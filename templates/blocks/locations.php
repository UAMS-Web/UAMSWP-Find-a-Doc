<?php
/*
 *
 * UAMS Find-a-Doc Locations Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
$id = '';
if ( empty( $id ) && isset($block) ) {
    $id = $block['id'];
} 
if ( empty ($id) ) {
    $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
}

$id = 'content-' . $id;  
    
$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}  

// Load values.
if ( empty($heading) )
    $heading = get_field('block_fad_locations_heading');
if ( empty($content_block) )
    $content_block = get_field('block_fad_locations_description');
if ( empty($background_color) )
    $background_color = get_field('block_fad_locations_background_color');

$filter_type = get_field('block_fad_locations_filter_type');
$filter_region = get_field('block_fad_locations_filter_region');

$tax_query = array('relation' => 'AND');
if (isset($filter_type))
{
    $tax_query[] =  array(
            'taxonomy' => 'location_type',
            'terms' => $filter_type
        );
}
if (isset($filter_region))
{
    $tax_query[] =  array(
            'taxonomy' => 'region',
            'terms' => $filter_region
        );
}

if($filter_type || $filter_region) {
    $args = (array(
        'post_type' => "location",
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'tax_query' => $tax_query,
    ));
    $location_query = new WP_Query( $args );

    if ( $location_query->have_posts() ) : ?>
        <section class="uams-module location-list alignfull <?php echo $background_color ? $background_color : 'bg-auto'; ?>" id="locations">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                    <h2 class="module-title"><span class="title"><?php echo $heading; ?></span></h2>
                        <div class="module-body text-center"><p><?php echo $content_block ? $content_block : ''; ?></p></div>
                        <div class="card-list-container location-card-list-container">
                            <div class="card-list">
                            <?php while ( $location_query->have_posts() ) : $location_query->the_post();
                                $id = get_the_ID(); 
                                include( UAMS_FAD_PATH . '/templates/loops/location-card.php' ); 
                            endwhile; 
                            wp_reset_postdata();?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php
    endif;
}