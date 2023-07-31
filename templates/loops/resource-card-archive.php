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

	$page_id = get_the_ID();

	if (
		!isset($provider_single_name)
		||
		!isset($provider_plural_name)
	) {
		$labels_provider_vars = isset($labels_provider_vars) ? $labels_provider_vars : uamswp_fad_labels_provider();
			$provider_single_name = $labels_provider_vars['provider_single_name']; // string
			$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string
	}
	if (
		!isset($location_single_name)
		||
		!isset($location_plural_name)
	) {
		$labels_location_vars = isset($labels_location_vars) ? $labels_location_vars : uamswp_fad_labels_location();
			$location_single_name = $labels_location_vars['location_single_name']; // string
			$location_plural_name = $labels_location_vars['location_plural_name']; // string
	}
	if (
		!isset($expertise_single_name)
		||
		!isset($expertise_plural_name)
	) {
		$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
			$expertise_single_name = $labels_expertise_vars['expertise_single_name']; // string
			$expertise_plural_name = $labels_expertise_vars['expertise_plural_name']; // string
	}
	if ( !isset($clinical_resource_single_name) ) {
		$labels_clinical_resource_vars = isset($labels_clinical_resource_vars) ? $labels_clinical_resource_vars : uamswp_fad_labels_clinical_resource();
			$clinical_resource_single_name = $labels_clinical_resource_vars['clinical_resource_single_name']; // string
	}
	if (
		!isset($condition_single_name)
		||
		!isset($condition_plural_name)
	) {
		$labels_condition_vars = isset($labels_condition_vars) ? $labels_condition_vars : uamswp_fad_labels_condition();
			$condition_single_name = $labels_condition_vars['condition_single_name']; // string
			$condition_plural_name = $labels_condition_vars['condition_plural_name']; // string
	}
	if (
		!isset($treatment_single_name)
		||
		!isset($treatment_plural_name)
	) {
		$labels_treatment_vars = isset($labels_treatment_vars) ? $labels_treatment_vars : uamswp_fad_labels_treatment();
			$treatment_single_name = $labels_treatment_vars['treatment_single_name']; // string
			$treatment_plural_name = $labels_treatment_vars['treatment_plural_name']; // string
	}

$resource_title = get_the_title($page_id);
$resource_title_attr = uamswp_attr_conversion($resource_title);

$resource_type = get_field('clinical_resource_type', $page_id);
$resource_type_value = $resource_type['value'];
$resource_type_label = $resource_type['label'];

// Build an array of resource type values (keys) with the corresponding button text (values)
$resource_button_text_arr = array(
	'text' => 'Read the Article',
	'infographic' => 'View the Infographic',
	'video' => 'Watch the Video',
	'doc' => 'Read the Document'
);
$resource_button_text = 'View the ' . $clinical_resource_single_name; // Set fallback button text
if ( isset($resource_type) && isset($resource_button_text_arr[$resource_type_value]) ) {
	// IF resource type is set...
	// AND IF the resource type value is in $resource_button_text_arr...
	$resource_button_text = $resource_button_text_arr[$resource_type_value]; // Set the button text from the corresponding value from the array
}
$resource_button_text_attr = uamswp_attr_conversion($resource_button_text);
$resource_label = $resource_button_text_attr . ', ' . $resource_title_attr;

$resource_excerpt = get_the_excerpt($page_id) ? get_the_excerpt($page_id) : wp_strip_all_tags( get_the_content($page_id) );
$resource_excerpt_len = strlen($resource_excerpt);
if ( $resource_excerpt_len > 160 ) {
	$resource_excerpt = wp_trim_words( $resource_excerpt, 23, ' &hellip;' );
}

$resource_image_wide = get_post_thumbnail_id($page_id);
$resource_image_square = get_field('clinical_resource_image_square', $page_id);
$resource_image_square = ( isset($resource_image_square) && !empty($resource_image_square) ) ? $resource_image_square : $resource_image_wide;

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

?>
<div class="col item-container">
	<div class="item">
		<div class="row">
			<div class="col image">
				<a href="<?php echo get_permalink($page_id); ?>" aria-label="<?php echo $resource_label; ?>" data-categorytitle="Photo" data-itemtitle="<?php echo $resource_title_attr; ?>">
					<picture>
						<?php if ( has_post_thumbnail() && function_exists( 'fly_add_image_size' ) ) { ?>
							<source srcset="<?php echo image_sizer($resource_image_square, 243, 243, 'center', 'center'); ?>"
								media="(min-width: 2054px)">
							<source srcset="<?php echo image_sizer($resource_image_square, 184, 184, 'center', 'center'); ?>"
								media="(min-width: 1784px)">
							<source srcset="<?php echo image_sizer($resource_image_square, 243, 243, 'center', 'center'); ?>"
								media="(min-width: 1200px)">
							<source srcset="<?php echo image_sizer($resource_image_square, 184, 184, 'center', 'center'); ?>"
								media="(min-width: 930px)">
							<source srcset="<?php echo image_sizer($resource_image_wide, 580, 326, 'center', 'center'); ?>"
								media="(min-width: 768px)">
							<source srcset="<?php echo image_sizer($resource_image_square, 95, 95, 'center', 'center'); ?>"
								media="(min-width: 576px)">
							<source srcset="<?php echo image_sizer($resource_image_wide, 510, 286, 'center', 'center'); ?>"
								media="(min-width: 1px)">
							<img src="<?php echo image_sizer($resource_image_wide, 510, 286, 'center', 'center'); ?>" alt="" role="presentation" />
						<?php } elseif ( has_post_thumbnail() ) { ?>
							<?php the_post_thumbnail( 'medium', array( 'itemprop' => 'image', 'alt' => '', 'role' => 'presentation' ) ); ?>
						<?php } else { ?>
							<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
							<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" />
						<?php } ?>
					</picture>
				</a>
			</div>
			<div class="col text">
				<div class="row">
					<div class="col-12 primary">
						<h3 class="h4">
							<a href="<?php echo get_permalink($page_id); ?>" aria-label="<?php echo $resource_label; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $resource_title_attr; ?>">
								<span class="name"><?php echo $resource_title; ?></span>
							</a>
							<span class="subtitle"><span class="sr-only"> (</span><?php echo esc_html($resource_type_label); ?><span class="sr-only">)</span></span>
						</h3>
						<p><?php echo $resource_excerpt; ?></p>
						<a class="btn btn-primary" href="<?php echo get_permalink($page_id); ?>" aria-label="<?php echo $resource_label; ?>" data-categorytitle="View Clinical Resource" data-itemtitle="<?php echo $resource_title_attr; ?>"><?php echo $resource_button_text; ?></a>
					</div>
					<div class="col-12 secondary">
						<h4 class="h5">Related Content</h4>
						<dl>
							<?php if( $resource_providers && $resource_provider_valid ) { ?>
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

												echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $resource_title_attr . '">' . $associate_title . '</a>';
												$resource_i++;
												if ( 
													( $resource_count > $resource_related_max && $resource_i != $resource_related_max )
													|| ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i < ($resource_count - 1) )
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
									} // endforeach; ?>
								</dd>
							<?php } // endif
								$resource_i = 0;
								$resource_count = '';
								$associates = '';
							?> 
							<?php if( $resource_locations && $resource_location_valid ) { ?>
								<dt><?php echo $resource_location_label; ?></dt>
								<dd>
									<?php
									$resource_i = 0;
									$resource_count = $resource_location_count;
									$associates = $resource_locations;
									foreach( $associates as $associate) {
										if ( get_post_status ( $associate ) == 'publish' ) {
											if ( $resource_i < $resource_related_max ) {
												$associate_title = get_the_title( $associate );
												$associate_title_attr = uamswp_attr_conversion($associate_title);

												echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $resource_title_attr . '">' . $associate_title . '</a>';
												$resource_i++;
												if ( 
													( $resource_count > $resource_related_max && $resource_i != $resource_related_max )
													|| ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i < ($resource_count - 1) )
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
									} // endforeach; ?>
								</dd>
							<?php } // endif
								$resource_i = 0;
								$resource_count = '';
								$associates = '';
							?> 
							<?php if( $resource_conditions && $resource_condition_valid ) { ?>
								<dt><?php echo $resource_condition_label; ?></dt>
								<dd>
									<?php
									$resource_i = 0;
									$resource_count = $resource_condition_count;
									$associates = $resource_conditions;
									foreach( $associates as $associate) {
										if ( get_post_status ( $associate ) == 'publish' ) {
											if ( $resource_i < $resource_related_max ) {
												$associate_title = get_the_title( $associate );
												$associate_title_attr = uamswp_attr_conversion($associate_title);

												echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $resource_title_attr . '">' . $associate_title . '</a>';
												$resource_i++;
												if ( 
													( $resource_count > $resource_related_max && $resource_i != $resource_related_max )
													|| ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i < ($resource_count - 1) )
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
									} // endforeach; ?>
								</dd>
							<?php } // endif
								$resource_i = 0;
								$resource_count = '';
								$associates = '';
							?> 
							<?php if( $resource_treatments && $resource_treatment_valid ) { ?>
								<dt><?php echo $resource_treatment_label; ?></dt>
								<dd>
									<?php
									$resource_i = 0;
									$resource_count = $resource_treatment_count;
									$associates = $resource_treatments;
									foreach( $associates as $associate) {
										if ( get_post_status ( $associate ) == 'publish' ) {
											if ( $resource_i < $resource_related_max ) {
												$associate_title = get_the_title( $associate );
												$associate_title_attr = uamswp_attr_conversion($associate_title);

												echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $resource_title_attr . '">' . $associate_title . '</a>';
												$resource_i++;
												if ( 
													( $resource_count > $resource_related_max && $resource_i != $resource_related_max )
													|| ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i < ($resource_count - 1) )
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
									} // endforeach; ?>
								</dd>
							<?php } // endif
								$resource_i = 0;
								$resource_count = '';
								$associates = '';
							?> 
							<?php if( $resource_expertises && $resource_expertise_valid ) { ?>
								<dt><?php echo $resource_expertise_label; ?></dt>
								<dd>
									<?php
									$resource_i = 0;
									$resource_count = $resource_expertise_count;
									$associates = $resource_expertises;
									foreach( $associates as $associate) {
										if ( get_post_status ( $associate ) == 'publish' ) {
											if ( $resource_i < $resource_related_max ) {
												$associate_title = get_the_title( $associate );
												$associate_title_attr = uamswp_attr_conversion($associate_title);

												echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $resource_title_attr . '">' . $associate_title . '</a>';
												$resource_i++;
												if ( 
													( $resource_count > $resource_related_max && $resource_i != $resource_related_max )
													|| ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i < ($resource_count - 1) )
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
									} // endforeach; ?>
								</dd>
							<?php } // endif
								$resource_i = 0;
								$resource_count = '';
								$associates = '';
							?> 
						</dl>
						<a class="btn btn-primary" href="<?php echo get_permalink($page_id); ?>" aria-label="<?php echo $resource_label; ?>" data-categorytitle="View Clinical Resource" data-itemtitle="<?php echo $resource_title_attr; ?>"><?php echo $resource_button_text; ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>