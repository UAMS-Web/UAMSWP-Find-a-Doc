<?php
/*
 * Template Name: System settings for combined condition and treatment labels
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for combined condition and treatment labels
 * 
 */

 if (
	!isset($condition_treatment_single_name) || empty($condition_treatment_single_name)
	||
	!isset($condition_treatment_single_name_attr) || empty($condition_treatment_single_name_attr)
	||
	!isset($condition_treatment_plural_name) || empty($condition_treatment_plural_name)
	||
	!isset($condition_treatment_plural_name_attr) || empty($condition_treatment_plural_name_attr)
	||
	!isset($placeholder_condition_treatment_single_name) || empty($placeholder_condition_treatment_single_name)
	||
	!isset($placeholder_condition_treatment_plural_name) || empty($placeholder_condition_treatment_plural_name)
) {

	$labels_condition_treatment_vars = isset($labels_condition_treatment_vars) ? $labels_condition_treatment_vars : uamswp_fad_labels_condition_treatment();
		$condition_treatment_single_name = $labels_condition_treatment_vars['condition_treatment_single_name']; // string
		$condition_treatment_single_name_attr = $labels_condition_treatment_vars['condition_treatment_single_name_attr']; // string
		$condition_treatment_plural_name = $labels_condition_treatment_vars['condition_treatment_plural_name']; // string
		$condition_treatment_plural_name_attr = $labels_condition_treatment_vars['condition_treatment_plural_name_attr']; // string
		$placeholder_condition_treatment_single_name = $labels_condition_treatment_vars['placeholder_condition_treatment_single_name']; // string
		$placeholder_condition_treatment_plural_name = $labels_condition_treatment_vars['placeholder_condition_treatment_plural_name']; // string

}