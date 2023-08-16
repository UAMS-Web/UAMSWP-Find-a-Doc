<?php

// StructuredValue

	/*
	 * Thing > Intangible > StructuredValue
	 * 
	 * Structured values are used when the value of a property has a more complex 
	 * structure than simply being a textual value or a reference to another thing.
	 */

	function uamswp_fad_schema_structuredvalue(
		$schema, // array // Main schema array
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

			// Properties from StructuredValue (Thing > Intangible > StructuredValue)

				// Do nothing (no property vars)

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_intangible(
					$schema, // array // Main schema array
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

			// Properties from StructuredValue (Thing > Intangible > StructuredValue)

				// Do nothing (no property vars)

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}

	// CDCPMDRecord
	include_once __DIR__ . '/StructuredValue/CDCPMDRecord.php';

		/*
		 * Thing > Intangible > StructuredValue > CDCPMDRecord
		 * 
		 * 
		 */

		function uamswp_fad_schema_cdcpmdrecord(
			$schema, // array // Main schema array
			// CDCPMDRecord
				$foo = '', // foo
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
	
				// Properties from CDCPMDRecord (Thing > Intangible > StructuredValue > CDCPMDRecord)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from CDCPMDRecord (Thing > Intangible > StructuredValue > CDCPMDRecord)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// ContactPoint
	include_once __DIR__ . '/StructuredValue/ContactPoint.php';

	// DatedMoneySpecification
	include_once __DIR__ . '/StructuredValue/DatedMoneySpecification.php';

		/*
		 * Thing > Intangible > StructuredValue > DatedMoneySpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_datedmoneyspecification(
			$schema, // array // Main schema array
			// DatedMoneySpecification
				$foo = '', // foo
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
	
				// Properties from DatedMoneySpecification (Thing > Intangible > StructuredValue > DatedMoneySpecification)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from DatedMoneySpecification (Thing > Intangible > StructuredValue > DatedMoneySpecification)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// DefinedRegion
	include_once __DIR__ . '/StructuredValue/DefinedRegion.php';

		/*
		 * Thing > Intangible > StructuredValue > DefinedRegion
		 * 
		 * 
		 */

		function uamswp_fad_schema_definedregion(
			$schema, // array // Main schema array
			// DefinedRegion
				$foo = '', // foo
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
	
				// Properties from DefinedRegion (Thing > Intangible > StructuredValue > DefinedRegion)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from DefinedRegion (Thing > Intangible > StructuredValue > DefinedRegion)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// DeliveryTimeSettings
	include_once __DIR__ . '/StructuredValue/DeliveryTimeSettings.php';

		/*
		 * Thing > Intangible > StructuredValue > DeliveryTimeSettings
		 * 
		 * 
		 */

		function uamswp_fad_schema_deliverytimesettings(
			$schema, // array // Main schema array
			// DeliveryTimeSettings
				$foo = '', // foo
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
	
				// Properties from DeliveryTimeSettings (Thing > Intangible > StructuredValue > DeliveryTimeSettings)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from DeliveryTimeSettings (Thing > Intangible > StructuredValue > DeliveryTimeSettings)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// EngineSpecification
	include_once __DIR__ . '/StructuredValue/EngineSpecification.php';

		/*
		 * Thing > Intangible > StructuredValue > EngineSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_enginespecification(
			$schema, // array // Main schema array
			// EngineSpecification
				$foo = '', // foo
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
	
				// Properties from EngineSpecification (Thing > Intangible > StructuredValue > EngineSpecification)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from EngineSpecification (Thing > Intangible > StructuredValue > EngineSpecification)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// ExchangeRateSpecification
	include_once __DIR__ . '/StructuredValue/ExchangeRateSpecification.php';

		/*
		 * Thing > Intangible > StructuredValue > ExchangeRateSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_exchangeratespecification(
			$schema, // array // Main schema array
			// ExchangeRateSpecification
				$foo = '', // foo
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
	
				// Properties from ExchangeRateSpecification (Thing > Intangible > StructuredValue > ExchangeRateSpecification)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from ExchangeRateSpecification (Thing > Intangible > StructuredValue > ExchangeRateSpecification)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// GeoCoordinates
	include_once __DIR__ . '/StructuredValue/GeoCoordinates.php';

		/*
		 * Thing > Intangible > StructuredValue > GeoCoordinates
		 * 
		 * 
		 */

		function uamswp_fad_schema_geocoordinates(
			$schema, // array // Main schema array
			// GeoCoordinates
				$foo = '', // foo
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
	
				// Properties from GeoCoordinates (Thing > Intangible > StructuredValue > GeoCoordinates)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from GeoCoordinates (Thing > Intangible > StructuredValue > GeoCoordinates)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// GeoShape
	include_once __DIR__ . '/StructuredValue/GeoShape.php';

		/*
		 * Thing > Intangible > StructuredValue > GeoShape
		 * 
		 * 
		 */

		function uamswp_fad_schema_geoshape(
			$schema, // array // Main schema array
			// GeoShape
				$foo = '', // foo
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
	
				// Properties from GeoShape (Thing > Intangible > StructuredValue > GeoShape)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from GeoShape (Thing > Intangible > StructuredValue > GeoShape)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// GeoCircle

			/*
			 * Thing > Intangible > StructuredValue > GeoShape > GeoCircle
			 * 
			 * 
			 */

			function uamswp_fad_schema_geocircle(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// InteractionCounter
	include_once __DIR__ . '/StructuredValue/InteractionCounter.php';

		/*
		 * Thing > Intangible > StructuredValue > InteractionCounter
		 * 
		 * 
		 */

		function uamswp_fad_schema_interactioncounter(
			$schema, // array // Main schema array
			// InteractionCounter
				$foo = '', // foo
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
	
				// Properties from InteractionCounter (Thing > Intangible > StructuredValue > InteractionCounter)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from InteractionCounter (Thing > Intangible > StructuredValue > InteractionCounter)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// MonetaryAmount
	include_once __DIR__ . '/StructuredValue/MonetaryAmount.php';

		/*
		 * Thing > Intangible > StructuredValue > MonetaryAmount
		 * 
		 * 
		 */

		function uamswp_fad_schema_monetaryamount(
			$schema, // array // Main schema array
			// MonetaryAmount
				$foo = '', // foo
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
	
				// Properties from MonetaryAmount (Thing > Intangible > StructuredValue > MonetaryAmount)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from MonetaryAmount (Thing > Intangible > StructuredValue > MonetaryAmount)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// NutritionInformation
	include_once __DIR__ . '/StructuredValue/NutritionInformation.php';

		/*
		 * Thing > Intangible > StructuredValue > NutritionInformation
		 * 
		 * 
		 */

		function uamswp_fad_schema_nutritioninformation(
			$schema, // array // Main schema array
			// NutritionInformation
				$foo = '', // foo
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
	
				// Properties from NutritionInformation (Thing > Intangible > StructuredValue > NutritionInformation)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from NutritionInformation (Thing > Intangible > StructuredValue > NutritionInformation)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// OfferShippingDetails
	include_once __DIR__ . '/StructuredValue/OfferShippingDetails.php';

		/*
		 * Thing > Intangible > StructuredValue > OfferShippingDetails
		 * 
		 * 
		 */

		function uamswp_fad_schema_offershippingdetails(
			$schema, // array // Main schema array
			// OfferShippingDetails
				$foo = '', // foo
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
	
				// Properties from OfferShippingDetails (Thing > Intangible > StructuredValue > OfferShippingDetails)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from OfferShippingDetails (Thing > Intangible > StructuredValue > OfferShippingDetails)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// OpeningHoursSpecification
	include_once __DIR__ . '/StructuredValue/OpeningHoursSpecification.php';

		/*
		 * Thing > Intangible > StructuredValue > OpeningHoursSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_openinghoursspecification(
			$schema, // array // Main schema array
			// OpeningHoursSpecification
				$foo = '', // foo
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
	
				// Properties from OpeningHoursSpecification (Thing > Intangible > StructuredValue > OpeningHoursSpecification)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from OpeningHoursSpecification (Thing > Intangible > StructuredValue > OpeningHoursSpecification)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// OwnershipInfo
	include_once __DIR__ . '/StructuredValue/OwnershipInfo.php';

		/*
		 * Thing > Intangible > StructuredValue > OwnershipInfo
		 * 
		 * 
		 */

		function uamswp_fad_schema_ownershipinfo(
			$schema, // array // Main schema array
			// OwnershipInfo
				$foo = '', // foo
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
	
				// Properties from OwnershipInfo (Thing > Intangible > StructuredValue > OwnershipInfo)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from OwnershipInfo (Thing > Intangible > StructuredValue > OwnershipInfo)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// PostalCodeRangeSpecification
	include_once __DIR__ . '/StructuredValue/PostalCodeRangeSpecification.php';

		/*
		 * Thing > Intangible > StructuredValue > PostalCodeRangeSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_postalcoderangespecification(
			$schema, // array // Main schema array
			// PostalCodeRangeSpecification
				$foo = '', // foo
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
	
				// Properties from PostalCodeRangeSpecification (Thing > Intangible > StructuredValue > PostalCodeRangeSpecification)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from PostalCodeRangeSpecification (Thing > Intangible > StructuredValue > PostalCodeRangeSpecification)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// PriceSpecification
	include_once __DIR__ . '/StructuredValue/PriceSpecification.php';

		/*
		 * Thing > Intangible > StructuredValue > PriceSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_pricespecification(
			$schema, // array // Main schema array
			// PriceSpecification
				$foo = '', // foo
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
	
				// Properties from PriceSpecification (Thing > Intangible > StructuredValue > PriceSpecification)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from PriceSpecification (Thing > Intangible > StructuredValue > PriceSpecification)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// CompoundPriceSpecification

			/*
			 * Thing > Intangible > StructuredValue > PriceSpecification > CompoundPriceSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_compoundpricespecification(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// DeliveryChargeSpecification

			/*
			 * Thing > Intangible > StructuredValue > PriceSpecification > DeliveryChargeSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_deliverychargespecification(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// PaymentChargeSpecification

			/*
			 * Thing > Intangible > StructuredValue > PriceSpecification > PaymentChargeSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_paymentchargespecification(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// UnitPriceSpecification

			/*
			 * Thing > Intangible > StructuredValue > PriceSpecification > UnitPriceSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_unitpricespecification(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// PropertyValue
	include_once __DIR__ . '/StructuredValue/PropertyValue.php';

		/*
		 * Thing > Intangible > StructuredValue > PropertyValue
		 * 
		 * 
		 */

		function uamswp_fad_schema_propertyvalue(
			$schema, // array // Main schema array
			// PropertyValue
				$foo = '', // foo
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
	
				// Properties from PropertyValue (Thing > Intangible > StructuredValue > PropertyValue)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from PropertyValue (Thing > Intangible > StructuredValue > PropertyValue)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// LocationFeatureSpecification

			/*
			 * Thing > Intangible > StructuredValue > PropertyValue > LocationFeatureSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_locationfeaturespecification(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// QuantitativeValue
	include_once __DIR__ . '/StructuredValue/QuantitativeValue.php';

		/*
		 * Thing > Intangible > StructuredValue > QuantitativeValue
		 * 
		 * 
		 */

		function uamswp_fad_schema_quantitativevalue(
			$schema, // array // Main schema array
			// QuantitativeValue
				$foo = '', // foo
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
	
				// Properties from QuantitativeValue (Thing > Intangible > StructuredValue > QuantitativeValue)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from QuantitativeValue (Thing > Intangible > StructuredValue > QuantitativeValue)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// Observation

			/*
			 * Thing > Intangible > StructuredValue > QuantitativeValue > Observation
			 * 
			 * 
			 */

			function uamswp_fad_schema_observation(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// QuantitativeValueDistribution
	include_once __DIR__ . '/StructuredValue/QuantitativeValueDistribution.php';

		/*
		 * Thing > Intangible > StructuredValue > QuantitativeValueDistribution
		 * 
		 * 
		 */

		function uamswp_fad_schema_quantitativevaluedistribution(
			$schema, // array // Main schema array
			// QuantitativeValueDistribution
				$foo = '', // foo
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
	
				// Properties from QuantitativeValueDistribution (Thing > Intangible > StructuredValue > QuantitativeValueDistribution)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from QuantitativeValueDistribution (Thing > Intangible > StructuredValue > QuantitativeValueDistribution)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// MonetaryAmountDistribution

			/*
			 * Thing > Intangible > StructuredValue > QuantitativeValueDistribution > MonetaryAmountDistribution
			 * 
			 * 
			 */

			function uamswp_fad_schema_monetaryamountdistribution(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// RepaymentSpecification
	include_once __DIR__ . '/StructuredValue/RepaymentSpecification.php';

		/*
		 * Thing > Intangible > StructuredValue > RepaymentSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_repaymentspecification(
			$schema, // array // Main schema array
			// RepaymentSpecification
				$foo = '', // foo
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
	
				// Properties from RepaymentSpecification (Thing > Intangible > StructuredValue > RepaymentSpecification)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from RepaymentSpecification (Thing > Intangible > StructuredValue > RepaymentSpecification)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// ShippingDeliveryTime
	include_once __DIR__ . '/StructuredValue/ShippingDeliveryTime.php';

		/*
		 * Thing > Intangible > StructuredValue > ShippingDeliveryTime
		 * 
		 * 
		 */

		function uamswp_fad_schema_shippingdeliverytime(
			$schema, // array // Main schema array
			// ShippingDeliveryTime
				$foo = '', // foo
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
	
				// Properties from ShippingDeliveryTime (Thing > Intangible > StructuredValue > ShippingDeliveryTime)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from ShippingDeliveryTime (Thing > Intangible > StructuredValue > ShippingDeliveryTime)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// ShippingRateSettings
	include_once __DIR__ . '/StructuredValue/ShippingRateSettings.php';

		/*
		 * Thing > Intangible > StructuredValue > ShippingRateSettings
		 * 
		 * 
		 */

		function uamswp_fad_schema_shippingratesettings(
			$schema, // array // Main schema array
			// ShippingRateSettings
				$foo = '', // foo
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
	
				// Properties from ShippingRateSettings (Thing > Intangible > StructuredValue > ShippingRateSettings)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from ShippingRateSettings (Thing > Intangible > StructuredValue > ShippingRateSettings)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// TypeAndQuantityNode
	include_once __DIR__ . '/StructuredValue/TypeAndQuantityNode.php';

		/*
		 * Thing > Intangible > StructuredValue > TypeAndQuantityNode
		 * 
		 * 
		 */

		function uamswp_fad_schema_typeandquantitynode(
			$schema, // array // Main schema array
			// TypeAndQuantityNode
				$foo = '', // foo
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
	
				// Properties from TypeAndQuantityNode (Thing > Intangible > StructuredValue > TypeAndQuantityNode)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from TypeAndQuantityNode (Thing > Intangible > StructuredValue > TypeAndQuantityNode)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

	// WarrantyPromise
	include_once __DIR__ . '/StructuredValue/WarrantyPromise.php';

		/*
		 * Thing > Intangible > StructuredValue > WarrantyPromise
		 * 
		 * 
		 */

		function uamswp_fad_schema_warrantypromise(
			$schema, // array // Main schema array
			// WarrantyPromise
				$foo = '', // foo
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
	
				// Properties from WarrantyPromise (Thing > Intangible > StructuredValue > WarrantyPromise)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from WarrantyPromise (Thing > Intangible > StructuredValue > WarrantyPromise)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = ( isset($foo) && !empty($foo) ) ? uamswp_fad_schema_type_selector($foo) : '';
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

