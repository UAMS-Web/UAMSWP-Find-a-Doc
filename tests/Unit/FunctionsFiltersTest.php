<?php

/**
 * Pins the second part of includes/class.fad-functions.php: the MedlinePlus feed, the provider, title and
 * location filter shortcodes and their AJAX callbacks, the region cookie script, the scheduling widget, the
 * Ajax Search Pro script removal, the attribute converter and the location source resolver.
 *
 * The callbacks that find posts include a card template from the plugin; those branches need the template
 * and are not run here, so each callback is tested down to the empty result it prints when nothing matches.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/acf-functions.php';
require_once __DIR__.'/../Support/functions-file.php';
require_once __DIR__.'/../Support/wp-classes.php';

final class FunctionsFiltersTest extends UnitTestCase
{
    /** @var list<array{0: string, 1: string, 2: mixed, 3: int, 4: int}> */
    private static array $hooks = [];

    /** @var list<array<string, mixed>> */
    private array $termsArgs = [];

    protected function setUp(): void
    {
        parent::setUp();

        // See AcfBlocksTest: the ACF functions file loads first, under Brain Monkey's own hook functions.
        \uamswp_test_acf_functions();

        self::$hooks = \uamswp_test_functions_file()['hooks'];

        \WP_Query::$queue = [];
        \WP_Query::$queries = [];
        \WP_Query::$current = 0;

        when('esc_attr')->returnArg();
        when('esc_html')->returnArg();
        when('esc_url')->returnArg();
        when('wp_kses_post')->returnArg();
        when('wp_unslash')->returnArg();
        when('sanitize_text_field')->returnArg();
        when('absint')->alias(static fn (mixed $value): int => abs((int) (is_scalar($value) ? $value : 0)));
        when('is_wp_error')->alias(static fn (mixed $thing): bool => $thing instanceof \WP_Error);
        when('wp_json_encode')->alias(static fn (mixed $value): string|false => json_encode($value));
        when('shortcode_atts')->alias(static fn (array $defaults, array $atts): array => array_merge($defaults, $atts));
        when('get_the_ID')->alias(static fn (): int => \WP_Query::$current);
    }

    protected function tearDown(): void
    {
        $_POST = [];
        $_GET = [];
        $_COOKIE = [];

        parent::tearDown();
    }

    // Registrations.

    /**
     * @return array<string, array{0: string, 1: string, 2: string, 3: int}>
     */
    public static function registrations(): array
    {
        return [
            'load more' => ['action', 'wp_ajax_load_posts_by_ajax', 'uamswp_load_by_ajax_callback', 10],
            'load more for visitors' => ['action', 'wp_ajax_nopriv_load_posts_by_ajax', 'uamswp_load_by_ajax_callback', 10],
            'provider filter shortcode' => ['shortcode', 'uamswp_provider_ajax_filter', 'uamswp_provider_ajax_filter_shortcode', 10],
            'provider filter' => ['action', 'wp_ajax_provider_ajax_filter', 'provider_ajax_filter_callback', 10],
            'provider filter for visitors' => ['action', 'wp_ajax_nopriv_provider_ajax_filter', 'provider_ajax_filter_callback', 10],
            'title filter shortcode' => ['shortcode', 'uamswp_provider_title_ajax_filter', 'uamswp_provider_title_ajax_filter_shortcode', 10],
            'title filter' => ['action', 'wp_ajax_provider_title_ajax_filter', 'provider_title_ajax_filter_callback', 10],
            'title filter for visitors' => ['action', 'wp_ajax_nopriv_provider_title_ajax_filter', 'provider_title_ajax_filter_callback', 10],
            'location filter shortcode' => ['shortcode', 'uamswp_location_ajax_filter', 'uamswp_location_ajax_filter_shortcode', 10],
            'location filter' => ['action', 'wp_ajax_location_ajax_filter', 'location_ajax_filter_callback', 10],
            'location filter for visitors' => ['action', 'wp_ajax_nopriv_location_ajax_filter', 'location_ajax_filter_callback', 10],
            'schedule filter' => ['action', 'wp_ajax_schedule_ajax_filter', 'schedule_ajax_filter_callback', 10],
            'schedule filter for visitors' => ['action', 'wp_ajax_nopriv_schedule_ajax_filter', 'schedule_ajax_filter_callback', 10],
            'region cookie script' => ['action', 'wp_footer', 'uamswp_add_trench', 10],
            'script removal' => ['action', 'wp_enqueue_scripts', 'uamswp_fad_disable_scripts', 100],
        ];
    }

    #[DataProvider('registrations')]
    public function test_the_hook_is_registered_with_its_callback(string $kind, string $name, string $callback, int $priority): void
    {
        $found = array_values(array_filter(
            self::$hooks,
            static fn (array $hook): bool => $hook[0] === $kind && $hook[1] === $name && $hook[2] === $callback
        ));

        $this->assertCount(1, $found);
        $this->assertSame($priority, $found[0][3]);
    }

    // The load-more callback.

    public function test_the_load_more_callback_queries_the_published_providers_by_id(): void
    {
        $_POST = ['page' => '1', 'postid' => '4,5'];
        expect('wp_die')->once();

        \uamswp_load_by_ajax_callback();

        $this->assertSame([[
            'post_type' => 'provider',
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'post__in' => ['4', '5'],
        ]], \WP_Query::$queries);
    }

    public function test_the_load_more_callback_prints_a_card_for_each_provider_found(): void
    {
        $_POST = ['page' => '1', 'postid' => '4,5'];
        \WP_Query::$queue = [[4, 5]];
        expect('header')->once()->with('Content-Type: text/html');
        when('get_field')->alias(static fn (string $field, int $id): mixed => $field === 'physician_first_name' ? 'First'.$id : ($field === 'physician_last_name' ? 'Last' : ''));
        when('has_post_thumbnail')->justReturn(false);
        when('the_permalink')->alias(static function (int $id): void {
            echo 'https://example.test/provider-'.$id;
        });
        expect('wp_die')->once();

        ob_start();
        \uamswp_load_by_ajax_callback();
        $html = (string) ob_get_clean();

        $this->assertSame(2, substr_count($html, '<div class="card">'));
        $this->assertStringContainsString('<span class="name">First4 Last</span>', $html);
        $this->assertStringContainsString('<span class="name">First5 Last</span>', $html);
        $this->assertStringContainsString('href="https://example.test/provider-5"', $html);
    }

    public function test_the_load_more_callback_without_ids_asks_for_an_empty_id(): void
    {
        $_POST = ['page' => '1'];
        expect('wp_die')->once();

        \uamswp_load_by_ajax_callback();

        $this->assertSame([''], \WP_Query::$queries[0]['post__in']);
    }

    // The MedlinePlus feed.

    /**
     * @param  array<string, mixed>  $transients
     * @return \ArrayObject<int, string>
     */
    private function medline(array $transients, string $body = ''): \ArrayObject
    {
        $urls = new \ArrayObject;
        when('get_transient')->alias(static fn (string $key): mixed => $transients[$key] ?? false);
        when('wp_remote_get')->alias(static function (string $url) use ($urls, $body): array {
            $urls[] = $url;

            return ['body' => $body];
        });
        when('wp_remote_retrieve_body')->alias(static fn (array $response): string => $response['body']);

        return $urls;
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function medline_types(): array
    {
        return [
            'icd' => ['icd', 'https://connect.medlineplus.gov/service?mainSearchCriteria.v.cs=2.16.840.1.113883.6.90&knowledgeResponseType=application/javascript&mainSearchCriteria.v.c=A00'],
            'ndc' => ['ndc', 'https://connect.medlineplus.gov/service?mainSearchCriteria.v.cs=2.16.840.1.113883.6.69&knowledgeResponseType=application%2Fjavascript&mainSearchCriteria.v.c=A00'],
            'lonic' => ['lonic', 'https://connect.medlineplus.gov/service?mainSearchCriteria.v.cs=2.16.840.1.113883.6.1&knowledgeResponseType=application%2Fjavascript&mainSearchCriteria.v.c=A00'],
            'an unknown type' => ['other', 'https://connect.medlineplus.gov/service?'],
        ];
    }

    #[DataProvider('medline_types')]
    public function test_the_feed_url_follows_the_code_type(string $type, string $url): void
    {
        $urls = $this->medline([], '{}');
        when('set_transient')->justReturn(true);

        \get_medline_api_data('A00', $type);

        $this->assertSame([$url], $urls->getArrayCopy());
    }

    public function test_the_feed_wrapper_is_stripped_and_the_decoded_feed_is_kept_for_a_day(): void
    {
        $this->medline([], 'None({"feed":{"n":1}});');
        expect('set_transient')->once()->with('medline-api-icd-A00', \Mockery::on(static fn (mixed $json): bool => $json instanceof \stdClass && $json->feed->n === 1), 86400);

        $json = \get_medline_api_data('A00', 'icd');

        $this->assertEquals((object) ['feed' => (object) ['n' => 1]], $json);
    }

    public function test_a_feed_that_is_not_json_is_kept_as_null(): void
    {
        $this->medline([], 'not json');
        expect('set_transient')->once()->with('medline-api-icd-A00', null, 86400);

        $this->assertNull(\get_medline_api_data('A00', 'icd'));
    }

    public function test_a_kept_feed_is_returned_without_a_request(): void
    {
        $urls = $this->medline(['medline-api-icd-A00' => 'kept']);
        expect('set_transient')->never();

        $this->assertSame('kept', \get_medline_api_data('A00', 'icd'));
        $this->assertSame([], $urls->getArrayCopy());
    }

    public function test_an_empty_kept_feed_is_fetched_again(): void
    {
        $urls = $this->medline(['medline-api-icd-A00' => []], '{"a":1}');
        when('set_transient')->justReturn(true);

        $this->assertEquals((object) ['a' => 1], \get_medline_api_data('A00', 'icd'));
        $this->assertCount(1, $urls);
    }

    private function cite(string $href): string
    {
        return '<div class="cite">'
            .'<p><em>Courtesy of <a href="https://medlineplus.gov/">MedlinePlus</a> from the <a href="https://www.nlm.nih.gov/">National Library of Medicine</a>.</em></p>'
            .'<p><strong>Syndicated Content Details:</strong><br />'
            .'Source URL: <a href="'.$href.'">'.$href.'</a><br />'
            .'Source Agency: <a href="https://www.nlm.nih.gov/">National Library of Medicine</a></p>'
            .'</div>';
    }

    private function feed(string $json): void
    {
        $this->medline(['medline-api-icd-A00' => json_decode($json)]);
    }

    public function test_a_single_entry_prints_its_summary_and_the_citation(): void
    {
        $this->feed('{"feed":{"entry":{"title":{"_value":"T"},"summary":{"_value":"The summary"},"link":{"href":"https://medlineplus.gov/a"}}}}');
        $this->expectOutputString('<p>The summary</p>'.$this->cite('https://medlineplus.gov/a'));

        \display_medline_api_data('A00', 'icd');
    }

    public function test_a_single_entry_with_no_summary_or_link_prints_empty_values(): void
    {
        $this->feed('{"feed":{"entry":{"title":{"_value":"T"}}}}');
        $this->expectOutputString('<p></p>'.$this->cite(''));

        \display_medline_api_data('A00', 'icd');
    }

    public function test_several_entries_print_the_medlineplus_ones_with_headings_after_the_first(): void
    {
        $this->feed(json_encode(['feed' => ['entry' => [
            ['title' => ['_value' => 'First'], 'summary' => ['_value' => 'S1'], 'link' => ['href' => 'https://medlineplus.gov/a']],
            ['title' => ['_value' => 'Other'], 'summary' => ['_value' => 'S2'], 'link' => ['href' => 'https://example.org/c']],
            ['title' => ['_value' => 'Third'], 'summary' => ['_value' => 'S3'], 'link' => ['href' => 'https://medlineplus.gov/b']],
        ]]]) ?: '');
        $this->expectOutputString(
            '<p>S1</p>'.$this->cite('https://medlineplus.gov/a')
            .'<h2>Third</h2><p>S3</p>'.$this->cite('https://medlineplus.gov/b')
        );

        \display_medline_api_data('A00', 'icd');
    }

    public function test_entries_with_missing_parts_print_empty_values(): void
    {
        $this->feed(json_encode(['feed' => ['entry' => [
            ['link' => ['href' => 'https://medlineplus.gov/a']],
            ['link' => ['href' => 'https://medlineplus.gov/b']],
            ['summary' => ['_value' => 'No link']],
        ]]]) ?: '');
        $this->expectOutputString(
            '<p></p>'.$this->cite('https://medlineplus.gov/a')
            .'<h2></h2><p></p>'.$this->cite('https://medlineplus.gov/b')
        );

        \display_medline_api_data('A00', 'icd');
    }

    public function test_a_feed_with_no_entry_prints_nothing(): void
    {
        $this->feed('{"feed":{}}');
        $this->expectOutputString('');
        \display_medline_api_data('A00', 'icd');

        $this->feed('{}');
        \display_medline_api_data('A00', 'icd');
    }

    // The provider shortcodes.

    /**
     * Answer the region lookup with the regions, keeping the arguments it was asked with.
     *
     * @param  array<int, object>|\WP_Error  $regions
     */
    private function recordTerms(array|\WP_Error $regions): void
    {
        when('get_terms')->alias(function (array $args) use ($regions): array|\WP_Error {
            $this->termsArgs[] = $args;

            return $regions;
        });
    }

    /**
     * Stubs for a set of providers: published status, a field map keyed `id:field`, the clinical title terms
     * and the regions.
     *
     * @param  array<string, string>  $status
     * @param  array<string, mixed>  $fields
     * @param  array<int, object|null>  $titleTerms
     * @param  array<string, mixed>  $termFields
     * @param  array<int, object>|\WP_Error  $regions
     */
    private function providers(array $status, array $fields, array $titleTerms = [], array $termFields = [], array|\WP_Error $regions = []): void
    {
        when('get_post_status')->alias(static fn (int $id): string => $status[$id] ?? 'draft');
        when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => is_object($id) ? ($termFields[$field] ?? '') : ($fields[$id.':'.$field] ?? ''));
        when('get_term')->alias(static fn (int $id, string $taxonomy): ?object => $titleTerms[$id] ?? null);
        $this->recordTerms($regions);
    }

    /**
     * @return array{0: array<int, string>, 1: array<string, mixed>, 2: array<int, object|null>, 3: array<string, mixed>, 4: list<object>}
     */
    private function sampleProviders(): array
    {
        return [
            [1 => 'publish', 2 => 'publish', 3 => 'draft', 4 => 'publish'],
            [
                '1:physician_resident' => true,
                '1:physician_region' => [10, 11],
                '2:physician_title' => '7',
                '2:physician_region' => [11],
                '3:physician_title' => '8',
                '3:physician_region' => [12],
                '4:physician_title' => '9',
            ],
            [7 => (object) ['name' => 'Cardiology'], 8 => (object) ['name' => 'Hidden'], 9 => null],
            [],
            [
                (object) ['slug' => 'a', 'term_id' => 10, 'name' => 'Region A'],
                (object) ['slug' => 'b', 'term_id' => 11, 'name' => 'Region B'],
                (object) ['slug' => 'c', 'term_id' => 12, 'name' => 'Region C'],
            ],
        ];
    }

    public function test_the_provider_filter_lists_the_clinical_titles_and_regions_of_published_providers(): void
    {
        $this->providers(...$this->sampleProviders());

        $html = \uamswp_provider_ajax_filter_shortcode(['providers' => '1,2,3,4']);

        $this->assertStringContainsString('<option value="7">Cardiology</option>', $html);
        $this->assertStringContainsString('<option value="0">Resident Physician</option>', $html);
        $this->assertLessThan(strpos($html, 'Resident Physician'), strpos($html, 'Cardiology'), 'titles are sorted by name');
        $this->assertStringNotContainsString('Hidden', $html, 'a draft provider adds no title');
        $this->assertStringContainsString('<option value="a">Region A</option>', $html);
        $this->assertStringContainsString('<option value="b">Region B</option>', $html);
        $this->assertStringContainsString('<option value="c" disabled>Region C</option>', $html, 'a region no listed provider serves is disabled');
        $this->assertStringContainsString('value="1,2,3,4"', $html);
        $this->assertStringNotContainsString('provider-ajax-filter-message', $html);
    }

    public function test_providers_with_the_same_title_list_it_once_and_those_without_a_name_list_nothing(): void
    {
        $this->providers(
            [5 => 'publish', 6 => 'publish', 7 => 'publish', 8 => 'publish'],
            ['5:physician_title' => '20', '6:physician_title' => '21', '8:physician_title' => '22'],
            [20 => (object) ['name' => 'Same'], 21 => (object) ['name' => 'Same'], 22 => (object) []]
        );

        $html = \uamswp_provider_ajax_filter_shortcode(['providers' => '5,6,7,8']);

        $this->assertSame(1, substr_count($html, '>Same<'));
        $this->assertStringContainsString('<option value="20">Same</option>', $html);
        $this->assertStringNotContainsString('value="21"', $html);
        $this->assertStringNotContainsString('value="22"', $html);
        $this->assertStringNotContainsString('<option value="0">', $html, 'a provider with no title adds no option');
    }

    public function test_a_cookie_of_zero_selects_no_title_and_the_regions_are_asked_for_by_name(): void
    {
        $this->providers(...$this->sampleProviders());
        $_COOKIE = ['_provider_title' => '0'];

        $html = \uamswp_provider_ajax_filter_shortcode(['providers' => '1,2']);

        $this->assertStringContainsString('<option value="0">Resident Physician</option>', $html);
        $this->assertSame([['taxonomy' => 'region', 'orderby' => 'name', 'hide_empty' => 0]], $this->termsArgs);
    }

    public function test_the_region_menu_can_be_hidden(): void
    {
        $this->providers(...$this->sampleProviders());

        $html = \uamswp_provider_ajax_filter_shortcode(['providers' => '1,2', 'region' => 'hide']);

        $this->assertStringNotContainsString('provider_region', $html);
        $this->assertStringContainsString('id="provider_title"', $html);
    }

    public function test_a_specialization_title_on_the_term_replaces_the_term_name(): void
    {
        [$status, $fields, $terms, , $regions] = $this->sampleProviders();
        $this->providers($status, $fields, $terms, ['clinical_specialization_title' => 'Interventional Cardiologist'], $regions);

        $html = \uamswp_provider_ajax_filter_shortcode(['providers' => '2']);

        $this->assertStringContainsString('<option value="7">Interventional Cardiologist</option>', $html);
        $this->assertStringNotContainsString('>Cardiology<', $html);
    }

    public function test_selections_from_the_cookie_and_the_query_string_are_marked(): void
    {
        $this->providers(...$this->sampleProviders());
        $_COOKIE = ['_provider_title' => '7', 'wp_filter_region' => 'b'];

        $html = \uamswp_provider_ajax_filter_shortcode(['providers' => '1,2']);

        $this->assertStringContainsString('<option value="7" selected>Cardiology</option>', $html);
        $this->assertStringContainsString('<option value="b" selected>Region B</option>', $html);
        $this->assertStringContainsString('id="provider-ajax-filter-message"', $html);

        $_GET = ['_filter_region' => 'a'];
        $html = \uamswp_provider_ajax_filter_shortcode(['providers' => '1,2']);

        $this->assertStringContainsString('<option value="a" selected>Region A</option>', $html, 'the query string wins over the cookie');
        $this->assertStringNotContainsString('<option value="b" selected>', $html);

        $html = \uamswp_provider_ajax_filter_shortcode(['providers' => '1,2', 'region' => 'hide']);
        $this->assertStringNotContainsString('provider-ajax-filter-message', $html, 'a hidden region menu has no message');
    }

    public function test_a_query_string_region_alone_marks_the_region_without_the_message(): void
    {
        $this->providers(...$this->sampleProviders());
        $_GET = ['_filter_region' => 'a'];

        $html = \uamswp_provider_ajax_filter_shortcode(['providers' => '1,2']);

        $this->assertStringContainsString('<option value="a" selected>Region A</option>', $html);
        $this->assertStringNotContainsString('provider-ajax-filter-message', $html);
    }

    public function test_a_disabled_region_that_is_selected_keeps_both_marks(): void
    {
        $this->providers(...$this->sampleProviders());
        $_GET = ['_filter_region' => 'c'];

        $html = \uamswp_provider_ajax_filter_shortcode(['providers' => '1,2']);

        $this->assertStringContainsString('<option value="c" disabled selected>Region C</option>', $html);
    }

    public function test_a_region_lookup_that_fails_leaves_only_the_any_region_choice(): void
    {
        [$status, $fields, $terms] = $this->sampleProviders();
        $this->providers($status, $fields, $terms, [], new \WP_Error('x', 'y'));

        $html = \uamswp_provider_ajax_filter_shortcode(['providers' => '1']);

        $this->assertStringContainsString('<option value="">Any Region</option>', $html);
        $this->assertStringNotContainsString('Region A', $html);
    }

    public function test_the_shortcode_defaults_to_no_providers(): void
    {
        $this->providers([], []);

        $html = \uamswp_provider_ajax_filter_shortcode('');

        $this->assertStringContainsString('<option value="">Any Clinical Title</option>', $html);
        $this->assertStringContainsString('name="providers-ids" value="0"', $html);
    }

    public function test_the_title_filter_lists_only_the_clinical_titles(): void
    {
        $this->providers(...$this->sampleProviders());
        $_COOKIE = ['_provider_title' => '0'];

        $html = \uamswp_provider_title_ajax_filter_shortcode(['providers' => '1,2,3']);

        $this->assertStringContainsString('<option value="7">Cardiology</option>', $html);
        $this->assertStringContainsString('<option value="0">Resident Physician</option>', $html, 'an empty cookie value selects nothing');
        $this->assertStringNotContainsString('Hidden', $html);
        $this->assertStringContainsString('id="provider-title-ajax-filter"', $html);
        $this->assertStringContainsString('value="1,2,3"', $html);
        $this->assertStringNotContainsString('provider_region', $html);

        $_COOKIE = ['_provider_title' => '7'];
        $this->assertStringContainsString('<option value="7" selected>Cardiology</option>', \uamswp_provider_title_ajax_filter_shortcode(['providers' => '2']));
        $this->assertStringContainsString('name="providers-ids" value="0"', \uamswp_provider_title_ajax_filter_shortcode(null));
    }

    public function test_the_title_filter_lists_equal_titles_once_in_order_and_skips_providers_without_a_name(): void
    {
        $this->providers(
            [5 => 'publish', 6 => 'publish', 7 => 'publish', 8 => 'publish', 9 => 'publish', 10 => 'publish'],
            ['5:physician_title' => '20', '6:physician_title' => '21', '8:physician_title' => '22', '9:physician_title' => '23', '10:physician_title' => '24'],
            [20 => (object) ['name' => 'Same'], 21 => (object) ['name' => 'Same'], 22 => (object) [], 23 => (object) ['name' => 'Zeta'], 24 => (object) ['name' => 'Alpha']]
        );

        $html = \uamswp_provider_title_ajax_filter_shortcode(['providers' => '5,6,7,8,9,10']);

        $this->assertSame(1, substr_count($html, '>Same<'));
        $this->assertStringNotContainsString('value="21"', $html);
        $this->assertStringNotContainsString('value="22"', $html);
        $this->assertStringNotContainsString('<option value="0">', $html);
        $this->assertLessThan(strpos($html, '>Same<'), strpos($html, '>Alpha<'), 'titles are sorted by name');
        $this->assertLessThan(strpos($html, '>Zeta<'), strpos($html, '>Same<'));
    }

    public function test_the_title_filter_uses_the_term_specialization_title_when_there_is_one(): void
    {
        [$status, $fields, $terms] = $this->sampleProviders();
        $this->providers($status, $fields, $terms, ['clinical_specialization_title' => 'Heart Doctor']);

        $this->assertStringContainsString('<option value="7">Heart Doctor</option>', \uamswp_provider_title_ajax_filter_shortcode(['providers' => '2']));
    }

    // The provider callbacks.

    /**
     * @param  array<int, mixed>  $fields
     * @param  array<int, object>  $terms
     */
    private function callbackStubs(array $fields, array $terms = [], bool $die = true): void
    {
        when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => $fields[$field.':'.$id] ?? '');
        when('get_term_by')->alias(static fn (string $by, mixed $id, string $taxonomy): object|false => $terms[$id] ?? false);
        if ($die) {
            when('wp_die')->justReturn();
        }
    }

    public function test_the_provider_callback_builds_each_query_from_the_title_and_region(): void
    {
        $_POST = ['provider_title' => '7', 'provider_region' => 'north', 'providers' => '1,2'];
        \WP_Query::$queue = [[1, 2], [1], []];
        $this->callbackStubs(
            ['physician_region:1' => [10], 'physician_region:2' => [10, 11], 'physician_title:1' => 7],
            [10 => (object) ['slug' => 'north'], 11 => (object) ['slug' => '']]
        );
        $this->expectOutputString(
            '<span class="no-results">Sorry, there are no providers matching your filter criteria. Please adjust your filter options or reset the filters.</span>'
            .'<data id="provider_ids" data-postids="" data-regions="north," data-titles="7,"></data>'
        );

        \provider_ajax_filter_callback();

        $title = ['taxonomy' => 'clinical_title', 'field' => 'term_id', 'terms' => '7'];
        $region = ['taxonomy' => 'region', 'field' => 'slug', 'terms' => 'north'];
        $base = ['post_type' => 'provider', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1, 'fields' => 'ids', 'post__in' => ['1', '2']];
        $this->assertSame([
            $base + ['tax_query' => [$title]],
            $base + ['tax_query' => [$region]],
            $base + ['tax_query' => ['relation' => 'AND', $title, $region]],
        ], \WP_Query::$queries);
    }

    public function test_the_provider_callback_prefers_the_cookies_to_the_posted_values(): void
    {
        $_POST = ['provider_title' => '7', 'provider_region' => 'north', 'providers' => '1'];
        $_COOKIE = ['_provider_title' => '9', 'wp_filter_region' => 'south'];
        $this->callbackStubs([]);
        $this->expectOutputRegex('/no-results/');

        \provider_ajax_filter_callback();

        $this->assertSame(
            ['relation' => 'AND', ['taxonomy' => 'clinical_title', 'field' => 'term_id', 'terms' => '9'], ['taxonomy' => 'region', 'field' => 'slug', 'terms' => 'south']],
            \WP_Query::$queries[2]['tax_query']
        );
    }

    public function test_the_provider_callback_with_nothing_posted_prints_the_empty_result(): void
    {
        $this->callbackStubs([], [], false);
        expect('wp_die')->once();
        $this->expectOutputString(
            '<span class="no-results">Sorry, there are no providers matching your filter criteria. Please adjust your filter options or reset the filters.</span>'
            .'<data id="provider_ids" data-postids="" data-regions="," data-titles=","></data>'
        );

        \provider_ajax_filter_callback();

        $this->assertSame([[], [], ['relation' => 'AND']], array_column(\WP_Query::$queries, 'tax_query'));
        $this->assertSame([[], [], []], array_column(\WP_Query::$queries, 'post__in'));
    }

    public function test_the_provider_callback_lists_each_distinct_title_once(): void
    {
        $_POST = ['providers' => '1,2,3'];
        \WP_Query::$queue = [[], [1, 2, 3], []];
        $this->callbackStubs(['physician_title:1' => 7, 'physician_title:2' => '7', 'physician_title:3' => 9]);
        $this->expectOutputRegex('/data-titles="7,9,"/');

        \provider_ajax_filter_callback();
    }

    public function test_the_title_callback_builds_its_query_from_the_title(): void
    {
        $_POST = ['provider_title' => '7', 'providers' => '1,2'];
        expect('wp_die')->once();
        $this->expectOutputString('<span class="no-results">Sorry, there are no providers matching your filter criteria. Please adjust your filter options or reset the filters.</span>');

        \provider_title_ajax_filter_callback();

        $this->assertSame([[
            'post_type' => 'provider',
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'post__in' => ['1', '2'],
            'tax_query' => ['relation' => 'AND', ['taxonomy' => 'clinical_title', 'field' => 'term_id', 'terms' => '7']],
        ]], \WP_Query::$queries);
    }

    public function test_the_title_callback_with_nothing_posted_filters_nothing(): void
    {
        when('wp_die')->justReturn();
        $this->expectOutputRegex('/no-results/');

        \provider_title_ajax_filter_callback();

        $this->assertSame(['relation' => 'AND'], \WP_Query::$queries[0]['tax_query']);
        $this->assertSame([], \WP_Query::$queries[0]['post__in']);
    }

    // The location shortcode and callback.

    /**
     * @param  array<string, mixed>  $fields
     * @param  array<int, string>  $status
     * @param  array<int, object>|\WP_Error  $regions
     */
    private function locations(array $status, array $fields, array|\WP_Error $regions): void
    {
        when('get_post_status')->alias(static fn (int $id): string => $status[$id] ?? 'draft');
        when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => $fields[$id.':'.$field] ?? '');
        $this->recordTerms($regions);
    }

    /**
     * @return array<int, object>
     */
    private function regionTerms(): array
    {
        return [
            (object) ['slug' => 'a', 'term_id' => 10, 'name' => 'Region A'],
            (object) ['slug' => 'b', 'term_id' => 11, 'name' => 'Region B'],
            (object) ['slug' => 'c', 'term_id' => 12, 'name' => 'Region C'],
        ];
    }

    public function test_the_location_filter_enables_the_regions_of_published_locations(): void
    {
        $this->locations(
            [1 => 'publish', 2 => 'publish', 3 => 'publish', 4 => 'publish', 5 => 'publish', 6 => 'draft'],
            ['1:location_region' => [10], '2:location_region' => 11, '3:location_region' => false, '4:location_region' => '', '5:location_region' => null, '6:location_region' => [12]],
            $this->regionTerms()
        );

        $html = \uamswp_location_ajax_filter_shortcode(['locations' => '1,2,3,4,5,6']);

        $this->assertStringContainsString('<option value="a">Region A</option>', $html);
        $this->assertStringContainsString('<option value="b">Region B</option>', $html, 'a single region value counts');
        $this->assertStringContainsString('<option value="c" disabled>Region C</option>', $html);
        $this->assertStringContainsString('id="locations" name="locations" value="1,2,3,4,5,6"', $html);
        $this->assertStringContainsString('id="location-ajax-filter"', $html);
        $this->assertStringNotContainsString('d-none', $html);
        $this->assertStringNotContainsString('location-ajax-filter-message', $html);
    }

    public function test_a_region_given_as_text_enables_its_region_and_the_regions_are_asked_for_by_name(): void
    {
        $this->locations([1 => 'publish'], ['1:location_region' => ['12']], $this->regionTerms());

        $html = \uamswp_location_ajax_filter_shortcode(['locations' => '1']);

        $this->assertStringContainsString('<option value="c">Region C</option>', $html);
        $this->assertStringContainsString('<option value="a" disabled>Region A</option>', $html);
        $this->assertStringContainsString('mb-sm-0">', $html);
        $this->assertSame([['taxonomy' => 'region', 'orderby' => 'name', 'hide_empty' => 0]], $this->termsArgs);
    }

    public function test_the_location_region_menu_can_be_hidden_and_the_selection_is_marked(): void
    {
        $this->locations([1 => 'publish'], ['1:location_region' => [10, 11]], $this->regionTerms());
        $_COOKIE = ['wp_filter_region' => 'b'];

        $html = \uamswp_location_ajax_filter_shortcode(['locations' => '1', 'region' => 'hide']);

        $this->assertStringContainsString('mb-sm-0 d-none"', $html);
        $this->assertStringContainsString('<option value="b" selected>Region B</option>', $html);
        $this->assertStringContainsString('id="location-ajax-filter-message"', $html);

        $_GET = ['_filter_region' => 'a'];
        $html = \uamswp_location_ajax_filter_shortcode(['locations' => '1']);

        $this->assertStringContainsString('<option value="a" selected>Region A</option>', $html);
        $this->assertStringNotContainsString('d-none', $html);
    }

    public function test_a_failed_location_region_lookup_and_no_locations_print_the_bare_menu(): void
    {
        $this->locations([], [], new \WP_Error('x', 'y'));

        $html = \uamswp_location_ajax_filter_shortcode(null);

        $this->assertStringContainsString('<option value="">Any Region</option>', $html);
        $this->assertStringNotContainsString('Region A', $html);
        $this->assertStringContainsString('name="locations" value="0"', $html);
    }

    public function test_the_location_callback_builds_each_query_from_the_region(): void
    {
        $_POST = ['location_region' => 'north', 'locations' => '1,2'];
        \WP_Query::$queue = [[1, 2], []];
        when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => $id === 1 ? 10 : 11);
        when('get_term_by')->alias(static fn (string $by, mixed $id, string $taxonomy): object|false => (string) $id === '10' ? (object) ['slug' => 'north'] : false);

        expect('wp_die')->once();
        $this->expectOutputString('<span class="no-results">Sorry, there are no locations matching your filter criteria. Please adjust your filter options or reset the filters.</span>');

        \location_ajax_filter_callback();

        $base = ['post_type' => 'location', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1, 'fields' => 'ids', 'post__in' => ['1', '2']];
        $this->assertSame([
            $base,
            $base + ['tax_query' => [['taxonomy' => 'region', 'field' => 'slug', 'terms' => 'north']]],
        ], \WP_Query::$queries);
    }

    public function test_the_location_callback_prefers_the_cookie_and_with_nothing_posted_filters_nothing(): void
    {
        when('wp_die')->justReturn();
        $this->expectOutputRegex('/no-results/');
        $_COOKIE = ['wp_filter_region' => 'south'];

        \location_ajax_filter_callback();

        $this->assertSame([['taxonomy' => 'region', 'field' => 'slug', 'terms' => 'south']], \WP_Query::$queries[1]['tax_query']);
        $this->assertSame([], \WP_Query::$queries[1]['post__in']);

        $_COOKIE = [];
        \WP_Query::$queries = [];
        \location_ajax_filter_callback();

        $this->assertSame([], \WP_Query::$queries[1]['tax_query']);
    }

    // Rendering the real cards.

    private function cardStubs(): void
    {
        require_once UAMS_FAD_PATH.'includes/class.fad-location-address.php';
        when('get_field_object')->justReturn();
        when('format_phone_dash')->justReturn('');
        when('uamswp_custom_table_query')->justReturn([]);
        when('get_post')->alias(static fn (mixed $id): object => (object) ['ID' => (int) $id, 'post_title' => 'Title '.$id]);
        when('get_term')->justReturn(false);
        when('sanitize_title_with_dashes')->returnArg();
        when('esc_html__')->returnArg();
        when('__')->returnArg();
        when('wp_reset_postdata')->justReturn();
        when('get_the_title')->alias(static fn (mixed $id = 0): string => 'Title '.$id);
        when('get_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/'.$id);
        when('has_post_thumbnail')->justReturn(false);
        when('the_permalink')->alias(static function (mixed $id = 0): void {
            echo 'https://example.test/'.$id;
        });
        when('wp_die')->justReturn();
    }

    public function test_the_location_callback_prints_a_card_and_the_ids_and_region_slugs_for_the_locations_found(): void
    {
        $_POST = ['locations' => '1,2,3'];
        \WP_Query::$queue = [[1, 2, 3], [1, 2]];
        $this->cardStubs();
        $asked = [];
        when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => match ($field) {
            'location_region' => $id === 3 ? [10, 11] : 10,
            'location_hours_group' => ['location_modified_hours' => false, 'location_modified_hours_start_date' => '', 'location_modified_hours_end' => false, 'location_modified_hours_end_date' => '', 'location_telemed_modified_hours_query' => false, 'location_telemed_modified_hours_start_date' => '', 'location_telemed_modified_hours_end' => false, 'location_telemed_modified_hours_end_date' => ''],
            default => null,
        });
        when('get_term_by')->alias(static function (string $by, mixed $id, string $taxonomy) use (&$asked): object|false {
            $asked[] = $id;

            return $id === '10' ? (object) ['slug' => 'north'] : false;
        });

        ob_start();
        \location_ajax_filter_callback();
        $html = (string) ob_get_clean();

        $this->assertSame(['10', ''], $asked, 'the regions of the locations are looked up once each, as text');
        $this->assertSame(2, substr_count($html, '<div class="card">'));
        $this->assertStringContainsString('href="https://example.test/1"', $html);
        $this->assertStringContainsString('href="https://example.test/2"', $html);
        $this->assertStringEndsWith('<data id="location_ids" data-postids="1,2," data-regions="north,,"></data>', $html);
        $this->assertStringNotContainsString('no-results', $html);
    }

    public function test_the_location_callback_shows_no_results_when_no_locations_were_posted_even_if_the_query_finds_some(): void
    {
        \WP_Query::$queue = [[], [7]];
        $this->cardStubs();
        $this->expectOutputRegex('/^<span class="no-results">Sorry, there are no locations/');

        \location_ajax_filter_callback();
    }

    public function test_the_provider_callback_looks_up_the_regions_by_number_and_shows_no_results_without_posted_providers(): void
    {
        \WP_Query::$queue = [[1], [], [7]];
        $this->cardStubs();
        $asked = [];
        when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => $field === 'physician_region' ? ['10', '11'] : null);
        when('get_term_by')->alias(static function (string $by, mixed $id, string $taxonomy) use (&$asked): object|false {
            $asked[] = $id;

            return (object) ['slug' => 'r'.$id];
        });

        ob_start();
        \provider_ajax_filter_callback();
        $html = (string) ob_get_clean();

        $this->assertSame([10, 11], $asked);
        $this->assertStringStartsWith('<span class="no-results">Sorry, there are no providers', $html);
        $this->assertStringEndsWith('<data id="provider_ids" data-postids="" data-regions="r10,r11," data-titles=","></data>', $html);
    }

    public function test_the_title_callback_shows_no_results_without_posted_providers_even_if_the_query_finds_some(): void
    {
        \WP_Query::$queue = [[7]];
        $this->cardStubs();
        $this->expectOutputRegex('/^<span class="no-results">Sorry, there are no providers/');

        \provider_title_ajax_filter_callback();
    }

    public function test_the_provider_callback_prints_the_cards_with_the_ids_regions_and_titles(): void
    {
        $_POST = ['providers' => '1,2'];
        \WP_Query::$queue = [[1, 2], [1, 2], [1, 2]];
        $this->cardStubs();
        when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => match ($field) {
            'physician_region' => [10],
            'physician_title' => 7,
            default => null,
        });
        when('get_term_by')->alias(static fn (string $by, mixed $id, string $taxonomy): object|false => (object) ['slug' => 'north']);

        ob_start();
        \provider_ajax_filter_callback();
        $html = (string) ob_get_clean();

        $this->assertSame(2, substr_count($html, '<div class="card">'));
        $this->assertStringEndsWith('<data id="provider_ids" data-postids="1,2," data-regions="north," data-titles="7,"></data>', $html);
    }

    // The region cookie script.

    public function test_a_page_with_a_region_term_sets_the_region_cookie(): void
    {
        $term = new \WP_Term;
        $term->slug = 'north';

        when('is_page')->justReturn(true);
        when('get_field')->justReturn($term);
        $this->expectOutputRegex('#<script type="text/javascript">\s*// Set cookie to expire at end of session\s*document\.cookie = "wp_filter_region=north; path=/; domain="\+window\.location\.hostname;\s*</script>#');

        \uamswp_add_trench();
    }

    public function test_the_query_string_region_is_escaped_into_the_cookie_script(): void
    {
        when('is_page')->justReturn(true);
        when('get_field')->justReturn(false);

        $_GET = ['_filter_region' => 'a"b'];
        $this->expectOutputRegex('/wp_filter_region=a&quot;b; path=\//');

        \uamswp_add_trench();
    }

    public function test_a_region_that_is_not_a_term_falls_back_to_the_query_string(): void
    {
        when('is_page')->justReturn(true);
        when('get_field')->justReturn(5);

        $_GET = ['_filter_region' => 'east'];
        $this->expectOutputRegex('/wp_filter_region=east; path=\//');

        \uamswp_add_trench();
    }

    public function test_a_region_that_is_not_a_term_and_no_query_string_sets_an_empty_cookie(): void
    {
        when('is_page')->justReturn(true);
        when('get_field')->justReturn(5);
        $this->expectOutputRegex('/wp_filter_region=; path=\//');

        \uamswp_add_trench();
    }

    public function test_nothing_is_printed_without_a_region_or_off_a_page(): void
    {
        when('is_page')->justReturn(true);
        when('get_field')->justReturn(false);
        $this->expectOutputString('');

        \uamswp_add_trench();

        $_GET = ['_filter_region' => 'east'];
        when('is_page')->justReturn(false);
        \uamswp_add_trench();
    }

    // The scheduling widget.

    /**
     * @param  array<string, mixed>  $fields
     */
    private function scheduleStubs(array $fields, string $type = 'location', string $status = 'publish', bool $referer = true): void
    {
        if ($referer) {
            when('check_ajax_referer')->justReturn(1);
        }

        when('get_post_type')->justReturn($type);
        when('get_post_status')->justReturn($status);
        when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => $fields[$id.':'.$field] ?? '');
        when('wp_send_json_error')->alias(static function (string $message, int $status): never {
            throw new \RuntimeException($message.'|'.$status);
        });
    }

    private function assertScheduleError(): void
    {
        try {
            \schedule_ajax_filter_callback();
            $this->fail('the callback answered');
        } catch (\RuntimeException $runtimeException) {
            $this->assertSame('not found|404', $runtimeException->getMessage());
        }
    }

    public function test_the_schedule_callback_checks_its_nonce(): void
    {
        $_POST = ['pid' => '12', 'schedule_options' => '0'];
        $this->scheduleStubs(['12:location_scheduling_options' => [['location_scheduling_ser' => 'S']]], 'location', 'publish', false);
        expect('check_ajax_referer')->once()->with('load_more_posts', 'security')->andReturn(1);
        when('wp_die')->justReturn();
        $this->expectOutputRegex('/EmbeddedSchedule/');

        \schedule_ajax_filter_callback();
    }

    /**
     * @return array<string, array{0: array<string, mixed>, 1: string, 2: string, 3: array<string, mixed>}>
     */
    public static function missing_schedules(): array
    {
        $options = ['12:location_scheduling_options' => [0 => ['location_scheduling_ser' => 'S']]];

        return [
            'no pid' => [['schedule_options' => '0'], 'location', 'publish', $options],
            'a pid of zero' => [['pid' => '0'], 'location', 'publish', $options],
            'no pid, with options on the numbers beside zero' => [['schedule_options' => '0'], 'location', 'publish', ['1:location_scheduling_options' => [0 => ['location_scheduling_ser' => 'S']], '-1:location_scheduling_options' => [0 => ['location_scheduling_ser' => 'S']]]],
            'a post that is not a location' => [['pid' => '12'], 'page', 'publish', $options],
            'an unpublished location' => [['pid' => '12'], 'location', 'draft', $options],
            'no scheduling options' => [['pid' => '12'], 'location', 'publish', []],
            'options that are not a list' => [['pid' => '12'], 'location', 'publish', ['12:location_scheduling_options' => 'x']],
            'an option that is missing' => [['pid' => '12', 'schedule_options' => '3'], 'location', 'publish', $options],
            'an option that is not a row' => [['pid' => '12'], 'location', 'publish', ['12:location_scheduling_options' => [0 => 'x']]],
        ];
    }

    /**
     * @param  array<string, mixed>  $post
     * @param  array<string, mixed>  $fields
     */
    #[DataProvider('missing_schedules')]
    public function test_a_request_with_nothing_to_show_is_a_404(array $post, string $type, string $status, array $fields): void
    {
        $_POST = $post;
        $this->scheduleStubs($fields, $type, $status);
        expect('wp_die')->never();

        $this->assertScheduleError();
    }

    public function test_the_widget_is_built_from_the_row_and_the_site_settings(): void
    {
        $_POST = ['pid' => '12', 'schedule_options' => '2'];
        $this->scheduleStubs([
            '12:location_scheduling_options' => [2 => [
                'location_scheduling_ser' => 'S 1',
                'location_scheduling_dep' => 'D&1',
                'location_scheduling_vt' => 'V',
                'location_scheduling_item_title_nested' => 'Book online',
                'location_scheduling_item_intro_nested' => '<b>Intro</b>',
                'location_scheduling_fallback' => '<i>Call us</i>',
            ]],
            'option:mychart_scheduling_domain' => 'mychart.example.org',
            'option:mychart_scheduling_instance' => 'MyChart',
            'option:mychart_scheduling_linksource' => 'partner',
        ]);
        expect('wp_die')->once();
        ob_start();

        \schedule_ajax_filter_callback();

        $html = (string) ob_get_clean();
        $this->assertStringContainsString('<h3 class="sr-only module-inner-title">Book online</h3>', $html);
        $this->assertMatchesRegularExpression('#<p class="note">\s*<b>Intro</b>\s*</p>#', $html);
        $this->assertStringContainsString('src="https://mychart.example.org/MyChart/SignupAndSchedule/EmbeddedSchedule?id=S%201&dept=D%261&vt=V&linksource=partner"', $html);
        $this->assertStringContainsString('<script src="https://mychart.example.org/MyChart/Content/EmbeddedWidgetController.js" type="text/javascript"></script>', $html);
        $this->assertStringContainsString("'hostname': \"https:\\/\\/mychart.example.org\",", $html);
        $this->assertMatchesRegularExpression('#<div class="more">\s*<i>Call us</i>\s*</div>#', $html);
    }

    public function test_a_bare_row_uses_the_default_title_and_link_source_and_prints_no_intro_or_fallback(): void
    {
        $_POST = ['pid' => '12'];
        $this->scheduleStubs([
            '12:location_scheduling_options' => [0 => ['location_scheduling_item_title_nested' => '']],
            'option:mychart_scheduling_domain' => 'mychart.example.org',
            'option:mychart_scheduling_instance' => 'MyChart',
        ]);
        when('wp_die')->justReturn();
        ob_start();

        \schedule_ajax_filter_callback();

        $html = (string) ob_get_clean();
        $this->assertStringContainsString('Schedule an Appointment Online</h3>', $html);
        $this->assertStringContainsString('EmbeddedSchedule?id=&dept=&vt=&linksource=uamshealth.com"', $html);
        $this->assertStringNotContainsString('class="note"', $html);
        $this->assertStringNotContainsString('class="more"', $html);
    }

    // Ajax Search Pro.

    /**
     * @return array<string, array{0: list<string>, 1: bool}>
     */
    public static function archives(): array
    {
        return [
            'the location archive' => [['location'], false],
            'the provider archive' => [['provider'], false],
            'the clinical resource archive' => [['clinical-resource'], false],
            'another archive' => [['condition'], true],
            'no archive' => [[], true],
        ];
    }

    /**
     * @param  list<string>  $archives
     */
    #[DataProvider('archives')]
    public function test_the_search_scripts_are_removed_everywhere_but_the_search_archives(array $archives, bool $removed): void
    {
        when('is_post_type_archive')->alias(static fn (string $type): bool => in_array($type, $archives, true));
        $dequeued = [];
        when('wp_dequeue_script')->alias(static function (string $handle) use (&$dequeued): void {
            $dequeued[] = $handle;
        });

        \uamswp_fad_disable_scripts();

        $this->assertSame($removed ? ['wd-asp-async-loader', 'wd-asp-ajaxsearchpro'] : [], $dequeued);
    }

    // The attribute converter.

    /**
     * @return array<string, array{0: mixed, 1: string}>
     */
    public static function attributes(): array
    {
        return [
            'null' => [null, ''],
            'an array' => [['a'], ''],
            'empty' => ['', ''],
            'zero' => [0, ''],
            'a zero string' => ['0', ''],
            'plain text' => ['Plain text', 'Plain text'],
            'a non-breaking space entity' => ['a&nbsp;b', 'a b'],
            'curly double quote entities' => ['&#8220;Hi&#8221;', "'Hi'"],
            'curly single quote entities' => ['&#8216;s&#8217;', "'s'"],
            'straight double quotes' => ['say "hi"', "say 'hi'"],
            'an ampersand' => ['Tom & Jerry', 'Tom & Jerry'],
            'an existing entity' => ['&amp;', '&amp;'],
            'a markup character' => ['<b>', '<b>'],
            'an accented letter' => ['caf'."\u{00E9}", 'caf'."\u{00E9}"],
            'a number' => [12, '12'],
            'a non-breaking space character' => ["a\u{00A0}b", 'a b'],
        ];
    }

    #[DataProvider('attributes')]
    public function test_text_is_converted_for_use_in_an_html_attribute(mixed $input, string $expected): void
    {
        $this->assertSame($expected, \uamswp_attr_conversion($input));
    }

    // The location source resolver.

    private function location(int|string $id): \WP_Post
    {
        $post = new \WP_Post;
        $post->ID = $id;

        return $post;
    }

    /**
     * @param  array<string, mixed>  $fields
     * @param  array<int, \WP_Post|null>  $posts
     */
    private function sourceStubs(array $fields, array $posts = [], int|string $current = 0): void
    {
        when('get_the_ID')->justReturn($current);
        when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => $fields[$id.':'.$field] ?? false);
        when('get_post')->alias(static fn (int $id): ?\WP_Post => $posts[$id] ?? null);
    }

    public function test_no_location_means_zero_everywhere(): void
    {
        $this->sourceStubs([]);
        expect('get_field')->never();

        $this->assertSame(
            ['self' => 0, 'parent' => 0, 'street' => 0, 'facility' => 0, 'unit' => 0, 'map' => 0, 'parking' => 0, 'directions' => 0],
            \uamswp_fad_location_source_ids()
        );
    }

    public function test_the_current_post_is_used_when_none_is_given(): void
    {
        $this->sourceStubs([], [], 9101);

        $this->assertSame(
            ['self' => 9101, 'parent' => 0, 'street' => 9101, 'facility' => 9101, 'unit' => 9101, 'map' => 9101, 'parking' => 9101, 'directions' => 9101],
            \uamswp_fad_location_source_ids()
        );
    }

    public function test_the_current_post_id_may_arrive_as_text(): void
    {
        $this->sourceStubs([], [], '9161');

        $this->assertSame(9161, \uamswp_fad_location_source_ids()['self']);
    }

    public function test_a_parent_whose_id_arrives_as_text_is_read_as_a_number(): void
    {
        $this->sourceStubs(['9200:location_parent' => true, '9200:location_parent_id' => 9199], [9199 => $this->location('9199')]);

        $ids = \uamswp_fad_location_source_ids(9200);

        $this->assertSame(9199, $ids['parent']);
        $this->assertSame(9199, $ids['street']);
    }

    public function test_a_parent_that_is_the_location_itself_with_its_id_as_text_is_ignored(): void
    {
        $this->sourceStubs(['9190:location_parent' => true, '9190:location_parent_id' => 9190], [9190 => $this->location('9190')]);

        $ids = \uamswp_fad_location_source_ids(9190);

        $this->assertSame(0, $ids['parent']);
        $this->assertSame(9190, $ids['street']);
    }

    public function test_a_post_object_or_an_id_string_names_the_location(): void
    {
        $this->sourceStubs([]);

        $this->assertSame(9102, \uamswp_fad_location_source_ids($this->location(9102))['self']);
        $this->assertSame(9103, \uamswp_fad_location_source_ids('9103')['self']);
    }

    public function test_a_child_inherits_every_group_from_its_parent_by_default(): void
    {
        $this->sourceStubs(['9111:location_parent' => true, '9111:location_parent_id' => 9110], [9110 => $this->location(9110)]);

        $this->assertSame(
            ['self' => 9111, 'parent' => 9110, 'street' => 9110, 'facility' => 9110, 'unit' => 9110, 'map' => 9110, 'parking' => 9110, 'directions' => 9110],
            \uamswp_fad_location_source_ids(9111)
        );
    }

    public function test_a_child_overrides_only_the_groups_it_switches_on(): void
    {
        $this->sourceStubs([
            '9121:location_parent' => true,
            '9121:location_parent_id' => 9120,
            '9121:location_address_override_parent' => true,
            '9121:location_address_override_parent_street' => true,
            '9121:location_address_override_parent_unit' => true,
            '9121:location_parking_override_parent' => true,
            '9121:location_parking_override_parent_directions' => true,
        ], [9120 => $this->location(9120)]);

        $this->assertSame(
            ['self' => 9121, 'parent' => 9120, 'street' => 9121, 'facility' => 9120, 'unit' => 9121, 'map' => 9120, 'parking' => 9120, 'directions' => 9121],
            \uamswp_fad_location_source_ids(9121)
        );
    }

    public function test_each_group_toggle_is_read_from_its_own_field(): void
    {
        $this->sourceStubs([
            '9131:location_parent' => true,
            '9131:location_parent_id' => 9130,
            '9131:location_address_override_parent' => true,
            '9131:location_address_override_parent_facility' => true,
            '9131:location_address_override_parent_map' => true,
            '9131:location_parking_override_parent' => true,
            '9131:location_parking_override_parent_parking' => true,
        ], [9130 => $this->location(9130)]);

        $this->assertSame(
            ['self' => 9131, 'parent' => 9130, 'street' => 9130, 'facility' => 9131, 'unit' => 9130, 'map' => 9131, 'parking' => 9131, 'directions' => 9130],
            \uamswp_fad_location_source_ids(9131)
        );
    }

    public function test_group_toggles_do_nothing_while_their_master_switch_is_off(): void
    {
        $this->sourceStubs([
            '9141:location_parent' => true,
            '9141:location_parent_id' => 9140,
            '9141:location_address_override_parent_street' => true,
            '9141:location_address_override_parent_facility' => true,
            '9141:location_address_override_parent_unit' => true,
            '9141:location_address_override_parent_map' => true,
            '9141:location_parking_override_parent_parking' => true,
            '9141:location_parking_override_parent_directions' => true,
        ], [9140 => $this->location(9140)]);

        $ids = \uamswp_fad_location_source_ids(9141);

        $this->assertSame([9140, 9140, 9140, 9140, 9140, 9140], [$ids['street'], $ids['facility'], $ids['unit'], $ids['map'], $ids['parking'], $ids['directions']]);
    }

    public function test_a_parent_given_as_a_post_object_is_used_without_a_lookup(): void
    {
        $this->sourceStubs(['9151:location_parent' => true, '9151:location_parent_id' => $this->location(9150)]);
        expect('get_post')->never();

        $this->assertSame(9150, \uamswp_fad_location_source_ids(9151)['parent']);
    }

    /**
     * @return array<string, array{0: array<string, mixed>}>
     */
    public static function no_parent(): array
    {
        return [
            'no parent set' => [[]],
            'a parent that cannot be found' => [['location_parent' => true, 'location_parent_id' => 1]],
            'a parent that is the location itself' => [['location_parent' => true, 'location_parent_id' => 'self']],
        ];
    }

    /**
     * @param  array<string, mixed>  $fields
     */
    #[DataProvider('no_parent')]
    public function test_a_location_whose_parent_is_missing_or_itself_supplies_every_group_itself(array $fields): void
    {
        static $next = 9160;
        $id = $next++;
        $keyed = [];
        foreach ($fields as $field => $value) {
            $keyed[$id.':'.$field] = $value === 'self' ? $id : $value;
        }

        $this->sourceStubs($keyed, [$id => $this->location($id)]);

        $this->assertSame(
            ['self' => $id, 'parent' => 0, 'street' => $id, 'facility' => $id, 'unit' => $id, 'map' => $id, 'parking' => $id, 'directions' => $id],
            \uamswp_fad_location_source_ids($id)
        );
    }

    public function test_a_resolved_location_is_remembered_for_the_rest_of_the_request(): void
    {
        $this->sourceStubs(['9171:location_parent' => true, '9171:location_parent_id' => 9170], [9170 => $this->location(9170)]);
        $first = \uamswp_fad_location_source_ids(9171);

        when('get_field')->alias(static function (): never {
            throw new \LogicException('the location was read again');
        });

        $this->assertSame($first, \uamswp_fad_location_source_ids(9171));
    }
}
