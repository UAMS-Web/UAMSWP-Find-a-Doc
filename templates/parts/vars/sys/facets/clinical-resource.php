<?php
/*
 * Template Name: System settings for clinical resource facet labels
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for clinical resource facet labels
 *
 */

// Call the function

	$labels_clinical_resource_facet_vars = ( isset($labels_clinical_resource_facet_vars) && !empty($labels_clinical_resource_facet_vars) ) ? $labels_clinical_resource_facet_vars : uamswp_fad_labels_clinical_resource_facet();

// Create a variable for each item in the array

	foreach ( $labels_clinical_resource_facet_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}