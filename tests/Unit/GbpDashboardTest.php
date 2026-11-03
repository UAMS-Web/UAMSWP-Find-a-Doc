<?php

/**
 * The Business Profile status widget: the status it picks, the counts it shows, and who sees it.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use UAMSWP_FAD_GBP_Logic;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpDashboardTest extends UnitTestCase
{
    /** 2026-11-03 19:00:00 UTC, which is 11:00 on 2026-11-03 in Los Angeles. */
    private const NOW = 1793732400;

    /** @var array<string, mixed> */
    private $options = [];

    /** @var array<string, mixed> */
    private $transients = [];

    /** @var array<int, array<string, array<string, mixed>>> */
    private $cron = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->options = [];
        $this->transients = [];
        $this->cron = [];

        Functions\stubEscapeFunctions();
        Functions\when('apply_filters')->alias(fn (string $hook, $value) => $value);
        Functions\when('get_option')->alias(fn (string $name, $default = false) => $this->options[$name] ?? $default);
        Functions\when('get_transient')->alias(fn (string $key) => $this->transients[$key] ?? false);
        Functions\when('_get_cron_array')->alias(fn () => $this->cron);
        Functions\when('home_url')->justReturn('https://uamshealth.com');
        Functions\when('wp_parse_url')->alias('parse_url');
        Functions\when('admin_url')->alias(fn (string $path = '') => 'https://example.test/wp-admin/'.$path);
        Functions\when('add_query_arg')->alias(fn (string $key, string $value, string $url) => $url.'&'.$key.'='.$value);
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
        $spring = UAMSWP_FAD_GBP_Logic::last_days([], (int) strtotime('2026-03-09 08:00:00 UTC'));
        $fall = UAMSWP_FAD_GBP_Logic::last_days([], (int) strtotime('2026-11-02 08:00:00 UTC'));

        $this->assertSame(['2026-03-03', '2026-03-04', '2026-03-05', '2026-03-06', '2026-03-07', '2026-03-08', '2026-03-09'], array_column($spring, 'day'));
        $this->assertSame(['2026-10-27', '2026-10-28', '2026-10-29', '2026-10-30', '2026-10-31', '2026-11-01', '2026-11-02'], array_column($fall, 'day'));
    }

    public function test_the_last_day_is_the_pacific_day_not_the_utc_day(): void
    {
        // 06:00 UTC on 2026-11-04 is still the evening of 2026-11-03 in Los Angeles.
        $days = UAMSWP_FAD_GBP_Logic::last_days(['2026-11-03' => 9], (int) strtotime('2026-11-04 06:00:00 UTC'));

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
        $this->assertSame(0, uamswp_fad_gbp_dashboard_data(self::NOW)['used'], 'yesterday\'s counter is not today\'s');
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
        Functions\when('current_user_can')->justReturn(true);
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
        $this->assertStringContainsString('Last 7 days, oldest first:', $html);
        $this->assertStringContainsString('Next 24 hours, per hour:', $html);
        $this->assertStringContainsString('log_action=error', $html);
        $this->assertStringNotContainsString('<script', $html);
        $this->assertStringNotContainsString('<link', $html);
    }

    public function test_a_user_without_the_settings_capability_gets_no_widget_and_no_output(): void
    {
        Functions\when('current_user_can')->justReturn(false);
        Functions\expect('wp_add_dashboard_widget')->never();

        uamswp_fad_gbp_register_dashboard_widget();

        ob_start();
        uamswp_fad_gbp_render_dashboard_widget();

        $this->assertSame('', (string) ob_get_clean());
    }

    public function test_a_user_with_the_capability_gets_the_widget_registered(): void
    {
        Functions\when('current_user_can')->justReturn(true);
        Functions\expect('wp_add_dashboard_widget')->once()->with('uamswp_fad_gbp_status', 'Business Profile Sync', 'uamswp_fad_gbp_render_dashboard_widget');

        uamswp_fad_gbp_register_dashboard_widget();
    }

    public function test_loading_the_dashboard_makes_no_request_to_google(): void
    {
        Functions\when('current_user_can')->justReturn(true);
        Functions\expect('wp_remote_request')->never();
        Functions\expect('wp_remote_get')->never();
        Functions\expect('wp_remote_post')->never();
        Functions\expect('uamswp_fad_gbp_transport')->never();

        ob_start();
        uamswp_fad_gbp_render_dashboard_widget();
        ob_end_clean();
    }
}
