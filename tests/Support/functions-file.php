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

    Functions\when('add_filter')->alias(static function (string $hook, mixed $callback, int $priority = 10, int $accepted = 1) use (&$hooks): void {
        $hooks[] = ['filter', $hook, $callback, $priority, $accepted];
    });
    Functions\when('add_action')->alias(static function (string $hook, mixed $callback, int $priority = 10, int $accepted = 1) use (&$hooks): void {
        $hooks[] = ['action', $hook, $callback, $priority, $accepted];
    });
    Functions\when('add_shortcode')->alias(static function (string $tag, mixed $callback) use (&$hooks): void {
        $hooks[] = ['shortcode', $tag, $callback, 10, 1];
    });
    Functions\when('register_deactivation_hook')->alias(static function (string $file, mixed $callback) use (&$deactivation): void {
        $deactivation[] = [$file, $callback];
    });

    require_once UAMS_FAD_PATH.'includes/class.fad-functions.php';

    $registered = ['hooks' => $hooks, 'deactivation' => $deactivation];

    return $registered;
}
