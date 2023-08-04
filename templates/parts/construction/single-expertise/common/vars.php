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

	// Get system settings for elements of a fake subpage (or section) in an Area of Expertise subsection (or profile)

		// Get system settings for area of expertise profile text elements
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/expertise.php' );

		// Image elements

			$fpage_image_expertise_vars = uamswp_fad_fpage_image_expertise(
				$page_id // int
			);
				$expertise_featured_image = $fpage_image_expertise_vars['expertise_featured_image']; // int
				$expertise_featured_image_url = $fpage_image_expertise_vars['expertise_featured_image_url']; // string
				$provider_fpage_featured_image_expertise = $fpage_image_expertise_vars['provider_fpage_featured_image_expertise']; // int
				$provider_fpage_featured_image_expertise_url = $fpage_image_expertise_vars['provider_fpage_featured_image_expertise_url']; // string
				$location_fpage_featured_image_expertise = $fpage_image_expertise_vars['location_fpage_featured_image_expertise']; // int
				$location_fpage_featured_image_expertise_url = $fpage_image_expertise_vars['location_fpage_featured_image_expertise_url']; // string
				$expertise_fpage_featured_image_expertise = $fpage_image_expertise_vars['expertise_fpage_featured_image_expertise']; // int
				$expertise_fpage_featured_image_expertise_url = $fpage_image_expertise_vars['expertise_fpage_featured_image_expertise_url']; // string
				$expertise_descendant_fpage_featured_image_expertise = $fpage_image_expertise_vars['expertise_descendant_fpage_featured_image_expertise']; // int
				$expertise_descendant_fpage_featured_image_expertise_url = $fpage_image_expertise_vars['expertise_descendant_fpage_featured_image_expertise_url']; // string
				$clinical_resource_fpage_featured_image_expertise = $fpage_image_expertise_vars['clinical_resource_fpage_featured_image_expertise']; // int
				$clinical_resource_fpage_featured_image_expertise_url = $fpage_image_expertise_vars['clinical_resource_fpage_featured_image_expertise_url']; // string

// Get site header and site nav values for ontology subsections

	$ontology_site_values_vars = uamswp_fad_ontology_site_values(
		$page_id, // int // ID of the post
		$ontology_type, // bool (optional) // Ontology type of the post (true is ontology type, false is content type)
		$page_title, // string (optional) // Title of the post
		$page_url // string (optional) // Permalink of the post
	);
		$site_nav_id = $ontology_site_values_vars['site_nav_id']; // int
		$navbar_subbrand_title = $ontology_site_values_vars['navbar_subbrand']['title']['name']; // string
		$navbar_subbrand_title_attr = $ontology_site_values_vars['navbar_subbrand']['title']['attr']; // string
		$navbar_subbrand_title_url = $ontology_site_values_vars['navbar_subbrand']['title']['url']; // string
		$navbar_subbrand_parent = $ontology_site_values_vars['navbar_subbrand']['parent']['name']; // string
		$navbar_subbrand_parent_attr = $ontology_site_values_vars['navbar_subbrand']['parent']['attr']; // string
		$navbar_subbrand_parent_url = $ontology_site_values_vars['navbar_subbrand']['parent']['url']; // string
		$providers = $ontology_site_values_vars['providers']; // int[]
		$locations = $ontology_site_values_vars['locations']; // int[]
		$expertises = $ontology_site_values_vars['expertises']; // int[]
		$expertise_descendants = $ontology_site_values_vars['expertise_descendants'];
		$clinical_resources = $ontology_site_values_vars['clinical_resources']; // int[]
		$conditions_cpt = $ontology_site_values_vars['conditions_cpt']; // int[]
		$treatments_cpt = $ontology_site_values_vars['treatments_cpt']; // int[]
		$ancestors_ontology_farthest = $ontology_site_values_vars['ancestors_ontology_farthest'];
		$page_top_level_query = $ontology_site_values_vars['page_top_level_query']; // bool

// Image values

	// Get the featured image ID

		$featured_image = $expertise_featured_image; // Image ID
		$featured_image = $featured_image ? $featured_image : '';

// Page template class
$template_type = 'default';

// Meta title

	$meta_title_enhanced_addition = $expertise_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
	$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(
		$page_title_attr, // string
		'', // string (optional) // Word or phrase to use to form base meta title // Defaults to $page_title_attr
		'', // array (optional) // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number
		$meta_title_enhanced_addition // string (optional) // Word or phrase to inject into base meta title to form enhanced meta title level 1
	);
		$meta_title = $meta_title_vars['meta_title']; // string

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

	// Query for whether related providers content section should be displayed on ontology pages/subsections

		$provider_query_vars = uamswp_fad_provider_query(
			$page_id, // int
			$providers // int[]
		);
			$provider_query = $provider_query_vars['provider_query']; // WP_Post[]
			$provider_section_show = $provider_query_vars['provider_section_show']; // bool
			$provider_ids = $provider_query_vars['provider_ids']; // int[]
			$provider_count = $provider_query_vars['provider_count']; // int

	// Query for whether related locations content section should be displayed on ontology pages/subsections

		$location_query_vars = uamswp_fad_location_query(
			$page_id, // int
			$locations // int[]
		);
			$location_query = $location_query_vars['location_query']; // WP_Post[]
			$location_section_show = $location_query_vars['location_section_show']; // bool
			$location_ids = $location_query_vars['location_ids']; // int[]
			$location_count = $location_query_vars['location_count']; // int
			$location_valid = $location_query_vars['location_valid']; // bool

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
