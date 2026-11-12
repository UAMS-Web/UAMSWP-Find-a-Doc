<?php

/**
 * Pins this repository's changes to the bundled FacetWP Alpha add-on (plugins/facetwp-alpha-uams): the facet
 * registration, the letter links, the selection query and the front-end assets.
 *
 * The registration closure includes the class file without `_once`, so it can run once per process; the
 * first call here loads the class and every test reuses the object it returned.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/acf-functions.php';
require_once __DIR__.'/../Support/facetwp.php';

final class FacetwpAlphaTest extends UnitTestCase
{
    /** @var list<array{0: string, 1: mixed}> */
    private static array $filters = [];

    private static ?\FacetWP_Facet $facet = null;

    private object $facetwp;

    private object $database;

    private mixed $previous_wpdb = null;

    protected function setUp(): void
    {
        parent::setUp();

        // See AcfBlocksTest: the ACF functions file loads first, under Brain Monkey's own hook functions.
        \uamswp_test_acf_functions();

        when('__')->returnArg();
        when('remove_accents')->alias(static fn (string $text): string => strtr($text, ['É' => 'E', 'Ö' => 'O']));

        $this->facetwp = new \stdClass;
        $this->facetwp->unfiltered_post_ids = [1, 2, 3];
        $this->facetwp->display = new \stdClass;
        $this->facetwp->display->assets = [];

        when('FWP')->justReturn($this->facetwp);

        $this->database = new class
        {
            public string $prefix = 'wp_';

            /** @var list<string> */
            public array $queries = [];

            /** @var list<string> */
            public array $letters = [];

            /** @var array{0: string, 1: array<int, string>}|null */
            public ?array $prepared = null;

            /** @var list<string> */
            public array $ids = [];

            /**
             * @return list<string>
             */
            public function get_col(string $sql): array
            {
                $this->queries[] = $sql;

                return str_contains($sql, 'DISTINCT post_id') ? $this->ids : $this->letters;
            }

            /**
             * @param  array<int, string>  $args
             */
            public function prepare(string $query, array $args): string
            {
                $this->prepared = [$query, $args];

                return 'PREPARED SQL: DISTINCT post_id';
            }
        };
        global $wpdb;
        $this->previous_wpdb = $wpdb;
        $wpdb = $this->database;

        if (! self::$facet instanceof \FacetWP_Facet) {
            when('add_filter')->alias(static function (string $hook, mixed $callback): void {
                self::$filters[] = [$hook, $callback];
            });
            require_once dirname(__DIR__, 2).'/plugins/facetwp-alpha-uams/index.php';

            $register = self::$filters[0][1];
            $types = $register(['existing' => 1]);
            self::$facet = $types['alpha'];
        }
    }

    protected function tearDown(): void
    {
        global $wpdb;
        $wpdb = $this->previous_wpdb;

        parent::tearDown();
    }

    private function facet(): \FacetWP_Facet
    {
        $this->assertNotNull(self::$facet);

        return self::$facet;
    }

    private function sql(): string
    {
        return (string) preg_replace('/\s+/', ' ', trim($this->database->queries[0]));
    }

    public function test_the_alphabet_facet_type_is_added_beside_the_existing_types(): void
    {
        $this->assertSame('facetwp_facet_types', self::$filters[0][0]);
        $this->assertCount(1, self::$filters);
        $this->assertInstanceOf(\FacetWP_Facet_Alpha_Addon::class, $this->facet());
        $this->assertSame('Alphabet', $this->facet()->label);
    }

    public function test_the_letter_links_show_selected_available_and_disabled_letters(): void
    {
        $this->database->letters = ['A', 'B', 'É'];

        $html = $this->facet()->render(['facet' => ['name' => 'last_name'], 'selected_values' => ['A']]);

        $this->assertStringStartsWith('<a class="facetwp-alpha available selected"  role="link" aria-label="Any" aria-selected="true" tabindex="0" data-id="">Any</a>', $html);
        $this->assertStringContainsString('<a class="facetwp-alpha selected" role="link" aria-label="A" aria-selected="true" tabindex="0" data-id="A">A</a>', $html);
        $this->assertStringContainsString('<a class="facetwp-alpha available" role="link" aria-label="B" aria-selected="false" tabindex="0" data-id="B">B</a>', $html);
        $this->assertStringContainsString('<a class="facetwp-alpha available" role="link" aria-label="E" aria-selected="false" tabindex="0" data-id="E">E</a>', $html, 'an accented letter counts as its plain letter');
        $this->assertStringContainsString("<a class=\"facetwp-alpha disabled\" aria-disabled=\u{201C}true\u{201D} role=\"link\" aria-label=\"C\" tabindex=\"-1\" data-id=\"C\">C</a>", $html);
        $this->assertSame(27, substr_count($html, '<a '));
        $this->assertStringEndsWith('data-id="Z">Z</a>', $html);
    }

    public function test_the_query_reads_the_first_letters_of_the_unfiltered_posts_for_the_facet(): void
    {
        $this->facet()->render(['facet' => ['name' => 'last_name'], 'selected_values' => []]);

        $this->assertSame(
            "SELECT DISTINCT UPPER(LEFT(facet_display_value, 1)) AS letter FROM wp_facetwp_index WHERE facet_name = 'last_name' AND post_id IN (1,2,3) ORDER BY letter",
            $this->sql()
        );
    }

    public function test_no_posts_look_up_post_zero(): void
    {
        $this->facetwp->unfiltered_post_ids = [];

        $this->facet()->render(['facet' => ['name' => 'last_name'], 'selected_values' => []]);

        $this->assertStringContainsString('AND post_id IN (0)', $this->sql());
    }

    public function test_a_string_selection_is_treated_as_one_selected_value(): void
    {
        $this->database->letters = [];

        $html = $this->facet()->render(['facet' => ['name' => 'n'], 'selected_values' => 'Q']);

        $this->assertStringContainsString('class="facetwp-alpha selected" role="link" aria-label="Q"', $html);
        $this->assertStringContainsString('class="facetwp-alpha disabled" aria-disabled', $html);
    }

    public function test_no_letters_found_leaves_every_letter_disabled(): void
    {
        $this->database->letters = [];

        $html = $this->facet()->render(['facet' => ['name' => 'n'], 'selected_values' => []]);

        $this->assertSame(26, substr_count($html, 'facetwp-alpha disabled'));
        $this->assertSame(1, substr_count($html, 'facetwp-alpha available'));
    }

    public function test_a_value_selected_in_this_facet_clears_the_any_link(): void
    {
        $this->facetwp->or_values = ['last_name' => [1, 2]];

        $html = $this->facet()->render(['facet' => ['name' => 'last_name'], 'selected_values' => ['B']]);

        $this->assertStringStartsWith('<a class="facetwp-alpha available"  role="link" aria-label="Any" aria-selected="false" tabindex="0" data-id="">Any</a>', $html);
        $this->assertStringContainsString('IN (1,2,3)', $this->sql(), 'this facet alone leaves the unfiltered posts in place');
    }

    public function test_other_facets_narrow_the_posts_and_this_facets_own_selection_is_ignored(): void
    {
        $this->facetwp->unfiltered_post_ids = [1, 2, 3, 4, 9];
        $this->facetwp->or_values = ['color' => [1, 2, 3, 4], 'last_name' => [9], 'size' => [2, 3, 4, 7]];

        $html = $this->facet()->render(['facet' => ['name' => 'last_name'], 'selected_values' => []]);

        $this->assertStringContainsString('IN (2,3,4)', $this->sql());
        $this->assertStringStartsWith('<a class="facetwp-alpha available"  role="link" aria-label="Any" aria-selected="false"', $html);
    }

    public function test_another_facets_selection_alone_leaves_the_any_link_marked_selected(): void
    {
        $this->facetwp->or_values = ['color' => [1, 2]];

        $html = $this->facet()->render(['facet' => ['name' => 'last_name'], 'selected_values' => []]);

        $this->assertStringContainsString('IN (1,2)', $this->sql());
        $this->assertStringStartsWith('<a class="facetwp-alpha available  selected"  role="link" aria-label="Any" aria-selected="true"', $html);
    }

    public function test_other_facets_that_match_no_posts_look_up_post_zero(): void
    {
        $this->facetwp->or_values = ['color' => [1], 'size' => [2]];

        $this->facet()->render(['facet' => ['name' => 'last_name'], 'selected_values' => []]);

        $this->assertStringContainsString('IN (0)', $this->sql());
    }

    /**
     * @return array<string, array{0: mixed, 1: list<string>}>
     */
    public static function selections(): array
    {
        return [
            'a letter' => [['b'], ['b']],
            'a letter as a string' => ['B', ['B']],
            'the first of several' => [['C', 'D'], ['C']],
            'a digit' => [['7'], ['7']],
            // The pattern's $ also matches before a final newline, so this value is bound as it is.
            'a letter and a final newline' => [['a
'], ['a
']],
            'the hash alias' => [['#'], ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']],
            'the hash alias as a string' => ['#', ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']],
        ];
    }

    /**
     * @param  list<string>  $values
     */
    #[DataProvider('selections')]
    public function test_the_selection_query_binds_the_facet_name_and_each_value(mixed $selected, array $values): void
    {
        $this->database->ids = ['11', '12'];

        $ids = $this->facet()->filter_posts(['facet' => ['name' => 'last_name'], 'selected_values' => $selected]);

        $this->assertSame(['11', '12'], $ids);
        $this->assertNotNull($this->database->prepared);
        [$query, $args] = $this->database->prepared;
        $this->assertSame(array_merge(['last_name'], $values), $args);
        $this->assertStringContainsString('WHERE facet_name = %s AND UPPER(SUBSTR(facet_display_value, 1, 1)) IN ('.implode(', ', array_fill(0, count($values), '%s')).')', (string) preg_replace('/\s+/', ' ', $query));
        $this->assertStringContainsString('FROM wp_facetwp_index', $query);
    }

    /**
     * @return array<string, array{0: mixed}>
     */
    public static function bad_selections(): array
    {
        return [
            'two characters' => [['ab']],
            'a quote' => [["'"]],
            'an injection' => [["x' OR 1=1 -- "]],
            'empty' => [['']],
            'a space' => [[' ']],
            'null' => [null],
        ];
    }

    #[DataProvider('bad_selections')]
    public function test_a_selection_that_is_not_one_letter_or_digit_matches_nothing_and_runs_no_query(mixed $selected): void
    {
        $this->database->ids = ['99'];

        $this->assertSame([], $this->facet()->filter_posts(['facet' => ['name' => 'n'], 'selected_values' => $selected]));
        $this->assertNull($this->database->prepared);
        $this->assertSame([], $this->database->queries);
    }

    public function test_the_front_end_assets_are_the_add_ons_stylesheet_and_script(): void
    {
        expect('plugins_url')->twice()->with('', Mockery::type('string'))->andReturn('https://example.test/wp-content/plugins/facetwp-alpha-uams');

        $this->facet()->front_scripts();

        $this->assertSame([
            'alpha-front.css' => 'https://example.test/wp-content/plugins/facetwp-alpha-uams/assets/css/front.css',
            'alpha-front.js' => 'https://example.test/wp-content/plugins/facetwp-alpha-uams/assets/js/front.js',
        ], $this->facetwp->display->assets);
    }
}
