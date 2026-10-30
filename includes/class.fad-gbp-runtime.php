<?php

/**
 * Business Profile sync: the WordPress wiring.
 *
 * Reads the credentials and silo modes, queues a debounced sync when a provider or location
 * is saved, and runs the sync from cron. The decisions live in UAMSWP_FAD_GBP_Sync and
 * UAMSWP_FAD_GBP_Logic, and the API calls in UAMSWP_FAD_GBP_Client; this file only connects
 * them to WordPress.
 */

declare(strict_types=1);

const UAMSWP_FAD_GBP_SYNC_HOOK = 'uamswp_fad_gbp_sync_post';

/** The post meta key that remembers the fingerprint of a provider's last settled run. */
const UAMSWP_FAD_GBP_HASH_META = '_uamswp_fad_gbp_hash';

/** How many times a rate-limited provider is retried before the job gives up. */
const UAMSWP_FAD_GBP_MAX_ATTEMPTS = 5;

/**
 * The silos, each with the brand that selects it and the Google account group that owns its
 * provider and location listings.
 *
 * @return array<string, array{brand: ?string, accounts: array{provider: string, location: string}}>
 */
function uamswp_fad_gbp_silos(): array
{
    return apply_filters('uamswp_fad_gbp_silos', [
        'uams' => [
            'brand' => null,
            'accounts' => ['provider' => 'accounts/112667655000425532878', 'location' => 'accounts/105283038566196415769'],
        ],
        'arkansas-childrens' => [
            'brand' => 'arkansas-childrens',
            'accounts' => ['provider' => 'accounts/109394488038091564790', 'location' => 'accounts/117883475495991069009'],
        ],
        'baptist-health' => [
            'brand' => 'baptist-health',
            'accounts' => ['provider' => 'accounts/114902564947343534669', 'location' => 'accounts/107080311706776481588'],
        ],
    ]);
}

/**
 * The OAuth credentials, or null when any of the three is missing.
 *
 * Each comes from a constant (wp-config.php) or an environment variable of the same name.
 * They are never stored in the database.
 *
 * @param  (callable(string): string)|null  $lookup  Returns a credential by name; for tests.
 * @return array{client_id: string, client_secret: string, refresh_token: string}|null
 */
function uamswp_fad_gbp_credentials(?callable $lookup = null): ?array
{
    $lookup ??= static function (string $name): string {
        if (defined($name)) {
            return (string) constant($name);
        }

        return (string) getenv($name);
    };

    $credentials = [
        'client_id' => $lookup('GOOGLE_BUSINESS_PROFILE_CLIENT_ID'),
        'client_secret' => $lookup('GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET'),
        'refresh_token' => $lookup('GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN'),
    ];

    return in_array('', $credentials, true) ? null : $credentials;
}

/**
 * Each silo's configured mode. A silo with no valid saved mode is in dry-run.
 *
 * @return array<string, string>
 */
function uamswp_fad_gbp_modes(): array
{
    $saved = get_option('uamswp_fad_gbp_modes', []);
    $saved = is_array($saved) ? $saved : [];

    $modes = [];

    foreach (array_keys(uamswp_fad_gbp_silos()) as $silo) {
        $mode = $saved[$silo] ?? '';
        $modes[$silo] = in_array($mode, UAMSWP_FAD_GBP_Logic::MODES, true) ? $mode : 'dry-run';
    }

    return $modes;
}

/**
 * Whether this site is production, where a live silo may write.
 */
function uamswp_fad_gbp_is_production(): bool
{
    $host = (string) wp_parse_url(home_url(), PHP_URL_HOST);

    return (bool) apply_filters('uamswp_fad_gbp_is_production', $host === 'uamshealth.com');
}

/**
 * The posts whose listings a provider or location post affects, itself included.
 *
 * A provider covers itself. A location covers itself, its child locations and the providers at
 * any of them, since their listings carry the location's address and phone.
 *
 * @return array<int, int> Empty for any other post type.
 */
function uamswp_fad_gbp_covered_post_ids(int $post_id): array
{
    $type = get_post_type($post_id);

    if ($type === 'provider') {
        return [$post_id];
    }

    if ($type !== 'location') {
        return [];
    }

    $locations = array_merge([$post_id], uamswp_fad_gbp_child_location_ids($post_id));

    return array_merge($locations, uamswp_fad_gbp_provider_ids_for_locations($locations));
}

/**
 * Every published provider, then every published location.
 *
 * @return array<int, int>
 */
function uamswp_fad_gbp_all_post_ids(): array
{
    $ids = [];

    foreach (['provider', 'location'] as $post_type) {
        $ids = array_merge($ids, array_map('intval', get_posts(['post_type' => $post_type, 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids'])));
    }

    return $ids;
}

/**
 * Queue a debounced sync for the providers a save affects.
 *
 * Saving a provider queues that provider. Saving a location queues every provider at it and at
 * its child locations, since their listings carry the location's address and phone. A job
 * already waiting for a provider is not queued again.
 *
 * @param  mixed  $post_id  The `acf/save_post` argument: a post ID, or a string for options and users.
 * @param  (callable(string): string)|null  $lookup  Credential lookup; for tests.
 */
function uamswp_fad_gbp_queue_on_save($post_id, ?callable $lookup = null): void
{
    if (! is_numeric($post_id)) {
        return;
    }

    $post_id = (int) $post_id;

    if (uamswp_fad_gbp_credentials($lookup) === null || array_diff(array_values(uamswp_fad_gbp_modes()), ['off']) === []) {
        return;
    }

    if (get_post_status($post_id) !== 'publish') {
        return;
    }

    $providers = uamswp_fad_gbp_covered_post_ids($post_id);

    if ($providers === []) {
        return;
    }

    foreach (array_values($providers) as $position => $provider_id) {
        if (wp_next_scheduled(UAMSWP_FAD_GBP_SYNC_HOOK, [$provider_id]) !== false) {
            continue;
        }

        wp_schedule_single_event(time() + 120 + $position * 30, UAMSWP_FAD_GBP_SYNC_HOOK, [$provider_id]);
    }
}

/**
 * Queue a sync when a provider or location goes live by any route.
 *
 * `acf/save_post` does not fire when WordPress publishes a scheduled post, nor for a change
 * made outside the edit screen (quick edit, bulk edit, the REST API). This catches the change of status instead. On an edit-screen
 * publish it fires before the ACF fields are stored; the delay on the queued job covers that,
 * and a job already waiting is not queued twice.
 *
 * @param  string  $new_status
 * @param  string  $old_status
 * @param  object  $post  The `WP_Post`.
 * @param  (callable(string): string)|null  $lookup  Credential lookup; for tests.
 */
function uamswp_fad_gbp_queue_on_publish($new_status, $old_status, $post, ?callable $lookup = null): void
{
    if ($new_status !== 'publish' || $old_status === 'publish') {
        return;
    }

    uamswp_fad_gbp_queue_on_save((int) $post->ID, $lookup);
}

/**
 * Queue a sync for every published provider and location, one job at a time.
 *
 * For the first run of a silo, and to recheck every listing against Google. Each job is the same cron job a save queues, spaced apart so the single-run
 * lock is rarely contended. A provider with a job already waiting is not queued again.
 *
 * @param  (callable(string): string)|null  $lookup  Credential lookup; for tests.
 * @return int How many providers and locations were queued.
 */
function uamswp_fad_gbp_queue_all(?callable $lookup = null): int
{
    if (uamswp_fad_gbp_credentials($lookup) === null || array_diff(array_values(uamswp_fad_gbp_modes()), ['off']) === []) {
        return 0;
    }

    $queued = 0;

    foreach (uamswp_fad_gbp_all_post_ids() as $post_id) {
        if (wp_next_scheduled(UAMSWP_FAD_GBP_SYNC_HOOK, [$post_id]) !== false) {
            continue;
        }

        // An explicit run checks every listing against Google, so it forgets what was settled.
        delete_post_meta($post_id, UAMSWP_FAD_GBP_HASH_META);
        wp_schedule_single_event(time() + 30 + $queued * 15, UAMSWP_FAD_GBP_SYNC_HOOK, [$post_id]);
        $queued++;
    }

    return $queued;
}

/**
 * The published child locations of a location.
 *
 * @return array<int, int>
 */
function uamswp_fad_gbp_child_location_ids(int $location_id): array
{
    return array_map('intval', get_posts([
        'post_type' => 'location',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => 'location_parent_id',
        'meta_value' => $location_id,
    ]));
}

/**
 * The published providers practicing at any of the given locations.
 *
 * @param  array<int, int>  $location_ids
 * @return array<int, int>
 */
function uamswp_fad_gbp_provider_ids_for_locations(array $location_ids): array
{
    $clauses = ['relation' => 'OR'];

    foreach ($location_ids as $location_id) {
        // ACF stores a relationship as a serialized array of quoted ID strings.
        $clauses[] = ['key' => 'physician_locations', 'value' => '"'.$location_id.'"', 'compare' => 'LIKE'];
    }

    return array_map('intval', get_posts([
        'post_type' => 'provider',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => $clauses,
    ]));
}

/**
 * The HTTP transport for the API client.
 *
 * @param  array<string, string>  $headers
 * @return array{0: int, 1: string} The status and body; status 0 when the request could not be made.
 */
function uamswp_fad_gbp_transport(string $method, string $url, array $headers, ?string $body): array
{
    $response = wp_remote_request($url, ['method' => $method, 'headers' => $headers, 'body' => $body, 'timeout' => 45]);

    if (is_wp_error($response)) {
        return [0, ''];
    }

    return [(int) wp_remote_retrieve_response_code($response), (string) wp_remote_retrieve_body($response)];
}

/**
 * Every listing the credentials can see, with the account group it is in.
 *
 * Kept for five minutes, so a burst of saves does not read every group each time.
 *
 * @return array<int, array{account: string, listing: array<string, mixed>}>
 */
function uamswp_fad_gbp_index(UAMSWP_FAD_GBP_Client $client): array
{
    $cached = get_transient('uamswp_fad_gbp_index');
    if (is_array($cached)) {
        return $cached;
    }

    $listings = [];
    foreach (array_keys($client->location_groups()) as $account) {
        foreach ($client->locations($account) as $listing) {
            $listings[] = ['account' => $account, 'listing' => $listing];
        }
    }

    set_transient('uamswp_fad_gbp_index', $listings, 300);

    return $listings;
}

/**
 * Google's category display names mapped to resource names, kept for a day.
 *
 * @return array<string, string>
 */
function uamswp_fad_gbp_categories(UAMSWP_FAD_GBP_Client $client): array
{
    $cached = get_transient('uamswp_fad_gbp_categories');
    if (is_array($cached)) {
        return $cached;
    }

    $categories = $client->category_ids();
    set_transient('uamswp_fad_gbp_categories', $categories, DAY_IN_SECONDS);

    return $categories;
}

/**
 * A location's silo, from its own brand fields.
 */
function uamswp_fad_gbp_silo_for_location(int $location_id): ?string
{
    return UAMSWP_FAD_GBP_Logic::resolve_silo(
        get_post_meta($location_id, 'schema_brandorg_query', true),
        get_post_meta($location_id, 'schema_brandorg', true),
        uamswp_fad_gbp_silos()
    );
}

/**
 * What the sync needs to know about a location, by slug: its silo, from its brand fields, and
 * whether providers practicing there are excluded from Google Business Profiles.
 *
 * The silo is null when the brand has no silo, and the location is treated as not excluding
 * its providers when it cannot be found.
 *
 * @return array{silo: ?string, providers_excluded: bool}
 */
function uamswp_fad_gbp_location_info(string $slug): array
{
    $ids = get_posts(['name' => $slug, 'post_type' => 'location', 'post_status' => 'publish', 'posts_per_page' => 1, 'fields' => 'ids']);

    if ($ids === []) {
        return ['silo' => null, 'providers_excluded' => false];
    }

    $location_id = (int) $ids[0];

    return [
        'silo' => uamswp_fad_gbp_silo_for_location($location_id),
        'providers_excluded' => function_exists('get_field') && UAMSWP_FAD_GBP_Logic::is_yes(get_field('location_gmb_exclude_providers', $location_id)),
    ];
}

/**
 * The sync items for one provider: one per published location the export would list them at.
 *
 * @param  array<string, string>  $categories
 * @return array<int, array{store_code: string, kind: string, silo: ?string, fields: array<string, mixed>, unresolved: array<int, string>, excluded: bool}>
 */
function uamswp_fad_gbp_items_for_provider(int $provider_id, array $categories): array
{
    $table = uamswp_fad_gmb_provider_table([$provider_id]);
    $items = [];

    foreach ($table['body'] as $row) {
        $store_code = (string) ($row[0] ?? '');
        $parts = UAMSWP_FAD_GBP_Logic::split_provider_store_code($store_code);

        if ($parts === null) {
            continue;
        }

        $location = uamswp_fad_gbp_location_info($parts[1]);

        $items[] = [
            'store_code' => $store_code,
            'kind' => 'provider',
            'silo' => $location['silo'],
            'excluded' => $location['providers_excluded'],
            'fields' => UAMSWP_FAD_GBP_Logic::fields_from_row($row, $categories),
            'unresolved' => UAMSWP_FAD_GBP_Logic::unresolved_categories($row, $categories),
        ];
    }

    return $items;
}

/**
 * The sync item for one location, or none when it is excluded or has no store code.
 *
 * @param  array<string, string>  $categories
 * @return array<int, array<string, mixed>>
 */
function uamswp_fad_gbp_items_for_location(int $location_id, array $categories): array
{
    $table = uamswp_fad_gmb_location_table([$location_id]);
    $silo = uamswp_fad_gbp_silo_for_location($location_id);
    $items = [];

    foreach ($table['body'] as $row) {
        $item = UAMSWP_FAD_GBP_Logic::location_item($row, $categories, $silo);

        if ($item !== null) {
            $items[] = $item;
        }
    }

    return $items;
}

/**
 * The sync items for a provider or a location post.
 *
 * @param  array<string, string>  $categories
 * @return array<int, array<string, mixed>>
 */
function uamswp_fad_gbp_items_for_post(int $post_id, array $categories): array
{
    return get_post_type($post_id) === 'location'
        ? uamswp_fad_gbp_items_for_location($post_id, $categories)
        : uamswp_fad_gbp_items_for_provider($post_id, $categories);
}

/**
 * Keep a log entry for the settings screen. Listings already in sync are not logged.
 *
 * @param  array<string, mixed>  $entry
 */
function uamswp_fad_gbp_log(array $entry): void
{
    if (($entry['action'] ?? '') === 'noop') {
        return;
    }

    $log = get_option('uamswp_fad_gbp_log', []);
    $log = is_array($log) ? $log : [];

    array_unshift($log, ['time' => time()] + $entry);

    update_option('uamswp_fad_gbp_log', array_slice($log, 0, 300), false);
}

/**
 * Seconds to wait before retry number $attempt (1 is the first), doubling from a minute to an hour.
 */
function uamswp_fad_gbp_retry_delay(int $attempt): int
{
    return min(3600, 60 * 2 ** max(0, $attempt - 1));
}

/**
 * Put a rate-limited post back in the queue with a growing delay, or give up after the limit.
 */
function uamswp_fad_gbp_schedule_retry(int $post_id): void
{
    $key = 'uamswp_fad_gbp_attempts_'.$post_id;
    $attempt = (int) get_transient($key) + 1;

    if ($attempt > UAMSWP_FAD_GBP_MAX_ATTEMPTS) {
        delete_transient($key);
        uamswp_fad_gbp_log(['store_code' => '', 'silo' => null, 'kind' => get_post_type($post_id) === 'location' ? 'location' : 'provider', 'mode' => 'n/a', 'action' => 'error', 'reason' => 'Gave up on post '.$post_id.' after '.UAMSWP_FAD_GBP_MAX_ATTEMPTS.' rate-limited attempts', 'fields' => [], 'unresolved_categories' => []]);

        return;
    }

    set_transient($key, $attempt, DAY_IN_SECONDS);

    if (wp_next_scheduled(UAMSWP_FAD_GBP_SYNC_HOOK, [$post_id]) === false) {
        wp_schedule_single_event(time() + uamswp_fad_gbp_retry_delay($attempt), UAMSWP_FAD_GBP_SYNC_HOOK, [$post_id]);
    }
}

/**
 * The cron job: sync the listings of one provider or location.
 *
 * One run at a time, so two jobs cannot both decide a listing is missing and create it twice.
 * A job that finds another running puts itself back in the queue.
 */
function uamswp_fad_gbp_run_post(int $post_id): void
{
    $credentials = uamswp_fad_gbp_credentials();
    if ($credentials === null || get_post_status($post_id) !== 'publish') {
        return;
    }

    if (get_transient('uamswp_fad_gbp_lock')) {
        wp_schedule_single_event(time() + 60, UAMSWP_FAD_GBP_SYNC_HOOK, [$post_id]);

        return;
    }

    set_transient('uamswp_fad_gbp_lock', 1, 300);

    try {
        $client = new UAMSWP_FAD_GBP_Client($credentials, 'uamswp_fad_gbp_transport');

        $modes = uamswp_fad_gbp_modes();
        $production = uamswp_fad_gbp_is_production();
        $items = uamswp_fad_gbp_items_for_post($post_id, uamswp_fad_gbp_categories($client));
        $hash = UAMSWP_FAD_GBP_Logic::fingerprint($items, $modes, $production, defined('UAMS_FAD_VERSION') ? (string) UAMS_FAD_VERSION : '');

        // Nothing that decides the sync has changed since its last settled run: no Google call.
        if (get_post_meta($post_id, UAMSWP_FAD_GBP_HASH_META, true) === $hash) {
            return;
        }

        $sync = new UAMSWP_FAD_GBP_Sync(
            $client,
            ['silos' => uamswp_fad_gbp_silos(), 'modes' => $modes, 'is_production' => $production],
            UAMSWP_FAD_GBP_Sync::build_index(uamswp_fad_gbp_index($client)),
            'uamswp_fad_gbp_log'
        );

        $entries = $sync->run($items);

        // A write changes what the next job should see.
        if (array_intersect(array_column($entries, 'reason'), ['Created', 'Updated']) !== []) {
            delete_transient('uamswp_fad_gbp_index');
        }

        if (in_array(true, array_column($entries, 'retryable'), true)) {
            uamswp_fad_gbp_schedule_retry($post_id);
        } else {
            delete_transient('uamswp_fad_gbp_attempts_'.$post_id);

            if (UAMSWP_FAD_GBP_Logic::is_settled($entries)) {
                update_post_meta($post_id, UAMSWP_FAD_GBP_HASH_META, $hash);
            }
        }
    } catch (UAMSWP_FAD_GBP_Rate_Limited $rateLimited) {
        uamswp_fad_gbp_log(['store_code' => '', 'silo' => null, 'kind' => get_post_type($post_id) === 'location' ? 'location' : 'provider', 'mode' => 'n/a', 'action' => 'error', 'reason' => $rateLimited->getMessage(), 'fields' => [], 'unresolved_categories' => []]);
        uamswp_fad_gbp_schedule_retry($post_id);
    } catch (RuntimeException $runtimeException) {
        uamswp_fad_gbp_log(['store_code' => '', 'silo' => null, 'kind' => get_post_type($post_id) === 'location' ? 'location' : 'provider', 'mode' => 'n/a', 'action' => 'error', 'reason' => $runtimeException->getMessage(), 'fields' => [], 'unresolved_categories' => []]);
    } finally {
        delete_transient('uamswp_fad_gbp_lock');
    }
}

/**
 * Register the save hook and the cron job.
 */
function uamswp_fad_gbp_register_hooks(): void
{
    add_action('acf/save_post', 'uamswp_fad_gbp_queue_on_save', 30);
    add_action('transition_post_status', 'uamswp_fad_gbp_queue_on_publish', 10, 3);
    add_action(UAMSWP_FAD_GBP_SYNC_HOOK, 'uamswp_fad_gbp_run_post');
}
