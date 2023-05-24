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

	// Begin Content
	if ( $scheduling_appointments_count > 1 ) {
		echo '<h3 class="' . $scheduling_request_heading_class . '">' . $scheduling_request_heading_nested . '</h3>';
		echo '<p>' . $scheduling_request_descr_nested . '</p>';
	} else {
		echo '<h2 class=' . $scheduling_request_heading_class . '>' . $scheduling_request_heading_standalone . '</h2>';
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