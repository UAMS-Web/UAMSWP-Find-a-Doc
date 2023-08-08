<?php
/*
 * Template Name: System settings for text elements in general placements of 
 * providers fake subpages (or sections)
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for the general placement of provider item text elements
 * 
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 */

if (
	!isset($provider_fpage_title_general) || empty($provider_fpage_title_general)
	||
	!isset($provider_fpage_intro_general) || empty($provider_fpage_intro_general)
	||
	!isset($provider_fpage_ref_main_title_general) || empty($provider_fpage_ref_main_title_general)
	||
	!isset($provider_fpage_ref_main_intro_general) || empty($provider_fpage_ref_main_intro_general)
	||
	!isset($provider_fpage_ref_main_link_general) || empty($provider_fpage_ref_main_link_general)
	||
	!isset($provider_fpage_ref_top_title_general) || empty($provider_fpage_ref_top_title_general)
	||
	!isset($provider_fpage_ref_top_intro_general) || empty($provider_fpage_ref_top_intro_general)
	||
	!isset($provider_fpage_ref_top_link_general) || empty($provider_fpage_ref_top_link_general)
) {

	$fpage_text_provider_general_vars = isset($fpage_text_provider_general_vars) ? $fpage_text_provider_general_vars : uamswp_fad_fpage_text_provider_general(
		$page_id, // int
		$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	);
		$provider_fpage_title_general = $fpage_text_provider_general_vars['provider_fpage_title_general']; // string
		$provider_fpage_intro_general = $fpage_text_provider_general_vars['provider_fpage_intro_general']; // string
		$provider_fpage_ref_main_title_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_title_general']; // string
		$provider_fpage_ref_main_intro_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_intro_general']; // string
		$provider_fpage_ref_main_link_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_link_general']; // string
		$provider_fpage_ref_top_title_general = $fpage_text_provider_general_vars['provider_fpage_ref_top_title_general']; // string
		$provider_fpage_ref_top_intro_general = $fpage_text_provider_general_vars['provider_fpage_ref_top_intro_general']; // string
		$provider_fpage_ref_top_link_general = $fpage_text_provider_general_vars['provider_fpage_ref_top_link_general']; // string

}