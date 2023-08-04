<?php
/*
 * Template Name: Fake Area of Expertise Providers Subpage
 */

// Define Variables

	// Define variables common to all area of expertise pages and fake subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/page_expertise-vars.php' );

	// Define variables common to all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/fpage/page_expertise-fpage-vars.php' );

// HEAD Elements

	// Construct HEAD elements common to all area of expertise overview pages and all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/page_expertise-construct-head.php' );

	// Construct HEAD elements common to all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/fpage/page_expertise-fpage-construct-head.php' );

// BODY elements

	// Construct BODY elements common to all area of expertise overview pages and all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/page_expertise-construct-body.php' );

	// Construct BODY elements common to all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/fpage/page_expertise-fpage-construct-body.php' );

	// Construct main ontology page content

		$provider_section_show_header = false; // Query whether to display the section header
		$provider_section_title = 'List of ' . $provider_plural_name; // Text to use for the section title
		$provider_section_intro = ''; // Text to use for the section intro text
		$provider_section_filter = true; // Query whether to add filter(s)
		$provider_section_filter_region = isset($provider_section_filter_region) ? $provider_section_filter_region : true; // Query for whether to add region filter
		$provider_section_filter_title = isset($provider_section_filter_title) ? $provider_section_filter_title : true; // Query for whether to add title filter
		$provider_section_collapse_list = false; // Query whether to collapse the list of providers in the providers section

		add_action( 'genesis_entry_content', function() use (
			$page_id,
			$providers,
			$page_titles,
			$provider_section_show,
			$ontology_type,
			$provider_section_title,
			$provider_section_intro,
			$provider_section_show_header,
			$provider_section_filter,
			$provider_section_filter_region,
			$provider_section_filter_title,
			$provider_section_collapse_list
		) {
			include( UAMS_FAD_PATH . '/templates/parts/section_list-provider.php' );
		} );

genesis();