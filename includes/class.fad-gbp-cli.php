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
 * @param  (callable(int, array<string, string>): array<int, array<string, mixed>>)|null  $items_for  For tests; the items for one post.
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

        if ($silo !== null) {
            $items = array_values(array_filter($items, fn (array $item): bool => $item['silo'] === $silo));
        }

        $sync = new UAMSWP_FAD_GBP_Sync(
            $client,
            ['silos' => uamswp_fad_gbp_silos(), 'modes' => $modes, 'is_production' => $production],
            UAMSWP_FAD_GBP_Sync::build_index(uamswp_fad_gbp_index($client)),
            'uamswp_fad_gbp_log'
        );

        $counts = [];
        $wrote = false;

        // One item at a time, so a rate limit ends the run at the item that met it.
        foreach ($items as $item) {
            $entry = $sync->run([$item])[0];
            $action = (string) $entry['action'];
            $counts[$action] = ($counts[$action] ?? 0) + 1;
            $out(sprintf('%s  %s  %s  %s  %s', $entry['store_code'], (string) $entry['silo'], $entry['mode'], $action, $entry['reason']));

            if (in_array($entry['reason'], ['Created', 'Updated'], true)) {
                $wrote = true;
            }

            if (in_array($action, ['error', 'review'], true)) {
                $failed = true;
            }

            if (! empty($entry['retryable'])) {
                $out('Stopped: Google is rate limiting this account. Run again later.');
                $failed = true;

                break;
            }
        }

        if ($wrote) {
            delete_transient('uamswp_fad_gbp_index');
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
 * The `wp uamswp gbp sync` handler: reads the options, runs the sync, exits with its status.
 *
 * @param  array<int, string>  $args
 * @param  array<string, string|bool>  $assoc
 */
function uamswp_fad_gbp_cli_sync(array $args, array $assoc): void
{
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
    }
}
