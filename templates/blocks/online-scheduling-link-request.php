<?php
/**
 * 	Template Name: Online Scheduling Information, Appointment Request
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
 * 		$scheduling_request_btn_style // Define whether the appointment request button is solid or outline
 * 	
 * 	Required vars from single location template:
 * 		$parent_slug
 * 		$scheduling_group // ACF field containing the inputs relevant to MyChart open scheduling and appointment request forms
 */

if ( $show_scheduling_request_section ) {

	// Get values from from Find-a-Doc Settings for Appointment Request Forms
	$scheduling_request_group_sys = get_field('appointment_request_group', 'option'); // ACF field containing the inputs relevant to Appointment Requests
	if ( $scheduling_template == 'single-location' ) {
		$scheduling_request_heading_standalone = $scheduling_request_group_sys['appointment_request_location_heading_standalone_system'];
		$scheduling_request_descr_standalone = $scheduling_request_group_sys['appointment_request_location_descr_standalone_system'];
		$scheduling_request_heading_nested = $scheduling_request_group_sys['appointment_request_location_heading_nested_system'];
		$scheduling_request_descr_nested = $scheduling_request_group_sys['appointment_request_location_descr_nested_system'];
	} elseif ( $scheduling_template == 'single-provider' ) {
		$scheduling_request_heading_standalone = $scheduling_request_group_sys['appointment_request_provider_heading_standalone_system'];
		$scheduling_request_descr_standalone = $scheduling_request_group_sys['appointment_request_provider_descr_standalone_system'];
		$scheduling_request_heading_nested = $scheduling_request_group_sys['appointment_request_provider_heading_nested_system'];
		$scheduling_request_descr_nested = $scheduling_request_group_sys['appointment_request_provider_descr_nested_system'];
	}
	$scheduling_request_heading_standalone = isset($scheduling_request_heading_standalone) ? $scheduling_request_heading_standalone : 'Appointments';
	$scheduling_request_descr_standalone = isset($scheduling_request_descr_standalone) ? $scheduling_request_descr_standalone : 'Appointments for specialized care cannot be scheduled online. For those, submit a request for an appointment.';
	$scheduling_request_heading_nested = isset($scheduling_request_heading_nested) ? $scheduling_request_heading_nested : 'Specialized Care';
	$scheduling_request_descr_nested = isset($scheduling_request_descr_nested) ? $scheduling_request_descr_nested : 'Some appointments involve specialized care and cannot be scheduled online. For those, submit a request for an appointment.';

	// Begin Content
	if ( $scheduling_appointments_count > 1 ) {
		echo '<h3 class="h5">' . $scheduling_request_heading_nested . '</h3>';
		echo '<p>' . $scheduling_request_descr_nested . '</p>';
	} else {
		echo '<h2 class="h4">' . $scheduling_request_heading_standalone . '</h2>';
		echo '<p>' . $scheduling_request_descr_standalone . '</p>';
	}

	?>
	<div class="btn-container">
		<div class="inner-container">
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-request-button.php' ); ?>
		</div>
	</div>
<?php } // endif ( $show_scheduling_request_section ) 

?>