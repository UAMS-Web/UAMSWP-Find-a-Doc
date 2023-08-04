<?php 
/**
 * Template Name: Location Loop - Card layout
 * 
 * Description: A template part that displays a location card to be included in a 
 * list of locations associated with the current page.
 * 
 * Must be used inside a loop
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	$location_single_name // System setting for Locations Plural Item Name
 * 	$location_single_name_attr // Attribute value friendly version of system setting for Locations single item name
 * 	$page_id
 * 
 * Optional vars:
 * 	$location_descendant_list // Query for whether this is a list of child locations within a location // bool (default: false)
 */

// Check/define variables

	// Get the attributes of the current location item

		// Page ID
		$page_id = get_the_ID();

		// Title

			$location_title = get_the_title($page_id);
			$location_title_attr = uamswp_attr_conversion($location_title);

	// Query on whether this card is in a list of descendant locations
	$location_descendant_list = isset($location_descendant_list) ? $location_descendant_list : false;

	// Get the attributes of the parent of the current location item

		// Eliminate PHP errors

			$location_parent_id = '';
			$location_has_parent = '';
			$parent_location = '';
			$parent_id = '';
			$parent_title = '';
			$parent_title_attr = '';
			$parent_url = '';
			$override_parent_photo = '';
			$override_parent_photo_featured = '';

		// Reset variables

			$featured_image = '';
			$address_id = $page_id;

		// Query on whether the current item has a parent
		$location_has_parent = get_field('location_parent', $page_id);

		// Parent ID
		$location_parent_id = get_field('location_parent_id', $page_id);

		// Get the parent post object
		if ( $location_has_parent && $location_parent_id ) {

			$parent_location = get_post($location_parent_id);

		}

		if ( $parent_location ) {

			// If the parent post object exists...

			// Parent ID
			$parent_id = $parent_location->ID;

			// Parent title

				$parent_title = $parent_location->post_title;
				$parent_title_attr = uamswp_attr_conversion($parent_title);

			// Parent URL
			$parent_url = get_permalink($parent_id);

			// Parent featured image
			$featured_image = get_the_post_thumbnail($parent_id, 'aspect-16-9-small', [ 'class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);

			// Set address ID using the parent ID
			$address_id = $parent_id;

			// Query on whether to override the photos using the parent's photos
			$override_parent_photo = get_field('location_image_override_parent', $page_id);

			// Query on whether to override the featured photo using the parent's featured photo
			$override_parent_photo_featured = get_field('location_image_override_parent_featured', $page_id);

			// Set featured image

				if (
					$override_parent_photo
					&&
					$override_parent_photo_featured
				) {

					$featured_image = get_the_post_thumbnail($page_id, 'aspect-16-9-small', [ 'class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);

				}

		} else {

			// Set featured image
			if ( has_post_thumbnail($page_id) ) {

				$featured_image = get_the_post_thumbnail($page_id, 'aspect-16-9-small', [ 'class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);

			}

		}

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-location.php' );

	// Get the address attributes of the relevant item

		// Street address (address line 1)
		$location_address_1 = get_field('location_address_1', $address_id );

		// Building

			$location_building = get_field('location_building', $address_id );

			if ( $location_building ) {

				$building = get_term($location_building, "building");
				$building_slug = $building->slug;
				$building_name = $building->name;

			}

		// Building floor

			$location_floor = get_field_object('location_building_floor', $address_id );

			// Eliminate PHP errors

				$location_floor_value = '';
				$location_floor_label = '';

			if ( $location_floor ) {
				$location_floor_value = $location_floor['value'];
				$location_floor_label = $location_floor['choices'][ $location_floor_value ];
			}

		// Suite/unit number

			$location_suite = get_field('location_suite', $address_id );

		// Construct address line 2

			$location_address_2 =
				( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? '<br />' : '' ) : '' )
				. ( $location_floor && !empty($location_floor_value) && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ', ' : '' ) : '' )
				. ( $location_suite ? $location_suite : '' );
			$location_address_2_deprecated = get_field('location_address_2', $address_id );

			// Construct the schema for address line 2
			$location_address_2_schema =
				( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? ' ' : '' ) : '' )
				. ( $location_floor && !empty($location_floor_value) && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ' ' : '' ) : '' )
				. ( $location_suite ? $location_suite : '' );

			// Fall back to the deprecated address line 2 field
			if ( !$location_address_2 ) {

				$location_address_2 = $location_address_2_deprecated;
				$location_address_2_schema = $location_address_2_deprecated;

			}

		// City
		$location_city = get_field('location_city', $address_id);

		// State
		$location_state = get_field('location_state', $address_id);

		// ZIP
		$location_zip = get_field('location_zip', $address_id);

		// GPS / Map
		$location_map = get_field('location_map', $address_id);

?>
<div class="card">
	<?php

	if ( $featured_image ) {

		echo $featured_image;

	} else {

		?>
		<picture>
			<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
			<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" data-categorytitle="Photo" data-itemtitle="<?php echo $location_title_attr; ?>" loading="lazy" />
		</picture>
		<?php

	} // endif ( $featured_image ) else

	?>
	<div class="card-body">
		<h3 class="card-title h5">
			<span class="name"><a href="<?php echo get_permalink($page_id); ?>" target="_self" data-categorytitle="Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $location_title; ?></a></span>
			<?php

			// Add subtitle referencing the parent location

				if (
					$parent_location
					&&
					!$location_descendant_list
				) {

					?>
					<span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $parent_url; ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
					<?php

				} // endif

			// Add subtitle indicating that it is the primary location

				if (
					isset($l)
					&&
					1 == $l
				) {

					?>
					<span class="subtitle"><span class="sr-only">, </span>Primary <?php echo $location_single_name; ?></span>
					<?php

				} // endif

			?>
		</h3>
		<?php 

		// Check if a closure alert should be displayed

			$location_closing = get_field('location_closing', $page_id); // true or false
			$location_closing_date = get_field('location_closing_date', $page_id); // F j, Y
			$location_closing_date_past = false;

			if (new DateTime() >= new DateTime($location_closing_date)) {

				$location_closing_date_past = true;

			} // endif

			$location_closing_length = get_field('location_closing_length', $page_id);
			$location_reopen_known = get_field('location_reopen_known', $page_id);
			$location_reopen_date = get_field('location_reopen_date', $page_id); // F j, Y
			$location_reopen_date_past = false;

			if ( new DateTime() >= new DateTime($location_reopen_date) ) {

				$location_reopen_date_past = true;

			} // endif

			$location_closing_info = get_field('location_closing_info', $page_id);
			$location_closing_display = false;

			if (
				$location_closing
				&&
				(
					$location_closing_length == 'permanent'
					||
					(
						$location_closing_length == 'temporary'
						&&
						!$location_reopen_date_past
					)
				)
			) {

				$location_closing_display = true;

			} // endif

		// Check if a modified hours alert should be displayed

			$location_hours_group = get_field('location_hours_group', $page_id);

			$modified = $location_hours_group['location_modified_hours'];
			$modified_start = $location_hours_group['location_modified_hours_start_date'];
			$modified_end = $location_hours_group['location_modified_hours_end'];
			$modified_end_date = $location_hours_group['location_modified_hours_end_date'];

			$today = strtotime("today");
			$today_30 = strtotime("+30 days");

			$telemedicine_modified = $location_hours_group['location_telemed_modified_hours_query']; // Are there modified hours for telemedicine?
			$telemedicine_modified_start = $location_hours_group['location_telemed_modified_hours_start_date']; // When do the modified telemedicine hours start?
			$telemedicine_modified_end = $location_hours_group['location_telemed_modified_hours_end']; // Do we know when the modified telemedicine hours end?
			$telemedicine_modified_end_date = $location_hours_group['location_telemed_modified_hours_end_date']; // When do the modified telemedicine hours end?

			$telemedicine_today = $today;
			$telemedicine_today_30 = $today_30;

			if ( 
				(
					$modified
					&&
					strtotime($modified_start) <= $today_30
					&&
					(
						strtotime($modified_end_date) >= $today
						||
						!$modified_end
					)
				)
				||
				(
					$telemedicine_modified
					&&
					strtotime($telemedicine_modified_start) <= $telemedicine_today_30
					&&
					(
						strtotime($telemedicine_modified_end_date) >= $telemedicine_today
						||
						!$telemedicine_modified_end
					) 
				)
			) {

				$location_modified_hours_display = true;

			} else {

				$location_modified_hours_display = false;

			} // endif

			// Set start of modified hours based on the earliest of the two (clinic and telemedicine)

			if ( $location_modified_hours_display ) {

				if (
					$modified
					&&
					$modified_start
					&&
					$telemedicine_modified
					&&
					$telemedicine_modified_start
				) {

					if ( strtotime($modified_start) <= strtotime($telemedicine_modified_start) ) {

						$location_modified_hours_start = $modified_start;

					} else {

						$location_modified_hours_start = $telemedicine_modified_start;

					} // endif

				} elseif ( $modified_start ) {

					$location_modified_hours_start = $modified_start;

				} elseif ( $telemedicine_modified_start ) {

					$location_modified_hours_start = $telemedicine_modified_start;

				} // endif

				if ( strtotime($location_modified_hours_start) <= $today ) {

					$location_modified_hours_date_past = true;

				} else {

					$location_modified_hours_date_past = false;

				} // endif

			}

		// Create the alert

			if (
				$location_closing_display
				||
				$location_modified_hours_display
			) {

				$alert_label = '';

				if ( $location_closing_display ) {

					$alert_label = 'Learn more about the closure of ' . $location_title_attr . '.';

				} elseif ( $location_modified_hours_display ) {

					$alert_label = 'Learn more about the modified hours.';

				} // endif

				$alert_label_attr = uamswp_attr_conversion($alert_label);

				?>
				<div class="alert alert-warning" role="alert">
					<?php

					if ( $location_closing_display ) {

						if ( $location_closing_date_past ) {

							?>
							This <?php echo strtolower($location_single_name); ?> is <?php echo $location_closing_length == 'temporary' ? 'temporarily' : 'permanently' ; ?> closed.
							<?php

						} else {

							?>
							This <?php echo strtolower($location_single_name); ?> will be closing <?php echo $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently' ; ?> on <?php echo $location_closing_date; ?>.
							<?php

						} // endif

					} elseif ( $location_modified_hours_display ) {

						if ($location_modified_hours_date_past) {

							?>
							This <?php echo strtolower($location_single_name); ?>'s hours have been temporarily modified.
							<?php

						} else {

							?>
							This <?php echo strtolower($location_single_name); ?>'s hours will be temporarily modified beginning on <?php echo $location_modified_hours_start; ?>.
							<?php

						} // endif

					} // endif

					?>
					<p><a href="<?php echo get_permalink($page_id); ?>" aria-label="<?php echo $alert_label_attr; ?>" class="alert-link" data-categorytitle="Alert" data-itemtitle="<?php echo $location_title_attr; ?>">Learn more</a></p>
				</div><?php // end div.alert

			} // endif

		// Address values

			?>
			<p class="card-text"><?php
				echo $location_address_1 . '<br/>';
				echo ( $location_address_2 ? $location_address_2 . '<br/>' : '' );
				echo $location_city . ', ' . $location_state . ' ' . $location_zip;
			?></p>
			<?php 

		// Phone values

			$phone_output_id = $page_id;
			$phone_output = 'associated_locations';
			include( UAMS_FAD_PATH . '/templates/blocks/locations-phone.php' );

		?>
	</div><?php // end div.card-body ?>
	<div class="btn-container">
		<div class="inner-container">
			<a href="<?php echo get_permalink($page_id); ?>" class="btn btn-primary" aria-label="View <?php echo strtolower($location_single_name_attr); ?> page for <?php echo $location_title_attr; ?>" data-categorytitle="View Location" data-itemtitle="<?php echo $location_title_attr; ?>">View <?php echo $location_single_name; ?></a>
			<?php

			if ( $location_map ) {

				?>
				<a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $location_map['lat'] ?>,<?php echo $location_map['lng'] ?>" target="_blank" aria-label="Get Directions to <?php echo $location_title; ?>" data-categorytitle="Get Directions" data-itemtitle="<?php echo $location_title_attr; ?>">Get Directions</a>
				<?php

			} // endif

			?>
		</div>
	</div><?php // end div.btn-container ?>
</div><?php // end div.card ?>