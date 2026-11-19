<?php

/**
 * Unit tests for the on-demand sync command's core, against a fake transport.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use UAMSWP_FAD_GBP_Client;
use UamswpFindADoc\Tests\Support\UnitTestCase;
use UamswpFindADoc\Tests\Support\WpCliEnded;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/wp-cli.php';

final class GbpCliTest extends UnitTestCase
{
    private const PROVIDER_GROUP = 'accounts/112667655000425532878';

    /** @var array<int, array{method: string, url: string}> */
    private array $calls = [];

    /** @var array<string, mixed> */
    private $transients = [];

    /** @var array<int, string> */
    private array $lines = [];

    /** @var array<int, string> */
    private array $deleted = [];

    /**
     * @param  array<string, string>  $modes
     * @param  array<int, string>  $post_types  Post type by ID.
     */
    private function wordpress(array $modes, string $host = 'uamshealth.dev', array $post_types = [100 => 'provider', 200 => 'provider']): void
    {
        $this->transients = [];
        $this->deleted = [];

        when('get_option')->alias(fn (string $name, $default = false) => $name === 'uamswp_fad_gbp_modes' ? $modes : $default);
        when('apply_filters')->alias(fn (string $hook, $value) => $value);
        when('home_url')->justReturn('https://'.$host);
        when('wp_parse_url')->alias('parse_url');
        when('get_post_status')->alias(fn (int $id) => isset($post_types[$id]) ? 'publish' : false);
        when('get_post_type')->alias(fn (int $id) => $post_types[$id] ?? false);
        when('get_transient')->alias(fn (string $key) => $this->transients[$key] ?? false);
        when('set_transient')->alias(function (string $key, $value): bool {
            $this->transients[$key] = $value;

            return true;
        });
        when('delete_transient')->alias(function (string $key): bool {
            $this->deleted[] = $key;
            unset($this->transients[$key]);

            return true;
        });
        when('update_option')->justReturn(true);

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
            $this->calls[] = ['method' => $method, 'url' => $url];

            return str_contains($url, 'oauth2.googleapis.com')
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
        return fn (int $post_id): array => $items[$post_id] ?? [];
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
        return count(array_filter($this->calls, fn (array $call): bool => $call['method'] === 'PATCH'));
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
        expect('get_post_meta')->never();
        expect('update_post_meta')->never();
        expect('delete_post_meta')->never();

        uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams')]]));

        $this->assertSame('Done: 1 update', $this->lines[1]);
    }

    public function test_a_rate_limit_ends_the_run_at_that_item_with_a_failure(): void
    {
        $this->wordpress(['uams' => 'live'], 'uamshealth.com');
        $limited = new UAMSWP_FAD_GBP_Client(['client_id' => 'a', 'client_secret' => 'b', 'refresh_token' => 'c'], function (string $method, string $url): array {
            if (str_contains($url, 'oauth2.googleapis.com')) {
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

    public function test_a_pause_on_creates_holds_them_and_still_runs_the_updates(): void
    {
        $this->wordpress(['uams' => 'live'], 'uamshealth.com');
        $until = time() + 7200;
        when('get_option')->alias(fn (string $name, $default = false) => match ($name) {
            'uamswp_fad_gbp_modes' => ['uams' => 'live'],
            'uamswp_fad_gbp_creates_paused_until' => $until,
            default => $default,
        });
        $create = ['store_code' => 'new_clinic', 'kind' => 'provider', 'silo' => 'uams', 'excluded' => false, 'fields' => ['title' => 'T', 'storefrontAddress' => ['postalCode' => '1'], 'categories' => ['primaryCategory' => ['name' => 'c']]], 'unresolved' => [], 'attributes' => []];

        $status = uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$create, $this->item('a_clinic', 'uams')]]));

        $this->assertSame(1, $status);
        $this->assertStringStartsWith('new_clinic  uams  live  create  Creates paused until', $this->lines[0]);
        $this->assertStringStartsWith('a_clinic  uams  live  update', $this->lines[1]);
        $this->assertSame('Creates were held back and resume '.\UAMSWP_FAD_GBP_Logic::reset_label($until).'. Run again after that.', $this->lines[2]);
        $this->assertSame(0, count(array_filter($this->calls, fn (array $call): bool => $call['method'] === 'POST' && ! str_contains($call['url'], 'oauth2'))), 'no create reached Google');
        $this->assertSame(1, $this->writes(), 'the update after the held create still ran');
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

        // The stand-in WP_CLI class exists here, so only the missing constant keeps the commands unregistered.
        \WP_CLI::reset();
        uamswp_fad_gbp_register_cli();

        $this->assertSame([], \WP_CLI::$commands);
    }

    /**
     * Run a command handler and return how it ended: the status it halted with (null for an error), the message
     * of an error, and the lines it printed.
     *
     * @param  array<string, string|bool>  $assoc
     * @return array{status: int|null, error: string, lines: list<string>}
     */
    private function command(string $handler, array $assoc): array
    {
        \WP_CLI::reset();

        try {
            $handler([], $assoc);
        } catch (WpCliEnded $wpCliEnded) {
            return ['status' => $wpCliEnded->status, 'error' => $wpCliEnded->getMessage(), 'lines' => \WP_CLI::$lines];
        }

        $this->fail($handler.' returned without halting');
    }

    /**
     * Stub a function the handlers call, recording its arguments, printing a line through the handler's callback
     * and returning a status.
     *
     * @param  list<array<int, mixed>>  $calls
     */
    private function record(string $function, array &$calls, int $status = 3): void
    {
        when($function)->alias(function (mixed ...$args) use (&$calls, $status): int {
            foreach ($args as $arg) {
                if (is_callable($arg) && ! is_string($arg)) {
                    $arg('printed');
                }
            }

            $calls[] = array_values(array_filter($args, fn (mixed $arg): bool => ! is_callable($arg) || is_string($arg)));

            return $status;
        });
    }

    public function test_an_unknown_silo_is_refused_with_the_list_of_known_silos(): void
    {
        $this->wordpress([]);

        uamswp_fad_gbp_manual_sync([100], 'nope', false, $this->out(), $this->client(), $this->itemsFor([]));

        $this->assertSame(['Unknown silo: nope. Known silos: '.implode(', ', array_keys(uamswp_fad_gbp_silos()))], $this->lines);
    }

    public function test_a_published_post_that_is_not_a_provider_or_location_fails_and_a_location_runs(): void
    {
        $this->wordpress(['uams' => 'dry-run'], 'uamshealth.dev', [100 => 'page', 300 => 'location']);
        when('uamswp_fad_gbp_covered_post_ids')->alias(fn (int $id): array => [$id]);

        $status = uamswp_fad_gbp_manual_sync([100, 300], null, false, $this->out(), $this->client(), $this->itemsFor([300 => [$this->item('a_clinic', 'uams')]]));

        $this->assertSame(1, $status);
        $this->assertSame(['Post 100 is not a published provider or location', 'a_clinic  uams  dry-run  update  Dry run, no write made', 'Done: 1 update'], $this->lines);
    }

    public function test_a_post_covered_twice_is_synced_once(): void
    {
        $this->wordpress(['uams' => 'dry-run']);
        when('uamswp_fad_gbp_covered_post_ids')->alias(fn (int $id): array => $id === 100 ? [100, 200] : [200]);
        $asked = [];

        uamswp_fad_gbp_manual_sync([100, 200], null, false, $this->out(), $this->client(), function (int $post_id) use (&$asked): array {
            $asked[] = $post_id;

            return [];
        });

        $this->assertSame([100, 200], $asked);
    }

    public function test_the_commands_stop_when_there_is_no_client_and_no_credentials(): void
    {
        $this->wordpress(['uams' => 'dry-run']);
        when('uamswp_fad_gbp_credentials')->justReturn();

        $this->assertSame(1, uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), null, $this->itemsFor([])));
        $this->assertSame(['The Google Business Profile credentials are not set'], $this->lines);
        $this->assertSame(1, uamswp_fad_gbp_backfill_ids([100], false, $this->out()));
        $this->assertSame(['The Google Business Profile credentials are not set'], $this->lines);
        $this->assertSame(1, uamswp_fad_gbp_cid_report([100], $this->out()));
        $this->assertSame(['The Google Business Profile credentials are not set'], $this->lines);
        $this->assertSame([], $this->deleted, 'nothing was read');
    }

    public function test_the_commands_use_the_client_they_are_given_without_reading_the_credentials(): void
    {
        $this->wordpress(['uams' => 'dry-run']);
        when('uamswp_fad_gbp_credentials')->justReturn();
        when('uamswp_fad_gbp_index')->justReturn([]);
        when('get_field')->justReturn();
        when('get_the_title')->justReturn('Post');

        $this->assertSame(0, uamswp_fad_gbp_backfill_ids([], false, $this->out(), $this->client(), $this->itemsFor([])));
        $this->assertSame(0, uamswp_fad_gbp_cid_report([], $this->out(), $this->client()));
    }

    public function test_the_sync_holds_the_lock_for_five_minutes(): void
    {
        $this->wordpress(['uams' => 'dry-run']);
        $set = [];
        when('set_transient')->alias(function (string $key, $value, int $expiration) use (&$set): bool {
            $set[] = [$key, $value, $expiration];

            return true;
        });

        uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([]));

        $this->assertSame([['uamswp_fad_gbp_lock', 1, 300]], $set);
    }

    public function test_each_item_s_listing_id_is_stored_with_its_own_entry_and_the_run_is_recorded(): void
    {
        $this->wordpress(['uams' => 'dry-run']);
        $stored = [];
        when('uamswp_fad_gbp_store_listing_ids')->alias(function (array $items, array $entries) use (&$stored): void {
            $stored[] = [array_column($items, 'store_code'), array_column($entries, 'store_code')];
        });
        $after = [];
        when('uamswp_fad_gbp_after_run')->alias(function (\UAMSWP_FAD_GBP_Sync $sync, array $entries) use (&$after): void {
            $after[] = array_column($entries, 'store_code');
        });

        uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams'), $this->item('b_clinic', 'uams')]]));

        $this->assertSame([[['a_clinic'], ['a_clinic']], [['b_clinic'], ['b_clinic']]], $stored);
        $this->assertSame([['a_clinic', 'b_clinic']], $after);
        $this->assertSame('Done: 2 update', $this->lines[2]);
    }

    public function test_the_done_line_counts_each_action_in_alphabetical_order(): void
    {
        $this->wordpress(['uams' => 'dry-run', 'baptist-health' => 'off']);

        uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $this->client(), $this->itemsFor([100 => [$this->item('a_clinic', 'uams'), $this->item('b_clinic', 'baptist-health')]]));

        $this->assertSame('Done: 1 skip, 1 update', $this->lines[2]);
    }

    public function test_an_error_fails_the_exit_status_and_the_run_goes_on(): void
    {
        $this->wordpress(['uams' => 'live'], 'uamshealth.com');
        $failing = new UAMSWP_FAD_GBP_Client(['client_id' => 'a', 'client_secret' => 'b', 'refresh_token' => 'c'], fn (string $method, string $url): array => str_contains($url, 'oauth2.googleapis.com') ? [200, (string) json_encode(['access_token' => 't', 'expires_in' => 3600])] : [500, '{}']);

        $status = uamswp_fad_gbp_manual_sync([100], null, false, $this->out(), $failing, $this->itemsFor([100 => [$this->item('a_clinic', 'uams'), $this->item('b_clinic', 'uams')]]));

        $this->assertSame(1, $status);
        $this->assertStringStartsWith('a_clinic  uams  live  error', $this->lines[0]);
        $this->assertStringStartsWith('b_clinic  uams  live  error', $this->lines[1]);
        $this->assertSame('Done: 2 error', $this->lines[2]);
    }

    public function test_a_post_s_cids_come_from_the_cid_field_of_its_type(): void
    {
        $this->wordpress([], 'uamshealth.dev', [100 => 'provider', 300 => 'location']);
        when('get_field')->alias(fn (string $field, int $post_id): ?string => ['physician_schema_google_cid' => '1111111111', 'schema_google_cid' => '3333333333'][$field] ?? null);

        $this->assertSame(['1111111111'], uamswp_fad_gbp_post_cids(100));
        $this->assertSame(['3333333333'], uamswp_fad_gbp_post_cids(300));
    }

    /**
     * The listings the backfill and the CID report read, and the CIDs each post carries.
     */
    private function cid_world(): void
    {
        $this->wordpress([], 'uamshealth.dev', [100 => 'provider', 200 => 'provider', 300 => 'location', 400 => 'provider']);
        when('uamswp_fad_gbp_index')->justReturn([
            ['account' => self::PROVIDER_GROUP, 'listing' => ['name' => 'locations/1', 'storeCode' => 'a_clinic', 'metadata' => ['mapsUri' => 'https://maps.google.com/?cid=1111111111']]],
            ['account' => self::PROVIDER_GROUP, 'listing' => ['name' => 'locations/5', 'storeCode' => 'other_code', 'metadata' => ['mapsUri' => 'https://maps.google.com/?cid=5555555555']]],
            ['account' => self::PROVIDER_GROUP, 'listing' => ['name' => 'locations/7', 'storeCode' => 'g', 'metadata' => ['mapsUri' => 'https://maps.google.com/?cid=7777777777']]],
            ['account' => self::PROVIDER_GROUP, 'listing' => ['name' => 'locations/9', 'storeCode' => 'b']],
            ['account' => self::PROVIDER_GROUP, 'listing' => ['name' => 'locations/3']],
        ]);
        when('uamswp_fad_gbp_categories')->justReturn([]);
        when('uamswp_fad_gbp_attach_stored_ids')->returnArg();
        when('get_the_title')->alias(fn (int $id): string => 'Post '.$id);

        $cids = [100 => '1111111111', 200 => '5555555555', 300 => '3333333333', 400 => '2222222222'];
        // The location's Yes is not a provider's, so it never reports a missing listing.
        $yes = [100 => '1', 200 => '1', 300 => '1', 400 => '1'];
        when('get_field')->alias(fn (string $field, int $post_id): ?string => $field === 'physician_schema_gmb_query' || $field === 'schema_gmb_query' ? ($yes[$post_id] ?? null) : ($cids[$post_id] ?? null));
    }

    public function test_the_backfill_prints_each_match_and_what_is_unmatched_and_writes_only_when_applied(): void
    {
        $this->cid_world();
        $written = [];
        when('uamswp_fad_gbp_write_listing_id')->alias(function (array $item, string $name) use (&$written): void {
            $written[] = [$item['store_code'], $name];
        });
        $items = $this->itemsFor([
            100 => [['post_id' => 100, 'location_id' => 300, 'kind' => 'provider', 'store_code' => 'a_clinic', 'stored_name' => '']],
            200 => [['post_id' => 200, 'location_id' => 300, 'kind' => 'provider', 'store_code' => 'z_clinic', 'stored_name' => '']],
            300 => [['post_id' => 300, 'kind' => 'location', 'store_code' => 'q_loc', 'stored_name' => ''], ['kind' => 'location', 'store_code' => 'no_post', 'stored_name' => '']],
            400 => [['post_id' => 400, 'location_id' => 300, 'kind' => 'provider', 'store_code' => 'b_kept', 'stored_name' => 'locations/9']],
        ]);

        $this->assertSame(0, uamswp_fad_gbp_backfill_ids([100, 200, 300, 400], false, $this->out(), $this->client(), $items));
        $this->assertSame([
            'a_clinic  locations/1  by_store_code',
            'z_clinic  locations/5  by_cid',
            'UNMATCHED  no_post  (post 0)',
            'UNMATCHED  q_loc  (post 300)',
            'Dry run, nothing written: 1 already stored, 1 matched by store code, 1 matched by CID, 2 unmatched',
        ], $this->lines);
        $this->assertSame([], $written);
        $this->assertSame(['uamswp_fad_gbp_index'], $this->deleted, 'the listings are read fresh');

        uamswp_fad_gbp_backfill_ids([100, 200, 300, 400], true, $this->out(), $this->client(), $items);

        $this->assertSame([['a_clinic', 'locations/1'], ['z_clinic', 'locations/5']], $written);
        $this->assertSame('Done: 1 already stored, 1 matched by store code, 1 matched by CID, 2 unmatched', end($this->lines));
    }

    public function test_the_cid_report_lists_providers_with_no_listing_and_listings_with_no_cid(): void
    {
        $this->cid_world();

        $this->assertSame(0, uamswp_fad_gbp_cid_report([100, 200, 300, 400], $this->out(), $this->client()));
        $this->assertSame([
            'NO LISTING  Post 400 (post 400)',
            'NO CID  g',
            'NO CID  b',
            'NO CID  locations/3',
            '1 providers say Yes but match no listing; 3 listings have no CID on any provider or location',
        ], $this->lines);
        $this->assertSame(['uamswp_fad_gbp_index'], $this->deleted, 'the listings are read fresh');
    }

    public function test_the_backfill_and_the_cid_report_stop_when_the_listings_cannot_be_read(): void
    {
        $this->wordpress([]);
        when('uamswp_fad_gbp_index')->alias(function (): array {
            throw new \RuntimeException('no listings');
        });

        $this->assertSame(1, uamswp_fad_gbp_backfill_ids([100], false, $this->out(), $this->client(), $this->itemsFor([])));
        $this->assertSame(['Stopped: no listings'], $this->lines);
        $this->assertSame(1, uamswp_fad_gbp_cid_report([100], $this->out(), $this->client()));
        $this->assertSame(['Stopped: no listings'], $this->lines);
    }

    public function test_the_sync_command_runs_the_orphan_scan_with_its_dry_run_flag(): void
    {
        $calls = [];
        $this->record('uamswp_fad_gbp_locked_orphan_scan', $calls);

        $this->assertSame(['status' => 3, 'error' => '', 'lines' => ['printed']], $this->command('uamswp_fad_gbp_cli_sync', ['orphans' => true]));
        $this->command('uamswp_fad_gbp_cli_sync', ['orphans' => true, 'dry-run' => true]);

        $this->assertSame([[false], [true]], $calls);
    }

    /**
     * @return array<string, array{array<string, string|bool>}>
     */
    public static function bad_targets(): array
    {
        return [
            'neither' => [[]],
            'both' => [['post' => '12', 'all' => true]],
            'a post that is not a number' => [['post' => '12a']],
        ];
    }

    #[DataProvider('bad_targets')]
    public function test_the_sync_command_needs_exactly_one_target(array $assoc): void
    {
        $calls = [];
        $this->record('uamswp_fad_gbp_manual_sync', $calls);

        $this->assertSame(['status' => null, 'error' => 'Pass exactly one of --post=<id> or --all.', 'lines' => []], $this->command('uamswp_fad_gbp_cli_sync', $assoc));
        $this->assertSame([], $calls);
    }

    public function test_the_sync_command_syncs_one_post_or_every_post_with_its_options(): void
    {
        $calls = [];
        $this->record('uamswp_fad_gbp_manual_sync', $calls);
        when('uamswp_fad_gbp_all_post_ids')->justReturn([5, 6]);

        $this->assertSame(['status' => 3, 'error' => '', 'lines' => ['printed']], $this->command('uamswp_fad_gbp_cli_sync', ['post' => '12']));
        $this->command('uamswp_fad_gbp_cli_sync', ['all' => true, 'silo' => 'uams', 'dry-run' => true]);

        $this->assertSame([[[12], null, false], [[5, 6], 'uams', true]], $calls);
    }

    /**
     * @return array<string, array{array<string, string|bool>, string}>
     */
    public static function options_with_no_value(): array
    {
        // WP-CLI passes an option given with no value as true, and a --no- option as false.
        return [
            '--post with no value' => [['post' => true], 'Pass exactly one of --post=<id> or --all.'],
            '--no-post with --all' => [['post' => false, 'all' => true], 'Pass exactly one of --post=<id> or --all.'],
            '--silo with no value' => [['all' => true, 'silo' => true], 'Pass a silo name with --silo=<silo>.'],
            '--no-silo' => [['post' => '12', 'silo' => false], 'Pass a silo name with --silo=<silo>.'],
        ];
    }

    #[DataProvider('options_with_no_value')]
    public function test_an_option_given_with_no_value_is_refused_and_nothing_syncs(array $assoc, string $error): void
    {
        $calls = [];
        $this->record('uamswp_fad_gbp_manual_sync', $calls);
        $all = [];
        $this->record('uamswp_fad_gbp_all_post_ids', $all);

        $this->assertSame(['status' => null, 'error' => $error, 'lines' => []], $this->command('uamswp_fad_gbp_cli_sync', $assoc));
        $this->assertSame([], $calls);
        $this->assertSame([], $all, 'no post was read either');
    }

    public function test_the_backfill_and_cid_report_commands_run_over_every_post(): void
    {
        $backfill = [];
        $report = [];
        $this->record('uamswp_fad_gbp_backfill_ids', $backfill, 4);
        $this->record('uamswp_fad_gbp_cid_report', $report, 5);
        when('uamswp_fad_gbp_all_post_ids')->justReturn([5, 6]);

        $this->assertSame(['status' => 4, 'error' => '', 'lines' => ['printed']], $this->command('uamswp_fad_gbp_cli_backfill', []));
        $this->command('uamswp_fad_gbp_cli_backfill', ['apply' => true]);
        $this->assertSame(['status' => 5, 'error' => '', 'lines' => ['printed']], $this->command('uamswp_fad_gbp_cli_cid_report', []));

        $this->assertSame([[[5, 6], false], [[5, 6], true]], $backfill);
        $this->assertSame([[[5, 6]]], $report);
    }
}
