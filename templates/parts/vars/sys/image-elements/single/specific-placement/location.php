<?php
/*
 * Template Name: System settings for image elements in a location subsection (or 
 * profile)
 * 
 * Description: A template part that defines a series of variables related to the 
 * image elements in a location subsection (or profile). This includes the 
 * featured of the subsection (or profile) and the featured images of the fake 
 * subpages (or sections) for related ontology items that have been placed in the 
 * subsection (or profile)
 * 
 * Required vars:
 * 	$page_id // int
 */

if (
	!isset($provider_fpage_image_location) || empty($provider_fpage_image_location)
	||
	!isset($location_descendant_fpage_image_location) || empty($location_descendant_fpage_image_location)
	||
	!isset($expertise_fpage_image_location) || empty($expertise_fpage_image_location)
	||
	!isset($clinical_resource_fpage_image_location) || empty($clinical_resource_fpage_image_location)
) {

	$fpage_image_location_vars = isset($fpage_image_location_vars) ? $fpage_image_location_vars : uamswp_fad_fpage_image_location(
		$page_id // int
	);
		$provider_fpage_image_location = $fpage_image_location_vars['provider_fpage_image_location']; // int
		$location_descendant_fpage_image_location = $fpage_image_location_vars['location_descendant_fpage_image_location']; // int
		$expertise_fpage_image_location = $fpage_image_location_vars['expertise_fpage_image_location']; // int
		$clinical_resource_fpage_image_location = $fpage_image_location_vars['clinical_resource_fpage_image_location']; // int

}