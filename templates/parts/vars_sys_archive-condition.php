<?php
/*
 * Template Name: System settings for condition archive text
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for condition archive text elements
 */

$archive_text_condition_vars = uamswp_fad_archive_text_condition();
	$condition_archive_headline = $archive_text_condition_vars['condition_archive_headline']; // string
	$condition_archive_headline_attr = $archive_text_condition_vars['condition_archive_headline_attr']; // string
	$condition_archive_intro_text = $archive_text_condition_vars['condition_archive_intro_text']; // string
	$placeholder_condition_archive_headline = $archive_text_condition_vars['placeholder_condition_archive_headline']; // string
	$placeholder_condition_archive_intro_text = $archive_text_condition_vars['placeholder_condition_archive_intro_text']; // string
	$condition_archive_link = get_post_type_archive_link( get_query_var('post_type') );