<?php

/**
 * Business Profile sync: orphaned listings.
 *
 * A listing is orphaned when its provider or location is no longer published, or when its provider
 * no longer practices at that location. The report lists each one with its cause and the days left;
 * after fourteen continuous days the listing is marked permanently closed and its public details are
 * cleared. It stays on the account. Nothing here deletes a listing.
 */

declare(strict_types=1);

/**
 * Meta key on a provider or location post: the date it was first seen not published.
 */
const UAMSWP_FAD_GBP_UNPUBLISHED_META = 'uamswp_fad_gbp_unpublished_since';

/**
 * The daily cron hook that reports orphans and closes the ones that are due.
 */
const UAMSWP_FAD_GBP_ORPHAN_HOOK = 'uamswp_fad_gbp_orphan_scan';

/**
 * Start or clear the unpublished date when a provider or location changes status.
 *
 * Leaving `publish` starts the period (an existing date is kept); entering it clears the date, so a
 * republish restarts the fourteen days.
 *
 * @param  mixed  $new_status
 * @param  mixed  $old_status
 * @param  mixed  $post
 */
function uamswp_fad_gbp_track_unpublish($new_status, $old_status, $post, ?int $now = null): void
{
    if (! is_object($post) || ! in_array($post->post_type ?? '', ['provider', 'location'], true)) {
        return;
    }

    if ($new_status === 'publish') {
        delete_post_meta((int) $post->ID, UAMSWP_FAD_GBP_UNPUBLISHED_META);

        return;
    }

    if ($old_status === 'publish' && (string) get_post_meta((int) $post->ID, UAMSWP_FAD_GBP_UNPUBLISHED_META, true) === '') {
        update_post_meta((int) $post->ID, UAMSWP_FAD_GBP_UNPUBLISHED_META, gmdate('Y-m-d', $now ?? time()));
    }
}

/**
 * The listings whose provider or location is not published, or whose provider left the location.
 *
 * A post already unpublished when this first runs gets today's date, so its period starts at deploy.
 * Posts that never had a listing stored contribute nothing.
 *
 * @return array<int, array{name: string, cause: string, since: string, label: string}>
 */
function uamswp_fad_gbp_orphan_candidates(?int $now = null, ?callable $locations_for = null): array
{
    $today = gmdate('Y-m-d', $now ?? time());
    $candidates = [];
    $locations_for ??= static fn (int $provider_id): array => array_map(fn (array $item): int => $item['location_id'], uamswp_fad_gbp_items_for_provider($provider_id, []));

    // A listing a published post still uses is never a candidate, whatever an unpublished copy of that post says.
    $live = [];

    foreach (get_posts(['post_type' => 'location', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids']) as $location_id) {
        $live = array_merge($live, uamswp_fad_gbp_stored_names(intval($location_id)));
    }

    $published_providers = array_map(intval(...), get_posts(['post_type' => 'provider', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids']));

    foreach ($published_providers as $provider_id) {
        $map = get_post_meta($provider_id, UAMSWP_FAD_GBP_LISTING_MAP_META, true);

        foreach (is_array($map) ? $map : [] as $entry) {
            if (is_array($entry) && ($entry['orphaned_since'] ?? null) === null && ($entry['id'] ?? '') !== '') {
                $live[] = (string) $entry['id'];
            }
        }
    }

    $live = array_flip($live);

    $unpublished = get_posts(['post_type' => ['provider', 'location'], 'post_status' => ['draft', 'pending', 'private', 'future', 'trash'], 'posts_per_page' => -1, 'fields' => 'ids']);

    foreach ($unpublished as $post_id) {
        $post_id = intval($post_id);
        $names = uamswp_fad_gbp_stored_names($post_id);

        if ($names === []) {
            continue;
        }

        $since = (string) get_post_meta($post_id, UAMSWP_FAD_GBP_UNPUBLISHED_META, true);

        if ($since === '') {
            $since = $today;
            update_post_meta($post_id, UAMSWP_FAD_GBP_UNPUBLISHED_META, $since);
        }

        foreach ($names as $name) {
            if (isset($live[$name])) {
                continue;
            }

            // Two unpublished posts naming one listing: the earlier date holds, so the clock is the same on every run.
            if (! isset($candidates[$name]) || $since < $candidates[$name]['since']) {
                $candidates[$name] = ['name' => $name, 'cause' => 'unpublished', 'since' => $since, 'label' => get_the_title($post_id).' (post '.$post_id.')'];
            }
        }
    }

    foreach ($published_providers as $post_id) {
        $map = get_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_MAP_META, true);
        $current = null;

        foreach (is_array($map) ? $map : [] as $location_id => $entry) {
            $since = (string) ($entry['orphaned_since'] ?? '');

            if ($since === '' || ($entry['id'] ?? '') === '' || isset($live[$entry['id']]) || isset($candidates[$entry['id']])) {
                continue;
            }

            // The stored date is only as fresh as the last live run: check the provider's locations now.
            $current ??= $locations_for($post_id);

            if (in_array((int) $location_id, $current, true)) {
                continue;
            }

            $candidates[$entry['id']] = ['name' => $entry['id'], 'cause' => 'association removed', 'since' => $since, 'label' => get_the_title($post_id).' (post '.$post_id.') at location '.$location_id];
        }
    }

    return array_values($candidates);
}

/**
 * Every Google listing name stored on a provider or location post.
 *
 * @return array<int, string>
 */
function uamswp_fad_gbp_stored_names(int $post_id): array
{
    if (get_post_type($post_id) === 'location') {
        $name = (string) get_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_META, true);

        return $name === '' ? [] : [$name];
    }

    $map = get_post_meta($post_id, UAMSWP_FAD_GBP_LISTING_MAP_META, true);

    $names = [];

    foreach (is_array($map) ? $map : [] as $entry) {
        if (is_array($entry) && ($entry['id'] ?? '') !== '') {
            $names[] = (string) $entry['id'];
        }
    }

    return $names;
}

/**
 * Report the orphaned listings and, where a silo is live, close the ones that are due.
 *
 * A silo in dry-run logs the planned closure and writes nothing; an off silo is reported only. A listing already
 * closed is reported once more as closed and not written again. A listing in no known silo is left alone. The
 * period is judged against the listings Google returns now, so a listing it no longer returns is skipped.
 *
 * @param  callable(string): void  $out  Receives one line per listing.
 * @param  UAMSWP_FAD_GBP_Client|null  $client  For tests; built from the credentials when null.
 * @param  array<int, array{name: string, cause: string, since: string, label: string}>|null  $candidates  For tests.
 * @param  bool  $dry_run  Treat every silo that is not off as a dry run.
 * @return int 0, or 1 when anything could not be read or written.
 */
function uamswp_fad_gbp_orphan_scan(callable $out, ?UAMSWP_FAD_GBP_Client $client = null, ?int $now = null, ?array $candidates = null, bool $dry_run = false): int
{
    if (! $client instanceof UAMSWP_FAD_GBP_Client) {
        $credentials = uamswp_fad_gbp_credentials();

        if ($credentials === null) {
            $out('The Google Business Profile credentials are not set');

            return 1;
        }

        $client = new UAMSWP_FAD_GBP_Client($credentials, 'uamswp_fad_gbp_transport');
    }

    $now ??= time();
    $failed = 0;

    try {
        delete_transient('uamswp_fad_gbp_index');
        $listings = [];

        foreach (uamswp_fad_gbp_index($client) as $entry) {
            $listings[(string) ($entry['listing']['name'] ?? '')] = $entry;
        }
    } catch (RuntimeException $runtimeException) {
        $out('Stopped: '.$runtimeException->getMessage());

        return 1;
    }

    $modes = uamswp_fad_gbp_modes();

    if ($dry_run) {
        $modes = array_map(fn (string $mode): string => $mode === 'off' ? 'off' : 'dry-run', $modes);
    }

    $production = uamswp_fad_gbp_is_production();
    $silos = uamswp_fad_gbp_silos();
    $report = UAMSWP_FAD_GBP_Logic::orphan_report($candidates ?? uamswp_fad_gbp_orphan_candidates($now), gmdate('Y-m-d', $now));

    foreach ($report as $row) {
        $found = $listings[$row['name']] ?? null;

        if ($found === null) {
            $out(sprintf('%s  not on Google any more (%s)', $row['name'], $row['label']));

            continue;
        }

        $silo = uamswp_fad_gbp_silo_for_account($found['account'], $silos);
        $mode = $silo === null ? 'off' : UAMSWP_FAD_GBP_Logic::effective_mode($modes[$silo] ?? 'off', $production);
        $entry = [
            'store_code' => (string) ($found['listing']['storeCode'] ?? $row['name']),
            'silo' => $silo,
            'kind' => 'listing',
            'mode' => $mode,
            'action' => 'review',
            'fields' => [],
            'attributes' => [],
            'unresolved_categories' => [],
            'listing_name' => $row['name'],
        ];

        if (UAMSWP_FAD_GBP_Logic::is_closed($found['listing'])) {
            $out(sprintf('%s  already closed  %s', $row['name'], $row['label']));

            continue;
        }

        $line = sprintf('%s  %s  orphaned since %s (%s)', $row['name'], $row['cause'], $row['since'], $row['label']);

        if (! $row['due']) {
            $entry['reason'] = sprintf('Orphaned (%s) since %s, %d days left before it is closed: %s', $row['cause'], $row['since'], $row['days_left'], $row['label']);
            $out($line.sprintf('  %d days left', $row['days_left']));

            // Logged when first seen and at 7, 3 and 1 days left, so a daily scan does not fill the log.
            if (in_array($row['days_left'], [UAMSWP_FAD_GBP_Logic::ORPHAN_GRACE_DAYS, 7, 3, 1], true)) {
                uamswp_fad_gbp_log($entry);
            }

            continue;
        }

        $patch = UAMSWP_FAD_GBP_Logic::close_patch();
        $entry['action'] = 'close';
        $entry['fields'] = array_keys($patch['fields']);

        if ($mode === 'off') {
            $entry['action'] = 'review';
            $entry['reason'] = sprintf('Due to close (%s since %s) but silo %s is off: %s', $row['cause'], $row['since'], (string) $silo, $row['label']);
            $out($line.'  due, silo off');
            uamswp_fad_gbp_log($entry);

            continue;
        }

        if ($mode !== 'live') {
            $entry['reason'] = sprintf('Dry run, would close (%s since %s): %s', $row['cause'], $row['since'], $row['label']);
            $out($line.'  due, dry run, nothing written');
            uamswp_fad_gbp_log($entry);

            continue;
        }

        try {
            $client->patch($row['name'], $patch['fields'], $patch['mask']);
            $entry['reason'] = sprintf('Closed (%s since %s): %s', $row['cause'], $row['since'], $row['label']);
            $out($line.'  closed');
        } catch (RuntimeException $runtimeException) {
            $failed = 1;
            $entry['action'] = 'error';
            $entry['reason'] = 'Closing '.$row['name'].' failed: '.$runtimeException->getMessage();
            $out($line.'  FAILED: '.$runtimeException->getMessage());

            // A rate limit ends the scan: the rest are tried on the next run, not into the same refusal.
            if ($runtimeException instanceof UAMSWP_FAD_GBP_Rate_Limited) {
                $entry['retryable'] = true;
                uamswp_fad_gbp_log($entry);
                $out('Stopped: Google is rate limiting this account. Run again later.');

                break;
            }
        }

        uamswp_fad_gbp_log($entry);
    }

    $out(sprintf('%d orphaned listings, %d due', count($report), count(array_filter($report, fn (array $row): bool => $row['due']))));

    return $failed;
}

/**
 * The silo whose account groups include an account, or null.
 *
 * @param  array<string, array<string, mixed>>  $silos
 */
function uamswp_fad_gbp_silo_for_account(string $account, array $silos): ?string
{
    foreach ($silos as $name => $silo) {
        if (in_array($account, (array) ($silo['accounts'] ?? []), true)) {
            return (string) $name;
        }
    }

    return null;
}

/**
 * The daily cron handler: the same scan, with its lines discarded.
 */
function uamswp_fad_gbp_run_orphan_scan(): void
{
    if (uamswp_fad_gbp_credentials() === null || array_diff(array_values(uamswp_fad_gbp_modes()), ['off']) === []) {
        return;
    }

    uamswp_fad_gbp_locked_orphan_scan(static function (string $line): void {});
}

/**
 * The scan, holding the sync's run lock so it never overlaps a sync or another scan.
 *
 * @param  callable(string): void  $out
 * @return int 0, or 1 when the lock is held or the scan failed.
 */
function uamswp_fad_gbp_locked_orphan_scan(callable $out, bool $dry_run = false): int
{
    if (get_transient('uamswp_fad_gbp_lock')) {
        $out('A sync is already running. Try again when it finishes.');

        return 1;
    }

    set_transient('uamswp_fad_gbp_lock', 1, 300);

    try {
        return uamswp_fad_gbp_orphan_scan($out, null, null, null, $dry_run);
    } finally {
        delete_transient('uamswp_fad_gbp_lock');
    }
}

/**
 * Register the status tracking and the daily scan.
 */
function uamswp_fad_gbp_register_orphan_hooks(): void
{
    add_action('transition_post_status', 'uamswp_fad_gbp_track_unpublish', 10, 3);
    add_action(UAMSWP_FAD_GBP_ORPHAN_HOOK, 'uamswp_fad_gbp_run_orphan_scan');

    if (! wp_next_scheduled(UAMSWP_FAD_GBP_ORPHAN_HOOK)) {
        wp_schedule_event(time() + 3600, 'daily', UAMSWP_FAD_GBP_ORPHAN_HOOK);
    }
}
