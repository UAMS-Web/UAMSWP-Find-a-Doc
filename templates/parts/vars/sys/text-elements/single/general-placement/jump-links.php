<?php
/*
 * Template Name: System settings for text elements in general placements of
 * a jump links bar
 *
 * Description: A template part that defines a series of variables related to the
 * navigational element containing jump links (or anchor links)
 */

// Call the function

	$labels_jump_links_vars = ( isset($labels_jump_links_vars) && !empty($labels_jump_links_vars) ) ? $labels_jump_links_vars : uamswp_fad_labels_jump_links();

// Create a variable for each item in the array

	foreach ( $labels_jump_links_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}
