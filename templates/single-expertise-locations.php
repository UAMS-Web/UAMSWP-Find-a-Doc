<?php
/*
 * Template Name: Fake Area of Expertise Locations Subpage
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	include( UAMS_FAD_PATH . '/templates/parts/sys-labels-provider.php' );

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/sys-labels-location.php' );

	// Get system settings for area of expertise labels
	include( UAMS_FAD_PATH . '/templates/parts/sys-labels-expertise.php' );

	// Get system settings for descendant area of expertise item labels
	include( UAMS_FAD_PATH . '/templates/parts/sys-labels-expertise-descendant.php' );

	// Get system settings for clinical resource labels
	include( UAMS_FAD_PATH . '/templates/parts/sys-labels-clinical-resource.php' );

	// Get system settings for combined condition and treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/sys-labels-condition-treatment.php' );

	// Get system settings for condition labels
	include( UAMS_FAD_PATH . '/templates/parts/sys-labels-condition.php' );

	// Get system settings for treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/sys-labels-treatment.php' );

// // Get system settings for this post type's archive page text
// include( UAMS_FAD_PATH . '/templates/parts/sys-archive-location.php' );

// Ontology / Content Type

	$ontology_type = get_field('expertise_type'); // Ontology type of the post (true is ontology type, false is content type)
	$ontology_type = isset($ontology_type) ? $ontology_type : 1; // Check if 'expertise_type' is not null, and if so, set value to true

// Get the page ID

	// 'Parent' area of expertise
	$page_id = get_the_ID();

// Get the page title and other name values

	// 'Parent' area of expertise

		$page_title = get_the_title();
		$page_title_attr = $page_title ? uamswp_attr_conversion($page_title) : '';

	// Array for page titles and section titles

		$page_titles = array(
			'page_title'		=> $page_title,
			'page_title_attr'	=> $page_title_attr
		);

	// Get system settings for elements of a fake subpage (or section) in an Area of Expertise subsection (or profile)

		// Text elements

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

		// Image elements

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

	// Name of ontology item type represented by this fake subpage

		$fpage_name = $location_plural_name; // Name of ontology item type represented by this fake subpage
		$fpage_name_attr = $fpage_name ? uamswp_attr_conversion($fpage_name) : '';

	// Fake subpage page title

		$fpage_title = $location_fpage_title_expertise; // Fake subpage page title
		$fpage_title_attr = $fpage_title ? uamswp_attr_conversion($fpage_title) : '';

	 // Fake subpage intro text

		$fpage_intro = $location_fpage_intro_expertise; // Fake subpage intro text
		$fpage_intro_attr = $fpage_intro ? uamswp_attr_conversion($fpage_intro) : ''; // Attribute-friendly version of fake subpage intro text

// Get the page URL and slug

	// 'Parent' area of expertise

		$page_url = user_trailingslashit(get_permalink());
		$page_slug = $post->post_name;

	// Fake subpage

		$current_fpage = get_query_var('fpage'); // Fake subpage slug
		$fpage_url = !empty($current_fpage) ? trailingslashit($page_url) . user_trailingslashit($current_fpage) : $page_url; // Fake subpage URL

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

// Image values

	// Get the featured image ID

		$featured_image = $location_fpage_featured_image_expertise; // Image ID
		$featured_image = $featured_image ? $featured_image : '';

// Define the placement for content

	$content_placement = 'subsection'; // Expected values: 'subsection' or 'profile'

// Query for whether to conditionally suppress ontology sections based on Find-a-Doc Settings configuration

	$regions = isset($regions) ? $regions : array();
	$service_lines = isset($service_lines) ? $service_lines : array();

	if (
		$regions
		||
		$service_lines
		) {

		$ontology_hide_vars = isset($ontology_hide_vars) ? $ontology_hide_vars : uamswp_fad_ontology_hide(
			$regions, // string|array // Region(s) associated with the item
			$service_lines // string|array // Service line(s) associated with the item
		);
			$hide_medical_ontology = $ontology_hide_vars['hide_medical_ontology']; // bool

	} else {

		$hide_medical_ontology = false; // bool

	}

// HEAD

	// Title tag

		// Construct the meta title

			$meta_title_base_addition = $fpage_name_attr; // Word or phrase to use to form base meta title
			$meta_title_enhanced_addition = $page_title_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
			$meta_title_base_order = array( $meta_title_base_addition, $meta_title_enhanced_addition ); // Override default base meta title structure to force inclusion of $meta_title_enhanced_addition
			$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(
				$page_title, // string
				$page_title_attr, // string (optional)
				$meta_title_base_addition, // string (optional) // Word or phrase to use to form base meta title // Defaults to $page_title_attr
				$meta_title_base_order, // array (optional) // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number
				$meta_title_enhanced_addition // string (optional) // Word or phrase to inject into base meta title to form enhanced meta title level 1
			);
				$meta_title = $meta_title_vars['meta_title']; // string

		// Override SEOPress's standard title tag settings

			add_filter( 'seopress_titles_title', function( $html ) use ( $meta_title ) {

				if ( $meta_title ) {

					$html = $meta_title;

				}

				return $html;

			}, 15, 2 );



	// Meta Description and Schema Description

		// Get excerpt

			$excerpt = $location_fpage_short_desc_expertise;
			$excerpt_user = true;

			if ( empty( $excerpt ) ) {

				$excerpt_user = false;

			}

			$excerpt_attr = $excerpt ? uamswp_attr_conversion($excerpt) : '';

		// Set schema description

			$schema_description = $excerpt_attr; // Used for Schema Data. Should ALWAYS have a value

		// Override theme's method of defining the meta description

			add_filter('seopress_titles_desc', function( $html ) use ( $excerpt_attr ) {

				if ( $excerpt_attr ) {

					$html = $excerpt_attr;

				}
				return $html;

			} );

	// Meta Keywords

		$keywords = get_field('expertise_alternate_names');

		// Override theme's standard meta keywords settings

			add_action( 'wp_head', function() use ( $keywords ) {
				uamswp_keyword_hook_header(
					$keywords // array
				);
			} );

	// Canonical URL

		// Get the canonical URL
		$canonical_url = $fpage_url;

		// Modify SEOPress's standard canonical URL settings

			add_filter( 'seopress_titles_canonical', function( $html ) use ( $canonical_url ) {

				if ( $canonical_url ) {

					$html = '<link rel="canonical" href="' . $canonical_url . '" />';

				}

				return $html;

			} );

	// Meta Social Media Tags

		// Filter hooks
		include( UAMS_FAD_PATH . '/templates/parts/meta-social.php' );

// BODY

	// Add page template class to body element's classes

		$template_type = 'page_landing';
		add_filter( 'body_class', function( $classes ) use ( $template_type ) {

			// Add page template class to body class array

				if ( $template_type ) {

					$classes[] = 'page-template-' . $template_type;

				}

			return $classes;

		} );

	// Header

		// get_header();

		// Site header

			// Remove the site header set by the theme

				remove_action( 'genesis_header', 'uamswp_site_image', 5 );

			// Add ontology subsection site header

				add_action( 'genesis_header', function() use (
					$page_id,
					$ontology_type,
					$page_title,
					$page_url
				) {
					include( UAMS_FAD_PATH . '/templates/parts/single-expertise-header.php');
				}, 5 );

		// Primary navigation

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
					include( UAMS_FAD_PATH . '/templates/parts/single-expertise-nav.php');
				}, 5 );

	// Breadcrumbs

		// Override Genesis standard breadcrumbs settings

			// Do nothing

		// Override SEOPress standard breadcrumbs settings

			add_filter( 'seopress_pro_breadcrumbs_crumbs', function( $crumbs ) use ( $fpage_name ) {

				// $crumbs is a multidimensional array.
				//     First array: key=position,
				//     second array: 0=>page title, 1=>URL, 2=>ID (since version 6.1)

				// Add name of fake subpage to the breadcrumbs array
				$crumbs[] = array($fpage_name, '');

				return $crumbs;

			} );

	// Page Header (before entry element)

		// Remove Genesis-standard post title and markup

			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
			remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
			remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

		// Construct non-standard post title

			$entry_header_style = 'graphic'; // Entry header style
			$entry_title_text = $fpage_title; // Regular title
			$entry_title_text_supertitle = ''; // Optional supertitle
			$entry_title_text_subtitle = ''; // Optional subtitle
			$entry_title_text_body = $fpage_intro; // Optional lead paragraph
			$entry_title_image_desktop = ''; // Desktop breakpoint image ID
			$entry_title_image_mobile = ''; // Optional mobile breakpoint image ID

			add_action( 'genesis_before_content', function() use (
				$entry_title_text,
				$entry_header_style,
				$entry_title_text_supertitle,
				$entry_title_text_subtitle,
				$entry_title_text_body,
				$entry_title_image_desktop,
				$entry_title_image_mobile
			) {

				// Check/define variables
				$entry_header_style = ( isset($entry_header_style) && !empty($entry_header_style) ) ? $entry_header_style : 'graphic';

				include( UAMS_FAD_PATH . '/templates/parts/entry-title-' . $entry_header_style . '.php');

			} );

	// MAIN / ARTICLE

		// Add bg-white class to article.entry element

			add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

		// Start count for jump links

			// $jump_link_count = 0;

		// Queries for whether each of the sections should be displayed

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
				$treatment_query_vars = isset($treatment_query_vars) ? $treatment_query_vars : uamswp_fad_treatment_query(
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

			// Query for whether Make an Appointment section should be displayed

				$appointment_section_show = true; // It should always be displayed.

		// Get remaining details about this item

			// Do nothing

		// Get remaining details content associated with this item

			// Do nothing

		// Classes for indicating presence of content

			// Do nothing

		// Remove standard post content

			remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		// Construct page content

			// Display ontology page content

				$location_section_schema_query = isset($location_section_schema_query) ? $location_section_schema_query : false; // Query for whether this is a list of child locations within a location
				$location_descendant_list = isset($location_descendant_list) ? $location_descendant_list : false; // Query for whether this is a list of child locations within a location
				$location_section_title = 'List of ' . $location_plural_name; // Text to use for the section title
				$location_section_intro = ''; // Text to use for the section intro text
				$location_section_show_header = false; // Query whether to display the section header
				$location_section_filter = isset($location_section_filter) ? $location_section_filter : true; // Query for whether to add filter(s)
				$location_section_filter_region = isset($location_section_filter_region) ? $location_section_filter_region : true; // Query for whether to add region filter
				$location_section_filter_title = isset($location_section_filter_title) ? $location_section_filter_title : true; // Query for whether to add title filter
				$location_section_collapse_list = false; // Query whether to collapse the list of locations in the providers section

				add_action( 'genesis_entry_content', function() use (
					$locations,
					$page_titles,
					$location_section_schema_query,
					$location_section_show,
					$ontology_type,
					$location_descendant_list,
					$location_section_title,
					$location_section_intro,
					$location_section_show_header,
					$location_section_filter,
					$location_section_filter_region,
					$location_section_filter_title,
					$location_section_collapse_list
				) {
					include( UAMS_FAD_PATH . '/templates/parts/section-list-location.php' );
				}, 22 );

			// Display references to other archive pages

				add_action( 'genesis_entry_content', function() use (
					$page_id,
					$page_titles,
					$current_fpage,
					$ontology_type
				) {
					uamswp_fad_fpage_text_image_overlay(
						$page_id, // int
						$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
						$current_fpage, // string (optional) // Fake subpage slug
						$ontology_type // bool (optional)
					);
				}, 25 );

			// Display appointment information

				add_action( 'genesis_after_entry', function() use ( $appointment_section_show ) {
					uamswp_fad_ontology_appointment(
						$appointment_section_show
					);
				}, 26 );

	// FOOTER

		// Remove the post-related content and markup from the entry footer

			remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
			remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
			remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
			remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

genesis();