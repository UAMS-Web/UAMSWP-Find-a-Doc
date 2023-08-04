<?php
/*
 * Template Name: System settings for clinical resource archive text
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for clinical resource archive text elements
 */

 if (
	!isset($clinical_resource_archive_headline) || empty($clinical_resource_archive_headline)
	||
	!isset($clinical_resource_archive_headline_attr) || empty($clinical_resource_archive_headline_attr)
	||
	!isset($placeholder_clinical_resource_archive_headline) || empty($placeholder_clinical_resource_archive_headline)
) {

	$archive_text_clinical_resource_vars = uamswp_fad_archive_text_clinical_resource();
		$clinical_resource_archive_headline = $archive_text_clinical_resource_vars['clinical_resource_archive_headline']; // string
		$clinical_resource_archive_headline_attr = $archive_text_clinical_resource_vars['clinical_resource_archive_headline_attr']; // string
		$placeholder_clinical_resource_archive_headline = $archive_text_clinical_resource_vars['placeholder_clinical_resource_archive_headline']; // string

}