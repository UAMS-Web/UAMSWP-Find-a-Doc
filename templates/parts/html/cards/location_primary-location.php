<?php
/**
 * Template Name: Location Card, Primary Location Style
 *
 * Description: A template part that displays information about a provider's
 * primary location
 *
 * Must be used inside a loop
 *
 * Designed for UAMS Health Find-a-Doc
 *
 * Required vars:
 * 	$location_count // int // Number of locations associated with the current item
 *
 * Optional vars:
 * 	$location_descendant_list // bool (default: false) // Query for whether this is a list of child locations within a location
 */

// Check/define variables

	// Page ID
	$page_id = get_the_ID();

	// Get the field values for the card

		$location_card_fields_vars = ''; // Reset the variables
		$location_card_style = 'primary-location'; // Location card style
		include( UAMS_FAD_PATH . '/templates/parts/vars/page/cards/location.php' );

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

	$location_primary_heading = implode(
		' ',
		array_filter(
			array(
				'Primary',
				( $eligible_appt ? 'Appointment' : ''),
				$location_single_name
			)
		)
	);

// Construct the HTML

?>
<div data-sectiontitle="Primary Location">
	<h2 class="h3"><?php echo $location_primary_heading; ?></h2>
	<?php

	// Address values

		if ( $location_address_text ) {

			?>
			<p class="card-text"><?php echo $location_address_text; ?></p>
			<?php

		} // endif ( $location_address_text )

	// Phone values

		include( UAMS_FAD_PATH . '/templates/parts/html/contacts/phone-numbers_location-card.php' );

	// Button container

		?>
		<div class="btn-container">
			<a class="btn btn-primary" href="<?php echo $location_url; ?>" data-itemtitle="<?php echo $location_title_attr; ?>" data-categorytitle="View <?php echo $location_single_name_attr; ?>">
				View <?php echo $location_single_name; ?>
			</a>
			<?php

			if ( $location_count > 1 ) {

				?>
				<a class="btn btn-outline-primary" href="#locations" aria-label="Jump to list of <?php echo strtolower($location_plural_name_attr); ?> for this <?php echo strtolower($provider_single_name_attr); ?>" data-categorytitle="View All <?php echo $location_plural_name_attr; ?>">
					View All <?php echo $location_plural_name; ?>
				</a>
				<?php

			} // endif ( $location_count > 1 )

			?>
		</div>
</div><?php