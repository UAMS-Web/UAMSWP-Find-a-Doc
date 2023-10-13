<?php
/*
 * Template Name: Set image values for Open Graph, Twitter and Oembed
 *
 * Description: A template part that defines a series of variables defining the
 * image URLs and their dimensions for use in social meta tags
 *
 * Required vars:
 * 	$featured_image // int // ID of the featured image
 */

if ( $featured_image ) {

	// Call the function

		$meta_image_values_vars = ( isset($meta_image_values_vars) && !empty($meta_image_values_vars) ) ? $meta_image_values_vars : uamswp_meta_image_values(
			$featured_image // int // ID of the featured image
		);

	// Create a variable for each item in the array

		foreach ( $meta_image_values_vars as $key => $value ) {

			${$key} = $value; // Create a variable for each item in the array

		}

} else {

	$meta_image_values_vars = '';
	$meta_og_image = '';
	$meta_og_image_width = '';
	$meta_og_image_height = '';
	$meta_twitter_image = '';
	$meta_twitter_image_width = '';
	$meta_twitter_image_height = '';
	$meta_twitter_image_alt = '';
	$meta_oembed_thumbnail_size = '';
	$meta_oembed_thumbnail = '';
	$meta_oembed_thumbnail_width = '';
	$meta_oembed_thumbnail_height = '';

}
