<?php

/**
 * The clinical resource template (templates/single-clinical-resource.php): the title attributes, the hooks it
 * adds and removes, the queries for each related section, which sections show, and the jump link count.
 *
 * The template sets its variables and hands the page to genesis(), which is stubbed, so each test renders it
 * and reads the variables it left behind. The section callbacks themselves live in
 * includes/class.fad-single-clinical-resource.php and are tested there.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';

/**
 * Render the template for one resource and return the variables it set.
 *
 * @param  array<string, mixed>  $fields  The resource's ACF fields by name.
 * @param  array<string, mixed>  $options  `title` (the post title), `option` (the options page fields), `status`
 *                                         (post id => status, default publish), and `found` (post type => the
 *                                         ids its query finds).
 * @return array<string, mixed>
 */
function single_clinical_resource(array $fields = [], array $options = []): array
{
    $option = $options['option'] ?? [];
    $status = $options['status'] ?? [];
    $found = $options['found'] ?? [];

    when('get_the_ID')->justReturn(7);
    when('get_the_title')->justReturn($options['title'] ?? 'Flu Facts');
    when('get_post_status')->alias(static fn (mixed $id): string => $status[$id] ?? 'publish');
    when('get_field')->alias(static fn (string $name, mixed $id = null): mixed => $id === 'option' ? ($option[$name] ?? null) : ($fields[$name] ?? null));

    // The queries in the order the template builds them; the provider and location queries only run when their field is set.
    $queue = [$found['condition'] ?? [], $found['treatment'] ?? []];
    if (! empty($fields['clinical_resource_providers'])) {
        $queue[] = $found['provider'] ?? [];
    }

    if (! empty($fields['clinical_resource_locations'])) {
        $queue[] = $found['location'] ?? [];
    }

    $queue[] = $found['expertise'] ?? [];
    $queue[] = $found['clinical-resource'] ?? [];
    WP_Query::$queue = $queue;
    WP_Query::$queries = [];

    // An earlier render's variables are cleared so a branch that sets nothing cannot read a stale value.
    foreach (['show_providers_section', 'show_locations_section', 'physicians_query', 'location_query', 'resource_count', 'postsPerPage', 'postsCountClass', 'postsCutoff'] as $name) {
        unset($GLOBALS[$name]);
    }

    $output = uamswp_test_render_template('single-clinical-resource');
    $GLOBALS['single_clinical_resource_output'] = $output;
    $GLOBALS['single_clinical_resource_queries'] = WP_Query::$queries;

    return $GLOBALS;
}

/** The fields of an article related to one of everything. */
function clinical_resource_everything(): array
{
    return [
        'clinical_resource_type' => ['value' => 'article', 'label' => 'Article'],
        'clinical_resource_conditions' => [11],
        'clinical_resource_treatments' => [12],
        'clinical_resource_providers' => [13],
        'clinical_resource_locations' => [14],
        'clinical_resource_aoe' => [15],
        'clinical_resource_related' => [16, 17],
    ];
}

/** What the queries of an article related to one of everything find. */
function clinical_resource_found(): array
{
    return ['condition' => [11], 'treatment' => [12], 'provider' => [13], 'location' => [14], 'expertise' => [15], 'clinical-resource' => [16, 17]];
}

test('the template renders nothing itself and hands the page to genesis', function (): void {
    $vars = single_clinical_resource(['clinical_resource_type' => ['value' => 'article', 'label' => 'Article']]);

    expect($vars['single_clinical_resource_output'])->toBe('')
        ->and($vars['uamswp_test_genesis_calls'])->toBe(1)
        ->and($vars['page_id'])->toBe(7);
});

test('the title attributes swap double quotes, turn non-breaking spaces into spaces and decode entities', function (): void {
    $title = "A \"B\"&nbsp;C & D\u{E9}\u{A0}E";
    $vars = single_clinical_resource(
        ['clinical_resource_type' => ['value' => 'article', 'label' => 'Article']],
        ['title' => $title, 'option' => ['clinical_resource_archive_headline' => $title]],
    );

    expect($vars['page_title'])->toBe($title)
        ->and($vars['page_title_attr'])->toBe("A 'B' C & D\u{E9} E")
        ->and($vars['resource_archive_title_system'])->toBe($title)
        ->and($vars['resource_archive_title'])->toBe($title)
        ->and($vars['resource_archive_title_attr'])->toBe("A 'B' C & D\u{E9} E");
});

test('the archive title defaults to Clinical Resource', function (): void {
    $vars = single_clinical_resource(['clinical_resource_type' => ['value' => 'article', 'label' => 'Article']]);

    expect($vars['resource_archive_title_system'])->toBeNull()
        ->and($vars['resource_archive_title'])->toBe('Clinical Resource')
        ->and($vars['resource_archive_title_attr'])->toBe('Clinical Resource');
});

test('the template adds the title, canonical and section hooks and removes the Genesis post info and title', function (): void {
    single_clinical_resource(['clinical_resource_type' => ['value' => 'article', 'label' => 'Article']]);

    expect(uamswp_test_render_hooks())->toBe([
        'add_filter' => [
            ['seopress_titles_title', 'uamswp_fad_title_single_clinical_resource', 15, 2],
            ['seopress_titles_canonical', 'uamswp_fad_canonical'],
            ['body_class', 'uams_default_page_body_class'],
            ['genesis_attr_entry', 'uamswp_add_entry_class'],
        ],
        'remove_action' => [
            ['genesis_entry_header', 'genesis_post_info', 12],
            ['genesis_entry_footer', 'genesis_post_info', 9],
            ['genesis_entry_footer', 'genesis_entry_footer_markup_open', 5],
            ['genesis_entry_footer', 'genesis_post_meta'],
            ['genesis_entry_footer', 'genesis_entry_footer_markup_close', 15],
            ['genesis_entry_header', 'genesis_do_post_title'],
        ],
        'add_action' => [
            ['genesis_entry_header', 'uamswp_resource_post_title'],
            ['genesis_entry_content', 'uamswp_resource_text', 8],
            ['genesis_entry_content', 'uamswp_resource_provider_spotlight', 9],
            ['genesis_entry_content', 'uamswp_resource_infographic', 10],
            ['genesis_entry_content', 'uamswp_resource_video', 12],
            ['genesis_entry_content', 'uamswp_resource_document', 14],
            ['genesis_after_entry', 'uamswp_resource_jump_links', 8],
            ['genesis_after_entry', 'uamswp_resource_associated', 10],
            ['genesis_after_entry', 'uamswp_resource_conditions_cpt', 12],
            ['genesis_after_entry', 'uamswp_resource_treatments_cpt', 14],
            ['genesis_after_entry', 'uamswp_resource_physicians', 16],
            ['genesis_after_entry', 'uamswp_resource_locations', 18],
            ['genesis_after_entry', 'uamswp_resource_expertise', 20],
            ['genesis_after_entry', 'uamswp_resource_appointment', 22],
        ],
    ]);
});

test('the syndication fields and the resource type are read into variables', function (): void {
    $vars = single_clinical_resource([
        'clinical_resource_type' => ['value' => 'video', 'label' => 'Video'],
        'clinical_resource_syndicated' => 1,
        'clinical_resource_syndication_url' => 'https://example.test/original/',
    ]);

    expect([$vars['syndicated'], $vars['syndication_url']])->toBe([1, 'https://example.test/original/'])
        ->and([$vars['resource_type_value'], $vars['resource_type_label']])->toBe(['video', 'Video'])
        ->and($vars['is_provider_spotlight'])->toBeFalse()
        ->and($vars['spotlight_provider'])->toBe(0);
});

test('each related section queries its published posts by the ids the resource lists', function (): void {
    $vars = single_clinical_resource(clinical_resource_everything(), ['found' => clinical_resource_found()]);

    expect($vars['single_clinical_resource_queries'])->toBe([
        ['post_type' => 'condition', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1, 'post__in' => [11]],
        ['post_type' => 'treatment', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1, 'post__in' => [12]],
        ['post_type' => 'provider', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post__in' => [13]],
        ['post_type' => 'location', 'order' => 'ASC', 'orderby' => 'title', 'posts_per_page' => -1, 'post_status' => 'publish', 'post__in' => [14]],
        ['post_type' => 'expertise', 'order' => 'ASC', 'orderby' => 'title', 'posts_per_page' => -1, 'post_status' => 'publish', 'post__in' => [15]],
        ['post_type' => 'clinical-resource', 'order' => 'DESC', 'orderby' => 'post_date', 'posts_per_page' => -1, 'post_status' => 'publish', 'post__in' => [16, 17]],
    ]);
});

test('every related section with published posts shows, and each adds a jump link', function (): void {
    $vars = single_clinical_resource(clinical_resource_everything(), ['found' => clinical_resource_found()]);

    expect([
        $vars['show_conditions_section'],
        $vars['show_treatments_section'],
        $vars['show_providers_section'],
        $vars['show_locations_section'],
        $vars['show_aoe_section'],
        $vars['show_related_resource_section'],
        $vars['show_appointment_section'],
        $vars['show_jump_links_section'],
    ])->toBe([true, true, true, true, true, true, true, true])
        ->and($vars['jump_link_count'])->toBe(7)
        ->and($vars['jump_link_count_min'])->toBe(2)
        ->and($vars['resource_count'])->toBe(2)
        ->and([$vars['resource_postsPerPage'], $vars['resource_more']])->toBe([-1, false]);
});

test('a section whose ids find no published post is hidden and adds no jump link', function (): void {
    $vars = single_clinical_resource(clinical_resource_everything());

    expect([
        $vars['show_conditions_section'],
        $vars['show_treatments_section'],
        $vars['show_providers_section'],
        $vars['show_locations_section'],
        $vars['show_aoe_section'],
        $vars['show_related_resource_section'],
    ])->toBe([false, false, false, false, false, false])
        ->and($vars['jump_link_count'])->toBe(1)
        ->and($vars['show_jump_links_section'])->toBeFalse();
});

test('a section with no ids is hidden even when its query finds posts', function (): void {
    $vars = single_clinical_resource(
        ['clinical_resource_type' => ['value' => 'article', 'label' => 'Article']],
        ['found' => ['condition' => [11], 'treatment' => [12], 'expertise' => [15], 'clinical-resource' => [16]]],
    );

    expect([
        $vars['show_conditions_section'],
        $vars['show_treatments_section'],
        $vars['show_aoe_section'],
        $vars['show_related_resource_section'],
    ])->toBe([false, false, false, false])
        ->and(isset($vars['show_providers_section']))->toBeFalse()
        ->and(isset($vars['show_locations_section']))->toBeFalse()
        ->and($vars['jump_link_count'])->toBe(1);
});

test('the jump links show from two sections, the appointment one included', function (): void {
    $vars = single_clinical_resource(
        ['clinical_resource_type' => ['value' => 'article', 'label' => 'Article'], 'clinical_resource_aoe' => [15]],
        ['found' => ['expertise' => [15]]],
    );

    expect($vars['jump_link_count'])->toBe(2)
        ->and($vars['show_jump_links_section'])->toBeTrue();
});

test('the providers list shows them all up to 18 and pages by 12 beyond', function (int $count, int $per_page): void {
    $ids = range(100, 99 + $count);
    $vars = single_clinical_resource(
        ['clinical_resource_type' => ['value' => 'article', 'label' => 'Article'], 'clinical_resource_providers' => $ids],
        ['found' => ['provider' => [100]]],
    );

    expect($vars['single_clinical_resource_queries'][2]['posts_per_page'])->toBe($per_page)
        ->and($vars['postsCountClass'])->toBe(12)
        ->and($vars['postsCutoff'])->toBe(18);
})->with([
    '18 providers' => [18, -1],
    '19 providers' => [19, 12],
]);

test('a provider spotlight reads its published provider and hides every related section', function (): void {
    $vars = single_clinical_resource(
        ['clinical_resource_type' => ['value' => 'provider_spotlight', 'label' => 'Provider Spotlight'], 'clinical_resource_spotlight_provider' => '13'] + array_diff_key(clinical_resource_everything(), ['clinical_resource_type' => 1]),
        ['found' => clinical_resource_found()],
    );

    expect($vars['is_provider_spotlight'])->toBeTrue()
        ->and($vars['spotlight_provider'])->toBe(13)
        ->and([
            $vars['show_conditions_section'],
            $vars['show_treatments_section'],
            $vars['show_providers_section'],
            $vars['show_locations_section'],
            $vars['show_aoe_section'],
            $vars['show_related_resource_section'],
        ])->toBe([false, false, false, false, false, false])
        ->and($vars['show_appointment_section'])->toBeTrue()
        ->and($vars['jump_link_count'])->toBe(1)
        ->and($vars['show_jump_links_section'])->toBeFalse();
});

test('a spotlight provider that is no longer published is dropped', function (): void {
    $vars = single_clinical_resource(
        ['clinical_resource_type' => ['value' => 'provider_spotlight', 'label' => 'Provider Spotlight'], 'clinical_resource_spotlight_provider' => 13],
        ['status' => [13 => 'draft']],
    );

    expect($vars['spotlight_provider'])->toBe(0);
});

test('a spotlight provider is read only for a provider spotlight', function (): void {
    $vars = single_clinical_resource(['clinical_resource_type' => ['value' => 'article', 'label' => 'Article'], 'clinical_resource_spotlight_provider' => 13]);

    expect($vars['spotlight_provider'])->toBe(0);
});
