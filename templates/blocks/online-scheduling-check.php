<?php
    /**
     *  Template Name: Online Scheduling Display Logic
     *  Designed for UAMS Find-a-Doc
     * 
     *  Required vars:
     *      $scheduling_query
     *      $scheduling_mychart_query
     *      $scheduling_request_query
     *      $scheduling_request_forms
     *      $scheduling_template
     *          (
     *              'single-location'
     *              'single-provider'
     *          )
     * 
     *  Required vars from single location template:
     *      $scheduling_mychart_type
     *          (
     *              'book'
     *              'preregister'
     *          )
     *      $scheduling_mychart_book_options
     *      $scheduling_mychart_preregister_options
     * 
     *  Optional vars from single location template:
     *      $location_ac_query
     * 
     *  Optional vars from single provider template:
     *      $mychart_scheduling_visit_type
     */

    // Check optional vars from single location template
    $location_ac_query = isset($location_ac_query) ? $location_ac_query : '';
    $scheduling_mychart_book_options = isset($scheduling_mychart_book_options) ? $scheduling_mychart_book_options : '';
    $scheduling_mychart_preregister_options = isset($scheduling_mychart_preregister_options) ? $scheduling_mychart_preregister_options : '';

    // Check optional vars from single provider template
    $mychart_scheduling_visit_type = isset($mychart_scheduling_visit_type) ? $mychart_scheduling_visit_type : '';

    // Get system setting for whether MyChart Open Scheduling is enabled
    $scheduling_mychart_query_system = get_field('mychart_scheduling_query_system', 'option');

    // Check if MyChart Open Scheduling section should be displayed
    if (
        $scheduling_mychart_query_system
        && $scheduling_mychart_query
        && $scheduling_query
        && (
            // Location-specific check
            (
                $scheduling_template == 'single-location'
                && !$location_ac_query
                && ( $scheduling_mychart_book_options || $scheduling_mychart_preregister_options )
            )
            ||
            // Provider-specific check
            (
                $scheduling_template == 'single-provider'
                && isset($mychart_scheduling_visit_type)
                && !empty($mychart_scheduling_visit_type)
            )
        )
    ) {
		$show_scheduling_mychart_section = true;
	} else {
		$show_scheduling_mychart_section = false;
	}

	// Check if appointment booking section should be displayed
    if ( in_array('book', $scheduling_mychart_type) && $scheduling_mychart_book_options ) {
		$show_scheduling_mychart_book_section = true;
    } else {
		$show_scheduling_mychart_book_section = false;
    }

	// Check if visit preregistration section should be displayed
    if ( in_array('preregister', $scheduling_mychart_type) && $scheduling_mychart_preregister_options ) {
		$show_scheduling_mychart_preregister_section = true;
    } else {
		$show_scheduling_mychart_preregister_section = false;
    }

    // Get system setting for whether Appointment Request Forms are enabled
    $scheduling_request_query_system = get_field('appointment_request_query_system', 'option');

    // Check if link(s) to appointment request form(s) should be displayed
    // $scheduling_mychart_query_system && $scheduling_request_query && $scheduling_request_forms
    $appointment_request_form_valid = false;    
	if (
        $scheduling_request_query_system
        && $scheduling_request_query
        && $scheduling_request_forms
        && $scheduling_query
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
				$appointment_request_form_valid = true;
				$break;
			}
		}
	}
	if ( $appointment_request_form_valid ) {
		$show_scheduling_request_section = true;
	} else {
		$show_scheduling_request_section = false;
	}

	// Check if online scheduling information overall should be displayed
	if ( $show_scheduling_mychart_section || $show_scheduling_request_section ) {
		$show_scheduling_section = true;
	} else {
		$show_scheduling_section = false;
	}
?>