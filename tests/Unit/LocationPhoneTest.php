<?php

/**
 * The location phone numbers partial (templates/blocks/locations-phone.php): the numbers printed in a location
 * profile's contact section and under the location cards.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';

/**
 * Render the phone numbers of location 9, titled `Clinic A`, and collapse the whitespace.
 *
 * @param  array<string, mixed>  $fields  The ACF fields of the location by name.
 * @param  array<string, mixed>  $variables  Other variables the including template sets.
 * @param  list<array<string, mixed>>  $rows  The rows of the additional phone numbers repeater.
 */
function location_phone(array $fields, string $output = 'associated_locations', array $variables = [], array $rows = []): string
{
    $position = new stdClass;
    $position->index = -1;

    when('get_field')->alias(static fn (string $name, mixed $id = null): mixed => $fields[$name] ?? null);
    when('get_the_title')->justReturn($fields['__title'] ?? 'Clinic A');
    when('format_phone_dash')->alias(static fn (mixed $phone): string => $phone ? 'dash:'.$phone : '');
    when('have_rows')->alias(static fn (): bool => $position->index + 1 < count($rows));
    when('the_row')->alias(static function () use ($position): void {
        $position->index++;
    });
    when('get_sub_field')->alias(static fn (string $name): mixed => $rows[$position->index][$name] ?? null);

    $text = uamswp_test_include_partial('blocks/locations-phone', $variables + ['phone_output_id' => 9, 'phone_output' => $output]);

    return trim((string) preg_replace('/\s+/', ' ', $text));
}

/**
 * The anchor the partial builds for a number.
 */
function location_phone_link(string $phone, string $type, string $item = 'Clinic A'): string
{
    return '<a href="tel:dash:'.$phone.'" class="icon-phone" data-categorytitle="Telephone Number"'.($item !== '' ? ' data-itemtitle="'.$item.'"' : '').' data-typetitle="'.$type.'">dash:'.$phone.'</a>';
}

test('the card phone block prints nothing without a phone number', function (): void {
    expect(location_phone([]))->toBe('');
});

test('the card phone block offers the general number for appointments when there is no separate one', function (): void {
    $output = location_phone(['location_phone' => '501-1']);

    expect($output)->toBe('<dl data-categorytitle="Telephone Number"> <dt>Appointment Phone Number</dt> <dd>'.location_phone_link('501-1', 'Appointment Phone Number for New and Returning Patients').'<br/><span class="subtitle">New and Returning Patients</span></dd> </dl>');
});

test('the card phone block treats a location that cannot schedule as a general information number', function (): void {
    $output = location_phone(['location_phone' => '501-1', 'location_appointments_query' => false]);

    expect($output)->toBe('<dl data-categorytitle="Telephone Number"> <dt>General Information</dt> <dd>'.location_phone_link('501-1', 'Clinic Phone Number').'</dd> </dl>');
});

test('the card phone block prints the appointment number for new and returning patients', function (): void {
    $output = location_phone(['location_phone' => '501-1', 'location_clinic_phone_query' => true, 'location_new_appointments_phone' => '501-2']);

    expect($output)->toContain('<dt>Appointment Phone Number</dt>')
        ->and($output)->toContain('<dd><a href="tel:dash:501-2" class="icon-phone" data-categorytitle="Telephone Number" data-itemtitle="Clinic A" data-typetitle="Appointment Phone Number for New and Returning Patients">dash:501-2</a><br/><span class="subtitle">New and Returning Patients</span></dd>')
        ->and($output)->not->toContain('dash:501-1');
});

// The tracking label of the new patient link says "New and Returning Patients" even with a separate returning
// number, because the link is built before the template reads whether there is one (kept as it was).
test('the card phone block prints a separate number for returning patients', function (): void {
    $output = location_phone([
        'location_phone' => '501-1',
        'location_clinic_phone_query' => true,
        'location_new_appointments_phone' => '501-2',
        'field_location_appointment_phone_query' => true,
        'location_return_appointments_phone' => '501-3',
    ]);

    expect($output)->toContain('<dt>Appointment Phone Numbers</dt>')
        ->and($output)->toContain('data-typetitle="Appointment Phone Number for New and Returning Patients">dash:501-2</a><br/><span class="subtitle">New Patients</span></dd>')
        ->and($output)->toContain('<dd><a href="tel:dash:501-3" class="icon-phone" data-categorytitle="Telephone Number" data-itemtitle="Clinic A" data-typetitle="Appointment Phone Number for Returning Patients">dash:501-3</a><br/><span class="subtitle">Returning Patients</span></dd>');
});

test('the card phone block leaves out the returning patients line without that number', function (): void {
    $output = location_phone([
        'location_phone' => '501-1',
        'location_clinic_phone_query' => true,
        'location_new_appointments_phone' => '501-2',
        'field_location_appointment_phone_query' => true,
    ]);

    expect($output)->toContain('<dt>Appointment Phone Numbers</dt>')
        ->and($output)->not->toContain('Returning Patients</span>');
});

test('the card phone block falls back to the general number when the clinic query has no appointment number', function (): void {
    $output = location_phone(['location_phone' => '501-1', 'location_clinic_phone_query' => true]);

    expect($output)->toContain('<dd>'.location_phone_link('501-1', 'Appointment Phone Number for New and Returning Patients').'<br/><span class="subtitle">New and Returning Patients</span></dd>');
});

test("the card phone block prints the primary and specialty care numbers of a children's hospital location", function (): void {
    $output = location_phone([
        'location_phone' => '501-1',
        'location_ac_query' => true,
        'location_ac_appointments_query' => true,
        'location_ac_appointments_primary' => '501-4',
        'location_ac_appointments_specialty' => '501-5',
        'location_clinic_phone_query' => true,
    ]);

    expect($output)->toContain('<dt>Appointment Phone Numbers</dt>')
        ->and($output)->toContain('<dd><a href="tel:dash:501-4" class="icon-phone" data-categorytitle="Telephone Number" data-itemtitle="Clinic A" data-typetitle="Arkansas Children\'s Primary Care Appointments Phone Number">dash:501-4</a><br/><span class="subtitle">Primary Care</span></dd>')
        ->and($output)->toContain('<dd><a href="tel:dash:501-5" class="icon-phone" data-categorytitle="Telephone Number" data-itemtitle="Clinic A" data-typetitle="Arkansas Children\'s Specialty Care Appointments Phone Number">dash:501-5</a><br/><span class="subtitle">Specialty Care</span></dd>');
});

test("the card phone block uses the general number for a children's hospital location without separate numbers", function (): void {
    $output = location_phone(['location_phone' => '501-1', 'location_ac_query' => true, 'location_new_appointments_phone' => '501-2']);

    expect($output)->toContain('<dt>Appointment Phone Number</dt>')
        ->and($output)->toContain('<dd><a href="tel:dash:501-2" class="icon-phone"')
        ->and($output)->toContain('New and Returning Patients</span></dd>');
});

test("the card phone block ignores the children's hospital numbers when the location cannot schedule", function (): void {
    $output = location_phone([
        'location_phone' => '501-1',
        'location_ac_query' => true,
        'location_appointments_query' => false,
        'location_ac_appointments_query' => true,
        'location_ac_appointments_primary' => '501-4',
    ]);

    expect($output)->toContain('<dt>General Information</dt>')
        ->and($output)->not->toContain('501-4');
});

test('the card phone block names the location in the tracking label without quotes or entities', function (): void {
    $output = location_phone(['location_phone' => '501-1', '__title' => 'Mom&#8217;s "Clinic"']);

    expect($output)->toContain('data-itemtitle="Mom\'s \'Clinic\'"');
});

test('the profile phone block prints the general number with appointments, and the fax number', function (): void {
    $output = location_phone(['location_phone' => '501-1', 'location_fax' => '501-9'], 'location_profile');

    expect($output)->toBe('<dl data-categorytitle="Telephone Number"> <dt>General Information and Appointments</dt> <dd>'.location_phone_link('501-1', 'Clinic Phone Number', '').'</dd> <dt>Fax Number</dt> <dd>dash:501-9</dd> </dl>');
});

test('the profile phone block prints general information only when appointments have their own numbers', function (): void {
    $withQuery = location_phone(['location_phone' => '501-1', 'location_clinic_phone_query' => true, 'location_new_appointments_phone' => '501-2'], 'location_profile');

    expect($withQuery)->toContain('<dt>General Information</dt>')
        ->and($withQuery)->toContain('<dt>Appointments</dt> <dd>'.location_phone_link('501-2', 'Appointment Phone Number for New and Returning Patients', '').'</dd>');

    $noAppointments = location_phone(['location_phone' => '501-1', 'location_appointments_query' => false], 'location_profile');

    expect($noAppointments)->toContain('<dt>General Information</dt>');
});

test('the profile phone block labels new and returning patients when there is a separate returning number', function (): void {
    $output = location_phone([
        'location_phone' => '501-1',
        'location_clinic_phone_query' => true,
        'location_new_appointments_phone' => '501-2',
        'field_location_appointment_phone_query' => true,
        'location_return_appointments_phone' => '501-3',
    ], 'location_profile');

    expect($output)->toContain('data-typetitle="Appointment Phone Number for New and Returning Patients">dash:501-2</a><br/><span class="subtitle">New Patients</span></dd>')
        ->and($output)->toContain('data-typetitle="Appointment Phone Number for Returning Patients">dash:501-3</a><br/><span class="subtitle">Returning Patients</span></dd>');
});

test('the profile phone block leaves out an appointments heading with no appointment number', function (): void {
    $output = location_phone(['location_phone' => '501-1', 'location_clinic_phone_query' => true], 'location_profile');

    expect($output)->not->toContain('<dt>Appointments</dt>');
});

test("the profile phone block prints the children's hospital numbers under appointments", function (): void {
    $output = location_phone([
        'location_phone' => '501-1',
        'location_ac_query' => true,
        'location_ac_appointments_query' => true,
        'location_ac_appointments_primary' => '501-4',
        'location_ac_appointments_specialty' => '501-5',
        'location_fax' => '501-9',
    ], 'location_profile');

    expect($output)->toContain('<dt>Appointments</dt>')
        ->and($output)->toContain('<br/><span class="subtitle">Primary Care</span></dd>')
        ->and($output)->toContain('<br/><span class="subtitle">Specialty Care</span></dd>')
        ->and($output)->not->toContain('Fax Number');
});

test("the profile phone block prints only the children's hospital number it has", function (): void {
    $primary = location_phone(['location_phone' => '501-1', 'location_ac_query' => true, 'location_ac_appointments_query' => true, 'location_ac_appointments_primary' => '501-4'], 'location_profile');

    expect($primary)->toContain('Primary Care</span>')->not->toContain('Specialty Care');

    $specialty = location_phone(['location_phone' => '501-1', 'location_ac_query' => true, 'location_ac_appointments_query' => true, 'location_ac_appointments_specialty' => '501-5'], 'location_profile');

    expect($specialty)->toContain('Specialty Care</span>')->not->toContain('Primary Care');

    $neither = location_phone(['location_phone' => '501-1', 'location_ac_query' => true, 'location_ac_appointments_query' => true], 'location_profile');

    expect($neither)->not->toContain('<dt>Appointments</dt>');
});

test('the profile phone block prints the additional phone numbers of the repeater', function (): void {
    $rows = [
        ['location_appointments_text' => 'Billing "desk"', 'location_appointments_phone' => '501-6', 'location_appointments_additional_text' => 'Weekdays'],
        ['location_appointments_text' => 'Records', 'location_appointments_phone' => '501-7', 'location_appointments_additional_text' => ''],
    ];

    $output = location_phone(['location_phone' => '501-1', 'field_location_phone_numbers' => $rows], 'location_profile', [], $rows);

    expect($output)->toContain('<dt>Billing "desk"</dt> <dd><a href="tel:dash:501-6" data-typetitle="Additional Phone Number: Billing \'desk\'">dash:501-6</a><br/><span class="subtitle">Weekdays</span></dd>')
        ->and($output)->toContain('<dt>Records</dt> <dd><a href="tel:dash:501-7" data-typetitle="Additional Phone Number: Records">dash:501-7</a></dd>');
});

test('the profile phone block prints the older appointments repeater rows that have a text and a number', function (): void {
    $older = [
        ['text' => 'After hours', 'number' => '501-8', 'after' => '(nurse line)'],
        ['text' => '', 'number' => '501-9', 'after' => ''],
        ['text' => 'No number', 'number' => '', 'after' => ''],
    ];

    $output = location_phone(['location_phone' => '501-1', 'location_appointments' => $older], 'location_profile');

    expect($output)->toContain('<dt>After hours</dt><dd><a href="tel:501-8" class="icon-phone">501-8</a> (nurse line)</dd>')
        ->and($output)->not->toContain('501-9"')
        ->and($output)->not->toContain('No number');

    $emptyFirst = location_phone(['location_phone' => '501-1', 'location_appointments' => [['text' => 'Skipped', 'number' => '', 'after' => '']]], 'location_profile');

    expect($emptyFirst)->not->toContain('Skipped');
});

test('the profile phone block builds the schema list of telephone numbers when the page asks for one', function (): void {
    $schema = '"contactPoint": [';
    $rows = [['location_appointments_text' => 'Billing', 'location_appointments_phone' => '501-6', 'location_appointments_additional_text' => '']];

    location_phone([
        'location_phone' => '501-1',
        'location_clinic_phone_query' => true,
        'location_new_appointments_phone' => '501-2',
        'field_location_appointment_phone_query' => true,
        'location_return_appointments_phone' => '501-3',
        'field_location_phone_numbers' => $rows,
    ], 'location_profile', ['phone_schema' => &$schema], $rows);

    $collapsed = trim((string) preg_replace('/\s+/', ' ', $schema));

    expect($collapsed)->toBe('"contactPoint": ["telephone": ["dash:501-1" , "dash:501-2" , "dash:501-3" , "dash:501-6" ],');
});

test("the profile phone block adds the children's hospital numbers to the schema list", function (): void {
    $schema = '';

    location_phone([
        'location_phone' => '501-1',
        'location_ac_query' => true,
        'location_ac_appointments_query' => true,
        'location_ac_appointments_primary' => '501-4',
        'location_ac_appointments_specialty' => '501-5',
    ], 'location_profile', ['phone_schema' => &$schema]);

    expect(trim((string) preg_replace('/\s+/', ' ', $schema)))->toBe('"telephone": ["dash:501-1" , "dash:501-4" , "dash:501-5" ],');
});

test('the profile phone block skips an additional number without a phone in the schema list', function (): void {
    $schema = '';
    $rows = [['location_appointments_text' => 'Empty', 'location_appointments_phone' => '', 'location_appointments_additional_text' => '']];

    location_phone(['field_location_phone_numbers' => $rows], 'location_profile', ['phone_schema' => &$schema], $rows);

    expect(trim((string) preg_replace('/\s+/', ' ', $schema)))->toBe('],');
});
