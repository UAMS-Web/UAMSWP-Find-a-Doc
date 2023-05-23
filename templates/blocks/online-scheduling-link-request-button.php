<?php
/**
 * 	Template Name: Online Scheduling Information, Appointment Request, Button
 * 	Designed for UAMS Find-a-Doc
 * 	
 * 	Required vars:
 * 		$page_slug
 * 		$scheduling_request_forms // Appointment Request Form(s)
 * 		$scheduling_request_btn_style // Define whether the appointment request button is solid or outline
 * 		$scheduling_request_group_sys // ACF field containing the inputs relevant to Appointment Requests
 *		$scheduling_template
 *			(
 *				'single-location'
 *				'single-provider'
 *			)
 * 	
 * 	Required vars from single location template:
 * 		$parent_slug
 * 		$scheduling_group // ACF field containing the inputs relevant to MyChart open scheduling and appointment request forms
 */

// Count the number of selected appointment request forms
$scheduling_request_form_count = count($scheduling_request_forms);

// Get the system setting for the button text
$scheduling_request_button_text = $scheduling_request_group_sys['appointment_request_btn_text_system'] ?: 'Request an Appointment';

// Determine whether to display Appointment Request visit types in a dropdown
if ( $scheduling_template == 'single-location' && $scheduling_request_form_count == 1 ) { // If it is on the single location template and there is only one visit type
	$scheduling_request_form_dropdown = $scheduling_group['location_scheduling_mychart_book_dropdown']; // Get the value from the location profile input
} else {
	$scheduling_request_form_dropdown = true;
}

// Create query string for UTM tracking
$scheduling_request_utm_arr = []; // Create empty array for use in UTM construction
$scheduling_request_utm_source_val = 'uamshealth_organic'; // Set value for utm_source URL parameter
if ($scheduling_request_utm_source_val) { // If value exists for utm_source URL parameter
	$scheduling_request_utm_arr[] = 'utm_source=' . $scheduling_request_utm_source_val; // Add utm_source URL parameter to UTM construction array
}
$scheduling_request_utm_medium_val = $scheduling_template; // Set value for utm_medium URL parameter
if ($scheduling_request_utm_medium_val) { // If value exists for utm_medium URL parameter
	$scheduling_request_utm_arr[] = 'utm_medium=' . $scheduling_request_utm_medium_val; // Add utm_medium URL parameter to UTM construction array
}
$scheduling_request_utm_campaign_val = 'clinical_service_request-appointment'; // Set value for utm_campaign URL parameter
if ($scheduling_request_utm_campaign_val) { // If value exists for utm_campaign URL parameter
	$scheduling_request_utm_arr[] = 'utm_campaign=' . $scheduling_request_utm_campaign_val; // Add utm_campaign URL parameter to UTM construction array
}
$scheduling_request_utm_content_val = $parent_slug ? $parent_slug . '_' . $page_slug : $page_slug; // Set value for utm_content URL parameter
if ($scheduling_request_utm_content_val) { // If value exists for utm_content URL parameter
	$scheduling_request_utm_arr[] = 'utm_content=' . $scheduling_request_utm_content_val; // Add utm_content URL parameter to UTM construction array
}
$scheduling_request_utm_arr_count = count($scheduling_request_utm_arr); // Count elements in UTM construction array
$scheduling_request_query_string = ''; // Create empty variable for query string construction
if ( $scheduling_request_utm_arr_count > 0 ) { // If there are elements in the UTM construction array
	$i = 0;
	$scheduling_request_query_string .= '?'; // Start the query string
	// Loop through the UTM construction array
	foreach( $scheduling_request_utm_arr as $param ) {
		$scheduling_request_query_string .= $param; // Add the URL parameter to the query string
		$i++;
		$scheduling_request_query_string .= ($i < $scheduling_request_utm_arr_count) ? '&' : ''; // If this is not the last URL parameter, add "&" to the query string
	}
}

// Create the dropdown/link element
if ( $scheduling_request_forms ) { // If at least form has been selected
	if ( $scheduling_request_form_dropdown ) { // If dropdown should be displayed ?>
		<div class="dropdown">
			<button class="btn <?php echo $scheduling_request_btn_style; ?>-primary dropdown-toggle" type="button" id="appt_request_form_dropdown" data-toggle="dropdown" aria-expanded="false"><?php echo $scheduling_request_button_text; ?></button>
			<div class="dropdown-menu" aria-labelledby="appt_request_form_dropdown">
				<?php

				// Begin looping through the forms
				
				foreach ( $scheduling_request_forms as $form ) {
					$form_object = get_term_by( 'id', $form, 'appointment_request'); // Get appointment request taxonomy object based on the selected ID
					if ( $form_object ) { // If the object exists...
						$form_object_name = $form_object->name;
						$form_object_name_attr = str_replace('"', '\'', $form_object_name);
						$form_object_name_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($form_object_name_attr, null, 'utf-8')));
						$form_url = get_field('appointment_request_url', $form_object) . $scheduling_request_query_string;
						?>
						<a class="dropdown-item" href="<?php echo $form_url; ?>" target="_blank"><?php echo $form_object_name; ?></a>
					<?php } // endif ( $form_object )
				}

				// End looping through the forms
				
				?>
			</div>
		</div>
	<?php } else { // Otherwise, no dropdown should be displayed
		
		// Begin looping through the forms
		
		foreach ( $scheduling_request_forms as $form ) {
			$form_object = get_term_by( 'id', $form, 'appointment_request'); // Get appointment request taxonomy object based on the selected ID
			if ( $form_object ) { // If the object exists...
				$form_object_name = $form_object->name;
				$form_object_name_attr = str_replace('"', '\'', $form_object_name);
				$form_object_name_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($form_object_name_attr, null, 'utf-8')));
				$form_url = get_field('appointment_request_url', $form_object) . $scheduling_request_query_string;
				?>
				<a class="btn <?php echo $scheduling_request_btn_style; ?>-primary" href="<?php echo $form_url; ?>" target="_blank" aria-label="<?php echo $scheduling_request_button_text . ', ' . $form_object_name_attr; ?>"><?php echo $scheduling_request_button_text; ?></a>
			<?php } // endif ( $form_object )
		}

		// End looping through the forms
		
	} // endif ( $scheduling_request_form_dropdown ) else
} // endif ( $scheduling_request_forms ) ?>