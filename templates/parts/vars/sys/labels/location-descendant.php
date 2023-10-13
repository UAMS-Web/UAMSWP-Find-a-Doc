<?php
/*
 * Template Name: System settings for descendant location labels
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for descendant location labels
 *
 */

// Call the function

	$labels_location_descendant_vars = ( isset($labels_location_descendant_vars) && !empty($labels_location_descendant_vars) ) ? $labels_location_descendant_vars : uamswp_fad_labels_location_descendant();

// Create a variable for each item in the array

	foreach ( $labels_location_descendant_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}