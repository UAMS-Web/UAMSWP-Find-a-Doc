<?php
/*
 * Template Name: System settings for image elements in an area of expertise 
 * subsection (or profile)
 * 
 * Description: A template part that defines a series of variables related to the 
 * image elements in an area of expertise subsection (or profile). This includes 
 * the featured of the subsection (or profile) and the featured images of the fake 
 * subpages (or sections) for related ontology items that have been placed in the 
 * subsection (or profile)
 * 
 * Required vars:
 * 	$page_id // int
 */

 if (
	!isset($expertise_featured_image) || empty($expertise_featured_image)
	||
	!isset($expertise_featured_image_url) || empty($expertise_featured_image_url)
	||
	!isset($provider_fpage_featured_image_expertise) || empty($provider_fpage_featured_image_expertise)
	||
	!isset($provider_fpage_featured_image_expertise_url) || empty($provider_fpage_featured_image_expertise_url)
	||
	!isset($location_fpage_featured_image_expertise) || empty($location_fpage_featured_image_expertise)
	||
	!isset($location_fpage_featured_image_expertise_url) || empty($location_fpage_featured_image_expertise_url)
	||
	!isset($expertise_fpage_featured_image_expertise) || empty($expertise_fpage_featured_image_expertise)
	||
	!isset($expertise_fpage_featured_image_expertise_url) || empty($expertise_fpage_featured_image_expertise_url)
	||
	!isset($expertise_descendant_fpage_featured_image_expertise) || empty($expertise_descendant_fpage_featured_image_expertise)
	||
	!isset($expertise_descendant_fpage_featured_image_expertise_url) || empty($expertise_descendant_fpage_featured_image_expertise_url)
	||
	!isset($clinical_resource_fpage_featured_image_expertise) || empty($clinical_resource_fpage_featured_image_expertise)
	||
	!isset($clinical_resource_fpage_featured_image_expertise_url) || empty($clinical_resource_fpage_featured_image_expertise_url)
) {

	$fpage_image_expertise_vars = uamswp_fad_fpage_image_expertise(
		$page_id // int
	);
		$expertise_featured_image = $fpage_image_expertise_vars['expertise_featured_image']; // int
		$expertise_featured_image_url = $fpage_image_expertise_vars['expertise_featured_image_url']; // string
		$provider_fpage_featured_image_expertise = $fpage_image_expertise_vars['provider_fpage_featured_image_expertise']; // int
		$provider_fpage_featured_image_expertise_url = $fpage_image_expertise_vars['provider_fpage_featured_image_expertise_url']; // string
		$location_fpage_featured_image_expertise = $fpage_image_expertise_vars['location_fpage_featured_image_expertise']; // int
		$location_fpage_featured_image_expertise_url = $fpage_image_expertise_vars['location_fpage_featured_image_expertise_url']; // string
		$expertise_fpage_featured_image_expertise = $fpage_image_expertise_vars['expertise_fpage_featured_image_expertise']; // int
		$expertise_fpage_featured_image_expertise_url = $fpage_image_expertise_vars['expertise_fpage_featured_image_expertise_url']; // string
		$expertise_descendant_fpage_featured_image_expertise = $fpage_image_expertise_vars['expertise_descendant_fpage_featured_image_expertise']; // int
		$expertise_descendant_fpage_featured_image_expertise_url = $fpage_image_expertise_vars['expertise_descendant_fpage_featured_image_expertise_url']; // string
		$clinical_resource_fpage_featured_image_expertise = $fpage_image_expertise_vars['clinical_resource_fpage_featured_image_expertise']; // int
		$clinical_resource_fpage_featured_image_expertise_url = $fpage_image_expertise_vars['clinical_resource_fpage_featured_image_expertise_url']; // string

}