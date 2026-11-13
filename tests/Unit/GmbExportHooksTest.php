<?php

/**
 * The hooks includes/class.fad-gmb-settings-page.php adds: the export page on `admin_menu`, and an export on
 * `admin_init` only when the request's `action` names it.
 *
 * The file loads once per process. The first test here loads it from its own body, so the mutation run
 * credits the call the file makes as it loads to that test. Name this file first when mutating the GMB file.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/gmb-page.php';

beforeEach(function (): void {
    $this->get_backup = $_GET;
});

afterEach(function (): void {
    $_GET = $this->get_backup;
});

/**
 * Register the export hooks for a request, and return what was hooked.
 *
 * @param  array<string, mixed>  $get
 * @return list<array{0: string, 1: string, 2: int}>
 */
function gmb_export_hooks(array $get): array
{
    $actions = [];
    when('add_action')->alias(static function (string $hook, string $callback, int $priority = 10) use (&$actions): void {
        $actions[] = [$hook, $callback, $priority];
    });
    $_GET = $get;

    uamswp_fad_gmb_register_export_hooks();

    return $actions;
}

test('loading the file hooks the export page on admin_menu at priority 105, and no export', function (): void {
    $_GET = [];

    uamswp_test_gmb_page();

    expect($GLOBALS['uamswp_test_gmb_page_actions'] ?? null)->toBe([['admin_menu', 'uamswp_add_gmb_export_page', 105]]);
});

test('a request with no action hooks only the export page', function (): void {
    uamswp_test_gmb_page();

    expect(gmb_export_hooks([]))->toBe([['admin_menu', 'uamswp_add_gmb_export_page', 105]]);
});

test('a request for an export also hooks that export on admin_init', function (string $action, string $export): void {
    uamswp_test_gmb_page();

    expect(gmb_export_hooks(['action' => $action]))->toBe([
        ['admin_menu', 'uamswp_add_gmb_export_page', 105],
        ['admin_init', $export, 10],
    ]);
})->with([
    'Doximity' => ['download_doximity_csv', 'doximity_csv_export'],
    'MyChart' => ['download_mychart_csv', 'mychart_csv_export'],
]);

test('any other action hooks no export', function (mixed $action): void {
    uamswp_test_gmb_page();

    expect(gmb_export_hooks(['action' => $action]))->toBe([['admin_menu', 'uamswp_add_gmb_export_page', 105]]);
})->with([
    'another action' => ['download_other_csv'],
    'an empty action' => [''],
    'an array' => [['download_doximity_csv']],
]);
