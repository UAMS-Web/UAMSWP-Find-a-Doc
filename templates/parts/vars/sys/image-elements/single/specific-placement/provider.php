<?php
/*
 * Template Name: System settings for image elements in a provider subsection (or 
 * profile)
 * 
 * Description: A template part that defines a series of variables related to the 
 * image elements in a provider subsection (or profile). This includes the 
 * featured of the subsection (or profile) and the featured images of the fake 
 * subpages (or sections) for related ontology items that have been placed in the 
 * subsection (or profile)
 * 
 * Required vars:
 * 	$page_id // int
 */

if (
	!isset($location_fpage_image_provider) || empty($location_fpage_image_provider)
	||
	!isset($expertise_fpage_image_provider) || empty($expertise_fpage_image_provider)
	||
	!isset($clinical_resource_fpage_image_provider) || empty($clinical_resource_fpage_image_provider)
) {

	$fpage_image_provider_vars = isset($fpage_image_provider_vars) ? $fpage_image_provider_vars : uamswp_fad_fpage_image_provider(
		$page_id // int
	);
		$location_fpage_image_provider = $fpage_image_location_vars['location_fpage_image_provider']; // int
		$expertise_fpage_image_provider = $fpage_image_location_vars['expertise_fpage_image_provider']; // int
		$clinical_resource_fpage_image_provider = $fpage_image_location_vars['clinical_resource_fpage_image_provider']; // int

}