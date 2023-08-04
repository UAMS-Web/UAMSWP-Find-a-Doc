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

 if (
	!isset($treatment_cpt_query) || empty($treatment_cpt_query)
	||
	!isset($treatment_section_show) || empty($treatment_section_show)
	||
	!isset($treatment_ids) || empty($treatment_ids)
	||
	!isset($treatment_count) || empty($treatment_count)
	||
	!isset($schema_medical_specialty) || empty($schema_medical_specialty)
) {

	$condition_treatment_section_show = ( isset($condition_treatment_section_show) && empty($condition_treatment_section_show) ) ? $condition_treatment_section_show : '';
	$ontology_type = ( isset($ontology_type) && empty($ontology_type) ) ? $ontology_type : '';
	$jump_link_count = ( isset($jump_link_count) && empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = ( isset($hide_medical_ontology) && empty($hide_medical_ontology) ) ? $hide_medical_ontology : '';

	$treatment_query_vars = uamswp_fad_treatment_query(
		$page_id, // int
		$treatments_cpt, // int[]
		$condition_treatment_section_show, // bool (optional)
		$ontology_type, // bool (optional)
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);
		$treatment_cpt_query = $treatment_query_vars['treatment_cpt_query']; // WP_Post[]
		$treatment_section_show = $treatment_query_vars['treatment_section_show']; // bool
		$treatment_ids = $treatment_query_vars['treatment_ids']; // int[]
		$treatment_count = $treatment_query_vars['treatment_count']; // int
		$schema_medical_specialty = $treatment_query_vars['schema_medical_specialty']; // array

}