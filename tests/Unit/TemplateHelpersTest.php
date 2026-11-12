<?php

/**
 * The callbacks several page templates share (includes/class.fad-template-helpers.php), and which template
 * registers each one. The functions used to be declared in every template that used them, so two templates
 * could not be loaded in one process; the source checks keep each declaration in the one file.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../../includes/class.fad-template-helpers.php';

/**
 * A stand-in for `$wpdb` that records nothing and quotes the way the real one does for these two calls.
 */
function template_helpers_wpdb(): object
{
    return new class
    {
        public string $posts = 'wp_posts';

        public function prepare(string $query, string $value): string
        {
            return str_replace('%s', "'".addslashes($value)."'", $query);
        }

        public function esc_like(string $text): string
        {
            return addcslashes($text, '_%\\');
        }
    };
}

test('the default page body class is added to the list', function (): void {
    expect(uams_default_page_body_class(['home']))->toBe(['home', 'page-template-default'])
        ->and(uams_default_page_body_class([]))->toBe(['page-template-default']);
});

test('the SEOPress description is the excerpt the template set', function (): void {
    $GLOBALS['excerpt'] = 'The page excerpt';

    expect(sp_titles_desc('Original description'))->toBe('The page excerpt');

    unset($GLOBALS['excerpt']);
});

test('the entry gets the white background class after the ones it has', function (): void {
    expect(uamswp_add_entry_class(['class' => 'entry']))->toBe(['class' => 'entry bg-white'])
        ->and(uamswp_add_entry_class(['class' => '', 'id' => 'x']))->toBe(['class' => ' bg-white', 'id' => 'x']);
});

test('the skip links script is dequeued', function (): void {
    Functions\expect('wp_dequeue_script')->once()->with('skip-links');

    child_dequeue_skip_links();
});

test('the posts query is limited to titles starting with the letter, or to digits for a number', function (string $starts_with, string $expected): void {
    $GLOBALS['wpdb'] = template_helpers_wpdb();
    when('esc_sql')->alias(static fn (string $text): string => addslashes($text));
    $query = new class($starts_with)
    {
        public function __construct(private string $startsWith) {}

        public function get(string $name): string
        {
            return $name === 'starts_with' ? $this->startsWith : '';
        }
    };

    $where = title_filter(' AND 1=1', $query);

    expect($where)->toBe(' AND 1=1'.$expected);
})->with([
    'a letter' => ['B', " AND wp_posts.post_title LIKE 'B%'"],
    'a digit' => ['7', " AND wp_posts.post_title REGEXP '^[0-9]'"],
    'a quote is escaped' => ["O'", " AND wp_posts.post_title LIKE 'O\\'%'"],
]);

test('the terms query is left alone without a first letter', function (): void {
    $GLOBALS['wpdb'] = template_helpers_wpdb();
    $clauses = ['where' => 'x'];

    expect(uamswp_terms_clauses($clauses, ['condition'], []))->toBe($clauses);
});

test('the terms query is limited to names starting with the letter, or to digits for a number', function (string $letter, string $expected): void {
    $GLOBALS['wpdb'] = template_helpers_wpdb();

    $clauses = uamswp_terms_clauses(['where' => 'x'], ['condition'], ['__first_letter' => $letter]);

    expect($clauses)->toBe(['where' => 'x'.$expected]);
})->with([
    'a letter' => ['C', " AND t.name LIKE 'C%'"],
    'a digit' => ['4', " AND t.name REGEXP '^[0-9]'"],
    'a wildcard is escaped, then quoted' => ['_', " AND t.name LIKE '\\\\_%'"],
]);

/**
 * The hook each helper is registered on, and the templates that register it. A template not listed registers none.
 *
 * @return array<string, array{registration: string, templates: list<string>}>
 */
function template_helper_registrations(): array
{
    return [
        'uams_default_page_body_class' => [
            'registration' => "add_filter('body_class', 'uams_default_page_body_class');",
            'templates' => ['archive-condition', 'archive-taxonomy-conditions', 'archive-taxonomy-treatments', 'archive-treatments', 'single-clinical-resource', 'single-condition', 'single-expertise', 'single-treatment'],
        ],
        'sp_titles_desc' => [
            'registration' => "add_filter('seopress_titles_desc', 'sp_titles_desc');",
            'templates' => ['single-condition', 'single-locations', 'single-physicians', 'single-treatment', 'taxonomy-condition', 'taxonomy-treatment_procedure'],
        ],
        'child_dequeue_skip_links' => [
            'registration' => "add_action('wp_enqueue_scripts', 'child_dequeue_skip_links');",
            'templates' => ['doximity-list', 'mychart-list-provider', 'provider-image'],
        ],
        'title_filter' => [
            'registration' => "add_filter('posts_where', 'title_filter', 10, 2);",
            'templates' => ['archive-condition', 'archive-treatments'],
        ],
        'uamswp_terms_clauses' => [
            'registration' => "add_filter('terms_clauses', 'uamswp_terms_clauses', 10, 3);",
            'templates' => ['archive-taxonomy-conditions', 'archive-taxonomy-treatments'],
        ],
        'uamswp_add_entry_class' => [
            'registration' => "add_filter('genesis_attr_entry', 'uamswp_add_entry_class');",
            'templates' => ['single-clinical-resource', 'single-expertise'],
        ],
    ];
}

/**
 * Every template's source, keyed by name without the extension.
 *
 * @return array<string, string>
 */
function template_sources(): array
{
    $sources = [];

    foreach (glob(dirname(__DIR__, 2).'/templates/*.php') ?: [] as $file) {
        $sources[basename($file, '.php')] = (string) file_get_contents($file);
    }

    return $sources;
}

test('the template sources are found', function (): void {
    expect(count(template_sources()))->toBeGreaterThan(15);
});

test('no template declares a shared helper', function (string $function): void {
    $declaring = array_keys(array_filter(
        template_sources(),
        static fn (string $source): bool => preg_match('/^\s*function\s+'.preg_quote($function, '/').'\s*\(/m', $source) === 1,
    ));

    expect($declaring)->toBe([]);
})->with(array_keys(template_helper_registrations()));

test('each template registers a shared helper on the same hook as before', function (string $function): void {
    $expected = template_helper_registrations()[$function];

    $registering = array_keys(array_filter(
        template_sources(),
        static fn (string $source): bool => substr_count($source, $expected['registration']) === 1,
    ));
    sort($registering);

    expect($registering)->toBe($expected['templates']);
})->with(array_keys(template_helper_registrations()));

test('no template registers a shared helper under a hook the list does not name', function (string $function): void {
    $expected = template_helper_registrations()[$function];

    $callers = array_keys(array_filter(
        template_sources(),
        static fn (string $source): bool => preg_match('/add_(?:filter|action)\(\s*[\'"][a-z_]+[\'"],\s*[\'"]'.preg_quote($function, '/').'[\'"]/', $source) === 1,
    ));
    sort($callers);

    expect($callers)->toBe($expected['templates']);
})->with(array_keys(template_helper_registrations()));
