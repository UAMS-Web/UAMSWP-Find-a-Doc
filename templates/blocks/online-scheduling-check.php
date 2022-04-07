<?php
    /**
     *  Template Name: Online Scheduling Display Logic
     *  Designed for UAMS Find-a-Doc
     * 
     *  Required vars:
     *      $online_appointments_query
     *      $mychart_scheduling_query
     *      $appointment_request_query
     *      $appointment_request_forms
     *      $online_scheduling_template
     *          (
     *              'single-location'
     *              'single-provider'
     *          }
     * 
     *  Optional vars from single location template:
     *      $location_ac_query
     *      $mychart_scheduling_options
     *      $mychart_scheduling_options_rows
     * 
     *  Optional vars from single provider template:
     *      $mychart_scheduling_visit_type
     */

    // Check optional vars from single location template
    $location_ac_query = isset($location_ac_query) ? $location_ac_query : '';
    $mychart_scheduling_options = isset($mychart_scheduling_options) ? $mychart_scheduling_options : '';
    $mychart_scheduling_options_rows = isset($mychart_scheduling_options_rows) ? $mychart_scheduling_options_rows : '';

    // Check optional vars from single provider template
    $mychart_scheduling_visit_type = isset($mychart_scheduling_visit_type) ? $mychart_scheduling_visit_type : '';

    // Get system setting for whether MyChart Open Scheduling is enabled
    $mychart_scheduling_query_system = get_field('mychart_scheduling_query_system', 'option');

    // Check if MyChart Open Scheduling section should be displayed
    if (
        $mychart_scheduling_query_system
        && $mychart_scheduling_query
        && $online_appointments_query
        && (
            // Location-specific check
            (
                $online_scheduling_template == 'single-location'
                && !$location_ac_query
                && $mychart_scheduling_options_rows
            )
            ||
            // Provider-specific check
            (
                $online_scheduling_template == 'single-provider'
                && isset($mychart_scheduling_visit_type)
                && !empty($mychart_scheduling_visit_type)
            )
        )
    ) {
		$show_mychart_scheduling_section = true;
	} else {
		$show_mychart_scheduling_section = false;
	}

    // Check if link(s) to appointment request form(s) should be displayed
    // $mychart_scheduling_query_system && $appointment_request_query && $appointment_request_forms
    $appointment_request_form_valid = false;    
	if (
        $mychart_scheduling_query_system
        && $appointment_request_query
        && $appointment_request_forms
        && $online_appointments_query
        && (
            // Location-specific check
            (
                $online_scheduling_template == 'single-location'
                && !$location_ac_query
            )
            ||
            // Provider-specific check
            (
                $online_scheduling_template == 'single-provider'
            )
        )
    ) {
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