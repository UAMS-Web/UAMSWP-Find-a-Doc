<?php

// area of expertise descendant expertise subpage-specific schema

// Integrate into array with '@type' => 'MedicalWebPage' in $schema_base['@graph'] related to the area of expertise

	$schema_graph_MedicalWebPage = array(
		// MedicalWebPage
		array(
			'mentions' => array(
				array( // Populate values for descendant expertise items, repeating as necessary
					'@type' => 'MedicalEntity',
					'foo' => 'bar',
					'url' => array(
						'@id' => 'https://uamshealth.com/expertise/foo/bar/#URL', // Replace URL up to the hash with relevant URL
						'https://uamshealth.com/expertise/foo/bar/' // Replace URL up to the hash with relevant URL
					)
				)
			),
			'significantLink' => array(
				array( // Repeat as necessary
					'@id' => 'https://uamshealth.com/expertise/foo/bar/#URL' // Replace URL up to the hash with relevant URL
				)
			)
		),
	);