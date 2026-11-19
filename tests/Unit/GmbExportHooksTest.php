<?php

/**
 * The hooks includes/class.fad-gmb-settings-page.php adds: the export page on `admin_menu`, and an export on
 * `admin_init` only when the request's `action` names it. Also how each export ends the request: with the
 * security message on a bad nonce, and with nothing more once the CSV is written.
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
    WP_Query::$queue = [];
    WP_Query::$queries = [];
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

test('an export with a bad nonce ends the request with the security message before building anything', function (string $export): void {
    $run = gmb_export_run($export, 'forged');

    expect($run)->toBe(['exit' => 'Security check error', 'output' => '', 'headers' => []])
        ->and(WP_Query::$queries)->toBe([]);
})->with(['Doximity' => ['doximity_csv_export'], 'MyChart' => ['mychart_csv_export']]);

test('the Doximity export ends the request with no message once the CSV is written', function (): void {
    $run = gmb_export_run('doximity_csv_export', 'valid-download_doximity_csv');

    expect($run['exit'])->toBe('')
        ->and($run['output'])->toBe(chr(0xEF).chr(0xBB).chr(0xBF)."\n")
        ->and($run['headers'])->toContain('Content-type: text/csv')
        ->and(WP_Query::$queries)->toHaveCount(1);
});

test('the MyChart export ends the request with no message once the CSV is written', function (): void {
    $provider = gmb_next_id();
    gmb_world([
        'posts' => [$provider => ['slug' => 'ann-lee', 'title' => 'Ann Lee']],
        'fields' => ['physician_pid|'.$provider => '123', 'physician_resident|'.$provider => false],
    ]);
    when('get_the_permalink')->justReturn('https://example.test/provider/ann-lee/');

    $run = gmb_export_run('mychart_csv_export', 'valid-download_mychart_csv', [$provider]);

    expect($run['exit'])->toBe('')
        ->and($run['output'])->toBe(chr(0xEF).chr(0xBB).chr(0xBF)
            ."\"SER ID\",\"Provider Name\",\"Provider Profile URL\",\"Provider Photo URL\"\n"
            ."123,\"Ann Lee\",https://example.test/provider/ann-lee/?utm_source=mychart&utm_medium=link&utm_campaign=clinical_service&utm_term=provider&utm_content=ann-lee&utm_specs=123,\n")
        ->and($run['headers'])->toContain('Content-type: text/csv');
});
