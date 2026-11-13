<?php

/**
 * The condition and treatment taxonomy archives: templates/archive-taxonomy-conditions.php and
 * templates/archive-taxonomy-treatments.php, page templates that list the terms of a taxonomy.
 *
 * The two files are the same template for two taxonomies, so every test runs against both. Each lists the terms
 * 40 to a page, or all of them with `?showall`, or those starting with one character with `?alpha`, and prints the
 * A to Z filter and page links the condition and treatment post type archives print (ConditionTreatmentArchivesTest).
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';
require_once __DIR__.'/../../includes/class.fad-template-helpers.php';

/**
 * What differs between the two archives.
 *
 * @return array<string, array{template: string, taxonomy: string, headline: string, intro: string, default: string, title_filter: string, list: string, none: string, nav: string, phrase: string}>
 */
function taxonomy_archives(): array
{
    return [
        'conditions' => [
            'template' => 'archive-taxonomy-conditions',
            'taxonomy' => 'condition',
            'headline' => 'conditions_archive_headline',
            'intro' => 'conditions_archive_intro_text',
            'default' => 'Conditions',
            'title_filter' => 'uamswp_fad_title_archive_taxonomy_conditions',
            'list' => 'List of Conditions',
            'none' => 'No conditions meet your criteria.',
            'nav' => 'Condition list pagination',
            'phrase' => 'conditions',
        ],
        'treatments' => [
            'template' => 'archive-taxonomy-treatments',
            'taxonomy' => 'treatment',
            'headline' => 'treatments_archive_headline',
            'intro' => 'treatments_archive_intro_text',
            'default' => 'Treatments &amp; Procedures',
            'title_filter' => 'uamswp_fad_title_archive_taxonomy_treatments',
            'list' => 'List of Treatments',
            'none' => 'No treatments meet your criteria.',
            'nav' => 'Treatment list pagination',
            'phrase' => 'treatments and procedures',
        ],
    ];
}

/**
 * The two archives as a dataset, one archive per case.
 *
 * @return array<string, array{0: array{template: string, taxonomy: string, headline: string, intro: string, default: string, title_filter: string, list: string, none: string, nav: string, phrase: string}}>
 */
function taxonomy_archive_dataset(): array
{
    return array_map(static fn (array $archive): array => [$archive], taxonomy_archives());
}

/**
 * Render one of the archives.
 *
 * @param  array{template: string, taxonomy: string, headline: string, intro: string, default: string, title_filter: string, list: string, none: string, nav: string, phrase: string}  $archive
 * @param  array<string, string>  $query  The query string (`$_GET`).
 * @param  list<string>  $names  The names of the terms the query finds.
 * @param  mixed  $count  What wp_count_terms() returns.
 * @param  mixed  $paged  The `paged` query variable.
 * @param  array<string, mixed>  $options  ACF option values by field name.
 */
function taxonomy_archive(array $archive, array $query = [], array $names = [], mixed $count = 0, mixed $paged = '', array $options = []): string
{
    when('get_header')->alias(static function (): void {
        echo '[header]';
    });
    when('get_footer')->alias(static function (): void {
        echo '[footer]';
    });
    when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => $id === 'option' ? ($options[$field] ?? null) : null);
    when('get_query_var')->alias(static fn (string $var): mixed => $var === 'paged' ? $paged : '');
    when('get_permalink')->justReturn('https://example.test/'.$archive['taxonomy'].'-list/');
    when('get_term_link')->alias(static fn (object $term, string $taxonomy): string => 'https://example.test/'.$taxonomy.'/'.$term->slug.'/');
    when('wp_count_terms')->alias(static function (array $args) use ($count): mixed {
        $GLOBALS['taxonomy_archive_counts'][] = [$args['taxonomy'], $args];

        return $count;
    });
    when('esc_attr')->alias(static fn (string $text): string => htmlspecialchars($text));
    when('__')->returnArg();

    WP_Term_Query::$queue = [array_map(static fn (string $name): object => (object) ['name' => $name, 'slug' => strtolower(str_replace(' ', '-', $name))], $names)];
    WP_Term_Query::$queries = [];
    $GLOBALS['taxonomy_archive_counts'] = [];

    $saved = $_GET;
    $_GET = $query;

    try {
        return uamswp_test_render_template($archive['template']);
    } finally {
        $_GET = $saved;
    }
}

/**
 * The page links an archive printed, as `label=href` with the current page starred.
 *
 * @return list<string>
 */
function taxonomy_archive_page_items(string $html): array
{
    preg_match_all('#<li class="page-item( current)?"><a class="page-link"(?: aria-label="(\w+)")?(?: href="([^"]*)")?[^>]*>(?:<span aria-hidden="true">)?([^<]*)#', $html, $items, PREG_SET_ORDER);

    return array_map(static fn (array $item): string => ($item[1] !== '' ? '*' : '').($item[2] !== '' ? $item[2] : $item[4]).(($item[3] ?? '') !== '' ? '='.$item[3] : ''), $items);
}

/**
 * The labels of the A to Z links an archive printed that are marked selected.
 *
 * @return list<string>
 */
function taxonomy_archive_selected(string $html): array
{
    preg_match_all('#aria-selected="true"[^>]*>(?:<span aria-hidden="true">)?([^<]*)#', $html, $selected);

    return $selected[1];
}

test('the archive lists its terms between the header and the footer and registers its filters', function (array $archive): void {
    $output = taxonomy_archive($archive, [], ['Back Pain', 'Asthma'], 2);
    $base = 'https://example.test/'.$archive['taxonomy'].'/';

    expect($output)->toStartWith('[header]')
        ->and($output)->toEndWith('[footer]')
        ->and(uamswp_test_render_hooks()['add_filter'])->toBe([
            ['body_class', 'uams_default_page_body_class'],
            ['terms_clauses', 'uamswp_terms_clauses', 10, 3],
            ['seopress_titles_title', $archive['title_filter'], 15, 1],
        ])
        ->and($output)->toContain('<h2 class="module-title sr-only">'.$archive['list'].'</h2>')
        ->and($output)->toContain('<ul class="list"><li><a href="'.$base.'back-pain/" aria-label="Learn about Back Pain"  class="btn btn-outline-primary">Back Pain</a></li><li><a href="'.$base.'asthma/" aria-label="Learn about Asthma"  class="btn btn-outline-primary">Asthma</a></li></ul>')
        ->and($output)->not->toContain($archive['none'])
        ->and(taxonomy_archive_selected($output))->toBe(['All'])
        ->and($output)->toContain('<a class="az-filter-label" href="https://example.test/'.$archive['taxonomy'].'-list/?alpha=a" aria-selected="false" aria-label="Show only the '.$archive['phrase'].' that begin with the letter A">A</a>');
})->with(taxonomy_archive_dataset());

test('the archive says when no term meets the criteria', function (array $archive): void {
    $output = taxonomy_archive($archive, [], [], 0);

    expect($output)->toContain('<p class="text-center content-width">'.$archive['none'].'</p>')
        ->and($output)->not->toContain('<ul class="list">')
        ->and($output)->not->toContain('list-pagination')
        ->and($output)->not->toContain('Show all items');
})->with(taxonomy_archive_dataset());

test('the archive prints its headline and intro from the options, with a default headline', function (array $archive): void {
    $set = taxonomy_archive($archive, [], ['A'], 1, '', [$archive['headline'] => 'Our List', $archive['intro'] => '<p>Intro.</p>']);

    expect($set)->toContain('<h1 class="entry-title" itemprop="headline">Our List</h1>')
        ->and($set)->toContain('<div class="entry-content clearfix" itemprop="text"><p>Intro.</p></div>');

    foreach ([[], [$archive['headline'] => '', $archive['intro'] => '']] as $options) {
        $default = taxonomy_archive($archive, [], ['A'], 1, '', $options);

        expect($default)->toContain('<h1 class="entry-title" itemprop="headline">'.$archive['default'].'</h1>')
            ->and($default)->toMatch('#</header>\s*</article>#');
    }
})->with(taxonomy_archive_dataset());

test('the archive queries 40 terms a page from the page it is on, and counts them all', function (array $archive, mixed $paged, int $offset): void {
    taxonomy_archive($archive, [], ['A'], 1, $paged);

    expect(WP_Term_Query::$queries)->toBe([['taxonomy' => $archive['taxonomy'], 'number' => 40, 'offset' => $offset, 'hide_empty' => 0]])
        ->and($GLOBALS['taxonomy_archive_counts'])->toBe([[$archive['taxonomy'], ['taxonomy' => $archive['taxonomy'], 'hide_empty' => 0, 'count' => true]]]);
})->with(taxonomy_archive_dataset())->with([
    'no page' => ['', 0],
    'page zero' => [0, 0],
    'the first page' => [1, 0],
    'the third page' => [3, 80],
    'a page as a string' => ['2', 40],
]);

test('the archive links its pages from the number of terms counted', function (array $archive, mixed $count, mixed $paged, array $expected): void {
    $output = taxonomy_archive($archive, [], ['A'], $count, $paged);
    $base = 'https://example.test/'.$archive['taxonomy'].'-list/';

    expect(taxonomy_archive_page_items($output))->toBe(array_map(static fn (string $item): string => str_replace('@', $base, $item), $expected));

    if ($expected === []) {
        expect($output)->not->toContain('list-pagination')
            ->and($output)->not->toContain('Show all items');
    } else {
        expect($output)->toContain('<nav aria-label="'.$archive['nav'].'">')
            ->and($output)->toContain('<div class="show-all"><p><a href="'.$base.'?showall=true">Show all items</a></p></div>');
    }
})->with(taxonomy_archive_dataset())->with([
    'one page' => [40, 1, []],
    'one more than a page' => [41, 1, ['*1', '2=@page/2/']],
    'a count as a string' => ['80', 2, ['1=@page/1/', '*2']],
    'four pages, on the third' => [160, 3, ['First=@page/1/', 'Previous=@page/2/', '2=@page/2/', '*3', '4=@page/4/', 'Next=@page/4/']],
    'a failed count' => [new WP_Error('invalid_taxonomy'), 1, []],
]);

test('the archive lists every term with showall and links back to the paged list', function (array $archive, array $query, array $selected): void {
    $output = taxonomy_archive($archive, $query, ['A', 'B'], 400);

    expect(WP_Term_Query::$queries)->toBe([['taxonomy' => $archive['taxonomy'], 'hide_empty' => 0]])
        ->and($GLOBALS['taxonomy_archive_counts'])->toBe([])
        ->and($output)->toContain('<div class="show-paged"><p><a href="https://example.test/'.$archive['taxonomy'].'-list/">Show paged</a></p></div>')
        ->and($output)->not->toContain('list-pagination')
        ->and(taxonomy_archive_selected($output))->toBe($selected);
})->with(taxonomy_archive_dataset())->with([
    'unfiltered' => [['showall' => 'true'], ['All']],
    'with a letter' => [['showall' => 'true', 'alpha' => 'c'], ['C']],
]);

test('the archive filters by the first character of alpha and keeps the filter on its page links', function (array $archive, string $alpha, string $first): void {
    $output = taxonomy_archive($archive, ['alpha' => $alpha], ['Bee'], 90, 2);
    $base = 'https://example.test/'.$archive['taxonomy'].'-list/';

    expect(WP_Term_Query::$queries)->toBe([['taxonomy' => $archive['taxonomy'], 'number' => 40, 'offset' => 40, 'hide_empty' => 0, '__first_letter' => $first]])
        ->and($GLOBALS['taxonomy_archive_counts'])->toBe([[$archive['taxonomy'], ['taxonomy' => $archive['taxonomy'], 'hide_empty' => 0, 'count' => true, '__first_letter' => $first]]])
        ->and(taxonomy_archive_selected($output))->toBe([$first === '0' ? '#' : strtoupper($first)])
        ->and(taxonomy_archive_page_items($output))->toBe(['1='.$base.'page/1/?alpha='.$first, '*2', '3='.$base.'page/3/?alpha='.$first]);
})->with(taxonomy_archive_dataset())->with([
    'a letter' => ['b', 'b'],
    'more than one character' => ['bc', 'b'],
    'a number' => ['0', '0'],
]);

test('the archive is not filtered by an empty alpha', function (array $archive): void {
    $output = taxonomy_archive($archive, ['alpha' => ''], ['A'], 41, 1);

    expect(WP_Term_Query::$queries[0])->not->toHaveKey('__first_letter')
        ->and($GLOBALS['taxonomy_archive_counts'][0][1])->not->toHaveKey('__first_letter')
        ->and(taxonomy_archive_selected($output))->toBe(['All'])
        ->and(taxonomy_archive_page_items($output))->toBe(['*1', '2=https://example.test/'.$archive['taxonomy'].'-list/page/2/']);
})->with(taxonomy_archive_dataset());
