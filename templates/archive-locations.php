<?php
/*
 * Template Name: Locations Archive
 */

// Define variables

	// Variable name slug

		$variable_slug = 'location';

	// Post type

		$post_type = 'location';

	// Get system settings for ontology item labels

		// Get system settings for provider labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

		// Get system settings for location labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

		// Get system settings for area of expertise labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

	// Get system settings for location archive text
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/location.php' );

	// Get the system settings for the image elements of the location archive
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/archive/location.php' );

	// Meta title variables

		// Do nothing

	// Arguments used in construction

		$archive_construct_args = array(
			'filters-layout'	=> array(
				'class'						=> 'location',
				'plural-name'				=> $location_plural_name,
				'ajaxsearchpro-id'			=> '2',
				'fwp-filters'				=> array(
					'location_type',
					'location_aoe',
					'location_region'
				), // array // FacetWP filter slugs
				'911-disclaimer'			=> true, // bool // Query for whether to include the 911 disclaimer
				'fwp-template'				=> 'locations', // string // Slug for FacetWP template
				'fwp-total-rows'			=> 9, // int
				'provider-ratings-modal'	=> false, // bool // Query for whether to include the providers ratings modal
				'region-cookie'				=> '_location_region' // string // Region cookie slug
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

// Construct BODY elements

	// Archive Template Common BODY Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/common/construct-body.php' );

	// Archive Template Filters Layout BODY Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/filters-layout/construct-body.php' );

?>