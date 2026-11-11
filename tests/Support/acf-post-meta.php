<?php

/**
 * Stand-ins for `get_field()` and `update_field()` in the Integration suite.
 *
 * The suite loads this plugin alone, so Advanced Custom Fields is not there. The
 * two functions store a field as post meta in the shape ACF uses for the
 * relationship fields the tests read: a list as a serialized array of ID strings,
 * a single post object as one ID string. A field that was never stored reads as
 * `false`, which is what ACF returns for it. Each is defined only when ACF has
 * not already defined it.
 *
 * `update_field()` also copies a list into the post's custom-table row when the
 * table has a column named like the field, as ACF Custom Database Tables does.
 * `get_field()` reads post meta only.
 */

declare(strict_types=1);

/**
 * Copy a stored list into the post's custom-table row, the way ACF Custom Database Tables keeps its
 * table in step with `update_field()`.
 *
 * The table is `<prefix>uamswp_physicians` for a provider and `<prefix>uamswp_locations` for a
 * location, and the column is named like the field. A post with no such table, column or row is
 * left as it is. The list is written as a JSON array of integers, the shape the plugin reads.
 *
 * @param  array<int, string>|string  $stored
 */
function uamswp_test_mirror_to_custom_table(string $field, $stored, int $post_id): void
{
    global $wpdb;

    $tables = ['provider' => 'uamswp_physicians', 'location' => 'uamswp_locations'];
    $table = $tables[get_post_type($post_id)] ?? null;

    if ($table === null || ! is_array($stored)) {
        return;
    }

    $table = $wpdb->prefix.$table;
    $previous = $wpdb->suppress_errors(true);
    $column = $wpdb->get_var($wpdb->prepare("SHOW COLUMNS FROM `{$table}` LIKE %s", $field));
    $wpdb->suppress_errors($previous);

    if ($column !== null) {
        $wpdb->update($table, [$field => (string) wp_json_encode(array_map('intval', $stored))], ['post_id' => $post_id]);
    }
}

if (! function_exists('get_field')) {
    /**
     * @param  mixed  $selector
     * @param  mixed  $post_id
     * @return mixed
     */
    function get_field($selector, $post_id = false, $format_value = true)
    {
        $value = get_post_meta((int) $post_id, (string) $selector, true);

        return $value === '' ? false : $value;
    }
}

if (! function_exists('get_field_object')) {
    /**
     * A field that was never stored reads as `false`, which is what ACF returns for it; a stored one has no choices.
     *
     * @param  mixed  $selector
     * @param  mixed  $post_id
     * @return array{value: mixed, choices: array<mixed>}|false
     */
    function get_field_object($selector, $post_id = false)
    {
        $value = get_field($selector, $post_id);

        return $value === false ? false : ['value' => $value, 'choices' => []];
    }
}

if (! function_exists('update_field')) {
    /**
     * @param  mixed  $selector
     * @param  mixed  $value
     * @param  mixed  $post_id
     */
    function update_field($selector, $value, $post_id = false): bool
    {
        $stored = is_array($value) ? array_map('strval', $value) : (string) $value;

        $saved = (bool) update_post_meta((int) $post_id, (string) $selector, $stored);

        uamswp_test_mirror_to_custom_table((string) $selector, $stored, (int) $post_id);

        return $saved;
    }
}
