<?php
/**
 * 	Template Name: Online Scheduling, Links to Appointment Request Forms
 * 	Designed for UAMS Find-a-Doc
 * 	
 * 	Required vars:
 * 		$scheduling_request_forms // Appointment Request Form(s)
 * 		$scheduling_request_btn_style // Define whether the appointment request button is solid or outline
 * 		$scheduling_request_utm_medium_val
 * 			(
 * 				Expected values:
 * 					single-provider
 * 					archive-provider
 * 					single-location
 * 					archive-location
 * 					single-expertise
 * 					archive-expertise
 * 					single-condition
 * 					archive-condition
 * 					single-treatment
 * 					archive-treatment
 * 					single-clinical-resource
 * 					archive-clinical-resource
 * 			)
 * 		$scheduling_request_utm_content_val
 * 			(
 * 				Expected values:
 * 					Slug of provider whose card or profile the link was on (e.g., leonard-h-mccoy)
 * 					Slug of location whose card or profile the link was on (e.g., that-place)
 * 						If child location, prepend slug of parent and underscore (e.g., parent-place_child-place)
 * 			)
 */

$scheduling_request_form_count = count($scheduling_request_forms);
$scheduling_request_button_text = 'Request an Appointment';

// Create query string for UTM tracking
$scheduling_request_utm_arr = []; // Create empty array for use in UTM construction
$scheduling_request_utm_source_val = 'uamshealth_organic'; // Set value for utm_source URL parameter
if ($scheduling_request_utm_source_val) { // If value exists for URL parameter
	$scheduling_request_utm_arr[] = 'utm_source=' . $scheduling_request_utm_source_val; // Add URL parameter to UTM construction array
}
$scheduling_request_utm_medium_val = isset($scheduling_request_utm_medium_val) ? $scheduling_request_utm_medium_val : ''; // Verify value exists for utm_medium URL parameter
if ($scheduling_request_utm_medium_val) { // If value exists for URL parameter
	$scheduling_request_utm_arr[] = 'utm_medium=' . $scheduling_request_utm_medium_val; // Add URL parameter to UTM construction array
}
$scheduling_request_utm_campaign_val = 'clinical_service_request-appointment'; // Set value for utm_campaign URL parameter
if ($scheduling_request_utm_campaign_val) { // If value exists for URL parameter
	$scheduling_request_utm_arr[] = 'utm_campaign=' . $scheduling_request_utm_campaign_val; // Add URL parameter to UTM construction array
}
$scheduling_request_utm_content_val = isset($scheduling_request_utm_content_val) ? $scheduling_request_utm_content_val : ''; // Verify value exists for utm_content URL parameter
if ($scheduling_request_utm_content_val) { // If value exists for URL parameter
	$scheduling_request_utm_arr[] = 'utm_content=' . $scheduling_request_utm_content_val; // Add URL parameter to UTM construction array
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
if ( $scheduling_request_form_count > 1 ) { ?>
	<div class="dropdown">
		<button class="btn <?php echo $scheduling_request_btn_style; ?>-primary dropdown-toggle" type="button" id="appt_request_form_dropdown" data-toggle="dropdown" aria-expanded="false"><?php echo $scheduling_request_button_text; ?></button>
		<div class="dropdown-menu" aria-labelledby="appt_request_form_dropdown">
			<?php foreach( $scheduling_request_forms as $form ) {
				$form_object = get_term_by( 'id', $form, 'appointment_request');
				if ( $form_object ) {
					$form_object_name = $form_object->name;
					$form_object_name_attr = str_replace('"', '\'', $form_object_name);
					$form_object_name_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($form_object_name_attr, null, 'utf-8')));
					$form_url = get_field('appointment_request_url', $form_object) . $scheduling_request_query_string;
					?>
					<a class="dropdown-item" href="<?php echo $form_url; ?>" target="_blank"><?php echo $form_object_name; ?></a>
				<?php }
			} ?>
		</div>
	</div>
<?php } else {
	foreach( $scheduling_request_forms as $form ) {
		$form_object = get_term_by( 'id', $form, 'appointment_request');
		if ( $form_object ) {
			$form_object_name = $form_object->name;
			$form_object_name_attr = str_replace('"', '\'', $form_object_name);
			$form_object_name_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($form_object_name_attr, null, 'utf-8')));
			$form_url = get_field('appointment_request_url', $form_object) . $scheduling_request_query_string;
			?>
			<a class="btn <?php echo $scheduling_request_btn_style; ?>-primary" href="<?php echo $form_url; ?>" target="_blank" aria-label="<?php echo $scheduling_request_button_text . ', ' . $form_object_name_attr; ?>"><?php echo $scheduling_request_button_text; ?></a>
		<?php }
	}
} ?>