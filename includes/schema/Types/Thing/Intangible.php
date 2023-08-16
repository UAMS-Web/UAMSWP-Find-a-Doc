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

				// Do nothing (no property vars)

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

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
			
		) {
			
		}

	// AlignmentObject
	include_once __DIR__ . '/Intangible/AlignmentObject.php';

		/*
		 * Thing > Intangible > AlignmentObject
		 * 
		 * 
		 */

		function uamswp_fad_schema_alignmentobject(
			
		) {
			
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

						$schema['audienceType'] = $audienceType;

					// geographicArea

						/* 
						 * Expected Type:
						 *     Thing > Place > AdministrativeArea
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
							 *     DataType > Text
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
							 *     Thing > Intangible > StructuredValue > QuantitativeValue
							 * 
							 * The age or age range for the intended audience or person, for example 3-12 
							 * months for infants, 1-5 years for toddlers.
							 */

							$schema['suggestedAge'] = $suggestedAge;

						// suggestedGender

							/* 
							 * Expected Type:
							 *     GenderType
							 *     DataType > Text
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
							 *     Thing > Intangible > StructuredValue > QuantitativeValue
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
	include_once __DIR__ . '/Intangible/BedDetails.php';

		/*
		 * Thing > Intangible > BedDetails
		 * 
		 * 
		 */

		function uamswp_fad_schema_beddetails(
			
		) {
			
		}

	// Brand
	include_once __DIR__ . '/Intangible/Brand.php';

		/*
		 * Thing > Intangible > Brand
		 * 
		 * 
		 */

		function uamswp_fad_schema_brand(
			
		) {
			
		}

	// BroadcastChannel
	include_once __DIR__ . '/Intangible/BroadcastChannel.php';

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
	include_once __DIR__ . '/Intangible/BroadcastFrequencySpecification.php';

		/*
		 * Thing > Intangible > BroadcastFrequencySpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_broadcastfrequencyspecification(
			
		) {
			
		}

	// Class
	include_once __DIR__ . '/Intangible/Class.php';

		/*
		 * Thing > Intangible > Class
		 * 
		 * 
		 */

		function uamswp_fad_schema_class(
			
		) {
			
		}

	// ComputerLanguage
	include_once __DIR__ . '/Intangible/ComputerLanguage.php';

		/*
		 * Thing > Intangible > ComputerLanguage
		 * 
		 * 
		 */

		function uamswp_fad_schema_computerlanguage(
			
		) {
			
		}

	// ConstraintNode
	include_once __DIR__ . '/Intangible/ConstraintNode.php';

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
	include_once __DIR__ . '/Intangible/DataFeedItem.php';

		/*
		 * Thing > Intangible > DataFeedItem
		 * 
		 * 
		 */

		function uamswp_fad_schema_datafeeditem(
			
		) {
			
		}

	// DefinedTerm
	include_once __DIR__ . '/Intangible/DefinedTerm.php';

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
	include_once __DIR__ . '/Intangible/Demand.php';

		/*
		 * Thing > Intangible > Demand
		 * 
		 * 
		 */

		function uamswp_fad_schema_demand(
			
		) {
			
		}

	// DigitalDocumentPermission
	include_once __DIR__ . '/Intangible/DigitalDocumentPermission.php';

		/*
		 * Thing > Intangible > DigitalDocumentPermission
		 * 
		 * 
		 */

		function uamswp_fad_schema_digitaldocumentpermission(
			
		) {
			
		}

	// EducationalOccupationalProgram
	include_once __DIR__ . '/Intangible/EducationalOccupationalProgram.php';

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
	include_once __DIR__ . '/Intangible/EnergyConsumptionDetails.php';

		/*
		 * Thing > Intangible > EnergyConsumptionDetails
		 * 
		 * 
		 */

		function uamswp_fad_schema_energyconsumptiondetails(
			
		) {
			
		}

	// EntryPoint
	include_once __DIR__ . '/Intangible/EntryPoint.php';

		/*
		 * Thing > Intangible > EntryPoint
		 * 
		 * 
		 */

		function uamswp_fad_schema_entrypoint(
			
		) {
			
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
			
		) {
			
		}

	// GameServer
	include_once __DIR__ . '/Intangible/GameServer.php';

		/*
		 * Thing > Intangible > GameServer
		 * 
		 * 
		 */

		function uamswp_fad_schema_gameserver(
			
		) {
			
		}

	// GeospatialGeometry
	include_once __DIR__ . '/Intangible/GeospatialGeometry.php';

		/*
		 * Thing > Intangible > GeospatialGeometry
		 * 
		 * 
		 */

		function uamswp_fad_schema_geospatialgeometry(
			
		) {
			
		}

	// Grant
	include_once __DIR__ . '/Intangible/Grant.php';

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
	include_once __DIR__ . '/Intangible/HealthInsurancePlan.php';

		/*
		 * Thing > Intangible > HealthInsurancePlan
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthinsuranceplan(
			
		) {
			
		}

	// HealthPlanCostSharingSpecification
	include_once __DIR__ . '/Intangible/HealthPlanCostSharingSpecification.php';

		/*
		 * Thing > Intangible > HealthPlanCostSharingSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthplancostsharingspecification(
			
		) {
			
		}

	// HealthPlanFormulary
	include_once __DIR__ . '/Intangible/HealthPlanFormulary.php';

		/*
		 * Thing > Intangible > HealthPlanFormulary
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthplanformulary(
			
		) {
			
		}

	// HealthPlanNetwork
	include_once __DIR__ . '/Intangible/HealthPlanNetwork.php';

		/*
		 * Thing > Intangible > HealthPlanNetwork
		 * 
		 * 
		 */

		function uamswp_fad_schema_healthplannetwork(
			
		) {
			
		}

	// Invoice
	include_once __DIR__ . '/Intangible/Invoice.php';

		/*
		 * Thing > Intangible > Invoice
		 * 
		 * 
		 */

		function uamswp_fad_schema_invoice(
			
		) {
			
		}

	// ItemList
	include_once __DIR__ . '/Intangible/ItemList.php';

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
	include_once __DIR__ . '/Intangible/JobPosting.php';

		/*
		 * Thing > Intangible > JobPosting
		 * 
		 * 
		 */

		function uamswp_fad_schema_jobposting(
			
		) {
			
		}

	// Language
	include_once __DIR__ . '/Intangible/Language.php';

		/*
		 * Thing > Intangible > Language
		 * 
		 * 
		 */

		function uamswp_fad_schema_language(
			
		) {
			
		}

	// ListItem
	include_once __DIR__ . '/Intangible/ListItem.php';

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
	include_once __DIR__ . '/Intangible/MediaSubscription.php';

		/*
		 * Thing > Intangible > MediaSubscription
		 * 
		 * 
		 */

		function uamswp_fad_schema_mediasubscription(
			
		) {
			
		}

	// MenuItem
	include_once __DIR__ . '/Intangible/MenuItem.php';

		/*
		 * Thing > Intangible > MenuItem
		 * 
		 * 
		 */

		function uamswp_fad_schema_menuitem(
			
		) {
			
		}

	// MerchantReturnPolicy
	include_once __DIR__ . '/Intangible/MerchantReturnPolicy.php';

		/*
		 * Thing > Intangible > MerchantReturnPolicy
		 * 
		 * 
		 */

		function uamswp_fad_schema_merchantreturnpolicy(
			
		) {
			
		}

	// MerchantReturnPolicySeasonalOverride
	include_once __DIR__ . '/Intangible/MerchantReturnPolicySeasonalOverride.php';

		/*
		 * Thing > Intangible > MerchantReturnPolicySeasonalOverride
		 * 
		 * 
		 */

		function uamswp_fad_schema_merchantreturnpolicyseasonaloverride(
			
		) {
			
		}

	// Observation
	include_once __DIR__ . '/Intangible/Observation.php';

		/*
		 * Thing > Intangible > Observation
		 * 
		 * 
		 */

		function uamswp_fad_schema_observation(
			
		) {
			
		}

	// Occupation
	include_once __DIR__ . '/Intangible/Occupation.php';

		/*
		 * Thing > Intangible > Occupation
		 * 
		 * 
		 */

		function uamswp_fad_schema_occupation(
			
		) {
			
		}

	// OccupationalExperienceRequirements
	include_once __DIR__ . '/Intangible/OccupationalExperienceRequirements.php';

		/*
		 * Thing > Intangible > OccupationalExperienceRequirements
		 * 
		 * 
		 */

		function uamswp_fad_schema_occupationalexperiencerequirements(
			
		) {
			
		}

	// Offer
	include_once __DIR__ . '/Intangible/Offer.php';

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
	include_once __DIR__ . '/Intangible/Order.php';

		/*
		 * Thing > Intangible > Order
		 * 
		 * 
		 */

		function uamswp_fad_schema_order(
			
		) {
			
		}

	// OrderItem
	include_once __DIR__ . '/Intangible/OrderItem.php';

		/*
		 * Thing > Intangible > OrderItem
		 * 
		 * 
		 */

		function uamswp_fad_schema_orderitem(
			
		) {
			
		}

	// ParcelDelivery
	include_once __DIR__ . '/Intangible/ParcelDelivery.php';

		/*
		 * Thing > Intangible > ParcelDelivery
		 * 
		 * 
		 */

		function uamswp_fad_schema_parceldelivery(
			
		) {
			
		}

	// Permit
	include_once __DIR__ . '/Intangible/Permit.php';

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
	include_once __DIR__ . '/Intangible/ProgramMembership.php';

		/*
		 * Thing > Intangible > ProgramMembership
		 * 
		 * 
		 */

		function uamswp_fad_schema_programmembership(
			
		) {
			
		}

	// Property
	include_once __DIR__ . '/Intangible/Property.php';

		/*
		 * Thing > Intangible > Property
		 * 
		 * 
		 */

		function uamswp_fad_schema_property(
			
		) {
			
		}

	// PropertyValueSpecification
	include_once __DIR__ . '/Intangible/PropertyValueSpecification.php';

		/*
		 * Thing > Intangible > PropertyValueSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_propertyvaluespecification(
			
		) {
			
		}

	// Quantity
	include_once __DIR__ . '/Intangible/Quantity.php';

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
	include_once __DIR__ . '/Intangible/Rating.php';

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
	include_once __DIR__ . '/Intangible/Reservation.php';

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
	include_once __DIR__ . '/Intangible/Role.php';

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
	include_once __DIR__ . '/Intangible/Schedule.php';

		/*
		 * Thing > Intangible > Schedule
		 * 
		 * 
		 */

		function uamswp_fad_schema_schedule(
			
		) {
			
		}

	// Seat
	include_once __DIR__ . '/Intangible/Seat.php';

		/*
		 * Thing > Intangible > Seat
		 * 
		 * 
		 */

		function uamswp_fad_schema_seat(
			
		) {
			
		}

	// Series
	include_once __DIR__ . '/Intangible/Series.php';

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
	include_once __DIR__ . '/Intangible/Service.php';

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
	include_once __DIR__ . '/Intangible/ServiceChannel.php';

		/*
		 * Thing > Intangible > ServiceChannel
		 * 
		 * 
		 */

		function uamswp_fad_schema_servicechannel(
			
		) {
			
		}

	// SpeakableSpecification
	include_once __DIR__ . '/Intangible/SpeakableSpecification.php';

		/*
		 * Thing > Intangible > SpeakableSpecification
		 * 
		 * 
		 */

		function uamswp_fad_schema_speakablespecification(
			
		) {
			
		}

	// StatisticalPopulation
	include_once __DIR__ . '/Intangible/StatisticalPopulation.php';

		/*
		 * Thing > Intangible > StatisticalPopulation
		 * 
		 * 
		 */

		function uamswp_fad_schema_statisticalpopulation(
			
		) {
			
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
			
		) {
			
		}

	// Trip
	include_once __DIR__ . '/Intangible/Trip.php';

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
	include_once __DIR__ . '/Intangible/VirtualLocation.php';

		/*
		 * Thing > Intangible > VirtualLocation
		 * 
		 * 
		 */

		function uamswp_fad_schema_virtuallocation(
			
		) {
			
		}