<?php

/**
 * The small partials under templates/loops, templates/blocks and templates/fwp: the term lists, the appointment
 * and clinical trial blocks, the resource list item, and the loop wrappers that include a card for each post.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';

/**
 * A stand-in for a term query that returns the given terms.
 *
 * @param  list<object>  $terms
 */
function small_partials_terms(array $terms): object
{
    return new class($terms)
    {
        /** @param list<object> $terms */
        public function __construct(private array $terms) {}

        /** @return list<object> */
        public function get_terms(): array
        {
            return $this->terms;
        }
    };
}

test('the conditions list links each condition to its page', function (): void {
    when('get_term_link')->alias(static fn (int $id): string => 'https://example.test/condition/'.$id.'/');
    $terms = [(object) ['term_id' => 4, 'name' => 'Asthma'], (object) ['term_id' => 9, 'name' => 'Gout']];

    $output = uamswp_test_include_partial('loops/conditions-loop', ['conditions_query' => small_partials_terms($terms)]);

    expect($output)->toContain('<h2 class="module-title"><span class="title">Conditions Treated</span></h2>')
        ->and($output)->toContain('href="https://example.test/condition/4/" aria-label="Go to Condition page for Asthma"')
        ->and($output)->toContain('href="https://example.test/condition/9/" aria-label="Go to Condition page for Gout"')
        ->and(substr_count($output, '<li>'))->toBe(2);
});

test('the conditions list prints no items without conditions', function (): void {
    $output = uamswp_test_include_partial('loops/conditions-loop', ['conditions_query' => small_partials_terms([])]);

    expect($output)->toContain('<ul class="list">')
        ->and($output)->not->toContain('<li>');
});

test('the treatments list links each treatment to its page', function (): void {
    when('get_term_link')->alias(static fn (int $id): string => 'https://example.test/treatment/'.$id.'/');
    $terms = [(object) ['term_id' => 2, 'name' => 'Bypass']];

    $output = uamswp_test_include_partial('loops/treatments-loop', ['treatments_query' => small_partials_terms($terms)]);

    expect($output)->toContain('<span class="title">Medical Treatments and Procedures Performed</span>')
        ->and($output)->toContain('<a href="https://example.test/treatment/2/" aria-label="Go to Treatment page for Bypass" class="btn btn-outline-primary">Bypass</a>')
        ->and(substr_count($output, '<li>'))->toBe(1);
});

test('the treatments list prints no items without treatments', function (): void {
    $output = uamswp_test_include_partial('loops/treatments-loop', ['treatments_query' => small_partials_terms([])]);

    expect($output)->not->toContain('<li>');
});

test('the appointment block links to the locations on the page when there are valid ones', function (): void {
    $output = uamswp_test_include_partial('blocks/appointment', ['locations' => [1], 'location_valid' => true]);

    expect($output)->toContain('<a href="#locations" data-itemtitle="Contact a clinic directly">')
        ->and($output)->toContain('href="tel:501-686-8000"');
});

test('the appointment block links to the location archive otherwise', function (array $variables): void {
    $output = uamswp_test_include_partial('blocks/appointment', $variables);

    expect($output)->toContain('<a href="/location/" data-itemtitle="Contact a clinic directly">');
})->with([
    'no locations' => [['locations' => [], 'location_valid' => true]],
    'no valid location' => [['locations' => [1], 'location_valid' => false]],
]);

test('the clinical trials block names the subject', function (): void {
    $output = uamswp_test_include_partial('blocks/clinical-trials', ['clinical_trial_title' => 'asthma']);

    expect($output)->toContain('<h2 class="module-title"><span class="title">Clinical Trials</span></h2>')
        ->and($output)->toContain('for those related to asthma.</p>')
        ->and($output)->toContain('href="https://uams.trialstoday.org/"');
});

/**
 * Stub the functions the clinical resource list item reads, for one resource.
 *
 * @param  array<string, mixed>  $fields  The ACF fields by name.
 */
function small_partials_resource(array $fields, string $title, string $excerpt, string $content = ''): void
{
    when('get_field')->alias(static fn (string $name): mixed => $fields[$name] ?? null);
    when('get_the_title')->justReturn($title);
    when('get_the_excerpt')->justReturn($excerpt);
    when('get_the_content')->justReturn($content);
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('wp_trim_words')->alias(static fn (string $text, int $words, string $more): string => 'TRIMMED'.$more);
    when('get_permalink')->justReturn('https://example.test/resource/7/');
    when('esc_attr')->alias(static fn (string $text): string => htmlspecialchars($text));
    when('esc_html')->alias(static fn (string $text): string => htmlspecialchars($text));
}

test('the resource list item uses the short title and the type label', function (): void {
    small_partials_resource(['clinical_resource_title_short' => 'Short', 'clinical_resource_type' => ['value' => 'guide', 'label' => 'Guide']], 'Full title', 'An excerpt.');

    $output = uamswp_test_include_partial('loops/resource-list-item', ['id' => 7]);

    expect($output)->toContain('<a href="https://example.test/resource/7/" aria-label="View Clinical Resource page for Short">Short</a>')
        ->and($output)->toContain('<span class="sr-only">(</span>Guide<span class="sr-only">)</span>')
        ->and($output)->toContain('<p>An excerpt.</p>');
});

test('the resource list item falls back to the full title and cleans it for the label', function (): void {
    small_partials_resource(['clinical_resource_title_short' => '', 'clinical_resource_type' => ['value' => 'guide', 'label' => 'Guide']], 'Mom&#8217;s "best" guide', 'An excerpt.');

    $output = uamswp_test_include_partial('loops/resource-list-item', ['id' => 7]);

    expect($output)->toContain('aria-label="View Clinical Resource page for Mom&#039;s &#039;best&#039; guide"')
        ->and($output)->toContain('>Mom&amp;#8217;s &quot;best&quot; guide</a>');
});

test('the resource list item puts a plain space in the label for a non-breaking space entity in the title', function (): void {
    small_partials_resource(['clinical_resource_title_short' => '', 'clinical_resource_type' => ['value' => 'guide', 'label' => 'Guide']], 'A&nbsp;B', 'An excerpt.');

    expect(uamswp_test_include_partial('loops/resource-list-item', ['id' => 7]))->toContain('aria-label="View Clinical Resource page for A B"');
});

test('the resource list item takes the text of the content when there is no excerpt', function (): void {
    small_partials_resource(['clinical_resource_type' => ['value' => 'guide', 'label' => 'Guide']], 'Title', '', '<b>From content</b>');

    $output = uamswp_test_include_partial('loops/resource-list-item', ['id' => 7]);

    expect($output)->toContain('<p>From content</p>');
});

test('the resource list item trims an excerpt longer than 160 characters only', function (): void {
    small_partials_resource(['clinical_resource_type' => ['value' => 'guide', 'label' => 'Guide']], 'Title', str_repeat('a', 160));

    expect(uamswp_test_include_partial('loops/resource-list-item', ['id' => 7]))->toContain('<p>'.str_repeat('a', 160).'</p>');

    small_partials_resource(['clinical_resource_type' => ['value' => 'guide', 'label' => 'Guide']], 'Title', str_repeat('a', 161));

    expect(uamswp_test_include_partial('loops/resource-list-item', ['id' => 7]))->toContain('<p>TRIMMED &hellip;</p>');
});
