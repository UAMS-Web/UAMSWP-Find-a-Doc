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

// Bring in variables from outside of the function
global $location_single_name; // Defined in uamswp_fad_labels_location()
global $location_single_name_attr; // Defined in uamswp_fad_labels_location()
global $location_plural_name; // Defined in uamswp_fad_labels_location()

if ( have_posts() ) : while ( have_posts() ) : the_post();

$id = get_the_ID();
include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );

endwhile; else : ?>
	<p><?php _e( 'Sorry, no ' . strtolower($location_plural_name) . ' matched your criteria.' ); ?></p>
<?php endif; ?>