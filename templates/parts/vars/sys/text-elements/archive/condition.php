<?php
/*
 * Template Name: System settings for condition archive text
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for condition archive text elements
 */

// Call the function

	$archive_text_condition_vars = ( isset($archive_text_condition_vars) && !empty($archive_text_condition_vars) ) ? $archive_text_condition_vars : uamswp_fad_archive_text_condition();

// Create a variable for each item in the array

	foreach ( $archive_text_condition_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}