<?php

/**
 * Location metadata fallback contracts without a WordPress installation.
 *
 * Thirty-seven approved equivalents are ignored in get_location_meta():
 * six structurally unobservable changes, 24 discarded phone-HTML changes,
 * and seven alert comparisons within the schema string domain.
 * Structural cases are redundant photo truth
 * conditions, an overwritten floor initializer, a non-object region
 * fallback, a string-accumulator comparison, and an unread suppression flag.
 *
 * The phone exclusions depend on the string/absent-value contract and the
 * uams-2020 formatter implementation at revision:
 * cd737498b249b60b9d456d07ddbe38ad0dff6811
 * Source repository: https://github.com/UAMS-Web/uams-2020
 * No application-visible effects were found under those bounds; alternate
 * implementations, malformed inputs, PCRE state, and resource failures are
 * outside that audit. The discarded ACF featured-override read remains
 * unexcluded because a supported hook can observe schema-valid values.
 * Both discarded formatter-call mutations also remain enabled: their
 * exclusions would require inline directives rather than standalone ones.
 *
 * @command  vendor/bin/pest tests/Unit/LocationMetadataTest.php
 */

declare(strict_types=1);

use function Brain\Monkey\Filters\expectApplied;
use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    // Load the callback without registering WordPress hooks.
    when('add_action')->justReturn(true);
    when('add_filter')->justReturn(true);
    require_once dirname(__DIR__, 2).'/includes/class.fad-custom-post.php';
    when('get_the_title')->justReturn('Clinic');
    when('get_permalink')->alias(fn (int $id): string => 'https://example.test/location-'.$id);
    when('user_trailingslashit')->alias(fn (string $url): string => rtrim($url, '/').'/');
    when('format_phone_us')->justReturn('');
    when('format_phone_dash')->justReturn('');
    when('get_term')->justReturn();

    // Supply getter-visible groups, not ACF's internal serialized group storage.
    $this->locationFields = [101 => ['location_hours_group' => array_fill_keys([
        'location_hours_variable', 'location_hours_variable_info', 'location_24_7',
        'location_modified_hours', 'location_modified_hours_reason',
        'location_modified_hours_start_date', 'location_modified_hours_end',
        'location_modified_hours_end_date', 'location_modified_hours_group',
        'location_hours', 'location_after_hours', 'location_telemed_query',
        'location_telemed_patients', 'location_telemed_24_7', 'location_telemed_hours',
        'location_telemed_modified_hours_query', 'location_telemed_modified_hours_reason',
        'location_telemed_modified_hours_start_date', 'location_telemed_modified_hours_end',
        'location_telemed_modified_hours_end_date', 'location_telemed_modified_hours_24_7',
    ], false)]];
    $this->locationSourceIds = array_fill_keys([
        'street', 'facility', 'unit', 'map', 'parking', 'directions',
    ], 101);
    $this->locationFieldObjects = [];
    $this->parentExists = true;

    // Keep each input source distinguishable, including resolved parent components.
    when('get_field')->alias(fn (string $field, mixed $id): mixed => is_int($id) || is_string($id)
        ? ($this->locationFields[$id][$field] ?? false) : false);
    when('get_field_object')->alias(fn (string $field, int $id): mixed => $this->locationFieldObjects[$id][$field] ?? false);
    when('uamswp_fad_location_source_ids')->alias(fn (): array => $this->locationSourceIds);
    when('get_post')->alias(fn (int $id): ?object => $this->parentExists && $id === 202
        ? (object) ['ID' => 202, 'post_title' => 'Parent Clinic'] : null);
    when('image_sizer')->alias(fn (int $id, int $width, int $height): array => [$id, $width, $height]);
});

test('location portal metadata resolves the term name without changing its fields', function (
    string $result,
    string $expected_name,
): void {

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

test('location photos follow parent selection and independent override switches', function (
    bool $parentEnabled,
    int $parentId,
    bool $parentExists,
    bool $override,
    bool $wayfindingOverride,
    bool $galleryOverride,
    array $expectedImages,
    bool $expectedParent,
): void {
    // Give child and parent distinct wayfinding photos and ordered galleries.
    $this->parentExists = $parentExists;
    $this->locationFields[101] += [
        'location_parent' => $parentEnabled,
        'location_parent_id' => $parentId,
        'location_image_override_parent' => $override,
        'location_image_override_parent_wayfinding' => $wayfindingOverride,
        'location_image_override_parent_gallery' => $galleryOverride,
        'location_wayfinding_photo' => 11,
        'location_photo_gallery' => [12, 13],
    ];
    $this->locationFields[202] = ['location_wayfinding_photo' => 21, 'location_photo_gallery' => [22, 23]];

    // Check selected sources and every named size, not real theme image rendering.
    $data = get_location_meta(['id' => 101]);
    $photos = [];
    foreach ($expectedImages as $id) {
        $photos[] = ['thumb' => [$id, 60, 45], 'sml' => [$id, 576, 324], 'med' => [$id, 630, 473], 'lrg' => [$id, 992, 558]];
    }

    expect($data['location_photo'])->toBe($photos)
        ->and($data['location_parent'])->toBe([
            'id' => $parentId,
            'title' => $expectedParent ? 'Parent Clinic' : '',
            'url' => $expectedParent ? 'https://example.test/location-202/' : '',
        ]);
})->with([
    'disabled parent' => [false, 202, true, true, true, true, [11, 12, 13], false],
    'missing parent selection' => [true, 0, true, true, true, true, [11, 12, 13], false],
    'deleted parent' => [true, 202, false, true, true, true, [11, 12, 13], false],
    'master override disabled' => [true, 202, true, false, true, true, [21, 22, 23], true],
    'wayfinding only' => [true, 202, true, true, true, false, [11, 22, 23], true],
    'gallery only' => [true, 202, true, true, false, true, [21, 12, 13], true],
    'both overrides' => [true, 202, true, true, true, true, [11, 12, 13], true],
    'neither component override' => [true, 202, true, true, false, false, [21, 22, 23], true],
]);

test('location metadata exports resolved address sources and selected taxonomy names', function (): void {
    // Separate every component's source from both the root and other components.
    $this->locationSourceIds = ['street' => 301, 'facility' => 302, 'unit' => 303, 'map' => 304, 'parking' => 305, 'directions' => 306];
    $this->locationFields[301] = [
        'location_address_1' => '42 Main St', 'location_address_2' => 'West entrance',
        'location_city' => 'Little Rock', 'location_state' => 'AR', 'location_zip' => '72205', 'location_region' => 41,
    ];
    $this->locationFields[302] = ['location_building' => 42];
    $this->locationFields[303] = ['location_suite' => 'Suite 19'];
    $this->locationFields[304] = ['location_map' => ['lat' => 34.7, 'lng' => -92.3]];
    $this->locationFields[305] = [
        'location_parking' => '<p>Use Deck B.</p>',
        'location_parking_map' => ['lat' => 35.25, 'lng' => -93.75],
    ];
    $this->locationFields[306] = ['location_direction' => '<p>Take the west walkway.</p>'];
    $this->locationFieldObjects[303] = ['location_building_floor' => ['value' => '2', 'choices' => ['1' => 'First floor', '2' => 'Second floor']]];
    $this->locationFields[101]['location_type'] = [44, 43];
    when('get_term')->alias(fn (int $id, string $taxonomy): ?object => match ([$id, $taxonomy]) {
        [41, 'region'] => (object) ['slug' => 'central'],
        [42, 'building'] => (object) ['name' => 'Research Tower', 'slug' => 'research-tower'],
        [43, 'location_type'] => (object) ['name' => 'Hospital'],
        [44, 'location_type'] => (object) ['name' => 'Clinic'],
        default => null,
    });

    // Pin public fields against literal expected values and selected term order.
    $data = get_location_meta(['id' => 101]);
    $expected = [
        'location_address_1' => '42 Main St', 'location_address_2' => 'West entrance<br/>',
        'location_city' => 'Little Rock', 'location_state' => 'AR', 'location_zip' => '72205',
        'location_region' => 'central', 'location_building' => 'Research Tower',
        'location_building_floor' => 'Second floor', 'location_suite' => 'Suite 19',
        'location_lat' => 34.7, 'location_lng' => -92.3, 'location_direction' => '<p>Take the west walkway.</p>',
        'location_types' => 'Clinic,Hospital',
        'location_parking' => '<p>Use Deck B.</p>',
        'location_parking_link' => '<a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/35.25,-93.75" target="_blank" aria-label="Get directions to the parking area">Get Directions</a>',
        'location_directions_link' => '<a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/34.7,-92.3" target="_blank" aria-label="Get Directions to Clinic">Get Directions</a>',
    ];
    foreach ($expected as $key => $value) {
        expect($data[$key])->toBe($value);
    }
});

test('location alerts preserve inheritance override concatenation and suppression rules', function (
    string $modification,
    string $heading,
    string $body,
    string $color,
    array $expected,
): void {
    // Supply distinct system/local values so override and concatenation differ.
    $this->locationFields['option'] = [
        'location_alert_heading_system' => 'System heading',
        'location_alert_body_system' => '<p>System body</p>',
        'location_alert_color_system' => 'alert-info',
    ];
    $this->locationFields[101] += [
        'location_alert_modification' => $modification,
        'location_alert_heading' => $heading,
        'location_alert_body' => $body,
        'location_alert_color' => $color,
    ];

    // Compare all exported alert fields rather than intermediate suppression state.
    $data = get_location_meta(['id' => 101]);
    expect([$data['location_alert_title'], $data['location_alert_text'], $data['location_alert_color']])->toBe($expected);
})->with([
    'inherit' => ['inherit', 'Local heading', '<b>Local body</b>', 'alert-danger', ['System heading', '<p>System body</p>', 'alert-info']],
    'override' => ['override', 'Local heading', '<b>Local body</b>', 'alert-danger', ['Local heading', '<b>Local body</b>', 'alert-danger']],
    'empty overrides' => ['override', '', '', 'inherit', ['System heading', '<p>System body</p>', 'alert-info']],
    'heading only override' => ['override', 'Local heading', '', 'inherit', ['Local heading', '<p>System body</p>', 'alert-info']],
    'body only override' => ['override', '', '<b>Local body</b>', 'inherit', ['System heading', '<b>Local body</b>', 'alert-info']],
    'prepend' => ['prepend', 'Local heading', '<b>Local body</b>', 'alert-danger', ['System heading', '<b>Local body</b><p>System body</p>', 'alert-info']],
    'append' => ['append', 'Local heading', '<b>Local body</b>', 'alert-danger', ['System heading', '<p>System body</p><b>Local body</b>', 'alert-info']],
    'empty prepend' => ['prepend', '', '', 'inherit', ['System heading', '<p>System body</p>', 'alert-info']],
    'empty append' => ['append', '', '', 'inherit', ['System heading', '<p>System body</p>', 'alert-info']],
    'suppress' => ['suppress', 'Local heading', '<b>Local body</b>', 'alert-danger', ['', '', 'alert-info']],
]);

test('location metadata reflects valid field observer effects before exporting alerts', function (): void {
    // Keep the featured override valid while making its reader effect observable.
    $this->locationFields[101] += [
        'location_image_override_parent_featured' => '0',
        'location_alert_modification' => 'override',
        'location_alert_heading' => 'Original heading',
        'location_alert_color' => 'inherit',
    ];
    when('get_field')->alias(function (
        string $field,
        mixed $id,
    ): mixed {
        $value = \is_int($id) || \is_string($id)
            ? ($this->locationFields[$id][$field] ?? false) : false;

        // Model the supported name-specific ACF formatting boundary.
        return apply_filters('acf/format_value/name='.$field, $value, $id);
    });

    // Preserve the valid value while changing another getter-visible field.
    expectApplied('acf/format_value/name=location_image_override_parent_featured')->andReturnUsing(function (
        string $value,
        int $id,
    ): string {
        $this->locationFields[$id]['location_alert_heading'] = 'Heading supplied by observer';

        return $value;
    });

    // Pin returned metadata, not the discarded value or a mock call count.
    expect(get_location_meta(['id' => 101])['location_alert_title'])->toBe('Heading supplied by observer');
});

test('empty location metadata omits optional collections and uses public fallbacks', function (): void {
    // Exercise the same public callback with every optional selection absent.
    $data = get_location_meta(['id' => 101]);
    expect($data)->not->toHaveKeys(['location_photo', 'location_additional_phone_numbers', 'location_modified_hours', 'location_hours']);
    expect([$data['location_lat'], $data['location_lng'], $data['location_building'], $data['location_building_floor'], $data['location_types']])->toBe(['', '', '', '', '']);
    expect([$data['location_alert_title'], $data['location_alert_text'], $data['location_alert_color']])->toBe(['', '', 'alert-warning']);

    // Pin empty scalar fallbacks independently of omitted optional collections.
    $expected = [
        'location_address_2' => '',
        'location_region' => '',
        'location_provider_list' => '',
        'location_expertise_list' => '',
        'location_conditions_list' => '',
        'location_treatments_list' => '',
        'location_parking_link' => '',
        'location_directions_link' => '',
    ];
    foreach ($expected as $key => $value) {
        expect($data[$key])->toBe($value);
    }
});

test('location appointment labels follow separate new and returning patient numbers', function (
    bool $separate,
    bool $returning,
    string $newPhone,
    string $returnPhone,
    array $expected,
): void {
    // Formatting is a theme boundary; this callback exports raw phone values.
    $this->locationFields[101] += [
        'location_phone' => '5015550100',
        'location_clinic_phone_query' => $separate,
        'field_location_appointment_phone_query' => $returning,
        'location_new_appointments_phone' => $newPhone,
        'location_return_appointments_phone' => $returnPhone,
        'location_phone_numbers' => [
            ['location_appointments_text' => 'Referrals', 'location_appointments_phone' => '5015550103', 'location_appointments_additional_text' => '<b>Fax first</b>'],
            ['location_appointments_text' => 'After hours', 'location_appointments_phone' => '5015550104', 'location_appointments_additional_text' => false],
        ],
    ];

    // Compare labels and selection-dependent values independently of formatter calls.
    $data = get_location_meta(['id' => 101]);
    expect($data['location_phone'])->toBe('5015550100');
    expect([
        $data['location_phone_text'], $data['location_new_appointments_phone'],
        $data['location_new_appointments_phonetext'], $data['location_return_appointments_phone'],
        $data['location_return_appointments_phone_text'],
    ])->toBe($expected);
    expect($data['location_additional_phone_numbers'])->toBe([
        ['text' => 'Referrals', 'phone' => '5015550103', 'additional_text' => '<b>Fax first</b>'],
        ['text' => 'After hours', 'phone' => '5015550104', 'additional_text' => false],
    ]);
})->with([
    'shared clinic number' => [false, true, '5015550101', '5015550102', ['Appointment Phone Number', '', 'New and Returning Patients', '', '']],
    'new appointments only' => [true, false, '5015550101', '5015550102', ['Appointment Phone Numbers', '5015550101', 'New Patients', '', '']],
    'separate returning appointments' => [true, true, '5015550101', '5015550102', ['Appointment Phone Numbers', '5015550101', 'New Patients', '5015550102', 'Returning Patients']],
    'empty selected numbers' => [true, true, '', '', ['Appointment Phone Numbers', '', 'New and Returning Patients', '', '']],
]);

test('location hours preserve repeater order and pass telemedicine values through', function (): void {
    // Distinguish every renamed telemedicine field with a unique input value.
    $mapping = [
        'location_hours_variable' => 'location_hours_variable',
        'location_hours_variable_info' => 'location_hours_variable_info',
        'location_24_7' => 'location_24_7',
        'location_modified_hours' => 'location_modified',
        'location_modified_hours_reason' => 'location_modified_reason',
        'location_modified_hours_start_date' => 'location_modified_start_date',
        'location_modified_hours_end' => 'location_modified_end',
        'location_modified_hours_end_date' => 'location_modified_end_date',
        'location_after_hours' => 'location_after_hours',
        'location_telemed_query' => 'location_telemed_query',
        'location_telemed_patients' => 'location_telemed_patients',
        'location_telemed_24_7' => 'location_telemed_hours247',
        'location_telemed_hours' => 'location_telemed_hours',
        'location_telemed_modified_hours_query' => 'location_telemed_modified',
        'location_telemed_modified_hours_reason' => 'location_telemed_modified_reason',
        'location_telemed_modified_hours_start_date' => 'location_telemed_modified_start',
        'location_telemed_modified_hours_end' => 'location_telemed_modified_end',
        'location_telemed_modified_hours_end_date' => 'location_telemed_modified_end_date',
        'location_telemed_modified_hours_24_7' => 'location_telemed_modified_hours247',
    ];
    foreach ($mapping as $input => $output) {
        $this->locationFields[101]['location_hours_group'][$input] = 'value for '.$input;
    }

    // Sparse getter rows must become ordered zero-based output collections.
    $this->locationFields[101]['location_hours_group']['location_modified_hours_group'] = [
        3 => ['location_modified_hours_title' => 'Holiday', 'location_modified_hours_information' => '<p>Call first</p>', 'location_modified_hours_times' => '10:00-14:00', 'location_modified_hours_24_7' => false],
        8 => ['location_modified_hours_title' => 'Emergency', 'location_modified_hours_information' => false, 'location_modified_hours_times' => '', 'location_modified_hours_24_7' => true],
    ];
    $this->locationFields[101]['location_hours_group']['location_hours'] = [
        5 => ['day' => 'Wednesday', 'closed' => false, 'open' => '08:30', 'close' => '17:45', 'comment' => 'By appointment'],
        9 => ['day' => 'Monday', 'closed' => true, 'open' => '', 'close' => '', 'comment' => false],
    ];
    $this->locationFields['option']['location_telemed_descr_system'] = '<p>System telemedicine</p>';

    // Pin renamed fields and full rows, including false and empty values.
    $data = get_location_meta(['id' => 101]);
    foreach ($mapping as $input => $output) {
        expect($data[$output])->toBe('value for '.$input);
    }

    expect($data['location_modified_hours'])->toBe([
        ['title' => 'Holiday', 'information' => '<p>Call first</p>', 'times' => '10:00-14:00', '24_7' => false],
        ['title' => 'Emergency', 'information' => false, 'times' => '', '24_7' => true],
    ]);
    expect($data['location_hours'])->toBe([
        ['day' => 'Wednesday', 'closed' => false, 'open' => '08:30', 'close' => '17:45', 'comment' => 'By appointment'],
        ['day' => 'Monday', 'closed' => true, 'open' => '', 'close' => '', 'comment' => false],
    ]);
    expect($data['location_telemed_info'])->toBe('<p>System telemedicine</p>');

    // Preserve actual telemedicine repeater arrays, including their original keys.
    $this->locationFields[101]['location_hours_group']['location_telemed_hours'] = [
        4 => ['day' => 'Friday', 'closed' => false, 'open' => '09:15', 'close' => '16:45'],
        8 => ['day' => 'Monday', 'closed' => true, 'open' => '', 'close' => ''],
    ];
    $data = get_location_meta(['id' => 101]);
    expect($data['location_telemed_hours'])->toBe([
        4 => ['day' => 'Friday', 'closed' => false, 'open' => '09:15', 'close' => '16:45'],
        8 => ['day' => 'Monday', 'closed' => true, 'open' => '', 'close' => ''],
    ]);
});
