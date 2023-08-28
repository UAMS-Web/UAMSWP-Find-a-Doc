<?php
/*
 * Template Name: Archive Template Filters Layout BODY Elements
 * 
 * Description: A template part that constructs the BODY elements common to all 
 * archive pages using the filters layout
 */

add_filter( 'facetwp_template_use_archive', '__return_true' );

?>
<div class="content-sidebar-wrap">
	<main class="container-fluid <?php echo $archive_construct_args['filters-layout']['class']; ?>-list" id="genesis-content">
		<h1 class="sr-only" itemprop="headline"><?php echo $page_title; ?></h1>
		<div class="row">
			<div class="col-12 col-sm filter-col collapse">
				<button type="button" class="close" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h2 class="h4">Filters</h2>
				<fieldset>
					<legend class="sr-only">Filter by...</legend>
					<?php

					if ( $archive_construct_args['filters-layout']['ajaxsearchpro-id'] ) {

						?><h3 class="h6">Search <?php echo $archive_construct_args['filters-layout']['plural-name']; ?></h3>
						<?php

						echo do_shortcode( '[wpdreams_ajaxsearchpro id=' . $archive_construct_args['filters-layout']['ajaxsearchpro-id'] . ']' );

					}

					// When adding facets, make sure relevant uamswp_fad_labels_*() function is also added to template

					foreach ( $archive_construct_args['filters-layout']['fwp-filters'] as $filter ) {

						?>
						<div class="fwp-filter"><?php echo facetwp_display( 'facet', $filter ); ?></div>
						<?php

					} // endforeach

					?>
					<button class="btn btn-outline-primary" id="filter-reset" onclick="FWP.reset()">Reset</button>
				</fieldset>
			</div>
			<div class="col-12 col-sm list-col">
				<h2 class="sr-only">List of <?php echo $archive_construct_args['filters-layout']['plural-name']; ?></h2>
				<?php

				if ( $archive_construct_args['filters-layout']['911-disclaimer'] ) {

					?><div class="alert alert-danger text-center" role="alert">
						If you think you are experiencing a medical emergency, call 911 immediately.
					</div><?php

				}

				?>
				<div class="row list-col-header">
					<div class="col result-status">
						<span class="result-count"><?php echo facetwp_display( 'counts' ); ?> <?php echo $archive_construct_args['filters-layout']['plural-name']; ?></span>
						<?php echo facetwp_display( 'selections' ); ?>
					</div>
					<div class="col filter-toggle-container">
						<!-- When button is active, add "active" class. -->
						<button title="Toggle Filter Tray" class="filter-toggle"><span class="sr-only">Toggle Filter Tray</span><span class="fas fa-filter"></span></button>
					</div>
					<div class="col sort-select">
						<?php

						echo facetwp_display( 'sort' );

						?>
					</div>
				</div>
				<?php

				echo facetwp_display( 'template', $archive_construct_args['filters-layout']['fwp-template'] );

				?>
				<div class="row list-pagination">
					<div class="col">
						<?php

						echo facetwp_display( 'pager' );

						?>
					</div>
				</div>
				<script>
					(function($) {
						$(document).on('facetwp-loaded', function() {
							if (<?php echo $archive_construct_args['filters-layout']['fwp-total-rows']; ?> >= FWP.settings.pager.total_rows ) {
								$('.list-pagination').hide()
							}
						});
					})(jQuery);
				</script>
				<?php

				if ( $archive_construct_args['filters-layout']['provider-ratings-modal'] ) {

					?>
					<div id="why_not_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="why-not-title" aria-modal="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="why-not-title">Why Are There No Ratings?</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<p>There is no publicly available rating for this <?php echo strtolower($provider_single_name); ?> for one of three reasons:</p>
									<ul>
										<li>The <?php strtolower($provider_single_name); ?> does not see patients</li>
										<li>The <?php strtolower($provider_single_name); ?> sees patients but has not yet received the minimum number of Patient Satisfaction Reviews. To be eligible for display, we require a minimum of 30 surveys. This ensures that the rating is statistically reliable and a true reflection of patient satisfaction.</li>
										<li>The <?php strtolower($provider_single_name); ?> is a resident physician.</li>
									</ul>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
					<?php

				} // endif ( $archive_construct_args['filters-layout']['provider-ratings-modal'] )

				?>
			</div>
		</div>
	</main>
</div>
<?php

get_footer();

?>