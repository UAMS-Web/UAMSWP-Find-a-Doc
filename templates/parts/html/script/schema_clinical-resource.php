<?php

/*
 * Required vars:
 *
 * 	$schema_common_base
 * 	$page_id
 */

include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base_script.php' );

$schema_clinical_resource = $schema_common_base;

// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.
$page_id = get_the_ID();

// Define Schema JSON item arrays for clinical resource profile as MedicalWebPage and CreativeWork

	$node_identifier_list = $node_identifier_list ?? array(); // List of node identifiers (@id) already defined in the schema
	$clinical_resource_schema_fields = $clinical_resource_schema_fields ?? array();

	$schema_clinical_resource_combined = uamswp_fad_schema_clinical_resource(
		array($page_id), // List of IDs of the clinical resource items
		$page_url, // Page URL
		$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
		0, // Nesting level within the main schema
		1, // Iteration counter for clinical resource-as-MedicalWebPage
		1, // Iteration counter for clinical resource-as-CreativeWork
		$clinical_resource_schema_fields // Pre-existing field values array so duplicate calls can be avoided
	);

// Add clinical resource schema arrays to the base schema array

	// Clinical resource profile as MedicalWebPage

		if ( isset($schema_clinical_resource_combined['MedicalWebPage']) ) {

			$schema_clinical_resource['@graph'][] = $schema_clinical_resource_combined['MedicalWebPage'];

		}

	// Clinical resource profile as CreativeWork

		if ( isset($schema_clinical_resource_combined['CreativeWork']) ) {

			$schema_clinical_resource['@graph'][] = $schema_clinical_resource_combined['CreativeWork'];

		}

// Construct the schema JSON script tag

	uamswp_fad_schema_construct($schema_clinical_resource);

// Display array as development testing

	// echo '<pre>'; // test

	// // Full
	// echo print_r($schema_clinical_resource); // test

	// // UAMS
	// echo print_r($schema_clinical_resource['@graph'][0]); // test

	// // UAMS Health
	// echo print_r($schema_clinical_resource['@graph'][1]); // test

	// // UAMSHealth.com
	// echo print_r($schema_clinical_resource['@graph'][2]); // test

	// // MedicalWebPage
	// echo print_r($schema_clinical_resource['@graph'][3]); // test

	// // CreativeWork
	// echo print_r($schema_clinical_resource['@graph'][4]); // test

	// // Specific @graph item
	// echo print_r($schema_clinical_resource['@graph'][3]); // test

	// echo '</pre>'; // test

// Reusable test display lines

	// echo '<p>$foo = ' . ( is_array($foo) ? 'Array' : ( is_object($foo) ? 'Object' : ( is_null($foo) ? 'Null' : ( $foo ) ) ) ) . '</p>'; // test
	// if ( is_array($foo) || is_object($foo) ) { echo '<pre>'; print_r($foo); echo '</pre>'; } // test
