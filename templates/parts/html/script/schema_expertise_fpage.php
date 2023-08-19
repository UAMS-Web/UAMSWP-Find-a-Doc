<?php

// area of expertise general subpage-specific schema

// Integrate into array with '@type' => 'MedicalWebPage' in $schema_base['@graph'] related to the area of expertise

	$schema_graph_MedicalWebPage = array(
		// MedicalWebPage
		array(
			'mainEntity' => array(
				'foo' // Replace 'foo' with MedicalSpecialty (Enumeration Type) associated with the area of expertise // Repeat as necessary
			)
		)
	);

// Integrate into array with '@type' => 'BreadcrumbList' in $schema_base['@graph'] related to the area of expertise

	$schema_graph_BreadcrumbList = array(
		// BreadcrumbList
		array(
		)
	);