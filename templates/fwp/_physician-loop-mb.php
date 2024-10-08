<?php
	/**
	 *  Template Name: Physician Loop
	 *  Designed for physicians
	 */
?>
	<?php $i = 0; ?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php $class = ($i%2 == 0)? 'whiteBackground': 'grayBackground'; ?>
	<?php $full_name = rwmb_meta('physician_first_name') .' ' .(rwmb_meta('physician_middle_name') ? rwmb_meta('physician_middle_name') . ' ' : '') . rwmb_meta('physician_last_name') . (rwmb_meta('physician_pedigree', $id) ? '&nbsp;' . rwmb_meta('physician_pedigree', $id) : '') . (rwmb_meta('physician_degree') ? ', ' . rwmb_meta('physician_degree') : '');
	      //$profileurl = '/directory/physician/' . $post->post_name .'/';
	?>
	<div class="<?php echo $class; ?> archive-box">
	    <div class="row">
			<div class="col-md-12">
				<a href="<?php echo get_permalink($post->ID); ?>"><h2 class="margin-top-none margin-bottom-none" itemprop="name"><?php echo $full_name; ?></h2></a>
				<?php
					if(! empty( rwmb_meta('physician_clinical_title') ) || ! empty( rwmb_meta('physician_department') ) ){
						echo '<h4>';
						echo (rwmb_meta('physician_clinical_title') ? rwmb_meta('physician_clinical_title')->name : '');
						echo ((! empty( rwmb_meta('physician_clinical_title') )) && (! empty( rwmb_meta('physician_department') ) ) ? ', ' : '' );
						echo (rwmb_meta('physician_department') ? rwmb_meta('physician_department')->name : '');
						echo '</h4>';
					}
				?>
			</div>
		</div>
		<div class="row margin-top-half">
	        <div class="col-md-3 col-sm-4" class="margin-top-none margin-bottom-two">
	            <div class="margin-bottom-two text-center">
	            	<span><a href="<?php echo get_permalink($post->ID); ?>" target="_self"><?php the_post_thumbnail( 'medium',  array( 'itemprop' => 'image' ) ); ?></a></span>
	            </div>
				<?php
					if(rwmb_meta('physician_npi')) {

							$npi =  rwmb_meta( 'physician_npi' );
							// $request = wp_nrc_cached_api( $npi );

							$pg_rating_request = '';
							$pg_rating_data = '';
							$pg_rating_valid = '';
							if ( $npi ) {
								$pg_rating_request = wp_pg_cached_api( $npi, 0 );
								$pg_rating_data = json_decode( $pg_rating_request );
								if ( !empty( $pg_rating_data ) ) {
									$pg_rating_valid = (( $pg_rating_data->data->entities[0]->totalRatingCount) >= 30 );
								}
							}

							// if( ! empty( $pg_rating_data ) )


							// if( ! empty( $data ) ) {

							// 	$rating_valid = $data->valid;

								if ( $pg_rating_valid ){
									$avg_rating = $pg_rating_data->data->entities[0]->overallRating->value;
									$review_count = $pg_rating_data->data->entities[0]->totalRatingCount;
									$comment_count = $pg_rating_data->data->entities[0]->totalCommentCount;
 									echo '<div class="text-center">';
									echo '<div class="ds-title">Patient Rating</div>';
									echo '<div><span class="ds-stars ds-stars'. $avg_rating .'"></span></div>';
									echo '<div class="ds-xofy"><span class="ds-average">'. $avg_rating .'</span><span class="ds-average-max">out of 5</span></div>';
									echo '<div class="ds-ratings"><span class="ds-ratingcount">'. $review_count .'</span> Ratings</div>';
									echo '<div class="ds-comments"><span class="ds-commentcount">'. $comment_count .'</span> Comments</div>';
 									echo '</div>';
									//echo '<a href="' . esc_url( $rating->info->link ) . '">' . $product->info->title . '</a>';
								} else { ?>
									<div class="text-center">
									<div class="ds-title">Patient Rating</div>
									<div><span class="ds-stars ds-stars0"></span></div>
									<div class="small bold">No Patient Satisfaction Reviews</div>
									<div><a href="#" class="js-modal" data-modal-close-text="Close" data-modal-close-title="Close this window" data-modal-content-id="why_not_modal" data-modal-title="Why Not?" aria-label="Learn why ratings are not available for this provider"><span aria-hidden="true">Why not?</span></a></div>
									</div>
								<?php
								}
							// }
						} else { ?>
							<div class="text-center">
							<div class="ds-title">Patient Rating</div>
							<div><span class="ds-stars ds-stars0"></span></div>
							<div class="small bold">No Patient Satisfaction Reviews</div>
							<div><a href="#why_not_modal" aria-label="Learn why ratings are not available for this provider" data-lity><span aria-hidden="true">Why not?</span></a></div>
							</div>
						<?php }
					?>
	        </div>
	        <div class="col-md-9 col-sm-8" class="margin-top-none margin-bottom-none">
	                <div class="row" class="margin-top-none margin-bottom-none">
	                    <div class="col-md-6">

	                        <p><?php echo ( rwmb_meta('physician_short_clinical_bio') ? rwmb_meta( 'physician_short_clinical_bio') : wp_trim_words( rwmb_meta( 'physician_clinical_bio' ), 30, ' &hellip;' ) ); ?></p>

	                         <a class="uams-btn btn-blue btn-sm" target="_self" title="View Profile" href="<?php echo get_permalink($post->ID); ?>">View Profile</a>
							<?php if(rwmb_meta('physician_youtube_link')) { ?>
								<a class="uams-btn btn-red btn-play btn-sm" target="_self" title="View Physician Video" href="<?php echo rwmb_meta('physician_youtube_link'); ?>&rel=0" data-lity>View Video</a>
							<?php } ?>
	                    </div>
	                    <div class="col-md-6">

	                        <?php // load all 'specialties' terms for the post
							$specialties = rwmb_meta('medical_specialties');

								// we will use the first term to load ACF data from
								if( $specialties ): ?>
								<h3>Specialties</h3>
								<ul>
									<?php foreach( $specialties as $specialty ): ?>
										<li>
											<a href="<?php echo get_term_link( $specialty ); ?>">
											<?php $specialty_name = get_term( $specialty, 'specialty');
												echo $specialty_name->name;
											?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php

							$locations = new WP_Query( array(
							    'relationship' => array(
							        'id'   => 'physicians_to_locations',
							        'from' => get_the_ID(), // You can pass object ID or full object
							    ),
							    'nopaging'     => true,
							) );

							?>
							<?php if( $locations ): ?>
							<h3 data-fontsize="16" data-lineheight="24"><i class="fa fa-medkit"></i> Clinic(s)</h3>
								<ul>
								<?php while ( $locations->have_posts() ) : $locations->the_post(); ?>
									<li>
										<a href="<?php echo get_permalink( ); ?>">
											<?php echo get_the_title( ); ?>
										</a>
									</li>
								<?php endwhile;
									wp_reset_postdata(); ?>
								</ul>
							<?php endif; ?>

	                    </div><!-- .col-6 -->
	                </div><!-- .row -->

	        </div><!-- .col-9 -->
	    </div><!-- .row -->
	</div><!-- .color -->
	<?php $i++; ?>
	<?php endwhile; ?>
		<link rel="stylesheet" type="text/css" href="https://www.docscores.com/resources/css/docscores-lotw.v1330-2018121714.css" />

<!-- 	<script src="https://www.docscores.com/widget/v2/uams/npi/lotw.js" async></script> -->

<!--
	<script>
		(function ($, window) {

		var intervals = {};
		var removeListener = function(selector) {

			if (intervals[selector]) {

				window.clearInterval(intervals[selector]);
				intervals[selector] = null;
			}
		};
		var found = 'waitUntilExists.found';

		/**
		 * @function
		 * @property {object} jQuery plugin which runs handler function once specified
		 *           element is inserted into the DOM
		 * @param {function|string} handler
		 *            A function to execute at the time when the element is inserted or
		 *            string "remove" to remove the listener from the given selector
		 * @param {bool} shouldRunHandlerOnce
		 *            Optional: if true, handler is unbound after its first invocation
		 * @example jQuery(selector).waitUntilExists(function);
		 */

		$.fn.waitUntilExists = function(handler, shouldRunHandlerOnce, isChild) {

			var selector = this.selector;
			var $this = $(selector);
			var $elements = $this.not(function() { return $(this).data(found); });

			if (handler === 'remove') {

				// Hijack and remove interval immediately if the code requests
				removeListener(selector);
			}
			else {

				// Run the handler on all found elements and mark as found
				$elements.each(handler).data(found, true);

				if (shouldRunHandlerOnce && $this.length) {

					// Element was found, implying the handler already ran for all
					// matched elements
					removeListener(selector);
				}
				else if (!isChild) {

					// If this is a recurring search or if the target has not yet been
					// found, create an interval to continue searching for the target
					intervals[selector] = window.setInterval(function () {

						$this.waitUntilExists(handler, shouldRunHandlerOnce, true);
					}, 500);
				}
			}

			return $this;
		};

		}(jQuery, window));
	(function($) {
		$(document).ready(function(){
			$('.ds-average').waitUntilExists( function(){

				$('.ds-average').attr('itemprop', 'ratingValue');
				$('.ds-ratingcount').attr('itemprop', 'ratingCount');
				$('.ds-summary').attr('itemtype', 'http://schema.org/AggregateRating');
				$('.ds-summary').attr('itemprop', 'aggregateRating');
				//$('.ds-comments').wrapInner('<a href="#PatientRatings"></a>');
			});
		});
	})(jQuery);
	</script>
-->
	<?php else : ?>
	<p><?php _e( 'Sorry, no physicians matched your criteria.' ); ?></p>
	<?php endif; ?>