<?php

// area of expertise location subpage-specific schema

// Integrate into array with '@type' => 'MedicalWebPage' in $schema_base['@graph'] related to the area of expertise

	$schema_graph_MedicalWebPage = array(
		// MedicalWebPage
		array(
			'mentions' => array(
				array( // Populate values for related location items, repeating as necessary
					'@type' => 'MedicalClinic', // Replace 'MedicalClinic' with 'Hospital' as necessary
					'foo' => 'bar',
					'url' => array(
						'@id' => 'https://uamshealth.com/location/foo/#URL', // Replace URL up to the hash with relevant URL
						'https://uamshealth.com/location/foo/' // Replace URL up to the hash with relevant URL
					)
				)
			),
			'significantLink' => array(
				array( // Repeat as necessary
					'@id' => 'https://uamshealth.com/provider/foo/#URL' // Replace URL up to the hash with relevant URL
				)
			)
		),
	);