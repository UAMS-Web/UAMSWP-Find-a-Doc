<?php
/*
 * Template Name: Page settings for conditionally suppressing ontology sections
 * based on region and service line
 *
 * Description: A template part that defines a series of variables related to a
 * combination of settings (region and service line) on the page and at the system
 * level used to determine whether or not to suppress certain ontology content on
 * a given page.
 *
 * Required vars:
 * 	$page_id // int // ID of the current page
 * 	$regions // array
 * 	$service_lines // array
 *
 * Optional vars:
 * 	$hide_medical_ontology // bool
 */

// Check/define variables

	$page_id = isset($page_id) ? $page_id : get_the_ID();
	$regions = isset($regions) ? $regions : array();
	$service_lines = isset($service_lines) ? $service_lines : array();

if (
	$regions
	||
	$service_lines
) {

	// Call the function

		$ontology_hide_vars = ( isset($ontology_hide_vars) && !empty($ontology_hide_vars) ) ? $ontology_hide_vars : uamswp_fad_ontology_hide(
			$page_id, // int // ID of the post
			$regions, // string|array // Region(s) associated with the item
			$service_lines // string|array // Service line(s) associated with the item
		);

	// Create a variable for each item in the array

		foreach ( $ontology_hide_vars as $key => $value ) {

			${$key} = $value; // Create a variable for each item in the array

		}

} else {

	$hide_medical_ontology = false; // bool

}