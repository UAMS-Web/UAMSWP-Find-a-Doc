<?php
/*
 * Template Name: System settings for image elements in general placements of 
 * location fake subpages (or sections)
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for image elements associated with fake subpages (or sections) 
 * for locations with no specific placement in mind
 */

if (
	!isset($location_fpage_image_general) || empty($location_fpage_image_general)
	||
	!isset($location_descendant_fpage_image_general) || empty($location_descendant_fpage_image_general)
) {

	$fpage_image_location_general_vars = isset($fpage_image_location_general_vars) ? $fpage_image_location_general_vars : uamswp_fad_fpage_image_location_general();
		$location_fpage_image_general = $fpage_image_location_general_vars['location_fpage_image_general']; // int
		$location_descendant_fpage_image_general = $fpage_image_location_general_vars['location_descendant_fpage_image_general']; // int

}