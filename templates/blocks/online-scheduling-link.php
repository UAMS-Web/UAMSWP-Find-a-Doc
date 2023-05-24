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


// Count number of appointment subsections
$scheduling_appointments_count = $show_scheduling_mychart_book_section + $show_scheduling_request_section;


// Get values from from Find-a-Doc Settings input group labeled "Appointment Booking"
if ( $show_scheduling_mychart_book_section ) {
	$scheduling_mychart_book_group_sys = get_field('mychart_scheduling_book_group', 'option'); // ACF field containing the inputs relevant to Appointment Booking
	if ( $scheduling_template == 'single-location' ) {
		$scheduling_mychart_book_heading_standalone = $scheduling_mychart_book_group_sys['mychart_scheduling_book_location_heading_standalone_system'];
		$scheduling_mychart_book_descr_standalone = $scheduling_mychart_book_group_sys['mychart_scheduling_book_location_descr_standalone_system'];
		$scheduling_mychart_book_heading_nested = $scheduling_mychart_book_group_sys['mychart_scheduling_book_location_heading_nested_system'];
		$scheduling_mychart_book_descr_nested = $scheduling_mychart_book_group_sys['mychart_scheduling_book_location_descr_nested_system'] ?: $scheduling_mychart_book_descr_standalone;
	} elseif ( $scheduling_template == 'single-provider' ) {
		$scheduling_mychart_book_heading_standalone = $scheduling_mychart_book_group_sys['mychart_scheduling_book_provider_heading_standalone_system'];
		$scheduling_mychart_book_descr_standalone = $scheduling_mychart_book_group_sys['mychart_scheduling_book_provider_descr_standalone_system'];
		$scheduling_mychart_book_heading_nested = $scheduling_mychart_book_group_sys['mychart_scheduling_book_provider_heading_nested_system'];
		$scheduling_mychart_book_descr_nested = $scheduling_mychart_book_group_sys['mychart_scheduling_book_provider_descr_nested_system'] ?: $scheduling_mychart_book_descr_standalone;
	}
}
$scheduling_mychart_book_heading_standalone = isset($scheduling_mychart_book_heading_standalone) ? $scheduling_mychart_book_heading_standalone : 'Appointments Care';
$scheduling_mychart_book_descr_standalone = isset($scheduling_mychart_book_descr_standalone) ? $scheduling_mychart_book_descr_standalone : 'Book an appointment online';
$scheduling_mychart_book_heading_nested = isset($scheduling_mychart_book_heading_nested) ? $scheduling_mychart_book_heading_nested : '';
$scheduling_mychart_book_descr_nested = isset($scheduling_mychart_book_descr_nested) ? $scheduling_mychart_book_descr_nested : $scheduling_mychart_book_descr_standalone;


// Get values from from Find-a-Doc Settings input group labeled "Visit Pre-Registration"
if ( $show_scheduling_mychart_book_section ) {
	$scheduling_mychart_preregister_group_sys = get_field('mychart_scheduling_preregister_group', 'option'); // ACF field containing the inputs relevant to Visit Pre-Registration
	if ( $scheduling_template == 'single-location' ) {
		$scheduling_mychart_preregister_heading_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_location_heading_standalone_system'];
		$scheduling_mychart_preregister_descr_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_location_descr_standalone_system'];
		$scheduling_mychart_preregister_heading_nested = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_location_heading_nested_system'];
		$scheduling_mychart_preregister_descr_nested = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_location_descr_nested_system'];
	} elseif ( $scheduling_template == 'single-provider' ) {
		$scheduling_mychart_preregister_heading_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_provider_heading_standalone_system'];
		$scheduling_mychart_preregister_descr_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_provider_descr_standalone_system'];
		$scheduling_mychart_preregister_heading_nested = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_provider_heading_nested_system'];
		$scheduling_mychart_preregister_descr_nested = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_provider_descr_nested_system'];
	}
}
$scheduling_mychart_preregister_heading_standalone = isset($scheduling_mychart_preregister_heading_standalone) ? $scheduling_mychart_preregister_heading_standalone : 'Immediate Care';
$scheduling_mychart_preregister_descr_standalone = isset($scheduling_mychart_preregister_descr_standalone) ? $scheduling_mychart_preregister_descr_standalone : 'Spend less time waiting and get home faster by choosing an available time.';
$scheduling_mychart_preregister_heading_nested = isset($scheduling_mychart_preregister_heading_nested) ? $scheduling_mychart_preregister_heading_nested : $scheduling_mychart_preregister_heading_standalone;
$scheduling_mychart_preregister_descr_nested = isset($scheduling_mychart_preregister_descr_nested) ? $scheduling_mychart_preregister_descr_nested : $scheduling_mychart_preregister_descr_standalone;


// Get values from from Find-a-Doc Settings for Appointment Request Forms
if ( $show_scheduling_request_section ) {
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
}
$scheduling_request_heading_standalone = isset($scheduling_request_heading_standalone) ? $scheduling_request_heading_standalone : 'Appointments';
$scheduling_request_descr_standalone = isset($scheduling_request_descr_standalone) ? $scheduling_request_descr_standalone : 'Appointments for specialized care cannot be scheduled online. For those, submit a request for an appointment.';
$scheduling_request_heading_nested = isset($scheduling_request_heading_nested) ? $scheduling_request_heading_nested : 'Specialized Care';
$scheduling_request_descr_nested = isset($scheduling_request_descr_nested) ? $scheduling_request_descr_nested : 'Some appointments involve specialized care and cannot be scheduled online. For those, submit a request for an appointment.';


// Determine heading class levels
// General heading
$scheduling_general_heading_class_rank = $info_section_heading_class_rank;
$scheduling_general_heading_class = 'h' . $scheduling_general_heading_class_rank;
// Appointment Booking subsection
$scheduling_mychart_book_heading_class_rank = ( $scheduling_appointments_count > 1 && $scheduling_mychart_book_heading_nested ) ? $info_section_heading_class_rank + 1 : $info_section_heading_class_rank;
$scheduling_mychart_book_heading_class = 'h' . $scheduling_mychart_book_heading_class_rank;
// Appointment Booking subsection
$scheduling_request_heading_class_rank = ( $scheduling_appointments_count > 1 && ( $scheduling_mychart_book_heading_nested || $scheduling_template == 'single-location' ) ) ? $info_section_heading_class_rank + 1 : $info_section_heading_class_rank;
$scheduling_request_heading_class = 'h' . $scheduling_request_heading_class_rank;
// Visit Pre-Registration subsection
$scheduling_mychart_preregister_heading_class_rank = $info_section_heading_class_rank;
$scheduling_mychart_preregister_heading_class = 'h' . $scheduling_mychart_preregister_heading_class_rank;

// Begin Appointments Section

$scheduling_heading_system = get_field('scheduling_heading_system', 'option'); // Heading for Online Scheduling Section

if ( $scheduling_template == 'single-location' ) {

	// Begin Location Profile Appointment Information Content
	
	if ( $scheduling_appointments_count > 1 ) { // If there are multiple Appointment subsections 
		// Display the general Appointments heading
		if ( $scheduling_mychart_book_heading_nested ) { ?>
			<h2 class="<?php echo $scheduling_general_heading_class; ?>"><?php echo $scheduling_heading_system; ?></h2>
		<?php }
	}

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

	// End Location Profile Appointment Information Content
	
} elseif ( $scheduling_template == 'single-provider' ) {
	
	// Begin Provider Profile Appointment Information Content

	if ( $scheduling_appointments_count > 1) { ?>
		<?php if ( $scheduling_mychart_book_heading_nested ) { ?>
			<div class="col-12">
				<h2 class="<?php echo $scheduling_general_heading_class; ?>"><?php echo $scheduling_heading_system; ?></h2>
				<div class="row">
		<?php } ?>
		<div class="col-12 col-lg-6">
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-book.php' ); ?>
		</div>
		<div class="col-12 col-lg-6">
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-request.php' ); ?>
		</div>
		<?php if ( $scheduling_mychart_book_heading_nested ) { ?>
				</div><?php // end div.row ?>
			</div><?php // end div.col-12 ?>
		<?php } ?>
	<?php } else { ?>
		<div class="col-12 col-lg-6">
			<h2 class="<?php echo $scheduling_general_heading_class; ?>"><?php echo $scheduling_heading_system; ?></h2>
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-book.php' ); ?>
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-request.php' ); ?>
		</div>
	<?php }

	// End Provider Profile Appointment Information Content

}

?>