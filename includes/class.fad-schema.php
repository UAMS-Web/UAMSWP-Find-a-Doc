<?php

// Format values for common schema data properties and types

	// Add data to an array defining schema data for PostalAddress

		function uamswp_fad_schema_postaladdress(
			string $address, // string // Required // The street address or the post office box number for PO box addresses.
			bool $address_query, // bool // Required // Query for whether the address is a street address (as opposed to a post office box number)
			string $addressLocality, // string // Required // The locality in which the street address is, and which is in the region. For example, Mountain View.
			string $addressRegion, // string // Required // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
			string $postalCode, // string // Required // The postal code (e.g., 94043).
			string $addressCountry = 'US', // string // Optional // The country's ISO 3166-1 alpha-2 country code. // Default: 'US'
			string $name = '', // string // Optional // The name of the item.
			string $telephone = '', // string // Optional // The telephone number.
			string $faxNumber = '', // string // Optional // The fax number.
			array $schema_PostalAddress = array() // array // Optional // Main address or location schema array
		) {

			/* 
			 * Example use:
			 * 
			 * 	// address Schema Data
			 * 
			 * 		// Check/define the main PostalAddress schema array
			 * 
			 * 			$schema_PostalAddress = $schema_PostalAddress ?? array();
			 * 
			 * 		// Add this location's details to the main address or location schema array
			 * 
			 * 			$schema_address = uamswp_fad_schema_postaladdress(
			 * 				$location_postOfficeBoxNumber, // string // Required // The street address or the post office box number for PO box addresses.
			 * 				false, // bool // Required // Query for whether the address is a street address (as opposed to a post office box number)
			 * 				$location_addressLocality, // string // Required // The locality in which the street address is, and which is in the region. For example, Mountain View.
			 * 				$location_addressRegion, // string // Required // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
			 * 				$location_postalCode, // string // Required // The postal code (e.g., 94043).
			 * 				'', // string // Optional // The country's ISO 3166-1 alpha-2 country code. // Default: 'US'
			 * 				$location_title = '', // string // Optional // The name of the item.
			 * 				$location_phone_format_dash = '', // string // Optional // The telephone number.
			 * 				$location_fax_format_dash = '', // string // Optional // The fax number.
			 * 				$schema_PostalAddress = array() // array // Optional // Main address or location schema array
			 * 			);
			 */

			// Check/define variables

				$addressCountry = $addressCountry ?: 'US';

				// Check streetAddress vs. postOfficeBoxNumber

					$streetAddress = '';
					$postOfficeBoxNumber = '';

					if ( $address_query ) {

						$streetAddress = $address;

					} else {

						$postOfficeBoxNumber = $address;

					}

				// If the existing array is flat, nest it in an additional layer

					if ( $schema_PostalAddress ) {

						if ( array_key_exists( '@type', $schema_PostalAddress ) ) {

							$schema_PostalAddress = array($schema_PostalAddress);

						}

					}

			// If the required fields are empty, end now

				if (
					!$address
					||
					!$addressLocality
					||
					!$addressRegion
					||
					!$postalCode
				) {

					return $schema_PostalAddress;

				}

			// Add values to the array

				$schema = array(
					'addressCountry' => $addressCountry,
					'addressLocality' => $addressLocality,
					'addressRegion' => $addressRegion,
					'faxNumber' => $faxNumber,
					'name' => $name,
					'postOfficeBoxNumber' => $postOfficeBoxNumber,
					'postalCode' => $postalCode,
					'streetAddress' => $streetAddress,
					'telephone' => $telephone
				);

			// Clean up the array

				$schema = array_filter($schema);

			// Add @type

				if ( !empty($schema) ) {

					$schema = array( '@type' => 'PostalAddress' ) + $schema;

				}

			// Add this item's array to the main PostalAddress schema array

				if ( !empty($schema) ) {

					$schema_PostalAddress[] = $schema;

				}

			// Clean up the array

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_PostalAddress);

			// Return the main address or location schema array

				return $schema_PostalAddress;

		}

	// Add data to an array defining schema data for medicalSpecialty

		// Base function

			function uamswp_fad_schema_medicalSpecialty(
				$input, // mixed // Required // MedicalSpecialty value(s)
				array $schema_medicalSpecialty = array() // Optional // Pre-existing list array to which to add additional items
			) {

				// Base arrays

					$output = array();

				// Check variables

					// If input is empty, end here

						if ( empty($input) ) {

							return $output;

						}

					// If input is not an array, then add it to an array

						if ( !is_array($input) ) {

							$input = array($input);

						}

					// If pre-existing list array is not an indexed array, then add it to one

						if ( !array_is_list($schema_medicalSpecialty) ) {

							$schema_medicalSpecialty = array($schema_medicalSpecialty);

						}

					// Populate base output array with pre-existing list array

						$output = $schema_medicalSpecialty;

				// Construct output

					foreach ( $input as $item ) {

						if (
							!empty($item)
							&&
							!is_array($item)
						) {

							$output[] = array(
								'@id' => $item,
								'@type' => 'MedicalSpecialty'
							);

						}

					}

				// Clean up output array

					if ( $output ) {

						// If there is only one item, flatten the multi-dimensional array by one step

							uamswp_fad_flatten_multidimensional_array($output);

					}

				return $output;

			}

		// Add data from associated clinical specialization

			function uamswp_fad_schema_medicalSpecialty_specialization(
				$clinical_specialization, // mixed // Required // Clinical Specialization value(s)
				array &$medicalSpecialty_list = array(), // Optional // Array to populate with the list of MedicalSpecialty values
				array $schema_medicalSpecialty = array() // Optional // Pre-existing list array to which to add additional items
			) {

				// Base arrays

					$output = array();
					$values = array();

				// Check variables

					// If input is empty, end here

						if ( empty($clinical_specialization) ) {

							return $output;

						}

					// If input is not an array, then add it to an array

						if ( !is_array($clinical_specialization) ) {

							$clinical_specialization = array($clinical_specialization);

						}

				// Get MedicalSpecialty value from Clinical Specialization terms

					foreach ( $clinical_specialization as $item ) {

						if ( $item ) {

							// Get Clinical Specialization term

								$item_term = get_term( $item, 'clinical_title' ) ?? '';

							// Get MedicalSpecialty field value

								$item_MedicalSpecialty = '';

								if ( is_object($item_term) ) {

									$item_MedicalSpecialty = get_field( 'schema_medicalspecialty_single', $item_term ) ?? '';

								}

							// Add MedicalSpecialty field value to values list

								if ( $item_MedicalSpecialty ) {

									$values[] = 'https://schema.org/' . $item_MedicalSpecialty . '/';

								}

						}

					}

				// Construct simple list of MedicalSpecialty values

					if ( $values ) {

						$medicalSpecialty_list = $medicalSpecialty_list + $values;
						sort($medicalSpecialty_list);

					}

				// Construct output for base MedicalSpecialty schema function

					if ( $values ) {

						$output = uamswp_fad_schema_medicalSpecialty(
							$values, // mixed // Required // MedicalSpecialty value(s)
							$schema_medicalSpecialty // Optional // Pre-existing list array to which to add additional items
						);

					}

				return $output;

			}

		// Add data from select or multi-select field

			function uamswp_fad_schema_medicalSpecialty_select(
				$medicalSpecialty_select, // mixed // Required // MedicalSpecialty select or multi-select field value
				array &$medicalSpecialty_list = array(), // Optional // Array to populate with the list of MedicalSpecialty values
				array $schema_medicalSpecialty = array() // Optional // Pre-existing list array to which to add additional items
			) {

				// Base arrays

					$output = array();
					$values = array();

				// Check variables

					// If input is empty, end here

						if ( empty($medicalSpecialty_select) ) {

							return $output;

						}

					// If input is not an array, then add it to an array

						if ( !is_array($medicalSpecialty_select) ) {

							$medicalSpecialty_select = array($medicalSpecialty_select);

						}
 
				// Format value

					foreach ( $medicalSpecialty_select as $item ) {

						if (
							!empty($item)
							&&
							!is_array($item)
						) {

							$values[] = 'https://schema.org/' . $item . '/';

						}

					}

				// Construct simple list of MedicalSpecialty values

					if ( $values ) {

						$medicalSpecialty_list = $medicalSpecialty_list + $values;
						sort($medicalSpecialty_list);

					}

				// Construct output for base MedicalSpecialty schema function

					if ( $values ) {

						$output = uamswp_fad_schema_medicalSpecialty(
							$values, // mixed // Required // MedicalSpecialty value(s)
							$schema_medicalSpecialty // Optional // Pre-existing list array to which to add additional items
						);

					}

				return $output;

			}

		// Add data from associated conditions and treatments

			function uamswp_fad_schema_medicalSpecialty_old(
				$schema_medical_specialty = array(), // array (optional) // Main medicalSpecialty schema array
				$name = '', // string (optional) // The name of the item.
				$url = '', // string (optional) // URL of the item.
				$alternate_name = '' // string (optional) // An alias for the item.
			) {

				/* 
				 * Example use:
				 * 
				 * 	// MedicalSpecialty Schema Data
				 * 
				 * 		// Check/define the main medicalSpecialty schema array
				 * 		$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();
				 * 
				 * 		// Add this location's details to the main medicalSpecialty schema array
				 * 		$schema_medical_specialty = uamswp_fad_schema_medicalSpecialty_old(
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

	// Add data to an array defining schema data for ContactPoint

		function uamswp_fad_schema_contactpoint(
			$input
		) {

			/*
				Expected structure:

				$input = array(
					array(
						'availableLanguage' => array(
							array(
								'name' => 'English',
								'alternateName' => 'en'
							)
						),
						'contactOption' => array(
							'HearingImpairedSupported',
							'TollFree'
						),
						'contactType' => 'appointment scheduing for new and existing patients',
						'email' => '',
						'faxNumber' => '',
						'telephone' => '+1 8008675309',
						'url' => ''
					),
					array(
						'availableLanguage' => array(
							array(
								'name' => 'English',
								'alternateName' => 'en'
							)
						),
						'contactOption' => '',
						'contactType' => 'appointment scheduing for new and existing patients',
						'email' => '',
						'faxNumber' => '',
						'telephone' => '+1 5015551111',
						'url' => ''
					),
					array(
						'availableLanguage' => array(
							array(
								'name' => 'Spanish',
								'alternateName' => 'es'
							)
						),
						'contactOption' => '',
						'contactType' => 'appointment scheduing for new and existing patients',
						'email' => '',
						'faxNumber' => '',
						'telephone' => '+1 5015552222',
						'url' => ''
					),
					array(
						'availableLanguage' => array(
							array(
								'name' => 'English',
								'alternateName' => 'en'
							)
						),
						'contactOption' => '',
						'contactType' => 'patient referral',
						'email' => '',
						'faxNumber' => '+1 5015553333',
						'telephone' => '+1 5015554444',
						'url' => 'https://test.com/'
					),
					array(
						'availableLanguage' => array(
							array(
								'name' => 'English',
								'alternateName' => 'en'
							)
						),
						'contactOption' => array(
							'HearingImpairedSupported',
							'TollFree'
						),
						'contactType' => 'patient referral',
						'email' => '',
						'faxNumber' => '',
						'telephone' => '+1 8005556666',
						'url' => ''
					)
				);
			*/

			// Base arrays

				// contactPoint property value array

					$schema_ContactPoint_list = array();

				// ContactPoint item value array

					$schema_ContactPoint_base = array(
						'availableLanguage' => '',
						'contactOption' => '',
						'contactType' => '',
						'email' => '',
						'faxNumber' => '',
						'hoursAvailable' => '',
						'telephone' => '',
						'url' => ''
					);

			// Add values to the property value array

				if ( $input ) {

					foreach ( $input as $item ) {

						// Intersect input with base ContactPoint item value array

							$input = array_filter(
								array_intersect(
									$schema_ContactPoint_base,
									$input
								)
							);

						// If array is empty, skip this iteration

							if ( !$input ) {

								continue;

							}

						// Base item array

							$schema_ContactPoint = $schema_ContactPoint_base;

						// Add property values

							// availableLanguage

								/*

									A language someone may use with or at the item, service or place.

									Please use one of the language codes from the IETF BCP 47 standard.

									See also inLanguage.

									Expected Type:

										- Language
										- Text

								*/

								if ( $item['availableLanguage'] ) {

									if ( is_array($item['availableLanguage']) ) {

										foreach ( $item['availableLanguage'] as $item) {

											$schema_ContactPoint['availableLanguage'][] = array(
												'@type' => 'Language',
												'name' => $item['name'],
												'alternateName' => $item['alternateName']
											);

										}

									} else {

										$schema_ContactPoint['availableLanguage'][] = array(
											'@type' => 'Language',
											'name' => $item['name'],
											'alternateName' => $item['alternateName']
										);

									}

								}

								$schema_ContactPoint['availableLanguage'] = $item['availableLanguage'] ?? '';

							// contactOption

								/*

									An option available on this contact point (e.g., a toll-free number or support for hearing-impaired callers).

									Expected Type:

										- ContactPointOption (enumeration type)
										      - HearingImpairedSupported
										      - TollFree

								*/

								// Define valid values

									$schema_ContactPoint_contactOption_valid = array(
										'HearingImpairedSupported',
										'TollFree'
									);

								// Add values to the schema

									if (
										is_array($item['contactOption'])
									) {

										$item['contactOption'] = array_intersect(
											$schema_ContactPoint_contactOption_valid,
											$item['contactOption']
										);

										$schema_ContactPoint['contactOption'] = $item['contactOption'] ?: '';

									} elseif (
										in_array(
											$item['contactOption'],
											$schema_ContactPoint_contactOption_valid
										)
									) {

										$schema_ContactPoint['contactOption'] = $item['contactOption'];

									}

							// contactType

								/*

									A person or organization can have different contact points, for different purposes.

									For example, a sales contact point, a PR contact point and so on.

									This property is used to specify the kind of contact point.

									Examples from schema.org:

										- 'customer service'
										- 'technical support'
										- 'bill payment'
										- 'mailing address'

									Expected Type:

										- Text

								*/

								$schema_ContactPoint['contactType'] = $item['contactType'] ?? '';

							// email

								/*

									Email address.

									Expected Type:

										- Text

								*/

								$schema_ContactPoint['email'] = $item['email'] ?? '';

							// faxNumber

								/*

									The fax number.

									Expected Type:

										- Text

								*/

								$schema_ContactPoint['faxNumber'] = $item['faxNumber'] ?? '';

							// hoursAvailable

								/*

									The hours during which this service or contact is available.

									Expected Type:

										- OpeningHoursSpecification

								*/

								$schema_ContactPoint['hoursAvailable'] = $item['hoursAvailable'] ?? '';

							// potentialAction

								/*

									Indicates a potential Action, which describes an idealized action in which this thing would play an 'object' role.

									Expected Type:

										- Action

								*/

								$schema_ContactPoint['potentialAction'] = $item['potentialAction'] ?? '';

							// productSupported

								/*

									The product or service this support contact point is related to (such as product support for a particular product line). This can be a specific product or product line (e.g., "iPhone") or a general category of products or services (e.g., "smartphones").

									Expected Type:

										- Product
										- Text

								*/

								$schema_ContactPoint['productSupported'] = $item['productSupported'] ?? '';

							// telephone

								/*

									The telephone number.

									Expected Type:

										- Text

								*/

								$schema_ContactPoint['telephone'] = $item['telephone'] ?? '';

							// url

								/*

									URL of the item.

									Expected Type:

										- Text

								*/

								$schema_ContactPoint['url'] = $item['url'] ?? '';

						// Clean up item array

							if ( $schema_ContactPoint ) {

								$schema_ContactPoint = array_filter($schema_ContactPoint);

							}

						// Add @type

							if ( $schema_ContactPoint ) {

								$schema_ContactPoint = array( '@type' => 'ContactPoint' ) + $schema_ContactPoint;

							}

						// Add to the contactPoint property value array

							if ( $schema_ContactPoint ) {

								$schema_ContactPoint_list[] = $schema_ContactPoint;

							}

					}

				}

			// Clean up the contactPoint property value array

				if ( $schema_ContactPoint_list ) {

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($schema_ContactPoint_list);

				}

			// Return the contactPoint property value array

				return $schema_ContactPoint_list;

		}

	// Add data to an array defining schema data for faxNumber

		function uamswp_fad_schema_fax_number(
			$schema_fax_number = array(), // array (optional) // Main faxNumber schema array
			$fax_number = '' // string (optional) // The fax number.
		) {

			/* 
			 * Example use:
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

			/* 
			 * Example use:
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

			/* 
			 * Example use:
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

			/* 
			 * Example use:
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
			string $latitude, // string // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
			string $longitude, // string // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
			string $elevation = '', // string // Optional // The elevation of a location (WGS 84). Values may be of the form 'NUMBER UNIT_OF_MEASUREMENT' (e.g., '1,000 m', '3,200 ft') while numbers alone should be assumed to be a value in meters.
			array $schema_geo_coordinates = array() // array // Optional // Existing main GeoCoordinates schema array
		) {

			/* 
			 * Example use:
			 * 
			 * 	// GeoCoordinates Schema Data
			 * 
			 * 		// Check/define the main GeoCoordinates schema array
			 * 
			 * 			$schema_geo_coordinates = $schema_geo_coordinates ?? array();
			 * 			$schema_geo_coordinates = $schema_geo_coordinates && !is_array($schema_geo_coordinates) ?? array($schema_geo_coordinates) : $schema_geo_coordinates;
			 * 
			 * 		// Add this location's details to the main GeoCoordinates schema array
			 * 
			 * 			$schema_geo_coordinates = uamswp_schema_geo_coordinates(
			 * 				$schema_latitude, // string // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
			 * 				$schema_longitude, // string // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
			 * 				$schema_elevation, // string // Optional // The elevation of a location (WGS 84). Values may be of the form 'NUMBER UNIT_OF_MEASUREMENT' (e.g., '1,000 m', '3,200 ft') while numbers alone should be assumed to be a value in meters.
			 * 				$schema_geo_coordinates // array // Optional // Main GeoCoordinates schema array
			 * 			);
			 */

			// Check existing array

				if ( $schema_geo_coordinates ) {

					// If the existing array is flat, nest it in an additional layer

						if ( array_key_exists( '@type', $schema_geo_coordinates ) ) {

							$schema_geo_coordinates = array($schema_geo_coordinates);

						}

				}

			// Add values to the array

				$schema = array(
					'@type' => 'GeoCoordinates',
					'latitude' => $latitude,
					'longitude' => $longitude
				);

				if ( $elevation ) {
					$schema['elevation'] = $elevation;
				}

			// Add this item's array to the main GeoCoordinates schema array

				if ( !empty($schema) ) {
					$schema_geo_coordinates[] = $schema;
				}

			// Clean up the array

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_geo_coordinates);

			// Return the main GeoCoordinates schema array

				return $schema_geo_coordinates;

		}

	// Add data to an array defining schema data for hospitalAffiliation

		function uamswp_fad_schema_hospital_affiliation(
			array $hospital_affiliation, // array // Required // Hospital affiliation ID values
			string $page_url, // string // Required // Page URL
			int $nesting_level = 1, // int // Optional // Nesting level within the main schema
			array $schema_hospital_affiliation = array() // array // Optional // Pre-existing list array for hospitalAffiliation to which to add additional items
		) {

			// Check/define variables

				$schema_hospital_affiliation_i = 1;

				if ( $schema_hospital_affiliation ) {

					$schema_hospital_affiliation = array_is_list($schema_hospital_affiliation) ? $schema_hospital_affiliation : array($schema_hospital_affiliation);
					$schema_hospital_affiliation_i = $schema_hospital_affiliation_i ? count($schema_hospital_affiliation) + 1 : 1;

				}

			// Create an array for this item

				$schema = array();

			// Loop through each hospital affiliation

				if ( $hospital_affiliation ) {

					foreach ( $hospital_affiliation as $hospital ) {

						// Eliminate PHP errors / reset variables

							$hospital_term = '';
							$hospital_location = '';
						// Get the Hospital Affiliation term from the ID

							$hospital_term = get_term( $hospital, 'affiliation' ) ?? array();

						// Get the ID of the location profile associated with the Affiliated Location

							if ( is_object($hospital_term) ) {

								$hospital_location = get_field( 'affiliation_location', $hospital_term ) ?? array();

								// Check location value

									if ( $hospital_location ) {

										$hospital_location = ( is_array($hospital_location) && !empty($hospital_location) ) ? reset($hospital_location) : $hospital_location;

									}

							}

						// Get hospital location's attribute values

							if ( !$hospital_location ) {

								// Skip the rest of the current loop iteration
								continue;

							} else {

								$schema = uamswp_fad_schema_location(
									array($hospital_location), // List of IDs of the location items
									$page_url, // Page URL
									$nesting_level, // Nesting level within the main schema
									$schema_hospital_affiliation_i, // Iteration counter
									array(), // Pre-existing field values array so duplicate calls can be avoided
									$schema_hospital_affiliation // Pre-existing list array to which to add additional items
								);

							}

						// Add this item to the list array

							if ( $schema ) {

								$schema_hospital_affiliation[] = $schema;

							}

					} // endforeach

				} // endif ( $hospital_affiliation )

			// Clean up the list array

				if ( $schema_hospital_affiliation ) {

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($schema_hospital_affiliation);

				}

			// Return the main hospitalAffiliation schema array

				return $schema_hospital_affiliation;

		}

	// Add data to an array defining schema data for availableService

		function uamswp_fad_schema_available_service(
			$schema_available_service, // array // Main availableService schema array
			$entity_id // int // ID of the medical entity (a.k.a., Treatment and Procedure post)
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'val_' . $entity_id, // Required // String added to transient name for disambiguation.
				$schema, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

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
					uamswp_fad_set_transient(
						'val_' . $entity_id, // Required // String added to transient name for disambiguation.
						$schema, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

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

												$code_item['inCodeSet'] = array(
													'@type' => 'CategoryCodeSet',
													'alternateName' => $inCodeSet_alternateName,
													'name' => $inCodeSet_name,
													'sameAs' => $inCodeSet_sameAs,
													'url' => $inCodeSet_url
												);

											} // endif ( $inCodeSet_name )

										} // endif ( $inCodeSet )

								} // endif ( $codeValue && $codingSystem )

							// Clean up item array

								if ( $code_item ) {

									$code_item = array_filter($code_item);
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

								// The term exists

								if ( $specialization_term ) {

									$specialization_extension_query = get_field( 'clinical_specialization_extension_query', $specialization_term ) ?? false; // Is this clinical specialization part of the UAMS Health extension to the Health Care Provider Taxonomy Code Set?

									// The specialization is not an extension to the Health Care Provider Taxonomy code set

										if ( !$specialization_extension_query ) {

											$specialization_code_query = get_field( 'clinical_specialization_code_query', $specialization_term ) ?? true; // Does this specialization have a taxonomy code in the Health Care Provider Taxonomy Code Set?

											// The specialization has a taxonomy code in the Health Care Provider Taxonomy code set

												if ( $specialization_code_query ) {

													$specialization_code = $specialization_code_query ? ( get_field( 'clinical_specialization_code', $specialization_term ) ?? '' ) : ''; // Specialization Taxonomy Code in the Health Care Provider Taxonomy Code Set

													// Set the fallback value (slug)

														if ( !$specialization_code ) {

															$specialization_code = $specialization_term->post_name ?? '';

															// Check if fallback value seems like a valid code

																if (
																	!(
																		$specialization_code
																		&&
																		strlen($specialization_code) == 10 // 10 digits
																		&&
																		( preg_match('/[A-Za-z]/', $specialization_code) && preg_match('/[0-9]/', $specialization_code) ) // Only letters and integers
																	)
																) {

																	$specialization_code = '';

																}

														}

												}
										}

									// If code value still does not exist, get the code from the nearest valid ancestor

										if ( !$specialization_code ) {

											// Get the list of ancestors

												$specialization_code_ancestors = get_ancestors(
													$code, // $object_id  // int // Optional // The ID of the object // Default: 0
													'clinical_title', // $object_type // string // Optional // The type of object for which we'll be retrieving ancestors. Accepts a post type or a taxonomy name. // Default: ''
													'taxonomy' // $resource_type // string // Optional // Type of resource $object_type is. Accepts 'post_type' or 'taxonomy'. // Default: ''
												);

											// Loop through each of the ancestors until finding one that does have a code in the code set

												if ( $specialization_code_ancestors ) {

													foreach ( $specialization_code_ancestors as $ancestor ) {

														$specialization_term = get_term( $ancestor, 'clinical_title' ) ?? '';

														if (
															$specialization_term // The term exists
														) {

															$specialization_extension_query = get_field( 'clinical_specialization_extension_query', $specialization_term ) ?? false; // Is this clinical specialization part of the UAMS Health extension to the Health Care Provider Taxonomy Code Set?

															if (
																!$specialization_extension_query // The specialization is not an extension to the Health Care Provider Taxonomy code set
															) {

																$specialization_code_query = get_field( 'clinical_specialization_code_query', $specialization_term ) ?? true; // Does this specialization have a taxonomy code in the Health Care Provider Taxonomy Code Set?

																if (
																	$specialization_code_query // The specialization has a taxonomy code in the Health Care Provider Taxonomy code set
																) {

																	$specialization_code = $specialization_code_query ? ( get_field( 'clinical_specialization_code', $specialization_term ) ?? '' ) : ''; // Specialization Taxonomy Code in the Health Care Provider Taxonomy Code Set

																	if ( $specialization_code ) {

																		// Break foreach loop
																		break;

																	} else {

																		// Set fallback value (slug)

																			$specialization_code = $specialization_term->post_name ?? '';

																		// Check if fallback value seems like a valid code

																			if (
																				$specialization_code
																				&&
																				strlen() == 10 // 10 digits
																				&&
																				( preg_match('/[A-Za-z]/', $myString) && preg_match('/[0-9]/', $myString) ) // Only letters and integers
																			) {

																				// Break foreach loop
																				break;

																			} else {

																				// Skip the rest of the current loop iteration
																				continue;

																			}

																	}

																} else {

																	// Skip the rest of the current loop iteration
																	continue;

																}

															} else {

																// Skip the rest of the current loop iteration
																continue;

															}

														} else {

															// Skip the rest of the current loop iteration
															continue;

														}

													}

													if (
														!$specialization_extension_query // The specialization is not an extension to the Health Care Provider Taxonomy code set
														&&
														$specialization_code_query // The specialization has a taxonomy code in the Health Care Provider Taxonomy code set
													) {

													}
												}

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

											$code_item = array(
												'@type' => 'MedicalCode',
												'codeValue' => $codeValue,
												'codingSystem' => $codingSystem,
												'inCodeSet' => $inCodeSet,
												'name' => $name,
												'url' => $url
											);

										}

								}

							// Clean up item array

								if ( $code_item ) {

									$code_item = array_filter($code_item);
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

					$code_list = array_unique($code_list, SORT_REGULAR);
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
			string $field_name = 'alternate_text', // alternateName item field name
			array $alternateName_schema = array() // array // Optional // Pre-existing schema array for alternateName to which to add alternateName items
		) {

			// Check / define variables

				$repeater = is_array($repeater) ? $repeater : array($repeater);
				$repeater = array_is_list($repeater) ? $repeater : array($repeater);
				$repeater = array_filter($repeater);
				$repeater = array_values($repeater);

				// If the input is empty, end now

					if ( !$repeater ) {

						return $alternateName_schema;

					}

				$alternateName_schema = array_is_list($alternateName_schema) ? $alternateName_schema : array($alternateName_schema);

			// Add each repeater row to the list array

				if ( $repeater ) {

					foreach ( $repeater as $alternateName ) {

						$alternateName_schema[] = $alternateName[$field_name];

					} // endforeach ( $repeater as $alternateName )

				} // endif ( $repeater )

			// Clean up list array

				if ( $alternateName_schema ) {

					$alternateName_schema = array_unique($alternateName_schema);
					$alternateName_schema = array_filter($alternateName_schema);
				}

				if ( $alternateName_schema ) {

					$alternateName_schema = array_values($alternateName_schema);
					sort($alternateName_schema);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($alternateName_schema);

				}

			return $alternateName_schema;

		}

	// Add data to an array defining schema data for MedicineSystem

		function uamswp_fad_schema_medicinesystem(
			array $input // array of MedicineSystem values
		) {

			/* 
			 * The system of medicine that includes this MedicalEntity 
			 * (e.g., 'evidence-based,' 'homeopathic,' 'chiropractic').
			 * 
			 * Values expected to be one of these types:
			 * 
			 *     - MedicineSystem
			 */

			// Base list array

				$MedicineSystem_list = array();

			// Add each item to the list array

				if ( $input ) {

					foreach ( $input as $MedicineSystem ) {

						$MedicineSystem_list[] = $MedicineSystem;

					} // endforeach ( $input as $MedicineSystem )

					// Clean up list array

						// If there is only one item, flatten the multi-dimensional array by one step

							uamswp_fad_flatten_multidimensional_array($MedicineSystem_list);

						// Sort list array

							if ( is_array( $MedicineSystem_list ) ) {

								sort($MedicineSystem_list);

							}

				} // endif ( $input )

			return $MedicineSystem_list;

		}

	// Add data to an array defining schema data for sameAs

		function uamswp_fad_schema_sameas(
			array $repeater, // array // Required // sameAs repeater field
			string $field_name = 'schema_sameas_url', // string // Optional // sameAs item field name
			array $sameAs_schema = array() // array // Optional // Pre-existing schema array for sameAs to which to add sameAs items
		) {

			/* 
			 * URL of a reference Web page that unambiguously indicates the item's identity 
			 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official 
			 * website).
			 * 
			 * Values expected to be one of these types:
			 * 
			 *     - URL
			 */

			// Check / define variables

				$repeater = is_array($repeater) ? $repeater : array($repeater);
				$repeater = array_is_list($repeater) ? $repeater : array($repeater);
				$repeater = array_filter($repeater);
				$repeater = array_values($repeater);

				// If the input is empty, end now

					if ( !$repeater ) {

						return $sameAs_schema;

					}

				$sameAs_schema = array_is_list($sameAs_schema) ? $sameAs_schema : array($sameAs_schema);

			// Add each repeater row to the list array

				if ( $repeater ) {

					foreach ( $repeater as $sameAs ) {

						$sameAs_schema[] = $sameAs[$field_name];

					} // endforeach ( $repeater as $sameAs )

				} // endif ( $repeater )

			// Clean up list array

				if ( $sameAs_schema ) {

					$sameAs_schema = array_unique($sameAs_schema);
					$sameAs_schema = array_filter($sameAs_schema);
				}

				if ( $sameAs_schema ) {

					$sameAs_schema = array_values($sameAs_schema);
					sort($sameAs_schema);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($sameAs_schema);

				}

			return $sameAs_schema;

		}

	// Add data to an array defining schema data for additionalType

		function uamswp_fad_schema_additionaltype(
			array $repeater, // additionalType repeater field
			string $field_name = 'schema_additionalType_uri' // additionalType item field name
		) {

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

			// Base list array

				$additionalType_list = array();

			// Add each repeater row to the list array

				if (
					$repeater
					&&
					$field_name
				) {

					foreach ( $repeater as $additionalType ) {

						$additionalType_list[] = $additionalType[$field_name];

					} // endforeach ( $repeater as $additionalType )

					// Clean up list array

						$additionalType_list = array_unique($additionalType_list);
						$additionalType_list = array_filter($additionalType_list);
						$additionalType_list = array_values($additionalType_list);
						sort($additionalType_list);

						// If there is only one item, flatten the multi-dimensional array by one step

							uamswp_fad_flatten_multidimensional_array($additionalType_list);

				} // endif ( $repeater )

			return $additionalType_list;

		}

	// Add data to an array defining schema data for PropertyValue

		function uamswp_fad_schema_propertyvalue(
			$alternateName = null, // mixed // Optional // alternateName property value
			string $description = null, // string // Optional // description property value
			int $maxValue = null, // int // Optional // maxValue property value
			$measurementMethod = null, // mixed // Optional // measurementMethod property value
			$measurementTechnique = null, // mixed // Optional // measurementTechnique property value
			int $minValue = null, // int // Optional // minValue property value
			string $name = null, // string // Optional // name property value
			string $propertyID = null, // string // Optional // propertyID property value
			string $unitCode = null, // string // Optional // unitCode property value
			string $unitText = null, // string // Optional // unitText property value
			string $url = null, // string // Optional // url property value
			$value = null, // mixed // Optional // value property value
			$valueReference = null, // mixed // Optional // valueReference property value
			array $propertyvalue_list = array() // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
		) {

			/*

				A property-value pair (e.g., representing a feature of a product or place). Use 
				the 'name' property for the name of the property. If there is an additional 
				human-readable version of the value, put that into the 'description' property.

				Always use specific schema.org properties when a) they exist and b) you can 
				populate them. Using PropertyValue as a substitute will typically not trigger 
				the same effect as using the original, specific property.

				$name

					Name of the property

					Examples:

						 * 'Data Universal Numbering System number'
						 * 'National Provider Identifier'

					Expected Type:

						 * Text

				$alternateName

					Alternate name(s) of the property

					Examples:

						 * array( 'Data Universal Numbering System (DUNS) number', 'DUNS number', 'D-U-N-S number' )
						 * 'NPI'

					Expected Type:

						 * Text

				$propertyID

					A commonly used identifier for the characteristic represented by the 
					property (e.g., a manufacturer or a standard code for a property). 
					propertyID can be:

						(1) a prefixed string, mainly meant to be used with standards for product 
						properties;

						(2) a site-specific, non-prefixed string (e.g., the primary key of the 
						property or the vendor-specific ID of the property), or

						(3) a URL indicating the type of the property, either pointing to an 
						external vocabulary, or a Web resource that describes the property 
						(e.g., a glossary entry).

					Standards bodies should promote a standard prefix for the identifiers of 
					properties from their standards.

					Examples:

						 * 'https://www.wikidata.org/wiki/Q246386' (for Data Universal Numbering System 
						   number)
						 * 'https://www.wikidata.org/wiki/Q6975101' (for National Provider Identifier)

					Expected Type:

						 * Text
						 * URL

				$value

					The value of a property value node.

					Use values from 0123456789 (Unicode 'DIGIT ZERO' (U+0030) to 
					'DIGIT NINE' (U+0039)) rather than superficially similar Unicode symbols.

					Use '.' (Unicode 'FULL STOP' (U+002E)) rather than ',' to indicate a 
					decimal point. Avoid using these symbols as a readability separator.

					Examples:

						 * '122452563' (for Data Universal Numbering System number)
						 * '1841276169' (for National Provider Identifier)

					Expected Type:

						 * Boolean
						 * Number
						 * StructuredValue
						 * Text

				$url

					URL of a reference Web page that unambiguously indicates the value

					Examples:

						 * 'https://npiregistry.cms.hhs.gov/provider-view/1841276169' (for a National 
						   Provider Identifier that is '1841276169')

					Expected Type:

						 * URL

				$description

					If there is an additional human-readable version of the value, put that 
					into the 'description' property.

					Expected Type:

						 * Text
						 * TextObject

				$maxValue

					The upper value of some characteristic or property.

					Expected Type:

						 * Number

				$measurementMethod

					A subproperty of measurementTechnique that can be used for specifying 
					specific methods, in particular via MeasurementMethodEnum.

					Expected Type:

						 * DefinedTerm
						 * MeasurementMethodEnum
						 * Text
						 * URL

				$measurementTechnique

					A technique, method or technology used in an Observation, 
					StatisticalVariable or Dataset (or DataDownload, DataCatalog), 
					corresponding to the method used for measuring the corresponding 
					variable(s) (for datasets, described using variableMeasured; for 
					Observation, a StatisticalVariable). Often but not necessarily each 
					variableMeasured will have an explicit representation as (or mapping to) an 
					property such as those defined in Schema.org, or other RDF vocabularies and 
					"knowledge graphs". In that case the subproperty of variableMeasured called 
					measuredProperty is applicable.

					The measurementTechnique property helps when extra clarification is needed 
					about how a measuredProperty was measured. This is oriented towards 
					scientific and scholarly dataset publication but may have broader 
					applicability; it is not intended as a full representation of measurement, 
					but can often serve as a high level summary for dataset discovery.

					For example, if variableMeasured is: molecule concentration, 
					measurementTechnique could be: "mass spectrometry" or "nmr spectroscopy" or 
					"colorimetry" or "immunofluorescence". If the variableMeasured is 
					"depression rating", the measurementTechnique could be "Zung Scale" or 
					"HAM-D" or "Beck Depression Inventory".

					If there are several variableMeasured properties recorded for some given 
					data object, use a PropertyValue for each variableMeasured and attach the 
					corresponding measurementTechnique. The value can also be from an 
					enumeration, organized as a MeasurementMetholdEnumeration.

					Expected Type:

						 * DefinedTerm
						 * MeasurementMethodEnum
						 * Text
						 * URL

				$minValue

					The lower value of some characteristic or property.

					Expected Type:

						 * Number

				$unitCode

					The unit of measurement given using the UN/CEFACT Common Code 
					(3 characters) or a URL. Other codes than the UN/CEFACT Common Code may be 
					used with a prefix followed by a colon.

					Expected Type:

						 * Text
						 * URL

				$unitText

					A string or text indicating the unit of measurement. Useful if you cannot 
					provide a standard unit code for unitCode.

					Expected Type:

						 * Text

				$valueReference

					A secondary value that provides additional information on the original 
					value (e.g., a reference temperature or a type of measurement).

					Expected Type:

						 * DefinedTerm
						 * Enumeration
						 * MeasurementTypeEnumeration
						 * PropertyValue
						 * QualitativeValue
						 * QuantitativeValue
						 * StructuredValue
						 * Text

			 */

			// Check/define variables

				if ( $propertyvalue_list ) {

					$propertyvalue_list = array_is_list($propertyvalue_list) ? $propertyvalue_list : array($propertyvalue_list);

				}

				// If the arguments are all empty, end here

					if (
						!$alternateName
						&&
						!$description
						&&
						!$maxValue
						&&
						!$measurementMethod
						&&
						!$measurementTechnique
						&&
						!$minValue
						&&
						!$name
						&&
						!$propertyID
						&&
						!$unitCode
						&&
						!$unitText
						&&
						!$url
						&&
						!$value
						&&
						!$valueReference
					) {

						return $propertyvalue_list;

					}

			// Set property values

				$propertyvalue_item = array(
					'alternateName' => $alternateName,
					'description' => $description,
					'maxValue' => $maxValue,
					'measurementMethod' => $measurementMethod,
					'measurementTechnique' => $measurementTechnique,
					'minValue' => $minValue,
					'name' => $name,
					'propertyID' => $propertyID,
					'unitCode' => $unitCode,
					'unitText' => $unitText,
					'url' => $url,
					'value' => $value,
					'valueReference' => $valueReference
				);

			// Clean up item array

				$propertyvalue_item = array_filter($propertyvalue_item);

			// Add @type

				if ( $propertyvalue_item ) {

					$propertyvalue_item = array( '@type' => 'PropertyValue' ) + $propertyvalue_item;

				}

			// Add item to the list array

				if ( $propertyvalue_item ) {

					$additionalType_list[] = $propertyvalue_item;

				}

			return $additionalType_list;

		}

	// Add data to an array defining schema data for Language

		function uamswp_fad_schema_language(
			$languages, // mixed // Required // Language ID values
			string &$language_string = null, // string // Optional // Pre-existing string variable to populate with a comma-separated list of language names
			string &$language_string_attr = null, // string // Optional // Pre-existing string variable to populate with an attribute-friendly comma-separated list of language names
			array &$language_array = array(), // array // Optional // Pre-existing array variable to populate with a list of language names
			array &$language_array_attr = array(), // array // Optional // Pre-existing array variable to populate with an attribute-friendly list of language names
			array $language_schema = array() // array // Optional // Pre-existing schema array for Language to which to add additional items
		) {

			/*

				Natural languages such as Spanish, Tamil, Hindi, English, etc.

				Formal language code tags expressed in BCP 47 can be used via 
				the alternateName property.

				The Language type previously also covered programming 
				languages such as Scheme and Lisp, which are now best represented using 
				ComputerLanguage.

				$name

					English name for this language (exonym / xenonym)

					Examples:

						 * 'Persian' is the English name, while 'Farsi' is the native name

					Expected Type:

						 * Text

				$alternateName

					Alternate name(s) of the language

						 * Internet Engineering Task Force Best Current Practice 47 (IETF BCP 47) Language Tag
						 * Native Name of Language (Endonym / Autonym)

					Examples:

						 * 'fa' is the BCP 47 code for Persian / Farsi
						 * 'Farsi' is the native name, while 'Persian' is the English name

					Expected Type:

						 * Text

				$sameAs

					URL of a reference Web page that unambiguously indicates the item's identity 
					(e.g., the URL of the item's Wikipedia page, the URL of the item's Wikidata 
					entry, the URL of the item's official website).

					Examples:

						 * 'https://en.wikipedia.org/wiki/Persian_language' (Wikipedia page for Persian)
						 * 'https://www.wikidata.org/wiki/Q9168' (Wikidata entry for Persian)

					Expected Type:

						 * Text
						 * URL

			 */

			// Check/define variables

				// Format input values

					if ( is_array($languages) ) {

						$languages = array_filter($languages);

						if ( $languages ) {

							$languages = array_is_list($languages) ? $languages : array($languages);

						}

					} else {

						$languages = array($languages);

					}

				// If the arguments are all empty, end here

					if ( !$languages ) {

						return $language_list;

					}

				// Format pre-existing output values

					// Explode string variables

						$language_string = array_filter(
							explode(
								', ',
								$language_string
							)
						);

						$language_string_attr = array_filter(
							explode(
								', ',
								$language_string_attr
							)
						);

					if ( $language_array ) {

						$language_array = array_is_list($language_array) ? $language_array : array($language_array);

					}

					if ( $language_array_attr ) {

						$language_array_attr = array_is_list($language_array_attr) ? $language_array_attr : array($language_array_attr);

					}

					if ( $language_schema ) {

						$language_schema = array_is_list($language_schema) ? $language_schema : array($language_schema);

					}

			// Get values

				foreach ( $languages as $item ) {

					if ( $item ) {

						$item_term = get_term_by( 'id', $item, 'language');

						if ( is_object($item_term) ) {

							// Language English name

								$item_name = $item_term->name ?? '';
								$item_name_attr = $item_name ? uamswp_attr_conversion($item_name) : '';
								$language_array[] = $item_name;
								$language_string[] = $item_name;
								$language_array_attr[] = $item_name_attr;
								$language_string_attr[] = $item_name;
								$item_schema['name'] = '';

							// alternateName

								// Language native name

									$item_name_native = get_field( 'language_name_native', $item_term ) ?? '';
									$item_name_native_attr = $item_name_native ? uamswp_attr_conversion($item_name_native) : '';
									$item_schema['alternateName'][] = $item_name_native_attr;

								// Language Internet Engineering Task Force Best Current Practice 47 (IETF BCP 47) language tag

									$item_bcp47 = get_field( 'language_bcp47', $item_term ) ?? '';
									$item_bcp47_attr = $item_bcp47 ? uamswp_attr_conversion($item_bcp47) : '';
									$item_schema['alternateName'][] = $item_bcp47_attr;

								// Clean up array

									if ( $item_schema['alternateName'] ) {

										$item_schema['alternateName'] = array_filter($item_schema['alternateName']);
										$item_schema['alternateName'] = array_unique($item_schema['alternateName']);
										$item_schema['alternateName'] = array_values($item_schema['alternateName']);

									}

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($item_schema['alternateName']);

									// Sort array

										if ( is_array($item_schema['alternateName']) ) {

											sort($item_schema['alternateName']);

										}

							// sameAs

								$item_sameAs = array();

								if ( $item_sameAs ) {

									$item_schema['sameAs'] = uamswp_fad_schema_sameas(
										$item_sameAs, // sameAs repeater field
										'schema_sameas_url' // sameAs item field name
									);

								} // endif

						} // endif

						// Add @type

							if ( $item_schema ) {

								$item_schema = array( '@type' => 'Language' ) + $item_schema;

							} // endif

						// Add item to schema list

							if ( $item_schema ) {

								$language_schema[] = $item_schema;

							} // endif

					}

				} // endforeach

			// Format values

				// Sort

					sort($language_string);
					sort($language_string_attr);
					sort($language_array);
					sort($language_array_attr);

				// String lists

					// Standard

						$language_string = $language_string ? implode( ', ', $language_string ) : '';

					// Attribute-friendly

						$language_string_attr = $language_string_attr ? implode( ', ', $language_string_attr ) : '';

			// Clean up schema list array

				if ( $language_schema ) {

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($language_schema);

				}

			return $language_schema;

		}

	// Add data to an array defining schema data for health care professional associations (memberOf)

		function uamswp_fad_schema_associations(
			$associations, // mixed // Required // Health care professional association ID values
			array &$association_array = array(), // array // Optional // Pre-existing array variable to populate with a list of association names
			array $memberOf_schema = array() // array // Optional // Pre-existing schema array for Language to which to add association items
		) {

			/*

				An Organization (or ProgramMembership) to which this Person or Organization 
				belongs.

				Inverse-property: member

				Values expected to be one of these types:

					 * Organization
					 * ProgramMembership

				Sub-properties:

					 * affiliation

			*/

			// Check / define variables

				$associations = is_array($associations) ? $associations : array($associations);
				$associations = array_is_list($associations) ? $associations : array($associations);
				$associations = array_filter($associations);
				$associations = array_values($associations);

				// If the input is empty, end now

					if ( !$associations ) {

						return $memberOf_schema;

					}

				$memberOf_schema = array_is_list($memberOf_schema) ? $memberOf_schema : array($memberOf_schema);

			// Get values

				foreach ( $associations as $association ) {

					// Eliminate PHP errors / reset variables

						$association_term = null;
						$association_name = null;
						$association_alternateName_array = null;
						$association_alternateName = null;
						$association_sameAs_array = null;
						$association_sameAs = null;
						$association_url = null;

					// Base array

						$association_schema = array(
							'alternateName' => '',
							'name' => '',
							'sameAs' => '',
							'url' => ''
						);

					$association_term = get_term( $association, 'association' ) ?? '';

					if ( is_object($association_term) ) {

						// name

							$association_name = $association_term->name;

							// Add name to list of association names

								if ( $association_name ) {

									$association_array[] = $association_name;

								}

							// Add to schema item

								if ( $association_name ) {

									$association_schema['name'] = uamswp_attr_conversion($association_name);

								}

						// alternateName

							// Get alternateName repeater field value

								$association_alternateName_array = get_field( 'schema_alternatename', $association_term ) ?? array();

							// Add each item to alternateName property values array

								if ( $association_alternateName_array ) {

									$association_alternateName = uamswp_fad_schema_alternatename(
										$association_alternateName_array, // alternateName repeater field
										'schema_alternatename_text' // alternateName item field name
									);

								}

							// Add to schema item

								if ( $association_alternateName ) {

									$association_schema['alternateName'] = $association_alternateName;

								}

						// sameAs

							// Get sameAs repeater field value

								$association_sameAs_array = get_field( 'schema_sameas', $association_term ) ?? array();

							// Add each item to sameAs property values array

								if ( $association_sameAs_array ) {

									$association_sameAs = uamswp_fad_schema_sameas(
										$association_sameAs_array, // sameAs repeater field
										'schema_sameas_url' // sameAs item field name
									);

								}

							// Add to schema item

								if ( $association_sameAs ) {

									$association_schema['sameAs'] = $association_sameAs;

								}

						// url (Official Website)

							$association_url = get_field( 'schema_url', $association_term ) ?? '';

							// Add to schema item

								if ( $association_url ) {

									$association_schema['url'] = uamswp_attr_conversion($association_url);

								}

						// Clean up schema item array

							$association_schema = array_filter($association_schema);

						// Add @type

							if ( $association_schema ) {

								$association_schema = array( '@type' => 'Organization' ) + $association_schema;

							}

						// Add to the list array

							if ( $association_schema ) {

								$memberOf_schema[] = $association_schema;

							}

					} // endif

				} // endforeach

			// Clean up schema list array

				if ( $memberOf_schema ) {

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($memberOf_schema);

				}

			return $memberOf_schema;

		}

	// Add data to an array defining schema data for hasCredential

		function uamswp_fad_schema_hascredential(
			$credentials, // mixed // Required // Degrees and credentials ID values
			array $hasCredential_schema = array() // array // Optional // Pre-existing schema array for hasCredential to which to add credential items
		) {

			/*

				'hasCredential' property:

					A credential awarded to the Person or Organization.

					Values expected to be one of these types:

						 * EducationalOccupationalCredential

				'EducationalOccupationalCredential' type:

					An educational or occupational credential. A diploma, academic degree, 
					certification, qualification, badge, etc., that may be awarded to a person 
					or other entity that meets the requirements defined by the credentialer.

			*/

			// Check / define variables

				$credentials = is_array($credentials) ? $credentials : array($credentials);
				$credentials = array_is_list($credentials) ? $credentials : array($credentials);
				$credentials = array_filter($credentials);
				$credentials = array_values($credentials);

				// If the input is empty, end now

					if ( !$credentials ) {

						return $hasCredential_schema;

					}

				$hasCredential_schema = array_is_list($hasCredential_schema) ? $hasCredential_schema : array($hasCredential_schema);

			// Credential Transparency Description Language Values Map

				$ctdl_values = array(
					'ApprenticeshipCertificate' => array(
						'label' => '',
						'definition' => ''
					),
					'Assessment' => array(
						'label' => '',
						'definition' => ''
					),
					'AssociateDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'AssociateOfAppliedArtsDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'AssociateOfAppliedScienceDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'AssociateOfArtsDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'AssociateOfScienceDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'BachelorDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'BachelorOfArtsDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'BachelorOfScienceDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'Badge' => array(
						'label' => '',
						'definition' => ''
					),
					'Certificate' => array(
						'label' => '',
						'definition' => ''
					),
					'CertificateOfCompletion' => array(
						'label' => '',
						'definition' => ''
					),
					'Certification' => array(
						'label' => '',
						'definition' => ''
					),
					'Course' => array(
						'label' => '',
						'definition' => ''
					),
					'Credential' => array(
						'label' => '',
						'definition' => ''
					),
					'Degree' => array(
						'label' => '',
						'definition' => ''
					),
					'DigitalBadge' => array(
						'label' => '',
						'definition' => ''
					),
					'Diploma' => array(
						'label' => '',
						'definition' => ''
					),
					'DoctoralDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'GeneralEducationDevelopment' => array(
						'label' => '',
						'definition' => ''
					),
					'JourneymanCertificate' => array(
						'label' => '',
						'definition' => ''
					),
					'LearningProgram' => array(
						'label' => '',
						'definition' => ''
					),
					'License' => array(
						'label' => '',
						'definition' => ''
					),
					'MasterCertificate' => array(
						'label' => '',
						'definition' => ''
					),
					'MasterDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'MasterOfArtsDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'MasterOfScienceDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'MicroCredential' => array(
						'label' => '',
						'definition' => ''
					),
					'ProfessionalDoctorate' => array(
						'label' => 'Professional Doctorate',
						'definition' => 'Doctoral degree conferred upon completion of a program providing the knowledge and skills for the recognition, credential, or license required for professional practice.'
					),
					'QualityAssuranceCredential' => array(
						'label' => '',
						'definition' => ''
					),
					'ResearchDoctorate' => array(
						'label' => '',
						'definition' => ''
					),
					'SecondarySchoolDiploma' => array(
						'label' => '',
						'definition' => ''
					),
					'SpecialistDegree' => array(
						'label' => '',
						'definition' => ''
					)
				);

			// Get values

				foreach ( $credentials as $credential ) {

					// Eliminate PHP errors / reset variables

						$credential_term = null;
						$credential_name = null;
						$credential_alternateName_array = null;
						$credential_alternateName = null;
						$credential_sameAs_array = null;
						$credential_sameAs = null;
						$credential_url = null;

					// Base array

						$credential_schema = array(
							'alternateName' => '',
							'credentialCategory' => array(
								'description' => '',
								'inDefinedTermSet' => array(
									'@type' => 'DefinedTermSet',
									'name' => 'Credential Transparency Description Language',
									'url' => 'http://purl.org/ctdl/terms/'
								),
								'name' => '',
								'termCode' => '',
								'url' => '',
							),
							'name' => '',
						);

					$credential_term = get_term( $credential, 'degree' ) ?? '';

					if ( is_object($credential_term) ) {

						// name (full name of the clinical degree or credential)

							$credential_name = get_field( 'degree_name', $credential_term ) ?? '';
							$credential_schema['name'] = $credential_name;

						// alternateName (e.g., abbreviation of the clinical degree or credential)

							// Base array

								$credential_alternateName = array();

							// Get abbreviation of clinical degree or credential

								$credential_abbreviation = $credential_term->name ?? '';
								$credential_alternateName[] = $credential_abbreviation;

							// Get alternateName repeater field value

								$credential_alternateName_array = get_field( 'schema_alternatename', $credential_term ) ?? array();

							// Add each item to alternateName property values array

								if ( $credential_alternateName_array ) {

									$credential_alternateName = uamswp_fad_schema_alternatename(
										$credential_alternateName_array, // alternateName repeater field
										'schema_alternatename_text', // alternateName item field name
										$credential_alternateName // array // Optional // Pre-existing schema array for alternateName to which to add alternateName items
									);

								} else {

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($credential_alternateName);

								}

							// Add to schema item

								if ( $credential_alternateName ) {

									$credential_schema['alternateName'] = $credential_alternateName;

								}

						// sameAs

							// Get sameAs repeater field value

								$credential_sameAs_array = get_field( 'schema_sameas', $credential_term ) ?? array();

							// Add each item to sameAs property values array

								if ( $credential_sameAs_array ) {

									$credential_sameAs = uamswp_fad_schema_sameas(
										$credential_sameAs_array, // sameAs repeater field
										'schema_sameas_url' // sameAs item field name
									);

								}

							// Add to schema item

								if ( $credential_sameAs ) {

									$credential_schema['sameAs'] = $credential_sameAs;

								}

						// credentialCategory (Credential Transparency Description Language schema term of clinical degree or credential)

							$credential_ctdl = get_field( 'degree_ctdl', $credential_term ) ?? '';

							if ( $credential_ctdl ) {

								$credential_schema['credentialCategory']['description'] = $ctdl_values[$credential_ctdl]['definition'] ?? '';
								$credential_schema['credentialCategory']['name'] = $ctdl_values[$credential_ctdl]['label'] ?? '';
								$credential_schema['credentialCategory']['termCode'] = $credential_ctdl;
								$credential_schema['credentialCategory']['url'] = 'https://purl.org/ctdl/terms/' . $credential_ctdl;

								// Clean up array

									$credential_schema['credentialCategory'] = array_filter($credential_schema['credentialCategory']);

							} else {

								unset($credential_schema['credentialCategory']);

							}

						// Clean up schema item array

							$credential_schema = array_filter($credential_schema);

						// Add @type

							if ( $credential_schema ) {

								$credential_schema = array( '@type' => 'EducationalOccupationalCredential' ) + $credential_schema;

							}

						// Add to the list array

							if ( $credential_schema ) {

								$hasCredential_schema[] = $credential_schema;

							}

					} // endif

				} // endforeach

			// Clean up schema list array

				if ( $hasCredential_schema ) {

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($hasCredential_schema);

				}

			return $hasCredential_schema;

		}

	// Add data to an array defining schema data for hasOccupation

		function uamswp_fad_schema_hasoccupation(
			$specializations, // mixed // Required // Clinical Specialization ID values
			array $hasOccupation_schema = array() // array // Optional // Pre-existing schema array for hasOccupation to which to add hasOccupation items
		) {

			/*

				'hasOccupation' property:

					The Person's occupation. For past professions, use Role for expressing dates.

					Values expected to be one of these types:

						 * Occupation

				'Occupation' type:

					A profession, may involve prolonged training and/or a formal qualification.

			*/

			// Check / define variables

				$specializations = is_array($specializations) ? $specializations : array($specializations);
				$specializations = array_is_list($specializations) ? $specializations : array($specializations);
				$specializations = array_filter($specializations);
				$specializations = array_values($specializations);

				// If the input is empty, end now

					if ( !$specializations ) {

						return $hasOccupation_schema;

					}

				$hasOccupation_schema = array_is_list($hasOccupation_schema) ? $hasOccupation_schema : array($hasOccupation_schema);

			// Get values

				foreach ( $specializations as $specialization ) {

					// Eliminate PHP errors / reset variables

						$specialization_term = null;
						$specialization_name = null;
						$specialization_alternateName_array = null;
						$specialization_alternateName = null;
						$specialization_sameAs_array = null;
						$specialization_sameAs = null;
						$specialization_url = null;

					// Base array

						$specialization_schema = array(
							'name' => '',
							'alternateName' => '',
							'description' => '',
							'occupationalCategory' => '',
							'sameAs' => ''
						);

					$specialization_term = get_term( $specialization, 'clinical_title' ) ?? '';

					if ( is_object($specialization_term) ) {

						// name (clinical occupation title value from Clinical Specialization item)

							$specialization_name = get_field( 'clinical_specialization_title', $specialization_term ) ?? '';
							$specialization_schema['name'] = $specialization_name;

						// alternateName (alternate clinical occupation title value from Clinical Specialization item)

							// Base array

								$specialization_alternateName = array();

							// Get alternateName repeater field value

								$specialization_alternateName_array = get_field( 'schema_alternatename', $specialization_term ) ?? array();

							// Add each item to alternateName property values array

								if ( $specialization_alternateName_array ) {

									$specialization_alternateName = uamswp_fad_schema_alternatename(
										$specialization_alternateName_array, // alternateName repeater field
										'schema_alternatename_text', // alternateName item field name
										$specialization_alternateName // array // Optional // Pre-existing schema array for alternateName to which to add alternateName items
									);

								}

							// Add to schema item

								if ( $specialization_alternateName ) {

									$specialization_schema['alternateName'] = $specialization_alternateName;

								}

						// description

							$specialization_description = get_field( 'clinical_specialization_definition', $specialization_term ) ?? '';

							// Add to schema item

								if ( $specialization_description ) {

									$specialization_schema['description'] = $specialization_description;

								}

						// occupationalCategory

							// O*Net-SOC

								// Occupation Code

								$specialization_onetsoc_code = get_field( 'clinical_specialization_onetsoc_code', $specialization_term ) ?? '';
								$specialization_onetsoc_code = $specialization_onetsoc_code ? uamswp_attr_conversion($specialization_onetsoc_code) : '';

								// Occupation Name

									$specialization_onetsoc_code_name = get_field( 'clinical_specialization_onetsoc_code_name', $specialization_term );
									$specialization_onetsoc_code_name = $specialization_onetsoc_code_name ? uamswp_attr_conversion($specialization_onetsoc_code_name) : '';

								// Array

									$specialization_onetsoc = array();

									if ( $specialization_onetsoc_code ) {

										$specialization_onetsoc = array(
											'@type' => 'CategoryCode',
											'codeValue' => $specialization_onetsoc_code, // O*Net-SOC code value from Specialty item
											'inCodeSet' => array(
												'@type' => 'CategoryCodeSet',
												'dateModified' => '2019',
												'name' => 'O*Net-SOC',
												'url' => 'https://www.onetonline.org/'
											),
											'name' => $specialization_onetsoc_code_name ?? '', // O*Net-SOC name from Specialty item
											'url' => 'https://www.onetonline.org/link/summary/' . $specialization_onetsoc_code // O*Net-SOC URL from Specialty item
										);

										// Clean up array

											$specialization_onetsoc = array_filter($specialization_onetsoc);

									}

							// ISCO-08 Code

								$specialization_isco08_code = get_field( 'clinical_specialization_isco08_code', $specialization_term ) ?? array();
								$specialization_isco08_code = is_array($specialization_isco08_code) ? $specialization_isco08_code : array($specialization_isco08_code);
								$specialization_isco08_code = array_filter($specialization_isco08_code);

								// Make value attribute-friendly, add it to an array if it is not already

									if ( $specialization_isco08_code ) {

										foreach ($specialization_isco08_code as &$code ) {

											$code = uamswp_attr_conversion($code);

										}

									}

								// Values map

									$isco08_values = array(
										'2' => array(
											'name' => 'Professionals',
											'description' => 'Professionals increase the existing stock of knowledge; apply scientific or artistic concepts and theories; teach about the foregoing in a systematic manner; or engage in any combination of these activities. Competent performance in most occupations in this major group requires skills at the fourth ISCO skill level.',
											'sameAs' => array()
										),
										'22' => array(
											'name' => 'Health Professionals',
											'description' => 'Health professionals conduct research; improve or develop concepts, theories and operational methods; and apply scientific knowledge relating to medicine, nursing, dentistry, veterinary medicine, pharmacy, and promotion of health.  Competent performance in most occupations in this sub-major group requires skills at the fourth ISCO skill level.',
											'sameAs' => array(
												'2'
											)
										),
										'221' => array(
											'name' => 'Medical Doctors',
											'description' => 'Medical doctors (physicians) study, diagnose, treat and prevent illness, disease, injury and other physical and mental impairments in humans through the application of the principles and procedures of modern medicine. They plan, supervise and evaluate the implementation of care and treatment plans by other health care providers, and conduct medical education and research activities.',
											'sameAs' => array(
												'2',
												'22'
											)
										),
										'2211' => array(
											'name' => 'Generalist Medical Practitioners',
											'description' => 'Generalist medical practitioners (including family and primary care doctors) diagnose, treat and prevent illness, disease, injury and other physical and mental impairments and maintain general health in humans through application of the principles and procedures of modern medicine.  They do not limit their practice to certain disease categories or methods of treatment, and may assume responsibility for the provision of continuing and comprehensive medical care to individuals, families and communities.',
											'sameAs' => array(
												'2',
												'22',
												'221'
											)
										),
										'2212' => array(
											'name' => 'Specialist Medical Practitioners',
											'description' => 'Specialist medical practitioners (medical doctors) diagnose, treat and prevent illness, disease, injury and other physical and mental impairments in humans, using specialized testing, diagnostic, medical, surgical, physical and psychiatric techniques through application of the principles and procedures of modern medicine. They specialize in certain disease categories, types of patient or methods of treatment and may conduct medical education and research in their chosen areas of specialization.',
											'sameAs' => array(
												'2',
												'22',
												'221'
											)
										),
										'222' => array(
											'name' => 'Nursing and Midwifery Professionals',
											'description' => 'Nursing and midwifery professionals provide treatment and care services for people who are physically or mentally ill, disabled or infirm, and others in need of care due to potential risks to health including before, during and after childbirth. They assume responsibility for the planning, management and evaluation of the care of patients, including the supervision of other health care workers, working autonomously or in teams with medical doctors and others in the practical application of preventive and curative measures. ',
											'sameAs' => array(
												'2',
												'22'
											)
										),
										'2221' => array(
											'name' => 'Nursing Professionals',
											'description' => 'Nursing professionals provide treatment, support and care services for people who are in need of nursing care due to the effects of ageing, injury, illness or other physical or mental impairment, or potential risks to health. They assume responsibility for the planning and management of the care of patients, including the supervision of other health care workers, working autonomously or in teams with medical doctors and others in the practical application of preventive and curative measures.',
											'sameAs' => array(
												'2',
												'22',
												'222'
											)
										),
										'2222' => array(
											'name' => 'Midwifery Professionals',
											'description' => 'Midwifery professionals plan, manage, provide and evaluate midwifery care services before, during and after pregnancy and childbirth. They provide delivery care for reducing health risks to women and newborn children, working autonomously or in teams with other health care providers.',
											'sameAs' => array(
												'2',
												'22',
												'222'
											)
										),
										'223' => array(
											'name' => 'Traditional and Complementary Medicine Professionals',
											'description' => 'Traditional and complementary medicine professionals examine patients; prevent and treat illness, disease, injury and other physical and mental impairments; and maintain general health in humans by applying knowledge, skills and practices acquired through extensive study of  the theories, beliefs and experiences originating in specific cultures.',
											'sameAs' => array(
												'2',
												'22'
											)
										),
										'2230' => array(
											'name' => 'Traditional and Complementary Medicine Professionals',
											'description' => 'Traditional and complementary medicine professionals examine patients, prevent and treat illness, disease, injury and other physical and mental impairments and maintain general health in humans by applying knowledge, skills and practices acquired through extensive study of  the theories, beliefs and experiences, originating in specific cultures.',
											'sameAs' => array(
												'2',
												'22',
												'223'
											)
										),
										'224' => array(
											'name' => 'Paramedical Practitioners',
											'description' => 'Paramedical practitioners provide advisory, diagnostic, curative and preventive medical services more limited in scope and complexity than those carried out by medical doctors. They work autonomously or with limited supervision of medical doctors, and apply advanced clinical procedures for treating and preventing diseases, injuries and other physical or mental impairments common to specific communities.',
											'sameAs' => array(
												'2',
												'22'
											)
										),
										'2240' => array(
											'name' => 'Paramedical Practitioners',
											'description' => 'Paramedical practitioners provide advisory, diagnostic, curative and preventive medical services more limited in scope and complexity than those carried out by medical doctors. They work autonomously, or with limited supervision of medical doctors, and apply advanced clinical procedures for treating and preventing diseases, injuries and other physical or mental impairments common to specific communities.',
											'sameAs' => array(
												'2',
												'22',
												'224'
											)
										),
										'225' => array(
											'name' => 'Veterinarians',
											'description' => 'Veterinarians diagnose, prevent and treat diseases, injuries and dysfunctions of animals. They may provide care to a wide range of animals; specialize in the treatment of a particular animal group or in a particular area of specialization; or provide professional services to commercial firms producing biological and pharmaceutical products.',
											'sameAs' => array(
												'2',
												'22'
											)
										),
										'2250' => array(
											'name' => 'Veterinarians',
											'description' => 'Veterinarians diagnose, prevent and treat diseases, injuries and dysfunctions of animals. They may provide care to a wide range of animals or specialize in the treatment of a particular animal group or in a particular specialty area, or provide professional services to commercial firms producing biological and pharmaceutical products.',
											'sameAs' => array(
												'2',
												'22',
												'225'
											)
										),
										'226' => array(
											'name' => 'Other Health Professionals',
											'description' => 'Other health professionals provide health services related to dentistry, pharmacy, environmental health and hygiene, occupational health and safety, physiotherapy, nutrition, hearing, speech, vision and rehabilitation therapies.  This minor group includes all human health professionals except doctors, traditional and complementary medicine practitioners, nurses, midwives and paramedical professionals.',
											'sameAs' => array(
												'2',)
										),
										'2261' => array(
											'name' => 'Dentists',
											'description' => 'Dentists diagnose, treat and prevent diseases, injuries and abnormalities of the teeth, mouth, jaws and associated tissues by applying the principles and procedures of modern dentistry. They use a broad range of specialized diagnostic, surgical and other techniques to promote and restore oral health.',
											'sameAs' => array(
												'2',
												'22',
												'226'
											)
										),
										'2262' => array(
											'name' => 'Pharmacists',
											'description' => 'Pharmacists store, preserve, compound and dispense medicinal products and counsel on the proper use and adverse effects of drugs and medicines following prescriptions issued by medical doctors and other health professionals. They contribute to researching, testing, preparing, prescribing and monitoring medicinal therapies for optimizing human health.',
											'sameAs' => array(
												'2',
												'22',
												'226'
											)
										),
										'2263' => array(
											'name' => 'Environmental and Occupational Health and Hygiene Professionals',
											'description' => 'Environmental and occupational health and hygiene professionals assess, plan and implement programmes to recognize, monitor and control environmental factors that can potentially affect human health, to ensure safe and healthy working conditions and to prevent disease or injury caused by chemical, physical, radiological and biological agents or ergonomic factors.',
											'sameAs' => array(
												'2',
												'22',
												'226'
											)
										),
										'2264' => array(
											'name' => 'Physiotherapists',
											'description' => 'Physiotherapists assess, plan and implement rehabilitative programmes that improve or restore human motor functions, maximize movement ability, relieve pain syndromes, and treat or prevent physical challenges associated with injuries, diseases and other impairments. They apply a broad range of physical therapies and techniques such as movement, ultrasound, heating, laser and other techniques. ',
											'sameAs' => array(
												'2',
												'22',
												'226'
											)
										),
										'2265' => array(
											'name' => 'Dieticians and Nutritionists',
											'description' => 'Dieticians and nutritionists assess, plan and implement programmes to enhance the impact of food and nutrition on human health.',
											'sameAs' => array(
												'2',
												'22',
												'226'
											)
										),
										'2266' => array(
											'name' => 'Audiologists and Speech Therapists',
											'description' => 'Audiologists and speech therapists evaluate, manage and treat physical disorders affecting human hearing, speech, communication and swallowing. They prescribe corrective devices or rehabilitative therapies for hearing loss, speech disorders and related sensory and neural problems, and provide counselling on hearing safety and communication performance.',
											'sameAs' => array(
												'2',
												'22',
												'226'
											)
										),
										'2267' => array(
											'name' => 'Optometrists and Ophthalmic Opticians',
											'description' => 'Optometrists and ophthalmic opticians provide diagnosis, management and treatment services for disorders of the eyes and visual system. They counsel on eye care and prescribe optical aids or other therapies for visual disturbance.',
											'sameAs' => array(
												'2',
												'22',
												'226'
											)
										),
										'2269' => array(
											'name' => 'Health Professionals Not Elsewhere Classified ',
											'description' => 'This unit group covers health professionals not classified elsewhere in Sub-major Group 22: Health Professionals. For instance, the group includes occupations such as podiatrist, occupational therapist, recreational therapist, chiropractor, osteopath and other professionals providing diagnostic, preventive, curative and rehabilitative health services.',
											'sameAs' => array(
												'2',
												'22',
												'226'
											)
										)
									);

								// // (Optional) Expand ISCO-8 code to include list of ancestors
								// 
								// 	if ( $specialization_isco08_code ) {
								// 
								// 		$specialization_isco08_code = array_merge(
								// 			$specialization_isco08_code,
								// 			$isco08_values[$specialization_isco08_code[0]]['sameAs']
								// 		);
								// 
								// 		sort($specialization_isco08_code);
								// 
								// 	}

								// Format values

									$specialization_isco08 = array();

									if ( $specialization_isco08_code ) {

										foreach ( $specialization_isco08_code as $code ) {

											if ( $code ) {

												$code_schema = array(
													'@type' => 'CategoryCode',
													'codeValue' => $code, // ISCO-08 code value from Specialty item
													'description' => $isco08_values[$code]['description'] ?? '', // ISCO-08 description from Specialty item (called "Lead Statement" in "Draft ISCO-08 Group Definitions: Occupations in Health")
													'inCodeSet' => array(
														'@type' => 'CategoryCodeSet',
														'dateModified' => '2016',
														'name' => 'ISCO-08',
														'url' => 'https://www.ilo.org/public/english/bureau/stat/isco/isco08/'
													),
													'name' => $isco08_values[$code]['name'] ?? '', // ISCO-08 name from Specialty item
													'url' => 'https://www.ilo.org/public/english/bureau/stat/isco/docs/health.pdf'
												);

												// Clean up the array

													$code_schema = array_filter($code_schema);

												// Add to the list array

													if (  $code_schema ) {

														$specialization_isco08[] = $code_schema;

													}

											}

										}

									}

							// occupationalCategory Value Array

								$schema_provider_occupationalCategory = array_merge(
									( ( is_array($specialization_isco08) && array_is_list($specialization_isco08) ) ? $specialization_isco08 : array($specialization_isco08) ),
									( ( is_array($specialization_onetsoc) && array_is_list($specialization_onetsoc) ) ? $specialization_onetsoc : array($specialization_onetsoc) )
								);

								// If there is only one item, flatten the multi-dimensional array by one step

									if ( $schema_provider_occupationalCategory ) {

										uamswp_fad_flatten_multidimensional_array($schema_provider_occupationalCategory);

									}

							// Add to schema item

								if ( $schema_provider_occupationalCategory ) {

									$specialization_schema['occupationalCategory'] = $schema_provider_occupationalCategory;

								}

						// sameAs

							// Base array

								$specialization_sameAs = array();

							// Get Wikidata entry URL for occupation

								$specialization_sameAs_wikidata = get_field( 'clinical_specialization_wikidata_url_occupation', $specialization_term ) ?? '';

								if ( $specialization_sameAs_wikidata ) {

									$specialization_sameAs[] = $specialization_sameAs_wikidata;

								}

								// Clean up the array

									// If there is only one item, flatten the multi-dimensional array by one step
								
										uamswp_fad_flatten_multidimensional_array($specialization_sameAs);
							

							// Get sameAs repeater field value

								// $specialization_sameAs_array = get_field( 'schema_sameas', $specialization_term ) ?? array();
								// 
								// // Add each item to sameAs property values array
								// 
								// 	if ( $specialization_sameAs_array ) {
								// 
								// 		$specialization_sameAs = uamswp_fad_schema_sameas(
								// 			$specialization_sameAs_array, // sameAs repeater field
								// 			'schema_sameas_url', // sameAs item field name
								// 			$specialization_sameAs // array // Optional // Pre-existing schema array for sameAs to which to add sameAs items
								// 		);
								// 
								// 	} else {
								// 
								// 	// If there is only one item, flatten the multi-dimensional array by one step
								// 
								// 		uamswp_fad_flatten_multidimensional_array($specialization_sameAs);
								// 
								// }

							// Add to schema item

								if ( $specialization_sameAs ) {

									$specialization_schema['sameAs'] = $specialization_sameAs;

								}

						// Clean up schema item array

							$specialization_schema = array_filter($specialization_schema);

						// Add @type

							if ( $specialization_schema ) {

								$specialization_schema = array( '@type' => 'EducationalOccupationalCredential' ) + $specialization_schema;

							}

						// Add to the list array

							if ( $specialization_schema ) {

								$hasOccupation_schema[] = $specialization_schema;

							}

					} // endif

				} // endforeach

			// Clean up schema list array

				if ( $hasOccupation_schema ) {

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($hasOccupation_schema);

				}

			return $hasOccupation_schema;

		}

	// Add data to an array defining schema data for ImageObject from thumbnails

		function uamswp_fad_schema_imageobject_thumbnails(
			string $url, // URL of entity with which the image is associated
			int $nesting_level, // Nesting level within the main schema
			string $single_aspect_ratio, // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9', 'full')
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

// Generate schema arrays of ontology page types

	// Providers (MedicalWebPage; MedicalBusiness; Person)

		function uamswp_fad_schema_provider(
			array $repeater, // List of IDs of the provider items
			string $page_url, // Page URL
			int $nesting_level = 1, // Nesting level within the main schema
			int $MedicalWebPage_i = 1, // Iteration counter for provider-as-MedicalWebPage
			int $MedicalBusiness_i = 1, // Iteration counter for provider-as-MedicalBusiness
			int $Person_i = 1, // Iteration counter for provider-as-Person
			array $provider_fields = array(), // Pre-existing field values array so duplicate calls can be avoided
			array $MedicalWebPage_list = array(), // Pre-existing list array for provider-as-MedicalWebPage to which to add additional items
			array $MedicalBusiness_list = array(), // Pre-existing list array for provider-as-MedicalBusiness to which to add additional items
			array $Person_list = array(), // Pre-existing list array for provider-as-Person to which to add additional items
			array $provider_list = array() // Pre-existing list array for both provider-as-MedicalBusiness and provider-as-Person to which to add additional items
		) {

			// Common property values

				include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

				// UAMS organization values

					include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/uams.php' );

			if ( !empty($repeater) ) {

				// Person and MedicalBusiness Subtype Properties Map

					/*

					Listing the properties valid for each schema type.

					*/

					$provider_properties_map = array(
						'Thing' => array(
							'Thing' => true,
							'CreativeWork' => false,
							'WebPage' => false,
							'Organization' => false,
							'Place' => false,
							'LocalBusiness' => false,
							'MedicalBusiness' => false,
							'MedicalOrganization' => false,
							'properties' => array(
								'additionalType',
								'alternateName',
								'description',
								'disambiguatingDescription',
								'identifier',
								'image',
								'mainEntityOfPage',
								'name',
								'potentialAction',
								'sameAs',
								'subjectOf',
								'url'
							)
						),
						'CreativeWork' => array(
							'Thing' => true,
							'CreativeWork' => true,
							'WebPage' => false,
							'Organization' => false,
							'Place' => false,
							'LocalBusiness' => false,
							'MedicalBusiness' => false,
							'MedicalOrganization' => false,
							'properties' => array(
								'about',
								'abstract',
								'accessMode',
								'accessModeSufficient',
								'accessibilityAPI',
								'accessibilityControl',
								'accessibilityFeature',
								'accessibilityHazard',
								'accessibilitySummary',
								'accountablePerson',
								'acquireLicensePage',
								'aggregateRating',
								'alternativeHeadline',
								'archivedAt',
								'assesses',
								'associatedMedia',
								'audience',
								'audio',
								'author',
								'award',
								'character',
								'citation',
								'comment',
								'commentCount',
								'conditionsOfAccess',
								'contentLocation',
								'contentRating',
								'contentReferenceTime',
								'contributor',
								'copyrightHolder',
								'copyrightNotice',
								'copyrightYear',
								'correction',
								'countryOfOrigin',
								'creativeWorkStatus',
								'creator',
								'creditText',
								'dateCreated',
								'dateModified',
								'datePublished',
								'discussionUrl',
								'editEIDR',
								'editor',
								'educationalAlignment',
								'educationalLevel',
								'educationalUse',
								'encoding',
								'encodingFormat',
								'exampleOfWork',
								'expires',
								'fileFormat',
								'funder',
								'funding',
								'genre',
								'hasPart',
								'headline',
								'inLanguage',
								'interactionStatistic',
								'interactivityType',
								'interpretedAsClaim',
								'isAccessibleForFree',
								'isBasedOn',
								'isBasedOnUrl',
								'isFamilyFriendly',
								'isPartOf',
								'keywords',
								'learningResourceType',
								'license',
								'locationCreated',
								'mainEntity',
								'maintainer',
								'material',
								'materialExtent',
								'mentions',
								'offers',
								'pattern',
								'position',
								'producer',
								'provider',
								'publication',
								'publisher',
								'publisherImprint',
								'publishingPrinciples',
								'recordedAt',
								'releasedEvent',
								'review',
								'schemaVersion',
								'sdDatePublished',
								'sdLicense',
								'sdPublisher',
								'size',
								'sourceOrganization',
								'spatial',
								'spatialCoverage',
								'sponsor',
								'teaches',
								'temporal',
								'temporalCoverage',
								'text',
								'thumbnail',
								'thumbnailUrl',
								'timeRequired',
								'translationOfWork',
								'translator',
								'typicalAgeRange',
								'usageInfo',
								'version',
								'video',
								'workExample',
								'workTranslation'
							)
						),
						'WebPage' => array(
							'Thing' => true,
							'CreativeWork' => true,
							'WebPage' => true,
							'Organization' => false,
							'Place' => false,
							'LocalBusiness' => false,
							'MedicalBusiness' => false,
							'MedicalOrganization' => false,
							'properties' => array(
								'breadcrumb',
								'lastReviewed',
								'mainContentOfPage',
								'primaryImageOfPage',
								'relatedLink',
								'reviewedBy',
								'significantLink',
								'speakable',
								'specialty'
							)
						),
						'MedicalWebPage' => array(
							'Thing' => true,
							'CreativeWork' => true,
							'WebPage' => true,
							'Organization' => false,
							'Place' => false,
							'LocalBusiness' => false,
							'MedicalBusiness' => false,
							'MedicalOrganization' => false,
							'properties' => array(
								'medicalAudience'
							)
						),
						'Organization' => array(
							'Thing' => true,
							'CreativeWork' => false,
							'WebPage' => false,
							'Organization' => true,
							'Place' => false,
							'LocalBusiness' => false,
							'MedicalBusiness' => false,
							'MedicalOrganization' => false,
							'properties' => array(
								'actionableFeedbackPolicy',
								'address',
								'aggregateRating',
								'alumni',
								'areaServed',
								'award',
								'brand',
								'contactPoint',
								'correctionsPolicy',
								'department',
								'dissolutionDate',
								'diversityPolicy',
								'diversityStaffingReport',
								'duns',
								'email',
								'employee',
								'ethicsPolicy',
								'event',
								'events',
								'faxNumber',
								'founder',
								'foundingDate',
								'foundingLocation',
								'funder',
								'funding',
								'globalLocationNumber',
								'hasCredential',
								'hasMerchantReturnPolicy',
								'hasOfferCatalog',
								'hasPOS',
								'hasProductReturnPolicy',
								'interactionStatistic',
								'isicV4',
								'iso6523Code',
								'keywords',
								'knowsAbout',
								'knowsLanguage',
								'legalName',
								'leiCode',
								'location',
								'logo',
								'makesOffer',
								'member',
								'memberOf',
								'naics',
								'nonprofitStatus',
								'numberOfEmployees',
								'ownershipFundingInfo',
								'owns',
								'parentOrganization',
								'publishingPrinciples',
								'review',
								'seeks',
								'serviceArea',
								'slogan',
								'sponsor',
								'subOrganization',
								'taxID',
								'telephone',
								'unnamedSourcesPolicy',
								'vatID'
							)
						),
						'Place' => array(
							'Thing' => true,
							'CreativeWork' => false,
							'WebPage' => false,
							'Organization' => false,
							'Place' => true,
							'LocalBusiness' => false,
							'MedicalBusiness' => false,
							'MedicalOrganization' => false,
							'properties' => array(
								'additionalProperty',
								'address',
								'aggregateRating',
								'amenityFeature',
								'branchCode',
								'containedInPlace',
								'containsPlace',
								'event',
								'events',
								'faxNumber',
								'geo',
								'geoContains',
								'geoCoveredBy',
								'geoCovers',
								'geoCrosses',
								'geoDisjoint',
								'geoEquals',
								'geoIntersects',
								'geoOverlaps',
								'geoTouches',
								'geoWithin',
								'globalLocationNumber',
								'hasDriveThroughService',
								'hasMap',
								'isAccessibleForFree',
								'isicV4',
								'keywords',
								'latitude',
								'logo',
								'longitude',
								'map',
								'maps',
								'maximumAttendeeCapacity',
								'openingHoursSpecification',
								'photo',
								'publicAccess',
								'review',
								'slogan',
								'smokingAllowed',
								'specialOpeningHoursSpecification',
								'telephone',
								'tourBookingPage'
							)
						),
						'LocalBusiness' => array(
							'Thing' => true,
							'CreativeWork' => false,
							'WebPage' => false,
							'Organization' => true,
							'Place' => true,
							'LocalBusiness' => true,
							'MedicalBusiness' => false,
							'MedicalOrganization' => false,
							'properties' => array(
								'currenciesAccepted',
								'openingHours',
								'paymentAccepted',
								'priceRange'
							)
						),
						'MedicalBusiness' => array(
							'Thing' => true,
							'CreativeWork' => false,
							'WebPage' => false,
							'Organization' => true,
							'Place' => true,
							'LocalBusiness' => true,
							'MedicalBusiness' => true,
							'MedicalOrganization' => false,
							'properties' => array()
						),
						'MedicalOrganization' => array(
							'Thing' => true,
							'CreativeWork' => false,
							'WebPage' => false,
							'Organization' => true,
							'Place' => false,
							'LocalBusiness' => false,
							'MedicalBusiness' => false,
							'MedicalOrganization' => true,
							'properties' => array(
								'healthPlanNetworkId',
								'isAcceptingNewPatients',
								'medicalSpecialty'
							)
						),
						'Physician' => array(
							'Thing' => true,
							'CreativeWork' => false,
							'WebPage' => false,
							'Organization' => true,
							'Place' => true,
							'LocalBusiness' => true,
							'MedicalBusiness' => true,
							'MedicalOrganization' => true,
							'properties' => array(
								'availableService',
								'hospitalAffiliation',
								'medicalSpecialty'
							),
							'degrees' => array(
								'M.D.',
								'D.O.'
							)
						),
						'Dentist' => array(
							'Thing' => true,
							'CreativeWork' => false,
							'WebPage' => false,
							'Organization' => true,
							'Place' => true,
							'LocalBusiness' => true,
							'MedicalBusiness' => true,
							'MedicalOrganization' => true,
							'properties' => array(),
							'degrees' => array(
								'D.D.S.',
								'D.M.D.'
							)
						),
						'Optician' => array(
							'Thing' => true,
							'CreativeWork' => false,
							'WebPage' => false,
							'Organization' => true,
							'Place' => true,
							'LocalBusiness' => true,
							'MedicalBusiness' => true,
							'MedicalOrganization' => false,
							'properties' => array(),
							'degrees' => array()
						),
						'Person' => array(
							'Thing' => true,
							'CreativeWork' => false,
							'WebPage' => false,
							'Organization' => false,
							'Place' => false,
							'LocalBusiness' => false,
							'MedicalBusiness' => false,
							'MedicalOrganization' => false,
							'properties' => array(
								'additionalName',
								'address',
								'affiliation',
								'alumniOf',
								'award',
								'awards',
								'birthDate',
								'birthPlace',
								'brand',
								'callSign',
								'children',
								'colleague',
								'colleagues',
								'contactPoint',
								'contactPoints',
								'deathDate',
								'deathPlace',
								'duns',
								'email',
								'familyName',
								'faxNumber',
								'follows',
								'funder',
								'funding',
								'gender',
								'givenName',
								'globalLocationNumber',
								'hasCredential',
								'hasOccupation',
								'hasOfferCatalog',
								'hasPOS',
								'height',
								'homeLocation',
								'honorificPrefix',
								'honorificSuffix',
								'interactionStatistic',
								'isicV4',
								'jobTitle',
								'knows',
								'knowsAbout',
								'knowsLanguage',
								'makesOffer',
								'memberOf',
								'naics',
								'nationality',
								'netWorth',
								'owns',
								'parent',
								'parents',
								'performerIn',
								'publishingPrinciples',
								'relatedTo',
								'seeks',
								'sibling',
								'siblings',
								'sponsor',
								'spouse',
								'taxID',
								'telephone',
								'vatID',
								'weight',
								'workLocation',
								'worksFor'
							),
							'degrees' => array()
						)
					);

					// Merge common properties into each type's properties

						foreach ( $provider_properties_map as &$provider_properties_map_item ) {

							// Thing properties

								if (
									$provider_properties_map_item != 'Thing'
									&&
									$provider_properties_map_item['Thing']
								) {

									$provider_properties_map_item['properties'] = array_merge(
										$provider_properties_map_item['properties'],
										$provider_properties_map['Thing']['properties']
									);
									$provider_properties_map_item['properties'] = array_unique($provider_properties_map_item['properties']);
									$provider_properties_map_item['properties'] = array_values($provider_properties_map_item['properties']);

								}

							// CreativeWork properties

								if (
									$provider_properties_map_item != 'CreativeWork'
									&&
									$provider_properties_map_item['CreativeWork']
								) {

									$provider_properties_map_item['properties'] = array_merge(
										$provider_properties_map_item['properties'],
										$provider_properties_map['CreativeWork']['properties']
									);
									$provider_properties_map_item['properties'] = array_unique($provider_properties_map_item['properties']);
									$provider_properties_map_item['properties'] = array_values($provider_properties_map_item['properties']);

								}

							// WebPage properties

								if (
									$provider_properties_map_item != 'WebPage'
									&&
									$provider_properties_map_item['WebPage']
								) {

									$provider_properties_map_item['properties'] = array_merge(
										$provider_properties_map_item['properties'],
										$provider_properties_map['WebPage']['properties']
									);
									$provider_properties_map_item['properties'] = array_unique($provider_properties_map_item['properties']);
									$provider_properties_map_item['properties'] = array_values($provider_properties_map_item['properties']);

								}

							// Organization properties

								if (
									$provider_properties_map_item != 'Organization'
									&&
									$provider_properties_map_item['Organization']
								) {

									$provider_properties_map_item['properties'] = array_merge(
										$provider_properties_map_item['properties'],
										$provider_properties_map['Organization']['properties']
									);
									$provider_properties_map_item['properties'] = array_unique($provider_properties_map_item['properties']);
									$provider_properties_map_item['properties'] = array_values($provider_properties_map_item['properties']);

								}

							// Place properties

								if (
									$provider_properties_map_item != 'Place'
									&&
									$provider_properties_map_item['Place']
								) {

									$provider_properties_map_item['properties'] = array_merge(
										$provider_properties_map_item['properties'],
										$provider_properties_map['Place']['properties']
									);
									$provider_properties_map_item['properties'] = array_unique($provider_properties_map_item['properties']);
									$provider_properties_map_item['properties'] = array_values($provider_properties_map_item['properties']);

								}

							// LocalBusiness properties

								if (
									$provider_properties_map_item != 'LocalBusiness'
									&&
									$provider_properties_map_item['LocalBusiness']
								) {

									$provider_properties_map_item['properties'] = array_merge(
										$provider_properties_map_item['properties'],
										$provider_properties_map['LocalBusiness']['properties']
									);
									$provider_properties_map_item['properties'] = array_unique($provider_properties_map_item['properties']);
									$provider_properties_map_item['properties'] = array_values($provider_properties_map_item['properties']);

								}

							// MedicalBusiness properties

								if (
									$provider_properties_map_item != 'MedicalBusiness'
									&&
									$provider_properties_map_item['MedicalBusiness']
								) {

									$provider_properties_map_item['properties'] = array_merge(
										$provider_properties_map_item['properties'],
										$provider_properties_map['MedicalBusiness']['properties']
									);
									$provider_properties_map_item['properties'] = array_unique($provider_properties_map_item['properties']);
									$provider_properties_map_item['properties'] = array_values($provider_properties_map_item['properties']);

								}

							// MedicalOrganization properties

								if (
									$provider_properties_map_item != 'MedicalOrganization'
									&&
									$provider_properties_map_item['MedicalOrganization']
								) {

									$provider_properties_map_item['properties'] = array_merge(
										$provider_properties_map_item['properties'],
										$provider_properties_map['MedicalOrganization']['properties']
									);
									$provider_properties_map_item['properties'] = array_unique($provider_properties_map_item['properties']);
									$provider_properties_map_item['properties'] = array_values($provider_properties_map_item['properties']);

								}

						}

				// MedicalBusiness additionalType MedicalSpecialty values

					/*

					Listing the MedicalSpecialty enumeration members that are also 
					subtypes of the MedicalBusiness type.

					*/

					$provider_additionalType_MedicalSpecialty = array(
						'https://schema.org/CommunityHealth/',
						'https://schema.org/Dermatology/',
						'https://schema.org/DietNutrition/',
						'https://schema.org/Emergency/',
						'https://schema.org/Geriatric/',
						'https://schema.org/Gynecologic/',
						'https://schema.org/Midwifery/',
						'https://schema.org/Nursing/',
						'https://schema.org/Obstetric/',
						'https://schema.org/Oncologic/',
						'https://schema.org/Optometric/',
						'https://schema.org/Otolaryngologic/',
						'https://schema.org/Pediatric/',
						'https://schema.org/Physiotherapy/',
						'https://schema.org/PlasticSurgery/',
						'https://schema.org/Podiatric/',
						'https://schema.org/PrimaryCare/',
						'https://schema.org/Psychiatric/',
						'https://schema.org/PublicHealth/'
					);

				// Loop through each provider to add values

					foreach ( $repeater as $provider ) {

						// Retrieve the value of the item transient

							uamswp_fad_get_transient(
								'item_' . $provider, // Required // String added to transient name for disambiguation.
								$provider_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
								__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
							);

						if (
							!empty( $provider_item )
							&&
							(
								(
									isset($provider_item['MedicalWebPage'])
									&&
									!empty($provider_item['MedicalWebPage'])
								)
								||
								(
									isset($provider_item['MedicalBusiness'])
									&&
									!empty($provider_item['MedicalBusiness'])
								)
								||
								(
									isset($provider_item['Person'])
									&&
									!empty($provider_item['Person'])
								)
							)
						) {

							/* 
							 * The transient exists.
							 * Return the variable.
							 */

							// Add to lists of providers

								// Add to list of MedicalWebPage items

									if (
										isset($provider_item['MedicalWebPage'])
										&&
										!empty($provider_item['MedicalWebPage'])
									) {

										$MedicalWebPage_list[] = $provider_item['MedicalWebPage'];

									}

								// Add to list of MedicalBusiness items

									if (
										isset($provider_item['MedicalBusiness'])
										&&
										!empty($provider_item['MedicalBusiness'])
									) {

										$MedicalBusiness_list[] = $provider_item['MedicalBusiness'];

									}

								// Add to list of Person items

									if (
										isset($provider_item['Person'])
										&&
										!empty($provider_item['Person'])
									) {

										$Person_list[] = $provider_item['Person'];

									}

						} else {

							// If post is not published, skip to the next iteration

								if ( get_post_status($provider) != 'publish' ) {

									continue;

								}

							// Eliminate PHP errors / reset variables

								$provider_item = array(); // Base array
								$provider_item_MedicalWebPage = array(); // Base MedicalWebPage array
								$provider_item_MedicalBusiness = array(); // Base MedicalBusiness array
								$provider_item_Person = array(); // Base Person array
								$Dentist_degree_query = null;
								$MedicalBusiness_type = null;
								$Optician_degree_query = null;
								$Person_type = null;
								$Physician_degree_query = null;
								$provider_about = null;
								$provider_abstract = null;
								$provider_accessibilityAPI = null;
								$provider_accessibilityControl = null;
								$provider_accessibilityFeature = null;
								$provider_accessibilityHazard = null;
								$provider_accessibilitySummary = null;
								$provider_accessMode = null;
								$provider_accessModeSufficient = null;
								$provider_accountablePerson = null;
								$provider_acquireLicensePage = null;
								$provider_additionalName = null;
								$provider_additionalType = null;
								$provider_additionalType_clinical_specialization = null;
								$provider_affiliation = null;
								$provider_aggregateRating = null;
								$provider_aggregateRating_api = null;
								$provider_aggregateRating_description = null;
								$provider_aggregateRating_itemReviewed = null;
								$provider_aggregateRating_query = null;
								$provider_aggregateRating_ratingCount = null;
								$provider_aggregateRating_ratingValue = null;
								$provider_aggregateRating_reviewAspect = null;
								$provider_aggregateRating_reviewCount = null;
								$provider_alternateName = null;
								$provider_alternateName_additional = null;
								$provider_alternateName_additional_repeater = null;
								$provider_alternateName_variants = null;
								$provider_areaServed = null;
								$provider_associations = null;
								$provider_audience = null;
								$provider_author = null;
								$provider_availableService = null;
								$provider_award = null;
								$provider_brand = null;
								$provider_breadcrumb = null;
								$provider_cid = null;
								$provider_clinical_specialization = null;
								$provider_clinical_specialization_name = null;
								$provider_clinical_specialization_term = null;
								$provider_containedInPlace = null;
								$provider_contributor = null;
								$provider_copyrightHolder = null;
								$provider_copyrightNotice = null;
								$provider_copyrightYear = null;
								$provider_countryOfOrigin = null;
								$provider_creativeWorkStatus = null;
								$provider_creator = null;
								$provider_creditText = null;
								$provider_currenciesAccepted = null;
								$provider_current_fpage = null;
								$provider_dateCreated = null;
								$provider_dateModified = null;
								$provider_datePublished = null;
								$provider_degree_array = null;
								$provider_degrees = null;
								$provider_description = null;
								$provider_description_ref = null;
								$provider_description_text = null;
								$provider_duns = null;
								$provider_editor = null;
								$provider_familyName = null;
								$provider_fpage_query = null;
								$provider_generational_suffix = null;
								$provider_givenName = null;
								$provider_globalLocationNumber = null;
								$provider_has_parent = null;
								$provider_hasCredential = null;
								$provider_hasMap = null;
								$provider_hasMap_repeater = null;
								$provider_honorificPrefix = null;
								$provider_honorificSuffix = null;
								$provider_hospitalAffiliation = null;
								$provider_hospitalAffiliation_multiselect = null;
								$provider_identifier = null;
								$provider_image = null;
								$provider_image_general = null;
								$provider_image_id = null;
								$provider_image_wide_id = null;
								$provider_inLanguage = null;
								$provider_isAcceptingNewPatients = null;
								$provider_isAccessibleForFree = null;
								$provider_isFamilyFriendly = null;
								$provider_isicV4 = null;
								$provider_iso6523Code = null;
								$provider_isPartOf = null;
								$provider_jobTitle = null;
								$provider_keywords = null;
								$provider_knowsAbout = null;
								$provider_knowsLanguage = null;
								$provider_languages = null;
								$provider_lastReviewed = null;
								$provider_legalName = null;
								$provider_leiCode = null;
								$provider_location = null;
								$provider_location_array = null;
								$provider_mainContentOfPage = null;
								$provider_mainEntity = null;
								$provider_mainEntityOfPage = null;
								$provider_maintainer = null;
								$provider_makesOffer = null;
								$provider_medicalAudience = null;
								$provider_medicalSpecialty = null;
								$provider_medicalSpecialty_list = null;
								$provider_memberOf = null;
								$provider_mentions = null;
								$provider_naics = null;
								$provider_name = null;
								$provider_nickname = null;
								$provider_npi = null;
								$provider_offers = null;
								$provider_ontology_type = null;
								$provider_parentOrganization = null;
								$provider_paymentAccepted = null;
								$provider_photo = null;
								$provider_potentialAction = null;
								$provider_potentialAction = null;
								$provider_primaryImageOfPage = null;
								$provider_producer = null;
								$provider_provider = null;
								$provider_publisher = null;
								$provider_relatedLink = null;
								$provider_review = null;
								$provider_review = null;
								$provider_reviewedBy = null;
								$provider_sameAs = null;
								$provider_sameAs_repeater = null;
								$provider_significantLink = null;
								$provider_smokingAllowed = null;
								$provider_sourceOrganization = null;
								$provider_speakable = null;
								$provider_specialty = null;
								$provider_subjectOf = null;
								$provider_taxID = null;
								$provider_taxID_employer = null;
								$provider_taxID_taxpayer = null;
								$provider_thumbnailUrl = null;
								$provider_timeRequired = null;
								$provider_treatments = null;
								$provider_url = null;
								$provider_vatID = null;
								$provider_video = null;
								$schema_provider_hospitalAffiliation_ref = null;
								$schema_provider_MedicalBusiness_ref = null;
								$schema_provider_Person_ref = null;

							// Load variables from pre-existing field values array

								if ( $provider_fields[$provider] ) {

									foreach ( $provider_fields[$provider] as $key => $value ) {

										${$key} = $value; // Create a variable for each item in the array

									}

								}

							// Get ontology type

								if ( !isset($provider_ontology_type) ) {

									$provider_ontology_type = true;

								}

							// If the page is not an ontology type, skip to the next iteration

								if ( !$provider_ontology_type ) {

									continue;

								}

							// Fake subpage query and get fake subpage slug

								if (
									$provider_ontology_type
									&&
									$nesting_level == 0
								) {

									if ( !isset($provider_current_fpage) ) {

										$provider_current_fpage = get_query_var( 'fpage' ) ?? ''; // Fake subpage slug

									}

									if ( !isset($provider_fpage_query) ) {

										$provider_fpage_query = $provider_current_fpage ? true : false;

									}

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

									// Get values

										if ( !isset($provider_url) ) {

											$provider_url = get_permalink($provider);
											$provider_url = $provider_url ? user_trailingslashit( $provider_url ) : '';

										}

									// Add to item values

										if ( $provider_url ) {

											$provider_item_MedicalWebPage['url'] = $provider_url;
											$provider_item_MedicalBusiness['url'] = $provider_url;
											$provider_item_Person['url'] = $provider_url;

										}

								// @type

									// MedicalWebPage type

										// Get values

											$MedicalWebPage_type = 'MedicalWebPage';

										// Add to item values

											if ( $MedicalWebPage_type ) {

												$provider_item_MedicalWebPage['@type'] = $MedicalWebPage_type;

											}

									// MedicalBusiness Subtype

										// Get values

											if ( !isset($MedicalBusiness_type) ) {

												// Base value

													$MedicalBusiness_type = 'MedicalBusiness';

												// Get list of the provider's degrees

													if (
														!isset($provider_degree_array)
														||
														!isset($provider_degree_list)
													) {

														$provider_degree_array = array();
														$provider_degree_list = '';
														$provider_degree_list_i = 1;
														$provider_degree_count = 0;

														if ( !isset($provider_degrees) ) {

															$provider_degrees = get_field( 'physician_degree', $provider );

														}

														if ( $provider_degrees ) {

															$provider_degree_count = count($provider_degrees) ?? 0;

															foreach ( $provider_degrees as $item ) {

																$item_term = get_term( $item, 'degree' );
																$item_name = '';

																if ( is_object($item_term) ) {

																	$item_name = $item_term->name;

																	if ( $item_name ) {

																		$provider_degree_list .= $item_name;
																		$provider_degree_array[] = uamswp_attr_conversion($item_name);

																		if ( $provider_degree_count > $provider_degree_list_i ) {

																			$provider_degree_list .= ', ';

																		} // endif

																		$provider_degree_list_i++;

																	}
																	
																} // endif

															} // endforeach

														} // endif

														if ( $provider_degree_list ) {

															$provider_degree_list = uamswp_attr_conversion($provider_degree_list);

														} // endif

													}

												// Check the list of degrees against the Physician degrees

													if (
														$provider_properties_map['Physician']['degrees']
														&&
														$provider_degree_array
													) {

														$Physician_degree_query = !empty(
															array_intersect(
																$provider_properties_map['Physician']['degrees'],
																$provider_degree_array
															)
														);

														$MedicalBusiness_type = $Physician_degree_query ? 'Physician' : $MedicalBusiness_type;

													}

												// Check the list of degrees against the Dentist degrees

													if (
														$provider_properties_map['Dentist']['degrees']
														&&
														$provider_degree_array
													) {

														$Dentist_degree_query = !empty(
															array_intersect(
																$provider_properties_map['Dentist']['degrees'],
																$provider_degree_array
															)
														);

														$MedicalBusiness_type = $Dentist_degree_query ? 'Dentist' : $MedicalBusiness_type;

													}

												// Check the list of degrees against the Optician degrees

													if (
														$provider_properties_map['Optician']['degrees']
														&&
														$provider_degree_array
													) {

														$Optician_degree_query = !empty(
															array_intersect(
																$provider_properties_map['Optician']['degrees'],
																$provider_degree_array
															)
														);

														$MedicalBusiness_type = $Optician_degree_query ? 'Optician' : $MedicalBusiness_type;

													}

											}

										// Add to item values

											if ( $MedicalBusiness_type ) {

												$provider_item_MedicalBusiness['@type'] = $MedicalBusiness_type;

											}

									// Person type

										// Get values

											$Person_type = 'Person';

										// Add to item values

											if ( $Person_type ) {

												$provider_item_Person['@type'] = $Person_type;

											}

								// @id

									// MedicalWebPage

										// Get values

											$MedicalWebPage_id = $provider_url . '#' . $MedicalWebPage_type;
											// $MedicalWebPage_id .= $MedicalWebPage_i;
											// $MedicalWebPage_i++;

										// Add to item values

											if ( $MedicalWebPage_id ) {

												$provider_item_MedicalWebPage['@id'] = $MedicalWebPage_id;

											}

										// Define reference to the @id

											if ( $provider_item_MedicalWebPage['@id'] ) {

												$schema_provider_MedicalWebPage_ref = uamswp_fad_schema_node_references(array($provider_item_MedicalWebPage));
												uamswp_fad_flatten_multidimensional_array($schema_provider_MedicalWebPage_ref);

											}

									// MedicalBusiness

										// Get values

											$MedicalBusiness_id = $provider_url . '#' . $MedicalBusiness_type;
											// $MedicalBusiness_id .= $MedicalBusiness_i;
											// $MedicalBusiness_i++;

										// Add to item values

											if ( $MedicalBusiness_id ) {

												$provider_item_MedicalBusiness['@id'] = $MedicalBusiness_id;

											}

										// Define reference to the @id

											if ( $provider_item_MedicalBusiness['@id'] ) {

												$schema_provider_MedicalBusiness_ref = uamswp_fad_schema_node_references(array($provider_item_MedicalBusiness));
												uamswp_fad_flatten_multidimensional_array($schema_provider_MedicalBusiness_ref);

											}

									// Person

										// Get values

											$Person_id = $provider_url . '#' . $Person_type;
											// $Person_id .= $Person_i;
											// $Person_i++;

										// Add to item values

											if ( $Person_id ) {

												$provider_item_Person['@id'] = $Person_id;

											}

										// Define reference to the @id

											if ( $provider_item_Person['@id'] ) {

												$schema_provider_Person_ref = uamswp_fad_schema_node_references(array($provider_item_Person));
												uamswp_fad_flatten_multidimensional_array($schema_provider_Person_ref);

											}

								// names (common use and specific properties)

									if (
										array_intersect(
											$provider_properties_map[$MedicalWebPage_type]['properties'],
											array(
												'additionalName',
												'alternateName',
												'familyName',
												'givenName',
												'honorificPrefix',
												'honorificSuffix',
												'legalName',
												'name'
											)
										)
										||
										array_intersect(
											$provider_properties_map[$MedicalBusiness_type]['properties'],
											array(
												'additionalName',
												'alternateName',
												'familyName',
												'givenName',
												'honorificPrefix',
												'honorificSuffix',
												'legalName',
												'name'
											)
										)
										||
										array_intersect(
											$provider_properties_map[$Person_type]['properties'],
											array(
												'additionalName',
												'alternateName',
												'familyName',
												'givenName',
												'honorificPrefix',
												'honorificSuffix',
												'legalName',
												'name'
											)
										)
									) {

										// Get values for name parts

											// Degrees

												if (
													!isset($provider_degree_array)
													||
													!isset($provider_degree_list)
												) {

													$provider_degree_array = array();
													$provider_degree_list = '';
													$provider_degree_list_i = 1;

													if ( !isset($provider_degrees) ) {

														$provider_degrees = get_field( 'physician_degree', $provider );
														$provider_degree_count = $degrees ? count($degrees) : 0;

													}

													if ( $provider_degrees ) {

														foreach ( $provider_degrees as $item ) {

															$item_term = get_term( $item, 'degree' );

															if ( is_object($item_term) ) {

																$item_name = $item_term->name;
																$provider_degree_list .= $item_name;
																$provider_degree_array[] = uamswp_attr_conversion($item_name);

																if ( $provider_degree_count > $provider_degree_list_i ) {

																	$provider_degree_list .= ', ';

																} // endif

																$provider_degree_list_i++;

															} // endif

														} // endforeach

													} // endif

													if ( $provider_degree_list ) {

														$provider_degree_list = uamswp_attr_conversion($provider_degree_list);

													} // endif

												}

											// Prefix

												if ( !isset($provider_honorificPrefix) ) {

													// Define list of degrees or credentials need for "Dr." prefix (per UAMS Health clinical administration)

														$provider_honorificPrefix_degree_valid = array(
															'M.D.',
															'D.O.'
														);

													// Set the "Dr." prefix

														$provider_honorificPrefix = '';

														if ( in_array( $provider_honorificPrefix_degree_valid, $provider_degree_array, true ) ) {

															$provider_honorificPrefix = uamswp_attr_conversion('Dr.');

														}

												}

											// First name

												if ( !isset($provider_givenName) ) {

													$provider_givenName = get_field( 'physician_first_name', $provider );

												}

											// Middle name

												if ( !isset($provider_additionalName) ) {

													$provider_additionalName = get_field( 'physician_middle_name', $provider );

												}

											// Nickname

												if ( !isset($provider_nickname) ) {

													$provider_nickname = '';

												}

											// Last name

												if ( !isset($provider_familyName) ) {

													$provider_familyName = get_field( 'physician_last_name', $provider );

												}

											// Generational suffix

												if ( !isset($provider_generational_suffix) ) {

													$provider_generational_suffix = get_field( 'physician_pedigree', $provider );

												}

										// givenName

											/* 
											 * Given name. In the U.S., the first name of a Person.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											// Add to item values

												// MedicalWebPage

													if (
														in_array(
															'givenName',
															$provider_properties_map[$MedicalWebPage_type]['properties']
														)
														&&
														$provider_givenName
													) {

														$provider_item_MedicalWebPage['givenName'] = $provider_givenName;

													}

												// MedicalBusiness

													if (
														in_array(
															'givenName',
															$provider_properties_map[$MedicalBusiness_type]['properties']
														)
														&&
														$provider_givenName
													) {

														$provider_item_MedicalBusiness['givenName'] = $provider_givenName;

													}

												// Person

													if (
														in_array(
															'givenName',
															$provider_properties_map[$Person_type]['properties']
														)
														&&
														$provider_givenName
													) {

														$provider_item_Person['givenName'] = $provider_givenName;

													}

										// additionalName

											/* 
											 * An additional name for a Person, can be used for a middle name.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											// Add to item values

												// MedicalWebPage

													if (
														in_array(
															'additionalName',
															$provider_properties_map[$MedicalWebPage_type]['properties']
														)
														&&
														$provider_additionalName
													) {

														$provider_item_MedicalWebPage['additionalName'] = $provider_additionalName;

													}

												// MedicalBusiness

													if (
														in_array(
															'additionalName',
															$provider_properties_map[$MedicalBusiness_type]['properties']
														)
														&&
														$provider_additionalName
													) {

														$provider_item_MedicalBusiness['additionalName'] = $provider_additionalName;

													}

												// Person

													if (
														in_array(
															'additionalName',
															$provider_properties_map[$Person_type]['properties']
														)
														&&
														$provider_additionalName
													) {

														$provider_item_Person['additionalName'] = $provider_additionalName;

													}

										// familyName

											/* 
											 * Family name. In the U.S., the last name of a Person.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											// Add to item values

												// MedicalWebPage

													if (
														in_array(
															'familyName',
															$provider_properties_map[$MedicalWebPage_type]['properties']
														)
														&&
														$provider_familyName
													) {

														$provider_item_MedicalWebPage['familyName'] = $provider_familyName;

													}

												// MedicalBusiness

													if (
														in_array(
															'familyName',
															$provider_properties_map[$MedicalBusiness_type]['properties']
														)
														&&
														$provider_familyName
													) {

														$provider_item_MedicalBusiness['familyName'] = $provider_familyName;

													}

												// Person

													if (
														in_array(
															'familyName',
															$provider_properties_map[$Person_type]['properties']
														)
														&&
														$provider_familyName
													) {

														$provider_item_Person['familyName'] = $provider_familyName;

													}

										// legalName

											/* 
											 * The official name of the organization (e.g., the registered company name).
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											// Get values

												if ( !isset($provider_legalName) ) {

													$provider_legalName = '';

												}

											// Add to item values

												// MedicalWebPage

													if (
														in_array(
															'legalName',
															$provider_properties_map[$MedicalWebPage_type]['properties']
														)
														&&
														$provider_legalName
													) {

														$provider_item_MedicalWebPage['legalName'] = $provider_legalName;

													}

												// MedicalBusiness

													if (
														in_array(
															'legalName',
															$provider_properties_map[$MedicalBusiness_type]['properties']
														)
														&&
														$provider_legalName
													) {

														$provider_item_MedicalBusiness['legalName'] = $provider_legalName;

													}

												// Person

													if (
														in_array(
															'legalName',
															$provider_properties_map[$Person_type]['properties']
														)
														&&
														$provider_legalName
													) {

														$provider_item_Person['legalName'] = $provider_legalName;

													}

										// honorificPrefix

											/* 
											 * An honorific prefix preceding a Person's name such as Dr/Mrs/Mr.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											// Add to item values

												// MedicalWebPage

													if (
														in_array(
															'honorificPrefix',
															$provider_properties_map[$MedicalWebPage_type]['properties']
														)
														&&
														$provider_honorificPrefix
														&&
														$nesting_level == 0
													) {

														$provider_item_MedicalWebPage['honorificPrefix'] = $provider_honorificPrefix;

													}

												// MedicalBusiness

													if (
														in_array(
															'honorificPrefix',
															$provider_properties_map[$MedicalBusiness_type]['properties']
														)
														&&
														$provider_honorificPrefix
														&&
														$nesting_level == 0
													) {

														$provider_item_MedicalBusiness['honorificPrefix'] = $provider_honorificPrefix;

													}

												// Person

													if (
														in_array(
															'honorificPrefix',
															$provider_properties_map[$Person_type]['properties']
														)
														&&
														$provider_honorificPrefix
														&&
														$nesting_level == 0
													) {

														$provider_item_Person['honorificPrefix'] = $provider_honorificPrefix;

													}

										// honorificSuffix

											/* 
											 * An honorific suffix following a Person's name such as M.D./PhD/MSCSW.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											// Get values

												$provider_honorificSuffix = $provider_degree_list;

											// Add to item values

												// MedicalWebPage

													if (
														in_array(
															'honorificSuffix',
															$provider_properties_map[$MedicalWebPage_type]['properties']
														)
														&&
														$provider_honorificSuffix
													) {

														$provider_item_MedicalWebPage['honorificSuffix'] = $provider_honorificSuffix;

													}

												// MedicalBusiness

													if (
														in_array(
															'honorificSuffix',
															$provider_properties_map[$MedicalBusiness_type]['properties']
														)
														&&
														$provider_honorificSuffix
													) {

														$provider_item_MedicalBusiness['honorificSuffix'] = $provider_honorificSuffix;

													}

												// Person

													if (
														in_array(
															'honorificSuffix',
															$provider_properties_map[$Person_type]['properties']
														)
														&&
														$provider_honorificSuffix
													) {

														$provider_item_Person['honorificSuffix'] = $provider_honorificSuffix;

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

												if ( !isset($provider_name) ) {

													$provider_name_parts = array();

													if ( $provider_givenName ) {

														$provider_name_parts[] = $provider_givenName;

													}

													if ( $provider_additionalName ) {

														$provider_name_parts[] = $provider_additionalName;

													}

													if ( $provider_nickname ) {

														$provider_name_parts[] = '\'' . $provider_nickname . '\'';

													}

													if ( $provider_familyName ) {

														$provider_name_parts[] = $provider_familyName;

													}

													if ( $provider_generational_suffix ) {

														$provider_name_parts[] = $provider_generational_suffix;

													}

													$provider_name = implode(
														' ',
														$provider_name_parts
													);

													if ( $provider_degree_list ) {

														$provider_name .= ', ' . $provider_degree_list;

													}

												}

											// Add to item values

												// MedicalWebPage

													if (
														in_array(
															'name',
															$provider_properties_map[$MedicalWebPage_type]['properties']
														)
														&&
														$provider_name
													) {

														$provider_item_MedicalWebPage['name'] = $provider_name;

													}

												// MedicalBusiness

													if (
														in_array(
															'name',
															$provider_properties_map[$MedicalBusiness_type]['properties']
														)
														&&
														$provider_name
													) {

														$provider_item_MedicalBusiness['name'] = $provider_name;

													}

												// Person

													if (
														in_array(
															'name',
															$provider_properties_map[$Person_type]['properties']
														)
														&&
														$provider_name
													) {

														$provider_item_Person['name'] = $provider_name;

													}

										// alternateName

											/* 
											 * An alias for the item.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

												// Get values

													// Base array

														$provider_alternateName = array();

													// Name variations

														// First Middle "Nickname" Last

															$provider_alternateName[] = '';

														// First Middle Last

															$provider_alternateName[] = '';

														// First "Nickname" Last

															$provider_alternateName[] = '';

														// First Last

															$provider_alternateName[] = '';

														// Middle Last

															$provider_alternateName[] = '';

														// Middle "Nickname" Last

															$provider_alternateName[] = '';

														// Nickname Last

															$provider_alternateName[] = '';

														// First M. "Nickname" Last

															$provider_alternateName[] = '';

														// First M. Last

															$provider_alternateName[] = '';

														// F. Middle "Nickname" Last

															$provider_alternateName[] = '';

														// F. Middle Last

															$provider_alternateName[] = '';

														// F. M. "Nickname" Last

															$provider_alternateName[] = '';

														// F. M. Last

															$provider_alternateName[] = '';

													// Additional alternate names

														// Get values

															// Get the alternateName repeater field value

																if ( !isset($provider_alternateName_additional_repeater) ) {

																	$provider_alternateName_additional_repeater = array();

																}

															// Get the item values

																if ( $provider_alternateName_additional_repeater ) {

																	$provider_alternateName_additional = uamswp_fad_schema_alternatename(
																		$provider_alternateName_additional_repeater, // alternateName repeater field
																		'alternate_text' // alternateName item field name
																	);

																}

														// Merge values

															if ( $provider_alternateName_additional ) {

																$provider_alternateName = $provider_alternateName + $provider_alternateName_additional;

															}

													// Create more variants using prefix and degrees

														if ( $provider_alternateName ) {

															$provider_alternateName_variants = array();

															foreach ( $provider_alternateName as $item ) {

																// Add prefix

																	if ( $provider_honorificPrefix ) {

																		$provider_alternateName_variants[] = $provider_honorificPrefix . ' ' . $item;

																	}

																// Add degrees

																	if ( $provider_degree_list ) {

																		$provider_alternateName_variants[] = $item . ', ' . $provider_degree_list;

																	}

															}

															if ( $provider_alternateName_variants ) {

																$provider_alternateName = $provider_alternateName + $provider_alternateName_variants;

															}

														}

													// Clean up values

														// Remove display name from list

															if (
																$provider_alternateName
																&&
																array_search( $provider_name, $provider_alternateName, true ) !== false
															) {
																unset($provider_alternateName[array_search( $provider_name, $provider_alternateName, true )]);
															}

														$provider_alternateName = array_filter($provider_alternateName);
														$provider_alternateName = array_unique($provider_alternateName);
														$provider_alternateName = array_values($provider_alternateName);

												// Add to item values

													// MedicalWebPage

														if (
															in_array(
																'alternateName',
																$provider_properties_map[$MedicalWebPage_type]['properties']
															)
															&&
															$provider_alternateName
															&&
															$nesting_level == 0
														) {

															$provider_item_MedicalWebPage['alternateName'] = $provider_alternateName;

														}

													// MedicalBusiness

														if (
															in_array(
																'alternateName',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_alternateName
															&&
															$nesting_level == 0
														) {

															$provider_item_MedicalBusiness['alternateName'] = $provider_alternateName;

														}

													// Person

														if (
															in_array(
																'alternateName',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_alternateName
															&&
															$nesting_level == 0
														) {

															$provider_item_Person['alternateName'] = $provider_alternateName;

														}

									}

								// about

									/* 
									 * The subject matter of the content.
									 * 
									 * Inverse-property: subjectOf
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Thing
									 */

									 if (
										(
											in_array(
												'about',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'about',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'about',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( $schema_provider_MedicalBusiness_ref ) {

												$provider_about = $schema_provider_MedicalBusiness_ref;

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'about',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_about
												) {

													$provider_item_MedicalWebPage['about'] = $provider_about;

												}

											// MedicalBusiness

												if (
													in_array(
														'about',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_about
												) {

													$provider_item_MedicalBusiness['about'] = $provider_about;

												}

											// Person

												if (
													in_array(
														'about',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_about
												) {

													$provider_item_Person['about'] = $provider_about;

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

									 if (
										(
											in_array(
												'abstract',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'abstract',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'abstract',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_abstract) ) {

												$provider_abstract = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'abstract',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_abstract
												) {

													$provider_item_MedicalWebPage['abstract'] = $provider_abstract;

												}

											// MedicalBusiness

												if (
													in_array(
														'abstract',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_abstract
												) {

													$provider_item_MedicalBusiness['abstract'] = $provider_abstract;

												}

											// Person

												if (
													in_array(
														'abstract',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_abstract
												) {

													$provider_item_Person['abstract'] = $provider_abstract;

												}

									}

								// accessMode

									/* 
									 * The human sensory perceptual system or cognitive faculty through which a person 
									 * may process or perceive information. Values should be drawn from the approved 
									 * vocabulary.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									 if (
										(
											in_array(
												'accessMode',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'accessMode',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'accessMode',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_accessMode) ) {

												$provider_accessMode = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'accessMode',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_accessMode
												) {

													$provider_item_MedicalWebPage['accessMode'] = $provider_accessMode;

												}

											// MedicalBusiness

												if (
													in_array(
														'accessMode',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_accessMode
												) {

													$provider_item_MedicalBusiness['accessMode'] = $provider_accessMode;

												}

											// Person

												if (
													in_array(
														'accessMode',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_accessMode
												) {

													$provider_item_Person['accessMode'] = $provider_accessMode;

												}

									}

								// accessModeSufficient

									/* 
									 * A list of single or combined accessModes that are sufficient to understand all 
									 * the intellectual content of a resource. Values should be drawn from the 
									 * approved vocabulary.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - ItemList
									 */

									if (
										(
											in_array(
												'accessModeSufficient',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'accessModeSufficient',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'accessModeSufficient',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_accessModeSufficient) ) {

												$provider_accessModeSufficient = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'accessModeSufficient',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_accessModeSufficient
												) {

													$provider_item_MedicalWebPage['accessModeSufficient'] = $provider_accessModeSufficient;

												}

											// MedicalBusiness

												if (
													in_array(
														'accessModeSufficient',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_accessModeSufficient
												) {

													$provider_item_MedicalBusiness['accessModeSufficient'] = $provider_accessModeSufficient;

												}

											// Person

												if (
													in_array(
														'accessModeSufficient',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_accessModeSufficient
												) {

													$provider_item_Person['accessModeSufficient'] = $provider_accessModeSufficient;

												}

									}

								// accessibilityAPI

									/* 
									 * Indicates that the resource is compatible with the referenced accessibility 
									 * API. Values should be drawn from the approved vocabulary 
									 * (https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityAPI-vocabulary).
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										(
											in_array(
												'accessibilityAPI',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'accessibilityAPI',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'accessibilityAPI',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_accessibilityAPI) ) {

												$provider_accessibilityAPI = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'accessibilityAPI',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_accessibilityAPI
												) {

													$provider_item_MedicalWebPage['accessibilityAPI'] = $provider_accessibilityAPI;

												}

											// MedicalBusiness

												if (
													in_array(
														'accessibilityAPI',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_accessibilityAPI
												) {

													$provider_item_MedicalBusiness['accessibilityAPI'] = $provider_accessibilityAPI;

												}

											// Person

												if (
													in_array(
														'accessibilityAPI',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_accessibilityAPI
												) {

													$provider_item_Person['accessibilityAPI'] = $provider_accessibilityAPI;

												}

									}

								// accessibilityControl

									/* 
									 * Identifies input methods that are sufficient to fully control the described 
									 * resource. Values should be drawn from the approved vocabulary 
									 * (https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityControl-vocabulary).
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										(
											in_array(
												'accessibilityControl',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'accessibilityControl',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'accessibilityControl',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_accessibilityControl) ) {

												$provider_accessibilityControl = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'accessibilityControl',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_accessibilityControl
												) {

													$provider_item_MedicalWebPage['accessibilityControl'] = $provider_accessibilityControl;

												}

											// MedicalBusiness

												if (
													in_array(
														'accessibilityControl',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_accessibilityControl
												) {

													$provider_item_MedicalBusiness['accessibilityControl'] = $provider_accessibilityControl;

												}

											// Person

												if (
													in_array(
														'accessibilityControl',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_accessibilityControl
												) {

													$provider_item_Person['accessibilityControl'] = $provider_accessibilityControl;

												}

									}

								// accessibilityFeature

									/* 
									 * Content features of the resource, such as accessible media, alternatives and 
									 * supported enhancements for accessibility. Values should be drawn from the 
									 * approved vocabulary 
									 * (https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityFeature-vocabulary).
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										(
											in_array(
												'accessibilityFeature',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'accessibilityFeature',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'accessibilityFeature',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_accessibilityFeature) ) {

												$provider_accessibilityFeature = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'accessibilityFeature',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_accessibilityFeature
												) {

													$provider_item_MedicalWebPage['accessibilityFeature'] = $provider_accessibilityFeature;

												}

											// MedicalBusiness

												if (
													in_array(
														'accessibilityFeature',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_accessibilityFeature
												) {

													$provider_item_MedicalBusiness['accessibilityFeature'] = $provider_accessibilityFeature;

												}

											// Person

												if (
													in_array(
														'accessibilityFeature',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_accessibilityFeature
												) {

													$provider_item_Person['accessibilityFeature'] = $provider_accessibilityFeature;

												}

									}

								// accessibilityHazard

									/* 
									 * A characteristic of the described resource that is physiologically dangerous to 
									 * some users. Related to WCAG 2.0 guideline 2.3. Values should be drawn from the 
									 * approved vocabulary.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										(
											in_array(
												'accessibilityHazard',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'accessibilityHazard',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'accessibilityHazard',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_accessibilityHazard) ) {

												$provider_accessibilityHazard = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'accessibilityHazard',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_accessibilityHazard
												) {

													$provider_item_MedicalWebPage['accessibilityHazard'] = $provider_accessibilityHazard;

												}

											// MedicalBusiness

												if (
													in_array(
														'accessibilityHazard',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_accessibilityHazard
												) {

													$provider_item_MedicalBusiness['accessibilityHazard'] = $provider_accessibilityHazard;

												}

											// Person

												if (
													in_array(
														'accessibilityHazard',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_accessibilityHazard
												) {

													$provider_item_Person['accessibilityHazard'] = $provider_accessibilityHazard;

												}

									}

								// accessibilitySummary

									/* 
									 * A human-readable summary of specific accessibility features or deficiencies, 
									 * consistent with the other accessibility metadata but expressing subtleties such 
									 * as "short descriptions are present but long descriptions will be needed for 
									 * non-visual users" or "short descriptions are present and no long descriptions 
									 * are needed."
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										(
											in_array(
												'accessibilitySummary',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'accessibilitySummary',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'accessibilitySummary',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_accessibilitySummary) ) {

												$provider_accessibilitySummary = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'accessibilitySummary',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_accessibilitySummary
												) {

													$provider_item_MedicalWebPage['accessibilitySummary'] = $provider_accessibilitySummary;

												}

											// MedicalBusiness

												if (
													in_array(
														'accessibilitySummary',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_accessibilitySummary
												) {

													$provider_item_MedicalBusiness['accessibilitySummary'] = $provider_accessibilitySummary;

												}

											// Person

												if (
													in_array(
														'accessibilitySummary',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_accessibilitySummary
												) {

													$provider_item_Person['accessibilitySummary'] = $provider_accessibilitySummary;

												}

									}

								// accountablePerson

									/* 
									 * Specifies the Person that is legally accountable for the CreativeWork.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Person
									 */

									if (
										(
											in_array(
												'accountablePerson',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'accountablePerson',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'accountablePerson',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_accountablePerson) ) {

												$provider_accountablePerson = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'accountablePerson',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_accountablePerson
												) {

													$provider_item_MedicalWebPage['accountablePerson'] = $provider_accountablePerson;

												}

											// MedicalBusiness

												if (
													in_array(
														'accountablePerson',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_accountablePerson
												) {

													$provider_item_MedicalBusiness['accountablePerson'] = $provider_accountablePerson;

												}

											// Person

												if (
													in_array(
														'accountablePerson',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_accountablePerson
												) {

													$provider_item_Person['accountablePerson'] = $provider_accountablePerson;

												}

									}

								// acquireLicensePage

									/* 
									 * Indicates a page documenting how licenses can be purchased or otherwise 
									 * acquired, for the current item.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - CreativeWork
									 *     - URL
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										(
											in_array(
												'acquireLicensePage',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'acquireLicensePage',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'acquireLicensePage',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_acquireLicensePage) ) {

												$provider_acquireLicensePage = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'acquireLicensePage',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_acquireLicensePage
												) {

													$provider_item_MedicalWebPage['acquireLicensePage'] = $provider_acquireLicensePage;

												}

											// MedicalBusiness

												if (
													in_array(
														'acquireLicensePage',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_acquireLicensePage
												) {

													$provider_item_MedicalBusiness['acquireLicensePage'] = $provider_acquireLicensePage;

												}

											// Person

												if (
													in_array(
														'acquireLicensePage',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_acquireLicensePage
												) {

													$provider_item_Person['acquireLicensePage'] = $provider_acquireLicensePage;

												}

									}

								// medicalSpecialty

									/* 
									 * A medical specialty of the provider.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - MedicalSpecialty
									 */

									if (
										in_array(
											'medicalSpecialty',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
										||
										in_array(
											'medicalSpecialty',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
										||
										in_array(
											'medicalSpecialty',
											$provider_properties_map[$Person_type]['properties']
										)
									) {

										// Get MedicalSpecialty value(s) from associated Clinical Specialization item(s)

											if (
												!isset($provider_medicalSpecialty)
												||
												!isset($provider_medicalSpecialty_list)
											) {

												// Get Clinical Specialization value

													if ( !isset($provider_clinical_specialization) ) {

														$provider_clinical_specialization = get_field( 'physician_title', $provider );

													}

												// Get MedicalSpecialty from Clinical Specialization value

													// Simple list of MedicalSpecialty values

														$provider_medicalSpecialty_list = array();

													// Schema property values

														$provider_medicalSpecialty = uamswp_fad_schema_medicalSpecialty_specialization(
															$provider_clinical_specialization, // mixed // Required // Clinical Specialization value(s)
															$provider_medicalSpecialty_list // Optional // Array to populate with the list of MedicalSpecialty values
														);

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'medicalSpecialty',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_medicalSpecialty
												) {

													$provider_item_MedicalWebPage['medicalSpecialty'] = $provider_medicalSpecialty;

												}

											// MedicalBusiness

												if (
													in_array(
														'medicalSpecialty',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_medicalSpecialty
												) {

													$provider_item_MedicalBusiness['medicalSpecialty'] = $provider_medicalSpecialty;

												}

											// Person

												if (
													in_array(
														'medicalSpecialty',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_medicalSpecialty
												) {

													$provider_item_Person['medicalSpecialty'] = $provider_medicalSpecialty;

												}

									}

								// additionalType (MedicalWebPage)

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

									if (
										in_array(
											'additionalType',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									) {

										// Get values

											$provider_additionalType_MedicalWebPage = 'ProfilePage';

										// Clean up additionalType property values array

											if (
												$provider_additionalType_MedicalWebPage
												&&
												is_array($provider_additionalType_MedicalWebPage)
											) {

												// If there is only one item, flatten the multi-dimensional array by one step

													uamswp_fad_flatten_multidimensional_array($provider_additionalType_MedicalWebPage);

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'additionalType',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_additionalType_MedicalWebPage
												) {

													$provider_item_MedicalWebPage['additionalType'] = $provider_additionalType_MedicalWebPage;

												}

									}

								// additionalType (MedicalBusiness; Person)

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

									if (
										in_array(
											'additionalType',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
										||
										in_array(
											'additionalType',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
										||
										in_array(
											'additionalType',
											$provider_properties_map[$Person_type]['properties']
										)
									) {

										// Get values

											// Base property values array

												$provider_additionalType = array();

											// Get MedicalSpecialty values that match MedicalBusiness subtypes and add to property values

												// Get values

													if (
														!isset($provider_medicalSpecialty)
														||
														!isset($provider_medicalSpecialty_list)
													) {

														// Get Clinical Specialization value

															if ( !isset($provider_clinical_specialization) ) {

																$provider_clinical_specialization = get_field( 'physician_title', $provider );

															}

														// Get MedicalSpecialty from Clinical Specialization value

															// Simple list of MedicalSpecialty values

																$provider_medicalSpecialty_list = array();

															// Schema property values

																$provider_medicalSpecialty = uamswp_fad_schema_medicalSpecialty_specialization(
																	$provider_clinical_specialization, // mixed // Required // Clinical Specialization value(s)
																	$provider_medicalSpecialty_list // Optional // Array to populate with the list of MedicalSpecialty values
																);

													}

												// Merge into property values array

													if ( $provider_medicalSpecialty_list ) {

														$provider_additionalType = array_merge(
															$provider_additionalType,
															array_intersect(
																$provider_additionalType_MedicalSpecialty,
																( is_array($provider_medicalSpecialty_list) ? $provider_medicalSpecialty_list : array($provider_medicalSpecialty_list) )
															)
														);

													}

											// Get Wikidata item URL for the occupation from associated Clinical Specialization items

												// Get values

													if ( !isset($provider_additionalType_clinical_specialization) ) {

														// Get Clinical Specialization value

															if ( !isset($provider_clinical_specialization_term) ) {

																if ( !isset($provider_clinical_specialization) ) {

																	$provider_clinical_specialization = get_field( 'physician_title', $provider );

																}

																if ( $provider_clinical_specialization ) {

																	$provider_clinical_specialization_term = get_term( $provider_clinical_specialization, 'clinical_title' ) ?? '';

																}

																// Get Wikidata Item URL for the Occupation field value

																	$provider_additionalType_clinical_specialization = '';

																	if ( is_object($provider_clinical_specialization_term) ) {

																		$provider_additionalType_clinical_specialization = get_field( 'clinical_specialization_wikidata_url_occupation', $item_term ) ?? '';

																	}

															}

													}

												// Merge into property values array

													if (
														$provider_additionalType
														&&
														$provider_additionalType_clinical_specialization
													) {

														$provider_additionalType = array_merge(
															$provider_additionalType,
															( is_array($provider_additionalType_clinical_specialization) ? $provider_additionalType_clinical_specialization : array($provider_additionalType_clinical_specialization) )
														);

													} elseif ( $provider_additionalType_clinical_specialization ) {

														$provider_additionalType = $provider_additionalType_clinical_specialization;

													}

										// Clean up additionalType property values array

											if (
												$provider_additionalType
												&&
												is_array($provider_additionalType)
											) {

												// If there is only one item, flatten the multi-dimensional array by one step

													uamswp_fad_flatten_multidimensional_array($provider_additionalType);

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'additionalType',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_additionalType
												) {

													$provider_item_MedicalWebPage['additionalType'] = $provider_additionalType;

												}

											// MedicalBusiness

												if (
													in_array(
														'additionalType',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_additionalType
												) {

													$provider_item_MedicalBusiness['additionalType'] = $provider_additionalType;

												}

											// Person

												if (
													in_array(
														'additionalType',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_additionalType
												) {

													$provider_item_Person['additionalType'] = $provider_additionalType;

												}

									}

								// aggregateRating

									/* 
									 * The overall rating, based on a collection of reviews or ratings, of the item.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - AggregateRating
									 */

									if (
										(
											in_array(
												'aggregateRating',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'aggregateRating',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'aggregateRating',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										if ( !isset($provider_aggregateRating) ) {

											// Get values

												// Query for whether there are valid ratings ($rating_valid)

													if ( !isset($provider_aggregateRating_query) ) {

														// Get NPI value ($npi)

															if ( !isset($provider_npi) ) {

																$provider_npi = get_field( 'physician_npi', $provider );

															}

														// Get ratings data from NRC JSON API and decode

															if ( !isset($provider_aggregateRating_api) ) {

																$provider_aggregateRating_api = json_decode( wp_nrc_cached_api($provider_npi) );

															}

														// Check if ratings data is valid

															if ( !empty($provider_aggregateRating_api) ) {

																$provider_aggregateRating_query = $provider_aggregateRating_api->valid ?? false;
																$provider_aggregateRating_query = $provider_aggregateRating_query ? true : false;

															} else {

																$provider_aggregateRating_query = false;

															}

													}

												// Get values from ratings data

													if (
														$provider_aggregateRating_query
														&&
														$provider_aggregateRating_api
													) {

														// ratingCount

															if ( !isset($provider_aggregateRating_ratingCount) ) {

																$provider_aggregateRating_ratingCount = $provider_aggregateRating_api->profile->reviewcount;

															}

														// ratingValue ($avg_rating)

															if ( !isset($provider_aggregateRating_ratingValue) ) {

																$provider_aggregateRating_ratingValue = $provider_aggregateRating_api->profile->averageRatingStr;

															}

														// reviewCount ($comment_count)

															if ( !isset($provider_aggregateRating_reviewCount) ) {

																$provider_aggregateRating_reviewCount = $provider_aggregateRating_api->profile->bodycount;

															}

													}

												// description

													/*

														Get description of the rating/review concept from Patient Experience.

													*/

													if ( !isset($provider_aggregateRating_description) ) {

														$provider_aggregateRating_description = '';

													}

												// itemReviewed

													if ( !isset($provider_aggregateRating_itemReviewed) ) {

														$provider_aggregateRating_itemReviewed = array(
															( is_array($schema_provider_MedicalBusiness_ref) ? $schema_provider_MedicalBusiness_ref : array($schema_provider_MedicalBusiness_ref) ),
															( is_array($schema_provider_Person_ref) ? $schema_provider_Person_ref : array($schema_provider_Person_ref) )
														);

													}

												// reviewAspect

													/*

														Get info from Patient Experience about which facets of the provider is rated/reviewed.

													*/

													if ( !isset($provider_aggregateRating_reviewAspect) ) {

														$provider_aggregateRating_reviewAspect = '';

													}

											// Format values

												$provider_aggregateRating = array_filter(
													array(
														'description' => $provider_aggregateRating_description,
														'itemReviewed' => $provider_aggregateRating_itemReviewed,
														'ratingCount' => $provider_aggregateRating_ratingCount,
														'ratingValue' => $provider_aggregateRating_ratingValue,
														'reviewAspect' => $provider_aggregateRating_reviewAspect,
														'reviewCount' => $provider_aggregateRating_reviewCount
													)
												);

												// Add @type

													if ( $provider_aggregateRating ) {

														$provider_aggregateRating = array( '@type' => 'AggregateRating' ) + $provider_aggregateRating;

													}

										}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'aggregateRating',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_aggregateRating
												) {

													$provider_item_MedicalWebPage['aggregateRating'] = $provider_aggregateRating;

												}

											// MedicalBusiness

												if (
													in_array(
														'aggregateRating',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_aggregateRating
												) {

													$provider_item_MedicalBusiness['aggregateRating'] = $provider_aggregateRating;

												}

											// Person

												if (
													in_array(
														'aggregateRating',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_aggregateRating
												) {

													$provider_item_Person['aggregateRating'] = $provider_aggregateRating;

												}

									}

								// alumniOf

									/* 
									 * An organization that the person is an alumni of.
									 * 
									 * Inverse property: alumni
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - EducationalOrganization
									 *     - Organization
									 */

									if (
										(
											in_array(
												'alumniOf',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'alumniOf',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'alumniOf',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_alumniOf) ) {

												$provider_alumniOf = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'alumniOf',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_alumniOf
												) {

													$provider_item_MedicalWebPage['alumniOf'] = $provider_alumniOf;

												}

											// MedicalBusiness

												if (
													in_array(
														'alumniOf',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_alumniOf
												) {

													$provider_item_MedicalBusiness['alumniOf'] = $provider_alumniOf;

												}

											// Person

												if (
													in_array(
														'alumniOf',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_alumniOf
												) {

													$provider_item_Person['alumniOf'] = $provider_alumniOf;

												}

									}

								// areaServed

									/* 
									 * The geographic area where a service or offered item is provided.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - AdministrativeArea
									 *     - GeoShape
									 *     - Place
									 *     - Text
									 */

									if (
										(
											in_array(
												'areaServed',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'areaServed',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'areaServed',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_areaServed) ) {

												$provider_areaServed = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'areaServed',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_areaServed
												) {

													$provider_item_MedicalWebPage['areaServed'] = $provider_areaServed;

												}

											// MedicalBusiness

												if (
													in_array(
														'areaServed',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_areaServed
												) {

													$provider_item_MedicalBusiness['areaServed'] = $provider_areaServed;

												}

											// Person

												if (
													in_array(
														'areaServed',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_areaServed
												) {

													$provider_item_Person['areaServed'] = $provider_areaServed;

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
										(
											in_array(
												'audience',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'audience',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'audience',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_audience) ) {

												$provider_audience = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'audience',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_audience
												) {

													$provider_item_MedicalWebPage['audience'] = $provider_audience;

												}

											// MedicalBusiness

												if (
													in_array(
														'audience',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_audience
												) {

													$provider_item_MedicalBusiness['audience'] = $provider_audience;

												}

											// Person

												if (
													in_array(
														'audience',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_audience
												) {

													$provider_item_Person['audience'] = $provider_audience;

												}

									}

								// author

									/* 
									 * The author of this content or rating. Please note that author is special in 
									 * that HTML 5 provides a special mechanism for indicating authorship via the rel 
									 * tag. That is equivalent to this and may be used interchangeably.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 *     - Person
									 */

									 if (
										(
											in_array(
												'author',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'author',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'author',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_author) ) {

												$provider_author = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'author',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_author
												) {

													$provider_item_MedicalWebPage['author'] = $provider_author;

												}

											// MedicalBusiness

												if (
													in_array(
														'author',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_author
												) {

													$provider_item_MedicalBusiness['author'] = $provider_author;

												}

											// Person

												if (
													in_array(
														'author',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_author
												) {

													$provider_item_Person['author'] = $provider_author;

												}

									}

								// availableService

									/* 
									 * A medical service available from this provider.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - MedicalProcedure
									 *     - MedicalTest
									 */

									if (
										(
											in_array(
												'availableService',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'availableService',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'availableService',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get related treatments

											if ( !isset($provider_treatments) ) {

												$provider_treatments = get_field( 'physician_treatments_cpt', $provider ) ?: array();

											}

										// Format values

											if ( !isset($provider_availableService) ) {

												if ( $provider_treatments ) {

													$provider_availableService = uamswp_fad_schema_service(
														$provider_treatments, // List of IDs of the service items
														$provider_url, // Page URL
														( $nesting_level + 1 ), // Nesting level within the main schema
														'Service' // Fragment identifier
													) ?? array();

												}

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'availableService',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_availableService
												) {

													$provider_item_MedicalWebPage['availableService'] = $provider_availableService;

												}

											// MedicalBusiness

												if (
													in_array(
														'availableService',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_availableService
												) {

													$provider_item_MedicalBusiness['availableService'] = $provider_availableService;

												}

											// Person

												if (
													in_array(
														'availableService',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_availableService
												) {

													$provider_item_Person['availableService'] = $provider_availableService;

												}

									}

								// award

									/* 
									 * An award won by or for this item.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										(
											in_array(
												'award',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'award',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'award',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_award) ) {

												$provider_award = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'award',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_award
												) {

													$provider_item_MedicalWebPage['award'] = $provider_award;

												}

											// MedicalBusiness

												if (
													in_array(
														'award',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_award
												) {

													$provider_item_MedicalBusiness['award'] = $provider_award;

												}

											// Person

												if (
													in_array(
														'award',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_award
												) {

													$provider_item_Person['award'] = $provider_award;

												}

									}

								// Associated Locations (common and specific)

									// List of properties that reference locations (i.e., 'Place')

										$provider_location_common = array(
											'containedInPlace',
											'location',
											'workLocation'
										);

									if (
										array_intersect(
											$provider_properties_map[$MedicalWebPage_type]['properties'],
											$provider_location_common
										)
										||
										array_intersect(
											$provider_properties_map[$MedicalBusiness_type]['properties'],
											$provider_location_common
										)
										||
										array_intersect(
											$provider_properties_map[$Person_type]['properties'],
											$provider_location_common
										)
									) {

										// location (common 'Place')

											// Get values

												if ( !isset($provider_location_array) ) {

													$provider_location_array = get_field( 'physician_locations', $provider ) ?? array(); // array

												}

												if ( $provider_location_array ) {

													$provider_location = uamswp_fad_schema_location(
														$provider_location_array, // List of IDs of the location items
														$provider_url, // Page URL
														$nesting_level + 1 // Nesting level within the main schema
													);

												}

												if ( $provider_location ) {

													$provider_location_ref = uamswp_fad_schema_node_references($provider_location);

												}

										// location (specific property)

											/* 
											 * The location of, for example, where an event is happening, where an 
											 * organization is located, or where an action takes place.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Place
											 *     - PostalAddress
											 *     - Text
											 *     - VirtualLocation
											 */

											if (
												(
													in_array(
														'location',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													||
													in_array(
														'location',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'location',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												// Add to item values

													// MedicalWebPage

														if (
															in_array(
																'location',
																$provider_properties_map[$MedicalWebPage_type]['properties']
															)
															&&
															$provider_location
														) {

															$provider_item_MedicalWebPage['location'] = $provider_location;

														}

													// MedicalBusiness

														if (
															in_array(
																'location',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_location
														) {

															$provider_item_MedicalBusiness['location'] = $provider_location;

														}

													// Person

														if (
															in_array(
																'location',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_location
														) {

															$provider_item_Person['location'] = $provider_location;

														}

											}

										// workLocation

											/* 
											 * A contact location for a person's place of work.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - ContactPoint
											 *     - Place
											 */

											if (
												(
													in_array(
														'workLocation',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													||
													in_array(
														'workLocation',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'workLocation',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												// Get values

													if (
														$provider_location
														&&
														isset($provider_location_ref)
													) {

														// @id references

															$provider_workLocation = $provider_location_ref;

													} else {

														// Full values

															$provider_workLocation = $provider_location;

													}

												// Add to item values

													// MedicalWebPage

														if (
															in_array(
																'workLocation',
																$provider_properties_map[$MedicalWebPage_type]['properties']
															)
															&&
															$provider_workLocation
														) {

															$provider_item_MedicalWebPage['workLocation'] = $provider_workLocation;

														}

													// MedicalBusiness

														if (
															in_array(
																'workLocation',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_workLocation
														) {

															$provider_item_MedicalBusiness['workLocation'] = $provider_workLocation;

														}

													// Person

														if (
															in_array(
																'workLocation',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_workLocation
														) {

															$provider_item_Person['workLocation'] = $provider_workLocation;

														}

											}

										// containedInPlace

											/* 
											 * The basic containment relation between a place and one that contains it.
											 * expected to be one of these types:
											 * 
											 *     - Place
											 */

											if (
												(
													in_array(
														'containedInPlace',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													||
													in_array(
														'containedInPlace',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'containedInPlace',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												// Get values

													if (
														(
															$provider_location
															||
															$provider_workLocation
														)
														&&
														isset($provider_location_ref)
													) {

														// @id references

															$provider_workLocation = $provider_location_ref;

													} else {

														// Full values

															$provider_workLocation = $provider_location;

													}

												// Add to item values

													// MedicalWebPage

														if (
															in_array(
																'containedInPlace',
																$provider_properties_map[$MedicalWebPage_type]['properties']
															)
															&&
															$provider_containedInPlace
														) {

															$provider_item_MedicalWebPage['containedInPlace'] = $provider_containedInPlace;

														}

													// MedicalBusiness

														if (
															in_array(
																'containedInPlace',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_containedInPlace
														) {

															$provider_item_MedicalBusiness['containedInPlace'] = $provider_containedInPlace;

														}

													// Person

														if (
															in_array(
																'containedInPlace',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_containedInPlace
														) {

															$provider_item_Person['containedInPlace'] = $provider_containedInPlace;

														}

											}

									}

								// Associated Organizations (common and specific)

									// List of properties that reference organizations (i.e., 'Organization')

										$provider_organization_common = array(
											'brand',
											'hospitalAffiliation',
											'affiliation',
											'memberOf',
											'parentOrganization',
											'worksFor'
										);

									if (
										array_intersect(
											$provider_properties_map[$MedicalWebPage_type]['properties'],
											$provider_organization_common
										)
										||
										array_intersect(
											$provider_properties_map[$MedicalBusiness_type]['properties'],
											$provider_organization_common
										)
										||
										array_intersect(
											$provider_properties_map[$Person_type]['properties'],
											$provider_organization_common
										)
									) {

										// Common clinical 'Organization'

											// Base array

												$provider_organization_common = array();

												// UAMS Health

													if ( $schema_base_org_uams_health_ref ) {

														$provider_organization_common[] = $schema_base_org_uams_health_ref;

													}

										// Specific clinical 'Organization'

											// Get specific clinical 'Organization'

												// Base array

													$provider_organization_specific = array();

											// Define @id references to each top-level node in an array

												if ( $provider_organization_specific ) {

													$provider_organization_specific_ref = uamswp_fad_schema_node_references(
														$provider_organization_specific
													);

												}

										// brand

											/* 
											 * The brand(s) associated with a product or service, or the brand(s) maintained 
											 * by an organization or business person.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Brand
											 *     - Organization
											 */

											if (
												in_array(
													'brand',
													$provider_properties_map[$MedicalWebPage_type]['properties']
												)
												||
												in_array(
													'brand',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
												||
												in_array(
													'brand',
													$provider_properties_map[$Person_type]['properties']
												)
											) {

												// Specific 'Brand'

													// Base array

														$provider_brand = array();

													// Get values

														$provider_brand = array();

												// Merge common clinical 'Organization'

													if ( $provider_organization_common ) {

														$provider_brand = array_merge(
															( array_is_list($provider_brand) ? $provider_brand : array($provider_brand) ),
															( array_is_list($provider_organization_common) ? $provider_organization_common : array($provider_organization_common) )
														);

													}

												// Merge specific clinical 'Organization'

													if ( $provider_organization_specific ) {

														if (
															$provider_affiliation
															||
															$provider_memberOf
															||
															$provider_parentOrganization
															||
															$provider_worksFor
														) {

															// @id references

																$provider_brand = array_merge(
																	( array_is_list($provider_brand) ? $provider_brand : array($provider_brand) ),
																	( array_is_list($provider_organization_specific_ref) ? $provider_organization_specific_ref : array($provider_organization_specific_ref) )
																);

														} else {

															// Full values

																$provider_brand = array_merge(
																	( array_is_list($provider_brand) ? $provider_brand : array($provider_brand) ),
																	( array_is_list($provider_organization_specific) ? $provider_organization_specific : array($provider_organization_specific) )
																);

														}

													}

												// Clean up array

													$provider_brand = array_unique( $provider_brand, SORT_REGULAR );
													$provider_brand = array_values($provider_brand);

													// If there is only one item, flatten the multi-dimensional array by one step

														uamswp_fad_flatten_multidimensional_array($provider_brand);

												// Add to item values

													// MedicalWebPage

														if (
															in_array(
																'brand',
																$provider_properties_map[$MedicalWebPage_type]['properties']
															)
															&&
															$provider_brand
														) {

															$provider_item_MedicalWebPage['brand'] = $provider_brand;

														}

													// MedicalBusiness

														if (
															in_array(
																'brand',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_brand
														) {

															$provider_item_MedicalBusiness['brand'] = $provider_brand;

														}

													// Person

														if (
															in_array(
																'brand',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_brand
														) {

															$provider_item_Person['brand'] = $provider_brand;

														}

											}

										// hospitalAffiliation and affiliation

											if (
												(
													in_array(
														'hospitalAffiliation',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													||
													in_array(
														'hospitalAffiliation',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'hospitalAffiliation',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												||
												(
													in_array(
														'affiliation',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													||
													in_array(
														'affiliation',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'affiliation',
														$provider_properties_map[$Person_type]['properties']
													)
												)
											) {

												// Get hospital affiliation multi-select field values

													if ( !isset($provider_hospitalAffiliation_multiselect) ) {

														$provider_hospitalAffiliation_multiselect = get_field( 'physician_affiliation', $provider ) ?? '';

													}

													// Add each item to hospitalAffiliation and affiliation property values array

														// Define hospitalAffiliation value

															if ( $provider_hospitalAffiliation_multiselect ) {

																$provider_hospitalAffiliation = uamswp_fad_schema_hospital_affiliation(
																	$provider_hospitalAffiliation_multiselect, // array // Required // Hospital affiliation ID values
																	$provider_url, // string // Required // Page URL
																	$nesting_level, // int // Optional // Nesting level within the main schema
																	array() // array // Optional // Pre-existing list array for hospitalAffiliation to which to add additional items
																);

															}

														// Define reference to hospitalAffiliation IDs if both hospitalAffiliation and affiliation are valid properties

															if (
																$provider_hospitalAffiliation
																&&
																(
																	in_array(
																		'hospitalAffiliation',
																		$provider_properties_map[$MedicalWebPage_type]['properties']
																	)
																	||
																	in_array(
																		'hospitalAffiliation',
																		$provider_properties_map[$MedicalBusiness_type]['properties']
																	)
																	||
																	in_array(
																		'hospitalAffiliation',
																		$provider_properties_map[$Person_type]['properties']
																	)
																)
																&&
																(
																	in_array(
																		'affiliation',
																		$provider_properties_map[$MedicalWebPage_type]['properties']
																	)
																	||
																	in_array(
																		'affiliation',
																		$provider_properties_map[$MedicalBusiness_type]['properties']
																	)
																	||
																	in_array(
																		'affiliation',
																		$provider_properties_map[$Person_type]['properties']
																	)
																)
															) {

																$schema_provider_hospitalAffiliation_ref = uamswp_fad_schema_node_references(
																	$provider_hospitalAffiliation
																);

															}

														// Define affiliation value

															if ( $provider_hospitalAffiliation ) {

																$provider_affiliation = $schema_provider_hospitalAffiliation_ref ?: $provider_hospitalAffiliation;

															}

												// hospitalAffiliation

													/* 
													 * A hospital with which the physician or office is affiliated.
													 * 
													 * Values expected to be one of these types:
													 * 
													 *     - Hospital
													 */

													// Add to item values

														// MedicalWebPage

															if (
																in_array(
																	'hospitalAffiliation',
																	$provider_properties_map[$MedicalWebPage_type]['properties']
																)
																&&
																$provider_hospitalAffiliation
															) {

																$provider_item_MedicalWebPage['hospitalAffiliation'] = $provider_hospitalAffiliation;

															}

														// MedicalBusiness

															if (
																in_array(
																	'hospitalAffiliation',
																	$provider_properties_map[$MedicalBusiness_type]['properties']
																)
																&&
																$provider_hospitalAffiliation
															) {

																$provider_item_MedicalBusiness['hospitalAffiliation'] = $provider_hospitalAffiliation;

															}

														// Person

															if (
																in_array(
																	'hospitalAffiliation',
																	$provider_properties_map[$Person_type]['properties']
																)
																&&
																$provider_hospitalAffiliation
															) {

																$provider_item_Person['hospitalAffiliation'] = $provider_hospitalAffiliation;

															}

												// affiliation

													/* 
													 * An organization that this person is affiliated with. For example, a 
													 * school/university, a club, or a team.
													 * 
													 * Values expected to be one of these types:
													 * 
													 *     - Organization
													 */

													// Base array

														$provider_affiliation = $provider_affiliation ?: array();

													// Merge common clinical 'Organization'

														if ( $provider_organization_common ) {

															$provider_affiliation = array_merge(
																( array_is_list($provider_affiliation) ? $provider_affiliation : array($provider_affiliation) ),
																( array_is_list($provider_organization_common) ? $provider_organization_common : array($provider_organization_common) )
															);

														}

													// Merge specific clinical 'Organization'

														if ( $provider_organization_specific ) {

															if (
																$provider_brand
																||
																$provider_memberOf
																||
																$provider_parentOrganization
																||
																$provider_worksFor
															) {

																// @id references

																	$provider_affiliation = array_merge(
																		( array_is_list($provider_affiliation) ? $provider_affiliation : array($provider_affiliation) ),
																		( array_is_list($provider_organization_specific_ref) ? $provider_organization_specific_ref : array($provider_organization_specific_ref) )
																	);

															} else {

																// Full values

																	$provider_affiliation = array_merge(
																		( array_is_list($provider_affiliation) ? $provider_affiliation : array($provider_affiliation) ),
																		( array_is_list($provider_organization_specific) ? $provider_organization_specific : array($provider_organization_specific) )
																	);

															}

														}

													// Clean up array

														$provider_affiliation = array_unique( $provider_affiliation, SORT_REGULAR );
														$provider_affiliation = array_values($provider_affiliation);

														// If there is only one item, flatten the multi-dimensional array by one step

															uamswp_fad_flatten_multidimensional_array($provider_affiliation);

													// Add to item values

														// MedicalWebPage

															if (
																in_array(
																	'affiliation',
																	$provider_properties_map[$MedicalWebPage_type]['properties']
																)
																&&
																$provider_affiliation
															) {

																$provider_item_MedicalWebPage['affiliation'] = $provider_affiliation;

															}

														// MedicalBusiness

															if (
																in_array(
																	'affiliation',
																	$provider_properties_map[$MedicalBusiness_type]['properties']
																)
																&&
																$provider_affiliation
															) {

																$provider_item_MedicalBusiness['affiliation'] = $provider_affiliation;

															}

														// Person

															if (
																in_array(
																	'affiliation',
																	$provider_properties_map[$Person_type]['properties']
																)
																&&
																$provider_affiliation
															) {

																$provider_item_Person['affiliation'] = $provider_affiliation;

															}

											}

										// memberOf

											/* 
											 * An Organization (or ProgramMembership) to which this Person or Organization 
											 * belongs.
											 * 
											 * Inverse-property: member
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Organization
											 *     - ProgramMembership
											 */

											if (
												(
													in_array(
														'memberOf',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													||
													in_array(
														'memberOf',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'memberOf',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												// Get specific membership 'Organization'

													if ( !isset($provider_memberOf) ) {

														// Get health care professional associations input value

															if ( !isset($provider_associations) ) {

																$provider_associations = get_field( 'physician_associations', $provider ) ?? array();

															}

														// Format values

															$provider_memberOf = uamswp_fad_schema_associations(
																$provider_associations, // mixed // Required // Health care professional association ID values
															); // Add to schema fields

													}

												// Merge common clinical 'Organization'

													if ( $provider_organization_common ) {

														$provider_memberOf = array_merge(
															( array_is_list( is_array($provider_memberOf) ? $provider_memberOf : array($provider_memberOf) ) ? $provider_memberOf : array($provider_memberOf) ),
															( array_is_list($provider_organization_common) ? $provider_organization_common : array($provider_organization_common) )
														);

													}

												// Merge specific clinical 'Organization'

													if ( $provider_organization_specific ) {

														if (
															$provider_affiliation
															||
															$provider_brand
															||
															$provider_parentOrganization
															||
															$provider_worksFor
														) {

															// @id references

																$provider_memberOf = array_merge(
																	( array_is_list($provider_memberOf) ? $provider_memberOf : array($provider_memberOf) ),
																	( array_is_list($provider_organization_specific_ref) ? $provider_organization_specific_ref : array($provider_organization_specific_ref) )
																);

														} else {

															// Full values

																$provider_memberOf = array_merge(
																	( array_is_list($provider_memberOf) ? $provider_memberOf : array($provider_memberOf) ),
																	( array_is_list($provider_organization_specific) ? $provider_organization_specific : array($provider_organization_specific) )
																);

														}

													}

												// Clean up array

													$provider_memberOf = array_unique( $provider_memberOf, SORT_REGULAR );
													$provider_memberOf = array_values($provider_memberOf);

													// If there is only one item, flatten the multi-dimensional array by one step

														uamswp_fad_flatten_multidimensional_array($provider_memberOf);

												// Add to item values

													// MedicalWebPage

														if (
															in_array(
																'memberOf',
																$provider_properties_map[$MedicalWebPage_type]['properties']
															)
															&&
															$provider_memberOf
														) {

															$provider_item_MedicalWebPage['memberOf'] = $provider_memberOf;

														}

													// MedicalBusiness

														if (
															in_array(
																'memberOf',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_memberOf
														) {

															$provider_item_MedicalBusiness['memberOf'] = $provider_memberOf;

														}

													// Person

														if (
															in_array(
																'memberOf',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_memberOf
														) {

															$provider_item_Person['memberOf'] = $provider_memberOf;

														}

											}

										// parentOrganization

											/* 
											 * The larger organization that this organization is a subOrganization of, if any.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Organization
											 */

											if (
												in_array(
													'parentOrganization',
													$provider_properties_map[$MedicalWebPage_type]['properties']
												)
												||
												in_array(
													'parentOrganization',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
												||
												in_array(
													'parentOrganization',
													$provider_properties_map[$Person_type]['properties']
												)
											) {

												// Base array

													$provider_parentOrganization = array();

												// Merge common clinical 'Organization'

													if ( $provider_organization_common ) {

														$provider_parentOrganization = array_merge(
															( array_is_list($provider_parentOrganization) ? $provider_parentOrganization : array($provider_parentOrganization) ),
															( array_is_list($provider_organization_common) ? $provider_organization_common : array($provider_organization_common) )
														);

													}

												// Merge specific clinical 'Organization'

													if ( $provider_organization_specific ) {

														if (
															$provider_affiliation
															||
															$provider_brand
															||
															$provider_memberOf
															||
															$provider_worksFor
														) {

															// @id references

																$provider_parentOrganization = array_merge(
																	( array_is_list($provider_memberOf) ? $provider_memberOf : array($provider_memberOf) ),
																	( array_is_list($provider_organization_specific_ref) ? $provider_organization_specific_ref : array($provider_organization_specific_ref) )
																);

														} else {

															// Full values

																$provider_parentOrganization = array_merge(
																	( array_is_list($provider_memberOf) ? $provider_memberOf : array($provider_memberOf) ),
																	( array_is_list($provider_organization_specific) ? $provider_organization_specific : array($provider_organization_specific) )
																);

														}

													}

												// Clean up array

													$provider_parentOrganization = array_unique( $provider_parentOrganization, SORT_REGULAR );
													$provider_parentOrganization = array_values($provider_parentOrganization);

													// If there is only one item, flatten the multi-dimensional array by one step

														uamswp_fad_flatten_multidimensional_array($provider_parentOrganization);

												// Add to item values

													// MedicalWebPage

														if (
															in_array(
																'parentOrganization',
																$provider_properties_map[$MedicalWebPage_type]['properties']
															)
															&&
															$provider_parentOrganization
														) {

															$provider_item_MedicalWebPage['parentOrganization'] = $provider_parentOrganization;

														}

													// MedicalBusiness

														if (
															in_array(
																'parentOrganization',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_parentOrganization
														) {

															$provider_item_MedicalBusiness['parentOrganization'] = $provider_parentOrganization;

														}

													// Person

														if (
															in_array(
																'parentOrganization',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_parentOrganization
														) {

															$provider_item_Person['parentOrganization'] = $provider_parentOrganization;

														}

											}

										// worksFor

											/* 
											 * Organizations that the person works for.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Organization
											 */

											if (
												(
													in_array(
														'worksFor',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													||
													in_array(
														'worksFor',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'worksFor',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												// Base array

													$provider_worksFor = array();

												// Merge common clinical 'Organization'

													if ( $provider_organization_common ) {

														$provider_worksFor = array_merge(
															( array_is_list($provider_worksFor) ? $provider_worksFor : array($provider_worksFor) ),
															( array_is_list($provider_organization_common) ? $provider_organization_common : array($provider_organization_common) )
														);

													}

												// Merge specific clinical 'Organization'

													if ( $provider_organization_specific ) {

														if (
															$provider_affiliation
															||
															$provider_brand
															||
															$provider_memberOf
															||
															$provider_parentOrganization
														) {

															// @id references

																$provider_worksFor = array_merge(
																	( array_is_list($provider_memberOf) ? $provider_memberOf : array($provider_memberOf) ),
																	( array_is_list($provider_organization_specific_ref) ? $provider_organization_specific_ref : array($provider_organization_specific_ref) )
																);

														} else {

															// Full values

																$provider_worksFor = array_merge(
																	( array_is_list($provider_memberOf) ? $provider_memberOf : array($provider_memberOf) ),
																	( array_is_list($provider_organization_specific) ? $provider_organization_specific : array($provider_organization_specific) )
																);

														}

													}

												// Clean up array

													$provider_worksFor = array_unique( $provider_worksFor, SORT_REGULAR );
													$provider_worksFor = array_values($provider_worksFor);

													// If there is only one item, flatten the multi-dimensional array by one step

														uamswp_fad_flatten_multidimensional_array($provider_worksFor);

												// Add to item values

													// MedicalWebPage

														if (
															in_array(
																'workLocation',
																$provider_properties_map[$MedicalWebPage_type]['properties']
															)
															&&
															$provider_worksFor
														) {

															$provider_item_MedicalWebPage['workLocation'] = $provider_worksFor;

														}

													// MedicalBusiness

														if (
															in_array(
																'workLocation',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_worksFor
														) {

															$provider_item_MedicalBusiness['workLocation'] = $provider_worksFor;

														}

													// Person

														if (
															in_array(
																'worksFor',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_worksFor
														) {

															$provider_item_Person['worksFor'] = $provider_worksFor;

														}

											}

									}

								// breadcrumb

									/* 
									 * A set of links that can help a user understand and navigate a website hierarchy.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - BreadcrumbList
									 *     - Text
									 */

									 if (
										(
											in_array(
												'breadcrumb',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'breadcrumb',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'breadcrumb',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											// Base array

												$provider_breadcrumb = array();

											// @type

												$provider_breadcrumb['@type'] = 'BreadcrumbList';

											// @id

												$provider_breadcrumb['@id'] = $page_url . '#' . $provider_breadcrumb['@type'];

												// // Define 'BreadcrumbList' reference
												//
												// 	$schema_provider_BreadcrumbList_ref = uamswp_fad_schema_node_references( $provider_breadcrumb );

											// itemListElement

												if ( !isset($provider_archive_url) ) {

													$provider_archive_url = user_trailingslashit( get_post_type_archive_link('provider') );

												}

												if ( !isset($schema_provider_MedicalWebPage_ref) ) {

													if ( !isset($provider_url) ) {

														$provider_url = get_permalink($provider);
														$provider_url = $provider_url ? user_trailingslashit( $provider_url ) : '';

													}

													$schema_provider_MedicalWebPage_ref = $provider_url ? array( '@id' => $provider_url . '#MedicalWebPage' ) : array();
													uamswp_fad_flatten_multidimensional_array($schema_provider_MedicalWebPage_ref);

												}

												// Base array

													$provider_breadcrumb['itemListElement'] = array();

												// Position 1

													$provider_breadcrumb['itemListElement'][] = array(
														'@id' => $schema_base_org_uams_health_url . '#ListItem',
														'@type' => 'ListItem',
														'item' => array(
															'@type' => 'WebPage',
															'url' => $schema_base_org_uams_health_url,
															'name' => 'UAMS Health'
														),
														'nextItem' => array(
															'@id' => $provider_archive_url . '#ListItem'
														),
														'position' => 1
													);

												// Position 2

													if ( !isset($provider_plural_name_attr) ) {

														// Get system settings for provider labels
														include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

													}

													$provider_breadcrumb['itemListElement'][] = array(
														'@id' => $provider_archive_url . '#ListItem',
														'@type' => 'ListItem',
														'item' => array(
															'@type' => 'WebPage',
															'url' => $provider_archive_url,
															'name' => $provider_plural_name_attr
														),
														'nextItem' => array(
															'@id' => $provider_url . '#ListItem'
														),
														'position' => 2,
														'previousItem' => array(
															'@id' => $schema_base_org_uams_health_url . '#ListItem'
														)
													);

												// Position 3

													$provider_breadcrumb['itemListElement'][] = array(
														'@id' => $provider_url . '#ListItem',
														'@type' => 'ListItem',
														'item' => $schema_provider_MedicalWebPage_ref,
														'position' => 3,
														'previousItem' => array(
															'@id' => $provider_archive_url . '#ListItem'
														)
													);

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'breadcrumb',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_breadcrumb
												) {

													$provider_item_MedicalWebPage['breadcrumb'] = $provider_breadcrumb;

												}

											// MedicalBusiness

												if (
													in_array(
														'breadcrumb',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_breadcrumb
												) {

													$provider_item_MedicalBusiness['breadcrumb'] = $provider_breadcrumb;

												}

											// Person

												if (
													in_array(
														'breadcrumb',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_breadcrumb
												) {

													$provider_item_Person['breadcrumb'] = $provider_breadcrumb;

												}

									}

								// contributor

									/* 
									 * A secondary contributor to the CreativeWork or Event.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 *     - Person
									 */

									if (
										(
											in_array(
												'contributor',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'contributor',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'contributor',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_contributor) ) {

												$provider_contributor = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'contributor',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_contributor
												) {

													$provider_item_MedicalWebPage['contributor'] = $provider_contributor;

												}

											// MedicalBusiness

												if (
													in_array(
														'contributor',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_contributor
												) {

													$provider_item_MedicalBusiness['contributor'] = $provider_contributor;

												}

											// Person

												if (
													in_array(
														'contributor',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_contributor
												) {

													$provider_item_Person['contributor'] = $provider_contributor;

												}

									}

								// copyrightHolder

									/* 
									 * The party holding the legal copyright to the CreativeWork.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 *     - Person
									 */

									if (
										(
											in_array(
												'copyrightHolder',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'copyrightHolder',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'copyrightHolder',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_copyrightHolder) ) {

												$provider_copyrightHolder = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'copyrightHolder',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_copyrightHolder
												) {

													$provider_item_MedicalWebPage['copyrightHolder'] = $provider_copyrightHolder;

												}

											// MedicalBusiness

												if (
													in_array(
														'copyrightHolder',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_copyrightHolder
												) {

													$provider_item_MedicalBusiness['copyrightHolder'] = $provider_copyrightHolder;

												}

											// Person

												if (
													in_array(
														'copyrightHolder',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_copyrightHolder
												) {

													$provider_item_Person['copyrightHolder'] = $provider_copyrightHolder;

												}

									}

								// copyrightNotice

									/* 
									 * Text of a notice appropriate for describing the copyright aspects of this 
									 * Creative Work, ideally indicating the owner of the copyright for the Work.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										(
											in_array(
												'copyrightNotice',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'copyrightNotice',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'copyrightNotice',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_copyrightNotice) ) {

												$provider_copyrightNotice = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'copyrightNotice',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_copyrightNotice
												) {

													$provider_item_MedicalWebPage['copyrightNotice'] = $provider_copyrightNotice;

												}

											// MedicalBusiness

												if (
													in_array(
														'copyrightNotice',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_copyrightNotice
												) {

													$provider_item_MedicalBusiness['copyrightNotice'] = $provider_copyrightNotice;

												}

											// Person

												if (
													in_array(
														'copyrightNotice',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_copyrightNotice
												) {

													$provider_item_Person['copyrightNotice'] = $provider_copyrightNotice;

												}

									}

								// copyrightYear

									/* 
									 * The year during which the claimed copyright for the CreativeWork was first 
									 * asserted.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Number
									 */

									if (
										(
											in_array(
												'copyrightYear',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'copyrightYear',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'copyrightYear',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_copyrightYear) ) {

												$provider_copyrightYear = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'copyrightYear',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_copyrightYear
												) {

													$provider_item_MedicalWebPage['copyrightYear'] = $provider_copyrightYear;

												}

											// MedicalBusiness

												if (
													in_array(
														'copyrightYear',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_copyrightYear
												) {

													$provider_item_MedicalBusiness['copyrightYear'] = $provider_copyrightYear;

												}

											// Person

												if (
													in_array(
														'copyrightYear',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_copyrightYear
												) {

													$provider_item_Person['copyrightYear'] = $provider_copyrightYear;

												}

									}

								// countryOfOrigin

									/* 
									 * The country of origin of something, including products as well as creative 
									 * works such as movie and TV content.
									 * 
									 * In the case of TV and movie, this would be the country of the principle offices 
									 * of the production company or individual responsible for the movie. For other 
									 * kinds of CreativeWork it is difficult to provide fully general guidance, and 
									 * properties such as contentLocation and locationCreated may be more applicable.
									 * 
									 * In the case of products, the country of origin of the product. The exact 
									 * interpretation of this may vary by context and product type, and cannot be 
									 * fully enumerated here.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Country
									 */

									if (
										(
											in_array(
												'countryOfOrigin',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'countryOfOrigin',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'countryOfOrigin',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_countryOfOrigin) ) {

												$provider_countryOfOrigin = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'countryOfOrigin',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_countryOfOrigin
												) {

													$provider_item_MedicalWebPage['countryOfOrigin'] = $provider_countryOfOrigin;

												}

											// MedicalBusiness

												if (
													in_array(
														'countryOfOrigin',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_countryOfOrigin
												) {

													$provider_item_MedicalBusiness['countryOfOrigin'] = $provider_countryOfOrigin;

												}

											// Person

												if (
													in_array(
														'countryOfOrigin',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_countryOfOrigin
												) {

													$provider_item_Person['countryOfOrigin'] = $provider_countryOfOrigin;

												}

									}

								// creativeWorkStatus

									/* 
									 * The status of a creative work in terms of its stage in a lifecycle. Example 
									 * terms include Incomplete, Draft, Published, Obsolete. Some organizations define 
									 * a set of terms for the stages of their publication lifecycle.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - DefinedTerm
									 *     - Text
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										(
											in_array(
												'creativeWorkStatus',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'creativeWorkStatus',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'creativeWorkStatus',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_creativeWorkStatus) ) {

												$provider_creativeWorkStatus = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'creativeWorkStatus',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_creativeWorkStatus
												) {

													$provider_item_MedicalWebPage['creativeWorkStatus'] = $provider_creativeWorkStatus;

												}

											// MedicalBusiness

												if (
													in_array(
														'creativeWorkStatus',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_creativeWorkStatus
												) {

													$provider_item_MedicalBusiness['creativeWorkStatus'] = $provider_creativeWorkStatus;

												}

											// Person

												if (
													in_array(
														'creativeWorkStatus',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_creativeWorkStatus
												) {

													$provider_item_Person['creativeWorkStatus'] = $provider_creativeWorkStatus;

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
										(
											in_array(
												'creator',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'creator',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'creator',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											// Base array

												$provider_creator = array();

											// UAMS

												if ( $schema_base_org_uams_ref ) {

													$provider_creator[] = $schema_base_org_uams_ref;

												}

											// UAMS Health

												if ( $schema_base_org_uams_health_ref ) {

													$provider_creator[] = $schema_base_org_uams_health_ref;

												}

										// Format values

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array( $provider_creator );

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'creator',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_creator
												) {

													$provider_item_MedicalWebPage['creator'] = $provider_creator;

												}

											// MedicalBusiness

												if (
													in_array(
														'creator',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_creator
												) {

													$provider_item_MedicalBusiness['creator'] = $provider_creator;

												}

											// Person

												if (
													in_array(
														'creator',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_creator
												) {

													$provider_item_Person['creator'] = $provider_creator;

												}

									}

								// creditText

									/* 
									 * Text that can be used to credit person(s) and/or organization(s) associated 
									 * with a published Creative Work.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										(
											in_array(
												'creditText',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'creditText',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'creditText',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( $schema_base_org_uams_name ) {

												$provider_creditText = $schema_base_org_uams_name;

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'creditText',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_creditText
												) {

													$provider_item_MedicalWebPage['creditText'] = $provider_creditText;

												}

											// MedicalBusiness

												if (
													in_array(
														'creditText',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_creditText
												) {

													$provider_item_MedicalBusiness['creditText'] = $provider_creditText;

												}

											// Person

												if (
													in_array(
														'creditText',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_creditText
												) {

													$provider_item_Person['creditText'] = $provider_creditText;

												}

									}

								// currenciesAccepted

									/* 
									 * The currency accepted.
									 * 
									 * Use standard formats:
									 *     - ISO 4217 currency format (e.g., "USD")
									 *     - Ticker symbol for cryptocurrencies (e.g., "BTC")
									 *     - Well-known names for Local Exchange Trading Systems (LETS) and other 
									 *       currency types (e.g., "Ithaca HOUR")
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										(
											in_array(
												'currenciesAccepted',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'currenciesAccepted',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'currenciesAccepted',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_currenciesAccepted) ) {

												/*

													Reference properties on associated locations

												*/

												$provider_currenciesAccepted = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'currenciesAccepted',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_currenciesAccepted
												) {

													$provider_item_MedicalWebPage['currenciesAccepted'] = $provider_currenciesAccepted;

												}

											// MedicalBusiness

												if (
													in_array(
														'currenciesAccepted',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_currenciesAccepted
												) {

													$provider_item_MedicalBusiness['currenciesAccepted'] = $provider_currenciesAccepted;

												}

											// Person

												if (
													in_array(
														'currenciesAccepted',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_currenciesAccepted
												) {

													$provider_item_Person['currenciesAccepted'] = $provider_currenciesAccepted;

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
										(
											in_array(
												'dateModified',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'dateModified',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'dateModified',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_dateModified) ) {

												$provider_dateModified = get_the_modified_date( 'c', $provider ) ?? '';

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'dateModified',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_dateModified
												) {

													$provider_item_MedicalWebPage['dateModified'] = $provider_dateModified;

												}

											// MedicalBusiness

												if (
													in_array(
														'dateModified',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_dateModified
												) {

													$provider_item_MedicalBusiness['dateModified'] = $provider_dateModified;

												}

											// Person

												if (
													in_array(
														'dateModified',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_dateModified
												) {

													$provider_item_Person['dateModified'] = $provider_dateModified;

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
										(
											in_array(
												'datePublished',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'datePublished',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'datePublished',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_datePublished) ) {

												$provider_datePublished = get_the_date( 'c', $provider ) ?? '';

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'datePublished',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_datePublished
												) {

													$provider_item_MedicalWebPage['datePublished'] = $provider_datePublished;

												}

											// MedicalBusiness

												if (
													in_array(
														'datePublished',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_datePublished
												) {

													$provider_item_MedicalBusiness['datePublished'] = $provider_datePublished;

												}

											// Person

												if (
													in_array(
														'datePublished',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_datePublished
												) {

													$provider_item_Person['datePublished'] = $provider_datePublished;

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

									if (
										(
											in_array(
												'description',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'description',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'description',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get the Selected Short Description for This Page

											if ( !isset($provider_description_text) ) {

												$provider_description_text = get_field( 'physician_short_clinical_bio', $provider ) ?? array();

												// Fallback — Get clinical bio

													if ( !$provider_description_text ) {

														$provider_description_text = get_field( 'physician_clinical_bio', $provider ) ?? array();

													}

												// Clean up value

													if ( $provider_description_text ) {

														$provider_description_text = wp_strip_all_tags($provider_description_text);
														$provider_description_text = str_replace("\n", ' ', $provider_description_text); // Strip line breaks
														$provider_description_text = strlen($provider_description_text) > 160 ? mb_strimwidth($provider_description_text, 0, 156, '...') : $provider_description_text; // Limit to 160 characters
														$provider_description_text = uamswp_attr_conversion($provider_description_text);

													}

											}

										// Format schema value

											if ( $provider_description_text ) {

												$provider_description = array(
													'@id' => $provider_url . '#description',
													'@type' => 'TextObject',
													'text' => $provider_description_text,
												);

												$provider_description_ref = uamswp_fad_schema_node_references($provider_description);

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'description',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_description
												) {

													if (
														(
															(
																isset($provider_item_MedicalBusiness['description']['text'])
																&&
																!empty($provider_item_MedicalBusiness['description']['text'])
															)
															||
															(
																isset($provider_item_Person['description']['text'])
																&&
																!empty($provider_item_Person['description']['text'])
															)
														)
														&&
														$provider_description_ref
													) {
														
														$provider_item_MedicalWebPage['description'] = $provider_description_ref;

													} else {
														
														$provider_item_MedicalWebPage['description'] = $provider_description;

													}

												}

											// MedicalBusiness

												if (
													in_array(
														'description',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_description
												) {

													if (
														(
															(
																isset($provider_item_MedicalWebPage['description']['text'])
																&&
																!empty($provider_item_MedicalWebPage['description']['text'])
															)
															||
															(
																isset($provider_item_Person['description']['text'])
																&&
																!empty($provider_item_Person['description']['text'])
															)
														)
														&&
														$provider_description_ref
													) {
														
														$provider_item_MedicalBusiness['description'] = $provider_description_ref;

													} else {
														
														$provider_item_MedicalBusiness['description'] = $provider_description;

													}

												}

											// Person

												if (
													in_array(
														'description',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_description
												) {

													if (
														(
															(
																isset($provider_item_MedicalWebPage['description']['text'])
																&&
																!empty($provider_item_MedicalWebPage['description']['text'])
															)
															||
															(
																isset($provider_item_MedicalBusiness['description']['text'])
																&&
																!empty($provider_item_MedicalBusiness['description']['text'])
															)
														)
														&&
														$provider_description_ref
													) {
														
														$provider_item_Person['description'] = $provider_description_ref;

													} else {
														
														$provider_item_Person['description'] = $provider_description;

													}

												}

									}

								// editor

									/* 
									 * Specifies the Person who edited the CreativeWork.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Person
									 */

									 if (
										(
											in_array(
												'editor',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'editor',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'editor',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											// Base array

												$provider_editor = array();

											// UAMS

												if ( $schema_base_org_uams_ref ) {

													$provider_editor[] = $schema_base_org_uams_ref;

												}

											// UAMS Health

												if ( $schema_base_org_uams_health_ref ) {

													$provider_editor[] = $schema_base_org_uams_health_ref;

												}

										// Format values

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array( $provider_editor );

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'editor',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_editor
												) {

													$provider_item_MedicalWebPage['editor'] = $provider_editor;

												}

											// MedicalBusiness

												if (
													in_array(
														'editor',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_editor
												) {

													$provider_item_MedicalBusiness['editor'] = $provider_editor;

												}

											// Person

												if (
													in_array(
														'editor',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_editor
												) {

													$provider_item_Person['editor'] = $provider_editor;

												}

									}

								// gender

									/* 
									 * Gender of something, typically a Person, but possibly also fictional 
									 * characters, animals, etc. While https://schema.org/Male and 
									 * https://schema.org/Female may be used, text strings are also acceptable for 
									 * people who do not identify as a binary gender. The gender property can also be 
									 * used in an extended sense to cover (e.g., the gender of sports teams). As with 
									 * the gender of individuals, we do not try to enumerate all possibilities. A 
									 * mixed-gender SportsTeam can be indicated with a text value of "Mixed".
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - GenderType
									 *     - Text
									 */

									if (
										(
											in_array(
												'gender',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'gender',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'gender',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_gender_value) ) {

												$provider_gender_value = get_field( 'physician_gender', $provider );

											}

											// Format values

												$provider_gender_value = $provider_gender_value ? ucfirst($provider_gender_value) : '';
												$provider_gender_value_attr = $provider_gender_value ? uamswp_attr_conversion($provider_gender_value) : '';

											// Define list of GenderType enumeration members

												$GenderType_valid = array(
													'Female',
													'Male'
												);

											// Format schema values

												if (
													in_array(
														$provider_gender_value_attr,
														$GenderType_valid
													)
												) {

													$provider_gender = array(
														'@id' => 'https://schema.org/' . $provider_gender_value_attr,
														'@type' => 'GenderType'
													);

												} else {

													$provider_gender = strtolower($provider_gender_value_attr);

												}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'gender',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_gender
												) {

													$provider_item_MedicalWebPage['gender'] = $provider_gender;

												}

											// MedicalBusiness

												if (
													in_array(
														'gender',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_gender
												) {

														$provider_item_MedicalBusiness['gender'] = $provider_gender;

												}

											// Person

												if (
													in_array(
														'gender',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_gender
												) {

													$provider_item_Person['gender'] = $provider_gender;

												}

									}

								// hasCredential

									/* 
									 * A credential awarded to the Person or Organization.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - EducationalOccupationalCredential
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										(
											in_array(
												'hasCredential',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'hasCredential',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'hasCredential',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											// Get IDs of degrees and credentials

												if ( !isset($provider_degrees) ) {

													$provider_degrees = get_field( 'physician_degree', $provider );
													$provider_degrees = array_filter($provider_degrees);
													$provider_degrees = array_unique($provider_degrees);
													$provider_degrees = array_values($provider_degrees);

												}

											// Format values

												if ( $provider_degrees ) {

													$provider_hasCredential = uamswp_fad_schema_hascredential(
														$provider_degrees // mixed // Required // Degrees and credentials ID values
													);

												}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'hasCredential',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_hasCredential
												) {

													$provider_item_MedicalWebPage['hasCredential'] = $provider_hasCredential;

												}

											// MedicalBusiness

												if (
													in_array(
														'hasCredential',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_hasCredential
												) {

														$provider_item_MedicalBusiness['hasCredential'] = $provider_hasCredential;

												}

											// Person

												if (
													in_array(
														'hasCredential',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_hasCredential
												) {

													$provider_item_Person['hasCredential'] = $provider_hasCredential;

												}

									}

								// hasOccupation

									/* 
									 * The Person's occupation. For past professions, use Role for expressing dates.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Occupation
									 */

									if (
										(
											in_array(
												'hasOccupation',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'hasOccupation',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'hasOccupation',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_clinical_specialization) ) {

												$provider_clinical_specialization = get_field( 'physician_title', $provider ) ?? '';

											}

										// Format values

											if ( $provider_clinical_specialization ) {

												$provider_hasOccupation = uamswp_fad_schema_hasoccupation(
													$provider_clinical_specialization // mixed // Required // Clinical Specialization ID values
												);

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'hasOccupation',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_hasOccupation
												) {

													$provider_item_MedicalWebPage['hasOccupation'] = $provider_hasOccupation;

												}

											// MedicalBusiness

												if (
													in_array(
														'hasOccupation',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_hasOccupation
												) {

														$provider_item_MedicalBusiness['hasOccupation'] = $provider_hasOccupation;

												}

											// Person

												if (
													in_array(
														'hasOccupation',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_hasOccupation
												) {

													$provider_item_Person['hasOccupation'] = $provider_hasOccupation;

												}

									}

								// hasMap

									/* 
									 * A URL to a map of the place.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Map
									 *     - URL
									 * 
									 * The examples on Schema.org indicate that a URL to the location on Google Maps 
									 * is acceptable.
									 */

									if (
										in_array(
											'hasMap',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
										||
										in_array(
											'hasMap',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
										||
										in_array(
											'hasMap',
											$provider_properties_map[$Person_type]['properties']
										)
									) {

										// Google Company ID (CID)

											// Get Google Customer ID repeater field value

												if ( !isset($provider_google_cid) ) {

													if ( !isset($provider_google_cid_repeater) ) {

														$provider_google_cid_repeater = get_field( 'schema_google_cid_multiple', $provider ) ?? array();

													}

													// Add each item to Google Company ID value array

														$provider_google_cid = array();

														if ( $provider_google_cid_repeater ) {

															foreach ( $provider_google_cid_repeater as $cid ) {

																if ( $cid ) {

																	$provider_google_cid[] = $cid['schema_google_cid_text'];

																}

															}

														}

													// Clean up Google Company ID value array

														$provider_google_cid = array_filter($provider_google_cid);
														$provider_google_cid = array_unique($provider_google_cid);
														$provider_google_cid = array_values($provider_google_cid);

												}

											// Format values

												if ( $provider_google_cid ) {

													$provider_google_cid = is_array($provider_google_cid) ? $provider_google_cid : array($provider_google_cid);

													foreach ( $provider_google_cid as $cid ) {

														if ( $cid ) {

															$provider_hasMap[] = 'https://www.google.com/maps?cid=' . $cid;

														}

													}

												}

											// Clean up array

												if ( $provider_hasMap ) {

													// If there is only one item, flatten the multi-dimensional array by one step

														if ( $provider_hasMap ) {

															uamswp_fad_flatten_multidimensional_array($provider_hasMap);

														}

												}


										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'hasMap',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_hasMap
												) {

													$provider_item_MedicalWebPage['hasMap'] = $provider_hasMap;

												}

											// MedicalBusiness

												if (
													in_array(
														'hasMap',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_hasMap
												) {

														$provider_item_MedicalBusiness['hasMap'] = $provider_hasMap;

												}

											// Person

												if (
													in_array(
														'hasMap',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_hasMap
												) {

													$provider_item_Person['hasMap'] = $provider_hasMap;

												}

									}

								// identifiers (multiple properties)

									if ( $nesting_level == 0 ) {

										// 'duns' property

											/* 
											 * The Dun & Bradstreet DUNS number for identifying an organization or business 
											 * person.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											if (
												(
													in_array(
														'duns',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'duns',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												// Define values

													if ( !isset($provider_duns) ) {

														// Base 'duns' property value array

															$provider_duns = array();

														// Get values

															$provider_duns = array();

													}

												// Add to item values

													// MedicalBusiness

														if (
															in_array(
																'duns',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_duns
														) {

																$provider_item_MedicalBusiness['duns'] = $provider_duns;

														}

													// Person

														if (
															in_array(
																'duns',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_duns
														) {

															$provider_item_Person['duns'] = $provider_duns;

														}

											}

										// globalLocationNumber

											/* 
											 * The Global Location Number (GLN, sometimes also referred to as International 
											 * Location Number or ILN) of the respective organization, person, or place. The 
											 * GLN is a 13-digit number used to identify parties and physical locations.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											if (
												(
													in_array(
														'globalLocationNumber',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'globalLocationNumber',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												// Define values

													if ( !isset($provider_globalLocationNumber) ) {

														// Base 'globalLocationNumber' property value array

															$provider_globalLocationNumber = array();

														// Get values

															$provider_globalLocationNumber = array();

													}

												// Add to item values

													// MedicalBusiness

														if (
															in_array(
																'globalLocationNumber',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_globalLocationNumber
														) {

																$provider_item_MedicalBusiness['globalLocationNumber'] = $provider_globalLocationNumber;

														}

													// Person

														if (
															in_array(
																'globalLocationNumber',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_globalLocationNumber
														) {

															$provider_item_Person['globalLocationNumber'] = $provider_globalLocationNumber;

														}

											}

										// isicV4

											/* 
											 * The International Standard of Industrial Classification of All Economic 
											 * Activities (ISIC), Revision 4 code for a particular organization, business 
											 * person, or place.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											// Define values

											if (
												(
													in_array(
														'isicV4',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'isicV4',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												if ( !isset($provider_isicV4) ) {

													// Base 'isicV4' property value array

														$provider_isicV4 = array();

													// Get values

														$provider_isicV4 = array();

												}

												// Add to item values

													// MedicalBusiness

														if (
															in_array(
																'isicV4',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_isicV4
														) {

																$provider_item_MedicalBusiness['isicV4'] = $provider_isicV4;

														}

													// Person

														if (
															in_array(
																'isicV4',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_isicV4
														) {

															$provider_item_Person['isicV4'] = $provider_isicV4;

														}

											}

										// leiCode

											/* 
											 * An organization identifier that uniquely identifies a legal entity as defined 
											 * in ISO 17442.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											if (
												(
													in_array(
														'leiCode',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'leiCode',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												if ( !isset($provider_leiCode) ) {

													// Base 'leiCode' property value array

														$provider_leiCode = array();

													// Get values

														$provider_leiCode = array();

												}

												// Add to item values

													// MedicalBusiness

														if (
															in_array(
																'leiCode',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_leiCode
														) {

																$provider_item_MedicalBusiness['leiCode'] = $provider_leiCode;

														}

													// Person

														if (
															in_array(
																'leiCode',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_leiCode
														) {

															$provider_item_Person['leiCode'] = $provider_leiCode;

														}

											}

										// naics

											/* 
											 * The North American Industry Classification System (NAICS) code for a particular 
											 * organization or business person.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											if (
												(
													in_array(
														'naics',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'naics',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												if ( !isset($provider_naics) ) {

													// Base 'naics' property value array

														$provider_naics = array();

													// Get values

														$provider_naics = array();

												}

												// Add to item values

													// MedicalBusiness

														if (
															in_array(
																'naics',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_naics
														) {

																$provider_item_MedicalBusiness['naics'] = $provider_naics;

														}

													// Person

														if (
															in_array(
																'naics',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_naics
														) {

															$provider_item_Person['naics'] = $provider_naics;

														}

											}

										// taxID

											/* 
											 * The Tax / Fiscal ID of the organization or person (e.g., the TIN in the US; 
											 * the CIF/NIF in Spain).
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											if (
												(
													in_array(
														'taxID',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'taxID',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												// taxID

													if ( !isset($provider_taxID) ) {

														// Base 'taxID' property value array

															/* https://schema.org/taxID */

															$provider_taxID = array();

														// Get values

															$provider_taxID = array();

													}

												// Taxpayer Identification Number

													if ( !isset($provider_taxID_taxpayer) ) {

														// Base Taxpayer Identification Number value array

															/* https://www.wikidata.org/wiki/Q1444804 */

															$provider_taxID_taxpayer = array();

														// Get values

															$provider_taxID_taxpayer = array();

													}

												// Employer Identification Number

													if ( !isset($provider_taxID_employer) ) {

														// Base Employer Identification Number value array

															/* https://www.wikidata.org/wiki/Q2397748 */

															$provider_taxID_employer = array();

														// Get values

															$provider_taxID_employer = array();

													}

												// Add to item values

													// MedicalBusiness

														if (
															in_array(
																'taxID',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_taxID
														) {

																$provider_item_MedicalBusiness['taxID'] = $provider_taxID;

														}

													// Person

														if (
															in_array(
																'taxID',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_taxID
														) {

															$provider_item_Person['taxID'] = $provider_taxID;

														}

											}

										// vatID

											/* 
											 * The Value-added Tax ID of the organization or person.
											 * 
											 *     - Text
											 */

											if (
												(
													in_array(
														'vatID',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'vatID',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												if ( !isset($provider_vatID) ) {

													// Base 'vatID' property value array

														$provider_vatID = array();

													// Get values

														$provider_vatID = array();

												}

												// Add to item values

													// MedicalBusiness

														if (
															in_array(
																'vatID',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_vatID
														) {

																$provider_item_MedicalBusiness['vatID'] = $provider_vatID;

														}

													// Person

														if (
															in_array(
																'vatID',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_vatID
														) {

															$provider_item_Person['vatID'] = $provider_vatID;

														}

											}

										// iso6523Code

											/* 
											 * An organization identifier as defined in ISO 6523(-1). Note that many existing 
											 * organization identifiers such as leiCode, duns and vatID can be expressed as an 
											 * ISO 6523 identifier by setting the ICD part of the ISO 6523 identifier 
											 * accordingly.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 * 
											 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
											 * feedback and adoption from applications and websites can help improve their 
											 * definitions.
											 */

											if (
												(
													in_array(
														'iso6523Code',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'iso6523Code',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												if ( !isset($provider_iso6523Code) ) {

													// Base 'iso6523Code' property value array

														$provider_iso6523Code = array();

													// Get values

														$provider_iso6523Code = array();

												}

												// Add to item values

													// MedicalBusiness

														if (
															in_array(
																'iso6523Code',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_iso6523Code
														) {

																$provider_item_MedicalBusiness['iso6523Code'] = $provider_iso6523Code;

														}

													// Person

														if (
															in_array(
																'iso6523Code',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_iso6523Code
														) {

															$provider_item_Person['iso6523Code'] = $provider_iso6523Code;

														}

											}

										// 'identifier' property

											if (
												(
													in_array(
														'identifier',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													||
													in_array(
														'identifier',
														$provider_properties_map[$Person_type]['properties']
													)
												)
												&&
												$nesting_level == 0
											) {

												/* 
												 * The identifier property represents any kind of identifier for any kind of 
												 * Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated 
												 * properties for representing many of these, either as textual strings or as 
												 * URL (URI) links.
												 * 
												 * See https://schema.org/docs/datamodel.html#identifierBg for more details.
												 * 
												 * Values expected to be one of these types:
												 * 
												 *     - PropertyValue
												 *     - Text
												 *     - URL
												 */

												if ( !isset($provider_identifier) ) {

													// Base 'identifier' property value array

														$provider_identifier = array();

													// Get values

														// Dun & Bradstreet DUNS number

															if ( $provider_duns ) {

																$provider_identifier[] = uamswp_fad_schema_propertyvalue(
																	array(
																		'Data Universal Numbering System (DUNS) number',
																		'DUNS number',
																		'D-U-N-S number'
																	), // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'Data Universal Numbering System number', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q246386', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$provider_duns, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// Global Location Number

															if ( $provider_globalLocationNumber ) {

																$provider_identifier[] = uamswp_fad_schema_propertyvalue(
																	'GLN', // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'Global Location Number', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q1258830', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$provider_globalLocationNumber, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code

															if ( $provider_isicV4 ) {

																$provider_identifier[] = uamswp_fad_schema_propertyvalue(
																	array(
																		'ISIC 2008',
																		'ISIC Rev 4'
																	), // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'International Standard Industrial Classification Rev. 4', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q112111674', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$provider_isicV4, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// Legal Entity Identifier (LEI)

															if ( $provider_leiCode ) {

																$provider_identifier[] = uamswp_fad_schema_propertyvalue(
																	array(
																		'Global Legal Entity Identifier',
																		'LEI',
																		'LEI code',
																		'LEI number'
																	), // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'Legal Entity Identifier', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q6517388', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$provider_leiCode, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// North American Industry Classification System (NAICS) code

															if ( $provider_naics ) {

																$provider_identifier[] = uamswp_fad_schema_propertyvalue(
																	array(
																		'North American Industry Classification System',
																		'NAICS code',
																		'NAICS',
																		'NAICS-SCIAN code',
																		'NAICS-SCIAN'
																	), // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'North American Industry Classification System code', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q3509282', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$provider_naics, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// Tax / Fiscal ID

															// Taxpayer Identification Number

																if ( $provider_taxID_taxpayer ) {

																	$provider_identifier[] = uamswp_fad_schema_propertyvalue(
																		array(
																			'TIN',
																			'IRS TIN',
																			'TIN IRS'
																		), // mixed // Optional // alternateName property value
																		null, // string // Optional // description property value
																		null, // int // Optional // maxValue property value
																		null, // mixed // Optional // measurementMethod property value
																		null, // mixed // Optional // measurementTechnique property value
																		null, // int // Optional // minValue property value
																		'Taxpayer Identification Number', // string // Optional // name property value
																		'https://www.wikidata.org/wiki/Q1444804', // string // Optional // propertyID property value
																		null, // string // Optional // unitCode property value
																		null, // string // Optional // unitText property value
																		null, // string // Optional // url property value
																		$provider_taxID_taxpayer, // mixed // Optional // value property value
																		null, // mixed // Optional // valueReference property value
																		$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																	);

																}

															// Employer Identification Number

																if ( $provider_taxID_employer ) {

																	$provider_identifier[] = uamswp_fad_schema_propertyvalue(
																		array(
																			'Federal Employer Identification Number',
																			'EIN'
																		), // mixed // Optional // alternateName property value
																		null, // string // Optional // description property value
																		null, // int // Optional // maxValue property value
																		null, // mixed // Optional // measurementMethod property value
																		null, // mixed // Optional // measurementTechnique property value
																		null, // int // Optional // minValue property value
																		'Employer Identification Number', // string // Optional // name property value
																		'https://www.wikidata.org/wiki/Q2397748', // string // Optional // propertyID property value
																		null, // string // Optional // unitCode property value
																		null, // string // Optional // unitText property value
																		null, // string // Optional // url property value
																		$provider_taxID_employer, // mixed // Optional // value property value
																		null, // mixed // Optional // valueReference property value
																		$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																	);

																}

														// Value-added tax (VAT) identification number

															if ( $provider_vatID ) {

																$provider_identifier[] = uamswp_fad_schema_propertyvalue(
																	array(
																		'value-added tax identification number',
																		'VAT ID',
																		'VATIN'
																	), // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'VAT identification number', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q2319042', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$provider_vatID, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// National Provider Identifier (NPI)

															// Get values

																if ( !isset($provider_npi) ) {

																	$provider_npi = get_field( 'physician_npi', $provider ) ?? '';
																	$provider_npi = $provider_npi ? str_pad($provider_npi, 10, '0', STR_PAD_LEFT) : ''; // Add enough leading zeroes to reach 10 digits

																}

															if ( $provider_npi ) {

																$provider_identifier[] = uamswp_fad_schema_propertyvalue(
																	'NPI', // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'National Provider Identifier', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q6975101', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$provider_npi, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// Google Company ID (CID)

															// Get Google Customer ID repeater field value

																if ( !isset($provider_google_cid) ) {

																	if ( !isset($provider_google_cid_repeater) ) {

																		$provider_google_cid_repeater = get_field( 'schema_google_cid_multiple', $provider ) ?? array();
	
																	}

																	// Add each item to Google Company ID value array
		
																		if ( $provider_google_cid_repeater ) {

																			foreach ( $provider_google_cid_repeater as $cid ) {

																				if ( $cid ) {

																					$provider_google_cid[] = $cid['schema_google_cid_text'];

																				}

																			}

																		}

																	// Clean up Google Company ID value array

																		$provider_google_cid = array_filter($provider_google_cid);
																		$provider_google_cid = array_unique($provider_google_cid);
																		$provider_google_cid = array_values($provider_google_cid);

																}

															// Add value to identifier property value list

																if ( $provider_google_cid ) {

																	// If there is only one item, flatten the multi-dimensional array by one step

																		if ( $provider_google_cid ) {

																			uamswp_fad_flatten_multidimensional_array($provider_google_cid);

																		}

																	$provider_identifier[] = uamswp_fad_schema_propertyvalue(
																		array(
																			'Google Ads customer ID',
																			'Google Ads CID',
																			'Google Maps customer ID',
																			'Google Maps CID',
																			'Google CID',
																			'CID'
																		), // mixed // Optional // alternateName property value
																		null, // string // Optional // description property value
																		null, // int // Optional // maxValue property value
																		null, // mixed // Optional // measurementMethod property value
																		null, // mixed // Optional // measurementTechnique property value
																		null, // int // Optional // minValue property value
																		'Google customer ID', // string // Optional // name property value
																		'https://support.google.com/google-ads/answer/29198', // string // Optional // propertyID property value
																		null, // string // Optional // unitCode property value
																		null, // string // Optional // unitText property value
																		null, // string // Optional // url property value
																		$provider_google_cid, // mixed // Optional // value property value
																		null, // mixed // Optional // valueReference property value
																		$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																	);

																}

												}

												// Add to item values

													// MedicalBusiness

														if (
															in_array(
																'identifier',
																$provider_properties_map[$MedicalBusiness_type]['properties']
															)
															&&
															$provider_identifier
														) {

																$provider_item_MedicalBusiness['identifier'] = $provider_identifier;

														}

													// Person

														if (
															in_array(
																'identifier',
																$provider_properties_map[$Person_type]['properties']
															)
															&&
															$provider_identifier
														) {

															$provider_item_Person['identifier'] = $provider_identifier;

														}

											}

									}

								// image (common use)

									if (
										(
											in_array(
												'image',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'image',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'image',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										||
										(
											in_array(
												'photo',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'photo',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'photo',
												$provider_properties_map[$Person_type]['properties']
											)
										)
									) {

										if ( !isset($provider_image_id) ) {

											// Get the various images

												$provider_image_id = get_field( '_thumbnail_id', $provider ) ?? 0;

										}

										if ( !isset($provider_image_wide_id) ) {

											// Get the various images

												$provider_image_wide_id = get_field( 'physician_image_wide', $provider ) ?? null;

										}

										// Create ImageObject values array

											if ( !isset($provider_image_general) ) {

												if (
													$provider_image_id
													||
													$provider_image_wide_id
												) {

													$provider_image_general = uamswp_fad_schema_imageobject_thumbnails(
														$provider_url, // URL of entity with which the image is associated
														$nesting_level, // Nesting level within the main schema
														'3:4', // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9')
														'Image', // Base fragment identifier
														$provider_image_id, // ID of image to use for 1:1 aspect ratio
														$provider_image_id, // ID of image to use for 3:4 aspect ratio
														$provider_image_wide_id, // ID of image to use for 4:3 aspect ratio
														$provider_image_wide_id, // ID of image to use for 16:9 aspect ratio
														0 // ID of image to use for full image
													);

												}

											}

										// Clean up the array

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array($provider_image_general);

									}

								// image (specific property)

									/* 
									 * An image of the item. This can be a URL or a fully described ImageObject.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - ImageObject
									 *     - URL
									 */

									if (
										in_array(
											'image',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
										||
										in_array(
											'image',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
										||
										in_array(
											'image',
											$provider_properties_map[$Person_type]['properties']
										)
									) {

										// Get the values

											if ( !isset($provider_image) ) {

												$provider_image = $provider_image_general ?? array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'image',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_image
												) {

													$provider_item_MedicalWebPage['image'] = $provider_image;

												}

											// MedicalBusiness

												if (
													in_array(
														'image',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_image
												) {

														$provider_item_MedicalBusiness['image'] = $provider_image;

												}

											// Person

												if (
													in_array(
														'image',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_image
												) {

													$provider_item_Person['image'] = $provider_image;

												}

									}

								// inLanguage

									/* 
									 * The language of the content or performance or used in an action. Please use one 
									 * of the language codes from the IETF BCP 47 standard. See also availableLanguage.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Language
									 *     - Text
									 */

									 if (
										(
											in_array(
												'inLanguage',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'inLanguage',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'inLanguage',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( isset($schema_base_website_uams_health_inLanguage_ref) ) {

												$provider_inLanguage = $schema_base_website_uams_health_inLanguage_ref;

											} else {

												$provider_inLanguage = $schema_base_website_uams_health_inLanguage_ref;

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'inLanguage',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_inLanguage
												) {

													$provider_item_MedicalWebPage['inLanguage'] = array(
														'@id' => $provider_url . '#inLanguage',
														'@type' => 'Language',
														'alternateName' => 'en',
														'name' => 'English',
														'sameAs' => 'https://www.wikidata.org/wiki/Q1860'
													);

												}

											// MedicalBusiness

												if (
													in_array(
														'inLanguage',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_inLanguage
												) {

													$provider_item_MedicalBusiness['inLanguage'] = $provider_inLanguage;

												}

											// Person

												if (
													in_array(
														'inLanguage',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_inLanguage
												) {

													$provider_item_Person['inLanguage'] = $provider_inLanguage;

												}

									}

								// isAcceptingNewPatients

									/* 
									 * Whether the provider is accepting new patients.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Boolean
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										(
											in_array(
												'isAcceptingNewPatients',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'isAcceptingNewPatients',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'isAcceptingNewPatients',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_isAcceptingNewPatients) ) {

												$provider_isAcceptingNewPatients = get_field( 'physician_accepting_patients', $provider ) ?? false;

											}

										// Format values

											$provider_isAcceptingNewPatients = $provider_isAcceptingNewPatients ? 'True' : 'False';

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'isAcceptingNewPatients',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_isAcceptingNewPatients
												) {

													$provider_item_MedicalWebPage['isAcceptingNewPatients'] = $provider_isAcceptingNewPatients;

												}

											// MedicalBusiness

												if (
													in_array(
														'isAcceptingNewPatients',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_isAcceptingNewPatients
												) {

														$provider_item_MedicalBusiness['isAcceptingNewPatients'] = $provider_isAcceptingNewPatients;

												}

											// Person

												if (
													in_array(
														'isAcceptingNewPatients',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_isAcceptingNewPatients
												) {

													$provider_item_Person['isAcceptingNewPatients'] = $provider_isAcceptingNewPatients;

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

									if (
										(
											in_array(
												'isAccessibleForFree',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'isAccessibleForFree',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'isAccessibleForFree',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_isAccessibleForFree) ) {

												$provider_isAccessibleForFree = 'True';

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'isAccessibleForFree',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_isAccessibleForFree
												) {

													$provider_item_MedicalWebPage['isAccessibleForFree'] = $provider_isAccessibleForFree;

												}

											// MedicalBusiness

												if (
													in_array(
														'isAccessibleForFree',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_isAccessibleForFree
												) {

														$provider_item_MedicalBusiness['isAccessibleForFree'] = $provider_isAccessibleForFree;

												}

											// Person

												if (
													in_array(
														'isAccessibleForFree',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_isAccessibleForFree
												) {

													$provider_item_Person['isAccessibleForFree'] = $provider_isAccessibleForFree;

												}

									}

								// isFamilyFriendly

									/* 
									 * Indicates whether this content is family friendly.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Boolean
									 */

									 if (
										in_array(
											'isFamilyFriendly',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
										||
										in_array(
											'isFamilyFriendly',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
										||
										in_array(
											'isFamilyFriendly',
											$provider_properties_map[$Person_type]['properties']
										)
									) {

										// Get values

											$provider_isFamilyFriendly = 'True';

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'isFamilyFriendly',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_isFamilyFriendly
												) {

													$provider_item_MedicalWebPage['isFamilyFriendly'] = $provider_isFamilyFriendly;

												}

											// MedicalBusiness

												if (
													in_array(
														'isFamilyFriendly',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_isFamilyFriendly
												) {

													$provider_item_MedicalBusiness['isFamilyFriendly'] = $provider_isFamilyFriendly;

												}

											// Person

												if (
													in_array(
														'isFamilyFriendly',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_isFamilyFriendly
												) {

													$provider_item_Person['isFamilyFriendly'] = $provider_isFamilyFriendly;

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
										(
											in_array(
												'isPartOf',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'isPartOf',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'isPartOf',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( $schema_base_website_uams_health_ref ) {

												$provider_isPartOf = $schema_base_website_uams_health_ref;

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'isPartOf',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_isPartOf
												) {

													$provider_item_MedicalWebPage['isPartOf'] = $provider_isPartOf;

												}

											// MedicalBusiness

												if (
													in_array(
														'isPartOf',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_isPartOf
												) {

													$provider_item_MedicalBusiness['isPartOf'] = $provider_isPartOf;

												}

											// Person

												if (
													in_array(
														'isPartOf',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_isPartOf
												) {

													$provider_item_Person['isPartOf'] = $provider_isPartOf;

												}

									}

								// jobTitle

									/* 
									 * The job title of the person (for example, Financial Manager).
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - DefinedTerm
									 *     - Text
									 */

									if (
										(
											in_array(
												'jobTitle',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'jobTitle',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'jobTitle',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_jobTitle) ) {

												$provider_jobTitle = array();

												if ( !isset($provider_clinical_specialization_term) ) {

													if ( !isset($provider_clinical_specialization) ) {

														$provider_clinical_specialization = get_field( 'physician_title', $provider );

													}

													if ( $provider_clinical_specialization ) {

														$provider_clinical_specialization_term = get_term( $provider_clinical_specialization, 'clinical_title' );

													}

												}

												if ( is_object($provider_clinical_specialization_term) ) {

													$provider_clinical_specialization_name = $provider_clinical_specialization_term->name;
													$provider_jobTitle = get_field( 'clinical_specialization_title', $provider_clinical_specialization_term );
													$provider_jobTitle = $provider_jobTitle ?: $provider_clinical_specialization_name;

												}

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'jobTitle',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_jobTitle
												) {

													$provider_item_MedicalWebPage['jobTitle'] = $provider_jobTitle;

												}

											// MedicalBusiness

												if (
													in_array(
														'jobTitle',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_jobTitle
												) {

														$provider_item_MedicalBusiness['jobTitle'] = $provider_jobTitle;

												}

											// Person

												if (
													in_array(
														'jobTitle',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_jobTitle
												) {

													$provider_item_Person['jobTitle'] = $provider_jobTitle;

												}

									}

								// keywords

									/* 
									 * Keywords or tags used to describe some item. Multiple textual entries in a 
									 * keywords list are typically delimited by commas, or by repeating the property.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - DefinedTerm
									 *     - Text
									 *     - URL
									 */

									if (
										(
											in_array(
												'keywords',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'keywords',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'keywords',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_keywords) ) {

												$provider_keywords = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'keywords',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_keywords
												) {

													$provider_item_MedicalWebPage['keywords'] = $provider_keywords;

												}

											// MedicalBusiness

												if (
													in_array(
														'keywords',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_keywords
												) {

														$provider_item_MedicalBusiness['keywords'] = $provider_keywords;

												}

											// Person

												if (
													in_array(
														'keywords',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_keywords
												) {

													$provider_item_Person['keywords'] = $provider_keywords;

												}

									}

								// knowsAbout

									/* 
									 * Of a Person, and less typically of an Organization, to indicate a topic that is 
									 * known about — suggesting possible expertise but not implying it. We do not 
									 * distinguish skill levels here, or relate this to educational content, events, 
									 * objectives or JobPosting descriptions.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 *     - Thing
									 *     - URL
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										(
											in_array(
												'knowsAbout',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'knowsAbout',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'knowsAbout',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_knowsAbout) ) {

												$provider_knowsAbout = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'knowsAbout',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_knowsAbout
												) {

													$provider_item_MedicalWebPage['knowsAbout'] = $provider_knowsAbout;

												}

											// MedicalBusiness

												if (
													in_array(
														'knowsAbout',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_knowsAbout
												) {

														$provider_item_MedicalBusiness['knowsAbout'] = $provider_knowsAbout;

												}

											// Person

												if (
													in_array(
														'knowsAbout',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_knowsAbout
												) {

													$provider_item_Person['knowsAbout'] = $provider_knowsAbout;

												}

									}

								// knowsLanguage

									/* 
									 * Of a Person, and less typically of an Organization, to indicate a known 
									 * language. We do not distinguish skill levels or reading / writing / speaking / 
									 * signing here. Use language codes from the IETF BCP 47 standard.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Language
									 *     - Text
									 */

									if (
										(
											in_array(
												'knowsLanguage',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'knowsLanguage',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'knowsLanguage',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_knowsLanguage) ) {

												if ( !isset($provider_languages) ) {

													$provider_languages = get_field( 'physician_languages', $provider );

												}

												$provider_knowsLanguage = uamswp_fad_schema_language(
													$languages // mixed // Required // Language ID values
												);

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'knowsLanguage',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_knowsLanguage
												) {

													$provider_item_MedicalWebPage['knowsLanguage'] = $provider_knowsLanguage;

												}

											// MedicalBusiness

												if (
													in_array(
														'knowsLanguage',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_knowsLanguage
												) {

														$provider_item_MedicalBusiness['knowsLanguage'] = $provider_knowsLanguage;

												}

											// Person

												if (
													in_array(
														'knowsLanguage',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_knowsLanguage
												) {

													$provider_item_Person['knowsLanguage'] = $provider_knowsLanguage;

												}

									}

								// lastReviewed

									/* 
									 * Date on which the content on this web page was last reviewed for accuracy 
									 * and/or completeness.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Date
									 */

									 if (
										(
											in_array(
												'lastReviewed',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'lastReviewed',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'lastReviewed',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_lastReviewed) ) {

												$provider_lastReviewed = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'lastReviewed',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_lastReviewed
												) {

													$provider_item_MedicalWebPage['lastReviewed'] = $provider_lastReviewed;

												}

											// MedicalBusiness

												if (
													in_array(
														'lastReviewed',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_lastReviewed
												) {

													$provider_item_MedicalBusiness['lastReviewed'] = $provider_lastReviewed;

												}

											// Person

												if (
													in_array(
														'lastReviewed',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_lastReviewed
												) {

													$provider_item_Person['lastReviewed'] = $provider_lastReviewed;

												}

									}

								// mainContentOfPage

									/* 
									 * Indicates if this web page element is the main subject of the page.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - WebPageElement
									 */

									if (
										(
											in_array(
												'mainContentOfPage',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'mainContentOfPage',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'mainContentOfPage',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_mainContentOfPage) ) {

												$provider_mainContentOfPage = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'mainContentOfPage',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_mainContentOfPage
												) {

													$provider_item_MedicalWebPage['mainContentOfPage'] = $provider_mainContentOfPage;

												}

											// MedicalBusiness

												if (
													in_array(
														'mainContentOfPage',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_mainContentOfPage
												) {

													$provider_item_MedicalBusiness['mainContentOfPage'] = $provider_mainContentOfPage;

												}

											// Person

												if (
													in_array(
														'mainContentOfPage',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_mainContentOfPage
												) {

													$provider_item_Person['mainContentOfPage'] = $provider_mainContentOfPage;

												}

									}

								// mainEntity

									/* 
									 * Indicates the primary entity described in some page or other CreativeWork.
									 * 
									 * Inverse-property: mainEntityOfPage
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Thing
									 */

									if (
										(
											in_array(
												'mainEntity',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'mainEntity',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'mainEntity',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( $schema_provider_MedicalBusiness_ref ) {

												$provider_mainEntity = $schema_provider_MedicalBusiness_ref;

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'mainEntity',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_mainEntity
												) {

													$provider_item_MedicalWebPage['mainEntity'] = $provider_mainEntity;

												}

											// MedicalBusiness

												if (
													in_array(
														'mainEntity',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_mainEntity
												) {

													$provider_item_MedicalBusiness['mainEntity'] = $provider_mainEntity;

												}

											// Person

												if (
													in_array(
														'mainEntity',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_mainEntity
												) {

													$provider_item_Person['mainEntity'] = $provider_mainEntity;

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
										in_array(
											'mainEntityOfPage',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									) {

										// Get values

											if ( !isset($provider_mainEntityOfPage) ) {

												if ( !isset($schema_provider_MedicalWebPage_ref) ) {

													if ( !isset($provider_url) ) {

														$provider_url = get_permalink($provider);
														$provider_url = $provider_url ? user_trailingslashit( $provider_url ) : '';

													}

													$schema_provider_MedicalWebPage_ref = $provider_url ? array( '@id' => $provider_url . '#MedicalWebPage' ) : array();
													uamswp_fad_flatten_multidimensional_array($schema_provider_MedicalWebPage_ref);

												}

												$provider_mainEntityOfPage = $schema_provider_MedicalWebPage_ref ?? array();

											}

										// Add to item values

											// MedicalBusiness

												if (
													in_array(
														'mainEntityOfPage',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_mainEntityOfPage
												) {

														$provider_item_MedicalBusiness['mainEntityOfPage'] = $provider_mainEntityOfPage;

												}

									}

								// maintainer

									/* 
									 * A maintainer of a Dataset, software package (SoftwareApplication), or other 
									 * Project.
									 * 
									 * A maintainer is a Person or Organization that manages contributions to, and/or 
									 * publication of, some (typically complex) artifact.
									 * 
									 * It is common for distributions of software and data to be based on "upstream" 
									 * sources.
									 * 
									 * When maintainer is applied to a specific version of something (e.g., a 
									 * particular version or packaging of a Dataset), it is always possible that the 
									 * upstream source has a different maintainer.
									 * 
									 * The isBasedOn property can be used to indicate such relationships between 
									 * datasets to make the different maintenance roles clear.
									 * 
									 * Similarly in the case of software, a package may have dedicated maintainers 
									 * working on integration into software distributions such as Ubuntu, as well as 
									 * upstream maintainers of the underlying work.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 *     - Person
									 */

									 if (
										(
											in_array(
												'maintainer',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'maintainer',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'maintainer',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											// Base array

												$provider_maintainer = array();

											// UAMS

												if ( $schema_base_org_uams_ref ) {

													$provider_maintainer[] = $schema_base_org_uams_ref;

												}

											// UAMS Health

												if ( $schema_base_org_uams_health_ref ) {

													$provider_maintainer[] = $schema_base_org_uams_health_ref;

												}

										// Format values

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array( $provider_maintainer );

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'maintainer',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_maintainer
												) {

													$provider_item_MedicalWebPage['maintainer'] = $provider_maintainer;

												}

											// MedicalBusiness

												if (
													in_array(
														'maintainer',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_maintainer
												) {

													$provider_item_MedicalBusiness['maintainer'] = $provider_maintainer;

												}

											// Person

												if (
													in_array(
														'maintainer',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_maintainer
												) {

													$provider_item_Person['maintainer'] = $provider_maintainer;

												}

									}

								// makesOffer

									/* 
									 * A pointer to products or services offered by the organization or person.
									 * 
									 * Inverse-property: offeredBy
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Offer
									 */

									if (
										(
											in_array(
												'makesOffer',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'makesOffer',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'makesOffer',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_makesOffer) ) {

												$provider_makesOffer = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'makesOffer',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_makesOffer
												) {

													$provider_item_MedicalWebPage['makesOffer'] = $provider_makesOffer;

												}

											// MedicalBusiness

												if (
													in_array(
														'makesOffer',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_makesOffer
												) {

														$provider_item_MedicalBusiness['makesOffer'] = $provider_makesOffer;

												}

											// Person

												if (
													in_array(
														'makesOffer',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_makesOffer
												) {

													$provider_item_Person['makesOffer'] = $provider_makesOffer;

												}

									}

								// medicalAudience

									/* 
									 * Medical audience for page.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - MedicalAudience (Type)
									 *     - MedicalAudienceType (Enumeration Type)
									 */

									 if (
										(
											in_array(
												'medicalAudience',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'medicalAudience',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'medicalAudience',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_medicalAudience) ) {

												$provider_medicalAudience = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'medicalAudience',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_medicalAudience
												) {

													$provider_item_MedicalWebPage['medicalAudience'] = $provider_medicalAudience;

												}

											// MedicalBusiness

												if (
													in_array(
														'medicalAudience',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_medicalAudience
												) {

													$provider_item_MedicalBusiness['medicalAudience'] = $provider_medicalAudience;

												}

											// Person

												if (
													in_array(
														'medicalAudience',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_medicalAudience
												) {

													$provider_item_Person['medicalAudience'] = $provider_medicalAudience;

												}

									}

								// mentions

									/* 
									 * Indicates that the CreativeWork contains a reference to, but is not necessarily 
									 * about a concept.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Thing
									 */

									if (
										(
											in_array(
												'mentions',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'mentions',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'mentions',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_mentions) ) {

												$provider_mentions = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'mentions',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_mentions
												) {

													$provider_item_MedicalWebPage['mentions'] = $provider_mentions;

												}

											// MedicalBusiness

												if (
													in_array(
														'mentions',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_mentions
												) {

													$provider_item_MedicalBusiness['mentions'] = $provider_mentions;

												}

											// Person

												if (
													in_array(
														'mentions',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_mentions
												) {

													$provider_item_Person['mentions'] = $provider_mentions;

												}

									}

								// offers

									/* 
									 * An offer to provide this item—for example, an offer to sell a product, rent the 
									 * DVD of a movie, perform a service, or give away tickets to an event.
									 * 
									 * Use businessFunction to indicate the kind of transaction offered 
									 * (i.e., sell, lease).
									 * 
									 * This property can also be used to describe a Demand.
									 * 
									 * While this property is listed as expected on a number of common types, it can 
									 * be used in others. In that case, using a second type, such as Product or a 
									 * subtype of Product, can clarify the nature of the offer.
									 * 
									 * Inverse-property: itemOffered
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Demand
									 *     - Offer
									 */

									if (
										(
											in_array(
												'offers',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'offers',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'offers',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_offers) ) {

												$provider_offers = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'offers',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_offers
												) {

													$provider_item_MedicalWebPage['offers'] = $provider_offers;

												}

											// MedicalBusiness

												if (
													in_array(
														'offers',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_offers
												) {

													$provider_item_MedicalBusiness['offers'] = $provider_offers;

												}

											// Person

												if (
													in_array(
														'offers',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_offers
												) {

													$provider_item_Person['offers'] = $provider_offers;

												}

									}

								// paymentAccepted

									/* 
									 * Cash, Credit Card, Cryptocurrency, Local Exchange Tradings System, etc.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										(
											in_array(
												'paymentAccepted',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'paymentAccepted',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'paymentAccepted',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_paymentAccepted) ) {

												/*

													Reference values from 'location'

												*/
												$provider_paymentAccepted = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'paymentAccepted',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_paymentAccepted
												) {

													$provider_item_MedicalWebPage['paymentAccepted'] = $provider_paymentAccepted;

												}

											// MedicalBusiness

												if (
													in_array(
														'paymentAccepted',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_paymentAccepted
												) {

														$provider_item_MedicalBusiness['paymentAccepted'] = $provider_paymentAccepted;

												}

											// Person

												if (
													in_array(
														'paymentAccepted',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_paymentAccepted
												) {

													$provider_item_Person['paymentAccepted'] = $provider_paymentAccepted;

												}

									}

								// photo

									/* 
									 * A photograph of this place.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - ImageObject
									 *     - Photograph
									 */

									if (
										in_array(
											'photo',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
										||
										in_array(
											'photo',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
										||
										in_array(
											'photo',
											$provider_properties_map[$Person_type]['properties']
										)
									) {

										// Get values

											if ( !isset($provider_photo) ) {

												$provider_photo = $provider_image_general ?? array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'photo',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_photo
												) {

													$provider_item_MedicalWebPage['photo'] = $provider_photo;

												}

											// MedicalBusiness

												if (
													in_array(
														'photo',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_photo
												) {

														$provider_item_MedicalBusiness['photo'] = $provider_photo;

												}

											// Person

												if (
													in_array(
														'photo',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_photo
												) {

													$provider_item_Person['photo'] = $provider_photo;

												}

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

									/*

										Create one or more Action arrays, likely 'CreateAction' type

											 * Make an appointment, new or existing patient, by phone
											 * Make an appointment, new patient, by phone
											 * Make an appointment, existing patient, by phone
											 * Make an appointment, new or existing patient, online
											 * Make an appointment, new patient, online
											 * Make an appointment, existing patient, online
											 * Refer a patient, by phone
											 * Refer a patient, by fax
											 * Refer a patient, through Epic thing

										Property descriptions:

											 * 'actionStatus'
												 * Indicates the current disposition of the Action
											 * 'agent'
												 * The direct performer or driver of the action — animate or inanimate (e.g., John 
												wrote a book)
											 * 'endTime'
												 * The endTime of something. For a reserved event or service 
												(e.g., FoodEstablishmentReservation), the time that it is expected to end. For 
												actions that span a period of time, when the action was performed (e.g., John 
												wrote a book from January to December). For media, including audio and video, 
												it's the time offset of the end of a clip within a larger file. Note that Event 
												uses startDate/endDate instead of startTime/endTime, even when describing dates 
												with times. This situation may be clarified in future revisions.
											 * 'error'
												 * For failed actions, more information on the cause of the failure.
											 * 'instrument'
												 * The object that helped the agent perform the action (e.g., John wrote a book 
												with a pen).
											 * 'location'
												 * The location of, for example, where an event is happening, where an 
												organization is located, or where an action takes place.
											 * 'object'
												 * The object upon which the action is carried out, whose state is kept intact or 
												changed. Also known as the semantic roles patient, affected or undergoer — 
												which change their state — or theme — which doesn't (e.g., John read a book).
											 * 'participant'
												 * Other co-agents that participated in the action indirectly (e.g., John wrote a 
												book with Steve).
											 * 'provider'
												 * The service provider, service operator, or service performer; the goods 
												producer. Another party (a seller) may offer those services or goods on behalf 
												of the provider. A provider may also serve as the seller. Supersedes carrier.
											 * 'result'
												 * The result produced in the action (e.g., John wrote a book).
											 * 'startTime'
												 * The startTime of something. For a reserved event or service 
												(e.g., FoodEstablishmentReservation), the time that it is expected to start. 
												For actions that span a period of time, when the action was performed 
												(e.g., John wrote a book from January to December). For media, including audio 
												and video, it's the time offset of the start of a clip within a larger file. 
												Note that Event uses startDate/endDate instead of startTime/endTime, even when 
												describing dates with times. This situation may be clarified in future 
												revisions.
											 * 'target'
												 * Indicates a target EntryPoint, or url, for an Action.

									*/

									if (
										(
											in_array(
												'potentialAction',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'potentialAction',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'potentialAction',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_potentialAction) ) {

												$provider_potentialAction = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'potentialAction',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_potentialAction
												) {

													$provider_item_MedicalWebPage['potentialAction'] = $provider_potentialAction;

												}

											// MedicalBusiness

												if (
													in_array(
														'potentialAction',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_potentialAction
												) {

														$provider_item_MedicalBusiness['potentialAction'] = $provider_potentialAction;

												}

											// Person

												if (
													in_array(
														'potentialAction',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_potentialAction
												) {

													$provider_item_Person['potentialAction'] = $provider_potentialAction;

												}

									}

								// primaryImageOfPage

									/* 
									 * Indicates the main image on the page.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - ImageObject
									 */

									 if (
										(
											in_array(
												'primaryImageOfPage',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'primaryImageOfPage',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'primaryImageOfPage',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_primaryImageOfPage) ) {

												$provider_primaryImageOfPage = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'primaryImageOfPage',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_primaryImageOfPage
												) {

													$provider_item_MedicalWebPage['primaryImageOfPage'] = $provider_primaryImageOfPage;

												}

											// MedicalBusiness

												if (
													in_array(
														'primaryImageOfPage',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_primaryImageOfPage
												) {

													$provider_item_MedicalBusiness['primaryImageOfPage'] = $provider_primaryImageOfPage;

												}

											// Person

												if (
													in_array(
														'primaryImageOfPage',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_primaryImageOfPage
												) {

													$provider_item_Person['primaryImageOfPage'] = $provider_primaryImageOfPage;

												}

									}

								// producer

									/* 
									 * The person or organization who produced the work (e.g., music album, movie, 
									 * TV/radio series).
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 *     - Person
									 */

									if (
										(
											in_array(
												'producer',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'producer',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'producer',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											// Base array

												$provider_producer = array();

											// UAMS

												if ( $schema_base_org_uams_ref ) {

													$provider_producer[] = $schema_base_org_uams_ref;

												}

											// UAMS Health

												if ( $schema_base_org_uams_health_ref ) {

													$provider_producer[] = $schema_base_org_uams_health_ref;

												}

										// Format values

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array( $provider_producer );

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'producer',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_producer
												) {

													$provider_item_MedicalWebPage['producer'] = $provider_producer;

												}

											// MedicalBusiness

												if (
													in_array(
														'producer',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_producer
												) {

													$provider_item_MedicalBusiness['producer'] = $provider_producer;

												}

											// Person

												if (
													in_array(
														'producer',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_producer
												) {

													$provider_item_Person['producer'] = $provider_producer;

												}

									}

								// provider

									/* 
									 * The service provider, service operator, or service performer; the goods 
									 * producer.
									 * 
									 * Another party (a seller) may offer those services or goods on behalf of the 
									 * provider.
									 * 
									 * A provider may also serve as the seller.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 *     - Person
									 */

									if (
										(
											in_array(
												'provider',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'provider',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'provider',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_provider) ) {

												$provider_provider = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'provider',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_provider
												) {

													$provider_item_MedicalWebPage['provider'] = $provider_provider;

												}

											// MedicalBusiness

												if (
													in_array(
														'provider',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_provider
												) {

													$provider_item_MedicalBusiness['provider'] = $provider_provider;

												}

											// Person

												if (
													in_array(
														'provider',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_provider
												) {

													$provider_item_Person['provider'] = $provider_provider;

												}

									}

								// publisher

									/* 
									 * The publisher of the creative work.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 *     - Person
									 */

									if (
										(
											in_array(
												'publisher',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'publisher',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'publisher',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											// Base array

												$provider_publisher = array();

											// UAMS

												if ( $schema_base_org_uams_ref ) {

													$provider_publisher[] = $schema_base_org_uams_ref;

												}

											// UAMS Health

												if ( $schema_base_org_uams_health_ref ) {

													$provider_publisher[] = $schema_base_org_uams_health_ref;

												}

										// Format values

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array( $provider_publisher );

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'publisher',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_publisher
												) {

													$provider_item_MedicalWebPage['publisher'] = $provider_publisher;

												}

											// MedicalBusiness

												if (
													in_array(
														'publisher',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_publisher
												) {

													$provider_item_MedicalBusiness['publisher'] = $provider_publisher;

												}

											// Person

												if (
													in_array(
														'publisher',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_publisher
												) {

													$provider_item_Person['publisher'] = $provider_publisher;

												}

									}

								// relatedLink

									/* 
									 * A link related to this web page, for example to other related web pages.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - URL
									 */

									if (
										(
											in_array(
												'relatedLink',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'relatedLink',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'relatedLink',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_relatedLink) ) {

												$provider_relatedLink = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'relatedLink',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_relatedLink
												) {

													$provider_item_MedicalWebPage['relatedLink'] = $provider_relatedLink;

												}

											// MedicalBusiness

												if (
													in_array(
														'relatedLink',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_relatedLink
												) {

													$provider_item_MedicalBusiness['relatedLink'] = $provider_relatedLink;

												}

											// Person

												if (
													in_array(
														'relatedLink',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_relatedLink
												) {

													$provider_item_Person['relatedLink'] = $provider_relatedLink;

												}

									}

								// review

									/* 
									 * A review of the item.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Review
									 */

									if (
										(
											in_array(
												'review',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'review',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'review',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_review) ) {

												$provider_review = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'review',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_review
												) {

													$provider_item_MedicalWebPage['review'] = $provider_review;

												}

											// MedicalBusiness

												if (
													in_array(
														'review',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_review
												) {

														$provider_item_MedicalBusiness['review'] = $provider_review;

												}

											// Person

												if (
													in_array(
														'review',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_review
												) {

													$provider_item_Person['review'] = $provider_review;

												}

									}

								// reviewedBy

									/* 
									 * People or organizations that have reviewed the content on this web page for 
									 * accuracy and/or completeness.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 *     - Person
									 */

									if (
										(
											in_array(
												'reviewedBy',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'reviewedBy',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'reviewedBy',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											// Base array

												$provider_reviewedBy = array();

											// UAMS

												if ( $schema_base_org_uams_ref ) {

													$provider_reviewedBy[] = $schema_base_org_uams_ref;

												}

											// UAMS Health

												if ( $schema_base_org_uams_health_ref ) {

													$provider_reviewedBy[] = $schema_base_org_uams_health_ref;

												}

										// Format values

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array( $provider_reviewedBy );

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'reviewedBy',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_reviewedBy
												) {

													$provider_item_MedicalWebPage['reviewedBy'] = $provider_reviewedBy;

												}

											// MedicalBusiness

												if (
													in_array(
														'reviewedBy',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_reviewedBy
												) {

													$provider_item_MedicalBusiness['reviewedBy'] = $provider_reviewedBy;

												}

											// Person

												if (
													in_array(
														'reviewedBy',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_reviewedBy
												) {

													$provider_item_Person['reviewedBy'] = $provider_reviewedBy;

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

									if (
										in_array(
											'sameAs',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
										||
										in_array(
											'sameAs',
											$provider_properties_map[$Person_type]['properties']
										)
									) {

										// Get sameAs repeater field value

											if ( !isset($provider_sameAs_repeater) ) {

												$provider_sameAs_repeater = get_field( 'schema_sameas', $provider );

											}

											// Add each item to sameAs property values array

												if ( $provider_sameAs_repeater ) {

													$provider_sameAs = uamswp_fad_schema_sameas(
														$provider_sameAs_repeater, // sameAs repeater field
														'schema_sameas_url' // sameAs item field name
													);

												}

										// Add to item values

											// MedicalBusiness

												if (
													in_array(
														'sameAs',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_sameAs
												) {

														$provider_item_MedicalBusiness['sameAs'] = $provider_sameAs;

												}

											// Person

												if (
													in_array(
														'sameAs',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_sameAs
												) {

													$provider_item_Person['sameAs'] = $provider_sameAs;

												}

									}

								// significantLink

									/* 
									 * One of the more significant URLs on the page. Typically, these are the 
									 * non-navigation links that are clicked on the most.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - URL
									 */

									 if (
										(
											in_array(
												'significantLink',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'significantLink',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'significantLink',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_significantLink) ) {

												$provider_significantLink = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'significantLink',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_significantLink
												) {

													$provider_item_MedicalWebPage['significantLink'] = $provider_significantLink;

												}

											// MedicalBusiness

												if (
													in_array(
														'significantLink',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_significantLink
												) {

													$provider_item_MedicalBusiness['significantLink'] = $provider_significantLink;

												}

											// Person

												if (
													in_array(
														'significantLink',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_significantLink
												) {

													$provider_item_Person['significantLink'] = $provider_significantLink;

												}

									}

								// smokingAllowed

									/* 
									 * Indicates whether it is allowed to smoke in the place (e.g., in the restaurant, 
									 * hotel or hotel room).
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Boolean
									 */

									if (
										(
											in_array(
												'smokingAllowed',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'smokingAllowed',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'smokingAllowed',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Define value

											if ( !isset($provider_smokingAllowed) ) {

												$provider_smokingAllowed = 'False';

											}

										// Add to item values

											if (
												isset($provider_smokingAllowed)
												&&
												!empty($provider_smokingAllowed)
											) {

												$provider_item['smokingAllowed'] = $provider_smokingAllowed;

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
										(
											in_array(
												'sourceOrganization',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'sourceOrganization',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'sourceOrganization',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_sourceOrganization) ) {

												$provider_sourceOrganization = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'sourceOrganization',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_sourceOrganization
												) {

													$provider_item_MedicalWebPage['sourceOrganization'] = $provider_sourceOrganization;

												}

											// MedicalBusiness

												if (
													in_array(
														'sourceOrganization',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_sourceOrganization
												) {

													$provider_item_MedicalBusiness['sourceOrganization'] = $provider_sourceOrganization;

												}

											// Person

												if (
													in_array(
														'sourceOrganization',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_sourceOrganization
												) {

													$provider_item_Person['sourceOrganization'] = $provider_sourceOrganization;

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
									 * annotated. The simplest use of speakable has (potentially relative) URL values, 
									 * referencing identified sections of the document concerned.
									 * 
									 *     2.) CSS Selectors - addresses content in the annotated page (e.g., via 
									 * class attribute). Use the cssSelector property.
									 * 
									 *     3.) XPaths - addresses content via XPaths (assuming an XML view of the 
									 * content). Use the xpath property.
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
										(
											in_array(
												'speakable',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'speakable',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'speakable',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_speakable) ) {

												$provider_speakable = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'speakable',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_speakable
												) {

													$provider_item_MedicalWebPage['speakable'] = $provider_speakable;

												}

											// MedicalBusiness

												if (
													in_array(
														'speakable',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_speakable
												) {

													$provider_item_MedicalBusiness['speakable'] = $provider_speakable;

												}

											// Person

												if (
													in_array(
														'speakable',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_speakable
												) {

													$provider_item_Person['speakable'] = $provider_speakable;

												}

									}

								// specialty

									/* 
									 * One of the domain specialities to which this web page's content applies.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Specialty
									 */

									if (
										(
											in_array(
												'specialty',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'specialty',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'specialty',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_specialty) ) {

												$provider_specialty = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'specialty',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_specialty
												) {

													$provider_item_MedicalWebPage['specialty'] = $provider_specialty;

												}

											// MedicalBusiness

												if (
													in_array(
														'specialty',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_specialty
												) {

													$provider_item_MedicalBusiness['specialty'] = $provider_specialty;

												}

											// Person

												if (
													in_array(
														'specialty',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_specialty
												) {

													$provider_item_Person['specialty'] = $provider_specialty;

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

									if (
										(
											in_array(
												'subjectOf',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'subjectOf',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_subjectOf) ) {

												if ( !isset($schema_provider_MedicalWebPage_ref) ) {

													if ( !isset($provider_url) ) {

														$provider_url = get_permalink($provider);
														$provider_url = $provider_url ? user_trailingslashit( $provider_url ) : '';

													}

													$schema_provider_MedicalWebPage_ref = $provider_url ? array( '@id' => $provider_url . '#MedicalWebPage' ) : array();
													uamswp_fad_flatten_multidimensional_array($schema_provider_MedicalWebPage_ref);

												}

												$provider_subjectOf = $schema_provider_MedicalWebPage_ref ?? '';

											}

										// Add to item values

											// MedicalBusiness

												if (
													in_array(
														'subjectOf',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_subjectOf
												) {

														$provider_item_MedicalBusiness['subjectOf'] = $provider_subjectOf;

												}

											// Person

												if (
													in_array(
														'subjectOf',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_subjectOf
												) {

													$provider_item_Person['subjectOf'] = $provider_subjectOf;

												}

									}

								// thumbnailUrl

									/* 
									 * A thumbnail image relevant to the Thing.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - URL
									 */

									 if (
										(
											in_array(
												'thumbnailUrl',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'thumbnailUrl',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'thumbnailUrl',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_thumbnailUrl) ) {

												$provider_thumbnailUrl = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'thumbnailUrl',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_thumbnailUrl
												) {

													$provider_item_MedicalWebPage['thumbnailUrl'] = $provider_thumbnailUrl;

												}

											// MedicalBusiness

												if (
													in_array(
														'thumbnailUrl',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_thumbnailUrl
												) {

													$provider_item_MedicalBusiness['thumbnailUrl'] = $provider_thumbnailUrl;

												}

											// Person

												if (
													in_array(
														'thumbnailUrl',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_thumbnailUrl
												) {

													$provider_item_Person['thumbnailUrl'] = $provider_thumbnailUrl;

												}

									}

								// timeRequired

									/* 
									 * Approximate or typical time it usually takes to work with or through the 
									 * content of this work for the typical or target audience.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Duration
									 */

									if (
										(
											in_array(
												'timeRequired',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'timeRequired',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'timeRequired',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_timeRequired) ) {

												$provider_timeRequired = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'timeRequired',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_timeRequired
												) {

													$provider_item_MedicalWebPage['timeRequired'] = $provider_timeRequired;

												}

											// MedicalBusiness

												if (
													in_array(
														'timeRequired',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_timeRequired
												) {

													$provider_item_MedicalBusiness['timeRequired'] = $provider_timeRequired;

												}

											// Person

												if (
													in_array(
														'timeRequired',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_timeRequired
												) {

													$provider_item_Person['timeRequired'] = $provider_timeRequired;

												}

									}

								// video

									/* 
									 * An embedded video object.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Clip
									 *     - VideoObject
									 */

									if (
										(
											in_array(
												'video',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
											||
											in_array(
												'video',
												$provider_properties_map[$MedicalBusiness_type]['properties']
											)
											||
											in_array(
												'video',
												$provider_properties_map[$Person_type]['properties']
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_video) ) {

												$provider_video = array();

											}

										// Add to item values

											// MedicalWebPage

												if (
													in_array(
														'video',
														$provider_properties_map[$MedicalWebPage_type]['properties']
													)
													&&
													$provider_video
												) {

													$provider_item_MedicalWebPage['video'] = $provider_video;

												}

											// MedicalBusiness

												if (
													in_array(
														'video',
														$provider_properties_map[$MedicalBusiness_type]['properties']
													)
													&&
													$provider_video
												) {

													$provider_item_MedicalBusiness['video'] = $provider_video;

												}

											// Person

												if (
													in_array(
														'video',
														$provider_properties_map[$Person_type]['properties']
													)
													&&
													$provider_video
												) {

													$provider_item_Person['video'] = $provider_video;

												}

									}

							// Sort arrays

								ksort($provider_item_MedicalWebPage);
								ksort($provider_item_MedicalBusiness);
								ksort($provider_item_Person);

							// Combine the arrays

								$provider_item = array(
									'MedicalWebPage' => $provider_item_MedicalWebPage,
									'MedicalBusiness' => $provider_item_MedicalBusiness,
									'Person' => $provider_item_Person
								);

							// Set/update the value of the item transient

								uamswp_fad_set_transient(
									'item_' . $provider, // Required // String added to transient name for disambiguation.
									$provider_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
									__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
								);

							// Add to lists of providers

								$provider_list[] = $provider_item;

								// Add to list of MedicalWebPage items

									if (
										isset($provider_item['MedicalWebPage'])
										&&
										!empty($provider_item['MedicalWebPage'])
									) {

										$MedicalWebPage_list[] = $provider_item['MedicalWebPage'];

									}

								// Add to list of MedicalBusiness items

									if (
										isset($provider_item['MedicalBusiness'])
										&&
										!empty($provider_item['MedicalBusiness'])
									) {

										$MedicalBusiness_list[] = $provider_item['MedicalBusiness'];

									}

								// Add to list of Person items

									if (
										isset($provider_item['Person'])
										&&
										!empty($provider_item['Person'])
									) {

										$Person_list[] = $provider_item['Person'];

									}

						}

					} // endforeach ( $repeater as $provider )

				// Clean up list arrays

					// MedicalWebPage

						$MedicalWebPage_list = array_filter($MedicalWebPage_list);
						$MedicalWebPage_list = array_values($MedicalWebPage_list);

						// If there is only one item, flatten the multi-dimensional array by one step

							uamswp_fad_flatten_multidimensional_array($MedicalWebPage_list);

					// MedicalBusiness

						$MedicalBusiness_list = array_filter($MedicalBusiness_list);
						$MedicalBusiness_list = array_values($MedicalBusiness_list);

						// If there is only one item, flatten the multi-dimensional array by one step

							uamswp_fad_flatten_multidimensional_array($MedicalBusiness_list);

					// Person

						$Person_list = array_filter($Person_list);
						$Person_list = array_values($Person_list);

						// If there is only one item, flatten the multi-dimensional array by one step

							uamswp_fad_flatten_multidimensional_array($Person_list);

				// Combine lists for return

					// MedicalWebPage

						if ( $MedicalWebPage_list ) {

							// Check if pre-existing list is an indexed array

								if (
									isset($provider_list['MedicalWebPage'])
									&&
									!empty($provider_list['MedicalWebPage'])
								) {

									$provider_list['MedicalWebPage'] = array_is_list($provider_list['MedicalWebPage']) ? $provider_list['MedicalWebPage'] : array($provider_list['MedicalWebPage']);

								}

							$provider_list['MedicalWebPage'] = $MedicalWebPage_list;

						}

					// MedicalBusiness

						if ( $MedicalBusiness_list ) {

							// Check if pre-existing list is an indexed array

								if (
									isset($provider_list['MedicalBusiness'])
									&&
									!empty($provider_list['MedicalBusiness'])
								) {

									$provider_list['MedicalBusiness'] = array_is_list($provider_list['MedicalBusiness']) ? $provider_list['MedicalBusiness'] : array($provider_list['MedicalBusiness']);

								}

							$provider_list['MedicalBusiness'] = $MedicalBusiness_list;

						}

					// Person

						if ( $Person_list ) {

							// Check if pre-existing list is an indexed array

								if (
									isset($provider_list['Person'])
									&&
									!empty($provider_list['Person'])
								) {

									$provider_list['Person'] = array_is_list($provider_list['Person']) ? $provider_list['Person'] : array($provider_list['Person']);

								}

							$provider_list['Person'] = $Person_list;

						}

			} // endif ( !empty($repeater) )

			return $provider_list;

		}

	// Locations (LocalBusiness)

		function uamswp_fad_schema_location(
			array $repeater, // List of IDs of the location items
			string $page_url, // Page URL
			int $nesting_level = 1, // Nesting level within the main schema
			int $LocalBusiness_i = 1, // Iteration counter
			array $LocalBusiness_fields = array(), // Pre-existing field values array so duplicate calls can be avoided
			array $LocalBusiness_list = array() // Pre-existing list array to which to add additional items
		) {

			// Common property values

				include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

				// UAMS organization values

					include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/uams.php' );

			if ( !empty($repeater) ) {

				// LocalBusiness Subtype Properties Map

					/*

					Listing the properties valid for each schema type.

					*/

					$LocalBusiness_subtype_map = array(
						'all' => array(
							'MedicalBusiness' => false,
							'properties' => array(
								'actionableFeedbackPolicy',
								'additionalProperty',
								'additionalType',
								'address',
								'aggregateRating',
								'alternateName',
								'alumni',
								'amenityFeature',
								'areaServed',
								'award',
								'awards',
								'branchCode',
								'brand',
								'contactPoint',
								'containedInPlace',
								'containsPlace',
								'correctionsPolicy',
								'currenciesAccepted',
								'department',
								'description',
								'disambiguatingDescription',
								'dissolutionDate',
								'diversityPolicy',
								'diversityStaffingReport',
								'duns',
								'email',
								'employee',
								'ethicsPolicy',
								'event',
								'events',
								'faxNumber',
								'founder',
								'foundingDate',
								'foundingLocation',
								'funder',
								'funding',
								'geo',
								'geoContains',
								'geoCoveredBy',
								'geoCovers',
								'geoCrosses',
								'geoDisjoint',
								'geoEquals',
								'geoIntersects',
								'geoOverlaps',
								'geoTouches',
								'geoWithin',
								'globalLocationNumber',
								'hasCredential',
								'hasDriveThroughService',
								'hasMap',
								'hasMerchantReturnPolicy',
								'hasOfferCatalog',
								'hasPOS',
								'hasProductReturnPolicy',
								'identifier',
								'image',
								'interactionStatistic',
								'isAccessibleForFree',
								'isicV4',
								'iso6523Code',
								'keywords',
								'knowsAbout',
								'knowsLanguage',
								'latitude',
								'legalName',
								'leiCode',
								'location',
								'logo',
								'longitude',
								'mainEntityOfPage',
								'makesOffer',
								'map',
								'maps',
								'maximumAttendeeCapacity',
								'member',
								'memberOf',
								'naics',
								'name',
								'nonprofitStatus',
								'numberOfEmployees',
								'openingHours',
								'openingHoursSpecification',
								'ownershipFundingInfo',
								'owns',
								'parentOrganization',
								'paymentAccepted',
								'photo',
								'potentialAction',
								'priceRange',
								'publicAccess',
								'publishingPrinciples',
								'review',
								'sameAs',
								'seeks',
								'serviceArea',
								'slogan',
								'smokingAllowed',
								'specialOpeningHoursSpecification',
								'sponsor',
								'subjectOf',
								'subOrganization',
								'taxID',
								'telephone',
								'tourBookingPage',
								'unnamedSourcesPolicy',
								'url',
								'vatID'
							)
						),
						'Hospital' => array(
							'MedicalBusiness' => false,
							'properties' => array(
								'availableService',
								'healthPlanNetworkId',
								'healthcareReportingData',
								'isAcceptingNewPatients',
								'medicalSpecialty'
							)
						),
						'MedicalBusiness' => array(
							'MedicalBusiness' => true,
							'properties' => array()
						),
						'MedicalClinic' => array(
							'MedicalBusiness' => true,
							'properties' => array(
								'availableService',
								'healthPlanNetworkId',
								'isAcceptingNewPatients',
								'medicalSpecialty'
							)
						),
						'Pharmacy' => array(
							'MedicalBusiness' => true,
							'properties' => array(
								'healthPlanNetworkId',
								'isAcceptingNewPatients',
								'medicalSpecialty'
							)
						)
					);

					// Merge common property values into each LocalBusiness Subtype's property values

						foreach ( $LocalBusiness_subtype_map as &$item ) {

							if ( $item != 'all ') {

								$item['properties'] = array_merge(
									$item['properties'],
									$LocalBusiness_subtype_map['all']['properties']
								);

							}
						}

				// LocalBusiness additionalType MedicalSpecialty values

					/*

					Listing the MedicalSpecialty enumeration members that are also 
					subtypes of the MedicalBusiness type.

					*/

					$LocalBusiness_additionalType_MedicalSpecialty = array(
						'https://schema.org/CommunityHealth/',
						'https://schema.org/Dermatology/',
						'https://schema.org/DietNutrition/',
						'https://schema.org/Emergency/',
						'https://schema.org/Geriatric/',
						'https://schema.org/Gynecologic/',
						'https://schema.org/Midwifery/',
						'https://schema.org/Nursing/',
						'https://schema.org/Obstetric/',
						'https://schema.org/Oncologic/',
						'https://schema.org/Optometric/',
						'https://schema.org/Otolaryngologic/',
						'https://schema.org/Pediatric/',
						'https://schema.org/Physiotherapy/',
						'https://schema.org/PlasticSurgery/',
						'https://schema.org/Podiatric/',
						'https://schema.org/PrimaryCare/',
						'https://schema.org/Psychiatric/',
						'https://schema.org/PublicHealth/'
					);

				// Loop through each location to add values

					foreach ( $repeater as $LocalBusiness ) {

						// Retrieve the value of the item transient

							uamswp_fad_get_transient(
								'item_' . $LocalBusiness, // Required // String added to transient name for disambiguation.
								$LocalBusiness_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
								__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
							);

						if ( !empty( $LocalBusiness_item ) ) {

							/* 
							 * The transient exists.
							 * Return the variable.
							 */

							// Add to list of areas of expertise

								$LocalBusiness_list[] = $LocalBusiness_item;

						} else {

							// If post is not published, skip to the next iteration

								if ( get_post_status($LocalBusiness) != 'publish' ) {

									continue;

								}

							// Eliminate PHP errors / reset variables

								$LocalBusiness_item = array(); // Base array
								$LocalBusiness_additionalType = null;
								$LocalBusiness_additionalType_field = null;
								$LocalBusiness_additionalType_repeater = null;
								$LocalBusiness_address = null;
								$LocalBusiness_address_1 = null;
								$LocalBusiness_address_2_array = null;
								$LocalBusiness_addressLocality = null;
								$LocalBusiness_addressRegion = null;
								$LocalBusiness_aggregateRating = null;
								$LocalBusiness_alternateName = null;
								$LocalBusiness_alternateName_repeater = null;
								$LocalBusiness_areaServed = null;
								$LocalBusiness_availableService = null;
								$LocalBusiness_award = null;
								$LocalBusiness_brand = null;
								$LocalBusiness_building = null;
								$LocalBusiness_building_name = null;
								$LocalBusiness_building_slug = null;
								$LocalBusiness_building_term = null;
								$LocalBusiness_contactPoint = null;
								$LocalBusiness_containedInPlace = null;
								$LocalBusiness_containsPlace = null;
								$LocalBusiness_currenciesAccepted = null;
								$LocalBusiness_current_fpage = null;
								$LocalBusiness_department = null;
								$LocalBusiness_description = null;
								$LocalBusiness_diversityPolicy = null;
								$LocalBusiness_diversityStaffingReport = null;
								$LocalBusiness_duns = null;
								$LocalBusiness_employee = null;
								$LocalBusiness_ethicsPolicy = null;
								$LocalBusiness_event = null;
								$LocalBusiness_featured_image_id = null;
								$LocalBusiness_floor = null;
								$LocalBusiness_floor_label = null;
								$LocalBusiness_floor_value = null;
								$LocalBusiness_foundingDate = null;
								$LocalBusiness_fpage_query = null;
								$LocalBusiness_funding = null;
								$LocalBusiness_gallery_image_id = null;
								$LocalBusiness_geo = null;
								$LocalBusiness_geo_value = null;
								$LocalBusiness_globalLocationNumber = null;
								$LocalBusiness_has_parent = null;
								$LocalBusiness_hasCredential = null;
								$LocalBusiness_hasDriveThroughService = null;
								$LocalBusiness_hasMap = null;
								$LocalBusiness_id = null;
								$LocalBusiness_identifier = null;
								$LocalBusiness_image = null;
								$LocalBusiness_image_general = null;
								$LocalBusiness_image_id = null;
								$LocalBusiness_isAcceptingNewPatients = null;
								$LocalBusiness_isAccessibleForFree = null;
								$LocalBusiness_isicV4 = null;
								$LocalBusiness_iso6523Code = null;
								$LocalBusiness_keywords = null;
								$LocalBusiness_knowsAbout = null;
								$LocalBusiness_knowsLanguage = null;
								$LocalBusiness_legalName = null;
								$LocalBusiness_leiCode = null;
								$LocalBusiness_logo = null;
								$LocalBusiness_mainEntityOfPage = null;
								$LocalBusiness_makesOffer = null;
								$LocalBusiness_maximumAttendeeCapacity = null;
								$LocalBusiness_medicalSpecialty_multiselect = null;
								$LocalBusiness_medicalSpecialty = null;
								$LocalBusiness_memberOf = null;
								$LocalBusiness_naics = null;
								$LocalBusiness_name = null;
								$LocalBusiness_nonprofitStatus = null;
								$LocalBusiness_numberOfEmployees = null;
								$LocalBusiness_ontology_type = null;
								$LocalBusiness_openingHours = null;
								$LocalBusiness_openingHoursSpecification = null;
								$LocalBusiness_override_parent_photo = null;
								$LocalBusiness_override_parent_photo_featured = null;
								$LocalBusiness_override_parent_photo_gallery = null;
								$LocalBusiness_override_parent_photo_wayfinding = null;
								$LocalBusiness_parent_id = null;
								$LocalBusiness_parentOrganization = null;
								$LocalBusiness_paymentAccepted = null;
								$LocalBusiness_photo = null;
								$LocalBusiness_postalCode = null;
								$LocalBusiness_potentialAction = null;
								$LocalBusiness_publicAccess = null;
								$LocalBusiness_review = null;
								$LocalBusiness_sameAs = null;
								$LocalBusiness_sameAs_repeater = null;
								$LocalBusiness_specialOpeningHoursSpecification = null;
								$LocalBusiness_streetAddress = null;
								$LocalBusiness_streetAddress_array = null;
								$LocalBusiness_subjectOf = null;
								$LocalBusiness_subOrganization = null;
								$LocalBusiness_suite = null;
								$LocalBusiness_taxID = null;
								$LocalBusiness_taxID_taxpayer = null;
								$LocalBusiness_taxID_employer = null;
								$LocalBusiness_treatments = null;
								$LocalBusiness_type = null;
								$LocalBusiness_url = null;
								$LocalBusiness_vatID = null;
								$LocalBusiness_wayfinding_image_id = null;

								// Reused variables

									$LocalBusiness_smokingAllowed = 'False';

							// Load variables from pre-existing field values array

								if ( $LocalBusiness_fields[$LocalBusiness] ) {

									foreach ( $LocalBusiness_fields[$LocalBusiness] as $key => $value ) {

										${$key} = $value; // Create a variable for each item in the array

									}

								}

							// Get ontology type

								if ( !isset($LocalBusiness_ontology_type) ) {

									$LocalBusiness_ontology_type = true;

								}

							// If the page is not an ontology type, skip to the next iteration

								if ( !$LocalBusiness_ontology_type ) {

									continue;

								}

							// Fake subpage query and get fake subpage slug

								if (
									$LocalBusiness_ontology_type
									&&
									$nesting_level == 0
								) {

									if ( !isset($LocalBusiness_current_fpage) ) {

										$LocalBusiness_current_fpage = get_query_var( 'fpage' ) ?? ''; // Fake subpage slug

									}

									if ( !isset($LocalBusiness_fpage_query) ) {

										$LocalBusiness_fpage_query = $LocalBusiness_current_fpage ? true : false;

									}

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

									// Get values

										if ( !isset($LocalBusiness_url) ) {

											$LocalBusiness_url = get_permalink($LocalBusiness);

										}

										$LocalBusiness_url = $LocalBusiness_url ? user_trailingslashit( $LocalBusiness_url ) : '';

									// Add to schema

										if ( $LocalBusiness_url ) {

											$LocalBusiness_item['url'] = $LocalBusiness_url;

										}

								// @type

									// Get values

										// LocalBusiness Subtype

											if ( !isset($LocalBusiness_type) ) {

												$LocalBusiness_type = get_field( 'schema_localbusiness_single', $LocalBusiness ) ?? '';

											}

											$LocalBusiness_type = is_array($LocalBusiness_type) ? reset($LocalBusiness_type) : $LocalBusiness_type;

											// Fallback value

												if (
													!$LocalBusiness_type
													||
													!array_key_exists( $LocalBusiness_type, $LocalBusiness_subtype_map )
												) {

													$LocalBusiness_type = 'MedicalBusiness';

												}

									// Add to schema

										$LocalBusiness_item['@type'] = $LocalBusiness_type;

								// @id

									if ( $nesting_level <= 1 ) {

										// Get values

											$LocalBusiness_id = $LocalBusiness_url . '#' . $LocalBusiness_type;
											// $LocalBusiness_id .= $LocalBusiness_i;
											// $LocalBusiness_id++;

										// Add to schema

											$LocalBusiness_item['@id'] = $LocalBusiness_id;

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

										if ( !isset($LocalBusiness_name) ) {

											$LocalBusiness_name = get_the_title($LocalBusiness) ?? '';

										}

									// Add to item values

										if ( $LocalBusiness_name ) {

											$LocalBusiness_item['name'] = $LocalBusiness_name;

										}

								// medicalSpecialty

									/* 
									 * A medical specialty of the provider.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - MedicalSpecialty
									 */

									if (
										in_array(
											'medicalSpecialty',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get values

											// Get medicalSpecialty multiselect field value

												if ( !isset($LocalBusiness_medicalSpecialty_multiselect) ) {

													$LocalBusiness_medicalSpecialty_multiselect = get_field( 'schema_medicalspecialty_multiple', $LocalBusiness ) ?? array();

												}

											// Format value

												if (
													!isset($LocalBusiness_medicalSpecialty_list)
													||
													!isset($LocalBusiness_medicalSpecialty)
												) {

													// Simple list of MedicalSpecialty values

														$LocalBusiness_medicalSpecialty_list = array();

													// Schema property values

														if ( $LocalBusiness_medicalSpecialty_multiselect ) {

															$LocalBusiness_medicalSpecialty = uamswp_fad_schema_medicalSpecialty_select(
																$LocalBusiness_medicalSpecialty_multiselect, // mixed // Required // MedicalSpecialty select or multi-select field value
																$LocalBusiness_medicalSpecialty_list // Optional // Array to populate with the list of MedicalSpecialty values
															);

														}

												}

										// Add to item values

											if (
												$LocalBusiness_medicalSpecialty
												&&
												$nesting_level == 0
											) {

												$LocalBusiness_item['medicalSpecialty'] = $LocalBusiness_medicalSpecialty;

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

									if (
										in_array(
											'additionalType',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get values

											if ( !isset($LocalBusiness_additionalType) ) {

												// Base property values array

													$LocalBusiness_additionalType = array();

												// Get medicalSpecialty values that match MedicalBusiness subtypes and add to property values

													if ( !isset($LocalBusiness_medicalSpecialty_list) ) {

														// Get medicalSpecialty multiselect field value

															if ( !isset($LocalBusiness_medicalSpecialty_multiselect) ) {

																$LocalBusiness_medicalSpecialty_multiselect = get_field( 'schema_medicalspecialty_multiple', $LocalBusiness ) ?? array();

															}

														// Format value

															// Simple list of MedicalSpecialty values

																$LocalBusiness_medicalSpecialty_list = array();

															// Schema property values

																if ( $LocalBusiness_medicalSpecialty_multiselect ) {

																	$LocalBusiness_medicalSpecialty = uamswp_fad_schema_medicalSpecialty_select(
																		$LocalBusiness_medicalSpecialty_multiselect, // mixed // Required // MedicalSpecialty select or multi-select field value
																		$LocalBusiness_medicalSpecialty_list // Optional // Array to populate with the list of MedicalSpecialty values
																	);

																}

													}

													if ( $LocalBusiness_medicalSpecialty_list ) {

														$LocalBusiness_additionalType = array_merge(
															$LocalBusiness_additionalType,
															array_intersect(
																$LocalBusiness_additionalType_MedicalSpecialty,
																( is_array($LocalBusiness_medicalSpecialty_list) ? $LocalBusiness_medicalSpecialty_list : array($LocalBusiness_medicalSpecialty_list) )
															)
														);

													}

												// Get additionalType repeater field value

													if ( !isset($LocalBusiness_additionalType_repeater) ) {

														$LocalBusiness_additionalType_repeater = get_field( 'schema_additionalType', $LocalBusiness ) ?? array();

													}

													// Add each item to an array

														if ( !isset($LocalBusiness_additionalType_field) ) {

															if ( $LocalBusiness_additionalType_repeater ) {

																$LocalBusiness_additionalType_field = uamswp_fad_schema_additionaltype(
																	$LocalBusiness_additionalType_repeater, // additionalType repeater field
																	'schema_additionalType_uri' // additionalType item field name
																);

															}

														}

													// Merge array into the additionalType property values array

														if (
															$LocalBusiness_additionalType
															&&
															$LocalBusiness_additionalType_field
														) {

															$LocalBusiness_additionalType_field = is_array($LocalBusiness_additionalType_field) ? $LocalBusiness_additionalType_field : array($LocalBusiness_additionalType_field);

															$LocalBusiness_additionalType = array_merge(
																$LocalBusiness_additionalType,
																$LocalBusiness_additionalType_field
															);

														} elseif ( $LocalBusiness_additionalType_field ) {

															$LocalBusiness_additionalType = $LocalBusiness_additionalType_field;

														}

											}

										// Clean up additionalType property values array

											if (
												$LocalBusiness_additionalType
												&&
												is_array($LocalBusiness_additionalType)
											) {

												// If there is only one item, flatten the multi-dimensional array by one step

													uamswp_fad_flatten_multidimensional_array($LocalBusiness_additionalType);

											}

										// Add to item values

											if ( $LocalBusiness_additionalType ) {

												$LocalBusiness_item['additionalType'] = $LocalBusiness_additionalType;

											}

									}

								// Parent location information (common use)

									if (
										in_array(
											'address',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										||
										in_array(
											'image',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										||
										in_array(
											'photo',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Query for whether the location has a parent

											if (
												!isset($LocalBusiness_has_parent)
												||
												(
													$LocalBusiness_has_parent
													&&
													!isset($LocalBusiness_parent_id)
												)
											) {

												$LocalBusiness_has_parent = get_field( 'location_parent', $LocalBusiness );
												$LocalBusiness_parent_id = $LocalBusiness_has_parent ? get_field( 'location_parent_id', $LocalBusiness ) : '';
												$LocalBusiness_has_parent = $LocalBusiness_parent_id ? true : false;

											}

									}

								// address

									/* 
									 * Physical address of the item.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - PostalAddress
									 *     - Text
									 */

									if (
										in_array(
											'address',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Base array

											$LocalBusiness_streetAddress_array = array();

										// Get values

											// Conditionally get parent location ID

												if ( $LocalBusiness_has_parent ) {

													$LocalBusiness_address_id = $LocalBusiness_parent_id;

												} else {

													$LocalBusiness_address_id = $LocalBusiness;

												}

											// Address line 1

												if ( !isset($LocalBusiness_address_1) ) {

													$LocalBusiness_address_1 = get_field( 'location_address_1', $LocalBusiness_address_id ) ?? '';

												}

												if ( $LocalBusiness_address_1 ) {

													$LocalBusiness_streetAddress_array[] = $LocalBusiness_address_1;

												}

											// Address line 2

												// Base array

													$LocalBusiness_address_2_array = array();

												// Building values

													if ( !isset($LocalBusiness_building_name) ) {

														$LocalBusiness_building = get_field( 'location_building', $LocalBusiness_address_id ) ?? '';

														if ( $LocalBusiness_building ) {

															$LocalBusiness_building_term = get_term( $LocalBusiness_building, 'building' ) ?? '';

															if ( $LocalBusiness_building_term ) {

																$LocalBusiness_building_slug = $LocalBusiness_building_term->slug;
																$LocalBusiness_building_name = $LocalBusiness_building_term->name;

																if ( $LocalBusiness_building_slug != '_none' ) {

																	$LocalBusiness_building_name = '';

																}

															}

														}

													}

													// Add to the address 2 array

														if ( $LocalBusiness_building_name ) {

															$LocalBusiness_address_2_array[] = $LocalBusiness_building_name;

														}

												// Floor values

													if ( !isset($LocalBusiness_floor_label) ) {

														$LocalBusiness_floor = get_field_object( 'location_building_floor', $LocalBusiness_address_id ) ?? array();

														if (
															$LocalBusiness_floor
															&&
															array_key_exists( 'value', $LocalBusiness_floor )
															&&
															array_key_exists( 'choices', $LocalBusiness_floor )
														) {

															// Floor label

																$LocalBusiness_floor_value = $LocalBusiness_floor['value'];

																// Check floor value

																	if (
																		$LocalBusiness_floor_value == '0'
																		||
																		$LocalBusiness_floor_value == 'false'
																		||
																		!$LocalBusiness_floor_value
																	) {
																		$LocalBusiness_floor_value = '';
																	}

															// Floor label

																$LocalBusiness_floor_label = $LocalBusiness_floor_value ? $LocalBusiness_floor['choices'][$LocalBusiness_floor_value] : '';

														}

													}

													// Add to the address 2 array

														if ( $LocalBusiness_floor_label ) {

															$LocalBusiness_address_2_array[] = $LocalBusiness_floor_label;

														}

												// Suite value

													if ( !isset($LocalBusiness_suite) ) {

														$LocalBusiness_suite = get_field(' location_suite', $LocalBusiness_address_id ) ?? '';

													}

													// Add to the address 2 array

														if ( $LocalBusiness_suite ) {

															$LocalBusiness_address_2_array[] = $LocalBusiness_suite;

														}

												// Explode the array and add to streetAddress values array

													if ( $LocalBusiness_address_2_array ) {

														$LocalBusiness_streetAddress_array[] = implode(
															' ',
															$LocalBusiness_address_2_array
														);

													}

											// Combine the lines

												if ( $LocalBusiness_streetAddress_array ) {

													$LocalBusiness_streetAddress = implode(
														' ',
														$LocalBusiness_streetAddress_array
													);

												}

											// City

												if ( !isset($LocalBusiness_addressLocality) ) {

													$LocalBusiness_addressLocality = get_field( 'location_city', $LocalBusiness_address_id ) ?? '';

												}

											// State

												if ( !isset($LocalBusiness_addressRegion) ) {

													$LocalBusiness_addressRegion = get_field( 'location_state', $LocalBusiness_address_id ) ?? '';

												}

											// ZIP

												if ( !isset($LocalBusiness_postalCode) ) {

													$LocalBusiness_postalCode = get_field( 'location_zip', $LocalBusiness_address_id ) ?? '';

												}

										// Format values

											if ( !isset($LocalBusiness_address) ) {

												if (
													$LocalBusiness_streetAddress
													&&
													$LocalBusiness_addressLocality
													&&
													$LocalBusiness_addressRegion
													&&
													$LocalBusiness_postalCode
												) {

													$LocalBusiness_address = uamswp_fad_schema_postaladdress(
														$LocalBusiness_streetAddress, // string // Required // The street address or the post office box number for PO box addresses.
														true, // bool // Required // Query for whether the address is a street address (as opposed to a post office box number)
														$LocalBusiness_addressLocality, // string // Required // The locality in which the street address is, and which is in the region. For example, Mountain View.
														$LocalBusiness_addressRegion, // string // Required // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
														$LocalBusiness_postalCode // string // Required // The postal code (e.g., 94043).
													);

												}

											}

										// Add to item values

											if ( $LocalBusiness_address ) {

												$LocalBusiness_item['address'] = $LocalBusiness_address;

											}

									}

								// aggregateRating

									/* 
									 * The overall rating, based on a collection of reviews or ratings, of the item.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - AggregateRating
									 */

									if (
										in_array(
											'aggregateRating',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_aggregateRating) ) {

												$LocalBusiness_aggregateRating = array();

											}

										// Add to item values

											if ( $LocalBusiness_aggregateRating ) {

												$LocalBusiness_item['aggregateRating'] = $LocalBusiness_aggregateRating;

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
										in_array(
											'alternateName',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get alternateName repeater field value

											if ( !isset($LocalBusiness_alternateName_repeater) ) {

												$LocalBusiness_alternateName_repeater = get_field( 'schema_alternatename', $LocalBusiness ) ?: array();

											}

											// Add each item to alternateName property values array

												if ( !isset($LocalBusiness_alternateName) ) {

													if ( $LocalBusiness_alternateName_repeater ) {

														$LocalBusiness_alternateName = uamswp_fad_schema_alternatename(
															$LocalBusiness_alternateName_repeater, // alternateName repeater field
															'schema_alternatename_text' // alternateName item field name
														);

													}

												}

										// Add to schema

											if ( $LocalBusiness_alternateName ) {

												$LocalBusiness_item['alternateName'] = $LocalBusiness_alternateName;

											}

									}

								// areaServed

									/* 
									 * The geographic area where a service or offered item is provided.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - AdministrativeArea
									 *     - GeoShape
									 *     - Place
									 *     - Text
									 */

									if (
										in_array(
											'areaServed',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_areaServed) ) {

												$LocalBusiness_areaServed = array();

											}

										// Add to item values

											if ( $LocalBusiness_areaServed ) {

												$LocalBusiness_item['areaServed'] = $LocalBusiness_areaServed;

											}

									}

								// availableService

									/* 
									 * A medical service available from this provider.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - MedicalProcedure
									 *     - MedicalTest
									 */

									if (
										in_array(
											'availableService',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get related treatments

											if ( !isset($LocalBusiness_treatments) ) {

												$LocalBusiness_treatments = get_field( 'location_treatments_cpt', $LocalBusiness ) ?: array();

											}

										// Format values

											if ( !isset($LocalBusiness_availableService) ) {

												if ( $LocalBusiness_treatments ) {

													$LocalBusiness_availableService = uamswp_fad_schema_service(
														$LocalBusiness_treatments, // List of IDs of the service items
														$LocalBusiness_url, // Page URL
														( $nesting_level + 1 ), // Nesting level within the main schema
														'Service' // Fragment identifier
													) ?? array();

												}

											}

										// Add to item values

											if ( $LocalBusiness_availableService ) {

												$LocalBusiness_item['availableService'] = $LocalBusiness_availableService;

											}

									}

								// award

									/* 
									 * An award won by or for this item.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										in_array(
											'award',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_award) ) {

												$LocalBusiness_award = array();

											}

										// Add to item values

											if ( $LocalBusiness_award ) {

												$LocalBusiness_item['award'] = $LocalBusiness_award;

											}

									}

								// brand

									/* 
									 * The brand(s) associated with a product or service, or the brand(s) maintained 
									 * by an organization or business person.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Brand
									 *     - Organization
									 */

									if (
										in_array(
											'brand',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get values

											if ( !isset($LocalBusiness_brand) ) {

												$LocalBusiness_brand = $schema_base_org_uams_health_ref ?? array();

											}

										// Add to item values

											if ( $LocalBusiness_brand ) {

												$LocalBusiness_item['brand'] = $LocalBusiness_brand;

											}

									}

								// contactPoint

									/* 
									 * A contact point for a person or organization.
									 * 
									 *     - ContactPoint
									 */

									/*

										email
										faxNumber
										telephone

									*/

									if (
										in_array(
											'contactPoint',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get values

											if ( !isset($LocalBusiness_contactPoint) ) {

												$LocalBusiness_contactPoint = array();

											}

										// Add to item values

											if ( $LocalBusiness_contactPoint ) {

												$LocalBusiness_item['contactPoint'] = $LocalBusiness_contactPoint;

											}

									}

								// containedInPlace

									/* 
									 * The basic containment relation between a place and one that contains it.
									 * expected to be one of these types:
									 * 
									 *     - Place
									 */

									if (
										in_array(
											'containedInPlace',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get values

											if ( !isset($LocalBusiness_containedInPlace) ) {

												$LocalBusiness_containedInPlace = array();

											}

										// Add to item values

											if ( $LocalBusiness_containedInPlace ) {

												$LocalBusiness_item['containedInPlace'] = $LocalBusiness_containedInPlace;

											}

									}

								// containsPlace

									/* 
									 * The basic containment relation between a place and another that it contains.
									 * 
									 * Inverse property: 'containedInPlace'
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Place
									 */

									if (
										in_array(
											'containsPlace',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										/*

											 * Provider as 'Physician' type
											 * Provider as 'Dentist' type
											 * Provider as 'Optician' type
											 * Descendant locations (LocalBusiness subtypes)

										*/

										// Get values

											if ( !isset($LocalBusiness_containsPlace) ) {

												$LocalBusiness_containsPlace = array();

											}

										// Add to item values

											if ( $LocalBusiness_containsPlace ) {

												$LocalBusiness_item['containsPlace'] = $LocalBusiness_containsPlace;

											}

									}

								// currenciesAccepted

									/* 
									 * The currency accepted.
									 * 
									 * Use standard formats:
									 *     - ISO 4217 currency format (e.g., "USD")
									 *     - Ticker symbol for cryptocurrencies (e.g., "BTC")
									 *     - Well-known names for Local Exchange Trading Systems (LETS) and other 
									 *       currency types (e.g., "Ithaca HOUR")
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										in_array(
											'currenciesAccepted',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_currenciesAccepted) ) {

												$LocalBusiness_currenciesAccepted = array();

											}

										// Add to item values

											if ( $LocalBusiness_currenciesAccepted ) {

												$LocalBusiness_item['currenciesAccepted'] = $LocalBusiness_currenciesAccepted;

											}

									}

								// department

									/* 
									 * A relationship between an organization and a department of that organization, 
									 * also described as an organization (allowing different urls, logos, opening 
									 * hours). For example: a store with a pharmacy, or a bakery with a cafe.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 */

									if (
										in_array(
											'department',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_department) ) {

												$LocalBusiness_department = $LocalBusiness_containsPlace ?? array();

											}

										// Add to item values

											if ( $LocalBusiness_department ) {

												$LocalBusiness_item['department'] = $LocalBusiness_department;

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

									if (
										in_array(
											'description',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get the Selected Short Description for This Page

											if ( !isset($LocalBusiness_description) ) {

												$LocalBusiness_description = get_field( 'location_short_desc', $LocalBusiness ) ?? array();

												// Fallback values

													if ( !$LocalBusiness_description ) {

														// Get the full description

															$LocalBusiness_description = get_field( 'location_about', $LocalBusiness ) ?? array();

													}

											}

										// Clean up value

											if ( $LocalBusiness_description ) {

												$LocalBusiness_description = wp_strip_all_tags($LocalBusiness_description);
												$LocalBusiness_description = str_replace("\n", ' ', $LocalBusiness_description); // Strip line breaks
												$LocalBusiness_description = strlen($LocalBusiness_description) > 160 ? mb_strimwidth($LocalBusiness_description, 0, 156, '...') : $LocalBusiness_description; // Limit to 160 characters

											}

										// Add to item values

											if ( $LocalBusiness_description ) {

												$LocalBusiness_item['description'] = $LocalBusiness_description;

											}

									}

								// diversityPolicy

									/* 
									 * Statement on diversity policy by an Organization 
									 * (e.g., a NewsMediaOrganization).
									 * 
									 * For a NewsMediaOrganization, a statement  describing the newsroom’s diversity 
									 * policy on both staffing and sources, typically providing staffing data.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - CreativeWork
									 *     - URL
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										in_array(
											'diversityPolicy',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_diversityPolicy) ) {

												$LocalBusiness_diversityPolicy = array();

											}

										// Add to item values

											if ( $LocalBusiness_diversityPolicy ) {

												$LocalBusiness_item['diversityPolicy'] = $LocalBusiness_diversityPolicy;

											}

									}

								// diversityStaffingReport

									/* 
									 * For an Organization (often but not necessarily a NewsMediaOrganization), a 
									 * report on staffing diversity issues. In a news context this might be for 
									 * example ASNE or RTDNA (US) reports, or self-reported.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Article
									 *     - URL
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										in_array(
											'diversityStaffingReport',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_diversityStaffingReport) ) {

												$LocalBusiness_diversityStaffingReport = array();

											}

										// Add to item values

											if ( $LocalBusiness_diversityStaffingReport ) {

												$LocalBusiness_item['diversityStaffingReport'] = $LocalBusiness_diversityStaffingReport;

											}

									}

								// employee

									/* 
									 * Someone working for this organization.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Person
									 */

									if (
										in_array(
											'employee',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_employee) ) {

												$LocalBusiness_employee = array();

											}

										// Add to item values

											if ( $LocalBusiness_employee ) {

												$LocalBusiness_item['employee'] = $LocalBusiness_employee;

											}

									}

								// ethicsPolicy

									/* 
									 * Statement about ethics policy, (e.g., journalistic and publishing practices of 
									 * a NewsMediaOrganization; food source policies of a Restaurant).
									 * 
									 * In the case of a NewsMediaOrganization, an ethicsPolicy is typically a 
									 * statement describing the personal, organizational, and corporate standards of 
									 * behavior expected by the organization.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - CreativeWork
									 *     - URL
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										in_array(
											'ethicsPolicy',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_ethicsPolicy) ) {

												$LocalBusiness_ethicsPolicy = array();

											}

										// Add to item values

											if ( $LocalBusiness_ethicsPolicy ) {

												$LocalBusiness_item['ethicsPolicy'] = $LocalBusiness_ethicsPolicy;

											}

									}

								// event

									/* 
									 * Upcoming or past event associated with this place, organization, or action.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Event
									 */

									if (
										in_array(
											'event',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_event) ) {

												$LocalBusiness_event = array();

											}

										// Add to item values

											if ( $LocalBusiness_event ) {

												$LocalBusiness_item['event'] = $LocalBusiness_event;

											}

									}

								// foundingDate

									/* 
									 * The date that this organization was founded.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Date
									 */

									if (
										in_array(
											'foundingDate',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_foundingDate) ) {

												$LocalBusiness_foundingDate = array();

											}

										// Add to item values

											if ( $LocalBusiness_foundingDate ) {

												$LocalBusiness_item['foundingDate'] = $LocalBusiness_foundingDate;

											}

									}

								// funding

									/* 
									 * A Grant that directly or indirectly provide funding or sponsorship for this 
									 * item. See also ownershipFundingInfo.
									 * 
									 * Inverse-property: fundedItem
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Grant
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										in_array(
											'funding',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_funding) ) {

												$LocalBusiness_funding = array();

											}

										// Add to item values

											if ( $LocalBusiness_funding ) {

												$LocalBusiness_item['funding'] = $LocalBusiness_funding;

											}

									}

								// geo (common use)

									if (
										in_array(
											'geo',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										||
										in_array(
											'latitude',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										||
										in_array(
											'longitude',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get values

											if ( !isset($LocalBusiness_geo_value) ) {

												$LocalBusiness_geo_value = get_field( 'location_map', $LocalBusiness ) ?? array();

											}

											// Check values

												if ( $LocalBusiness_geo_value ) {

													$LocalBusiness_geo_value = ( array_key_exists( 'lat', $LocalBusiness_geo_value ) && array_key_exists( 'lng', $LocalBusiness_geo_value ) ) ? $LocalBusiness_geo_value : array();

												}

									}

								// geo (specific property)

									/* 
									 * The geo coordinates of the place.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - GeoCoordinates
									 *     - GeoShape
									 */

									if (
										in_array(
											'geo',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Format values

											if ( !isset($LocalBusiness_geo) ) {

												if ( $LocalBusiness_geo_value ) {

													$LocalBusiness_geo = uamswp_schema_geo_coordinates(
														$LocalBusiness_geo_value['lat'], // string // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
														$LocalBusiness_geo_value['lng'] // string // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
													);

												}

											}

										// Add to item values

											if ( $LocalBusiness_geo ) {

												$LocalBusiness_item['geo'] = $LocalBusiness_geo;

											}

									}

								// hasCredential

									/* 
									 * A credential awarded to the Person or Organization.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - EducationalOccupationalCredential
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										in_array(
											'hasCredential',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_hasCredential) ) {

												$LocalBusiness_hasCredential = array();

											}

										// Add to item values

											if ( $LocalBusiness_hasCredential ) {

												$LocalBusiness_item['hasCredential'] = $LocalBusiness_hasCredential;

											}

									}

								// hasDriveThroughService

									/* 
									 * Indicates whether some facility (e.g., FoodEstablishment, CovidTestingFacility) 
									 * offers a service that can be used by driving through in a car.
									 * 
									 * In the case of CovidTestingFacility such facilities could potentially help with 
									 * social distancing from other potentially-infected users.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Boolean
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										in_array(
											'hasDriveThroughService',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_hasDriveThroughService) ) {

												$LocalBusiness_hasDriveThroughService = array();

											}

										// Add to item values

											if ( $LocalBusiness_hasDriveThroughService ) {

												$LocalBusiness_item['hasDriveThroughService'] = $LocalBusiness_hasDriveThroughService;

											}

									}

								// hasMap

									/* 
									 * A URL to a map of the place.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Map
									 *     - URL
									 * 
									 * The examples on Schema.org indicate that a URL to the location on Google Maps 
									 * is acceptable.
									 */

									if (
										in_array(
											'hasMap',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get values

											if ( !isset($LocalBusiness_hasMap) ) {

												$LocalBusiness_hasMap = get_field( 'schema_google_cid', $LocalBusiness ) ?? array();

											}

										// Format values

											if ( $LocalBusiness_hasMap ) {

												$LocalBusiness_hasMap = 'https://www.google.com/maps?cid=' . $LocalBusiness_hasMap;

											}

										// Add to item values

											if ( $LocalBusiness_hasMap ) {

												$LocalBusiness_item['hasMap'] = $LocalBusiness_hasMap;

											}

									}

								// identifiers (multiple properties)

									if ( $nesting_level == 0 ) {

										// 'duns' property

											/* 
											 * The Dun & Bradstreet DUNS number for identifying an organization or business 
											 * person.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											// Define values

												if (
													in_array(
														'duns',
														$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
													)
													&&
													$nesting_level == 0
												) {

													if ( !isset($LocalBusiness_duns) ) {

														// Base 'duns' property value array

															$LocalBusiness_duns = array();

														// Get values

															$LocalBusiness_duns = array();

													}

													// Add to item values

														if ( $LocalBusiness_duns ) {

															$LocalBusiness_item['duns'] = $LocalBusiness_duns;

														}

												}

										// globalLocationNumber

											/* 
											 * The Global Location Number (GLN, sometimes also referred to as International 
											 * Location Number or ILN) of the respective organization, person, or place. The 
											 * GLN is a 13-digit number used to identify parties and physical locations.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											// Define values

												if (
													in_array(
														'globalLocationNumber',
														$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
													)
												) {

													if ( !isset($LocalBusiness_globalLocationNumber) ) {

														// Base 'globalLocationNumber' property value array

															$LocalBusiness_globalLocationNumber = array();

														// Get values

															$LocalBusiness_globalLocationNumber = array();

													}

													// Add to item values

														if ( $LocalBusiness_globalLocationNumber ) {

															$LocalBusiness_item['globalLocationNumber'] = $LocalBusiness_globalLocationNumber;

														}

												}

										// isicV4

											/* 
											 * The International Standard of Industrial Classification of All Economic 
											 * Activities (ISIC), Revision 4 code for a particular organization, business 
											 * person, or place.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											if (
												in_array(
													'isicV4',
													$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
												)
											) {

												if ( !isset($LocalBusiness_isicV4) ) {

													// Base 'isicV4' property value array

														$LocalBusiness_isicV4 = array();

													// Get values

														$LocalBusiness_isicV4 = array();

												}

												// Add to item values

													if ( $LocalBusiness_isicV4 ) {

														$LocalBusiness_item['isicV4'] = $LocalBusiness_isicV4;

													}

											}

										// leiCode

											/* 
											 * An organization identifier that uniquely identifies a legal entity as defined 
											 * in ISO 17442.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											if (
												in_array(
													'leiCode',
													$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
												)
											) {

												if ( !isset($LocalBusiness_leiCode) ) {

													// Base 'leiCode' property value array

														$LocalBusiness_leiCode = array();

													// Get values

														$LocalBusiness_leiCode = array();

												}

												// Add to item values

													if ( $LocalBusiness_leiCode ) {

														$LocalBusiness_item['leiCode'] = $LocalBusiness_leiCode;

													}

											}

										// naics

											/* 
											 * The North American Industry Classification System (NAICS) code for a particular 
											 * organization or business person.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											if (
												in_array(
													'naics',
													$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
												)
											) {

												if ( !isset($LocalBusiness_naics) ) {

													// Base 'naics' property value array

														$LocalBusiness_naics = array();

													// Get values

														$LocalBusiness_naics = array();

												}

												// Add to item values

													if ( $LocalBusiness_naics ) {

														$LocalBusiness_item['naics'] = $LocalBusiness_naics;

													}

											}

										// taxID

											/* 
											 * The Tax / Fiscal ID of the organization or person (e.g., the TIN in the US; 
											 * the CIF/NIF in Spain).
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 */

											if (
												in_array(
													'taxID',
													$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
												)
											) {

												// taxID

													if ( !isset($LocalBusiness_taxID) ) {

														// Base 'taxID' property value array

															/* https://schema.org/taxID */

															$LocalBusiness_taxID = array();

														// Get values

															$LocalBusiness_taxID = array();

													}

												// Taxpayer Identification Number

													if ( !isset($LocalBusiness_taxID_taxpayer) ) {

														// Base Taxpayer Identification Number value array

															/* https://www.wikidata.org/wiki/Q1444804 */

															$LocalBusiness_taxID_taxpayer = array();

														// Get values

															$LocalBusiness_taxID_taxpayer = array();

													}

												// Employer Identification Number

													if ( !isset($LocalBusiness_taxID_employer) ) {

														// Base Employer Identification Number value array

															/* https://www.wikidata.org/wiki/Q2397748 */

															$LocalBusiness_taxID_employer = array();

														// Get values

															$LocalBusiness_taxID_employer = array();

													}

												// Add to item values

													if ( $LocalBusiness_taxID ) {

														$LocalBusiness_item['taxID'] = $LocalBusiness_taxID;

													}

											}

										// vatID

											/* 
											 * The Value-added Tax ID of the organization or person.
											 * 
											 *     - Text
											 */

											if (
												in_array(
													'vatID',
													$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
												)
											) {

												if ( !isset($LocalBusiness_vatID) ) {

													// Base 'vatID' property value array

														$LocalBusiness_vatID = array();

													// Get values

														$LocalBusiness_vatID = array();

												}

												// Add to item values

													if ( $LocalBusiness_vatID ) {

														$LocalBusiness_item['vatID'] = $LocalBusiness_vatID;

													}

											}

										// iso6523Code

											/* 
											 * An organization identifier as defined in ISO 6523(-1). Note that many existing 
											 * organization identifiers such as leiCode, duns and vatID can be expressed as an 
											 * ISO 6523 identifier by setting the ICD part of the ISO 6523 identifier 
											 * accordingly.
											 * 
											 * Values expected to be one of these types:
											 * 
											 *     - Text
											 * 
											 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
											 * feedback and adoption from applications and websites can help improve their 
											 * definitions.
											 */

											if (
												in_array(
													'iso6523Code',
													$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
												)
											) {

												if ( !isset($LocalBusiness_iso6523Code) ) {

													// Base 'iso6523Code' property value array

														$LocalBusiness_iso6523Code = array();

													// Get values

														$LocalBusiness_iso6523Code = array();

												}

												// Add to item values

													if ( $LocalBusiness_iso6523Code ) {

														$LocalBusiness_item['iso6523Code'] = $LocalBusiness_iso6523Code;

													}

											}

										// 'identifier' property

											if (
												in_array(
													'identifier',
													$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
												)
											) {

												/* 
												 * The identifier property represents any kind of identifier for any kind of 
												 * Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated 
												 * properties for representing many of these, either as textual strings or as 
												 * URL (URI) links.
												 * 
												 * See https://schema.org/docs/datamodel.html#identifierBg for more details.
												 * 
												 * Values expected to be one of these types:
												 * 
												 *     - PropertyValue
												 *     - Text
												 *     - URL
												 */

												if ( !isset($LocalBusiness_identifier) ) {

													// Base 'identifier' property value array

														$LocalBusiness_identifier = array();

													// Get values

														// Dun & Bradstreet DUNS number

															if ( $LocalBusiness_duns ) {

																$LocalBusiness_identifier[] =  uamswp_fad_schema_propertyvalue(
																	array(
																		'Data Universal Numbering System (DUNS) number',
																		'DUNS number',
																		'D-U-N-S number'
																	), // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'Data Universal Numbering System number', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q246386', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$LocalBusiness_duns, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// Global Location Number

															if ( $LocalBusiness_globalLocationNumber ) {

																$LocalBusiness_identifier[] = uamswp_fad_schema_propertyvalue(
																	'GLN', // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'Global Location Number', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q1258830', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$LocalBusiness_globalLocationNumber, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code

															if ( $LocalBusiness_isicV4 ) {

																$LocalBusiness_identifier[] = uamswp_fad_schema_propertyvalue(
																	array(
																		'ISIC 2008',
																		'ISIC Rev 4'
																	), // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'International Standard Industrial Classification Rev. 4', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q112111674', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$LocalBusiness_isicV4, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// Legal Entity Identifier (LEI)

															if ( $LocalBusiness_leiCode ) {

																$LocalBusiness_identifier[] = uamswp_fad_schema_propertyvalue(
																	array(
																		'Global Legal Entity Identifier',
																		'LEI',
																		'LEI code',
																		'LEI number'
																	), // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'Legal Entity Identifier', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q6517388', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$LocalBusiness_leiCode, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// North American Industry Classification System (NAICS) code

															if ( $LocalBusiness_naics ) {

																$LocalBusiness_identifier[] = uamswp_fad_schema_propertyvalue(
																	array(
																		'North American Industry Classification System',
																		'NAICS code',
																		'NAICS',
																		'NAICS-SCIAN code',
																		'NAICS-SCIAN'
																	), // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'North American Industry Classification System code', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q3509282', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$LocalBusiness_naics, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

														// Tax / Fiscal ID

															// Taxpayer Identification Number

																if ( $LocalBusiness_taxID_taxpayer ) {

																	$LocalBusiness_identifier[] = uamswp_fad_schema_propertyvalue(
																		array(
																			'TIN',
																			'IRS TIN',
																			'TIN IRS'
																		), // mixed // Optional // alternateName property value
																		null, // string // Optional // description property value
																		null, // int // Optional // maxValue property value
																		null, // mixed // Optional // measurementMethod property value
																		null, // mixed // Optional // measurementTechnique property value
																		null, // int // Optional // minValue property value
																		'Taxpayer Identification Number', // string // Optional // name property value
																		'https://www.wikidata.org/wiki/Q1444804', // string // Optional // propertyID property value
																		null, // string // Optional // unitCode property value
																		null, // string // Optional // unitText property value
																		null, // string // Optional // url property value
																		$LocalBusiness_taxID_taxpayer, // mixed // Optional // value property value
																		null, // mixed // Optional // valueReference property value
																		$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																	);

																}

															// Employer Identification Number

																if ( $LocalBusiness_taxID_employer ) {

																	$LocalBusiness_identifier[] = uamswp_fad_schema_propertyvalue(
																		array(
																			'Federal Employer Identification Number',
																			'EIN'
																		), // mixed // Optional // alternateName property value
																		null, // string // Optional // description property value
																		null, // int // Optional // maxValue property value
																		null, // mixed // Optional // measurementMethod property value
																		null, // mixed // Optional // measurementTechnique property value
																		null, // int // Optional // minValue property value
																		'Employer Identification Number', // string // Optional // name property value
																		'https://www.wikidata.org/wiki/Q2397748', // string // Optional // propertyID property value
																		null, // string // Optional // unitCode property value
																		null, // string // Optional // unitText property value
																		null, // string // Optional // url property value
																		$LocalBusiness_taxID_employer, // mixed // Optional // value property value
																		null, // mixed // Optional // valueReference property value
																		$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																	);

																}

														// Value-added tax (VAT) identification number

															if ( $LocalBusiness_vatID ) {

																$LocalBusiness_identifier[] = uamswp_fad_schema_propertyvalue(
																	array(
																		'value-added tax identification number',
																		'VAT ID',
																		'VATIN'
																	), // mixed // Optional // alternateName property value
																	null, // string // Optional // description property value
																	null, // int // Optional // maxValue property value
																	null, // mixed // Optional // measurementMethod property value
																	null, // mixed // Optional // measurementTechnique property value
																	null, // int // Optional // minValue property value
																	'VAT identification number', // string // Optional // name property value
																	'https://www.wikidata.org/wiki/Q2319042', // string // Optional // propertyID property value
																	null, // string // Optional // unitCode property value
																	null, // string // Optional // unitText property value
																	null, // string // Optional // url property value
																	$LocalBusiness_vatID, // mixed // Optional // value property value
																	null, // mixed // Optional // valueReference property value
																	$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
																);

															}

												}

												// Add to item values

													if ( $LocalBusiness_identifier ) {

														$LocalBusiness_item['identifier'] = $LocalBusiness_identifier;

													}

											}

									}

								// image (common use)

									if (
										in_array(
											'image',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										||
										in_array(
											'photo',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										if ( !isset($LocalBusiness_image_id) ) {

											// Get the various images

												// Base list array

													$LocalBusiness_image_id = array();

												// Parent location overrides

													if ( $LocalBusiness_has_parent ) {

														// Query on whether to override any of the parent location's photos

															if ( !isset($LocalBusiness_override_parent_photo) ) {

																$LocalBusiness_override_parent_photo = get_field('location_image_override_parent') ?? false;

															}

														if ( $LocalBusiness_override_parent_photo ) {

															// Query on whether to override the parent location's featured image

																if ( !isset($LocalBusiness_override_parent_photo_featured) ) {

																	$LocalBusiness_override_parent_photo_featured = get_field( 'location_image_override_parent_featured', $LocalBusiness) ?? false;

																}

															// Query on whether to override the parent location's wayfinding photo

																if ( !isset($LocalBusiness_override_parent_photo_wayfinding) ) {

																	$LocalBusiness_override_parent_photo_wayfinding = get_field( 'location_image_override_parent_wayfinding', $LocalBusiness) ?? false;

																}

															// Query on whether to override the parent location's gallery photos

																if ( !isset($LocalBusiness_override_parent_photo_gallery) ) {

																	$LocalBusiness_override_parent_photo_gallery = get_field( 'location_image_override_parent_gallery', $LocalBusiness) ?? false;

																}

														}

													}

												// Get featured image ID

													if ( !isset($LocalBusiness_featured_image_id) ) {

														if ( !$LocalBusiness_fpage_query ) {

															/* Overview page */

															$LocalBusiness_featured_image_id = $LocalBusiness_override_parent_photo_featured ? get_field( '_thumbnail_id', $LocalBusiness_parent_id ) : get_field( '_thumbnail_id', $LocalBusiness ); // int

														} elseif ( $LocalBusiness_current_fpage == 'providers' ) {

															/* Fake subpage for related providers */

															$LocalBusiness_featured_image_id = '';

														} elseif ( $LocalBusiness_current_fpage == 'clinics' ) {

															/* Fake subpage for descendant locations */

															$LocalBusiness_featured_image_id = '';

														} elseif ( $LocalBusiness_current_fpage == 'related' ) {

															/* Fake subpage for related locations */

															$LocalBusiness_featured_image_id = '';

														} elseif ( $LocalBusiness_current_fpage == 'expertises' ) {

															/* Fake subpage for related areas of expertise */

															$LocalBusiness_featured_image_id = '';

														} elseif ( $LocalBusiness_current_fpage == 'resources' ) {

															/* Fake subpage for related clinical resources */

															$LocalBusiness_featured_image_id = '';

														}

													}

													// Add to the list of image IDs

														if ( $LocalBusiness_featured_image_id ) {

															$LocalBusiness_image_id[] = $LocalBusiness_featured_image_id;

														}

												// Get wayfinding photo ID

													if ( !isset($LocalBusiness_wayfinding_image_id) ) {

														if ( $nesting_level == 0 ) {

															$LocalBusiness_wayfinding_image_id = $LocalBusiness_override_parent_photo_wayfinding ? get_field( 'location_wayfinding_photo', $LocalBusiness_parent_id ) : get_field('location_wayfinding_photo', $LocalBusiness); // int

														}

													}

													// Add to the list of image IDs

														if ( $LocalBusiness_wayfinding_image_id ) {

															$LocalBusiness_image_id[] = $LocalBusiness_wayfinding_image_id;

														}

												// Get gallery photo IDs

													if ( !isset($LocalBusiness_gallery_image_id) ) {

														if ( $nesting_level == 0 ) {

															$LocalBusiness_gallery_image_id = $LocalBusiness_override_parent_photo_wayfinding ? get_field( 'location_photo_gallery', $LocalBusiness_parent_id ) : get_field('location_photo_gallery', $LocalBusiness); // array

														}

													}

													// Add to the list of image IDs

														if ( $LocalBusiness_wayfinding_image_id ) {

															$LocalBusiness_image_id = array_merge(
																$LocalBusiness_image_id,
																$LocalBusiness_gallery_image_id
															);

														}

												// Clean up the list

													$LocalBusiness_image_id = array_filter($LocalBusiness_image_id);
													$LocalBusiness_image_id = array_unique($LocalBusiness_image_id);
													$LocalBusiness_image_id = array_values($LocalBusiness_image_id);

										}

										// Create ImageObject values array for each image

											if ( !isset($LocalBusiness_image_general) ) {

												if ( $LocalBusiness_image_id ) {

													// Base array

														$LocalBusiness_image_general = array();

													foreach ( $LocalBusiness_image_id as $id ) {

														$LocalBusiness_image_general = array_merge(
															$LocalBusiness_image_general,
															uamswp_fad_schema_imageobject_thumbnails(
																$LocalBusiness_url, // URL of entity with which the image is associated
																$nesting_level, // Nesting level within the main schema
																'16:9', // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9')
																'Image', // Base fragment identifier
																$id, // ID of image to use for 1:1 aspect ratio
																0, // ID of image to use for 3:4 aspect ratio
																$id, // ID of image to use for 4:3 aspect ratio
																$id, // ID of image to use for 16:9 aspect ratio
																0 // ID of image to use for full image
															)
														);

													}

												}

											}

										// Clean up the array

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array($LocalBusiness_image_general);

									}

								// image (specific property)

									/* 
									 * An image of the item. This can be a URL or a fully described ImageObject.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - ImageObject
									 *     - URL
									 */

									if (
										in_array(
											'image',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get the values

											if ( !isset($LocalBusiness_image) ) {

												$LocalBusiness_image = $LocalBusiness_image_general ?? array();

											}

										// Add to schema

											if ( $LocalBusiness_image ) {

												$LocalBusiness_item['image'] = $LocalBusiness_image;

											}

									}

								// isAcceptingNewPatients

									/* 
									 * Whether the provider is accepting new patients.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Boolean
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										in_array(
											'isAcceptingNewPatients',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_isAcceptingNewPatients) ) {

												$LocalBusiness_isAcceptingNewPatients = array();

											}

										// Add to item values

											if ( $LocalBusiness_isAcceptingNewPatients ) {

												$LocalBusiness_item['isAcceptingNewPatients'] = $LocalBusiness_isAcceptingNewPatients;

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

									if (
										in_array(
											'isAccessibleForFree',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_isAccessibleForFree) ) {

												$LocalBusiness_isAccessibleForFree = array();

											}

										// Add to item values

											if ( $LocalBusiness_isAccessibleForFree ) {

												$LocalBusiness_item['isAccessibleForFree'] = $LocalBusiness_isAccessibleForFree;

											}

									}

								// keywords

									/* 
									 * Keywords or tags used to describe some item. Multiple textual entries in a 
									 * keywords list are typically delimited by commas, or by repeating the property.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - DefinedTerm
									 *     - Text
									 *     - URL
									 */

									if (
										in_array(
											'keywords',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_keywords) ) {

												$LocalBusiness_keywords = array();

											}

										// Add to item values

											if ( $LocalBusiness_keywords ) {

												$LocalBusiness_item['keywords'] = $LocalBusiness_keywords;

											}

									}

								// knowsAbout

									/* 
									 * Of a Person, and less typically of an Organization, to indicate a topic that is 
									 * known about — suggesting possible expertise but not implying it. We do not 
									 * distinguish skill levels here, or relate this to educational content, events, 
									 * objectives or JobPosting descriptions.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 *     - Thing
									 *     - URL
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										in_array(
											'knowsAbout',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_knowsAbout) ) {

												$LocalBusiness_knowsAbout = array();

											}

										// Add to item values

											if ( $LocalBusiness_knowsAbout ) {

												$LocalBusiness_item['knowsAbout'] = $LocalBusiness_knowsAbout;

											}

									}

								// knowsLanguage

									/* 
									 * Of a Person, and less typically of an Organization, to indicate a known 
									 * language. We do not distinguish skill levels or reading / writing / speaking / 
									 * signing here. Use language codes from the IETF BCP 47 standard.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Language
									 *     - Text
									 */

									if (
										in_array(
											'knowsLanguage',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_knowsLanguage) ) {

												$LocalBusiness_knowsLanguage = array();

											}

										// Add to item values

											if ( $LocalBusiness_knowsLanguage ) {

												$LocalBusiness_item['knowsLanguage'] = $LocalBusiness_knowsLanguage;

											}

									}

								// legalName

									/* 
									 * The official name of the organization (e.g., the registered company name).
									 * 
									 *     - Text
									 */

									if (
										in_array(
											'legalName',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get values

											if ( !isset($LocalBusiness_legalName) ) {

												$LocalBusiness_legalName = array();

											}

										// Add to item values

											if ( $LocalBusiness_legalName ) {

												$LocalBusiness_item['legalName'] = $LocalBusiness_legalName;

											}

									}

								// logo

									/* 
									 * An associated logo.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - ImageObject
									 *     - URL
									 */

									if (
										in_array(
											'logo',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_logo) ) {

												$LocalBusiness_logo = array();

											}

										// Add to item values

											if ( $LocalBusiness_logo ) {

												$LocalBusiness_item['logo'] = $LocalBusiness_logo;

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
										in_array(
											'mainEntityOfPage',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get values

											if ( !isset($LocalBusiness_mainEntityOfPage) ) {

												$LocalBusiness_mainEntityOfPage = $schema_location_MedicalWebPage_ref ?? '';

												if ( !$LocalBusiness_mainEntityOfPage ) {

													$LocalBusiness_mainEntityOfPage = ( isset($LocalBusiness_url) && !empty($LocalBusiness_url) ) ? $LocalBusiness_url . '#MedicalWebPage' : '';

												}

											}

										// Add to item values

											if ( $LocalBusiness_mainEntityOfPage ) {

												$LocalBusiness_item['mainEntityOfPage'] = $LocalBusiness_mainEntityOfPage;

											}

									}

								// makesOffer

									/* 
									 * A pointer to products or services offered by the organization or person.
									 * 
									 * Inverse-property: offeredBy
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Offer
									 */

									if (
										in_array(
											'makesOffer',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_makesOffer) ) {

												$LocalBusiness_makesOffer = array();

											}

										// Add to item values

											if ( $LocalBusiness_makesOffer ) {

												$LocalBusiness_item['makesOffer'] = $LocalBusiness_makesOffer;

											}

									}

								// maximumAttendeeCapacity

									/* 
									 * The total number of individuals that may attend an event or venue.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Integer
									 */

									if (
										in_array(
											'maximumAttendeeCapacity',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_maximumAttendeeCapacity) ) {

												$LocalBusiness_maximumAttendeeCapacity = array();

											}

										// Add to item values

											if ( $LocalBusiness_maximumAttendeeCapacity ) {

												$LocalBusiness_item['maximumAttendeeCapacity'] = $LocalBusiness_maximumAttendeeCapacity;

											}

									}

								// memberOf

									/* 
									 * An Organization (or ProgramMembership) to which this Person or Organization 
									 * belongs.
									 * 
									 * Inverse-property: member
									 * 
									 * Subproperty of:
									 * 
									 *     - foo
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 *     - ProgramMembership
									 */

									if (
										in_array(
											'memberOf',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_memberOf) ) {

												$LocalBusiness_memberOf = array();

											}

										// Add to item values

											if ( $LocalBusiness_memberOf ) {

												$LocalBusiness_item['memberOf'] = $LocalBusiness_memberOf;

											}

									}

								// nonprofitStatus

									/* 
									 * nonprofitStatus indicates the legal status of a non-profit organization in its 
									 * primary place of business.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - NonprofitType
									 * 
									 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
									 * feedback and adoption from applications and websites can help improve their 
									 * definitions.
									 */

									if (
										in_array(
											'nonprofitStatus',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_nonprofitStatus) ) {

												$LocalBusiness_nonprofitStatus = array();

											}

										// Add to item values

											if ( $LocalBusiness_nonprofitStatus ) {

												$LocalBusiness_item['nonprofitStatus'] = $LocalBusiness_nonprofitStatus;

											}

									}

								// numberOfEmployees

									/* 
									 * The number of employees in an organization (e.g., business).
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - QuantitativeValue
									 */

									if (
										in_array(
											'numberOfEmployees',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_numberOfEmployees) ) {

												$LocalBusiness_numberOfEmployees = array();

											}

										// Add to item values

											if ( $LocalBusiness_numberOfEmployees ) {

												$LocalBusiness_item['numberOfEmployees'] = $LocalBusiness_numberOfEmployees;

											}

									}

								// openingHours

									/* 
									 * The general opening hours for a business. Opening hours can be specified as a 
									 * weekly time range, starting with days, then times per day. Multiple days can be 
									 * listed with commas ',' separating each day. Day or time ranges are specified 
									 * using a hyphen '-'.
									 * 
									 * Days are specified using the following two-letter combinations: 
									 * Mo, Tu, We, Th, Fr, Sa, Su.
									 * 
									 * Times are specified using 24:00 format. For example, 3 p.m. is specified as 
									 * 15:00, 10 a.m. as 10:00.
									 * 
									 * Here is an example: 
									 * <time itemprop="openingHours" datetime="Tu,Th 16:00-20:00">Tuesdays and Thursdays 4-8pm</time>.
									 * 
									 * If a business is open 7 days a week, then it can be specified as 
									 * <time itemprop="openingHours" datetime="Mo-Su">Monday through Sunday, all day</time>.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										in_array(
											'openingHours',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_openingHours) ) {

												$LocalBusiness_openingHours = array();

											}

										// Add to item values

											if ( $LocalBusiness_openingHours ) {

												$LocalBusiness_item['openingHours'] = $LocalBusiness_openingHours;

											}

									}

								// openingHoursSpecification

									/* 
									 * The opening hours of a certain place.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - OpeningHoursSpecification
									 */

									if (
										in_array(
											'openingHoursSpecification',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_openingHoursSpecification) ) {

												$LocalBusiness_openingHoursSpecification = array();

											}

										// Add to item values

											if ( $LocalBusiness_openingHoursSpecification ) {

												$LocalBusiness_item['openingHoursSpecification'] = $LocalBusiness_openingHoursSpecification;

											}

									}

								// parentOrganization

									/* 
									 * The larger organization that this organization is a subOrganization of, if any.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 */

									if (
										in_array(
											'parentOrganization',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get values

											if ( !isset($LocalBusiness_parentOrganization) ) {

												$LocalBusiness_parentOrganization = array();

											}

										// Add to item values

											if ( $LocalBusiness_parentOrganization ) {

												$LocalBusiness_item['parentOrganization'] = $LocalBusiness_parentOrganization;

											}

									}

								// paymentAccepted

									/* 
									 * Cash, Credit Card, Cryptocurrency, Local Exchange Tradings System, etc.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Text
									 */

									if (
										in_array(
											'paymentAccepted',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_paymentAccepted) ) {

												$LocalBusiness_paymentAccepted = array();

											}

										// Add to item values

											if ( $LocalBusiness_paymentAccepted ) {

												$LocalBusiness_item['paymentAccepted'] = $LocalBusiness_paymentAccepted;

											}

									}

								// photo

									/* 
									 * A photograph of this place.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - ImageObject
									 *     - Photograph
									 */

									if (
										in_array(
											'photo',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get values

											if ( !isset($LocalBusiness_photo) ) {

												$LocalBusiness_photo = $LocalBusiness_image_general ?? array();

											}

										// Add to item values

											if ( $LocalBusiness_photo ) {

												$LocalBusiness_item['photo'] = $LocalBusiness_photo;

											}

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

									/*

										Create one or more Action arrays, likely 'CreateAction' type

											 * Make an appointment, new or existing patient, by phone
											 * Make an appointment, new patient, by phone
											 * Make an appointment, existing patient, by phone
											 * Make an appointment, new or existing patient, online
											 * Make an appointment, new patient, online
											 * Make an appointment, existing patient, online
											 * Refer a patient, by phone
											 * Refer a patient, by fax
											 * Refer a patient, through Epic thing

										Property descriptions:

											 * 'actionStatus'
												 * Indicates the current disposition of the Action
											 * 'agent'
											        * The direct performer or driver of the action — animate or inanimate (e.g., John 
											          wrote a book)
											 * 'endTime'
												 * The endTime of something. For a reserved event or service 
												(e.g., FoodEstablishmentReservation), the time that it is expected to end. For 
												actions that span a period of time, when the action was performed (e.g., John 
												wrote a book from January to December). For media, including audio and video, 
												it's the time offset of the end of a clip within a larger file. Note that Event 
												uses startDate/endDate instead of startTime/endTime, even when describing dates 
												with times. This situation may be clarified in future revisions.
											 * 'error'
												 * For failed actions, more information on the cause of the failure.
											 * 'instrument'
												 * The object that helped the agent perform the action (e.g., John wrote a book 
												with a pen).
											 * 'location'
												 * The location of, for example, where an event is happening, where an 
												organization is located, or where an action takes place.
											 * 'object'
												 * The object upon which the action is carried out, whose state is kept intact or 
												changed. Also known as the semantic roles patient, affected or undergoer — 
												which change their state — or theme — which doesn't (e.g., John read a book).
											 * 'participant'
												 * Other co-agents that participated in the action indirectly (e.g., John wrote a 
												book with Steve).
											 * 'provider'
												 * The service provider, service operator, or service performer; the goods 
												producer. Another party (a seller) may offer those services or goods on behalf 
												of the provider. A provider may also serve as the seller. Supersedes carrier.
											 * 'result'
												 * The result produced in the action (e.g., John wrote a book).
											 * 'startTime'
												 * The startTime of something. For a reserved event or service 
												(e.g., FoodEstablishmentReservation), the time that it is expected to start. 
												For actions that span a period of time, when the action was performed 
												(e.g., John wrote a book from January to December). For media, including audio 
												and video, it's the time offset of the start of a clip within a larger file. 
												Note that Event uses startDate/endDate instead of startTime/endTime, even when 
												describing dates with times. This situation may be clarified in future 
												revisions.
											 * 'target'
												 * Indicates a target EntryPoint, or url, for an Action.

									*/

									if (
										in_array(
											'potentialAction',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_potentialAction) ) {

												$LocalBusiness_potentialAction = array();

											}

										// Add to item values

											if ( $LocalBusiness_potentialAction ) {

												$LocalBusiness_item['potentialAction'] = $LocalBusiness_potentialAction;

											}

									}

								// publicAccess

									/* 
									 * A flag to signal that the Place is open to public visitors. If this property is 
									 * omitted there is no assumed default boolean value.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Boolean
									 */

									if (
										in_array(
											'publicAccess',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_publicAccess) ) {

												$LocalBusiness_publicAccess = array();

											}

										// Add to item values

											if ( $LocalBusiness_publicAccess ) {

												$LocalBusiness_item['publicAccess'] = $LocalBusiness_publicAccess;

											}

									}

								// review

									/* 
									 * A review of the item.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Review
									 */

									if (
										in_array(
											'review',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_review) ) {

												$LocalBusiness_review = array();

											}

										// Add to item values

											if ( $LocalBusiness_review ) {

												$LocalBusiness_item['review'] = $LocalBusiness_review;

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

									if (
										in_array(
											'sameAs',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
									) {

										// Get sameAs repeater field value

											if ( !isset($LocalBusiness_sameAs_repeater) ) {

												$LocalBusiness_sameAs_repeater = get_field( 'schema_sameas', $LocalBusiness ) ?: array();

											}

											// Add each item to sameAs property values array

												if ( !isset($LocalBusiness_sameAs) ) {

													if ( $LocalBusiness_sameAs_repeater ) {

														$LocalBusiness_sameAs = uamswp_fad_schema_sameas(
															$LocalBusiness_sameAs_repeater, // sameAs repeater field
															'schema_sameas_url' // sameAs item field name
														);

													}

												}

										// Add to schema

											if ( $LocalBusiness_sameAs ) {

												$LocalBusiness_item['sameAs'] = $LocalBusiness_sameAs;

											}

									}

								// smokingAllowed

									/* 
									 * Indicates whether it is allowed to smoke in the place (e.g., in the restaurant, 
									 * hotel or hotel room).
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Boolean
									 */

									if (
										in_array(
											'smokingAllowed',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Define value

											if ( !isset($LocalBusiness_smokingAllowed) ) {

												$LocalBusiness_smokingAllowed = 'False';

											}

										// Add to item values

											if (
												isset($LocalBusiness_smokingAllowed)
												&&
												!empty($LocalBusiness_smokingAllowed)
											) {

												$LocalBusiness_item['smokingAllowed'] = $LocalBusiness_smokingAllowed;

											}

									}

								// specialOpeningHoursSpecification

									/* 
									 * The special opening hours of a certain place.
									 * 
									 * Use this to explicitly override general opening hours brought in scope by 
									 * openingHoursSpecification or openingHours.
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - OpeningHoursSpecification
									 */

									if (
										in_array(
											'specialOpeningHoursSpecification',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_specialOpeningHoursSpecification) ) {

												$LocalBusiness_specialOpeningHoursSpecification = array();

											}

										// Add to item values

											if ( $LocalBusiness_specialOpeningHoursSpecification ) {

												$LocalBusiness_item['specialOpeningHoursSpecification'] = $LocalBusiness_specialOpeningHoursSpecification;

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

									if (
										in_array(
											'subjectOf',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_subjectOf) ) {

												$LocalBusiness_subjectOf = $schema_expertise_MedicalWebPage_ref ?? '';

												if ( !$LocalBusiness_subjectOf ) {

													$LocalBusiness_subjectOf = $LocalBusiness_mainEntityOfPage ?? '';

													if ( !$LocalBusiness_subjectOf ) {

														$LocalBusiness_subjectOf = ( isset($LocalBusiness_url) && !empty($LocalBusiness_url) ) ? $LocalBusiness_url . '#MedicalWebPage' : '';

													}

												}

											}

										// Add to item values

											if ( $LocalBusiness_subjectOf ) {

												$LocalBusiness_item['subjectOf'] = $LocalBusiness_subjectOf;

											}

									}

								// subOrganization

									/* 
									 * A relationship between two organizations where the first includes the second 
									 * (e.g., as a subsidiary).
									 * 
									 * See also: the more specific 'department' property.
									 * 
									 * Inverse-property: parentOrganization
									 * 
									 * Values expected to be one of these types:
									 * 
									 *     - Organization
									 */

									if (
										in_array(
											'subOrganization',
											$LocalBusiness_subtype_map[$LocalBusiness_type]['properties']
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($LocalBusiness_subOrganization) ) {

												$LocalBusiness_subOrganization = $LocalBusiness_containsPlace ?? array();

											}

										// Add to item values

											if ( $LocalBusiness_subOrganization ) {

												$LocalBusiness_item['subOrganization'] = $LocalBusiness_subOrganization;

											}

									}

							// Sort array

								ksort($LocalBusiness_item);

							// Set/update the value of the item transient

								uamswp_fad_set_transient(
									'item_' . $LocalBusiness, // Required // String added to transient name for disambiguation.
									$LocalBusiness_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
									__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
								);

							// Add to list of locations

								$LocalBusiness_list[] = $LocalBusiness_item;

						}

					} // endforeach ( $repeater as $LocalBusiness )

				// Clean up list array

					$LocalBusiness_list = array_filter($LocalBusiness_list);
					$LocalBusiness_list = array_values($LocalBusiness_list);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($LocalBusiness_list);

			} // endif ( !empty($repeater) )

			return $LocalBusiness_list;

		}

	// Areas of expertise (MedicalEntity)

		function uamswp_fad_schema_expertise(
			array $repeater, // List of IDs of the area of expertise items
			string $page_url, // Page URL
			int $nesting_level = 1, // Nesting level within the main schema
			string $page_fragment = 'MedicalEntity', // Base fragment identifier
			int $MedicalEntity_i = 1 // Iteration counter
		) {

			// Common property values

				include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

			// UAMS organization values

				include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/uams.php' );

			// Base list array

				$MedicalEntity_list = array();

			if ( !empty($repeater) ) {

				foreach ( $repeater as $MedicalEntity ) {

					// Retrieve the value of the item transient

						uamswp_fad_get_transient(
							'item_' . $MedicalEntity, // Required // String added to transient name for disambiguation.
							$MedicalEntity_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
							__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
						);

					if ( !empty( $MedicalEntity_item ) ) {

						/* 
						 * The transient exists.
						 * Return the variable.
						 */

						// Add to list of areas of expertise

							$MedicalEntity_list[] = $MedicalEntity_item;

					} else {

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
							$MedicalEntity_image_id = '';
							$MedicalEntity_mainEntityOfPage = '';
							$MedicalEntity_medicineSystem = '';
							$MedicalEntity_relevantSpecialty = array();
							$MedicalEntity_sameAs = '';
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

							if (
								$ontology_type
								&&
								$nesting_level == 0
							) {

								$current_fpage = get_query_var( 'fpage' ) ?? ''; // Fake subpage slug
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

								if ( $nesting_level <= 1 ) {

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

								if ( $nesting_level <= 1 ) {

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

								if ( $nesting_level <= 1 ) {

									// Get values

										// Get the Selected Short Description for This Page

											$MedicalEntity_description = get_field( 'expertise_selected_post_excerpt', $MedicalEntity ) ?? '';

											// Fallback values

												if ( !$MedicalEntity_description ) {

													// Get the excerpt

														$MedicalEntity_description = get_the_excerpt($MedicalEntity) ?? '';

														// Get the Short Description

															if ( !$MedicalEntity_description ) {

																$MedicalEntity_description = get_field( 'post_excerpt', $MedicalEntity ) ?? '';

																// Get the Intro Text (Marketing Landing Page Header style)

																	if ( !$MedicalEntity_description ) {

																		$MedicalEntity_description = get_field( 'page_header_landingpage_intro', $MedicalEntity ) ?? '';

																	}

															}

												}

									// Add to item values

										if ( $MedicalEntity_description ) {

											$MedicalEntity_item['description'] = $MedicalEntity_description;

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

								// Get featured image ID

									if ( !$fpage_query ) {

										/* Overview page */

										$MedicalEntity_image_id = get_field( '_thumbnail_id', $MedicalEntity ) ?? '';

									} elseif ( $current_fpage == 'providers' ) {

										/* Fake subpage for related providers */

										$MedicalEntity_image_id = get_field( 'expertise_providers_fpage_featured_image', $MedicalEntity ) ?? '';

									} elseif ( $current_fpage == 'locations' ) {

										/* Fake subpage for related locations */

										$MedicalEntity_image_id = get_field( 'expertise_locations_fpage_featured_image', $MedicalEntity ) ?? '';

									} elseif ( $current_fpage == 'specialties' ) {

										/* Fake subpage for descendant areas of expertise */

										$MedicalEntity_image_id = get_field( 'expertise_descendant_fpage_featured_image', $MedicalEntity ) ?? '';

									} elseif ( $current_fpage == 'related' ) {

										/* Fake subpage for related areas of expertise */

										$MedicalEntity_image_id = get_field( 'expertise_associated_fpage_featured_image', $MedicalEntity ) ?? '';

									} elseif ( $current_fpage == 'resources' ) {

										/* Fake subpage for related clinical resources */

										$MedicalEntity_image_id = get_field( 'expertise_clinical_resources_fpage_featured_image', $MedicalEntity ) ?? '';

									}

								// Create ImageObject values array

									if ( $MedicalEntity_image_id ) {

										$MedicalEntity_image = uamswp_fad_schema_imageobject_thumbnails(
											$MedicalEntity_url, // URL of entity with which the image is associated
											$nesting_level, // Nesting level within the main schema
											'16:9', // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9')
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

									$MedicalEntity_mainEntityOfPage = $schema_expertise_MedicalWebPage_ref ?? '';

									if ( !$MedicalEntity_mainEntityOfPage ) {

										$MedicalEntity_mainEntityOfPage = ( isset($MedicalEntity_url) && !empty($MedicalEntity_url) ) ? $MedicalEntity_url . '#MedicalWebPage' : '';

									}

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

								if ( $nesting_level <= 1 ) {

									// Get field value

										$MedicalEntity_medicineSystems_array = get_field( 'schema_medicinesystem', $MedicalEntity ) ?: array();

									// Add each item to the list array

										$MedicalEntity_medicineSystem = uamswp_fad_schema_medicinesystem(
											$MedicalEntity_medicineSystems_array // array of MedicineSystem values
										);

									// Add to schema

										if ( $MedicalEntity_medicineSystem ) {

											$MedicalEntity_item['medicineSystem'] = $MedicalEntity_medicineSystem;

										}

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

								/*

									Create one or more Action arrays, likely 'CreateAction' type

										 * Make an appointment, new or existing patient, by phone
										 * Make an appointment, new patient, by phone
										 * Make an appointment, existing patient, by phone
										 * Make an appointment, new or existing patient, online
										 * Make an appointment, new patient, online
										 * Make an appointment, existing patient, online
										 * Refer a patient, by phone
										 * Refer a patient, by fax
										 * Refer a patient, through Epic thing

									Property descriptions:

										 * 'actionStatus'
										        * Indicates the current disposition of the Action
										 * 'agent'
										        * The direct performer or driver of the action — animate or inanimate (e.g., John 
										          wrote a book)
										 * 'endTime'
										        * The endTime of something. For a reserved event or service 
										          (e.g., FoodEstablishmentReservation), the time that it is expected to end. For 
										          actions that span a period of time, when the action was performed (e.g., John 
										          wrote a book from January to December). For media, including audio and video, 
										          it's the time offset of the end of a clip within a larger file. Note that Event 
										          uses startDate/endDate instead of startTime/endTime, even when describing dates 
										          with times. This situation may be clarified in future revisions.
										 * 'error'
										        * For failed actions, more information on the cause of the failure.
										 * 'instrument'
										        * The object that helped the agent perform the action (e.g., John wrote a book 
											with a pen).
										 * 'location'
											 * The location of, for example, where an event is happening, where an 
										          organization is located, or where an action takes place.
										 * 'object'
										        * The object upon which the action is carried out, whose state is kept intact or 
										          changed. Also known as the semantic roles patient, affected or undergoer — 
										          which change their state — or theme — which doesn't (e.g., John read a book).
										 * 'participant'
										        * Other co-agents that participated in the action indirectly (e.g., John wrote a 
										          book with Steve).
										 * 'provider'
										        * The service provider, service operator, or service performer; the goods 
										          producer. Another party (a seller) may offer those services or goods on behalf 
										          of the provider. A provider may also serve as the seller. Supersedes carrier.
										 * 'result'
										        * The result produced in the action (e.g., John wrote a book).
										 * 'startTime'
										        * The startTime of something. For a reserved event or service 
										          (e.g., FoodEstablishmentReservation), the time that it is expected to start. 
										          For actions that span a period of time, when the action was performed 
										          (e.g., John wrote a book from January to December). For media, including audio 
										          and video, it's the time offset of the start of a clip within a larger file. 
										          Note that Event uses startDate/endDate instead of startTime/endTime, even when 
										          describing dates with times. This situation may be clarified in future 
										          revisions.
										 * 'target'
										        * Indicates a target EntryPoint, or url, for an Action.

								*/

								if ( $nesting_level <= 1 ) {

								}

							// relevantSpecialty

								/* 
								 * If applicable, a medical specialty in which this entity is relevant.
								 * 
								 * Values expected to be one of these types:
								 * 
								 *     - MedicalSpecialty
								 */

								if ( $nesting_level <= 1 ) {

									// Get values

										$MedicalEntity_relevantSpecialty = get_field( 'schema_medicalspecialty_multiple', $MedicalEntity ) ?: array();

									// Clean up list array

										$MedicalEntity_relevantSpecialty = array_filter($MedicalEntity_relevantSpecialty);
										$MedicalEntity_relevantSpecialty = array_values($MedicalEntity_relevantSpecialty);

										// If there is only one item, flatten the multi-dimensional array by one step

											uamswp_fad_flatten_multidimensional_array($MedicalEntity_relevantSpecialty);

									// Add to item values

										if ( $MedicalEntity_relevantSpecialty ) {

											$MedicalEntity_item['relevantSpecialty'] = $MedicalEntity_relevantSpecialty;

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

									$MedicalEntity_sameAs_array = get_field( 'schema_sameas', $MedicalEntity ) ?: array();

								// Add each row to the list array

									if ( $MedicalEntity_sameAs_array ) {

										$MedicalEntity_sameAs = uamswp_fad_schema_sameas(
											$MedicalEntity_sameAs_array, // sameAs repeater field
											'schema_sameas_url' // sameAs item field name
										);

									}

								// Add to schema

									if ( $MedicalEntity_sameAs ) {

										$MedicalEntity_item['sameAs'] = $MedicalEntity_sameAs;

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

								if ( $nesting_level <= 1 ) {

									// Get values

										$MedicalEntity_subjectOf = $schema_expertise_MedicalWebPage_ref ?? '';

										if ( !$MedicalEntity_subjectOf ) {

											$MedicalEntity_subjectOf = $MedicalEntity_mainEntityOfPage ?? '';

											if ( !$MedicalEntity_subjectOf ) {

												$MedicalEntity_subjectOf = ( isset($MedicalEntity_url) && !empty($MedicalEntity_url) ) ? $MedicalEntity_url . '#MedicalWebPage' : '';

											}

										}

									// Add to item values

										if ( $MedicalEntity_subjectOf ) {

											$MedicalEntity_item['subjectOf'] = $MedicalEntity_subjectOf;

										}

								}

						// Sort array

							ksort($MedicalEntity_item);

						// Set/update the value of the item transient

							uamswp_fad_set_transient(
								'item_' . $MedicalEntity, // Required // String added to transient name for disambiguation.
								$MedicalEntity_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
								__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
							);

						// Add to list of areas of expertise

							$MedicalEntity_list[] = $MedicalEntity_item;

					}

				} // endforeach ( $repeater as $MedicalEntity )

				// Clean up list array

					$MedicalEntity_list = array_filter($MedicalEntity_list);
					$MedicalEntity_list = array_values($MedicalEntity_list);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($MedicalEntity_list);

			} // endif ( !empty($repeater) )

			return $MedicalEntity_list;

		}

	// Clinical resources (CreativeWork)

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

						uamswp_fad_get_transient(
							'item_' . $CreativeWork, // Required // String added to transient name for disambiguation.
							$CreativeWork_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
							__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
						);

					if ( !empty( $CreativeWork_item ) ) {

						/* 
						 * The transient exists.
						 * Return the variable.
						 */

						// Add to list of clinical resources

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
										'16:9', // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9')
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

										// Get values

											// Base array

												$CreativeWork_creator = array();

											// UAMS

												if ( $schema_base_org_uams_ref ) {

													$CreativeWork_creator[] = $schema_base_org_uams_ref;

												}

											// UAMS Health

												if ( $schema_base_org_uams_health_ref ) {

													$CreativeWork_creator[] = $schema_base_org_uams_health_ref;

												}

										// Format values

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array( $CreativeWork_creator );

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
							 * Represents textual captioning from a MediaObject (e.g., text of a 'meme').
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 * 
							 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
							 * feedback and adoption from applications and websites can help improve their 
							 * definitions.
							 */

							if (
								in_array( 'embeddedTextCaption', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

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
							 * the most appropriate URL (e.g., defining Web page or a Wikipedia/Wikidata 
							 * entry).
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
							 * A permission related to the access to this document (e.g., permission to read 
							 * or  write an electronic document). For a public document, specify a grantee
							 * with an  Audience with audienceType equal to "public".
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

							if (
								in_array( 'isAccessibleForFree', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

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

										$CreativeWork_isPartOf = ( isset($CreativeWork_url) && !empty($CreativeWork_url) ) ? $CreativeWork_url . '#MedicalWebPage' : '';

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

							if ( in_array( 'mainEntityOfPage', $CreativeWork_properties ) ) {

								// Get values

									$CreativeWork_mainEntityOfPage = $schema_clinical_resource_MedicalWebPage_ref ?? '';

									if ( !$CreativeWork_mainEntityOfPage ) {

										$CreativeWork_mainEntityOfPage = ( isset($CreativeWork_url) && !empty($CreativeWork_url) ) ? $CreativeWork_url . '#MedicalWebPage' : '';

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

							if (
								in_array( 'representativeOfPage', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

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

							if (
								in_array( 'sameAs', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base list array

										$CreativeWork_sameAs = array();

									// Add values to the list array

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

								// Clean up list array

									if ( $CreativeWork_sameAs ) {

										$CreativeWork_sameAs = array_unique($CreativeWork_sameAs);
										$CreativeWork_sameAs = array_filter($CreativeWork_sameAs);
										$CreativeWork_sameAs = array_values($CreativeWork_sameAs);

										// If there is only one item, flatten the multi-dimensional array by one step

											uamswp_fad_flatten_multidimensional_array($CreativeWork_sameAs);

									}

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

										// Get values

											// Base array

												$CreativeWork_sourceOrganization = array();

											// UAMS

												if ( $schema_base_org_uams_ref ) {

													$CreativeWork_sourceOrganization[] = $schema_base_org_uams_ref;

												}

											// UAMS Health

												if ( $schema_base_org_uams_health_ref ) {

													$CreativeWork_sourceOrganization[] = $schema_base_org_uams_health_ref;

												}

										// Format values

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array( $CreativeWork_sourceOrganization );

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

							if (
								in_array( 'subjectOf', $CreativeWork_properties )
								&&
								$nesting_level == 0
							) {

								// Get values

									$CreativeWork_subjectOf = $schema_clinical_resource_MedicalWebPage_ref ?? '';

									if ( !$CreativeWork_subjectOf ) {

										$CreativeWork_subjectOf = $CreativeWork_mainEntityOfPage ?? '';

										if ( !$CreativeWork_subjectOf ) {

											$CreativeWork_subjectOf = ( isset($CreativeWork_url) && !empty($CreativeWork_url) ) ? $CreativeWork_url . '#MedicalWebPage' : '';

										}

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

							uamswp_fad_set_transient(
								'item_' . $CreativeWork, // Required // String added to transient name for disambiguation.
								$CreativeWork_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
								__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
							);

						// Add to list of clinical resources

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

	// Conditions (MedicalCondition)

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

					// Retrieve the value of the item transient

						uamswp_fad_get_transient(
							'item_' . $condition, // Required // String added to transient name for disambiguation.
							$condition_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
							__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
						);

					if ( !empty( $condition_item ) ) {

						/* 
						 * The transient exists.
						 * Return the variable.
						 */

						// Add to list of conditions

							$condition_list[] = $condition_item;

					} else {

						// If post is not published, skip to the next iteration

							if ( get_post_status($condition) != 'publish' ) {

								continue;

							}

						// Eliminate PHP errors / reset variables

							$condition_item = array(); // Base array
							$condition_additionalType_repeater = array();
							$condition_additionalType = array();
							$condition_alternateName = array();
							$condition_alternateName_repeater = array();
							$condition_code = array();
							$condition_code_repeater = array();
							$condition_id = '';
							$condition_infectiousAgent = '';
							$condition_infectiousAgentClass = '';
							$condition_name = '';
							$condition_possibleTreatment = array();
							$condition_possibleTreatment_relationship = array();
							$condition_primaryPrevention = array();
							$condition_primaryPrevention_relationship = array();
							$condition_sameAs = array();
							$condition_sameAs_repeater = array();
							$condition_secondaryPrevention = array();
							$condition_secondaryPrevention_relationship = array();
							$condition_type = '';
							$condition_type_parent = array();
							$condition_typicalTest = array();
							$condition_typicalTest_relationship = array();

						// @id

							// Define value

								if ( $nesting_level == 1 ) {

									$condition_id = $page_url . '#' . $page_fragment . $condition_i;
									$condition_i++;

								}

							// Add to schema

								if ( $condition_id ) {

									$condition_item['@id'] = $condition_id;

								}

						// @type

							$condition_type = 'MedicalCondition';

							// MedicalCondition Subtype

								$condition_type = get_field( 'schema_medicalcondition_subtype', $condition ) ?: $condition_type;
								$condition_type_parent = $condition_type != 'MedicalCondition' ? array( 'MedicalCondition' ) : array();

							// Add to schema

								if ( $condition_type ) {

									$condition_item['@type'] = $condition_type;

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

							// Get value

								$condition_name = get_the_title($condition); // Expects Text

							// Add to array

								if ( $condition_name ) {

									$condition_item['name'] = $condition_name;

								}

						// alternateName

							/* 
							 * An alias for the item.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							// Get alternateName repeater field value

								$condition_alternateName_repeater = get_field( 'condition_alternate', $condition ) ?: array();

								// Add each item to alternateName property values array

									$condition_alternateName = uamswp_fad_schema_alternatename(
										$condition_alternateName_repeater, // alternateName repeater field
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

							// Get code repeater field value

								$condition_code_repeater = get_field( 'schema_medicalcode', $condition ) ?: array();

								// Add each item to code property values array

									$condition_code = uamswp_fad_schema_code(
										$condition_code_repeater // code repeater field
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

							// Get additionalType repeater field value

								$condition_additionalType_repeater = get_field( 'schema_additionalType', $condition ) ?? array();

								// Add each item to additionalType property values array

									if ( $condition_additionalType_repeater ) {

										$condition_additionalType = uamswp_fad_schema_additionaltype(
											$condition_additionalType_repeater, // additionalType repeater field
											'schema_additionalType_uri' // additionalType item field name
										);

									}

							// Add to schema

								if ( $condition_additionalType ) {

									$service_item['additionalType'] = $condition_additionalType;

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

							// Get sameAs repeater field value

								$condition_sameAs_repeater = get_field( 'schema_sameas', $condition ) ?: array();

								// Add each item to sameAs property values array

									if ( $condition_sameAs_repeater ) {

										$condition_sameAs = uamswp_fad_schema_sameas(
											$condition_sameAs_repeater, // sameAs repeater field
											'schema_sameas_url' // sameAs item field name
										);

									}

							// Add to schema

								if ( $condition_sameAs ) {

									$condition_item['sameAs'] = $condition_sameAs;

								}

						// infectiousAgent

							if (
								$condition_type == 'InfectiousDisease'
								||
								in_array( 'InfectiousDisease', $condition_type_parent )
							) {

								// Get field value

									$condition_infectiousAgent = get_field( 'schema_infectiousagent', $condition ) ?: '';

								// Add to schema

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

								// Get field value

									$condition_infectiousAgentClass =  get_field( 'condition_schema_infectiousagentclass_schema_infectiousagentclass', $condition ) ?: '';

								// Add to schema

									if ( $condition_infectiousAgentClass ) {

										$condition_item['infectiousAgentClass'] = $condition_infectiousAgentClass;

									}

							}

						// possibleTreatment

							if ( $nesting_level == 1 ) {

								// Get possibleTreatment relationship field value

									$condition_possibleTreatment_relationship = get_field( 'condition_schema_possibletreatment', $condition ) ?: array();

									// Add each item to possibleTreatment property values array

										if ( $condition_possibleTreatment_relationship ) {

											$condition_possibleTreatment = uamswp_fad_schema_service(
												$condition_possibleTreatment_relationship,
												$page_url,
												( $nesting_level + 1 ),
												'possibleTreatment' // Fragment identifier
											);

										}

								// Add to schema

									if ( $condition_possibleTreatment ) {

										$condition_item['possibleTreatment'] = $condition_possibleTreatment;

									}

							}

						// primaryPrevention

							if ( $nesting_level == 1 ) {

								// Get primaryPrevention relationship field value

									$condition_primaryPrevention_relationship = get_field( 'condition_schema_primaryprevention', $condition ) ?: array();

									// Add each item to primaryPrevention property values array

										if ( $condition_primaryPrevention_relationship ) {

											$condition_primaryPrevention = uamswp_fad_schema_service(
												$condition_primaryPrevention_relationship,
												$page_url,
												( $nesting_level + 1 ),
												'primaryPrevention' // Fragment identifier
											);

										}

								// Add to schema

									if ( $condition_primaryPrevention ) {

										$condition_item['primaryPrevention'] = $condition_primaryPrevention;

									}

							}

						// secondaryPrevention

							if ( $nesting_level == 1 ) {

								// Get secondaryPrevention relationship field value

									$condition_secondaryPrevention_relationship = get_field( 'condition_schema_secondaryprevention', $condition ) ?: array();

									// Add each item to secondaryPrevention property values array

										if ( $condition_secondaryPrevention_relationship ) {

											$condition_secondaryPrevention = uamswp_fad_schema_service(
												$condition_secondaryPrevention_relationship,
												$page_url,
												( $nesting_level + 1 ),
												'secondaryPrevention' // Fragment identifier
											);

										}

								// Add to schema

									if ( $condition_secondaryPrevention ) {

										$condition_item['secondaryPrevention'] = $condition_secondaryPrevention;

									}

							}

						// typicalTest

							if ( $nesting_level == 1 ) {

								// Get typicalTest relationship field value

									$condition_typicalTest_relationship = get_field( 'condition_schema_typicaltest', $condition ) ?: array();

									// Add each item to typicalTest property values array

										if ( $condition_typicalTest_relationship ) {

											$condition_typicalTest = uamswp_fad_schema_service(
												$condition_typicalTest_relationship,
												$page_url,
												( $nesting_level + 1 ),
												'typicalTest' // Fragment identifier
											);

										}

								// Add to schema

									if ( $condition_typicalTest ) {

										$condition_item['typicalTest'] = $condition_typicalTest;

									}

							}

						// Sort array

							ksort($condition_item);

						// Set/update the value of the item transient

							uamswp_fad_set_transient(
								'item_' . $condition, // Required // String added to transient name for disambiguation.
								$condition_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
								__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
							);

						// Add to list of conditions

							$condition_list[] = $condition_item;

					}

				} // endforeach ( $repeater as $condition )

				// Clean up list array

					$condition_list = array_filter($condition_list);
					$condition_list = array_values($condition_list);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($condition_list);

			} // endif ( !empty($repeater) )

			return $condition_list;

		}

	// Treatments and procedures (MedicalProcedure, MedicalTest)

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

					// Retrieve the value of the item transient

						uamswp_fad_get_transient(
							'item_' . $service, // Required // String added to transient name for disambiguation.
							$service_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
							__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
						);

					if ( !empty( $service_item ) ) {

						/* 
						 * The transient exists.
						 * Return the variable.
						 */

						// Add to list of treatments and procedures

							$service_list[] = $service_item;

					} else {

						// If post is not published, skip to the next iteration

							if ( get_post_status($service) != 'publish' ) {

								continue;

							}

						// Eliminate PHP errors and reset variables

							$service_item = array(); // Base array
							$service_additionalType = array();
							$service_additionalType_repeater = array();
							$service_alternateName = array();
							$service_alternateName_repeater = array();
							$service_code = array();
							$service_code_repeater = array();
							$service_drug_item = array();
							$service_drug_item_nonProprietaryName_list = array();
							$service_drug_item_proprietaryName_list = array();
							$service_drug = array();
							$service_drug_repeater = array();
							$service_duplicateTherapy = array();
							$service_duplicateTherapy_relationship = array();
							$service_id = '';
							$service_MedicalImagingTechnique = '';
							$service_name = '';
							$service_procedureType = '';
							$service_relevantSpecialty = array();
							$service_relevantSpecialty_multiselect = array();
							$service_sameAs = array();
							$service_sameAs_repeater = array();
							$service_subTest = array();
							$service_subTest_array = array();
							$service_subTest_relationship = array();
							$service_tissueSample = array();
							$service_tissueSample_repeater = array();
							$service_type = '';
							$service_type_parent = array();
							$service_usedToDiagnose = array();
							$service_usedToDiagnose_relationship = array();
							$service_usesDevice_item = array();
							$service_usesDevice_item_alternateName = array();
							$service_usesDevice_item_alternateName_repeater = array();
							$service_usesDevice_item_code = array();
							$service_usesDevice_item_code_repeater = array();
							$service_usesDevice = array();
							$service_usesDevice_repeater = array();
							$usesDevice = array();

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

							// Get value

								$service_name = get_the_title($service) ?? ''; // Expects Text

							// Add to schema

								if ( $service_name ) {

									$service_item['name'] = $service_name;

								}

						// alternateName

							/* 
							 * An alias for the item.
							 * 
							 * Values expected to be one of these types:
							 * 
							 *     - Text
							 */

							// Get alternateName repeater field value

								$service_alternateName_repeater = get_field( 'treatment_procedure_alternate', $service ) ?: array();

								// Add each item to alternateName property values array

									if ( $service_alternateName_repeater ) {

										$service_alternateName = uamswp_fad_schema_alternatename(
											$service_alternateName_repeater, // alternateName repeater field
											'alternate_text' // alternateName item field name
										);

									}

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

							// Get code repeater field value

								$service_code_repeater = get_field( 'schema_medicalcode', $service ) ?: array();

								// Add each item to code property values array

									if ( $service_code_repeater ) {

										$service_code = uamswp_fad_schema_code(
											$service_code_repeater // code repeater field
										);

									}

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

							// Get additionalType repeater field value

								$service_additionalType_repeater = get_field( 'schema_additionalType', $service ) ?? array();

								// Add each item to additionalType property values array

									if ( $service_additionalType_repeater ) {

										$service_additionalType = uamswp_fad_schema_additionaltype(
											$service_additionalType_repeater, // additionalType repeater field
											'schema_additionalType_uri' // additionalType item field name
										);

									}

							// Add to schema

								if ( $service_additionalType ) {

									$service_item['additionalType'] = $service_additionalType;

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

							// Get sameAs repeater field value

								$service_sameAs_repeater = get_field( 'schema_sameas', $service ) ?: array();

								// Add each item to sameAs property values array

									if ( $service_sameAs_repeater ) {

										$service_sameAs = uamswp_fad_schema_sameas(
											$service_sameAs_repeater, // sameAs repeater field
											'schema_sameas_url' // sameAs item field name
										);

									}

							// Add to schema

								if ( $service_sameAs ) {

									$service_item['sameAs'] = $service_sameAs;

								}

						// drug

							if (
								$service_type == 'TherapeuticProcedure'
								||
								in_array( 'TherapeuticProcedure', $service_type_parent )
							) {

								// Base drug property values array

									$service_drug = array();

								// Get drug repeater field value — Drug(s) or Medicine(s) Used

									$service_drug_repeater = get_field( 'schema_drug', $service ) ?? array();

									// Add each item to drug property values array

										if ( $service_drug_repeater ) {

											foreach ( $service_drug_repeater as $drug ) {

												// Base drug property value item array

													$service_drug_item = array();

												// Define property values of drug items

													// Define proprietaryName schema property value

														// Base proprietaryName list array

															$service_drug_item_proprietaryName_list = array();

														// Get proprietaryName repeater value

															$service_drug_item['proprietaryName'] = $drug['schema_drug_proprietaryname'] ?? array();

														// Loop through rows of proprietaryName repeater value

															if ( $service_drug_item['proprietaryName'] ) {

																foreach ( $service_drug_item['proprietaryName'] as $proprietaryName ) {

																	// Add subfield value to base proprietaryName list array

																		$service_drug_item_proprietaryName_list[] = $proprietaryName['schema_drug_proprietaryname_text'];

																}

															}

														// Clean up base proprietaryName list array

															if ( $service_drug_item_proprietaryName_list ) {

																// If there is only one item, flatten the multi-dimensional array by one step

																	uamswp_fad_flatten_multidimensional_array($service_drug_item_proprietaryName_list);

															}

														// Add proprietaryName values to drug property value item array

															if ( $service_drug_item_proprietaryName_list ) {

																$service_drug_item['proprietaryName'] = $service_drug_item_proprietaryName_list;

															}

													// Define nonProprietaryName schema property value

														// Base nonProprietaryName list array

															$service_drug_item_nonProprietaryName_list = array();

														// Get nonProprietaryName subfield value

															$service_drug_item['nonProprietaryName'] = $drug['schema_drug_nonproprietaryname'] ?? array();

														// Loop through rows of proprietaryName repeater value

															if ( $service_drug_item['nonProprietaryName'] ) {

																foreach ( $service_drug_item['nonProprietaryName'] as $nonProprietaryName ) {

																	// Add subfield value to base nonProprietaryName list array

																		$service_drug_item_nonProprietaryName_list[] = $nonProprietaryName['schema_drug_nonproprietaryname_text'];

																}

															}

														// Clean up base nonProprietaryName list array

															if ( $service_drug_item_nonProprietaryName_list ) {

																// If there is only one item, flatten the multi-dimensional array by one step

																	uamswp_fad_flatten_multidimensional_array($service_drug_item_nonProprietaryName_list);

															}

														// Add nonProprietaryName values to drug property value item array

															if ( $service_drug_item_nonProprietaryName_list ) {

																$service_drug_item['nonProprietaryName'] = $service_drug_item_nonProprietaryName_list;

															}

													// Define prescriptionStatus schema property value, add to drug property value item array

														$service_drug_item['prescriptionStatus'] = $drug['schema_drug_prescriptionstatus'] ?? '';

													// Define rxcui schema property value, add to drug property value item array

														$service_drug_item['rxcui'] = $drug['schema_drug_rxcui'] ?? '';

												// Add drug property value item array to the drug property values array

													if ( $service_drug_item ) {

														$service_drug[] = $service_drug_item;

													}

											}

										}

								// Clean up drug property values array

									if ( $service_drug ) {

										$service_drug = array_filter($service_drug);
										$service_drug = array_values($service_drug);

										// If there is only one item, flatten the multi-dimensional array by one step

											uamswp_fad_flatten_multidimensional_array($service_drug);

									}

								// Add to schema

									if ( $service_drug ) {

										$service_item['drug'] = $service_drug;

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

								// Get duplicateTherapy relationship repeater value (clones 'field_schema_medicaltherapy')

									$service_duplicateTherapy_relationship = get_field( 'treatment_procedure_schema_duplicatetherapy_schema_medicaltherapy', $service ) ?: array();

									// Add each item to duplicateTherapy property values array

										if ( $service_duplicateTherapy_relationship ) {

											$service_duplicateTherapy = uamswp_fad_schema_service(
												$service_duplicateTherapy_relationship,
												$page_url,
												( $nesting_level + 1 ),
												'duplicateTherapy' // Fragment identifier
											);

										}

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

								// Get field value

									$service_MedicalImagingTechnique = get_field( 'schema_medicalimagingtechnique', $service ) ?: '';

								// Add to schema

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

								// Get field value

									$service_procedureType = get_field( 'schema_medicalproceduretype', $service ) ?: '';

								// Add to schema

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

								// Get subTest relationship field value (clone field referencing 'field_schema_medicaltest')

									$service_subTest_relationship = get_field( 'treatment_procedure_schema_subtest_schema_medicaltest', $service ) ?: array();

									// Add each item to subTest property values array

										if ( $service_subTest_relationship ) {

											$service_subTest = uamswp_fad_schema_service(
												$service_subTest_relationship,
												$page_url,
												( $nesting_level + 1 ),
												'subTest' // Fragment identifier
											);

										}

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

							// Base relevantSpecialty property values array

								$service_relevantSpecialty = array();

							// Get relevantSpecialty multi-select field value (clone of 'field_schema_medicalspecialty_multiple')

								$service_relevantSpecialty_multiselect = get_field( 'treatment_procedure_schema_relevantspecialty_schema_medicalspecialty_multiple', $service ) ?: array();

								// Add each item to relevantSpecialty property values array

									if ( $service_relevantSpecialty_multiselect ) {

										foreach ( $service_relevantSpecialty_multiselect as $relevantSpecialty ) {

												$service_relevantSpecialty[] = $relevantSpecialty ?? '';

										}

									}

							// Clean up relevantSpecialty property values array

								if ( $service_relevantSpecialty ) {

									$service_relevantSpecialty = array_filter($service_relevantSpecialty);
									$service_relevantSpecialty = array_values($service_relevantSpecialty);
									sort($service_relevantSpecialty);

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($service_relevantSpecialty);

								}

							// Add to schema

								if ( $service_relevantSpecialty ) {

									$service_item['relevantspecialty'] = $service_relevantSpecialty;

								}

						// tissueSample

							if (
								$service_type == 'PathologyTest'
								||
								in_array( 'PathologyTest', $service_type_parent )
							) {

								// Base tissueSample property values array

									$service_tissueSample = array();

								// Get tissueSample repeater field value 

									$service_tissueSample_repeater = get_field( 'schema_tissuesample', $service ) ?: array();

									// Add each item to tissueSample property values array

										if ( $service_tissueSample_repeater ) {

											foreach ( $service_tissueSample_repeater as $tissueSample ) {

												$service_tissueSample[] = $tissueSample['schema_tissuesample_text'];

											}

										}

								// Clean up tissueSample property values array

									if ( $service_tissueSample ) {

										$service_tissueSample = array_filter($service_tissueSample);
										$service_tissueSample = array_values($service_tissueSample);
										sort($service_tissueSample);

										// If there is only one item, flatten the multi-dimensional array by one step

											uamswp_fad_flatten_multidimensional_array($service_tissueSample);

									}

								// Add to schema

									if ( $service_tissueSample ) {

										$service_item['tissueSample'] = $service_tissueSample;

									}

							}

						// usedToDiagnose

							if (
								$service_type == 'MedicalTest'
								||
								in_array( 'MedicalTest', $service_type_parent )
							) {

								// Get usedToDiagnose relationship field value (clone of 'field_schema_medicalcondition')

									$service_usedToDiagnose_relationship = get_field( 'treatment_procedure_schema_usedtodiagnose_schema_medicalcondition', $service ) ?: array();

									// Add each item to usedToDiagnose property values array

										if ( $service_usedToDiagnose_relationship ) {

											$service_usedToDiagnose = uamswp_fad_schema_medicalcondition(
												$service_usedToDiagnose_relationship, // List of IDs of the MedicalCondition items
												$page_url, // Page URL
												( $nesting_level + 1 ), // Nesting level within the main schema
												'usedToDiagnose' // Fragment identifier
											);

										}

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

								// Base usesDevice property values array

									$service_usesDevice = array();

								// Get usesDevice repeater field value

									$service_usesDevice_repeater = get_field( 'schema_medicaldevice', $service ) ?: array();

									// Add each item to usesDevice property values array

										if ( $service_usesDevice_repeater ) {

											foreach ( $service_usesDevice_repeater as $usesDevice ) {

												// Base usesDevice property value item array

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

													// Get alternateName repeater field value

														$service_usesDevice_item_alternateName_repeater = $usesDevice['schema_medicaldevice_alternatename']['schema_alternatename'] ?: array();

														// Add each item to alternateName property value array

															$service_usesDevice_item_alternateName = uamswp_fad_schema_alternatename(
																$service_alternateName_repeater, // alternateName repeater field
																'schema_alternatename_text' // alternateName item field name
															);

													// Add to usesDevice property value item array

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

													// Get code repeater field value

														$service_usesDevice_item_code_repeater = $usesDevice['schema_medicaldevice_code']['schema_medicalcode'] ?: array();

														// Add each item to code property value array

															$service_usesDevice_item_code = uamswp_fad_schema_code(
																$service_usesDevice_item_code_repeater // code repeater field
															);

													// Add to usesDevice property value item array

														if ( $service_usesDevice_item_code ) {

															$service_usesDevice_item['code'] = $service_usesDevice_item_code;

														}

												// Add item to the list array

													$service_usesDevice[] = $service_usesDevice_item;

											}

											// Clean up list array

												$service_usesDevice = array_filter($service_usesDevice);
												$service_usesDevice = array_values($service_usesDevice);
												sort($service_usesDevice);

												// If there is only one item, flatten the multi-dimensional array by one step

													uamswp_fad_flatten_multidimensional_array($service_usesDevice);

											// Add to schema

												if ( $service_usesDevice ) {

													$service_item['usesDevice'] = $service_usesDevice;

												}

										}

							}

						// Sort array

							ksort($service_item);

						// Set/update the value of the item transient

							uamswp_fad_set_transient(
								'item_' . $service, // Required // String added to transient name for disambiguation.
								$service_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
								__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
							);

						// Add to list of treatments

							$service_list[] = $service_item;

					}

				} // endforeach ( $repeater as $service )

				// Clean up list array

					$service_list = array_filter($service_list);
					$service_list = array_values($service_list);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($service_list);

			} // endif ( !empty($repeater) )

			return $service_list;

		}

// Utility functions

	// Define @id references to each top-level node in an array

		function uamswp_fad_schema_node_references(
			array $input
		) {

			// Check or define variables

				$input = array_is_list($input) ? $input : array($input);

			// Base array

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