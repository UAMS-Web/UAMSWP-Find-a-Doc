<?php
/*
 * Template Name: System settings for treatment archive text
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for treatment archive text elements
 */

$archive_text_treatment_vars = isset($archive_text_treatment_vars) ? $archive_text_treatment_vars : uamswp_fad_archive_text_treatment();
	$treatment_archive_headline = $archive_text_treatment_vars['treatment_archive_headline']; // string
	$treatment_archive_headline_attr = $archive_text_treatment_vars['treatment_archive_headline_attr']; // string
	$treatment_archive_intro_text = $archive_text_treatment_vars['treatment_archive_intro_text']; // string
	$placeholder_treatment_archive_headline = $archive_text_treatment_vars['placeholder_treatment_archive_headline']; // string
	$placeholder_treatment_archive_intro_text = $archive_text_treatment_vars['placeholder_treatment_archive_intro_text']; // string
$treatment_archive_link = get_post_type_archive_link( get_query_var('post_type') );