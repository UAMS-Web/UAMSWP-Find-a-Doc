<?php
/*
 * Template Name: System settings for clinical resource archive image elements
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for clinical resource archive image elements
 */

 if (
	!isset($clinical_resource_archive_image) || empty($clinical_resource_archive_image)
) {

	$archive_image_clinical_resource_vars = uamswp_fad_archive_image_clinical_resource();
		$clinical_resource_archive_image = $archive_image_clinical_resource_vars['clinical_resource_archive_image']; // int

}