<?php
/*
 * Template Name: System settings for provider archive text
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for provider archive text elements
 */

// Call the function

	$archive_text_provider_vars = ( isset($archive_text_provider_vars) && !empty($archive_text_provider_vars) ) ? $archive_text_provider_vars : uamswp_fad_archive_text_provider();

// Create a variable for each item in the array

	foreach ( $archive_text_provider_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}