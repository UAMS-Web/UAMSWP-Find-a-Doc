<?php

// Enumeration

	/*
	 * Thing > Intangible > Enumeration
	 * 
	 * Lists or enumerations — for example, a list of cuisines or music genres, etc.
	 */

	function uamswp_fad_schema_enumeration(
		$schema, // array // Main schema array
		// Enumeration
			$supersededBy = '', // supersededBy
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

			// Properties from Enumeration (Thing > Intangible > Enumeration)

				$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';

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

			// Properties from Enumeration

				// supersededBy

					/* 
					 * Expected Type:
					 *     Thing > Intangible > Class
					 *     Thing > Intangible > Enumeration
					 *     Thing > Intangible > Property
					 * 
					 * Relates a term (i.e., a property, class or enumeration) to one that supersedes 
					 * it.
					 */

					 $schema['supersededBy'] = $supersededBy;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// AdultOrientedEnumeration
	include_once __DIR__ . '/Enumeration/AdultOrientedEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > AdultOrientedEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_adultorientedenumeration(
			$schema, // array // Main schema array
			// AdultOrientedEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from AdultOrientedEnumeration (Thing > Intangible > Enumeration > AdultOrientedEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from AdultOrientedEnumeration (Thing > Intangible > Enumeration > AdultOrientedEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// AlcoholConsideration

			/*
			 * Thing > Intangible > Enumeration > AdultOrientedEnumeration > AlcoholConsideration
			 * 
			 * 
			 */

			function uamswp_fad_schema_alcoholconsideration(
				
			) {
				
			}

		// DangerousGoodConsideration

			/*
			 * Thing > Intangible > Enumeration > AdultOrientedEnumeration > DangerousGoodConsideration
			 * 
			 * 
			 */

			function uamswp_fad_schema_dangerousgoodconsideration(
				
			) {
				
			}

		// HealthcareConsideration

			/*
			 * Thing > Intangible > Enumeration > AdultOrientedEnumeration > HealthcareConsideration
			 * 
			 * 
			 */

			function uamswp_fad_schema_healthcareconsideration(
				
			) {
				
			}

		// NarcoticConsideration

			/*
			 * Thing > Intangible > Enumeration > AdultOrientedEnumeration > NarcoticConsideration
			 * 
			 * 
			 */

			function uamswp_fad_schema_narcoticconsideration(
				
			) {
				
			}

		// ReducedRelevanceForChildrenConsideration

			/*
			 * Thing > Intangible > Enumeration > AdultOrientedEnumeration > ReducedRelevanceForChildrenConsideration
			 * 
			 * 
			 */

			function uamswp_fad_schema_reducedrelevanceforchildrenconsideration(
				
			) {
				
			}

		// SexualContentConsideration

			/*
			 * Thing > Intangible > Enumeration > AdultOrientedEnumeration > SexualContentConsideration
			 * 
			 * 
			 */

			function uamswp_fad_schema_sexualcontentconsideration(
				
			) {
				
			}

		// TobaccoNicotineConsideration

			/*
			 * Thing > Intangible > Enumeration > AdultOrientedEnumeration > TobaccoNicotineConsideration
			 * 
			 * 
			 */

			function uamswp_fad_schema_tobacconicotineconsideration(
				
			) {
				
			}

		// UnclassifiedAdultConsideration

			/*
			 * Thing > Intangible > Enumeration > AdultOrientedEnumeration > UnclassifiedAdultConsideration
			 * 
			 * 
			 */

			function uamswp_fad_schema_unclassifiedadultconsideration(
				
			) {
				
			}

		// ViolenceConsideration

			/*
			 * Thing > Intangible > Enumeration > AdultOrientedEnumeration > ViolenceConsideration
			 * 
			 * 
			 */

			function uamswp_fad_schema_violenceconsideration(
				
			) {
				
			}

		// WeaponConsideration

			/*
			 * Thing > Intangible > Enumeration > AdultOrientedEnumeration > WeaponConsideration
			 * 
			 * 
			 */

			function uamswp_fad_schema_weaponconsideration(
				
			) {
				
			}

	// BoardingPolicyType
	include_once __DIR__ . '/Enumeration/BoardingPolicyType.php';
	
		/*
		 * Thing > Intangible > Enumeration > BoardingPolicyType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_boardingpolicytype(
			$schema, // array // Main schema array
			// BoardingPolicyType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from BoardingPolicyType (Thing > Intangible > Enumeration > BoardingPolicyType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from BoardingPolicyType (Thing > Intangible > Enumeration > BoardingPolicyType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// GroupBoardingPolicy

			/*
			 * Thing > Intangible > Enumeration > BoardingPolicyType > GroupBoardingPolicy
			 * 
			 * 
			 */

			function uamswp_fad_schema_groupboardingpolicy(
				
			) {
				
			}

		// ZoneBoardingPolicy

			/*
			 * Thing > Intangible > Enumeration > BoardingPolicyType > ZoneBoardingPolicy
			 * 
			 * 
			 */

			function uamswp_fad_schema_zoneboardingpolicy(
				
			) {
				
			}

	// BookFormatType
	include_once __DIR__ . '/Enumeration/BookFormatType.php';
	
		/*
		 * Thing > Intangible > Enumeration > BookFormatType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_bookformattype(
			$schema, // array // Main schema array
			// BookFormatType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from BookFormatType (Thing > Intangible > Enumeration > BookFormatType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from BookFormatType (Thing > Intangible > Enumeration > BookFormatType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// AudiobookFormat

			/*
			 * Thing > Intangible > Enumeration > BookFormatType > AudiobookFormat
			 * 
			 * 
			 */

			function uamswp_fad_schema_audiobookformat(
				
			) {
				
			}

		// EBook

			/*
			 * Thing > Intangible > Enumeration > BookFormatType > EBook
			 * 
			 * 
			 */

			function uamswp_fad_schema_ebook(
				
			) {
				
			}

		// GraphicNovel

			/*
			 * Thing > Intangible > Enumeration > BookFormatType > GraphicNovel
			 * 
			 * 
			 */

			function uamswp_fad_schema_graphicnovel(
				
			) {
				
			}

		// Hardcover

			/*
			 * Thing > Intangible > Enumeration > BookFormatType > Hardcover
			 * 
			 * 
			 */

			function uamswp_fad_schema_hardcover(
				
			) {
				
			}

		// Paperback

			/*
			 * Thing > Intangible > Enumeration > BookFormatType > Paperback
			 * 
			 * 
			 */

			function uamswp_fad_schema_paperback(
				
			) {
				
			}

	// BusinessEntityType
	include_once __DIR__ . '/Enumeration/BusinessEntityType.php';
	
		/*
		 * Thing > Intangible > Enumeration > BusinessEntityType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_businessentitytype(
			$schema, // array // Main schema array
			// BusinessEntityType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from BusinessEntityType (Thing > Intangible > Enumeration > BusinessEntityType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from BusinessEntityType (Thing > Intangible > Enumeration > BusinessEntityType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

	// BusinessFunction
	include_once __DIR__ . '/Enumeration/BusinessFunction.php';
	
		/*
		 * Thing > Intangible > Enumeration > BusinessFunction
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_businessfunction(
			$schema, // array // Main schema array
			// BusinessFunction
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from BusinessFunction (Thing > Intangible > Enumeration > BusinessFunction)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from BusinessFunction (Thing > Intangible > Enumeration > BusinessFunction)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

	// CarUsageType
	include_once __DIR__ . '/Enumeration/CarUsageType.php';
	
		/*
		 * Thing > Intangible > Enumeration > CarUsageType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_carusagetype(
			$schema, // array // Main schema array
			// CarUsageType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from CarUsageType (Thing > Intangible > Enumeration > CarUsageType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from CarUsageType (Thing > Intangible > Enumeration > CarUsageType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// DrivingSchoolVehicleUsage

			/*
			 * Thing > Intangible > Enumeration > CarUsageType > DrivingSchoolVehicleUsage
			 * 
			 * 
			 */

			function uamswp_fad_schema_drivingschoolvehicleusage(
				
			) {
				
			}

		// RentalVehicleUsage

			/*
			 * Thing > Intangible > Enumeration > CarUsageType > RentalVehicleUsage
			 * 
			 * 
			 */

			function uamswp_fad_schema_rentalvehicleusage(
				
			) {
				
			}

		// TaxiVehicleUsage

			/*
			 * Thing > Intangible > Enumeration > CarUsageType > TaxiVehicleUsage
			 * 
			 * 
			 */

			function uamswp_fad_schema_taxivehicleusage(
				
			) {
				
			}

	// ContactPointOption
	include_once __DIR__ . '/Enumeration/ContactPointOption.php';
	
		/*
		 * Thing > Intangible > Enumeration > ContactPointOption
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_contactpointoption(
			$schema, // array // Main schema array
			// ContactPointOption
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from ContactPointOption (Thing > Intangible > Enumeration > ContactPointOption)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from ContactPointOption (Thing > Intangible > Enumeration > ContactPointOption)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// HearingImpairedSupported

			/*
			 * Thing > Intangible > Enumeration > ContactPointOption > HearingImpairedSupported
			 * 
			 * 
			 */

			function uamswp_fad_schema_hearingimpairedsupported(
				
			) {
				
			}

		// TollFree

			/*
			 * Thing > Intangible > Enumeration > ContactPointOption > TollFree
			 * 
			 * 
			 */

			function uamswp_fad_schema_tollfree(
				
			) {
				
			}

	// DayOfWeek
	include_once __DIR__ . '/Enumeration/DayOfWeek.php';
	
		/*
		 * Thing > Intangible > Enumeration > DayOfWeek
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_dayofweek(
			$schema, // array // Main schema array
			// DayOfWeek
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from DayOfWeek (Thing > Intangible > Enumeration > DayOfWeek)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from DayOfWeek (Thing > Intangible > Enumeration > DayOfWeek)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// Friday

			/*
			 * Thing > Intangible > Enumeration > DayOfWeek > Friday
			 * 
			 * 
			 */

			function uamswp_fad_schema_friday(
				
			) {
				
			}

		// Monday

			/*
			 * Thing > Intangible > Enumeration > DayOfWeek > Monday
			 * 
			 * 
			 */

			function uamswp_fad_schema_monday(
				
			) {
				
			}

		// PublicHolidays

			/*
			 * Thing > Intangible > Enumeration > DayOfWeek > PublicHolidays
			 * 
			 * 
			 */

			function uamswp_fad_schema_publicholidays(
				
			) {
				
			}

		// Saturday

			/*
			 * Thing > Intangible > Enumeration > DayOfWeek > Saturday
			 * 
			 * 
			 */

			function uamswp_fad_schema_saturday(
				
			) {
				
			}

		// Sunday

			/*
			 * Thing > Intangible > Enumeration > DayOfWeek > Sunday
			 * 
			 * 
			 */

			function uamswp_fad_schema_sunday(
				
			) {
				
			}

		// Thursday

			/*
			 * Thing > Intangible > Enumeration > DayOfWeek > Thursday
			 * 
			 * 
			 */

			function uamswp_fad_schema_thursday(
				
			) {
				
			}

		// Tuesday

			/*
			 * Thing > Intangible > Enumeration > DayOfWeek > Tuesday
			 * 
			 * 
			 */

			function uamswp_fad_schema_tuesday(
				
			) {
				
			}

		// Wednesday

			/*
			 * Thing > Intangible > Enumeration > DayOfWeek > Wednesday
			 * 
			 * 
			 */

			function uamswp_fad_schema_wednesday(
				
			) {
				
			}

	// DeliveryMethod
	include_once __DIR__ . '/Enumeration/DeliveryMethod.php';
	
		/*
		 * Thing > Intangible > Enumeration > DeliveryMethod
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_deliverymethod(
			$schema, // array // Main schema array
			// DeliveryMethod
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from DeliveryMethod (Thing > Intangible > Enumeration > DeliveryMethod)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from DeliveryMethod (Thing > Intangible > Enumeration > DeliveryMethod)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// LockerDelivery

			/*
			 * Thing > Intangible > Enumeration > DeliveryMethod > LockerDelivery
			 * 
			 * 
			 */

			function uamswp_fad_schema_lockerdelivery(
				
			) {
				
			}

		// OnSitePickup

			/*
			 * Thing > Intangible > Enumeration > DeliveryMethod > OnSitePickup
			 * 
			 * 
			 */

			function uamswp_fad_schema_onsitepickup(
				
			) {
				
			}

		// ParcelService

			/*
			 * Thing > Intangible > Enumeration > DeliveryMethod > ParcelService
			 * 
			 * 
			 */

			function uamswp_fad_schema_parcelservice(
				
			) {
				
			}

	// DigitalDocumentPermissionType
	include_once __DIR__ . '/Enumeration/DigitalDocumentPermissionType.php';
	
		/*
		 * Thing > Intangible > Enumeration > DigitalDocumentPermissionType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_digitaldocumentpermissiontype(
			$schema, // array // Main schema array
			// DigitalDocumentPermissionType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from DigitalDocumentPermissionType (Thing > Intangible > Enumeration > DigitalDocumentPermissionType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from DigitalDocumentPermissionType (Thing > Intangible > Enumeration > DigitalDocumentPermissionType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// CommentPermission

			/*
			 * Thing > Intangible > Enumeration > DigitalDocumentPermissionType > CommentPermission
			 * 
			 * 
			 */

			function uamswp_fad_schema_commentpermission(
				
			) {
				
			}

		// ReadPermission

			/*
			 * Thing > Intangible > Enumeration > DigitalDocumentPermissionType > ReadPermission
			 * 
			 * 
			 */

			function uamswp_fad_schema_readpermission(
				
			) {
				
			}

		// WritePermission

			/*
			 * Thing > Intangible > Enumeration > DigitalDocumentPermissionType > WritePermission
			 * 
			 * 
			 */

			function uamswp_fad_schema_writepermission(
				
			) {
				
			}

	// DigitalPlatformEnumeration
	include_once __DIR__ . '/Enumeration/DigitalPlatformEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > DigitalPlatformEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_digitalplatformenumeration(
			$schema, // array // Main schema array
			// DigitalPlatformEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from DigitalPlatformEnumeration (Thing > Intangible > Enumeration > DigitalPlatformEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from DigitalPlatformEnumeration (Thing > Intangible > Enumeration > DigitalPlatformEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// AndroidPlatform

			/*
			 * Thing > Intangible > Enumeration > DigitalPlatformEnumeration > AndroidPlatform
			 * 
			 * 
			 */

			function uamswp_fad_schema_androidplatform(
				
			) {
				
			}

		// DesktopWebPlatform

			/*
			 * Thing > Intangible > Enumeration > DigitalPlatformEnumeration > DesktopWebPlatform
			 * 
			 * 
			 */

			function uamswp_fad_schema_desktopwebplatform(
				
			) {
				
			}

		// GenericWebPlatform

			/*
			 * Thing > Intangible > Enumeration > DigitalPlatformEnumeration > GenericWebPlatform
			 * 
			 * 
			 */

			function uamswp_fad_schema_genericwebplatform(
				
			) {
				
			}

		// IOSPlatform

			/*
			 * Thing > Intangible > Enumeration > DigitalPlatformEnumeration > IOSPlatform
			 * 
			 * 
			 */

			function uamswp_fad_schema_iosplatform(
				
			) {
				
			}

		// MobileWebPlatform

			/*
			 * Thing > Intangible > Enumeration > DigitalPlatformEnumeration > MobileWebPlatform
			 * 
			 * 
			 */

			function uamswp_fad_schema_mobilewebplatform(
				
			) {
				
			}

	// EnergyEfficiencyEnumeration
	include_once __DIR__ . '/Enumeration/EnergyEfficiencyEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > EnergyEfficiencyEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_energyefficiencyenumeration(
			$schema, // array // Main schema array
			// EnergyEfficiencyEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from EnergyEfficiencyEnumeration (Thing > Intangible > Enumeration > EnergyEfficiencyEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from EnergyEfficiencyEnumeration (Thing > Intangible > Enumeration > EnergyEfficiencyEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// EUEnergyEfficiencyEnumeration

			/*
			 * Thing > Intangible > Enumeration > EnergyEfficiencyEnumeration > EUEnergyEfficiencyEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_euenergyefficiencyenumeration(
				
			) {
				
			}

			// EUEnergyEfficiencyCategoryA

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EUEnergyEfficiencyCategoryA
				 * 
				 * 
				 */

				function uamswp_fad_schema_euenergyefficiencycategorya(
					
				) {
					
				}

			// EUEnergyEfficiencyCategoryA1Plus

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EUEnergyEfficiencyCategoryA1Plus
				 * 
				 * 
				 */

				function uamswp_fad_schema_euenergyefficiencycategorya1plus(
					
				) {
					
				}

			// EUEnergyEfficiencyCategoryA2Plus

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EUEnergyEfficiencyCategoryA2Plus
				 * 
				 * 
				 */

				function uamswp_fad_schema_euenergyefficiencycategorya2plus(
					
				) {
					
				}

			// EUEnergyEfficiencyCategoryA3Plus

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EUEnergyEfficiencyCategoryA3Plus
				 * 
				 * 
				 */

				function uamswp_fad_schema_euenergyefficiencycategorya3plus(
					
				) {
					
				}

			// EUEnergyEfficiencyCategoryB

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EUEnergyEfficiencyCategoryB
				 * 
				 * 
				 */

				function uamswp_fad_schema_euenergyefficiencycategoryb(
					
				) {
					
				}

			// EUEnergyEfficiencyCategoryC

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EUEnergyEfficiencyCategoryC
				 * 
				 * 
				 */

				function uamswp_fad_schema_euenergyefficiencycategoryc(
					
				) {
					
				}

			// EUEnergyEfficiencyCategoryD

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EUEnergyEfficiencyCategoryD
				 * 
				 * 
				 */

				function uamswp_fad_schema_euenergyefficiencycategoryd(
					
				) {
					
				}

			// EUEnergyEfficiencyCategoryE

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EUEnergyEfficiencyCategoryE
				 * 
				 * 
				 */

				function uamswp_fad_schema_euenergyefficiencycategorye(
					
				) {
					
				}

			// EUEnergyEfficiencyCategoryF

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EUEnergyEfficiencyCategoryF
				 * 
				 * 
				 */

				function uamswp_fad_schema_euenergyefficiencycategoryf(
					
				) {
					
				}

			// EUEnergyEfficiencyCategoryG

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EUEnergyEfficiencyCategoryG
				 * 
				 * 
				 */

				function uamswp_fad_schema_euenergyefficiencycategoryg(
					
				) {
					
				}

		// EnergyStarEnergyEfficiencyEnumeration

			/*
			 * Thing > Intangible > Enumeration > EnergyEfficiencyEnumeration > EnergyStarEnergyEfficiencyEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_energystarenergyefficiencyenumeration(
				
			) {
				
			}

			// EnergyStarCertified

				/*
				 * Thing > Intangible > Enumeration > EnergyEfficiencyEnumeration > quux > EnergyStarCertified
				 * 
				 * 
				 */

				function uamswp_fad_schema_energystarcertified(
					
				) {
					
				}


	// EventAttendanceModeEnumeration
	include_once __DIR__ . '/Enumeration/EventAttendanceModeEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > EventAttendanceModeEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_eventattendancemodeenumeration(
			$schema, // array // Main schema array
			// EventAttendanceModeEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from EventAttendanceModeEnumeration (Thing > Intangible > Enumeration > EventAttendanceModeEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from EventAttendanceModeEnumeration (Thing > Intangible > Enumeration > EventAttendanceModeEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// MixedEventAttendanceMode

			/*
			 * Thing > Intangible > Enumeration > EventAttendanceModeEnumeration > MixedEventAttendanceMode
			 * 
			 * 
			 */

			function uamswp_fad_schema_mixedeventattendancemode(
				
			) {
				
			}

		// OfflineEventAttendanceMode

			/*
			 * Thing > Intangible > Enumeration > EventAttendanceModeEnumeration > OfflineEventAttendanceMode
			 * 
			 * 
			 */

			function uamswp_fad_schema_offlineeventattendancemode(
				
			) {
				
			}

		// OnlineEventAttendanceMode

			/*
			 * Thing > Intangible > Enumeration > EventAttendanceModeEnumeration > OnlineEventAttendanceMode
			 * 
			 * 
			 */

			function uamswp_fad_schema_onlineeventattendancemode(
				
			) {
				
			}

	// GameAvailabilityEnumeration
	include_once __DIR__ . '/Enumeration/GameAvailabilityEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > GameAvailabilityEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_gameavailabilityenumeration(
			$schema, // array // Main schema array
			// GameAvailabilityEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from GameAvailabilityEnumeration (Thing > Intangible > Enumeration > GameAvailabilityEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from GameAvailabilityEnumeration (Thing > Intangible > Enumeration > GameAvailabilityEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// DemoGameAvailability

			/*
			 * Thing > Intangible > Enumeration > GameAvailabilityEnumeration > DemoGameAvailability
			 * 
			 * 
			 */

			function uamswp_fad_schema_demogameavailability(
				
			) {
				
			}

		// FullGameAvailability

			/*
			 * Thing > Intangible > Enumeration > GameAvailabilityEnumeration > FullGameAvailability
			 * 
			 * 
			 */

			function uamswp_fad_schema_fullgameavailability(
				
			) {
				
			}

	// GamePlayMode
	include_once __DIR__ . '/Enumeration/GamePlayMode.php';
	
		/*
		 * Thing > Intangible > Enumeration > GamePlayMode
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_gameplaymode(
			$schema, // array // Main schema array
			// GamePlayMode
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from GamePlayMode (Thing > Intangible > Enumeration > GamePlayMode)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from GamePlayMode (Thing > Intangible > Enumeration > GamePlayMode)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// CoOp

			/*
			 * Thing > Intangible > Enumeration > GamePlayMode > CoOp
			 * 
			 * 
			 */

			function uamswp_fad_schema_coop(
				
			) {
				
			}

		// MultiPlayer

			/*
			 * Thing > Intangible > Enumeration > GamePlayMode > MultiPlayer
			 * 
			 * 
			 */

			function uamswp_fad_schema_multiplayer(
				
			) {
				
			}

		// SinglePlayer

			/*
			 * Thing > Intangible > Enumeration > GamePlayMode > SinglePlayer
			 * 
			 * 
			 */

			function uamswp_fad_schema_singleplayer(
				
			) {
				
			}

	// GenderType
	include_once __DIR__ . '/Enumeration/GenderType.php';
	
		/*
		 * Thing > Intangible > Enumeration > GenderType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_gendertype(
			$schema, // array // Main schema array
			// GenderType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from GenderType (Thing > Intangible > Enumeration > GenderType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from GenderType (Thing > Intangible > Enumeration > GenderType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// Female

			/*
			 * Thing > Intangible > Enumeration > GenderType > Female
			 * 
			 * 
			 */

			function uamswp_fad_schema_female(
				
			) {
				
			}

		// Male

			/*
			 * Thing > Intangible > Enumeration > GenderType > Male
			 * 
			 * 
			 */

			function uamswp_fad_schema_male(
				
			) {
				
			}

	// GovernmentBenefitsType
	include_once __DIR__ . '/Enumeration/GovernmentBenefitsType.php';
	
		/*
		 * Thing > Intangible > Enumeration > GovernmentBenefitsType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_governmentbenefitstype(
			$schema, // array // Main schema array
			// GovernmentBenefitsType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from GovernmentBenefitsType (Thing > Intangible > Enumeration > GovernmentBenefitsType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from GovernmentBenefitsType (Thing > Intangible > Enumeration > GovernmentBenefitsType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// BasicIncome

			/*
			 * Thing > Intangible > Enumeration > GovernmentBenefitsType > BasicIncome
			 * 
			 * 
			 */

			function uamswp_fad_schema_basicincome(
				
			) {
				
			}

		// BusinessSupport

			/*
			 * Thing > Intangible > Enumeration > GovernmentBenefitsType > BusinessSupport
			 * 
			 * 
			 */

			function uamswp_fad_schema_businesssupport(
				
			) {
				
			}

		// DisabilitySupport

			/*
			 * Thing > Intangible > Enumeration > GovernmentBenefitsType > DisabilitySupport
			 * 
			 * 
			 */

			function uamswp_fad_schema_disabilitysupport(
				
			) {
				
			}

		// HealthCare

			/*
			 * Thing > Intangible > Enumeration > GovernmentBenefitsType > HealthCare
			 * 
			 * 
			 */

			function uamswp_fad_schema_healthcare(
				
			) {
				
			}

		// OneTimePayments

			/*
			 * Thing > Intangible > Enumeration > GovernmentBenefitsType > OneTimePayments
			 * 
			 * 
			 */

			function uamswp_fad_schema_onetimepayments(
				
			) {
				
			}

		// PaidLeave

			/*
			 * Thing > Intangible > Enumeration > GovernmentBenefitsType > PaidLeave
			 * 
			 * 
			 */

			function uamswp_fad_schema_paidleave(
				
			) {
				
			}

		// ParentalSupport

			/*
			 * Thing > Intangible > Enumeration > GovernmentBenefitsType > ParentalSupport
			 * 
			 * 
			 */

			function uamswp_fad_schema_parentalsupport(
				
			) {
				
			}

		// UnemploymentSupport

			/*
			 * Thing > Intangible > Enumeration > GovernmentBenefitsType > UnemploymentSupport
			 * 
			 * 
			 */

			function uamswp_fad_schema_unemploymentsupport(
				
			) {
				
			}

	// HealthAspectEnumeration
	include_once __DIR__ . '/Enumeration/HealthAspectEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > HealthAspectEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_healthaspectenumeration(
			$schema, // array // Main schema array
			// HealthAspectEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from HealthAspectEnumeration (Thing > Intangible > Enumeration > HealthAspectEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from HealthAspectEnumeration (Thing > Intangible > Enumeration > HealthAspectEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// AllergiesHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > AllergiesHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_allergieshealthaspect(
				
			) {
				
			}

		// BenefitsHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > BenefitsHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_benefitshealthaspect(
				
			) {
				
			}

		// CausesHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > CausesHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_causeshealthaspect(
				
			) {
				
			}

		// ContagiousnessHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > ContagiousnessHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_contagiousnesshealthaspect(
				
			) {
				
			}

		// EffectivenessHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > EffectivenessHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_effectivenesshealthaspect(
				
			) {
				
			}

		// GettingAccessHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > GettingAccessHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_gettingaccesshealthaspect(
				
			) {
				
			}

		// HowItWorksHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > HowItWorksHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_howitworkshealthaspect(
				
			) {
				
			}

		// HowOrWhereHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > HowOrWhereHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_howorwherehealthaspect(
				
			) {
				
			}

		// IngredientsHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > IngredientsHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_ingredientshealthaspect(
				
			) {
				
			}

		// LivingWithHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > LivingWithHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_livingwithhealthaspect(
				
			) {
				
			}

		// MayTreatHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > MayTreatHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_maytreathealthaspect(
				
			) {
				
			}

		// MisconceptionsHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > MisconceptionsHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_misconceptionshealthaspect(
				
			) {
				
			}

		// OverviewHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > OverviewHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_overviewhealthaspect(
				
			) {
				
			}

		// PatientExperienceHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > PatientExperienceHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_patientexperiencehealthaspect(
				
			) {
				
			}

		// PregnancyHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > PregnancyHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_pregnancyhealthaspect(
				
			) {
				
			}

		// PreventionHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > PreventionHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_preventionhealthaspect(
				
			) {
				
			}

		// PrognosisHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > PrognosisHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_prognosishealthaspect(
				
			) {
				
			}

		// RelatedTopicsHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > RelatedTopicsHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_relatedtopicshealthaspect(
				
			) {
				
			}

		// RisksOrComplicationsHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > RisksOrComplicationsHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_risksorcomplicationshealthaspect(
				
			) {
				
			}

		// SafetyHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > SafetyHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_safetyhealthaspect(
				
			) {
				
			}

		// ScreeningHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > ScreeningHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_screeninghealthaspect(
				
			) {
				
			}

		// SeeDoctorHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > SeeDoctorHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_seedoctorhealthaspect(
				
			) {
				
			}

		// SelfCareHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > SelfCareHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_selfcarehealthaspect(
				
			) {
				
			}

		// SideEffectsHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > SideEffectsHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_sideeffectshealthaspect(
				
			) {
				
			}

		// StagesHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > StagesHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_stageshealthaspect(
				
			) {
				
			}

		// SymptomsHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > SymptomsHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_symptomshealthaspect(
				
			) {
				
			}

		// TreatmentsHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > TreatmentsHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_treatmentshealthaspect(
				
			) {
				
			}

		// TypesHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > TypesHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_typeshealthaspect(
				
			) {
				
			}

		// UsageOrScheduleHealthAspect

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration > UsageOrScheduleHealthAspect
			 * 
			 * 
			 */

			function uamswp_fad_schema_usageorschedulehealthaspect(
				
			) {
				
			}

	// ItemAvailability
	include_once __DIR__ . '/Enumeration/ItemAvailability.php';
	
		/*
		 * Thing > Intangible > Enumeration > ItemAvailability
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_itemavailability(
			$schema, // array // Main schema array
			// ItemAvailability
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from ItemAvailability (Thing > Intangible > Enumeration > ItemAvailability)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from ItemAvailability (Thing > Intangible > Enumeration > ItemAvailability)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// BackOrder

			/*
			 * Thing > Intangible > Enumeration > ItemAvailability > BackOrder
			 * 
			 * 
			 */

			function uamswp_fad_schema_backorder(
				
			) {
				
			}

		// Discontinued

			/*
			 * Thing > Intangible > Enumeration > ItemAvailability > Discontinued
			 * 
			 * 
			 */

			function uamswp_fad_schema_discontinued(
				
			) {
				
			}

		// InStock

			/*
			 * Thing > Intangible > Enumeration > ItemAvailability > InStock
			 * 
			 * 
			 */

			function uamswp_fad_schema_instock(
				
			) {
				
			}

		// InStoreOnly

			/*
			 * Thing > Intangible > Enumeration > ItemAvailability > InStoreOnly
			 * 
			 * 
			 */

			function uamswp_fad_schema_instoreonly(
				
			) {
				
			}

		// LimitedAvailability

			/*
			 * Thing > Intangible > Enumeration > ItemAvailability > LimitedAvailability
			 * 
			 * 
			 */

			function uamswp_fad_schema_limitedavailability(
				
			) {
				
			}

		// OnlineOnly

			/*
			 * Thing > Intangible > Enumeration > ItemAvailability > OnlineOnly
			 * 
			 * 
			 */

			function uamswp_fad_schema_onlineonly(
				
			) {
				
			}

		// OutOfStock

			/*
			 * Thing > Intangible > Enumeration > ItemAvailability > OutOfStock
			 * 
			 * 
			 */

			function uamswp_fad_schema_outofstock(
				
			) {
				
			}

		// PreOrder

			/*
			 * Thing > Intangible > Enumeration > ItemAvailability > PreOrder
			 * 
			 * 
			 */

			function uamswp_fad_schema_preorder(
				
			) {
				
			}

		// PreSale

			/*
			 * Thing > Intangible > Enumeration > ItemAvailability > PreSale
			 * 
			 * 
			 */

			function uamswp_fad_schema_presale(
				
			) {
				
			}

		// SoldOut

			/*
			 * Thing > Intangible > Enumeration > ItemAvailability > SoldOut
			 * 
			 * 
			 */

			function uamswp_fad_schema_soldout(
				
			) {
				
			}

	// ItemListOrderType
	include_once __DIR__ . '/Enumeration/ItemListOrderType.php';
	
		/*
		 * Thing > Intangible > Enumeration > ItemListOrderType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_itemlistordertype(
			$schema, // array // Main schema array
			// ItemListOrderType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from ItemListOrderType (Thing > Intangible > Enumeration > ItemListOrderType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from ItemListOrderType (Thing > Intangible > Enumeration > ItemListOrderType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// ItemListOrderAscending

			/*
			 * Thing > Intangible > Enumeration > ItemListOrderType > ItemListOrderAscending
			 * 
			 * 
			 */

			function uamswp_fad_schema_itemlistorderascending(
				
			) {
				
			}

		// ItemListOrderDescending

			/*
			 * Thing > Intangible > Enumeration > ItemListOrderType > ItemListOrderDescending
			 * 
			 * 
			 */

			function uamswp_fad_schema_itemlistorderdescending(
				
			) {
				
			}

		// ItemListUnordered

			/*
			 * Thing > Intangible > Enumeration > ItemListOrderType > ItemListUnordered
			 * 
			 * 
			 */

			function uamswp_fad_schema_itemlistunordered(
				
			) {
				
			}

	// LegalValueLevel
	include_once __DIR__ . '/Enumeration/LegalValueLevel.php';
	
		/*
		 * Thing > Intangible > Enumeration > LegalValueLevel
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_legalvaluelevel(
			$schema, // array // Main schema array
			// LegalValueLevel
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from LegalValueLevel (Thing > Intangible > Enumeration > LegalValueLevel)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from LegalValueLevel (Thing > Intangible > Enumeration > LegalValueLevel)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// AuthoritativeLegalValue

			/*
			 * Thing > Intangible > Enumeration > LegalValueLevel > AuthoritativeLegalValue
			 * 
			 * 
			 */

			function uamswp_fad_schema_authoritativelegalvalue(
				
			) {
				
			}

		// DefinitiveLegalValue

			/*
			 * Thing > Intangible > Enumeration > LegalValueLevel > DefinitiveLegalValue
			 * 
			 * 
			 */

			function uamswp_fad_schema_definitivelegalvalue(
				
			) {
				
			}

		// OfficialLegalValue

			/*
			 * Thing > Intangible > Enumeration > LegalValueLevel > OfficialLegalValue
			 * 
			 * 
			 */

			function uamswp_fad_schema_officiallegalvalue(
				
			) {
				
			}

		// UnofficialLegalValue

			/*
			 * Thing > Intangible > Enumeration > LegalValueLevel > UnofficialLegalValue
			 * 
			 * 
			 */

			function uamswp_fad_schema_unofficiallegalvalue(
				
			) {
				
			}

	// MapCategoryType
	include_once __DIR__ . '/Enumeration/MapCategoryType.php';
	
		/*
		 * Thing > Intangible > Enumeration > MapCategoryType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_mapcategorytype(
			$schema, // array // Main schema array
			// MapCategoryType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MapCategoryType (Thing > Intangible > Enumeration > MapCategoryType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from MapCategoryType (Thing > Intangible > Enumeration > MapCategoryType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// ParkingMap

			/*
			 * Thing > Intangible > Enumeration > MapCategoryType > ParkingMap
			 * 
			 * 
			 */

			function uamswp_fad_schema_parkingmap(
				
			) {
				
			}

		// SeatingMap

			/*
			 * Thing > Intangible > Enumeration > MapCategoryType > SeatingMap
			 * 
			 * 
			 */

			function uamswp_fad_schema_seatingmap(
				
			) {
				
			}

		// TransitMap

			/*
			 * Thing > Intangible > Enumeration > MapCategoryType > TransitMap
			 * 
			 * 
			 */

			function uamswp_fad_schema_transitmap(
				
			) {
				
			}

		// VenueMap

			/*
			 * Thing > Intangible > Enumeration > MapCategoryType > VenueMap
			 * 
			 * 
			 */

			function uamswp_fad_schema_venuemap(
				
			) {
				
			}

	// MeasurementMethodEnum
	include_once __DIR__ . '/Enumeration/MeasurementMethodEnum.php';
	
		/*
		 * Thing > Intangible > Enumeration > MeasurementMethodEnum
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_measurementmethodenum(
			$schema, // array // Main schema array
			// MeasurementMethodEnum
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MeasurementMethodEnum (Thing > Intangible > Enumeration > MeasurementMethodEnum)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from MeasurementMethodEnum (Thing > Intangible > Enumeration > MeasurementMethodEnum)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// ExampleMeasurementMethodEnum

			/*
			 * Thing > Intangible > Enumeration > MeasurementMethodEnum > ExampleMeasurementMethodEnum
			 * 
			 * 
			 */

			function uamswp_fad_schema_examplemeasurementmethodenum(
				
			) {
				
			}

	// MeasurementTypeEnumeration
	include_once __DIR__ . '/Enumeration/MeasurementTypeEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > MeasurementTypeEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_measurementtypeenumeration(
			$schema, // array // Main schema array
			// MeasurementTypeEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MeasurementTypeEnumeration (Thing > Intangible > Enumeration > MeasurementTypeEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from MeasurementTypeEnumeration (Thing > Intangible > Enumeration > MeasurementTypeEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// BodyMeasurementTypeEnumeration

			/*
			 * Thing > Intangible > Enumeration > MeasurementTypeEnumeration > BodyMeasurementTypeEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_bodymeasurementtypeenumeration(
				
			) {
				
			}

			// BodyMeasurementArm

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementArm
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementarm(
					
				) {
					
				}

			// BodyMeasurementBust

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementBust
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementbust(
					
				) {
					
				}

			// BodyMeasurementChest

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementChest
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementchest(
					
				) {
					
				}

			// BodyMeasurementFoot

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementFoot
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementfoot(
					
				) {
					
				}

			// BodyMeasurementHand

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementHand
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementhand(
					
				) {
					
				}

			// BodyMeasurementHead

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementHead
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementhead(
					
				) {
					
				}

			// BodyMeasurementHeight

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementHeight
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementheight(
					
				) {
					
				}

			// BodyMeasurementHips

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementHips
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementhips(
					
				) {
					
				}

			// BodyMeasurementInsideLeg

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementInsideLeg
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementinsideleg(
					
				) {
					
				}

			// BodyMeasurementNeck

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementNeck
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementneck(
					
				) {
					
				}

			// BodyMeasurementUnderbust

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementUnderbust
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementunderbust(
					
				) {
					
				}

			// BodyMeasurementWaist

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementWaist
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementwaist(
					
				) {
					
				}

			// BodyMeasurementWeight

				/*
				 * Thing > Intangible > Enumeration > qux > quux > BodyMeasurementWeight
				 * 
				 * 
				 */

				function uamswp_fad_schema_bodymeasurementweight(
					
				) {
					
				}

		// WearableMeasurementTypeEnumeration

			/*
			 * Thing > Intangible > Enumeration > qux > WearableMeasurementTypeEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_wearablemeasurementtypeenumeration(
				
			) {
				
			}

			// WearableMeasurementBack

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableMeasurementBack
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementback(
					
				) {
					
				}

			// WearableMeasurementChestOrBust

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableMeasurementChestOrBust
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementchestorbust(
					
				) {
					
				}

			// WearableMeasurementCollar

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableMeasurementCollar
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementcollar(
					
				) {
					
				}

			// WearableMeasurementCup

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableMeasurementCup
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementcup(
					
				) {
					
				}

			// WearableMeasurementHeight

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableMeasurementHeight
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementheight(
					
				) {
					
				}

			// WearableMeasurementHips

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableMeasurementHips
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementhips(
					
				) {
					
				}

			// WearableMeasurementInseam

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableMeasurementInseam
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementinseam(
					
				) {
					
				}

			// WearableMeasurementLength

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableMeasurementLength
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementlength(
					
				) {
					
				}

			// WearableMeasurementOutsideLeg

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableMeasurementOutsideLeg
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementoutsideleg(
					
				) {
					
				}

			// WearableMeasurementSleeve

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableMeasurementSleeve
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementsleeve(
					
				) {
					
				}

			// WearableMeasurementWaist

				/*
				 * Thing > Intangible > Enumeration > MeasurementTypeEnumeration > quux > WearableMeasurementWaist
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementwaist(
					
				) {
					
				}

			// WearableMeasurementWidth

				/*
				 * Thing > Intangible > Enumeration > MeasurementTypeEnumeration > quux > WearableMeasurementWidth
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablemeasurementwidth(
					
				) {
					
				}


	// MediaManipulationRatingEnumeration
	include_once __DIR__ . '/Enumeration/MediaManipulationRatingEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > MediaManipulationRatingEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_mediamanipulationratingenumeration(
			$schema, // array // Main schema array
			// MediaManipulationRatingEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MediaManipulationRatingEnumeration (Thing > Intangible > Enumeration > MediaManipulationRatingEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from MediaManipulationRatingEnumeration (Thing > Intangible > Enumeration > MediaManipulationRatingEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// DecontextualizedContent

			/*
			 * Thing > Intangible > Enumeration > MediaManipulationRatingEnumeration > DecontextualizedContent
			 * 
			 * 
			 */

			function uamswp_fad_schema_decontextualizedcontent(
				
			) {
				
			}

		// EditedOrCroppedContent

			/*
			 * Thing > Intangible > Enumeration > MediaManipulationRatingEnumeration > EditedOrCroppedContent
			 * 
			 * 
			 */

			function uamswp_fad_schema_editedorcroppedcontent(
				
			) {
				
			}

		// OriginalMediaContent

			/*
			 * Thing > Intangible > Enumeration > MediaManipulationRatingEnumeration > OriginalMediaContent
			 * 
			 * 
			 */

			function uamswp_fad_schema_originalmediacontent(
				
			) {
				
			}

		// SatireOrParodyContent

			/*
			 * Thing > Intangible > Enumeration > MediaManipulationRatingEnumeration > SatireOrParodyContent
			 * 
			 * 
			 */

			function uamswp_fad_schema_satireorparodycontent(
				
			) {
				
			}

		// StagedContent

			/*
			 * Thing > Intangible > Enumeration > MediaManipulationRatingEnumeration > StagedContent
			 * 
			 * 
			 */

			function uamswp_fad_schema_stagedcontent(
				
			) {
				
			}

		// TransformedContent

			/*
			 * Thing > Intangible > Enumeration > MediaManipulationRatingEnumeration > TransformedContent
			 * 
			 * 
			 */

			function uamswp_fad_schema_transformedcontent(
				
			) {
				
			}

	// MedicalEnumeration
	include_once __DIR__ . '/Enumeration/MedicalEnumeration.php';

	// MerchantReturnEnumeration
	include_once __DIR__ . '/Enumeration/MerchantReturnEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > MerchantReturnEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_merchantreturnenumeration(
			$schema, // array // Main schema array
			// MerchantReturnEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MerchantReturnEnumeration (Thing > Intangible > Enumeration > MerchantReturnEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from MerchantReturnEnumeration (Thing > Intangible > Enumeration > MerchantReturnEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// MerchantReturnFiniteReturnWindow

			/*
			 * Thing > Intangible > Enumeration > MerchantReturnEnumeration > MerchantReturnFiniteReturnWindow
			 * 
			 * 
			 */

			function uamswp_fad_schema_merchantreturnfinitereturnwindow(
				
			) {
				
			}

		// MerchantReturnNotPermitted

			/*
			 * Thing > Intangible > Enumeration > MerchantReturnEnumeration > MerchantReturnNotPermitted
			 * 
			 * 
			 */

			function uamswp_fad_schema_merchantreturnnotpermitted(
				
			) {
				
			}

		// MerchantReturnUnlimitedWindow

			/*
			 * Thing > Intangible > Enumeration > MerchantReturnEnumeration > MerchantReturnUnlimitedWindow
			 * 
			 * 
			 */

			function uamswp_fad_schema_merchantreturnunlimitedwindow(
				
			) {
				
			}

		// MerchantReturnUnspecified

			/*
			 * Thing > Intangible > Enumeration > MerchantReturnEnumeration > MerchantReturnUnspecified
			 * 
			 * 
			 */

			function uamswp_fad_schema_merchantreturnunspecified(
				
			) {
				
			}

	// MusicAlbumProductionType
	include_once __DIR__ . '/Enumeration/MusicAlbumProductionType.php';
	
		/*
		 * Thing > Intangible > Enumeration > MusicAlbumProductionType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_musicalbumproductiontype(
			$schema, // array // Main schema array
			// MusicAlbumProductionType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MusicAlbumProductionType (Thing > Intangible > Enumeration > MusicAlbumProductionType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from MusicAlbumProductionType (Thing > Intangible > Enumeration > MusicAlbumProductionType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// CompilationAlbum

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumProductionType > CompilationAlbum
			 * 
			 * 
			 */

			function uamswp_fad_schema_compilationalbum(
				
			) {
				
			}

		// DJMixAlbum

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumProductionType > DJMixAlbum
			 * 
			 * 
			 */

			function uamswp_fad_schema_djmixalbum(
				
			) {
				
			}

		// DemoAlbum

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumProductionType > DemoAlbum
			 * 
			 * 
			 */

			function uamswp_fad_schema_demoalbum(
				
			) {
				
			}

		// LiveAlbum

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumProductionType > LiveAlbum
			 * 
			 * 
			 */

			function uamswp_fad_schema_livealbum(
				
			) {
				
			}

		// MixtapeAlbum

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumProductionType > MixtapeAlbum
			 * 
			 * 
			 */

			function uamswp_fad_schema_mixtapealbum(
				
			) {
				
			}

		// RemixAlbum

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumProductionType > RemixAlbum
			 * 
			 * 
			 */

			function uamswp_fad_schema_remixalbum(
				
			) {
				
			}

		// SoundtrackAlbum

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumProductionType > SoundtrackAlbum
			 * 
			 * 
			 */

			function uamswp_fad_schema_soundtrackalbum(
				
			) {
				
			}

		// SpokenWordAlbum

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumProductionType > SpokenWordAlbum
			 * 
			 * 
			 */

			function uamswp_fad_schema_spokenwordalbum(
				
			) {
				
			}

		// StudioAlbum

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumProductionType > StudioAlbum
			 * 
			 * 
			 */

			function uamswp_fad_schema_studioalbum(
				
			) {
				
			}

	// MusicAlbumReleaseType
	include_once __DIR__ . '/Enumeration/MusicAlbumReleaseType.php';
	
		/*
		 * Thing > Intangible > Enumeration > MusicAlbumReleaseType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_musicalbumreleasetype(
			$schema, // array // Main schema array
			// MusicAlbumReleaseType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MusicAlbumReleaseType (Thing > Intangible > Enumeration > MusicAlbumReleaseType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from MusicAlbumReleaseType (Thing > Intangible > Enumeration > MusicAlbumReleaseType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// AlbumRelease

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumReleaseType > AlbumRelease
			 * 
			 * 
			 */

			function uamswp_fad_schema_albumrelease(
				
			) {
				
			}

		// BroadcastRelease

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumReleaseType > BroadcastRelease
			 * 
			 * 
			 */

			function uamswp_fad_schema_broadcastrelease(
				
			) {
				
			}

		// EPRelease

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumReleaseType > EPRelease
			 * 
			 * 
			 */

			function uamswp_fad_schema_eprelease(
				
			) {
				
			}

		// SingleRelease

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumReleaseType > SingleRelease
			 * 
			 * 
			 */

			function uamswp_fad_schema_singlerelease(
				
			) {
				
			}

	// MusicReleaseFormatType
	include_once __DIR__ . '/Enumeration/MusicReleaseFormatType.php';
	
		/*
		 * Thing > Intangible > Enumeration > MusicReleaseFormatType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_musicreleaseformattype(
			$schema, // array // Main schema array
			// MusicReleaseFormatType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MusicReleaseFormatType (Thing > Intangible > Enumeration > MusicReleaseFormatType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from MusicReleaseFormatType (Thing > Intangible > Enumeration > MusicReleaseFormatType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// CDFormat

			/*
			 * Thing > Intangible > Enumeration > MusicReleaseFormatType > CDFormat
			 * 
			 * 
			 */

			function uamswp_fad_schema_cdformat(
				
			) {
				
			}

		// CassetteFormat

			/*
			 * Thing > Intangible > Enumeration > MusicReleaseFormatType > CassetteFormat
			 * 
			 * 
			 */

			function uamswp_fad_schema_cassetteformat(
				
			) {
				
			}

		// DVDFormat

			/*
			 * Thing > Intangible > Enumeration > MusicReleaseFormatType > DVDFormat
			 * 
			 * 
			 */

			function uamswp_fad_schema_dvdformat(
				
			) {
				
			}

		// DigitalAudioTapeFormat

			/*
			 * Thing > Intangible > Enumeration > MusicReleaseFormatType > DigitalAudioTapeFormat
			 * 
			 * 
			 */

			function uamswp_fad_schema_digitalaudiotapeformat(
				
			) {
				
			}

		// DigitalFormat

			/*
			 * Thing > Intangible > Enumeration > MusicReleaseFormatType > DigitalFormat
			 * 
			 * 
			 */

			function uamswp_fad_schema_digitalformat(
				
			) {
				
			}

		// LaserDiscFormat

			/*
			 * Thing > Intangible > Enumeration > MusicReleaseFormatType > LaserDiscFormat
			 * 
			 * 
			 */

			function uamswp_fad_schema_laserdiscformat(
				
			) {
				
			}

		// VinylFormat

			/*
			 * Thing > Intangible > Enumeration > MusicReleaseFormatType > VinylFormat
			 * 
			 * 
			 */

			function uamswp_fad_schema_vinylformat(
				
			) {
				
			}

	// NonprofitType
	include_once __DIR__ . '/Enumeration/NonprofitType.php';
	
		/*
		 * Thing > Intangible > Enumeration > NonprofitType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_nonprofittype(
			$schema, // array // Main schema array
			// NonprofitType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from NonprofitType (Thing > Intangible > Enumeration > NonprofitType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from NonprofitType (Thing > Intangible > Enumeration > NonprofitType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// NLNonprofitType

			/*
			 * Thing > Intangible > Enumeration > NonprofitType > NLNonprofitType
			 * 
			 * 
			 */

			function uamswp_fad_schema_nlnonprofittype(
				
			) {
				
			}

			// NonprofitANBI

				/*
				 * Thing > Intangible > Enumeration > qux > quux > NonprofitANBI
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofitanbi(
					
				) {
					
				}

			// NonprofitSBBI

				/*
				 * Thing > Intangible > Enumeration > qux > quux > NonprofitSBBI
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofitsbbi(
					
				) {
					
				}

		// UKNonprofitType

			/*
			 * Thing > Intangible > Enumeration > qux > UKNonprofitType
			 * 
			 * 
			 */

			function uamswp_fad_schema_uknonprofittype(
				
			) {
				
			}

			// CharitableIncorporatedOrganization

				/*
				 * Thing > Intangible > Enumeration > qux > quux > CharitableIncorporatedOrganization
				 * 
				 * 
				 */

				function uamswp_fad_schema_charitableincorporatedorganization(
					
				) {
					
				}

			// LimitedByGuaranteeCharity

				/*
				 * Thing > Intangible > Enumeration > qux > quux > LimitedByGuaranteeCharity
				 * 
				 * 
				 */

				function uamswp_fad_schema_limitedbyguaranteecharity(
					
				) {
					
				}

			// UKTrust

				/*
				 * Thing > Intangible > Enumeration > qux > quux > UKTrust
				 * 
				 * 
				 */

				function uamswp_fad_schema_uktrust(
					
				) {
					
				}

			// UnincorporatedAssociationCharity

				/*
				 * Thing > Intangible > Enumeration > qux > quux > UnincorporatedAssociationCharity
				 * 
				 * 
				 */

				function uamswp_fad_schema_unincorporatedassociationcharity(
					
				) {
					
				}

		// USNonprofitType

			/*
			 * Thing > Intangible > Enumeration > qux > USNonprofitType
			 * 
			 * 
			 */

			function uamswp_fad_schema_usnonprofittype(
				
			) {
				
			}

			// Nonprofit501a

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501a
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501a(
					
				) {
					
				}

			// Nonprofit501c1

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c1
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c1(
					
				) {
					
				}

			// Nonprofit501c10

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c10
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c10(
					
				) {
					
				}

			// Nonprofit501c11

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c11
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c11(
					
				) {
					
				}

			// Nonprofit501c12

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c12
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c12(
					
				) {
					
				}

			// Nonprofit501c13

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c13
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c13(
					
				) {
					
				}

			// Nonprofit501c14

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c14
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c14(
					
				) {
					
				}

			// Nonprofit501c15

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c15
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c15(
					
				) {
					
				}

			// Nonprofit501c16

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c16
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c16(
					
				) {
					
				}

			// Nonprofit501c17

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c17
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c17(
					
				) {
					
				}

			// Nonprofit501c18

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c18
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c18(
					
				) {
					
				}

			// Nonprofit501c19

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c19
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c19(
					
				) {
					
				}

			// Nonprofit501c2

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c2
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c2(
					
				) {
					
				}

			// Nonprofit501c20

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c20
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c20(
					
				) {
					
				}

			// Nonprofit501c21

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c21
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c21(
					
				) {
					
				}

			// Nonprofit501c22

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c22
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c22(
					
				) {
					
				}

			// Nonprofit501c23

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c23
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c23(
					
				) {
					
				}

			// Nonprofit501c24

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c24
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c24(
					
				) {
					
				}

			// Nonprofit501c25

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c25
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c25(
					
				) {
					
				}

			// Nonprofit501c26

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c26
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c26(
					
				) {
					
				}

			// Nonprofit501c27

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c27
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c27(
					
				) {
					
				}

			// Nonprofit501c28

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c28
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c28(
					
				) {
					
				}

			// Nonprofit501c3

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c3
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c3(
					
				) {
					
				}

			// Nonprofit501c4

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c4
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c4(
					
				) {
					
				}

			// Nonprofit501c5

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c5
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c5(
					
				) {
					
				}

			// Nonprofit501c6

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c6
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c6(
					
				) {
					
				}

			// Nonprofit501c7

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c7
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c7(
					
				) {
					
				}

			// Nonprofit501c8

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c8
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c8(
					
				) {
					
				}

			// Nonprofit501c9

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501c9
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501c9(
					
				) {
					
				}

			// Nonprofit501d

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501d
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501d(
					
				) {
					
				}

			// Nonprofit501e

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501e
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501e(
					
				) {
					
				}

			// Nonprofit501f

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501f
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501f(
					
				) {
					
				}

			// Nonprofit501k

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501k
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501k(
					
				) {
					
				}

			// Nonprofit501n

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501n
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501n(
					
				) {
					
				}

			// Nonprofit501q

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Nonprofit501q
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit501q(
					
				) {
					
				}

			// Nonprofit527

				/*
				 * Thing > Intangible > Enumeration > NonprofitType > quux > Nonprofit527
				 * 
				 * 
				 */

				function uamswp_fad_schema_nonprofit527(
					
				) {
					
				}


	// OfferItemCondition
	include_once __DIR__ . '/Enumeration/OfferItemCondition.php';
	
		/*
		 * Thing > Intangible > Enumeration > OfferItemCondition
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_offeritemcondition(
			$schema, // array // Main schema array
			// OfferItemCondition
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from OfferItemCondition (Thing > Intangible > Enumeration > OfferItemCondition)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from OfferItemCondition (Thing > Intangible > Enumeration > OfferItemCondition)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// DamagedCondition

			/*
			 * Thing > Intangible > Enumeration > OfferItemCondition > DamagedCondition
			 * 
			 * 
			 */

			function uamswp_fad_schema_damagedcondition(
				
			) {
				
			}

		// NewCondition

			/*
			 * Thing > Intangible > Enumeration > OfferItemCondition > NewCondition
			 * 
			 * 
			 */

			function uamswp_fad_schema_newcondition(
				
			) {
				
			}

		// RefurbishedCondition

			/*
			 * Thing > Intangible > Enumeration > OfferItemCondition > RefurbishedCondition
			 * 
			 * 
			 */

			function uamswp_fad_schema_refurbishedcondition(
				
			) {
				
			}

		// UsedCondition

			/*
			 * Thing > Intangible > Enumeration > OfferItemCondition > UsedCondition
			 * 
			 * 
			 */

			function uamswp_fad_schema_usedcondition(
				
			) {
				
			}

	// PaymentMethod
	include_once __DIR__ . '/Enumeration/PaymentMethod.php';
	
		/*
		 * Thing > Intangible > Enumeration > PaymentMethod
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_paymentmethod(
			$schema, // array // Main schema array
			// PaymentMethod
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from PaymentMethod (Thing > Intangible > Enumeration > PaymentMethod)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from PaymentMethod (Thing > Intangible > Enumeration > PaymentMethod)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// PaymentCard

			/*
			 * Thing > Intangible > Enumeration > PaymentMethod > PaymentCard
			 * 
			 * 
			 */

			function uamswp_fad_schema_paymentcard(
				
			) {
				
			}

			// CreditCard

				/*
				 * Thing > Intangible > Enumeration > PaymentMethod > quux > CreditCard
				 * 
				 * 
				 */

				function uamswp_fad_schema_creditcard(
					
				) {
					
				}


	// PhysicalActivityCategory
	include_once __DIR__ . '/Enumeration/PhysicalActivityCategory.php';
	
		/*
		 * Thing > Intangible > Enumeration > PhysicalActivityCategory
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_physicalactivitycategory(
			$schema, // array // Main schema array
			// PhysicalActivityCategory
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from PhysicalActivityCategory (Thing > Intangible > Enumeration > PhysicalActivityCategory)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from PhysicalActivityCategory (Thing > Intangible > Enumeration > PhysicalActivityCategory)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// AerobicActivity

			/*
			 * Thing > Intangible > Enumeration > PhysicalActivityCategory > AerobicActivity
			 * 
			 * 
			 */

			function uamswp_fad_schema_aerobicactivity(
				
			) {
				
			}

		// AnaerobicActivity

			/*
			 * Thing > Intangible > Enumeration > PhysicalActivityCategory > AnaerobicActivity
			 * 
			 * 
			 */

			function uamswp_fad_schema_anaerobicactivity(
				
			) {
				
			}

		// Balance

			/*
			 * Thing > Intangible > Enumeration > PhysicalActivityCategory > Balance
			 * 
			 * 
			 */

			function uamswp_fad_schema_balance(
				
			) {
				
			}

		// Flexibility

			/*
			 * Thing > Intangible > Enumeration > PhysicalActivityCategory > Flexibility
			 * 
			 * 
			 */

			function uamswp_fad_schema_flexibility(
				
			) {
				
			}

		// LeisureTimeActivity

			/*
			 * Thing > Intangible > Enumeration > PhysicalActivityCategory > LeisureTimeActivity
			 * 
			 * 
			 */

			function uamswp_fad_schema_leisuretimeactivity(
				
			) {
				
			}

		// OccupationalActivity

			/*
			 * Thing > Intangible > Enumeration > PhysicalActivityCategory > OccupationalActivity
			 * 
			 * 
			 */

			function uamswp_fad_schema_occupationalactivity(
				
			) {
				
			}

		// StrengthTraining

			/*
			 * Thing > Intangible > Enumeration > PhysicalActivityCategory > StrengthTraining
			 * 
			 * 
			 */

			function uamswp_fad_schema_strengthtraining(
				
			) {
				
			}

	// PriceComponentTypeEnumeration
	include_once __DIR__ . '/Enumeration/PriceComponentTypeEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > PriceComponentTypeEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_pricecomponenttypeenumeration(
			$schema, // array // Main schema array
			// PriceComponentTypeEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from PriceComponentTypeEnumeration (Thing > Intangible > Enumeration > PriceComponentTypeEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from PriceComponentTypeEnumeration (Thing > Intangible > Enumeration > PriceComponentTypeEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// ActivationFee

			/*
			 * Thing > Intangible > Enumeration > PriceComponentTypeEnumeration > ActivationFee
			 * 
			 * 
			 */

			function uamswp_fad_schema_activationfee(
				
			) {
				
			}

		// CleaningFee

			/*
			 * Thing > Intangible > Enumeration > PriceComponentTypeEnumeration > CleaningFee
			 * 
			 * 
			 */

			function uamswp_fad_schema_cleaningfee(
				
			) {
				
			}

		// DistanceFee

			/*
			 * Thing > Intangible > Enumeration > PriceComponentTypeEnumeration > DistanceFee
			 * 
			 * 
			 */

			function uamswp_fad_schema_distancefee(
				
			) {
				
			}

		// Downpayment

			/*
			 * Thing > Intangible > Enumeration > PriceComponentTypeEnumeration > Downpayment
			 * 
			 * 
			 */

			function uamswp_fad_schema_downpayment(
				
			) {
				
			}

		// Installment

			/*
			 * Thing > Intangible > Enumeration > PriceComponentTypeEnumeration > Installment
			 * 
			 * 
			 */

			function uamswp_fad_schema_installment(
				
			) {
				
			}

		// Subscription

			/*
			 * Thing > Intangible > Enumeration > PriceComponentTypeEnumeration > Subscription
			 * 
			 * 
			 */

			function uamswp_fad_schema_subscription(
				
			) {
				
			}

	// PriceTypeEnumeration
	include_once __DIR__ . '/Enumeration/PriceTypeEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > PriceTypeEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_pricetypeenumeration(
			$schema, // array // Main schema array
			// PriceTypeEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from PriceTypeEnumeration (Thing > Intangible > Enumeration > PriceTypeEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from PriceTypeEnumeration (Thing > Intangible > Enumeration > PriceTypeEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// InvoicePrice

			/*
			 * Thing > Intangible > Enumeration > PriceTypeEnumeration > InvoicePrice
			 * 
			 * 
			 */

			function uamswp_fad_schema_invoiceprice(
				
			) {
				
			}

		// ListPrice

			/*
			 * Thing > Intangible > Enumeration > PriceTypeEnumeration > ListPrice
			 * 
			 * 
			 */

			function uamswp_fad_schema_listprice(
				
			) {
				
			}

		// MSRP

			/*
			 * Thing > Intangible > Enumeration > PriceTypeEnumeration > MSRP
			 * 
			 * 
			 */

			function uamswp_fad_schema_msrp(
				
			) {
				
			}

		// MinimumAdvertisedPrice

			/*
			 * Thing > Intangible > Enumeration > PriceTypeEnumeration > MinimumAdvertisedPrice
			 * 
			 * 
			 */

			function uamswp_fad_schema_minimumadvertisedprice(
				
			) {
				
			}

		// SRP

			/*
			 * Thing > Intangible > Enumeration > PriceTypeEnumeration > SRP
			 * 
			 * 
			 */

			function uamswp_fad_schema_srp(
				
			) {
				
			}

		// SalePrice

			/*
			 * Thing > Intangible > Enumeration > PriceTypeEnumeration > SalePrice
			 * 
			 * 
			 */

			function uamswp_fad_schema_saleprice(
				
			) {
				
			}

	// QualitativeValue
	include_once __DIR__ . '/Enumeration/QualitativeValue.php';
	
		/*
		 * Thing > Intangible > Enumeration > QualitativeValue
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_qualitativevalue(
			$schema, // array // Main schema array
			// QualitativeValue
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from QualitativeValue (Thing > Intangible > Enumeration > QualitativeValue)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from QualitativeValue (Thing > Intangible > Enumeration > QualitativeValue)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// BedType

			/*
			 * Thing > Intangible > Enumeration > QualitativeValue > BedType
			 * 
			 * 
			 */

			function uamswp_fad_schema_bedtype(
				
			) {
				
			}

		// DriveWheelConfigurationValue

			/*
			 * Thing > Intangible > Enumeration > QualitativeValue > DriveWheelConfigurationValue
			 * 
			 * 
			 */

			function uamswp_fad_schema_drivewheelconfigurationvalue(
				
			) {
				
			}

			// AllWheelDriveConfiguration

				/*
				 * Thing > Intangible > Enumeration > qux > quux > AllWheelDriveConfiguration
				 * 
				 * 
				 */

				function uamswp_fad_schema_allwheeldriveconfiguration(
					
				) {
					
				}

			// FourWheelDriveConfiguration

				/*
				 * Thing > Intangible > Enumeration > qux > quux > FourWheelDriveConfiguration
				 * 
				 * 
				 */

				function uamswp_fad_schema_fourwheeldriveconfiguration(
					
				) {
					
				}

			// FrontWheelDriveConfiguration

				/*
				 * Thing > Intangible > Enumeration > qux > quux > FrontWheelDriveConfiguration
				 * 
				 * 
				 */

				function uamswp_fad_schema_frontwheeldriveconfiguration(
					
				) {
					
				}

			// RearWheelDriveConfiguration

				/*
				 * Thing > Intangible > Enumeration > qux > quux > RearWheelDriveConfiguration
				 * 
				 * 
				 */

				function uamswp_fad_schema_rearwheeldriveconfiguration(
					
				) {
					
				}

		// SizeSpecification

			/*
			 * Thing > Intangible > Enumeration > qux > SizeSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_sizespecification(
				
			) {
				
			}

		// SteeringPositionValue

			/*
			 * Thing > Intangible > Enumeration > qux > SteeringPositionValue
			 * 
			 * 
			 */

			function uamswp_fad_schema_steeringpositionvalue(
				
			) {
				
			}

			// LeftHandDriving

				/*
				 * Thing > Intangible > Enumeration > qux > quux > LeftHandDriving
				 * 
				 * 
				 */

				function uamswp_fad_schema_lefthanddriving(
					
				) {
					
				}

			// RightHandDriving

				/*
				 * Thing > Intangible > Enumeration > QualitativeValue > quux > RightHandDriving
				 * 
				 * 
				 */

				function uamswp_fad_schema_righthanddriving(
					
				) {
					
				}


	// RefundTypeEnumeration
	include_once __DIR__ . '/Enumeration/RefundTypeEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > RefundTypeEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_refundtypeenumeration(
			$schema, // array // Main schema array
			// RefundTypeEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from RefundTypeEnumeration (Thing > Intangible > Enumeration > RefundTypeEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from RefundTypeEnumeration (Thing > Intangible > Enumeration > RefundTypeEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// ExchangeRefund

			/*
			 * Thing > Intangible > Enumeration > RefundTypeEnumeration > ExchangeRefund
			 * 
			 * 
			 */

			function uamswp_fad_schema_exchangerefund(
				
			) {
				
			}

		// FullRefund

			/*
			 * Thing > Intangible > Enumeration > RefundTypeEnumeration > FullRefund
			 * 
			 * 
			 */

			function uamswp_fad_schema_fullrefund(
				
			) {
				
			}

		// StoreCreditRefund

			/*
			 * Thing > Intangible > Enumeration > RefundTypeEnumeration > StoreCreditRefund
			 * 
			 * 
			 */

			function uamswp_fad_schema_storecreditrefund(
				
			) {
				
			}

	// RestrictedDiet
	include_once __DIR__ . '/Enumeration/RestrictedDiet.php';
	
		/*
		 * Thing > Intangible > Enumeration > RestrictedDiet
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_restricteddiet(
			$schema, // array // Main schema array
			// RestrictedDiet
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from RestrictedDiet (Thing > Intangible > Enumeration > RestrictedDiet)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from RestrictedDiet (Thing > Intangible > Enumeration > RestrictedDiet)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// DiabeticDiet

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet > DiabeticDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_diabeticdiet(
				
			) {
				
			}

		// GlutenFreeDiet

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet > GlutenFreeDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_glutenfreediet(
				
			) {
				
			}

		// HalalDiet

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet > HalalDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_halaldiet(
				
			) {
				
			}

		// HinduDiet

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet > HinduDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_hindudiet(
				
			) {
				
			}

		// KosherDiet

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet > KosherDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_kosherdiet(
				
			) {
				
			}

		// LowCalorieDiet

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet > LowCalorieDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_lowcaloriediet(
				
			) {
				
			}

		// LowFatDiet

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet > LowFatDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_lowfatdiet(
				
			) {
				
			}

		// LowLactoseDiet

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet > LowLactoseDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_lowlactosediet(
				
			) {
				
			}

		// LowSaltDiet

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet > LowSaltDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_lowsaltdiet(
				
			) {
				
			}

		// VeganDiet

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet > VeganDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_vegandiet(
				
			) {
				
			}

		// VegetarianDiet

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet > VegetarianDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_vegetariandiet(
				
			) {
				
			}

	// ReturnFeesEnumeration
	include_once __DIR__ . '/Enumeration/ReturnFeesEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > ReturnFeesEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_returnfeesenumeration(
			$schema, // array // Main schema array
			// ReturnFeesEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from ReturnFeesEnumeration (Thing > Intangible > Enumeration > ReturnFeesEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from ReturnFeesEnumeration (Thing > Intangible > Enumeration > ReturnFeesEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// FreeReturn

			/*
			 * Thing > Intangible > Enumeration > ReturnFeesEnumeration > FreeReturn
			 * 
			 * 
			 */

			function uamswp_fad_schema_freereturn(
				
			) {
				
			}

		// OriginalShippingFees

			/*
			 * Thing > Intangible > Enumeration > ReturnFeesEnumeration > OriginalShippingFees
			 * 
			 * 
			 */

			function uamswp_fad_schema_originalshippingfees(
				
			) {
				
			}

		// RestockingFees

			/*
			 * Thing > Intangible > Enumeration > ReturnFeesEnumeration > RestockingFees
			 * 
			 * 
			 */

			function uamswp_fad_schema_restockingfees(
				
			) {
				
			}

		// ReturnFeesCustomerResponsibility

			/*
			 * Thing > Intangible > Enumeration > ReturnFeesEnumeration > ReturnFeesCustomerResponsibility
			 * 
			 * 
			 */

			function uamswp_fad_schema_returnfeescustomerresponsibility(
				
			) {
				
			}

		// ReturnShippingFees

			/*
			 * Thing > Intangible > Enumeration > ReturnFeesEnumeration > ReturnShippingFees
			 * 
			 * 
			 */

			function uamswp_fad_schema_returnshippingfees(
				
			) {
				
			}

	// ReturnLabelSourceEnumeration
	include_once __DIR__ . '/Enumeration/ReturnLabelSourceEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > ReturnLabelSourceEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_returnlabelsourceenumeration(
			$schema, // array // Main schema array
			// ReturnLabelSourceEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from ReturnLabelSourceEnumeration (Thing > Intangible > Enumeration > ReturnLabelSourceEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from ReturnLabelSourceEnumeration (Thing > Intangible > Enumeration > ReturnLabelSourceEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// ReturnLabelCustomerResponsibility

			/*
			 * Thing > Intangible > Enumeration > ReturnLabelSourceEnumeration > ReturnLabelCustomerResponsibility
			 * 
			 * 
			 */

			function uamswp_fad_schema_returnlabelcustomerresponsibility(
				
			) {
				
			}

		// ReturnLabelDownloadAndPrint

			/*
			 * Thing > Intangible > Enumeration > ReturnLabelSourceEnumeration > ReturnLabelDownloadAndPrint
			 * 
			 * 
			 */

			function uamswp_fad_schema_returnlabeldownloadandprint(
				
			) {
				
			}

		// ReturnLabelInBox

			/*
			 * Thing > Intangible > Enumeration > ReturnLabelSourceEnumeration > ReturnLabelInBox
			 * 
			 * 
			 */

			function uamswp_fad_schema_returnlabelinbox(
				
			) {
				
			}

	// ReturnMethodEnumeration
	include_once __DIR__ . '/Enumeration/ReturnMethodEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > ReturnMethodEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_returnmethodenumeration(
			$schema, // array // Main schema array
			// ReturnMethodEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from ReturnMethodEnumeration (Thing > Intangible > Enumeration > ReturnMethodEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from ReturnMethodEnumeration (Thing > Intangible > Enumeration > ReturnMethodEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// ReturnAtKiosk

			/*
			 * Thing > Intangible > Enumeration > ReturnMethodEnumeration > ReturnAtKiosk
			 * 
			 * 
			 */

			function uamswp_fad_schema_returnatkiosk(
				
			) {
				
			}

		// ReturnByMail

			/*
			 * Thing > Intangible > Enumeration > ReturnMethodEnumeration > ReturnByMail
			 * 
			 * 
			 */

			function uamswp_fad_schema_returnbymail(
				
			) {
				
			}

		// ReturnInStore

			/*
			 * Thing > Intangible > Enumeration > ReturnMethodEnumeration > ReturnInStore
			 * 
			 * 
			 */

			function uamswp_fad_schema_returninstore(
				
			) {
				
			}

	// RsvpResponseType
	include_once __DIR__ . '/Enumeration/RsvpResponseType.php';
	
		/*
		 * Thing > Intangible > Enumeration > RsvpResponseType
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_rsvpresponsetype(
			$schema, // array // Main schema array
			// RsvpResponseType
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from RsvpResponseType (Thing > Intangible > Enumeration > RsvpResponseType)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from RsvpResponseType (Thing > Intangible > Enumeration > RsvpResponseType)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// RsvpResponseMaybe

			/*
			 * Thing > Intangible > Enumeration > RsvpResponseType > RsvpResponseMaybe
			 * 
			 * 
			 */

			function uamswp_fad_schema_rsvpresponsemaybe(
				
			) {
				
			}

		// RsvpResponseNo

			/*
			 * Thing > Intangible > Enumeration > RsvpResponseType > RsvpResponseNo
			 * 
			 * 
			 */

			function uamswp_fad_schema_rsvpresponseno(
				
			) {
				
			}

		// RsvpResponseYes

			/*
			 * Thing > Intangible > Enumeration > RsvpResponseType > RsvpResponseYes
			 * 
			 * 
			 */

			function uamswp_fad_schema_rsvpresponseyes(
				
			) {
				
			}

	// SizeGroupEnumeration
	include_once __DIR__ . '/Enumeration/SizeGroupEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > SizeGroupEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_sizegroupenumeration(
			$schema, // array // Main schema array
			// SizeGroupEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from SizeGroupEnumeration (Thing > Intangible > Enumeration > SizeGroupEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from SizeGroupEnumeration (Thing > Intangible > Enumeration > SizeGroupEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// WearableSizeGroupEnumeration

			/*
			 * Thing > Intangible > Enumeration > SizeGroupEnumeration > WearableSizeGroupEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_wearablesizegroupenumeration(
				
			) {
				
			}

			// WearableSizeGroupBig

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupBig
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupbig(
					
				) {
					
				}

			// WearableSizeGroupBoys

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupBoys
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupboys(
					
				) {
					
				}

			// WearableSizeGroupExtraShort

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupExtraShort
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupextrashort(
					
				) {
					
				}

			// WearableSizeGroupExtraTall

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupExtraTall
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupextratall(
					
				) {
					
				}

			// WearableSizeGroupGirls

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupGirls
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupgirls(
					
				) {
					
				}

			// WearableSizeGroupHusky

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupHusky
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegrouphusky(
					
				) {
					
				}

			// WearableSizeGroupInfants

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupInfants
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupinfants(
					
				) {
					
				}

			// WearableSizeGroupJuniors

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupJuniors
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupjuniors(
					
				) {
					
				}

			// WearableSizeGroupMaternity

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupMaternity
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupmaternity(
					
				) {
					
				}

			// WearableSizeGroupMens

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupMens
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupmens(
					
				) {
					
				}

			// WearableSizeGroupMisses

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupMisses
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupmisses(
					
				) {
					
				}

			// WearableSizeGroupPetite

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupPetite
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegrouppetite(
					
				) {
					
				}

			// WearableSizeGroupPlus

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupPlus
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupplus(
					
				) {
					
				}

			// WearableSizeGroupRegular

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupRegular
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupregular(
					
				) {
					
				}

			// WearableSizeGroupShort

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupShort
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupshort(
					
				) {
					
				}

			// WearableSizeGroupTall

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeGroupTall
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegrouptall(
					
				) {
					
				}

			// WearableSizeGroupWomens

				/*
				 * Thing > Intangible > Enumeration > SizeGroupEnumeration > quux > WearableSizeGroupWomens
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizegroupwomens(
					
				) {
					
				}


	// SizeSystemEnumeration
	include_once __DIR__ . '/Enumeration/SizeSystemEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > SizeSystemEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_sizesystemenumeration(
			$schema, // array // Main schema array
			// SizeSystemEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from SizeSystemEnumeration (Thing > Intangible > Enumeration > SizeSystemEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from SizeSystemEnumeration (Thing > Intangible > Enumeration > SizeSystemEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// SizeSystemImperial

			/*
			 * Thing > Intangible > Enumeration > SizeSystemEnumeration > SizeSystemImperial
			 * 
			 * 
			 */

			function uamswp_fad_schema_sizesystemimperial(
				
			) {
				
			}

		// SizeSystemMetric

			/*
			 * Thing > Intangible > Enumeration > SizeSystemEnumeration > SizeSystemMetric
			 * 
			 * 
			 */

			function uamswp_fad_schema_sizesystemmetric(
				
			) {
				
			}

		// WearableSizeSystemEnumeration

			/*
			 * Thing > Intangible > Enumeration > SizeSystemEnumeration > WearableSizeSystemEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_wearablesizesystemenumeration(
				
			) {
				
			}

			// WearableSizeSystemAU

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemAU
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemau(
					
				) {
					
				}

			// WearableSizeSystemBR

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemBR
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystembr(
					
				) {
					
				}

			// WearableSizeSystemCN

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemCN
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemcn(
					
				) {
					
				}

			// WearableSizeSystemContinental

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemContinental
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemcontinental(
					
				) {
					
				}

			// WearableSizeSystemDE

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemDE
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemde(
					
				) {
					
				}

			// WearableSizeSystemEN13402

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemEN13402
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemen13402(
					
				) {
					
				}

			// WearableSizeSystemEurope

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemEurope
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemeurope(
					
				) {
					
				}

			// WearableSizeSystemFR

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemFR
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemfr(
					
				) {
					
				}

			// WearableSizeSystemGS1

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemGS1
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemgs1(
					
				) {
					
				}

			// WearableSizeSystemIT

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemIT
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemit(
					
				) {
					
				}

			// WearableSizeSystemJP

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemJP
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemjp(
					
				) {
					
				}

			// WearableSizeSystemMX

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemMX
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemmx(
					
				) {
					
				}

			// WearableSizeSystemUK

				/*
				 * Thing > Intangible > Enumeration > qux > quux > WearableSizeSystemUK
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemuk(
					
				) {
					
				}

			// WearableSizeSystemUS

				/*
				 * Thing > Intangible > Enumeration > SizeSystemEnumeration > quux > WearableSizeSystemUS
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearablesizesystemus(
					
				) {
					
				}


	// Specialty
	include_once __DIR__ . '/Enumeration/Specialty.php';

		/*
		 * Thing > Intangible > Enumeration > Specialty
		 * 
		 * Any branch of a field in which people typically develop specific expertise, 
		 * usually after significant study, time, and effort.
		 */

		function uamswp_fad_schema_specialty(
			
		) {
			
		}

		// MedicalSpecialty

			/*
			 * Thing > Intangible > Enumeration > Specialty > MedicalSpecialty
			 * 
			 * See: Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty
			 */

	// StatusEnumeration
	include_once __DIR__ . '/Enumeration/StatusEnumeration.php';
	
		/*
		 * Thing > Intangible > Enumeration > StatusEnumeration
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_statusenumeration(
			$schema, // array // Main schema array
			// StatusEnumeration
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from StatusEnumeration (Thing > Intangible > Enumeration > StatusEnumeration)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from StatusEnumeration (Thing > Intangible > Enumeration > StatusEnumeration)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// ActionStatusType

			/*
			 * Thing > Intangible > Enumeration > StatusEnumeration > ActionStatusType
			 * 
			 * 
			 */

			function uamswp_fad_schema_actionstatustype(
				
			) {
				
			}

			// ActiveActionStatus

				/*
				 * Thing > Intangible > Enumeration > qux > quux > ActiveActionStatus
				 * 
				 * 
				 */

				function uamswp_fad_schema_activeactionstatus(
					
				) {
					
				}

			// CompletedActionStatus

				/*
				 * Thing > Intangible > Enumeration > qux > quux > CompletedActionStatus
				 * 
				 * 
				 */

				function uamswp_fad_schema_completedactionstatus(
					
				) {
					
				}

			// FailedActionStatus

				/*
				 * Thing > Intangible > Enumeration > qux > quux > FailedActionStatus
				 * 
				 * 
				 */

				function uamswp_fad_schema_failedactionstatus(
					
				) {
					
				}

			// PotentialActionStatus

				/*
				 * Thing > Intangible > Enumeration > qux > quux > PotentialActionStatus
				 * 
				 * 
				 */

				function uamswp_fad_schema_potentialactionstatus(
					
				) {
					
				}

		// EventStatusType

			/*
			 * Thing > Intangible > Enumeration > qux > EventStatusType
			 * 
			 * 
			 */

			function uamswp_fad_schema_eventstatustype(
				
			) {
				
			}

			// EventCancelled

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EventCancelled
				 * 
				 * 
				 */

				function uamswp_fad_schema_eventcancelled(
					
				) {
					
				}

			// EventMovedOnline

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EventMovedOnline
				 * 
				 * 
				 */

				function uamswp_fad_schema_eventmovedonline(
					
				) {
					
				}

			// EventPostponed

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EventPostponed
				 * 
				 * 
				 */

				function uamswp_fad_schema_eventpostponed(
					
				) {
					
				}

			// EventRescheduled

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EventRescheduled
				 * 
				 * 
				 */

				function uamswp_fad_schema_eventrescheduled(
					
				) {
					
				}

			// EventScheduled

				/*
				 * Thing > Intangible > Enumeration > qux > quux > EventScheduled
				 * 
				 * 
				 */

				function uamswp_fad_schema_eventscheduled(
					
				) {
					
				}

		// GameServerStatus

			/*
			 * Thing > Intangible > Enumeration > qux > GameServerStatus
			 * 
			 * 
			 */

			function uamswp_fad_schema_gameserverstatus(
				
			) {
				
			}

			// OfflinePermanently

				/*
				 * Thing > Intangible > Enumeration > qux > quux > OfflinePermanently
				 * 
				 * 
				 */

				function uamswp_fad_schema_offlinepermanently(
					
				) {
					
				}

			// OfflineTemporarily

				/*
				 * Thing > Intangible > Enumeration > qux > quux > OfflineTemporarily
				 * 
				 * 
				 */

				function uamswp_fad_schema_offlinetemporarily(
					
				) {
					
				}

			// Online

				/*
				 * Thing > Intangible > Enumeration > qux > quux > Online
				 * 
				 * 
				 */

				function uamswp_fad_schema_online(
					
				) {
					
				}

			// OnlineFull

				/*
				 * Thing > Intangible > Enumeration > qux > quux > OnlineFull
				 * 
				 * 
				 */

				function uamswp_fad_schema_onlinefull(
					
				) {
					
				}

		// LegalForceStatus

			/*
			 * Thing > Intangible > Enumeration > qux > LegalForceStatus
			 * 
			 * 
			 */

			function uamswp_fad_schema_legalforcestatus(
				
			) {
				
			}

			// InForce

				/*
				 * Thing > Intangible > Enumeration > qux > quux > InForce
				 * 
				 * 
				 */

				function uamswp_fad_schema_inforce(
					
				) {
					
				}

			// NotInForce

				/*
				 * Thing > Intangible > Enumeration > qux > quux > NotInForce
				 * 
				 * 
				 */

				function uamswp_fad_schema_notinforce(
					
				) {
					
				}

			// PartiallyInForce

				/*
				 * Thing > Intangible > Enumeration > qux > quux > PartiallyInForce
				 * 
				 * 
				 */

				function uamswp_fad_schema_partiallyinforce(
					
				) {
					
				}

		// OrderStatus

			/*
			 * Thing > Intangible > Enumeration > qux > OrderStatus
			 * 
			 * 
			 */

			function uamswp_fad_schema_orderstatus(
				
			) {
				
			}

			// OrderCancelled

				/*
				 * Thing > Intangible > Enumeration > qux > quux > OrderCancelled
				 * 
				 * 
				 */

				function uamswp_fad_schema_ordercancelled(
					
				) {
					
				}

			// OrderDelivered

				/*
				 * Thing > Intangible > Enumeration > qux > quux > OrderDelivered
				 * 
				 * 
				 */

				function uamswp_fad_schema_orderdelivered(
					
				) {
					
				}

			// OrderInTransit

				/*
				 * Thing > Intangible > Enumeration > qux > quux > OrderInTransit
				 * 
				 * 
				 */

				function uamswp_fad_schema_orderintransit(
					
				) {
					
				}

			// OrderPaymentDue

				/*
				 * Thing > Intangible > Enumeration > qux > quux > OrderPaymentDue
				 * 
				 * 
				 */

				function uamswp_fad_schema_orderpaymentdue(
					
				) {
					
				}

			// OrderPickupAvailable

				/*
				 * Thing > Intangible > Enumeration > qux > quux > OrderPickupAvailable
				 * 
				 * 
				 */

				function uamswp_fad_schema_orderpickupavailable(
					
				) {
					
				}

			// OrderProblem

				/*
				 * Thing > Intangible > Enumeration > qux > quux > OrderProblem
				 * 
				 * 
				 */

				function uamswp_fad_schema_orderproblem(
					
				) {
					
				}

			// OrderProcessing

				/*
				 * Thing > Intangible > Enumeration > qux > quux > OrderProcessing
				 * 
				 * 
				 */

				function uamswp_fad_schema_orderprocessing(
					
				) {
					
				}

			// OrderReturned

				/*
				 * Thing > Intangible > Enumeration > qux > quux > OrderReturned
				 * 
				 * 
				 */

				function uamswp_fad_schema_orderreturned(
					
				) {
					
				}

		// PaymentStatusType

			/*
			 * Thing > Intangible > Enumeration > qux > PaymentStatusType
			 * 
			 * 
			 */

			function uamswp_fad_schema_paymentstatustype(
				
			) {
				
			}

			// PaymentAutomaticallyApplied

				/*
				 * Thing > Intangible > Enumeration > qux > quux > PaymentAutomaticallyApplied
				 * 
				 * 
				 */

				function uamswp_fad_schema_paymentautomaticallyapplied(
					
				) {
					
				}

			// PaymentComplete

				/*
				 * Thing > Intangible > Enumeration > qux > quux > PaymentComplete
				 * 
				 * 
				 */

				function uamswp_fad_schema_paymentcomplete(
					
				) {
					
				}

			// PaymentDeclined

				/*
				 * Thing > Intangible > Enumeration > qux > quux > PaymentDeclined
				 * 
				 * 
				 */

				function uamswp_fad_schema_paymentdeclined(
					
				) {
					
				}

			// PaymentDue

				/*
				 * Thing > Intangible > Enumeration > qux > quux > PaymentDue
				 * 
				 * 
				 */

				function uamswp_fad_schema_paymentdue(
					
				) {
					
				}

			// PaymentPastDue

				/*
				 * Thing > Intangible > Enumeration > qux > quux > PaymentPastDue
				 * 
				 * 
				 */

				function uamswp_fad_schema_paymentpastdue(
					
				) {
					
				}

		// ReservationStatusType

			/*
			 * Thing > Intangible > Enumeration > qux > ReservationStatusType
			 * 
			 * 
			 */

			function uamswp_fad_schema_reservationstatustype(
				
			) {
				
			}

			// ReservationCancelled

				/*
				 * Thing > Intangible > Enumeration > qux > quux > ReservationCancelled
				 * 
				 * 
				 */

				function uamswp_fad_schema_reservationcancelled(
					
				) {
					
				}

			// ReservationConfirmed

				/*
				 * Thing > Intangible > Enumeration > qux > quux > ReservationConfirmed
				 * 
				 * 
				 */

				function uamswp_fad_schema_reservationconfirmed(
					
				) {
					
				}

			// ReservationHold

				/*
				 * Thing > Intangible > Enumeration > qux > quux > ReservationHold
				 * 
				 * 
				 */

				function uamswp_fad_schema_reservationhold(
					
				) {
					
				}

			// ReservationPending

				/*
				 * Thing > Intangible > Enumeration > StatusEnumeration > quux > ReservationPending
				 * 
				 * 
				 */

				function uamswp_fad_schema_reservationpending(
					
				) {
					
				}


	// WarrantyScope
	include_once __DIR__ . '/Enumeration/WarrantyScope.php';
	
		/*
		 * Thing > Intangible > Enumeration > WarrantyScope
		 * 
		 * 
		 */
	
		function uamswp_fad_schema_warrantyscope(
			$schema, // array // Main schema array
			// WarrantyScope
				$foo, // foo
			// Enumeration
				$supersededBy, // supersededBy
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
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from WarrantyScope (Thing > Intangible > Enumeration > WarrantyScope)
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_enumeration(
						$schema, // array // Main schema array
						// Enumeration
							$supersededBy, // supersededBy
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
	
				// Properties from WarrantyScope (Thing > Intangible > Enumeration > WarrantyScope)
	
					// foo
	
						/* 
						 * Expected Type:
						 *     bar
						 * 
						 * 
						 */
	
						 $schema['foo'] = $foo;
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}