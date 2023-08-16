<?php

// Physician

	/*
	 * Thing > Organization > LocalBusiness > MedicalBusiness > Physician
	 * 
	 *     Also: Thing > Organization > MedicalOrganization > Physician
	 *     Also: Thing > Place > LocalBusiness > MedicalBusiness > Physician
	 * 
	 * A doctor's office.
	 */

	function uamswp_fad_schema_physician(
		array $schema, // Main schema array
		array $Physician // Properties from Physician
	) {

		/* 

		Expected format for the Physician properties array:

			$var = array(
				'type'			=> 'Physician',
				'properties'	=> array(
					// Physician
						$availableService					=> '', // availableService
						$hospitalAffiliation				=> '', // hospitalAffiliation
						$medicalSpecialty					=> '', // medicalSpecialty
					// MedicalBusiness (no property vars)
					// LocalBusiness
						$currenciesAccepted					=> '', // currenciesAccepted
						$openingHours						=> '', // openingHours
						$paymentAccepted					=> '', // paymentAccepted
						$priceRange							=> '', // priceRange
					// MedicalOrganization
						$healthPlanNetworkId				=> '', // healthPlanNetworkId
						$isAcceptingNewPatients				=> '', // isAcceptingNewPatients
						$medicalSpecialty					=> '', // medicalSpecialty
					// Organization
						$actionableFeedbackPolicy			=> '', // actionableFeedbackPolicy
						$address							=> '', // address
						$aggregateRating					=> '', // aggregateRating
						$alumni								=> '', // alumni
						$areaServed							=> '', // areaServed
						$award								=> '', // award
						$brand								=> '', // brand
						$contactPoint						=> '', // contactPoint
						$correctionsPolicy					=> '', // correctionsPolicy
						$department							=> '', // department
						$dissolutionDate					=> '', // dissolutionDate
						$diversityPolicy					=> '', // diversityPolicy
						$diversityStaffingReport			=> '', // diversityStaffingReport
						$duns								=> '', // duns
						$email								=> '', // email
						$employee							=> '', // employee
						$ethicsPolicy						=> '', // ethicsPolicy
						$event								=> '', // event
						$faxNumber							=> '', // faxNumber
						$founder							=> '', // founder
						$foundingDate						=> '', // foundingDate
						$foundingLocation					=> '', // foundingLocation
						$funder								=> '', // funder
						$funding							=> '', // funding
						$globalLocationNumber				=> '', // globalLocationNumber
						$hasCredential						=> '', // hasCredential
						$hasMerchantReturnPolicy			=> '', // hasMerchantReturnPolicy
						$hasOfferCatalog					=> '', // hasOfferCatalog
						$hasPOS								=> '', // hasPOS
						$interactionStatistic				=> '', // interactionStatistic
						$isicV4								=> '', // isicV4
						$iso6523Code						=> '', // iso6523Code
						$keywords							=> '', // keywords
						$knowsAbout							=> '', // knowsAbout
						$knowsLanguage						=> '', // knowsLanguage
						$legalName							=> '', // legalName
						$leiCode							=> '', // leiCode
						$location							=> '', // location
						$logo								=> '', // logo
						$makesOffer							=> '', // makesOffer
						$member								=> '', // member
						$memberOf							=> '', // memberOf
						$naics								=> '', // naics
						$nonprofitStatus					=> '', // nonprofitStatus
						$numberOfEmployees					=> '', // numberOfEmployees
						$ownershipFundingInfo				=> '', // ownershipFundingInfo
						$owns								=> '', // owns
						$parentOrganization					=> '', // parentOrganization
						$publishingPrinciples				=> '', // publishingPrinciples
						$review								=> '', // review
						$seeks								=> '', // seeks
						$slogan								=> '', // slogan
						$sponsor							=> '', // sponsor
						$subOrganization					=> '', // subOrganization
						$taxID								=> '', // taxID
						$telephone							=> '', // telephone
						$unnamedSourcesPolicy				=> '', // unnamedSourcesPolicy
						$vatID								=> '', // vatID
					// Place
						$additionalProperty					=> '', // additionalProperty
						$address							=> '', // address
						$aggregateRating					=> '', // aggregateRating
						$amenityFeature						=> '', // amenityFeature
						$branchCode							=> '', // branchCode
						$containedInPlace					=> '', // containedInPlace
						$containsPlace						=> '', // containsPlace
						$event								=> '', // event
						$faxNumber							=> '', // faxNumber
						$geo								=> '', // geo
						$geoContains						=> '', // geoContains
						$geoCoveredBy						=> '', // geoCoveredBy
						$geoCovers							=> '', // geoCovers
						$geoCrosses							=> '', // geoCrosses
						$geoDisjoint						=> '', // geoDisjoint
						$geoEquals							=> '', // geoEquals
						$geoIntersects						=> '', // geoIntersects
						$geoOverlaps						=> '', // geoOverlaps
						$geoTouches							=> '', // geoTouches
						$geoWithin							=> '', // geoWithin
						$globalLocationNumber				=> '', // globalLocationNumber
						$hasDriveThroughService				=> '', // hasDriveThroughService
						$hasMap								=> '', // hasMap
						$isAccessibleForFree				=> '', // isAccessibleForFree
						$isicV4								=> '', // isicV4
						$keywords							=> '', // keywords
						$latitude							=> '', // latitude
						$logo								=> '', // logo
						$longitude							=> '', // longitude
						$maximumAttendeeCapacity			=> '', // maximumAttendeeCapacity
						$openingHoursSpecification			=> '', // openingHoursSpecification
						$photo								=> '', // photo
						$publicAccess						=> '', // publicAccess
						$review								=> '', // review
						$slogan								=> '', // slogan
						$smokingAllowed						=> '', // smokingAllowed
						$specialOpeningHoursSpecification	=> '', // specialOpeningHoursSpecification
						$telephone							=> '', // telephone
						$tourBookingPage					=> '', // tourBookingPage
					// Thing
						$additionalType						=> '', // additionalType
						$alternateName						=> '', // alternateName
						$description						=> '', // description
						$disambiguatingDescription			=> '', // disambiguatingDescription
						$identifier							=> '', // identifier
						$image								=> '', // image
						$mainEntityOfPage					=> '', // mainEntityOfPage
						$name								=> '', // name
						$potentialAction					=> '', // potentialAction
						$sameAs								=> '', // sameAs
						$subjectOf							=> '', // subjectOf
						$url								=> '' // url
				)
			);

		 */

		// Check/define variables

			$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

			// Extract variables from properties array

				foreach ( $Physician['properties'] as $key => $value ) {

					${$key} = $value;
		
				}

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_medicalbusiness(
					$schema, // array // Main schema array
					$Physician // array // Properties from Physician
				);

				$schema = uamswp_fad_schema_medicalorganization(
					$schema, // array // Main schema array
					$Physician // array // Properties from Physician
				);

			// Properties from Physician

				// availableService

					/* 
					 * Expected Type:
					 *     Thing > MedicalEntity > MedicalProcedure
					 *     Thing > MedicalEntity > MedicalTest
					 * 
					 * A medical service available from this provider.
					 */

					$schema['availableService'] = ( isset($availableService) && !empty($availableService) ) ? uamswp_fad_schema_type_selector($availableService) : '';

				// hospitalAffiliation

					/* 
					 * Expected Type:
					 *     Thing > Place > CivicStructure > Hospital
					 * 
					 * A hospital with which the physician or office is affiliated.
					 */

					$schema['hospitalAffiliation'] = ( isset($hospitalAffiliation) && !empty($hospitalAffiliation) ) ? uamswp_fad_schema_type_selector($hospitalAffiliation) : '';

				// medicalSpecialty

					/* 
					 * Expected Type:
					 *     Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty
					 * 
					 * A medical specialty of the provider.
					 */

					$schema['medicalSpecialty'] = ( isset($medicalSpecialty) && !empty($medicalSpecialty) ) ? uamswp_fad_schema_type_selector($medicalSpecialty) : '';

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}

