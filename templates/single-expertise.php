<?php
/*
 *
 *  Template Name: Services
 *  Designed for services single
 *
 */
// add_action( 'genesis_after_header', 'page_options', 5 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

function uams_default_page_body_class( $classes ) {

    $classes[] = 'page-template-default';
    return $classes;
}
add_filter( 'body_class', 'uams_default_page_body_class' );

add_action( 'genesis_after_entry', 'uamswp_expertise_conditions', 8 );
add_action( 'genesis_after_entry', 'uamswp_expertise_treatments', 10 );
add_action( 'genesis_after_entry', 'uamswp_physicians_facet', 12 );
add_action( 'genesis_after_entry', 'uamswp_expertise_locations', 14 );
add_action( 'genesis_after_entry', 'uamswp_expertise_associated', 16 );
function uamswp_physicians_facet() {
    // if(wp_get_post_parent_id(get_the_ID()) == 0) {
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
    </section>
<?php
    // }
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
genesis();