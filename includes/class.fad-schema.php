<?php

// Collect Values For Schema Data Properties

	// Add data to an array defining schema data for address or location
	function uamswp_fad_schema_address(
		$schema_address = array(), // array (optional) // Main address or location schema array
		$street_address = '', // string (optional) // The street address. For example, 1600 Amphitheatre Pkwy.
		$post_office_box_number = '', // string (optional) // The post office box number for PO box addresses.
		$address_locality = '', // string (optional) // The locality in which the street address is, and which is in the region. For example, Mountain View.
		$address_region = '', // string (optional) // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
		$postal_code = '', // string (optional) // The postal code. For example, 94043.
		$address_country = 'USA', // string (optional) // The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.
		$name = '', // string (optional) // The name of the item.
		$telephone = '', // string (optional) // The telephone number.
		$fax_number = '' // string (optional) // The fax number.
	) {

		/* Example use:
		* 
		* 	// Address Schema Data
		* 
		* 		// Check/define the main address or location schema array
		* 		$schema_address = ( isset($schema_address) && is_array($schema_address) ) ? $schema_address : array();
		* 
		* 		// Add this location's details to the main address or location schema array
		* 		$schema_address = uamswp_fad_schema_address(
		* 			$schema_address, // array (optional) // Main address or location schema array
		* 			'PostalAddress', // string (optional) // Schema type
		* 			$location_address_1 . ( $location_address_2_schema ? ' ' . $location_address_2_schema : '' ), // string (optional) // The street address. For example, 1600 Amphitheatre Pkwy.
		* 			'', // string (optional) // The post office box number for PO box addresses.
		* 			$location_city, // string (optional) // The locality in which the street address is, and which is in the region. For example, Mountain View.
		* 			$location_state, // string (optional) // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
		* 			$location_zip, // string (optional) // The postal code. For example, 94043.
		* 			'', // string (optional) // The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.
		* 			$location_title, // string (optional) // The name of the item.
		* 			$location_phone_format_dash, // string (optional) // The telephone number.
		* 			$location_fax_format_dash // string (optional) // The fax number.
		* 		);
		*/

		// Check/define variables

			$schema_address = is_array($schema_address) ? $schema_address : array();
			$address_country = !empty($address_country) ? $address_country : 'USA';

		// Create an array for this item

			$schema = array();

		// Add values to the array

			if ( $name ) {

				if ( is_array($name) ) {

					foreach ( $name as $item ) {

						$schema['name'][] = uamswp_attr_conversion($item);

					}

				} else {

					$schema['name'] = uamswp_attr_conversion($name);

				}

			}

			if ( $street_address ) {

				if ( is_array($street_address) ) {

					foreach ( $street_address as $item ) {

						$schema['streetAddress'][] = uamswp_attr_conversion($item);

					}

				} else {

					$schema['streetAddress'] = uamswp_attr_conversion($street_address);

				}

			}

			if ( $post_office_box_number ) {

				if ( is_array($post_office_box_number) ) {

					foreach ( $post_office_box_number as $item ) {

						$schema['postOfficeBoxNumber'][] = uamswp_attr_conversion($item);

					}

				} else {

					$schema['postOfficeBoxNumber'] = uamswp_attr_conversion($post_office_box_number);

				}

			}

			if ( $address_locality ) {

				if ( is_array($address_locality) ) {

					foreach ( $address_locality as $item ) {

						$schema['addressLocality'][] = uamswp_attr_conversion($item);

					}

				} else {

					$schema['addressLocality'] = uamswp_attr_conversion($address_locality);

				}

			}

			if ( $address_region ) {

				if ( is_array($address_region) ) {

					foreach ( $address_region as $item ) {

						$schema['addressRegion'][] = uamswp_attr_conversion($item);

					}

				} else {

					$schema['addressRegion'] = uamswp_attr_conversion($address_region);

				}

			}

			if ( $postal_code ) {

				if ( is_array($postal_code) ) {

					foreach ( $postal_code as $item ) {

						$schema['postalCode'][] = uamswp_attr_conversion($item);

					}

				} else {

					$schema['postalCode'] = uamswp_attr_conversion($postal_code);

				}

			}

			if ( $address_country ) {

				if ( is_array($address_country) ) {

					foreach ( $address_country as $item ) {

						$schema['addressCountry'][] = uamswp_attr_conversion($item);

					}

				} else {

					$schema['addressCountry'] = uamswp_attr_conversion($address_country);

				}

			}

			if ( $telephone ) {

				if ( is_array($telephone) ) {

					foreach ( $telephone as $item ) {

						$schema['telephone'][] = format_phone_dash($item);

					}

				} else {

					$schema['telephone'] = format_phone_dash($telephone);

				}

			}

			if ( $fax_number ) {

				if ( is_array($fax_number) ) {

					foreach ( $fax_number as $item ) {

						$schema['faxNumber'][] = format_phone_dash($item);

					}

				} else {

					$schema['faxNumber'] = format_phone_dash($fax_number);

				}

			}

			if ( !empty($schema) ) {
				$schema = array('@type' => 'PostalAddress') + $schema;
			}

		// Add this item's array to the main address or location schema array

			if ( !empty($schema) ) {
				$schema_address[] = $schema;
			}

		// Return the main address or location schema array

			return $schema_address;

	}

	// Add data to an array defining schema data for medicalSpecialty
	function uamswp_fad_schema_medical_specialty(
		$schema_medical_specialty = array(), // array (optional) // Main medicalSpecialty schema array
		$name = '', // string (optional) // The name of the item.
		$url = '', // string (optional) // URL of the item.
		$alternate_name = '' // string (optional) // An alias for the item.
	) {

		/* Example use:
		* 
		* 	// MedicalSpecialty Schema Data
		* 
		* 		// Check/define the main medicalSpecialty schema array
		* 		$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();
		* 
		* 		// Add this location's details to the main medicalSpecialty schema array
		* 		$schema_medical_specialty = uamswp_fad_schema_medical_specialty(
		* 			$schema_medical_specialty, // array (optional) // Main medicalSpecialty schema array
		* 			$condition_title_attr, // string (optional) // The name of the item.
		* 			$condition_url // string (optional) // URL of the item.
		* 		);
		*/

		// Check/define variables

			$schema_medical_specialty = is_array($schema_medical_specialty) ? $schema_medical_specialty : array();

		// Create an array for this item

			$schema = array();

		// Add values to the array

			if ( $name ) {

				if ( is_array($name) ) {

					foreach ( $name as $item ) {

						$schema['name'][] = uamswp_attr_conversion($item);

					}

				} else {

					$schema['name'] = uamswp_attr_conversion($name);

				}

			}

			if ( $url ) {

				if ( is_array($url) ) {

					foreach ( $url as $item ) {

						$schema['url'][] = uamswp_attr_conversion($item);

					}

				} else {

					$schema['url'] = uamswp_attr_conversion($url);

				}

			}

			if ( $alternate_name ) {

				if ( is_array($alternate_name) ) {

					foreach ( $alternate_name as $item ) {

						$schema['alternateName'][] = uamswp_attr_conversion($item);

					}

				} else {

					$schema['alternateName'] = uamswp_attr_conversion($alternate_name);

				}

			}

			if ( !empty($schema) ) {
				$schema = array('@type' => 'MedicalSpecialty') + $schema;
			}

		// Add this item's array to the main address schema array

			if ( !empty($schema) ) {
				$schema_medical_specialty[] = $schema;
			}

		// Return the main address schema array

			return $schema_medical_specialty;

	}

	// Add data to an array defining schema data for faxNumber
	function uamswp_fad_schema_fax_number(
		$schema_fax_number = array(), // array (optional) // Main faxNumber schema array
		$fax_number = '' // string (optional) // The fax number.
	) {

		/* Example use:
		* 
		* 	// FaxNumber Schema Data
		* 
		* 		// Check/define the main faxNumber schema array
		* 		$schema_fax_number = ( isset($schema_fax_number) && is_array($schema_fax_number) && !empty($schema_fax_number) ) ? $schema_fax_number : array();
		* 
		* 		// Add this location's details to the main faxNumber schema array
		* 		$schema_fax_number = uamswp_fad_schema_fax_number(
		* 			$schema_fax_number, // array (optional) // Main faxNumber schema array
		* 			$location_fax_format_dash // string (optional) // The fax number.
		* 		);
		*/

		// Check/define variables

			$schema_fax_number = is_array($schema_fax_number) ? $schema_fax_number : array();

		// Add values to the main faxNumber schema array

			if ( $fax_number ) {

				if ( is_array($fax_number) ) {

					foreach ( $fax_number as $item ) {

						$schema_fax_number[] = format_phone_dash($item);

					}

				} else {

					$schema_fax_number[] = format_phone_dash($fax_number);

				}

			}

		// Return the main faxNumber schema array

			return $schema_fax_number;

	}

	// Add data to an array defining schema data for telephone
	function uamswp_fad_schema_telephone(
		$schema_telephone = array(), // array (optional) // Main telephone schema array
		$telephone_number = '' // string (optional) // The telephone number.
	) {

		/* Example use:
		* 
		* 	// Telephone Schema Data
		* 
		* 		// Check/define the main telephone schema array
		* 		$schema_telephone = ( isset($schema_telephone) && is_array($schema_telephone) && !empty($schema_telephone) ) ? $schema_telephone : array();
		* 
		* 		// Add this location's details to the main telephone schema array
		* 		$schema_telephone = uamswp_fad_schema_telephone(
		* 			$schema_telephone, // array (optional) // Main telephone schema array
		* 			$telephone_number // string (optional) // The telephone number.
		* 		);
		*/

		// Check/define variables

			$schema_telephone = is_array($schema_telephone) ? $schema_telephone : array();

		// Add values to the main telephone schema array

			if ( $telephone_number ) {

				if ( is_array($telephone_number) ) {

					foreach ( $telephone_number as $item ) {

						$schema_telephone[] = format_phone_dash($item);

					}

				} else {

					$schema_telephone[] = format_phone_dash($telephone_number);

				}

			}

		// Return the main telephone schema array

			return $schema_telephone;

	}

	// Add data to an array defining schema data for OpeningHoursSpecification
	function uamswp_fad_schema_opening_hours_specification(
		$schema_opening_hours_specification = array(), // array (optional) // Main OpeningHoursSpecification schema array
		$day_of_week = array(), // array|string (optional) // The day of the week for which these opening hours are valid.
		$opens = '', // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		$closes = '', // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		$valid_from = '', // string (optional) // The date when the item becomes valid.
		$valid_through = '' // string (optional) // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
	) {

		/* Example use:
		* 
		* 	// OpeningHoursSpecification Schema Data
		* 
		* 		// Check/define the main OpeningHoursSpecification schema array
		* 		$schema_opening_hours_specification = ( isset($schema_opening_hours_specification) && is_array($schema_opening_hours_specification) && !empty($schema_opening_hours_specification) ) ? $schema_opening_hours_specification : array();
		* 
		* 		// Add this location's details to the main OpeningHoursSpecification schema array
		* 
		* 			// // Schema.org method: Add all days as an array under the dayOfWeek property
		* 			// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
		* 
		* 			// 	$schema_opening_hours_specification = uamswp_fad_schema_opening_hours_specification(
		* 			// 		$schema_opening_hours_specification, // array (optional) // Main OpeningHoursSpecification schema array
		* 			// 		$schema_day_of_week, // array|string (optional) // The day of the week for which these opening hours are valid.
		* 			// 		$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 			// 		$schema_closes, // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 			// 		$schema_valid_from, // string (optional) // The date when the item becomes valid.
		* 			// 		$schema_valid_through // string (optional) // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
		* 			// 	);
		* 
		* 			// Google method: Loop through all the days defined in the current Hours repeater row separately
		* 			// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
		* 
		* 				foreach ( $schema_day_of_week as $day) {
		* 					$schema_opening_hours_specification = uamswp_fad_schema_opening_hours_specification(
		* 						$schema_opening_hours_specification, // array (optional) // Main OpeningHoursSpecification schema array
		* 						$day, // array|string (optional) // The day of the week for which these opening hours are valid.
		* 						$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 						$schema_closes, // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 						$schema_valid_from, // string (optional) // The date when the item becomes valid.
		* 						$schema_valid_through // string (optional) // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
		* 					);
		* 				}
		*/

		// Check/define variables

			$schema_opening_hours_specification = is_array($schema_opening_hours_specification) ? $schema_opening_hours_specification : array();
			$day_of_week = !empty($day_of_week) ? $day_of_week : array();

		// Create an array for this item

			$schema = array();

		// Add values to the array

			if ( $day_of_week ) {
				$schema['dayOfWeek'] = $day_of_week;
			}

			if ( $opens ) {
				$schema['opens'] = $opens;
			}

			if ( $closes ) {
				$schema['closes'] = $closes;
			}

			if ( $valid_from ) {
				$schema['validFrom'] = $valid_from;
			}

			if ( $valid_through ) {
				$schema['validThrough'] = $valid_through;
			}

			if ( !empty($schema) ) {
				$schema = array('@type' => 'OpeningHoursSpecification') + $schema;
			}

		// Add this item's array to the main openingHoursSpecification schema array

			if ( !empty($schema) ) {
				$schema_opening_hours_specification[] = $schema;
			}

		// Return the main address schema array

			return $schema_opening_hours_specification;

	}

	// Add data to an array defining schema data for OpeningHours
	function uamswp_fad_schema_opening_hours(
		$schema_opening_hours = array(), // array (optional) // Main OpeningHours schema array
		$day_of_week = '', // string (optional) // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
		$opens = '', // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		$closes = '' // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
	) {

		/* Example use:
		* 
		* 	// OpeningHours Schema Data
		* 
		* 		// Check/define the main OpeningHours schema array
		* 		$schema_opening_hours = ( isset($schema_opening_hours) && is_array($schema_opening_hours) && !empty($schema_opening_hours) ) ? $schema_opening_hours : array();
		* 
		* 		// Add this location's details to the main OpeningHours schema array
		* 
		* 			$schema_opening_hours = uamswp_fad_schema_opening_hours(
		* 				$schema_opening_hours, // array (optional) // Main OpeningHours schema array
		* 				$schema_day_of_week, // string (optional) // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
		* 				$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 				$schema_closes // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 			);
		*/

		// Check/define variables

			$schema_opening_hours = is_array($schema_opening_hours) ? $schema_opening_hours : array();

		// Add values to the array

			if (
				$day_of_week
				&&
				$opens
				&&
				$closes
			) {
				$schema_opening_hours[] = $day_of_week . ' ' . $opens . '-' . $closes;
			}

		// Return the main address schema array

			return $schema_opening_hours;

	}

	// Add data to an array defining schema data for GeoCoordinates
	function uamswp_schema_geo_coordinates(
		$schema_geo_coordinates = array(), // array (optional) // main GeoCoordinates schema array
		$latitude = '', // string (optional) // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
		$longitude = '', // string (optional) // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
		$elevation = '' // string (optional) // The elevation of a location (WGS 84). Values may be of the form 'NUMBER UNIT_OF_MEASUREMENT' (e.g., '1,000 m', '3,200 ft') while numbers alone should be assumed to be a value in meters.
	) {

		/* Example use:
		 * 
		 * 	// GeoCoordinates Schema Data
		 * 
		 * 		// Check/define the main GeoCoordinates schema array
		 * 		$schema_geo_coordinates = ( isset($schema_geo_coordinates) && is_array($schema_geo_coordinates) && !empty($schema_geo_coordinates) ) ? $schema_geo_coordinates : array();
		 * 
		 * 		// Add this location's details to the main GeoCoordinates schema array
		 * 
		 * 			$schema_geo_coordinates = uamswp_schema_geo_coordinates(
		 * 				$schema_geo_coordinates, // array (optional) // main GeoCoordinates schema array
		 * 				$schema_latitude, // string (optional) // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
		 * 				$schema_longitude, // string (optional) // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
		 * 				$schema_elevation // string (optional) // The elevation of a location (WGS 84). Values may be of the form 'NUMBER UNIT_OF_MEASUREMENT' (e.g., '1,000 m', '3,200 ft') while numbers alone should be assumed to be a value in meters.
		 * 			);
		 */

		// Check/define variables

			$schema_geo_coordinates = is_array($schema_geo_coordinates) ? $schema_geo_coordinates : array();

		// Create an array for this item

		$schema = array();

		// Add values to the array

			if ( $latitude ) {
				$schema['latitude'] = $latitude;
			}

			if ( $longitude ) {
				$schema['longitude'] = $longitude;
			}

			if ( $elevation ) {
				$schema['elevation'] = $elevation;
			}

			if ( !empty($schema) ) {
				$schema = array('@type' => 'GeoCoordinates') + $schema;
			}

		// Add this item's array to the main GeoCoordinates schema array

			if ( !empty($schema) ) {
				$schema_geo_coordinates[] = $schema;
			}

		// Return the main GeoCoordinates schema array

			return $schema_geo_coordinates;

	}

	// Add data to an array defining schema data for hospitalAffiliation
	function uamswp_fad_schema_hospital_affiliation(
		$schema_hospital_affiliation = array(), // array (optional) // Main hospitalAffiliation schema array
		$hospital_affiliation = array() // array (optional) // Hospital affiliation
	) {

		// Check/define variables

			$schema_hospital_affiliation = is_array($schema_hospital_affiliation) ? $schema_hospital_affiliation : array();

		// Create an array for this item

		$schema = array();

		// Loop through each hospital affiliation

			foreach ( $hospital_affiliation as $hospital ) {

				// Eliminate PHP errors

					$hospital_affiliation_term = '';
					$hospital_affiliation_location = '';
					$hospital_affiliation_location_title = '';
					$hospital_affiliation_location_url = '';

				// Get the Hospital Affiliation term from the ID

					$hospital_affiliation_term = get_term( $hospital, 'affiliation' ) ?: '';

				// Associated location

					// Get the ID of the location profile associated with the Affiliated Location

						if ( is_object($hospital_affiliation_term) ) {

							$hospital_affiliation_location = get_field( 'affiliation_location', $hospital_affiliation_term ) ?: '';
							$hospital_affiliation_location = is_array($hospital_affiliation_location) && !empty($hospital_affiliation_location) ? $hospital_affiliation_location[0] : '';

							if ( $hospital_affiliation_location ) {

								// Get the name of the associated location profile

									$hospital_affiliation_location_title = get_the_title($hospital_affiliation_location);

								// Get the URL of the associated location profile

									$hospital_affiliation_location_url = get_permalink($hospital_affiliation_location);

							} else {

								// Skip the rest of the current loop iteration
								continue;

							}

						}

				// Add values to the array

					if ( $hospital_affiliation_location_title ) {

						if ( is_array($hospital_affiliation_location_title) ) {
		
							foreach ( $hospital_affiliation_location_title as $item ) {
		
								$schema['name'][] = uamswp_attr_conversion($item);
		
							}
		
						} else {
		
							$schema['name'] = uamswp_attr_conversion($hospital_affiliation_location_title);
		
						}
		
					}
		
					if ( $hospital_affiliation_location_url ) {
		
						if ( is_array($hospital_affiliation_location_url) ) {
		
							foreach ( $hospital_affiliation_location_url as $item ) {
		
								$schema['url'][] = user_trailingslashit($item);
		
							}
		
						} else {
		
							$schema['url'] = user_trailingslashit($hospital_affiliation_location_url);
		
						}
		
					}
		
					if ( !empty($schema) ) {
						$schema = array('@type' => 'Hospital') + $schema;
					}

				// Add this item's array to the main hospitalAffiliation schema array

					if ( !empty($schema) ) {
						$schema_hospital_affiliation[] = $schema;
					}

			} // endforeach

		// Return the main hospitalAffiliation schema array

			return $schema_hospital_affiliation;

	}

	// Add data to an array defining schema data for availableService
	function uamswp_fad_schema_available_service(
		$schema_available_service, // array // Main availableService schema array
		$entity_id // int // ID of the medical entity (a.k.a., Treatment and Procedure post)
	) {

		// Retrieve the value of the transient
		uamswp_fad_get_transient( 'val_' . $entity_id, $schema, __FUNCTION__ );

		// Eliminate PHP errors

			$type = '';

		if ( !empty( $schema ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $schema;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Check/define variables

				$schema_available_service = is_array($schema_available_service) ? $schema_available_service : array();

			// Create an array for this item

				$schema = array();

			// Determine specific type of MedicalEntity

				$medical_entity = get_field( 'treatment_procedure_schema_medical_entity', $entity_id ) ?: '';

				if ( $medical_entity == 'MedicalTest' ) {

					// Determine specific type of MedicalTest

					$medical_test = get_field( 'treatment_procedure_schema_medical_test', $entity_id ) ?: '';

						if ( $medical_test == 'none' ) {

							$type = $medical_entity;

						} else {

							$type = $medical_test;

						}

				} elseif ( $medical_entity == 'MedicalProcedure' ) {

					// Determine specific type of MedicalProcedure

						$medical_procedure = get_field( 'treatment_procedure_schema_medical_procedure', $entity_id ) ?: '';

						if ( $medical_procedure == 'none' ) {

							$type = $medical_entity;

						} else {

							if ( $medical_procedure == 'TherapeuticProcedure' ) {

								// Determine specific type of TherapeuticProcedure

									$therapeutic_procedure = get_field( 'treatment_procedure_schema_therapeutic_procedure', $entity_id ) ?: '';

									if ( $therapeutic_procedure == 'MedicalTherapy' ) {

										// Determine specific type of MedicalTherapy

											$medical_therapy = get_field( 'treatment_procedure_schema_medical_therapy', $entity_id ) ?: '';

											if ( $medical_therapy == 'none' ) {

												$type = $therapeutic_procedure;

											} else {

												$type = $medical_therapy;

											}

									} else {

										$type = $therapeutic_procedure;

									}

							} else {

								$type = $medical_procedure;

							}

						}

				}

			// Add values to the array

				if ( $type ) {

					// MedicalEntity Name
						
						$name = get_the_title($entity_id) ?: '';

						if ( $name ) {

							$schema['name'] = uamswp_attr_conversion($name);

						}

					// MedicalEntity Name
						
						$name = get_the_title($entity_id) ?: '';

						if ( $name ) {

							$schema['name'] = uamswp_attr_conversion($name);

						}

				}

				if ( !empty($schema) ) {
					$schema = array('@type' => $type) + $schema;
				}

				// Set/update the value of the transient
				uamswp_fad_set_transient( 'val_' . $entity_id, $schema, __FUNCTION__ );

			// Add this item's array to the main availableService schema array

				if ( !empty($schema) ) {
					$schema_available_service[] = $schema;
				}

			// Return the main availableService schema array

				// Return the variable
				return $schema_available_service;

		}

	}
