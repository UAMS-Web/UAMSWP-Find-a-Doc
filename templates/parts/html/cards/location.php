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
			$phone_output = 'associated_locations';
			include( UAMS_FAD_PATH . '/templates/parts/html/contacts/phone-numbers_location-profile.php' );

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