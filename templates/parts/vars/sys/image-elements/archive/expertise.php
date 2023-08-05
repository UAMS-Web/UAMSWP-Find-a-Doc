<?php
/*
 * Template Name: System settings for area of expertise archive image elements
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for area of expertise archive image elements
 */

 if (
	!isset($expertise_archive_image) || empty($expertise_archive_image)
) {

	$archive_image_expertise_vars = uamswp_fad_archive_image_expertise();
		$expertise_archive_image = $archive_image_expertise_vars['expertise_archive_image']; // int

}