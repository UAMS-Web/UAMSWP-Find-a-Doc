<?php
/*
 * Template Name: System settings for area of expertise archive image elements
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for area of expertise archive image elements
 */

// Call the function

	$archive_image_expertise_vars = ( isset($archive_image_expertise_vars) && !empty($archive_image_expertise_vars) ) ? $archive_image_expertise_vars : uamswp_fad_archive_image_expertise();

// Create a variable for each item in the array

	foreach ( $archive_image_expertise_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}