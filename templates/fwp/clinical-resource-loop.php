<?php 
if ( have_posts() ) : while ( have_posts() ) : the_post();

$id = get_the_ID();
$resource_page = 'archive';
include( UAMS_FAD_PATH . '/templates/loops/resource-card.php' );

endwhile; else : ?>
	<p><?php _e( 'Sorry, no resources matched your criteria.' ); ?></p>
<?php endif; ?>