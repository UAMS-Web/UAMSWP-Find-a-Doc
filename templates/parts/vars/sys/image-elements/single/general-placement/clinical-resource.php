<?php
/*
 * Template Name: System settings for image elements in general placements of 
 * clinical resource fake subpages (or sections)
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for image elements associated with fake subpages (or sections) 
 * for clinical resources with no specific placement in mind
 */

if (
	!isset($clinical_resource_fpage_image_general) || empty($clinical_resource_fpage_image_general)
) {

	$fpage_image_clinical_resource_general_vars = isset($fpage_image_clinical_resource_general_vars) ? $fpage_image_clinical_resource_general_vars : uamswp_fad_fpage_image_clinical_resource_general();
		$clinical_resource_fpage_image_general = $fpage_image_clinical_resource_general_vars['clinical_resource_fpage_image_general']; // int

}