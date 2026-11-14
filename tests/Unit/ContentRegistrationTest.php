<?php

/**
 * Public content registration contracts and read-only REST field names.
 *
 * Registration snapshots pin the complete arguments passed to WordPress,
 * including explicit defaults, labels, capabilities, menus, and rewrite options.
 * Regenerate them only when a reviewed change intentionally changes that contract.
 * Validate with --ci so missing snapshots fail instead of creating new baselines.
 *
 * @command  vendor/bin/pest --ci tests/Unit/ContentRegistrationTest.php
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    when('add_action')->justReturn(true);
    when('add_filter')->justReturn(true);
    require_once dirname(__DIR__, 2).'/includes/class.fad-custom-post.php';
});

test('content types retain their editor features, visibility, and REST route', function (string $callback, string $type, bool $hierarchical, array $supports, string $rest_base): void {
    $registered = [];
    when('plugin_dir_url')->justReturn('https://example.test/plugin/includes/');
    when('register_post_type')->alias(function (string $name, array $args) use (&$registered): void {
        $registered[$name] = $args;
    });

    $callback();

    expect(array_keys($registered))->toBe([$type]);

    // Pin the complete public registration, not just the common feature subset.
    expect($registered[$type])->toMatchSnapshot();

    foreach ([
        'supports' => $supports,
        'hierarchical' => $hierarchical,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'map_meta_cap' => true,
        'show_in_rest' => true,
        'rest_base' => $rest_base,
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    ] as $key => $expected) {
        expect($registered[$type])->toHaveKey($key)
            ->and($registered[$type][$key])->toBe($expected);
    }
})->with([
    'providers' => ['providers', 'provider', false, ['title', 'author', 'thumbnail', 'revisions'], 'provider'],
    'locations' => ['locations', 'location', true, ['title', 'author', 'thumbnail', 'revisions', 'page-attributes'], 'location'],
    'expertise' => ['expertise_cpt', 'expertise', true, ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions'], 'expertise'],
    'conditions' => ['conditions_cpt', 'condition', false, ['title', 'editor', 'author', 'thumbnail', 'revisions', 'custom-fields'], 'condition'],
    'treatments' => ['treatments_cpt', 'treatment', false, ['title', 'editor', 'author', 'thumbnail', 'revisions', 'custom-fields'], 'treatment'],
    'resources' => ['clinical_resources_cpt', 'clinical-resource', false, ['title', 'author', 'revisions', 'custom-fields'], 'clinical_resource'],
]);

test('all six REST metadata fields are read-only and use their content-specific callback', function (): void {
    $registered = [];
    when('register_rest_field')->alias(function (string $type, string $field, array $args) use (&$registered): void {
        $registered[$type][$field] = $args;
    });

    rest_api_provider_meta();

    $callbacks = [
        'provider' => ['provider_meta', 'get_provider_meta'],
        'location' => ['location_meta', 'get_location_meta'],
        'expertise' => ['expertise_meta', 'get_expertise_meta'],
        'condition' => ['condition_meta', 'get_condition_meta'],
        'treatment' => ['treatment_meta', 'get_treatment_meta'],
        'clinical-resource' => ['resource_meta', 'get_resource_meta'],
    ];

    expect(array_keys($registered))->toEqualCanonicalizing(array_keys($callbacks));
    foreach ($callbacks as $type => [$field, $callback]) {
        expect($registered[$type])->toBe([
            $field => ['get_callback' => $callback, 'update_callback' => null, 'schema' => null],
        ]);
    }
});

test('taxonomy registration retains its associations, hierarchy, and REST exposure', function (string $callback, string $name, array $objects, bool $hierarchical, string $rest_base): void {
    $registered = [];
    when('__')->returnArg();
    when('register_taxonomy')->alias(function (string $taxonomy, array $types, array $args) use (&$registered): void {
        $registered[$taxonomy] = [$types, $args];
    });

    $callback();

    expect(array_keys($registered))->toBe([$name]);
    [$actual_objects, $args] = $registered[$name];
    expect($actual_objects)->toEqualCanonicalizing($objects);
    foreach ([
        'public' => true,
        'show_ui' => true,
        'hierarchical' => $hierarchical,
        'show_in_nav_menus' => false,
        'show_tagcloud' => false,
        'show_admin_column' => false,
        'meta_box_cb' => false,
    ] as $key => $expected) {
        expect($args)->toHaveKey($key)
            ->and($args[$key])->toBe($expected);
    }

    // Rewrite hierarchy is independent of taxonomy hierarchy.
    expect($args['rewrite'])->toBe([
        'slug' => match ($name) {
            'service_line' => 'service-line',
            'academic_position' => 'academic-position',
            'academic_college' => 'college',
            default => $name,
        },
        'with_front' => ! in_array($name, [
            'school', 'residency_year', 'academic_department', 'board', 'certifying_body',
            'association', 'educationtype', 'recognition', 'region', 'location_type',
            'gmb_cat_provider', 'gmb_cat_location', 'building', 'parking',
            'brand_organization_uams', 'brand_organization',
        ], true),
        'hierarchical' => ! in_array($name, [
            'language', 'school', 'residency_year', 'board', 'certifying_body',
            'association', 'educationtype', 'recognition', 'gmb_cat_provider',
            'gmb_cat_location', 'building', 'parking',
        ], true),
    ]);
    expect($args['show_in_quick_edit'] ?? $args['show_ui'])->toBe(in_array($name, [
        'location_type', 'gmb_cat_provider', 'gmb_cat_location',
    ], true));

    expect((bool) ($args['show_in_rest'] ?? false))->toBe($rest_base !== '')
        ->and($args['rest_base'] ?? '')->toBe($rest_base);

    // Preserve every explicit option, including labels and term permissions.
    expect($args)->toMatchSnapshot();
})->with([
    'service line' => ['create_service_line_taxonomy', 'service_line', ['location', 'provider'], true, 'service_line'],
    'degree' => ['create_degrees_taxonomy', 'degree', ['provider'], false, 'medical_degree'],
    'patient type' => ['create_patient_type_taxonomy', 'patient_type', ['location', 'provider'], true, 'patient_type'],
    'clinical title' => ['create_clinical_title_taxonomy', 'clinical_title', ['provider'], true, 'clinical_title'],
    'clinical admin title' => ['create_clinical_admin_title_taxonomy', 'clinical_admin_title', ['provider'], true, 'clinical_admin_title'],
    'affiliation' => ['create_affiliations_taxonomy', 'affiliation', ['provider'], false, 'affiliation'],
    'institute affiliation' => ['create_institute_affiliations_taxonomy', 'institute_affiliation', ['provider'], false, 'institute_affiliation'],
    'language' => ['create_languages_taxonomy', 'language', ['location', 'provider'], false, 'language'],
    'academic position' => ['create_academic_position_taxonomy', 'academic_position', ['provider'], true, ''],
    'academic college' => ['create_academic_college_taxonomy', 'academic_college', ['provider'], true, ''],
    'school' => ['create_schools_taxonomy', 'school', ['provider'], false, ''],
    'residency year' => ['create_residency_years_taxonomy', 'residency_year', ['provider'], false, ''],
    'academic department' => ['create_academic_departments_taxonomy', 'academic_department', ['provider'], true, ''],
    'board' => ['create_boards_taxonomy', 'board', ['provider'], false, ''],
    'certifying body' => ['create_certifying_body_taxonomy', 'certifying_body', ['provider'], false, ''],
    'association' => ['create_associations_taxonomy', 'association', ['provider'], false, ''],
    'education' => ['create_education_taxonomy', 'educationtype', ['provider'], false, ''],
    'portal' => ['create_portal_taxonomy', 'portal', ['location', 'provider'], false, 'portal'],
    'academic title' => ['create_academic_title_taxonomy', 'academic_title', ['provider'], true, 'academic_title'],
    'academic admin title' => ['create_academic_admin_title_taxonomy', 'academic_admin_title', ['provider'], true, 'academic_admin_title'],
    'recognition' => ['create_recognition_taxonomy', 'recognition', ['provider'], false, ''],
    'region' => ['create_region_taxonomy', 'region', ['location'], true, ''],
    'location type' => ['create_location_type_taxonomy', 'location_type', ['location'], true, ''],
    'provider Google category' => ['create_gmb_cat_provider_taxonomy', 'gmb_cat_provider', ['provider'], true, ''],
    'location Google category' => ['create_gmb_cat_location_taxonomy', 'gmb_cat_location', ['location'], true, ''],
    'building' => ['create_building_taxonomy', 'building', ['location'], true, ''],
    'parking' => ['create_parking_taxonomy', 'parking', ['location'], false, ''],
    'UAMS brand organization' => ['create_brand_organization_uams_taxonomy', 'brand_organization_uams', ['location', 'provider'], true, ''],
    'brand organization' => ['create_brand_organization_taxonomy', 'brand_organization', ['location', 'provider'], true, ''],
]);

test('provider image labels describe headshots without changing other editor labels', function (): void {
    $labels = (object) [
        'name' => 'Providers',
        'add_new_item' => 'Add New Provider',
        'featured_image' => 'Featured image',
        'set_featured_image' => 'Set featured image',
        'remove_featured_image' => 'Remove featured image',
        'use_featured_image' => 'Use as featured image',
    ];

    $result = change_featured_image_labels_provider($labels);

    expect((array) $result)->toBe([
        'name' => 'Providers',
        'add_new_item' => 'Add New Provider',
        'featured_image' => 'Headshot',
        'set_featured_image' => 'Set headshot',
        'remove_featured_image' => 'Remove headshot',
        'use_featured_image' => 'Use as headshot',
    ]);
});

test('REST query variables retain existing entries and allow metadata comparisons', function (array $existing): void {
    $result = provider_add_rest_query_vars($existing);

    expect($result)->toBe([...$existing, 'meta_key', 'meta_value', 'meta_compare']);
})->with([
    'empty allow list' => [[]],
    'other query variables' => [['author', 'search', 'orderby']],
    'previous metadata entry' => [['meta_key', 'author']],
]);

test('content editors remove only the configured taxonomy side metaboxes', function (): void {
    // Seed unrelated screens, contexts, and boxes beside every configured box.
    $screens = ['provider', 'location', 'expertise', 'clinical-resource', 'condition', 'treatment'];
    $ids = [
        'conditiondiv', 'treatmentdiv', 'specialtydiv', 'departmentdiv',
        'service_linediv', 'degreediv', 'patient_typediv', 'clinical_titlediv',
        'clinical_admin_titlediv', 'affiliationdiv', 'institute_affiliationdiv',
        'languagediv', 'medical_termsdiv', 'medical_termdiv', 'academic_positiondiv',
        'academic_collegediv', 'schooldiv', 'residency_yeardiv', 'academic_departmentdiv',
        'boarddiv', 'certifying_bodydiv', 'associationdiv', 'educationtypediv', 'portaldiv',
        'academic_titlediv', 'academic_admin_titlediv', 'recognitiondiv', 'regiondiv',
        'location_typediv', 'gmb_cat_providerdiv', 'gmb_cat_locationdiv', 'buildingdiv',
        'parkingdiv', 'brand_organizationdiv', 'brand_organization_uamsdiv',
        'tagsdiv-recognition', 'tagsdiv-medical_procedures', 'custom-post-type-onomies-locations',
    ];
    $boxes = [];
    foreach ([...$screens, 'post'] as $screen) {
        foreach (['side', 'normal', 'advanced'] as $context) {
            $boxes[$screen][$context] = array_fill_keys([...$ids, 'keep-custom-box', 'tagsdiv-region'], true);
        }
    }

    $expected = $boxes;
    foreach ($screens as $screen) {
        $expected[$screen]['side'] = array_fill_keys(['keep-custom-box', 'tagsdiv-region'], true);
    }

    // Model the removal boundary; the integration test verifies WordPress's registry.
    when('remove_meta_box')->alias(function (
        string $id,
        string|array $screen,
        string $context,
    ) use (&$boxes): void {
        foreach ((array) $screen as $name) {
            unset($boxes[$name][$context][$id]);
        }
    });
    remove_provider_meta();

    // Compare the complete remaining editor state rather than invocation counts.
    expect($boxes)->toBe($expected);
});

test('ACF editor styles retain hidden field labels borders and clone spacing', function (): void {
    // Capture the public output without depending on its indentation.
    ob_start();
    try {
        acf_hide_title();
        $output = (string) ob_get_contents();
    } finally {
        ob_end_clean();
    }

    // Keep the entire style structure and duplicate rules, collapsing whitespace only.
    expect(preg_replace('/\s+/', ' ', trim($output)))->toBe(
        '<style> '
        .'.acf-field.hide-acf-field { display: none; } '
        .'.acf-field.hide-acf-title { border: none; padding: 6px 12px; } '
        .'.acf-field.hide-acf-border, .acf-field[data-width]+.acf-field[data-width].hide-acf-border { border-color: transparent; } '
        .'.acf-field.hide-acf-border .acf-taxonomy-field .categorychecklist-holder { border: none; } '
        .'.acf-field.hide-acf-border .acf-taxonomy-field .acf-checkbox-list { padding: 0; } '
        .'.hide-acf-title .acf-label { display: none; } '
        .'.acf-field.pbn { padding-bottom:0; } '
        .'.acf-fields > .uamswp-hide-clone-label.acf-field-clone.acfe-field-clone-layout-block.acfe-seamless-style > .acf-input > .acf-clone-fields > .acf-field:first-child:last-child { padding-top: 0; } '
        .'.acf-fields > .uamswp-hide-clone-label.acf-field-clone.acfe-field-clone-layout-block.acfe-seamless-style > .acf-input > .acf-clone-fields > .acf-field:first-child:last-child > .acf-label > label { display: none; } '
        .'</style>'
    );
});
