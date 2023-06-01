<?php 
/**
 * Required var:
 * 	$provider_single_name // System setting for Providers single item name
 * 	$provider_plural_name // System setting for Providers plural item name
 * 	$location_single_name // System setting for Locations single item name
 * 	$location_plural_name // System setting for Locations plural item name
 * 	$expertise_single_name // System setting for Areas of Expertise single item name
 * 	$expertise_plural_name // System setting for Areas of Expertise plural item name
 * 	$clinical_resource_single_name // System setting for Clinical Resources single item name
 * 	$clinical_resource_plural_name // System setting for Clinical Resources plural item name
 * 	$conditions_single_name // System setting for Conditions single item name
 * 	$conditions_plural_name // System setting for Conditions plural item name
 * 	$treatments_single_name // System setting for Treatments single item name
 * 	$treatments_plural_name // System setting for Treatments plural item name
 */

if ( have_posts() ) : while ( have_posts() ) : the_post();

$id = get_the_ID();
$resource_page = 'archive';
include( UAMS_FAD_PATH . '/templates/loops/resource-card.php' );

endwhile; else : ?>
	<p><?php _e( 'Sorry, no ' . strtolower($clinical_resource_plural_name) . ' matched your criteria.' ); ?></p>
<?php endif; ?>