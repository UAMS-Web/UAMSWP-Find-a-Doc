<?php

/**
 * The post type archives under templates/: areas of expertise, locations, clinical resources and providers.
 *
 * Each one prints its FacetWP listing between the Genesis header and footer. The stubs print a token for every
 * WordPress, Genesis and FacetWP call, so a test reads the order of the calls as well as what each one printed.
 *
 * The provider and location archives redirect a request to add the visitor's saved region before they print
 * anything. That redirect ends in `exit()`, which a test cannot survive, so its address is built by
 * uamswp_fad_region_redirect_url() and tested there; the templates are rendered with a region that is already
 * in the query string, so the call is made and returns null.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';
require_once __DIR__.'/../../includes/class.fad-template-helpers.php';

/**
 * Stub what the archives call, and render one.
 *
 * @param  array<string, mixed>  $options  ACF option values by field name.
 */
function archive_template(string $name, array $options = []): string
{
    when('get_header')->alias(static function (): void {
        echo '[header]';
    });
    when('get_footer')->alias(static function (): void {
        echo '[footer]';
    });
    when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => $id === 'option' ? ($options[$field] ?? null) : null);
    when('do_shortcode')->alias(static fn (string $content): string => '{shortcode:'.$content.'}');
    when('facetwp_display')->alias(static fn (string $type, string $name = ''): string => '{'.$type.':'.$name.'}');

    return uamswp_test_render_template($name);
}

/**
 * The tokens an archive printed, in order: the header and footer, and each shortcode and FacetWP call.
 *
 * @return list<string>
 */
function archive_template_tokens(string $output): array
{
    preg_match_all('/\[header\]|\[footer\]|\{shortcode:\[[^\]]*\]\}|\{shortcode:\s*<div|\{[a-z]+:[a-z_]*\}/', $output, $matches);

    return $matches[0];
}

/**
 * Run a test with request cookies and query variables in place, and put the real ones back after it.
 *
 * @param  array<string, string>  $cookies
 * @param  array<string, string>  $query
 */
function archive_template_with_request(array $cookies, array $query, Closure $test): mixed
{
    [$savedCookies, $savedQuery] = [$_COOKIE, $_GET];
    [$_COOKIE, $_GET] = [$cookies, $query];

    try {
        return $test();
    } finally {
        [$_COOKIE, $_GET] = [$savedCookies, $savedQuery];
    }
}

test('the expertise archive prints the listing between the header and the footer', function (): void {
    $output = archive_template('archive-expertise');

    expect(archive_template_tokens($output))->toBe(['[header]', '{template:expertise}', '{pager:}', '[footer]'])
        ->and(uamswp_test_render_hooks()['add_filter'])->toBe([['facetwp_template_use_archive', '__return_true']])
        ->and($output)->toContain('<section class="uams-module" id="expertise">')
        ->and($output)->toContain('if (3 >= FWP.settings.pager.total_rows ) {');
});

test('the expertise archive prints its headline and intro from the options, with a default headline', function (array $options, string $headline, ?string $intro): void {
    $output = archive_template('archive-expertise', $options);

    expect($output)->toContain('<h1 class="entry-title" itemprop="headline">'.$headline.'</h1>');

    if ($intro === null) {
        expect($output)->toMatch('#</header>\s*</section>#');
    } else {
        expect($output)->toContain('<div class="entry-content clearfix" itemprop="text">'.$intro.'</div>');
    }
})->with([
    'both set' => [['expertise_archive_headline' => 'Our Specialties', 'expertise_archive_intro_text' => '<p>Find care.</p>'], 'Our Specialties', '<p>Find care.</p>'],
    'neither set' => [[], 'Areas of Expertise', null],
    'empty values' => [['expertise_archive_headline' => '', 'expertise_archive_intro_text' => ''], 'Areas of Expertise', null],
]);

test('the location archive prints the search, the filters and the listing between the header and the footer', function (): void {
    $output = archive_template('archive-locations');

    expect(archive_template_tokens($output))->toBe([
        '[header]',
        '{shortcode:[wpdreams_ajaxsearchpro id=2]}',
        '{facet:location_type}',
        '{facet:location_aoe}',
        '{facet:location_region}',
        '{counts:}',
        '{selections:}',
        '{sort:}',
        '{template:locations}',
        '{pager:}',
        '[footer]',
    ])
        ->and(uamswp_test_render_hooks()['add_filter'])->toBe([['facetwp_template_use_archive', '__return_true']])
        ->and($output)->toContain('<span class="result-count">{counts:} Locations</span>')
        ->and($output)->toContain('if (9 >= FWP.settings.pager.total_rows ) {');
});

test('the clinical resource archive prints the search, the filters and the listing between the header and the footer', function (): void {
    $output = archive_template('archive-clinical-resource');

    expect(archive_template_tokens($output))->toBe([
        '[header]',
        '{shortcode:[wpdreams_ajaxsearchpro id=3]}',
        '{facet:resource_provider}',
        '{facet:resource_locations}',
        '{facet:resource_type}',
        '{facet:resource_aoe}',
        '{facet:resource_conditions}',
        '{facet:resource_treatments}',
        '{counts:}',
        '{selections:}',
        '{sort:}',
        '{template:clinical_resources}',
        '{pager:}',
        '[footer]',
    ])
        ->and(uamswp_test_render_hooks()['add_filter'])->toBe([['facetwp_template_use_archive', '__return_true']])
        ->and($output)->toContain('<span class="result-count">{counts:} Clinical Resources</span>');
});

test('the clinical resource archive prints its headline from the options, with a default, in the heading and the commented-out description', function (array $options, string $headline, ?string $intro): void {
    $output = archive_template('archive-clinical-resource', $options);

    // The commented-out description is still PHP, so it runs and prints inside the HTML comment.
    expect($output)->toContain('<h1 class="sr-only" itemprop="headline">'.$headline.'</h1>')
        ->and($output)->toContain('<h1 class="entry-title" itemprop="headline">'.$headline.'</h1>');

    if ($intro === null) {
        expect($output)->toMatch('#</header>\s*</section>#');
    } else {
        expect($output)->toContain('<div class="entry-content clearfix" itemprop="text">'.$intro.'</div>');
    }
})->with([
    'both set' => [['clinical_resource_archive_headline' => 'Resource Library', 'clinical_resource_archive_intro_text' => '<p>Read more.</p>'], 'Resource Library', '<p>Read more.</p>'],
    'neither set' => [[], 'Clinical Resources', null],
    'empty values' => [['clinical_resource_archive_headline' => '', 'clinical_resource_archive_intro_text' => ''], 'Clinical Resources', null],
]);

test('the provider archive prints the search, the filters and the listing between the header and the footer', function (): void {
    $output = archive_template('archive-physicians');

    expect(archive_template_tokens($output))->toBe([
        '[header]',
        '{shortcode:[wpdreams_ajaxsearchpro id=1]}',
        '{facet:alpha}',
        '{shortcode:		<div',
        '{counts:}',
        '{selections:}',
        '{sort:}',
        '{template:physician}',
        '{pager:}',
        '[footer]',
    ])
        ->and(uamswp_test_render_hooks()['add_filter'])->toBe([
            ['excerpt_length', 'wpdocs_custom_excerpt_length', 999],
            ['facetwp_template_use_archive', '__return_true'],
        ])
        ->and($output)->toContain('<div class="fwp-filter">[facetwp facet="primary_care"]</div>')
        ->and($output)->toContain('<div class="fwp-filter">[facetwp facet="provider_region"]</div>')
        ->and($output)->toContain('<span class="result-count">{counts:} Providers</span>')
        ->and($output)->toContain('<h5 class="modal-title" id="why-not-title">Why are there no ratings?</h5>');
});

test('the provider and location archives render without redirecting when the request already has their region', function (string $name, string $parameter): void {
    // A wrong parameter name would make the template redirect and exit, which ends the run.
    $output = archive_template_with_request(['wp_filter_region' => 'central'], [$parameter => 'central'], static fn (): string => archive_template($name));

    expect($output)->toStartWith('[header]')
        ->and($output)->toEndWith('[footer]');
})->with([
    'providers' => ['archive-physicians', '_provider_region'],
    'locations' => ['archive-locations', '_location_region'],
]);

test('the region redirect adds the saved region to the address', function (string $requestUri, string $expected): void {
    expect(uamswp_fad_region_redirect_url('_provider_region', ['wp_filter_region' => 'central'], [], ['REQUEST_URI' => $requestUri]))->toBe($expected);
})->with([
    'no query string' => ['/providers/', '/providers/?_provider_region=central'],
    'a query string' => ['/providers/?_alpha=b', '/providers/?_alpha=b&_provider_region=central'],
    'an empty query string' => ['/providers/?', '/providers/??_provider_region=central'],
    'an empty address' => ['', '?_provider_region=central'],
]);

test('the region redirect reads an address that is missing or not a string as empty', function (array $server): void {
    expect(uamswp_fad_region_redirect_url('_provider_region', ['wp_filter_region' => 'central'], [], $server))->toBe('?_provider_region=central');
})->with([
    'missing' => [[]],
    'an array' => [['REQUEST_URI' => ['/providers/']]],
]);

test('the region redirect uses the parameter it is given and adds the saved region as it is', function (): void {
    expect(uamswp_fad_region_redirect_url('_location_region', ['wp_filter_region' => 'north-west'], ['_other' => '1'], ['REQUEST_URI' => '/locations/']))->toBe('/locations/?_location_region=north-west')
        ->and(uamswp_fad_region_redirect_url('_location_region', ['wp_filter_region' => ''], [], ['REQUEST_URI' => '/locations/']))->toBe('/locations/?_location_region=')
        ->and(uamswp_fad_region_redirect_url('_location_region', ['wp_filter_region' => ['a']], [], ['REQUEST_URI' => '/locations/']))->toBe('/locations/?_location_region=');
});

test('the region redirect does nothing without a saved region or when the request already has the parameter', function (array $cookies, array $query): void {
    expect(uamswp_fad_region_redirect_url('_provider_region', $cookies, $query, ['REQUEST_URI' => '/providers/']))->toBeNull();
})->with([
    'no cookies' => [[], []],
    'another cookie' => [['other' => 'central'], []],
    'a null region' => [['wp_filter_region' => null], []],
    'the parameter is set' => [['wp_filter_region' => 'central'], ['_provider_region' => 'south']],
    'the parameter is empty' => [['wp_filter_region' => 'central'], ['_provider_region' => '']],
]);
