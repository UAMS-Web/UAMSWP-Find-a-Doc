<?php
/*
 * Template Name: Define variables for a provider profile
 *
 * Description: A template part that defines a series of variables for the fields
 * needed in a provider profile
 *
 * Required vars:
 * 	$page_id // int // ID of the profile
 */

// Call the function

	$provider_profile_fields_vars = ( isset($provider_profile_fields_vars) && !empty($provider_profile_fields_vars) ) ? $provider_profile_fields_vars : uamswp_fad_provider_profile_fields( $page_id );

// Create a variable for each item in the array

	foreach ( $provider_profile_fields_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}