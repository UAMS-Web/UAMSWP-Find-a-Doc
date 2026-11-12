<?php

/**
 * Callbacks shared by several page templates.
 *
 * Each template registers the ones it needs on its own hook; the functions are declared here once so
 * that more than one template can be loaded in a process.
 */

declare(strict_types=1);

/**
 * Add the default page template class to the body.
 *
 * @param  array<int, string>  $classes  The body classes.
 * @return array<int, string>
 */
function uams_default_page_body_class($classes)
{

    $classes[] = 'page-template-default';

    return $classes;
}

/**
 * Use the excerpt the template set as the SEOPress description.
 *
 * @param  string  $html  The description SEOPress built; not used.
 * @return mixed The excerpt global the template sets.
 */
function sp_titles_desc($html)
{
    global $excerpt;

    return $excerpt;
}

/**
 * Limit a posts query to titles that start with the letter in the `starts_with` query variable.
 *
 * @param  string  $where  The WHERE clause.
 * @param  WP_Query  $query  The query.
 * @return string
 */
function title_filter($where, $query)
{
    // Search for posts with the first letter
    /** @var wpdb $wpdb */
    global $wpdb;

    $starts_with = esc_sql(uamswp_fad_str($query->get('starts_with')));

    // @phpstan-ignore isset.variable (kept as it was; $starts_with is always set, so the guard never returns early)
    if (! isset($starts_with)) {
        return $where;
    }

    if (is_numeric($starts_with)) {
        $where .= " AND $wpdb->posts.post_title REGEXP '^[0-9]'";
        // $where .= ' AND ' . $wpdb->prepare( $wpdb->posts . ".post_title REGEXP %s", '^[0-9]' );
    } else {
        $where .= " AND $wpdb->posts.post_title LIKE '$starts_with%'";
    }

    return $where;

}

/**
 * Limit a terms query to names that start with the letter in the `__first_letter` argument.
 *
 * @param  array<string, string>  $clauses  The query clauses.
 * @param  array<int, string>  $taxonomies  The taxonomies queried.
 * @param  array<string, mixed>  $args  The query arguments.
 * @return array<string, string>
 */
function uamswp_terms_clauses($clauses, $taxonomies, $args)
{
    // Search for terms with the first letter
    /** @var wpdb $wpdb */
    global $wpdb;

    if (! isset($args['__first_letter'])) {
        return $clauses;
    }

    if (is_numeric($args['__first_letter'])) {
        $clauses['where'] .= ' AND '.$wpdb->prepare('t.name REGEXP %s', '^[0-9]');
    } else {
        $clauses['where'] .= ' AND '.$wpdb->prepare('t.name LIKE %s', $wpdb->esc_like(uamswp_fad_str($args['__first_letter'])).'%');
    }

    return $clauses;

}

/**
 * Add the white background class to the entry.
 *
 * @param  array<string, string>  $attributes  The entry attributes.
 * @return array<string, string>
 */
function uamswp_add_entry_class($attributes)
{
    $attributes['class'] .= ' bg-white';

    return $attributes;
}

/**
 * Dequeue the skip links script.
 */
function child_dequeue_skip_links(): void
{
    wp_dequeue_script('skip-links');
}
