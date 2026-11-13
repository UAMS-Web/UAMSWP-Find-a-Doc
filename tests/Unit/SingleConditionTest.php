<?php

/**
 * The condition template (templates/single-condition.php): the title attributes, the hooks it adds, the excerpt,
 * the classes that flag its content, the queries for each related section, which sections show, the jump links,
 * and the markup of every section.
 *
 * The included partials (the location, provider and expertise cards and the blocks) render for real with their
 * functions stubbed, and are tested on their own elsewhere.
 */

declare(strict_types=1);

require_once __DIR__.'/../Support/single-condition.php';

/**
 * Render the condition template and return the variables it set.
 *
 * @param  array<string, mixed>  $fields  The condition's ACF fields by name.
 * @param  array<string, mixed>  $options  The options of uamswp_test_single_ontology(), with `found` (post type =>
 *                                         the ids its query finds) and `refound` (`location` or `provider` => the
 *                                         ids its query in the region filter finds) in place of `queries`.
 * @return array<string, mixed>
 */
function single_condition(array $fields = [], array $options = []): array
{
    $found = $options['found'] ?? [];
    $refound = $options['refound'] ?? [];

    // The queries in the order the template builds them: a region query only runs when the test names one.
    $queries = [$found['clinical-resource'] ?? [], $found['location'] ?? []];
    if (isset($refound['location'])) {
        $queries[] = $refound['location'];
    }

    $queries[] = $found['treatment'] ?? [];
    if (! empty($fields['condition_physicians'])) {
        $queries[] = $found['provider'] ?? [];
    }

    $queries[] = $found['expertise'] ?? [];
    if (isset($refound['provider'])) {
        $queries[] = $refound['provider'];
    }

    return uamswp_test_single_ontology('single-condition', $fields, ['queries' => $queries] + $options);
}

/**
 * Render the condition template and return its output with the whitespace between tags removed.
 *
 * @param  array<string, mixed>  $fields
 * @param  array<string, mixed>  $options
 */
function single_condition_markup(array $fields = [], array $options = []): string
{
    return uamswp_test_single_ontology_markup(single_condition($fields, $options)['single_ontology_output']);
}

/** The fields of a condition related to one of everything. */
function single_condition_everything(): array
{
    return [
        'condition_clinical_resources' => [61],
        'condition_locations' => [21],
        'condition_treatments' => [41],
        'condition_physicians' => [31],
        'condition_expertise' => [51],
    ];
}

/** What the queries of a condition related to one of everything find. */
function single_condition_found(): array
{
    return ['clinical-resource' => [61], 'location' => [21], 'treatment' => [41], 'provider' => [31], 'expertise' => [51]];
}

/** A call to action bar row. */
function single_condition_cta(array $values = []): array
{
    return $values + [
        'cta_bar_heading' => 'Get Help',
        'cta_bar_body' => '<p>Body.</p>',
        'cta_bar_action_type' => 'none',
        'cta_bar_button_text' => 'Go',
        'cta_bar_button_url' => ['url' => 'https://example.test/go/', 'target' => ''],
        'cta_bar_button_description' => 'Go there',
        'cta_bar_phone_prepend' => '',
        'cta_bar_phone' => '5015551234',
    ];
}

// The lite YouTube plugin's function stays defined for the rest of the process once a test stubs it, so this runs first.
test('a video is embedded when the lite YouTube plugin is not active', function (): void {
    if (function_exists('lyte_preparse')) {
        $this->markTestSkipped('An earlier test defined lyte_preparse() for the rest of the process.');
    }

    $output = single_condition_markup(['condition_youtube_link' => 'https://www.youtube.com/watch?v=abc']);

    expect($output)->toContain('<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9"><iframe src="https://www.youtube.com/watch?v=abc"></iframe></div>')
        ->and($output)->not->toContain('lyte:');
});

test('a video is played by the lite YouTube player when it is active', function (): void {
    $output = single_condition_markup(['condition_youtube_link' => 'https://www.youtube.com/watch?v=abc'], ['lyte' => true]);

    expect($output)->toContain('<div class="alignwide">lyte:httpv://www.youtube.com/watch?v=abc</div>')
        ->and($output)->not->toContain('<iframe');
});

test('the page has one header and footer and opens with the condition name and title', function (): void {
    $vars = single_condition([], ['title' => 'Asthma']);
    $output = uamswp_test_single_ontology_markup($vars['single_ontology_output']);

    expect($vars['single_ontology_headers'])->toBe(1)
        ->and($vars['single_ontology_footers'])->toBe(1)
        ->and($output)->toStartWith('<div class="content-sidebar-wrap"><main id="genesis-content" class="condition-item"><section class="archive-description bg-white"><header class="entry-header"><h1 class="entry-title" itemprop="headline"><span class="supertitle">Condition</span><span class="sr-only">:</span>Asthma</h1></header><div class="entry-content clearfix" itemprop="text"></div></section>')
        ->and($output)->toEndWith('</section></main></div>');
});

test('the condition name comes from the options page', function (): void {
    $vars = single_condition([], ['option' => ['conditions_single_name' => 'Disease', 'conditions_archive_headline' => 'Diseases', 'conditions_archive_intro_text' => 'Intro.']]);

    expect($vars['condition_title'])->toBe('Disease')
        ->and($vars['condition_archive_title'])->toBe('Diseases')
        ->and($vars['condition_text'])->toBe('Intro.')
        ->and($vars['single_ontology_output'])->toContain('<span class="supertitle">Disease</span>');
});

test('the archive title defaults to Conditions and the name to Condition', function (): void {
    $vars = single_condition();

    expect([$vars['condition_archive_title'], $vars['condition_archive_title_attr']])->toBe(['Conditions', 'Conditions'])
        ->and([$vars['condition_title'], $vars['condition_title_attr']])->toBe(['Condition', 'Condition'])
        ->and($vars['condition_text'])->toBeNull();
});

test('the title attributes swap quotes, turn non-breaking spaces into spaces and decode entities', function (): void {
    $title = "A \"B\"&nbsp;C&#8217;s & D\u{E9}\u{A0}E";
    $vars = single_condition([], ['title' => $title, 'option' => ['conditions_archive_headline' => $title, 'conditions_single_name' => $title]]);

    expect($vars['page_title'])->toBe($title)
        ->and($vars['page_title_attr'])->toBe("A 'B' C's & D\u{E9} E")
        ->and($vars['condition_archive_title_attr'])->toBe("A 'B' C's & D\u{E9} E")
        ->and($vars['condition_title_attr'])->toBe("A 'B' C's & D\u{E9} E");
});

test('the template adds the keyword, title, description and body class hooks', function (): void {
    single_condition();

    expect(uamswp_test_render_hooks())->toBe([
        'add_action' => [
            ['wp_head', 'uamswp_keyword_hook_header'],
        ],
        'add_filter' => [
            ['seopress_titles_title', 'uamswp_fad_title_single_condition', 15, 2],
            ['seopress_titles_desc', 'sp_titles_desc'],
            ['body_class', 'uams_default_page_body_class'],
        ],
    ]);
});

test('an excerpt the editor wrote is kept', function (): void {
    $vars = single_condition([], ['excerpt' => 'Short.', 'content' => '<p>Long.</p>']);

    expect($vars['excerpt'])->toBe('Short.')
        ->and($vars['excerpt_user'])->toBeTrue()
        ->and($vars['content'])->toBe('<p>Long.</p>');
});

test('with no excerpt the description is the start of the content without its tags', function (): void {
    $vars = single_condition([], ['content' => '<p>'.str_repeat('é', 200).'</p>']);

    expect($vars['excerpt'])->toBe(str_repeat('é', 152).'...')
        ->and($vars['excerpt_user'])->toBeFalse();
});

test('a short content is the whole description', function (): void {
    $vars = single_condition([], ['content' => '<p>Short.</p>']);

    expect($vars['excerpt'])->toBe('Short.')
        ->and($vars['excerpt_user'])->toBeFalse();
});

test('with no excerpt and no content the description is empty', function (): void {
    $vars = single_condition();

    expect($vars['excerpt'])->toBe('')
        ->and($vars['excerpt_user'])->toBeFalse();
});

test('the content prints in the description section', function (): void {
    $output = single_condition_markup([], ['content' => '<p>About asthma.</p>']);

    expect($output)->toContain('<div class="entry-content clearfix" itemprop="text"><p>About asthma.</p></div>');
});

test('the alternate names are listed, separated by semicolons', function (): void {
    $output = single_condition_markup(['condition_alternate' => [['text' => 'Wheeze'], ['text' => 'Reactive airway'], ['text' => 'RAD']]]);

    expect($output)->toContain('<div class="entry-content clearfix" itemprop="text"><p class="text-callout text-callout-info">Also called: Wheeze; Reactive airway; RAD</p></div>');
});

test('one alternate name is listed alone', function (): void {
    $output = single_condition_markup(['condition_alternate' => [['text' => 'Wheeze']]]);

    expect($output)->toContain('<p class="text-callout text-callout-info">Also called: Wheeze</p>');
});

test('the content classes flag every kind of content the condition has', function (): void {
    $fields = single_condition_everything() + [
        'condition_alternate' => [['text' => 'Wheeze']],
        'condition_clinical_trials' => 1,
        'condition_youtube_link' => 'https://www.youtube.com/watch?v=abc',
        'condition_embed_codes' => '<div>Embed.</div>',
    ];
    $vars = single_condition($fields, ['found' => single_condition_found(), 'excerpt' => 'Short.', 'content' => '<p>Long.</p>', 'lyte' => true]);

    expect($vars['condition_field_classes'])->toBe(' has-keywords has-clinical-trials has-content has-excerpt has-syndication has-video has-treatment has-expertise has-location has-provider')
        ->and($vars['single_ontology_output'])->toContain('<main id="genesis-content" class="condition-item has-keywords has-clinical-trials has-content has-excerpt has-syndication has-video has-treatment has-expertise has-location has-provider">');
});

test('the content classes are empty for a condition with nothing', function (): void {
    expect(single_condition()['condition_field_classes'])->toBe('');
});

test('an excerpt made from the content is not flagged as an excerpt', function (): void {
    expect(single_condition([], ['content' => '<p>Long.</p>'])['condition_field_classes'])->toBe(' has-content');
});

test('lists with only empty items are not flagged', function (): void {
    $fields = ['condition_alternate' => [null], 'condition_treatments' => [0], 'condition_expertise' => [''], 'condition_physicians' => [0]];
    $vars = single_condition($fields, ['found' => ['provider' => [31]]]);

    expect($vars['condition_field_classes'])->toBe('');
});

test('a location list with no published location is not flagged', function (): void {
    $vars = single_condition(['condition_locations' => [21]], ['found' => ['location' => [21]], 'status' => [21 => 'draft']]);

    expect($vars['condition_field_classes'])->toBe('')
        ->and($vars['location_valid'])->toBeFalse();
});

test('a MedlinePlus code or an embed code is syndicated content', function (array $fields, bool $syndication): void {
    expect(single_condition($fields)['syndication'])->toBe($syndication);
})->with([
    'a MedlinePlus code' => [['medline_code_type' => 'mplus_topic', 'medline_code_id' => '123'], true],
    'a MedlinePlus code of type none' => [['medline_code_type' => 'none', 'medline_code_id' => '123'], false],
    'a MedlinePlus type with no code' => [['medline_code_type' => 'mplus_topic'], false],
    'a MedlinePlus code with no type' => [['medline_code_id' => '123'], false],
    'an embed code' => [['condition_embed_codes' => '<div>Embed.</div>'], true],
    'a code of type none and an embed code' => [['medline_code_type' => 'none', 'medline_code_id' => '123', 'condition_embed_codes' => '<div>Embed.</div>'], true],
    'nothing' => [[], false],
]);

test('the MedlinePlus content prints for a trimmed code of a type other than none', function (): void {
    $fields = ['medline_code_type' => 'mplus_topic', 'medline_code_id' => ' 123 '];

    expect(single_condition_markup($fields))->toContain('<div class="entry-content clearfix" itemprop="text"><medline code="123" type="mplus_topic"/></div>')
        ->and(single_condition_markup(['medline_code_type' => 'none', 'medline_code_id' => '123']))->not->toContain('<medline')
        ->and(single_condition_markup(['medline_code_type' => 'mplus_topic']))->not->toContain('<medline')
        ->and(single_condition_markup(['medline_code_id' => '123']))->not->toContain('<medline');
});

test('the embed code prints in the description section', function (): void {
    expect(single_condition_markup(['condition_embed_codes' => '<div>Embed.</div>']))->toContain('<div class="entry-content clearfix" itemprop="text"><div>Embed.</div></div>');
});

test('the podcast, CTA and other fields are read into variables', function (): void {
    $vars = single_condition(['condition_podcast_name' => 'Asthma', 'condition_cta' => [], 'condition_clinical_trials' => 1, 'medline_code_type' => 'none', 'medline_code_id' => '9', 'condition_embed_codes' => 'E']);

    expect([$vars['podcast_name'], $vars['cta_repeater'], $vars['clinical_trials'], $vars['medline_type'], $vars['medline_code'], $vars['embed_code']])->toBe(['Asthma', [], 1, 'none', '9', 'E']);
});

test('the clinical resources query asks for four published resources of the condition, newest first', function (): void {
    $vars = single_condition(['condition_clinical_resources' => [61, 62]]);

    expect($vars['single_ontology_queries'][0])->toBe(['post_type' => 'clinical-resource', 'order' => 'DESC', 'orderby' => 'post_date', 'posts_per_page' => 4, 'post_status' => 'publish', 'post__in' => [61, 62]])
        ->and($vars['resource_postsPerPage'])->toBe(4);
});

test('the locations query asks for the ids of the published locations of the condition by title', function (): void {
    $vars = single_condition(['condition_locations' => [21, 22]]);

    expect($vars['single_ontology_queries'][1])->toBe([
        'post_type' => 'location',
        'post_status' => 'publish',
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'post__in' => [21, 22],
    ]);
});

test('the treatments query asks for the published treatments of the condition by title', function (): void {
    $vars = single_condition(['condition_treatments' => [41, 42]]);

    expect($vars['single_ontology_queries'][2])->toBe([
        'post_type' => 'treatment',
        'post_status' => 'publish',
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'no_found_rows' => true,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'post__in' => [41, 42],
    ]);
});

test('the providers query asks for the ids of the published providers of the condition by title', function (): void {
    $vars = single_condition(['condition_physicians' => [31, 32]]);

    expect($vars['single_ontology_queries'][3])->toBe([
        'post_type' => 'provider',
        'post_status' => 'publish',
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'post__in' => [31, 32],
    ]);
});

test('the providers query only runs for a condition with providers', function (): void {
    $vars = single_condition();

    expect($vars['single_ontology_queries'])->toHaveCount(4)
        ->and(isset($vars['physicians_query']))->toBeFalse()
        ->and($vars['show_providers_section'])->toBeFalse();
});

test('the expertise query asks for the published areas of expertise of the condition by title', function (): void {
    $vars = single_condition(['condition_expertise' => [51, 52]]);

    expect($vars['single_ontology_queries'][3])->toBe([
        'post_type' => 'expertise',
        'post_status' => 'publish',
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'no_found_rows' => true,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'post__in' => [51, 52],
    ]);
});

test('the locations are valid once one of them is published, and the rest are not checked', function (): void {
    $vars = single_condition(['condition_locations' => [21, 22, 23]], ['found' => ['location' => [22]], 'status' => [21 => 'draft']]);

    expect($vars['location_valid'])->toBeTrue()
        ->and($vars['single_ontology_status_calls'])->toBe([21, 22]);
});

test('the locations are not valid when none of them is published', function (): void {
    $vars = single_condition(['condition_locations' => [21, 22]], ['found' => ['location' => [21]], 'status' => [21 => 'draft', 22 => 'private']]);

    expect($vars['location_valid'])->toBeFalse()
        ->and($vars['single_ontology_status_calls'])->toBe([21, 22])
        ->and($vars['location_content'])->toBe('')
        ->and($vars['show_locations_section'])->toBeFalse();
});

test('the locations are not checked when the query finds none', function (): void {
    $vars = single_condition(['condition_locations' => [21]]);

    expect($vars['location_valid'])->toBeFalse()
        ->and($vars['single_ontology_status_calls'])->toBe([]);
});

test('the locations are not checked when the condition has none', function (): void {
    $vars = single_condition([], ['found' => ['location' => [21]]]);

    expect($vars['location_valid'])->toBeFalse()
        ->and($vars['single_ontology_status_calls'])->toBe([])
        ->and($vars['show_locations_section'])->toBeFalse();
});

test('the locations section lists the cards of the locations found', function (): void {
    $vars = single_condition(['condition_locations' => [21, 22]], ['found' => ['location' => [21, 22]], 'titles' => [21 => 'North Clinic', 22 => 'South Clinic'], 'title' => 'Asthma']);
    $output = uamswp_test_single_ontology_markup($vars['single_ontology_output']);

    expect($vars['location_ids'])->toBe([21, 22])
        ->and($output)->toContain('<section class="uams-module bg-auto" id="locations"><div class="container-fluid"><div class="row"><div class="col-12"><h2 class="module-title"><span class="title">Locations Where Providers Treat Asthma</span></h2><p class="note">Note that the treatment of Asthma may not be<em>performed</em>at every location listed below. The list may include locations where the treatment plan is developed during and after a patient visit.</p>[uamswp_location_ajax_filter locations="21,22"]<div class="card-list-container location-card-list-container"><div class="card-list card-list-locations"><div class="card">')
        ->and(substr_count($output, '<div class="card">'))->toBe(2)
        ->and($output)->toContain('>North Clinic</a>')
        ->and($output)->toContain('>South Clinic</a>')
        ->and($output)->toContain('</div><!-- .card --><data id="location_ids" data-postids="21,22," data-regions=","></data></div></div></div></div></div></section>');
});

test('currently the region of each location is listed as an empty slug, once for each region', function (): void {
    $fields_by_id = [21 => ['location_region' => 5], 22 => ['location_region' => 6], 23 => ['location_region' => 5]];
    $vars = single_condition(['condition_locations' => [21, 22, 23]], ['found' => ['location' => [21, 22, 23]], 'fields_by_id' => $fields_by_id]);

    // get_term_by() returns an object, which the template reads only when it is an array.
    expect($vars['location_region_IDs'])->toBe([0 => 5, 1 => 6])
        ->and($vars['location_region_list'])->toBe(['', ''])
        ->and($vars['single_ontology_output'])->toContain('data-regions=",,"');
});

test('a region filter in the cookie or the query lists only the locations in that region', function (array $get, array $cookie, string $region): void {
    $vars = single_condition(['condition_locations' => [21, 22]], ['found' => ['location' => [21, 22]], 'refound' => ['location' => [22]], 'get' => $get, 'cookie' => $cookie]);
    $output = uamswp_test_single_ontology_markup($vars['single_ontology_output']);

    expect($vars['single_ontology_queries'][2])->toBe([
        'post_type' => 'location',
        'post_status' => 'publish',
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'post__in' => [21, 22],
        'tax_query' => [['taxonomy' => 'region', 'field' => 'slug', 'terms' => $region]],
    ])
        ->and($vars['location_region'])->toBe($region)
        ->and($output)->toContain('[uamswp_location_ajax_filter locations="21,22"]')
        ->and(substr_count($output, '<div class="card">'))->toBe(1)
        ->and($output)->toContain('data-postids="22,"');
})->with([
    'the cookie' => [[], ['wp_filter_region' => 'north'], 'north'],
    'the query' => [['_filter_region' => 'south'], [], 'south'],
    'the query over the cookie' => [['_filter_region' => 'south'], ['wp_filter_region' => 'north'], 'south'],
]);

test('an empty region filter queries the locations in every region', function (): void {
    $vars = single_condition(['condition_locations' => [21]], ['found' => ['location' => [21]], 'refound' => ['location' => [21]], 'get' => ['_filter_region' => '']]);

    expect($vars['single_ontology_queries'][2]['tax_query'])->toBe([])
        ->and($vars['location_region'])->toBe('');
});

test('a region filter with no locations in the region says so', function (): void {
    $vars = single_condition(['condition_locations' => [21]], ['found' => ['location' => [21]], 'refound' => ['location' => []], 'cookie' => ['wp_filter_region' => 'north']]);
    $output = uamswp_test_single_ontology_markup($vars['single_ontology_output']);

    expect($output)->toContain('<div class="card-list card-list-locations"><span class="no-results">Sorry, there are no locations matching your filter criteria. Please adjust your filter options or reset the filters.</span></div>')
        ->and($output)->not->toContain('id="location_ids"')
        ->and($vars['show_locations_section'])->toBeTrue();
});

test('every related section with posts shows, and each adds a jump link', function (): void {
    $vars = single_condition(single_condition_everything() + ['condition_podcast_name' => 'Asthma'], ['found' => single_condition_found()]);

    expect([
        $vars['show_podcast_section'],
        $vars['show_related_resource_section'],
        $vars['show_treatments_section'],
        $vars['show_providers_section'],
        $vars['show_aoe_section'],
        $vars['show_locations_section'],
        $vars['show_appointment_section'],
        $vars['show_jump_links_section'],
    ])->toBe([true, true, true, true, true, true, true, true])
        ->and($vars['jump_link_count'])->toBe(7)
        ->and($vars['jump_link_count_min'])->toBe(2);
});

test('a section whose ids find no post is hidden and adds no jump link', function (): void {
    $vars = single_condition(single_condition_everything());

    expect([
        $vars['show_podcast_section'],
        $vars['show_related_resource_section'],
        $vars['show_clinical_trials_section'],
        $vars['show_treatments_section'],
        $vars['show_providers_section'],
        $vars['show_aoe_section'],
        $vars['show_locations_section'],
    ])->toBe([false, false, false, false, false, false, false])
        ->and($vars['show_appointment_section'])->toBeTrue()
        ->and($vars['jump_link_count'])->toBe(1)
        ->and($vars['show_jump_links_section'])->toBeFalse();
});

test('a section with no ids is hidden even when its query finds posts', function (): void {
    $vars = single_condition([], ['found' => single_condition_found()]);

    expect([
        $vars['show_related_resource_section'],
        $vars['show_treatments_section'],
        $vars['show_aoe_section'],
        $vars['show_locations_section'],
    ])->toBe([false, false, false, false])
        ->and($vars['jump_link_count'])->toBe(1);
});

test('currently the clinical trials section shows but adds no jump link', function (): void {
    $vars = single_condition(['condition_clinical_trials' => 1]);

    expect($vars['show_clinical_trials_section'])->toBeTrue()
        ->and($vars['jump_link_count'])->toBe(1)
        ->and($vars['show_jump_links_section'])->toBeFalse();
});

test('the jump links show from two sections, the appointment one included', function (array $fields, array $found): void {
    $vars = single_condition($fields, ['found' => $found]);

    expect($vars['jump_link_count'])->toBe(2)
        ->and($vars['show_jump_links_section'])->toBeTrue();
})->with([
    'the podcast' => [['condition_podcast_name' => 'Asthma'], []],
    'the resources' => [['condition_clinical_resources' => [61]], ['clinical-resource' => [61]]],
    'the treatments' => [['condition_treatments' => [41]], ['treatment' => [41]]],
    'the providers' => [['condition_physicians' => [31]], ['provider' => [31]]],
    'the areas of expertise' => [['condition_expertise' => [51]], ['expertise' => [51]]],
    'the locations' => [['condition_locations' => [21]], ['location' => [21]]],
]);

test('the jump links list each section that shows, in order', function (): void {
    $fields = single_condition_everything() + ['condition_podcast_name' => 'Asthma', 'condition_clinical_trials' => 1];
    $output = single_condition_markup($fields, ['found' => single_condition_found()]);

    expect($output)->toContain('<nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links"><h2>Contents</h2><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse inner-container" id="jump-link-nav"><ul class="nav navbar-nav">'
        .'<li class="nav-item"><a class="nav-link" href="#podcast" title="Jump to the section of this page about UAMS Health Talk Podcast">Podcast</a></li>'
        .'<li class="nav-item"><a class="nav-link" href="#related-resources" title="Jump to the section of this page about Resources">Resources</a></li>'
        .'<li class="nav-item"><a class="nav-link" href="#clinical-trials" title="Jump to the section of this page about Clinical Trials">Clinical Trials</a></li>'
        .'<li class="nav-item"><a class="nav-link" href="#treatments" title="Jump to the section of this page about Treatments and Procedures">Treatments &amp; Procedures</a></li>'
        .'<li class="nav-item"><a class="nav-link" href="#providers" title="Jump to the section of this page about Providers">Providers</a></li>'
        .'<li class="nav-item"><a class="nav-link" href="#locations" title="Jump to the section of this page about Locations">Locations</a></li>'
        .'<li class="nav-item"><a class="nav-link" href="#expertise" title="Jump to the section of this page about Areas of Expertise">Areas of Expertise</a></li>'
        .'<li class="nav-item"><a class="nav-link" href="#appointment-info" title="Jump to the section of this page about making an appointment">Make an Appointment</a></li>'
        .'</ul></div></nav>');
});

test('the jump links leave out the sections that do not show', function (): void {
    $output = single_condition_markup(['condition_podcast_name' => 'Asthma']);

    expect($output)->toContain('<ul class="nav navbar-nav"><li class="nav-item"><a class="nav-link" href="#podcast" title="Jump to the section of this page about UAMS Health Talk Podcast">Podcast</a></li><li class="nav-item"><a class="nav-link" href="#appointment-info" title="Jump to the section of this page about making an appointment">Make an Appointment</a></li></ul>');
});

test('the sections print in order after the description', function (): void {
    $fields = single_condition_everything() + ['condition_podcast_name' => 'Asthma', 'condition_clinical_trials' => 1, 'condition_cta' => [single_condition_cta()]];
    $output = single_condition_markup($fields, ['found' => single_condition_found()]);

    preg_match_all('/<(?:section|nav) class="[^"]*" id="([^"]+)"/', $output, $matches);

    expect($matches[1])->toBe(['cta-bar-1', 'jump-links', 'podcast', 'related-resources', 'clinical-trials', 'treatments', 'providers', 'locations', 'expertise', 'appointment-info']);
});

test('a page with no related content prints only the description and the appointment section', function (): void {
    $output = single_condition_markup();

    preg_match_all('/<(?:section|nav) class="[^"]*" id="([^"]+)"/', $output, $matches);

    expect($matches[1])->toBe(['appointment-info'])
        ->and($output)->not->toContain('<section class="archive-description bg-white"><header class="entry-header"><h1 class="entry-title" itemprop="headline"><span class="supertitle">Condition</span><span class="sr-only">:</span>Asthma</h1></header><div class="entry-content clearfix" itemprop="text"><p');
});

test('the podcast section loads the episodes tagged with the podcast name', function (): void {
    $output = single_condition_markup(['condition_podcast_name' => 'Asthma "Care"'], ['title' => 'Asthma']);

    expect($output)->toContain('<section class="uams-module podcast-list bg-auto" id="podcast"><script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js"></script><script type="text/javascript">radiomd_embedded_filtered_tag("uams","radiomd-embedded-filtered-tag",303,"Asthma \"Care\"");</script><style type="text/css">#radiomd-embedded-filtered-tag iframe { width: 100%; border: none; }</style><div class="container-fluid"><div class="row"><div class="col-12"><h2 class="module-title"><span class="title">UAMS Health Talk Podcast</span></h2><div class="module-body text-center"><p class="lead">In the UAMS Health Talk podcast, experts from UAMS talk about a variety of health topics, providing tips and guidelines to help people lead healthier lives. Listen to the episode(s) featuring the topic of Asthma.</p></div><div class="content-width mt-8" id="radiomd-embedded-filtered-tag"></div></div><div class="col-12 more"><p class="lead">Find other great episodes on other topics and from other UAMS Health providers.</p><div class="cta-container"><a href="/podcast/" class="btn btn-primary" aria-label="Listen to more episodes of the UAMS Health Talk podcast">Listen to More Episodes</a></div></div></div></div></section>');
});

test('the resources section is headed as related to the condition and links to the full list of its resources', function (): void {
    $vars = single_condition(['condition_clinical_resources' => [61, 62, 63, 64, 65]], ['found' => ['clinical-resource' => [61, 62, 63, 64]], 'title' => 'Asthma', 'post_name' => 'asthma-adult']);
    $output = uamswp_test_single_ontology_markup($vars['single_ontology_output']);

    expect($output)->toContain('<section class="uams-module stacked-image-text bg-auto" id="related-resources" aria-labelledby="related-resources-title"><div class="container-fluid"><div class="row"><div class="col-12"><h2 class="module-title" id="related-resources-title"><span class="title">Resources Related to Asthma</span></h2>')
        ->and(substr_count($output, 'View the Clinical Resource</a>'))->toBe(4)
        ->and($output)->toContain('<a href="/clinical-resource/?_resource_conditions=asthma-adult" class="btn btn-outline-primary" aria-label="View the full list of clinical resources related to Asthma" target="_blank" rel="noopener">View the Full List</a>')
        ->and([$vars['resource_heading_related_pre'], $vars['resource_heading_related_post'], $vars['resource_heading_related_name'], $vars['resource_more_suppress'], $vars['resource_more']])->toBe([false, true, 'Asthma', false, true]);
});

test('the resources section is not built when it does not show', function (): void {
    $vars = single_condition();

    expect($vars['resource_more'])->toBeFalse()
        ->and(isset($vars['resource_more_key']))->toBeFalse()
        ->and(isset($vars['resource_heading']))->toBeFalse();
});

test('the clinical trials section searches the trials for the condition', function (): void {
    $vars = single_condition(['condition_clinical_trials' => 1], ['title' => 'Asthma']);

    expect(uamswp_test_single_ontology_markup($vars['single_ontology_output']))->toContain('<section class="uams-module cta-bar cta-bar-1 bg-auto" id="clinical-trials"><div class="container-fluid"><div class="row"><div class="col-xs-12"><h2 class="module-title"><span class="title">Clinical Trials</span></h2><p><a href="https://uams.trialstoday.org/" aria-label="Search UAMS Clinical Trials">Search our clinical trials</a>for those related to Asthma.</p></div></div></div></section>')
        ->and($vars['clinical_trial_title'])->toBe('Asthma');
});

test('the treatments section links each treatment found', function (): void {
    $titles = [41 => 'Inhaler &amp; Spacer', 42 => "Allergy \"Shots\"&nbsp;Plus\u{A0}Care"];
    $output = single_condition_markup(['condition_treatments' => [41, 42]], ['found' => ['treatment' => [41, 42]], 'titles' => $titles, 'title' => 'Asthma']);

    expect($output)->toContain('<section class="uams-module conditions-treatments bg-auto" id="treatments"><div class="container-fluid"><div class="row"><div class="col-xs-12"><h2 class="module-title"><span class="title">Treatments and Procedures Related to Asthma</span></h2><p class="note">UAMS Health providers perform and prescribe a broad range of treatments and procedures, some of which may not be listed below.</p><div class="list-container list-container-rows"><ul class="list">'
        .'<li><a href="https://example.test/p/41/" aria-label="Go to Treatment page for Inhaler &amp; Spacer" class="btn btn-outline-primary">Inhaler &amp; Spacer</a></li>'
        ."<li><a href=\"https://example.test/p/42/\" aria-label=\"Go to Treatment page for Allergy 'Shots' Plus Care\" class=\"btn btn-outline-primary\">Allergy \"Shots\"&nbsp;Plus\u{A0}Care</a></li>"
        .'</ul></div></div></div></div></section>');
});

test('the providers section lists the cards of the providers found with their regions and titles', function (): void {
    $fields_by_id = [
        31 => ['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee', 'physician_region' => [5, 6], 'physician_title' => 8],
        32 => ['physician_first_name' => 'Bo', 'physician_last_name' => 'Kim', 'physician_region' => [6], 'physician_title' => 8],
        33 => ['physician_first_name' => 'Cy', 'physician_last_name' => 'Ng', 'physician_region' => 7, 'physician_title' => 9],
    ];
    $vars = single_condition(['condition_physicians' => [31, 32, 33]], ['found' => ['provider' => [31, 32, 33]], 'fields_by_id' => $fields_by_id, 'regions' => [5 => 'north', 6 => 'south'], 'title' => 'Asthma']);
    $output = uamswp_test_single_ontology_markup($vars['single_ontology_output']);

    expect($vars['provider_ids'])->toBe([31, 32, 33])
        ->and($vars['region_list'])->toBe(['north', 'south'])
        ->and($vars['provider_count'])->toBe(3)
        ->and($output)->toContain('<section class="uams-module bg-auto" id="providers"><div class="container-fluid"><div class="row"><div class="col-12"><h2 class="module-title"><span class="title">Providers Diagnosing or Treating Asthma</span></h2><p class="note">Note that every provider listed below may not perform or prescribe all treatments or procedures related to Asthma. Review each provider for availability.</p>[uamswp_provider_ajax_filter providers="31,32,33"]<div class="card-list-container"><div class="card-list card-list-doctors"><div class="card">')
        ->and(substr_count($output, '<div class="card">'))->toBe(3)
        ->and($output)->toContain('<span class="name">Ann Lee</span>')
        ->and($output)->toContain('<span class="name">Cy Ng</span>')
        ->and($output)->toContain('</div><data id="provider_ids" data-postids="31,32,33," data-regions="north,south," data-titles="8,9,"></data></div></div><!-- <div class="more" style=""><button class="loadmore btn btn-primary" data-ppp="" aria-label="Load more providers">Load More</button></div>--><div class="ajax-filter-load-more"><button class="btn btn-lg btn-primary" aria-label="Load all providers">Load All</button></div></div></div></div></section>');
});

test('a region filter in the cookie or the query lists only the providers in that region', function (array $get, array $cookie, string $region): void {
    $vars = single_condition(['condition_physicians' => [31, 32]], ['found' => ['provider' => [31, 32]], 'refound' => ['provider' => [32]], 'get' => $get, 'cookie' => $cookie]);
    $output = uamswp_test_single_ontology_markup($vars['single_ontology_output']);

    expect($vars['single_ontology_queries'][5])->toBe([
        'post_type' => 'provider',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'fields' => 'ids',
        'post__in' => [31, 32],
        'tax_query' => [['taxonomy' => 'region', 'field' => 'slug', 'terms' => $region]],
    ])
        ->and($vars['provider_region'])->toBe($region)
        ->and($vars['provider_count'])->toBe(1)
        ->and($output)->toContain('[uamswp_provider_ajax_filter providers="31,32"]')
        ->and(substr_count($output, '<div class="card">'))->toBe(1)
        ->and($output)->toContain('data-postids="32,"');
})->with([
    'the cookie' => [[], ['wp_filter_region' => 'north'], 'north'],
    'the query' => [['_filter_region' => 'south'], [], 'south'],
    'the query over the cookie' => [['_filter_region' => 'south'], ['wp_filter_region' => 'north'], 'south'],
]);

test('an empty region filter queries the providers in every region', function (): void {
    $vars = single_condition(['condition_physicians' => [31]], ['found' => ['provider' => [31]], 'refound' => ['provider' => [31]], 'cookie' => ['wp_filter_region' => '']]);

    expect($vars['single_ontology_queries'][5]['tax_query'])->toBe([])
        ->and($vars['provider_region'])->toBe('');
});

test('a region filter with no providers in the region says so', function (): void {
    $output = single_condition_markup(['condition_physicians' => [31]], ['found' => ['provider' => [31]], 'refound' => ['provider' => []], 'cookie' => ['wp_filter_region' => 'north']]);

    expect($output)->toContain('<div class="card-list card-list-doctors"><span class="no-results">Sorry, there are no providers matching your filter criteria. Please adjust your filter options or reset the filters.</span></div>')
        ->and($output)->not->toContain('id="provider_ids"');
});

test('a region filter in the query is saved in a cookie for the session', function (): void {
    $output = single_condition_markup(['condition_physicians' => [31]], ['found' => ['provider' => [31]], 'refound' => ['provider' => [31]], 'get' => ['_filter_region' => 'north<"x">']]);

    expect($output)->toContain('<div class="ajax-filter-load-more"><button class="btn btn-lg btn-primary" aria-label="Load all providers">Load All</button></div></div></div></div><script type="text/javascript">// Set cookie to expire at end of session document.cookie = "wp_filter_region=north&lt;&quot;x&quot;&gt;; path=/; domain="+window.location.hostname;</script></section>');
});

test('a region filter in the cookie alone is not saved again', function (): void {
    $output = single_condition_markup(['condition_physicians' => [31]], ['found' => ['provider' => [31]], 'refound' => ['provider' => [31]], 'cookie' => ['wp_filter_region' => 'north']]);

    expect($output)->not->toContain('document.cookie');
});

test('the areas of expertise section lists the cards of the areas found', function (): void {
    $output = single_condition_markup(['condition_expertise' => [51, 52]], ['found' => ['expertise' => [51, 52]], 'titles' => [51 => 'Lungs', 52 => 'Allergy'], 'title' => 'Asthma']);

    expect($output)->toContain('<section class="uams-module bg-auto" id="expertise"><div class="container-fluid"><div class="row"><div class="col-12"><h2 class="module-title"><span class="title">Areas of Expertise for Asthma</span></h2><div class="card-list-container"><div class="card-list card-list-expertise"><div class="card">')
        ->and(substr_count($output, '<div class="card">'))->toBe(2)
        ->and($output)->toContain('>Lungs</a>')
        ->and($output)->toContain('>Allergy</a>');
});

test('the appointment section points to the locations listed, or else to every location', function (): void {
    $with = single_condition_markup(['condition_locations' => [21]], ['found' => ['location' => [21]]]);
    $without = single_condition_markup();

    expect($with)->toContain('<section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info"><div class="container-fluid"><div class="row"><div class="col-xs-12"><h2 class="module-title"><span class="title">Make an Appointment</span></h2><p>Request an appointment by<a href="#locations" data-itemtitle="Contact a clinic directly">')
        ->and($without)->toContain('<a href="/location/" data-itemtitle="Contact a clinic directly">');
});

test('a call to action bar with no action has no link', function (): void {
    $output = single_condition_markup(['condition_cta' => [single_condition_cta()]]);

    expect($output)->toContain('<section class="uams-module cta-bar cta-bar-centered bg-auto no-link" id="cta-bar-1" aria-label="Get Help"><div class="container-fluid"><div class="row"><div class="col-12"><div class="inner-container"><div class="cta-heading"><h2>Get Help</h2></div><div class="cta-body"><div class="text-container"><p>Body.</p></div></div></div></div></div></div></section>');
});

test('a call to action bar with a link has a button', function (): void {
    $vars = single_condition(['condition_cta' => [single_condition_cta(['cta_bar_action_type' => 'url'])]]);
    $output = uamswp_test_single_ontology_markup($vars['single_ontology_output']);

    expect($output)->toContain('<section class="uams-module cta-bar cta-bar-centered bg-auto" id="cta-bar-1" aria-label="Get Help"><div class="container-fluid"><div class="row"><div class="col-12"><div class="inner-container"><div class="cta-heading"><h2>Get Help</h2></div><div class="cta-body"><div class="text-container"><p>Body.</p></div><div class="btn-container"><a href="https://example.test/go/" aria-label="Go there" class=" btn btn-primary" data-moduletitle="Get Help">Go</a></div></div></div></div></div></div></section>')
        ->and([$vars['cta_button_text'], $vars['cta_button_url'], $vars['cta_button_desc']])->toBe(['Go', ['url' => 'https://example.test/go/', 'target' => ''], 'Go there'])
        ->and([$vars['cta_phone_prepend'], $vars['cta_phone'], $vars['cta_phone_link']])->toBe(['', '', '']);
});

test('a call to action bar with a phone number has a call link', function (): void {
    $vars = single_condition(['condition_cta' => [single_condition_cta(['cta_bar_action_type' => 'phone', 'cta_bar_phone_prepend' => 'Dial'])]]);
    $output = uamswp_test_single_ontology_markup($vars['single_ontology_output']);

    expect($output)->toContain('<div class="cta-body"><div class="text-container"><p>Body.</p></div><div class="btn-container"><a href="tel:5015551234" data-moduletitle="Get Help">Dial<span class="no-break">5015551234</span></a></div></div>')
        ->and([$vars['cta_phone_prepend'], $vars['cta_phone'], $vars['cta_phone_link']])->toBe(['Dial', '5015551234', '<a href="tel:dash:5015551234">us:5015551234</a>'])
        ->and([$vars['cta_button_text'], $vars['cta_button_url'], $vars['cta_button_desc']])->toBe(['', '', '']);
});

test('a phone call to action says Call when it has no words of its own', function (): void {
    $output = single_condition_markup(['condition_cta' => [single_condition_cta(['cta_bar_action_type' => 'phone'])]]);

    expect($output)->toContain('>Call<span class="no-break">5015551234</span></a>');
});

test('each call to action bar is numbered in turn', function (): void {
    $vars = single_condition(['condition_cta' => [single_condition_cta(), single_condition_cta(['cta_bar_heading' => 'More'])]]);

    expect($vars['single_ontology_output'])->toContain('id="cta-bar-1" aria-label="Get Help"')
        ->and($vars['single_ontology_output'])->toContain('id="cta-bar-2" aria-label="More"')
        ->and([$vars['cta_layout'], $vars['cta_size'], $vars['cta_use_image'], $vars['cta_image'], $vars['cta_background_color'], $vars['cta_btn_color']])->toBe(['cta-bar-centered', 'normal', false, '', 'bg-auto', 'primary']);
});

test('currently a call to action bar reads a size variable that is never set', function (): void {
    $vars = single_condition(['condition_cta' => [single_condition_cta(), single_condition_cta()]]);

    expect(uamswp_test_single_ontology_warnings($vars, 'single-condition.php'))->toBe(['Undefined variable $cta_cta_size', 'Undefined variable $cta_cta_size']);
});

test('a page related to everything raises no warnings of its own', function (): void {
    $fields = single_condition_everything() + ['condition_podcast_name' => 'Asthma', 'condition_clinical_trials' => 1, 'condition_alternate' => [['text' => 'Wheeze']]];
    $vars = single_condition($fields, ['found' => single_condition_found(), 'refound' => ['location' => [21], 'provider' => [31]], 'get' => ['_filter_region' => 'north']]);

    expect(uamswp_test_single_ontology_warnings($vars, 'single-condition.php'))->toBe([]);
});

test('each query the template loops over is reset after its loop', function (array $fields, array $found, int $resets): void {
    expect(single_condition($fields, ['found' => $found])['single_ontology_resets'])->toBe($resets);
})->with([
    'nothing, after the areas of expertise' => [[], [], 1],
    'the locations' => [['condition_locations' => [21]], ['location' => [21]], 2],
    'the treatments' => [['condition_treatments' => [41]], ['treatment' => [41]], 2],
    'the providers, once found and once listed' => [['condition_physicians' => [31]], ['provider' => [31]], 3],
]);
