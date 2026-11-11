<?php

declare(strict_types=1);
/**
 * Admin notice: the ACF Custom Database Tables cache disagrees with the table definition files.
 *
 * A deploy, a rollback or a partial copy can leave `.cache/_table_map.php` out of step with the
 * `table_*.json` files beside it, and the plugin reads only the cache. No author-time check can
 * see that, so the same comparison the command line check runs is repeated here, on the server,
 * and a notice names the screen that fixes it.
 *
 * Scope: the cache against the table JSON only. The notice does not compare the ACF field groups
 * with the table JSON, and says so, so a missing notice is not read as proof that the tables are
 * complete.
 */
if (! defined('WPINC')) {
    exit;
}

require_once __DIR__.'/class.fad-acf-table-map-check.php';

/**
 * The directory that holds the table definition files and the `.cache` folder.
 *
 * Follows the directory the custom tables plugin is told to read, so the notice checks the files
 * the plugin actually loads.
 */
function uamswp_fad_acf_table_map_dir(): string
{
    return defined('ACFCDT_JSON_DIR') ? ACFCDT_JSON_DIR : UAMS_FAD_PATH.'assets/json/acf-tables';
}

/**
 * Print the notice when the cache and the table definition files disagree.
 *
 * Shown only to users who can open the Database Tables screen (the same `manage_options`
 * capability that screen requires), so nobody sees a notice they cannot act on. An unreadable
 * cache is reported too: it is the same failure with a different cause.
 */
function uamswp_fad_acf_table_map_notice(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }

    $check = uamswp_fad_acf_table_map_check(uamswp_fad_acf_table_map_dir());

    if ($check['readable'] && $check['problems'] === []) {
        return;
    }

    $screen = admin_url('edit.php?post_type=acf-field-group&page=acf-custom-database-tables');
    $shown = array_slice($check['problems'], 0, 5);

    echo '<div class="notice notice-error"><p><strong>'.esc_html__('The ACF custom table map is out of date.', 'uamswp-find-a-doc').'</strong> ';
    echo esc_html__('The plugin reads a cached copy of the table definitions, and the cache no longer matches them. Open', 'uamswp-find-a-doc');
    echo ' <a href="'.esc_url($screen).'">'.esc_html__('Custom Fields > Database Tables', 'uamswp-find-a-doc').'</a> ';
    echo esc_html__('and run Create/Update Tables.', 'uamswp-find-a-doc').'</p><ul>';

    foreach ($shown as $problem) {
        echo '<li>'.esc_html($problem).'</li>';
    }

    if (count($check['problems']) > count($shown)) {
        echo '<li>'.esc_html(sprintf('and %d more', count($check['problems']) - count($shown))).'</li>';
    }

    echo '</ul><p><em>'.esc_html__('This check compares the cache with the table definition files only. It does not compare the ACF field groups with the table definitions.', 'uamswp-find-a-doc').'</em></p></div>';
}

/**
 * Register the notice.
 */
function uamswp_fad_acf_table_map_register(): void
{
    add_action('admin_notices', 'uamswp_fad_acf_table_map_notice');
}

uamswp_fad_acf_table_map_register();
