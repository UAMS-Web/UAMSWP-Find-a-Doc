<?php
/*
 * Template Name: Areas of Expertise Archive
 */

// Define variables

	// Variable name slug

		$variable_slug = 'expertise';

	// Post type
	
		$post_type = 'expertise';

	// Get system settings for ontology item labels

		// Get system settings for area of expertise labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

	// Get system settings for area of expertise archive text
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/expertise.php' );

	// Get the system settings for the image elements of the area of expertise archive
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/archive/expertise.php' );

	// Meta title variables
	
		// Do nothing

	// Arguments used in construction

		$archive_construct_args = array(
			'filters-layout'	=> array(
				'class'						=> 'expertise',
				'layout'					=> 'cards', // string enum('filters', 'cards')
				'plural-name'				=> $expertise_plural_name,
				'ajaxsearchpro-id'			=> '',
				'fwp-filters'				=> array(), // array // FacetWP filter slugs
				'911-disclaimer'			=> false, // bool // Query for whether to include the 911 disclaimer
				'fwp-template'				=> '', // string // Slug for FacetWP template
				'fwp-total-rows'			=> 3, // int
				'provider-ratings-modal'	=> false, // bool // Query for whether to include the providers ratings modal
				'region-cookie'				=> '' // string // Region cookie slug
			),
			'cards-layout'		=> array(
				'id'				=> 'expertise',
				'class'				=> 'expertise',
				'intro-text'		=> $expertise_archive_intro_text,
				'plural-name'		=> $expertise_plural_name,
				'fwp-template'		=> 'expertise', // string // Slug for FacetWP template
				'fwp-total-rows'	=> 3, // int
			)
		);

	// Define variables common to all archive pages
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/common/vars.php' );

	// Define variables common to all archive pages with the cards layout
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/cards-layout/vars.php' );

// Construct HEAD elements

	// Archive Template Common HEAD Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/common/construct-head.php' );

	// Archive Template Filters Layout HEAD Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/cards-layout/construct-head.php' );

// Construct BODY elements

	// Archive Template Common BODY Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/common/construct-body.php' );

	// Archive Template Cards Layout BODY Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/cards-layout/construct-body.php' );

?>