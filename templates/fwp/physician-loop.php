<?php
/**
 * Template Name: Providers Archive Page Loop for FacetWP "Physician" Template
 * Template Slug: physician
 * Designed for physicians
 */

// Bring in variables from outside of the function
global $provider_single_name; // Defined in uamswp_fad_labels_provider()
global $provider_single_name_attr; // Defined in uamswp_fad_labels_provider()
global $provider_plural_name; // Defined in uamswp_fad_labels_provider()
global $provider_plural_name_attr; // Defined in uamswp_fad_labels_provider()
global $location_plural_name; // Defined in uamswp_fad_labels_location()
 
if ( have_posts() ) : while ( have_posts() ) : the_post();
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
}
$full_name = get_field('physician_first_name') .' ' .(get_field('physician_middle_name') ? get_field('physician_middle_name') . ' ' : '') . get_field('physician_last_name') . (get_field('physician_pedigree') ? '&nbsp;' . get_field('physician_pedigree') : '') . ( $degree_list ? ', ' . $degree_list : '' );
$full_name_attr = uamswp_attr_conversion($full_name);
$physician_resident = get_field('physician_resident');
$physician_resident_name = 'Resident Physician';
$physician_title = get_field('physician_title');
$physician_title_name = $physician_resident ? $physician_resident_name : get_term( $physician_title, 'clinical_title' )->name;
$physician_service_line = get_field('physician_service_line');
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
						<?php the_post_thumbnail( 'medium', array( 'itemprop' => 'image' ) ); ?>
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
						<?php if ( $physician_title_name ) { ?>
						<span class="subtitle"><?php echo $physician_title_name; ?></span>
						<?php } // endif ?>
					</h3>
					<?php
						if(get_field('physician_npi')) {

							$npi = get_field( 'physician_npi' );
							$request = wp_nrc_cached_api( $npi );

							$data = json_decode( $request );

							if( ! empty( $data ) ) {

								$rating_valid = $data->valid;

								if ( $rating_valid ){
									echo '<div class="rating">';
									echo '<div class="star-ratings-sprite" title="'. $data->profile->averageRatingStr .' out of 5"><div class="star-ratings-sprite-percentage" style="width: '. (floatval($data->profile->averageRatingStr) / 5)*100 .'%;"></div></div>';
									echo '<div class="ratings-count">'. $data->profile->reviewcount .' Ratings</div>';
									echo '</div>';
								} else { ?>
								<p class="small"><em>Patient ratings are not available for this <?php echo strtolower($provider_single_name); ?>. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#" data-categorytitle="Ratings Modal" data-itemtitle="<?php echo $full_name_attr; ?>" aria-label="Learn why ratings are not available for this <?php echo strtolower($provider_single_name_attr); ?>"><span aria-hidden="true">Why not?</span></a></em></p>
								<?php
								}
							}
						} else { ?>
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
					foreach( $locations as $location ) {
						if ( get_post_status ( $location ) == 'publish' ) {
							$location_valid = true;
							$break;
						}
					}
					if( $locations && $location_valid ) { ?>
						<div class="col-12 secondary">
							<h4 class="h5"><?php echo $location_plural_name; ?></h4>
								<ul>
									<?php foreach( $locations as $location):
										if ( get_post_status ( $location ) == 'publish' ) {
											$related_location = get_the_title( $location );
											$related_location_attr = uamswp_attr_conversion($related_location);
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
					echo (get_field('physician_clinical_title') ? get_field('physician_clinical_title')->name : '');
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
				<span><a href="<?php echo get_permalink($post->ID); ?>" target="_self"><?php the_post_thumbnail( 'medium', array( 'itemprop' => 'image' ) ); ?></a></span>
			</div>
			<?php /*
				if(get_field('physician_npi')) {

						$npi = get_field( 'physician_npi' );
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
								<div><a href="#" class="js-modal" data-modal-close-text="Close" data-modal-close-title="Close this window" data-modal-content-id="why_not_modal" data-modal-title="Why Not?" aria-label="Learn why ratings are not available for this <?php //echo strtolower($provider_single_name_attr); ?>"><span aria-hidden="true">Why not?</span></a></div>
								</div>
							<?php
							}
						}
					} else { ?>
						<div class="text-center">
						<div class="ds-title">Patient Rating</div>
						<div><span class="ds-stars ds-stars0"></span></div>
						<div class="small bold">No Patient Satisfaction Reviews</div>
						<div><a data-toggle="modal" data-target="#why_not_modal" aria-label="Learn why ratings are not available for this <?php //echo strtolower($provider_single_name_attr); ?>"><span aria-hidden="true">Why not?</span></a></div>
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
						<h3 data-fontsize="16" data-lineheight="24"><i class="fa fa-medkit"></i> <?php //echo $location_single_name; ?>(s)</h3>
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
<p><?php _e( 'Sorry, no ' . strtolower($provider_plural_name) . ' matched your criteria.' ); ?></p>
<?php endif; ?>