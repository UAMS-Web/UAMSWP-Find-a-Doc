<?php

/*
 * Required vars:
 *
 * 	$schema_common_base
 * 	$page_id
 */

include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base_script.php' );

$schema_location = $schema_common_base;

// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.
$page_id = get_the_ID();

// Define Schema JSON item arrays for location profile as MedicalWebPage and LocalBusiness

	$node_identifier_list = $node_identifier_list ?? array(); // List of node identifiers (@id) already defined in the schema

	if ( function_exists('uamswp_fad_schema_location') ) {

		// Check argument variables

			$schema_location_provider_MedicalWebPage_i = $schema_location_provider_MedicalWebPage_i ?? 1; // Iteration counter for provider-as-MedicalWebPage
			$schema_location_provider_MedicalBusiness_i = $schema_location_provider_MedicalBusiness_i ?? 1; // Iteration counter for provider-as-MedicalBusiness
			$schema_location_provider_Person_i = $schema_location_provider_Person_i ?? 1; // Iteration counter for provider-as-Person
			$schema_location_location_MedicalWebPage_i = $schema_location_location_MedicalWebPage_i ?? 1; // Iteration counter for location-as-MedicalWebPage
			$schema_location_location_LocalBusiness_i = $schema_location_location_LocalBusiness_i ?? 1; // Iteration counter for location-as-LocalBusiness
			$schema_location_expertise_MedicalWebPage_i = $schema_location_expertise_MedicalWebPage_i ?? 1; // Iteration counter for area of expertise-as-MedicalWebPage
			$schema_location_expertise_MedicalEntity_i = $schema_location_expertise_MedicalEntity_i ?? 1; // Iteration counter for area of expertise-as-MedicalEntity
			$schema_location_clinical_resource_MedicalWebPage_i = $schema_location_clinical_resource_MedicalWebPage_i ?? 1; // Iteration counter for clinical resource-as-MedicalWebPage
			$schema_location_clinical_resource_CreativeWork_i = $schema_location_clinical_resource_CreativeWork_i ?? 1; // Iteration counter for clinical resource-as-CreativeWork
			$schema_location_condition_MedicalCondition_i = $schema_location_condition_MedicalCondition_i ?? 1; // Iteration counter for condition
			$schema_location_treatment_Service_i = $schema_location_treatment_Service_i ?? 1; // Iteration counter for treatments and procedures

		$schema_location_combined = uamswp_fad_schema_location(
			array($page_id), // List of IDs of the location items
			$page_url, // Page URL
			$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
			0, // Nesting level within the main schema
			$schema_location_location_MedicalWebPage_i, // int // Optional // Iteration counter for location-as-MedicalWebPage
			$schema_location_location_LocalBusiness_i, // int // Optional // Iteration counter for location-as-LocalBusiness
			$schema_location_provider_MedicalWebPage_i, // int // Optional // Iteration counter for provider-as-MedicalWebPage
			$schema_location_provider_MedicalBusiness_i, // int // Optional // Iteration counter for provider-as-MedicalBusiness
			$schema_location_provider_Person_i, // int // Optional // Iteration counter for provider-as-Person
			$schema_location_expertise_MedicalWebPage_i, // int // Optional // Iteration counter for area of expertise-as-MedicalWebPage
			$schema_location_expertise_MedicalEntity_i, // int // Optional // Iteration counter for area of expertise-as-MedicalEntity
			$schema_location_clinical_resource_MedicalWebPage_i, // int // Optional // Iteration counter for clinical resource-as-MedicalWebPage
			$schema_location_clinical_resource_CreativeWork_i, // int // Optional // Iteration counter for clinical resource-as-CreativeWork
			$schema_location_condition_MedicalCondition_i, // int // Optional // Iteration counter for condition
			$schema_location_treatment_Service_i, // int // Optional // Iteration counter for treatments and procedures
			$location_schema_fields // Pre-existing field values array so duplicate calls can be avoided
		);

	} else {

		$schema_location_combined = null;

	}

// Add location schema arrays to the base schema array

	// location profile as MedicalWebPage

		if ( isset($schema_location_combined['MedicalWebPage']) ) {

			$schema_location['@graph'][] = $schema_location_combined['MedicalWebPage'];

		}

	// location profile as LocalBusiness

		if ( isset($schema_location_combined['LocalBusiness']) ) {

			$schema_location['@graph'][] = $schema_location_combined['LocalBusiness'];

		}

// Construct the schema JSON script tag

	uamswp_fad_schema_construct($schema_location);