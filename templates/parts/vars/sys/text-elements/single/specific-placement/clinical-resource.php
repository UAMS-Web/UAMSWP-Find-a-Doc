<?php
/*
 * Template Name: System settings for text elements in a clinical resource 
 * subsection (or profile)
 * 
 * Description: A template part that defines a series of variables related to the 
 * text elements in a clinical resource subsection (or profile). This includes the 
 * titles and intro text of the subsection (or profile) and the text elements of 
 * the fake subpages (or sections) for related ontology items that have been 
 * placed in the subsection (or profile)
 * 
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 */

if (
	!isset($provider_fpage_title_clinical_resource) || empty($provider_fpage_title_clinical_resource)
	||
	!isset($provider_fpage_intro_clinical_resource) || empty($provider_fpage_intro_clinical_resource)
	||
	!isset($provider_fpage_ref_main_title_clinical_resource) || empty($provider_fpage_ref_main_title_clinical_resource)
	||
	!isset($provider_fpage_ref_main_intro_clinical_resource) || empty($provider_fpage_ref_main_intro_clinical_resource)
	||
	!isset($provider_fpage_ref_main_link_clinical_resource) || empty($provider_fpage_ref_main_link_clinical_resource)
	||
	!isset($provider_fpage_ref_main_title_clinical_resource) || empty($provider_fpage_ref_main_title_clinical_resource)
	||
	!isset($provider_fpage_ref_main_intro_clinical_resource) || empty($provider_fpage_ref_main_intro_clinical_resource)
	||
	!isset($provider_fpage_ref_main_link_clinical_resource) || empty($provider_fpage_ref_main_link_clinical_resource)
	||
	!isset($location_fpage_title_clinical_resource) || empty($location_fpage_title_clinical_resource)
	||
	!isset($location_fpage_intro_clinical_resource) || empty($location_fpage_intro_clinical_resource)
	||
	!isset($location_fpage_ref_main_title_clinical_resource) || empty($location_fpage_ref_main_title_clinical_resource)
	||
	!isset($location_fpage_ref_main_intro_clinical_resource) || empty($location_fpage_ref_main_intro_clinical_resource)
	||
	!isset($location_fpage_ref_main_link_clinical_resource) || empty($location_fpage_ref_main_link_clinical_resource)
	||
	!isset($expertise_fpage_title_clinical_resource) || empty($expertise_fpage_title_clinical_resource)
	||
	!isset($expertise_fpage_intro_clinical_resource) || empty($expertise_fpage_intro_clinical_resource)
	||
	!isset($expertise_fpage_ref_main_title_clinical_resource) || empty($expertise_fpage_ref_main_title_clinical_resource)
	||
	!isset($expertise_fpage_ref_main_intro_clinical_resource) || empty($expertise_fpage_ref_main_intro_clinical_resource)
	||
	!isset($expertise_fpage_ref_main_link_clinical_resource) || empty($expertise_fpage_ref_main_link_clinical_resource)
	||
	!isset($clinical_resource_fpage_title_clinical_resource) || empty($clinical_resource_fpage_title_clinical_resource)
	||
	!isset($clinical_resource_fpage_intro_clinical_resource) || empty($clinical_resource_fpage_intro_clinical_resource)
	||
	!isset($clinical_resource_fpage_ref_main_title_clinical_resource) || empty($clinical_resource_fpage_ref_main_title_clinical_resource)
	||
	!isset($clinical_resource_fpage_ref_main_intro_clinical_resource) || empty($clinical_resource_fpage_ref_main_intro_clinical_resource)
	||
	!isset($clinical_resource_fpage_ref_main_link_clinical_resource) || empty($clinical_resource_fpage_ref_main_link_clinical_resource)
	||
	!isset($clinical_resource_fpage_more_text_clinical_resource) || empty($clinical_resource_fpage_more_text_clinical_resource)
	||
	!isset($clinical_resource_fpage_more_link_text_clinical_resource) || empty($clinical_resource_fpage_more_link_text_clinical_resource)
	||
	!isset($clinical_resource_fpage_more_link_descr_clinical_resource) || empty($clinical_resource_fpage_more_link_descr_clinical_resource)
	||
	!isset($condition_fpage_title_clinical_resource) || empty($condition_fpage_title_clinical_resource)
	||
	!isset($condition_fpage_intro_clinical_resource) || empty($condition_fpage_intro_clinical_resource)
	||
	!isset($treatment_fpage_title_clinical_resource) || empty($treatment_fpage_title_clinical_resource)
	||
	!isset($treatment_fpage_intro_clinical_resource) || empty($treatment_fpage_intro_clinical_resource)
	||
	!isset($condition_treatment_fpage_title_clinical_resource) || empty($condition_treatment_fpage_title_clinical_resource)
	||
	!isset($condition_treatment_fpage_intro_clinical_resource) || empty($condition_treatment_fpage_intro_clinical_resource)
) {

	$fpage_text_clinical_resource_vars = isset($fpage_text_clinical_resource_vars) ? $fpage_text_clinical_resource_vars : uamswp_fad_fpage_text_clinical_resource(
		$page_id, // int
		$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	);
		$provider_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_title_clinical_resource']; // string
		$provider_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_intro_clinical_resource']; // string
		$provider_fpage_ref_main_title_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_title_clinical_resource']; // string
		$provider_fpage_ref_main_intro_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_intro_clinical_resource']; // string
		$provider_fpage_ref_main_link_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_link_clinical_resource']; // string
		$provider_fpage_ref_main_title_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_title_clinical_resource']; // string
		$provider_fpage_ref_main_intro_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_intro_clinical_resource']; // string
		$provider_fpage_ref_main_link_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_link_clinical_resource']; // string
		$location_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['location_fpage_title_clinical_resource']; // string
		$location_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['location_fpage_intro_clinical_resource']; // string
		$location_fpage_ref_main_title_clinical_resource = $fpage_text_clinical_resource_vars['location_fpage_ref_main_title_clinical_resource']; // string
		$location_fpage_ref_main_intro_clinical_resource = $fpage_text_clinical_resource_vars['location_fpage_ref_main_intro_clinical_resource']; // string
		$location_fpage_ref_main_link_clinical_resource = $fpage_text_clinical_resource_vars['location_fpage_ref_main_link_clinical_resource']; // string
		$expertise_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['expertise_fpage_title_clinical_resource']; // string
		$expertise_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['expertise_fpage_intro_clinical_resource']; // string
		$expertise_fpage_ref_main_title_clinical_resource = $fpage_text_clinical_resource_vars['expertise_fpage_ref_main_title_clinical_resource']; // string
		$expertise_fpage_ref_main_intro_clinical_resource = $fpage_text_clinical_resource_vars['expertise_fpage_ref_main_intro_clinical_resource']; // string
		$expertise_fpage_ref_main_link_clinical_resource = $fpage_text_clinical_resource_vars['expertise_fpage_ref_main_link_clinical_resource']; // string
		$clinical_resource_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_title_clinical_resource']; // string
		$clinical_resource_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_intro_clinical_resource']; // string
		$clinical_resource_fpage_ref_main_title_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_ref_main_title_clinical_resource']; // string
		$clinical_resource_fpage_ref_main_intro_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_ref_main_intro_clinical_resource']; // string
		$clinical_resource_fpage_ref_main_link_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_ref_main_link_clinical_resource']; // string
		$clinical_resource_fpage_more_text_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_more_text_clinical_resource']; // string
		$clinical_resource_fpage_more_link_text_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_more_link_text_clinical_resource']; // string
		$clinical_resource_fpage_more_link_descr_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_more_link_descr_clinical_resource']; // string
		$condition_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['condition_fpage_title_clinical_resource']; // string
		$condition_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['condition_fpage_intro_clinical_resource']; // string
		$treatment_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['treatment_fpage_title_clinical_resource']; // string
		$treatment_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['treatment_fpage_intro_clinical_resource']; // string
		$condition_treatment_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['condition_treatment_fpage_title_clinical_resource']; // string
		$condition_treatment_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['condition_treatment_fpage_intro_clinical_resource']; // string

}