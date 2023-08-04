<?php
/*
 * Template Name: Clinical Resource Posts Per Page
 * 
 * Description: A template part that defines the general maximum number of 
 * clinical resource items to display on a fake subpage (or section)
 */

if (
	!isset($clinical_resource_posts_per_page_fpage) || empty($clinical_resource_posts_per_page_fpage)
	||
	!isset($clinical_resource_posts_per_page_section) || empty($clinical_resource_posts_per_page_section)
) {

	$posts_per_page_clinical_resource_general_vars = isset($posts_per_page_clinical_resource_general_vars) ? $posts_per_page_clinical_resource_general_vars : uamswp_fad_posts_per_page_clinical_resource_general();
		$clinical_resource_posts_per_page_fpage = $posts_per_page_clinical_resource_general_vars['clinical_resource_posts_per_page_fpage']; // int
		$clinical_resource_posts_per_page_section = $posts_per_page_clinical_resource_general_vars['clinical_resource_posts_per_page_section']; // int

}