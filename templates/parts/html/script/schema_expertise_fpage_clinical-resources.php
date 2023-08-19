<?php

// area of expertise clinical resource subpage-specific schema

// Integrate into array with '@type' => 'MedicalWebPage' in $schema_base['@graph'] related to the area of expertise

	$schema_graph_MedicalWebPage = array(
	// MedicalWebPage
	array(
		'mentions' => array(
			array( // Populate values for related clinical resource items, repeating as necessary
				'@type' => 'CreativeWork', // Replace 'CreativeWork' as relevant for each related clinical resource
				'foo' => 'bar',
				'url' => array(
					'@id' => 'https://uamshealth.com/clinical-resource/foo/#URL', // Replace URL up to the hash with relevant URL
					'https://uamshealth.com/clinical-resource/foo/' // Replace URL up to the hash with relevant URL
				)
			)
		),
		'significantLink' => array(
			array( // Repeat as necessary
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#URL' // Replace URL up to the hash with relevant URL
			)
		)
	),
);