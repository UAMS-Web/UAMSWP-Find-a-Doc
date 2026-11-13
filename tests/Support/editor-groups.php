<?php

/**
 * Loader for includes/class.fad-editor-groups.php that keeps the hooks the file registers at file scope.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

/**
 * Load the editor groups file once and return the hook registrations it made while loading.
 *
 * The file registers its hooks when it loads, and it loads once per process, so every test that needs the
 * file loads it through here: whichever test loads first, the registrations are kept.
 *
 * @return list<array<int, mixed>> Each entry is the hook function name followed by its arguments.
 */
function uamswp_test_load_editor_groups(): array
{
    /** @var list<array<int, mixed>>|null $registered */
    static $registered = null;

    if ($registered !== null) {
        return $registered;
    }

    $registered = [];

    foreach (['add_action', 'add_filter'] as $function) {
        when($function)->alias(static function (mixed ...$arguments) use (&$registered, $function): bool {
            $registered[] = [$function, ...$arguments];

            return true;
        });
    }

    require_once dirname(__DIR__, 2).'/includes/class.fad-editor-groups.php';

    return $registered;
}
