<?php
/*
 * Template Name: System settings for provider labels
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for provider labels
 *
 */

// Call the function

	$labels_provider_vars = ( isset($labels_provider_vars) && !empty($labels_provider_vars) ) ? $labels_provider_vars : uamswp_fad_labels_provider();

// Create a variable for each item in the array

	foreach ( $labels_provider_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}