<?php

/**
 * Unit tests for the edges of the Business Profile runtime's reads and writes of post meta, and of its queue.
 *
 * Post meta is stubbed the way WordPress answers it: a single read gives the stored value or an empty string, and
 * a read that is not single gives a list of values. So a read that drops `$single` is told apart from one that keeps it.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

final class GbpRuntimeEdgesTest extends UnitTestCase
{
    /** @var array<int, array<string, mixed>> */
    private array $meta = [];

    /** @var list<array{0: int, 1: string, 2: mixed}> */
    private array $updates = [];

    /** @var list<array{0: int, 1: string}> */
    private array $deletes = [];

    /** @var array<int, array<int, mixed>> */
    private array $scheduled = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->meta = [];
        $this->updates = [];
        $this->deletes = [];
        $this->scheduled = [];
    }

    /**
     * Stub post meta as WordPress stores it, and record every write.
     *
     * @param  array<int, array<string, mixed>>  $meta
     */
    private function wireMeta(array $meta): void
    {
        $this->meta = $meta;
        when('get_post_meta')->alias(function (int $id, string $key = '', bool $single = false) {
            if (! array_key_exists($key, $this->meta[$id] ?? [])) {
                return $single ? '' : [];
            }

            return $single ? $this->meta[$id][$key] : [$this->meta[$id][$key]];
        });
        when('update_post_meta')->alias(function (int $id, string $key, $value): bool {
            $this->updates[] = [$id, $key, $value];
            $this->meta[$id][$key] = $value;

            return true;
        });
        when('delete_post_meta')->alias(function (int $id, string $key): bool {
            $this->deletes[] = [$id, $key];
            unset($this->meta[$id][$key]);

            return true;
        });
        // No other post holds any listing.
        when('get_posts')->justReturn([]);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    private static function location(int $post_id, array $extra = []): array
    {
        return $extra + ['store_code' => 'clinic', 'kind' => 'location', 'post_id' => $post_id, 'location_id' => $post_id];
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    private static function provider(array $extra = []): array
    {
        return $extra + ['store_code' => 'jane_clinic', 'kind' => 'provider', 'post_id' => 7, 'location_id' => 55];
    }

    private function recordSchedule(): void
    {
        when('wp_next_scheduled')->alias(function (string $hook, array $args) {
            foreach ($this->scheduled as $call) {
                if ($call[1] === $hook && $call[2] === $args) {
                    return $call[0];
                }
            }

            return false;
        });
        when('wp_schedule_single_event')->alias(function (int $time, string $hook, array $args): bool {
            $this->scheduled[] = [$time, $hook, $args];

            return true;
        });
    }

    private function enable(): void
    {
        when('apply_filters')->alias(fn (string $hook, $value) => $value);
        when('get_option')->alias(fn (string $name, $default = false) => $default);
    }

    private function creds(): callable
    {
        return fn (string $name): string => 'value-of-'.$name;
    }

    // Photos sent to a location's listing.

    public function test_a_location_s_sent_photos_are_read_single_kept_as_strings_and_listed_once(): void
    {
        $this->wireMeta([55 => [UAMSWP_FAD_GBP_LISTING_META => 'locations/1', UAMSWP_FAD_GBP_PHOTOS_META => [5, 'u1']]]);

        uamswp_fad_gbp_write_photos_sent(self::location(55), ['listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => ['u1', 'u2']]);

        $this->assertSame([[55, UAMSWP_FAD_GBP_PHOTOS_META, ['5', 'u1', 'u2']]], $this->updates);
    }

    public function test_one_photo_sent_as_a_string_is_recorded_as_a_list(): void
    {
        $this->wireMeta([55 => [UAMSWP_FAD_GBP_LISTING_META => 'locations/1']]);

        uamswp_fad_gbp_write_photos_sent(self::location(55), ['listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => 'u3']);

        $this->assertSame([[55, UAMSWP_FAD_GBP_PHOTOS_META, ['u3']]], $this->updates);
    }

    public function test_a_photo_address_that_is_not_a_string_is_recorded_as_one(): void
    {
        $this->wireMeta([55 => [UAMSWP_FAD_GBP_LISTING_META => 'locations/1']]);

        uamswp_fad_gbp_write_photos_sent(self::location(55), ['listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => [7]]);

        $this->assertSame([[55, UAMSWP_FAD_GBP_PHOTOS_META, ['7']]], $this->updates);
    }

    public function test_photos_sent_under_a_key_do_not_replace_a_stored_photo_under_the_same_key(): void
    {
        $this->wireMeta([55 => [UAMSWP_FAD_GBP_LISTING_META => 'locations/1', UAMSWP_FAD_GBP_PHOTOS_META => ['x' => 'u1']]]);

        uamswp_fad_gbp_write_photos_sent(self::location(55), ['listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => ['x' => 'u2']]);

        $this->assertSame([[55, UAMSWP_FAD_GBP_PHOTOS_META, ['u1', 'u2']]], $this->updates);
    }

    /**
     * @return array<string, array{0: array<string, mixed>, 1: array<string, mixed>}>
     */
    public static function photo_writes_with_nothing_to_record(): array
    {
        $entry = ['listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => ['u1']];

        return [
            'no post ID' => [['store_code' => 'clinic', 'kind' => 'location'], $entry],
            'post 0' => [self::location(0), $entry],
            'no listing name' => [self::location(55), ['action' => 'update', 'photos_sent_now' => ['u1']]],
            'an empty listing name' => [self::location(55), ['listing_name' => ''] + $entry],
            'no photos sent' => [self::location(55), ['photos_sent_now' => []] + $entry],
        ];
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  array<string, mixed>  $entry
     */
    #[DataProvider('photo_writes_with_nothing_to_record')]
    public function test_a_photo_write_with_no_post_name_or_photo_reads_nothing(array $item, array $entry): void
    {
        expect('get_post_meta')->never();
        expect('update_post_meta')->never();

        uamswp_fad_gbp_write_photos_sent($item, $entry);
    }

    // Photos sent to a provider's listing at a location.

    public function test_a_provider_s_sent_photos_are_read_single_kept_as_strings_and_listed_once(): void
    {
        $this->wireMeta([7 => [UAMSWP_FAD_GBP_LISTING_MAP_META => [55 => ['id' => 'locations/1', 'orphaned_since' => null, 'photos' => [9, 'u1']]]]]);

        uamswp_fad_gbp_write_photos_sent(self::provider(), ['listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => ['u1', 'u2']]);

        $this->assertSame([[7, UAMSWP_FAD_GBP_LISTING_MAP_META, [55 => ['id' => 'locations/1', 'orphaned_since' => null, 'photos' => ['9', 'u1', 'u2']]]]], $this->updates);
    }

    public function test_a_provider_item_with_no_location_records_no_photos(): void
    {
        $item = self::provider();
        unset($item['location_id']);
        $this->wireMeta([7 => [UAMSWP_FAD_GBP_LISTING_MAP_META => [1 => ['id' => 'locations/1', 'orphaned_since' => null], -1 => ['id' => 'locations/1', 'orphaned_since' => null]]]]);

        uamswp_fad_gbp_write_photos_sent($item, ['listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => ['u1']]);

        $this->assertSame([], $this->updates);
    }

    public function test_a_provider_map_that_is_not_an_array_records_no_photos(): void
    {
        $this->wireMeta([7 => [UAMSWP_FAD_GBP_LISTING_MAP_META => 'junk']]);

        uamswp_fad_gbp_write_photos_sent(self::provider(), ['listing_name' => 'locations/1', 'action' => 'update', 'photos_sent_now' => ['u1']]);

        $this->assertSame([], $this->updates);
    }

    public function test_a_provider_item_with_no_location_reads_no_sent_photos(): void
    {
        $item = self::provider(['stored_name' => 'locations/1']);
        unset($item['location_id']);
        $this->wireMeta([7 => [UAMSWP_FAD_GBP_LISTING_MAP_META => [1 => ['id' => 'locations/1', 'photos' => ['a']], -1 => ['id' => 'locations/1', 'photos' => ['b']]]]]);

        $this->assertSame([], uamswp_fad_gbp_photos_sent_for($item));
    }

    // The stored listing names attached to the items.

    public function test_a_post_id_given_as_a_string_still_reads_its_stored_listing(): void
    {
        $this->wireMeta([55 => [UAMSWP_FAD_GBP_LISTING_META => 'locations/1']]);

        $this->assertSame('locations/1', uamswp_fad_gbp_attach_stored_ids([self::location(55, ['post_id' => '55'])])[0]['stored_name']);
    }

    public function test_an_item_with_no_post_is_skipped_and_the_next_one_is_still_read(): void
    {
        $this->wireMeta([55 => [UAMSWP_FAD_GBP_LISTING_META => 'locations/1']]);

        $items = uamswp_fad_gbp_attach_stored_ids([self::location(0), self::location(55)]);

        $this->assertArrayNotHasKey('stored_name', $items[0]);
        $this->assertSame('locations/1', $items[1]['stored_name']);
    }

    public function test_a_provider_s_stored_listing_is_read_single_from_its_map(): void
    {
        $this->wireMeta([7 => [UAMSWP_FAD_GBP_LISTING_MAP_META => [55 => ['id' => 'locations/1', 'orphaned_since' => null]]]]);

        $this->assertSame('locations/1', uamswp_fad_gbp_attach_stored_ids([self::provider()])[0]['stored_name']);
    }

    public function test_a_location_id_that_reads_as_a_number_finds_its_map_entry(): void
    {
        $this->wireMeta([7 => [UAMSWP_FAD_GBP_LISTING_MAP_META => [56 => ['id' => 'locations/2', 'orphaned_since' => null]]]]);

        $this->assertSame('locations/2', uamswp_fad_gbp_attach_stored_ids([self::provider(['location_id' => '056'])])[0]['stored_name']);
    }

    /**
     * @return array<string, array{0: mixed, 1?: bool}>
     */
    public static function maps_with_no_listing_for_the_item(): array
    {
        return [
            'an entry with no ID' => [[55 => ['orphaned_since' => null]]],
            'a map that is not an array' => ['junk'],
            'no entry for location 0, only for 1 and -1' => [[1 => ['id' => 'locations/1'], -1 => ['id' => 'locations/1']], true],
        ];
    }

    #[DataProvider('maps_with_no_listing_for_the_item')]
    public function test_a_provider_item_with_no_listing_in_its_map_gets_no_stored_name(mixed $map, bool $no_location = false): void
    {
        $item = self::provider();
        if ($no_location) {
            unset($item['location_id']);
        }

        $this->wireMeta([7 => [UAMSWP_FAD_GBP_LISTING_MAP_META => $map]]);

        $this->assertSame('', uamswp_fad_gbp_attach_stored_ids([$item])[0]['stored_name']);
    }

    public function test_an_item_with_no_stored_listing_does_not_look_for_another_holder(): void
    {
        $this->wireMeta([]);
        $looked = false;
        when('get_posts')->alias(function () use (&$looked): array {
            $looked = true;

            return [];
        });

        $this->assertSame('', uamswp_fad_gbp_attach_stored_ids([self::location(55)])[0]['stored_name']);
        $this->assertFalse($looked);
    }

    // Storing the listing names a run matched or created.

    public function test_an_entry_is_paired_with_its_item_by_position_not_by_key(): void
    {
        $this->wireMeta([]);

        uamswp_fad_gbp_store_listing_ids([5 => self::location(55)], [0 => ['mode' => 'live', 'listing_name' => 'locations/1']]);

        $this->assertSame([[55, UAMSWP_FAD_GBP_LISTING_META, 'locations/1']], $this->updates);
    }

    /**
     * @return array<string, array{0: array<string, mixed>}>
     */
    public static function entries_with_no_name_to_store(): array
    {
        return [
            'no listing name' => [['mode' => 'live']],
            'an empty listing name' => [['mode' => 'live', 'listing_name' => '']],
            'a dry run' => [['mode' => 'dry-run', 'listing_name' => 'locations/1']],
        ];
    }

    /**
     * @param  array<string, mixed>  $entry
     */
    #[DataProvider('entries_with_no_name_to_store')]
    public function test_an_entry_with_no_live_listing_name_stores_nothing(array $entry): void
    {
        $this->wireMeta([55 => [UAMSWP_FAD_GBP_LISTING_META => 'locations/9']]);

        uamswp_fad_gbp_store_listing_ids([self::location(55)], [$entry]);

        $this->assertSame([], $this->updates);
    }

    public function test_a_skipped_entry_does_not_stop_the_next_one_being_stored(): void
    {
        $this->wireMeta([]);

        uamswp_fad_gbp_store_listing_ids([self::location(55), self::location(56)], [['mode' => 'dry-run', 'listing_name' => 'locations/1'], ['mode' => 'live', 'listing_name' => 'locations/2']]);

        $this->assertSame([[56, UAMSWP_FAD_GBP_LISTING_META, 'locations/2']], $this->updates);
    }

    // Writing one listing name.

    public function test_a_provider_item_with_no_location_writes_no_listing_name(): void
    {
        $item = self::provider();
        unset($item['location_id']);
        $this->wireMeta([]);

        uamswp_fad_gbp_write_listing_id($item, 'locations/1');

        $this->assertSame([], $this->updates);
    }

    public function test_a_location_s_unchanged_listing_name_is_read_single_and_not_rewritten(): void
    {
        $this->wireMeta([55 => [UAMSWP_FAD_GBP_LISTING_META => 'locations/1', UAMSWP_FAD_GBP_PHOTOS_META => ['u1']]]);

        uamswp_fad_gbp_write_listing_id(self::location(55), 'locations/1');

        $this->assertSame([[], []], [$this->updates, $this->deletes]);
    }

    public function test_a_location_s_new_listing_forgets_the_photos_sent_to_the_old_one(): void
    {
        $this->wireMeta([55 => [UAMSWP_FAD_GBP_LISTING_META => 'locations/1', UAMSWP_FAD_GBP_PHOTOS_META => ['u1']]]);

        uamswp_fad_gbp_write_listing_id(self::location(55), 'locations/2');

        $this->assertSame([[55, UAMSWP_FAD_GBP_LISTING_META, 'locations/2']], $this->updates);
        $this->assertSame([[55, UAMSWP_FAD_GBP_PHOTOS_META]], $this->deletes);
    }

    public function test_a_provider_s_unchanged_map_entry_is_read_single_and_not_rewritten(): void
    {
        $this->wireMeta([7 => [UAMSWP_FAD_GBP_LISTING_MAP_META => [55 => ['id' => 'locations/1', 'orphaned_since' => null]]]]);

        uamswp_fad_gbp_write_listing_id(self::provider(), 'locations/1');

        $this->assertSame([], $this->updates);
    }

    // Dating a provider's orphaned map entries.

    public function test_the_orphan_map_is_read_single(): void
    {
        when('get_post_type')->justReturn('provider');
        $this->wireMeta([7 => [UAMSWP_FAD_GBP_LISTING_MAP_META => [55 => ['id' => 'locations/1', 'orphaned_since' => null], 56 => ['id' => 'locations/2', 'orphaned_since' => null]]]]);

        uamswp_fad_gbp_mark_orphans(7, [self::provider()], strtotime('2026-11-19 12:00:00 UTC'));

        $this->assertSame([[7, UAMSWP_FAD_GBP_LISTING_MAP_META, [55 => ['id' => 'locations/1', 'orphaned_since' => null], 56 => ['id' => 'locations/2', 'orphaned_since' => '2026-11-19']]]], $this->updates);
    }

    public function test_an_item_with_no_location_keeps_no_map_entry_current(): void
    {
        when('get_post_type')->justReturn('provider');
        $item = self::provider();
        unset($item['location_id']);
        $this->wireMeta([7 => [UAMSWP_FAD_GBP_LISTING_MAP_META => [1 => ['id' => 'locations/1', 'orphaned_since' => null], -1 => ['id' => 'locations/2', 'orphaned_since' => null]]]]);

        uamswp_fad_gbp_mark_orphans(7, [$item], strtotime('2026-11-19 12:00:00 UTC'));

        $this->assertSame([1 => '2026-11-19', -1 => '2026-11-19'], array_map(fn (array $entry): ?string => $entry['orphaned_since'], $this->updates[0][2]));
    }

    public function test_an_orphan_map_that_is_not_an_array_is_left_alone(): void
    {
        when('get_post_type')->justReturn('provider');
        $this->wireMeta([7 => [UAMSWP_FAD_GBP_LISTING_MAP_META => 'junk']]);

        uamswp_fad_gbp_mark_orphans(7, [], 1);

        $this->assertSame([], $this->updates);
    }

    // The location a provider row names, and a location's own item.

    public function test_a_location_is_looked_up_by_its_slug_as_one_published_id(): void
    {
        $queries = [];
        when('get_posts')->alias(function (array $args) use (&$queries): array {
            $queries[] = $args;

            return [];
        });

        uamswp_fad_gbp_location_info('main-clinic');

        $this->assertSame([['name' => 'main-clinic', 'post_type' => 'location', 'post_status' => 'publish', 'posts_per_page' => 1, 'fields' => 'ids']], $queries);
    }

    public function test_a_location_item_carries_its_post_and_location_ids(): void
    {
        when('apply_filters')->alias(fn (string $hook, $value) => $value);
        $row = array_fill(0, 18, '');
        $row[0] = 'main-clinic';
        $row[1] = 'Main Clinic';
        require_once __DIR__.'/../Support/gmb-page.php';
        \uamswp_test_gmb_page();
        when('uamswp_fad_gmb_location_table')->justReturn(['head' => [], 'body' => [$row]]);
        when('get_post_meta')->justReturn('');

        $item = uamswp_fad_gbp_items_for_location(55, []);

        $this->assertSame([55, 55], [$item[0]['post_id'], $item[0]['location_id']]);
    }

    // The queue.

    public function test_a_status_change_on_a_post_with_no_id_queues_nothing(): void
    {
        $this->enable();
        $this->recordSchedule();
        when('get_post_status')->alias(fn (int $id) => in_array($id, [1, -1], true) ? 'publish' : false);
        when('get_post_type')->justReturn('provider');

        uamswp_fad_gbp_queue_on_publish('publish', 'draft', (object) [], $this->creds());

        $this->assertSame([], $this->scheduled);
    }

    public function test_the_save_queue_staggers_by_position_whatever_the_keys(): void
    {
        $this->enable();
        $this->recordSchedule();
        when('get_post_status')->justReturn('publish');
        when('uamswp_fad_gbp_covered_post_ids')->justReturn([5 => 7, 9 => 8]);

        $before = time();

        uamswp_fad_gbp_queue_on_save(55, $this->creds());

        $this->assertSame([[7], [8]], array_column($this->scheduled, 2));
        $this->assertSame(30, $this->scheduled[1][0] - $this->scheduled[0][0]);
        $this->assertLessThanOrEqual(time() + 120, $this->scheduled[0][0]);
        $this->assertGreaterThanOrEqual($before + 120, $this->scheduled[0][0]);
    }

    public function test_a_post_already_queued_by_a_save_does_not_stop_the_next_one(): void
    {
        $this->enable();
        $this->recordSchedule();
        $this->scheduled[] = [123, UAMSWP_FAD_GBP_SYNC_HOOK, [7]];
        when('get_post_status')->justReturn('publish');
        when('uamswp_fad_gbp_covered_post_ids')->justReturn([7, 8]);

        uamswp_fad_gbp_queue_on_save(55, $this->creds());

        $this->assertSame([[7], [8]], array_column($this->scheduled, 2));
    }

    public function test_a_post_already_queued_by_a_full_run_does_not_stop_the_next_one(): void
    {
        $this->enable();
        $this->recordSchedule();
        $this->scheduled[] = [123, UAMSWP_FAD_GBP_SYNC_HOOK, [7]];
        when('uamswp_fad_gbp_all_post_ids')->justReturn([7, 8]);
        when('uamswp_fad_gbp_has_silo_to_sync')->justReturn(true);
        when('delete_post_meta')->justReturn(true);

        $this->assertSame(1, uamswp_fad_gbp_queue_all($this->creds()));
        $this->assertSame([[7], [8]], array_column($this->scheduled, 2));
    }

    public function test_a_production_filter_that_returns_a_truthy_value_reads_as_production(): void
    {
        when('home_url')->justReturn('https://uamshealth.dev');
        when('wp_parse_url')->alias('parse_url');
        when('apply_filters')->justReturn(1);

        $this->assertTrue(uamswp_fad_gbp_is_production());
    }

    // Retries.

    public function test_a_retry_before_the_first_attempt_waits_a_minute(): void
    {
        $this->assertSame(60, uamswp_fad_gbp_retry_delay(0));
    }

    public function test_the_last_allowed_attempt_is_still_retried(): void
    {
        when('get_transient')->justReturn(UAMSWP_FAD_GBP_MAX_ATTEMPTS - 1);
        when('set_transient')->justReturn(true);
        when('get_option')->justReturn(0);
        when('update_option')->justReturn(true);
        $this->recordSchedule();

        uamswp_fad_gbp_schedule_retry(7);

        $this->assertSame([[7]], array_column($this->scheduled, 2));
    }

    public function test_a_retry_for_a_post_already_queued_queues_it_no_second_time(): void
    {
        when('get_transient')->justReturn(false);
        when('set_transient')->justReturn(true);
        when('get_option')->justReturn(0);

        expect('update_option')->never();
        $this->recordSchedule();
        $this->scheduled[] = [123, UAMSWP_FAD_GBP_SYNC_HOOK, [7]];

        uamswp_fad_gbp_schedule_retry(7);

        $this->assertCount(1, $this->scheduled);
    }

    public function test_a_backoff_ending_at_the_same_moment_as_the_stored_one_is_not_rewritten(): void
    {
        when('get_transient')->justReturn(false);
        when('set_transient')->justReturn(true);
        // The stored backoff ends when this retry's would, read at the moment the retry is scheduled.
        when('get_option')->alias(fn (string $name, $default = false) => $name === UAMSWP_FAD_GBP_BACKOFF_OPTION ? time() + 60 : $default);

        expect('update_option')->never();
        $this->recordSchedule();

        uamswp_fad_gbp_schedule_retry(7);

        $this->assertCount(1, $this->scheduled);
    }
}
