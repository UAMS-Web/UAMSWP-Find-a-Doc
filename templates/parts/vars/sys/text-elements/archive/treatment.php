<?php
/*
 * Template Name: System settings for treatment archive text
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for treatment archive text elements
 */

// Call the function

	$archive_text_treatment_vars = ( isset($archive_text_treatment_vars) && !empty($archive_text_treatment_vars) ) ? $archive_text_treatment_vars : uamswp_fad_archive_text_treatment();

// Create a variable for each item in the array

	foreach ( $archive_text_treatment_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}

if (
	!isset($treatment_archive_link) || empty($treatment_archive_link)
) {

	$treatment_archive_link = get_post_type_archive_link( get_query_var('post_type') );

}