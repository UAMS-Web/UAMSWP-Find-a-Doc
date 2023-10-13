<?php
/*
 * Template Name: System settings for area of expertise archive text
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for area of expertise archive text elements
 */

// Call the function

	$archive_text_expertise_vars = ( isset($archive_text_expertise_vars) && !empty($archive_text_expertise_vars) ) ? $archive_text_expertise_vars : uamswp_fad_archive_text_expertise();

// Create a variable for each item in the array

	foreach ( $archive_text_expertise_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}