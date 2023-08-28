<?php

// area of expertise location subpage-specific schema

// Integrate into array with '@type' => 'MedicalWebPage' in $schema_base['@graph'] related to the area of expertise

	$schema_graph_MedicalWebPage = array(
		// MedicalWebPage
		array(
			'mentions' => array(
				array( // Populate values for related provider items, repeating as necessary
					'@id' => 'MedicalClinic', // Replace 'MedicalClinic' with 'Hospital' as necessary
					'foo' => 'bar'
				)
			),
			'significantLink' => array(
				'foo', // Replace 'foo' with URL to related location profiles // Repeat as necessary
				'bar', // Replace 'bar' with URL to main location archive, if relevant
				'baz' // Replace 'baz' with URL to parent item's location list, if relevant
			)
		),
	);