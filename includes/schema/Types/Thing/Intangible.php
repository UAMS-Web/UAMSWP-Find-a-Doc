<?php

// Intangible

	/*
	 * Thing > Intangible
	 * 
	 * A utility class that serves as the umbrella for a number of 'intangible' things 
	 * such as quantities, structured values, etc.
	 */

	function uamswp_fad_schema_intangible(
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
					'Thing'
				);

			// Properties from this type (and not from the parent[s] of this type)

				/*

					The type name and capitalization must match what is found on Schema.org.

					Find the expected types of their values on Schema.org page for this type
					(e.g., https://schema.org/Thing).

				*/

				$type_properties = array();

		// Construct schema array

			$schema = uamswp_fad_schema_construct_array(
				$schema, // Main schema array
				$input, // Array of properties and values for the type and its parent types
				$type_properties, // Array of properties available to the type
				$type_parent // Array of the immediate parent(s) of this type
			);

		return $schema;

	}

	// ActionAccessSpecification
	include_once __DIR__ . '/Intangible/ActionAccessSpecification.php';

		/*
		 * Thing > Intangible > ActionAccessSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_actionaccessspecification(
			$schema, // array // Main schema array
			// ActionAccessSpecification
				$foo = '', // foo
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
	
				// Properties from ActionAccessSpecification
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from ActionAccessSpecification
	
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

	// AlignmentObject
	include_once __DIR__ . '/Intangible/AlignmentObject.php';

		/*
		 * Thing > Intangible > AlignmentObject
		 * 
		 * 
		 */

		function uamswp_fad_schema_alignmentobject(
			$schema, // array // Main schema array
			// AlignmentObject
				$foo = '', // foo
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
	
				// Properties from AlignmentObject
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from AlignmentObject
	
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

	// Audience
	include_once __DIR__ . '/Intangible/Audience.php';

		/*
		 * Thing > Intangible > Audience
		 * 
		 * Intended audience for an item (i.e., the group for whom the item was created).
		 */

		function uamswp_fad_schema_audience(
			$schema, // array // Main schema array
			// Audience
				$audienceType = '', // audienceType
				$geographicArea = '', // geographicArea
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

				// Properties from Audience (Thing > Intangible > Audience)

					$audienceType = ( isset($audienceType) && !empty($audienceType) ) ? $audienceType : '';
					$geographicArea = ( isset($geographicArea) && !empty($geographicArea) ) ? $geographicArea : '';

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

				// Properties from Audience

					// audienceType

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * The target group associated with a given audience (e.g., veterans, car owners, 
						 * musicians).
						 */

						$schema['audienceType'] = ( isset($audienceType) && !empty($audienceType) ) ? uamswp_fad_schema_type_selector($audienceType) : '';

					// geographicArea

						/* 
						 * Expected Type:
						 *     Thing > Place > AdministrativeArea
						 * 
						 * The geographic area associated with the audience.
						 */

						$schema['geographicArea'] = ( isset($geographicArea) && !empty($geographicArea) ) ? uamswp_fad_schema_type_selector($geographicArea) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

		// BusinessAudience

			/*
			 * Thing > Intangible > Audience > BusinessAudience
			 * 
			 * 
			 */

			function uamswp_fad_schema_businessaudience(
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

		// EducationalAudience

			/*
			 * Thing > Intangible > Audience > EducationalAudience
			 * 
			 * 
			 */

			function uamswp_fad_schema_educationalaudience(
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

		// MedicalAudience

			/*
			 * Thing > Intangible > Audience > MedicalAudience
			 * 
			 *     Also: Thing > Intangible > Audience > PeopleAudience > MedicalAudience
			 * 
			 * Target audiences for medical web pages.
			 */

			function uamswp_fad_schema_medicalaudience(
				$schema, // array // Main schema array
				// MedicalAudience (no property vars)
				// PeopleAudience
					$healthCondition = '', // healthCondition
					$requiredGender = '', // requiredGender
					$requiredMaxAge = '', // requiredMaxAge
					$requiredMinAge = '', // requiredMinAge
					$suggestedAge = '', // suggestedAge
					$suggestedGender = '', // suggestedGender
					$suggestedMaxAge = '', // suggestedMaxAge
					$suggestedMeasurement = '', // suggestedMeasurement
					$suggestedMinAge = '', // suggestedMinAge
				// Audience
					$audienceType = '', // audienceType
					$geographicArea = '', // geographicArea
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

					// Inherited properties from Audience (Thing > Intangible > Audience)

						$audienceType = ( isset($audienceType) && !empty($audienceType) ) ? $audienceType : '';
						$geographicArea = ( isset($geographicArea) && !empty($geographicArea) ) ? $geographicArea : '';

					// Inherited properties from PeopleAudience (Thing > Intangible > Audience > PeopleAudience)

						$healthCondition = ( isset($healthCondition) && !empty($healthCondition) ) ? $healthCondition : '';
						$requiredGender = ( isset($requiredGender) && !empty($requiredGender) ) ? $requiredGender : '';
						$requiredMaxAge = ( isset($requiredMaxAge) && !empty($requiredMaxAge) ) ? $requiredMaxAge : '';
						$requiredMinAge = ( isset($requiredMinAge) && !empty($requiredMinAge) ) ? $requiredMinAge : '';
						$suggestedAge = ( isset($suggestedAge) && !empty($suggestedAge) ) ? $suggestedAge : '';
						$suggestedGender = ( isset($suggestedGender) && !empty($suggestedGender) ) ? $suggestedGender : '';
						$suggestedMaxAge = ( isset($suggestedMaxAge) && !empty($suggestedMaxAge) ) ? $suggestedMaxAge : '';
						$suggestedMeasurement = ( isset($suggestedMeasurement) && !empty($suggestedMeasurement) ) ? $suggestedMeasurement : '';
						$suggestedMinAge = ( isset($suggestedMinAge) && !empty($suggestedMinAge) ) ? $suggestedMinAge : '';

					// Properties from MedicalAudience (Thing > Intangible > Audience > MedicalAudience)
					// Properties from MedicalAudience (Thing > Intangible > Audience > PeopleAudience > MedicalAudience)

						// Do nothing (no property vars)

				// Add values to the schema array

					// Inherited properties

						$schema = uamswp_fad_schema_peopleaudience(
							$schema, // array // Main schema array
							// PeopleAudience
								$healthCondition, // healthCondition
								$requiredGender, // requiredGender
								$requiredMaxAge, // requiredMaxAge
								$requiredMinAge, // requiredMinAge
								$suggestedAge, // suggestedAge
								$suggestedGender, // suggestedGender
								$suggestedMaxAge, // suggestedMaxAge
								$suggestedMeasurement, // suggestedMeasurement
								$suggestedMinAge, // suggestedMinAge
							// Audience
								$audienceType, // audienceType
								$geographicArea, // geographicArea
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

					// Properties from MedicalAudience (Thing > Intangible > Audience > MedicalAudience)
					// Properties from MedicalAudience (Thing > Intangible > Audience > PeopleAudience > MedicalAudience)

						// Do nothing (no property vars)

				// Remove any empty values from the schema array

					$schema = array_filter($schema);
					$schema = array_unique($schema, SORT_REGULAR);

				return $schema;

			}

			// Patient

				/*
				 * Thing > Intangible > Audience > MedicalAudience > Patient
				 * 
				 *     Also: Thing > Person > Patient
				 *     Also: Thing > Intangible > Audience > PeopleAudience > MedicalAudience > Patient
				 * 
				 * A patient is any person recipient of health care services.
				 */

				function uamswp_fad_schema_patient(
					$schema, // array // Main schema array
					// Patient
						$diagnosis = '', // diagnosis
						$drug = '', // drug
						$healthCondition = '', // healthCondition
					// MedicalAudience (no property vars)
					// PeopleAudience
						$healthCondition = '', // healthCondition
						$requiredGender = '', // requiredGender
						$requiredMaxAge = '', // requiredMaxAge
						$requiredMinAge = '', // requiredMinAge
						$suggestedAge = '', // suggestedAge
						$suggestedGender = '', // suggestedGender
						$suggestedMaxAge = '', // suggestedMaxAge
						$suggestedMeasurement = '', // suggestedMeasurement
						$suggestedMinAge = '', // suggestedMinAge
					// Audience
						$audienceType = '', // audienceType
						$geographicArea = '', // geographicArea
					// Person
						$additionalName = '', // additionalName
						$address = '', // address
						$affiliation = '', // affiliation
						$alumniOf = '', // alumniOf
						$award = '', // award
						$birthDate = '', // birthDate
						$birthPlace = '', // birthPlace
						$brand = '', // brand
						$callSign = '', // callSign
						$children = '', // children
						$colleague = '', // colleague
						$contactPoint = '', // contactPoint
						$deathDate = '', // deathDate
						$deathPlace = '', // deathPlace
						$duns = '', // duns
						$email = '', // email
						$familyName = '', // familyName
						$faxNumber = '', // faxNumber
						$follows = '', // follows
						$funder = '', // funder
						$funding = '', // funding
						$gender = '', // gender
						$givenName = '', // givenName
						$globalLocationNumber = '', // globalLocationNumber
						$hasCredential = '', // hasCredential
						$hasOccupation = '', // hasOccupation
						$hasOfferCatalog = '', // hasOfferCatalog
						$hasPOS = '', // hasPOS
						$height = '', // height
						$homeLocation = '', // homeLocation
						$honorificPrefix = '', // honorificPrefix
						$honorificSuffix = '', // honorificSuffix
						$interactionStatistic = '', // interactionStatistic
						$isicV4 = '', // isicV4
						$jobTitle = '', // jobTitle
						$knows = '', // knows
						$knowsAbout = '', // knowsAbout
						$knowsLanguage = '', // knowsLanguage
						$makesOffer = '', // makesOffer
						$memberOf = '', // memberOf
						$naics = '', // naics
						$nationality = '', // nationality
						$netWorth = '', // netWorth
						$owns = '', // owns
						$parent = '', // parent
						$performerIn = '', // performerIn
						$publishingPrinciples = '', // publishingPrinciples
						$relatedTo = '', // relatedTo
						$seeks = '', // seeks
						$sibling = '', // sibling
						$sponsor = '', // sponsor
						$spouse = '', // spouse
						$taxID = '', // taxID
						$telephone = '', // telephone
						$vatID = '', // vatID
						$weight = '', // weight
						$workLocation = '', // workLocation
						$worksFor = '', // worksFor
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

						// Inherited properties from Person (Thing > Person)

							$additionalName = ( isset($additionalName) && !empty($additionalName) ) ? $additionalName : '';
							$address = ( isset($address) && !empty($address) ) ? $address : '';
							$affiliation = ( isset($affiliation) && !empty($affiliation) ) ? $affiliation : '';
							$alumniOf = ( isset($alumniOf) && !empty($alumniOf) ) ? $alumniOf : '';
							$award = ( isset($award) && !empty($award) ) ? $award : '';
							$birthDate = ( isset($birthDate) && !empty($birthDate) ) ? $birthDate : '';
							$birthPlace = ( isset($birthPlace) && !empty($birthPlace) ) ? $birthPlace : '';
							$brand = ( isset($brand) && !empty($brand) ) ? $brand : '';
							$callSign = ( isset($callSign) && !empty($callSign) ) ? $callSign : '';
							$children = ( isset($children) && !empty($children) ) ? $children : '';
							$colleague = ( isset($colleague) && !empty($colleague) ) ? $colleague : '';
							$contactPoint = ( isset($contactPoint) && !empty($contactPoint) ) ? $contactPoint : '';
							$deathDate = ( isset($deathDate) && !empty($deathDate) ) ? $deathDate : '';
							$deathPlace = ( isset($deathPlace) && !empty($deathPlace) ) ? $deathPlace : '';
							$duns = ( isset($duns) && !empty($duns) ) ? $duns : '';
							$email = ( isset($email) && !empty($email) ) ? $email : '';
							$familyName = ( isset($familyName) && !empty($familyName) ) ? $familyName : '';
							$faxNumber = ( isset($faxNumber) && !empty($faxNumber) ) ? $faxNumber : '';
							$follows = ( isset($follows) && !empty($follows) ) ? $follows : '';
							$funder = ( isset($funder) && !empty($funder) ) ? $funder : '';
							$funding = ( isset($funding) && !empty($funding) ) ? $funding : '';
							$gender = ( isset($gender) && !empty($gender) ) ? $gender : '';
							$givenName = ( isset($givenName) && !empty($givenName) ) ? $givenName : '';
							$globalLocationNumber = ( isset($globalLocationNumber) && !empty($globalLocationNumber) ) ? $globalLocationNumber : '';
							$hasCredential = ( isset($hasCredential) && !empty($hasCredential) ) ? $hasCredential : '';
							$hasOccupation = ( isset($hasOccupation) && !empty($hasOccupation) ) ? $hasOccupation : '';
							$hasOfferCatalog = ( isset($hasOfferCatalog) && !empty($hasOfferCatalog) ) ? $hasOfferCatalog : '';
							$hasPOS = ( isset($hasPOS) && !empty($hasPOS) ) ? $hasPOS : '';
							$height = ( isset($height) && !empty($height) ) ? $height : '';
							$homeLocation = ( isset($homeLocation) && !empty($homeLocation) ) ? $homeLocation : '';
							$honorificPrefix = ( isset($honorificPrefix) && !empty($honorificPrefix) ) ? $honorificPrefix : '';
							$honorificSuffix = ( isset($honorificSuffix) && !empty($honorificSuffix) ) ? $honorificSuffix : '';
							$interactionStatistic = ( isset($interactionStatistic) && !empty($interactionStatistic) ) ? $interactionStatistic : '';
							$isicV4 = ( isset($isicV4) && !empty($isicV4) ) ? $isicV4 : '';
							$jobTitle = ( isset($jobTitle) && !empty($jobTitle) ) ? $jobTitle : '';
							$knows = ( isset($knows) && !empty($knows) ) ? $knows : '';
							$knowsAbout = ( isset($knowsAbout) && !empty($knowsAbout) ) ? $knowsAbout : '';
							$knowsLanguage = ( isset($knowsLanguage) && !empty($knowsLanguage) ) ? $knowsLanguage : '';
							$makesOffer = ( isset($makesOffer) && !empty($makesOffer) ) ? $makesOffer : '';
							$memberOf = ( isset($memberOf) && !empty($memberOf) ) ? $memberOf : '';
							$naics = ( isset($naics) && !empty($naics) ) ? $naics : '';
							$nationality = ( isset($nationality) && !empty($nationality) ) ? $nationality : '';
							$netWorth = ( isset($netWorth) && !empty($netWorth) ) ? $netWorth : '';
							$owns = ( isset($owns) && !empty($owns) ) ? $owns : '';
							$parent = ( isset($parent) && !empty($parent) ) ? $parent : '';
							$performerIn = ( isset($performerIn) && !empty($performerIn) ) ? $performerIn : '';
							$publishingPrinciples = ( isset($publishingPrinciples) && !empty($publishingPrinciples) ) ? $publishingPrinciples : '';
							$relatedTo = ( isset($relatedTo) && !empty($relatedTo) ) ? $relatedTo : '';
							$seeks = ( isset($seeks) && !empty($seeks) ) ? $seeks : '';
							$sibling = ( isset($sibling) && !empty($sibling) ) ? $sibling : '';
							$sponsor = ( isset($sponsor) && !empty($sponsor) ) ? $sponsor : '';
							$spouse = ( isset($spouse) && !empty($spouse) ) ? $spouse : '';
							$taxID = ( isset($taxID) && !empty($taxID) ) ? $taxID : '';
							$telephone = ( isset($telephone) && !empty($telephone) ) ? $telephone : '';
							$vatID = ( isset($vatID) && !empty($vatID) ) ? $vatID : '';
							$weight = ( isset($weight) && !empty($weight) ) ? $weight : '';
							$workLocation = ( isset($workLocation) && !empty($workLocation) ) ? $workLocation : '';
							$worksFor = ( isset($worksFor) && !empty($worksFor) ) ? $worksFor : '';

						// Inherited properties from Audience (Thing > Intangible > Audience)

							$audienceType = ( isset($audienceType) && !empty($audienceType) ) ? $audienceType : '';
							$geographicArea = ( isset($geographicArea) && !empty($geographicArea) ) ? $geographicArea : '';

						// Inherited properties from PeopleAudience (Thing > Intangible > Audience > PeopleAudience)

							$healthCondition = ( isset($healthCondition) && !empty($healthCondition) ) ? $healthCondition : '';
							$requiredGender = ( isset($requiredGender) && !empty($requiredGender) ) ? $requiredGender : '';
							$requiredMaxAge = ( isset($requiredMaxAge) && !empty($requiredMaxAge) ) ? $requiredMaxAge : '';
							$requiredMinAge = ( isset($requiredMinAge) && !empty($requiredMinAge) ) ? $requiredMinAge : '';
							$suggestedAge = ( isset($suggestedAge) && !empty($suggestedAge) ) ? $suggestedAge : '';
							$suggestedGender = ( isset($suggestedGender) && !empty($suggestedGender) ) ? $suggestedGender : '';
							$suggestedMaxAge = ( isset($suggestedMaxAge) && !empty($suggestedMaxAge) ) ? $suggestedMaxAge : '';
							$suggestedMeasurement = ( isset($suggestedMeasurement) && !empty($suggestedMeasurement) ) ? $suggestedMeasurement : '';
							$suggestedMinAge = ( isset($suggestedMinAge) && !empty($suggestedMinAge) ) ? $suggestedMinAge : '';

						// Inherited properties from MedicalAudience (Thing > Intangible > Audience > MedicalAudience)

							/* 
							 * Also: Inherited properties from MedicalAudience (Thing > Intangible > Audience > PeopleAudience > MedicalAudience)
							 */

							// Do nothing (no property vars)

						// Properties from Patient (Thing > Intangible > Audience > MedicalAudience > Patient)

							/* 
							 * Also: Properties from Patient (Thing > Person > Patient)
							 * Also: Properties from Patient (Thing > Intangible > Audience > PeopleAudience > MedicalAudience > Patient)
							 */

							$diagnosis = ( isset($diagnosis) && !empty($diagnosis) ) ? $diagnosis : '';
							$drug = ( isset($drug) && !empty($drug) ) ? $drug : '';
							$healthCondition = ( isset($healthCondition) && !empty($healthCondition) ) ? $healthCondition : '';

					// Add values to the schema array

						// Inherited properties

							$schema = uamswp_fad_schema_medicalaudience(
								$schema, // array // Main schema array
								// MedicalAudience (no property vars)
								// PeopleAudience
									$healthCondition, // healthCondition
									$requiredGender, // requiredGender
									$requiredMaxAge, // requiredMaxAge
									$requiredMinAge, // requiredMinAge
									$suggestedAge, // suggestedAge
									$suggestedGender, // suggestedGender
									$suggestedMaxAge, // suggestedMaxAge
									$suggestedMeasurement, // suggestedMeasurement
									$suggestedMinAge, // suggestedMinAge
								// Audience
									$audienceType, // audienceType
									$geographicArea, // geographicArea
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

							$schema = uamswp_fad_schema_person(
								$schema, // array // Main schema array
								// Person
									$additionalName, // additionalName
									$address, // address
									$affiliation, // affiliation
									$alumniOf, // alumniOf
									$award, // award
									$birthDate, // birthDate
									$birthPlace, // birthPlace
									$brand, // brand
									$callSign, // callSign
									$children, // children
									$colleague, // colleague
									$contactPoint, // contactPoint
									$deathDate, // deathDate
									$deathPlace, // deathPlace
									$duns, // duns
									$email, // email
									$familyName, // familyName
									$faxNumber, // faxNumber
									$follows, // follows
									$funder, // funder
									$funding, // funding
									$gender, // gender
									$givenName, // givenName
									$globalLocationNumber, // globalLocationNumber
									$hasCredential, // hasCredential
									$hasOccupation, // hasOccupation
									$hasOfferCatalog, // hasOfferCatalog
									$hasPOS, // hasPOS
									$height, // height
									$homeLocation, // homeLocation
									$honorificPrefix, // honorificPrefix
									$honorificSuffix, // honorificSuffix
									$interactionStatistic, // interactionStatistic
									$isicV4, // isicV4
									$jobTitle, // jobTitle
									$knows, // knows
									$knowsAbout, // knowsAbout
									$knowsLanguage, // knowsLanguage
									$makesOffer, // makesOffer
									$memberOf, // memberOf
									$naics, // naics
									$nationality, // nationality
									$netWorth, // netWorth
									$owns, // owns
									$parent, // parent
									$performerIn, // performerIn
									$publishingPrinciples, // publishingPrinciples
									$relatedTo, // relatedTo
									$seeks, // seeks
									$sibling, // sibling
									$sponsor, // sponsor
									$spouse, // spouse
									$taxID, // taxID
									$telephone, // telephone
									$vatID, // vatID
									$weight, // weight
									$workLocation, // workLocation
									$worksFor, // worksFor
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

						// Properties from Patient (Thing > Intangible > Audience > MedicalAudience > Patient)

							/* 
							 * Also: Properties from Patient (Thing > Person > Patient)
							 * Also: Properties from Patient (Thing > Intangible > Audience > PeopleAudience > MedicalAudience > Patient)
							 */

							// diagnosis

								/* 
								 * Expected Type:
								 *     MedicalCondition
								 * 
								 * One or more alternative conditions considered in the differential diagnosis 
								 * process as output of a diagnosis process.
								 */

								$schema['diagnosis'] = ( isset($diagnosis) && !empty($diagnosis) ) ? uamswp_fad_schema_type_selector($diagnosis) : '';

							// drug

								/* 
								 * Expected Type:
								 *     Drug
								 * 
								 * Specifying a drug or medicine used in a medication procedure.
								 */

								$schema['drug'] = ( isset($drug) && !empty($drug) ) ? uamswp_fad_schema_type_selector($drug) : '';

							// healthCondition

								/* 
								 * Expected Type:
								 *     MedicalCondition
								 * 
								 * Specifying the health condition(s) of a patient, medical study, or other target 
								 * audience.
								 */

								$schema['healthCondition'] = ( isset($healthCondition) && !empty($healthCondition) ) ? uamswp_fad_schema_type_selector($healthCondition) : '';

					// Remove any empty values from the schema array

						$schema = array_filter($schema);
						$schema = array_unique($schema, SORT_REGULAR);

					return $schema;

				}

		// PeopleAudience

			/*
			 * Thing > Intangible > Audience > PeopleAudience
			 * 
			 * A set of characteristics belonging to people (e.g., who compose an item's 
			 * target audience).
			 */

			function uamswp_fad_schema_medicalaudience(
				$schema, // array // Main schema array
				// PeopleAudience
					$healthCondition = '', // healthCondition
					$requiredGender = '', // requiredGender
					$requiredMaxAge = '', // requiredMaxAge
					$requiredMinAge = '', // requiredMinAge
					$suggestedAge = '', // suggestedAge
					$suggestedGender = '', // suggestedGender
					$suggestedMaxAge = '', // suggestedMaxAge
					$suggestedMeasurement = '', // suggestedMeasurement
					$suggestedMinAge = '', // suggestedMinAge
				// Audience
					$audienceType = '', // audienceType
					$geographicArea = '', // geographicArea
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

					// Inherited properties from Audience (Thing > Intangible > Audience)

						$audienceType = ( isset($audienceType) && !empty($audienceType) ) ? $audienceType : '';
						$geographicArea = ( isset($geographicArea) && !empty($geographicArea) ) ? $geographicArea : '';

					// Properties from PeopleAudience (Thing > Intangible > Audience > PeopleAudience)

						$healthCondition = ( isset($healthCondition) && !empty($healthCondition) ) ? $healthCondition : '';
						$requiredGender = ( isset($requiredGender) && !empty($requiredGender) ) ? $requiredGender : '';
						$requiredMaxAge = ( isset($requiredMaxAge) && !empty($requiredMaxAge) ) ? $requiredMaxAge : '';
						$requiredMinAge = ( isset($requiredMinAge) && !empty($requiredMinAge) ) ? $requiredMinAge : '';
						$suggestedAge = ( isset($suggestedAge) && !empty($suggestedAge) ) ? $suggestedAge : '';
						$suggestedGender = ( isset($suggestedGender) && !empty($suggestedGender) ) ? $suggestedGender : '';
						$suggestedMaxAge = ( isset($suggestedMaxAge) && !empty($suggestedMaxAge) ) ? $suggestedMaxAge : '';
						$suggestedMeasurement = ( isset($suggestedMeasurement) && !empty($suggestedMeasurement) ) ? $suggestedMeasurement : '';
						$suggestedMinAge = ( isset($suggestedMinAge) && !empty($suggestedMinAge) ) ? $suggestedMinAge : '';

				// Add values to the schema array

					// Inherited properties

						$schema = uamswp_fad_schema_audience(
							$schema, // array // Main schema array
							// Audience
								$audienceType, // audienceType
								$geographicArea, // geographicArea
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

					// Properties from PeopleAudience (Thing > Intangible > Audience > PeopleAudience)

						// healthCondition

							/* 
							 * Expected Type:
							 *     MedicalCondition
							 * 
							 * Specifying the health condition(s) of a patient, medical study, or other target 
							 * audience.
							 */

							$schema['healthCondition'] = ( isset($healthCondition) && !empty($healthCondition) ) ? uamswp_fad_schema_type_selector($healthCondition) : '';

						// requiredGender

							/* 
							 * Expected Type:
							 *     DataType > Text
							 * 
							 * Audiences defined by a person's gender.
							 */

							$schema['requiredGender'] = ( isset($requiredGender) && !empty($requiredGender) ) ? uamswp_fad_schema_type_selector($requiredGender) : '';

						// requiredMaxAge

							/* 
							 * Expected Type:
							 *     Integer
							 * 
							 * Audiences defined by a person's maximum age.
							 */

							$schema['requiredMaxAge'] = ( isset($requiredMaxAge) && !empty($requiredMaxAge) ) ? uamswp_fad_schema_type_selector($requiredMaxAge) : '';

						// requiredMinAge

							/* 
							 * Expected Type:
							 *     Integer
							 * 
							 * Audiences defined by a person's minimum age.
							 */

							$schema['requiredMinAge'] = ( isset($requiredMinAge) && !empty($requiredMinAge) ) ? uamswp_fad_schema_type_selector($requiredMinAge) : '';

						// suggestedAge

							/* 
							 * Expected Type:
							 *     Thing > Intangible > StructuredValue > QuantitativeValue
							 * 
							 * The age or age range for the intended audience or person, for example 3-12 
							 * months for infants, 1-5 years for toddlers.
							 */

							$schema['suggestedAge'] = ( isset($suggestedAge) && !empty($suggestedAge) ) ? uamswp_fad_schema_type_selector($suggestedAge) : '';

						// suggestedGender

							/* 
							 * Expected Type:
							 *     GenderType
							 *     DataType > Text
							 * 
							 * The suggested gender of the intended person or audience, for example "male", 
							 * "female", or "unisex".
							 */

							$schema['suggestedGender'] = ( isset($suggestedGender) && !empty($suggestedGender) ) ? uamswp_fad_schema_type_selector($suggestedGender) : '';

						// suggestedMaxAge

							/* 
							 * Expected Type:
							 *     Number
							 * 
							 * Maximum recommended age in years for the audience or user.
							 */

							$schema['suggestedMaxAge'] = ( isset($suggestedMaxAge) && !empty($suggestedMaxAge) ) ? uamswp_fad_schema_type_selector($suggestedMaxAge) : '';

						// suggestedMeasurement

							/* 
							 * Expected Type:
							 *     Thing > Intangible > StructuredValue > QuantitativeValue
							 * 
							 * A suggested range of body measurements for the intended audience or person, for 
							 * example inseam between 32 and 34 inches or height between 170 and 190 cm. 
							 * Typically found on a size chart for wearable products.
							 */

							$schema['suggestedMeasurement'] = ( isset($suggestedMeasurement) && !empty($suggestedMeasurement) ) ? uamswp_fad_schema_type_selector($suggestedMeasurement) : '';

						// suggestedMinAge

							/* 
							 * Expected Type:
							 *     Number
							 * 
							 * Minimum recommended age in years for the audience or user.
							 */

							$schema['suggestedMinAge'] = ( isset($suggestedMinAge) && !empty($suggestedMinAge) ) ? uamswp_fad_schema_type_selector($suggestedMinAge) : '';

				// Remove any empty values from the schema array

					$schema = array_filter($schema);
					$schema = array_unique($schema, SORT_REGULAR);

				return $schema;

			}

			// MedicalAudience

				/*
				 * Thing > Intangible > Audience > PeopleAudience > MedicalAudience
				 * 
				 * See: Thing > Intangible > Audience > MedicalAudience
				 */

			// ParentAudience

				/*
				 * Thing > Intangible > Audience > PeopleAudience > ParentAudience
				 * 
				 * 
				 */

				function uamswp_fad_schema_parentaudience(
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

		// Researcher

			/*
			 * Thing > Intangible > Audience > Researcher
			 * 
			 * 
			 */

			function uamswp_fad_schema_researcher(
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

	// BedDetails
	include_once __DIR__ . '/Intangible/BedDetails.php';

		/*
		 * Thing > Intangible > BedDetails
		 * 
		 * 
		 */

		function uamswp_fad_schema_beddetails(
			$schema, // array // Main schema array
			// BedDetails
				$foo = '', // foo
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
	
				// Properties from BedDetails
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from BedDetails
	
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

	// Brand
	include_once __DIR__ . '/Intangible/Brand.php';

		/*
		 * Thing > Intangible > Brand
		 * 
		 * 
		 */

		function uamswp_fad_schema_brand(
			$schema, // array // Main schema array
			// Brand
				$foo = '', // foo
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
	
				// Properties from Brand
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Brand
	
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

	// BroadcastChannel
	include_once __DIR__ . '/Intangible/BroadcastChannel.php';

		/*
		 * Thing > Intangible > BroadcastChannel
		 * 
		 * 
		 */

		function uamswp_fad_schema_broadcastchannel(
			$schema, // array // Main schema array
			// BroadcastChannel
				$foo = '', // foo
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
	
				// Properties from BroadcastChannel
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from BroadcastChannel
	
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

		// RadioChannel

			/*
			 * Thing > Intangible > BroadcastChannel > RadioChannel
			 * 
			 * 
			 */

			function uamswp_fad_schema_radiochannel(
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

			// AMRadioChannel

				/*
				 * Thing > Intangible > BroadcastChannel > RadioChannel > AMRadioChannel
				 * 
				 * 
				 */

				function uamswp_fad_schema_amradiochannel(
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

			// FMRadioChannel

				/*
				 * Thing > Intangible > BroadcastChannel > RadioChannel > FMRadioChannel
				 * 
				 * 
				 */

				function uamswp_fad_schema_fmradiochannel(
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

		// TelevisionChannel

			/*
			 * Thing > Intangible > BroadcastChannel > TelevisionChannel
			 * 
			 * 
			 */

			function uamswp_fad_schema_televisionchannel(
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

	// BroadcastFrequencySpecification
	include_once __DIR__ . '/Intangible/BroadcastFrequencySpecification.php';

		/*
		 * Thing > Intangible > BroadcastFrequencySpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_broadcastfrequencyspecification(
			$schema, // array // Main schema array
			// BroadcastFrequencySpecification
				$foo = '', // foo
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
	
				// Properties from BroadcastFrequencySpecification
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from BroadcastFrequencySpecification
	
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

	// Class
	include_once __DIR__ . '/Intangible/Class.php';

		/*
		 * Thing > Intangible > Class
		 * 
		 * 
		 */

		function uamswp_fad_schema_class(
			$schema, // array // Main schema array
			// Class
				$foo = '', // foo
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
	
				// Properties from Class
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Class
	
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

	// ComputerLanguage
	include_once __DIR__ . '/Intangible/ComputerLanguage.php';

		/*
		 * Thing > Intangible > ComputerLanguage
		 * 
		 * 
		 */

		function uamswp_fad_schema_computerlanguage(
			$schema, // array // Main schema array
			// ComputerLanguage
				$foo = '', // foo
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
	
				// Properties from ComputerLanguage
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from ComputerLanguage
	
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

	// ConstraintNode
	include_once __DIR__ . '/Intangible/ConstraintNode.php';

		/*
		 * Thing > Intangible > ConstraintNode
		 * 
		 * 
		 */

		function uamswp_fad_schema_constraintnode(
			$schema, // array // Main schema array
			// ConstraintNode
				$foo = '', // foo
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
	
				// Properties from ConstraintNode
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from ConstraintNode
	
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

		// StatisticalVariable

			/*
			 * Thing > Intangible > ConstraintNode > StatisticalVariable
			 * 
			 * 
			 */

			function uamswp_fad_schema_statisticalvariable(
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

	// DataFeedItem
	include_once __DIR__ . '/Intangible/DataFeedItem.php';

		/*
		 * Thing > Intangible > DataFeedItem
		 * 
		 * 
		 */

		function uamswp_fad_schema_datafeeditem(
			$schema, // array // Main schema array
			// DataFeedItem
				$foo = '', // foo
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
	
				// Properties from DataFeedItem
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from DataFeedItem
	
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

	// DefinedTerm
	include_once __DIR__ . '/Intangible/DefinedTerm.php';

		/*
		 * Thing > Intangible > DefinedTerm
		 * 
		 * 
		 */

		function uamswp_fad_schema_definedterm(
			$schema, // array // Main schema array
			// DefinedTerm
				$foo = '', // foo
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
	
				// Properties from DefinedTerm
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from DefinedTerm
	
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

		// CategoryCode

			/*
			 * Thing > Intangible > DefinedTerm > CategoryCode
			 * 
			 * 
			 */

			function uamswp_fad_schema_categorycode(
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

			// MedicalCode

				/*
				 * Thing > Intangible > DefinedTerm > CategoryCode > MedicalCode
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicalcode(
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


	// Demand
	include_once __DIR__ . '/Intangible/Demand.php';

		/*
		 * Thing > Intangible > Demand
		 * 
		 * 
		 */

		function uamswp_fad_schema_demand(
			$schema, // array // Main schema array
			// Demand
				$foo = '', // foo
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
	
				// Properties from Demand
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Demand
	
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

	// DigitalDocumentPermission
	include_once __DIR__ . '/Intangible/DigitalDocumentPermission.php';

		/*
		 * Thing > Intangible > DigitalDocumentPermission
		 * 
		 * 
		 */

		function uamswp_fad_schema_digitaldocumentpermission(
			$schema, // array // Main schema array
			// DigitalDocumentPermission
				$foo = '', // foo
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
	
				// Properties from DigitalDocumentPermission
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from DigitalDocumentPermission
	
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

	// EducationalOccupationalProgram
	include_once __DIR__ . '/Intangible/EducationalOccupationalProgram.php';

		/*
		 * Thing > Intangible > EducationalOccupationalProgram
		 * 
		 * 
		 */

		function uamswp_fad_schema_educationaloccupationalprogram(
			$schema, // array // Main schema array
			// EducationalOccupationalProgram
				$foo = '', // foo
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
	
				// Properties from EducationalOccupationalProgram
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from EducationalOccupationalProgram
	
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

		// WorkBasedProgram

			/*
			 * Thing > Intangible > EducationalOccupationalProgram > WorkBasedProgram
			 * 
			 * 
			 */

			function uamswp_fad_schema_workbasedprogram(
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

	// EnergyConsumptionDetails
	include_once __DIR__ . '/Intangible/EnergyConsumptionDetails.php';

		/*
		 * Thing > Intangible > EnergyConsumptionDetails
		 * 
		 * 
		 */

		function uamswp_fad_schema_energyconsumptiondetails(
			$schema, // array // Main schema array
			// EnergyConsumptionDetails
				$foo = '', // foo
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
	
				// Properties from EnergyConsumptionDetails
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from EnergyConsumptionDetails
	
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

	// EntryPoint
	include_once __DIR__ . '/Intangible/EntryPoint.php';

		/*
		 * Thing > Intangible > EntryPoint
		 * 
		 * 
		 */

		function uamswp_fad_schema_entrypoint(
			$schema, // array // Main schema array
			// EntryPoint
				$foo = '', // foo
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
	
				// Properties from EntryPoint
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from EntryPoint
	
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

	// Enumeration
	include_once __DIR__ . '/Intangible/Enumeration.php';

	// FloorPlan
	include_once __DIR__ . '/Intangible/FloorPlan.php';

		/*
		 * Thing > Intangible > FloorPlan
		 * 
		 * 
		 */

		function uamswp_fad_schema_floorplan(
			$schema, // array // Main schema array
			// FloorPlan
				$foo = '', // foo
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
	
				// Properties from FloorPlan
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from FloorPlan
	
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

	// GameServer
	include_once __DIR__ . '/Intangible/GameServer.php';

		/*
		 * Thing > Intangible > GameServer
		 * 
		 * 
		 */

		function uamswp_fad_schema_gameserver(
			$schema, // array // Main schema array
			// GameServer
				$foo = '', // foo
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
	
				// Properties from GameServer
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from GameServer
	
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

	// GeospatialGeometry
	include_once __DIR__ . '/Intangible/GeospatialGeometry.php';

		/*
		 * Thing > Intangible > GeospatialGeometry
		 * 
		 * 
		 */

		function uamswp_fad_schema_geospatialgeometry(
			$schema, // array // Main schema array
			// GeospatialGeometry
				$foo = '', // foo
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
	
				// Properties from GeospatialGeometry
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from GeospatialGeometry
	
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

	// Grant
	include_once __DIR__ . '/Intangible/Grant.php';

		/*
		 * Thing > Intangible > Grant
		 * 
		 * 
		 */

		function uamswp_fad_schema_grant(
			$schema, // array // Main schema array
			// Grant
				$foo = '', // foo
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
	
				// Properties from Grant
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Grant
	
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

		// MonetaryGrant

			/*
			 * Thing > Intangible > Grant > MonetaryGrant
			 * 
			 * 
			 */

			function uamswp_fad_schema_monetarygrant(
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

	// HealthInsurancePlan
	include_once __DIR__ . '/Intangible/HealthInsurancePlan.php';

		/*
		 * Thing > Intangible > HealthInsurancePlan
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthinsuranceplan(
			$schema, // array // Main schema array
			// HealthInsurancePlan
				$foo = '', // foo
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
	
				// Properties from HealthInsurancePlan
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from HealthInsurancePlan
	
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

	// HealthPlanCostSharingSpecification
	include_once __DIR__ . '/Intangible/HealthPlanCostSharingSpecification.php';

		/*
		 * Thing > Intangible > HealthPlanCostSharingSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthplancostsharingspecification(
			$schema, // array // Main schema array
			// HealthPlanCostSharingSpecification
				$foo = '', // foo
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
	
				// Properties from HealthPlanCostSharingSpecification
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from HealthPlanCostSharingSpecification
	
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

	// HealthPlanFormulary
	include_once __DIR__ . '/Intangible/HealthPlanFormulary.php';

		/*
		 * Thing > Intangible > HealthPlanFormulary
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthplanformulary(
			$schema, // array // Main schema array
			// HealthPlanFormulary
				$foo = '', // foo
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
	
				// Properties from HealthPlanFormulary
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from HealthPlanFormulary
	
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

	// HealthPlanNetwork
	include_once __DIR__ . '/Intangible/HealthPlanNetwork.php';

		/*
		 * Thing > Intangible > HealthPlanNetwork
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthplannetwork(
			$schema, // array // Main schema array
			// HealthPlanNetwork
				$foo = '', // foo
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
	
				// Properties from HealthPlanNetwork
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from HealthPlanNetwork
	
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

	// Invoice
	include_once __DIR__ . '/Intangible/Invoice.php';

		/*
		 * Thing > Intangible > Invoice
		 * 
		 * 
		 */

		function uamswp_fad_schema_invoice(
			$schema, // array // Main schema array
			// Invoice
				$foo = '', // foo
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
	
				// Properties from Invoice
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Invoice
	
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

	// ItemList
	include_once __DIR__ . '/Intangible/ItemList.php';

		/*
		 * Thing > Intangible > ItemList
		 * 
		 * 
		 */

		function uamswp_fad_schema_itemlist(
			$schema, // array // Main schema array
			// ItemList
				$foo = '', // foo
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
	
				// Properties from ItemList
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from ItemList
	
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

		// BreadcrumbList

			/*
			 * Thing > Intangible > ItemList > BreadcrumbList
			 * 
			 * 
			 */

			function uamswp_fad_schema_breadcrumblist(
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

		// HowToSection

			/*
			 * Thing > Intangible > ItemList > HowToSection
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtosection(
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

		// HowToStep

			/*
			 * Thing > Intangible > ItemList > HowToStep
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtostep(
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

		// OfferCatalog

			/*
			 * Thing > Intangible > ItemList > OfferCatalog
			 * 
			 * 
			 */

			function uamswp_fad_schema_offercatalog(
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

	// JobPosting
	include_once __DIR__ . '/Intangible/JobPosting.php';

		/*
		 * Thing > Intangible > JobPosting
		 * 
		 * 
		 */

		function uamswp_fad_schema_jobposting(
			$schema, // array // Main schema array
			// JobPosting
				$foo = '', // foo
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
	
				// Properties from JobPosting
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from JobPosting
	
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

	// Language
	include_once __DIR__ . '/Intangible/Language.php';

		/*
		 * Thing > Intangible > Language
		 * 
		 * 
		 */

		function uamswp_fad_schema_language(
			$schema, // array // Main schema array
			// Language
				$foo = '', // foo
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
	
				// Properties from Language
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Language
	
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

	// ListItem
	include_once __DIR__ . '/Intangible/ListItem.php';

		/*
		 * Thing > Intangible > ListItem
		 * 
		 * 
		 */

		function uamswp_fad_schema_listitem(
			$schema, // array // Main schema array
			// ListItem
				$foo = '', // foo
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
	
				// Properties from ListItem
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from ListItem
	
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

		// HowToDirection

			/*
			 * Thing > Intangible > ListItem > HowToDirection
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtodirection(
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

		// HowToItem

			/*
			 * Thing > Intangible > ListItem > HowToItem
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtoitem(
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

			// HowToSupply

				/*
				 * Thing > Intangible > ListItem > HowToItem > HowToSupply
				 * 
				 * 
				 */

				function uamswp_fad_schema_howtosupply(
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

			// HowToTool

				/*
				 * Thing > Intangible > ListItem > HowToItem > HowToTool
				 * 
				 * 
				 */

				function uamswp_fad_schema_howtotool(
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

		// HowToSection

			/*
			 * Thing > Intangible > ListItem > HowToSection
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtosection(
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

		// HowToStep

			/*
			 * Thing > Intangible > ListItem > HowToStep
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtostep(
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

		// HowToTip

			/*
			 * Thing > Intangible > ListItem > HowToTip
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtotip(
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

	// MediaSubscription
	include_once __DIR__ . '/Intangible/MediaSubscription.php';

		/*
		 * Thing > Intangible > MediaSubscription
		 * 
		 * 
		 */

		function uamswp_fad_schema_mediasubscription(
			$schema, // array // Main schema array
			// MediaSubscription
				$foo = '', // foo
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
	
				// Properties from MediaSubscription
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from MediaSubscription
	
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

	// MenuItem
	include_once __DIR__ . '/Intangible/MenuItem.php';

		/*
		 * Thing > Intangible > MenuItem
		 * 
		 * 
		 */

		function uamswp_fad_schema_menuitem(
			$schema, // array // Main schema array
			// MenuItem
				$foo = '', // foo
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
	
				// Properties from MenuItem
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from MenuItem
	
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

	// MerchantReturnPolicy
	include_once __DIR__ . '/Intangible/MerchantReturnPolicy.php';

		/*
		 * Thing > Intangible > MerchantReturnPolicy
		 * 
		 * 
		 */

		function uamswp_fad_schema_merchantreturnpolicy(
			$schema, // array // Main schema array
			// MerchantReturnPolicy
				$foo = '', // foo
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
	
				// Properties from MerchantReturnPolicy
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from MerchantReturnPolicy
	
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

	// MerchantReturnPolicySeasonalOverride
	include_once __DIR__ . '/Intangible/MerchantReturnPolicySeasonalOverride.php';

		/*
		 * Thing > Intangible > MerchantReturnPolicySeasonalOverride
		 * 
		 * 
		 */

		function uamswp_fad_schema_merchantreturnpolicyseasonaloverride(
			$schema, // array // Main schema array
			// MerchantReturnPolicySeasonalOverride
				$foo = '', // foo
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
	
				// Properties from MerchantReturnPolicySeasonalOverride
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from MerchantReturnPolicySeasonalOverride
	
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
	include_once __DIR__ . '/Intangible/Observation.php';

		/*
		 * Thing > Intangible > Observation
		 * 
		 * 
		 */

		function uamswp_fad_schema_observation(
			$schema, // array // Main schema array
			// Observation
				$foo = '', // foo
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
	
				// Properties from Observation
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Observation
	
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

	// Occupation
	include_once __DIR__ . '/Intangible/Occupation.php';

		/*
		 * Thing > Intangible > Occupation
		 * 
		 * 
		 */

		function uamswp_fad_schema_occupation(
			$schema, // array // Main schema array
			// Occupation
				$foo = '', // foo
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
	
				// Properties from Occupation
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Occupation
	
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

	// OccupationalExperienceRequirements
	include_once __DIR__ . '/Intangible/OccupationalExperienceRequirements.php';

		/*
		 * Thing > Intangible > OccupationalExperienceRequirements
		 * 
		 * 
		 */

		function uamswp_fad_schema_occupationalexperiencerequirements(
			$schema, // array // Main schema array
			// OccupationalExperienceRequirements
				$foo = '', // foo
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
	
				// Properties from OccupationalExperienceRequirements
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from OccupationalExperienceRequirements
	
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

	// Offer
	include_once __DIR__ . '/Intangible/Offer.php';

		/*
		 * Thing > Intangible > Offer
		 * 
		 * 
		 */

		function uamswp_fad_schema_offer(
			$schema, // array // Main schema array
			// Offer
				$foo = '', // foo
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
	
				// Properties from Offer
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Offer
	
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

		// AggregateOffer

			/*
			 * Thing > Intangible > Offer > AggregateOffer
			 * 
			 * 
			 */

			function uamswp_fad_schema_aggregateoffer(
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

		// OfferForLease

			/*
			 * Thing > Intangible > Offer > OfferForLease
			 * 
			 * 
			 */

			function uamswp_fad_schema_offerforlease(
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

		// OfferForPurchase

			/*
			 * Thing > Intangible > Offer > OfferForPurchase
			 * 
			 * 
			 */

			function uamswp_fad_schema_offerforpurchase(
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

	// Order
	include_once __DIR__ . '/Intangible/Order.php';

		/*
		 * Thing > Intangible > Order
		 * 
		 * 
		 */

		function uamswp_fad_schema_order(
			$schema, // array // Main schema array
			// Order
				$foo = '', // foo
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
	
				// Properties from Order
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Order
	
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

	// OrderItem
	include_once __DIR__ . '/Intangible/OrderItem.php';

		/*
		 * Thing > Intangible > OrderItem
		 * 
		 * 
		 */

		function uamswp_fad_schema_orderitem(
			$schema, // array // Main schema array
			// OrderItem
				$foo = '', // foo
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
	
				// Properties from OrderItem
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from OrderItem
	
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

	// ParcelDelivery
	include_once __DIR__ . '/Intangible/ParcelDelivery.php';

		/*
		 * Thing > Intangible > ParcelDelivery
		 * 
		 * 
		 */

		function uamswp_fad_schema_parceldelivery(
			$schema, // array // Main schema array
			// ParcelDelivery
				$foo = '', // foo
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
	
				// Properties from ParcelDelivery
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from ParcelDelivery
	
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

	// Permit
	include_once __DIR__ . '/Intangible/Permit.php';

		/*
		 * Thing > Intangible > Permit
		 * 
		 * 
		 */

		function uamswp_fad_schema_permit(
			$schema, // array // Main schema array
			// Permit
				$foo = '', // foo
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
	
				// Properties from Permit
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Permit
	
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

		// GovernmentPermit

			/*
			 * Thing > Intangible > Permit > GovernmentPermit
			 * 
			 * 
			 */

			function uamswp_fad_schema_governmentpermit(
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

	// ProgramMembership
	include_once __DIR__ . '/Intangible/ProgramMembership.php';

		/*
		 * Thing > Intangible > ProgramMembership
		 * 
		 * 
		 */

		function uamswp_fad_schema_programmembership(
			$schema, // array // Main schema array
			// ProgramMembership
				$foo = '', // foo
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
	
				// Properties from ProgramMembership
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from ProgramMembership
	
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

	// Property
	include_once __DIR__ . '/Intangible/Property.php';

		/*
		 * Thing > Intangible > Property
		 * 
		 * 
		 */

		function uamswp_fad_schema_property(
			$schema, // array // Main schema array
			// Property
				$foo = '', // foo
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
	
				// Properties from Property
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Property
	
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

	// PropertyValueSpecification
	include_once __DIR__ . '/Intangible/PropertyValueSpecification.php';

		/*
		 * Thing > Intangible > PropertyValueSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_propertyvaluespecification(
			$schema, // array // Main schema array
			// PropertyValueSpecification
				$foo = '', // foo
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
	
				// Properties from PropertyValueSpecification
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from PropertyValueSpecification
	
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

	// Quantity
	include_once __DIR__ . '/Intangible/Quantity.php';

		/*
		 * Thing > Intangible > Quantity
		 * 
		 * 
		 */

		function uamswp_fad_schema_quantity(
			$schema, // array // Main schema array
			// Quantity
				$foo = '', // foo
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
	
				// Properties from Quantity
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Quantity
	
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

		// Distance

			/*
			 * Thing > Intangible > Quantity > Distance
			 * 
			 * 
			 */

			function uamswp_fad_schema_distance(
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

		// Duration

			/*
			 * Thing > Intangible > Quantity > Duration
			 * 
			 * 
			 */

			function uamswp_fad_schema_duration(
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

		// Energy

			/*
			 * Thing > Intangible > Quantity > Energy
			 * 
			 * 
			 */

			function uamswp_fad_schema_energy(
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

		// Mass

			/*
			 * Thing > Intangible > Quantity > Mass
			 * 
			 * 
			 */

			function uamswp_fad_schema_mass(
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

	// Rating
	include_once __DIR__ . '/Intangible/Rating.php';

	// Reservation
	include_once __DIR__ . '/Intangible/Reservation.php';

		/*
		 * Thing > Intangible > Reservation
		 * 
		 * 
		 */

		function uamswp_fad_schema_reservation(
			$schema, // array // Main schema array
			// Reservation
				$foo = '', // foo
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
	
				// Properties from Reservation
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Reservation
	
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

		// BoatReservation

			/*
			 * Thing > Intangible > Reservation > BoatReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_boatreservation(
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

		// BusReservation

			/*
			 * Thing > Intangible > Reservation > BusReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_busreservation(
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

		// EventReservation

			/*
			 * Thing > Intangible > Reservation > EventReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_eventreservation(
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

		// FlightReservation

			/*
			 * Thing > Intangible > Reservation > FlightReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_flightreservation(
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

		// FoodEstablishmentReservation

			/*
			 * Thing > Intangible > Reservation > FoodEstablishmentReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_foodestablishmentreservation(
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

		// LodgingReservation

			/*
			 * Thing > Intangible > Reservation > LodgingReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_lodgingreservation(
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

		// RentalCarReservation

			/*
			 * Thing > Intangible > Reservation > RentalCarReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_rentalcarreservation(
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

		// ReservationPackage

			/*
			 * Thing > Intangible > Reservation > ReservationPackage
			 * 
			 * 
			 */

			function uamswp_fad_schema_reservationpackage(
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

		// TaxiReservation

			/*
			 * Thing > Intangible > Reservation > TaxiReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_taxireservation(
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

		// TrainReservation

			/*
			 * Thing > Intangible > Reservation > TrainReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_trainreservation(
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

	// Role
	include_once __DIR__ . '/Intangible/Role.php';

		/*
		 * Thing > Intangible > Role
		 * 
		 * 
		 */

		function uamswp_fad_schema_role(
			$schema, // array // Main schema array
			// Role
				$foo = '', // foo
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
	
				// Properties from Role
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Role
	
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

		// LinkRole

			/*
			 * Thing > Intangible > Role > LinkRole
			 * 
			 * 
			 */

			function uamswp_fad_schema_linkrole(
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

		// OrganizationRole

			/*
			 * Thing > Intangible > Role > OrganizationRole
			 * 
			 * 
			 */

			function uamswp_fad_schema_organizationrole(
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

			// EmployeeRole

				/*
				 * Thing > Intangible > Role > OrganizationRole > EmployeeRole
				 * 
				 * 
				 */

				function uamswp_fad_schema_employeerole(
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

		// PerformanceRole

			/*
			 * Thing > Intangible > Role > PerformanceRole
			 * 
			 * 
			 */

			function uamswp_fad_schema_performancerole(
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

	// Schedule
	include_once __DIR__ . '/Intangible/Schedule.php';

		/*
		 * Thing > Intangible > Schedule
		 * 
		 * 
		 */

		function uamswp_fad_schema_schedule(
			$schema, // array // Main schema array
			// Schedule
				$foo = '', // foo
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
	
				// Properties from Schedule
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Schedule
	
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

	// Seat
	include_once __DIR__ . '/Intangible/Seat.php';

		/*
		 * Thing > Intangible > Seat
		 * 
		 * 
		 */

		function uamswp_fad_schema_seat(
			$schema, // array // Main schema array
			// Seat
				$foo = '', // foo
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
	
				// Properties from Seat
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Seat
	
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

	// Series
	include_once __DIR__ . '/Intangible/Series.php';

		/*
		 * Thing > Intangible > Series
		 * 
		 * 
		 */

		function uamswp_fad_schema_series(
			$schema, // array // Main schema array
			// Series
				$foo = '', // foo
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
	
				// Properties from Series
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Series
	
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

		// CreativeWorkSeries

			/*
			 * Thing > Intangible > Series > CreativeWorkSeries
			 * 
			 * 
			 */

			function uamswp_fad_schema_creativeworkseries(
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

		// EventSeries

			/*
			 * Thing > Intangible > Series > EventSeries
			 * 
			 * 
			 */

			function uamswp_fad_schema_eventseries(
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

	// Service
	include_once __DIR__ . '/Intangible/Service.php';

		/*
		 * Thing > Intangible > Service
		 * 
		 * 
		 */

		function uamswp_fad_schema_service(
			$schema, // array // Main schema array
			// Service
				$foo = '', // foo
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
	
				// Properties from Service
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Service
	
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

		// BroadcastService

			/*
			 * Thing > Intangible > Service > BroadcastService
			 * 
			 * 
			 */

			function uamswp_fad_schema_broadcastservice(
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

			// RadioBroadcastService

				/*
				 * Thing > Intangible > Service > BroadcastService > RadioBroadcastService
				 * 
				 * 
				 */

				function uamswp_fad_schema_radiobroadcastservice(
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

		// CableOrSatelliteService

			/*
			 * Thing > Intangible > Service > CableOrSatelliteService
			 * 
			 * 
			 */

			function uamswp_fad_schema_cableorsatelliteservice(
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

		// FinancialProduct

			/*
			 * Thing > Intangible > Service > FinancialProduct
			 * 
			 * 
			 */

			function uamswp_fad_schema_financialproduct(
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

			// BankAccount

				/*
				 * Thing > Intangible > Service > FinancialProduct > BankAccount
				 * 
				 * 
				 */

				function uamswp_fad_schema_bankaccount(
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

				// DepositAccount

					/*
					 * Thing > Intangible > Service > qux > quux > DepositAccount
					 * 
					 * 
					 */

					function uamswp_fad_schema_depositaccount(
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

			// CurrencyConversionService

				/*
				 * Thing > Intangible > Service > qux > CurrencyConversionService
				 * 
				 * 
				 */

				function uamswp_fad_schema_currencyconversionservice(
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

			// InvestmentOrDeposit

				/*
				 * Thing > Intangible > Service > qux > InvestmentOrDeposit
				 * 
				 * 
				 */

				function uamswp_fad_schema_investmentordeposit(
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

				// BrokerageAccount

					/*
					 * Thing > Intangible > Service > qux > quux > BrokerageAccount
					 * 
					 * 
					 */

					function uamswp_fad_schema_brokerageaccount(
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

				// DepositAccount

					/*
					 * Thing > Intangible > Service > qux > quux > DepositAccount
					 * 
					 * 
					 */

					function uamswp_fad_schema_depositaccount(
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

				// InvestmentFund

					/*
					 * Thing > Intangible > Service > qux > quux > InvestmentFund
					 * 
					 * 
					 */

					function uamswp_fad_schema_investmentfund(
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

			// LoanOrCredit

				/*
				 * Thing > Intangible > Service > qux > LoanOrCredit
				 * 
				 * 
				 */

				function uamswp_fad_schema_loanorcredit(
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

				// CreditCard

					/*
					 * Thing > Intangible > Service > qux > quux > CreditCard
					 * 
					 * 
					 */

					function uamswp_fad_schema_creditcard(
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

				// MortgageLoan

					/*
					 * Thing > Intangible > Service > qux > quux > MortgageLoan
					 * 
					 * 
					 */

					function uamswp_fad_schema_mortgageloan(
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

			// PaymentCard

				/*
				 * Thing > Intangible > Service > qux > PaymentCard
				 * 
				 * 
				 */

				function uamswp_fad_schema_paymentcard(
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

			// PaymentService

				/*
				 * Thing > Intangible > Service > FinancialProduct > PaymentService
				 * 
				 * 
				 */

				function uamswp_fad_schema_paymentservice(
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

		// FoodService

			/*
			 * Thing > Intangible > Service > FoodService
			 * 
			 * 
			 */

			function uamswp_fad_schema_foodservice(
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

		// GovernmentService

			/*
			 * Thing > Intangible > Service > GovernmentService
			 * 
			 * 
			 */

			function uamswp_fad_schema_governmentservice(
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

		// Taxi

			/*
			 * Thing > Intangible > Service > Taxi
			 * 
			 * 
			 */

			function uamswp_fad_schema_taxi(
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

		// TaxiService

			/*
			 * Thing > Intangible > Service > TaxiService
			 * 
			 * 
			 */

			function uamswp_fad_schema_taxiservice(
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

		// WebAPI

			/*
			 * Thing > Intangible > Service > WebAPI
			 * 
			 * 
			 */

			function uamswp_fad_schema_webapi(
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

	// ServiceChannel
	include_once __DIR__ . '/Intangible/ServiceChannel.php';

		/*
		 * Thing > Intangible > ServiceChannel
		 * 
		 * 
		 */

		function uamswp_fad_schema_servicechannel(
			$schema, // array // Main schema array
			// ServiceChannel
				$foo = '', // foo
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
	
				// Properties from ServiceChannel
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from ServiceChannel
	
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

	// SpeakableSpecification
	include_once __DIR__ . '/Intangible/SpeakableSpecification.php';

		/*
		 * Thing > Intangible > SpeakableSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_speakablespecification(
			$schema, // array // Main schema array
			// SpeakableSpecification
				$foo = '', // foo
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
	
				// Properties from SpeakableSpecification
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from SpeakableSpecification
	
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

	// StatisticalPopulation
	include_once __DIR__ . '/Intangible/StatisticalPopulation.php';

		/*
		 * Thing > Intangible > StatisticalPopulation
		 * 
		 * 
		 */

		function uamswp_fad_schema_statisticalpopulation(
			$schema, // array // Main schema array
			// StatisticalPopulation
				$foo = '', // foo
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
	
				// Properties from StatisticalPopulation
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from StatisticalPopulation
	
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

	// StructuredValue
	include_once __DIR__ . '/Intangible/StructuredValue.php';

	// Ticket
	include_once __DIR__ . '/Intangible/Ticket.php';

		/*
		 * Thing > Intangible > Ticket
		 * 
		 * 
		 */

		function uamswp_fad_schema_ticket(
			$schema, // array // Main schema array
			// Ticket
				$foo = '', // foo
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
	
				// Properties from Ticket
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Ticket
	
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

	// Trip
	include_once __DIR__ . '/Intangible/Trip.php';

		/*
		 * Thing > Intangible > Trip
		 * 
		 * 
		 */

		function uamswp_fad_schema_trip(
			$schema, // array // Main schema array
			// Trip
				$foo = '', // foo
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
	
				// Properties from Trip
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from Trip
	
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

		// BoatTrip

			/*
			 * Thing > Intangible > Trip > BoatTrip
			 * 
			 * 
			 */

			function uamswp_fad_schema_boattrip(
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

		// BusTrip

			/*
			 * Thing > Intangible > Trip > BusTrip
			 * 
			 * 
			 */

			function uamswp_fad_schema_bustrip(
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

		// Flight

			/*
			 * Thing > Intangible > Trip > Flight
			 * 
			 * 
			 */

			function uamswp_fad_schema_flight(
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

		// TouristTrip

			/*
			 * Thing > Intangible > Trip > TouristTrip
			 * 
			 * 
			 */

			function uamswp_fad_schema_touristtrip(
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

		// TrainTrip

			/*
			 * Thing > Intangible > Trip > TrainTrip
			 * 
			 * 
			 */

			function uamswp_fad_schema_traintrip(
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

	// VirtualLocation
	include_once __DIR__ . '/Intangible/VirtualLocation.php';

		/*
		 * Thing > Intangible > VirtualLocation
		 * 
		 * 
		 */

		function uamswp_fad_schema_virtuallocation(
			$schema, // array // Main schema array
			// VirtualLocation
				$foo = '', // foo
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
	
				// Properties from VirtualLocation
	
					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';
	
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
	
				// Properties from VirtualLocation
	
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