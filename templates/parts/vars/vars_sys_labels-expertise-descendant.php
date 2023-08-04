<?php
/*
 * Template Name: System settings for descendant area of expertise labels
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for descendant area of expertise labels
 * 
 */

 if (
	!isset($expertise_descendant_single_name) || empty($foo)
	||
	!isset($expertise_descendant_single_name_attr) || empty($foo)
	||
	!isset($expertise_descendant_plural_name) || empty($foo)
	||
	!isset($expertise_descendant_plural_name_attr) || empty($foo)
	||
	!isset($placeholder_expertise_descendant_single_name) || empty($foo)
	||
	!isset($placeholder_expertise_descendant_plural_name) || empty($foo)
) {

	$labels_expertise_descendant_vars = uamswp_fad_labels_expertise_descendant();
		$expertise_descendant_single_name = $labels_expertise_descendant_vars['expertise_descendant_single_name']; // string
		$expertise_descendant_single_name_attr = $labels_expertise_descendant_vars['expertise_descendant_single_name_attr']; // string
		$expertise_descendant_plural_name = $labels_expertise_descendant_vars['expertise_descendant_plural_name']; // string
		$expertise_descendant_plural_name_attr = $labels_expertise_descendant_vars['expertise_descendant_plural_name_attr']; // string
		$placeholder_expertise_descendant_single_name = $labels_expertise_descendant_vars['placeholder_expertise_descendant_single_name']; // string
		$placeholder_expertise_descendant_plural_name = $labels_expertise_descendant_vars['placeholder_expertise_descendant_plural_name']; // string

}