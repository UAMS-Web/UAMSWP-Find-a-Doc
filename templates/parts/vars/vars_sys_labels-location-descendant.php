<?php
/*
 * Template Name: System settings for descendant location labels
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for descendant location labels
 * 
 */

 if (
	!isset($location_descendant_single_name) || empty($location_descendant_single_name)
	||
	!isset($location_descendant_single_name_attr) || empty($location_descendant_single_name_attr)
	||
	!isset($location_descendant_plural_name) || empty($location_descendant_plural_name)
	||
	!isset($location_descendant_plural_name_attr) || empty($location_descendant_plural_name_attr)
	||
	!isset($placeholder_location_descendant_single_name) || empty($placeholder_location_descendant_single_name)
	||
	!isset($placeholder_location_descendant_plural_name) || empty($placeholder_location_descendant_plural_name)
) {

	$labels_location_descendant_vars = uamswp_fad_labels_location_descendant();
		$location_descendant_single_name = $labels_location_descendant_vars['location_descendant_single_name']; // string
		$location_descendant_single_name_attr = $labels_location_descendant_vars['location_descendant_single_name_attr']; // string
		$location_descendant_plural_name = $labels_location_descendant_vars['location_descendant_plural_name']; // string
		$location_descendant_plural_name_attr = $labels_location_descendant_vars['location_descendant_plural_name_attr']; // string
		$placeholder_location_descendant_single_name = $labels_location_descendant_vars['placeholder_location_descendant_single_name']; // string
		$placeholder_location_descendant_plural_name = $labels_location_descendant_vars['placeholder_location_descendant_plural_name']; // string

}