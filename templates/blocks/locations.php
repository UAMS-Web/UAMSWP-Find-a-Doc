<?php
/*
 * Template Name: UAMS Health Find-a-Doc Locations ACF Block
 * 
 * Description: A template part that displays a list of locations as part of an 
 * Advanced Custom Fields block.
 * 
 * The Query is built using the inputs from the ACF block.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	$location_single_name // System setting for Locations Plural Item Name
 * 	$location_single_name_attr // Attribute value friendly version of system setting for Locations single item name
 */

// Create id attribute allowing for custom "anchor" value.
$block_id = '';
if ( empty( $block_id ) && isset($block) ) {
	$block_id = $block['id'];
}
if ( empty ($block_id) ) {
	$block_id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
}

$section_id = 'locations-' . $block_id;

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
		$more_button_description_attr = uamswp_attr_conversion($more_button_description);
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
	$tax_query[] = array(
			'taxonomy' => 'location_type',
			'terms' => $filter_type
		);
}
if (!empty($filter_region))
{
	$tax_query[] = array(
			'taxonomy' => 'region',
			'terms' => $filter_region
		);
}

if($filter_type || $filter_region || $filter_aoe || $filter_ids) {
	if ($filter_type || $filter_region || $filter_aoe) {
		// Build Query to get Ids from filters
		$args = (array(
			'post_type' => 'location',
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post__in' => $post_ids,
			'tax_query' => $tax_query,
			'fields' => 'ids',
		));
		$filtered_query = new WP_Query( $args );
		$post_ids = array_unique( array_merge( $filter_ids, $filtered_query->posts ) );
	} else {
		$post_ids = $filter_ids;
	}

	// Build Main Query from Post Ids (Filtered IDs + Specific IDs)
	$args = (array(
		'post_type' => 'location',
		'order' => 'ASC',
		'orderby' => 'title',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'post__in' => $post_ids,
	));
	$location_query = new WP_Query( $args );

	if ( $location_query->have_posts() ) : ?>
		<section class="uams-module location-list alignfull <?php echo $background_color ? $background_color : 'bg-auto'; ?>" id="<?php echo $section_id; ?>">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title"><span class="title"><?php echo $heading; ?></span></h2>
						<?php echo $content_block ? '<div class="module-description"><p>' . $content_block . '</p></div>' : ''; ?>
						<div class="card-list-container location-card-list-container">
							<div class="card-list">
							<?php while ( $location_query->have_posts() ) : $location_query->the_post();
								$page_id = get_the_ID();
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