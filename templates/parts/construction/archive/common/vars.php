<?php
/*
 * Template Name: Archive Template Common Variables
 *
 * Description: A template part that defines the variables used on an all archive
 * templates
 */

// Get the page ID

	$page_id = get_the_ID(); // int

// Get the page title

	$page_title = isset(${ $variable_slug . '_archive_headline' }) ? ${ $variable_slug . '_archive_headline' } : ''; // string
	$page_title_attr = uamswp_attr_conversion($page_title);

	// Array for page titles and section titles

		$page_titles = array(
			'page_title'		=> $page_title,
			'page_title_attr'	=> $page_title_attr
		);

// Get the page URL

	$page_url = user_trailingslashit(get_permalink());

// Get the featured image

	$featured_image = isset(${ $variable_slug . '_archive_image_intro_text' }) ? ${ $variable_slug . '_archive_image_intro_text' } : ''; // Image ID // int

// Meta title

	$meta_title_base_addition = isset($meta_title_base_addition) ? $meta_title_base_addition : ${ $variable_slug . '_plural_name_attr' }; // Word or phrase to use to form base meta title
	$meta_title_base_order = isset($meta_title_base_order) ? $meta_title_base_order : array(); // array (optional) // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number
	$meta_title_enhanced_addition = isset($meta_title_enhanced_addition) ? $meta_title_enhanced_addition : ''; // string (optional) // Word or phrase to inject into base meta title to form enhanced meta title level 1
	$meta_title_enhanced_order = isset($meta_title_enhanced_order) ? $meta_title_enhanced_order : array(); // array (optional) // Pre-defined array for name order of enhanced meta title level 1 // Expects two values but will accommodate any number
	$meta_title_enhanced_x2_addition = isset($meta_title_enhanced_x2_addition) ? $meta_title_enhanced_x2_addition : ''; // string (optional) // Second word or phrase to inject into base meta title to form enhanced meta title level 2
	$meta_title_enhanced_x2_order = isset($meta_title_enhanced_x2_order) ? $meta_title_enhanced_x2_order : array(); // array (optional) // Pre-defined array for name order of enhanced meta title level 2 // Expects three values but will accommodate any number
	$meta_title_enhanced_x3_addition = isset($meta_title_enhanced_x3_addition) ? $meta_title_enhanced_x3_addition : ''; // string (optional) // Third word or phrase to inject into base meta title to form enhanced meta title level 3
	$meta_title_enhanced_x3_order = isset($meta_title_enhanced_x3_order) ? $meta_title_enhanced_x3_order : array(); // array (optional) // Pre-defined array for name order of enhanced meta title level 3 // Expects four values but will accommodate any number
	include( UAMS_FAD_PATH . '/templates/parts/html/meta/title.php' );

// Meta Description and Schema Description

	// Get excerpt

		$excerpt = isset(${ $variable_slug . '_archive_intro_text' }) ? ${ $variable_slug . '_archive_intro_text' } : '';
		$excerpt_attr = uamswp_attr_conversion($excerpt);
		$excerpt_user = true;

		if ( empty( $excerpt ) ) {

			$excerpt_user = false;

		}

// Set schema description

	$schema_description = $excerpt_attr; // Used for Schema Data. Should ALWAYS have a value

// Meta Keywords

	$keywords = isset(${ $variable_slug . '_foo' }) ? ${ $variable_slug . '_foo' } : '';
