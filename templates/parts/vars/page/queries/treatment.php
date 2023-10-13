<?php
/*
 * Template Name: Related Treatments Section Query
 *
 * Description: A query for whether the section which lists treatments related to
 * the current ontology item should be displayed on the profile/subsection. It
 * also returns the Query, an array of IDs and a count of the IDs.
 *
 * Required vars:
 * 	$page_id // int
 * 	$treatments_cpt // int[]
 *
 * Optional vars:
 * 	$condition_treatment_section_show // bool
 * 	$ontology_type // bool
 * 	$jump_link_count // int
 * 	hide_medical_ontology // bool
 */

// Check/define optional variables

	$condition_treatment_section_show = ( isset($condition_treatment_section_show) && !empty($condition_treatment_section_show) ) ? $condition_treatment_section_show : '';
	$ontology_type = isset($ontology_type) ? $ontology_type : true;
	$jump_link_count = ( isset($jump_link_count) && !empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = ( isset($hide_medical_ontology) && !empty($hide_medical_ontology) ) ? $hide_medical_ontology : '';

// Call the function

	$treatment_query_vars = ( isset($treatment_query_vars) && !empty($treatment_query_vars) ) ? $treatment_query_vars : uamswp_fad_treatment_query(
		$page_id, // int
		$treatments_cpt, // int[]
		$condition_treatment_section_show, // bool (optional)
		$ontology_type, // bool (optional)
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);

// Create a variable for each item in the array

	foreach ( $treatment_query_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}