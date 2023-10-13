<?php
/*
 * Template Name: System settings for text elements in general placements of
 * clinical resource fake subpages (or sections)
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for the general placement of clinical resource item text
 * elements
 *
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 */

// Call the function

	$fpage_text_clinical_resource_general_vars = ( isset($fpage_text_clinical_resource_general_vars) && !empty($fpage_text_clinical_resource_general_vars) ) ? $fpage_text_clinical_resource_general_vars : uamswp_fad_fpage_text_clinical_resource_general(
		$page_id, // int
		$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	);

// Create a variable for each item in the array

	foreach ( $fpage_text_clinical_resource_general_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}
