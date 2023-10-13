<?php
/*
 * Template Name: System settings for clinical resource labels
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for clinical resource labels
 *
 */

// Call the function

	$labels_clinical_resource_vars = ( isset($labels_clinical_resource_vars) && !empty($labels_clinical_resource_vars) ) ? $labels_clinical_resource_vars : uamswp_fad_labels_clinical_resource();

// Create a variable for each item in the array

	foreach ( $labels_clinical_resource_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}