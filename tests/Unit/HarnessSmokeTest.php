<?php

/**
 * First unit test pinning harness load for UAMSWP Find-a-Doc.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

test('the harness defines the plugin constants', function (): void {
    expect(defined('UAMS_FAD_PATH'))->toBeTrue();
});

test('Brain Monkey is armed around each test', function (): void {
    when('get_option')->justReturn('stubbed');

    expect(get_option('anything'))->toBe('stubbed');
});
