<?php

/**
 * The Provider Spotlight code in includes/class.fad-acf-functions.php: reading the submitted type, the
 * required-field validation, the seeded questions, the featured-provider reconciliation, the derived title,
 * slug, image and excerpt, the provider link, the ontology mirror and the Epic DEP ID validation.
 *
 * The tests of the branches that run without ACF's own request helper come first. Once a test defines
 * acf_maybe_get_POST() through Brain Monkey the function stays defined for the rest of the process.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/acf-functions.php';
require_once __DIR__.'/../Support/functions-file.php';
require_once __DIR__.'/../Support/wp-classes.php';

beforeEach(function (): void {
    $this->post_backup = $_POST;
});

afterEach(function (): void {
    $_POST = $this->post_backup;
});

/**
 * Stub get_field() from a map of "name|post" or "name" keys.
 *
 * @param  array<string, mixed>  $values
 */
function spot_fields(array $values): void
{
    spot_text_stubs();
    when('get_field')->alias(static fn (string $name, mixed $post = false): mixed => $values[$name.'|'.(is_scalar($post) ? (string) $post : '')] ?? $values[$name] ?? null);
}

/**
 * Stub update_field() and collect each write.
 *
 * @return ArrayObject<int, array{0: string, 1: mixed, 2: mixed}>
 */
function spot_writes(): ArrayObject
{
    $writes = new ArrayObject;
    when('update_field')->alias(static function (string $name, mixed $value, mixed $post) use ($writes): void {
        $writes->append([$name, $value, $post]);
    });

    return $writes;
}

test('a submitted value is read from the posted ACF values without the request helper', function (): void {
    uamswp_test_acf_functions();
    $_POST = ['acf' => ['field_a' => 'x', 'field_b' => 0]];

    expect(uamswp_spotlight_submitted_value('field_a'))->toBe('x')
        ->and(uamswp_spotlight_submitted_value('field_b'))->toBe(0)
        ->and(uamswp_spotlight_submitted_value('field_missing'))->toBeNull();
});

test('a submitted value is null when the posted ACF values are absent or not a list', function (mixed $acf): void {
    uamswp_test_acf_functions();
    $_POST = $acf === 'absent' ? [] : ['acf' => $acf];

    expect(uamswp_spotlight_submitted_value('field_a'))->toBeNull();
})->with([
    'absent' => ['absent'],
    'a string' => ['text'],
    'null' => [null],
]);

test('the current type is the submitted type, flattened when it is a field array', function (mixed $submitted, mixed $expected): void {
    uamswp_test_acf_functions();
    $_POST = ['acf' => ['field_clinical_resource_type' => $submitted]];

    expect(uamswp_spotlight_current_type())->toBe($expected);
})->with([
    'a string' => ['provider_spotlight', 'provider_spotlight'],
    'an array with a value' => [['value' => 'faq', 'label' => 'FAQ'], 'faq'],
    'an array with no value' => [['label' => 'FAQ'], ''],
    'an empty string' => ['', ''],
]);

test('with no submitted type the current type falls back to the stored type of the posted post', function (mixed $stored, mixed $expected): void {
    uamswp_test_acf_functions();
    $_POST = ['post_ID' => '42'];
    Functions\expect('get_field')->once()->with('clinical_resource_type', '42')->andReturn($stored);

    expect(uamswp_spotlight_current_type())->toBe($expected);
})->with([
    'a string' => ['provider_spotlight', 'provider_spotlight'],
    'an array with a value' => [['value' => 'faq'], 'faq'],
    'an array with no value' => [['label' => 'FAQ'], ''],
]);

test('with no submitted type and no usable post id the current type is null', function (array $post): void {
    uamswp_test_acf_functions();
    $_POST = $post;
    Functions\expect('get_field')->never();

    expect(uamswp_spotlight_current_type())->toBeNull();
})->with([
    'nothing posted' => [[]],
    'a zero id' => [['post_ID' => '0']],
    'a non-numeric id' => [['post_ID' => 'abc']],
]);

test('a submission is a spotlight only when the current type is provider_spotlight', function (string $type, bool $expected): void {
    uamswp_test_acf_functions();
    $_POST = ['acf' => ['field_clinical_resource_type' => $type]];

    expect(uamswp_is_spotlight_submission())->toBe($expected);
})->with([
    'spotlight' => ['provider_spotlight', true],
    'another type' => ['faq', false],
    'empty' => ['', false],
]);

test('the featured provider query is limited to published providers', function (): void {
    uamswp_test_acf_functions();

    expect(uamswp_spotlight_provider_query(['s' => 'a', 'post_status' => 'any'], [], 5))->toBe(['s' => 'a', 'post_status' => 'publish']);
});

test('the required-field validation leaves a failure from an earlier filter alone', function (string $function): void {
    uamswp_test_acf_functions();
    $_POST = ['acf' => ['field_clinical_resource_type' => 'faq']];

    expect($function('Already invalid', '', [], 'acf[x]'))->toBe('Already invalid')
        ->and($function(false, '', [], 'acf[x]'))->toBeFalse();
})->with([
    'excerpt' => ['uamswp_spotlight_validate_excerpt'],
    'featured image' => ['uamswp_spotlight_validate_featured_image'],
    'provider' => ['uamswp_spotlight_validate_provider'],
    'questions' => ['uamswp_spotlight_validate_qa'],
]);

test('the short description is required unless the resource is a spotlight', function (mixed $value, string $type, mixed $expected): void {
    uamswp_test_acf_functions();
    $_POST = ['acf' => ['field_clinical_resource_type' => $type]];

    expect(uamswp_spotlight_validate_excerpt(true, $value, [], 'acf[x]'))->toBe($expected);
})->with([
    'text on another type' => ['A summary', 'faq', true],
    'empty on another type' => ['', 'faq', 'Short Description is required.'],
    'spaces on another type' => ['   ', 'faq', 'Short Description is required.'],
    'null on another type' => [null, 'faq', 'Short Description is required.'],
    'empty on a spotlight' => ['', 'provider_spotlight', true],
]);

test('the featured image is required unless the resource is a spotlight', function (mixed $value, string $type, mixed $expected): void {
    uamswp_test_acf_functions();
    $_POST = ['acf' => ['field_clinical_resource_type' => $type]];

    expect(uamswp_spotlight_validate_featured_image(true, $value, [], 'acf[x]'))->toBe($expected);
})->with([
    'an image on another type' => [12, 'faq', true],
    'none on another type' => ['', 'faq', 'Featured Image is required.'],
    'zero on another type' => [0, 'faq', 'Featured Image is required.'],
    'none on a spotlight' => ['', 'provider_spotlight', true],
]);

test('the questions are required only for a spotlight', function (mixed $value, string $type, mixed $expected): void {
    uamswp_test_acf_functions();
    $_POST = ['acf' => ['field_clinical_resource_type' => $type]];

    expect(uamswp_spotlight_validate_qa(true, $value, [], 'acf[x]'))->toBe($expected);
})->with([
    'none on a spotlight' => [[], 'provider_spotlight', 'A Provider Spotlight needs at least one question and answer.'],
    'null on a spotlight' => [null, 'provider_spotlight', 'A Provider Spotlight needs at least one question and answer.'],
    'rows on a spotlight' => [[['q' => 'a']], 'provider_spotlight', true],
    'none on another type' => [[], 'faq', true],
]);

test('the featured provider needs a photo unless an override image is submitted', function (): void {
    uamswp_test_acf_functions();
    $message = 'This provider has no wide portrait and no headshot, so there is no image to use for social sharing and cards. Add a Featured Image Override below, or add a photo to the provider.';
    $_POST = ['acf' => []];

    spot_fields([]);
    when('get_post_thumbnail_id')->justReturn(0);
    $none = uamswp_spotlight_validate_provider(true, 7, [], 'acf[x]');

    spot_fields(['physician_image_wide|7' => 33]);
    $wide = uamswp_spotlight_validate_provider(true, 7, [], 'acf[x]');

    spot_fields([]);
    when('get_post_thumbnail_id')->justReturn(44);
    $headshot = uamswp_spotlight_validate_provider(true, [7], [], 'acf[x]');

    $_POST = ['acf' => ['field_clinical_resource_spotlight_image_wide' => 55]];
    when('get_post_thumbnail_id')->justReturn(0);
    $override = uamswp_spotlight_validate_provider(true, 7, [], 'acf[x]');

    expect($none)->toBe($message)
        ->and($wide)->toBeTrue()
        ->and($headshot)->toBeTrue()
        ->and($override)->toBeTrue();
});

test('the featured provider check passes an empty value and an unusable id', function (mixed $value): void {
    uamswp_test_acf_functions();
    $_POST = ['acf' => []];
    Functions\expect('get_field')->never();

    expect(uamswp_spotlight_validate_provider(true, $value, [], 'acf[x]'))->toBeTrue();
})->with([
    'empty' => [''],
    'an empty list' => [[]],
    'a text id' => ['abc'],
]);

test('the seeded questions apply only to an empty list on a new clinical resource in the admin', function (array $case): void {
    uamswp_test_acf_functions();
    when('is_admin')->justReturn($case['admin']);
    when('get_post_type')->justReturn($case['type']);
    when('get_post_status')->justReturn($case['status']);
    when('apply_filters')->alias(static fn (string $hook, mixed $value): mixed => $value);

    $result = uamswp_spotlight_seed_questions($case['value'], $case['post'], []);

    expect($result === $case['value'])->toBe($case['untouched']);
})->with([
    'seeded' => [['admin' => true, 'type' => 'clinical-resource', 'status' => 'auto-draft', 'value' => null, 'post' => 5, 'untouched' => false]],
    'the front end' => [['admin' => false, 'type' => 'clinical-resource', 'status' => 'auto-draft', 'value' => null, 'post' => 5, 'untouched' => true]],
    'existing rows' => [['admin' => true, 'type' => 'clinical-resource', 'status' => 'auto-draft', 'value' => [['x']], 'post' => 5, 'untouched' => true]],
    'no post' => [['admin' => true, 'type' => 'clinical-resource', 'status' => 'auto-draft', 'value' => null, 'post' => 0, 'untouched' => true]],
    'a term id' => [['admin' => true, 'type' => 'clinical-resource', 'status' => 'auto-draft', 'value' => null, 'post' => 'term_5', 'untouched' => true]],
    'another post type' => [['admin' => true, 'type' => 'provider', 'status' => 'auto-draft', 'value' => null, 'post' => 5, 'untouched' => true]],
    'a saved post' => [['admin' => true, 'type' => 'clinical-resource', 'status' => 'publish', 'value' => null, 'post' => 5, 'untouched' => true]],
]);

test('each seeded row carries one default question and an empty answer', function (): void {
    uamswp_test_acf_functions();
    when('is_admin')->justReturn(true);
    when('get_post_type')->justReturn('clinical-resource');
    when('get_post_status')->justReturn('auto-draft');
    when('apply_filters')->alias(static fn (string $hook, mixed $value): mixed => $value);

    $rows = uamswp_spotlight_seed_questions(null, '5', []);

    expect($rows)->toHaveCount(count(uamswp_spotlight_default_questions()))
        ->and($rows[0])->toBe([
            'field_clinical_resource_spotlight_qa_question' => 'What inspired you to work in healthcare?',
            'field_clinical_resource_spotlight_qa_answer' => '',
        ])
        ->and(array_column($rows, 'field_clinical_resource_spotlight_qa_answer'))->each->toBe('');
});

test('the stored type is the value of a field array, or the string form of anything else', function (mixed $stored, mixed $expected): void {
    uamswp_test_acf_functions();
    spot_fields(['clinical_resource_type' => $stored]);

    expect(uamswp_spotlight_stored_type(5))->toBe($expected);
})->with([
    'a string' => ['provider_spotlight', 'provider_spotlight'],
    'an array' => [['value' => 'faq'], 'faq'],
    'an array without a value' => [['label' => 'x'], ''],
    'nothing' => [null, ''],
]);

test('related posts are normalized to a list of unique non-zero ids', function (mixed $value, array $expected): void {
    uamswp_test_acf_functions();

    expect(uamswp_spotlight_to_ids($value))->toBe($expected);
})->with([
    'empty' => [null, []],
    'an empty list' => [[], []],
    'a single id' => ['7', [7]],
    'a list with repeats and zeros' => [['3', 3, 0, '', 4], [3, 4]],
]);

/**
 * Run the provider reconciliation and return what it left in `$_POST['acf']`.
 *
 * @param  array<string, mixed>  $posted
 * @param  array<string, mixed>  $fields
 * @return array<string, mixed>
 */
function spot_reconcile(array $posted, array $fields = [], string $type = 'clinical-resource', mixed $post = 5): array
{
    when('get_post_type')->justReturn($type);
    spot_fields($fields);
    $_POST = ['acf' => $posted];

    uamswp_spotlight_reconcile_providers($post);

    return $_POST['acf'];
}

test('the provider reconciliation does nothing without data, an id or a clinical resource', function (): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn('clinical-resource');

    $_POST = [];
    uamswp_spotlight_reconcile_providers(5);
    $_POST = ['acf' => ['field_clinical_resource_type' => 'provider_spotlight']];
    uamswp_spotlight_reconcile_providers('term_5');

    expect($_POST['acf'])->toBe(['field_clinical_resource_type' => 'provider_spotlight']);

    when('get_post_type')->justReturn('provider');

    expect(spot_reconcile(['field_clinical_resource_type' => 'provider_spotlight', 'field_clinical_resource_spotlight_provider' => 9], [], 'provider'))
        ->not->toHaveKey('field_clinical_resource_providers');
});

test('a resource that never had a featured provider and gains none is left alone', function (): void {
    uamswp_test_acf_functions();

    expect(spot_reconcile(['field_clinical_resource_type' => 'provider_spotlight'], ['clinical_resource_spotlight_provider' => 0]))
        ->toBe(['field_clinical_resource_type' => 'provider_spotlight']);
});

test('the featured provider is added to the submitted related providers as strings', function (): void {
    uamswp_test_acf_functions();

    $result = spot_reconcile([
        'field_clinical_resource_type' => 'provider_spotlight',
        'field_clinical_resource_spotlight_provider' => '9',
        'field_clinical_resource_providers' => ['1', '2'],
    ]);

    expect($result['field_clinical_resource_providers'])->toBe(['1', '2', '9']);
});

test('the first provider of a submitted list is the featured provider', function (): void {
    uamswp_test_acf_functions();

    $result = spot_reconcile([
        'field_clinical_resource_type' => 'provider_spotlight',
        'field_clinical_resource_spotlight_provider' => [9, 10],
        'field_clinical_resource_providers' => [],
    ]);

    expect($result['field_clinical_resource_providers'])->toBe(['9']);
});

test('a featured provider already in the submitted list leaves the submitted value as it was', function (): void {
    uamswp_test_acf_functions();

    $result = spot_reconcile([
        'field_clinical_resource_type' => 'provider_spotlight',
        'field_clinical_resource_spotlight_provider' => 9,
        'field_clinical_resource_providers' => [9, 2],
    ]);

    expect($result['field_clinical_resource_providers'])->toBe([9, 2]);
});

test('an unsubmitted related providers field starts from the stored list', function (): void {
    uamswp_test_acf_functions();

    $result = spot_reconcile(
        ['field_clinical_resource_type' => 'provider_spotlight', 'field_clinical_resource_spotlight_provider' => 9],
        ['clinical_resource_providers' => [3, 4], 'clinical_resource_spotlight_provider' => 3],
    );

    expect($result['field_clinical_resource_providers'])->toBe(['4', '9']);
});

test('an unsubmitted list keeps the previous provider when it is still the featured one', function (): void {
    uamswp_test_acf_functions();

    $result = spot_reconcile(
        ['field_clinical_resource_type' => 'provider_spotlight', 'field_clinical_resource_spotlight_provider' => 3],
        ['clinical_resource_providers' => [3, 4], 'clinical_resource_spotlight_provider' => 3],
    );

    expect($result)->not->toHaveKey('field_clinical_resource_providers');
});

test('an unsubmitted list loses the previous provider when the resource stops being a spotlight', function (): void {
    uamswp_test_acf_functions();

    $result = spot_reconcile(
        ['field_clinical_resource_type' => 'faq'],
        ['clinical_resource_providers' => [3, 4], 'clinical_resource_spotlight_provider' => 3],
    );

    expect($result['field_clinical_resource_providers'])->toBe(['4']);
});

test('a submitted list keeps the previous provider because it is the editor intent', function (): void {
    uamswp_test_acf_functions();

    $result = spot_reconcile(
        ['field_clinical_resource_type' => 'faq', 'field_clinical_resource_providers' => ['3', '4']],
        ['clinical_resource_spotlight_provider' => 3],
    );

    expect($result['field_clinical_resource_providers'])->toBe(['3', '4']);
});

test('the featured provider is not added to a resource that is not a spotlight', function (): void {
    uamswp_test_acf_functions();

    $result = spot_reconcile([
        'field_clinical_resource_type' => 'faq',
        'field_clinical_resource_spotlight_provider' => 9,
        'field_clinical_resource_providers' => ['1'],
    ]);

    expect($result['field_clinical_resource_providers'])->toBe(['1']);
});

/**
 * A stand-in provider with a first and last name and nothing else.
 */
function spot_provider(int $id, string $first = 'Ann', string $last = 'Lee'): array
{
    return [
        'physician_first_name|'.$id => $first,
        'physician_last_name|'.$id => $last,
    ];
}

/**
 * Stub the text helpers the title sync uses.
 */
function spot_text_stubs(): void
{
    // The name variants run through the real uamswp_attr_conversion() from the functions file.
    uamswp_test_functions_file();
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('sanitize_title_with_dashes')->alias(static fn (string $text): string => trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($text)), '-'));
    when('sanitize_title')->alias(static fn (string $text): string => trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($text)), '-'));
}

test('the title sync does nothing unless the resource is a spotlight with a featured provider', function (string $type, string $stored, int $provider): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn($type);
    spot_fields(['clinical_resource_type' => $stored, 'clinical_resource_spotlight_provider' => $provider]);
    Functions\expect('wp_update_post')->never();
    Functions\expect('get_post')->never();

    uamswp_spotlight_sync_title(5);
})->with([
    'a provider post' => ['provider', 'provider_spotlight', 7],
    'another resource type' => ['clinical-resource', 'faq', 7],
    'no featured provider' => ['clinical-resource', 'provider_spotlight', 0],
]);

test('the title sync ignores a term id', function (): void {
    uamswp_test_acf_functions();
    Functions\expect('get_post_type')->never();

    uamswp_spotlight_sync_title('term_5');
});

test('the title sync does nothing when the provider has no name or the post is missing', function (array $fields, mixed $post): void {
    uamswp_test_acf_functions();
    spot_text_stubs();
    when('get_post_type')->justReturn('clinical-resource');
    spot_fields(['clinical_resource_type' => 'provider_spotlight', 'clinical_resource_spotlight_provider' => 7] + $fields);
    when('get_post')->justReturn($post);
    Functions\expect('wp_update_post')->never();

    uamswp_spotlight_sync_title(5);
})->with([
    'no name' => [[], (object) ['post_title' => '', 'post_name' => '', 'post_status' => 'draft']],
    'no post' => [spot_provider(7), null],
]);

test('the title sync sets the title and slug from the provider name on a draft and does not lock the slug', function (): void {
    uamswp_test_acf_functions();
    spot_text_stubs();
    when('get_post_type')->justReturn('clinical-resource');
    spot_fields(['clinical_resource_type' => 'provider_spotlight', 'clinical_resource_spotlight_provider' => 7] + spot_provider(7));
    when('get_post')->justReturn((object) ['post_title' => 'Old', 'post_name' => 'old', 'post_status' => 'draft']);
    when('get_post_meta')->justReturn('');

    Functions\expect('update_post_meta')->never();
    Functions\expect('wp_update_post')->once()->with(['post_title' => 'Get to Know Ann Lee', 'post_name' => 'get-to-know-ann-lee', 'ID' => 5]);

    uamswp_spotlight_sync_title(5);
});

test('publishing locks the slug with a meta flag', function (string $status): void {
    uamswp_test_acf_functions();
    spot_text_stubs();
    when('get_post_type')->justReturn('clinical-resource');
    spot_fields(['clinical_resource_type' => 'provider_spotlight', 'clinical_resource_spotlight_provider' => 7] + spot_provider(7));
    when('get_post')->justReturn((object) ['post_title' => 'Get to Know Ann Lee', 'post_name' => 'placeholder', 'post_status' => $status]);
    when('get_post_meta')->justReturn('');
    Functions\expect('update_post_meta')->once()->with(5, '_uamswp_spotlight_slug_locked', '1');
    Functions\expect('wp_update_post')->once()->with(['post_name' => 'get-to-know-ann-lee', 'ID' => 5]);

    uamswp_spotlight_sync_title(5);
})->with(['publish', 'future', 'private']);

test('a locked slug is not derived again, and the title still tracks the provider', function (): void {
    uamswp_test_acf_functions();
    spot_text_stubs();
    when('get_post_type')->justReturn('clinical-resource');
    spot_fields(['clinical_resource_type' => 'provider_spotlight', 'clinical_resource_spotlight_provider' => 7] + spot_provider(7, 'Anne', 'Lee'));
    when('get_post')->justReturn((object) ['post_title' => 'Get to Know Ann Lee', 'post_name' => 'get-to-know-ann-lee', 'post_status' => 'publish']);
    when('get_post_meta')->justReturn('1');

    Functions\expect('update_post_meta')->never();
    Functions\expect('wp_update_post')->once()->with(['post_title' => 'Get to Know Anne Lee', 'ID' => 5]);

    uamswp_spotlight_sync_title(5);
});

test('a title and slug that already match are not written', function (): void {
    uamswp_test_acf_functions();
    spot_text_stubs();
    when('get_post_type')->justReturn('clinical-resource');
    spot_fields(['clinical_resource_type' => 'provider_spotlight', 'clinical_resource_spotlight_provider' => 7] + spot_provider(7));
    when('get_post')->justReturn((object) ['post_title' => 'Get to Know Ann Lee', 'post_name' => 'get-to-know-ann-lee', 'post_status' => 'draft']);
    when('get_post_meta')->justReturn('');

    Functions\expect('wp_update_post')->never();

    uamswp_spotlight_sync_title(5);
});

test('markup in the provider name is stripped before it becomes the title', function (): void {
    uamswp_test_acf_functions();
    spot_text_stubs();
    when('get_post_type')->justReturn('clinical-resource');
    spot_fields(['clinical_resource_type' => 'provider_spotlight', 'clinical_resource_spotlight_provider' => 7] + spot_provider(7, 'Ann', '<b>Lee</b>'));
    when('get_post')->justReturn((object) ['post_title' => 'Old', 'post_name' => 'old', 'post_status' => 'draft']);
    when('get_post_meta')->justReturn('');
    Functions\expect('wp_update_post')->once()->withArgs(static fn (array $update): bool => $update['post_title'] === 'Get to Know Ann Lee');

    uamswp_spotlight_sync_title(5);
});

test('the spotlight image is the override, then the wide portrait, then the headshot', function (): void {
    uamswp_test_acf_functions();
    when('get_post_thumbnail_id')->justReturn(44);

    spot_fields(['clinical_resource_spotlight_image_wide|5' => '11', 'physician_image_wide|7' => '22']);
    $override = uamswp_spotlight_resolve_image(5, 7);

    spot_fields(['physician_image_wide|7' => '22']);
    $wide = uamswp_spotlight_resolve_image(5, 7);

    spot_fields([]);
    $headshot = uamswp_spotlight_resolve_image(5, 7);

    when('get_post_thumbnail_id')->justReturn('');
    $none = uamswp_spotlight_resolve_image(5, 7);

    expect($override)->toBe(11)
        ->and($wide)->toBe(22)
        ->and($headshot)->toBe(44)
        ->and($none)->toBe(0);
});

test('saving a spotlight sets the thumbnail and rewrites the short description from the provider', function (): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn('clinical-resource');
    spot_fields(['clinical_resource_type' => 'provider_spotlight', 'clinical_resource_spotlight_provider' => 7, 'physician_image_wide|7' => 22] + spot_provider(7));
    when('get_post_thumbnail_id')->justReturn(0);
    $writes = spot_writes();
    Functions\expect('set_post_thumbnail')->once()->with(5, 22);

    uamswp_spotlight_save(5);

    $expected = uamswp_spotlight_default_excerpt(7);

    expect($expected)->not->toBe('')
        ->and($writes->getArrayCopy())->toBe([['clinical_resource_excerpt', $expected, 5]]);
});

test('saving a spotlight leaves a thumbnail that is already right and an image that cannot be resolved', function (int $current, int $wide): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn('clinical-resource');
    spot_fields(['clinical_resource_type' => 'provider_spotlight', 'clinical_resource_spotlight_provider' => 7, 'physician_image_wide|7' => $wide] + spot_provider(7));
    when('get_post_thumbnail_id')->alias(static fn (int $id): int => $id === 5 ? $current : 0);
    spot_writes();
    Functions\expect('set_post_thumbnail')->never();

    uamswp_spotlight_save(5);
})->with([
    'already set' => [22, 22],
    'no image' => [0, 0],
]);

test('saving does nothing for another type, another post type, no provider or a term id', function (string $type, string $stored, int $provider, mixed $post): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn($type);
    spot_fields(['clinical_resource_type' => $stored, 'clinical_resource_spotlight_provider' => $provider]);
    Functions\expect('set_post_thumbnail')->never();
    Functions\expect('update_field')->never();

    uamswp_spotlight_save($post);
})->with([
    'a provider post' => ['provider', 'provider_spotlight', 7, 5],
    'another type' => ['clinical-resource', 'faq', 7, 5],
    'no provider' => ['clinical-resource', 'provider_spotlight', 0, 5],
    'a term id' => ['clinical-resource', 'provider_spotlight', 7, 'term_5'],
]);

test('saving a spotlight writes no short description when none can be generated', function (): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn('clinical-resource');
    spot_fields(['clinical_resource_type' => 'provider_spotlight', 'clinical_resource_spotlight_provider' => 7]);
    when('get_post_thumbnail_id')->justReturn(0);
    Functions\expect('update_field')->never();

    uamswp_spotlight_save(5);
});

test('asserting the link adds the provider to the resource and the resource to the provider', function (): void {
    uamswp_test_acf_functions();
    spot_fields(['clinical_resource_providers|5' => [3], 'physician_clinical_resources|7' => [4]]);
    $writes = spot_writes();

    uamswp_spotlight_assert_link(5, 7);

    expect($writes->getArrayCopy())->toBe([
        ['clinical_resource_providers', [3, 7], 5],
        ['physician_clinical_resources', [4, 5], 7],
    ]);
});

test('asserting a link that is already in place writes nothing', function (): void {
    uamswp_test_acf_functions();
    spot_fields(['clinical_resource_providers|5' => [7], 'physician_clinical_resources|7' => [5]]);
    $writes = spot_writes();

    uamswp_spotlight_assert_link(5, 7);

    expect($writes->getArrayCopy())->toBe([]);
});

test('releasing the link removes each side and re-indexes the lists', function (): void {
    uamswp_test_acf_functions();
    spot_fields(['clinical_resource_providers|5' => [3, 7, 8], 'physician_clinical_resources|7' => [4, 5]]);
    $writes = spot_writes();

    uamswp_spotlight_release_link(5, 7);

    expect($writes->getArrayCopy())->toBe([
        ['clinical_resource_providers', [3, 8], 5],
        ['physician_clinical_resources', [4], 7],
    ]);
});

test('releasing a link that is not there writes nothing', function (): void {
    uamswp_test_acf_functions();
    spot_fields(['clinical_resource_providers|5' => [3], 'physician_clinical_resources|7' => [4]]);
    $writes = spot_writes();

    uamswp_spotlight_release_link(5, 7);

    expect($writes->getArrayCopy())->toBe([]);
});

test('the spotlights for a provider are the published provider_spotlight resources naming it', function (): void {
    uamswp_test_acf_functions();
    Functions\expect('get_posts')->once()->with([
        'post_type' => 'clinical-resource',
        'post_status' => 'publish',
        'numberposts' => -1,
        'fields' => 'ids',
        'suppress_filters' => false,
        'meta_query' => [
            'relation' => 'AND',
            ['key' => 'clinical_resource_type', 'value' => 'provider_spotlight'],
            ['key' => 'clinical_resource_spotlight_provider', 'value' => 7],
        ],
    ])->andReturn(['11', 12]);

    expect(uamswp_spotlight_posts_for_provider(7))->toBe([11, 12]);
});

test('no spotlights are searched for without a provider', function (): void {
    uamswp_test_acf_functions();
    Functions\expect('get_posts')->never();

    expect(uamswp_spotlight_posts_for_provider(0))->toBe([]);
});

test('the provider guard re-asserts the link and the mirror of every spotlight naming the provider', function (): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn('provider');
    when('get_posts')->justReturn([11]);
    when('is_user_logged_in')->justReturn(false);
    spot_fields(['physician_clinical_resources|7' => [], 'physician_locations|7' => [30]]);
    $writes = spot_writes();

    uamswp_spotlight_provider_guard('7');

    expect($writes->getArrayCopy())->toContain(['clinical_resource_providers', [7], 11])
        ->toContain(['physician_clinical_resources', [11], 7])
        ->toContain(['clinical_resource_locations', [30], 11]);
});

test('the provider guard ignores other post types and term ids', function (string|false $type, mixed $post): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn($type);
    Functions\expect('get_posts')->never();

    uamswp_spotlight_provider_guard($post);
})->with([
    'a clinical resource' => ['clinical-resource', 5],
    'no post type' => [false, 5],
    'a term id' => ['provider', 'term_5'],
]);

/**
 * A post for the transition hook.
 */
function spot_transition_post(string $type = 'clinical-resource', int $id = 5): object
{
    return (object) ['ID' => $id, 'post_type' => $type];
}

test('publishing a spotlight asserts the link and mirrors the provider ontology', function (): void {
    uamswp_test_acf_functions();
    when('wp_is_post_revision')->justReturn(false);
    when('wp_is_post_autosave')->justReturn(false);
    when('is_user_logged_in')->justReturn(false);
    spot_fields(['clinical_resource_type' => 'provider_spotlight', 'clinical_resource_spotlight_provider' => 7, 'physician_expertise|7' => [40]]);
    $writes = spot_writes();

    uamswp_spotlight_status_sync('publish', 'draft', spot_transition_post());

    expect($writes->getArrayCopy())->toContain(['clinical_resource_providers', [7], 5])
        ->toContain(['physician_clinical_resources', [5], 7])
        ->toContain(['clinical_resource_aoe', [40], 5]);
});

test('taking a spotlight out of publication releases the link and clears the mirror', function (string $new): void {
    uamswp_test_acf_functions();
    when('wp_is_post_revision')->justReturn(false);
    when('wp_is_post_autosave')->justReturn(false);
    when('is_user_logged_in')->justReturn(false);
    spot_fields([
        'clinical_resource_type' => 'provider_spotlight',
        'clinical_resource_spotlight_provider' => 7,
        'clinical_resource_providers|5' => [7],
        'physician_clinical_resources|7' => [5],
        'clinical_resource_aoe|5' => [40],
    ]);
    $writes = spot_writes();

    uamswp_spotlight_status_sync($new, 'publish', spot_transition_post());

    expect($writes->getArrayCopy())->toContain(['clinical_resource_providers', [], 5])
        ->toContain(['physician_clinical_resources', [], 7])
        ->toContain(['clinical_resource_aoe', [], 5]);
})->with(['draft', 'trash', 'pending']);

test('a status change that is not a spotlight publication does nothing', function (string $new, string $old, object $post, bool $revision, bool $autosave, string $stored, int $provider): void {
    uamswp_test_acf_functions();
    when('wp_is_post_revision')->justReturn($revision);
    when('wp_is_post_autosave')->justReturn($autosave);
    spot_fields(['clinical_resource_type' => $stored, 'clinical_resource_spotlight_provider' => $provider]);
    Functions\expect('update_field')->never();

    uamswp_spotlight_status_sync($new, $old, $post);
})->with([
    'no post id' => ['publish', 'draft', (object) ['ID' => 0, 'post_type' => 'clinical-resource'], false, false, 'provider_spotlight', 7],
    'another post type' => ['publish', 'draft', (object) ['ID' => 5, 'post_type' => 'provider'], false, false, 'provider_spotlight', 7],
    'the same status' => ['publish', 'publish', (object) ['ID' => 5, 'post_type' => 'clinical-resource'], false, false, 'provider_spotlight', 7],
    'a revision' => ['publish', 'draft', (object) ['ID' => 5, 'post_type' => 'clinical-resource'], true, false, 'provider_spotlight', 7],
    'an autosave' => ['publish', 'draft', (object) ['ID' => 5, 'post_type' => 'clinical-resource'], false, true, 'provider_spotlight', 7],
    'another type' => ['publish', 'draft', (object) ['ID' => 5, 'post_type' => 'clinical-resource'], false, false, 'faq', 7],
    'no provider' => ['publish', 'draft', (object) ['ID' => 5, 'post_type' => 'clinical-resource'], false, false, 'provider_spotlight', 0],
    'draft to pending' => ['pending', 'draft', (object) ['ID' => 5, 'post_type' => 'clinical-resource'], false, false, 'provider_spotlight', 7],
]);

test('the ontology map pairs each resource field with its provider source and reverse field', function (): void {
    uamswp_test_acf_functions();

    expect(uamswp_spotlight_ontology_map())->toBe([
        'field_clinical_resource_locations' => ['name' => 'clinical_resource_locations', 'provider' => 'physician_locations', 'reverse' => 'field_location_clinical_resources'],
        'field_clinical_resource_aoe' => ['name' => 'clinical_resource_aoe', 'provider' => 'physician_expertise', 'reverse' => 'field_expertise_clinical_resources'],
        'field_clinical_resource_conditions' => ['name' => 'clinical_resource_conditions', 'provider' => 'physician_conditions_cpt', 'reverse' => 'field_condition_clinical_resources'],
        'field_clinical_resource_treatments' => ['name' => 'clinical_resource_treatments', 'provider' => 'physician_treatments_cpt', 'reverse' => 'field_treatment_procedure_clinical_resources'],
    ]);
});

test('the ontology sync copies the provider set and updates each reverse list', function (): void {
    uamswp_test_acf_functions();
    when('is_user_logged_in')->justReturn(false);
    spot_fields([
        'physician_locations|7' => [30, 31],
        'clinical_resource_locations|5' => [31, 32],
        'field_location_clinical_resources|30' => [1],
        'field_location_clinical_resources|32' => [5, 6],
    ]);
    $writes = spot_writes();

    uamswp_spotlight_sync_ontology('5', 7);

    expect($writes->getArrayCopy())->toBe([
        ['clinical_resource_locations', [30, 31], 5],
        ['field_location_clinical_resources', [1, 5], 30],
        ['field_location_clinical_resources', [6], 32],
    ]);
});

test('the ontology sync writes nothing when the sets already match', function (): void {
    uamswp_test_acf_functions();
    when('is_user_logged_in')->justReturn(false);
    spot_fields(['physician_expertise|7' => [40], 'clinical_resource_aoe|5' => [40]]);
    $writes = spot_writes();

    uamswp_spotlight_sync_ontology(5, 7);

    expect($writes->getArrayCopy())->toBe([]);
});

test('the ontology sync skips a reverse write that already lists, or no longer lists, the resource', function (): void {
    uamswp_test_acf_functions();
    when('is_user_logged_in')->justReturn(false);
    spot_fields([
        'physician_locations|7' => [30],
        'clinical_resource_locations|5' => [32],
        'field_location_clinical_resources|30' => [5],
        'field_location_clinical_resources|32' => [6],
    ]);
    $writes = spot_writes();

    uamswp_spotlight_sync_ontology(5, 7);

    expect($writes->getArrayCopy())->toBe([['clinical_resource_locations', [30], 5]]);
});

test('a provider of zero clears the mirror', function (): void {
    uamswp_test_acf_functions();
    when('is_user_logged_in')->justReturn(false);
    spot_fields(['clinical_resource_conditions|5' => [50], 'field_condition_clinical_resources|50' => [5, 6]]);
    $writes = spot_writes();

    uamswp_spotlight_sync_ontology(5, 0);

    expect($writes->getArrayCopy())->toBe([
        ['clinical_resource_conditions', [], 5],
        ['field_condition_clinical_resources', [6], 50],
    ]);
});

test('an acting user writes only to the posts they can edit', function (): void {
    uamswp_test_acf_functions();
    when('is_user_logged_in')->justReturn(true);
    when('current_user_can')->alias(static fn (string $capability, int $id): bool => $capability === 'edit_post' && $id !== 31 && $id !== 5);
    spot_fields([
        'physician_locations|7' => [30, 31],
        'clinical_resource_locations|5' => [32],
        'field_location_clinical_resources|30' => [],
        'field_location_clinical_resources|31' => [],
        'field_location_clinical_resources|32' => [5],
    ]);
    $writes = spot_writes();

    uamswp_spotlight_sync_ontology(5, 7);

    expect($writes->getArrayCopy())->toBe([
        ['field_location_clinical_resources', [5], 30],
        ['field_location_clinical_resources', [], 32],
    ]);
});

test('an acting user who can edit the spotlight has its own field written', function (): void {
    uamswp_test_acf_functions();
    when('is_user_logged_in')->justReturn(true);
    when('current_user_can')->justReturn(true);
    spot_fields(['physician_expertise|7' => [40], 'field_expertise_clinical_resources|40' => []]);
    $writes = spot_writes();

    uamswp_spotlight_sync_ontology(5, 7);

    expect($writes->getArrayCopy())->toBe([
        ['clinical_resource_aoe', [40], 5],
        ['field_expertise_clinical_resources', [5], 40],
    ]);
});

/**
 * Run the ontology reconciliation and return what it left in `$_POST['acf']`.
 *
 * @param  array<string, mixed>  $posted
 * @param  array<string, mixed>  $fields
 * @return array<string, mixed>
 */
function spot_reconcile_ontology(array $posted, array $fields = [], string $type = 'clinical-resource', mixed $post = 5): array
{
    when('get_post_type')->justReturn($type);
    spot_fields($fields);
    $_POST = ['acf' => $posted];

    uamswp_spotlight_reconcile_ontology($post);

    return $_POST['acf'];
}

test('a spotlight submission takes its ontology from the featured provider as strings', function (): void {
    uamswp_test_acf_functions();

    $result = spot_reconcile_ontology(
        ['field_clinical_resource_type' => 'provider_spotlight', 'field_clinical_resource_spotlight_provider' => [7, 8]],
        ['physician_locations|7' => [30], 'physician_expertise|7' => ['40', 41], 'physician_conditions_cpt|7' => [], 'physician_treatments_cpt|7' => [60]],
    );

    expect($result['field_clinical_resource_locations'])->toBe(['30'])
        ->and($result['field_clinical_resource_aoe'])->toBe(['40', '41'])
        ->and($result['field_clinical_resource_conditions'])->toBe([])
        ->and($result['field_clinical_resource_treatments'])->toBe(['60']);
});

test('a resource that stops being a spotlight drops the mirror except for fields the editor submitted', function (): void {
    uamswp_test_acf_functions();

    $result = spot_reconcile_ontology(
        ['field_clinical_resource_type' => 'faq', 'field_clinical_resource_aoe' => ['99']],
        ['clinical_resource_type' => 'provider_spotlight'],
    );

    expect($result['field_clinical_resource_locations'])->toBe([])
        ->and($result['field_clinical_resource_aoe'])->toBe(['99'])
        ->and($result['field_clinical_resource_conditions'])->toBe([])
        ->and($result['field_clinical_resource_treatments'])->toBe([]);
});

test('the ontology reconciliation leaves a non-spotlight alone', function (string $type, array $posted, array $fields): void {
    uamswp_test_acf_functions();

    expect(spot_reconcile_ontology($posted, $fields, $type))->toBe($posted);
})->with([
    'another type, stored and submitted' => ['clinical-resource', ['field_clinical_resource_type' => 'faq'], ['clinical_resource_type' => 'faq']],
    'a spotlight with no provider and a non-spotlight stored type' => ['clinical-resource', ['field_clinical_resource_type' => 'provider_spotlight'], ['clinical_resource_type' => 'faq']],
    'a provider post' => ['provider', ['field_clinical_resource_type' => 'provider_spotlight', 'field_clinical_resource_spotlight_provider' => 7], []],
]);

test('the ontology reconciliation does nothing without data or with a term id', function (): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn('clinical-resource');
    Functions\expect('get_field')->never();

    $_POST = [];
    uamswp_spotlight_reconcile_ontology(5);
    $_POST = ['acf' => ['field_clinical_resource_type' => 'provider_spotlight', 'field_clinical_resource_spotlight_provider' => 7]];
    uamswp_spotlight_reconcile_ontology('term_5');

    expect($_POST['acf'])->not->toHaveKey('field_clinical_resource_locations');
});

test('only expertise, condition and treatment posts have a provider field to watch', function (string|false $type, string $expected): void {
    uamswp_test_acf_functions();

    expect(uamswp_spotlight_ontology_provider_field($type))->toBe($expected);
})->with([
    'expertise' => ['expertise', 'field_expertise_physicians'],
    'condition' => ['condition', 'field_condition_physicians'],
    'treatment' => ['treatment', 'field_treatment_procedure_physicians'],
    'location' => ['location', ''],
    'no type' => [false, ''],
]);

test('the providers before a save are remembered per post and default to none', function (): void {
    uamswp_test_acf_functions();

    expect(uamswp_spotlight_ontology_providers_before(901))->toBe([])
        ->and(uamswp_spotlight_ontology_providers_before('901', [1, 2]))->toBe([1, 2])
        ->and(uamswp_spotlight_ontology_providers_before(901))->toBe([1, 2])
        ->and(uamswp_spotlight_ontology_providers_before(902))->toBe([])
        ->and(uamswp_spotlight_ontology_providers_before(901, []))->toBe([]);
});

test('capturing and guarding an ontology save re-syncs the providers added and removed', function (): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn('condition');
    when('is_user_logged_in')->justReturn(false);
    when('get_posts')->alias(static fn (array $args): array => match ($args['meta_query'][1]['value']) {
        7 => [11],
        8 => [12],
        default => [],
    });

    // Before the save the condition lists provider 8; the save leaves provider 7.
    spot_fields(['field_condition_physicians|903' => [8]]);
    uamswp_spotlight_capture_ontology_providers(903);
    spot_fields([
        'field_condition_physicians|903' => [7],
        'physician_conditions_cpt|7' => [50],
        'physician_conditions_cpt|8' => [],
    ]);
    $writes = spot_writes();

    uamswp_spotlight_ontology_guard(903);

    expect($writes->getArrayCopy())->toBe([
        ['clinical_resource_conditions', [50], 11],
        ['field_condition_clinical_resources', [11], 50],
    ]);
});

test('the ontology capture and guard ignore term ids and post types without a provider field', function (string|false $type, mixed $post): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn($type);
    Functions\expect('get_field')->never();
    Functions\expect('get_posts')->never();

    uamswp_spotlight_capture_ontology_providers($post);
    uamswp_spotlight_ontology_guard($post);
})->with([
    'a term id' => ['condition', 'term_5'],
    'a location' => ['location', 5],
    'no post type' => [false, 5],
]);

/**
 * Collect the validation errors the Epic DEP ID check adds.
 *
 * @return ArrayObject<string, string>
 */
function spot_validation_errors(): ArrayObject
{
    $errors = new ArrayObject;
    when('acf_add_validation_error')->alias(static function (string $input, string $message) use ($errors): void {
        $errors[$input] = $message;
    });
    when('esc_html')->alias(static fn (string $text): string => htmlspecialchars($text));

    return $errors;
}

test('the Epic DEP ID validation passes distinct rows and a value that is not a list', function (mixed $value): void {
    uamswp_test_acf_functions();
    $errors = spot_validation_errors();

    $result = uamswp_location_validate_epic_dep_ids(true, $value, [], 'acf[field_x]');

    expect($result)->toBeTrue()
        ->and($errors->getArrayCopy())->toBe([]);
})->with([
    'distinct rows' => [[['field_location_epic_dep_id_item' => '900'], ['field_location_epic_dep_id_item' => '901']]],
    'nothing' => [null],
    'text' => ['900'],
    'an empty list' => [[]],
    'rows without the item' => [[['other' => 'x'], 'not a row']],
    'the clone template and a deleted row' => [['acfcloneindex' => ['field_location_epic_dep_id_item' => ''], 'row-1_deleted' => ['field_location_epic_dep_id_item' => '']]],
]);

test('the Epic DEP ID validation leaves an earlier failure alone', function (): void {
    uamswp_test_acf_functions();
    $errors = spot_validation_errors();

    expect(uamswp_location_validate_epic_dep_ids('Failed', [['field_location_epic_dep_id_item' => '']], [], 'acf[field_x]'))->toBe('Failed')
        ->and($errors->getArrayCopy())->toBe([]);
});

test('a blank Epic DEP ID row is rejected on its own input', function (string $blank): void {
    uamswp_test_acf_functions();
    $errors = spot_validation_errors();

    $result = uamswp_location_validate_epic_dep_ids(true, [['field_location_epic_dep_id_item' => '900'], ['field_location_epic_dep_id_item' => $blank]], [], 'acf[field_x]');

    expect($result)->toBeTrue()
        ->and($errors->getArrayCopy())->toBe(['acf[field_x][1][field_location_epic_dep_id_item]' => 'A UAMS Health Epic DEP ID is required.']);
})->with(['', '   ']);

test('a repeated Epic DEP ID is rejected on the later row, ignoring case and surrounding spaces', function (): void {
    uamswp_test_acf_functions();
    $errors = spot_validation_errors();

    uamswp_location_validate_epic_dep_ids(true, [
        'row-a' => ['field_location_epic_dep_id_item' => 'Dep<1>'],
        'row-b' => ['field_location_epic_dep_id_item' => ' dep<1> '],
        'row-c' => ['field_location_epic_dep_id_item' => 'dep<2>'],
    ], [], 'acf[field_x]');

    expect($errors->getArrayCopy())->toBe([
        'acf[field_x][row-b][field_location_epic_dep_id_item]' => 'UAMS Health Epic DEP ID "dep&lt;1&gt;" is listed more than once. Each DEP ID may appear only once on a location.',
    ]);
});

test('the request helper is used for the submitted values when ACF provides it', function (): void {
    uamswp_test_acf_functions();
    when('acf_maybe_get_POST')->alias(static fn (string $key): mixed => match ($key) {
        'acf' => ['field_clinical_resource_type' => ['value' => 'provider_spotlight']],
        'post_ID' => '77',
        default => null,
    });
    $_POST = ['acf' => ['field_clinical_resource_type' => 'faq']];

    expect(uamswp_spotlight_submitted_value('field_clinical_resource_type'))->toBe(['value' => 'provider_spotlight'])
        ->and(uamswp_spotlight_current_type())->toBe('provider_spotlight')
        ->and(uamswp_is_spotlight_submission())->toBeTrue();
});

test('the request helper supplies the post id when no type was submitted', function (): void {
    uamswp_test_acf_functions();
    when('acf_maybe_get_POST')->alias(static fn (string $key): mixed => $key === 'post_ID' ? '77' : null);
    Functions\expect('get_field')->once()->with('clinical_resource_type', '77')->andReturn('provider_spotlight');
    $_POST = [];

    expect(uamswp_spotlight_current_type())->toBe('provider_spotlight');
});

test('an acting user skips an ontology post they cannot edit and still updates the next one', function (): void {
    uamswp_test_acf_functions();
    when('is_user_logged_in')->justReturn(true);
    when('current_user_can')->alias(static fn (string $capability, int $id): bool => ! in_array($id, [30, 32], true));
    spot_fields([
        'physician_locations|7' => [30, 31],
        'clinical_resource_locations|5' => [32, 33],
        'field_location_clinical_resources|30' => [],
        'field_location_clinical_resources|31' => [],
        'field_location_clinical_resources|32' => [5],
        'field_location_clinical_resources|33' => [5],
    ]);
    $writes = spot_writes();

    uamswp_spotlight_sync_ontology(5, 7);

    expect($writes->getArrayCopy())->toBe([
        ['clinical_resource_locations', [30, 31], 5],
        ['field_location_clinical_resources', [5], 31],
        ['field_location_clinical_resources', [], 33],
    ]);
});

test('deleting a spotlight releases its provider link and clears its ontology mirror', function (): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn('clinical-resource');
    when('is_user_logged_in')->justReturn(false);
    spot_fields([
        'clinical_resource_type' => 'provider_spotlight',
        'clinical_resource_spotlight_provider' => 7,
        'clinical_resource_providers|5' => [7],
        'physician_clinical_resources|7' => [5],
        'clinical_resource_treatments|5' => [60],
        'field_treatment_procedure_clinical_resources|60' => [5],
    ]);
    $writes = spot_writes();

    uamswp_spotlight_delete_sync('5');

    expect($writes->getArrayCopy())->toContain(['clinical_resource_providers', [], 5])
        ->toContain(['physician_clinical_resources', [], 7])
        ->toContain(['clinical_resource_treatments', [], 5])
        ->toContain(['field_treatment_procedure_clinical_resources', [], 60]);
});

test('deleting something that is not a featured spotlight changes nothing', function (string $type, string $stored, int $provider, mixed $post): void {
    uamswp_test_acf_functions();
    when('get_post_type')->justReturn($type);
    spot_fields(['clinical_resource_type' => $stored, 'clinical_resource_spotlight_provider' => $provider]);
    Functions\expect('update_field')->never();

    uamswp_spotlight_delete_sync($post);
})->with([
    'a term id' => ['clinical-resource', 'provider_spotlight', 7, 'term_5'],
    'a provider post' => ['provider', 'provider_spotlight', 7, 5],
    'another resource type' => ['clinical-resource', 'faq', 7, 5],
    'no featured provider' => ['clinical-resource', 'provider_spotlight', 0, 5],
]);
