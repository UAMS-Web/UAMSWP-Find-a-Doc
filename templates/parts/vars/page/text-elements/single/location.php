<?php
/*
 * Template Name: System settings for text elements in a location subsection (or 
 * profile)
 * 
 * Description: A template part that defines a series of variables related to the 
 * text elements in an location subsection (or profile). This includes the titles 
 * and intro text of the subsection (or profile) and the text elements of the fake 
 * subpages (or sections) for related ontology items that have been placed in the 
 * subsection (or profile)
 * 
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 */

 if (
	!isset($provider_fpage_title_location) || empty($provider_fpage_title_location)
	||
	!isset($provider_fpage_intro_location) || empty($provider_fpage_intro_location)
	||
	!isset($provider_fpage_ref_main_title_location) || empty($provider_fpage_ref_main_title_location)
	||
	!isset($provider_fpage_ref_main_intro_location) || empty($provider_fpage_ref_main_intro_location)
	||
	!isset($provider_fpage_ref_main_link_location) || empty($provider_fpage_ref_main_link_location)
	||
	!isset($provider_fpage_ref_top_title_location) || empty($provider_fpage_ref_top_title_location)
	||
	!isset($provider_fpage_ref_top_intro_location) || empty($provider_fpage_ref_top_intro_location)
	||
	!isset($provider_fpage_ref_top_link_location) || empty($provider_fpage_ref_top_link_location)
	||
	!isset($location_descendant_fpage_title_location) || empty($location_descendant_fpage_title_location)
	||
	!isset($location_descendant_fpage_intro_location) || empty($location_descendant_fpage_intro_location)
	||
	!isset($location_descendant_fpage_ref_main_title_location) || empty($location_descendant_fpage_ref_main_title_location)
	||
	!isset($location_descendant_fpage_ref_main_intro_location) || empty($location_descendant_fpage_ref_main_intro_location)
	||
	!isset($location_descendant_fpage_ref_main_link_location) || empty($location_descendant_fpage_ref_main_link_location)
	||
	!isset($expertise_fpage_title_location) || empty($expertise_fpage_title_location)
	||
	!isset($expertise_fpage_intro_location) || empty($expertise_fpage_intro_location)
	||
	!isset($expertise_fpage_ref_main_title_location) || empty($expertise_fpage_ref_main_title_location)
	||
	!isset($expertise_fpage_ref_main_intro_location) || empty($expertise_fpage_ref_main_intro_location)
	||
	!isset($expertise_fpage_ref_main_link_location) || empty($expertise_fpage_ref_main_link_location)
	||
	!isset($expertise_fpage_ref_top_title_location) || empty($expertise_fpage_ref_top_title_location)
	||
	!isset($expertise_fpage_ref_top_intro_location) || empty($expertise_fpage_ref_top_intro_location)
	||
	!isset($expertise_fpage_ref_top_link_location) || empty($expertise_fpage_ref_top_link_location)
	||
	!isset($clinical_resource_fpage_title_location) || empty($clinical_resource_fpage_title_location)
	||
	!isset($clinical_resource_fpage_intro_location) || empty($clinical_resource_fpage_intro_location)
	||
	!isset($clinical_resource_fpage_ref_main_title_location) || empty($clinical_resource_fpage_ref_main_title_location)
	||
	!isset($clinical_resource_fpage_ref_main_intro_location) || empty($clinical_resource_fpage_ref_main_intro_location)
	||
	!isset($clinical_resource_fpage_ref_main_link_location) || empty($clinical_resource_fpage_ref_main_link_location)
	||
	!isset($clinical_resource_fpage_ref_top_title_location) || empty($clinical_resource_fpage_ref_top_title_location)
	||
	!isset($clinical_resource_fpage_ref_top_intro_location) || empty($clinical_resource_fpage_ref_top_intro_location)
	||
	!isset($clinical_resource_fpage_ref_top_link_location) || empty($clinical_resource_fpage_ref_top_link_location)
	||
	!isset($clinical_resource_fpage_more_text_location) || empty($clinical_resource_fpage_more_text_location)
	||
	!isset($clinical_resource_fpage_more_link_text_location) || empty($clinical_resource_fpage_more_link_text_location)
	||
	!isset($clinical_resource_fpage_more_link_descr_location) || empty($clinical_resource_fpage_more_link_descr_location)
	||
	!isset($condition_fpage_title_location) || empty($condition_fpage_title_location)
	||
	!isset($condition_fpage_intro_location) || empty($condition_fpage_intro_location)
	||
	!isset($treatment_fpage_title_location) || empty($treatment_fpage_title_location)
	||
	!isset($treatment_fpage_intro_location) || empty($treatment_fpage_intro_location)
	||
	!isset($condition_treatment_fpage_title_location) || empty($condition_treatment_fpage_title_location)
	||
	!isset($condition_treatment_fpage_intro_location) || empty($condition_treatment_fpage_intro_location)
) {

	$fpage_text_location_vars = uamswp_fad_fpage_text_location(
		$page_id, // int
		$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	);
		$provider_fpage_title_location = $fpage_text_location_vars['provider_fpage_title_location']; // string
		$provider_fpage_intro_location = $fpage_text_location_vars['provider_fpage_intro_location']; // string
		$provider_fpage_ref_main_title_location = $fpage_text_location_vars['provider_fpage_ref_main_title_location']; // string
		$provider_fpage_ref_main_intro_location = $fpage_text_location_vars['provider_fpage_ref_main_intro_location']; // string
		$provider_fpage_ref_main_link_location = $fpage_text_location_vars['provider_fpage_ref_main_link_location']; // string
		$provider_fpage_ref_top_title_location = $fpage_text_location_vars['provider_fpage_ref_top_title_location']; // string
		$provider_fpage_ref_top_intro_location = $fpage_text_location_vars['provider_fpage_ref_top_intro_location']; // string
		$provider_fpage_ref_top_link_location = $fpage_text_location_vars['provider_fpage_ref_top_link_location']; // string
		$location_descendant_fpage_title_location = $fpage_text_location_vars['location_descendant_fpage_title_location']; // string
		$location_descendant_fpage_intro_location = $fpage_text_location_vars['location_descendant_fpage_intro_location']; // string
		$location_descendant_fpage_ref_main_title_location = $fpage_text_location_vars['location_descendant_fpage_ref_main_title_location']; // string
		$location_descendant_fpage_ref_main_intro_location = $fpage_text_location_vars['location_descendant_fpage_ref_main_intro_location']; // string
		$location_descendant_fpage_ref_main_link_location = $fpage_text_location_vars['location_descendant_fpage_ref_main_link_location']; // string
		$expertise_fpage_title_location = $fpage_text_location_vars['expertise_fpage_title_location']; // string
		$expertise_fpage_intro_location = $fpage_text_location_vars['expertise_fpage_intro_location']; // string
		$expertise_fpage_ref_main_title_location = $fpage_text_location_vars['expertise_fpage_ref_main_title_location']; // string
		$expertise_fpage_ref_main_intro_location = $fpage_text_location_vars['expertise_fpage_ref_main_intro_location']; // string
		$expertise_fpage_ref_main_link_location = $fpage_text_location_vars['expertise_fpage_ref_main_link_location']; // string
		$expertise_fpage_ref_top_title_location = $fpage_text_location_vars['expertise_fpage_ref_top_title_location']; // string
		$expertise_fpage_ref_top_intro_location = $fpage_text_location_vars['expertise_fpage_ref_top_intro_location']; // string
		$expertise_fpage_ref_top_link_location = $fpage_text_location_vars['expertise_fpage_ref_top_link_location']; // string
		$clinical_resource_fpage_title_location = $fpage_text_location_vars['clinical_resource_fpage_title_location']; // string
		$clinical_resource_fpage_intro_location = $fpage_text_location_vars['clinical_resource_fpage_intro_location']; // string
		$clinical_resource_fpage_ref_main_title_location = $fpage_text_location_vars['clinical_resource_fpage_ref_main_title_location']; // string
		$clinical_resource_fpage_ref_main_intro_location = $fpage_text_location_vars['clinical_resource_fpage_ref_main_intro_location']; // string
		$clinical_resource_fpage_ref_main_link_location = $fpage_text_location_vars['clinical_resource_fpage_ref_main_link_location']; // string
		$clinical_resource_fpage_ref_top_title_location = $fpage_text_location_vars['clinical_resource_fpage_ref_top_title_location']; // string
		$clinical_resource_fpage_ref_top_intro_location = $fpage_text_location_vars['clinical_resource_fpage_ref_top_intro_location']; // string
		$clinical_resource_fpage_ref_top_link_location = $fpage_text_location_vars['clinical_resource_fpage_ref_top_link_location']; // string
		$clinical_resource_fpage_more_text_location = $fpage_text_location_vars['clinical_resource_fpage_more_text_location']; // string
		$clinical_resource_fpage_more_link_text_location = $fpage_text_location_vars['clinical_resource_fpage_more_link_text_location']; // string
		$clinical_resource_fpage_more_link_descr_location = $fpage_text_location_vars['clinical_resource_fpage_more_link_descr_location']; // string
		$condition_fpage_title_location = $fpage_text_location_vars['condition_fpage_title_location']; // string
		$condition_fpage_intro_location = $fpage_text_location_vars['condition_fpage_intro_location']; // string
		$treatment_fpage_title_location = $fpage_text_location_vars['treatment_fpage_title_location']; // string
		$treatment_fpage_intro_location = $fpage_text_location_vars['treatment_fpage_intro_location']; // string
		$condition_treatment_fpage_title_location = $fpage_text_location_vars['condition_treatment_fpage_title_location']; // string
		$condition_treatment_fpage_intro_location = $fpage_text_location_vars['condition_treatment_fpage_intro_location']; // string

 }