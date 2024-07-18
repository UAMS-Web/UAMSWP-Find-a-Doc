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

	if ( function_exists('uamswp_fad_schema_provider') ) {

		$schema_provider_combined = uamswp_fad_schema_provider(
			array($page_id), // array // Required // List of IDs of the provider items
			$page_url, // string // Required // Page URL
			$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
			0, // int // Optional // Nesting level within the main schema
			array( 'MedicalBusiness', 'MedicalWebPage', 'Person' ), // array // Optional // List of the schema types to output
			1, // int // Optional // Iteration counter for provider-as-MedicalWebPage
			1, // int // Optional // Iteration counter for provider-as-MedicalBusiness
			1, // int // Optional // Iteration counter for provider-as-Person
			$provider_schema_fields // array // Optional // Pre-existing field values array so duplicate calls can be avoided
		);

	} else {

		$schema_provider_combined = null;

	}

// Add provider schema arrays to the base schema array

	// Provider profile as MedicalWebPage

		if ( isset($schema_provider_combined['MedicalWebPage']) ) {

			$schema_provider['@graph'][] = $schema_provider_combined['MedicalWebPage'];

		}

	// Provider profile as MedicalBusiness

		if ( isset($schema_provider_combined['MedicalBusiness']) ) {

			$schema_provider['@graph'][] = $schema_provider_combined['MedicalBusiness'];

		}

	// Provider profile as Person

		if ( isset($schema_provider_combined['Person']) ) {

			$schema_provider['@graph'][] = $schema_provider_combined['Person'];

		}

// Construct the schema JSON script tag

	uamswp_fad_schema_construct($schema_provider);