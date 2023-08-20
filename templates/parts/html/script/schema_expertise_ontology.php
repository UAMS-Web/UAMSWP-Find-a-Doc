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
			'code' => (
				array(
					'@id' => 'https://taxonomy.nucc.org/?searchTerm=foo', // Replace 'foo' with taxonomy code
					'@type' => 'MedicalCode',
					'code' => 'foo', // Replace 'foo' with taxonomy code (or the parent code to specialization in the UAMS Health extension to the code set)
					'codingSystem' => 'Health Care Provider Taxonomy Code Set',
					'name' => 'foo' // Replace 'foo' with name associated with taxonomy code (not the clinical title)
				),
				array(
					'@id' => 'https://www.cms.gov/regulations-and-guidance/guidance/manuals/internet-only-manuals-ioms-items/cms019018#foo', // Replace 'foo' with specialty code (or the parent code to specialization in the UAMS Health extension to the code set)
					'@type' => 'MedicalCode',
					'code' => 'foo', // Replace 'foo' with specialty code (or the parent code to specialization in the UAMS Health extension to the code set)
					'codingSystem' => 'Centers for Medicare & Medicaid Services Specialty Codes',
					'name' => 'foo' // Replace 'foo' with name associated with taxonomy code (not the clinical title)
				)
			),
			'description' => 'foo', // Replace 'foo' with excerpt / short description
			'mainEntityOfPage' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'medicineSystem' => array(
				'foo' // Replace 'foo' with MedicineSystem Enumeration Type // Repeat as necessary
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