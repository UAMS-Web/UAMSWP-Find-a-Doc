<?php
/*
 * Template Name: Single Clinical Resource
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

	// // Get system settings for area of expertise descendant item labels
	// $labels_expertise_descendant_vars = isset($labels_expertise_descendant_vars) ? $labels_expertise_descendant_vars : uamswp_fad_labels_expertise_descendant();
	// 	$expertise_descendant_single_name = $labels_expertise_descendant_vars['expertise_descendant_single_name']; // string
	// 	$expertise_descendant_single_name_attr = $labels_expertise_descendant_vars['expertise_descendant_single_name_attr']; // string
	// 	$expertise_descendant_plural_name = $labels_expertise_descendant_vars['expertise_descendant_plural_name']; // string
	// 	$expertise_descendant_plural_name_attr = $labels_expertise_descendant_vars['expertise_descendant_plural_name_attr']; // string
	// 	$placeholder_expertise_descendant_single_name = $labels_expertise_descendant_vars['placeholder_expertise_descendant_single_name']; // string
	// 	$placeholder_expertise_descendant_plural_name = $labels_expertise_descendant_vars['placeholder_expertise_descendant_plural_name']; // string

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

// // Get system settings for clinical resource archive page text
// $archive_text_clinical_resource_vars = isset($archive_text_clinical_resource_vars) ? $archive_text_clinical_resource_vars : uamswp_fad_archive_text_clinical_resource();
// 	$clinical_resource_archive_headline = $archive_text_clinical_resource_vars['clinical_resource_archive_headline']; // string
// 	$clinical_resource_archive_headline_attr = $archive_text_clinical_resource_vars['clinical_resource_archive_headline_attr']; // string
// 	$placeholder_clinical_resource_archive_headline = $archive_text_clinical_resource_vars['placeholder_clinical_resource_archive_headline']; // string

// Get the page ID
$page_id = get_the_ID();

// Get the page title for the clinical resource
$page_title = get_the_title();
$page_title_attr = uamswp_attr_conversion($page_title);

// Array for page titles and section titles
$page_titles = array(
	'page_title'	=> $page_title
);

// Get the page slug for the clinical resource
$page_slug = $post->post_name;

// Define the placement for content
$content_placement = 'profile'; // Expected values: 'subsection' or 'profile'

// Get system settings for text elements on Clinical Resource profile
$fpage_text_clinical_resource_vars = isset($fpage_text_clinical_resource_vars) ? $fpage_text_clinical_resource_vars : uamswp_fad_fpage_text_clinical_resource(
	$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
);
	$provider_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_title_clinical_resource']; // string
	$provider_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_intro_clinical_resource']; // string
	$provider_fpage_ref_main_title_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_title_clinical_resource']; // string
	$provider_fpage_ref_main_intro_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_intro_clinical_resource']; // string
	$provider_fpage_ref_main_link_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_link_clinical_resource']; // string
	$provider_fpage_ref_main_title_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_title_clinical_resource']; // string
	$provider_fpage_ref_main_intro_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_intro_clinical_resource']; // string
	$provider_fpage_ref_main_link_clinical_resource = $fpage_text_clinical_resource_vars['provider_fpage_ref_main_link_clinical_resource']; // string
	$location_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['location_fpage_title_clinical_resource']; // string
	$location_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['location_fpage_intro_clinical_resource']; // string
	$location_fpage_ref_main_title_clinical_resource = $fpage_text_clinical_resource_vars['location_fpage_ref_main_title_clinical_resource']; // string
	$location_fpage_ref_main_intro_clinical_resource = $fpage_text_clinical_resource_vars['location_fpage_ref_main_intro_clinical_resource']; // string
	$location_fpage_ref_main_link_clinical_resource = $fpage_text_clinical_resource_vars['location_fpage_ref_main_link_clinical_resource']; // string
	$expertise_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['expertise_fpage_title_clinical_resource']; // string
	$expertise_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['expertise_fpage_intro_clinical_resource']; // string
	$expertise_fpage_ref_main_title_clinical_resource = $fpage_text_clinical_resource_vars['expertise_fpage_ref_main_title_clinical_resource']; // string
	$expertise_fpage_ref_main_intro_clinical_resource = $fpage_text_clinical_resource_vars['expertise_fpage_ref_main_intro_clinical_resource']; // string
	$expertise_fpage_ref_main_link_clinical_resource = $fpage_text_clinical_resource_vars['expertise_fpage_ref_main_link_clinical_resource']; // string
	$clinical_resource_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_title_clinical_resource']; // string
	$clinical_resource_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_intro_clinical_resource']; // string
	$clinical_resource_fpage_ref_main_title_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_ref_main_title_clinical_resource']; // string
	$clinical_resource_fpage_ref_main_intro_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_ref_main_intro_clinical_resource']; // string
	$clinical_resource_fpage_ref_main_link_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_ref_main_link_clinical_resource']; // string
	$clinical_resource_fpage_more_text_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_more_text_clinical_resource']; // string
	$clinical_resource_fpage_more_link_text_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_more_link_text_clinical_resource']; // string
	$clinical_resource_fpage_more_link_descr_clinical_resource = $fpage_text_clinical_resource_vars['clinical_resource_fpage_more_link_descr_clinical_resource']; // string
	$condition_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['condition_fpage_title_clinical_resource']; // string
	$condition_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['condition_fpage_intro_clinical_resource']; // string
	$treatment_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['treatment_fpage_title_clinical_resource']; // string
	$treatment_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['treatment_fpage_intro_clinical_resource']; // string
	$condition_treatment_fpage_title_clinical_resource = $fpage_text_clinical_resource_vars['condition_treatment_fpage_title_clinical_resource']; // string
	$condition_treatment_fpage_intro_clinical_resource = $fpage_text_clinical_resource_vars['condition_treatment_fpage_intro_clinical_resource']; // string

// Get system settings for jump links (a.k.a. anchor links)
$labels_jump_links_vars = isset($labels_jump_links_vars) ? $labels_jump_links_vars : uamswp_fad_labels_jump_links();
	$fad_jump_links_title = $labels_jump_links_vars['fad_jump_links_title']; // string

// Get resource type
$resource_type = get_field('clinical_resource_type');
$resource_type_value = $resource_type['value'];
$resource_type_label = $resource_type['label'];
$resource_type_label_attr = uamswp_attr_conversion($resource_type_label);

// Override theme's method of defining the meta page title
$meta_title_base_addition = $page_title_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
$meta_title_enhanced_addition = $clinical_resource_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
$meta_title_enhanced_x2_addition = $resource_type_label_attr; // Second word or phrase to inject into base meta title to form enhanced meta title level 2
$meta_title_enhanced_x2_order = array( $meta_title_base_addition, $meta_title_enhanced_x2_addition, $meta_title_enhanced_addition ); // Optional pre-defined array for name order of enhanced meta title level 2 // Expects three values but will accommodate any number
$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(
	$page_title, // string
	$page_title_attr, // string (optional)
	$meta_title_base_addition, // string (optional) // Word or phrase to use to form base meta title // Defaults to $page_title_attr
	'', // array (optional) // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number
	$meta_title_enhanced_addition, // string (optional) // Word or phrase to inject into base meta title to form enhanced meta title level 1
	'', // array (optional) // Pre-defined array for name order of enhanced meta title level 1 // Expects two values but will accommodate any number
	$meta_title_enhanced_x2_addition, // string (optional) // Second word or phrase to inject into base meta title to form enhanced meta title level 2
	$meta_title_enhanced_x2_order // array (optional) // Pre-defined array for name order of enhanced meta title level 2 // Expects three values but will accommodate any number
);
	$meta_title = $meta_title_vars['meta_title']; // string
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

// Modify SEOPress's standard canonical URL settings
$syndicated = get_field('clinical_resource_syndicated');
$canonical_url = $syndicated ? get_field('clinical_resource_syndication_url') : '';
add_filter('seopress_titles_canonical','uamswp_fad_canonical');

// Add page template class to body element's classes
add_filter( 'body_class', 'uamswp_page_body_class' );
$template_type = 'default';

// Set logic for displaying jump links and sections
$jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
$jump_link_count = 0;

// Query for whether related providers content section should be displayed on ontology pages/subsections
$providers = get_field('clinical_resource_providers');
$jump_link_count = isset($jump_link_count) ? $jump_link_count : 0;
$provider_query_vars = isset($provider_query_vars) ? $provider_query_vars : uamswp_fad_provider_query(
	$providers, // int[]
	$jump_link_count // int
);
	$provider_query = $provider_query_vars['provider_query']; // WP_Post[]
	$provider_section_show = $provider_query_vars['provider_section_show']; // bool
	$provider_ids = $provider_query_vars['provider_ids']; // int[]
	$provider_count = $provider_query_vars['provider_count']; // int
	$jump_link_count = $provider_query_vars['jump_link_count']; // int

// Query for whether related locations content section should be displayed on a page
$locations = get_field('clinical_resource_locations');
$location_query_vars = isset($location_query_vars) ? $location_query_vars : uamswp_fad_location_query(
	$locations // int[]
);
	$location_query = $location_query_vars['location_query']; // WP_Post[]
	$location_section_show = $location_query_vars['location_section_show']; // bool
	$location_ids = $location_query_vars['location_ids']; // int[]
	$location_count = $location_query_vars['location_count']; // int
	$location_valid = $location_query_vars['location_valid']; // bool

// Query for whether related areas of expertise content section should be displayed on a page
$expertises = get_field('clinical_resource_aoe');
$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query(
	$expertises // int[]
);
	$expertise_query = $expertise_query_vars['expertise_query']; // WP_Post[]
	$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool
	$expertise_ids = $expertise_query_vars['expertise_ids']; // int[]
	$expertise_count = $expertise_query_vars['expertise_count']; // int

// Query for whether related clinical resources content section should be displayed on ontology pages/subsections
$clinical_resources = get_field('clinical_resource_related');
$posts_per_page_clinical_resource_general_vars = isset($posts_per_page_clinical_resource_general_vars) ? $posts_per_page_clinical_resource_general_vars : uamswp_fad_posts_per_page_clinical_resource_general();
	$clinical_resource_posts_per_page_section = $posts_per_page_clinical_resource_general_vars['clinical_resource_posts_per_page_section']; // int
$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_section;
$jump_link_count = isset($jump_link_count) ? $jump_link_count : 0;
$clinical_resource_query_vars = isset($clinical_resource_query_vars) ? $clinical_resource_query_vars : uamswp_fad_clinical_resource_query(
	$clinical_resources,
	$clinical_resource_posts_per_page,
	$jump_link_count
);
	$clinical_resource_query = $clinical_resource_query_vars['clinical_resource_query']; // WP_Post[]
	$clinical_resource_section_show = $clinical_resource_query_vars['clinical_resource_section_show']; // bool
	$clinical_resource_ids = $clinical_resource_query_vars['clinical_resource_ids']; // int[]
	$clinical_resource_count = $clinical_resource_query_vars['clinical_resource_count']; // int
	$jump_link_count = $clinical_resource_query_vars['jump_link_count']; // int

// Query for whether related conditions content section should be displayed on ontology pages/subsections
$conditions_cpt = get_field('clinical_resource_conditions');
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
$treatments_cpt = get_field('clinical_resource_treatments');
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

// Query for whether appointment information section should be displayed on a page
// It should always be displayed.
$appointment_section_show = true;
$jump_link_count++;

// Query for whether jump links section should be displayed on a page
if ( $jump_link_count >= $jump_link_count_min ) {
	$jump_links_section_show = true;
} else {
	$jump_links_section_show = false;
}

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
// Removes entry meta from entry footer incl. markup.
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

// Add bg-white class to article.entry element
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

// Modify Entry Title

	// Remove Genesis-standard post title and markup
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

	// Construct non-standard post title
	$entry_header_style = 'normal'; // Entry header style
	$entry_title_text = $page_title; // Regular title
	$entry_title_text_supertitle = $clinical_resource_single_name; // Optional supertitle, placed above the regular title
	$entry_title_text_subtitle = ''; // Optional subtitle, placed below the regular title
	$entry_title_text_body = ''; // Optional lead paragraph, placed below the entry title
	$entry_title_image_desktop = ''; // Desktop breakpoint image ID
	$entry_title_image_mobile = ''; // Optional mobile breakpoint image ID

	$uamswp_fad_post_title_args = array(
		'entry_title_text'				=> $entry_title_text, // Entry title text
		'entry_header_style'			=> $entry_header_style, // Entry header style
		'entry_title_text_supertitle'	=> $entry_title_text_supertitle, // Entry supertitle text
		'entry_title_text_subtitle'		=> $entry_title_text_subtitle, // Entry subtitle text
		'entry_title_text_body'			=> $entry_title_text_body, // Entry header lead paragraph text
		'entry_title_image_desktop'		=> $entry_title_image_desktop, // Entry header background image for desktop breakpoints
		'entry_title_image_mobile'		=> $entry_title_image_mobile // Entry header background image for mobile breakpoints
	);

	add_action( 'genesis_entry_header', function() use ($uamswp_fad_post_title_args) {
		uamswp_fad_post_title(
			$uamswp_fad_post_title_args['entry_title_text'], // string // Entry title text
			$uamswp_fad_post_title_args['entry_header_style'], // string // Entry header style
			$uamswp_fad_post_title_args['entry_title_text_supertitle'], // string (optional) // Entry supertitle text
			$uamswp_fad_post_title_args['entry_title_text_subtitle'], // string (optional) // Entry subtitle text
			$uamswp_fad_post_title_args['entry_title_text_body'], // string (optional) // Entry header lead paragraph text
			$uamswp_fad_post_title_args['entry_title_image_desktop'], // int (optional) // Entry header background image for desktop breakpoints
			$uamswp_fad_post_title_args['entry_title_image_mobile'] // int (optional) // Entry header background image for mobile breakpoints
		);
	} );

// Construct page content

	// Construct main clinical resource content section

		add_action( 'genesis_entry_content', function() use ($resource_type_value) {

			if ( 'text' == $resource_type_value ) {

				// Resource type: article

				$text = get_field('clinical_resource_text');
				$nci_query = get_field('clinical_resource_text_nci_query');
				$nci_embed = $nci_query ? get_field('clinical_resource_nci_embed') : '';

				if( $text && !$nci_query ) { // $show_text_section ) {
					echo $text;
				} elseif ( $nci_query && $nci_embed ) {
					echo $nci_embed;
				}

			} elseif ( 'infographic' == $resource_type_value ) {

				// Resource type: infographic

				$infographic = get_field('clinical_resource_infographic');

				if ( $infographic ) {

					$infographic_descr = get_field('clinical_resource_infographic_descr');
					$infographic_transcript = get_field('clinical_resource_infographic_transcript');
					$size = 'content-image-wide';

					if ( $infographic_descr ) {

						echo '<h2 class="sr-only">Description</h2>';
						echo $infographic_descr;

					}

					echo '<h2 class="sr-only">Infographic</h2>';
					echo '<div class="alignwide">';
					echo wp_get_attachment_image( $infographic, $size );
					echo '</div>';

					if ( $infographic_transcript ) {

						echo '<h2>Transcript</h2>';
						echo $infographic_transcript;

					}

				}

			} elseif ( 'video' == $resource_type_value ) {

				// Resource type: video

				$video = get_field('clinical_resource_video');

				if ( $video ) {

					// Check video source

						if (
							strpos( $video, 'youtube' ) !== false
							||
							strpos( $video, 'youtu.be' ) !== false
						) {

							$video_source = 'youtube';

						} else {

							$video_source = '';

						}

					// Display video description

						$video_descr = get_field('clinical_resource_video_descr');

						if ( $video_descr ) {

							echo '<h2 class="sr-only">Description</h2>';
							echo $video_descr;

						}

					// Display video player

						echo '<h2 class="sr-only">Video Player</h2>';

						if (
							function_exists('lyte_preparse')
							&&
							$video_source == 'youtube'
						) {

							echo '<div class="alignwide">';
							echo lyte_parse( str_replace( ['https:', 'http:'], 'httpv:', $video ) );
							echo '</div>';

						} else {

							echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
							echo wp_oembed_get( $video );
							echo '</div>';

						}

					// Display video transcript

						$video_transcript = get_field('clinical_resource_video_transcript');

						if ( $video_transcript ) {

							echo '<h2>Transcript</h2>';
							echo $video_transcript;

						}

				}

			} elseif ( 'doc' == $resource_type_value ) {

				// Resource type: document

					$documents = get_field('clinical_resource_document');

					if ( $documents ) {

						// Display document description

							$document_descr = get_field('clinical_resource_document_descr');

							echo $document_descr;

						// Display document attachments

							$icon_file = 'far fa-file';
							$icon_pdf = 'far fa-file-pdf';
							$icon_word = 'far fa-file-word';
							$icon_powerpoint = 'far fa-file-powerpoint';
							$icon_excel = 'far fa-file-excel';
							$icon_image = 'far fa-file-image';

							echo '<hr />';
							echo '<h2>Attachments</h2>';
							echo '<ul class="attachments">';

							foreach ( $documents as $document ) {

								// Get file attributes

									$document_title = $document['document_title'];
									$document_file = $document['document_file'];
										$document_url = $document_file['url'];
											$document_url_path = pathinfo($document_url);
												$document_url_extension = $document_url_path['extension'];

								// Set icon

									if ( $document_url_extension == 'pdf' ) {

										$icon_file = $icon_pdf;

									} elseif (
										$document_url_extension == 'doc'
										||
										$document_url_extension == 'docx'
									) {

										$icon_file = $icon_word;

									} elseif (
										$document_url_extension == 'ppt'
										||
										$document_url_extension == 'pptx'
									) {

										$icon_file = $icon_powerpoint;

									} elseif (
										$document_url_extension == 'xls'
										||
										$document_url_extension == 'xlsx'
									) {

										$icon_file = $icon_excel;

									} elseif (
										$document_url_extension == 'jpg'
										||
										$document_url_extension == 'jpeg'
										||
										$document_url_extension == 'gif'
										||
										$document_url_extension == 'png'
										||
										$document_url_extension == 'bmp'
									) {

										$icon_file = $icon_image;

									}

								// Construct list item

									?>
									<li><a class="attachment-link" href="<?php echo $document_url; ?>" title="<?php echo $document_title; ?>" target="_blank"><span class="<?php echo $icon_file; ?> fa-fw"></span><span class="attachment-label"><?php echo $document_title; ?></span></a></li>
									<?php

							} // endwhile

							echo '</ul>';

					} // endif

			}

		}, 10 );

	// Construct jump links section

		add_action( 'genesis_after_entry', 'uamswp_resource_jump_links', 8 );

	// Construct related clinical resources section

		$clinical_resource_section_more_link_key = '';
		$clinical_resource_section_more_link_value = '';
		$clinical_resource_section_title = $clinical_resource_fpage_title_clinical_resource; // Text to use for the section title
		$clinical_resource_section_intro = $clinical_resource_fpage_intro_clinical_resource; // Text to use for the section intro text
		$clinical_resource_section_more_show = false; // Query for whether to show the section that links to more items

		$uamswp_fad_section_clinical_resource_args = array(
			'clinical_resources'						=> $clinical_resources, // Value of the related clinical resources input
			'page_titles'								=> $page_titles,
			'clinical_resource_section_more_link_key'	=> $clinical_resource_section_more_link_key,
			'clinical_resource_section_more_link_value'	=> $clinical_resource_section_more_link_value,
			'clinical_resource_section_show'			=> $clinical_resource_section_show, // Query for whether to show the clinical resource section
			'ontology_type'								=> $ontology_type, // Query for whether item is ontology type vs. content type
			'clinical_resource_section_title'			=> $clinical_resource_section_title, // Text to use for the section title
			'clinical_resource_section_intro'			=> $clinical_resource_section_intro, // Text to use for the section intro text
			'clinical_resource_posts_per_page'			=> $clinical_resource_posts_per_page, // Maximum number of clinical resources to display (-1, 4, 6, 8, 10 or 12)
			'clinical_resource_section_more_show'		=> $clinical_resource_section_more_show, // Query for whether to show the section that links to more items
		);

		add_action( 'genesis_after_entry', function() use ($uamswp_fad_section_clinical_resource_args) {
			uamswp_fad_section_clinical_resource(
				$uamswp_fad_section_clinical_resource_args['clinical_resources'], // int[] // Value of the related clinical resources input
				$uamswp_fad_section_clinical_resource_args['page_titles'], // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				$uamswp_fad_section_clinical_resource_args['clinical_resource_section_more_link_key'], // string
				$uamswp_fad_section_clinical_resource_args['clinical_resource_section_more_link_value'], // string
				$uamswp_fad_section_clinical_resource_args['clinical_resource_section_show'], // bool (optional) // Query for whether to show the clinical resource section
				$uamswp_fad_section_clinical_resource_args['ontology_type'], // bool (optional) // Query for whether item is ontology type vs. content type
				$uamswp_fad_section_clinical_resource_args['clinical_resource_section_title'], // string (optional) // Text to use for the section title
				$uamswp_fad_section_clinical_resource_args['clinical_resource_section_intro'], // string (optional) // Text to use for the section intro text
				$uamswp_fad_section_clinical_resource_args['clinical_resource_posts_per_page'], // int (optional) // Maximum number of clinical resources to display (-1, 4, 6, 8, 10 or 12)
				$uamswp_fad_section_clinical_resource_args['clinical_resource_section_more_show'], // bool (optional) // Query for whether to show the section that links to more items
			);
		}, 10 );

	// Construct Combined Conditions and Treatments Section

		$ontology_type = isset($ontology_type) ? $ontology_type : true;
		$condition_treatment_section_title = $condition_treatment_fpage_title_clinical_resource; // Text to use for the section title
		$condition_treatment_section_intro = $condition_treatment_fpage_intro_clinical_resource; // Text to use for the section intro text
		$condition_section_title = $condition_fpage_title_clinical_resource; // Text to use for the section title
		$condition_section_intro = $condition_fpage_intro_clinical_resource; // Text to use for the section intro text
		$treatment_section_title = $treatment_fpage_title_clinical_resource; // Text to use for the section title
		$treatment_section_intro = $treatment_fpage_intro_clinical_resource; // Text to use for the section intro text

		$uamswp_fad_section_condition_treatment_args = array(
			'conditions_cpt'					=> $conditions_cpt,
			'treatments_cpt'					=> $treatments_cpt,
			'page_titles'						=> $page_titles,
			'hide_medical_ontology'				=> $hide_medical_ontology, // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
			'condition_treatment_section_show'	=> $condition_treatment_section_show,
			'condition_section_show'			=> $condition_section_show,
			'treatment_section_show'			=> $treatment_section_show,
			'ontology_type'						=> $ontology_type,
			'condition_treatment_section_title'	=> $condition_treatment_section_title, // Text to use for the section title
			'condition_treatment_section_intro'	=> $condition_treatment_section_intro, // Text to use for the section intro text
			'condition_section_title'			=> $condition_section_title, // Text to use for the conditions subsection title
			'condition_section_intro'			=> $condition_section_intro, // Text to use for the conditions subsection intro text
			'treatment_section_title'			=> $treatment_section_title, // Text to use for the treatments subsection title
			'treatment_section_intro'			=> $treatment_section_intro // Text to use for the treatments subsection intro text
		);

		add_action( 'genesis_after_entry', function() use ($uamswp_fad_section_condition_treatment_args) {
			uamswp_fad_section_condition_treatment(
				$uamswp_fad_section_condition_treatment_args['conditions_cpt'], // int[]
				$uamswp_fad_section_condition_treatment_args['treatments_cpt'], // int[]
				$uamswp_fad_section_condition_treatment_args['page_titles'], // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				$uamswp_fad_section_condition_treatment_args['hide_medical_ontology'], // bool (optional) // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
				$uamswp_fad_section_condition_treatment_args['condition_treatment_section_show'], // bool
				$uamswp_fad_section_condition_treatment_args['condition_section_show'], // bool
				$uamswp_fad_section_condition_treatment_args['treatment_section_show'], // bool
				$uamswp_fad_section_condition_treatment_args['ontology_type'], // bool
				$uamswp_fad_section_condition_treatment_args['condition_treatment_section_title'], // string // Text to use for the section title
				$uamswp_fad_section_condition_treatment_args['condition_treatment_section_intro'], // string // Text to use for the section intro text
				$uamswp_fad_section_condition_treatment_args['condition_section_title'], // string // Text to use for the conditions subsection title
				$uamswp_fad_section_condition_treatment_args['condition_section_intro'], // string // Text to use for the conditions subsection intro text
				$uamswp_fad_section_condition_treatment_args['treatment_section_title'], // string // Text to use for the treatments subsection title
				$uamswp_fad_section_condition_treatment_args['treatment_section_intro'] // string // Text to use for the treatments subsection intro text
			);
		}, 12 );

	// Construct providers section

		$provider_section_title = $provider_fpage_title_clinical_resource; // Text to use for the section title
		$provider_section_intro = $provider_fpage_intro_clinical_resource; // Text to use for the section intro text
		$provider_section_show_header = isset($provider_section_show_header) ? $provider_section_show_header : true; // Query for whether to display the section header
		$provider_section_filter = false; // Query for whether to add filter(s) // bool (default: true)

		$uamswp_fad_section_provider_args = array(
			'providers'						=> $providers, // Value of the related providers input
			'page_titles'					=> $page_titles,
			'provider_section_show'			=> $provider_section_show, // Query for whether to show the provider section
			'ontology_type'					=> $ontology_type, // Query for whether item is ontology type vs. content type
			'provider_section_title'		=> $provider_section_title, // Text to use for the section title
			'provider_section_intro'		=> $provider_section_intro, // Text to use for the section intro text
			'provider_section_show_header'	=> $provider_section_show_header, // Query for whether to display the section header
			'provider_section_filter'		=> $provider_section_filter // Query for whether to add filter(s)
		);

		add_action( 'genesis_after_entry', function() use ($uamswp_fad_section_provider_args) {
			uamswp_fad_section_provider(
				$uamswp_fad_section_provider_args['providers'], // int[] // Value of the related providers input
				$uamswp_fad_section_provider_args['page_titles'], // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				$uamswp_fad_section_provider_args['provider_section_show'], // bool (optional) // Query for whether to show the provider section
				$uamswp_fad_section_provider_args['ontology_type'], // bool (optional) // Query for whether item is ontology type vs. content type
				$uamswp_fad_section_provider_args['provider_section_title'], // string (optional) // Text to use for the section title
				$uamswp_fad_section_provider_args['provider_section_intro'], // string (optional) // Text to use for the section intro text
				$uamswp_fad_section_provider_args['provider_section_show_header'], // bool (optional) // Query for whether to display the section header
				$uamswp_fad_section_provider_args['provider_section_filter'] // bool (optional) // Query for whether to add filter(s)
			);
		}, 16 );

	// Construct locations section

		$location_section_schema_query = isset($location_section_schema_query) ? $location_section_schema_query : false;
		$location_descendant_list = isset($location_descendant_list) ? $location_descendant_list : false;
		$location_section_title = $location_fpage_title_clinical_resource; // Text to use for the section title
		$location_section_intro = $location_fpage_intro_clinical_resource; // Text to use for the section intro text
		$location_section_show_header = isset($location_section_show_header) ? $location_section_show_header : true;
		$location_section_filter = false; // Query for whether to add filter(s) // bool (default: true)
		$location_section_filter_region = false; // Query for whether to add region filter
		$location_section_filter_title = false; // Query for whether to add title filter
		$location_section_collapse_list = false; // Query for whether to collapse the list of locations in the locations section

		$uamswp_fad_section_location_args = array(
			'locations'							=> $locations, // Value of the related locations input
			'page_titles'						=> $page_titles,
			'location_section_schema_query'		=> $location_section_schema_query, // Query for whether to add locations to schema
			'location_section_show'				=> $location_section_show, // Query for whether to show the location section
			'ontology_type'						=> $ontology_type, // Query for whether item is ontology type vs. content type
			'location_descendant_list'			=> $location_descendant_list, // Query for whether this is a list of child locations within a location
			'location_section_title'			=> $location_section_title, // Text to use for the section title
			'location_section_intro'			=> $location_section_intro, // Text to use for the section intro text
			'location_section_show_header'		=> $location_section_show_header, // Query for whether to display the section header
			'location_section_filter'			=> $location_section_filter, // Query for whether to add filter(s)
			'location_section_filter_region'	=> $location_section_filter_region, // Query for whether to add region filter
			'location_section_filter_title'		=> $location_section_filter_title, // Query for whether to add title filter
			'location_section_collapse_list'	=> $location_section_collapse_list // Query for whether to collapse the list of locations in the locations section
		);

		add_action( 'genesis_after_entry', function() use ($uamswp_fad_section_location_args) {
			uamswp_fad_section_location(
				$uamswp_fad_section_location_args['locations'], // int[] // Value of the related locations input
				$uamswp_fad_section_location_args['page_titles'], // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				$uamswp_fad_section_location_args['location_section_schema_query'], // bool (optional) // Query for whether to add locations to schema
				$uamswp_fad_section_location_args['location_section_show'], // bool (optional) // Query for whether to show the location section
				$uamswp_fad_section_location_args['ontology_type'], // bool (optional) // Query for whether item is ontology type vs. content type
				$uamswp_fad_section_location_args['location_descendant_list'], // bool (optional) // Query for whether this is a list of child locations within a location
				$uamswp_fad_section_location_args['location_section_title'], // string (optional) // Text to use for the section title
				$uamswp_fad_section_location_args['location_section_intro'], // string (optional) // Text to use for the section intro text
				$uamswp_fad_section_location_args['location_section_show_header'], // bool (optional) // Query for whether to display the section header
				$uamswp_fad_section_location_args['location_section_filter'], // bool (optional) // Query for whether to add filter(s)
				$uamswp_fad_section_location_args['location_section_filter_region'], // bool (optional) // Query for whether to add region filter
				$uamswp_fad_section_location_args['location_section_filter_title'], // bool (optional) // Query for whether to add title filter
				$uamswp_fad_section_location_args['location_section_collapse_list'] // bool (optional) // Query for whether to collapse the list of locations in the locations section
			);
		}, 18 );

	// Construct areas of expertise section

		$expertise_descendant_list = isset($expertise_descendant_list) ? $expertise_descendant_list : false; // Query for whether this is a list of child areas of expertise within an area of expertise
		$site_nav_id = ''; // ID of post that defines the subsection
		$expertise_section_title = $expertise_fpage_title_clinical_resource; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of expertise section title in a general placement)
		$expertise_section_intro = $expertise_fpage_intro_clinical_resource; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of expertise section intro text in a general placement)

		$uamswp_fad_section_expertise_args = array(
			'expertises'				=> $expertises, // Value of the related areas of expertise input (or 'expertise_descendants'	=> $expertise_descendants, List of this area of expertise item's descendant items)
			'page_titles'				=> $page_titles,
			'hide_medical_ontology'		=> $hide_medical_ontology, // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
			'expertise_section_show'	=> $expertise_section_show, // Query for whether to show the area of expertise section
			'ontology_type'				=> $ontology_type, // Query for whether item is ontology type vs. content type
			'expertise_descendant_list'	=> $expertise_descendant_list, // Query for whether this is a list of child areas of expertise within an area of expertise
			'content_placement'			=> $content_placement, // string (optional) // Expected values: 'subsection' or 'profile'
			'site_nav_id'				=> $site_nav_id, // ID of post that defines the subsection
			'expertise_section_title'	=> $expertise_section_title, // Text to use for the section title
			'expertise_section_intro'	=> $expertise_section_intro, // Text to use for the section intro text
		);

		add_action( 'genesis_after_entry', function() use ($uamswp_fad_section_expertise_args) {
			uamswp_fad_section_expertise(
				$uamswp_fad_section_expertise_args['expertises'], // int[] // Value of the related areas of expertise input (or $uamswp_fad_section_expertise_args['expertise_descendants'], List of this area of expertise item's descendant items)
				$uamswp_fad_section_expertise_args['page_titles'], // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				$uamswp_fad_section_expertise_args['hide_medical_ontology'], // bool (optional) // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
				$uamswp_fad_section_expertise_args['expertise_section_show'], // bool // Query for whether to show the area of expertise section
				$uamswp_fad_section_expertise_args['ontology_type'], // bool (optional) // Query for whether item is ontology type vs. content type
				$uamswp_fad_section_expertise_args['expertise_descendant_list'], // bool (optional) // Query for whether this is a list of child areas of expertise within an area of expertise
				$uamswp_fad_section_expertise_args['content_placement'], // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
				$uamswp_fad_section_expertise_args['site_nav_id'], // int (optional) // ID of post that defines the subsection
				$uamswp_fad_section_expertise_args['expertise_section_title'], // string (optional) // Text to use for the section title
				$uamswp_fad_section_expertise_args['expertise_section_intro'], // string (optional) // Text to use for the section intro text
			);
		}, 20 );

	// Construct appointment information section

		add_action( 'genesis_after_entry', 'uamswp_resource_appointment', 22 );

function uamswp_resource_jump_links() {
	// Bring in variables from outside of the function
	global $provider_plural_name; // Defined in uamswp_fad_labels_provider()
	global $provider_plural_name_attr; // Defined in uamswp_fad_labels_provider()
	global $location_plural_name; // Defined in uamswp_fad_labels_location()
	global $location_plural_name_attr; // Defined in uamswp_fad_labels_location()
	global $expertise_plural_name; // Defined in uamswp_fad_labels_expertise()
	global $expertise_plural_name_attr; // Defined in uamswp_fad_labels_expertise()
	global $clinical_resource_plural_name; // Defined in uamswp_fad_labels_clinical_resource()
	global $clinical_resource_plural_name_attr; // Defined in uamswp_fad_labels_clinical_resource()
	global $condition_plural_name; // Defined in uamswp_fad_labels_condition()
	global $condition_plural_name_attr; // Defined in uamswp_fad_labels_condition()
	global $treatment_plural_name; // Defined in uamswp_fad_labels_treatment()
	global $treatment_plural_name_attr; // Defined in uamswp_fad_labels_treatment()
	global $fad_jump_links_title; // Defined in uamswp_fad_labels_jump_links()
	global $page_title; // Defined on the template
	global $clinical_resource_section_show; // Defined on the template
	global $condition_section_show; // Defined on the template
	global $treatment_section_show; // Defined on the template
	global $provider_section_show; // Defined on the template
	global $location_section_show; // Defined on the template
	global $expertise_section_show; // Defined on the template
	global $jump_links_section_show; // Defined on the template
	global $appointment_section_show; // Defined on the template

	// Begin Jump Links Section
	if ( $jump_links_section_show ) { ?>
		<nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
			<h2><?php echo $fad_jump_links_title; ?></h2>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse inner-container" id="jump-link-nav">
				<ul class="nav navbar-nav">
					<?php if ( $clinical_resource_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#related-resources" title="Jump to the section of this page about related <?php echo $clinical_resource_plural_name_attr; ?>">Related <?php echo $clinical_resource_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $condition_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#conditions" title="Jump to the section of this page about related <?php echo $condition_plural_name_attr; ?>"><?php echo $condition_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $treatment_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#treatments" title="Jump to the section of this page about related <?php echo $treatment_plural_name_attr; ?>"><?php echo $treatment_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $provider_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#providers" title="Jump to the section of this page about related <?php echo $provider_plural_name_attr; ?>"><?php echo $provider_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ($location_section_show) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#locations" title="Jump to the section of this page about related <?php echo $location_plural_name_attr; ?>"><?php echo $location_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ($expertise_section_show) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#expertise" title="Jump to the section of this page about related <?php echo $expertise_plural_name_attr; ?>"><?php echo $expertise_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $appointment_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#appointment-info" title="Jump to the section of this page about making an appointment">Make an Appointment</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</nav>
	<?php }
}
function uamswp_resource_appointment() {
	// Bring in variables from outside of the function
	global $location_single_name; // Defined in uamswp_fad_labels_location()
	global $location_single_name_attr; // Defined in uamswp_fad_labels_location()
	global $appointment_section_show; // Defined on the template

	if ( $appointment_section_show ) {
		$appointment_location_url = '/location/';
		//$appointment_location_label = 'View a list of UAMS Health locations';
		?>
		<section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2>Make an Appointment</h2>
						<p>Request an appointment by <a href="<?php echo $appointment_location_url; ?>" data-itemtitle="Contact a <?php echo strtolower($location_single_name_attr); ?> directly">contacting a <?php echo strtolower($location_single_name); ?> directly</a> or by calling the UAMS&nbsp;Health appointment line at <a href="tel:501-686-8000" class="no-break" data-itemtitle="Call the UAMS Health appointment line">(501) 686-8000</a>.</p>
					</div>
				</div>
			</div>
		</section>
	<?php }
}
genesis();