<?php
/*
 * Template Name: System settings for condition labels
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for condition labels
 *
 */

// Call the function

	$labels_condition_vars = ( isset($labels_condition_vars) && !empty($labels_condition_vars) ) ? $labels_condition_vars : uamswp_fad_labels_condition();

// Create a variable for each item in the array

	foreach ( $labels_condition_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}