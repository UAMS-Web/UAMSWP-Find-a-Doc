<?php

/**
 * Unit tests for the Business Profile sync's reads of WordPress and its caches.
 *
 * Pins the silo list, the posts a save covers, the queries behind them, the cached listing index and
 * categories, the photos already sent and the provider items, each with the exact arguments it sends.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UAMSWP_FAD_GBP_Client;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

final class GbpRuntimeQueriesTest extends UnitTestCase
{
    /** @var array<int, array<string, mixed>> */
    private array $queries = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->queries = [];
    }

    /**
     * Record every get_posts() query and answer it with the given function.
     *
     * @param  callable(array<string, mixed>): array<int, mixed>  $answer
     */
    private function recordQueries(callable $answer): void
    {
        when('get_posts')->alias(function (array $args) use ($answer): array {
            $this->queries[] = $args;

            return $answer($args);
        });
    }

    public function test_the_silos_are_the_three_brands_and_their_account_groups(): void
    {
        expect('apply_filters')->once()->andReturnUsing(fn (string $hook, $value) => $hook === 'uamswp_fad_gbp_silos' ? $value : null);

        $this->assertSame([
            'uams' => [
                'brand' => null,
                'accounts' => ['provider' => 'accounts/112667655000425532878', 'location' => 'accounts/105283038566196415769'],
            ],
            'arkansas-childrens' => [
                'brand' => 'arkansas-childrens',
                'accounts' => ['provider' => 'accounts/109394488038091564790', 'location' => 'accounts/117883475495991069009'],
            ],
            'baptist-health' => [
                'brand' => 'baptist-health',
                'accounts' => ['provider' => 'accounts/114902564947343534669', 'location' => 'accounts/107080311706776481588'],
            ],
        ], uamswp_fad_gbp_silos());
    }

    public function test_the_silos_filter_replaces_the_list(): void
    {
        $replacement = ['only' => ['brand' => 'only', 'accounts' => ['provider' => 'accounts/1', 'location' => 'accounts/2']]];
        when('apply_filters')->justReturn($replacement);

        $this->assertSame($replacement, uamswp_fad_gbp_silos());
    }

    public function test_a_provider_covers_only_itself(): void
    {
        when('get_post_type')->justReturn('provider');
        expect('get_posts')->never();

        $this->assertSame([7], uamswp_fad_gbp_covered_post_ids(7));
    }

    public function test_another_post_type_covers_nothing(): void
    {
        when('get_post_type')->justReturn('page');
        expect('get_posts')->never();

        $this->assertSame([], uamswp_fad_gbp_covered_post_ids(7));
    }

    public function test_a_location_covers_itself_its_children_and_the_providers_at_any_of_them(): void
    {
        when('get_post_type')->justReturn('location');
        $this->recordQueries(fn (array $args): array => $args['post_type'] === 'location' ? ['56', 57] : [7, '8']);

        $this->assertSame([55, 56, 57, 7, 8], uamswp_fad_gbp_covered_post_ids(55));
        $this->assertSame([
            ['post_type' => 'location', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => 'location_parent_id', 'meta_value' => '55'],
            ['post_type' => 'provider', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_query' => [
                'relation' => 'OR',
                ['key' => 'physician_locations', 'value' => '"55"', 'compare' => 'LIKE'],
                ['key' => 'physician_locations', 'value' => '"56"', 'compare' => 'LIKE'],
                ['key' => 'physician_locations', 'value' => '"57"', 'compare' => 'LIKE'],
            ]],
        ], $this->queries);
    }

    public function test_every_published_provider_comes_before_every_published_location(): void
    {
        $this->recordQueries(fn (array $args): array => $args['post_type'] === 'provider' ? ['7', 8] : [55, '56']);

        $this->assertSame([7, 8, 55, 56], uamswp_fad_gbp_all_post_ids());
        $this->assertSame([
            ['post_type' => 'provider', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids'],
            ['post_type' => 'location', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids'],
        ], $this->queries);
    }

    /**
     * Brand fields by location ID: `uams`, `baptist-health` or `none` for a brand with no silo.
     *
     * @param  array<int, string>  $brands
     */
    private function wireBrands(array $brands): void
    {
        $fields = ['uams' => ['0', ''], 'baptist-health' => ['1', 'baptist-health'], 'none' => ['1', 'dhs']];
        when('apply_filters')->alias(fn (string $hook, $value) => $value);
        when('get_post_meta')->alias(function (int $id, string $key, bool $single) use ($brands, $fields): string {
            $this->assertTrue($single);

            return $fields[$brands[$id] ?? 'uams'][$key === 'schema_brandorg_query' ? 0 : ($key === 'schema_brandorg' ? 1 : 2)] ?? 'unexpected key '.$key;
        });
    }

    public function test_a_location_s_silo_comes_from_its_own_brand_fields(): void
    {
        $this->wireBrands([55 => 'baptist-health', 56 => 'uams', 57 => 'none']);

        $this->assertSame(['baptist-health', 'uams', null], array_map(uamswp_fad_gbp_silo_for_location(...), [55, 56, 57]));
    }

    public function test_a_location_has_a_silo_to_sync_only_when_its_silo_is_not_off(): void
    {
        $this->wireBrands([55 => 'baptist-health']);
        when('get_post_type')->justReturn('location');
        expect('get_field')->never();

        $this->assertTrue(uamswp_fad_gbp_has_silo_to_sync(55, ['baptist-health' => 'dry-run', 'uams' => 'off']));
        $this->assertTrue(uamswp_fad_gbp_has_silo_to_sync(55, ['baptist-health' => 'live']));
        $this->assertFalse(uamswp_fad_gbp_has_silo_to_sync(55, ['baptist-health' => 'off', 'uams' => 'live']));
        $this->assertFalse(uamswp_fad_gbp_has_silo_to_sync(55, ['uams' => 'live']), 'a silo with no mode is off');
    }

    public function test_a_location_whose_brand_has_no_silo_has_one_to_sync(): void
    {
        $this->wireBrands([55 => 'none']);
        when('get_post_type')->justReturn('location');

        $this->assertTrue(uamswp_fad_gbp_has_silo_to_sync(55, []));
    }

    public function test_a_provider_has_a_silo_to_sync_through_any_published_location_it_lists(): void
    {
        $this->wireBrands([55 => 'uams', 56 => 'baptist-health', 57 => 'baptist-health']);
        when('get_post_type')->justReturn('provider');
        expect('get_field')->atLeast()->once()->with('physician_locations', 7)->andReturn(['55', 56, 57]);
        when('get_post_status')->alias(fn (int $id) => $id === 57 ? 'draft' : 'publish');

        $this->assertTrue(uamswp_fad_gbp_has_silo_to_sync(7, ['uams' => 'off', 'baptist-health' => 'live']), 'the second location is on');
        $this->assertTrue(uamswp_fad_gbp_has_silo_to_sync(7, ['uams' => 'live', 'baptist-health' => 'off']), 'the first location is on');
        $this->assertFalse(uamswp_fad_gbp_has_silo_to_sync(7, ['uams' => 'off', 'baptist-health' => 'off']));
    }

    public function test_a_provider_s_unpublished_location_does_not_count(): void
    {
        $this->wireBrands([57 => 'baptist-health']);
        when('get_post_type')->justReturn('provider');
        when('get_field')->justReturn([57]);
        when('get_post_status')->justReturn('draft');

        $this->assertFalse(uamswp_fad_gbp_has_silo_to_sync(7, ['baptist-health' => 'live']));
    }

    public function test_a_provider_with_no_location_field_has_nothing_to_sync(): void
    {
        $this->wireBrands([]);
        when('get_post_type')->justReturn('provider');
        when('get_field')->justReturn(false);
        when('get_post_status')->justReturn('publish');

        $this->assertFalse(uamswp_fad_gbp_has_silo_to_sync(7, ['uams' => 'live']));
    }

    /**
     * A client whose transport answers the token exchange, the account groups and each group's listings.
     *
     * @param  array<string, array<int, array<string, mixed>>>  $groups  Account => its listings.
     * @param  list<string>  $calls  The URLs requested, after the token.
     */
    private function client(array $groups, array &$calls, array $categories = []): UAMSWP_FAD_GBP_Client
    {
        return new UAMSWP_FAD_GBP_Client(['client_id' => 'a', 'client_secret' => 'b', 'refresh_token' => 'c'], function (string $method, string $url) use ($groups, $categories, &$calls): array {
            if (str_contains($url, 'oauth2.googleapis.com')) {
                return [200, (string) json_encode(['access_token' => 'tok', 'expires_in' => 3600])];
            }

            $calls[] = $url;

            if (str_contains($url, '/categories')) {
                return [200, (string) json_encode(['categories' => $categories])];
            }

            foreach ($groups as $account => $listings) {
                if (str_contains($url, '/'.$account.'/locations')) {
                    return [200, (string) json_encode(['locations' => $listings])];
                }
            }

            $accounts = array_map(fn (string $account): array => ['name' => $account, 'accountName' => $account, 'type' => 'LOCATION_GROUP'], array_keys($groups));

            return [200, (string) json_encode(['accounts' => $accounts])];
        });
    }

    public function test_the_index_is_read_from_every_group_and_kept_for_five_minutes(): void
    {
        $calls = [];
        $client = $this->client(['accounts/1' => [['name' => 'locations/a'], ['name' => 'locations/b']], 'accounts/2' => [['name' => 'locations/c']]], $calls);
        when('get_transient')->justReturn(false);
        $set = [];
        when('set_transient')->alias(function (string $key, $value, int $expiry) use (&$set): bool {
            $set[$key] = [$value, $expiry];

            return true;
        });
        $before = time();

        $index = uamswp_fad_gbp_index($client);

        $expected = [
            ['account' => 'accounts/1', 'listing' => ['name' => 'locations/a']],
            ['account' => 'accounts/1', 'listing' => ['name' => 'locations/b']],
            ['account' => 'accounts/2', 'listing' => ['name' => 'locations/c']],
        ];
        $this->assertSame($expected, $index);
        $this->assertSame(['uamswp_fad_gbp_index', 'uamswp_fad_gbp_index_at'], array_keys($set));
        $this->assertSame([$expected, 300], $set['uamswp_fad_gbp_index']);
        $this->assertSame(300, $set['uamswp_fad_gbp_index_at'][1]);
        $this->assertGreaterThanOrEqual($before, $set['uamswp_fad_gbp_index_at'][0]);
        $this->assertLessThanOrEqual(time(), $set['uamswp_fad_gbp_index_at'][0]);
    }

    public function test_a_cached_index_is_used_without_calling_google(): void
    {
        $calls = [];
        $client = $this->client(['accounts/1' => [['name' => 'locations/a']]], $calls);
        $cached = [['account' => 'accounts/9', 'listing' => ['name' => 'locations/z']]];
        expect('get_transient')->once()->with('uamswp_fad_gbp_index')->andReturn($cached);
        expect('set_transient')->never();

        $this->assertSame($cached, uamswp_fad_gbp_index($client));
        $this->assertSame([], $calls);
    }

    public function test_an_empty_cached_index_is_still_a_cached_index(): void
    {
        $calls = [];
        $client = $this->client(['accounts/1' => [['name' => 'locations/a']]], $calls);
        when('get_transient')->justReturn([]);
        expect('set_transient')->never();

        $this->assertSame([], uamswp_fad_gbp_index($client));
        $this->assertSame([], $calls);
    }

    public function test_the_categories_are_read_once_and_kept_for_a_day(): void
    {
        $calls = [];
        $client = $this->client([], $calls, [['name' => 'categories/gcid:medical_clinic', 'displayName' => 'Medical Clinic']]);
        expect('get_transient')->once()->with('uamswp_fad_gbp_categories')->andReturn(false);
        expect('set_transient')->once()->with('uamswp_fad_gbp_categories', ['Medical Clinic' => 'categories/gcid:medical_clinic'], DAY_IN_SECONDS)->andReturn(true);

        $this->assertSame(['Medical Clinic' => 'categories/gcid:medical_clinic'], uamswp_fad_gbp_categories($client));
    }

    public function test_cached_categories_are_used_without_calling_google(): void
    {
        $calls = [];
        $client = $this->client([], $calls);
        when('get_transient')->justReturn([]);
        expect('set_transient')->never();

        $this->assertSame([], uamswp_fad_gbp_categories($client));
        $this->assertSame([], $calls);
    }

    public function test_a_location_s_photos_sent_come_from_its_own_meta(): void
    {
        expect('get_post_meta')->once()->with(55, UAMSWP_FAD_GBP_PHOTOS_META, true)->andReturn(['b' => 'https://x.test/1.jpg', 'a' => 2]);

        $this->assertSame(['https://x.test/1.jpg', '2'], uamswp_fad_gbp_photos_sent_for(['post_id' => 55, 'kind' => 'location', 'stored_name' => 'locations/1']));
    }

    public function test_a_provider_s_photos_sent_come_from_the_map_entry_of_its_location(): void
    {
        expect('get_post_meta')->once()->with(7, UAMSWP_FAD_GBP_LISTING_MAP_META, true)->andReturn([
            55 => ['id' => 'locations/1', 'photos' => ['https://x.test/55.jpg']],
            56 => ['id' => 'locations/2', 'photos' => ['https://x.test/56.jpg']],
        ]);

        $this->assertSame(['https://x.test/56.jpg'], uamswp_fad_gbp_photos_sent_for(['post_id' => 7, 'kind' => 'provider', 'location_id' => '56', 'stored_name' => 'locations/2']));
    }

    public function test_photos_sent_are_empty_for_a_malformed_record(): void
    {
        when('get_post_meta')->alias(fn (int $id, string $key) => match ($id) {
            1 => 'not a list',
            2 => 'not a map',
            3 => [56 => ['id' => 'locations/2']],
            default => [56 => ['id' => 'locations/2', 'photos' => 'not a list']],
        });

        $this->assertSame([], uamswp_fad_gbp_photos_sent_for(['post_id' => 1, 'kind' => 'location', 'stored_name' => 'locations/1']));
        $this->assertSame([], uamswp_fad_gbp_photos_sent_for(['post_id' => 2, 'kind' => 'provider', 'location_id' => 56, 'stored_name' => 'locations/2']));
        $this->assertSame([], uamswp_fad_gbp_photos_sent_for(['post_id' => 3, 'kind' => 'provider', 'location_id' => 56, 'stored_name' => 'locations/2']), 'no photos key');
        $this->assertSame([], uamswp_fad_gbp_photos_sent_for(['post_id' => 4, 'kind' => 'provider', 'location_id' => 56, 'stored_name' => 'locations/2']));
        $this->assertSame([], uamswp_fad_gbp_photos_sent_for(['post_id' => 3, 'kind' => 'provider', 'location_id' => 99, 'stored_name' => 'locations/2']), 'no entry for the location');
    }

    public function test_no_photos_are_sent_without_a_post_or_a_stored_listing(): void
    {
        expect('get_post_meta')->never();

        $this->assertSame([], uamswp_fad_gbp_photos_sent_for(['post_id' => 0, 'kind' => 'location', 'stored_name' => 'locations/1']));
        $this->assertSame([], uamswp_fad_gbp_photos_sent_for(['kind' => 'location', 'stored_name' => 'locations/1']));
        $this->assertSame([], uamswp_fad_gbp_photos_sent_for(['post_id' => 55, 'kind' => 'location', 'stored_name' => '']));
        $this->assertSame([], uamswp_fad_gbp_photos_sent_for(['post_id' => 55, 'kind' => 'location']));
    }

    public function test_a_name_is_held_elsewhere_when_another_post_stores_it_either_way(): void
    {
        $this->recordQueries(fn (array $args): array => [9]);

        $this->assertTrue(uamswp_fad_gbp_name_held_elsewhere(7, 'locations/1'));
        $this->assertSame([[
            'post_type' => ['provider', 'location'],
            'post_status' => 'any',
            'exclude' => [7],
            'fields' => 'ids',
            'posts_per_page' => 1,
            'meta_query' => [
                'relation' => 'OR',
                ['key' => UAMSWP_FAD_GBP_LISTING_META, 'value' => 'locations/1'],
                ['key' => UAMSWP_FAD_GBP_LISTING_MAP_META, 'value' => '"locations/1"', 'compare' => 'LIKE'],
            ],
        ]], $this->queries);
    }

    public function test_a_name_no_other_post_stores_is_not_held_elsewhere(): void
    {
        when('get_posts')->justReturn([]);

        $this->assertFalse(uamswp_fad_gbp_name_held_elsewhere(7, 'locations/1'));
    }

    /**
     * A provider table row with the given store code, a category and a logo and cover.
     *
     * @return array<int, string>
     */
    private function providerRow(string $store_code): array
    {
        $row = array_fill(0, 21, '');
        $row[0] = $store_code;
        $row[1] = 'Dr. Example';
        $row[15] = 'Doctor';
        $row[16] = 'Unknown Category';
        $row[18] = 'https://x.test/logo.jpg';
        $row[19] = 'https://x.test/cover.jpg';
        $row[20] = 'https://x.test/other.jpg';

        return $row;
    }

    public function test_a_provider_has_one_item_per_row_with_a_well_formed_store_code(): void
    {
        require_once __DIR__.'/../Support/gmb-page.php';
        \uamswp_test_gmb_page();
        when('uamswp_fad_gmb_provider_table')->alias(fn (array $ids) => ['head' => [], 'body' => $ids === [7] ? [
            $this->providerRow('dr-example_main-clinic'),
            $this->providerRow('malformed'),
            $this->providerRow('dr-example_gone-clinic'),
        ] : []]);
        when('uamswp_fad_gbp_location_info')->alias(fn (string $slug) => $slug === 'main-clinic'
            ? ['id' => 55, 'silo' => 'baptist-health', 'providers_excluded' => true]
            : ['id' => 0, 'silo' => null, 'providers_excluded' => false]);
        $categories = ['Doctor' => 'categories/gcid:doctor'];

        $items = uamswp_fad_gbp_items_for_provider(7, $categories);

        $this->assertCount(2, $items, 'the malformed store code is not an item');
        $this->assertSame([
            'store_code' => 'dr-example_main-clinic',
            'kind' => 'provider',
            'photos' => [['category' => 'LOGO', 'url' => 'https://x.test/logo.jpg'], ['category' => 'COVER', 'url' => 'https://x.test/cover.jpg']],
            'post_id' => 7,
            'location_id' => 55,
            'silo' => 'baptist-health',
            'excluded' => true,
            'fields' => \UAMSWP_FAD_GBP_Logic::fields_from_row($this->providerRow('dr-example_main-clinic'), $categories),
            'unresolved' => ['Unknown Category'],
        ], $items[0]);
        $this->assertSame(['dr-example_gone-clinic', [], 0, null, false], [$items[1]['store_code'], $items[1]['photos'], $items[1]['location_id'], $items[1]['silo'], $items[1]['excluded']], 'a row whose location is gone sends no photos');
    }
}
