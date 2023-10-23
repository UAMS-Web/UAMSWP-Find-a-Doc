<?php

// Common property values

	include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

// List of existing node identifiers

	$node_identifier_list = $node_identifier_list ?? array();

// UAMSHealth.com

	$schema_base_website_uams_health = uamswp_fad_schema_uamshealth_website(
		$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
		0 // int // Optional // Nesting level within the main schema
	);
