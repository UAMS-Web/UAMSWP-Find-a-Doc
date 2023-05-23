<?php
/**
 * 	Template Name: Online Scheduling Information
 * 	Designed for UAMS Find-a-Doc
 * 	
 * 	Required vars:
 * 		$scheduling_group // ACF field containing the inputs relevant to MyChart open scheduling and appointment request forms
 * 		$show_scheduling_mychart_book_section // Check if appointment booking section should be displayed
 * 		$scheduling_mychart_book_group_sys // ACF field containing the inputs relevant to Appointment Booking
 * 		$scheduling_mychart_type // Which Type(s) of MyChart Open Scheduling?
 * 		$show_scheduling_request_section // Check if link(s) to appointment request form(s) should be displayed
 * 		$scheduling_request_forms // Appointment Request Form(s)
 * 		$scheduling_request_btn_style // Define whether the appointment request button is solid or outline
 * 		$show_scheduling_mychart_preregister_section
 * 		$scheduling_mychart_preregister_group_sys // ACF field containing the inputs relevant to Visit Pre-Registration
 * 		$scheduling_template
 * 			(
 * 				'single-location'
 * 				'single-provider'
 * 			)
 * 		$page_slug
 * 		$scheduling_mychart_book_options // MyChart Open Scheduling Widget Option(s) for Appointment Booking
 * 	
 * 	Required vars from single location template:
 * 		$parent_slug
 * 		$scheduling_mychart_preregister_options // MyChart Open Scheduling Widget Option(s) for Visit Pre-Registration
 * 	
 * 	Required vars from single provider template:
 * 		$scheduling_mychart_preregister_visit_type
 */

// Check variables
$parent_slug = isset($parent_slug) ? $parent_slug : '';
$scheduling_mychart_book_options = isset($scheduling_mychart_book_options) ? $scheduling_mychart_book_options : '';
$scheduling_mychart_preregister_options = isset($scheduling_mychart_preregister_options) ? $scheduling_mychart_preregister_options : '';
$scheduling_mychart_preregister_visit_type = isset($scheduling_mychart_preregister_visit_type) ? $scheduling_mychart_preregister_visit_type : '';


// Begin Appointments Section

$scheduling_appointments_count = $show_scheduling_mychart_book_section + $show_scheduling_request_section; // Count number of appointment subsections

$scheduling_heading_system = get_field('scheduling_heading_system', 'option'); // Heading for Online Scheduling Section

if ( $scheduling_appointments_count > 1 ) { // If there are multiple Appointment subsections 
	// Display the general Appointments heading ?>
	<h2 class="h4"><?php echo $scheduling_heading_system; ?></h2>
<?php }


// Begin Appointment Booking Subsection

include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-book.php' );

// End Appointment Booking Subsection


// Begin Appointment Request Subsection

include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-request.php' );

// End Appointment Request Subsection

// End Appointments Section


// Begin Visit Pre-Registration Section

include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-preregister.php' );

// End Visit Pre-Registration Section

?>