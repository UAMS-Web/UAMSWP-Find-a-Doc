<?php
/**
 * Template Name: Location Card, Basic Style
 *
 * Description: A template part that displays a card containing basic information
 * about a location and links to the location's profile
 *
 * Must be used inside a loop
 *
 * Designed for UAMS Health Find-a-Doc
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

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

// Add to related location schema

	// Base list array

		if (
			isset($LocalBusiness_list)
			&&
			!empty($LocalBusiness_list)
			&&
			is_array($LocalBusiness_list)
		) {

			$LocalBusiness_list = array_is_list($LocalBusiness_list) ? $LocalBusiness_list : array($LocalBusiness_list);

		} else {

			$LocalBusiness_list = array();

		}

	// Pre-existing field values array

		$LocalBusiness_fields = ( isset($LocalBusiness_fields) && !empty($LocalBusiness_list) && is_array($LocalBusiness_fields) ) ? $LocalBusiness_fields : array();

		$LocalBusiness_fields[$page_id] = array(
			'LocalBusiness_address_1' => $location_address_1,
			'LocalBusiness_addressLocality' => $location_city,
			'LocalBusiness_addressRegion' => $location_state,
			'LocalBusiness_building' => $location_building,
			'LocalBusiness_building_name' => $location_building_name,
			'LocalBusiness_featured_image_id' => $location_featured_image,
			'LocalBusiness_floor_label' => $location_floor_label,
			'LocalBusiness_floor_value' => $location_floor_value,
			'LocalBusiness_geo_value' => $location_map,
			'LocalBusiness_has_parent' => $location_has_parent,
			'LocalBusiness_name' => $location_title_attr,
			'LocalBusiness_parent_id' => $location_parent_id,
			'LocalBusiness_postalCode' => $location_zip,
			'LocalBusiness_suite' => $location_suite,
			'LocalBusiness_url' => $location_url
		);

	// Construct the schema for this location

		$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

		if ( function_exists('uamswp_fad_schema_location') ) {

			$MedicalWebPage_i = $MedicalWebPage_i ?? 1; // Iteration counter for location-as-MedicalWebPage
			$LocalBusiness_i = $LocalBusiness_i ?? 1; // Iteration counter for location-as-LocalBusiness

			$LocalBusiness_list = $LocalBusiness_list + uamswp_fad_schema_location(
				array($page_id), // array // Required // List of IDs of the location items
				$location_url, // string // Required // Page URL
				$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
				1, // int // Optional // Nesting level within the main schema
				$MedicalWebPage_i, // int // Optional // Iteration counter for location-as-MedicalWebPage
				$LocalBusiness_i, // int // Optional // Iteration counter for location-as-LocalBusiness
				$LocalBusiness_fields, // array // Optional // Pre-existing field values array so duplicate calls can be avoided
				array(), // array // Optional // Pre-existing list array for location-as-MedicalWebPage to which to add additional items
				$LocalBusiness_list // array // Optional // Pre-existing list array for location-as-LocalBusiness to which to add additional items
			);

		} else {

			$LocalBusiness_list = null;

		}

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

				if ( $location_parent_display ) {

					?>
					<span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $location_parent_url; ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $location_parent_title; ?></a><span class="sr-only">)</span></span>
					<?php

				} // endif ( $location_parent_display )

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

		// Construct the location alert

			if ( $alert_message ) {

				?>
				<div class="alert alert-warning" role="alert">
					<p><?php echo $alert_message; ?></p>
					<p><a href="<?php echo $location_url . ( $location_closing_display ? '#closing-info' : '' ); ?>" aria-label="<?php echo $alert_label_attr; ?>" class="alert-link" data-categorytitle="Alert" data-itemtitle="<?php echo $location_title_attr; ?>">Learn more</a></p>
				</div><?php // end div.alert

			} // endif ( $alert_message )

		// Address values

			if ( $location_address_text ) {

				?>
				<p class="card-text"><?php echo $location_address_text; ?></p>
				<?php

			} // endif ( $location_address_text )

		// Phone values

			$phone_output_id = $page_id;
			include( UAMS_FAD_PATH . '/templates/parts/html/contacts/phone-numbers_location-card.php' );

		?>
	</div><?php // end div.card-body ?>
	<div class="btn-container">
		<div class="inner-container">
			<a href="<?php echo $location_url; ?>" class="btn btn-primary" aria-label="View <?php echo strtolower($location_single_name_attr); ?> page for <?php echo $location_title_attr; ?>" data-categorytitle="View Location" data-itemtitle="<?php echo $location_title_attr; ?>">View <?php echo $location_single_name; ?></a>
			<?php

			if ( $location_directions_url ) {

				?>
				<a class="btn btn-outline-primary" href="<?php echo $location_directions_url; ?>" target="_blank" aria-label="Get Directions to <?php echo $location_title; ?>" data-categorytitle="Get Directions" data-itemtitle="<?php echo $location_title_attr; ?>">Get Directions</a>
				<?php

			} // endif

			?>
		</div>
	</div><?php // end div.btn-container ?>
</div><?php // end div.card ?>