<?php
/*
 * Template Name: System settings for location archive text
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for location archive text elements
 */

// Call the function

	$archive_text_location_vars = ( isset($archive_text_location_vars) && !empty($archive_text_location_vars) ) ? $archive_text_location_vars : uamswp_fad_archive_text_location();

// Create a variable for each item in the array

	foreach ( $archive_text_location_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}