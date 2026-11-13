<?php

/**
 * The provider listing templates under templates/: the image page, the MyChart provider list and the Doximity
 * list. Each registers the Genesis removals that strip the page down to its one table or image, and a loop
 * function that prints it.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/gmb-page.php';
require_once __DIR__.'/../Support/templates.php';

/**
 * The calls to remove_action() a listing template makes, in order.
 *
 * @param  'image'|'list'  $variant  The image page removes the theme scripts; the two lists keep the primary navigation out and remove the tag manager.
 * @return list<array<int, mixed>>
 */
function listing_removals(string $variant): array
{
    $removals = [
        ['genesis_after_header', 'genesis_do_nav'],
        ['genesis_header', 'genesis_do_header'],
        ['genesis_header', 'genesis_header_markup_open', 5],
        ['genesis_header', 'genesis_header_markup_close', 15],
    ];

    if ($variant === 'list') {
        $removals[] = ['genesis_after_header', 'custom_nav_menu', 5];
    }

    $removals[] = ['genesis_before_footer', 'genesis_footer_widget_areas'];
    $removals[] = ['genesis_footer', 'genesis_do_footer'];
    $removals[] = ['genesis_footer', 'genesis_footer_markup_open', 5];
    $removals[] = ['genesis_footer', 'genesis_footer_markup_close', 15];
    $removals[] = ['genesis_before_loop', 'genesis_do_breadcrumbs'];
    $removals[] = ['genesis_after_header', 'genesis_do_breadcrumbs'];
    $removals[] = ['genesis_after_header', 'sp_breadcrumb_after_header'];
    $removals[] = ['genesis_before_header', 'genesis_skip_links', 5];
    $removals[] = ['genesis_header', 'uamswp_site_image', 5];
    $removals[] = ['genesis_after_header', 'genesis_do_breadcrumbs'];
    $removals[] = ['genesis_entry_header', 'genesis_do_post_title'];
    $removals[] = ['genesis_before_header', 'uams_toggle_search', 12];
    $removals[] = ['genesis_before_header', 'uamswp_skip_links', 5];

    if ($variant === 'image') {
        $removals[] = ['wp_enqueue_scripts', 'uamswp_theme_scripts'];
    } else {
        $removals[] = ['wp_head', 'uamswp_gtm_1'];
        $removals[] = ['genesis_before', 'uamswp_gtm_2'];
    }

    $removals[] = ['genesis_loop', 'genesis_do_loop'];

    return $removals;
}

test('a listing template strips the page down and registers its one loop function', function (string $template, string $variant, string $function): void {
    $calls = uamswp_test_template($template);

    expect($calls['remove_action'])->toBe(listing_removals($variant))
        ->and($calls['add_action'])->toBe([['wp_enqueue_scripts', 'child_dequeue_skip_links'], ['genesis_loop', $function]])
        ->and($calls['add_filter'])->toBe([['genesis_pre_get_option_site_layout', '__genesis_return_full_width_content'], ['wp_nav_menu', '__return_false']])
        ->and($calls['remove_filter'])->toBe([])
        ->and($calls['genesis'])->toBe(1);
})->with([
    'the image page' => ['provider-image', 'image', 'display_provider_image_provider_image'],
    'the MyChart list' => ['mychart-list-provider', 'list', 'display_provider_image_mychart_list_provider'],
    'the Doximity list' => ['doximity-list', 'list', 'display_provider_image_doximity_list'],
]);

/**
 * Run a template's loop function over the providers the query will find, and return what it printed.
 *
 * @param  array<int, int>  $ids
 */
function listing_print(string $function, array $ids = []): string
{
    WP_Query::$queue = [$ids];
    WP_Query::$queries = [];

    ob_start();
    $function();

    return (string) ob_get_clean();
}

/**
 * The printed markup with the whitespace between tags removed, so a test states the cells and not the indentation.
 */
function listing_compact(string $html): string
{
    return trim((string) preg_replace('/>\s+</', '><', $html));
}

/**
 * The table rows of the body, without the header rows.
 */
function listing_body_rows(string $html): string
{
    $compact = listing_compact($html);
    $start = strpos($compact, '<tbody>');
    $end = strpos($compact, '</tbody>');

    return $start === false || $end === false ? '' : substr($compact, $start + 7, $end - $start - 7);
}

afterEach(function (): void {
    WP_Query::$queue = [];
    WP_Query::$queries = [];
});

test('the image page prints nothing without a provider and an image in the address', function (array $vars): void {
    uamswp_test_template('provider-image');
    gmb_world([]);
    when('get_query_var')->alias(static fn (string $name): mixed => $vars[$name] ?? '');

    expect(listing_print('display_provider_image_provider_image'))->toBe('')
        ->and(WP_Query::$queries)->toBe([]);
})->with([
    'neither' => [[]],
    'a provider only' => [['provid' => '12']],
    'an image only' => [['image' => '1']],
]);

test('the image page prints the full size featured image of the provider', function (): void {
    uamswp_test_template('provider-image');
    gmb_world([]);
    when('get_query_var')->alias(static fn (string $name): mixed => ['provid' => '12', 'image' => '1'][$name] ?? '');
    when('get_the_post_thumbnail')->alias(static fn (int $id, string $size): string => "<img src=\"thumb-{$id}-{$size}\" />");

    $html = listing_print('display_provider_image_provider_image', [12]);

    expect($html)->toBe('<img src="thumb-12-full" />')
        ->and(WP_Query::$queries)->toBe([['post_type' => 'provider', 'p' => '12']]);
});

test('the image page prints the placeholder when the provider is not found, and finds none for a provider that is not a number', function (string $provider, array $ids, array $query): void {
    uamswp_test_template('provider-image');
    gmb_world([]);
    when('get_query_var')->alias(static fn (string $name): mixed => ['provid' => $provider, 'image' => '1'][$name] ?? '');

    $html = listing_print('display_provider_image_provider_image', $ids);

    expect($html)->toBe('<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg" alt="No image available" />')
        ->and(WP_Query::$queries)->toBe([$query]);
})->with([
    'a number with no provider' => ['99', [], ['post_type' => 'provider', 'p' => '99']],
    'a name' => ['ann-lee', [], ['post__in' => [0]]],
]);

/**
 * A MyChart list case: providers keyed by id, with the fields each prints.
 *
 * @param  array<int, array<string, mixed>>  $providers  id => pid, title, slug, resident.
 */
function mychart_html(array $providers): string
{
    $posts = [];
    $fields = [];

    foreach ($providers as $id => $provider) {
        $posts[$id] = ['title' => $provider['title'] ?? 'Ann Lee', 'slug' => $provider['slug'] ?? 'ann-lee'];
        $fields['physician_pid|'.$id] = $provider['pid'] ?? null;
        $fields['physician_resident|'.$id] = $provider['resident'] ?? null;
    }

    gmb_world(['posts' => $posts, 'fields' => $fields]);
    when('get_the_permalink')->alias(static fn (int $id): string => 'https://uamshealth.com/provider/'.($posts[$id]['slug'] ?? '').'/');

    return listing_print('display_provider_image_mychart_list_provider', array_keys($providers));
}

test('the MyChart list says so when there are no providers', function (): void {
    uamswp_test_template('mychart-list-provider');

    expect(mychart_html([]))->toBe('No providers found')
        ->and(WP_Query::$queries)->toBe([['post_type' => 'provider', 'post_status' => 'publish', 'posts_per_page' => '-1', 'orderby' => 'title', 'order' => 'ASC']]);
});

test('the MyChart list prints the header and one row for each provider that is not a resident', function (): void {
    uamswp_test_template('mychart-list-provider');
    $first = gmb_next_id();
    $resident = gmb_next_id();
    $second = gmb_next_id();
    $html = '';

    $warnings = uamswp_test_capture_warnings(function () use (&$html, $first, $resident, $second): void {
        $html = mychart_html([
            $first => ['pid' => '4411', 'title' => 'Lee, Ann', 'slug' => 'ann-lee'],
            $resident => ['pid' => '5', 'title' => 'Roe, Sam', 'slug' => 'sam-roe', 'resident' => 1],
            $second => ['pid' => 0, 'title' => 'Poe, Jo', 'slug' => 'jo-poe'],
        ]);
    });

    $utm = '?utm_source=mychart&utm_medium=link&utm_campaign=clinical_service&utm_term=provider&utm_content=';

    expect(listing_compact($html))->toContain('<thead><tr><th class="no-break">Provider ID</th><th class="no-break">Provider Name</th><th class="no-break">Provider Profile URL</th></tr></thead>')
        ->and(listing_body_rows($html))->toBe(
            '<tr><td class="no-break">4411</td><td class="no-break">Lee, Ann</td><td class="no-break">https://uamshealth.com/provider/ann-lee/'.$utm.'ann-lee</td></tr>'
            .'<tr><td class="no-break"></td><td class="no-break">Poe, Jo</td><td class="no-break">https://uamshealth.com/provider/jo-poe/'.$utm.'jo-poe</td></tr>',
        )
        ->and($warnings)->toBe([]);
});

/**
 * One Doximity list provider and its location, with the fields that make a complete row, and the changes a test makes.
 *
 * @param  array<string, mixed>  $provider  Provider fields by name, replacing the baseline.
 * @param  array<string, mixed>  $location  Location fields by name, replacing the baseline.
 * @param  array<string, string>  $terms
 * @param  array<string, mixed>  $objects
 * @param  array<int, array<int, array<string, string>>>  $contacts  Contact rows by provider id.
 * @param  list<int>|null  $statusLog  When given, receives the id of every post whose status is read, in order.
 * @return array{id: int, location: int, html: string, warnings: array<int, string>}
 */
function doximity_case(array $provider = [], array $location = [], array $terms = [], array $objects = [], ?array $contacts = null, ?int $id = null, ?int $place = null, array $posts = [], ?array &$statusLog = null): array
{
    $id ??= gmb_next_id();
    $place ??= gmb_next_id();

    $provider += [
        'physician_degree' => [501],
        'physician_npi' => '1234567890',
        'physician_affiliation' => [601],
        'physician_locations' => [$place],
        'physician_first_name' => 'Ann',
        'physician_last_name' => 'Lee',
    ];
    $location += [
        'location_address_1' => '1 Main St',
        'location_city' => 'Little Rock',
        'location_state' => 'AR',
        'location_zip' => '72205',
        'location_phone' => '501-555-0100',
        'location_fax' => '501-555-0199',
    ];
    $terms += ['degree:501' => 'M.D.', 'affiliation:601' => 'UAMS Medical Center'];

    $fields = [];

    foreach ($provider as $name => $value) {
        $fields[$name.'|'.$id] = $value;
    }

    foreach ($location as $name => $value) {
        $fields[$name.'|'.$place] = $value;
    }

    $objectsById = [];

    foreach ($objects as $name => $value) {
        $objectsById[$name.'|'.$place] = $value;
    }

    $allPosts = $posts + [$id => ['title' => 'Lee, Ann', 'slug' => 'ann-lee'], $place => ['title' => 'Main Clinic', 'slug' => 'main-clinic']];

    gmb_world([
        'posts' => $allPosts,
        'fields' => $fields,
        'objects' => $objectsById,
        'terms' => $terms,
    ]);

    if ($statusLog !== null) {
        when('get_post_status')->alias(static function (int $post) use ($allPosts, &$statusLog): string|false {
            $statusLog[] = $post;

            return isset($allPosts[$post]) ? (string) ($allPosts[$post]['status'] ?? 'publish') : false;
        });
    }

    $rows = $contacts ?? [];
    $cursor = [];
    $checks = 0;
    when('have_rows')->alias(static function (string $name, int $post) use ($rows, &$cursor, &$checks): bool {
        $cursor[$post] ??= 0;

        // Capped so a loop whose the_row() call is gone ends, and the row it should have read is missing.
        return $checks++ < 100 && isset($rows[$post][$cursor[$post]]);
    });
    when('the_row')->alias(static function () use (&$cursor): void {
        // Advance the cursor of the post being read; have_rows() reads the same one.
        $post = array_key_last($cursor);
        $cursor[$post]++;
    });
    when('get_sub_field')->alias(static function (string $name) use ($rows, &$cursor): string {
        $post = array_key_last($cursor);

        return $rows[$post][$cursor[$post] - 1][$name] ?? '';
    });

    $html = '';
    $warnings = uamswp_test_capture_warnings(function () use (&$html, $id): void {
        $html = listing_print('display_provider_image_doximity_list', [$id]);
    });

    return ['id' => $id, 'location' => $place, 'html' => $html, 'warnings' => $warnings];
}

/**
 * The markup of one Doximity row from its cells.
 *
 * @param  array<string, string>  $cells  Cells by name, replacing the baseline.
 */
function doximity_row(array $cells = []): string
{
    $cells += [
        'npi' => '1234567890',
        'first' => 'Ann',
        'last' => 'Lee',
        'degree' => 'MD',
        'email' => '',
        'facility' => '<span class="no-break">UAMS Medical Center</span>',
        'address_1' => '1 Main St',
        'address_2' => '',
        'city' => 'Little Rock',
        'state' => 'AR',
        'zip' => '72205',
        'phone' => '501-555-0100',
        'fax' => '501-555-0199',
    ];

    $cell = static fn (string $value): string => '<td class="no-break">'.$value.'</td>';

    return '<tr>'.$cell($cells['npi']).$cell($cells['first']).$cell($cells['last']).$cell($cells['degree']).$cell($cells['email'])
        .'<td>'.$cells['facility'].'</td>'
        .$cell($cells['address_1']).$cell($cells['address_2']).$cell($cells['city']).$cell($cells['state']).$cell($cells['zip']).$cell($cells['phone']).$cell($cells['fax'])
        .'<td></td><td></td></tr>';
}

test('the Doximity list says so when there are no providers', function (): void {
    uamswp_test_template('doximity-list');
    gmb_world([]);

    expect(listing_print('display_provider_image_doximity_list'))->toBe('No providers found')
        ->and(WP_Query::$queries)->toBe([['post_type' => 'provider', 'post_status' => 'publish', 'posts_per_page' => '-1', 'orderby' => 'title', 'order' => 'ASC']]);
});

test('the Doximity list prints the two header rows and one row for a complete provider', function (): void {
    uamswp_test_template('doximity-list');

    $case = doximity_case();

    expect(listing_compact($case['html']))->toContain('<thead><tr><th>Required</th><th colspan="4">Basic Information</th><th colspan="8">Address and Phone</th><th colspan="2">(Suggested) Expertise</th></tr>')
        ->and(listing_compact($case['html']))->toContain('<th class="no-break">NPI Number</th><th class="no-break">First Name</th><th class="no-break">Last Name</th><th class="no-break">Credentials (MD or DO)</th><th class="no-break">Email Address</th><th class="no-break">Facility Name</th><th class="no-break">Office Address 1</th><th class="no-break">Office Address 2</th><th class="no-break">Office City</th><th class="no-break">Office State</th><th class="no-break">Office Zip</th><th class="no-break">Phone</th><th class="no-break">Fax</th><th class="no-break">Specialty</th><th class="no-break">Sub-Specialty</th>')
        ->and(listing_body_rows($case['html']))->toBe(doximity_row())
        ->and($case['warnings'])->toBe([]);
});

test('a degree is printed as its short form, and only the first valid one is used', function (array $degrees, string $expected): void {
    uamswp_test_template('doximity-list');
    $terms = ['degree:501' => 'M.D.', 'degree:502' => 'D.O.', 'degree:503' => 'CNP', 'degree:504' => 'FNP-C', 'degree:505' => 'PA', 'degree:506' => 'Ph.D.'];

    $case = doximity_case(['physician_degree' => $degrees], terms: $terms);

    expect(listing_body_rows($case['html']))->toBe($expected === '' ? '' : doximity_row(['degree' => $expected]));
})->with([
    'M.D.' => [[501], 'MD'],
    'D.O.' => [[502], 'DO'],
    'CNP' => [[503], 'NP'],
    'FNP-C' => [[504], 'NP'],
    'PA' => [[505], 'PA'],
    'one that is not listed' => [[506], ''],
    'none' => [[], ''],
    'a listed degree after one that is not' => [[506, 502], 'DO'],
    'two listed degrees' => [[502, 501], 'DO'],
]);

test('a provider with no usable NPI, no affiliation or no published location gets no row', function (string $field, mixed $value): void {
    uamswp_test_template('doximity-list');

    $case = doximity_case([$field => $value]);

    expect(listing_body_rows($case['html']))->toBe('');
})->with([
    'an empty NPI' => ['physician_npi', ''],
    'a zero NPI' => ['physician_npi', '0'],
    'a zero NPI written as a decimal' => ['physician_npi', '0.0'],
    'no affiliation' => ['physician_affiliation', []],
]);

test('a provider whose only location is not published gets no row', function (): void {
    uamswp_test_template('doximity-list');
    $place = gmb_next_id();

    $case = doximity_case(place: $place, posts: [$place => ['title' => 'Closed', 'slug' => 'closed', 'status' => 'draft']]);

    expect(listing_body_rows($case['html']))->toBe('');
});

test('a provider with no locations at all gets no row and raises no warning', function (): void {
    uamswp_test_template('doximity-list');

    $case = doximity_case(['physician_locations' => null]);

    expect(listing_body_rows($case['html']))->toBe('')
        ->and($case['warnings'])->toBe([]);
});

test('the first email in the contact information is printed and the other rows are not', function (): void {
    uamswp_test_template('doximity-list');
    $id = gmb_next_id();

    $case = doximity_case(
        contacts: [$id => [
            ['type' => 'phone', 'information' => '501-555-0000'],
            ['type' => 'email', 'information' => 'ann@uams.edu'],
            ['type' => 'email', 'information' => 'other@uams.edu'],
        ]],
        id: $id,
    );

    expect(listing_body_rows($case['html']))->toBe(doximity_row(['email' => 'ann@uams.edu']));
});

test('every affiliation is listed in its own span, separated by commas', function (): void {
    uamswp_test_template('doximity-list');

    $case = doximity_case(['physician_affiliation' => [601, 602, 603]], terms: ['affiliation:601' => 'UAMS', 'affiliation:602' => 'ACH', 'affiliation:603' => 'VA']);

    expect(listing_body_rows($case['html']))->toBe(doximity_row(['facility' => '<span class="no-break">UAMS</span>, <span class="no-break">ACH</span>, <span class="no-break">VA</span>']));
});

test('the office address 2 is the building, floor and suite, or the older address line 2 when those are empty', function (): void {
    uamswp_test_template('doximity-list');

    $building = doximity_case(
        location: ['location_building' => 701, 'location_suite' => 'Suite 5'],
        terms: ['building:701' => 'Tower A'],
        objects: ['location_building_floor' => ['value' => '2', 'choices' => ['2' => 'Floor 2']]],
    );
    $none = doximity_case(
        location: ['location_building' => 702, 'location_address_2' => 'Old line 2'],
        terms: ['building:702' => '_none'],
    );

    expect(listing_body_rows($building['html']))->toBe(doximity_row(['address_2' => 'Tower A<br />Floor 2<br />Suite 5']))
        ->and(listing_body_rows($none['html']))->toBe(doximity_row(['address_2' => 'Old line 2']));
});

test('only the first published location supplies the office, and an unpublished one before it is passed over', function (): void {
    uamswp_test_template('doximity-list');
    $closed = gmb_next_id();
    $first = gmb_next_id();
    $second = gmb_next_id();
    $id = gmb_next_id();
    $fields = [
        'physician_degree|'.$id => [501],
        'physician_npi|'.$id => '1234567890',
        'physician_affiliation|'.$id => [601],
        'physician_locations|'.$id => [$closed, $first, $second],
        'physician_first_name|'.$id => 'Ann',
        'physician_last_name|'.$id => 'Lee',
    ];

    foreach ([$closed, $first, $second] as $place) {
        $fields += [
            'location_address_1|'.$place => "Street {$place}",
            'location_city|'.$place => 'City',
            'location_state|'.$place => 'AR',
            'location_zip|'.$place => '72205',
            'location_phone|'.$place => "phone-{$place}",
            'location_fax|'.$place => "fax-{$place}",
        ];
    }

    gmb_world([
        'posts' => [
            $id => ['title' => 'Lee, Ann'],
            $closed => ['title' => 'Closed', 'status' => 'draft'],
            $first => ['title' => 'First'],
            $second => ['title' => 'Second'],
        ],
        'fields' => $fields,
        'terms' => ['degree:501' => 'M.D.', 'affiliation:601' => 'UAMS Medical Center'],
    ]);
    when('have_rows')->justReturn(false);

    $html = listing_print('display_provider_image_doximity_list', [$id]);

    expect(listing_body_rows($html))->toBe(doximity_row([
        'address_1' => "Street {$first}",
        'city' => 'City',
        'phone' => "phone-{$first}",
        'fax' => "fax-{$first}",
    ]));
});

test('the office columns are empty when the location has no address, phone or fax', function (): void {
    uamswp_test_template('doximity-list');

    $case = doximity_case(location: [
        'location_address_1' => '',
        'location_city' => '',
        'location_state' => '',
        'location_zip' => '',
        'location_phone' => '',
        'location_fax' => '',
    ]);

    expect(listing_body_rows($case['html']))->toBe(doximity_row(['address_1' => '', 'city' => '', 'state' => '', 'zip' => '', 'phone' => '', 'fax' => '']));
});

test('the MyChart list leaves the provider ID empty for a zero written as text', function (): void {
    uamswp_test_template('mychart-list-provider');
    $id = gmb_next_id();
    $html = '';

    uamswp_test_capture_warnings(function () use (&$html, $id): void {
        $html = mychart_html([$id => ['pid' => '0', 'title' => 'Poe, Jo', 'slug' => 'jo-poe']]);
    });

    expect(listing_body_rows($html))->toStartWith('<tr><td class="no-break"></td><td class="no-break">Poe, Jo</td>');
});

test('the Doximity list stops looking for a published location at the first one', function (): void {
    uamswp_test_template('doximity-list');
    $closed = gmb_next_id();
    $first = gmb_next_id();
    $second = gmb_next_id();
    $id = gmb_next_id();
    $checked = [];

    $case = doximity_case(
        ['physician_locations' => [$closed, $first, $second]],
        id: $id,
        place: $first,
        posts: [$closed => ['title' => 'Closed', 'slug' => 'closed', 'status' => 'draft'], $second => ['title' => 'Second', 'slug' => 'second']],
        statusLog: $checked,
    );

    expect($checked)->toBe([$closed, $first, $closed, $first])
        ->and(listing_body_rows($case['html']))->toBe(doximity_row());
});

test('a floor that is not among the choices adds nothing to the office address 2', function (): void {
    uamswp_test_template('doximity-list');

    $case = doximity_case(
        location: ['location_building' => 701],
        terms: ['building:701' => 'Tower A'],
        objects: ['location_building_floor' => ['value' => '9', 'choices' => ['2' => 'Floor 2']]],
    );

    expect(listing_body_rows($case['html']))->toBe(doximity_row(['address_2' => 'Tower A']));
});
