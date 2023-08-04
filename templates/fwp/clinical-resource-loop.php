<?php 
/**
 * Template Name: Clinical Resources Archive Page Loop for FacetWP Listing
 * 
 * Description: A template part that builds a while loop to display a list of 
 * clinical resource cards.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * FacetWP Listing Name and Slug:
 * 	Clinical Resources	('clinical_resources')
 */

// Get system settings for clinical resource labels
include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

if ( have_posts() ) : while ( have_posts() ) : the_post();

include( UAMS_FAD_PATH . '/templates/loops/resource-card-archive.php' );

endwhile; else : ?>
	<p><?php _e( 'Sorry, no ' . strtolower($clinical_resource_plural_name) . ' matched your criteria.' ); ?></p>
<?php endif; ?>