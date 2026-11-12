<?php

/**
 * The acf/save_post handlers in includes/class.fad-acf-functions.php other than the provider one: the clinical
 * resource search list, the location image and region copying, the location parent sync, the clinical title
 * inheritance, the bidirectional relationship sync and the post excerpt.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/acf-functions.php';
require_once __DIR__.'/../Support/wp-classes.php';

beforeEach(function (): void {
    $this->post_backup = $_POST;
    $this->post_global = $GLOBALS['post'] ?? null;
});

afterEach(function (): void {
    $_POST = $this->post_backup;
    $GLOBALS['post'] = $this->post_global;
});

/**
 * Make get_post_type() answer for every post.
 */
function acf_save_type(string|false $type): void
{
    when('get_post_type')->justReturn($type);
}

/**
 * Stub get_field() from a map of "name" or "name|post" keys, collecting nothing.
 *
 * @param  array<string, mixed>  $values
 */
function acf_save_fields(array $values): void
{
    when('get_field')->alias(static fn (string $name, mixed $post = false): mixed => $values[$name.'|'.(is_object($post) ? 'term' : (string) $post)] ?? $values[$name] ?? null);
}

test('the clinical resource handler does nothing without submitted data or for another post type', function (array $post, string $type): void {
    uamswp_test_acf_functions();
    acf_save_type($type);
    Functions\expect('get_the_title')->never();
    $_POST = $post;

    resources_save_post(5);

    expect($_POST)->toBe($post);
})->with([
    'no data' => [[], 'clinical-resource'],
    'empty data' => [['acf' => []], 'clinical-resource'],
    'a provider' => [['acf' => ['field_clinical_resource_providers' => [1]]], 'provider'],
]);

test('the clinical resource handler stores one comma list of every related title in the search field', function (): void {
    uamswp_test_acf_functions();
    acf_save_type('clinical-resource');
    when('get_the_title')->alias(static fn (int $id): string => 'T'.$id);
    $_POST = ['acf' => [
        'field_clinical_resource_providers' => ['1', '2'],
        'field_clinical_resource_locations' => [3, 11],
        'field_clinical_resource_aoe' => [4, 5],
        'field_clinical_resource_conditions' => [6, 12],
        'field_clinical_resource_treatments' => [7, 13],
        'field_clinical_resource_related' => [8, 9, 10],
    ]];

    resources_save_post(5);

    expect($_POST['acf']['field_clinical_resource_asp_filter'])->toBe('T1, T2, T3, T11, T4, T5, T6, T12, T7, T13, T8, T9, T10');
});

test('the clinical resource handler leaves a gap for each empty relation', function (): void {
    uamswp_test_acf_functions();
    acf_save_type('clinical-resource');
    when('get_the_title')->alias(static fn (int $id): string => 'T'.$id);
    $_POST = ['acf' => ['field_clinical_resource_providers' => [1]]];

    resources_save_post(5);

    expect($_POST['acf']['field_clinical_resource_asp_filter'])->toBe('T1, , , , , ');
});

test('the location handler does nothing without submitted data or for another post type', function (string $type, array $post): void {
    uamswp_test_acf_functions();
    acf_save_type($type);
    $_POST = $post;

    location_save_post(5);

    expect($_POST)->toBe($post);
})->with([
    'no data' => ['location', []],
    'a provider' => ['provider', ['acf' => ['field_location_featured_image' => 4]]],
]);

test('the location handler copies the featured image to an empty wayfinding photo', function (): void {
    uamswp_test_acf_functions();
    acf_save_type('location');
    $_POST = ['acf' => ['field_location_featured_image' => 4, 'field_location_wayfinding_photo' => '']];

    location_save_post(5);

    expect($_POST['acf']['field_location_wayfinding_photo'])->toBe(4)
        ->and($_POST['acf']['field_location_featured_image'])->toBe(4);
});

test('the location handler copies the wayfinding photo to an empty featured image', function (): void {
    uamswp_test_acf_functions();
    acf_save_type('location');
    $_POST = ['acf' => ['field_location_featured_image' => '', 'field_location_wayfinding_photo' => 6]];

    location_save_post(5);

    expect($_POST['acf']['field_location_featured_image'])->toBe(6);
});

test('the location handler keeps two different images and writes nothing for two empty ones', function (): void {
    uamswp_test_acf_functions();
    acf_save_type('location');

    $_POST = ['acf' => ['field_location_featured_image' => 4, 'field_location_wayfinding_photo' => 6]];
    location_save_post(5);
    $both = $_POST['acf'];

    $_POST = ['acf' => ['field_location_featured_image' => '', 'field_location_wayfinding_photo' => '']];
    location_save_post(5);

    expect($both)->toBe(['field_location_featured_image' => 4, 'field_location_wayfinding_photo' => 6])
        ->and($_POST['acf'])->toBe(['field_location_featured_image' => '', 'field_location_wayfinding_photo' => '']);
});

test('a child location takes the region of its parent', function (): void {
    uamswp_test_acf_functions();
    acf_save_type('location');
    acf_save_fields(['location_region|77' => 'Central']);
    $_POST = ['acf' => ['field_location_parent' => 1, 'field_location_parent_id' => 77]];

    location_save_post(5);

    expect($_POST['acf']['field_location_region'])->toBe(['Central']);
});

test('a child that supplies its own street address keeps its own region', function (): void {
    uamswp_test_acf_functions();
    acf_save_type('location');
    Functions\expect('get_field')->never();
    $_POST = ['acf' => [
        'field_location_parent' => 1,
        'field_location_parent_id' => 77,
        'field_location_address_override_parent' => 1,
        'field_location_address_override_parent_street' => '1 Main St',
    ]];

    location_save_post(5);

    expect($_POST['acf'])->not->toHaveKey('field_location_region');
});

test('a child overriding the parent without a street still takes the parent region', function (array $override): void {
    uamswp_test_acf_functions();
    acf_save_type('location');
    acf_save_fields(['location_region' => 'Central']);
    $_POST = ['acf' => ['field_location_parent' => 1, 'field_location_parent_id' => 77] + $override];

    location_save_post(5);

    expect($_POST['acf']['field_location_region'])->toBe(['Central']);
})->with([
    'override without street' => [['field_location_address_override_parent' => 1]],
    'street without override' => [['field_location_address_override_parent_street' => '1 Main St']],
]);

test('a location takes no parent region without a parent or a parent id', function (array $parent): void {
    uamswp_test_acf_functions();
    acf_save_type('location');
    Functions\expect('get_field')->never();
    $_POST = ['acf' => $parent];

    location_save_post(5);

    expect($_POST['acf'])->not->toHaveKey('field_location_region');
})->with([
    'no parent' => [['field_location_parent' => 0, 'field_location_parent_id' => 77]],
    'no parent id' => [['field_location_parent' => 1, 'field_location_parent_id' => '']],
]);

test('after a location saves, its post parent is set from the parent field', function (): void {
    uamswp_test_acf_functions();
    acf_save_type('location');
    $post = new WP_Post;
    $post->ID = 5;

    when('get_post')->justReturn($post);
    acf_save_fields(['location_parent' => 1, 'location_parent_id' => '77']);
    Functions\expect('wp_update_post')->once()->with(['ID' => 5, 'post_parent' => 77]);

    location_save_post_after(5);
});

test('after a location saves, its post parent is cleared without a parent or a parent id', function (mixed $has, mixed $id): void {
    uamswp_test_acf_functions();
    acf_save_type('location');
    $post = new WP_Post;
    $post->ID = 5;

    when('get_post')->justReturn($post);
    acf_save_fields(['location_parent' => $has, 'location_parent_id' => $id]);
    Functions\expect('wp_update_post')->once()->with(['ID' => 5, 'post_parent' => 0]);

    location_save_post_after(5);
})->with([
    'no parent' => [0, 77],
    'no id' => [1, 0],
    'neither' => [null, null],
]);

test('after a save, only a location with a loaded post has its parent set', function (string|false $type, mixed $post, mixed $postId): void {
    uamswp_test_acf_functions();
    acf_save_type($type);
    when('get_post')->justReturn($post);
    Functions\expect('wp_update_post')->never();

    location_save_post_after($postId);
})->with([
    'a provider' => ['provider', new WP_Post, 5],
    'no post type' => [false, new WP_Post, 5],
    'a location that cannot be loaded' => ['location', null, 5],
    'a non-numeric id' => ['location', new WP_Post, 'term_5'],
]);

test('the clinical title handler stops without submitted data', function (): void {
    uamswp_test_acf_functions();
    Functions\expect('get_term_by')->never();
    $_POST = [];

    clinical_title_save_post('term_5');
});

test('the clinical title handler looks the term up by its id without the term prefix', function (): void {
    uamswp_test_acf_functions();
    Functions\expect('get_term_by')->once()->with('id', '5', 'clinical_title')->andReturn(false);
    $_POST = ['acf' => ['x' => 1]];

    clinical_title_save_post('term_5');

    expect($_POST['acf'])->toBe(['x' => 1]);
});

test('the clinical title handler inherits nothing for a term with no parent', function (): void {
    uamswp_test_acf_functions();
    Functions\expect('get_term_by')->once()->andReturn((object) ['parent' => 0]);
    Functions\expect('get_field')->never();
    $_POST = ['acf' => ['field_clinical_specialization_cms_code_inherit_query' => 1]];

    clinical_title_save_post('term_5');

    expect($_POST['acf'])->toBe(['field_clinical_specialization_cms_code_inherit_query' => 1]);
});

test('the clinical title handler inherits nothing when the parent term cannot be found', function (): void {
    uamswp_test_acf_functions();
    when('get_term_by')->alias(static fn (string $by, string $id): object|false => $id === '5' ? (object) ['parent' => 3] : false);
    Functions\expect('get_field')->never();
    $_POST = ['acf' => ['field_clinical_specialization_cms_code_inherit_query' => 1]];

    clinical_title_save_post('term_5');

    expect($_POST['acf'])->toBe(['field_clinical_specialization_cms_code_inherit_query' => 1]);
});

/**
 * Run the clinical title handler over a term with a parent whose fields are known.
 *
 * @param  array<string, mixed>  $posted
 * @return array<string, mixed>
 */
function acf_clinical_title_run(array $posted): array
{
    $parent = (object) ['term_id' => 3];
    when('get_term_by')->alias(static fn (string $by, string $id): object => $id === '5' ? (object) ['parent' => 3] : $parent);
    when('get_field')->alias(static fn (string $name, mixed $post = false): string => 'parent:'.$name);

    $_POST = ['acf' => $posted];

    clinical_title_save_post('term_5');

    return $_POST['acf'];
}

test('each inherit switch copies its own parent fields and no others', function (string $switch, array $copied): void {
    uamswp_test_acf_functions();

    $result = acf_clinical_title_run([$switch => 1]);
    unset($result[$switch]);

    expect($result)->toBe($copied);
})->with([
    'CMS code' => [
        'field_clinical_specialization_cms_code_inherit_query',
        [
            'field_clinical_specialization_cms_code' => 'parent:clinical_specialization_cms_code',
            'field_clinical_specialization_cms_code_type' => 'parent:clinical_specialization_cms_code_type',
        ],
    ],
    'MedicalSpecialty member' => [
        'field_clinical_specialization_medicalspecialty_member_inherit_query',
        ['field_clinical_specialization_medicalspecialty_member' => 'parent:clinical_specialization_medicalspecialty_member'],
    ],
    'O*Net-SOC code' => [
        'field_clinical_specialization_onetsoc_code_inherit_query',
        [
            'field_clinical_specialization_onetsoc_code' => 'parent:clinical_specialization_onetsoc_code',
            'field_clinical_specialization_onetsoc_code_name' => 'parent:clinical_specialization_onetsoc_code_name',
        ],
    ],
    'ISCO-08 code' => [
        'field_clinical_specialization_isco08_code_inherit_query',
        ['field_clinical_specialization_isco08_code' => 'parent:clinical_specialization_isco08_code'],
    ],
    'occupation pages' => [
        'field_clinical_specialization_sameas_occupation_inherit_query',
        ['field_clinical_specialization_sameas_occupation' => 'parent:clinical_specialization_sameas_occupation'],
    ],
    'specialty pages' => [
        'field_clinical_specialization_sameas_field_inherit_query',
        ['field_clinical_specialization_sameas_field' => 'parent:clinical_specialization_sameas_field'],
    ],
]);

test('with no inherit switch on, a term with a parent keeps its submitted values', function (): void {
    uamswp_test_acf_functions();

    expect(acf_clinical_title_run(['field_clinical_specialization_cms_code_inherit_query' => 0, 'other' => 'kept']))
        ->toBe(['field_clinical_specialization_cms_code_inherit_query' => 0, 'other' => 'kept']);
});

/**
 * Record the reads and writes of the relationship sync.
 *
 * @param  array<string, mixed>  $stored  The stored value of each "field|post".
 * @return ArrayObject<int, array{0: string, 1: mixed, 2: mixed}>
 */
function acf_sync_stubs(array $stored): ArrayObject
{
    $updates = new ArrayObject;
    when('get_field')->alias(static fn (string $name, mixed $post = false, mixed $format = true): mixed => $stored[$name.'|'.$post] ?? null);
    when('update_field')->alias(static function (string $key, mixed $value, mixed $post) use ($updates): void {
        $updates->append([$key, $value, $post]);
    });

    return $updates;
}

test('a new relation adds the current post to the related post', function (): void {
    uamswp_test_acf_functions();
    $updates = acf_sync_stubs(['physician_locations|8' => [4]]);

    bidirectional_acf_update('physician_locations', 'field_location_physicians', [8], 123);

    expect($updates->getArrayCopy())->toBe([['field_location_physicians', [4, 123], 8]]);
});

test('a related post with no stored list starts one', function (): void {
    uamswp_test_acf_functions();
    $updates = acf_sync_stubs([]);

    bidirectional_acf_update('physician_locations', 'field_location_physicians', [8], 123);

    expect($updates->getArrayCopy())->toBe([['field_location_physicians', [123], 8]]);
});

test('a related post that already lists the current post is not written again', function (): void {
    uamswp_test_acf_functions();
    $updates = acf_sync_stubs(['physician_locations|8' => [123]]);

    bidirectional_acf_update('physician_locations', 'field_location_physicians', [8], 123);

    expect($updates->getArrayCopy())->toBe([]);
});

test('a removed relation takes the current post out of the related post', function (): void {
    uamswp_test_acf_functions();
    $updates = acf_sync_stubs([
        'physician_locations|123' => [7, 8],
        'physician_locations|7' => [123],
        'physician_locations|8' => [4, 123, 9],
    ]);

    bidirectional_acf_update('physician_locations', 'field_location_physicians', [7], 123);

    expect($updates->getArrayCopy())->toBe([['field_location_physicians', [0 => 4, 2 => 9], 8]]);
});

test('every relation is removed when the new value is not a list', function (): void {
    uamswp_test_acf_functions();
    $updates = acf_sync_stubs([
        'physician_locations|123' => [7],
        'physician_locations|7' => [123],
    ]);

    bidirectional_acf_update('physician_locations', 'field_location_physicians', null, 123);

    expect($updates->getArrayCopy())->toBe([['field_location_physicians', [], 7]]);
});

test('a removed relation whose related post has no stored list is skipped', function (): void {
    uamswp_test_acf_functions();
    $updates = acf_sync_stubs(['physician_locations|123' => [7]]);

    bidirectional_acf_update('physician_locations', 'field_location_physicians', [], 123);

    expect($updates->getArrayCopy())->toBe([]);
});

test('the sync handler pairs each post type with its relationship fields', function (string $type, array $posted, array $expected): void {
    uamswp_test_acf_functions();
    acf_save_type($type);
    $updates = acf_sync_stubs([]);
    $_POST = ['acf' => $posted];

    uamswp_sync_acf_save_post(123);

    expect($updates->getArrayCopy())->toBe($expected);
})->with([
    'a location' => [
        'location',
        ['field_location_physicians' => [1], 'field_location_expertise' => [2]],
        [['field_location_physicians', [123], 1], ['field_location_expertise', [123], 2]],
    ],
    'a provider' => [
        'provider',
        ['field_physician_locations' => [3], 'field_physician_expertise' => [4]],
        [['field_physician_locations', [123], 3], ['field_physician_expertise', [123], 4]],
    ],
    'an area of expertise' => [
        'expertise',
        ['field_expertise_locations' => [5], 'field_expertise_physicians' => [6], 'field_expertise_associated' => [7]],
        [['field_expertise_locations', [123], 5], ['field_expertise_physicians', [123], 6], ['field_expertise_associated', [123], 7]],
    ],
    'another post type' => ['clinical-resource', ['field_location_physicians' => [1]], []],
]);

/**
 * Run the excerpt handler for a post of a type, with fields known.
 *
 * @param  array<string, mixed>  $fields
 */
function acf_excerpt_run(string|false $type, array $fields, ?string $expected): void
{
    $post = new WP_Post;
    $post->ID = 5;
    $GLOBALS['post'] = $post;
    acf_save_type($type);
    acf_save_fields($fields);

    if ($expected === null) {
        Functions\expect('wp_update_post')->never();
    } else {
        Functions\expect('wp_update_post')->once()->with(['ID' => 5, 'post_excerpt' => $expected]);
    }

    custom_excerpt_acf();
}

test('the excerpt comes from the field for the post type, unchanged', function (string $type, string $field): void {
    uamswp_test_acf_functions();

    acf_excerpt_run($type, [$field => "Short <b>text</b>\nsecond line"], "Short <b>text</b>\nsecond line");
})->with([
    'provider' => ['provider', 'physician_short_clinical_bio'],
    'location' => ['location', 'location_short_desc'],
    'expertise' => ['expertise', 'post_excerpt'],
    'clinical resource' => ['clinical-resource', 'clinical_resource_excerpt'],
]);

test('the excerpt falls back to the full text of a provider or location, stripped to one line', function (string $type, string $field): void {
    uamswp_test_acf_functions();
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));

    acf_excerpt_run($type, [$field => "<p>Line one</p>\n<p>Line two</p>"], 'Line one Line two');
})->with([
    'provider' => ['provider', 'physician_clinical_bio'],
    'location' => ['location', 'location_about'],
]);

test('a fallback excerpt over 160 characters is cut to 156 with an ellipsis', function (): void {
    uamswp_test_acf_functions();
    when('wp_strip_all_tags')->alias(static fn (string $text): string => $text);

    acf_excerpt_run('provider', ['physician_clinical_bio' => str_repeat('a', 161)], str_repeat('a', 153).'...');
});

test('a fallback excerpt of exactly 160 characters is kept whole', function (): void {
    uamswp_test_acf_functions();
    when('wp_strip_all_tags')->alias(static fn (string $text): string => $text);

    acf_excerpt_run('provider', ['physician_clinical_bio' => str_repeat('b', 160)], str_repeat('b', 160));
});

test('no excerpt is written when the fields are empty, the type has no fallback, or the type is not covered', function (string|false $type, array $fields): void {
    uamswp_test_acf_functions();

    acf_excerpt_run($type, $fields, null);
})->with([
    'a provider with no bio' => ['provider', []],
    'a location with no text' => ['location', ['location_short_desc' => '', 'location_about' => '']],
    'an expertise with no excerpt' => ['expertise', []],
    'a post type with no field' => ['post', ['post_excerpt' => 'Text']],
    'no post type' => [false, ['physician_short_clinical_bio' => 'Text']],
]);

test('no excerpt is written when the global post has no id', function (): void {
    uamswp_test_acf_functions();
    $GLOBALS['post'] = new WP_Post;
    acf_save_type('provider');
    Functions\expect('wp_update_post')->never();

    custom_excerpt_acf();
});

test('the provider handler lists every related expertise, condition and treatment title in the search field', function (): void {
    uamswp_test_acf_functions();
    acf_save_type('provider');
    when('get_the_title')->alias(static fn (int $id): string => 'T'.$id);
    $_POST = ['acf' => [
        'field_physician_expertise' => [5, 6],
        'field_physician_conditions_cpt' => [1, 2],
        'field_physician_treatments_cpt' => [3, 4],
    ]];

    physician_save_post(5);

    expect($_POST['acf']['field_physician_asp_filter'])->toBe('T5, T6, T1, T2, T3, T4');
});
