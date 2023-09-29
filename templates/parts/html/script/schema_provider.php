<?php

/*
 * Required vars:
 * 
 * 	$schema_common_base
 * 	$page_id
 */

include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base_script.php' );

$schema_provider = $schema_common_base;

// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.
$page_id = get_the_ID();

// Define Schema JSON item arrays for provider profile as MedicalWebPage, MedicalBusiness and Person

	$node_identifier_list = $node_identifier_list ?? array();

	$schema_provider_combined = uamswp_fad_schema_provider(
		array($page_id), // List of IDs of the provider items
		$page_url, // Page URL
		$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
		0, // Nesting level within the main schema
		1, // Iteration counter for provider-as-MedicalWebPage
		1, // Iteration counter for provider-as-MedicalBusiness
		1, // Iteration counter for provider-as-Person
		$provider_schema_fields // Pre-existing field values array so duplicate calls can be avoided
	);

// Add provider schema arrays to the base schema array

	// Provider profile as MedicalWebPage

		$schema_provider['@graph'][] = $schema_provider_combined['MedicalWebPage'];

	// Provider profile as MedicalBusiness

		$schema_provider['@graph'][] = $schema_provider_combined['MedicalBusiness'];

	// Provider profile as Person

		$schema_provider['@graph'][] = $schema_provider_combined['Person'];

// Construct the schema JSON script tag

	uamswp_fad_schema_construct($schema_provider);

// Display array as development testing

	echo '<pre>'; // test

	// Full
	echo print_r($schema_provider); // test

	// // Specific @graph item
	// echo print_r($schema_provider['@graph'][0]); // test

	echo '</pre>'; // test

// Reusable test display lines

	// echo '<p>$foo = ' . ( is_array($foo) ? 'Array' : ( is_object($foo) ? 'Object' : ( is_null($foo) ? 'Null' : ( $foo ) ) ) ) . '</p>'; // test
	// if ( is_array($foo) || is_object($foo) ) { echo '<pre>'; print_r($foo); echo '</pre>'; } // test
