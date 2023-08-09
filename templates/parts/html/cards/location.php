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
 * 	$location_single_name // string // System setting for Locations Plural Item Name
 * 	$location_single_name_attr // string // Attribute value friendly version of system setting for Locations single item name
 * 	$page_id // int
 * 
 * Optional vars:
 * 	$location_descendant_list // bool (default: false) // Query for whether this is a list of child locations within a location
 */

// Check/define variables

	// Page ID
	$page_id = get_the_ID();

	// Get the field values for the card

		$location_card_fields_vars = ''; // Reset the variables
		$location_card_style = 'basic'; // Location card style
		include( UAMS_FAD_PATH . '/templates/parts/vars/page/cards/location.php' );

// Construct the card

?>
<div class="card">
	<?php

	if ( $location_featured_image_url ) {

		?>
		<img src="<?php echo $location_featured_image_url; ?>" itemprop="image" class="card-img-top" alt="<?php echo $location_title_attr; ?>" data-categorytitle="Photo" data-itemtitle="<?php echo $location_title_attr; ?>" loading="lazy" />
		<?php

	} else {

		?>
		<picture>
			<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
			<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" data-categorytitle="Photo" data-itemtitle="<?php echo $location_title_attr; ?>" loading="lazy" />
		</picture>
		<?php

	} // endif ( $location_featured_image_url ) else

	?>
	<div class="card-body">
		<h3 class="card-title h5">
			<span class="name"><a href="<?php echo $location_url; ?>" target="_self" data-categorytitle="Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $location_title; ?></a></span>
			<?php

			// Add subtitle referencing the parent location

				if (
					$location_parent_object
					&&
					!$location_descendant_list
				) {

					?>
					<span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $location_parent_url; ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $location_parent_title; ?></a><span class="sr-only">)</span></span>
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
					<p><a href="<?php echo $location_url; ?>" aria-label="<?php echo $alert_label_attr; ?>" class="alert-link" data-categorytitle="Alert" data-itemtitle="<?php echo $location_title_attr; ?>">Learn more</a></p>
				</div><?php // end div.alert

			} // endif

		// Address values

			if ( $location_address_text ) {
				
				?>
				<p class="card-text"><?php echo $location_address_text; ?></p>
				<?php 

			}

		// Phone values

			$phone_output_id = $page_id;
			$phone_output = 'associated_locations';
			include( UAMS_FAD_PATH . '/templates/parts/html/contacts/phone-numbers_location.php' );

		?>
	</div><?php // end div.card-body ?>
	<div class="btn-container">
		<div class="inner-container">
			<a href="<?php echo $location_url; ?>" class="btn btn-primary" aria-label="View <?php echo strtolower($location_single_name_attr); ?> page for <?php echo $location_title_attr; ?>" data-categorytitle="View Location" data-itemtitle="<?php echo $location_title_attr; ?>">View <?php echo $location_single_name; ?></a>
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