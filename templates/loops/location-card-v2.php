<?php 
/**
 * 	Template Name: Location Loop - Card layout
 * 	Designed for UAMS Find-a-Doc
 * 	
 * 	Must be used inside a loop
 * 	
 * 	Required vars:
 * 		$id
 */

// Reset variables
$featured_image = '';
$address_id = $id;

// $child_location_list indicates whether this is a list of child locations within this location
// Check if $child_location_list is set. Otherwise create the variable and set its value to false.
$child_location_list = isset($child_location_list) ? $child_location_list : false;

$location_title = get_the_title($id);
$location_title_attr = str_replace('"', '\'', $location_title);
$location_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($location_title_attr, null, 'utf-8')));

// Parent Location 
$location_has_parent = get_field('location_parent', $id);
$location_parent_id = get_field('location_parent_id', $id);
$parent_location = '';
$parent_id = '';
$parent_title = '';
$parent_url = '';
$override_parent_photo = '';
$override_parent_photo_featured = '';

if ($location_has_parent && $location_parent_id) { 
	$parent_location = get_post( $location_parent_id );
}
// Get Post ID for Address & Image fields
if ($parent_location) {
	$parent_id = $parent_location->ID;
	$parent_title = $parent_location->post_title;
	$parent_title_attr = str_replace('"', '\'', $parent_title);
	$parent_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($parent_title_attr, null, 'utf-8')));
	$parent_url = get_permalink( $parent_id );
	$featured_image = get_the_post_thumbnail($parent_id, 'aspect-16-9-small', [ 'class' => 'card-img', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);
	$address_id = $parent_id;

	$override_parent_photo = get_field('location_image_override_parent', $id);
	$override_parent_photo_featured = get_field('location_image_override_parent_featured', $id);
	
	// Set featured image
	if ( $override_parent_photo && $override_parent_photo_featured ) {
		$featured_image = get_the_post_thumbnail($id, 'aspect-16-9-small', [ 'class' => 'card-img', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);
	}
} else {
	// Set featured image
	if ( has_post_thumbnail($id) ) {
		$featured_image = get_the_post_thumbnail($id, 'aspect-16-9-small', [ 'class' => 'card-img', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);
	}
}
										
$location_address_1 = get_field('location_address_1', $address_id );
$location_building = get_field('location_building', $address_id );
if ($location_building) {
	$building = get_term($location_building, "building");
	$building_slug = $building->slug;
	$building_name = $building->name;
}
$location_floor = get_field_object('location_building_floor', $address_id );
	$location_floor_value = '';
	$location_floor_label = '';
	if ( $location_floor && is_object($location_floor) ) {
		$location_floor_value = $location_floor['value'];
		$location_floor_label = $location_floor['choices'][ $location_floor_value ];
	}
$location_suite = get_field('location_suite', $address_id );
$location_address_2 =
	( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? '<br />' : '' ) : '' )
	. ( $location_floor && !empty($location_floor_value) && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ', ' : '' ) : '' )
	. ( $location_suite ? $location_suite : '' );
$location_address_2_schema =
	( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? ' ' : '' ) : '' )
	. ( $location_floor && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ' ' : '' ) : '' )
	. ( $location_suite ? $location_suite : '' );

$location_address_2_deprecated = get_field('location_address_2', $address_id );
if (!$location_address_2) {
	$location_address_2 = $location_address_2_deprecated;
	$location_address_2_schema = $location_address_2_deprecated;
}

$location_city = get_field('location_city', $address_id);
$location_state = get_field('location_state', $address_id);
$location_zip = get_field('location_zip', $address_id);

$parent_location_display_query = $parent_location && !$child_location_list;

$primary_location_display_query = isset($l) && 1 == $l;
$region_display_query = false;
$supertitle_display_query = $primary_location_display_query || $region_display_query;

$expertise_display_query = false;
$subtitle_display_query = $expertise_display_query;

// ----------
// Begin Card
// ----------
?>
<div class="card">
	<div class="card-body">
		<?php
		// ------------------
		// Begin Card Heading
		// ------------------
		?>
		<div class="card-title">
			<h3 class="h5"><span class="name"><a href="<?php echo get_permalink($id); ?>" target="_self" data-categorytitle="Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $location_title; ?></a></span><?php
				if ( $parent_location_display_query ) {
					?> <span class="sr-only">(</span><span class="subtitle">Part of <a href="<?php echo $parent_url; ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span><?php
				}
			?></h3>
			<?php if ( $supertitle_display_query ) { ?>
				<span class="note supertitle"><?php
					if ( $primary_location_display_query ) {
						?><span class="supertitle-item">Primary Location</span><?php
					}
					if ( $region_display_query ) {
						?><span class="supertitle-item">Northwest Arkansas</span><?php
					}
				?></span>
			<?php } ?>
			<?php if ( $subtitle_display_query ) { ?>
				<span class="note subtitle"><?php
					if ( $expertise_display_query ) {
						?><span class="subtitle-item">List of Areas of Expertise</span><?php
					}
				?></span>
			<?php } // endif ?>
		</div>
		<?php
		// ----------------
		// End Card Heading
		// ----------------
		
		// -----------
		// Begin Alert
		// -----------

		// Define variables
		
		//// Check for if we should display a closure alert
		
		$location_closing = get_field('location_closing', $id); // true or false
		$location_closing_date = get_field('location_closing_date', $id); // F j, Y
		$location_closing_date_past = false;
		if (new DateTime() >= new DateTime($location_closing_date)) {
			$location_closing_date_past = true;
		}
		$location_closing_length = get_field('location_closing_length', $id);
		$location_reopen_known = get_field('location_reopen_known', $id);
		$location_reopen_date = get_field('location_reopen_date', $id); // F j, Y
		$location_reopen_date_past = false;
		if (new DateTime() >= new DateTime($location_reopen_date)) {
			$location_reopen_date_past = true;
		}
		$location_closing_info = get_field('location_closing_info', $id);
		$location_closing_display = false;
		if (
			$location_closing && (
				$location_closing_length == 'permanent'
				|| ($location_closing_length == 'temporary' && !$location_reopen_date_past)
				)
			) {
			$location_closing_display = true;
		}

		//// Check for if we should display a modified hours alert

		$location_hours_group = get_field('location_hours_group', $id);

		$modified = $location_hours_group['location_modified_hours'];
		$modified_start = $location_hours_group['location_modified_hours_start_date'];
		$modified_end = $location_hours_group['location_modified_hours_end'];
		$modified_end_date = $location_hours_group['location_modified_hours_end_date'];

		$today = strtotime("today");
		$today_30 = strtotime("+30 days");

		$telemed_modified = $location_hours_group['location_telemed_modified_hours_query']; // Are there modified hours for telemedicine?
		$telemed_modified_start = $location_hours_group['location_telemed_modified_hours_start_date']; // When do the modified telemedicine hours start?
		$telemed_modified_end = $location_hours_group['location_telemed_modified_hours_end']; // Do we know when the modified telemedicine hours end?
		$telemed_modified_end_date = $location_hours_group['location_telemed_modified_hours_end_date']; // When do the modified telemedicine hours end?

		$telemed_today = $today;
		$telemed_today_30 = $today_30;

		if ( 
			( $modified && strtotime($modified_start) <= $today_30 && ( strtotime($modified_end_date) >= $today || !$modified_end ) ) ||
			( $telemed_modified && strtotime($telemed_modified_start) <= $telemed_today_30 && ( strtotime($telemed_modified_end_date) >= $telemed_today || !$telemed_modified_end ) )
		) {
			$location_modified_hours_display = true;
		} else {
			$location_modified_hours_display = false;
		}

		//// Set start of modified hours based on the earliest of the two (clinic and telemedicine)

		if ($location_modified_hours_display) {
			if ( ($modified && $modified_start) && ($telemed_modified && $telemed_modified_start) ) {
				if ( strtotime($modified_start) <= strtotime($telemed_modified_start) ) {
					$location_modified_hours_start = $modified_start;
				} else {
					$location_modified_hours_start = $telemed_modified_start;
				}
			} elseif ($modified_start) {
				$location_modified_hours_start = $modified_start;
			} elseif ($telemed_modified_start) {
				$location_modified_hours_start = $telemed_modified_start;
			}

			if ( strtotime($location_modified_hours_start) <= $today ) {
				$location_modified_hours_date_past = true;
			} else {
				$location_modified_hours_date_past = false;
			}
		}   

		// Create the alert
		
		if ( $location_closing_display || $location_modified_hours_display ) { 
			$alert_label = '';
			if ($location_closing_display) {
				$alert_label = 'Learn more about the closure of ' . $location_title_attr . '.';
			} elseif ($location_modified_hours_display) {
				$alert_label = 'Learn more about the modified hours.';
			}
			$alert_label_attr = str_replace('"', '\'', $alert_label);
			$alert_label_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($alert_label_attr, null, 'utf-8')));
			?>
			<div class="text-subsection">
				<div class="row">
					<div class="col">
						<div class="alert alert-warning" role="alert">
							<?php if ($location_closing_display) {
								if ($location_closing_date_past) { ?>
									This location is <?php echo $location_closing_length == 'temporary' ? 'temporarily' : 'permanently' ; ?> closed.
								<?php } else { ?>
									This location will be closing <?php echo $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently' ; ?> on <?php echo $location_closing_date; ?>.
								<?php } // endif
							} elseif ($location_modified_hours_display) {
								if ($location_modified_hours_date_past) { ?>
									This location's hours have been temporarily modified.
								<?php } else { ?>
									This location's hours will be temporarily modified beginning on <?php echo $location_modified_hours_start; ?>.
								<?php } // endif
							} // endif ?>
							<p><a href="<?php echo get_permalink($id); ?>" aria-label="<?php echo $alert_label_attr; ?>" class="alert-link" data-categorytitle="Alert" data-itemtitle="<?php echo $location_title_attr; ?>">Learn more</a></p>
						</div>
					</div>
				</div>

			</div>
		<?php } // endif
		
		// -----------
		// End Alert
		// -----------
		
		// -------------------------------------
		// Begin Address and Contact Information
		// -------------------------------------
		?>
		<div class="text-subsection">
			<div class="row">
				<?php
				// -------------
				// Begin Address
				// -------------

				$map = get_field('location_map', $address_id);
				$show_parking_section = false; // WIP — Check the location to see if it has a parking section
				?>
				<div class="col-12 col-md-6">
					<p class="address"><?php echo $location_address_1; ?><br/>
						<?php echo $location_address_2 ? $location_address_2 . '<br/>' : ''; ?>
						<?php echo $location_city . ', ' . $location_state . ' ' . $location_zip; ?>
					</p>
					<?php if ( $map || $show_parking_section) { ?>
						<ul class="location-address-links">
							<?php if ( $map ) { ?>
								<li><a href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get directions to <?php echo $page_title; ?>" data-typetitle="Get directions to the clinic"><span class="link-text">Get Directions</span><span class="fas fa-map link-icon"></span></span></a></li>
							<?php } ?>
							<?php if ($show_parking_section) { ?>
								<li><a href="<?php echo get_permalink($id); ?>#parking-info" aria-label="Parking instructions for <?php echo $page_title; ?>" data-typetitle="Parking instructions for the clinic"><span class="link-text">Parking Instructions</span><span class="fas fa-car link-icon"></span></span></a></li>
							<?php } // endif $show_parking_section ?>
						</ul>
					<?php } ?>
				</div>
				<?php
				// -----------
				// End Address
				// -----------

				// -------------------------
				// Begin Contact Information
				// -------------------------

				// Phone values
				$phone_output_id = $id;
				$phone_output = 'associated_locations';
				?>
				<div class="col-12 col-md-6">
					<?php include( UAMS_FAD_PATH . '/templates/blocks/locations-phone.php' ); ?>
				</div>
				<?php
				// -----------------------
				// End Contact Information
				// -----------------------
				?>
			</div>
		</div>
		<?php
		// -----------------------------------
		// End Address and Contact Information
		// -----------------------------------

		// ----------------------
		// Begin Button Container
		// ----------------------

		$show_online_scheduling_link_book = true; // WIP — Check the location to see if it has appointment booking enabled
		$show_online_scheduling_link_request = false; // WIP — Check the location to see if it has appointment requests enabled
		?>
		<div class="btn-container">
			<div class="inner-container">
				<a href="<?php echo get_permalink($id); ?>" class="btn btn-outline-primary" aria-label="View location page for <?php echo $location_title_attr; ?>" data-categorytitle="View Location" data-itemtitle="<?php echo $location_title_attr; ?>">View Location</a>
				<?php
				if ( $show_online_scheduling_link_book ) {
					$scheduling_mychart_book_options = ''; // WIP — Check the location and provider profiles to get this value
					include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-book-button.php' );
				} elseif ( $show_online_scheduling_link_request ) {
					$scheduling_request_forms = ''; // WIP — Check the location profile to get this value
					include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-request-button.php' );
				}
				?>
			</div>
		</div>
		<?php
		// --------------------
		// End Button Container
		// --------------------
		?>
	</div>
	<?php
	// -------------
	// End Card Body
	// -------------

	// ----------------
	// Begin Card Image
	// ----------------
	?>
	<?php if ( $featured_image && true == false ) { ?>
		<picture>
			<?php echo $featured_image; ?>
		</picture>
	<?php } else { ?>
		<picture>
			<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_1-1.svg" media="(min-width: 992px)">
			<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
			<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img" data-categorytitle="Photo" data-itemtitle="<?php echo $location_title_attr; ?>" loading="lazy" />
		</picture>
	<?php } // endif ?>
	<?php
	// --------------
	// End Card Image
	// --------------
	?>
</div>
<?php
// --------
// End Card
// --------
?>