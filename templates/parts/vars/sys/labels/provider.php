<?php
/*
 * Template Name: System settings for provider labels
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for provider labels
 * 
 */

 if (
	!isset($provider_single_name) || empty($provider_single_name)
	||
	!isset($provider_single_name_attr) || empty($provider_single_name_attr)
	||
	!isset($provider_plural_name) || empty($provider_plural_name)
	||
	!isset($provider_plural_name_attr) || empty($provider_plural_name_attr)
	||
	!isset($placeholder_provider_single_name) || empty($placeholder_provider_single_name)
	||
	!isset($placeholder_provider_plural_name) || empty($placeholder_provider_plural_name)
	||
	!isset($placeholder_provider_short_name) || empty($placeholder_provider_short_name)
	||
	!isset($placeholder_provider_short_name_possessive) || empty($placeholder_provider_short_name_possessive)
) {

	$labels_provider_vars = isset($labels_provider_vars) ? $labels_provider_vars : uamswp_fad_labels_provider();
		$provider_single_name = $labels_provider_vars['provider_single_name']; // string
		$provider_single_name_attr = $labels_provider_vars['provider_single_name_attr']; // string
		$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string
		$provider_plural_name_attr = $labels_provider_vars['provider_plural_name_attr']; // string
		$placeholder_provider_single_name = $labels_provider_vars['placeholder_provider_single_name']; // string
		$placeholder_provider_plural_name = $labels_provider_vars['placeholder_provider_plural_name']; // string
		$placeholder_provider_short_name = $labels_provider_vars['placeholder_provider_short_name']; // string
		$placeholder_provider_short_name_possessive = $labels_provider_vars['placeholder_provider_short_name_possessive']; // string

}