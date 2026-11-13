<?php

/**
 * The area of expertise template (templates/single-expertise.php): the title attributes, the parent area, the
 * hooks it adds and removes, the queries for each related section, which sections show, and the jump link
 * count.
 *
 * The template sets its variables and hands the page to genesis(), which is stubbed, so each test renders it
 * and reads the variables it left behind. The section callbacks themselves live in includes/ and are tested
 * there.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';

/**
 * Render the template for one area of expertise and return the variables it set.
 *
 * @param  array<string, mixed>  $fields  The area's ACF fields by name.
 * @param  array<string, mixed>  $options  `title` (the post title), `option` (the options page fields), `parent`
 *                                         (the parent post object, or null), `parent_gone` (true when that post
 *                                         can no longer be read), `hide_children` (the post meta),
 *                                         `children` (the child pages found), and `found` (post type => the ids
 *                                         its query finds).
 * @return array<string, mixed>
 */
function single_expertise(array $fields = [], array $options = []): array
{
    $option = $options['option'] ?? [];
    $found = $options['found'] ?? [];
    $parent = $options['parent'] ?? null;
    $GLOBALS['single_expertise_pages_args'] = [];
    $GLOBALS['single_expertise_meta_args'] = [];

    when('get_the_ID')->justReturn(7);
    when('get_the_title')->justReturn($options['title'] ?? 'Eye Care');
    when('get_field')->alias(static fn (string $name, mixed $id = null): mixed => $id === 'option' ? ($option[$name] ?? null) : ($fields[$name] ?? null));
    when('wp_get_post_parent_id')->alias(static fn (mixed $id): int => $id === 7 && $parent ? (int) $parent->ID : 0);
    when('get_post')->alias(static fn (mixed $id): ?object => $parent && empty($options['parent_gone']) && $id === $parent->ID ? $parent : null);
    when('get_permalink')->alias(static fn (mixed $id): string => 'https://example.test/p/'.$id.'/');
    when('get_post_meta')->alias(static function (mixed ...$args) use ($options): mixed {
        $GLOBALS['single_expertise_meta_args'][] = $args;

        return $options['hide_children'] ?? '';
    });
    when('get_pages')->alias(static function (array $args) use ($options): array {
        $GLOBALS['single_expertise_pages_args'][] = $args;

        return $options['children'] ?? [];
    });

    // The queries in the order the template builds them; the provider and location queries only run when their field is set.
    $queue = [$found['clinical-resource'] ?? [], $found['condition'] ?? [], $found['treatment'] ?? []];
    if (! empty($fields['physician_expertise'])) {
        $queue[] = $found['provider'] ?? [];
    }

    if (! empty($fields['location_expertise'])) {
        $queue[] = $found['location'] ?? [];
    }

    $queue[] = $found['expertise'] ?? [];
    WP_Query::$queue = $queue;
    WP_Query::$queries = [];

    // An earlier render's variables are cleared so a branch that sets nothing cannot read a stale value.
    foreach (['show_providers_section', 'show_locations_section', 'provider_ids', 'parent_title_attr'] as $name) {
        unset($GLOBALS[$name]);
    }

    $GLOBALS['single_expertise_output'] = uamswp_test_render_template('single-expertise');
    $GLOBALS['single_expertise_queries'] = WP_Query::$queries;

    return $GLOBALS;
}

/** The fields of an area related to one of everything. */
function expertise_everything(): array
{
    return [
        'expertise_podcast_name' => 'Eye Talk',
        'expertise_clinical_resources' => [21],
        'expertise_conditions_cpt' => [22],
        'expertise_treatments_cpt' => [23],
        'physician_expertise' => [24, 25],
        'location_expertise' => [26],
        'expertise_associated' => [27],
    ];
}

/** What the queries of an area related to one of everything find. */
function expertise_found(): array
{
    return ['clinical-resource' => [21], 'condition' => [22], 'treatment' => [23], 'provider' => [24, 25], 'location' => [26], 'expertise' => [27]];
}

test('the template renders nothing itself and hands the page to genesis', function (): void {
    $vars = single_expertise();

    expect($vars['single_expertise_output'])->toBe('')
        ->and($vars['uamswp_test_genesis_calls'])->toBe(1)
        ->and($vars['page_id'])->toBe(7);
});

test('the title attributes swap double quotes, turn non-breaking spaces into spaces and decode entities', function (): void {
    $title = "A \"B\"&nbsp;C & D\u{E9}\u{A0}E";
    $vars = single_expertise(options: ['title' => $title, 'option' => ['expertise_archive_headline' => $title]]);

    expect($vars['page_title'])->toBe($title)
        ->and($vars['page_title_attr'])->toBe("A 'B' C & D\u{E9} E")
        ->and($vars['expertise_archive_title'])->toBe($title)
        ->and($vars['expertise_archive_title_attr'])->toBe("A 'B' C & D\u{E9} E")
        ->and($vars['expertise_single_name'])->toBe($title)
        ->and($vars['expertise_single_name_attr'])->toBe("A 'B' C & D\u{E9} E");
});

test('the archive title and the single name default when the headline is not set', function (): void {
    $vars = single_expertise();

    expect([$vars['expertise_archive_title'], $vars['expertise_archive_title_attr']])->toBe(['Areas of Expertise', 'Areas of Expertise'])
        ->and([$vars['expertise_single_name'], $vars['expertise_single_name_attr']])->toBe(['Area of Expertise', 'Area of Expertise']);
});

test('an area with no parent leaves the parent variables empty', function (): void {
    $vars = single_expertise();

    expect([$vars['expertise_parent_id'], $vars['expertise_has_parent']])->toBe([0, false])
        ->and([$vars['parent_expertise'], $vars['parent_id'], $vars['parent_title'], $vars['parent_url']])->toBe(['', '', '', ''])
        ->and(isset($vars['parent_title_attr']))->toBeFalse();
});

test('a parent area gives its id, title, title attribute and link', function (): void {
    $parent = (object) ['ID' => 5, 'post_title' => "Mom&#8217;s \"Care\"&nbsp;Plus \u{A0}Co & Caf\u{E9}"];

    $vars = single_expertise(options: ['parent' => $parent]);

    expect([$vars['expertise_parent_id'], $vars['expertise_has_parent']])->toBe([5, true])
        ->and($vars['parent_expertise'])->toBe($parent)
        ->and([$vars['parent_id'], $vars['parent_title'], $vars['parent_url']])->toBe([5, $parent->post_title, 'https://example.test/p/5/'])
        ->and($vars['parent_title_attr'])->toBe("Mom's 'Care' Plus  Co & Caf\u{E9}");
});

test('a parent id whose post is gone leaves the parent variables empty', function (): void {
    $parent = (object) ['ID' => 5, 'post_title' => 'Gone'];
    $vars = single_expertise(options: ['parent' => $parent, 'parent_gone' => true]);

    expect($vars['expertise_has_parent'])->toBeTrue()
        ->and([$vars['parent_expertise'], $vars['parent_id'], $vars['parent_title'], $vars['parent_url']])->toBe([null, '', '', '']);
});

test('the template adds the title, entry and section hooks and removes the Genesis post info and title', function (): void {
    single_expertise();

    expect(uamswp_test_render_hooks())->toBe([
        'add_filter' => [
            ['seopress_titles_title', 'uamswp_fad_title_single_expertise', 15, 2],
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
            ['genesis_entry_header', 'uamswp_expertise_post_title'],
            ['genesis_entry_content', 'uamswp_expertise_keywords', 8],
            ['genesis_entry_content', 'uamswp_expertise_youtube', 12],
            ['genesis_after_entry', 'uamswp_expertise_cta', 6],
            ['genesis_after_entry', 'uamswp_expertise_jump_links', 8],
            ['genesis_after_entry', 'uamswp_expertise_podcast', 10],
            ['genesis_after_entry', 'uamswp_list_child_expertise', 12],
            ['genesis_after_entry', 'uamswp_expertise_resource', 14],
            ['genesis_after_entry', 'uamswp_expertise_conditions_cpt', 16],
            ['genesis_after_entry', 'uamswp_expertise_treatments_cpt', 18],
            ['genesis_after_entry', 'uamswp_expertise_physicians', 20],
            ['genesis_after_entry', 'uamswp_expertise_locations', 22],
            ['genesis_after_entry', 'uamswp_expertise_associated', 24],
            ['genesis_after_entry', 'uamswp_expertise_appointment', 26],
            ['wp_head', 'uamswp_expertise_header_metadata'],
        ],
    ]);
});

test('each related section queries its published posts by the ids the area lists', function (): void {
    $vars = single_expertise(expertise_everything(), ['found' => expertise_found()]);

    expect($vars['single_expertise_queries'])->toBe([
        ['post_type' => 'clinical-resource', 'order' => 'DESC', 'orderby' => 'post_date', 'posts_per_page' => 4, 'post_status' => 'publish', 'post__in' => [21]],
        ['post_type' => 'condition', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1, 'post__in' => [22]],
        ['post_type' => 'treatment', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1, 'post__in' => [23]],
        ['post_type' => 'provider', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'fields' => 'ids', 'update_post_term_cache' => false, 'update_post_meta_cache' => false, 'post__in' => [24, 25]],
        ['post_type' => 'location', 'post_status' => 'publish', 'order' => 'ASC', 'orderby' => 'title', 'posts_per_page' => -1, 'fields' => 'ids', 'no_found_rows' => true, 'update_post_term_cache' => false, 'update_post_meta_cache' => false, 'post__in' => [26]],
        ['post_type' => 'expertise', 'order' => 'ASC', 'orderby' => 'title', 'posts_per_page' => -1, 'post_status' => 'publish', 'post__in' => [27]],
    ]);
});

test('every section with something to show shows, and each adds a jump link', function (): void {
    $vars = single_expertise(expertise_everything(), ['found' => expertise_found(), 'children' => [new WP_Post]]);

    expect([
        $vars['show_podcast_section'],
        $vars['show_related_resource_section'],
        $vars['show_child_aoe_section'],
        $vars['show_conditions_section'],
        $vars['show_treatments_section'],
        $vars['show_providers_section'],
        $vars['show_locations_section'],
        $vars['show_related_aoe_section'],
        $vars['show_appointment_section'],
        $vars['show_jump_links_section'],
    ])->toBe([true, true, true, true, true, true, true, true, true, true])
        ->and($vars['jump_link_count'])->toBe(9)
        ->and($vars['jump_link_count_min'])->toBe(2)
        ->and($vars['provider_ids'])->toBe([24, 25])
        ->and([$vars['resource_postsPerPage'], $vars['resource_more']])->toBe([4, false]);
});

test('the child areas are looked up under this area and hidden by its meta', function (): void {
    $shown = single_expertise(options: ['children' => [new WP_Post]]);
    $pages_args = $GLOBALS['single_expertise_pages_args'];
    $meta_args = $GLOBALS['single_expertise_meta_args'];
    $hidden = single_expertise(options: ['children' => [new WP_Post], 'hide_children' => '1']);
    $none = single_expertise();

    expect($pages_args)->toBe([['child_of' => 7, 'post_type' => 'expertise']])
        ->and($meta_args)->toBe([[7, 'hide_sub_areas_of_expertise', true]])
        ->and([$shown['show_child_aoe_section'], $shown['jump_link_count']])->toBe([true, 2])
        ->and([$hidden['show_child_aoe_section'], $hidden['jump_link_count']])->toBe([false, 1])
        ->and([$none['show_child_aoe_section'], $none['jump_link_count']])->toBe([false, 1]);
});

test('a section whose ids find no published post is hidden and adds no jump link', function (): void {
    $fields = expertise_everything();
    unset($fields['expertise_podcast_name']);
    $vars = single_expertise($fields);

    expect([
        $vars['show_podcast_section'],
        $vars['show_related_resource_section'],
        $vars['show_child_aoe_section'],
        $vars['show_conditions_section'],
        $vars['show_treatments_section'],
        $vars['show_providers_section'],
        $vars['show_locations_section'],
        $vars['show_related_aoe_section'],
    ])->toBe([false, false, false, false, false, false, false, false])
        ->and(isset($vars['provider_ids']))->toBeFalse()
        ->and($vars['jump_link_count'])->toBe(1)
        ->and($vars['show_jump_links_section'])->toBeFalse();
});

test('a section with no ids is hidden even when its query finds posts', function (): void {
    $vars = single_expertise(options: ['found' => ['clinical-resource' => [21], 'condition' => [22], 'treatment' => [23], 'expertise' => [27]]]);

    expect([
        $vars['show_related_resource_section'],
        $vars['show_conditions_section'],
        $vars['show_treatments_section'],
        $vars['show_related_aoe_section'],
    ])->toBe([false, false, false, false])
        ->and(isset($vars['show_providers_section']))->toBeFalse()
        ->and(isset($vars['show_locations_section']))->toBeFalse()
        ->and($vars['jump_link_count'])->toBe(1);
});

test('the jump links show from two sections, the appointment one included', function (): void {
    $vars = single_expertise(['expertise_podcast_name' => 'Eye Talk']);

    expect($vars['jump_link_count'])->toBe(2)
        ->and($vars['show_jump_links_section'])->toBeTrue();
});
