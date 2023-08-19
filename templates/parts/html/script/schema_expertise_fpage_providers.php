<?php

// area of expertise provider subpage-specific schema

// Integrate into array with '@type' => 'MedicalWebPage' in $schema_base['@graph'] related to the area of expertise

	$schema_graph_MedicalWebPage = array(
		// MedicalWebPage
		array(
			'mentions' => array(
				array( // Populate values for related provider items, repeating as necessary
					'@type' => 'Person',
					'foo' => 'bar',
					'url' => array(
						'@id' => 'https://uamshealth.com/provider/foo/#URL', // Replace URL up to the hash with relevant URL
						'https://uamshealth.com/provider/foo/' // Replace URL up to the hash with relevant URL
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