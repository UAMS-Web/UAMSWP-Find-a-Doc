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
    <section class="container-fluid p-8 p-sm-10 bg-auto" id="doctors">
        <div class="row">
            <div class="col-12">
                <h2 class="module-title">Doctors</h2>
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
        echo '<p>Possible alternate names: '. $keyword_text .'</p>';
    endif;
}
function uamswp_expertise_conditions() {
    // load all 'conditions' terms for the post
    $conditions = get_field('expertise_conditions');

    if( $conditions ):
        include( UAMS_FAD_PATH . '/templates/loops/conditions-loop.php' );
    endif;
}
function uamswp_expertise_treatments() {
    $treatments = get_field('expertise_treatments');

    if( $treatments ): 
        // print_r($treatments); 
        include( UAMS_FAD_PATH . '/templates/loops/treatments-loop.php' );
    endif;
}
function uamswp_expertise_locations() {
    $locations = get_field('expertise_locations');
    if( $locations ): ?>
        <section class="container-fluid p-8 p-sm-10 location-list bg-auto" id="locations">
            <div class="row">
                <div class="col-12">
                    <h2 class="module-title">Locations</h2>
                    <div class="card-list-container">
                        <div class="card-list">
                        <?php foreach( $locations as $location ){
                            $id = $location; 
                            include( UAMS_FAD_PATH . '/templates/loops/location-card.php' ); 
                        } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif;
}
function uamswp_expertise_associated() {
    $expertises =  get_field('expertise_associated');
	if( $expertises ): ?>
		<section class="container-fluid p-8 p-sm-10 location-list bg-auto" id="expertise">
            <div class="row">
                <div class="col-12">
                    <h2 class="module-title">Associated Areas of Expertise</h2>
                    <div class="card-list-container">
                        <div class="card-list">
                        <?php foreach( $expertises as $expertise ) {
                            $id = $expertise; 
                            include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
                        } ?>
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