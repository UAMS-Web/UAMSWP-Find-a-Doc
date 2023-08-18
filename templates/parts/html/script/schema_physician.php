<?php

// Base schema to be used on all pages

	$schema_base = array(
		'@context' => 'https://schema.org/',
		'@graph' => array(
			array(
				'@type' => 'CollegeOrUniversity',
				'@id' => 'https://uams.edu/#CollegeOrUniversity',
				'name' = 'University of Arkansas for Medical Sciences',
				'address' => 'foo',
				'alternateName' = 'UAMS',
				'brand' => 'foo',
				'contactPoint' => 'foo',
				'description' => 'foo',
				'location' => array(
					'@type' => 'Place',
				),
				'logo' => 'foo',
				'nonprofitStatus' => 'foo',
				'sameAs' => 'https://en.wikipedia.org/wiki/University_of_Arkansas_for_Medical_Sciences',
				'slogan' => 'foo',
				'subOrganization' => array(
					'@id' => 'https://uamshealth.com/#MedicalOrganization'
				),
				'telephone' => 'foo',
				'url' => 'https://uams.edu'
			),
			array(
				'@type' => 'MedicalOrganization',
				'@id' => 'https://uamshealth.com/#MedicalOrganization',
				'name' => 'UAMS Health',
				'address' => 'foo',
				'contactPoint' => 'foo',
				'contactPoints' => 'foo',
				'description' => 'foo',
				'location' => 'foo',
				'logo' => 'foo',
				'nonprofitStatus' => 'foo',
				'parentOrganization' => array(
					'@id' => 'https://uams.edu/#CollegeOrUniversity'
				),
				'slogan' => 'foo',
				'url' => 'https://uamshealth.com/'
			),
			array(
				'@type' => 'WebSite',
				'@id' => 'https://uamshealth.com/#WebSite',
				'name' => 'UAMS Health',
				'audience' => array(
					array(
						'@type' => 'Audience',
						'name' => 'Patients',
						'geographicArea' => 'Arkansas'
					),
					array(
						'@type' => 'Audience',
						'name' => 'Referring physicians',
						'geographicArea' => 'Arkansas'
					)
				),
				'creator' => array(
					'@id' => 'https://uams.edu/#CollegeOrUniversity'
				),
				'inLanguage' => 'English',
				'sourceOrganization' => array(
					'@id' => 'https://uamshealth.com/#MedicalOrganization'
				),
				'url' => 'https://uamshealth.com'
			),

		),
	);

// Provider-specific schema
// Merge into $schema_base['@graph']

	$schema_graph = array(
		array(
			'@type' => 'WebPage',
			'@id' => 'https://uamshealth.com/provider/foo/#WebPage',
			'name' => 'foo',
			'audience' => array(
				array(
					'@type' => 'Audience',
					'name' => 'Patients',
					'geographicArea' => 'Arkansas'
				),
				array(
					'@type' => 'Audience',
					'name' => 'Referring physicians',
					'geographicArea' => 'Arkansas'
				)
			),
			'author' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'breadcrumb' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#BreadcrumbList'
			),
			'creator' => array(
				'@id' => 'https://uams.edu/#CollegeOrUniversity'
			),
			'dateModified' => 'foo',
			'datePublished' => 'foo',
			'inLanguage' => 'English',
			'isPartOf' => array(
				'@id' => 'https://uamshealth.com/#WebSite'
			),
			'maintainer' => array(
				'@id' => 'https://uams.edu/#CollegeOrUniversity'
			),
			'mentions' => array(
				array(
					'@id' => 'https://uamshealth.com/provider/foo/#Physician'
				),
				array(
					'@id' => 'https://uamshealth.com/provider/foo/#Person'
				)
			),
			'primaryImageOfPage' => 'foo',
			'significantLink' => 'foo',
			'sourceOrganization' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'url' => 'https://uamshealth.com/provider/foo/',
			'video' => 'foo'
		),
		array(
			'@type' => 'BreadcrumbList',
			'@id' => 'https://uamshealth.com/provider/foo/#BreadcrumbList',
			'itemListElement' => array(
				array(
					'@type' => 'ListItem',
					'position' => 1,
					'item' => array(
						'@type' => 'WebPage',
						'@id' => 'https://uamshealth.com',
						'url' => 'https://uamshealth.com',
						'name' => 'UAMS Health'
					)
				),
				array(
					'@type' => 'ListItem',
					'position' => 2,
					'item' => array(
						'@type' => 'WebPage',
						'@id' => 'https://uamshealth.com/provider/',
						'url' => 'https://uamshealth.com/provider/',
						'name' => 'Providers'
					)
					),
				array(
					'@type' => 'ListItem',
					'position' => 3,
					'item' => array(
						'@id' => 'https://uamshealth.com/provider/foo/#WebPage'
					)
				)
			)
		),
		array(
			'@type' => 'Physician',
			'@id' => 'https://uamshealth.com/provider/foo/#Physician',
			'name' => 'foo',
			'aggregateRating' => array(
				array(
					'@type' => 'AggregateRating',
					'foo' => 'bar'
				)
			),
			'availableService' => array(
				array(
					'@type' => 'MedicalProcedure',
					'foo' => 'bar'
				),
				array(
					'@type' => 'MedicalTest',
					'foo' => 'bar'
				)
			),
			'brand' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'hospitalAffiliation' => array(
				array(
					'@type' => 'Hospital',
					'foo' => 'bar'
				)
			),
			'isAcceptingNewPatients' => 'foo', // Boolean (Data Type)
			'location' => array(
				array(
					'@type' => 'MedicalClinic',
					'address' => 'foo',
					'areaServed' => array(
						'@type' => 'AdministrativeArea',
						'name' => 'Arkansas'
					),
					'brand' => array(
						array(
							'@id' => 'https://uamshealth.com/#MedicalOrganization'
						),
						array(
							'@type' => 'MedicalOrganization',
							'name' => 'Arkansas Children\'s',
							'url' => 'https://www.archildrens.org/'
						),
					),
					'contactPoint' => 'foo',
					'containedInPlace' => 'foo',
					'containsPlace' => 'foo',
					'department' => 'foo',
					'description' => 'foo',
					'disambiguatingDescription' => 'foo',
					'faxNumber' => 'foo',
					'geo' => 'foo',
					'healthPlanNetworkId' => 'foo',
					'image' => 'foo',
					'latitude' => 'foo',
					'logo' => 'foo',
					'longitude' => 'foo',
					'name' => 'foo',
					'openingHours' => 'foo',
					'openingHoursSpecification' => 'foo',
					'parentOrganization' => 'foo',
					'photo' => 'foo',
					'potentialAction' => 'foo',
					'serviceArea' => 'foo',
					'smokingAllowed' => 'foo',
					'specialOpeningHoursSpecification' => 'foo',
					'telephone' => 'foo',
					'url' => 'foo'
				)
			),
			'medicalSpecialty' => array(
				'foo' => 'foo', // MedicalSpecialty (Enumeration Type)
				'bar' // MedicalSpecialty (Enumeration Type)
			),
			'parentOrganization' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'review' => array(
				array(
					'@type' => 'Review',
					'foo' => 'bar'
				)
			),
			'url' => 'https://uamshealth.com/provider/foo/'
		),
		array(
			'@type' => 'Person',
			'@id' => 'https://uamshealth.com/provider/foo/#Person',
			'name' => 'foo',
			'affiliation' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'alumniOf' => array(
				'@type' => 'EducationalOrganization',
				'name' => 'foo'
			),
			'award' => array(
				'foo', // Text (Data Type)
				'bar' // Text (Data Type)
			),
			'brand' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'description' => 'foo', // Text (Data Type)
			'familyName' => 'foo', // Text (Data Type)
			'gender' => 'foo', // Text (Data Type)
			'givenName' 'foo', // Text (Data Type)
			'hasCredential' => array(
				array(
					'@type' => 'EducationalOccupationalCredential',
					'name' => 'foo' // Full name of degree or credential
				)
			),
			'honorificPrefix' => 'foo', // Text (Data Type)
			'honorificSuffix' => 'foo', // Text (Data Type)
			'image' => 'foo', // URL (Data Type)
			'jobTitle' => array(
				'foo', // Text (Data Type)
				'bar' // Text (Data Type)
			),
			'knowsLanguage' => array(
				array(
					'@type' => 'Language',
					'name' => 'foo'
				)
			),
			'memberOf' => array(
				array(
					'@type' => 'Organization',
					'name' => 'foo'
				)
			),
			'url' => 'https://uamshealth.com/provider/foo/',
			'workLocation' => array(
				array(
					'@type' => 'MedicalClinic',
					'address' => 'foo',
					'areaServed' => array(
						'@type' => 'AdministrativeArea',
						'name' => 'Arkansas'
					),
					'brand' => 'foo',
					'contactPoint' => 'foo',
					'containedInPlace' => 'foo',
					'containsPlace' => 'foo',
					'department' => 'foo',
					'description' => 'foo',
					'disambiguatingDescription' => 'foo',
					'faxNumber' => 'foo',
					'geo' => 'foo',
					'healthPlanNetworkId' => 'foo',
					'image' => 'foo',
					'latitude' => 'foo',
					'logo' => 'foo',
					'longitude' => 'foo',
					'name' => 'foo',
					'openingHours' => 'foo',
					'openingHoursSpecification' => 'foo',
					'parentOrganization' => 'foo',
					'photo' => 'foo',
					'potentialAction' => 'foo',
					'serviceArea' => 'foo',
					'smokingAllowed' => 'foo',
					'specialOpeningHoursSpecification' => 'foo',
					'telephone' => 'foo',
					'url' => 'foo'
				)
			),
			'worksFor' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			)
		)
	);