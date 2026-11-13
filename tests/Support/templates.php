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
 *
 * @param  array<string, mixed>  $variables  Variables WordPress would have in scope for the template, such as `$post`.
 */
function uamswp_test_render_template(string $name, array $variables = []): string
{
    uamswp_test_functions_file();

    // The calls the template makes to the hook functions, read afterwards with uamswp_test_render_hooks().
    $GLOBALS['uamswp_test_render_hooks'] = [];

    foreach (['add_action', 'add_filter', 'remove_action', 'remove_filter'] as $function) {
        when($function)->alias(static function (mixed ...$arguments) use ($function): bool {
            $GLOBALS['uamswp_test_render_hooks'][$function][] = $arguments;

            return true;
        });
    }

    // How many times the template handed the page to Genesis.
    $GLOBALS['uamswp_test_genesis_calls'] = 0;
    when('genesis')->alias(static function (): void {
        $GLOBALS['uamswp_test_genesis_calls']++;
    });

    $render = static function () use ($name, $variables): string {
        extract($variables, EXTR_SKIP);
        ob_start();

        try {
            require UAMS_FAD_PATH.'templates/'.$name.'.php';
        } finally {
            $output = (string) ob_get_clean();
        }

        foreach (get_defined_vars() as $variable => $value) {
            if (! in_array($variable, ['name', 'output', 'render', 'variables'], true)) {
                $GLOBALS[$variable] = $value;
            }
        }

        return $output;
    };

    return $render();
}

/**
 * The hook calls the last uamswp_test_render_template() run made, by function name.
 *
 * @return array<string, list<array<int, mixed>>>
 */
function uamswp_test_render_hooks(): array
{
    return $GLOBALS['uamswp_test_render_hooks'] ?? [];
}

/**
 * Include a partial under templates/ with the given variables in scope, and return what it printed.
 *
 * The blocks, loops and fwp partials are not page templates: they are included by one, and read the variables
 * that template set. Each call runs the file again. A variable passed as a reference in $variables stays one, so a test
 * can read what the partial did to it.
 *
 * @param  string  $path  The path under templates/, without the extension (for example `loops/expertise-card`).
 * @param  array<string, mixed>  $variables  The variables the including template would have set.
 */
function uamswp_test_include_partial(string $path, array $variables = []): string
{
    $render = static function () use ($path, $variables): string {
        extract($variables, EXTR_SKIP | EXTR_REFS);
        ob_start();

        try {
            include UAMS_FAD_PATH.'templates/'.$path.'.php';
        } finally {
            $output = (string) ob_get_clean();
        }

        return $output;
    };

    return $render();
}

/**
 * Stub the main loop (have_posts(), the_post() and get_the_ID()) over the given post ids.
 *
 * @param  list<int>  $ids
 */
function uamswp_test_main_loop(array $ids): void
{
    $position = new stdClass;
    $position->index = 0;
    $position->current = 0;

    when('have_posts')->alias(static fn (): bool => $position->index < count($ids));
    when('the_post')->alias(static function () use ($position, $ids): void {
        $position->current = $ids[$position->index];
        $position->index++;
    });
    when('get_the_ID')->alias(static fn (): int => $position->current);
}
