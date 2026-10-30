<?php

/**
 * Unit tests for the Business Profile sync's WordPress wiring: configuration and the save queue.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpRuntimeTest extends UnitTestCase
{
    /** @var array<int, array<int, mixed>> */
    private $scheduled = [];

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
        Functions\when('get_option')->alias(function (string $name, $default = false) use ($modes) {
            return $name === 'uamswp_fad_gbp_modes' ? $modes : $default;
        });
        Functions\when('apply_filters')->alias(function (string $hook, $value) {
            return $value;
        });
    }

    private function recordSchedule(): void
    {
        Functions\when('wp_next_scheduled')->alias(function (string $hook, array $args) {
            foreach ($this->scheduled as $call) {
                if ($call[1] === $hook && $call[2] === $args) {
                    return $call[0];
                }
            }

            return false;
        });
        Functions\when('wp_schedule_single_event')->alias(function (int $time, string $hook, array $args) {
            $this->scheduled[] = [$time, $hook, $args];

            return true;
        });
    }

    private function creds(): callable
    {
        return function (string $name): string {
            return 'value-of-'.$name;
        };
    }

    public function test_credentials_come_from_the_lookup_and_are_all_required(): void
    {
        $credentials = uamswp_fad_gbp_credentials($this->creds());

        $this->assertSame([
            'client_id' => 'value-of-GOOGLE_BUSINESS_PROFILE_CLIENT_ID',
            'client_secret' => 'value-of-GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET',
            'refresh_token' => 'value-of-GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN',
        ], $credentials);

        $this->assertNull(uamswp_fad_gbp_credentials(function (string $name): string {
            return $name === 'GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN' ? '' : 'x';
        }));
        $this->assertNull(uamswp_fad_gbp_credentials(function (): string {
            return '';
        }));
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
        Functions\when('wp_parse_url')->alias('parse_url');

        Functions\when('home_url')->justReturn('https://uamshealth.com');
        $this->assertTrue(uamswp_fad_gbp_is_production());

        Functions\when('home_url')->justReturn('https://uamshealth.dev');
        $this->assertFalse(uamswp_fad_gbp_is_production());

        Functions\when('home_url')->justReturn('https://uamshealth.com.evil.test');
        $this->assertFalse(uamswp_fad_gbp_is_production());
    }

    /**
     * @return array<int, array{0: array<string, mixed>, 1: array{silo: ?string, providers_excluded: bool}}>
     */
    public function location_info_cases(): array
    {
        return [
            'default brand, providers not excluded' => [['query' => '0', 'org' => '', 'exclude_providers' => '0'], ['silo' => 'uams', 'providers_excluded' => false]],
            'default brand, providers excluded' => [['query' => '0', 'org' => '', 'exclude_providers' => '1'], ['silo' => 'uams', 'providers_excluded' => true]],
            'partner brand, providers excluded' => [['query' => '1', 'org' => 'baptist-health', 'exclude_providers' => '1'], ['silo' => 'baptist-health', 'providers_excluded' => true]],
            'a brand with no silo still reports the flag' => [['query' => '1', 'org' => 'dhs', 'exclude_providers' => '1'], ['silo' => null, 'providers_excluded' => true]],
            'a never-saved field is not an exclusion' => [['query' => '', 'org' => '', 'exclude_providers' => ''], ['silo' => 'uams', 'providers_excluded' => false]],
        ];
    }

    /**
     * @dataProvider location_info_cases
     *
     * @param  array<string, string>  $stored
     * @param  array{silo: ?string, providers_excluded: bool}  $expected
     */
    public function test_location_info_reads_the_silo_and_the_provider_exclusion(array $stored, array $expected): void
    {
        $this->enable();
        Functions\when('get_posts')->justReturn([321]);
        Functions\when('get_post_meta')->alias(function (int $id, string $key) use ($stored) {
            return $key === 'schema_brandorg_query' ? $stored['query'] : $stored['org'];
        });
        Functions\when('get_field')->alias(function (string $name, int $id) use ($stored) {
            return $name === 'location_gmb_exclude_providers' ? $stored['exclude_providers'] : null;
        });

        $this->assertSame($expected, uamswp_fad_gbp_location_info('some-clinic'));
    }

    public function test_an_unknown_location_has_no_silo_and_is_not_excluded(): void
    {
        $this->enable();
        Functions\when('get_posts')->justReturn([]);

        $this->assertSame(['silo' => null, 'providers_excluded' => false], uamswp_fad_gbp_location_info('gone'));
    }

    public function test_saving_a_published_provider_schedules_one_debounced_sync(): void
    {
        $this->enable();
        $this->recordSchedule();
        Functions\when('get_post_type')->justReturn('provider');
        Functions\when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_save(100, $this->creds());
        uamswp_fad_gbp_queue_on_save(100, $this->creds());

        $this->assertCount(1, $this->scheduled);
        $this->assertSame(['uamswp_fad_gbp_sync_post', [100]], [$this->scheduled[0][1], $this->scheduled[0][2]]);
        $this->assertGreaterThan(time(), $this->scheduled[0][0]);
    }

    public function test_queue_all_schedules_every_published_provider_once_and_staggered(): void
    {
        $this->enable();
        $this->recordSchedule();
        Functions\when('get_posts')->justReturn(['7', 8, 9]);

        Functions\expect('delete_post_meta')->times(3)->with(\Mockery::anyOf(7, 8, 9), UAMSWP_FAD_GBP_HASH_META);

        $first = uamswp_fad_gbp_queue_all($this->creds());
        $second = uamswp_fad_gbp_queue_all($this->creds());

        $this->assertSame([3, 0], [$first, $second]);
        $this->assertSame([[7], [8], [9]], array_column($this->scheduled, 2));
        $this->assertSame(15, $this->scheduled[1][0] - $this->scheduled[0][0]);
        $this->assertSame(15, $this->scheduled[2][0] - $this->scheduled[1][0]);
    }

    public function test_queue_all_does_nothing_without_credentials_or_when_every_silo_is_off(): void
    {
        $this->recordSchedule();
        Functions\when('get_posts')->justReturn([7]);

        $this->enable();
        $this->assertSame(0, uamswp_fad_gbp_queue_all(function (): string {
            return '';
        }));

        $this->enable(['uams' => 'off', 'arkansas-childrens' => 'off', 'baptist-health' => 'off']);
        $this->assertSame(0, uamswp_fad_gbp_queue_all($this->creds()));

        $this->assertSame([], $this->scheduled);
    }

    public function test_a_scheduled_post_going_live_is_queued(): void
    {
        $this->enable();
        $this->recordSchedule();
        Functions\when('get_post_type')->justReturn('provider');
        Functions\when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_publish('publish', 'future', (object) ['ID' => 100], $this->creds());

        $this->assertSame([[100]], array_column($this->scheduled, 2));
    }

    public function test_only_a_change_into_publish_is_queued_by_the_transition(): void
    {
        $this->enable();
        $this->recordSchedule();
        Functions\when('get_post_type')->justReturn('provider');
        Functions\when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_publish('publish', 'publish', (object) ['ID' => 100], $this->creds());
        uamswp_fad_gbp_queue_on_publish('draft', 'publish', (object) ['ID' => 101], $this->creds());
        uamswp_fad_gbp_queue_on_publish('future', 'draft', (object) ['ID' => 102], $this->creds());

        $this->assertSame([], $this->scheduled);
    }

    public function test_the_save_and_the_transition_together_queue_a_provider_once(): void
    {
        $this->enable();
        $this->recordSchedule();
        Functions\when('get_post_type')->justReturn('provider');
        Functions\when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_publish('publish', 'draft', (object) ['ID' => 100], $this->creds());
        uamswp_fad_gbp_queue_on_save(100, $this->creds());

        $this->assertCount(1, $this->scheduled);
    }

    public function test_the_hooks_are_registered(): void
    {
        Actions\expectAdded('acf/save_post')->once()->with('uamswp_fad_gbp_queue_on_save', 30);
        Actions\expectAdded('transition_post_status')->once()->with('uamswp_fad_gbp_queue_on_publish', 10, 3);
        Actions\expectAdded(UAMSWP_FAD_GBP_SYNC_HOOK)->once()->with('uamswp_fad_gbp_run_post');

        uamswp_fad_gbp_register_hooks();
    }

    public function test_the_retry_delay_doubles_from_a_minute_to_an_hour(): void
    {
        $this->assertSame([60, 120, 240, 480, 960, 1920, 3600, 3600], array_map('uamswp_fad_gbp_retry_delay', [1, 2, 3, 4, 5, 6, 7, 20]));
    }

    public function test_a_rate_limited_provider_is_retried_later_with_a_growing_delay(): void
    {
        $attempts = [];
        Functions\when('get_transient')->alias(function (string $key) use (&$attempts) {
            return $attempts[$key] ?? false;
        });
        Functions\when('set_transient')->alias(function (string $key, $value) use (&$attempts): bool {
            $attempts[$key] = $value;

            return true;
        });
        $this->recordSchedule();
        $now = time();

        uamswp_fad_gbp_schedule_retry(7);
        $this->assertSame([[7]], array_column($this->scheduled, 2));
        $this->assertEqualsWithDelta($now + 60, $this->scheduled[0][0], 5);

        $this->scheduled = [];
        uamswp_fad_gbp_schedule_retry(7);
        $this->assertEqualsWithDelta($now + 120, $this->scheduled[0][0], 5);
    }

    public function test_a_provider_is_given_up_on_after_the_attempt_limit(): void
    {
        $logged = [];
        Functions\when('get_transient')->justReturn(UAMSWP_FAD_GBP_MAX_ATTEMPTS);
        Functions\when('delete_transient')->justReturn(true);
        Functions\when('get_option')->justReturn([]);
        Functions\when('update_option')->alias(function (string $name, $value) use (&$logged): bool {
            $logged = $value;

            return true;
        });
        $this->recordSchedule();

        uamswp_fad_gbp_schedule_retry(7);

        $this->assertSame([], $this->scheduled);
        $this->assertStringContainsString('Gave up on provider 7', $logged[0]['reason']);
    }

    public function test_nothing_is_queued_without_credentials(): void
    {
        $this->enable();
        $this->recordSchedule();
        Functions\when('get_post_type')->justReturn('provider');
        Functions\when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_save(100, function (): string {
            return '';
        });

        $this->assertSame([], $this->scheduled);
    }

    public function test_nothing_is_queued_when_every_silo_is_off(): void
    {
        $this->enable(['uams' => 'off', 'arkansas-childrens' => 'off', 'baptist-health' => 'off']);
        $this->recordSchedule();
        Functions\when('get_post_type')->justReturn('provider');
        Functions\when('get_post_status')->justReturn('publish');

        uamswp_fad_gbp_queue_on_save(100, $this->creds());

        $this->assertSame([], $this->scheduled);
    }

    public function test_an_unpublished_provider_and_other_post_types_are_ignored(): void
    {
        $this->enable();
        $this->recordSchedule();
        Functions\when('get_post_type')->justReturn('provider');
        Functions\when('get_post_status')->justReturn('draft');
        uamswp_fad_gbp_queue_on_save(100, $this->creds());

        Functions\when('get_post_type')->justReturn('page');
        Functions\when('get_post_status')->justReturn('publish');
        uamswp_fad_gbp_queue_on_save(101, $this->creds());

        uamswp_fad_gbp_queue_on_save('options', $this->creds());

        $this->assertSame([], $this->scheduled);
    }

    public function test_saving_a_location_schedules_the_providers_at_it_and_at_its_children(): void
    {
        $this->enable();
        $this->recordSchedule();
        Functions\when('get_post_type')->justReturn('location');
        Functions\when('get_post_status')->justReturn('publish');
        Functions\when('uamswp_fad_gbp_provider_ids_for_locations')->alias(function (array $location_ids) {
            return $location_ids === [55, 56] ? [7, 8] : [];
        });
        Functions\when('uamswp_fad_gbp_child_location_ids')->justReturn([56]);

        uamswp_fad_gbp_queue_on_save(55, $this->creds());

        $this->assertSame([[7], [8]], array_column($this->scheduled, 2));
        $this->assertLessThan($this->scheduled[1][0], $this->scheduled[0][0], 'syncs are staggered, not simultaneous');
    }
}
