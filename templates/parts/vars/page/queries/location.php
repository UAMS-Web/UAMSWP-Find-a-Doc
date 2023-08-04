<?php
/*
 * Template Name: Related Locations Section Query
 * 
 * Description: A query for whether the section which lists locations related to 
 * the current ontology item should be displayed on the profile/subsection. It 
 * also returns the Query, an array of IDs and a count of the IDs.
 * 
 * Required vars:
 * 	$page_id // int
 * 	$locations // int[]
 * 
 * Optional vars:
 * 	$jump_link_count // int
 * 	hide_medical_ontology // bool
 */

 if (
	!isset($location_query) || empty($location_query)
	||
	!isset($location_section_show) || empty($location_section_show)
	||
	!isset($location_ids) || empty($location_ids)
	||
	!isset($location_count) || empty($location_count)
	||
	!isset($location_valid) || empty($location_valid)
) {

	$jump_link_count = ( isset($jump_link_count) && empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = ( isset($hide_medical_ontology) && empty($hide_medical_ontology) ) ? $hide_medical_ontology : '';

	$location_query_vars = uamswp_fad_location_query(
		$page_id, // int
		$locations, // int[]
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);
		$location_query = $location_query_vars['location_query']; // WP_Post[]
		$location_section_show = $location_query_vars['location_section_show']; // bool
		$location_ids = $location_query_vars['location_ids']; // int[]
		$location_count = $location_query_vars['location_count']; // int
		$location_valid = $location_query_vars['location_valid']; // bool

}