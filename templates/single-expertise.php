<?php
/*
 * Template Name: Single Area of Expertise
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	$labels_provider = uamswp_fad_labels_provider();
		$provider_single_name = $labels_provider['provider_single_name']; // string
		$provider_single_name_attr = $labels_provider['provider_single_name_attr']; // string
		$provider_plural_name = $labels_provider['provider_plural_name']; // string
		$provider_plural_name_attr = $labels_provider['provider_plural_name_attr']; // string
		$placeholder_provider_single_name = $labels_provider['placeholder_provider_single_name']; // string
		$placeholder_provider_plural_name = $labels_provider['placeholder_provider_plural_name']; // string
		$placeholder_provider_short_name = $labels_provider['placeholder_provider_short_name']; // string
		$placeholder_provider_short_name_possessive = $labels_provider['placeholder_provider_short_name_possessive']; // string

	// Get system settings for location labels
	$labels_location = uamswp_fad_labels_location();
		$location_single_name = $labels_location['location_single_name']; // string
		$location_single_name_attr = $labels_location['location_single_name_attr']; // string
		$location_plural_name = $labels_location['location_plural_name']; // string
		$location_plural_name_attr = $labels_location['location_plural_name_attr']; // string
		$placeholder_location_single_name = $labels_location['placeholder_location_single_name']; // string
		$placeholder_location_plural_name = $labels_location['placeholder_location_plural_name']; // string
		$placeholder_location_page_title = $labels_location['placeholder_location_page_title']; // string
		$placeholder_location_page_title_phrase = $labels_location['placeholder_location_page_title_phrase']; // string

	// // Get system settings for location descendant item labels
	// $labels_location_descendant = uamswp_fad_labels_location_descendant();
	// 	$location_descendant_single_name = $labels_location_descendant['location_descendant_single_name']; // string
	// 	$location_descendant_single_name_attr = $labels_location_descendant['location_descendant_single_name_attr']; // string
	// 	$location_descendant_plural_name = $labels_location_descendant['location_descendant_plural_name']; // string
	// 	$location_descendant_plural_name_attr = $labels_location_descendant['location_descendant_plural_name_attr']; // string
	// 	$placeholder_location_descendant_single_name = $labels_location_descendant['placeholder_location_descendant_single_name']; // string
	// 	$placeholder_location_descendant_plural_name = $labels_location_descendant['placeholder_location_descendant_plural_name']; // string

	// Get system settings for area of expertise labels
	$labels_expertise = uamswp_fad_labels_expertise();
		$expertise_single_name = $labels_expertise['expertise_single_name']; // string
		$expertise_single_name_attr = $labels_expertise['expertise_single_name_attr']; // string
		$expertise_plural_name = $labels_expertise['expertise_plural_name']; // string
		$expertise_plural_name_attr = $labels_expertise['expertise_plural_name_attr']; // string
		$placeholder_expertise_single_name = $labels_expertise['placeholder_expertise_single_name']; // string
		$placeholder_expertise_plural_name = $labels_expertise['placeholder_expertise_plural_name']; // string
		$placeholder_expertise_page_title = $labels_expertise['placeholder_expertise_page_title']; // string

	// Get system settings for area of expertise descendant item labels
	$labels_expertise_descendant = uamswp_fad_labels_expertise_descendant();
		$expertise_descendant_single_name = $labels_expertise_descendant['expertise_descendant_single_name']; // string
		$expertise_descendant_single_name_attr = $labels_expertise_descendant['expertise_descendant_single_name_attr']; // string
		$expertise_descendant_plural_name = $labels_expertise_descendant['expertise_descendant_plural_name']; // string
		$expertise_descendant_plural_name_attr = $labels_expertise_descendant['expertise_descendant_plural_name_attr']; // string
		$placeholder_expertise_descendant_single_name = $labels_expertise_descendant['placeholder_expertise_descendant_single_name']; // string
		$placeholder_expertise_descendant_plural_name = $labels_expertise_descendant['placeholder_expertise_descendant_plural_name']; // string

	// Get system settings for clinical resource labels
	$labels_clinical_resource = uamswp_fad_labels_clinical_resource();
		$clinical_resource_single_name = $labels_clinical_resource['clinical_resource_single_name']; // string
		$clinical_resource_single_name_attr = $labels_clinical_resource['clinical_resource_single_name_attr']; // string
		$clinical_resource_plural_name = $labels_clinical_resource['clinical_resource_plural_name']; // string
		$clinical_resource_plural_name_attr = $labels_clinical_resource['clinical_resource_plural_name_attr']; // string
		$placeholder_clinical_resource_single_name = $labels_clinical_resource['placeholder_clinical_resource_single_name']; // string
		$placeholder_clinical_resource_plural_name = $labels_clinical_resource['placeholder_clinical_resource_plural_name']; // string

	// Get system settings for combined condition and treatment labels
	$labels_condition_treatment = uamswp_fad_labels_condition_treatment();
		$condition_treatment_single_name = $labels_condition_treatment['clinical_resource_single_name']; // string
		$condition_treatment_single_name_attr = $labels_condition_treatment['clinical_resource_single_name']; // string
		$condition_treatment_plural_name = $labels_condition_treatment['clinical_resource_single_name']; // string
		$condition_treatment_plural_name_attr = $labels_condition_treatment['clinical_resource_single_name']; // string
		$placeholder_condition_treatment_single_name = $labels_condition_treatment['clinical_resource_single_name']; // string
		$placeholder_condition_treatment_plural_name = $labels_condition_treatment['clinical_resource_single_name']; // string

	// Get system settings for condition labels
	$labels_condition = uamswp_fad_labels_condition();
		$condition_single_name = $labels_condition['condition_single_name']; // string
		$condition_single_name_attr = $labels_condition['condition_single_name_attr']; // string
		$condition_plural_name = $labels_condition['condition_plural_name']; // string
		$condition_plural_name_attr = $labels_condition['condition_plural_name_attr']; // string
		$placeholder_condition_single_name = $labels_condition['placeholder_condition_single_name']; // string
		$placeholder_condition_plural_name = $labels_condition['placeholder_condition_plural_name']; // string

	// Get system settings for treatment labels
	$labels_treatment = uamswp_fad_labels_treatment();
		$treatment_single_name = $labels_treatment['condition_single_name']; // string
		$treatment_single_name_attr = $labels_treatment['condition_single_name']; // string
		$treatment_plural_name = $labels_treatment['condition_single_name']; // string
		$treatment_plural_name_attr = $labels_treatment['condition_single_name']; // string
		$placeholder_treatment_single_name = $labels_treatment['condition_single_name']; // string
		$placeholder_treatment_plural_name = $labels_treatment['condition_single_name']; // string

// Get system settings for area of expertise archive page text
// uamswp_fad_archive_text_expertise();

// Get the page ID
$page_id = get_the_ID();

// Get the page title for the area of expertise
$page_title = get_the_title();
$page_title_attr = uamswp_attr_conversion($page_title);

// Get the page URL
$page_url = get_permalink();

// Area of Expertise Content Type
$ontology_type = get_field('expertise_type'); // True is ontology type, false is content type
$ontology_type = isset($ontology_type) ? $ontology_type : 1; // Check if 'expertise_type' is not null, and if so, set value to true

// Get system settings for fake subpage text elements in an Area of Expertise subsection
uamswp_fad_fpage_text_expertise();

// Get system settings for image elements of a fake subpage (or section) in an Area of Expertise subsection (or profile)
uamswp_fad_fpage_image_expertise();

// Get the featured image / post thumbnail
$page_image_id = $expertise_featured_image; // Image ID
uamswp_meta_image_resize();

// // Get system settings for jump links (a.k.a. anchor links)
// $labels_jump_links = uamswp_fad_labels_jump_links();
// 	$fad_jump_links_title = $labels_jump_links['fad_jump_links_title']; // string

// Get site header and site nav values for ontology subsections
$ontology_site_values = uamswp_fad_ontology_site_values();
	$site_nav_id = $ontology_site_values['site_nav_id']; // int
	$navbar_subbrand_title = $ontology_site_values['navbar_subbrand']['title']['name']; // string
	$navbar_subbrand_title_attr = $ontology_site_values['navbar_subbrand']['title']['attr']; // string
	$navbar_subbrand_title_url = $ontology_site_values['navbar_subbrand']['title']['url']; // string
	$navbar_subbrand_parent = $ontology_site_values['navbar_subbrand']['parent']['name']; // string
	$navbar_subbrand_parent_attr = $ontology_site_values['navbar_subbrand']['parent']['attr']; // string
	$navbar_subbrand_parent_url = $ontology_site_values['navbar_subbrand']['parent']['url']; // string
	$providers = $ontology_site_values['providers']; // int[]
	$locations = $ontology_site_values['locations']; // int[]
	$expertises = $ontology_site_values['expertises']; // int[]
	$expertise_descendants = $ontology_site_values['expertise_descendants'];
	$clinical_resources = $ontology_site_values['clinical_resources']; // int[]
	$conditions_cpt = $ontology_site_values['conditions_cpt']; // int[]
	$treatments_cpt = $ontology_site_values['treatments_cpt']; // int[]
	$ancestors_ontology_farthest = $ontology_site_values['ancestors_ontology_farthest'];
	$page_top_level_query = $ontology_site_values['page_top_level_query']; // bool

// Queries for whether each of the associated ontology content sections should be displayed on ontology pages/subsections

	// Query for whether related providers content section should be displayed on ontology pages/subsections
	uamswp_fad_provider_query( $providers );

	// Query for whether related locations content section should be displayed on ontology pages/subsections
	$location_query_function = uamswp_fad_location_query( $locations );
		$location_query = $location_query_function['location_query']; // WP_Post[]
		$location_section_show = $location_query_function['location_section_show']; // bool
		$location_ids = $location_query_function['location_ids']; // int[]
		$location_count = $location_query_function['location_count']; // int
		$location_valid = $location_query_function['location_valid']; // bool

	// Query for whether descendant ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	uamswp_fad_expertise_descendant_query();

	// Query for whether related ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	$expertise_query_function = uamswp_fad_expertise_query( $expertises );
		$expertise_query = $expertise_query_function['expertise_query']; // WP_Post[]
		$expertise_section_show = $expertise_query_function['expertise_section_show']; // bool
		$expertise_ids = $expertise_query_function['expertise_ids']; // int[]
		$expertise_count = $expertise_query_function['expertise_count']; // int

	// Query for whether related clinical resources content section should be displayed on ontology pages/subsections
	$clinical_resource_query_function = uamswp_fad_clinical_resource_query( $clinical_resources );
		$clinical_resource_query = $clinical_resource_query_function['clinical_resource_query']; // WP_Post[]
		$clinical_resource_section_show = $clinical_resource_query_function['clinical_resource_section_show']; // bool
		$clinical_resource_ids = $clinical_resource_query_function['clinical_resource_ids']; // int[]
		$clinical_resource_count = $clinical_resource_query_function['clinical_resource_count']; // int

	// Query for whether related conditions content section should be displayed on ontology pages/subsections
	$condition_query_function = uamswp_fad_condition_query( $conditions_cpt );
		$condition_cpt_query = $condition_query_function['clinical_resource_query']; // WP_Post[]
		$condition_section_show = $condition_query_function['clinical_resource_query']; // bool
		$condition_treatment_section_show = $condition_query_function['clinical_resource_query']; // bool
		$condition_ids = $condition_query_function['clinical_resource_query']; // int[]
		$condition_count = $condition_query_function['clinical_resource_query']; // int
		$condition_schema = $condition_query_function['clinical_resource_query']; // string

	// Query for whether related treatments content section should be displayed on ontology pages/subsections
	uamswp_fad_treatment_query();

// Override theme's method of defining the meta page title
$meta_title_enhanced_addition = $expertise_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
$meta_title_vars = uamswp_fad_meta_title_vars(); // Defines universal variables related to the setting the meta title
	$meta_title = $meta_title_vars['meta_title']; // string
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

// Override theme's method of defining the meta description
$excerpt = $expertise_short_desc;
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
$template_type = 'default';

// Add bg-white class to article.entry element
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

// Modify Entry Title

	// Remove Genesis-standard post title and markup
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

	// Construct non-standard post title
	add_action( 'genesis_before_content', 'uamswp_fad_post_title' );
	$entry_header_style = $expertise_page_title_options; // Entry header style
	$entry_title_text = $expertise_page_title; // Regular title
	$entry_title_text_supertitle = ''; // Optional supertitle, placed above the regular title
	$entry_title_text_subtitle = ''; // Optional subtitle, placed below the regular title
	$entry_title_text_body = $expertise_page_intro; // Optional lead paragraph, placed below the entry title
	$entry_title_image_desktop = $expertise_page_image; // Desktop breakpoint image ID
	$entry_title_image_mobile = $expertise_page_image_mobile; // Optional mobile breakpoint image ID

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

	// Construct UAMS Health Talk podcast section
	add_action( 'genesis_after_entry', 'uamswp_fad_podcast', 10 );
	// Check if podcast section should be displayed
	$podcast_name = get_field('expertise_podcast_name');
	$podcast_filter = 'tag';
	$podcast_subject = $page_title;
	uamswp_fad_podcast_query();

	// Display Combined Conditions and Treatments Section
	$condition_treatment_section_title = $condition_treatment_fpage_title_expertise; // Text to use for the section title // string (default: Find-a-Doc Settings value for combined condition/treatment section title in a general placement)
	$condition_treatment_section_intro = $condition_treatment_fpage_intro_expertise; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for combined condition/treatment section intro text in a general placement)
	$condition_section_title = $condition_fpage_title_expertise; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
	$condition_section_intro = $condition_fpage_intro_expertise; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
	$treatment_section_title = $treatment_fpage_title_expertise; // Text to use for the section title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
	$treatment_section_intro = $treatment_fpage_intro_expertise; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)
	add_action( 'genesis_after_entry', 'uamswp_fad_section_condition_treatment', 16 );

	// // Display conditions
	// $condition_section_title = $condition_fpage_title_expertise; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
	// $condition_section_intro = $condition_fpage_intro_expertise; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
	// add_action( 'genesis_after_entry', 'uamswp_fad_section_condition', 16 );

	// // Display treatments
	// $treatment_section_title = $treatment_fpage_title_expertise; // Text to use for the section title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
	// $treatment_section_intro = $treatment_fpage_intro_expertise; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)
	// add_action( 'genesis_after_entry', 'uamswp_fad_section_treatment', 18 );

	// Display appointment information
	add_action( 'genesis_after_entry', 'uamswp_fad_ontology_appointment', 26 );
	// Check if Make an Appointment section should be displayed
	$appointment_section_show = true; // It should always be displayed.

genesis();