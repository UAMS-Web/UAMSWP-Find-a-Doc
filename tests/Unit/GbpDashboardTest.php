<?php

/**
 * The Business Profile status widget: the status it picks, the counts it shows, and who sees it.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use UAMSWP_FAD_GBP_Logic;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\stubEscapeFunctions;
use function Brain\Monkey\Functions\when;

final class GbpDashboardTest extends UnitTestCase
{
    /** 2026-11-03 19:00:00 UTC, which is 11:00 on 2026-11-03 in Los Angeles. */
    private const NOW = 1793732400;

    /** @var array<string, mixed> */
    private array $options = [];

    /** @var array<string, mixed> */
    private array $transients = [];

    /** @var array<int, array<string, array<string, mixed>>> */
    private array $cron = [];

    /** @var array<string, string|false> */
    private array $saved_env = [];

    protected function tearDown(): void
    {
        foreach ($this->saved_env as $name => $value) {
            putenv($value === false ? $name : $name.'='.$value);
        }

        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Real credentials, so a request made only when they exist would be reached by these tests.
        foreach (['GOOGLE_BUSINESS_PROFILE_CLIENT_ID', 'GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET', 'GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN'] as $name) {
            $this->saved_env[$name] = getenv($name);
            putenv($name.'=test-value');
        }

        $this->options = [];
        $this->transients = [];
        $this->cron = [];

        stubEscapeFunctions();
        when('apply_filters')->alias(fn (string $hook, $value) => $value);
        when('get_option')->alias(fn (string $name, $default = false) => $this->options[$name] ?? $default);
        when('get_transient')->alias(fn (string $key) => $this->transients[$key] ?? false);
        when('_get_cron_array')->alias(fn () => $this->cron);
        when('home_url')->justReturn('https://uamshealth.com');
        when('wp_parse_url')->alias('parse_url');
        when('admin_url')->alias(fn (string $path = '') => 'https://example.test/wp-admin/'.$path);
        when('add_query_arg')->alias(fn (string $key, string $value, string $url) => $url.'&'.$key.'='.$value);
    }

    /**
     * @param  array<int, int>  $times
     */
    private function schedule(array $times): void
    {
        foreach ($times as $i => $time) {
            $this->cron[$time]['uamswp_fad_gbp_sync_post']['key'.$i] = ['schedule' => false, 'args' => [$i]];
        }
    }

    public function test_the_clock_constant_is_the_pacific_day_these_tests_assume(): void
    {
        $this->assertSame('2026-11-03', UAMSWP_FAD_GBP_Logic::pacific_day(self::NOW));
    }

    public function test_every_silo_off_is_off_even_with_work_queued_and_a_pause_in_force(): void
    {
        $status = UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'off', 'baptist-health' => 'off'], false, self::NOW + 600, self::NOW + 600, self::NOW);

        $this->assertSame(['key' => 'off', 'label' => 'Off'], $status);
    }

    public function test_missing_credentials_is_not_configured_and_off_still_wins(): void
    {
        $status = UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'live'], true, 0, 0, self::NOW, false);
        $off = UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'off'], false, 0, 0, self::NOW, false);

        $this->assertSame('unconfigured', $status['key']);
        $this->assertStringContainsString('credentials are missing', $status['label']);
        $this->assertSame('off', $off['key']);
    }

    public function test_the_data_reports_missing_credentials(): void
    {
        foreach (['GOOGLE_BUSINESS_PROFILE_CLIENT_ID', 'GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET', 'GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN'] as $name) {
            putenv($name);
        }

        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];

        $this->assertSame('unconfigured', uamswp_fad_gbp_dashboard_data(self::NOW)['status']['key']);
    }

    public function test_a_spent_budget_with_no_stored_pause_shows_creates_paused_and_the_events_waiting_for_the_reset(): void
    {
        $reset = UAMSWP_FAD_GBP_Logic::next_pacific_reset(self::NOW);
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];
        $this->options['uamswp_fad_gbp_create_count'] = ['day' => '2026-11-03', 'count' => 80];
        $this->schedule([$reset + 61, $reset + 300, self::NOW + 100]);

        $data = uamswp_fad_gbp_dashboard_data(self::NOW);

        $this->assertSame('paused', $data['status']['key']);
        $this->assertStringContainsString('Creates paused until 2026-11-04 00:00 PST', $data['status']['label']);
        $this->assertSame(2, $data['queue']['waiting']);
        $this->assertSame(1, array_sum($data['queue']['hours']), 'only the event before the reset is counted by the hour');
    }

    public function test_a_budget_not_yet_spent_shows_no_pause(): void
    {
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];
        $this->options['uamswp_fad_gbp_create_count'] = ['day' => '2026-11-03', 'count' => 79];

        $this->assertSame('idle', uamswp_fad_gbp_dashboard_data(self::NOW)['status']['key']);
    }

    public function test_today_in_the_history_is_never_below_the_counter(): void
    {
        $this->options['uamswp_fad_gbp_create_count'] = ['day' => '2026-11-03', 'count' => 32];
        $this->options['uamswp_fad_gbp_create_history'] = ['2026-11-03' => 2, '2026-11-02' => 5];

        $days = uamswp_fad_gbp_dashboard_data(self::NOW)['days'];

        $this->assertSame([5, 32], array_slice(array_column($days, 'count'), -2));
    }

    public function test_a_budget_of_zero_says_creates_are_disabled_and_draws_no_bar(): void
    {
        when('current_user_can')->justReturn(true);
        when('apply_filters')->alias(fn (string $hook, $value) => $hook === 'uamswp_fad_gbp_daily_create_budget' ? 0 : $value);
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];

        ob_start();
        uamswp_fad_gbp_render_dashboard_widget();
        $html = (string) ob_get_clean();

        $this->assertStringContainsString('Creates are disabled (daily budget 0).', $html);
        $this->assertStringNotContainsString('<progress', $html);
        $this->assertStringNotContainsString('0 of 0', $html);
    }

    public function test_the_hour_chart_and_the_log_counts_say_what_they_leave_out(): void
    {
        when('current_user_can')->justReturn(true);
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];

        ob_start();
        uamswp_fad_gbp_render_dashboard_widget();
        $html = (string) ob_get_clean();

        $this->assertStringContainsString('not counting those waiting for the reset', $html);
        $this->assertStringContainsString('From the newest 300 activity log entries', $html);
    }

    public function test_a_run_holding_the_lock_is_running(): void
    {
        $status = UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'live'], true, 0, 0, self::NOW);

        $this->assertSame('running', $status['key']);
    }

    public function test_a_pause_shows_the_reset_and_the_time_remaining(): void
    {
        $reset = UAMSWP_FAD_GBP_Logic::next_pacific_reset(self::NOW);
        $status = UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'live'], false, $reset, 0, self::NOW);

        $this->assertSame('paused', $status['key']);
        $this->assertStringContainsString('Creates paused until 2026-11-04 00:00 PST', $status['label']);
        $this->assertStringContainsString('resets at Pacific midnight', $status['label']);
        $this->assertStringContainsString('13 h 0 min left', $status['label']);
    }

    public function test_a_pause_that_has_ended_is_not_shown(): void
    {
        $status = UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'live'], false, self::NOW - 1, 0, self::NOW);

        $this->assertSame('idle', $status['key']);
    }

    public function test_a_backoff_is_shown_and_a_pause_wins_over_it(): void
    {
        $backoff = UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'live'], false, 0, self::NOW + 120, self::NOW);
        $both = UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'live'], false, self::NOW + 3600, self::NOW + 120, self::NOW);

        $this->assertSame('backoff', $backoff['key']);
        $this->assertStringContainsString('2 min left', $backoff['label']);
        $this->assertSame('paused', $both['key']);
    }

    public function test_only_dry_run_silos_in_use_is_dry_run_and_a_live_silo_is_idle(): void
    {
        $this->assertSame('dry-run', UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'dry-run', 'baptist-health' => 'off'], false, 0, 0, self::NOW)['key']);
        $this->assertSame('idle', UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'dry-run', 'baptist-health' => 'live'], false, 0, 0, self::NOW)['key']);
    }

    public function test_remaining_time_is_written_for_a_person(): void
    {
        $this->assertSame('under 1 min', UAMSWP_FAD_GBP_Logic::remaining_label(59));
        $this->assertSame('under 1 min', UAMSWP_FAD_GBP_Logic::remaining_label(-5));
        $this->assertSame('45 min', UAMSWP_FAD_GBP_Logic::remaining_label(2700));
        $this->assertSame('2 h 5 min', UAMSWP_FAD_GBP_Logic::remaining_label(7500));
    }

    public function test_a_create_adds_to_its_day_and_old_days_fall_off(): void
    {
        $history = ['2026-10-01' => 9, '2026-11-03' => 4];

        $this->assertSame(['2026-10-01' => 9, '2026-11-03' => 7], UAMSWP_FAD_GBP_Logic::add_to_history($history, '2026-11-03', 3));
        $this->assertSame(['2026-11-02' => 1, '2026-11-03' => 2], UAMSWP_FAD_GBP_Logic::add_to_history(['2026-10-01' => 9, '2026-11-02' => 1], '2026-11-03', 2, 2));
        $this->assertSame(['2026-11-03' => 1], UAMSWP_FAD_GBP_Logic::add_to_history(['junk' => 5, '2026-11-02' => 'x', 7 => 3], '2026-11-03', 1), 'unreadable entries are dropped');
    }

    public function test_the_last_seven_days_end_today_and_missing_days_are_zero(): void
    {
        $days = UAMSWP_FAD_GBP_Logic::last_days(['2026-11-03' => 12, '2026-10-30' => 80, '2026-10-01' => 5], self::NOW);

        $this->assertSame(['2026-10-28', '2026-10-29', '2026-10-30', '2026-10-31', '2026-11-01', '2026-11-02', '2026-11-03'], array_column($days, 'day'));
        $this->assertSame([0, 0, 80, 0, 0, 0, 12], array_column($days, 'count'));
    }

    public function test_the_last_seven_days_are_right_across_both_daylight_saving_changes(): void
    {
        // 2026-03-08 has 23 hours and 2026-11-01 has 25 in Los Angeles.
        $spring = UAMSWP_FAD_GBP_Logic::last_days([], strtotime('2026-03-09 08:00:00 UTC'));
        $fall = UAMSWP_FAD_GBP_Logic::last_days([], strtotime('2026-11-02 08:00:00 UTC'));

        $this->assertSame(['2026-03-03', '2026-03-04', '2026-03-05', '2026-03-06', '2026-03-07', '2026-03-08', '2026-03-09'], array_column($spring, 'day'));
        $this->assertSame(['2026-10-27', '2026-10-28', '2026-10-29', '2026-10-30', '2026-10-31', '2026-11-01', '2026-11-02'], array_column($fall, 'day'));
    }

    public function test_the_last_day_is_the_pacific_day_not_the_utc_day(): void
    {
        // 06:00 UTC on 2026-11-04 is still the evening of 2026-11-03 in Los Angeles.
        $days = UAMSWP_FAD_GBP_Logic::last_days(['2026-11-03' => 9], strtotime('2026-11-04 06:00:00 UTC'));

        $this->assertSame('2026-11-03', $days[6]['day']);
        $this->assertSame(9, $days[6]['count']);
        $this->assertSame('2026-10-28', $days[0]['day']);
    }

    public function test_the_queue_is_sorted_into_due_waiting_and_hours(): void
    {
        $paused_until = self::NOW + 50000;
        $times = [self::NOW - 60, self::NOW, self::NOW + 30, self::NOW + 3700, self::NOW + 49999, $paused_until, $paused_until + 75];

        $summary = UAMSWP_FAD_GBP_Logic::queue_summary($times, self::NOW, $paused_until);

        $this->assertSame(7, $summary['total']);
        $this->assertSame(2, $summary['due'], 'at or before now');
        $this->assertSame(2, $summary['waiting'], 'events at or after the end of the pause wait for the reset');
        $this->assertSame(24, count($summary['hours']));
        $this->assertSame(1, $summary['hours'][0]);
        $this->assertSame(1, $summary['hours'][1]);
        $this->assertSame(1, $summary['hours'][13]);
        $this->assertSame(3, array_sum($summary['hours']));
    }

    public function test_an_event_more_than_a_day_away_is_in_the_total_only(): void
    {
        $summary = UAMSWP_FAD_GBP_Logic::queue_summary([self::NOW + 86399, self::NOW + 86400, self::NOW + 90000], self::NOW, 0);

        $this->assertSame(3, $summary['total']);
        $this->assertSame(1, $summary['hours'][23], 'the last second of the 24th hour');
        $this->assertSame(1, array_sum($summary['hours']));
    }

    public function test_with_nothing_paused_events_after_the_reset_are_not_waiting(): void
    {
        $reset = UAMSWP_FAD_GBP_Logic::next_pacific_reset(self::NOW);

        $summary = UAMSWP_FAD_GBP_Logic::queue_summary([$reset + 75], self::NOW, 0);

        $this->assertSame(0, $summary['waiting']);
    }

    public function test_outcomes_count_only_the_last_day_and_only_work_that_was_carried_out(): void
    {
        $log = [
            ['time' => self::NOW - 10, 'action' => 'create', 'reason' => 'Created'],
            ['time' => self::NOW - 10, 'action' => 'create', 'reason' => 'Dry run, no listing created'],
            ['time' => self::NOW - 20, 'action' => 'update', 'reason' => 'Updated'],
            ['time' => self::NOW - 20, 'action' => 'update', 'reason' => 'Dry run, no write made'],
            ['time' => self::NOW - 30, 'action' => 'review', 'reason' => 'Store code is on 2 listings'],
            ['time' => self::NOW - 40, 'action' => 'error', 'reason' => 'boom'],
            ['time' => self::NOW - 86401, 'action' => 'error', 'reason' => 'too old'],
            ['time' => self::NOW - 50, 'action' => 'skip', 'reason' => 'Silo uams is off'],
        ];

        $this->assertSame(['create' => 1, 'update' => 1, 'review' => 1, 'error' => 1], UAMSWP_FAD_GBP_Logic::outcome_counts($log, self::NOW));
    }

    public function test_the_data_reads_the_counter_for_today_only_and_the_scheduled_events_for_the_sync_hook(): void
    {
        $this->options['uamswp_fad_gbp_create_count'] = ['day' => '2026-11-03', 'count' => 17];
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];
        $this->options['uamswp_fad_gbp_create_history'] = ['2026-11-02' => 80, '2026-11-03' => 17];
        $this->schedule([self::NOW - 5, self::NOW + 4000]);
        $this->cron[self::NOW + 10]['some_other_hook']['k'] = ['schedule' => false, 'args' => []];

        $data = uamswp_fad_gbp_dashboard_data(self::NOW);

        $this->assertSame(17, $data['used']);
        $this->assertSame(80, $data['budget']);
        $this->assertSame(2, $data['queue']['total'], 'an event for another hook is not counted');
        $this->assertSame(1, $data['queue']['due']);
        $this->assertSame([80, 17], array_slice(array_column($data['days'], 'count'), -2));

        $this->options['uamswp_fad_gbp_create_count'] = ['day' => '2026-11-02', 'count' => 80];
        $this->assertSame(0, uamswp_fad_gbp_dashboard_data(self::NOW)['used'], "yesterday's counter is not today's");
    }

    public function test_with_the_queue_empty_and_every_silo_off_the_widget_says_off_not_idle(): void
    {
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'off', 'arkansas-childrens' => 'off', 'baptist-health' => 'off'];

        $data = uamswp_fad_gbp_dashboard_data(self::NOW);

        $this->assertSame(0, $data['queue']['total']);
        $this->assertSame('Off', $data['status']['label']);
    }

    public function test_the_widget_shows_the_pause_the_bar_and_a_text_figure_for_each_graphic(): void
    {
        when('current_user_can')->justReturn(true);
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];
        $this->options['uamswp_fad_gbp_creates_paused_until'] = time() + 7200;
        $this->options['uamswp_fad_gbp_create_count'] = ['day' => UAMSWP_FAD_GBP_Logic::pacific_day(time()), 'count' => 20];

        ob_start();
        uamswp_fad_gbp_render_dashboard_widget();
        $html = (string) ob_get_clean();

        $this->assertStringContainsString('Status: Creates paused until', $html);
        $this->assertStringContainsString('20 of 80 (25%)', $html);
        $this->assertStringContainsString('<progress max="80" value="20"', $html);
        $this->assertSame(2, substr_count($html, '<svg role="img" aria-label="'), 'two graphics, each named');
        $this->assertStringContainsString('aria-label="Creates sent on each of the last 7 Pacific days, oldest first: 0, 0, 0, 0, 0, 0, 20"', $html, 'the full list stays in the name');
        $this->assertStringContainsString('aria-label="Scheduled sync events due in each of the next 24 hours, not counting those waiting for the reset: '.implode(', ', array_fill(0, 24, 0)).'"', $html);
        $this->assertStringContainsString("<small>20 creates sent in the last 7 days, the most (20) today. Counted by this site as it sends; Google's own quota figure is in the Google Cloud console.</small>", $html);
        $this->assertStringContainsString('<small>Nothing due in the next 24 hours. Not counting those waiting for the reset.</small>', $html);
        $this->assertStringContainsString('<span>6 days ago</span><span>today</span>', $html);
        $this->assertStringContainsString('<span>now</span><span>+24 h</span>', $html);
        $this->assertStringNotContainsString('oldest first: 0, 0, 0, 0, 0, 0, 20.', $html, 'no bare list in a caption');
        $this->assertStringContainsString('log_action=error', $html);
        $this->assertStringNotContainsString('<script', $html);
        $this->assertStringNotContainsString('<link', $html);
    }

    public function test_the_days_caption_says_when_no_creates_were_sent(): void
    {
        $days = UAMSWP_FAD_GBP_Logic::last_days([], self::NOW);

        $this->assertSame('No creates sent in the last 7 days.', uamswp_fad_gbp_days_caption($days));
    }

    public function test_the_days_caption_gives_the_total_and_the_day_with_the_most(): void
    {
        $days = UAMSWP_FAD_GBP_Logic::last_days(['2026-10-30' => 1180, '2026-11-02' => 40, '2026-11-03' => 3], self::NOW);

        $this->assertSame('1,223 creates sent in the last 7 days, the most (1,180) on Friday.', uamswp_fad_gbp_days_caption($days));
    }

    public function test_the_days_caption_names_today_and_yesterday_and_takes_the_latest_of_a_tie(): void
    {
        $this->assertSame('1 create sent in the last 7 days, the most (1) today.', uamswp_fad_gbp_days_caption(UAMSWP_FAD_GBP_Logic::last_days(['2026-11-03' => 1], self::NOW)));
        // 2026-10-29 and 2026-11-02 both sent 5; the later one is named.
        $this->assertSame('10 creates sent in the last 7 days, the most (5) yesterday.', uamswp_fad_gbp_days_caption(UAMSWP_FAD_GBP_Logic::last_days(['2026-10-29' => 5, '2026-11-02' => 5], self::NOW)));
    }

    public function test_the_queue_caption_says_when_nothing_is_due(): void
    {
        $this->assertSame('Nothing due in the next 24 hours.', uamswp_fad_gbp_queue_caption(array_fill(0, 24, 0)));
    }

    public function test_the_queue_caption_for_a_queue_that_fits_in_the_next_hour(): void
    {
        $hours = array_fill(0, 24, 0);
        $hours[0] = 1500;

        $this->assertSame('1,500 due within the next hour.', uamswp_fad_gbp_queue_caption($hours));
    }

    public function test_the_queue_caption_gives_the_total_the_busiest_hour_and_how_far_off_the_last_is(): void
    {
        // Staging on 2026-11-16: five full hours, then the rest in the sixth.
        $hours = array_merge([240, 240, 240, 240, 240, 23], array_fill(0, 18, 0));

        $this->assertSame('1,223 due in the next 24 hours, at most 240 an hour; the last is due in about 5 hours.', uamswp_fad_gbp_queue_caption($hours));

        $hours = array_fill(0, 24, 0);
        $hours[1] = 2;

        $this->assertSame('2 due in the next 24 hours, at most 2 an hour; the last is due in about 1 hour.', uamswp_fad_gbp_queue_caption($hours));

        // An hour holding a single event still counts as the last.
        $hours = array_fill(0, 24, 0);
        $hours[0] = 5;
        $hours[3] = 1;

        $this->assertSame('6 due in the next 24 hours, at most 5 an hour; the last is due in about 3 hours.', uamswp_fad_gbp_queue_caption($hours));
    }

    public function test_a_chart_is_labeled_at_each_end_without_the_labels_in_its_name(): void
    {
        $chart = uamswp_fad_gbp_dashboard_bars([1, 2], 2, 'Two values: 1, 2', '<start>', 'end');

        $this->assertStringStartsWith('<svg role="img" aria-label="Two values: 1, 2" width="27"', $chart);
        $this->assertStringEndsWith('</svg><span aria-hidden="true" style="display:flex;justify-content:space-between;width:27px;font-size:11px"><span>&lt;start&gt;</span><span>end</span></span>', $chart);
        $this->assertStringEndsWith('</svg>', uamswp_fad_gbp_dashboard_bars([1, 2], 2, 'Two values: 1, 2'), 'no labels, no label row');
        $this->assertStringEndsWith('</svg><span aria-hidden="true" style="display:flex;justify-content:space-between;width:27px;font-size:11px"><span>start</span><span></span></span>', uamswp_fad_gbp_dashboard_bars([1, 2], 2, 'Two values: 1, 2', 'start'), 'one label still gets the row');
    }

    public function test_a_user_without_the_settings_capability_gets_no_widget_and_no_output(): void
    {
        when('current_user_can')->justReturn(false);
        expect('wp_add_dashboard_widget')->never();

        uamswp_fad_gbp_register_dashboard_widget();

        ob_start();
        uamswp_fad_gbp_render_dashboard_widget();

        $this->assertSame('', (string) ob_get_clean());
    }

    public function test_a_user_with_the_capability_gets_the_widget_registered(): void
    {
        when('current_user_can')->justReturn(true);
        expect('wp_add_dashboard_widget')->once()->with('uamswp_fad_gbp_status', 'Business Profile Sync', 'uamswp_fad_gbp_render_dashboard_widget');

        uamswp_fad_gbp_register_dashboard_widget();
    }

    public function test_loading_the_dashboard_makes_no_request_to_google(): void
    {
        when('current_user_can')->justReturn(true);
        expect('wp_remote_request')->never();
        expect('wp_remote_get')->never();
        expect('wp_remote_post')->never();
        expect('uamswp_fad_gbp_transport')->never();

        ob_start();
        uamswp_fad_gbp_render_dashboard_widget();
        ob_end_clean();
    }

    /**
     * Render the widget as an administrator and return its HTML.
     */
    private function render(): string
    {
        when('current_user_can')->justReturn(true);
        // WordPress documents both as taking a string, so these stubs hold the widget to that. The check is in the
        // body because the stub is called from a file without strict types, which would coerce a typed parameter.
        $escape = static function (mixed $text): string {
            if (! is_string($text)) {
                throw new \TypeError('An escape function got '.get_debug_type($text).', not a string');
            }

            return htmlspecialchars($text, ENT_QUOTES);
        };
        when('esc_attr')->alias($escape);
        when('esc_html')->alias($escape);
        ob_start();
        uamswp_fad_gbp_render_dashboard_widget();

        return (string) ob_get_clean();
    }

    public function test_a_counter_for_today_with_no_count_counts_as_none(): void
    {
        $this->options['uamswp_fad_gbp_create_count'] = ['day' => '2026-11-03'];

        $this->assertSame(0, uamswp_fad_gbp_dashboard_data(self::NOW)['used']);
    }

    public function test_the_data_counts_the_outcomes_in_the_activity_log(): void
    {
        $this->options['uamswp_fad_gbp_log'] = [['time' => self::NOW - 10, 'action' => 'create', 'reason' => 'Created']];

        $this->assertSame(['create' => 1, 'update' => 0, 'review' => 0, 'error' => 0], uamswp_fad_gbp_dashboard_data(self::NOW)['outcomes']);
    }

    public function test_today_in_the_data_keeps_its_day_beside_the_count(): void
    {
        $this->options['uamswp_fad_gbp_create_count'] = ['day' => '2026-11-03', 'count' => 3];

        $this->assertSame(['day' => '2026-11-03', 'count' => 3], uamswp_fad_gbp_dashboard_data(self::NOW)['days'][6]);
    }

    public function test_the_data_shows_running_while_a_run_holds_the_lock(): void
    {
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];
        $this->transients['uamswp_fad_gbp_lock'] = 1;

        $this->assertSame('running', uamswp_fad_gbp_dashboard_data(self::NOW)['status']['key']);
    }

    public function test_a_chart_draws_each_bar_to_scale_on_a_baseline(): void
    {
        $bar = fn (int $x, int $height): string => '<rect x="'.$x.'" y="'.(40 - $height).'" width="12" height="'.$height.'" fill="currentColor"></rect>'
            .'<line x1="'.$x.'" y1="40" x2="'.($x + 12).'" y2="40" stroke="currentColor" stroke-width="1"></line>';

        // 1, 3 and 4 of 7 are 5.7, 17.1 and 22.9 of the 40 high chart, so each rounds to the nearest.
        $this->assertSame(
            '<svg role="img" aria-label="Five values" width="72" height="41" viewBox="0 0 72 41" focusable="false">'
            .$bar(0, 0).$bar(15, 6).$bar(30, 17).$bar(45, 23).$bar(60, 40).'</svg>',
            uamswp_fad_gbp_dashboard_bars([0, 1, 3, 4, 7], 7, 'Five values')
        );
    }

    public function test_a_chart_draws_a_small_value_two_high_and_scales_to_a_value_above_the_ceiling(): void
    {
        $this->assertStringContainsString('<rect x="0" y="38" width="12" height="2"', uamswp_fad_gbp_dashboard_bars([1], 100, 'One small value'));
        $this->assertStringContainsString('<rect x="0" y="0" width="12" height="40"', uamswp_fad_gbp_dashboard_bars([1], 0, 'One value, no ceiling'));
        $this->assertStringContainsString('<rect x="15" y="20" width="12" height="20"', uamswp_fad_gbp_dashboard_bars([4, 2], 1, 'A value above the ceiling'));
    }

    public function test_a_chart_with_no_values_has_no_width(): void
    {
        $this->assertSame('<svg role="img" aria-label="Nothing" width="0" height="41" viewBox="0 0 0 41" focusable="false"></svg>', uamswp_fad_gbp_dashboard_bars([], 0, 'Nothing'));
    }

    /**
     * @return array<string, array{int, string}>
     */
    public static function creates_sent(): array
    {
        return [
            'one, which rounds down' => [1, '1 of 80 (1%).'],
            'seven, which rounds up' => [7, '7 of 80 (9%).'],
            'sixty' => [60, '60 of 80 (75%).'],
            'more than the budget' => [100, '100 of 80 (100%).'],
        ];
    }

    #[DataProvider('creates_sent')]
    public function test_the_widget_gives_the_creates_sent_today_as_a_rounded_share_of_the_budget(int $used, string $figure): void
    {
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];
        $this->options['uamswp_fad_gbp_create_count'] = ['day' => UAMSWP_FAD_GBP_Logic::pacific_day(time()), 'count' => $used];

        $html = $this->render();

        $this->assertStringContainsString($figure, $html);
        $this->assertStringContainsString('<progress max="80" value="'.min($used, 80).'" aria-label="Creates sent today">'.$used.' of 80</progress>', $html);
    }

    public function test_the_widget_says_how_long_until_the_reset(): void
    {
        $before = time();
        $html = $this->render();
        $after = time();
        $reset = UAMSWP_FAD_GBP_Logic::next_pacific_reset($before);

        // The widget reads the clock itself, so either second around the call may be the one it read.
        $labels = array_unique([UAMSWP_FAD_GBP_Logic::remaining_label($reset - $before), UAMSWP_FAD_GBP_Logic::remaining_label($reset - $after)]);
        $this->assertNotEmpty(array_filter($labels, fn (string $label): bool => str_contains($html, ', in '.$label.'.')));
    }

    public function test_the_widget_counts_the_queue_and_charts_it_against_one_event_an_hour(): void
    {
        $now = time();
        $this->schedule([$now - 10, $now + 100, $now + 3700]);

        $html = $this->render();

        $this->assertStringContainsString('3 scheduled, 1 due now, 0 waiting for the reset.', $html);
        $this->assertStringContainsString('<rect x="0" y="0" width="12" height="40"', $html, 'one event in the next hour fills the bar');
    }

    public function test_the_widget_links_each_outcome_to_the_activity_log_filtered_by_it(): void
    {
        $html = $this->render();

        foreach (['create' => 'created', 'update' => 'updated', 'review' => 'for review', 'error' => 'errors'] as $action => $label) {
            $this->assertStringContainsString('<li><a href="https://example.test/wp-admin/admin.php?page=uamswp-fad-gbp&log_action='.$action.'">0 '.$label.'</a></li>', $html);
        }
    }

    public function test_the_widget_is_registered_on_dashboard_setup(): void
    {
        expect('add_action')->once()->with('wp_dashboard_setup', 'uamswp_fad_gbp_register_dashboard_widget');

        uamswp_fad_gbp_register_dashboard_hooks();
    }
}
