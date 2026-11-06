<?php

/**
 * Pins the Edit Flow status control on published providers and locations (class.fad-edit-flow-status.php).
 *
 * The inline script itself runs in the browser against Edit Flow's own script, so what is pinned here is when
 * the plugin attaches it and the lines of it that carry the behavior.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class EditFlowStatusControlTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The file registers its hook at file scope, so the hook function must exist while it loads.
        Functions\when('add_action')->justReturn(true);
        require_once dirname(__DIR__, 2).'/includes/class.fad-edit-flow-status.php';
    }

    private function screen(string $post_type): void
    {
        Functions\when('get_current_screen')->justReturn((object) ['post_type' => $post_type]);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function covered_post_types(): array
    {
        return ['provider' => ['provider'], 'location' => ['location']];
    }

    #[DataProvider('covered_post_types')]
    public function test_the_script_is_attached_to_the_edit_screen_of_a_provider_or_location(string $post_type): void
    {
        Functions\when('wp_script_is')->justReturn(true);
        $this->screen($post_type);

        $captured = [];
        Functions\expect('wp_add_inline_script')->once()->andReturnUsing(function ($handle, $script, $position) use (&$captured) {
            $captured = [$handle, $script, $position];

            return true;
        });

        \uamswp_fad_edit_flow_published_status_control('post.php');

        $this->assertSame('edit_flow-custom_status', $captured[0], 'attached to Edit Flow\'s own script');
        $this->assertSame('after', $captured[2], 'runs after Edit Flow\'s ready handler');
    }

    public function test_the_script_only_acts_for_a_published_profile_the_user_cannot_publish_but_can_edit(): void
    {
        Functions\when('wp_script_is')->justReturn(true);
        $this->screen('provider');

        $script = '';
        Functions\when('wp_add_inline_script')->alias(function ($handle, $js) use (&$script) {
            $script = $js;

            return true;
        });

        \uamswp_fad_edit_flow_published_status_control('post.php');

        $this->assertStringContainsString("'publish' !== current_status", $script);
        $this->assertStringContainsString('|| current_user_can_publish_posts', $script);
        $this->assertStringContainsString('|| ! current_user_can_edit_published_posts', $script);
        // Publishing is not unlocked: the dropdown keeps the one Published option and ACF validation is only
        // skipped when the chosen status is not 'publish'.
        $this->assertStringContainsString("slice( 1 ).remove()", $script);
        $this->assertStringContainsString("acf.validation.set( 'ignore', 'publish' !== ", $script);
    }

    public function test_nothing_is_attached_on_other_admin_screens(): void
    {
        Functions\when('wp_script_is')->justReturn(true);
        $this->screen('provider');
        Functions\expect('wp_add_inline_script')->never();

        \uamswp_fad_edit_flow_published_status_control('edit.php');
        \uamswp_fad_edit_flow_published_status_control('post-new.php');

        $this->addToAssertionCount(1);
    }

    public function test_nothing_is_attached_when_edit_flow_has_not_enqueued_its_script(): void
    {
        Functions\when('wp_script_is')->justReturn(false);
        $this->screen('provider');
        Functions\expect('wp_add_inline_script')->never();

        \uamswp_fad_edit_flow_published_status_control('post.php');

        $this->addToAssertionCount(1);
    }

    public function test_nothing_is_attached_for_other_post_types(): void
    {
        Functions\when('wp_script_is')->justReturn(true);
        $this->screen('post');
        Functions\expect('wp_add_inline_script')->never();

        \uamswp_fad_edit_flow_published_status_control('post.php');

        $this->addToAssertionCount(1);
    }

    public function test_nothing_is_attached_when_there_is_no_current_screen(): void
    {
        Functions\when('wp_script_is')->justReturn(true);
        Functions\when('get_current_screen')->justReturn(null);
        Functions\expect('wp_add_inline_script')->never();

        \uamswp_fad_edit_flow_published_status_control('post.php');

        $this->addToAssertionCount(1);
    }
}
