<?php

/**
 * The sections the single-expertise callbacks in includes/class.fad-single-expertise.php print from the page's
 * queries: providers and locations with their region filter, the related and child areas, the conditions,
 * treatments and resources lists, the video, the podcast, the appointment band and the jump links.
 *
 * The title, the call to action bars and the alternate names are in tests/Unit/SingleExpertiseCallbacksTest.php.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/functions-file.php';
require_once __DIR__.'/../Support/wp-classes.php';
require_once __DIR__.'/../Support/templates.php';
require_once __DIR__.'/../Support/acf-blocks.php';
require_once __DIR__.'/../../includes/class.fad-helper-functions.php';
require_once __DIR__.'/../../includes/class.fad-single-expertise.php';

/** The page globals the callbacks read, cleared around each test so one test's value cannot reach the next. */
const EXPERTISE_SECTION_GLOBALS = [
    'show_providers_section', 'physicians_query', 'physicians', 'provider_ids', 'page_title', 'show_conditions_section',
    'conditions_cpt_query', 'show_treatments_section', 'treatments_cpt_query', 'show_locations_section', 'location_query',
    'locations', 'show_related_aoe_section', 'expertise_query', 'show_podcast_section', 'podcast_name', 'post',
    'show_related_resource_section', 'resources', 'resource_query', 'resource_postsPerPage', 'page_id',
    'show_child_aoe_section', 'show_appointment_section', 'jump_link_count_min', 'jump_link_count', 'show_jump_links_section',
];

beforeEach(function (): void {
    $this->request = [$_GET, $_COOKIE];

    foreach (EXPERTISE_SECTION_GLOBALS as $name) {
        unset($GLOBALS[$name]);
    }

    WP_Query::$queue = [];
    WP_Query::$found = [];
    WP_Query::$queries = [];
    WP_Query::$current = 0;
});

afterEach(function (): void {
    [$_GET, $_COOKIE] = $this->request;

    foreach (EXPERTISE_SECTION_GLOBALS as $name) {
        unset($GLOBALS[$name]);
    }
});

/**
 * A query over the given posts, as the template built it.
 *
 * @param  array<int, mixed>  $posts
 */
function expertise_section_query(array $posts): WP_Query
{
    $query = new WP_Query;
    $query->posts = $posts;

    return $query;
}

/**
 * Run a callback with the given page globals, and return what it printed.
 *
 * @param  array<string, mixed>  $globals
 */
function expertise_section(string $callback, array $globals = []): string
{
    foreach ($globals as $name => $value) {
        $GLOBALS[$name] = $value;
    }

    ob_start();

    try {
        $callback();
    } finally {
        $output = (string) ob_get_clean();
    }

    return $output;
}

/**
 * Collapse the whitespace of printed markup and drop it between tags.
 */
function expertise_section_squeeze(string $html): string
{
    return trim((string) preg_replace(['/\s+/', '/>\s+</'], [' ', '><'], $html));
}

/**
 * Stub what the provider and location cards read, so a section can include them for real.
 *
 * Each card prints its post's name, so a test can count the cards and read their order.
 *
 * @param  array<string, mixed>  $fields  ACF fields by `name:id`, or by name for every post.
 * @return ArrayObject<int, string> The calls the section made to wp_reset_postdata() and do_shortcode().
 */
function expertise_section_cards(array $fields = []): ArrayObject
{
    /** @var ArrayObject<int, string> $calls */
    $calls = new ArrayObject;

    uamswp_test_functions_file();
    require_once UAMS_FAD_PATH.'includes/class.fad-location-address.php';
    uamswp_test_acf_blocks_file();

    when('get_field')->alias(static fn (string $name, mixed $id = null): mixed => $fields[$name.':'.(is_scalar($id) ? $id : '')] ?? $fields[$name] ?? null);
    when('get_field_object')->justReturn();
    when('get_term')->justReturn(false);
    when('get_the_ID')->alias(static fn (): int => WP_Query::$current);
    when('get_the_title')->alias(static fn (mixed $id = 0): string => 'Post '.($id ?: WP_Query::$current));
    when('get_post')->alias(static fn (mixed $id): object => (object) ['ID' => (int) $id, 'post_title' => 'Post '.$id]);
    when('get_permalink')->alias(static fn (mixed $id = null): string => 'https://example.test/p/'.($id ?? WP_Query::$current).'/');
    when('the_permalink')->alias(static function (mixed $id = null): void {
        echo 'https://example.test/p/'.($id ?? WP_Query::$current).'/';
    });
    when('has_post_thumbnail')->justReturn(false);
    when('get_post_thumbnail_id')->justReturn(0);
    when('get_the_post_thumbnail')->justReturn('');
    when('bis_get_attachment_image')->justReturn('');
    when('image_sizer')->justReturn('');
    when('__')->returnArg();
    when('esc_html__')->returnArg();
    when('esc_url')->returnArg();
    when('esc_js')->alias(static fn (mixed $text): string => 'js('.$text.')');
    when('esc_attr')->alias(static fn (mixed $text): string => htmlspecialchars((string) $text));
    when('esc_html')->alias(static fn (mixed $text): string => htmlspecialchars((string) $text));
    when('sanitize_title_with_dashes')->alias(static fn (string $text): string => strtolower(str_replace(' ', '-', $text)));
    when('format_phone_dash')->justReturn('');
    when('uamswp_custom_table_query')->justReturn([]);
    when('get_the_excerpt')->justReturn('');
    when('get_the_content')->justReturn('');
    when('wp_strip_all_tags')->returnArg();
    when('wp_trim_words')->returnArg();
    when('get_post_meta')->justReturn('');
    when('get_post_status')->justReturn('publish');
    when('wp_get_post_parent_id')->justReturn(0);
    when('uamswp_fad_term_slug')->alias(static fn (mixed $id, string $taxonomy): string => $taxonomy.'-'.var_export($id, true));
    when('wp_reset_postdata')->alias(static function () use ($calls): void {
        $calls[] = 'reset';
    });
    when('do_shortcode')->alias(static function (string $code) use ($calls): string {
        $calls[] = $code;

        return '[shortcode]';
    });

    return $calls;
}

test('the providers section lists a card for each provider, and records their ids, regions and titles once each', function (): void {
    $calls = expertise_section_cards(['physician_region:21' => [3, 4], 'physician_region:22' => [4], 'physician_region:23' => 5, 'physician_title:21' => 'MD', 'physician_title:22' => 'MD', 'physician_title:23' => 'DO']);

    $output = expertise_section('uamswp_expertise_physicians', ['show_providers_section' => true, 'physicians_query' => expertise_section_query([21, 22, 23]), 'physicians' => [21, 22, 23], 'provider_ids' => [21, 22, 23]]);

    expect(substr_count($output, '<div class="card">'))->toBe(3)
        ->and($output)->toContain('<h2 class="module-title"><span class="title">Providers</span></h2>')
        ->toContain('<data id="provider_ids" data-postids="21,22,23," data-regions="region-3,region-4," data-titles="MD,DO,"></data>')
        ->toContain('<button class="btn btn-lg btn-primary" aria-label="Load all providers">Load All</button>')
        ->not->toContain('no-results')
        ->not->toContain('<script')
        ->and(strpos($output, 'href="https://example.test/p/21/"'))->toBeLessThan((int) strpos($output, 'href="https://example.test/p/22/"'))
        ->and($calls->getArrayCopy())->toBe(['[uamswp_provider_ajax_filter providers="21,22,23"]', 'reset'])
        ->and(WP_Query::$queries)->toBe([]);
});

test('the providers section says so when no provider matches', function (): void {
    expertise_section_cards();

    $output = expertise_section_squeeze(expertise_section('uamswp_expertise_physicians', ['show_providers_section' => true, 'physicians_query' => expertise_section_query([]), 'physicians' => [], 'provider_ids' => []]));

    expect($output)->toContain('<div class="card-list card-list-doctors"><span class="no-results">Sorry, there are no providers matching your filter criteria. Please adjust your filter options or reset the filters.</span></div>')
        ->not->toContain('<data');
});

test('a region filter in the query string runs the provider query again for that region and remembers it in a cookie', function (): void {
    expertise_section_cards(['physician_title:22' => 'MD']);
    $_GET['_filter_region'] = 'north<b>';
    $_COOKIE['wp_filter_region'] = 'south';
    WP_Query::$queue = [[22]];

    $output = expertise_section('uamswp_expertise_physicians', ['show_providers_section' => true, 'physicians_query' => expertise_section_query([21, 22]), 'physicians' => [21, 22], 'provider_ids' => [21, 22]]);

    expect(WP_Query::$queries)->toBe([[
        'post_type' => 'provider',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'fields' => 'ids',
        'post__in' => [21, 22],
        'tax_query' => [['taxonomy' => 'region', 'field' => 'slug', 'terms' => 'north<b>']],
    ]])
        ->and(substr_count($output, '<div class="card">'))->toBe(1)
        ->and($output)->toContain('data-postids="22,"')
        ->toContain('document.cookie = "wp_filter_region=north&lt;b&gt;; path=/; domain="+window.location.hostname;');
});

test('a region remembered in the cookie runs the provider query for it without setting the cookie again', function (): void {
    expertise_section_cards();
    $_COOKIE['wp_filter_region'] = 'south';
    WP_Query::$queue = [[]];

    $output = expertise_section('uamswp_expertise_physicians', ['show_providers_section' => true, 'physicians_query' => expertise_section_query([21]), 'physicians' => [21], 'provider_ids' => [21]]);

    expect(WP_Query::$queries[0]['tax_query'])->toBe([['taxonomy' => 'region', 'field' => 'slug', 'terms' => 'south']])
        ->and($output)->toContain('no-results')
        ->not->toContain('<script');
});

test('an empty region filter runs the provider query with no region', function (): void {
    expertise_section_cards();
    $_GET['_filter_region'] = '';
    WP_Query::$queue = [[]];

    $output = expertise_section('uamswp_expertise_physicians', ['show_providers_section' => true, 'physicians_query' => expertise_section_query([21]), 'physicians' => [21], 'provider_ids' => [21]]);

    expect(WP_Query::$queries[0]['tax_query'])->toBe([])
        ->and($output)->toContain('document.cookie = "wp_filter_region=; path=/;');
});

test('the locations section lists a card for each location, and records their ids and regions once each', function (): void {
    $calls = expertise_section_cards(['location_region:31' => 7, 'location_region:32' => 7, 'location_region:33' => 8]);

    $output = expertise_section('uamswp_expertise_locations', ['show_locations_section' => true, 'location_query' => expertise_section_query([31, 32, 33]), 'locations' => [31, 32, 33]]);

    expect(substr_count($output, 'class="card'))->toBeGreaterThanOrEqual(3)
        ->and($output)->toContain('<h2 class="module-title"><span class="title">Locations</span></h2>')
        ->toContain('<div class="card-list card-list-locations">')
        ->toContain('<data id="location_ids" data-postids="31,32,33," data-regions="region-7,region-8,"></data>')
        ->not->toContain('no-results')
        ->and(strpos($output, 'Post 31'))->toBeLessThan((int) strpos($output, 'Post 32'))
        ->and($calls->getArrayCopy())->toBe(['[uamswp_location_ajax_filter locations="31,32,33"]', 'reset'])
        ->and(WP_Query::$queries)->toBe([]);
});

test('the locations section says so when no location matches', function (): void {
    expertise_section_cards();

    $output = expertise_section_squeeze(expertise_section('uamswp_expertise_locations', ['show_locations_section' => true, 'location_query' => expertise_section_query([]), 'locations' => []]));

    expect($output)->toContain('<div class="card-list card-list-locations"><span class="no-results">Sorry, there are no locations matching your filter criteria. Please adjust your filter options or reset the filters.</span></div>')
        ->not->toContain('<data');
});

test('a region filter runs the location query again for that region, from the query string before the cookie', function (array $get, array $cookie, array $tax): void {
    expertise_section_cards();
    $_GET = $get;
    $_COOKIE = $cookie;
    WP_Query::$queue = [[]];

    expertise_section('uamswp_expertise_locations', ['show_locations_section' => true, 'location_query' => expertise_section_query([31]), 'locations' => [31, 32]]);

    expect(WP_Query::$queries)->toBe([[
        'post_type' => 'location',
        'post_status' => 'publish',
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'post__in' => [31, 32],
        'tax_query' => $tax,
    ]]);
})->with([
    'the query string' => [['_filter_region' => 'north'], ['wp_filter_region' => 'south'], [['taxonomy' => 'region', 'field' => 'slug', 'terms' => 'north']]],
    'the cookie' => [[], ['wp_filter_region' => 'south'], [['taxonomy' => 'region', 'field' => 'slug', 'terms' => 'south']]],
    'an empty filter' => [['_filter_region' => ''], [], []],
]);

test('the providers and locations sections print nothing when they do not show', function (string $callback, string $flag): void {
    expertise_section_cards();
    $_GET['_filter_region'] = 'north';

    expect(expertise_section($callback, [$flag => false, 'physicians_query' => expertise_section_query([21]), 'location_query' => expertise_section_query([31])]))->toBe('')
        ->and(WP_Query::$queries)->toBe([]);
})->with([
    'providers' => ['uamswp_expertise_physicians', 'show_providers_section'],
    'locations' => ['uamswp_expertise_locations', 'show_locations_section'],
]);

test('the video plays through Lyte when it is installed, and is embedded otherwise', function (): void {
    when('lyte_preparse')->justReturn('');
    when('lyte_parse')->alias(static fn (string $url): string => '[lyte '.$url.']');
    when('get_field')->alias(static fn (string $name): mixed => $name === 'expertise_youtube_link' ? 'https://www.youtube.com/watch?v=abc' : null);

    expect(trim(expertise_section('uamswp_expertise_youtube')))->toBe('<div class="alignwide">[lyte httpv://www.youtube.com/watch?v=abc]</div>');

    when('get_field')->justReturn();

    expect(expertise_section('uamswp_expertise_youtube'))->toBe('');
});

test('the related conditions and treatments lists are headed for an area of expertise', function (string $callback, string $flag, string $query, string $heading, string $disclaimer): void {
    when('get_the_permalink')->alias(static fn (int $id): string => 'https://example.test/p/'.$id.'/');
    when('esc_attr')->returnArg();

    $output = expertise_section($callback, [$flag => true, $query => (object) ['posts' => [(object) ['ID' => 5, 'post_title' => 'Asthma']]], 'page_title' => 'Eye Care']);

    expect($output)->toContain('<span class="title">'.$heading.'</span>')
        ->toContain('href="https://example.test/p/5/"')
        ->toContain($disclaimer)
        ->and(expertise_section($callback, [$flag => false]))->toBe('');
})->with([
    'conditions' => ['uamswp_expertise_conditions_cpt', 'show_conditions_section', 'conditions_cpt_query', 'Related Conditions', 'some of which may not be listed below'],
    'treatments' => ['uamswp_expertise_treatments_cpt', 'show_treatments_section', 'treatments_cpt_query', 'Related Medical Treatments and Procedures', 'some of which may not be listed below'],
]);

test('the related areas list each area with a link and its trimmed excerpt when it has one', function (): void {
    $calls = expertise_section_cards();
    when('get_permalink')->alias(static fn (): string => 'https://example.test/p/'.WP_Query::$current.'/');
    when('get_the_title')->alias(static fn (): string => 'Area '.WP_Query::$current);
    when('has_excerpt')->alias(static fn (): bool => WP_Query::$current === 61);
    when('get_the_excerpt')->justReturn('An excerpt.');
    when('wp_trim_words')->alias(static fn (string $text, int $words, string $more): string => $text.' ('.$words.')'.$more);

    $output = expertise_section('uamswp_expertise_associated', ['show_related_aoe_section' => true, 'expertise_query' => expertise_section_query([61, 62])]);

    expect(expertise_section_squeeze($output))->toBe(expertise_section_squeeze('<section class="uams-module link-list link-list-layout-split bg-auto" id="related-expertise" aria-labelledby="related-expertise-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6 heading">
                        <div class="text-container">
                            <h2 class="module-title" id="related-expertise-title"><span class="title">Related Areas of Expertise</span></h2>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 list">
                        <ul>
                            <li class="item"><div class="text-container"><h3 class="h5"><a href="https://example.test/p/61/" aria-label="Go to Area of Expertise page for Area 61">Area 61</a></h3><p>An excerpt. (30)&nbsp;&hellip;</p></div></li>
                            <li class="item"><div class="text-container"><h3 class="h5"><a href="https://example.test/p/62/" aria-label="Go to Area of Expertise page for Area 62">Area 62</a></h3></div></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>'))
        ->and($calls->getArrayCopy())->toBe(['reset'])
        ->and(expertise_section('uamswp_expertise_associated', ['show_related_aoe_section' => false, 'expertise_query' => expertise_section_query([61])]))->toBe('');
});

test('the podcast section embeds the episodes tagged with the podcast name', function (): void {
    when('esc_js')->alias(static fn (string $text): string => 'js('.$text.')');

    $output = expertise_section('uamswp_expertise_podcast', ['show_podcast_section' => true, 'podcast_name' => 'Eye "Care"', 'page_title' => 'Eye Care']);

    expect(expertise_section_squeeze($output))->toBe(expertise_section_squeeze('<section class="uams-module podcast-list bg-auto" id="podcast">
        <script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">
        </script>
        <script type="text/javascript">
            radiomd_embedded_filtered_tag("uams","radiomd-embedded-filtered-tag",303,"js(Eye "Care")");
        </script>
        <style type="text/css">
            #radiomd-embedded-filtered-tag iframe {
            width: 100%;
            border: none;
        }
        </style>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h2 class="module-title"><span class="title">UAMS Health Talk Podcast</span></h2>
                    <div class="module-body text-center">
                        <p class="lead">In the UAMS Health Talk podcast, experts from UAMS talk about a variety of health topics, providing tips and guidelines to help people lead healthier lives. Listen to the episode(s) featuring the topic of Eye Care.</p>
                    </div>
                    <div class="content-width mt-8" id="radiomd-embedded-filtered-tag"></div>
                </div>
                <div class="col-12 more">
                    <p class="lead">Find other great episodes on other topics and from other UAMS Health providers.</p>
                    <div class="cta-container">
                        <a href="/podcast/" class="btn btn-primary" aria-label="Listen to more episodes of the UAMS Health Talk podcast">Listen to More Episodes</a>
                    </div>
                </div>
            </div>
        </div>
    </section>'))
        ->and(expertise_section('uamswp_expertise_podcast', ['show_podcast_section' => false, 'podcast_name' => 'Eye Care']))->toBe('');
});

test('the related resources link to the full list filtered to this area once there are more than a page of them', function (): void {
    expertise_section_cards();
    when('uams_fad_link_target_attributes')->alias(static fn (string $target): string => ' target="'.$target.'"');
    $post = new WP_Post;
    $post->post_name = 'eye-care';

    $output = expertise_section('uamswp_expertise_resource', ['post' => $post, 'page_title' => 'Eye Care', 'show_related_resource_section' => true, 'resources' => [71, 72], 'resource_query' => expertise_section_query([71, 72]), 'resource_postsPerPage' => 1]);

    expect($output)->toContain('<span class="title">Related Resources</span>')
        ->not->toContain('Related Resources Related to')
        ->toContain('<p class="lead">Want to find more resources related to Eye Care?</p>')
        ->toContain('<a href="/clinical-resource/?_resource_aoe=eye-care" class="btn btn-outline-primary" aria-label="View the full list of clinical resources related to Eye Care" target="_blank">View the Full List</a>')
        ->and(expertise_section('uamswp_expertise_resource', ['post' => $post, 'show_related_resource_section' => false]))->toBe('');
});

test('the areas within this one are queried by parent, leaving out those hidden from the menu, and get an expertise card each', function (): void {
    $calls = expertise_section_cards();
    WP_Query::$queue = [[81, 82]];

    $output = expertise_section('uamswp_list_child_expertise', ['page_id' => 7, 'page_title' => 'Eye Care', 'show_child_aoe_section' => true]);

    expect(WP_Query::$queries)->toBe([[
        'post_type' => 'expertise',
        'post_status' => 'publish',
        'post_parent' => 7,
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'meta_query' => ['relation' => 'OR', ['key' => 'hide_from_sub_menu', 'value' => '1', 'compare' => '!=']],
    ]])
        ->and($output)->toContain('<section class="uams-module expertise-list bg-auto" id="sub-expertise" aria-labelledby="sub-expertise-title" >')
        ->toContain('<h2 class="module-title" id="sub-expertise-title"><span class="title">Areas Within Eye Care</span></h2>')
        ->toContain('<div class="card-list card-list-expertise">')
        ->and(substr_count($output, 'Post 81'))->toBeGreaterThan(0)
        ->and(strpos($output, 'Post 81'))->toBeLessThan((int) strpos($output, 'Post 82'))
        ->and($calls->getArrayCopy())->toBe(['reset']);
});

test('the areas within this one print nothing when there are none, or when they do not show', function (): void {
    expertise_section_cards();
    WP_Query::$queue = [[]];

    expect(expertise_section('uamswp_list_child_expertise', ['page_id' => 7, 'page_title' => 'Eye Care', 'show_child_aoe_section' => true]))->toBe('')
        ->and(expertise_section('uamswp_list_child_expertise', ['page_id' => 7, 'show_child_aoe_section' => false]))->toBe('')
        ->and(WP_Query::$queries)->toHaveCount(1);
});

test("the appointment band links to this area's locations when it has any, and to the location list otherwise", function (mixed $locations, string $url): void {
    when('get_field')->alias(static fn (string $name): mixed => $name === 'location_expertise' ? $locations : null);

    expect(expertise_section_squeeze(expertise_section('uamswp_expertise_appointment', ['show_appointment_section' => true])))
        ->toBe(expertise_section_squeeze('<section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>Make an Appointment</h2>
                        <p>Request an appointment by <a href="'.$url.'" data-itemtitle="Contact a clinic directly">contacting a clinic directly</a> or by calling the UAMS&nbsp;Health appointment line at <a href="tel:501-686-8000" class="no-break" data-itemtitle="Call the UAMS Health appointment line">(501) 686-8000</a>.</p>
                    </div>
                </div>
            </div>
        </section>'))
        ->and(expertise_section('uamswp_expertise_appointment', ['show_appointment_section' => false]))->toBe('');
})->with([
    'with locations' => [[31], '#locations'],
    'without' => [[], '/location/'],
]);

test('the jump links list each section that shows, in page order', function (): void {
    $all = ['show_jump_links_section' => true, 'page_title' => 'Eye Care', 'show_podcast_section' => true, 'show_child_aoe_section' => true, 'show_related_resource_section' => true, 'show_conditions_section' => true, 'show_treatments_section' => true, 'show_providers_section' => true, 'show_locations_section' => true, 'show_related_aoe_section' => true, 'show_appointment_section' => true];

    expect(expertise_section_squeeze(expertise_section('uamswp_expertise_jump_links', $all)))->toBe(expertise_section_squeeze('<nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
            <h2>Contents</h2>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse inner-container" id="jump-link-nav">
                <ul class="nav navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#podcast" title="Jump to the section of this page about UAMS Health Talk Podcast">Podcast</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sub-expertise" title="Jump to the section of this page about Areas Within Cancer Care">Areas Within Eye Care</a></li>
                    <li class="nav-item"><a class="nav-link" href="#related-resources" title="Jump to the section of this page about Resources">Resources</a></li>
                    <li class="nav-item"><a class="nav-link" href="#conditions" title="Jump to the section of this page about Conditions Treated">Conditions</a></li>
                    <li class="nav-item"><a class="nav-link" href="#treatments" title="Jump to the section of this page about Medical Treatments and Procedures Performed">Treatments &amp; Procedures</a></li>
                    <li class="nav-item"><a class="nav-link" href="#providers" title="Jump to the section of this page about Providers">Providers</a></li>
                    <li class="nav-item"><a class="nav-link" href="#locations" title="Jump to the section of this page about Locations">Locations</a></li>
                    <li class="nav-item"><a class="nav-link" href="#related-expertise" title="Jump to the section of this page about Related Areas of Expertise">Related Areas</a></li>
                    <li class="nav-item"><a class="nav-link" href="#appointment-info" title="Jump to the section of this page about making an appointment">Make an Appointment</a></li>
                </ul>
            </div>
        </nav>'));
});

test('each jump link shows only with its section, and none print when the jump links do not show', function (string $flag, string $anchor): void {
    $output = expertise_section('uamswp_expertise_jump_links', ['show_jump_links_section' => true, 'page_title' => 'Eye Care', $flag => true]);

    expect(substr_count($output, 'class="nav-item"'))->toBe(1)
        ->and($output)->toContain('href="#'.$anchor.'"')
        ->and(expertise_section('uamswp_expertise_jump_links', ['show_jump_links_section' => false, $flag => true]))->toBe('');
})->with([
    'podcast' => ['show_podcast_section', 'podcast'],
    'areas within' => ['show_child_aoe_section', 'sub-expertise'],
    'resources' => ['show_related_resource_section', 'related-resources'],
    'conditions' => ['show_conditions_section', 'conditions'],
    'treatments' => ['show_treatments_section', 'treatments'],
    'providers' => ['show_providers_section', 'providers'],
    'locations' => ['show_locations_section', 'locations'],
    'related areas' => ['show_related_aoe_section', 'related-expertise'],
    'appointment' => ['show_appointment_section', 'appointment-info'],
]);
