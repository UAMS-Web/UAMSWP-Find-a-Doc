<?php
/*
 * Template Name: System settings for combined condition and treatment labels
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for combined condition and treatment labels
 *
 */

// Call the function

	$labels_condition_treatment_vars = ( isset($labels_condition_treatment_vars) && !empty($labels_condition_treatment_vars) ) ? $labels_condition_treatment_vars : uamswp_fad_labels_condition_treatment();

// Create a variable for each item in the array

	foreach ( $labels_condition_treatment_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}