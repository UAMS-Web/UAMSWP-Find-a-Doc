<?php

declare(strict_types=1);
/**
 * Dead link cleanup.
 *
 * Before the delete cleanup (class.fad-delete-cleanup.php), deleting a provider or a location left
 * its ID in the relationship fields of the records linked to it, and left its own row in the ACF
 * Custom Database Tables. This screen removes what accumulated: Find-a-Doc Settings > Dead Link
 * Cleanup.
 *
 * Three steps, each its own button:
 *
 * 1. Dry run. Reads every link field and custom table and lists each ID that names a post that no
 *    longer exists, and each custom-table row whose post is gone. Changes nothing, but keeps the
 *    list as the run. Once a removal has started, a dry run only reports what is left and keeps the
 *    run, so the undo is never lost to a check.
 * 2. Remove. Works through the list 25 items at a time, redirecting to itself between batches so a
 *    long run needs no JavaScript and no long request. Each item is read again just before it is
 *    written; an ID that exists now, or a row whose post is back, is left alone. The values being
 *    replaced are saved, and checked in the database, before anything is written; if they cannot be
 *    saved, nothing is written. One step runs at a time, and a step is tied to the dry run it
 *    was started from.
 * 3. Undo. Puts the removed IDs and rows back. A field is restored only when it still holds
 *    exactly what the removal wrote, so an edit made since is never overwritten.
 *
 * The fields come from the delete cleanup's map, so both files cover the same links. Links are
 * read with direct SQL and written through ACF, so the custom-table plugin stores the value in
 * its own format. The backup is the run record in two options (the items, and the full custom-table
 * rows), kept until the next dry run replaces it; it is not a substitute for a database backup.
 */
if (! defined('ABSPATH')) {
    exit;
}

const UAMSWP_DEAD_LINKS_RUN_OPTION = 'uamswp_fad_dead_links_run';

const UAMSWP_DEAD_LINKS_ROWS_OPTION = 'uamswp_fad_dead_links_rows';

const UAMSWP_DEAD_LINKS_BATCH = 25;

/**
 * The link fields and custom tables to read.
 *
 * Taken from the delete cleanup's map, so a field added there is covered here.
 *
 * @return array{meta: array<int, array{0: string, 1: string, 2: string}>, tables: array<int, array{0: string, 1: string}>, own: array<int, string>}
 *                                                                                                                                                   `meta` is `[post type, meta key, shape]`, `tables` is `[table, field]`, `own` the tables
 *                                                                                                                                                   whose rows are checked against the posts.
 */
function uamswp_dead_links_sources(): array
{

    $sources = ['meta' => [], 'tables' => [], 'own' => []];

    foreach (uamswp_delete_cleanup_map() as $links) {

        foreach ($links['meta'] as $post_type => $fields) {

            foreach ($fields as [$field, $shape]) {

                $sources['meta'][$post_type.'|'.$field] = [$post_type, $field, $shape];

            }

        }

        foreach ($links['tables'] as [$table, $field]) {

            $sources['tables'][$table.'|'.$field] = [$table, $field];

        }

        $sources['own'][$links['own_table']] = $links['own_table'];

    }

    return [
        'meta' => array_values($sources['meta']),
        'tables' => array_values($sources['tables']),
        'own' => array_values($sources['own']),
    ];

}

/**
 * Whether a custom table exists.
 */
function uamswp_dead_links_table_exists(string $table): bool
{

    global $wpdb;

    return $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($wpdb->prefix.$table))) === $wpdb->prefix.$table;

}

/**
 * The IDs, out of those given, that name no post.
 *
 * A post in the trash is still a post, so it is not missing.
 *
 * @param  array<int, int>  $ids
 * @return array<int, int>
 */
function uamswp_dead_links_missing(array $ids): array
{

    global $wpdb;

    $ids = array_values(array_unique(array_filter($ids, fn (int $id): bool => $id > 0)));
    $existing = [];

    foreach (array_chunk($ids, 500) as $chunk) {

        $placeholders = implode(',', array_fill(0, count($chunk), '%d'));
        $found = $wpdb->get_col($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE ID IN ({$placeholders})", $chunk));
        $existing = array_merge($existing, array_map(intval(...), (array) $found));

    }

    return array_values(array_diff($ids, $existing));

}

/**
 * The IDs a stored link value holds, whichever way it is stored.
 *
 * @return array<int, int>
 */
function uamswp_dead_links_stored_ids(string $value): array
{

    if ($value === '') {

        return [];

    }

    if (is_serialized($value)) {

        // Link fields hold IDs only; no class is ever needed to read one.
        return uamswp_delete_cleanup_ids(unserialize($value, ['allowed_classes' => false]));

    }

    $decoded = json_decode($value, true);

    return uamswp_delete_cleanup_ids(is_array($decoded) ? $decoded : $value);

}

/**
 * Read every link field and custom table, and build the list of what is dead.
 *
 * @param  bool  $store  Keep the result as the run and its row backup. A check made after a removal
 *                       has started passes false, so it reports without replacing the undo.
 * @return array<string, mixed> The run: `items` (each a link or a row), `counts` and `status`.
 */
function uamswp_dead_links_scan(bool $store = true): array
{

    global $wpdb;

    $sources = uamswp_dead_links_sources();

    // [owner post, field, shape, ids] for every link field value read.
    $reads = [];

    foreach ($sources['meta'] as [$post_type, $field, $shape]) {

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT m.post_id, m.meta_value FROM {$wpdb->postmeta} m INNER JOIN {$wpdb->posts} p ON p.ID = m.post_id WHERE p.post_type = %s AND m.meta_key = %s AND m.meta_value <> ''",
                $post_type,
                $field
            ),
            ARRAY_A
        );

        foreach ((array) $rows as $row) {

            $reads[] = [(int) $row['post_id'], $field, $shape, uamswp_dead_links_stored_ids((string) $row['meta_value'])];

        }

    }

    foreach ($sources['tables'] as [$table, $field]) {

        if (! uamswp_dead_links_table_exists($table)) {

            continue;

        }

        // Only rows whose post exists: a row with no post is listed as a row, not as a record with links.
        $rows = $wpdb->get_results("SELECT t.post_id, t.`{$field}` AS links FROM {$wpdb->prefix}{$table} t INNER JOIN {$wpdb->posts} p ON p.ID = t.post_id WHERE t.`{$field}` <> ''", ARRAY_A);

        foreach ((array) $rows as $row) {

            $reads[] = [(int) $row['post_id'], $field, 'list', uamswp_dead_links_stored_ids((string) $row['links'])];

        }

    }

    $all_ids = [];

    foreach ($reads as $read) {

        $all_ids = array_merge($all_ids, $read[3]);

    }

    $missing = array_flip(uamswp_dead_links_missing($all_ids));
    $items = [];
    $at = [];

    foreach ($reads as [$owner, $field, $shape, $ids]) {

        $dead = array_values(array_filter($ids, fn (int $id): bool => isset($missing[$id])));
        $key = $owner.'|'.$field;

        if ($dead === []) {

            continue;

        }

        // A field kept in both post meta and a custom table is one link to the editor: one item,
        // listing the dead IDs found in either copy.
        if (isset($at[$key])) {

            $items[$at[$key]]['dead'] = array_values(array_unique(array_merge($items[$at[$key]]['dead'], $dead)));

            continue;

        }

        $at[$key] = count($items);
        $items[] = ['kind' => 'link', 'post' => $owner, 'field' => $field, 'shape' => $shape, 'dead' => $dead, 'state' => 'pending'];

    }

    $rows_backup = [];

    foreach ($sources['own'] as $table) {

        if (! uamswp_dead_links_table_exists($table)) {

            continue;

        }

        $orphans = $wpdb->get_results("SELECT t.* FROM {$wpdb->prefix}{$table} t LEFT JOIN {$wpdb->posts} p ON p.ID = t.post_id WHERE p.ID IS NULL", ARRAY_A);

        foreach ((array) $orphans as $row) {

            $items[] = ['kind' => 'row', 'table' => $table, 'post' => (int) $row['post_id'], 'state' => 'pending'];
            $rows_backup[$table.'|'.(int) $row['post_id']] = $row;

        }

    }

    $run = [
        'status' => 'scanned',
        'scanned_at' => time(),
        'cursor' => 0,
        'items' => $items,
    ];
    $run['counts'] = uamswp_dead_links_counts($run);

    if ($store) {

        uamswp_dead_links_save(UAMSWP_DEAD_LINKS_ROWS_OPTION, $rows_backup);
        uamswp_dead_links_save(UAMSWP_DEAD_LINKS_RUN_OPTION, $run);

    }

    return $run;

}

/**
 * Save an option and prove the database holds it.
 *
 * `update_option()` returns false both when the write failed and when the value did not change, so
 * its answer cannot be trusted either way. The stored value is compared by hash instead.
 *
 * @param  array<mixed>  $value
 */
function uamswp_dead_links_save(string $option, array $value): bool
{

    global $wpdb;

    update_option($option, $value, false);

    $stored = $wpdb->get_var($wpdb->prepare("SELECT MD5(option_value) FROM {$wpdb->options} WHERE option_name = %s", $option));

    return $stored === md5(maybe_serialize($value));

}

/**
 * Take the run lock, so two steps never work on the run at once.
 *
 * `add_option()` fails when the option exists, which makes it a lock that two requests cannot both
 * take. A lock older than five minutes belongs to a request that died, and is taken over.
 */
function uamswp_dead_links_lock(): bool
{

    $held = get_option('uamswp_fad_dead_links_lock', null);

    if ($held !== null && (int) $held > time() - 300) {

        return false;

    }

    if ($held !== null) {

        delete_option('uamswp_fad_dead_links_lock');

    }

    return add_option('uamswp_fad_dead_links_lock', time(), '', false);

}

/**
 * Release the run lock.
 */
function uamswp_dead_links_unlock(): void
{

    delete_option('uamswp_fad_dead_links_lock');

}

/**
 * Totals for a run: records and links with a dead ID, distinct dead IDs, orphan rows by table.
 *
 * @param  array<string, mixed>  $run
 * @return array<string, mixed>
 */
function uamswp_dead_links_counts(array $run): array
{

    $counts = ['links' => 0, 'rows' => [], 'done' => 0, 'skipped' => 0, 'failed' => 0];
    $records = [];
    $dead_ids = [];

    foreach ((array) ($run['items'] ?? []) as $item) {

        if ($item['state'] === 'done') {

            $counts['done']++;

        } elseif ($item['state'] === 'skipped') {

            $counts['skipped']++;

        } elseif ($item['state'] === 'failed') {

            $counts['failed']++;

        }

        if ($item['kind'] === 'link') {

            $records[$item['post']] = true;
            $counts['links'] += count($item['dead']);

            foreach ($item['dead'] as $id) {

                $dead_ids[$id] = true;

            }

        } else {

            $counts['rows'][$item['table']] = ($counts['rows'][$item['table']] ?? 0) + 1;

        }

    }

    $counts['records'] = count($records);
    $counts['dead_ids'] = count($dead_ids);

    return $counts;

}

/**
 * The IDs a link field holds now, read through ACF so the custom-table plugin answers for its fields.
 *
 * @return array<int, int>
 */
function uamswp_dead_links_read(string $field, int $post_id): array
{

    return function_exists('get_field') ? uamswp_delete_cleanup_ids(get_field($field, $post_id, false)) : [];

}

/**
 * Write a link field through ACF, so the custom-table plugin stores it in its own format.
 *
 * Lists are written as ID strings, the shape ACF stores a relationship field in.
 *
 * @param  array<int, int>|string  $value  The IDs, or an empty string to clear a single post-object field.
 */
function uamswp_dead_links_write(string $field, array|string $value, int $post_id): void
{

    if (function_exists('update_field')) {

        update_field($field, is_array($value) ? array_map(strval(...), $value) : $value, $post_id);

    }

}

/**
 * Whether a post exists, in any status.
 */
function uamswp_dead_links_post_exists(int $post_id): bool
{

    return get_post($post_id) instanceof WP_Post;

}

/**
 * Remove the dead IDs and orphan rows of the next batch.
 *
 * Each item is read again first: its current value is kept as the backup, and an ID that names a
 * post now, or a row whose post is back, is left alone. The backup is saved, and read back from the
 * database, before any write; if it cannot be saved the step stops with status `failed` and
 * writes nothing. An item whose backup was saved by a step that then ended early keeps that backup,
 * and is written, or recognized as already written, when the step runs again.
 *
 * @param  array<string, mixed>  $run
 * @return array<string, mixed> The run, with `status` set to `applied` once every item has been handled, or `failed`.
 */
function uamswp_dead_links_apply_step(array $run, int $limit = UAMSWP_DEAD_LINKS_BATCH): array
{

    global $wpdb;

    $run['status'] = 'applying';
    $rows_backup = (array) get_option(UAMSWP_DEAD_LINKS_ROWS_OPTION, []);
    $end = min($run['cursor'] + $limit, count($run['items']));
    $writes = [];

    // Step one: record what is about to be replaced.
    for ($i = $run['cursor']; $i < $end; $i++) {

        $item = $run['items'][$i];

        if ($item['state'] !== 'pending') {

            continue;

        }

        if ($item['kind'] === 'link') {

            if (! uamswp_dead_links_post_exists($item['post'])) {

                $run['items'][$i]['state'] = 'skipped';

                continue;

            }

            $current = uamswp_dead_links_read($item['field'], $item['post']);

            if (! isset($item['before'])) {

                $remove = uamswp_dead_links_missing(array_values(array_intersect($current, $item['dead'])));
                $run['items'][$i]['before'] = $current;
                $run['items'][$i]['remove'] = $remove;
                $run['items'][$i]['after'] = $item['shape'] === 'single' ? [] : array_values(array_diff($current, $remove));

                if ($remove === []) {

                    $run['items'][$i]['state'] = 'skipped';

                    continue;

                }

                $writes[] = $i;

                continue;

            }

            // An earlier step recorded this item and ended before it was marked.
            if ($current === $item['after']) {

                $run['items'][$i]['state'] = 'done';

            } elseif ($current === $item['before']) {

                $writes[] = $i;

            } else {

                $run['items'][$i]['state'] = 'skipped';

            }

            continue;

        }

        $key = $item['table'].'|'.$item['post'];
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}{$item['table']} WHERE post_id = %d", $item['post']), ARRAY_A);

        if (uamswp_dead_links_post_exists($item['post'])) {

            $run['items'][$i]['state'] = 'skipped';

        } elseif (is_array($row)) {

            $rows_backup[$key] = $row;
            $run['items'][$i]['saved'] = true;
            $writes[] = $i;

        } elseif (! empty($item['saved']) && isset($rows_backup[$key])) {

            // An earlier step deleted it and ended before it was marked.
            $run['items'][$i]['state'] = 'done';

        } else {

            $run['items'][$i]['state'] = 'skipped';

        }

    }

    // The backup is in the database before anything is written, or nothing is.
    if (! uamswp_dead_links_save(UAMSWP_DEAD_LINKS_ROWS_OPTION, $rows_backup) || ! uamswp_dead_links_save(UAMSWP_DEAD_LINKS_RUN_OPTION, $run)) {

        $run['status'] = 'failed';

        return $run;

    }

    // Step two: write.
    foreach ($writes as $i) {

        $item = $run['items'][$i];

        if ($item['kind'] === 'link') {

            uamswp_dead_links_write($item['field'], $item['shape'] === 'single' ? '' : $item['after'], $item['post']);
            $run['items'][$i]['state'] = 'done';

            continue;

        }

        $deleted = $wpdb->delete($wpdb->prefix.$item['table'], ['post_id' => $item['post']], ['%d']);
        $run['items'][$i]['state'] = $deleted === false ? 'failed' : 'done';

    }

    $run['cursor'] = $end;
    $run['status'] = $end >= count($run['items']) ? 'applied' : 'applying';
    $run['counts'] = uamswp_dead_links_counts($run);

    if (! uamswp_dead_links_save(UAMSWP_DEAD_LINKS_RUN_OPTION, $run)) {

        $run['status'] = 'failed';

    }

    return $run;

}

/**
 * Put back the IDs and rows of the next batch of a removal.
 *
 * A field is restored only when it still holds what the removal wrote. A row is restored only when
 * its post is still gone and no row has taken its place.
 *
 * @param  array<string, mixed>  $run
 * @return array<string, mixed> The run, with `status` set to `undone` once every item has been handled, or `failed`.
 */
function uamswp_dead_links_undo_step(array $run, int $limit = UAMSWP_DEAD_LINKS_BATCH): array
{

    global $wpdb;

    if ($run['status'] === 'applied') {

        $run['cursor'] = 0;

    }

    $rows_backup = (array) get_option(UAMSWP_DEAD_LINKS_ROWS_OPTION, []);
    $end = min($run['cursor'] + $limit, count($run['items']));

    for ($i = $run['cursor']; $i < $end; $i++) {

        $item = $run['items'][$i];

        if ($item['state'] !== 'done') {

            continue;

        }

        $state = 'kept';

        if ($item['kind'] === 'link') {

            $now = uamswp_dead_links_read($item['field'], $item['post']);

            if (! uamswp_dead_links_post_exists($item['post'])) {

            } elseif ($now === $item['after'] && $item['before'] !== $item['after']) {

                uamswp_dead_links_write($item['field'], $item['shape'] === 'single' ? (string) ($item['before'][0] ?? '') : $item['before'], $item['post']);
                $state = 'restored';

            } elseif ($now === $item['before']) {

                // An earlier undo step put it back and ended before it was marked.
                $state = 'restored';

            }

        } else {

            $row = $rows_backup[$item['table'].'|'.$item['post']] ?? null;
            $present = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}{$item['table']} WHERE post_id = %d", $item['post']));

            if (! is_array($row) || uamswp_dead_links_post_exists($item['post'])) {

            } elseif ($present !== '0') {

                // The row is back already: by an earlier undo step, or made again since.

            } else {

                $state = $wpdb->insert($wpdb->prefix.$item['table'], $row) === false ? 'failed' : 'restored';

            }

        }

        $run['items'][$i]['state'] = $state;

    }

    $run['cursor'] = $end;
    $run['status'] = $end >= count($run['items']) ? 'undone' : 'undoing';
    $run['counts'] = uamswp_dead_links_counts($run);

    if (! uamswp_dead_links_save(UAMSWP_DEAD_LINKS_RUN_OPTION, $run)) {

        $run['status'] = 'failed';

    }

    return $run;

}

/**
 * The stored run, or null before the first dry run or when what is stored is not a run.
 *
 * @return array<string, mixed>|null
 */
function uamswp_dead_links_run(): ?array
{

    $run = get_option(UAMSWP_DEAD_LINKS_RUN_OPTION, null);

    if (! is_array($run) || ! isset($run['status'], $run['cursor'], $run['scanned_at'], $run['items'], $run['counts']) || ! is_array($run['items']) || ! is_array($run['counts']) || ! is_array($run['counts']['rows'] ?? null)) {

        return null;

    }

    return $run;

}

/**
 * Add the screen under Find-a-Doc Settings.
 */
function uamswp_dead_links_menu(): void
{

    add_submenu_page('fad-settings', 'Dead Link Cleanup', 'Dead Link Cleanup', 'manage_options', 'uamswp-dead-links', 'uamswp_dead_links_screen', 95);

}

/**
 * The URL of a step, with its nonce.
 *
 * A removal or undo step carries the dry run it was started from, so a page that was open when a
 * newer dry run replaced the list cannot remove the newer list.
 *
 * @param  array<string, mixed>|null  $run
 */
function uamswp_dead_links_step_url(string $action, ?array $run = null): string
{

    $url = admin_url('admin-post.php?action='.$action);

    if ($run !== null) {

        $url = add_query_arg('run', (int) $run['scanned_at'], $url);

    }

    return wp_nonce_url($url, $action);

}

/**
 * Back to the screen.
 */
function uamswp_dead_links_back(string $notice = ''): never
{

    wp_safe_redirect(add_query_arg(array_filter(['page' => 'uamswp-dead-links', 'notice' => $notice]), admin_url('admin.php')));

    exit;

}

/**
 * Stop a request that is not an administrator's own and valid.
 */
function uamswp_dead_links_guard(string $action): void
{

    if (! current_user_can('manage_options')) {

        wp_die(esc_html__('You do not have permission to do this.', 'uamswp-find-a-doc'), '', ['response' => 403]);

    }

    check_admin_referer($action);

}

/**
 * Dry run: read everything, change nothing, show the list.
 *
 * Once a removal has started the stored run is its undo, so the dry run only reports what is left
 * and leaves the run alone.
 */
function uamswp_dead_links_handle_scan(): void
{

    uamswp_dead_links_guard('uamswp_dead_links_scan');

    $run = uamswp_dead_links_run();

    if ($run !== null && in_array($run['status'], ['applying', 'applied', 'undoing'], true)) {

        $left = uamswp_dead_links_scan(false)['counts'];
        set_transient('uamswp_fad_dead_links_check', ['at' => time(), 'links' => $left['links'], 'rows' => array_sum($left['rows'])], DAY_IN_SECONDS);

        uamswp_dead_links_back('checked');

    }

    $scanned = uamswp_dead_links_scan();
    $stored = uamswp_dead_links_run();

    uamswp_dead_links_back($stored !== null && $stored['scanned_at'] === $scanned['scanned_at'] ? '' : 'failed');

}

/**
 * Run one step of a removal or an undo, then come back for the next until the list is done.
 *
 * @param  callable(array<string, mixed>): array<string, mixed>  $step
 * @param  array<int, string>  $statuses  The run statuses the step may start from.
 */
function uamswp_dead_links_run_step(string $action, array $statuses, callable $step, string $done): never
{

    uamswp_dead_links_guard($action);

    $run = uamswp_dead_links_run();

    if ($run === null || ! in_array($run['status'], $statuses, true)) {

        uamswp_dead_links_back('nothing');

    }

    // Both steps write fields through ACF.
    if (! function_exists('get_field') || ! function_exists('update_field')) {

        uamswp_dead_links_back('noacf');

    }

    // Tied to the dry run the page was showing.
    if ((int) ($_GET['run'] ?? 0) !== (int) $run['scanned_at']) {

        uamswp_dead_links_back('stale');

    }

    if (! uamswp_dead_links_lock()) {

        uamswp_dead_links_back('busy');

    }

    try {

        $run = $step($run);

    } finally {

        uamswp_dead_links_unlock();

    }

    if ($run['status'] === 'failed') {

        uamswp_dead_links_back('failed');

    }

    if (in_array($run['status'], ['applying', 'undoing'], true)) {

        wp_safe_redirect(uamswp_dead_links_step_url($action, $run));

        exit;

    }

    uamswp_dead_links_back($done);

}

/**
 * Remove one batch, then come back for the next until the list is done.
 */
function uamswp_dead_links_handle_apply(): void
{

    uamswp_dead_links_run_step('uamswp_dead_links_apply', ['scanned', 'applying'], uamswp_dead_links_apply_step(...), 'applied');

}

/**
 * Put back one batch, then come back for the next until the list is done.
 */
function uamswp_dead_links_handle_undo(): void
{

    uamswp_dead_links_run_step('uamswp_dead_links_undo', ['applied', 'undoing'], uamswp_dead_links_undo_step(...), 'undone');

}

/**
 * One line of the list for a post: its title and a link to its edit screen.
 */
function uamswp_dead_links_post_label(int $post_id): string
{

    $title = get_the_title($post_id);
    $label = '#'.$post_id.($title !== '' ? ' '.$title : '');
    $edit = get_edit_post_link($post_id);

    return $edit ? '<a href="'.esc_url($edit).'">'.esc_html($label).'</a>' : esc_html($label);

}

/**
 * The screen.
 */
function uamswp_dead_links_screen(): void
{

    if (! current_user_can('manage_options')) {

        wp_die(esc_html__('You do not have permission to do this.', 'uamswp-find-a-doc'), '', ['response' => 403]);

    }

    $run = uamswp_dead_links_run();
    $status = $run['status'] ?? 'none';
    $notice = isset($_GET['notice']) ? sanitize_key(wp_unslash($_GET['notice'])) : '';
    $messages = [
        'applied' => 'The dead links and orphan rows in the list were removed. Use Check what is left to confirm none remain.',
        'undone' => 'The removal was undone.',
        'checked' => 'Checked. The result is under Last run; the removal and its undo are unchanged.',
        'nothing' => 'There was nothing to do for that step.',
        'stale' => 'That page was out of date: the list changed after it was shown. Review the list below and start again from it.',
        'busy' => 'Another step is running. Wait for it to finish.',
        'noacf' => 'Advanced Custom Fields is not available, so nothing can be removed or restored.',
        'failed' => 'The backup could not be saved, so nothing was changed by that step. Check the database and try again.',
    ];
    $bad = ['stale', 'busy', 'noacf', 'failed'];

    echo '<div class="wrap"><h1>Dead Link Cleanup</h1>';

    if (isset($messages[$notice])) {

        echo '<div class="notice notice-'.(in_array($notice, $bad, true) ? 'error' : 'success').'"><p>'.esc_html($messages[$notice]).'</p></div>';

    }

    echo '<p>Providers and locations deleted before the delete cleanup existed left their IDs in the relationship fields of the records linked to them, and their rows in the custom tables. This screen finds them, removes them, and can put them back.</p>';
    echo '<p><strong>Take a database backup first.</strong> The undo covers what this screen removed; it is not a database backup.</p>';

    $started = in_array($status, ['applying', 'applied', 'undoing'], true);
    $scan_label = $started ? 'Check what is left' : ($run === null ? 'Run the dry run' : 'Run the dry run again');

    echo '<p><a class="button" href="'.esc_url(uamswp_dead_links_step_url('uamswp_dead_links_scan')).'">'.esc_html($scan_label).'</a> ';

    if ($run !== null && in_array($status, ['scanned', 'applying'], true) && ($run['counts']['links'] + array_sum($run['counts']['rows'])) > 0) {

        echo '<a class="button button-primary" href="'.esc_url(uamswp_dead_links_step_url('uamswp_dead_links_apply', $run)).'">'.($status === 'applying' ? 'Continue the removal' : 'Remove everything in this list').'</a> ';

    }

    if ($run !== null && in_array($status, ['applied', 'undoing'], true)) {

        echo '<a class="button" href="'.esc_url(uamswp_dead_links_step_url('uamswp_dead_links_undo', $run)).'">'.($status === 'undoing' ? 'Continue the undo' : 'Undo the removal').'</a>';

    }

    echo '</p>';

    if ($run === null) {

        echo '</div>';

        return;

    }

    $counts = $run['counts'];
    $states = ['scanned' => 'Dry run done; nothing has been changed.', 'applying' => 'Removal in progress.', 'applied' => 'Removed.', 'undoing' => 'Undo in progress.', 'undone' => 'Removal undone.'];

    echo '<h2>Last run</h2><p>'.esc_html($states[$status] ?? $status).' Dry run of '.esc_html(wp_date('F j, Y g:i a', (int) $run['scanned_at'])).'.</p>';
    echo '<ul><li>'.esc_html((string) $counts['links']).' dead links on '.esc_html((string) $counts['records']).' records ('.esc_html((string) $counts['dead_ids']).' deleted IDs)</li>';

    foreach ($counts['rows'] as $table => $number) {

        echo '<li>'.esc_html((string) $number).' orphan rows in <code>'.esc_html((string) $table).'</code></li>';

    }

    echo '<li>'.esc_html((string) $counts['done']).' removed, '.esc_html((string) $counts['skipped']).' skipped because they were already fixed or their post had come back, '.esc_html((string) ($counts['failed'] ?? 0)).' failed</li></ul>';

    $check = get_transient('uamswp_fad_dead_links_check');

    if (is_array($check)) {

        echo '<p><strong>Check made '.esc_html(wp_date('F j, Y g:i a', (int) $check['at'])).':</strong> '.esc_html((string) $check['links']).' dead links and '.esc_html((string) $check['rows']).' orphan rows are left.</p>';

    }

    echo '<h2>Every dead link and orphan row</h2>';
    echo '<table class="widefat striped"><thead><tr><th>Record</th><th>Field or table</th><th>Missing IDs</th><th>State</th></tr></thead><tbody>';

    foreach ($run['items'] as $item) {

        $where = $item['kind'] === 'link' ? '<code>'.esc_html($item['field']).'</code>' : 'row in <code>'.esc_html($item['table']).'</code>';
        $ids = $item['kind'] === 'link' ? esc_html(implode(', ', $item['dead'])) : esc_html('#'.$item['post']);
        $record = $item['kind'] === 'link' ? uamswp_dead_links_post_label($item['post']) : esc_html('#'.$item['post'].' (deleted)');

        echo '<tr><td>'.$record.'</td><td>'.$where.'</td><td>'.$ids.'</td><td>'.esc_html($item['state']).'</td></tr>';

    }

    echo '</tbody></table></div>';

}

add_action('admin_menu', 'uamswp_dead_links_menu', 106);
add_action('admin_post_uamswp_dead_links_scan', 'uamswp_dead_links_handle_scan');
add_action('admin_post_uamswp_dead_links_apply', 'uamswp_dead_links_handle_apply');
add_action('admin_post_uamswp_dead_links_undo', 'uamswp_dead_links_handle_undo');
