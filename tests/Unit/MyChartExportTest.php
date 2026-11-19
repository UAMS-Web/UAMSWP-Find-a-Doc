<?php

/**
 * The rows the MyChart export in includes/class.fad-gmb-settings-page.php writes, pinned as the code builds them
 * today.
 *
 * Every published provider who is not a resident is written with the SER ID, the name, the tagged profile
 * address and the photo. A resident adds no row.
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

/** The header row the export writes. */
const MYCHART_HEAD = ['SER ID', 'Provider Name', 'Provider Profile URL', 'Provider Photo URL'];

/**
 * Run the MyChart export over providers, and return the CSV rows it wrote, header first.
 *
 * @param  array<int, array{slug: string, title: string, pid?: mixed, resident?: mixed, thumb?: int}>  $providers
 * @return list<list<string|null>>
 */
function mychart_run(array $providers): array
{
    $posts = [];
    $fields = [];
    $thumbs = [];
    foreach ($providers as $id => $provider) {
        $posts[$id] = ['slug' => $provider['slug'], 'title' => $provider['title']];
        $fields['physician_pid|'.$id] = $provider['pid'] ?? null;
        $fields['physician_resident|'.$id] = $provider['resident'] ?? false;
        $thumbs[$id] = $provider['thumb'] ?? 0;
    }

    uamswp_test_gmb_page();
    gmb_world(['posts' => $posts, 'fields' => $fields, 'thumbs' => $thumbs]);
    when('get_the_permalink')->alias(static fn (int $id): string => 'https://example.test/provider/'.$posts[$id]['slug'].'/');

    $run = gmb_export_run('mychart_csv_export', 'valid-download_mychart_csv', array_keys($providers));
    expect($run['exit'])->toBe('')
        ->and(substr($run['output'], 0, 3))->toBe(chr(0xEF).chr(0xBB).chr(0xBF));

    // Each row ends in a newline, and a row with no cells is an empty line.
    $rows = [];
    foreach (explode("\n", substr($run['output'], 3, -1)) as $line) {
        $rows[] = str_getcsv($line, ',', '"', '\\');
    }

    return $rows;
}

test('an export by someone who cannot manage options, or outside WP-Admin, builds and writes nothing', function (bool $can, bool $admin): void {
    uamswp_test_gmb_page();
    when('current_user_can')->alias(static fn (string $capability): bool => $capability === 'manage_options' && $can);
    when('is_admin')->justReturn($admin);
    when('wp_verify_nonce')->justReturn(false);

    WP_Query::$queries = [];

    ob_start();
    $result = mychart_csv_export();
    $output = ob_get_clean();

    expect($result)->toBeFalse()
        ->and($output)->toBe('')
        ->and(WP_Query::$queries)->toBe([]);
})->with([
    'not an administrator' => [false, true],
    'not in WP-Admin' => [true, false],
]);

test('the export queries every published provider by title, and sends the download headers', function (): void {
    $run = gmb_export_run('mychart_csv_export', 'valid-download_mychart_csv', []);

    expect(WP_Query::$queries)->toBe([[
        'post_type' => 'provider',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'orderby' => 'title',
        'order' => 'ASC',
    ]])
        ->and($run['output'])->toBe(chr(0xEF).chr(0xBB).chr(0xBF)."\n")
        ->and($run['headers'])->toHaveCount(6)
        ->and(array_slice($run['headers'], 0, 3))->toBe([
            'Cache-Control: must-revalidate, post-check=0, pre-check=0',
            'Content-Description: File Transfer',
            'Content-type: text/csv',
        ])
        ->and($run['headers'][3])->toMatch('/^Content-Disposition: attachment; filename=MyChart_List_\d+\.csv$/')
        ->and(array_slice($run['headers'], 4))->toBe(['Pragma: no-cache', 'Expires: 0']);
});

test('a provider is written with the SER ID, the decoded name, the tagged profile address and the thumbnail', function (): void {
    $id = gmb_next_id();

    expect(mychart_run([$id => ['slug' => 'zoe-lee', 'title' => 'Zo&euml; Lee', 'pid' => '4567', 'thumb' => 88]]))->toBe([
        MYCHART_HEAD,
        ['4567', 'Zoë Lee', 'https://example.test/provider/zoe-lee/?utm_source=mychart&utm_medium=link&utm_campaign=clinical_service&utm_term=provider&utm_content=zoe-lee&utm_specs=4567', 'https://img.test/88-thumbnail.jpg'],
    ]);
});

test('a SER ID of zero, or none, is written empty', function (mixed $pid): void {
    $id = gmb_next_id();

    $row = mychart_run([$id => ['slug' => 'ann-lee', 'title' => 'Ann Lee', 'pid' => $pid]])[1];

    expect($row[0])->toBe('')
        ->and($row[2])->toEndWith('&utm_content=ann-lee&utm_specs=');
})->with(['zero' => [0], 'the string zero' => ['0'], 'none' => [null]]);

test('a resident adds no row, alone or between two providers', function (): void {
    $first = gmb_next_id();
    $resident = gmb_next_id();
    $third = gmb_next_id();
    $alone = gmb_next_id();

    $rows = mychart_run([
        $first => ['slug' => 'ann-lee', 'title' => 'Ann Lee', 'pid' => '1'],
        $resident => ['slug' => 'bo-ray', 'title' => 'Bo Ray', 'pid' => '2', 'resident' => true],
        $third => ['slug' => 'cy-jo', 'title' => 'Cy Jo', 'pid' => '3'],
    ]);

    expect(array_map(static fn (array $row): array => [$row[0], $row[1]], array_slice($rows, 1)))->toBe([['1', 'Ann Lee'], ['3', 'Cy Jo']])
        ->and(mychart_run([$alone => ['slug' => 'di-po', 'title' => 'Di Po', 'resident' => true]]))->toBe([MYCHART_HEAD]);
});

test('a cell that would start a spreadsheet formula is written as text', function (string $title): void {
    $id = gmb_next_id();

    expect(mychart_run([$id => ['slug' => 'x', 'title' => $title, 'pid' => '9']])[1][1])->toBe("'".$title);
})->with(['=1+1', '+1', '-1', '@SUM(A1)', "\tTab", "\rReturn"]);

test('a cell that only contains a formula character later is written as it is', function (): void {
    $id = gmb_next_id();

    expect(mychart_run([$id => ['slug' => 'x', 'title' => 'Ann=Lee', 'pid' => '9']])[1][1])->toBe('Ann=Lee');
});
