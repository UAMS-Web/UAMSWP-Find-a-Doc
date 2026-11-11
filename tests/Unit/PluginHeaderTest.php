<?php

/**
 * Pins the plugin header that WordPress and the update tooling read, and the places that
 * repeat its version (UAMS-Web/UAMSWP-Find-a-Doc#1168).
 */

declare(strict_types=1);

/**
 * Read one field from the plugin header comment.
 */
function uamswp_test_plugin_header_field(string $field): string
{
    $source = (string) file_get_contents(dirname(__DIR__, 2).'/uamswp-find-a-doc.php');

    preg_match('/^'.preg_quote($field, '/').':[ \t]*(.*?)\s*$/m', $source, $match);

    return $match[1] ?? '';
}

test('the plugin URI is the repository URL', function (): void {
    expect(uamswp_test_plugin_header_field('Plugin URI'))->toBe('https://github.com/UAMS-Web/UAMSWP-Find-a-Doc');
});

test('the header version is a release version', function (): void {
    expect(uamswp_test_plugin_header_field('Version'))->toMatch('/^\d+\.\d+\.\d+$/');
});

test('package.json and its lockfile name the header version', function (): void {
    $root = dirname(__DIR__, 2);
    $header = uamswp_test_plugin_header_field('Version');
    $package = json_decode((string) file_get_contents($root.'/package.json'), true, 512, JSON_THROW_ON_ERROR);
    $lock = json_decode((string) file_get_contents($root.'/package-lock.json'), true, 512, JSON_THROW_ON_ERROR);

    expect($package['version'])->toBe($header)
        ->and($lock['version'])->toBe($header)
        ->and($lock['packages']['']['version'])->toBe($header);
});

test('the header credits UAMS Web Services, not individual developers', function (): void {
    expect(uamswp_test_plugin_header_field('Author'))->toBe('UAMS Web Services')
        ->and(uamswp_test_plugin_header_field('Author URI'))->toBe('https://communications.uams.edu/web/');
});

test('the README credits UAMS Web Services and lists the developers by surname', function (): void {
    $readme = (string) file_get_contents(dirname(__DIR__, 2).'/README.md');

    expect($readme)->toContain("## Credits\n\nDeveloped and maintained by UAMS Web Services.\n\nDevelopers:\n\n");

    $section = substr($readme, (int) strpos($readme, "## Credits\n"));
    preg_match_all('/^- (.+)$/m', $section, $match);
    $names = $match[1];

    $surnames = array_map(static fn (string $name): string => (string) substr(strrchr(' '.$name, ' '), 1), $names);
    $sorted = $surnames;
    sort($sorted);

    expect($names)->toContain('Todd McKee')
        ->and($names)->not->toBeEmpty()
        ->and($surnames)->toBe($sorted);
});
