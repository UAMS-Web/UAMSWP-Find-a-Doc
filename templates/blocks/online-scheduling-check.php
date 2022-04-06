<?php
    /**
     *  Template Name: Online Scheduling Display Logic
     *  Designed for UAMS Find-a-Doc
     * 
     *  Required vars:
     *      $mychart_scheduling_query
     *      $appointment_request_query
     *      $appointment_request_forms
     * 
     *  Optional vars:
     *      $location_ac_query
     *      $location_appointments_query
     *      $mychart_scheduling_options
     *      $mychart_scheduling_options_rows
     */

    // Get system setting for whether MyChart Open Scheduling is enabled
    $mychart_scheduling_query_system = get_field('mychart_scheduling_query_system', 'option');

    // Check if MyChart Open Scheduling section should be displayed
    if (
        $mychart_scheduling_query_system
        && $mychart_scheduling_query
        && !$location_ac_query
        && $location_appointments_query
        && $mychart_scheduling_options_rows
    ) {
		$show_mychart_scheduling_section = true;
	} else {
		$show_mychart_scheduling_section = false;
	}

    // Check if link(s) to appointment request form(s) should be displayed
    $appointment_request_form_valid = false;
	if ( $mychart_scheduling_query_system && $appointment_request_query && $appointment_request_forms && !$location_ac_query && $location_appointments_query ) {
		foreach( $appointment_request_forms as $form ) {
			$form_object = get_term_by( 'id', $form, 'appointment_request');
			if ( $form_object ) {
				$appointment_request_form_valid = true;
				$break;
			}
		}
	}
	if ( $appointment_request_form_valid ) {
		$show_appointment_request_section = true;
	} else {
		$show_appointment_request_section = false;
	}

?>