<?php

/**
 * Unit tests for the Business Profile sync's WordPress wiring: configuration and the save queue.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Actions\expectAdded;
use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

final class GbpRuntimeTest extends UnitTestCase
{
    /** @var array<int, array<int, mixed>> */
    private array $scheduled = [];

    /** @var array<string, array<int|string, array<string, mixed>>> */
    private array $options = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->scheduled = [];
    }

    /**
     * @param  array<string, string>  $modes
     */
    private function enable(array $modes = []): void
    {
        when('get_option')->alias(fn (string $name, $default = false) => $name === 'uamswp_fad_gbp_modes' ? $modes : $default);
        when('apply_filters')->alias(fn (string $hook, $value) => $value);
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
        when('wp_schedule_single_event')->alias(function (int $time, string $hook, array $args) {
            $this->scheduled[] = [$time, $hook, $args];

            return true;
        });
    }

    private function creds(): callable
    {
        return fn (string $name): string => 'value-of-'.$name;
    }

    public function test_credentials_come_from_the_lookup_and_are_all_required(): void
    {
        $credentials = uamswp_fad_gbp_credentials($this->creds());

        $this->assertSame([
            'client_id' => 'value-of-GOOGLE_BUSINESS_PROFILE_CLIENT_ID',
            'client_secret' => 'value-of-GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET',
            'refresh_token' => 'value-of-GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN',
        ], $credentials);

        $this->assertNull(uamswp_fad_gbp_credentials(fn (string $name): string => $name === 'GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN' ? '' : 'x'));
        $this->assertNull(uamswp_fad_gbp_credentials(fn (): string => ''));
    }

    public function test_every_silo_defaults_to_dry_run(): void
    {
        $this->enable();

        $modes = uamswp_fad_gbp_modes();

        $this->assertSame(['uams' => 'dry-run', 'arkansas-childrens' => 'dry-run', 'baptist-health' => 'dry-run'], $modes);
    }

    public function test_saved_modes_are_kept_and_junk_is_ignored(): void
    {
        $this->enable(['uams' => 'live', 'baptist-health' => 'off', 'arkansas-childrens' => 'bogus', 'unknown-silo' => 'live']);

        $this->assertSame(['uams' => 'live', 'arkansas-childrens' => 'dry-run', 'baptist-health' => 'off'], uamswp_fad_gbp_modes());
    }

    public function test_production_is_decided_by_the_host(): void
    {
        $this->enable();
        when('wp_parse_url')->alias('parse_url');

        when('home_url')->justReturn('https://uamshealth.com');
        $this->assertTrue(uamswp_fad_gbp_is_production());

        when('home_url')->justReturn('https://uamshealth.dev');
        $this->assertFalse(uamswp_fad_gbp_is_production());

        when('home_url')->justReturn('https://uamshealth.com.evil.test');
        $this->assertFalse(uamswp_fad_gbp_is_production());
    }

    /**
     * @return array<int, array{0: array<string, mixed>, 1: array{silo: ?string, providers_excluded: bool}}>
     */
    public static function location_info_cases(): array
    {
        return [
            'default brand, providers not excluded' => [['query' => '0', 'org' => '', 'exclude_providers' => '0'], ['id' => 321, 'silo' => 'uams', 'providers_excluded' => false]],
            'default brand, providers excluded' => [['query' => '0', 'org' => '', 'exclude_providers' => '1'], ['id' => 321, 'silo' => 'uams', 'providers_excluded' => true]],
            'partner brand, providers excluded' => [['query' => '1', 'org' => 'baptist-health', 'exclude_providers' => '1'], ['id' => 321, 'silo' => 'baptist-health', 'providers_excluded' => true]],
            'a brand with no silo still reports the flag' => [['query' => '1', 'org' => 'dhs', 'exclude_providers' => '1'], ['id' => 321, 'silo' => null, 'providers_excluded' => true]],
            'a never-saved field is not an exclusion' => [['query' => '', 'org' => '', 'exclude_providers' => ''], ['id' => 321, 'silo' => 'uams', 'providers_excluded' => false]],
        ];
    }

    /**
     * @param  array<string, string>  $stored
     * @param  array{id: int, silo: ?string, providers_excluded: bool}  $expected
     */
    #[DataProvider('location_info_cases')]
    public function test_location_info_reads_the_silo_and_the_provider_exclusion(array $stored, array $expected): void
    {
        $this->enable();
        when('get_posts')->justReturn([321]);
        when('get_post_meta')->alias(fn (int $id, string $key) => $key === 'schema_brandorg_query' ? $stored['query'] : $stored['org']);
        when('get_field')->alias(fn (string $name, int $id) => $name === 'location_gmb_exclude_providers' ? $stored['exclude_providers'] : null);

        $this->assertSame($expected, uamswp_fad_gbp_location_info('some-clinic'));
    }

    public function test_an_unknown_location_has_no_silo_and_is_not_excluded(): void
    {
        $this->enable();
        when('get_posts')->justReturn([]);

        $this->assertSame(['silo' => null, 'providers_excluded' => false, 'id' => 0], uamswp_fad_gbp_location_info('gone'));
    }

    public function test_saving_a_published_provider_schedules_one_debounced_sync(): void
    {
        $this->enable();
        $this->recordSchedule();
        when('get_post_type')->justReturn('provider');
        when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_save(100, $this->creds());
        uamswp_fad_gbp_queue_on_save(100, $this->creds());

        $this->assertCount(1, $this->scheduled);
        $this->assertSame(['uamswp_fad_gbp_sync_post', [100]], [$this->scheduled[0][1], $this->scheduled[0][2]]);
        $this->assertGreaterThan(time(), $this->scheduled[0][0]);
    }

    public function test_queue_all_schedules_every_published_provider_and_location_once_and_staggered(): void
    {
        $this->enable();
        $this->recordSchedule();
        when('get_posts')->alias(fn (array $args) => $args['post_type'] === 'provider' ? ['7', 8, 9] : [55]);

        expect('delete_post_meta')->times(4)->with(\Mockery::anyOf(7, 8, 9, 55), UAMSWP_FAD_GBP_HASH_META);

        $first = uamswp_fad_gbp_queue_all($this->creds());
        $second = uamswp_fad_gbp_queue_all($this->creds());

        $this->assertSame([4, 0], [$first, $second]);
        $this->assertSame([[7], [8], [9], [55]], array_column($this->scheduled, 2));
        $this->assertSame(15, $this->scheduled[1][0] - $this->scheduled[0][0]);
        $this->assertSame(15, $this->scheduled[2][0] - $this->scheduled[1][0]);
        $this->assertSame(15, $this->scheduled[3][0] - $this->scheduled[2][0]);
    }

    public function test_queue_all_does_nothing_without_credentials_or_when_every_silo_is_off(): void
    {
        $this->recordSchedule();
        when('get_posts')->justReturn([7]);

        $this->enable();
        $this->assertSame(0, uamswp_fad_gbp_queue_all(fn (): string => ''));

        $this->enable(['uams' => 'off', 'arkansas-childrens' => 'off', 'baptist-health' => 'off']);
        $this->assertSame(0, uamswp_fad_gbp_queue_all($this->creds()));

        $this->assertSame([], $this->scheduled);
    }

    public function test_a_scheduled_post_going_live_is_queued(): void
    {
        $this->enable();
        $this->recordSchedule();
        when('get_post_type')->justReturn('provider');
        when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_publish('publish', 'future', (object) ['ID' => 100], $this->creds());

        $this->assertSame([[100]], array_column($this->scheduled, 2));
    }

    public function test_only_a_change_into_publish_is_queued_by_the_transition(): void
    {
        $this->enable();
        $this->recordSchedule();
        when('get_post_type')->justReturn('provider');
        when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_publish('publish', 'publish', (object) ['ID' => 100], $this->creds());
        uamswp_fad_gbp_queue_on_publish('draft', 'publish', (object) ['ID' => 101], $this->creds());
        uamswp_fad_gbp_queue_on_publish('future', 'draft', (object) ['ID' => 102], $this->creds());

        $this->assertSame([], $this->scheduled);
    }

    public function test_the_save_and_the_transition_together_queue_a_provider_once(): void
    {
        $this->enable();
        $this->recordSchedule();
        when('get_post_type')->justReturn('provider');
        when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_publish('publish', 'draft', (object) ['ID' => 100], $this->creds());
        uamswp_fad_gbp_queue_on_save(100, $this->creds());

        $this->assertCount(1, $this->scheduled);
    }

    public function test_the_hooks_are_registered(): void
    {
        expectAdded('acf/save_post')->once()->with('uamswp_fad_gbp_queue_on_save', 30);
        expectAdded('transition_post_status')->once()->with('uamswp_fad_gbp_queue_on_publish', 10, 3);
        expectAdded(UAMSWP_FAD_GBP_SYNC_HOOK)->once()->with('uamswp_fad_gbp_run_post');

        uamswp_fad_gbp_register_hooks();
    }

    public function test_items_for_a_location_come_from_its_own_row_and_silo(): void
    {
        $this->enable();
        $row = array_fill(0, 18, '');
        $row[0] = 'parent_child';
        $row[1] = 'Child Clinic';
        $row[15] = 'Medical Clinic';
        // Load the real builder first, so the stub below is a redefinition the test undoes rather than a function that outlives it.
        require_once __DIR__.'/../Support/gmb-page.php';
        \uamswp_test_gmb_page();
        when('uamswp_fad_gmb_location_table')->alias(fn (array $ids) => ['head' => [], 'body' => $ids === [55] ? [$row, []] : []]);
        when('get_post_meta')->alias(fn (int $id, string $key) => $key === 'schema_brandorg_query' ? '1' : 'baptist-health');

        $items = uamswp_fad_gbp_items_for_location(55, ['Medical Clinic' => 'categories/gcid:medical_clinic']);

        $this->assertCount(1, $items, 'an empty row is not an item');
        $this->assertSame(['parent_child', 'location', 'baptist-health'], [$items[0]['store_code'], $items[0]['kind'], $items[0]['silo']]);
    }

    public function test_items_for_a_post_depend_on_its_type(): void
    {
        when('uamswp_fad_gbp_items_for_location')->justReturn([['kind' => 'location']]);
        when('uamswp_fad_gbp_items_for_provider')->justReturn([['kind' => 'provider']]);

        when('get_post_type')->justReturn('location');
        $this->assertSame('location', uamswp_fad_gbp_items_for_post(1, [])[0]['kind']);

        when('get_post_type')->justReturn('provider');
        $this->assertSame('provider', uamswp_fad_gbp_items_for_post(1, [])[0]['kind']);
    }

    public function test_the_retry_delay_doubles_from_a_minute_to_an_hour(): void
    {
        $this->assertSame([60, 120, 240, 480, 960, 1920, 3600, 3600], array_map(uamswp_fad_gbp_retry_delay(...), [1, 2, 3, 4, 5, 6, 7, 20]));
    }

    public function test_a_rate_limited_provider_is_retried_later_with_a_growing_delay(): void
    {
        $attempts = [];
        when('get_transient')->alias(function (string $key) use (&$attempts) {
            return $attempts[$key] ?? false;
        });
        when('set_transient')->alias(function (string $key, $value) use (&$attempts): bool {
            $attempts[$key] = $value;

            return true;
        });
        $stored = [];
        when('get_option')->alias(function (string $name, $default = false) use (&$stored) {
            return $stored[$name] ?? $default;
        });
        when('update_option')->alias(function (string $name, $value) use (&$stored): bool {
            $stored[$name] = $value;

            return true;
        });
        $this->recordSchedule();
        $now = time();

        uamswp_fad_gbp_schedule_retry(7);
        $this->assertSame([[7]], array_column($this->scheduled, 2));
        $this->assertEqualsWithDelta($now + 60, $this->scheduled[0][0], 5);
        $this->assertSame($this->scheduled[0][0], $stored[UAMSWP_FAD_GBP_BACKOFF_OPTION], 'the dashboard shows when the backoff ends');

        $this->scheduled = [];
        uamswp_fad_gbp_schedule_retry(7);
        $this->assertEqualsWithDelta($now + 120, $this->scheduled[0][0], 5);
        $this->assertSame($this->scheduled[0][0], $stored[UAMSWP_FAD_GBP_BACKOFF_OPTION]);

        $stored[UAMSWP_FAD_GBP_BACKOFF_OPTION] = $now + 99999;
        $this->scheduled = [];
        uamswp_fad_gbp_schedule_retry(7);
        $this->assertSame($now + 99999, $stored[UAMSWP_FAD_GBP_BACKOFF_OPTION], 'an earlier backoff does not shorten a later one');
    }

    public function test_a_provider_is_given_up_on_after_the_attempt_limit(): void
    {
        $logged = [];
        when('get_transient')->justReturn(UAMSWP_FAD_GBP_MAX_ATTEMPTS);
        when('get_post_type')->justReturn('provider');
        when('delete_transient')->justReturn(true);
        when('get_option')->justReturn([]);
        when('update_option')->alias(function (string $name, $value) use (&$logged): bool {
            $logged = $value;

            return true;
        });
        $this->recordSchedule();

        uamswp_fad_gbp_schedule_retry(7);

        $this->assertSame([], $this->scheduled);
        $this->assertStringContainsString('Gave up on post 7', $logged[0]['reason']);
    }

    public function test_nothing_is_queued_without_credentials(): void
    {
        $this->enable();
        $this->recordSchedule();
        when('get_post_type')->justReturn('provider');
        when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_save(100, fn (): string => '');

        $this->assertSame([], $this->scheduled);
    }

    public function test_nothing_is_queued_when_every_silo_is_off(): void
    {
        $this->enable(['uams' => 'off', 'arkansas-childrens' => 'off', 'baptist-health' => 'off']);
        $this->recordSchedule();
        when('get_post_type')->justReturn('provider');
        when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_save(100, $this->creds());

        $this->assertSame([], $this->scheduled);
    }

    public function test_an_unpublished_provider_and_other_post_types_are_ignored(): void
    {
        $this->enable();
        $this->recordSchedule();
        when('get_post_type')->justReturn('provider');
        when('get_post_status')->justReturn('draft');
        uamswp_fad_gbp_queue_on_save(100, $this->creds());

        when('get_post_type')->justReturn('page');
        when('get_post_status')->justReturn('publish');
        uamswp_fad_gbp_queue_on_save(101, $this->creds());

        uamswp_fad_gbp_queue_on_save('options', $this->creds());

        $this->assertSame([], $this->scheduled);
    }

    public function test_saving_a_location_schedules_it_its_children_and_the_providers_at_them(): void
    {
        $this->enable();
        $this->recordSchedule();
        when('get_post_type')->justReturn('location');
        when('get_post_status')->justReturn('publish');
        when('uamswp_fad_gbp_provider_ids_for_locations')->alias(fn (array $location_ids) => $location_ids === [55, 56] ? [7, 8] : []);
        when('uamswp_fad_gbp_child_location_ids')->justReturn([56]);

        uamswp_fad_gbp_queue_on_save(55, $this->creds());

        $this->assertSame([[55], [56], [7], [8]], array_column($this->scheduled, 2));
        $this->assertLessThan($this->scheduled[1][0], $this->scheduled[0][0], 'syncs are staggered, not simultaneous');
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    private function storeOptions(): array
    {
        $store = ['uamswp_fad_gbp_log' => [], 'uamswp_fad_gbp_attention' => []];
        $this->options = &$store;
        when('get_option')->alias(function (string $name, $default = false) use (&$store) {
            return $store[$name] ?? $default;
        });
        when('update_option')->alias(function (string $name, $value) use (&$store): bool {
            $store[$name] = $value;

            return true;
        });

        return $store;
    }

    public function test_review_and_error_entries_survive_more_routine_entries_than_the_log_keeps(): void
    {
        $this->storeOptions();

        uamswp_fad_gbp_log(['store_code' => 'needs_review', 'silo' => 'uams', 'action' => 'review', 'reason' => 'Store code is on 2 listings']);
        uamswp_fad_gbp_log(['store_code' => 'broken', 'silo' => 'uams', 'action' => 'error', 'reason' => 'HTTP 500']);

        for ($i = 0; $i < UAMSWP_FAD_GBP_LOG_CAP + 50; $i++) {
            uamswp_fad_gbp_log(['store_code' => 'routine_'.$i, 'silo' => 'uams', 'action' => 'update', 'reason' => 'Updated']);
        }

        $this->assertCount(UAMSWP_FAD_GBP_LOG_CAP, $this->options['uamswp_fad_gbp_log']);
        $this->assertNotContains('needs_review', array_column($this->options['uamswp_fad_gbp_log'], 'store_code'));
        $this->assertSame(['broken', 'needs_review'], array_keys($this->options['uamswp_fad_gbp_attention']));
    }

    public function test_a_later_settled_run_clears_the_entry_even_when_it_is_a_noop_that_is_not_logged(): void
    {
        $this->storeOptions();

        uamswp_fad_gbp_log(['store_code' => 'a', 'silo' => 'uams', 'action' => 'error', 'reason' => 'HTTP 500']);
        uamswp_fad_gbp_log(['store_code' => 'a', 'silo' => 'uams', 'action' => 'noop', 'reason' => 'Listing already matches']);

        $this->assertSame([], $this->options['uamswp_fad_gbp_attention']);
        $this->assertSame(['error'], array_column($this->options['uamswp_fad_gbp_log'], 'action'));
    }
}
