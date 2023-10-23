<?php

$schema_common_base = array(
	'@context' => 'https://schema.org/',
	'@graph' => array(
	)
);

// Add UAMS items to the array

	include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/uams.php' );

	// UAMSHealth.com as WebSite
	$schema_common_base['@graph'][] = $schema_base_website_uams_health;
