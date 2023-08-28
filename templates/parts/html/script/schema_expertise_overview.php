<?php

// area of expertise overview-specific schema
// Merge into $schema_base['@graph']

	$schema_graph = array(
		// MedicalWebPage
		array(
			'about' => array(
				array( // Populate values for related condition items, repeating as necessary
					'@type' => 'MedicalCondition',
					'foo' => 'bar'
				),
			),
			'mainEntity' => array(
				'foo' // Replace 'foo' with MedicalSpecialty (Enumeration Type) associated with the area of expertise // Repeat as necessary
			),
			'mentions' => array(
				array( // Populate values for related condition items, repeating as necessary
					'@id' => 'MedicalCondition',
					'foo' => 'bar'
				),
				array( // Populate values for related treatment/procedure items, repeating as necessary
					'@id' => 'MedicalProcedure',
					'foo' => 'bar'
				)
v			),
			'significantLink' => array(
				'foo' // Replace 'foo' with URL to fake subpage // Repeat as necessary
			)
		),
		// BreadcrumbList
		array(
		)
	);