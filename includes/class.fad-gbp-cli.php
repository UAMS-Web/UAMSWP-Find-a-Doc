<?php

/**
 * Business Profile sync: the on-demand WP-CLI command.
 *
 * Runs the same decisions as the queued job, in the foreground, and prints each one. It takes
 * the queue's run lock, and it neither reads nor writes the fingerprint the queue skips on, so
 * a manual run never changes what the queue does next.
 */

declare(strict_types=1);

/**
 * Sync the listings of the given posts now, printing one line per listing.
 *
 * @param  array<int, int>  $post_ids  Provider and location posts. A location also covers its child locations and the providers at them.
 * @param  string|null  $silo  Limit the run to one silo's listings.
 * @param  bool  $dry_run  Run every silo that is not off as a dry run.
 * @param  callable(string): void  $out  Receives each line of output.
 * @param  UAMSWP_FAD_GBP_Client|null  $client  For tests; built from the credentials when null.
 * @param  (callable(int, array<string, string>): array<int, GbpSyncItem>)|null  $items_for  For tests; the items for one post.
 * @return int The exit status: 0, or 1 when anything ended in error or review, or the run could not finish.
 */
function uamswp_fad_gbp_manual_sync(array $post_ids, ?string $silo, bool $dry_run, callable $out, ?UAMSWP_FAD_GBP_Client $client = null, ?callable $items_for = null): int
{
    if ($silo !== null && ! isset(uamswp_fad_gbp_silos()[$silo])) {
        $out('Unknown silo: '.$silo.'. Known silos: '.implode(', ', array_keys(uamswp_fad_gbp_silos())));

        return 1;
    }

    $failed = false;
    $covered = [];

    foreach ($post_ids as $post_id) {
        $type = get_post_type($post_id);

        if (get_post_status($post_id) !== 'publish' || ! in_array($type, ['provider', 'location'], true)) {
            $out('Post '.$post_id.' is not a published provider or location');
            $failed = true;

            continue;
        }

        $covered = array_merge($covered, uamswp_fad_gbp_covered_post_ids($post_id));
    }

    $covered = array_values(array_unique($covered));

    if ($covered === []) {
        $out('Nothing to sync');

        return 1;
    }

    if (! $client instanceof UAMSWP_FAD_GBP_Client) {
        $credentials = uamswp_fad_gbp_credentials();

        if ($credentials === null) {
            $out('The Google Business Profile credentials are not set');

            return 1;
        }

        $client = new UAMSWP_FAD_GBP_Client($credentials, 'uamswp_fad_gbp_transport');
    }

    if (get_transient('uamswp_fad_gbp_lock')) {
        $out('A sync is already running. Try again when it finishes.');

        return 1;
    }

    set_transient('uamswp_fad_gbp_lock', 1, 300);

    try {
        $modes = uamswp_fad_gbp_modes();

        if ($dry_run) {
            $modes = array_map(fn (string $mode): string => $mode === 'off' ? 'off' : 'dry-run', $modes);
        }

        $production = uamswp_fad_gbp_is_production();
        $categories = uamswp_fad_gbp_categories($client);
        $items_for ??= 'uamswp_fad_gbp_items_for_post';
        $items = [];

        foreach ($covered as $post_id) {
            $items = array_merge($items, $items_for($post_id, $categories));
        }

        $items = uamswp_fad_gbp_attach_stored_ids($items);

        if ($silo !== null) {
            $items = array_values(array_filter($items, fn (array $item): bool => $item['silo'] === $silo));
        }

        $gate = uamswp_fad_gbp_create_gate();
        $sync = new UAMSWP_FAD_GBP_Sync(
            $client,
            ['silos' => uamswp_fad_gbp_silos(), 'modes' => $modes, 'is_production' => $production] + $gate,
            UAMSWP_FAD_GBP_Sync::build_index(uamswp_fad_gbp_index($client)),
            'uamswp_fad_gbp_log'
        );

        $counts = [];
        $entries = [];
        $held = false;

        try {
            // One item at a time, so a rate limit ends the run at the item that met it.
            foreach ($items as $item) {
                $entry = $sync->run([$item])[0];
                $entries[] = $entry;
                uamswp_fad_gbp_store_listing_ids([$item], [$entry]);
                $action = (string) $entry['action'];
                $counts[$action] = ($counts[$action] ?? 0) + 1;
                $out(sprintf('%s  %s  %s  %s  %s', $entry['store_code'], (string) $entry['silo'], $entry['mode'], $action, $entry['reason']));

                if (in_array($action, ['error', 'review'], true)) {
                    $failed = true;
                }

                // A held create does not end the run: updates are not paused, and later creates are held without a call.
                if (! empty($entry['defer_until'])) {
                    $held = true;
                    $failed = true;
                    $resume = (int) $entry['defer_until'];
                }

                if (! empty($entry['retryable'])) {
                    $out('Stopped: Google is rate limiting this account. Run again later.');
                    $failed = true;

                    break;
                }
            }
        } finally {
            // The creates already made count against the day's quota even if the run ended early.
            uamswp_fad_gbp_after_run($sync, $entries, $gate['now']);
        }

        if ($held) {
            $out('Creates were held back and resume '.UAMSWP_FAD_GBP_Logic::reset_label($resume ?? 0).'. Run again after that.');
        }

        ksort($counts);
        $out('Done: '.($counts === [] ? 'no listings' : implode(', ', array_map(fn (string $action, int $count): string => $count.' '.$action, array_keys($counts), $counts))));
    } catch (RuntimeException $runtimeException) {
        $out('Stopped: '.$runtimeException->getMessage());

        return 1;
    } finally {
        delete_transient('uamswp_fad_gbp_lock');
    }

    return $failed ? 1 : 0;
}

/**
 * The Google CIDs a provider or location post carries in its public CID fields. Read only.
 *
 * @return array<int, string>
 */
function uamswp_fad_gbp_post_cids(int $post_id): array
{
    $field = get_post_type($post_id) === 'location' ? 'schema_google_cid' : 'physician_schema_google_cid';

    return UAMSWP_FAD_GBP_Logic::cids_from_value(function_exists('get_field') ? get_field($field, $post_id) : null);
}

/**
 * Fill the stored Google location IDs of existing listings, by store code and then by CID.
 *
 * Without $apply nothing is written. A listing neither route matches is listed for a person; none is guessed.
 * The public CID fields are never written.
 *
 * @param  array<int, int>  $post_ids  Provider and location posts.
 * @param  callable(string): void  $out
 * @param  (callable(int, array<string, string>): array<int, GbpSyncItem>)|null  $items_for  For tests.
 * @return int 0, or 1 when the listings could not be read.
 */
function uamswp_fad_gbp_backfill_ids(array $post_ids, bool $apply, callable $out, ?UAMSWP_FAD_GBP_Client $client = null, ?callable $items_for = null): int
{
    if (! $client instanceof UAMSWP_FAD_GBP_Client) {
        $credentials = uamswp_fad_gbp_credentials();

        if ($credentials === null) {
            $out('The Google Business Profile credentials are not set');

            return 1;
        }

        $client = new UAMSWP_FAD_GBP_Client($credentials, 'uamswp_fad_gbp_transport');
    }

    try {
        // A read from before this version has no metadata, so read fresh.
        delete_transient('uamswp_fad_gbp_index');
        $listings = uamswp_fad_gbp_index($client);
        $categories = uamswp_fad_gbp_categories($client);
    } catch (RuntimeException $runtimeException) {
        $out('Stopped: '.$runtimeException->getMessage());

        return 1;
    }

    $items_for ??= 'uamswp_fad_gbp_items_for_post';
    $items = [];
    $cids = [];

    foreach ($post_ids as $post_id) {
        $items = array_merge($items, $items_for($post_id, $categories));
        $cids[$post_id] = uamswp_fad_gbp_post_cids($post_id);
    }

    $plan = UAMSWP_FAD_GBP_Logic::backfill_plan(uamswp_fad_gbp_attach_stored_ids($items), $listings, $cids);

    foreach (['by_store_code', 'by_cid'] as $route) {
        foreach ($plan[$route] as $match) {
            $out(sprintf('%s  %s  %s', $match['item']['store_code'], $match['name'], $route));

            if ($apply) {
                uamswp_fad_gbp_write_listing_id($match['item'], $match['name']);
            }
        }
    }

    foreach ($plan['unmatched'] as $item) {
        $out('UNMATCHED  '.$item['store_code'].'  (post '.($item['post_id'] ?? 0).')');
    }

    $out(sprintf(
        '%s: %d already stored, %d matched by store code, %d matched by CID, %d unmatched',
        $apply ? 'Done' : 'Dry run, nothing written',
        count($plan['already']),
        count($plan['by_store_code']),
        count($plan['by_cid']),
        count($plan['unmatched'])
    ));

    return 0;
}

/**
 * Print the providers whose "listings on Google" field says Yes but whose CIDs match no listing, and the listings
 * whose CID is on no provider or location. Read only.
 *
 * @param  array<int, int>  $post_ids
 * @param  callable(string): void  $out
 */
function uamswp_fad_gbp_cid_report(array $post_ids, callable $out, ?UAMSWP_FAD_GBP_Client $client = null): int
{
    if (! $client instanceof UAMSWP_FAD_GBP_Client) {
        $credentials = uamswp_fad_gbp_credentials();

        if ($credentials === null) {
            $out('The Google Business Profile credentials are not set');

            return 1;
        }

        $client = new UAMSWP_FAD_GBP_Client($credentials, 'uamswp_fad_gbp_transport');
    }

    try {
        delete_transient('uamswp_fad_gbp_index');
        $listings = uamswp_fad_gbp_index($client);
    } catch (RuntimeException $runtimeException) {
        $out('Stopped: '.$runtimeException->getMessage());

        return 1;
    }

    $posts = [];
    $yes = [];

    foreach ($post_ids as $post_id) {
        $row = ['id' => $post_id, 'label' => get_the_title($post_id).' (post '.$post_id.')', 'cids' => uamswp_fad_gbp_post_cids($post_id)];
        $posts[] = $row;

        if (get_post_type($post_id) === 'provider' && function_exists('get_field') && UAMSWP_FAD_GBP_Logic::is_yes(get_field('physician_schema_gmb_query', $post_id))) {
            $yes[] = $row;
        }
    }

    $report = UAMSWP_FAD_GBP_Logic::cid_report($yes, $posts, $listings);

    foreach ($report['providers_without_listing'] as $label) {
        $out('NO LISTING  '.$label);
    }

    foreach ($report['listings_without_cid'] as $label) {
        $out('NO CID  '.$label);
    }

    $out(sprintf('%d providers say Yes but match no listing; %d listings have no CID on any provider or location', count($report['providers_without_listing']), count($report['listings_without_cid'])));

    return 0;
}

/**
 * The `wp uamswp gbp backfill-ids` handler.
 *
 * @param  array<int, string>  $args
 * @param  array<string, string|bool>  $assoc
 */
function uamswp_fad_gbp_cli_backfill(array $args, array $assoc): void
{
    WP_CLI::halt(uamswp_fad_gbp_backfill_ids(uamswp_fad_gbp_all_post_ids(), ! empty($assoc['apply']), function (string $line): void {
        WP_CLI::line($line);
    }));
}

/**
 * The `wp uamswp gbp cid-report` handler.
 *
 * @param  array<int, string>  $args
 * @param  array<string, string|bool>  $assoc
 */
function uamswp_fad_gbp_cli_cid_report(array $args, array $assoc): void
{
    WP_CLI::halt(uamswp_fad_gbp_cid_report(uamswp_fad_gbp_all_post_ids(), function (string $line): void {
        WP_CLI::line($line);
    }));
}

/**
 * The `wp uamswp gbp sync` handler: reads the options, runs the sync, exits with its status.
 *
 * @param  array<int, string>  $args
 * @param  array<string, string|bool>  $assoc
 */
function uamswp_fad_gbp_cli_sync(array $args, array $assoc): void
{
    // The orphan report and close: the same scan the daily job runs, printed.
    if (! empty($assoc['orphans'])) {
        WP_CLI::halt(uamswp_fad_gbp_locked_orphan_scan(function (string $line): void {
            WP_CLI::line($line);
        }, ! empty($assoc['dry-run'])));
    }

    $post = isset($assoc['post']) ? (string) $assoc['post'] : '';
    $all = ! empty($assoc['all']);

    if (($post === '') === ! $all || ($post !== '' && ! ctype_digit($post))) {
        WP_CLI::error('Pass exactly one of --post=<id> or --all.');
    }

    $ids = $all ? uamswp_fad_gbp_all_post_ids() : [(int) $post];
    $silo = isset($assoc['silo']) ? (string) $assoc['silo'] : null;

    $status = uamswp_fad_gbp_manual_sync($ids, $silo, ! empty($assoc['dry-run']), function (string $line): void {
        WP_CLI::line($line);
    });

    WP_CLI::halt($status);
}

/**
 * Register the command, only under WP-CLI.
 */
function uamswp_fad_gbp_register_cli(): void
{
    if (defined('WP_CLI') && WP_CLI && class_exists('WP_CLI')) {
        WP_CLI::add_command('uamswp gbp sync', 'uamswp_fad_gbp_cli_sync');
        WP_CLI::add_command('uamswp gbp backfill-ids', 'uamswp_fad_gbp_cli_backfill');
        WP_CLI::add_command('uamswp gbp cid-report', 'uamswp_fad_gbp_cli_cid_report');
    }
}
