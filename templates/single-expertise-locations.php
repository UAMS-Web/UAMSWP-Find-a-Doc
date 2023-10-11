<?php
/*
 * Template Name: Fake Area of Expertise Locations Subpage
 */

// Define Variables

	// Define variables common to all area of expertise pages and fake subpages
	include( UAMS_FAD_PATH . '/templates/parts/construction/single-expertise/common/vars.php' );

	// Define variables common to all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/construction/single-expertise/fpage/vars.php' );

// HEAD Elements

	// Construct HEAD elements common to all area of expertise overview pages and all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/construction/single-expertise/common/construct-head.php' );

	// Construct HEAD elements common to all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/construction/single-expertise/fpage/construct-head.php' );

// BODY elements

	// Construct BODY elements common to all area of expertise overview pages and all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/construction/single-expertise/common/construct-body.php' );

	// Construct BODY elements common to all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/construction/single-expertise/fpage/construct-body.php' );

	// Construct main ontology page content

		$location_section_schema_query = isset($location_section_schema_query) ? $location_section_schema_query : false; // Query for whether to add locations to schema
		$location_descendant_list = isset($location_descendant_list) ? $location_descendant_list : false; // Query for whether this is a list of child locations within a location
		$location_section_title = 'List of ' . $location_plural_name; // Text to use for the section title
		$location_section_intro = ''; // Text to use for the section intro text
		$location_section_show_header = false; // Query whether to display the section header
		$location_section_filter = isset($location_section_filter) ? $location_section_filter : true; // Query for whether to add filter(s)
		$location_section_filter_region = isset($location_section_filter_region) ? $location_section_filter_region : true; // Query for whether to add region filter
		$location_section_filter_title = isset($location_section_filter_title) ? $location_section_filter_title : true; // Query for whether to add title filter
		$location_section_collapse_list = false; // Query whether to collapse the list of locations in the providers section

		add_action( 'genesis_entry_content', function() use (
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
			include( UAMS_FAD_PATH . '/templates/parts/html/section/list/location.php' );
		}, 22 );

	// Construct the Schema Data Script Tag

		add_action( 'genesis_after_entry', function() use (
			$page_id,
			$page_url,
			$hide_medical_ontology,
			$ontology_type,
			$current_fpage,
			$fpage_url
		) {
			include( UAMS_FAD_PATH . '/templates/parts/html/script/schema_expertise.php' );
		}, 18 );

genesis();