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

// Check/define variables

	if ( !isset($id) ) {
		$id = get_the_ID();
	}
	if (
		!isset($provider_single_name)
		||
		!isset($provider_plural_name)
	) {
		$labels_provider_vars = uamswp_fad_labels_provider();
			$provider_single_name = $labels_provider_vars['provider_single_name']; // string
			$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string
	}
	if (
		!isset($location_single_name)
		||
		!isset($location_plural_name)
	) {
		$labels_location_vars = uamswp_fad_labels_location();
			$location_single_name = $labels_location_vars['location_single_name']; // string
			$location_plural_name = $labels_location_vars['location_plural_name']; // string
	}
	if (
		!isset($expertise_single_name)
		||
		!isset($expertise_plural_name)
	) {
		$labels_expertise_vars = uamswp_fad_labels_expertise();
			$expertise_single_name = $labels_expertise_vars['expertise_single_name']; // string
			$expertise_plural_name = $labels_expertise_vars['expertise_plural_name']; // string
	}
	if ( !isset($clinical_resource_single_name) ) {
		$labels_clinical_resource_vars = uamswp_fad_labels_clinical_resource();
			$clinical_resource_single_name = $labels_clinical_resource_vars['clinical_resource_single_name']; // string
	}
	if (
		!isset($condition_single_name)
		||
		!isset($condition_plural_name)
	) {
		$labels_condition_vars = uamswp_fad_labels_condition();
			$condition_single_name = $labels_condition_vars['condition_single_name']; // string
			$condition_plural_name = $labels_condition_vars['condition_plural_name']; // string
	}
	if (
		!isset($treatment_single_name)
		||
		!isset($treatment_plural_name)
	) {
		$labels_treatment_vars = uamswp_fad_labels_treatment();
			$treatment_single_name = $labels_treatment_vars['treatment_single_name']; // string
			$treatment_plural_name = $labels_treatment_vars['treatment_plural_name']; // string
	}

if ( have_posts() ) : while ( have_posts() ) : the_post();

$id = get_the_ID();
$resource_page = 'archive';

include( UAMS_FAD_PATH . '/templates/loops/resource-card-archive.php' );

endwhile; else : ?>
	<p><?php _e( 'Sorry, no ' . strtolower($clinical_resource_plural_name) . ' matched your criteria.' ); ?></p>
<?php endif; ?>