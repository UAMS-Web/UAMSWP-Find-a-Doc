<?php
/*
 * Template Name: Ontology subsection values
 *
 * Description: A template part that defines a series of variables related to the
 * the values necessary to create the site header and the site navigation elements
 * in an ontology subsection
 *
 * Required vars:
 * 	$page_id // int // ID of the current ontology item
 *
 * Optional vars:
 * 	$ontology_type // bool (default: true) // Ontology type of the current ontology item (true is ontology type, false is content type)
 * 	$page_title // string (default: '') // Title of the current ontology item
 * 	$page_url // string (default: '') // Permalink of the current ontology item
 */

// Check/define optional variables

	$ontology_type = isset($ontology_type) ? $ontology_type : true;
	$page_title = ( isset($page_title) && !empty($page_title) ) ? $page_title : '';
	$page_url = ( isset($page_url) && !empty($page_url) ) ? $page_url : '';

// Call the function

	$ontology_site_values_vars = ( isset($ontology_site_values_vars) && !empty($ontology_site_values_vars) ) ? $ontology_site_values_vars : uamswp_fad_ontology_site_values(
		$page_id, // int // ID of the current ontology item
		$ontology_type, // bool // Ontology type of the current ontology item (true is ontology type, false is content type)
		$page_title, // string // Title of the current ontology item
		$page_url // string // Permalink of the current ontology item
	);

// Create a variable for each item in the array

	foreach ( $ontology_site_values_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}