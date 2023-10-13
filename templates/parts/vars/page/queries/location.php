<?php
/*
 * Template Name: Related Locations Section Query
 *
 * Description: A query for whether the section which lists locations related to
 * the current ontology item should be displayed on the profile/subsection. It
 * also returns the Query, an array of IDs and a count of the IDs.
 *
 * Required vars:
 * 	$page_id // int
 * 	$locations // int[]
 *
 * Optional vars:
 * 	$jump_link_count // int
 * 	hide_medical_ontology // bool
 */

// Check/define optional variables

	$jump_link_count = ( isset($jump_link_count) && !empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = false; // Never hide related locations

// Call the function

	$location_query_vars = ( isset($location_query_vars) && !empty($location_query_vars) ) ? $location_query_vars : uamswp_fad_location_query(
		$page_id, // int
		$locations, // int[]
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);

// Create a variable for each item in the array

	foreach ( $location_query_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}

// Notes on what should possibly go where

	if ( true == false ) {

		$test_provider_related_location[] = array(
			'@id' => $schema_provider_url . '#Location1', // Increase integer by one each iteration
			'@type' => 'MedicalClinic', // Replace 'MedicalClinic' with 'Hospital' if necessary
			'name' => 'foo', // Replace 'foo' with location name
			'address' => array(
				'@type' => 'PostalAddress',
				'addressCountry' => 'USA',
				'addressLocality' => 'foo', // Replace 'foo' with city
				'addressRegion' => 'Arkansas',
				'postalCode' => 'foo', // Replace 'foo' with ZIP code
				'streetAddress' => 'foo' // Replace 'foo' with street address
			),
			'areaServed' => $schema_common_arkansas,
			'brand' => $schema_base_org_uams_health_ref, // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
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
			'openingHoursSpecification' => array( // The opening hours of a certain place. // Repeat as necessary
				'@type' => 'OpeningHoursSpecification',
				'closes' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
				'dayOfWeek' => 'foo', // Replace 'foo' necessary value // DayOfWeek (Enumeration Type)
				'opens' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
				'validFrom' => 'foo', // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
				'validThrough' => 'foo' // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
			),
			'parentOrganization' => $schema_base_org_uams_health_ref, // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
			'photo' => array( // Repeat for all photos include in location profile
				'@type' => 'ImageObject',
				'caption' => 'foo', // Replace 'foo' with the image's alt text
				'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
				'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
				'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
				'height' => 'foo', // Replace 'foo' with the image's height
				'representativeOfPage' => 'False',
				'width' => 'foo' // Replace 'foo' with the image's width
			),
			'specialOpeningHoursSpecification' => array( // The special opening hours of a certain place. Use this to explicitly override general opening hours brought in scope by openingHoursSpecification or openingHours. // Repeat as necessary
				'@type' => 'OpeningHoursSpecification',
				'closes' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
				'dayOfWeek' => 'foo', // Replace 'foo' necessary value // DayOfWeek (Enumeration Type)
				'opens' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
				'validFrom' => 'foo', // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
				'validThrough' => 'foo' // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
			),
			'url' => 'foo' // Replace 'foo' with location profile URL
		);

	}