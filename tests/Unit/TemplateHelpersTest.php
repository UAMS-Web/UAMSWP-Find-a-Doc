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
        'uamswp_keyword_hook_header' => [
            'registration' => "add_action('wp_head', 'uamswp_keyword_hook_header');",
            'templates' => ['single-condition', 'single-treatment', 'taxonomy-condition'],
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

test('the keywords tag is printed from the alternate names with their commas dropped', function (): void {
    $GLOBALS['keywords'] = [['text' => 'heart, attack'], ['text' => 'cardiac'], ['text' => 'MI']];

    ob_start();
    uamswp_keyword_hook_header();
    $output = ob_get_clean();

    unset($GLOBALS['keywords']);

    expect($output)->toBe('<meta name="keywords" content="heart attack, cardiac, MI" />');
});

test('no keywords tag is printed without alternate names', function (mixed $keywords): void {
    $GLOBALS['keywords'] = $keywords;

    ob_start();
    uamswp_keyword_hook_header();
    $output = ob_get_clean();

    unset($GLOBALS['keywords']);

    expect($output)->toBe('');
})->with([
    'none set' => [null],
    'false' => [false],
    'an empty list' => [[]],
]);

test('no two templates declare a function with the same name', function (): void {
    $declared = [];

    foreach (template_sources() as $name => $source) {
        preg_match_all('/^function\s+(\w+)\s*\(/m', $source, $matches);

        foreach ($matches[1] as $function) {
            $declared[strtolower($function)][] = $name;
        }
    }

    $duplicates = array_filter($declared, static fn (array $templates): bool => count($templates) > 1);

    expect($duplicates)->toBe([]);
});

test('each template that sets a page title registers its own function at priority 15', function (): void {
    $registrations = [];

    foreach (template_sources() as $name => $source) {
        if (preg_match_all("/^add_filter\\('seopress_titles_title', '(\\w+)', (\\d+), (\\d+)\\);/m", $source, $matches, PREG_SET_ORDER) > 0) {
            foreach ($matches as $match) {
                $registrations[$name][] = [$match[1], $match[2], $match[3]];
            }
        }
    }

    $expected = [];

    foreach (array_keys($registrations) as $name) {
        $expected[$name] = [['uamswp_fad_title_'.str_replace('-', '_', $name), '15', '2']];
    }

    expect($registrations)->toBe($expected)
        ->and(array_keys($registrations))->toBe(['archive-condition', 'archive-taxonomy-conditions', 'archive-taxonomy-treatments', 'archive-treatments', 'single-clinical-resource', 'single-condition', 'single-expertise', 'single-locations', 'single-physicians', 'single-treatment', 'taxonomy-condition', 'taxonomy-treatment_procedure']);
});

test('each listing template shows its images with a function named for the template', function (string $template): void {
    $source = template_sources()[$template];
    $function = 'display_provider_image_'.str_replace('-', '_', $template);

    expect(substr_count($source, "add_action('genesis_loop', '".$function."');"))->toBe(1)
        ->and(preg_match('/^function '.$function.'\(\): void/m', $source))->toBe(1);
})->with(['doximity-list', 'mychart-list-provider', 'provider-image']);

test('the treatment procedure keywords function keeps its own name and its registration', function (): void {
    $source = template_sources()['taxonomy-treatment_procedure'];

    expect(preg_match('/^function uamswp_keyword_hook_header_taxonomy_treatment_procedure\(\): void/m', $source))->toBe(1)
        ->and(substr_count($source, "add_action('wp_head', 'uamswp_keyword_hook_header_taxonomy_treatment_procedure');"))->toBe(1);
});
