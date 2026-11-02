<?php

/**
 * Pins how a new provider gets its default language today, before the pending change to it.
 *
 * The default has two parts: the ACF field's own default_value in the field group JSON,
 * and the set_default_language() filter on the loaded value. Both are pinned as they are,
 * including the parts that look wrong (the filter never reads the post, and a missing
 * 'english' term raises a warning and returns an array holding null).
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

require_once __DIR__.'/../Support/acf-functions.php';

test('it is hooked to the provider languages load_value filter at priority 20 with three arguments', function (): void {
    expect(uamswp_test_acf_functions()['filters']['acf/load_value/key=field_physician_languages|set_default_language|20'])->toBeTrue();
});

test('it returns a stored value untouched, whatever the post', function (mixed $stored): void {
    uamswp_test_acf_functions();
    Functions\expect('get_term_by')->never();
    Functions\expect('get_post_status')->never();

    expect(set_default_language($stored, 123, ['key' => 'field_physician_languages']))->toBe($stored);
})->with([
    'a list of term IDs' => [[5, 7]],
    'an empty list' => [[]],
    'an empty string' => [''],
    'zero' => [0],
    'false' => [false],
]);

test('it returns the English term ID in a list when the value is null', function (): void {
    uamswp_test_acf_functions();
    Functions\expect('get_term_by')->once()->with('slug', 'english', 'language')->andReturn((object) ['term_id' => 42]);

    expect(set_default_language(null, 123, []))->toBe([42]);
});

test('it does not look at the post when it seeds the default', function (): void {
    uamswp_test_acf_functions();
    Functions\when('get_term_by')->justReturn((object) ['term_id' => 42]);
    Functions\expect('get_post_status')->never();
    Functions\expect('get_post')->never();

    expect(set_default_language(null, 0, []))->toBe([42])
        ->and(set_default_language(null, 'not-a-post', []))->toBe([42]);
});

test('it raises a warning and returns a list holding null when there is no english term', function (): void {
    uamswp_test_acf_functions();
    Functions\when('get_term_by')->justReturn(false);

    $result = null;
    $warnings = uamswp_test_capture_warnings(function () use (&$result): void {
        $result = set_default_language(null, 123, []);
    });

    expect($warnings)->toBe(['Attempt to read property "term_id" on false'])
        ->and($result)->toBe([null]);
});

test('the field group sets the default value to the term name English', function (): void {
    $group = json_decode((string) file_get_contents(UAMS_FAD_PATH.'assets/json/acf-json/group_uamswp_physicians.json'), true);
    $field = null;

    foreach ($group['fields'] as $candidate) {
        if (($candidate['key'] ?? '') === 'field_physician_languages') {
            $field = $candidate;
        }
    }

    expect($field)->not->toBeNull()
        ->and($field['type'])->toBe('taxonomy')
        ->and($field['taxonomy'])->toBe('language')
        ->and($field['default_value'])->toBe(['English']);
});
