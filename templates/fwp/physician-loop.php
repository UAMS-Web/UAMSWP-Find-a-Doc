<?php
/**
 * Template Name: Providers Archive Page Loop for FacetWP Listing
 *
 * Description: A template part that builds a while loop to display a list of
 * provider cards on a provider archive page.
 *
 * Designed for UAMS Health Find-a-Doc
 *
 * FacetWP Listing Name and Slug:
 * 	Physician	('physician')
 */

if ( have_posts() ) {

	while ( have_posts() ) {

		the_post();

		include( UAMS_FAD_PATH . '/templates/parts/html/cards/provider_archive.php' );

	} // endwhile

	?>
	<link rel="stylesheet" type="text/css" href="https://www.docscores.com/resources/css/docscores-lotw.v1330-2018121714.css" />
	<?php

} else {

	?>
	<p><?php _e( 'Sorry, no ' . strtolower($provider_plural_name) . ' matched your criteria.' ); ?></p>
	<?php

} // endif

?>
