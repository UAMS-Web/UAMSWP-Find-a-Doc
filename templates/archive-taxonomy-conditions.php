<?php /* Template Name: Conditions Archive */

function uams_default_page_body_class( $classes ) {

    $classes[] = 'page-template-default';
    return $classes;
}
add_filter( 'body_class', 'uams_default_page_body_class' );

add_filter( 'terms_clauses', 'uamswp_terms_clauses', 10, 3 );
function uamswp_terms_clauses( $clauses, $taxonomies, $args ){
    // Search for terms with the first letter
    global $wpdb;

    if( !isset( $args['__first_letter'] ) ){
        return $clauses;
    }

    if ( is_numeric($args['__first_letter']) ) {
        $clauses['where'] .= ' AND ' . $wpdb->prepare( "t.name REGEXP %s", '^[0-9]' );
    } else {
        $clauses['where'] .= ' AND ' . $wpdb->prepare( "t.name LIKE %s", $wpdb->esc_like( $args['__first_letter'] ) . '%' );
    }
    return $clauses;

}

$condition_title = get_field('conditions_archive_headline', 'option');
$condition_text = get_field('conditions_archive_intro_text', 'option');

get_header(); ?>

<?php genesis_breadcrumb(); ?>
<div class="content-sidebar-wrap row">
    <main class="content col-sm-12" id="genesis-content">
        <article class="entry">
            <header class="entry-header">
                <h1 class="entry-title" itemprop="headline"><?php echo ( $condition_title ? $condition_title : 'Conditions' ); ?></h1>
            </header>
            <?php echo ($condition_text ? '<div class="entry-content clearfix" itemprop="text">' . $condition_text . '</div>' : '' ); ?>
        </article>
        <section class="uams-module conditions-treatments">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="module-title sr-only">List of Conditions</h2>
                            <?php
                            // if show all is set
                            $taxonomy = 'condition';
                            if( isset($_GET['showall']) ):

                                $args = array( 'taxonomy' => $taxonomy, 'hide_empty' => 0 );

                            else:
                            // else show paged
                                
                                $paged = ( get_query_var('paged') ) ? get_query_var( 'paged' ) : 1;
                                // number of tags to show per page
                                $per_page = 40;
                                $offset = ( $paged-1 ) * $per_page;
                                $args = array( 'taxonomy' => $taxonomy, 'number' => $per_page, 'offset' => $offset, 'hide_empty' => 0 );
                                if ( isset($_GET['alpha']) ) {
                                    $alpha = substr($_GET['alpha'], 0, 1);
                                    $args['__first_letter'] = $alpha;
                                }

                            endif;

                            $tax_terms = new WP_Term_Query( $args );

                            // Insert Alpha links
                            ?>
                            <div class="list-legend">
                                <div class="az-filter">
                                    <!-- All Filters:
                                        Disable any checkbox input that does not represent any conditions. Add "disabled" to input.
                                        
                                        A-Z Filter:
                                        If we can legally do this, default to the "Featured" set of conditions, either defined manually or by analytics.
                                        Start with "Featured" checkbox input checked. Add "checked" attribute to input.
                                        If user checks any other checkbox input, remove "checked" attribute from "Featured" checkbox input.

                                        If user checks "All"/"Any" checkbox input, remove "checked" attribute from checkbox input of other characters.
                                        After that, if user checks any other checkbox input, remove "checked" attribute from "Any"/"All" checkbox input.
                                    -->
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>" aria-label="Remove any filter">All</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=0" aria-label="Show only the conditions that begin with a number">#</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=a" aria-label="Show only the conditions that begin with the letter A">A</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=b" aria-label="Show only the conditions that begin with the letter B">B</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=c" aria-label="Show only the conditions that begin with the letter C">C</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=d" aria-label="Show only the conditions that begin with the letter D">D</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=e" aria-label="Show only the conditions that begin with the letter E">E</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=f" aria-label="Show only the conditions that begin with the letter F">F</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=g" aria-label="Show only the conditions that begin with the letter G">G</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=h" aria-label="Show only the conditions that begin with the letter H">H</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=i" aria-label="Show only the conditions that begin with the letter I">I</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=j" aria-label="Show only the conditions that begin with the letter J">J</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=k" aria-label="Show only the conditions that begin with the letter K">K</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=l" aria-label="Show only the conditions that begin with the letter L">L</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=m" aria-label="Show only the conditions that begin with the letter M">M</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=n" aria-label="Show only the conditions that begin with the letter N">N</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=o" aria-label="Show only the conditions that begin with the letter O">O</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=p" aria-label="Show only the conditions that begin with the letter P">P</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=q" aria-label="Show only the conditions that begin with the letter Q">Q</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=r" aria-label="Show only the conditions that begin with the letter R">R</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=s" aria-label="Show only the conditions that begin with the letter S">S</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=t" aria-label="Show only the conditions that begin with the letter T">T</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=u" aria-label="Show only the conditions that begin with the letter U">U</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=v" aria-label="Show only the conditions that begin with the letter V">V</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=w" aria-label="Show only the conditions that begin with the letter W">W</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=x" aria-label="Show only the conditions that begin with the letter X">X</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=y" aria-label="Show only the conditions that begin with the letter Y">Y</a>
                                    </div>
                                    <div class="custom-control">
                                        <a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=z" aria-label="Show only the conditions that begin with the letter Z">Z</a>
                                    </div>
                                </div>
                            </div>
                            <div class="list-container list-container-rows">
                            <?php

                            if ( ! empty($tax_terms->terms) ) {

                                echo '<ul class="list">';

                                foreach ($tax_terms->terms as $tax_term) {
                                    echo '<li>' . '<a href="' . esc_attr(get_term_link($tax_term, $taxonomy)) . '" aria-label="' . sprintf( __( "Learn about %s" ), $tax_term->name ) . '" ' . '>' . $tax_term->name.'</a></li>';
                                }

                                echo '</ul>';

                            } else {

                                echo '<p class="text-center content-width">No conditions meet your criteria.</p>';

                            }

                            // pagination
                            // if showall isn't set
                            if( !isset($_GET['showall']) ):

                                $args = array( 'taxonomy' => $taxonomy, 'hide_empty' => 0, 'count' => true );
                                if ( isset($_GET['alpha']) ) {
                                    $alpha = substr($_GET['alpha'], 0, 1);
                                    $args['__first_letter'] = $alpha;
                                }

                                // $total_terms = $terms->count;
                                $total_terms = wp_count_terms( $taxonomy, $args );
                                $pages = ceil($total_terms/$per_page);

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
                                        if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="First" href="'.get_permalink().'page/1/'. $alpha_url .'"><span aria-hidden="true">&laquo;</span></a></li>';
                                        if($paged > 1 && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="Previous" href="'.get_permalink().'page/'.($paged - 1).'/'. $alpha_url .'"><span aria-hidden="true">&lsaquo;</span></a></li>';

                                        for ($pagecount=1; $pagecount <= $pages; $pagecount++):
                                            if (1 != $pages &&( !($pagecount >= $paged+$range+1 || $pagecount <= $paged-$range-1) || $pages <= $showitems )) {
                                                echo ($paged == $pagecount)? '<li class="page-item current"><a class="page-link">'.$pagecount.'</a></li>':'<li class="page-item"><a class="page-link" href="'.get_permalink().'page/'.$pagecount.'/'. $alpha_url .'" class="inactive" >'.$pagecount.'</a></li>';
                                            }
                                        endfor;

                                        if ($paged < $pages && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="Next" href="'.get_permalink().'page/'.($paged+1).'/'. $alpha_url .'"><span aria-hidden="true">&rsaquo;</span></a></li>';
                                        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="Last" href="'.get_permalink().'page/'.$pages.'/'. $alpha_url .'"><span aria-hidden="true">&raquo;</span></a></li>';
                                        ?>
                                        </ul>
                                    </nav>
                                </div>
                                    <?php
                                    // link to show all
                                    echo '<div class="show-all"><p><a href="'.get_permalink().'?showall=true">Show all items</a></p></div>';
                                endif;

                            else:
                            // showall is set, show link to get back to paged mode

                            echo '<div class="show-paged"><p><a href="'.get_permalink().'">Show paged</a></p></div>';

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
