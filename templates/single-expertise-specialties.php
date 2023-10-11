<?php
/*
 * Template Name: Fake Area of Expertise Specialties Subpage
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

		$expertise_descendant_list = true;
		$expertise_section_id = 'sub-expertise'; // Section ID // string (default: expertise)
		$expertise_section_show_header = false; // Query whether to display the section header // bool (default: true)
		$expertise_section_title = 'List of ' . $expertise_descendant_plural_name; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of expertise section title in a general placement)
		$expertise_section_intro = ''; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of expertise section intro text in a general placement)

		add_action( 'genesis_entry_content', function() use (
			$page_id,
			$expertise_descendants,
			$page_titles,
			$hide_medical_ontology,
			$expertise_descendant_section_show,
			$ontology_type,
			$expertise_descendant_list,
			$content_placement,
			$site_nav_id,
			$expertise_section_title,
			$expertise_section_intro,
			$expertise_section_show_header
		) {
			include( UAMS_FAD_PATH . '/templates/parts/html/section/list/expertise.php' );
		}, 12 );

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