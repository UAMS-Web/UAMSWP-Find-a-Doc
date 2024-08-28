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

	if ( function_exists('uamswp_fad_schema_clinical_resource') ) {

		$MedicalWebPage_i = $MedicalWebPage_i ?? 1, // Iteration counter for clinical resource-as-MedicalWebPage
		$CreativeWork_i = $CreativeWork_i ?? 1, // Iteration counter for clinical resource-as-CreativeWork

		$schema_clinical_resource_combined = uamswp_fad_schema_clinical_resource(
			array($page_id), // array // Required // List of IDs of the clinical resource items
			$page_url, // string // Required // Page URL
			$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
			0, // int // Optional // Nesting level within the main schema
			$MedicalWebPage_i, // int // Optional // Iteration counter for clinical resource-as-MedicalWebPage
			$CreativeWork_i, // int // Optional // Iteration counter for clinical resource-as-CreativeWork
			$clinical_resource_schema_fields // array // Optional // Pre-existing field values array so duplicate calls can be avoided
		);

	} else {

		$schema_clinical_resource_combined = null;

	}

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