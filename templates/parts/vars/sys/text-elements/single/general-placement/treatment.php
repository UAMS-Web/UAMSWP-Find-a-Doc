<?php
/*
 * Template Name: System settings for text elements in general placements of 
 * treatments fake subpages (or sections)
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for the general placement of treatment item text elements
 * 
 * Required vars:
 * 	$page_id // int
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 */

 if (
	!isset($treatment_fpage_title_general) || empty($treatment_fpage_title_general)
	||
	!isset($treatment_fpage_intro_general) || empty($treatment_fpage_intro_general)
) {

	$fpage_text_treatment_general_vars = uamswp_fad_fpage_text_treatment_general(
		$page_id, // int
		$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	);
		$treatment_fpage_title_general = $fpage_text_treatment_general_vars['treatment_fpage_title_general']; // string
		$treatment_fpage_intro_general = $fpage_text_treatment_general_vars['treatment_fpage_intro_general']; // string

}
