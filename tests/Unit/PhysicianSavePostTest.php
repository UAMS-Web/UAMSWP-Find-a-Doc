<?php

/**
 * Pins what physician_save_post() does today, before the pending changes to it.
 *
 * Several tests assert behavior that looks wrong (PHP warnings on a sparse POST, on a
 * degree term that does not exist, on a save with no locations). They are the baseline
 * those changes are measured against: a change that alters one of them should change
 * the test in the same pull request, on purpose.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/acf-functions.php';

/**
 * A POST 'acf' array with every key the function reads, all empty.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function uamswp_test_save_input(array $overrides = []): array
{
    return $overrides + [
        'field_physician_first_name' => 'Leonard',
        'field_physician_middle_name' => '',
        'field_physician_last_name' => 'McCoy',
        'field_physician_pedigree' => '',
        'field_physician_degree' => [],
        'field_physician_expertise' => [],
        'field_physician_conditions_cpt' => [],
        'field_physician_treatments_cpt' => [],
        'field_physician_locations' => [10],
    ];
}

/**
 * Run the function over an input and return what it left in $_POST['acf'] and the warnings it raised.
 *
 * @param  array<string, mixed>  $acf
 * @return array{0: array<string, mixed>, 1: array<int, string>}
 */
function uamswp_test_run_save(array $acf, string $post_type = 'provider'): array
{
    uamswp_test_acf_functions();

    when('get_post_type')->justReturn($post_type);

    $_POST = ['acf' => $acf];

    $warnings = uamswp_test_capture_warnings(function (): void {
        physician_save_post(123);
    });

    return [$_POST['acf'], $warnings];
}

beforeEach(function (): void {
    $this->post_backup = $_POST;

    // One location with a region and a portal, unless a test says otherwise.
    when('get_field')->alias(fn (string $name, $post_id) => match ($name) {
        'location_region' => 'Central Arkansas',
        'location_portal' => 'UAMS Health',
        default => null,
    });
    when('get_the_title')->alias(fn ($id) => 'Title '.$id);
});

afterEach(function (): void {
    $_POST = $this->post_backup;
});

test('it is hooked to acf/save_post at priority 5', function (): void {
    expect(uamswp_test_acf_functions()['actions']['acf/save_post|physician_save_post|5'])->toBeTrue();
});

test('it leaves the data alone when no ACF data was posted', function (): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn('provider');
    $_POST = ['acf' => []];

    physician_save_post(123);

    expect($_POST)->toBe(['acf' => []]);
});

test('it leaves the data alone for a post type other than provider', function (): void {
    [$acf, $warnings] = uamswp_test_run_save(uamswp_test_save_input(), 'location');

    expect($acf)->toBe(uamswp_test_save_input())
        ->and($warnings)->toBe([]);
});

test('it builds the full name from first and last name', function (): void {
    [$acf, $warnings] = uamswp_test_run_save(uamswp_test_save_input());

    expect($acf['field_physician_full_name'])->toBe('Leonard McCoy')
        ->and($warnings)->toBe([]);
});

test('it adds the middle name, the pedigree after a non-breaking space entity, and the degrees', function (): void {
    when('get_term')->alias(fn ($id, $taxonomy) => (object) ['name' => ['1' => 'M.D.', '2' => 'Ph.D.'][(string) $id]]);

    [$acf, $warnings] = uamswp_test_run_save(uamswp_test_save_input([
        'field_physician_middle_name' => 'H.',
        'field_physician_pedigree' => 'Jr.',
        'field_physician_degree' => [1, 2],
    ]));

    expect($acf['field_physician_full_name'])->toBe('Leonard H. McCoy&nbsp;Jr., M.D., Ph.D.')
        ->and($warnings)->toBe([]);
});

test('it joins the degree names in posted order and leaves a trailing separator off', function (): void {
    when('get_term')->alias(fn ($id, $taxonomy) => (object) ['name' => 'Degree '.$id]);

    [$acf] = uamswp_test_run_save(uamswp_test_save_input(['field_physician_degree' => [3, 1, 2]]));

    expect($acf['field_physician_full_name'])->toBe('Leonard McCoy, Degree 3, Degree 1, Degree 2');
});

test('it skips a degree term that does not exist without a warning and still builds the name', function (mixed $term): void {
    when('get_term')->justReturn($term);
    when('is_wp_error')->alias(fn ($value): bool => $value instanceof stdClass && isset($value->errors));

    [$acf, $warnings] = uamswp_test_run_save(uamswp_test_save_input(['field_physician_degree' => [9]]));

    expect($warnings)->toBe([])
        ->and($acf['field_physician_full_name'])->toBe('Leonard McCoy');
})->with([
    'null' => [null],
    'false' => [false],
    'an error object' => [(object) ['errors' => ['invalid_term' => ['Invalid taxonomy.']]]],
]);

test('it builds the filter list from the titles of the expertise, condition and treatment posts', function (): void {
    [$acf, $warnings] = uamswp_test_run_save(uamswp_test_save_input([
        'field_physician_expertise' => [1, 2],
        'field_physician_conditions_cpt' => [3],
        'field_physician_treatments_cpt' => [4, 5],
    ]));

    expect($acf['field_physician_asp_filter'])->toBe('Title 1, Title 2, Title 3, Title 4, Title 5')
        ->and($warnings)->toBe([]);
});

test('it keeps both separators in the filter list when a group is empty', function (): void {
    [$acf] = uamswp_test_run_save(uamswp_test_save_input([
        'field_physician_conditions_cpt' => [3],
    ]));

    expect($acf['field_physician_asp_filter'])->toBe(', Title 3, ');
});

test('it stores the region of every location and the portal of the first location only', function (): void {
    when('get_field')->alias(fn (string $name, $post_id) => match ($name) {
        'location_region' => 'Region '.$post_id,
        'location_portal' => 'Portal '.$post_id,
        default => null,
    });

    [$acf, $warnings] = uamswp_test_run_save(uamswp_test_save_input(['field_physician_locations' => [10, 20]]));

    expect($acf['field_physician_region'])->toBe(['Region 10', 'Region 20'])
        ->and($acf['field_physician_portal'])->toBe('Portal 10')
        ->and($warnings)->toBe([]);
});

test('it stores a region once when several locations share it', function (): void {
    when('get_field')->alias(fn (string $name, $post_id) => match ($name) {
        'location_region' => 65,
        'location_portal' => 'Portal '.$post_id,
        default => null,
    });

    [$acf, $warnings] = uamswp_test_run_save(uamswp_test_save_input(['field_physician_locations' => [10, 20]]));

    expect($acf['field_physician_region'])->toBe([65])
        ->and($warnings)->toBe([]);
});

test('it keeps each region once, in first-seen order, when locations repeat and mix regions', function (): void {
    when('get_field')->alias(fn (string $name, $post_id) => match ($name) {
        'location_region' => [10 => 66, 20 => 65, 30 => 66][$post_id],
        default => null,
    });

    [$acf] = uamswp_test_run_save(uamswp_test_save_input(['field_physician_locations' => [10, 20, 30]]));

    expect($acf['field_physician_region'])->toBe([66, 65]);
});

test('it skips a location with no region or portal value', function (): void {
    when('get_field')->alias(fn (string $name, $post_id) => $post_id === 20 ? 'Value '.$post_id : null);

    [$acf] = uamswp_test_run_save(uamswp_test_save_input(['field_physician_locations' => [10, 20]]));

    expect($acf['field_physician_region'])->toBe(['Value 20'])
        ->and($acf['field_physician_portal'])->toBe('Value 20');
});

test('it raises no warning and stores null for the portal when no location has one', function (): void {
    when('get_field')->alias(fn (string $name, $post_id) => $name === 'location_region' ? 'Central Arkansas' : null);

    [$acf, $warnings] = uamswp_test_run_save(uamswp_test_save_input());

    expect($warnings)->toBe([])
        ->and($acf['field_physician_region'])->toBe(['Central Arkansas'])
        ->and($acf['field_physician_portal'])->toBeNull();
});

test('it raises no warning and stores null region and portal when the provider has no locations', function (): void {
    [$acf, $warnings] = uamswp_test_run_save(uamswp_test_save_input(['field_physician_locations' => []]));

    expect($warnings)->toBe([])
        ->and($acf['field_physician_region'])->toBeNull()
        ->and($acf['field_physician_portal'])->toBeNull();
});

test('it raises no warning for a missing posted key and reads the value as empty', function (): void {
    [$acf, $warnings] = uamswp_test_run_save(['field_physician_first_name' => 'Leonard']);

    expect($warnings)->toBe([])
        ->and($acf['field_physician_full_name'])->toBe('Leonard ')
        ->and($acf['field_physician_asp_filter'])->toBe(', , ');
});

test('it writes only to the posted data and never to the database', function (): void {
    Functions\expect('update_post_meta')->never();
    Functions\expect('update_field')->never();
    Functions\expect('wp_update_post')->never();

    $before = uamswp_test_save_input();
    [$acf] = uamswp_test_run_save($before);

    expect(array_keys(array_diff_key($acf, $before)))->toBe([
        'field_physician_full_name',
        'field_physician_asp_filter',
        'field_physician_region',
        'field_physician_portal',
    ]);
});
