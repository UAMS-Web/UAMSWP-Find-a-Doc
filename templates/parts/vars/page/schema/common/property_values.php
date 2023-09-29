<?php

// Common schema values

	// Arkansas as State

		$schema_common_arkansas = array(
			'@type' => 'State',
			'alternateName' => array(
				'AR',
				'Ark.'
			),
			'name' => 'Arkansas',
			'sameAs' => 'https://www.wikidata.org/wiki/Q1612'
		);

	// USA as Country

		$schema_common_usa = array(
			'@type' => 'Country',
			'alternateName' => array(
				'U.S.',
				'U.S.A.',
				'United States of America',
				'USA'
			),
			'name' => 'US',
			'sameAs' => 'https://www.wikidata.org/wiki/Q30',
		);

	// audience (untyped)

		$schema_common_audience = array( // Expected type: 'Audience'
			array(
				'@type' => 'Patient', // Thing > Intangible > Audience > PeopleAudience > MedicalAudience > Patient
				'geographicArea' => $schema_common_arkansas,
				'sameAs' => 'https://www.wikidata.org/wiki/Q181600' // Wikidata entry for 'patient'
			),
			array(
				'@type' => 'MedicalAudience', // Thing > Intangible > Audience > PeopleAudience > MedicalAudience
				'additionalType' => 'https://www.wikidata.org/wiki/Q12722854', // Wikidata entry for 'sick person'
				'audienceType' => 'sick person', // DataType > Text
				'geographicArea' => $schema_common_arkansas,
				'sameAs' => 'https://www.wikidata.org/wiki/Q12722854' // Wikidata entry for 'sick person'
			),
			array(
				'@type' => 'MedicalAudience', // Thing > Intangible > Audience > PeopleAudience > MedicalAudience
				'additionalType' => array(
					'https://www.wikidata.org/wiki/Q5133860', // Wikidata entry for 'clinician'
					'https://www.wikidata.org/wiki/Q6500773', // Wikidata entry for 'general practitioner'
					'https://www.wikidata.org/wiki/Q55379489' // Wikidata entry for 'primary care physician'
				),
				'audienceType' => 'referring clinician', // DataType > Text
				'geographicArea' => $schema_common_arkansas
			)
		);

	// medicalAudience

		$schema_common_medicalAudience = array_merge( // Expected type: 'MedicalAudience' or 'MedicalAudienceType'
			$schema_common_audience,
			array(
				array(
					'@type' => 'MedicalAudienceType', // Thing > Intangible > Enumeration > MedicalEnumeration > MedicalAudienceType
					'geographicArea' => $schema_common_arkansas,
					'name' => 'Clinician' // MedicalAudienceType (Enumeration Type) :: Clinician (Enumeration Member)
				)
			)
		);
