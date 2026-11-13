<?php

/**
 * Loader for the tests of includes/class.fad-functions.php.
 *
 * The file registers hooks, a class and a deactivation hook at file scope, and Brain Monkey's hook functions
 * exist only inside a test, so the file is loaded from the first test that asks for it. The registrations are
 * read as it loads and kept, because Brain Monkey forgets every hook when the test ends. Two test classes
 * share the one load, so neither depends on running first.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;
use PHPUnit\Framework\Assert;

use function Brain\Monkey\Functions\when;

/**
 * Load the functions file once and return what it registered.
 *
 * @return array{hooks: list<array{0: string, 1: string, 2: mixed, 3: int, 4: int}>, deactivation: list<array{0: string, 1: mixed}>}
 */
function uamswp_test_functions_file(): array
{
    static $registered = null;

    if ($registered !== null) {
        return $registered;
    }

    $hooks = [];
    $deactivation = [];

    when('add_filter')->alias(static function (string $hook, mixed $callback, int $priority = 10, int $accepted = 1) use (&$hooks): void {
        $hooks[] = ['filter', $hook, $callback, $priority, $accepted];
    });
    when('add_action')->alias(static function (string $hook, mixed $callback, int $priority = 10, int $accepted = 1) use (&$hooks): void {
        $hooks[] = ['action', $hook, $callback, $priority, $accepted];
    });
    when('add_shortcode')->alias(static function (string $tag, mixed $callback) use (&$hooks): void {
        $hooks[] = ['shortcode', $tag, $callback, 10, 1];
    });
    when('register_deactivation_hook')->alias(static function (string $file, mixed $callback) use (&$deactivation): void {
        $deactivation[] = [$file, $callback];
    });

    require_once UAMS_FAD_PATH.'includes/class.fad-functions.php';

    $registered = ['hooks' => $hooks, 'deactivation' => $deactivation];

    // The file-scope registrations run only here, in the first test that loads the file, so a mutation run reaches
    // them through that one test. Pinning the whole list at the load makes any dropped or altered one fail it.
    Assert::assertSame(uamswp_test_functions_file_expected_hooks(), array_map(
        static fn (array $hook): string => implode('|', [
            $hook[0],
            $hook[1],
            is_string($hook[2]) ? $hook[2] : (is_array($hook[2]) ? $hook[2][0]::class.'::'.$hook[2][1] : 'closure'),
            $hook[3],
            $hook[4],
        ]),
        $hooks
    ));
    Assert::assertSame([UAMS_FAD_PATH.'uamswp-find-a-doc.php'], array_column($deactivation, 0));

    return $registered;
}

/**
 * The hooks the functions file registers as it loads, in order, as `kind|hook|callback|priority|accepted`.
 *
 * @return list<string>
 */
function uamswp_test_functions_file_expected_hooks(): array
{
    return [
        'filter|asp_results|asp_custom_link_meta_results|1|1',
        'action|admin_enqueue_scripts|uamswp_admin_scripts|10|1',
        'action|acf/input/admin_enqueue_scripts|pubmed_field_on_change::enqueue_script|10|1',
        'action|wp_ajax_load_content_from_pubmed|pubmed_field_on_change::load_content_from_pubmed|10|1',
        'action|wp_enqueue_scripts|fad_script_register|10|1',
        'shortcode|pubmed|uams_pubmed_shortcode|10|1',
        'filter|manage_provider_posts_columns|posts_provider_columns|10|1',
        'action|manage_provider_posts_custom_column|posts_provider_custom_columns|10|2',
        'filter|cron_schedules|uamswp_cron_every_two_hours|10|1',
        'action|init|uamswp_fad_check_and_schedule_cron|10|1',
        'action|uamswp_provider_pg_sync_hook|sync_provider_pg_data|10|1',
        'action|wp_ajax_pg_ajax_api_action|pg_ajax_api|10|1',
        'action|wp_ajax_nopriv_pg_ajax_api_action|pg_ajax_api|10|1',
        'action|admin_init|uamswp_remove_genesis_term_meta|11|1',
        'filter|manage_edit-condition_columns|closure|10|1',
        'filter|manage_edit-treatment_columns|closure|10|1',
        'filter|relevanssi_tax_term_additional_content|rlv_tax_excerpt_term_fields|10|2',
        'filter|relevanssi_pre_excerpt_content|rlv_tax_excerpt_term_fields|10|2',
        'action|wp_enqueue_scripts|uamswp_ajax_scripts|10|1',
        'action|wp_ajax_load_posts_by_ajax|uamswp_load_by_ajax_callback|10|1',
        'action|wp_ajax_nopriv_load_posts_by_ajax|uamswp_load_by_ajax_callback|10|1',
        'shortcode|uamswp_provider_ajax_filter|uamswp_provider_ajax_filter_shortcode|10|1',
        'action|wp_ajax_nopriv_provider_ajax_filter|provider_ajax_filter_callback|10|1',
        'action|wp_ajax_provider_ajax_filter|provider_ajax_filter_callback|10|1',
        'shortcode|uamswp_provider_title_ajax_filter|uamswp_provider_title_ajax_filter_shortcode|10|1',
        'action|wp_ajax_nopriv_provider_title_ajax_filter|provider_title_ajax_filter_callback|10|1',
        'action|wp_ajax_provider_title_ajax_filter|provider_title_ajax_filter_callback|10|1',
        'shortcode|uamswp_location_ajax_filter|uamswp_location_ajax_filter_shortcode|10|1',
        'action|wp_ajax_nopriv_location_ajax_filter|location_ajax_filter_callback|10|1',
        'action|wp_ajax_location_ajax_filter|location_ajax_filter_callback|10|1',
        'action|wp_footer|uamswp_add_trench|10|1',
        'action|wp_ajax_nopriv_schedule_ajax_filter|schedule_ajax_filter_callback|10|1',
        'action|wp_ajax_schedule_ajax_filter|schedule_ajax_filter_callback|10|1',
        'action|wp_enqueue_scripts|uamswp_fad_disable_scripts|100|1',
    ];
}
