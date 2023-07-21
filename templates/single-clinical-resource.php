<?php
/*
 * Template Name: Single Clinical Resource v2
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

// Get the page slug for the clinical resource
$page_slug = $post->post_name;

// Get system settings for text elements on Clinical Resource profile
$fpage_text_clinical_resource_vars = isset($fpage_text_clinical_resource_vars) ? $fpage_text_clinical_resource_vars : uamswp_fad_fpage_text_clinical_resource();
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
$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(); // Defines universal variables related to the setting the meta title
	$meta_title = $meta_title_vars['meta_title']; // string
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

// Modify SEOPress's standard canonical URL settings
$syndicated = get_field('clinical_resource_syndicated');
$syndication_url = get_field('clinical_resource_syndication_url');
function uamswp_fad_canonical($html) {
	// Bring in variables from outside of the function
	global $syndicated; // Defined on the template
	global $syndication_url; // Defined on the template

	if ( $syndicated && !empty($syndication_url) ) {
		$html = '<link rel="canonical" href="' . htmlspecialchars(urldecode($syndication_url)) . '" />';
	}

	return $html;
}
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
$provider_query_vars = isset($provider_query_vars) ? $provider_query_vars : uamswp_fad_provider_query( $providers, $jump_link_count );
	$provider_query = $provider_query_vars['provider_query']; // WP_Post[]
	$provider_section_show = $provider_query_vars['provider_section_show']; // bool
	$provider_ids = $provider_query_vars['provider_ids']; // int[]
	$provider_count = $provider_query_vars['provider_count']; // int
	$jump_link_count = $provider_query_vars['jump_link_count']; // int

// Query for whether related locations content section should be displayed on a page
$locations = get_field('clinical_resource_locations');
$location_query_vars = isset($location_query_vars) ? $location_query_vars : uamswp_fad_location_query( $locations );
	$location_query = $location_query_vars['location_query']; // WP_Post[]
	$location_section_show = $location_query_vars['location_section_show']; // bool
	$location_ids = $location_query_vars['location_ids']; // int[]
	$location_count = $location_query_vars['location_count']; // int
	$location_valid = $location_query_vars['location_valid']; // bool

// Query for whether related areas of expertise content section should be displayed on a page
$expertises = get_field('clinical_resource_aoe');
$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query( $expertises );
	$expertise_query = $expertise_query_vars['expertise_query']; // WP_Post[]
	$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool
	$expertise_ids = $expertise_query_vars['expertise_ids']; // int[]
	$expertise_count = $expertise_query_vars['expertise_count']; // int

// Query for whether related clinical resources content section should be displayed on ontology pages/subsections
$clinical_resources = get_field('clinical_resource_related');
$clinical_resource_posts_per_page = -1; // Maximum number of clinical resources displayed in the section (-1, 4, 6, 8, 10, 12) // int (default: 4)
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
$condition_query_vars = isset($condition_query_vars) ? $condition_query_vars : uamswp_fad_condition_query( $conditions_cpt, $condition_treatment_section_show, $ontology_type );
	$condition_cpt_query = $condition_query_vars['condition_cpt_query']; // WP_Post[]
	$condition_section_show = $condition_query_vars['condition_section_show']; // bool
	$condition_treatment_section_show = $condition_query_vars['condition_treatment_section_show']; // bool
	$condition_ids = $condition_query_vars['condition_ids']; // int[]
	$condition_count = $condition_query_vars['condition_count']; // int
	$condition_treatment_schema = $condition_query_vars['condition_treatment_schema']; // string

// Query for whether related treatments content section should be displayed on ontology pages/subsections
$treatments_cpt = get_field('clinical_resource_treatments');
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

get_header();

while ( have_posts() ) : the_post(); ?>

<div class="content-sidebar-wrap">
	<main class="clinical-resource-item" id="genesis-content">
		<article class="entry bg-white" itemtype="https://schema.org/CreativeWork">
			<?php

				// Construct the page header

					$entry_title_text_supertitle = $clinical_resource_single_name;
					$entry_title_text = $page_title;
					$entry_title_text_subtitle = '';

					include( UAMS_FAD_PATH . '/templates/parts/entry-title-normal.php');
			?>
			<div class="entry-content clearfix" itemprop="text">
				<?php

				// Construct the page content

					// Resource type: article
					function uamswp_resource_text() {
						// Bring in variables from outside of the function
						global $resource_type_value; // Defined on the template

						$text = get_field('clinical_resource_text');
						$nci_query = get_field('clinical_resource_text_nci_query');
						$nci_embed = get_field('clinical_resource_nci_embed');

						if( 'text' == $resource_type_value && $text && !$nci_query ) { // $show_text_section ) {
							echo $text;
						} elseif ( 'text' == $resource_type_value && $nci_query && $nci_embed ) {
							echo $nci_embed;
						}
					}
					uamswp_resource_text();

					// Resource type: infographic
					function uamswp_resource_infographic() {
						// Bring in variables from outside of the function
						global $resource_type_value; // Defined on the template

						$infographic = get_field('clinical_resource_infographic');
						$infographic_descr = get_field('clinical_resource_infographic_descr');
						$infographic_transcript = get_field('clinical_resource_infographic_transcript');
						$size = 'content-image-wide';

						if( 'infographic' == $resource_type_value && $infographic ) {
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
					}
					uamswp_resource_infographic();

					// Resource type: video
					function uamswp_resource_video() {
						// Bring in variables from outside of the function
						global $resource_type_value; // Defined on the template

						$video = get_field('clinical_resource_video');
						$video_descr = get_field('clinical_resource_video_descr');
						$video_transcript = get_field('clinical_resource_video_transcript');

						$video_source = '';
						if ( (strpos($video, 'youtube') !== false) || (strpos($video, 'youtu.be') !== false) ) {
							$video_source = 'youtube';
						}

						if( 'video' == $resource_type_value && $video ) { ?>
							<?php if ( $video_descr ) {
								echo '<h2 class="sr-only">Description</h2>';
								echo $video_descr;
							}
							echo '<h2 class="sr-only">Video Player</h2>';
							if( function_exists('lyte_preparse') && $video_source == 'youtube' ) {
								echo '<div class="alignwide">';
								echo lyte_parse( str_replace( ['https:', 'http:'], 'httpv:', $video ) );
								echo '</div>';
							} else {
								echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
								echo wp_oembed_get( $video );
								echo '</div>';
							}
							if ( $video_transcript ) {
								echo '<h2>Transcript</h2>';
								echo $video_transcript;
							}
						}
					}
					uamswp_resource_video();

					// Resource type: document
					function uamswp_resource_document() {
						// Bring in variables from outside of the function
						global $resource_type_value; // Defined on the template

						$document_descr = get_field('clinical_resource_document_descr');
						$document = get_field('clinical_resource_document');

						$icon_file = 'far fa-file';
						$icon_pdf = 'far fa-file-pdf';
						$icon_word = 'far fa-file-word';
						$icon_powerpoint = 'far fa-file-powerpoint';
						$icon_excel = 'far fa-file-excel';
						$icon_image = 'far fa-file-image';

						if( 'doc' == $resource_type_value && have_rows('clinical_resource_document') ):
							echo $document_descr;
							echo '<hr />';
							echo '<h2>Attachments</h2>';
							echo '<ul class="attachments">';
							while( have_rows('clinical_resource_document') ): the_row();
								$document_title = get_sub_field('document_title');
								$document_file = get_sub_field('document_file');
								$document_url = $document_file['url'];
								$document_url_path = pathinfo($document_url);
								$document_url_extension = $document_url_path['extension'];

								if ( $document_url_extension == 'pdf' ) {
									$icon_file = $icon_pdf;
								} elseif ( $document_url_extension == 'doc' || $document_url_extension == 'docx' ) {
									$icon_file = $icon_word;
								} elseif ( $document_url_extension == 'ppt' || $document_url_extension == 'pptx' ) {
									$icon_file = $icon_powerpoint;
								} elseif ( $document_url_extension == 'xls' || $document_url_extension == 'xlsx' ) {
									$icon_file = $icon_excel;
								} elseif ( $document_url_extension == 'jpg' || $document_url_extension == 'jpeg' || $document_url_extension == 'gif' || $document_url_extension == 'png' || $document_url_extension == 'bmp' ) {
									$icon_file = $icon_image;
								}
							?>
								<li><a class="attachment-link" href="<?php echo $document_url; ?>" title="<?php echo $document_title; ?>" target="_blank"><span class="<?php echo $icon_file; ?> fa-fw"></span><span class="attachment-label"><?php echo $document_title; ?></span></a></li>
							<?php endwhile;
							echo '</ul>';
						endif;
					}
					uamswp_resource_document();

				?>
			</div>
		</article>
		<?php

			// Construct jump links section

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
				uamswp_resource_jump_links();

			// Construct related clinical resources section

				$clinical_resource_section_more_link_key = '';
				$clinical_resource_section_more_link_value = '';
				$clinical_resource_section_title = $clinical_resource_fpage_title_clinical_resource; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of clinical_resource section title in a general placement)
				$clinical_resource_section_intro = $clinical_resource_fpage_intro_clinical_resource; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of clinical_resource section intro text in a general placement)
				$clinical_resource_section_more_show = false;
				include( UAMS_FAD_PATH . '/templates/parts/section-list-clinical-resource.php' );

			// Construct Combined Conditions and Treatments Section

				$ontology_type = isset($ontology_type) ? $ontology_type : true; // bool
				$condition_treatment_section_title = $condition_treatment_fpage_title_clinical_resource; // Text to use for the section title // string (default: Find-a-Doc Settings value for combined condition/treatment section title in a general placement)
				$condition_treatment_section_intro = $condition_treatment_fpage_intro_clinical_resource; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for combined condition/treatment section intro text in a general placement)
				$condition_section_title = $condition_fpage_title_clinical_resource; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
				$condition_section_intro = $condition_fpage_intro_clinical_resource; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
				$treatment_section_title = $treatment_fpage_title_clinical_resource; // Text to use for the section title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
				$treatment_section_intro = $treatment_fpage_intro_clinical_resource; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)
				include( UAMS_FAD_PATH . '/templates/parts/section-list-condition-treatment.php' );

			// Construct providers section

				$provider_section_title = $provider_fpage_title_clinical_resource; // Text to use for the section title
				$provider_section_intro = $provider_fpage_intro_clinical_resource; // Text to use for the section intro text
				$provider_section_filter = false; // Query for whether to add filter(s) // bool (default: true)
				include( UAMS_FAD_PATH . '/templates/parts/section-list-provider.php' );

			// Construct locations section

				$location_section_title = $location_fpage_title_clinical_resource; // Text to use for the section title
				$location_section_intro = $location_fpage_intro_clinical_resource; // Text to use for the section intro text
				$location_section_filter = false; // Query for whether to add filter(s) // bool (default: true)
				include( UAMS_FAD_PATH . '/templates/parts/section-list-location.php' );

			// Construct areas of expertise section

				$expertise_section_title = $expertise_fpage_title_clinical_resource; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of expertise section title in a general placement)
				$expertise_section_intro = $expertise_fpage_intro_clinical_resource; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of expertise section intro text in a general placement)
				include( UAMS_FAD_PATH . '/templates/parts/section-list-expertise.php' );

			// Construct appointment information section

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
				uamswp_resource_appointment();

		?>
	</main>
</div>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>