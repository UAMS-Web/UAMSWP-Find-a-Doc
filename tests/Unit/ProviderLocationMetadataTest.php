<?php

/**
 * Provider location exports retain selected phone values and map payloads.
 *
 * @command  vendor/bin/pest tests/Unit/ProviderLocationMetadataTest.php
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    // Load the public callback with unrelated provider fields left empty.
    when('add_action')->justReturn(true);
    when('add_filter')->justReturn(true);
    require_once dirname(__DIR__, 2).'/includes/class.fad-custom-post.php';
    when('get_post_thumbnail_id')->justReturn(0);
    when('image_sizer')->justReturn();
    when('wp_trim_words')->justReturn('');
    when('esc_js')->returnArg();
    when('get_term')->justReturn();
    when('is_wp_error')->justReturn(false);
    when('get_permalink')->alias(fn (int $id): string => 'https://example.test/content-'.$id.'/');
    when('get_the_title')->alias(fn (int $id): string => 'Content '.$id);
    when('get_post_field')->alias(fn (string $field, int $id): string => $field === 'post_name' ? 'content-'.$id : '');

    // Distinguish the provider's values from the selected location's values.
    $this->providerLocationFields = [
        101 => [
            'physician_locations' => [202],
            'physician_resident_profile_group' => [
                'physician_resident_academic_department' => 0,
                'physician_resident_academic_chief' => false,
                'physician_resident_academic_year' => 0,
            ],
            'location_phone' => 'wrong provider phone',
        ],
        202 => ['location_phone' => '(501) 555-0100 ext 9'],
    ];
    $this->providerLocationMap = false;
    when('get_field')->alias(fn (string $field, mixed $id): mixed => \is_int($id)
        ? ($this->providerLocationFields[$id][$field] ?? false) : false);
    when('get_post_meta')->alias(fn (int $id, string $key): mixed => [$id, $key] === [202, 'location_map']
        ? $this->providerLocationMap : '');
});

test('provider location phone switches retain raw values and gate returning appointments', function (
    bool $clinic,
    bool $returning,
    string $newPhone,
    string $returnPhone,
    array $expected,
): void {
    // Seed even disabled numbers so reading them through the wrong gate fails.
    $this->providerLocationFields[202] += [
        'location_clinic_phone_query' => $clinic,
        'field_location_appointment_phone_query' => $returning,
        'location_new_appointments_phone' => $newPhone,
        'location_return_appointments_phone' => $returnPhone,
    ];

    // Compare the complete row, including labels and absence of map fields.
    $data = get_provider_meta(['id' => 101]);
    expect($data['physician_locations'])->toBe([202 => [
        'link' => 'https://example.test/content-202/',
        'title' => 'Content 202',
        'slug' => 'content-202',
        'locations_phone_title' => 'Clinic Phone Number',
        'location_clinic_phone' => '(501) 555-0100 ext 9',
        'location_phone_text' => $expected[0],
        'location_new_appointments_phone' => $expected[1],
        'location_new_appointments_phonetext' => $expected[2],
        'location_return_appointments_phone' => $expected[3],
        'location_return_appointments_phone_text' => $expected[4],
    ]]);
})->with([
    'both switches disabled' => [false, false, '501.555.0101', '+1 501 555 0102', ['Appointment Phone Number', '', 'New and Returning Patients', '', '']],
    'returning switch requires clinic switch' => [false, true, '501.555.0101', '+1 501 555 0102', ['Appointment Phone Number', '', 'New and Returning Patients', '', '']],
    'new appointments only' => [true, false, '501.555.0101', '+1 501 555 0102', ['Appointment Phone Numbers', '501.555.0101', 'New Patients', '', '']],
    'separate new and returning phones' => [true, true, '501.555.0101', '+1 501 555 0102', ['Appointment Phone Numbers', '501.555.0101', 'New Patients', '+1 501 555 0102', 'Returning Patients']],
    'empty new phone' => [true, true, '', '+1 501 555 0102', ['Appointment Phone Numbers', '', 'New and Returning Patients', '+1 501 555 0102', 'Returning Patients']],
    'empty returning phone' => [true, true, '501.555.0101', '', ['Appointment Phone Numbers', '501.555.0101', 'New Patients', '', '']],
]);

test('provider location directions require array-shaped stored coordinates', function (
    mixed $map,
    array $expected,
): void {
    // Supply a stored map independently from ACF's getter-visible fields.
    $this->providerLocationMap = $map;
    $this->providerLocationFields[202]['location_map'] = ['lat' => 1, 'lng' => 2];

    // Require exact coordinates and link text, or complete omission of map keys.
    $data = get_provider_meta(['id' => 101]);
    $row = $data['physician_locations'][202];
    if ($expected === []) {
        expect($row)->not->toHaveKeys(['location_lat', 'location_lng', 'location_directions']);
    } else {
        foreach ($expected as $key => $value) {
            expect($row[$key])->toBe($value);
        }
    }
})->with([
    'no stored map' => [false, []],
    'scalar stored value' => ['not a map', []],
    'zero latitude is a valid coordinate' => [['lat' => 0.0, 'lng' => -92.32], [
        'location_lat' => 0.0,
        'location_lng' => -92.32,
        'location_directions' => '<a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/0,-92.32" target="_blank" aria-label="Get Directions to Content 202">Get Directions</a>',
    ]],
    'zero longitude is a valid coordinate' => [['lat' => 51.5, 'lng' => 0.0], [
        'location_lat' => 51.5,
        'location_lng' => 0.0,
        'location_directions' => '<a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/51.5,0" target="_blank" aria-label="Get Directions to Content 202">Get Directions</a>',
    ]],
]);

test('providers without selected locations omit the location collection', function (): void {
    // Leave populated location records available but unselected.
    $this->providerLocationFields[101]['physician_locations'] = [];

    expect(get_provider_meta(['id' => 101]))->not->toHaveKey('physician_locations');
});
