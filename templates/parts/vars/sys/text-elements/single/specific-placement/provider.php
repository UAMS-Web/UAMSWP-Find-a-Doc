<?php
/*
 * Template Name: System settings for text elements in a provider subsection (or 
 * profile)
 * 
 * Description: A template part that defines a series of variables related to the 
 * text elements in a provider subsection (or profile). This includes the titles 
 * and intro text of the subsection (or profile) and the text elements of the fake 
 * subpages (or sections) for related ontology items that have been placed in the 
 * subsection (or profile)
 * 
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 */

// Call the function

	$fpage_text_provider_vars = isset($fpage_text_provider_vars) ? $fpage_text_provider_vars : uamswp_fad_fpage_text_provider(
		$page_id, // int
		$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	);

// Create a variable for each item in the array

	foreach ( $fpage_text_provider_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}