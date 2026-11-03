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
 *    list and the values it read as the run's backup.
 * 2. Remove. Works through the list 25 items at a time, redirecting to itself between batches so a
 *    long run needs no JavaScript and no long request. Each item is read again just before it is
 *    written; an ID that exists now, or a row whose post is back, is left alone.
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

        return uamswp_delete_cleanup_ids(maybe_unserialize($value));

    }

    $decoded = json_decode($value, true);

    return uamswp_delete_cleanup_ids(is_array($decoded) ? $decoded : $value);

}

/**
 * Read every link field and custom table, and build the list of what is dead.
 *
 * @return array<string, mixed> The run: `items` (each a link or a row), `counts` and `status`.
 */
function uamswp_dead_links_scan(): array
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

        $rows = $wpdb->get_results("SELECT post_id, `{$field}` AS links FROM {$wpdb->prefix}{$table} WHERE `{$field}` <> ''", ARRAY_A);

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
    $seen = [];

    foreach ($reads as [$owner, $field, $shape, $ids]) {

        $dead = array_values(array_filter($ids, fn (int $id): bool => isset($missing[$id])));
        $key = $owner.'|'.$field;

        // A field kept in both post meta and a custom table is one link to the editor.
        if ($dead === [] || isset($seen[$key])) {

            continue;

        }

        $seen[$key] = true;
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

    update_option(UAMSWP_DEAD_LINKS_ROWS_OPTION, $rows_backup, false);
    update_option(UAMSWP_DEAD_LINKS_RUN_OPTION, $run, false);

    return $run;

}

/**
 * Totals for a run: records and links with a dead ID, distinct dead IDs, orphan rows by table.
 *
 * @param  array<string, mixed>  $run
 * @return array<string, mixed>
 */
function uamswp_dead_links_counts(array $run): array
{

    $counts = ['records' => 0, 'links' => 0, 'dead_ids' => 0, 'rows' => [], 'done' => 0, 'skipped' => 0];
    $records = [];
    $dead_ids = [];

    foreach ((array) ($run['items'] ?? []) as $item) {

        if ($item['state'] === 'done') {

            $counts['done']++;

        } elseif ($item['state'] === 'skipped') {

            $counts['skipped']++;

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
 * Remove the dead IDs and orphan rows of the next batch.
 *
 * Each item is read again first: its current value is kept as the backup, and an ID that names a
 * post now, or a row whose post is back, is left alone. The run is saved before the writes, so the
 * values being replaced are on record if a request ends partway.
 *
 * @param  array<string, mixed>  $run
 * @return array<string, mixed> The run, with `status` set to `applied` once every item has been handled.
 */
function uamswp_dead_links_apply_step(array $run, int $limit = UAMSWP_DEAD_LINKS_BATCH): array
{

    global $wpdb;

    $run['status'] = 'applying';
    $rows_backup = (array) get_option(UAMSWP_DEAD_LINKS_ROWS_OPTION, []);
    $end = min($run['cursor'] + $limit, count($run['items']));
    $before = [];

    // Step one: record what is about to be replaced.
    for ($i = $run['cursor']; $i < $end; $i++) {

        $item = $run['items'][$i];

        if ($item['kind'] === 'link') {

            $current = uamswp_delete_cleanup_ids(function_exists('get_field') ? get_field($item['field'], $item['post'], false) : []);
            $dead_now = uamswp_dead_links_missing(array_values(array_intersect($current, $item['dead'])));
            $run['items'][$i]['before'] = $current;
            $run['items'][$i]['remove'] = $dead_now;
            $run['items'][$i]['after'] = $item['shape'] === 'single' ? [] : array_values(array_diff($current, $dead_now));
            $before[$i] = $dead_now;

        } else {

            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}{$item['table']} WHERE post_id = %d", $item['post']), ARRAY_A);
            $gone = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE ID = %d", $item['post'])) === '0';

            if (is_array($row) && $gone) {

                $rows_backup[$item['table'].'|'.$item['post']] = $row;

            }

            $before[$i] = (is_array($row) && $gone) ? [1] : [];

        }

    }

    update_option(UAMSWP_DEAD_LINKS_ROWS_OPTION, $rows_backup, false);
    update_option(UAMSWP_DEAD_LINKS_RUN_OPTION, $run, false);

    // Step two: write.
    for ($i = $run['cursor']; $i < $end; $i++) {

        $item = $run['items'][$i];

        if ($before[$i] === []) {

            $run['items'][$i]['state'] = 'skipped';

            continue;

        }

        if ($item['kind'] === 'link') {

            if (! function_exists('update_field')) {

                $run['items'][$i]['state'] = 'skipped';

                continue;

            }

            update_field($item['field'], $item['shape'] === 'single' ? '' : $run['items'][$i]['after'], $item['post']);

        } else {

            $wpdb->delete($wpdb->prefix.$item['table'], ['post_id' => $item['post']], ['%d']);

        }

        $run['items'][$i]['state'] = 'done';

    }

    $run['cursor'] = $end;
    $run['status'] = $end >= count($run['items']) ? 'applied' : 'applying';
    $run['counts'] = uamswp_dead_links_counts($run);
    update_option(UAMSWP_DEAD_LINKS_RUN_OPTION, $run, false);

    return $run;

}

/**
 * Put back the IDs and rows of the next batch of a removal.
 *
 * A field is restored only when it still holds what the removal wrote. A row is restored only when
 * its post is still gone and no row has taken its place.
 *
 * @param  array<string, mixed>  $run
 * @return array<string, mixed> The run, with `status` set to `undone` once every item has been handled.
 */
function uamswp_dead_links_undo_step(array $run, int $limit = UAMSWP_DEAD_LINKS_BATCH): array
{

    global $wpdb;

    if ($run['status'] === 'applied') {

        $run['status'] = 'undoing';
        $run['cursor'] = 0;

    }

    $rows_backup = (array) get_option(UAMSWP_DEAD_LINKS_ROWS_OPTION, []);
    $end = min($run['cursor'] + $limit, count($run['items']));

    for ($i = $run['cursor']; $i < $end; $i++) {

        $item = $run['items'][$i];

        if ($item['state'] !== 'done') {

            continue;

        }

        $restored = false;

        if ($item['kind'] === 'link' && function_exists('get_field') && function_exists('update_field')) {

            $now = uamswp_delete_cleanup_ids(get_field($item['field'], $item['post'], false));

            if ($now === $item['after']) {

                update_field($item['field'], $item['shape'] === 'single' ? ($item['before'][0] ?? '') : $item['before'], $item['post']);
                $restored = true;

            }

        } elseif ($item['kind'] === 'row') {

            $row = $rows_backup[$item['table'].'|'.$item['post']] ?? null;
            $present = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}{$item['table']} WHERE post_id = %d", $item['post']));
            $gone = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE ID = %d", $item['post'])) === '0';

            if (is_array($row) && $present === '0' && $gone) {

                $wpdb->insert($wpdb->prefix.$item['table'], $row);
                $restored = true;

            }

        }

        $run['items'][$i]['state'] = $restored ? 'restored' : 'kept';

    }

    $run['cursor'] = $end;
    $run['status'] = $end >= count($run['items']) ? 'undone' : 'undoing';
    $run['counts'] = uamswp_dead_links_counts($run);
    update_option(UAMSWP_DEAD_LINKS_RUN_OPTION, $run, false);

    return $run;

}

/**
 * The stored run, or null before the first dry run.
 *
 * @return array<string, mixed>|null
 */
function uamswp_dead_links_run(): ?array
{

    $run = get_option(UAMSWP_DEAD_LINKS_RUN_OPTION, null);

    return is_array($run) ? $run : null;

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
 */
function uamswp_dead_links_step_url(string $action): string
{

    return wp_nonce_url(admin_url('admin-post.php?action='.$action), $action);

}

/**
 * Back to the screen.
 */
function uamswp_dead_links_back(string $notice = ''): void
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
 */
function uamswp_dead_links_handle_scan(): void
{

    uamswp_dead_links_guard('uamswp_dead_links_scan');
    uamswp_dead_links_scan();
    uamswp_dead_links_back();

}

/**
 * Remove one batch, then come back for the next until the list is done.
 */
function uamswp_dead_links_handle_apply(): void
{

    uamswp_dead_links_guard('uamswp_dead_links_apply');

    $run = uamswp_dead_links_run();

    if ($run === null || ! in_array($run['status'], ['scanned', 'applying'], true)) {

        uamswp_dead_links_back('nothing');

    }

    $run = uamswp_dead_links_apply_step($run);

    if ($run['status'] === 'applying') {

        wp_safe_redirect(uamswp_dead_links_step_url('uamswp_dead_links_apply'));

        exit;

    }

    uamswp_dead_links_back('applied');

}

/**
 * Put back one batch, then come back for the next until the list is done.
 */
function uamswp_dead_links_handle_undo(): void
{

    uamswp_dead_links_guard('uamswp_dead_links_undo');

    $run = uamswp_dead_links_run();

    if ($run === null || ! in_array($run['status'], ['applied', 'undoing'], true)) {

        uamswp_dead_links_back('nothing');

    }

    $run = uamswp_dead_links_undo_step($run);

    if ($run['status'] === 'undoing') {

        wp_safe_redirect(uamswp_dead_links_step_url('uamswp_dead_links_undo'));

        exit;

    }

    uamswp_dead_links_back('undone');

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
        'applied' => 'The dead links and orphan rows in the list were removed. Run the dry run again to confirm none are left.',
        'undone' => 'The removal was undone.',
        'nothing' => 'There was nothing to do for that step.',
    ];

    echo '<div class="wrap"><h1>Dead Link Cleanup</h1>';

    if (isset($messages[$notice])) {

        echo '<div class="notice notice-success"><p>'.esc_html($messages[$notice]).'</p></div>';

    }

    echo '<p>Providers and locations deleted before the delete cleanup existed left their IDs in the relationship fields of the records linked to them, and their rows in the custom tables. This screen finds them, removes them, and can put them back.</p>';
    echo '<p><strong>Take a database backup first.</strong> The undo covers what this screen removed; it is not a database backup.</p>';

    echo '<p><a class="button" href="'.esc_url(uamswp_dead_links_step_url('uamswp_dead_links_scan')).'">'.($run === null ? 'Run the dry run' : 'Run the dry run again').'</a> ';

    if (in_array($status, ['scanned', 'applying'], true) && ($run['counts']['links'] + array_sum($run['counts']['rows'])) > 0) {

        echo '<a class="button button-primary" href="'.esc_url(uamswp_dead_links_step_url('uamswp_dead_links_apply')).'">'.($status === 'applying' ? 'Continue the removal' : 'Remove everything in this list').'</a> ';

    }

    if (in_array($status, ['applied', 'undoing'], true)) {

        echo '<a class="button" href="'.esc_url(uamswp_dead_links_step_url('uamswp_dead_links_undo')).'">'.($status === 'undoing' ? 'Continue the undo' : 'Undo the removal').'</a>';

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

        echo '<li>'.esc_html((string) $number).' orphan rows in <code>'.esc_html($table).'</code></li>';

    }

    echo '<li>'.esc_html((string) $counts['done']).' removed, '.esc_html((string) $counts['skipped']).' skipped because they had already been fixed</li></ul>';

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
