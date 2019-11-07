<?php /* Template Name: Treatments Archive */

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

$treatment_title = get_field('treatments_archive_headline', 'option');
$treatment_text = get_field('treatments_archive_intro_text', 'option');

get_header(); ?>
<main class="content col-sm-12" id="genesis-content">
    <section class="archive-description">
		<header class="entry-header">
			<h1 class="entry-title" itemprop="headline"><?php echo ( $treatment_title ? $treatment_title : 'Conditions' ); ?></h1>
		</header>
        <?php echo ($treatment_text ? '<div class="entry-content clearfix" itemprop="text">' . $treatment_text . '</div>' : '' ); ?>
    </section>
    <section class="uams-module">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="module-title sr-only">List of Conditions</h2>
                    <div class="list-container list-container-columns">
                        <?php
                        // if show all is set
                        $taxonomy = 'treatment_procedure';
                        if( isset($_GET['showall']) ):

                            $args = array( 'hide_empty' => 0 );

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
                                    Disable any checkbox input that does not represent any Treatments. Add "disabled" to input.
                                    
                                    A-Z Filter:
                                    If we can legally do this, default to the "Featured" set of Treatments, either defined manually or by analytics.
                                    Start with "Featured" checkbox input checked. Add "checked" attribute to input.
                                    If user checks any other checkbox input, remove "checked" attribute from "Featured" checkbox input.

                                    If user checks "All"/"Any" checkbox input, remove "checked" attribute from checkbox input of other characters.
                                    After that, if user checks any other checkbox input, remove "checked" attribute from "Any"/"All" checkbox input.
                                -->
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-any"><a href="<?php echo get_permalink(); ?>">All</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-number"><a href="<?php echo get_permalink(); ?>?alpha=0">#</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-A"><a href="<?php echo get_permalink(); ?>?alpha=a">A</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-B"><a href="<?php echo get_permalink(); ?>?alpha=b">B</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-C"><a href="<?php echo get_permalink(); ?>?alpha=c">C</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-D"><a href="<?php echo get_permalink(); ?>?alpha=d">D</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-E"><a href="<?php echo get_permalink(); ?>?alpha=e">E</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-F"><a href="<?php echo get_permalink(); ?>?alpha=f">F</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-G"><a href="<?php echo get_permalink(); ?>?alpha=g">G</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-H"><a href="<?php echo get_permalink(); ?>?alpha=h">H</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-I"><a href="<?php echo get_permalink(); ?>?alpha=i">I</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-J"><a href="<?php echo get_permalink(); ?>?alpha=j">J</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-K"><a href="<?php echo get_permalink(); ?>?alpha=k">K</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-L"><a href="<?php echo get_permalink(); ?>?alpha=l">L</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-M"><a href="<?php echo get_permalink(); ?>?alpha=m">M</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-N"><a href="<?php echo get_permalink(); ?>?alpha=n">N</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-O"><a href="<?php echo get_permalink(); ?>?alpha=o">O</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-P"><a href="<?php echo get_permalink(); ?>?alpha=p">P</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-Q"><a href="<?php echo get_permalink(); ?>?alpha=q">Q</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-R"><a href="<?php echo get_permalink(); ?>?alpha=r">R</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-S"><a href="<?php echo get_permalink(); ?>?alpha=s">S</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-T"><a href="<?php echo get_permalink(); ?>?alpha=t">T</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-U"><a href="<?php echo get_permalink(); ?>?alpha=u">U</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-V"><a href="<?php echo get_permalink(); ?>?alpha=v">V</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-W"><a href="<?php echo get_permalink(); ?>?alpha=w">W</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-X"><a href="<?php echo get_permalink(); ?>?alpha=x">X</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-Y"><a href="<?php echo get_permalink(); ?>?alpha=y">Y</a></label>
                                </div>
                                <div class="custom-control">
                                    <label class="az-filter-label" for="az-filter-Z"><a href="<?php echo get_permalink(); ?>?alpha=z">Z</a></label>
                                </div>
                            </div>
                        </div>
                        <?php

                        if ( ! empty($tax_terms->terms) ) {

                            echo '<ul class="list">';

                            foreach ($tax_terms->terms as $tax_term) {
                                echo '<li>' . '<a href="' . esc_attr(get_term_link($tax_term, $taxonomy)) . '" title="' . sprintf( __( "View all posts in %s" ), $tax_term->name ) . '" ' . '>' . $tax_term->name.'</a></li>';
                            }

                            echo '</ul>';

                        } else {

                            echo '<p>No Treatments meet your criteria.</p>';

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
                                echo '<a href="'.get_permalink().'?showall=true">show all</a>';
                            endif;

                        else:
                        // showall is set, show link to get back to paged mode

                            echo '<a href="'.get_permalink().'">show paged</a>';

                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
