<?php
/*
 * Template Name: System settings for location archive text
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for location archive text elements
 */

 if (
	!isset($location_archive_headline) || empty($location_archive_headline)
	||
	!isset($location_archive_headline_attr) || empty($location_archive_headline_attr)
	||
	!isset($placeholder_location_archive_headline) || empty($placeholder_location_archive_headline)
) {

	$archive_text_location_vars = uamswp_fad_archive_text_location();
		$location_archive_headline = $archive_text_location_vars['location_archive_headline']; // string
		$location_archive_headline_attr = $archive_text_location_vars['location_archive_headline_attr']; // string
		$placeholder_location_archive_headline = $archive_text_location_vars['placeholder_location_archive_headline']; // string

}