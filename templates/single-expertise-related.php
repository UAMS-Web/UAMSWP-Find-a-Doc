<?php
/*
 * Template Name: Fake Area of Expertise Related Areas of Expertise Subpage
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	uamswp_fad_labels_provider();

	// Get system settings for location labels
	uamswp_fad_labels_location();

	// Get system settings for area of expertise labels
	uamswp_fad_labels_expertise();

	// Get system settings for area of expertise descendant item labels
	uamswp_fad_labels_expertise_descendant();

	// Get system settings for clinical resource labels
	uamswp_fad_labels_clinical_resource();

	// Get system settings for combined condition and treatment labels
	uamswp_fad_labels_condition_treatment();

	// Get system settings for condition labels
	uamswp_fad_labels_condition();

	// Get system settings for treatment labels
	uamswp_fad_labels_treatment();

// Get the page ID for the 'parent' area of expertise
$page_id = get_the_ID();

// Get the page title for the 'parent' area of expertise
$page_title = get_the_title(); // Title of the area of expertise
$page_title_attr = uamswp_attr_conversion($page_title);

// Get the page URL for the 'parent' area of expertise
$page_url = get_permalink();

// Area of Expertise Content Type
$ontology_type = get_field('expertise_type'); // True is ontology type, false is content type
$ontology_type = isset($ontology_type) ? $ontology_type : 1; // Check if 'expertise_type' is not null, and if so, set value to true

// Get system settings for fake subpage text elements on Area of Expertise subsection
uamswp_fad_fpage_text_expertise();

// Get system settings for image elements of fake subpage or section on Area of Expertise subsection or profile
uamswp_fad_fpage_image_expertise();

// Get the featured image / post thumbnail
$page_image_id = $expertise_fpage_featured_image_expertise; // Image ID
uamswp_meta_image_resize();

// Set general variables for fake subpage
$fpage_name = 'Related ' . $expertise_plural_name; // Name of ontology item type represented by this fake subpage
$fpage_name_attr = uamswp_attr_conversion($fpage_name);
$fpage_title = $expertise_fpage_title_expertise; // Fake subpage page title
$fpage_title_attr = uamswp_attr_conversion($fpage_title);
$current_fpage = get_query_var('fpage'); // Fake subpage slug
$fpage_url = !empty($current_fpage) ? $page_url . user_trailingslashit($current_fpage) : $page_url; // Fake subpage URL
$fpage_intro = $expertise_fpage_intro_expertise; // Fake subpage intro text
$fpage_intro_attr = uamswp_attr_conversion($fpage_intro); // Attribute-friendly version of fake subpage intro text

// Get site header and site nav values for ontology subsections
uamswp_fad_ontology_site_values();

// Get whether this fake subpage's parent item is the top-level item
$page_top_level_query = $page_id == $ancestors_ontology_farthest; // bool

// Queries for whether each of the associated ontology content sections should be displayed on ontology pages/subsections

	// Query for whether associated providers content section should be displayed on ontology pages/subsections
	uamswp_fad_provider_query();

	// Query for whether associated locations content section should be displayed on ontology pages/subsections
	$location_query_id = $site_nav_id;
	$location_query_field_name = 'location_expertise';
	uamswp_fad_location_query();

	// Query for whether descendant ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	uamswp_fad_expertise_descendant_query();

	// Query for whether related ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	uamswp_fad_expertise_query();

	// Query for whether associated clinical resources content section should be displayed on ontology pages/subsections
	uamswp_fad_clinical_resource_query();

	// Query for whether associated conditions content section should be displayed on ontology pages/subsections
	uamswp_fad_condition_query();

	// Query for whether associated treatments content section should be displayed on ontology pages/subsections
	uamswp_fad_treatment_query();

// Override theme's method of defining the meta page title
$meta_title_base_addition = $fpage_name_attr; // Word or phrase to use to form base meta title
$meta_title_enhanced_addition = $page_title_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
$meta_title_base_order = array( $meta_title_base_addition, $meta_title_enhanced_addition ); // Override default base meta title structure to force inclusion of $meta_title_enhanced_addition
uamswp_fad_title_vars(); // Defines universal variables related to the setting the meta title
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

// Override theme's method of defining the meta description
$excerpt = $expertise_fpage_short_desc_expertise;
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
	add_action( 'genesis_before_content', 'uamswp_fad_post_title' );
	$entry_header_style = 'graphic'; // Entry header style
	$entry_title_text = $fpage_title; // Regular title
	$entry_title_text_supertitle = ''; // Optional supertitle
	$entry_title_text_subtitle = ''; // Optional subtitle
	$entry_title_text_body = $fpage_intro; // Optional lead paragraph
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
	$expertise_section_id = 'related-expertise';
	$expertise_section_show_header = false;
	$expertise_section_title = 'List of Related ' . $expertise_plural_name;
	$expertise_section_intro = '';
	add_action( 'genesis_entry_content', 'uamswp_fad_section_expertise', 24 );

	// Display references to other archive pages
	add_action( 'genesis_entry_content', 'uamswp_fad_fpage_text_image_overlay', 25);

	// Display appointment information
	add_action( 'genesis_entry_content', 'uamswp_fad_ontology_appointment', 26 );
	// Check if Make an Appointment section should be displayed
	$appointment_section_show = true; // It should always be displayed.

genesis();