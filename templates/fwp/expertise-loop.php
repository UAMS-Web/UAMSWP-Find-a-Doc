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

// Check/define variables

	if (
		!isset($expertise_plural_name) || empty($expertise_plural_name)
	) {
		$labels_expertise_vars = uamswp_fad_labels_expertise();
			$expertise_plural_name = $labels_expertise_vars['expertise_plural_name']; // string
	}

if ( have_posts() ) {

	while ( have_posts() ) {

		the_post();
		include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );

	} // endwhile

} else {

	?>
	<p><?php _e( 'Sorry, no ' . strtolower($expertise_plural_name) . ' matched your criteria.' ); ?></p>
	<?php

} // endif ?>