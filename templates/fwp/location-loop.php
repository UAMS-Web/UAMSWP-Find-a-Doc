<?php 
/**
 * Required vars:
 * 	$location_single_name // System setting for Locations Plural Item Name
 * 	$location_single_name_attr // Attribute value friendly version of system setting for Locations single item name
 * 	$location_plural_name // System setting for Locations plural item name
 */

if ( have_posts() ) : while ( have_posts() ) : the_post();

$id = get_the_ID();
include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );

endwhile; else : ?>
	<p><?php _e( 'Sorry, no ' . strtolower($location_plural_name) . ' matched your criteria.' ); ?></p>
<?php endif; ?>