<?php

/**
 * Google's daily create quota: telling a daily refusal from a per-minute one, the Pacific reset, the
 * count and pause kept between jobs, and the cached index after a create.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use UAMSWP_FAD_GBP_Client;
use UAMSWP_FAD_GBP_Logic;
use UAMSWP_FAD_GBP_Rate_Limited;
use UAMSWP_FAD_GBP_Sync;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpQuotaTest extends UnitTestCase
{
    /** @var array<string, mixed> */
    private $options = [];

    /** @var array<string, mixed> */
    private $transients = [];

    /** @var array<int, array<int, mixed>> */
    private $scheduled = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->options = [];
        $this->transients = [];
        $this->scheduled = [];

        Functions\when('apply_filters')->alias(fn (string $hook, $value) => $value);
        Functions\when('get_option')->alias(fn (string $name, $default = false) => $this->options[$name] ?? $default);
        Functions\when('update_option')->alias(function (string $name, $value): bool {
            $this->options[$name] = $value;

            return true;
        });
        Functions\when('get_transient')->alias(fn (string $key) => $this->transients[$key] ?? false);
        Functions\when('set_transient')->alias(function (string $key, $value): bool {
            $this->transients[$key] = $value;

            return true;
        });
        Functions\when('delete_transient')->alias(function (string $key): bool {
            unset($this->transients[$key]);

            return true;
        });
        Functions\when('wp_next_scheduled')->alias(function (string $hook, array $args) {
            foreach ($this->scheduled as $call) {
                if ($call[1] === $hook && $call[2] === $args) {
                    return $call[0];
                }
            }

            return false;
        });
        Functions\when('wp_schedule_single_event')->alias(function (int $time, string $hook, array $args): bool {
            $this->scheduled[] = [$time, $hook, $args];

            return true;
        });
    }

    private function utc(string $moment): int
    {
        return (new \DateTimeImmutable($moment, new \DateTimeZone('UTC')))->getTimestamp();
    }

    /**
     * @param  array<int, array{0: int, 1: array<string, mixed>}>  $responses
     */
    private function client(array $responses): UAMSWP_FAD_GBP_Client
    {
        return new UAMSWP_FAD_GBP_Client(['client_id' => 'a', 'client_secret' => 'b', 'refresh_token' => 'c'], function (string $method, string $url) use (&$responses): array {
            if (strpos($url, 'oauth2.googleapis.com') !== false) {
                return [200, json_encode(['access_token' => 't', 'expires_in' => 3600])];
            }

            $next = array_shift($responses);

            return [$next[0], json_encode($next[1])];
        });
    }

    public function test_a_429_that_names_a_per_day_quota_is_daily(): void
    {
        $message = "Quota exceeded for quota metric 'Create Location requests' and limit 'Create Location requests per day' of service 'mybusinessbusinessinformation.googleapis.com'";

        try {
            $this->client([[429, ['error' => ['message' => $message]]]])->location_groups();
            $this->fail('Expected a rate limit');
        } catch (UAMSWP_FAD_GBP_Rate_Limited $rateLimited) {
            $this->assertTrue($rateLimited->daily);
        }
    }

    public function test_a_per_minute_429_and_a_503_are_not_daily(): void
    {
        foreach ([[429, 'Quota exceeded for limit Requests per minute per user'], [503, 'The service is unavailable, retry per day later']] as [$status, $message]) {
            try {
                $this->client([[$status, ['error' => ['message' => $message]]]])->location_groups();
                $this->fail('Expected a rate limit');
            } catch (UAMSWP_FAD_GBP_Rate_Limited $rateLimited) {
                $this->assertFalse($rateLimited->daily, (string) $status);
            }
        }
    }

    public function test_the_reset_is_the_next_midnight_in_los_angeles(): void
    {
        // 11:00 on 2026-11-03 in Los Angeles (PST, UTC-8): midnight is 08:00 UTC the next day.
        $this->assertSame($this->utc('2026-11-04 08:00:00'), UAMSWP_FAD_GBP_Logic::next_pacific_reset($this->utc('2026-11-03 19:00:00')));
        // Exactly at midnight, the reset is the next one, a day on.
        $this->assertSame($this->utc('2026-11-05 08:00:00'), UAMSWP_FAD_GBP_Logic::next_pacific_reset($this->utc('2026-11-04 08:00:00')));
    }

    public function test_the_reset_follows_the_daylight_saving_change_in_both_directions(): void
    {
        // Clocks go back on 2026-11-01: that Pacific day is 25 hours, so the reset after 12:00 UTC is 08:00 UTC on 11-02 (PST).
        $this->assertSame($this->utc('2026-11-02 08:00:00'), UAMSWP_FAD_GBP_Logic::next_pacific_reset($this->utc('2026-11-01 12:00:00')));
        // Clocks go forward on 2026-03-08: from 12:00 UTC (05:00 PDT) the next midnight is PDT, 07:00 UTC on 03-09.
        $this->assertSame($this->utc('2026-03-09 07:00:00'), UAMSWP_FAD_GBP_Logic::next_pacific_reset($this->utc('2026-03-08 12:00:00')));
    }

    public function test_the_pacific_day_changes_at_pacific_midnight_not_utc_midnight(): void
    {
        $this->assertSame('2026-11-03', UAMSWP_FAD_GBP_Logic::pacific_day($this->utc('2026-11-04 07:59:59')));
        $this->assertSame('2026-11-04', UAMSWP_FAD_GBP_Logic::pacific_day($this->utc('2026-11-04 08:00:00')));
        $this->assertSame('2026-11-03', UAMSWP_FAD_GBP_Logic::pacific_day($this->utc('2026-11-03 23:30:00')), 'late evening UTC is still the same Pacific day');
    }

    public function test_the_gate_allows_the_default_budget_on_a_new_day(): void
    {
        $now = $this->utc('2026-11-03 19:00:00');

        $this->assertSame(['creates_paused_until' => 0, 'creates_left' => UAMSWP_FAD_GBP_DEFAULT_CREATE_BUDGET, 'now' => $now], uamswp_fad_gbp_create_gate($now));
    }

    public function test_the_gate_subtracts_todays_creates_and_ignores_yesterdays(): void
    {
        $now = $this->utc('2026-11-03 19:00:00');
        $this->options[UAMSWP_FAD_GBP_COUNT_OPTION] = ['day' => '2026-11-03', 'count' => 30];

        $this->assertSame(UAMSWP_FAD_GBP_DEFAULT_CREATE_BUDGET - 30, uamswp_fad_gbp_create_gate($now)['creates_left']);

        // The next Pacific day starts from zero again.
        $this->assertSame(UAMSWP_FAD_GBP_DEFAULT_CREATE_BUDGET, uamswp_fad_gbp_create_gate($this->utc('2026-11-04 08:00:00'))['creates_left']);
    }

    public function test_the_gate_never_goes_below_zero_and_reads_the_filtered_budget(): void
    {
        $now = $this->utc('2026-11-03 19:00:00');
        $this->options[UAMSWP_FAD_GBP_COUNT_OPTION] = ['day' => '2026-11-03', 'count' => 500];
        $this->options[UAMSWP_FAD_GBP_PAUSE_OPTION] = $now + 99;

        $gate = uamswp_fad_gbp_create_gate($now);

        $this->assertSame(0, $gate['creates_left']);
        $this->assertSame($now + 99, $gate['creates_paused_until']);

        Functions\when('apply_filters')->alias(fn (string $hook, $value) => $hook === 'uamswp_fad_gbp_daily_create_budget' ? 700 : $value);

        $this->assertSame(200, uamswp_fad_gbp_create_gate($now)['creates_left']);
    }

    public function test_a_run_adds_its_creates_to_the_days_count_and_stores_a_pause(): void
    {
        $now = $this->utc('2026-11-03 19:00:00');
        $this->options[UAMSWP_FAD_GBP_COUNT_OPTION] = ['day' => '2026-11-03', 'count' => 5];
        $sync = $this->quotaSync(['now' => $now, 'creates_left' => 10], 3, true);

        uamswp_fad_gbp_after_run($sync, [], $now);

        $this->assertSame(['day' => '2026-11-03', 'count' => 8], $this->options[UAMSWP_FAD_GBP_COUNT_OPTION]);
        $this->assertSame($this->utc('2026-11-04 08:00:00'), $this->options[UAMSWP_FAD_GBP_PAUSE_OPTION]);
    }

    public function test_a_run_that_created_nothing_leaves_the_count_and_pause_alone(): void
    {
        $now = $this->utc('2026-11-03 19:00:00');
        $sync = $this->quotaSync(['now' => $now], 0, false);

        uamswp_fad_gbp_after_run($sync, [], $now);

        $this->assertArrayNotHasKey(UAMSWP_FAD_GBP_COUNT_OPTION, $this->options);
        $this->assertArrayNotHasKey(UAMSWP_FAD_GBP_PAUSE_OPTION, $this->options);
    }

    public function test_a_created_listing_is_added_to_the_cached_index_without_dropping_it(): void
    {
        $this->transients['uamswp_fad_gbp_index'] = [['account' => 'accounts/up', 'listing' => ['name' => 'locations/1', 'storeCode' => 'old_clinic']]];
        $sync = $this->quotaSync(['now' => 1], 1, false);

        uamswp_fad_gbp_after_run($sync, [['reason' => 'Created']], 1);

        $this->assertSame(['old_clinic', 'new_clinic_0'], array_map(fn (array $entry): string => $entry['listing']['storeCode'], $this->transients['uamswp_fad_gbp_index']));
    }

    public function test_an_update_drops_the_cached_index(): void
    {
        $this->transients['uamswp_fad_gbp_index'] = [['account' => 'accounts/up', 'listing' => ['name' => 'locations/1', 'storeCode' => 'old_clinic']]];
        $sync = $this->quotaSync(['now' => 1], 1, false);

        uamswp_fad_gbp_after_run($sync, [['reason' => 'Created'], ['reason' => 'Updated']], 1);

        $this->assertArrayNotHasKey('uamswp_fad_gbp_index', $this->transients);
    }

    public function test_a_deferred_post_is_queued_once_after_the_reset_without_counting_an_attempt(): void
    {
        $reset = $this->utc('2026-11-04 08:00:00');

        uamswp_fad_gbp_schedule_deferred(1234, [['defer_until' => $reset], ['defer_until' => $reset], ['reason' => 'Updated']]);
        uamswp_fad_gbp_schedule_deferred(1234, [['defer_until' => $reset]]);

        $this->assertCount(1, $this->scheduled, 'a post already queued is not queued again');
        $this->assertSame($reset + 60 + (1234 % 600), $this->scheduled[0][0]);
        $this->assertSame([1234], $this->scheduled[0][2]);
        $this->assertArrayNotHasKey('uamswp_fad_gbp_attempts_1234', $this->transients, 'no attempt is recorded');
    }

    public function test_a_post_with_nothing_deferred_is_not_queued(): void
    {
        uamswp_fad_gbp_schedule_deferred(1234, [['reason' => 'Created']]);

        $this->assertSame([], $this->scheduled);
    }

    /**
     * A sync that has run $creates creates and, when $limited, one more that Google refused for the day.
     *
     * @param  array<string, int|null>  $gate
     */
    private function quotaSync(array $gate, int $creates, bool $limited): UAMSWP_FAD_GBP_Sync
    {
        $client = new class
        {
            public bool $limited = false;

            public int $made = 0;

            public function create(string $account, array $fields, string $store_code, bool $validate_only = false): array
            {
                if ($this->limited && $store_code === 'refused_clinic') {
                    throw new UAMSWP_FAD_GBP_Rate_Limited('Google API POST failed (429): per day', true);
                }

                $this->made++;

                return ['name' => 'locations/'.$store_code];
            }
        };

        $silos = ['uams' => ['brand' => null, 'accounts' => ['provider' => 'accounts/up', 'location' => 'accounts/ul']]];
        $sync = new UAMSWP_FAD_GBP_Sync($client, ['silos' => $silos, 'modes' => ['uams' => 'live'], 'is_production' => true] + $gate, [], function (array $entry): void {});
        $fields = ['title' => 'T', 'storefrontAddress' => ['postalCode' => '1'], 'categories' => ['primaryCategory' => ['name' => 'c']]];
        $items = [];

        for ($i = 0; $i < $creates; $i++) {
            $items[] = ['store_code' => 'new_clinic_'.$i, 'kind' => 'provider', 'silo' => 'uams', 'fields' => $fields, 'unresolved' => []];
        }

        if ($limited) {
            $client->limited = true;
            $items[] = ['store_code' => 'refused_clinic', 'kind' => 'provider', 'silo' => 'uams', 'fields' => $fields, 'unresolved' => []];
        }

        $sync->run($items);

        return $sync;
    }
}
