<?php

/**
 * Helpers for the tests of includes/class.fad-acf-functions.php.
 *
 * That file registers its hooks at file scope, and Brain Monkey's add_filter() and
 * add_action() exist only inside a test, so the file is loaded from the first test that
 * asks for it. The registrations are read right after the load and kept, because Brain
 * Monkey forgets every hook when the test ends. A function defined by the file is
 * available to every later test in the process.
 */

declare(strict_types=1);

use PHPUnit\Framework\Assert;

use function Brain\Monkey\Functions\when;

/**
 * Load the ACF functions file once and return the hooks it registered.
 *
 * @return array{filters: array<string, array<int, array{0: string, 1: int}>>, actions: array<string, array<int, array{0: string, 1: int}>>, blocks: list<array<string, mixed>>}
 */
function uamswp_test_acf_functions(): array
{
    static $registered = null;

    if ($registered !== null) {
        return $registered;
    }

    // The file builds a directory constant from WP_PLUGIN_DIR as it loads.
    if (! defined('WP_PLUGIN_DIR')) {
        define('WP_PLUGIN_DIR', dirname(UAMS_FAD_PATH));
    }

    // Every registration is captured as the file loads: Brain Monkey forgets them when the test ends.
    $hooks = [];
    when('add_filter')->alias(static function (string $hook, mixed $callback, int $priority = 10, int $args = 1) use (&$hooks): bool {
        $hooks[] = ['filter', $hook, $callback, $priority, $args];

        return true;
    });
    when('add_action')->alias(static function (string $hook, mixed $callback, int $priority = 10, int $args = 1) use (&$hooks): bool {
        $hooks[] = ['action', $hook, $callback, $priority, $args];

        return true;
    });

    // The file registers its two blocks only when ACF's registration function exists, so it exists for the load.
    $blocks = [];
    when('acf_register_block_type')->alias(static function (array $block) use (&$blocks): void {
        $blocks[] = $block;
    });

    require_once UAMS_FAD_PATH.'includes/class.fad-acf-functions.php';

    // Whichever test loads the file first is the one a coverage run credits with the file scope lines.
    Assert::assertSame(require __DIR__.'/acf-functions-hooks.php', $hooks, 'The ACF functions file registers the hooks it was pinned with.');
    Assert::assertSame([
        [
            'name' => 'uamswp_fad_facetwp_cards',
            'title' => 'FacetWP Cards',
            'description' => '',
            'category' => 'common',
            'keywords' => ['provider', 'location', 'facetwp'],
            'acf_block_version' => 3,
            'align' => '',
            'render_template' => '',
            'render_callback' => 'fad_facetwp_cards_callback',
            'enqueue_style' => '',
            'enqueue_script' => '',
            'enqueue_assets' => '',
            'icon' => 'id',
            'supports' => ['align' => ['full'], 'mode' => true, 'multiple' => true],
        ],
        [
            'name' => 'uamswp_fad_facetwp_blocks',
            'title' => 'FacetWP Block',
            'description' => '',
            'category' => 'common',
            'keywords' => ['facetwp', 'shortcode'],
            'acf_block_version' => 3,
            'align' => '',
            'render_template' => '',
            'render_callback' => 'fad_facetwp_blocks_callback',
            'enqueue_style' => '',
            'enqueue_script' => '',
            'enqueue_assets' => '',
            'icon' => 'list-view',
            'supports' => ['align' => true, 'mode' => true, 'multiple' => true],
        ],
    ], $blocks, 'The ACF functions file registers the two blocks it was pinned with.');
    Assert::assertSame(WP_PLUGIN_DIR.'/'.basename(rtrim(UAMS_FAD_PATH, '/\\')).'/assets/json/acf-tables', ACFCDT_JSON_DIR);

    // [hook, callback, priority] for the registrations the tests pin.
    $filters = [
        ['acf/load_value/key=field_physician_languages', 'set_default_language', 20],
        ['acf/update_value/key=field_location_epic_dep_id_item', 'uamswp_location_trim_epic_dep_id', 10],
    ];
    $actions = [
        ['acf/save_post', 'physician_save_post', 5],
    ];

    // The Workday ID fields: no validation or storage hook is registered for them today.
    foreach (['universal_id', 'employee_id', 'contingent_worker_id', 'student_id'] as $name) {
        foreach (['acf/validate_value', 'acf/update_value'] as $prefix) {
            $hook = $prefix.'/key=field_physician_workday_'.$name;
            foreach ([10, 20] as $priority) {
                foreach (['uamswp_workday_id_validate', 'uamswp_workday_id_trim'] as $callback) {
                    $filters[] = [$hook, $callback, $priority];
                }
            }
        }
    }

    $registered = ['filters' => [], 'actions' => [], 'blocks' => $blocks];

    foreach ($filters as [$hook, $callback, $priority]) {
        $registered['filters'][$hook.'|'.$callback.'|'.$priority] = in_array(['filter', $hook, $callback, $priority], array_map(static fn (array $h): array => array_slice($h, 0, 4), $hooks), true);
    }

    foreach ($actions as [$hook, $callback, $priority]) {
        $registered['actions'][$hook.'|'.$callback.'|'.$priority] = in_array(['action', $hook, $callback, $priority], array_map(static fn (array $h): array => array_slice($h, 0, 4), $hooks), true);
    }

    return $registered;
}

/**
 * Run a callback and return the PHP warnings and notices it raised, as "message" strings.
 *
 * Handles each one, so the test runner does not also report it.
 *
 * @return array<int, string>
 */
function uamswp_test_capture_warnings(callable $callback): array
{
    $raised = [];

    set_error_handler(function (int $level, string $message) use (&$raised): bool {
        $raised[] = $message;

        return true;
    });

    try {
        $callback();
    } finally {
        restore_error_handler();
    }

    return $raised;
}
