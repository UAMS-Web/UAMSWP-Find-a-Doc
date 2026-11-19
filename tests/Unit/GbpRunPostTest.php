<?php

/**
 * Unit tests for the Business Profile cron job that syncs one published provider or location.
 *
 * The steps around the sync are stubbed and recorded, so each test pins what the job hands them:
 * the single-run lock, the fingerprint that skips an unchanged post, the quota gate the sync is
 * given, the work after the run, and the handling of a rate limit or another failure.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use UAMSWP_FAD_GBP_Logic;
use UAMSWP_FAD_GBP_Rate_Limited;
use UAMSWP_FAD_GBP_Sync;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

final class GbpRunPostTest extends UnitTestCase
{
    private const CREDENTIAL_NAMES = ['GOOGLE_BUSINESS_PROFILE_CLIENT_ID', 'GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET', 'GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN'];

    private const MODES = ['uams' => 'live', 'arkansas-childrens' => 'off', 'baptist-health' => 'dry-run'];

    /** @var array<string, string|false> */
    private array $saved_env = [];

    /** @var array<int, array{0: string, 1: array<int, mixed>}> The stubbed steps, in call order. */
    private array $calls = [];

    /** @var array<string, mixed> */
    private array $transients = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->calls = [];
        $this->transients = [];

        foreach (self::CREDENTIAL_NAMES as $name) {
            $this->saved_env[$name] = getenv($name);
            putenv($name.'=test-value');
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->saved_env as $name => $value) {
            putenv($value === false ? $name : $name.'='.$value);
        }

        parent::tearDown();
    }

    /**
     * A provider item in the given silo, with no listing in the index.
     *
     * @return array<string, mixed>
     */
    private static function item(string $store_code, ?string $silo): array
    {
        return [
            'store_code' => $store_code,
            'kind' => 'provider',
            'photos' => [],
            'post_id' => 7,
            'location_id' => 55,
            'silo' => $silo,
            'excluded' => false,
            'fields' => ['title' => 'Dr. Example', 'storefrontAddress' => ['addressLines' => ['1 Main St']], 'categories' => ['primaryCategory' => ['name' => 'categories/gcid:doctor']]],
            'unresolved' => [],
        ];
    }

    /**
     * Wire a published post, the modes, production and the stubbed steps around the sync.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @param  array{creates_paused_until: int, creates_left: int, now: int}  $gate
     */
    private function wire(array $items, string $stored_hash = '', string $type = 'provider', array $gate = ['creates_paused_until' => 0, 'creates_left' => 0, 'now' => 1_700_000_000]): void
    {
        when('get_post_status')->justReturn('publish');
        when('get_post_type')->justReturn($type);
        when('apply_filters')->alias(fn (string $hook, $value) => $value);
        when('get_option')->alias(fn (string $name, $default = false) => $name === 'uamswp_fad_gbp_modes' ? self::MODES : $default);
        when('home_url')->justReturn('https://uamshealth.com');
        when('wp_parse_url')->alias('parse_url');
        when('get_transient')->alias(fn (string $key) => $this->transients[$key] ?? false);
        when('set_transient')->alias(function (string $key, $value, int $expiry): bool {
            $this->calls[] = ['set_transient', [$key, $value, $expiry]];
            $this->transients[$key] = $value;

            return true;
        });
        when('delete_transient')->alias(function (string $key): bool {
            $this->calls[] = ['delete_transient', [$key]];
            unset($this->transients[$key]);

            return true;
        });
        when('get_post_meta')->alias(fn (int $id, string $key, bool $single) => $id === 7 && $key === UAMSWP_FAD_GBP_HASH_META && $single ? $stored_hash : 'unexpected');
        when('uamswp_fad_gbp_transport')->alias(fn (string $method, string $url) => str_contains($url, 'oauth2.googleapis.com')
            ? [200, (string) json_encode(['access_token' => 'tok', 'expires_in' => 3600])]
            : [500, (string) json_encode(['error' => ['message' => 'no call to Google is expected']])]);
        when('uamswp_fad_gbp_categories')->alias(function ($client): array {
            $this->calls[] = ['categories', []];

            return ['Doctor' => 'categories/gcid:doctor'];
        });
        when('uamswp_fad_gbp_items_for_post')->alias(function (int $post_id, array $categories) use ($items): array {
            $this->calls[] = ['items_for_post', [$post_id, $categories]];

            return $items;
        });
        when('uamswp_fad_gbp_attach_stored_ids')->alias(function (array $items): array {
            $this->calls[] = ['attach_stored_ids', [$items]];

            return array_map(fn (array $item): array => ['stored_name' => '', 'photos_sent' => []] + $item, $items);
        });
        when('uamswp_fad_gbp_create_gate')->alias(function () use ($gate): array {
            $this->calls[] = ['create_gate', []];

            return $gate;
        });
        when('uamswp_fad_gbp_index')->alias(function ($client): array {
            $this->calls[] = ['index', []];

            return [];
        });
        foreach (['store_listing_ids', 'mark_orphans', 'after_run', 'finish_run', 'log', 'schedule_retry'] as $step) {
            when('uamswp_fad_gbp_'.$step)->alias(function (...$args) use ($step): void {
                $this->calls[] = [$step, $args];
            });
        }
    }

    /**
     * The recorded calls of one step.
     *
     * @return array<int, array<int, mixed>>
     */
    private function callsOf(string $step): array
    {
        return array_values(array_map(fn (array $call): array => $call[1], array_filter($this->calls, fn (array $call): bool => $call[0] === $step)));
    }

    /**
     * @return list<string>
     */
    private function steps(): array
    {
        return array_column($this->calls, 0);
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private static function hash(array $items): string
    {
        return UAMSWP_FAD_GBP_Logic::fingerprint($items, self::MODES, true, UAMS_FAD_VERSION);
    }

    public function test_a_changed_post_is_synced_under_the_lock_and_its_run_is_finished(): void
    {
        $items = [self::item('dr-example_main-clinic', 'uams'), self::item('dr-example_partner', 'baptist-health')];
        $this->wire($items);

        uamswp_fad_gbp_run_post(7);

        $this->assertSame(['set_transient', 'categories', 'items_for_post', 'attach_stored_ids', 'create_gate', 'index', 'log', 'log', 'store_listing_ids', 'mark_orphans', 'after_run', 'finish_run', 'delete_transient'], $this->steps());
        $this->assertSame([['uamswp_fad_gbp_lock', 1, 300]], $this->callsOf('set_transient'));
        $this->assertSame([['uamswp_fad_gbp_lock']], $this->callsOf('delete_transient'));
        $this->assertSame([[7, ['Doctor' => 'categories/gcid:doctor']]], $this->callsOf('items_for_post'));
        $this->assertSame([[$items]], $this->callsOf('attach_stored_ids'));

        $entries = $this->callsOf('finish_run')[0][1];
        $this->assertSame([7, $entries, self::hash($items)], $this->callsOf('finish_run')[0], 'the fingerprint covers the items, the modes, production and the version');
        $this->assertSame(['live', 'dry-run'], array_column($entries, 'mode'), 'the sync was given the modes and production');
        $this->assertStringStartsWith('Daily create budget used', $entries[0]['reason'], 'the live create is held back by the gate it was given, which allows none today');
        $this->assertArrayHasKey('defer_until', $entries[0]);
        $this->assertSame('Dry run, no listing created', $entries[1]['reason']);

        $stored = $this->callsOf('store_listing_ids')[0];
        $this->assertSame([array_map(fn (array $item): array => ['stored_name' => '', 'photos_sent' => []] + $item, $items), $entries], $stored, 'the listing IDs are stored from the items with their stored IDs');
        $this->assertSame([[7, $items]], $this->callsOf('mark_orphans'), 'orphans are marked from every item, as built');

        $after = $this->callsOf('after_run')[0];
        $this->assertInstanceOf(UAMSWP_FAD_GBP_Sync::class, $after[0]);
        $this->assertSame([$entries, 1_700_000_000], [$after[1], $after[2]], 'the quota is counted from the moment the gate was read');
        $this->assertSame([[$entries[0]], [$entries[1]]], $this->callsOf('log'), "the sync logs through the job's logger");
    }

    public function test_orphans_are_not_marked_when_no_entry_was_live(): void
    {
        $this->wire([self::item('dr-example_partner', 'baptist-health')]);

        uamswp_fad_gbp_run_post(7);

        $this->assertSame([], $this->callsOf('mark_orphans'));
        $this->assertCount(1, $this->callsOf('finish_run'));
    }

    public function test_an_unchanged_post_makes_no_google_call_and_writes_nothing(): void
    {
        $items = [self::item('dr-example_main-clinic', 'uams')];
        $this->wire($items, self::hash($items));

        uamswp_fad_gbp_run_post(7);

        $this->assertSame(['set_transient', 'categories', 'items_for_post', 'delete_transient'], $this->steps());
    }

    public function test_a_stored_fingerprint_from_another_state_does_not_skip_the_run(): void
    {
        $items = [self::item('dr-example_main-clinic', 'uams')];
        $this->wire($items, UAMSWP_FAD_GBP_Logic::fingerprint($items, self::MODES, false, UAMS_FAD_VERSION));

        uamswp_fad_gbp_run_post(7);

        $this->assertCount(1, $this->callsOf('finish_run'));
    }

    public function test_a_job_that_finds_another_running_queues_itself_a_minute_later(): void
    {
        $this->wire([]);
        $this->transients['uamswp_fad_gbp_lock'] = 1;
        $scheduled = [];
        when('wp_schedule_single_event')->alias(function (int $time, string $hook, array $args) use (&$scheduled): bool {
            $scheduled[] = [$time, $hook, $args];

            return true;
        });
        $before = time();

        uamswp_fad_gbp_run_post(7);

        $this->assertCount(1, $scheduled);
        $this->assertSame([UAMSWP_FAD_GBP_SYNC_HOOK, [7]], [$scheduled[0][1], $scheduled[0][2]]);
        $this->assertGreaterThanOrEqual($before + 60, $scheduled[0][0]);
        $this->assertLessThanOrEqual(time() + 60, $scheduled[0][0]);
        $this->assertSame([], $this->calls, 'the lock is neither taken nor released, and nothing runs');
    }

    public function test_without_credentials_the_job_does_nothing(): void
    {
        putenv('GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN');
        $this->wire([]);
        expect('get_post_status')->never();

        uamswp_fad_gbp_run_post(7);

        $this->assertSame([], $this->calls);
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function post_types(): array
    {
        return [
            'a provider' => ['provider', 'provider'],
            'a location' => ['location', 'location'],
            'any other type is logged as a provider' => ['page', 'provider'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function errorEntry(string $kind, string $reason): array
    {
        return ['store_code' => '', 'silo' => null, 'kind' => $kind, 'mode' => 'n/a', 'action' => 'error', 'reason' => $reason, 'fields' => [], 'unresolved_categories' => []];
    }

    #[DataProvider('post_types')]
    public function test_a_rate_limit_is_logged_and_the_post_retried_and_the_lock_released(string $type, string $kind): void
    {
        $this->wire([], '', $type);
        when('uamswp_fad_gbp_categories')->alias(function (): never {
            throw new UAMSWP_FAD_GBP_Rate_Limited('Google rate limit (429)');
        });

        uamswp_fad_gbp_run_post(7);

        $this->assertSame(['set_transient', 'log', 'schedule_retry', 'delete_transient'], $this->steps());
        $this->assertSame([[self::errorEntry($kind, 'Google rate limit (429)')]], $this->callsOf('log'));
        $this->assertSame([[7]], $this->callsOf('schedule_retry'));
    }

    #[DataProvider('post_types')]
    public function test_another_failure_is_logged_without_a_retry_and_the_lock_released(string $type, string $kind): void
    {
        $this->wire([], '', $type);
        when('uamswp_fad_gbp_categories')->alias(function (): never {
            throw new RuntimeException('Google token exchange failed (400): invalid_grant');
        });

        uamswp_fad_gbp_run_post(7);

        $this->assertSame(['set_transient', 'log', 'delete_transient'], $this->steps());
        $this->assertSame([[self::errorEntry($kind, 'Google token exchange failed (400): invalid_grant')]], $this->callsOf('log'));
    }

    public function test_a_run_that_fails_after_the_sync_still_counts_its_creates_and_is_not_finished(): void
    {
        $this->wire([self::item('dr-example_main-clinic', 'uams')]);
        when('uamswp_fad_gbp_store_listing_ids')->alias(function (): never {
            throw new RuntimeException('database went away');
        });

        uamswp_fad_gbp_run_post(7);

        $this->assertSame(['set_transient', 'categories', 'items_for_post', 'attach_stored_ids', 'create_gate', 'index', 'log', 'after_run', 'log', 'delete_transient'], $this->steps());
        $this->assertArrayHasKey('defer_until', $this->callsOf('after_run')[0][1][0], 'the entries the sync returned reach the quota count');
        $this->assertSame([self::errorEntry('provider', 'database went away')], $this->callsOf('log')[1]);
    }
}
