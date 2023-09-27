<?php

/*
 * Required vars:
 * 
 * 	$schema_common_base
 * 	$page_id
 */

include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base.php' );

$schema_location = $schema_common_base;

// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.
$page_id = get_the_ID();

// Define Schema JSON item arrays for location profile as MedicalWebPage, MedicalBusiness and Person

	$schema_location_combined = uamswp_fad_schema_location(
		array($page_id), // List of IDs of the location items
		$page_url, // Page URL
		0, // Nesting level within the main schema
		1, // Iteration counter for location-as-MedicalWebPage
		1, // Iteration counter for location-as-LocalBusiness
		false, // bool // Query for whether to output the 'MedicalWebPage' type
		true, // bool // Query for whether to output the 'LocalBusiness' type
		$location_schema_fields // Pre-existing field values array so duplicate calls can be avoided
	);

// Add location schema arrays to the base schema array

	// location profile as MedicalWebPage

		$schema_location['@graph'][] = $schema_location_combined['MedicalWebPage'];

	// location profile as LocalBusiness

		$schema_location['@graph'][] = $schema_location_combined['LocalBusiness'];

// Construct the schema JSON script tag

	uamswp_fad_schema_construct($schema_location);

// Display array as development testing

	echo '<pre>'; // test

	// Full
	echo print_r($schema_location); // test

	// // Specific @graph item
	// echo print_r($schema_location['@graph'][0]); // test

	echo '</pre>'; // test

// Reusable test display lines

	// echo '<p>$foo = ' . ( is_array($foo) ? 'Array' : ( is_object($foo) ? 'Object' : ( is_null($foo) ? 'Null' : ( $foo ) ) ) ) . '</p>'; // test
	// if ( is_array($foo) || is_object($foo) ) { echo '<pre>'; print_r($foo); echo '</pre>'; } // test
