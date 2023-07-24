<?php
/*
 * Template Name: Fake Area of Expertise Clinical Resources Subpage
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

// Get the page ID for the 'parent' area of expertise
$page_id = get_the_ID();

// Get the page title for the 'parent' area of expertise
$page_title = get_the_title(); // Title of the area of expertise
$page_title_attr = uamswp_attr_conversion($page_title);

// Get the page slug for the 'parent' area of expertise
$page_slug = $post->post_name;

// Get the page URL for the 'parent' area of expertise
$page_url = get_permalink();

// Area of Expertise Content Type
$ontology_type = get_field('expertise_type'); // True is ontology type, false is content type
$ontology_type = isset($ontology_type) ? $ontology_type : 1; // Check if 'expertise_type' is not null, and if so, set value to true

// Get system settings for fake subpage text elements in an Area of Expertise subsection
$fpage_text_expertise_vars = isset($fpage_text_expertise_vars) ? $fpage_text_expertise_vars : uamswp_fad_fpage_text_expertise( $page_id, $page_title, $ontology_type );
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
$page_image_id = $clinical_resource_fpage_featured_image_expertise; // Image ID
$meta_image_resize_vars = uamswp_meta_image_resize( $page_image_id );
	$meta_og_image = $meta_image_resize_vars['meta_og_image']; // string
	$meta_og_image_width = $meta_image_resize_vars['meta_og_image_width']; // int
	$meta_og_image_height = $meta_image_resize_vars['meta_og_image_height']; // int
	$meta_twitter_image = $meta_image_resize_vars['meta_twitter_image']; // string
	$meta_twitter_image_width = $meta_image_resize_vars['meta_twitter_image_width']; // int
	$meta_twitter_image_height = $meta_image_resize_vars['meta_twitter_image_height']; // int

// Set general variables for fake subpage
$fpage_name = $clinical_resource_plural_name; // Name of ontology item type represented by this fake subpage
$fpage_name_attr = uamswp_attr_conversion($fpage_name);
$fpage_title = $clinical_resource_fpage_title_expertise; // Fake subpage page title
$fpage_title_attr = uamswp_attr_conversion($fpage_title);
$current_fpage = get_query_var('fpage'); // Fake subpage slug
$fpage_url = !empty($current_fpage) ? $page_url . user_trailingslashit($current_fpage) : $page_url; // Fake subpage URL
$fpage_intro = $clinical_resource_fpage_intro_expertise; // Fake subpage intro text
$fpage_intro_attr = uamswp_attr_conversion($fpage_intro); // Attribute-friendly version of fake subpage intro text

// Get site header and site nav values for ontology subsections
$ontology_site_values_vars = isset($ontology_site_values_vars) ? $ontology_site_values_vars : uamswp_fad_ontology_site_values();
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
	$provider_query_vars = isset($provider_query_vars) ? $provider_query_vars : uamswp_fad_provider_query( $providers );
		$provider_query = $provider_query_vars['provider_query']; // WP_Post[]
		$provider_section_show = $provider_query_vars['provider_section_show']; // bool
		$provider_ids = $provider_query_vars['provider_ids']; // int[]
		$provider_count = $provider_query_vars['provider_count']; // int

	// Query for whether related locations content section should be displayed on ontology pages/subsections
	$location_query_vars = isset($location_query_vars) ? $location_query_vars : uamswp_fad_location_query( $locations );
		$location_query = $location_query_vars['location_query']; // WP_Post[]
		$location_section_show = $location_query_vars['location_section_show']; // bool
		$location_ids = $location_query_vars['location_ids']; // int[]
		$location_count = $location_query_vars['location_count']; // int
		$location_valid = $location_query_vars['location_valid']; // bool

	// Query for whether descendant ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	$expertise_descendant_query_vars = isset($expertise_descendant_query_vars) ? $expertise_descendant_query_vars : uamswp_fad_expertise_descendant_query( $page_id );
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
	$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query( $expertises );
		$expertise_query = $expertise_query_vars['expertise_query']; // WP_Post[]
		$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool
		$expertise_ids = $expertise_query_vars['expertise_ids']; // int[]
		$expertise_count = $expertise_query_vars['expertise_count']; // int

	// Query for whether related clinical resources content section should be displayed on ontology pages/subsections
	$posts_per_page_clinical_resource_general_vars = isset($posts_per_page_clinical_resource_general_vars) ? $posts_per_page_clinical_resource_general_vars : uamswp_fad_posts_per_page_clinical_resource_general();
		$clinical_resource_posts_per_page_fpage = $posts_per_page_clinical_resource_general_vars['clinical_resource_posts_per_page_fpage']; // int
	$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_fpage;
	$clinical_resource_query_vars = isset($clinical_resource_query_vars) ? $clinical_resource_query_vars : uamswp_fad_clinical_resource_query(
		$clinical_resources,
		$clinical_resource_posts_per_page
	);
		$clinical_resource_query = $clinical_resource_query_vars['clinical_resource_query']; // WP_Post[]
		$clinical_resource_section_show = $clinical_resource_query_vars['clinical_resource_section_show']; // bool
		$clinical_resource_ids = $clinical_resource_query_vars['clinical_resource_ids']; // int[]
		$clinical_resource_count = $clinical_resource_query_vars['clinical_resource_count']; // int

	// Query for whether related conditions content section should be displayed on ontology pages/subsections
	$condition_treatment_section_show = isset($condition_treatment_section_show) ? $condition_treatment_section_show : false;
	$ontology_type = isset($ontology_type) ? $ontology_type : true;
	$condition_query_vars = isset($condition_query_vars) ? $condition_query_vars : uamswp_fad_condition_query( $conditions_cpt, $condition_treatment_section_show, $ontology_type );
		$condition_cpt_query = $condition_query_vars['condition_cpt_query']; // WP_Post[]
		$condition_section_show = $condition_query_vars['condition_section_show']; // bool
		$condition_treatment_section_show = $condition_query_vars['condition_treatment_section_show']; // bool
		$condition_ids = $condition_query_vars['condition_ids']; // int[]
		$condition_count = $condition_query_vars['condition_count']; // int
		$condition_treatment_schema = $condition_query_vars['condition_treatment_schema']; // string

	// Query for whether related treatments content section should be displayed on ontology pages/subsections
	$condition_treatment_section_show = isset($condition_treatment_section_show) ? $condition_treatment_section_show : false;
	$ontology_type = isset($ontology_type) ? $ontology_type : true;
	$treatment_query_vars = isset($treatment_query_vars) ? $treatment_query_vars : uamswp_fad_treatment_query( $treatments_cpt, $condition_treatment_section_show, $ontology_type );
		$treatment_cpt_query = $treatment_query_vars['treatment_cpt_query']; // WP_Post[]
		$treatment_section_show = $treatment_query_vars['treatment_section_show']; // bool
		$condition_treatment_section_show = $treatment_query_vars['condition_treatment_section_show']; // bool
		$treatment_ids = $treatment_query_vars['treatment_ids']; // int[]
		$treatment_count = $treatment_query_vars['treatment_count']; // int
		$condition_treatment_schema = $treatment_query_vars['condition_treatment_schema']; // string

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
$meta_title_base_addition = $fpage_name_attr; // Word or phrase to use to form base meta title
$meta_title_enhanced_addition = $page_title_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
$meta_title_base_order = array( $meta_title_base_addition, $meta_title_enhanced_addition ); // Override default base meta title structure to force inclusion of $meta_title_enhanced_addition
$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(); // Defines universal variables related to the setting the meta title
	$meta_title = $meta_title_vars['meta_title']; // string
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

// Override theme's method of defining the meta description
$excerpt = $clinical_resource_fpage_short_desc_expertise;
add_filter('seopress_titles_desc', 'uamswp_fad_meta_desc');

// Construct the meta keywords element
$keywords = get_field('expertise_alternate_names');
add_action('wp_head','uamswp_keyword_hook_header');

// Override the theme's method of defining the social meta tags

	// Open Graph meta tags
	add_filter('seopress_social_og_thumb', 'uamswp_sp_social_og_thumb'); // Filter Open Graph thumbnail (og:image)

	// Twitter Card meta tags
	add_filter('seopress_social_twitter_card_thumb', 'uamswp_sp_social_twitter_card_thumb'); // Filter Twitter Card thumbnail (twitter:image:src)

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
	$entry_header_style = 'graphic'; // Entry header style
	$entry_title_text = $fpage_title; // Regular title
	$entry_title_text_supertitle = ''; // Optional supertitle
	$entry_title_text_subtitle = ''; // Optional subtitle
	$entry_title_text_body = $fpage_intro; // Optional lead paragraph
	$entry_title_image_desktop = ''; // Desktop breakpoint image ID
	$entry_title_image_mobile = ''; // Optional mobile breakpoint image ID
	function uamswp_fad_post_title__template() {
		global $entry_title_text; 
		global $entry_header_style;
		global $entry_title_text_supertitle;
		global $entry_title_text_subtitle;
		global $entry_title_text_body;
		global $entry_title_image_desktop;
		global $entry_title_image_mobile;

		uamswp_fad_post_title(
			$entry_title_text, // string // Entry title text
			$entry_header_style, // string // Entry header style
			$entry_title_text_supertitle, // string (optional) // Entry supertitle text
			$entry_title_text_subtitle, // string (optional) // Entry subtitle text
			$entry_title_text_body, // string (optional) // Entry header lead paragraph text
			$entry_title_image_desktop, // int (optional) // Entry header background image for desktop breakpoints
			$entry_title_image_mobile // int (optional) // Entry header background image for mobile breakpoints
		);
	}
	add_action( 'genesis_before_content', 'uamswp_fad_post_title__template' );

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
	$clinical_resource_section_more_link_key = '_resource_aoe';
	$clinical_resource_section_more_link_value = $page_slug;
	$clinical_resource_section_show_header = false; // Query whether to display the section header // bool (default: true)
	$clinical_resource_section_title = 'List of ' . $clinical_resource_plural_name; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of clinical_resource section title in a general placement)
	$clinical_resource_section_intro = ''; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of clinical_resource section intro text in a general placement)
	$clinical_resource_section_more_show = false; // Query whether to show the section that links to more items // bool (default: true)
	function uamswp_fad_section_clinical_resource__template() {

		global $clinical_resources;
		global $clinical_resource_section_more_link_key;
		global $clinical_resource_section_more_link_value;
		global $clinical_resource_section_show;
		global $ontology_type;
		global $clinical_resource_section_title;
		global $clinical_resource_section_intro;
		global $clinical_resource_section_more_show;

		uamswp_fad_section_clinical_resource(
			$clinical_resources, // int[] // Value of the related clinical resources input
			$clinical_resource_section_more_link_key, // string (optional)
			$clinical_resource_section_more_link_value, // string (optional)
			$clinical_resource_section_show, // bool (optional) // Query for whether to show the clinical resource section
			$ontology_type, // bool (optional) // Query for whether item is ontology type vs. content type
			$clinical_resource_section_title, // string (optional) // Text to use for the section title
			$clinical_resource_section_intro, // string (optional) // Text to use for the section intro text
			$clinical_resource_section_more_show, // bool (optional) // Query for whether to show the section that links to more items
		);

	}
	add_action( 'genesis_entry_content', 'uamswp_fad_section_clinical_resource__template', 14 );

	// Display references to other archive pages
	add_action( 'genesis_entry_content', 'uamswp_fad_fpage_text_image_overlay', 25);

	// Check if Make an Appointment section should be displayed

	$appointment_section_show = true; // It should always be displayed.

	// Display appointment information

		function uamswp_fad_ontology_appointment__template() {

			global $appointment_section_show;

			uamswp_fad_ontology_appointment(
				$appointment_section_show
			);

		}
		add_action( 'genesis_after_entry', 'uamswp_fad_ontology_appointment__template', 26 );

genesis();