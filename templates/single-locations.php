<?php 
/*
 * Template Name: Single Location
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

	// Get system settings for location descendant item labels

		$labels_location_descendant_vars = isset($labels_location_descendant_vars) ? $labels_location_descendant_vars : uamswp_fad_labels_location_descendant();
			$location_descendant_single_name = $labels_location_descendant_vars['location_descendant_single_name']; // string
			$location_descendant_single_name_attr = $labels_location_descendant_vars['location_descendant_single_name_attr']; // string
			$location_descendant_plural_name = $labels_location_descendant_vars['location_descendant_plural_name']; // string
			$location_descendant_plural_name_attr = $labels_location_descendant_vars['location_descendant_plural_name_attr']; // string
			$placeholder_location_descendant_single_name = $labels_location_descendant_vars['placeholder_location_descendant_single_name']; // string
			$placeholder_location_descendant_plural_name = $labels_location_descendant_vars['placeholder_location_descendant_plural_name']; // string

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

// Get system settings for this post type's archive page text

	// $archive_text_location_vars = isset($archive_text_location_vars) ? $archive_text_location_vars : uamswp_fad_archive_text_location();
	// 	$location_archive_headline = $archive_text_location_vars['location_archive_headline']; // string
	// 	$location_archive_headline_attr = $archive_text_location_vars['location_archive_headline_attr']; // string
	// 	$placeholder_location_archive_headline = $archive_text_location_vars['placeholder_location_archive_headline']; // string

// Ontology / Content Type

	$ontology_type = true; // Ontology type of the post (true is ontology type, false is content type)

// Get the page ID

	$page_id = get_the_ID();

	// Parent location ID

		$location_has_parent = get_field('location_parent');
		$location_parent_id = $location_has_parent ? get_field('location_parent_id') : '';
		$location_has_parent = $location_parent_id ? true : false;

	// Parent location post object

		$parent_location = $location_has_parent ? get_post( $location_parent_id ) : '';
		$location_has_parent = $parent_location ? true : false;

	// Relevant ID for determining address and images
	$post_id = $location_has_parent ? $location_parent_id : $page_id;

// Get the page title and other name values

	$page_title = get_the_title(); // Title of the location
	$page_title_attr = $page_title ? uamswp_attr_conversion($page_title) : '';
	$page_title_phrase = ( get_field('location_prepend_the') ? 'the ' : '' ) . $page_title; // Conditionally prepend "the" to the title for use in phrases
	$page_title_phrase_attr = $page_title_phrase ? uamswp_attr_conversion($page_title_phrase) : '';

	// Parent location

		$parent_title = $parent_location ? $parent_location->post_title : '';
		$parent_title_attr = $parent_title ? uamswp_attr_conversion($parent_title) : '';
		$parent_title_phrase = ( get_field('location_prepend_the', $location_parent_id ) ? 'the ' : '' ) . $parent_title; // Conditionally prepend "the" to the title for use in phrases
		$parent_title_phrase_attr = $parent_title_phrase ? uamswp_attr_conversion($parent_title_phrase) : '';

	// Array for page titles and section titles

		$page_titles = array(
			'page_title'				=> $page_title,
			'page_title_attr'			=> $page_title_attr,
			'page_title_phrase'			=> $page_title_phrase,
			'page_title_phrase_attr'	=> $page_title_phrase_attr,
			'parent_title'				=> $parent_title,
			'parent_title_attr'			=> $parent_title_attr,
			'parent_title_phrase'		=> $parent_title_phrase,
			'parent_title_phrase_attr'	=> $parent_title_phrase_attr
		);

	// Get system settings for elements of a fake subpage (or section) in an Area of Expertise subsection (or profile)

		// Text elements

			$fpage_text_location_vars = isset($fpage_text_location_vars) ? $fpage_text_location_vars : uamswp_fad_fpage_text_location(
				$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
			);
				$provider_fpage_title_location = $fpage_text_location_vars['provider_fpage_title_location']; // string
				$provider_fpage_intro_location = $fpage_text_location_vars['provider_fpage_intro_location']; // string
				$provider_fpage_ref_main_title_location = $fpage_text_location_vars['provider_fpage_ref_main_title_location']; // string
				$provider_fpage_ref_main_intro_location = $fpage_text_location_vars['provider_fpage_ref_main_intro_location']; // string
				$provider_fpage_ref_main_link_location = $fpage_text_location_vars['provider_fpage_ref_main_link_location']; // string
				$provider_fpage_ref_top_title_location = $fpage_text_location_vars['provider_fpage_ref_top_title_location']; // string
				$provider_fpage_ref_top_intro_location = $fpage_text_location_vars['provider_fpage_ref_top_intro_location']; // string
				$provider_fpage_ref_top_link_location = $fpage_text_location_vars['provider_fpage_ref_top_link_location']; // string
				$location_descendant_fpage_title_location = $fpage_text_location_vars['location_descendant_fpage_title_location']; // string
				$location_descendant_fpage_intro_location = $fpage_text_location_vars['location_descendant_fpage_intro_location']; // string
				$location_descendant_fpage_ref_main_title_location = $fpage_text_location_vars['location_descendant_fpage_ref_main_title_location']; // string
				$location_descendant_fpage_ref_main_intro_location = $fpage_text_location_vars['location_descendant_fpage_ref_main_intro_location']; // string
				$location_descendant_fpage_ref_main_link_location = $fpage_text_location_vars['location_descendant_fpage_ref_main_link_location']; // string
				$expertise_fpage_title_location = $fpage_text_location_vars['expertise_fpage_title_location']; // string
				$expertise_fpage_intro_location = $fpage_text_location_vars['expertise_fpage_intro_location']; // string
				$expertise_fpage_ref_main_title_location = $fpage_text_location_vars['expertise_fpage_ref_main_title_location']; // string
				$expertise_fpage_ref_main_intro_location = $fpage_text_location_vars['expertise_fpage_ref_main_intro_location']; // string
				$expertise_fpage_ref_main_link_location = $fpage_text_location_vars['expertise_fpage_ref_main_link_location']; // string
				$expertise_fpage_ref_top_title_location = $fpage_text_location_vars['expertise_fpage_ref_top_title_location']; // string
				$expertise_fpage_ref_top_intro_location = $fpage_text_location_vars['expertise_fpage_ref_top_intro_location']; // string
				$expertise_fpage_ref_top_link_location = $fpage_text_location_vars['expertise_fpage_ref_top_link_location']; // string
				$clinical_resource_fpage_title_location = $fpage_text_location_vars['clinical_resource_fpage_title_location']; // string
				$clinical_resource_fpage_intro_location = $fpage_text_location_vars['clinical_resource_fpage_intro_location']; // string
				$clinical_resource_fpage_ref_main_title_location = $fpage_text_location_vars['clinical_resource_fpage_ref_main_title_location']; // string
				$clinical_resource_fpage_ref_main_intro_location = $fpage_text_location_vars['clinical_resource_fpage_ref_main_intro_location']; // string
				$clinical_resource_fpage_ref_main_link_location = $fpage_text_location_vars['clinical_resource_fpage_ref_main_link_location']; // string
				$clinical_resource_fpage_ref_top_title_location = $fpage_text_location_vars['clinical_resource_fpage_ref_top_title_location']; // string
				$clinical_resource_fpage_ref_top_intro_location = $fpage_text_location_vars['clinical_resource_fpage_ref_top_intro_location']; // string
				$clinical_resource_fpage_ref_top_link_location = $fpage_text_location_vars['clinical_resource_fpage_ref_top_link_location']; // string
				$clinical_resource_fpage_more_text_location = $fpage_text_location_vars['clinical_resource_fpage_more_text_location']; // string
				$clinical_resource_fpage_more_link_text_location = $fpage_text_location_vars['clinical_resource_fpage_more_link_text_location']; // string
				$clinical_resource_fpage_more_link_descr_location = $fpage_text_location_vars['clinical_resource_fpage_more_link_descr_location']; // string
				$condition_fpage_title_location = $fpage_text_location_vars['condition_fpage_title_location']; // string
				$condition_fpage_intro_location = $fpage_text_location_vars['condition_fpage_intro_location']; // string
				$treatment_fpage_title_location = $fpage_text_location_vars['treatment_fpage_title_location']; // string
				$treatment_fpage_intro_location = $fpage_text_location_vars['treatment_fpage_intro_location']; // string
				$condition_treatment_fpage_title_location = $fpage_text_location_vars['condition_treatment_fpage_title_location']; // string
				$condition_treatment_fpage_intro_location = $fpage_text_location_vars['condition_treatment_fpage_intro_location']; // string

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

	// Parent location

		$parent_url = $location_has_parent ? user_trailingslashit(get_permalink( $location_parent_id )) : '';
		$parent_slug = $location_has_parent ? $parent_location->post_name : '';

	// Fake subpage

		// Do nothing

// Get site header and site nav values for ontology subsections

	// Do nothing

// Image values

	// Query on whether to override the parent location's photos

		$override_parent_photo = $location_has_parent ? get_field('location_image_override_parent') : '';

		// Query on whether to override the parent location's featured image
		$override_parent_photo_featured = $override_parent_photo ? get_field('location_image_override_parent_featured') : false;

		// Query on whether to override the parent location's wayfinding photo
		$override_parent_photo_wayfinding = $override_parent_photo ? get_field('location_image_override_parent_wayfinding') : false;

		// Query on whether to override the parent location's gallery photos
		$override_parent_photo_gallery = $override_parent_photo ? get_field('location_image_override_parent_gallery') : false;

	// Get the wayfinding photo ID
	$wayfinding_photo = $override_parent_photo_wayfinding ? get_field('location_wayfinding_photo') : get_field('location_wayfinding_photo', $post_id); // int

	// Get the list of photo gallery item IDs
	$photo_gallery = $override_parent_photo_gallery ? get_field('location_photo_gallery') : get_field('location_photo_gallery', $post_id); // array

	// Get the featured image ID
	$featured_image = $override_parent_photo_featured ? get_post_thumbnail_id() : get_post_thumbnail_id($post_id); // int

	// Create the list of photos to use in the photo carousel

		$location_images = array();

		// Add the wayfinding photo to the photo carousel list

			if ( $wayfinding_photo ) {

				$location_images[] = $wayfinding_photo;

			}

		// Add the photo gallery items to the photo carousel list

			if ( $photo_gallery ) {

				foreach ( $photo_gallery as $photo_gallery_image ) {

					$location_images[] = $photo_gallery_image;

				}
			}

		// Count the number of items in the photo carousel list
		$location_images_count = count($location_images);

	// Set image for schema

		// Eliminate PHP errors

			$schema_image = '';
			$schema_image_id = '';

		// Set the schema image ID

			if ( $featured_image ) {

				$schema_image_id = $featured_image;

			} elseif ( $location_images ) {

				$schema_image_id = $location_images[0];

			}

		// Set the schema image URL

			if (
				function_exists( 'fly_add_image_size' )
				&&
				!empty($schema_image_id)
			) {

				$schema_image = image_sizer( $schema_image_id, 640, 480, 'center', 'center' );

			} else {

				$schema_image = wp_get_attachment_image_src( $schema_image_id, 'large' );

			}

// Define the placement for content

	$content_placement = 'profile'; // Expected values: 'subsection' or 'profile'

// Query for whether to conditionally suppress ontology sections based on Find-a-Doc Settings configuration

	$regions = get_field('physician_region',$post->ID);
	$service_lines = get_field('physician_service_line',$post->ID);

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

		// Get relevant values

			// Get the location's city

				$location_city = get_field('location_city', $post_id); // Get the location's city
				$location_city_attr = $location_city ? uamswp_attr_conversion($location_city) : '';

		// Construct the meta title

			if (
				$parent_title_attr
				&&
				$location_city_attr
			) {

				$meta_title_enhanced_addition = $parent_title_attr; // Word or phrase to inject into base meta title to form enhanced meta title
				$meta_title_enhanced_x2_addition = $location_city_attr; // Second word or phrase to inject into base meta title to form enhanced meta title level 2

			} elseif ( $location_city_attr ) {

				$meta_title_enhanced_addition = $location_city_attr; // Word or phrase to inject into base meta title to form enhanced meta title
				$meta_title_enhanced_x2_addition = ''; // Second word or phrase to inject into base meta title to form enhanced meta title level 2

			} else {

				$meta_title_enhanced_addition = ''; // Word or phrase to inject into base meta title to form enhanced meta title
				$meta_title_enhanced_x2_addition = ''; // Second word or phrase to inject into base meta title to form enhanced meta title level 2

			}

			$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(
				$page_title, // string
				$page_title_attr, // string (optional)
				'', // string (optional) // Word or phrase to use to form base meta title // Defaults to $page_title_attr
				'', // array (optional) // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number
				$meta_title_enhanced_addition, // string (optional) // Word or phrase to inject into base meta title to form enhanced meta title level 1
				'', // array (optional) // Pre-defined array for name order of enhanced meta title level 1 // Expects two values but will accommodate any number
				$meta_title_enhanced_x2_addition // string (optional) // Second word or phrase to inject into base meta title to form enhanced meta title level 2
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

			$excerpt = get_the_excerpt();
			$excerpt_user = true;

		// Get location description

			$location_about = get_field('location_about');
			$content = $location_about;

		// Create excerpt if none exists

			if ( empty( $excerpt ) ) {

				$excerpt_user = false;

				if ( $content ) {

					$excerpt = mb_strimwidth(wp_strip_all_tags($content), 0, 155, '...');

				}

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

		// Do nothing

	// Meta Social Media Tags

		// Filter hooks
		include( UAMS_FAD_PATH . '/templates/parts/meta-social.php' );

// BODY

	// Add page template class to body element's classes

		// $template_type = '';
		// add_filter( 'body_class', function( $classes ) use ( $template_type ) {
		// 
		// 	// Add page template class to body class array
		// 
		// 		if ( $template_type ) {
		// 
		// 			$classes[] = 'page-template-' . $template_type;
		// 
		// 		}
		// 
		// 	return $classes;
		// 
		// } );

	// Header

		get_header();

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
				// 	uamswp_fad_ontology_header(
				// 		$page_id, // int // ID of the post
				// 		$ontology_type, // bool (optional) // Ontology type of the post (true is ontology type, false is content type)
				// 		$page_title, // string (optional) // Title of the post
				// 		$page_url // string (optional) // Permalink of the post
				// 	);
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
				// 	uamswp_fad_ontology_nav_menu(
				// 		$page_id, // int // ID of the post
				// 		$ontology_type, // bool (optional) // Ontology type of the post (true is ontology type, false is content type)
				// 		$page_title, // string (optional) // Title of the post
				// 		$page_url // string (optional) // Permalink of the post
				// 	);
				// }, 5 );

	// Breadcrumbs

		// Override Genesis standard breadcrumbs settings

			// Do nothing

		// Override SEOPress standard breadcrumbs settings

			// Do nothing

	// Page Header (before entry element)

		// Remove Genesis-standard post title and markup

			// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
			// remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
			// remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
			// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

		// Construct non-standard post title

			// $entry_header_style = ''; // Entry header style
			// $entry_title_text = ''; // Regular title
			// $entry_title_text_supertitle = ''; // Optional supertitle, placed above the regular title
			// $entry_title_text_subtitle = ''; // Optional subtitle, placed below the regular title
			// $entry_title_text_body = ''; // Optional lead paragraph, placed below the entry title
			// $entry_title_image_desktop = ''; // Desktop breakpoint image ID
			// $entry_title_image_mobile = ''; // Optional mobile breakpoint image ID
			// 
			// add_action( 'genesis_before_content', function() use (
			// 	$entry_title_text,
			// 	$entry_header_style,
			// 	$entry_title_text_supertitle,
			// 	$entry_title_text_subtitle,
			// 	$entry_title_text_body,
			// 	$entry_title_image_desktop,
			// 	$entry_title_image_mobile
			// ) {
			// 	uamswp_fad_post_title(
			// 		$entry_title_text, // string // Entry title text
			// 		$entry_header_style, // string // Entry header style
			// 		$entry_title_text_supertitle, // string (optional) // Entry supertitle text
			// 		$entry_title_text_subtitle, // string (optional) // Entry subtitle text
			// 		$entry_title_text_body, // string (optional) // Entry header lead paragraph text
			// 		$entry_title_image_desktop, // int (optional) // Entry header background image for desktop breakpoints
			// 		$entry_title_image_mobile // int (optional) // Entry header background image for mobile breakpoints
			// 	);
			// } );

	// MAIN / ARTICLE

		// Add bg-white class to article.entry element

			// add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

		// Start count for jump links

			$jump_link_count = 0;

		// Queries for whether each of the sections should be displayed

			// Check if Location Alert section should be displayed

				// Set location alert values

					$location_alert_title_sys = get_field('location_alert_heading_system', 'option');
					$location_alert_text_sys = get_field('location_alert_body_system', 'option');
					$location_alert_color_sys = get_field('location_alert_color_system', 'option');

					$location_alert_suppress = get_field('location_alert_suppress');
					$location_alert_modification = get_field('location_alert_modification');

					$location_alert_title_local = get_field('location_alert_heading');
					$location_alert_text_local = get_field('location_alert_body');
					$location_alert_color_local = get_field('location_alert_color');

					$location_alert_title = $location_alert_title_sys;

					if ( !empty($location_alert_title_local) && $location_alert_modification == 'override' ) {
						$location_alert_title = $location_alert_title_local;
					}

					$location_alert_color = $location_alert_color_sys;

					if ( $location_alert_modification == 'override' && $location_alert_color_local != 'inherit' ) {
						$location_alert_color = $location_alert_color_local;
					}

					$location_alert_text = $location_alert_text_sys;

					if ( $location_alert_modification == 'override' && !empty($location_alert_text_local) ) {
						$location_alert_text = $location_alert_text_local;
					} elseif ( $location_alert_modification == 'prepend' && !empty($location_alert_text_local) ) {
						$location_alert_text = $location_alert_text_local . $location_alert_text_sys;
					} elseif ( $location_alert_modification == 'append' && !empty($location_alert_text_local) ) {
						$location_alert_text = $location_alert_text_sys . $location_alert_text_local;
					}

					if ( $location_alert_modification == 'suppress' ) {
						$location_alert_suppress = true;
						$location_alert_title = '';
						$location_alert_text = '';
					}

				// Set location closing information values

					$location_closing = get_field('location_closing'); // true or false
					$location_closing_date = '';
					$location_closing_date_past = false;
					$location_closing_length = '';
					$location_reopen_known = '';
					$location_reopen_date = '';
					$location_reopen_date_past = false;
					$location_closing_info = '';
					$location_closing_telemed = '';
					$location_closing_display = false;

					if ( $location_closing ) {
						$location_closing_date = get_field('location_closing_date'); // F j, Y
						if (new DateTime() >= new DateTime($location_closing_date)) {
							$location_closing_date_past = true;
						}
						$location_closing_length = get_field('location_closing_length');
						$location_reopen_known = get_field('location_reopen_known');
						$location_reopen_date = get_field('location_reopen_date'); // F j, Y
						if (new DateTime() >= new DateTime($location_reopen_date)) {
							$location_reopen_date_past = true;
						}
						$location_closing_info = get_field('location_closing_info');
						if ($location_closing_length == 'temporary') {
							$location_closing_telemed = get_field('location_closing_telemed'); // Will telemedicine be available during closure?
						}
						if (
							$location_closing_length == 'permanent'
							|| ($location_closing_length == 'temporary' && !$location_reopen_date_past)
							) {
							$location_closing_display = true;
						}
					}

				if (
					(
						( $location_closing && !$location_closing_date_past ) // If location closing is toggled, but closing start date is future
						||
						( $location_closing && $location_reopen_date_past ) // If location closing is toggled, but reopening date is past (or is TBD)
						||
						!$location_closing // If location closing is not toggled
					)
					&& 
					( $location_alert_title || $location_alert_text ) // If location alert title or location alert description has value
				) {

					$location_alert_section_show = true;
					$jump_link_count++;

				} else {

					$location_alert_section_show = false;

				}

			// Check if Closing Information section should be displayed

				if (
					$location_closing_display
					&&
					!empty($location_closing_info)
				) {

					$closing_section_show = true;
					$jump_link_count++;

				} else {

					$closing_section_show = false;

				}

			// Check if About section should be displayed

				// Set prescription values

					$prescription_query = get_field('location_prescription_query'); // Display prescription information

					if ( $prescription_query ) {

						$prescription_info_type = get_field('location_prescription_type'); // Which preset or custom text?

						if ( $prescription_info_type == 'clinic' ) {

							$prescription_clinic_sys = get_field('location_prescription_clinic_system', 'option'); // Text from Find-a-Doc settings (a.k.a. system) for calling clinic
							$prescription = $prescription_clinic_sys; // Text from location (a.k.a. local)

						} elseif ( $prescription_info_type == 'pharm' ) {

							$prescription_pharm_sys = get_field('location_prescription_pharm_system', 'option'); // Text from Find-a-Doc settings (a.k.a. system) for calling pharmacy
							$prescription = $prescription_pharm_sys; // Text from location (a.k.a. local)

						} else {

							$prescription = get_field('location_prescription'); // Text from location (a.k.a. local)

						}

						$prescription_section_show = $prescription ? true : false; // Query on whether to display the section

					} else {

						// Eliminate PHP errors

							$prescription = '';
							$prescription_info_type = '';
							$prescription_section_show = false;

					}

				$location_about_section_show = $location_about ? true : false;
				$location_affiliation = get_field('location_affiliation');
				$location_affiliation_section_show = $location_affiliation ? true : false; // Query on whether to display the section
				$location_youtube_link = get_field('location_youtube_link');
				$location_about_section_title = '';
				$location_about_section_title_short = '';
				$location_about_section_submenu = false; // Query on whether to include the submenu under this item in the jump links item submenu
				$location_about_section_label = 'Jump to the section of this page with the ' . strtolower($location_single_name) . ' description';

				if (
					$location_about_section_show
					||
					$location_affiliation_section_show
					||
					$prescription_section_show
				) {

					$location_about_section_show = true;
					$jump_link_count++;

					if (
						$location_about_section_show
						||
						$location_youtube_link
						||
						(
							!$location_about_section_show
							&&
							$location_affiliation_section_show
							&&
							$prescription_section_show
						)
					) {
						$location_about_section_title = 'About ' . $page_title_phrase;
						$location_about_section_title_short = 'About';

						if (
							$location_affiliation_section_show
							||
							$prescription_section_show
						) {

							$location_about_section_submenu = true; // Query on whether to include the item in the jump links item submenu

						}

					} elseif ( $location_affiliation_section_show ) {

						$location_about_section_title = 'Affiliation';
						$location_about_section_title_short = $location_about_section_title;
						$location_about_section_label = 'Jump to the section of this page about ' . $location_about_section_title;

					} elseif ( $prescription_section_show ) {

						$location_about_section_title = 'Prescription Information';
						$location_about_section_title_short = $location_about_section_title;
						$location_about_section_label = 'Jump to the section of this page about ' . $location_about_section_title;

					}
				} else {

					$location_about_section_show = false;

				}

			// Check if Parking and Directions section should be displayed

				$location_parking = get_field('location_parking', $post_id);
				$location_direction = get_field('location_direction', $post_id);
				$parking_map = get_field('location_parking_map', $post_id);

				if ( $location_parking || $location_direction || $parking_map ) {
					$parking_section_show = true;
					$jump_link_count++;
				} else {
					$parking_section_show = false;
				}

			// Query for whether Appointment Information section should be displayed

				$location_appointment = get_field('location_appointment');
				$location_appointment_bring = get_field('location_appointment_bring');
				$location_appointment_expect = get_field('location_appointment_expect');

				if (
					$location_appointment
					||
					$location_appointment_bring
					||
					$location_appointment_expect
				) {

					$appointment_section_show = true;
					$jump_link_count++;

				} else {

					$appointment_section_show = false;

				}

			// Check if MyChart Open Scheduling section should be displayed

				$mychart_scheduling_query_system = get_field('mychart_scheduling_query_system', 'option');
				$location_scheduling_query = get_field('location_scheduling_query');
				$location_scheduling_options = get_field('location_scheduling_options');

				// Set main appointment scheduling section title

					$location_scheduling_title_default = 'Schedule an Appointment Online'; // Default value for appointment section title
					$location_scheduling_title_general = get_field('location_scheduling_title_general'); // Get input for general appointment section title
					$location_scheduling_title = ( isset($location_scheduling_title_general) && !empty($location_scheduling_title_general) ) ? $location_scheduling_title_general : $location_scheduling_title_default; // Set main title from general title input. If general title value is empty, set to default value.

				// Set main appointment scheduling section intro

					$location_scheduling_intro_default = 'Use your UAMS Health MyChart account to schedule an appointment at this ' . strtolower($location_single_name) . '. If you are not a MyChart user, you can continue as a guest.'; // Default value for appointment section intro
					$location_scheduling_intro_general = get_field('location_scheduling_intro_general'); // Get input for general appointment section intro
					$location_scheduling_intro = ( isset($location_scheduling_intro_general) && !empty($location_scheduling_intro_general) ) ? $location_scheduling_intro_general : $location_scheduling_intro_default; // Set main intro from general intro input. If general intro value is empty, set to default value.

				// Change main appointment scheduling section title and intro if only one scheduling widget

					if ($location_scheduling_query && (count((array)$location_scheduling_options) < 2)) {
						$row = $location_scheduling_options[0];
						$location_scheduling_item_title_main = $row['location_scheduling_title']; // Get input for specific appointment section standalone title
						$location_scheduling_title = ( isset($location_scheduling_item_title_main) && !empty($location_scheduling_item_title_main) ) ? $location_scheduling_item_title_main : $location_scheduling_title; // If input for specific appointment section title exists, use that. Otherwise, keep original value.
						$location_scheduling_item_intro_main = $row['location_scheduling_intro']; // Get input for specific appointment section standalone intro
						$location_scheduling_intro = ( isset($location_scheduling_item_intro_main) && !empty($location_scheduling_item_intro_main) ) ? $location_scheduling_item_intro_main : $location_scheduling_intro; // If input for specific appointment section intro exists, use that. Otherwise, keep original value.
					}


				$mychart_scheduling_domain = get_field('mychart_scheduling_domain', 'option');
				$mychart_scheduling_instance = get_field('mychart_scheduling_instance', 'option');
				$mychart_scheduling_linksource = get_field('mychart_scheduling_linksource', 'option');
				$mychart_scheduling_linksource = ( isset($mychart_scheduling_linksource) && !empty($mychart_scheduling_linksource) ) ? $mychart_scheduling_linksource : 'uamshealth.com';

				if ( $mychart_scheduling_query_system && $location_scheduling_query ) {
					$mychart_scheduling_section_show = true;
				} else {
					$mychart_scheduling_section_show = false;
				}

			// Check if Telemedicine Information section should be displayed

				// Set telemedicine values

					// Original Set

						// $telemedicine_query = get_field('field_location_telemed_query'); // Is there telemedicine?
						// $telemedicine_patients = get_field('field_location_telemed_patients'); // New patients, existing or both?
						// $telemedicine_hours247 = get_field('field_location_telemed_24_7'); // typically 24/7?
						// $telemedicine_hours = get_field('location_telemed_hours'); // telemedicine hours repeater
						// $telemedicine_modified = get_field('field_location_telemed_modified_hours_query'); // Are there modified hours for telemedicine?
						// $telemedicine_modified_reason = get_field('field_location_telemed_modified_hours_reason'); // Why are there modified hours for telemedicine?
						// $telemedicine_modified_start = get_field('field_location_telemed_modified_hours_start_date'); // When do the modified telemedicine hours start?
						// $telemedicine_modified_end = get_field('field_location_telemed_modified_hours_end'); // Do we know when the modified telemedicine hours end?
						// $telemedicine_modified_end_date = get_field('field_location_telemed_modified_hours_end_date'); // When do the modified telemedicine hours end?
						// $telemedicine_modified_hours = get_field('field_location_telemed_modified_hours_group'); // modified telemedicine hours repeater

					// Hours Grouping

						$location_hours_group = get_field('location_hours_group');

						$telemedicine_query = $location_hours_group['location_telemed_query']; // Is there telemedicine?
						$telemedicine_patients = $location_hours_group['location_telemed_patients']; // New patients, existing or both?
						$telemedicine_hours247 = $location_hours_group['location_telemed_24_7']; // typically 24/7?
						$telemedicine_hours = $location_hours_group['location_telemed_hours']; // telemedicine hours repeater
						$telemedicine_modified = $location_hours_group['location_telemed_modified_hours_query']; // Are there modified hours for telemedicine?
						$telemedicine_modified_reason = $location_hours_group['location_telemed_modified_hours_reason']; // Why are there modified hours for telemedicine?
						$telemedicine_modified_start = $location_hours_group['location_telemed_modified_hours_start_date']; // When do the modified telemedicine hours start?
						$telemedicine_modified_end = $location_hours_group['location_telemed_modified_hours_end']; // Do we know when the modified telemedicine hours end?
						$telemedicine_modified_end_date = $location_hours_group['location_telemed_modified_hours_end_date']; // When do the modified telemedicine hours end?
						$telemedicine_modified_hours247 = $location_hours_group['location_telemed_modified_hours_24_7'];
						// $telemedicine_modified_hours = $location_hours_group['location_telemed_modified_hours_group']; // modified telemedicine hours repeater
						$telemedicine_info = get_field('location_telemed_descr_system', 'option'); // System-wide information about telemedicine at locations

				if ( $telemedicine_query ) {

					$telemedicine_section_show = true;
					$jump_link_count++;

				} else {

					$telemedicine_section_show = false;

				}

			// Check if Portal Information section should be displayed

				$location_portal = get_field('location_portal');

				if ( $location_portal ) {
					$portal = get_term($location_portal, "portal");
					$portal_slug = $portal->slug;
					$portal_name = $portal->name;
					$portal_name_attr = uamswp_attr_conversion($portal_name);
					$portal_content = get_field('portal_content', $portal);
					$portal_link = get_field('portal_url', $portal);
					if ($portal_link) {
						$portal_url = $portal_link['url'];
						$portal_link_title = $portal_link['title'];
					} else {
						$portal_url = '';
						$portal_link_title = '';
					}
				}
				if ($portal && $portal_slug !== "_none") {
					$portal_section_show = true;
					$jump_link_count++;
				} else {
					$portal_section_show = false;
				}

			// Query for whether related providers content section should be displayed on ontology pages/subsections

				$providers = get_field('physician_locations');
				$jump_link_count = isset($jump_link_count) ? $jump_link_count : 0;
				$provider_query_vars = isset($provider_query_vars) ? $provider_query_vars : uamswp_fad_provider_query(
					$providers,
					$jump_link_count
				);
					$provider_query = $provider_query_vars['provider_query']; // WP_Post[]
					$provider_section_show = $provider_query_vars['provider_section_show']; // bool
					$provider_ids = $provider_query_vars['provider_ids']; // int[]
					$provider_count = $provider_query_vars['provider_count']; // int
					$jump_link_count = $provider_query_vars['jump_link_count']; // int

			// Query for whether related descendant locations content section should be displayed on a page

				$current_id = get_the_ID();
				$location_descendants = get_pages(
					array(
						'child_of' => $current_id,
						'post_type' => 'location'
					)
				);
				$location_descendant_query_vars = isset($location_descendant_query_vars) ? $location_descendant_query_vars : uamswp_fad_location_descendant_query(
					$current_id, // int
					$location_descendants, // int[]
					$jump_link_count // int
				);
					$location_descendant_query = $location_descendant_query_vars['location_descendant_query']; // WP_Post[]
					$location_descendant_section_show = $location_descendant_query_vars['location_descendant_section_show']; // bool
					$location_descendant_ids = $location_descendant_query_vars['location_descendant_ids']; // int[]
					$location_descendant_count = $location_descendant_query_vars['location_descendant_count']; // int
					$location_descendant_valid = $location_descendant_query_vars['location_descendant_valid']; // bool
					$jump_link_count = $location_descendant_query_vars['jump_link_count']; // int
				$location_query = $location_descendant_query;
				$location_section_show = $location_descendant_section_show;
				$location_ids = $location_descendant_ids;
				$location_count = $location_descendant_count;
				$location_valid = $location_descendant_valid;

			// Query for whether related areas of expertise content section should be displayed on a page

				$expertises = get_field('location_expertise');
				$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query(
					$expertises // int[]
				);
					$expertise_query = $expertise_query_vars['expertise_query']; // WP_Post[]
					$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool
					$expertise_ids = $expertise_query_vars['expertise_ids']; // int[]
					$expertise_count = $expertise_query_vars['expertise_count']; // int

			// Query for whether related clinical resources content section should be displayed on ontology pages/subsections

				$clinical_resources = get_field('location_clinical_resources');
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

				// $conditions = get_field('location_conditions');
				$conditions_cpt = get_field('location_conditions_cpt');
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

				$treatments_cpt = get_field('location_treatments_cpt');
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

		// Get remaining details about this item

			// After hours information

				$afterhours_system = get_field('location_afterhours_descr_system', 'option'); // System-wide information about telemedicine at locations
				$afterhours_system = ( isset($afterhours_system) && !empty($afterhours_system) ) ? $afterhours_system : '<p>If you are in need of urgent or emergency care, call 911 or go to your nearest emergency department at your local hospital.</p>'; // System-wide information about telemedicine at locations

			// Address and physical location information

				$map = get_field('location_map', $post_id );
				$location_address_1 = get_field('location_address_1', $post_id );
				$location_building = get_field('location_building', $post_id );
				if ($location_building) {
					$building = get_term($location_building, "building");
					$building_slug = $building->slug;
					$building_name = $building->name;
				}
				$location_floor = get_field_object('location_building_floor', $post_id );
					$location_floor_value = '';
					$location_floor_label = '';
					if ( $location_floor ) {
						$location_floor_value = $location_floor['value'];
						$location_floor_label = $location_floor['choices'][ $location_floor_value ];
					}
				$location_suite = get_field('location_suite', $post_id );
				$location_address_2 =
					( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? '<br />' : '' ) : '' )
					. ( $location_floor && !empty($location_floor_value) && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ', ' : '' ) : '' )
					. ( $location_suite ? $location_suite : '' );
				$location_address_2_schema =
					( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? ' ' : '' ) : '' )
					. ( $location_floor && !empty($location_floor_value) && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ' ' : '' ) : '' )
					. ( $location_suite ? $location_suite : '' );

				$location_address_2_deprecated = get_field('location_address_2', $post_id );
				if (!$location_address_2) {
					$location_address_2 = $location_address_2_deprecated;
					$location_address_2_schema = $location_address_2_deprecated;
				}

				$location_state = get_field('location_state', $post_id);
				$location_zip = get_field('location_zip', $post_id);

			// External website

				$location_external_web_name = get_field('location_web_name');
				$location_external_web_url = get_field('location_url');

		// Get remaining details content associated with this item

			// Do nothing

		// Classes for indicating presence of content

			// Do nothing

		// Remove standard post content

			// remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		// Construct page content

			while ( have_posts() ) {

				the_post();

				?>
				<div class="content-sidebar-wrap">
					<main class="location-item" id="genesis-content">
						<?php

						// Construct main info section

							?>
							<section class="container-fluid p-0 p-md-10 location-info bg-white">
								<div class="row mx-0 mx-md-n8">
									<div class="col-12 col-md text">
										<div class="content-width">
											<h1 class="page-title"><?php echo $page_title; ?>
											<?php if ($parent_location) { ?>
											<span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $parent_url; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
											<?php } // endif ?>
											</h1>
											<?php if ($location_closing_display) { ?>
												<div class="alert alert-warning" role="alert">
													<p><?php

													if ($location_closing_date_past) {

														?>This <?php echo strtolower($location_single_name); ?> is <?php echo $location_closing_length == 'temporary' ? 'temporarily' : 'permanently' ; ?> closed. <?php

													} else {

														?>This <?php echo strtolower($location_single_name); ?> will be closing <?php echo $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently' ; ?> on <?php echo $location_closing_date; ?>. <?php

													} // endif

													if (
														$location_closing_length == 'temporary' 
														&& $location_reopen_known == 'date' 
														&& !empty($location_reopen_date)
														&& (new DateTime($location_reopen_date) >= new DateTime($location_closing_date))
													) {

														?>It is scheduled to reopen on <?php echo $location_reopen_date; ?>. <?php

													} elseif (
														$location_closing_length == 'temporary' 
														&& $location_reopen_known == 'tbd' 
													) {

														?>It will remain closed until further notice. <?php

													} // endif

													if (!empty($location_closing_info)) {

														?><a href="#closing-info" class="alert-link no-break" aria-label="Learn more information about the closure of this <?php echo strtolower($location_single_name_attr); ?>">Learn more</a>. <?php

													} // endif

													?></p>
													<?php

													if ($location_closing_telemed) {

														?>
														<p>Telemedicine will still be available. <a href="#telemedicine-info" class="alert-link no-break" aria-label="Learn more information about telemedicine at this <?php echo strtolower($location_single_name_attr); ?>">Learn more</a>.</p>
														<?php

													}

													?>
												</div>
												<?php

											} // endif

											?>
											<h2 class="sr-only">Address</h2>
											<p><?php echo $location_address_1; ?><br/>
											<?php

											echo ( $location_address_2 ? $location_address_2 . '<br/>' : ( $location_address_2_deprecated ? $location_address_2_deprecated . '<br/>' : ''));
											echo $location_city . ', ' . $location_state . ' ' . $location_zip;

											?></p>
											<div class="btn-container">
												<div class="inner-container">
													<a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get directions to <?php echo $page_title_phrase; ?>" data-typetitle="Get directions to the clinic">Get Directions</a>
													<?php if ($parking_section_show) { ?>
														<a class="btn btn-outline-primary" href="#parking-info" aria-label="Parking instructions for <?php echo $page_title_phrase; ?>" data-typetitle="Parking instructions for the clinic">Parking Instructions</a>
													<?php } // endif $parking_section_show ?>

												</div>
											</div>
											<?php

											if (
												$location_external_web_name
												&&
												$location_external_web_url
											) {

												?>
												<p><a class="btn btn-secondary" href="<?php echo $location_external_web_url['url']; ?>" target="_blank" data-categorytitle="External Link"><?php echo $location_external_web_name; ?> <span class="far fa-external-link-alt"></span></span></a></p>
												<?php
											}

											// Address Schema Data

												// Check/define the main address schema array

													$schema_address = ( isset($schema_address) && is_array($schema_address) && !empty($schema_address) ) ? $schema_address : array(); // array

												// Add this location's details to the main address schema array

													$schema_address = uamswp_schema_address(
														$schema_address, // array (optional) // Main address schema array
														$location_address_1 . ' '. $location_address_2_schema, // string (optional) // The street address. For example, 1600 Amphitheatre Pkwy.
														'', // string (optional) // The post office box number for PO box addresses.
														$location_city, // string (optional) // The locality in which the street address is, and which is in the region. For example, Mountain View.
														$location_state, // string (optional) // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
														$location_zip, // string (optional) // The postal code. For example, 94043.
													);

											// Geo Schema Data

												// Check/define the main geo schema array

													$schema_geo = ( isset($schema_geo) && is_array($schema_geo) && !empty($schema_geo) ) ? $schema_geo : array();

												// Add this location's details to the main geo schema array

													$schema_geo = uamswp_schema_geo(
														$schema_geo, // array (optional) // Main geo schema array
														$map['lat'], // string (optional) // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
														$map['lng'], // string (optional) // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
													);

											?>
											<h2>Contact Information</h2>
											<?php

											// Phone values

												$phone_output_id = $page_id;
												$phone_output = 'location_profile';
												include( UAMS_FAD_PATH . '/templates/blocks/locations-phone.php' );

											// Hours values

												$hoursvary = $location_hours_group['location_hours_variable']; // Do the location's hours vary? // bool
												$hoursvary_info = $location_hours_group['location_hours_variable_info']; // Variable Hours Information // string (WYSIWYG)
												$hours247 = $location_hours_group['location_24_7']; // Is the location typically available 24/7? // bool
												$modified = $location_hours_group['location_modified_hours']; // Upcoming Modified Hours // bool
												$modified_reason = $location_hours_group['location_modified_hours_reason']; // Reason for Modified Hours // string (WYSIWYG)
												$modified_start = $location_hours_group['location_modified_hours_start_date']; // Modified Hours Start Date // string (F j, Y)
												$modified_end = $location_hours_group['location_modified_hours_end']; // Is there a Modified Hours End Date? // bool
												$modified_end_date = $location_hours_group['location_modified_hours_end_date']; // Modified Hours End Date // string (F j, Y)
												$modified_hours = $location_hours_group['location_modified_hours_group']; // Modified Hours // repeater
												$modified_text = '';
												$active_start = '';
												$active_end = '';

												if ( $hoursvary ) {

													// If the location's hours vary...

													echo '<h2>Hours Vary</h2>';
													echo $hoursvary_info;

												} else {

													// If the location's hours do not vary...

													// Begin Modified Hours Logic

														if ( $modified ) {

															// If there are upcoming modified hours...

															$modified_day = ''; // Previous Day
															$modified_comment = ''; // Comment on previous day
															$i = 1;

															$today = strtotime("today");
															$today_30 = strtotime("+30 days");

															// OpeningHoursSpecification Schema Data

																// Check/define schema data variables

																	$schema_opening_hours_specification = ( isset($schema_opening_hours_specification) && is_array($schema_opening_hours_specification) && !empty($schema_opening_hours_specification) ) ? $schema_opening_hours_specification : array(); // Main OpeningHoursSpecification schema array
																	$schema_day_of_week = array(); // The day of the week for which these opening hours are valid.
																	$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																	$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																	$schema_valid_from = date( "Y-m-d", strtotime($modified_start) ); // The date when the item becomes valid.
																	$schema_valid_through = ( $modified_end && $modified_end_date) ? date( "Y-m-d", strtotime($modified_end_date) ) : ''; // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.

															// Display the modified hours if they have started or if they start within 30 days
															if (
																strtotime($modified_start) <= $today_30 // If the modified hours start date is less or equal to 30 days in the future...
																&&
																(
																	strtotime($modified_end_date) >= $today // If the modified hours end date is greater than or equal to today
																	||
																	!$modified_end // If there is no end date for the modified hours
																)
															) {

																$modified_text .= $modified_reason;
																$modified_text .= '<p class="small font-italic">These modified hours start on ' . $modified_start . ', ';
																$modified_text .= $modified_end && $modified_end_date ? 'and are scheduled to end after ' . $modified_end_date . '.' : 'and will remain in effect until further notice.';
																$modified_text .= '</p>';

																if ( $modified_hours ) {

																	// If the Modified Hours repeater has at least one row...

																	// Loop through the Modified Hours repeater rows
																	foreach ( $modified_hours as $modified_hour ) {

																		// Get data from fields in the Modified Hours repeater

																			$modified_title = $modified_hour['location_modified_hours_title']; // Title (in Modified Hours repeater; in Modified Hours tab) // string
																			$modified_info = $modified_hour['location_modified_hours_information']; // Information (in Modified Hours repeater; in Modified Hours tab) // string (wysiwyg)
																			$modified_times = $modified_hour['location_modified_hours_times']; // Hours (in Modified Hours repeater; in Modified Hours tab) // repeater
																			$modified_hours247 = $modified_hour['location_modified_hours_24_7']; // Is this location available 24/7 during these modified hours? (in Modified Hours repeater; in Modified Hours tab) // bool

																		$modified_text .= $modified_title ? '<h3 class="h4">'.$modified_title.'</h3>' : '';
																		$modified_text .= $modified_info ? $modified_info : '';

																		// OpeningHoursSpecification Schema Data

																			// Reset schema data variables

																				$schema_day_of_week = array(); // The day of the week for which these opening hours are valid.
																				$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																				$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																		// Get the earliest (most past) modified hours start date from all the rows in the Modified hours repeater
																		if (
																			$active_start > strtotime($modified_start) // If previous loop's modified hours start date is greater than the current loop's modified hours start date
																			||
																			'' == $active_start // Or if there is no modified hours start date from a previous loop
																		) {

																			$active_start = strtotime($modified_start); // Store the modified hours start date for comparison in future loops

																		} // endif ( $active_start > strtotime($modified_start) || '' == $active_start )

																		// Get the latest (most future) modified hours end date from all the rows in the Modified hours repeater
																		if (
																			$active_end <= strtotime($modified_end_date) // If previous loop's modified hours end date is less than or equal to the current loop's modified hours end date
																			||
																			'' == $active_start // Or if there is no modified hours end date from a previous loop
																			||
																			!$modified_end // Or if the current loop has no modified hours end date
																		) {

																			if ( !$modified_end ) {

																				// If the current loop has no modified hours end date...

																				$active_end = 'TBD';

																			} else {

																				// Else if the current loop has a modified hours end date...

																				$active_end = strtotime($modified_end_date);

																			} // endif ( !$modified_end ) else

																		} // endif ( $active_end <= strtotime($modified_end_date) || !$modified_end )

																		if ( $modified_hours247 ) {

																			// If the modified hours are 24/7...

																			$modified_text .= '<strong>Open 24/7</strong>';

																			// OpeningHoursSpecification Schema Data for Modified Hours That Are 24/7

																				$schema_day_of_week = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ); // The day of the week for which these opening hours are valid.
																				$schema_opens = '00:00'; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																				$schema_closes = '23:59'; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																				// Add this location's details to the main OpeningHoursSpecification schema array

																					// // Schema.org method: Add all days as an array under the dayOfWeek property
																					// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)

																					// 	$schema_opening_hours_specification = uamswp_schema_opening_hours_specification(
																					// 		$schema_opening_hours_specification, // array (optional) // Main OpeningHoursSpecification schema array
																					// 		$schema_day_of_week, // array|string (optional) // The day of the week for which these opening hours are valid.
																					// 		$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																					// 		$schema_closes, // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																					// 		$schema_valid_from, // string (optional) // The date when the item becomes valid.
																					// 		$schema_valid_through // string (optional) // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
																					// 	);

																					// Google method: Loop through all the days defined in the current Hours repeater row separately
																					// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

																						foreach ( $schema_day_of_week as $day) {
																							$schema_opening_hours_specification = uamswp_schema_opening_hours_specification(
																								$schema_opening_hours_specification, // array (optional) // Main OpeningHoursSpecification schema array
																								$day, // array|string (optional) // The day of the week for which these opening hours are valid.
																								$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																								$schema_closes, // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																								$schema_valid_from, // string (optional) // The date when the item becomes valid.
																								$schema_valid_through // string (optional) // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
																							);
																						}

																		} else {

																			// If the modified hours are not 24/7...

																			if (
																				is_array($modified_times)
																				||
																				is_object($modified_times)
																			) {

																				$modified_text .= '<dl class="hours">';

																				// OpeningHoursSpecification Schema Data for Modified Hours That Are Not 24/7

																				// Loop through all the Hours repeater rows (in Modified Hours repeater; in Modified Hours tab)
																				foreach ( $modified_times as $modified_time ) {

																					$modified_text .= $modified_day !== $modified_time['location_modified_hours_day'] ? '<dt>'. $modified_time['location_modified_hours_day'] .'</dt> ' : '';
																					$modified_text .= '<dd>';

																					// OpeningHoursSpecification Schema Data

																						// Reset/define variables

																							$schema_day_of_week = array();

																					if (
																						'Mon - Fri' == $modified_time['location_modified_hours_day']
																						&&
																						!$modified_time['location_modified_hours_closed']
																					) {

																						// OpeningHoursSpecification Schema Data

																							$schema_day_of_week = array_merge( $schema_day_of_week, array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ) ); // The day of the week for which these opening hours are valid.

																					} else {

																						// OpeningHoursSpecification Schema Data

																							$schema_day_of_week[] = $modified_time['location_modified_hours_day']; // The day of the week for which these opening hours are valid.

																					} // endif ( 'Mon - Fri' == $modified_time['location_modified_hours_day'] && !$modified_time['location_modified_hours_closed'] ) else

																					if ( $modified_time['location_modified_hours_closed'] ) {

																						// OpeningHoursSpecification Schema Data

																							$schema_opens = '00:00'; // string // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																							$schema_closes = '00:00'; // string // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																						$modified_text .= 'Closed ';

																					} else {

																						$modified_text .= ( ( $modified_time['location_modified_hours_open'] && '00:00:00' != $modified_time['location_modified_hours_open'] ) ? '' . ap_time_span( strtotime($modified_time['location_modified_hours_open']), strtotime($modified_time['location_modified_hours_close']) ). '' : '' );

																						$schema_opens = date('H:i', strtotime($modified_time['location_modified_hours_open'])); // string // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																						$schema_closes = date('H:i', strtotime($modified_time['location_modified_hours_close'])); // string // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																					} // endif ( $modified_time['location_modified_hours_closed'] ) else

																					if ( $modified_time['location_modified_hours_comment'] ) {

																						$modified_text .= ' <br /><span class="subtitle">' .$modified_time['location_modified_hours_comment'] . '</span>';
																						$modified_comment = $modified_time['location_modified_hours_comment'];

																					} else {

																						$modified_comment = '';

																					} // endif ( $modified_time['location_modified_hours_comment'] ) else

																					$modified_text .= '</dd>';
																					$modified_day = $modified_time['location_modified_hours_day']; // Reset the day

																					// OpeningHoursSpecification Schema Data for Modified Hours That Are Not 24/7

																						// Add this location's details to the main OpeningHoursSpecification schema array

																							// // Schema.org method: Add all days as an array under the dayOfWeek property
																							// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)

																							// 	$schema_opening_hours_specification = uamswp_schema_opening_hours_specification(
																							// 		$schema_opening_hours_specification, // array (optional) // Main OpeningHoursSpecification schema array
																							// 		$schema_day_of_week, // array|string (optional) // The day of the week for which these opening hours are valid.
																							// 		$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																							// 		$schema_closes, // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																							// 		$schema_valid_from, // string (optional) // The date when the item becomes valid.
																							// 		$schema_valid_through // string (optional) // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
																							// 	);

																							// Google method: Loop through all the days defined in the current Hours repeater row separately
																							// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

																								foreach ( $schema_day_of_week as $day) {
																									$schema_opening_hours_specification = uamswp_schema_opening_hours_specification(
																										$schema_opening_hours_specification, // array (optional) // Main OpeningHoursSpecification schema array
																										$day, // array|string (optional) // The day of the week for which these opening hours are valid.
																										$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																										$schema_closes, // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																										$schema_valid_from, // string (optional) // The date when the item becomes valid.
																										$schema_valid_through // string (optional) // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
																									);
																								}

																					$i++;

																				} // endforeach ( $modified_times as $modified_time )

																				$modified_text .= '</dl>';

																			} // endif ( is_array($modified_times) || is_object($modified_times) )

																		} // endif ( $modified_hours247 ) else
																	} // endforeach ( $modified_hours as $modified_hour )

																} // endif ( $modified_hours )

															} // endif ( strtotime($modified_start) <= $today_30 && ( strtotime($modified_end_date) >= $today || !$modified_end ) )

															echo $modified_text ? '<h2>Modified Hours</h2>' . $modified_text: '';

														} // endif ( $modified )

													// End Modified Hours Logic

													// Begin Typical Hours Logic

														if (
															(
																$active_start != '' // If there is a modified hours start date
																&&
																$active_start <= $today // And if that modified hours start date is today or earlier
															)
															&&
															(
																$active_end > $today_30 // If the modified hours end date is after 30 days in the future
																||
																$active_end == 'TBD' // Or if there is no modified hours end date
															)
														) {

															// If the modified hours are the current hours for at least the next 30 days...
															// Do not display the typical hours

														} else {

															// If the modified hours end within 30 days or if they haven't started yet...
															// Display the typical hours

															$hours = $location_hours_group['location_hours']; // Typical Hours // repeater

															// Schema Data

																// // Schema.org method: OpeningHours Schema Data
																// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)

																// 	// Check/define/reset schema data variables

																// 		$schema_opening_hours = ( isset($schema_opening_hours) && is_array($schema_opening_hours) && !empty($schema_opening_hours) ) ? $schema_opening_hours : array(); // Main OpeningHours schema array
																// 		$schema_day_of_week = ''; // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
																// 		$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																// 		$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																// Google method: OpeningHoursSpecification Schema Data
																// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

																	// Check/define/reset schema data variables

																		$schema_opening_hours_specification = ( isset($schema_opening_hours_specification) && is_array($schema_opening_hours_specification) && !empty($schema_opening_hours_specification) ) ? $schema_opening_hours_specification : array(); // Main OpeningHoursSpecification schema array
																		$schema_day_of_week = ''; // The day of the week for which these opening hours are valid. // Days are specified using their full name (e.g., Sunday)
																		$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																		$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

															if (
																$hours247 // The location is typically available 24/7
																||
																$hours[0]['day'] // Typical daily hours have been set
															) {

																// If the location is typically available 24/7
																// or if typical daily hours have been set...

																?>
																<h2><?php echo $modified_text ? 'Typical ' : ''; ?>Hours</h2>
																<?php

																if ( $hours247 ) {

																	// If the location is typically available 24/7...

																	echo '<strong>Open 24/7</strong>';

																	// Schema Data

																		// // Schema.org method: OpeningHours Schema Data
																		// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)

																		// 	// Define schema data variables

																		// 		$schema_day_of_week = 'Mo-Su'; // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
																		// 		$schema_opens = '00:00'; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																		// 		$schema_closes = '23:59'; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																		// 	// Add this location's details to the main OpeningHours schema array

																		// 		$schema_opening_hours = uamswp_schema_opening_hours(
																		// 			$schema_opening_hours, // array (optional) // Main OpeningHours schema array
																		// 			$schema_day_of_week, // string (optional) // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
																		// 			$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																		// 			$schema_closes // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																		// 		);

																		// Google method: OpeningHoursSpecification Schema Data
																		// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

																			// Define schema data variables

																				$schema_day_of_week = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ); // The day of the week for which these opening hours are valid.
																				$schema_opens = '00:00'; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																				$schema_closes = '23:59'; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																			// Loop through all the days in the array separately

																				foreach ( $schema_day_of_week as $day) {
																					$schema_opening_hours_specification = uamswp_schema_opening_hours_specification(
																						$schema_opening_hours_specification, // array (optional) // Main OpeningHoursSpecification schema array
																						$day, // array|string (optional) // The day of the week for which these opening hours are valid.
																						$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																						$schema_closes // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																					);
																				}

																} else {

																	// If typical daily hours have been set...

																	echo '<dl class="hours">';

																	if ( $hours ) {

																		// If the Typical Hours repeater has at least one row...

																		$hours_text = ''; // Definition term and definition description tag set
																		$day = ''; // Previous Day
																		$comment = ''; // Comment on previous day
																		$i = 1;

																		// Loop through the Typical Hours repeater
																		foreach ( $hours as $hour ) {

																			// OpeningHours Schema Data for Typical Hours That Are 24/7

																			// Schema Data

																				// // Schema.org method: OpeningHours Schema Data
																				// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)

																				// 	// Define/reset schema data variables

																				// 		$schema_day_of_week = ''; // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
																				// 		$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																				// 		$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																				// Google method: OpeningHoursSpecification Schema Data
																				// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

																					// Define/reset schema data variables

																					$schema_day_of_week = array(); // The day of the week for which these opening hours are valid.
																					$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																					$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																			if ( $day !== $hour['day'] ) {

																				// If the current repeater row's day does not match the previous repeater row's day...

																				// Definition term and definition description tag set
																				// Write a new definition term element with the current repeater row's day
																				$hours_text .= '<dt>'. $hour['day'] .'</dt> ';

																			}

																			// Definition term and definition description tag set
																			// Open a definition description tag
																			$hours_text .= '<dd>';

																			// Schema Data

																				if (
																					'Mon - Fri' == $hour['day'] // The current repeater row's day is set as 'Mon - Fri'
																					&&
																					$hour['closed'] // And the current repeater row is marked as closed
																				) {

																					// // Schema.org method: OpeningHours Schema Data
																					// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)

																					// 	// Do nothing

																					// Google method: OpeningHoursSpecification Schema Data
																					// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

																						// Define schema data variables

																							$schema_day_of_week = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ); // The day of the week for which these opening hours are valid.
																							$schema_opens = '00:00'; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																							$schema_closes = '00:00'; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																				} elseif (
																					'Mon - Fri' == $hour['day'] // The current repeater row's day is set as 'Mon - Fri'
																					// And the current repeater row is not marked as closed
																				) {

																					// // Schema.org method: OpeningHours Schema Data
																					// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)

																					// 	// Define schema data variables

																					// 		$schema_day_of_week = 'Mo-Fr'; // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)

																					// Google method: OpeningHoursSpecification Schema Data
																					// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

																						// Define schema data variables

																							$schema_day_of_week = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ); // The day of the week for which these opening hours are valid.
																							$schema_opens = date('H:i', strtotime($hour['open'])); // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																							$schema_closes = date('H:i', strtotime($hour['close'])); // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																				} elseif (
																					// The current repeater row's day is not set as 'Mon - Fri'
																					$hour['closed'] // And the current repeater row is marked as closed
																				) {

																					// // Schema.org method: OpeningHours Schema Data
																					// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)

																					// 	// Do nothing

																					// Google method: OpeningHoursSpecification Schema Data
																					// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

																						// Define schema data variables

																							$schema_day_of_week[] = $hour['day']; // The day of the week for which these opening hours are valid.
																							$schema_opens = '00:00'; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																							$schema_closes = '00:00'; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																				} else {

																					// If the current repeater row's day is not set as 'Mon - Fri'
																					// And the current repeater row is not marked as closed

																					// // Schema.org method: OpeningHours Schema Data
																					// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)

																					// 	// Define schema data variables

																					// 		$schema_day_of_week = substr( $hour['day'], 0, 2 ); // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
																					// 		$schema_opens = date('H:i', strtotime($hour['open'])); // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																					// 		$schema_closes = date('H:i', strtotime($hour['close'])); // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																					// Google method: OpeningHoursSpecification Schema Data
																					// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

																						// Define schema data variables

																							$schema_day_of_week[] = $hour['day']; // The day of the week for which these opening hours are valid.
																							$schema_opens = date('H:i', strtotime($hour['open'])); // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																							$schema_closes = date('H:i', strtotime($hour['close'])); // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

																				}

																				// // Schema.org method: OpeningHours Schema Data
																				// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)

																				// 	// Add this location's details to the main OpeningHours schema array

																				// 		$schema_opening_hours = uamswp_schema_opening_hours(
																				// 			$schema_opening_hours, // array (optional) // Main OpeningHours schema array
																				// 			$schema_day_of_week, // string (optional) // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
																				// 			$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																				// 			$schema_closes // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																				// 		);

																				// Google method: OpeningHoursSpecification Schema Data
																				// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

																					// Loop through all the days in the array separately

																						foreach ( $schema_day_of_week as $day) {
																							$schema_opening_hours_specification = uamswp_schema_opening_hours_specification(
																								$schema_opening_hours_specification, // array (optional) // Main OpeningHoursSpecification schema array
																								$day, // array|string (optional) // The day of the week for which these opening hours are valid.
																								$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																								$schema_closes // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																							);
																						}

																			// Set the text for the day or time span (closed or hours open)

																				if ( $hour['closed'] ) {

																					// If the location is closed on this day or time span...

																					// Definition term and definition description tag set
																					// Set the text for the day or time span (closed)
																					$hours_text .= 'Closed ';

																				} else {

																					// Else if the location is open on this day or time span...

																					// Definition term and definition description tag set
																					// Set the text for the day or time span (hours open)
																					$hours_text .= ( ( $hour['open'] && '00:00:00' != $hour['open'] ) ? '' . ap_time_span( strtotime($hour['open']), strtotime($hour['close']) ) . '' : '' );

																				}

																			// Set the comment for the day or time span

																				if ( $hour['comment'] ) {

																					// If a comment exists for this day or time span...

																					// Definition term and definition description tag set
																					$hours_text .= ' <br /><span class="subtitle">' .$hour['comment'] . '</span>';

																					// Store comment for comparison on next repeater row
																					$comment = $hour['comment'];

																				} else {

																					// Else if no comment exists for this day or time span...

																					$comment = '';

																				} // if ( $hour['comment'] ) else

																			// Definition term and definition description tag set
																			// Close the definition description tag
																			$hours_text .= '</dd>';

																			// Store day for comparison on next repeater row
																			$day = $hour['day'];

																		} // endforeach ( $hours as $hour )

																		// Definition term and definition description tag set
																		echo $hours_text;

																	} else {

																		// Write a definition term tag
																		echo '<dt>No information</dt>';

																	} // endif ( $hours ) else

																	// Close the definition list tag
																	echo '</dl>';

																} // endif ( $hours247 ) else

																// $holidayhours = get_field('location_holiday_hours'); // Holiday Hours // repeater

																// if ( $holidayhours ) {

																// 	// If the Holiday Hours repeater has at least one row

																// 	/**
																// 	 * Sort by date
																// 	 * if current date is before date & within 30 days
																// 	 * Display results
																// 	 */

																// 	$order = array();

																// 	// populate order
																// 	foreach ( $holidayhours as $i => $row ) {

																// 		$order[ $i ] = $row['date'];

																// 	} // endforeach ( $holidayhours as $i => $row )

																// 	// multisort
																// 	array_multisort( $order, SORT_ASC, $holidayhours );

																// 	$i = 0;

																// 	foreach ( $holidayhours as $row ) {

																// 		$holidayDate = $row['date']; // Text
																// 		$holidayDateTime = DateTime::createFromFormat('m/d/Y', $holidayDate); // Date for evaluation
																// 		$dateNow = new DateTime("now", new DateTimeZone('America/Chicago') );

																// 		if ( ( $dateNow < $holidayDateTime ) && ( $holidayDateTime->diff($dateNow)->days < 30 ) ) {

																// 			if ( 0 == $i ) {
																// 				echo '<h3>Upcoming Holiday Hours</h3>';
																// 				echo '<dl class="hours">';
																// 				$i++;
																// 			}

																// 			echo '<dt>'. $row['label'] . '<br />' . $holidayDate . '<br/>';
																// 			echo '</dt>' . '<dd>';

																// 			if ( $row['closed'] ) {

																// 				echo $row['closed'] ? 'Closed</dd>': '';

																// 			} else {

																// 				echo ( ( $hour['open'] && '00:00:00' != $row['open'] ) ? '' . ap_time_span( strtotime($row['open']), strtotime($row['close']) ) . ' ' : '' );

																// 			}
																// 		}

																// 	} // endforeach ( $holidayhours as $row )

																// 	if ( 0 < $i ) {

																// 		echo '</dl>';

																// 	} // endif ( 0 < $i )

																// } // endif ( $holidayhours )

															} // endif ( $hours247 || $hours[0]['day'] )

														} // endif ( ( $active_start != '' && $active_start <= $today) && ( $active_end > $today_30 || $active_end == 'TBD' ) ) else

													// End Typical Hours Logic

												} // endif ( $hoursvary )

												if (
													$location_hours_group['location_after_hours']
													&&
													!$location_hours_group['location_24_7']
												) {

													?>
													<h2>After Hours</h2>
													<?php

													echo $location_hours_group['location_after_hours'];

												} elseif ( !$location_hours_group['location_24_7'] ) {

													?>
													<h2>After Hours</h2>
													<?php

													echo $afterhours_system;

												} // endif ($location_hours_group['location_after_hours'] && !$location_hours_group['location_24_7']) elseif ( !$location_hours_group['location_24_7'] )

											?>
										</div>
									</div>
									<?php

									if ( $location_images_count ) {

										?>
										<div class="col-12 col-md image">
											<div class="content-width">
												<?php if ( $location_images_count == 1 ) { ?>
													<picture>
														<?php if ( function_exists( 'fly_add_image_size' ) && !empty($location_images[0]) ) { ?>
															<source srcset="<?php echo image_sizer($location_images[0], 630, 473, 'center', 'center'); ?>"
																media="(min-width: 1350px)">
															<source srcset="<?php echo image_sizer($location_images[0], 572, 429, 'center', 'center'); ?>"
																media="(min-width: 992px)">
															<source srcset="<?php echo image_sizer($location_images[0], 992, 558, 'center', 'center'); ?>"
																media="(min-width: 768px)">
															<source srcset="<?php echo image_sizer($location_images[0], 768, 432, 'center', 'center'); ?>"
																media="(min-width: 576px)">
															<source srcset="<?php echo image_sizer($location_images[0], 576, 324, 'center', 'center'); ?>"
																media="(min-width: 1px)">
															<img src="<?php echo image_sizer($location_images[0], 630, 473, 'center', 'center'); ?>" alt="<?php echo get_post_meta( $location_images[0], '_wp_attachment_image_alt', true ); ?>" class="single-image" />
														<?php } else { ?>
															<img src="<?php echo wp_get_attachment_image_url($location_images[0], 'large'); ?>" class="single-image">
														<?php } //endif ?>
													</picture>
												<?php } else { ?>
													<div class="carousel slide carousel-thumbnails" id="location-info-carousel" data-interval="false" data-ride="carousel">
														<div class="carousel-inner">
															<?php 

															$location_carousel_slide = 1;

															foreach( $location_images as $location_images_item ) {

																?>
																<div class="carousel-item<?php echo ($location_carousel_slide == 1) ? ' active' : '' ?>">
																	<picture>
																		<?php

																		if ( function_exists( 'fly_add_image_size' ) ) {

																			?>
																			<source srcset="<?php echo image_sizer($location_images_item, 630, 473, 'center', 'center'); ?>"
																				media="(min-width: 1350px)">
																			<source srcset="<?php echo image_sizer($location_images_item, 572, 429, 'center', 'center'); ?>"
																				media="(min-width: 992px)">
																			<source srcset="<?php echo image_sizer($location_images_item, 992, 558, 'center', 'center'); ?>"
																				media="(min-width: 768px)">
																			<source srcset="<?php echo image_sizer($location_images_item, 768, 432, 'center', 'center'); ?>"
																				media="(min-width: 576px)">
																			<source srcset="<?php echo image_sizer($location_images_item, 576, 324, 'center', 'center'); ?>"
																				media="(min-width: 1px)">
																			<img src="<?php echo image_sizer($location_images_item, 630, 473, 'center', 'center'); ?>" alt="<?php echo get_post_meta( $location_images_item, '_wp_attachment_image_alt', true ); ?>" />
																			<?php
																		} else {

																			?>
																			<img src="<?php echo wp_get_attachment_image_url($location_images_item, 'large'); ?>">
																			<?php

																		} //endif

																		?>
																	</picture>
																</div>
																<?php

																$location_carousel_slide++;

															} // endforeach

															?>
														</div>
														<a class="carousel-control-prev" href="#location-info-carousel" role="button" data-slide="prev">
															<span class="carousel-control-prev-icon" aria-hidden="true"></span>
															<span class="sr-only">Previous</span>
														</a>
														<a class="carousel-control-next" href="#location-info-carousel" role="button" data-slide="next">
															<span class="carousel-control-next-icon" aria-hidden="true"></span>
															<span class="sr-only">Next</span>
														</a>
														<ol class="carousel-indicators">
															<?php

															for ($i = 0; $i < $location_images_count; $i++) {

																?>
																<li data-target="#location-info-carousel" data-slide-to="<?php echo $i; ?>" <?php echo (0 == $i ? 'class="active"' : ''); ?>>
																	<?php if ( function_exists( 'fly_add_image_size' )) { ?>
																		<img src="<?php echo image_sizer($location_images[$i], 60, 45, 'center', 'center'); ?>" alt="<?php echo get_post_meta( $location_images[$i], '_wp_attachment_image_alt', true ); ?>" />
																	<?php } else { ?>
																		<img src="<?php echo wp_get_attachment_image_url($location_images[$i], 'small'); ?>" alt="<?php echo get_post_meta( $location_images[$i], '_wp_attachment_image_alt', true ); ?>">
																	<?php } //endif ?>
																</li>
																<?php

															}

															?>
														</ol>
													</div>
													<?php

												} //endif

												?>
											</div>
										</div>
										<?php

									} // endif ( $location_images_count )

									?>
								</div>
							</section>
							<?php

						// Construct Jump Links Section

							include( UAMS_FAD_PATH . '/templates/parts/jump-links.php' );

						// Construct Location Alert Section

							if ( $location_alert_section_show ) {

								?>
								<section class="uams-module location-alert location-<?php echo $location_alert_color ? $location_alert_color : 'alert-warning'; ?>" id="location-alert">
									<div class="container-fluid">
										<div class="row">
											<div class="col-xs-12">
												<h2 class="module-title<?php echo empty($location_alert_title) ? ' sr-only' : ''; ?>"><?php echo $location_alert_title ? $location_alert_title : 'Alert'; ?></h2>
												<?php echo $location_alert_text ? '<div class="module-body"><p>' . $location_alert_text . '</p></div>' : ''; ?>
											</div>
										</div>
									</div>
								</section>
								<?php

							} // endif

						// Construct Closing Information Section

							if ( $closing_section_show ) {

								?>
								<section class="uams-module location-alert location-alert-warning" id="closing-info">
									<div class="container-fluid">
										<div class="row">
											<div class="col-xs-12">
												<h2 class="module-title"><span class="title">Closing Information</span></h2>
												<div class="module-body">
													<?php echo $location_closing_info; ?>
												</div>
											</div>
										</div>
									</div>
								</section>
								<?php

							} // endif

						// Construct About Section

							if ( $location_about_section_show ) {

								?>
								<section class="uams-module bg-auto" id="description">
									<div class="container-fluid">
										<div class="row">
											<div class="col-xs-12">
												<h2 class="module-title"><span class="title"><?php echo $location_about_section_title; ?></span></h2>
												<div class="module-body">
													<?php

													echo $location_about_section_show ? $location_about : '';

													if ( $location_youtube_link ) {

														if ( function_exists('lyte_preparse') ) {

															echo '<div class="alignwide">';
															echo lyte_parse( str_replace( ['https:', 'http:'], 'httpv:', $location_youtube_link ) );
															echo '</div>';

														} else {

															echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
															echo wp_oembed_get( $location_youtube_link );
															echo '</div>';

														}
													}

													if ( $location_affiliation_section_show ) { 

														if (
															$location_about_section_show
															||
															$prescription_section_show
														) { 

															echo '<h3 id="affiliation">Affiliation</h3>';

														}

														echo $location_affiliation;

													}

													if ( $prescription_section_show ) { 

														if (
															$location_about_section_show
															||
															$location_affiliation_section_show
														) { 

															echo '<h3 id="prescription-info">Prescription Information</h3>';

														}

														echo $prescription;

													}

													?>
												</div>
											</div>
										</div>
									</div>
								</section>
								<?php

							} // endif

						// Construct Parking and Directions Section

							if ( $parking_section_show ) {

								?>
								<section class="uams-module bg-auto" id="parking-info">
									<div class="container-fluid">
										<div class="row">
											<div class="col-xs-12<?php echo $parking_map ? ' col-md-6' : '' ?>">
												<?php if ($parking_map) { ?>
													<div class="module-body">
													<h2><?php echo ( $location_parking ? 'Parking Information' : 'Directions From the Parking Area'); // Display parking heading if parking has value. Otherwise, display directions heading. ?></h2>
												<?php } else { ?>
													<h2 class="module-title"><span class="title"><?php echo ( $location_parking ? 'Parking Information' : 'Directions From the Parking Area'); // Display parking heading if parking has value. Otherwise, display directions heading. ?></span></h2>
													<div class="module-body">
												<?php } // endif ?>
													<?php echo $location_parking; ?>
													<?php if ( $parking_map ) { ?>
														<a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $parking_map['lat'] ?>,<?php echo $parking_map['lng'] ?>" target="_blank" aria-label="Get directions to the parking area" data-typetitle="Get directions to the parking area">Get Directions</a>
													<?php } // endif ?>
													<?php echo ( $location_parking && $location_direction ? '<h3>Directions From the Parking Area</h3>' : ''); // Display the directions heading here if there is a value for parking. ?>
													<?php echo $location_direction; ?>
												</div>
											</div>
											<?php if ( $parking_map ) { ?>
												<div class="col-xs-12 col-md-6 parking-map-container">
													<div class="embed-responsive embed-responsive-16by9" id="map"></div>
													<script type='text/javascript'>
														/*-- Function to create encode SVG --*/
														/* colors need to be hex code without # */
														// createSVGIcon("9d2235", "222", "whitetext", "1");
														var createSVGIcon = function(fillColor,strokeColor,labelClass,labelText) {
															var svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 27.77" aria-labelledby="pinTitle" role="img"><title id="mapTitle">Basic Map Pin</title><path d="M9.5,26.26l.57-.65c.29-.4,7.93-9.54,7.93-15.67A8.75,8.75,0,0,0,9.5,1,8.75,8.75,0,0,0,1,9.94c0,6,7.54,15.27,7.93,15.67l.57.65Z" fill="#'+ fillColor +'" stroke="#'+ strokeColor +'" stroke-miterlimit="10" stroke-width="1"/></svg>';
															var encoded = window.btoa(svg);
															var backgroundImage = "background-image: url(data:image/svg+xml;base64,"+encoded+")";
															return '<div style="'+ backgroundImage +'" class="'+ labelClass +'">'+ labelText +'</div>';
														}
														/* Function to create divIcon for leaflet map */
														// createLabelIcon("leaflet-icon","A");
														var createLabelIcon = function(labelClass,labelText){
															return L.divIcon({
																className: labelClass,
																html: labelText,
																iconSize: new L.Point(28, 41),
																iconAnchor: new L.Point(14, 43),
																popupAnchor: [0, -43]
															})
														}
														var map = new L.Map('map', {center: new L.LatLng(<?php echo $parking_map['lat']; ?>, <?php echo $parking_map['lng'] ?>), zoom: 16 });
														map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.
														// for all possible values and explanations see "Template Parameters" in https://msdn.microsoft.com/en-us/library/ff701716.aspx
														var imagerySet = "Road"; // AerialWithLabels | Birdseye | BirdseyeWithLabels | Road
														var bing = new L.BingLayer("AnCRy0VpPMDzYT6rOBqqqCNvNbUWvdSOv8zrQloRCGFnJZU28JK3c6cQkCMAHtCd", {type: imagerySet});
														map.addLayer(bing);
														/* [lat, lon, fillColor, strokeColor, labelClass, iconText, popupText] */
														var markers = [
															// example [ 34.74376029995541, -92.31828863640054, "00F","000","white","A","I am a blue icon." ],
															[ <?php echo $map['lat']; ?>, <?php echo $map['lng'] ?>, "9d2235","222", "transparentwhite", '1', 'Clinic<br/><a href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get directions to <?php echo $page_title_phrase; ?>" data-typetitle="Get directions to the clinic">Get Directions</a>' ],
															[ <?php echo $parking_map['lat']; ?>, <?php echo $parking_map['lng'] ?>, "9d2235","222", "transparentwhite", '2', 'Parking<br/><a href="https://www.google.com/maps/dir/Current+Location/<?php echo $parking_map['lat'] ?>,<?php echo $parking_map['lng'] ?>" target="_blank" aria-label="Get directions to the parking area" data-typetitle="Get directions to the parking area">Get Directions</a>' ]
														]
														//Loop through the markers array
														var markerArray = [];
														for (var i=0; i<markers.length; i++) {
															var lat = markers[i][0];
															var lon = markers[i][1];
															var fillColor = markers[i][2];
															var strokeColor = markers[i][3];
															var labelClass = markers[i][4];
															var iconText = markers[i][5];
															var popupText = markers[i][6];
															var markerLocation = new L.LatLng(lat, lon);
															marker = new L.marker([lat, lon], { icon: createLabelIcon("leaflet-icon", createSVGIcon(fillColor,strokeColor,labelClass,iconText))});
															if (popupText)
																marker.bindPopup(popupText, { maxWidth: '240' });
															marker.addTo(map);
															markerArray.push(markerLocation);
														}
														group = new L.LatLngBounds(markerArray);
														if (markers.length > 1){
															map.fitBounds(group, {padding: [100, 75]});
														}
													</script>
													<div class="map-legend bg-info" aria-label="Legend for map">
														<ol data-categorytitle="Directions">
															<li>Clinic (<a href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get directions to <?php echo $page_title_phrase; ?>" data-typetitle="Get directions to the clinic">Get Directions</a>)</li>
															<li>Parking (<a href="https://www.google.com/maps/dir/Current+Location/<?php echo $parking_map['lat'] ?>,<?php echo $parking_map['lng'] ?>" target="_blank" aria-label="Get directions to the parking area" data-typetitle="Get directions to the parking area">Get Directions</a>)</li>
														</ol>
													</div>
												</div>
											<?php } ?>
										</div>
									</div>
								</section>
								<?php

							} // endif

						// Construct Appointment Information Section

							if ( $appointment_section_show ) {

								$location_appointment_heading = 'Appointment Information';
								$location_appointment_bring_heading = 'What to Bring to Your Appointment';
								$location_appointment_expect_heading = 'What to Expect at Your Appointment';

								?>
								<section class="uams-module bg-auto" id="appointment-info">
									<div class="container-fluid">
										<div class="row">
											<div class="col-xs-12">
												<?php if ( $location_appointment ) { ?>
													<h2 class="module-title"><span class="title"><?php echo $location_appointment_heading; ?></span></h2>
													<div class="module-body">
														<?php echo $location_appointment; ?>
														<?php if ( $location_appointment_bring ) { ?>
															<h3><?php echo $location_appointment_bring_heading; ?></h3>
															<?php echo $location_appointment_bring; ?>
														<?php } // endif ?>
														<?php if ( $location_appointment_expect ) { ?>
															<h3><?php echo $location_appointment_expect_heading; ?></h3>
															<?php echo $location_appointment_expect; ?>
														<?php } // endif ?>
													</div>

												<?php } elseif ( $location_appointment_bring && $location_appointment_expect ) { ?>
													<h2 class="module-title"><span class="title"><?php echo $location_appointment_heading; ?></span></h2>
													<div class="module-body">
														<h3><?php echo $location_appointment_bring_heading; ?></h3>
														<?php echo $location_appointment_bring; ?>
														<h3><?php echo $location_appointment_expect_heading; ?></h3>
														<?php echo $location_appointment_expect; ?>
													</div>
												<?php } elseif ( $location_appointment_bring ) { ?>
													<h2 class="module-title"><span class="title"><?php echo $location_appointment_bring_heading; ?></span></h2>
													<div class="module-body">
														<?php echo $location_appointment_bring; ?>
													</div>
												<?php } elseif ( $location_appointment_expect ) { ?>
													<h2 class="module-title"><span class="title"><?php echo $location_appointment_expect_heading; ?></span></h2>
													<div class="module-body">
														<?php echo $location_appointment_expect; ?>
													</div>
												<?php } // endif ?>
											</div>
										</div>
									</div>
								</section>
								<?php

							} // endif

						// Construct MyChart Scheduling Section

							if ( $mychart_scheduling_section_show ) {

								?>
								<section class="uams-module mychart-scheduling-module bg-auto" id="scheduling">
									<div class="container-fluid">
										<div class="row">
											<div class="col-xs-12">
												<h2 class="module-title"><span class="title"><?php echo $location_scheduling_title; ?></span></h2>
												<?php if ( $location_scheduling_intro && !empty($location_scheduling_intro) ) { ?>
													<p class="note"><?php echo $location_scheduling_intro; ?></p>
												<?php } ?>
												<div class="module-body">
													<?php if ($location_scheduling_query && (count((array)$location_scheduling_options) > 1)) { ?>
														<form action="" method="get" class="mychart-scheduling-select">
															<div class="form-group">
																<label for="schedule_options" class="lead">Available Services</label>
																<select name="schedule_options" id="schedule_options" class="form-control">
																	<option value="">Select an option</option>
																	<?php foreach($location_scheduling_options as $key => $title) : 
																		$location_scheduling_item_title_nested = $title['location_scheduling_item_title_nested'];
																		$location_scheduling_item_title_nested = ( isset($location_scheduling_item_title_nested) && !empty($location_scheduling_item_title_nested) ) ? $location_scheduling_item_title_nested : 'Schedule an Appointment Online';
																		?>
																		<option value="<?= $key; ?>"<?php //echo ($key == $provider_title) ? ' selected' : ''; ?>><? echo $location_scheduling_item_title_nested; ?></option>
																	<?php endforeach; ?>
																</select>
																<input type="hidden" id="pid" name="pid" value="<?php echo get_the_id(); ?>">
															</div>
														</form>
														<div class="mychart-scheduling"></div>
														<?php //var_dump($location_scheduling_options); ?>
													<?php } else {
														$row = $location_scheduling_options[0];
														$location_scheduling_ser = $row['location_scheduling_ser'];
														$location_scheduling_dep = $row['location_scheduling_dep'];
														$location_scheduling_vt = $row['location_scheduling_vt'];
														$location_scheduling_fallback = $row['location_scheduling_fallback'];
													?>
														<div id="scheduleContainer">
															<iframe id="openSchedulingFrame" class="widgetframe" scrolling="no" src="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/SignupAndSchedule/EmbeddedSchedule?id=<?php echo $location_scheduling_ser; ?>&dept=<?php echo $location_scheduling_dep; ?>&vt=<?php echo $location_scheduling_vt; ?>&linksource=<?php echo $mychart_scheduling_linksource; ?>"></iframe>
														</div>

														<!-- <link href="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/Content/EmbeddedWidget.css" rel="stylesheet" type="text/css"> -->

														<script src="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/Content/EmbeddedWidgetController.js" type="text/javascript"></script>

														<script type="text/javascript">
														var EWC = new EmbeddedWidgetController({

															// Replace with the hostname of your Open Scheduling site
															'hostname':'https://<?php echo $mychart_scheduling_domain; ?>',

															// Must equal media query in EpicWP.css + any left/right margin of the host page. Should also change in EmbeddedWidget.css
															'matchMediaString':'(max-width: 991.98px)',

															//Show a button on top of the widget that lets the user see the slots in fullscreen.
															'showToggleBtn':true,

															//The toggle button’s help text for screen reader.
															'toggleBtnExpandHelpText': 'Expand to see the slots in fullscreen',
															'toggleBtnCollapseHelpText': 'Exit fullscreen',
														});
														</script>
														<?php if ( $location_scheduling_fallback && !empty($location_scheduling_fallback) ) { ?>
															<div class="more">
																<?php echo $location_scheduling_fallback; ?>
															</div>
														<?php } ?>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
								</section>
								<?php

							} // endif

						// Construct Telemedicine Information Section

							if ( $telemedicine_section_show ) {

								?>
								<section class="uams-module bg-auto" aria-label="Telemedicine Information" id="telemedicine-info">
									<div class="container-fluid">
										<div class="row">
											<div class="col-12">
												<h2 class="module-title"><span class="title">Telemedicine Information</span></h2>
												<?php if ($location_closing_display && !$location_closing_telemed) { ?>
													<div class="module-body">
														<p class="text-center"><strong>
															<?php if ($location_closing_date_past) { ?>
																Telemedicine is not available while this <?php echo strtolower($location_single_name); ?> is <?php echo $location_closing_length == 'temporary' ? 'temporarily' : 'permanently' ; ?> closed.
															<?php } else { ?>
																Telemedicine will not be available after this <?php echo strtolower($location_single_name); ?> closes <?php echo $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently' ; ?> on <?php echo $location_closing_date; ?>.
															<?php } // endif ?>
														</strong></p>
													</div>
												<?php } else { ?>
													<div class="row content-split-lg">
														<div class="col-xs-12 col-lg-7">
															<div class="content-width">
																<?php echo $telemedicine_info ? $telemedicine_info : '' ?>
																<p>
																	<?php // Declare which patients can use the service.
																	if ($telemedicine_patients == 'all') { ?>
																		This service is available to both new and existing patients.
																	<?php } elseif ($telemedicine_patients == 'new') { ?>
																		This service is available to new patients only.
																	<?php } elseif ($telemedicine_patients == 'existing') { ?>
																		This service is available to existing patients only.
																	<?php } // endif

																	// Declare which phone number should be called.

																		if (!$location_clinic_phone_query) { // If there is only one phone number ?>
																			Patients should call <?php echo $location_phone_link; ?> to schedule a telemedicine appointment.
																		<?php } elseif ($location_clinic_phone_query && !$location_appointment_phone_query) { // If there is only one appointment number ?>
																			Patients should call <?php echo $location_new_appointments_phone_link; ?> to schedule a telemedicine appointment.
																		<?php } else { // If there are two appointment numbers (one for new, one for existing)
																			if ($telemedicine_patients == 'all') { ?>
																				New patients should call <?php echo $location_new_appointments_phone_link; ?> to schedule a telemedicine appointment, while existing patients should call <?php echo $location_return_appointments_phone_link; ?>.
																			<?php } elseif ($telemedicine_patients == 'new') { ?>
																				Patients should call <?php echo $location_new_appointments_phone_link; ?> to schedule a telemedicine appointment.
																			<?php } elseif ($telemedicine_patients == 'existing') { ?>
																				Patients should call <?php echo $location_return_appointments_phone_link; ?> to schedule a telemedicine appointment.
																			<?php }
																		} // endif ?>
																</p>
															</div>
														</div>
														<div class="col-xs-12 col-lg-5">
															<div class="content-width">
															<?php
															$telemedicine_modified_text = '';
															$telemedicine_active_start = '';
															$telemedicine_active_end = '';
															if ($telemedicine_modified) : 
															?>
															<?php 

																$telemedicine_modified_day = ''; // Previous Day
																$telemedicine_modified_comment = ''; // Comment on previous day
																$i = 1;

																$telemedicine_today = strtotime("today");
																$telemedicine_today_30 = strtotime("+30 days");

																if( strtotime($telemedicine_modified_start) <= $telemedicine_today_30 && ( strtotime($telemedicine_modified_end_date) >= $telemedicine_today || !$telemedicine_modified_end ) ){
																	$telemedicine_modified_text .= $telemedicine_modified_reason;
																	$telemedicine_modified_text .= '<p class="small font-italic">These modified hours start on ' . $telemedicine_modified_start . ', ';
																	$telemedicine_modified_text .= $telemedicine_modified_end && $telemedicine_modified_end_date ? 'and are scheduled to end after ' . $telemedicine_modified_end_date . '.' : 'and will remain in effect until further notice.';
																	$telemedicine_modified_text .= '</p>';

																	if ($telemedicine_modified_hours247):
																		$telemedicine_modified_text .= '<strong>Open 24/7</strong>';
																	else :
																		$telemedicine_modified_times = $location_hours_group['location_telemed_modified_hours_times'];
																		if ($telemedicine_active_start > strtotime($telemedicine_modified_start) || '' == $telemedicine_active_start) {
																			$telemedicine_active_start = strtotime($telemedicine_modified_start);
																		}
																		if ( $telemedicine_active_end <= strtotime($telemedicine_modified_end_date) || !$telemedicine_modified_end ) {
																			if (!$telemedicine_modified_end) {
																				$telemedicine_active_end = 'TBD';
																			} else {
																				$telemedicine_active_end = strtotime($telemedicine_modified_end_date);
																			}
																		}

																		if (is_array($telemedicine_modified_times) || is_object($telemedicine_modified_times)) {
																			$telemedicine_modified_text .= '<dl class="hours">';
																			foreach ( $telemedicine_modified_times as $telemedicine_modified_time ) {

																				$telemedicine_modified_text .= $telemedicine_modified_day !== $telemedicine_modified_time['location_telemed_modified_hours_day'] ? '<dt>'. $telemedicine_modified_time['location_telemed_modified_hours_day'] .'</dt> ' : '';
																				$telemedicine_modified_text .= '<dd>';

																				if ( $telemedicine_modified_time['location_telemed_modified_hours_closed'] ) {
																					$telemedicine_modified_text .= 'Closed ';
																				} else {
																					$telemedicine_modified_text .= ( ( $telemedicine_modified_time['location_telemed_modified_hours_open'] && '00:00:00' != $telemedicine_modified_time['location_telemed_modified_hours_open'] ) ? '' . ap_time_span( strtotime($telemedicine_modified_time['location_telemed_modified_hours_open']), strtotime($telemedicine_modified_time['location_telemed_modified_hours_close']) ) . '' : '' );
																				}
																				if ( $telemedicine_modified_time['location_telemed_modified_hours_comment'] ) {
																					$telemedicine_modified_text .= ' <br /><span class="subtitle">' .$telemedicine_modified_time['location_telemed_modified_hours_comment'] . '</span>';
																					$telemedicine_modified_comment = $telemedicine_modified_time['location_telemed_modified_hours_comment'];
																				} else {
																					$telemedicine_modified_comment = '';
																				}
																				$telemedicine_modified_text .= '</dd>';
																				$telemedicine_modified_day = $telemedicine_modified_time['location_telemed_modified_hours_day']; // Reset the day
																				$i++;

																			} // endforeach
																			$telemedicine_modified_text .= '</dl>';

																		} // End if (array)
																	endif;
																}

																echo $telemedicine_modified_text ? '<h3>Modified Hours</h3>' . $telemedicine_modified_text: '';

															endif; // End Modified Hours
															if (($telemedicine_active_start != '' && $telemedicine_active_start <= $telemedicine_today) && ( strtotime($telemedicine_active_end) > $telemedicine_today || $telemedicine_active_end == 'TBD' ) ) {
																// Do Nothing;
																// Future Option
															} else {
																if ( $telemedicine_hours247 || $telemedicine_hours[0]['day'] ) : ?>
																<h3><?php echo $telemedicine_modified_text ? 'Typical ' : ''; ?>Hours</h3>
																<?php
																	if ($telemedicine_hours247):
																		echo '<strong>Open 24/7</strong>';
																	else :
																		echo '<dl class="hours">';
																		if( $telemedicine_hours ) {
																			$telemedicine_hours_text = '';
																			$telemedicine_day = ''; // Previous Day
																			$telemedicine_comment = ''; // Comment on previous day
																			$i = 1;
																			foreach ($telemedicine_hours as $telemedicine_hour) :
																				$telemedicine_hours_text .= $telemedicine_day !== $telemedicine_hour['day'] ? '<dt>'. $telemedicine_hour['day'] .'</dt> ' : '';
																				$telemedicine_hours_text .= '<dd>';

																				if ( $telemedicine_hour['closed'] ) {
																					$telemedicine_hours_text .= 'Closed ';
																				} else {
																					$telemedicine_hours_text .= ( ( $telemedicine_hour['open'] && '00:00:00' != $telemedicine_hour['open'] ) ? '' . ap_time_span( strtotime($telemedicine_hour['open']), strtotime($telemedicine_hour['close']) ) . '' : '' );
																				}
																				if ( $telemedicine_hour['comment'] ) {
																					$telemedicine_hours_text .= ' <br /><span class="subtitle">' .$telemedicine_hour['comment'] . '</span>';
																					$telemedicine_comment = $telemedicine_hour['comment'];
																				} else {
																					$telemedicine_comment = '';
																				}
																				$telemedicine_hours_text .= '</dd>';
																				$telemedicine_day = $telemedicine_hour['day']; // Reset the day
																				if (!$telemedicine_hour['closed']) {
																				$i++;
																				}
																			endforeach;
																			echo $telemedicine_hours_text;
																		} else {
																			echo '<dt>No information</dt>';
																		}
																		echo '</dl>';
																	endif;
																endif;
																}
																?>
															</div>
														</div>
													</div>
												<?php } // endif ?>
											</div>
										</div>
									</div>
								</section>
								<?php

							} // endif

						// Construct Portal Section

							if ( $portal_section_show ) {

								?>
								<section class="uams-module cta-bar cta-bar-weighted bg-blue" aria-label="Patient Portal" id="portal-info">
									<div class="container-fluid">
										<div class="row">
											<div class="col-12">
												<div class="inner-container">
													<div class="cta-heading">
														<h2><?php echo $portal_name; ?></h2>
													</div>
													<?php if ( $portal_content || $portal_link ) { ?>
													<div class="cta-body">
														<?php if ( $portal_content ) { ?>
														<div class="text-container">
															<?php echo $portal_content; ?>
														</div>
														<?php }
														if ( $portal_content ) { ?>
														<div class="btn-container">
															<a href="<?php echo $portal_url; ?>" aria-label="Log in to <?php echo $portal_name_attr; ?> to view your patient information and medical records" class="btn btn-white" target="_blank" data-moduletitle="<?php echo $portal_name_attr; ?>"><?php echo $portal_link_title ? $portal_link_title : 'Log in to '. $portal_name; ?></a>
														</div>
														<?php } ?>
													</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
								</section>
								<?php

							} // endif

						// Construct Providers Section

							$provider_section_title = $provider_fpage_title_location; // Text to use for the section title
							$provider_section_intro = $provider_fpage_intro_location; // Text to use for the section intro text
							$provider_section_filter_region = false; // Query whether to add region filter
							include( UAMS_FAD_PATH . '/templates/parts/section-list-provider.php' );

						// Construct Combined Conditions and Treatments Section

							$condition_treatment_section_title = $condition_treatment_fpage_title_location; // Text to use for the section title // string (default: Find-a-Doc Settings value for combined condition/treatment section title in a general placement)
							$condition_treatment_section_intro = $condition_treatment_fpage_intro_location; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for combined condition/treatment section intro text in a general placement)
							$condition_section_title = $condition_fpage_title_location; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
							$condition_section_intro = $condition_fpage_intro_location; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
							$treatment_section_title = $treatment_fpage_title_location; // Text to use for the section title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
							$treatment_section_intro = $treatment_fpage_intro_location; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)
							include( UAMS_FAD_PATH . '/templates/parts/section-list-condition-treatment.php' );

						// Construct Areas of Expertise Section

							$expertise_section_title = $expertise_fpage_title_location;
							$expertise_section_intro = $expertise_fpage_intro_location;
							include( UAMS_FAD_PATH . '/templates/parts/section-list-expertise.php' );

						// Construct Descendant Locations Section

							$location_section_title = $location_descendant_fpage_title_location; // Text to use for the section title // string (default: Find-a-Doc Settings value for locations section title in a general placement)
							$location_section_intro = $location_descendant_fpage_intro_location; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for locations section intro text in a general placement)
							$location_section_filter = false; // Query whether to add filter(s) // bool (default: true)
							$location_descendant_list = true; // Query whether this is a list of child locations within a location // bool (default: false)
							include( UAMS_FAD_PATH . '/templates/parts/section-list-location.php' );

						// Construct Clinical Resources Section

							$clinical_resource_section_more_link_key = '_resource_locations';
							$clinical_resource_section_more_link_value = $page_slug;
							$clinical_resource_section_title = $clinical_resource_fpage_title_location; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of clinical_resource section title in a general placement)
							$clinical_resource_section_intro = $clinical_resource_fpage_intro_location; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of clinical_resource section intro text in a general placement)
							$clinical_resource_section_more_text = $clinical_resource_fpage_more_text_location;
							$clinical_resource_section_more_link_text = $clinical_resource_fpage_more_link_text_location;
							$clinical_resource_section_more_link_descr = $clinical_resource_fpage_more_link_descr_location;
							include( UAMS_FAD_PATH . '/templates/parts/section-list-clinical-resource.php' );

						// Construct News Section

							if ( true == false ) {

								?>
								<!-- Latest News -->
								<!-- <section class="uams-module news-list bg-auto" id="news">
									<div class="container-fluid">
										<div class="row">
											<div class="col-12">
												<h2 class="module-title"><span class="title">Latest News for <?php echo $page_title_phrase; ?></span></h2>
												<div class="card-list-container">
													<div class="card-list">
														<div class="card">
															<img srcset="https://picsum.photos/434/244?image=1066" src="https://picsum.photos/434/244?image=1066" class="card-img-top" alt="Image description or Story title">
															<div class="card-body">
																<h3 class="card-title">
																	<span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
																</h3>
																<p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
																<a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
															</div>
														</div>
														<div class="card">
															<img srcset="https://picsum.photos/434/244?image=348" src="https://picsum.photos/434/244?image=348" class="card-img-top" alt="Image description or Story title">
															<div class="card-body">
																<h3 class="card-title">
																	<span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
																</h3>
																<p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
																<a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
															</div>
														</div>
														<div class="card">
															<img srcset="https://picsum.photos/434/244?image=823" src="https://picsum.photos/434/244?image=823" class="card-img-top" alt="Image description or Story title">
															<div class="card-body">
																<h3 class="card-title">
																	<span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
																</h3>
																<p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
																<a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</section> -->
								<?

							} // endif

						?>
					</main>
				</div>
				<?php

				// Schema Data

					// Set the values

						// Required for Google Structured Data
						// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

							// Type: MedicalClinic
							$schema_type = 'MedicalClinic'; // string

							// Property: name
							$schema_name = $page_title; // string

							// Property: address
							$schema_address = ( isset($schema_address) && is_array($schema_address) && !empty($schema_address) ) ? $schema_address : array(); // array

						// Recommended by Google Structured Data
						// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

							// Property: openingHoursSpecification
							$schema_opening_hours_specification = isset($schema_opening_hours_specification) ? $schema_opening_hours_specification : '';

							// Property: telephone
							$schema_telephone = ( isset($schema_telephone) && is_array($schema_telephone) && !empty($schema_telephone) ) ? $schema_telephone : array(); // array

							// Property: url
							$schema_url = $page_url; // string

						// Additional Selected Properties

							// Property: description
							$schema_description = isset($schema_description) ? $schema_description : ''; // string

							// Property: faxNumber
							$schema_fax_number = ( isset($schema_fax_number) && is_array($schema_fax_number) && !empty($schema_fax_number) ) ? $schema_fax_number : array(); // array

							// Property: image
							$schema_image = isset($schema_image) ? $schema_image : ''; // string

							// Property: medicalSpecialty
							$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array(); // array

							// Property: openingHours
							// $schema_opening_hours = ( isset($schema_opening_hours) && is_array($schema_opening_hours) && !empty($schema_opening_hours) ) ? $schema_opening_hours : array(); // array

					// Construct the schema script tag

						include( UAMS_FAD_PATH . '/templates/parts/schema.php' );

			} // endwhile // end of the loop.

	// FOOTER

		// Remove the post-related content and markup from the entry footer

			// remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
			// remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
			// remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
			// remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

		get_footer();

?>
