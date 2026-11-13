<?php

/**
 * Pins the template routing, page templates, rewrite rules and body class (class.fad-templates.php).
 *
 * The file registers its hooks at file scope, so it loads once per process and the registrations are
 * captured on that first load.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

final class TemplatesTest extends UnitTestCase
{
    /** @var list<array{0: string, 1: mixed}> */
    private static array $actions = [];

    /** @var list<array{0: string, 1: mixed}> */
    private static array $filters = [];

    private static bool $loaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (! defined('WP_PLUGIN_DIR')) {
            define('WP_PLUGIN_DIR', dirname(__DIR__, 3));
        }

        if (self::$loaded) {
            return;
        }

        when('add_action')->alias(static function (string $hook, mixed $callback): void {
            self::$actions[] = [$hook, $callback];
        });
        when('add_filter')->alias(static function (string $hook, mixed $callback): void {
            self::$filters[] = [$hook, $callback];
        });

        require_once dirname(__DIR__, 2).'/includes/class.fad-templates.php';
        self::$loaded = true;
    }

    private function template(string $file): string
    {
        return WP_PLUGIN_DIR.'/'.basename(dirname(__DIR__, 2)).'/templates/'.$file;
    }

    /**
     * Makes every conditional tag answer false except the ones named.
     *
     * @param  list<string>  $true  Tags to answer true, as `tag:argument`.
     */
    private function conditions(array $true): void
    {
        foreach (['is_post_type_archive', 'is_singular', 'is_tax'] as $tag) {
            when($tag)->alias(static fn (string $type): bool => in_array($tag.':'.$type, $true, true));
        }
    }

    public function test_the_hooks_are_registered(): void
    {
        $this->assertSame([
            ['template_include', 'uamswp_force_template'],
            ['theme_page_templates', 'uamswp_taxonomy_archive_page_template'],
            ['page_template', 'uamswp_redirect_page_template'],
            ['query_vars', 'provider_image_query_var'],
            ['body_class', 'uamswp_fad_body_class'],
        ], self::$filters);

        $this->assertSame('init', self::$actions[0][0]);
        $this->assertInstanceOf(\Closure::class, self::$actions[0][1]);
        $this->assertSame(['init', 'provider_image_rewrite_rule'], self::$actions[1]);
        $this->assertSame(['template_redirect', 'provider_image_rewrite_templates'], self::$actions[2]);
        $this->assertSame('template_redirect', self::$actions[3][0]);
        $this->assertInstanceOf(\Closure::class, self::$actions[3][1]);
        $this->assertCount(4, self::$actions);
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function routes(): array
    {
        return [
            'provider archive' => ['is_post_type_archive:provider', 'archive-physicians.php'],
            'provider single' => ['is_singular:provider', 'single-physicians.php'],
            'location archive' => ['is_post_type_archive:location', 'archive-locations.php'],
            'location single' => ['is_singular:location', 'single-locations.php'],
            'expertise archive' => ['is_post_type_archive:expertise', 'archive-expertise.php'],
            'expertise single' => ['is_singular:expertise', 'single-expertise.php'],
            'condition taxonomy' => ['is_tax:condition', 'taxonomy-condition.php'],
            'condition single' => ['is_singular:condition', 'single-condition.php'],
            'condition archive' => ['is_post_type_archive:condition', 'archive-condition.php'],
            'treatment taxonomy' => ['is_tax:treatment', 'taxonomy-treatment_procedure.php'],
            'treatment single' => ['is_singular:treatment', 'single-treatment.php'],
            'treatment archive' => ['is_post_type_archive:treatment', 'archive-treatments.php'],
            'clinical resource single' => ['is_singular:clinical-resource', 'single-clinical-resource.php'],
            'clinical resource archive' => ['is_post_type_archive:clinical-resource', 'archive-clinical-resource.php'],
        ];
    }

    #[DataProvider('routes')]
    public function test_each_post_type_view_is_forced_to_the_plugin_template(string $condition, string $file): void
    {
        $this->conditions([$condition]);

        $this->assertSame($this->template($file), \uamswp_force_template('theme.php'));
    }

    public function test_any_other_view_keeps_the_theme_template(): void
    {
        $this->conditions([]);

        $this->assertSame('theme.php', \uamswp_force_template('theme.php'));
    }

    public function test_the_template_name_is_taken_from_the_plugin_folder(): void
    {
        $this->conditions(['is_singular:provider']);

        $this->assertStringEndsWith('/'.basename(dirname(__DIR__, 2)).'/templates/single-physicians.php', \uamswp_force_template(null));
    }

    public function test_the_page_template_list_gains_three_entries(): void
    {
        $this->assertSame([
            'existing.php' => 'Existing',
            'archive-taxonomy-conditions.php' => 'Conditions Archive',
            'archive-taxonomy-treatments.php' => 'Treatments Archive',
            'provider-image.php' => 'Provider Image',
        ], \uamswp_taxonomy_archive_page_template(['existing.php' => 'Existing']));
    }

    /**
     * @return array<string, array{0: mixed, 1: string|null}>
     */
    public static function page_templates(): array
    {
        return [
            'conditions archive' => ['archive-taxonomy-conditions.php', 'archive-taxonomy-conditions.php'],
            'treatments archive' => ['archive-taxonomy-treatments.php', 'archive-taxonomy-treatments.php'],
            'provider image' => ['provider-image.php', 'provider-image.php'],
            'a theme folder path' => ['templates/provider-image.php', 'provider-image.php'],
            'another template' => ['page.php', null],
            'nothing stored' => ['', null],
            'a non-string' => [false, null],
        ];
    }

    #[DataProvider('page_templates')]
    public function test_a_page_template_is_redirected_to_the_plugin_file(mixed $stored, ?string $file): void
    {
        when('get_post')->justReturn((object) ['ID' => 7]);
        expect('get_post_meta')->once()->with(7, '_wp_page_template', true)->andReturn($stored);

        $this->assertSame($file === null ? 'theme.php' : $this->template($file), \uamswp_redirect_page_template('theme.php'));
    }

    public function test_a_missing_post_reads_the_meta_of_id_zero(): void
    {
        when('get_post')->justReturn();
        expect('get_post_meta')->once()->with(0, '_wp_page_template', true)->andReturn('');

        $this->assertSame('theme.php', \uamswp_redirect_page_template('theme.php'));
    }

    public function test_the_rewrite_rules_send_pagination_and_the_provider_image_to_their_queries(): void
    {
        $rules = [];
        when('add_rewrite_rule')->alias(static function (string $regex, string $query, string $after) use (&$rules): void {
            $rules[] = [$regex, $query, $after];
        });

        (self::$actions[0][1])();
        \provider_image_rewrite_rule();

        $this->assertSame([
            ['(.?.+?)/page/?([0-9]{1,})/?$', 'index.php?pagename=$matches[1]&paged=$matches[2]', 'top'],
            ['provider/([^/]+)/image', 'index.php?provid=$matches[1]&pagename=image&image=yes', 'top'],
        ], $rules);
    }

    public function test_the_image_and_provider_query_vars_are_added(): void
    {
        $this->assertSame(['page', 'image', 'provid'], \provider_image_query_var(['page']));
    }

    public function test_an_image_request_swaps_in_the_image_template(): void
    {
        when('get_query_var')->alias(static fn (string $var): string => $var === 'image' ? 'yes' : '');
        $added = [];
        when('add_filter')->alias(static function (string $hook, \Closure $callback) use (&$added): void {
            $added[] = [$hook, $callback];
        });

        \provider_image_rewrite_templates();

        $this->assertCount(1, $added);
        $this->assertSame('template_include', $added[0][0]);
        $this->assertSame($this->template('provider-image.php'), ($added[0][1])());
    }

    public function test_a_request_without_the_image_var_adds_no_filter(): void
    {
        when('get_query_var')->justReturn('');
        expect('add_filter')->never();

        \provider_image_rewrite_templates();

        $this->addToAssertionCount(1);
    }

    public function test_condition_and_treatment_taxonomies_get_the_default_page_class(): void
    {
        $this->conditions(['is_tax:condition']);
        $this->assertSame(['a', 'page-template-default'], \uamswp_fad_body_class(['a']));

        $this->conditions(['is_tax:treatment']);
        $this->assertSame(['a', 'page-template-default'], \uamswp_fad_body_class(['a']));
    }

    public function test_other_views_keep_their_body_classes(): void
    {
        $this->conditions([]);

        $this->assertSame(['a'], \uamswp_fad_body_class(['a']));
    }

    public function test_the_redirect_hook_leaves_other_requests_alone(): void
    {
        global $wp_query;
        $previous = $wp_query;
        $wp_query = new class
        {
            public function get(string $name): string
            {
                return 'page';
            }
        };
        when('is_404')->justReturn(true);
        expect('wp_redirect')->never();

        try {
            (self::$actions[3][1])();
        } finally {
            $wp_query = $previous;
        }

        $this->addToAssertionCount(1);
    }

    public function test_a_provider_that_is_found_is_not_redirected(): void
    {
        global $wp_query;
        $previous = $wp_query;
        $wp_query = new class
        {
            public function get(string $name): string
            {
                return 'provider';
            }
        };
        when('is_404')->justReturn(false);
        expect('wp_redirect')->never();

        try {
            (self::$actions[3][1])();
        } finally {
            $wp_query = $previous;
        }

        $this->addToAssertionCount(1);
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function missing_archive_types(): array
    {
        return [
            'a provider' => ['provider', 'https://example.test/provider/'],
            'a location' => ['location', 'https://example.test/location/'],
        ];
    }

    #[DataProvider('missing_archive_types')]
    public function test_a_provider_or_location_that_is_not_found_is_sent_to_its_archive_page(string $type, string $archive): void
    {
        global $wp_query;
        $previous = $wp_query;
        $wp_query = new class($type)
        {
            public function __construct(private readonly string $type) {}

            public function get(string $name): string
            {
                return $this->type;
            }
        };
        when('is_404')->justReturn(true);
        when('uamswp_fad_str')->returnArg();
        expect('get_post_type_archive_link')->once()->with($type)->andReturn($archive);
        // wp_redirect() is followed by exit; the sentinel ends the callback where the exit would.
        expect('wp_redirect')->once()->with($archive, 301)->andThrow(new \RuntimeException('redirected'));

        try {
            (self::$actions[3][1])();
            $this->fail('The request was not redirected.');
        } catch (\RuntimeException $runtimeException) {
            $this->assertSame('redirected', $runtimeException->getMessage());
        } finally {
            $wp_query = $previous;
        }
    }

    public function test_a_type_with_no_archive_page_is_redirected_to_an_empty_address(): void
    {
        global $wp_query;
        $previous = $wp_query;
        $wp_query = new class
        {
            public function get(string $name): string
            {
                return 'provider';
            }
        };
        when('is_404')->justReturn(true);
        when('uamswp_fad_str')->returnArg();
        when('get_post_type_archive_link')->justReturn(false);
        // The address is cast to a string, so a type without an archive reaches wp_redirect() as '' and not as false.
        expect('wp_redirect')->once()->with(Mockery::mustBe(''), 301)->andThrow(new \RuntimeException('redirected'));

        try {
            (self::$actions[3][1])();
            $this->fail('The request was not redirected.');
        } catch (\RuntimeException $runtimeException) {
            $this->assertSame('redirected', $runtimeException->getMessage());
        } finally {
            $wp_query = $previous;
        }
    }
}
