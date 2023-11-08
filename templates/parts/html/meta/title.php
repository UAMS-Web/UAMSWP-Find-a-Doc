<?php
/*
 * Template Name: Title tag
 *
 * Description: A template part that constructs the value for the <title> tag
 *
 * Required vars:
 * 	$page_title_attr // string
 *
 * Optional vars:
 * 	$meta_title_base_addition // string // Word or phrase to use to form base meta title // Defaults to $page_title_attr
 * 	$meta_title_base_order // array // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number of values
 * 	$meta_title_enhanced_addition // string // Word or phrase to inject into base meta title to form enhanced meta title level 1
 * 	$meta_title_enhanced_order // array // Pre-defined array for name order of enhanced meta title level 1 // Expects two values but will accommodate any number of values
 * 	$meta_title_enhanced_x2_addition // string // Second word or phrase to inject into base meta title to form enhanced meta title level 2
 * 	$meta_title_enhanced_x2_order // array // Pre-defined array for name order of enhanced meta title level 2 // Expects three values but will accommodate any number of values
 * 	$meta_title_enhanced_x3_addition // string // Third word or phrase to inject into base meta title to form enhanced meta title level 3
 * 	$meta_title_enhanced_x3_order // array // Pre-defined array for name order of enhanced meta title level 3 // Expects four values but will accommodate any number of values
 */

 if (
	!isset($meta_title) || empty($meta_title)
) {

	$meta_title_base_addition = ( isset($meta_title_base_addition) && !empty($meta_title_base_addition) ) ? $meta_title_base_addition : '';
	$meta_title_base_order = ( isset($meta_title_base_order) && !empty($meta_title_base_order) ) ? $meta_title_base_order : array();
	$meta_title_enhanced_addition = ( isset($meta_title_enhanced_addition) && !empty($meta_title_enhanced_addition) ) ? $meta_title_enhanced_addition : '';
	$meta_title_enhanced_order = ( isset($meta_title_enhanced_order) && !empty($meta_title_enhanced_order) ) ? $meta_title_enhanced_order : array();
	$meta_title_enhanced_x2_addition = ( isset($meta_title_enhanced_x2_addition) && !empty($meta_title_enhanced_x2_addition) ) ? $meta_title_enhanced_x2_addition : '';
	$meta_title_enhanced_x2_order = ( isset($meta_title_enhanced_x2_order) && !empty($meta_title_enhanced_x2_order) ) ? $meta_title_enhanced_x2_order : array();
	$meta_title_enhanced_x3_addition = ( isset($meta_title_enhanced_x3_addition) && !empty($meta_title_enhanced_x3_addition) ) ? $meta_title_enhanced_x3_addition : '';
	$meta_title_enhanced_x3_order = ( isset($meta_title_enhanced_x3_order) && !empty($meta_title_enhanced_x3_order) ) ? $meta_title_enhanced_x3_order : array();

	$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(
		$page_title_attr, // string
		$meta_title_base_addition, // string // Optional // Word or phrase to use to form base meta title // Defaults to $page_title_attr
		$meta_title_base_order, // array // Optional // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number
		$meta_title_enhanced_addition, // string // Optional // Word or phrase to inject into base meta title to form enhanced meta title level 1
		$meta_title_enhanced_order, // array // Optional // Pre-defined array for name order of enhanced meta title level 1 // Expects two values but will accommodate any number
		$meta_title_enhanced_x2_addition, // string // Optional // Second word or phrase to inject into base meta title to form enhanced meta title level 2
		$meta_title_enhanced_x2_order, // array // Optional // Pre-defined array for name order of enhanced meta title level 2 // Expects three values but will accommodate any number
		$meta_title_enhanced_x3_addition, // string // Optional // Third word or phrase to inject into base meta title to form enhanced meta title level 3
		$meta_title_enhanced_x3_order // array // Optional // Pre-defined array for name order of enhanced meta title level 3 // Expects four values but will accommodate any number
	);
		$meta_title = $meta_title_vars['meta_title']; // string

}