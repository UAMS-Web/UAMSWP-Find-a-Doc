<?php
/**
 * 	Template Name: Online Scheduling, MyChart Open Scheduling Widget Modal Toggles
 * 	Designed for UAMS Find-a-Doc
 * 
 * 	Required vars:
 * 		$show_scheduling_request_section // Check if link(s) to appointment request form(s) should be displayed
 * 		$scheduling_appointments_count // Count number of appointment subsections
*		$scheduling_template
*			(
*				'single-location'
*				'single-provider'
*			)
 * 		$page_slug
 * 		$scheduling_request_forms // Appointment Request Form(s)
 * 		$scheduling_request_form_dropdown // Display the Single Appointment Request Form in a Dropdown?
 * 		$scheduling_request_btn_style // Define whether the appointment request button is solid or outline
 */

if ( $show_scheduling_request_section ) {
	
	// Check variables
	$parent_slug = isset($parent_slug) ? $parent_slug : '';

	// Get values from from Find-a-Doc Settings for Appointment Request Forms
	$scheduling_request_heading_standalone = get_field('appointment_request_heading_standalone_system', 'option') ?: 'Appointments';
	$scheduling_request_intro_standalone = get_field('appointment_request_descr_standalone_system', 'option') ?: 'Appointments for specialized care at this location cannot be scheduled online. For those, submit a request for an appointment.';
	$scheduling_request_heading_nested = get_field('appointment_request_heading_nested_system', 'option') ?: 'Specialized Care';
	$scheduling_request_intro_nested = get_field('appointment_request_descr_nested_system', 'option') ?: 'Some appointments at this location involve specialized care and cannot be scheduled online. For those, submit a request for an appointment.';

	// Begin Content
	if ( $scheduling_appointments_count > 1 ) {
		echo '<h3 class="h5">' . $scheduling_request_heading_nested . '</h3>';
		echo '<p>' . $scheduling_request_intro_nested . '</p>';
	} else {
		echo '<h2 class="h4">' . $scheduling_request_heading_standalone . '</h2>';
		echo '<p>' . $scheduling_request_intro_standalone . '</p>';
	}

	$scheduling_request_utm_medium_val = $scheduling_template;
	$scheduling_request_utm_content_val = $parent_slug ? $parent_slug . '_' . $page_slug : $page_slug;
	?>
	<div class="btn-container">
		<div class="inner-container">
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-request-button.php' ); ?>
		</div>
	</div>
<?php } // endif ( $show_scheduling_request_section ) 

?>