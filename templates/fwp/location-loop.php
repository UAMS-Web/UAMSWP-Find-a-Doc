<?php 
/**
 * Template Name: Locations Archive Page Loop for FacetWP Listings
 * 
 * Description: A template part that builds a while loop to display a list of 
 * location cards on a location archive page.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * FacetWP Listing Name and Slug:
 * 	Locations					('locations')
 * 	Condition - Locations		('condition_locations')
 * 	Baptist Health Cancer Care	('baptist_health_cancer_care')
 */

// Check/define variables

	// Find-a-Doc Settings values for location labels

		if (
			!isset($location_plural_name) || empty($location_plural_name)
		) {
			$labels_location_vars = isset($labels_location_vars) ? $labels_location_vars : uamswp_fad_labels_location();
				$location_plural_name = $labels_location_vars['location_plural_name']; // string
		}

if ( have_posts() ) {

	while ( have_posts() ) {

		the_post();
		$page_id = get_the_ID();
		include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );

	} // endwhile

} else {

		?>
		<p><?php _e( 'Sorry, no ' . strtolower($location_plural_name) . ' matched your criteria.' ); ?></p>
		<?php

} // endif

?>