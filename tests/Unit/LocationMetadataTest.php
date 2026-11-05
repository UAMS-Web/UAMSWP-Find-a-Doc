<?php

/**
 * Location metadata fallback contracts without a WordPress installation.
 *
 * @command  vendor/bin/pest tests/Unit/LocationMetadataTest.php
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

test('location portal metadata resolves the term name without changing its fields', function (
    string $result,
    string $expected_name,
): void {
    // Isolate the public callback from registrations and unrelated location fields.
    when('add_action')->justReturn(true);
    when('add_filter')->justReturn(true);
    require_once dirname(__DIR__, 2).'/includes/class.fad-custom-post.php';
    when('get_the_title')->justReturn('Clinic');
    when('get_permalink')->justReturn('https://example.test/clinic');
    when('get_field_object')->justReturn(false);
    when('format_phone_us')->justReturn('');
    when('format_phone_dash')->justReturn('');
    when('uamswp_fad_location_source_ids')->justReturn(array_fill_keys([
        'street', 'facility', 'unit', 'map', 'parking', 'directions',
    ], 101));

    // Distinguish successful, missing, and erroneous WordPress term lookups.
    $error = (object) ['errors' => ['invalid_taxonomy' => ['Invalid taxonomy']]];
    $selection = $result === 'unselected' ? false : 41;
    $portal = match ($result) {
        'valid' => (object) ['name' => 'MyChart Patient Portal'],
        'alternate' => (object) ['name' => 'Specialty Care Access'],
        'error', 'unselected' => $error,
        default => null,
    };
    when('is_wp_error')->alias(fn ($value): bool => $value === $error);
    when('get_term')->alias(fn ($id, string $taxonomy) => [$id, $taxonomy] === [$selection, 'portal'] ? $portal : null);
    when('get_field')->alias(fn (string $field, $id): array|int|string|false => match (true) {
        $field === 'location_portal' && $id === 101 => $selection,
        $field === 'portal_content' && $id === $portal => 'Sign in to your account',
        $field === 'portal_url' && $id === $portal => 'https://example.test/portal',
        $field === 'location_hours_group' => array_fill_keys([
            'location_hours_variable', 'location_hours_variable_info', 'location_24_7',
            'location_modified_hours', 'location_modified_hours_reason',
            'location_modified_hours_start_date', 'location_modified_hours_end',
            'location_modified_hours_end_date', 'location_modified_hours_group',
            'location_hours', 'location_after_hours', 'location_telemed_query',
            'location_telemed_patients', 'location_telemed_24_7', 'location_telemed_hours',
            'location_telemed_modified_hours_query', 'location_telemed_modified_hours_reason',
            'location_telemed_modified_hours_start_date', 'location_telemed_modified_hours_end',
            'location_telemed_modified_hours_end_date', 'location_telemed_modified_hours_24_7',
        ], false),
        default => false,
    });

    // Preserve portal content and URL even when a name cannot be resolved.
    $data = get_location_meta(['id' => 101]);
    expect($data['location_portal'])->toBe([
        'name' => $expected_name,
        'content' => 'Sign in to your account',
        'url' => 'https://example.test/portal',
    ]);
})->with([
    ['valid', 'MyChart Patient Portal'],
    ['alternate', 'Specialty Care Access'],
    ['missing', ''],
    ['error', ''],
    ['unselected', ''],
]);
