<?php /* Template Name: Treatments Archive */

add_filter('body_class', 'uams_default_page_body_class');

add_filter('posts_where', 'title_filter', 10, 2);
$treatment_title = get_field('treatments_archive_headline', 'option') ?: 'Treatments &amp; Procedures';
$treatment_text = get_field('treatments_archive_intro_text', 'option');
$treatment_link = uamswp_fad_str(get_post_type_archive_link(get_query_var('post_type')));

$alpha = uamswp_fad_str($_GET['alpha'] ?? '');

add_filter('seopress_titles_title', 'uamswp_fad_title_archive_treatments', 15, 2);

get_header(); ?>

<div class="content-sidebar-wrap">
    <main id="genesis-content">
        <section class="archive-description">
            <header class="entry-header">
                <h1 class="entry-title" itemprop="headline"><?php echo $treatment_title; ?></h1>
            </header>
            <?php echo $treatment_text ? '<div class="entry-content clearfix" itemprop="text">'.$treatment_text.'</div>' : ''; ?>
        </section>
        <section class="uams-module conditions-treatments">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="module-title sr-only">List of Conditions</h2>
                            <?php
                            // if show all is set
                            $post_type = 'treatment';
if (isset($_GET['showall'])) {

    $args = ['post_type' => $post_type, 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1];

} else {
    // else show paged

    $paged = max(1, uamswp_fad_int(get_query_var('paged')));
    // number of tags to show per page
    $per_page = 40;
    $offset = ($paged - 1) * $per_page;
    $args = ['post_type' => $post_type, 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => $per_page, 'offset' => $offset];
    // `0` stands for every title that starts with a number (title_filter()).
    if ($alpha !== '') {
        $alpha = substr($alpha, 0, 1);
        $args['starts_with'] = $alpha;
        $args['posts_per_page'] = -1;
    }

}

$the_query = new WP_Query($args);

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
                                    <?php echo uamswp_fad_archive_alpha_links($treatment_link, $alpha, 'treatments and procedures'); ?>
                                </div>
                            </div>
                            <div class="list-container list-container-rows">
                            <?php

if ($the_query->have_posts()) {

    echo '<ul class="list">';

    while ($the_query->have_posts()) {
        $the_query->the_post();
        echo '<li>'.'<a href="'.esc_attr(get_the_permalink()).'" aria-label="'.sprintf(__('Learn about %s'), get_the_title()).'" '.' class="btn btn-outline-primary">'.get_the_title().'</a></li>';
    }

    echo '</ul>';

    wp_reset_postdata();

} else {

    echo '<p class="text-center content-width">No treatments meet your criteria.</p>';

}

// The page links, unless every item is shown. A list filtered by a character is all on one page, so it has none.
if (! isset($_GET['showall'])) {
    if ($alpha === '') {
        $pages = intdiv($the_query->found_posts + $per_page - 1, $per_page);
        echo uamswp_fad_archive_page_links($treatment_link, $paged, $pages, '', 'Treatment list pagination');
    }
} else {
    // showall is set, show link to get back to paged mode

    echo '<div class="show-paged"><p><a href="'.$treatment_link.'">Show paged</a></p></div>';

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
