<?php
/*
 * Template Name: Define variables for a location profile
 *
 * Description: A template part that defines a series of variables for the fields
 * needed in a location profile
 *
 * Required vars:
 * 	$page_id // int // ID of the profile
 */

// Call the function

	$location_profile_fields_vars = ( isset($location_profile_fields_vars) && !empty($location_profile_fields_vars) ) ? $location_profile_fields_vars : uamswp_fad_location_profile_fields( $page_id );

// Create a variable for each item in the array

	foreach ( $location_profile_fields_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}