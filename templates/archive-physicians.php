<?php get_header();

	function custom_field_excerpt($title) {
			global $post;
			$text = get_field($title);
			if ( '' != $text ) {
				$text = strip_shortcodes( $text );
				$text = apply_filters('the_content', $text);
				$text = str_replace(']]>', ']]>', $text);
				$excerpt_length = 35; // 35 words
				$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
				$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
			}
			return apply_filters('the_excerpt', $text);
		}
	function wpdocs_custom_excerpt_length( $length ) {
	    return 35; // 35 words
	}
	add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

	add_filter( 'facetwp_template_use_archive', '__return_true' );

?>
				<main class="container-fluid doctor-list">
					<div class="row">
						<div class="col-12 col-sm filter-col collapse">
							<h2>Filters</h2>
							<button type="button" class="close" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<fieldset>
								<legend class="sr-only">Filter by...</legend>
								<h3>Search Doctors, Specialty or Condition</h3>
								<?php  echo facetwp_display( 'facet', 'alpha' ); ?>
								<?php // echo do_shortcode( '[wpdreams_ajaxsearchpro id=1]' ); ?>
								<?php  echo do_shortcode( '		<div class="fwp-filter">[facetwp facet="primary_care"]</div>
																<div class="fwp-filter">[facetwp facet="conditions"]</div>
																<div class="fwp-filter">[facetwp facet="patient_types"]</div>
																<div class="fwp-filter">[facetwp facet="physician_gender"]</div>
																<div class="fwp-filter">[facetwp facet="physician_language"]</div>
																<div class="fwp-filter">[facetwp facet="locations"]</div>
																<div class="fwp-filter sr-only">[facetwp facet="searchable"]</div>
																<button onclick="FWP.refresh()">Apply</button> <button onclick="FWP.reset()">Reset</button>
															' );
								?>
							</fieldset>
		        		</div>
						<div class="col-12 col-sm list-col">
							<h2 class="sr-only">List of Doctors</h2>
							<div class="row list-col-header">
								<div class="col result-count"><?php echo facetwp_display( 'counts' ); ?> Doctors</div>
								<div class="col filter-toggle-container">
									<!-- When button is active, add "active" class. -->
									<button title="Toggle Filter Tray" class="filter-toggle"><span class="sr-only">Toggle Filter Tray</span><span class="fas fa-filter"></span></button>
									
								</div>
								<div class="col sort-select">
								<?php echo facetwp_display( 'sort' ); ?>
								</div>
							</div>
							<!-- <div class="row list"> -->
								<?php echo facetwp_display( 'template', 'physician' ); ?>
						<!-- <div class="facetwp-template">
						<?php //include( plugin_dir_path( __DIR__ ) . 'templates/fwp/physician-loop.php' ); ?>
						</div> -->
						<?php // echo facetwp_display( 'per_page' ); ?>
						<?php //echo do_shortcode('[facetwp load_more="true" label="Load more"]'); ?>
							<!-- </div> -->
							<div class="row list-pagination">
								<div class="col">
									<?php echo facetwp_display( 'pager' ); ?>
								</div>
							</div>
						<div id="why_not_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="why_not_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="WhyNotTitle">Why Not?</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<p>There is no publicly available rating for this medical professional for one of two reasons: 1) he or she does not see patients or 2) he or she sees patients but has not yet received the minimum number of Patient Satisfaction Reviews. To be eligible for display, we require a minimum of 30 surveys. This ensures that the rating is statistically reliable and a true reflection of patient satisfaction.</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
					</div>
        		</div>
    		</main>

    	<!--</div><!-- uams-content -->
    <!-- <div id="sidebar"></div>

  </div>

</div> -->

<?php get_footer(); ?>
