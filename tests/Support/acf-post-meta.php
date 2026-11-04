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
 */

declare(strict_types=1);

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

if (! function_exists('update_field')) {
    /**
     * @param  mixed  $selector
     * @param  mixed  $value
     * @param  mixed  $post_id
     */
    function update_field($selector, $value, $post_id = false): bool
    {
        $stored = is_array($value) ? array_map('strval', $value) : (string) $value;

        return (bool) update_post_meta((int) $post_id, (string) $selector, $stored);
    }
}
