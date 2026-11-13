<?php

/**
 * Empty relationship and scalar metadata contracts for non-provider content.
 *
 * One approved equivalent is ignored in get_resource_meta(): the resource
 * type is a schema radio choice, a string, so comparing it to
 * 'provider_spotlight' loosely or strictly gives the same result.
 *
 * These tests model getter results only. Published selection, query ordering,
 * and WordPress persistence remain covered by Integration/RestMetadataTest.php.
 *
 * @command  vendor/bin/pest --ci tests/Unit/ContentMetadataBaselineTest.php
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/query-posts.php';

beforeEach(function (): void {
    // Load metadata callbacks without registering WordPress hooks.
    when('add_action')->justReturn(true);
    when('add_filter')->justReturn(true);
    require_once dirname(__DIR__, 2).'/includes/class.fad-custom-post.php';

    // Keep source IDs observable and ordinary absent ACF fields false.
    $this->fields = [101 => ['clinical_resource_type' => ['value' => 'text', 'label' => 'Article']]];
    when('get_field')->alias(fn (
        string $field,
        mixed $id,
    ): mixed => $this->fields[$id][$field] ?? false);
    when('get_the_title')->alias(fn (int $id): string => 'Content '.$id);
    when('get_permalink')->alias(fn (int $id): string => 'https://example.test/content-'.$id);
    when('get_the_post_thumbnail_url')->alias(fn (
        int $id,
        string $size,
    ): string => 'https://example.test/'.$id.'-'.$size.'.jpg');

    // Start each test with no queued or recorded relationship queries.
    reset_query_stand_in();
});

test('condition and treatment metadata omit absent item collections and retain empty lists', function (
    string $callback,
    array $expected,
): void {
    expect($callback(['id' => 101]))->toBe($expected);
})->with([
    'condition' => ['get_condition_meta', [
        'condition_expertise_list' => '',
        'condition_treatments_list' => '',
        'condition_provider_list' => '',
        'condition_location_list' => '',
    ]],
    'treatment' => ['get_treatment_meta', [
        'treatment_expertise_list' => '',
        'treatment_conditions_list' => '',
        'treatment_provider_list' => '',
        'treatment_location_list' => '',
    ]],
]);

test('expertise metadata retains the podcast widget with selected or absent names', function (string|false $name): void {
    // Use a literal widget contract independent of the callback under test.
    $this->fields[101]['expertise_podcast_name'] = $name;
    $widget = '<script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">
			</script>
			<script type="text/javascript">
				radiomd_embedded_filtered_tag("uams","radiomd-embedded-filtered-tag",303,"'.$name.'");
			</script>
			<style type="text/css">
				#radiomd-embedded-filtered-tag iframe {
				width: 100%;
				border: none;
			}
			</style>
			<div class="content-width mt-8" id="radiomd-embedded-filtered-tag"></div>';

    // Assert the entire payload, including optional collections remaining absent.
    expect(get_expertise_meta(['id' => 101]))->toBe([
        'expertise_title' => 'Content 101',
        'expertise_link' => 'https://example.test/content-101',
        'expertise_podcast' => $widget,
        'expertise_conditions_list' => '',
        'expertise_treatments_list' => '',
        'expertise_provider_list' => '',
        'expertise_location_list' => '',
    ]);
})->with(['absent' => [false], 'selected' => ['Heart Health']]);

test('clinical resource metadata preserves distinct scalar fields and absent collections', function (bool $populated): void {
    // Give each getter a distinct value so crossed fields cannot pass.
    $fields = [
        'clinical_resource_excerpt' => 'Short excerpt',
        'clinical_resource_text' => '<p>Resource &amp; text</p>',
        'clinical_resource_text_nci_query' => '1',
        'clinical_resource_nci_embed' => '<div>NCI content</div>',
        'clinical_resource_infographic' => 123,
        'clinical_resource_infographic_descr' => 'Image description',
        'clinical_resource_infographic_transcript' => 'Image transcript',
        'clinical_resource_video' => 'https://example.test/video',
        'clinical_resource_video_descr' => 'Video description',
        'clinical_resource_video_transcript' => 'Video transcript',
        'clinical_resource_title_short' => 'Short title',
        'clinical_resource_document_descr' => 'Document description',
    ];
    if ($populated) {
        $this->fields[101] += $fields;
    }

    $expected = [
        'clinical_resource_title' => 'Content 101',
        'clinical_resource_link' => 'https://example.test/content-101',
        'clinical_resource_excerpt' => false,
        'clinical_resource_image' => 'https://example.test/101-large.jpg',
        'clinical_resource_type' => 'Article',
        'clinical_resource_text' => false,
        'clinical_resource_text_nci_query' => false,
        'clinical_resource_nci_embed' => false,
        'clinical_resource_infographic' => false,
        'clinical_resource_infographic_descr' => false,
        'clinical_resource_infographic_transcript' => false,
        'clinical_resource_video' => false,
        'clinical_resource_video_descr' => false,
        'clinical_resource_video_transcript' => false,
        'clinical_resource_title_short' => false,
        'clinical_resource_document_descr' => false,
        'clinical_resource_conditions_list' => '',
        'clinical_resource_treatments_list' => '',
        'clinical_resource_provider_list' => '',
        'clinical_resource_location_list' => '',
        'clinical_resource_expertise_list' => '',
    ];
    if ($populated) {
        $expected = array_replace($expected, $fields);
    }

    // Pin public values without simulating queries or optional repeater internals.
    expect(get_resource_meta(['id' => 101]))->toBe($expected);
})->with(['absent' => [false], 'populated' => [true]]);

test('content relationship lists join published query results in their returned order', function (
    string $callback,
    string $field,
    array $query,
    string $key,
    string $list,
): void {
    // Return the posts in an order that differs from the selection.
    $this->fields[101][$field] = [302, 301];
    $this->fields[301] = ['physician_full_name' => 'Ann Alpha'];
    $this->fields[302] = ['physician_full_name' => 'Bob Beta'];
    WP_Query::$queue = [[query_post(301, 'Alpha', 'alpha'), query_post(302, 'Beta', 'beta')]];

    // Pin the query, every exported item, and the joined list.
    $data = $callback(['id' => 101]);
    $provider = $query['post_type'] === 'provider';
    expect(WP_Query::$queries)->toBe([$query + ['post__in' => [302, 301]]])
        ->and($data[$key])->toBe([
            301 => ['link' => 'https://example.test/content-301', 'title' => $provider ? 'Ann Alpha' : 'Alpha', 'slug' => 'alpha'],
            302 => ['link' => 'https://example.test/content-302', 'title' => $provider ? 'Bob Beta' : 'Beta', 'slug' => 'beta'],
        ])
        ->and($data[$key.'_list'])->toBe($list);
})->with(function (): array {
    // Queries for locations, and every clinical resource query, list their arguments in another order.
    $published = fn (string $type): array => ['post_type' => $type, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1];
    $location = fn (string $type): array => ['post_type' => $type, 'order' => 'ASC', 'orderby' => 'title', 'posts_per_page' => -1, 'post_status' => 'publish'];
    $resourceProvider = ['post_type' => 'provider', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'];

    return [
        'expertise conditions' => ['get_expertise_meta', 'expertise_conditions_cpt', $published('condition'), 'expertise_conditions', 'Alpha, Beta'],
        'expertise treatments' => ['get_expertise_meta', 'expertise_treatments_cpt', $published('treatment'), 'expertise_treatments', 'Alpha, Beta'],
        'expertise providers' => ['get_expertise_meta', 'physician_expertise', $published('provider'), 'expertise_provider', 'Ann Alpha | Bob Beta'],
        'expertise locations' => ['get_expertise_meta', 'location_expertise', $location('location'), 'expertise_location', 'Alpha, Beta'],
        'condition expertise' => ['get_condition_meta', 'condition_expertise', $published('expertise'), 'condition_expertise', 'Alpha, Beta'],
        'condition treatments' => ['get_condition_meta', 'condition_treatments', $published('treatment'), 'condition_treatments', 'Alpha, Beta'],
        'condition providers' => ['get_condition_meta', 'condition_physicians', $published('provider'), 'condition_provider', 'Ann Alpha | Bob Beta'],
        'condition locations' => ['get_condition_meta', 'condition_locations', $location('location'), 'condition_location', 'Alpha, Beta'],
        'treatment expertise' => ['get_treatment_meta', 'treatment_procedure_expertise', $published('expertise'), 'treatment_expertise', 'Alpha, Beta'],
        'treatment conditions' => ['get_treatment_meta', 'treatment_conditions', $published('condition'), 'treatment_conditions', 'Alpha, Beta'],
        'treatment providers' => ['get_treatment_meta', 'treatment_procedure_physicians', $published('provider'), 'treatment_provider', 'Ann Alpha | Bob Beta'],
        'treatment locations' => ['get_treatment_meta', 'treatment_procedure_locations', $location('location'), 'treatment_location', 'Alpha, Beta'],
        'resource conditions' => ['get_resource_meta', 'clinical_resource_conditions', $published('condition'), 'clinical_resource_conditions', 'Alpha, Beta'],
        'resource treatments' => ['get_resource_meta', 'clinical_resource_treatments', $published('treatment'), 'clinical_resource_treatments', 'Alpha, Beta'],
        'resource providers' => ['get_resource_meta', 'clinical_resource_providers', $resourceProvider, 'clinical_resource_provider', 'Ann Alpha | Bob Beta'],
        'resource locations' => ['get_resource_meta', 'clinical_resource_locations', $location('location'), 'clinical_resource_location', 'Alpha, Beta'],
        'resource expertise' => ['get_resource_meta', 'clinical_resource_aoe', $location('expertise'), 'clinical_resource_expertise', 'Alpha, Beta'],
    ];
});

test('content relationship lists stay empty when no selected post is published', function (
    string $callback,
    array $fields,
    array $keys,
): void {
    // Each query finds nothing although every field has a selection.
    foreach ($fields as $field) {
        $this->fields[101][$field] = [301];
    }

    $data = $callback(['id' => 101]);
    expect(WP_Query::$queries)->toHaveCount(\count($fields))
        ->and($data)->not->toHaveKeys($keys);
    foreach ($keys as $key) {
        expect($data[$key.'_list'])->toBe('');
    }
})->with([
    'expertise' => ['get_expertise_meta', ['expertise_conditions_cpt', 'expertise_treatments_cpt', 'physician_expertise', 'location_expertise'], ['expertise_conditions', 'expertise_treatments', 'expertise_provider', 'expertise_location']],
    'condition' => ['get_condition_meta', ['condition_expertise', 'condition_treatments', 'condition_physicians', 'condition_locations'], ['condition_expertise', 'condition_treatments', 'condition_provider', 'condition_location']],
    'treatment' => ['get_treatment_meta', ['treatment_procedure_expertise', 'treatment_conditions', 'treatment_procedure_physicians', 'treatment_procedure_locations'], ['treatment_expertise', 'treatment_conditions', 'treatment_provider', 'treatment_location']],
    'clinical resource' => ['get_resource_meta', ['clinical_resource_conditions', 'clinical_resource_treatments', 'clinical_resource_providers', 'clinical_resource_locations', 'clinical_resource_aoe'], ['clinical_resource_conditions', 'clinical_resource_treatments', 'clinical_resource_provider', 'clinical_resource_location', 'clinical_resource_expertise']],
]);

test('clinical resource metadata lists published related resources newest first without a joined list', function (): void {
    // Return the related resources in an order that differs from the selection.
    $this->fields[101]['clinical_resource_related'] = [302, 301];
    WP_Query::$queue = [[query_post(302, 'Beta', 'beta'), query_post(301, 'Alpha', 'alpha')]];

    $data = get_resource_meta(['id' => 101]);
    expect(WP_Query::$queries)->toBe([[
        'post_type' => 'clinical-resource',
        'order' => 'DESC',
        'orderby' => 'post_date',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post__in' => [302, 301],
    ]])->and($data['clinical_resource_associated'])->toBe([
        302 => ['link' => 'https://example.test/content-302', 'title' => 'Beta', 'slug' => 'beta'],
        301 => ['link' => 'https://example.test/content-301', 'title' => 'Alpha', 'slug' => 'alpha'],
    ]);
});

test('clinical resource metadata exports titled documents that have a file, keyed by row', function (): void {
    // Skip an untitled row and a row without a file between two complete rows.
    $this->fields[101]['clinical_resource_document'] = [
        ['document_title' => 'Patient guide', 'document_file' => ['url' => 'https://example.test/guide.pdf']],
        ['document_title' => '', 'document_file' => ['url' => 'https://example.test/untitled.pdf']],
        ['document_title' => 'Missing file', 'document_file' => false],
        ['document_title' => 'Consent form', 'document_file' => ['url' => 'https://example.test/consent.pdf']],
    ];
    when('user_trailingslashit')->alias(fn (string $url): string => $url.'/');

    expect(get_resource_meta(['id' => 101])['clinical_resource_document'])->toBe([
        0 => ['title' => 'Patient guide', 'url' => 'https://example.test/guide.pdf/'],
        3 => ['title' => 'Consent form', 'url' => 'https://example.test/consent.pdf/'],
    ]);
});

test('clinical resource metadata exports a spotlight only for a published provider on a spotlight resource', function (
    string $type,
    int|false $provider,
    string|false $status,
    bool $exported,
): void {
    // Give every spotlight field a distinct value, including a skipped unasked question.
    $this->fields[101] = [
        'clinical_resource_type' => ['value' => $type, 'label' => 'Label'],
        'clinical_resource_spotlight_provider' => $provider,
        'clinical_resource_spotlight_image_wide' => 77,
        'clinical_resource_spotlight_phone' => '501-555-0100',
        'clinical_resource_spotlight_qa' => [
            ['spotlight_qa_question' => 'Why medicine?', 'spotlight_qa_answer' => '<p>To help.</p>'],
            ['spotlight_qa_question' => '', 'spotlight_qa_answer' => '<p>Unasked.</p>'],
            ['spotlight_qa_question' => 'Favorite place?', 'spotlight_qa_answer' => ''],
        ],
    ];
    $this->fields[501] = ['physician_full_name' => 'Ann Lee'];

    // Only the selected provider has the status under test; any other ID reads as published.
    when('get_post_status')->alias(fn (int $id): string|false => $id === 501 ? $status : 'publish');
    when('get_post_field')->alias(fn (string $field, int $id): string => $field.'-'.$id);
    when('uamswp_spotlight_default_intro')->alias(fn (int $id): string => 'Introduction for '.$id);

    // Compare every spotlight key, or require that none is exported.
    $data = get_resource_meta(['id' => 101]);
    $spotlight = array_filter($data, fn (string $key): bool => str_starts_with($key, 'clinical_resource_spotlight'), ARRAY_FILTER_USE_KEY);
    expect($spotlight)->toBe($exported ? [
        'clinical_resource_spotlight_provider' => [
            'id' => 501,
            'link' => 'https://example.test/content-501',
            'title' => 'Ann Lee',
            'slug' => 'post_name-501',
        ],
        'clinical_resource_spotlight_intro' => 'Introduction for 501',
        'clinical_resource_spotlight_image_wide' => 77,
        'clinical_resource_spotlight_phone' => '501-555-0100',
        'clinical_resource_spotlight_qa' => [
            0 => ['question' => 'Why medicine?', 'answer' => '<p>To help.</p>'],
            2 => ['question' => 'Favorite place?', 'answer' => ''],
        ],
    ] : []);
})->with([
    'published spotlight' => ['provider_spotlight', 501, 'publish', true],
    'draft provider' => ['provider_spotlight', 501, 'draft', false],
    'trashed provider' => ['provider_spotlight', 501, 'trash', false],
    'deleted provider' => ['provider_spotlight', 501, false, false],
    'no provider selected' => ['provider_spotlight', false, 'publish', false],
    'another resource type' => ['text', 501, 'publish', false],
]);
