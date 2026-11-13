<?php

/**
 * Pins the Area of Expertise attributes meta box (class.fad-expertise-attributes-meta-box.php).
 *
 * The plugin does not load this file today (its include is commented out), so the tests load it directly.
 * It builds its object at file scope, so the hooks are captured on that first load.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

final class ExpertiseAttributesMetaBoxTest extends UnitTestCase
{
    /** @var list<array{0: string, 1: array{0: object, 1: string}}> */
    private static array $actions = [];

    private static bool $loaded = false;

    /** @var array<string, mixed> */
    private array $post_before = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->post_before = $_POST;

        if (! self::$loaded) {
            when('add_action')->alias(static function (string $hook, array $callback): void {
                self::$actions[] = [$hook, $callback];
            });

            require_once dirname(__DIR__, 2).'/includes/class.fad-expertise-attributes-meta-box.php';
            self::$loaded = true;
        }

        when('_e')->alias(static function (string $text): void {
            echo $text;
        });
        when('__')->returnArg();
        // Marks a value that reaches it as a string, so a dropped cast or a dropped call shows in the markup.
        when('esc_attr')->alias(static fn (mixed $text): string => is_string($text) ? 'esc('.$text.')' : 'not-a-string');
        when('wp_nonce_field')->alias(static function (string $action, string $name): void {
            echo '[nonce '.$action.' '.$name.']';
        });
    }

    protected function tearDown(): void
    {
        $_POST = $this->post_before;

        parent::tearDown();
    }

    private function box(): \UAMS_Expertise_Attributes_Meta_Box
    {
        $object = self::$actions[0][1][0];
        $this->assertInstanceOf(\UAMS_Expertise_Attributes_Meta_Box::class, $object);

        return $object;
    }

    private function render(object $post): string
    {
        ob_start();
        $this->box()->expertise_attributes_meta_box($post);

        return (string) ob_get_clean();
    }

    public function test_the_meta_box_swaps_and_saves_on_the_post_hooks(): void
    {
        $this->assertSame(
            [['add_meta_boxes', 'replace_meta_box'], ['save_post', 'save_postdata']],
            array_map(static fn (array $action): array => [$action[0], $action[1][1]], self::$actions)
        );
        $this->assertSame(self::$actions[0][1][0], self::$actions[1][1][0]);
    }

    public function test_the_constants_describe_the_box(): void
    {
        $this->assertSame('pageparentdiv', \UAMS_Expertise_Attributes_Meta_Box::ID);
        $this->assertSame('Area of Expertise Attributes', \UAMS_Expertise_Attributes_Meta_Box::TITLE);
        $this->assertSame('expertise', \UAMS_Expertise_Attributes_Meta_Box::POSTTYPE);
        $this->assertSame('side', \UAMS_Expertise_Attributes_Meta_Box::POSITION);
        $this->assertSame('core', \UAMS_Expertise_Attributes_Meta_Box::PRIORITY);
    }

    public function test_replacing_the_box_removes_the_page_attributes_box_and_adds_its_own(): void
    {
        expect('remove_meta_box')->once()->with('pageparentdiv', 'expertise', 'side');
        expect('add_meta_box')->once()->with(
            'uamsexpertiseparentdiv',
            'Area of Expertise Attributes',
            [$this->box(), 'expertise_attributes_meta_box'],
            'expertise',
            'side',
            'core'
        );

        $this->box()->replace_meta_box();
    }

    public function test_a_flat_post_type_shows_the_sidebar_and_order_fields_only(): void
    {
        when('get_post_type_object')->justReturn((object) ['hierarchical' => false]);
        when('get_post_meta')->justReturn('');

        expect('wp_dropdown_pages')->never();

        $html = $this->render((object) ['ID' => 5, 'post_type' => 'expertise', 'post_parent' => 0, 'menu_order' => 3]);

        $this->assertStringNotContainsString('parent_id', $html);
        $this->assertStringNotContainsString('parent_nonce', $html);
        $this->assertStringContainsString('[nonce sidebar_nonce sidebar_name]', $html);
        $this->assertStringContainsString('name="sidebarcheck"', $html);
        $this->assertStringNotContainsString('checked="checked"', $html);
        $this->assertStringContainsString('value="esc(3)"', $html);
        $this->assertStringContainsString('<strong>Sidebar</strong>', $html);
        $this->assertStringContainsString('for="sidebar">Sidebar</label>', $html);
        $this->assertStringContainsString('/>No Sidebar</p>', $html);
        $this->assertStringContainsString('<strong>Order</strong>', $html);
        $this->assertStringContainsString('for="menu_order">Order</label>', $html);
        $this->assertStringNotContainsString('Need help?', $html);
    }

    public function test_a_stored_sidebar_flag_checks_the_box_and_is_echoed(): void
    {
        when('get_post_type_object')->justReturn();
        expect('get_post_meta')->once()->with(5, 'sidebar', true)->andReturn('on');

        $html = $this->render((object) ['ID' => 5, 'post_type' => 'expertise', 'post_parent' => 0, 'menu_order' => 0]);

        $this->assertStringContainsString('checked="checked"', $html);
        $this->assertStringContainsString('checked="checked" on />', $html);
    }

    public function test_a_hierarchical_post_type_shows_the_parent_dropdown(): void
    {
        when('get_post_type_object')->justReturn((object) ['hierarchical' => true]);
        when('get_post_meta')->justReturn('');
        expect('wp_dropdown_pages')->once()->with([
            'post_type' => 'expertise',
            'exclude_tree' => 5,
            'selected' => 9,
            'name' => 'parent_id',
            'show_option_none' => '(no parent)',
            'sort_column' => 'menu_order, post_title, sidebar, parent',
            'echo' => 0,
        ])->andReturn('<select id="parent_id"></select>');

        $html = $this->render((object) ['ID' => 5, 'post_type' => 'expertise', 'post_parent' => 9, 'menu_order' => 0]);

        $this->assertStringContainsString('<select id="parent_id"></select>', $html);
        $this->assertStringContainsString('[nonce parent_nonce parent_name]', $html);
        $this->assertStringContainsString('for="parent_id"', $html);
        $this->assertStringContainsString('<strong>Parent</strong>', $html);
        $this->assertStringContainsString('for="parent_id">Parent</label>', $html);
    }

    public function test_a_hierarchical_post_type_with_no_pages_shows_no_parent_dropdown(): void
    {
        when('get_post_type_object')->justReturn((object) ['hierarchical' => true]);
        when('get_post_meta')->justReturn('');
        when('wp_dropdown_pages')->justReturn('');

        $html = $this->render((object) ['ID' => 5, 'post_type' => 'expertise', 'post_parent' => 0, 'menu_order' => 0]);

        $this->assertStringNotContainsString('parent_nonce', $html);
        $this->assertStringNotContainsString('for="parent_id"', $html);
    }

    public function test_a_page_gets_the_help_hint(): void
    {
        when('get_post_type_object')->justReturn();
        when('get_post_meta')->justReturn('');

        $html = $this->render((object) ['ID' => 5, 'post_type' => 'page', 'post_parent' => 0, 'menu_order' => 0]);

        $this->assertStringContainsString('Need help? Use the Help tab in the upper right of your screen.', $html);
    }

    public function test_saving_another_post_type_changes_nothing_and_returns_the_id(): void
    {
        when('get_post_type')->justReturn('page');
        when('get_post_status')->justReturn('publish');

        expect('update_post_meta')->never();
        $_POST = ['sidebar_name' => 'n', 'sidebarcheck' => 'on'];

        $this->assertSame(12, $this->box()->save_postdata(12));
    }

    public function test_the_id_defaults_to_zero_and_a_numeric_string_is_cast(): void
    {
        expect('get_post_type')->once()->with(0)->andReturn('page');
        when('get_post_status')->justReturn(false);
        $this->assertSame(0, $this->box()->save_postdata());

        expect('get_post_type')->once()->with(15)->andReturn('page');
        $this->assertSame(15, $this->box()->save_postdata('15'));
    }

    public function test_a_checked_sidebar_box_is_stored(): void
    {
        when('get_post_type')->justReturn('expertise');
        when('get_post_status')->justReturn('publish');
        expect('check_admin_referer')->once()->with('sidebar_nonce', 'sidebar_name')->andReturn(true);
        expect('update_post_meta')->once()->with(12, 'sidebar', 'on');
        $_POST = ['sidebar_name' => 'n', 'sidebarcheck' => 'on'];

        $this->assertSame(12, $this->box()->save_postdata(12));
    }

    public function test_an_unchecked_sidebar_box_clears_the_flag(): void
    {
        when('get_post_type')->justReturn('expertise');
        when('get_post_status')->justReturn('publish');
        when('check_admin_referer')->justReturn(true);
        expect('update_post_meta')->once()->with(12, 'sidebar', null);
        $_POST = ['sidebar_name' => 'n'];

        $this->box()->save_postdata(12);
    }

    public function test_a_failed_sidebar_nonce_stores_nothing(): void
    {
        when('get_post_type')->justReturn('expertise');
        when('get_post_status')->justReturn('publish');
        when('check_admin_referer')->justReturn(false);

        expect('update_post_meta')->never();
        $_POST = ['sidebar_name' => 'n', 'sidebarcheck' => 'on'];

        $this->box()->save_postdata(12);

        $this->addToAssertionCount(1);
    }

    public function test_a_save_without_the_sidebar_nonce_field_checks_nothing(): void
    {
        when('get_post_type')->justReturn('expertise');
        when('get_post_status')->justReturn('publish');

        expect('check_admin_referer')->never();
        expect('update_post_meta')->never();

        $_POST = ['sidebarcheck' => 'on'];

        $this->box()->save_postdata(12);

        $this->addToAssertionCount(1);
    }

    public function test_a_checked_parent_box_is_stored(): void
    {
        when('get_post_type')->justReturn('expertise');
        when('get_post_status')->justReturn('publish');
        expect('check_admin_referer')->once()->with('parent_nonce', 'parent_name')->andReturn(true);
        expect('update_post_meta')->once()->with(12, 'parent', 'yes');
        $_POST = ['parent_name' => 'n', 'parentcheck' => 'yes'];

        $this->box()->save_postdata(12);
    }

    public function test_an_unchecked_parent_box_clears_the_value(): void
    {
        when('get_post_type')->justReturn('expertise');
        when('get_post_status')->justReturn('publish');
        when('check_admin_referer')->justReturn(true);
        expect('update_post_meta')->once()->with(12, 'parent', null);
        $_POST = ['parent_name' => 'n'];

        $this->box()->save_postdata(12);
    }

    public function test_a_failed_parent_nonce_stores_nothing(): void
    {
        when('get_post_type')->justReturn('expertise');
        when('get_post_status')->justReturn('publish');
        when('check_admin_referer')->justReturn(false);

        expect('update_post_meta')->never();
        $_POST = ['parent_name' => 'n', 'parentcheck' => 'yes'];

        $this->box()->save_postdata(12);

        $this->addToAssertionCount(1);
    }
}
