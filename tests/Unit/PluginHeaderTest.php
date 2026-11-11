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
