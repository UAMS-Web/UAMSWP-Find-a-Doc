<?php

// Common property values

	include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

// List of existing node identifiers

	$node_identifier_list = $node_identifier_list ?? array();

// UAMSHealth.com

	// URL

		$schema_base_org_uams_health_url = user_trailingslashit( get_site_url() );
		$schema_base_org_uams_health_url_trailingslashit = trailingslashit( get_site_url() );

	// Base array

		$schema_base_website_uams_health = array(
			'@type' => 'WebSite'
		);

	// @id

		$schema_base_website_uams_health['@id'] = $schema_base_org_uams_health_url . '#' . $schema_base_website_uams_health['@type'];
		$node_identifier_list[] = $schema_base_website_uams_health['@id']; // Add to the list of existing node identifiers

		// Define reference to the @id

			if (
				isset($schema_base_website_uams_health['@id'])
				&&
				!empty($schema_base_website_uams_health['@id'])
			) {

				$schema_base_website_uams_health_ref = uamswp_fad_schema_node_references(array($schema_base_website_uams_health));

			}

	// name

		$schema_base_website_uams_health['name'] = 'UAMS Health';

	// audience

		$schema_base_website_uams_health['audience'] = $schema_common_audience;

	// creator

		$schema_base_website_uams_health['creator'] = $schema_default_brand_organization_credit;

	// inLanguage

		$schema_base_website_uams_health['inLanguage'] = array(
			'@id' => $schema_base_org_uams_health_url . '#inLanguage',
			'@type' => 'Language',
			'alternateName' => 'en',
			'name' => 'English',
			'sameAs' => 'https://www.wikidata.org/wiki/Q1860'
		);
		$node_identifier_list[] = $schema_base_website_uams_health['inLanguage']['@id']; // Add to the list of existing node identifiers

		// Define reference to the @id

			$schema_base_website_uams_health_inLanguage = $schema_base_website_uams_health['inLanguage'] ?? array();

			if (
				isset($schema_base_website_uams_health_inLanguage['@id'])
				&&
				!empty($schema_base_website_uams_health_inLanguage['@id'])
			) {

				$schema_base_website_uams_health_inLanguage_ref = uamswp_fad_schema_node_references(array($schema_base_website_uams_health_inLanguage));

			}

	// sourceOrganization

		$schema_base_website_uams_health['sourceOrganization'] = $schema_default_brand_organization_credit;

	// url

		$schema_base_website_uams_health['url'] = $schema_base_org_uams_health_url;

		// Define reference to this 'url' property

			$schema_base_website_uams_health_url = $schema_base_website_uams_health['url'] ?? array();

		// Set value of 'url' property of UAMS Health 'MedicalOrganization' item with this 'url' reference

			$schema_base_org_uams_health['url'] = $schema_base_website_uams_health_url;
