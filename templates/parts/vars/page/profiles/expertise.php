<?php
/*
 * Template Name: Define variables for an area of expertise profile
 *
 * Description: A template part that defines a series of variables for the fields
 * needed in an area of expertise profile
 *
 * Required vars:
 * 	$page_id // int // ID of the profile
 *
 * Optional vars:
 * 	$current_fpage // string // Current fake subpage slug
 */

// Check/define optional variables
$current_fpage = isset($current_fpage) ? $current_fpage : '';

// Call the function

	$expertise_profile_fields_vars = ( isset($expertise_profile_fields_vars) && !empty($expertise_profile_fields_vars) ) ? $expertise_profile_fields_vars : uamswp_fad_expertise_profile_fields(
		$page_id,
		$current_fpage
	);

// Create a variable for each item in the array

	foreach ( $expertise_profile_fields_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}