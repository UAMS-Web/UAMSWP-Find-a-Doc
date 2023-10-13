<?php

/*
 * Required vars:
 *
 * 	$schema_common_base
 * 	$page_id
 */

include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base_script.php' );

$schema_expertise = $schema_common_base;

// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.
$page_id = get_the_ID();

// Define Schema JSON item arrays for area of expertise profile as MedicalWebPage and MedicalEntity

	$node_identifier_list = $node_identifier_list ?? array(); // List of node identifiers (@id) already defined in the schema
	$expertise_schema_fields = $expertise_schema_fields ?? array();
	$ontology_type = $ontology_type ?? true;
	$current_fpage = $current_fpage ?? '';
	$fpage_url = $fpage_url ?? '';

	$schema_expertise_combined = uamswp_fad_schema_expertise(
		array($page_id), // array // Required // List of IDs of the area of expertise items
		$fpage_url ?: $page_url, // string // Required // Page or fake subpage URL
		$ontology_type, // bool // Required // Query for the ontology type of the post (true is ontology type, false is content type)
		$current_fpage, // string // Required // Fake subpage slug
		$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
		0, // Nesting level within the main schema
		1, // Iteration counter for area of expertise-as-MedicalWebPage
		1, // Iteration counter for area of expertise-as-MedicalEntity
		$expertise_schema_fields // Pre-existing field values array so duplicate calls can be avoided
	);

// Add area of expertise schema arrays to the base schema array

	// Area of expertise profile as MedicalWebPage

		if ( isset($schema_expertise_combined['MedicalWebPage']) ) {

			$schema_expertise['@graph'][] = $schema_expertise_combined['MedicalWebPage'];

		}

	// Area of expertise profile as MedicalEntity

		if ( isset($schema_expertise_combined['MedicalEntity']) ) {

			$schema_expertise['@graph'][] = $schema_expertise_combined['MedicalEntity'];

		}

// Construct the schema JSON script tag

	uamswp_fad_schema_construct($schema_expertise);

// Display array as development testing

	echo '<pre>'; // test

	// // Full
	// echo print_r($schema_expertise); // test

	// // UAMS
	// echo print_r($schema_expertise['@graph'][0]); // test

	// // UAMS Health
	// echo print_r($schema_expertise['@graph'][1]); // test

	// // UAMSHealth.com
	// echo print_r($schema_expertise['@graph'][2]); // test

	// MedicalWebPage
	echo print_r($schema_expertise['@graph'][3]); // test

	// MedicalEntity
	echo print_r($schema_expertise['@graph'][4]); // test

	// // Specific @graph item
	// echo print_r($schema_expertise['@graph'][3]); // test

	echo '</pre>'; // test

// Reusable test display lines

	// echo '<p>$foo = ' . ( is_array($foo) ? 'Array' : ( is_object($foo) ? 'Object' : ( is_null($foo) ? 'Null' : ( $foo ) ) ) ) . '</p>'; // test
	// if ( is_array($foo) || is_object($foo) ) { echo '<pre>'; print_r($foo); echo '</pre>'; } // test
