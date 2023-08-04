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

 if (
	!isset($condition_cpt_query) || empty($condition_cpt_query)
	||
	!isset($condition_section_show) || empty($condition_section_show)
	||
	!isset($condition_treatment_section_show) || empty($condition_treatment_section_show)
	||
	!isset($condition_ids) || empty($condition_ids)
	||
	!isset($condition_count) || empty($condition_count)
	||
	!isset($schema_medical_specialty) || empty($schema_medical_specialty)
) {

	$condition_treatment_section_show = ( isset($condition_treatment_section_show) && empty($condition_treatment_section_show) ) ? $condition_treatment_section_show : '';
	$ontology_type = ( isset($ontology_type) && empty($ontology_type) ) ? $ontology_type : '';
	$jump_link_count = ( isset($jump_link_count) && empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = ( isset($hide_medical_ontology) && empty($hide_medical_ontology) ) ? $hide_medical_ontology : '';

	$condition_query_vars = uamswp_fad_condition_query(
		$page_id, // int
		$conditions_cpt, // int[]
		$condition_treatment_section_show, // bool (optional)
		$ontology_type, // bool (optional)
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);
		$condition_cpt_query = $condition_query_vars['condition_cpt_query']; // WP_Post[]
		$condition_section_show = $condition_query_vars['condition_section_show']; // bool
		$condition_ids = $condition_query_vars['condition_ids']; // int[]
		$condition_count = $condition_query_vars['condition_count']; // int
		$schema_medical_specialty = $condition_query_vars['schema_medical_specialty']; // array

}