<?php

// PostalAddress

	/*
	 * Thing > Intangible > StructuredValue > ContactPoint > PostalAddress
	 * 
	 * The mailing address.
	 */

	function uamswp_fad_schema_postaladdress(
		$schema, // array // Main schema array
		// PostalAddress
			$addressCountry = '', // addressCountry
			$addressLocality = '', // addressLocality
			$addressRegion = '', // addressRegion
			$postalCode = '', // postalCode
			$postOfficeBoxNumber = '', // postOfficeBoxNumber
			$streetAddress = '', // streetAddress
		// ContactPoint
			$areaServed = '', // areaServed
			$availableLanguage = '', // availableLanguage
			$contactOption = '', // contactOption
			$contactType = '', // contactType
			$email = '', // email
			$faxNumber = '', // faxNumber
			$hoursAvailable = '', // hoursAvailable
			$productSupported = '', // productSupported
			$telephone = '', // telephone
		// StructuredValue (no property vars)
		// Intangible (no property vars)
		// Thing
			$additionalType = '', // additionalType
			$alternateName = '', // alternateName
			$description = '', // description
			$disambiguatingDescription = '', // disambiguatingDescription
			$identifier = '', // identifier
			$image = '', // image
			$mainEntityOfPage = '', // mainEntityOfPage
			$name = '', // name
			$potentialAction = '', // potentialAction
			$sameAs = '', // sameAs
			$subjectOf = '', // subjectOf
			$url = '' // url
	) {

		// Check/define variables

			$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

			// Inherited properties from Thing

				$additionalType = ( isset($additionalType) && !empty($additionalType) ) ? $additionalType : '';
				$alternateName = ( isset($alternateName) && !empty($alternateName) ) ? $alternateName : '';
				$description = ( isset($description) && !empty($description) ) ? $description : '';
				$disambiguatingDescription = ( isset($disambiguatingDescription) && !empty($disambiguatingDescription) ) ? $disambiguatingDescription : '';
				$identifier = ( isset($identifier) && !empty($identifier) ) ? $identifier : '';
				$image = ( isset($image) && !empty($image) ) ? $image : '';
				$mainEntityOfPage = ( isset($mainEntityOfPage) && !empty($mainEntityOfPage) ) ? $mainEntityOfPage : '';
				$name = ( isset($name) && !empty($name) ) ? $name : '';
				$potentialAction = ( isset($potentialAction) && !empty($potentialAction) ) ? $potentialAction : '';
				$sameAs = ( isset($sameAs) && !empty($sameAs) ) ? $sameAs : '';
				$subjectOf = ( isset($subjectOf) && !empty($subjectOf) ) ? $subjectOf : '';
				$url = ( isset($url) && !empty($url) ) ? $url : '';

			// Inherited properties from Intangible (Thing > Intangible)

				// Do nothing (no property vars)

			// Inherited properties from StructuredValue (Thing > Intangible > StructuredValue)

				// Do nothing (no property vars)

			// Inherited properties from ContactPoint (Thing > Intangible > StructuredValue > ContactPoint)

				$areaServed = ( isset($areaServed) && !empty($areaServed) ) ? $areaServed : '';
				$availableLanguage = ( isset($availableLanguage) && !empty($availableLanguage) ) ? $availableLanguage : '';
				$contactOption = ( isset($contactOption) && !empty($contactOption) ) ? $contactOption : '';
				$contactType = ( isset($contactType) && !empty($contactType) ) ? $contactType : '';
				$email = ( isset($email) && !empty($email) ) ? $email : '';
				$faxNumber = ( isset($faxNumber) && !empty($faxNumber) ) ? $faxNumber : '';
				$hoursAvailable = ( isset($hoursAvailable) && !empty($hoursAvailable) ) ? $hoursAvailable : '';
				$productSupported = ( isset($productSupported) && !empty($productSupported) ) ? $productSupported : '';
				$telephone = ( isset($telephone) && !empty($telephone) ) ? $telephone : '';

			// Properties from PostalAddress (Thing > Intangible > StructuredValue > ContactPoint > PostalAddress)

				$addressCountry = ( isset($addressCountry) && !empty($addressCountry) ) ? $addressCountry : '';
				$addressLocality = ( isset($addressLocality) && !empty($addressLocality) ) ? $addressLocality : '';
				$addressRegion = ( isset($addressRegion) && !empty($addressRegion) ) ? $addressRegion : '';
				$postalCode = ( isset($postalCode) && !empty($postalCode) ) ? $postalCode : '';
				$postOfficeBoxNumber = ( isset($postOfficeBoxNumber) && !empty($postOfficeBoxNumber) ) ? $postOfficeBoxNumber : '';
				$streetAddress = ( isset($streetAddress) && !empty($streetAddress) ) ? $streetAddress : '';

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_contactpoint(
					$schema, // array // Main schema array
					// ContactPoint
						$areaServed, // areaServed
						$availableLanguage, // availableLanguage
						$contactOption, // contactOption
						$contactType, // contactType
						$email, // email
						$faxNumber, // faxNumber
						$hoursAvailable, // hoursAvailable
						$productSupported, // productSupported
						$telephone, // telephone
					// StructuredValue (no property vars)
					// Intangible (no property vars)
					// Thing
						$additionalType, // additionalType
						$alternateName, // alternateName
						$description, // description
						$disambiguatingDescription, // disambiguatingDescription
						$identifier, // identifier
						$image, // image
						$mainEntityOfPage, // mainEntityOfPage
						$name, // name
						$potentialAction, // potentialAction
						$sameAs, // sameAs
						$subjectOf, // subjectOf
						$url // url
				);

			// Properties from PostalAddress (Thing > Intangible > StructuredValue > ContactPoint > PostalAddress)

				// addressCountry

					/* 
					 * Expected Type:
					 *     Country
					 *     DataType > Text
					 * 
					 * The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.
					 */

					$schema['addressCountry'] = ( isset($addressCountry) && !empty($addressCountry) ) ? uamswp_fad_schema_type_selector($addressCountry) : '';

				// addressLocality

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The locality in which the street address is, and which is in the region. For example, Mountain View.
					 */

					$schema['addressLocality'] = ( isset($addressLocality) && !empty($addressLocality) ) ? uamswp_fad_schema_type_selector($addressLocality) : '';

				// addressRegion

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
					 */

					$schema['addressRegion'] = ( isset($addressRegion) && !empty($addressRegion) ) ? uamswp_fad_schema_type_selector($addressRegion) : '';

				// postalCode

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The postal code. For example, 94043.
					 */

					$schema['postalCode'] = ( isset($postalCode) && !empty($postalCode) ) ? uamswp_fad_schema_type_selector($postalCode) : '';

				// postOfficeBoxNumber

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The post office box number for PO box addresses.
					 */

					$schema['postOfficeBoxNumber'] = ( isset($postOfficeBoxNumber) && !empty($postOfficeBoxNumber) ) ? uamswp_fad_schema_type_selector($postOfficeBoxNumber) : '';

				// streetAddress

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The street address. For example, 1600 Amphitheatre Pkwy.
					 */

					$schema['streetAddress'] = ( isset($streetAddress) && !empty($streetAddress) ) ? uamswp_fad_schema_type_selector($streetAddress) : '';

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}