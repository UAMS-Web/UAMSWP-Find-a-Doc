<?php
/*
 * Template Name: System settings for area of expertise labels
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for area of expertise labels
 * 
 */

 if (
	!isset($expertise_single_name) || empty($expertise_single_name)
	||
	!isset($expertise_single_name_attr) || empty($expertise_single_name_attr)
	||
	!isset($expertise_plural_name) || empty($expertise_plural_name)
	||
	!isset($expertise_plural_name_attr) || empty($expertise_plural_name_attr)
	||
	!isset($placeholder_expertise_single_name) || empty($placeholder_expertise_single_name)
	||
	!isset($placeholder_expertise_plural_name) || empty($placeholder_expertise_plural_name)
	||
	!isset($placeholder_expertise_page_title) || empty($placeholder_expertise_page_title)
) {

	$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
		$expertise_single_name = $labels_expertise_vars['expertise_single_name']; // string
		$expertise_single_name_attr = $labels_expertise_vars['expertise_single_name_attr']; // string
		$expertise_plural_name = $labels_expertise_vars['expertise_plural_name']; // string
		$expertise_plural_name_attr = $labels_expertise_vars['expertise_plural_name_attr']; // string
		$placeholder_expertise_single_name = $labels_expertise_vars['placeholder_expertise_single_name']; // string
		$placeholder_expertise_plural_name = $labels_expertise_vars['placeholder_expertise_plural_name']; // string
		$placeholder_expertise_page_title = $labels_expertise_vars['placeholder_expertise_page_title']; // string

}