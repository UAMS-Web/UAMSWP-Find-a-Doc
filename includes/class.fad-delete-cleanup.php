<?php

declare(strict_types=1);
/**
 * Delete cleanup.
 *
 * Permanently deleting a provider or a location used to leave its ID in the relationship lists
 * of the records linked to it, and leave its own row in the ACF Custom Database Tables. This
 * file removes both when the post is deleted for good. A post in the trash is still a post, so
 * trashing, restoring and editing change no list.
 *
 * The links live in two places. Conditions, treatments, expertise and clinical resources keep
 * theirs in post meta, as a serialized array of ID strings (or one plain ID for a single
 * post-object field). Providers and locations keep theirs in the custom tables, as a JSON array
 * of integers in one column. Both are found with direct SQL, so no query filter (editor groups,
 * FacetWP) can hide a linked record from the cleanup, and written back through ACF so the
 * custom-table plugin stores the value in its own format.
 */
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Where each deletable post type is linked from.
 *
 * `meta` lists post types that keep the link in post meta, as `[meta key, shape]` where the
 * shape is `list` (a relationship or multiple post-object field) or `single` (one post object).
 * `tables` lists custom-table links as `[table without prefix, ACF field name, post type]`.
 * `own_table` is the custom table that holds the deleted post's own row.
 *
 * @return array<string, array{meta: array<string, array<int, array{0: string, 1: string}>>, tables: array<int, array{0: string, 1: string, 2: string}>, own_table: string}>
 */
function uamswp_delete_cleanup_map(): array
{

    return [
        'provider' => [
            'meta' => [
                'clinical-resource' => [['clinical_resource_providers', 'list'], ['clinical_resource_spotlight_provider', 'single']],
                'condition' => [['condition_physicians', 'list']],
                'treatment' => [['treatment_procedure_physicians', 'list']],
                'expertise' => [['physician_expertise', 'list']],
            ],
            'tables' => [
                ['uamswp_locations', 'physician_locations', 'location'],
            ],
            'own_table' => 'uamswp_physicians',
        ],
        'location' => [
            'meta' => [
                'clinical-resource' => [['clinical_resource_locations', 'list']],
                'condition' => [['condition_locations', 'list']],
                'treatment' => [['treatment_procedure_locations', 'list']],
                'expertise' => [['location_expertise', 'list']],
            ],
            'tables' => [
                ['uamswp_physicians', 'physician_locations', 'provider'],
            ],
            'own_table' => 'uamswp_locations',
        ],
    ];

}

/**
 * Turn a raw ACF value into a list of integer IDs.
 *
 * @param  mixed  $value  An array of IDs (integers or strings), one ID, or an empty value.
 * @return array<int, int>
 */
function uamswp_delete_cleanup_ids($value): array
{

    if (in_array($value, [null, false, ''], true)) {

        return [];

    }

    $ids = [];

    foreach ((array) $value as $item) {

        if (is_object($item) && isset($item->ID)) {

            $item = $item->ID;

        }

        if (is_numeric($item) && (int) $item > 0) {

            $ids[] = (int) $item;

        }

    }

    return $ids;

}

/**
 * The IDs of posts of one type whose post meta links to an ID.
 *
 * Direct SQL on purpose: no query filter applies, and every status counts, trash included.
 * A list is serialized, with each ID either a string (`s:3:"123"`) or an integer (`i:123;`),
 * so both are matched with the quoted or terminated form that cannot hit a longer number.
 *
 * @return array<int, int>
 */
function uamswp_delete_cleanup_meta_post_ids(string $post_type, string $meta_key, string $shape, int $id): array
{

    /** @var wpdb $wpdb */
    global $wpdb;

    if ($shape === 'single') {

        $sql = $wpdb->prepare(
            "SELECT DISTINCT p.ID FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} m ON m.post_id = p.ID WHERE p.post_type = %s AND m.meta_key = %s AND m.meta_value = %s",
            $post_type,
            $meta_key,
            (string) $id
        );

    } else {

        $sql = $wpdb->prepare(
            "SELECT DISTINCT p.ID FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} m ON m.post_id = p.ID WHERE p.post_type = %s AND m.meta_key = %s AND (m.meta_value LIKE %s OR m.meta_value LIKE %s)",
            $post_type,
            $meta_key,
            '%'.$wpdb->esc_like('"'.$id.'"').'%',
            '%'.$wpdb->esc_like('i:'.$id.';').'%'
        );

    }

    return array_values(array_map(uamswp_fad_int(...), (array) $wpdb->get_col($sql)));

}

/**
 * The IDs of posts whose custom-table column lists an ID.
 *
 * The column holds a JSON array of integers. The LIKE only narrows the rows (it also matches
 * 12 inside 123); each candidate is decoded and checked, so only a real member is returned.
 * Table and column names come from uamswp_delete_cleanup_map(), never from input.
 *
 * @return array<int, int>
 */
function uamswp_delete_cleanup_table_post_ids(string $table, string $field, int $id): array
{

    /** @var wpdb $wpdb */
    global $wpdb;

    $sql = $wpdb->prepare(
        "SELECT post_id, `{$field}` AS links FROM {$wpdb->prefix}{$table} WHERE `{$field}` LIKE %s",
        '%'.$wpdb->esc_like((string) $id).'%'
    );

    $found = [];

    foreach ((array) $wpdb->get_results($sql, ARRAY_A) as $row) {

        $decoded = json_decode(uamswp_fad_str((uamswp_fad_arr($row)['links'] ?? '')), true);
        $ids = uamswp_delete_cleanup_ids(is_array($decoded) ? $decoded : []);

        if (in_array($id, $ids, true)) {

            $found[] = uamswp_fad_int(uamswp_fad_arr($row)['post_id']);

        }

    }

    return $found;

}

/**
 * Remove an ID from one linked field of one post.
 *
 * Reads the stored IDs unformatted, and writes only when the ID is in the field.
 *
 * @return bool Whether the field was changed.
 */
function uamswp_delete_cleanup_unlink(int $linked_post_id, string $field, string $shape, int $id): bool
{

    // Without ACF there are no fields to read or write.
    if (! function_exists('get_field') || ! function_exists('update_field')) {

        return false;

    }

    $stored = uamswp_delete_cleanup_ids(get_field($field, $linked_post_id, false));

    if (! in_array($id, $stored, true)) {

        return false;

    }

    update_field($field, $shape === 'single' ? '' : array_values(array_diff($stored, [$id])), $linked_post_id);

    return true;

}

/**
 * The custom table that holds a deleted post's own row, remembered until the post is gone.
 *
 * @param  int|null  $post_id  Remember this post's table.
 * @param  string|null  $table  The table (without prefix) to remember for it.
 * @return array<int, string> Every remembered post ID with its table.
 */
function uamswp_delete_cleanup_pending(?int $post_id = null, ?string $table = null): array
{

    static $pending = [];

    if ($post_id !== null && $table !== null) {

        $pending[$post_id] = $table;

    } elseif ($post_id !== null) {

        unset($pending[$post_id]);

    }

    return $pending;

}

/**
 * Write a cleanup failure to the PHP error log.
 */
function uamswp_delete_cleanup_log(string $message): void
{

    error_log('UAMSWP Find-a-Doc: '.$message);

}

/**
 * Before a provider or location is deleted for good, remove it from everything linked to it.
 *
 * A failure on one linked record is logged and the rest still run: the delete must not be
 * blocked by a cleanup, and what is left behind is what the cleanup screen (#838) finds.
 *
 * @param  int|string  $post_id
 */
function uamswp_delete_cleanup_before_delete($post_id): void
{

    $map = uamswp_delete_cleanup_map();
    $post_type = is_numeric($post_id) ? get_post_type((int) $post_id) : false;

    if (! is_string($post_type) || ! isset($map[$post_type])) {

        return;

    }

    $id = (int) $post_id;
    $links = $map[$post_type];

    uamswp_delete_cleanup_pending($id, $links['own_table']);

    $targets = [];

    foreach ($links['meta'] as $linked_type => $fields) {

        foreach ($fields as [$field, $shape]) {

            foreach (uamswp_delete_cleanup_meta_post_ids($linked_type, $field, $shape, $id) as $linked_id) {

                $targets[] = [$linked_id, $field, $shape];

            }

        }

    }

    foreach ($links['tables'] as [$table, $field]) {

        foreach (uamswp_delete_cleanup_table_post_ids($table, $field, $id) as $linked_id) {

            $targets[] = [$linked_id, $field, 'list'];

        }

    }

    foreach ($targets as [$linked_id, $field, $shape]) {

        try {

            uamswp_delete_cleanup_unlink($linked_id, $field, $shape, $id);

        } catch (Throwable $throwable) {

            uamswp_delete_cleanup_log(sprintf('could not remove deleted %s %d from %s on post %d: %s', $post_type, $id, $field, $linked_id, $throwable->getMessage()));

        }

    }

}

/**
 * Once a provider or location is gone, delete its own custom-table row.
 *
 * @param  int|string  $post_id
 */
function uamswp_delete_cleanup_after_delete($post_id): void
{

    if (! is_numeric($post_id)) {

        return;

    }

    $pending = uamswp_delete_cleanup_pending();
    $id = (int) $post_id;

    if (! isset($pending[$id])) {

        return;

    }

    /** @var wpdb $wpdb */
    global $wpdb;

    $wpdb->delete($wpdb->prefix.$pending[$id], ['post_id' => $id], ['%d']);

    uamswp_delete_cleanup_pending($id);

}

/**
 * Register the delete hooks.
 *
 * `before_delete_post` runs only for a permanent delete, from every route: the list screen,
 * the edit screen, `wp_delete_post( $id, true )` and the scheduled emptying of the trash.
 */
function uamswp_delete_cleanup_register(): void
{

    add_action('before_delete_post', 'uamswp_delete_cleanup_before_delete');
    add_action('deleted_post', 'uamswp_delete_cleanup_after_delete');

}

uamswp_delete_cleanup_register();
