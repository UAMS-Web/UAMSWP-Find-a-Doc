<?php

// Provider-specific schema
// Merge into $schema_base['@graph']

	$schema_graph = array(
		// WebPage
		array(
			'@type' => 'MedicalWebPage',
			'@id' => 'https://uamshealth.com/provider/foo/#WebPage', // Replace 'foo' with provider profile slug
			'name' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#Name' // Replace 'foo' with provider profile slug
			),
			'author' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'breadcrumb' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#BreadcrumbList' // Replace 'foo' with provider profile slug
			),
			'creator' => array(
				'@id' => 'https://uams.edu/#CollegeOrUniversity'
			),
			'dateModified' => 'foo', // Replace 'foo' with date value in ISO 8601 date format.
			'datePublished' => 'foo', // Replace 'foo' with date value in ISO 8601 date format.
			'inLanguage' => 'English',
			'isPartOf' => array(
				'@id' => 'https://uamshealth.com/#WebSite'
			),
			'maintainer' => array(
				'@id' => 'https://uams.edu/#CollegeOrUniversity'
			),
			'medicalAudience' => array(
				array(
					'@type' => 'Patient',
					'name' => 'Patient',
					'geographicArea' => 'Arkansas'
				),
				'Clinician' // MedicalAudienceType (Enumeration Type) :: Clinician (Enumeration Member)
			),
			'mentions' => array(
				array(
					'@id' => 'https://uamshealth.com/provider/foo/#Physician' // Replace 'foo' with provider profile slug
				),
				array(
					'@id' => 'https://uamshealth.com/provider/foo/#Person' // Replace 'foo' with provider profile slug
				)
			),
			'primaryImageOfPage' => 'foo', // Replace 'foo' with URL of headshot
			'significantLink' => array(
				'foo', // Replace 'foo', 'bar', etc. with URLs to related ontology items
				'bar'
			),
			'sourceOrganization' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'url' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#URL', // Replace 'foo' with provider profile slug
				'https://uamshealth.com/provider/foo/' // Replace 'foo' with provider profile slug
			),
			'video' => 'foo' // Replace 'foo' with URL to featured video
		),
		// BreadcrumbList
		array(
			'@type' => 'BreadcrumbList',
			'@id' => 'https://uamshealth.com/provider/foo/#BreadcrumbList', // Replace 'foo' with provider profile slug
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
						'@id' => 'https://uamshealth.com/provider/foo/#WebPage' // Replace 'foo' with provider profile slug
					)
				)
			)
		),
		// Physician
		array(
			'@type' => 'Physician',
			'@id' => 'https://uamshealth.com/provider/foo/#Physician', // Replace 'foo' with provider profile slug
			'name' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#Name' // Replace 'foo' with provider profile slug
			),
			'aggregateRating' => array(
				array(
					'@type' => 'AggregateRating',
					'itemReviewed' => 'foo', // Replace 'foo' with @id of relevant Thing (Physician or Person) // Thing
					'ratingCount' => 'foo', // Replace 'foo' with relevant value // Integer (Data Type)
					'reviewCount' => 'foo', // Replace 'foo' with relevant value // Integer (Data Type)
				)
			),
			'availableService' => array(
				array( // Repeat as necessary
					'@type' => 'MedicalProcedure', // Replace 'MedicalProcedure' with more specific type if relevant
					'name' => 'foo', // Replace 'foo' with name of associated procedure
					'foo' => 'bar' // Replace 'foo' and 'bar' with necessary property/value pairs, adding more if necessary
				),
				array( // Repeat as necessary
					'@type' => 'MedicalTest', // Replace 'MedicalProcedure' with more specific type if relevant
					'name' => 'foo', // Replace 'foo' with name of associated test
					'foo' => 'bar' // Replace 'foo' and 'bar' with necessary property/value pairs, adding more if necessary
				)
			),
			'brand' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'hospitalAffiliation' => array(
				array( // Repeat for all associated locations
					'@type' => 'Hospital',
					'name' => 'foo', // Replace 'foo' with location name
					'address' => array(
						'@type' => 'PostalAddress',
						'addressCountry' = 'USA',
						'addressLocality' = 'foo', // Replace 'foo' with city
						'addressRegion' = 'Arkansas',
						'postalCode' = 'foo', // Replace 'foo' with ZIP code
						'streetAddress' = 'foo' // Replace 'foo' with street address
					),
					'areaServed' => array(
						'@type' => 'AdministrativeArea',
						'name' => 'Arkansas'
					),
					'brand' => array(
						'@id' => 'https://uamshealth.com/#MedicalOrganization' // Replace array with relevant Organization (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
					),
					'contactPoint' => array(
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'General information',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new and existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'foo', // Replace 'foo' with additional phone number label
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Fax',
							'faxNumber' => 'foo' // Replace 'foo' with fax number
						),
					),
					'description' => 'foo', // Replace 'foo' with location description
					'geo' => array(
						'@type' => 'GeoCoordinates',
						'latitude' => 'foo', // Replace 'foo' with latitude
						'longitude' => 'foo' // Replace 'foo' with longitude
					),
					'openingHoursSpecification' => array( // The opening hours of a certain place.
						array( // Repeat as necessary
							'@type' => 'OpeningHoursSpecification',
							'closes' => '23:59',
							'dayOfWeek' => array(
								'Monday',
								'Tuesday',
								'Wednesday',
								'Thursday',
								'Friday',
								'Saturday',
								'Sunday'
							),
							'opens' => '00:00'
						)
					),
					'parentOrganization' => array(
						'@id' => 'https://uamshealth.com/#MedicalOrganization' // Replace array with relevant Organization (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
					),
					'photo' => array(
						array( // Repeat for all photos include in location profile
							'@type' => 'ImageObject',
							'caption' => 'foo', // Replace 'foo' with alt text of image
							'url' => 'foo' // Replace 'foo' with URL of image
						)
					),
					'url' => 'foo' // Replace 'foo' with location profile URL
				)
			),
			'isAcceptingNewPatients' => 'foo', // Boolean (Data Type)
			'location' => array(
				array( // Repeat for all associated locations
					'@type' => 'MedicalClinic', // Replace 'MedicalClinic' with 'Hospital' if necessary
					'name' => 'foo', // Replace 'foo' with location name
					'address' => array(
						'@type' => 'PostalAddress',
						'addressCountry' = 'USA',
						'addressLocality' = 'foo', // Replace 'foo' with city
						'addressRegion' = 'Arkansas',
						'postalCode' = 'foo', // Replace 'foo' with ZIP code
						'streetAddress' = 'foo' // Replace 'foo' with street address
					),
					'areaServed' => array(
						'@type' => 'AdministrativeArea',
						'name' => 'Arkansas'
					),
					'brand' => array( // Keep if a UAMS location
						'@id' => 'https://uamshealth.com/#MedicalOrganization'
					),
					'contactPoint' => array(
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'General information',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new and existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'foo', // Replace 'foo' with additional phone number label
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Fax',
							'faxNumber' => 'foo' // Replace 'foo' with fax number
						),
					),
					'description' => 'foo', // Replace 'foo' with location description
					'geo' => array(
						'@type' => 'GeoCoordinates',
						'latitude' => 'foo', // Replace 'foo' with latitude
						'longitude' => 'foo' // Replace 'foo' with longitude
					),
					'openingHoursSpecification' => array( // The opening hours of a certain place.
						array( // Repeat as necessary
							'@type' => 'OpeningHoursSpecification',
							'closes' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'dayOfWeek' => 'foo', // Replace 'foo' necessary value // DayOfWeek (Enumeration Type)
							'opens' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'validFrom' => 'foo', // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
							'validThrough' => 'foo' // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
						)
					),
					'parentOrganization' => array( // Keep if a UAMS location
						'@id' => 'https://uamshealth.com/#MedicalOrganization'
					),
					'photo' => array(
						array( // Repeat for all photos include in location profile
							'@type' => 'ImageObject',
							'caption' => 'foo', // Replace 'foo' with alt text of image
							'url' => 'foo' // Replace 'foo' with URL of image
						)
					),
					'specialOpeningHoursSpecification' => array( // The special opening hours of a certain place. Use this to explicitly override general opening hours brought in scope by openingHoursSpecification or openingHours.
						array( // Repeat as necessary
							'@type' => 'OpeningHoursSpecification',
							'closes' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'dayOfWeek' => 'foo', // Replace 'foo' necessary value // DayOfWeek (Enumeration Type)
							'opens' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'validFrom' => 'foo', // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
							'validThrough' => 'foo' // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
						)
					),
					'url' => 'foo' // Replace 'foo' with location profile URL
				)
			),
			'medicalSpecialty' => array(
				'foo' // Replace foo with MedicalSpecialty (Enumeration Type) associated with the related area of expertise // Repeat as necessary
			),
			'parentOrganization' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'review' => array( // Include if NRC API allows for the content of the review to be loaded into this schema
				array(
					'@type' => 'Review',
					'foo' => 'bar' // Replace 'foo' and 'bar' with relevant property/value pairs as necessary
				)
			),
			'url' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#URL' // Replace 'foo' with provider profile slug
			),
		),
		// Person
		array(
			'@type' => 'Person',
			'@id' => 'https://uamshealth.com/provider/foo/#Person', // Replace 'foo' with provider profile slug
			'name' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#Name', // Replace 'foo' with provider profile slug
				'foo', // Replace 'foo' with long provider name (e.g., "Leonard H. McCoy Jr., M.D.")
			)
			'affiliation' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'alumniOf' => array(
				array( // Repeat as necessary
					'@type' => 'EducationalOrganization', // Replace with more specific type as relevant
					'name' => 'foo' // Replace 'foo' with name of the organization from which the provider received education/training
				)
			),
			'brand' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'description' => 'foo', // Replace 'foo' with provider's clinical short bio // Text (Data Type)
			'familyName' => 'foo', // Replace 'foo' with provider's last name // Text (Data Type)
			'gender' => 'foo', // Replace 'foo' with provider's gender // Text (Data Type)
			'givenName' 'foo', // Replace 'foo' with provider's first name // Text (Data Type)
			'hasCredential' => array(
				array( // Repeat as necessary
					'@type' => 'EducationalOccupationalCredential',
					'name' => 'foo' // Full name of degree or credential (e.g., 'Doctor of Medicine')
				)
			),
			'honorificPrefix' => 'foo', // Replace 'foo' with 'Dr.' if relevant // Text (Data Type)
			'honorificSuffix' => 'foo', // Replace 'foo' with provider's degree list // Text (Data Type)
			'image' => 'foo', // Replace 'foo' with provider's headshot URL // URL (Data Type)
			'jobTitle' => array(
				'foo' // Replace 'foo' with provider's clinical title (e.g., 'Orthopaedic surgeon') // Repeat as necessary // Text (Data Type)
			),
			'knowsLanguage' => array(
				array( // Repeat as necessary
					'@type' => 'Language',
					'name' => 'foo' // Replace 'foo' with provider's language
				)
			),
			'memberOf' => array(
				array( // Repeat as necessary
					'@type' => 'Organization',
					'name' => 'foo' // Replace 'foo' with provider's association organization
				)
			),
			'url' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#URL' // Replace 'foo' with provider profile slug
			),
			'workLocation' => array(
				array( // Repeat for all associated locations
					'@type' => 'MedicalClinic', // Replace 'MedicalClinic' with 'Hospital' if necessary
					'name' => 'foo', // Replace 'foo' with location name
					'address' => array(
						'@type' => 'PostalAddress',
						'addressCountry' = 'USA',
						'addressLocality' = 'foo', // Replace 'foo' with city
						'addressRegion' = 'Arkansas',
						'postalCode' = 'foo', // Replace 'foo' with ZIP code
						'streetAddress' = 'foo' // Replace 'foo' with street address
					),
					'areaServed' => array(
						'@type' => 'AdministrativeArea',
						'name' => 'Arkansas'
					),
					'brand' => array( // Keep if a UAMS location
						'@id' => 'https://uamshealth.com/#MedicalOrganization'
					),
					'contactPoint' => array(
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'General information',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new and existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'foo', // Replace 'foo' with additional phone number label
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Fax',
							'faxNumber' => 'foo' // Replace 'foo' with fax number
						),
					),
					'description' => 'foo', // Replace 'foo' with location description
					'geo' => array(
						'@type' => 'GeoCoordinates',
						'latitude' => 'foo', // Replace 'foo' with latitude
						'longitude' => 'foo' // Replace 'foo' with longitude
					),
					'openingHoursSpecification' => array( // The opening hours of a certain place.
						array( // Repeat as necessary
							'@type' => 'OpeningHoursSpecification',
							'closes' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'dayOfWeek' => 'foo', // Replace 'foo' necessary value // DayOfWeek (Enumeration Type)
							'opens' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'validFrom' => 'foo', // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
							'validThrough' => 'foo' // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
						)
					),
					'parentOrganization' => array( // Keep if a UAMS location
						'@id' => 'https://uamshealth.com/#MedicalOrganization'
					),
					'photo' => array(
						array( // Repeat for all photos include in location profile
							'@type' => 'ImageObject',
							'caption' => 'foo', // Replace 'foo' with alt text of image
							'url' => 'foo' // Replace 'foo' with URL of image
						)
					),
					'specialOpeningHoursSpecification' => array( // The special opening hours of a certain place. Use this to explicitly override general opening hours brought in scope by openingHoursSpecification or openingHours.
						array( // Repeat as necessary
							'@type' => 'OpeningHoursSpecification',
							'closes' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'dayOfWeek' => 'foo', // Replace 'foo' necessary value // DayOfWeek (Enumeration Type)
							'opens' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'validFrom' => 'foo', // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
							'validThrough' => 'foo' // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
						)
					),
					'url' => 'foo' // Replace 'foo' with location profile URL
				)
			),
			'worksFor' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			)
		)
	);