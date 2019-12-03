<?php
/*
 *
 *  Template Name: Services
 *  Designed for services single
 *
 */
// add_action( 'genesis_after_header', 'page_options', 5 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
// Removes entry meta from entry footer incl. markup.
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

function uams_default_page_body_class( $classes ) {

    $classes[] = 'page-template-default';
    return $classes;
}
add_filter( 'body_class', 'uams_default_page_body_class' );

// Add extra class to entry
function uamswp_add_entry_class( $attributes ) {
    $attributes['class'] = $attributes['class']. ' bg-white';
    return $attributes;
}
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

add_filter( 'genesis_entry_content', 'uamswp_expertise_keywords', 8);
add_action( 'genesis_entry_content', 'uamswp_expertise_youtube', 12 );
add_action( 'genesis_after_entry', 'uamswp_expertise_conditions', 8 );
add_action( 'genesis_after_entry', 'uamswp_expertise_treatments', 10 );
add_action( 'genesis_after_entry', 'uamswp_expertise_physicians', 12 );
add_action( 'genesis_after_entry', 'uamswp_expertise_locations', 14 );
add_action( 'genesis_after_entry', 'uamswp_expertise_associated', 16 );
add_action( 'wp_head', 'uamswp_expertise_header_metadata' );

function uamswp_expertise_physicians() {
    if(get_field('expertise_physicians')) {
?>
    <section class="uams-module bg-auto" id="doctors">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h2 class="module-title">Providers</h2>
                    <div class="card-list-container">
                        <div class="card-list card-list-doctors facetwp-template">
                            <?php echo facetwp_display( "template", "physicians_by_expertise" ); ?>
                        </div>
                    </div>
                    <div class="list-pagination">
                    <?php echo facetwp_display( "pager" ); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php // FacetWP Hide elements
            // Set # value depending on element
            ?>
        <script>
            (function($) {
                $(document).on('facetwp-loaded', function() {
                    if( 0 === FWP.settings.pager.total_rows ) {
                        $('#doctors').hide()
                    }
                    if (4 >= FWP.settings.pager.total_rows ) {
                        $('.list-pagination').hide()
                    }
                });
            })(jQuery);
        </script>
    </section>
<?php
    }
}
function uamswp_expertise_youtube() {
    if(get_field('expertise_youtube_link')) { ?>
        <div class="embed-responsive embed-responsive-16by9">
            <?php echo wp_oembed_get( get_field( 'expertise_youtube_link' ) ); ?>
        </div>
    <?php }
}
function uamswp_expertise_keywords() {
    $keywords = get_field('expertise_alternate_names');
    $keyword_text = '';
    if( $keywords ): 
        $i = 1;
        foreach( $keywords as $keyword ) { 
            if ( 1 < $i ) {
                $keyword_text .= '; ';
            }
            $keyword_text .= $keyword['text'];
            $i++;
        } 
        echo '<p class="text-callout text-callout-info">Also called: '. $keyword_text .'</p>';
    endif;
}
function uamswp_expertise_conditions() {
    // load all 'conditions' terms for the post
    $conditions = get_field('expertise_conditions');
    $args = (array(
        'taxonomy' => "condition",
        'order' => 'ASC',
        'orderby' => 'name',
        'hide_empty' => false,
        'term_taxonomy_id' => $conditions
    ));
    $conditions_query = new WP_Term_Query( $args );
    if( $conditions ):
        include( UAMS_FAD_PATH . '/templates/loops/conditions-loop.php' );
    endif;
}
function uamswp_expertise_treatments() {
    $treatments = get_field('expertise_treatments');
    $args = (array(
        'taxonomy' => "treatment_procedure",
        'order' => 'ASC',
        'orderby' => 'name',
        'hide_empty' => false,
        'term_taxonomy_id' => $treatments
    ));
    $treatments_query = new WP_Term_Query( $args );
    if( $treatments ): 
        include( UAMS_FAD_PATH . '/templates/loops/treatments-loop.php' );
    endif;
}
function uamswp_expertise_locations() {
    $locations = get_field('expertise_locations');
    $args = (array(
        'post_type' => "locations",
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post__in'	=> $locations
    ));
    $location_query = new WP_Query( $args );
    if( $location_query->have_posts() ): ?>
        <section class="container-fluid p-8 p-sm-10 location-list bg-auto" id="locations">
            <div class="row">
                <div class="col-12">
                    <h2 class="module-title">Locations</h2>
                    <div class="card-list-container">
                        <div class="card-list">
                        <?php while ( $location_query->have_posts() ) : $location_query->the_post();
                            $id = get_the_ID(); 
                            include( UAMS_FAD_PATH . '/templates/loops/location-card.php' ); 
                        endwhile; 
                        wp_reset_postdata();?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif;
}
function uamswp_expertise_associated() {
    $expertises =  get_field('expertise_associated');
    $args = (array(
        'post_type' => "expertise",
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post__in'	=> $expertises
    ));
    $expertise_query = new WP_Query( $args );
	if( $expertises ): ?>
		<section class="uams-module expertise-list bg-auto" id="expertise">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title">Associated Areas of Expertise</h2>
                        <div class="card-list-container">
                            <div class="card-list">
                            <?php while ( $expertise_query->have_posts() ) : $expertise_query->the_post();
                                $id = get_the_ID(); 
                                include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
                            endwhile;
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	<?php 
    endif;
}
function uamswp_expertise_header_metadata() { 
    $keywords = get_field('expertise_alternate_names');
    if( $keywords ): 
        $i = 1;
        $keyword_text = '';
        foreach( $keywords as $keyword ) { 
            if ( 1 < $i ) {
                $keyword_text .= ', ';
            }
            $keyword_text .= str_replace(",", "", $keyword['text']);
            $i++;
        }
        
        echo '<meta name="keywords" content="'. $keyword_text .'" />';
    endif;
}
genesis();