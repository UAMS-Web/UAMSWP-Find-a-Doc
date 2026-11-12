<?php

/**
 * The location profile template (templates/single-locations.php): the values it prepares before the markup, such
 * as the description, the parent location, the images, the alert, the closing notice and the prescription text.
 */

declare(strict_types=1);

require_once __DIR__.'/../Support/single-location.php';

test('the meta description is the short description of the location', function (): void {
    $vars = single_location(['location_short_desc' => 'Short.', 'location_about' => '<p>About.</p>']);

    expect($vars['excerpt'])->toBe('Short.');
});

test('the meta description falls back to the start of the about text without its markup', function (): void {
    $vars = single_location(['location_about' => '<p>'.str_repeat('x', 200).'</p>']);

    expect($vars['excerpt'])->toBe(str_repeat('x', 152).'...');
});

test('a location with no description has none', function (): void {
    expect(single_location()['excerpt'])->toBeNull();
});

test('the title for attributes swaps quotes and entities', function (): void {
    $vars = single_location([], ['titles' => [0 => 'The "Heart" Clinic&#8217;s <b>']]);

    expect($vars['page_title'])->toBe('The "Heart" Clinic&#8217;s <b>')
        ->and($vars['page_title_attr'])->toBe("The 'Heart' Clinic's <b>");
});

test('a location with no parent is its own source of images', function (): void {
    $vars = single_location();

    // The main loop has not started yet when the template reads the id, so it is 0.
    expect($vars['post_id'])->toBe(0)
        ->and($vars['parent_location'])->toBe('')
        ->and($vars['parent_title_phrase'])->toBe('');
});

test('a child location reads the parent title and address', function (): void {
    $vars = single_location(
        ['location_parent' => true, 'location_parent_id' => 90],
        ['posts' => [90 => (object) ['ID' => 90, 'post_title' => 'Medical Center']], 'fields_by_id' => [90 => ['location_prepend_the' => true]]],
    );

    expect($vars['post_id'])->toBe(90)
        ->and($vars['parent_title'])->toBe('Medical Center')
        ->and($vars['parent_url'])->toBe('https://example.test/p/90/')
        ->and($vars['parent_title_prepend'])->toBe('the ')
        ->and($vars['parent_title_phrase'])->toBe('the Medical Center');
});

test('a parent without the article has a bare title phrase', function (): void {
    $vars = single_location(
        ['location_parent' => true, 'location_parent_id' => 91],
        ['posts' => [91 => (object) ['ID' => 91, 'post_title' => 'Medical Center']], 'fields_by_id' => [91 => []]],
    );

    expect($vars['parent_title_prepend'])->toBe('')
        ->and($vars['parent_title_phrase'])->toBe('Medical Center');
});

test('a parent flag without a parent id leaves the location on its own', function (): void {
    $vars = single_location(['location_parent' => true]);

    expect($vars['parent_location'])->toBe('')
        ->and($vars['post_id'])->toBe(0);
});

test('the images are the wayfinding photo followed by the gallery', function (): void {
    $vars = single_location(['location_wayfinding_photo' => 501, 'location_photo_gallery' => [502, 503]]);

    expect($vars['location_images'])->toBe([501, 502, 503])
        ->and($vars['location_images_count'])->toBe(3);
});

test('a location with no photos has no images', function (): void {
    $vars = single_location();

    expect($vars['location_images'])->toBe([])
        ->and($vars['location_images_count'])->toBe(0)
        ->and($vars['schema_image'])->toBe('');
});

test('a child location uses its own wayfinding photo and gallery when it overrides the parent', function (): void {
    $fields = ['location_parent' => true, 'location_parent_id' => 92, 'location_image_override_parent' => true, 'location_image_override_parent_wayfinding' => true, 'location_image_override_parent_gallery' => true, 'location_wayfinding_photo' => 601, 'location_photo_gallery' => [602]];
    $options = ['posts' => [92 => (object) ['ID' => 92, 'post_title' => 'Parent']], 'fields_by_id' => [92 => ['location_wayfinding_photo' => 701, 'location_photo_gallery' => [702]]]];

    expect(single_location($fields, $options)['location_images'])->toBe([601, 602]);
});

test('a child location uses the parent photos unless it overrides each kind', function (): void {
    $options = ['posts' => [93 => (object) ['ID' => 93, 'post_title' => 'Parent']], 'fields_by_id' => [93 => ['location_wayfinding_photo' => 701, 'location_photo_gallery' => [702]]]];
    $fields = ['location_parent' => true, 'location_parent_id' => 93, 'location_wayfinding_photo' => 601, 'location_photo_gallery' => [602]];

    expect(single_location($fields, $options)['location_images'])->toBe([701, 702])
        ->and(single_location($fields + ['location_image_override_parent' => true], $options)['location_images'])->toBe([701, 702])
        ->and(single_location($fields + ['location_image_override_parent' => true, 'location_image_override_parent_wayfinding' => true], $options)['location_images'])->toBe([601, 702])
        ->and(single_location($fields + ['location_image_override_parent' => true, 'location_image_override_parent_gallery' => true], $options)['location_images'])->toBe([701, 602]);
});

test('the schema image is the featured image, or else the first location image', function (): void {
    expect(single_location(['location_wayfinding_photo' => 501], ['thumbnail' => 900])['schema_image'])->toBe(900)
        ->and(single_location(['location_wayfinding_photo' => 501])['schema_image'])->toBe(501);
});

test('the location photo is sized for the schema image', function (): void {
    expect(single_location(['location_wayfinding_photo' => 501])['locationphoto'])->toBe('https://example.test/photo-640x480.jpg');
});

test('a location without an image has no photo address', function (): void {
    expect(single_location()['locationphoto'])->toBe(false)
        ->and(single_location(['location_wayfinding_photo' => 0])['locationphoto'])->toBe(false);
});

test('the telemedicine values come from the hours group', function (): void {
    $group = [
        'location_telemed_query' => true,
        'location_telemed_patients' => 'both',
        'location_telemed_24_7' => false,
        'location_telemed_hours' => [['day' => 'Mon']],
        'location_telemed_modified_hours_query' => true,
        'location_telemed_modified_hours_reason' => 'Holiday',
        'location_telemed_modified_hours_start_date' => 'May 1, 2026',
        'location_telemed_modified_hours_end' => true,
        'location_telemed_modified_hours_end_date' => 'May 9, 2026',
        'location_telemed_modified_hours_24_7' => true,
    ];
    $vars = single_location(['location_hours_group' => $group]);

    expect($vars['telemed_query'])->toBeTrue()
        ->and($vars['telemed_patients'])->toBe('both')
        ->and($vars['telemed_hours247'])->toBeFalse()
        ->and($vars['telemed_hours'])->toBe([['day' => 'Mon']])
        ->and($vars['telemed_modified'])->toBeTrue()
        ->and($vars['telemed_modified_reason'])->toBe('Holiday')
        ->and($vars['telemed_modified_start'])->toBe('May 1, 2026')
        ->and($vars['telemed_modified_end'])->toBeTrue()
        ->and($vars['telemed_modified_end_date'])->toBe('May 9, 2026')
        ->and($vars['telemed_modified_hours247'])->toBeTrue()
        ->and(array_count_values($vars['single_location_warnings'])['Trying to access array offset on null'] ?? 0)->toBeLessThan(10);
});

test('a location with no hours group raises an array offset warning for each telemedicine value', function (): void {
    $vars = single_location();

    expect(array_count_values($vars['single_location_warnings'])['Trying to access array offset on null'])->toBeGreaterThanOrEqual(10)
        ->and($vars['telemed_query'])->toBeNull();
});

test('the system telemedicine text and the after hours text come from the options page', function (): void {
    $vars = single_location([], ['option' => ['location_telemed_descr_system' => '<p>Telemedicine.</p>', 'location_afterhours_descr_system' => '<p>After hours.</p>']]);

    expect($vars['telemed_info'])->toBe('<p>Telemedicine.</p>')
        ->and($vars['afterhours_system'])->toBe('<p>After hours.</p>');
});

test('the after hours text has a default for emergencies', function (): void {
    expect(single_location()['afterhours_system'])->toStartWith('<p>If you are in need of urgent or emergency care, call 911');
});

test('the alert is the system alert when the location does not modify it', function (): void {
    $vars = single_location(['location_alert_heading' => 'Local', 'location_alert_body' => 'Local text', 'location_alert_color' => 'red'], ['option' => ['location_alert_heading_system' => 'System', 'location_alert_body_system' => 'System text.', 'location_alert_color_system' => 'blue']]);

    expect($vars['location_alert_title'])->toBe('System')
        ->and($vars['location_alert_text'])->toBe('System text.')
        ->and($vars['location_alert_color'])->toBe('blue');
});

test('an override replaces the title, color and text of the alert', function (): void {
    $fields = ['location_alert_modification' => 'override', 'location_alert_heading' => 'Local', 'location_alert_body' => 'Local text', 'location_alert_color' => 'red'];
    $vars = single_location($fields, ['option' => ['location_alert_heading_system' => 'System', 'location_alert_body_system' => 'System text.', 'location_alert_color_system' => 'blue']]);

    expect($vars['location_alert_title'])->toBe('Local')
        ->and($vars['location_alert_text'])->toBe('Local text')
        ->and($vars['location_alert_color'])->toBe('red');
});

test('an override that inherits the color or leaves values empty keeps the system ones', function (): void {
    $fields = ['location_alert_modification' => 'override', 'location_alert_heading' => '', 'location_alert_body' => '', 'location_alert_color' => 'inherit'];
    $vars = single_location($fields, ['option' => ['location_alert_heading_system' => 'System', 'location_alert_body_system' => 'System text.', 'location_alert_color_system' => 'blue']]);

    expect($vars['location_alert_title'])->toBe('System')
        ->and($vars['location_alert_text'])->toBe('System text.')
        ->and($vars['location_alert_color'])->toBe('blue');
});

test('the local alert text goes before or after the system text', function (): void {
    $option = ['option' => ['location_alert_body_system' => 'System.']];

    expect(single_location(['location_alert_modification' => 'prepend', 'location_alert_body' => 'Local.'], $option)['location_alert_text'])->toBe('Local.System.')
        ->and(single_location(['location_alert_modification' => 'append', 'location_alert_body' => 'Local.'], $option)['location_alert_text'])->toBe('System.Local.')
        ->and(single_location(['location_alert_modification' => 'append', 'location_alert_body' => ''], $option)['location_alert_text'])->toBe('System.')
        ->and(single_location(['location_alert_modification' => 'prepend', 'location_alert_body' => ''], $option)['location_alert_text'])->toBe('System.');
});

test('suppressing the alert clears its title and text', function (): void {
    $vars = single_location(['location_alert_modification' => 'suppress'], ['option' => ['location_alert_heading_system' => 'System', 'location_alert_body_system' => 'System text.']]);

    expect($vars['location_alert_suppress'])->toBeTrue()
        ->and($vars['location_alert_title'])->toBe('')
        ->and($vars['location_alert_text'])->toBe('');
});

test('a location that is not closing has no closing values', function (): void {
    $vars = single_location();

    expect($vars['location_closing_display'])->toBeFalse()
        ->and($vars['location_closing_date_past'])->toBeFalse()
        ->and($vars['location_reopen_date_past'])->toBeFalse()
        ->and($vars['location_closing_telemed'])->toBe('');
});

test('a permanent closing is displayed', function (): void {
    $vars = single_location(['location_closing' => true, 'location_closing_length' => 'permanent', 'location_closing_date' => 'January 1, 2020', 'location_closing_info' => 'Closed.']);

    expect($vars['location_closing_display'])->toBeTrue()
        ->and($vars['location_closing_date_past'])->toBeTrue()
        ->and($vars['location_closing_info'])->toBe('Closed.')
        ->and($vars['location_closing_telemed'])->toBe('');
});

test('a closing that starts in the future is not yet past', function (): void {
    $vars = single_location(['location_closing' => true, 'location_closing_length' => 'permanent', 'location_closing_date' => 'January 1, 2099']);

    expect($vars['location_closing_date_past'])->toBeFalse();
});

test('a temporary closing is displayed until the reopening date passes', function (): void {
    $fields = ['location_closing' => true, 'location_closing_length' => 'temporary', 'location_closing_date' => 'January 1, 2020', 'location_reopen_known' => true, 'location_closing_telemed' => 'yes'];

    $future = single_location($fields + ['location_reopen_date' => 'January 1, 2099']);
    $past = single_location($fields + ['location_reopen_date' => 'January 2, 2020']);

    expect($future['location_closing_display'])->toBeTrue()
        ->and($future['location_reopen_date_past'])->toBeFalse()
        ->and($future['location_closing_telemed'])->toBe('yes')
        ->and($past['location_closing_display'])->toBeFalse()
        ->and($past['location_reopen_date_past'])->toBeTrue();
});

test('a closing of another length is not displayed', function (): void {
    $vars = single_location(['location_closing' => true, 'location_closing_length' => 'other', 'location_closing_date' => 'January 1, 2020']);

    expect($vars['location_closing_display'])->toBeFalse();
});

test('the prescription text depends on the type chosen', function (string $type, string $expected): void {
    $vars = single_location(
        ['location_prescription_query' => true, 'location_prescription_type' => $type, 'location_prescription' => 'Custom text.'],
        ['option' => ['location_prescription_clinic_system' => 'Call the clinic.', 'location_prescription_pharm_system' => 'Call the pharmacy.']],
    );

    expect($vars['prescription'])->toBe($expected)
        ->and($vars['prescription_query'])->toBeTrue();
})->with([
    'the clinic' => ['clinic', 'Call the clinic.'],
    'the pharmacy' => ['pharm', 'Call the pharmacy.'],
    'custom text' => ['custom', 'Custom text.'],
]);

test('the prescription section is off when it has no text', function (): void {
    $vars = single_location(['location_prescription_query' => true, 'location_prescription_type' => 'clinic']);

    expect($vars['prescription'])->toBeNull()
        ->and($vars['prescription_query'])->toBeFalse();
});

test('no prescription query leaves the prescription empty', function (): void {
    $vars = single_location(['location_prescription_type' => 'clinic'], ['option' => ['location_prescription_clinic_system' => 'Call the clinic.']]);

    expect($vars['prescription'])->toBe('');
});

test('the medical ontology is hidden by a region rule', function (): void {
    $fields = ['location_region' => 'north', 'location_service_line' => [7]];
    $rules = [['remove_regions' => ['north'], 'remove_service_lines' => null]];

    expect(single_location($fields, ['rows' => ['remove_ontology_criteria' => $rules]])['hide_medical_ontology'])->toBeTrue()
        ->and(single_location(['location_region' => 'south', 'location_service_line' => [7]], ['rows' => ['remove_ontology_criteria' => $rules]])['hide_medical_ontology'])->toBeFalse();
});

test('the medical ontology is hidden by a service line rule', function (): void {
    $rules = [['remove_regions' => null, 'remove_service_lines' => [7]]];

    expect(single_location(['location_service_line' => [3, 7]], ['rows' => ['remove_ontology_criteria' => $rules]])['hide_medical_ontology'])->toBeTrue()
        ->and(single_location(['location_service_line' => [3]], ['rows' => ['remove_ontology_criteria' => $rules]])['hide_medical_ontology'])->toBeFalse();
});

test('a rule with a region and a service line needs both', function (): void {
    $options = ['rows' => ['remove_ontology_criteria' => [['remove_regions' => ['north'], 'remove_service_lines' => [7]]]]];

    expect(single_location(['location_region' => 'north', 'location_service_line' => [7]], $options)['hide_medical_ontology'])->toBeTrue()
        ->and(single_location(['location_region' => 'north', 'location_service_line' => [3]], $options)['hide_medical_ontology'])->toBeFalse()
        ->and(single_location(['location_region' => 'south', 'location_service_line' => [7]], $options)['hide_medical_ontology'])->toBeFalse();
});

test('a location with no service lines never hides the ontology', function (): void {
    $options = ['rows' => ['remove_ontology_criteria' => [['remove_regions' => ['north'], 'remove_service_lines' => null]]]];

    expect(single_location(['location_region' => 'north'], $options)['hide_medical_ontology'])->toBeFalse();
});

test('the clinical resources query asks for the resources of the location', function (): void {
    single_location(['location_clinical_resources' => [4, 5]]);

    expect(WP_Query::$queries[0])->toBe(['post_type' => 'clinical-resource', 'order' => 'DESC', 'orderby' => 'post_date', 'posts_per_page' => 4, 'post_status' => 'publish', 'post__in' => [4, 5]]);
});

test('the template overrides the theme titles', function (): void {
    single_location();

    expect(uamswp_test_render_hooks()['add_filter'])->toBe([
        ['seopress_titles_desc', 'sp_titles_desc'],
        ['seopress_titles_title', 'uamswp_fad_title_single_locations', 15, 2],
    ]);
});
