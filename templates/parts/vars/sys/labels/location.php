<?php
/*
 * Template Name: System settings for location labels
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for location labels
 *
 */

// Call the function

	$labels_location_vars = ( isset($labels_location_vars) && !empty($labels_location_vars) ) ? $labels_location_vars : uamswp_fad_labels_location();

// Create a variable for each item in the array

	foreach ( $labels_location_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}