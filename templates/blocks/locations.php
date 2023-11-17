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

$id = 'locations-' . $id;  
    
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
if ( empty($more) )
    $more = get_field('block_fad_locations_more');
if ( $more ) {
    if ( empty($more_text) )
        $more_text = get_field('block_fad_locations_more_text');
    if ( empty($more_button_text) )
        $more_button_text = get_field('block_fad_locations_more_button_text');
    if ( empty($more_button_url) )
        $more_button_url = get_field('block_fad_locations_more_button_url');
    if ( empty($more_button_target) ) 
        $more_button_target = $more_button_url['target'];
    if ( empty($more_button_description) )
        $more_button_description = get_field('block_fad_locations_more_button_description');
        $more_button_description_attr = $more_button_description;
        $more_button_description_attr = str_replace('"', '\'', $more_button_description_attr); // Replace double quotes with single quote
        $more_button_description_attr = str_replace('&#8217;', '\'', $more_button_description_attr); // Replace right single quote with single quote
        $more_button_description_attr = htmlentities($more_button_description_attr, null, 'UTF-8'); // Convert all applicable characters to HTML entities
        $more_button_description_attr = str_replace('&nbsp;', ' ', $more_button_description_attr); // Convert non-breaking space with normal space
        $more_button_description_attr = html_entity_decode($more_button_description_attr); // Convert HTML entities to their corresponding characters
    if ( empty($more_button_color) && ( $background_color == 'bg-white' || $background_color == 'bg-gray' ) ) {
        $more_button_color = 'primary';
    } else {
        $more_button_color = 'white';
    }
}

$filter_ids = get_field('block_fad_locations_filter_ids') ?: array();
$filter_type = get_field('block_fad_locations_filter_type');
$filter_region = get_field('block_fad_locations_filter_region');
$filter_aoe = get_field('block_fad_locations_filter_aoe') ?: array();

$post_ids = array();
if (!empty($filter_aoe))
{   
    $aoe_ids = uamswp_custom_table_query('uamswp_locations', 'location_expertise', $filter_aoe);
}
if (!empty($aoe_ids)){
    $post_ids = $aoe_ids;
}

$tax_query = array();
if (!empty($filter_region) && !empty($filter_type))
{
	$tax_query[] = array('relation' => 'AND');
}
if (!empty($filter_type))
{
    $tax_query[] =  array(
            'taxonomy' => 'location_type',
            'terms' => $filter_type
        );
}
if (!empty($filter_region))
{
    $tax_query[] =  array(
            'taxonomy' => 'region',
            'terms' => $filter_region
        );
}

if($filter_type || $filter_region || $filter_aoe || $filter_ids) {
    if ($filter_type || $filter_region || $filter_aoe) {
        // Build Query to get Ids from filters
        $args = (array(
            'post_type' => "location",
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post__in' => $post_ids,
            'tax_query' => $tax_query,
            'fields' => 'ids',
        ));
        $filtered_query = new WP_Query( $args ); 
        $post_ids = array_unique( array_merge( $filter_ids,  $filtered_query->posts ) );
    } else {
        $post_ids = $filter_ids;
    }

    // Build Main Query from Post Ids (Filtered IDs + Specific IDs)
    $args = (array(
        'post_type' => "location",
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post__in' => $post_ids,
    ));
    $location_query = new WP_Query( $args );

    if ( $location_query->have_posts() ) : ?>
        <section class="uams-module location-list alignfull <?php echo $background_color ? $background_color : 'bg-auto'; ?>" id="<?php echo $id; ?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title"><?php echo $heading; ?></span></h2>
                        <?php echo $content_block ? '<div class="module-description"><p>' . $content_block . '</p></div>' : ''; ?>
                        <div class="card-list-container location-card-list-container">
                            <div class="card-list">
                            <?php while ( $location_query->have_posts() ) : $location_query->the_post();
                                $id = get_the_ID(); 
                                include( UAMS_FAD_PATH . '/templates/loops/location-card.php' ); 
                            endwhile; 
                            wp_reset_postdata();?>
                        </div>
                        <?php if ( $more ) { ?>
                            <div class="more">
                                <p class="lead"><?php echo $more_text; ?></p>
                                <div class="cta-container">
                                    <a href="<?php echo $more_button_url['url']; ?>" class="btn btn-outline-<?php echo $more_button_color; ?>" aria-label="<?php echo $more_button_description_attr; ?>"<?php $more_button_target ? ' target="'. $more_button_target . '"' : '' ?>><?php echo $more_button_text; ?></a>
                                </div>
                            </div>
                        <?php } // endif ?>
                    </div>
                </div>
            </div>
        </section>
    <?php
    endif;
}