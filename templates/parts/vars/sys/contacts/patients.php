<?php
/*
 * Template Name: System settings for general patient appointment information
 *
 * Description: A template part that defines a series of variables related to the
 * phone numbers and URLs used for patients to make an appointment.
 */

// Call the function

	$appointment_patients_vars = ( isset($appointment_patients_vars) && !empty($appointment_patients_vars) ) ? $appointment_patients_vars : uamswp_fad_appointment_patients();

// Create a variable for each item in the array

	foreach ( $appointment_patients_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}