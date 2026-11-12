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

/**
 * Print the meta keywords tag from the `$keywords` global the template sets.
 *
 * The alternate names are the repeater rows of the template's `condition_alternate` field; commas inside a
 * name are dropped because the tag is comma separated.
 */
function uamswp_keyword_hook_header(): void
{
    /** @var array<int, array{text: string}>|false|null $keywords */
    global $keywords;
    $keyword_text = '';
    if ($keywords) {
        $i = 1;
        foreach ($keywords as $keyword) {
            if ($i > 1) {
                $keyword_text .= ', ';
            }

            $keyword_text .= str_replace(',', '', $keyword['text']);
            $i++;
        }

        echo '<meta name="keywords" content="'.$keyword_text.'" />';
    }
}

/**
 * The text of an ACF field as a trimmed excerpt, for the provider archive cards.
 *
 * @param  string  $title  The ACF field name.
 * @return mixed The excerpt after the `the_excerpt` filters.
 */
function custom_field_excerpt($title)
{
    global $post;
    /** @var string $text */
    $text = get_field($title);
    if ($text != '') {
        $text = strip_shortcodes($text);
        $text = uamswp_fad_str(apply_filters('the_content', $text));
        $text = str_replace(']]>', ']]>', $text);
        $excerpt_length = 35; // 35 words
        $excerpt_more = uamswp_fad_str(apply_filters('excerpt_more', ' '.'[...]'));
        $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
    }

    return apply_filters('the_excerpt', $text);
}

/**
 * The excerpt length the provider archive uses, in words.
 *
 * @param  int  $length  The length WordPress would use; not used.
 */
function wpdocs_custom_excerpt_length($length): int
{
    return 35; // 35 words
}

/**
 * Replace the last breadcrumb of a provider page with the provider's name.
 *
 * @param  string  $crumb  The breadcrumb Genesis built.
 * @param  array<string, mixed>  $args  The breadcrumb arguments.
 */
function be_remove_title_from_single_crumb($crumb, $args): string // Because BE is the man
{
    /** @var string $full_name */
    global $full_name;

    // Genesis outputs the filtered crumb as raw HTML, so escape the provider
    // name (a plain-text ACF field, not kses-filtered).
    return substr($crumb, 0, (int) strrpos($crumb, uamswp_fad_str($args['sep']))).uamswp_fad_str($args['sep']).uamswp_provider_name_html($full_name);
}

/**
 * Replace the last SEOPress breadcrumb of a provider page with the provider's name and permalink.
 *
 * @param  array<int, mixed>  $crumbs  The breadcrumbs SEOPress built.
 * @return array<int, mixed>
 */
// SEOPress Breadcrumbs Fix
function sp_change_title_from_provider_crumb($crumbs) // SEOPress
{
    /** @var string $full_name */
    global $full_name;
    array_pop($crumbs);
    $provider_name = [$full_name, get_permalink()];
    $crumbs[] = $provider_name;

    return $crumbs;
}

/**
 * Print the meta keywords tag for the treatment procedure taxonomy template.
 *
 * Kept as it was: the function never reads the `$keywords` global, so it prints nothing (#1266).
 */
function uamswp_keyword_hook_header_taxonomy_treatment_procedure(): void
{
    $keyword_text = '';
    // @phpstan-ignore variable.undefined (kept as it was; the function never reads the $keywords global, see #1266)
    if ($keywords) {
        $i = 1;
        // @phpstan-ignore variable.undefined, foreach.nonIterable (same: $keywords is never set in this function, see #1266)
        foreach ($keywords as $keyword) {
            if ($i > 1) {
                $keyword_text .= ', ';
            }

            // @phpstan-ignore offsetAccess.nonOffsetAccessible, argument.type (same: $keyword comes from the never-set $keywords, see #1266)
            $keyword_text .= str_replace(',', '', $keyword['text']);
            $i++;
        }

        echo '<meta name="keywords" content="'.$keyword_text.'" />';
    }
}
