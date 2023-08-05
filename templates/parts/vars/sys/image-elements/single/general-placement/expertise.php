<?php
/*
 * Template Name: System settings for image elements in general placements of 
 * area of expertise fake subpages (or sections)
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for image elements associated with fake subpages (or sections) 
 * for areas of expertise with no specific placement in mind
 */

if (
	!isset($expertise_fpage_image_general) || empty($expertise_fpage_image_general)
	||
	!isset($expertise_descendant_fpage_image_general) || empty($expertise_descendant_fpage_image_general)
) {

	$fpage_image_expertise_general_vars = isset($fpage_image_expertise_general_vars) ? $fpage_image_expertise_general_vars : uamswp_fad_fpage_image_expertise_general();
		$expertise_fpage_image_general = $fpage_image_expertise_general_vars['expertise_fpage_image_general']; // int
		$expertise_descendant_fpage_image_general = $fpage_image_expertise_general_vars['expertise_descendant_fpage_image_general']; // int

}