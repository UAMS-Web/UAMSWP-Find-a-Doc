<?php
/*
 * Template Name: System settings for general placement of location item text 
 * elements
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for the general placement of location item text elements
 * 
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 */

if (
	!isset($location_fpage_title_general) || empty($location_fpage_title_general)
	||
	!isset($location_fpage_intro_general) || empty($location_fpage_intro_general)
	||
	!isset($location_fpage_ref_main_title_general) || empty($location_fpage_ref_main_title_general)
	||
	!isset($location_fpage_ref_main_intro_general) || empty($location_fpage_ref_main_intro_general)
	||
	!isset($location_fpage_ref_main_link_general) || empty($location_fpage_ref_main_link_general)
	||
	!isset($location_fpage_ref_top_title_general) || empty($location_fpage_ref_top_title_general)
	||
	!isset($location_fpage_ref_top_intro_general) || empty($location_fpage_ref_top_intro_general)
	||
	!isset($location_fpage_ref_top_link_general) || empty($location_fpage_ref_top_link_general)
	||
	!isset($location_descendant_fpage_title_general) || empty($location_descendant_fpage_title_general)
	||
	!isset($location_descendant_fpage_intro_general) || empty($location_descendant_fpage_intro_general)
	||
	!isset($location_descendant_fpage_ref_main_title_general) || empty($location_descendant_fpage_ref_main_title_general)
	||
	!isset($location_descendant_fpage_ref_main_intro_general) || empty($location_descendant_fpage_ref_main_intro_general)
	||
	!isset($location_descendant_fpage_ref_main_link_general) || empty($location_descendant_fpage_ref_main_link_general)
	||
	!isset($location_descendant_fpage_ref_top_title_general) || empty($location_descendant_fpage_ref_top_title_general)
	||
	!isset($location_descendant_fpage_ref_top_intro_general) || empty($location_descendant_fpage_ref_top_intro_general)
	||
	!isset($location_descendant_fpage_ref_top_link_general) || empty($location_descendant_fpage_ref_top_link_general)
) {

	$fpage_text_location_general_vars = isset($fpage_text_location_general_vars) ? $fpage_text_location_general_vars : uamswp_fad_fpage_text_location_general(
		$page_id, // int
		$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	);
		$location_fpage_title_general = $fpage_text_location_general_vars['location_fpage_title_general']; // string
		$location_fpage_intro_general = $fpage_text_location_general_vars['location_fpage_intro_general']; // string
		$location_fpage_ref_main_title_general = $fpage_text_location_general_vars['location_fpage_ref_main_title_general']; // string
		$location_fpage_ref_main_intro_general = $fpage_text_location_general_vars['location_fpage_ref_main_intro_general']; // string
		$location_fpage_ref_main_link_general = $fpage_text_location_general_vars['location_fpage_ref_main_link_general']; // string
		$location_fpage_ref_top_title_general = $fpage_text_location_general_vars['location_fpage_ref_top_title_general']; // string
		$location_fpage_ref_top_intro_general = $fpage_text_location_general_vars['location_fpage_ref_top_intro_general']; // string
		$location_fpage_ref_top_link_general = $fpage_text_location_general_vars['location_fpage_ref_top_link_general']; // string
		$location_descendant_fpage_title_general = $fpage_text_location_general_vars['location_descendant_fpage_title_general']; // string
		$location_descendant_fpage_intro_general = $fpage_text_location_general_vars['location_descendant_fpage_intro_general']; // string
		$location_descendant_fpage_ref_main_title_general = $fpage_text_location_general_vars['location_descendant_fpage_ref_main_title_general']; // string
		$location_descendant_fpage_ref_main_intro_general = $fpage_text_location_general_vars['location_descendant_fpage_ref_main_intro_general']; // string
		$location_descendant_fpage_ref_main_link_general = $fpage_text_location_general_vars['location_descendant_fpage_ref_main_link_general']; // string
		$location_descendant_fpage_ref_top_title_general = $fpage_text_location_general_vars['location_descendant_fpage_ref_top_title_general']; // string
		$location_descendant_fpage_ref_top_intro_general = $fpage_text_location_general_vars['location_descendant_fpage_ref_top_intro_general']; // string
		$location_descendant_fpage_ref_top_link_general = $fpage_text_location_general_vars['location_descendant_fpage_ref_top_link_general']; // string

}
