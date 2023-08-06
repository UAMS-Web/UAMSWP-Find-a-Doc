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

	if (
		!isset($meta_og_image) || empty($meta_og_image)
		||
		!isset($meta_og_image_width) || empty($meta_og_image_width)
		||
		!isset($meta_og_image_height) || empty($meta_og_image_height)
		||
		!isset($meta_twitter_image) || empty($meta_twitter_image)
		||
		!isset($meta_twitter_image_width) || empty($meta_twitter_image_width)
		||
		!isset($meta_twitter_image_height) || empty($meta_twitter_image_height)
		||
		!isset($meta_twitter_image_alt) || empty($meta_twitter_image_alt)
	) {

		$meta_image_values_vars = uamswp_meta_image_values(
			$featured_image // int // ID of the featured image
		);
			$meta_og_image = $meta_image_values_vars['meta_og_image']; // string // Image URL for Open Graph
			$meta_og_image_width = $meta_image_values_vars['meta_og_image_width']; // int
			$meta_og_image_height = $meta_image_values_vars['meta_og_image_height']; // int
			$meta_twitter_image = $meta_image_values_vars['meta_twitter_image']; // string // Image URL for Twitter
			$meta_twitter_image_width = $meta_image_values_vars['meta_twitter_image_width']; // int
			$meta_twitter_image_height = $meta_image_values_vars['meta_twitter_image_height']; // int
			$meta_twitter_image_alt = $meta_image_values_vars['meta_twitter_image_alt']; // string // Alt text of the image in $meta_twitter_image
			$meta_oembed_thumbnail_size = '';
			$meta_oembed_thumbnail = '';
			$meta_oembed_thumbnail_width = '';
			$meta_oembed_thumbnail_height = '';
		
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
