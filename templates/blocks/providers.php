<?php
/*
 *
 * UAMS Find-a-Doc Providers Block
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

$id = 'providers-' . $id;  
    
$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}  

// Load values.
if ( empty($heading) )
    $heading = get_field('block_fad_providers_heading');
if ( empty($content_block) )
    $content_block = get_field('block_fad_providers_description');
if ( empty($background_color) )
    $background_color = get_field('block_fad_providers_background_color');
if ( empty($count) )
    $count = get_field('block_fad_providers_count');

// Set up Vars to hold Post Ids
$post_ids = array();
$location_ids = array();
$aoe_ids = array();
// Grab ACF values
$filter_id = get_field('block_fad_providers_filter_ids') ?: array();
$filter_aoe = get_field('block_fad_providers_filter_aoe') ?: array();
$filter_region = get_field('block_fad_providers_filter_region');
$filter_location = get_field('block_fad_providers_filter_location');

// Build Taxonomy Query, if region is set
$tax_query = array();
if (!empty($filter_region))
{
    $tax_query[] =  array(
        'taxonomy' => 'region',
        'terms' => $filter_region
    );
}
// Use custom table function
if (!empty($filter_location))
{   
    $location_ids = uamswp_custom_table_query('uamswp_physicians', 'physician_locations', $filter_location);
}
if (!empty($filter_aoe))
{   
    $aoe_ids = uamswp_custom_table_query('uamswp_physicians', 'physician_expertise', $filter_aoe);
}

// Build $post_ids from $location_ids and $aoe_ids
if (!empty($location_ids) && !empty($aoe_ids)){
    $post_ids = array_intersect($location_ids, $aoe_ids);
} else {
    $post_ids = array_merge($location_ids, $aoe_ids);
}
    
// If values are set
if($filter_id || $filter_region || $filter_location || $filter_aoe) {
    // If region or location 
    if ($filter_region || $filter_location || $filter_aoe) {
        // Build Query to get Ids from filters
        $args = (array(
            'post_type' => "provider",
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post__in' => $post_ids,
            'tax_query' => $tax_query,
            'fields' => 'ids',
        ));
        $filtered_query = new WP_Query( $args ); 
        $filter_id = array_unique( array_merge( $filter_id,  $filtered_query->posts ) );
    }
    // Build Main Query from Post Ids (Filtered IDs + Specific IDs)
    $args = (array(
        'post_type' => "provider",
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post__in' => $filter_id
    ));
    $provider_query = new WP_Query( $args ); 

    // echo '<pre>'; print_r($SQL); echo '</pre>';
    // echo '<pre>'; print_r($post_ids); echo '</pre>';
    // echo '<pre>'; print_r($args); echo '</pre>';
    
    if ( $provider_query->have_posts() ) : ?>
        <section class="uams-module provider-list  alignfull <?php echo $background_color ? $background_color : 'bg-auto'; ?>" id="<?php echo $id; ?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title"><?php echo $heading; ?></span></h2>
                        <div class="module-body text-center"><p><?php echo $content_block ? $content_block : ''; ?></p></div>
                        <div class="card-list-container">
                            <div class="card-list card-list-doctors card-list-doctors-count-<?php echo $count; ?>">
                                <div class="card-list">
                                <?php while ( $provider_query->have_posts() ) : $provider_query->the_post();
                                    $id = get_the_ID(); 
                                    include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' ); 
                                endwhile; 
                                wp_reset_postdata();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php
    endif;
}