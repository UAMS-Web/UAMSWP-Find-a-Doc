<?php

// area of expertise descendant expertise subpage-specific schema

// Integrate into array with '@type' => 'MedicalWebPage' in $schema_base['@graph'] related to the area of expertise

	$schema_graph_MedicalWebPage = array(
		// MedicalWebPage
		array(
			'mentions' => array(
				array( // Populate values for descendant expertise items, repeating as necessary
					'@id' => 'MedicalWebPage',
					'foo' => 'bar'
				)
			),
			'significantLink' => array(
				'foo', // Replace 'foo' with URL to descendant expertise profiles // Repeat as necessary
				'bar', // Replace 'bar' with URL to main expertise archive, if relevant
				'baz' // Replace 'baz' with URL to parent item's expertise list, if relevant
			)
		),
	);