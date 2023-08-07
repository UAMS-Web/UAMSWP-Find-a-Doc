<?php
/*
 * Template Name: System settings for provider archive text
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for provider archive text elements
 */

 if (
	!isset($provider_archive_headline) || empty($provider_archive_headline)
	||
	!isset($provider_archive_headline_attr) || empty($provider_archive_headline_attr)
	||
	!isset($placeholder_provider_archive_headline) || empty($placeholder_provider_archive_headline)
) {

	$archive_text_provider_vars = isset($archive_text_provider_vars) ? $archive_text_provider_vars : uamswp_fad_archive_text_provider();
		$provider_archive_headline = $archive_text_provider_vars['provider_archive_headline']; // string
		$provider_archive_headline_attr = $archive_text_provider_vars['provider_archive_headline_attr']; // string
		$placeholder_provider_archive_headline = $archive_text_provider_vars['placeholder_provider_archive_headline']; // string

}