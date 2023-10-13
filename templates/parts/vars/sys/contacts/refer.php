<?php
/*
 * Template Name: System settings for general patient referral information
 *
 * Description: A template part that defines a series of variables related to the
 * phone numbers and URLs used for physicians to submit appointment referrals on
 * the behalf of patients.
 */

// Call the function

	$appointment_refer_vars = ( isset($appointment_refer_vars) && !empty($appointment_refer_vars) ) ? $appointment_refer_vars : uamswp_fad_appointment_refer();

// Create a variable for each item in the array

	foreach ( $appointment_refer_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}