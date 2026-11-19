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

    /** @var list<array<string, mixed>> The arguments of each get_posts() call, in order. */
    private array $queries = [];

    /** @var list<string> The transients deleted, in order. */
    private array $deleted_transients = [];

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
        $this->queries = [];
        $this->deleted_transients = [];

        when('apply_filters')->alias(fn (string $hook, $value) => $value);
        when('home_url')->justReturn('https://uamshealth.com');
        when('wp_parse_url')->alias(fn (string $url, int $component) => parse_url($url, $component));
        when('get_option')->alias(fn (string $name, $default = false) => $this->options[$name] ?? $default);
        when('update_option')->alias(function (string $name, $value): bool {
            $this->options[$name] = $value;

            return true;
        });
        when('delete_transient')->alias(function (string $key): bool {
            $this->deleted_transients[] = $key;

            return true;
        });
        when('get_the_title')->alias(fn (int $id): string => 'Post '.$id);
        when('get_post_type')->alias(fn (int $id): string => $this->types[$id] ?? 'provider');
        // Like WordPress, a read that is not single returns every value as a list.
        when('get_post_meta')->alias(function (int $id, string $key, bool $single = false) {
            if (! $single) {
                return isset($this->meta[$id][$key]) ? [$this->meta[$id][$key]] : [];
            }

            return $this->meta[$id][$key] ?? '';
        });
        when('update_post_meta')->alias(function (int $id, string $key, $value): bool {
            $this->meta[$id][$key] = $value;

            return true;
        });
        when('delete_post_meta')->alias(function (int $id, string $key): bool {
            unset($this->meta[$id][$key]);

            return true;
        });
        when('get_posts')->alias(function (array $args): array {
            $this->queries[] = $args;

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

    /**
     * The log entries the scan wrote, oldest first, without the time each was written.
     *
     * @return list<array<string, mixed>>
     */
    private function logged(): array
    {
        return array_reverse(array_map(fn (array $entry): array => array_diff_key($entry, ['time' => true]), $this->options['uamswp_fad_gbp_log'] ?? []));
    }

    /**
     * Run the scan over candidates with a mode for the uams silo, and return its status and lines.
     *
     * @param  array<int, array{account: string, listing: array<string, mixed>}>  $listings
     * @param  array<int, array{name: string, cause: string, since: string, label: string}>  $candidates
     * @return array{0: int, 1: list<string>}
     */
    private function run_scan(array $listings, string $mode, array $candidates, int $patch_status = 200): array
    {
        $this->options['uamswp_fad_gbp_modes'] = ['uams' => $mode];
        $lines = [];

        $status = uamswp_fad_gbp_orphan_scan(function (string $line) use (&$lines): void {
            $lines[] = $line;
        }, $this->client($listings, $patch_status), self::NOW, $candidates);

        return [$status, $lines];
    }

    /**
     * Every silo set to off, then the given ones to their own mode.
     *
     * @param  array<string, string>  $modes
     * @return array<string, string>
     */
    private function modes(array $modes): array
    {
        return array_merge(array_fill_keys(array_keys(uamswp_fad_gbp_silos()), 'off'), $modes);
    }

    public function test_tracking_ignores_anything_that_is_not_a_provider_or_location_post(): void
    {
        $this->meta[3][UAMSWP_FAD_GBP_UNPUBLISHED_META] = '2026-11-01';

        uamswp_fad_gbp_track_unpublish('publish', 'draft', 'post 3');
        uamswp_fad_gbp_track_unpublish('publish', 'draft', (object) ['ID' => 3, 'post_type' => 'page']);
        uamswp_fad_gbp_track_unpublish('draft', 'publish', (object) ['ID' => 4, 'post_type' => 'page'], self::NOW);

        $this->assertSame([3 => [UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-01']], $this->meta);
    }

    public function test_tracking_a_location_starts_its_period_when_it_leaves_publish_and_clears_it_on_return(): void
    {
        $post = (object) ['ID' => 7, 'post_type' => 'location'];

        uamswp_fad_gbp_track_unpublish('draft', 'publish', $post, self::NOW);
        $this->assertSame('2026-11-17', $this->meta[7][UAMSWP_FAD_GBP_UNPUBLISHED_META]);

        uamswp_fad_gbp_track_unpublish('publish', 'draft', $post);
        $this->assertSame([], $this->meta[7]);
    }

    public function test_tracking_a_post_with_no_id_reads_and_writes_post_zero(): void
    {
        $post = (object) ['post_type' => 'provider'];

        uamswp_fad_gbp_track_unpublish('draft', 'publish', $post, self::NOW);
        $this->assertSame([0 => [UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-17']], $this->meta);

        uamswp_fad_gbp_track_unpublish('publish', 'draft', $post);
        $this->assertSame([0 => []], $this->meta);

        $this->meta[0][UAMSWP_FAD_GBP_UNPUBLISHED_META] = '2026-11-01';
        uamswp_fad_gbp_track_unpublish('draft', 'publish', $post, self::NOW);
        $this->assertSame([0 => [UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-01']], $this->meta, 'the date already on post zero is kept');
    }

    public function test_the_candidates_query_every_published_location_and_provider_and_every_unpublished_post(): void
    {
        uamswp_fad_gbp_orphan_candidates(self::NOW, fn (int $id): array => []);

        $this->assertSame([
            ['post_type' => 'location', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids'],
            ['post_type' => 'provider', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids'],
            ['post_type' => ['provider', 'location'], 'post_status' => ['draft', 'pending', 'private', 'future', 'trash'], 'posts_per_page' => -1, 'fields' => 'ids'],
        ], $this->queries);
    }

    public function test_by_default_a_provider_s_locations_are_the_location_ids_of_its_sync_items(): void
    {
        $this->providers[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [
            10 => ['id' => 'locations/10', 'orphaned_since' => '2026-11-01'],
            0 => ['id' => 'locations/0', 'orphaned_since' => '2026-11-01'],
            11 => ['id' => 'locations/11', 'orphaned_since' => '2026-11-01'],
        ]];
        $asked = [];
        when('uamswp_fad_gbp_items_for_provider')->alias(function (int $id, array $categories) use (&$asked): array {
            $asked[] = [$id, $categories];

            // A location id read from a field is a string; an item with none counts as location 0.
            return [['location_id' => '10'], ['name' => 'no location id']];
        });

        $this->assertSame(['locations/11'], array_column(uamswp_fad_gbp_orphan_candidates(self::NOW), 'name'));
        $this->assertSame([[3, []]], $asked);
    }

    public function test_an_unpublished_post_with_no_listing_does_not_stop_the_posts_after_it(): void
    {
        $this->unpublished[3] = [];
        $this->unpublished[4] = [];
        $this->meta[4] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/4', 'orphaned_since' => null]], UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-05'];

        $this->assertSame([['name' => 'locations/4', 'cause' => 'unpublished', 'since' => '2026-11-05', 'label' => 'Post 4 (post 4)']], uamswp_fad_gbp_orphan_candidates(self::NOW));
    }

    public function test_a_name_a_published_post_still_uses_does_not_stop_the_other_names_of_an_unpublished_post(): void
    {
        $this->providers[5] = [];
        $this->meta[5] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null]]];
        $this->unpublished[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null], 11 => ['id' => 'locations/2', 'orphaned_since' => null]], UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-05'];

        $this->assertSame(['locations/2'], array_column(uamswp_fad_gbp_orphan_candidates(self::NOW, fn (int $id): array => [10]), 'name'));
    }

    public function test_two_unpublished_posts_with_the_same_date_keep_the_first_post_s_label(): void
    {
        $this->unpublished[3] = [];
        $this->unpublished[4] = [];

        foreach ([3, 4] as $id) {
            $this->meta[$id] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null]], UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-05'];
        }

        $this->assertSame([['name' => 'locations/1', 'cause' => 'unpublished', 'since' => '2026-11-05', 'label' => 'Post 3 (post 3)']], uamswp_fad_gbp_orphan_candidates(self::NOW));
    }

    public function test_map_entries_that_are_not_arrays_or_have_no_listing_are_ignored(): void
    {
        $this->providers[5] = [];
        $this->meta[5] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['orphaned_since' => null], 11 => ['id' => '', 'orphaned_since' => null]]];
        $this->unpublished[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => 'not an entry', 11 => ['orphaned_since' => null], 12 => ['id' => 'locations/3', 'orphaned_since' => null]], UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-05'];

        $this->assertSame(['locations/3'], array_column(uamswp_fad_gbp_orphan_candidates(self::NOW, fn (int $id): array => []), 'name'));
    }

    public function test_an_association_candidate_needs_a_listing_that_is_not_live_and_not_already_unpublished(): void
    {
        $this->providers[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [
            10 => ['orphaned_since' => '2026-11-01'],
            11 => ['id' => '', 'orphaned_since' => '2026-11-01'],
            15 => ['id' => 'locations/empty-date', 'orphaned_since' => ''],
            12 => ['id' => 'locations/live', 'orphaned_since' => '2026-11-01'],
            13 => ['id' => 'locations/unpublished', 'orphaned_since' => '2026-11-01'],
            14 => ['id' => 'locations/gone', 'orphaned_since' => '2026-11-02'],
        ]];
        $this->providers[5] = [];
        $this->meta[5] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [12 => ['id' => 'locations/live', 'orphaned_since' => null]]];
        $this->unpublished[6] = [];
        $this->meta[6] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [13 => ['id' => 'locations/unpublished', 'orphaned_since' => null]], UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-04'];

        $this->assertSame([
            ['name' => 'locations/unpublished', 'cause' => 'unpublished', 'since' => '2026-11-04', 'label' => 'Post 6 (post 6)'],
            ['name' => 'locations/gone', 'cause' => 'association removed', 'since' => '2026-11-02', 'label' => 'Post 3 (post 3) at location 14'],
        ], uamswp_fad_gbp_orphan_candidates(self::NOW, fn (int $id): array => []));
    }

    public function test_a_provider_s_locations_are_read_once_and_each_location_it_left_is_reported(): void
    {
        $this->providers[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [
            10 => ['id' => 'locations/10', 'orphaned_since' => '2026-11-01'],
            11 => ['id' => 'locations/11', 'orphaned_since' => '2026-11-01'],
            12 => ['id' => 'locations/12', 'orphaned_since' => '2026-11-03'],
        ]];
        $calls = 0;

        $candidates = uamswp_fad_gbp_orphan_candidates(self::NOW, function (int $id) use (&$calls): array {
            $calls++;

            return [10];
        });

        $this->assertSame(1, $calls);
        $this->assertSame([
            ['name' => 'locations/11', 'cause' => 'association removed', 'since' => '2026-11-01', 'label' => 'Post 3 (post 3) at location 11'],
            ['name' => 'locations/12', 'cause' => 'association removed', 'since' => '2026-11-03', 'label' => 'Post 3 (post 3) at location 12'],
        ], $candidates);
    }

    public function test_an_unpublished_location_contributes_its_listing_only_when_it_has_one(): void
    {
        $this->types[7] = 'location';
        $this->unpublished[7] = [];
        $this->meta[7] = [UAMSWP_FAD_GBP_LISTING_META => ''];
        $this->types[8] = 'location';
        $this->unpublished[8] = [];
        $this->meta[8] = [UAMSWP_FAD_GBP_LISTING_META => 'locations/8', UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-05'];

        $this->assertSame([['name' => 'locations/8', 'cause' => 'unpublished', 'since' => '2026-11-05', 'label' => 'Post 8 (post 8)']], uamswp_fad_gbp_orphan_candidates(self::NOW));
        $this->assertSame([UAMSWP_FAD_GBP_LISTING_META => ''], $this->meta[7], 'a location with no listing gets no date either');
    }

    public function test_a_scan_with_no_client_and_no_credentials_stops_before_reading_anything(): void
    {
        when('uamswp_fad_gbp_credentials')->justReturn(null);
        $lines = [];

        $status = uamswp_fad_gbp_orphan_scan(function (string $line) use (&$lines): void {
            $lines[] = $line;
        }, null, self::NOW, []);

        $this->assertSame(1, $status);
        $this->assertSame(['The Google Business Profile credentials are not set'], $lines);
        $this->assertSame([], $this->deleted_transients);
    }

    public function test_a_scan_that_cannot_read_the_listings_stops_with_the_reason(): void
    {
        $client = $this->client([]);
        when('uamswp_fad_gbp_index')->alias(function (): array {
            throw new \RuntimeException('the account could not be read');
        });
        $lines = [];

        $status = uamswp_fad_gbp_orphan_scan(function (string $line) use (&$lines): void {
            $lines[] = $line;
        }, $client, self::NOW, [$this->candidate('locations/1', '2026-11-01')]);

        $this->assertSame(1, $status);
        $this->assertSame(['Stopped: the account could not be read'], $lines);
    }

    public function test_a_scan_that_closes_nothing_clears_the_index_reports_each_listing_and_returns_zero(): void
    {
        [$status, $lines] = $this->run_scan([$this->listing('locations/1', 'a'), $this->listing('locations/2', 'b')], 'live', [
            $this->candidate('locations/9', '2026-11-01'),
            $this->candidate('locations/1', '2026-11-10'),
            $this->candidate('locations/2', '2026-11-12'),
        ]);

        $this->assertSame(0, $status);
        $this->assertSame(['uamswp_fad_gbp_index'], $this->deleted_transients);
        $this->assertSame([
            'locations/9  not on Google any more (Post 1)',
            'locations/1  unpublished  orphaned since 2026-11-10 (Post 1)  7 days left',
            'locations/2  unpublished  orphaned since 2026-11-12 (Post 1)  9 days left',
            // The listing Google no longer returns is past its fourteen days, and the count includes it.
            '3 orphaned listings, 1 due',
        ], $lines);
        $this->assertSame([], $this->patches);
    }

    public function test_a_closed_listing_is_logged_with_its_store_code_silo_mode_and_the_fields_written(): void
    {
        $listings = [['account' => self::ACCOUNT, 'listing' => ['name' => 'locations/1']], $this->listing('locations/2', 'b')];

        [$status, $lines] = $this->run_scan($listings, 'live', [$this->candidate('locations/1', '2026-11-01'), $this->candidate('locations/2', '2026-11-02', 'association removed')]);

        $entry = [
            'silo' => 'uams',
            'kind' => 'listing',
            'mode' => 'live',
            'action' => 'close',
            'fields' => array_keys(UAMSWP_FAD_GBP_Logic::close_patch()['fields']),
            'attributes' => [],
            'unresolved_categories' => [],
        ];
        $this->assertSame(0, $status);
        $this->assertSame([
            ['store_code' => 'locations/1'] + $entry + ['listing_name' => 'locations/1', 'reason' => 'Closed (unpublished since 2026-11-01): Post 1'],
            ['store_code' => 'b'] + $entry + ['listing_name' => 'locations/2', 'reason' => 'Closed (association removed since 2026-11-02): Post 1'],
        ], $this->logged());
        $this->assertSame([
            'locations/1  unpublished  orphaned since 2026-11-01 (Post 1)  closed',
            'locations/2  association removed  orphaned since 2026-11-02 (Post 1)  closed',
            '2 orphaned listings, 2 due',
        ], $lines);
    }

    public function test_a_listing_already_closed_does_not_stop_the_listings_after_it(): void
    {
        $listings = [$this->listing('locations/1', 'a', ['openInfo' => ['status' => 'CLOSED_PERMANENTLY']]), $this->listing('locations/2', 'b')];

        [, $lines] = $this->run_scan($listings, 'live', [$this->candidate('locations/1', '2026-11-01'), $this->candidate('locations/2', '2026-11-01')]);

        $this->assertSame(['locations/2'], array_column($this->patches, 0));
        $this->assertSame('locations/1  already closed  Post 1', $lines[0]);
    }

    public function test_each_listing_due_in_an_off_silo_gets_a_line_and_a_log_entry(): void
    {
        [, $lines] = $this->run_scan([$this->listing('locations/1', 'a'), $this->listing('locations/2', 'b')], 'off', [$this->candidate('locations/1', '2026-11-01'), $this->candidate('locations/2', '2026-11-01')]);

        $this->assertSame([
            'locations/1  unpublished  orphaned since 2026-11-01 (Post 1)  due, silo off',
            'locations/2  unpublished  orphaned since 2026-11-01 (Post 1)  due, silo off',
            '2 orphaned listings, 2 due',
        ], $lines);
        $this->assertSame(['review', 'review'], array_column($this->logged(), 'action'));
        $this->assertSame('Due to close (unpublished since 2026-11-01) but silo uams is off: Post 1', $this->logged()[0]['reason']);
    }

    public function test_each_listing_due_in_a_dry_run_silo_gets_a_line(): void
    {
        [, $lines] = $this->run_scan([$this->listing('locations/1', 'a'), $this->listing('locations/2', 'b')], 'dry-run', [$this->candidate('locations/1', '2026-11-01'), $this->candidate('locations/2', '2026-11-01')]);

        $this->assertSame([
            'locations/1  unpublished  orphaned since 2026-11-01 (Post 1)  due, dry run, nothing written',
            'locations/2  unpublished  orphaned since 2026-11-01 (Post 1)  due, dry run, nothing written',
            '2 orphaned listings, 2 due',
        ], $lines);
        $this->assertSame([], $this->patches);
    }

    public function test_a_failed_close_names_the_listing_and_the_error(): void
    {
        [$status, $lines] = $this->run_scan([$this->listing('locations/1', 'a')], 'live', [$this->candidate('locations/1', '2026-11-01')], 500);

        $reason = (string) $this->logged()[0]['reason'];
        $this->assertSame(1, $status);
        $this->assertStringStartsWith('Closing locations/1 failed: ', $reason);
        $this->assertSame('locations/1  unpublished  orphaned since 2026-11-01 (Post 1)  FAILED: '.substr($reason, strlen('Closing locations/1 failed: ')), $lines[0]);
    }

    public function test_a_rate_limit_is_logged_once_as_retryable_and_ends_the_scan_with_a_message(): void
    {
        [, $lines] = $this->run_scan([$this->listing('locations/1', 'a'), $this->listing('locations/2', 'b')], 'live', [$this->candidate('locations/1', '2026-11-01'), $this->candidate('locations/2', '2026-11-01')], 429);

        $this->assertCount(1, $this->logged());
        $this->assertTrue($this->logged()[0]['retryable']);
        $this->assertSame('error', $this->logged()[0]['action']);
        $this->assertSame(['Stopped: Google is rate limiting this account. Run again later.', '2 orphaned listings, 2 due'], array_slice($lines, 1));
    }

    /**
     * @return array<string, array{int, int}>
     */
    public static function days_left_logging(): array
    {
        return ['14' => [14, 1], '13' => [13, 0], '8' => [8, 0], '7' => [7, 1], '6' => [6, 0], '4' => [4, 0], '3' => [3, 1], '2' => [2, 0], '1' => [1, 1]];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('days_left_logging')]
    public function test_a_listing_that_is_not_due_is_logged_only_on_the_days_that_mark_the_countdown(int $days_left, int $logged): void
    {
        $since = gmdate('Y-m-d', self::NOW - (14 - $days_left) * 86400);

        [, $lines] = $this->run_scan([$this->listing('locations/1', 'a')], 'live', [$this->candidate('locations/1', $since)]);

        $this->assertStringEndsWith(sprintf('  %d days left', $days_left), $lines[0]);
        $this->assertCount($logged, $this->logged());
    }

    public function test_the_silo_for_an_account_reads_a_single_account_and_a_numbered_silo(): void
    {
        $this->assertSame('x', uamswp_fad_gbp_silo_for_account('accounts/1', ['x' => ['accounts' => 'accounts/1']]));
        $this->assertSame('7', uamswp_fad_gbp_silo_for_account('accounts/2', [7 => ['accounts' => ['accounts/2']]]));
        $this->assertNull(uamswp_fad_gbp_silo_for_account('accounts/3', ['x' => ['accounts' => ['accounts/1']], 'y' => []]));
    }

    public function test_the_daily_scan_runs_only_with_credentials_and_a_silo_that_is_not_off(): void
    {
        $locks = [];
        when('get_transient')->alias(fn (string $key) => $key === 'uamswp_fad_gbp_lock' ? false : []);
        when('set_transient')->alias(function (string $key, $value, int $expiration) use (&$locks): bool {
            $locks[] = [$key, $value, $expiration];

            return true;
        });
        $credentials = null;
        when('uamswp_fad_gbp_credentials')->alias(function () use (&$credentials): ?array {
            return $credentials;
        });

        $this->options['uamswp_fad_gbp_modes'] = $this->modes(['uams' => 'live']);
        uamswp_fad_gbp_run_orphan_scan();
        $this->assertSame([], $locks, 'no credentials');

        $credentials = ['client_id' => 'a', 'client_secret' => 'b', 'refresh_token' => 'c'];
        $this->options['uamswp_fad_gbp_modes'] = $this->modes([]);
        uamswp_fad_gbp_run_orphan_scan();
        $this->assertSame([], $locks, 'every silo off');

        $this->options['uamswp_fad_gbp_modes'] = $this->modes(['uams' => 'dry-run']);
        uamswp_fad_gbp_run_orphan_scan();
        $this->assertSame([['uamswp_fad_gbp_lock', 1, 300]], $locks);
    }

    public function test_the_locked_scan_holds_the_lock_for_five_minutes_and_closes_unless_asked_for_a_dry_run(): void
    {
        $locks = [];
        when('get_transient')->alias(fn (string $key) => $key === 'uamswp_fad_gbp_lock' ? false : [$this->listing('locations/1', 'a')]);
        when('set_transient')->alias(function (string $key, $value, int $expiration) use (&$locks): bool {
            $locks[] = [$key, $value, $expiration];

            return true;
        });
        when('uamswp_fad_gbp_credentials')->justReturn(['client_id' => 'a', 'client_secret' => 'b', 'refresh_token' => 'c']);
        when('uamswp_fad_gbp_transport')->alias(function (string $method, string $url, array $headers, ?string $body): array {
            if (str_contains($url, 'oauth2.googleapis.com')) {
                return [200, (string) json_encode(['access_token' => 't', 'expires_in' => 3600])];
            }

            $this->patches[] = [$method, $url];

            return [200, '{}'];
        });
        $this->options['uamswp_fad_gbp_modes'] = $this->modes(['uams' => 'live']);
        $this->unpublished[3] = [];
        $this->meta[3] = [UAMSWP_FAD_GBP_LISTING_MAP_META => [10 => ['id' => 'locations/1', 'orphaned_since' => null]], UAMSWP_FAD_GBP_UNPUBLISHED_META => '2026-11-01'];

        $status = uamswp_fad_gbp_locked_orphan_scan(function (string $line): void {});

        $this->assertSame(0, $status);
        $this->assertSame([['uamswp_fad_gbp_lock', 1, 300]], $locks);
        $this->assertSame('uamswp_fad_gbp_lock', end($this->deleted_transients));
        $this->assertSame('PATCH', $this->patches[0][0] ?? null, 'without the dry-run flag a live silo closes the listing');
    }

    public function test_registering_hooks_tracks_status_changes_and_schedules_the_daily_scan_once(): void
    {
        $actions = [];
        when('add_action')->alias(function (string $hook, string $callback, int $priority = 10, int $args = 1) use (&$actions): bool {
            $actions[] = [$hook, $callback, $priority, $args];

            return true;
        });
        $scheduled = [];
        when('wp_schedule_event')->alias(function (int $time, string $recurrence, string $hook) use (&$scheduled): bool {
            $scheduled[] = [$time, $recurrence, $hook];

            return true;
        });

        when('wp_next_scheduled')->justReturn(1794900000);
        uamswp_fad_gbp_register_orphan_hooks();
        $this->assertSame([], $scheduled, 'an event already scheduled is not scheduled again');

        when('wp_next_scheduled')->justReturn(false);
        $before = time();
        uamswp_fad_gbp_register_orphan_hooks();
        $after = time();

        $this->assertSame([
            ['transition_post_status', 'uamswp_fad_gbp_track_unpublish', 10, 3],
            ['uamswp_fad_gbp_orphan_scan', 'uamswp_fad_gbp_run_orphan_scan', 10, 1],
        ], array_slice($actions, 0, 2));
        $this->assertCount(1, $scheduled);
        $this->assertSame(['daily', UAMSWP_FAD_GBP_ORPHAN_HOOK], array_slice($scheduled[0], 1));
        $this->assertGreaterThanOrEqual($before + 3600, $scheduled[0][0]);
        $this->assertLessThanOrEqual($after + 3600, $scheduled[0][0]);
    }
}
