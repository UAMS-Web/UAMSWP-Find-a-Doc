<?php
/*
 * Template Name: Related Conditions Section Query
 *
 * Description: A query for whether the section which lists conditions related to
 * the current ontology item should be displayed on the profile/subsection. It
 * also returns the Query, an array of IDs and a count of the IDs.
 *
 * Required vars:
 * 	$page_id // int
 * 	$conditions_cpt // int[]
 *
 * Optional vars:
 * 	$condition_treatment_section_show // bool
 * 	$ontology_type // bool
 * 	$jump_link_count // int
 * 	hide_medical_ontology // bool
 */

// Check/define optional variables

	$condition_treatment_section_show = ( isset($condition_treatment_section_show) && !empty($condition_treatment_section_show) ) ? $condition_treatment_section_show : '';
	$ontology_type = isset($ontology_type) ? $ontology_type : '';
	$jump_link_count = ( isset($jump_link_count) && !empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = ( isset($hide_medical_ontology) && !empty($hide_medical_ontology) ) ? $hide_medical_ontology : '';

// Call the function

	$condition_query_vars = ( isset($condition_query_vars) && !empty($condition_query_vars) ) ? $condition_query_vars : uamswp_fad_condition_query(
		$page_id, // int
		$conditions_cpt, // int[]
		$condition_treatment_section_show, // bool (optional)
		$ontology_type, // bool (optional)
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);

// Create a variable for each item in the array

	foreach ( $condition_query_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}