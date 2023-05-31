<?php 
if ( have_posts() ) : while ( have_posts() ) : the_post();

$id = get_the_ID();
include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );

endwhile; else : ?>
	<p><?php _e( 'Sorry, no ' . strtolower($location_plural_name) . ' matched your criteria.' ); ?></p>
<?php endif; ?>