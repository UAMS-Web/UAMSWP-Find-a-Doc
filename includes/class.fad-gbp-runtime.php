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

/** The option that holds the moment creates are paused until, after Google's daily create limit. */
const UAMSWP_FAD_GBP_PAUSE_OPTION = 'uamswp_fad_gbp_creates_paused_until';

/** The option that holds how many creates were made on a Pacific day: `day` and `count`. */
const UAMSWP_FAD_GBP_COUNT_OPTION = 'uamswp_fad_gbp_create_count';

/** The option holding the creates made on each recent Pacific day, for the dashboard. */
const UAMSWP_FAD_GBP_HISTORY_OPTION = 'uamswp_fad_gbp_create_history';

/** The option holding the moment a per-minute rate limit backoff ends. */
const UAMSWP_FAD_GBP_BACKOFF_OPTION = 'uamswp_fad_gbp_backoff_until';

/** Creates allowed per Pacific day by default, below the 102 Google accepted on 2026-10-30. */
const UAMSWP_FAD_GBP_DEFAULT_CREATE_BUDGET = 80;

/** How many recent entries the activity log keeps. */
const UAMSWP_FAD_GBP_LOG_CAP = 300;

/** How many review and error entries are kept apart from the activity log, one per store code. */
const UAMSWP_FAD_GBP_ATTENTION_CAP = 1000;

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
        $ids = array_merge($ids, array_map(intval(...), get_posts(['post_type' => $post_type, 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids'])));
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
    return array_map(intval(...), get_posts([
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

    return array_map(intval(...), get_posts([
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
    set_transient('uamswp_fad_gbp_index_at', time(), 300);

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
 * Meta key on a location post: the Google location name (`locations/123`) of its listing.
 */
const UAMSWP_FAD_GBP_LISTING_META = 'uamswp_fad_gbp_listing_id';

/**
 * Meta key on a provider post: a map of location post ID to the Google location name and the day it was first seen orphaned.
 */
const UAMSWP_FAD_GBP_LISTING_MAP_META = 'uamswp_fad_gbp_listing_ids';

/**
 * Meta key on a location post: the addresses of the photos already sent to its listing.
 */
const UAMSWP_FAD_GBP_PHOTOS_META = 'uamswp_fad_gbp_photos_sent';

/**
 * The addresses of the photos already sent to the listing an item is stored against.
 *
 * Empty when no listing is stored, so a listing found only by its store code is sent every photo once and then recorded.
 *
 * @param  array<string, mixed>  $item
 * @return array<int, string>
 */
function uamswp_fad_gbp_photos_sent_for(array $item): array
{
    $post_id = (int) ($item['post_id'] ?? 0);

    if ($post_id === 0 || ($item['stored_name'] ?? '') === '') {
        return [];
    }

    if ($item['kind'] === 'location') {
        $sent = get_post_meta($post_id, UAMSWP_FAD_GBP_PHOTOS_META, true);
    } else {
        $map = get_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_MAP_META, true);
        $sent = is_array($map) ? ($map[(int) ($item['location_id'] ?? 0)]['photos'] ?? []) : [];
    }

    return is_array($sent) ? array_values(array_map(strval(...), $sent)) : [];
}

/**
 * Record the photos a run sent, on the post and listing they went to.
 *
 * A new listing starts its record over; an existing one adds to it. Nothing is recorded unless the listing's ID is stored
 * on the post, since the record belongs to that listing.
 *
 * @param  array<string, mixed>  $item
 * @param  array<string, mixed>  $entry
 */
function uamswp_fad_gbp_write_photos_sent(array $item, array $entry): void
{
    $post_id = (int) ($item['post_id'] ?? 0);
    $name = (string) ($entry['listing_name'] ?? '');
    $now_sent = array_values(array_map(strval(...), (array) ($entry['photos_sent_now'] ?? [])));

    if ($post_id === 0 || $name === '' || $now_sent === []) {
        return;
    }

    $fresh = ($entry['action'] ?? '') === 'create';

    if ($item['kind'] === 'location') {
        if ((string) get_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_META, true) !== $name) {
            return;
        }

        $stored = get_post_meta($post_id, UAMSWP_FAD_GBP_PHOTOS_META, true);
        $before = $fresh || ! is_array($stored) ? [] : $stored;
        update_post_meta($post_id, UAMSWP_FAD_GBP_PHOTOS_META, array_values(array_unique(array_merge($before, $now_sent))));

        return;
    }

    $map = get_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_MAP_META, true);
    $location_id = (int) ($item['location_id'] ?? 0);

    if (! is_array($map) || ($map[$location_id]['id'] ?? '') !== $name) {
        return;
    }

    $before = $fresh ? [] : (array) ($map[$location_id]['photos'] ?? []);
    $map[$location_id]['photos'] = array_values(array_unique(array_merge($before, $now_sent)));
    update_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_MAP_META, $map);
}

/**
 * Whether another provider or location post stores a Google listing name.
 *
 * A copied post (a duplicate-post tool copies meta) would otherwise point at the original's listing and
 * rewrite it. One listing belongs to one post.
 */
function uamswp_fad_gbp_name_held_elsewhere(int $post_id, string $name): bool
{
    $held = get_posts([
        'post_type' => ['provider', 'location'],
        'post_status' => 'any',
        'exclude' => [$post_id],
        'fields' => 'ids',
        'posts_per_page' => 1,
        'meta_query' => [
            'relation' => 'OR',
            ['key' => UAMSWP_FAD_GBP_LISTING_META, 'value' => $name],
            ['key' => UAMSWP_FAD_GBP_LISTING_MAP_META, 'value' => '"'.$name.'"', 'compare' => 'LIKE'],
        ],
    ]);

    return $held !== [];
}

/**
 * Give each item the Google location name stored for it, if any.
 *
 * A name another post also stores is not trusted: the item falls back to its store code.
 *
 * Done after the fingerprint is taken, so storing an ID does not make the next run look changed.
 *
 * @param  array<int, array<string, mixed>>  $items
 * @return array<int, array<string, mixed>>
 */
function uamswp_fad_gbp_attach_stored_ids(array $items): array
{
    foreach ($items as $key => $item) {
        $post_id = (int) ($item['post_id'] ?? 0);

        if ($post_id === 0) {
            continue;
        }

        if ($item['kind'] === 'location') {
            $name = (string) get_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_META, true);
        } else {
            $map = get_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_MAP_META, true);
            $name = is_array($map) ? (string) ($map[(int) ($item['location_id'] ?? 0)]['id'] ?? '') : '';
        }

        $items[$key]['stored_name'] = $name !== '' && ! uamswp_fad_gbp_name_held_elsewhere($post_id, $name) ? $name : '';
        $items[$key]['photos_sent'] = uamswp_fad_gbp_photos_sent_for($items[$key]);
    }

    return $items;
}

/**
 * Store the Google name of every listing the run matched or created, on the post it belongs to.
 *
 * @param  array<int, array<string, mixed>>  $items
 * @param  array<int, array<string, mixed>>  $entries  The run's entries, in item order.
 */
function uamswp_fad_gbp_store_listing_ids(array $items, array $entries): void
{
    foreach (array_values($items) as $position => $item) {
        $name = (string) ($entries[$position]['listing_name'] ?? '');
        $post_id = (int) ($item['post_id'] ?? 0);

        if ($name === '' || $post_id === 0 || ($entries[$position]['mode'] ?? '') !== 'live') {
            continue;
        }

        uamswp_fad_gbp_write_listing_id($item, $name);
        uamswp_fad_gbp_write_photos_sent($item, $entries[$position]);
    }
}

/**
 * Write one listing's Google name on the post an item belongs to, only when it differs from what is stored.
 *
 * @param  array<string, mixed>  $item  With `post_id`, `kind` and, for a provider, `location_id`.
 */
function uamswp_fad_gbp_write_listing_id(array $item, string $name): void
{
    $post_id = (int) ($item['post_id'] ?? 0);

    // No post or location to key it by, or the listing is another post's: store nothing.
    if ($post_id === 0 || ($item['kind'] === 'provider' && (int) ($item['location_id'] ?? 0) === 0) || uamswp_fad_gbp_name_held_elsewhere($post_id, $name)) {
        return;
    }

    if ($item['kind'] === 'location') {
        if ((string) get_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_META, true) !== $name) {
            update_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_META, $name);

            // The photos sent belong to the listing they went to.
            delete_post_meta($post_id, UAMSWP_FAD_GBP_PHOTOS_META);
        }

        return;
    }

    $map = get_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_MAP_META, true);
    $map = is_array($map) ? $map : [];

    $location_id = (int) ($item['location_id'] ?? 0);

    if (($map[$location_id]['id'] ?? '') !== $name || ($map[$location_id]['orphaned_since'] ?? null) !== null) {
        update_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_MAP_META, UAMSWP_FAD_GBP_Logic::remember_listing($map, $location_id, $name));
    }
}

/**
 * Date the map entries of a provider whose location it no longer has.
 *
 * @param  array<int, array<string, mixed>>  $items  All of the provider's items, unfiltered.
 */
function uamswp_fad_gbp_mark_orphans(int $post_id, array $items, ?int $now = null): void
{
    if (get_post_type($post_id) !== 'provider') {
        return;
    }

    $map = get_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_MAP_META, true);

    if (! is_array($map) || $map === []) {
        return;
    }

    $marked = UAMSWP_FAD_GBP_Logic::mark_orphans($map, array_map(fn (array $item): int => (int) ($item['location_id'] ?? 0), $items), gmdate('Y-m-d', $now ?? time()));

    if ($marked !== $map) {
        update_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_MAP_META, $marked);
    }
}

/**
 * What the sync needs to know about a location, by slug: its silo, from its brand fields, and
 * whether providers practicing there are excluded from Google Business Profiles.
 *
 * The silo is null when the brand has no silo, and the location is treated as not excluding
 * its providers when it cannot be found.
 *
 * @return array{silo: ?string, providers_excluded: bool, id: int} The location's post ID, 0 when it cannot be found.
 */
function uamswp_fad_gbp_location_info(string $slug): array
{
    $ids = get_posts(['name' => $slug, 'post_type' => 'location', 'post_status' => 'publish', 'posts_per_page' => 1, 'fields' => 'ids']);

    if ($ids === []) {
        return ['silo' => null, 'providers_excluded' => false, 'id' => 0];
    }

    $location_id = (int) $ids[0];

    return [
        'id' => $location_id,
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
            // With no location post there is no map entry to record the photos against, so they would be re-sent every run.
            'photos' => $location['id'] === 0 ? [] : UAMSWP_FAD_GBP_Logic::photos_from_row($row, false),
            'post_id' => $provider_id,
            'location_id' => $location['id'],
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
            $items[] = ['post_id' => $location_id, 'location_id' => $location_id] + $item;
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
 * A review or error entry is also kept in its own list, one per store code, that routine entries
 * cannot push out; a later settled entry for the store code removes it.
 *
 * @param  array<string, mixed>  $entry
 */
function uamswp_fad_gbp_log(array $entry): void
{
    $entry = ['time' => time()] + $entry;

    $attention = get_option('uamswp_fad_gbp_attention', []);
    $attention = is_array($attention) ? $attention : [];

    $next = UAMSWP_FAD_GBP_Logic::next_attention($attention, $entry, UAMSWP_FAD_GBP_ATTENTION_CAP);

    if ($next !== $attention) {
        update_option('uamswp_fad_gbp_attention', $next, false);
    }

    if (($entry['action'] ?? '') === 'noop') {
        return;
    }

    $log = get_option('uamswp_fad_gbp_log', []);
    $log = is_array($log) ? $log : [];

    array_unshift($log, $entry);

    update_option('uamswp_fad_gbp_log', array_slice($log, 0, UAMSWP_FAD_GBP_LOG_CAP), false);
}

/**
 * How many listings may be created per Pacific day.
 *
 * Set below the cap Google was seen to enforce, so a normal backlog drains over several days without
 * reaching a refusal. The refusal handling is the backstop.
 */
function uamswp_fad_gbp_create_budget(): int
{
    $budget = apply_filters('uamswp_fad_gbp_daily_create_budget', UAMSWP_FAD_GBP_DEFAULT_CREATE_BUDGET);

    // A filter returning anything but a number must not silently become 0 (every create held back).
    return is_numeric($budget) ? max(0, (int) $budget) : UAMSWP_FAD_GBP_DEFAULT_CREATE_BUDGET;
}

/**
 * What the sync needs to know about Google's daily create quota: a pause in force, the creates
 * still allowed today, and the clock.
 *
 * @return array{creates_paused_until: int, creates_left: int, now: int}
 */
function uamswp_fad_gbp_create_gate(?int $now = null): array
{
    $now ??= time();
    $count = get_option(UAMSWP_FAD_GBP_COUNT_OPTION, []);
    $used = is_array($count) && ($count['day'] ?? '') === UAMSWP_FAD_GBP_Logic::pacific_day($now) ? (int) ($count['count'] ?? 0) : 0;

    return [
        'creates_paused_until' => (int) get_option(UAMSWP_FAD_GBP_PAUSE_OPTION, 0),
        'creates_left' => max(0, uamswp_fad_gbp_create_budget() - $used),
        'now' => $now,
    ];
}

/**
 * Remember what a run did to the daily quota, and keep the cached index in step with its creates.
 *
 * Adds the run's creates to today's count, stores a new pause, and adds each created listing to the
 * cached index so the next job does not re-read every group. An update still drops the cache, since
 * it changes a listing the index describes.
 *
 * @param  array<int, array<string, mixed>>  $entries  The run's log entries.
 */
function uamswp_fad_gbp_after_run(UAMSWP_FAD_GBP_Sync $sync, array $entries, ?int $now = null): void
{
    $now ??= time();
    $made = $sync->creates_made();

    if ($made > 0) {
        $day = UAMSWP_FAD_GBP_Logic::pacific_day($now);
        $count = get_option(UAMSWP_FAD_GBP_COUNT_OPTION, []);
        $before = is_array($count) && ($count['day'] ?? '') === $day ? (int) ($count['count'] ?? 0) : 0;
        update_option(UAMSWP_FAD_GBP_COUNT_OPTION, ['day' => $day, 'count' => $before + $made], false);

        $history = get_option(UAMSWP_FAD_GBP_HISTORY_OPTION, []);
        update_option(UAMSWP_FAD_GBP_HISTORY_OPTION, UAMSWP_FAD_GBP_Logic::add_to_history(is_array($history) ? $history : [], $day, $made), false);
    }

    if ($sync->creates_paused_until() > (int) get_option(UAMSWP_FAD_GBP_PAUSE_OPTION, 0)) {
        update_option(UAMSWP_FAD_GBP_PAUSE_OPTION, $sync->creates_paused_until(), false);
    }

    if (in_array('Updated', array_column($entries, 'reason'), true)) {
        delete_transient('uamswp_fad_gbp_index');

        return;
    }

    $cached = get_transient('uamswp_fad_gbp_index');
    $read_at = get_transient('uamswp_fad_gbp_index_at');

    if ($sync->created() === [] || ! is_array($cached)) {
        return;
    }

    // Keep the index's original expiry: a long backlog of creates must not keep a stale read alive.
    $remaining = is_int($read_at) ? 300 - ($now - $read_at) : 0;

    if ($remaining < 1) {
        delete_transient('uamswp_fad_gbp_index');

        return;
    }

    set_transient('uamswp_fad_gbp_index', array_merge($cached, $sync->created()), $remaining);
}

/**
 * Queue a post once at the reset when a create was held back by the daily quota.
 *
 * Not an attempt: the retry counter is not touched, so the five-attempt limit stays for per-minute
 * limits. Each post is staggered by its ID so the posts of a backlog do not all start at the reset.
 *
 * @param  array<int, array<string, mixed>>  $entries  The run's log entries.
 */
function uamswp_fad_gbp_schedule_deferred(int $post_id, array $entries): void
{
    $deferred = array_column($entries, 'defer_until');

    if ($deferred !== [] && wp_next_scheduled(UAMSWP_FAD_GBP_SYNC_HOOK, [$post_id]) === false) {
        wp_schedule_single_event(max($deferred) + 60 + ($post_id % 600), UAMSWP_FAD_GBP_SYNC_HOOK, [$post_id]);
    }
}

/**
 * What to remember and queue after a job has run a post's items.
 *
 * A retryable failure queues a backed-off retry that counts an attempt. A create held back by the
 * daily quota queues the post at the reset and counts nothing; it is queued in both cases, so a post
 * whose retries give up still runs again after the reset. The fingerprint is written only when
 * every entry is settled, which a held create never is.
 *
 * @param  array<int, array<string, mixed>>  $entries  The run's log entries.
 */
function uamswp_fad_gbp_finish_run(int $post_id, array $entries, string $hash): void
{
    if (in_array(true, array_column($entries, 'retryable'), true)) {
        uamswp_fad_gbp_schedule_retry($post_id);
        uamswp_fad_gbp_schedule_deferred($post_id, $entries);

        return;
    }

    delete_transient('uamswp_fad_gbp_attempts_'.$post_id);

    uamswp_fad_gbp_schedule_deferred($post_id, $entries);

    if (UAMSWP_FAD_GBP_Logic::is_settled($entries)) {
        update_post_meta($post_id, UAMSWP_FAD_GBP_HASH_META, $hash);
    }
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
        $until = time() + uamswp_fad_gbp_retry_delay($attempt);
        wp_schedule_single_event($until, UAMSWP_FAD_GBP_SYNC_HOOK, [$post_id]);

        // The dashboard shows the latest backoff in force.
        if ($until > (int) get_option(UAMSWP_FAD_GBP_BACKOFF_OPTION, 0)) {
            update_option(UAMSWP_FAD_GBP_BACKOFF_OPTION, $until, false);
        }
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

        $all_items = $items;
        $items = uamswp_fad_gbp_attach_stored_ids($items);
        $gate = uamswp_fad_gbp_create_gate();
        $sync = new UAMSWP_FAD_GBP_Sync(
            $client,
            ['silos' => uamswp_fad_gbp_silos(), 'modes' => $modes, 'is_production' => $production] + $gate,
            UAMSWP_FAD_GBP_Sync::build_index(uamswp_fad_gbp_index($client)),
            'uamswp_fad_gbp_log'
        );

        $entries = [];

        try {
            $entries = $sync->run($items);
            uamswp_fad_gbp_store_listing_ids($items, $entries);

            if (in_array('live', array_column($entries, 'mode'), true)) {
                uamswp_fad_gbp_mark_orphans($post_id, $all_items);
            }
        } finally {
            // The creates already made count against the day's quota even if the run ended early,
            // and a write changes what the next job should see.
            uamswp_fad_gbp_after_run($sync, $entries, $gate['now']);
        }

        uamswp_fad_gbp_finish_run($post_id, $entries, $hash);
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
