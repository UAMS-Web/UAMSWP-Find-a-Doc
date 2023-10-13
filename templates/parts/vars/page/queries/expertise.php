<?php
/*
 * Template Name: Related Areas of Expertise Section Query
 *
 * Description: A query for whether the section which lists areas of expertise
 * related to the current ontology item should be displayed on the
 * profile/subsection. It also returns the Query, an array of IDs and a count of
 * the IDs.
 *
 * Required vars:
 * 	$page_id // int
 * 	$expertises // int[]
 *
 * Optional vars:
 * 	$jump_link_count // int
 * 	hide_medical_ontology // bool
 */

// Check/define optional variables

	$jump_link_count = ( isset($jump_link_count) && !empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = ( isset($hide_medical_ontology) && !empty($hide_medical_ontology) ) ? $hide_medical_ontology : '';

// Call the function

	$expertise_query_vars = ( isset($expertise_query_vars) && !empty($expertise_query_vars) ) ? $expertise_query_vars : uamswp_fad_expertise_query(
		$page_id, // int
		$expertises, // int[]
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);

// Create a variable for each item in the array

	foreach ( $expertise_query_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}