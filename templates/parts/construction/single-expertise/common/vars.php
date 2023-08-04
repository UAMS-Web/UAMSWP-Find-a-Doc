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

	// Query for whether descendant ontology items (of the same post type) content section should be displayed on ontology pages/subsections

		$expertise_descendant_query_vars = uamswp_fad_expertise_descendant_query(
			$page_id, // int
			$expertise_descendants, // int[]
			$content_placement, // string (optional)
			$site_nav_id // int (optional)
		);
			$expertise_descendant_query = $expertise_descendant_query_vars['expertise_descendant_query']; // WP_Post[]
			$expertise_descendant_section_show = $expertise_descendant_query_vars['expertise_descendant_section_show']; // bool
			$expertise_descendant_ids = $expertise_descendant_query_vars['expertise_descendant_ids']; // int[]
			$expertise_descendant_count = $expertise_descendant_query_vars['expertise_descendant_count']; // int
			$expertise_content_query = $expertise_descendant_query_vars['expertise_content_query']; // WP_Post[]
			$expertise_content_nav_show = $expertise_descendant_query_vars['expertise_content_nav_show']; // bool
			$expertise_content_ids = $expertise_descendant_query_vars['expertise_content_ids']; // int[]
			$expertise_content_count = $expertise_descendant_query_vars['expertise_content_count']; // int
			$expertise_content_nav = $expertise_descendant_query_vars['expertise_content_nav']; // string

	// Query for whether related ontology items (of the same post type) content section should be displayed on ontology pages/subsections

		$expertise_query_vars = uamswp_fad_expertise_query(
			$page_id, // int
			$expertises // int[]
		);
			$expertise_query = $expertise_query_vars['expertise_query']; // WP_Post[]
			$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool
			$expertise_ids = $expertise_query_vars['expertise_ids']; // int[]
			$expertise_count = $expertise_query_vars['expertise_count']; // int

	// Query for whether related clinical resources content section should be displayed on ontology pages/subsections

		$posts_per_page_clinical_resource_general_vars = isset($posts_per_page_clinical_resource_general_vars) ? $posts_per_page_clinical_resource_general_vars : uamswp_fad_posts_per_page_clinical_resource_general();
			$clinical_resource_posts_per_page_fpage = $posts_per_page_clinical_resource_general_vars['clinical_resource_posts_per_page_fpage']; // int
		$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_fpage;
		$clinical_resource_query_vars = uamswp_fad_clinical_resource_query(
			$page_id, // int
			$clinical_resources, // int[]
			$clinical_resource_posts_per_page // int
		);
			$clinical_resource_query = $clinical_resource_query_vars['clinical_resource_query']; // WP_Post[]
			$clinical_resource_section_show = $clinical_resource_query_vars['clinical_resource_section_show']; // bool
			$clinical_resource_ids = $clinical_resource_query_vars['clinical_resource_ids']; // int[]
			$clinical_resource_count = $clinical_resource_query_vars['clinical_resource_count']; // int

	// Query for whether related conditions content section should be displayed on ontology pages/subsections

		$condition_treatment_section_show = isset($condition_treatment_section_show) ? $condition_treatment_section_show : false;
		$ontology_type = isset($ontology_type) ? $ontology_type : true;
		$condition_query_vars = uamswp_fad_condition_query(
			$page_id, // int
			$conditions_cpt, // int[]
			$condition_treatment_section_show, // bool (optional)
			$ontology_type // bool (optional)
		);
			$condition_cpt_query = $condition_query_vars['condition_cpt_query']; // WP_Post[]
			$condition_section_show = $condition_query_vars['condition_section_show']; // bool
			$condition_treatment_section_show = $condition_query_vars['condition_treatment_section_show']; // bool
			$condition_ids = $condition_query_vars['condition_ids']; // int[]
			$condition_count = $condition_query_vars['condition_count']; // int
			$schema_medical_specialty = $condition_query_vars['schema_medical_specialty']; // array

	// Query for whether related treatments content section should be displayed on ontology pages/subsections

		$condition_treatment_section_show = isset($condition_treatment_section_show) ? $condition_treatment_section_show : false;
		$ontology_type = isset($ontology_type) ? $ontology_type : true;
		$treatment_query_vars = uamswp_fad_treatment_query(
			$page_id, // int
			$treatments_cpt, // int[]
			$condition_treatment_section_show, // bool (optional)
			$ontology_type, // bool (optional)
		);
			$treatment_cpt_query = $treatment_query_vars['treatment_cpt_query']; // WP_Post[]
			$treatment_section_show = $treatment_query_vars['treatment_section_show']; // bool
			$condition_treatment_section_show = $treatment_query_vars['condition_treatment_section_show']; // bool
			$treatment_ids = $treatment_query_vars['treatment_ids']; // int[]
			$treatment_count = $treatment_query_vars['treatment_count']; // int
			$schema_medical_specialty = $treatment_query_vars['schema_medical_specialty']; // array

	// Query for whether Make an Appointment section should be displayed

		$appointment_section_show = true; // It should always be displayed.
