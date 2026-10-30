<?php

/**
 * Unit tests for the Business Profile sync's WordPress wiring: configuration and the save queue.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

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
