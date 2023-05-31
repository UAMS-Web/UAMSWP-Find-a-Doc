<?php
/*
 * Template Name: Fake Area of Expertise Clinical Resources Subpage
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	uamswp_fad_labels_provider();

	// Get system settings for location labels
	uamswp_fad_labels_location();

	// Get system settings for area of expertise labels
	uamswp_fad_labels_expertise();

	// Get system settings for clinical resource labels
	uamswp_fad_labels_clinical_resource();

	// Get system settings for condition labels
	uamswp_fad_labels_conditions();

	// Get system settings for treatment labels
	uamswp_fad_labels_treatments();

// Set general variables
$page_id = get_the_ID();
$page_title = get_the_title(); // Title of Area of Expertise
$page_title_attr = uamswp_attr_conversion($page_title);
$fpage_name = $clinical_resource_plural_name; // Fake subpage name
$fpage_name_attr = uamswp_attr_conversion($fpage_name);
$fpage_title = $clinical_resource_plural_name . ' Related to ' . $page_title; // Fake subpage page title
$fpage_title_attr = uamswp_attr_conversion($fpage_title);
$page_url = get_permalink();

// Area of Expertise Content Type
$ontology_type = get_field('expertise_type'); // True is ontology type, false is content type

// Get site header and site nav values for ontology subsections
uamswp_fad_ontology_site_values();

// Queries for whether each of the associated ontology content sections should be displayed on ontology pages/subsections

	// Query for whether associated providers content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_providers_query();

	// Query for whether associated locations content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_locations_query();

	// Query for whether descendant ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_descendants_query();

	// Query for whether related ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_related_query();

	// Query for whether associated clinical resources content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_resources_query();

	// Query for whether associated conditions content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_conditions_query();

	// Query for whether associated treatments content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_treatments_query();

// Override theme's method of defining the meta page title
add_filter('seopress_titles_title', 'uamswp_fad_fpage_title', 15, 2);

// Add meta keywords
add_action( 'wp_head', 'uamswp_expertise_header_metadata' );
$keywords = get_field('expertise_alternate_names');

// Modify site header

	// Remove the site header set by the theme
	remove_action( 'genesis_header', 'uamswp_site_image', 5 );

	// Add ontology subsection site header
	add_action( 'genesis_header', 'uamswp_fad_ontology_header', 5 );

// Modify primary navigation

	// Remove the primary navigation set by the theme
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

	// Add ontology subsection primary navigation
	add_action( 'genesis_after_header', 'uamswp_fad_ontology_nav_menu', 5 );

// Add page template class to body element's classes
add_filter( 'body_class', 'uamswp_page_body_class' );
$template_type = 'page_landing';

// Add fake subpage to breadcrumbs
add_filter('seopress_pro_breadcrumbs_crumbs', 'uamswp_fad_fpage_breadcrumbs');

// Add bg-white class to article.entry element
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

// Modify Entry Title

	// Remove Genesis-standard post title and markup
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

	// Construct non-standard post title
	add_action( 'genesis_before_content', 'uamswp_fad_post_title' );
	$entry_header_style = 'graphic'; // Entry header style
	$entry_title_text = $fpage_title; // Regular title
	$entry_title_text_supertitle = ''; // Optional supertitle
	$entry_title_text_subtitle = ''; // Optional subtitle
	$entry_title_text_body = ''; // Optional lead paragraph
	$entry_title_image_desktop = ''; // Desktop breakpoint image ID
	$entry_title_image_mobile = ''; // Optional mobile breakpoint image ID

// Remove the post info (byline) from the entry header and the entry footer
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php

// Remove the entry meta (tags) from the entry footer, including markup
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

// Construct page content

	// Remove content
	remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

	// Display ontology page content
	add_action( 'genesis_entry_content', 'uamswp_expertise_resource', 14 );
	function uamswp_expertise_resource() {
		global $post;
		global $page_title;
		global $show_related_resource_section;
		global $resources;
		global $resource_query;
		global $resource_postsPerPage;
	
		global $provider_single_name;
		global $provider_single_name_attr;
		global $provider_plural_name;
		global $provider_plural_name_attr;
		global $location_single_name;
		global $location_single_name_attr;
		global $location_plural_name;
		global $location_plural_name_attr;
		global $expertise_single_name;
		global $expertise_single_name_attr;
		global $expertise_plural_name;
		global $expertise_plural_name_attr;
		global $expertise_archive_headline;
		global $expertise_archive_headline_attr;
		global $expertise_archive_intro_text;
		global $clinical_resource_single_name;
		global $clinical_resource_single_name_attr;
		global $clinical_resource_plural_name;
		global $clinical_resource_plural_name_attr;
		global $conditions_single_name;
		global $conditions_single_name_attr;
		global $conditions_plural_name;
		global $conditions_plural_name_attr;
		global $treatments_single_name;
		global $treatments_single_name_attr;
		global $treatments_plural_name;
		global $treatments_plural_name_attr;
		
		$resource_heading_related_pre = true; // "Related Resources"
		$resource_heading_related_post = false; // "Resources Related to __"
		$resource_heading_related_name = $page_title; // To what is it related?
		$resource_heading_related_name_attr = $page_title_attr;
		$resource_more_suppress = false; // Force div.more to not display
		$resource_more_key = '_resource_aoe';
		$resource_more_value = $post->post_name;
		if( $show_related_resource_section ) {
			include( UAMS_FAD_PATH . '/templates/blocks/clinical-resources.php' );
		}
	}

	// Display appointment information
	add_action( 'genesis_entry_content', 'uamswp_fad_ontology_appointment', 26 );
	// Check if Make an Appointment section should be displayed
	$show_appointment_section = true; // It should always be displayed.

genesis();