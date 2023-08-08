<?php
/*
 * Template Name: System settings for location archive image elements
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for location archive image elements
 */

// Call the function

	$archive_image_location_vars = isset($archive_image_location_vars) ? $archive_image_location_vars : uamswp_fad_archive_image_location();

// Create a variable for each item in the array

	foreach ( $archive_image_location_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}