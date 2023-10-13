<?php
/*
 * Template Name: System settings for image elements in a clinical resource
 * subsection (or profile)
 *
 * Description: A template part that defines a series of variables related to the
 * image elements in a clinical resource subsection (or profile). This includes
 * the featured of the subsection (or profile) and the featured images of the fake
 * subpages (or sections) for related ontology items that have been placed in the
 * subsection (or profile)
 *
 * Required vars:
 * 	$page_id // int
 */

// Call the function

	$fpage_image_clinical_resource_vars = ( isset($fpage_image_clinical_resource_vars) && !empty($fpage_image_clinical_resource_vars) ) ? $fpage_image_clinical_resource_vars : uamswp_fad_fpage_image_clinical_resource(
		$page_id // int
	);

// Create a variable for each item in the array

	foreach ( $fpage_image_clinical_resource_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}