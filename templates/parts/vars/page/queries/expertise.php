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

 if (
	!isset($expertise_query) || empty($expertise_query)
	||
	!isset($expertise_section_show) || empty($expertise_section_show)
	||
	!isset($expertise_ids) || empty($expertise_ids)
	||
	!isset($expertise_count) || empty($expertise_count)
) {

	$jump_link_count = ( isset($jump_link_count) && empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = ( isset($hide_medical_ontology) && empty($hide_medical_ontology) ) ? $hide_medical_ontology : '';

	$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query(
		$page_id, // int
		$expertises, // int[]
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);
		$expertise_query = $expertise_query_vars['expertise_query']; // WP_Post[]
		$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool
		$expertise_ids = $expertise_query_vars['expertise_ids']; // int[]
		$expertise_count = $expertise_query_vars['expertise_count']; // int

}