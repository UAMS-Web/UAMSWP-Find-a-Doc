<?php

// Provider-specific schema
// Merge into $schema_base['@graph']

	$schema_MedicalProcedure = array(
		// WebPage
		array(
			'@type' => 'MedicalProcedure',
			'name' => 'foo',
			'additionalType' => array(
				'foo' // Replace 'foo' with URI-identified RDF class (mirroring sameAs) // Repeat as necessary
			),
			'alternateName' => array(
				'foo' // Replace 'foo' with alternate name // Repeat as necessary
			),
			'code' => (
				array( // Repeat as necessary
					'@id' => 'foo', // Replace 'foo' with URL relevant to code hashed with 'MedicalCode'
					'@type' => 'MedicalCode',
					'codeValue' => 'foo', // Replace 'foo' with relevant code (or the parent code)
					'codingSystem' => 'foo', // Replace 'foo' with relevant code set
					'name' => 'foo', // Replace 'foo' with name associated with code
					'url' => 'foo' // Replace 'foo' with URL associated with code
				)
			),
			'drug' => 'foo', // Only if subtype is TherapeuticProcedure (or its subtypes)
			'duplicateTherapy' => array( // Only if subtype is MedicalTherapy (or its subtypes)
				array(
					'@type' => 'MedicalTherapy', // Replace with values for the selected MedicalTherapy
					'foo' => 'bar'
				)
			),
			'procedureType' => array(
				'foo' // Replace foo with MedicalProcedureType (Enumeration Type) // Repeat as necessary
			),
			'relevantSpecialty' => array(
				'foo' // Replace foo with MedicalSpecialty (Enumeration Type) associated with the area of expertise // Repeat as necessary
			),
			'sameAs' => array(
				'foo' // Replace 'foo' with URI-identified RDF class (mirroring additionalType) // Repeat as necessary
			)
		)
	);

	$schema_MedicalTest = array(
		// WebPage
		array(
			'@type' => 'MedicalTest',
			'name' => 'foo',
			'additionalType' => array(
				'foo' // Replace 'foo' with URI-identified RDF class (mirroring sameAs) // Repeat as necessary
			),
			'alternateName' => array(
				'foo' // Replace 'foo' with alternate name // Repeat as necessary
			),
			'code' => (
				array( // Repeat as necessary
					'@id' => 'foo', // Replace 'foo' with URL relevant to code hashed with 'MedicalCode'
					'@type' => 'MedicalCode',
					'codeValue' => 'foo', // Replace 'foo' with relevant code (or the parent code)
					'codingSystem' => 'foo', // Replace 'foo' with relevant code set
					'name' => 'foo', // Replace 'foo' with name associated with code
					'url' => 'foo' // Replace 'foo' with URL associated with code
				)
			),
			'imagingTechnique' => array( // Only if subtype is ImagingTest
				'foo' // Replace with MedicalImagingTechnique enumeration member // Repeat as necessary
			)
			'relevantSpecialty' => array(
				'foo' // Replace foo with MedicalSpecialty (Enumeration Type) associated with the area of expertise // Repeat as necessary
			),
			'sameAs' => array(
				'foo' // Replace 'foo' with URI-identified RDF class (mirroring additionalType) // Repeat as necessary
			),
			'subTest' => array( // Only if subtype is MedicalTestPanel
				array( // Repeat as necessary
					'@type' => 'MedicalTest', // Replace with values for the selected MedicalTest // Replace 'MedicalTest' with relevant subtype, if necessary
					'foo' => 'bar'
				)
			),
			'tissueSample' => array( // Only if subtype is PathologyTest
				'foo' // Replace with name of tissue sample // Repeat as necessary
			),
			'usedToDiagnose' => array(
				array( // Populate values for related condition items, repeating as necessary
					'@type' => 'MedicalCondition',
					'foo' => 'bar'
				)
			),
			'usesDevice' => array(
				array( // Repeat as necessary
					'@type' => 'MedicalDevice',
					'name' => 'foo',
					'alternateName' => array(
						'foo' // Replace 'foo' with alternate name associated with device // Repeat as necessary
					),
					'code' => (
						array( // Repeat as necessary
							'@id' => 'foo', // Replace 'foo' with URL relevant to code hashed with 'MedicalCode'
							'@type' => 'MedicalCode',
							'codeValue' => 'foo', // Replace 'foo' with relevant code (or the parent code)
							'codingSystem' => 'foo', // Replace 'foo' with relevant code set
							'name' => 'foo', // Replace 'foo' with name associated with code
							'url' => 'foo' // Replace 'foo' with URL associated with code
						)
					)
				)
			)
		)
	);