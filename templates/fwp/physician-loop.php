<?php
	/**
	 *  Template Name: Physician Loop
	 *  Designed for physicians
	 */
?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php 
	$degrees = get_field('physician_degree');
	$degree_list = '';
	$i = 1;
	if ($degrees){
		foreach( $degrees as $degree ):
			$degree_name = get_term( $degree, 'degree');
			$degree_list .= $degree_name->name;
			if( count($degrees) > $i ) {
				$degree_list .= ", ";
			}
			$i++;
		endforeach;
 	} ?>
	<?php $full_name = get_field('physician_first_name') .' ' .(get_field('physician_middle_name') ? get_field('physician_middle_name') . ' ' : '') . get_field('physician_last_name') . ( $degree_list ? ', ' . $degree_list : '' ); ?>
	<div class="col item-container">
		<div class="item">
			<div class="row">
				<div class="col image">
					<picture>
					<?php if ( has_post_thumbnail() && function_exists( 'fly_add_image_size' ) ) { ?>
						<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 486, 648, 'center', 'center'); ?>"
                            media="(min-width: 2054px) and (-webkit-min-device-pixel-ratio: 2), 
                            (min-width: 2054px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 243, 324, 'center', 'center'); ?>"
                            media="(min-width: 2054px)">
						<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 368, 490, 'center', 'center'); ?>"
                            media="(min-width: 1784px) and (-webkit-min-device-pixel-ratio: 2), 
                            (min-width: 1784px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 184, 245, 'center', 'center'); ?>"
                            media="(min-width: 1784px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 486, 648, 'center', 'center'); ?>"
                            media="(min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2), 
                            (min-width: 1200px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 243, 324, 'center', 'center'); ?>"
                            media="(min-width: 1200px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 368, 490, 'center', 'center'); ?>"
                            media="(min-width: 768px) and (-webkit-min-device-pixel-ratio: 2), 
                            (min-width: 768px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 184, 245, 'center', 'center'); ?>"
                            media="(min-width: 768px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 190, 254, 'center', 'center'); ?>"
                            media="(min-width: 576px) and (-webkit-min-device-pixel-ratio: 2), 
                            (min-width: 576px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 95, 127, 'center', 'center'); ?>"
                            media="(min-width: 576px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 368, 490, 'center', 'center'); ?>"
                            media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), 
                            (min-width: 1px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 184, 245, 'center', 'center'); ?>"
                            media="(min-width: 1px)">
						<img src="<?php echo image_sizer(get_post_thumbnail_id(), 184, 245, 'center', 'center'); ?>" alt="<?php echo $full_name; ?>" />
					<?php } elseif ( has_post_thumbnail() ) { ?>
						<?php the_post_thumbnail( 'medium',  array( 'itemprop' => 'image' ) ); ?>
					<?php } else { ?>
						<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.svg" alt="" />
					<?php } ?>
					</picture>
				</div>
				<div class="col text">
					<div class="row">
						<div class="col-12 primary">
						<h3 class="h4">
							<span class="name"><?php echo $full_name; ?></span>
							<span class="subtitle"><?php echo (get_field('physician_department') ? get_field('physician_department')->name : ''); ?></span>
						</h3>
						<?php
							if(get_field('physician_npi')) {

									$npi =  get_field( 'physician_npi' );
									$request = wp_nrc_cached_api( $npi );

									$data = json_decode( $request );

									if( ! empty( $data ) ) {

										$rating_valid = $data->valid;

										if ( $rating_valid ){
											echo '<div class="rating">';
											echo '<div class="star-ratings-sprite" title="'. $data->profile->averageRatingStr .' out of 5"><div class="star-ratings-sprite-percentage" style="width: '. (floatval($data->profile->averageRatingStr) / 5)*100  .'%;"></div></div>';
											echo '<div class="ratings-count">'. $data->profile->reviewcount .' Ratings</div>';
											echo '</div>';
										} else { ?>
											<div class="rating">
												<div class="star-ratings-sprite" title="0 out of 5"><div class="star-ratings-sprite-percentage" style="width: 0%;"></div></div>
												<div class="ratings-count">No ratings - <a data-toggle="modal" data-target="#why_not_modal">Why Not?</a></div>
											</div>
										<?php
										}
									}
								} else { ?>
									<div class="rating">
										<div class="star-ratings-sprite" title="0 out of 5"><div class="star-ratings-sprite-percentage" style="width: 0%;"></div></div>
										<div class="ratings-count">No ratings - <a data-toggle="modal" data-target="#why_not_modal">Why Not?</a></div>
									</div>
						<?php } ?>
						
							<p><?php echo ( get_field('physician_short_clinical_bio') ? get_field( 'physician_short_clinical_bio') : wp_trim_words( get_field( 'physician_clinical_bio' ), 30, ' &hellip;' ) ); ?></p>
						<a class="btn btn-primary" href="<?php echo get_permalink($post->ID); ?>">Full Profile</a>
						</div>
						<div class="col-12 secondary">
						<h4 class="h5">Locations</h5>
						<?php

							// $locations = new WP_Query( array(
							//     'relationship' => array(
							//         'id'   => 'physicians_to_locations',
							//         'from' => get_the_ID(), // You can pass object ID or full object
							//     ),
							//     'nopaging'     => true,
							// ) );

							$locations = get_field('physician_locations');

							?>
							<?php if( $locations ): ?>
							<!-- <h3 data-fontsize="16" data-lineheight="24"><i class="fa fa-medkit"></i> Clinic(s)</h3> -->
								<ul>
								<?php foreach( $locations as $location): ?>
									<li>
										<a href="<?php echo get_permalink( $location ); ?>">
											<?php echo get_the_title( $location ); ?>
										</a>
									</li>
								<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						<a class="btn btn-primary" href="<?php echo get_permalink($post->ID); ?>">Full Profile</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--
				<a href="<?php echo get_permalink($post->ID); ?>"><h2 class="margin-top-none margin-bottom-none" itemprop="name"><?php echo $full_name; ?></h2></a>
				<?php
					if(! empty( get_field('physician_clinical_title') ) || ! empty( get_field('physician_department') ) ){
						echo '<h4>';
						echo (get_field('physician_clinical_title') ? get_field('physician_clinical_title')->name : '');
						echo ((! empty( get_field('physician_clinical_title') )) && (! empty( get_field('physician_department') ) ) ? ', ' : '' );
						echo (get_field('physician_department') ? get_field('physician_department')->name : '');
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
					if(get_field('physician_npi')) {

							$npi =  get_field( 'physician_npi' );
							$request = wp_nrc_cached_api( $npi );

							$data = json_decode( $request );

							if( ! empty( $data ) ) {

								$rating_valid = $data->valid;

								if ( $rating_valid ){
 									echo '<div class="text-center">';
									echo '<div class="ds-title">Patient Rating</div>';
									echo '<div><span class="ds-stars ds-stars'. $data->profile->averageStarRatingStr .'"></span></div>';
									echo '<div class="ds-xofy"><span class="ds-average">'. $data->profile->averageRatingStr .'</span><span class="ds-average-max">out of 5</span></div>';
									echo '<div class="ds-ratings"><span class="ds-ratingcount">'. $data->profile->reviewcount .'</span> Ratings</div>';
									echo '<div class="ds-comments"><span class="ds-commentcount">'. $data->profile->bodycount .'</span> Comments</div>';
 									echo '</div>';
									//echo '<a href="' . esc_url( $rating->info->link ) . '">' . $product->info->title . '</a>';
								} else { ?>
									<div class="text-center">
									<div class="ds-title">Patient Rating</div>
									<div><span class="ds-stars ds-stars0"></span></div>
									<div class="small bold">No Patient Satisfaction Reviews</div>
									<div><a href="#" class="js-modal" data-modal-close-text="Close" data-modal-close-title="Close this window" data-modal-content-id="why_not_modal" data-modal-title="Why Not?">Why Not?</a></div>
									</div>
								<?php
								}
							}
						} else { ?>
							<div class="text-center">
							<div class="ds-title">Patient Rating</div>
							<div><span class="ds-stars ds-stars0"></span></div>
							<div class="small bold">No Patient Satisfaction Reviews</div>
							<div><a data-toggle="modal" data-target="#why_not_modal">Why Not?</a></div>
							</div>
						<?php }
					?>
	        </div>
	        <div class="col-md-9 col-sm-8" class="margin-top-none margin-bottom-none">
	                <div class="row" class="margin-top-none margin-bottom-none">
	                    <div class="col-md-6">

	                        <p><?php echo ( get_field('physician_short_clinical_bio') ? get_field( 'physician_short_clinical_bio') : wp_trim_words( get_field( 'physician_clinical_bio' ), 30, ' &hellip;' ) ); ?></p>

	                         <a class="uams-btn btn-blue btn-sm" target="_self" title="View Profile" href="<?php echo get_permalink($post->ID); ?>">View Profile</a>
							<?php if(get_field('physician_youtube_link')) { ?>
								<a class="uams-btn btn-red btn-play btn-sm" target="_self" title="View Physician Video" href="<?php echo get_field('physician_youtube_link'); ?>&rel=0" data-lity>View Video</a>
							<?php } ?>
	                    </div>
	                    <div class="col-md-6">

	                        <?php // load all 'specialties' terms for the post
							$specialties = get_field('medical_specialties');

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

							// $locations = new WP_Query( array(
							//     'relationship' => array(
							//         'id'   => 'physicians_to_locations',
							//         'from' => get_the_ID(), // You can pass object ID or full object
							//     ),
							//     'nopaging'     => true,
							// ) );

							$locations = get_field('physician_locations');

							?>
							<?php if( $locations ): ?>
							<h3 data-fontsize="16" data-lineheight="24"><i class="fa fa-medkit"></i> Clinic(s)</h3>
								<ul>
								<?php foreach( $locations as $location): ?>
									<li>
										<a href="<?php echo get_permalink( $location ); ?>">
											<?php echo get_the_title( $location ); ?>
										</a>
									</li>
								<?php endforeach; ?>
								</ul>
							<?php endif; ?>

	                    <!-- </div>.col-6 -->
	                <!-- </div>.row -->

	        <!-- </div>.col-9 -->
	    <!-- </div>.row -->
	<!-- </div>.color -->
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