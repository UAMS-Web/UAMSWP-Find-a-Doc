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

		// Check argument variables

			$schema_provider_provider_MedicalWebPage_i = $schema_provider_provider_MedicalWebPage_i ?? 1; // Iteration counter for provider-as-MedicalWebPage
			$schema_provider_provider_MedicalBusiness_i = $schema_provider_provider_MedicalBusiness_i ?? 1; // Iteration counter for provider-as-MedicalBusiness
			$schema_provider_provider_Person_i = $schema_provider_provider_Person_i ?? 1; // Iteration counter for provider-as-Person
			$schema_provider_location_MedicalWebPage_i = $schema_provider_location_MedicalWebPage_i ?? 1; // Iteration counter for location-as-MedicalWebPage
			$schema_provider_location_LocalBusiness_i = $schema_provider_location_LocalBusiness_i ?? 1; // Iteration counter for location-as-LocalBusiness
			$schema_provider_expertise_MedicalWebPage_i = $schema_provider_expertise_MedicalWebPage_i ?? 1; // Iteration counter for area of expertise-as-MedicalWebPage
			$schema_provider_expertise_MedicalEntity_i = $schema_provider_expertise_MedicalEntity_i ?? 1; // Iteration counter for area of expertise-as-MedicalEntity
			$schema_provider_clinical_resource_MedicalWebPage_i = $schema_provider_clinical_resource_MedicalWebPage_i ?? 1; // Iteration counter for clinical resource-as-MedicalWebPage
			$schema_provider_clinical_resource_CreativeWork_i = $schema_provider_clinical_resource_CreativeWork_i ?? 1; // Iteration counter for clinical resource-as-CreativeWork
			$schema_provider_condition_MedicalCondition_i = $schema_provider_condition_MedicalCondition_i ?? 1; // Iteration counter for condition
			$schema_provider_treatment_Service_i = $schema_provider_treatment_Service_i ?? 1; // Iteration counter for treatments and procedures

		$schema_provider_combined = uamswp_fad_schema_provider(
			array($page_id), // array // Required // List of IDs of the provider items
			$page_url, // string // Required // Page URL
			$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
			0, // int // Optional // Nesting level within the main schema
			array( 'MedicalBusiness', 'MedicalWebPage', 'Person' ), // array // Optional // List of the schema types to output
			$schema_provider_provider_MedicalWebPage_i, // int // Optional // Iteration counter for provider-as-MedicalWebPage
			$schema_provider_provider_MedicalBusiness_i, // int // Optional // Iteration counter for provider-as-MedicalBusiness
			$schema_provider_provider_Person_i, // int // Optional // Iteration counter for provider-as-Person
			$schema_provider_location_MedicalWebPage_i, // int // Optional // Iteration counter for location-as-MedicalWebPage
			$schema_provider_location_LocalBusiness_i, // int // Optional // Iteration counter for location-as-LocalBusiness
			$schema_provider_expertise_MedicalWebPage_i, // int // Optional // Iteration counter for area of expertise-as-MedicalWebPage
			$schema_provider_expertise_MedicalEntity_i, // int // Optional // Iteration counter for area of expertise-as-MedicalEntity
			$schema_provider_clinical_resource_MedicalWebPage_i, // int // Optional // Iteration counter for clinical resource-as-MedicalWebPage
			$schema_provider_clinical_resource_CreativeWork_i, // int // Optional // Iteration counter for clinical resource-as-CreativeWork
			$schema_provider_condition_MedicalCondition_i, // int // Optional // Iteration counter for condition
			$schema_provider_treatment_Service_i, // int // Optional // Iteration counter for treatments and procedures
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