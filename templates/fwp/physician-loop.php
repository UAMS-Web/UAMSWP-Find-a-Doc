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

	// Check/define variables

		// Find-a-Doc Settings values for provider labels

			if (
				!isset($provider_single_name) || empty($provider_single_name)
				||
				!isset($provider_single_name_attr) || empty($provider_single_name_attr)
				||
				!isset($provider_plural_name) || empty($provider_plural_name)
				||
				!isset($provider_plural_name_attr) || empty($provider_plural_name_attr)
			) {
				$labels_provider_vars = uamswp_fad_labels_provider();
					$provider_single_name = $labels_provider_vars['provider_single_name']; // string
					$provider_single_name_attr = $labels_provider_vars['provider_single_name_attr']; // string
					$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string
					$provider_plural_name_attr = $labels_provider_vars['provider_plural_name_attr']; // string
			}

		// Find-a-Doc Settings values for location labels

			if (
				!isset($location_plural_name) || empty($location_plural_name)
			) {
				$labels_location_vars = uamswp_fad_labels_location();
					$location_plural_name = $labels_location_vars['location_plural_name']; // string
			}
 
	while ( have_posts() ) {

		the_post();

		$degrees = get_field('physician_degree');
		$degree_list = '';
		$i = 1;

		if ( $degrees ) {

			foreach ( $degrees as $degree ) {

				$degree_name = get_term( $degree, 'degree');
				$degree_list .= $degree_name->name;

				if ( count($degrees) > $i ) {

					$degree_list .= ", ";

				}

				$i++;

			} // endforeach

		}

		$full_name = get_field('physician_first_name') .' ' .(get_field('physician_middle_name') ? get_field('physician_middle_name') . ' ' : '') . get_field('physician_last_name') . (get_field('physician_pedigree') ? '&nbsp;' . get_field('physician_pedigree') : '') . ( $degree_list ? ', ' . $degree_list : '' );
		$full_name_attr = uamswp_attr_conversion($full_name);
		$provider_resident = get_field('physician_resident');
		$provider_resident_name = 'Resident Physician';
		$provider_title = get_field('physician_title');
		$provider_title_name = $provider_resident ? $provider_resident_name : get_term( $provider_title, 'clinical_title' )->name;
		$provider_service_line = get_field('physician_service_line');

		?>
		<div class="col item-container">
			<div class="item">
				<div class="row">
					<div class="col image">
						<a href="<?php echo get_permalink($post->ID); ?>" aria-label="Full profile for <?php echo $full_name_attr; ?>" class="stretched-link" data-categorytitle="Photo" data-itemtitle="<?php echo $full_name_attr; ?>">
							<picture>
								<?php

								if (
									has_post_thumbnail()
									&&
									function_exists( 'fly_add_image_size' )
								) {

									?>
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
									<?php

								} elseif ( has_post_thumbnail() ) {

									the_post_thumbnail( 'medium', array( 'itemprop' => 'image' ) );

								} else {

									?>
									<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.svg" media="(min-width: 1px)">
									<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg" alt="" role="presentation" />
									<?php

								} // endif

								?>
							</picture>
						</a>
					</div>
					<div class="col text">
						<div class="row">
							<div class="col-12 primary">
							<h3 class="h4">
								<a href="<?php echo get_permalink($post->ID); ?>" aria-label="Full profile for <?php echo $full_name_attr; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $full_name_attr; ?>"><span class="name"><?php echo $full_name; ?></span></a>
								<?php

								// Add subtitle for clinical title

									if ( $provider_title_name ) {

										?>
										<span class="subtitle"><?php echo $provider_title_name; ?></span>
										<?php

									} // endif

								?>
							</h3>
							<?php

								if ( get_field('physician_npi') ) {

									$npi = get_field( 'physician_npi' );
									$request = wp_nrc_cached_api( $npi );

									$data = json_decode( $request );

									if ( ! empty( $data ) ) {

										$rating_valid = $data->valid;

										if ( $rating_valid ) {

											echo '<div class="rating">';
											echo '<div class="star-ratings-sprite" title="'. $data->profile->averageRatingStr .' out of 5"><div class="star-ratings-sprite-percentage" style="width: '. (floatval($data->profile->averageRatingStr) / 5)*100 .'%;"></div></div>';
											echo '<div class="ratings-count">'. $data->profile->reviewcount .' Ratings</div>';
											echo '</div>';

										} else {

											?>
											<p class="small"><em>Patient ratings are not available for this <?php echo strtolower($provider_single_name); ?>. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#" data-categorytitle="Ratings Modal" data-itemtitle="<?php echo $full_name_attr; ?>" aria-label="Learn why ratings are not available for this <?php echo strtolower($provider_single_name_attr); ?>"><span aria-hidden="true">Why not?</span></a></em></p>
											<?php

										}

									}

								} else {

									?>
									<p class="small"><em>Patient ratings are not available for this <?php echo strtolower($provider_single_name); ?>. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#" data-categorytitle="Ratings Modal" data-itemtitle="<?php echo $full_name_attr; ?>" aria-label="Learn why ratings are not available for this <?php echo strtolower($provider_single_name_attr); ?>"><span aria-hidden="true">Why not?</span></a></em></p>
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

								foreach ( $locations as $location ) {

									if ( get_post_status ( $location ) == 'publish' ) {

										$location_valid = true;
										$break;

									}
								}

							if (
								$locations
								&&
								$location_valid
							) {

								?>
								<div class="col-12 secondary">
									<h4 class="h5"><?php echo $location_plural_name; ?></h4>
										<ul>
											<?php

											foreach ( $locations as $location) {

												if ( get_post_status ( $location ) == 'publish' ) {

													$related_location = get_the_title( $location );
													$related_location_attr = uamswp_attr_conversion($related_location);

													?>
													<li>
														<a href="<?php echo get_permalink( $location ); ?>" data-categorytitle="Related Location" data-typetitle="<?php echo $related_location_attr; ?>" data-itemtitle="<?php echo $full_name_attr; ?>">
															<?php echo get_the_title( $location ); ?>
														</a>
													</li>
													<?php

												} // endif

											} // endforeach

											?>
										</ul>
									<a class="btn btn-primary" href="<?php echo get_permalink($post->ID); ?>" aria-label="Full profile for <?php echo $full_name_attr; ?>" data-categorytitle="View Full Profile" data-itemtitle="<?php echo $full_name_attr; ?>">Full Profile</a>
								</div>
								<?php

							} // endif

							?> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php

		$i++;

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