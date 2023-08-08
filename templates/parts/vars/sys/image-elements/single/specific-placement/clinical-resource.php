<?php
/*
 * Template Name: System settings for image elements in a clinical resource 
 * subsection (or profile)
 * 
 * Description: A template part that defines a series of variables related to the 
 * image elements in a clinical resource subsection (or profile). This includes 
 * the featured of the subsection (or profile) and the featured images of the fake 
 * subpages (or sections) for related ontology items that have been placed in the 
 * subsection (or profile)
 * 
 * Required vars:
 * 	$page_id // int
 */
 
 if (
	!isset($provider_fpage_image_clinical_resource) || empty($provider_fpage_image_clinical_resource)
	||
	!isset($location_fpage_image_clinical_resource) || empty($location_fpage_image_clinical_resource)
	||
	!isset($expertise_fpage_image_clinical_resource) || empty($expertise_fpage_image_clinical_resource)
	||
	!isset($clinical_resource_fpage_image_clinical_resource) || empty($clinical_resource_fpage_image_clinical_resource)
) {

	$fpage_image_clinical_resource_vars = isset($fpage_image_clinical_resource_vars) ? $fpage_image_clinical_resource_vars : uamswp_fad_fpage_image_clinical_resource(
		$page_id // int
	);
		$provider_fpage_image_clinical_resource = $fpage_image_clinical_resource_vars['provider_fpage_image_clinical_resource']; // int
		$location_fpage_image_clinical_resource = $fpage_image_clinical_resource_vars['location_fpage_image_clinical_resource']; // int
		$expertise_fpage_image_clinical_resource = $fpage_image_clinical_resource_vars['expertise_fpage_image_clinical_resource']; // int
		$clinical_resource_fpage_image_clinical_resource = $fpage_image_clinical_resource_vars['clinical_resource_fpage_image_clinical_resource']; // int

}