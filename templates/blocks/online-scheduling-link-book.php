<?php
/**
 * 	Template Name: Online Scheduling, MyChart Open Scheduling Widget Modal Toggles
 * 	Designed for UAMS Find-a-Doc
 * 
 * 	Required vars
 * 		$scheduling_mychart_book_group // ACF field containing the inputs relevant to MyChart open scheduling widget configuration
 * 		$scheduling_mychart_type // Which Type(s) of MyChart Open Scheduling?
 * 		$scheduling_template
 * 			(
 * 				'single-location'
 * 				'single-provider'
 * 			)
 * 
 * 	Required vars from single location template:
 * 		$scheduling_mychart_book_options // MyChart Open Scheduling Widget Option(s) for Appointment Booking
 * 		$scheduling_mychart_book_dropdown // Display the Single Appointment Booking Visit Type in a Dropdown?
 * 
 * 	Required vars from single provider template:
 * 		$scheduling_mychart_book_visit_type // Visit Type(s) from UAMS Health Epic for Appointment Booking
 * 	
 */

// Count visit types
$scheduling_mychart_book_options = isset($scheduling_mychart_book_options) ? $scheduling_mychart_book_options : '';
$scheduling_mychart_book_visit_type = isset($scheduling_mychart_book_visit_type) ? $scheduling_mychart_book_visit_type : '';
$scheduling_mychart_book_count = '';
if ( $scheduling_mychart_book_options ) {
	$scheduling_mychart_book_count = count( $scheduling_mychart_book_options );
} elseif ( $scheduling_mychart_book_visit_type ) {
	$scheduling_mychart_book_count = count( $scheduling_mychart_book_visit_type );
}

// Get the system setting for the button text
$scheduling_mychart_book_button_text = $scheduling_mychart_book_group['mychart_scheduling_book_btn_text_system'] ?: 'Book an Appointment';

// Set the dropdown query variable
if (
	$scheduling_template == 'single-provider' // If it is on the single provider template
	||
	$scheduling_mychart_book_count > 1 // If there is more than one visit type selected
) {
	$scheduling_mychart_book_dropdown = true;
} else {
	$scheduling_mychart_book_dropdown = isset($scheduling_mychart_book_dropdown) ? $scheduling_mychart_book_dropdown : true;
}

// Create the dropdown/link element
if (
	$scheduling_mychart_book_options // Single Location: MyChart open scheduling widget options for Appointment Booking is not empty
	||
	$scheduling_mychart_book_visit_type // Single Provider: At least one Visit Type has been selected
) {
	if ( $scheduling_mychart_book_dropdown ) { // If dropdown should be displayed ?>
		<div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" type="button" id="mychart_scheduling_dropdown" data-toggle="dropdown" aria-expanded="false"><?php echo $scheduling_mychart_book_button_text; ?></button>
			<div class="dropdown-menu" aria-labelledby="mychart_scheduling_dropdown">
				<?php 
				
				$i = 0;
				$options = $scheduling_mychart_book_options ?: $scheduling_mychart_book_visit_type;
				
				// Begin looping through the visit types

				foreach ( $options as $option ) {
					// Load sub field value.
					$visit_type = $option['location_scheduling_vt'] ?: $option; // Get ID of location's visit type. Otherwise, get ID of provider's visit type.
					$visit_type_object = get_term_by( 'id', $visit_type, 'mychart_visit_type'); // Get visit type taxonomy object based on the selected ID
					
					// Do something...
					if ( $visit_type_object ) { // If the object exists...
						// Get Open Scheduling Widget Link Text
						$visit_type_link_text = get_field('mychart_visit_type_link_text', $visit_type_object);

						// Create the modal link
						?>
						<a class="dropdown-item" href="#" data-toggle="modal" data-target="#mychart-scheduling_<?php echo $i; ?>"><?php echo $visit_type_link_text; ?></a>
					<?php } // endif ( $visit_type_object )
					$i++;
				}

				// End looping through the visit types
				
				?>
			</div>
		</div>
	<?php } else { // Otherwise, no dropdown should be displayed
		
		$i = 0;
		$options = $scheduling_mychart_book_options ?: $scheduling_mychart_book_visit_type;
		
		// Begin looping through the visit types
		
		foreach ( $options as $option ) {
			// Load sub field value.
			$visit_type = $option['location_scheduling_vt'] ?: $option; // Get ID of location's visit type. Otherwise, get ID of provider's visit type.
			$visit_type_object = get_term_by( 'id', $visit_type, 'mychart_visit_type'); // Get visit type taxonomy object based on the selected ID
			
			// Do something...
			if ( $visit_type_object ) { // If the object exists...
				// Get Open Scheduling Widget Link Text
				$visit_type_link_text = get_field('mychart_visit_type_link_text', $visit_type_object);

				// Create the modal link
				?>
				<a class="btn btn-primary" href="#" data-toggle="modal" data-target="#mychart-scheduling_<?php echo $i; ?>"><?php echo $scheduling_mychart_book_button_text; ?></a>
			<?php } // endif ( $visit_type_object )
			$i++;
		}

		// End looping through the visit types

	} // endif ( $scheduling_mychart_book_dropdown ) else
} // endif ( $scheduling_mychart_book_options || $scheduling_mychart_book_visit_type) ?>