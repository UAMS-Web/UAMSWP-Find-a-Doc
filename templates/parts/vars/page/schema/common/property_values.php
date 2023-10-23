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

	// UAMS Health Website URL

		$schema_base_org_uams_health_url = user_trailingslashit( get_site_url() );
		$schema_base_org_uams_health_url_trailingslashit = trailingslashit( $schema_base_org_uams_health_url );

	// Default UAMS Brand Organizations

		// Default Clinical UAMS Brand Organization

			$schema_default_brand_organization_clinical = uamswp_fad_schema_default_brand_organization(
				'clinical' // string enum('affiliation', 'clinical', 'copyright', 'credit', 'locationcreated') // Required // The suffix of the relevant Default UAMS Brand Organizations field
			) ?? array();

		// Default UAMS Brand Organizations as Affiliations

			$schema_default_brand_organization_affiliation = uamswp_fad_schema_default_brand_organization(
				'affiliation' // string enum('affiliation', 'clinical', 'copyright', 'credit', 'locationcreated') // Required // The suffix of the relevant Default UAMS Brand Organizations field
			) ?? array();

		// Default UAMS Brand Organizations to Credit

			$schema_default_brand_organization_credit = uamswp_fad_schema_default_brand_organization(
				'credit' // string enum('affiliation', 'clinical', 'copyright', 'credit', 'locationcreated') // Required // The suffix of the relevant Default UAMS Brand Organizations field
			) ?? array();

			// Name(s) of Default UAMS Brand Organizations to Credit

				$schema_default_brand_organization_credit_name = null;

				if ( $schema_default_brand_organization_credit ) {

					if ( is_array($schema_default_brand_organization_credit) ) {

						if ( array_is_list($schema_default_brand_organization_credit) ) {

							$schema_default_brand_organization_credit_name = array();

							foreach ( $schema_default_brand_organization_credit_name as $item ) {

								if (
									isset($item['name'])
									&&
									!empty($item['name'])
								) {

									$schema_default_brand_organization_credit_name[] = $item['name'];

								}

							}

							uamswp_fad_flatten_multidimensional_array($schema_default_brand_organization_credit_name);

						} else {

							if (
								isset($schema_default_brand_organization_credit['name'])
								&&
								!empty($schema_default_brand_organization_credit['name'])
							) {

								$schema_default_brand_organization_credit_name = $schema_default_brand_organization_credit['name'];

							}

						}

					} else {

						$schema_default_brand_organization_credit_name = $schema_default_brand_organization_credit;

					}

				}

		// Default UAMS Brand Organizations as the Locations Where the Website and Its Contents Were Created

			$schema_default_brand_organization_locationcreated = uamswp_fad_schema_default_brand_organization(
				'locationcreated' // string enum('affiliation', 'clinical', 'copyright', 'credit', 'locationcreated') // Required // The suffix of the relevant Default UAMS Brand Organizations field
			) ?? array();

		// Default UAMS Brand Organizations as Copyright Holder

			$schema_default_brand_organization_copyright = uamswp_fad_schema_default_brand_organization(
				'copyright' // string enum('affiliation', 'clinical', 'copyright', 'credit', 'locationcreated') // Required // The suffix of the relevant Default UAMS Brand Organizations field
			) ?? array();
