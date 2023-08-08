<?php
/*
 * Template Name: System settings for text elements in an area of expertise 
 * subsection (or profile)
 * 
 * Description: A template part that defines a series of variables related to the 
 * text elements in an area of expertise subsection (or profile). This includes 
 * the titles and intro text of the subsection (or profile) and the text elements 
 * of the fake subpages (or sections) for related ontology items that have been 
 * placed in the subsection (or profile)
 * 
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 * 	$ontology_type // bool
 */

if (
	!isset($expertise_page_title_options) || empty($expertise_page_title_options)
	||
	!isset($expertise_page_title) || empty($expertise_page_title)
	||
	!isset($expertise_page_intro) || empty($expertise_page_intro)
	||
	!isset($expertise_page_image) || empty($expertise_page_image)
	||
	!isset($expertise_page_image_mobile) || empty($expertise_page_image_mobile)
	||
	!isset($expertise_short_desc) || empty($expertise_short_desc)
	||
	!isset($provider_fpage_title_expertise) || empty($provider_fpage_title_expertise)
	||
	!isset($provider_fpage_intro_expertise) || empty($provider_fpage_intro_expertise)
	||
	!isset($provider_fpage_ref_main_title_expertise) || empty($provider_fpage_ref_main_title_expertise)
	||
	!isset($provider_fpage_ref_main_intro_expertise) || empty($provider_fpage_ref_main_intro_expertise)
	||
	!isset($provider_fpage_ref_main_link_expertise) || empty($provider_fpage_ref_main_link_expertise)
	||
	!isset($provider_fpage_ref_top_title_expertise) || empty($provider_fpage_ref_top_title_expertise)
	||
	!isset($provider_fpage_ref_top_intro_expertise) || empty($provider_fpage_ref_top_intro_expertise)
	||
	!isset($provider_fpage_ref_top_link_expertise) || empty($provider_fpage_ref_top_link_expertise)
	||
	!isset($provider_fpage_short_desc_expertise) || empty($provider_fpage_short_desc_expertise)
	||
	!isset($location_fpage_title_expertise) || empty($location_fpage_title_expertise)
	||
	!isset($location_fpage_intro_expertise) || empty($location_fpage_intro_expertise)
	||
	!isset($location_fpage_short_desc_expertise) || empty($location_fpage_short_desc_expertise)
	||
	!isset($location_fpage_ref_main_title_expertise) || empty($location_fpage_ref_main_title_expertise)
	||
	!isset($location_fpage_ref_main_intro_expertise) || empty($location_fpage_ref_main_intro_expertise)
	||
	!isset($location_fpage_ref_main_link_expertise) || empty($location_fpage_ref_main_link_expertise)
	||
	!isset($location_fpage_ref_top_title_expertise) || empty($location_fpage_ref_top_title_expertise)
	||
	!isset($location_fpage_ref_top_intro_expertise) || empty($location_fpage_ref_top_intro_expertise)
	||
	!isset($location_fpage_ref_top_link_expertise) || empty($location_fpage_ref_top_link_expertise)
	||
	!isset($expertise_descendant_fpage_title_expertise) || empty($expertise_descendant_fpage_title_expertise)
	||
	!isset($expertise_descendant_fpage_intro_expertise) || empty($expertise_descendant_fpage_intro_expertise)
	||
	!isset($expertise_descendant_fpage_short_desc_expertise) || empty($expertise_descendant_fpage_short_desc_expertise)
	||
	!isset($expertise_descendant_fpage_ref_main_title_expertise) || empty($expertise_descendant_fpage_ref_main_title_expertise)
	||
	!isset($expertise_descendant_fpage_ref_main_intro_expertise) || empty($expertise_descendant_fpage_ref_main_intro_expertise)
	||
	!isset($expertise_descendant_fpage_ref_main_link_expertise) || empty($expertise_descendant_fpage_ref_main_link_expertise)
	||
	!isset($expertise_fpage_title_expertise) || empty($expertise_fpage_title_expertise)
	||
	!isset($expertise_fpage_intro_expertise) || empty($expertise_fpage_intro_expertise)
	||
	!isset($expertise_fpage_short_desc_expertise) || empty($expertise_fpage_short_desc_expertise)
	||
	!isset($expertise_fpage_ref_main_title_expertise) || empty($expertise_fpage_ref_main_title_expertise)
	||
	!isset($expertise_fpage_ref_main_intro_expertise) || empty($expertise_fpage_ref_main_intro_expertise)
	||
	!isset($expertise_fpage_ref_main_link_expertise) || empty($expertise_fpage_ref_main_link_expertise)
	||
	!isset($clinical_resource_fpage_title_expertise) || empty($clinical_resource_fpage_title_expertise)
	||
	!isset($clinical_resource_fpage_intro_expertise) || empty($clinical_resource_fpage_intro_expertise)
	||
	!isset($clinical_resource_fpage_ref_main_title_expertise) || empty($clinical_resource_fpage_ref_main_title_expertise)
	||
	!isset($clinical_resource_fpage_ref_main_intro_expertise) || empty($clinical_resource_fpage_ref_main_intro_expertise)
	||
	!isset($clinical_resource_fpage_ref_main_link_expertise) || empty($clinical_resource_fpage_ref_main_link_expertise)
	||
	!isset($clinical_resource_fpage_ref_top_title_expertise) || empty($clinical_resource_fpage_ref_top_title_expertise)
	||
	!isset($clinical_resource_fpage_ref_top_intro_expertise) || empty($clinical_resource_fpage_ref_top_intro_expertise)
	||
	!isset($clinical_resource_fpage_ref_top_link_expertise) || empty($clinical_resource_fpage_ref_top_link_expertise)
	||
	!isset($clinical_resource_fpage_more_text_expertise) || empty($clinical_resource_fpage_more_text_expertise)
	||
	!isset($clinical_resource_fpage_more_link_text_expertise) || empty($clinical_resource_fpage_more_link_text_expertise)
	||
	!isset($clinical_resource_fpage_more_link_descr_expertise) || empty($clinical_resource_fpage_more_link_descr_expertise)
	||
	!isset($clinical_resource_fpage_short_desc_expertise) || empty($clinical_resource_fpage_short_desc_expertise)
	||
	!isset($condition_fpage_title_expertise) || empty($condition_fpage_title_expertise)
	||
	!isset($condition_fpage_intro_expertise) || empty($condition_fpage_intro_expertise)
	||
	!isset($treatment_fpage_title_expertise) || empty($treatment_fpage_title_expertise)
	||
	!isset($treatment_fpage_intro_expertise) || empty($treatment_fpage_intro_expertise)
	||
	!isset($condition_treatment_fpage_title_expertise) || empty($condition_treatment_fpage_title_expertise)
	||
	!isset($condition_treatment_fpage_intro_expertise) || empty($condition_treatment_fpage_intro_expertise)
) {

	$fpage_text_expertise_vars = isset($fpage_text_expertise_vars) ? $fpage_text_expertise_vars : uamswp_fad_fpage_text_expertise(
		$page_id, // int
		$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		$ontology_type // bool
	);
		$expertise_page_title_options = $fpage_text_expertise_vars['expertise_page_title_options']; // string
		$expertise_page_title = $fpage_text_expertise_vars['expertise_page_title']; // string
		$expertise_page_intro = $fpage_text_expertise_vars['expertise_page_intro']; // string
		$expertise_page_image = $fpage_text_expertise_vars['expertise_page_image']; // string
		$expertise_page_image_mobile = $fpage_text_expertise_vars['expertise_page_image_mobile']; // string
		$expertise_short_desc = $fpage_text_expertise_vars['expertise_short_desc']; // string
		$provider_fpage_title_expertise = $fpage_text_expertise_vars['provider_fpage_title_expertise']; // string
		$provider_fpage_intro_expertise = $fpage_text_expertise_vars['provider_fpage_intro_expertise']; // string
		$provider_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['provider_fpage_ref_main_title_expertise']; // string
		$provider_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['provider_fpage_ref_main_intro_expertise']; // string
		$provider_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['provider_fpage_ref_main_link_expertise']; // string
		$provider_fpage_ref_top_title_expertise = $fpage_text_expertise_vars['provider_fpage_ref_top_title_expertise']; // string
		$provider_fpage_ref_top_intro_expertise = $fpage_text_expertise_vars['provider_fpage_ref_top_intro_expertise']; // string
		$provider_fpage_ref_top_link_expertise = $fpage_text_expertise_vars['provider_fpage_ref_top_link_expertise']; // string
		$provider_fpage_short_desc_expertise = $fpage_text_expertise_vars['provider_fpage_short_desc_expertise']; // string
		$location_fpage_title_expertise = $fpage_text_expertise_vars['location_fpage_title_expertise']; // string
		$location_fpage_intro_expertise = $fpage_text_expertise_vars['location_fpage_intro_expertise']; // string
		$location_fpage_short_desc_expertise = $fpage_text_expertise_vars['location_fpage_short_desc_expertise']; // string
		$location_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['location_fpage_ref_main_title_expertise']; // string
		$location_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['location_fpage_ref_main_intro_expertise']; // string
		$location_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['location_fpage_ref_main_link_expertise']; // string
		$location_fpage_ref_top_title_expertise = $fpage_text_expertise_vars['location_fpage_ref_top_title_expertise']; // string
		$location_fpage_ref_top_intro_expertise = $fpage_text_expertise_vars['location_fpage_ref_top_intro_expertise']; // string
		$location_fpage_ref_top_link_expertise = $fpage_text_expertise_vars['location_fpage_ref_top_link_expertise']; // string
		$expertise_descendant_fpage_title_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_title_expertise']; // string
		$expertise_descendant_fpage_intro_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_intro_expertise']; // string
		$expertise_descendant_fpage_short_desc_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_short_desc_expertise']; // string
		$expertise_descendant_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_ref_main_title_expertise']; // string
		$expertise_descendant_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_ref_main_intro_expertise']; // string
		$expertise_descendant_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_ref_main_link_expertise']; // string
		$expertise_fpage_title_expertise = $fpage_text_expertise_vars['expertise_fpage_title_expertise']; // string
		$expertise_fpage_intro_expertise = $fpage_text_expertise_vars['expertise_fpage_intro_expertise']; // string
		$expertise_fpage_short_desc_expertise = $fpage_text_expertise_vars['expertise_fpage_short_desc_expertise']; // string
		$expertise_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['expertise_fpage_ref_main_title_expertise']; // string
		$expertise_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['expertise_fpage_ref_main_intro_expertise']; // string
		$expertise_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['expertise_fpage_ref_main_link_expertise']; // string
		$clinical_resource_fpage_title_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_title_expertise']; // string
		$clinical_resource_fpage_intro_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_intro_expertise']; // string
		$clinical_resource_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_main_title_expertise']; // string
		$clinical_resource_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_main_intro_expertise']; // string
		$clinical_resource_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_main_link_expertise']; // string
		$clinical_resource_fpage_ref_top_title_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_top_title_expertise']; // string
		$clinical_resource_fpage_ref_top_intro_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_top_intro_expertise']; // string
		$clinical_resource_fpage_ref_top_link_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_top_link_expertise']; // string
		$clinical_resource_fpage_more_text_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_more_text_expertise']; // string
		$clinical_resource_fpage_more_link_text_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_more_link_text_expertise']; // string
		$clinical_resource_fpage_more_link_descr_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_more_link_descr_expertise']; // string
		$clinical_resource_fpage_short_desc_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_short_desc_expertise']; // string
		$condition_fpage_title_expertise = $fpage_text_expertise_vars['condition_fpage_title_expertise']; // string
		$condition_fpage_intro_expertise = $fpage_text_expertise_vars['condition_fpage_intro_expertise']; // string
		$treatment_fpage_title_expertise = $fpage_text_expertise_vars['treatment_fpage_title_expertise']; // string
		$treatment_fpage_intro_expertise = $fpage_text_expertise_vars['treatment_fpage_intro_expertise']; // string
		$condition_treatment_fpage_title_expertise = $fpage_text_expertise_vars['condition_treatment_fpage_title_expertise']; // string
		$condition_treatment_fpage_intro_expertise = $fpage_text_expertise_vars['condition_treatment_fpage_intro_expertise']; // string

}