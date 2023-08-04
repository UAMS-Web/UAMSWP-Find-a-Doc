<?php
/*
 * Template Name: Descendant Areas of Expertise Section Query
 * 
 * Description: A query for whether the section which lists descendants of the 
 * current area of expertise item should be displayed on the profile/subsection. 
 * It also returns the Query, an array of IDs and a count of the IDs.
 * 
 * Required vars:
 * 	$page_id // int
 * 	$expertise_descendants // int[]
 * 
 * Optional vars:
 * 	$content_placement // string
 * 	$site_nav_id // int
 * 	$jump_link_count // int
 * 	hide_medical_ontology // bool
 */

 if (
	!isset($expertise_descendant_query) || empty($expertise_descendant_query)
	||
	!isset($expertise_descendant_section_show) || empty($expertise_descendant_section_show)
	||
	!isset($expertise_descendant_ids) || empty($expertise_descendant_ids)
	||
	!isset($expertise_descendant_count) || empty($expertise_descendant_count)
	||
	!isset($expertise_content_query) || empty($expertise_content_query)
	||
	!isset($expertise_content_nav_show) || empty($expertise_content_nav_show)
	||
	!isset($expertise_content_ids) || empty($expertise_content_ids)
	||
	!isset($expertise_content_count) || empty($expertise_content_count)
	||
	!isset($expertise_content_nav) || empty($expertise_content_nav)
) {

	$content_placement = ( isset($content_placement) && empty($content_placement) ) ? $content_placement : '';
	$site_nav_id = ( isset($site_nav_id) && empty($site_nav_id) ) ? $site_nav_id : '';
	$jump_link_count = ( isset($jump_link_count) && empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = ( isset($hide_medical_ontology) && empty($hide_medical_ontology) ) ? $hide_medical_ontology : '';

	$expertise_descendant_query_vars = uamswp_fad_expertise_descendant_query(
		$page_id, // int
		$expertise_descendants, // int[]
		$content_placement, // string
		$site_nav_id, // int
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);
		$expertise_descendant_query = $expertise_descendant_query_vars['expertise_descendant_query']; // WP_Post[]
		$expertise_descendant_section_show = $expertise_descendant_query_vars['expertise_descendant_section_show']; // bool
		$expertise_descendant_ids = $expertise_descendant_query_vars['expertise_descendant_ids']; // int[]
		$expertise_descendant_count = $expertise_descendant_query_vars['expertise_descendant_count']; // int
		$expertise_content_query = $expertise_descendant_query_vars['expertise_content_query']; // WP_Post[]
		$expertise_content_nav_show = $expertise_descendant_query_vars['expertise_content_nav_show']; // bool
		$expertise_content_ids = $expertise_descendant_query_vars['expertise_content_ids']; // int[]
		$expertise_content_count = $expertise_descendant_query_vars['expertise_content_count']; // int
		$expertise_content_nav = $expertise_descendant_query_vars['expertise_content_nav']; // string

}