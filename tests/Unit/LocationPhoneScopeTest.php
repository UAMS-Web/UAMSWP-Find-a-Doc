<?php

/**
 * What the location phone numbers partial (templates/blocks/locations-phone.php) leaves in the scope of the
 * template that includes it. The partial runs in that template's scope, and the location profile reads five
 * of these variables after the include, so each is part of what the partial hands back.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';

/**
 * Include the partial for location 9, titled `Clinic A`, and return the variables it leaves behind.
 *
 * @param  array<string, mixed>  $fields  The ACF fields of the location by name.
 * @param  list<array<string, mixed>>  $rows  The rows of the additional phone numbers repeater.
 * @return array<string, mixed>
 */
function location_phone_scope(array $fields, string $output = 'associated_locations', array $rows = []): array
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

    $include = static function (int $phone_output_id, string $phone_output): array {
        ob_start();

        try {
            include UAMS_FAD_PATH.'templates/blocks/locations-phone.php';
        } finally {
            ob_end_clean();
        }

        $scope = get_defined_vars();
        unset($scope['phone_output_id'], $scope['phone_output']);
        ksort($scope);

        return $scope;
    };

    return $include(9, $output);
}

/**
 * The anchor the partial builds for a number of location 9.
 */
function location_phone_scope_link(string $phone, string $type, string $item = 'Clinic A'): string
{
    return '<a href="tel:dash:'.$phone.'" class="icon-phone" data-categorytitle="Telephone Number"'.($item !== '' ? ' data-itemtitle="'.$item.'"' : '').' data-typetitle="'.$type.'">dash:'.$phone.'</a>';
}

/**
 * What the partial leaves for a location card of `Clinic A` with no number of any kind, as overridden.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function location_phone_scope_card(array $overrides = []): array
{
    $scope = array_replace([
        'location_ac_appointments_primary' => '',
        'location_ac_appointments_primary_format_dash' => '',
        'location_ac_appointments_primary_link' => '',
        'location_ac_appointments_query' => false,
        'location_ac_appointments_specialty' => '',
        'location_ac_appointments_specialty_format_dash' => '',
        'location_ac_appointments_specialty_link' => '',
        'location_ac_query' => null,
        'location_appointment_phone_query' => false,
        'location_appointments_query' => null,
        'location_clinic_phone_query' => false,
        'location_fax' => '',
        'location_fax_format_dash' => '',
        'location_new_appointments_phone' => '',
        'location_new_appointments_phone_format_dash' => '',
        'location_new_appointments_phone_link' => '',
        'location_phone' => null,
        'location_phone_appointments_multiple_query' => false,
        'location_phone_data_categorytitle' => 'Telephone Number',
        'location_phone_data_itemtitle' => 'Clinic A',
        'location_phone_format_dash' => '',
        'location_phone_link' => '<a href="tel:" class="icon-phone" data-categorytitle="Telephone Number" data-itemtitle="Clinic A" data-typetitle="Clinic Phone Number"></a>',
        'location_phone_link_data_typetitle' => 'Clinic Phone Number',
        'location_phone_numbers' => '',
        'location_return_appointments_phone' => '',
        'location_return_appointments_phone_format_dash' => '',
        'location_return_appointments_phone_link' => '',
        'location_title' => 'Clinic A',
        'location_title_attr' => 'Clinic A',
    ], $overrides);
    ksort($scope);

    return $scope;
}

/**
 * What the partial leaves for a location profile with no number of any kind, as overridden.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function location_phone_scope_profile(array $overrides = []): array
{
    return location_phone_scope_card(array_replace([
        'first_phone_number' => [],
        'location_fax' => null,
        'location_phone_data_itemtitle' => '',
        'location_phone_link' => '<a href="tel:" class="icon-phone" data-categorytitle="Telephone Number" data-typetitle="Clinic Phone Number"></a>',
        'location_phone_numbers' => null,
        'location_title' => '',
        'location_title_attr' => '',
        'phone_numbers' => [],
        'profile_phone_schema' => [],
    ], $overrides));
}

test('a card of a location that cannot schedule leaves only its title and the clinic number link', function (): void {
    expect(location_phone_scope([]))->toBe(location_phone_scope_card());
});

test('a card of a location that can schedule leaves the general number as the appointment number', function (): void {
    expect(location_phone_scope(['location_phone' => '501-1', 'location_appointments_query' => '1']))->toBe(location_phone_scope_card([
        'location_appointments_query' => '1',
        'location_clinic_phone_query' => null,
        'location_phone' => '501-1',
        'location_phone_format_dash' => 'dash:501-1',
        'location_phone_link' => location_phone_scope_link('501-1', 'Appointment Phone Number for New and Returning Patients'),
        'location_phone_link_data_typetitle' => 'Appointment Phone Number for New and Returning Patients',
    ]));
});

test('a card leaves the new and returning patient numbers of a location that has them', function (): void {
    $scope = location_phone_scope([
        'location_phone' => '501-1',
        'location_appointments_query' => '1',
        'location_clinic_phone_query' => true,
        'location_new_appointments_phone' => '501-2',
        'field_location_appointment_phone_query' => true,
        'location_return_appointments_phone' => '501-3',
    ]);

    expect($scope)->toBe(location_phone_scope_card([
        'location_appointment_phone_query' => true,
        'location_appointments_query' => '1',
        'location_clinic_phone_query' => true,
        'location_new_appointments_phone' => '501-2',
        'location_new_appointments_phone_format_dash' => 'dash:501-2',
        'location_new_appointments_phone_link' => location_phone_scope_link('501-2', 'Appointment Phone Number for New and Returning Patients'),
        'location_phone' => '501-1',
        'location_phone_appointments_multiple_query' => true,
        'location_phone_format_dash' => 'dash:501-1',
        'location_phone_link' => location_phone_scope_link('501-1', 'Appointment Phone Number for New and Returning Patients'),
        'location_phone_link_data_typetitle' => 'Appointment Phone Number for New and Returning Patients',
        'location_return_appointments_phone' => '501-3',
        'location_return_appointments_phone_format_dash' => 'dash:501-3',
        'location_return_appointments_phone_link' => location_phone_scope_link('501-3', 'Appointment Phone Number for Returning Patients'),
    ]));
});

test("a card leaves the primary and specialty care numbers of a children's hospital location, and reads none of the others", function (): void {
    $scope = location_phone_scope([
        'location_phone' => '501-1',
        'location_appointments_query' => '1',
        'location_ac_query' => true,
        'location_ac_appointments_query' => true,
        'location_ac_appointments_primary' => '501-4',
        'location_ac_appointments_specialty' => '501-5',
        'location_clinic_phone_query' => true,
        'location_new_appointments_phone' => '501-2',
        'field_location_appointment_phone_query' => true,
        'location_return_appointments_phone' => '501-3',
    ]);

    expect($scope)->toBe(location_phone_scope_card([
        'location_ac_appointments_primary' => '501-4',
        'location_ac_appointments_primary_format_dash' => 'dash:501-4',
        'location_ac_appointments_primary_link' => location_phone_scope_link('501-4', "Arkansas Children's Primary Care Appointments Phone Number"),
        'location_ac_appointments_query' => true,
        'location_ac_appointments_specialty' => '501-5',
        'location_ac_appointments_specialty_format_dash' => 'dash:501-5',
        'location_ac_appointments_specialty_link' => location_phone_scope_link('501-5', "Arkansas Children's Specialty Care Appointments Phone Number"),
        'location_ac_query' => true,
        'location_appointments_query' => '1',
        'location_clinic_phone_query' => true,
        'location_phone' => '501-1',
        'location_phone_appointments_multiple_query' => true,
        'location_phone_format_dash' => 'dash:501-1',
        'location_phone_link' => location_phone_scope_link('501-1', 'Appointment Phone Number for New and Returning Patients'),
        'location_phone_link_data_typetitle' => 'Appointment Phone Number for New and Returning Patients',
    ]));
});

test("a children's hospital location with one appointment number leaves it as the new patient number", function (): void {
    $scope = location_phone_scope([
        'location_appointments_query' => '1',
        'location_ac_query' => true,
        'location_new_appointments_phone' => '501-2',
        'field_location_appointment_phone_query' => true,
        'location_return_appointments_phone' => '501-3',
    ]);

    expect($scope)->toBe(location_phone_scope_card([
        'location_ac_appointments_query' => null,
        'location_ac_query' => true,
        'location_appointments_query' => '1',
        'location_clinic_phone_query' => true,
        'location_new_appointments_phone' => '501-2',
        'location_new_appointments_phone_format_dash' => 'dash:501-2',
        'location_new_appointments_phone_link' => location_phone_scope_link('501-2', 'Appointment Phone Number for New and Returning Patients'),
        'location_phone_link' => '<a href="tel:" class="icon-phone" data-categorytitle="Telephone Number" data-itemtitle="Clinic A" data-typetitle="Appointment Phone Number for New and Returning Patients"></a>',
        'location_phone_link_data_typetitle' => 'Appointment Phone Number for New and Returning Patients',
    ]));
});

test("a children's hospital location that cannot schedule reads no appointment number", function (): void {
    $scope = location_phone_scope([
        'location_ac_query' => true,
        'location_ac_appointments_query' => true,
        'location_ac_appointments_primary' => '501-4',
        'location_new_appointments_phone' => '501-2',
    ]);

    expect($scope)->toBe(location_phone_scope_card(['location_ac_query' => true]));
});

test('a location without its own appointment numbers reads neither the new nor the returning number', function (): void {
    $scope = location_phone_scope([
        'location_appointments_query' => '1',
        'location_clinic_phone_query' => false,
        'location_new_appointments_phone' => '501-2',
        'field_location_appointment_phone_query' => true,
        'location_return_appointments_phone' => '501-3',
    ]);

    expect($scope)->toBe(location_phone_scope_card([
        'location_appointments_query' => '1',
        'location_phone_link' => '<a href="tel:" class="icon-phone" data-categorytitle="Telephone Number" data-itemtitle="Clinic A" data-typetitle="Appointment Phone Number for New and Returning Patients"></a>',
        'location_phone_link_data_typetitle' => 'Appointment Phone Number for New and Returning Patients',
    ]));
});

test('the tracking label turns a non-breaking space into a space and keeps an ampersand as one', function (): void {
    $scope = location_phone_scope(['__title' => "Smith\u{A0}&amp; Jones"]);

    expect($scope['location_title'])->toBe("Smith\u{A0}&amp; Jones")
        ->and($scope['location_title_attr'])->toBe('Smith &amp; Jones')
        ->and($scope['location_phone_data_itemtitle'])->toBe('Smith &amp; Jones');
});

test('a profile leaves its fax number and no title', function (): void {
    expect(location_phone_scope(['location_phone' => '501-1', 'location_fax' => '501-9'], 'location_profile'))->toBe(location_phone_scope_profile([
        'location_fax' => '501-9',
        'location_fax_format_dash' => 'dash:501-9',
        'location_phone' => '501-1',
        'location_phone_format_dash' => 'dash:501-1',
        'location_phone_link' => location_phone_scope_link('501-1', 'Clinic Phone Number', ''),
    ]));
});

test("a profile of a children's hospital location reads no fax or additional numbers", function (): void {
    $rows = [['location_appointments_text' => 'Billing', 'location_appointments_phone' => '501-6', 'location_appointments_additional_text' => '']];

    expect(location_phone_scope(['location_ac_query' => true, 'location_fax' => '501-9', 'field_location_phone_numbers' => $rows], 'location_profile', $rows))->toBe(location_phone_scope_profile([
        'location_ac_query' => true,
        'location_fax' => '',
        'location_phone_numbers' => '',
    ]));
});

test("a profile leaves the children's hospital links without an item title", function (): void {
    $scope = location_phone_scope([
        'location_appointments_query' => '1',
        'location_ac_query' => true,
        'location_ac_appointments_query' => true,
        'location_ac_appointments_primary' => '501-4',
        'location_ac_appointments_specialty' => '501-5',
    ], 'location_profile');

    expect($scope['location_ac_appointments_primary_link'])->toBe(location_phone_scope_link('501-4', "Arkansas Children's Primary Care Appointments Phone Number", ''))
        ->and($scope['location_ac_appointments_specialty_link'])->toBe(location_phone_scope_link('501-5', "Arkansas Children's Specialty Care Appointments Phone Number", ''))
        ->and($scope['profile_phone_schema'])->toBe([]);
});

test('a profile leaves the new and returning patient links without an item title', function (): void {
    $scope = location_phone_scope([
        'location_appointments_query' => '1',
        'location_clinic_phone_query' => true,
        'location_new_appointments_phone' => '501-2',
        'field_location_appointment_phone_query' => true,
        'location_return_appointments_phone' => '501-3',
    ], 'location_profile');

    expect($scope['location_new_appointments_phone_link'])->toBe(location_phone_scope_link('501-2', 'Appointment Phone Number for New and Returning Patients', ''))
        ->and($scope['location_return_appointments_phone_link'])->toBe(location_phone_scope_link('501-3', 'Appointment Phone Number for Returning Patients', ''));
});
