<?php
/**
*	Template Name: Online Scheduling Display Logic
*	Designed for UAMS Find-a-Doc
*	
*	Required vars:
*		$scheduling_template
*			(
*				'single-location'
*				'single-provider'
*			)
*		$scheduling_query // Can a patient schedule an appointment for services rendered at this location?
*		$scheduling_mychart_query // Enable UAMS Health MyChart Open Scheduling for This Location?
*		$scheduling_request_query // Enable Linking to Appointment Request Forms?
*		$scheduling_request_forms // Appointment Request Form(s)
*		$scheduling_mychart_book_options // MyChart open scheduling widget options for Appointment Booking
* 
*	Required vars from single location template:
*		$scheduling_mychart_type // MyChart open scheduling type
*			(
*				'book'
*				'preregister'
*			)
*		$scheduling_mychart_preregister_options // MyChart open scheduling widget options for Visit Pre-Registration
* 
*	Optional vars from single location template:
*		$location_ac_query // Check if location is an Arkansas Children's location
*/

// Check optional vars from single location template
$location_ac_query = isset($location_ac_query) ? $location_ac_query : '';

// Check optional vars from single provider template
$scheduling_mychart_book_options = isset($scheduling_mychart_book_options) ? $scheduling_mychart_book_options : '';

// Get system setting for whether MyChart Open Scheduling is enabled
$scheduling_mychart_query_system = get_field('mychart_scheduling_query_system', 'option');

// Query multiple settings to make a single determination on whether MyChart open scheduling is enabled for this location or provider
$scheduling_mychart_query = (
	$scheduling_mychart_query_system // MyChart open scheduling is allowed at the system level
	&& $scheduling_mychart_query // MyChart open scheduling is allowed at the location / provider level
	&& $scheduling_query // Patients can schedule an appointment for services rendered at this location or with this provider
	) ? TRUE : FALSE;

// Check if MyChart Open Scheduling section should be displayed
if (
	$scheduling_mychart_query
	&& (
		// Location-specific check
		(
			$scheduling_template == 'single-location'
			&& !$location_ac_query // Location is not an Arkansas Children's location
			&& (
				(
					in_array('book', $scheduling_mychart_type) // MyChart open scheduling type is set to Appointment Booking
					&& $scheduling_mychart_book_options // MyChart open scheduling widget options for Appointment Booking is not empty
				)
				||
				(
					in_array('preregister', $scheduling_mychart_type) // MyChart open scheduling type is set to Visit Pre-Registration
					&& $scheduling_mychart_preregister_options // MyChart open scheduling widget options for Visit Pre-Registration is not empty
				)
			)
		)
		||
		// Provider-specific check
		(
			$scheduling_template == 'single-provider'
			&& isset($scheduling_mychart_book_options)
			&& !empty($scheduling_mychart_book_options)
		)
	)
) {
	$show_scheduling_mychart_section = true;
} else {
	$show_scheduling_mychart_section = false;
}

// Check if appointment booking section should be displayed
if (
	$scheduling_mychart_query
	&& in_array('book', $scheduling_mychart_type) // MyChart open scheduling is set to Appointment Booking
	&& $scheduling_mychart_book_options // MyChart open scheduling widget options for Appointment Booking is not empty
) {
	$show_scheduling_mychart_book_section = true;
} else {
	$show_scheduling_mychart_book_section = false;
}

// Check if visit pre-registration section should be displayed
if (
	$scheduling_mychart_query
	&& in_array('preregister', $scheduling_mychart_type) // MyChart open scheduling is set to Visit Pre-Registration
	&& $scheduling_mychart_preregister_options // MyChart open scheduling widget options for Visit Pre-Registration is not empty
) {
	$show_scheduling_mychart_preregister_section = true;
} else {
	$show_scheduling_mychart_preregister_section = false;
}

// Get system setting for whether Appointment Request Forms are enabled
$scheduling_request_query_system = get_field('appointment_request_query_system', 'option');

// Query multiple settings to make a single determination on whether Appointment Request Forms are enabled for this location or provider
$scheduling_request_query = (
	$scheduling_request_query_system // Appointment Request Forms are allowed at the system level
	&& $scheduling_request_query // Appointment Request Forms are allowed at the location / provider level
	&& $scheduling_query // Patients can schedule an appointment for services rendered at this location or with this provider
	&& $scheduling_request_forms // At least one appointment request form has been selected
	) ? TRUE : FALSE;


// Check if link(s) to appointment request form(s) should be displayed
// $scheduling_mychart_query_system && $scheduling_request_query && $scheduling_request_forms
$scheduling_request_form_valid = false;
if (
	$scheduling_request_query
	&& (
		// Location-specific check
		(
			$scheduling_template == 'single-location'
			&& !$location_ac_query
		)
		||
		// Provider-specific check
		(
			$scheduling_template == 'single-provider'
		)
	)
) {
	foreach( $scheduling_request_forms as $form ) {
		$form_object = get_term_by( 'id', $form, 'appointment_request');
		if ( $form_object ) {
			$scheduling_request_form_valid = true;
			$break;
		}
	}
}
if ( $scheduling_request_form_valid ) {
	$show_scheduling_request_section = true;
} else {
	$show_scheduling_request_section = false;
}

// Define whether the appointment request button is solid or outline
if ( $show_scheduling_mychart_section && $show_scheduling_request_section ) {
	// If both booking and request are displayed, use the outline style
	$scheduling_request_btn_style = 'btn-outline';
} else {
	// Otherwise use the solid style
	$scheduling_request_btn_style = 'btn';
}

// Check if online scheduling information overall should be displayed
if ( $show_scheduling_mychart_section || $show_scheduling_request_section ) {
	$show_scheduling_section = true;
} else {
	$show_scheduling_section = false;
}
?>