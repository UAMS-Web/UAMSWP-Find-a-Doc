<?php 
/**
 * Template Name: Clinical Resources Archive Page Loop for FacetWP "Clinical Resources" Template
 * Template Slug: clinical_resources
 */

// Bring in variables from outside of the function
global $provider_single_name; // Defined in uamswp_fad_labels_provider()
global $provider_plural_name; // Defined in uamswp_fad_labels_provider()
global $location_single_name; // Defined in uamswp_fad_labels_location()
global $location_plural_name; // Defined in uamswp_fad_labels_location()
global $expertise_single_name; // Defined in uamswp_fad_labels_expertise()
global $expertise_plural_name; // Defined in uamswp_fad_labels_expertise()
global $clinical_resource_single_name; // Defined in uamswp_fad_labels_clinical_resource()
global $clinical_resource_plural_name; // Defined in uamswp_fad_labels_clinical_resource()
global $conditions_single_name; // Defined in uamswp_fad_labels_conditions()
global $conditions_plural_name; // Defined in uamswp_fad_labels_conditions()
global $treatments_single_name; // Defined in uamswp_fad_labels_treatments()
global $treatments_plural_name; // Defined in uamswp_fad_labels_treatments()

if ( have_posts() ) : while ( have_posts() ) : the_post();

$id = get_the_ID();
$resource_page = 'archive';

include( UAMS_FAD_PATH . '/templates/loops/resource-card.php' );

endwhile; else : ?>
	<p><?php _e( 'Sorry, no ' . strtolower($clinical_resource_plural_name) . ' matched your criteria.' ); ?></p>
<?php endif; ?>