<?php
/*
 * Template Name: Single Area of Expertise
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	$labels_provider_vars = isset($labels_provider_vars) ? $labels_provider_vars : uamswp_fad_labels_provider();
		$provider_single_name = $labels_provider_vars['provider_single_name']; // string
		$provider_single_name_attr = $labels_provider_vars['provider_single_name_attr']; // string
		$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string
		$provider_plural_name_attr = $labels_provider_vars['provider_plural_name_attr']; // string
		$placeholder_provider_single_name = $labels_provider_vars['placeholder_provider_single_name']; // string
		$placeholder_provider_plural_name = $labels_provider_vars['placeholder_provider_plural_name']; // string
		$placeholder_provider_short_name = $labels_provider_vars['placeholder_provider_short_name']; // string
		$placeholder_provider_short_name_possessive = $labels_provider_vars['placeholder_provider_short_name_possessive']; // string

	// Get system settings for location labels
	$labels_location_vars = isset($labels_location_vars) ? $labels_location_vars : uamswp_fad_labels_location();
		$location_single_name = $labels_location_vars['location_single_name']; // string
		$location_single_name_attr = $labels_location_vars['location_single_name_attr']; // string
		$location_plural_name = $labels_location_vars['location_plural_name']; // string
		$location_plural_name_attr = $labels_location_vars['location_plural_name_attr']; // string
		$placeholder_location_single_name = $labels_location_vars['placeholder_location_single_name']; // string
		$placeholder_location_plural_name = $labels_location_vars['placeholder_location_plural_name']; // string
		$placeholder_location_page_title = $labels_location_vars['placeholder_location_page_title']; // string
		$placeholder_location_page_title_phrase = $labels_location_vars['placeholder_location_page_title_phrase']; // string

	// // Get system settings for location descendant item labels
	// $labels_location_descendant_vars = isset($labels_location_descendant_vars) ? $labels_location_descendant_vars : uamswp_fad_labels_location_descendant();
	// 	$location_descendant_single_name = $labels_location_descendant_vars['location_descendant_single_name']; // string
	// 	$location_descendant_single_name_attr = $labels_location_descendant_vars['location_descendant_single_name_attr']; // string
	// 	$location_descendant_plural_name = $labels_location_descendant_vars['location_descendant_plural_name']; // string
	// 	$location_descendant_plural_name_attr = $labels_location_descendant_vars['location_descendant_plural_name_attr']; // string
	// 	$placeholder_location_descendant_single_name = $labels_location_descendant_vars['placeholder_location_descendant_single_name']; // string
	// 	$placeholder_location_descendant_plural_name = $labels_location_descendant_vars['placeholder_location_descendant_plural_name']; // string

	// Get system settings for area of expertise labels
	$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
		$expertise_single_name = $labels_expertise_vars['expertise_single_name']; // string
		$expertise_single_name_attr = $labels_expertise_vars['expertise_single_name_attr']; // string
		$expertise_plural_name = $labels_expertise_vars['expertise_plural_name']; // string
		$expertise_plural_name_attr = $labels_expertise_vars['expertise_plural_name_attr']; // string
		$placeholder_expertise_single_name = $labels_expertise_vars['placeholder_expertise_single_name']; // string
		$placeholder_expertise_plural_name = $labels_expertise_vars['placeholder_expertise_plural_name']; // string
		$placeholder_expertise_page_title = $labels_expertise_vars['placeholder_expertise_page_title']; // string

	// Get system settings for area of expertise descendant item labels
	$labels_expertise_descendant_vars = isset($labels_expertise_descendant_vars) ? $labels_expertise_descendant_vars : uamswp_fad_labels_expertise_descendant();
		$expertise_descendant_single_name = $labels_expertise_descendant_vars['expertise_descendant_single_name']; // string
		$expertise_descendant_single_name_attr = $labels_expertise_descendant_vars['expertise_descendant_single_name_attr']; // string
		$expertise_descendant_plural_name = $labels_expertise_descendant_vars['expertise_descendant_plural_name']; // string
		$expertise_descendant_plural_name_attr = $labels_expertise_descendant_vars['expertise_descendant_plural_name_attr']; // string
		$placeholder_expertise_descendant_single_name = $labels_expertise_descendant_vars['placeholder_expertise_descendant_single_name']; // string
		$placeholder_expertise_descendant_plural_name = $labels_expertise_descendant_vars['placeholder_expertise_descendant_plural_name']; // string

	// Get system settings for clinical resource labels
	$labels_clinical_resource_vars = isset($labels_clinical_resource_vars) ? $labels_clinical_resource_vars : uamswp_fad_labels_clinical_resource();
		$clinical_resource_single_name = $labels_clinical_resource_vars['clinical_resource_single_name']; // string
		$clinical_resource_single_name_attr = $labels_clinical_resource_vars['clinical_resource_single_name_attr']; // string
		$clinical_resource_plural_name = $labels_clinical_resource_vars['clinical_resource_plural_name']; // string
		$clinical_resource_plural_name_attr = $labels_clinical_resource_vars['clinical_resource_plural_name_attr']; // string
		$placeholder_clinical_resource_single_name = $labels_clinical_resource_vars['placeholder_clinical_resource_single_name']; // string
		$placeholder_clinical_resource_plural_name = $labels_clinical_resource_vars['placeholder_clinical_resource_plural_name']; // string

	// Get system settings for combined condition and treatment labels
	$labels_condition_treatment_vars = isset($labels_condition_treatment_vars) ? $labels_condition_treatment_vars : uamswp_fad_labels_condition_treatment();
		$condition_treatment_single_name = $labels_condition_treatment_vars['condition_treatment_single_name']; // string
		$condition_treatment_single_name_attr = $labels_condition_treatment_vars['condition_treatment_single_name_attr']; // string
		$condition_treatment_plural_name = $labels_condition_treatment_vars['condition_treatment_plural_name']; // string
		$condition_treatment_plural_name_attr = $labels_condition_treatment_vars['condition_treatment_plural_name_attr']; // string
		$placeholder_condition_treatment_single_name = $labels_condition_treatment_vars['placeholder_condition_treatment_single_name']; // string
		$placeholder_condition_treatment_plural_name = $labels_condition_treatment_vars['placeholder_condition_treatment_plural_name']; // string

	// Get system settings for condition labels
	$labels_condition_vars = isset($labels_condition_vars) ? $labels_condition_vars : uamswp_fad_labels_condition();
		$condition_single_name = $labels_condition_vars['condition_single_name']; // string
		$condition_single_name_attr = $labels_condition_vars['condition_single_name_attr']; // string
		$condition_plural_name = $labels_condition_vars['condition_plural_name']; // string
		$condition_plural_name_attr = $labels_condition_vars['condition_plural_name_attr']; // string
		$placeholder_condition_single_name = $labels_condition_vars['placeholder_condition_single_name']; // string
		$placeholder_condition_plural_name = $labels_condition_vars['placeholder_condition_plural_name']; // string

	// Get system settings for treatment labels
	$labels_treatment_vars = isset($labels_treatment_vars) ? $labels_treatment_vars : uamswp_fad_labels_treatment();
		$treatment_single_name = $labels_treatment_vars['treatment_single_name']; // string
		$treatment_single_name_attr = $labels_treatment_vars['treatment_single_name_attr']; // string
		$treatment_plural_name = $labels_treatment_vars['treatment_plural_name']; // string
		$treatment_plural_name_attr = $labels_treatment_vars['treatment_plural_name_attr']; // string
		$placeholder_treatment_single_name = $labels_treatment_vars['placeholder_treatment_single_name']; // string
		$placeholder_treatment_plural_name = $labels_treatment_vars['placeholder_treatment_plural_name']; // string

// // Get system settings for area of expertise archive page text
// $archive_text_expertise_vars = isset($archive_text_expertise_vars) ? $archive_text_expertise_vars : uamswp_fad_archive_text_expertise();
// 	$expertise_archive_headline = $archive_text_expertise_vars['expertise_archive_headline']; // string
// 	$expertise_archive_headline_attr = $archive_text_expertise_vars['expertise_archive_headline_attr']; // string
// 	$expertise_archive_intro_text = $archive_text_expertise_vars['expertise_archive_intro_text']; // string
// 	$placeholder_expertise_archive_headline = $archive_text_expertise_vars['placeholder_expertise_archive_headline']; // string
// 	$placeholder_expertise_archive_intro_text = $archive_text_expertise_vars['placeholder_expertise_archive_intro_text']; // string

// Get the page ID
$page_id = get_the_ID();

// Get the page title for the area of expertise
$page_title = get_the_title();
$page_title_attr = uamswp_attr_conversion($page_title);

// Array for page titles and section titles
$page_titles = array(
	'page_title'	=> $page_title
);

// Get the page URL
$page_url = user_trailingslashit(get_permalink());

// Define the placement for content
$content_placement = 'subsection'; // Expected values: 'subsection' or 'profile'

// Area of Expertise Content Type
$ontology_type = get_field('expertise_type'); // True is ontology type, false is content type
$ontology_type = isset($ontology_type) ? $ontology_type : 1; // Check if 'expertise_type' is not null, and if so, set value to true

// Get system settings for fake subpage text elements in an Area of Expertise subsection
$fpage_text_expertise_vars = isset($fpage_text_expertise_vars) ? $fpage_text_expertise_vars : uamswp_fad_fpage_text_expertise(
	$page_id, // int
	$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	$ontology_type // bool
);
	$expertise_page_title_options = $fpage_text_expertise_vars['expertise_page_title_options']; // string
	$expertise_page_title = $fpage_text_expertise_vars['expertise_page_title']; // string
	$expertise_page_intro = $fpage_text_expertise_vars['expertise_page_intro']; // string
	$expertise_page_image = $fpage_text_expertise_vars['expertise_page_image']; // string
	$expertise_page_image_mobile = $fpage_text_expertise_vars['expertise_page_image_mobile']; // string
	$expertise_short_desc = $fpage_text_expertise_vars['expertise_short_desc']; // string
	$provider_fpage_title_expertise = $fpage_text_expertise_vars['provider_fpage_title_expertise']; // string
	$provider_fpage_intro_expertise = $fpage_text_expertise_vars['provider_fpage_intro_expertise']; // string
	$provider_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['provider_fpage_ref_main_title_expertise']; // string
	$provider_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['provider_fpage_ref_main_intro_expertise']; // string
	$provider_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['provider_fpage_ref_main_link_expertise']; // string
	$provider_fpage_ref_top_title_expertise = $fpage_text_expertise_vars['provider_fpage_ref_top_title_expertise']; // string
	$provider_fpage_ref_top_intro_expertise = $fpage_text_expertise_vars['provider_fpage_ref_top_intro_expertise']; // string
	$provider_fpage_ref_top_link_expertise = $fpage_text_expertise_vars['provider_fpage_ref_top_link_expertise']; // string
	$provider_fpage_short_desc_expertise = $fpage_text_expertise_vars['provider_fpage_short_desc_expertise']; // string
	$location_fpage_title_expertise = $fpage_text_expertise_vars['location_fpage_title_expertise']; // string
	$location_fpage_intro_expertise = $fpage_text_expertise_vars['location_fpage_intro_expertise']; // string
	$location_fpage_short_desc_expertise = $fpage_text_expertise_vars['location_fpage_short_desc_expertise']; // string
	$location_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['location_fpage_ref_main_title_expertise']; // string
	$location_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['location_fpage_ref_main_intro_expertise']; // string
	$location_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['location_fpage_ref_main_link_expertise']; // string
	$location_fpage_ref_top_title_expertise = $fpage_text_expertise_vars['location_fpage_ref_top_title_expertise']; // string
	$location_fpage_ref_top_intro_expertise = $fpage_text_expertise_vars['location_fpage_ref_top_intro_expertise']; // string
	$location_fpage_ref_top_link_expertise = $fpage_text_expertise_vars['location_fpage_ref_top_link_expertise']; // string
	$expertise_descendant_fpage_title_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_title_expertise']; // string
	$expertise_descendant_fpage_intro_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_intro_expertise']; // string
	$expertise_descendant_fpage_short_desc_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_short_desc_expertise']; // string
	$expertise_descendant_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_ref_main_title_expertise']; // string
	$expertise_descendant_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_ref_main_intro_expertise']; // string
	$expertise_descendant_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_ref_main_link_expertise']; // string
	$expertise_fpage_title_expertise = $fpage_text_expertise_vars['expertise_fpage_title_expertise']; // string
	$expertise_fpage_intro_expertise = $fpage_text_expertise_vars['expertise_fpage_intro_expertise']; // string
	$expertise_fpage_short_desc_expertise = $fpage_text_expertise_vars['expertise_fpage_short_desc_expertise']; // string
	$expertise_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['expertise_fpage_ref_main_title_expertise']; // string
	$expertise_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['expertise_fpage_ref_main_intro_expertise']; // string
	$expertise_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['expertise_fpage_ref_main_link_expertise']; // string
	$clinical_resource_fpage_title_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_title_expertise']; // string
	$clinical_resource_fpage_intro_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_intro_expertise']; // string
	$clinical_resource_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_main_title_expertise']; // string
	$clinical_resource_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_main_intro_expertise']; // string
	$clinical_resource_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_main_link_expertise']; // string
	$clinical_resource_fpage_ref_top_title_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_top_title_expertise']; // string
	$clinical_resource_fpage_ref_top_intro_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_top_intro_expertise']; // string
	$clinical_resource_fpage_ref_top_link_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_top_link_expertise']; // string
	$clinical_resource_fpage_more_text_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_more_text_expertise']; // string
	$clinical_resource_fpage_more_link_text_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_more_link_text_expertise']; // string
	$clinical_resource_fpage_more_link_descr_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_more_link_descr_expertise']; // string
	$clinical_resource_fpage_short_desc_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_short_desc_expertise']; // string
	$condition_fpage_title_expertise = $fpage_text_expertise_vars['condition_fpage_title_expertise']; // string
	$condition_fpage_intro_expertise = $fpage_text_expertise_vars['condition_fpage_intro_expertise']; // string
	$treatment_fpage_title_expertise = $fpage_text_expertise_vars['treatment_fpage_title_expertise']; // string
	$treatment_fpage_intro_expertise = $fpage_text_expertise_vars['treatment_fpage_intro_expertise']; // string
	$condition_treatment_fpage_title_expertise = $fpage_text_expertise_vars['condition_treatment_fpage_title_expertise']; // string
	$condition_treatment_fpage_intro_expertise = $fpage_text_expertise_vars['condition_treatment_fpage_intro_expertise']; // string

// Get system settings for image elements of a fake subpage (or section) in an Area of Expertise subsection (or profile)
$fpage_image_expertise_vars = isset($fpage_image_expertise_vars) ? $fpage_image_expertise_vars : uamswp_fad_fpage_image_expertise();
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

// Get the featured image / post thumbnail
$page_image_id = $expertise_featured_image; // Image ID
$meta_image_resize_vars = uamswp_meta_image_resize( $page_image_id );
	$meta_og_image = $meta_image_resize_vars['meta_og_image']; // string
	$meta_og_image_width = $meta_image_resize_vars['meta_og_image_width']; // int
	$meta_og_image_height = $meta_image_resize_vars['meta_og_image_height']; // int
	$meta_twitter_image = $meta_image_resize_vars['meta_twitter_image']; // string
	$meta_twitter_image_width = $meta_image_resize_vars['meta_twitter_image_width']; // int
	$meta_twitter_image_height = $meta_image_resize_vars['meta_twitter_image_height']; // int

// // Get system settings for jump links (a.k.a. anchor links)
// $labels_jump_links_vars = isset($labels_jump_links_vars) ? $labels_jump_links_vars : uamswp_fad_labels_jump_links();
// 	$fad_jump_links_title = $labels_jump_links_vars['fad_jump_links_title']; // string

// Get site header and site nav values for ontology subsections
$ontology_site_values_vars = isset($ontology_site_values_vars) ? $ontology_site_values_vars : uamswp_fad_ontology_site_values(
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

// Queries for whether each of the associated ontology content sections should be displayed on ontology pages/subsections

	// Query for whether related providers content section should be displayed on ontology pages/subsections
	$provider_query_vars = isset($provider_query_vars) ? $provider_query_vars : uamswp_fad_provider_query(
		$providers // int[]
	);
		$provider_query = $provider_query_vars['provider_query']; // WP_Post[]
		$provider_section_show = $provider_query_vars['provider_section_show']; // bool
		$provider_ids = $provider_query_vars['provider_ids']; // int[]
		$provider_count = $provider_query_vars['provider_count']; // int

	// Query for whether related locations content section should be displayed on ontology pages/subsections
	$location_query_vars = isset($location_query_vars) ? $location_query_vars : uamswp_fad_location_query(
		$locations // int[]
	);
		$location_query = $location_query_vars['location_query']; // WP_Post[]
		$location_section_show = $location_query_vars['location_section_show']; // bool
		$location_ids = $location_query_vars['location_ids']; // int[]
		$location_count = $location_query_vars['location_count']; // int
		$location_valid = $location_query_vars['location_valid']; // bool

	// Query for whether descendant ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	$expertise_descendant_query_vars = isset($expertise_descendant_query_vars) ? $expertise_descendant_query_vars : uamswp_fad_expertise_descendant_query(
		$expertise_descendants, // int[]
		'subsection', // string (optional) // Expected values: 'subsection' or 'profile'
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
	$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query(
		$expertises // int[]
	);
		$expertise_query = $expertise_query_vars['expertise_query']; // WP_Post[]
		$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool
		$expertise_ids = $expertise_query_vars['expertise_ids']; // int[]
		$expertise_count = $expertise_query_vars['expertise_count']; // int

	// Query for whether related clinical resources content section should be displayed on ontology pages/subsections
	$clinical_resource_query_vars = isset($clinical_resource_query_vars) ? $clinical_resource_query_vars : uamswp_fad_clinical_resource_query(
		$clinical_resources // int[]
	);
		$clinical_resource_query = $clinical_resource_query_vars['clinical_resource_query']; // WP_Post[]
		$clinical_resource_section_show = $clinical_resource_query_vars['clinical_resource_section_show']; // bool
		$clinical_resource_ids = $clinical_resource_query_vars['clinical_resource_ids']; // int[]
		$clinical_resource_count = $clinical_resource_query_vars['clinical_resource_count']; // int

	// Query for whether related conditions content section should be displayed on ontology pages/subsections
	$condition_treatment_section_show = isset($condition_treatment_section_show) ? $condition_treatment_section_show : false;
	$ontology_type = isset($ontology_type) ? $ontology_type : true;
	$condition_query_vars = isset($condition_query_vars) ? $condition_query_vars : uamswp_fad_condition_query(
		$conditions_cpt,
		$condition_treatment_section_show,
		$ontology_type
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
	$treatment_query_vars = isset($treatment_query_vars) ? $treatment_query_vars : uamswp_fad_treatment_query(
		$treatments_cpt,
		$condition_treatment_section_show,
		$ontology_type
	);
		$treatment_cpt_query = $treatment_query_vars['treatment_cpt_query']; // WP_Post[]
		$treatment_section_show = $treatment_query_vars['treatment_section_show']; // bool
		$condition_treatment_section_show = $treatment_query_vars['condition_treatment_section_show']; // bool
		$treatment_ids = $treatment_query_vars['treatment_ids']; // int[]
		$treatment_count = $treatment_query_vars['treatment_count']; // int
		$schema_medical_specialty = $treatment_query_vars['schema_medical_specialty']; // array

	// Query for whether to conditionally suppress ontology sections based on Find-a-Doc Settings configuration
	$regions = isset($regions) ? $regions : array();
	$service_lines = isset($service_lines) ? $service_lines : array();
	if ( $regions || $service_lines ) {
		$ontology_hide_vars = isset($ontology_hide_vars) ? $ontology_hide_vars : uamswp_fad_ontology_hide(
			$regions, // string|array // Region(s) associated with the item
			$service_lines // string|array // Service line(s) associated with the item
		);
			$hide_medical_ontology = $ontology_hide_vars['hide_medical_ontology']; // bool
	} else {
		$hide_medical_ontology = false; // bool
	}

// Override theme's method of defining the meta page title

	// Construct the meta title

		$meta_title_enhanced_addition = $expertise_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
		$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(
			$page_title, // string
			$page_title_attr, // string (optional)
			'', // string (optional) // Word or phrase to use to form base meta title // Defaults to $page_title_attr
			'', // array (optional) // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number
			$meta_title_enhanced_addition // string (optional) // Word or phrase to inject into base meta title to form enhanced meta title level 1
		);
			$meta_title = $meta_title_vars['meta_title']; // string
	
	// Modify SEOPress's standard meta title settings

		add_filter( 'seopress_titles_title', function( $html ) use ( $meta_title ) {

			$html = $meta_title;

			return $html;

		}, 15, 2 );

// Set the schema description and the meta description

	// Get excerpt

		$excerpt = get_the_excerpt(); // 'expertise_selected_post_excerpt'
		$excerpt_user = true;

	// Set schema description

		$schema_description = $excerpt; // Used for Schema Data. Should ALWAYS have a value

	// Override theme's method of defining the meta description

		add_filter('seopress_titles_desc', function( $html ) use ( $excerpt ) {

			$html = $excerpt;

			return $html;

		} );

// Construct the meta keywords element

	$keywords = get_field('expertise_alternate_names');

	add_action( 'wp_head', function() use ($keywords) {
		uamswp_keyword_hook_header(
			$keywords // array
		);
	} );

// Override the theme's method of defining the social meta tags

	// Open Graph meta tags
	add_filter('seopress_social_og_thumb', 'uamswp_sp_social_og_thumb'); // Filter Open Graph thumbnail (og:image)

	// Twitter Card meta tags
	add_filter('seopress_social_twitter_card_thumb', 'uamswp_sp_social_twitter_card_thumb'); // Filter Twitter Card thumbnail (twitter:image:src)

// Modify site header

	// Remove the site header set by the theme
	remove_action( 'genesis_header', 'uamswp_site_image', 5 );

	// Add ontology subsection site header

		add_action( 'genesis_header', function() use (
			$page_id,
			$ontology_type,
			$page_title,
			$page_url
		) {
			uamswp_fad_ontology_header(
				$page_id, // int // ID of the post
				$ontology_type, // bool (optional) // Ontology type of the post (true is ontology type, false is content type)
				$page_title, // string (optional) // Title of the post
				$page_url // string (optional) // Permalink of the post
			);
		}, 5 );

// Modify primary navigation

	// Remove the primary navigation set by the theme
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

	// Add ontology subsection primary navigation

		add_action( 'genesis_after_header', function() use (
			$page_id,
			$ontology_type,
			$page_title,
			$page_url
		) {
			uamswp_fad_ontology_nav_menu(
				$page_id, // int // ID of the post
				$ontology_type, // bool (optional) // Ontology type of the post (true is ontology type, false is content type)
				$page_title, // string (optional) // Title of the post
				$page_url // string (optional) // Permalink of the post
			);
		}, 5 );

// Add page template class to body element's classes

	$template_type = 'default';
	add_filter( 'body_class', function( $classes ) use ( $template_type ) {

		// Add page template class to body class array
		$classes[] = 'page-template-' . $template_type;

		return $classes;

	} );

// Add bg-white class to article.entry element
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

// Modify Entry Title

	// Remove Genesis-standard post title and markup
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

	// Construct non-standard post title

		$entry_header_style = $expertise_page_title_options; // Entry header style
		$entry_title_text = $expertise_page_title; // Regular title
		$entry_title_text_supertitle = ''; // Optional supertitle, placed above the regular title
		$entry_title_text_subtitle = ''; // Optional subtitle, placed below the regular title
		$entry_title_text_body = $expertise_page_intro; // Optional lead paragraph, placed below the entry title
		$entry_title_image_desktop = $expertise_page_image; // Desktop breakpoint image ID
		$entry_title_image_mobile = $expertise_page_image_mobile; // Optional mobile breakpoint image ID

		add_action( 'genesis_before_content', function() use (
			$entry_title_text,
			$entry_header_style,
			$entry_title_text_supertitle,
			$entry_title_text_subtitle,
			$entry_title_text_body,
			$entry_title_image_desktop,
			$entry_title_image_mobile
		) {
			uamswp_fad_post_title(
				$entry_title_text, // string // Entry title text
				$entry_header_style, // string // Entry header style
				$entry_title_text_supertitle, // string (optional) // Entry supertitle text
				$entry_title_text_subtitle, // string (optional) // Entry subtitle text
				$entry_title_text_body, // string (optional) // Entry header lead paragraph text
				$entry_title_image_desktop, // int (optional) // Entry header background image for desktop breakpoints
				$entry_title_image_mobile // int (optional) // Entry header background image for mobile breakpoints
			);
		} );

// Remove the post info (byline) from the entry header and the entry footer
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php

// Remove the entry meta (tags) from the entry footer, including markup
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

// Construct page content

	// Display alternate names
	add_filter( 'genesis_entry_content', 'uamswp_expertise_keywords', 8 );
	function uamswp_expertise_keywords() {
		$keywords = get_field('expertise_alternate_names');
		$keyword_text = '';
		if( $keywords ): 
			$i = 1;
			foreach( $keywords as $keyword ) { 
				if ( 1 < $i ) {
					$keyword_text .= '; ';
				}
				$keyword_text .= $keyword['alternate_text'];
				$i++;
			}
			echo '<p class="text-callout text-callout-info">Also called: '. $keyword_text .'</p>';
		endif;
	}

	// Display featured video
	add_action( 'genesis_entry_content', 'uamswp_expertise_youtube', 12 );
	function uamswp_expertise_youtube() {
		$video = get_field('expertise_youtube_link');
		if( $video ) { ?>
			<?php if(function_exists('lyte_preparse')) {
				echo '<div class="alignwide">';
				echo lyte_parse( str_replace( ['https:', 'http:'], 'httpv:', $video ) );
				echo '</div>';
			} else {
				echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
				echo wp_oembed_get( $video );
				echo '</div>';
			} ?>
		<?php }
	}

	// Display call-to-action bars
	add_action( 'genesis_after_entry', 'uamswp_expertise_cta', 6 );
	function uamswp_expertise_cta() {
		$cta_repeater = get_field('expertise_cta');
		if( $cta_repeater ): 
			$i = 1;
			foreach( $cta_repeater as $cta ) { 
				$cta_heading = $cta['cta_bar_heading'];
				$cta_body = $cta['cta_bar_body'];
				$cta_action_type = $cta['cta_bar_action_type'];

				$cta_button_text = '';
				$cta_button_url = '';
				$cta_button_target = '';
				$cta_button_desc = '';
				if ( $cta_action_type == 'url' ) {
					$cta_button_text = $cta['cta_bar_button_text'];
					$cta_button_url = $cta['cta_bar_button_url'];
					if ( $cta_button_url ) {
						$cta_button_target = $cta_button_url['target'];
					}
					$cta_button_desc = $cta['cta_bar_button_description'];
				}

				$cta_phone_prepend = '';
				$cta_phone = '';
				$cta_phone_link = '';
				if ( $cta_action_type == 'phone' ) {
					$cta_phone_prepend = $cta['cta_bar_phone_prepend'] ? $cta['cta_bar_phone_prepend'] : 'Call';
					$cta_phone = $cta['cta_bar_phone'];
					$cta_phone_link = '<a href="tel:' . format_phone_dash( $cta_phone ) . '">' . format_phone_us( $cta_phone ) . '</a>';
				}

				$cta_layout = 'cta-bar-centered';
				$cta_size = 'normal';
				$cta_use_image = false;
				$cta_image = '';
				$cta_background_color = 'bg-auto';
				$cta_btn_color = 'primary';

				$cta_className = '';
				$cta_className .= ' ' . $cta_layout;
				$cta_className .= ' ' . $cta_background_color;
				$cta_className .= $cta_use_image ? ' bg-image' : '';
				if ( $cta_size == 'small' ) {
					$cta_className .= ' cta-bar-sm';
				} elseif ( $cta_size == 'large' ) {
					$cta_className .= ' extra-padding cta-bar-lg';
				}
				if ( $cta_action_type == 'none' ) {
					$cta_className .= ' no-link';
				}

				echo '<section class="uams-module cta-bar' . $cta_className . '" id="cta-bar-' . $i . '" aria-label="' . $cta_heading . '">
					<div class="container-fluid">
						<div class="row">
							<div class="col-12">
								<div class="inner-container">
									<div class="cta-heading">
										<h2>' . $cta_heading . '</h2>
									</div>
									<div class="cta-body">
										<div class="text-container">
											' . $cta_body . '
										</div>';
										echo $cta_action_type == 'url' ?
										'<div class="btn-container">
											<a href="' . $cta_button_url['url'] . '" aria-label="' . $cta_button_desc . '" class=" btn btn-' . $cta_btn_color . ( $cta_size == 'large' ? ' btn-lg' : '' ) . '"' . ( $cta_button_target ? ' target="'. $cta_button_target . '"' : '' ) . ' data-moduletitle="' . $cta_heading . '">' . $cta_button_text . '</a>
										</div>'
										: '';
										echo $cta_action_type == 'phone' ?
										'<div class="btn-container">
											<a href="tel:' . $cta_phone . '" data-moduletitle="' . $cta_heading . '">' . $cta_phone_prepend . ' <span class="no-break">' . $cta_phone . '</span></a>
										</div>'
										: '';
									echo '</div>
								</div>
							</div>
						</div>
					</div>
				</section>';
				$i++;
			}
		endif;
	}

	// Check if podcast section should be displayed

		$podcast_name = get_field('expertise_podcast_name');
		$podcast_query_vars = isset($podcast_query_vars) ? $podcast_query_vars : uamswp_fad_podcast_query(
			$podcast_name // string
		);
			$podcast_section_show = $podcast_query_vars['podcast_section_show']; // bool

	// Construct UAMS Health Talk podcast section

		$podcast_filter = 'tag'; // string // Expected values: 'tag' or 'doctor'
		$podcast_subject = $page_title; // string

		add_action( 'genesis_after_entry', function() use (
			$podcast_name,
			$podcast_section_show,
			$podcast_filter,
			$podcast_subject
		) {
			uamswp_fad_podcast(
				$podcast_name, // string
				$podcast_section_show, // bool
				$podcast_filter, // string // Expected values: 'tag' or 'doctor'
				$podcast_subject // string
			);
		}, 10 );

	// Construct Combined Conditions and Treatments Section

		$ontology_type = isset($ontology_type) ? $ontology_type : true; // bool
		$condition_treatment_section_title = $condition_treatment_fpage_title_expertise; // Text to use for the section title // string (default: Find-a-Doc Settings value for combined condition/treatment section title in a general placement)
		$condition_treatment_section_intro = $condition_treatment_fpage_intro_expertise; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for combined condition/treatment section intro text in a general placement)
		$condition_section_title = $condition_fpage_title_expertise; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
		$condition_section_intro = $condition_fpage_intro_expertise; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
		$treatment_section_title = $treatment_fpage_title_expertise; // Text to use for the section title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
		$treatment_section_intro = $treatment_fpage_intro_expertise; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)

		add_action( 'genesis_after_entry', function() use (
			$conditions_cpt,
			$treatments_cpt,
			$page_titles,
			$hide_medical_ontology,
			&$schema_medical_specialty,
			$condition_treatment_section_show,
			$condition_section_show,
			$treatment_section_show,
			$ontology_type,
			$condition_treatment_section_title,
			$condition_treatment_section_intro,
			$condition_section_title,
			$condition_section_intro,
			$treatment_section_title,
			$treatment_section_intro
		) {
			uamswp_fad_section_condition_treatment(
				$conditions_cpt, // int[]
				$treatments_cpt, // int[]
				$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				$hide_medical_ontology, // bool (optional) // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
				$schema_medical_specialty, // array (optional) // MedicalSpecialty Schema data
				$condition_treatment_section_show, // bool
				$condition_section_show, // bool
				$treatment_section_show, // bool
				$ontology_type, // bool
				$condition_treatment_section_title, // string // Text to use for the section title
				$condition_treatment_section_intro, // string // Text to use for the section intro text
				$condition_section_title, // string // Text to use for the conditions subsection title
				$condition_section_intro, // string // Text to use for the conditions subsection intro text
				$treatment_section_title, // string // Text to use for the treatments subsection title
				$treatment_section_intro // string // Text to use for the treatments subsection intro text
			);
		}, 16 );

	// Check if Make an Appointment section should be displayed

		$appointment_section_show = true; // It should always be displayed.

	// Display appointment information

		add_action( 'genesis_after_entry', function() use ( $appointment_section_show ) {
			uamswp_fad_ontology_appointment(
				$appointment_section_show
			);
		}, 26 );

genesis();