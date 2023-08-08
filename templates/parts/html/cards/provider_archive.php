<?php
/**
 * Template Name: Provider Archive Card
 *
 * Description: A template part that displays a provider card to be included in a
 * list of providers on a provider archive page.
 *
 * Must be used inside a loop
 *
 * Designed for UAMS Health Find-a-Doc
*/

// Check/define variables

	// Page ID
	$page_id = get_the_ID();

	// Get the field values for the card
	
		$provider_card_fields_vars = ''; // Reset the variables
		$provider_card_style = 'detailed'; // Provider card style
		include( UAMS_FAD_PATH . '/templates/parts/vars/page/cards/provider.php' );

	// Get system settings for provider labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

?>
<div class="col item-container">
	<div class="item">
		<div class="row">
			<div class="col image">
				<a href="<?php echo $provider_url; ?>" aria-label="Full profile for <?php echo $provider_full_name_attr; ?>" class="stretched-link" data-categorytitle="Photo" data-itemtitle="<?php echo $provider_full_name_attr; ?>">
					<picture>
						<?php

						if ( $provider_headshot_base_url ) {

							foreach ( $provider_headshot_srcset as $srcset ) {

								?>
								<source srcset="<?php echo $srcset['url']; ?>" media="(min-width: <?php echo $srcset['media-min-width']; ?>)">
								<?php

							}
							?>
							<img src="<?php echo $provider_headshot_base_url; ?>" itemprop="image" alt="<?php echo $provider_full_name_attr; ?>" loading="lazy" />
							<?php

						} else {

							?>
							<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.svg" media="(min-width: 1px)">
							<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg" alt="" role="presentation" loading="lazy" />
							<?php

						} // endif ( $provider_headshot_base_url ) else

						?>
					</picture>
				</a>
			</div>
			<div class="col text">
				<div class="row">
					<div class="col-12 primary">
					<h3 class="h4">
						<a href="<?php echo $provider_url; ?>" aria-label="Full profile for <?php echo $provider_full_name_attr; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $provider_full_name_attr; ?>"><span class="name"><?php echo $provider_full_name; ?></span></a>
						<?php

						// Add subtitle for clinical title

							if ( $provider_title_list ) {

								?>
								<span class="subtitle"><?php echo $provider_title_list; ?></span>
								<?php

							} // endif  ( $provider_title_list )

						?>
					</h3>
					<?php

						if ( $provider_npi ) {

							$nrc_request = wp_nrc_cached_api( $provider_npi );

							$nrc_data = json_decode( $nrc_request );

							if ( !empty( $nrc_data ) ) {

								$rating_valid = $nrc_data->valid;

								if ( $rating_valid ) {

									echo '<div class="rating">';
									echo '<div class="star-ratings-sprite" title="'. $nrc_data->profile->averageRatingStr .' out of 5"><div class="star-ratings-sprite-percentage" style="width: '. (floatval($nrc_data->profile->averageRatingStr) / 5)*100 .'%;"></div></div>';
									echo '<div class="ratings-count">'. $nrc_data->profile->reviewcount .' Ratings</div>';
									echo '</div>';

								} else {

									?>
									<p class="small"><em>Patient ratings are not available for this <?php echo strtolower($provider_single_name); ?>. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#" data-categorytitle="Ratings Modal" data-itemtitle="<?php echo $provider_full_name_attr; ?>" aria-label="Learn why ratings are not available for this <?php echo strtolower($provider_single_name_attr); ?>"><span aria-hidden="true">Why not?</span></a></em></p>
									<?php

								} // endif ( $rating_valid ) else

							} // endif ( !empty( $nrc_data ) )

						} else {

							?>
							<p class="small"><em>Patient ratings are not available for this <?php echo strtolower($provider_single_name); ?>. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#" data-categorytitle="Ratings Modal" data-itemtitle="<?php echo $provider_full_name_attr; ?>" aria-label="Learn why ratings are not available for this <?php echo strtolower($provider_single_name_attr); ?>"><span aria-hidden="true">Why not?</span></a></em></p>
							<?php

						} // endif ( $provider_npi ) else

						// Excerpt

						if ( $provider_excerpt ) {

							?>
							<p><?php echo $provider_excerpt; ?></p>
							<?php

						} // endif ( $provider_excerpt )

						?>
						<a class="btn btn-primary" href="<?php echo $provider_url; ?>" aria-label="Full profile for <?php echo $provider_full_name_attr; ?>" data-categorytitle="View Full Profile" data-itemtitle="<?php echo $provider_full_name_attr; ?>">Full Profile</a>
					</div>
					<?php 

					if ( $provider_locations_array ) {

						?>
						<div class="col-12 secondary">
							<h4 class="h5"><?php echo $location_plural_name; ?></h4>
								<ul>
									<?php

									foreach ( $provider_locations_array as $item) {

										?>
										<li>
											<a href="<?php echo $item['url']; ?>" data-categorytitle="Related Location" data-typetitle="<?php echo $item['title_attr']; ?>" data-itemtitle="<?php echo $provider_full_name_attr; ?>">
												<?php echo $item['title']; ?>
											</a>
										</li>
										<?php

									} // endforeach

									?>
								</ul>
							<a class="btn btn-primary" href="<?php echo $provider_url; ?>" aria-label="Full profile for <?php echo $provider_full_name_attr; ?>" data-categorytitle="View Full Profile" data-itemtitle="<?php echo $provider_full_name_attr; ?>">Full Profile</a>
						</div>
						<?php

					} // endif ( $provider_locations_array )

					?> 
				</div>
			</div>
		</div>
	</div>
</div>
<?php