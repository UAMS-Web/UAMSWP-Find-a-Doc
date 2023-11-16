<?php
/*
 * Template Name: System settings for image elements in general placements of
 * area of expertise fake subpages (or sections)
 *
 * Description: A template part that defines a series of variables related to the
 * system settings for image elements associated with fake subpages (or sections)
 * for areas of expertise with no specific placement in mind
 */

// Call the function

	$fpage_image_expertise_general_vars = ( isset($fpage_image_expertise_general_vars) && !empty($fpage_image_expertise_general_vars) ) ? $fpage_image_expertise_general_vars : uamswp_fad_fpage_image_expertise_general();

// Create a variable for each item in the array

	foreach ( $fpage_image_expertise_general_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}