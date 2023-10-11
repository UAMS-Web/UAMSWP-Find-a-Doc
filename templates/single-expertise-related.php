<?php
/*
 * Template Name: Fake Area of Expertise Related Areas of Expertise Subpage
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

		$expertise_descendant_list = isset($expertise_descendant_list) ? $expertise_descendant_list : false;
		$site_nav_id = ''; // ID of post that defines the subsection
		$expertise_section_title = 'List of Related ' . $expertise_plural_name;
		$expertise_section_intro = '';
		$expertise_section_show_header = false; // Query whether to display the section header
		$expertise_section_collapse_list = false; // Query for whether to collapse the list of locations in the locations section
		$expertise_section_id = 'related-expertise'; // Section ID

		add_action( 'genesis_entry_content', function() use (
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
			$expertise_section_intro,
			$expertise_section_show_header,
			$expertise_section_collapse_list,
			$expertise_section_id
		) {
			include( UAMS_FAD_PATH . '/templates/parts/html/section/list/expertise.php' );
		}, 24 );

	// Construct the Schema Data Script Tag

		add_action( 'genesis_after_entry', function() use (
			$page_id,
			$page_url,
			$hide_medical_ontology,
			$ontology_type
		) {
			include( UAMS_FAD_PATH . '/templates/parts/html/script/schema_expertise.php' );
		}, 18 );

genesis();