<?php

// area of expertise overview-specific schema
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
				'@id' => 'https://uamshealth.com/expertise/foo/#Name', // Replace URL up to the hash with relevant URL
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
		),
		// Person
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
			'image' => array(
				array( // Featured image of area of expertise
					'@type' => 'ImageObject',
					'caption' => 'foo', // Replace 'foo' with the image's alt text
					'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
					'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
					'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
					'height' => 'foo', // Replace 'foo' with the image's height
					'representativeOfPage' => true,
					'width' => 'foo' // Replace 'foo' with the image's width
				),
				array( // Header background image
					'@type' => 'ImageObject',
					'caption' => 'foo', // Replace 'foo' with the image's alt text
					'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
					'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
					'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
					'height' => 'foo', // Replace 'foo' with the image's height
					'representativeOfPage' => true,
					'width' => 'foo' // Replace 'foo' with the image's width
				),
				array( // Featured image of fake subpage // Repeat as necessary
					'@type' => 'ImageObject',
					'caption' => 'foo', // Replace 'foo' with the image's alt text
					'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
					'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
					'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
					'height' => 'foo', // Replace 'foo' with the image's height
					'representativeOfPage' => true,
					'width' => 'foo' // Replace 'foo' with the image's width
				)
			),
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