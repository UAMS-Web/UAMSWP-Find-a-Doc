<?php

// EmergencyService

	/*
	 * Thing > Organization > LocalBusiness > EmergencyService
	 * 
	 *     Also: Thing > Place > LocalBusiness > EmergencyService
	 * 
	 * An emergency service, such as a fire station or ER.
	 */

	function uamswp_fad_schema_emergencyservice(
		$schema, // array // Main schema array
		// EmergencyService (no property vars)
		// LocalBusiness
			$currenciesAccepted = '', // currenciesAccepted
			$openingHours = '', // openingHours
			$paymentAccepted = '', // paymentAccepted
			$priceRange = '', // priceRange
		// Organization
			$actionableFeedbackPolicy = '', // actionableFeedbackPolicy
			$address = '', // address
			$aggregateRating = '', // aggregateRating
			$alumni = '', // alumni
			$areaServed = '', // areaServed
			$award = '', // award
			$brand = '', // brand
			$contactPoint = '', // contactPoint
			$correctionsPolicy = '', // correctionsPolicy
			$department = '', // department
			$dissolutionDate = '', // dissolutionDate
			$diversityPolicy = '', // diversityPolicy
			$diversityStaffingReport = '', // diversityStaffingReport
			$duns = '', // duns
			$email = '', // email
			$employee = '', // employee
			$ethicsPolicy = '', // ethicsPolicy
			$event = '', // event
			$faxNumber = '', // faxNumber
			$founder = '', // founder
			$foundingDate = '', // foundingDate
			$foundingLocation = '', // foundingLocation
			$funder = '', // funder
			$funding = '', // funding
			$globalLocationNumber = '', // globalLocationNumber
			$hasCredential = '', // hasCredential
			$hasMerchantReturnPolicy = '', // hasMerchantReturnPolicy
			$hasOfferCatalog = '', // hasOfferCatalog
			$hasPOS = '', // hasPOS
			$interactionStatistic = '', // interactionStatistic
			$isicV4 = '', // isicV4
			$iso6523Code = '', // iso6523Code
			$keywords = '', // keywords
			$knowsAbout = '', // knowsAbout
			$knowsLanguage = '', // knowsLanguage
			$legalName = '', // legalName
			$leiCode = '', // leiCode
			$location = '', // location
			$logo = '', // logo
			$makesOffer = '', // makesOffer
			$member = '', // member
			$memberOf = '', // memberOf
			$naics = '', // naics
			$nonprofitStatus = '', // nonprofitStatus
			$numberOfEmployees = '', // numberOfEmployees
			$ownershipFundingInfo = '', // ownershipFundingInfo
			$owns = '', // owns
			$parentOrganization = '', // parentOrganization
			$publishingPrinciples = '', // publishingPrinciples
			$review = '', // review
			$seeks = '', // seeks
			$slogan = '', // slogan
			$sponsor = '', // sponsor
			$subOrganization = '', // subOrganization
			$taxID = '', // taxID
			$telephone = '', // telephone
			$unnamedSourcesPolicy = '', // unnamedSourcesPolicy
			$vatID = '', // vatID
		// Place
			$additionalProperty = '', // additionalProperty
			$address = '', // address
			$aggregateRating = '', // aggregateRating
			$amenityFeature = '', // amenityFeature
			$branchCode = '', // branchCode
			$containedInPlace = '', // containedInPlace
			$containsPlace = '', // containsPlace
			$event = '', // event
			$faxNumber = '', // faxNumber
			$geo = '', // geo
			$geoContains = '', // geoContains
			$geoCoveredBy = '', // geoCoveredBy
			$geoCovers = '', // geoCovers
			$geoCrosses = '', // geoCrosses
			$geoDisjoint = '', // geoDisjoint
			$geoEquals = '', // geoEquals
			$geoIntersects = '', // geoIntersects
			$geoOverlaps = '', // geoOverlaps
			$geoTouches = '', // geoTouches
			$geoWithin = '', // geoWithin
			$globalLocationNumber = '', // globalLocationNumber
			$hasDriveThroughService = '', // hasDriveThroughService
			$hasMap = '', // hasMap
			$isAccessibleForFree = '', // isAccessibleForFree
			$isicV4 = '', // isicV4
			$keywords = '', // keywords
			$latitude = '', // latitude
			$logo = '', // logo
			$longitude = '', // longitude
			$maximumAttendeeCapacity = '', // maximumAttendeeCapacity
			$openingHoursSpecification = '', // openingHoursSpecification
			$photo = '', // photo
			$publicAccess = '', // publicAccess
			$review = '', // review
			$slogan = '', // slogan
			$smokingAllowed = '', // smokingAllowed
			$specialOpeningHoursSpecification = '', // specialOpeningHoursSpecification
			$telephone = '', // telephone
			$tourBookingPage = '', // tourBookingPage
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

			// Inherited properties from Organization (Thing > Organization)

				$actionableFeedbackPolicy = ( isset($actionableFeedbackPolicy) && !empty($actionableFeedbackPolicy) ) ? $actionableFeedbackPolicy : '';
				$address = ( isset($address) && !empty($address) ) ? $address : '';
				$aggregateRating = ( isset($aggregateRating) && !empty($aggregateRating) ) ? $aggregateRating : '';
				$alumni = ( isset($alumni) && !empty($alumni) ) ? $alumni : '';
				$areaServed = ( isset($areaServed) && !empty($areaServed) ) ? $areaServed : '';
				$award = ( isset($award) && !empty($award) ) ? $award : '';
				$brand = ( isset($brand) && !empty($brand) ) ? $brand : '';
				$contactPoint = ( isset($contactPoint) && !empty($contactPoint) ) ? $contactPoint : '';
				$correctionsPolicy = ( isset($correctionsPolicy) && !empty($correctionsPolicy) ) ? $correctionsPolicy : '';
				$department = ( isset($department) && !empty($department) ) ? $department : '';
				$dissolutionDate = ( isset($dissolutionDate) && !empty($dissolutionDate) ) ? $dissolutionDate : '';
				$diversityPolicy = ( isset($diversityPolicy) && !empty($diversityPolicy) ) ? $diversityPolicy : '';
				$diversityStaffingReport = ( isset($diversityStaffingReport) && !empty($diversityStaffingReport) ) ? $diversityStaffingReport : '';
				$duns = ( isset($duns) && !empty($duns) ) ? $duns : '';
				$email = ( isset($email) && !empty($email) ) ? $email : '';
				$employee = ( isset($employee) && !empty($employee) ) ? $employee : '';
				$ethicsPolicy = ( isset($ethicsPolicy) && !empty($ethicsPolicy) ) ? $ethicsPolicy : '';
				$event = ( isset($event) && !empty($event) ) ? $event : '';
				$faxNumber = ( isset($faxNumber) && !empty($faxNumber) ) ? $faxNumber : '';
				$founder = ( isset($founder) && !empty($founder) ) ? $founder : '';
				$foundingDate = ( isset($foundingDate) && !empty($foundingDate) ) ? $foundingDate : '';
				$foundingLocation = ( isset($foundingLocation) && !empty($foundingLocation) ) ? $foundingLocation : '';
				$funder = ( isset($funder) && !empty($funder) ) ? $funder : '';
				$funding = ( isset($funding) && !empty($funding) ) ? $funding : '';
				$globalLocationNumber = ( isset($globalLocationNumber) && !empty($globalLocationNumber) ) ? $globalLocationNumber : '';
				$hasCredential = ( isset($hasCredential) && !empty($hasCredential) ) ? $hasCredential : '';
				$hasMerchantReturnPolicy = ( isset($hasMerchantReturnPolicy) && !empty($hasMerchantReturnPolicy) ) ? $hasMerchantReturnPolicy : '';
				$hasOfferCatalog = ( isset($hasOfferCatalog) && !empty($hasOfferCatalog) ) ? $hasOfferCatalog : '';
				$hasPOS = ( isset($hasPOS) && !empty($hasPOS) ) ? $hasPOS : '';
				$interactionStatistic = ( isset($interactionStatistic) && !empty($interactionStatistic) ) ? $interactionStatistic : '';
				$isicV4 = ( isset($isicV4) && !empty($isicV4) ) ? $isicV4 : '';
				$iso6523Code = ( isset($iso6523Code) && !empty($iso6523Code) ) ? $iso6523Code : '';
				$keywords = ( isset($keywords) && !empty($keywords) ) ? $keywords : '';
				$knowsAbout = ( isset($knowsAbout) && !empty($knowsAbout) ) ? $knowsAbout : '';
				$knowsLanguage = ( isset($knowsLanguage) && !empty($knowsLanguage) ) ? $knowsLanguage : '';
				$legalName = ( isset($legalName) && !empty($legalName) ) ? $legalName : '';
				$leiCode = ( isset($leiCode) && !empty($leiCode) ) ? $leiCode : '';
				$location = ( isset($location) && !empty($location) ) ? $location : '';
				$logo = ( isset($logo) && !empty($logo) ) ? $logo : '';
				$makesOffer = ( isset($makesOffer) && !empty($makesOffer) ) ? $makesOffer : '';
				$member = ( isset($member) && !empty($member) ) ? $member : '';
				$memberOf = ( isset($memberOf) && !empty($memberOf) ) ? $memberOf : '';
				$naics = ( isset($naics) && !empty($naics) ) ? $naics : '';
				$nonprofitStatus = ( isset($nonprofitStatus) && !empty($nonprofitStatus) ) ? $nonprofitStatus : '';
				$numberOfEmployees = ( isset($numberOfEmployees) && !empty($numberOfEmployees) ) ? $numberOfEmployees : '';
				$ownershipFundingInfo = ( isset($ownershipFundingInfo) && !empty($ownershipFundingInfo) ) ? $ownershipFundingInfo : '';
				$owns = ( isset($owns) && !empty($owns) ) ? $owns : '';
				$parentOrganization = ( isset($parentOrganization) && !empty($parentOrganization) ) ? $parentOrganization : '';
				$publishingPrinciples = ( isset($publishingPrinciples) && !empty($publishingPrinciples) ) ? $publishingPrinciples : '';
				$review = ( isset($review) && !empty($review) ) ? $review : '';
				$seeks = ( isset($seeks) && !empty($seeks) ) ? $seeks : '';
				$slogan = ( isset($slogan) && !empty($slogan) ) ? $slogan : '';
				$sponsor = ( isset($sponsor) && !empty($sponsor) ) ? $sponsor : '';
				$subOrganization = ( isset($subOrganization) && !empty($subOrganization) ) ? $subOrganization : '';
				$taxID = ( isset($taxID) && !empty($taxID) ) ? $taxID : '';
				$telephone = ( isset($telephone) && !empty($telephone) ) ? $telephone : '';
				$unnamedSourcesPolicy = ( isset($unnamedSourcesPolicy) && !empty($unnamedSourcesPolicy) ) ? $unnamedSourcesPolicy : '';
				$vatID = ( isset($vatID) && !empty($vatID) ) ? $vatID : '';

			// Inherited properties from Place (Thing > Place)

				$additionalProperty = ( isset($additionalProperty) && !empty($additionalProperty) ) ? $additionalProperty : '';
				$address = ( isset($address) && !empty($address) ) ? $address : '';
				$aggregateRating = ( isset($aggregateRating) && !empty($aggregateRating) ) ? $aggregateRating : '';
				$amenityFeature = ( isset($amenityFeature) && !empty($amenityFeature) ) ? $amenityFeature : '';
				$branchCode = ( isset($branchCode) && !empty($branchCode) ) ? $branchCode : '';
				$containedInPlace = ( isset($containedInPlace) && !empty($containedInPlace) ) ? $containedInPlace : '';
				$containsPlace = ( isset($containsPlace) && !empty($containsPlace) ) ? $containsPlace : '';
				$event = ( isset($event) && !empty($event) ) ? $event : '';
				$faxNumber = ( isset($faxNumber) && !empty($faxNumber) ) ? $faxNumber : '';
				$geo = ( isset($geo) && !empty($geo) ) ? $geo : '';
				$geoContains = ( isset($geoContains) && !empty($geoContains) ) ? $geoContains : '';
				$geoCoveredBy = ( isset($geoCoveredBy) && !empty($geoCoveredBy) ) ? $geoCoveredBy : '';
				$geoCovers = ( isset($geoCovers) && !empty($geoCovers) ) ? $geoCovers : '';
				$geoCrosses = ( isset($geoCrosses) && !empty($geoCrosses) ) ? $geoCrosses : '';
				$geoDisjoint = ( isset($geoDisjoint) && !empty($geoDisjoint) ) ? $geoDisjoint : '';
				$geoEquals = ( isset($geoEquals) && !empty($geoEquals) ) ? $geoEquals : '';
				$geoIntersects = ( isset($geoIntersects) && !empty($geoIntersects) ) ? $geoIntersects : '';
				$geoOverlaps = ( isset($geoOverlaps) && !empty($geoOverlaps) ) ? $geoOverlaps : '';
				$geoTouches = ( isset($geoTouches) && !empty($geoTouches) ) ? $geoTouches : '';
				$geoWithin = ( isset($geoWithin) && !empty($geoWithin) ) ? $geoWithin : '';
				$globalLocationNumber = ( isset($globalLocationNumber) && !empty($globalLocationNumber) ) ? $globalLocationNumber : '';
				$hasDriveThroughService = ( isset($hasDriveThroughService) && !empty($hasDriveThroughService) ) ? $hasDriveThroughService : '';
				$hasMap = ( isset($hasMap) && !empty($hasMap) ) ? $hasMap : '';
				$isAccessibleForFree = ( isset($isAccessibleForFree) && !empty($isAccessibleForFree) ) ? $isAccessibleForFree : '';
				$isicV4 = ( isset($isicV4) && !empty($isicV4) ) ? $isicV4 : '';
				$keywords = ( isset($keywords) && !empty($keywords) ) ? $keywords : '';
				$latitude = ( isset($latitude) && !empty($latitude) ) ? $latitude : '';
				$logo = ( isset($logo) && !empty($logo) ) ? $logo : '';
				$longitude = ( isset($longitude) && !empty($longitude) ) ? $longitude : '';
				$maximumAttendeeCapacity = ( isset($maximumAttendeeCapacity) && !empty($maximumAttendeeCapacity) ) ? $maximumAttendeeCapacity : '';
				$openingHoursSpecification = ( isset($openingHoursSpecification) && !empty($openingHoursSpecification) ) ? $openingHoursSpecification : '';
				$photo = ( isset($photo) && !empty($photo) ) ? $photo : '';
				$publicAccess = ( isset($publicAccess) && !empty($publicAccess) ) ? $publicAccess : '';
				$review = ( isset($review) && !empty($review) ) ? $review : '';
				$slogan = ( isset($slogan) && !empty($slogan) ) ? $slogan : '';
				$smokingAllowed = ( isset($smokingAllowed) && !empty($smokingAllowed) ) ? $smokingAllowed : '';
				$specialOpeningHoursSpecification = ( isset($specialOpeningHoursSpecification) && !empty($specialOpeningHoursSpecification) ) ? $specialOpeningHoursSpecification : '';
				$telephone = ( isset($telephone) && !empty($telephone) ) ? $telephone : '';
				$tourBookingPage = ( isset($tourBookingPage) && !empty($tourBookingPage) ) ? $tourBookingPage : '';

			// Properties from LocalBusiness (Thing > Organization > LocalBusiness)
			// Properties from LocalBusiness (Thing > Place > LocalBusiness)

				$currenciesAccepted = ( isset($currenciesAccepted) && !empty($currenciesAccepted) ) ? $currenciesAccepted : '';
				$openingHours = ( isset($openingHours) && !empty($openingHours) ) ? $openingHours : '';
				$paymentAccepted = ( isset($paymentAccepted) && !empty($paymentAccepted) ) ? $paymentAccepted : '';
				$priceRange = ( isset($priceRange) && !empty($priceRange) ) ? $priceRange : '';

			// Properties from EmergencyService (Thing > Organization > LocalBusiness > EmergencyService)
			// Properties from EmergencyService (Thing > Place > LocalBusiness > EmergencyService)

				// Do nothing (no property vars)

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_localbusiness(
					$schema, // array // Main schema array
					// LocalBusiness
						$currenciesAccepted, // currenciesAccepted
						$openingHours, // openingHours
						$paymentAccepted, // paymentAccepted
						$priceRange, // priceRange
					// Organization
						$actionableFeedbackPolicy, // actionableFeedbackPolicy
						$address, // address
						$aggregateRating, // aggregateRating
						$alumni, // alumni
						$areaServed, // areaServed
						$award, // award
						$brand, // brand
						$contactPoint, // contactPoint
						$correctionsPolicy, // correctionsPolicy
						$department, // department
						$dissolutionDate, // dissolutionDate
						$diversityPolicy, // diversityPolicy
						$diversityStaffingReport, // diversityStaffingReport
						$duns, // duns
						$email, // email
						$employee, // employee
						$ethicsPolicy, // ethicsPolicy
						$event, // event
						$faxNumber, // faxNumber
						$founder, // founder
						$foundingDate, // foundingDate
						$foundingLocation, // foundingLocation
						$funder, // funder
						$funding, // funding
						$globalLocationNumber, // globalLocationNumber
						$hasCredential, // hasCredential
						$hasMerchantReturnPolicy, // hasMerchantReturnPolicy
						$hasOfferCatalog, // hasOfferCatalog
						$hasPOS, // hasPOS
						$interactionStatistic, // interactionStatistic
						$isicV4, // isicV4
						$iso6523Code, // iso6523Code
						$keywords, // keywords
						$knowsAbout, // knowsAbout
						$knowsLanguage, // knowsLanguage
						$legalName, // legalName
						$leiCode, // leiCode
						$location, // location
						$logo, // logo
						$makesOffer, // makesOffer
						$member, // member
						$memberOf, // memberOf
						$naics, // naics
						$nonprofitStatus, // nonprofitStatus
						$numberOfEmployees, // numberOfEmployees
						$ownershipFundingInfo, // ownershipFundingInfo
						$owns, // owns
						$parentOrganization, // parentOrganization
						$publishingPrinciples, // publishingPrinciples
						$review, // review
						$seeks, // seeks
						$slogan, // slogan
						$sponsor, // sponsor
						$subOrganization, // subOrganization
						$taxID, // taxID
						$telephone, // telephone
						$unnamedSourcesPolicy, // unnamedSourcesPolicy
						$vatID, // vatID
					// Place
						$additionalProperty, // additionalProperty
						$address, // address
						$aggregateRating, // aggregateRating
						$amenityFeature, // amenityFeature
						$branchCode, // branchCode
						$containedInPlace, // containedInPlace
						$containsPlace, // containsPlace
						$event, // event
						$faxNumber, // faxNumber
						$geo, // geo
						$geoContains, // geoContains
						$geoCoveredBy, // geoCoveredBy
						$geoCovers, // geoCovers
						$geoCrosses, // geoCrosses
						$geoDisjoint, // geoDisjoint
						$geoEquals, // geoEquals
						$geoIntersects, // geoIntersects
						$geoOverlaps, // geoOverlaps
						$geoTouches, // geoTouches
						$geoWithin, // geoWithin
						$globalLocationNumber, // globalLocationNumber
						$hasDriveThroughService, // hasDriveThroughService
						$hasMap, // hasMap
						$isAccessibleForFree, // isAccessibleForFree
						$isicV4, // isicV4
						$keywords, // keywords
						$latitude, // latitude
						$logo, // logo
						$longitude, // longitude
						$maximumAttendeeCapacity, // maximumAttendeeCapacity
						$openingHoursSpecification, // openingHoursSpecification
						$photo, // photo
						$publicAccess, // publicAccess
						$review, // review
						$slogan, // slogan
						$smokingAllowed, // smokingAllowed
						$specialOpeningHoursSpecification, // specialOpeningHoursSpecification
						$telephone, // telephone
						$tourBookingPage, // tourBookingPage
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

			// Properties from EmergencyService (Thing > Organization > LocalBusiness > EmergencyService)
			// Properties from EmergencyService (Thing > Place > LocalBusiness > EmergencyService)

				// Do nothing (no property vars)

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}
 
	// FireStation
	include_once __DIR__ . '/EmergencyService/FireStation.php';

	// Hospital
	include_once __DIR__ . '/EmergencyService/Hospital.php';

	// PoliceStation
	include_once __DIR__ . '/EmergencyService/PoliceStation.php';
