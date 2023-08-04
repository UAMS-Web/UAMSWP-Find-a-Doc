<?php
/*
 * Template Name: Single Clinical Resource
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-provider.php' );

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-location.php' );

	// Get system settings for area of expertise labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-expertise.php' );

	// Get system settings for clinical resource labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-clinical-resource.php' );

	// Get system settings for combined condition and treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-condition-treatment.php' );

	// Get system settings for condition labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-condition.php' );

	// Get system settings for treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-treatment.php' );

// // Get system settings for this post type's archive page text
// include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_archive-clinical-resource.php' );

// Ontology / Content Type

	$ontology_type = true; // Ontology type of the post (true is ontology type, false is content type)

// Get the page ID

	$page_id = get_the_ID();

// Get the page title

	$page_title = get_the_title();
	$page_title_attr = uamswp_attr_conversion($page_title);

	// Array for page titles and section titles

		$page_titles = array(
			'page_title'		=> $page_title,
			'page_title_attr'	=> $page_title_attr
		);

	// Get system settings for elements of a fake subpage (or section) in an Clinical subsection (or profile)

		// Text elements

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

		// Image elements

			// Do nothing

	// Name of ontology item type represented by this fake subpage

		// Do nothing

	// Fake subpage page title

		// Do nothing

	// Fake subpage intro text

		// Do nothing

// Get the page URL and slug

	$page_url = user_trailingslashit(get_permalink());
	$page_slug = $post->post_name;

	// Fake subpage

		// Do nothing

// Get site header and site nav values for ontology subsections

	// Do nothing

// Image values

	// Get the featured image ID

		$featured_image = get_post_thumbnail_id(); // int // Featured image ID
		$featured_image = $featured_image ? $featured_image : '';

// Define the placement for content

	$content_placement = 'profile'; // Expected values: 'subsection' or 'profile'

// Query for whether to conditionally suppress ontology sections based on Find-a-Doc Settings configuration

	$regions = isset($regions) ? $regions : array();
	$service_lines = isset($service_lines) ? $service_lines : array();

	include( UAMS_FAD_PATH . '/templates/parts/vars/page/ontology-hide.php' );

// HEAD

	// Title tag

		// Get relevant values

			// Get resource type

				$resource_type = get_field('clinical_resource_type');
				$resource_type_value = $resource_type['value'];
				$resource_type_label = $resource_type['label'];
				$resource_type_label_attr = uamswp_attr_conversion($resource_type_label);

		// Construct the meta title

			$meta_title_base_addition = $page_title_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
			$meta_title_enhanced_addition = $clinical_resource_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
			$meta_title_enhanced_x2_addition = $resource_type_label_attr; // Second word or phrase to inject into base meta title to form enhanced meta title level 2
			$meta_title_enhanced_x2_order = array( $meta_title_base_addition, $meta_title_enhanced_x2_addition, $meta_title_enhanced_addition ); // Optional pre-defined array for name order of enhanced meta title level 2 // Expects three values but will accommodate any number
			$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(
				$page_title_attr, // string
				$meta_title_base_addition, // string (optional) // Word or phrase to use to form base meta title // Defaults to $page_title_attr
				'', // array (optional) // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number
				$meta_title_enhanced_addition, // string (optional) // Word or phrase to inject into base meta title to form enhanced meta title level 1
				'', // array (optional) // Pre-defined array for name order of enhanced meta title level 1 // Expects two values but will accommodate any number
				$meta_title_enhanced_x2_addition, // string (optional) // Second word or phrase to inject into base meta title to form enhanced meta title level 2
				$meta_title_enhanced_x2_order // array (optional) // Pre-defined array for name order of enhanced meta title level 2 // Expects three values but will accommodate any number
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

			$excerpt = get_the_excerpt(); // get_field( 'clinical_resource_excerpt' );
			$excerpt_user = true;

		// Get the content

			// Avoid PHP errors

				$content = '';
				$text = '';
				$infographic_descr = '';
				$video_descr = '';
				$document_descr = '';

			if ( 'text' == $resource_type_value ) {

				// Resource type: article

				$text = get_field('clinical_resource_text');
				$content = $text;

			} elseif ( 'infographic' == $resource_type_value ) {

				// Resource type: infographic

				$infographic_descr = get_field('clinical_resource_infographic_descr');
				$content = $infographic_descr;


			} elseif ( 'video' == $resource_type_value ) {

				// Resource type: video

				$video_descr = get_field('clinical_resource_video_descr');
				$content = $video_descr;

			} elseif ( 'doc' == $resource_type_value ) {

				// Resource type: document

				$document_descr = get_field('clinical_resource_document_descr');
				$content = $document_descr;

			}


		// Create excerpt if none exists

			if ( empty( $excerpt ) ) {

				$excerpt_user = false;

				if ( $content ) {

					$excerpt = mb_strimwidth(wp_strip_all_tags($content), 0, 155, '...');

				}

			}

		$excerpt_attr = uamswp_attr_conversion($excerpt);

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

		// $keywords = '';
		// 
		// // Override theme's standard meta keywords settings
		// 
		// 	add_action( 'wp_head', function() use ( $keywords ) {
		// 		uamswp_keyword_hook_header(
		// 			$keywords // array
		// 		);
		// 	} );

	// Canonical URL

		// Query for whether this clinical resource is syndicated from another source

			$syndicated = get_field('clinical_resource_syndicated');

		// Get syndication URL

			if ( $syndicated ) {

				$canonical_url = user_trailingslashit(
					htmlspecialchars(
						urldecode(
							get_field('clinical_resource_syndication_url')
						)
					)
				);

			} else {

				$canonical_url = '';

			}

		// Modify SEOPress's standard canonical URL settings

			add_filter( 'seopress_titles_canonical', function( $html ) use ( $canonical_url ) {

				if ( $canonical_url ) {

					$html = '<link rel="canonical" href="' . $canonical_url . '" />';

				}

				return $html;

			} );

	// Meta Social Media Tags

		// Filter hooks
		include( UAMS_FAD_PATH . '/templates/parts/meta/social.php' );

// BODY

	// Add page template class to body element's classes

		$template_type = 'default';
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

				// remove_action( 'genesis_header', 'uamswp_site_image', 5 );

			// Add ontology subsection site header

				// add_action( 'genesis_header', function() use (
				// 	$page_id,
				// 	$ontology_type,
				// 	$page_title,
				// 	$page_url
				// ) {
				// 	include( UAMS_FAD_PATH . '/templates/parts/site-header/single-expertise.php');
				// }, 5 );

		// Primary navigation

			// Remove the primary navigation set by the theme

				// remove_action( 'genesis_after_header', 'genesis_do_nav' );
				// remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

			// Add ontology subsection primary navigation

				// add_action( 'genesis_after_header', function() use (
				// 	$page_id,
				// 	$ontology_type,
				// 	$page_title,
				// 	$page_url
				// ) {
				// 	include( UAMS_FAD_PATH . '/templates/parts/site-nav/single-expertise.php');
				// }, 5 );

	// Breadcrumbs

		// Override Genesis standard breadcrumbs settings

			// Do nothing

		// Override SEOPress standard breadcrumbs settings

			// Do nothing

	// Page Header (before entry element)

		// Remove Genesis-standard post title and markup

			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
			remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
			remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

		// Construct non-standard post title

			$entry_header_style = 'normal'; // Entry header style
			$entry_title_text = $page_title; // Regular title
			$entry_title_text_supertitle = $clinical_resource_single_name; // Optional supertitle, placed above the regular title
			$entry_title_text_subtitle = ''; // Optional subtitle, placed below the regular title
			$entry_title_text_body = ''; // Optional lead paragraph, placed below the entry title
			$entry_title_image_desktop = ''; // Desktop breakpoint image ID
			$entry_title_image_mobile = ''; // Optional mobile breakpoint image ID

			add_action( 'genesis_entry_header', function() use (
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

				include( UAMS_FAD_PATH . '/templates/parts/entry-title/' . $entry_header_style . '.php');

			} );

	// MAIN / ARTICLE

		// Add bg-white class to article.entry element

			// add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

		// Start count for jump links

			$jump_link_count = 0;

		// Queries for whether each of the sections should be displayed

			// Query for whether related providers content section should be displayed on ontology pages/subsections

				$providers = get_field('clinical_resource_providers');
				$jump_link_count = isset($jump_link_count) ? $jump_link_count : 0;
				$provider_query_vars = uamswp_fad_provider_query(
					$page_id, // int
					$providers, // int[]
					$jump_link_count // int
				);
					$provider_query = $provider_query_vars['provider_query']; // WP_Post[]
					$provider_section_show = $provider_query_vars['provider_section_show']; // bool
					$provider_ids = $provider_query_vars['provider_ids']; // int[]
					$provider_count = $provider_query_vars['provider_count']; // int

			// Query for whether related locations content section should be displayed on a page

				$locations = get_field('clinical_resource_locations');
				$location_query_vars = uamswp_fad_location_query(
					$page_id, // int
					$locations, // int[]
					$jump_link_count // int
				);
					$location_query = $location_query_vars['location_query']; // WP_Post[]
					$location_section_show = $location_query_vars['location_section_show']; // bool
					$location_ids = $location_query_vars['location_ids']; // int[]
					$location_count = $location_query_vars['location_count']; // int
					$location_valid = $location_query_vars['location_valid']; // bool

			// Query for whether related areas of expertise content section should be displayed on a page

				$expertises = get_field('clinical_resource_aoe');
				$expertise_query_vars = uamswp_fad_expertise_query(
					$page_id, // int
					$expertises, // int[]
					$jump_link_count // int
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
				$clinical_resource_query_vars = uamswp_fad_clinical_resource_query(
					$page_id, // int
					$clinical_resources, // int[]
					$clinical_resource_posts_per_page, // int
					$jump_link_count // int
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
				$condition_query_vars = uamswp_fad_condition_query(
					$page_id, // int
					$conditions_cpt, // int[]
					$condition_treatment_section_show, // bool
					$ontology_type, // bool
					$jump_link_count // int
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
				$treatment_query_vars = uamswp_fad_treatment_query(
					$page_id, // int
					$treatments_cpt, // int[]
					$condition_treatment_section_show, // bool (optional)
					$ontology_type, // bool
					$jump_link_count // int
				);
					$treatment_cpt_query = $treatment_query_vars['treatment_cpt_query']; // WP_Post[]
					$treatment_section_show = $treatment_query_vars['treatment_section_show']; // bool
					$condition_treatment_section_show = $treatment_query_vars['condition_treatment_section_show']; // bool
					$treatment_ids = $treatment_query_vars['treatment_ids']; // int[]
					$treatment_count = $treatment_query_vars['treatment_count']; // int
					$schema_medical_specialty = $treatment_query_vars['schema_medical_specialty']; // array

			// Query for whether Make an Appointment section should be displayed

				$appointment_section_show = true; // It should always be displayed.
				$jump_link_count++;

		// Get remaining details about this item

			// Do nothing

		// Get remaining details content associated with this item

			// Do nothing

		// Classes for indicating presence of content

			// Do nothing

		// Remove standard post content

			// remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		// Construct page content

			// Construct main clinical resource content section

				add_action( 'genesis_entry_content', function() use (
					$resource_type_value,
					$text,
					$infographic_descr,
					$video_descr,
					$document_descr
				) {

					if ( 'text' == $resource_type_value ) {

						// Resource type: article

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

				add_action( 'genesis_after_entry', function() use(
					$jump_link_count,
					$provider_section_show,
					$location_section_show,
					$expertise_section_show,
					$clinical_resource_section_show,
					$condition_treatment_section_show,
					$appointment_section_show
				) {
					include( UAMS_FAD_PATH . '/templates/parts/section/jump-links.php' );
				}, 8 );

			// Construct related clinical resources section

				$clinical_resource_section_more_link_key = '';
				$clinical_resource_section_more_link_value = '';
				$clinical_resource_section_title = $clinical_resource_fpage_title_clinical_resource; // Text to use for the section title
				$clinical_resource_section_intro = $clinical_resource_fpage_intro_clinical_resource; // Text to use for the section intro text
				$clinical_resource_section_more_show = false; // Query for whether to show the section that links to more items

				add_action( 'genesis_after_entry', function() use (
					$page_id,
					$clinical_resources,
					$page_titles,
					$clinical_resource_section_more_link_key,
					$clinical_resource_section_more_link_value,
					$clinical_resource_section_show,
					$ontology_type,
					$clinical_resource_section_title,
					$clinical_resource_section_intro,
					$clinical_resource_posts_per_page,
					$clinical_resource_section_more_show
				) {
					include( UAMS_FAD_PATH . '/templates/parts/section/list/clinical-resource.php' );
				}, 10 );

			// Construct Combined Conditions and Treatments Section

				$ontology_type = isset($ontology_type) ? $ontology_type : true;
				$condition_treatment_section_title = $condition_treatment_fpage_title_clinical_resource; // Text to use for the section title
				$condition_treatment_section_intro = $condition_treatment_fpage_intro_clinical_resource; // Text to use for the section intro text
				$condition_section_title = $condition_fpage_title_clinical_resource; // Text to use for the section title
				$condition_section_intro = $condition_fpage_intro_clinical_resource; // Text to use for the section intro text
				$treatment_section_title = $treatment_fpage_title_clinical_resource; // Text to use for the section title
				$treatment_section_intro = $treatment_fpage_intro_clinical_resource; // Text to use for the section intro text

				add_action( 'genesis_after_entry', function() use (
					$page_id,
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
					include( UAMS_FAD_PATH . '/templates/parts/section/list/condition-treatment.php' );
				}, 12 );

			// Construct providers section

				$provider_section_title = $provider_fpage_title_clinical_resource; // Text to use for the section title
				$provider_section_intro = $provider_fpage_intro_clinical_resource; // Text to use for the section intro text
				$provider_section_show_header = isset($provider_section_show_header) ? $provider_section_show_header : true; // Query for whether to display the section header
				$provider_section_filter = false; // Query for whether to add filter(s) // bool (default: true)

				add_action( 'genesis_after_entry', function() use (
					$page_id,
					$providers,
					$page_titles,
					$provider_section_show,
					$ontology_type,
					$provider_section_title,
					$provider_section_intro,
					$provider_section_show_header,
					$provider_section_filter
				) {
					include( UAMS_FAD_PATH . '/templates/parts/section/list/provider.php' );
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

				add_action( 'genesis_after_entry', function() use (
					$page_id,
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
					include( UAMS_FAD_PATH . '/templates/parts/section/list/location.php' );
				}, 18 );

			// Construct areas of expertise section

				$expertise_descendant_list = isset($expertise_descendant_list) ? $expertise_descendant_list : false; // Query for whether this is a list of child areas of expertise within an area of expertise
				$site_nav_id = ''; // ID of post that defines the subsection
				$expertise_section_title = $expertise_fpage_title_clinical_resource; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of expertise section title in a general placement)
				$expertise_section_intro = $expertise_fpage_intro_clinical_resource; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of expertise section intro text in a general placement)

				add_action( 'genesis_after_entry', function() use (
					$page_id,
					$expertises,
					$page_titles,
					$hide_medical_ontology,
					$expertise_section_show,
					$ontology_type,
					$expertise_descendant_list,
					$content_placement,
					$site_nav_id,
					$expertise_section_title,
					$expertise_section_intro
				) {
					include( UAMS_FAD_PATH . '/templates/parts/section/list/expertise.php' );
				}, 20 );

			// Construct appointment information section

				add_action( 'genesis_after_entry', function() use (
					$location_single_name,
					$location_single_name_attr,
					$appointment_section_show
				) {
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
						<?php
					}
				}, 22 );

	// FOOTER

		// Remove the post-related content and markup from the entry footer

			remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
			remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
			remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
			remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

genesis();