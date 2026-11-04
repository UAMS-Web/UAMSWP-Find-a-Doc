<?php

/**
 * Pins the Workday ID fields on the provider profile as they are today: four plain text
 * fields with no validation and no storage hook, beside the one trim hook the location
 * Epic DEP ID field already has.
 *
 * The tests that assert an absence (no validation filter, the old instructions text) are
 * meant to fail the moment the pending validation change lands, and to be replaced then
 * by tests of the new behavior.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

require_once __DIR__.'/../Support/acf-functions.php';

const UAMSWP_TEST_WORKDAY_KEYS = [
    'field_physician_workday_universal_id',
    'field_physician_workday_employee_id',
    'field_physician_workday_contingent_worker_id',
    'field_physician_workday_student_id',
];

/**
 * The four Workday fields from the provider field group, by key.
 *
 * @return array<string, array<string, mixed>>
 */
function uamswp_test_workday_fields(): array
{
    $group = json_decode((string) file_get_contents(UAMS_FAD_PATH.'assets/json/acf-json/group_uamswp_physicians.json'), true);
    $fields = [];

    foreach ($group['fields'] as $field) {
        if (in_array($field['key'] ?? '', UAMSWP_TEST_WORKDAY_KEYS, true)) {
            $fields[$field['key']] = $field;
        }
    }

    return $fields;
}

test('no validate_value or update_value filter is registered for any Workday ID field', function (): void {
    $registered = uamswp_test_acf_functions()['filters'];
    $workday = array_filter(
        $registered,
        fn (bool $is_registered, string $key): bool => str_contains($key, 'workday'),
        ARRAY_FILTER_USE_BOTH,
    );

    // The probe covers both hook kinds, four keys, two priorities and both callback names.
    expect(count($workday))->toBe(32)
        ->and(array_filter($workday))->toBe([]);
});

test('the probe can see a registration: the Epic DEP ID trim filter reads as registered', function (): void {
    $registered = uamswp_test_acf_functions()['filters'];

    expect($registered['acf/update_value/key=field_location_epic_dep_id_item|uamswp_location_trim_epic_dep_id|10'])->toBeTrue();
});

test('the four Workday fields are optional plain text fields with no length limit', function (): void {
    $fields = uamswp_test_workday_fields();

    expect(array_keys($fields))->toBe(UAMSWP_TEST_WORKDAY_KEYS);

    foreach ($fields as $field) {
        expect($field['type'])->toBe('text')
            ->and($field['required'])->toBe(0)
            ->and($field['maxlength'])->toBe('')
            ->and($field['default_value'])->toBe('');
    }
});

test('the Universal ID instructions describe a 32-character GUID', function (): void {
    $field = uamswp_test_workday_fields()['field_physician_workday_universal_id'];

    expect($field['instructions'])->toStartWith('A Workday Universal ID is a unique, immutable 32-character GUID')
        ->and($field['label'])->toBe('Workday Universal ID');
});

test('the Epic DEP ID trim filter trims a string and passes anything else through', function (): void {
    uamswp_test_acf_functions();

    expect(uamswp_location_trim_epic_dep_id("  900 \t", 123, []))->toBe('900')
        ->and(uamswp_location_trim_epic_dep_id('', 123, []))->toBe('')
        ->and(uamswp_location_trim_epic_dep_id(null, 123, []))->toBeNull()
        ->and(uamswp_location_trim_epic_dep_id(900, 123, []))->toBe(900)
        ->and(uamswp_location_trim_epic_dep_id(['900'], 123, []))->toBe(['900']);
});
