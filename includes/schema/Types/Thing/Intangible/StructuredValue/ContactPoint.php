<?php

// ContactPoint

	/*
	 * Thing > Intangible > StructuredValue > ContactPoint
	 * 
	 * A contact point—for example, a Customer Complaints department.
	 */

	function uamswp_fad_schema_contactpoint(
		$schema, // array // Main schema array
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

			// Properties from ContactPoint (Thing > Intangible > StructuredValue > ContactPoint)

				$areaServed = ( isset($areaServed) && !empty($areaServed) ) ? $areaServed : '';
				$availableLanguage = ( isset($availableLanguage) && !empty($availableLanguage) ) ? $availableLanguage : '';
				$contactOption = ( isset($contactOption) && !empty($contactOption) ) ? $contactOption : '';
				$contactType = ( isset($contactType) && !empty($contactType) ) ? $contactType : '';
				$email = ( isset($email) && !empty($email) ) ? $email : '';
				$faxNumber = ( isset($faxNumber) && !empty($faxNumber) ) ? $faxNumber : '';
				$hoursAvailable = ( isset($hoursAvailable) && !empty($hoursAvailable) ) ? $hoursAvailable : '';
				$productSupported = ( isset($productSupported) && !empty($productSupported) ) ? $productSupported : '';
				$telephone = ( isset($telephone) && !empty($telephone) ) ? $telephone : '';
	
		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_structuredvalue(
					$schema, // array // Main schema array
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

			// Properties from ContactPoint (Thing > Intangible > StructuredValue > ContactPoint)

				// areaServed

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					 $schema['areaServed'] = ( isset($areaServed) && !empty($areaServed) ) ? uamswp_fad_schema_type_selector($areaServed) : '';

				// availableLanguage

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					 $schema['availableLanguage'] = ( isset($availableLanguage) && !empty($availableLanguage) ) ? uamswp_fad_schema_type_selector($availableLanguage) : '';

				// contactOption

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					 $schema['contactOption'] = ( isset($contactOption) && !empty($contactOption) ) ? uamswp_fad_schema_type_selector($contactOption) : '';

				// contactType

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					 $schema['contactType'] = ( isset($contactType) && !empty($contactType) ) ? uamswp_fad_schema_type_selector($contactType) : '';

				// email

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					 $schema['email'] = ( isset($email) && !empty($email) ) ? uamswp_fad_schema_type_selector($email) : '';

				// faxNumber

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					 $schema['faxNumber'] = ( isset($faxNumber) && !empty($faxNumber) ) ? uamswp_fad_schema_type_selector($faxNumber) : '';

				// hoursAvailable

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					 $schema['hoursAvailable'] = ( isset($hoursAvailable) && !empty($hoursAvailable) ) ? uamswp_fad_schema_type_selector($hoursAvailable) : '';

				// productSupported

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					 $schema['productSupported'] = ( isset($productSupported) && !empty($productSupported) ) ? uamswp_fad_schema_type_selector($productSupported) : '';

				// telephone

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					 $schema['telephone'] = ( isset($telephone) && !empty($telephone) ) ? uamswp_fad_schema_type_selector($telephone) : '';

		 
		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}

	// PostalAddress
	include_once __DIR__ . '/Place/PostalAddress.php';