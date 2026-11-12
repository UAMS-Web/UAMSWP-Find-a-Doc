<?php

/**
 * Unit tests for matching Business Profile listings by a stored Google location ID: what is stored where,
 * the orphan dates, and the backfill and CID report.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UAMSWP_FAD_GBP_Logic;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\when;

final class GbpStoredIdTest extends UnitTestCase
{
    /** @var array<int, array<string, mixed>> */
    private array $meta = [];

    /** @var array<string, array<int, int>> Listing name => other posts that store it. */
    private array $holders = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->meta = [];
        $this->holders = [];
        when('get_posts')->alias(fn (array $args): array => $this->holders[$args['meta_query'][0]['value']] ?? []);
        when('get_post_meta')->alias(fn (int $id, string $key) => $this->meta[$id][$key] ?? '');
        when('update_post_meta')->alias(function (int $id, string $key, $value): bool {
            $this->meta[$id][$key] = $value;

            return true;
        });
        when('delete_post_meta')->alias(function (int $id, string $key): bool {
            unset($this->meta[$id][$key]);

            return true;
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function provider_item(int $provider, int $location, string $code = 'jane_clinic'): array
    {
        return ['store_code' => $code, 'kind' => 'provider', 'post_id' => $provider, 'location_id' => $location];
    }

    /**
     * @return array{account: string, listing: array<string, mixed>}
     */
    private function listing(string $name, string $code, ?string $cid = null): array
    {
        return ['account' => 'accounts/up', 'listing' => ['name' => $name, 'storeCode' => $code] + ($cid === null ? [] : ['metadata' => ['mapsUri' => 'https://maps.google.com/?cid='.$cid]])];
    }

    public function test_a_matched_location_listing_is_stored_on_the_location_and_read_back_onto_its_item(): void
    {
        $item = ['store_code' => 'clinic', 'kind' => 'location', 'post_id' => 7, 'location_id' => 7];

        uamswp_fad_gbp_store_listing_ids([$item], [['mode' => 'live', 'listing_name' => 'locations/55']]);

        $this->assertSame('locations/55', $this->meta[7][UAMSWP_FAD_GBP_LISTING_META]);
        $this->assertSame('locations/55', uamswp_fad_gbp_attach_stored_ids([$item])[0]['stored_name']);
    }

    public function test_a_provider_keeps_one_listing_per_location_and_each_reads_back_for_its_own_location(): void
    {
        $items = [$this->provider_item(3, 10, 'jane_a'), $this->provider_item(3, 11, 'jane_b')];

        uamswp_fad_gbp_store_listing_ids($items, [['mode' => 'live', 'listing_name' => 'locations/1'], ['mode' => 'live', 'listing_name' => 'locations/2']]);
        $read = uamswp_fad_gbp_attach_stored_ids($items);

        $this->assertSame(['locations/1', 'locations/2'], array_column($read, 'stored_name'));
        $this->assertSame([10, 11], array_keys($this->meta[3][UAMSWP_FAD_GBP_LISTING_MAP_META]));
    }

    public function test_nothing_is_stored_for_a_dry_run_or_an_entry_with_no_listing(): void
    {
        $item = $this->provider_item(3, 10);

        uamswp_fad_gbp_store_listing_ids([$item, $item], [['mode' => 'dry-run', 'listing_name' => 'locations/1'], ['mode' => 'live', 'listing_name' => '']]);

        $this->assertSame([], $this->meta);
    }

    public function test_removing_a_provider_from_one_location_dates_that_entry_only_and_keeps_the_first_date(): void
    {
        when('get_post_type')->justReturn('provider');
        $this->meta[3][UAMSWP_FAD_GBP_LISTING_MAP_META] = [
            10 => ['id' => 'locations/1', 'orphaned_since' => null],
            11 => ['id' => 'locations/2', 'orphaned_since' => null],
        ];
        $now = strtotime('2026-11-03 12:00:00 UTC');

        uamswp_fad_gbp_mark_orphans(3, [$this->provider_item(3, 10)], $now);
        uamswp_fad_gbp_mark_orphans(3, [$this->provider_item(3, 10)], $now + 86400 * 5);

        $map = $this->meta[3][UAMSWP_FAD_GBP_LISTING_MAP_META];
        $this->assertNull($map[10]['orphaned_since']);
        $this->assertSame('2026-11-03', $map[11]['orphaned_since']);
        $this->assertSame('locations/2', $map[11]['id'], 'the entry is kept, not removed');
    }

    public function test_returning_to_a_location_clears_its_orphan_date(): void
    {
        $map = [10 => ['id' => 'locations/1', 'orphaned_since' => '2026-11-01']];

        $this->assertNull(UAMSWP_FAD_GBP_Logic::mark_orphans($map, [10], '2026-11-03')[10]['orphaned_since']);
    }

    public function test_a_location_post_is_never_given_an_orphan_map(): void
    {
        when('get_post_type')->justReturn('location');

        uamswp_fad_gbp_mark_orphans(7, [], 1);

        $this->assertSame([], $this->meta);
    }

    public function test_cids_are_read_from_nested_values_and_the_maps_link(): void
    {
        $value = ['schema_google_cid_multiple' => [['schema_google_cid_text' => '12345678901234567890'], ['schema_google_cid_text' => 'n/a']]];

        $this->assertSame(['12345678901234567890'], UAMSWP_FAD_GBP_Logic::cids_from_value($value));
        $this->assertSame([], UAMSWP_FAD_GBP_Logic::cids_from_value(null));
        $this->assertSame('987654321012345678', UAMSWP_FAD_GBP_Logic::cid_from_maps_uri('https://maps.google.com/?cid=987654321012345678&g_mp=x'));
        $this->assertNull(UAMSWP_FAD_GBP_Logic::cid_from_maps_uri('https://maps.google.com/?q=clinic'));
    }

    public function test_the_backfill_plan_matches_by_store_code_then_by_cid_and_lists_the_rest(): void
    {
        $listings = [
            $this->listing('locations/1', 'jane_clinic'),
            $this->listing('locations/5', 'solo'),
            $this->listing('locations/2', 'old-slug', '11111111111111111111'),
            $this->listing('locations/3', 'dup'),
            $this->listing('locations/4', 'dup'),
        ];
        $items = [
            $this->provider_item(3, 10, 'solo'),
            $this->provider_item(4, 10, 'new-slug'),
            $this->provider_item(5, 10, 'dup'),
            $this->provider_item(6, 10, 'nothing'),
            $this->provider_item(7, 10, 'jane_clinic') + ['stored_name' => 'locations/1'],
        ];

        $plan = UAMSWP_FAD_GBP_Logic::backfill_plan($items, $listings, [4 => ['11111111111111111111']]);

        $this->assertSame([['locations/5'], ['locations/2']], [array_column($plan['by_store_code'], 'name'), array_column($plan['by_cid'], 'name')]);
        $this->assertSame(['dup', 'nothing'], array_column($plan['unmatched'], 'store_code'));
        $this->assertCount(1, $plan['already']);
    }

    public function test_a_providers_cid_is_not_used_when_it_has_several_locations_and_never_when_two_listings_share_it(): void
    {
        $listings = [$this->listing('locations/2', 'old', '11111111111111111111'), $this->listing('locations/9', 'old2', '11111111111111111111')];
        $two = [$this->provider_item(4, 10, 'a'), $this->provider_item(4, 11, 'b')];

        $this->assertCount(2, UAMSWP_FAD_GBP_Logic::backfill_plan($two, [$listings[0]], [4 => ['11111111111111111111']])['unmatched']);
        $this->assertCount(1, UAMSWP_FAD_GBP_Logic::backfill_plan([$this->provider_item(4, 10, 'a')], $listings, [4 => ['11111111111111111111']])['unmatched']);
    }

    public function test_the_cid_report_lists_yes_providers_without_a_listing_and_listings_without_a_cid(): void
    {
        $listings = [$this->listing('locations/1', 'a', '11111111111111111111'), $this->listing('locations/2', 'b', '22222222222222222222'), $this->listing('locations/3', 'c')];
        $posts = [['id' => 1, 'label' => 'One', 'cids' => ['11111111111111111111']], ['id' => 2, 'label' => 'Two', 'cids' => ['99999999999999999999']]];

        $report = UAMSWP_FAD_GBP_Logic::cid_report([$posts[0], $posts[1]], $posts, $listings);

        $this->assertSame(['Two'], $report['providers_without_listing']);
        $this->assertSame(['b', 'c'], $report['listings_without_cid']);
    }

    public function test_the_backfill_writes_only_with_apply_and_never_touches_the_public_cid_fields(): void
    {
        when('uamswp_fad_gbp_credentials')->justReturn(['client_id' => 'x']);
        when('get_post_type')->justReturn('provider');
        when('get_field')->justReturn('');
        when('delete_transient')->justReturn(true);
        when('get_transient')->justReturn([$this->listing('locations/1', 'jane_clinic')]);
        when('update_field')->alias(function (): never {
            throw new \LogicException('the public CID field must not be written');
        });
        $client = (new \ReflectionClass(\UAMSWP_FAD_GBP_Client::class))->newInstanceWithoutConstructor();
        $lines = [];
        $items_for = fn (int $id): array => [$this->provider_item($id, 10)];

        uamswp_fad_gbp_backfill_ids([3], false, function (string $line) use (&$lines): void {
            $lines[] = $line;
        }, $client, $items_for);

        $this->assertSame([], $this->meta, 'a dry run writes nothing');

        uamswp_fad_gbp_backfill_ids([3], true, function (string $line) use (&$lines): void {
            $lines[] = $line;
        }, $client, $items_for);

        $this->assertSame('locations/1', $this->meta[3][UAMSWP_FAD_GBP_LISTING_MAP_META][10]['id']);
        $this->assertStringContainsString('1 matched by store code, 0 matched by CID, 0 unmatched', end($lines));
    }

    public function test_a_name_another_post_also_stores_is_not_trusted_and_not_written(): void
    {
        $item = ['store_code' => 'clone', 'kind' => 'location', 'post_id' => 8, 'location_id' => 8];
        $this->meta[8][UAMSWP_FAD_GBP_LISTING_META] = 'locations/111';
        $this->holders['locations/111'] = [7];

        $this->assertSame('', uamswp_fad_gbp_attach_stored_ids([$item])[0]['stored_name']);

        unset($this->meta[8]);
        uamswp_fad_gbp_write_listing_id($item, 'locations/111');

        $this->assertSame([], $this->meta);
    }

    public function test_an_item_without_a_resolved_post_or_location_is_never_stored_under_key_zero(): void
    {
        uamswp_fad_gbp_write_listing_id($this->provider_item(3, 0), 'locations/1');
        uamswp_fad_gbp_write_listing_id(['store_code' => 'x', 'kind' => 'location'], 'locations/1');

        $this->assertSame([], $this->meta);
        $this->assertCount(1, UAMSWP_FAD_GBP_Logic::backfill_plan([$this->provider_item(3, 0, 'jane_clinic')], [$this->listing('locations/1', 'jane_clinic')], [])['unmatched']);
    }

    public function test_the_backfill_never_gives_one_listing_to_two_posts(): void
    {
        $listings = [$this->listing('locations/1', 'jane_clinic'), $this->listing('locations/2', 'old', '11111111111111111111')];
        $items = [$this->provider_item(3, 10, 'jane_clinic'), $this->provider_item(4, 10, 'new-a'), $this->provider_item(5, 10, 'new-b'), $this->provider_item(6, 10, 'new-c') + ['stored_name' => 'locations/2']];
        $cids = [4 => ['11111111111111111111'], 5 => ['11111111111111111111']];

        $plan = UAMSWP_FAD_GBP_Logic::backfill_plan($items, $listings, $cids);

        $this->assertSame(['locations/1'], array_column($plan['by_store_code'], 'name'));
        $this->assertSame([], $plan['by_cid'], 'locations/2 is already stored on post 6');
        $this->assertSame(['new-a', 'new-b'], array_column($plan['unmatched'], 'store_code'));
    }

    public function test_a_number_field_cid_is_not_read_in_scientific_notation(): void
    {
        $this->assertMatchesRegularExpression('/^\d{19,20}$/', UAMSWP_FAD_GBP_Logic::cids_from_value(1.2345678901234567E+19)[0]);
    }

    public function test_the_photo_rules_come_from_the_row_and_a_provider_has_no_other_photos(): void
    {
        $row = array_fill(0, 22, '');
        $row[18] = 'https://x/logo.png';
        $row[19] = 'https://x/cover.jpg';
        $row[20] = 'https://x/a.jpg, https://x/b.jpg,';

        $this->assertSame(['LOGO', 'COVER', 'ADDITIONAL', 'ADDITIONAL'], array_column(UAMSWP_FAD_GBP_Logic::photos_from_row($row), 'category'));
        $this->assertSame(['LOGO', 'COVER'], array_column(UAMSWP_FAD_GBP_Logic::photos_from_row($row, false), 'category'));

        $row[19] = '';

        $this->assertSame(['LOGO', 'ADDITIONAL', 'ADDITIONAL'], array_column(UAMSWP_FAD_GBP_Logic::photos_from_row($row), 'category'), 'a provider with no wide image has the logo only');
    }

    public function test_only_photos_not_yet_sent_are_pending_and_one_address_is_sent_once(): void
    {
        $photos = [['category' => 'LOGO', 'url' => 'u1'], ['category' => 'COVER', 'url' => 'u2'], ['category' => 'ADDITIONAL', 'url' => 'u2'], ['category' => 'ADDITIONAL', 'url' => 'u3']];

        $this->assertSame(['u2', 'u3'], array_column(UAMSWP_FAD_GBP_Logic::photos_to_send($photos, ['u1']), 'url'));
        $this->assertSame([], UAMSWP_FAD_GBP_Logic::photos_to_send($photos, ['u1', 'u2', 'u3']));
    }

    public function test_sent_photos_are_recorded_with_the_listing_and_start_over_for_a_new_one(): void
    {
        $item = ['store_code' => 'c', 'kind' => 'location', 'post_id' => 7, 'location_id' => 7];
        $this->meta[7][UAMSWP_FAD_GBP_LISTING_META] = 'locations/1';

        uamswp_fad_gbp_write_photos_sent($item, ['listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => ['u1']]);
        uamswp_fad_gbp_write_photos_sent($item, ['listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => ['u2']]);

        $this->assertSame(['u1', 'u2'], $this->meta[7][UAMSWP_FAD_GBP_PHOTOS_META]);
        $this->assertSame(['u1', 'u2'], uamswp_fad_gbp_attach_stored_ids([$item])[0]['photos_sent']);

        $this->meta[7][UAMSWP_FAD_GBP_LISTING_META] = 'locations/2';
        uamswp_fad_gbp_write_photos_sent($item, ['listing_name' => 'locations/2', 'action' => 'create', 'photos_sent_now' => ['u3']]);

        $this->assertSame(['u3'], $this->meta[7][UAMSWP_FAD_GBP_PHOTOS_META]);
    }

    public function test_a_provider_s_sent_photos_are_kept_per_location_and_survive_clearing_an_orphan_date(): void
    {
        $item = $this->provider_item(3, 10);
        $this->meta[3][UAMSWP_FAD_GBP_LISTING_MAP_META] = [10 => ['id' => 'locations/1', 'orphaned_since' => '2026-11-01', 'photos' => ['u1']]];

        uamswp_fad_gbp_store_listing_ids([$item], [['mode' => 'live', 'listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => ['u2']]]);

        $entry = $this->meta[3][UAMSWP_FAD_GBP_LISTING_MAP_META][10];
        $this->assertNull($entry['orphaned_since']);
        $this->assertSame(['u1', 'u2'], $entry['photos']);
    }

    public function test_photos_are_not_recorded_for_a_listing_whose_id_was_not_stored(): void
    {
        $item = ['store_code' => 'c', 'kind' => 'location', 'post_id' => 7, 'location_id' => 7];

        uamswp_fad_gbp_write_photos_sent($item, ['listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => ['u1']]);

        $this->assertSame([], $this->meta);
    }
}
