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
 * Where to send a provider or location archive request so that it is filtered by the visitor's saved region.
 *
 * The region filter keeps the chosen region in the `wp_filter_region` cookie. A request for the archive that does
 * not yet carry the archive's own FacetWP region parameter is sent to the same address with that parameter added
 * from the cookie. The two archives used to do this inline before their `header()` and `exit()`.
 *
 * @param  string  $parameter  The archive's region parameter: `_provider_region` or `_location_region`.
 * @param  array<array-key, mixed>  $cookies  The request cookies (`$_COOKIE`).
 * @param  array<array-key, mixed>  $query  The query string variables (`$_GET`).
 * @param  array<array-key, mixed>  $server  The server variables (`$_SERVER`); `REQUEST_URI` is the path and query of the request.
 * @return string|null The address to redirect to, or null when there is no saved region or the request already has the parameter.
 */
function uamswp_fad_region_redirect_url(string $parameter, array $cookies, array $query, array $server): ?string
{
    if (! isset($cookies['wp_filter_region']) || isset($query[$parameter])) {
        return null;
    }

    $request_uri = uamswp_fad_str($server['REQUEST_URI'] ?? '');

    return $request_uri.(parse_url($request_uri, PHP_URL_QUERY) ? '&' : '?').$parameter.'='.uamswp_fad_str($cookies['wp_filter_region']);
}

/**
 * The A to Z filter above the condition and treatment lists: an All link, a # link and one link per letter.
 *
 * The link of the character the list is filtered by is marked selected, or the All link when it is not filtered.
 *
 * @param  string  $base_url  The address of the list, which each link adds `?alpha=` to.
 * @param  string  $selected  The character the list is filtered by, or an empty string.
 * @param  string  $noun  What the list holds, as its labels name it (`conditions and procedures`).
 */
function uamswp_fad_archive_alpha_links(string $base_url, string $selected, string $noun): string
{
    $html = '<div class="custom-control"><a class="az-filter-label" href="'.$base_url.'" aria-selected="'.($selected === '' ? 'true' : 'false').'" aria-label="Remove any filter"><span aria-hidden="true">All</span></a></div>';
    $html .= '<div class="custom-control"><a class="az-filter-label" href="'.$base_url.'?alpha=0" aria-selected="'.($selected === '0' ? 'true' : 'false').'" aria-label="Show only the '.$noun.' that begin with a number"><span aria-hidden="true">#</span></a></div>';

    foreach (range('a', 'z') as $letter) {
        $html .= '<div class="custom-control"><a class="az-filter-label" href="'.$base_url.'?alpha='.$letter.'" aria-selected="'.($selected === $letter ? 'true' : 'false').'" aria-label="Show only the '.$noun.' that begin with the letter '.strtoupper($letter).'">'.strtoupper($letter).'</a></div>';
    }

    return $html;
}

/**
 * The page links under the condition and treatment lists, and the link that shows every item on one page.
 *
 * The current page and one page on each side of it are linked, with links to the first, previous, next and last
 * pages once there are more than three pages; with three pages or fewer every page is linked.
 *
 * @param  string  $base_url  The address of the list, which each page link adds `page/<n>/` to.
 * @param  int  $paged  The page being shown, from 1.
 * @param  int  $pages  The number of pages.
 * @param  string  $query  The query string each page link ends with (`?alpha=b`), or an empty string.
 * @param  string  $label  The accessible name of the navigation (`Condition list pagination`).
 * @return string Nothing when there is only one page or none.
 */
function uamswp_fad_archive_page_links(string $base_url, int $paged, int $pages, string $query, string $label): string
{
    if ($pages <= 1) {
        return '';
    }

    $range = 1; // Pages linked on each side of the current one
    $show_items = ($range * 2) + 1;
    $page_link = static fn (int $page): string => $base_url.'page/'.$page.'/'.$query;

    $html = '<div class="list-pagination"><nav aria-label="'.$label.'"><ul class="pagination">';

    if ($paged > $range + 1 && $show_items < $pages) {
        $html .= '<li class="page-item"><a class="page-link" aria-label="First" href="'.$page_link(1).'"><span aria-hidden="true">&laquo;</span></a></li>';
    }

    if ($paged > 1 && $show_items < $pages) {
        $html .= '<li class="page-item"><a class="page-link" aria-label="Previous" href="'.$page_link($paged - 1).'"><span aria-hidden="true">&lsaquo;</span></a></li>';
    }

    for ($page = 1; $page <= $pages; $page++) {
        if ($pages <= $show_items || ($page <= $paged + $range && $page >= $paged - $range)) {
            $html .= $page === $paged
                ? '<li class="page-item current"><a class="page-link">'.$page.'</a></li>'
                : '<li class="page-item"><a class="page-link" href="'.$page_link($page).'" class="inactive" >'.$page.'</a></li>';
        }
    }

    if ($paged < $pages && $show_items < $pages) {
        $html .= '<li class="page-item"><a class="page-link" aria-label="Next" href="'.$page_link($paged + 1).'"><span aria-hidden="true">&rsaquo;</span></a></li>';
    }

    if ($paged < $pages - 1 && $show_items < $pages) {
        $html .= '<li class="page-item"><a class="page-link" aria-label="Last" href="'.$page_link($pages).'"><span aria-hidden="true">&raquo;</span></a></li>';
    }

    $html .= '</ul></nav></div>';

    return $html.'<div class="show-all"><p><a href="'.$base_url.'?showall=true">Show all items</a></p></div>';
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
