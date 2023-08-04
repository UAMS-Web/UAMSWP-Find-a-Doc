<?php
/*
 * Template Name: System settings for location labels
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for location labels
 * 
 */

 if (
	!isset($location_single_name) || empty($location_single_name)
	||
	!isset($location_single_name_attr) || empty($location_single_name_attr)
	||
	!isset($location_plural_name) || empty($location_plural_name)
	||
	!isset($location_plural_name_attr) || empty($location_plural_name_attr)
	||
	!isset($placeholder_location_single_name) || empty($placeholder_location_single_name)
	||
	!isset($placeholder_location_plural_name) || empty($placeholder_location_plural_name)
	||
	!isset($placeholder_location_page_title) || empty($placeholder_location_page_title)
	||
	!isset($placeholder_location_page_title_phrase) || empty($placeholder_location_page_title_phrase)
) {

	$labels_location_vars = uamswp_fad_labels_location();
		$location_single_name = $labels_location_vars['location_single_name']; // string
		$location_single_name_attr = $labels_location_vars['location_single_name_attr']; // string
		$location_plural_name = $labels_location_vars['location_plural_name']; // string
		$location_plural_name_attr = $labels_location_vars['location_plural_name_attr']; // string
		$placeholder_location_single_name = $labels_location_vars['placeholder_location_single_name']; // string
		$placeholder_location_plural_name = $labels_location_vars['placeholder_location_plural_name']; // string
		$placeholder_location_page_title = $labels_location_vars['placeholder_location_page_title']; // string
		$placeholder_location_page_title_phrase = $labels_location_vars['placeholder_location_page_title_phrase']; // string

}