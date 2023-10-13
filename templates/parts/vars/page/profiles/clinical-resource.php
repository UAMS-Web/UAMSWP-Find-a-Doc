<?php
/*
 * Template Name: Define variables for a clinical resource profile
 *
 * Description: A template part that defines a series of variables for the fields
 * needed in a clinical resource profile
 *
 * Required vars:
 * 	$page_id // int // ID of the profile
 */

// Call the function

	$clinical_resource_profile_fields_vars = ( isset($clinical_resource_profile_fields_vars) && !empty($clinical_resource_profile_fields_vars) ) ? $clinical_resource_profile_fields_vars : uamswp_fad_clinical_resource_profile_fields( $page_id );

// Create a variable for each item in the array

	foreach ( $clinical_resource_profile_fields_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}