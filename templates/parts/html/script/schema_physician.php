<?php

// Base schema to be used on all UAMSHealth.com pages

	$schema_base = array(
		'@context' => 'https://schema.org/',
		'@graph' => array(
			array(
				'@type' => 'CollegeOrUniversity',
				'@id' => 'https://uams.edu/#CollegeOrUniversity',
				'name' = 'University of Arkansas for Medical Sciences',
				'address' => array(
					'@type' => 'PostalAddress',
					'addressCountry' = 'USA',
					'addressLocality' = 'Little Rock',
					'addressRegion' = 'Arkansas',
					'postalCode' = '72205',
					'streetAddress' = '4301 West Markham Street'
				),
				'alternateName' = 'UAMS',
				'description' => 'The University of Arkansas for Medical Sciences is the 
					state\'s only health sciences university, with colleges of Medicine, 
					Nursing, Pharmacy, Health Professions and Public Health; a graduate school; 
					a hospital; a main campus in Little Rock; a Northwest Arkansas regional 
					campus in Fayetteville; a statewide network of regional campuses; and seven 
					institutes: the Winthrop P. Rockefeller Cancer Institute, Jackson T. 
					Stephens Spine & Neurosciences Institute, Harvey & Bernice Jones Eye 
					Institute, Psychiatric Research Institute, Donald W. Reynolds Institute on 
					Aging, Translational Research Institute and Institute for Digital Health & 
					Innovation. UAMS includes UAMS Health, a statewide health system that 
					encompasses all of UAMS\' clinical enterprise. UAMS is the only adult Level 
					1 trauma center in the state. UAMS has 3,240 students, 913 medical 
					residents and fellows, and five dental residents. It is the state\'s 
					largest public employer with more than 11,000 employees, including 1,200 
					physicians who provide care to patients at UAMS, its regional campuses, 
					Arkansas Children\'s, the VA Medical Center and Baptist Health.',
				'location' => array(
					array(
						'@type' => 'Place',
						'name' = 'University of Arkansas for Medical Sciences',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Little Rock',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72205',
							'streetAddress' = '4301 West Markham Street'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS East Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Helena-West Helena',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72390',
							'streetAddress' = '1393 Highway 242 South'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS North Central Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Batesville',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72501',
							'streetAddress' = '1993 Harrison Street'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS Northeast Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = '72401',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72401',
							'streetAddress' = '311 East Matthews Street'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS Northwest Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Fayetteville',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72703',
							'streetAddress' = '1125 North College Avenue'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS South Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Magnolia',
							'addressRegion' = 'Arkansas',
							'postalCode' = '71753',
							'streetAddress' = '1617 North Washington Street'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS South Central Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Pine Bluff',
							'addressRegion' = 'Arkansas',
							'postalCode' = '71603',
							'streetAddress' = '1601 West 40th Avenue'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS Southwest Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Texarkana',
							'addressRegion' = 'Arkansas',
							'postalCode' = '71854',
							'streetAddress' = '3417 U of A Way'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS West Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Fort Smith',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72901',
							'streetAddress' = '1301 South E Street'
						)
					)
				),
				'logo' => 'foo',
				'nonprofitStatus' => 'foo',
				'sameAs' => 'https://en.wikipedia.org/wiki/University_of_Arkansas_for_Medical_Sciences',
				'slogan' => 'For a Better State of Health',
				'subOrganization' => array(
					'@id' => 'https://uamshealth.com/#MedicalOrganization'
				),
				'telephone' => '501-686-7000',
				'url' => 'https://uams.edu'
			),
			array(
				'@type' => 'MedicalOrganization',
				'@id' => 'https://uamshealth.com/#MedicalOrganization',
				'name' => 'UAMS Health',
				'address' => array(
					'@type' => 'PostalAddress',
					'addressCountry' = 'USA',
					'addressLocality' = 'Little Rock',
					'addressRegion' = 'Arkansas',
					'postalCode' = '72205',
					'streetAddress' = '4301 West Markham Street'
				),
				'contactPoint' => 'foo',
				'description' => 'foo',
				'location' => array(
					array(
						'@type' => 'Place',
						'name' = 'University of Arkansas for Medical Sciences',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Little Rock',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72205',
							'streetAddress' = '4301 West Markham Street'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS East Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Helena-West Helena',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72390',
							'streetAddress' = '1393 Highway 242 South'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS North Central Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Batesville',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72501',
							'streetAddress' = '1993 Harrison Street'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS Northeast Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = '72401',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72401',
							'streetAddress' = '311 East Matthews Street'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS Northwest Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Fayetteville',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72703',
							'streetAddress' = '1125 North College Avenue'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS South Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Magnolia',
							'addressRegion' = 'Arkansas',
							'postalCode' = '71753',
							'streetAddress' = '1617 North Washington Street'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS South Central Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Pine Bluff',
							'addressRegion' = 'Arkansas',
							'postalCode' = '71603',
							'streetAddress' = '1601 West 40th Avenue'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS Southwest Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Texarkana',
							'addressRegion' = 'Arkansas',
							'postalCode' = '71854',
							'streetAddress' = '3417 U of A Way'
						)
					),
					array(
						'@type' => 'Place',
						'name' = 'UAMS West Regional Campus',
						'address' => array(
							'@type' => 'PostalAddress',
							'addressCountry' = 'USA',
							'addressLocality' = 'Fort Smith',
							'addressRegion' = 'Arkansas',
							'postalCode' = '72901',
							'streetAddress' = '1301 South E Street'
						)
					)
				),
				'logo' => 'foo',
				'nonprofitStatus' => 'foo',
				'parentOrganization' => array(
					'@id' => 'https://uams.edu/#CollegeOrUniversity'
				),
				'slogan' => 'foo',
				'url' => 'https://uamshealth.com'
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
			)
		),
	);

// Provider-specific schema
// Merge into $schema_base['@graph']

	$schema_graph = array(
		array(
			'@type' => 'WebPage',
			'@id' => 'https://uamshealth.com/provider/foo/#WebPage', // Replace 'foo' with provider profile slug
			'name' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#Name' // Replace 'foo' with provider profile slug
			),
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
		array(
			'@type' => 'Physician',
			'@id' => 'https://uamshealth.com/provider/foo/#Physician', // Replace 'foo' with provider profile slug
			'name' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#Name' // Replace 'foo' with provider profile slug
			),
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
			'url' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#URL' // Replace 'foo' with provider profile slug
			),
		),
		array(
			'@type' => 'Person',
			'@id' => 'https://uamshealth.com/provider/foo/#Person', // Replace 'foo' with provider profile slug
			'name' => array(
				'@id' => 'https://uamshealth.com/provider/foo/#Name', // Replace 'foo' with provider profile slug
				'foo', // Replace 'foo' with long provider name (e.g., "Leonard H. McCoy Jr., M.D.")
			)
			'affiliation' => array( // Keep if a UAMS location
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'alumniOf' => array(
				array( // Repeat as necessary
					'@type' => 'EducationalOrganization',
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
					'name' => 'foo' // Full name of degree or credential
				)
			),
			'honorificPrefix' => 'foo', // Replace 'foo' with 'Dr.' if relevant // Text (Data Type)
			'honorificSuffix' => 'foo', // Replace 'foo' with provider's degree list // Text (Data Type)
			'image' => 'foo', // Replace 'foo' with provider's headshot URL // URL (Data Type)
			'jobTitle' => array(
				'foo' // Replace 'foo' with provider's clinical title // Repeat as necessary // Text (Data Type)
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