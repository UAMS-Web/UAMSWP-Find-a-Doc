<?php
/*
 * Template Name: Fake Area of Expertise Subpage Template Variables
 *
 * Description: A template part that defines the variables used on a single
 * template for a fake area of expertise subpage
 */

// Get the page URL and slug

	// Fake subpage

		$current_fpage = get_query_var('fpage'); // Fake subpage slug
		$fpage_url = !empty($current_fpage) ? trailingslashit($page_url) . user_trailingslashit($current_fpage) : $page_url; // Fake subpage URL

// Convert fake subpage slug for variable names

	$fpage_var_name_conversion = array(
		'providers' => 'provider',
		'locations' => 'location',
		'specialties' => 'expertise_descendant',
		'resources' => 'clinical_resource',
		'related' => 'expertise'
	);

	$fpage_var_name = $fpage_var_name_conversion[$current_fpage];

// Get the page title and other name values

	// Name of ontology item type represented by this fake subpage

		$fpage_name = ${ $fpage_var_name . '_plural_name' }; // Name of ontology item type represented by this fake subpage
		$fpage_name_attr = uamswp_attr_conversion($fpage_name);

	// Fake subpage page title

		$fpage_title = ${ $fpage_var_name . '_fpage_title_expertise' }; // Fake subpage page title
		$fpage_title_attr = uamswp_attr_conversion($fpage_title);

	 // Fake subpage intro text

		$fpage_intro = ${ $fpage_var_name . '_fpage_intro_expertise' }; // Fake subpage intro text
		$fpage_intro_attr = uamswp_attr_conversion($fpage_intro); // Attribute-friendly version of fake subpage intro text

// Image values

	// Get the featured image ID

		// 'Parent' area of expertise (rather than fake subpage)

			$featured_image_parent = $expertise_featured_image; // Image ID
			$featured_image_parent = $featured_image_parent ? $featured_image_parent : '';

		// Fake subpage (override overview page values)

			$featured_image = ${ $fpage_var_name . '_fpage_featured_image_expertise' }; // Image ID
			$featured_image = $featured_image ? $featured_image : '';

// Page template class (override overview page values)
$template_type = 'page_landing';

// Meta title (override overview page values)

	$meta_title_base_addition = $fpage_name_attr; // Word or phrase to use to form base meta title
	$meta_title_enhanced_addition = $page_title_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
	$meta_title_base_order = array( $meta_title_base_addition, $meta_title_enhanced_addition ); // Override default base meta title structure to force inclusion of $meta_title_enhanced_addition
	include( UAMS_FAD_PATH . '/templates/parts/html/meta/title.php' );

// Meta Description and Schema Description (override overview page values)

	// Get excerpt

		$excerpt = ${ $fpage_var_name . '_fpage_short_desc_expertise' };
		$excerpt_user = true;

		if ( empty( $excerpt ) ) {

			$excerpt_user = false;

		}

		$excerpt_attr = uamswp_attr_conversion($excerpt);

	// Set schema description

		$schema_description = $excerpt_attr; // Used for Schema Data. Should ALWAYS have a value

// Canonical URL
$canonical_url = $fpage_url;

// Page title configuration (override overview page values)

	$entry_header_style = 'graphic'; // Entry header style
	$entry_title_text = $fpage_title; // Regular title
	$entry_title_text_supertitle = ''; // Optional supertitle
	$entry_title_text_subtitle = ''; // Optional subtitle
	$entry_title_text_body = $fpage_intro; // Optional lead paragraph
	$entry_title_image_desktop = ''; // Desktop breakpoint image ID
	$entry_title_image_mobile = ''; // Optional mobile breakpoint image ID