<?php

/**
 * The rows the Doximity export in includes/class.fad-gmb-settings-page.php writes, pinned as the code builds them
 * today: who qualifies, and what each of the fifteen columns holds.
 *
 * A provider qualifies with an NPI that is neither empty nor zero, a first degree that is M.D., D.O., CNP or
 * FNP-C, an affiliation and a published location. A provider who does not qualify still adds a row: the row the
 * provider before built, or an empty line when none has been built yet.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/gmb-page.php';

beforeEach(function (): void {
    $this->get_backup = $_GET;
});

afterEach(function (): void {
    $_GET = $this->get_backup;
    WP_Query::$queue = [];
    WP_Query::$queries = [];
});

/** The header row the export writes. */
const DOXIMITY_HEAD = [
    'NPI Number', 'First Name', 'Last Name', 'Credentials (MD or DO)', 'Email Address', 'Facility Name',
    'Office Address 1', 'Office Address 2', 'Office City', 'Office State', 'Office Zip', 'Phone', 'Fax',
    'Specialty', 'Sub-Specialty',
];

/**
 * Stub the world the Doximity export reads, and run it over the given providers.
 *
 * Fields are keyed `name|post id`; a field read from a term object is keyed `name|taxonomy:id`.
 *
 * @param  list<int>  $providers
 * @param  array<string, mixed>  $fields
 * @param  array<string, mixed>  $objects  get_field_object() values, keyed `name|post id`.
 * @param  array<string, string>  $terms  "taxonomy:id" => name.
 * @param  array<int, string>  $statuses  Post status by id; an id not listed is published.
 * @param  array<int, list<array{type: string, information: string}>>  $contacts  Contact rows by provider id.
 * @return list<list<string>> The CSV rows the export wrote, header first.
 */
function doximity_run(array $providers, array $fields, array $objects = [], array $terms = [], array $statuses = [], array $contacts = []): array
{
    uamswp_test_gmb_page();
    gmb_world(['objects' => $objects, 'terms' => $terms]);

    $term_ids = [];
    when('get_term')->alias(static function (int $id, string $taxonomy) use ($terms, &$term_ids): ?object {
        $name = $terms[$taxonomy.':'.$id] ?? null;
        if ($name === null) {
            return null;
        }
        $term = (object) ['name' => $name, 'slug' => strtolower(str_replace(' ', '-', $name))];
        $term_ids[spl_object_id($term)] = $taxonomy.':'.$id;

        return $term;
    });
    when('get_field')->alias(static function (string $name, mixed $post = false) use ($fields, &$term_ids): mixed {
        $key = is_object($post) ? ($term_ids[spl_object_id($post)] ?? '') : (is_scalar($post) ? (string) $post : '');

        return $fields[$name.'|'.$key] ?? null;
    });
    when('get_post_status')->alias(static fn (int $id): string => $statuses[$id] ?? 'publish');

    // The contact information repeater, as ACF reads it: have_rows() says whether a row is left, the_row() moves to it.
    $cursor = [];
    $current = null;
    // A loop that never calls the_row() would ask forever, so asking twenty times without moving fails the test.
    $asked = [];
    when('have_rows')->alias(static function (string $name, int $post) use ($contacts, &$cursor, &$asked): bool {
        $at = $post.':'.($cursor[$post] ?? -1);
        $asked[$at] = ($asked[$at] ?? 0) + 1;
        if ($asked[$at] > 20) {
            throw new RuntimeException('have_rows() was asked twenty times without the_row() moving to a row.');
        }
        if (isset($contacts[$post][($cursor[$post] ?? -1) + 1])) {
            return true;
        }
        unset($cursor[$post]);

        return false;
    });
    when('the_row')->alias(static function () use ($contacts, &$cursor, &$current): array {
        $post = WP_Query::$current;
        $cursor[$post] = ($cursor[$post] ?? -1) + 1;
        $current = $contacts[$post][$cursor[$post]];

        return $current;
    });
    when('get_sub_field')->alias(static function (string $name) use (&$current): mixed {
        return $current[$name] ?? null;
    });

    $run = gmb_export_run('doximity_csv_export', 'valid-download_doximity_csv', $providers);
    expect($run['exit'])->toBe('');

    $output = $run['output'];
    expect(substr($output, 0, 3))->toBe(chr(0xEF).chr(0xBB).chr(0xBF));

    // Each row ends in a newline, and a row with no cells is an empty line.
    $rows = [];
    foreach (explode("\n", substr($output, 3, -1)) as $line) {
        $rows[] = str_getcsv($line, ',', '"', '\\');
    }

    return $rows;
}

/**
 * The fields of a provider who qualifies, with one published location: the provider id, the fields, the field
 * objects and the terms.
 *
 * @return array{0: int, 1: array<string, mixed>, 2: array<string, mixed>, 3: array<string, string>}
 */
function doximity_provider(string $npi = '1234567890'): array
{
    $provider = gmb_next_id();
    $location = gmb_next_id();
    $degree = gmb_next_id();
    $affiliation = gmb_next_id();

    return [
        $provider,
        [
            'physician_degree|'.$provider => [$degree],
            'physician_npi|'.$provider => $npi,
            'physician_affiliation|'.$provider => [$affiliation],
            'physician_locations|'.$provider => [$location],
            'physician_first_name|'.$provider => 'Ann',
            'physician_last_name|'.$provider => 'Lee',
            'location_address_1|'.$location => '4301 W. Markham St.',
            'location_city|'.$location => 'Little Rock',
            'location_state|'.$location => 'AR',
            'location_zip|'.$location => '72205',
            'location_phone|'.$location => '501-686-7000',
            'location_fax|'.$location => '501-686-7001',
        ],
        ['location_building_floor|'.$location => ['value' => '0', 'choices' => ['0' => 'Single-Story Building']]],
        ['degree:'.$degree => 'M.D.', 'affiliation:'.$affiliation => 'Main Hospital'],
    ];
}

test('an export by someone who cannot manage options, or outside WP-Admin, builds and writes nothing', function (bool $can, bool $admin): void {
    uamswp_test_gmb_page();
    when('current_user_can')->alias(static fn (string $capability): bool => $capability === 'manage_options' && $can);
    when('is_admin')->justReturn($admin);
    when('wp_verify_nonce')->justReturn(false);
    WP_Query::$queries = [];

    ob_start();
    $result = doximity_csv_export();
    $output = ob_get_clean();

    expect($result)->toBeFalse()
        ->and($output)->toBe('')
        ->and(WP_Query::$queries)->toBe([]);
})->with([
    'not an administrator' => [false, true],
    'not in WP-Admin' => [true, false],
]);

test('the export queries every published provider by title', function (): void {
    gmb_export_run('doximity_csv_export', 'valid-download_doximity_csv', []);

    expect(WP_Query::$queries)->toBe([[
        'post_type' => 'provider',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'orderby' => 'title',
        'order' => 'ASC',
    ]]);
});

test('the export sends the download headers', function (): void {
    $run = gmb_export_run('doximity_csv_export', 'valid-download_doximity_csv', []);

    expect($run['headers'])->toHaveCount(6)
        ->and(array_slice($run['headers'], 0, 3))->toBe([
            'Cache-Control: must-revalidate, post-check=0, pre-check=0',
            'Content-Description: File Transfer',
            'Content-type: text/csv',
        ])
        ->and($run['headers'][3])->toMatch('/^Content-Disposition: attachment; filename=Doximity_List_\d+\.csv$/')
        ->and(array_slice($run['headers'], 4))->toBe(['Pragma: no-cache', 'Expires: 0']);
});

test('a provider who qualifies is written under the header with every column filled from the first published location', function (): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();

    expect(doximity_run([$provider], $fields, $objects, $terms))->toBe([
        DOXIMITY_HEAD,
        ['1234567890', 'Ann', 'Lee', 'MD', '', 'Main Hospital', '4301 W. Markham St.', '', 'Little Rock', 'AR', '72205', '501-686-7000', '501-686-7001', '', ''],
    ]);
});

test('the names and the first address line are decoded from HTML entities', function (): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $location = $fields['physician_locations|'.$provider][0];
    $fields['physician_first_name|'.$provider] = 'Zo&euml;';
    $fields['physician_last_name|'.$provider] = 'O&#039;Neal';
    $fields['location_address_1|'.$location] = 'Main &amp; 4th';

    $row = doximity_run([$provider], $fields, $objects, $terms)[1];

    expect([$row[1], $row[2], $row[6]])->toBe(['Zoë', "O'Neal", 'Main & 4th']);
});

test('the credential is MD, DO or NP from the first degree that is one of them', function (array $degrees, string $credential): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $ids = [];
    foreach ($degrees as $name) {
        $id = gmb_next_id();
        $terms['degree:'.$id] = $name;
        $ids[] = $id;
    }
    $fields['physician_degree|'.$provider] = $ids;

    expect(doximity_run([$provider], $fields, $objects, $terms)[1][3])->toBe($credential);
})->with([
    'M.D.' => [['M.D.'], 'MD'],
    'D.O.' => [['D.O.'], 'DO'],
    'CNP' => [['CNP'], 'NP'],
    'FNP-C' => [['FNP-C'], 'NP'],
    'a degree that is not one of them, then D.O.' => [['Ph.D.', 'D.O.'], 'DO'],
    'D.O. then M.D.' => [['D.O.', 'M.D.'], 'DO'],
    'CNP then M.D.' => [['CNP', 'M.D.'], 'NP'],
]);

test('a provider who does not qualify writes an empty line when no row has been built before', function (string $case): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $statuses = [];
    $degree = $fields['physician_degree|'.$provider][0];
    match ($case) {
        'no NPI' => $fields['physician_npi|'.$provider] = '',
        'an NPI of zero' => $fields['physician_npi|'.$provider] = '0',
        'an NPI of 00, which compares equal to zero' => $fields['physician_npi|'.$provider] = '00',
        'no degree' => $fields['physician_degree|'.$provider] = [],
        'a degree that is not one of them' => $terms['degree:'.$degree] = 'Ph.D.',
        'a PA, then an M.D.' => [$terms['degree:'.$degree] = 'PA', $fields['physician_degree|'.$provider][] = $degree + 100000, $terms['degree:'.($degree + 100000)] = 'M.D.'],
        'a degree that is not a term' => $terms = array_diff_key($terms, ['degree:'.$degree => true]),
        'no affiliation' => $fields['physician_affiliation|'.$provider] = [],
        'no location' => $fields['physician_locations|'.$provider] = [],
        'only a draft location' => $statuses[$fields['physician_locations|'.$provider][0]] = 'draft',
    };

    expect(doximity_run([$provider], $fields, $objects, $terms, $statuses))->toBe([DOXIMITY_HEAD, [null]]);
})->with([
    'no NPI', 'an NPI of zero', 'an NPI of 00, which compares equal to zero', 'no degree', 'a degree that is not one of them', 'a PA, then an M.D.',
    'a degree that is not a term', 'no affiliation', 'no location', 'only a draft location',
]);

test('a provider who does not qualify repeats the row of the provider before', function (): void {
    [$first, $fields, $objects, $terms] = doximity_provider();
    [$second, $second_fields, $second_objects, $second_terms] = doximity_provider('');

    $rows = doximity_run([$first, $second], $fields + $second_fields, $objects + $second_objects, $terms + $second_terms);

    expect($rows)->toHaveCount(3)
        ->and($rows[2])->toBe($rows[1])
        ->and($rows[1][0])->toBe('1234567890');
});

test('the email address is the first email row of the contact information', function (): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $contacts = [$provider => [
        ['type' => 'phone', 'information' => '501-555-0100'],
        ['type' => 'email', 'information' => 'ann@example.test'],
        ['type' => 'email', 'information' => 'lee@example.test'],
    ]];

    expect(doximity_run([$provider], $fields, $objects, $terms, [], $contacts)[1][4])->toBe('ann@example.test');
});

test('the facility is each affiliation by its AHA name, or its term name, with its AHA ID, joined by commas', function (): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $first = $fields['physician_affiliation|'.$provider][0];
    $second = gmb_next_id();
    $third = gmb_next_id();
    $fields['physician_affiliation|'.$provider] = [$first, $second, $third];
    $terms['affiliation:'.$second] = 'North Clinic';
    $terms['affiliation:'.$third] = 'South Clinic';
    $fields['affiliation_name|affiliation:'.$first] = 'UAMS Medical Center';
    $fields['affiliation_id|affiliation:'.$first] = '6390001';
    $fields['affiliation_id|affiliation:'.$third] = '6390003';

    expect(doximity_run([$provider], $fields, $objects, $terms)[1][5])
        ->toBe('UAMS Medical Center (AHA ID: 6390001), North Clinic, South Clinic (AHA ID: 6390003)');
});

test('an affiliation that is not a term adds an empty name', function (): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $fields['physician_affiliation|'.$provider][] = gmb_next_id();

    expect(doximity_run([$provider], $fields, $objects, $terms)[1][5])->toBe('Main Hospital, ');
});

test('a provider whose only affiliation is not a term still qualifies, with no facility', function (): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $fields['physician_affiliation|'.$provider] = [gmb_next_id()];

    $row = doximity_run([$provider], $fields, $objects, $terms)[1];

    expect([$row[0], $row[5]])->toBe(['1234567890', '']);
});

test('the address comes from the first published location, after any that are not published', function (): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $published = $fields['physician_locations|'.$provider][0];
    $draft = gmb_next_id();
    $later = gmb_next_id();
    $fields['physician_locations|'.$provider] = [$draft, $published, $later];
    foreach ([$draft, $later] as $other) {
        $fields['location_address_1|'.$other] = 'Other '.$other;
        $fields['location_city|'.$other] = 'Conway';
        $objects['location_building_floor|'.$other] = ['value' => '0', 'choices' => []];
    }

    $row = doximity_run([$provider], $fields, $objects, $terms, [$draft => 'draft'])[1];

    expect([$row[6], $row[8]])->toBe(['4301 W. Markham St.', 'Little Rock']);
});

test('the second address line joins the building, the floor and the suite', function (array $location_fields, array $floor, array $extra_terms, string $address_2): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $location = $fields['physician_locations|'.$provider][0];
    foreach ($location_fields as $name => $value) {
        $fields[$name.'|'.$location] = $value;
    }
    $objects['location_building_floor|'.$location] = $floor;

    expect(doximity_run([$provider], $fields, $objects, $terms + $extra_terms)[1][7])->toBe($address_2);
})->with([
    'building, floor and suite' => [['location_building' => 9001, 'location_suite' => 'Suite 200'], ['value' => '3', 'choices' => ['3' => '3rd Floor']], ['building:9001' => 'Tower B'], 'Tower B, 3rd Floor, Suite 200'],
    'the building only' => [['location_building' => 9001], ['value' => '0', 'choices' => ['0' => 'Single-Story Building']], ['building:9001' => 'Tower B'], 'Tower B'],
    'the floor only' => [[], ['value' => '2', 'choices' => ['2' => '2nd Floor']], [], '2nd Floor'],
    'an empty suite is left out' => [['location_building' => 9001, 'location_suite' => ''], ['value' => '0', 'choices' => []], ['building:9001' => 'Tower B'], 'Tower B'],
    'the suite only' => [['location_suite' => 'Suite 5'], ['value' => '0', 'choices' => []], [], 'Suite 5'],
    'the None building is left out' => [['location_building' => 9002, 'location_suite' => 'Suite 5'], ['value' => '0', 'choices' => []], ['building:9002' => '_none'], 'Suite 5'],
    'a building that is not a term is left out' => [['location_building' => 9003, 'location_suite' => 'Suite 5'], ['value' => '0', 'choices' => []], [], 'Suite 5'],
    'a floor with no label is left out' => [['location_suite' => 'Suite 5'], ['value' => '4', 'choices' => ['3' => '3rd Floor']], [], 'Suite 5'],
    'the single-story floor is left out even with a label' => [[], ['value' => '0', 'choices' => ['0' => 'Single-Story Building']], [], ''],
    'the deprecated line when nothing else is set' => [['location_address_2' => 'Room &amp; Board'], ['value' => '0', 'choices' => []], [], 'Room & Board'],
    'the deprecated line is not used when the line is built' => [['location_address_2' => 'Old', 'location_suite' => 'Suite 5'], ['value' => '0', 'choices' => []], [], 'Suite 5'],
]);

test('a location with no city, state, ZIP, phone or fax leaves those columns empty', function (): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $location = $fields['physician_locations|'.$provider][0];
    foreach (['location_address_1', 'location_city', 'location_state', 'location_zip', 'location_phone', 'location_fax'] as $name) {
        unset($fields[$name.'|'.$location]);
    }

    expect(array_slice(doximity_run([$provider], $fields, $objects, $terms)[1], 6))
        ->toBe(['', '', '', '', '', '', '', '', '']);
});

test('a cell that would start a spreadsheet formula is written as text', function (string $name): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $fields['physician_first_name|'.$provider] = $name;

    expect(doximity_run([$provider], $fields, $objects, $terms)[1][1])->toBe("'".$name);
})->with(['=1+1', '+1', '-1', '@SUM(A1)', "\tTab", "\rReturn"]);

test('a cell that only contains a formula character later is written as it is', function (): void {
    [$provider, $fields, $objects, $terms] = doximity_provider();
    $fields['physician_first_name|'.$provider] = 'Ann=Lee';

    expect(doximity_run([$provider], $fields, $objects, $terms)[1][1])->toBe('Ann=Lee');
});
