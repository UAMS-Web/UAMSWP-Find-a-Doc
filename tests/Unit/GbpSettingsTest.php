<?php

/**
 * Unit tests for the Business Profile sync settings screen's save path.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use RuntimeException;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpSettingsTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Functions\when('apply_filters')->alias(function (string $hook, $value) {
            return $value;
        });
    }

    protected function tearDown(): void
    {
        $_POST = [];
        parent::tearDown();
    }

    public function test_posted_modes_keep_only_known_silos_and_modes(): void
    {
        $posted = ['uams' => 'live', 'baptist-health' => 'off', 'arkansas-childrens' => 'maybe', 'unknown' => 'live', 'nested' => ['x']];

        $this->assertSame(['uams' => 'live', 'arkansas-childrens' => 'dry-run', 'baptist-health' => 'off'], uamswp_fad_gbp_sanitize_modes($posted));
    }

    public function test_an_empty_post_puts_every_silo_in_dry_run(): void
    {
        $this->assertSame(['uams' => 'dry-run', 'arkansas-childrens' => 'dry-run', 'baptist-health' => 'dry-run'], uamswp_fad_gbp_sanitize_modes([]));
    }

    public function test_saving_without_the_capability_dies_and_writes_nothing(): void
    {
        Functions\when('current_user_can')->justReturn(false);
        Functions\when('esc_html__')->returnArg();
        Functions\expect('wp_die')->once()->andReturnUsing(function (): void {
            throw new RuntimeException('died');
        });
        Functions\expect('update_option')->never();

        $this->expectException(RuntimeException::class);

        uamswp_fad_gbp_save_modes();
    }

    public function test_saving_checks_the_nonce_before_writing(): void
    {
        Functions\when('current_user_can')->justReturn(true);
        Functions\expect('check_admin_referer')->once()->with('uamswp_fad_gbp_modes')->andReturnUsing(function (): void {
            throw new RuntimeException('bad nonce');
        });
        Functions\expect('update_option')->never();

        $this->expectException(RuntimeException::class);

        uamswp_fad_gbp_save_modes();
    }

    public function test_queueing_all_without_the_capability_dies_and_queues_nothing(): void
    {
        Functions\when('current_user_can')->justReturn(false);
        Functions\when('esc_html__')->returnArg();
        Functions\expect('wp_die')->once()->andReturnUsing(function (): void {
            throw new RuntimeException('died');
        });
        Functions\expect('get_posts')->never();

        $this->expectException(RuntimeException::class);

        uamswp_fad_gbp_queue_all_action();
    }

    public function test_queueing_all_checks_the_nonce_before_queueing(): void
    {
        Functions\when('current_user_can')->justReturn(true);
        Functions\expect('check_admin_referer')->once()->with('uamswp_fad_gbp_queue_all')->andReturnUsing(function (): void {
            throw new RuntimeException('bad nonce');
        });
        Functions\expect('get_posts')->never();

        $this->expectException(RuntimeException::class);

        uamswp_fad_gbp_queue_all_action();
    }

    public function test_queueing_all_reports_how_many_were_queued(): void
    {
        Functions\when('current_user_can')->justReturn(true);
        Functions\when('check_admin_referer')->justReturn(1);
        Functions\when('admin_url')->returnArg();
        Functions\when('get_option')->alias(function (string $name, $default = false) {
            return $name === 'uamswp_fad_gbp_modes' ? [] : $default;
        });
        Functions\when('get_posts')->justReturn([7, 8]);
        Functions\when('delete_post_meta')->justReturn(true);
        Functions\when('wp_next_scheduled')->justReturn(false);
        Functions\when('wp_schedule_single_event')->justReturn(true);
        Functions\when('add_query_arg')->alias(function (string $key, int $value, string $url): string {
            return $url.'&'.$key.'='.$value;
        });
        Functions\expect('wp_safe_redirect')->once()->with('admin.php?page=uamswp-fad-gbp&queued=2');

        uamswp_fad_gbp_queue_all_action(false, function (string $name): string {
            return 'value-of-'.$name;
        });
    }

    public function test_a_valid_save_stores_the_sanitized_modes_without_autoload(): void
    {
        $_POST['modes'] = ['uams' => 'live', 'baptist-health' => 'bogus'];
        Functions\when('current_user_can')->justReturn(true);
        Functions\when('check_admin_referer')->justReturn(1);
        Functions\when('wp_unslash')->returnArg();
        Functions\when('admin_url')->returnArg();
        Functions\when('add_query_arg')->alias(function (string $key, string $value, string $url): string {
            return $url.'&'.$key.'='.$value;
        });
        Functions\when('wp_safe_redirect')->justReturn(true);
        Functions\expect('update_option')->once()->with('uamswp_fad_gbp_modes', ['uams' => 'live', 'arkansas-childrens' => 'dry-run', 'baptist-health' => 'dry-run'], false);

        uamswp_fad_gbp_save_modes(false);
    }
}
