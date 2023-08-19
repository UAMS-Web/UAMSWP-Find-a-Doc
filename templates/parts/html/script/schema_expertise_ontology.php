<?php

// area of expertise ontology type-specific schema
// Merge into $schema_base['@graph']

	$schema_graph = array(
		// MedicalWebPage
		array(
			'about' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#Name', // Replace URL up to the hash with relevant URL
				array( // Populate values for related condition items, repeating as necessary
					'@type' => 'MedicalCondition',
					'foo' => 'bar'
				),
			),
			'mainEntity' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#Name' // Replace URL up to the hash with relevant URL
			),
			'mentions' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#Name' // Replace URL up to the hash with relevant URL
			)
		),
		// BreadcrumbList
		array(
		),
		// MedicalEntity
		array(
			'@type' => 'MedicalEntity',
			'@id' => 'https://uamshealth.com/expertise/foo/#MedicalEntity', // Replace URL up to the hash with relevant URL
			'name' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#Name', // Replace URL up to the hash with relevant URL
				'foo', // Replace 'foo' with title of area of expertise
			),
			'additionalType' => array(
				'foo' // Replace 'foo' with URI-identified RDF class (mirroring sameAs) // Repeat as necessary
			),
			'alternateName' => array(
				'foo' // Replace 'foo' with alternate name // Repeat as necessary
			),
			'code' => array(
				'@id' => 'https://taxonomy.nucc.org/?searchTerm=foo', // Replace 'foo' with taxonomy code (clinical title slug)
				'@type' => 'MedicalCode',
				'code' => 'foo', // Replace 'foo' with taxonomy code (currently, the clinical title slug)
				'codingSystem' => 'Health Care Provider Taxonomy Code Set',
				'name' => 'foo' // Replace 'foo' with taxonomy name (not the clinical title)
			),
			'description' => 'foo', // Replace 'foo' with excerpt / short description
			'mainEntityOfPage' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'medicineSystem' => array(
				'foo' // Replace 'foo' with MedicineSystem Enumeration Type
			),
			'relevantSpecialty' => array(
				'foo' // Replace foo with MedicalSpecialty (Enumeration Type) associated with the area of expertise // Repeat as necessary
			),
			'sameAs' => array(
				'foo' // Replace 'foo' with URI-identified RDF class (mirroring additionalType) // Repeat as necessary
			),
			'subjectOf' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#URL' // Replace URL up to the hash with relevant URL
			),
			'url' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#URL' // Replace URL up to the hash with relevant URL
			)
		)
	);