<?php
/*
 * Template Name: Related Clinical Resources Section Query
 *
 * Description: A query for whether the section which lists clinical resources
 * related to the current ontology item should be displayed on the
 * profile/subsection. It also returns the Query, an array of IDs and a count of
 * the IDs.
 *
 * Required vars:
 * 	$page_id // int
 * 	$clinical_resources // int[]
 *
 * Optional vars:
 * 	$clinical_resource_posts_per_page // int
 * 	$jump_link_count // int
 * 	hide_medical_ontology // bool
 */

// Check/define optional variables

	$clinical_resource_posts_per_page = ( isset($clinical_resource_posts_per_page) && !empty($clinical_resource_posts_per_page) ) ? $clinical_resource_posts_per_page : '';
	$jump_link_count = ( isset($jump_link_count) && !empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = ( isset($hide_medical_ontology) && !empty($hide_medical_ontology) ) ? $hide_medical_ontology : '';

// Call the function

	$clinical_resource_query_vars = ( isset($clinical_resource_query_vars) && !empty($clinical_resource_query_vars) ) ? $clinical_resource_query_vars : uamswp_fad_clinical_resource_query(
		$page_id, // int
		$clinical_resources, // int[]
		$clinical_resource_posts_per_page, // int
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);

// Create a variable for each item in the array

	foreach ( $clinical_resource_query_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}