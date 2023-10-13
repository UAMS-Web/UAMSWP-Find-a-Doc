<?php
/*
 * Template Name: System settings for descendant area of expertise labels
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for descendant area of expertise labels
 *
 */

// Call the function

	$labels_expertise_descendant_vars = ( isset($labels_expertise_descendant_vars) && !empty($labels_expertise_descendant_vars) ) ? $labels_expertise_descendant_vars : uamswp_fad_labels_expertise_descendant();

// Create a variable for each item in the array

	foreach ( $labels_expertise_descendant_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}