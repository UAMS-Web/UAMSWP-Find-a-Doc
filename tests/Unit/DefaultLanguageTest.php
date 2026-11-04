<?php

/**
 * Pins how a new provider gets its default language: the ACF field's own default_value in
 * the field group JSON, and the set_default_language() filter that seeds the English term
 * on an auto-draft post only.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

require_once __DIR__.'/../Support/acf-functions.php';

test('it is hooked to the provider languages load_value filter at priority 20 with three arguments', function (): void {
    expect(uamswp_test_acf_functions()['filters']['acf/load_value/key=field_physician_languages|set_default_language|20'])->toBeTrue();
});

test('it returns the value untouched unless the post is an auto-draft', function (mixed $stored, string|false $status): void {
    uamswp_test_acf_functions();
    Functions\when('get_post_status')->justReturn($status);
    Functions\expect('get_term_by')->never();

    expect(set_default_language($stored, 123, ['key' => 'field_physician_languages']))->toBe($stored);
})->with([
    'null on a published provider' => [null, 'publish'],
    'null on a draft' => [null, 'draft'],
    'an empty list on a published provider' => [[], 'publish'],
    'a list on a published provider' => [[5, 7], 'publish'],
    'null with no post status' => [null, false],
]);

test('it returns a non-empty value untouched even on an auto-draft', function (mixed $stored): void {
    uamswp_test_acf_functions();
    Functions\when('get_post_status')->justReturn('auto-draft');
    Functions\expect('get_term_by')->never();

    expect(set_default_language($stored, 123, []))->toBe($stored);
})->with([
    'a list of term IDs' => [[5, 7]],
    'a term ID string' => ['5'],
]);

test('it seeds the English term ID, as an integer, in a list on an auto-draft with an empty value', function (mixed $empty): void {
    uamswp_test_acf_functions();
    Functions\expect('get_post_status')->once()->with(123)->andReturn('auto-draft');
    Functions\expect('get_term_by')->once()->with('slug', 'english', 'language')->andReturn((object) ['term_id' => '42']);

    expect(set_default_language($empty, 123, []))->toBe([42]);
})->with([
    'null' => [null],
    'an empty list' => [[]],
    'an empty string' => [''],
    'false' => [false],
]);

test('it leaves the value as it is, with no warning, when there is no english term', function (mixed $term): void {
    uamswp_test_acf_functions();
    Functions\when('get_post_status')->justReturn('auto-draft');
    Functions\when('get_term_by')->justReturn($term);
    Functions\when('is_wp_error')->alias(fn ($value): bool => $value instanceof stdClass && isset($value->errors));

    $result = 'unset';
    $warnings = uamswp_test_capture_warnings(function () use (&$result): void {
        $result = set_default_language(null, 123, []);
    });

    expect($warnings)->toBe([])
        ->and($result)->toBeNull();
})->with([
    'false' => [false],
    'null' => [null],
    'an error object' => [(object) ['errors' => ['invalid_term' => ['Invalid taxonomy.']]]],
]);

test('the field group sets no default value on the languages field', function (): void {
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
        ->and($field['default_value'])->toBe([]);
});
