<?php
/*
 * Template Name: System settings for location archive image elements
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for location archive image elements
 */

 if (
	!isset($location_archive_image) || empty($location_archive_image)
) {

	$archive_image_location_vars = uamswp_fad_archive_image_location();
		$location_archive_image = $archive_image_location_vars['location_archive_image']; // int

}