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

	if ( function_exists('uamswp_fad_schema_expertise') ) {

		$MedicalWebPage_i = $MedicalWebPage_i ?? 1; // Iteration counter for area of expertise-as-MedicalWebPage
		$MedicalEntity_i = $MedicalEntity_i ?? 1; // Iteration counter for area of expertise-as-MedicalEntity
		$MedicalCondition_i = $MedicalCondition_i ?? 1; // Iteration counter for condition
		$Service_i = $Service_i ?? 1; // Iteration counter for treatments and procedures

		$schema_expertise_combined = uamswp_fad_schema_expertise(
			array($page_id), // array // Required // List of IDs of the area of expertise items
			$fpage_url ?: $page_url, // string // Required // Page or fake subpage URL
			$ontology_type, // bool // Required // Query for the ontology type of the post (true is ontology type, false is content type)
			$current_fpage, // string // Required // Fake subpage slug
			$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
			0, // int // Optional // Nesting level within the main schema
			$MedicalWebPage_i, // int // Optional // Iteration counter for area of expertise-as-MedicalWebPage
			$MedicalEntity_i, // int // Optional // Iteration counter for area of expertise-as-MedicalEntity
			$MedicalCondition_i, // int // Optional // Iteration counter for condition
			$Service_i, // int // Optional // Iteration counter for treatments and procedures
			$expertise_schema_fields // array // Optional // Pre-existing field values array so duplicate calls can be avoided
		);

	} else {

		$schema_expertise_combined = null;

	}

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