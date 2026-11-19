<?php

/**
 * The CSV Export page in includes/class.fad-gmb-settings-page.php: its submenu entry, and the two export links
 * it prints, each carrying its own action and nonce.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/gmb-page.php';

test('the export page is added under the Find-a-Doc settings for those who can manage options', function (): void {
    uamswp_test_gmb_page();
    $added = [];
    when('add_submenu_page')->alias(static function (mixed ...$args) use (&$added): string {
        $added[] = $args;

        return 'hook';
    });

    uamswp_add_gmb_export_page();

    expect($added)->toBe([['fad-settings', 'UAMSWP CSV Export', 'CSV Export', 'manage_options', 'uamswp-gmb-export', 'uamswp_fad_gmb_export_page', 90]]);
});

test('the export page prints its title and a link to each export with that export\'s nonce', function (): void {
    uamswp_test_gmb_page();
    $title = $GLOBALS['title'] ?? null;
    $GLOBALS['title'] = 'UAMSWP CSV Export';
    when('admin_url')->alias(static fn (string $path): string => 'https://example.test/wp-admin/'.$path);
    when('wp_create_nonce')->alias(static fn (string $action): string => 'nonce-'.$action);
    when('_e')->alias(static function (string $text): void {
        echo $text;
    });

    ob_start();
    try {
        uamswp_fad_gmb_export_page();
    } finally {
        $html = (string) ob_get_clean();
        $GLOBALS['title'] = $title;
    }

    expect($html)->toContain('<h1>UAMSWP CSV Export</h1>')
        ->and($html)->toContain('href="https://example.test/wp-admin/admin.php?page=uamswp-gmb-export&action=download_doximity_csv&_wpnonce=nonce-download_doximity_csv" class="page-title-action">Export to CSV</a>')
        ->and($html)->toContain('href="https://example.test/wp-admin/admin.php?page=uamswp-gmb-export&action=download_mychart_csv&_wpnonce=nonce-download_mychart_csv" class="page-title-action">Export to CSV</a>');
});
