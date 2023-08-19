<?php

// area of expertise content-specific schema
// Merge into $schema_base['@graph']

	$schema_graph = array(
		// MedicalWebPage
		array(
			'mentions' => array( // Populate values referencing the parent area of expertise
				'@type' => 'MedicalEntity',
				'foo' => 'bar'
			)
		),
		// BreadcrumbList
		array(
		)
	);