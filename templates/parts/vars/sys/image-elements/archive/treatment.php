<?php
/*
 * Template Name: System settings for treatment archive image elements
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for treatment archive image elements
 */

 if (
	!isset($treatment_archive_image) || empty($treatment_archive_image)
) {

	$archive_image_treatment_vars = uamswp_fad_archive_image_treatment();
		$treatment_archive_image = $archive_image_treatment_vars['treatment_archive_image']; // int

}