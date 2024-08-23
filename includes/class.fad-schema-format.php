<?php

/**
 * Functions for Schema.org Schema and Google Local Business structured data
 *
 * Format values for common schema data properties and types
 */

// Add data to an array defining schema data for LoanOrCredit

	function uamswp_fad_schema_loanorcredit(
		$input = array(), // array // Optional // LoanOrCredit value(s)
		array $output = array() // Optional // Pre-existing list array to which to add additional items
	) {

		/**
		 * A financial product for the loaning of an amount of money, or line of credit,
		 * under agreed terms and charges.
		 */

		// Check variables

			// If LoanOrCredit is invalid, stop here

				if (
					!$LoanOrCredit
					||
					!is_array($occupationalCategory)
				) {

					return $output;

				}

			$output = array_is_list($output) ? $output : array($output);

		// Add input variable to output array

			if ( $LoanOrCredit ) {

				if ( array_is_list($LoanOrCredit) ) {

					array_merge(
						$output,
						$LoanOrCredit
					);

				} else {

					$output[] = $LoanOrCredit;

				}

			}

		// Clean up schema list array

			if ( $output ) {

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($output);

			}

		return $output;

	}

// Add data to an array defining schema data for PaymentMethod

	function uamswp_fad_schema_paymentmethod(
		$input = array(), // array|string // Optional // PaymentMethod value(s)
		array $output = array() // Optional // Pre-existing list array to which to add additional items
	) {

		/**
		 * A payment method is a standardized procedure for transferring the monetary
		 * amount for a purchase. Payment methods are characterized by the legal and
		 * technical structures used, and by the organization or group carrying out the
		 * transaction.
		 *
		 * Commonly used values:
		 *
		 *      - http://purl.org/goodrelations/v1#ByBankTransferInAdvance
		 *      - http://purl.org/goodrelations/v1#ByInvoice
		 *      - http://purl.org/goodrelations/v1#Cash
		 *      - http://purl.org/goodrelations/v1#CheckInAdvance
		 *      - http://purl.org/goodrelations/v1#COD
		 *      - http://purl.org/goodrelations/v1#DirectDebit
		 *      - http://purl.org/goodrelations/v1#GoogleCheckout
		 *      - http://purl.org/goodrelations/v1#PayPal
		 *      - http://purl.org/goodrelations/v1#PaySwarm
		 *
		 * Enumeration Subtypes:
		 *
		 *      - PaymentCard
		 */

		// Check variables

			// If PaymentMethod is invalid, stop here

				if ( !$PaymentMethod ) {

					return $output;

				}

			$output = array_is_list($output) ? $output : array($output);

		// PaymentMethod

			// Check input variable

				// Check type

					$input = ( $input && ( is_string($input) || is_array($input) ) ) ? $input : '';

				// Check values against Schema.org's commonly used values

					$PaymentMethod_valid = array(
						'http://purl.org/goodrelations/v1#ByBankTransferInAdvance',
						'http://purl.org/goodrelations/v1#ByInvoice',
						'http://purl.org/goodrelations/v1#Cash',
						'http://purl.org/goodrelations/v1#CheckInAdvance',
						'http://purl.org/goodrelations/v1#COD',
						'http://purl.org/goodrelations/v1#DirectDebit',
						'http://purl.org/goodrelations/v1#GoogleCheckout',
						'http://purl.org/goodrelations/v1#PayPal',
						'http://purl.org/goodrelations/v1#PaySwarm'
					);

					if (
						$input
						&&
						is_array($input)
						&&
						array_is_list($PaymentMethod)
					) {

						$input = array_intersect(
							$input, // The array with master values to check
							$PaymentMethod_valid // Arrays to compare values against
						);

					} elseif (
						$input
						&&
						is_string($input)
					) {

						$input = in_array(
							$input, // needle
							$PaymentMethod_valid // haystack
						) ? $input : '';

					}

			// Add input variable to output array

				if ( $input ) {

					if (
						is_array($input)
						&&
						array_is_list($input)
					) {

						array_merge(
							$output,
							$input
						);

					} else {

						$output[] = $input;

					}

				}

		// Clean up schema list array

			if ( $output ) {

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($output);

			}

		return $output;

	}

// Add data to an array defining schema data for acceptedPaymentMethod

	function uamswp_fad_schema_acceptedpaymentmethod(
		$LoanOrCredit = array(), // array // Optional // LoanOrCredit value(s)
		$PaymentMethod = array(), // array|string // Optional // PaymentMethod value(s)
		array $output = array() // Optional // Pre-existing list array to which to add additional items
	) {

		// Check variables

			// If LoanOrCredit and PaymentMethod are invalid, stop here

				if (
					!$LoanOrCredit
					||
					!$PaymentMethod
				) {

					return $output;

				}

			$output = array_is_list($output) ? $output : array($output);
			$LoanOrCredit =  is_array($occupationalCategory) ? $occupationalCategory : '';
			$PaymentMethod = ( $PaymentMethod && ( is_string($alternateName) || is_array($alternateName) ) ) ? $alternateName : '';

		// LoanOrCredit

			// Add input variable to output array

				if ( $LoanOrCredit ) {

					if ( array_is_list($LoanOrCredit) ) {

						array_merge(
							$output,
							$LoanOrCredit
						);

					} else {

						$output[] = $LoanOrCredit;

					}

				}

		// PaymentMethod

			// Add input variable to output array

				if ( $PaymentMethod ) {

					if (
						is_array($PaymentMethod)
						&&
						array_is_list($PaymentMethod)
					) {

						array_merge(
							$output,
							$PaymentMethod
						);

					} else {

						$output[] = $PaymentMethod;

					}

				}

		// Clean up schema list array

			if ( $output ) {

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($output);

			}

		return $output;

	}

// Add data to an array defining schema data for PostalAddress

	function uamswp_fad_schema_postaladdress(
		string $contactType, // string // Required // A person or organization can have different contact points, for different purposes. For example, a sales contact point, a PR contact point and so on. This property is used to specify the kind of contact point.
		string $address, // string // Required // The street address or the post office box number for PO box addresses.
		bool $address_query, // bool // Required // Query for whether the address is a street address (as opposed to a post office box number)
		string $addressLocality, // string // Required // The locality in which the street address is, and which is in the region. For example, Mountain View.
		string $addressRegion, // string // Required // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
		string $postalCode, // string // Required // The postal code (e.g., 94043).
		$addressCountry = '', // string|array // Optional // The country's ISO 3166-1 alpha-2 country code.
		string $name = '', // string // Optional // The name of the item.
		string $telephone = '', // string // Optional // The telephone number.
		string $faxNumber = '', // string // Optional // The fax number.
		array $schema_PostalAddress = array() // array // Optional // Main address or location schema array
	) {

		/**
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
		 * 				'physical address', // string // Required // A person or organization can have different contact points, for different purposes. For example, a sales contact point, a PR contact point and so on. This property is used to specify the kind of contact point.
		 * 				$location_postOfficeBoxNumber, // string // Required // The street address or the post office box number for PO box addresses.
		 * 				false, // bool // Required // Query for whether the address is a street address (as opposed to a post office box number)
		 * 				$location_addressLocality, // string // Required // The locality in which the street address is, and which is in the region. For example, Mountain View.
		 * 				$location_addressRegion, // string // Required // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
		 * 				$location_postalCode, // string // Required // The postal code (e.g., 94043).
		 * 				'', // string|array // Optional // The country's ISO 3166-1 alpha-2 country code.
		 * 				$location_title = '', // string // Optional // The name of the item.
		 * 				$location_phone_format_dash = '', // string // Optional // The telephone number.
		 * 				$location_fax_format_dash = '', // string // Optional // The fax number.
		 * 				$schema_PostalAddress = array() // array // Optional // Main address or location schema array
		 * 			);
		 */

		// Get common property values

			include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

		// Check/define variables

			// Set fallback value for addressCountry

				if (
					empty($addressCountry)
					||
					$addressCountry == 'US'
					||
					$addressCountry == 'USA'
				) {

					$addressCountry = $schema_common_usa;

				}

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

			// Get full state name from postal abbreviation

				// Value comparison map

					$addressRegion_map = array(
						'AL' => 'Alabama',
						'AK' => 'Alaska',
						'AZ' => 'Arizona',
						'AR' => 'Arkansas',
						'CA' => 'California',
						'CO' => 'Colorado',
						'CT' => 'Connecticut',
						'DE' => 'Delaware',
						'DC' => 'District Of Columbia',
						'FL' => 'Florida',
						'GA' => 'Georgia',
						'HI' => 'Hawaii',
						'ID' => 'Idaho',
						'IL' => 'Illinois',
						'IN' => 'Indiana',
						'IA' => 'Iowa',
						'KS' => 'Kansas',
						'KY' => 'Kentucky',
						'LA' => 'Louisiana',
						'ME' => 'Maine',
						'MD' => 'Maryland',
						'MA' => 'Massachusetts',
						'MI' => 'Michigan',
						'MN' => 'Minnesota',
						'MS' => 'Mississippi',
						'MO' => 'Missouri',
						'MT' => 'Montana',
						'NE' => 'Nebraska',
						'NV' => 'Nevada',
						'NH' => 'New Hampshire',
						'NJ' => 'New Jersey',
						'NM' => 'New Mexico',
						'NY' => 'New York',
						'NC' => 'North Carolina',
						'ND' => 'North Dakota',
						'OH' => 'Ohio',
						'OK' => 'Oklahoma',
						'OR' => 'Oregon',
						'PA' => 'Pennsylvania',
						'RI' => 'Rhode Island',
						'SC' => 'South Carolina',
						'SD' => 'South Dakota',
						'TN' => 'Tennessee',
						'TX' => 'Texas',
						'UT' => 'Utah',
						'VT' => 'Vermont',
						'VA' => 'Virginia',
						'WA' => 'Washington',
						'WV' => 'West Virginia',
						'WI' => 'Wisconsin',
						'WY' => 'Wyoming'
					);

				if ( $addressRegion ) {

					$addressRegion = $addressRegion_map[$addressRegion] ?? $addressRegion;

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
				'contactType' => $contactType,
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

								$values[] = 'https://schema.org/' . $item_MedicalSpecialty;

							}

					}

				}

			// Construct simple list of MedicalSpecialty values

				if ( $values ) {

					$medicalSpecialty_list = $medicalSpecialty_list + $values;
					sort( $medicalSpecialty_list, SORT_NATURAL | SORT_FLAG_CASE );

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
				$list_values = array();
				$output_values = array();

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

						$list_values[] = 'https://schema.org/' . $item;
						$output_values[] = 'https://schema.org/' . $item;

					}

				}

			// Construct simple list of MedicalSpecialty values

				if ( $list_values ) {

					$medicalSpecialty_list = $medicalSpecialty_list + $list_values;
					sort( $medicalSpecialty_list, SORT_NATURAL | SORT_FLAG_CASE );

				}

			// Construct output for base MedicalSpecialty schema function

				if ( $output_values ) {

					$output = uamswp_fad_schema_medicalSpecialty(
						$output_values, // mixed // Required // MedicalSpecialty value(s)
						$schema_medicalSpecialty // Optional // Pre-existing list array to which to add additional items
					);

				}

			return $output;

		}

	// Add data from associated conditions and treatments

		function uamswp_fad_schema_medicalSpecialty_old(
			$schema_medical_specialty = array(), // array // Optional // Main medicalSpecialty schema array
			$name = '', // string // Optional // The name of the item.
			$url = '', // string // Optional // URL of the item.
			$alternate_name = '' // string // Optional // An alias for the item.
		) {

			/**
			 * Example use:
			 *
			 * 	// MedicalSpecialty Schema Data
			 *
			 * 		// Check/define the main medicalSpecialty schema array
			 * 		$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();
			 *
			 * 		// Add this location's details to the main medicalSpecialty schema array
			 * 		$schema_medical_specialty = uamswp_fad_schema_medicalSpecialty_old(
			 * 			$schema_medical_specialty, // array // Optional // Main medicalSpecialty schema array
			 * 			$condition_title_attr, // string // Optional // The name of the item.
			 * 			$condition_url // string // Optional // URL of the item.
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
		$additionalType = null, // string|array // Optional // additionalType // An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. // Allowed schema types: 'Text', 'URL'
		$areaServed = null, // string|array // Optional // 'areaServed' // The geographic area where a service or offered item is provided. // Allowed schema types: 'AdministrativeArea', 'GeoShape', 'Place', 'Text'
		$availableLanguage = null, // string|array // Optional // 'availableLanguage' // A language someone may use with or at the item, service or place. Must use one of the language codes from the IETF BCP 47 standard. // Allowed schema types: 'Language', 'Text'
		$contactOption = null, // string|array enum('HearingImpairedSupported', 'TollFree') // Optional // 'contactOption' // An option available on this contact point. // Allowed schema types: 'ContactPointOption'
		string $contactType = null, // string // Optional // 'contactType' // A person or organization can have different contact points, for different purposes (e.g., sales, PR, bill payment, customer service, technical support). This property is used to specify the kind of contact point. // Allowed schema types: 'Text'
		string $description = null, // string // Optional // 'description' // A description of the item. // Allowed schema types: 'Text'
		string $disambiguatingDescription = null, // string // Optional // 'disambiguatingDescription' // A short description of the item used to disambiguate from other, similar items. // Allowed schema types: 'Text'
		string $email = null, // string // Optional // 'email' // Email address. // Allowed schema types: 'Text'
		string $faxNumber = null, // string // Optional // 'faxNumber' // The fax number. // Allowed schema types: 'Text'
		array $hoursAvailable = null, // array // Optional // 'hoursAvailable' // The hours during which this service or contact is available. // Allowed schema types: 'OpeningHoursSpecification'
		$mainEntityOfPage = null, // string|array // Optional // 'mainEntityOfPage' // Indicates a page (or other CreativeWork) for which this thing is the main entity being described. // Allowed schema types: 'CreativeWork', 'URL'
		array $potentialAction = null, // array // Optional // 'potentialAction' // Indicates a potential Action, which describes an idealized action in which this thing would play an 'object' role. // Allowed schema types: 'Action'
		$productSupported = null, // string|array // Optional // 'productSupported' // The product or service this support contact point is related to (such as product support for a particular product line). // Allowed schema types: 'Product  or Text'
		$sameAs = null, // string|array // Optional // 'sameAs' // URL of a reference Web page that unambiguously indicates the item's identity (e.g., the item's Wikipedia page, the item's Wikidata entry, the item's official website). // Allowed schema types: 'URL'
		array $subjectOf = null, // array // Optional // 'subjectOf' // A CreativeWork or Event about this Thing. // Allowed schema types: 'CreativeWork', 'Event'
		string $telephone = null, // string // Optional // 'telephone' // The telephone number. // Allowed schema types: 'Text'
		string $url = null, // string // Optional // 'url' // URL of the item. // Allowed schema types: 'URL'
		array $output = array() // array // Optional // Pre-existing list array of ContactPoint items to which to add additional items
	) {

		/**
		 * This function is intended to add one ContactPoint schema item to a property.
		 *
		 * The values of arguments using a Schema.org DataType type (i.e., 'Number',
		 * 'Date', 'Time', 'Boolean', 'Text', 'DateTime') are expected to either be
		 * strings or single-dimensional arrays containing only strings.
		 *
		 * The values of arguments using any other Schema.org type
		 * (e.g., 'OpeningHoursSpecification', 'PropertyValue') are expected to be a
		 * fully-formatted string or array (as relevant) before being passed as an
		 * argument of this function.
		 *
		 * $additionalType
		 *
		 *     If the specific ContactPoint item is a telephone number, use the following
		 *     value for the $additionalType argument:
		 *
		 *         'https://www.wikidata.org/wiki/Q214995' // Wikidata entry for telephone number
		 *
		 *     If the specific ContactPoint item is a toll-free telephone number, use the
		 *     following value for the $additionalType argument:
		 *
		 *         ‘https://www.wikidata.org/wiki/Q348308’ // Wikidata entry for toll-free telephone number
		 *
		 *     If the specific ContactPoint item is a fax number, use the same
		 *     value for the $additionalType argument that was used for telephone number
		 *     unless there is a Wikidata entry published that is specific to fax number.
		 *     Include 'fax number' in the contactType property value to further distinguish
		 *     it from a standard telephone number.
		 */

		// Check argument values

			// Check the values that should be either a string or an array

				$additionalType = ( is_string($additionalType) || is_array($additionalType) ) ? $additionalType : null;
				$areaServed = ( is_string($areaServed) || is_array($areaServed) ) ? $areaServed : null;
				$availableLanguage = ( is_string($availableLanguage) || is_array($availableLanguage) ) ? $availableLanguage : null;
				$mainEntityOfPage = ( is_string($mainEntityOfPage) || is_array($mainEntityOfPage) ) ? $mainEntityOfPage : null;
				$productSupported = ( is_string($productSupported) || is_array($productSupported) ) ? $productSupported : null;
				$sameAs = ( is_string($sameAs) || is_array($sameAs) ) ? $sameAs : null;

			// Check the values that should be an array, flattening any single-row multi-dimensional list arrays by one step

				uamswp_fad_flatten_multidimensional_array($hoursAvailable);
				uamswp_fad_flatten_multidimensional_array($potentialAction);
				uamswp_fad_flatten_multidimensional_array($subjectOf);

			// Check argument values that expect enumeration values

				// contactOption

					$contactOption_valid = array(
						'HearingImpairedSupported',
						'TollFree'
					);

					if ( $contactOption ) {

						if (
							is_string($contactOption)
							&&
							!in_array( $contactOption, $contactOption_valid )
						) {

							$contactOption = null;

						} elseif ( is_array($contactOption) ) {

							$contactOption = array_intersect(
								$contactOption_valid,
								$contactOption
							);

						}

					}

			// Check pre-existing list array, nesting the array if it is not a list array

				if ( !array_is_list($output) ) {

					$output = array($output);

				}

		// Create the ContactPoint item

			$ContactPoint_item = array(
				'additionalType' => $additionalType,
				'areaServed' => $areaServed,
				'availableLanguage' => $availableLanguage,
				'contactOption' => $contactOption,
				'contactType' => $contactType,
				'description' => $description,
				'disambiguatingDescription' => $disambiguatingDescription,
				'email' => $email,
				'faxNumber' => $faxNumber,
				'hoursAvailable' => $hoursAvailable,
				'mainEntityOfPage' => $mainEntityOfPage,
				'potentialAction' => $potentialAction,
				'productSupported' => $productSupported,
				'sameAs' => $sameAs,
				'subjectOf' => $subjectOf,
				'telephone' => $telephone,
				'url' => $url
			);

		// Remove rows with empty values from the ContactPoint item

			$ContactPoint_item = array_filter($ContactPoint_item);

		// Add @type to the ContactPoint item

			if ( $ContactPoint_item ) {

				$ContactPoint_item = array( '@type' => 'ContactPoint' ) + $ContactPoint_item;

			}

		// Add the ContactPoint item to the list array

			if ( $ContactPoint_item ) {

				$output[] = $ContactPoint_item;

			}

		// Clean up the list array

			if ( $output ) {

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($output);

			}

		// Return the contactPoint property value array

			return $output;

	}

// Add data to an array defining schema data for faxNumber

	function uamswp_fad_schema_fax_number(
		$schema_fax_number = array(), // array // Optional // Main faxNumber schema array
		$fax_number = '' // string // Optional // The fax number.
	) {

		/**
		 * Example use:
		 *
		 * 	// FaxNumber Schema Data
		 *
		 * 		// Check/define the main faxNumber schema array
		 * 		$schema_fax_number = ( isset($schema_fax_number) && is_array($schema_fax_number) && !empty($schema_fax_number) ) ? $schema_fax_number : array();
		 *
		 * 		// Add this location's details to the main faxNumber schema array
		 * 		$schema_fax_number = uamswp_fad_schema_fax_number(
		 * 			$schema_fax_number, // array // Optional // Main faxNumber schema array
		 * 			$location_fax_format_dash // string // Optional // The fax number.
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

// Add data to an array defining schema data for telephone as the Text type

	function uamswp_fad_schema_telephone_text(
		$telephone_number, // string|array // Required // The telephone number as a string or as a list array containing strings
		array $schema_telephone = array() // array // Optional // Pre-existing list array for telephone (as the Text type) to which to add additional items
	) {

		// Check telephone number value, converting a string to an array

			if (
				$telephone_number
				||
				!is_array($telephone_number)
			) {

				$telephone_number = array($telephone_number);

			}

		// Check pre-existing list array, nesting the array if it is not a list array

			if ( !array_is_list($schema_telephone) ) {

				$schema_telephone = array($schema_telephone);

			}

		// Add values to the main telephone schema array

			if ( $telephone_number ) {

				foreach ( $telephone_number as $item ) {

					if (
						$item
						&&
						is_string($item)
					) {

						$schema_telephone[] = format_phone_dash($item);

					}

				}

			}

		// Format the list array

			$schema_telephone = $schema_telephone ? array_filter($schema_telephone) : array();
			$schema_telephone = $schema_telephone ? array_unique( $schema_telephone, SORT_REGULAR ) : array();
			$schema_telephone = $schema_telephone ? array_values($schema_telephone) : array();

			if ( $schema_telephone ) {

				uamswp_fad_flatten_multidimensional_array($schema_geo_coordinates);

			}

		// Return the list array

			return $schema_telephone;

	}

// Add data to an array defining schema data for hours of operation

	// Add data to an array defining schema data for openingHoursSpecification and specialOpeningHoursSpecification (OpeningHoursSpecification)

		function uamswp_fad_schema_openinghoursspecification(
			$dayOfWeek = null, // array|string // Optional // The day of the week for which these opening hours are valid.
			string $opens = null, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
			string $closes = null, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
			string $validFrom = null, // string // Optional // The date when the item becomes valid. // Date is specified using the ISO 8601 date format (YYYY-MM-DD).
			string $validThrough = null, // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours. //  Date is specified using the ISO 8601 date format (YYYY-MM-DD).
			array $schema_OpeningHoursSpecification = array() // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
		) {

			/**
			 * Schema.org Documentation for the 'OpeningHoursSpecification' type:
			 *
			 *     A structured value providing information about the opening hours of a place or
			 *     a certain service inside a place.
			 *
			 *     The place is open if the opens property is specified, and closed otherwise.
			 *
			 *     If the value for the closes property is less than the value for the opens
			 *     property then the hour range is assumed to span over the next day.
			 *
			 *     Properties from OpeningHoursSpecification:
			 *
			 *         'dayOfWeek'
			 *
			 *             The day of the week for which these opening hours are valid.
			 *
			 *             Expected Type:
			 *
			 *                 - DayOfWeek (Schema.org Enumeration Type)
			 *                       - Sunday
			 *                       - Monday
			 *                       - Tuesday
			 *                       - Wednesday
			 *                       - Thursday
			 *                       - Friday
			 *                       - Saturday
			 *                       - PublicHolidays
			 *
			 *         'opens'
			 *
			 *             The opening hour of the place or service on the given day(s) of the
			 *             week.
			 *
			 *             Expected Type:
			 *
			 *                 - Time
			 *
			 *         'closes'
			 *
			 *             The closing hour of the place or service on the given day(s) of the week.
			 *
			 *             Expected Type:
			 *
			 *                 - Time
			 *
			 *         'validFrom'
			 *
			 *             The date when the item becomes valid.
			 *
			 *             Expected Type:
			 *
			 *                 - Date
			 *                 - DateTime
			 *
			 *         'validThrough'
			 *
			 *             The date after when the item is not valid. For example the end of an offer,
			 *             salary period, or a period of opening hours.
			 *
			 *             Expected Type:
			 *
			 *                 - Date
			 *                 - DateTime
			 *
			 * Google documentation for the 'openingHoursSpecification' property / 'OpeningHoursSpecification' type:
			 * (https://developers.google.com/search/docs/appearance/structured-data/local-business)
			 *
			 *     Standard hours:
			 *
			 *         Excluding the 'validFrom' and 'validThrough' properties signify that the hours
			 *         are valid year-round. This example defines a business that is open weekdays
			 *         from 9am to 9pm, with weekend hours from 10am until 11pm.
			 *
			 *             Set 1 (weekday hours):
			 *
			 *                 $dayOfWeek = array(
			 *                     'Monday',
			 *                     'Tuesday',
			 *                     'Wednesday',
			 *                     'Thursday',
			 *                     'Friday'
			 *                 );
			 *
			 *                 $opens = '09:00';
			 *
			 *                 $closes = '21:00';
			 *
			 *             Set 2 (weekend hours):
			 *
			 *                 $dayOfWeek = array(
			 *                     'Saturday',
			 *                     'Sunday'
			 *                 );
			 *
			 *                 $opens = '10:00';
			 *
			 *                 $closes = '23:00';
			 *
			 *     Late night hours:
			 *
			 *         For hours past midnight, define opening and closing hours using a single
			 *         'OpeningHoursSpecification' property. This example defines hours from Saturday
			 *         at 6pm until Sunday at 3am.
			 *
			 *             $dayOfWeek = 'Saturday';
			 *             $opens = '18:00';
			 *             $closes = '03:00';
			 *
			 *     All-day hours:
			 *
			 *         To show a business as open 24 hours a day, set the 'opens' property to "00:00"
			 *         and the 'closes' property to "23:59".To show a business is closed all day, set
			 *         both 'opens' and 'closes' properties to "00:00". This example shows a business
			 *         open all day Saturday and closed all day Sunday.
			 *
			 *             Set 1 (open all day):
			 *
			 *                 $dayOfWeek = 'Saturday';
			 *                 $opens = '00:00';
			 *                 $closes = '23:59';
			 *
			 *             Set 2 (closed all day):
			 *
			 *                 $dayOfWeek = 'Sunday';
			 *                 $opens = '00:00';
			 *                 $closes = '00:00';
			 *
			 *     Seasonal hours:
			 *
			 *         Use both the 'validFrom' and 'validThrough' properties to define seasonal
			 *         hours. This example shows a business closed for winter holidays.
			 *
			 *             $opens = '00:00';
			 *             $closes = '00:00';
			 *             $validFrom = '2015-12-23';
			 *             $validThrough = '2016-01-05';
			 *
			 * Example use:
			 *
			 * 	// OpeningHoursSpecification Schema Data
			 *
			 * 		// Check/define the main OpeningHoursSpecification schema array
			 * 		$schema_OpeningHoursSpecification = ( isset($schema_OpeningHoursSpecification) && is_array($schema_OpeningHoursSpecification) && !empty($schema_OpeningHoursSpecification) ) ? $schema_OpeningHoursSpecification : array();
			 *
			 * 		// Add this location's details to the main OpeningHoursSpecification schema array
			 *
			 * 			// // Schema.org method: Add all days as an array under the dayOfWeek property
			 * 			// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
			 *
			 * 			// 	$schema_OpeningHoursSpecification = uamswp_fad_schema_openinghoursspecification(
			 * 			// 		$schema_day_of_week, // array|string // Optional // The day of the week for which these opening hours are valid.
			 * 			// 		$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
			 * 			// 		$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
			 * 			// 		$schema_valid_from, // string // Optional // The date when the item becomes valid.
			 * 			// 		$schema_valid_through, // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
			 * 			// 		$schema_OpeningHoursSpecification // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
			 * 			// 	);
			 *
			 * 			// Google method: Loop through all the days defined in the current Hours repeater row separately
			 * 			// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
			 *
			 * 				foreach ( $schema_day_of_week as $day) {
			 * 					$schema_OpeningHoursSpecification = uamswp_fad_schema_openinghoursspecification(
			 * 						$day, // array|string // Optional // The day of the week for which these opening hours are valid.
			 * 						$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
			 * 						$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
			 * 						$schema_valid_from, // string // Optional // The date when the item becomes valid.
			 * 						$schema_valid_through // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours., // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
			 * 						$schema_OpeningHoursSpecification // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
			 * 					);
			 * 				}
			 */

			// Check/define variables

				$schema_OpeningHoursSpecification = is_array($schema_OpeningHoursSpecification) ? $schema_OpeningHoursSpecification : array();
				$dayOfWeek = !empty($dayOfWeek) ? $dayOfWeek : array();

				// If $dayOfWeek argument is not a string, an array or null, bail early

					if (
						$dayOfWeek
						&&
						!is_array($dayOfWeek)
						&&
						!is_string($dayOfWeek)
						&&
						!is_null($dayOfWeek)
					) {

						return $schema_OpeningHoursSpecification;

					}

				// If $dayOfWeek argument is an associative array (not a list array), bail early

					if (
						is_array($dayOfWeek)
						&&
						!array_is_list($dayOfWeek)
					) {

						return $schema_OpeningHoursSpecification;

					}

				// Check $dayOfWeek against list of valid values

					$dayOfWeek_valid = array(
						'Sunday',
						'Monday',
						'Tuesday',
						'Wednesday',
						'Thursday',
						'Friday',
						'Saturday',
						'PublicHolidays'
					);

					if ( $dayOfWeek ) {

						if ( is_array($dayOfWeek) ) {

							$dayOfWeek = array_intersect(
								$dayOfWeek_valid, // array // The array with master values to check.
								$dayOfWeek // array // Arrays to compare values against.
							);

						} else {

							$dayOfWeek = in_array(
								$dayOfWeek, // mixed // The searched value.
								$dayOfWeek_valid // array // The array.
							) ? $dayOfWeek : null;

						}

					}

				// If there is no value in either $dayOfWeek, $validFrom or $validThrough arguments, bail early

					if (
						!$dayOfWeek
						&&
						!$validFrom
						&&
						!$validThrough
					) {

						return $schema_OpeningHoursSpecification;

					}

				// Check whether the pre-existing list is a list array

					if (
						$schema_OpeningHoursSpecification
						&&
						is_array($schema_OpeningHoursSpecification)
						&&
						!array_is_list($schema_OpeningHoursSpecification)
					) {

						$schema_OpeningHoursSpecification = array($schema_OpeningHoursSpecification);

					}

			// Set the timezone from the server

				date_default_timezone_set( wp_timezone_string() );

			// Convert $opens and $closes to the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).

				$opens = $opens ? date( 'H:i:sP', strtotime($opens) ) : null;
				$closes = $closes ? date( 'H:i:sP', strtotime($closes) ) : null;

			// Add time value to $validFrom as midnight, then convert $validFrom to the ISO 8601 date and time format ([-]CCYY-MM-DDThh:mm:ss[Z|(+|-)hh:mm])

				$validFrom = $validFrom ? date( 'c', strtotime( $validFrom . ', midnight' ) ) : null;

			// Add time value to $validThrough Date as the last second of the day, then convert $validThrough to the ISO 8601 date and time format ([-]CCYY-MM-DDThh:mm:ss[Z|(+|-)hh:mm])

				$validThrough = $validThrough ? date( 'c', strtotime( $validThrough . ', 23:59:59' ) ) : null;

			// Base item array

				$schema = array();

			// Add values to the item array

				$schema['dayOfWeek'] = $dayOfWeek;
				$schema['opens'] = $opens;
				$schema['closes'] = $closes;
				$schema['validFrom'] = $validFrom;
				$schema['validThrough'] = $validThrough;

			// Clean up the item array

				$schema = $schema ? array_filter($schema) : null;

			// Add @type to the item array

				if ( $schema ) {

					$schema = array( '@type' => 'OpeningHoursSpecification' ) + $schema;

				}

			// Add the item to the pre-existing list array

				if ( $schema ) {

					$schema_OpeningHoursSpecification[] = $schema;

				}

			// Clean up the pre-existing list array

				uamswp_fad_flatten_multidimensional_array($schema_OpeningHoursSpecification);

			// Return the main address schema array

				return $schema_OpeningHoursSpecification;

		}

	// Add data to an array defining schema data for openingHours (Text)

		function uamswp_fad_schema_openinghours(
			$dayOfWeek, // string|array // Required // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
			string $opens = '', // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
			string $closes = '', // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
			$schema_openingHours = array() // mixed // Optional // Pre-existing list array for openingHours to which to add additional items
		) {

			/**
			 * Schema.org Documentation for the 'openingHours' property:
			 *
			 *     The general opening hours for a business.
			 *
			 *     Opening hours can be specified as a weekly time range, starting with days, then
			 *     times per day.
			 *
			 *     Multiple days can be listed with commas ',' separating each day.
			 *
			 *     Day or time ranges are specified using a hyphen '-'.
			 *
			 *     Days are specified using the following two-letter combinations:
			 *
			 *         - Sunday is specified as 'Su'
			 *         - Monday is specified as 'Mo'
			 *         - Tuesday is specified as 'Tu'
			 *         - Wednesday is specified as 'We'
			 *         - Thursday is specified as 'Th'
			 *         - Friday is specified as 'Fr'
			 *         - Saturday is specified as 'Sa'
			 *
			 *     Times are specified using 24:00 format. For example:
			 *
			 *         - 3 p.m. is specified as '15:00'
			 *         - 10 a.m. is specified as '10:00'
			 *
			 *     Examples:
			 *
			 *         - Tuesdays and Thursdays from 4-8 p.m. is specified as 'Tu,Th 16:00-20:00'
			 *         - All day, seven days a week is specified as 'Mo-Su'
			 *         - Monday through Thursday, 9 a.m.-noon is specified as 'Mo,Tu,We,Th 09:00-12:00'
			 *         - Monday through Friday, 10 a.m.-7 p.m.; Saturday, 10 a.m.-10 p.m.; Sunday, 10 a.m.-9 p.m. is specified as [ 'Mo-Fr 10:00-19:00', 'Sa 10:00-22:00', 'Su 10:00-21:00' ]
			 *
			 *     Values expected to be one of these types
			 *
			 *         - Text
			 *
			 * $opens and $closes arguments:
			 *
			 *     The strings can be any of the following formats:
			 *
			 *         - ##:##
			 *         - #:##
			 *         - ##:## A.M./P.M.
			 *         - ##:## a.m./p.m.
			 *         - ##:## am/pm
			 *         - ##:## AM/PM
			 *         - ##:##A.M./P.M.
			 *         - ##:##a.m./p.m.
			 *         - ##:##am/pm
			 *         - ##:##AM/PM
			 *         - #:## A.M./P.M.
			 *         - #:## a.m./p.m.
			 *         - #:## am/pm
			 *         - #:## AM/PM
			 *         - #:##A.M./P.M.
			 *         - #:##a.m./p.m.
			 *         - #:##am/pm
			 *         - #:##AM/PM
			 *         - ## A.M./P.M.
			 *         - ## a.m./p.m.
			 *         - ## AM/PM
			 *         - ## am/pm
			 *         - ##A.M./P.M.
			 *         - ##a.m./p.m.
			 *         - ##AM/PM
			 *         - ##am/pm
			 *         - # A.M./P.M.
			 *         - # a.m./p.m.
			 *         - # AM/PM
			 *         - # am/pm
			 *         - #A.M./P.M.
			 *         - #a.m./p.m.
			 *         - #AM/PM
			 *         - #am/pm
			 *         - Noon
			 *         - noon
			 *         - Midnight
			 *         - midnight
			 *
			 *     The latest time defined for a day should be '23:59' (a.k.a., 11:59 p.m.).
			 *
			 *     Do not mix 24-hour format of an hour with a.m./p.m. (or their capitalization/punctuation variants).
			 *
			 * Example use:
			 *
			 * 	// openingHours Schema Data
			 *
			 * 		// Check/define the main openingHours schema array
			 * 		$schema_openingHours = ( isset($schema_openingHours) && is_array($schema_openingHours) && !empty($schema_openingHours) ) ? $schema_openingHours : array();
			 *
			 * 		// Add this location's details to the main openingHours schema array
			 *
			 * 			$schema_openingHours = uamswp_fad_schema_openinghours(
			 * 				$schema_day_of_week, // string|array // Required // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
			 * 				$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
			 * 				$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
			 * 				$schema_openingHours // mixed // Optional // Pre-existing list array for openingHours to which to add additional items
			 * 			);
			 */

			// Check/define variables

				// If $dayOfWeek argument is not a string or array, bail early

					if (
						!is_array($dayOfWeek)
						&&
						!is_string($dayOfWeek)
					) {

						return $schema_openingHours;

					}

				// If $dayOfWeek argument is an associative array (not a list array), bail early

					if (
						is_array($dayOfWeek)
						&&
						!array_is_list($dayOfWeek)
					) {

						return $schema_openingHours;

					}

				// Check whether the pre-existing list is a list array

					if (
						$schema_openingHours
						&&
						is_array($schema_openingHours)
						&&
						!array_is_list($schema_openingHours)
					) {

						$schema_openingHours = array($schema_openingHours);

					}

				// Convert full names of days to two-letter abbreviations

					$dayOfWeek_map = array(
						'Monday' => 'Mo',
						'Tuesday' => 'Tu',
						'Wednesday' => 'We',
						'Thursday' => 'Th',
						'Friday' => 'Fr',
						'Saturday' => 'Sa',
						'Sunday' => 'Su'
					);

					if ( is_array($dayOfWeek) ) {

						foreach ( $dayOfWeek as &$item ) {

							if (
								$item
								&&
								is_string($item)
								&&
								array_key_exists( $item, $dayOfWeek_map )
							) {

								$item = $dayOfWeek_map[$item];

							}

						}

					} elseif ( is_string($dayOfWeek) ) {

						if (
							$dayOfWeek
							&&
							is_string($dayOfWeek)
							&&
							array_key_exists( $dayOfWeek, $dayOfWeek_map )
						) {

							$dayOfWeek = $dayOfWeek_map[$dayOfWeek];

						}

					}

				// Convert $opens and $closes to 24-hour format with leading zeroes

					$opens = date( 'H:i', strtotime($opens) );
					$closes = date( 'H:i', strtotime($closes) );

			// Add values to the array

				// Base item

					$schema_item = '';

				// Add day of week

					if ( is_array($dayOfWeek) ) {

						$schema_item .= implode(
							',',
							$dayOfWeek
						);

					} elseif( is_string($dayOfWeek) ) {

						$schema_item .= $dayOfWeek;

					}

				// Add opening and closing time


					if (
						$opens
						&&
						$closes
						&&
						(
							$opens != '00:00'
							&&
							$closes != '23:59'
						)
					) {

						$schema_item .= ' ' . $opens . '-' . $closes;

					}

				// Add the item to the output

					if ( $schema_item ) {

						if ( $schema_openingHours ) {

							if (
								(
									is_array($schema_openingHours)
									&&
									!array_is_list($schema_openingHours)
								)
								||
								!is_array($schema_openingHours)
							) {

								// Nest the existing value in an array to make the value a list array

									$schema_openingHours = array($schema_openingHours);

							}

							// Add the value to the list array

								$schema_openingHours[] = $schema_item;

						} else {

							// Add the value to the output

								$schema_openingHours = $schema_item;

						}

					}

			// Return the output

				return $schema_openingHours;

		}

	// Loop through an input array to add data to an array defining schema data for openingHours, openingHoursSpecification and specialOpeningHoursSpecification

		function uamswp_fad_schema_hours_loop(
			array $input, // array // Required // Array containing the argument values for the openingHours and openingHoursSpecification functions
			string $property, // string enum('openingHours', 'openingHoursSpecification', 'specialOpeningHoursSpecification') // string // Required // Which property to define
			array $output = array() // array // Optional // Pre-existing list array for openingHours, openingHoursSpecification or specialOpeningHoursSpecification to which to add additional items
		) {

			// If $input argument is empty/false or is not an array, bail early

				if (
					!$input
					||
					!is_array($input)
				) {

					return $output;

				}

			// If $property argument is not valid, bail early

				if (
					!$property
					||
					!in_array(
						$property,
						array(
							'openingHours',
							'openingHoursSpecification',
							'specialOpeningHoursSpecification'
						)
					)
				) {

					return $output;

				}

			// Ensure $input argument is a list array

				if (
					is_array($input)
					&&
					!array_is_list($input)
				) {

					$input = array($input);

				}

			// Loop through the input array

				foreach ( $input as $item ) {

					// If $item is empty/false or if $item is not an array, bail on this iteration

						if (
							!$item
							||
							!is_array($item)
						) {

							continue;

						}

					// If 'dayOfWeek' is not set or is empty/false, bail on this iteration

						if (
							!array_key_exists( 'dayOfWeek', $item )
							||
							array_key_exists( 'dayOfWeek', $item ) && !$item['dayOfWeek']
						) {

							continue;

						}

					// Check secondary keys/values

						if (
							!array_key_exists( 'opens', $item )
							||
							array_key_exists( 'opens', $item ) && !$item['opens']
						) {

							$item['opens'] = null;

						}

						if (
							!array_key_exists( 'closes', $item )
							||
							array_key_exists( 'closes', $item ) && !$item['closes']
						) {

							$item['closes'] = null;

						}

						if (
							!array_key_exists( 'validFrom', $item )
							||
							array_key_exists( 'validFrom', $item ) && !$item['validFrom']
						) {

							$item['validFrom'] = null;

						}

						if (
							!array_key_exists( 'validThrough', $item )
							||
							array_key_exists( 'validThrough', $item ) && !$item['validThrough']
						) {

							$item['validThrough'] = null;

						}

					// Ensure existing $output value is a list array

						if (
							$output
							&&
							(
								(
									is_array($output)
									&&
									!array_is_list($output)
								)
								||
								is_string($output)
							)
						) {

							$output = array($output);

						}

					// Add item schema to the output array

						if (
							$property == 'openingHoursSpecification'
							||
							$property == 'specialOpeningHoursSpecification'
						) {

							// openingHoursSpecification or specialOpeningHoursSpecification

								$output = uamswp_fad_schema_openinghoursspecification(
									$item['dayOfWeek'], // array|string // Optional // The day of the week for which these opening hours are valid.
									$item['opens'], // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
									$item['closes'], // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
									$item['validFrom'], // string // Optional // The date when the item becomes valid. // Date is specified using the ISO 8601 date format (YYYY-MM-DD).
									$item['validThrough'], // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours. //  Date is specified using the ISO 8601 date format (YYYY-MM-DD).
									$output // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
								);

						} elseif ( $property == 'openingHours' ) {

							// openingHours

								$output = uamswp_fad_schema_openinghours(
									$item['dayOfWeek'], // string|array // Required // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
									$item['opens'], // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
									$item['closes'], // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
									$output // mixed // Optional // Pre-existing list array for openingHours to which to add additional items
								);

						}

				}

			// Clean up $output

				uamswp_fad_flatten_multidimensional_array($output);

			// Return $output

				return $output;

		}

// Add data to an array defining schema data for GeoCoordinates

	function uamswp_schema_geo_coordinates(
		$latitude, // string|int // Required // The latitude of a location. For example 37.42242 (WGS 84). // The precision must be at least 5 decimal places.
		$longitude, // string|int // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
		$elevation = '', // string|int // Optional // The elevation of a location (WGS 84). Values may be of the form 'NUMBER UNIT_OF_MEASUREMENT' (e.g., '1,000 m', '3,200 ft') while numbers alone should be assumed to be a value in meters.
		array $schema_geo_coordinates = array() // array // Optional // Existing main GeoCoordinates schema array
	) {

		/**
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
		 * 				$schema_latitude, // string|int // Required // The latitude of a location. For example 37.42242 (WGS 84). // The precision must be at least 5 decimal places.
		 * 				$schema_longitude, // string|int // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
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
				'latitude' => number_format(
					$latitude, // float // The number being formatted.
					5, // int // Sets the number of decimal digits. If 0, the decimal_separator is omitted from the return value.
					'.', // ?string // Sets the separator for the decimal point.
					'' // ?string // Sets the thousands separator.
				),
				'longitude' => number_format(
					$longitude, // float // The number being formatted.
					5, // int // Sets the number of decimal digits. If 0, the decimal_separator is omitted from the return value.
					'.', // ?string // Sets the separator for the decimal point.
					'' // ?string // Sets the thousands separator.
				),
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
		int $nesting_level = 1, // int // Optional // Nesting level within the main schema
		array $schema_hospital_affiliation = array() // array // Optional // Pre-existing list array for hospitalAffiliation to which to add additional items
	) {

		// Check/define variables

			$schema_hospital_affiliation_i = 1;

			if ( $schema_hospital_affiliation ) {

				$schema_hospital_affiliation = array_is_list($schema_hospital_affiliation) ? $schema_hospital_affiliation : array($schema_hospital_affiliation);
				$schema_hospital_affiliation_i = $schema_hospital_affiliation ? count($schema_hospital_affiliation) + 1 : 1;

			}

		// Create an array for this item

			$schema = array();

		// Loop through each hospital affiliation

			if ( $hospital_affiliation ) {

				foreach ( $hospital_affiliation as $hospital ) {

					// Eliminate PHP errors / reset variables

						$hospital_term = null;
						$hospital_location = null;

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

							$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

							if ( function_exists('uamswp_fad_schema_location') ) {

								$schema = uamswp_fad_schema_location(
									array($hospital_location), // List of IDs of the location items
									'', // Page URL
									$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
									$nesting_level, // Nesting level within the main schema
									1, // Iteration counter for location-as-MedicalWebPage
									$schema_hospital_affiliation_i, // Iteration counter for location-as-LocalBusiness
									array(), // Pre-existing field values array so duplicate calls can be avoided
									$schema_hospital_affiliation // Pre-existing list array to which to add additional items
								)['LocalBusiness'] ?? array();

							} else {

								$schema = null;

							}

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

			/**
			 * The transient exists.
			 * Return the variable.
			 */

			return $schema;

		} else {

			/**
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
		array $code_repeater = array(), // array // Optional // code repeater field
		array $nucc = array() // array // Optional // Health Care Provider Taxonomy Code Set taxonomy field
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
								'Diseases Database',
								'diseasesdatabase.com'
							),
							'description' => 'identifier sourced on the Diseases Database',
							'name' => 'DiseasesDB',
							'propertyID' => 'https://www.wikidata.org/wiki/Property:P557', // Wikidata property entry for 'DiseasesDB'
							'sameAs' => 'https://bioregistry.io/registry/diseasesdb',
							'url' => 'http://www.diseasesdatabase.com/'
						),
						'ICD-9-code' => array(
							'alternateName' => array(
								'ICD-9 ID',
								'ICD-9',
								'ICD9 code',
								'ICD9 ID',
								'ICD9',
								'International Statistical Classification of Diseases, Ninth Revision',
								'International Statistical Classification of Diseases and Related Health Problems, Ninth Revision'
							),
							'description' => 'identifier in the ICD-9',
							'name' => 'ICD-9 code',
							'propertyID' => 'https://www.wikidata.org/wiki/Property:P493', // Wikidata property entry for 'ICD-9 ID'
							'sameAs' => 'https://bioregistry.io/registry/icd9',
							'url' => 'https://www.cdc.gov/nchs/icd/icd9.htm'
						),
						'ICD-9-CM-code' => array(
							'alternateName' => array(
								'ICD-9-CM ID',
								'ICD-9-CM',
								'ICD9CM code',
								'ICD9CM ID',
								'ICD9CM',
								'ICD-9 Clinical Modification code',
								'ICD-9 Clinical Modification ID',
								'ICD-9 Clinical Modification',
								'International Statistical Classification of Diseases, Ninth Revision, Clinical Modification',
								'International Statistical Classification of Diseases and Related Health Problems, Ninth Revision, Clinical Modification'
							),
							'description' => 'identifier in the ICD-9 Clinical Modification',
							'name' => 'ICD-9-CM code',
							'propertyID' => 'https://www.wikidata.org/wiki/Property:P1692', // Wikidata property entry for 'ICD-9-CM'
							'sameAs' => 'https://bioregistry.io/registry/icd9cm',
							'url' => 'https://www.cdc.gov/nchs/icd/icd9cm.htm'
						),
						'ICD-10-code' => array(
							'alternateName' => array(
								'ICD-10 code',
								'ICD-10 ID',
								'ICD-10',
								'ICD10 code',
								'ICD10 ID',
								'ICD10',
								'International Statistical Classification of Diseases, Tenth Revision',
								'International Statistical Classification of Diseases and Related Health Problems, Tenth Revision'
							),
							'description' => 'identifier in the ICD-10',
							'name' => 'ICD-10 code',
							'propertyID' => 'https://www.wikidata.org/wiki/Property:P494', // Wikidata property entry for 'ICD-10 ID'
							'sameAs' => 'https://bioregistry.io/registry/icd10',
							'url' => 'https://icd.who.int/browse10/'
						),
						'ICD-10-CM-code' => array(
							'alternateName' => array(
								'ICD-10 CM code',
								'ICD-10 CM ID',
								'ICD-10 CM',
								'ICD-10-CM code',
								'ICD-10-CM ID',
								'ICD-10-CM',
								'ICD10CM code',
								'ICD10CM ID',
								'ICD10CM',
								'ICD-10 Clinical Modification code',
								'ICD-10 Clinical Modification ID',
								'ICD-10 Clinical Modification',
								'International Statistical Classification of Diseases, Tenth Revision, Clinical Modification',
								'International Statistical Classification of Diseases and Related Health Problems, Tenth Revision, Clinical Modification'
							),
							'description' => 'identifier in the ICD-10 Clinical Modification',
							'name' => 'ICD-10-CM code',
							'propertyID' => 'https://www.wikidata.org/wiki/Property:P4229', // Wikidata property entry for 'ICD-10-CM'
							'sameAs' => 'https://bioregistry.io/registry/icd10cm',
							'url' => 'https://www.cms.gov/medicare/coding/icd10'
						),
						'ICD-10-PCS-code' => array(
							'alternateName' => array(
								'ICD-10 PCS code',
								'ICD-10 PCS ID',
								'ICD-10 PCS',
								'ICD-10-PCS code',
								'ICD-10-PCS ID',
								'ICD-10-PCS',
								'ICD10PCS code',
								'ICD10PCS ID',
								'ICD10PCS',
								'ICD-10 Procedure Coding System code',
								'ICD-10 Procedure Coding System ID',
								'ICD-10 Procedure Coding System',
								'International Statistical Classification of Diseases, Tenth Revision, Procedure Coding System',
								'International Statistical Classification of Diseases and Related Health Problems, Tenth Revision, Procedure Coding System'
							),
							'description' => 'identifier in the ICD-10 Procedure Coding System',
							'name' => 'ICD-10-PCS code',
							'propertyID' => 'https://www.wikidata.org/wiki/Property:P1690', // Wikidata property entry for 'ICD-10-PCS'
							'sameAs' => 'https://bioregistry.io/registry/icd10pcs',
							'url' => 'https://www.cms.gov/medicare/coding/icd10'
						),
						'ICD-11-code' => array(
							'alternateName' => array(
								'ICD code',
								'ICD ID',
								'ICD-11 MMS code',
								'ICD-11 MMS ID',
								'ICD-11 MMS',
								'ICD-11',
								'ICD11 MMS code',
								'ICD11 MMS ID',
								'ICD11 MMS',
								'ICD11 code',
								'ICD11 ID',
								'ICD11',
								'International Statistical Classification of Diseases, Eleventh Revision',
								'International Classification of Diseases 11th Revision'
							),
							'description' => 'identifier in the ICD-11',
							'name' => 'ICD-11 code',
							'propertyID' => 'https://registry.identifiers.org/registry/icd',
							'sameAs' => 'https://bioregistry.io/registry/icd11',
							'url' => 'https://icd.who.int/en'
						),
						'ICHI-procedure-code' => array(
							'alternateName' => array(
								'ICHI code',
								'International Classification of Health Interventions procedure code',
								'International Classification of Health Interventions code'
							),
							'description' => 'classification system for reporting and statistical analysis of medical interventions',
							'name' => 'ICHI procedure code',
							'propertyID' => 'https://www.who.int/standards/classifications/international-classification-of-health-interventions',
							'sameAs' => 'https://www.wikidata.org/wiki/Q3505045', // Wikidata item entry for 'International Classification of Health Interventions'
							'url' => 'https://www.who.int/standards/classifications/international-classification-of-health-interventions'
						),
						'ICPC-2-ID' => array(
							'alternateName' => array(
								'ICPC-2',
								'ICPC 2 ID',
								'ICPC 2 code',
								'ICPC 2',
								'ICPC2 ID',
								'ICPC2 code',
								'ICPC2e',
								'ICPC-2e',
								'ICPC 2e ID',
								'ICPC 2e code',
								'ICPC 2e',
								'ICPC2e ID',
								'ICPC2e code',
								'ICPC2e',
								'ICPC-2-E',
								'ICPC 2-E ID',
								'ICPC 2-E code',
								'ICPC 2-E',
								'ICPC2-E ID',
								'ICPC2-E code',
								'ICPC2-E',
								'International Classification of Primary Care, Second Revision ID',
								'International Classification of Primary Care, Third Revision ID'
							),
							'description' => 'classification method for primary care encounters',
							'name' => 'ICPC-2 ID',
							'propertyID' => 'https://www.wikidata.org/wiki/Property:P667', // Wikidata property entry for 'ICPC 2 ID'
							'url' => 'https://icpc2.icpc-3.info/'
						),
						'ICPC-3-ID' => array(
							'alternateName' => array(
								'ICPC-3',
								'ICPC-3-EN',
								'ICPC-3-EN ID',
								'ICPC-3-EN code',
								'ICPC 3 ID',
								'ICPC 3 code',
								'ICPC 3',
								'ICPC3 ID',
								'ICPC3 code',
								'ICPC3e',
								'ICPC-3e',
								'ICPC 3e ID',
								'ICPC 3e code',
								'ICPC 3e',
								'ICPC3e ID',
								'ICPC3e code',
								'ICPC3e',
								'ICPC-3-E',
								'ICPC 3-E ID',
								'ICPC 3-E code',
								'ICPC 3-E',
								'ICPC3-E ID',
								'ICPC3-E code',
								'ICPC3-E',
								'International Classification of Primary Care, Third Revision ID',
								'International Classification of Primary Care, Third Revision code'
							),
							'description' => 'classification method for primary care encounters',
							'name' => 'ICPC-3 ID',
							'propertyID' => 'ICPC-3 ID',
							'url' => 'https://www.icpc-3.info/'
						),
						'MeSH-descriptor-ID' => array(
							'alternateName' => array(
								'MeSH unique ID',
								'MeSH ID',
								'Medical Subject Headings descriptor ID',
								'Medical Subject Headings unique ID',
								'Medical Subject Headings ID'
							),
							'description' => 'identifier for Descriptor or Supplementary concept in the Medical Subject Headings controlled vocabulary',
							'name' => 'MeSH descriptor ID',
							'propertyID' => 'https://registry.identifiers.org/registry/mesh',
							'sameAs' => array(
								'https://www.wikidata.org/wiki/Property:P486', // Wikidata property entry for 'MeSH descriptor ID'
								'https://bioregistry.io/registry/mesh'
							),
							'url' => 'https://www.nlm.nih.gov/mesh/meshhome.html'
						),
						'RxNorm-ID' => array(
							'alternateName' => array(
								'RxCui',
								'RxNorm CUI',
								'RXCUI'
							),
							'description' => 'identifier for the normalized clinical drug dictionary of the Unified Medical Language System',
							'name' => 'RxNorm ID',
							'propertyID' => 'https://www.wikidata.org/wiki/Property:P3345', // Wikidata property entry for 'RxNorm ID'
							'sameAs' => 'https://bioregistry.io/registry/rxnorm',
							'url' => 'https://www.nlm.nih.gov/research/umls/rxnorm/'
						),
						'SNOMED-CT-ID' => array(
							'alternateName' => array(
								'SCTID',
								'SNOMED CT identifier',
								'SNOMED Clinical Terms identifier',
								'Systematized Nomenclature of Medicine CT identifier',
								'Systematized Nomenclature of Medicine Clinical Terms identifier'
							),
							'description' => 'identifier in the SNOMED CT catalogue codes for diseases, symptoms and procedures',
							'name' => 'SNOMED CT ID',
							'propertyID' => 'https://registry.identifiers.org/registry/snomedct',
							'sameAs' => 'https://www.wikidata.org/wiki/Property:P5806', // Wikidata property entry for 'SNOMED CT ID'
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

									$identifier_item = array_filter(
										array(
											'@type' => 'PropertyValue',
											'value' => $codeValue, // The value of a QuantitativeValue (including Observation) or property value node.
											'url' => $url // URL of the item.
										)
									);

								// inCodeSet

									/**
									 * A CategoryCodeSet that contains this category code.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - CategoryCodeSet
									 *      - URL
									 *
									 * Used on these types:
									 *
									 *      - CategoryCode
									 *
									 * As of 22 Apr 2024, this term is in the "new" area of Schema.org. Implementation
									 * feedback and adoption from applications and websites can help improve their
									 * definitions.
									 */

									$inCodeSet = $MedicalCode_values[$codingSystem] ?? array();

									if ( $inCodeSet ) {

										$inCodeSet_alternateName = $inCodeSet['alternateName'] ?? array();
										$inCodeSet_description = $inCodeSet['description'] ?? '';
										$inCodeSet_name = $inCodeSet['name'] ?? '';
										$inCodeSet_propertyID = $inCodeSet['propertyID'] ?? '';
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

										if ( $inCodeSet_name || $inCodeSet_propertyID ) {

											$identifier_item['alternateName'] = $inCodeSet_alternateName;
											$identifier_item['description'] = $inCodeSet_description;
											$identifier_item['name'] = $inCodeSet_name;
											$identifier_item['propertyID'] = $inCodeSet_propertyID;
											$identifier_item['sameAs'] = $inCodeSet_sameAs;

										} // endif ( $inCodeSet_name )

									} // endif ( $inCodeSet )

							} // endif ( $codeValue && $codingSystem )

						// Clean up item array

							if ( $code_item ) {

								$code_item = array_filter($code_item);
								ksort( $code_item, SORT_NATURAL | SORT_FLAG_CASE );

							} // endif ( $code_item )

							if ( $identifier_item ) {

								$identifier_item = array_filter($identifier_item);
								ksort( $identifier_item, SORT_NATURAL | SORT_FLAG_CASE );

							} // endif ( $identifier_item )

						// Add to code item to list of codes

							if ( $code_item ) {

								$code_list[] = $code_item;

							} // endif ( $code_item )

					} // endforeach ( $code_repeater as $code )

			} // endif ( $code_repeater )

		// Health Care Provider Taxonomy Code Set taxonomy items

			$code_list = uamswp_fad_schema_nucc_code_set_id(
				$nucc, // int|int[] // Required // List of Clinical Specialization term IDs
				$code_list // array // Optional // Pre-existing schema array for the Health Care Provider Taxonomy code set to which to add items
			);

		// Clean up list array

			if ( $code_list ) {

				$code_list = array_filter($code_list);

				if ( array_is_list($code_list) ) {

					$code_list = array_unique( $code_list, SORT_REGULAR );
					$code_list = array_values($code_list);

				}

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($code_list);

			}

		return $code_list;

	}

// Add data to an array defining schema data for alternateName

	function uamswp_fad_schema_alternatename(
		array $repeater, // array // Required // alternateName repeater field
		string $field_name = 'alternate_text', // string // Optional // alternateName item field name
		$alternateName_schema = array() // mixed // Optional // Pre-existing schema array for alternateName to which to add alternateName items
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

			$alternateName_schema = $alternateName_schema ?: array();
			$alternateName_schema = is_array($alternateName_schema) ? $alternateName_schema : array($alternateName_schema);
			$alternateName_schema = array_is_list($alternateName_schema) ? $alternateName_schema : array($alternateName_schema);

		// Add each repeater row to the list array

			if ( $repeater ) {

				foreach ( $repeater as $alternateName ) {

					$alternateName_schema[] = $alternateName[$field_name];

				} // endforeach ( $repeater as $alternateName )

			} // endif ( $repeater )

		// Clean up list array

			if ( $alternateName_schema ) {

				$alternateName_schema = array_filter($alternateName_schema);
				$alternateName_schema = array_unique( $alternateName_schema, SORT_REGULAR );
			}

			if ( $alternateName_schema ) {

				$alternateName_schema = array_values($alternateName_schema);
				sort( $alternateName_schema, SORT_NATURAL | SORT_FLAG_CASE );

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($alternateName_schema);

			}

		return $alternateName_schema;

	}

// Add data to an array defining schema data for MedicineSystem

	function uamswp_fad_schema_medicinesystem(
		array $input // array of MedicineSystem values
	) {

		/**
		 * The system of medicine that includes this MedicalEntity
		 * (e.g., 'evidence-based,' 'homeopathic,' 'chiropractic').
		 *
		 * Values expected to be one of these types:
		 *
		 *      - MedicineSystem
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

							sort( $MedicineSystem_list, SORT_NATURAL | SORT_FLAG_CASE );

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

		/**
		 * URL of a reference Web page that unambiguously indicates the item's identity
		 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
		 * website).
		 *
		 * Values expected to be one of these types:
		 *
		 *      - URL
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

			if ( $sameAs_schema ) {

				$sameAs_schema = array_is_list($sameAs_schema) ? $sameAs_schema : array($sameAs_schema);

			}

		// Add each repeater row to the list array

			if (
				$repeater
				&&
				$field_name
			) {

				foreach ( $repeater as $sameAs ) {

					if (
						$sameAs
						&&
						array_key_exists( $field_name, $sameAs)
						&&
						$sameAs[$field_name]
					) {

						$sameAs_schema[] = $sameAs[$field_name];

					}

				} // endforeach ( $repeater as $sameAs )

			} // endif ( $repeater )

		// Clean up list array

			if ( $sameAs_schema ) {

				$sameAs_schema = array_filter($sameAs_schema);
				$sameAs_schema = array_unique( $sameAs_schema, SORT_REGULAR );
			}

			if ( $sameAs_schema ) {

				$sameAs_schema = array_values($sameAs_schema);
				sort( $sameAs_schema, SORT_NATURAL | SORT_FLAG_CASE );

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($sameAs_schema);

			}

		return $sameAs_schema;

	}

// Add data to an array defining schema data for additionalType

	function uamswp_fad_schema_additionaltype(
		array $repeater, // additionalType repeater field
		string $field_name = 'schema_additionalType_uri', // additionalType item field name
		array $additionalType_schema = array() // array // Optional // Pre-existing schema array for additionalType to which to add sameAs items
	) {

		/**
		 * An additional type for the item, typically used for adding more specific types
		 * from external vocabularies in microdata syntax. This is a relationship between
		 * something and a class that the thing is in. Typically the value is a
		 * URI-identified RDF class, and in this case corresponds to the use of rdf:type
		 * in RDF. Text values can be used sparingly, for cases where useful information
		 * can be added without their being an appropriate schema to reference. In the
		 * case of text values, the class label should follow the schema.org style guide.
		 *
		 * Subproperty of:
		 *      - rdf:type
		 *
		 * Values expected to be one of these types:
		 *
		 *      - Text
		 *      - URL
		 *
		 * Used on these types:
		 *
		 *      - Thing
		 */

		// Check / define variables

			if ( $additionalType_schema ) {

				$additionalType_schema = array_is_list($additionalType_schema) ? $additionalType_schema : array($additionalType_schema);

			}

		// Add each repeater row to the list array

			if (
				$repeater
				&&
				$field_name
			) {

				foreach ( $repeater as $additionalType ) {

					if (
						$additionalType
						&&
						array_key_exists( $field_name, $additionalType)
						&&
						$additionalType[$field_name]
					) {

						$additionalType_schema[] = $additionalType[$field_name];

					}

				} // endforeach ( $repeater as $additionalType )

				// Clean up list array

					$additionalType_schema = array_filter($additionalType_schema);
					$additionalType_schema = array_unique( $additionalType_schema, SORT_REGULAR );
					$additionalType_schema = array_values($additionalType_schema);
					sort( $additionalType_schema, SORT_NATURAL | SORT_FLAG_CASE );

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($additionalType_schema);

			} // endif ( $repeater )

		return $additionalType_schema;

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
		$propertyID = null, // string|array // Optional // propertyID property value
		string $unitCode = null, // string // Optional // unitCode property value
		string $unitText = null, // string // Optional // unitText property value
		string $url = null, // string // Optional // url property value
		$value = null, // string|array // Optional // value property value
		$valueReference = null, // mixed // Optional // valueReference property value
		array $propertyvalue_list = array() // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
	) {

		/*

			# Arguments Notes

			$value

				If $value is an array, a schema item will be added for each row in the array.

				If there are other property values that should be paired with a specific
				'value' value, then this function should be called within a foreach loop, with
				the values of those properties being added individually within each foreach
				iteration.

			$propertyID

				If $propertyID is an array, it is expected to be a list array with each value
				being a string.

				Example:

				$propertyID = array(
					'https://foo.com/bar',
					'https://baz.com/qux'
				);

			**********

			# Schema.org Definitions

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
				enumeration, organized as a MeasurementMethodEnum.

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

			// If any of the important arguments are empty, end here

				if (
					!$name
					||
					!$value
				) {

					return $propertyvalue_list;

				}

			// Convert value string to value array

				$value = is_array($value) ? $value : array($value);

			// De-duplicate the value array

				$value = array_unique( $value, SORT_REGULAR );
				$value = array_values($value);

		// Set property values

			foreach ( $value as $value_item ) {

				if ( $value_item ) {

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
						'value' => $value_item,
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

							$propertyvalue_list[] = $propertyvalue_item;

						}

				}

			}

		// Clean up the array

			if (
				is_array($propertyvalue_list)
				&&
				!empty($propertyvalue_list)
			) {

				$propertyvalue_list = array_filter($propertyvalue_list);
				$propertyvalue_list = array_unique( $propertyvalue_list, SORT_REGULAR );
				$propertyvalue_list = array_values($propertyvalue_list);

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($propertyvalue_list);

			}

		return $propertyvalue_list;

	}

	// Add data to an array defining schema data for National Provider Identifier (NPI)

		function uamswp_fad_schema_propertyvalue_npi(
			$npi, // string|array // Required // National Provider Identifier
			array $list = array() // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
		) {

			/**
			 * If $npi is an array, it is expected to be a list array with the values being
			 * the NPI value string.
			 *
			 * Example:
			 *
			 *     $npi = array(
			 *         '0123456789',
			 *         '9876543210'
			 *     );
			 */

			if ( !$npi ) {

				return $list;

			}

			// Convert value string to value array

				$npi = is_array($npi) ? $npi : array($npi);

			// De-duplicate the value array

				$npi = array_unique( $npi, SORT_REGULAR );
				$npi = array_values($npi);

			// Loop through the values, construct the schema item and add them to the list array

				foreach ( $npi as $item ) {

					if (
						$item
						&&
						is_string($item)
					) {

						$list = uamswp_fad_schema_propertyvalue(
							'NPI', // mixed // Optional // alternateName property value
							null, // string // Optional // description property value
							null, // int // Optional // maxValue property value
							null, // mixed // Optional // measurementMethod property value
							null, // mixed // Optional // measurementTechnique property value
							null, // int // Optional // minValue property value
							'National Provider Identifier', // string // Optional // name property value
							'https://www.wikidata.org/wiki/Q6975101', // string|array // Optional // propertyID property value
							null, // string // Optional // unitCode property value
							null, // string // Optional // unitText property value
							'https://npiregistry.cms.hhs.gov/provider-view/' . $item, // string // Optional // url property value
							$item, // string|array // Optional // value property value
							null, // mixed // Optional // valueReference property value
							$list // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
						);

					}

				}

			// Clean up the list array

				uamswp_fad_flatten_multidimensional_array($list);

			// Return the list array

				return $list;

		}

	// Add data to an array defining schema data for Google customer ID (CID)

		function uamswp_fad_schema_propertyvalue_google_cid(
			$google_cid, // string|array // Required // Google customer ID
			array $list = array() // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
		) {

			/**
			 * If $google_cid is an array, it is expected to be a list array with the values being
			 * the Google customer ID value string.
			 *
			 * Example:
			 *
			 *     $google_cid = array(
			 *         '0123456789',
			 *         '9876543210'
			 *     );
			 */

			if ( !$google_cid ) {

				return $list;

			}

			if (
				is_array($google_cid)
				&&
				count($google_cid) == 1
			) {

				// If there is only one item, get the value of the item

					$google_cid = reset($google_cid);

			}

			$list = uamswp_fad_schema_propertyvalue(
				array(
					'CID',
					'GM CID',
					'GMCID',
					'Google Ads CID',
					'Google Ads customer ID',
					'Google CID',
					'Google Maps CID',
					'Google Maps customer ID',
					'Google Maps Place CID'
				), // mixed // Optional // alternateName property value
				null, // string // Optional // description property value
				null, // int // Optional // maxValue property value
				null, // mixed // Optional // measurementMethod property value
				null, // mixed // Optional // measurementTechnique property value
				null, // int // Optional // minValue property value
				'Google customer ID', // string // Optional // name property value
				array(
					'https://support.google.com/google-ads/answer/29198', // Google Ads Help Glossary entry for 'Customer ID: Definition'
					'https://www.wikidata.org/wiki/Property:P3749' // Wikidata entry for the 'Google Maps Customer ID' property
				), // string|array // Optional // propertyID property value
				null, // string // Optional // unitCode property value
				null, // string // Optional // unitText property value
				null, // string // Optional // url property value
				$google_cid, // string|array // Optional // value property value
				null, // mixed // Optional // valueReference property value
				$list // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
			);

			return $list;

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

				// Reset variables

					$item_schema = array();
					$item_term = array();
					$item_name = '';
					$item_name_attr = '';
					$item_name_native = '';
					$item_name_native_attr = '';
					$item_bcp47 = '';
					$item_bcp47_attr = '';
					$item_sameAs_array = array();
					$item_sameAs = array();

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
							$item_schema['name'] = $item_name_attr;

						// alternateName

							/**
							 * An alias for the item.
							 *
							 * Expected Type:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 */

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
									$item_schema['alternateName'] = array_unique( $item_schema['alternateName'], SORT_REGULAR );
									$item_schema['alternateName'] = array_values($item_schema['alternateName']);

								}

								// If there is only one item, flatten the multi-dimensional array by one step

									uamswp_fad_flatten_multidimensional_array($item_schema['alternateName']);

								// Sort array

									if ( is_array($item_schema['alternateName']) ) {

										sort( $item_schema['alternateName'], SORT_NATURAL | SORT_FLAG_CASE );

									}

						// sameAs

							/**
							 * URL of a reference Web page that unambiguously indicates the item's identity
							 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
							 * website).
							 *
							 * Expected Type:
							 *
							 *      - URL
							 */

							// Get sameAs repeater field value

								$item_sameAs_array = get_field( 'schema_sameas', $item_term ) ?? array();

							// Add each item to sameAs property values array

								if ( $item_sameAs_array ) {

									$item_sameAs = uamswp_fad_schema_sameas(
										$item_sameAs_array, // sameAs repeater field
										'schema_sameas_url' // sameAs item field name
									);

								}

							// Add to schema item

								if ( $item_sameAs ) {

									$item_schema['sameAs'] = $item_sameAs;

								}

					} // endif

					// Clean up item array

						if ( $item_schema ) {

							$item_schema = array_filter($item_schema);
							$item_schema = array_unique( $item_schema, SORT_REGULAR );

						}

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

				sort( $language_string, SORT_NATURAL | SORT_FLAG_CASE );
				sort( $language_string_attr, SORT_NATURAL | SORT_FLAG_CASE );
				sort( $language_array, SORT_NATURAL | SORT_FLAG_CASE );
				sort( $language_array_attr, SORT_NATURAL | SORT_FLAG_CASE );

			// String lists

				// Standard

					$language_string = $language_string ? implode( ', ', $language_string ) : '';

				// Attribute-friendly

					$language_string_attr = $language_string_attr ? implode( ', ', $language_string_attr ) : '';

		// Clean up schema list array

			if ( $language_schema ) {

				$language_schema = array_filter($language_schema);
				$language_schema = array_unique( $language_schema, SORT_REGULAR );
				$language_schema = array_is_list($language_schema) ? array_values($language_schema) : $language_schema;

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($language_schema);

			}

		return $language_schema;

	}

// Add data to an array defining schema data for health care professional association memberships (memberOf)

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

					/**
					 * The following properties are either beyond the scope of what is being included
					 * in this item schema, irrelevant to this item schema, or are superseded by
					 * another property, and so they will not be included:
					 *
					 *      * acceptedPaymentMethod
					 *      * actionableFeedbackPolicy
					 *      * additionalType
					 *      * address
					 *      * agentInteractionStatistic
					 *      * aggregateRating
					 *      * alumni
					 *      * areaServed
					 *      * award
					 *      * awards
					 *      * brand
					 *      * contactPoint
					 *      * contactPoints
					 *      * correctionsPolicy
					 *      * department
					 *      * description
					 *      * disambiguatingDescription
					 *      * dissolutionDate
					 *      * diversityPolicy
					 *      * diversityStaffingReport
					 *      * duns
					 *      * email
					 *      * employee
					 *      * employees
					 *      * ethicsPolicy
					 *      * event
					 *      * events
					 *      * faxNumber
					 *      * founder
					 *      * founders
					 *      * foundingDate
					 *      * foundingLocation
					 *      * funder
					 *      * funding
					 *      * globalLocationNumber
					 *      * hasCertification
					 *      * hasCredential
					 *      * hasGS1DigitalLink
					 *      * hasMerchantReturnPolicy
					 *      * hasOfferCatalog
					 *      * hasPOS
					 *      * hasProductReturnPolicy
					 *      * identifier
					 *      * image
					 *      * interactionStatistic
					 *      * isicV4
					 *      * iso6523Code
					 *      * keywords
					 *      * knowsAbout
					 *      * knowsLanguage
					 *      * legalName
					 *      * leiCode
					 *      * location
					 *      * logo
					 *      * mainEntityOfPage
					 *      * makesOffer
					 *      * member
					 *      * memberOf
					 *      * members
					 *      * naics
					 *      * nonprofitStatus
					 *      * numberOfEmployees
					 *      * ownershipFundingInfo
					 *      * owns
					 *      * parentOrganization
					 *      * potentialAction
					 *      * publishingPrinciples
					 *      * review
					 *      * reviews
					 *      * seeks
					 *      * serviceArea
					 *      * slogan
					 *      * sponsor
					 *      * subjectOf
					 *      * subOrganization
					 *      * taxID
					 *      * telephone
					 *      * unnamedSourcesPolicy
					 *      * vatID
					 */

					// name

						/**
						 * The name of the item.
						 *
						 * Expected Type:
						 *
						 *      - Text
						 */

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

						/**
						 * An alias for the item.
						 *
						 * Expected Type:
						 *
						 *      - Text
						 *
						 * Used on these types:
						 *
						 *      - Thing
						 */

						// Get alternateName repeater field value

							$association_alternateName_array = get_field( 'schema_alternatename', $association_term ) ?? array();

						// Add each item to alternateName property values array

							if ( $association_alternateName_array ) {

								$association_alternateName = uamswp_fad_schema_alternatename(
									$association_alternateName_array, // array // Required // alternateName repeater field
									'schema_alternatename_text' // string // Optional // alternateName item field name
								);

							}

						// Add to schema item

							if ( $association_alternateName ) {

								$association_schema['alternateName'] = $association_alternateName;

							}

					// sameAs

						/**
						 * URL of a reference Web page that unambiguously indicates the item's identity
						 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
						 * website).
						 *
						 * Expected Type:
						 *
						 *      - URL
						 */

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

						/**
						 * URL of the item.
						 *
						 * Expected Type:
						 *
						 *      - URL
						 */

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

// Add data to an array defining schema data for hasCertification

	function uamswp_fad_schema_hascertification(
		$certifications, // mixed // Required // ID values for certifications
		array $hasCertification_schema = array() // array // Optional // Pre-existing schema array for hasCertification to which to add credential items
	) {

		/*

			'hasCertification' property:

				Certification information about a product, organization, service, place, or person.

				Values expected to be one of these types:

						* Certification

				Used on these types:

						* Organization
						* Person
						* Place
						* Product
						* Service

			'Certification' type:

				A Certification is an official and authoritative statement about a subject, for
				example a product, service, person, or organization. A certification is
				typically issued by an independent certification body, for example a
				professional organization or government. It formally attests certain
				characteristics about the subject, for example Organizations can be ISO
				certified, Food products can be certified Organic or Vegan, a Person can be a
				certified professional, a Place can be certified for food processing. There are
				certifications for many domains: regulatory, organizational, recycling, food,
				efficiency, educational, ecological, etc. A certification is a form of
				credential, as are accreditations and licenses. Mapped from the
				gs1:CertificationDetails [https://www.gs1.org/voc/CertificationDetails] class
				in the GS1 Web Vocabulary.

		*/

		// Check / define variables

			// Convert the certifications IDs value to array (if not already an array)

				$certifications = is_array($certifications) ? $certifications : array($certifications);
				$certifications = array_is_list($certifications) ? $certifications : array($certifications);
				$certifications = array_filter($certifications);
				$certifications = array_values($certifications);

			// If the input is empty, end now

				if ( !$certifications ) {

					return $hasCertification_schema;

				}

			// Convert the pre-existing schema array for hasCertification to a list array (if not already a list array)

				$hasCertification_schema = array_is_list($hasCertification_schema) ? $hasCertification_schema : array($hasCertification_schema);

		// Base values/arrays

			$certification_schema_issuedBy = array(
				'alternateName' => '',
				'additionalType' => '',
				'name' => '',
				'sameAs' => '',
				'url' => '',
			);

			$certification_schema_issuedBy_type = 'Organization';

			$certification_schema = array(
				'additionalType' => '',
				'alternateName' => '',
				'alternativeHeadline' => '',
				'certificationStatus' => '',
				'issuedBy' => '',
				'mainEntityOfPage' => '',
				'name' => '',
				'sameAs' => '',
				'url' => ''
			);

			$certification_schema_type = 'Certification';

		// Get values

			// Loop through each term in the certifications array

				foreach ( $certifications as $item ) {

					// Reset individual item schema array

						$item_schema = $certification_schema;

					if ( $item ) {

						// Get the term

							$item_term = get_term( $item, 'board' ); // WP_Term|array|WP_Error|null

						// Get the attributes from the term

							if ( is_object($item_term) ) {

								/**
								 * The following properties are either beyond the scope of what is being included
								 * in this item schema, irrelevant to this item schema, or are superseded by
								 * another property, and so they will not be included:
								 *
								 *      * about
								 *      * abstract
								 *      * accessMode
								 *      * accessModeSufficient
								 *      * accessibilityAPI
								 *      * accessibilityControl
								 *      * accessibilityFeature
								 *      * accessibilityHazard
								 *      * accessibilitySummary
								 *      * accountablePerson
								 *      * acquireLicensePage
								 *      * aggregateRating
								 *      * archivedAt
								 *      * assesses
								 *      * associatedMedia
								 *      * audience
								 *      * audio
								 *      * auditDate
								 *      * author
								 *      * award
								 *      * awards
								 *      * certificationIdentification
								 *      * certificationRating
								 *      * certificationStatus
								 *      * character
								 *      * citation
								 *      * comment
								 *      * commentCount
								 *      * conditionsOfAccess
								 *      * contentLocation
								 *      * contentRating
								 *      * contentReferenceTime
								 *      * contributor
								 *      * copyrightHolder
								 *      * copyrightNotice
								 *      * copyrightYear
								 *      * correction
								 *      * countryOfOrigin
								 *      * creativeWorkStatus
								 *      * creator
								 *      * creditText
								 *      * dateCreated
								 *      * dateModified
								 *      * datePublished
								 *      * description
								 *      * digitalSourceType
								 *      * disambiguatingDescription
								 *      * discussionUrl
								 *      * editEIDR
								 *      * editor
								 *      * educationalAlignment
								 *      * educationalLevel
								 *      * educationalUse
								 *      * encoding
								 *      * encodingFormat
								 *      * encodings
								 *      * exampleOfWork
								 *      * expires
								 *      * fileFormat
								 *      * funder
								 *      * funding
								 *      * genre
								 *      * hasMeasurement
								 *      * hasPart
								 *      * headline
								 *      * identifier
								 *      * image
								 *      * inLanguage
								 *      * interactionStatistic
								 *      * interactivityType
								 *      * interpretedAsClaim
								 *      * isAccessibleForFree
								 *      * isBasedOn
								 *      * isBasedOnUrl
								 *      * isFamilyFriendly
								 *      * isPartOf
								 *      * keywords
								 *      * learningResourceType
								 *      * license
								 *      * locationCreated
								 *      * logo
								 *      * mainEntity
								 *      * maintainer
								 *      * material
								 *      * materialExtent
								 *      * mentions
								 *      * offers
								 *      * pattern
								 *      * position
								 *      * potentialAction
								 *      * producer
								 *      * provider
								 *      * publication
								 *      * publisher
								 *      * publisherImprint
								 *      * publishingPrinciples
								 *      * recordedAt
								 *      * releasedEvent
								 *      * review
								 *      * reviews
								 *      * schemaVersion
								 *      * sdDatePublished
								 *      * sdLicense
								 *      * sdPublisher
								 *      * size
								 *      * sourceOrganization
								 *      * spatial
								 *      * spatialCoverage
								 *      * sponsor
								 *      * subjectOf
								 *      * teaches
								 *      * temporal
								 *      * temporalCoverage
								 *      * text
								 *      * thumbnail
								 *      * thumbnailUrl
								 *      * timeRequired
								 *      * translationOfWork
								 *      * translator
								 *      * typicalAgeRange
								 *      * usageInfo
								 *      * validFrom
								 *      * validIn
								 *      * version
								 *      * video
								 *      * workExample
								 *      * workTranslation
								 */

								// additionalType

									/**
									 * An additional type for the item, typically used for adding more specific types
									 * from external vocabularies in microdata syntax. This is a relationship between
									 * something and a class that the thing is in. Typically the value is a
									 * URI-identified RDF class, and in this case corresponds to the use of rdf:type
									 * in RDF. Text values can be used sparingly, for cases where useful information
									 * can be added without their being an appropriate schema to reference. In the
									 * case of text values, the class label should follow the schema.org style guide.
									 *
									 * Subproperty of:
									 *      - rdf:type
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *      - URL
									 *
									 * Used on these types:
									 *
									 *      - Thing
									 */

									$item_additionalType = 'https://www.wikidata.org/wiki/Q4931289'; // Wikidata item for 'board certification'
									$item_schema['additionalType'] = $item_additionalType;

								// alternateName

									/**
									 * An alias for the item.
									 *
									 * Expected Type:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Thing
									 */

									// Get alternateName repeater field value

										$item_alternateName_repeater = get_field( 'schema_alternatename', $item_term ) ?? '';

									// Add each item to alternateName property values array

										$item_alternateName = array();

										if ( $item_alternateName_repeater ) {

											$item_alternateName = uamswp_fad_schema_alternatename(
												$item_alternateName_repeater, // array // Required // alternateName repeater field
												'schema_alternatename_text', // string // Optional // alternateName item field name
												$item_alternateName // mixed // Optional // Pre-existing schema array for alternateName to which to add alternateName items
											);

										}

									// Add the value to the schema

										$item_schema['alternateName'] = $item_alternateName;

								// alternativeHeadline

									/**
									 * A secondary title of the CreativeWork.
									 *
									 * Expected Type:
									 *
									 *      - Text
									 */

									$item_alternativeHeadline = $item_alternateName;
									$item_schema['alternativeHeadline'] = $item_alternativeHeadline;

								// certificationStatus

									/**
									 * Indicates the current status of a certification: active or inactive.
									 *
									 * See also gs1:certificationStatus.
									 *
									 * Expected Type:
									 *
									 *      - CertificationStatusEnumeration
									 */

									$item_certificationStatus = 'https://schema.org/CertificationActive';
									$item_schema['certificationStatus'] = $item_certificationStatus;

								// issuedBy

									/**
									 * The organization issuing the item (e.g., a Permit, Ticket, or Certification).
									 *
									 * Expected Type:
									 *
									 *      - Organization
									 */

									// Base array

										$item_issuedBy = $certification_schema_issuedBy;

									// Get the organization value

										$item_issuedBy_id = get_field( 'certificate_certifying_body', $item_term ) ?? 0; // int

										// Get the term

											if ( $item_issuedBy_id ) {

												$item_issuedBy_term = get_term( $item_issuedBy_id, 'certifying_body' ); // WP_Term|array|WP_Error|null

												if ( is_object($item_issuedBy_term) ) {

													// Get the attributes from the organization

														// alternateName

															/**
															 * An alias for the item.
															 *
															 * Expected Type:
															 *
															 *      - Text
															 *
															 * Used on these types:
															 *
															 *      - Thing
															 */

															// Get alternateName repeater field value

																$item_issuedBy_alternateName_repeater = get_field( 'schema_alternatename', $item_issuedBy_term ) ?? array();

															// Add each item to alternateName property values array

																$item_issuedBy_alternateName = array();

																if ( $item_issuedBy_alternateName_repeater ) {

																	$item_issuedBy_alternateName = uamswp_fad_schema_alternatename(
																		$item_issuedBy_alternateName_repeater, // array // Required // alternateName repeater field
																		'schema_alternatename_text', // string // Optional // alternateName item field name
																		$item_issuedBy_alternateName // mixed // Optional // Pre-existing schema array for alternateName to which to add alternateName items
																	);

																}

															// Add the value to the schema

																$item_issuedBy['alternateName'] = $item_issuedBy_alternateName;

														// name

															/**
															 * The name of the item.
															 *
															 * Expected Type:
															 *
															 *      - Text
															 */

															// Get the term name

																$item_issuedBy_name = $item_issuedBy_term->name ?? '';

															// Add the value to the schema

																$item_issuedBy['name'] = $item_issuedBy_name ? uamswp_attr_conversion($item_issuedBy_name) : '';

														// sameAs

															/**
															 * URL of a reference Web page that unambiguously indicates the item's identity
															 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
															 * website).
															 *
															 * Expected Type:
															 *
															 *      - URL
															 */

															// Get sameAs repeater field value

																$item_issuedBy_sameAs_repeater = get_field( 'schema_sameas', $item_issuedBy_term ) ?? array();

															// Add each item to sameAs property values array

																$item_issuedBy_sameAs = array();

																if ( $item_issuedBy_sameAs_repeater ) {

																	$item_issuedBy_sameAs = uamswp_fad_schema_sameas(
																		$item_issuedBy_sameAs_repeater, // array // Required // sameAs repeater field
																		'schema_sameas_url', // string // Optional // sameAs item field name
																		$item_issuedBy_sameAs // array // Optional // Pre-existing schema array for sameAs to which to add sameAs items
																	);

																}

															// Add the value to the schema

																$item_issuedBy['sameAs'] = $item_issuedBy_sameAs;

														// url

															/**
															 * URL of the item.
															 *
															 * Expected Type:
															 *
															 *      - URL
															 */

															// Query: Does this specialty or subspecialty certificate have a webpage on the certifying body's official website?

																$item_issuedBy_url_query = get_field( 'certificate_url_query', $item_issuedBy_term ) ?? false; // bool

															// Get Official Website URL

																$item_issuedBy_url = $item_issuedBy_url_query ? ( get_field( 'schema_url', $item_issuedBy_term ) ?? '' ) : ''; // string

															// Add the value to the schema

																$item_issuedBy['url'] = $item_issuedBy_url;

												}

											}

										// Clean up issuedBy schema

											if ( $item_issuedBy ) {

												$item_issuedBy = array_filter($item_issuedBy);

											}

										// Add final properties and their values to issuedBy schema

											if ( $item_issuedBy ) {

												// Add @id value

													if (
														$item_issuedBy_url
														&&
														$certification_schema_issuedBy_type
													) {

														$item_issuedBy['@id'] = $item_issuedBy_url . '#' . $certification_schema_issuedBy_type;

													}

												// Add @type value

													$item_issuedBy['@type'] = $certification_schema_issuedBy_type;

												// Add additionalType value

													$item_issuedBy['additionalType'] = 'https://www.wikidata.org/wiki/Q87415039'; // Wikidata item for 'accrediting body'

											}

										// Sort issuedBy schema properties

											if ( $item_issuedBy ) {

												ksort($item_issuedBy);

											}

									// Add the value to the schema

										if ( $item_issuedBy ) {

											$item_schema['issuedBy'] = $item_issuedBy;

										}

								// mainEntityOfPage

									/**
									 * Indicates a page (or other CreativeWork) for which this thing is the main
									 * entity being described. See background notes for details.
									 *
									 * Inverse property:
									 *
									 *      - mainEntity
									 *
									 * Values expected to be one of these types:
									 *
									 *      - CreativeWork
									 *      - URL
									 *
									 * Used on these types:
									 *
									 *      - Thing
									 */

									$item_mainEntityOfPage = get_field( 'schema_url', $item_term ) ?? '';
									$item_schema['mainEntityOfPage'] = $item_mainEntityOfPage;

								// name

									/**
									 * The name of the item.
									 *
									 * Expected Type:
									 *
									 *      - Text
									 */

									// Get Official Name of the Specialty or Subspecialty Certificate

										$item_name = get_field( 'certificate_name', $item_term ) ?? '';

									// Fallback: Get the term name

										$item_name = $item_name ?: ($item_term->name ?? '');

									// Add the value to the schema

										$item_schema['name'] = $item_name ? uamswp_attr_conversion($item_name) : '';

								// sameAs

									/**
									 * URL of a reference Web page that unambiguously indicates the item's identity
									 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
									 * website).
									 *
									 * Expected Type:
									 *
									 *      - URL
									 */

									// Get sameAs repeater field value

										$item_sameAs_repeater = get_field( 'schema_sameas', $item_term ) ?? array();

									// Add each item to sameAs property values array

										$item_sameAs = array();

										if ( $item_sameAs_repeater ) {

											$item_sameAs = uamswp_fad_schema_sameas(
												$item_sameAs_repeater, // array // Required // sameAs repeater field
												'schema_sameas_url', // string // Optional // sameAs item field name
												$item_sameAs // array // Optional // Pre-existing schema array for sameAs to which to add sameAs items
											);

										}

									// Add the value to the schema

										$item_schema['sameAs'] = $item_sameAs;

								// url

									/**
									 * URL of the item.
									 *
									 * Expected Type:
									 *
									 *      - URL
									 */

									$item_url = $item_mainEntityOfPage;
									$item_schema['url'] = $item_url;

							}

						// Clean up schema item array

							$item_schema = array_filter($item_schema);

							if ( $item_schema ) {

								ksort($item_schema);

							}

						// Set the schema item's @type property value

							if ( $item_schema ) {

								$item_schema = array( '@type' => $certification_schema_type ) + $item_schema;

							}

						// Set the schema item's @id property value

							if (
								$item_schema
								&&
								$item_url
								&&
								$item_schema['@type']
							) {

								$item_schema = array( '@id' => $item_url . '#' . $item_schema['@type'] ) + $item_schema;

							}

						// Add the schema item to the output array

							if (
								isset($item_schema['name'])
								&&
								!empty($item_schema['name'])
							) {

								$hasCertification_schema[] = $item_schema;

							} // endif

					} // endif ( $item )

				} // endforeach ( $certifications as $item )

		// Clean up schema list array

			if ( $hasCertification_schema ) {

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($hasCertification_schema);

			}

		return $hasCertification_schema;

	}

// Add data to an array defining schema data for hasCredential

	function uamswp_fad_schema_hascredential(
		$credentials, // mixed // Required // ID values for degrees/credentials or certifications
		string $taxonomy, // string // Required // Slug of relevant taxonomy (enum: 'degree', 'board')
		$credential_ctdl = array(), // mixed // Optional // Manually-defined Credential Transparency Description Language classes
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

			// If the taxonomy is invalid, end now

				$taxonomy_valid = array(
					'degree',
					'board'
				);

				if (
					!in_array(
						$taxonomy, // mixed // needle
						$taxonomy_valid // array // haystack
					)
				) {

					return $hasCredential_schema;

				}

			// Convert the credentials IDs value to array (if not already an array)

				$credentials = is_array($credentials) ? $credentials : array($credentials);
				$credentials = array_is_list($credentials) ? $credentials : array($credentials);
				$credentials = array_filter($credentials);
				$credentials = array_values($credentials);

			// If the input is empty, end now

				if ( !$credentials ) {

					return $hasCredential_schema;

				}

			// Convert the Credential Transparency Description Language classes value to array (if not already an array)

				$credential_ctdl = $credential_ctdl ?: array();
				$credential_ctdl = is_array($credential_ctdl) ? $credential_ctdl : array($credential_ctdl);

			// Convert the pre-existing schema array for hasCredential to a list array (if not already a list array)

				$hasCredential_schema = array_is_list($hasCredential_schema) ? $hasCredential_schema : array($hasCredential_schema);

		// Taxonomy field map

			$taxonomy_map = array(
				'degree' => array(
					'alternateName' => 'schema_alternatename',
					'alternateName_arg' => 'schema_alternatename_text',
					'alternateName_from_title' => true,
					'name' => 'degree_name',
					'recognizedBy' => '',
					'recognizedBy_taxonomy' => array(
						'slug' => '',
						'alternateName' => '',
						'alternateName_arg' => '',
						'name' => '',
						'sameAs' => '',
						'url' => '',
						'url_query' => ''
					),
					'sameAs' => 'schema_sameas',
					'sameAs_arg' => 'schema_sameas_url',
					'termCode' => 'degree_ctdl',
					'url' => '',
					'url_query' => ''
				),
				'board' => array(
					'alternateName' => 'schema_alternatename',
					'alternateName_arg' => 'schema_alternatename_text',
					'alternateName_from_title' => false,
					'name' => 'certificate_name',
					'recognizedBy' => 'certificate_certifying_body',
					'recognizedBy_taxonomy' => array(
						'slug' => 'certifying_body',
						'alternateName' => 'schema_alternatename',
						'alternateName_arg' => 'schema_alternatename_text',
						'name' => '',
						'sameAs' => 'schema_sameas',
						'sameAs' => 'schema_sameas_url',
						'url' => 'schema_url',
						'url_query' => 'certifying_body_url_query'
					),
					'sameAs' => 'schema_sameas',
					'sameAs_arg' => 'schema_sameas_url',
					'termCode' => '',
					'url' => 'schema_url',
					'url_query' => 'certificate_url_query'
				)
			);

			// Get the relevant item of the map

				$credential_taxonomy = $taxonomy_map[$taxonomy];

		// Credential Transparency Description Language classes map

			/*

				Values taken from https://credreg.net/ctdl/terms/#classes on Sept. 21, 2023

			*/

			$ctdl_classes_map = array(
				'AccreditAction' => array(
					'label' => 'Accredit Action',
					'definition' => 'Action by an independent, neutral, and authoritative agent that certifies an entity as meeting a prescribed set of standards.'
				),
				'AdvancedStandingAction' => array(
					'label' => 'Advanced Standing Action',
					'definition' => 'Claim by an agent asserting that the object credential of the action provides advanced standing for a credential under the asserting agent\'s authority.'
				),
				'Agent' => array(
					'label' => 'Agent',
					'definition' => 'Organization or person that acts or has the power to act.'
				),
				'AggregateDataProfile' => array(
					'label' => 'Aggregate Data Profile',
					'definition' => 'Resource containing summary statistical data.'
				),
				'AlignmentMap' => array(
					'label' => 'Alignment Map',
					'definition' => 'An entity comprised of a set of alignment or mapping assertions between two existing entities such as mapping a certificate providing advanced standing to a degree.'
				),
				'ApprenticeshipCertificate' => array(
					'label' => 'Apprenticeship Certificate',
					'definition' => 'Credential earned through work-based learning and earn-and-learn models that meet standards and are applicable to industry trades and professions.'
				),
				'ApproveAction' => array(
					'label' => 'Approve Action',
					'definition' => 'Action by an independent, neutral, and authoritative agent that pronounces a favorable judgment of a credential.'
				),
				'Assessment' => array(
					'label' => 'Assessment',
					'definition' => 'Direct, indirect, formative, and summative evaluation or estimation of the nature, ability, or quality of an entity, performance, or outcome of an action.'
				),
				'AssessmentComponent' => array(
					'label' => 'Assessment Component',
					'definition' => 'Resource that identifies a direct, indirect, formative, and summative evaluation or estimation of the nature, ability, or quality of a resource, performance, or outcome of an action.'
				),
				'AssessmentProfile' => array(
					'label' => 'Assessment Profile',
					'definition' => 'Entity that describes the key characteristics of an assessment for a credential.'
				),
				'AssociateDegree' => array(
					'label' => 'Associate Degree',
					'definition' => 'College/university award for students typically completing the first two years of full-time postsecondary school education.'
				),
				'AssociateOfAppliedArtsDegree' => array(
					'label' => 'Associate of Applied Arts Degree',
					'definition' => 'College/university award for students typically completing two years of full-time postsecondary school education in a technical, professional, or vocational program with an emphasis on direct employment in the arts and social sciences fields.'
				),
				'AssociateOfAppliedScienceDegree' => array(
					'label' => 'Associate of Applied Science Degree',
					'definition' => 'College/university award for students typically completing two years of full-time postsecondary school education in a technical, professional, or vocational program with an emphasis on direct employment in applied scientific and technological fields.'
				),
				'AssociateOfArtsDegree' => array(
					'label' => 'Associate of Arts Degree',
					'definition' => 'College/university award for students typically completing the first two years of full-time postsecondary school education with an emphasis in the liberal arts and sciences such as the humanities and social science fields.'
				),
				'AssociateOfScienceDegree' => array(
					'label' => 'Associate of Science Degree',
					'definition' => 'College/university award for students typically completing the first two years of full-time postsecondary school education with an emphasis in scientific and technical fields and professional fields of study.'
				),
				'BachelorDegree' => array(
					'label' => 'Bachelor\'s Degree',
					'definition' => 'College/university award for students typically completing three to five years of education where course work and activities advance skills beyond those of the first one to two years of college/university study.'
				),
				'BachelorOfArtsDegree' => array(
					'label' => 'Bachelor of Arts Degree',
					'definition' => 'College/university award for students typically completing three to five years of education where course work and activities advance skills beyond those of the first one to two years of college/university study, with an emphasis in the liberal arts and sciences such as the humanities and social science fields.'
				),
				'BachelorOfScienceDegree' => array(
					'label' => 'Bachelor of Science Degree',
					'definition' => 'College/university award for students typically completing three to five years of education where course work and activities advance skills beyond those of the first one to two years of college/university study, with an emphasis in scientific and technical fields and professional fields of study.'
				),
				'Badge' => array(
					'label' => 'Badge',
					'definition' => 'Recognition designed to be displayed as a marker of accomplishment, activity, achievement, skill, interest, association, or identity.'
				),
				'BasicComponent' => array(
					'label' => 'Basic Component',
					'definition' => 'Resource that identifies a resource not otherwise covered by the enumerated PathwayComponent subclasses.'
				),
				'Certificate' => array(
					'label' => 'Certificate',
					'definition' => 'Credential that designates requisite knowledge and skills of an occupation, profession, or academic program.'
				),
				'CertificateOfCompletion' => array(
					'label' => 'Certificate of Completion',
					'definition' => 'Credential that acknowledges completion of an assignment, training or other activity.'
				),
				'Certification' => array(
					'label' => 'Certification',
					'definition' => 'Time-limited, revocable, renewable credential awarded by an authoritative body for demonstrating the knowledge, skills, and abilities to perform specific tasks or an occupation.'
				),
				'CocurricularComponent' => array(
					'label' => 'Cocurricular Component',
					'definition' => 'Resource that identifies an activity, program, or informal learning experience such as a civic or service activity that supplements and complements the curriculum.'
				),
				'Collection' => array(
					'label' => 'Collection',
					'definition' => 'Aggregation of related resources.'
				),
				'CollectionMember' => array(
					'label' => 'Collection Member',
					'definition' => 'Provides information about the membership of a resource in a Collection.'
				),
				'CompetencyComponent' => array(
					'label' => 'Competency Component',
					'definition' => 'Resource that identifies a measurable or observable knowledge, skill, or ability necessary to successful performance of a person in a given context.'
				),
				'ComponentCondition' => array(
					'label' => 'Component Condition',
					'definition' => 'Resource that describes what must be done to complete a PathwayComponent, or part thereof, as determined by the issuer of the Pathway.'
				),
				'ConditionManifest' => array(
					'label' => 'Condition Manifest',
					'definition' => 'Set of constraints, prerequisites, entry conditions, or requirements maintained at the organizational and/or sub-organizational level.'
				),
				'ConditionProfile' => array(
					'label' => 'Condition Profile',
					'definition' => 'Entity describing a constraint, prerequisite, entry condition, or requirement.'
				),
				'Constraint' => array(
					'label' => 'Constraint',
					'definition' => 'Resource that identifies the parameters defining a limitation or restriction applicable to candidate pathway components.'
				),
				'ConstraintRule' => array(
					'label' => 'Constraint Rule',
					'definition' => 'Abstract class of executable constraints.'
				),
				'ContactPoint' => array(
					'label' => 'Contact Point',
					'definition' => 'Means of contacting an organization or its representative.'
				),
				'CostManifest' => array(
					'label' => 'Cost Manifest',
					'definition' => 'Entity that describes a set of costs maintained at, and applicable across the organizational and/or sub-organizational level.'
				),
				'CostProfile' => array(
					'label' => 'Cost Profile',
					'definition' => 'Entity that describes direct costs one would incur if one were to pursue a credential, assessment, learning opportunity, or aspects thereof.'
				),
				'Course' => array(
					'label' => 'Course',
					'definition' => 'Single structured sequence of one or more educational activities that aims to develop a prescribed set of competencies of learners.'
				),
				'CourseComponent' => array(
					'label' => 'Course Component',
					'definition' => 'Resource that identifies a structured sequence of one or more learning activities that aims to develop a prescribed set of knowledge, skill, or ability of learners.'
				),
				'Credential' => array(
					'label' => 'Credential',
					'definition' => 'Qualification, achievement, personal or organizational quality, or aspect of an identity typically used to indicate suitability.'
				),
				'CredentialAlignmentObject' => array(
					'label' => 'Credential Alignment Object',
					'definition' => 'Entity describing an affiliation or association between an entity such as a credential, learning opportunity or assessment and another entity in a structured framework such as a concept scheme, enumerated list, or competency framework.'
				),
				'CredentialAssertion' => array(
					'label' => 'Credential Assertion',
					'definition' => 'Representation of a credential awarded to a person.'
				),
				'CredentialComponent' => array(
					'label' => 'Credential Component',
					'definition' => 'Resource that identifies another resource that describes qualification, achievement, personal or organizational quality, or aspect of an identity typically used to indicate suitability.'
				),
				'CredentialFramework' => array(
					'label' => 'Credential Framework',
					'definition' => 'Class of all structured sets of conceptual entities intentionally designed for use as value vocabulary terms for description and classification in the credentialing context.'
				),
				'CredentialingAction' => array(
					'label' => 'Credentialing Action',
					'definition' => 'Action taken by an agent affecting the status of an object entity.'
				),
				'CredentialOrganization' => array(
					'label' => 'Credential Organization',
					'definition' => 'Organization that plays one or more key roles in the lifecycle of a credential.'
				),
				'CredentialPerson' => array(
					'label' => 'CredentialPerson',
					'definition' => 'Person who plays a role as primary agent in a credentialing action.'
				),
				'Degree' => array(
					'label' => 'Degree',
					'definition' => 'Academic credential conferred upon completion of a program or course of study, typically over multiple years at a college or university.'
				),
				'DigitalBadge' => array(
					'label' => 'Digital Badge',
					'definition' => 'Badge offered in digital form.'
				),
				'Diploma' => array(
					'label' => 'Diploma',
					'definition' => 'Credential awarded by educational institutions for successful completion of a course of study or its equivalent.'
				),
				'DoctoralDegree' => array(
					'label' => 'Doctoral Degree',
					'definition' => 'Highest credential award for students who have completed both a bachelor\'s degree and a master\'s degree or their equivalent as well as independent research and/or a significant project or paper.'
				),
				'DurationProfile' => array(
					'label' => 'Duration Profile',
					'definition' => 'Entity describing the temporal aspects of a resource.'
				),
				'EarningsProfile' => array(
					'label' => 'Earnings Profile',
					'definition' => 'Entity that describes earning and related statistical information for a given credential.'
				),
				'EmploymentOutcomeProfile' => array(
					'label' => 'Employment Outcome Profile',
					'definition' => 'Entity that describes employment outcomes and related statistical information for a given credential.'
				),
				'ExtracurricularComponent' => array(
					'label' => 'Extracurricular Component',
					'definition' => 'Resource that identifies an activity, program, or informal learning experience that may be offered or provided by a school, college, or other organization that is not connected to a curriculum.'
				),
				'FinancialAssistanceProfile' => array(
					'label' => 'Financial Assistance Profile',
					'definition' => 'Entity that describes financial assistance that is offered or available.'
				),
				'GeneralEducationDevelopment' => array(
					'label' => 'General Education Development (GED)',
					'definition' => 'Credential awarded by examination that demonstrates that an individual has acquired secondary school-level academic skills.'
				),
				'GeoCoordinates' => array(
					'label' => 'Geographic Coordinates',
					'definition' => 'Geographic coordinates of a place or event including latitude and longitude as well as other locational information.'
				),
				'HoldersProfile' => array(
					'label' => 'Holders Profile',
					'definition' => 'Entity describing the count and related statistical information of holders of a given credential.'
				),
				'IdentifierValue' => array(
					'label' => 'Identifier Value',
					'definition' => 'Means of identifying a resource, typically consisting of an alphanumeric token and a context or scheme from which that token originates.'
				),
				'IndustryClassification' => array(
					'label' => 'Industry Classification',
					'definition' => 'Class of of concept schemes defining industries such as NAICS in the U.S. and ESCO in the European Union.'
				),
				'InstructionalProgramClassification' => array(
					'label' => 'Instructional Program Classification',
					'definition' => 'Class of concept schemes defining instructional program types such as the CIP codes in the U.S.'
				),
				'Job' => array(
					'label' => 'Job',
					'definition' => 'Set of responsibilities based on work roles within an occupation as defined by an employer.'
				),
				'JobComponent' => array(
					'label' => 'Job Component',
					'definition' => 'Resource that identifies a work position, employment, or occupation.'
				),
				'JourneymanCertificate' => array(
					'label' => 'Journeyman Certificate',
					'definition' => 'Credential awarded to skilled workers on successful completion of an apprenticeship in industry trades and professions.'
				),
				'JurisdictionProfile' => array(
					'label' => 'Jurisdiction Profile',
					'definition' => 'Geo-political information about applicable geographic areas and their exceptions.'
				),
				'LearningOpportunity' => array(
					'label' => 'Learning Opportunity',
					'definition' => 'Structured and unstructured learning and development opportunities based in direct experience, formal and informal study, observation, and involvement in discourse and practice.'
				),
				'LearningOpportunityProfile' => array(
					'label' => 'Learning Opportunity Profile',
					'definition' => 'Entity describing an educational or training opportunity.'
				),
				'LearningProgram' => array(
					'label' => 'Learning Program',
					'definition' => 'Set of learning opportunities that leads to an outcome, usually a credential like a degree or certificate.'
				),
				'LearningResource' => array(
					'label' => 'Learning Resource',
					'definition' => 'Entity that is used as part of an learning activity (e.g. a textbook) or that describes (e.g. a lesson plan) or records the educational activity (e.g. an audio- or video-recording of a lesson).'
				),
				'License' => array(
					'label' => 'License',
					'definition' => 'Credential awarded by a government agency or other authorized organization that constitutes legal authority to do a specific job and/or utilize a specific item, system or infrastructure and are typically earned through some combination of degree or certificate attainment, certifications, assessments, work experience, and/or fees, and are time-limited and must be renewed periodically.'
				),
				'MasterCertificate' => array(
					'label' => 'Master Certificate',
					'definition' => 'Credential awarded upon demonstration through apprenticeship of the highest level of skills and performance in industry trades and professions.'
				),
				'MasterDegree' => array(
					'label' => 'Master\'s Degree',
					'definition' => 'Credential awarded for a graduate level course of study where course work and activities advance skills beyond those of the bachelor\'s degree or its equivalent.'
				),
				'MasterOfArtsDegree' => array(
					'label' => 'Master of Arts Degree',
					'definition' => 'Credential awarded for a graduate level course of study where course work and activities advance skills beyond those of the bachelor\'s degree or its equivalent, with an emphasis in the liberal arts and sciences such as the humanities and social science fields.'
				),
				'MasterOfScienceDegree' => array(
					'label' => 'Master of Science Degree',
					'definition' => 'Credential awarded for a graduate level course of study where course work and activities advance skills beyond those of the bachelor\'s degree or its equivalent, with an emphasis in scientific and technical fields and professional fields of study.'
				),
				'MicroCredential' => array(
					'label' => 'Micro-Credential',
					'definition' => 'Credential that addresses a subset of field-specific knowledge, skills, or competencies; often developmental with relationships to other micro-credentials and field credentials.'
				),
				'Occupation' => array(
					'label' => 'Occupation',
					'definition' => 'Profession, trade, or career field that may involve training and/or a formal qualification.'
				),
				'OccupationClassification' => array(
					'label' => 'Occupation Classification',
					'definition' => 'Class of concept schemes identifying occupations such as the Standard Occupational Classification (SOC) system in the U.S. and the European Skills/Competences, Qualifications and Occupations (ESCO).'
				),
				'OfferAction' => array(
					'label' => 'Offer Action',
					'definition' => 'Action by an authoritative agent offering access to a entity such as a credential, learning opportunity or assessment.'
				),
				'OpenBadge' => array(
					'label' => 'Open Badge',
					'definition' => 'Visual symbol containing verifiable claims in accordance with the Open Badges specification and delivered digitally.'
				),
				'Organization' => array(
					'label' => 'Organization',
					'definition' => 'An entity such as an association, company, corporation, club, co-operative, labor union etc.'
				),
				'Pathway' => array(
					'label' => 'Pathway',
					'definition' => 'Resource composed of a structured set of PathwayComponents defining points along a route to fulfillment of a goal or objective.'
				),
				'PathwayComponent' => array(
					'label' => 'Pathway Component',
					'definition' => 'Resource that serves as a defined point along the route of a Pathway which describes an objective and its completion requirements through reference to one or more instances of ComponentCondition.'
				),
				'PathwaySet' => array(
					'label' => 'Pathway Set',
					'definition' => 'A roadmap of multiple related pathways that lead to one or more destinations.'
				),
				'Place' => array(
					'label' => 'Place',
					'definition' => 'Entity describing a physical location or geospatial area.'
				),
				'PostalAddress' => array(
					'label' => 'Postal Address',
					'definition' => 'Entity describing a mailing address.'
				),
				'ProcessProfile' => array(
					'label' => 'Process Profile',
					'definition' => 'Entity describing the type, nature, and other relevant information about a process related to a credential.'
				),
				'ProfessionalDoctorate' => array(
					'label' => 'Professional Doctorate',
					'definition' => 'Doctoral degree conferred upon completion of a program providing the knowledge and skills for the recognition, credential, or license required for professional practice.'
				),
				'QACredentialOrganization' => array(
					'label' => 'QA Credential Organization',
					'definition' => 'Quality assurance organization that plays one or more key roles in the lifecycle of a credential, learning program, or assessment.'
				),
				'QualityAssuranceCredential' => array(
					'label' => 'Quality Assurance Credential',
					'definition' => 'Credential assuring that an organization, program, or awarded credential meets prescribed requirements and may include development and administration of qualifying examinations.'
				),
				'RecognizeAction' => array(
					'label' => 'Recognize Action',
					'definition' => 'Action by an independent, neutral, and authoritative agent acknowledging the validity of a resource.'
				),
				'RegulateAction' => array(
					'label' => 'Regulate Action',
					'definition' => 'Action by an independent, neutral, and authoritative agent enforcing the legal requirements of a resource.'
				),
				'RenewAction' => array(
					'label' => 'Renew Action',
					'definition' => 'Action by an agent renewing an existing credential assertion.'
				),
				'ResearchDoctorate' => array(
					'label' => 'Research Doctorate',
					'definition' => 'Doctoral degree conferred for advanced work beyond the master level, including the preparation and defense of a thesis or dissertation based on original research, or the planning and execution of an original project demonstrating substantial artistic or scholarly achievement.'
				),
				'RevocationProfile' => array(
					'label' => 'Revocation Profile',
					'definition' => 'Entity describing conditions and methods by which a credential can be removed from a holder.'
				),
				'RevokeAction' => array(
					'label' => 'Revoke Action',
					'definition' => 'Action by an agent removing an awarded credential (credential assertion) from the credential holder based on violations or failure of the holder to renew.'
				),
				'RightsAction' => array(
					'label' => 'Rights Action',
					'definition' => 'Action asserting legal rights by an agent to possess, defend, transfer, license, and grant conditional access to a credential, learning opportunity, or assessment.'
				),
				'ScheduledOffering' => array(
					'label' => 'Scheduled Offering',
					'definition' => 'Offering of a Learning Opportunity or Assessment with a schedule associated with a specified location or modality.'
				),
				'SecondarySchoolDiploma' => array(
					'label' => 'Secondary School Diploma',
					'definition' => 'Diploma awarded by secondary education institutions for successful completion of a secondary school program of study.'
				),
				'SpecialistDegree' => array(
					'label' => 'Specialist Degree',
					'definition' => 'Credential awarded for a graduate level course of study where course work and activities advance skills beyond those of the master\'s degree and less than a doctoral degree and provide specific preparation for advanced careers in a specialist field.'
				),
				'SupportService' => array(
					'label' => 'Support Service',
					'definition' => 'Resources and assistance that help people overcome barriers to succeed in their education and career goals.'
				),
				'Task' => array(
					'label' => 'Task',
					'definition' => 'Specific activity, typically related to performing a function or achieving a goal.'
				),
				'TransferIntermediary' => array(
					'label' => 'Transfer Intermediary',
					'definition' => 'Surrogate resource to which other resources are mapped in order to indicate their common transferability.'
				),
				'TransferValueProfile' => array(
					'label' => 'Transfer Value Profile',
					'definition' => 'Description of transfer value of a resource.'
				),
				'ValueProfile' => array(
					'label' => 'Value Profile',
					'definition' => 'A description of value awarded for, required by, or otherwise related to the resource.'
				),
				'VerificationServiceProfile' => array(
					'label' => 'Verification Service Profile',
					'definition' => 'Entity describing the means by which someone can verify whether a credential has been attained.'
				),
				'WorkExperienceComponent' => array(
					'label' => 'Work Experience Component',
					'definition' => 'Resource describing an activity or training through which a person gains job experience.'
				),
				'WorkforceDemandAction' => array(
					'label' => 'Workforce Demand Action',
					'definition' => 'Action taken by an agent asserting that the resource being described has a workforce demand level worthy of note.'
				),
				'WorkRole' => array(
					'label' => 'Work Role',
					'definition' => 'Collection of tasks and competencies that embody a particular function in one or more jobs.'
				)
			);

		// Base values/arrays

			$credential_schema_credentialCategory_inDefinedTermSet = array(
				'@id' => 'http://purl.org/ctdl/terms/#DefinedTermSet',
				'@type' => 'DefinedTermSet',
				'alternateName' => 'CTDL',
				'name' => 'Credential Transparency Description Language',
				'url' => 'http://purl.org/ctdl/terms/'
			);

			$credential_schema_credentialCategory = array(
				'@type' => 'DefinedTerm',
				'description' => '',
				'inDefinedTermSet' => $credential_schema_credentialCategory_inDefinedTermSet,
				'name' => '',
				'termCode' => '',
				'url' => '',
			);

			$credential_schema_recognizedBy = array(
				'additionalType' => '',
				'alternateName' => '',
				'name' => '',
				'sameAs' => '',
				'url' => '',
			);

			$credential_schema_recognizedBy_type = 'Organization';

			$credential_schema = array(
				'alternateName' => '',
				'credentialCategory' => '',
				'name' => '',
				'recognizedBy' => '',
				'sameAs' => '',
				'url' => '',
			);

			$credential_schema_type = 'EducationalOccupationalCredential';

		// Get values

			foreach ( $credentials as $credential ) {

				if ( $credential ) {

					// Base array

						$credential_schema = array();

					// Eliminate PHP errors / reset variables

						$credential_title = null;
						$credential_term = null;
						$credential_name = null;
						$credential_name_field = null;
						$credential_alternateName_repeater = null;
						$credential_alternateName = null;
						$credential_sameAs_repeater = null;
						$credential_sameAs = null;
						$credential_url = null;

					// Get the term

						$credential_term = get_term( $credential, $taxonomy ) ?? array();

					// Get the attributes from the term

						if ( is_object($credential_term) ) {

							/**
							 * The following properties are either beyond the scope of what is being included
							 * in this item schema, irrelevant to this item schema, or are superseded by
							 * another property, and so they will not be included:
							 *
							 *      * about
							 *      * abstract
							 *      * accessMode
							 *      * accessModeSufficient
							 *      * accessibilityAPI
							 *      * accessibilityControl
							 *      * accessibilityFeature
							 *      * accessibilityHazard
							 *      * accessibilitySummary
							 *      * accountablePerson
							 *      * acquireLicensePage
							 *      * additionalType
							 *      * aggregateRating
							 *      * alternativeHeadline
							 *      * archivedAt
							 *      * assesses
							 *      * associatedMedia
							 *      * audience
							 *      * audio
							 *      * author
							 *      * award
							 *      * awards
							 *      * character
							 *      * citation
							 *      * comment
							 *      * commentCount
							 *      * competencyRequired
							 *      * conditionsOfAccess
							 *      * contentLocation
							 *      * contentRating
							 *      * contentReferenceTime
							 *      * contributor
							 *      * copyrightHolder
							 *      * copyrightNotice
							 *      * copyrightYear
							 *      * correction
							 *      * countryOfOrigin
							 *      * creativeWorkStatus
							 *      * creator
							 *      * creditText
							 *      * dateCreated
							 *      * dateModified
							 *      * datePublished
							 *      * description
							 *      * digitalSourceType
							 *      * disambiguatingDescription
							 *      * discussionUrl
							 *      * editEIDR
							 *      * editor
							 *      * educationalAlignment
							 *      * educationalLevel
							 *      * educationalUse
							 *      * encoding
							 *      * encodingFormat
							 *      * encodings
							 *      * exampleOfWork
							 *      * expires
							 *      * fileFormat
							 *      * funder
							 *      * funding
							 *      * genre
							 *      * hasPart
							 *      * headline
							 *      * identifier
							 *      * image
							 *      * inLanguage
							 *      * interactionStatistic
							 *      * interactivityType
							 *      * interpretedAsClaim
							 *      * isAccessibleForFree
							 *      * isBasedOn
							 *      * isBasedOnUrl
							 *      * isFamilyFriendly
							 *      * isPartOf
							 *      * keywords
							 *      * learningResourceType
							 *      * license
							 *      * locationCreated
							 *      * mainEntity
							 *      * mainEntityOfPage
							 *      * maintainer
							 *      * material
							 *      * materialExtent
							 *      * mentions
							 *      * offers
							 *      * pattern
							 *      * position
							 *      * potentialAction
							 *      * producer
							 *      * provider
							 *      * publication
							 *      * publisher
							 *      * publisherImprint
							 *      * publishingPrinciples
							 *      * recordedAt
							 *      * releasedEvent
							 *      * review
							 *      * reviews
							 *      * schemaVersion
							 *      * sdDatePublished
							 *      * sdLicense
							 *      * sdPublisher
							 *      * size
							 *      * sourceOrganization
							 *      * spatial
							 *      * spatialCoverage
							 *      * sponsor
							 *      * subjectOf
							 *      * teaches
							 *      * temporal
							 *      * temporalCoverage
							 *      * text
							 *      * thumbnail
							 *      * thumbnailUrl
							 *      * timeRequired
							 *      * translationOfWork
							 *      * translator
							 *      * typicalAgeRange
							 *      * usageInfo
							 *      * validFor
							 *      * validIn
							 *      * version
							 *      * video
							 *      * workExample
							 *      * workTranslation
							 */

							// name (full name of the clinical degree or credential)

								/**
								 * The name of the item.
								 *
								 * Expected Type:
								 *
								 *      - Text
								 */

								// Get value

									$credential_name = '';

									if ( $credential_taxonomy['name'] ) {

										$credential_name = get_field( $credential_taxonomy['name'], $credential_term ) ?? '';

									}

								// Add to schema item's 'name' property value

									if ( $credential_name ) {

										$credential_schema['name'] = $credential_name;

									} else {

										continue;

									}

							// url query

								$credential_url_query = true;

								if ( $credential_taxonomy['url_query'] ) {

									$credential_url_query = get_field( $credential_taxonomy['url_query'], $credential_term ) ?? true;

								}

							// url

								/**
								 * URL of the item.
								 *
								 * Expected Type:
								 *
								 *      - URL
								 */

								// Get value

									$credential_url = '';

									if (
										$credential_taxonomy['url']
										&&
										$credential_url_query
									) {

										$credential_url = get_field( $credential_taxonomy['url'], $credential_term ) ?? '';

									}

								// Add to schema item's 'url' property value

									if ( $credential_url ) {

										$credential_schema['url'] = $credential_url;

									}

							// alternateName (e.g., abbreviation of the clinical degree or credential)

								/**
								 * An alias for the item.
								 *
								 * Expected Type:
								 *
								 *      - Text
								 *
								 * Used on these types:
								 *
								 *      - Thing
								 */

								// Get values

									// Base 'alternateName' property value array

										$credential_alternateName = array();

									// Get the taxonomy item title

										if ( $credential_taxonomy['alternateName_from_title'] ) {

											// Get the value

												$credential_title = $credential_term->name ?? '';

											// Add the value to the 'alternateName' property value array

												if ( $credential_title ) {

													$credential_alternateName = $credential_title;

												}

										}

									// alternateName repeater

										// Get the alternateName repeater field value

											// Get the field names for the indicated taxonomy

												$credential_alternateName_field = $credential_taxonomy['alternateName'] ?? '';
												$credential_alternateName_arg_field = $credential_taxonomy['alternateName_arg'] ?? 'schema_alternatename_text';

											$credential_alternateName_repeater = $credential_alternateName_field ? ( get_field( $credential_alternateName_field, $credential_term ) ?? array() ) : array();

										// Add each repeater row to 'alternateName' property value array

											if ( $credential_alternateName_repeater ) {

												$credential_alternateName = uamswp_fad_schema_alternatename(
													$credential_alternateName_repeater, // array // Required // alternateName repeater field
													$credential_alternateName_arg_field, // string // Optional // alternateName item field name
													$credential_alternateName // mixed // Optional // Pre-existing schema array for alternateName to which to add alternateName items
												);

											}

								// Set the schema item's 'alternateName' property value

									if ( $credential_alternateName ) {

										$credential_schema['alternateName'] = $credential_alternateName;

									}

							// recognizedBy (e.g., certifying body)

								// Get values

									// Base 'recognizedBy' property value array

										$credential_recognizedBy = array();

									// Get the list of organization IDs

										// Get the field name for the indicated taxonomy

											$credential_recognizedBy_field = $credential_taxonomy['recognizedBy'] ?? '';
											$credential_recognizedBy_taxonomy = $credential_taxonomy['recognizedBy_taxonomy'] ?? array();
											$credential_recognizedBy_slug = $credential_recognizedBy_taxonomy['slug'] ?? '';
											$credential_recognizedBy_field_alternateName = $credential_recognizedBy_taxonomy['alternateName'] ?? '';
											$credential_recognizedBy_field_alternateName_arg = $credential_recognizedBy_taxonomy['alternateName_arg'] ?? 'schema_alternatename_text';
											$credential_recognizedBy_field_name = $credential_recognizedBy_taxonomy['name'] ?? '';
											$credential_recognizedBy_field_sameAs = $credential_recognizedBy_taxonomy['sameAs'] ?? '';
											$credential_recognizedBy_field_sameAs_arg = $credential_recognizedBy_taxonomy['sameAs_arg'] ?? 'schema_sameas_url';
											$credential_recognizedBy_field_url = $credential_recognizedBy_taxonomy['url'] ?? '';
											$credential_recognizedBy_field_url_query = $credential_recognizedBy_taxonomy['url_query'] ?? '';

										$credential_recognizedBy_list = $credential_recognizedBy_field ? ( get_field( $credential_recognizedBy_field, $credential_term ) ?? array() ) : array();
										$credential_recognizedBy_list = is_array($credential_recognizedBy_list) ? $credential_recognizedBy_list : array($credential_recognizedBy_list);

									// Get the attributes of each organization

										if (
											$credential_recognizedBy_list
											&&
											$credential_recognizedBy_slug
										) {

											foreach ( $credential_recognizedBy_list as $item ) {

												// Base item schema array

													$item_schema = array();

												// Reset variables

													$item_term = null;
													$item_name = null;
													$item_url_query = null;
													$item_url = null;
													$item_alternatename_repeater = null;
													$item_alternatename = null;
													$item_sameAs = null;
													$item_sameAs_repeater = null;

												// Get values

													if ( $item ) {

														$item_term = get_term( $item, $credential_recognizedBy_slug ) ?? array();

														if ( is_object($item_term) ) {

															// name

																/**
																 * The name of the item.
																 *
																 * Expected Type:
																 *
																 *      - Text
																 */

																// Get value

																	$item_name = '';

																	if ( $credential_recognizedBy_field_name ) {

																		$item_name = get_field( $credential_recognizedBy_field_name, $item_term ) ?? '';

																	}

																	$item_name = $item_name ?: ( $item_term->name ?? '' );

																// Add to schema item's 'name' property value

																	if ( $item_name ) {

																		$item_schema['name'] = $item_name;

																	} else {

																		continue;

																	}

															// url query

																$item_url_query = true;

																if ( $credential_recognizedBy_field_url_query ) {

																	$item_url_query = get_field( $credential_recognizedBy_field_url_query, $item_term ) ?? true;

																}

															// url

																/**
																 * URL of the item.
																 *
																 * Expected Type:
																 *
																 *      - URL
																 */

																// Get value

																	$item_url = '';

																	if (
																		$credential_recognizedBy_field_url
																		&&
																		$item_url_query
																	) {

																		$item_url = get_field( $credential_recognizedBy_field_url, $item_term ) ?? '';

																	}

																// Add to schema item's 'url' property value

																	if ( $item_url ) {

																		$item_schema['url'] = $item_url;

																	}

															// alternateName

																/**
																 * An alias for the item.
																 *
																 * Expected Type:
																 *
																 *      - Text
																 *
																 * Used on these types:
																 *
																 *      - Thing
																 */

																// Get alternateName repeater field value

																	$item_alternatename_repeater = array();

																	if ( $credential_recognizedBy_field_alternateName ) {

																		$item_alternatename_repeater = get_field( $credential_recognizedBy_field_alternateName, $item_term ) ?? array();

																	}

																	// Add each item to alternateName property values array

																		if ( $item_alternatename_repeater ) {

																			$item_alternatename = uamswp_fad_schema_alternatename(
																				$item_alternatename_repeater, // array // Required // alternateName repeater field
																				$credential_recognizedBy_field_alternateName_arg // string // Optional // alternateName item field name
																			);

																		}

																// Add to schema item's 'alternateName' property value

																	if ( $item_alternatename ) {

																		$item_schema['alternateName'] = $item_alternatename;

																	}

															// sameAs

																/**
																 * URL of a reference Web page that unambiguously indicates the item's identity
																 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
																 * website).
																 *
																 * Expected Type:
																 *
																 *      - URL
																 */

																// Base array

																	$item_sameAs = array();

																// Get sameAs repeater field value

																	$item_sameAs_repeater = array();

																	if ( $credential_recognizedBy_field_sameAs ) {

																		$item_sameAs_repeater = get_field( $credential_recognizedBy_field_sameAs, $item_term ) ?? array();

																	}

																	// Add each item to sameAs property values array

																		if ( $item_sameAs_repeater ) {

																			$item_sameAs = uamswp_fad_schema_sameas(
																				$item_sameAs_repeater, // sameAs repeater field
																				$credential_recognizedBy_field_sameAs_arg // sameAs item field name
																			);

																		}

																// Add to schema item's 'sameAs' property value

																	if ( $item_sameAs ) {

																		$item_schema['sameAs'] = $item_sameAs;

																	}

															// Clean up recognizedBy item schema

																if ( $item_schema ) {

																	$item_schema = array_filter($item_schema);

																}

															// Add final properties and their values to recognizedBy item schema

																if ( $item_schema ) {

																	// Add @id value

																		if (
																			$item_url
																			&&
																			$credential_schema_recognizedBy_type
																		) {

																			$item_schema['@id'] = $item_url . '#' . $credential_schema_recognizedBy_type;

																		}

																	// Add @type value

																		$item_schema['@type'] = $credential_schema_recognizedBy_type;

																	// Add additionalType value

																		// Specialty and Subspecialty Certifications only

																			if ( $taxonomy == 'board' ) {

																				$item_schema['additionalType'] = 'https://www.wikidata.org/wiki/Q87415039'; // Wikidata item for 'accrediting body'

																			}

																}

															// Sort recognizedBy item schema properties

																if ( $item_schema ) {

																	ksort($item_schema);

																}

														}

													}

												// Add to the 'recognizedBy' property value

													if ( $item_schema ) {

														$credential_schema['recognizedBy'] = $item_schema;

													}

											}

										}

								// Set the schema item's 'alternateName' property value

									if ( $credential_alternateName ) {

										$credential_schema['alternateName'] = $credential_alternateName;

									}

							// sameAs

								/**
								 * URL of a reference Web page that unambiguously indicates the item's identity
								 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
								 * website).
								 *
								 * Expected Type:
								 *
								 *      - URL
								 */

								// Get values

									// Base 'sameAs' property value array

										$credential_sameAs = array();

									// sameAs repeater

										// Get the sameAs repeater field value

											// Get the field names for the indicated taxonomy

												$credential_sameAs_field = $credential_taxonomy['sameAs'] ?? '';
												$credential_sameAs_arg_field = $credential_taxonomy['sameAs_arg'] ?? 'schema_sameas_url';

											$credential_sameAs_repeater = $credential_sameAs_field ? ( get_field( $credential_sameAs_field, $credential_term ) ?? array() ) : array();

										// Add each repeater row to 'sameAs' property value array

											if ( $credential_sameAs_repeater ) {

												$credential_sameAs = uamswp_fad_schema_sameas(
													$credential_sameAs_repeater, // sameAs repeater field
													$credential_sameAs_arg_field // sameAs item field name
												);

											}

								// Set the schema item's 'sameAs' property value

									if ( $credential_sameAs ) {

										$credential_schema['sameAs'] = $credential_sameAs;

									}

							// credentialCategory (Credential Transparency Description Language schema term of clinical degree or credential)

								// Get the termCode

									if ( !$credential_ctdl ) {

										// Get the field names for the indicated taxonomy

											$credential_termCode_field = $credential_taxonomy['termCode'] ?? '';

										$credential_ctdl = $credential_termCode_field ? ( get_field( $credential_termCode_field, $credential_term ) ?? array() ) : array();

									}

									// Convert the Credential Transparency Description Language classes value to array (if not already an array)

										$credential_ctdl = $credential_ctdl ?: array();
										$credential_ctdl = is_array($credential_ctdl) ? $credential_ctdl : array($credential_ctdl);

								// Get values

									// Base 'credentialCategory' property value array

										$credential_credentialCategory = array();

									// Get values for each termCode

										if ( $credential_ctdl ) {

											foreach ( $credential_ctdl as $item ) {

												// Base property value item array

													$item_schema = array();

												// Get values

													if ( $item ) {

															$item_schema = array(
																'@id' => 'https://purl.org/ctdl/terms/' . $item . '#DefinedTerm',
																'@type' => 'DefinedTerm',
																'description' => $ctdl_classes_map[$item]['definition'] ?? '',
																'inDefinedTermSet' => $credential_schema_credentialCategory_inDefinedTermSet,
																'name' => $ctdl_classes_map[$item]['label'] ?? '',
																'termCode' => $item,
																'url' => 'https://purl.org/ctdl/terms/' . $item,
															);

													}

												// Clean up property value item array

													if ( $item_schema ) {

														$item_schema = array_filter($item_schema);

													}

												// Add to the schema item's list array

													if ( $item_schema ) {

														$credential_credentialCategory[] = $item_schema;

													}

											}

											// Clean up the schema item's list array

												if ( $credential_credentialCategory ) {

													$credential_credentialCategory = array_filter($credential_credentialCategory);
													$credential_credentialCategory = array_unique( $credential_credentialCategory, SORT_REGULAR );
													$credential_credentialCategory = array_values($credential_credentialCategory);

													// If there is only one item, flatten the multi-dimensional array by one step

														uamswp_fad_flatten_multidimensional_array($credential_credentialCategory);

												}

										}

								// Set the schema item's 'credentialCategory' property value

									if ( $credential_credentialCategory ) {

										$credential_schema['credentialCategory'] = $credential_credentialCategory;

									}

							// Clean up schema item array

								$credential_schema = array_filter($credential_schema);

								if ( $credential_schema ) {

									ksort($credential_schema);

								}

							// Set the schema item's @type property value

								if ( $credential_schema ) {

									$credential_schema = array( '@type' => $credential_schema_type ) + $credential_schema;

								}

							// Set the schema item's @id property value

								if (
									$credential_schema
									&&
									$credential_url
									&&
									$credential_schema['@type']
								) {

									$credential_schema = array( '@id' => $credential_url . '#' . $credential_schema['@type'] ) + $credential_schema;

								}

							// Add the schema item to the output array

								if (
									isset($credential_schema['name'])
									&&
									!empty($credential_schema['name'])
								) {

									$hasCredential_schema[] = $credential_schema;

								}

						} // endif

				} // endif ( $credential )

			} // endforeach ( $credentials as $credential )

		// Clean up schema list array

			if ( $hasCredential_schema ) {

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($hasCredential_schema);

			}

		return $hasCredential_schema;

	}

// Add data to an array defining schema data for hasOccupation

	function uamswp_fad_schema_hasoccupation(
		$alternateName = '', // string|array // optional // alternateName (alternate clinical occupation title value from Clinical Specialization item)
		$description = '', // string // optional // description
		$name = '', // string // optional // name (clinical occupation title value from Clinical Specialization item)
		$occupationalCategory = array(), // array // optional // occupationalCategory
		$sameAs = '', // string|array // optional // sameAs
		array $output = array() // array // Optional // Pre-existing schema array for hasOccupation to which to add hasOccupation items
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

			$alternateName = ( is_string($alternateName) || is_array($alternateName) ) ? $alternateName : '';
			$description =  is_string($description) ? $description : '';
			$name =  is_string($name) ? $name : '';
			$occupationalCategory =  is_array($occupationalCategory) ? $occupationalCategory : '';
			$sameAs = ( is_string($sameAs) || is_array($sameAs) ) ? $sameAs : '';
			$output = array_is_list($output) ? $output : array($output);

		// Base array

			$output_item = array();

		// Get values

			/**
			 * The following properties are either beyond the scope of what is being included
			 * in this item schema, irrelevant to this item schema, or are superseded by
			 * another property, and so they will not be included:
			 *
			 *      * additionalType
			 *      * disambiguatingDescription
			 *      * educationRequirements
			 *      * estimatedSalary
			 *      * experienceRequirements
			 *      * identifier
			 *      * image
			 *      * mainEntityOfPage
			 *      * occupationLocation
			 *      * potentialAction
			 *      * qualifications
			 *      * responsibilities
			 *      * skills
			 *      * subjectOf
			 *      * url
			 */

			// alternateName (alternate clinical occupation title value from Clinical Specialization item)

				/**
				 * An alias for the item.
				 *
				 * Expected Type:
				 *
				 *      - Text
				 *
				 * Used on these types:
				 *
				 *      - Thing
				 */

				if ( $alternateName ) {

					$output_item['alternateName'] = $alternateName;

				}

			// description

				/**
				 * A description of the item.
				 *
				 * Values expected to be one of these types:
				 *
				 *      - Text
				 *      - TextObject
				 *
				 * Used on these types:
				 *
				 *      - Thing
				 *
				 * Sub-properties:
				 *
				 *      - disambiguatingDescription
				 *      - interpretedAsClaim
				 *      - originalMediaContextDescription
				 *      - sha256
				 */

				if ( $description ) {

					$output_item['description'] = $description;

				}

			// name (clinical occupation title value from Clinical Specialization item)

				/**
				 * The name of the item.
				 *
				 * Expected Type:
				 *
				 *      - Text
				 */

				if ( $name ) {

					$output_item['name'] = $name;

				}

			// occupationalCategory

				/*

					Expected array structure:

						$input = array(
							'onetsoc' => array(
								'code' => 'foo',
								'title' => 'bar'
							),
							'isco08' => array(
								'code' => 'baz'
							)
						);

				*/

				if ( $occupationalCategory ) {

					$output_item['occupationalCategory'] = $occupationalCategory;

				}

			// sameAs

				/**
				 * URL of a reference Web page that unambiguously indicates the item's identity
				 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
				 * website).
				 *
				 * Values expected to be one of these types:
				 *
				 *      - URL
				 */

				if ( $sameAs ) {

					$output_item['sameAs'] = $sameAs;

				}

			// Add @type

				if ( $output_item ) {

					$output_item = array( '@type' => 'Occupation' ) + $output_item;

				}

			// Add to the list array

				if ( $output_item ) {

					$output[] = $output_item;

				}

		// Clean up schema list array

			if ( $output ) {

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($output);

			}

		return $output;

	}

	// Add data to an array defining schema data for hasOccupation from Clinical Specialization ID values

		function uamswp_fad_schema_hasoccupation_id(
			$specializations, // mixed // Required // Clinical Specialization ID values
			array $output = array() // array // Optional // Pre-existing schema array for hasOccupation to which to add hasOccupation items
		) {

			// Check / define variables

				$specializations = is_array($specializations) ? $specializations : array($specializations);
				$specializations = array_is_list($specializations) ? $specializations : array($specializations);
				$specializations = array_filter($specializations);
				$specializations = array_values($specializations);

				// If the input is empty, end now

					if ( !$specializations ) {

						return $output;

					}

				$output = array_is_list($output) ? $output : array($output);

			// Get values

				foreach ( $specializations as $specialization ) {

					// Eliminate PHP errors / reset variables

						$output_item = array();
						$specialization_term = null;

					// Base array

						$specialization_schema = array();

					$specialization_term = get_term( $specialization, 'clinical_title' ) ?? '';

					if ( is_object($specialization_term) ) {

						// alternateName (alternate clinical occupation title value from Clinical Specialization item)

							/**
							 * An alias for the item.
							 *
							 * Expected Type:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 */

							// Base array

								$alternateName = array();

							// Get alternateName repeater field value

								$alternateName_repeater = get_field( 'schema_alternatename', $specialization_term ) ?? null;

							// Add each item to alternateName property values array

								if ( $alternateName_repeater ) {

									$alternateName = uamswp_fad_schema_alternatename(
										$alternateName_repeater, // array // Required // alternateName repeater field
										'schema_alternatename_text', // string // Optional // alternateName item field name
										$alternateName // mixed // Optional // Pre-existing schema array for alternateName to which to add alternateName items
									);

								}

						// description

							/**
							 * A description of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *      - TextObject
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 *
							 * Sub-properties:
							 *
							 *      - disambiguatingDescription
							 *      - interpretedAsClaim
							 *      - originalMediaContextDescription
							 *      - sha256
							 */

							$description = get_field( 'clinical_specialization_definition', $specialization_term ) ?? '';

						// name (clinical occupation title value from Clinical Specialization item)

							/**
							 * The name of the item.
							 *
							 * Expected Type:
							 *
							 *      - Text
							 */

							$name = get_field( 'clinical_specialization_title', $specialization_term ) ?? '';

						// occupationalCategory

							// Base array

								$occupationalCategory = array();

							// Get field values

								// Base array

									$occupationalCategory_input = array();

								// Bureau of Labor Statistics (BLS) O*NET-SOC (Occupational Information Network Standard Occupational Classification Taxonomy)

									$occupationalCategory_input['onetsoc']['code'] = get_field( 'clinical_specialization_onetsoc_code', $specialization_term ) ?? ''; // Occupation code
									$occupationalCategory_input['onetsoc']['title'] = get_field( 'clinical_specialization_onetsoc_code_name', $specialization_term ) ?? ''; // Occupation title

								// ISCO-08 Code

									$occupationalCategory_input['isco08']['code'] = get_field( 'clinical_specialization_isco08_code', $specialization_term ) ?? ''; // ISCO-08 occupation code

							// Format schema values

								$occupationalCategory = uamswp_fad_schema_occupationalCategory(
									$occupationalCategory_input, // array // Required // Values for defining occupationalCategory
									$output = array() // array // Optional // Pre-existing schema array for occupationalCategory to which to add occupationalCategory items
								);

						// sameAs

							/**
							 * URL of a reference Web page that unambiguously indicates the item's identity
							 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
							 * website).
							 *
							 * Values expected to be one of these types:
							 *
							 *      - URL
							 */

							// Base array

								$sameAs = array();

							// Get sameAs repeater field value for occupation

								$sameAs_repeater = get_field( 'clinical_specialization_sameas_occupation_schema_sameas', $specialization_term ) ?? null;

								// Add each item to sameAs property values array

									if ( $sameAs_repeater ) {

										$sameAs = uamswp_fad_schema_sameas(
											$sameAs_repeater, // sameAs repeater field
											'schema_sameas_url', // sameAs item field name
											$sameAs // array // Optional // Pre-existing schema array for sameAs to which to add sameAs items
										);

									} else {

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($sameAs);

								}

						// Add to the schema output array

							$output = uamswp_fad_schema_hasoccupation(
								$alternateName, // string|array // optional // alternateName (alternate clinical occupation title value from Clinical Specialization item)
								$description, // string // optional // description
								$name, // string // optional // name (clinical occupation title value from Clinical Specialization item)
								$occupationalCategory, // array // optional // occupationalCategory
								$sameAs, // string|array // optional // sameAs
								$output // array // Optional // Pre-existing schema array for hasOccupation to which to add hasOccupation items
							);

					} // endif

				} // endforeach

			// Clean up schema list array

				if ( $output ) {

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($output);

				}

			return $output;

		}

// Add data to an array defining schema data for occupationalCategory

	function uamswp_fad_schema_occupationalCategory(
		array $input, // array // Required // Values for defining occupationalCategory
		array $output = array() // array // Optional // Pre-existing schema array for occupationalCategory to which to add occupationalCategory items
	) {

		/**
		 * A category describing the job, preferably using a term from a taxonomy such as
		 * BLS O*NET-SOC, ISCO-08 or similar, with the property repeated for each
		 * applicable value. Ideally the taxonomy should be identified, and both the
		 * textual label and formal code for the category should be provided.
		 *
		 * Note: for historical reasons, any textual label and formal code provided as a
		 * literal may be assumed to be from O*NET-SOC.
		 *
		 * Values expected to be one of these types:
		 *
		 *      - CategoryCode
		 *      - Text
		 *
		 * Used on these types:
		 *
		 *      - EducationalOccupationalProgram
		 *      - JobPosting
		 *      - Occupation
		 *      - Physician
		 *      - WorkBasedProgram
		 *
		 * Note: As of 16 Apr 2024, this term is in the "new" area of Schema.org.
		 * Implementation feedback and adoption from applications and websites can help
		 * improve their definitions.
		 */

		/*

			Expected array structure:

				$input = array(
					'onetsoc' => array(
						'code' => 'foo',
						'title' => 'bar'
					),
					'isco08' => array(
						'code' => 'baz'
					)
				);

			*/

		// Bureau of Labor Statistics (BLS) O*NET-SOC (Occupational Information Network Standard Occupational Classification Taxonomy)

			$onetsoc_code = $input['onetsoc']['code'] ?? '';
			$onetsoc_title = $input['onetsoc']['title'] ?? '';

			$output = ( $onetsoc_code && $onetsoc_title ) ? uamswp_fad_schema_occupationalCategory_onetsoc(
				$onetsoc_code, // string // Required // O*Net-SOC occupation code
				$onetsoc_title, // string // Required // O*Net-SOC occupation title
				$output // array // Optional // Pre-existing schema array for occupationalCategory to which to add occupationalCategory items
			) : array();

		// ISCO-08 (International Standard Classification of Occupations)

			$isco08_code = $input['isco08']['code'] ?? '';

			$output = $isco08_code ? uamswp_fad_schema_occupationalCategory_isco08(
				$isco08_code, // string // Required // ISCO-08 occupation code
				false, // bool // Optional // Query for whether to add a occupationalCategory value for each of the code's ancestors
				$output // array // Optional // Pre-existing schema array for occupationalCategory to which to add occupationalCategory items
			) : array();

		return $output;

	}

	// Bureau of Labor Statistics (BLS) O*NET-SOC (Occupational Information Network Standard Occupational Classification Taxonomy)

		function uamswp_fad_schema_occupationalCategory_onetsoc(
			$code, // string // Required // O*Net-SOC occupation code
			$title, // string // Required // O*Net-SOC occupation title
			array $output = array() // array // Optional // Pre-existing schema array for occupationalCategory to which to add occupationalCategory items
		) {

			/**
			 * The Occupational Information Network — or O*NET — is a free online database
			 * that contains hundreds of job definitions to help students, job seekers,
			 * businesses and workforce development professionals to understand today's world
			 * of work in the United States.
			 *
			 * O*NET classifies jobs in job families (functional areas which include workers
			 * from entry level to advanced, and may include several sub-specialties).
			 *
			 * All O*NET occupations conform to the Standard Occupational Classification —
			 * or SOC — the United States government system of classifying occupations.
			 *
			 * Search for occupations at https://www.onetonline.org/find/quick.
			 */

			// Check / define variables

				// If the O*Net-SOC Occupation Code and Name are invalid, stop here

					if (
						!$code
						||
						!$title
					) {

						return $output;

					}

				// Make the values attribute-friendly

					$code = uamswp_attr_conversion($code);
					$title = uamswp_attr_conversion($title);

			// Base item output array

				/**
				 * The following properties are either beyond the scope of what is being included
				 * in this item schema, irrelevant to this item schema, or are superseded by
				 * another property, and so they will not be included:
				 *
				 *      * additionalType
				 *      * alternateName
				 *      * description
				 *      * disambiguatingDescription
				 *      * identifier
				 *      * image
				 *      * inDefinedTermSet
				 *      * mainEntityOfPage
				 *      * potentialAction
				 *      * sameAs
				 *      * subjectOf
				 *      * termCode
				 */

				$output_item = array(
					'@type' => 'CategoryCode',
					'codeValue' => $code, // O*NET-SOC occupation code
					'inCodeSet' => array(
						'@type' => 'CategoryCodeSet',
						'alternateName' => array(
							'BLS O*NET Standard Occupational Classification',
							'BLS O*NET Standard Occupational Classification Taxonomy',
							'BLS O*NET-SOC',
							'BLS O*NET-SOC Taxonomy',
							'BLS Occupational Information Network SOC',
							'BLS Occupational Information Network SOC Taxonomy',
							'BLS Occupational Information Network Standard Occupational Classification',
							'BLS Occupational Information Network Standard Occupational Classification Taxonomy',
							'Bureau of Labor Statistics O*NET Standard Occupational Classification',
							'Bureau of Labor Statistics O*NET Standard Occupational Classification Taxonomy',
							'Bureau of Labor Statistics O*NET-SOC',
							'Bureau of Labor Statistics O*NET-SOC Taxonomy',
							'Bureau of Labor Statistics Occupational Information Network SOC',
							'Bureau of Labor Statistics Occupational Information Network SOC Taxonomy',
							'Bureau of Labor Statistics Occupational Information Network Standard Occupational Classification',
							'Bureau of Labor Statistics Occupational Information Network Standard Occupational Classification Taxonomy',
							'O*NET Standard Occupational Classification',
							'O*NET Standard Occupational Classification Taxonomy',
							'O*NET-SOC Taxonomy',
							'Occupational Information Network SOC',
							'Occupational Information Network SOC Taxonomy',
							'Occupational Information Network Standard Occupational Classification',
							'Occupational Information Network Standard Occupational Classification Taxonomy'
						),
						'dateModified' => '2019',
						'name' => 'O*NET-SOC',
						'url' => 'https://www.onetonline.org/'
					),
					'name' => $title ?? '', // O*NET-SOC occupation title
					'url' => 'https://www.onetonline.org/link/summary/' . $code // O*NET-SOC occupation URL
				);

			// Add the item to the output array

				if ( !$output ) {

					/**
					 * If the pre-existing output array is empty, then set the output value using the
					 * item value.
					 */

					$output = $output_item;

				} elseif ( !array_is_list($output) ) {

					/**
					 * If the pre-existing output array is an associative array, then add both the
					 * pre-existing output array value and the item value to the output array as items
					 * in a list array.
					 */

					$output = array_merge(
						array($output),
						array($output_item)
					);

				} else {

					/**
					 * Otherwise, add the item value to the output array as an additional item.
					 */

					$output[] = $output_item;

				}

			return $output;

		}

	// ISCO-08 (International Standard Classification of Occupations)

		function uamswp_fad_schema_occupationalCategory_isco08(
			$code, // string // Required // // ISCO-08 occupation code
			bool $get_ancestors = false, // bool // Optional // Query for whether to add a occupationalCategory value for each of the code's ancestors
			array $output = array() // array // Optional // Pre-existing schema array for occupationalCategory to which to add occupationalCategory items
		) {

			/**
			 * The International Standard Classification of Occupations — or ISCO — is an
			 * International Labour Organization classification structure for organizing
			 * information on labour and jobs. It is part of the international family of
			 * economic and social classifications of the United Nations. The current version
			 * is known as ISCO-08.
			 *
			 * ISCO-08 is a four-level hierarchically structured classification that allows
			 * all jobs in the world to be classified into 436 unit groups. These groups form
			 * the most detailed level of the classification structure and are aggregated into
			 * 130 minor groups, 43 sub-major groups and 10 major groups, based on their
			 * similarity in terms of the skill level and skill specialization required for
			 * the jobs.
			 *
			 * # Structure and composition of ISCO-08
			 *
			 *     Looking at the hierarchical structure of ISCO-08 from the top down, each of the ten major
			 *     groups is made up of two or more sub-major groups, which in turn are made up of one or more
			 *     minor groups. Each of the 130 minor groups is made up of one or more unit groups. In
			 *     general, each unit group is made up of several "occupations" that have a high degree of
			 *     similarity in terms of skill level and skill specialization.
			 *
			 *     Each group in the classification is designated by a title and code number and is associated
			 *     with a description that specifies the scope of the group.
			 *
			 *         - Major Group is denoted by a 1-digit code (e.g., 2 Professionals)
			 *         - Sub-Major Group is denoted by a 2-digit codes (e.g., 22 Health Professionals)
			 *         - Minor Groups are denoted by 3-digit codes (e.g., 221 Medical doctors)
			 *         - Unit Groups are denoted by 4-digit codes (e.g., 2211 Generalist Medical Practitioners)
			 */

			// Check / define variables

				// If the ISCO-08 code value is invalid, stop here

					if ( !$code ) {

						return $output;

					}

				// Make the values attribute-friendly

					$code = uamswp_attr_conversion($code);

			// ISCO-08 values map

				/**
				 * An array to map attribute values to ISCO-08 group codes for a curated subset of
				 * groups from the ISCO-08.
				 *
				 * Download the ISCO-08 Structure and definitions for all groups at
				 * https://isco-ilo.netlify.app/en/isco-08/.
				 *
				 * The occupation groups selected were those that appeared to be relevant to the
				 * clinical occupations that will be indicated in Find-a-Doc.
				 */

				$isco08_values = array(
					'2' => array(
						'isco08_level' => '1',
						'name' => 'Professionals',
						'description' => 'Professionals increase the existing stock of knowledge; apply scientific or artistic concepts and theories; teach about the foregoing in a systematic manner; or engage in any combination of these activities. Competent performance in most occupations in this major group requires skills at the fourth ISCO skill level.',
						'sameAs' => array()
					),
					'22' => array(
						'isco08_level' => '2',
						'name' => 'Health Professionals',
						'description' => 'Health professionals conduct research; improve or develop concepts, theories and operational methods; and apply scientific  knowledge relating to medicine, nursing, dentistry, veterinary medicine, pharmacy, and  promotion of health. Competent performance in most occupations in this sub-major group requires skills at the fourth ISCO skill level.',
						'sameAs' => array(
							'2'
						)
					),
					'221' => array(
						'isco08_level' => '3',
						'name' => 'Medical Doctors',
						'description' => 'Medical doctors (physicians) study, diagnose, treat and prevent illness, disease, injury, and other physical and mental impairments in humans through the application of the principles and procedures of modern medicine. They plan, supervise and evaluate the implementation of care and treatment plans by other health care providers, and conduct medical education and research activities.',
						'sameAs' => array(
							'2',
							'22'
						)
					),
					'2211' => array(
						'isco08_level' => '4',
						'name' => 'Generalist Medical Practitioners',
						'description' => 'Generalist medical practitioners (including family and primary care doctors) diagnose, treat and prevent illness, disease, injury and other physical and mental impairments and maintain general health in humans through application of the principles and procedures of modern medicine. They do not limit their practice to certain disease categories or methods of treatment, and may assume responsibility for the provision of continuing and comprehensive medical care to individuals, families and communities.',
						'sameAs' => array(
							'2',
							'22',
							'221'
						)
					),
					'2212' => array(
						'isco08_level' => '4',
						'name' => 'Specialist Medical Practitioners',
						'description' => 'Specialist medical practitioners (medical doctors) diagnose, treat and prevent illness, disease, injury, and other physical and mental impairments in humans, using specialized testing, diagnostic, medical, surgical, physical and psychiatric techniques, through application of the principles and procedures of modern medicine. They specialize in certain disease categories, types of patient or methods of treatment and may conduct medical education and research in their chosen areas of specialization.',
						'sameAs' => array(
							'2',
							'22',
							'221'
						)
					),
					'222' => array(
						'isco08_level' => '3',
						'name' => 'Nursing and Midwifery Professionals',
						'description' => 'Nursing and midwifery professionals provide treatment and care services for people who are physically or mentally ill, disabled or infirm, and others in need of care due to potential risks to health including before, during and after childbirth. They assume responsibility for the planning, management and evaluation of the care of patients, including the supervision of other health care workers, working autonomously or in teams with medical doctors and others in the practical application of preventive and curative measures.',
						'sameAs' => array(
							'2',
							'22'
						)
					),
					'2221' => array(
						'isco08_level' => '4',
						'name' => 'Nursing Professionals',
						'description' => 'Nursing professionals provide treatment, support and care services for people who are in need of nursing care due to the effects of ageing, injury, illness or other physical or mental impairment, or potential risks to health. They assume responsibility for the planning and management of the care of patients, including the supervision of other health care workers, working autonomously or in teams with medical doctors and others in the practical application of preventive and curative measures.',
						'sameAs' => array(
							'2',
							'22',
							'222'
						)
					),
					'2222' => array(
						'isco08_level' => '4',
						'name' => 'Midwifery Professionals',
						'description' => 'Midwifery professionals plan, manage, provide and evaluate midwifery care services before, during and after pregnancy and childbirth. They provide delivery care for reducing health risks to women and newborn children, working autonomously or in teams with other health care providers.',
						'sameAs' => array(
							'2',
							'22',
							'222'
						)
					),
					'223' => array(
						'isco08_level' => '3',
						'name' => 'Traditional and Complementary Medicine Professionals',
						'description' => 'Traditional and complementary medicine professionals examine patients, prevent and treat illness, disease, injury, and other physical and mental impairments and maintain general health in humans by applying knowledge, skills and practices acquired through extensive study of  the theories, beliefs and experiences, originating in specific cultures.',
						'sameAs' => array(
							'2',
							'22'
						)
					),
					'2230' => array(
						'isco08_level' => '4',
						'name' => 'Traditional and Complementary Medicine Professionals',
						'description' => 'Traditional and complementary medicine professionals examine patients, prevent and treat illness, disease, injury, and other physical and mental impairments and maintain general health in humans by applying knowledge, skills and practices acquired through extensive study of the theories, beliefs and experiences, originating in specific cultures',
						'sameAs' => array(
							'2',
							'22',
							'223'
						)
					),
					'224' => array(
						'isco08_level' => '3',
						'name' => 'Paramedical Practitioners',
						'description' => 'Paramedical practitioners provide advisory, diagnostic, curative and preventive medical services for humans more limited in scope and complexity than those carried out by medical doctors. They work autonomously or with limited supervision of medical doctors, and apply advanced clinical procedures for treating and preventing diseases, injuries and other physical or mental impairments common to specific communities.',
						'sameAs' => array(
							'2',
							'22'
						)
					),
					'2240' => array(
						'isco08_level' => '4',
						'name' => 'Paramedical Practitioners',
						'description' => 'Paramedical practitioners provide advisory, diagnostic, curative and preventive medical services more limited in scope and complexity than those carried out by medical doctors. They work autonomously, or with limited supervision of medical doctors, and apply advanced clinical procedures for treating and preventing diseases, injuries and other physical or mental impairments common to specific communities.',
						'sameAs' => array(
							'2',
							'22',
							'224'
						)
					),
					'225' => array(
						'isco08_level' => '3',
						'name' => 'Veterinarians',
						'description' => 'Veterinarians diagnose, prevent and treat diseases, injuries and dysfunctions of animals. They may provide care to a wide range of animals or specialize in the treatment of a particular animal group or in a particular area of specialization, or provide professional services to commercial firms producing biological and pharmaceutical products.',
						'sameAs' => array(
							'2',
							'22'
						)
					),
					'2250' => array(
						'isco08_level' => '4',
						'name' => 'Veterinarians',
						'description' => 'Veterinarians diagnose, prevent and treat diseases, injuries and dysfunctions of animals. They may provide care to a wide range of animals or specialize in the treatment of a particular animal group or in a particular specialty area, or provide professional services to commercial firms producing biological and pharmaceutical products.',
						'sameAs' => array(
							'2',
							'22',
							'225'
						)
					),
					'226' => array(
						'isco08_level' => '3',
						'name' => 'Other Health Professionals',
						'description' => 'Other health professionals provide health services related to dentistry, pharmacy, environmental health and hygiene, occupational health and safety, physiotherapy, nutrition, hearing, speech, vision and rehabilitation therapies.  This minor group includes all human health professionals, except doctors, traditional and complementary medicine practitioners, nurses, midwives and paramedical professionals.',
						'sameAs' => array(
							'2',
							'22'
						)
					),
					'2261' => array(
						'isco08_level' => '4',
						'name' => 'Dentists',
						'description' => 'Dentists diagnose, treat and prevent diseases, injuries and abnormalities of the teeth, mouth, jaws and associated tissues by applying the principles and procedures of modern dentistry. They use a broad range of specialized diagnostic, surgical and other techniques to promote and restore oral health.',
						'sameAs' => array(
							'2',
							'22',
							'226'
						)
					),
					'2262' => array(
						'isco08_level' => '4',
						'name' => 'Pharmacists',
						'description' => 'Pharmacists store, preserve, compound, and dispense medicinal products and counsel on the proper use and adverse effects of drugs and medicines following prescriptions issued by medical doctors and other health professionals. They contribute to researching, testing, preparing, prescribing and monitoring medicinal therapies for optimizing human health.',
						'sameAs' => array(
							'2',
							'22',
							'226'
						)
					),
					'2263' => array(
						'isco08_level' => '4',
						'name' => 'Environmental and Occupational Health and Hygiene Professionals',
						'description' => 'Environmental and occupational health and hygiene professionals assess, plan and implement programmes to recognize, monitor and control environmental factors that can potentially affect human health, to ensure safe and healthy working conditions and to prevent disease or injury caused by chemical, physical, radiological and biological agents or ergonomic factors.',
						'sameAs' => array(
							'2',
							'22',
							'226'
						)
					),
					'2264' => array(
						'isco08_level' => '4',
						'name' => 'Physiotherapists',
						'description' => 'Physiotherapists assess, plan and implement rehabilitative programmes that improve or restore human motor functions, maximize movement ability, relieve pain syndromes, and treat or prevent physical challenges associated with injuries, diseases and other impairments. They apply a broad range of physical therapies and techniques such as movement, ultrasound, heating, laser and other techniques.',
						'sameAs' => array(
							'2',
							'22',
							'226'
						)
					),
					'2265' => array(
						'isco08_level' => '4',
						'name' => 'Dieticians and Nutritionists',
						'description' => 'Dieticians and nutritionists assess, plan and implement programmes to enhance the impact of food and nutrition on human health.',
						'sameAs' => array(
							'2',
							'22',
							'226'
						)
					),
					'2266' => array(
						'isco08_level' => '4',
						'name' => 'Audiologists and Speech Therapists',
						'description' => 'Audiologists and speech therapists evaluate, manage and treat physical disorders affecting human hearing, speech, communication and swallowing. They prescribe corrective devices or rehabilitative therapies for hearing loss, speech disorders, and related sensory and neural problems and provide counselling on hearing safety and communication performance.',
						'sameAs' => array(
							'2',
							'22',
							'226'
						)
					),
					'2267' => array(
						'isco08_level' => '4',
						'name' => 'Optometrists and Ophthalmic Opticians',
						'description' => 'Optometrists and ophthalmic opticians provide diagnosis,  management and treatment services for disorders of the eyes and visual system. They counsel on eye care and prescribe optical aids or other therapies for visual disturbance.',
						'sameAs' => array(
							'2',
							'22',
							'226'
						)
					),
					'2269' => array(
						'isco08_level' => '4',
						'name' => 'Health Professionals Not Elsewhere Classified',
						'description' => 'This unit group covers health professionals not classified elsewhere in Sub-major Group 22: Health Professionals. For instance, the group includes occupations such as podiatrist, occupational therapist, recreational therapist, chiropractor, osteopath and other professionals providing diagnostic, preventive, curative and rehabilitative health services.',
						'sameAs' => array(
							'2',
							'22',
							'226'
						)
					),
					'26' => array(
						'isco08_level' => '2',
						'name' => 'Legal, Social and Cultural Professionals',
						'description' => 'Legal, social and cultural professionals conduct research, improve or develop concepts, theories and operational methods; or apply knowledge relating to the law,  storage and retrieval of information and artefacts, psychology, social welfare, politics, economics, history, religion, languages, sociology, other social sciences, and arts and entertainment. Competent performance in most occupations in this sub-major group requires skills at the fourth ISCO skill level.',
						'sameAs' => array(
							'2'
						)
					),
					'261' => array(
						'isco08_level' => '3',
						'name' => 'Legal Professionals',
						'description' => 'Legal professionals conduct research on legal problems, advise clients on legal aspects of problems, plead cases or conduct prosecutions in courts of law, preside over judicial proceedings in courts of law and draft laws and regulations',
						'sameAs' => array(
							'2',
							'26'
						)
					),
					'2611' => array(
						'isco08_level' => '4',
						'name' => 'Lawyers',
						'description' => 'Lawyers give clients legal advice on a wide variety of subjects, draw up legal documents, represent clients before administrative boards or tribunals and plead  cases or conduct prosecutions in courts of justice, or instruct barristers to plead in higher courts of justice.',
						'sameAs' => array(
							'2',
							'26',
							'261'
						)
					),
					'2612' => array(
						'isco08_level' => '4',
						'name' => 'Judges',
						'description' => 'Judges preside over civil and criminal proceedings in courts of law.',
						'sameAs' => array(
							'2',
							'26',
							'261'
						)
					),
					'2619' => array(
						'isco08_level' => '4',
						'name' => 'Legal Professionals Not Elsewhere Classified',
						'description' => 'This unit group covers legal professionals not classified elsewhere in Minor Group 261: Legal professionals. 	For instance, the group includes those who perform legal functions other than pleading or prosecuting cases or presiding over judicial  proceedings.',
						'sameAs' => array(
							'2',
							'26',
							'261'
						)
					),
					'262' => array(
						'isco08_level' => '3',
						'name' => 'Librarians, Archivists and Curators',
						'description' => 'Librarians, archivists and curators develop and maintain the collections of archives, libraries, museums, art galleries, and similar establishments.',
						'sameAs' => array(
							'2',
							'26'
						)
					),
					'2621' => array(
						'isco08_level' => '4',
						'name' => 'Archivists and Curators',
						'description' => 'Archivists and curators collect, appraise and ensure the safekeeping and preservation of the contents of archives, artefacts and records of historical, cultural, administrative and artistic interest, and of art and other objects. They plan, devise and implement systems for the safekeeping of records and historically valuable documents.',
						'sameAs' => array(
							'2',
							'26',
							'262'
						)
					),
					'2622' => array(
						'isco08_level' => '4',
						'name' => 'Librarians and Related Information Professionals',
						'description' => 'Librarians and related information professionals collect, select, develop, organize and maintain library collections and other information repositories, organize and control other library services and provide information for users.',
						'sameAs' => array(
							'2',
							'26',
							'262'
						)
					),
					'263' => array(
						'isco08_level' => '3',
						'name' => 'Social and Religious Professionals',
						'description' => 'Social and religious professionals conduct research, improve or develop concepts, theories and operational methods; apply knowledge relating to philosophy, politics, economics, sociology, anthropology, history, psychology, and other social sciences; or provide social services to meet the needs of individuals and families in a community.',
						'sameAs' => array(
							'2',
							'26'
						)
					),
					'2631' => array(
						'isco08_level' => '4',
						'name' => 'Economists',
						'description' => 'Economists conduct research, monitor data, analyse information and prepare reports and plans to resolve economic and business problems and develop models to analyse, explain and forecast economic behaviour and patterns. They provide advice to business, interest groups and governments to formulate solutions to present or projected economic and business problems.',
						'sameAs' => array(
							'2',
							'26',
							'263'
						)
					),
					'2632' => array(
						'isco08_level' => '4',
						'name' => 'Sociologists, Anthropologists and Related Professionals',
						'description' => 'Sociologists, anthropologists and related professionals investigate and describe the structure, origin and evolution of societies and the interdependence between environmental conditions and human activities. They provide advice on the practical application of their findings in the formulation of economic and social policies.',
						'sameAs' => array(
							'2',
							'26',
							'263'
						)
					),
					'2633' => array(
						'isco08_level' => '4',
						'name' => 'Philosophers, Historians and Political Scientists',
						'description' => 'Philosophers, historians and political scientists conduct research into the nature of human experience and existence, phases or aspects of human history, and political structures, movements and behaviour. They document and report on findings to inform and guide political and individual actions',
						'sameAs' => array(
							'2',
							'26',
							'263'
						)
					),
					'2634' => array(
						'isco08_level' => '4',
						'name' => 'Psychologists',
						'description' => 'Psychologists research into and study the mental processes and behaviour of human beings as individuals or in groups, and apply this knowledge to promote personal, social, educational or occupational adjustment and development.',
						'sameAs' => array(
							'2',
							'26',
							'263'
						)
					),
					'2635' => array(
						'isco08_level' => '4',
						'name' => 'Social Work and Counselling Professionals',
						'description' => 'Social work and counselling professionals provide advice and guidance to individuals, families, groups, communities and organizations in response to social and personal difficulties.  They assist clients to develop skills and access resources and support services needed to respond to issues arising from unemployment, poverty, disability, addiction, criminal and delinquent behaviour, and marital and other problems.',
						'sameAs' => array(
							'2',
							'26',
							'263'
						)
					),
					'2636' => array(
						'isco08_level' => '4',
						'name' => 'Religious Professionals',
						'description' => 'Religious professionals function as perpetuators of sacred traditions, practices and beliefs.  They conduct religious services, celebrate or administer the rites of a religious faith or denomination, provide spiritual and moral guidance and perform other functions associated with the practice of a religion.',
						'sameAs' => array(
							'2',
							'26',
							'263'
						)
					),
					'264' => array(
						'isco08_level' => '3',
						'name' => 'Authors, Journalists and Linguists',
						'description' => 'Authors, journalists and linguists conceive and create literary works, interpret and communicate news and public affairs through the media; and translate or interpret from one language into another.',
						'sameAs' => array(
							'2',
							'26'
						)
					),
					'2641' => array(
						'isco08_level' => '4',
						'name' => 'Authors and Related Writers',
						'description' => 'Authors and related writers plan, research and write books, scripts, storyboards, plays, essays, speeches, manuals, specifications and other non-journalistic articles (excluding material for newspapers, magazines and other periodicals) for publication or presentation.',
						'sameAs' => array(
							'2',
							'26',
							'264'
						)
					),
					'2642' => array(
						'isco08_level' => '4',
						'name' => 'Journalists',
						'description' => 'Journalists research, investigate, interpret and communicate news and public affairs through newspapers, television, radio and other media.',
						'sameAs' => array(
							'2',
							'26',
							'264'
						)
					),
					'2643' => array(
						'isco08_level' => '4',
						'name' => 'Translators, Interpreters and Other Linguists',
						'description' => 'Translators, interpreters and other linguists translate or interpret from one language into another and study the origin, development and structure of languages.',
						'sameAs' => array(
							'2',
							'26',
							'264'
						)
					),
					'265' => array(
						'isco08_level' => '3',
						'name' => 'Creative and Performing Artists',
						'description' => 'Creative and performing artists communicate ideas, impressions and facts in a wide range of media to achieve particular effects; interpret a composition such as a musical score or a script to perform or direct the performance; and host the presentation of such performance and other media events.',
						'sameAs' => array(
							'2',
							'26'
						)
					),
					'2651' => array(
						'isco08_level' => '4',
						'name' => 'Visual Artists',
						'description' => 'Visual artists create and execute works of art by sculpting, painting, drawing, creating cartoons, engraving or using other techniques.',
						'sameAs' => array(
							'2',
							'26',
							'265'
						)
					),
					'2652' => array(
						'isco08_level' => '4',
						'name' => 'Musicians, Singers and Composers',
						'description' => 'Musicians, singers and composers write, arrange, conduct and perform musical compositions.',
						'sameAs' => array(
							'2',
							'26',
							'265'
						)
					),
					'2653' => array(
						'isco08_level' => '4',
						'name' => 'Dancers and Choreographers',
						'description' => 'Dancers and choreographers conceive and create or perform dances.',
						'sameAs' => array(
							'2',
							'26',
							'265'
						)
					),
					'2654' => array(
						'isco08_level' => '4',
						'name' => 'Film, Stage and Related Directors and Producers',
						'description' => 'Film, stage and related directors and producers oversee and control the technical and artistic aspects of motion pictures, television or radio productions and stage shows.',
						'sameAs' => array(
							'2',
							'26',
							'265'
						)
					),
					'2655' => array(
						'isco08_level' => '4',
						'name' => 'Actors',
						'description' => 'Actors portray roles in motion pictures, television or radio productions and stage shows.',
						'sameAs' => array(
							'2',
							'26',
							'265'
						)
					),
					'2656' => array(
						'isco08_level' => '4',
						'name' => 'Announcers on Radio, Television and Other Media',
						'description' => 'Announcers on radio, television and other media read news bulletins, conduct interviews, and make other announcements or introductions on radio, television, and in theatres and other establishments or media',
						'sameAs' => array(
							'2',
							'26',
							'265'
						)
					),
					'2659' => array(
						'isco08_level' => '4',
						'name' => 'Creative and Performing Artists Not Elsewhere Classified',
						'description' => 'This unit group covers all creative and performing artists not classified elsewhere in Minor Group 265: Creative and Performing Artists. For instance, the group includes clowns, magicians, acrobats and other performing artists.',
						'sameAs' => array(
							'2',
							'26',
							'265'
						)
					),
					'3' => array(
						'isco08_level' => '1',
						'name' => 'Technicians and Associate Professionals',
						'description' => 'Technicians and associate professionals perform technical and related tasks connected with research and the application of scientific or artistic concepts and operational methods, and government or business regulations. Competent performance in most occupations in this major group requires skills at the third ISCO skill level.',
						'sameAs' => array()
					),
					'32' => array(
						'isco08_level' => '2',
						'name' => 'Health Associate Professionals',
						'description' => 'Health associate professionals perform technical and practical tasks to support diagnosis and treatment of illness, disease, injuries and impairments in humans and animals, and to support implementation of health care, treatment and referral plans usually established by medical, veterinary, nursing and other health professionals. Competent performance in most occupations in this sub-major group requires skills at the third ISCO skill level.',
						'sameAs' => array(
							'3'
						)
					),
					'321' => array(
						'isco08_level' => '3',
						'name' => 'Medical and Pharmaceutical Technicians',
						'description' => 'Medical and pharmaceutical technicians perform technical tasks to assist in diagnosis and treatment of illness, disease, injuries and impairments.',
						'sameAs' => array(
							'3',
							'32'
						)
					),
					'3211' => array(
						'isco08_level' => '4',
						'name' => 'Medical Imaging and Therapeutic Equipment Technicians',
						'description' => 'Medical imaging and therapeutic equipment technicians test and operate radiographic, ultrasound and other medical imaging equipment to produce images of body structures for the diagnosis and treatment of injury, disease and other impairments. They may administer radiation treatments to patients under the supervision of a radiologist or other health professional.',
						'sameAs' => array(
							'3',
							'32',
							'321'
						)
					),
					'3212' => array(
						'isco08_level' => '4',
						'name' => 'Medical and Pathology Laboratory Technicians',
						'description' => 'Medical and pathology laboratory technicians perform clinical tests on specimens of bodily fluids and tissues in order to obtain information about the health of a patient or cause of death.',
						'sameAs' => array(
							'3',
							'32',
							'321'
						)
					),
					'3213' => array(
						'isco08_level' => '4',
						'name' => 'Pharmaceutical Technicians and Assistants',
						'description' => 'Pharmaceutical technicians and assistants perform a variety of tasks associated with dispensing  medicinal products under the guidance of a pharmacist or other health professional.',
						'sameAs' => array(
							'3',
							'32',
							'321'
						)
					),
					'3214' => array(
						'isco08_level' => '4',
						'name' => 'Medical and Dental Prosthetic Technicians',
						'description' => 'Medical and dental prosthetic technicians design, fit, service and repair medical and dental devices and appliances following prescriptions or instructions established by a health professional. They may service a wide range of support instruments to correct physical medical or dental problems such as neck braces, orthopaedic splints, artificial limbs, hearing aids, arch supports, dentures, and dental crowns and bridges.',
						'sameAs' => array(
							'3',
							'32',
							'321'
						)
					),
					'322' => array(
						'isco08_level' => '3',
						'name' => 'Nursing and Midwifery Associate Professionals',
						'description' => 'Nursing and midwifery associate professionals provide basic nursing and personal care for people who are physically or mentally ill, disabled or infirm, and others in need of care due to potential risks to health including before, during and after childbirth. They generally work under the supervision of, and in support of implementation of health care, treatment and referrals plans established by, medical, nursing, midwifery and other health professionals.',
						'sameAs' => array(
							'3',
							'32'
						)
					),
					'3221' => array(
						'isco08_level' => '4',
						'name' => 'Nursing Associate Professionals',
						'description' => 'Nursing associate professionals provide basic nursing and personal care for people in need of such care due to effects of ageing, illness, injury or other physical or mental impairment. They generally work under the supervision of, and in support of implementation of health care, treatment and referrals plans established by, medical, nursing and other health professionals.',
						'sameAs' => array(
							'3',
							'32',
							'322'
						)
					),
					'3222' => array(
						'isco08_level' => '4',
						'name' => 'Midwifery Associate Professionals',
						'description' => 'Midwifery associate professionals provide basic health care and advice before, during and after pregnancy and childbirth. They implement care, treatment and referral plans usually established by medical, midwifery and other health professionals.',
						'sameAs' => array(
							'3',
							'32',
							'322'
						)
					),
					'323' => array(
						'isco08_level' => '3',
						'name' => 'Traditional and Complementary Medicine Associate Professionals',
						'description' => 'Traditional and complementary medicine associate professionals prevent, care for and treat human physical and mental illnesses, disorders and injuries using herbal and other therapies based on theories, beliefs and experiences originating in specific cultures. They administer treatments using traditional techniques and medicaments, either acting independently or according to therapeutic care plans established by a traditional medicine or other health professional.',
						'sameAs' => array(
							'3',
							'32'
						)
					),
					'3230' => array(
						'isco08_level' => '4',
						'name' => 'Traditional and Complementary Medicine Associate Professionals',
						'description' => 'Traditional and complementary medicine associate professionals prevent, care for and treat human physical and mental illnesses, disorders and injuries using herbal and other therapies based on theories, beliefs and experiences originating in specific cultures. They administer treatments using traditional techniques and medicaments, either acting independently or according to therapeutic care plans established by a traditional medicine or other health professional.',
						'sameAs' => array(
							'3',
							'32',
							'323'
						)
					),
					'324' => array(
						'isco08_level' => '3',
						'name' => 'Veterinary Technicians and Assistants',
						'description' => 'Veterinary technicians and assistants carry out advisory, diagnostic, preventive and curative veterinary tasks, more limited in scope and complexity than those carried out by veterinarians. They care for animals under treatment and in temporary residence at veterinary facilities and assist veterinarians to perform procedures and operations.',
						'sameAs' => array(
							'3',
							'32'
						)
					),
					'3240' => array(
						'isco08_level' => '4',
						'name' => 'Veterinary Technicians and Assistants',
						'description' => 'Veterinary technicians and assistants carry out advisory, diagnostic, preventive and curative veterinary tasks more limited in scope and complexity than those carried out by, and with the guidance of, veterinarians. They care for animals under treatment and in temporary residence at veterinary facilities, perform routine procedures and assist veterinarians to perform procedures and operations.',
						'sameAs' => array(
							'3',
							'32',
							'324'
						)
					),
					'325' => array(
						'isco08_level' => '3',
						'name' => 'Other Health Associate Professionals',
						'description' => 'Other health associate professionals perform technical tasks and provide support services in dentistry, medical records administration, community health, the correction of reduced visual acuity, physiotherapy, environmental health, emergency medical treatment and other activities to support and promote human health.',
						'sameAs' => array(
							'3',
							'32'
						)
					),
					'3251' => array(
						'isco08_level' => '4',
						'name' => 'Dental Assistants and Therapists',
						'description' => 'Dental assistants and therapists provide basic dental care services for the prevention and treatment of diseases and disorders of the teeth and mouth, according to care plans and procedures established by a dentist or other oral health professional.',
						'sameAs' => array(
							'3',
							'32',
							'325'
						)
					),
					'3252' => array(
						'isco08_level' => '4',
						'name' => 'Medical Records and Health Information Technicians',
						'description' => 'Medical records and health information technicians develop, maintain and implement health records processing, storage and retrieval systems in medical facilities and other health care settings to meet the legal professional, ethical and administrative records-keeping requirements of health services delivery.',
						'sameAs' => array(
							'3',
							'32',
							'325'
						)
					),
					'3253' => array(
						'isco08_level' => '4',
						'name' => 'Community Health Workers',
						'description' => 'Community health workers provide health education, referral and follow up, case management, basic preventive health care and home visiting services to specific communities. They provide support and assistance to individuals and families in navigating the health and social services system.',
						'sameAs' => array(
							'3',
							'32',
							'325'
						)
					),
					'3254' => array(
						'isco08_level' => '4',
						'name' => 'Dispensing Opticians',
						'description' => 'Dispensing opticians design, fit and dispense optical lenses based on a prescription from an ophthalmologist or optometrist for the correction of reduced visual acuity. They service corrective eyeglasses, contact lenses, low-vision aids and other optical devices.',
						'sameAs' => array(
							'3',
							'32',
							'325'
						)
					),
					'3255' => array(
						'isco08_level' => '4',
						'name' => 'Physiotherapy Technicians and Assistants',
						'description' => 'Physiotherapy technicians and assistants provide physical therapeutic treatments to patients in circumstances where functional movement is threatened by injury, disease or impairment. Therapies are usually provided as per rehabilitative plans established by a physiotherapist or other health professional.',
						'sameAs' => array(
							'3',
							'32',
							'325'
						)
					),
					'3256' => array(
						'isco08_level' => '4',
						'name' => 'Medical Assistants',
						'description' => 'Medical assistants perform basic clinical and administrative tasks to support patient care under the direct supervision of a medical practitioner or other health professional.',
						'sameAs' => array(
							'3',
							'32',
							'325'
						)
					),
					'3257' => array(
						'isco08_level' => '4',
						'name' => 'Environmental and Occupational Health Inspectors and Associates',
						'description' => 'Environmental and occupational health inspectors and associates investigate the implementation of rules and regulations relating to environmental factors that may affect human health, safety in the workplace, and safety of processes for the production of goods and services. They may implement and evaluate programmes to restore or improve safety and sanitary conditions under the supervision of a health professional.',
						'sameAs' => array(
							'3',
							'32',
							'325'
						)
					),
					'3258' => array(
						'isco08_level' => '4',
						'name' => 'Ambulance Workers',
						'description' => 'Ambulance workers provide emergency health care to patients who are injured, sick, infirm, or otherwise physically or mentally impaired prior to and during transport to medical facilities.',
						'sameAs' => array(
							'3',
							'32',
							'325'
						)
					),
					'3259' => array(
						'isco08_level' => '4',
						'name' => 'Health Associate Professionals Not Elsewhere Classified',
						'description' => 'This unit group covers health associate professionals not classified elsewhere in Sub-major Group 32: Health Associate Professionals. For instance, the group includes occupations such as HIV counsellor, family planning counsellor and other health associate professionals.',
						'sameAs' => array(
							'3',
							'32',
							'325'
						)
					),
					'34' => array(
						'isco08_level' => '2',
						'name' => 'Legal, Social, Cultural and Related Associate Professionals',
						'description' => 'Legal, social, cultural and related associate professionals perform technical tasks connected with the practical application of knowledge relating to legal services, social work, culture, food preparation, sport and religion. Competent performance in most occupations in this sub-major group requires skills at the third ISCO skill level.',
						'sameAs' => array(
							'3'
						)
					),
					'341' => array(
						'isco08_level' => '3',
						'name' => 'Legal, Social and Religious Associate Professionals',
						'description' => 'Legal, social and religious associate professionals provide technical and practical services and support functions in legal processes and investigations, social and community assistance programmes and religious activities.',
						'sameAs' => array(
							'3',
							'34'
						)
					),
					'3412' => array(
						'isco08_level' => '4',
						'name' => 'Social Work Associate Professionals',
						'description' => 'Social work associate professionals administer and implement social assistance programmes and community services and assist clients to deal with personal and social problems.',
						'sameAs' => array(
							'3',
							'34',
							'341'
						)
					),
					'3413' => array(
						'isco08_level' => '4',
						'name' => 'Religious Associate Professionals',
						'description' => 'Religious associate professionals provide support to ministers of religion or to a religious community, undertake religious works, preach and propagate the teachings of a particular religion and endeavour to improve well-being through the power of faith and spiritual advice.',
						'sameAs' => array(
							'3',
							'34',
							'341'
						)
					),
					'342' => array(
						'isco08_level' => '3',
						'name' => 'Sports and Fitness Workers',
						'description' => 'Sports and fitness workers prepare for and compete in sporting events for financial gain, train amateur and professional sportsmen and women to enhance performance; promote participation and standards in sport; organize and officiate sporting events; and provide instruction, training and supervision for various forms of exercise and other recreational activities.',
						'sameAs' => array(
							'3',
							'34'
						)
					),
					'3422' => array(
						'isco08_level' => '4',
						'name' => 'Sports Coaches, Instructors and Officials',
						'description' => 'Sports coaches, instructors and officials work with amateur and professional sportspersons to enhance performance, encourage greater participation in sport, and organize and officiate in sporting events according to established rules.',
						'sameAs' => array(
							'3',
							'34',
							'342'
						)
					),
					'3423' => array(
						'isco08_level' => '4',
						'name' => 'Fitness and Recreation Instructors and Programme Leaders',
						'description' => 'Fitness and recreation instructors and programme leaders lead, guide and instruct groups and individuals in recreational, fitness or outdoor adventure activities.',
						'sameAs' => array(
							'3',
							'34',
							'342'
						)
					)
				);

			// (Optional) Expand ISCO-8 output to include CategoryCodes for each of the ancestors of the indicated ISCO-08 code

				if ( $get_ancestors ) {

					$code_ancestors = $isco08_values[$code]['sameAs'] ?? null;

					if ( $code_ancestors ) {

						foreach ( $code_ancestors as $ancestor ) {

							if ( $ancestor ) {

								$output = uamswp_fad_schema_occupationalCategory_isco08(
									$ancestor, // string // Required // ISCO-08 occupation code
									false, // bool // Optional // Query for whether to add a occupationalCategory value for each of the code's ancestors
									$output // array // Optional // Pre-existing schema array for occupationalCategory to which to add occupationalCategory items
								);

							}

						}

					}

				}

			// Construct item

				/**
				 * The following properties are either beyond the scope of what is being included
				 * in this item schema, irrelevant to this item schema, or are superseded by
				 * another property, and so they will not be included:
				 *
				 *      * additionalType
				 *      * alternateName
				 *      * disambiguatingDescription
				 *      * identifier
				 *      * image
				 *      * inDefinedTermSet
				 *      * mainEntityOfPage
				 *      * potentialAction
				 *      * sameAs
				 *      * subjectOf
				 *      * termCode
				 *      * url
				 */

				// Base array

					$output_item = array();

				// @type

					$output_item['@type'] = 'CategoryCode';

				// codeValue

					/**
					 * A short textual code that uniquely identifies the value.
					 *
					 * Values expected to be one of these types:
					 *
					 *      - Text
					 *
					 * Used on these types:
					 *
					 *      - CategoryCode
					 *      - MedicalCode
					 *
					 * As of 22 Apr 2024, this term is in the "new" area of Schema.org. Implementation
					 * feedback and adoption from applications and websites can help improve their
					 * definitions.
					 */

					if ( $code ) {

						$output_item['codeValue'] = $code; // ISCO-08 group code

					}

				// description

					/**
					 * A description of the item.
					 *
					 * Values expected to be one of these types:
					 *
					 *      - Text
					 *      - TextObject
					 *
					 * Used on these types:
					 *
					 *      - Thing
					 *
					 * Sub-properties:
					 *
					 *      - disambiguatingDescription
					 *      - interpretedAsClaim
					 *      - originalMediaContextDescription
					 *      - sha256
					 */

					$description = $isco08_values[$code]['description'] ?? null; // ISCO-08 group definition/description

					if ( $description ) {

						$output_item['description'] = $description;

					}

				// inCodeSet

					/**
					 * A CategoryCodeSet that contains this category code.
					 *
					 * Values expected to be one of these types:
					 *
					 *      - CategoryCodeSet
					 *      - URL
					 *
					 * Used on these types:
					 *
					 *      - CategoryCode
					 *
					 * As of 22 Apr 2024, this term is in the "new" area of Schema.org. Implementation
					 * feedback and adoption from applications and websites can help improve their
					 * definitions.
					 */

					$output_item['inCodeSet'] = array(
						'@type' => 'CategoryCodeSet',
						'alternateName' => array(
							'ISCO 08',
							'International Standard Classification of Occupations 2008'
						),
						'datePublished' => '2008',
						'name' => 'ISCO-08',
						'url' => array(
							'https://www.ilo.org/public/english/bureau/stat/isco/isco08/',
							'https://www.ilo.org/public/english/bureau/stat/isco/docs/publication08.pdf'
						)
					);

				// name

					/**
					 * The name of the item.
					 *
					 * Expected Type:
					 *
					 *      - Text
					 */

					$name = $isco08_values[$code]['name'] ?? null; // ISCO-08 group title

					if ( $name ) {

						$output_item['name'] = $name;

					}

			// Add the item to the output array

				if ( !$output ) {

					/**
					 * If the pre-existing output array is empty, then set the output value using the
					 * item value.
					 */

					$output = $output_item;

				} elseif ( !array_is_list($output) ) {

					/**
					 * If the pre-existing output array is an associative array, then add both the
					 * pre-existing output array value and the item value to the output array as items
					 * in a list array.
					 */

					$output = array_merge(
						array($output),
						array($output_item)
					);

				} else {

					/**
					 * Otherwise, add the item value to the output array as an additional item.
					 */

					$output[] = $output_item;

				}

			return $output;

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

			$page_fragment = $page_fragment ?: 'Image';

			// If values are 0 or empty, end now

				if (
					!$input_1_1
					&&
					!$input_3_4
					&&
					!$input_4_3
					&&
					!$input_16_9
					&&
					!$input_full
				) {

					return;

				}

		// Base array

			$image_ImageObject = array();

		// Image attributes

			/**
			 * The following properties are either beyond the scope of what is being included
			 * in this item schema, irrelevant to this item schema, or are superseded by
			 * another property, and so they will not be included:
			 *
			 *      * about
			 *      * abstract
			 *      * accessMode
			 *      * accessModeSufficient
			 *      * accessibilityAPI
			 *      * accessibilityControl
			 *      * accessibilityFeature
			 *      * accessibilityHazard
			 *      * accessibilitySummary
			 *      * accountablePerson
			 *      * acquireLicensePage
			 *      * additionalType
			 *      * aggregateRating
			 *      * alternateName
			 *      * alternativeHeadline
			 *      * archivedAt
			 *      * assesses
			 *      * associatedArticle
			 *      * associatedMedia
			 *      * audience
			 *      * audio
			 *      * author
			 *      * award
			 *      * awards
			 *      * bitrate
			 *      * character
			 *      * citation
			 *      * comment
			 *      * commentCount
			 *      * conditionsOfAccess
			 *      * contentLocation
			 *      * contentRating
			 *      * contentReferenceTime
			 *      * contributor
			 *      * copyrightHolder
			 *      * copyrightNotice
			 *      * copyrightYear
			 *      * correction
			 *      * countryOfOrigin
			 *      * creativeWorkStatus
			 *      * creator
			 *      * creditText
			 *      * dateCreated
			 *      * dateModified
			 *      * datePublished
			 *      * description
			 *      * digitalSourceType
			 *      * disambiguatingDescription
			 *      * discussionUrl
			 *      * duration
			 *      * editEIDR
			 *      * editor
			 *      * educationalAlignment
			 *      * educationalLevel
			 *      * educationalUse
			 *      * embedUrl
			 *      * embeddedTextCaption
			 *      * encodesCreativeWork
			 *      * encoding
			 *      * encodings
			 *      * endTime
			 *      * exampleOfWork
			 *      * exifData
			 *      * expires
			 *      * fileFormat
			 *      * funder
			 *      * funding
			 *      * genre
			 *      * hasPart
			 *      * headline
			 *      * identifier
			 *      * image
			 *      * inLanguage
			 *      * ineligibleRegion
			 *      * interactionStatistic
			 *      * interactivityType
			 *      * interpretedAsClaim
			 *      * isAccessibleForFree
			 *      * isBasedOn
			 *      * isBasedOnUrl
			 *      * isFamilyFriendly
			 *      * isPartOf
			 *      * keywords
			 *      * learningResourceType
			 *      * license
			 *      * locationCreated
			 *      * mainEntity
			 *      * mainEntityOfPage
			 *      * maintainer
			 *      * material
			 *      * materialExtent
			 *      * mentions
			 *      * name
			 *      * offers
			 *      * pattern
			 *      * playerType
			 *      * position
			 *      * potentialAction
			 *      * producer
			 *      * productionCompany
			 *      * provider
			 *      * publication
			 *      * publisher
			 *      * publisherImprint
			 *      * publishingPrinciples
			 *      * recordedAt
			 *      * regionsAllowed
			 *      * releasedEvent
			 *      * requiresSubscription
			 *      * review
			 *      * reviews
			 *      * sameAs
			 *      * schemaVersion
			 *      * sdDatePublished
			 *      * sdLicense
			 *      * sdPublisher
			 *      * sha256
			 *      * size
			 *      * sourceOrganization
			 *      * spatial
			 *      * spatialCoverage
			 *      * sponsor
			 *      * startTime
			 *      * subjectOf
			 *      * teaches
			 *      * temporal
			 *      * temporalCoverage
			 *      * text
			 *      * thumbnail
			 *      * thumbnailUrl
			 *      * timeRequired
			 *      * translationOfWork
			 *      * translator
			 *      * typicalAgeRange
			 *      * uploadDate
			 *      * url
			 *      * usageInfo
			 *      * version
			 *      * video
			 *      * workExample
			 *      * workTranslation
			 */

			// Base ImageObject

				$image_ImageObject_base = array(
					'@type' => 'ImageObject'
				);
				$image_ImageObject_base['representativeOfPage'] = $nesting_level <= 1 ? 'True' : 'False';

			// 1:1 aspect ratio source image

				if (
					$input_1_1
					&&
					(
						$nesting_level <= 1
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

					// Merge base ImageObject values array into the item array

						if ( $image_1_1 ) {

							$image_1_1 = array_merge(
								$image_ImageObject_base,
								$image_1_1
							);

						}

					// Clean up the item array

						if ( $image_1_1 ) {

							$image_1_1 = array_filter($image_1_1);
							ksort( $image_1_1, SORT_NATURAL | SORT_FLAG_CASE );

						}

					// Add to list array

						$image_ImageObject[] = $image_1_1;

				}

			// 3:4 aspect ratio source image

				if (
					$input_3_4
					&&
					(
						$nesting_level <= 1
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

					// Merge base ImageObject values array into the item array

						if ( $image_3_4 ) {

							$image_3_4 = array_merge(
								$image_ImageObject_base,
								$image_3_4
							);

						}

					// Clean up the item array

						if ( $image_3_4 ) {

							$image_3_4 = array_filter($image_3_4);
							ksort( $image_3_4, SORT_NATURAL | SORT_FLAG_CASE );

						}

					// Add to list array

						$image_ImageObject[] = $image_3_4;

				}

			// 4:3 aspect ratio source image

				if (
					$input_4_3
					&&
					(
						$nesting_level <= 1
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

					// Merge base ImageObject values array into the item array

						if ( $image_4_3 ) {

							$image_4_3 = array_merge(
								$image_ImageObject_base,
								$image_4_3
							);

						}

					// Clean up the item array

						if ( $image_4_3 ) {

							$image_4_3 = array_filter($image_4_3);
							ksort( $image_4_3, SORT_NATURAL | SORT_FLAG_CASE );

						}

					// Add to list array

						$image_ImageObject[] = $image_4_3;

				}

			// 16:9 aspect ratio source image

				if (
					$input_16_9
					&&
					(
						$nesting_level <= 1
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

					// Merge base ImageObject values array into the item array

						if ( $image_16_9 ) {

							$image_16_9 = array_merge(
								$image_ImageObject_base,
								$image_16_9
							);

						}

					// Clean up the item array

						if ( $image_16_9 ) {

							$image_16_9 = array_filter($image_16_9);
							ksort( $image_16_9, SORT_NATURAL | SORT_FLAG_CASE );

						}

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

						$image_full_src = wp_get_attachment_image_src( $input_full, 'full' ) ?? array();

						if ( $image_full_src ) {

							$image_full_url = $image_full_src[0] ?? '';
							$image_full_width = $image_full_src[1] ?? '';
							$image_full_height = $image_full_src[2] ?? '';
							$image_full_size = '';

						}

					// ImageObject

						$image_full = array();
						$image_full['@id'] = ( isset($url) && !empty($url) ) ? $url . '#' . $page_fragment .  '-full' : '';
						$image_full['caption'] = $image_caption_full ?? '';
						$image_full['contentSize'] = $image_full_size ?: '';
						$image_full['contentUrl'] = $image_full_url ?: '';
						$image_full['encodingFormat'] = $image_encodingFormat_full ?? '';
						$image_full['height'] = $image_full_height ? $image_full_height . ' px' : '';
						$image_full['width'] = $image_full_width ? $image_full_width . ' px' : '';

					// Merge base ImageObject values array into the item array

						if ( $image_full ) {

							$image_full = array_merge(
								$image_ImageObject_base,
								$image_full
							);

						}

					// Clean up the item array

						if ( $image_full ) {

							$image_full = array_filter($image_full);
							ksort( $image_full, SORT_NATURAL | SORT_FLAG_CASE );

						}

					// Add to list array

						$image_ImageObject[] = $image_full;

				}

		return $image_ImageObject;

	}

// Add data to an array defining schema data for image URL from thumbnails (thumbnailUrl)

	function uamswp_fad_schema_image_url_thumbnails(
		int $input_1_1 = 0, // ID of image to use for 1:1 aspect ratio
		int $input_3_4 = 0, // ID of image to use for 3:4 aspect ratio
		int $input_4_3 = 0, // ID of image to use for 4:3 aspect ratio
		int $input_16_9 = 0, // ID of image to use for 16:9 aspect ratio
		int $input_full = 0 // ID of image to use for full image
	) {

		// If values are 0 or empty, bail early

			if (
				!$input_1_1
				&&
				!$input_3_4
				&&
				!$input_4_3
				&&
				!$input_16_9
				&&
				!$input_full
			) {

				return;

			}

		// Base array

			$output = array();

		// Get the URL of the image thumbnails

			// 1:1 aspect ratio source image

				if ( $input_1_1 ) {

					$image_1_1_url = wp_get_attachment_image_url( $input_1_1, 'aspect-1-1' ) ?? null;

					if ( $image_1_1_url ) {

						$output[] = $image_1_1_url;

					}

				}

			// 3:4 aspect ratio source image

				if ( $input_3_4 ) {

					$image_3_4_url = wp_get_attachment_image_url( $input_3_4, 'aspect-3-4' ) ?? null;

					if ( $image_3_4_url ) {

						$output[] = $image_3_4_url;

					}

				}

			// 4:3 aspect ratio source image

				if ( $input_4_3 ) {

					$image_4_3_url = wp_get_attachment_image_url( $input_4_3, 'aspect-4-3' ) ?? null;

					if ( $image_4_3_url ) {

						$output[] = $image_4_3_url;

					}

				}

			// 16:9 aspect ratio source image

				if ( $input_16_9 ) {

					$image_16_9_url = wp_get_attachment_image_url( $input_16_9, 'aspect-16-9' ) ?? null;

					if ( $image_16_9_url ) {

						$output[] = $image_16_9_url;

					}

				}

			// Full-size image output

				if ( $input_full ) {

					$image_full_url = wp_get_attachment_image_url( $input_full, 'full' ) ?? null;

					if ( $image_full_url ) {

						$output[] = $image_full_url;

					}

				}

		// Clean up the list array

			$output = $output ? array_filter($output) : null;
			$output = $output ? array_unique( $output, SORT_REGULAR ) : null;
			$output = $output ? array_values($output) : null;

			// If there is only one item, flatten the multi-dimensional array by one step

				if ( $output ) {

					uamswp_fad_flatten_multidimensional_array($output);

				}

		// Return the list array

			return $output;

	}

// Add data to an array defining schema data for the Health Care Provider Taxonomy code set

	function uamswp_fad_schema_nucc_code_set(
		$code, // string // Required // Health Care Provider Taxonomy code
		$alternateName = '', // string|array // Optional // alternateName
		$description = '', // string // Optional // description
		$name = '', // string // Optional // name
		array $output = array() // array // Optional // Pre-existing schema array for the Health Care Provider Taxonomy code set to which to add items
	) {

		/**
		 * Make all input values attribute-friendly before adding them as arguments for
		 * this function.
		 */

		// Check variables

			$code = is_string($code) ? $code : '';
			$alternateName = ( is_string($alternateName) || is_array($alternateName) ) ? $alternateName : '';
			$description = is_string($description) ? $description : '';
			$name = is_string($name) ? $name : '';

			// If $code input is invalid, stop here

				if ( !$code ) {

					return $output;

				}

		// Base item array

			$output_item = array();

		// Define static values

			// Type

				$type = 'MedicalCode';

			// codingSystem

				$codingSystem = 'Health Care Provider Taxonomy';

			// inCodeSet

				/**
				 * The following properties are either beyond the scope of what is being included
				 * in this item schema, irrelevant to this item schema, or are superseded by
				 * another property, and so they will not be included:
				 *
				 *      * about
				 *      * abstract
				 *      * accessMode
				 *      * accessModeSufficient
				 *      * accessibilityAPI
				 *      * accessibilityControl
				 *      * accessibilityFeature
				 *      * accessibilityHazard
				 *      * accessibilitySummary
				 *      * accountablePerson
				 *      * acquireLicensePage
				 *      * additionalType
				 *      * aggregateRating
				 *      * alternativeHeadline
				 *      * archivedAt
				 *      * assesses
				 *      * associatedMedia
				 *      * audience
				 *      * audio
				 *      * author
				 *      * award
				 *      * awards
				 *      * character
				 *      * citation
				 *      * comment
				 *      * commentCount
				 *      * conditionsOfAccess
				 *      * contentLocation
				 *      * contentRating
				 *      * contentReferenceTime
				 *      * contributor
				 *      * copyrightHolder
				 *      * copyrightNotice
				 *      * copyrightYear
				 *      * correction
				 *      * countryOfOrigin
				 *      * creativeWorkStatus
				 *      * creator
				 *      * creditText
				 *      * dateCreated
				 *      * dateModified
				 *      * datePublished
				 *      * description
				 *      * digitalSourceType
				 *      * disambiguatingDescription
				 *      * discussionUrl
				 *      * editEIDR
				 *      * editor
				 *      * educationalAlignment
				 *      * educationalLevel
				 *      * educationalUse
				 *      * encoding
				 *      * encodingFormat
				 *      * encodings
				 *      * exampleOfWork
				 *      * expires
				 *      * fileFormat
				 *      * funder
				 *      * funding
				 *      * genre
				 *      * hasCategoryCode
				 *      * hasDefinedTerm
				 *      * hasPart
				 *      * headline
				 *      * identifier
				 *      * image
				 *      * inLanguage
				 *      * interactionStatistic
				 *      * interactivityType
				 *      * interpretedAsClaim
				 *      * isAccessibleForFree
				 *      * isBasedOn
				 *      * isBasedOnUrl
				 *      * isFamilyFriendly
				 *      * isPartOf
				 *      * keywords
				 *      * learningResourceType
				 *      * license
				 *      * locationCreated
				 *      * mainEntity
				 *      * mainEntityOfPage
				 *      * maintainer
				 *      * material
				 *      * materialExtent
				 *      * mentions
				 *      * offers
				 *      * pattern
				 *      * position
				 *      * potentialAction
				 *      * producer
				 *      * provider
				 *      * publication
				 *      * publisher
				 *      * publisherImprint
				 *      * publishingPrinciples
				 *      * recordedAt
				 *      * releasedEvent
				 *      * review
				 *      * reviews
				 *      * schemaVersion
				 *      * sdDatePublished
				 *      * sdLicense
				 *      * sdPublisher
				 *      * size
				 *      * sourceOrganization
				 *      * spatial
				 *      * spatialCoverage
				 *      * sponsor
				 *      * subjectOf
				 *      * teaches
				 *      * temporal
				 *      * temporalCoverage
				 *      * text
				 *      * thumbnail
				 *      * thumbnailUrl
				 *      * timeRequired
				 *      * translationOfWork
				 *      * translator
				 *      * typicalAgeRange
				 *      * usageInfo
				 *      * version
				 *      * video
				 *      * workExample
				 *      * workTranslation
				 */

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

			// url

				$url = $code ? 'https://taxonomy.nucc.org/?searchTerm=' . $code : ''; // URL to term on taxonomy.nucc.org

		// Add schema property values to the item array

			/**
			 * The following properties are either beyond the scope of what is being included
			 * in this item schema, irrelevant to this item schema, or are superseded by
			 * another property, and so they will not be included:
			 *
			 *      * additionalType
			 *      * disambiguatingDescription
			 *      * funding
			 *      * guideline
			 *      * identifier
			 *      * image
			 *      * legalStatus
			 *      * mainEntityOfPage
			 *      * medicineSystem
			 *      * potentialAction
			 *      * recognizingAuthority
			 *      * relevantSpecialty
			 *      * sameAs
			 *      * study
			 *      * subjectOf
			 */

			// @id

				$schema_item_id = ( $url && $type ) ? $url . '#' . $type : '';

				if ( $schema_item_id ) {

					$output_item['@id'] = $schema_item_id;

				}

			// @type

				if ( $type ) {

					$output_item['@type'] = $type;

				}

			// alternateName

				/**
					* An alias for the item.
					*
					* Expected Type:
					*
					*      - Text
					*
					* Used on these types:
					*
					*      - Thing
					*/

				if ( $alternateName ) {

					$output_item['alternateName'] = $alternateName;

				}

			// code

				/**
					* A medical code for the entity, taken from a controlled vocabulary or ontology
					* such as ICD-9, DiseasesDB, MeSH, SNOMED-CT, RxNorm, etc.
					*
					* Values expected to be one of these types:
					*
					*      - MedicalCode
					*
					* Used on these types:
					*
					*      - MedicalEntity
					*/

				if ( $code ) {

					$output_item['code'] = $code;

				}

			// codeValue

				/**
					* A short textual code that uniquely identifies the value.
					*
					* Values expected to be one of these types:
					*
					*      - Text
					*
					* Used on these types:
					*
					*      - CategoryCode
					*      - MedicalCode
					*
					* As of 22 Apr 2024, this term is in the "new" area of Schema.org. Implementation
					* feedback and adoption from applications and websites can help improve their
					* definitions.
					*/

				if ( $code ) {

					$output_item['codeValue'] = $code;

				}

			// codingSystem

				/**
					* The coding system, e.g. 'ICD-10'.
					*
					* Values expected to be one of these types:
					*
					*      - Text
					*
					* Used on these types:
					*
					*      - MedicalCode
					*/

				if ( $codingSystem ) {

					$output_item['codingSystem'] = $codingSystem;

				}

			// description

				/**
					* A description of the item.
					*
					* Values expected to be one of these types:
					*
					*      - Text
					*      - TextObject
					*
					* Used on these types:
					*
					*      - Thing
					*
					* Sub-properties:
					*
					*      - disambiguatingDescription
					*      - interpretedAsClaim
					*      - originalMediaContextDescription
					*      - sha256
					*/

				// Clean up value

					if ( $description ) {

						$description = wp_strip_all_tags($description);
						$description = str_replace("\n", ' ', $description); // Strip line breaks
						$description = uamswp_attr_conversion($description);

					}

				if ( $description ) {

					$output_item['description'] = $description;

				}

			// inCodeSet

				/**
					* A CategoryCodeSet that contains this category code.
					*
					* Values expected to be one of these types:
					*
					*      - CategoryCodeSet
					*      - URL
					*
					* Used on these types:
					*
					*      - CategoryCode
					*
					* As of 22 Apr 2024, this term is in the "new" area of Schema.org. Implementation
					* feedback and adoption from applications and websites can help improve their
					* definitions.
					*/

				if ( $inCodeSet ) {

					$output_item['inCodeSet'] = $inCodeSet;

				}

			// inDefinedTermSet

				/**
					* A DefinedTermSet that contains this term.
					*
					* Values expected to be one of these types:
					*
					*      - DefinedTermSet
					*      - URL
					*
					* Used on these types:
					*
					*      - DefinedTerm
					*
					* Sub-properties:
					*
					*      - inCodeSet
					*
					* As of 22 Apr 2024, this term is in the "new" area of Schema.org. Implementation
					* feedback and adoption from applications and websites can help improve their
					* definitions.
					*/

				if ( $inCodeSet ) {

					$output_item['inDefinedTermSet'] = $inCodeSet;

				}

			// name

				/**
					* The name of the item.
					*
					* Subproperty of:
					*
					*      - rdfs:label
					*
					* Expected Type:
					*
					*      - Text
					*/

				if ( $name ) {

					$output_item['name'] = $name;

				}

			// termCode

				/**
					* A code that identifies this DefinedTerm within a DefinedTermSet.
					*
					* Values expected to be one of these types:
					*
					*      - Text
					*
					* Used on these types:
					*
					*      - DefinedTerm
					*
					* Sub-properties:
					*
					*      - codeValue
					*
					* As of 22 Apr 2024, this term is in the "new" area of Schema.org. Implementation
					* feedback and adoption from applications and websites can help improve their
					* definitions.
					*/

				$output_item_termCode = $code ?? '';

				if ( $output_item_termCode ) {

					$output_item['termCode'] = $output_item_termCode;

				}

			// url

				/**
					* URL of the item.
					*
					* Expected Type:
					*
					*      - URL
					*
					* Used on these types:
					*
					*      - Thing
					*/

				$output_item_url = $url ?? '';

				if ( $output_item_url ) {

					$output_item['url'] = $output_item_url;

				}

		// Add the item to the output array

			if ( !$output ) {

				/**
				 * If the pre-existing output array is empty, then set the output value using the
				 * item value.
				 */

				$output = $output_item;

			} elseif ( !array_is_list($output) ) {

				/**
				 * If the pre-existing output array is an associative array, then add both the
				 * pre-existing output array value and the item value to the output array as items
				 * in a list array.
				 */

				$output = array_merge(
					array($output),
					array($output_item)
				);

			} else {

				/**
				 * Otherwise, add the item value to the output array as an additional item.
				 */

				$output[] = $output_item;

			}

		return $output;

	}

	// Add data to an array defining schema data for the Health Care Provider Taxonomy code set from Clinical Specialization ID values

		function uamswp_fad_schema_nucc_code_set_id(
			$id_arg, // int|int[] // Required // List of Clinical Specialization term IDs
			array $output = array() // array // Optional // Pre-existing schema array for the Health Care Provider Taxonomy code set to which to add items
		) {

			// Check / define variables

				// Convert term ID argument into a list array (if not already a list array)

					if ( !$id_arg ) {

						return $output;

					}

				// Convert Clinical Specialization term ID value into a list array (if not already a list array)

					$id_arg = is_array($id_arg) ? $id_arg : array($id_arg);
					$id_arg = array_is_list($id_arg) ? $id_arg : array($id_arg);

				// Convert output array into a list array (if not already a list array)

					$output = is_array($output) ? $output : array($output);
					$output = array_is_list($output) ? $output : array($output);

			// Loop through each Health Care Provider Taxonomy Code Set taxonomy item, adding values to the output array

				foreach ( $id_arg as $term_id ) {

					if ( $term_id ) {

						// Eliminate PHP errors / reset variables

							$output_item = array(); // Base item array
							$alternateName = null;
							$description = null;
							$code = null;
							$code_query = null;
							$extension_query = null;
							$name = null;

						// Get the term

							$term = get_term( $term_id, 'clinical_title' ) ?? array();

						// Get and set schema property values from the term

							if ( is_object($term) ) {

								/**
								 * The following properties are either beyond the scope of what is being included
								 * in this item schema, irrelevant to this item schema, or are superseded by
								 * another property, and so they will not be included:
								 *
								 *      * additionalType
								 *      * codeValue
								 *      * codingSystem
								 *      * disambiguatingDescription
								 *      * funding
								 *      * guideline
								 *      * identifier
								 *      * image
								 *      * inCodeSet
								 *      * inDefinedTermSet
								 *      * legalStatus
								 *      * mainEntityOfPage
								 *      * medicineSystem
								 *      * potentialAction
								 *      * recognizingAuthority
								 *      * relevantSpecialty
								 *      * sameAs
								 *      * study
								 *      * subjectOf
								 *      * termCode
								 *      * url
								 */

								// Get the Health Care Provider Taxonomy code

									// Query: Is this clinical specialization part of the UAMS Health extension to the Health Care Provider Taxonomy Code Set?

										$extension_query = get_field( 'clinical_specialization_extension_query', $term ) ?? false; // bool

									// Get the code from this term

										if ( !$extension_query ) {

											$code_query = get_field( 'clinical_specialization_code_query', $term ) ?? true; // Does this specialization have a taxonomy code in the Health Care Provider Taxonomy Code Set?

											// The specialization has a taxonomy code in the Health Care Provider Taxonomy code set

												if ( $code_query ) {

													$code = $code_query ? ( get_field( 'clinical_specialization_code', $term ) ?? '' ) : ''; // Specialization Taxonomy Code in the Health Care Provider Taxonomy Code Set

													// Set the fallback value (slug)

														if ( !$code ) {

															$slug = $term->post_name ?? '';
															$code = $slug ?? '';

															// Check if fallback value seems like a valid code

																if (
																	!(
																		$code
																		&&
																		strlen($code) == 10 // 10 digits
																		&&
																		( preg_match('/[A-Za-z]/', $code) && preg_match('/[0-9]/', $code) ) // Only letters and integers
																	)
																) {

																	$code = '';

																} // endif

														} // endif ( !$code )

												} // endif ( $code_query )

										} // endif ( !$extension_query )

									// If code value still does not exist, get the code from the nearest valid ancestor

										if ( !$code ) {

											// Get the list of ancestors

												$code_ancestors = get_ancestors(
													$term_id, // $object_id  // int // Optional // The ID of the object // Default: 0
													'clinical_title', // $object_type // string // Optional // The type of object for which we'll be retrieving ancestors. Accepts a post type or a taxonomy name. // Default: ''
													'taxonomy' // $resource_type // string // Optional // Type of resource $object_type is. Accepts 'post_type' or 'taxonomy'. // Default: ''
												);

											// Loop through each of the ancestors until finding one that does have a code in the code set

												if ( $code_ancestors ) {

													foreach ( $code_ancestors as $ancestor ) {

														$term = get_term( $ancestor, 'clinical_title' ) ?? array();

														if (
															$term // The term exists
														) {

															$extension_query = get_field( 'clinical_specialization_extension_query', $term ) ?? false; // Is this clinical specialization part of the UAMS Health extension to the Health Care Provider Taxonomy Code Set?

															if (
																!$extension_query // The specialization is not an extension to the Health Care Provider Taxonomy code set
															) {

																$code_query = get_field( 'clinical_specialization_code_query', $term ) ?? true; // Does this specialization have a taxonomy code in the Health Care Provider Taxonomy Code Set?

																if (
																	$code_query // The specialization has a taxonomy code in the Health Care Provider Taxonomy code set
																) {

																	$code = $code_query ? ( get_field( 'clinical_specialization_code', $term ) ?? '' ) : ''; // Specialization Taxonomy Code in the Health Care Provider Taxonomy Code Set

																	if ( $code ) {

																		// Break foreach loop
																		break;

																	} else {

																		// Set fallback value (slug)

																			$slug = $term->post_name ?? '';
																			$code = $slug ?? '';

																		// Check if fallback value seems like a valid code

																			if (
																				$code
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

																			} // endif else

																	} // endif ( $code ) else

																} else {

																	// Skip the rest of the current loop iteration
																	continue;

																}

															} else {

																// Skip the rest of the current loop iteration
																continue;

															} // endif ( !$extension_query ) else

														} else {

															// Skip the rest of the current loop iteration
															continue;

														}

													} // endforeach ( $code_ancestors as $ancestor )

												} // endif ( $code_ancestors )

										} // endif ( !$code )

								// Get the other property values

									if ( $code ) {

										// alternateName

											/**
											 * An alias for the item.
											 *
											 * Expected Type:
											 *
											 *      - Text
											 *
											 * Used on these types:
											 *
											 *      - Thing
											 */

											$alternateName = get_field( 'clinical_specialization_name_display', $term ) ?? '';
											$alternateName = $alternateName ? uamswp_attr_conversion($alternateName) : '';

										// description

											/**
											 * A description of the item.
											 *
											 * Values expected to be one of these types:
											 *
											 *      - Text
											 *      - TextObject
											 *
											 * Used on these types:
											 *
											 *      - Thing
											 *
											 * Sub-properties:
											 *
											 *      - disambiguatingDescription
											 *      - interpretedAsClaim
											 *      - originalMediaContextDescription
											 *      - sha256
											 */

											$description = get_field( 'clinical_specialization_definition', $term ) ?? '';

											// Clean up value

												if ( $description ) {

													$description = wp_strip_all_tags($description);
													$description = str_replace("\n", ' ', $description); // Strip line breaks
													$description = uamswp_attr_conversion($description);

												}

										// name

											/**
											 * The name of the item.
											 *
											 * Subproperty of:
											 *
											 *      - rdfs:label
											 *
											 * Expected Type:
											 *
											 *      - Text
											 */

											$name = get_field( 'clinical_specialty_name', $term ) ?? ( $term->name ?? ''); // Use the term's name  as fallback value
											$name = $name ? uamswp_attr_conversion($name) : '';

									}

								// Add the values to the schema output

									if ( $code ) {

										$output = uamswp_fad_schema_nucc_code_set(
											$code, // string // Required // Health Care Provider Taxonomy code
											$alternateName, // string|array // Optional // alternateName
											$description, // string // Optional // description
											$name, // string // Optional // name
											$output // array // Optional // Pre-existing schema array for the Health Care Provider Taxonomy code set to which to add items
										);

									}

							} // endif ( is_object($term) )

					} // endif ( $term_id )

				} // endforeach ( $id_arg as $term_id )

			return $output;

		}

// Add data to an array defining schema data for brand organization

	// Construct a single brand organization item

		function uamswp_fad_schema_brand_organization(
			string $slug // string // Required // Brand Organization term slug
		) {

			// Retrieve the value of the transient

				uamswp_fad_get_transient(
					'slug_' . $slug, // Required // String added to transient name for disambiguation.
					$output, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

			if ( !empty( $output ) ) {

				/**
				 * The transient exists.
				 * Return the variable.
				 */

				return $output;

			} else {

				/**
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Base array

					$output = array();

				// Define the list of taxonomy names to check

					$taxonomy_name = array(
						'brand_organization',
						'brand_organization_uams'
					);

				// Get the term object

					foreach ( $taxonomy_name as $name ) {

						$term = get_term_by(
							'slug', // string // Required // Either 'slug', 'name', 'term_id' (or 'id', 'ID'), or 'term_taxonomy_id'.
							$slug, // string|int // Required // Search for this term value.
							$name // string // Optional // Taxonomy name. Optional, if $field is 'term_taxonomy_id'.
						);

						// If term is valid, break the foreach loop

							if ( is_object($term) ) {

								break;

							}

					}

				// If term is invalid, bail early

					if ( !is_object($term) ) {

						return $output;

					}

				// Construct schema item

					/**
					 * The following properties are either beyond the scope of what is being included
					 * in this item schema, irrelevant to this item schema, or are superseded by
					 * another property, and so they will not be included:
					 *
					 *      * acceptedPaymentMethod
					 *      * additionalProperty
					 *      * agentInteractionStatistic
					 *      * aggregateRating
					 *      * alumni
					 *      * amenityFeature
					 *      * areaServed
					 *      * award
					 *      * awards
					 *      * branchCode
					 *      * branchOf
					 *      * brand
					 *      * contactPoints
					 *      * containedIn
					 *      * containedInPlace
					 *      * containsPlace
					 *      * currenciesAccepted
					 *      * department
					 *      * dissolutionDate
					 *      * employee
					 *      * employees
					 *      * event
					 *      * events
					 *      * founder
					 *      * founders
					 *      * foundingDate
					 *      * foundingLocation
					 *      * funder
					 *      * funding
					 *      * geo
					 *      * geoContains
					 *      * geoCoveredBy
					 *      * geoCovers
					 *      * geoCrosses
					 *      * geoDisjoint
					 *      * geoEquals
					 *      * geoIntersects
					 *      * geoOverlaps
					 *      * geoTouches
					 *      * geoWithin
					 *      * hasCertification
					 *      * hasCredential
					 *      * hasDriveThroughService
					 *      * hasGS1DigitalLink
					 *      * hasMerchantReturnPolicy
					 *      * hasOfferCatalog
					 *      * hasPOS
					 *      * hasProductReturnPolicy
					 *      * interactionStatistic
					 *      * isAccessibleForFree
					 *      * keywords
					 *      * knowsAbout
					 *      * knowsLanguage
					 *      * latitude
					 *      * location
					 *      * logo
					 *      * longitude
					 *      * makesOffer
					 *      * map
					 *      * maps
					 *      * masthead
					 *      * maximumAttendeeCapacity
					 *      * member
					 *      * memberOf
					 *      * members
					 *      * missionCoveragePrioritiesPolicy
					 *      * noBylinesPolicy
					 *      * numberOfEmployees
					 *      * openingHours
					 *      * openingHoursSpecification
					 *      * owns
					 *      * paymentAccepted
					 *      * photo
					 *      * photos
					 *      * potentialAction
					 *      * priceRange
					 *      * publicAccess
					 *      * review
					 *      * reviews
					 *      * seeks
					 *      * serviceArea
					 *      * smokingAllowed
					 *      * specialOpeningHoursSpecification
					 *      * sponsor
					 *      * sport
					 *      * subjectOf
					 *      * subOrganization
					 *      * tourBookingPage
					 *      * url
					 *      * verificationFactCheckingPolicy
					 */

					// mainEntityOfPage (common use)

						$mainEntityOfPage = get_field( 'brandorg_mainentityofpage', $term ) ?? null;

					// @id

						$id = $mainEntityOfPage ? $mainEntityOfPage . '#Organization' : '';

						if ( $id ) {

							$output['@id'] = $id;

						}

					// @type

						$type = 'Organization';
						$Organization_subtype = get_field( 'brandorg_organization_subtype', $term ) ?? null;
						$EducationalOrganization_subtype = get_field( 'brandorg_educationalorganization_subtype', $term ) ?? null;

						if (
							$Organization_subtype
							&&
							$EducationalOrganization_subtype
						) {

							$type = $EducationalOrganization_subtype;

						} elseif ( $Organization_subtype ) {

							$type = $Organization_subtype;

						}

					// actionableFeedbackPolicy

						/**
						 * [Insert definition here]
						 *
						 * Subproperty of:
						 *
						 *      - [Insert property name here]
						 *
						 * Values expected to be one of these types:
						 *
						 *      - [Insert type name here]
						 */

						$actionableFeedbackPolicy = get_field( 'brandorg_actionablefeedbackpolicy', $term ) ?? null;

						if ( $actionableFeedbackPolicy ) {

							$output['actionableFeedbackPolicy'] = $actionableFeedbackPolicy;

						}

					// additionalType

						/**
						 * An additional type for the item, typically used for adding more specific types
						 * from external vocabularies in microdata syntax. This is a relationship between
						 * something and a class that the thing is in. Typically the value is a
						 * URI-identified RDF class, and in this case corresponds to the use of rdf:type
						 * in RDF. Text values can be used sparingly, for cases where useful information
						 * can be added without their being an appropriate schema to reference. In the
						 * case of text values, the class label should follow the schema.org style guide.
						 *
						 * Subproperty of:
						 *      - rdf:type
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 *      - URL
						 *
						 * Used on these types:
						 *
						 *      - Thing
						 */

						// Get additionalType repeater field value

							$additionalType_repeater = get_field( 'schema_additionalType', $term ) ?? null;

						// Add each item to an array

							$additionalType = null;

							if ( $additionalType_repeater ) {

								$additionalType = uamswp_fad_schema_additionaltype(
									$additionalType_repeater, // additionalType repeater field
									'schema_additionalType_uri' // additionalType item field name
								);

							}

						if ( $additionalType ) {

							$output['additionalType'] = $additionalType;

						}

					// alternateName

						/**
						 * An alias for the item.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 */

						// Get alternateName repeater field value

							$alternateName_repeater = get_field( 'schema_alternatename', $term ) ?? null;

						// Add each item to alternateName property values array

							$alternateName = null;

							if ( $alternateName_repeater ) {

								$alternateName = uamswp_fad_schema_alternatename(
									$alternateName_repeater, // array // Required // alternateName repeater field
									'schema_alternatename_text' // string // Optional // alternateName item field name
								);

							}

						if ( $alternateName ) {

							$output['alternateName'] = $alternateName;

						}

					// contactPoint

						/**
						 * A contact point for a person or organization.
						 *
						 *      - ContactPoint
						 */

						// Base list array

							$contactPoint_list = array();

						// PostalAddress

							// Base root-level address value

								$address = null;

							// Get the repeater field

								$contactPoint_PostalAddress_repeater = get_field( 'brandorg_contactpoint_postaladdress', $term ) ?? null;

							// Get the subfields

								if ( $contactPoint_PostalAddress_repeater ) {

									foreach ( $contactPoint_PostalAddress_repeater as $item ) {

										// Base array

										$contactPoint_PostalAddress_item = array(
											'addressCountry' => '',
											'addressLocality' => '',
											'addressRegion' => '',
											'contactType' => '',
											'postalCode' => '',
											'postOfficeBoxNumber' => '',
											'streetAddress' => ''
										);

										// Set values

											// contactType

												$contactPoint_PostalAddress_item['contactType'] = $item['brandorg_contactpoint_postaladdress_contacttype'] ?? '';

											// streetAddress / postOfficeBoxNumber

												// Query: Street Address or Post Office Box?

													$contactPoint_PostalAddress_item_streetaddress_query = $item['brandorg_contactpoint_postaladdress_streetaddress_query'] ?? '';

												// streetAddress

													if ( $contactPoint_PostalAddress_item_streetaddress_query ) {

														$contactPoint_PostalAddress_item['streetaddress'] = $item['brandorg_contactpoint_postaladdress_streetaddress'] ?? '';

													}

												// postOfficeBoxNumber

													if ( !$contactPoint_PostalAddress_item_streetaddress_query ) {

														$contactPoint_PostalAddress_item['postofficeboxnumber'] = $item['brandorg_contactpoint_postaladdress_postofficeboxnumber'] ?? '';

													}

											// addressLocality

												$contactPoint_PostalAddress_item['addresslocality'] = $item['brandorg_contactpoint_postaladdress_addresslocality'] ?? '';

											// addressRegion

												$contactPoint_PostalAddress_item['addressregion'] = $item['brandorg_contactpoint_postaladdress_addressregion'] ?? '';

											// postalCode

												$contactPoint_PostalAddress_item['postalCode'] = $item['brandorg_contactpoint_postaladdress_postalcode'] ?? '';

											// addressCountry

												$contactPoint_PostalAddress_item['addresscountry'] = $item['brandorg_contactpoint_postaladdress_addresscountry'] ?? '';

										// Clean up item schema array

											$contactPoint_PostalAddress_item = array_filter($contactPoint_PostalAddress_item);

										// Add @type

											if ( $contactPoint_PostalAddress_item ) {

												$contactPoint_PostalAddress_item = array( '@type' => 'PostalAddress' ) + $contactPoint_PostalAddress_item;

											}

										// Add to list

											if ( $contactPoint_PostalAddress_item ) {

												$contactPoint_list[] = $contactPoint_PostalAddress_item;
												$address = $address ?? $contactPoint_PostalAddress_item;

											}

									}

								}

						// telephone

							// Base root-level telephone value

								$telephone = null;

							// Get the repeater field

								$contactPoint_telephone_repeater = get_field( 'brandorg_contactpoint_telephone', $term ) ?? null;

							// Get the subfields

								if ( $contactPoint_telephone_repeater ) {

									foreach ( $contactPoint_telephone_repeater as $item ) {

										// Base array

										$contactPoint_telephone_item = array(
											'contactOption' => '',
											'contactType' => '',
											'telephone' => ''
										);

										// Set values

											// contactType

												$contactPoint_telephone_item['contactType'] = $item['brandorg_contactpoint_telephone_contacttype'] ?? '';

											// telephone

												$contactPoint_telephone_item['telephone'] = $item['brandorg_contactpoint_telephone_text'] ?? '';

											// contactOption

												$contactPoint_telephone_item['contactOption'] = $item['brandorg_contactpoint_telephone_contactoption'] ?? '';

										// Clean up item schema array

											$contactPoint_telephone_item = array_filter($contactPoint_telephone_item);

										// Add @type

											if ( $contactPoint_telephone_item ) {

												$contactPoint_telephone_item = array( '@type' => 'ContactPoint' ) + $contactPoint_telephone_item;

											}

										// Add to list

											if ( $contactPoint_telephone_item ) {

												$contactPoint_list[] = $contactPoint_telephone_item;

											}

											if (
												$contactPoint_telephone_item
												&&
												isset($contactPoint_telephone_item['telephone'])
												&&
												!empty($contactPoint_telephone_item['telephone'])
											) {

												$contactPoint_list[] = $contactPoint_telephone_item;
												$telephone = $telephone ?? $contactPoint_telephone_item['telephone'];

											}

									}

								}

						// faxNumber

							// Base root-level faxNumber value

								$faxNumber = null;

							// Get the repeater field

								$contactPoint_faxNumber_repeater = get_field( 'brandorg_contactpoint_faxnumber', $term ) ?? null;

							// Get the subfields

								if ( $contactPoint_faxNumber_repeater ) {

									foreach ( $contactPoint_faxNumber_repeater as $item ) {

										// Base array

										$contactPoint_faxNumber_item = array(
											'contactType' => '',
											'faxNumber' => ''
										);

										// Set values

											// contactType

												$contactPoint_faxNumber_item['contactType'] = $item['brandorg_contactpoint_faxnumber_contacttype'] ?? '';

											// faxNumber

												$contactPoint_faxNumber_item['faxNumber'] = $item['brandorg_contactpoint_faxnumber_text'] ?? '';

										// Clean up item schema array

											$contactPoint_faxNumber_item = array_filter($contactPoint_faxNumber_item);

										// Add @type

											if ( $contactPoint_faxNumber_item ) {

												$contactPoint_faxNumber_item = array( '@type' => 'ContactPoint' ) + $contactPoint_faxNumber_item;

											}

										// Add to list

											if (
												$contactPoint_faxNumber_item
												&&
												isset($contactPoint_faxNumber_item['faxNumber'])
												&&
												!empty($contactPoint_faxNumber_item['faxNumber'])
											) {

												$contactPoint_list[] = $contactPoint_faxNumber_item;
												$faxNumber = $faxNumber ?? $contactPoint_faxNumber_item['faxNumber'];

											}

									}

								}

						// email

							// Base root-level email value

								$email = null;

							// Get the repeater field

								$contactPoint_email_repeater = get_field( 'brandorg_contactpoint_email', $term ) ?? null;

							// Get the subfields

								if ( $contactPoint_email_repeater ) {

									foreach ( $contactPoint_email_repeater as $item ) {

										// Base array

										$contactPoint_email_item = array(
											'contactType' => '',
											'email' => ''
										);

										// Set values

											// contactType

												$contactPoint_email_item['contactType'] = $item['brandorg_contactpoint_email_contacttype'] ?? '';

											// email

												$contactPoint_email_item['email'] = $item['brandorg_contactpoint_email_text'] ?? '';

										// Clean up item schema array

											$contactPoint_email_item = array_filter($contactPoint_email_item);

										// Add @type

											if (
												$contactPoint_email_item
											) {

												$contactPoint_email_item = array( '@type' => 'ContactPoint' ) + $contactPoint_email_item;

											}

										// Add to list

											if (
												$contactPoint_email_item
												&&
												isset($contactPoint_email_item['email'])
												&&
												!empty($contactPoint_email_item['email'])
											) {

												$contactPoint_list[] = $contactPoint_email_item;
												$email = $email ?? $contactPoint_email_item['email'];

											}

										// Add

									}

								}

						// Clean up the list array

							uamswp_fad_flatten_multidimensional_array($contactPoint_list);

						if ( $contactPoint_list ) {

							$output['contactPoint'] = $contactPoint_list;

						}

					// address

						/**
						 * Physical address of the item.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - PostalAddress
						 *      - Text
						 */

						if ( $address ) {

							$output['address'] = $address;

						}

					// email

						/**
						 * Email address.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 */

						if ( $email ) {

							$output['email'] = $email;

						}

					// faxNumber

						/**
						 * The fax number.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 */

						if ( $faxNumber ) {

							$output['faxNumber'] = $faxNumber;

						}

					// telephone

						/**
						 * The telephone number.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 */

						if ( $telephone ) {

							$output['telephone'] = $telephone;

						}

					// correctionsPolicy

						/**
						 * [Insert definition here]
						 *
						 * Subproperty of:
						 *
						 *      - [Insert property name here]
						 *
						 * Values expected to be one of these types:
						 *
						 *      - [Insert type name here]
						 */

						$correctionsPolicy = get_field( 'brandorg_correctionspolicy', $term ) ?? null;

						if ( $correctionsPolicy ) {

							$output['correctionsPolicy'] = $correctionsPolicy;

						}

					// description

						/**
						 * A description of the item.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 *      - TextObject
						 *
						 * Used on these types:
						 *
						 *      - Thing
						 *
						 * Sub-properties:
						 *
						 *      - disambiguatingDescription
						 *      - interpretedAsClaim
						 *      - originalMediaContextDescription
						 *      - sha256
						 */

						$description = get_field( 'brandorg_description', $term ) ?? null;

						if ( $description ) {

							$output['description'] = $description;

						}

					// disambiguatingDescription

						/**
						 * A sub property of description. A short description of the item used to
						 * disambiguate from other, similar items. Information from other properties (in
						 * particular, name) may be necessary for the description to be useful for
						 * disambiguation.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 *
						 * Used on these types:
						 *
						 *      - Thing
						 */

						$disambiguatingDescription = get_field( 'brandorg_disambiguatingdescription', $term ) ?? null;

						if ( $disambiguatingDescription ) {

							$output['disambiguatingDescription'] = $disambiguatingDescription;

						}

					// diversityPolicy

						/**
						 * Statement on diversity policy by an Organization
						 * (e.g., a NewsMediaOrganization).
						 *
						 * For a NewsMediaOrganization, a statement  describing the newsroom’s diversity
						 * policy on both staffing and sources, typically providing staffing data.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - CreativeWork
						 *      - URL
						 *
						 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
						 * feedback and adoption from applications and websites can help improve their
						 * definitions.
						 */

						$diversityPolicy = get_field( 'brandorg_diversitypolicy', $term ) ?? null;

						if ( $diversityPolicy ) {

							$output['diversityPolicy'] = $diversityPolicy;

						}

					// diversityStaffingReport

						/**
						 * For an Organization (often but not necessarily a NewsMediaOrganization), a
						 * report on staffing diversity issues. In a news context this might be for
						 * example ASNE or RTDNA (US) reports, or self-reported.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Article
						 *      - URL
						 *
						 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
						 * feedback and adoption from applications and websites can help improve their
						 * definitions.
						 */

						$diversityStaffingReport = get_field( 'brandorg_diversitystaffingreport', $term ) ?? null;

						if ( $diversityStaffingReport ) {

							$output['diversityStaffingReport'] = $diversityStaffingReport;

						}

					// ethicsPolicy

						/**
						 * Statement about ethics policy, (e.g., journalistic and publishing practices of
						 * a NewsMediaOrganization; food source policies of a Restaurant).
						 *
						 * In the case of a NewsMediaOrganization, an ethicsPolicy is typically a
						 * statement describing the personal, organizational, and corporate standards of
						 * behavior expected by the organization.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - CreativeWork
						 *      - URL
						 *
						 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
						 * feedback and adoption from applications and websites can help improve their
						 * definitions.
						 */

						$ethicsPolicy = get_field( 'brandorg_ethicspolicy', $term ) ?? null;

						if ( $ethicsPolicy ) {

							$output['ethicsPolicy'] = $ethicsPolicy;

						}

					// healthPlanNetworkId

						/**
						 * [Insert definition here]
						 *
						 * Subproperty of:
						 *
						 *      - [Insert property name here]
						 *
						 * Values expected to be one of these types:
						 *
						 *      - [Insert type name here]
						 */

						$healthPlanNetworkId = get_field( 'brandorg_healthplannetworkid', $term ) ?? null;

						if ( $healthPlanNetworkId ) {

							$output['healthPlanNetworkId'] = $healthPlanNetworkId;

						}

					// identifiers (multiple properties)

						// duns

							/**
							 * The Dun & Bradstreet DUNS number for identifying an organization or business
							 * person.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 */

							$duns = get_field( 'brandorg_duns', $term ) ?? null;

							if ( $duns ) {

								$output['duns'] = $duns;

							}

						// globalLocationNumber

							/**
							 * The Global Location Number (GLN, sometimes also referred to as International
							 * Location Number or ILN) of the respective organization, person, or place. The
							 * GLN is a 13-digit number used to identify parties and physical locations.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 */

							$globalLocationNumber = get_field( 'schema_globallocationnumber', $term ) ?? null;

							if ( $globalLocationNumber ) {

								$output['globalLocationNumber'] = $globalLocationNumber;

							}

						// isicV4

							/**
							 * The International Standard of Industrial Classification of All Economic
							 * Activities (ISIC), Revision 4 code for a particular organization, business
							 * person, or place.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 */

							$isicV4 = get_field( 'brandorg_isicv4', $term ) ?? null;

							if ( $isicV4 ) {

								$output['isicV4'] = $isicV4;

							}

						// iso6523Code

							/**
							 * An organization identifier as defined in ISO 6523(-1). Note that many existing
							 * organization identifiers such as leiCode, duns and vatID can be expressed as an
							 * ISO 6523 identifier by setting the ICD part of the ISO 6523 identifier
							 * accordingly.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
							 * feedback and adoption from applications and websites can help improve their
							 * definitions.
							 */

							$iso6523Code = get_field( 'brandorg_iso6523code', $term ) ?? null;

							if ( $iso6523Code ) {

								$output['iso6523Code'] = $iso6523Code;

							}

						// leiCode

							/**
							 * An organization identifier that uniquely identifies a legal entity as defined
							 * in ISO 17442.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 */

							$leiCode = get_field( 'brandorg_leicode', $term ) ?? null;

							if ( $leiCode ) {

								$output['leiCode'] = $leiCode;

							}

						// naics

							/**
							 * The North American Industry Classification System (NAICS) code for a particular
							 * organization or business person.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 */

							$naics = get_field( 'brandorg_naics', $term ) ?? null;

							if ( $naics ) {

								$output['naics'] = $naics;

							}

						// taxID (Taxpayer Identification Number)

							/**
							 * The Tax / Fiscal ID of the organization or person (e.g., the TIN in the US;
							 * the CIF/NIF in Spain).
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 */

							$taxID = get_field( 'brandorg_taxid', $term ) ?? null;

							if ( $taxID ) {

								$output['taxID'] = $taxID;

							}

						// vatID

							/**
							 * The Value-added Tax ID of the organization or person.
							 *
							 *      - Text
							 */

							$vatID = get_field( 'brandorg_vatid', $term ) ?? null;

							if ( $vatID ) {

								$output['vatID'] = $vatID;

							}

					// identifier

						/**
						 * The identifier property represents any kind of identifier for any kind of
						 * Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated
						 * properties for representing many of these, either as textual strings or as
						 * URL (URI) links.
						 *
						 * See https://schema.org/docs/datamodel.html#identifierBg for more details.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - PropertyValue
						 *      - Text
						 *      - URL
						 */

						// Base list array

							$identifier_list = array();

						// Get the repeater field

							$identifier_repeater = get_field( 'brandorg_identifier', $term ) ?? null;

						// Get the subfields

							if ( $identifier_repeater ) {

								foreach ( $identifier_repeater as $item ) {

									// Base array

									$identifier_item = array(
										'alternateName' => '',
										'maxValue' => '',
										'minValue' => '',
										'name' => '',
										'propertyID' => '',
										'unitCode' => '',
										'unitText' => '',
										'url' => '',
										'value' => ''
									);

									// Set values

										// alternateName

											/**
											 * An alias for the item.
											 *
											 * Expected Type:
											 *
											 *      - Text
											 *
											 * Used on these types:
											 *
											 *      - Thing
											 */

											// Get alternateName repeater field value

												$identifier_item_alternateName_repeater = $item['schema_alternatename'] ?? null;

											// Add each item to alternateName property values array

												if ( $identifier_item_alternateName_repeater ) {

													$identifier_item_alternateName = uamswp_fad_schema_alternatename(
														$identifier_item_alternateName_repeater, // array // Required // alternateName repeater field
														'schema_alternatename_text' // string // Optional // alternateName item field name
													);

												}

											$identifier_item['alternateName'] = $identifier_item_alternateName ?? '';

										// maxValue

											$identifier_item['maxValue'] = $item['brandorg_identifier_maxvalue'] ?? '';

										// minValue

											$identifier_item['minValue'] = $item['brandorg_identifier_minvalue'] ?? '';

										// name

											/**
											 * The name of the item.
											 *
											 * Expected Type:
											 *
											 *      - Text
											 */

											$identifier_item['name'] = $item['brandorg_identifier_name'] ?? '';

										// propertyID

											$identifier_item['propertyID'] = $item['brandorg_identifier_propertyid'] ?? '';

										// unitCode

											$identifier_item['unitCode'] = $item['brandorg_identifier_unitcode'] ?? '';

										// unitText

											$identifier_item['unitText'] = $item['brandorg_identifier_unittext'] ?? '';

										// url

											/**
											 * URL of the item.
											 *
											 * Expected Type:
											 *
											 *      - URL
											 */

											$identifier_item['url'] = $item['brandorg_identifier_url'] ?? '';

										// value

											$identifier_item['value'] = $item['brandorg_identifier_value'] ?? '';

									// Clean up item schema array

										$identifier_item = array_filter($contactPoint_email_item);

									// Add @type

										if ( $identifier_item ) {

											$identifier_item = array( '@type' => 'PropertyValue' ) + $identifier_item;

										}

									// Add to list

										if ( $identifier_item ) {

											$identifier_list[] = $identifier_item;

										}

								}

							}

						// Other specific identifiers

							// Google My Business customer ID (CID)

								// Query: Does this organization have a listing on Google My Business?

									$google_cid_query = get_field( 'brandorg_google_cid_query', $term ) ?? false;

								// Get value

									$google_cid = null;

									if ( $google_cid_query ) {

										$google_cid = get_field( 'schema_google_cid', $term ) ?? null;

									}

								// Add value to identifier list

									if ( $google_cid ) {

										$identifier_list = uamswp_fad_schema_propertyvalue_google_cid(
											$google_cid, // string|array // Required // Google customer ID
											$identifier_list // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
										);

									}

							// duns (Dun & Bradstreet DUNS number)

								$duns = $duns ?? null;

								if ( $duns ) {

									$identifier_list = uamswp_fad_schema_propertyvalue(
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
										'https://www.wikidata.org/wiki/Q246386', // string|array // Optional // propertyID property value
										null, // string // Optional // unitCode property value
										null, // string // Optional // unitText property value
										null, // string // Optional // url property value
										$duns, // string|array // Optional // value property value
										null, // mixed // Optional // valueReference property value
										$identifier_list // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
									);

								}

							// globalLocationNumber (Global Location Number)

								$globalLocationNumber = $globalLocationNumber ?? null;

								if ( $globalLocationNumber ) {

									$identifier_list = uamswp_fad_schema_propertyvalue(
										'GLN', // mixed // Optional // alternateName property value
										null, // string // Optional // description property value
										null, // int // Optional // maxValue property value
										null, // mixed // Optional // measurementMethod property value
										null, // mixed // Optional // measurementTechnique property value
										null, // int // Optional // minValue property value
										'Global Location Number', // string // Optional // name property value
										'https://www.wikidata.org/wiki/Q1258830', // string|array // Optional // propertyID property value
										null, // string // Optional // unitCode property value
										null, // string // Optional // unitText property value
										null, // string // Optional // url property value
										$globalLocationNumber, // string|array // Optional // value property value
										null, // mixed // Optional // valueReference property value
										$identifier_list // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
									);

								}

							// isicV4 (International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code)

								$isicV4 = $isicV4 ?? null;

								if ( $isicV4 ) {

									$identifier_list = uamswp_fad_schema_propertyvalue(
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
										'https://www.wikidata.org/wiki/Q112111674', // string|array // Optional // propertyID property value
										null, // string // Optional // unitCode property value
										null, // string // Optional // unitText property value
										null, // string // Optional // url property value
										$isicV4, // string|array // Optional // value property value
										null, // mixed // Optional // valueReference property value
										$identifier_list // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
									);

								}

							// leiCode (Legal Entity Identifier (LEI))

								$leiCode = $leiCode ?? null;

								if ( $leiCode ) {

									$identifier_list = uamswp_fad_schema_propertyvalue(
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
										'https://www.wikidata.org/wiki/Q6517388', // string|array // Optional // propertyID property value
										null, // string // Optional // unitCode property value
										null, // string // Optional // unitText property value
										null, // string // Optional // url property value
										$leiCode, // string|array // Optional // value property value
										null, // mixed // Optional // valueReference property value
										$identifier_list // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
									);

								}

							// naics (North American Industry Classification System (NAICS) code)

								$naics = $naics ?? null;

								if ( $naics ) {

									$identifier_list = uamswp_fad_schema_propertyvalue(
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
										'https://www.wikidata.org/wiki/Q3509282', // string|array // Optional // propertyID property value
										null, // string // Optional // unitCode property value
										null, // string // Optional // unitText property value
										null, // string // Optional // url property value
										$naics, // string|array // Optional // value property value
										null, // mixed // Optional // valueReference property value
										$identifier_list // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
									);

								}

							// taxID (Taxpayer Identification Number)

								$taxID = $taxID ?? null;

								if ( $taxID ) {

									$identifier_list = uamswp_fad_schema_propertyvalue(
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
										'https://www.wikidata.org/wiki/Q1444804', // string|array // Optional // propertyID property value
										null, // string // Optional // unitCode property value
										null, // string // Optional // unitText property value
										null, // string // Optional // url property value
										$taxID, // string|array // Optional // value property value
										null, // mixed // Optional // valueReference property value
										$identifier_list // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
									);

								}

							// vatID (Value-added tax (VAT) identification number)

								$vatID = $vatID ?? null;

								if ( $vatID ) {

									$identifier_list = uamswp_fad_schema_propertyvalue(
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
										'https://www.wikidata.org/wiki/Q2319042', // string|array // Optional // propertyID property value
										null, // string // Optional // unitCode property value
										null, // string // Optional // unitText property value
										null, // string // Optional // url property value
										$vatID, // string|array // Optional // value property value
										null, // mixed // Optional // valueReference property value
										$identifier_list // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
									);

								}

						if ( $identifier_list ) {

							$output['identifier'] = $identifier_list;

						}

					// hasMap

						/**
						 * A URL to a map of the place.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Map
						 *      - URL
						 *
						 * The examples on Schema.org indicate that a URL to the location on Google Maps
						 * is acceptable.
						 */

						$hasMap = null;

						if ( $google_cid ) {

							$hasMap = 'https://www.google.com/maps?cid=' . $google_cid;

						}

						if ( $hasMap ) {

							$output['hasMap'] = $hasMap;

						}

					// image

						/**
						 * An image of the item. This can be a URL or a fully described ImageObject.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - ImageObject
						 *      - URL
						 */

						$image = get_field( 'brandorg_image', $term ) ?? null;

						if ( $image ) {

							$output['image'] = $image;

						}

					// isAcceptingNewPatients

						/**
						 * Whether the provider is accepting new patients.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Boolean
						 *
						 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
						 * feedback and adoption from applications and websites can help improve their
						 * definitions.
						 */

						$isAcceptingNewPatients = get_field( 'brandorg_isacceptingnewpatients', $term ) ?? null;

						if (
							isset($isAcceptingNewPatients)
							&&
							!empty($isAcceptingNewPatients)
						) {

							$output['isAcceptingNewPatients'] = $isAcceptingNewPatients;

						}

					// legalName

						/**
						 * The official name of the organization (e.g., the registered company name).
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 */

						$legalName = get_field( 'brandorg_legalname', $term ) ?? null;

						if ( $legalName ) {

							$output['legalName'] = $legalName;

						}

					// mainEntityOfPage (specific property)

						/**
						 * Indicates a page (or other CreativeWork) for which this thing is the main
						 * entity being described. See background notes for details.
						 *
						 * Inverse property:
						 *
						 *      - mainEntity
						 *
						 * Values expected to be one of these types:
						 *
						 *      - CreativeWork
						 *      - URL
						 *
						 * Used on these types:
						 *
						 *      - Thing
						 */

						if ( $mainEntityOfPage ) {

							$output['mainEntityOfPage'] = $mainEntityOfPage;

						}

					// medicalSpecialty

						/**
						 * A medical specialty of the provider.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - MedicalSpecialty
						 */

						// Get values

							// Get medicalSpecialty multiselect field value

								$medicalSpecialty_multiselect = get_field( 'schema_medicalspecialty_multiple', $term ) ?? null;

						// Format value

							// Simple list of MedicalSpecialty values

								$medicalSpecialty = array();

							// Schema property values

								if ( $medicalSpecialty_multiselect ) {

									$medicalSpecialty = uamswp_fad_schema_medicalSpecialty_select(
										$medicalSpecialty_multiselect, // mixed // Required // MedicalSpecialty select or multi-select field value
										$medicalSpecialty // Optional // Array to populate with the list of MedicalSpecialty values
									);

								}

						if ( $medicalSpecialty ) {

							$output['medicalSpecialty'] = $medicalSpecialty;

						}

					// name

						/**
						 * The name of the item.
						 *
						 * Subproperty of:
						 *
						 *      - rdfs:label
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 */

						$name = get_field( 'brandorg_name', $term ) ?? null;

						if ( $name ) {

							$output['name'] = $name;

						}

					// nonprofitStatus

						/**
						 * nonprofitStatus indicates the legal status of a non-profit organization in its
						 * primary place of business.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - NonprofitType
						 *
						 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
						 * feedback and adoption from applications and websites can help improve their
						 * definitions.
						 */

						$nonprofitStatus = get_field( 'schema_nonprofitstatus', $term ) ?? null;

						if ( $nonprofitStatus ) {

							$output['nonprofitStatus'] = $nonprofitStatus;

						}

					// ownershipFundingInfo

						/**
						 * [Insert definition here]
						 *
						 * Subproperty of:
						 *
						 *      - [Insert property name here]
						 *
						 * Values expected to be one of these types:
						 *
						 *      - [Insert type name here]
						 */

						$ownershipFundingInfo = get_field( 'brandorg_ownershipfundinginfo', $term ) ?? null;

						if ( $ownershipFundingInfo ) {

							$output['ownershipFundingInfo'] = $ownershipFundingInfo;

						}

					// parentOrganization

						/**
						 * The larger organization that this organization is a subOrganization of, if any.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Organization
						 */

						// Get the term ID

							$term_id = $term->term_id;

						// Get the parent term ID, object and slug

							// Define the list of taxonomy names to check

								$taxonomy_name = array(
									'brand_organization',
									'brand_organization_uams'
								);

							$parent_term_id = null;
							$parent_term = null;
							$parent_term_slug = null;

							foreach ( $taxonomy_name as $name ) {

								$parent_term_id = wp_get_term_taxonomy_parent_id( $term_id, $name ) ?? null;

								if ( $parent_term_id ) {

									$parent_term = get_term( $parent_term_id, $name ) ?? null;

								}

								// If term is valid, break the foreach loop

									if ( is_object($parent_term) ) {

										$parent_term_slug = $parent_term->slug ?? null;

										break;

									}

							}

						// Construct the Organization schema for the parent

							$parentOrganization = null;

							if ( $parent_term_slug ) {

								$parentOrganization = uamswp_fad_schema_brand_organization(
									$parent_term_slug // string // Required // Brand Organization term slug
								);

							}

						if ( $parentOrganization ) {

							$output['parentOrganization'] = $parentOrganization;

						}

					// publishingPrinciples

						/**
						 * The publishingPrinciples property indicates (typically via URL) a document
						 * describing the editorial principles of an Organization or individual
						 * (e.g., a Person writing a blog) that relate to their activities as a publisher
						 * (e.g., ethics or diversity policies). When applied to a CreativeWork
						 * (e.g., NewsArticle) the principles are those of the party primarily responsible
						 * for the creation of the CreativeWork.
						 *
						 * While such policies are most typically expressed in natural language, sometimes
						 * related information (e.g., indicating a funder) can be expressed using
						 * schema.org terminology.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - CreativeWork
						 *      - URL
						 */

						$publishingPrinciples = get_field( 'brandorg_publishingprinciples', $term ) ?? null;

						if ( $publishingPrinciples ) {

							$output['publishingPrinciples'] = $publishingPrinciples;

						}

					// sameAs

						/**
						 * URL of a reference Web page that unambiguously indicates the item's identity
						 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
						 * website).
						 *
						 * Values expected to be one of these types:
						 *
						 *      - URL
						 */

						// Get sameAs repeater field value

							$sameAs_repeater = get_field( 'schema_sameas', $term ) ?? null;

						// Add each item to sameAs property values array

							$sameAs = null;

							if ( $sameAs_repeater ) {

								$sameAs = uamswp_fad_schema_sameas(
									$sameAs_repeater, // sameAs repeater field
									'schema_sameas_url' // sameAs item field name
								);

							}

						if ( $sameAs ) {

							$output['sameAs'] = $sameAs;

						}

					// slogan

						/**
						 * [Insert definition here]
						 *
						 * Subproperty of:
						 *
						 *      - [Insert property name here]
						 *
						 * Values expected to be one of these types:
						 *
						 *      - [Insert type name here]
						 */

						$slogan = get_field( 'brandorg_slogan', $term ) ?? null;

						if ( $slogan ) {

							$output['slogan'] = $slogan;

						}

					// tickerSymbol

						/**
						 * [Insert definition here]
						 *
						 * Subproperty of:
						 *
						 *      - [Insert property name here]
						 *
						 * Values expected to be one of these types:
						 *
						 *      - [Insert type name here]
						 */

						$tickerSymbol = get_field( 'brandorg_tickersymbol', $term ) ?? null;

						if ( $tickerSymbol ) {

							$output['tickerSymbol'] = $tickerSymbol;

						}

					// unnamedSourcesPolicy

						/**
						 * [Insert definition here]
						 *
						 * Subproperty of:
						 *
						 *      - [Insert property name here]
						 *
						 * Values expected to be one of these types:
						 *
						 *      - [Insert type name here]
						 */

						$unnamedSourcesPolicy = get_field( 'brandorg_unnamedsourcespolicy', $term ) ?? null;

						if ( $unnamedSourcesPolicy ) {

							$output['unnamedSourcesPolicy'] = $unnamedSourcesPolicy;

						}

					// url

						/**
						 * URL of the item.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - URL
						 */

						$url = get_field( 'brandorg_url', $term ) ?? null;

						if ( $url ) {

							$output['url'] = $url;

						}

				// Clean up the output array

					// Remove empty rows

						if ( $output ) {

							$output = array_filter($output);

						}

					// Add @type

						if ( $output ) {

							$output = array( '@type' => $type ) + $output;

						}

					// Sort rows by key

						if ( $output ) {

							ksort($output);

						}


				// Set/update the value of the transient

					uamswp_fad_set_transient(
						'slug_' . $slug, // Required // String added to transient name for disambiguation.
						$output, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

				// Return the array

					return $output;

			}

		}

	// Get the default brand organizations

		function uamswp_fad_schema_default_brand_organization(
			string $field_suffix // string enum('affiliation', 'clinical', 'copyright', 'credit', 'locationcreated') // Required // The suffix of the relevant Default UAMS Brand Organizations field
		) {

			// Retrieve the value of the transient

				uamswp_fad_get_transient(
					$field_suffix, // Required // String added to transient name for disambiguation.
					$output, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

			if ( !empty( $output ) ) {

				/**
				 * The transient exists.
				 * Return the variable.
				 */

				return $output;

			} else {

				/**
				 * The transient does not exist.
				 * Define the variable again.
				 */

				$slug = get_field( 'fad_default_brandorg_' . $field_suffix, 'option' ) ?? null;

				// If there is no value, bail early

					if ( !$slug ) {

						return;

					}

				// Construct the brand organization item

					if ( is_array($slug) ) {

						// Base output array

							$output = array();

						// Loop through each item, adding the Organization array to the output array

							foreach ( $slug as $item ) {

								if ( $item ) {

									$output[] = uamswp_fad_schema_brand_organization(
										$item // string // Required // Brand Organization term slug
									);

								}

							}

					} else {

						$output = uamswp_fad_schema_brand_organization(
							$slug // string // Required // Brand Organization term slug
						);

					}

				// Set/update the value of the transient

					uamswp_fad_set_transient(
						$field_suffix, // Required // String added to transient name for disambiguation.
						$output, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

				// Return the array

					return $output;

			}

		}

	// Construct a list of brand organization items associated with an entity

		function uamswp_fad_schema_brand_organization_list(
			$entity, // int|WP_Term // Required // Post ID or term object
			array $brand_org = array(), // array // Optional // Pre-existing list array for brand organizations to which to add additional items
			array &$brand_org_slug = array() // array // Optional // Pre-existing list array for brand organizations slugs to which to add additional slugs
		) {

			/**
			 * This function expects the brand organization(s) to be defined by either a field
			 * with the name 'schema_brandorg_multiple' (for multiple values) or a field with
			 * the name 'schema_brandorg' (for a single value).
			 */

			// Retrieve the value of the transient

				uamswp_fad_get_transient(
					'val_' . ( is_object($entity) ? $entity->term_id : $entity ), // Required // String added to transient name for disambiguation.
					$output, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

			if ( !empty( $output ) ) {

				/**
				 * The transient exists.
				 * Return the variable.
				 */

				return $output;

			} else {

				/**
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// If $entity is not valid, bail early

					if ( !$entity ) {

						return $brand_org;

					}

				// Check pre-existing list arrays, nesting the arrays if they are not list arrays

					if ( !array_is_list($brand_org) ) {

						$brand_org = array($brand_org);

					}

					$output = $brand_org;

					if ( !array_is_list($brand_org_slug) ) {

						$brand_org_slug = array($brand_org_slug);

					}

				// Get brand organization slug(s)

					// Check for multiple brand organization field value

						$field_value = get_field( 'schema_brandorg_multiple', $entity ) ?? null;

					// If no multiple brand organization field value, check for single brand organization value

						if ( !$field_value ) {

							$field_value = get_field( 'schema_brandorg', $entity ) ?? null;

							if ( $field_value ) {

								$field_value = array($field_value);

							}

						}

					// De-duplicate the brand organization field value

						if (
							$field_value
							&&
							is_array($field_value)
						) {

							$field_value = array_unique( $field_value, SORT_REGULAR );
							$field_value = array_values($field_value);

						}

				// If there is no field value, bail early

					if ( !$field_value ) {

						return $output;

					}

				// Construct the list of brand organization items

					foreach ( $field_value as $slug ) {

						if ( $slug ) {

							// Add the slug to the slug list

								$brand_org_slug[] = $slug;

							// Construct the brand organization items

								$output_temp = uamswp_fad_schema_brand_organization(
									$slug // string // Required // Brand Organization term slug
								);

							// If the item is valid, add it to the list

								if ( $output_temp ) {

									$output[] = $output_temp;

								}

						}

					}

				// Set/update the value of the transient

					uamswp_fad_set_transient(
						'val_' . ( is_object($entity) ? $entity->term_id : $entity ), // Required // String added to transient name for disambiguation.
						$output, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

				// Return the array

					return $output;

			}

		}

// Add data to an array defining schema data for UAMSHealth.com (WebSite)

	function uamswp_fad_schema_uamshealth_website(
		array &$node_identifier_list = array(), // array // Optional // List of node identifiers (@id) already defined in the schema
		int $nesting_level = 1 // int // Optional // Nesting level within the main schema
	) {

		// Retrieve the value of the item transient

			uamswp_fad_get_transient(
				'0', // Required // String added to transient name for disambiguation.
				$schema, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

		if ( !empty( $schema ) ) {

			/**
			 * The transient exists.
			 * Return the variable.
			 */

			return $schema;

		} else {

			/**
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Base schema function variables

				$schema_base_website_uams_health = array(); // Prevent this function from being called repeatedly from within the following included template part
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base_function.php' );

			// List of valid types

				/**
				 * Define the list of high-level types that are considered valid. The list may be
				 * expanded later to include the subtypes of these high-level types.
				 */

				// List of Schema.org types for which to not get the subtypes

					$uamshealth_website_valid_types = array();

				// List of Schema.org types for which to get the subtypes

					$uamshealth_website_valid_types_plus_subtypes = array(
						'WebSite'
					);

				// Base array for schema.org type URLs

					$uamshealth_website_valid_types_url = array();

				// Get a list of schema.org subtypes and URLs

					uamswp_fad_schema_subtypes_and_urls(
						$uamshealth_website_valid_types, // array // Required // List of Schema.org types for which to not get the subtypes
						$uamshealth_website_valid_types_plus_subtypes, // array // Optional // List of Schema.org types for which to get the subtypes
						$uamshealth_website_valid_types_url // string|array // Optional // Pre-existing list of schema.org URLs to which to add additional items
					);

			// List of valid properties for each type

				// Base array

					$uamshealth_website_properties_map = array();

				// Get list of valid properties from Schema.org type list

					foreach ( $uamshealth_website_valid_types as $item ) {

						$uamshealth_website_properties_map[$item]['properties'] = $schema_org_types[$item]['properties'] ?? array();
						$uamshealth_website_properties_map[$item]['properties'] = is_array($uamshealth_website_properties_map[$item]['properties']) ? $uamshealth_website_properties_map[$item]['properties'] : array($uamshealth_website_properties_map[$item]['properties']);

					}

			// Eliminate PHP errors / reset variables

				$schema = array(); // Base array

			// Add values

				// @type

					$type = 'WebSite';
					$schema['@type'] = $type;

				// @id

					$id = $schema_common_website_url . '#' . $type;
					$schema['@id'] = $id;

				// url

					/**
					 * URL of the item.
					 *
					 * Expected Type:
					 *
					 *      - URL
					 */

					$url = $schema_common_website_url;

					if ( $url ) {

						$schema['url'] = $url;

					}

				// Add common properties

					include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/properties.php' );

					// All types

						/*

							Loop through an associative array of properties common to all of our schema
							types, adding each row to this item's schema when the key matches a property
							valid for the type, replacing full values with only the node identifier where
							appropriate.

						*/

						if (
							isset($schema_common_properties)
							&&
							!empty($schema_common_properties)
						) {

							foreach ( $schema_common_properties as $key => &$value ) {

								// Add to item values

									if (
										is_array($value)
										&&
										array_is_list($value)
									) {

										$value_temp = array();

										foreach ( $value as &$item ) {

											uamswp_fad_schema_values_or_reference(
												$value_temp, // Property variable
												$item, // Full value variable
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											);

										}

										$value = $value_temp;

									}

									uamswp_fad_schema_add_to_item_values(
										$type, // string // Required // The @type value for the schema item
										$schema, // array // Required // The list array for the schema item to which to add the property value
										$key, // string // Required // Name of schema property
										$value, // mixed // Required // Variable to add as the property value
										$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
										$uamshealth_website_properties_map, // array // Required // Map array to match schema types with allowed properties
										($nesting_level + 1) // int // Required // Current nesting level value
									);

							}

						}

					// WebSite

						/*

							Loop through an associative array of properties specific to the WebSite type,
							adding each row to this item's schema when the key matches a property valid for
							the type, replacing full values with only the node identifier where appropriate.

						*/

						if (
							isset($schema_common_properties_WebSite)
							&&
							!empty($schema_common_properties_WebSite)
						) {

							foreach ( $schema_common_properties_WebSite as $key => $value ) {

								// Add to item values

									uamswp_fad_schema_add_to_item_values(
										$type, // string // Required // The @type value for the schema item
										$schema, // array // Required // The list array for the schema item to which to add the property value
										$key, // string // Required // Name of schema property
										$value, // mixed // Required // Variable to add as the property value
										$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
										$uamshealth_website_properties_map, // array // Required // Map array to match schema types with allowed properties
										($nesting_level + 1) // int // Required // Current nesting level value
									);

							}

						}

					// Main entity type

						/*

							Loop through an associative array of properties specific to the main entity
							type, adding each row to this item's schema when the key matches a property
							valid for the type, replacing full values with only the node identifier where
							appropriate.

						*/

						if (
							isset($schema_common_properties_main_entity)
							&&
							!empty($schema_common_properties_main_entity)
						) {

							foreach ( $schema_common_properties_main_entity as $key => $value ) {

								// Add to item values

									uamswp_fad_schema_add_to_item_values(
										$type, // string // Required // The @type value for the schema item
										$schema, // array // Required // The list array for the schema item to which to add the property value
										$key, // string // Required // Name of schema property
										$value, // mixed // Required // Variable to add as the property value
										$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
										$uamshealth_website_properties_map, // array // Required // Map array to match schema types with allowed properties
										($nesting_level + 1) // int // Required // Current nesting level value
									);

							}

						}

				// name

					/**
					 * The name of the item.
					 *
					 * Expected Type:
					 *
					 *      - Text
					 */

					// Get values

						$name = 'UAMS Health';

					// Add to item values

						uamswp_fad_schema_add_to_item_values(
							$type, // string // Required // The @type value for the schema item
							$schema, // array // Required // The list array for the schema item to which to add the property value
							'name', // string // Required // Name of schema property
							$name, // mixed // Required // Variable to add as the property value
							$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
							$uamshealth_website_properties_map, // array // Required // Map array to match schema types with allowed properties
							($nesting_level + 1) // int // Required // Current nesting level value
						);

				// additionalType

					/**
					 * An additional type for the item, typically used for adding more specific types
					 * from external vocabularies in microdata syntax. This is a relationship between
					 * something and a class that the thing is in. Typically the value is a
					 * URI-identified RDF class, and in this case corresponds to the use of rdf:type
					 * in RDF. Text values can be used sparingly, for cases where useful information
					 * can be added without their being an appropriate schema to reference. In the
					 * case of text values, the class label should follow the schema.org style guide.
					 *
					 * Subproperty of:
					 *      - rdf:type
					 *
					 * Values expected to be one of these types:
					 *
					 *      - Text
					 *      - URL
					 *
					 * Used on these types:
					 *
					 *      - Thing
					 *
					 * This property is beyond the scope of what is being included in the UAMS Health
					 * WebSite item schema and so it will not be included.
					 */

					$schema['additionalType'] = '';

			// Clean up the schema array

				if ( $schema ) {

					// Remove empty rows

						if ( is_array($schema) ) {

							$schema = array_filter($schema);

						}

					// Sort by key

						if ( is_array($schema) ) {

							ksort($schema);

						}

				}

			// Set/update the value of the item transient

				uamswp_fad_set_transient(
					'0', // Required // String added to transient name for disambiguation.
					$schema, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

			return $schema;

		}

	}

// Add data to an array defining schema data for a facility (Place)

	function uamswp_fad_schema_facility(
		int $term_id, // int // Required // Term ID for the facility
		int $nesting_level, // Nesting level within the main schema
		array $facility_items = array() // array // Optional // Pre-existing list array for facilities to which to add additional items
	) {

		/**
		 * The values of the 'Specific Type of Location'
		 * ('field_facility_place_subtype') field are expected to use the following
		 * structure:
		 *
		 *     Values representing a Schema.org type must begin with 'SchemaOrg_' followed by
		 *     the type value (e.g., 'SchemaOrg_CivicStructure' for a Schema.org type with the
		 *     URL 'https://schema.org/CivicStructure').
		 *
		 *     Values representing a Wikidata entry must begin with 'Wikidata_' followed by
		 *     the name of the entry in camel case, followed by an underscore, followed by the
		 *     entity ID of the Wikidata entry (e.g., 'Wikidata_MedicalFacility_Q4260475' for
		 *     an entry with the URL 'https://www.wikidata.org/wiki/Q4260475').
		 */

			// Base schema function variables

				include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base_function.php' );

			// List of valid types

				/**
				 * Define the list of high-level types that are considered valid. The list may be
				 * expanded later to include the subtypes of these high-level types.
				 */

				// List of Schema.org types for which to not get the subtypes

					$facility_valid_types = array(
						'Place'
					);

				// List of Schema.org types for which to get the subtypes

					$facility_valid_types_plus_subtypes = array(
						'CityHall',
						'CivicStructure',
						'Courthouse',
						'EventVenue',
						'GovernmentBuilding',
						'Hospital',
						'LegislativeBuilding',
						'StadiumOrArena'
					);

				// Base array for schema.org type URLs

					$facility_valid_types_url = array();

				// Get a list of schema.org subtypes and URLs

					uamswp_fad_schema_subtypes_and_urls(
						$facility_valid_types, // array // Required // List of Schema.org types for which to not get the subtypes
						$facility_valid_types_plus_subtypes, // array // Optional // List of Schema.org types for which to get the subtypes
						$facility_valid_types_url // string|array // Optional // Pre-existing list of schema.org URLs to which to add additional items
					);

			// List of valid properties for each type

				// Base array

					$facility_properties_map = array();

				// Get list of valid properties from Schema.org type list

					foreach ( $facility_valid_types as $item ) {

						$facility_properties_map[$item]['properties'] = $schema_org_types[$item]['properties'] ?? array();
						$facility_properties_map[$item]['properties'] = is_array($facility_properties_map[$item]['properties']) ? $facility_properties_map[$item]['properties'] : array($facility_properties_map[$item]['properties']);

					}

			// Retrieve the value of the transient

				uamswp_fad_get_transient(
					'val_' . $term_id, // Required // String added to transient name for disambiguation.
					$output, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

			if ( !empty( $output ) ) {

				/**
				 * The transient exists.
				 * Return the variable.
				 */

				return $output;

			} else {

				/**
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Get the term object

					$term = get_term(
						$term_id, // int|WP_Term|object // If integer, term data will be fetched from the database, or from the cache if available. If stdClass object (as in the results of a database query), will apply filters and return a WP_Term object with the $term data. If WP_Term, will return $term.
						'building' // string // Optional // Taxonomy name that $term is part of. // Default: ''
					);

				// If term is invalid, bail early

					if (
						!is_object($term)
						||
						$term->slug == '_none'
					) {

						return $output;

					}

				// Check variables

					if ( !array_is_list($facility_items) ) {

						$facility_items = array($facility_items);

					}

				// Base array

					$output = $facility_items;

				// Construct schema item

					/**
					 * The following properties are either beyond the scope of what is being included
					 * in the facility item schema, irrelevant to the facility item schema, or are
					 * superseded by another property, and so they will not be included:
					 *
					 * acceptsReservations
					 * accommodationCategory
					 * accommodationFloorPlan
					 * actionableFeedbackPolicy
					 * aggregateRating
					 * alumni
					 * amenityFeature
					 * archiveHeld
					 * areaServed
					 * audience
					 * availableLanguage
					 * availableService
					 * award
					 * awards
					 * bed
					 * branchCode
					 * branchOf
					 * checkinTime
					 * checkoutTime
					 * contactPoint
					 * contactPoints
					 * containedIn
					 * containsPlace
					 * correctionsPolicy
					 * currenciesAccepted
					 * department
					 * description
					 * disambiguatingDescription
					 * dissolutionDate
					 * diversityPolicy
					 * diversityStaffingReport
					 * duns
					 * email
					 * employee
					 * employees
					 * ethicsPolicy
					 * event
					 * events
					 * faxNumber
					 * feesAndCommissionsSpecification
					 * floorLevel
					 * floorSize
					 * founder
					 * founders
					 * foundingDate
					 * foundingLocation
					 * funder
					 * funding
					 * geoContains
					 * geoCoveredBy
					 * geoCovers
					 * geoCrosses
					 * geoDisjoint
					 * geoEquals
					 * geoIntersects
					 * geoOverlaps
					 * geoTouches
					 * geoWithin
					 * hasCredential
					 * hasDriveThroughService
					 * hasGS1DigitalLink
					 * hasMenu
					 * hasMerchantReturnPolicy
					 * hasOfferCatalog
					 * hasPOS
					 * hasProductReturnPolicy
					 * healthPlanNetworkId
					 * healthcareReportingData
					 * hospitalAffiliation
					 * iataCode
					 * icaoCode
					 * includesAttraction
					 * interactionStatistic
					 * isAcceptingNewPatients
					 * isicV4
					 * iso6523Code
					 * keywords
					 * knowsAbout
					 * knowsLanguage
					 * leaseLength
					 * legalName
					 * leiCode
					 * logo
					 * mainEntityOfPage
					 * makesOffer
					 * map
					 * maps
					 * maximumAttendeeCapacity
					 * medicalSpecialty
					 * member
					 * memberOf
					 * members
					 * menu
					 * naics
					 * nonprofitStatus
					 * numberOfAccommodationUnits
					 * numberOfAvailableAccommodationUnits
					 * numberOfBathroomsTotal
					 * numberOfBedrooms
					 * numberOfEmployees
					 * numberOfFullBathrooms
					 * numberOfPartialBathrooms
					 * numberOfRooms
					 * occupancy
					 * openingHours
					 * openingHoursSpecification
					 * ownershipFundingInfo
					 * owns
					 * paymentAccepted
					 * permittedUsage
					 * petsAllowed
					 * photos
					 * potentialAction
					 * priceRange
					 * publishingPrinciples
					 * review
					 * reviews
					 * screenCount
					 * seeks
					 * servesCuisine
					 * serviceArea
					 * slogan
					 * specialOpeningHoursSpecification
					 * sponsor
					 * starRating
					 * subOrganization
					 * subjectOf
					 * taxID
					 * telephone
					 * tourBookingPage
					 * touristType
					 * unnamedSourcesPolicy
					 * url
					 * vatID
					 * yearBuilt
					 */

					// Get common property values

						include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

					// @id

						// Get the value

							$id_fragment = 'Facility-' . $term_id;
							$id = $schema_common_website_url . '#' . $id_fragment;

						// Add the value to the property

							if ( $id ) {

								$output['@id'] = $id;

							}

					// @type

						// Get the value

							$type = 'Place';

							// Place Subtype (building or building complex)

								$Place_facility_query = get_field( 'facility_place_subtype', $term ) ?? null;
								$Place_subtype = $type;

								// Building subtype

									if (
										$Place_facility_query
										&&
										$Place_facility_query == 'building'
									) {

										$Place_subtype = get_field( 'facility_place_subtype_building', $term ) ?? $type;

									}

								// Building Complex subtype

									if (
										$Place_facility_query
										&&
										$Place_facility_query == 'complex'
									) {

										$Place_subtype = get_field( 'facility_place_subtype_complex', $term ) ?? $type;

									}

								// Schema.org subtype

									/**
									 * Extract the Schema.org type value from the field value and use it to construct
									 * the Schema.org type URL.
									 */

									$Place_subtype_SchemaOrg = $type;
									$Place_subtype_SchemaOrg_url = null;

									if ( $Place_subtype ) {

										if ( str_starts_with( $Place_subtype, 'SchemaOrg_' ) ) {

											$Place_subtype_SchemaOrg = substr(
												strrchr(
													$Place_subtype, // haystack
													'_' // needle
												), 1
											);

										}

									}

									if ( $Place_subtype_SchemaOrg ) {

										$Place_subtype_SchemaOrg_url = 'https://schema.org/' . $Place_subtype_SchemaOrg;

									}

								// Wikidata additionalType

									/**
									 * Extract the Wikidata entry entity ID value from the field value and use it to
									 * construct the Wikidata.org entry URL.
									 */

									$Place_subtype_Wikidata = null;

									if ( $Place_subtype ) {

										if ( str_starts_with( $Place_subtype, 'Wikidata_' ) ) {

											$Place_subtype_Wikidata = 'https://www.wikidata.org/wiki/' . substr(
												strrchr(
													$Place_subtype, // haystack
													'_' // needle
												), 1
											);

										}

									}

						// Add the value to the property

							if ( $Place_subtype_SchemaOrg ) {

								$type = $Place_subtype_SchemaOrg;

							}

					// brand organization (common use)

						// Get values

							// Query: Whether to override the default clinical brand organization for this entity

								$brand_override = get_field( 'schema_brandorg_query', $term ) ?? false;

							// Get the brand organization

								if ( $brand_override ) {

									// Get the third-party brand organization

										$brand = uamswp_fad_schema_brand_organization_list(
											$term // int|WP_Term // Required // Post ID or term object
										);

								} else {

									// Get the default clinical brand organization

										$brand = $schema_default_brand_organization_clinical ?? null;

								}

					// geo (common use)

						// Get map field value

							$geo_value = get_field( 'location_map', $term ) ?? null;

						// Check field values

							if ( $geo_value ) {

								$geo_value = ( array_key_exists( 'lat', $geo_value ) && array_key_exists( 'lng', $geo_value ) ) ? $geo_value : null;

							}

					// Google customer ID (CID) (common use)

						// Query: Does this organization have a listing on Google My Business?

							$google_cid_query = get_field( 'facility_google_cid_query', $term ) ?? false;

						// Get value

							$google_cid = null;

							if ( $google_cid_query ) {

								$google_cid = get_field( 'schema_google_cid', $term ) ?? null;

							}

					// photo (common use)

						// Get image ID

							$photo_id = get_field( 'facility_photo', $term ) ?? 0;

						// Create ImageObject from image ID

							$photo_ImageObject = uamswp_fad_schema_imageobject_thumbnails(
								$schema_common_website_url, // URL of entity with which the image is associated
								$nesting_level + 1, // Nesting level within the main schema
								'16:9', // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9', 'full')
								$id_fragment . '-Photo', // Base fragment identifier
								$photo_id, // ID of image to use for 1:1 aspect ratio
								0, // ID of image to use for 3:4 aspect ratio
								$photo_id, // ID of image to use for 4:3 aspect ratio
								$photo_id // ID of image to use for 16:9 aspect ratio
							);

					// additionalProperty

						/**
						 * A property-value pair representing an additional characteristic of the entity
						 * (e.g., a product feature or another characteristic for which there is no
						 * matching property in schema.org).
						 *
						 * Note: Publishers should be aware that applications designed to use specific
						 * schema.org properties (e.g., https://schema.org/width,
						 * https://schema.org/color, https://schema.org/gtin13) will typically expect such
						 * data to be provided using those properties, rather than using the generic
						 * property/value mechanism.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - PropertyValue
						 */

						if (
							in_array(
								'additionalProperty',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'additionalProperty',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Parking location

								// Get the value

									// Base property value

										$additionalProperty_parking = array();

									// Get Parking Facility

										// Get the term ID

											$parking_id = get_field( 'facility_parking', $term ) ?? null;

										// Get the term

											$parking_term = null;

											if ( $parking_id ) {

												$parking_term = get_term( $parking_id, 'parking' ) ?? null;

											}

											// Check the term

												if ( !is_object($parking_term) ) {

													$parking_term = null;

												}

									// Geo value

										$parking_geo_value = null;
										$parking_geo = null;

										if ( $parking_term ) {

											// Get the Google Map field value

												$parking_geo_value = get_field( 'parking_map', $parking_term ) ?? null;

											// Check Google Map field value

												if ( $parking_geo_value ) {

													$parking_geo_value = ( array_key_exists( 'lat', $parking_geo_value ) && array_key_exists( 'lng', $parking_geo_value ) ) ? $parking_geo_value : null;

												}

											// Format Google Map field value as GeoCoordinates

												$parking_geo = null;

												if ( $parking_geo_value ) {

													$parking_geo = uamswp_schema_geo_coordinates(
														$parking_geo_value['lat'], // string|int // Required // The latitude of a location. For example 37.42242 (WGS 84). // The precision must be at least 5 decimal places.
														$parking_geo_value['lng'] // string|int // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
													);

												}

										}

									// additionalType

										$parking_additionalType = 'https://schema.org/ParkingFacility';
										$parking_subtype = $parking_additionalType;
										$parking_subtype_value = null;
										$parking_subtype_SchemaOrg = null;
										$parking_subtype_Wikidata = null;

										if ( $parking_term ) {

											// Get the parking facility subtype

												$parking_subtype_value = get_field( 'parking_place_subtype', $parking_term ) ?? null;

											if ( $parking_subtype_value ) {

												// Schema.org subtype

													/**
													 * Extract the Schema.org type value from the field value and use it to construct
													 * the Schema.org type URL.
													 */

													if ( str_starts_with( $parking_subtype_value, 'SchemaOrg_' ) ) {

														$parking_subtype_SchemaOrg = 'https://schema.org/' . substr(
															strrchr(
																$parking_subtype_value, // haystack
																'_' // needle
															), 1
														);

													}

												// Wikidata additionalType

													/**
													 * Extract the Wikidata entry entity ID value from the field value and use it to
													* construct the Wikidata.org entry URL.
													*/

													if ( str_starts_with( $parking_subtype_value, 'Wikidata_' ) ) {

														$parking_subtype_Wikidata = 'https://www.wikidata.org/wiki/' . substr(
															strrchr(
																$parking_subtype_value, // haystack
																'_' // needle
															), 1
														);

													}

												// Select the value

													if ( $parking_subtype_SchemaOrg ) {

														$parking_subtype = $parking_subtype_SchemaOrg;

													} elseif ( $parking_subtype_Wikidata ) {

														$parking_subtype = $parking_subtype_Wikidata;

													}

											}


										}


									// Construct PropertyValue

										if ( $parking_geo ) {

											// additionalType

												$additionalProperty_parking['additionalType'] = $parking_additionalType;

											// alternateName

												/**
												 * An alias for the item.
												 *
												 * Expected Type:
												 *
												 *      - Text
												 *
												 * Used on these types:
												 *
												 *      - Thing
												 */

												$additionalProperty_parking['alternateName'] = array(
													'parking deck',
													'parking garage',
													'parking lot',
													'parking structure'
												);

											// description

												/**
												 * A description of the item.
												 *
												 * Values expected to be one of these types:
												 *
												 *      - Text
												 *      - TextObject
												 *
												 * Used on these types:
												 *
												 *      - Thing
												 *
												 * Sub-properties:
												 *
												 *      - disambiguatingDescription
												 *      - interpretedAsClaim
												 *      - originalMediaContextDescription
												 *      - sha256
												*/

												$additionalProperty_parking['description'] = 'A parking lot or other parking facility.';

											// name

												/**
												 * The name of the item.
												 *
												 * Expected Type:
												 *
												 *      - Text
												 */

												$additionalProperty_parking['name'] = 'Parking Facility';

											// propertyID

												if ( $parking_subtype == $parking_additionalType ) {

													$additionalProperty_parking['propertyID'] = 'https://www.wikidata.org/wiki/Q55697304'; // Wikidata entry for 'parking facility'

												} else {

													$additionalProperty_parking['propertyID'] = $parking_subtype;

												}

											// value

												$additionalProperty_parking['value'] = $parking_geo;

										}

								// Add the value to the property

									if ( $additionalProperty_parking ) {

										$output['additionalProperty'] = $additionalProperty_parking;

									}

						}

					// additionalType

						/**
						 * An additional type for the item, typically used for adding more specific types
						 * from external vocabularies in microdata syntax. This is a relationship between
						 * something and a class that the thing is in. Typically the value is a
						 * URI-identified RDF class, and in this case corresponds to the use of rdf:type
						 * in RDF. Text values can be used sparingly, for cases where useful information
						 * can be added without their being an appropriate schema to reference. In the
						 * case of text values, the class label should follow the schema.org style guide.
						 *
						 * Subproperty of:
						 *      - rdf:type
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 *      - URL
						 *
						 * Used on these types:
						 *
						 *      - Thing
						 */

						if (
							in_array(
								'additionalType',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'additionalType',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$additionalType = null;

								if ( $Place_subtype_Wikidata ) {

									$additionalType = $Place_subtype_Wikidata;

								} else {

									// Get additionalType repeater field value

										$additionalType_repeater = get_field( 'schema_additionalType', $term ) ?? null;

									// Add each item to additionalType property values array

										if ( $additionalType_repeater ) {

											$additionalType = uamswp_fad_schema_additionaltype(
												$additionalType_repeater, // additionalType repeater field
												'schema_additionalType_uri' // additionalType item field name
											);

										}

								}

							// Add the value to the property

								if ( $additionalType ) {

									$output['additionalType'] = $additionalType;

								}

						}

					// address

						/**
						 * Physical address of the item.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - PostalAddress
						 *      - Text
						 */

						if (
							in_array(
								'address',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'address',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$address = null;
								$address_streetAddress = get_field( 'facility_streetaddress', $term ) ?? '';
								$address_addressRegion = get_field( 'facility_state', $term ) ?? '';
								$address_addressLocality = get_field( 'facility_city', $term ) ?? '';
								$address_postalCode = get_field( 'facility_zip', $term ) ?? '';
								$address_addressCountry = $schema_common_usa;

								if (
									$address_streetAddress
									&&
									$address_addressRegion
									&&
									$address_addressLocality
									&&
									$address_postalCode
								) {

									$address = uamswp_fad_schema_postaladdress(
										'physical address', // string // Required // A person or organization can have different contact points, for different purposes. For example, a sales contact point, a PR contact point and so on. This property is used to specify the kind of contact point.
										$address_streetAddress, // string // Required // The street address or the post office box number for PO box addresses.
										true, // bool // Required // Query for whether the address is a street address (as opposed to a post office box number)
										$address_addressLocality, // string // Required // The locality in which the street address is, and which is in the region. For example, Mountain View.
										$address_addressRegion, // string // Required // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
										$address_postalCode, // string // Required // The postal code (e.g., 94043).
										$address_addressCountry // string|array // Optional // The country's ISO 3166-1 alpha-2 country code.
									);

								}

							// Add the value to the property

								if ( $address ) {

									$output['address'] = $address;

								}

						}

					// alternateName

						/**
						 * An alias for the item.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 */

						if (
							in_array(
								'alternateName',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'alternateName',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								// Get alternateName repeater field value

									$alternateName_repeater = get_field( 'schema_alternatename', $term ) ?? null;

								// Add each item to alternateName property values array

									$alternateName = null;

									if ( $alternateName_repeater ) {

										$alternateName = uamswp_fad_schema_alternatename(
											$alternateName_repeater, // array // Required // alternateName repeater field
											'schema_alternatename_text' // string // Optional // alternateName item field name
										);

									}

							// Add the value to the property

								if ( $alternateName ) {

									$output['alternateName'] = $alternateName;

								}

						}

					// brand (specific property)

						/**
						 * The brand(s) associated with a product or service, or the brand(s) maintained
						 * by an organization or business person.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Brand
						 *      - Organization
						 */

						if (
							in_array(
								'brand',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'brand',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$brand = $brand ?? null;

							// Add the value to the property

								if ( $brand ) {

									$output['brand'] = $brand;

								}

						}

					// containedInPlace [blocked]

						/**
						 * The basic containment relation between a place and one that contains it.
						 * expected to be one of these types:
						 *
						 *      - Place
						 *
						 * Create a taxonomy (or other solution) to define a campus in which this facility
						 * is contained.
						 */

						if (
							in_array(
								'containedInPlace',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'containedInPlace',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$containedInPlace = null;

							// Add the value to the property

								if ( $containedInPlace ) {

									$output['containedInPlace'] = $containedInPlace;

								}

						}

					// geo

						/**
						 * The geo coordinates of the place.
						 *
						 * The precision must be at least 5 decimal places.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - GeoCoordinates
						 *      - GeoShape
						 */

						if (
							in_array(
								'geo',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'geo',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$geo_value = $geo_value ?? null;
								$geo = null;

								if ( $geo_value ) {

									$geo = uamswp_schema_geo_coordinates(
										$geo_value['lat'], // string|int // Required // The latitude of a location. For example 37.42242 (WGS 84). // The precision must be at least 5 decimal places.
										$geo_value['lng'] // string|int // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
									);

								}

							// Add the value to the property

								if ( $geo ) {

									$output['geo'] = $geo;

								}

						}

					// globalLocationNumber

						/**
						 * The Global Location Number (GLN, sometimes also referred to as International
						 * Location Number or ILN) of the respective organization, person, or place. The
						 * GLN is a 13-digit number used to identify parties and physical locations.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 */

						if (
							in_array(
								'globalLocationNumber',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'globalLocationNumber',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$globalLocationNumber = get_field( 'schema_globallocationnumber', $term ) ?? null;

							// Add the value to the property

								if ( $globalLocationNumber ) {

									$output['globalLocationNumber'] = $globalLocationNumber;

								}

						}

					// hasMap

						/**
						 * A URL to a map of the place.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Map
						 *      - URL
						 *
						 * The examples on Schema.org indicate that a URL to the location on Google Maps
						 * is acceptable.
						 */

						if (
							in_array(
								'hasMap',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'hasMap',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$google_cid = $google_cid ?? null;
								$hasMap = null;

								if ( $google_cid ) {

									$hasMap = 'https://www.google.com/maps?cid=' . $google_cid;

								}

							// Add the value to the property

								if ( $hasMap ) {

									$output['hasMap'] = $hasMap;

								}

						}

					// identifier

						/**
						 * The identifier property represents any kind of identifier for any kind of
						 * Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated
						 * properties for representing many of these, either as textual strings or as
						 * URL (URI) links.
						 *
						 * See https://schema.org/docs/datamodel.html#identifierBg for more details.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - PropertyValue
						 *      - Text
						 *      - URL
						 */

						if (
							in_array(
								'identifier',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'identifier',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								// Base array

									$identifier = array();

								// Google customer ID (CID)

									$google_cid = $google_cid ?? null;

									if ( $google_cid ) {

										$identifier = uamswp_fad_schema_propertyvalue_google_cid(
											$google_cid, // string|array // Required // Google customer ID
											$identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
										);

									}

							// Add the value to the property

								if ( $identifier ) {

									$output['identifier'] = $identifier;

								}

						}

					// image

						/**
						 * An image of the item. This can be a URL or a fully described ImageObject.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - ImageObject
						 *      - URL
						 */

						if (
							in_array(
								'image',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'image',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$photo_ImageObject = $photo_ImageObject ?? null;
								$image = $photo_ImageObject;

							// Add the value to the property

								if ( $image ) {

									$output['image'] = $image;

								}

						}

					// isAccessibleForFree

						/**
						 * A flag to signal that the item, event, or place is accessible for free.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Boolean
						 */

						if (
							in_array(
								'isAccessibleForFree',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'isAccessibleForFree',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$isAccessibleForFree = 'True';

							// Add the value to the property

								if ( $isAccessibleForFree ) {

									$output['isAccessibleForFree'] = $isAccessibleForFree;

								}

						}

					// latitude

						/**
						 * The latitude of a location. For example 37.42242 (WGS 84).
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Number
						 *      - Text
						 */

						if (
							in_array(
								'latitude',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'latitude',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$latitude = null;
								$geo_value = $geo_value ?? null;

								if ( $geo_value ) {

									$latitude = number_format(
										$geo_value['lat'], // float // The number being formatted.
										5, // int // Sets the number of decimal digits. If 0, the decimal_separator is omitted from the return value.
										'.', // ?string // Sets the separator for the decimal point.
										'' // ?string // Sets the thousands separator.
									);

								}

							// Add the value to the property

								if ( $latitude ) {

									$output['latitude'] = $latitude;

								}

						}

					// location

						/**
						 * The location of, for example, where an event is happening, where an
						 * organization is located, or where an action takes place.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Place
						 *      - PostalAddress
						 *      - Text
						 *      - VirtualLocation
						 */

						if (
							in_array(
								'location',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'location',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$address = $address ?? null;
								$location = $address;

							// Add the value to the property

								if ( $location ) {

									$output['location'] = $location;

								}

						}

					// longitude

						/**
						 * The longitude of a location. For example -122.08585 (WGS 84).
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Number
						 *      - Text
						 */

						if (
							in_array(
								'longitude',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'longitude',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$geo_value = $geo_value ?? null;
								$longitude = null;

								if ( $geo_value ) {

									$longitude = number_format(
										$geo_value['lng'], // float // The number being formatted.
										5, // int // Sets the number of decimal digits. If 0, the decimal_separator is omitted from the return value.
										'.', // ?string // Sets the separator for the decimal point.
										'' // ?string // Sets the thousands separator.
									);

								}

							// Add the value to the property

								if ( $longitude ) {

									$output['longitude'] = $longitude;

								}

						}

					// name

						/**
						 * The name of the item.
						 *
						 * Subproperty of:
						 *
						 *      - rdfs:label
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Text
						 */

						if (
							in_array(
								'name',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'name',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$name = get_field( 'facility_name', $term ) ?? null;

							// Add the value to the property

								if ( $name ) {

									$output['name'] = $name;

								}

						}

					// parentOrganization

						/**
						 * The larger organization that this organization is a subOrganization of, if any.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Organization
						 */

						if (
							in_array(
								'parentOrganization',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'parentOrganization',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$brand = $brand ?? null;

							// Add the value to the property

								if ( $brand ) {

									$output['parentOrganization'] = $brand;

								}

						}

					// photo

						/**
						 * A photograph of this place.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - ImageObject
						 *      - Photograph
						 */

						if (
							in_array(
								'photo',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'photo',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$photo_ImageObject = $photo_ImageObject ?? null;
								$photo = $photo_ImageObject;

							// Add the value to the property

								if ( $photo ) {

									$output['photo'] = $photo;

								}

						}

					// publicAccess

						/**
						 * A flag to signal that the Place is open to public visitors. If this property
						 * is omitted there is no assumed default boolean value.
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Boolean
						 */

						if (
							in_array(
								'publicAccess',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'publicAccess',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$publicAccess = 'True';

							// Add the value to the property

								if ( $publicAccess ) {

									$output['publicAccess'] = $publicAccess;

								}

						}

					// sameAs

						/**
						 * URL of a reference Web page that unambiguously indicates the item's identity
						 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
						 * website).
						 *
						 * Values expected to be one of these types:
						 *
						 *      - URL
						 */

						if (
							in_array(
								'sameAs',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'sameAs',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								// Get sameAs repeater field value

									$sameAs_repeater = get_field( 'schema_sameas', $term ) ?? null;

								// Add each item to sameAs property values array

									$sameAs = null;

									if ( $sameAs_repeater ) {

										$sameAs = uamswp_fad_schema_sameas(
											$sameAs_repeater, // sameAs repeater field
											'schema_sameas_url' // sameAs item field name
										);

									}

							// Add the value to the property

								if ( $sameAs ) {

									$output['sameAs'] = $sameAs;

								}

						}

					// smokingAllowed

						/**
						 * Indicates whether it is allowed to smoke in the place (e.g., in the restaurant,
						 * hotel or hotel room).
						 *
						 * Values expected to be one of these types:
						 *
						 *      - Boolean
						 */

						if (
							in_array(
								'smokingAllowed',
								$facility_properties_map[$type]['properties']
							)
							||
							in_array(
								'smokingAllowed',
								$facility_properties_map[$type]['properties']
							)
						) {

							// Get the value

								$smokingAllowed = 'False';

							// Add the value to the property

								if ( $smokingAllowed ) {

									$output['smokingAllowed'] = $smokingAllowed;

								}

						}

				// Clean up the output array

					// Remove empty rows

						if ( $output ) {

							$output = array_filter($output);

						}

					// Add @type

						if ( $output ) {

							$output = array( '@type' => $type ) + $output;

						}

					// Sort rows by key

						if ( $output ) {

							ksort($output);

						}


				// Set/update the value of the transient

					uamswp_fad_set_transient(
						'val_' . $term_id, // Required // String added to transient name for disambiguation.
						$output, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

				// Return the array

					return $output;

			}

	}

