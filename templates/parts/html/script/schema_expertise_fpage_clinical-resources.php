<?php

// area of expertise clinical resource subpage-specific schema

// Integrate into array with '@type' => 'MedicalWebPage' in $schema_base['@graph'] related to the area of expertise

	$schema_graph_MedicalWebPage = array(
		// MedicalWebPage
		array(
			'mentions' => array(
				array( // Populate values for related clinical resource items, repeating as necessary
					'@id' => 'CreativeWork', // Replace 'CreativeWork' as relevant for each related clinical resource
					'foo' => 'bar'
				)
			),
			'significantLink' => array(
				'foo', // Replace 'foo' with URL to related clinical resource items // Repeat as necessary
				'bar', // Replace 'bar' with URL to main clinical resource archive, if relevant
				'baz' // Replace 'baz' with URL to parent item's clinical resource list, if relevant
			)
		),
	);