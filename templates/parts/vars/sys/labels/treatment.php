<?php
/*
 * Template Name: System settings for treatment labels
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for treatment labels
 * 
 */

 if (
	!isset($treatment_single_name) || empty($treatment_single_name)
	||
	!isset($treatment_single_name_attr) || empty($treatment_single_name_attr)
	||
	!isset($treatment_plural_name) || empty($treatment_plural_name)
	||
	!isset($treatment_plural_name_attr) || empty($treatment_plural_name_attr)
	||
	!isset($placeholder_treatment_single_name) || empty($placeholder_treatment_single_name)
	||
	!isset($placeholder_treatment_plural_name) || empty($placeholder_treatment_plural_name)
) {

	$labels_treatment_vars = isset($labels_treatment_vars) ? $labels_treatment_vars : uamswp_fad_labels_treatment();
		$treatment_single_name = $labels_treatment_vars['treatment_single_name']; // string
		$treatment_single_name_attr = $labels_treatment_vars['treatment_single_name_attr']; // string
		$treatment_plural_name = $labels_treatment_vars['treatment_plural_name']; // string
		$treatment_plural_name_attr = $labels_treatment_vars['treatment_plural_name_attr']; // string
		$placeholder_treatment_single_name = $labels_treatment_vars['placeholder_treatment_single_name']; // string
		$placeholder_treatment_plural_name = $labels_treatment_vars['placeholder_treatment_plural_name']; // string

}