<?php
/*
 * Template Name: System settings for text elements in general placements of areas 
 * of expertise fake subpages (or sections)
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for the general placement of area of expertise item text 
 * elements
 * 
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 */

if (
	!isset($expertise_fpage_title_general) || empty($expertise_fpage_title_general)
	||
	!isset($expertise_fpage_intro_general) || empty($expertise_fpage_intro_general)
	||
	!isset($expertise_fpage_ref_main_title_general) || empty($expertise_fpage_ref_main_title_general)
	||
	!isset($expertise_fpage_ref_main_intro_general) || empty($expertise_fpage_ref_main_intro_general)
	||
	!isset($expertise_fpage_ref_main_link_general) || empty($expertise_fpage_ref_main_link_general)
	||
	!isset($expertise_fpage_ref_top_title_general) || empty($expertise_fpage_ref_top_title_general)
	||
	!isset($expertise_fpage_ref_top_intro_general) || empty($expertise_fpage_ref_top_intro_general)
	||
	!isset($expertise_fpage_ref_top_link_general) || empty($expertise_fpage_ref_top_link_general)
	||
	!isset($expertise_descendant_fpage_title_general) || empty($expertise_descendant_fpage_title_general)
	||
	!isset($expertise_descendant_fpage_intro_general) || empty($expertise_descendant_fpage_intro_general)
	||
	!isset($expertise_descendant_fpage_ref_main_title_general) || empty($expertise_descendant_fpage_ref_main_title_general)
	||
	!isset($expertise_descendant_fpage_ref_main_intro_general) || empty($expertise_descendant_fpage_ref_main_intro_general)
	||
	!isset($expertise_descendant_fpage_ref_main_link_general) || empty($expertise_descendant_fpage_ref_main_link_general)
	||
	!isset($expertise_descendant_fpage_ref_top_title_general) || empty($expertise_descendant_fpage_ref_top_title_general)
	||
	!isset($expertise_descendant_fpage_ref_top_intro_general) || empty($expertise_descendant_fpage_ref_top_intro_general)
	||
	!isset($expertise_descendant_fpage_ref_top_link_general) || empty($expertise_descendant_fpage_ref_top_link_general)
) {

	$fpage_text_expertise_general_vars = uamswp_fad_fpage_text_expertise_general(
		$page_id, // int
		$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	);
		$expertise_fpage_title_general = $fpage_text_expertise_general_vars['expertise_fpage_title_general']; // string
		$expertise_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_intro_general']; // string
		$expertise_fpage_ref_main_title_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_title_general']; // string
		$expertise_fpage_ref_main_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_intro_general']; // string
		$expertise_fpage_ref_main_link_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_link_general']; // string
		$expertise_fpage_ref_top_title_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_top_title_general']; // string
		$expertise_fpage_ref_top_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_top_intro_general']; // string
		$expertise_fpage_ref_top_link_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_top_link_general']; // string
		$expertise_descendant_fpage_title_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_title_general']; // string
		$expertise_descendant_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_intro_general']; // string
		$expertise_descendant_fpage_ref_main_title_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_ref_main_title_general']; // string
		$expertise_descendant_fpage_ref_main_intro_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_ref_main_intro_general']; // string
		$expertise_descendant_fpage_ref_main_link_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_ref_main_link_general']; // string
		$expertise_descendant_fpage_ref_top_title_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_ref_top_title_general']; // string
		$expertise_descendant_fpage_ref_top_intro_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_ref_top_intro_general']; // string
		$expertise_descendant_fpage_ref_top_link_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_ref_top_link_general']; // string

}