<?php

/**
 * How the dead link cleanup screen (includes/class.fad-dead-link-cleanup.php) ends a request: every handler
 * redirects and then ends the request through `uamswp_fad_exit()`.
 *
 * The scan, removal and undo themselves read and write the database, and are tested in the Integration suite.
 * The file registers its hooks as it loads, so it is loaded once per process from the first test that asks for
 * it, and the registrations are asserted there.
 */

declare(strict_types=1);

use UamswpFindADoc\Tests\Support\ExitCalled;

use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    $this->get_backup = $_GET;
});

afterEach(function (): void {
    $_GET = $this->get_backup;
});

/**
 * Load the dead link cleanup file once, and return the hooks it registered as it loaded.
 *
 * @return list<array<int, mixed>>
 */
function dead_links_load(): array
{
    /** @var list<array<int, mixed>>|null $registered */
    static $registered = null;

    if ($registered !== null) {
        return $registered;
    }

    $registered = [];
    when('add_action')->alias(static function (mixed ...$arguments) use (&$registered): bool {
        $registered[] = $arguments;

        return true;
    });

    require_once UAMS_FAD_PATH.'includes/class.fad-dead-link-cleanup.php';

    return $registered;
}

/**
 * Stub the WordPress calls a handler makes, and record each redirect it sends.
 *
 * @param  array<string, mixed>|null  $run  The stored run, or null for none.
 * @return ArrayObject<int, string> The addresses redirected to, in order.
 */
function dead_links_world(?array $run = null): ArrayObject
{
    /** @var ArrayObject<int, string> $redirects */
    $redirects = new ArrayObject;

    when('current_user_can')->justReturn(true);
    when('check_admin_referer')->justReturn(1);
    when('admin_url')->alias(static fn (string $path = ''): string => 'https://example.test/wp-admin/'.$path);
    when('add_query_arg')->alias(static function (string|array $key, mixed ...$rest): string {
        if (is_array($key)) {
            return $rest[0].(str_contains((string) $rest[0], '?') ? '&' : '?').http_build_query($key);
        }

        return $rest[1].(str_contains((string) $rest[1], '?') ? '&' : '?').$key.'='.$rest[0];
    });
    when('wp_nonce_url')->alias(static fn (string $url, string $action): string => $url.'&_wpnonce='.$action);
    when('wp_safe_redirect')->alias(static function (string $location) use ($redirects): bool {
        $redirects[] = $location;

        return true;
    });
    when('get_option')->alias(static fn (string $name, mixed $default = false): mixed => $name === UAMSWP_DEAD_LINKS_RUN_OPTION ? $run : $default);
    when('add_option')->justReturn(true);
    when('delete_option')->justReturn(true);
    // The steps check that ACF is active.
    when('get_field')->justReturn();
    when('update_field')->justReturn(true);

    return $redirects;
}

/**
 * A stored run with the given status.
 *
 * @return array<string, mixed>
 */
function dead_links_stored_run(string $status): array
{
    return ['status' => $status, 'cursor' => 0, 'scanned_at' => 1700000000, 'items' => [], 'counts' => ['rows' => []]];
}

test('loading the file hooks the screen on admin_menu at priority 106 and the three steps on admin_post', function (): void {
    expect(dead_links_load())->toBe([
        ['admin_menu', 'uamswp_dead_links_menu', 106],
        ['admin_post_uamswp_dead_links_scan', 'uamswp_dead_links_handle_scan'],
        ['admin_post_uamswp_dead_links_apply', 'uamswp_dead_links_handle_apply'],
        ['admin_post_uamswp_dead_links_undo', 'uamswp_dead_links_handle_undo'],
    ]);
});

test('going back to the screen redirects there with the notice and ends the request', function (string $notice, string $expected): void {
    dead_links_load();
    $redirects = dead_links_world();

    expect(fn () => uamswp_dead_links_back($notice))->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe([$expected]);
})->with([
    'a notice' => ['stale', 'https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice=stale'],
    'no notice' => ['', 'https://example.test/wp-admin/admin.php?page=uamswp-dead-links'],
]);

test('a step that leaves the run unfinished redirects to the next step and ends the request', function (string $action, string $from, string $status): void {
    dead_links_load();
    $redirects = dead_links_world(dead_links_stored_run($from));
    $_GET['run'] = '1700000000';

    expect(fn () => uamswp_dead_links_run_step($action, [$from], static fn (array $run): array => ['status' => $status] + $run, 'done'))->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin-post.php?action='.$action.'&run=1700000000&_wpnonce='.$action]);
})->with([
    'a removal' => ['uamswp_dead_links_apply', 'scanned', 'applying'],
    'an undo' => ['uamswp_dead_links_undo', 'applied', 'undoing'],
]);

test('a step that finishes or fails the run goes back to the screen instead of the next step', function (string $status, string $notice): void {
    dead_links_load();
    $redirects = dead_links_world(dead_links_stored_run('scanned'));
    $_GET['run'] = '1700000000';

    expect(fn () => uamswp_dead_links_run_step('uamswp_dead_links_apply', ['scanned'], static fn (array $run): array => ['status' => $status] + $run, 'applied'))->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice='.$notice]);
})->with([
    'finished' => ['applied', 'applied'],
    'failed' => ['failed', 'failed'],
    'any other status' => ['scanned', 'applied'],
]);
