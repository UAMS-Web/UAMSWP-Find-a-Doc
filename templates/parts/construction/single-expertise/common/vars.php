<?php
/*
 * Template Name: Area of Expertise Template Variables
 * 
 * Description: A template part that defines the variables used on a single 
 * template for an area of expertise overview page and for a fake area of 
 * expertise subpage
 */

// Get the page URL and slug

	// 'Parent' area of expertise (rather than fake subpage)

		$page_url = user_trailingslashit(get_permalink());
		$page_slug = $post->post_name;

// Get system settings for ontology item labels

	// Get system settings for provider labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

	// Get system settings for area of expertise labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

	// Get system settings for descendant area of expertise item labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise-descendant.php' );

	// Get system settings for clinical resource labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

	// Get system settings for combined condition and treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition-treatment.php' );

	// Get system settings for condition labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition.php' );

	// Get system settings for treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/treatment.php' );

// // Get system settings for this post type's archive page text
// include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/expertise.php' );

// Ontology / Content Type

	$ontology_type = get_field('expertise_type'); // Ontology type of the post (true is ontology type, false is content type)
	$ontology_type = isset($ontology_type) ? $ontology_type : 1; // Check if 'expertise_type' is not null, and if so, set value to true

// Get the page ID

	// 'Parent' area of expertise (rather than fake subpage)
	$page_id = get_the_ID();

// Get the page title and other name values

	// 'Parent' area of expertise (rather than fake subpage)

		$page_title = get_the_title();
		$page_title_attr = uamswp_attr_conversion($page_title);

	// Array for page titles and section titles

		$page_titles = array(
			'page_title'		=> $page_title,
			'page_title_attr'	=> $page_title_attr
		);

// Get system settings for area of expertise profile text elements
include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/expertise.php' );

// Get system settings for area of expertise profile image elements
include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/expertise.php' );

// Get the ontology subsection values
include( UAMS_FAD_PATH . '/templates/parts/vars/sys/ontology-subsection.php' );

// Image values

	// Get the featured image ID

		$featured_image = $expertise_featured_image; // Image ID
		$featured_image = $featured_image ? $featured_image : '';

// Page template class
$template_type = 'default';

// Meta title

	$meta_title_enhanced_addition = $expertise_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
	include( UAMS_FAD_PATH . '/templates/parts/html/meta/title.php' );

// Meta Description and Schema Description

	// Get excerpt

		$excerpt = get_the_excerpt(); // 'expertise_selected_post_excerpt'
		$excerpt_user = true;

		if ( empty( $excerpt ) ) {

			$excerpt_user = false;

		}

		$excerpt_attr = uamswp_attr_conversion($excerpt);

	// Set schema description

		$schema_description = $excerpt_attr; // Used for Schema Data. Should ALWAYS have a value

// Meta Keywords

	$keywords = get_field('expertise_alternate_names');

// Canonical URL

	// Do nothing

// Meta Social Media Tags

	// Filter hooks
	include( UAMS_FAD_PATH . '/templates/parts/html/meta/social.php' );

// Define the placement for content

	$content_placement = 'subsection'; // Expected values: 'subsection' or 'profile'

// Page title configuration

	$entry_header_style = $expertise_page_title_options ?: 'graphic'; // Entry header style
	$entry_title_text = $expertise_page_title; // Regular title
	$entry_title_text_supertitle = ''; // Optional supertitle, placed above the regular title
	$entry_title_text_subtitle = ''; // Optional subtitle, placed below the regular title
	$entry_title_text_body = $expertise_page_intro; // Optional lead paragraph, placed below the entry title
	$entry_title_image_desktop = $expertise_page_image; // Desktop breakpoint image ID
	$entry_title_image_mobile = $expertise_page_image_mobile; // Optional mobile breakpoint image ID

// Query for whether to conditionally suppress ontology sections based on based on region and service line

	$regions = isset($regions) ? $regions : array();
	$service_lines = isset($service_lines) ? $service_lines : array();

	include( UAMS_FAD_PATH . '/templates/parts/vars/page/ontology-hide.php' );

// Queries for whether each of the sections should be displayed

	// Related Providers Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/provider.php' );

	// Related Locations Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/location.php' );

	// Descendant Areas of Expertise Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/expertise-descendant.php' );

	// Related Areas of Expertise Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/expertise.php' );

	// Related Clinical Resources Section Query

		$posts_per_page_clinical_resource_general_vars = isset($posts_per_page_clinical_resource_general_vars) ? $posts_per_page_clinical_resource_general_vars : uamswp_fad_posts_per_page_clinical_resource_general();
			$clinical_resource_posts_per_page_fpage = $posts_per_page_clinical_resource_general_vars['clinical_resource_posts_per_page_fpage']; // int
		$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_fpage;
		include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/clinical-resource.php' );

	// Related Conditions Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/condition.php' );

	// Related Treatments Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/treatment.php.php' );

	// Query for whether Make an Appointment section should be displayed
	$appointment_section_show = true; // It should always be displayed.
