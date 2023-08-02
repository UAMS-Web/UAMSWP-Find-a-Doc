<?php
/*
 * Template Name: System settings for condition labels
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for condition labels
 * 
 */

$labels_condition_vars = isset($labels_condition_vars) ? $labels_condition_vars : uamswp_fad_labels_condition();
	$condition_single_name = $labels_condition_vars['condition_single_name']; // string
	$condition_single_name_attr = $labels_condition_vars['condition_single_name_attr']; // string
	$condition_plural_name = $labels_condition_vars['condition_plural_name']; // string
	$condition_plural_name_attr = $labels_condition_vars['condition_plural_name_attr']; // string
	$placeholder_condition_single_name = $labels_condition_vars['placeholder_condition_single_name']; // string
	$placeholder_condition_plural_name = $labels_condition_vars['placeholder_condition_plural_name']; // string