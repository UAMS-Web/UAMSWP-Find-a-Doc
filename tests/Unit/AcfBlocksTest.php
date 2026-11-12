<?php

/**
 * Pins the ACF block helpers, the block registration and the three block field groups (class.fad-acf-blocks.php).
 *
 * The file adds its field groups at file scope when ACF is present, so it loads once per process with the
 * ACF functions stubbed, and the groups are captured on that first load.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

require_once __DIR__.'/../Support/acf-functions.php';

final class AcfBlocksTest extends UnitTestCase
{
    /** @var list<array<string, mixed>> */
    private static array $groups = [];

    /** @var list<array{0: string, 1: string}> */
    private static array $actions = [];

    private static bool $loaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        // The ACF functions file is loaded once per process under Brain Monkey's own hook functions, and it
        // changes what it does when ACF functions exist. Load it before this file defines any, so a test
        // that sorts later and relies on its registrations sees the same file it always has.
        \uamswp_test_acf_functions();

        Functions\when('__')->returnArg();
        Functions\when('esc_attr')->returnArg();
        Functions\when('esc_url')->returnArg();
        Functions\when('esc_html')->returnArg();
        Functions\when('esc_html__')->returnArg();

        if (self::$loaded) {
            return;
        }

        Functions\when('acf_add_local_field_group')->alias(static function (array $group): void {
            self::$groups[] = $group;
        });
        Functions\when('add_action')->alias(static function (string $hook, string $callback): void {
            self::$actions[] = [$hook, $callback];
        });

        require_once dirname(__DIR__, 2).'/includes/class.fad-acf-blocks.php';
        self::$loaded = true;
    }

    public function test_the_blocks_are_registered_on_acf_init(): void
    {
        $this->assertSame([['acf/init', 'uams_fad_register_blocks']], self::$actions);
    }

    /**
     * @return array<string, array{0: mixed, 1: string}>
     */
    public static function targets(): array
    {
        return [
            'null' => [null, ''],
            'empty' => ['', ''],
            'a non-string' => [['_blank'], ''],
            'blank' => ['_blank', ' target="_blank" rel="noopener"'],
            'self' => ['_self', ' target="_self"'],
        ];
    }

    #[DataProvider('targets')]
    public function test_the_target_attributes_follow_the_stored_target(mixed $target, string $expected): void
    {
        $this->assertSame($expected, \uams_fad_link_target_attributes($target));
    }

    public function test_the_button_anchor_carries_the_link_label_color_and_tracking_title(): void
    {
        $this->assertSame(
            '<a href="https://example.org/a" aria-label="Learn more about A" class=" btn btn-primary" target="_blank" rel="noopener" data-moduletitle="Heading">Learn more</a>',
            \uams_fad_cta_button_anchor(['url' => 'https://example.org/a', 'target' => '_blank'], 'Learn more', 'Learn more about A', 'primary', 'small', 'Heading')
        );
    }

    public function test_a_large_button_adds_the_large_class_and_a_missing_link_leaves_the_anchor_empty(): void
    {
        $this->assertSame(
            '<a href="" aria-label="d" class=" btn btn-secondary btn-lg" data-moduletitle="h">t</a>',
            \uams_fad_cta_button_anchor(false, 't', 'd', 'secondary', 'large', 'h')
        );
        $this->assertSame(
            '<a href="" aria-label="d" class=" btn btn-secondary" data-moduletitle="h">t</a>',
            \uams_fad_cta_button_anchor(['url' => 12, 'target' => ''], 't', 'd', 'secondary', 'small', 'h')
        );
    }

    /**
     * @return array<string, array{0: mixed, 1: mixed}>
     */
    public static function not_previews(): array
    {
        return [
            'not a preview' => [['title' => 'T', 'data' => []], false],
            'no block' => [null, true],
            'empty block' => [[], true],
            'a non-array block' => ['block', true],
        ];
    }

    #[DataProvider('not_previews')]
    public function test_outside_a_block_preview_nothing_is_printed(mixed $block, mixed $is_preview): void
    {
        $this->expectOutputString('');

        $this->assertFalse(\uams_fad_block_placeholder($block, $is_preview));
    }

    public function test_the_defaults_print_nothing(): void
    {
        $this->expectOutputString('');

        $this->assertFalse(\uams_fad_block_placeholder());
    }

    /**
     * @return array<string, array{0: array<string, mixed>}>
     */
    public static function filled_blocks(): array
    {
        return [
            'text' => [['title' => 'T', 'data' => ['heading' => 'Hello']]],
            'a number' => [['title' => 'T', 'data' => ['count' => 3]]],
            'a list' => [['title' => 'T', 'data' => ['ids' => [1]]]],
            'true' => [['title' => 'T', 'data' => ['more' => true]]],
            'a numbered key' => [['title' => 'T', 'data' => [0 => 'x']]],
        ];
    }

    /**
     * @param  array<string, mixed>  $block
     */
    #[DataProvider('filled_blocks')]
    public function test_a_block_with_content_prints_no_placeholder(array $block): void
    {
        $this->expectOutputString('');

        $this->assertFalse(\uams_fad_block_placeholder($block, true));
    }

    public function test_an_empty_block_prints_the_placeholder_with_its_title(): void
    {
        $this->expectOutputString(
            '<div class="uams-block-placeholder" style="padding:1.5rem;border:1px dashed currentColor;text-align:center;"><strong>UAMS Providers</strong><br>Click the pencil icon in the block toolbar to add content.</div>'
        );

        $this->assertTrue(\uams_fad_block_placeholder([
            'title' => 'UAMS Providers',
            'data' => ['heading' => '', 'count' => null, 'ids' => [], 'more' => false, 'zero' => '0', '_heading' => 'field_1'],
        ], true));
    }

    public function test_a_block_with_no_data_or_title_prints_the_default_title(): void
    {
        $this->expectOutputRegex('/<strong>Find-a-Doc block<\/strong>/');

        $this->assertTrue(\uams_fad_block_placeholder(['name' => 'x'], true));
    }

    public function test_a_block_whose_data_is_not_an_array_counts_as_empty(): void
    {
        $this->expectOutputRegex('/<strong>T<\/strong>/');

        $this->assertTrue(\uams_fad_block_placeholder(['title' => 'T', 'data' => 'not an array'], 1));
    }

    public function test_a_blank_title_falls_back_to_the_default(): void
    {
        $this->expectOutputRegex('/<strong>Find-a-Doc block<\/strong>/');

        $this->assertTrue(\uams_fad_block_placeholder(['title' => '', 'data' => []], true));
    }

    public function test_the_three_blocks_are_registered_with_their_templates(): void
    {
        $blocks = [];
        Functions\when('acf_register_block_type')->alias(static function (array $block) use (&$blocks): void {
            $blocks[] = $block;
        });

        \uams_fad_register_blocks();

        $this->assertSame([
            [
                'name' => 'fad-locations',
                'title' => 'UAMS Find-a-Doc Locations',
                'description' => 'Filtered Clinical Locations',
                'category' => 'common',
                'icon' => 'location',
                'keywords' => ['uams', 'clinical', 'locations', 'clinics'],
                'acf_block_version' => 3,
                'align' => 'full',
                'render_template' => UAMS_FAD_PATH.'/templates/blocks/locations.php',
            ],
            [
                'name' => 'fad-providers',
                'title' => 'UAMS Find-a-Doc Providers',
                'description' => 'Filtered Providers',
                'category' => 'common',
                'icon' => 'id',
                'keywords' => ['uams', 'providers', 'doctors', 'physicians'],
                'acf_block_version' => 3,
                'align' => 'full',
                'render_template' => UAMS_FAD_PATH.'templates/blocks/providers.php',
            ],
            [
                'name' => 'fad-recognitions',
                'title' => 'Provider Recognition List',
                'description' => 'List of providers from a Recognition List',
                'category' => 'common',
                'icon' => 'awards',
                'keywords' => ['uams', 'providers', 'doctors', 'awards'],
                'acf_block_version' => 3,
                'align' => '',
                'render_template' => UAMS_FAD_PATH.'templates/blocks/recognitions.php',
            ],
        ], $blocks);
    }

    public function test_the_three_block_field_groups_are_added_for_their_blocks(): void
    {
        $this->assertSame(
            ['group_block_fad_locations', 'group_block_fad_providers', 'group_block_fad_recognition'],
            array_column(self::$groups, 'key')
        );
        $this->assertSame(
            ['acf/fad-locations', 'acf/fad-providers', 'acf/fad-recognitions'],
            array_map(static fn (array $group): mixed => $group['location'][0][0]['value'], self::$groups)
        );

        foreach (self::$groups as $group) {
            $this->assertSame(['param' => 'block', 'operator' => '=='], array_diff_key($group['location'][0][0], ['value' => 1]));
            $this->assertSame(['normal', 'top', true], [$group['position'], $group['label_placement'], $group['active']]);
        }
    }

    public function test_the_field_names_of_each_group_are_pinned(): void
    {
        $names = array_map(
            static fn (array $group): array => array_values(array_filter(array_column($group['fields'], 'name'))),
            self::$groups
        );

        $this->assertSame([
            [
                'block_fad_locations_heading',
                'block_fad_locations_description',
                'block_fad_locations_filter_type',
                'block_fad_locations_filter_aoe',
                'block_fad_locations_filter_region',
                'block_fad_locations_filter_ids',
                'block_fad_locations_background_color',
                'block_fad_locations_more',
                'block_fad_locations_more_text',
                'block_fad_locations_more_button_text',
                'block_fad_locations_more_button_url',
                'block_fad_locations_more_button_description',
            ],
            [
                'block_fad_providers_heading',
                'block_fad_providers_description',
                'block_fad_providers_filter_aoe',
                'block_fad_providers_filter_location',
                'block_fad_providers_filter_region',
                'block_fad_providers_filter_ids',
                'block_fad_providers_count',
                'block_fad_providers_background_color',
                'block_fad_providers_more',
                'block_fad_providers_more_text',
                'block_fad_providers_more_button_text',
                'block_fad_providers_more_button_url',
                'block_fad_providers_more_button_description',
            ],
            ['select_recognition_list'],
        ], $names);
    }

    public function test_the_whole_definition_of_every_group_is_pinned(): void
    {
        // The groups are declarative data; a snapshot catches a change to any key, label, choice or default.
        $this->assertSame(
            [
                '8af4da2b8bb59a40a6109f2aa944a1bbfed67f0d',
                '8fc21ac23076f0eb339b21ddc2b3453fccfbb694',
                'b26080fb00d8e340ed0f8c11719b84771f5ed217',
            ],
            array_map(static fn (array $group): string => sha1((string) json_encode($group)), self::$groups)
        );
    }
}
