<?php /* Template Name: Treatments Archive */


function uams_default_page_body_class( $classes ) {

    $classes[] = 'page-template-default';
    return $classes;
}
add_filter( 'body_class', 'uams_default_page_body_class' );

add_filter( 'posts_where', 'title_filter', 10, 2 );
function title_filter( $where, $query ){
    // Search for posts with the first letter
    global $wpdb;

    $starts_with = esc_sql( $query->get( 'starts_with' ) );

    if( !isset( $starts_with ) ){
        return $where;
    }

    if ( is_numeric($starts_with) ) {
        $where .= " AND $wpdb->posts.post_title REGEXP '^[0-9]'";
        // $where .= ' AND ' . $wpdb->prepare( $wpdb->posts . ".post_title REGEXP %s", '^[0-9]' );
    } else {
        $where .= " AND $wpdb->posts.post_title LIKE '$starts_with%'";
    }
    return $where;

}

$treatment_title = get_field('treatments_archive_headline', 'option') ?: 'Treatments &amp; Procedures';
$treatment_text = get_field('treatments_archive_intro_text', 'option');
$treatment_link = get_post_type_archive_link( get_query_var('post_type') );

$alpha = '';
if (isset($_GET['alpha'])) {
    $alpha = $_GET['alpha'];
}

// Override theme's method of defining the page title
function uamswp_fad_title($html) { 
    global $treatment_title;
	//you can add here all your conditions as if is_page(), is_category() etc.. 
	$html = $treatment_title . ' | ' . get_bloginfo( "name" );
	return $html;
}
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

get_header(); ?>

<div class="content-sidebar-wrap">
    <main id="genesis-content">
        <section class="archive-description">
            <header class="entry-header">
                <h1 class="entry-title" itemprop="headline"><?php echo $treatment_title; ?></h1>
            </header>
            <?php echo ($treatment_text ? '<div class="entry-content clearfix" itemprop="text">' . $treatment_text . '</div>' : '' ); ?>
        </section>
        <section class="uams-module conditions-treatments">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="module-title sr-only">List of Conditions</h2>
                            <?php
                            // if show all is set
                            $post_type = 'treatment';
                            if( isset($_GET['showall']) ):

                                $args = array( 'post_type' => $post_type, 'orderby' => 'title', 'order'  => 'ASC', 'posts_per_page' => -1 );

                            else:
                            // else show paged
                                
                                $paged = ( get_query_var('paged') ) ? get_query_var( 'paged' ) : 1;
                                // number of tags to show per page
                                $per_page = 40;
                                $offset = ( $paged-1 ) * $per_page;
                                $args = array( 'post_type' => $post_type, 'orderby' => 'title', 'order'  => 'ASC', 'posts_per_page' => $per_page, 'offset' => $offset );
                                if ( !empty($alpha) ) {
                                    $alpha = substr($alpha, 0, 1);
                                    $args['starts_with'] = $alpha;
                                    $args['posts_per_page'] = -1;
                                }

                            endif;

                            $the_query = new WP_Query( $args );

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
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>" <?php echo ('' != $alpha) ? 'aria-selected="false"' : 'aria-selected="true"'; ?> aria-label="Remove any filter"><span aria-hidden="true">All</span></a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=0" <?php echo ('0' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with a number"><span aria-hidden="true">#</span></a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=a" <?php echo ('a' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter A">A</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=b" <?php echo ('b' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter B">B</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=c" <?php echo ('c' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter C">C</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=d" <?php echo ('d' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter D">D</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=e" <?php echo ('e' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter E">E</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=f" <?php echo ('f' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter F">F</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=g" <?php echo ('g' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter G">G</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=h" <?php echo ('h' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter H">H</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=i" <?php echo ('i' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter I">I</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=j" <?php echo ('j' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter J">J</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=k" <?php echo ('k' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter K">K</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=l" <?php echo ('l' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter L">L</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=m" <?php echo ('m' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter M">M</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=n" <?php echo ('n' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter N">N</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=o" <?php echo ('o' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter O">O</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=p" <?php echo ('p' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter P">P</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=q" <?php echo ('q' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter Q">Q</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=r" <?php echo ('r' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter R">R</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=s" <?php echo ('s' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter S">S</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=t" <?php echo ('t' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter T">T</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=u" <?php echo ('u' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter U">U</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=v" <?php echo ('v' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter V">V</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=w" <?php echo ('w' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter W">W</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=x" <?php echo ('x' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter X">X</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=y" <?php echo ('y' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter Y">Y</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo $treatment_link; ?>?alpha=z" <?php echo ('z' == $alpha) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the treatments and procedures that begin with the letter Z">Z</a>
                                    </div>
                                </div>
                            </div>
                            <div class="list-container list-container-rows">
                            <?php

                            if ( $the_query->have_posts() ) {

                                echo '<ul class="list">';

                                while ( $the_query->have_posts() ) : $the_query->the_post();
                                    echo '<li>' . '<a href="' . esc_attr(get_the_permalink( )) . '" aria-label="' . sprintf( __( "Learn about %s" ), get_the_title() ) . '" ' . ' class="btn btn-outline-primary">' . get_the_title() .'</a></li>';
                                endwhile;

                                echo '</ul>';

                                wp_reset_postdata();

                            } else {

                                echo '<p class="text-center content-width">No treatments meet your criteria.</p>';

                            }

                            // pagination
                            // if showall isn't set
                            if( !isset($_GET['showall']) ):
                                $pages = '';
                                $args = array( 'post_type' => $post_type, 'orderby' => 'title', 'order'  => 'ASC' );
                                if ( !empty($alpha) ) {
                                    $alpha = substr($alpha, 0, 1);
                                    $args['starts_with'] = $alpha;
                                    $args['posts_per_page'] = -1;
                                    $pages = 1;
                                }

                                // $total_terms = $terms->count;
                                $total_terms = $the_query->found_posts;
                                if($pages == '') {
                                    $pages = ceil($total_terms/$per_page);
                                }

                                if(empty($paged)) $paged = 1;
                                $range = 1; // Number of items +/- current page
                                $showitems = ($range * 2)+1;

                                if($pages == '')
                                {
                                    // $total_terms = wp_count_terms( $taxonomy );
                                    $pages = ceil($total_terms/$per_page);
                                    if(!$pages)
                                    {
                                        $pages = 1;
                                    }
                                }

                                // if there's more than one page
                                if( 1 != $pages ): ?>
                                    <div class="list-pagination">
                                    <nav aria-label="Condtion list pagination">
                                        <ul class="pagination">
                                        <?php
                                        $alpha_url = isset($alpha) ? '?alpha='. $alpha : '';
                                        if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="First" href="'.$treatment_link.'page/1/'. $alpha_url .'"><span aria-hidden="true">&laquo;</span></a></li>';
                                        if($paged > 1 && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="Previous" href="'.$treatment_link.'page/'.($paged - 1).'/'. $alpha_url .'"><span aria-hidden="true">&lsaquo;</span></a></li>';

                                        for ($pagecount=1; $pagecount <= $pages; $pagecount++):
                                            if (1 != $pages &&( !($pagecount >= $paged+$range+1 || $pagecount <= $paged-$range-1) || $pages <= $showitems )) {
                                                echo ($paged == $pagecount)? '<li class="page-item current"><a class="page-link">'.$pagecount.'</a></li>':'<li class="page-item"><a class="page-link" href="'.$treatment_link.'page/'.$pagecount.'/'. $alpha_url .'" class="inactive" >'.$pagecount.'</a></li>'; 
                                            }
                                        endfor;

                                        if ($paged < $pages && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="Next" href="'.$treatment_link.'page/'.($paged+1).'/'. $alpha_url .'"><span aria-hidden="true">&rsaquo;</span></a></li>';
                                        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="Last" href="'.$treatment_link.'page/'.$pages.'/'. $alpha_url .'"><span aria-hidden="true">&raquo;</span></a></li>';
                                        ?>
                                        </ul>
                                    </nav>
                                </div>
                                    <?php
                                    // link to show all
                                    echo '<div class="show-all"><p><a href="'.$treatment_link.'?showall=true">Show all items</a></p></div>';
                                endif;

                            else:
                            // showall is set, show link to get back to paged mode

                            echo '<div class="show-paged"><p><a href="'.$treatment_link.'">Show paged</a></p></div>';

                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
<?php get_footer(); ?>
