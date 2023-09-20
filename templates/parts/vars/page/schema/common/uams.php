<?php

// Common property values

	include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

// UAMS

	$schema_base_org_uams_url = user_trailingslashit('https://uams.edu');

	// Base array

		$schema_base_org_uams = array(
			'@type' => 'CollegeOrUniversity'
		);

	// @id

		$schema_base_org_uams['@id'] = $schema_base_org_uams_url . '#' . $schema_base_org_uams['@type'];

		// Define reference to this 'CollegeOrUniversity' item

			$schema_base_org_uams_ref['@id'] = $schema_base_org_uams['@id'] ?: '';

	// name

		$schema_base_org_uams['name'] = 'University of Arkansas for Medical Sciences';
		$schema_base_org_uams_name = $schema_base_org_uams['name'] ?? '';

	// alternateName

		$schema_base_org_uams['alternateName'] = 'UAMS';
		$schema_base_org_uams_alternateName = $schema_base_org_uams['alternateName'] ?? '';

	// contactPoint

		$schema_base_org_uams['contactPoint'] = array(
			'@type' => 'ContactPoint',
			'@id' => $schema_base_org_uams_url . '#ContactPoint',
			'addressCountry' => 'USA',
			'addressLocality' => 'Little Rock',
			'addressRegion' => 'Arkansas',
			'contactType' => 'main campus address',
			'postalCode' => '72205',
			'streetAddress' => '4301 West Markham Street'
		);

		// Define reference to this 'CollegeOrUniversity' item's 'contactPoint' property

			$schema_base_org_uams_contactPoint_ref['@id'] = $schema_base_org_uams['contactPoint']['@id'] ?: '';

	// description

		$schema_base_org_uams['description'] = 'The University of Arkansas for Medical Sciences is the state\'s only health sciences university, with colleges of Medicine, Nursing, Pharmacy, Health Professions and Public Health; a graduate school; a hospital; a main campus in Little Rock; a Northwest Arkansas regional campus in Fayetteville; a statewide network of regional campuses; and seven institutes: the Winthrop P. Rockefeller Cancer Institute, Jackson T. Stephens Spine & Neurosciences Institute, Harvey & Bernice Jones Eye Institute, Psychiatric Research Institute, Donald W. Reynolds Institute on Aging, Translational Research Institute and Institute for Digital Health & Innovation. UAMS includes UAMS Health, a statewide health system that encompasses all of UAMS\' clinical enterprise. UAMS is the only adult Level 1 trauma center in the state. UAMS has 3,240 students, 913 medical residents and fellows, and five dental residents. It is the state\'s largest public employer with more than 11,000 employees, including 1,200 physicians who provide care to patients at UAMS, its regional campuses, Arkansas Children\'s, the VA Medical Center and Baptist Health.';

	// location

		// // Main UAMS campus in Little Rock
		// 
		// 	$schema_base_org_uams_location[] = array(
		// 		'@type' => 'Place',
		// 		'@id' => '', // Defined later
		// 		'name' => 'University of Arkansas for Medical Sciences',
		// 		'address' => array(
		// 			'@type' => 'PostalAddress',
		// 			'addressCountry' => 'USA',
		// 			'addressLocality' => 'Little Rock',
		// 			'addressRegion' => 'Arkansas',
		// 			'postalCode' => '72205',
		// 			'streetAddress' => '4301 West Markham Street'
		// 		)
		// 	);
		// 
		// // UAMS East Regional Campus
		// 
		// 	$schema_base_org_uams_location[] = array(
		// 		'@type' => 'Place',
		// 		'@id' => '', // Defined later
		// 		'name' => 'UAMS East Regional Campus',
		// 		'address' => array(
		// 			'@type' => 'PostalAddress',
		// 			'addressCountry' => 'USA',
		// 			'addressLocality' => 'Helena-West Helena',
		// 			'addressRegion' => 'Arkansas',
		// 			'postalCode' => '72390',
		// 			'streetAddress' => '1393 Highway 242 South'
		// 		)
		// 	);
		// 
		// // UAMS North Central Regional Campus
		// 
		// 	$schema_base_org_uams_location[] = array(
		// 		'@type' => 'Place',
		// 		'@id' => '', // Defined later
		// 		'name' => 'UAMS North Central Regional Campus',
		// 		'address' => array(
		// 			'@type' => 'PostalAddress',
		// 			'addressCountry' => 'USA',
		// 			'addressLocality' => 'Batesville',
		// 			'addressRegion' => 'Arkansas',
		// 			'postalCode' => '72501',
		// 			'streetAddress' => '1993 Harrison Street'
		// 		)
		// 	);
		// 
		// // UAMS Northeast Regional Campus
		// 
		// 	$schema_base_org_uams_location[] = array(
		// 		'@type' => 'Place',
		// 		'@id' => '', // Defined later
		// 		'name' => 'UAMS Northeast Regional Campus',
		// 		'address' => array(
		// 			'@type' => 'PostalAddress',
		// 			'addressCountry' => 'USA',
		// 			'addressLocality' => '72401',
		// 			'addressRegion' => 'Arkansas',
		// 			'postalCode' => '72401',
		// 			'streetAddress' => '311 East Matthews Street'
		// 		)
		// 	);
		// 
		// // UAMS Northwest Regional Campus
		// 
		// 	$schema_base_org_uams_location[] = array(
		// 		'@type' => 'Place',
		// 		'@id' => '', // Defined later
		// 		'name' => 'UAMS Northwest Regional Campus',
		// 		'address' => array(
		// 			'@type' => 'PostalAddress',
		// 			'addressCountry' => 'USA',
		// 			'addressLocality' => 'Fayetteville',
		// 			'addressRegion' => 'Arkansas',
		// 			'postalCode' => '72703',
		// 			'streetAddress' => '1125 North College Avenue'
		// 		)
		// 	);
		// 
		// // UAMS South Regional Campus
		// 
		// 	$schema_base_org_uams_location[] = array(
		// 		'@type' => 'Place',
		// 		'@id' => '', // Defined later
		// 		'name' => 'UAMS South Regional Campus',
		// 		'address' => array(
		// 			'@type' => 'PostalAddress',
		// 			'addressCountry' => 'USA',
		// 			'addressLocality' => 'Magnolia',
		// 			'addressRegion' => 'Arkansas',
		// 			'postalCode' => '71753',
		// 			'streetAddress' => '1617 North Washington Street'
		// 		)
		// 	);
		// 
		// // UAMS South Central Regional Campus
		// 
		// 	$schema_base_org_uams_location[] = array(
		// 		'@type' => 'Place',
		// 		'@id' => '', // Defined later
		// 		'name' => 'UAMS South Central Regional Campus',
		// 		'address' => array(
		// 			'@type' => 'PostalAddress',
		// 			'addressCountry' => 'USA',
		// 			'addressLocality' => 'Pine Bluff',
		// 			'addressRegion' => 'Arkansas',
		// 			'postalCode' => '71603',
		// 			'streetAddress' => '1601 West 40th Avenue'
		// 		)
		// 	);
		// 
		// // UAMS Southwest Regional Campus
		// 
		// 	$schema_base_org_uams_location[] = array(
		// 		'@type' => 'Place',
		// 		'@id' => '', // Defined later
		// 		'name' => 'UAMS Southwest Regional Campus',
		// 		'address' => array(
		// 			'@type' => 'PostalAddress',
		// 			'addressCountry' => 'USA',
		// 			'addressLocality' => 'Texarkana',
		// 			'addressRegion' => 'Arkansas',
		// 			'postalCode' => '71854',
		// 			'streetAddress' => '3417 U of A Way'
		// 		)
		// 	);
		// 
		// // UAMS West Regional Campus
		// 
		// 	$schema_base_org_uams_location[] = array(
		// 		'@type' => 'Place',
		// 		'@id' => '', // Defined later
		// 		'name' => 'UAMS West Regional Campus',
		// 		'address' => array(
		// 			'@type' => 'PostalAddress',
		// 			'addressCountry' => 'USA',
		// 			'addressLocality' => 'Fort Smith',
		// 			'addressRegion' => 'Arkansas',
		// 			'postalCode' => '72901',
		// 			'streetAddress' => '1301 South E Street'
		// 		)
		// 	);
		// 
		// // Dynamically add IDs to each node and capture them in a new array
		// 
		// 	$schema_base_org_uams_location_ref = array();
		// 
		// 	foreach ( $schema_base_org_uams_location as $key => $value) {
		// 
		// 		$location_id = $schema_base_org_uams_url . '#Location-' . str_replace(' ', '-', $schema_base_org_uams_location[$key]['name']);
		// 		$schema_base_org_uams_location[$key]['@id'] = $location_id;
		// 		$schema_base_org_uams_location_ref[]['@id'] = $location_id;
		// 
		// 	}
		// 
		// // Add the values to the schema array
		// $schema_base_org_uams['location'] = $schema_base_org_uams_location;

	// logo

		$schema_base_org_uams['logo'] = 'foo'; // Replace foo with __

	// nonprofitStatus

		$schema_base_org_uams['nonprofitStatus'] = 'foo'; // Replace foo with __

	// sameAs

		// Wikipedia: https://en.wikipedia.org/

			$schema_base_org_uams['sameAs'][] = 'https://en.wikipedia.org/wiki/University_of_Arkansas_for_Medical_Sciences';

		// Wikidata: https://www.wikidata.org/

			$schema_base_org_uams['sameAs'][] = 'https://www.wikidata.org/wiki/Q941298';

		// Library of Congress Name Authority File: https://id.loc.gov/authorities/names.html

			$schema_base_org_uams['sameAs'][] = 'http://id.loc.gov/authorities/names/n79026333';

		// Virtual International Authority File: https://viaf.org/

			$schema_base_org_uams['sameAs'][] = 'http://viaf.org/viaf/140704690';

		// FAST Authority File: https://experimental.worldcat.org/fast/

			$schema_base_org_uams['sameAs'][] = 'http://id.worldcat.org/fast/530628';

	// slogan

		$schema_base_org_uams['slogan'] = 'For a Better State of Health';

	// subOrganization

		$schema_base_org_uams['subOrganization'] = array(); // Defined after UAMS Health 'MedicalOrganization' item is defined

	// telephone

		$schema_base_org_uams['telephone'] = '501-686-7000';

	// url

		$schema_base_org_uams['url'] = $schema_base_org_uams_url;

// UAMS Health

	// URL

		$schema_base_org_uams_health_url = user_trailingslashit( get_site_url() );
		$schema_base_org_uams_health_url_trailingslashit = trailingslashit( get_site_url() );

	// Base array

		$schema_base_org_uams_health = array(
			'@type' => 'MedicalOrganization'
		);

	// @id

		$schema_base_org_uams_health['@id'] = $schema_base_org_uams_health_url . '#' . $schema_base_org_uams_health['@type'];

		// Define reference to this 'MedicalOrganization' item

			$schema_base_org_uams_health_ref['@id'] = $schema_base_org_uams_health['@id'] ?: '';

		// Set value of 'subOrganization' property of 'CollegeOrUniversity' item with this 'MedicalOrganization' reference

			$schema_base_org_uams['subOrganization'] = $schema_base_org_uams_health_ref;

	// name

		$schema_base_org_uams_health['name'] = 'UAMS Health';
		$schema_base_org_uams_health_name = $schema_base_org_uams_health['name'] ?? '';

	// alternateName

		$schema_base_org_uams_health['alternateName'] = '';
		$schema_base_org_uams_health_alternateName = $schema_base_org_uams_health['alternateName'] ?? '';

	// contactPoint

		$schema_base_org_uams_health['contactPoint'] = $schema_base_org_uams_contactPoint_ref;

	// description

		$schema_base_org_uams_health['description'] = 'foo'; // Replace foo with __

	// location

		if (
			isset($schema_base_org_uams_location_ref)
			&&
			!empty($schema_base_org_uams_location_ref)
		) {

			$schema_base_org_uams_health['location'] = $schema_base_org_uams_location_ref;

		}

	// logo

		$schema_base_org_uams_health['logo'] = 'foo'; // Replace foo with __

	// nonprofitStatus

		$schema_base_org_uams_health['nonprofitStatus'] = 'foo'; // Replace foo with __

	// parentOrganization

		$schema_base_org_uams_health['parentOrganization'] = $schema_base_org_uams_ref;

	// slogan

		$schema_base_org_uams_health['slogan'] = 'foo'; // Replace foo with __

	// url

		$schema_base_org_uams_health['url'] = ''; // Defined after UAMSHealth.com 'WebSite' 'url' property is defined

// UAMSHealth.com

	// Base array

		$schema_base_website_uams_health = array(
			'@type' => 'WebSite'
		);

	// @id

		$schema_base_website_uams_health['@id'] = $schema_base_org_uams_health_url . '#' . $schema_base_website_uams_health['@type'];

		// Define reference to this 'MedicalOrganization' item

			$schema_base_website_uams_health_ref['@id'] = $schema_base_website_uams_health['@id'] ?: '';

	// name

		$schema_base_website_uams_health['name'] = 'UAMS Health';

	// audience

		$schema_base_website_uams_health['audience'] = $schema_common_audience;

	// creator

		$schema_base_website_uams_health['creator'] = $schema_base_org_uams_ref;

	// inLanguage

		$schema_base_website_uams_health['inLanguage'] = array(
			'@id' => $schema_base_org_uams_health_url . '#inLanguage',
			'@type' => 'Language',
			'alternateName' => 'en',
			'name' => 'English',
			'sameAs' => 'https://www.wikidata.org/wiki/Q1860'
		);

		// Define reference to this 'inLanguage' property

			$schema_base_website_uams_health_inLanguage_ref['@id'] = $schema_base_website_uams_health['inLanguage']['@id'] ?: '';

	// sourceOrganization

		$schema_base_website_uams_health['sourceOrganization'] = $schema_base_org_uams_health_ref;

	// url

		$schema_base_website_uams_health['url'] = array(
			'@type' => 'URL',
			'@id' => $schema_base_org_uams_health_url . '#URL',
			'url' => $schema_base_org_uams_health_url
		);

		// Define reference to this 'url' property

			$schema_base_website_uams_health_url_ref['@id'] = $schema_base_website_uams_health['url']['@id'] ?: '';

		// Set value of 'url' property of UAMS Health 'MedicalOrganization' item with this 'url' reference

			$schema_base_org_uams_health['url'] = $schema_base_website_uams_health_url_ref;
