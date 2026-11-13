<?php

/**
 * Pins the first part of includes/class.fad-functions.php: the search result rewrite, the admin and front-end
 * scripts, the PubMed shortcode and field, the provider list columns, the Press Ganey sync, token, cache and
 * review pager, the taxonomy admin tweaks, the Relevanssi content and the AJAX script registration.
 *
 * The file registers hooks and a class at file scope, so it loads once per process and the registrations are
 * captured on that first load. The branches that end in `exit` cannot run in a unit process.
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
require_once __DIR__.'/../Support/functions-file.php';
require_once __DIR__.'/../Support/wp-classes.php';

final class FunctionsTest extends UnitTestCase
{
    /** @var list<array{0: string, 1: string, 2: mixed, 3: int, 4: int}> */
    private static array $hooks = [];

    /** @var list<array{0: string, 1: mixed}> */
    private static array $deactivation = [];

    private string $previous_timezone = 'UTC';

    private string $previous_error_log = '';

    protected function setUp(): void
    {
        parent::setUp();

        // See AcfBlocksTest: the ACF functions file loads first, under Brain Monkey's own hook functions.
        \uamswp_test_acf_functions();

        defined('HOUR_IN_SECONDS') || define('HOUR_IN_SECONDS', 3600);
        defined('MINUTE_IN_SECONDS') || define('MINUTE_IN_SECONDS', 60);

        $this->previous_timezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $this->previous_error_log = (string) ini_get('error_log');

        when('__')->returnArg();
        when('esc_html__')->returnArg();
        when('esc_html')->returnArg();
        when('esc_attr')->returnArg();
        when('wp_unslash')->returnArg();
        when('absint')->alias(static fn (mixed $value): int => abs((int) (is_scalar($value) ? $value : 0)));
        when('is_wp_error')->alias(static fn (mixed $thing): bool => $thing instanceof \WP_Error);
        when('add_query_arg')->alias(static fn (array $args, string $url): string => $url.'?'.http_build_query($args));

        $registered = \uamswp_test_functions_file();
        self::$hooks = $registered['hooks'];
        self::$deactivation = $registered['deactivation'];
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->previous_timezone);
        ini_set('error_log', $this->previous_error_log);
        unset($_POST['pmid'], $_POST['post_id'], $_POST['page']);
        putenv('UAMSWP_PG_APP_ID');
        putenv('UAMSWP_PG_APP_SECRET');
        $GLOBALS['post_type'] = null;

        parent::tearDown();
    }

    private function hook(string $kind, string $name, int $nth = 0): mixed
    {
        $matches = array_values(array_filter(self::$hooks, static fn (array $hook): bool => $hook[0] === $kind && $hook[1] === $name));
        $this->assertArrayHasKey($nth, $matches, "no $kind registered on $name");

        return $matches[$nth][2];
    }

    private function closure(string $kind, string $name, int $nth = 0): \Closure
    {
        $callback = $this->hook($kind, $name, $nth);
        $this->assertInstanceOf(\Closure::class, $callback);

        return $callback;
    }

    /**
     * @return array<string, array{0: string, 1: string, 2: string, 3: int, 4: int}>
     */
    public static function registrations(): array
    {
        return [
            'search results' => ['filter', 'asp_results', 'asp_custom_link_meta_results', 1, 1],
            'admin scripts' => ['action', 'admin_enqueue_scripts', 'uamswp_admin_scripts', 10, 1],
            'front-end scripts' => ['action', 'wp_enqueue_scripts', 'fad_script_register', 10, 1],
            'pubmed shortcode' => ['shortcode', 'pubmed', 'uams_pubmed_shortcode', 10, 1],
            'provider columns' => ['filter', 'manage_provider_posts_columns', 'posts_provider_columns', 10, 1],
            'provider column cells' => ['action', 'manage_provider_posts_custom_column', 'posts_provider_custom_columns', 10, 2],
            'cron schedule' => ['filter', 'cron_schedules', 'uamswp_cron_every_two_hours', 10, 1],
            'cron check' => ['action', 'init', 'uamswp_fad_check_and_schedule_cron', 10, 1],
            'sync hook' => ['action', 'uamswp_provider_pg_sync_hook', 'sync_provider_pg_data', 10, 1],
            'review pager' => ['action', 'wp_ajax_pg_ajax_api_action', 'pg_ajax_api', 10, 1],
            'review pager for visitors' => ['action', 'wp_ajax_nopriv_pg_ajax_api_action', 'pg_ajax_api', 10, 1],
            'genesis term meta' => ['action', 'admin_init', 'uamswp_remove_genesis_term_meta', 11, 1],
            'relevanssi term content' => ['filter', 'relevanssi_tax_term_additional_content', 'rlv_tax_excerpt_term_fields', 10, 2],
            'relevanssi excerpt content' => ['filter', 'relevanssi_pre_excerpt_content', 'rlv_tax_excerpt_term_fields', 10, 2],
            'ajax scripts' => ['action', 'wp_enqueue_scripts', 'uamswp_ajax_scripts', 10, 1],
        ];
    }

    #[DataProvider('registrations')]
    public function test_the_hook_is_registered_with_its_callback(string $kind, string $name, string $callback, int $priority, int $accepted): void
    {
        $found = array_filter(
            self::$hooks,
            static fn (array $hook): bool => $hook[0] === $kind && $hook[1] === $name && $hook[2] === $callback
        );

        $this->assertCount(1, $found);
        $this->assertSame([$priority, $accepted], [array_values($found)[0][3], array_values($found)[0][4]]);
    }

    public function test_the_pubmed_field_registers_its_script_and_ajax_action(): void
    {
        $this->assertSame(
            [['action', 'acf/input/admin_enqueue_scripts', 10], ['action', 'wp_ajax_load_content_from_pubmed', 10]],
            array_values(array_map(
                static fn (array $hook): array => [$hook[0], $hook[1], $hook[3]],
                array_filter(self::$hooks, static fn (array $hook): bool => is_array($hook[2]) && ($hook[2][0] ?? null) instanceof \pubmed_field_on_change)
            ))
        );
    }

    public function test_the_plugin_deactivation_hook_clears_the_sync(): void
    {
        $this->assertCount(1, self::$deactivation);
        $this->assertSame(UAMS_FAD_PATH.'uamswp-find-a-doc.php', self::$deactivation[0][0]);

        expect('wp_clear_scheduled_hook')->once()->with('uamswp_provider_pg_sync_hook');
        expect('delete_transient')->once()->with('uamswp_pg_sync_lock');

        $callback = self::$deactivation[0][1];
        $this->assertInstanceOf(\Closure::class, $callback);
        $callback();
    }

    // Search results.

    private function searchResult(int|string $id, string $type = 'pagepost'): \stdClass
    {
        $result = new \stdClass;
        $result->id = $id;
        $result->content_type = $type;
        $result->title = 'Original title';
        $result->content = 'Original content';

        return $result;
    }

    /**
     * @param  array<string, mixed>  $fields
     * @param  array<int, string>  $types
     * @param  array<int, string|null>  $terms
     */
    private function searchStubs(array $fields, array $types, array $terms = []): void
    {
        when('get_post_type')->alias(static fn (int $id): string => $types[$id] ?? 'page');
        when('get_field')->alias(static fn (string $field, int $id): mixed => $fields[$id.':'.$field] ?? '');
        when('get_term')->alias(static fn (int $id, string $taxonomy): ?object => isset($terms[$id]) ? (object) ['name' => $terms[$id]] : null);
    }

    public function test_a_provider_result_shows_the_full_name_with_degrees_and_the_short_bio(): void
    {
        $this->searchStubs(
            [
                '12:physician_degree' => [3, 4],
                '12:physician_first_name' => 'Ann',
                '12:physician_middle_name' => 'Marie',
                '12:physician_last_name' => 'Lee',
                '12:physician_short_clinical_bio' => 'Cares for kids.',
            ],
            [12 => 'provider'],
            [3 => 'MD', 4 => 'PhD']
        );

        $results = \asp_custom_link_meta_results([$this->searchResult('12')]);

        $this->assertSame('Ann Marie Lee, MD, PhD', $results[0]->title);
        $this->assertSame('Cares for kids.', $results[0]->content);
    }

    public function test_a_provider_without_a_middle_name_or_degrees_shows_the_plain_name(): void
    {
        $this->searchStubs(
            ['12:physician_first_name' => 'Ann', '12:physician_last_name' => 'Lee'],
            [12 => 'provider']
        );

        $results = \asp_custom_link_meta_results([$this->searchResult(12)]);

        $this->assertSame('Ann Lee', $results[0]->title);
        $this->assertSame('Original content', $results[0]->content, 'an empty bio leaves the content alone');
    }

    public function test_a_degree_term_that_cannot_be_found_adds_nothing_to_the_list(): void
    {
        $this->searchStubs(
            ['12:physician_degree' => [3, 9], '12:physician_first_name' => 'Ann', '12:physician_last_name' => 'Lee'],
            [12 => 'provider'],
            [3 => 'MD']
        );

        $results = \asp_custom_link_meta_results([$this->searchResult(12)]);

        $this->assertSame('Ann Lee, MD, ', $results[0]->title);
    }

    public function test_only_provider_page_posts_are_rewritten_and_the_name_does_not_carry_over(): void
    {
        $this->searchStubs(
            ['12:physician_first_name' => 'Ann', '12:physician_last_name' => 'Lee', '12:physician_short_clinical_bio' => 'Bio'],
            [12 => 'provider', 13 => 'post', 14 => 'provider']
        );

        $results = \asp_custom_link_meta_results([
            $this->searchResult(12),
            $this->searchResult(13),
            $this->searchResult(14, 'term'),
            $this->searchResult(15),
        ]);

        $this->assertSame('Ann Lee', $results[0]->title);
        $this->assertSame(['Original title', 'Original title', 'Original title'], [$results[1]->title, $results[2]->title, $results[3]->title]);
        $this->assertSame(['Original content', 'Original content', 'Original content'], [$results[1]->content, $results[2]->content, $results[3]->content]);
    }

    public function test_a_first_result_that_is_not_a_provider_starts_with_no_name_or_bio(): void
    {
        $this->searchStubs(['12:physician_first_name' => 'Ann', '12:physician_last_name' => 'Lee', '12:physician_short_clinical_bio' => 'Bio'], [12 => 'provider']);

        $results = \asp_custom_link_meta_results([$this->searchResult(13), $this->searchResult(12)]);

        $this->assertSame(['Original title', 'Original content'], [$results[0]->title, $results[0]->content]);
        $this->assertSame(['Ann Lee', 'Bio'], [$results[1]->title, $results[1]->content]);
    }

    public function test_no_results_come_back_unchanged(): void
    {
        $this->assertSame([], \asp_custom_link_meta_results([]));
    }

    // Scripts.

    /**
     * @return list<array{0: string, 1: string}>
     */
    private function recordAssets(): \ArrayObject
    {
        $calls = new \ArrayObject;
        foreach (['wp_register_script', 'wp_enqueue_script', 'wp_enqueue_style'] as $function) {
            when($function)->alias(static function (string $handle, string $src = '', array $deps = [], mixed $version = null, mixed $extra = null) use ($calls, $function): void {
                $calls[] = [$function, $handle, $src, $deps, $version, $extra];
            });
        }

        return $calls;
    }

    /**
     * @return list<string>
     */
    private function handles(\ArrayObject $calls): array
    {
        return array_values(array_map(static fn (array $call): string => $call[0].':'.$call[1], $calls->getArrayCopy()));
    }

    public function test_the_admin_scripts_load_on_the_post_and_term_screens(): void
    {
        $calls = $this->recordAssets();

        foreach (['post.php', 'edit-tags.php', 'term.php'] as $hook) {
            \uamswp_admin_scripts($hook);
        }

        $this->assertSame(
            array_merge(...array_fill(0, 3, ['wp_enqueue_script:acf-admin-js', 'wp_enqueue_script:medline-acf-js'])),
            $this->handles($calls)
        );
        $this->assertSame([UAMS_FAD_ROOT_URL.'admin/js/acf-admin.js', ['jquery'], null, true], [$calls[0][2], $calls[0][3], $calls[0][4], $calls[0][5]]);
        $this->assertSame([UAMS_FAD_ROOT_URL.'admin/js/acf-medline.js', ['jquery'], null, true], [$calls[1][2], $calls[1][3], $calls[1][4], $calls[1][5]]);
    }

    public function test_the_admin_scripts_stay_off_other_screens(): void
    {
        $calls = $this->recordAssets();

        \uamswp_admin_scripts('edit.php');
        \uamswp_admin_scripts('');

        $this->assertSame([], $this->handles($calls));
    }

    /**
     * @return array<string, array{0: bool, 1: bool, 2: bool, 3: string|null, 4: list<string>}>
     */
    public static function pages(): array
    {
        $styles = ['wp_enqueue_style:fad-app-css', 'wp_enqueue_style:fad-css'];

        return [
            'a plain page' => [false, false, false, 'page', array_merge(['wp_register_script:pubmed-api'], $styles)],
            'the admin' => [true, false, false, 'page', $styles],
            'a single location' => [false, true, false, 'location', array_merge(['wp_register_script:pubmed-api', 'wp_enqueue_style:leaflet-css', 'wp_enqueue_script:leaflet-js'], $styles)],
            'a single provider' => [false, true, false, 'provider', array_merge(['wp_register_script:pubmed-api'], $styles)],
            'the provider archive' => [false, false, true, 'provider', array_merge(['wp_register_script:pubmed-api', 'wp_enqueue_script:mobile-filter-toggle'], $styles)],
            'the location archive' => [false, false, true, 'location', array_merge(['wp_register_script:pubmed-api', 'wp_enqueue_script:mobile-filter-toggle'], $styles)],
            'another archive' => [false, false, true, 'condition', array_merge(['wp_register_script:pubmed-api'], $styles)],
            'an archive with no post type' => [false, false, true, null, array_merge(['wp_register_script:pubmed-api'], $styles)],
        ];
    }

    /**
     * @param  list<string>  $expected
     */
    #[DataProvider('pages')]
    public function test_the_front_end_scripts_follow_the_page_type(bool $admin, bool $single, bool $archive, ?string $type, array $expected): void
    {
        $calls = $this->recordAssets();
        when('is_admin')->justReturn($admin);
        when('is_single')->justReturn($single);
        when('is_archive')->justReturn($archive);
        $GLOBALS['post_type'] = $type;

        \fad_script_register();

        $this->assertSame($expected, $this->handles($calls));
    }

    public function test_the_front_end_assets_come_from_the_plugin_with_its_version(): void
    {
        $calls = $this->recordAssets();
        when('is_admin')->justReturn(false);
        when('is_single')->justReturn(true);
        when('is_archive')->justReturn(false);
        $GLOBALS['post_type'] = 'location';

        \fad_script_register();

        $this->assertSame([
            ['wp_register_script', 'pubmed-api', UAMS_FAD_ROOT_URL.'assets/js/pubmed-api-async.js', ['jquery'], null, true],
            ['wp_enqueue_style', 'leaflet-css', UAMS_FAD_ROOT_URL.'assets/leaflet/leaflet.css', [], '1.1', 'all'],
            ['wp_enqueue_script', 'leaflet-js', UAMS_FAD_ROOT_URL.'assets/leaflet/leaflet.js', [], null, false],
            ['wp_enqueue_style', 'fad-app-css', UAMS_FAD_ROOT_URL.'assets/css/app.css', [], UAMS_FAD_VERSION, 'all'],
            ['wp_enqueue_style', 'fad-css', UAMS_FAD_ROOT_URL.'assets/css/style.css', [], UAMS_FAD_VERSION, 'all'],
        ], $calls->getArrayCopy());

        $calls->exchangeArray([]);
        $GLOBALS['post_type'] = 'provider';
        when('is_single')->justReturn(false);
        when('is_archive')->justReturn(true);

        \fad_script_register();

        $this->assertSame(
            ['wp_enqueue_script', 'mobile-filter-toggle', UAMS_FAD_ROOT_URL.'assets/js/mobile-filter-toggle.js', ['jquery'], null, false],
            $calls[1]
        );
    }

    // PubMed.

    public function test_the_pubmed_field_script_is_registered_against_acf_input_and_enqueued(): void
    {
        $calls = $this->recordAssets();

        (new \pubmed_field_on_change)->enqueue_script();

        $this->assertSame([
            ['wp_register_script', 'pubmed_field_on_change', UAMS_FAD_ROOT_URL.'/assets/js/acf-pubmed.js', ['acf-input'], null, null],
            ['wp_enqueue_script', 'pubmed_field_on_change', '', [], null, null],
        ], $calls->getArrayCopy());
    }

    public function test_a_failed_pubmed_request_returns_false(): void
    {
        $_POST['pmid'] = '22703441abc';
        $requested = [];
        when('wp_remote_get')->alias(static function (string $url) use (&$requested): \WP_Error {
            $requested[] = $url;

            return new \WP_Error('http_request_failed', 'down');
        });
        when('wp_remote_retrieve_body')->justReturn('');

        $this->assertFalse((new \pubmed_field_on_change)->load_content_from_pubmed());
        $this->assertSame(['http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&retmode=json&id=22703441'], $requested);
    }

    public function test_the_pubmed_shortcode_prints_its_list_with_the_terms_and_count(): void
    {
        when('shortcode_atts')->alias(static fn (array $defaults, array $atts, string $tag): array => array_merge($defaults, $atts));
        expect('wp_enqueue_script')->twice()->with('pubmed-api');

        $this->assertSame(
            '<ul class="pubmed-list" data-terms="Chernoff%20R%5BAuthor%5D" data-count="10"></ul>',
            \uams_pubmed_shortcode(['terms' => 'Chernoff%20R%5BAuthor%5D', 'count' => '10'])
        );
        $this->assertSame('<ul class="pubmed-list" data-terms="" data-count="20"></ul>', \uams_pubmed_shortcode(''));
    }

    // Provider list columns.

    public function test_the_headshot_column_is_added_before_the_title(): void
    {
        $this->assertSame(
            ['cb' => 'Box', 'provider_post_thumbs' => 'Headshot', 'title' => 'Title', 'date' => 'Date'],
            \posts_provider_columns(['cb' => 'Box', 'title' => 'Title', 'date' => 'Date'])
        );
        $this->assertSame(['cb', 'provider_post_thumbs', 'title', 'date'], array_keys(\posts_provider_columns(['cb' => 'Box', 'title' => 'Title', 'date' => 'Date'])));
    }

    public function test_the_columns_without_a_title_are_left_alone(): void
    {
        $this->assertSame(['cb' => 'Box'], \posts_provider_columns(['cb' => 'Box']));
    }

    public function test_the_headshot_cell_prints_the_thumbnail_at_eighty_pixels(): void
    {
        expect('get_the_post_thumbnail')->once()->with(12, [80, 80])->andReturn('<img src="a.jpg">');
        $this->expectOutputString('<img src="a.jpg">');

        \posts_provider_custom_columns('provider_post_thumbs', '12');
    }

    public function test_other_cells_print_nothing(): void
    {
        expect('get_the_post_thumbnail')->never();
        $this->expectOutputString('');

        \posts_provider_custom_columns('title', 12);
    }

    public function test_a_two_hour_cron_schedule_is_added_beside_the_existing_ones(): void
    {
        $this->assertSame(
            ['hourly' => ['interval' => 3600, 'display' => 'Hourly'], 'every_two_hours' => ['interval' => 7200, 'display' => 'Every 2 Hours']],
            \uamswp_cron_every_two_hours(['hourly' => ['interval' => 3600, 'display' => 'Hourly']])
        );
    }

    // Press Ganey.

    /**
     * @param  array<string, mixed>  $transients
     */
    private function transients(array $transients): void
    {
        when('get_transient')->alias(static fn (string $key): mixed => $transients[$key] ?? false);
    }

    /**
     * @param  list<mixed>  $responses
     * @return \ArrayObject<int, array{0: string, 1: array<string, mixed>}>
     */
    private function responses(array $responses): \ArrayObject
    {
        $requests = new \ArrayObject;
        when('wp_remote_get')->alias(static function (string $url, array $args = []) use (&$responses, $requests): mixed {
            $requests[] = [$url, $args];

            return array_shift($responses);
        });
        // The code is returned as given, so a test can hand back text the way a transport may.
        when('wp_remote_retrieve_response_code')->alias(static fn (mixed $response): mixed => $response['code']);
        when('wp_remote_retrieve_body')->alias(static fn (mixed $response): string => (string) $response['body']);

        return $requests;
    }

    public function test_a_good_response_is_decoded_and_the_token_goes_in_the_header(): void
    {
        $this->transients(['pg_api_token' => 'tok']);
        $requests = $this->responses([['code' => 200, 'body' => '{"status":{"code":"200"},"data":[1]}']]);

        $this->assertSame(
            ['status' => ['code' => '200'], 'data' => [1]],
            \fetch_pg_api_with_retry('https://example.test/api', 3, 0)
        );
        $this->assertSame(
            [['https://example.test/api', ['timeout' => 15, 'headers' => ['Content-Type' => 'application/json', 'Access-Token' => 'tok']]]],
            $requests->getArrayCopy()
        );
    }

    public function test_a_transport_error_is_returned_without_a_retry(): void
    {
        $this->transients(['pg_api_token' => 'tok']);
        $error = new \WP_Error('http_request_failed', 'down');
        $requests = $this->responses([$error, ['code' => 200, 'body' => '{}']]);

        $this->assertSame($error, \fetch_pg_api_with_retry('https://example.test/api', 3, 0));
        $this->assertCount(1, $requests);
    }

    /**
     * @return array<string, array{0: array{code: int, body: string}}>
     */
    public static function limited_responses(): array
    {
        return [
            'an http 429' => [['code' => 429, 'body' => '{}']],
            'a 429 in the status block' => [['code' => 200, 'body' => '{"status":{"code":429}}']],
            'a 429 string in the status block' => [['code' => 200, 'body' => '{"status":{"code":"429"}}']],
        ];
    }

    /**
     * @param  array{code: int, body: string}  $limited
     */
    #[DataProvider('limited_responses')]
    public function test_a_rate_limit_is_retried_and_then_succeeds(array $limited): void
    {
        $this->transients(['pg_api_token' => 'tok']);
        $requests = $this->responses([$limited, ['code' => 200, 'body' => '{"ok":true}']]);

        $this->assertSame(['ok' => true], \fetch_pg_api_with_retry('https://example.test/api', 3, 0));
        $this->assertCount(2, $requests);
    }

    public function test_each_retry_waits_the_delay_times_the_attempt_number(): void
    {
        $this->transients(['pg_api_token' => 'tok']);
        $this->responses(array_fill(0, 5, ['code' => 429, 'body' => '{}']));
        expect('sleep')->once()->with(6)->ordered();
        expect('sleep')->once()->with(12)->ordered();

        $result = \fetch_pg_api_with_retry('https://example.test/api', 3, 6);

        $this->assertInstanceOf(\WP_Error::class, $result);
    }

    public function test_a_rate_limit_that_never_clears_stops_at_the_retry_limit(): void
    {
        $this->transients(['pg_api_token' => 'tok']);
        $requests = $this->responses(array_fill(0, 5, ['code' => 429, 'body' => '{}']));

        $result = \fetch_pg_api_with_retry('https://example.test/api', 2, 0);

        $this->assertInstanceOf(\WP_Error::class, $result);
        $this->assertSame('pg_rate_limited', $result->get_error_code());
        $this->assertSame('PressGaney API rate limited after max retries.', $result->get_error_message());
        $this->assertCount(2, $requests);
    }

    public function test_an_unexpected_status_is_an_error_carrying_the_body(): void
    {
        $this->transients(['pg_api_token' => 'tok']);
        $this->responses([['code' => 500, 'body' => 'oops']]);

        $result = \fetch_pg_api_with_retry('https://example.test/api', 3, 0);

        $this->assertInstanceOf(\WP_Error::class, $result);
        $this->assertSame(['pg_bad_response', 'Unexpected response code: 500', 'oops'], [$result->get_error_code(), $result->get_error_message(), $result->get_error_data()]);
    }

    public function test_a_body_that_is_not_json_is_an_error(): void
    {
        $this->transients(['pg_api_token' => 'tok']);
        $this->responses([['code' => 200, 'body' => '{not json']]);

        $result = \fetch_pg_api_with_retry('https://example.test/api', 3, 0);

        $this->assertInstanceOf(\WP_Error::class, $result);
        $this->assertSame(['pg_json_error', 'Failed to decode PressGaney response: Syntax error'], [$result->get_error_code(), $result->get_error_message()]);
    }

    public function test_no_attempts_allowed_returns_false(): void
    {
        $this->transients(['pg_api_token' => 'tok']);
        $requests = $this->responses([]);

        $this->assertFalse(\fetch_pg_api_with_retry('https://example.test/api', 0, 0));
        $this->assertCount(0, $requests);
    }

    public function test_the_default_retry_settings_make_one_request_for_a_good_response(): void
    {
        $this->transients(['pg_api_token' => 'tok']);
        $requests = $this->responses([['code' => 200, 'body' => '[]']]);

        $this->assertSame([], \fetch_pg_api_with_retry('https://example.test/api'));
        $this->assertCount(1, $requests);
    }

    // The sync schedule and run.

    public function test_the_sync_is_scheduled_every_two_hours_when_it_is_missing_in_the_admin(): void
    {
        when('is_admin')->justReturn(true);
        expect('wp_next_scheduled')->once()->with('uamswp_provider_pg_sync_hook')->andReturn(false);
        expect('wp_schedule_event')->once()->with(Mockery::type('int'), 'every_two_hours', 'uamswp_provider_pg_sync_hook');

        \uamswp_fad_check_and_schedule_cron();
    }

    public function test_a_sync_that_is_already_scheduled_is_left_alone(): void
    {
        when('is_admin')->justReturn(true);
        expect('wp_next_scheduled')->once()->andReturn(1700000000);
        expect('wp_schedule_event')->never();

        \uamswp_fad_check_and_schedule_cron();
    }

    public function test_the_front_end_never_checks_the_schedule(): void
    {
        when('is_admin')->justReturn(false);
        expect('wp_next_scheduled')->never();
        expect('wp_schedule_event')->never();

        \uamswp_fad_check_and_schedule_cron();
    }

    public function test_a_running_sync_blocks_a_second_one(): void
    {
        $this->transients(['uamswp_pg_sync_lock' => 1700000000]);
        expect('get_posts')->never();
        expect('set_transient')->never();

        \sync_provider_pg_data();
    }

    public function test_no_published_providers_means_nothing_to_sync_and_no_lock(): void
    {
        $this->transients([]);
        expect('get_posts')->once()->with([
            'post_type' => 'provider',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ])->andReturn([]);
        expect('set_transient')->never();
        expect('delete_transient')->never();

        \sync_provider_pg_data();
    }

    public function test_a_provider_without_an_npi_is_skipped_and_the_lock_is_set_then_cleared(): void
    {
        $this->transients([]);
        when('get_posts')->justReturn([5, 6]);
        when('get_field')->alias(static fn (string $field, int $id): mixed => $id === 6 ? '0' : '');
        expect('set_transient')->once()->with('uamswp_pg_sync_lock', Mockery::type('int'), 7200);
        expect('delete_transient')->once()->with('uamswp_pg_sync_lock');
        expect('wp_remote_get')->never();
        expect('update_post_meta')->never();

        \sync_provider_pg_data();
    }

    public function test_a_provider_without_an_npi_does_not_stop_the_providers_after_it(): void
    {
        when('usleep')->justReturn();
        $this->transients(['pg_api_token' => 'tok']);
        when('get_posts')->justReturn([5, 6]);
        when('get_field')->alias(static fn (string $field, int $id): mixed => $id === 6 ? '1234567890' : '');
        when('set_transient')->justReturn(true);
        when('delete_transient')->justReturn(true);
        $this->responses([['code' => 200, 'body' => '{"n":2}']]);
        expect('update_post_meta')->once()->with(6, '_syndicated_api_data', ['n' => 2]);

        \sync_provider_pg_data();
    }

    public function test_each_provider_with_an_npi_is_fetched_and_its_payload_kept(): void
    {
        $this->transients(['pg_api_token' => 'tok']);
        when('get_posts')->justReturn(['5']);
        when('get_field')->justReturn('1234567890');
        when('set_transient')->justReturn(true);
        when('delete_transient')->justReturn(true);

        $requests = $this->responses([['code' => 200, 'body' => '{"status":{"code":"200"},"n":1}']]);
        expect('update_post_meta')->once()->with(5, '_syndicated_api_data', ['status' => ['code' => '200'], 'n' => 1]);
        expect('usleep')->once()->with(500000);

        \sync_provider_pg_data();

        $this->assertSame(
            'https://api1.consumerism.pressganey.com/api/bsr/comments?personId=1234567890&perPage=10&days=540&additionalFields=reviewSummary',
            $requests[0][0]
        );
    }

    public function test_an_empty_payload_is_not_stored(): void
    {
        when('usleep')->justReturn();
        $this->transients(['pg_api_token' => 'tok']);
        when('get_posts')->justReturn([5]);
        when('get_field')->justReturn('1234567890');
        when('set_transient')->justReturn(true);
        when('delete_transient')->justReturn(true);
        $this->responses([['code' => 200, 'body' => '[]']]);
        expect('update_post_meta')->never();

        \sync_provider_pg_data();
    }

    public function test_a_failed_fetch_is_logged_and_the_next_provider_still_runs(): void
    {
        when('usleep')->justReturn();
        $log = tempnam(sys_get_temp_dir(), 'pglog');
        $this->assertIsString($log);
        ini_set('error_log', $log);
        $this->transients(['pg_api_token' => 'tok']);
        when('get_posts')->justReturn([5, 6]);
        when('get_field')->justReturn('1234567890');
        when('set_transient')->justReturn(true);
        when('delete_transient')->justReturn(true);
        $this->responses([new \WP_Error('http_request_failed', 'down'), ['code' => 200, 'body' => '{"n":2}']]);
        expect('update_post_meta')->once()->with(6, '_syndicated_api_data', ['n' => 2]);

        \sync_provider_pg_data();

        $this->assertStringContainsString('UAMSWP Find-a-Doc: Press Ganey sync skipped provider 5: down', (string) file_get_contents($log));
        unlink($log);
    }

    public function test_an_exhausted_quota_stops_the_run_but_still_clears_the_lock(): void
    {
        $log = tempnam(sys_get_temp_dir(), 'pglog');
        $this->assertIsString($log);
        ini_set('error_log', $log);
        $this->transients(['pg_api_token' => 'tok']);
        when('get_posts')->justReturn([5, 6]);
        when('get_field')->justReturn('1234567890');
        when('set_transient')->justReturn(true);
        // The sync uses the fetcher's default pause of two seconds, grown by the attempt number.
        expect('sleep')->twice();
        $requests = $this->responses(array_fill(0, 6, ['code' => 429, 'body' => '{}']));
        expect('delete_transient')->once()->with('uamswp_pg_sync_lock');
        expect('update_post_meta')->never();

        \sync_provider_pg_data();

        $this->assertCount(3, $requests, 'the second provider is never fetched');
        $this->assertStringContainsString('skipped provider 5: PressGaney API rate limited after max retries.', (string) file_get_contents($log));
        unlink($log);
    }

    // The access token.

    public function test_a_cached_token_is_returned_without_asking_for_credentials(): void
    {
        $this->transients(['pg_api_token' => 'cached']);
        expect('wp_remote_post')->never();

        $this->assertSame('cached', \wp_pg_get_token());
    }

    public function test_without_both_credentials_no_token_is_requested(): void
    {
        $this->transients([]);
        expect('wp_remote_post')->never();

        $this->assertFalse(\wp_pg_get_token());

        putenv('UAMSWP_PG_APP_ID=id-only');
        $this->assertFalse(\wp_pg_get_token());

        putenv('UAMSWP_PG_APP_ID');
        putenv('UAMSWP_PG_APP_SECRET=secret-only');
        $this->assertFalse(\wp_pg_get_token());
    }

    public function test_the_credentials_from_the_environment_fetch_a_token_that_is_cached_for_25_minutes(): void
    {
        putenv('UAMSWP_PG_APP_ID=the-id');
        putenv('UAMSWP_PG_APP_SECRET=the-secret');
        $this->transients([]);
        $posted = [];
        when('wp_remote_post')->alias(static function (string $url, array $args) use (&$posted): array {
            $posted = [$url, $args];

            return ['code' => '200', 'body' => '{"accessToken":"abc"}'];
        });
        when('wp_remote_retrieve_response_code')->alias(static fn (mixed $response): mixed => $response['code']);
        when('wp_remote_retrieve_body')->alias(static fn (mixed $response): string => (string) $response['body']);
        expect('set_transient')->once()->with('pg_api_token', 'abc', 1500);

        $this->assertSame('abc', \wp_pg_get_token());
        $this->assertSame(
            [
                'https://api1.consumerism.pressganey.com/api/service/v1/token/create?appId=the-id&appSecret=the-secret',
                ['headers' => ['Content-Type' => 'application/json', 'Access-Token' => 'Content-Type']],
            ],
            $posted
        );
    }

    /**
     * @return array<string, array{0: int, 1: string}>
     */
    public static function failed_token_responses(): array
    {
        return [
            'a server error' => [500, '{"accessToken":"abc"}'],
            'an empty body' => [200, ''],
        ];
    }

    #[DataProvider('failed_token_responses')]
    public function test_a_failed_token_request_gives_no_token_and_caches_nothing(int $code, string $body): void
    {
        putenv('UAMSWP_PG_APP_ID=the-id');
        putenv('UAMSWP_PG_APP_SECRET=the-secret');
        $this->transients([]);
        when('wp_remote_post')->justReturn(['code' => $code, 'body' => $body]);
        when('wp_remote_retrieve_response_code')->alias(static fn (mixed $response): int => (int) $response['code']);
        when('wp_remote_retrieve_body')->alias(static fn (mixed $response): string => (string) $response['body']);

        expect('set_transient')->never();

        $this->assertFalse(\wp_pg_get_token());
    }

    // The cached comments call.

    public function test_a_cache_miss_fetches_the_comments_and_keeps_the_body_for_fifteen_minutes(): void
    {
        $this->transients(['pg_api_token' => 'tok']);
        $body = '{"status":{"code":"200"}}';
        $requests = $this->responses([['code' => 200, 'body' => $body]]);
        expect('set_transient')->once()->with('pg_123', $body, 900);

        $this->assertSame($body, \wp_pg_cached_api('123'));
        $this->assertSame(
            [
                'https://api1.consumerism.pressganey.com/api/bsr/comments?personId=123&perPage=6&days=540',
                ['headers' => ['Content-Type' => 'application/json', 'Access-Token' => 'tok']],
            ],
            $requests[0]
        );
    }

    public function test_the_count_is_part_of_the_request(): void
    {
        $this->transients([]);
        $requests = $this->responses([['code' => 200, 'body' => '{}']]);
        when('set_transient')->justReturn(true);

        \wp_pg_cached_api(7, 3);

        $this->assertSame('https://api1.consumerism.pressganey.com/api/bsr/comments?personId=7&perPage=3&days=540', $requests[0][0]);
    }

    public function test_a_transport_error_gives_false_and_caches_nothing(): void
    {
        $this->transients([]);
        $this->responses([new \WP_Error('http_request_failed', 'down')]);
        expect('set_transient')->never();

        $this->assertFalse(\wp_pg_cached_api(123));
    }

    /**
     * @return array<string, array{0: array{code: int, body: string}}>
     */
    public static function limited_comment_responses(): array
    {
        return [
            'an http 429' => [['code' => 429, 'body' => '{}']],
            'a 429 in the status block' => [['code' => 200, 'body' => '{"status":{"code":429}}']],
            'a 429 string in the status block' => [['code' => 200, 'body' => '{"status":{"code":"429"}}']],
        ];
    }

    /**
     * @param  array{code: int, body: string}  $limited
     */
    #[DataProvider('limited_comment_responses')]
    public function test_a_rate_limited_response_gives_false_and_is_not_cached(array $limited): void
    {
        $this->transients([]);
        $this->responses([$limited]);
        expect('set_transient')->never();

        $this->assertFalse(\wp_pg_cached_api(123));
    }

    public function test_a_cached_good_payload_is_returned_without_a_request(): void
    {
        $cached = ['status' => ['code' => '200']];
        $this->transients(['pg_api_token' => 'tok', 'pg_123' => $cached]);
        expect('wp_remote_get')->never();

        $this->assertSame($cached, \wp_pg_cached_api(123));
    }

    public function test_a_cached_string_is_returned_without_a_request(): void
    {
        $this->transients(['pg_api_token' => 'tok', 'pg_123' => '{"cached":true}']);
        expect('wp_remote_get')->never();

        $this->assertSame('{"cached":true}', \wp_pg_cached_api(123));
    }

    public function test_a_cached_payload_that_was_not_a_success_is_fetched_again(): void
    {
        $this->transients(['pg_api_token' => 'tok', 'pg_123' => ['status' => ['code' => '500']]]);
        $this->responses([['code' => 200, 'body' => '{"fresh":true}']]);
        when('set_transient')->justReturn(true);

        $this->assertSame('{"fresh":true}', \wp_pg_cached_api(123));
    }

    // The review pager.

    /**
     * @param  array<string, mixed>  $post
     * @param  array<string, string>  $stubs
     */
    private function pager(array $post = ['post_id' => '12'], string $type = 'provider', string $status = 'publish', mixed $npi = '1234567890'): void
    {
        $_POST = $post;
        expect('check_ajax_referer')->atLeast()->once()->with('pg_pagination_posts', 'nonce')->andReturn(1);
        when('get_post_type')->justReturn($type);
        when('get_post_status')->justReturn($status);
        when('get_field')->justReturn($npi);
        when('wp_send_json_error')->alias(static function (string $message, int $status): never {
            throw new \RuntimeException($message.'|'.$status);
        });
    }

    private function assertPagerError(string $expected): void
    {
        try {
            \pg_ajax_api();
            $this->fail('the pager answered');
        } catch (\RuntimeException $runtimeException) {
            $this->assertSame($expected, $runtimeException->getMessage());
        }
    }

    /**
     * @return array<string, array{0: array<string, mixed>, 1: string, 2: string, 3: mixed}>
     */
    public static function invalid_requests(): array
    {
        return [
            'no post id' => [[], 'provider', 'publish', '1234567890'],
            'a post id of zero' => [['post_id' => '0'], 'provider', 'publish', '1234567890'],
            'a post that is not a provider' => [['post_id' => '12'], 'page', 'publish', '1234567890'],
            'a draft provider' => [['post_id' => '12'], 'provider', 'draft', '1234567890'],
            'a provider with no npi' => [['post_id' => '12'], 'provider', 'publish', ''],
        ];
    }

    /**
     * @param  array<string, mixed>  $post
     */
    #[DataProvider('invalid_requests')]
    public function test_an_invalid_request_is_refused_with_400(array $post, string $type, string $status, mixed $npi): void
    {
        $this->pager($post, $type, $status, $npi);
        expect('wp_remote_get')->never();

        $this->assertPagerError('invalid request|400');
    }

    public function test_no_token_means_the_upstream_is_unavailable(): void
    {
        $this->pager();
        $this->transients([]);
        expect('wp_remote_get')->never();

        $this->assertPagerError('upstream unavailable|502');
    }

    public function test_a_failed_or_non_200_upstream_answer_is_a_502(): void
    {
        $this->pager();
        $this->transients(['pg_api_token' => 'tok']);
        $this->responses([new \WP_Error('http_request_failed', 'down')]);
        $this->assertPagerError('upstream unavailable|502');

        $this->responses([['code' => 503, 'body' => '{}']]);
        $this->assertPagerError('upstream unavailable|502');
    }

    public function test_a_status_code_that_arrives_as_text_still_counts_as_ok(): void
    {
        $this->pager();
        $this->transients(['pg_api_token' => 'tok']);
        $this->responses([['code' => '200', 'body' => '{}']]);
        expect('wp_die')->once();
        $this->expectOutputString('');

        \pg_ajax_api();
    }

    public function test_a_review_with_nothing_filled_in_prints_a_zero_rating_no_comment_and_no_date(): void
    {
        $this->pager();
        $this->transients(['pg_api_token' => 'tok']);
        // Escaping functions take text, so a rating that is not turned into text first fails here.
        $text = static fn (mixed $value): string => is_string($value) ? $value : throw new \LogicException('not text');
        when('esc_attr')->alias($text);
        when('esc_html')->alias($text);

        $body = json_encode(['data' => ['entities' => [['comments' => [[], ['overallRating' => ['value' => 4], 'comment' => 'Ok']]]]]]);
        $this->assertIsString($body);
        $this->responses([['code' => 200, 'body' => $body]]);
        expect('wp_die')->once();

        ob_start();
        \pg_ajax_api();
        $html = (string) ob_get_clean();

        $this->assertStringContainsString('style="width: 0%;"', $html);
        $this->assertStringContainsString('itemprop="ratingValue">0<span', $html);
        $this->assertStringContainsString('<p class="card-text"></p>', $html);
        $this->assertStringContainsString('style="width: 80%;"', $html);
        $this->assertStringContainsString('itemprop="ratingValue">4<span', $html);
        $this->assertSame(2, preg_match_all('#<h4 class="sr-only">Date</h4>\s*</div>#', $html), 'neither review has a date to print');
        $this->assertStringNotContainsString('1970', $html);
    }

    public function test_the_reviews_of_the_requested_page_are_printed_as_cards(): void
    {
        $this->pager(['post_id' => '12', 'page' => '3']);
        $this->transients(['pg_api_token' => 'tok']);
        $body = json_encode(['data' => ['entities' => [['comments' => [
            ['overallRating' => ['value' => 4], 'comment' => 'Great visit', 'mentionTime' => '2026-01-15T10:00:00Z'],
            ['overallRating' => ['value' => 2.5], 'comment' => 'Fine'],
        ]]]]]);
        $this->assertIsString($body);
        $requests = $this->responses([['code' => 200, 'body' => $body]]);
        expect('wp_die')->once();

        ob_start();
        \pg_ajax_api();
        $html = (string) ob_get_clean();

        $this->assertSame(
            ['https://api1.consumerism.pressganey.com/api/bsr/comments?personId=1234567890&perPage=10&days=540&page=3', ['timeout' => 15, 'headers' => ['Content-Type' => 'application/json', 'Access-Token' => 'tok']]],
            $requests[0]
        );
        $this->assertSame(2, substr_count($html, '<div class="card">'));
        $this->assertStringContainsString('style="width: 80%;"', $html);
        $this->assertStringContainsString('style="width: 50%;"', $html);
        $this->assertStringContainsString('itemprop="ratingValue">4<span class="sr-only"> out of 5</span>', $html);
        $this->assertStringContainsString('itemprop="ratingValue">2.5<span', $html);
        $this->assertStringContainsString('<p class="card-text">Great visit</p>', $html);
        $this->assertStringContainsString('Jan 15, 2026', $html);
        $this->assertSame(1, substr_count($html, 'Jan 15, 2026'), 'a review with no time prints no date');
    }

    /**
     * @return array<string, array{0: array<string, mixed>, 1: string}>
     */
    public static function pages_requested(): array
    {
        return [
            'no page' => [['post_id' => '12'], 'page=1'],
            'page zero' => [['post_id' => '12', 'page' => '0'], 'page=1'],
            'a negative page' => [['post_id' => '12', 'page' => '-4'], 'page=4'],
            'page two' => [['post_id' => '12', 'page' => '2'], 'page=2'],
        ];
    }

    /**
     * @param  array<string, mixed>  $post
     */
    #[DataProvider('pages_requested')]
    public function test_the_page_defaults_to_the_first_and_is_never_below_it(array $post, string $expected): void
    {
        $this->pager($post);
        $this->transients(['pg_api_token' => 'tok']);
        $requests = $this->responses([['code' => 200, 'body' => '{}']]);
        when('wp_die')->justReturn();
        $this->expectOutputString('');

        \pg_ajax_api();

        $this->assertStringEndsWith('&'.$expected, $requests[0][0]);
    }

    public function test_a_reply_with_no_reviews_prints_nothing(): void
    {
        $this->pager();
        $this->transients(['pg_api_token' => 'tok']);
        $this->responses([['code' => 200, 'body' => '{"data":{"entities":[]}}']]);
        expect('wp_die')->once();
        $this->expectOutputString('');

        \pg_ajax_api();
    }

    // Small admin helpers.

    public function test_related_pages_are_limited_to_top_level_pages_other_than_this_one(): void
    {
        $this->assertSame(
            ['post_type' => 'page', 'post_parent' => 0, 'post__not_in' => [42]],
            \limit_to_post_parent(['post_type' => 'page', 'post_parent' => 7], ['key' => 'f'], 42)
        );
    }

    public function test_the_genesis_term_meta_boxes_are_removed_and_the_description_hidden_for_each_taxonomy(): void
    {
        $removed = [];
        $added = [];
        when('remove_action')->alias(static function (string $hook, string $callback, int $priority) use (&$removed): void {
            $removed[] = [$hook, $callback, $priority];
        });
        when('add_action')->alias(static function (string $hook, string $callback) use (&$added): void {
            $added[] = [$hook, $callback];
        });

        \uamswp_remove_genesis_term_meta();

        $expected_removed = [];
        $expected_added = [];
        foreach (['condition', 'treatment', 'portal'] as $taxonomy) {
            foreach (['genesis_taxonomy_archive_options', 'genesis_taxonomy_seo_options', 'genesis_taxonomy_layout_options'] as $callback) {
                $expected_removed[] = ["{$taxonomy}_edit_form", $callback, 10];
            }

            $expected_added[] = ["{$taxonomy}_edit_form", 'remove_description_form'];
            $expected_added[] = ["{$taxonomy}_add_form", 'remove_description_form'];
        }

        $this->assertSame($expected_removed, $removed);
        $this->assertSame($expected_added, $added);
    }

    public function test_the_description_field_is_hidden_with_a_style_rule(): void
    {
        $this->expectOutputString('<style> .term-description-wrap { display:none; } </style>');

        \remove_description_form();
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function column_filters(): array
    {
        return [
            'conditions' => ['manage_edit-condition_columns'],
            'treatments' => ['manage_edit-treatment_columns'],
        ];
    }

    #[DataProvider('column_filters')]
    public function test_the_description_column_is_dropped_from_the_term_lists(string $hook): void
    {
        $filter = $this->closure('filter', $hook);

        $this->assertSame(['cb' => 'Box', 'name' => 'Name'], $filter(['cb' => 'Box', 'name' => 'Name', 'description' => 'Description']));
        $this->assertSame(['cb' => 'Box', 'name' => 'Name'], $filter(['cb' => 'Box', 'name' => 'Name']));
    }

    // Relevanssi.

    /**
     * @param  array<string, string>  $fields
     */
    private function termFields(array $fields): void
    {
        when('get_field')->alias(static fn (string $field, string $post_id): mixed => $fields[$post_id.':'.$field] ?? '');
    }

    public function test_a_post_is_not_a_term_and_keeps_its_content(): void
    {
        expect('get_field')->never();

        $this->assertSame('body', \rlv_tax_excerpt_term_fields('body', new \stdClass));
    }

    public function test_condition_fields_are_added_to_the_term_content(): void
    {
        $this->termFields(['condition_7:condition_alternate' => 'Alt', 'condition_7:condition_content' => 'Cont']);
        $term = (object) ['term_id' => 7, 'taxonomy' => 'condition'];

        $this->assertSame('baseAlt Cont', \rlv_tax_excerpt_term_fields('base', $term));
    }

    public function test_treatment_fields_are_added_to_the_term_content(): void
    {
        $this->termFields(['treatment_9:treatment_procedure_alternate' => 'Other', 'treatment_9:treatment_procedure_content' => 'Body']);
        $term = (object) ['term_id' => 9, 'taxonomy' => 'treatment'];

        $this->assertSame('baseOther Body', \rlv_tax_excerpt_term_fields('base', $term));
    }

    public function test_either_condition_field_alone_is_enough(): void
    {
        $this->termFields(['condition_7:condition_content' => 'Cont']);
        $term = (object) ['term_id' => 7, 'taxonomy' => 'condition'];
        $this->assertSame('base Cont', \rlv_tax_excerpt_term_fields('base', $term));

        $this->termFields(['condition_7:condition_alternate' => 'Alt']);
        $this->assertSame('baseAlt ', \rlv_tax_excerpt_term_fields('base', $term));
    }

    public function test_either_treatment_field_alone_is_enough(): void
    {
        $term = (object) ['term_id' => 9, 'taxonomy' => 'treatment'];
        $this->termFields(['treatment_9:treatment_procedure_content' => 'Body']);
        $this->assertSame('base Body', \rlv_tax_excerpt_term_fields('base', $term));

        $this->termFields(['treatment_9:treatment_procedure_alternate' => 'Other']);
        $this->assertSame('baseOther ', \rlv_tax_excerpt_term_fields('base', $term));
    }

    public function test_a_term_with_neither_set_of_fields_keeps_its_content(): void
    {
        $this->termFields([]);

        $this->assertSame('base', \rlv_tax_excerpt_term_fields('base', (object) ['term_id' => 7, 'taxonomy' => 'condition']));
    }

    public function test_an_excerpt_term_takes_its_taxonomy_from_the_post_type(): void
    {
        $this->termFields(['condition_7:condition_content' => 'Cont']);
        $term = (object) ['term_id' => 7, 'post_type' => 'condition'];

        $this->assertSame('base Cont', \rlv_tax_excerpt_term_fields('base', $term));
        $this->assertSame('condition', $term->taxonomy);
    }

    public function test_content_that_is_not_text_becomes_empty_text(): void
    {
        $this->termFields(['condition_7:condition_content' => 'Cont']);

        $this->assertSame(' Cont', \rlv_tax_excerpt_term_fields(null, (object) ['term_id' => 7, 'taxonomy' => 'condition']));
    }

    // AJAX scripts.

    /**
     * @param  list<string>  $singular
     * @return \ArrayObject<int, array<int, mixed>>
     */
    private function ajaxPage(array $singular): \ArrayObject
    {
        $calls = new \ArrayObject;
        when('is_singular')->alias(static fn (string $type): bool => in_array($type, $singular, true));
        when('admin_url')->alias(static fn (string $path): string => 'https://example.test/wp-admin/'.$path);
        when('wp_create_nonce')->alias(static fn (string $action): string => 'nonce:'.$action);
        when('wp_register_script')->alias(static function (string $handle, string $src, array $deps, mixed $version, bool $footer) use ($calls): void {
            $calls[] = ['register', $handle, $src, $deps, $version, $footer];
        });
        when('wp_localize_script')->alias(static function (string $handle, string $name, array $data) use ($calls): void {
            $calls[] = ['localize', $handle, $name, $data];
        });
        when('wp_enqueue_script')->alias(static function (string $handle) use ($calls): void {
            $calls[] = ['enqueue', $handle];
        });

        return $calls;
    }

    /**
     * @return array<string, array{0: list<string>}>
     */
    public static function region_pages(): array
    {
        return [
            'an area of expertise' => [['expertise']],
            'a condition' => [['condition']],
            'a treatment' => [['treatment']],
            'a clinical resource' => [['clinical-resource']],
        ];
    }

    /**
     * @param  list<string>  $singular
     */
    #[DataProvider('region_pages')]
    public function test_the_region_filter_script_loads_on_the_content_pages(array $singular): void
    {
        $calls = $this->ajaxPage($singular);

        \uamswp_ajax_scripts();

        $this->assertSame([
            ['register', 'uamswp-region-filter', UAMS_FAD_ROOT_URL.'assets/js/uamswp-region-filter.js', ['jquery'], false, true],
            ['localize', 'uamswp-region-filter', 'uamswp_region_filter', ['ajaxurl' => 'https://example.test/wp-admin/admin-ajax.php', 'security' => 'nonce:load_more_posts']],
            ['enqueue', 'uamswp-region-filter'],
        ], $calls->getArrayCopy());
    }

    public function test_the_location_page_loads_the_title_and_schedule_filters(): void
    {
        $calls = $this->ajaxPage(['location']);
        $data = ['ajaxurl' => 'https://example.test/wp-admin/admin-ajax.php', 'security' => 'nonce:load_more_posts'];

        \uamswp_ajax_scripts();

        $this->assertSame([
            ['register', 'uamswp-title-filter', UAMS_FAD_ROOT_URL.'assets/js/uamswp-title-filter.js', ['jquery'], false, true],
            ['register', 'uamswp-schedule-filter', UAMS_FAD_ROOT_URL.'assets/js/uamswp-schedule.js', ['jquery'], false, true],
            ['localize', 'uamswp-title-filter', 'uamswp_ajax_scripts', $data],
            ['localize', 'uamswp-schedule-filter', 'uamswp_ajax_scripts', $data],
            ['enqueue', 'uamswp-title-filter'],
            ['enqueue', 'uamswp-schedule-filter'],
        ], $calls->getArrayCopy());
    }

    public function test_the_provider_page_loads_the_review_pager_with_its_own_nonce(): void
    {
        $calls = $this->ajaxPage(['provider']);

        \uamswp_ajax_scripts();

        $this->assertSame([
            ['register', 'uamswp-pg-pagination', UAMS_FAD_ROOT_URL.'assets/js/uamswp-pg-ajax.js', ['jquery'], false, true],
            ['localize', 'uamswp-pg-pagination', 'uamswp_ajax_scripts', ['ajaxurl' => 'https://example.test/wp-admin/admin-ajax.php', 'security' => 'nonce:pg_pagination_posts']],
            ['enqueue', 'uamswp-pg-pagination'],
        ], $calls->getArrayCopy());
    }

    public function test_other_pages_load_no_ajax_scripts(): void
    {
        $calls = $this->ajaxPage(['page']);

        \uamswp_ajax_scripts();

        $this->assertSame([], $calls->getArrayCopy());
    }
}
