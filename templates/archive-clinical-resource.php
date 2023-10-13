<?php
/*
 * Template Name: Clinical Resources Archive
 */

// Define variables

	// Variable name slug
	$variable_slug = 'clinical_resource';

	// Get system settings for ontology item labels

		// Get system settings for provider labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

		// Get system settings for location labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

		// Get system settings for area of expertise labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

		// Get system settings for clinical resource labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

		// Get system settings for Clinical Resource facet labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/facets/clinical-resource.php' );

		// Get system settings for condition labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition.php' );

		// Get system settings for treatment labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/treatment.php' );

	// Get system settings for clinical resource archive text
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/clinical-resource.php' );

	// Get the system settings for the image elements of the clinical resource archive
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/archive/clinical-resource.php' );

	// Meta title variables

		// Do nothing

	// Arguments used in construction

		$archive_construct_args = array(
			'filters-layout'	=> array(
				'class'						=> 'resource',
				'plural-name'				=> $clinical_resource_plural_name,
				'ajaxsearchpro-id'			=> '3',
				'fwp-filters'				=> array(
					'resource_provider',
					'resource_locations',
					'resource_type',
					'resource_aoe',
					'resource_conditions',
					'resource_treatments'
				), // array // FacetWP filter slugs
				'911-disclaimer'			=> false, // bool // Query for whether to include the 911 disclaimer
				'fwp-template'				=> 'clinical_resources', // string // Slug for FacetWP template
				'fwp-total-rows'			=> 3, // int
				'provider-ratings-modal'	=> false, // bool // Query for whether to include the providers ratings modal
				'region-cookie'				=> '' // string // Region cookie slug
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