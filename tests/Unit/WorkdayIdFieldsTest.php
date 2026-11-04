<?php

/**
 * Pins the Workday ID fields on the provider profile: four optional text fields, each with
 * a digits-only validation filter and a trim-on-store filter, beside the Epic DEP ID trim
 * filter the trim is modeled on.
 */

declare(strict_types=1);

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

test('each Workday ID field has the validation filter at priority 10 and the trim filter at priority 10', function (): void {
    $registered = uamswp_test_acf_functions()['filters'];

    foreach (UAMSWP_TEST_WORKDAY_KEYS as $key) {
        $name = substr($key, strlen('field_physician_workday_'));

        expect($registered['acf/validate_value/key=field_physician_workday_'.$name.'|uamswp_workday_id_validate|10'])->toBeTrue()
            ->and($registered['acf/update_value/key=field_physician_workday_'.$name.'|uamswp_workday_id_trim|10'])->toBeTrue()
            // The callbacks are not crossed, and nothing sits at the other priority.
            ->and($registered['acf/validate_value/key=field_physician_workday_'.$name.'|uamswp_workday_id_trim|10'])->toBeFalse()
            ->and($registered['acf/update_value/key=field_physician_workday_'.$name.'|uamswp_workday_id_validate|10'])->toBeFalse()
            ->and($registered['acf/validate_value/key=field_physician_workday_'.$name.'|uamswp_workday_id_validate|20'])->toBeFalse();
    }
});

test('the probe can see a registration: the Epic DEP ID trim filter reads as registered', function (): void {
    $registered = uamswp_test_acf_functions()['filters'];

    expect($registered['acf/update_value/key=field_location_epic_dep_id_item|uamswp_location_trim_epic_dep_id|10'])->toBeTrue();
});

test('the rules table gives 9 digits to the Universal ID and 7 to the other three', function (): void {
    uamswp_test_acf_functions();

    $rules = uamswp_workday_id_validation_rules();

    expect(array_keys($rules))->toBe(UAMSWP_TEST_WORKDAY_KEYS)
        ->and(array_column($rules, 'digits'))->toBe([9, 7, 7, 7])
        ->and(array_column($rules, 'label'))->toBe(['Workday Universal ID', 'Workday Employee ID', 'Workday Contingent Worker ID', 'Workday Student ID']);
});

test('the validator accepts the exact number of digits, with surrounding whitespace', function (string $key, string $value): void {
    uamswp_test_acf_functions();

    expect(uamswp_workday_id_validate(true, $value, ['key' => $key], 'acf[x]'))->toBeTrue();
})->with([
    'universal' => ['field_physician_workday_universal_id', '123456789'],
    'universal padded' => ['field_physician_workday_universal_id', '  123456789
'],
    'employee' => ['field_physician_workday_employee_id', '1234567'],
    'contingent worker' => ['field_physician_workday_contingent_worker_id', '0000001'],
    'student' => ['field_physician_workday_student_id', ' 7654321 '],
]);

test('the validator accepts an empty value, since no field is required', function (mixed $value): void {
    uamswp_test_acf_functions();

    expect(uamswp_workday_id_validate(true, $value, ['key' => 'field_physician_workday_employee_id'], 'acf[x]'))->toBeTrue();
})->with(['an empty string' => [''], 'whitespace only' => ['  	']]);

test('the validator returns the message for a value that is not digits only of the expected length', function (string $key, string $value, string $message): void {
    uamswp_test_acf_functions();

    expect(uamswp_workday_id_validate(true, $value, ['key' => $key], 'acf[x]'))->toBe($message);
})->with([
    'a label pasted with the number' => ['field_physician_workday_employee_id', 'Employee ID 1234567', 'Enter the 7-digit Workday Employee ID only, e.g. 1234567.'],
    'too short' => ['field_physician_workday_employee_id', '123456', 'Enter the 7-digit Workday Employee ID only, e.g. 1234567.'],
    'too long' => ['field_physician_workday_student_id', '12345678', 'Enter the 7-digit Workday Student ID only, e.g. 1234567.'],
    'a seven-digit universal ID' => ['field_physician_workday_universal_id', '1234567', 'Enter the 9-digit Workday Universal ID only, e.g. 123456789.'],
    'the old 32-character GUID' => ['field_physician_workday_universal_id', 'a1b2c3d4e5f60718293a4b5c6d7e8f90', 'Enter the 9-digit Workday Universal ID only, e.g. 123456789.'],
    'digits with an inner space' => ['field_physician_workday_contingent_worker_id', '123 4567', 'Enter the 7-digit Workday Contingent Worker ID only, e.g. 1234567.'],
]);

test('the validator passes through an earlier error, an unknown field and a non-string value', function (): void {
    uamswp_test_acf_functions();

    expect(uamswp_workday_id_validate('Already invalid.', 'abc', ['key' => 'field_physician_workday_employee_id'], 'acf[x]'))->toBe('Already invalid.')
        ->and(uamswp_workday_id_validate(true, 'abc', ['key' => 'field_physician_first_name'], 'acf[x]'))->toBeTrue()
        ->and(uamswp_workday_id_validate(true, 'abc', [], 'acf[x]'))->toBeTrue()
        ->and(uamswp_workday_id_validate(true, ['abc'], ['key' => 'field_physician_workday_employee_id'], 'acf[x]'))->toBeTrue();
});

test('the trim filter trims a string and passes anything else through', function (): void {
    uamswp_test_acf_functions();

    expect(uamswp_workday_id_trim(' 1234567 ', 123, []))->toBe('1234567')
        ->and(uamswp_workday_id_trim('', 123, []))->toBe('')
        ->and(uamswp_workday_id_trim(null, 123, []))->toBeNull()
        ->and(uamswp_workday_id_trim(1234567, 123, []))->toBe(1234567);
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

test('the Universal ID instructions describe the 9-digit number', function (): void {
    $field = uamswp_test_workday_fields()['field_physician_workday_universal_id'];

    expect($field['instructions'])->toStartWith('The 9-digit Workday Universal ID shown on the person')
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
