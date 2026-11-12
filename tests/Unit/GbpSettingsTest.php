<?php

/**
 * Unit tests for the Business Profile sync settings screen's save path.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use RuntimeException;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

final class GbpSettingsTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        when('apply_filters')->alias(fn (string $hook, $value) => $value);
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
        when('current_user_can')->justReturn(false);
        when('esc_html__')->returnArg();
        expect('wp_die')->once()->andReturnUsing(function (): never {
            throw new RuntimeException('died');
        });
        expect('update_option')->never();

        $this->expectException(RuntimeException::class);

        uamswp_fad_gbp_save_modes();
    }

    public function test_saving_checks_the_nonce_before_writing(): void
    {
        when('current_user_can')->justReturn(true);
        expect('check_admin_referer')->once()->with('uamswp_fad_gbp_modes')->andReturnUsing(function (): never {
            throw new RuntimeException('bad nonce');
        });
        expect('update_option')->never();

        $this->expectException(RuntimeException::class);

        uamswp_fad_gbp_save_modes();
    }

    public function test_queueing_all_without_the_capability_dies_and_queues_nothing(): void
    {
        when('current_user_can')->justReturn(false);
        when('esc_html__')->returnArg();
        expect('wp_die')->once()->andReturnUsing(function (): never {
            throw new RuntimeException('died');
        });
        expect('get_posts')->never();

        $this->expectException(RuntimeException::class);

        uamswp_fad_gbp_queue_all_action();
    }

    public function test_queueing_all_checks_the_nonce_before_queueing(): void
    {
        when('current_user_can')->justReturn(true);
        expect('check_admin_referer')->once()->with('uamswp_fad_gbp_queue_all')->andReturnUsing(function (): never {
            throw new RuntimeException('bad nonce');
        });
        expect('get_posts')->never();

        $this->expectException(RuntimeException::class);

        uamswp_fad_gbp_queue_all_action();
    }

    public function test_queueing_all_reports_how_many_were_queued(): void
    {
        when('current_user_can')->justReturn(true);
        when('check_admin_referer')->justReturn(1);
        when('admin_url')->returnArg();
        when('get_option')->alias(fn (string $name, $default = false) => $name === 'uamswp_fad_gbp_modes' ? [] : $default);
        when('get_posts')->alias(fn (array $args) => $args['post_type'] === 'provider' ? [7, 8] : [55]);
        when('delete_post_meta')->justReturn(true);
        when('wp_next_scheduled')->justReturn(false);
        when('wp_schedule_single_event')->justReturn(true);
        when('add_query_arg')->alias(fn (string $key, int $value, string $url): string => $url.'&'.$key.'='.$value);
        expect('wp_safe_redirect')->once()->with('admin.php?page=uamswp-fad-gbp&queued=3');

        uamswp_fad_gbp_queue_all_action(false, fn (string $name): string => 'value-of-'.$name);
    }

    public function test_a_valid_save_stores_the_sanitized_modes_without_autoload(): void
    {
        $_POST['modes'] = ['uams' => 'live', 'baptist-health' => 'bogus'];
        when('current_user_can')->justReturn(true);
        when('check_admin_referer')->justReturn(1);
        when('wp_unslash')->returnArg();
        when('admin_url')->returnArg();
        when('add_query_arg')->alias(fn (string $key, string $value, string $url): string => $url.'&'.$key.'='.$value);
        when('wp_safe_redirect')->justReturn(true);
        expect('update_option')->once()->with('uamswp_fad_gbp_modes', ['uams' => 'live', 'arkansas-childrens' => 'dry-run', 'baptist-health' => 'dry-run'], false);

        uamswp_fad_gbp_save_modes(false);
    }

    public function test_log_filters_accept_only_known_actions_and_silos(): void
    {
        $this->assertSame(['action' => 'review', 'silo' => 'baptist-health'], uamswp_fad_gbp_log_filters(['log_action' => 'review', 'log_silo' => 'baptist-health']));
        $this->assertSame(['action' => null, 'silo' => null], uamswp_fad_gbp_log_filters(['log_action' => 'drop table', 'log_silo' => ['uams']]));
        $this->assertSame(['action' => null, 'silo' => null], uamswp_fad_gbp_log_filters([]));
    }
}
