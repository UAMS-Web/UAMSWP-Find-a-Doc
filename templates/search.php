<?php

/**
 * Author: Sridhar Katakam
 * Link: https://sridharkatakam.com/
 */

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'uamswp_do_searchwp_loop' );

// Remove search results page from google search results
function sp_titles_robots($html) { 
	$html = '<meta name="robots" content="noindex, noarchive, nosnippet"/>';
	$html .= '<meta name="google" content="noimageindex">';
	return $html;
}

remove_action( 'genesis_after_header', 'page_options', 5 );

add_action( 'genesis_before_loop', 'uamswp_search_page_before_entry', 5 );
add_action( 'genesis_before_loop', 'uamswp_search_page_heading', 8 );
add_action( 'genesis_after_loop', 'uamswp_search_page_after_entry', 10 );

/**
 * Outputs a custom loop
 *
 * @global mixed $paged current page number if paginated
 * @return void
 */
function uamswp_do_searchwp_loop() {
    global $post;

    $args = [
       's'  => get_search_query(),
       'engine' => 'default', // The Engine name.
    ];

    if ( ! empty( $args['s'] ) ) {
        $swp_query = new SWP_Query( $args );
    } else {
        $swp_query = new WP_Query( $args );
    }

    $current_blog_id = get_current_blog_id();
    // Open Layout
    echo '<div class="uams-module bg-auto">';
    echo '<div class="container-fluid">';
    echo '<div class="search-content row">';
    echo '<div class="col-12">';
    echo '<div class="inner-container content-width">';
    echo '<div class="pb-4">';
    get_search_form();
    if( function_exists('uamswp_search_post_type_links') ){
        echo uamswp_search_post_type_links();
    }
    echo '</div>';

    // SWP_Query
    if ( $swp_query->have_posts() ) :
        while ( $swp_query->have_posts() ) : 
            $swp_query->the_post();
            // Track whether we switched sites for this result.
            $switched_site = false;
            

            // Do we need to switch to the proper site for this result?
            if ( $current_blog_id !== $post->site ) {
                switch_to_blog( $post->site );
                $switched_site = true;
                $post = get_post( $post->id );
            } else {
                $post = get_post( $post->id );
            }

            uamswp_get_template_part( 'results', $post->post_type, ['post_id' => $post->id, 'blog_id' => $post->site], UAMS_FAD_PATH . 'templates/search-parts', STYLESHEETPATH . 'templates/parts' );

            if ( $switched_site ) {
                restore_current_blog();
            }
        endwhile;
        wp_reset_postdata();

    else :
        echo "<p>Sorry, no content matched your criteria.</p>";
    endif;

    // Close Layout
    echo '</div>'; // .inner-container
    echo '</div>'; // .col-12
    echo '</div>'; // .search-content
    genesis_posts_nav();
    echo '</div>'; // .container-fluid
    echo '</div>'; // .uams-module
}



/**
 * Arrange elements in the loop.
 */
function uamswp_loop_layout() {
    // remove post info.
    remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

    // remove post image (from theme settings).
    remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );

    // remove entry content.
    // remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

    // remove post content nav.
    remove_action( 'genesis_entry_content', 'genesis_do_post_content_nav', 12 );
    remove_action( 'genesis_entry_content', 'genesis_do_post_permalink', 14 );

    // force content limit.
    add_filter( 'genesis_pre_get_option_content_archive_limit', 'uamswp_content_limit' );

    // modify the Content Limit read more link.
    add_filter( 'get_the_content_more_link', 'uamswp_read_more_link' );

	// remove h1 modifications
    remove_filter( 'genesis_post_title_output', 'uamswp_entry_title_h1' );

    add_filter( 'genesis_post_title_output', 'uamswp_search_title' );

    // force excerpts.
    // add_filter( 'genesis_pre_get_option_content_archive', 'uamswp_show_excerpts' );

    // remove entry footer.
    remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
    remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
    remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
}

function uamswp_content_limit() {
    return '150'; // number of characters
}

function uamswp_read_more_link() {
    return '... <a class="more-link" href="' . get_permalink() . '">Continue Reading</a>';
}

function uamswp_show_excerpts() {
    return 'excerpts';
}

function new_excerpt_more( $more ) {
    return '... <a class="more-link" href="' . get_permalink() . '">Continue Reading</a>';
}

function uamswp_excerpt_length( $length ) {
    return 20; // pull first 20 words
}

function uamswp_search_page_before_entry () {
    echo '<article class="entry" itemtype="https://schema.org/CreativeWork">';
}

function uamswp_search_page_heading () {
    $s = isset( $_GET["s"] ) ? esc_html($_GET["s"]) : "";
    echo '<header class="entry-header"><h1 class="entry-title" itemprop="headline">Search results for: '. $s .'</h1></header>';
}

function uamswp_search_page_after_entry () {
    echo '</article>';
}

//* Prefix search breadcrumb trail with the text 'Search results for'
function uamswp_prefix_search_breadcrumb( $args ) {

    $args['labels']['search'] = 'Search results for ';

    return $args;

}
add_filter( 'genesis_breadcrumb_args', 'uamswp_prefix_search_breadcrumb' );

add_filter( 'get_the_excerpt', 'strip_shortcodes', 20 );

// modify the Excerpt read more link.
add_filter( 'excerpt_more', 'new_excerpt_more' );

// modify the length of post excerpts.
add_filter( 'excerpt_length', 'uamswp_excerpt_length' );

genesis();
