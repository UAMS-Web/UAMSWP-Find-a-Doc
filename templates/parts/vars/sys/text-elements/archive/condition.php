<?php
/*
 * Template Name: System settings for condition archive text
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for condition archive text elements
 */

 if (
	!isset($condition_archive_headline) || empty($condition_archive_headline)
	||
	!isset($condition_archive_headline_attr) || empty($condition_archive_headline_attr)
	||
	!isset($condition_archive_intro_text) || empty($condition_archive_intro_text)
	||
	!isset($placeholder_condition_archive_headline) || empty($placeholder_condition_archive_headline)
	||
	!isset($placeholder_condition_archive_intro_text) || empty($placeholder_condition_archive_intro_text)
	||
	!isset($condition_archive_link) || empty($condition_archive_link)
) {

	$archive_text_condition_vars = isset($archive_text_condition_vars) ? $archive_text_condition_vars : uamswp_fad_archive_text_condition();
		$condition_archive_headline = $archive_text_condition_vars['condition_archive_headline']; // string
		$condition_archive_headline_attr = $archive_text_condition_vars['condition_archive_headline_attr']; // string
		$condition_archive_intro_text = $archive_text_condition_vars['condition_archive_intro_text']; // string
		$placeholder_condition_archive_headline = $archive_text_condition_vars['placeholder_condition_archive_headline']; // string
		$placeholder_condition_archive_intro_text = $archive_text_condition_vars['placeholder_condition_archive_intro_text']; // string
		$condition_archive_link = get_post_type_archive_link( get_query_var('post_type') );

}