<?php

/**
 * Pins the Find-a-Doc options pages and the MyChart field validators (class.fad-settings-pages.php).
 *
 * The file registers everything at file scope, so it loads once per process and the calls it makes are
 * captured on that first load.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class SettingsPagesTest extends UnitTestCase
{
    /** @var list<array<string, mixed>> */
    private static array $pages = [];

    /** @var list<array<string, mixed>> */
    private static array $sub_pages = [];

    /** @var list<array{0: string, 1: string, 2: int, 3: int}> */
    private static array $filters = [];

    /** @var list<array{0: string, 1: string}> */
    private static array $actions = [];

    private static bool $loaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (self::$loaded) {
            return;
        }

        Functions\when('acf_add_options_page')->alias(static function (array $page): void {
            self::$pages[] = $page;
        });
        Functions\when('acf_add_options_sub_page')->alias(static function (array $page): void {
            self::$sub_pages[] = $page;
        });
        Functions\when('add_action')->alias(static function (string $hook, string $callback): void {
            self::$actions[] = [$hook, $callback];
        });
        Functions\when('add_filter')->alias(static function (string $hook, string $callback, int $priority, int $accepted): void {
            self::$filters[] = [$hook, $callback, $priority, $accepted];
        });

        require_once dirname(__DIR__, 2).'/includes/class.fad-settings-pages.php';
        self::$loaded = true;
    }

    public function test_the_top_level_page_is_for_administrators_and_does_not_redirect(): void
    {
        $this->assertSame([[
            'page_title' => 'Find-a-Doc Settings',
            'menu_title' => 'Find-a-Doc Settings',
            'menu_slug' => 'fad-settings',
            'capability' => 'manage_options',
            'redirect' => false,
        ]], self::$pages);
    }

    public function test_seven_sub_pages_hang_under_the_top_level_page_in_menu_order(): void
    {
        $this->assertSame([
            'uamswp-fad-providers',
            'uamswp-fad-locations',
            'uamswp-fad-expertise',
            'uamswp-fad-resources',
            'uamswp-fad-tax',
            'uamswp-fad-mychart',
            'uamswp-fad-remove-ontology',
        ], array_column(self::$sub_pages, 'menu_slug'));

        foreach (self::$sub_pages as $page) {
            $this->assertSame('fad-settings', $page['parent_slug'], $page['menu_slug']);
            $this->assertSame('manage_options', $page['capability'], $page['menu_slug']);
            $this->assertFalse($page['redirect'], $page['menu_slug']);
        }
    }

    public function test_each_sub_page_is_titled_for_its_menu_entry(): void
    {
        $this->assertSame([
            'uamswp-fad-providers' => ['Clinical Providers Options', 'Clinical Providers'],
            'uamswp-fad-locations' => ['Clinical Locations Options', 'Clinical Locations'],
            'uamswp-fad-expertise' => ['Areas of Expertise Options', 'Clinical Areas of Expertise'],
            'uamswp-fad-resources' => ['Clinical Resource Options', 'Clinical Resources'],
            'uamswp-fad-tax' => ['Conditions and Treatments Options', 'Conditions and Treatments'],
            'uamswp-fad-mychart' => ['MyChart Open Scheduling Options', 'MyChart Open Scheduling'],
            'uamswp-fad-remove-ontology' => ['Remove Medical Ontology Options', 'Remove Medical Ontology'],
        ], array_combine(
            array_column(self::$sub_pages, 'menu_slug'),
            array_map(static fn (array $page): array => [$page['page_title'], $page['menu_title']], self::$sub_pages)
        ));
    }

    public function test_the_google_key_and_the_validators_are_hooked(): void
    {
        $this->assertSame([['acf/init', 'my_acf_google_key']], self::$actions);
        $this->assertSame([
            ['acf/validate_value/name=mychart_scheduling_domain', 'uamswp_fad_validate_mychart_domain', 10, 4],
            ['acf/validate_value/name=mychart_scheduling_instance', 'uamswp_fad_validate_mychart_instance', 10, 4],
            ['acf/validate_value/name=mychart_scheduling_linksource', 'uamswp_fad_validate_mychart_domain', 10, 4],
        ], self::$filters);
    }

    public function test_a_saved_google_key_is_handed_to_acf(): void
    {
        Functions\when('get_field')->alias(static fn (string $field, string $id): string => $field === 'fad_google_key' && $id === 'option' ? 'KEY-123' : '');
        Functions\expect('acf_update_setting')->once()->with('google_api_key', 'KEY-123');

        \my_acf_google_key();
    }

    public function test_no_google_key_leaves_the_acf_setting_alone(): void
    {
        Functions\when('get_field')->justReturn('');
        Functions\expect('acf_update_setting')->never();

        \my_acf_google_key();

        $this->addToAssertionCount(1);
    }

    /**
     * @return array<string, array{0: mixed, 1: mixed}>
     */
    public static function domain_values(): array
    {
        $message = 'Enter a hostname only, such as mychart.example.org, with no scheme, path, or punctuation.';

        return [
            'empty is allowed' => ['', true],
            'whitespace only is allowed' => ['   ', true],
            'a hostname' => ['mychart.example.org', true],
            'a hostname with padding' => ['  mychart.example.org  ', true],
            'a hostname with a hyphen' => ['my-chart.uams.edu', true],
            'upper case' => ['MyChart.Example.ORG', true],
            'a single label' => ['mychart', $message],
            'a scheme' => ['https://mychart.example.org', $message],
            'a path' => ['mychart.example.org/path', $message],
            'a leading hyphen' => ['-mychart.example.org', $message],
            'a trailing hyphen' => ['mychart-.example.org', $message],
            'a leading dot' => ['.example.org', $message],
            'a non-string reads as empty' => [['x'], true],
        ];
    }

    #[DataProvider('domain_values')]
    public function test_the_domain_validator_accepts_only_a_bare_hostname(mixed $value, mixed $expected): void
    {
        $this->assertSame($expected, \uamswp_fad_validate_mychart_domain(true, $value, [], 'input'));
    }

    public function test_the_domain_validator_passes_an_earlier_failure_through_untouched(): void
    {
        $this->assertSame('Already wrong.', \uamswp_fad_validate_mychart_domain('Already wrong.', 'https://bad', [], 'input'));
        $this->assertFalse(\uamswp_fad_validate_mychart_domain(false, 'https://bad', [], 'input'));
    }

    /**
     * @return array<string, array{0: mixed, 1: mixed}>
     */
    public static function instance_values(): array
    {
        $message = 'Enter a single path segment, such as MyChartPRD, using letters, numbers, dot, underscore, or dash.';

        return [
            'empty is allowed' => ['', true],
            'a segment' => ['MyChartPRD', true],
            'a segment with padding' => ['  MyChartPRD  ', true],
            'dot underscore dash' => ['My.Chart_PRD-2', true],
            'a slash' => ['My/Chart', $message],
            'a space inside' => ['My Chart', $message],
            'a query' => ['MyChart?x=1', $message],
        ];
    }

    #[DataProvider('instance_values')]
    public function test_the_instance_validator_accepts_only_one_path_segment(mixed $value, mixed $expected): void
    {
        $this->assertSame($expected, \uamswp_fad_validate_mychart_instance(true, $value, [], 'input'));
    }

    public function test_the_instance_validator_passes_an_earlier_failure_through_untouched(): void
    {
        $this->assertSame('Already wrong.', \uamswp_fad_validate_mychart_instance('Already wrong.', 'a/b', [], 'input'));
    }
}
