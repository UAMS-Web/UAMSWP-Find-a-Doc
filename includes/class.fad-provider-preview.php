<?php

declare(strict_types=1);
/**
 * Read a provider's saved fields during a preview.
 *
 * ACF answers a preview request (a `preview` query argument whose `p`, `page_id` or `preview_id` is the
 * post) with the post's latest revision instead of the post, so the page shows unsaved changes. It does
 * that by swapping the post ID inside acf_get_valid_post_id(). The swap relies on the revision holding a
 * copy of the field values in core post meta. This plugin turns that storage off
 * (`acfcdt/settings/store_acf_values_in_core_meta` in class.fad-acf-functions.php), so a provider's values
 * live only in the provider custom table, under the saved post's ID. A revision has no row there, every
 * field reads empty, and the preview page renders without the name, title, location, languages or biography.
 *
 * The filter below hands back the saved post's ID when ACF has swapped in a provider's revision. A preview
 * therefore shows the saved profile, which is what the custom table can supply. Changes that have not been
 * saved are not shown, and could not have been: they are not in a table until the profile is saved.
 *
 * This file registers no hooks; the hook is added in class.fad-acf-functions.php.
 */

/**
 * The post types whose preview must read the saved post's fields.
 *
 * @return string[]
 */
function uamswp_fad_preview_saved_post_types(): array
{

    return ['provider'];

}

/**
 * Undo ACF's swap of a provider's post ID for its latest revision's ID.
 *
 * Runs on `acf/validate_post_id` after ACF's own handler, which swaps the ID.
 *
 * @param  mixed  $post_id  The ID ACF settled on: the post's, or its latest revision's during a preview.
 * @param  mixed  $original_post_id  The ID the caller asked for (unused; the revision's parent is the answer).
 * @return mixed The saved post's ID when $post_id is a provider's revision, otherwise $post_id unchanged.
 */
function uamswp_fad_preview_keep_saved_post_id($post_id, $original_post_id = null)
{

    // ACF swaps the ID only on a preview request, so every other call is spared the lookup below.
    if (! isset($_GET['preview']) || ! is_numeric($post_id)) {
        return $post_id;
    }

    $parent_id = wp_is_post_revision((int) $post_id);

    if (! $parent_id) {
        return $post_id;
    }

    if (! in_array(get_post_type($parent_id), uamswp_fad_preview_saved_post_types(), true)) {
        return $post_id;
    }

    return (int) $parent_id;

}
