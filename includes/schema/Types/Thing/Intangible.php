<?php

// Intangible

	/*
	 * Thing > Intangible
	 * 
	 * A utility class that serves as the umbrella for a number of 'intangible' things 
	 * such as quantities, structured values, etc.
	 */

	function uamswp_fad_schema_intangible(
		$schema, // array // Main schema array
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

			// Properties from Intangible (Thing > Intangible)

				// Do nothing (no property vars)

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_thing(
					$schema, // array // Main schema array
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

			// Properties from Intangible

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

	// ActionAccessSpecification

		/*
		 * Thing > Intangible > ActionAccessSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_actionaccessspecification(
			
		) {
			
		}

	// AlignmentObject

		/*
		 * Thing > Intangible > AlignmentObject
		 * 
		 * 
		 */

		function uamswp_fad_schema_alignmentobject(
			
		) {
			
		}

	// Audience

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
						 *     Text
						 * 
						 * The target group associated with a given audience (e.g., veterans, car owners, 
						 * musicians).
						 */

						$schema['audienceType'] = $audienceType;

					// geographicArea

						/* 
						 * Expected Type:
						 *     AdministrativeArea
						 * 
						 * The geographic area associated with the audience.
						 */

						$schema['geographicArea'] = $geographicArea;

			// Remove any empty values from the schema array

				$schema = array_filter($schema);

			return $schema;

		}

		// BusinessAudience

			/*
			 * Thing > Intangible > Audience > BusinessAudience
			 * 
			 * 
			 */

			function uamswp_fad_schema_businessaudience(
				
			) {
				
			}

		// EducationalAudience

			/*
			 * Thing > Intangible > Audience > EducationalAudience
			 * 
			 * 
			 */

			function uamswp_fad_schema_educationalaudience(
				
			) {
				
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

								$schema['diagnosis'] = $diagnosis;

							// drug

								/* 
								 * Expected Type:
								 *     Drug
								 * 
								 * Specifying a drug or medicine used in a medication procedure.
								 */

								$schema['drug'] = $drug;

							// healthCondition

								/* 
								 * Expected Type:
								 *     MedicalCondition
								 * 
								 * Specifying the health condition(s) of a patient, medical study, or other target 
								 * audience.
								 */

								$schema['healthCondition'] = $healthCondition;

					// Remove any empty values from the schema array

						$schema = array_filter($schema);

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

							$schema['healthCondition'] = $healthCondition;

						// requiredGender

							/* 
							 * Expected Type:
							 *     Text
							 * 
							 * Audiences defined by a person's gender.
							 */

							$schema['requiredGender'] = $requiredGender;

						// requiredMaxAge

							/* 
							 * Expected Type:
							 *     Integer
							 * 
							 * Audiences defined by a person's maximum age.
							 */

							$schema['requiredMaxAge'] = $requiredMaxAge;

						// requiredMinAge

							/* 
							 * Expected Type:
							 *     Integer
							 * 
							 * Audiences defined by a person's minimum age.
							 */

							$schema['requiredMinAge'] = $requiredMinAge;

						// suggestedAge

							/* 
							 * Expected Type:
							 *     QuantitativeValue
							 * 
							 * The age or age range for the intended audience or person, for example 3-12 
							 * months for infants, 1-5 years for toddlers.
							 */

							$schema['suggestedAge'] = $suggestedAge;

						// suggestedGender

							/* 
							 * Expected Type:
							 *     GenderType
							 *     Text
							 * 
							 * The suggested gender of the intended person or audience, for example "male", 
							 * "female", or "unisex".
							 */

							$schema['suggestedGender'] = $suggestedGender;

						// suggestedMaxAge

							/* 
							 * Expected Type:
							 *     Number
							 * 
							 * Maximum recommended age in years for the audience or user.
							 */

							$schema['suggestedMaxAge'] = $suggestedMaxAge;

						// suggestedMeasurement

							/* 
							 * Expected Type:
							 *     QuantitativeValue
							 * 
							 * A suggested range of body measurements for the intended audience or person, for 
							 * example inseam between 32 and 34 inches or height between 170 and 190 cm. 
							 * Typically found on a size chart for wearable products.
							 */

							$schema['suggestedMeasurement'] = $suggestedMeasurement;

						// suggestedMinAge

							/* 
							 * Expected Type:
							 *     Number
							 * 
							 * Minimum recommended age in years for the audience or user.
							 */

							$schema['suggestedMinAge'] = $suggestedMinAge;

				// Remove any empty values from the schema array

					$schema = array_filter($schema);

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
					
				) {
					
				}

		// Researcher

			/*
			 * Thing > Intangible > Audience > Researcher
			 * 
			 * 
			 */

			function uamswp_fad_schema_researcher(
				
			) {
				
			}

	// BedDetails

		/*
		 * Thing > Intangible > BedDetails
		 * 
		 * 
		 */

		function uamswp_fad_schema_beddetails(
			
		) {
			
		}

	// Brand

		/*
		 * Thing > Intangible > Brand
		 * 
		 * 
		 */

		function uamswp_fad_schema_brand(
			
		) {
			
		}

	// BroadcastChannel

		/*
		 * Thing > Intangible > BroadcastChannel
		 * 
		 * 
		 */

		function uamswp_fad_schema_broadcastchannel(
			
		) {
			
		}

		// RadioChannel

			/*
			 * Thing > Intangible > BroadcastChannel > RadioChannel
			 * 
			 * 
			 */

			function uamswp_fad_schema_radiochannel(
				
			) {
				
			}

			// AMRadioChannel

				/*
				 * Thing > Intangible > BroadcastChannel > RadioChannel > AMRadioChannel
				 * 
				 * 
				 */

				function uamswp_fad_schema_amradiochannel(
					
				) {
					
				}

			// FMRadioChannel

				/*
				 * Thing > Intangible > BroadcastChannel > RadioChannel > FMRadioChannel
				 * 
				 * 
				 */

				function uamswp_fad_schema_fmradiochannel(
					
				) {
					
				}

		// TelevisionChannel

			/*
			 * Thing > Intangible > BroadcastChannel > TelevisionChannel
			 * 
			 * 
			 */

			function uamswp_fad_schema_televisionchannel(
				
			) {
				
			}

	// BroadcastFrequencySpecification

		/*
		 * Thing > Intangible > BroadcastFrequencySpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_broadcastfrequencyspecification(
			
		) {
			
		}

	// Class

		/*
		 * Thing > Intangible > Class
		 * 
		 * 
		 */

		function uamswp_fad_schema_class(
			
		) {
			
		}

	// ComputerLanguage

		/*
		 * Thing > Intangible > ComputerLanguage
		 * 
		 * 
		 */

		function uamswp_fad_schema_computerlanguage(
			
		) {
			
		}

	// ConstraintNode

		/*
		 * Thing > Intangible > ConstraintNode
		 * 
		 * 
		 */

		function uamswp_fad_schema_constraintnode(
			
		) {
			
		}

		// StatisticalVariable

			/*
			 * Thing > Intangible > ConstraintNode > StatisticalVariable
			 * 
			 * 
			 */

			function uamswp_fad_schema_statisticalvariable(
				
			) {
				
			}

	// DataFeedItem

		/*
		 * Thing > Intangible > DataFeedItem
		 * 
		 * 
		 */

		function uamswp_fad_schema_datafeeditem(
			
		) {
			
		}

	// DefinedTerm

		/*
		 * Thing > Intangible > DefinedTerm
		 * 
		 * 
		 */

		function uamswp_fad_schema_definedterm(
			
		) {
			
		}

		// CategoryCode

			/*
			 * Thing > Intangible > DefinedTerm > CategoryCode
			 * 
			 * 
			 */

			function uamswp_fad_schema_categorycode(
				
			) {
				
			}

			// MedicalCode

				/*
				 * Thing > Intangible > DefinedTerm > CategoryCode > MedicalCode
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicalcode(
					
				) {
					
				}


	// Demand

		/*
		 * Thing > Intangible > Demand
		 * 
		 * 
		 */

		function uamswp_fad_schema_demand(
			
		) {
			
		}

	// DigitalDocumentPermission

		/*
		 * Thing > Intangible > DigitalDocumentPermission
		 * 
		 * 
		 */

		function uamswp_fad_schema_digitaldocumentpermission(
			
		) {
			
		}

	// EducationalOccupationalProgram

		/*
		 * Thing > Intangible > EducationalOccupationalProgram
		 * 
		 * 
		 */

		function uamswp_fad_schema_educationaloccupationalprogram(
			
		) {
			
		}

		// WorkBasedProgram

			/*
			 * Thing > Intangible > EducationalOccupationalProgram > WorkBasedProgram
			 * 
			 * 
			 */

			function uamswp_fad_schema_workbasedprogram(
				
			) {
				
			}

	// EnergyConsumptionDetails

		/*
		 * Thing > Intangible > EnergyConsumptionDetails
		 * 
		 * 
		 */

		function uamswp_fad_schema_energyconsumptiondetails(
			
		) {
			
		}

	// EntryPoint

		/*
		 * Thing > Intangible > EntryPoint
		 * 
		 * 
		 */

		function uamswp_fad_schema_entrypoint(
			
		) {
			
		}

	// Enumeration

		/*
		 * Thing > Intangible > Enumeration
		 * 
		 * Lists or enumerations — for example, a list of cuisines or music genres, etc.
		 */

		function uamswp_fad_schema_enumeration(
			
		) {
			
		}

		// AdultOrientedEnumeration

			/*
			 * Thing > Intangible > Enumeration > AdultOrientedEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_adultorientedenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > BoardingPolicyType
			 * 
			 * 
			 */

			function uamswp_fad_schema_boardingpolicytype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > BookFormatType
			 * 
			 * 
			 */

			function uamswp_fad_schema_bookformattype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > BusinessEntityType
			 * 
			 * 
			 */

			function uamswp_fad_schema_businessentitytype(
				
			) {
				
			}

		// BusinessFunction

			/*
			 * Thing > Intangible > Enumeration > BusinessFunction
			 * 
			 * 
			 */

			function uamswp_fad_schema_businessfunction(
				
			) {
				
			}

		// CarUsageType

			/*
			 * Thing > Intangible > Enumeration > CarUsageType
			 * 
			 * 
			 */

			function uamswp_fad_schema_carusagetype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > ContactPointOption
			 * 
			 * 
			 */

			function uamswp_fad_schema_contactpointoption(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > DayOfWeek
			 * 
			 * 
			 */

			function uamswp_fad_schema_dayofweek(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > DeliveryMethod
			 * 
			 * 
			 */

			function uamswp_fad_schema_deliverymethod(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > DigitalDocumentPermissionType
			 * 
			 * 
			 */

			function uamswp_fad_schema_digitaldocumentpermissiontype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > DigitalPlatformEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_digitalplatformenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > EnergyEfficiencyEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_energyefficiencyenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > EventAttendanceModeEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_eventattendancemodeenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > GameAvailabilityEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_gameavailabilityenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > GamePlayMode
			 * 
			 * 
			 */

			function uamswp_fad_schema_gameplaymode(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > GenderType
			 * 
			 * 
			 */

			function uamswp_fad_schema_gendertype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > GovernmentBenefitsType
			 * 
			 * 
			 */

			function uamswp_fad_schema_governmentbenefitstype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > HealthAspectEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_healthaspectenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > ItemAvailability
			 * 
			 * 
			 */

			function uamswp_fad_schema_itemavailability(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > ItemListOrderType
			 * 
			 * 
			 */

			function uamswp_fad_schema_itemlistordertype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > LegalValueLevel
			 * 
			 * 
			 */

			function uamswp_fad_schema_legalvaluelevel(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > MapCategoryType
			 * 
			 * 
			 */

			function uamswp_fad_schema_mapcategorytype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > MeasurementMethodEnum
			 * 
			 * 
			 */

			function uamswp_fad_schema_measurementmethodenum(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > MeasurementTypeEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_measurementtypeenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > MediaManipulationRatingEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_mediamanipulationratingenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration
			 * 
			 * Enumerations related to health and the practice of medicine: A concept that is 
			 * used to attribute a quality to another concept, as a qualifier, a collection of 
			 * items or a listing of all of the elements of a set in medicine practice.
			 */

			function uamswp_fad_schema_medicalenumeration(
				
			) {
				
			}

			// DrugCostCategory

				/*
				 * Thing > Intangible > Enumeration > MedicalEnumeration > DrugCostCategory
				 * 
				 * 
				 */

				function uamswp_fad_schema_drugcostcategory(
					
				) {
					
				}

				// ReimbursementCap

					/*
					 * Thing > Intangible > Enumeration > qux > quux > ReimbursementCap
					 * 
					 * 
					 */

					function uamswp_fad_schema_reimbursementcap(
						
					) {
						
					}

				// Retail

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Retail
					 * 
					 * 
					 */

					function uamswp_fad_schema_retail(
						
					) {
						
					}

				// Wholesale

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Wholesale
					 * 
					 * 
					 */

					function uamswp_fad_schema_wholesale(
						
					) {
						
					}

			// DrugPregnancyCategory

				/*
				 * Thing > Intangible > Enumeration > qux > DrugPregnancyCategory
				 * 
				 * 
				 */

				function uamswp_fad_schema_drugpregnancycategory(
					
				) {
					
				}

				// FDAcategoryA

					/*
					 * Thing > Intangible > Enumeration > qux > quux > FDAcategoryA
					 * 
					 * 
					 */

					function uamswp_fad_schema_fdacategorya(
						
					) {
						
					}

				// FDAcategoryB

					/*
					 * Thing > Intangible > Enumeration > qux > quux > FDAcategoryB
					 * 
					 * 
					 */

					function uamswp_fad_schema_fdacategoryb(
						
					) {
						
					}

				// FDAcategoryC

					/*
					 * Thing > Intangible > Enumeration > qux > quux > FDAcategoryC
					 * 
					 * 
					 */

					function uamswp_fad_schema_fdacategoryc(
						
					) {
						
					}

				// FDAcategoryD

					/*
					 * Thing > Intangible > Enumeration > qux > quux > FDAcategoryD
					 * 
					 * 
					 */

					function uamswp_fad_schema_fdacategoryd(
						
					) {
						
					}

				// FDAcategoryX

					/*
					 * Thing > Intangible > Enumeration > qux > quux > FDAcategoryX
					 * 
					 * 
					 */

					function uamswp_fad_schema_fdacategoryx(
						
					) {
						
					}

				// FDAnotEvaluated

					/*
					 * Thing > Intangible > Enumeration > qux > quux > FDAnotEvaluated
					 * 
					 * 
					 */

					function uamswp_fad_schema_fdanotevaluated(
						
					) {
						
					}

			// DrugPrescriptionStatus

				/*
				 * Thing > Intangible > Enumeration > qux > DrugPrescriptionStatus
				 * 
				 * 
				 */

				function uamswp_fad_schema_drugprescriptionstatus(
					
				) {
					
				}

				// OTC

					/*
					 * Thing > Intangible > Enumeration > qux > quux > OTC
					 * 
					 * 
					 */

					function uamswp_fad_schema_otc(
						
					) {
						
					}

				// PrescriptionOnly

					/*
					 * Thing > Intangible > Enumeration > qux > quux > PrescriptionOnly
					 * 
					 * 
					 */

					function uamswp_fad_schema_prescriptiononly(
						
					) {
						
					}

			// InfectiousAgentClass

				/*
				 * Thing > Intangible > Enumeration > qux > InfectiousAgentClass
				 * 
				 * 
				 */

				function uamswp_fad_schema_infectiousagentclass(
					
				) {
					
				}

				// Bacteria

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Bacteria
					 * 
					 * 
					 */

					function uamswp_fad_schema_bacteria(
						
					) {
						
					}

				// Fungus

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Fungus
					 * 
					 * 
					 */

					function uamswp_fad_schema_fungus(
						
					) {
						
					}

				// MulticellularParasite

					/*
					 * Thing > Intangible > Enumeration > qux > quux > MulticellularParasite
					 * 
					 * 
					 */

					function uamswp_fad_schema_multicellularparasite(
						
					) {
						
					}

				// Prion

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Prion
					 * 
					 * 
					 */

					function uamswp_fad_schema_prion(
						
					) {
						
					}

				// Protozoa

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Protozoa
					 * 
					 * 
					 */

					function uamswp_fad_schema_protozoa(
						
					) {
						
					}

				// Virus

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Virus
					 * 
					 * 
					 */

					function uamswp_fad_schema_virus(
						
					) {
						
					}

			// MedicalAudienceType

				/*
				 * Thing > Intangible > Enumeration > qux > MedicalAudienceType
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicalaudiencetype(
					
				) {
					
				}

				// Clinician

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Clinician
					 * 
					 * 
					 */

					function uamswp_fad_schema_clinician(
						
					) {
						
					}

				// MedicalResearcher

					/*
					 * Thing > Intangible > Enumeration > qux > quux > MedicalResearcher
					 * 
					 * 
					 */

					function uamswp_fad_schema_medicalresearcher(
						
					) {
						
					}

			// MedicalDevicePurpose

				/*
				 * Thing > Intangible > Enumeration > qux > MedicalDevicePurpose
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicaldevicepurpose(
					
				) {
					
				}

				// Diagnostic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Diagnostic
					 * 
					 * 
					 */

					function uamswp_fad_schema_diagnostic(
						
					) {
						
					}

				// Therapeutic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Therapeutic
					 * 
					 * 
					 */

					function uamswp_fad_schema_therapeutic(
						
					) {
						
					}

			// MedicalEvidenceLevel

				/*
				 * Thing > Intangible > Enumeration > qux > MedicalEvidenceLevel
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicalevidencelevel(
					
				) {
					
				}

				// EvidenceLevelA

					/*
					 * Thing > Intangible > Enumeration > qux > quux > EvidenceLevelA
					 * 
					 * 
					 */

					function uamswp_fad_schema_evidencelevela(
						
					) {
						
					}

				// EvidenceLevelB

					/*
					 * Thing > Intangible > Enumeration > qux > quux > EvidenceLevelB
					 * 
					 * 
					 */

					function uamswp_fad_schema_evidencelevelb(
						
					) {
						
					}

				// EvidenceLevelC

					/*
					 * Thing > Intangible > Enumeration > qux > quux > EvidenceLevelC
					 * 
					 * 
					 */

					function uamswp_fad_schema_evidencelevelc(
						
					) {
						
					}

			// MedicalImagingTechnique

				/*
				 * Thing > Intangible > Enumeration > qux > MedicalImagingTechnique
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicalimagingtechnique(
					
				) {
					
				}

				// CT

					/*
					 * Thing > Intangible > Enumeration > qux > quux > CT
					 * 
					 * 
					 */

					function uamswp_fad_schema_ct(
						
					) {
						
					}

				// MRI

					/*
					 * Thing > Intangible > Enumeration > qux > quux > MRI
					 * 
					 * 
					 */

					function uamswp_fad_schema_mri(
						
					) {
						
					}

				// PET

					/*
					 * Thing > Intangible > Enumeration > qux > quux > PET
					 * 
					 * 
					 */

					function uamswp_fad_schema_pet(
						
					) {
						
					}

				// Radiography

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Radiography
					 * 
					 * 
					 */

					function uamswp_fad_schema_radiography(
						
					) {
						
					}

				// Ultrasound

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Ultrasound
					 * 
					 * 
					 */

					function uamswp_fad_schema_ultrasound(
						
					) {
						
					}

				// XRay

					/*
					 * Thing > Intangible > Enumeration > qux > quux > XRay
					 * 
					 * 
					 */

					function uamswp_fad_schema_xray(
						
					) {
						
					}

			// MedicalObservationalStudyDesign

				/*
				 * Thing > Intangible > Enumeration > qux > MedicalObservationalStudyDesign
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicalobservationalstudydesign(
					
				) {
					
				}

				// CaseSeries

					/*
					 * Thing > Intangible > Enumeration > qux > quux > CaseSeries
					 * 
					 * 
					 */

					function uamswp_fad_schema_caseseries(
						
					) {
						
					}

				// CohortStudy

					/*
					 * Thing > Intangible > Enumeration > qux > quux > CohortStudy
					 * 
					 * 
					 */

					function uamswp_fad_schema_cohortstudy(
						
					) {
						
					}

				// CrossSectional

					/*
					 * Thing > Intangible > Enumeration > qux > quux > CrossSectional
					 * 
					 * 
					 */

					function uamswp_fad_schema_crosssectional(
						
					) {
						
					}

				// Longitudinal

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Longitudinal
					 * 
					 * 
					 */

					function uamswp_fad_schema_longitudinal(
						
					) {
						
					}

				// Observational

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Observational
					 * 
					 * 
					 */

					function uamswp_fad_schema_observational(
						
					) {
						
					}

				// Registry

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Registry
					 * 
					 * 
					 */

					function uamswp_fad_schema_registry(
						
					) {
						
					}

			// MedicalProcedureType

				/*
				 * Thing > Intangible > Enumeration > qux > MedicalProcedureType
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicalproceduretype(
					
				) {
					
				}

				// NoninvasiveProcedure

					/*
					 * Thing > Intangible > Enumeration > qux > quux > NoninvasiveProcedure
					 * 
					 * 
					 */

					function uamswp_fad_schema_noninvasiveprocedure(
						
					) {
						
					}

				// PercutaneousProcedure

					/*
					 * Thing > Intangible > Enumeration > qux > quux > PercutaneousProcedure
					 * 
					 * 
					 */

					function uamswp_fad_schema_percutaneousprocedure(
						
					) {
						
					}

			// MedicalSpecialty

				/*
				 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty
				 * 
				 *     Also: Thing > Intangible > Enumeration > Specialty > MedicalSpecialty
				 * 
				 * Any specific branch of medical science or practice. Medical specialities 
				 * include clinical specialties that pertain to particular organ systems and 
				 * their respective disease states, as well as allied health specialties. 
				 * Enumerated type.
				 * 
				 * Enumeration members:
				 * 
				 *     Anesthesia
				 *     Cardiovascular
				 *     CommunityHealth
				 *     Dentistry
				 *     Dermatology
				 *     DietNutrition
				 *     Emergency
				 *     Endocrine
				 *     Gastroenterologic
				 *     Genetic
				 *     Geriatric
				 *     Gynecologic
				 *     Hematologic
				 *     Infectious
				 *     LaboratoryScience
				 *     Midwifery
				 *     Musculoskeletal
				 *     Neurologic
				 *     Nursing
				 *     Obstetric
				 *     Oncologic
				 *     Optometric
				 *     Otolaryngologic
				 *     Pathology
				 *     Pediatric
				 *     PharmacySpecialty
				 *     Physiotherapy
				 *     PlasticSurgery
				 *     Podiatric
				 *     PrimaryCare
				 *     Psychiatric
				 *     PublicHealth
				 *     Pulmonary
				 *     Radiography
				 *     Renal
				 *     RespiratoryTherapy
				 *     Rheumatologic
				 *     SpeechPathology
				 *     Surgical
				 *     Toxicologic
				 *     Urologic
				 */

				function uamswp_fad_schema_medicalspecialty(
					
				) {
					
				}

				// Anesthesia

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Anesthesia
					 * 
					 * 
					 */

					function uamswp_fad_schema_anesthesia(
						
					) {
						
					}

				// Cardiovascular

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Cardiovascular
					 * 
					 * 
					 */

					function uamswp_fad_schema_cardiovascular(
						
					) {
						
					}

				// CommunityHealth

					/*
					 * Thing > Intangible > Enumeration > qux > quux > CommunityHealth
					 * 
					 * 
					 */

					function uamswp_fad_schema_communityhealth(
						
					) {
						
					}

				// Dentistry

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Dentistry
					 * 
					 * 
					 */

					function uamswp_fad_schema_dentistry(
						
					) {
						
					}

				// Dermatologic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Dermatologic
					 * 
					 * 
					 */

					function uamswp_fad_schema_dermatologic(
						
					) {
						
					}

				// Dermatology

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Dermatology
					 * 
					 * 
					 */

					function uamswp_fad_schema_dermatology(
						
					) {
						
					}

				// DietNutrition

					/*
					 * Thing > Intangible > Enumeration > qux > quux > DietNutrition
					 * 
					 * 
					 */

					function uamswp_fad_schema_dietnutrition(
						
					) {
						
					}

				// Emergency

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Emergency
					 * 
					 * 
					 */

					function uamswp_fad_schema_emergency(
						
					) {
						
					}

				// Endocrine

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Endocrine
					 * 
					 * 
					 */

					function uamswp_fad_schema_endocrine(
						
					) {
						
					}

				// Gastroenterologic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Gastroenterologic
					 * 
					 * 
					 */

					function uamswp_fad_schema_gastroenterologic(
						
					) {
						
					}

				// Genetic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Genetic
					 * 
					 * 
					 */

					function uamswp_fad_schema_genetic(
						
					) {
						
					}

				// Geriatric

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Geriatric
					 * 
					 * 
					 */

					function uamswp_fad_schema_geriatric(
						
					) {
						
					}

				// Gynecologic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Gynecologic
					 * 
					 * 
					 */

					function uamswp_fad_schema_gynecologic(
						
					) {
						
					}

				// Hematologic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Hematologic
					 * 
					 * 
					 */

					function uamswp_fad_schema_hematologic(
						
					) {
						
					}

				// Infectious

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Infectious
					 * 
					 * 
					 */

					function uamswp_fad_schema_infectious(
						
					) {
						
					}

				// LaboratoryScience

					/*
					 * Thing > Intangible > Enumeration > qux > quux > LaboratoryScience
					 * 
					 * 
					 */

					function uamswp_fad_schema_laboratoryscience(
						
					) {
						
					}

				// Midwifery

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Midwifery
					 * 
					 * 
					 */

					function uamswp_fad_schema_midwifery(
						
					) {
						
					}

				// Musculoskeletal

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Musculoskeletal
					 * 
					 * 
					 */

					function uamswp_fad_schema_musculoskeletal(
						
					) {
						
					}

				// Neurologic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Neurologic
					 * 
					 * 
					 */

					function uamswp_fad_schema_neurologic(
						
					) {
						
					}

				// Nursing

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Nursing
					 * 
					 * 
					 */

					function uamswp_fad_schema_nursing(
						
					) {
						
					}

				// Obstetric

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Obstetric
					 * 
					 * 
					 */

					function uamswp_fad_schema_obstetric(
						
					) {
						
					}

				// Oncologic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Oncologic
					 * 
					 * 
					 */

					function uamswp_fad_schema_oncologic(
						
					) {
						
					}

				// Optometric

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Optometric
					 * 
					 * 
					 */

					function uamswp_fad_schema_optometric(
						
					) {
						
					}

				// Otolaryngologic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Otolaryngologic
					 * 
					 * 
					 */

					function uamswp_fad_schema_otolaryngologic(
						
					) {
						
					}

				// Pathology

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Pathology
					 * 
					 * 
					 */

					function uamswp_fad_schema_pathology(
						
					) {
						
					}

				// Pediatric

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Pediatric
					 * 
					 * 
					 */

					function uamswp_fad_schema_pediatric(
						
					) {
						
					}

				// PharmacySpecialty

					/*
					 * Thing > Intangible > Enumeration > qux > quux > PharmacySpecialty
					 * 
					 * 
					 */

					function uamswp_fad_schema_pharmacyspecialty(
						
					) {
						
					}

				// Physiotherapy

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Physiotherapy
					 * 
					 * 
					 */

					function uamswp_fad_schema_physiotherapy(
						
					) {
						
					}

				// PlasticSurgery

					/*
					 * Thing > Intangible > Enumeration > qux > quux > PlasticSurgery
					 * 
					 * 
					 */

					function uamswp_fad_schema_plasticsurgery(
						
					) {
						
					}

				// Podiatric

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Podiatric
					 * 
					 * 
					 */

					function uamswp_fad_schema_podiatric(
						
					) {
						
					}

				// PrimaryCare

					/*
					 * Thing > Intangible > Enumeration > qux > quux > PrimaryCare
					 * 
					 * 
					 */

					function uamswp_fad_schema_primarycare(
						
					) {
						
					}

				// Psychiatric

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Psychiatric
					 * 
					 * 
					 */

					function uamswp_fad_schema_psychiatric(
						
					) {
						
					}

				// PublicHealth

					/*
					 * Thing > Intangible > Enumeration > qux > quux > PublicHealth
					 * 
					 * 
					 */

					function uamswp_fad_schema_publichealth(
						
					) {
						
					}

				// Pulmonary

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Pulmonary
					 * 
					 * 
					 */

					function uamswp_fad_schema_pulmonary(
						
					) {
						
					}

				// Radiography

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Radiography
					 * 
					 * 
					 */

					function uamswp_fad_schema_radiography(
						
					) {
						
					}

				// Renal

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Renal
					 * 
					 * 
					 */

					function uamswp_fad_schema_renal(
						
					) {
						
					}

				// RespiratoryTherapy

					/*
					 * Thing > Intangible > Enumeration > qux > quux > RespiratoryTherapy
					 * 
					 * 
					 */

					function uamswp_fad_schema_respiratorytherapy(
						
					) {
						
					}

				// Rheumatologic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Rheumatologic
					 * 
					 * 
					 */

					function uamswp_fad_schema_rheumatologic(
						
					) {
						
					}

				// SpeechPathology

					/*
					 * Thing > Intangible > Enumeration > qux > quux > SpeechPathology
					 * 
					 * 
					 */

					function uamswp_fad_schema_speechpathology(
						
					) {
						
					}

				// Surgical

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Surgical
					 * 
					 * 
					 */

					function uamswp_fad_schema_surgical(
						
					) {
						
					}

				// Toxicologic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Toxicologic
					 * 
					 * 
					 */

					function uamswp_fad_schema_toxicologic(
						
					) {
						
					}

				// Urologic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Urologic
					 * 
					 * 
					 */

					function uamswp_fad_schema_urologic(
						
					) {
						
					}

			// MedicalStudyStatus

				/*
				 * Thing > Intangible > Enumeration > qux > MedicalStudyStatus
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicalstudystatus(
					
				) {
					
				}

				// ActiveNotRecruiting

					/*
					 * Thing > Intangible > Enumeration > qux > quux > ActiveNotRecruiting
					 * 
					 * 
					 */

					function uamswp_fad_schema_activenotrecruiting(
						
					) {
						
					}

				// Completed

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Completed
					 * 
					 * 
					 */

					function uamswp_fad_schema_completed(
						
					) {
						
					}

				// EnrollingByInvitation

					/*
					 * Thing > Intangible > Enumeration > qux > quux > EnrollingByInvitation
					 * 
					 * 
					 */

					function uamswp_fad_schema_enrollingbyinvitation(
						
					) {
						
					}

				// NotYetRecruiting

					/*
					 * Thing > Intangible > Enumeration > qux > quux > NotYetRecruiting
					 * 
					 * 
					 */

					function uamswp_fad_schema_notyetrecruiting(
						
					) {
						
					}

				// Recruiting

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Recruiting
					 * 
					 * 
					 */

					function uamswp_fad_schema_recruiting(
						
					) {
						
					}

				// ResultsAvailable

					/*
					 * Thing > Intangible > Enumeration > qux > quux > ResultsAvailable
					 * 
					 * 
					 */

					function uamswp_fad_schema_resultsavailable(
						
					) {
						
					}

				// ResultsNotAvailable

					/*
					 * Thing > Intangible > Enumeration > qux > quux > ResultsNotAvailable
					 * 
					 * 
					 */

					function uamswp_fad_schema_resultsnotavailable(
						
					) {
						
					}

				// Suspended

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Suspended
					 * 
					 * 
					 */

					function uamswp_fad_schema_suspended(
						
					) {
						
					}

				// Terminated

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Terminated
					 * 
					 * 
					 */

					function uamswp_fad_schema_terminated(
						
					) {
						
					}

				// Withdrawn

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Withdrawn
					 * 
					 * 
					 */

					function uamswp_fad_schema_withdrawn(
						
					) {
						
					}

			// MedicalTrialDesign

				/*
				 * Thing > Intangible > Enumeration > qux > MedicalTrialDesign
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicaltrialdesign(
					
				) {
					
				}

				// DoubleBlindedTrial

					/*
					 * Thing > Intangible > Enumeration > qux > quux > DoubleBlindedTrial
					 * 
					 * 
					 */

					function uamswp_fad_schema_doubleblindedtrial(
						
					) {
						
					}

				// InternationalTrial

					/*
					 * Thing > Intangible > Enumeration > qux > quux > InternationalTrial
					 * 
					 * 
					 */

					function uamswp_fad_schema_internationaltrial(
						
					) {
						
					}

				// MultiCenterTrial

					/*
					 * Thing > Intangible > Enumeration > qux > quux > MultiCenterTrial
					 * 
					 * 
					 */

					function uamswp_fad_schema_multicentertrial(
						
					) {
						
					}

				// OpenTrial

					/*
					 * Thing > Intangible > Enumeration > qux > quux > OpenTrial
					 * 
					 * 
					 */

					function uamswp_fad_schema_opentrial(
						
					) {
						
					}

				// PlaceboControlledTrial

					/*
					 * Thing > Intangible > Enumeration > qux > quux > PlaceboControlledTrial
					 * 
					 * 
					 */

					function uamswp_fad_schema_placebocontrolledtrial(
						
					) {
						
					}

				// RandomizedTrial

					/*
					 * Thing > Intangible > Enumeration > qux > quux > RandomizedTrial
					 * 
					 * 
					 */

					function uamswp_fad_schema_randomizedtrial(
						
					) {
						
					}

				// SingleBlindedTrial

					/*
					 * Thing > Intangible > Enumeration > qux > quux > SingleBlindedTrial
					 * 
					 * 
					 */

					function uamswp_fad_schema_singleblindedtrial(
						
					) {
						
					}

				// SingleCenterTrial

					/*
					 * Thing > Intangible > Enumeration > qux > quux > SingleCenterTrial
					 * 
					 * 
					 */

					function uamswp_fad_schema_singlecentertrial(
						
					) {
						
					}

				// TripleBlindedTrial

					/*
					 * Thing > Intangible > Enumeration > qux > quux > TripleBlindedTrial
					 * 
					 * 
					 */

					function uamswp_fad_schema_tripleblindedtrial(
						
					) {
						
					}

			// MedicineSystem

				/*
				 * Thing > Intangible > Enumeration > qux > MedicineSystem
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicinesystem(
					
				) {
					
				}

				// Ayurvedic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Ayurvedic
					 * 
					 * 
					 */

					function uamswp_fad_schema_ayurvedic(
						
					) {
						
					}

				// Chiropractic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Chiropractic
					 * 
					 * 
					 */

					function uamswp_fad_schema_chiropractic(
						
					) {
						
					}

				// Homeopathic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Homeopathic
					 * 
					 * 
					 */

					function uamswp_fad_schema_homeopathic(
						
					) {
						
					}

				// Osteopathic

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Osteopathic
					 * 
					 * 
					 */

					function uamswp_fad_schema_osteopathic(
						
					) {
						
					}

				// TraditionalChinese

					/*
					 * Thing > Intangible > Enumeration > qux > quux > TraditionalChinese
					 * 
					 * 
					 */

					function uamswp_fad_schema_traditionalchinese(
						
					) {
						
					}

				// WesternConventional

					/*
					 * Thing > Intangible > Enumeration > qux > quux > WesternConventional
					 * 
					 * 
					 */

					function uamswp_fad_schema_westernconventional(
						
					) {
						
					}

			// PhysicalExam

				/*
				 * Thing > Intangible > Enumeration > qux > PhysicalExam
				 * 
				 * 
				 */

				function uamswp_fad_schema_physicalexam(
					
				) {
					
				}

				// Abdomen

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Abdomen
					 * 
					 * 
					 */

					function uamswp_fad_schema_abdomen(
						
					) {
						
					}

				// Appearance

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Appearance
					 * 
					 * 
					 */

					function uamswp_fad_schema_appearance(
						
					) {
						
					}

				// CardiovascularExam

					/*
					 * Thing > Intangible > Enumeration > qux > quux > CardiovascularExam
					 * 
					 * 
					 */

					function uamswp_fad_schema_cardiovascularexam(
						
					) {
						
					}

				// Ear

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Ear
					 * 
					 * 
					 */

					function uamswp_fad_schema_ear(
						
					) {
						
					}

				// Eye

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Eye
					 * 
					 * 
					 */

					function uamswp_fad_schema_eye(
						
					) {
						
					}

				// Genitourinary

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Genitourinary
					 * 
					 * 
					 */

					function uamswp_fad_schema_genitourinary(
						
					) {
						
					}

				// Head

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Head
					 * 
					 * 
					 */

					function uamswp_fad_schema_head(
						
					) {
						
					}

				// Lung

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Lung
					 * 
					 * 
					 */

					function uamswp_fad_schema_lung(
						
					) {
						
					}

				// MusculoskeletalExam

					/*
					 * Thing > Intangible > Enumeration > qux > quux > MusculoskeletalExam
					 * 
					 * 
					 */

					function uamswp_fad_schema_musculoskeletalexam(
						
					) {
						
					}

				// Neck

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Neck
					 * 
					 * 
					 */

					function uamswp_fad_schema_neck(
						
					) {
						
					}

				// Neuro

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Neuro
					 * 
					 * 
					 */

					function uamswp_fad_schema_neuro(
						
					) {
						
					}

				// Nose

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Nose
					 * 
					 * 
					 */

					function uamswp_fad_schema_nose(
						
					) {
						
					}

				// Skin

					/*
					 * Thing > Intangible > Enumeration > qux > quux > Skin
					 * 
					 * 
					 */

					function uamswp_fad_schema_skin(
						
					) {
						
					}

				// Throat

					/*
					 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Throat
					 * 
					 * 
					 */

					function uamswp_fad_schema_throat(
						
					) {
						
					}


		// MerchantReturnEnumeration

			/*
			 * Thing > Intangible > Enumeration > MerchantReturnEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_merchantreturnenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumProductionType
			 * 
			 * 
			 */

			function uamswp_fad_schema_musicalbumproductiontype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > MusicAlbumReleaseType
			 * 
			 * 
			 */

			function uamswp_fad_schema_musicalbumreleasetype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > MusicReleaseFormatType
			 * 
			 * 
			 */

			function uamswp_fad_schema_musicreleaseformattype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > NonprofitType
			 * 
			 * 
			 */

			function uamswp_fad_schema_nonprofittype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > OfferItemCondition
			 * 
			 * 
			 */

			function uamswp_fad_schema_offeritemcondition(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > PaymentMethod
			 * 
			 * 
			 */

			function uamswp_fad_schema_paymentmethod(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > PhysicalActivityCategory
			 * 
			 * 
			 */

			function uamswp_fad_schema_physicalactivitycategory(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > PriceComponentTypeEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_pricecomponenttypeenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > PriceTypeEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_pricetypeenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > QualitativeValue
			 * 
			 * 
			 */

			function uamswp_fad_schema_qualitativevalue(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > RefundTypeEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_refundtypeenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > RestrictedDiet
			 * 
			 * 
			 */

			function uamswp_fad_schema_restricteddiet(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > ReturnFeesEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_returnfeesenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > ReturnLabelSourceEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_returnlabelsourceenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > ReturnMethodEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_returnmethodenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > RsvpResponseType
			 * 
			 * 
			 */

			function uamswp_fad_schema_rsvpresponsetype(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > SizeGroupEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_sizegroupenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > SizeSystemEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_sizesystemenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > StatusEnumeration
			 * 
			 * 
			 */

			function uamswp_fad_schema_statusenumeration(
				
			) {
				
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

			/*
			 * Thing > Intangible > Enumeration > WarrantyScope
			 * 
			 * 
			 */

			function uamswp_fad_schema_warrantyscope(
				
			) {
				
			}

	// FloorPlan

		/*
		 * Thing > Intangible > FloorPlan
		 * 
		 * 
		 */

		function uamswp_fad_schema_floorplan(
			
		) {
			
		}

	// GameServer

		/*
		 * Thing > Intangible > GameServer
		 * 
		 * 
		 */

		function uamswp_fad_schema_gameserver(
			
		) {
			
		}

	// GeospatialGeometry

		/*
		 * Thing > Intangible > GeospatialGeometry
		 * 
		 * 
		 */

		function uamswp_fad_schema_geospatialgeometry(
			
		) {
			
		}

	// Grant

		/*
		 * Thing > Intangible > Grant
		 * 
		 * 
		 */

		function uamswp_fad_schema_grant(
			
		) {
			
		}

		// MonetaryGrant

			/*
			 * Thing > Intangible > Grant > MonetaryGrant
			 * 
			 * 
			 */

			function uamswp_fad_schema_monetarygrant(
				
			) {
				
			}

	// HealthInsurancePlan

		/*
		 * Thing > Intangible > HealthInsurancePlan
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthinsuranceplan(
			
		) {
			
		}

	// HealthPlanCostSharingSpecification

		/*
		 * Thing > Intangible > HealthPlanCostSharingSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthplancostsharingspecification(
			
		) {
			
		}

	// HealthPlanFormulary

		/*
		 * Thing > Intangible > HealthPlanFormulary
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthplanformulary(
			
		) {
			
		}

	// HealthPlanNetwork

		/*
		 * Thing > Intangible > HealthPlanNetwork
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthplannetwork(
			
		) {
			
		}

	// Invoice

		/*
		 * Thing > Intangible > Invoice
		 * 
		 * 
		 */

		function uamswp_fad_schema_invoice(
			
		) {
			
		}

	// ItemList

		/*
		 * Thing > Intangible > ItemList
		 * 
		 * 
		 */

		function uamswp_fad_schema_itemlist(
			
		) {
			
		}

		// BreadcrumbList

			/*
			 * Thing > Intangible > ItemList > BreadcrumbList
			 * 
			 * 
			 */

			function uamswp_fad_schema_breadcrumblist(
				
			) {
				
			}

		// HowToSection

			/*
			 * Thing > Intangible > ItemList > HowToSection
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtosection(
				
			) {
				
			}

		// HowToStep

			/*
			 * Thing > Intangible > ItemList > HowToStep
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtostep(
				
			) {
				
			}

		// OfferCatalog

			/*
			 * Thing > Intangible > ItemList > OfferCatalog
			 * 
			 * 
			 */

			function uamswp_fad_schema_offercatalog(
				
			) {
				
			}

	// JobPosting

		/*
		 * Thing > Intangible > JobPosting
		 * 
		 * 
		 */

		function uamswp_fad_schema_jobposting(
			
		) {
			
		}

	// Language

		/*
		 * Thing > Intangible > Language
		 * 
		 * 
		 */

		function uamswp_fad_schema_language(
			
		) {
			
		}

	// ListItem

		/*
		 * Thing > Intangible > ListItem
		 * 
		 * 
		 */

		function uamswp_fad_schema_listitem(
			
		) {
			
		}

		// HowToDirection

			/*
			 * Thing > Intangible > ListItem > HowToDirection
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtodirection(
				
			) {
				
			}

		// HowToItem

			/*
			 * Thing > Intangible > ListItem > HowToItem
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtoitem(
				
			) {
				
			}

			// HowToSupply

				/*
				 * Thing > Intangible > ListItem > HowToItem > HowToSupply
				 * 
				 * 
				 */

				function uamswp_fad_schema_howtosupply(
					
				) {
					
				}

			// HowToTool

				/*
				 * Thing > Intangible > ListItem > HowToItem > HowToTool
				 * 
				 * 
				 */

				function uamswp_fad_schema_howtotool(
					
				) {
					
				}

		// HowToSection

			/*
			 * Thing > Intangible > ListItem > HowToSection
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtosection(
				
			) {
				
			}

		// HowToStep

			/*
			 * Thing > Intangible > ListItem > HowToStep
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtostep(
				
			) {
				
			}

		// HowToTip

			/*
			 * Thing > Intangible > ListItem > HowToTip
			 * 
			 * 
			 */

			function uamswp_fad_schema_howtotip(
				
			) {
				
			}

	// MediaSubscription

		/*
		 * Thing > Intangible > MediaSubscription
		 * 
		 * 
		 */

		function uamswp_fad_schema_mediasubscription(
			
		) {
			
		}

	// MenuItem

		/*
		 * Thing > Intangible > MenuItem
		 * 
		 * 
		 */

		function uamswp_fad_schema_menuitem(
			
		) {
			
		}

	// MerchantReturnPolicy

		/*
		 * Thing > Intangible > MerchantReturnPolicy
		 * 
		 * 
		 */

		function uamswp_fad_schema_merchantreturnpolicy(
			
		) {
			
		}

	// MerchantReturnPolicySeasonalOverride

		/*
		 * Thing > Intangible > MerchantReturnPolicySeasonalOverride
		 * 
		 * 
		 */

		function uamswp_fad_schema_merchantreturnpolicyseasonaloverride(
			
		) {
			
		}

	// Observation

		/*
		 * Thing > Intangible > Observation
		 * 
		 * 
		 */

		function uamswp_fad_schema_observation(
			
		) {
			
		}

	// Occupation

		/*
		 * Thing > Intangible > Occupation
		 * 
		 * 
		 */

		function uamswp_fad_schema_occupation(
			
		) {
			
		}

	// OccupationalExperienceRequirements

		/*
		 * Thing > Intangible > OccupationalExperienceRequirements
		 * 
		 * 
		 */

		function uamswp_fad_schema_occupationalexperiencerequirements(
			
		) {
			
		}

	// Offer

		/*
		 * Thing > Intangible > Offer
		 * 
		 * 
		 */

		function uamswp_fad_schema_offer(
			
		) {
			
		}

		// AggregateOffer

			/*
			 * Thing > Intangible > Offer > AggregateOffer
			 * 
			 * 
			 */

			function uamswp_fad_schema_aggregateoffer(
				
			) {
				
			}

		// OfferForLease

			/*
			 * Thing > Intangible > Offer > OfferForLease
			 * 
			 * 
			 */

			function uamswp_fad_schema_offerforlease(
				
			) {
				
			}

		// OfferForPurchase

			/*
			 * Thing > Intangible > Offer > OfferForPurchase
			 * 
			 * 
			 */

			function uamswp_fad_schema_offerforpurchase(
				
			) {
				
			}

	// Order

		/*
		 * Thing > Intangible > Order
		 * 
		 * 
		 */

		function uamswp_fad_schema_order(
			
		) {
			
		}

	// OrderItem

		/*
		 * Thing > Intangible > OrderItem
		 * 
		 * 
		 */

		function uamswp_fad_schema_orderitem(
			
		) {
			
		}

	// ParcelDelivery

		/*
		 * Thing > Intangible > ParcelDelivery
		 * 
		 * 
		 */

		function uamswp_fad_schema_parceldelivery(
			
		) {
			
		}

	// Permit

		/*
		 * Thing > Intangible > Permit
		 * 
		 * 
		 */

		function uamswp_fad_schema_permit(
			
		) {
			
		}

		// GovernmentPermit

			/*
			 * Thing > Intangible > Permit > GovernmentPermit
			 * 
			 * 
			 */

			function uamswp_fad_schema_governmentpermit(
				
			) {
				
			}

	// ProgramMembership

		/*
		 * Thing > Intangible > ProgramMembership
		 * 
		 * 
		 */

		function uamswp_fad_schema_programmembership(
			
		) {
			
		}

	// Property

		/*
		 * Thing > Intangible > Property
		 * 
		 * 
		 */

		function uamswp_fad_schema_property(
			
		) {
			
		}

	// PropertyValueSpecification

		/*
		 * Thing > Intangible > PropertyValueSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_propertyvaluespecification(
			
		) {
			
		}

	// Quantity

		/*
		 * Thing > Intangible > Quantity
		 * 
		 * 
		 */

		function uamswp_fad_schema_quantity(
			
		) {
			
		}

		// Distance

			/*
			 * Thing > Intangible > Quantity > Distance
			 * 
			 * 
			 */

			function uamswp_fad_schema_distance(
				
			) {
				
			}

		// Duration

			/*
			 * Thing > Intangible > Quantity > Duration
			 * 
			 * 
			 */

			function uamswp_fad_schema_duration(
				
			) {
				
			}

		// Energy

			/*
			 * Thing > Intangible > Quantity > Energy
			 * 
			 * 
			 */

			function uamswp_fad_schema_energy(
				
			) {
				
			}

		// Mass

			/*
			 * Thing > Intangible > Quantity > Mass
			 * 
			 * 
			 */

			function uamswp_fad_schema_mass(
				
			) {
				
			}

	// Rating

		/*
		 * Thing > Intangible > Rating
		 * 
		 * 
		 */

		function uamswp_fad_schema_rating(
			
		) {
			
		}

		// AggregateRating

			/*
			 * Thing > Intangible > Rating > AggregateRating
			 * 
			 * 
			 */

			function uamswp_fad_schema_aggregaterating(
				
			) {
				
			}

			// EmployerAggregateRating

				/*
				 * Thing > Intangible > Rating > AggregateRating > EmployerAggregateRating
				 * 
				 * 
				 */

				function uamswp_fad_schema_employeraggregaterating(
					
				) {
					
				}

		// EndorsementRating

			/*
			 * Thing > Intangible > Rating > EndorsementRating
			 * 
			 * 
			 */

			function uamswp_fad_schema_endorsementrating(
				
			) {
				
			}

	// Reservation

		/*
		 * Thing > Intangible > Reservation
		 * 
		 * 
		 */

		function uamswp_fad_schema_reservation(
			
		) {
			
		}

		// BoatReservation

			/*
			 * Thing > Intangible > Reservation > BoatReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_boatreservation(
				
			) {
				
			}

		// BusReservation

			/*
			 * Thing > Intangible > Reservation > BusReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_busreservation(
				
			) {
				
			}

		// EventReservation

			/*
			 * Thing > Intangible > Reservation > EventReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_eventreservation(
				
			) {
				
			}

		// FlightReservation

			/*
			 * Thing > Intangible > Reservation > FlightReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_flightreservation(
				
			) {
				
			}

		// FoodEstablishmentReservation

			/*
			 * Thing > Intangible > Reservation > FoodEstablishmentReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_foodestablishmentreservation(
				
			) {
				
			}

		// LodgingReservation

			/*
			 * Thing > Intangible > Reservation > LodgingReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_lodgingreservation(
				
			) {
				
			}

		// RentalCarReservation

			/*
			 * Thing > Intangible > Reservation > RentalCarReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_rentalcarreservation(
				
			) {
				
			}

		// ReservationPackage

			/*
			 * Thing > Intangible > Reservation > ReservationPackage
			 * 
			 * 
			 */

			function uamswp_fad_schema_reservationpackage(
				
			) {
				
			}

		// TaxiReservation

			/*
			 * Thing > Intangible > Reservation > TaxiReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_taxireservation(
				
			) {
				
			}

		// TrainReservation

			/*
			 * Thing > Intangible > Reservation > TrainReservation
			 * 
			 * 
			 */

			function uamswp_fad_schema_trainreservation(
				
			) {
				
			}

	// Role

		/*
		 * Thing > Intangible > Role
		 * 
		 * 
		 */

		function uamswp_fad_schema_role(
			
		) {
			
		}

		// LinkRole

			/*
			 * Thing > Intangible > Role > LinkRole
			 * 
			 * 
			 */

			function uamswp_fad_schema_linkrole(
				
			) {
				
			}

		// OrganizationRole

			/*
			 * Thing > Intangible > Role > OrganizationRole
			 * 
			 * 
			 */

			function uamswp_fad_schema_organizationrole(
				
			) {
				
			}

			// EmployeeRole

				/*
				 * Thing > Intangible > Role > OrganizationRole > EmployeeRole
				 * 
				 * 
				 */

				function uamswp_fad_schema_employeerole(
					
				) {
					
				}

		// PerformanceRole

			/*
			 * Thing > Intangible > Role > PerformanceRole
			 * 
			 * 
			 */

			function uamswp_fad_schema_performancerole(
				
			) {
				
			}

	// Schedule

		/*
		 * Thing > Intangible > Schedule
		 * 
		 * 
		 */

		function uamswp_fad_schema_schedule(
			
		) {
			
		}

	// Seat

		/*
		 * Thing > Intangible > Seat
		 * 
		 * 
		 */

		function uamswp_fad_schema_seat(
			
		) {
			
		}

	// Series

		/*
		 * Thing > Intangible > Series
		 * 
		 * 
		 */

		function uamswp_fad_schema_series(
			
		) {
			
		}

		// CreativeWorkSeries

			/*
			 * Thing > Intangible > Series > CreativeWorkSeries
			 * 
			 * 
			 */

			function uamswp_fad_schema_creativeworkseries(
				
			) {
				
			}

		// EventSeries

			/*
			 * Thing > Intangible > Series > EventSeries
			 * 
			 * 
			 */

			function uamswp_fad_schema_eventseries(
				
			) {
				
			}

	// Service

		/*
		 * Thing > Intangible > Service
		 * 
		 * 
		 */

		function uamswp_fad_schema_service(
			
		) {
			
		}

		// BroadcastService

			/*
			 * Thing > Intangible > Service > BroadcastService
			 * 
			 * 
			 */

			function uamswp_fad_schema_broadcastservice(
				
			) {
				
			}

			// RadioBroadcastService

				/*
				 * Thing > Intangible > Service > BroadcastService > RadioBroadcastService
				 * 
				 * 
				 */

				function uamswp_fad_schema_radiobroadcastservice(
					
				) {
					
				}

		// CableOrSatelliteService

			/*
			 * Thing > Intangible > Service > CableOrSatelliteService
			 * 
			 * 
			 */

			function uamswp_fad_schema_cableorsatelliteservice(
				
			) {
				
			}

		// FinancialProduct

			/*
			 * Thing > Intangible > Service > FinancialProduct
			 * 
			 * 
			 */

			function uamswp_fad_schema_financialproduct(
				
			) {
				
			}

			// BankAccount

				/*
				 * Thing > Intangible > Service > FinancialProduct > BankAccount
				 * 
				 * 
				 */

				function uamswp_fad_schema_bankaccount(
					
				) {
					
				}

				// DepositAccount

					/*
					 * Thing > Intangible > Service > qux > quux > DepositAccount
					 * 
					 * 
					 */

					function uamswp_fad_schema_depositaccount(
						
					) {
						
					}

			// CurrencyConversionService

				/*
				 * Thing > Intangible > Service > qux > CurrencyConversionService
				 * 
				 * 
				 */

				function uamswp_fad_schema_currencyconversionservice(
					
				) {
					
				}

			// InvestmentOrDeposit

				/*
				 * Thing > Intangible > Service > qux > InvestmentOrDeposit
				 * 
				 * 
				 */

				function uamswp_fad_schema_investmentordeposit(
					
				) {
					
				}

				// BrokerageAccount

					/*
					 * Thing > Intangible > Service > qux > quux > BrokerageAccount
					 * 
					 * 
					 */

					function uamswp_fad_schema_brokerageaccount(
						
					) {
						
					}

				// DepositAccount

					/*
					 * Thing > Intangible > Service > qux > quux > DepositAccount
					 * 
					 * 
					 */

					function uamswp_fad_schema_depositaccount(
						
					) {
						
					}

				// InvestmentFund

					/*
					 * Thing > Intangible > Service > qux > quux > InvestmentFund
					 * 
					 * 
					 */

					function uamswp_fad_schema_investmentfund(
						
					) {
						
					}

			// LoanOrCredit

				/*
				 * Thing > Intangible > Service > qux > LoanOrCredit
				 * 
				 * 
				 */

				function uamswp_fad_schema_loanorcredit(
					
				) {
					
				}

				// CreditCard

					/*
					 * Thing > Intangible > Service > qux > quux > CreditCard
					 * 
					 * 
					 */

					function uamswp_fad_schema_creditcard(
						
					) {
						
					}

				// MortgageLoan

					/*
					 * Thing > Intangible > Service > qux > quux > MortgageLoan
					 * 
					 * 
					 */

					function uamswp_fad_schema_mortgageloan(
						
					) {
						
					}

			// PaymentCard

				/*
				 * Thing > Intangible > Service > qux > PaymentCard
				 * 
				 * 
				 */

				function uamswp_fad_schema_paymentcard(
					
				) {
					
				}

			// PaymentService

				/*
				 * Thing > Intangible > Service > FinancialProduct > PaymentService
				 * 
				 * 
				 */

				function uamswp_fad_schema_paymentservice(
					
				) {
					
				}

		// FoodService

			/*
			 * Thing > Intangible > Service > FoodService
			 * 
			 * 
			 */

			function uamswp_fad_schema_foodservice(
				
			) {
				
			}

		// GovernmentService

			/*
			 * Thing > Intangible > Service > GovernmentService
			 * 
			 * 
			 */

			function uamswp_fad_schema_governmentservice(
				
			) {
				
			}

		// Taxi

			/*
			 * Thing > Intangible > Service > Taxi
			 * 
			 * 
			 */

			function uamswp_fad_schema_taxi(
				
			) {
				
			}

		// TaxiService

			/*
			 * Thing > Intangible > Service > TaxiService
			 * 
			 * 
			 */

			function uamswp_fad_schema_taxiservice(
				
			) {
				
			}

		// WebAPI

			/*
			 * Thing > Intangible > Service > WebAPI
			 * 
			 * 
			 */

			function uamswp_fad_schema_webapi(
				
			) {
				
			}

	// ServiceChannel

		/*
		 * Thing > Intangible > ServiceChannel
		 * 
		 * 
		 */

		function uamswp_fad_schema_servicechannel(
			
		) {
			
		}

	// SpeakableSpecification

		/*
		 * Thing > Intangible > SpeakableSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_speakablespecification(
			
		) {
			
		}

	// StatisticalPopulation

		/*
		 * Thing > Intangible > StatisticalPopulation
		 * 
		 * 
		 */

		function uamswp_fad_schema_statisticalpopulation(
			
		) {
			
		}

	// StructuredValue

		/*
		 * Thing > Intangible > StructuredValue
		 * 
		 * 
		 */

		function uamswp_fad_schema_structuredvalue(
			
		) {
			
		}

		// CDCPMDRecord

			/*
			 * Thing > Intangible > StructuredValue > CDCPMDRecord
			 * 
			 * 
			 */

			function uamswp_fad_schema_cdcpmdrecord(
				
			) {
				
			}

		// ContactPoint

			/*
			 * Thing > Intangible > StructuredValue > ContactPoint
			 * 
			 * 
			 */

			function uamswp_fad_schema_contactpoint(
				
			) {
				
			}

			// PostalAddress

				/*
				 * Thing > Intangible > StructuredValue > ContactPoint > PostalAddress
				 * 
				 * 
				 */

				function uamswp_fad_schema_postaladdress(
					
				) {
					
				}

		// DatedMoneySpecification

			/*
			 * Thing > Intangible > StructuredValue > DatedMoneySpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_datedmoneyspecification(
				
			) {
				
			}

		// DefinedRegion

			/*
			 * Thing > Intangible > StructuredValue > DefinedRegion
			 * 
			 * 
			 */

			function uamswp_fad_schema_definedregion(
				
			) {
				
			}

		// DeliveryTimeSettings

			/*
			 * Thing > Intangible > StructuredValue > DeliveryTimeSettings
			 * 
			 * 
			 */

			function uamswp_fad_schema_deliverytimesettings(
				
			) {
				
			}

		// EngineSpecification

			/*
			 * Thing > Intangible > StructuredValue > EngineSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_enginespecification(
				
			) {
				
			}

		// ExchangeRateSpecification

			/*
			 * Thing > Intangible > StructuredValue > ExchangeRateSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_exchangeratespecification(
				
			) {
				
			}

		// GeoCoordinates

			/*
			 * Thing > Intangible > StructuredValue > GeoCoordinates
			 * 
			 * 
			 */

			function uamswp_fad_schema_geocoordinates(
				
			) {
				
			}

		// GeoShape

			/*
			 * Thing > Intangible > StructuredValue > GeoShape
			 * 
			 * 
			 */

			function uamswp_fad_schema_geoshape(
				
			) {
				
			}

			// GeoCircle

				/*
				 * Thing > Intangible > StructuredValue > GeoShape > GeoCircle
				 * 
				 * 
				 */

				function uamswp_fad_schema_geocircle(
					
				) {
					
				}

		// InteractionCounter

			/*
			 * Thing > Intangible > StructuredValue > InteractionCounter
			 * 
			 * 
			 */

			function uamswp_fad_schema_interactioncounter(
				
			) {
				
			}

		// MonetaryAmount

			/*
			 * Thing > Intangible > StructuredValue > MonetaryAmount
			 * 
			 * 
			 */

			function uamswp_fad_schema_monetaryamount(
				
			) {
				
			}

		// NutritionInformation

			/*
			 * Thing > Intangible > StructuredValue > NutritionInformation
			 * 
			 * 
			 */

			function uamswp_fad_schema_nutritioninformation(
				
			) {
				
			}

		// OfferShippingDetails

			/*
			 * Thing > Intangible > StructuredValue > OfferShippingDetails
			 * 
			 * 
			 */

			function uamswp_fad_schema_offershippingdetails(
				
			) {
				
			}

		// OpeningHoursSpecification

			/*
			 * Thing > Intangible > StructuredValue > OpeningHoursSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_openinghoursspecification(
				
			) {
				
			}

		// OwnershipInfo

			/*
			 * Thing > Intangible > StructuredValue > OwnershipInfo
			 * 
			 * 
			 */

			function uamswp_fad_schema_ownershipinfo(
				
			) {
				
			}

		// PostalCodeRangeSpecification

			/*
			 * Thing > Intangible > StructuredValue > PostalCodeRangeSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_postalcoderangespecification(
				
			) {
				
			}

		// PriceSpecification

			/*
			 * Thing > Intangible > StructuredValue > PriceSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_pricespecification(
				
			) {
				
			}

			// CompoundPriceSpecification

				/*
				 * Thing > Intangible > StructuredValue > PriceSpecification > CompoundPriceSpecification
				 * 
				 * 
				 */

				function uamswp_fad_schema_compoundpricespecification(
					
				) {
					
				}

			// DeliveryChargeSpecification

				/*
				 * Thing > Intangible > StructuredValue > PriceSpecification > DeliveryChargeSpecification
				 * 
				 * 
				 */

				function uamswp_fad_schema_deliverychargespecification(
					
				) {
					
				}

			// PaymentChargeSpecification

				/*
				 * Thing > Intangible > StructuredValue > PriceSpecification > PaymentChargeSpecification
				 * 
				 * 
				 */

				function uamswp_fad_schema_paymentchargespecification(
					
				) {
					
				}

			// UnitPriceSpecification

				/*
				 * Thing > Intangible > StructuredValue > PriceSpecification > UnitPriceSpecification
				 * 
				 * 
				 */

				function uamswp_fad_schema_unitpricespecification(
					
				) {
					
				}

		// PropertyValue

			/*
			 * Thing > Intangible > StructuredValue > PropertyValue
			 * 
			 * 
			 */

			function uamswp_fad_schema_propertyvalue(
				
			) {
				
			}

			// LocationFeatureSpecification

				/*
				 * Thing > Intangible > StructuredValue > PropertyValue > LocationFeatureSpecification
				 * 
				 * 
				 */

				function uamswp_fad_schema_locationfeaturespecification(
					
				) {
					
				}

		// QuantitativeValue

			/*
			 * Thing > Intangible > StructuredValue > QuantitativeValue
			 * 
			 * 
			 */

			function uamswp_fad_schema_quantitativevalue(
				
			) {
				
			}

			// Observation

				/*
				 * Thing > Intangible > StructuredValue > QuantitativeValue > Observation
				 * 
				 * 
				 */

				function uamswp_fad_schema_observation(
					
				) {
					
				}

		// QuantitativeValueDistribution

			/*
			 * Thing > Intangible > StructuredValue > QuantitativeValueDistribution
			 * 
			 * 
			 */

			function uamswp_fad_schema_quantitativevaluedistribution(
				
			) {
				
			}

			// MonetaryAmountDistribution

				/*
				 * Thing > Intangible > StructuredValue > QuantitativeValueDistribution > MonetaryAmountDistribution
				 * 
				 * 
				 */

				function uamswp_fad_schema_monetaryamountdistribution(
					
				) {
					
				}

		// RepaymentSpecification

			/*
			 * Thing > Intangible > StructuredValue > RepaymentSpecification
			 * 
			 * 
			 */

			function uamswp_fad_schema_repaymentspecification(
				
			) {
				
			}

		// ShippingDeliveryTime

			/*
			 * Thing > Intangible > StructuredValue > ShippingDeliveryTime
			 * 
			 * 
			 */

			function uamswp_fad_schema_shippingdeliverytime(
				
			) {
				
			}

		// ShippingRateSettings

			/*
			 * Thing > Intangible > StructuredValue > ShippingRateSettings
			 * 
			 * 
			 */

			function uamswp_fad_schema_shippingratesettings(
				
			) {
				
			}

		// TypeAndQuantityNode

			/*
			 * Thing > Intangible > StructuredValue > TypeAndQuantityNode
			 * 
			 * 
			 */

			function uamswp_fad_schema_typeandquantitynode(
				
			) {
				
			}

		// WarrantyPromise

			/*
			 * Thing > Intangible > StructuredValue > WarrantyPromise
			 * 
			 * 
			 */

			function uamswp_fad_schema_warrantypromise(
				
			) {
				
			}

	// Ticket

		/*
		 * Thing > Intangible > Ticket
		 * 
		 * 
		 */

		function uamswp_fad_schema_ticket(
			
		) {
			
		}

	// Trip

		/*
		 * Thing > Intangible > Trip
		 * 
		 * 
		 */

		function uamswp_fad_schema_trip(
			
		) {
			
		}

		// BoatTrip

			/*
			 * Thing > Intangible > Trip > BoatTrip
			 * 
			 * 
			 */

			function uamswp_fad_schema_boattrip(
				
			) {
				
			}

		// BusTrip

			/*
			 * Thing > Intangible > Trip > BusTrip
			 * 
			 * 
			 */

			function uamswp_fad_schema_bustrip(
				
			) {
				
			}

		// Flight

			/*
			 * Thing > Intangible > Trip > Flight
			 * 
			 * 
			 */

			function uamswp_fad_schema_flight(
				
			) {
				
			}

		// TouristTrip

			/*
			 * Thing > Intangible > Trip > TouristTrip
			 * 
			 * 
			 */

			function uamswp_fad_schema_touristtrip(
				
			) {
				
			}

		// TrainTrip

			/*
			 * Thing > Intangible > Trip > TrainTrip
			 * 
			 * 
			 */

			function uamswp_fad_schema_traintrip(
				
			) {
				
			}

	// VirtualLocation

		/*
		 * Thing > Intangible > VirtualLocation
		 * 
		 * 
		 */

		function uamswp_fad_schema_virtuallocation(
			
		) {
			
		}