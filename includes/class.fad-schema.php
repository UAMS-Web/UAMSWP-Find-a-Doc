<?php

// Collect Values for Schema Data Properties

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

						$hospital_affiliation_term = get_term( $hospital, 'affiliation' ) ?: array();

					// Associated location

						// Get the ID of the location profile associated with the Affiliated Location

							if ( is_object($hospital_affiliation_term) ) {

								$hospital_affiliation_location = get_field( 'affiliation_location', $hospital_affiliation_term ) ?: array();
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

	// Add data to an array defining schema data for code (MedicalCode)

		function uamswp_fad_schema_code(
			array $code_repeater = array(), // code repeater field
			array $nucc = array() // Health Care Provider Taxonomy Code Set taxonomy field
		) {

			// Base list array

				$code_list = array();

			// If neither argument has values, stop here

				if (
					empty($code_repeater)
					&&
					empty($nucc)
				) {

					return $code_list;

				}

			// Code repeater

				if ( $code_repeater ) {

					// Medical Code values map

						$MedicalCode_values = array(
							'DiseasesDB' => array(
								'alternateName' => array(
									'DiseasesDB',
									'diseasesdatabase.com'
								),
								'name' => 'Diseases Database',
								'sameAs' => 'https://www.wikidata.org/wiki/Q213103',
								'url' => 'http://www.diseasesdatabase.com/'
							),
							'ICD-9' => array(
								'alternateName' => array(
									'International Statistical Classification of Diseases, Ninth Revision',
									'ICD-9',
									'ICD9',
								),
								'name' => 'International Statistical Classification of Diseases and Related Health Problems, Ninth Revision',
								'sameAs' => 'https://www.wikidata.org/wiki/Q14067712',
								'url' => 'https://www.cdc.gov/nchs/icd/icd9.htm'
							),
							'ICD-9-CM' => array(
								'alternateName' => array(
									'International Statistical Classification of Diseases, Ninth Revision, Clinical Modification',
									'ICD-9 Clinical Modification',
									'ICD-9-CM',
									'ICD9CM',
								),
								'dateModified' => 'International Statistical Classification of Diseases and Related Health Problems, Ninth Revision, Clinical Modification',
								'sameAs' => array(
									'https://id.loc.gov/authorities/names/n2009185485.html',
									'https://www.wikidata.org/wiki/Q5737131'
								),
								'url' => 'https://www.cdc.gov/nchs/icd/icd9cm.htm'
							),
							'ICD-10' => array(
								'alternateName' => array(
									'International Statistical Classification of Diseases, Tenth Revision',
									'ICD-10',
									'ICD10',
								),
								'name' => 'International Statistical Classification of Diseases and Related Health Problems, Tenth Revision',
								'sameAs' => array(
									'http://id.loc.gov/authorities/names/n2009019647',
									'https://www.wikidata.org/wiki/Q45127'
								),
								'url' => 'https://icd.who.int/browse10/'
							),
							'ICD-10-CM' => array(
								'alternateName' => array(
									'International Statistical Classification of Diseases, Tenth Revision, Clinical Modification',
									'ICD-10 Clinical Modification',
									'ICD-10-CM',
									'ICD10CM',
								),
								'name' => 'International Statistical Classification of Diseases and Related Health Problems, Tenth Revision, Clinical Modification',
								'sameAs' => array(
									'http://id.loc.gov/authorities/names/n2009185486',
									'https://www.wikidata.org/wiki/Q5969475'
								),
								'url' => 'https://www.cms.gov/medicare/coding/icd10'
							),
							'ICD-10-PCS' => array(
								'alternateName' => array(
									'International Statistical Classification of Diseases, Tenth Revision, Procedure Coding System',
									'ICD-10 Procedure Coding System',
									'ICD-10-PCS',
									'ICD10PCS',
								),
								'name' => 'International Statistical Classification of Diseases and Related Health Problems, Tenth Revision, Procedure Coding System',
								'sameAs' => array(
									'http://id.loc.gov/authorities/names/n2009185487',
									'https://www.wikidata.org/wiki/Q9006342'
								),
								'url' => 'https://www.cms.gov/medicare/coding/icd10'
							),
							'ICD-11' => array(
								'alternateName' => array(
									'International Statistical Classification of Diseases, Eleventh Revision',
									'ICD-11',
									'ICD11',
								),
								'name' => 'International Statistical Classification of Diseases and Related Health Problems, Eleventh Revision',
								'sameAs' => array(
									'http://id.loc.gov/authorities/names/n2010022952',
									'https://www.wikidata.org/wiki/Q55695727'
								),
								'url' => 'https://icd.who.int/en'
							),
							'ICHI' => array(
								'alternateName' => 'ICHI',
								'name' => 'International Classification of Health Interventions',
								'sameAs' => 'https://www.wikidata.org/wiki/Q3505045',
								'url' => 'https://www.who.int/standards/classifications/international-classification-of-health-interventions'
							),
							'ICPC-2' => array(
								'alternateName' => 'ICPC-2',
								'name' => 'International Classification of Primary Care, Second Revision',
							),
							'ICPC-3' => array(
								'alternateName' => 'ICPC-3',
								'name' => 'International Classification of Primary Care, Third Revision',
								'url' => 'https://www.icpc-3.info/'
							),
							'MeSH' => array(
								'alternateName' => 'MeSH',
								'name' => 'Medical Subject Headings',
								'sameAs' => array(
									'http://id.loc.gov/authorities/names/n2013188677',
									'https://www.wikidata.org/wiki/Q199897'
								),
								'url' => 'https://www.nlm.nih.gov/mesh/meshhome.html'
							),
							'RxNorm' => array(
								'name' => 'RxNorm',
								'sameAs' => array(
									'https://www.wikidata.org/wiki/Q7383767',
									'https://id.nlm.nih.gov/mesh/D062245.html'
								),
								'url' => 'https://www.nlm.nih.gov/research/umls/rxnorm/'
							),
							'SNOMED-CT' => array(
								'alternateName' => 'SNOMED Clinical Terms',
								'name' => 'SNOMED CT',
								'sameAs' => array(
									'http://id.loc.gov/authorities/names/n2005182509',
									'https://www.wikidata.org/wiki/Q1753883'
								),
								'url' => 'https://www.snomed.org/'
							)
						);

					// Loop through each Code repeater row, adding values to the list array

						foreach ( $code_repeater as $code ) {

							// Base item array

								$code_item = array();

							// Get values from the row

								$codeValue = $code['schema_medicalcode_codevalue'] ?? '';
								$codingSystem = $code['schema_medicalcode_codingsystem'] ?? '';
								$name = $code['schema_medicalcode_name'] ?? '';
								$url = $code['schema_medicalcode_url'] ?? '';

							// Add property values to the item array

								if (
									$codeValue
									&&
									$codingSystem
								) {

									// Base values

										$code_item = array_filter(
											array(
												'@type' => 'MedicalCode',
												'codeValue' => $codeValue,
												'codingSystem' => $codingSystem,
												'name' => $name,
												'url' => $url
											)
										);

									// inCodeSet

										$inCodeSet = $MedicalCode_values[$codingSystem] ?? array();

										if ( $inCodeSet ) {

											$inCodeSet_alternateName = $inCodeSet['alternateName'] ?? array();
											$inCodeSet_name = $inCodeSet['name'] ?? '';
											$code_item['codingSystem'] = $inCodeSet_name ?? $code_item['codingSystem']; // Update base code 'codingSystem' value with 'name' value from 'inCodeSet' property
											$inCodeSet_sameAs = $inCodeSet['sameAs'] ?? array();
											$inCodeSet_url = $inCodeSet['url'] ?? '';

											if ( $inCodeSet_name ) {

												$code_item['inCodeSet'] = array_filter(
													array(
														'@type' => 'CategoryCodeSet',
														'alternateName' => $inCodeSet_alternateName,
														'name' => $inCodeSet_name,
														'sameAs' => $inCodeSet_sameAs,
														'url' => $inCodeSet_url
													)
												);

											} // endif ( $inCodeSet_name )

										} // endif ( $inCodeSet )

								} // endif ( $codeValue && $codingSystem )

							// Clean up item array

								if ( $code_item ) {

									// Sort code item array

										ksort($code_item);

								} // endif ( $code_item )

							// Add to code item to list of codes

								if ( $code_item ) {

									$code_list[] = $code_item;

								} // endif ( $code_item )

						} // endforeach ( $code_repeater as $code )

				} // endif ( $code_repeater )

			// Health Care Provider Taxonomy Code Set taxonomy items

				if ( $nucc ) {

					// Loop through each Health Care Provider Taxonomy Code Set taxonomy item, adding values to the list array

						foreach ( $nucc as $code ) {

							// Eliminate PHP errors / reset variables

								$code_item = array(); // Base array
								$specialization_term = '';
								$specialization_extension_query = '';
								$specialization_code_query = '';
								$specialization_code = '';
								$specialization_name = '';
								$codeValue = '';
								$name = '';
								$url = '';

								// Reused variables

									$codingSystem = 'Health Care Provider Taxonomy';
									$inCodeSet = array(
										'@type' => 'CategoryCodeSet',
										'alternateName' => array(
											'Health Care Provider Taxonomy code set',
											'National Uniform Claim Committee Health Care Provider Taxonomy',
											'NUCC Health Care Provider Taxonomy',
											'National Uniform Claim Committee code set',
											'NUCC code set',
											'Provider Taxonomy Code List'
										),
										'name' => $codingSystem,
										'sameAs' => 'http://terminology.hl7.org/CodeSystem/v3-nuccProviderCodes',
										'url' => array(
											'https://nucc.org/index.php/code-sets-mainmenu-41/provider-taxonomy-mainmenu-40',
											'https://taxonomy.nucc.org/'
										),
									);

							// Base item array

								$code_item = array();

							// Get values from the item

								$specialization_term = get_term( $code, 'clinical_title' ) ?? '';
								$specialization_extension_query = get_field( 'clinical_specialization_extension_query', $specialization_term ) ?? false; // Is this clinical specialization part of the UAMS Health extension to the Health Care Provider Taxonomy Code Set?
								$specialization_code_query = get_field( 'clinical_specialization_code_query', $specialization_term ) ?? true; // Does this specialization have a taxonomy code in the Health Care Provider Taxonomy Code Set?

								// Taxonomy code

									if (
										$specialization_term // The term exists
										&&
										!$specialization_extension_query // The specialization is not an extension to the Health Care Provider Taxonomy code set
										&&
										$specialization_code_query // The specialization has a taxonomy code in the Health Care Provider Taxonomy code set
									) {

										$specialization_code = $specialization_code_query ? ( get_field( 'clinical_specialization_code', $specialization_term ) ?? '' ) : ''; // Specialization Taxonomy Code in the Health Care Provider Taxonomy Code Set

										// Fallback value (slug)

											if ( !$specialization_code ) {

												$specialization_code = $specialization_term->post_name ?? '';

												// Check if fallback value seems like a valid code

													// 10 digits

														if ( $specialization_code ) {

															$specialization_code = strlen() == 10 ? $specialization_code : '';

														}

													// Only letters and integers

														if ( $specialization_code ) {

															$specialization_code = ( preg_match('/[A-Za-z]/', $myString) && preg_match('/[0-9]/', $myString) ) ? $specialization_code : '';

														}

											}

										$specialization_code = $specialization_code ?: '';

									} else {

										/* Get the closest parent that does have a code in the code set */

									}

									// If no specialization taxonomy code exists from the Health Care Provider Taxonomy Code Set in the item's ancestry, skip this iteration

										if ( !$specialization_code ) {

											continue;

										}

								// Taxonomy code name

									if ( $specialization_code ) {

										$specialization_name = get_field( 'clinical_specialty_name', $specialization_term ) ?? '';
										$specialization_name = $specialization_name ?: $specialization_term->name;

									}

								// Add values from the item to the item array

									$codeValue = $specialization_code ?? '';
									$name = $specialization_name ?? '';
									$url = $codeValue ? 'https://taxonomy.nucc.org/?searchTerm=' . $codeValue : '';

									if (
										$codeValue
										&&
										$codingSystem
									) {

										$code_item = array_filter(
											array(
												'@type' => 'MedicalCode',
												'codeValue' => $codeValue,
												'codingSystem' => $codingSystem,
												'inCodeSet' => $inCodeSet,
												'name' => $name,
												'url' => $url
											)
										);

									}

							// Clean up item array

								if ( $code_item ) {

									// Sort code item array

										ksort($code_item);

								} // endif ( $code_item )

							// Add to code item to list of codes

								if ( $code_item ) {

									$code_list[] = $code_item;

								} // endif ( $code_item )
		
						} // endforeach ( $nucc as $code )

				}

			// Clean up list array

				if ( $code_list ) {

					$code_list = array_filter($code_list);
					$code_list = array_values($code_list);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($code_list);

				}

			return $code_list;

		}

	// Add data to an array defining schema data for alternateName

		function uamswp_fad_schema_alternatename(
			array $repeater, // alternateName repeater field
			string $field_name = 'alternate_text' // alternateName item field name
		) {

			// Base list array

				$alternateName_list = array();

			if ( $repeater ) {

				foreach ( $repeater as $alternateName ) {

					$alternateName_list[] = $alternateName[$field_name];

				} // endforeach ( $repeater as $alternateName )

				// Clean up list array

					$alternateName_list = array_filter($alternateName_list);
					$alternateName_list = array_values($alternateName_list);
					sort($alternateName_list);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($alternateName_list);

			} // endif ( $repeater )

			return $alternateName_list;

		}

	// Add data to an array defining schema data for ImageObject from thumbnails

		function uamswp_fad_schema_imageobject_thumbnails(
			string $url, // URL of entity with which the image is associated
			int $nesting_level, // Nesting level within the main schema
			string $single_aspect_ratio, // Aspect ratio to use if only on image is included // enum('1:1', '3:4', '4:3', '16:9', 'full')
			string $page_fragment = 'Image', // Base fragment identifier
			int $input_1_1 = 0, // ID of image to use for 1:1 aspect ratio
			int $input_3_4 = 0, // ID of image to use for 3:4 aspect ratio
			int $input_4_3 = 0, // ID of image to use for 4:3 aspect ratio
			int $input_16_9 = 0, // ID of image to use for 16:9 aspect ratio
			int $input_full = 0 // ID of image to use for full image
		) {

			// Check variables

				// If values are 0 or empty, end now

					if (
						!$input_1_1
						&&
						!$input_3_4
						&&
						!$input_4_3
						&&
						!$input_16_9
					) {

						return;

					}

			// Base array

				$image_ImageObject = array();

			// Image attributes

				// Base ImageObject

					$image_ImageObject_base = array(
						'@type' => 'ImageObject'
					);
					$image_ImageObject_base['representativeOfPage'] = $nesting_level == 0 ? 'True' : 'False';

				// 1:1 aspect ratio source image

					if (
						$input_1_1
						&&
						(
							$nesting_level == 0
							||
							$single_aspect_ratio == '1:1'
						)
					) {

						// Encoding Format (e.g., 'image/jpeg')

							$image_encodingFormat_1_1 = get_post_mime_type( $input_1_1 ) ?? '';

						// Alt Text

							$image_caption_1_1 = get_post_meta( $input_1_1, '_wp_attachment_image_alt', TRUE ) ?? '';

						// Image data
							
							$image_1_1_src = wp_get_attachment_image_src( $input_1_1, 'aspect-1-1' ) ?? array();

							if ( $image_1_1_src ) {

								$image_1_1_url = $image_1_1_src[0] ?? '';
								$image_1_1_width = $image_1_1_src[1] ?? '';
								$image_1_1_height = $image_1_1_src[2] ?? '';
								$image_1_1_size = '';

							}

						// Create ImageObject

							$image_1_1 = array();
							$image_1_1['@id'] = $url . '#' . $page_fragment .  '-1-1';
							$image_1_1['caption'] = $image_caption_1_1 ?? '';
							$image_1_1['contentSize'] = $image_1_1_size ?: '';
							$image_1_1['contentUrl'] = $image_1_1_url ?: '';
							$image_1_1['encodingFormat'] = $image_encodingFormat_1_1 ?? '';
							$image_1_1['height'] = $image_1_1_height ? $image_1_1_height . ' px' : '';
							$image_1_1['width'] = $image_1_1_width ? $image_1_1_width . ' px' : '';

							$image_1_1 = array_filter(
								array_merge(
									$image_ImageObject_base,
									$image_1_1
								)
							);

							// Sort the item array

								ksort($image_1_1);

							// Add to list array

								$image_ImageObject[] = $image_1_1;

					}

				// 3:4 aspect ratio source image

					if (
						$input_3_4
						&&
						(
							$nesting_level == 0
							||
							$single_aspect_ratio == '3:4'
						)
					) {
				
						// Encoding Format (e.g., 'image/jpeg')

							$image_encodingFormat_3_4 = get_post_mime_type( $input_3_4 ) ?? '';

						// Alt Text

							$image_caption_3_4 = get_post_meta( $input_3_4, '_wp_attachment_image_alt', TRUE ) ?? '';

						// Image Data

							$image_3_4_src = wp_get_attachment_image_src( $input_3_4, 'aspect-3-4' ) ?? array();
					
							if ( $image_3_4_src ) {
					
								$image_3_4_url = $image_3_4_src[0] ?? '';
								$image_3_4_width = $image_3_4_src[1] ?? '';
								$image_3_4_height = $image_3_4_src[2] ?? '';
								$image_3_4_size = '';
					
							}
			
						// ImageObject
			
							$image_3_4 = array();
							$image_3_4['@id'] = ( isset($url) && !empty($url) ) ? $url . '#' . $page_fragment .  '-3-4' : '';
							$image_3_4['caption'] = $image_caption_3_4 ?? '';
							$image_3_4['contentSize'] = $image_3_4_size ?: '';
							$image_3_4['contentUrl'] = $image_3_4_url ?: '';
							$image_3_4['encodingFormat'] = $image_encodingFormat_3_4 ?? '';
							$image_3_4['height'] = $image_3_4_height ? $image_3_4_height . ' px' : '';
							$image_3_4['width'] = $image_3_4_width ? $image_3_4_width . ' px' : '';
			
							$image_3_4 = array_filter(
								array_merge(
									$image_ImageObject_base,
									$image_3_4
								)
							);
			
							// Sort the item array
			
								ksort($image_3_4);
			
							// Add to list array
			
								$image_ImageObject[] = $image_3_4;
			
					}

				// 4:3 aspect ratio source image

					if (
						$input_4_3
						&&
						(
							$nesting_level == 0
							||
							$single_aspect_ratio == '4:3'
						)
					) {

						// Encoding Format (e.g., 'image/jpeg')

							$image_encodingFormat_4_3 = get_post_mime_type( $input_4_3 ) ?? '';

						// Alt Text

							$image_caption_4_3 = get_post_meta( $input_4_3, '_wp_attachment_image_alt', TRUE ) ?? '';

						// Image Data

							$image_4_3_src = wp_get_attachment_image_src( $input_4_3, 'aspect-4-3' ) ?? array();

							if ( $image_4_3_src ) {

								$image_4_3_url = $image_4_3_src[0] ?? '';
								$image_4_3_width = $image_4_3_src[1] ?? '';
								$image_4_3_height = $image_4_3_src[2] ?? '';
								$image_4_3_size = '';

							}

						// ImageObject

							$image_4_3 = array();
							$image_4_3['@id'] = ( isset($url) && !empty($url) ) ? $url . '#' . $page_fragment .  '-4-3' : '';
							$image_4_3['caption'] = $image_caption_4_3 ?? '';
							$image_4_3['contentSize'] = $image_4_3_size ?: '';
							$image_4_3['contentUrl'] = $image_4_3_url ?: '';
							$image_4_3['encodingFormat'] = $image_encodingFormat_4_3 ?? '';
							$image_4_3['height'] = $image_4_3_height ? $image_4_3_height . ' px' : '';
							$image_4_3['width'] = $image_4_3_width ? $image_4_3_width . ' px' : '';

							$image_4_3 = array_filter(
								array_merge(
									$image_ImageObject_base,
									$image_4_3
								)
							);

							// Sort the item array

								ksort($image_4_3);

							// Add to list array

								$image_ImageObject[] = $image_4_3;

					}

				// 16:9 aspect ratio source image

					if (
						$input_16_9
						&&
						(
							$nesting_level == 0
							||
							$single_aspect_ratio == '16:9'
						)
					) {

						// Encoding Format (e.g., 'image/jpeg')

							$image_encodingFormat_16_9 = get_post_mime_type( $input_16_9 ) ?? '';

						// Alt Text

							$image_caption_16_9 = get_post_meta( $input_16_9, '_wp_attachment_image_alt', TRUE ) ?? '';

						// Image Data

							$image_16_9_src = wp_get_attachment_image_src( $input_16_9, 'aspect-16-9' ) ?? array();

							if ( $image_16_9_src ) {

								$image_16_9_url = $image_16_9_src[0] ?? '';
								$image_16_9_width = $image_16_9_src[1] ?? '';
								$image_16_9_height = $image_16_9_src[2] ?? '';
								$image_16_9_size = '';

							}

						// ImageObject

							$image_16_9 = array();
							$image_16_9['@id'] = ( isset($url) && !empty($url) ) ? $url . '#' . $page_fragment .  '-16-9' : '';
							$image_16_9['caption'] = $image_caption_16_9 ?? '';
							$image_16_9['contentSize'] = $image_16_9_size ?: '';
							$image_16_9['contentUrl'] = $image_16_9_url ?: '';
							$image_16_9['encodingFormat'] = $image_encodingFormat_16_9 ?? '';
							$image_16_9['height'] = $image_16_9_height ? $image_16_9_height . ' px' : '';
							$image_16_9['width'] = $image_16_9_width ? $image_16_9_width . ' px' : '';

							$image_16_9 = array_filter(
								array_merge(
									$image_ImageObject_base,
									$image_16_9
								)
							);

							// Sort the item array

								ksort($image_16_9);

							// Add to list array

								$image_ImageObject[] = $image_16_9;

					}

				// Full-size image output
				
					if (
						$input_full
						&&
						$single_aspect_ratio == 'full'
					) {

						// Encoding Format (e.g., 'image/jpeg')

							$image_encodingFormat_full = get_post_mime_type( $input_full ) ?? '';

						// Alt Text

							$image_caption_full = get_post_meta( $input_full, '_wp_attachment_image_alt', TRUE ) ?? '';

						// ImageObject

							$image_ImageObject_base_full = $image_ImageObject_base;

						// Image Data

							$image_full_src = wp_get_attachment_image_src( $input_full, 'aspect-16-9' ) ?? array();

							if ( $image_full_src ) {

								$image_full_url = $image_full_src[0] ?? '';
								$image_full_width = $image_full_src[1] ?? '';
								$image_full_height = $image_full_src[2] ?? '';
								$image_full_size = '';

							}

						// ImageObject

							$image_full = array();
							$image_full['@id'] = ( isset($url) && !empty($url) ) ? $url . '#' . $page_fragment .  '-16-9' : '';
							$image_full['caption'] = $image_caption_full ?? '';
							$image_full['contentSize'] = $image_full_size ?: '';
							$image_full['contentUrl'] = $image_full_url ?: '';
							$image_full['encodingFormat'] = $image_encodingFormat_full ?? '';
							$image_full['height'] = $image_full_height ? $image_full_height . ' px' : '';
							$image_full['width'] = $image_full_width ? $image_full_width . ' px' : '';

							$image_full = array_filter(
								array_merge(
									$image_ImageObject_base,
									$image_full
								)
							);

							// Sort the item array

								ksort($image_full);

							// Add to list array

								$image_ImageObject[] = $image_full;

					}

			return $image_ImageObject;

		}

	// Add data to an array defining schema data for related areas of expertise (MedicalEntity)

		function uamswp_fad_schema_expertise(
			array $repeater, // List of IDs of the area of expertise items
			string $page_url, // Page URL
			int $nesting_level = 1, // Nesting level within the main schema
			string $page_fragment = 'MedicalEntity', // Base fragment identifier
			string $expertise_page = 'overview', // Identify which type of area of expertise page
			int $MedicalEntity_i = 1 // Iteration counter
		) {

			/*

				Expected $expertise_page values:

				'overview' = The root page of an area of expertise or descendant area of expertise
				'fpage_provider' = Fake subpage for related providers
				'fpage_location' = Fake subpage for related locations
				'fpage_expertise_descendant' = Fake subpage for descendant areas of expertise
				'fpage_expertise' = Fake subpage for related areas of expertise
				'fpage_clinical_resource' = Fake subpage for related clinical resources
				'content' = Content page

			 */

			// Common property values

				include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

			// UAMS organization values

				include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/uams.php' );

			// Base list array

				$MedicalEntity_list = array();

			if ( !empty($repeater) ) {

				foreach ( $repeater as $MedicalEntity ) {

					// If post is not published, skip to the next iteration

						if ( get_post_status($MedicalEntity) != 'publish' ) {

							continue;

						}

					// Eliminate PHP errors / reset variables

						$MedicalEntity_item = array(); // Base array
						$MedicalEntity_url = '';
						$MedicalEntity_type = '';
						$MedicalEntity_id = '';
						$MedicalEntity_image = '';
						$MedicalEntity_featured_image = '';
						$MedicalEntity_name = '';
						$MedicalEntity_alternateName = '';
						$MedicalEntity_code = '';
						$MedicalEntity_description = '';
						$MedicalEntity_disambiguatingDescription = '';
						$MedicalEntity_funding = '';
						$MedicalEntity_guideline = '';
						$MedicalEntity_identifier = '';
						$MedicalEntity_image_id = '';
						$MedicalEntity_legalStatus = '';
						$MedicalEntity_mainEntityOfPage = '';
						$MedicalEntity_medicineSystem = '';
						$MedicalEntity_potentialAction = '';
						$MedicalEntity_recognizingAuthority = '';
						$MedicalEntity_relevantSpecialty = array();
						$MedicalEntity_sameAs = '';
						$MedicalEntity_study = '';
						$MedicalEntity_subjectOf = '';
						$MedicalEntity_level = '';
						$ontology_type = '';
						$current_fpage = '';
						$fpage_query = '';
						$MedicalEntity_alternateName_array = '';

						// Reused variables

							$MedicalEntity_additionalType = $MedicalEntity_additionalType ?? '';

					// Get ontology type

						$ontology_type = get_field( 'expertise_type', $MedicalEntity ) ?? true; // Check if 'expertise_type' is not null, and if so, set value to true

					// If the page is not an ontology type, skip to the next iteration

						if ( !$ontology_type ) {

							continue;

						}

					// Fake subpage query and get fake subpage slug

						if ( $ontology_type ) {

							$current_fpage = get_query_var( 'fpage', $MedicalEntity ) ?? ''; // Fake subpage slug
							$fpage_query = $current_fpage ? true : false;

						}

					// Add property values

						// url

							/*
							 * URL of the item.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - URL
							 */

							$MedicalEntity_url = user_trailingslashit( get_permalink($MedicalEntity) );
							$MedicalEntity_item['url'] = $MedicalEntity_url;

						// @type

							$MedicalEntity_type = 'MedicalEntity';

							// Add to schema

								$MedicalEntity_item['@type'] = $MedicalEntity_type;

						// @id

							if ( $nesting_level <= 1 ) {

								// Get values

									$MedicalEntity_id = $MedicalEntity_url . '#' . $MedicalEntity_type;
									// $MedicalEntity_id .= $MedicalEntity_i;
									// $MedicalEntity_id++;

								// Add to schema

									$MedicalEntity_item['@id'] = $MedicalEntity_id;

							}

						// name

							/*
							 * The name of the item.
							 * 
							 * Subproperty of:
							 * 
							 *     - rdfs:label
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							// Get values

								$MedicalEntity_name = get_the_title($MedicalEntity) ?: '';

							// Add to item values

								if ( $MedicalEntity_name ) {

									$MedicalEntity_item['name'] = $MedicalEntity_name;

								}

						// additionalType

							/*
							 * An additional type for the item, typically used for adding more specific types 
							 * from external vocabularies in microdata syntax. This is a relationship between 
							 * something and a class that the thing is in. Typically the value is a 
							 * URI-identified RDF class, and in this case corresponds to the use of rdf:type 
							 * in RDF. Text values can be used sparingly, for cases where useful information 
							 * can be added without their being an appropriate schema to reference. In the 
							 * case of text values, the class label should follow the schema.org style guide.
							 * 
							 * Subproperty of:
							 *     - rdf:type
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 *     - URL
							 */

							// Get values

								// Get level of the item within the area of expertise page hierarchy

									$MedicalEntity_level = 1 + count(
										get_ancestors(
											$MedicalEntity, // $object_id  // int // Optional // The ID of the object // Default: 0
											'expertise', // $object_type // string // Optional // The type of object for which we'll be retrieving ancestors. Accepts a post type or a taxonomy name. // Default: ''
											'post_type' // $resource_type // string // Optional // Type of resource $object_type is. Accepts 'post_type' or 'taxonomy'. // Default: ''
										)
									) ?? '';

								// Set value relevant to level
									
									if ( $MedicalEntity_level ) {

										if ( $MedicalEntity_level == 1 ) {

											$MedicalEntity_additionalType = 'https://www.wikidata.org/wiki/Q930752'; // Wikidata entry for 'medical specialty'

										} else {

											$MedicalEntity_additionalType = 'https://www.wikidata.org/wiki/Q7632042'; // Wikidata entry for 'subspecialty'

										}

									}

							// Add to item values

								if ( $MedicalEntity_additionalType ) {

									$MedicalEntity_item['additionalType'] = $MedicalEntity_additionalType;

								}

						// alternateName

							/*
							 * An alias for the item.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							// Get repeater field value

								$MedicalEntity_alternateName_array = get_field( 'expertise_alternate_names', $MedicalEntity ) ?: array();

							// Get item values

								$MedicalEntity_alternateName = uamswp_fad_schema_alternatename(
									$MedicalEntity_alternateName_array, // alternateName repeater field
									'alternate_text' // alternateName item field name
								);

							// Add to schema

								if ( $MedicalEntity_alternateName ) {

									$MedicalEntity_item['alternateName'] = $MedicalEntity_alternateName;

								}

						// code

							/*
							 * A medical code for the entity, taken from a controlled vocabulary or ontology 
							 * such as ICD-9, DiseasesDB, MeSH, SNOMED-CT, RxNorm, etc.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - MedicalCode
							 */

							// Get values

								// Code repeater

									$MedicalEntity_code_array = get_field( 'schema_medicalcode', $MedicalEntity ) ?: array();

								// Health Care Provider Taxonomy Code Set taxonomy field

									$MedicalEntity_nucc_array = get_field( 'schema_nucc_multiple', $MedicalEntity ) ?: array();

							// Get item values

								$MedicalEntity_code = uamswp_fad_schema_code(
									$MedicalEntity_code_array, // code repeater field
									$MedicalEntity_nucc_array // Health Care Provider Taxonomy Code Set taxonomy field
								);

							// Add to schema
							
								if ( $MedicalEntity_code ) {

									$MedicalEntity_item['code'] = $MedicalEntity_code;

								}

						// description

							/*
							 * A description of the item.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 *     - TextObject
							 */

							// Get values

								$MedicalEntity_description = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_description ) {
	
										$MedicalEntity_item['description'] = $MedicalEntity_description;
	
									}

						// disambiguatingDescription

							/*
							 * A sub property of description. A short description of the item used to 
							 * disambiguate from other, similar items. Information from other properties (in 
							 * particular, name) may be necessary for the description to be useful for 
							 * disambiguation.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							// Get values

								$MedicalEntity_disambiguatingDescription = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_disambiguatingDescription ) {
	
										$MedicalEntity_item['disambiguatingDescription'] = $MedicalEntity_disambiguatingDescription;
	
									}

						// funding

							/*
							 * A Grant that directly or indirectly provide funding or sponsorship for this 
							 * item. See also ownershipFundingInfo.
							 * 
							 * Inverse-property:
							 * 
							 *     - fundedItem
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Grant
							 * 
							 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
							 * feedback and adoption from applications and websites can help improve their 
							 * definitions.
							 */

							// Get values

								$MedicalEntity_funding = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_funding ) {
	
										$MedicalEntity_item['funding'] = $MedicalEntity_funding;
	
									}

						// guideline

							/*
							 * A medical guideline related to this entity.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - MedicalGuideline
							 */

							// Get values

								$MedicalEntity_guideline = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_guideline ) {
	
										$MedicalEntity_item['guideline'] = $MedicalEntity_guideline;
	
									}

						// identifier

							/* 
							 * The identifier property represents any kind of identifier for any kind of 
							 * Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated 
							 * properties for representing many of these, either as textual strings or as 
							 * URL (URI) links. See background notes at 
							 * https://schema.org/docs/datamodel.html#mainEntityBackground for details.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - PropertyValue
							 *     - Text
							 *     - URL
							 */

							// Get values

								$MedicalEntity_identifier = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_identifier ) {
	
										$MedicalEntity_item['identifier'] = $MedicalEntity_identifier;
	
									}

						// image

							/*
							 * An image of the item. This can be a URL or a fully described ImageObject.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - ImageObject
							 *     - URL
							 */

							// Get values

								// Featured image

									if (
										$expertise_page == 'overview'
										||
										$expertise_page == 'content'
									) {

										$MedicalEntity_image_id = get_field( '_thumbnail_id', $MedicalEntity ) ?? '';

									} elseif ( $expertise_page == 'fpage_provider' ) {

										$MedicalEntity_image_id = get_field( 'expertise_providers_fpage_featured_image', $MedicalEntity ) ?? '';

									} elseif ( $expertise_page == 'fpage_location' ) {

										$MedicalEntity_image_id = get_field( 'expertise_locations_fpage_featured_image', $MedicalEntity ) ?? '';

									} elseif ( $expertise_page == 'fpage_expertise_descendant' ) {

										$MedicalEntity_image_id = get_field( 'expertise_descendant_fpage_featured_image', $MedicalEntity ) ?? '';

									} elseif ( $expertise_page == 'fpage_expertise' ) {

										$MedicalEntity_image_id = get_field( 'expertise_associated_fpage_featured_image', $MedicalEntity ) ?? '';

									} elseif ( $expertise_page == 'fpage_clinical_resource' ) {

										$MedicalEntity_image_id = get_field( 'expertise_clinical_resources_fpage_featured_image', $MedicalEntity ) ?? '';

									}

								// Create ImageObject values array

									if ( $MedicalEntity_image_id ) {

										$MedicalEntity_image = uamswp_fad_schema_imageobject_thumbnails(
											$MedicalEntity_url, // URL of entity with which the image is associated
											$nesting_level, // Nesting level within the main schema
											'16:9', // Aspect ratio to use if only on image is included // enum('1:1', '3:4', '4:3', '16:9')
											'Image', // Base fragment identifier
											$MedicalEntity_image_id, // ID of image to use for 1:1 aspect ratio
											0, // ID of image to use for 3:4 aspect ratio
											$MedicalEntity_image_id, // ID of image to use for 4:3 aspect ratio
											$MedicalEntity_image_id, // ID of image to use for 16:9 aspect ratio
											0 // ID of image to use for full image
										) ?? array();

									}

							// Add to schema

								if ( $MedicalEntity_image ) {

									$MedicalEntity_item['image'] = $MedicalEntity_image;

								}

						// legalStatus

							/*
							 * The drug or supplement's legal status, including any controlled substance 
							 * schedules that apply.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - DrugLegalStatus
							 *     - MedicalEnumeration
							 *     - Text
							 */

							// Get values

								$MedicalEntity_legalStatus = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_legalStatus ) {
	
										$MedicalEntity_item['legalStatus'] = $MedicalEntity_legalStatus;
	
									}

						// mainEntityOfPage

							/*
							 * Indicates a page (or other CreativeWork) for which this thing is the main 
							 * entity being described. See background notes at 
							 * https://schema.org/docs/datamodel.html#mainEntityBackground for details.
							 * 
							 * Inverse-property: mainEntity
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - CreativeWork
							 *     - URL
							 */

							// Get values

								$MedicalEntity_mainEntityOfPage = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_mainEntityOfPage ) {
	
										$MedicalEntity_item['mainEntityOfPage'] = $MedicalEntity_mainEntityOfPage;
	
									}

						// medicineSystem

							/*
							 * The system of medicine that includes this MedicalEntity 
							 * (e.g., 'evidence-based,' 'homeopathic,' 'chiropractic').
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - MedicineSystem
							 */

							// Get values

								$MedicalEntity_medicineSystem = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_medicineSystem ) {
	
										$MedicalEntity_item['medicineSystem'] = $MedicalEntity_medicineSystem;
	
									}

						// potentialAction

							/*
							 * Indicates a potential Action, which describes an idealized action in which this 
							 * thing would play an 'object' role.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Action
							 */

							// Get values

								$MedicalEntity_potentialAction = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_potentialAction ) {
	
										$MedicalEntity_item['potentialAction'] = $MedicalEntity_potentialAction;
	
									}

						// recognizingAuthority

							/*
							 * If applicable, the organization that officially recognizes this entity as part 
							 * of its endorsed system of medicine.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Organization
							 */

							// Get values

								$MedicalEntity_recognizingAuthority = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_recognizingAuthority ) {
	
										$MedicalEntity_item['recognizingAuthority'] = $MedicalEntity_recognizingAuthority;
	
									}

						// relevantSpecialty

							/*
							 * If applicable, a medical specialty in which this entity is relevant.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - MedicalSpecialty
							 */

							// Get values

								$MedicalEntity_relevantSpecialty = get_field( 'schema_medicalspecialty_multiple', $MedicalEntity ) ?? array();

								// Clean up list array

									$MedicalEntity_relevantSpecialty = array_filter($MedicalEntity_relevantSpecialty);
									$MedicalEntity_relevantSpecialty = array_values($MedicalEntity_relevantSpecialty);

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($MedicalEntity_relevantSpecialty);

								// Add to item values
	
									if ( $MedicalEntity_relevantSpecialty ) {
	
										$MedicalEntity_item['relevantSpecialty'] = $MedicalEntity_relevantSpecialty;
	
									}

						// sameAs

							/*
							 * URL of a reference Web page that unambiguously indicates the item's identity 
							 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official 
							 * website).
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - URL
							 */

							// Get values

								// Get repeater field value
								
									$MedicalEntity_sameAs_array = get_field( 'schema_sameas', $MedicalEntity );

								// Base list array
		
									$MedicalEntity_sameAs = array();
		
								// Add each row to the list array
		
									if ( $MedicalEntity_sameAs_array ) {
		
										foreach ( $MedicalEntity_sameAs_array as $sameAs ) {
		
											$MedicalEntity_sameAs[] = $sameAs['schema_sameas_url'];
		
										}
		
									}
	
							// Clean up list array

								if ( $MedicalEntity_sameAs ) {

									$MedicalEntity_sameAs = array_unique($MedicalEntity_sameAs);
									$MedicalEntity_sameAs = array_filter($MedicalEntity_sameAs);
									$MedicalEntity_sameAs = array_values($MedicalEntity_sameAs);

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($MedicalEntity_sameAs);

								}

							// Add to schema
	
								if ( $MedicalEntity_sameAs ) {
	
									$MedicalEntity_item['sameAs'] = $MedicalEntity_sameAs;
	
								}
			
						// study

							/*
							 * A medical study or trial related to this entity.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - MedicalStudy
							 */

							// Get values

								$MedicalEntity_study = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_study ) {
	
										$MedicalEntity_item['study'] = $MedicalEntity_study;
	
									}

						// subjectOf

							/*
							 * A CreativeWork or Event about this Thing.
							 * 
							 * Inverse-property: about
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - CreativeWork
							 *     - Event
							 */

							// Get values

								$MedicalEntity_subjectOf = 'foo' ?? '';

								// Add to item values
	
									if ( $MedicalEntity_subjectOf ) {
	
										$MedicalEntity_item['subjectOf'] = $MedicalEntity_subjectOf;
	
									}

					// Sort array

						ksort($MedicalEntity_item);

					// Add to list of conditions

						$MedicalEntity_list[] = $MedicalEntity_item;

				} // endforeach ( $repeater as $MedicalEntity )

				// Clean up list array

					$MedicalEntity_list = array_filter($MedicalEntity_list);
					$MedicalEntity_list = array_values($MedicalEntity_list);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($MedicalEntity_list);

			} // endif ( !empty($repeater) )

			return $MedicalEntity_list;
	
		}

	// Add data to an array defining schema data for CreativeWork (a.k.a. related clinical resources)

		function uamswp_fad_schema_creativework(
			array $repeater, // List of IDs of the clinical resource items
			string $page_url, // Page URL
			int $nesting_level = 1, // Nesting level within the main schema
			string $page_fragment = 'CreativeWork', // Base fragment identifier
			int $CreativeWork_i = 1 // Iteration counter
		) {

			// Common property values

				include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

			// UAMS organization values

				include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/uams.php' );

			// Base list array

				$CreativeWork_list = array();

			if ( !empty($repeater) ) {

				foreach ( $repeater as $CreativeWork ) {

					// Retrieve the value of the item transient

						uamswp_fad_get_transient( 'item_' . $CreativeWork, $CreativeWork_item, __FUNCTION__ );

					if ( !empty( $CreativeWork_item ) ) {

						/* 
						 * The transient exists.
						 * Return the variable.
						 */

						// Add to list of conditions

							$CreativeWork_list[] = $CreativeWork_item;

					} else {

						/* 
						 * The transient does not exist.
						 * Define the variable again.
						 */

						// If post is not published, skip to the next iteration

							if ( get_post_status($CreativeWork) != 'publish' ) {

								continue;

							}

						// Eliminate PHP errors / reset variables

							$CreativeWork_abstract = '';
							$CreativeWork_additionalType = '';
							$CreativeWork_alternateName = '';
							$CreativeWork_articleBody = '';
							$CreativeWork_articleBody_count = '';
							$CreativeWork_asset_caption_query = '';
							$CreativeWork_asset_description = '';
							$CreativeWork_asset_duration = '';
							$CreativeWork_asset_embedUrl = '';
							$CreativeWork_asset_filesize = '';
							$CreativeWork_asset_height = '';
							$CreativeWork_asset_id = '';
							$CreativeWork_asset_info = '';
							$CreativeWork_asset_info = '';
							$CreativeWork_asset_parsed = '';
							$CreativeWork_asset_path = '';
							$CreativeWork_asset_published = '';
							$CreativeWork_asset_thumbnail = '';
							$CreativeWork_asset_title = '';
							$CreativeWork_asset_url = '';
							$CreativeWork_asset_width = '';
							$CreativeWork_contentUrl = '';
							$CreativeWork_creator = '';
							$CreativeWork_dateModified = '';
							$CreativeWork_datePublished = '';
							$CreativeWork_description = '';
							$CreativeWork_description_count = '';
							$CreativeWork_duration = '';
							$CreativeWork_embeddedTextCaption = '';
							$CreativeWork_embeddedTextCaption_count = '';
							$CreativeWork_embedUrl = '';
							$CreativeWork_encodingFormat = '';
							$CreativeWork_featured_image_1_1_size = '';
							$CreativeWork_featured_image_1_1_src = array();
							$CreativeWork_featured_image_1_1_url = '';
							$CreativeWork_featured_image_1_1_width = '';
							$CreativeWork_featured_image_16_9_height = '';
							$CreativeWork_featured_image_16_9_size = '';
							$CreativeWork_featured_image_16_9_src = array();
							$CreativeWork_featured_image_16_9_url = '';
							$CreativeWork_featured_image_16_9_width = '';
							$CreativeWork_featured_image_3_4_height = '';
							$CreativeWork_featured_image_3_4_size = '';
							$CreativeWork_featured_image_3_4_src = array();
							$CreativeWork_featured_image_3_4_url = '';
							$CreativeWork_featured_image_3_4_width = '';
							$CreativeWork_featured_image_4_3_height = '';
							$CreativeWork_featured_image_4_3_size = '';
							$CreativeWork_featured_image_4_3_src = array();
							$CreativeWork_featured_image_4_3_url = '';
							$CreativeWork_featured_image_4_3_width = '';
							$CreativeWork_featured_image_caption = '';
							$CreativeWork_featured_image_encodingFormat = '';
							$CreativeWork_featured_image_id = '';
							$CreativeWork_featured_image_ImageObject = array();
							$CreativeWork_featured_image_ImageObject_base = array();
							$CreativeWork_featured_image_square_encodingFormat = '';
							$CreativeWork_featured_image_square_id = '';
							$CreativeWork_hasDigitalDocumentPermission = '';
							$CreativeWork_height = '';
							$CreativeWork_id = '';
							$CreativeWork_image = '';
							$CreativeWork_isAccessibleForFree = '';
							$CreativeWork_isPartOf = '';
							$CreativeWork_mainEntityOfPage = '';
							$CreativeWork_name = '';
							$CreativeWork_nci_query = '';
							$CreativeWork_representativeOfPage = '';
							$CreativeWork_sameAs = '';
							$CreativeWork_sourceOrganization = '';
							$CreativeWork_speakable = '';
							$CreativeWork_subjectOf = '';
							$CreativeWork_syndication_org = '';
							$CreativeWork_syndication_query = '';
							$CreativeWork_syndication_URL = '';
							$CreativeWork_thumbnail = array();
							$CreativeWork_timeRequired = '';
							$CreativeWork_timeRequired_seconds = '';
							$CreativeWork_transcript = '';
							$CreativeWork_transcript_count = '';
							$CreativeWork_video = '';
							$CreativeWork_videoFrameSize = '';
							$CreativeWork_videoQuality = '';
							$CreativeWork_width = '';
							$CreativeWork_word_count = 0;
							$CreativeWork_wordCount = '';$CreativeWork_featured_image_1_1_height = '';

							// Reused variables

								$CreativeWork_audience = $CreativeWork_audience ?? '';

						// Values Map

							$CreativeWork_type_values = array(
								'all' => array(
									'@type' => 'CreativeWork',
									'properties' => array(
										'abstract',
										'audience',
										'creator',
										'dateModified',
										'datePublished',
										'description',
										'isAccessibleForFree',
										'isPartOf',
										'mainEntityOfPage',
										'name',
										'sameAs',
										'sourceOrganization',
										'speakable',
										'subjectOf',
										'url'
									)
								),
								'text' => array(
									'@type' => 'Article',
									'properties' => array(
										'articleBody',
										'image',
										'timeRequired',
										'wordCount'
									)
								),
								'infographic' => array(
									'@type' => 'ImageObject',
									'properties' => array(
										'additionalType',
										'contentSize',
										'contentUrl',
										'embeddedTextCaption',
										'encodingFormat',
										'height',
										'representativeOfPage',
										'thumbnail',
										'timeRequired',
										'width'
									)
								),
								'video' => array(
									'@type' => 'VideoObject',
									'properties' => array(
										'alternateName',
										'duration',
										'embedUrl',
										'thumbnail',
										'timeRequired',
										'transcript',
										'videoFrameSize',
										'videoQuality'
									)
								),
								'doc' => array(
									'@type' => 'DigitalDocument',
									'properties' => array(
										'hasDigitalDocumentPermission'
									)
								)
							);

							// Merge common property values into each resource type's property values

								foreach ( $CreativeWork_type_values as &$item ) {

									if ( $item != 'all ') {

										$item['properties'] = array_merge(
											$item['properties'],
											$CreativeWork_type_values['all']['properties']
										);

									}
								}

						// Base array

							$CreativeWork_item = array();

						// url

							/*
							 * URL of the item.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - URL
							 */

							$CreativeWork_url = user_trailingslashit( get_permalink($CreativeWork) );
							$CreativeWork_item['url'] = $CreativeWork_url;

						// @type

							// Base value

								$CreativeWork_type = $CreativeWork_type_values['all']['@type'];
								$CreativeWork_properties = $CreativeWork_type_values['all']['properties'];

							// Get value based on clinical resource type

								// Resource type

									$CreativeWork_resource_type = get_field( 'clinical_resource_type', $CreativeWork )['value'] ?: '';

								// Get value from values map

									if ( $CreativeWork_resource_type ) {

										$CreativeWork_type = isset( $CreativeWork_type_values[$CreativeWork_resource_type]['@type'] ) ? $CreativeWork_type_values[$CreativeWork_resource_type]['@type'] : $CreativeWork_type;
										$CreativeWork_properties = isset( $CreativeWork_type_values[$CreativeWork_resource_type]['properties'] ) ? $CreativeWork_type_values[$CreativeWork_resource_type]['properties'] : $CreativeWork_properties;

									}

							// Add to schema

								$CreativeWork_item['@type'] = $CreativeWork_type;

						// @id

							if ( $nesting_level <= 1 ) {

								$CreativeWork_id = $CreativeWork_url . '#' . $CreativeWork_type;
								// $CreativeWork_id .= $CreativeWork_i;
								$CreativeWork_item['@id'] = $CreativeWork_id;
								// $CreativeWork_i++;

							} // endif ( $nesting_level == 1 )

						// Asset ID

							if ( $nesting_level == 0 ) {

								if ( $CreativeWork_resource_type == 'infographic' ) {

									// Infographic image id

										$CreativeWork_asset_id = get_field( 'clinical_resource_infographic', $CreativeWork ) ?: '';

								}

							}

						// Syndication values

							if ( $nesting_level == 0 ) {

								$CreativeWork_syndication_query = get_field( 'clinical_resource_syndicated', $CreativeWork ) ?: false;

								// NCI syndication query

									if ( $CreativeWork_syndication_query ) {

										$CreativeWork_nci_query = get_field( 'clinical_resource_text_nci_query', $CreativeWork ) ?: false;

									}

								// Syndication source URL

									if ( $CreativeWork_syndication_query ) {

										$CreativeWork_syndication_URL = get_field( 'clinical_resource_syndication_url', $CreativeWork ) ?: false;

									}

								// Syndication source organization

									if (
										$CreativeWork_syndication_query
										&&
										$CreativeWork_nci_query
									) {

										$CreativeWork_syndication_org = array(
											'@type' => 'ResearchOrganization',
											'name' => 'National Cancer Institute',
											'sameAs' => array(
												'http://id.loc.gov/authorities/names/n79107940',
												'https://www.wikidata.org/wiki/Q664846'
											),
											'url' => 'https://www.cancer.gov/'
										);

									}

							}

						// Get image info

							if (
								$CreativeWork_resource_type == 'infographic'
								&&
								$nesting_level == 0
							) {

								// Full infographic image

									// URL, width, height

										$CreativeWork_asset_info = wp_get_attachment_image_src( $CreativeWork_asset_id, 'full' ) ?: '';

										if ( $CreativeWork_asset_info ) {

											$CreativeWork_asset_url = $CreativeWork_asset_info[0] ?? '';
											$CreativeWork_asset_width = $CreativeWork_asset_info[1] ?? '';
											$CreativeWork_asset_height = $CreativeWork_asset_info[2] ?? '';

										}

									// File size

										// Asset file path

											$CreativeWork_asset_path = get_attached_file( $CreativeWork_asset_id ) ?: '';

										// Asset file size

											$CreativeWork_asset_filesize = filesize( $CreativeWork_asset_path ) ?: '';

										// Formatted asset file size

											$CreativeWork_asset_filesize = size_format( $CreativeWork_asset_filesize, 2 ) ?: '';

							} elseif (
								in_array( 'image', $CreativeWork_properties )
								||
								in_array( 'thumbnail', $CreativeWork_properties )
							) {

								// Get featured image values

									// 16:9 aspect ratio source image

										$CreativeWork_featured_image_id = get_field( '_thumbnail_id', $CreativeWork ) ?? '';

									// 1:1 aspect ratio source image
									
										$CreativeWork_featured_image_square_id = get_field( 'clinical_resource_image_square', $CreativeWork ) ?? $CreativeWork_featured_image_id;

								// Create ImageObject values arrays

									$CreativeWork_image = uamswp_fad_schema_imageobject_thumbnails(
										$CreativeWork_url, // URL of entity with which the image is associated
										$nesting_level, // Nesting level within the main schema
										'16:9', // Aspect ratio to use if only on image is included // enum('1:1', '3:4', '4:3', '16:9')
										'Image', // Base fragment identifier
										$CreativeWork_featured_image_square_id, // ID of image to use for 1:1 aspect ratio
										0, // ID of image to use for 3:4 aspect ratio
										$CreativeWork_featured_image_id, // ID of image to use for 4:3 aspect ratio
										$CreativeWork_featured_image_id, // ID of image to use for 16:9 aspect ratio
										0 // ID of image to use for full image
									) ?? array();
									
									$CreativeWork_thumbnail = $CreativeWork_image;

							}

						// Get video info

							if ( $CreativeWork_resource_type == 'video' ) {

								// Video URL

									$CreativeWork_video = get_field( 'clinical_resource_video', $CreativeWork ) ?: '';

								// Video info

									if ( $nesting_level == 0 ) {

											// Parse the URL and return its components

												$CreativeWork_asset_parsed = parse_url($CreativeWork_video);

												// Parse the query string into variables
												
													parse_str($CreativeWork_asset_parsed['query'], $CreativeWork_asset_parsed['query']);

											if (
												str_contains( $CreativeWork_asset_parsed['host'], 'youtube' )
												||
												str_contains( $CreativeWork_asset_parsed['host'], 'youtu.be' )
											) {

												// If YouTube

													// Embed URL

														$CreativeWork_asset_embedUrl = $CreativeWork_asset_parsed['query']['v'] ? 'https://www.youtube.com/embed/' . $CreativeWork_asset_parsed['query']['v'] : '';

													// Get info from video

														$CreativeWork_asset_info = uamswp_fad_youtube_info( $CreativeWork_video ) ?? array();

														// Title (snippet.title)

															$CreativeWork_asset_title = $CreativeWork_asset_info['title'] ?? '';

														// Thumbnail URL

															// MaxRes Thumbnail URL, 1280x720 (snippet.thumbnails.maxres.url)

																$CreativeWork_asset_thumbnail = $CreativeWork_asset_info['HQthumbUrl'] ?? array();

															// Fallback value: High Thumbnail URL, 480x360 (snippet.thumbnails.high.url)

																if ( !$CreativeWork_asset_thumbnail ) {

																	$CreativeWork_asset_thumbnail = $CreativeWork_asset_info['thumbUrl'] ?? array(); // High Thumbnail URL, 480x360 (snippet.thumbnails.high.url)

																}

														// Published date and time (snippet.publishedAt)

															$CreativeWork_asset_published = $CreativeWork_asset_info['dateField'] ?? '';

														// Duration (contentDetails.duration)

															$CreativeWork_asset_duration = $CreativeWork_asset_info['duration'] ?? '';

														// Description (snippet.description)

															$CreativeWork_asset_description = $CreativeWork_asset_info['description'] ?? '';

														// Whether captions are available for the video (contentDetails.caption)

															$CreativeWork_asset_caption_query = $CreativeWork_asset_info['captions_data'] ?? '';
															$CreativeWork_asset_caption_query = ( $CreativeWork_asset_caption_query == 'true' ) ? true : false;

														// Video quality: high definition (hd) or standard definition (sd) (contentDetails.definition)

															/* No info on this returned from function */

															$CreativeWork_asset_videoQuality = '';

														// Frame size

															/* No info on this returned from function */

															$CreativeWork_asset_videoFrameSize = '';

											} elseif ( str_contains( $CreativeWork_asset_parsed['host'], 'vimeo' ) ) {

												// If Vimeo

													// Embed URL

														$CreativeWork_asset_embedUrl = $CreativeWork_asset_parsed['path'] ? 'https://www.youtube.com/embed/' . $CreativeWork_asset_parsed['path']: '';

											}

									}

							}

						// name

							/*
							 * The name of the item.
							 * 
							 * Subproperty of:
							 * 
							 *     - rdfs:label
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							if ( in_array( 'name', $CreativeWork_properties ) ) {

								// Get values

									$CreativeWork_name = get_the_title($CreativeWork) ?: '';

								// Add to item values

									if ( $CreativeWork_name ) {

										$CreativeWork_item['name'] = $CreativeWork_name;

									}

							}

						// abstract

							/*
							 * An abstract is a short description that summarizes a CreativeWork.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 * 
							 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
							 * feedback and adoption from applications and websites can help improve their 
							 * definitions.
							 */

							if ( in_array( 'abstract', $CreativeWork_properties ) ) {

								// Get values

									$CreativeWork_abstract = get_field( 'clinical_resource_excerpt', $CreativeWork ) ?: '';

								// Add to item values

									if ( $CreativeWork_abstract ) {

										$CreativeWork_item['abstract'] = $CreativeWork_abstract;

									}

							}

						// additionalType

							/*
							 * An additional type for the item, typically used for adding more specific types 
							 * from external vocabularies in microdata syntax. This is a relationship between 
							 * something and a class that the thing is in. Typically the value is a 
							 * URI-identified RDF class, and in this case corresponds to the use of rdf:type 
							 * in RDF. Text values can be used sparingly, for cases where useful information 
							 * can be added without their being an appropriate schema to reference. In the 
							 * case of text values, the class label should follow the schema.org style guide.
							 * 
							 * Subproperty of:
							 *     - rdf:type
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 *     - URL
							 */

							if ( in_array( 'additionalType', $CreativeWork_properties ) ) {

								// Get values

									if ( $CreativeWork_resource_type == 'infographic' ) {

										$CreativeWork_additionalType = 'https://www.wikidata.org/wiki/Q845734'; // Wikidata entry for 'infographic'

									}

								// Add to item values

									if ( $CreativeWork_additionalType ) {

										$CreativeWork_item['additionalType'] = $CreativeWork_additionalType;

									}

							}

						// alternateName

							/*
							 * An alias for the item.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							if (
								in_array( 'alternateName', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									if ( $CreativeWork_resource_type == 'video' ) {

										$CreativeWork_alternateName = $CreativeWork_asset_title ?? '';

									}

								// Add to item values

									if ( $CreativeWork_alternateName ) {

										$CreativeWork_item['alternateName'] = $CreativeWork_alternateName;

									}

							}

						// articleBody

							/*
							 * The actual body of the article.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							if (
								in_array( 'articleBody', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values
								
									$CreativeWork_nci_query = get_field( 'clinical_resource_text_nci_query', $CreativeWork ) ?: false;

									if ( !$CreativeWork_nci_query ) {

										$CreativeWork_articleBody = get_field( 'clinical_resource_text', $CreativeWork )  ?: '';

									}

								// Clean up values

									if ( $CreativeWork_articleBody ) {

										// Strip all tags

											$CreativeWork_articleBody = wp_strip_all_tags($CreativeWork_articleBody);
											$CreativeWork_articleBody = str_replace("\n", ' ', $CreativeWork_articleBody); // Strip line breaks

										// Make attribute-friendly

											$CreativeWork_articleBody = uamswp_attr_conversion($CreativeWork_articleBody);

									}

								// Add to item values

									if ( $CreativeWork_articleBody ) {

										$CreativeWork_item['articleBody'] = $CreativeWork_articleBody;

									}

							}

						// audience

							/*
							 * An intended audience, i.e. a group for whom something was created.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Audience
							 */

							if (
								in_array( 'audience', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_audience = $CreativeWork_audience ?? $schema_common_audience;

								// Add to item values

									if ( $CreativeWork_audience ) {

										$CreativeWork_item['audience'] = $CreativeWork_audience;

									}

							}

						// contentSize

							/*
							 * File size in (mega/kilo)bytes.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							if (
								in_array( 'contentSize', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_contentSize = $CreativeWork_asset_filesize ?? '';

								// Add to item values

									if ( $CreativeWork_contentSize ) {

										$CreativeWork_item['contentSize'] = $CreativeWork_contentSize;

									}

							}

						// contentUrl

							/*
							 * Actual bytes of the media object, for example the image file or video file.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - URL
							 */

							if (
								in_array( 'contentUrl', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_contentUrl = $CreativeWork_asset_url ?? '';

								// Add to item values

									if ( $CreativeWork_contentUrl ) {

										$CreativeWork_item['contentUrl'] = $CreativeWork_contentUrl;

									}

							}

						// creator

							/*
							 * The creator/author of this CreativeWork. This is the same as the Author 
							 * property for CreativeWork.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Organization
							 *     - Person
							 */

							if (
								in_array( 'creator', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									if ( $CreativeWork_syndication_query ) {

										$CreativeWork_creator = $CreativeWork_syndication_org;

									} else {

										$CreativeWork_creator = $schema_base_org_uams_health_ref;

									}

								// Add to item values

									if ( $CreativeWork_creator ) {

										$CreativeWork_item['creator'] = $CreativeWork_creator;

										// If there is only one item, flatten the multi-dimensional array by one step

											uamswp_fad_flatten_multidimensional_array($CreativeWork_item['creator']);

									}

							}

						// dateModified

							/*
							 * The date on which the CreativeWork was most recently modified or when the 
							 * item's entry was modified within a DataFeed.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Date
							 *     - DateTime
							 */

							if (
								in_array( 'dateModified', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_dateModified = get_the_modified_date( 'Y-m-d', $CreativeWork ); // ISO 8601 date format

								// Add to item values

									if ( $CreativeWork_dateModified ) {

										$CreativeWork_item['dateModified'] = $CreativeWork_dateModified;

									}

							}

						// datePublished

							/*
							 * Date of first broadcast/publication.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Date
							 *     - DateTime
							 */

							if (
								in_array( 'datePublished', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_datePublished = get_the_date( 'Y-m-d', $CreativeWork ); // ISO 8601 date format

								// Add to item values

									if ( $CreativeWork_datePublished ) {

										$CreativeWork_item['datePublished'] = $CreativeWork_datePublished;

									}

							}

						// description

							/*
							 * A description of the item.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 *     - TextObject
							 */

							// Get values

								if ( $CreativeWork_resource_type == 'text' ) {

									// Article

										if ( in_array( 'abstract', $CreativeWork_properties ) ) {

											$CreativeWork_description = $CreativeWork_abstract ?? '';

										} else {

											$CreativeWork_description = get_field( 'clinical_resource_excerpt', $CreativeWork ) ?: '';

										}

								} elseif ( $CreativeWork_resource_type == 'infographic' ) {

									// Infographic

										$CreativeWork_description = get_field( 'clinical_resource_infographic_descr', $CreativeWork ) ?: '';

										// Fallback value

											if ( !$CreativeWork_description ) {

												if ( in_array( 'abstract', $CreativeWork_properties ) ) {

													$CreativeWork_description = $CreativeWork_abstract ?? '';

												} else {

													$CreativeWork_description = get_field( 'clinical_resource_excerpt', $CreativeWork ) ?: '';

												}

											}

								} elseif ( $CreativeWork_resource_type == 'video' ) {

									// Video

										$CreativeWork_description = get_field( 'clinical_resource_video_descr', $CreativeWork ) ?: '';

										// Fallback value

											if ( !$CreativeWork_description ) {

												if ( in_array( 'abstract', $CreativeWork_properties ) ) {

													$CreativeWork_description = $CreativeWork_abstract ?? '';

												} else {

													$CreativeWork_description = get_field( 'clinical_resource_excerpt', $CreativeWork ) ?: '';

												}

											}

								} elseif ( $CreativeWork_resource_type == 'doc' ) {

									// Document

										$CreativeWork_description = get_field( 'clinical_resource_document_descr', $CreativeWork ) ?: '';

										// Fallback value

											if ( !$CreativeWork_description ) {

												if ( in_array( 'abstract', $CreativeWork_properties ) ) {

													$CreativeWork_description = $CreativeWork_abstract ?? '';

												} else {

													$CreativeWork_description = get_field( 'clinical_resource_excerpt', $CreativeWork ) ?: '';

												}

											}

								}

							// Clean up values

								if ( $CreativeWork_description ) {

									// Strip all tags

										$CreativeWork_description = wp_strip_all_tags($CreativeWork_description);
										$CreativeWork_description = str_replace("\n", ' ', $CreativeWork_description); // Strip line breaks

									// Make attribute-friendly

										$CreativeWork_description = uamswp_attr_conversion($CreativeWork_description);

								}

							// Add to item values

								if ( $CreativeWork_description ) {

									$CreativeWork_item['description'] = $CreativeWork_description;

								}

						// duration

							/*
							 * The duration of the item (movie, audio recording, event, etc.) in ISO 8601 date 
							 * format.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Duration
							 */

							if (
								in_array( 'duration', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_duration = $CreativeWork_asset_duration ?? '';

								// Add to item values

									if ( $CreativeWork_duration ) {

										$CreativeWork_item['duration'] = $CreativeWork_duration;

									}

							}

						// embeddedTextCaption

							/*
							 * Represents textual captioning from a MediaObject, e.g. text of a 'meme'.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 * 
							 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
							 * feedback and adoption from applications and websites can help improve their 
							 * definitions.
							 */

							if ( in_array( 'embeddedTextCaption', $CreativeWork_properties ) ) {

								// Get values

									$CreativeWork_embeddedTextCaption = get_field( 'clinical_resource_infographic_transcript', $CreativeWork ) ?: '';

								// Clean up values

									if ( $CreativeWork_embeddedTextCaption ) {

										// Strip all tags

											$CreativeWork_embeddedTextCaption = wp_strip_all_tags($CreativeWork_embeddedTextCaption);
											$CreativeWork_embeddedTextCaption = str_replace("\n", ' ', $CreativeWork_embeddedTextCaption); // Strip line breaks

										// Make attribute-friendly

											$CreativeWork_embeddedTextCaption = uamswp_attr_conversion($CreativeWork_embeddedTextCaption);

									}

								// Add to item values

									if ( $CreativeWork_embeddedTextCaption ) {

										$CreativeWork_item['embeddedTextCaption'] = $CreativeWork_embeddedTextCaption;

									}

							}

						// embedUrl

							/*
							 * A URL pointing to a player for a specific video. In general, this is the 
							 * information in the src element of an embed tag and should not be the same as 
							 * the content of the loc tag.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - URL
							 */

							if (
								in_array( 'embedUrl', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_embedUrl = $CreativeWork_asset_embedUrl ?? '';

								// Add to item values

									if ( $CreativeWork_embedUrl ) {

										$CreativeWork_item['embedUrl'] = $CreativeWork_embedUrl;

									}

							}

						// encodingFormat

							/*
							 * Media type typically expressed using a MIME format (see IANA site and MDN 
							 * reference) (e.g., application/zip for a SoftwareApplication binary, audio/mpeg 
							 * for .mp3).
							 * 
							 * In cases where a CreativeWork has several media type representations, encoding 
							 * can be used to indicate each MediaObject alongside particular encodingFormat 
							 * information.
							 * 
							 * Unregistered or niche encoding and file formats can be indicated instead via 
							 * the most appropriate URL, e.g. defining Web page or a Wikipedia/Wikidata entry.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 *     - URL
							 */

							if (
								in_array( 'encodingFormat', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_encodingFormat = get_post_mime_type( $CreativeWork_asset_id ) ?: ''; // e.g., 'image/jpeg'

								// Add to item values

									if ( $CreativeWork_encodingFormat ) {

										$CreativeWork_item['encodingFormat'] = $CreativeWork_encodingFormat;

									}

							}

						// hasDigitalDocumentPermission

							/*
							 * A permission related to the access to this document (e.g. permission to read or 
							 * write an electronic document). For a public document, specify a grantee with an 
							 * Audience with audienceType equal to "public".
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - DigitalDocumentPermission
							 */

							if (
								in_array( 'hasDigitalDocumentPermission', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_hasDigitalDocumentPermission = array(
										'@type' => 'DigitalDocumentPermission',
										'permissionType' => 'ReadPermission', // Thing > Intangible > Enumeration > DigitalDocumentPermissionType
										'grantee' => array(
											'@type' => 'Audience',
											'audienceType' => 'public'
										)
									);

								// Add to item values

									if ( $CreativeWork_hasDigitalDocumentPermission ) {

										$CreativeWork_item['hasDigitalDocumentPermission'] = $CreativeWork_hasDigitalDocumentPermission;

									}

							}

						// height

							/*
							 * The height of the item.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Distance
							 *     - QuantitativeValue
							 */

							if (
								in_array( 'height', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_height = ( isset($CreativeWork_asset_height) && !empty($CreativeWork_asset_height) ) ? $CreativeWork_asset_height . ' px' : '';

								// Add to item values

									if ( $CreativeWork_height ) {

										$CreativeWork_item['height'] = $CreativeWork_height;

									}

							}

						// image

							/*
							 * An image of the item. This can be a URL or a fully described ImageObject.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - ImageObject
							 *     - URL
							 */

							if ( in_array( 'image', $CreativeWork_properties ) ) {

								// Get values

									$CreativeWork_image = $CreativeWork_image ?? array();

								// Clean up list array

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($CreativeWork_image);

								// Add to item values

									if ( $CreativeWork_image ) {

										$CreativeWork_item['image'] = $CreativeWork_image;

									}

							}

						// isAccessibleForFree

							/*
							 * A flag to signal that the item, event, or place is accessible for free.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Boolean
							 */

							if ( in_array( 'isAccessibleForFree', $CreativeWork_properties ) ) {

								// Get values

									$CreativeWork_isAccessibleForFree = 'True';

								// Add to item values

									if ( $CreativeWork_isAccessibleForFree ) {

										$CreativeWork_item['isAccessibleForFree'] = $CreativeWork_isAccessibleForFree;

									}

							}

						// isPartOf

							/*
							 * Indicates an item or CreativeWork that this item, or CreativeWork (in some 
							 * sense), is part of.
							 * 
							 * Inverse-property: hasPart
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - CreativeWork
							 *     - URL
							 */

							if (
								in_array( 'isPartOf', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_isPartOf = $schema_clinical_resource_MedicalWebPage_ref ?? '';

									if ( !$CreativeWork_isPartOf ) {

										$CreativeWork_isPartOf = ( isset($CreativeWork_url) && !empty($CreativeWork_url) ) ? $CreativeWork_url . '#' . 'MedicalWebPage' : '';

									}

								// Add to item values

									if ( $CreativeWork_isPartOf ) {

										$CreativeWork_item['isPartOf'] = $CreativeWork_isPartOf;

									}

							}

						// mainEntityOfPage

							/*
							 * Indicates a page (or other CreativeWork) for which this thing is the main 
							 * entity being described. See background notes at 
							 * https://schema.org/docs/datamodel.html#mainEntityBackground for details.
							 * 
							 * Inverse-property: mainEntity
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - CreativeWork
							 *     - URL
							 */

							if (
								in_array( 'mainEntityOfPage', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_mainEntityOfPage = $schema_clinical_resource_MedicalWebPage_ref ?? '';

									if ( !$CreativeWork_mainEntityOfPage ) {

										$CreativeWork_mainEntityOfPage = ( isset($CreativeWork_url) && !empty($CreativeWork_url) ) ? $CreativeWork_url . '#' . 'MedicalWebPage' : '';

									}

								// Add to item values

									if ( $CreativeWork_mainEntityOfPage ) {

										$CreativeWork_item['mainEntityOfPage'] = $CreativeWork_mainEntityOfPage;

									}

							}

						// representativeOfPage

							/*
							 * Indicates whether this image is representative of the content of the page.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Boolean
							 */

							if ( in_array( 'representativeOfPage', $CreativeWork_properties ) ) {

								// Get values

									$CreativeWork_representativeOfPage = $nesting_level == 0 ? 'True' : 'False';

								// Add to item values

									if ( $CreativeWork_representativeOfPage ) {

										$CreativeWork_item['representativeOfPage'] = $CreativeWork_representativeOfPage;

									}

							}

						// sameAs

							/*
							 * URL of a reference Web page that unambiguously indicates the item's identity 
							 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official 
							 * website).
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - URL
							 */

							if ( in_array( 'sameAs', $CreativeWork_properties ) ) {

								// Base array

									$CreativeWork_sameAs = array();

								// Get values

									// Syndication URL

										if (
											isset($CreativeWork_syndication_URL)
											&&
											!empty($CreativeWork_syndication_URL)
										) {

											$CreativeWork_sameAs[] = $CreativeWork_syndication_URL;

										}

									// Video URL

										if (
											$CreativeWork_resource_type == 'video'
											&&
											$CreativeWork_video
										) {

											$CreativeWork_sameAs[] = $CreativeWork_video;
		
										}
		
								// Clean up values array

									$CreativeWork_sameAs = array_unique($CreativeWork_sameAs);
									$CreativeWork_sameAs = array_filter($CreativeWork_sameAs);
									$CreativeWork_sameAs = array_values($CreativeWork_sameAs);

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($CreativeWork_sameAs);

								// Add to item values

									if ( $CreativeWork_sameAs ) {

										$CreativeWork_item['sameAs'] = $CreativeWork_sameAs;

									}

							}

						// sourceOrganization

							/*
							 * The Organization on whose behalf the creator was working.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Organization
							 */

							if (
								in_array( 'sourceOrganization', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									if ( $CreativeWork_syndication_query ) {

										$CreativeWork_sourceOrganization = $CreativeWork_syndication_org ?? '';

									} else {

										$CreativeWork_sourceOrganization = $schema_base_org_uams_health_ref ?? '';

									}

								// Add to item values

									if ( $CreativeWork_sourceOrganization ) {

										$CreativeWork_item['sourceOrganization'] = $CreativeWork_sourceOrganization;

									}

							}

						// speakable

							/*
							 * Indicates sections of a Web page that are particularly 'speakable' in the sense 
							 * of being highlighted as being especially appropriate for text-to-speech 
							 * conversion. Other sections of a page may also be usefully spoken in particular 
							 * circumstances; the 'speakable' property serves to indicate the parts most 
							 * likely to be generally useful for speech.
							 * 
							 * The speakable property can be repeated an arbitrary number of times, with three 
							 * kinds of possible 'content-locator' values:
							 *     
							 *     1.) id-value URL references - uses id-value of an element in the page being 
							 *         annotated. The simplest use of speakable has (potentially relative) URL 
							 *         values, referencing identified sections of the document concerned.
							 *     2.) CSS Selectors - addresses content in the annotated page (e.g., via 
							 *         class attribute. Use the cssSelector property).
							 *     3.) XPaths - addresses content via XPaths (assuming an XML view of the 
							 *         content). Use the xpath property.
							 * 
							 * For more sophisticated markup of speakable sections beyond simple ID 
							 * references, either CSS selectors or XPath expressions to pick out document 
							 * section(s) as speakable. For this we define a supporting type, 
							 * SpeakableSpecification which is defined to be a possible value of the speakable 
							 * property.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - SpeakableSpecification
							 *     - URL
							 */

							if (
								in_array( 'speakable', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Base array

									$CreativeWork_speakable = array();

								// Get values

									// Introduction / Description

										if (
											$CreativeWork_resource_type == 'infographic'
											||
											$CreativeWork_resource_type == 'video'
											||
											$CreativeWork_resource_type == 'doc'
										) {

											$CreativeWork_speakable[] = array(
												'@type' => 'SpeakableSpecification',
												'cssSelector' => '#resource-description-body'
											);

										}

									// Content

										if ( $CreativeWork_resource_type == 'text' ) {

											$CreativeWork_speakable[] = array(
												'@type' => 'SpeakableSpecification',
												'cssSelector' => '#resource-content-body'
											);

										}

									// Transcript

										if (
											$CreativeWork_resource_type == 'infographic'
											||
											$CreativeWork_resource_type == 'video'
										) {

											$CreativeWork_speakable[] = array(
												'@type' => 'SpeakableSpecification',
												'cssSelector' => '#resource-transcript-body'
											);

										}

								// Add to item values

									if ( $CreativeWork_speakable ) {

										$CreativeWork_item['speakable'] = $CreativeWork_speakable;

										// If there is only one item, flatten the multi-dimensional array by one step

											uamswp_fad_flatten_multidimensional_array($CreativeWork_item['speakable']);

									}

							}

						// subjectOf

							/*
							 * A CreativeWork or Event about this Thing.
							 * 
							 * Inverse-property: about
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - CreativeWork
							 *     - Event
							 */

							if ( in_array( 'subjectOf', $CreativeWork_properties ) ) {

								// Get values

									$CreativeWork_subjectOf = $schema_clinical_resource_MedicalWebPage_ref ?? '';

									if ( !$CreativeWork_subjectOf ) {

										$CreativeWork_subjectOf = ( isset($CreativeWork_url) && !empty($CreativeWork_url) ) ? $CreativeWork_url . '#' . 'MedicalWebPage' : '';

									}

								// Add to item values

									if ( $CreativeWork_subjectOf ) {

										$CreativeWork_item['subjectOf'] = $CreativeWork_subjectOf;

									}

							}

						// thumbnail

							/*
							 * Thumbnail image for an image or video.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - ImageObject
							 */

							if ( in_array( 'thumbnail', $CreativeWork_properties ) ) {

								// Get values

									$CreativeWork_thumbnail = $CreativeWork_asset_thumbnail ?: $CreativeWork_thumbnail;

								// Clean up list array

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($CreativeWork_thumbnail);

								// Add to item values

									if ( $CreativeWork_thumbnail ) {

										$CreativeWork_item['thumbnail'] = $CreativeWork_thumbnail;

									}

							}

						// transcript

							/*
							 * If this MediaObject is an AudioObject or VideoObject, the transcript of that 
							 * object.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							if (
								in_array( 'transcript', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_transcript = get_field( 'clinical_resource_video_transcript', $CreativeWork ) ?: '';

								// Clean up values

									if ( $CreativeWork_transcript ) {

										// Strip all tags

											$CreativeWork_transcript = wp_strip_all_tags($CreativeWork_transcript);
											$CreativeWork_transcript = str_replace("\n", ' ', $CreativeWork_transcript); // Strip line breaks

										// Make attribute-friendly

											$CreativeWork_transcript = uamswp_attr_conversion($CreativeWork_transcript);

									}

								// Add to item values

									if ( $CreativeWork_transcript ) {

										$CreativeWork_item['transcript'] = $CreativeWork_transcript;

									}

							}

						// timeRequired

							/*
							 * Approximate or typical time it usually takes to work with or through the 
							 * content of this work for the typical or target audience.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Duration (use ISO 8601 duration format).
							 */

							if (
								in_array( 'timeRequired', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									// Count words

										// Base value

											$CreativeWork_word_count = 0;

										// Introduction / Description

											if ( $CreativeWork_resource_type != 'text' ) {

												$CreativeWork_description_count = str_word_count($CreativeWork_description);
												$CreativeWork_word_count = $CreativeWork_word_count + $CreativeWork_description_count;

											}

										// Article body

											$CreativeWork_articleBody_count = str_word_count($CreativeWork_articleBody);
											$CreativeWork_word_count = $CreativeWork_word_count + $CreativeWork_articleBody_count;

										// Video transcript

											$CreativeWork_transcript_count = str_word_count($CreativeWork_transcript);
											$CreativeWork_word_count = $CreativeWork_word_count + $CreativeWork_transcript_count;

										// Infographic transcript

											$CreativeWork_embeddedTextCaption_count = str_word_count($CreativeWork_embeddedTextCaption);
											$CreativeWork_word_count = $CreativeWork_word_count + $CreativeWork_embeddedTextCaption_count;

									// Calculate time to read all words

										$wpm = 214; // National average for optimal silent reading rate for 9th grade, as words per minute (Hasbrouck & Tindal, 2006)
										$wps = $wps ?? $wpm / 60; // National average for optimal silent reading rate for 9th grade, as words per second (Hasbrouck & Tindal, 2006)

										$CreativeWork_timeRequired_seconds = $CreativeWork_word_count ? ( $CreativeWork_word_count / $wps ) : '';
										$CreativeWork_timeRequired = $CreativeWork_timeRequired_seconds ? uamswp_fad_iso8601_duration($CreativeWork_timeRequired_seconds) : '';

								// Add to item values

									if ( $CreativeWork_timeRequired ) {

										$CreativeWork_item['timeRequired'] = $CreativeWork_timeRequired;

									}

							}

						// videoFrameSize

							/*
							 * The frame size of the video.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							if (
								in_array( 'videoFrameSize', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_videoFrameSize = $CreativeWork_asset_videoFrameSize ?? '';

								// Add to item values

									if ( $CreativeWork_videoFrameSize ) {

										$CreativeWork_item['videoFrameSize'] = $CreativeWork_videoFrameSize;

									}

							}

						// videoQuality

							/*
							 * The quality of the video.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							if (
								in_array( 'videoQuality', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_videoQuality = $CreativeWork_asset_videoQuality ?? '';

								// Add to item values

									if ( $CreativeWork_videoQuality ) {

										$CreativeWork_item['videoQuality'] = $CreativeWork_videoQuality;

									}

							}

						// width

							/*
							 * The width of the item.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Distance
							 *     - QuantitativeValue
							 */

							if (
								in_array( 'width', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_width = ( isset($CreativeWork_asset_width) && !empty($CreativeWork_asset_width) ) ? $CreativeWork_asset_width . ' px' : '';

								// Add to item values

									if ( $CreativeWork_width ) {

										$CreativeWork_item['width'] = $CreativeWork_width;

									}

							}

						// wordCount

							/*
							 * The number of words in the text of the Article.
							 * 
							 * Values expected to be one of these types:
							 *     - Integer
							 */

							if (
								in_array( 'wordCount', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_wordCount = $CreativeWork_articleBody_count ?? '';

									// Fallback value

										if ( !$CreativeWork_wordCount ) {

											$CreativeWork_wordCount = ( isset($CreativeWork_articleBody) && !empty($CreativeWork_articleBody) ) ? str_word_count($CreativeWork_articleBody) : '';

										}

								// Add to item values

									if ( $CreativeWork_wordCount ) {

										$CreativeWork_item['wordCount'] = $CreativeWork_wordCount;

									}

							}

						// Sort array

							ksort($CreativeWork_item);

						// Set/update the value of the item transient

							uamswp_fad_set_transient( 'item_' . $CreativeWork, $CreativeWork_item, __FUNCTION__ );

						// Add to list of conditions

							$CreativeWork_list[] = $CreativeWork_item;

					}

				} // endforeach ( $repeater as $CreativeWork )

				// Clean up list array

					$CreativeWork_list = array_filter($CreativeWork_list);
					$CreativeWork_list = array_values($CreativeWork_list);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($CreativeWork_list);

			} // endif ( !empty($repeater) )

			return $CreativeWork_list;

		}

	// Add data to an array defining schema data for MedicalCondition (a.k.a. related conditions)

		function uamswp_fad_schema_medicalcondition(
			array $repeater, // List of IDs of the MedicalCondition items
			string $page_url, // Page URL
			int $nesting_level = 1, // Nesting level within the main schema
			string $page_fragment = 'MedicalCondition', // Fragment identifier
			int $condition_i = 1 // Iteration counter
		) {
			
			// Base list array

				$condition_list = array();

			if ( !empty($repeater) ) {

				foreach ( $repeater as $condition ) {

					// If post is not published, skip to the next iteration

						if ( get_post_status($condition) != 'publish' ) {

							continue;

						}

					// Base array

						$condition_item = array();

					// @id

						if ( $nesting_level == 1 ) {

							$condition_id = $page_url . '#' . $page_fragment . $condition_i;
							$condition_item['@id'] = $condition_id;
							$condition_i++;

						}

					// @type

						$condition_type = 'MedicalCondition';

						// MedicalCondition Subtype

							$condition_type = get_field( 'schema_medicalcondition_subtype', $condition ) ?: $condition_type;
							$condition_type_parent = $condition_type != 'MedicalCondition' ? array( 'MedicalCondition' ) : array();

						// Add to array

							$condition_item['@type'] = $condition_type;

					// name

						/*
						 * The name of the item.
						 * 
						 * Subproperty of:
						 * 
						 *     - rdfs:label
						 * 
						 * Values expected to be one of these types:
						 * 
						 *     - Text
						 */

						$condition_name = get_the_title($condition); // Expects Text

						// Add to array

							$condition_item['name'] = $condition_name;

					// alternateName

						/*
						 * An alias for the item.
						 * 
						 * Values expected to be one of these types:
						 * 
						 *     - Text
						 */

						// Get repeater field value

							$condition_alternateName_array = get_field( 'condition_alternate', $condition ) ?: array();

						// Get item values

							$condition_alternateName = uamswp_fad_schema_alternatename(
								$condition_alternateName_array, // alternateName repeater field
								'alternate_text' // alternateName item field name
							);

						// Add to schema

							if ( $condition_alternateName ) {

								$condition_item['alternateName'] = $condition_alternateName;

							}

					// code

						/*
						 * A medical code for the entity, taken from a controlled vocabulary or ontology 
						 * such as ICD-9, DiseasesDB, MeSH, SNOMED-CT, RxNorm, etc.
						 * 
						 * Values expected to be one of these types:
						 * 
						 *     - MedicalCode
						 */

						// Get repeater field value

							$condition_code_array = get_field( 'condition_schema_code_schema_medicalcode', $condition ) ?: array();

						// Get item values

							$condition_code = uamswp_fad_schema_code(
								$condition_code_array // code repeater field
							);

						// Add to schema
						
							if ( $condition_code ) {

								$condition_item['code'] = $condition_code;

							}

					// additionalType

						/*
						 * An additional type for the item, typically used for adding more specific types 
						 * from external vocabularies in microdata syntax. This is a relationship between 
						 * something and a class that the thing is in. Typically the value is a 
						 * URI-identified RDF class, and in this case corresponds to the use of rdf:type 
						 * in RDF. Text values can be used sparingly, for cases where useful information 
						 * can be added without their being an appropriate schema to reference. In the 
						 * case of text values, the class label should follow the schema.org style guide.
						 * 
						 * Subproperty of:
						 *     - rdf:type
						 * 
						 * Values expected to be one of these types:
						 * 
						 *     - Text
						 *     - URL
						 */

						$condition_additionalType_array = get_field( 'schema_additionalType', $condition ) ?: '';

						// Base array

							$condition_additionalType = array();

						// Add each row to the array

							if ( $condition_additionalType_array ) {

								foreach ( $condition_additionalType_array as $additionalType ) {

									$condition_additionalType[] = $additionalType['schema_additionalType_uri'];

								}

							}

						// Add to schema

							if ( $condition_additionalType ) {

								$condition_item['additionalType'] = $condition_additionalType;

								// If there is only one item, flatten the multi-dimensional array by one step

									uamswp_fad_flatten_multidimensional_array($condition_item['additionalType']);

							}

					// sameAs

						/*
						 * URL of a reference Web page that unambiguously indicates the item's identity 
						 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official 
						 * website).
						 * 
						 * Values expected to be one of these types:
						 * 
						 *     - URL
						 */

						$condition_sameAs_array = get_field( 'schema_sameas', $condition );

						// Base array

							$condition_sameAs = array();

						// Add each row to the array

							if ( $condition_sameAs_array ) {

								foreach ( $condition_sameAs_array as $sameAs ) {

									$condition_sameAs[] = $sameAs['schema_sameas_url'];

								}

							}

						// Add to schema

							if ( $condition_sameAs ) {

								$condition_item['sameAs'] = $condition_sameAs;

								// If there is only one item, flatten the multi-dimensional array by one step

									uamswp_fad_flatten_multidimensional_array($condition_item['sameAs']);

							}

					// infectiousAgent

						if (
							$condition_type == 'InfectiousDisease'
							||
							in_array( 'InfectiousDisease', $condition_type_parent )
						) {

							$condition_infectiousAgent = get_field( 'schema_infectiousagent', $condition ) ?: '';

							if ( $condition_infectiousAgent ) {

								$condition_item['infectiousAgent'] = $condition_infectiousAgent;

							}

						}

					// infectiousAgentClass
					
						if (
							$condition_type == 'InfectiousDisease'
							||
							in_array( 'InfectiousDisease', $condition_type_parent )
						) {

							$condition_infectiousAgentClass =  get_field( 'condition_schema_infectiousagentclass_schema_infectiousagentclass', $condition ) ?: '';

							if ( $condition_infectiousAgentClass ) {

								$condition_item['infectiousAgentClass'] = $condition_infectiousAgentClass;

							}

						}

					// possibleTreatment

						if ( $nesting_level == 1 ) {

							// Get relationship field value

								$condition_possibleTreatment_array = get_field( 'condition_schema_possibletreatment', $condition ) ?: array();

							// Get item values

								$condition_possibleTreatment = uamswp_fad_schema_service(
									$condition_possibleTreatment_array,
									$page_url,
									( $nesting_level + 1 ),
									'possibleTreatment'
								);

							// Add to schema

								if ( $condition_possibleTreatment ) {

									$condition_item['possibleTreatment'] = $condition_possibleTreatment;

								}

						}

					// primaryPrevention

						if ( $nesting_level == 1 ) {

							// Get relationship field value

								$condition_primaryPrevention_array = get_field( 'condition_schema_primaryprevention', $condition ) ?: array();

							// Get item values

								$condition_primaryPrevention = uamswp_fad_schema_service(
									$condition_primaryPrevention_array,
									$page_url,
									( $nesting_level + 1 ),
									'primaryPrevention'
								);

							// Add to schema

								if ( $condition_primaryPrevention ) {

									$condition_item['primaryPrevention'] = $condition_primaryPrevention;

								}

						}

					// secondaryPrevention

						if ( $nesting_level == 1 ) {

							// Get relationship field value

								$condition_secondaryPrevention_array = get_field( 'condition_schema_secondaryprevention', $condition ) ?: array();

							// Get item values

								$condition_secondaryPrevention = uamswp_fad_schema_service(
									$condition_secondaryPrevention_array,
									$page_url,
									( $nesting_level + 1 ),
									'secondaryPrevention'
								);

							// Add to schema

								if ( $condition_secondaryPrevention ) {

									$condition_item['secondaryPrevention'] = $condition_secondaryPrevention;

								}

						}

					// typicalTest

						if ( $nesting_level == 1 ) {

							// Get relationship field value

								$condition_typicalTest_array = get_field( 'condition_schema_typicaltest', $condition ) ?: array();

							// Get item values

								$condition_typicalTest = uamswp_fad_schema_service(
									$condition_typicalTest_array,
									$page_url,
									( $nesting_level + 1 ),
									'typicalTest'
								);

							// Add to schema

								if ( $condition_typicalTest ) {

									$condition_item['typicalTest'] = $condition_typicalTest;

								}

						}

					// Sort array

						ksort($condition_item);

					// Add to list of conditions

						$condition_list[] = $condition_item;

				} // endforeach ( $repeater as $condition )

				// Clean up list array

					$condition_list = array_filter($condition_list);
					$condition_list = array_values($condition_list);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($condition_list);

			} // endif ( !empty($repeater) )

			return $condition_list;

		}

	// Add data to an array defining schema data for a service (a.k.a. treatments and procedures)

		function uamswp_fad_schema_service(
			array $repeater, // List of IDs of the service items
			string $page_url, // Page URL
			int $nesting_level = 1, // Nesting level within the main schema
			string $page_fragment = 'Service', // Fragment identifier
			int $service_i = 1 // Iteration counter
		) {
			
			// Base list array

				$service_list = array();

			if ( !empty($repeater) ) {

				foreach ( $repeater as $service ) {

					// If post is not published, skip to the next iteration

						if ( get_post_status($service) != 'publish' ) {

							continue;

						}

					// Eliminate PHP errors and reset variables

						$additionalType = '';
						$drug = '';
						$duplicateTherapy = '';
						$nonProprietaryName = '';
						$proprietaryName = '';
						$relevantSpecialty = '';
						$sameAs = '';
						$service_additionalType_array = array();
						$service_additionalType_list = array();
						$service_alternateName = '';
						$service_alternateName_array = array();
						$service_item = array();
						$service_code = '';
						$service_code_array = array();
						$service_drug_array = array();
						$service_drug_item = array();
						$service_drug_item_nonProprietaryName_list = array();
						$service_drug_item_proprietaryName_list = array();
						$service_drug_list = array();
						$service_duplicateTherapy = '';
						$service_duplicateTherapy_array = array();
						$service_MedicalImagingTechnique = '';
						$service_name = '';
						$service_procedureType = '';
						$service_relevantSpecialty_array = array();
						$service_relevantSpecialty_list = array();
						$service_sameAs_array = array();
						$service_sameAs_list = array();
						$service_subTest_array = array();
						$service_subTest_item = array();
						$service_subTest_list = array();
						$service_tissueSample_array = array();
						$service_tissueSample_list = array();
						$service_type = '';
						$service_type_parent = array();
						$service_usedToDiagnose_array = array();
						$service_usedToDiagnose_item = array();
						$service_usedToDiagnose_list = array();
						$service_usesDevice_array = array();
						$service_usesDevice_item = array();
						$service_usesDevice_item_alternateName = '';
						$service_usesDevice_item_alternateName_array = array();
						$service_usesDevice_item_code = '';
						$service_usesDevice_item_code_array = array();
						$service_usesDevice_list = array();
						$subTest = '';
						$tissueSample = '';
						$usedToDiagnose = '';
						$usesDevice = '';

					// Base array

						$service_item = array();

					// @id

						if ( $nesting_level == 1 ) {

							$service_id = $page_url . '#' . $page_fragment . $service_i;
							$service_item['@id'] = $service_id;
							$service_i++;

						}

					// @type
					
						// Base value

							$service_type = 'MedicalEntity';
							$service_type_parent = array();

						// MedicalEntity Subtype

							$service_type = get_field( 'schema_medicalentity_subtype_availableservice', $service ) ?: $service_type;

							if ( $service_type == 'MedicalTest' ) {

								$service_type_parent[] = 'MedicalEntity';

								// MedicalTest Subtype

									$service_type = get_field( 'schema_medicaltest_subtype', $service ) ?: $service_type;
									$service_type_parent[] = 'MedicalTest';

							} elseif ( $service_type == 'MedicalProcedure' ) {

								$service_type_parent[] = 'MedicalEntity';

								// MedicalProcedure Subtype

									$service_type = get_field( 'schema_medicalprocedure_subtype', $service ) ?: $service_type;
									$service_type_parent[] = 'MedicalProcedure';

									if ( $service_type == 'TherapeuticProcedure' ) {
										
										// TherapeuticProcedure Subtype

											$service_type = get_field( 'schema_therapeuticprocedure_subtype', $service ) ?: $service_type;
											$service_type_parent[] = 'TherapeuticProcedure';

											if ( $service_type == 'MedicalTherapy' ) {
										
												// MedicalTherapy Subtype
		
													$service_type = get_field( 'schema_medicaltherapy_subtype', $service ) ?: $service_type;
													$service_type_parent[] = 'MedicalTherapy';
		
											}
		
									}

							}

						// Add to schema
						
							$service_item['@type'] = $service_type;

					// Name

						/*
						 * The name of the item.
						 * 
						 * Subproperty of:
						 * 
						 *     - rdfs:label
						 * 
						 * Values expected to be one of these types:
						 * 
						 *     - Text
						 */

						$service_name = get_the_title($service); // Expects Text
						$service_item['name'] = $service_name;

					// alternateName

						/*
						 * An alias for the item.
						 * 
						 * Values expected to be one of these types:
						 * 
						 *     - Text
						 */

						// Get repeater field value

							$service_alternateName_array = get_field( 'treatment_procedure_alternate', $service ) ?: array();

						// Get item values

							$service_alternateName = uamswp_fad_schema_alternatename(
								$service_alternateName_array, // alternateName repeater field
								'alternate_text' // alternateName item field name
							);

						// Add to schema

							if ( $service_alternateName ) {

								$service_item['alternateName'] = $service_alternateName;

							}

					// code

						/*
						 * A medical code for the entity, taken from a controlled vocabulary or ontology 
						 * such as ICD-9, DiseasesDB, MeSH, SNOMED-CT, RxNorm, etc.
						 * 
						 * Values expected to be one of these types:
						 * 
						 *     - MedicalCode
						 */

						// Get repeater field value

							$service_code_array = get_field( 'treatment_procedure_schema_code_schema_medicalcode', $service ) ?: array();

						// Get item values

							$service_code = uamswp_fad_schema_code(
								$service_code_array // code repeater field
							);

						// Add to schema
						
							if ( $service_code ) {

								$service_item['code'] = $service_code;

							}

					// additionalType

						/*
						 * An additional type for the item, typically used for adding more specific types 
						 * from external vocabularies in microdata syntax. This is a relationship between 
						 * something and a class that the thing is in. Typically the value is a 
						 * URI-identified RDF class, and in this case corresponds to the use of rdf:type 
						 * in RDF. Text values can be used sparingly, for cases where useful information 
						 * can be added without their being an appropriate schema to reference. In the 
						 * case of text values, the class label should follow the schema.org style guide.
						 * 
						 * Subproperty of:
						 *     - rdf:type
						 * 
						 * Values expected to be one of these types:
						 * 
						 *     - Text
						 *     - URL
						 */

						// Get repeater field value

							$service_additionalType_array = get_field( 'schema_additionalType', $service ) ?: array();

						// Base list array

							$service_additionalType_list = array();

						// Add each row to the array

							if ( $service_additionalType_array ) {

								foreach ( $service_additionalType_array as $additionalType ) {

									$service_additionalType_list[] = $additionalType['schema_additionalType_uri'];

								}

								// Clean up list array

									$service_additionalType_list = array_filter($service_additionalType_list);
									$service_additionalType_list = array_values($service_additionalType_list);
									sort($service_additionalType_list);

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($service_additionalType_list);

								// Add to schema

									if ( $service_additionalType_list ) {

										$service_item['additionalType'] = $service_additionalType_list;

									}

							}

					// sameAs

						/*
						 * URL of a reference Web page that unambiguously indicates the item's identity 
						 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official 
						 * website).
						 * 
						 * Values expected to be one of these types:
						 * 
						 *     - URL
						 */


						// Get repeater field value

							$service_sameAs_array = get_field( 'schema_sameas', $service );

						// Base list array

							$service_sameAs_list = array();

						if ( $service_sameAs_array ) {

							foreach ( $service_sameAs_array as $sameAs ) {

								$service_sameAs_list[] = $sameAs['schema_sameas_url'];

							}

							// Clean up list array

								$service_sameAs_list = array_filter($service_sameAs_list);
								$service_sameAs_list = array_values($service_sameAs_list);
								sort($service_sameAs_list);

								// If there is only one item, flatten the multi-dimensional array by one step

									uamswp_fad_flatten_multidimensional_array($service_sameAs_list);

							// Add to schema

								if ( $service_sameAs_list ) {

									$service_item['sameAs'] = $service_sameAs_list;

								}

						}

					// drug

						if (
							$service_type == 'TherapeuticProcedure'
							||
							in_array( 'TherapeuticProcedure', $service_type_parent )
						) {
					
							// Get repeater field value

								$service_drug_array = get_field( 'treatment_procedure_schema_drug_schema_drug', $service ) ?: array();

							// Base list array

								$service_drug_list = array();

							if ( $service_drug_array ) {

								foreach ( $service_drug_array as $drug ) {

									// Base item array

										$service_drug_item = array();

									// proprietaryName

										// Get repeater field value

											$service_drug_item['proprietaryName'] = $drug['schema_drug_proprietaryname'] ?: array();

										// Base list array

											$service_drug_item_proprietaryName_list = array();

										if ( $service_drug_item['proprietaryName'] ) {

											foreach ( $service_drug_item['proprietaryName'] as $proprietaryName ) {

												$service_drug_item_proprietaryName_list[] = $proprietaryName['schema_drug_proprietaryname_text'];

											}

											// Add to schema

												if ( $service_drug_item_proprietaryName_list ) {

													$service_drug_item['proprietaryName'] = $service_drug_item_proprietaryName_list;

													// If there is only one item, flatten the multi-dimensional array by one step

														uamswp_fad_flatten_multidimensional_array($service_drug_item['proprietaryName']);

												}

										}

									// nonProprietaryName

										// Get repeater field value

											$service_drug_item['nonProprietaryName'] = $drug['schema_drug_nonproprietaryname'] ?: array();

										// Base list array

											$service_drug_item_nonProprietaryName_list = array();

										if ( $service_drug_item['nonProprietaryName'] ) {

											foreach ( $service_drug_item['nonProprietaryName'] as $nonProprietaryName ) {

												$service_drug_item_nonProprietaryName_list[] = $nonProprietaryName['schema_drug_nonproprietaryname_text'];

											}

											// Add to schema

												if ( $service_drug_item_nonProprietaryName_list ) {

													$service_drug_item['nonProprietaryName'] = $service_drug_item_nonProprietaryName_list;

													// If there is only one item, flatten the multi-dimensional array by one step

														uamswp_fad_flatten_multidimensional_array($service_drug_item['nonProprietaryName']);

												}

										}

									// prescriptionStatus

										$service_drug_item['prescriptionStatus'] = $drug['schema_drug_prescriptionstatus'] ?: '';

									// rxcui

										$service_drug_item['rxcui'] = $drug['schema_drug_rxcui'] ?: '';

									// Add item to the list array

										$service_drug_list[] = $service_drug_item;

								}

								// Clean up list array

									$service_drug_list = array_filter($service_drug_list);
									$service_drug_list = array_values($service_drug_list);

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($service_drug_list);

								// Add to schema

									if ( $service_drug_list ) {

										$service_item['drug'] = $service_drug_list;

									}

							}

						}

					// duplicateTherapy

						if (
							(
								$service_type == 'TherapeuticProcedure'
								||
								in_array( 'TherapeuticProcedure', $service_type_parent )
							)
							&&
							$nesting_level == 1
						) {
						
							// Get relationship field value

								$service_duplicateTherapy_array = get_field( 'treatment_procedure_schema_duplicatetherapy_schema_medicaltherapy', $service ) ?: array();

							// Get item values

								$service_duplicateTherapy = uamswp_fad_schema_service(
									$service_duplicateTherapy_array,
									$page_url,
									( $nesting_level + 1 ),
									'duplicateTherapy'
								);

							// Add to schema

								if ( $service_duplicateTherapy ) {

									$service_item['duplicateTherapy'] = $service_duplicateTherapy;

								}

						}

					// MedicalImagingTechnique

						if (
							$service_type == 'ImagingTest'
							||
							in_array( 'ImagingTest', $service_type_parent )
						) {

							$service_MedicalImagingTechnique = get_field( 'schema_medicalimagingtechnique', $service ) ?: '';

							if ( $service_MedicalImagingTechnique ) {

								$service_item['imagingTechnique'] = $service_MedicalImagingTechnique;

							}

						}

					// procedureType

						if (
							$service_type == 'MedicalProcedure'
							||
							(
								in_array( 'MedicalProcedure', $service_type_parent )
								&&
								$service_type != 'SurgicalProcedure'
								&&
								!in_array( 'SurgicalProcedure', $service_type_parent )
							)
						) {

							$service_procedureType = get_field( 'schema_medicalproceduretype', $service ) ?: '';

							if ( $service_procedureType ) {

								$service_item['procedureType'] = $service_procedureType;

							}

						}

					// subTest

						if (
							(
								$service_type == 'MedicalTestPanel'
								||
								in_array( 'MedicalTestPanel', $service_type_parent )
							)
							&&
							$nesting_level == 1
						) {

							// Get relationship field array

								$service_subTest_array = get_field( 'treatment_procedure_schema_subtest_schema_medicaltest', $service ) ?: array();

							// Get item values

								$service_subTest = uamswp_fad_schema_service(
									$service_subTest_array,
									$page_url,
									( $nesting_level + 1 ),
									'subTest'
								);

							// Add to schema

								if ( $service_subTest ) {

									$service_item['subTest'] = $service_subTest;

								}

						}

					// relevantSpecialty

						/*
						 * If applicable, a medical specialty in which this entity is relevant.
						 * 
						 * Values expected to be one of these types:
						 * 
						 *     - MedicalSpecialty
						 */

						// Get relationship field value

							$service_relevantSpecialty_array = get_field( 'treatment_procedure_schema_relevantspecialty_schema_medicalspecialty_multiple', $service ) ?: array();

						// Base list array

							$service_relevantSpecialty_list = array();

						if ( $service_relevantSpecialty_array ) {

							foreach ( $service_relevantSpecialty_array as $relevantSpecialty ) {

								// Add item to the list array

									$service_relevantSpecialty_list[] = ( isset($relevantSpecialty) && !empty($relevantSpecialty) ) ? $relevantSpecialty : '';

							}

							// Clean up list array

								$service_relevantSpecialty_list = array_filter($service_relevantSpecialty_list);
								$service_relevantSpecialty_list = array_values($service_relevantSpecialty_list);
								sort($service_relevantSpecialty_list);

								// If there is only one item, flatten the multi-dimensional array by one step

									uamswp_fad_flatten_multidimensional_array($service_relevantSpecialty_list);

							// Add to schema

								if ( !empty($service_relevantSpecialty_list) ) {

									$service_item['relevantspecialty'] = $service_relevantSpecialty_list;

								}

						}

					// tissueSample

						if (
							$service_type == 'PathologyTest'
							||
							in_array( 'PathologyTest', $service_type_parent )
						) {

							// Get repeater field value

								$service_tissueSample_array = get_field( 'schema_tissuesample', $service ) ?: array();

							// Base list array

								$service_tissueSample_list = array();

							if ( $service_tissueSample_array ) {

								foreach ( $service_tissueSample_array as $tissueSample ) {

									$service_tissueSample_list[] = $tissueSample['schema_tissuesample_text'];

								}

								// Clean up list array

									$service_tissueSample_list = array_filter($service_tissueSample_list);
									$service_tissueSample_list = array_values($service_tissueSample_list);
									sort($service_tissueSample_list);

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($service_tissueSample_list);

								// Add to schema

									if ( $service_tissueSample_list ) {

										$service_item['tissueSample'] = $service_tissueSample_list;

									}

							}

						}

					// usedToDiagnose

						if (
							$service_type == 'MedicalTest'
							||
							in_array( 'MedicalTest', $service_type_parent )
						) {

							// Get relationship field value

								$service_usedToDiagnose_array = get_field( 'treatment_procedure_schema_usedtodiagnose_schema_medicalcondition', $service ) ?: array();

							// Get item values

								$service_usedToDiagnose = uamswp_fad_schema_medicalcondition(
									$service_usedToDiagnose_array, // List of IDs of the MedicalCondition items
									$page_url, // Page URL
									( $nesting_level + 1 ), // Nesting level within the main schema
									'usedToDiagnose' // Fragment identifier
								);

							// Add to schema

								if ( $service_usedToDiagnose ) {

									$service_item['usedToDiagnose'] = $service_usedToDiagnose;

								}

						}

					// usesDevice

						if (
							$service_type == 'MedicalTest'
							||
							in_array( 'MedicalTest', $service_type_parent )
						) {

							// Get relationship field value

								$service_usesDevice_array = get_field( 'treatment_procedure_schema_usesdevice_schema_medicaldevice', $service ) ?: array();

							// Base list array

								$service_usesDevice_list = array();

							if ( $service_usesDevice_array ) {

								foreach ( $service_usesDevice_array as $usesDevice ) {

									// Base item array

										$service_usesDevice_item = array();

									// @type

										$service_usesDevice_item['@type'] = 'MedicalDevice'; // Replace 'MedicalDevice' with subtype, if relevant

									// name

										/*
										 * The name of the item.
										 * 
										 * Subproperty of:
										 * 
										 *     - rdfs:label
										 * 
										 * Values expected to be one of these types:
										 * 
										 *     - Text
										 */

										$service_usesDevice_item['name'] = $usesDevice['schema_medicaldevice_name'];

									// alternateName

										/*
										 * An alias for the item.
										 * 
										 * Values expected to be one of these types:
										 * 
										 *     - Text
										 */

										// Get repeater field value

											$service_usesDevice_item_alternateName_array = $usesDevice['schema_medicaldevice_alternatename']['schema_alternatename'] ?: array();

										// Get item values

											$service_usesDevice_item_alternateName = uamswp_fad_schema_alternatename(
												$service_alternateName_array, // alternateName repeater field
												'schema_alternatename_text' // alternateName item field name
											);

										// Add to schema

											if ( $service_usesDevice_item_alternateName ) {

												$service_usesDevice_item['alternateName'] = $service_usesDevice_item_alternateName;

											}

									// code

										/*
										 * A medical code for the entity, taken from a controlled vocabulary or ontology 
										 * such as ICD-9, DiseasesDB, MeSH, SNOMED-CT, RxNorm, etc.
										 * 
										 * Values expected to be one of these types:
										 * 
										 *     - MedicalCode
										 */

										// Get repeater field value

											$service_usesDevice_item_code_array = $usesDevice['schema_medicaldevice_code']['schema_medicalcode'] ?: array();

										// Get item values

											$service_usesDevice_item_code = uamswp_fad_schema_code(
												$service_usesDevice_item_code_array // code repeater field
											);

										// Add to schema
										
											if ( $service_usesDevice_item_code ) {

												$service_usesDevice_item['code'] = $service_usesDevice_item_code;

											}

									// Add item to the list array

										$service_usesDevice_list[] = $service_usesDevice_item;

								}

								// Clean up list array

									$service_usesDevice_list = array_filter($service_usesDevice_list);
									$service_usesDevice_list = array_values($service_usesDevice_list);
									sort($service_usesDevice_list);

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($service_usesDevice_list);

								// Add to schema

									if ( $service_usesDevice_list ) {

										$service_item['usesDevice'] = $service_usesDevice_list;

									}

							}

						}

					// Sort array

						ksort($service_item);

					// Add to list of treatments

						$service_list[] = $service_item;

				} // endforeach ( $repeater as $service )

				// Clean up list array

					$service_list = array_filter($service_list);
					$service_list = array_values($service_list);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($service_list);

			} // endif ( !empty($repeater) )

			return $service_list;

		}

	// Define @id references to each top-level node in an array

		function uamswp_fad_schema_node_references(
			array $input
		) {

			$output = array();

			if ( $input ) {

				foreach ( $input as $item ) {

					// Define reference to each value/row in this property

						if (
							isset($item['@id'])
							&&
							!empty($item['@id'])
						) {

							$output[]['@id'] = $item['@id'];

						}

				}

			}

			return $output;

		}

	// Create list of URLs from property value items

		function uamswp_fad_schema_property_urls(
			array $input, // Property values from which to extract URLs
			array &$output = array() // Existing list of URLs
		) {

			if ( $input ) {

				foreach ( $input as $item ) {

					// Get URLs for significantLink property

						if (
							isset($item['url'])
							&&
							!empty($item['url'])
						) {

							$output[] = $item['url'];

						}

				}

			}

			return $output;

		}

// Construct the schema script tag

	function uamswp_fad_schema_construct($input) {

		$schema_line_break = "\n"; // the double quotes are important

		// Construct schema JSON
		// $schema_block = uamswp_fad_schema_type_selector($input);

		// Open script tag
			echo '<script type="application/ld+json">';
			echo $schema_line_break;

		// Encode JSON

			echo json_encode($input, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

		// Close script tag

			echo $schema_line_break;
			echo '</script>';
			echo $schema_line_break;

	}