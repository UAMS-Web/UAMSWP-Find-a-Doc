<?php

/**
 * Loader for includes/class.fad-acf-blocks.php.
 *
 * The file adds its field groups and one hook at file scope, and the block templates call a helper it declares,
 * so it loads once per process from the first test that asks for it, with the registrations captured. Brain
 * Monkey forgets every stub when a test ends, hence the capture.
 */

declare(strict_types=1);

use PHPUnit\Framework\Assert;

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/acf-functions.php';

/**
 * Load the ACF blocks file once and return what it registered as it loaded.
 *
 * @return array{groups: list<array<string, mixed>>, actions: list<array{0: string, 1: string}>}
 */
function uamswp_test_acf_blocks_file(): array
{
    /** @var array{groups: list<array<string, mixed>>, actions: list<array{0: string, 1: string}>}|null $registered */
    static $registered = null;

    if ($registered !== null) {
        return $registered;
    }

    // The ACF functions file changes what it does when ACF functions exist, so it loads before this one defines any.
    uamswp_test_acf_functions();

    $groups = [];
    $actions = [];

    when('acf_add_local_field_group')->alias(static function (array $group) use (&$groups): void {
        $groups[] = $group;
    });
    when('add_action')->alias(static function (string $hook, string $callback) use (&$actions): void {
        $actions[] = [$hook, $callback];
    });
    when('__')->returnArg();
    when('esc_attr')->returnArg();
    when('esc_url')->returnArg();
    when('esc_html')->returnArg();
    when('esc_html__')->returnArg();

    require_once UAMS_FAD_PATH.'includes/class.fad-acf-blocks.php';

    // Whichever test loads the file first is the one a coverage run credits with the file scope lines.
    Assert::assertSame(require __DIR__.'/acf-blocks-groups.php', $groups, 'The ACF blocks file registers the field groups it was pinned with.');

    $registered = ['groups' => $groups, 'actions' => $actions];

    return $registered;
}
