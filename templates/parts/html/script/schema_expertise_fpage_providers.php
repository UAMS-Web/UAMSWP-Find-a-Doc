<?php

// area of expertise provider subpage-specific schema

// Integrate into array with '@type' => 'MedicalWebPage' in $schema_base['@graph'] related to the area of expertise

	$schema_graph_MedicalWebPage = array(
		// MedicalWebPage
		array(
			'mentions' => array(
				array( // Populate values for related provider items, repeating as necessary
					'@id' => 'Person',
					'foo' => 'bar'
				)
			),
			'significantLink' => array(
				'foo', // Replace 'foo' with URL to related provider profiles // Repeat as necessary
				'bar', // Replace 'bar' with URL to main provider archive, if relevant
				'baz' // Replace 'baz' with URL to parent item's provider list, if relevant
			)
		),
	);