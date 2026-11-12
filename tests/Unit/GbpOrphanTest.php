<?php

/**
 * Unit tests for the orphaned-listing report and the close after 14 continuous days.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UAMSWP_FAD_GBP_Client;
use UAMSWP_FAD_GBP_Logic;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\when;

final class GbpOrphanTest extends UnitTestCase
{
    private const ACCOUNT = 'accounts/112667655000425532878';

    /** 2026-11-17 12:00:00 UTC. */
    private const NOW = 1794916800;

    /** @var array<int, array<string, mixed>> */
    private array $meta = [];

    /** @var array<string, mixed> */
    private array $options = [];

    /** @var array<int, array<string, mixed>> */
    private array $unpublished = [];

    /** @var array<int, array<string, mixed>> */
    private array $providers = [];

    /** @var array<int, array<string, mixed>> */
    private array $locations = [];

    /** @var array<string, string> Post ID => type. */
    private array $types = [];

    /** @var array<int, array{string, array<string, mixed>, string, string}> */
    private array $patches = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->meta = [];
        $this->options = [];
        $this->unpublished = [];
        $this->providers = [];
        $this->locations = [];
        $this->types = [];
        $this->patches = [];

        when('apply_filters')->alias(fn (string $hook, $value) => $value);
        when('home_url')->justReturn('https://uamshealth.com');
        when('wp_parse_url')->alias(fn (string $url, int $component) => parse_url($url, $component));
        when('get_option')->alias(fn (string $name, $default = false) => $this->options[$name] ?? $default);
        when('update_option')->alias(function (string $name, $value): bool {
            $this->options[$name] = $value;

            return true;
        });
        when('delete_transient')->justReturn(true);
        when('get_the_title')->alias(fn (int $id): string => 'Post '.$id);
        when('get_post_type')->alias(fn (int $id): string => $this->types[$id] ?? 'provider');
        when('get_post_meta')->alias(fn (int $id, string $key) => $this->meta[$id][$key] ?? '');
        when('update_post_meta')->alias(function (int $id, string $key, $value): bool {
            $this->meta[$id][$key] = $value;

            return true;
        });
        when('delete_post_meta')->alias(function (int $id, string $key): bool {
            unset($this->meta[$id][$key]);

            return true;
        });
        when('get_posts')->alias(function (array $args): array {
            if ($args['post_status'] !== 'publish') {
                return array_keys($this->unpublished);
            }

            return $args['post_type'] === 'location' ? array_keys($this->locations) : array_keys($this->providers);
        });
    }

    /**
     * @return array{account: string, listing: array<string, mixed>}
     */
    private function listing(string $name, string $code, array $extra = []): array
    {
        return ['account' => self::ACCOUNT, 'listing' => ['name' => $name, 'storeCode' => $code] + $extra];
    }

    /**
     * A real client whose transport records each PATCH instead of sending it.
     *
     * @param  array<int, array{account: string, listing: array<string, mixed>}>  $listings
     */
    private function client(array $listings, int $patch_status = 200): UAMSWP_FAD_GBP_Client
    {
        when('get_transient')->justReturn($listings);

        return new UAMSWP_FAD_GBP_Client(['client_id' => 'a', 'client_secret' => 'b', 'refresh_token' => 'c'], function (string $method, string $url, array $headers, ?string $body) use ($patch_status): array {
            if (str_contains($url, 'oauth2.googleapis.com')) {
                return [200, (string) json_encode(['access_token' => 't', 'expires_in' => 3600])];
            }

            if ($method !== 'PATCH') {
                throw new \LogicException('The orphan scan made a '.$method.' call to '.$url);
            }

            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
            $this->patches[] = [substr((string) parse_url($url, PHP_URL_PATH), strlen('/v1/')), (array) json_decode((string) $body, true), (string) $query['updateMask'], (string) $body];

            return [$patch_status, '{}'];
        });
    }

    /**
     * @return array<int, string>
     */
    private function scan(array $listings, string $mode, ?array $candidates = null): array
    {
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => $mode];
        $lines = [];

        uamswp_fad_gbp_orphan_scan(function (string $line) use (&$lines): void {
            $lines[] = $line;
        }, $this->client($listings), self::NOW, $candidates);

        return $lines;
    }

    private function candidate(string $name, string $since, string $cause = 'unpublished'): array
    {
        return ['name' => $name, 'cause' => $cause, 'since' => $since, 'label' => 'Post 1'];
    }

    public function test_days_left_count_down_and_the_listing_is_due_on_the_fourteenth_day(): void
    {
        $report = UAMSWP_FAD_GBP_Logic::orphan_report([
            $this->candidate('locations/1', '2026-11-17'),
            $this->candidate('locations/2', '2026-11-05'),
            $this->candidate('locations/3', '2026-11-03'),
            $this->candidate('locations/4', 'not a date'),
        ], '2026-11-17');

        $this->assertSame([14, 2, 0, 14], array_column($report, 'days_left'));
        $this->assertSame([false, false, true, false], array_column($report, 'due'));
    }

    public function test_a_provider_unpublished_with_listings_at_two_locations_reports_and_closes_both(): void
    {
        $this->unpublished[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null], 11 => ['id' => 'locations/2', 'orphaned_since' => null]], UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-03'];
        $listings = [$this->listing('locations/1', 'a'), $this->listing('locations/2', 'b'), $this->listing('locations/9', 'other')];

        $candidates = uamswp_fad_gbp_orphan_candidates(self::NOW);

        $this->assertSame(['locations/1', 'locations/2'], array_column($candidates, 'name'));
        $this->assertSame(['unpublished', 'unpublished'], array_column($candidates, 'cause'));

        $this->scan($listings, 'live');

        $this->assertSame(['locations/1', 'locations/2'], array_column($this->patches, 0), 'both are closed, the unrelated listing is not');
        $this->assertSame('CLOSED_PERMANENTLY', $this->patches[0][1]['openInfo']['status']);
        $this->assertSame('', $this->patches[0][1]['websiteUri']);
        $this->assertSame('', $this->patches[0][1]['profile']['description']);
        $this->assertStringContainsString('"phoneNumbers":{}', $this->patches[0][3]);
        $this->assertSame('openInfo.status,websiteUri,profile.description,phoneNumbers,labels,categories.additionalCategories', $this->patches[0][2]);
        $this->assertSame([], $this->patches[0][1]['labels']);
        $this->assertSame([], $this->patches[0][1]['categories']['additionalCategories']);
        $this->assertArrayNotHasKey('title', $this->patches[0][1], 'the title and address stay');
    }

    public function test_before_the_fourteenth_day_it_is_reported_with_the_days_left_and_nothing_is_closed(): void
    {
        $this->unpublished[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null]], UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-10'];

        $lines = $this->scan([$this->listing('locations/1', 'a')], 'live');

        $this->assertSame([], $this->patches);
        $this->assertStringContainsString('7 days left', $lines[0]);
        $this->assertSame('review', $this->options['uamswp_fad_gbp_log'][0]['action']);
    }

    public function test_a_provider_removed_from_one_of_two_locations_reports_and_closes_only_that_listing(): void
    {
        $this->providers[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null], 11 => ['id' => 'locations/2', 'orphaned_since' => '2026-11-03']]];

        $candidates = uamswp_fad_gbp_orphan_candidates(self::NOW, fn (int $id): array => [10]);

        $this->assertSame(['locations/2'], array_column($candidates, 'name'));
        $this->assertSame(['association removed'], array_column($candidates, 'cause'));

        $this->scan([$this->listing('locations/1', 'a'), $this->listing('locations/2', 'b')], 'live', $candidates);

        $this->assertSame(['locations/2'], array_column($this->patches, 0));
    }

    public function test_republishing_on_day_thirteen_clears_the_record_and_unpublishing_again_restarts_the_period(): void
    {
        $this->unpublished[3] = [];
        $post = (object) ['ID' => 3, 'post_type' => 'provider'];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null]]];

        uamswp_fad_gbp_track_unpublish('draft', 'publish', $post, strtotime('2026-11-04 12:00:00 UTC'));
        $this->assertSame('2026-11-04', $this->meta[3][UAMSWP_FAD_GBP_UNPUBLISHED_META]);

        uamswp_fad_gbp_track_unpublish('publish', 'draft', $post);
        $this->assertArrayNotHasKey(UAMSWP_FAD_GBP_UNPUBLISHED_META, $this->meta[3]);
        $this->unpublished = [];
        $this->assertSame([], uamswp_fad_gbp_orphan_candidates(self::NOW));

        $this->unpublished[3] = [];
        uamswp_fad_gbp_track_unpublish('draft', 'publish', $post, self::NOW);
        $candidates = UAMSWP_FAD_GBP_Logic::orphan_report(uamswp_fad_gbp_orphan_candidates(self::NOW), '2026-11-17');

        $this->assertSame(14, $candidates[0]['days_left']);
        $this->assertFalse($candidates[0]['due']);
    }

    public function test_an_existing_unpublished_date_is_kept_when_the_status_changes_again(): void
    {
        $post = (object) ['ID' => 3, 'post_type' => 'provider'];
        $this->meta[3][UAMSWP_FAD_GBP_UNPUBLISHED_META] = '2026-11-01';

        uamswp_fad_gbp_track_unpublish('trash', 'draft', $post);
        uamswp_fad_gbp_track_unpublish('draft', 'publish', $post, self::NOW);

        $this->assertSame('2026-11-01', $this->meta[3][UAMSWP_FAD_GBP_UNPUBLISHED_META]);
    }

    public function test_a_post_already_unpublished_with_no_date_starts_its_period_today(): void
    {
        $this->unpublished[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null]]];

        $candidates = uamswp_fad_gbp_orphan_candidates(self::NOW);

        $this->assertSame('2026-11-17', $candidates[0]['since']);
        $this->assertSame('2026-11-17', $this->meta[3][UAMSWP_FAD_GBP_UNPUBLISHED_META]);
    }

    public function test_a_posts_that_never_had_a_listing_stored_contributes_nothing(): void
    {
        $this->unpublished[3] = [];

        $this->assertSame([], uamswp_fad_gbp_orphan_candidates(self::NOW));
    }

    public function test_a_silo_in_dry_run_logs_the_planned_closure_and_writes_nothing_to_google(): void
    {
        $lines = $this->scan([$this->listing('locations/1', 'a')], 'dry-run', [$this->candidate('locations/1', '2026-11-01')]);

        $this->assertSame([], $this->patches);
        $this->assertStringContainsString('dry run, nothing written', $lines[0]);
        $this->assertSame('close', $this->options['uamswp_fad_gbp_log'][0]['action']);
        $this->assertStringContainsString('Dry run, would close', $this->options['uamswp_fad_gbp_log'][0]['reason']);
    }

    public function test_a_silo_that_is_off_reports_a_due_listing_and_closes_nothing(): void
    {
        $this->scan([$this->listing('locations/1', 'a')], 'off', [$this->candidate('locations/1', '2026-11-01')]);

        $this->assertSame([], $this->patches);
        $this->assertSame('review', $this->options['uamswp_fad_gbp_log'][0]['action']);
    }

    public function test_a_listing_already_closed_is_not_written_again(): void
    {
        $lines = $this->scan([$this->listing('locations/1', 'a', ['openInfo' => ['status' => 'CLOSED_PERMANENTLY']])], 'live', [$this->candidate('locations/1', '2026-11-01')]);

        $this->assertSame([], $this->patches);
        $this->assertStringContainsString('already closed', $lines[0]);
    }

    public function test_a_failed_close_is_logged_as_an_error_and_the_scan_continues(): void
    {
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];
        $client = $this->client([$this->listing('locations/1', 'a'), $this->listing('locations/2', 'b')], 500);

        $status = uamswp_fad_gbp_orphan_scan(function (string $line): void {}, $client, self::NOW, [$this->candidate('locations/1', '2026-11-01'), $this->candidate('locations/2', '2026-11-01')]);

        $this->assertSame(1, $status);
        $this->assertSame(['locations/1', 'locations/2'], array_column($this->patches, 0));
        $this->assertSame(['error', 'error'], array_column($this->options['uamswp_fad_gbp_log'], 'action'));
    }

    public function test_no_call_the_sync_makes_can_delete_a_listing(): void
    {
        $methods = array_map(fn (\ReflectionMethod $method): string => strtolower($method->getName()), (new \ReflectionClass(UAMSWP_FAD_GBP_Client::class))->getMethods());

        $this->assertSame([], array_values(array_filter($methods, fn (string $name): bool => str_contains($name, 'delete') || str_contains($name, 'remove'))));
    }

    public function test_the_dry_run_option_stops_a_live_silo_from_closing(): void
    {
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];
        $lines = [];

        uamswp_fad_gbp_orphan_scan(function (string $line) use (&$lines): void {
            $lines[] = $line;
        }, $this->client([$this->listing('locations/1', 'a')]), self::NOW, [$this->candidate('locations/1', '2026-11-01')], true);

        $this->assertSame([], $this->patches);
        $this->assertStringContainsString('dry run', $lines[0]);
    }

    public function test_a_draft_copy_of_a_post_never_makes_the_original_s_live_listing_a_candidate(): void
    {
        $this->providers[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null]]];
        $this->unpublished[4] = [];
        $this->meta[4] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null]]];
        $this->locations[7] = [];
        $this->types[7] = 'location';
        $this->meta[7] = [UAMSWP_FAD_GBP_LISTING_META => 'locations/5'];
        $this->unpublished[8] = [];
        $this->types[8] = 'location';
        $this->meta[8] = [UAMSWP_FAD_GBP_LISTING_META => 'locations/5'];

        $this->assertSame([], uamswp_fad_gbp_orphan_candidates(self::NOW, fn (int $id): array => [10]));
    }

    public function test_a_stale_association_date_is_ignored_when_the_provider_is_back_at_the_location(): void
    {
        $this->providers[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => '2026-11-01']]];

        $this->assertSame([], uamswp_fad_gbp_orphan_candidates(self::NOW, fn (int $id): array => [10]));
        $this->assertSame(['locations/1'], array_column(uamswp_fad_gbp_orphan_candidates(self::NOW, fn (int $id): array => [11]), 'name'));
    }

    public function test_two_unpublished_posts_naming_one_listing_keep_the_earlier_date(): void
    {
        $this->unpublished[3] = [];
        $this->unpublished[4] = [];

        foreach ([3 => '2026-11-10', 4 => '2026-11-02'] as $id => $since) {
            $this->meta[$id] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null]], UAMSWP_FAD_GBP_UNPUBLISHED_META => $since];
        }

        $this->assertSame('2026-11-02', uamswp_fad_gbp_orphan_candidates(self::NOW)[0]['since']);
    }

    public function test_a_rate_limit_stops_the_scan_after_the_first_refusal(): void
    {
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];
        $client = $this->client([$this->listing('locations/1', 'a'), $this->listing('locations/2', 'b')], 429);

        uamswp_fad_gbp_orphan_scan(function (string $line): void {}, $client, self::NOW, [$this->candidate('locations/1', '2026-11-01'), $this->candidate('locations/2', '2026-11-01')]);

        $this->assertSame(['locations/1'], array_column($this->patches, 0));
    }

    public function test_the_locked_scan_does_nothing_while_a_sync_holds_the_lock_and_releases_it_after(): void
    {
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => 'live'];
        $locks = [];
        when('get_transient')->alias(function (string $key) use (&$locks) {
            return $key === 'uamswp_fad_gbp_lock' ? ($locks['held'] ?? false) : [];
        });
        when('set_transient')->alias(function (string $key) use (&$locks): bool {
            $locks['set'] = true;

            return true;
        });
        $lines = [];
        $locks['held'] = true;

        $this->assertSame(1, uamswp_fad_gbp_locked_orphan_scan(function (string $line) use (&$lines): void {
            $lines[] = $line;
        }));
        $this->assertSame(['A sync is already running. Try again when it finishes.'], $lines);
        $this->assertArrayNotHasKey('set', $locks);
    }

    public function test_a_listing_is_logged_when_first_seen_and_at_the_last_week_but_not_every_day(): void
    {
        foreach (['2026-11-17', '2026-11-12', '2026-11-10', '2026-11-08', '2026-11-04'] as $since) {
            $this->options['uamswp_fad_gbp_log'] = [];
            $this->scan([$this->listing('locations/1', 'a')], 'live', [$this->candidate('locations/1', $since)]);
            $logged[$since] = count($this->options['uamswp_fad_gbp_log']);
        }

        $this->assertSame(['2026-11-17' => 1, '2026-11-12' => 0, '2026-11-10' => 1, '2026-11-08' => 0, '2026-11-04' => 1], $logged);
    }
}
