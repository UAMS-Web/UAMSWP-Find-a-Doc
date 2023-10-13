<?php
/*
 * Template Name: Clinical Resource Posts Per Page
 *
 * Description: A template part that defines the general maximum number of
 * clinical resource items to display on a fake subpage (or section)
 */

// Call the function

	$posts_per_page_clinical_resource_general_vars = ( isset($posts_per_page_clinical_resource_general_vars) && !empty($posts_per_page_clinical_resource_general_vars) ) ? $posts_per_page_clinical_resource_general_vars : uamswp_fad_posts_per_page_clinical_resource_general();

// Create a variable for each item in the array

	foreach ( $posts_per_page_clinical_resource_general_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}