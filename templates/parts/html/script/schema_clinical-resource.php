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

		// Check argument variables

			$schema_clinical_resource_provider_MedicalWebPage_i = $schema_clinical_resource_provider_MedicalWebPage_i ?? 1; // Iteration counter for provider-as-MedicalWebPage
			$schema_clinical_resource_provider_MedicalBusiness_i = $schema_clinical_resource_provider_MedicalBusiness_i ?? 1; // Iteration counter for provider-as-MedicalBusiness
			$schema_clinical_resource_provider_Person_i = $schema_clinical_resource_provider_Person_i ?? 1; // Iteration counter for provider-as-Person
			$schema_clinical_resource_location_MedicalWebPage_i = $schema_clinical_resource_location_MedicalWebPage_i ?? 1; // Iteration counter for location-as-MedicalWebPage
			$schema_clinical_resource_location_LocalBusiness_i = $schema_clinical_resource_location_LocalBusiness_i ?? 1; // Iteration counter for location-as-LocalBusiness
			$schema_clinical_resource_expertise_MedicalWebPage_i = $schema_clinical_resource_expertise_MedicalWebPage_i ?? 1; // Iteration counter for area of expertise-as-MedicalWebPage
			$schema_clinical_resource_expertise_MedicalEntity_i = $schema_clinical_resource_expertise_MedicalEntity_i ?? 1; // Iteration counter for area of expertise-as-MedicalEntity
			$schema_clinical_resource_clinical_resource_MedicalWebPage_i = $schema_clinical_resource_clinical_resource_MedicalWebPage_i ?? 1; // Iteration counter for clinical resource-as-MedicalWebPage
			$schema_clinical_resource_clinical_resource_CreativeWork_i = $schema_clinical_resource_clinical_resource_CreativeWork_i ?? 1; // Iteration counter for clinical resource-as-CreativeWork
			$schema_clinical_resource_condition_MedicalCondition_i = $schema_clinical_resource_condition_MedicalCondition_i ?? 1; // Iteration counter for condition
			$schema_clinical_resource_treatment_Service_i = $schema_clinical_resource_treatment_Service_i ?? 1; // Iteration counter for treatments and procedures

		$schema_clinical_resource_combined = uamswp_fad_schema_clinical_resource(
			array($page_id), // array // Required // List of IDs of the clinical resource items
			$page_url, // string // Required // Page URL
			$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
			0, // int // Optional // Nesting level within the main schema
			$schema_clinical_resource_clinical_resource_MedicalWebPage_i, // int // Optional // Iteration counter for clinical resource-as-MedicalWebPage
			$schema_clinical_resource_clinical_resource_CreativeWork_i, // int // Optional // Iteration counter for clinical resource-as-CreativeWork
			$schema_clinical_resource_provider_MedicalWebPage_i, // int // Optional // Iteration counter for provider-as-MedicalWebPage
			$schema_clinical_resource_provider_MedicalBusiness_i, // int // Optional // Iteration counter for provider-as-MedicalBusiness
			$schema_clinical_resource_provider_Person_i, // int // Optional // Iteration counter for provider-as-Person
			$schema_clinical_resource_location_MedicalWebPage_i, // int // Optional // Iteration counter for location-as-MedicalWebPage
			$schema_clinical_resource_location_LocalBusiness_i, // int // Optional // Iteration counter for location-as-LocalBusiness
			$schema_clinical_resource_expertise_MedicalWebPage_i, // int // Optional // Iteration counter for area of expertise-as-MedicalWebPage
			$schema_clinical_resource_expertise_MedicalEntity_i, // int // Optional // Iteration counter for area of expertise-as-MedicalEntity
			$schema_clinical_resource_condition_MedicalCondition_i, // int // Optional // Iteration counter for condition
			$schema_clinical_resource_treatment_Service_i, // int // Optional // Iteration counter for treatments and procedures
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