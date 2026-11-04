<?php

/**
 * Unit tests for the on-demand sync command's core, against a fake transport.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use UAMSWP_FAD_GBP_Client;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpCliTest extends UnitTestCase
{
    private const PROVIDER_GROUP = 'accounts/112667655000425532878';

    /** @var array<int, array{method: string, url: string}> */
    private $calls = [];

    /** @var array<string, mixed> */
    private $transients = [];

    /** @var array<int, string> */
    private $lines = [];

    /** @var array<int, string> */
    private $deleted = [];

    /**
     * @param  array<string, string>  $modes
     * @param  array<int, string>  $post_types  Post type by ID.
     */
    private function wordpress(array $modes, string $host = 'uamshealth.dev', array $post_types = [100 => 'provider', 200 => 'provider']): void
    {
        $this->transients = [];
        $this->deleted = [];

        Functions\when('get_option')->alias(function (string $name, $default = false) use ($modes) {
            return $name === 'uamswp_fad_gbp_modes' ? $modes : $default;
        });
        Functions\when('apply_filters')->alias(function (string $hook, $value) {
            return $value;
        });
        Functions\when('home_url')->justReturn('https://'.$host);
        Functions\when('wp_parse_url')->alias('parse_url');
        Functions\when('get_post_status')->alias(function (int $id) use ($post_types) {
            return isset($post_types[$id]) ? 'publish' : false;
        });
        Functions\when('get_post_type')->alias(function (int $id) use ($post_types) {
            return $post_types[$id] ?? false;
        });
        Functions\when('get_transient')->alias(function (string $key) {
            return $this->transients[$key] ?? false;
        });
        Functions\when('set_transient')->alias(function (string $key, $value): bool {
            $this->transients[$key] = $value;

            return true;
        });
        Functions\when('delete_transient')->alias(function (string $key): bool {
            $this->deleted[] = $key;
            unset($this->transients[$key]);

            return true;
        });
        Functions\when('update_option')->justReturn(true);

        // The index and the category map are cached, so a run makes no listing or category call.
        $this->transients['uamswp_fad_gbp_categories'] = [];
        $this->transients['uamswp_fad_gbp_index'] = [
            ['account' => self::PROVIDER_GROUP, 'listing' => ['name' => 'locations/1', 'storeCode' => 'a_clinic', 'title' => 'Old title']],
            ['account' => self::PROVIDER_GROUP, 'listing' => ['name' => 'locations/2', 'storeCode' => 'b_clinic', 'title' => 'Old title']],
        ];
    }

    private function client(): UAMSWP_FAD_GBP_Client
    {
        $this->calls = [];

        return new UAMSWP_FAD_GBP_Client(['client_id' => 'a', 'client_secret' => 'b', 'refresh_token' => 'c'], function (string $method, string $url): array {
            $this->calls[] = compact('method', 'url');

            return strpos($url, 'oauth2.googleapis.com') !== false
                ? [200, json_encode(['access_token' => 't', 'expires_in' => 3600])]
                : [200, '{}'];
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function item(string $code, string $silo, string $title = 'New title'): array
    {
        return ['store_code' => $code, 'kind' => 'provider', 'silo' => $silo, 'excluded' => false, 'fields' => ['title' => $title], 'unresolved' => [], 'attributes' => []];
    }

    /**
     * @param  array<int, array<int, array<string, mixed>>>  $items  Items by post ID.
     */
    private function itemsFor(array $items): callable
    {
        return function (int $post_id) use ($items): array {
            return $items[$post_id] ?? [];
        };
    }

    private function out(): callable
    {
        $this->lines = [];

        return function (string $line): void {
            $this->lines[] = $line;
        };
    }

    private function writes(): int
    {
        return count(array_filter($this->calls, function (array $call): bool {
            return $call['method'] === 'PATCH';
        }));
    }

    public function test_a_dry_run_prints_the_decision_and_writes_nothing(): void
    {
        $this->wordpress(['uams' => 'dry-run']);

        $status = uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams')]]));

        $this->assertSame(0, $status);
        $this->assertSame(['a_clinic  uams  dry-run  update  Dry run, no write made', 'Done: 1 update'], $this->lines);
        $this->assertSame(0, $this->writes());
    }

    public function test_the_flag_forces_a_live_silo_on_production_to_dry_run_and_its_absence_does_not(): void
    {
        $this->wordpress(['uams' => 'live'], 'uamshealth.com');
        $status = uamswp_fad_gbp_manual_sync([100], null, true, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams')]]));

        $this->assertSame([0, 0, 'dry-run'], [$status, $this->writes(), explode('  ', $this->lines[0])[2]]);

        // Control: the same run without the flag writes, so the zero above is the flag's doing.
        $this->wordpress(['uams' => 'live'], 'uamshealth.com');
        $status = uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams')]]));

        $this->assertSame([0, 1, 'live'], [$status, $this->writes(), explode('  ', $this->lines[0])[2]]);
    }

    public function test_the_flag_leaves_an_off_silo_off(): void
    {
        $this->wordpress(['uams' => 'off']);

        uamswp_fad_gbp_manual_sync([100], null, true, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams')]]));

        $this->assertSame('a_clinic  uams  off  skip  Silo uams is off', $this->lines[0]);
    }

    public function test_a_silo_option_runs_only_that_silos_items(): void
    {
        $this->wordpress(['uams' => 'dry-run', 'baptist-health' => 'dry-run']);

        uamswp_fad_gbp_manual_sync([100], 'uams', false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams'), $this->item('b_clinic', 'baptist-health')]]));

        $this->assertCount(2, $this->lines);
        $this->assertStringStartsWith('a_clinic  uams', $this->lines[0]);
        $this->assertSame('Done: 1 update', $this->lines[1]);
    }

    public function test_an_unknown_silo_is_refused_before_anything_runs(): void
    {
        $this->wordpress([]);

        $status = uamswp_fad_gbp_manual_sync([100], 'nope', false, $this->out(), $this->client(), $this->itemsFor([]));

        $this->assertSame(1, $status);
        $this->assertStringStartsWith('Unknown silo: nope', $this->lines[0]);
        $this->assertSame([], $this->calls);
    }

    public function test_an_unpublished_or_other_post_fails_and_the_rest_still_run(): void
    {
        $this->wordpress(['uams' => 'dry-run']);

        $status = uamswp_fad_gbp_manual_sync([999, 100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams')]]));

        $this->assertSame(1, $status);
        $this->assertSame('Post 999 is not a published provider or location', $this->lines[0]);
        $this->assertStringStartsWith('a_clinic', $this->lines[1]);
    }

    public function test_nothing_to_sync_fails(): void
    {
        $this->wordpress([]);

        $this->assertSame(1, uamswp_fad_gbp_manual_sync([999], null, false, $this->out(), $this->client(), $this->itemsFor([])));
        $this->assertSame(['Post 999 is not a published provider or location', 'Nothing to sync'], $this->lines);
    }

    public function test_it_refuses_to_run_while_the_queue_holds_the_lock_and_leaves_the_lock_alone(): void
    {
        $this->wordpress(['uams' => 'dry-run']);
        $this->transients['uamswp_fad_gbp_lock'] = 1;

        $status = uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams')]]));

        $this->assertSame(1, $status);
        $this->assertSame(['A sync is already running. Try again when it finishes.'], $this->lines);
        $this->assertNotContains('uamswp_fad_gbp_lock', $this->deleted);
        $this->assertArrayHasKey('uamswp_fad_gbp_lock', $this->transients);
    }

    public function test_it_releases_the_lock_when_it_finishes_and_when_it_throws(): void
    {
        $this->wordpress(['uams' => 'dry-run']);
        uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams')]]));
        $this->assertArrayNotHasKey('uamswp_fad_gbp_lock', $this->transients);
        $this->assertContains('uamswp_fad_gbp_lock', $this->deleted);

        $this->deleted = [];
        $status = uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), function (): array {
            throw new \RuntimeException('categories unreadable');
        });

        $this->assertSame(1, $status);
        $this->assertSame('Stopped: categories unreadable', $this->lines[0]);
        $this->assertContains('uamswp_fad_gbp_lock', $this->deleted);
    }

    public function test_it_never_touches_the_fingerprint(): void
    {
        $this->wordpress(['uams' => 'dry-run']);
        Functions\expect('get_post_meta')->never();
        Functions\expect('update_post_meta')->never();
        Functions\expect('delete_post_meta')->never();

        uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams')]]));

        $this->assertSame('Done: 1 update', $this->lines[1]);
    }

    public function test_a_rate_limit_ends_the_run_at_that_item_with_a_failure(): void
    {
        $this->wordpress(['uams' => 'live'], 'uamshealth.com');
        $limited = new UAMSWP_FAD_GBP_Client(['client_id' => 'a', 'client_secret' => 'b', 'refresh_token' => 'c'], function (string $method, string $url): array {
            if (strpos($url, 'oauth2.googleapis.com') !== false) {
                return [200, json_encode(['access_token' => 't', 'expires_in' => 3600])];
            }

            return [429, '{}'];
        });

        $status = uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $limited, $this->itemsFor([100 => [$this->item('a_clinic', 'uams'), $this->item('b_clinic', 'uams')]]));

        $this->assertSame(1, $status);
        $this->assertCount(3, $this->lines);
        $this->assertStringStartsWith('a_clinic  uams  live  error', $this->lines[0]);
        $this->assertSame('Stopped: Google is rate limiting this account. Run again later.', $this->lines[1]);
    }

    public function test_a_review_decision_fails_the_exit_status_and_a_create_does_not(): void
    {
        $this->wordpress(['uams' => 'dry-run']);

        $status = uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('not_in_any_group', 'uams')]]));

        $this->assertSame(0, $status, 'A listing with too few fields to create is a skip, not a failure');

        $this->transients['uamswp_fad_gbp_index'][] = ['account' => 'accounts/other', 'listing' => ['name' => 'locations/3', 'storeCode' => 'c_clinic']];
        $status = uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('c_clinic', 'uams')]]));

        $this->assertSame(1, $status);
        $this->assertStringContainsString('review', $this->lines[0]);
    }

    public function test_the_command_is_only_registered_under_wp_cli(): void
    {
        $this->assertFalse(defined('WP_CLI'), 'The test run is not under WP-CLI');

        // Without WP-CLI this must not reach for the WP_CLI class, which does not exist here.
        uamswp_fad_gbp_register_cli();

        $this->assertFalse(class_exists('WP_CLI', false));
    }
}
