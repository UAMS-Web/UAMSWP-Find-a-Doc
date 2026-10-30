<?php

/**
 * Business Profile sync: the settings screen.
 *
 * Shows whether the credentials are present (never their values), lets an administrator set
 * each silo to off, dry-run or live, and lists what the sync did recently.
 */

declare(strict_types=1);

/**
 * The names of the credentials the sync reads.
 *
 * @return array<int, string>
 */
function uamswp_fad_gbp_credential_names(): array
{
    return ['GOOGLE_BUSINESS_PROFILE_CLIENT_ID', 'GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET', 'GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN'];
}

/**
 * Reduce a posted set of modes to the known silos and modes. Anything else is dry-run.
 *
 * @param  array<string, mixed>  $posted
 * @return array<string, string>
 */
function uamswp_fad_gbp_sanitize_modes(array $posted): array
{
    $modes = [];

    foreach (array_keys(uamswp_fad_gbp_silos()) as $silo) {
        $mode = $posted[$silo] ?? '';
        $modes[$silo] = is_string($mode) && in_array($mode, UAMSWP_FAD_GBP_Logic::MODES, true) ? $mode : 'dry-run';
    }

    return $modes;
}

/**
 * Save the posted silo modes, then return to the settings screen.
 *
 * @param  bool  $exit  Whether to stop after the redirect. Off only in tests.
 */
function uamswp_fad_gbp_save_modes(bool $exit = true): void
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to change these settings.', 'uamswp-find-a-doc'), '', ['response' => 403]);
    }

    check_admin_referer('uamswp_fad_gbp_modes');

    $posted = isset($_POST['modes']) && is_array($_POST['modes']) ? wp_unslash($_POST['modes']) : [];

    update_option('uamswp_fad_gbp_modes', uamswp_fad_gbp_sanitize_modes($posted), false);

    wp_safe_redirect(add_query_arg('saved', '1', admin_url('admin.php?page=uamswp-fad-gbp')));

    if ($exit) {
        exit;
    }
}

/**
 * Queue a sync for every published provider, then return to the settings screen.
 *
 * @param  bool  $exit  Whether to stop after the redirect. Off only in tests.
 * @param  (callable(string): string)|null  $lookup  Credential lookup; for tests.
 */
function uamswp_fad_gbp_queue_all_action(bool $exit = true, ?callable $lookup = null): void
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to run this sync.', 'uamswp-find-a-doc'), '', ['response' => 403]);
    }

    check_admin_referer('uamswp_fad_gbp_queue_all');

    $queued = uamswp_fad_gbp_queue_all($lookup);

    wp_safe_redirect(add_query_arg('queued', $queued, admin_url('admin.php?page=uamswp-fad-gbp')));

    if ($exit) {
        exit;
    }
}

/**
 * Add the screen under Find-a-Doc Settings.
 */
function uamswp_fad_gbp_add_settings_page(): void
{
    add_submenu_page('fad-settings', 'Business Profile Sync', 'Business Profile Sync', 'manage_options', 'uamswp-fad-gbp', 'uamswp_fad_gbp_render_settings_page', 95);
}

/**
 * Print the screen.
 */
function uamswp_fad_gbp_render_settings_page(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }

    $labels = ['uams' => 'UAMS', 'arkansas-childrens' => "Arkansas Children's", 'baptist-health' => 'Baptist Health'];
    $modes = uamswp_fad_gbp_modes();
    $production = uamswp_fad_gbp_is_production();
    $log = get_option('uamswp_fad_gbp_log', []);
    $log = is_array($log) ? array_slice($log, 0, 100) : [];
    ?>
    <div class="wrap">
        <h1>Business Profile Sync</h1>
        <?php if (isset($_GET['saved'])) { ?>
            <div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>
        <?php } ?>
        <?php if (isset($_GET['queued'])) { ?>
            <div class="notice notice-success is-dismissible"><p><?php echo esc_html(sprintf('Queued a sync for %d providers.', (int) $_GET['queued'])); ?></p></div>
        <?php } ?>
        <p>Updates Google Business Profile listings for providers when a provider or one of their locations is saved, and creates a listing when none exists. Each silo is a set of listings under one account group.</p>

        <h2>Credentials</h2>
        <p>Set in <code>wp-config.php</code> as constants, or as environment variables. Values are never shown or stored in the database.</p>
        <table class="widefat striped" style="max-width:40rem">
            <tbody>
            <?php foreach (uamswp_fad_gbp_credential_names() as $name) { ?>
                <tr>
                    <td><code><?php echo esc_html($name); ?></code></td>
                    <td><?php echo (defined($name) && constant($name) !== '') || getenv($name) ? 'Set' : '<strong>Missing</strong>'; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <h2>Silos</h2>
        <p>
            This site is <strong><?php echo $production ? 'production' : 'not production'; ?></strong>.
            <?php if (! $production) { ?>
                A silo set to Live runs as a dry run here.
            <?php } ?>
            A dry run logs what would change and writes nothing.
        </p>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="uamswp_fad_gbp_save_modes">
            <?php wp_nonce_field('uamswp_fad_gbp_modes'); ?>
            <table class="form-table" role="presentation">
                <?php foreach ($modes as $silo => $mode) { ?>
                    <tr>
                        <th scope="row"><label for="gbp-mode-<?php echo esc_attr($silo); ?>"><?php echo esc_html($labels[$silo] ?? $silo); ?></label></th>
                        <td>
                            <select id="gbp-mode-<?php echo esc_attr($silo); ?>" name="modes[<?php echo esc_attr($silo); ?>]">
                                <?php foreach (['off' => 'Off', 'dry-run' => 'Dry run', 'live' => 'Live'] as $value => $text) { ?>
                                    <option value="<?php echo esc_attr($value); ?>" <?php selected($mode, $value); ?>><?php echo esc_html($text); ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <?php submit_button('Save silo modes'); ?>
        </form>

        <h2>Sync every provider</h2>
        <p>Queues a sync for every published provider, one every 15 seconds, using each silo's mode above. Use it for a silo's first run and after an import, which does not trigger a sync on its own. A provider already waiting in the queue is not queued again. Results appear under Recent activity as each job runs.</p>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="uamswp_fad_gbp_queue_all">
            <?php wp_nonce_field('uamswp_fad_gbp_queue_all'); ?>
            <?php submit_button('Queue a sync for all providers', 'secondary'); ?>
        </form>

        <h2>Recent activity</h2>
        <?php if ($log === []) { ?>
            <p>Nothing logged yet.</p>
        <?php } else { ?>
            <table class="widefat striped">
                <thead><tr><th>Time</th><th>Store code</th><th>Silo</th><th>Mode</th><th>Action</th><th>Detail</th></tr></thead>
                <tbody>
                <?php foreach ($log as $entry) { ?>
                    <tr>
                        <td><?php echo esc_html(wp_date('Y-m-d H:i', (int) ($entry['time'] ?? 0))); ?></td>
                        <td><code><?php echo esc_html((string) ($entry['store_code'] ?? '')); ?></code></td>
                        <td><?php echo esc_html((string) ($entry['silo'] ?? '')); ?></td>
                        <td><?php echo esc_html((string) ($entry['mode'] ?? '')); ?></td>
                        <td><?php echo esc_html((string) ($entry['action'] ?? '')); ?></td>
                        <td>
                            <?php echo esc_html((string) ($entry['reason'] ?? '')); ?>
                            <?php if (! empty($entry['fields'])) { ?>
                                <br><small><?php echo esc_html(implode(', ', (array) $entry['fields'])); ?></small>
                            <?php } ?>
                            <?php if (! empty($entry['unresolved_categories'])) { ?>
                                <br><small>No Google category for: <?php echo esc_html(implode(', ', (array) $entry['unresolved_categories'])); ?></small>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
    <?php
}

/**
 * Register the screen and its save handler.
 */
function uamswp_fad_gbp_register_settings_hooks(): void
{
    add_action('admin_menu', 'uamswp_fad_gbp_add_settings_page', 110);
    add_action('admin_post_uamswp_fad_gbp_save_modes', 'uamswp_fad_gbp_save_modes');
    add_action('admin_post_uamswp_fad_gbp_queue_all', 'uamswp_fad_gbp_queue_all_action');
}
