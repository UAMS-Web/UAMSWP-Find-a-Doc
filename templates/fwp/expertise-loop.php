<?php
/**
 * Template Name: Areas of Expertise Archive Page Loop for FacetWP Listing
 * 
 * Description: A template part that builds a while loop to display a list of 
 * area of expertise cards.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * FacetWP Listing Name and Slug:
 * 	Areas of Expertise	('expertise')
 */

// Bring in variables from outside of the function
global $expertise_single_name; // Defined in uamswp_fad_labels_expertise()
global $expertise_single_name_attr; // Defined in uamswp_fad_labels_expertise()
global $expertise_plural_name; // Defined in uamswp_fad_labels_expertise()

if ( have_posts() ) : while ( have_posts() ) : the_post();

$page_id = get_the_ID();
include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );

endwhile; else : ?>
	<p><?php _e( 'Sorry, no ' . strtolower($expertise_plural_name) . ' matched your criteria.' ); ?></p>
<?php endif; ?>