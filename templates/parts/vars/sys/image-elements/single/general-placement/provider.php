<?php
/*
 * Template Name: System settings for image elements in general placements of 
 * provider fake subpages (or sections)
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for image elements associated with fake subpages (or sections) 
 * for providers with no specific placement in mind
 */

if (
	!isset($provider_fpage_image_general) || empty($provider_fpage_image_general)
) {

	$fpage_image_provider_general_vars = isset($fpage_image_provider_general_vars) ? $fpage_image_provider_general_vars : uamswp_fad_fpage_image_provider_general();
		$provider_fpage_image_general = $fpage_image_provider_general_vars['provider_fpage_image_general']; // int

}