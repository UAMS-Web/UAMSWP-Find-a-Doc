<?php
/*
 * Template Name: System settings for provider archive image elements
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for provider archive image elements
 */

if (
	!isset($provider_archive_image) || empty($provider_archive_image)
) {

	$archive_image_provider_vars = isset($archive_image_provider_vars) ? $archive_image_provider_vars : uamswp_fad_archive_image_provider();
		$provider_archive_image = $archive_image_provider_vars['provider_archive_image']; // int

}