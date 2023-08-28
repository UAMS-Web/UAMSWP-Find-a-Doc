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