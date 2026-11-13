<?php

/**
 * Pins the FacetWP filters, footer scripts, pager markup, index rows and hourly index schedule
 * (class.fad-fwp-functions.php).
 *
 * The file registers its filters and actions at file scope, mostly as closures, so it loads once per process
 * and the registrations are captured on that first load.
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

final class FwpFunctionsTest extends UnitTestCase
{
    /** @var list<array{0: string, 1: mixed, 2: int, 3: int}> */
    private static array $filters = [];

    /** @var list<array{0: string, 1: mixed, 2: int}> */
    private static array $actions = [];

    private static bool $loaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        // See AcfBlocksTest: the ACF functions file loads first, under Brain Monkey's own hook functions.
        \uamswp_test_acf_functions();

        if (! defined('FACETWP_URL')) {
            define('FACETWP_URL', 'https://example.test/wp-content/plugins/facetwp');
        }

        when('__')->returnArg();

        if (self::$loaded) {
            return;
        }

        when('add_filter')->alias(static function (string $hook, mixed $callback, int $priority = 10, int $accepted = 1): void {
            self::$filters[] = [$hook, $callback, $priority, $accepted];
        });
        when('add_action')->alias(static function (string $hook, mixed $callback, int $priority = 10): void {
            self::$actions[] = [$hook, $callback, $priority];
        });

        require_once dirname(__DIR__, 2).'/includes/class.fad-fwp-functions.php';
        self::$loaded = true;
    }

    /**
     * The closure registered on a filter hook (the nth one when a hook has two).
     */
    private function filter(string $hook, int $nth = 0): \Closure
    {
        $matches = array_values(array_filter(self::$filters, static fn (array $filter): bool => $filter[0] === $hook));
        $callback = $matches[$nth][1];
        $this->assertInstanceOf(\Closure::class, $callback);

        return $callback;
    }

    /**
     * @param  list<string>  $true  Conditions that answer true, as `tag:type`.
     */
    private function conditions(array $true): void
    {
        foreach (['is_post_type_archive', 'is_singular'] as $tag) {
            when($tag)->alias(static fn (string $type): bool => in_array($tag.':'.$type, $true, true));
        }
    }

    public function test_the_filters_and_actions_are_registered_in_order(): void
    {
        $this->assertSame([
            ['facetwp_is_main_query', 10, 2],
            ['facetwp_shortcode_html', 10, 2],
            ['facetwp_preload_url_vars', 10, 1],
            ['facetwp_sort_options', 10, 2],
            ['facetwp_pager_html', 10, 2],
            ['facetwp_index_row', 10, 2],
            ['facetwp_assets', 10, 1],
            ['facetwp_load_a11y', 10, 1],
            ['facetwp_index_row', 10, 2],
        ], array_map(static fn (array $filter): array => [$filter[0], $filter[2], $filter[3]], self::$filters));
        $this->assertSame('__return_true', self::$filters[7][1]);

        $this->assertSame([
            ['wp_footer', 'fwp_disable_auto_refresh', 100],
            ['wp_footer', 'fwp_facet_scripts', 100],
            ['fwp_indexer', 'fwp_cron_index', 10],
            ['init', 'register_hourly_fwp_indexer', 10],
        ], self::$actions);
    }

    public function test_facetwp_never_treats_a_query_as_the_main_query(): void
    {
        $this->assertFalse(($this->filter('facetwp_is_main_query'))(true, new \stdClass));
    }

    /**
     * @return array<string, array{0: mixed, 1: string}>
     */
    public static function shortcodes(): array
    {
        return [
            'physician list' => [['template' => 'physician'], '<div class="facetwp-template row list"></div>'],
            'clinical resources list' => [['template' => 'clinical_resources'], '<div class="facetwp-template row list"></div>'],
            'locations cards' => [['template' => 'locations'], '<div class="facetwp-template card-list"></div>'],
            'expertise cards' => [['template' => 'expertise'], '<div class="facetwp-template card-list"></div>'],
            'another template' => [['template' => 'other'], '<div class="facetwp-template"></div>'],
            'no template' => [[], '<div class="facetwp-template"></div>'],
            'attributes that are not an array' => ['physician', '<div class="facetwp-template"></div>'],
        ];
    }

    #[DataProvider('shortcodes')]
    public function test_the_shortcode_output_gets_the_class_for_its_template(mixed $atts, string $expected): void
    {
        $this->assertSame($expected, ($this->filter('facetwp_shortcode_html'))('<div class="facetwp-template"></div>', $atts));
    }

    public function test_the_auto_refresh_script_prints_on_the_three_archives_only(): void
    {
        foreach (['provider', 'location', 'clinical-resource'] as $type) {
            $this->conditions(['is_post_type_archive:'.$type]);
            ob_start();
            \fwp_disable_auto_refresh();
            $html = (string) ob_get_clean();

            $this->assertStringContainsString('FWP.extras.sort = $(this).val();', $html, $type);
        }

        $this->conditions(['is_post_type_archive:condition']);
        $this->expectOutputString('');
        \fwp_disable_auto_refresh();
    }

    public function test_the_facet_scripts_on_a_provider_archive_include_the_taxonomy_slug_and_provider_region(): void
    {
        $this->conditions(['is_post_type_archive:provider']);
        expect('get_body_class')->once();
        when('get_queried_object')->justReturn((object) ['slug' => 'cardiology']);

        ob_start();
        \fwp_facet_scripts();
        $html = (string) ob_get_clean();

        $this->assertStringContainsString('facet_label = FWP.settings.labels[facet_name];', $html);
        $this->assertStringContainsString('.facetwp-facet-specialty_checkbox .facetwp-checkbox[data-value="cardiology"]', $html);
        $this->assertStringContainsString('regionname = "_provider_region";', $html);
        $this->assertStringNotContainsString('regionname = "_location_region";', $html);
        $this->assertSame(2, substr_count($html, '</script>'));
    }

    public function test_the_facet_scripts_on_a_location_archive_use_the_location_region(): void
    {
        $this->conditions(['is_post_type_archive:location']);
        when('get_body_class')->justReturn([]);
        when('get_queried_object')->justReturn();

        ob_start();
        \fwp_facet_scripts();
        $html = (string) ob_get_clean();

        $this->assertStringContainsString('regionname = "_location_region";', $html);
        $this->assertStringNotContainsString('regionname = "_provider_region";', $html);
        $this->assertStringContainsString('.facetwp-checkbox[data-value=""]', $html);
    }

    public function test_the_facet_scripts_on_a_clinical_resource_archive_only_label_the_facets(): void
    {
        $this->conditions(['is_post_type_archive:clinical-resource']);
        when('get_body_class')->justReturn([]);

        ob_start();
        \fwp_facet_scripts();
        $html = (string) ob_get_clean();

        $this->assertStringContainsString('facet-label h6', $html);
        $this->assertStringNotContainsString('num_choices', $html);
        $this->assertSame(2, substr_count($html, '</script>'));
    }

    public function test_the_facet_scripts_print_nothing_elsewhere(): void
    {
        $this->conditions([]);
        when('get_body_class')->justReturn([]);
        $this->expectOutputString('');

        \fwp_facet_scripts();
    }

    private function facetwp(string $uri): void
    {
        when('FWP')->justReturn(new class($uri)
        {
            public object $helper;

            public object $indexer;

            public int $indexed = 0;

            public function __construct(string $uri)
            {
                $this->helper = new class($uri)
                {
                    public function __construct(private readonly string $uri) {}

                    public function get_uri(): string
                    {
                        return $this->uri;
                    }
                };
                $this->indexer = new class
                {
                    public int $calls = 0;

                    public function index(): void
                    {
                        $this->calls++;
                    }
                };
            }
        });
    }

    public function test_an_all_region_is_dropped_from_the_preloaded_provider_vars(): void
    {
        $this->facetwp('provider');

        $this->assertSame(['specialty' => ['x']], ($this->filter('facetwp_preload_url_vars'))([
            'provider_region' => ['all'],
            'location_region' => ['all'],
            'specialty' => ['x'],
        ]));
    }

    public function test_other_regions_and_other_pages_keep_their_preloaded_vars(): void
    {
        $vars = ['provider_region' => ['north'], 'location_region' => ['all', 'north']];

        $this->facetwp('provider');
        $this->assertSame($vars, ($this->filter('facetwp_preload_url_vars'))($vars));

        $this->facetwp('location');
        $all = ['provider_region' => ['all'], 'location_region' => ['all']];
        $this->assertSame($all, ($this->filter('facetwp_preload_url_vars'))($all));

        $this->facetwp('provider');
        $empty = ['provider_region' => [], 'location_region' => ''];
        $this->assertSame($empty, ($this->filter('facetwp_preload_url_vars'))($empty));
    }

    public function test_the_location_region_check_does_not_depend_on_the_provider_region(): void
    {
        $this->facetwp('provider');

        $this->assertSame(['provider_region' => ['north']], ($this->filter('facetwp_preload_url_vars'))(['provider_region' => ['north'], 'location_region' => ['all']]));
    }

    public function test_every_other_view_loses_the_date_sorts_and_keeps_the_rest(): void
    {
        $this->conditions([]);
        $options = ['date_desc' => 'a', 'date_asc' => 'b', 'title_asc' => 'c'];

        $this->assertSame(['title_asc' => 'c'], ($this->filter('facetwp_sort_options'))($options, []));
    }

    public function test_providers_and_locations_sort_by_name_only(): void
    {
        $expected = [
            'default' => ['label' => 'Sort by', 'query_args' => []],
            'title_asc' => ['label' => 'Name (A-Z)', 'query_args' => ['orderby' => 'title', 'order' => 'ASC']],
            'title_desc' => ['label' => 'Name (Z-A)', 'query_args' => ['orderby' => 'title', 'order' => 'DESC']],
        ];

        foreach (['is_post_type_archive:provider', 'is_singular:provider', 'is_post_type_archive:location', 'is_singular:location'] as $condition) {
            $this->conditions([$condition]);

            $this->assertSame($expected, ($this->filter('facetwp_sort_options'))(['x' => 1], []), $condition);
        }
    }

    public function test_clinical_resources_sort_by_date_added_modified_and_title(): void
    {
        $expected = [
            'default' => ['label' => 'Sort by', 'query_args' => []],
            'date_desc' => ['label' => 'Date Added (Newest)', 'query_args' => ['orderby' => 'date', 'order' => 'DESC']],
            'date_asc' => ['label' => 'Date Added (Oldest)', 'query_args' => ['orderby' => 'date', 'order' => 'ASC']],
            'modified_desc' => ['label' => 'Date Modified (Newest)', 'query_args' => ['orderby' => 'modified', 'order' => 'DESC']],
            'modified_asc' => ['label' => 'Date Modified (Oldest)', 'query_args' => ['orderby' => 'modified', 'order' => 'ASC']],
            'title_asc' => ['label' => 'Title (A-Z)', 'query_args' => ['orderby' => 'title', 'order' => 'ASC']],
            'title_desc' => ['label' => 'Title (Z-A)', 'query_args' => ['orderby' => 'title', 'order' => 'DESC']],
        ];

        foreach (['is_post_type_archive:clinical-resource', 'is_singular:clinical-resource'] as $condition) {
            $this->conditions([$condition]);

            $this->assertSame($expected, ($this->filter('facetwp_sort_options'))(['x' => 1], []), $condition);
        }
    }

    /**
     * @return list<int>
     */
    private function pages(string $html): array
    {
        preg_match_all('/data-page="(\d+)"/', $html, $found);

        return array_map(intval(...), $found[1]);
    }

    private function pager(int $page, int $total): string
    {
        return ($this->filter('facetwp_pager_html'))('ignored', ['page' => $page, 'total_pages' => $total]);
    }

    public function test_one_page_or_none_has_no_pager(): void
    {
        $this->assertSame('', $this->pager(1, 1));
        $this->assertSame('', $this->pager(1, 0));
    }

    public function test_the_last_page_of_two_shows_the_previous_page_and_the_current_page(): void
    {
        $this->assertSame(
            '<nav aria-label="list pagination"><ul class="pagination">'
            .'<li class="page-item"><a class="facetwp-page page-link" title="Previous Page" data-page="1"><span class="fas fa-angle-left" aria-hidden="true"></span></a></li>'
            .'<li class="page-item"><a class="facetwp-page page-link" title="Page 1" data-page="1">1</a></li>'
            .'<li class="page-item"><a class="facetwp-page page-link active" title="Page 2" data-page="2">2</a></li>'
            .'</ul></nav>',
            $this->pager(2, 2)
        );
    }

    public function test_the_first_page_of_five_shows_the_next_pages_and_the_last_page_link(): void
    {
        $html = $this->pager(1, 5);

        $this->assertSame([1, 2, 3, 2, 5], $this->pages($html));
        $this->assertStringNotContainsString('first-page', $html);
        $this->assertStringContainsString('class="facetwp-page page-link last-page" title="Last Page" data-page="5"><span class="fas fa-fast-forward aria-hidden="true"></span></a></li>', $html);
        $this->assertStringContainsString('title="Next Page" data-page="2"><span class="fas fa-angle-right" aria-hidden="true"></span></a><li', $html);
    }

    public function test_a_middle_page_shows_the_first_page_the_ten_page_jumps_and_the_last_page(): void
    {
        $this->assertSame([1, 4, 3, 4, 5, 6, 7, 15, 6, 30], $this->pages($this->pager(5, 30)));
        $this->assertSame([1, 14, 5, 13, 14, 15, 16, 17, 25, 16, 40], $this->pages($this->pager(15, 40)));
        $this->assertStringContainsString('class="facetwp-page page-link first-page" title="First Page" data-page="1"><span class="fas fa-fast-backward" aria-hidden="true"></span></a></li>', $this->pager(5, 30));
    }

    public function test_the_edges_of_each_range_are_exact(): void
    {
        // The first page link needs a page beyond the third; the last page link needs two pages after.
        $this->assertSame([2, 1, 2, 3, 4, 5, 4, 6], $this->pages($this->pager(3, 6)));
        $this->assertStringNotContainsString('first-page', $this->pager(3, 6));
        $this->assertStringContainsString('last-page', $this->pager(3, 6));
        $this->assertStringContainsString('first-page', $this->pager(4, 6));
        $this->assertStringNotContainsString('last-page', $this->pager(4, 6));

        // The ten page jump back needs a page more than ten before; the jump forward needs more than ten after.
        $this->assertSame([1, 10, 9, 10, 11, 12, 13, 21, 12, 40], $this->pages($this->pager(11, 40)));
        $this->assertSame([1, 11, 2, 10, 11, 12, 13, 14, 22, 13, 40], $this->pages($this->pager(12, 40)));
        $this->assertSame([1, 1, 2, 3, 4, 3, 12], $this->pages($this->pager(2, 12)));
        $this->assertSame([1, 1, 2, 3, 4, 12, 3, 13], $this->pages($this->pager(2, 13)));
    }

    public function test_the_primary_care_facet_keeps_only_yes_rows(): void
    {
        $row = $this->filter('facetwp_index_row', 0);

        $this->assertSame(['facet_name' => 'primary_care', 'facet_display_value' => 'Yes'], $row(['facet_name' => 'primary_care', 'facet_display_value' => 'Yes'], null));
        $this->assertFalse($row(['facet_name' => 'primary_care', 'facet_display_value' => 'No'], null));
        $this->assertSame(['facet_name' => 'other', 'facet_display_value' => 'No'], $row(['facet_name' => 'other', 'facet_display_value' => 'No'], null));
    }

    public function test_the_accessibility_script_is_always_added_and_the_pager_scroll_off_the_archives(): void
    {
        $this->conditions(['is_post_type_archive:provider']);
        $this->assertSame(
            ['keep.js' => 'k', 'accessibility.js' => 'https://example.test/wp-content/plugins/facetwp/assets/js/src/accessibility.js'],
            ($this->filter('facetwp_assets'))(['keep.js' => 'k'])
        );

        foreach (['location', 'clinical-resource'] as $type) {
            $this->conditions(['is_post_type_archive:'.$type]);
            $this->assertArrayNotHasKey('fwp-pager-scroll.js', ($this->filter('facetwp_assets'))([]), $type);
        }

        $this->conditions([]);
        $this->assertSame(
            ['accessibility.js' => FACETWP_URL.'/assets/js/src/accessibility.js', 'fwp-pager-scroll.js' => UAMS_FAD_ROOT_URL.'assets/js/fwp-pager-scroll.js'],
            ($this->filter('facetwp_assets'))([])
        );
    }

    public function test_a_resource_provider_facet_is_indexed_by_name_slug_and_full_name(): void
    {
        when('get_post')->justReturn((object) ['ID' => 55]);
        when('get_field')->alias(static fn (string $field, int $id): string => $id === 55 ? [
            'physician_last_name' => 'McCoy',
            'physician_first_name' => 'Leonard',
            'physician_middle_name' => 'H',
            'physician_full_name' => 'Leonard H McCoy, MD',
        ][$field] : '');
        when('sanitize_title_with_dashes')->alias(static fn (string $title): string => strtolower(str_replace(' ', '-', trim($title))));

        $this->assertSame(
            ['facet_name' => 'resource_provider', 'facet_value' => 'mccoy-leonard-h', 'facet_display_value' => 'Leonard H McCoy, MD'],
            ($this->filter('facetwp_index_row', 1))(['facet_name' => 'resource_provider', 'facet_value' => '55', 'facet_display_value' => '55'], null)
        );
    }

    public function test_a_resource_provider_facet_with_a_missing_post_reads_the_fields_of_post_zero(): void
    {
        when('get_post')->justReturn();
        expect('get_field')->times(4)->andReturnUsing(static fn (string $field, int $id): string => $id === 0 ? 'x' : 'wrong');
        when('sanitize_title_with_dashes')->returnArg();

        $this->assertSame(
            ['facet_name' => 'resource_provider', 'facet_value' => 'x x x', 'facet_display_value' => 'x'],
            ($this->filter('facetwp_index_row', 1))(['facet_name' => 'resource_provider', 'facet_value' => '9', 'facet_display_value' => '9'], null)
        );
    }

    public function test_the_other_resource_facets_are_indexed_by_post_slug(): void
    {
        expect('get_post')->times(4)->with(12)->andReturn((object) ['post_name' => 'main-clinic']);

        foreach (['resource_locations', 'resource_aoe', 'resource_conditions', 'resource_treatments'] as $facet) {
            $this->assertSame(
                ['facet_name' => $facet, 'facet_value' => 'main-clinic'],
                ($this->filter('facetwp_index_row', 1))(['facet_name' => $facet, 'facet_value' => '12'], null),
                $facet
            );
        }
    }

    public function test_a_resource_facet_with_a_missing_post_is_indexed_with_an_empty_slug(): void
    {
        when('get_post')->justReturn();

        $this->assertSame(
            ['facet_name' => 'resource_aoe', 'facet_value' => ''],
            ($this->filter('facetwp_index_row', 1))(['facet_name' => 'resource_aoe', 'facet_value' => '12'], null)
        );
    }

    public function test_rows_without_a_value_other_facets_and_empty_rows_pass_through_untouched(): void
    {
        expect('get_post')->never();
        $row = $this->filter('facetwp_index_row', 1);

        $this->assertSame(['facet_name' => 'resource_provider', 'facet_value' => ''], $row(['facet_name' => 'resource_provider', 'facet_value' => ''], null));
        $this->assertSame(['facet_name' => 'resource_aoe', 'facet_value' => 0], $row(['facet_name' => 'resource_aoe', 'facet_value' => 0], null));
        $this->assertSame(['facet_name' => 'specialty', 'facet_value' => '12'], $row(['facet_name' => 'specialty', 'facet_value' => '12'], null));
        $this->assertSame([], $row([], null));
        $this->assertFalse($row(false, null));
    }

    public function test_the_hourly_cron_runs_the_indexer(): void
    {
        $this->facetwp('provider');

        \fwp_cron_index();

        $this->assertSame(1, \FWP()->indexer->calls);
    }

    public function test_the_hourly_event_is_scheduled_when_it_is_missing(): void
    {
        expect('wp_next_scheduled')->once()->with('fwp_indexer')->andReturn(false);
        expect('wp_schedule_event')->once()->with(Mockery::type('int'), 'hourly', 'fwp_indexer');

        \register_hourly_fwp_indexer();
    }

    public function test_an_event_that_is_already_scheduled_is_not_scheduled_again(): void
    {
        expect('wp_next_scheduled')->once()->with('fwp_indexer')->andReturn(1700000000);
        expect('wp_schedule_event')->never();

        \register_hourly_fwp_indexer();

        $this->addToAssertionCount(1);
    }

    private function pageLink(int $page, string $kind = ''): string
    {
        $class = $kind === 'active' ? ' active' : '';

        return '<li class="page-item"><a class="facetwp-page page-link'.$class.'" title="Page '.$page.'" data-page="'.$page.'">'.$page.'</a></li>';
    }

    public function test_a_middle_page_prints_every_link_with_its_title_and_text(): void
    {
        $arrow = static fn (string $title, int $page, string $icon): string => '<li class="page-item"><a class="facetwp-page page-link" title="'.$title.'" data-page="'.$page.'"><span class="fas fa-angle-'.$icon.'" aria-hidden="true"></span></a>';

        $this->assertSame(
            '<nav aria-label="list pagination"><ul class="pagination">'
            .'<li class="page-item"><a class="facetwp-page page-link first-page" title="First Page" data-page="1"><span class="fas fa-fast-backward" aria-hidden="true"></span></a></li>'
            .'<li class="page-item"><a class="facetwp-page page-link" title="Previous Page" data-page="14"><span class="fas fa-angle-left" aria-hidden="true"></span></a></li>'
            .$this->pageLink(5).$this->pageLink(13).$this->pageLink(14).$this->pageLink(15, 'active').$this->pageLink(16).$this->pageLink(17).$this->pageLink(25)
            .$arrow('Next Page', 16, 'right')
            .'<li class="page-item"><a class="facetwp-page page-link last-page" title="Last Page" data-page="40"><span class="fas fa-fast-forward aria-hidden="true"></span></a></li>'
            .'</ul></nav>',
            $this->pager(15, 40)
        );
    }

    public function test_the_last_links_of_a_short_list_include_the_final_page_exactly(): void
    {
        // Three pages from the end: the page two ahead is the final page and is still linked.
        $this->assertSame([2, 1, 2, 3, 4, 5, 4], $this->pages($this->pager(3, 5)));
        $this->assertSame([1, 1, 2, 3, 3], $this->pages($this->pager(2, 3)));
    }
}
