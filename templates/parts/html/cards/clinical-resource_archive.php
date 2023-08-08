<?php 
/**
 * Template Name: Clinical Resource Card for Clinical Resource Archive
 * 
 * Description: A template part that displays a clinical resource card to be 
 * included in a clinical resource archive.
 * 
 * Must be used inside a loop
 * 
 * Designed for UAMS Health Find-a-Doc
 */

// Check/define variables

	// Page ID
	$page_id = get_the_ID();

	// Get the field values for the card

		$clinical_resource_card_fields_vars = ''; // Reset the variables
		$clinical_resource_card_style = 'detailed'; // Clinical resource card style
		include( UAMS_FAD_PATH . '/templates/parts/vars/page/cards/clinical-resource.php' );

// Check/define variables

	// Get system settings for clinical resource labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

$resource_related_max = 3; // Set how many of each related item type to display

// Check for valid providers

	$resource_providers = get_field('clinical_resource_providers');
	$resource_provider_valid = false;
	if ( !empty($resource_providers) ) {
		foreach( $resource_providers as $resource_provider ) {
			if ( get_post_status ( $resource_provider ) == 'publish' ) {
				$resource_provider_valid = true;
				$break;
			} // endif
		} // endforeach;
		$resource_provider = '';
		// Count published providers
		$resource_provider_count = 0;
		foreach( $resource_providers as $resource_provider) {
			if ( get_post_status ( $resource_provider ) == 'publish' ) {
				$resource_provider_count++;
			} // endif
		} // endforeach;
		$resource_provider = '';
		$resource_provider_label = $resource_provider_count > 1 ? $provider_plural_name : $provider_single_name;
	}

// Check for valid locations

	$resource_locations = get_field('clinical_resource_locations');
	$resource_location_valid = false;
	if( !empty($resource_locations) ) {
		foreach( $resource_locations as $resource_location ) {
			if ( get_post_status ( $resource_location ) == 'publish' ) {
				$resource_location_valid = true;
				$break;
			} // endif
		} // endforeach;
		$resource_location = '';
		// Count published locations
		$resource_location_count = 0;
		foreach( $resource_locations as $resource_location) {
			if ( get_post_status ( $resource_location ) == 'publish' ) {
				$resource_location_count++;
			} // endif
		} // endforeach;
		$resource_location = '';
		$resource_location_label = $resource_location_count > 1 ? $location_plural_name : $location_single_name;
	}

// Check for valid conditions

	$resource_conditions = get_field('clinical_resource_conditions');
	$resource_condition_valid = false;
	if ( !empty($resource_conditions) ) {
		foreach( $resource_conditions as $resource_condition ) {
			if ( get_post_status ( $resource_condition ) == 'publish' ) {
				$resource_condition_valid = true;
				$break;
			} // endif
		} // endforeach;
		$resource_condition = '';
		// Count published conditions
		$resource_condition_count = 0;
		foreach( $resource_conditions as $resource_condition) {
			if ( get_post_status ( $resource_condition ) == 'publish' ) {
				$resource_condition_count++;
			} // endif
		} // endforeach;
		$resource_condition = '';
		$resource_condition_label = $resource_condition_count > 1 ? $condition_plural_name : $condition_single_name;
	}

// Check for valid treatments

	$resource_treatments = get_field('clinical_resource_treatments');
	$resource_treatment_valid = false;
	if ( !empty($resource_treatments) ) {
		foreach( $resource_treatments as $resource_treatment ) {
			if ( get_post_status ( $resource_treatment ) == 'publish' ) {
				$resource_treatment_valid = true;
				$break;
			} // endif
		} // endforeach;
		$resource_treatment = '';
		// Count published treatments
		$resource_treatment_count = 0;
		foreach( $resource_treatments as $resource_treatment) {
			if ( get_post_status ( $resource_treatment ) == 'publish' ) {
				$resource_treatment_count++;
			} // endif
		} // endforeach;
		$resource_treatment = '';
		$resource_treatment_label = $resource_treatment_count > 1 ? $treatment_plural_name : $treatment_single_name;
	}

// Check for valid areas of expertise

	$resource_expertises = get_field('clinical_resource_aoe');
	$resource_expertise_valid = false;
	if ( !empty($resource_expertises) ) {
		foreach( $resource_expertises as $resource_expertise ) {
			if ( get_post_status ( $resource_expertise ) == 'publish' ) {
				$resource_expertise_valid = true;
				$break;
			} // endif
		} // endforeach;
		$resource_expertise = '';
		// Count areas of expertise
		$resource_expertise_count = 0;
		foreach( $resource_expertises as $resource_expertise) {
			if ( get_post_status ( $resource_expertise ) == 'publish' ) {
				$resource_expertise_count++;
			} // endif
		} // endforeach;
		$resource_expertise = '';
		$resource_expertise_label = $resource_expertise_count > 1 ? $expertise_plural_name : $expertise_single_name;
	}

// Construct the card

?>
<div class="col item-container">
	<div class="item">
		<div class="row">
			<div class="col image">
				<a href="<?php echo $clinical_resource_url; ?>" aria-label="<?php echo $clinical_resource_link_label; ?>" data-categorytitle="Photo" data-itemtitle="<?php echo $clinical_resource_title_attr; ?>">
					<picture>
						<?php

						if ( $clinical_resource_featured_image_base_url ) {

							foreach ( $clinical_resource_featured_image_srcset as $srcset ) {

								?>
								<source srcset="<?php echo $srcset['url']; ?>" media="(min-width: <?php echo $srcset['media-min-width']; ?>)">
								<?php

							}

							?>
							<img src="<?php echo $clinical_resource_featured_image_base_url; ?>" alt="" role="presentation" loading="lazy" />
							<?php

						} else {

							?>
							<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
							<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" loading="lazy" />
							<?php

						} // endif ( $clinical_resource_featured_image_base_url ) else

						?>
					</picture>
				</a>
			</div>
			<div class="col text">
				<div class="row">
					<div class="col-12 primary">
						<h3 class="h4">
							<a href="<?php echo $clinical_resource_url; ?>" aria-label="<?php echo $clinical_resource_link_label; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $clinical_resource_title_attr; ?>">
								<span class="name"><?php echo $clinical_resource_title; ?></span>
							</a>
							<?php

							if ( $clinical_resource_type_label ) {

								?>
								<span class="subtitle"><span class="sr-only">(</span><?php echo esc_html($clinical_resource_type_label); ?><span class="sr-only">)</span></span>
								<?php

							} // endif ( $clinical_resource_type_label )

							?>
						</h3>
						<?php

						if ( $clinical_resource_excerpt ) {

							?>
							<p class="card-text"><?php echo $clinical_resource_excerpt; ?></p>
							<?php

						} // endif ( $clinical_resource_excerpt )

						?>
						<a class="btn btn-primary" href="<?php echo $clinical_resource_url; ?>" aria-label="<?php echo $clinical_resource_link_label; ?>" data-categorytitle="View Clinical Resource" data-itemtitle="<?php echo $clinical_resource_title_attr; ?>"><?php echo $clinical_resource_link_text; ?></a>
					</div>
					<div class="col-12 secondary">
						<?php

						// Related Content

							?>
							<h4 class="h5">Related Content</h4>
							<dl>
								<?php

								// Providers

									if (
										$resource_providers
										&&
										$resource_provider_valid
									) {

										// Get system settings for provider labels
										include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

										?>
										<dt><?php echo $resource_provider_label; ?></dt>
										<dd>
											<?php

											$resource_i = 0;
											$resource_count = $resource_provider_count;
											$associates = $resource_providers;

											foreach( $associates as $associate) {

												$resource_provider_prefix = get_field('physician_prefix', $associate);
												$resource_provider_first_name = get_field('physician_first_name', $associate);
												$resource_provider_middle_name = get_field('physician_middle_name', $associate);
												$resource_provider_last_name = get_field('physician_last_name', $associate);
												$resource_provider_pedigree = get_field('physician_pedigree', $associate);
												$resource_provider_medium_name = ($resource_provider_prefix ? $resource_provider_prefix .' ' : '') . $resource_provider_first_name .' ' .($resource_provider_middle_name ? $resource_provider_middle_name . ' ' : '') . $resource_provider_last_name . ($resource_provider_pedigree ? ' ' . $resource_provider_pedigree : '');

												if ( get_post_status ( $associate ) == 'publish' ) {

													if ( $resource_i < $resource_related_max ) {

														$associate_title = $resource_provider_medium_name;
														$associate_title_attr = uamswp_attr_conversion($associate_title);

														echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $clinical_resource_title_attr . '">' . $associate_title . '</a>';
														$resource_i++;

														if ( 
															(
																$resource_count > $resource_related_max
																&&
																$resource_i != $resource_related_max
															)
															||
															(
																$resource_count > 1
																&&
																$resource_count <= $resource_related_max
																&&
																$resource_i < ($resource_count - 1)
															)
														) {

															// If there are more items than the max AND this is not the max item
															// OR If there are as many or fewer items than the max AND this is not the penultimate item
															echo ', ';

														} elseif ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i == ($resource_count - 1) ) {

															// If there are as many or fewer items than the max AND this is the penultimate item
															echo ' and ';
														}

													} elseif ( $resource_i == $resource_related_max ) {

														echo ' and more';
														break;
													}

												}

											} // endforeach;

											?>
										</dd>
										<?php

									} // endif

									$resource_i = 0;
									$resource_count = '';
									$associates = '';

								// Locations

									if (
										$resource_locations
										&&
										$resource_location_valid
									) {

										// Get system settings for location labels
										include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

										?>
										<dt><?php echo $resource_location_label; ?></dt>
										<dd>
											<?php

											$resource_i = 0;
											$resource_count = $resource_location_count;
											$associates = $resource_locations;

											foreach ( $associates as $associate) {

												if ( get_post_status ( $associate ) == 'publish' ) {

													if ( $resource_i < $resource_related_max ) {

														$associate_title = get_the_title( $associate );
														$associate_title_attr = uamswp_attr_conversion($associate_title);

														echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $clinical_resource_title_attr . '">' . $associate_title . '</a>';
														$resource_i++;

														if ( 
															(
																$resource_count > $resource_related_max
																&&
																$resource_i != $resource_related_max
															)
															||
															(
																$resource_count > 1
																&&
																$resource_count <= $resource_related_max
																&&
																$resource_i < ($resource_count - 1)
															)
														) {

															// If there are more items than the max AND this is not the max item
															// OR If there are as many or fewer items than the max AND this is not the penultimate item
															echo ', ';

														} elseif (
															$resource_count > 1
															&&
															$resource_count <= $resource_related_max
															&&
															$resource_i == ($resource_count - 1)
														) {

															// If there are as many or fewer items than the max AND this is the penultimate item
															echo ' and ';

														}

													} elseif ( $resource_i == $resource_related_max ) {

														echo ' and more';
														break;

													}

												}

											} // endforeach;

											?>
										</dd>
										<?php

									} // endif

										$resource_i = 0;
										$resource_count = '';
										$associates = '';

								// Areas of Expertise

									if (
										$resource_expertises
										&&
										$resource_expertise_valid
									) {

										// Get system settings for area of expertise labels
										include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

										?>
										<dt><?php echo $resource_expertise_label; ?></dt>
										<dd>
											<?php

											$resource_i = 0;
											$resource_count = $resource_expertise_count;
											$associates = $resource_expertises;

											foreach ( $associates as $associate) {

												if ( get_post_status ( $associate ) == 'publish' ) {

													if ( $resource_i < $resource_related_max ) {

														$associate_title = get_the_title( $associate );
														$associate_title_attr = uamswp_attr_conversion($associate_title);

														echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $clinical_resource_title_attr . '">' . $associate_title . '</a>';

														$resource_i++;

														if ( 
															(
																$resource_count > $resource_related_max
																&&
																$resource_i != $resource_related_max
															)
															||
															(
																$resource_count > 1
																&&
																$resource_count <= $resource_related_max
																&&
																$resource_i < ($resource_count - 1)
															)
														) {

															// If there are more items than the max AND this is not the max item
															// OR If there are as many or fewer items than the max AND this is not the penultimate item
															echo ', ';

														} elseif (
															$resource_count > 1
															&&
															$resource_count <= $resource_related_max
															&&
															$resource_i == ($resource_count - 1)
														) {

															// If there are as many or fewer items than the max AND this is the penultimate item
															echo ' and ';

														}

													} elseif ( $resource_i == $resource_related_max ) {

														echo ' and more';
														break;

													}

												}

											} // endforeach;

											?>
										</dd>
										<?php

									} // endif

									$resource_i = 0;
									$resource_count = '';
									$associates = '';

								// Conditions

									if (
										$resource_conditions
										&&
										$resource_condition_valid
									) {

										// Get system settings for condition labels
										include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition.php' );

										?>
										<dt><?php echo $resource_condition_label; ?></dt>
										<dd>
											<?php

											$resource_i = 0;
											$resource_count = $resource_condition_count;
											$associates = $resource_conditions;

											foreach ( $associates as $associate) {

												if ( get_post_status ( $associate ) == 'publish' ) {

													if ( $resource_i < $resource_related_max ) {

														$associate_title = get_the_title( $associate );
														$associate_title_attr = uamswp_attr_conversion($associate_title);

														echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $clinical_resource_title_attr . '">' . $associate_title . '</a>';

														$resource_i++;

														if (
															(
																$resource_count > $resource_related_max
																&&
																$resource_i != $resource_related_max
															)
															||
															(
																$resource_count > 1
																&&
																$resource_count <= $resource_related_max
																&&
																$resource_i < ($resource_count - 1)
															)
														) {

															// If there are more items than the max AND this is not the max item
															// OR If there are as many or fewer items than the max AND this is not the penultimate item
															echo ', ';

														} elseif (
															$resource_count > 1
															&&
															$resource_count <= $resource_related_max
															&&
															$resource_i == ($resource_count - 1)
														) {

															// If there are as many or fewer items than the max AND this is the penultimate item
															echo ' and ';

														}

													} elseif ( $resource_i == $resource_related_max ) {

														echo ' and more';
														break;

													}

												}

											} // endforeach;

											?>
										</dd>
										<?php

									} // endif

									$resource_i = 0;
									$resource_count = '';
									$associates = '';

								// Treatments

									if (
										$resource_treatments
										&&
										$resource_treatment_valid
									) {

										// Get system settings for treatment labels
										include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/treatment.php' );

										?>
										<dt><?php echo $resource_treatment_label; ?></dt>
										<dd>
											<?php

											$resource_i = 0;
											$resource_count = $resource_treatment_count;
											$associates = $resource_treatments;

											foreach ( $associates as $associate) {

												if ( get_post_status ( $associate ) == 'publish' ) {

													if ( $resource_i < $resource_related_max ) {

														$associate_title = get_the_title( $associate );
														$associate_title_attr = uamswp_attr_conversion($associate_title);

														echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $clinical_resource_title_attr . '">' . $associate_title . '</a>';

														$resource_i++;

														if (
															(
																$resource_count > $resource_related_max
																&&
																$resource_i != $resource_related_max
															)
															||
															(
																$resource_count > 1
																&&
																$resource_count <= $resource_related_max
																&&
																$resource_i < ($resource_count - 1)
															)
														) {

															// If there are more items than the max AND this is not the max item
															// OR If there are as many or fewer items than the max AND this is not the penultimate item
															echo ', ';

														} elseif (
															$resource_count > 1
															&&
															$resource_count <= $resource_related_max
															&&
															$resource_i == ($resource_count - 1)
														) {

															// If there are as many or fewer items than the max AND this is the penultimate item
															echo ' and ';

														}

													} elseif ( $resource_i == $resource_related_max ) {

														echo ' and more';
														break;

													}

												}

											} // endforeach;

											?>
										</dd>
										<?php

									} // endif

									$resource_i = 0;
									$resource_count = '';
									$associates = '';

								?> 
							</dl>
							<?php

						// End Related Content
						?>
						<a class="btn btn-primary" href="<?php echo $clinical_resource_url; ?>" aria-label="<?php echo $clinical_resource_link_label; ?>" data-categorytitle="View Clinical Resource" data-itemtitle="<?php echo $clinical_resource_title_attr; ?>"><?php echo $clinical_resource_link_text; ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>