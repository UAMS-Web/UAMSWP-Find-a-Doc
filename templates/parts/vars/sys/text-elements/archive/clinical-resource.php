<?php
/*
 * Template Name: System settings for clinical resource archive text
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for clinical resource archive text elements
 */

// Call the function

	$archive_text_clinical_resource_vars = ( isset($archive_text_clinical_resource_vars) && !empty($archive_text_clinical_resource_vars) ) ? $archive_text_clinical_resource_vars : uamswp_fad_archive_text_clinical_resource();

// Create a variable for each item in the array

	foreach ( $archive_text_clinical_resource_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}