<?php

// Location-specific schema
// Merge into $schema_base['@graph']

	$schema_graph = array(
		// MedicalWebPage
		array(
			'@type' => 'MedicalWebPage',
			'@id' => 'https://uamshealth.com/location/foo/#MedicalWebPage', // Replace 'foo' with location item slug
			'name' => array(
				'@id' => 'https://uamshealth.com/location/foo/#Name' // Replace 'foo' with location item slug
			),
			'headline' => array(
				'@id' => 'https://uamshealth.com/location/foo/#Name' // Replace 'foo' with location item slug
			),
			'about' => array(
				'@id' => 'https://uamshealth.com/location/foo/#Thing' // Replace 'foo' with location item slug
			),
			'breadcrumb' => array(
				'@id' => 'https://uamshealth.com/location/foo/#BreadcrumbList' // Replace 'foo' with location item slug
			),
			'creator' => array(
				'@id' => 'https://uams.edu/#CollegeOrUniversity'
			),
			'dateModified' => 'foo', // Replace 'foo' with date value in ISO 8601 date format.
			'datePublished' => 'foo', // Replace 'foo' with date value in ISO 8601 date format.
			'description' => 'foo', // Replace 'foo' with excerpt / short description
			'inLanguage' => 'English',
			'isPartOf' => array(
				'@id' => 'https://uamshealth.com/#WebSite'
			),
			'mainEntity' => array(
				'@id' => 'https://uamshealth.com/location/foo/#MedicalClinic', // Replace 'foo' with location item slug // Replace 'MedicalClinic' with 'Hospital' if necessary
			),
			'maintainer' => array(
				'@id' => 'https://uams.edu/#CollegeOrUniversity'
			),
			'medicalAudience' => array(
				array(
					'@type' => 'Patient',
					'geographicArea' => 'Arkansas'
				),
				'Clinician' // MedicalAudienceType (Enumeration Type) :: Clinician (Enumeration Member)
			),
			'mentions' => array(
				array(
					'@id' => 'https://uamshealth.com/location/foo/#MedicalClinic' // Replace 'foo' with location item slug // Replace 'MedicalClinic' with 'Hospital' if necessary
				),
				array( // Populate values for related ontology items, repeating as necessary
					'@id' => 'Thing',
					'foo' => 'bar'
				)
			),
			'primaryImageOfPage' => array( // Wayfinding photo
				'@type' => 'ImageObject',
				'caption' => 'foo', // Replace 'foo' with the image's alt text
				'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
				'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
				'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
				'height' => 'foo', // Replace 'foo' with the image's height
				'representativeOfPage' => true,
				'width' => 'foo' // Replace 'foo' with the image's width
			),
			'significantLink' => array(
				'foo' // Replace 'foo' with URL to related ontology items // Repeat as necessary
			),
			'sourceOrganization' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'url' => array(
				'@id' => 'https://uamshealth.com/location/foo/#URL', // Replace 'foo' with location item slug
				'https://uamshealth.com/provider/foo/' // Replace 'foo' with provider profile slug
			),
			'video' => 'foo' // Replace 'foo' with URL to video if relevant
		),
		// BreadcrumbList
		array(
			'@type' => 'BreadcrumbList',
			'@id' => 'https://uamshealth.com/location/foo/#BreadcrumbList', // Replace 'foo' with location item slug
			'itemListElement' => array(
				array(
					'@type' => 'ListItem',
					'position' => 1,
					'item' => array(
						'@type' => 'MedicalWebPage',
						'@id' => 'https://uamshealth.com',
						'url' => 'https://uamshealth.com',
						'name' => 'UAMS Health'
					)
				),
				array(
					'@type' => 'ListItem',
					'position' => 2,
					'item' => array(
						'@type' => 'MedicalWebPage',
						'@id' => 'https://uamshealth.com/provider/',
						'url' => 'https://uamshealth.com/provider/',
						'name' => 'Providers'
					)
				),
				array(
					'@type' => 'ListItem',
					'position' => 3,
					'item' => array(
						'@id' => 'https://uamshealth.com/location/foo/#MedicalWebPage' // Replace 'foo' with location item slug
					)
				)
			)
		),
		// Location as the thing
		array(
			'@type' => 'MedicalClinic', // Replace 'MedicalClinic' with 'Hospital' if necessary
			'@id' => 'https://uamshealth.com/location/foo/#MedicalClinic', // Replace 'foo' with location item slug // Replace 'MedicalClinic' with 'Hospital' if necessary
			'name' => array(
				'@id' => 'https://uamshealth.com/location/foo/#Name', // Replace 'foo' with location item slug
				'foo', // Replace 'foo' with title of the location item
			),
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
			'containedInPlace' => 'foo',  // Replace 'foo' with parent location / building / facility / campus
			'containsPlace' => 'foo',  // Replace 'foo' with descendant location
			'description' => 'foo', // Replace 'foo' with location description
			'employee' => 'foo',  // Replace 'foo' with related providers as Person
			'geo' => array(
				'@type' => 'GeoCoordinates',
				'latitude' => 'foo', // Replace 'foo' with latitude
				'longitude' => 'foo' // Replace 'foo' with longitude
			),
			'keywords' => 'foo',  // Replace 'foo' with titles of related ontology items
			'mainEntityOfPage' => array(
				'@id' => 'https://uamshealth.com/location/foo/#MedicalWebPage' // Replace 'foo' with location item slug
			),
			'medicalSpecialty' => array(
				'foo' // Replace foo with MedicalSpecialty (Enumeration Type) associated with the related area of expertise // Repeat as necessary
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
					'caption' => 'foo', // Replace 'foo' with the image's alt text
					'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
					'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
					'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
					'height' => 'foo', // Replace 'foo' with the image's height
					'representativeOfPage' => true,
					'width' => 'foo' // Replace 'foo' with the image's width
				)
			),
			'sameAs' => 'foo',  // Replace 'foo' with URL of Wikipedia page or location profile on 3rd-party website (e.g., 'https://www.archildrens.org/locations/arkansas-childrens')
			'smokingAllowed' => false,
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
			'subjectOf' => array(
				'@id' => 'https://uamshealth.com/location/foo/#MedicalWebPage' // Replace 'foo' with location item slug
			),
			'url' => 'https://uamshealth.com/location/foo/' // Replace 'foo' with location item slug
		)
	);