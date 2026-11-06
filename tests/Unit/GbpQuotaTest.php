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

    /** @var array<int, int> */
    private $transientTtls = [];

    /** @var array<int, array<int, mixed>> */
    private $postMeta = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->options = [];
        $this->transients = [];
        $this->scheduled = [];
        $this->transientTtls = [];
        $this->postMeta = [];

        Functions\when('apply_filters')->alias(fn (string $hook, $value) => $value);
        Functions\when('get_option')->alias(fn (string $name, $default = false) => $this->options[$name] ?? $default);
        Functions\when('update_option')->alias(function (string $name, $value): bool {
            $this->options[$name] = $value;

            return true;
        });
        Functions\when('get_transient')->alias(fn (string $key) => $this->transients[$key] ?? false);
        Functions\when('set_transient')->alias(function (string $key, $value, int $ttl = 0): bool {
            $this->transients[$key] = $value;

            if ($key === 'uamswp_fad_gbp_index') {
                $this->transientTtls[] = $ttl;
            }

            return true;
        });
        Functions\when('update_post_meta')->alias(function (int $post_id, string $key, $value): bool {
            $this->postMeta[$post_id][] = $value;

            return true;
        });
        Functions\when('get_post_type')->justReturn('provider');
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

    public function test_a_created_listing_is_added_to_the_cached_index_keeping_its_original_expiry(): void
    {
        $this->transients['uamswp_fad_gbp_index'] = [['account' => 'accounts/up', 'listing' => ['name' => 'locations/1', 'storeCode' => 'old_clinic']]];
        $this->transients['uamswp_fad_gbp_index_at'] = 1000;
        $sync = $this->quotaSync(['now' => 1100], 1, false);

        uamswp_fad_gbp_after_run($sync, [['reason' => 'Created']], 1100);

        $this->assertSame(['old_clinic', 'new_clinic_0'], array_map(fn (array $entry): string => $entry['listing']['storeCode'], $this->transients['uamswp_fad_gbp_index']));
        $this->assertSame([200], $this->transientTtls, 'the index was read 100 seconds ago, so 200 of its 300 remain');
    }

    public function test_an_index_past_its_expiry_is_dropped_instead_of_extended(): void
    {
        $this->transients['uamswp_fad_gbp_index'] = [['account' => 'accounts/up', 'listing' => ['name' => 'locations/1', 'storeCode' => 'old_clinic']]];
        $this->transients['uamswp_fad_gbp_index_at'] = 1000;
        $sync = $this->quotaSync(['now' => 1400], 1, false);

        uamswp_fad_gbp_after_run($sync, [['reason' => 'Created']], 1400);

        $this->assertArrayNotHasKey('uamswp_fad_gbp_index', $this->transients);
        $this->assertSame([], $this->transientTtls);
    }

    public function test_a_missing_cached_index_is_left_missing(): void
    {
        $sync = $this->quotaSync(['now' => 1100], 1, false);

        uamswp_fad_gbp_after_run($sync, [['reason' => 'Created']], 1100);

        $this->assertArrayNotHasKey('uamswp_fad_gbp_index', $this->transients, 'nothing to patch, so nothing is written');
        $this->assertSame([], $this->transientTtls);
    }

    public function test_a_stored_pause_later_than_the_runs_is_not_lowered(): void
    {
        $now = $this->utc('2026-11-03 19:00:00');
        $this->options[UAMSWP_FAD_GBP_PAUSE_OPTION] = $now + 999999;
        $sync = $this->quotaSync(['now' => $now, 'creates_left' => 10], 1, true);

        uamswp_fad_gbp_after_run($sync, [], $now);

        $this->assertSame($now + 999999, $this->options[UAMSWP_FAD_GBP_PAUSE_OPTION]);
    }

    public function test_a_budget_filter_returning_junk_falls_back_to_the_default(): void
    {
        foreach (['', null, 'abc', []] as $junk) {
            Functions\when('apply_filters')->alias(fn (string $hook, $value) => $hook === 'uamswp_fad_gbp_daily_create_budget' ? $junk : $value);

            $this->assertSame(UAMSWP_FAD_GBP_DEFAULT_CREATE_BUDGET, uamswp_fad_gbp_create_budget(), var_export($junk, true));
        }

        Functions\when('apply_filters')->alias(fn (string $hook, $value) => $hook === 'uamswp_fad_gbp_daily_create_budget' ? 0 : $value);
        $this->assertSame(0, uamswp_fad_gbp_create_budget(), 'a deliberate 0 stays 0');

        Functions\when('apply_filters')->alias(fn (string $hook, $value) => $hook === 'uamswp_fad_gbp_daily_create_budget' ? -5 : $value);
        $this->assertSame(0, uamswp_fad_gbp_create_budget(), 'a negative budget is 0');
    }

    public function test_a_run_whose_creates_are_all_sent_before_midnight_counts_on_that_day(): void
    {
        $started = $this->utc('2026-11-04 07:59:00'); // still 2026-11-03 in Los Angeles
        $sync = $this->quotaSync(['now' => $started], 2, false);

        uamswp_fad_gbp_after_run($sync, [], $started);

        $this->assertSame(['day' => '2026-11-03', 'count' => 2], $this->options[UAMSWP_FAD_GBP_COUNT_OPTION]);
    }

    public function test_a_run_that_crosses_pacific_midnight_counts_each_create_on_the_day_it_was_sent(): void
    {
        $started = $this->utc('2026-11-04 07:59:50'); // 23:59:50 on 2026-11-03 in Los Angeles
        $sent = [$this->utc('2026-11-04 07:59:55'), $this->utc('2026-11-04 08:00:05'), $this->utc('2026-11-04 08:00:06'), $this->utc('2026-11-04 08:00:07')];
        $clock = static function () use (&$sent): int {
            return array_shift($sent);
        };
        $this->options[UAMSWP_FAD_GBP_COUNT_OPTION] = ['day' => '2026-11-03', 'count' => 10];

        uamswp_fad_gbp_after_run($this->quotaSync(['now' => $started, 'clock' => $clock], 4, false), [], $started);

        $this->assertSame(['day' => '2026-11-04', 'count' => 3], $this->options[UAMSWP_FAD_GBP_COUNT_OPTION], 'the budget for the new day has 3 spent, as Google counts it');
        $this->assertSame(['2026-11-03' => 1, '2026-11-04' => 3], $this->options[UAMSWP_FAD_GBP_HISTORY_OPTION]);
    }

    public function test_the_stored_count_describes_the_newest_day_whatever_order_the_stamps_arrive_in(): void
    {
        $started = $this->utc('2026-11-04 07:59:50');
        $sent = [$this->utc('2026-11-04 08:00:05'), $this->utc('2026-11-04 07:59:55')]; // newer day first
        $clock = static function () use (&$sent): int {
            return array_shift($sent);
        };

        uamswp_fad_gbp_after_run($this->quotaSync(['now' => $started, 'clock' => $clock], 2, false), [], $started);

        $this->assertSame(['day' => '2026-11-04', 'count' => 1], $this->options[UAMSWP_FAD_GBP_COUNT_OPTION], 'with no count stored, the newest day is written last');
        $this->assertSame(['2026-11-03' => 1, '2026-11-04' => 1], $this->options[UAMSWP_FAD_GBP_HISTORY_OPTION]);
    }

    public function test_a_late_finishing_run_keeps_the_newer_stored_day_and_its_count(): void
    {
        $started = $this->utc('2026-11-04 07:59:50');
        $this->options[UAMSWP_FAD_GBP_COUNT_OPTION] = ['day' => '2026-11-04', 'count' => 7];

        uamswp_fad_gbp_after_run($this->quotaSync(['now' => $started], 2, false), [], $started);

        $this->assertSame(['day' => '2026-11-04', 'count' => 7], $this->options[UAMSWP_FAD_GBP_COUNT_OPTION]);
        $this->assertSame(['2026-11-03' => 2], $this->options[UAMSWP_FAD_GBP_HISTORY_OPTION], 'older creates still belong in the history');

        $sent = [$started, $this->utc('2026-11-04 08:00:05')];
        $clock = static function () use (&$sent): int {
            return array_shift($sent);
        };

        uamswp_fad_gbp_after_run($this->quotaSync(['now' => $started, 'clock' => $clock], 2, false), [], $started);

        $this->assertSame(['day' => '2026-11-04', 'count' => 8], $this->options[UAMSWP_FAD_GBP_COUNT_OPTION], 'same-day creates add to the existing count, even after an older day');
        $this->assertSame(['2026-11-03' => 3, '2026-11-04' => 1], $this->options[UAMSWP_FAD_GBP_HISTORY_OPTION]);
    }

    public function test_a_refused_create_is_not_stamped_or_counted(): void
    {
        $started = $this->utc('2026-11-03 17:00:00');
        $sync = $this->quotaSync(['now' => $started], 2, true);

        $this->assertSame(2, $sync->creates_made());
        $this->assertCount(2, $sync->create_times(), 'one stamp per successful create, none for the refusal');

        uamswp_fad_gbp_after_run($sync, [], $started);

        $this->assertSame(['day' => '2026-11-03', 'count' => 2], $this->options[UAMSWP_FAD_GBP_COUNT_OPTION]);
    }

    public function test_each_run_adds_its_creates_to_the_day_in_the_history_the_dashboard_reads(): void
    {
        $morning = $this->utc('2026-11-03 17:00:00');
        $evening = $this->utc('2026-11-04 06:00:00'); // still 2026-11-03 in Los Angeles
        $next_day = $this->utc('2026-11-04 17:00:00');

        uamswp_fad_gbp_after_run($this->quotaSync(['now' => $morning], 2, false), [], $morning);
        uamswp_fad_gbp_after_run($this->quotaSync(['now' => $evening], 3, false), [], $evening);
        uamswp_fad_gbp_after_run($this->quotaSync(['now' => $next_day], 4, false), [], $next_day);
        uamswp_fad_gbp_after_run($this->quotaSync(['now' => $next_day], 0, false), [], $next_day);

        $this->assertSame(['2026-11-03' => 5, '2026-11-04' => 4], $this->options[UAMSWP_FAD_GBP_HISTORY_OPTION], 'a run that made no creates adds nothing');
    }

    public function test_a_daily_refusal_in_other_words_is_still_daily(): void
    {
        foreach (["limit 'Create Location requests per day'", 'RequestsPerDay exceeded', 'Quota exceeded for limit per-day', 'The daily quota is exhausted'] as $message) {
            try {
                $this->client([[429, ['error' => ['message' => $message]]]])->location_groups();
                $this->fail('Expected a rate limit');
            } catch (UAMSWP_FAD_GBP_Rate_Limited $rateLimited) {
                $this->assertTrue($rateLimited->daily, $message);
            }
        }
    }

    public function test_finishing_a_run_with_a_held_create_queues_the_post_and_writes_no_fingerprint(): void
    {
        $this->transients['uamswp_fad_gbp_attempts_77'] = 3;
        $reset = $this->utc('2026-11-04 08:00:00');

        uamswp_fad_gbp_finish_run(77, [['action' => 'create', 'reason' => 'Creates paused', 'defer_until' => $reset], ['action' => 'noop', 'reason' => 'Listing already matches']], 'hash-1');

        $this->assertSame([[$reset + 60 + (77 % 600), UAMSWP_FAD_GBP_SYNC_HOOK, [77]]], $this->scheduled);
        $this->assertSame([], $this->postMeta, 'a held create is not settled, so no fingerprint is written');
        $this->assertArrayNotHasKey('uamswp_fad_gbp_attempts_77', $this->transients, 'the held create is not an attempt');
    }

    public function test_finishing_a_settled_run_writes_the_fingerprint_and_queues_nothing(): void
    {
        uamswp_fad_gbp_finish_run(77, [['action' => 'create', 'reason' => 'Created'], ['action' => 'noop', 'reason' => 'Listing already matches']], 'hash-1');

        $this->assertSame([77 => ['hash-1']], $this->postMeta);
        $this->assertSame([], $this->scheduled);
    }

    public function test_finishing_a_run_with_a_retryable_failure_counts_an_attempt_and_writes_no_fingerprint(): void
    {
        uamswp_fad_gbp_finish_run(77, [['action' => 'error', 'reason' => 'Google API POST failed (429): slow', 'retryable' => true]], 'hash-1');

        $this->assertSame(1, $this->transients['uamswp_fad_gbp_attempts_77']);
        $this->assertSame([], $this->postMeta);
        $this->assertCount(1, $this->scheduled);
    }

    public function test_a_post_whose_retries_give_up_is_still_queued_for_its_held_create(): void
    {
        $reset = $this->utc('2026-11-04 08:00:00');
        $this->transients['uamswp_fad_gbp_attempts_77'] = UAMSWP_FAD_GBP_MAX_ATTEMPTS;
        uamswp_fad_gbp_finish_run(77, [
            ['action' => 'error', 'reason' => 'Google API POST failed (429): slow', 'retryable' => true],
            ['action' => 'create', 'reason' => 'Creates paused', 'defer_until' => $reset],
        ], 'hash-1');

        $this->assertSame([[$reset + 60 + (77 % 600), UAMSWP_FAD_GBP_SYNC_HOOK, [77]]], $this->scheduled, 'the retry gave up, the held create is queued for the reset');
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
        // Unless a test sets its own clock, every create is sent at the run's start.
        $clock = $gate['clock'] ?? static fn (): int => $gate['now'] ?? time();
        $sync = new UAMSWP_FAD_GBP_Sync($client, ['silos' => $silos, 'modes' => ['uams' => 'live'], 'is_production' => true, 'clock' => $clock] + $gate, [], function (array $entry): void {});
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
