<?php
/*
 * Template Name: System settings for general placement of condition item text 
 * elements
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for the general placement of condition item text elements
 * 
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 */

if (
	!isset($condition_fpage_title_general) || empty($condition_fpage_title_general)
	||
	!isset($condition_fpage_intro_general) || empty($condition_fpage_intro_general)
) {

	$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
		$page_id, // int
		$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	);
		$condition_fpage_title_general = $fpage_text_condition_general_vars['condition_fpage_title_general']; // string
		$condition_fpage_intro_general = $fpage_text_condition_general_vars['condition_fpage_intro_general']; // string

}
