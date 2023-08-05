<?php
/*
 * Template Name: System settings for general placement of clinical resource item 
 * text elements
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for the general placement of clinical resource item text 
 * elements
 * 
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 */

if (
	!isset($clinical_resource_fpage_title_general) || empty($clinical_resource_fpage_title_general)
	||
	!isset($clinical_resource_fpage_intro_general) || empty($clinical_resource_fpage_intro_general)
	||
	!isset($clinical_resource_fpage_ref_main_title_general) || empty($clinical_resource_fpage_ref_main_title_general)
	||
	!isset($clinical_resource_fpage_ref_main_intro_general) || empty($clinical_resource_fpage_ref_main_intro_general)
	||
	!isset($clinical_resource_fpage_ref_main_link_general) || empty($clinical_resource_fpage_ref_main_link_general)
	||
	!isset($clinical_resource_fpage_ref_top_title_general) || empty($clinical_resource_fpage_ref_top_title_general)
	||
	!isset($clinical_resource_fpage_ref_top_intro_general) || empty($clinical_resource_fpage_ref_top_intro_general)
	||
	!isset($clinical_resource_fpage_ref_top_link_general) || empty($clinical_resource_fpage_ref_top_link_general)
	||
	!isset($clinical_resource_fpage_more_text_general) || empty($clinical_resource_fpage_more_text_general)
	||
	!isset($clinical_resource_fpage_more_link_text_general) || empty($clinical_resource_fpage_more_link_text_general)
	||
	!isset($clinical_resource_fpage_more_link_descr_general) || empty($clinical_resource_fpage_more_link_descr_general)
) {

	$fpage_text_clinical_resource_general_vars = isset($fpage_text_clinical_resource_general_vars) ? $fpage_text_clinical_resource_general_vars : uamswp_fad_fpage_text_clinical_resource_general(
		$page_id, // int
		$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	);
		$clinical_resource_fpage_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_title_general']; // string
		$clinical_resource_fpage_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_intro_general']; // string
		$clinical_resource_fpage_ref_main_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_title_general']; // string
		$clinical_resource_fpage_ref_main_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_intro_general']; // string
		$clinical_resource_fpage_ref_main_link_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_link_general']; // string
		$clinical_resource_fpage_ref_top_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_top_title_general']; // string
		$clinical_resource_fpage_ref_top_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_top_intro_general']; // string
		$clinical_resource_fpage_ref_top_link_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_top_link_general']; // string
		$clinical_resource_fpage_more_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_text_general']; // string
		$clinical_resource_fpage_more_link_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_text_general']; // string
		$clinical_resource_fpage_more_link_descr_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_descr_general']; // string

}
