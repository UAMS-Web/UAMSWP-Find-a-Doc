<?php
/*
 * Template Name: System settings for treatment archive text
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for treatment archive text elements
 */

 if (
	!isset($treatment_archive_headline) || empty($treatment_archive_headline)
	||
	!isset($treatment_archive_headline_attr) || empty($treatment_archive_headline_attr)
	||
	!isset($treatment_archive_intro_text) || empty($treatment_archive_intro_text)
	||
	!isset($placeholder_treatment_archive_headline) || empty($placeholder_treatment_archive_headline)
	||
	!isset($placeholder_treatment_archive_intro_text) || empty($placeholder_treatment_archive_intro_text)
) {

	$archive_text_treatment_vars = uamswp_fad_archive_text_treatment();
		$treatment_archive_headline = $archive_text_treatment_vars['treatment_archive_headline']; // string
		$treatment_archive_headline_attr = $archive_text_treatment_vars['treatment_archive_headline_attr']; // string
		$treatment_archive_intro_text = $archive_text_treatment_vars['treatment_archive_intro_text']; // string
		$placeholder_treatment_archive_headline = $archive_text_treatment_vars['placeholder_treatment_archive_headline']; // string
		$placeholder_treatment_archive_intro_text = $archive_text_treatment_vars['placeholder_treatment_archive_intro_text']; // string

}

if (
	!isset($treatment_archive_link) || empty($treatment_archive_link)
) {

	$treatment_archive_link = get_post_type_archive_link( get_query_var('post_type') );

}