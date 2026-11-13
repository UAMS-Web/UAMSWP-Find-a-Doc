<?php

/**
 * The condition and treatment post type archives: templates/archive-condition.php and templates/archive-treatments.php.
 *
 * The two files are the same template for two post types, so every test runs against both. Each lists the posts
 * of its type 40 to a page, or all of them with `?showall`, or those starting with one character with `?alpha`,
 * and prints an A to Z filter and the page links under the list.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';
require_once __DIR__.'/../../includes/class.fad-template-helpers.php';

/**
 * What differs between the two archives.
 *
 * @return array<string, array{template: string, post_type: string, headline: string, intro: string, default: string, title_filter: string, none: string, nav: string, phrase: string}>
 */
function condition_treatment_archives(): array
{
    return [
        'conditions' => [
            'template' => 'archive-condition',
            'post_type' => 'condition',
            'headline' => 'conditions_archive_headline',
            'intro' => 'conditions_archive_intro_text',
            'default' => 'Conditions',
            'title_filter' => 'uamswp_fad_title_archive_condition',
            'none' => 'No conditions meet your criteria.',
            'nav' => 'Condition list pagination',
            'phrase' => 'conditions and procedures',
        ],
        'treatments' => [
            'template' => 'archive-treatments',
            'post_type' => 'treatment',
            'headline' => 'treatments_archive_headline',
            'intro' => 'treatments_archive_intro_text',
            'default' => 'Treatments &amp; Procedures',
            'title_filter' => 'uamswp_fad_title_archive_treatments',
            'none' => 'No treatments meet your criteria.',
            'nav' => 'Treatment list pagination',
            'phrase' => 'treatments and procedures',
        ],
    ];
}

/**
 * The two archives as a dataset, one archive per case.
 *
 * @return array<string, array{0: array{template: string, post_type: string, headline: string, intro: string, default: string, title_filter: string, none: string, nav: string, phrase: string}}>
 */
function condition_treatment_dataset(): array
{
    return array_map(static fn (array $archive): array => [$archive], condition_treatment_archives());
}

/**
 * Render one of the archives.
 *
 * @param  array{template: string, post_type: string, headline: string, intro: string, default: string, title_filter: string, none: string, nav: string, phrase: string}  $archive
 * @param  array<string, string>  $query  The query string (`$_GET`).
 * @param  list<int>  $posts  The post ids the query finds.
 * @param  int|null  $found  The number of posts it matched over every page; the number found when null.
 * @param  mixed  $paged  The `paged` query variable.
 * @param  array<string, mixed>  $options  ACF option values by field name.
 */
function condition_treatment_archive(array $archive, array $query = [], array $posts = [], ?int $found = null, mixed $paged = '', array $options = []): string
{
    when('get_header')->alias(static function (): void {
        echo '[header]';
    });
    when('get_footer')->alias(static function (): void {
        echo '[footer]';
    });
    when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => $id === 'option' ? ($options[$field] ?? null) : null);
    when('get_query_var')->alias(static fn (string $var): mixed => match ($var) {
        'post_type' => $archive['post_type'],
        'paged' => $paged,
        default => '',
    });
    when('get_post_type_archive_link')->alias(static fn (string $post_type): string => 'https://example.test/'.$post_type.'/');
    when('get_the_permalink')->alias(static fn (): string => 'https://example.test/'.$archive['post_type'].'/'.WP_Query::$current.'/');
    when('get_the_title')->alias(static fn (): string => 'Item '.WP_Query::$current);
    when('esc_attr')->alias(static fn (string $text): string => htmlspecialchars($text));
    when('__')->returnArg();
    when('wp_reset_postdata')->alias(static function (): void {
        echo '[reset]';
    });

    WP_Query::$queue = [$posts];
    WP_Query::$found = $found === null ? [] : [$found];
    WP_Query::$queries = [];

    $saved = $_GET;
    $_GET = $query;

    try {
        return uamswp_test_render_template($archive['template']);
    } finally {
        $_GET = $saved;
    }
}

/**
 * The page links a list printed, as `label=href` with the current page starred.
 *
 * @return list<string>
 */
function condition_treatment_page_items(string $html): array
{
    preg_match_all('#<li class="page-item( current)?"><a class="page-link"(?: aria-label="(\w+)")?(?: href="([^"]*)")?[^>]*>(?:<span aria-hidden="true">)?([^<]*)#', $html, $items, PREG_SET_ORDER);

    return array_map(static fn (array $item): string => ($item[1] !== '' ? '*' : '').($item[2] !== '' ? $item[2] : $item[4]).(($item[3] ?? '') !== '' ? '='.$item[3] : ''), $items);
}

/**
 * The labels of the A to Z links an archive printed that are marked selected.
 *
 * @return list<string>
 */
function condition_treatment_selected(string $html): array
{
    preg_match_all('#aria-selected="true"[^>]*>(?:<span aria-hidden="true">)?([^<]*)#', $html, $selected);

    return $selected[1];
}

test('the A to Z filter links All, a number and each letter to the list address', function (): void {
    $html = uamswp_fad_archive_alpha_links('https://example.test/list/', '', 'things');

    preg_match_all('#<div class="custom-control"><a class="az-filter-label" href="([^"]*)" aria-selected="(true|false)" aria-label="([^"]*)">(?:<span aria-hidden="true">)?([^<]*)#', $html, $links, PREG_SET_ORDER);

    expect($links)->toHaveCount(28)
        ->and(array_map(static fn (array $link): string => $link[4], $links))->toBe(['All', '#', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'])
        ->and($links[0][1])->toBe('https://example.test/list/')
        ->and($links[0][3])->toBe('Remove any filter')
        ->and($links[1][1])->toBe('https://example.test/list/?alpha=0')
        ->and($links[1][3])->toBe('Show only the things that begin with a number')
        ->and($links[2][1])->toBe('https://example.test/list/?alpha=a')
        ->and($links[2][3])->toBe('Show only the things that begin with the letter A')
        ->and($links[27][1])->toBe('https://example.test/list/?alpha=z')
        ->and($links[27][3])->toBe('Show only the things that begin with the letter Z')
        ->and($html)->toStartWith('<div class="custom-control"><a class="az-filter-label" href="https://example.test/list/" aria-selected="true" aria-label="Remove any filter"><span aria-hidden="true">All</span></a></div><div class="custom-control">')
        ->and($html)->toContain('aria-label="Show only the things that begin with a number"><span aria-hidden="true">#</span></a></div><div class="custom-control">')
        ->and($html)->toEndWith('aria-label="Show only the things that begin with the letter Z">Z</a></div>');
});

test('the A to Z filter marks the link of the character the list is filtered by', function (string $selected, array $expected): void {
    expect(condition_treatment_selected(uamswp_fad_archive_alpha_links('/list/', $selected, 'things')))->toBe($expected);
})->with([
    'not filtered' => ['', ['All']],
    'a number' => ['0', ['#']],
    'the first letter' => ['a', ['A']],
    'a middle letter' => ['m', ['M']],
    'the last letter' => ['z', ['Z']],
    'an upper case letter matches nothing' => ['Z', []],
    'more than one character matches nothing' => ['bc', []],
]);

test('the page links of a list with one page or none are left out', function (int $pages): void {
    expect(uamswp_fad_archive_page_links('/list/', 1, $pages, '', 'List pagination'))->toBe('');
})->with([0, 1]);

test('the page links link the pages around the current one, and the first, previous, next and last pages past three', function (int $paged, int $pages, array $expected): void {
    $html = uamswp_fad_archive_page_links('/list/', $paged, $pages, '', 'List pagination');

    expect(condition_treatment_page_items($html))->toBe($expected);
})->with([
    'two pages, on the first' => [1, 2, ['*1', '2=/list/page/2/']],
    'two pages, on the second' => [2, 2, ['1=/list/page/1/', '*2']],
    'three pages, on the first' => [1, 3, ['*1', '2=/list/page/2/', '3=/list/page/3/']],
    'three pages, on the second' => [2, 3, ['1=/list/page/1/', '*2', '3=/list/page/3/']],
    'three pages, on the third' => [3, 3, ['1=/list/page/1/', '2=/list/page/2/', '*3']],
    'four pages, on the first' => [1, 4, ['*1', '2=/list/page/2/', 'Next=/list/page/2/', 'Last=/list/page/4/']],
    'four pages, on the second' => [2, 4, ['Previous=/list/page/1/', '1=/list/page/1/', '*2', '3=/list/page/3/', 'Next=/list/page/3/', 'Last=/list/page/4/']],
    'four pages, on the third' => [3, 4, ['First=/list/page/1/', 'Previous=/list/page/2/', '2=/list/page/2/', '*3', '4=/list/page/4/', 'Next=/list/page/4/']],
    'four pages, on the last' => [4, 4, ['First=/list/page/1/', 'Previous=/list/page/3/', '3=/list/page/3/', '*4']],
    'five pages, in the middle' => [3, 5, ['First=/list/page/1/', 'Previous=/list/page/2/', '2=/list/page/2/', '*3', '4=/list/page/4/', 'Next=/list/page/4/', 'Last=/list/page/5/']],
    'five pages, on the fourth' => [4, 5, ['First=/list/page/1/', 'Previous=/list/page/3/', '3=/list/page/3/', '*4', '5=/list/page/5/', 'Next=/list/page/5/']],
    'ten pages, on the fifth' => [5, 10, ['First=/list/page/1/', 'Previous=/list/page/4/', '4=/list/page/4/', '*5', '6=/list/page/6/', 'Next=/list/page/6/', 'Last=/list/page/10/']],
    'ten pages, on the ninth' => [9, 10, ['First=/list/page/1/', 'Previous=/list/page/8/', '8=/list/page/8/', '*9', '10=/list/page/10/', 'Next=/list/page/10/']],
    'ten pages, on the last' => [10, 10, ['First=/list/page/1/', 'Previous=/list/page/9/', '9=/list/page/9/', '*10']],
]);

test('the page links end with the query string, sit in a labeled navigation, and are followed by the show-all link', function (): void {
    $html = uamswp_fad_archive_page_links('/list/', 3, 5, '?alpha=b', 'List pagination');

    expect($html)->toStartWith('<div class="list-pagination"><nav aria-label="List pagination"><ul class="pagination">')
        ->and($html)->toEndWith('</ul></nav></div><div class="show-all"><p><a href="/list/?showall=true">Show all items</a></p></div>')
        ->and(condition_treatment_page_items($html))->toBe(['First=/list/page/1/?alpha=b', 'Previous=/list/page/2/?alpha=b', '2=/list/page/2/?alpha=b', '*3', '4=/list/page/4/?alpha=b', 'Next=/list/page/4/?alpha=b', 'Last=/list/page/5/?alpha=b'])
        ->and($html)->toContain('<li class="page-item current"><a class="page-link">3</a></li>')
        ->and($html)->toContain('<li class="page-item"><a class="page-link" href="/list/page/4/?alpha=b" class="inactive" >4</a></li>')
        ->and($html)->toContain('<li class="page-item"><a class="page-link" aria-label="First" href="/list/page/1/?alpha=b"><span aria-hidden="true">&laquo;</span></a></li>')
        ->and($html)->toContain('<li class="page-item"><a class="page-link" aria-label="Previous" href="/list/page/2/?alpha=b"><span aria-hidden="true">&lsaquo;</span></a></li>')
        ->and($html)->toContain('<li class="page-item"><a class="page-link" aria-label="Next" href="/list/page/4/?alpha=b"><span aria-hidden="true">&rsaquo;</span></a></li>')
        ->and($html)->toContain('<li class="page-item"><a class="page-link" aria-label="Last" href="/list/page/5/?alpha=b"><span aria-hidden="true">&raquo;</span></a></li>');
});

test('the archive prints its list between the header and the footer and registers its filters', function (array $archive): void {
    $output = condition_treatment_archive($archive, [], [11, 12]);

    expect($output)->toStartWith('[header]')
        ->and($output)->toEndWith('[footer]')
        ->and(uamswp_test_render_hooks()['add_filter'])->toBe([
            ['body_class', 'uams_default_page_body_class'],
            ['posts_where', 'title_filter', 10, 2],
            ['seopress_titles_title', $archive['title_filter'], 15, 2],
        ])
        ->and($output)->toContain('<ul class="list"><li><a href="https://example.test/'.$archive['post_type'].'/11/" aria-label="Learn about Item 11"  class="btn btn-outline-primary">Item 11</a></li><li><a href="https://example.test/'.$archive['post_type'].'/12/" aria-label="Learn about Item 12"  class="btn btn-outline-primary">Item 12</a></li></ul>[reset]')
        ->and($output)->not->toContain($archive['none']);
})->with(condition_treatment_dataset());

test('the archive says when nothing meets the criteria', function (array $archive): void {
    $output = condition_treatment_archive($archive, [], [], 0);

    expect($output)->toContain('<p class="text-center content-width">'.$archive['none'].'</p>')
        ->and($output)->not->toContain('<ul class="list">')
        ->and($output)->not->toContain('[reset]')
        ->and($output)->not->toContain('list-pagination')
        ->and($output)->not->toContain('Show all items');
})->with(condition_treatment_dataset());

test('the archive prints its headline and intro from the options, with a default headline', function (array $archive): void {
    $set = condition_treatment_archive($archive, [], [1], null, '', [$archive['headline'] => 'Our List', $archive['intro'] => '<p>Intro.</p>']);

    expect($set)->toContain('<h1 class="entry-title" itemprop="headline">Our List</h1>')
        ->and($set)->toContain('<div class="entry-content clearfix" itemprop="text"><p>Intro.</p></div>');

    foreach ([[], [$archive['headline'] => '', $archive['intro'] => '']] as $options) {
        $default = condition_treatment_archive($archive, [], [1], null, '', $options);

        expect($default)->toContain('<h1 class="entry-title" itemprop="headline">'.$archive['default'].'</h1>')
            ->and($default)->toMatch('#</header>\s*</section>#');
    }
})->with(condition_treatment_dataset());

test('the archive queries 40 posts a page from the page it is on', function (array $archive, mixed $paged, int $offset): void {
    condition_treatment_archive($archive, [], [1], null, $paged);

    expect(WP_Query::$queries)->toBe([['post_type' => $archive['post_type'], 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => 40, 'offset' => $offset]]);
})->with(condition_treatment_dataset())->with([
    'no page' => ['', 0],
    'page zero' => [0, 0],
    'the first page' => [1, 0],
    'the third page' => [3, 80],
    'a page as a string' => ['2', 40],
]);

test('the archive links its pages from the number of posts found', function (array $archive, int $found, mixed $paged, array $expected): void {
    $output = condition_treatment_archive($archive, [], [1], $found, $paged);
    $base = 'https://example.test/'.$archive['post_type'].'/';

    expect(condition_treatment_page_items($output))->toBe(array_map(static fn (string $item): string => str_replace('@', $base, $item), $expected));

    if ($expected === []) {
        expect($output)->not->toContain('list-pagination')
            ->and($output)->not->toContain('Show all items');
    } else {
        expect($output)->toContain('<nav aria-label="'.$archive['nav'].'">')
            ->and($output)->toContain('<div class="show-all"><p><a href="'.$base.'?showall=true">Show all items</a></p></div>');
    }
})->with(condition_treatment_dataset())->with([
    'one page' => [40, 1, []],
    'one more than a page' => [41, 1, ['*1', '2=@page/2/']],
    'two pages, on the second' => [80, 2, ['1=@page/1/', '*2']],
    'four pages, on the third' => [160, 3, ['First=@page/1/', 'Previous=@page/2/', '2=@page/2/', '*3', '4=@page/4/', 'Next=@page/4/']],
]);

test('the archive lists every post on one page with showall and links back to the paged list', function (array $archive, array $query, array $selected): void {
    $output = condition_treatment_archive($archive, $query, [1, 2, 3], 400);
    $base = 'https://example.test/'.$archive['post_type'].'/';

    expect(WP_Query::$queries)->toBe([['post_type' => $archive['post_type'], 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1]])
        ->and($output)->toContain('<div class="show-paged"><p><a href="'.$base.'">Show paged</a></p></div>')
        ->and($output)->not->toContain('list-pagination')
        ->and(condition_treatment_selected($output))->toBe($selected);
})->with(condition_treatment_dataset())->with([
    'unfiltered' => [['showall' => 'true'], ['All']],
    'with a letter' => [['showall' => 'true', 'alpha' => 'c'], ['C']],
]);

test('the archive filters by the first character of alpha, on one page', function (array $archive, string $alpha, string $first): void {
    $output = condition_treatment_archive($archive, ['alpha' => $alpha], [1], 400, 1);
    $base = 'https://example.test/'.$archive['post_type'].'/';

    expect(WP_Query::$queries)->toBe([['post_type' => $archive['post_type'], 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1, 'offset' => 0, 'starts_with' => $first]])
        ->and($output)->not->toContain('list-pagination')
        ->and($output)->not->toContain('Show paged')
        ->and(condition_treatment_selected($output))->toBe([$first === '0' ? '#' : strtoupper($first)])
        ->and($output)->toContain('<a class="az-filter-label" href="'.$base.'?alpha=a" aria-selected="'.($first === 'a' ? 'true' : 'false').'" aria-label="Show only the '.$archive['phrase'].' that begin with the letter A">A</a>');
})->with(condition_treatment_dataset())->with([
    'a letter' => ['b', 'b'],
    'more than one character' => ['bc', 'b'],
    'a number' => ['0', '0'],
    'the letter a' => ['a', 'a'],
]);

test('the archive is not filtered by an empty alpha', function (array $archive): void {
    $output = condition_treatment_archive($archive, ['alpha' => ''], [1], 41, 1);

    expect(WP_Query::$queries[0])->not->toHaveKey('starts_with')
        ->and(condition_treatment_selected($output))->toBe(['All'])
        ->and(condition_treatment_page_items($output))->toBe(['*1', '2=https://example.test/'.$archive['post_type'].'/page/2/']);
})->with(condition_treatment_dataset());
