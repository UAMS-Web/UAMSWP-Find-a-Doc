<?php
/*
 * Template Name: Archive Template Cards Layout BODY Elements
 *
 * Description: A template part that constructs the BODY elements common to all
 * archive pages using the cards layout
 */

// Add page template class to body element's classes

	// add_filter( 'body_class', function( $classes ) use ( $template_type ) {
	//
	// 	// Add page template class to body class array
	//
	// 		if ( $template_type ) {
	//
	// 			$classes[] = 'page-template-' . $template_type;
	//
	// 		}
	//
	// 	return $classes;
	//
	// } );

add_filter( 'facetwp_template_use_archive', '__return_true' );

?>
<div class="content-sidebar-wrap row">
	<?php

	// Construct the entry title

	$entry_title_text = ${ $variable_slug . '_archive_headline' };
	$entry_title_text_body = ${ $variable_slug . '_archive_intro_text' };
	include( UAMS_FAD_PATH . '/templates/parts/html/entry-title/graphic.php');

	?>
	<main class="col-12" id="genesis-content">
		<section class="uams-module" id="<?php echo $archive_construct_args['cards-layout']['id']; ?>">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="module-title sr-only">List of <?php echo $archive_construct_args['cards-layout']['plural-name']; ?></h2>
						<div class="card-list-container">
							<div class="card-list card-list-<?php echo $archive_construct_args['cards-layout']['class']; ?>">
								<?php echo facetwp_display( 'template', $archive_construct_args['cards-layout']['fwp-template'] ); ?>
								<?php //get_template_part( 'templates/physician-loop' ); ?>
							</div>
						</div>
						<div class="row list-pagination">
							<div class="col">
								<?php echo facetwp_display( 'pager' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php

			// FacetWP Hide elements
			// Set # value depending on element

			?>
			<script>
				(function($) {
					$(document).on('facetwp-loaded', function() {
						if (<?php echo $archive_construct_args['cards-layout']['fwp-total-rows']; ?> >= FWP.settings.pager.total_rows ) {
							$('.list-pagination').hide()
						}
						if (FWP.loaded) {
							$('html, body').animate({
								scrollTop: $('main').offset().top
							}, 500);
						}
					});
				})(jQuery);
			</script>
		</section>
	</main>
</div>
<?php

get_footer();

?>