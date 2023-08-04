<?php
/*
 * Template Name: Fake Area of Expertise Clinical Resources Subpage
 */

// Define Variables

	// Define variables common to all area of expertise pages and fake subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/page_expertise_common_vars.php' );

	// Define variables common to all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/fpage/page_expertise_fpage_vars.php' );

// HEAD Elements

	// Construct HEAD elements common to all area of expertise overview pages and all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/page_expertise_common_construct-head.php' );

	// Construct HEAD elements common to all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/fpage/page_expertise_fpage_construct-head.php' );

// BODY elements

	// Construct BODY elements common to all area of expertise overview pages and all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/page_expertise_common_construct-body.php' );

	// Construct BODY elements common to all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/page/expertise/fpage/page_expertise_fpage_construct-body.php' );

	// Construct main ontology page content

		$clinical_resource_section_more_link_key = '_resource_aoe';
		$clinical_resource_section_more_link_value = $page_slug;
		$clinical_resource_section_show_header = false; // Query whether to display the section header // bool (default: true)
		$clinical_resource_section_title = 'List of ' . $clinical_resource_plural_name; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of clinical_resource section title in a general placement)
		$clinical_resource_section_intro = ''; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of clinical_resource section intro text in a general placement)
		$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_fpage; // Maximum number of clinical resources to display (-1, 4, 6, 8, 10 or 12)
		$clinical_resource_section_more_show = false; // Query whether to show the section that links to more items // bool (default: true)
		$clinical_resource_section_more_text = ''; // Text to use for the "more" intro text
		$clinical_resource_section_more_link_text = ''; // Text to use for the "more" link text
		$clinical_resource_section_more_link_descr = ''; // Text to use for the "more" link description
		$clinical_resource_section_show_header = false; // Query for whether to display the section header

		add_action( 'genesis_entry_content', function() use (
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
			$clinical_resource_section_more_show,
			$clinical_resource_section_more_text,
			$clinical_resource_section_more_link_text,
			$clinical_resource_section_more_link_descr,
			$clinical_resource_section_show_header
		) {
			include( UAMS_FAD_PATH . '/templates/parts/section/section_list-clinical-resource.php' );
		}, 14 );

genesis();