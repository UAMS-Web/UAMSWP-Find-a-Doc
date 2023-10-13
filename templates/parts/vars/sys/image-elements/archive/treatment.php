<?php
/*
 * Template Name: System settings for treatment archive image elements
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for treatment archive image elements
 */

// Call the function

	$archive_image_treatment_vars = ( isset($archive_image_treatment_vars) && !empty($archive_image_treatment_vars) ) ? $archive_image_treatment_vars : uamswp_fad_archive_image_treatment();

// Create a variable for each item in the array

	foreach ( $archive_image_treatment_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}