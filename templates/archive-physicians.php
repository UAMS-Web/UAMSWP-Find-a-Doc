<?php
/*
 * Template Name: Providers Archive
 */

// Define variables

	// Variable name slug

		$variable_slug = 'provider';

	// Post type
	
		$post_type = 'provider';

	// Get system settings for ontology item labels

		// Get system settings for provider labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

		// Get system settings for location labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

		// Get system settings for area of expertise labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

		// Get system settings for condition labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition.php' );

		// Get system settings for treatment labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/treatment.php' );

	// Get system settings for provider archive text
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/provider.php' );

	// Get the system settings for the image elements of the provider archive
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/archive/provider.php' );

	// Meta title variables
	
		// Do nothing

	// Arguments used in construction

		$archive_construct_args = array(
			'filters-layout'	=> array(
				'class'						=> 'doctor',
				'plural-name'				=> $provider_plural_name,
				'ajaxsearchpro-id'			=> 1,
				'fwp-filters'				=> array(
					'alpha',
					'primary_care',
					'physician_areas_of_expertise',
					'conditions',
					'treatments_procedures',
					'patient_types',
					'physician_gender',
					'physician_language',
					'locations',
					'provider_region'
				), // array // FacetWP filter slugs
				'911-disclaimer'			=> true, // bool // Query for whether to include the 911 disclaimer
				'fwp-template'				=> 'physician', // string // Slug for FacetWP template
				'fwp-total-rows'			=> 3, // int
				'provider-ratings-modal'	=> true, // bool // Query for whether to include the providers ratings modal
				'region-cookie'				=> '_provider_region' // string // Region cookie slug
			),
			'cards-layout'		=> array(
				'id'				=> '',
				'class'				=> '',
				'intro-text'		=> '',
				'plural-name'		=> '',
				'fwp-template'		=> '', // string // Slug for FacetWP template
				'fwp-total-rows'	=> 3, // int
			)
		);

	// Define variables common to all archive pages
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/common/vars.php' );

	// Define variables common to all archive pages with the filters layout
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/filters-layout/vars.php' );

// Construct HEAD elements

	// Archive Template Common HEAD Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/common/construct-head.php' );

	// Archive Template Filters Layout HEAD Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/filters-layout/construct-head.php' );

	// Custom excerpt

		function custom_field_excerpt($title) {

			// Bring in variables from outside of the function
			global $post; // WordPress-specific global variable

			$text = get_field($title);

			if ( '' != $text ) {

				$text = strip_shortcodes( $text );
				$text = apply_filters('the_content', $text);
				$text = str_replace(']]>', ']]>', $text);
				$excerpt_length = 35; // 35 words
				$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
				$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );

			}

			return apply_filters('the_excerpt', $text);
		}

		function wpdocs_custom_excerpt_length( $length ) {
			return 35; // 35 words
		}

		add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

// Construct BODY elements

	// Archive Template Common BODY Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/common/construct-body.php' );

	// Archive Template Filters Layout BODY Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/filters-layout/construct-body.php' );

?>
