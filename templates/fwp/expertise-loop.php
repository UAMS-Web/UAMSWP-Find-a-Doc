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

// Get system settings for area of expertise labels
include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

if ( have_posts() ) {

	while ( have_posts() ) {

		the_post();
		include( UAMS_FAD_PATH . '/templates/parts/html/cards/expertise.php' );

	} // endwhile

} else {

	?>
	<p><?php _e( 'Sorry, no ' . strtolower($expertise_plural_name) . ' matched your criteria.' ); ?></p>
	<?php

} // endif ?>