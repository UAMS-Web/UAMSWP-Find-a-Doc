<?php /* Template Name: Treatments Archive */

add_filter('body_class', 'uams_default_page_body_class');

add_filter('terms_clauses', 'uamswp_terms_clauses', 10, 3);
$treatment_title = get_field('treatments_archive_headline', 'option');
$treatment_text = get_field('treatments_archive_intro_text', 'option');

add_filter('seopress_titles_title', 'uamswp_fad_title_archive_taxonomy_treatments', 15, 1);

$alpha = uamswp_fad_str($_GET['alpha'] ?? '');

get_header(); ?>

<div class="content-sidebar-wrap">
    <main id="genesis-content">
        <article class="entry">
            <header class="entry-header">
                <h1 class="entry-title" itemprop="headline"><?php echo $treatment_title ?: 'Treatments &amp; Procedures'; ?></h1>
            </header>
            <?php echo $treatment_text ? '<div class="entry-content clearfix" itemprop="text">'.$treatment_text.'</div>' : ''; ?>
        </article>
        <section class="uams-module conditions-treatments">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="module-title sr-only">List of Treatments</h2>
                            <?php
                            // if show all is set
                            $taxonomy = 'treatment';
$paged = 1;
// number of tags to show per page
$per_page = 40;
if (isset($_GET['showall'])) {

    $args = ['taxonomy' => $taxonomy, 'hide_empty' => 0];

} else {
    // else show paged

    $paged = max(1, uamswp_fad_int(get_query_var('paged')));
    $offset = ($paged - 1) * $per_page;
    $args = ['taxonomy' => $taxonomy, 'number' => $per_page, 'offset' => $offset, 'hide_empty' => 0];
    // `0` stands for every name that starts with a number (uamswp_terms_clauses()).
    if ($alpha !== '') {
        $alpha = substr($alpha, 0, 1);
        $args['__first_letter'] = $alpha;
    }

}

$tax_terms = new WP_Term_Query($args);

// Insert Alpha links
?>
                            <div class="list-legend">
                                <div class="az-filter">
                                    <!-- All Filters:
                                        Disable any checkbox input that does not represent any Treatments. Add "disabled" to input.
                                        
                                        A-Z Filter:
                                        If we can legally do this, default to the "Featured" set of Treatments, either defined manually or by analytics.
                                        Start with "Featured" checkbox input checked. Add "checked" attribute to input.
                                        If user checks any other checkbox input, remove "checked" attribute from "Featured" checkbox input.

                                        If user checks "All"/"Any" checkbox input, remove "checked" attribute from checkbox input of other characters.
                                        After that, if user checks any other checkbox input, remove "checked" attribute from "Any"/"All" checkbox input.
                                    -->
                                    <?php echo uamswp_fad_archive_alpha_links(uamswp_fad_str(get_permalink()), $alpha, 'treatments and procedures'); ?>
                                </div>
                            </div>
                            <div class="list-container list-container-rows">
                            <?php

/** @var list<WP_Term>|null $tax_term_list */
$tax_term_list = $tax_terms->terms;
if (! empty($tax_term_list)) {

    echo '<ul class="list">';

    foreach ($tax_term_list as $tax_term) {
        /** @var string $tax_term_link */
        $tax_term_link = get_term_link($tax_term, $taxonomy);
        echo '<li>'.'<a href="'.esc_attr($tax_term_link).'" aria-label="'.sprintf(__('Learn about %s'), $tax_term->name).'" '.' class="btn btn-outline-primary">'.$tax_term->name.'</a></li>';
    }

    echo '</ul>';

} else {

    echo '<p class="text-center content-width">No treatments meet your criteria.</p>';

}

// The page links, unless every term is shown. A list filtered by a character keeps the filter on each page link.
if (! isset($_GET['showall'])) {
    $count_args = ['taxonomy' => $taxonomy, 'hide_empty' => 0, 'count' => true];
    $page_query = '';
    if ($alpha !== '') {
        $count_args['__first_letter'] = $alpha;
        $page_query = '?alpha='.$alpha;
    }

    $total_terms = uamswp_fad_int(wp_count_terms($count_args));
    $pages = intdiv($total_terms + $per_page - 1, $per_page);
    echo uamswp_fad_archive_page_links(uamswp_fad_str(get_permalink()), $paged, $pages, $page_query, 'Treatment list pagination');
} else {
    // showall is set, show link to get back to paged mode

    echo '<div class="show-paged"><p><a href="'.get_permalink().'">Show paged</a></p></div>';

}
?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
<?php get_footer(); ?>
