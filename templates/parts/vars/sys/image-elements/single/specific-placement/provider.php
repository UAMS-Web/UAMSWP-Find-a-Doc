<?php
/*
 * Template Name: System settings for image elements in a provider subsection (or
 * profile)
 *
 * Description: A template part that defines a series of variables related to the
 * image elements in a provider subsection (or profile). This includes the
 * featured of the subsection (or profile) and the featured images of the fake
 * subpages (or sections) for related ontology items that have been placed in the
 * subsection (or profile)
 *
 * Required vars:
 * 	$page_id // int
 */

// Call the function

	$fpage_image_provider_vars = ( isset($fpage_image_provider_vars) && !empty($fpage_image_provider_vars) ) ? $fpage_image_provider_vars : uamswp_fad_fpage_image_provider(
		$page_id // int
	);

// Create a variable for each item in the array

	foreach ( $fpage_image_provider_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}