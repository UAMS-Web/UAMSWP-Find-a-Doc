<?php

/**
 * Loader for includes/class.fad-editor-groups.php that checks the hooks the file registers at file scope.
 */

declare(strict_types=1);

use PHPUnit\Framework\Assert;

use function Brain\Monkey\Functions\when;

/**
 * The hook registrations the editor groups file makes when it loads, in file order.
 *
 * @return list<array<int, mixed>> Each entry is the hook function name followed by its arguments.
 */
function uamswp_test_editor_group_hooks(): array
{
    return [
        ['add_action', 'init', 'uamswp_fad_register_editor_group_taxonomy', 5],
        ['add_action', 'acf/init', 'uamswp_fad_register_editor_group_fields'],
        ['add_filter', 'map_meta_cap', 'uamswp_fad_editor_group_map_meta_cap', 10, 4],
        ['add_action', 'pre_get_posts', 'uamswp_fad_editor_group_admin_list_scope'],
        ['add_filter', 'posts_where', 'uamswp_fad_editor_group_admin_list_where', 10, 2],
        ['add_action', 'save_post', 'uamswp_fad_inherit_editor_group_on_save', 20, 2],
        ['add_action', 'editor_group_pre_add_form', 'uamswp_fad_editor_group_sync_notice'],
        ['add_action', 'admin_post_uamswp_fad_sync_editor_groups', 'uamswp_fad_sync_editor_groups'],
    ];
}

/**
 * Load the editor groups file once and return the hook registrations it made while loading.
 *
 * The file registers its hooks when it loads, and it loads once per process, so every test that needs the
 * file loads it through here. Whichever test loads it first is the one a coverage run credits with the file
 * scope lines, so the registrations are asserted here, at load, and not in a test that may run later.
 *
 * @return list<array<int, mixed>>
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

    Assert::assertSame(uamswp_test_editor_group_hooks(), $registered, 'The editor groups file registers its hooks as expected.');

    return $registered;
}
