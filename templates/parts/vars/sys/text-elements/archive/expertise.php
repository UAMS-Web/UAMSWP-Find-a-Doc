<?php
/*
 * Template Name: System settings for area of expertise archive text
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for area of expertise archive text elements
 */

 if (
	!isset($expertise_archive_headline) || empty($expertise_archive_headline)
	||
	!isset($expertise_archive_headline_attr) || empty($expertise_archive_headline_attr)
	||
	!isset($expertise_archive_intro_text) || empty($expertise_archive_intro_text)
	||
	!isset($placeholder_expertise_archive_headline) || empty($placeholder_expertise_archive_headline)
	||
	!isset($placeholder_expertise_archive_intro_text) || empty($placeholder_expertise_archive_intro_text)
) {

	$archive_text_expertise_vars = isset($archive_text_expertise_vars) ? $archive_text_expertise_vars : uamswp_fad_archive_text_expertise();
		$expertise_archive_headline = $archive_text_expertise_vars['expertise_archive_headline']; // string
		$expertise_archive_headline_attr = $archive_text_expertise_vars['expertise_archive_headline_attr']; // string
		$expertise_archive_intro_text = $archive_text_expertise_vars['expertise_archive_intro_text']; // string
		$placeholder_expertise_archive_headline = $archive_text_expertise_vars['placeholder_expertise_archive_headline']; // string
		$placeholder_expertise_archive_intro_text = $archive_text_expertise_vars['placeholder_expertise_archive_intro_text']; // string

}