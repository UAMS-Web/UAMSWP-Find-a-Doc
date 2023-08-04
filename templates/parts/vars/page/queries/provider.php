<?php
/*
 * Template Name: Related Providers Section Query
 * 
 * Description: A query for whether the section which lists providers related to 
 * the current ontology item should be displayed on the profile/subsection. It 
 * also returns the Query, an array of IDs and a count of the IDs.
 * 
 * Required vars:
 * 	$page_id // int
 * 	$providers // int[]
 * 
 * Optional vars:
 * 	$jump_link_count // int
 * 	hide_medical_ontology // bool
 */

 if (
	!isset($provider_query) || empty($provider_query)
	||
	!isset($provider_section_show) || empty($provider_section_show)
	||
	!isset($provider_ids) || empty($provider_ids)
	||
	!isset($provider_count) || empty($provider_count)
) {

	$jump_link_count = ( isset($jump_link_count) && empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = ( isset($hide_medical_ontology) && empty($hide_medical_ontology) ) ? $hide_medical_ontology : '';

	$provider_query_vars = uamswp_fad_provider_query(
		$page_id, // int
		$providers, // int[]
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);
		$provider_query = $provider_query_vars['provider_query']; // WP_Post[]
		$provider_section_show = $provider_query_vars['provider_section_show']; // bool
		$provider_ids = $provider_query_vars['provider_ids']; // int[]
		$provider_count = $provider_query_vars['provider_count']; // int

}