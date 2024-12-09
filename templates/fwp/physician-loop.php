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
	<?php
		$full_name = get_field('physician_first_name') .' ' .(get_field('physician_middle_name') ? get_field('physician_middle_name') . ' ' : '') . get_field('physician_last_name') . (get_field('physician_pedigree') ? '&nbsp;' . get_field('physician_pedigree') : '') . ( $degree_list ? ', ' . $degree_list : '' );
		$full_name_attr = $full_name;
		$full_name_attr = str_replace('"', '\'', $full_name_attr); // Replace double quotes with single quote
		$full_name_attr = str_replace('&#8217;', '\'', $full_name_attr); // Replace right single quote with single quote
		$full_name_attr = htmlentities($full_name_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
		$full_name_attr = str_replace('&nbsp;', ' ', $full_name_attr); // Convert non-breaking space with normal space
		$full_name_attr = html_entity_decode($full_name_attr); // Convert HTML entities to their corresponding characters

		// Get resident values

			$physician_resident = get_field('physician_resident');
			$physician_resident_title_name = 'Resident Physician';
			$physician_service_line = get_field('physician_service_line');


		// Get clinical specialty and occupation title values

			// Eliminate PHP errors

				$provider_specialty = '';
				$provider_specialty_term = '';
				$provider_specialty_name = '';
				$provider_occupation_title = '';

			if ( $physician_resident ) {

				// Clinical Occupation Title

					$provider_occupation_title = $physician_resident_title_name;

			} else {

				// Clinical Specialty

					$provider_specialty = get_field('physician_title');

				// Clinical Occupation Title

					if ( $provider_specialty ) {

						$provider_specialty_term = get_term($provider_specialty, 'clinical_title');

						if ( is_object($provider_specialty_term) ) {

							// Get term name

								$provider_specialty_name = $provider_specialty_term->name;

							// Get occupational title field from term

								$provider_occupation_title = get_field('clinical_specialization_title', $provider_specialty_term) ?? null;

							// Set occupational title from term name as a fallback

								if ( !$provider_occupation_title ) {

									$provider_occupation_title = $provider_specialty_name;

								}

						}

					}

			}

	?>
	<div class="col item-container">
		<div class="item">
			<div class="row">
				<div class="col image">
					<a href="<?php echo get_permalink($post->ID); ?>" aria-label="Full profile for <?php echo $full_name_attr; ?>" class="stretched-link" data-categorytitle="Photo" data-itemtitle="<?php echo $full_name_attr; ?>">
						<picture>
						<?php if ( has_post_thumbnail() && function_exists( 'fly_add_image_size' ) ) { ?>
							<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 243, 324, 'center', 'center'); ?>"
								media="(min-width: 2054px)">
							<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 184, 245, 'center', 'center'); ?>"
								media="(min-width: 1784px)">
							<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 243, 324, 'center', 'center'); ?>"
								media="(min-width: 1200px)">
							<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 184, 245, 'center', 'center'); ?>"
								media="(min-width: 768px)">
							<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 95, 127, 'center', 'center'); ?>"
								media="(min-width: 576px)">
							<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 184, 245, 'center', 'center'); ?>"
								media="(min-width: 1px)">
							<img src="<?php echo image_sizer(get_post_thumbnail_id(), 184, 245, 'center', 'center'); ?>" alt="<?php echo $full_name_attr; ?>" />
						<?php } elseif ( has_post_thumbnail() ) { ?>
							<?php the_post_thumbnail( 'medium',  array( 'itemprop' => 'image' ) ); ?>
						<?php } else { ?>
							<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.svg" media="(min-width: 1px)">
							<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg" alt="" role="presentation" />
						<?php } ?>
						</picture>
					</a>
				</div>
				<div class="col text">
					<div class="row">
						<div class="col-12 primary">
						<h3 class="h4">
							<a href="<?php echo get_permalink($post->ID); ?>" aria-label="Full profile for <?php echo $full_name_attr; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $full_name_attr; ?>"><span class="name"><?php echo $full_name; ?></span></a>
							<?php

							if (
								$provider_occupation_title
								&&
								!empty($provider_occupation_title)
							) {
								?>
								<span class="subtitle"><?php echo $provider_occupation_title; ?></span>
								<?php

							}

							?>
						</h3>
						<?php
							if(get_field('physician_npi')) {

								$npi =  get_field( 'physician_npi' );
								// $request = wp_nrc_cached_api( $npi );

								// $data = json_decode( $request );

								$pg_rating_request = '';
								$pg_rating_data = '';
								$pg_rating_valid = false;
								if ( $npi ) {
									$pg_rating_request = wp_pg_cached_api( $npi, 0 );
									$pg_rating_data = json_decode( $pg_rating_request );
									if ( !empty( $pg_rating_data ) && !empty($pg_rating_data->data->entities[0]->totalRatingCount) ) {
										$pg_rating_valid = (( $pg_rating_data->data->entities[0]->totalRatingCount) >= 30 );
									}
								}

								if( ! empty( $pg_rating_data ) ) {

									if ( $pg_rating_valid ){
										$avg_rating = $pg_rating_data->data->entities[0]->overallRating->value;
										$review_count = $pg_rating_data->data->entities[0]->totalRatingCount;
										$comment_count = $pg_rating_data->data->entities[0]->totalCommentCount;
										echo '<div class="rating">';
										echo '<div class="star-ratings-sprite" title="'. $avg_rating .' out of 5"><div class="star-ratings-sprite-percentage" style="width: '. (floatval($avg_rating) / 5)*100  .'%;"></div></div>';
										echo '<div class="ratings-count">'. $review_count .' Ratings</div>';
										echo '</div>';
									} else { ?>
									<p class="small"><em>Patient ratings are not available for this provider. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#" data-categorytitle="Ratings Modal" data-itemtitle="<?php echo $full_name_attr; ?>" aria-label="Learn why ratings are not available for this provider"><span aria-hidden="true">Why not?</span></a></em></p>
									<?php
									}

								}
								// if( ! empty( $data ) ) {
								//
								// 	$rating_valid = $data->valid;
								//
								// 	if ( $rating_valid ){
								// 		echo '<div class="rating">';
								// 		echo '<div>NRC Data:</div>';
								// 		echo '<div class="ratings-count">Rating: '. $data->profile->averageRatingStr . ' - ' . $data->profile->reviewcount .' Ratings</div>';
								// 		echo '</div>';
								// 	}
								// }
							} else { ?>
								<p class="small"><em>Patient ratings are not available for this provider. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#" data-categorytitle="Ratings Modal" data-itemtitle="<?php echo $full_name_attr; ?>" aria-label="Learn why ratings are not available for this provider"><span aria-hidden="true">Why not?</span></a></em></p>
							<?php
							}
							?>

							<p><?php echo ( get_field('physician_short_clinical_bio') ? get_field( 'physician_short_clinical_bio') : wp_trim_words( get_field( 'physician_clinical_bio' ), 30, ' &hellip;' ) ); ?></p>
						<a class="btn btn-primary" href="<?php echo get_permalink($post->ID); ?>" aria-label="Full profile for <?php echo $full_name_attr; ?>" data-categorytitle="View Full Profile" data-itemtitle="<?php echo $full_name_attr; ?>">Full Profile</a>
						</div>
						<?php
						// Check for valid locations
						$locations = get_field('physician_locations');
						$location_valid = false;
						foreach( $locations as $location ) {
							if ( get_post_status ( $location ) == 'publish' ) {
								$location_valid = true;
								$break;
							}
						}
						if( $locations && $location_valid ) { ?>
							<div class="col-12 secondary">
								<h4 class="h5">Locations</h4>
									<ul>
										<?php foreach( $locations as $location):
											if ( get_post_status ( $location ) == 'publish' ) {
												$related_location = get_the_title( $location );
												$related_location_attr = $related_location;
												$related_location_attr = str_replace('"', '\'', $related_location_attr); // Replace double quotes with single quote
												$related_location_attr = str_replace('&#8217;', '\'', $related_location_attr); // Replace right single quote with single quote
												$related_location_attr = htmlentities($related_location_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
												$related_location_attr = str_replace('&nbsp;', ' ', $related_location_attr); // Convert non-breaking space with normal space
												$related_location_attr = html_entity_decode($related_location_attr); // Convert HTML entities to their corresponding characters
											?>
											<li>
												<a href="<?php echo get_permalink( $location ); ?>" data-categorytitle="Related Location" data-typetitle="<?php echo $related_location_attr; ?>" data-itemtitle="<?php echo $full_name_attr; ?>">
													<?php echo get_the_title( $location ); ?>
												</a>
											</li>
										<?php } // endif
										endforeach; ?>
									</ul>
								<a class="btn btn-primary" href="<?php echo get_permalink($post->ID); ?>" aria-label="Full profile for <?php echo $full_name_attr; ?>" data-categorytitle="View Full Profile" data-itemtitle="<?php echo $full_name_attr; ?>">Full Profile</a>
							</div>
						<?php } // endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--
				<a href="<?php echo get_permalink($post->ID); ?>"><h2 class="margin-top-none margin-bottom-none" itemprop="name"><?php echo $full_name; ?></h2></a>
				<?php
					if(! empty( get_field('physician_clinical_title') ) || ! empty( $physician_service_line ) ){
						echo '<h4>';
						echo (get_field('physician_clinical_title') && is_object(get_field('physician_clinical_title')) ? get_field('physician_clinical_title')->name : '');
						echo ((! empty( get_field('physician_clinical_title') )) && (! empty( $physician_service_line ) ) ? ', ' : '' );
						echo ($physician_service_line ? get_term( $physician_service_line, 'service_line' )->name : '');
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
				<?php /*
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
									<div><a href="#" class="js-modal" data-modal-close-text="Close" data-modal-close-title="Close this window" data-modal-content-id="why_not_modal" data-modal-title="Why Not?" aria-label="Learn why ratings are not available for this provider"><span aria-hidden="true">Why not?</span></a></div>
									</div>
								<?php
								}
							}
						} else { ?>
							<div class="text-center">
							<div class="ds-title">Patient Rating</div>
							<div><span class="ds-stars ds-stars0"></span></div>
							<div class="small bold">No Patient Satisfaction Reviews</div>
							<div><a data-toggle="modal" data-target="#why_not_modal" aria-label="Learn why ratings are not available for this provider"><span aria-hidden="true">Why not?</span></a></div>
							</div>
						<?php }
				*/	?>
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
							-->
	                    <!-- </div>.col-6 -->
	                <!-- </div>.row -->

	        <!-- </div>.col-9 -->
	    <!-- </div>.row -->
	<!-- </div>.color -->
	<?php $i++; ?>
	<?php endwhile; ?>
		<link rel="stylesheet" type="text/css" href="https://www.docscores.com/resources/css/docscores-lotw.v1330-2018121714.css" />

	<?php else : ?>
	<p><?php _e( 'Sorry, no providers matched your criteria.' ); ?></p>
	<?php endif; ?>