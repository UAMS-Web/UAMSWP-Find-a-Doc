<?php

/**
 * The Google Business Profile provider and location tables built in includes/class.fad-gmb-settings-page.php:
 * the header row, which providers and locations get a row, and what each column holds. The CSV export and the
 * Business Profile sync both read these tables, so a changed column shows up in both.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/gmb-page.php';

beforeEach(function (): void {
    uamswp_test_gmb_page();
    $this->wp_query_backup = $GLOBALS['wp_query'] ?? null;
});

afterEach(function (): void {
    $GLOBALS['wp_query'] = $this->wp_query_backup;
    WP_Query::$queue = [];
    WP_Query::$queries = [];
});

/**
 * A world with one provider at one published location, and the data both need for a complete row.
 *
 * @param  array<string, mixed>  $fields  Fields added to, or replacing, the baseline.
 * @param  array<int, array<string, mixed>>  $posts  Posts added to, or replacing, the baseline.
 * @param  array<string, string>  $terms
 * @return array{provider: int, location: int, world: array<string, mixed>}
 */
function gmb_provider_case(array $fields = [], array $posts = [], array $terms = []): array
{
    $provider = gmb_next_id();
    $location = gmb_next_id();

    $world = [
        'posts' => [
            $provider => ['slug' => 'ann-lee', 'title' => 'Ann Lee'],
            $location => ['slug' => 'main-clinic', 'title' => 'Main Clinic'],
        ],
        'fields' => [
            'physician_first_name' => 'Ann',
            'physician_last_name' => 'Lee',
            'physician_title' => 55,
            'physician_locations|'.$provider => [$location],
            'location_address_1' => '1 Main St',
            'location_city' => 'Little Rock',
            'location_state' => 'AR',
            'location_zip' => '72205',
            'location_phone' => '501-555-0100',
            'location_hours_group' => ['location_telemed_query' => 0],
        ],
        'terms' => ['clinical_title:55' => 'Optometrist'],
    ];

    foreach ($posts as $id => $post) {
        $world['posts'][$id] = $post;
    }

    foreach ($fields as $key => $value) {
        $world['fields'][$key] = $value;
    }

    $world['terms'] += $terms;

    return ['provider' => $provider, 'location' => $location, 'world' => $world];
}

/**
 * Build the provider table for a case.
 *
 * @param  array{provider: int, location: int, world: array<string, mixed>}  $case
 * @return array{head: array<int, mixed>, body: array<int, array<int, mixed>>}
 */
function gmb_providers(array $case): array
{
    gmb_world($case['world']);

    return gmb_build('uamswp_fad_gmb_provider_table', [$case['provider']]);
}

test('the provider table is empty when no provider is found', function (): void {
    gmb_world([]);

    expect(gmb_build('uamswp_fad_gmb_provider_table', []))->toBe(['head' => [], 'body' => []]);
});

test('the provider table queries published providers by title and limits to the ids asked for', function (): void {
    gmb_world([]);

    gmb_build('uamswp_fad_gmb_provider_table', []);
    $all = WP_Query::$queries[0];
    WP_Query::$queue = [[]];
    WP_Query::$queries = [];
    uamswp_fad_gmb_provider_table(['12', 0, 13]);
    $limited = WP_Query::$queries[0];

    expect($all)->toBe(['post_type' => 'provider', 'post_status' => 'publish', 'posts_per_page' => '-1', 'orderby' => 'title', 'order' => 'ASC'])
        ->and($limited['post__in'])->toBe([12, 0, 13])
        ->and(array_diff_key($limited, ['post__in' => 1]))->toBe($all);
});

test('the provider table restores the global query it found', function (): void {
    $previous = new WP_Query;
    $GLOBALS['wp_query'] = $previous;
    $case = gmb_provider_case();

    gmb_providers($case);

    expect($GLOBALS['wp_query'])->toBe($previous);
});

test('the provider table header names the columns the export fills, and leaves the others out', function (): void {
    $head = gmb_providers(gmb_provider_case())['head'];

    expect($head)->toBe([
        0 => 'Store code',
        1 => 'Business name',
        2 => 'Address line 1',
        3 => 'Address line 2',
        4 => 'Address line 3',
        5 => 'Address line 4',
        6 => 'Address line 5',
        7 => 'Locality',
        8 => 'Administrative area',
        9 => 'Country / Region',
        10 => 'Postal code',
        11 => 'Latitude',
        12 => 'Longitude',
        13 => 'Primary phone',
        14 => 'Website',
        15 => 'Primary category',
        16 => 'Additional categories',
        17 => 'From the business',
        18 => 'Logo photo',
        19 => 'Cover photo',
        20 => 'Labels',
        22 => 'Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)',
        23 => 'Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)',
        24 => 'Amenities: Restroom (has_restroom)',
        25 => 'Planning: Appointment required (requires_appointments)',
        50 => 'Service options: Has online care (has_video_visits)',
    ]);
});

test('a provider at a published location gets one complete row', function (): void {
    $case = gmb_provider_case();

    $body = gmb_providers($case)['body'];

    expect($body)->toBe([[
        0 => 'ann-lee_main-clinic',
        1 => 'UAMS Health - Ann Lee - Main Clinic',
        2 => '1 Main St',
        3 => 'Main Clinic',
        4 => '',
        5 => '',
        6 => '',
        7 => 'Little Rock',
        8 => 'AR',
        9 => 'US',
        10 => '72205',
        11 => '',
        12 => '',
        13 => '501-555-0100',
        14 => 'https://uamshealth.com/provider/ann-lee/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=provider&utm_content=profile&utm_specs=ann-lee_main-clinic',
        15 => 'Doctor',
        16 => '',
        17 => 'Ann Lee is an optometrist at Main Clinic employed by UAMS Health.',
        18 => UAMS_FAD_ROOT_URL.'assets/img/uams-health-1024x1024.png',
        19 => '',
        20 => '',
        22 => 'Yes',
        23 => 'Yes',
        24 => 'Yes',
        25 => '',
        50 => '',
    ]]);
});

test('a provider with two published locations gets a row for each, and an unpublished one gets none', function (): void {
    $second = gmb_next_id();
    $third = gmb_next_id();
    $case = gmb_provider_case(posts: [
        $second => ['slug' => 'north-clinic', 'title' => 'North Clinic'],
        $third => ['slug' => 'closed-clinic', 'title' => 'Closed Clinic', 'status' => 'draft'],
    ]);
    $case['world']['fields']['physician_locations|'.$case['provider']] = [$case['location'], $third, $second];

    $body = gmb_providers($case)['body'];

    expect(array_column($body, 0))->toBe(['ann-lee_main-clinic', 'ann-lee_north-clinic'])
        ->and(array_column($body, 1))->toBe(['UAMS Health - Ann Lee - Main Clinic', 'UAMS Health - Ann Lee - North Clinic']);
});

test('a provider gets no row when resident, excluded or without a published location', function (string $field, mixed $value): void {
    $case = gmb_provider_case();
    $case['world']['fields'][$field] = $value;
    if ($field === 'physician_locations') {
        unset($case['world']['fields']['physician_locations|'.$case['provider']]);
    }

    expect(gmb_providers($case)['body'])->toBe([]);
})->with([
    'a resident' => ['physician_resident', 1],
    'excluded' => ['physician_gmb_exclude', 1],
    'no locations' => ['physician_locations', []],
]);

test('a provider whose only location is unpublished gets no row', function (): void {
    $case = gmb_provider_case();
    $case['world']['posts'][$case['location']]['status'] = 'draft';

    expect(gmb_providers($case)['body'])->toBe([]);
});

test('degrees go into the business name and an M.D. or D.O. adds Dr. to the fallback description', function (string $degree, string $business, string $description): void {
    $case = gmb_provider_case(
        fields: ['physician_degree' => [5]],
        terms: ['degree:5' => $degree],
    );

    $row = gmb_providers($case)['body'][0];

    expect($row[1])->toBe($business)
        ->and($row[17])->toBe($description);
})->with([
    'M.D.' => ['M.D.', 'UAMS Health - Ann Lee, M.D. - Main Clinic', 'Dr. Ann Lee is an optometrist at Main Clinic employed by UAMS Health.'],
    'D.O.' => ['D.O.', 'UAMS Health - Ann Lee, D.O. - Main Clinic', 'Dr. Ann Lee is an optometrist at Main Clinic employed by UAMS Health.'],
    'Ph.D.' => ['Ph.D.', 'UAMS Health - Ann Lee, Ph.D. - Main Clinic', 'Ann Lee is an optometrist at Main Clinic employed by UAMS Health.'],
]);

test('a middle name and a generational suffix are part of the business name', function (): void {
    $case = gmb_provider_case(['physician_middle_name' => 'Q.', 'physician_pedigree' => 'Jr.']);

    $row = gmb_providers($case)['body'][0];

    expect($row[1])->toBe("UAMS Health - Ann Q. Lee\u{A0}Jr. - Main Clinic");
});

test('the primary category defaults to Doctor and the rest are listed, up to ten in all', function (): void {
    $terms = [];
    $ids = [];
    foreach (range(1, 12) as $n) {
        $terms['gmb_cat_provider:'.(100 + $n)] = 'Category '.$n;
        $ids[] = 100 + $n;
    }

    $none = gmb_providers(gmb_provider_case())['body'][0];
    $one = gmb_providers(gmb_provider_case(['physician_gmb_cat' => [101]], terms: $terms))['body'][0];
    $two = gmb_providers(gmb_provider_case(['physician_gmb_cat' => [101, 102]], terms: $terms))['body'][0];
    $many = gmb_providers(gmb_provider_case(['physician_gmb_cat' => $ids], terms: $terms))['body'][0];

    expect([$none[15], $none[16]])->toBe(['Doctor', ''])
        ->and([$one[15], $one[16]])->toBe(['Category 1', ''])
        ->and([$two[15], $two[16]])->toBe(['Category 1', 'Category 2'])
        ->and([$many[15], $many[16]])->toBe(['Category 1', 'Category 2, Category 3, Category 4, Category 5, Category 6, Category 7, Category 8, Category 9, Category 10']);
});

test('a category name is escaped for the CSV', function (): void {
    $case = gmb_provider_case(['physician_gmb_cat' => [101, 102]], terms: ['gmb_cat_provider:101' => 'Eyes & Ears', 'gmb_cat_provider:102' => 'Nose <b>']);

    $row = gmb_providers($case)['body'][0];

    expect([$row[15], $row[16]])->toBe(['Eyes &amp; Ears', 'Nose &lt;b&gt;']);
});

test('the description is the short bio, then the bio, then a sentence built from the title and location', function (): void {
    $short = gmb_providers(gmb_provider_case(['physician_short_clinical_bio' => "Short <i>bio</i>\r\nhere", 'physician_clinical_bio' => 'Long bio']))['body'][0][17];
    $long = gmb_providers(gmb_provider_case(['physician_clinical_bio' => "<p>Long</p>\nbio &amp; more"]))['body'][0][17];
    $built = gmb_providers(gmb_provider_case(['physician_title' => 56], terms: ['clinical_title:56' => 'Surgeon']))['body'][0][17];

    expect($short)->toBe('Short bio  here')
        ->and($long)->toBe('Long bio & more')
        ->and($built)->toBe('Ann Lee is a surgeon at Main Clinic employed by UAMS Health.');
});

test('a long bio is cut to 747 characters with an ellipsis', function (): void {
    $row = gmb_providers(gmb_provider_case(['physician_clinical_bio' => str_repeat('b', 800)]))['body'][0];
    $exact = gmb_providers(gmb_provider_case(['physician_clinical_bio' => str_repeat('c', 747)]))['body'][0];

    expect($row[17])->toBe(str_repeat('b', 744).'...')
        ->and($exact[17])->toBe(str_repeat('c', 747));
});

test('a provider with no clinical title is described as a health care provider', function (): void {
    $case = gmb_provider_case(['physician_title' => null]);
    $row = [];

    $warnings = uamswp_test_capture_warnings(function () use ($case, &$row): void {
        $row = gmb_providers($case)['body'][0];
    });

    expect($row[17])->toBe('Ann Lee is a health care provider at Main Clinic employed by UAMS Health.')
        ->and($warnings)->toBe(['Uninitialized string offset 0']);
});

test('the description names no location when the location has no title', function (): void {
    $case = gmb_provider_case(posts: []);
    $case['world']['posts'][$case['location']]['title'] = '';

    $row = gmb_providers($case)['body'][0];

    expect($row[17])->toBe('Ann Lee is an optometrist employed by UAMS Health.')
        ->and($row[1])->toBe('UAMS Health - Ann Lee');
});

test('the cover photo is the provider wide image and the labels are the service line', function (): void {
    $case = gmb_provider_case(['physician_image_wide' => 77, 'physician_service_line' => 8], terms: ['service_line:8' => 'Eye Care']);

    $row = gmb_providers($case)['body'][0];

    expect($row[19])->toBe('https://img.test/77-large.jpg')
        ->and($row[20])->toBe('Eye Care');
});

test('the accessibility columns pass the location answers through and default to Yes', function (): void {
    $case = gmb_provider_case();
    $case['world']['fields'] += [
        'has_wheelchair_accessible_entrance' => 'No',
        'has_wheelchair_accessible_restroom' => 'Not Applicable',
        'has_restroom' => 'Not Applicable',
        'requires_appointments' => 'Not Applicable',
    ];

    $row = gmb_providers($case)['body'][0];

    expect([$row[22], $row[23], $row[24], $row[25]])->toBe(['No', '[NOT APPLICABLE]', '[NOT APPLICABLE]', '[NOT APPLICABLE]']);
});

test('the appointment column carries the location answer and the online care column follows telemedicine', function (): void {
    $case = gmb_provider_case(['requires_appointments' => 'Yes', 'location_hours_group' => ['location_telemed_query' => 1]]);

    $row = gmb_providers($case)['body'][0];

    expect($row[25])->toBe('Yes')
        ->and($row[50])->toBe('Yes');
});

test('the address columns come from the location, decoded, with its map and building', function (): void {
    $building = 31;
    $case = gmb_provider_case(
        fields: [
            'location_address_1' => '1 Main &amp; Oak',
            'location_building' => $building,
            'location_map' => ['lat' => '34.7', 'lng' => '-92.3'],
        ],
        terms: ['building:'.$building => 'Tower A'],
    );

    $row = gmb_providers($case)['body'][0];

    expect($row[2])->toBe('1 Main & Oak')
        ->and([$row[3], $row[4]])->toBe(['Tower A', 'Main Clinic'])
        ->and([$row[11], $row[12]])->toBe(['34.7', '-92.3']);
});

test('a building with the _none slug adds no address line', function (): void {
    $case = gmb_provider_case(['location_building' => 32], terms: ['building:32' => '_none']);

    $row = gmb_providers($case)['body'][0];

    expect([$row[3], $row[4]])->toBe(['Main Clinic', '']);
});

test('a child location lists its parent and itself without the place they are in', function (): void {
    $parent = gmb_next_id();
    $case = gmb_provider_case(
        fields: ['location_parent' => 1],
        posts: [$parent => ['slug' => 'parent-clinic', 'title' => 'Parent Clinic in Little Rock']],
    );
    $case['world']['fields']['location_parent_id'] = $parent;
    $case['world']['posts'][$case['location']]['title'] = 'Eye Care at Parent Clinic';
    $case['world']['fields']['location_address_1|'.$parent] = '9 Parent Way';
    unset($case['world']['fields']['location_address_1']);

    $row = gmb_providers($case)['body'][0];

    expect($row[0])->toBe('ann-lee_main-clinic')
        ->and($row[2])->toBe('9 Parent Way')
        ->and([$row[3], $row[4]])->toBe(['Parent Clinic', 'Eye Care'])
        ->and($row[1])->toBe('UAMS Health - Ann Lee - Eye Care at Parent Clinic');
});

test('a location without a flag for the telemedicine group would read the key directly', function (): void {
    $case = gmb_provider_case(['location_hours_group' => []]);
    $row = null;

    $warnings = uamswp_test_capture_warnings(function () use ($case, &$row): void {
        $row = gmb_providers($case)['body'][0] ?? null;
    });

    expect($warnings)->toBe(['Undefined array key "location_telemed_query"'])
        ->and($row[50])->toBe('');
});

/**
 * Build the location table for a case.
 *
 * @param  array<string, mixed>  $fields
 * @param  array<int, array<string, mixed>>  $posts
 * @param  array<string, string>  $terms
 * @param  array<string, mixed>  $objects
 * @param  array<int, int>  $thumbs
 * @return array{id: int, result: array{head: array<int, mixed>, body: array<int, array<int, mixed>>}}
 */
function gmb_locations(array $fields = [], array $posts = [], array $terms = [], array $objects = [], array $thumbs = [], ?int $id = null): array
{
    $id ??= gmb_next_id();

    $world = [
        'posts' => [$id => ['slug' => 'main-clinic', 'title' => 'Main Clinic']] + $posts,
        'fields' => $fields + [
            'location_address_1' => '1 Main St',
            'location_city' => 'Little Rock',
            'location_state' => 'AR',
            'location_zip' => '72205',
            'location_phone' => '501-555-0100',
            'location_hours_group' => ['location_telemed_query' => 0],
        ],
        'terms' => $terms,
        'objects' => $objects,
        'thumbs' => $thumbs,
    ];
    gmb_world($world);

    return ['id' => $id, 'result' => gmb_build('uamswp_fad_gmb_location_table', [$id])];
}

test('the location table is empty when no location is found', function (): void {
    gmb_world([]);

    expect(gmb_build('uamswp_fad_gmb_location_table', []))->toBe(['head' => [], 'body' => []]);
});

test('the location table queries published locations by title and limits to the ids asked for', function (): void {
    gmb_world([]);

    gmb_build('uamswp_fad_gmb_location_table', []);
    $all = WP_Query::$queries[0];
    WP_Query::$queue = [[]];
    WP_Query::$queries = [];
    uamswp_fad_gmb_location_table([14]);

    expect($all)->toBe(['post_type' => 'location', 'post_status' => 'publish', 'posts_per_page' => '-1', 'orderby' => 'title', 'order' => 'ASC'])
        ->and(WP_Query::$queries[0]['post__in'])->toBe([14]);
});

test('the location table header names the columns the export fills', function (): void {
    expect(gmb_locations()['result']['head'])->toBe([
        0 => 'Store code',
        1 => 'Business name',
        2 => 'Address line 1',
        3 => 'Address line 2',
        4 => 'Address line 3',
        5 => 'Address line 4',
        6 => 'Address line 5',
        7 => 'Locality',
        8 => 'Administrative area',
        9 => 'Country / Region',
        10 => 'Postal code',
        11 => 'Latitude',
        12 => 'Longitude',
        13 => 'Primary phone',
        14 => 'Website',
        15 => 'Primary category',
        16 => 'Additional categories',
        17 => 'From the business',
        18 => 'Logo photo',
        19 => 'Cover photo',
        20 => 'Other photos',
        21 => 'Labels',
        23 => 'Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)',
        24 => 'Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)',
        25 => 'Amenities: Restroom (has_restroom)',
        26 => 'Planning: Appointment required (requires_appointments)',
        52 => 'Service options: Has online care (has_video_visits)',
    ]);
});

test('a location gets one complete row', function (): void {
    expect(gmb_locations()['result']['body'])->toBe([[
        0 => 'main-clinic',
        1 => 'Main Clinic',
        2 => '1 Main St',
        3 => '',
        4 => '',
        5 => '',
        6 => '',
        7 => 'Little Rock',
        8 => 'AR',
        9 => 'US',
        10 => '72205',
        11 => '',
        12 => '',
        13 => '501-555-0100',
        14 => 'https://uamshealth.com/location/main-clinic/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=location&utm_content=profile&utm_specs=main-clinic',
        15 => 'Medical Clinic',
        16 => '',
        17 => '',
        18 => UAMS_FAD_ROOT_URL.'assets/img/uams-health-1024x1024.png',
        19 => '',
        20 => '',
        21 => '',
        23 => 'Yes',
        24 => 'Yes',
        25 => 'Yes',
        26 => '',
        52 => '',
    ]]);
});

test('a location marked to be left out gets no row, and the next location still does', function (): void {
    $first = gmb_next_id();
    $second = gmb_next_id();
    gmb_world(['posts' => [$first => ['slug' => 'a', 'title' => 'A'], $second => ['slug' => 'b', 'title' => 'B']], 'fields' => [
        'location_gmb_exclude|'.$first => 1,
        'location_hours_group' => ['location_telemed_query' => 0],
    ]]);

    $body = gmb_build('uamswp_fad_gmb_location_table', [$first, $second])['body'];

    expect(array_column($body, 0))->toBe(['b']);
});

test('the business name carries the UAMS Health prefix when asked and decodes entities', function (): void {
    $plain = gmb_locations(posts: [])['result']['body'][0][1];
    $id = gmb_next_id();
    $prefixed = gmb_locations(['location_gmb_prefix|'.$id => 1], id: $id)['result']['body'][0][1];

    expect($plain)->toBe('Main Clinic')
        ->and($prefixed)->toBe('UAMS Health - Main Clinic');
});

test('the address lines are the building, the floor and the suite, and entities are decoded', function (): void {
    $id = gmb_next_id();
    $result = gmb_locations(
        fields: ['location_building' => 41, 'location_suite' => 'Suite &amp; 5', 'location_address_1' => '1 Main &amp; Oak'],
        terms: ['building:41' => 'Tower A'],
        objects: ['location_building_floor|'.$id => ['value' => '2', 'choices' => ['2' => 'Floor 2']]],
        id: $id,
    )['result']['body'][0];

    expect($result[2])->toBe('1 Main & Oak')
        ->and([$result[3], $result[4], $result[5], $result[6]])->toBe(['Tower A', 'Floor 2', 'Suite & 5', '']);
});

test('a floor with no label or a zero value, and a _none building, add no address line', function (): void {
    $id = gmb_next_id();
    $result = gmb_locations(
        fields: ['location_building' => 42],
        terms: ['building:42' => '_none'],
        objects: ['location_building_floor|'.$id => ['value' => '0', 'choices' => ['0' => 'Ground']]],
        id: $id,
    )['result']['body'][0];

    expect([$result[3], $result[4]])->toBe(['', '']);
});

test('the older address line 2 is used when the new lines are empty', function (): void {
    $result = gmb_locations(['location_address_2' => 'Building 9'])['result']['body'][0];

    expect($result[3])->toBe('Building 9');
});

test('the map gives the latitude and longitude and the region gives the labels', function (): void {
    $result = gmb_locations(
        fields: ['location_map' => ['lat' => '34.7', 'lng' => '-92.3'], 'location_region' => 3],
        terms: ['region:3' => 'Central Arkansas'],
    )['result']['body'][0];

    expect([$result[11], $result[12], $result[21]])->toBe(['34.7', '-92.3', 'Central Arkansas']);
});

test('the primary category defaults to Medical Clinic and the rest are listed, up to ten in all', function (): void {
    $terms = [];
    $ids = [];
    foreach (range(1, 12) as $n) {
        $terms['gmb_cat_location:'.(200 + $n)] = 'Place '.$n;
        $ids[] = 200 + $n;
    }

    $one = gmb_locations(['location_gmb_cat' => [201]], terms: $terms)['result']['body'][0];
    $two = gmb_locations(['location_gmb_cat' => [201, 202]], terms: $terms)['result']['body'][0];
    $many = gmb_locations(['location_gmb_cat' => $ids], terms: $terms)['result']['body'][0];

    expect([$one[15], $one[16]])->toBe(['Place 1', ''])
        ->and([$two[15], $two[16]])->toBe(['Place 1', 'Place 2'])
        ->and([$many[15], $many[16]])->toBe(['Place 1', 'Place 2, Place 3, Place 4, Place 5, Place 6, Place 7, Place 8, Place 9, Place 10']);
});

test('the description is the short description, then the full one, cut to 747 characters', function (): void {
    $short = gmb_locations(['location_short_desc' => "Short <b>one</b>\nline", 'location_about' => 'About'])['result']['body'][0][17];
    $about = gmb_locations(['location_about' => '<p>About &amp; more</p>'])['result']['body'][0][17];
    $long = gmb_locations(['location_about' => str_repeat('a', 800)])['result']['body'][0][17];

    expect($short)->toBe('Short one line')
        ->and($about)->toBe('About & more')
        ->and($long)->toBe(str_repeat('a', 744).'...');
});

test('a location passes its own accessibility answers through and defaults to Yes', function (): void {
    $id = gmb_next_id();
    $row = gmb_locations([
        'has_wheelchair_accessible_entrance|'.$id => 'No',
        'has_wheelchair_accessible_restroom|'.$id => 'Not Applicable',
        'has_restroom|'.$id => 'Not Applicable',
        'requires_appointments|'.$id => 'Not Applicable',
        'location_hours_group' => ['location_telemed_query' => 1],
    ], id: $id)['result']['body'][0];

    expect([$row[23], $row[24], $row[25], $row[26], $row[52]])->toBe(['No', '[NOT APPLICABLE]', '[NOT APPLICABLE]', '[NOT APPLICABLE]', 'Yes']);
});

test('the cover photo is the featured image and the other photos are the wayfinding photo and gallery without repeats', function (): void {
    $id = gmb_next_id();
    $row = gmb_locations(
        fields: ['location_wayfinding_photo|'.$id => 101, 'location_photo_gallery|'.$id => [101, 102]],
        thumbs: [$id => 100],
        id: $id,
    )['result']['body'][0];

    expect($row[19])->toBe('https://img.test/100-large.jpg')
        ->and($row[20])->toBe('https://img.test/101-large.jpg,https://img.test/102-large.jpg');
});

test('with no featured image the first photo is the cover and a lone photo leaves no other photos', function (): void {
    $id = gmb_next_id();
    $row = gmb_locations(fields: ['location_wayfinding_photo|'.$id => 101], id: $id)['result']['body'][0];

    expect($row[19])->toBe('https://img.test/101-large.jpg')
        ->and($row[20])->toBe('');
});

test('a child location is keyed under its parent and takes the parent images unless it overrides them', function (): void {
    $parent = gmb_next_id();
    $child = gmb_next_id();
    $fields = [
        'location_parent|'.$child => 1,
        'location_parent_id|'.$child => $parent,
        'location_wayfinding_photo|'.$parent => 201,
        'location_wayfinding_photo|'.$child => 301,
        'location_photo_gallery|'.$parent => [202],
        'location_photo_gallery|'.$child => [302],
        'location_address_1|'.$parent => '9 Parent Way',
        'has_restroom|'.$parent => 'No',
    ];
    $posts = [$parent => ['slug' => 'parent-clinic', 'title' => 'Parent Clinic']];
    $thumbs = [$parent => 200, $child => 300];

    $inherited = gmb_locations($fields, $posts, thumbs: $thumbs, id: $child)['result']['body'][0];

    $overridden = gmb_locations(
        $fields + [
            'location_image_override_parent|'.$child => 1,
            'location_image_override_parent_featured|'.$child => 1,
            'location_image_override_parent_wayfinding|'.$child => 1,
            'location_image_override_parent_gallery|'.$child => 1,
        ],
        $posts,
        thumbs: $thumbs,
        id: $child,
    )['result']['body'][0];

    expect($inherited[0])->toBe('parent-clinic_main-clinic')
        ->and($inherited[14])->toContain('/location/parent-clinic/main-clinic/?')
        ->and($inherited[2])->toBe('9 Parent Way')
        ->and($inherited[25])->toBe('No')
        ->and([$inherited[19], $inherited[20]])->toBe(['https://img.test/200-large.jpg', 'https://img.test/201-large.jpg,https://img.test/202-large.jpg'])
        ->and([$overridden[19], $overridden[20]])->toBe(['https://img.test/300-large.jpg', 'https://img.test/301-large.jpg,https://img.test/302-large.jpg']);
});

test('each override toggle changes only its own image', function (string $toggle, string $cover, string $others): void {
    $parent = gmb_next_id();
    $child = gmb_next_id();
    $fields = [
        'location_parent|'.$child => 1,
        'location_parent_id|'.$child => $parent,
        'location_wayfinding_photo|'.$parent => 201,
        'location_wayfinding_photo|'.$child => 301,
        'location_photo_gallery|'.$parent => [202],
        'location_photo_gallery|'.$child => [302],
        'location_image_override_parent|'.$child => 1,
        $toggle.'|'.$child => 1,
    ];

    $row = gmb_locations($fields, [$parent => ['slug' => 'parent-clinic', 'title' => 'Parent Clinic']], thumbs: [$parent => 200, $child => 300], id: $child)['result']['body'][0];

    expect([$row[19], $row[20]])->toBe([$cover, $others]);
})->with([
    'featured' => ['location_image_override_parent_featured', 'https://img.test/300-large.jpg', 'https://img.test/201-large.jpg,https://img.test/202-large.jpg'],
    'wayfinding' => ['location_image_override_parent_wayfinding', 'https://img.test/200-large.jpg', 'https://img.test/301-large.jpg,https://img.test/202-large.jpg'],
    'gallery' => ['location_image_override_parent_gallery', 'https://img.test/200-large.jpg', 'https://img.test/201-large.jpg,https://img.test/302-large.jpg'],
]);

test('the override toggles do nothing for a location without a parent', function (): void {
    $id = gmb_next_id();

    $row = gmb_locations(
        fields: [
            'location_image_override_parent|'.$id => 1,
            'location_image_override_parent_featured|'.$id => 1,
            'location_image_override_parent_wayfinding|'.$id => 1,
            'location_image_override_parent_gallery|'.$id => 1,
            'location_wayfinding_photo|'.$id => 101,
            'location_photo_gallery|'.$id => [102],
        ],
        thumbs: [$id => 100],
        id: $id,
    )['result']['body'][0];

    expect([$row[19], $row[20]])->toBe(['https://img.test/100-large.jpg', 'https://img.test/101-large.jpg,https://img.test/102-large.jpg']);
});

// These two define the theme's image functions, which then stay defined for the process, so they run last.
test('the cover photo is sized by the theme when the theme provides it', function (): void {
    when('bis_get_attachment_image')->justReturn('');
    when('image_sizer')->alias(static fn (mixed $id, int $width, int $height, string $x, string $y, string $class): string => "sized:$id:$width:$height:$class");

    $withImage = gmb_provider_case(['physician_image_wide' => 77]);
    $withoutImage = gmb_provider_case();

    $sized = gmb_providers($withImage)['body'][0][19];
    $none = gmb_providers($withoutImage)['body'][0][19];

    expect($sized)->toBe('sized:77:2120:1192:aspect-16-9')
        ->and($none)->toBe('');
});

test('the theme sizes the location photos when it provides the function', function (): void {
    when('bis_get_attachment_image')->justReturn('');
    when('image_sizer')->alias(static fn (mixed $id, int $width, int $height, string $x = '', string $y = '', string $class = ''): string => "sized:$id:$width:$height");

    $id = gmb_next_id();

    $row = gmb_locations(
        fields: ['location_wayfinding_photo|'.$id => 101, 'location_photo_gallery|'.$id => [102]],
        thumbs: [$id => 100],
        id: $id,
    )['result']['body'][0];

    expect($row[19])->toBe('sized:100:2120:1192')
        ->and($row[20])->toBe('sized:101:2120:1192,sized:102:2120:1192');
});
