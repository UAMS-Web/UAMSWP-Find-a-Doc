<?php

/**
 * Loader for the page templates under templates/.
 *
 * A template runs at file scope when it is loaded: it registers hooks, removes Genesis ones and declares the
 * functions it uses. It is loaded once per process from the first test that asks for it, with the hook
 * functions stubbed to record their calls, and the calls are kept because Brain Monkey forgets every stub
 * when the test ends. Each template declares functions of its own name, so any number of them can be loaded.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/functions-file.php';
require_once __DIR__.'/wp-classes.php';

/**
 * Load a template once and return the hook calls it made as it loaded.
 *
 * @return array{add_action: list<array<int, mixed>>, add_filter: list<array<int, mixed>>, remove_action: list<array<int, mixed>>, remove_filter: list<array<int, mixed>>, genesis: int}
 */
function uamswp_test_template(string $name): array
{
    /** @var array<string, array{add_action: list<array<int, mixed>>, add_filter: list<array<int, mixed>>, remove_action: list<array<int, mixed>>, remove_filter: list<array<int, mixed>>, genesis: int}> $loaded */
    static $loaded = [];

    if (isset($loaded[$name])) {
        return $loaded[$name];
    }

    // The functions file supplies the location source ids, and the address file the address lines.
    uamswp_test_functions_file();
    require_once UAMS_FAD_PATH.'includes/class.fad-location-address.php';
    require_once UAMS_FAD_PATH.'includes/class.fad-template-helpers.php';

    $calls = ['add_action' => [], 'add_filter' => [], 'remove_action' => [], 'remove_filter' => [], 'genesis' => 0];

    foreach (['add_action', 'add_filter', 'remove_action', 'remove_filter'] as $function) {
        when($function)->alias(static function (mixed ...$arguments) use (&$calls, $function): bool {
            $calls[$function][] = $arguments;

            return true;
        });
    }

    when('genesis')->alias(static function () use (&$calls): void {
        $calls['genesis']++;
    });

    require UAMS_FAD_PATH.'templates/'.$name.'.php';

    $loaded[$name] = $calls;

    return $calls;
}

/**
 * Run a template as WordPress would for a request, and return what it printed.
 *
 * Unlike uamswp_test_template() this runs the file every time it is called, so a test can run one template more
 * than once in the process. The file-scope variables it sets are promoted to globals afterwards, because the
 * callbacks that moved into includes/ read them with `global`.
 */
function uamswp_test_render_template(string $name): string
{
    uamswp_test_functions_file();

    foreach (['add_action', 'add_filter', 'remove_action', 'remove_filter'] as $function) {
        when($function)->justReturn(true);
    }

    when('genesis')->justReturn(null);

    $render = static function () use ($name): string {
        ob_start();

        try {
            require UAMS_FAD_PATH.'templates/'.$name.'.php';
        } finally {
            $output = (string) ob_get_clean();
        }

        foreach (get_defined_vars() as $variable => $value) {
            if (! in_array($variable, ['name', 'output', 'render'], true)) {
                $GLOBALS[$variable] = $value;
            }
        }

        return $output;
    };

    return $render();
}
