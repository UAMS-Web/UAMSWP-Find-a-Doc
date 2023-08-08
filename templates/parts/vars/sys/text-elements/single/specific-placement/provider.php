<?php
/*
 * Template Name: System settings for text elements in a provider subsection (or 
 * profile)
 * 
 * Description: A template part that defines a series of variables related to the 
 * text elements in a provider subsection (or profile). This includes the titles 
 * and intro text of the subsection (or profile) and the text elements of the fake 
 * subpages (or sections) for related ontology items that have been placed in the 
 * subsection (or profile)
 * 
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 */

if (
	!isset($location_fpage_title_provider) || empty($location_fpage_title_provider)
	||
	!isset($location_fpage_intro_provider) || empty($location_fpage_intro_provider)
	||
	!isset($location_fpage_ref_main_title_provider) || empty($location_fpage_ref_main_title_provider)
	||
	!isset($location_fpage_ref_main_intro_provider) || empty($location_fpage_ref_main_intro_provider)
	||
	!isset($location_fpage_ref_main_link_provider) || empty($location_fpage_ref_main_link_provider)
	||
	!isset($expertise_fpage_title_provider) || empty($expertise_fpage_title_provider)
	||
	!isset($expertise_fpage_intro_provider) || empty($expertise_fpage_intro_provider)
	||
	!isset($expertise_fpage_ref_main_title_provider) || empty($expertise_fpage_ref_main_title_provider)
	||
	!isset($expertise_fpage_ref_main_intro_provider) || empty($expertise_fpage_ref_main_intro_provider)
	||
	!isset($expertise_fpage_ref_main_link_provider) || empty($expertise_fpage_ref_main_link_provider)
	||
	!isset($clinical_resource_fpage_title_provider) || empty($clinical_resource_fpage_title_provider)
	||
	!isset($clinical_resource_fpage_intro_provider) || empty($clinical_resource_fpage_intro_provider)
	||
	!isset($clinical_resource_fpage_ref_main_title_provider) || empty($clinical_resource_fpage_ref_main_title_provider)
	||
	!isset($clinical_resource_fpage_ref_main_intro_provider) || empty($clinical_resource_fpage_ref_main_intro_provider)
	||
	!isset($clinical_resource_fpage_ref_main_link_provider) || empty($clinical_resource_fpage_ref_main_link_provider)
	||
	!isset($clinical_resource_fpage_more_text_provider) || empty($clinical_resource_fpage_more_text_provider)
	||
	!isset($clinical_resource_fpage_more_link_text_provider) || empty($clinical_resource_fpage_more_link_text_provider)
	||
	!isset($clinical_resource_fpage_more_link_descr_provider) || empty($clinical_resource_fpage_more_link_descr_provider)
	||
	!isset($condition_fpage_title_provider) || empty($condition_fpage_title_provider)
	||
	!isset($condition_fpage_intro_provider) || empty($condition_fpage_intro_provider)
	||
	!isset($treatment_fpage_title_provider) || empty($treatment_fpage_title_provider)
	||
	!isset($treatment_fpage_intro_provider) || empty($treatment_fpage_intro_provider)
	||
	!isset($condition_treatment_fpage_title_provider) || empty($condition_treatment_fpage_title_provider)
	||
	!isset($condition_treatment_fpage_intro_provider) || empty($condition_treatment_fpage_intro_provider)
) {

	$fpage_text_provider_vars = isset($fpage_text_provider_vars) ? $fpage_text_provider_vars : uamswp_fad_fpage_text_provider(
		$page_id, // int
		$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	);
		$location_fpage_title_provider = $fpage_text_provider_vars['location_fpage_title_provider']; // string
		$location_fpage_intro_provider = $fpage_text_provider_vars['location_fpage_intro_provider']; // string
		$location_fpage_ref_main_title_provider = $fpage_text_provider_vars['location_fpage_ref_main_title_provider']; // string
		$location_fpage_ref_main_intro_provider = $fpage_text_provider_vars['location_fpage_ref_main_intro_provider']; // string
		$location_fpage_ref_main_link_provider = $fpage_text_provider_vars['location_fpage_ref_main_link_provider']; // string
		$expertise_fpage_title_provider = $fpage_text_provider_vars['expertise_fpage_title_provider']; // string
		$expertise_fpage_intro_provider = $fpage_text_provider_vars['expertise_fpage_intro_provider']; // string
		$expertise_fpage_ref_main_title_provider = $fpage_text_provider_vars['expertise_fpage_ref_main_title_provider']; // string
		$expertise_fpage_ref_main_intro_provider = $fpage_text_provider_vars['expertise_fpage_ref_main_intro_provider']; // string
		$expertise_fpage_ref_main_link_provider = $fpage_text_provider_vars['expertise_fpage_ref_main_link_provider']; // string
		$clinical_resource_fpage_title_provider = $fpage_text_provider_vars['clinical_resource_fpage_title_provider']; // string
		$clinical_resource_fpage_intro_provider = $fpage_text_provider_vars['clinical_resource_fpage_intro_provider']; // string
		$clinical_resource_fpage_ref_main_title_provider = $fpage_text_provider_vars['clinical_resource_fpage_ref_main_title_provider']; // string
		$clinical_resource_fpage_ref_main_intro_provider = $fpage_text_provider_vars['clinical_resource_fpage_ref_main_intro_provider']; // string
		$clinical_resource_fpage_ref_main_link_provider = $fpage_text_provider_vars['clinical_resource_fpage_ref_main_link_provider']; // string
		$clinical_resource_fpage_more_text_provider = $fpage_text_provider_vars['clinical_resource_fpage_more_text_provider']; // string
		$clinical_resource_fpage_more_link_text_provider = $fpage_text_provider_vars['clinical_resource_fpage_more_link_text_provider']; // string
		$clinical_resource_fpage_more_link_descr_provider = $fpage_text_provider_vars['clinical_resource_fpage_more_link_descr_provider']; // string
		$condition_fpage_title_provider = $fpage_text_provider_vars['condition_fpage_title_provider']; // string
		$condition_fpage_intro_provider = $fpage_text_provider_vars['condition_fpage_intro_provider']; // string
		$treatment_fpage_title_provider = $fpage_text_provider_vars['treatment_fpage_title_provider']; // string
		$treatment_fpage_intro_provider = $fpage_text_provider_vars['treatment_fpage_intro_provider']; // string
		$condition_treatment_fpage_title_provider = $fpage_text_provider_vars['condition_treatment_fpage_title_provider']; // string
		$condition_treatment_fpage_intro_provider = $fpage_text_provider_vars['condition_treatment_fpage_intro_provider']; // string

 }