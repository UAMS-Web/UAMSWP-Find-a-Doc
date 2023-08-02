<?php
/*
 * Template Name: System settings for clinical resource labels
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for clinical resource labels
 * 
 */

$labels_clinical_resource_vars = isset($labels_clinical_resource_vars) ? $labels_clinical_resource_vars : uamswp_fad_labels_clinical_resource();
	$clinical_resource_single_name = $labels_clinical_resource_vars['clinical_resource_single_name']; // string
	$clinical_resource_single_name_attr = $labels_clinical_resource_vars['clinical_resource_single_name_attr']; // string
	$clinical_resource_plural_name = $labels_clinical_resource_vars['clinical_resource_plural_name']; // string
	$clinical_resource_plural_name_attr = $labels_clinical_resource_vars['clinical_resource_plural_name_attr']; // string
	$placeholder_clinical_resource_single_name = $labels_clinical_resource_vars['placeholder_clinical_resource_single_name']; // string
	$placeholder_clinical_resource_plural_name = $labels_clinical_resource_vars['placeholder_clinical_resource_plural_name']; // string