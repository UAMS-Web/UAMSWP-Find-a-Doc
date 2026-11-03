<?php

/**
 * Business Profile sync: the status widget on the WordPress Dashboard.
 *
 * Shows whether the sync is running, backed off or waiting for Google's daily reset, how many creates
 * this site has sent today, the scheduled queue, and the last day's outcomes. It reads only options,
 * the scheduled events and the activity log, so loading the Dashboard makes no Google request.
 */

declare(strict_types=1);

/**
 * Everything the widget shows, gathered from this site's own records.
 *
 * @return array{status: array{key: string, label: string}, used: int, budget: int, reset: int, days: array<int, array{day: string, count: int}>, queue: array{total: int, due: int, waiting: int, hours: array<int, int>}, outcomes: array{create: int, update: int, review: int, error: int}}
 */
function uamswp_fad_gbp_dashboard_data(?int $now = null): array
{
    $now ??= time();
    $production = uamswp_fad_gbp_is_production();
    $modes = [];

    foreach (uamswp_fad_gbp_modes() as $silo => $mode) {
        $modes[$silo] = UAMSWP_FAD_GBP_Logic::effective_mode($mode, $production);
    }

    $paused_until = (int) get_option(UAMSWP_FAD_GBP_PAUSE_OPTION, 0);
    $backoff_until = (int) get_option(UAMSWP_FAD_GBP_BACKOFF_OPTION, 0);

    $count = get_option(UAMSWP_FAD_GBP_COUNT_OPTION, []);
    $used = is_array($count) && ($count['day'] ?? '') === UAMSWP_FAD_GBP_Logic::pacific_day($now) ? (int) ($count['count'] ?? 0) : 0;

    $history = get_option(UAMSWP_FAD_GBP_HISTORY_OPTION, []);
    $log = get_option('uamswp_fad_gbp_log', []);

    return [
        'status' => UAMSWP_FAD_GBP_Logic::dashboard_status($modes, (bool) get_transient('uamswp_fad_gbp_lock'), $paused_until, $backoff_until, $now),
        'used' => $used,
        'budget' => uamswp_fad_gbp_create_budget(),
        'reset' => UAMSWP_FAD_GBP_Logic::next_pacific_reset($now),
        'days' => UAMSWP_FAD_GBP_Logic::last_days(is_array($history) ? $history : [], $now),
        'queue' => UAMSWP_FAD_GBP_Logic::queue_summary(uamswp_fad_gbp_scheduled_times(), $now, $paused_until),
        'outcomes' => UAMSWP_FAD_GBP_Logic::outcome_counts(is_array($log) ? $log : [], $now),
    ];
}

/**
 * The scheduled time of every pending sync event.
 *
 * @return array<int, int>
 */
function uamswp_fad_gbp_scheduled_times(): array
{
    $cron = _get_cron_array();
    $times = [];

    if (! is_array($cron)) {
        return $times;
    }

    foreach ($cron as $time => $hooks) {
        foreach ((array) ($hooks[UAMSWP_FAD_GBP_SYNC_HOOK] ?? []) as $ignored) {
            $times[] = (int) $time;
        }
    }

    return $times;
}

/**
 * A bar chart as inline SVG. The values are also written out by the caller, so the figure is never the only carrier.
 *
 * @param  array<int, int>  $values
 */
function uamswp_fad_gbp_dashboard_bars(array $values, int $ceiling, string $description): string
{
    $width = 12;
    $gap = 3;
    $height = 40;
    $ceiling = max(1, $ceiling, ...$values);
    $bars = '';

    foreach ($values as $i => $value) {
        $bar = $value > 0 ? max(2, (int) round($value / $ceiling * $height)) : 0;
        $bars .= '<rect x="'.($i * ($width + $gap)).'" y="'.($height - $bar).'" width="'.$width.'" height="'.$bar.'" fill="currentColor"></rect>';
        $bars .= '<line x1="'.($i * ($width + $gap)).'" y1="'.$height.'" x2="'.($i * ($width + $gap) + $width).'" y2="'.$height.'" stroke="currentColor" stroke-width="1"></line>';
    }

    $total = max(0, count($values) * ($width + $gap) - $gap);

    return '<svg role="img" aria-label="'.esc_attr($description).'" width="'.$total.'" height="'.($height + 1).'" viewBox="0 0 '.$total.' '.($height + 1).'" focusable="false">'.$bars.'</svg>';
}

/**
 * Print the widget.
 */
function uamswp_fad_gbp_render_dashboard_widget(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }

    $data = uamswp_fad_gbp_dashboard_data();
    $settings = admin_url('admin.php?page=uamswp-fad-gbp');
    $percent = $data['budget'] > 0 ? min(100, (int) round($data['used'] / $data['budget'] * 100)) : 100;
    $day_counts = array_column($data['days'], 'count');
    $queue = $data['queue'];
    $remaining = UAMSWP_FAD_GBP_Logic::remaining_label($data['reset'] - time());

    ?>
    <p><strong>Status: <?php echo esc_html($data['status']['label']); ?></strong></p>

    <h3>Creates sent today</h3>
    <p>
        <?php echo esc_html($data['used'].' of '.$data['budget']); ?> (<?php echo esc_html((string) $percent); ?>%).
        Resets <?php echo esc_html(UAMSWP_FAD_GBP_Logic::reset_label($data['reset'])); ?>, in <?php echo esc_html($remaining); ?>.
    </p>
    <progress max="<?php echo esc_attr((string) max(1, $data['budget'])); ?>" value="<?php echo esc_attr((string) min($data['used'], max(1, $data['budget']))); ?>" aria-label="Creates sent today"><?php echo esc_html($data['used'].' of '.$data['budget']); ?></progress>
    <p>
        <?php echo uamswp_fad_gbp_dashboard_bars($day_counts, $data['budget'], 'Creates sent on each of the last 7 Pacific days, oldest first: '.implode(', ', $day_counts)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from integers and an escaped attribute.?>
        <br><small>Last 7 days, oldest first: <?php echo esc_html(implode(', ', $day_counts)); ?>. Counted by this site as it sends; Google's own quota figure is in the Google Cloud console.</small>
    </p>

    <h3>Queue</h3>
    <p>
        <?php echo esc_html($queue['total'].' scheduled, '.$queue['due'].' due now, '.$queue['waiting'].' waiting for the reset.'); ?>
        <br><?php echo uamswp_fad_gbp_dashboard_bars($queue['hours'], 1, 'Scheduled sync events in each of the next 24 hours: '.implode(', ', $queue['hours'])); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from integers and an escaped attribute.?>
        <br><small>Next 24 hours, per hour: <?php echo esc_html(implode(', ', $queue['hours'])); ?>.</small>
    </p>

    <h3>Last 24 hours</h3>
    <ul>
        <?php foreach (['create' => 'created', 'update' => 'updated', 'review' => 'for review', 'error' => 'errors'] as $action => $label) { ?>
            <li><a href="<?php echo esc_url(add_query_arg('log_action', $action, $settings)); ?>"><?php echo esc_html($data['outcomes'][$action].' '.$label); ?></a></li>
        <?php } ?>
    </ul>

    <p><a href="<?php echo esc_url($settings); ?>">Business Profile Sync settings and activity log</a></p>
    <?php
}

/**
 * Add the widget for users who can open the settings screen.
 */
function uamswp_fad_gbp_register_dashboard_widget(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }

    wp_add_dashboard_widget('uamswp_fad_gbp_status', 'Business Profile Sync', 'uamswp_fad_gbp_render_dashboard_widget');
}

/**
 * Register the widget.
 */
function uamswp_fad_gbp_register_dashboard_hooks(): void
{
    add_action('wp_dashboard_setup', 'uamswp_fad_gbp_register_dashboard_widget');
}
