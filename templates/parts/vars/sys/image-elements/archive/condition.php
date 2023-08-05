<?php
/*
 * Template Name: System settings for condition archive image elements
 * 
 * Description: A template part that defines a series of variables related to the 
 * system settings for condition archive image elements
 */

 if (
	!isset($condition_archive_image) || empty($condition_archive_image)
) {

	$archive_image_condition_vars = uamswp_fad_archive_image_condition();
		$condition_archive_image = $archive_image_condition_vars['condition_archive_image']; // int

}