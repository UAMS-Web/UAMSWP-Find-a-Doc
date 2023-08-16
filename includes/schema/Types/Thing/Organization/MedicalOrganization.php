<?php

// MedicalOrganization

	/*
	 * Thing > Organization > MedicalOrganization
	 * 
	 * A medical organization (physical or not), such as hospital, institution or 
	 * clinic.
	 */

	function uamswp_fad_schema_medicalorganization(
		$schema, // array // Main schema array
		// MedicalOrganization
			$healthPlanNetworkId = '', // healthPlanNetworkId
			$isAcceptingNewPatients = '', // isAcceptingNewPatients
			$medicalSpecialty = '', // medicalSpecialty
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

			// Properties from MedicalOrganization (Thing > Organization > MedicalOrganization)

				$healthPlanNetworkId = ( isset($healthPlanNetworkId) && !empty($healthPlanNetworkId) ) ? $healthPlanNetworkId : '';
				$isAcceptingNewPatients = ( isset($isAcceptingNewPatients) && !empty($isAcceptingNewPatients) ) ? $isAcceptingNewPatients : '';
				$medicalSpecialty = ( isset($medicalSpecialty) && !empty($medicalSpecialty) ) ? $medicalSpecialty : '';

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_organization(
					$schema, // array // Main schema array
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

			// Properties from MedicalOrganization (Thing > Organization > MedicalOrganization)

				// healthPlanNetworkId

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * Name or unique ID of network. (Networks are often reused across different 
					 * insurance plans.)
					 */

					$schema['healthPlanNetworkId'] = $healthPlanNetworkId;

				// isAcceptingNewPatients

					/* 
					 * Expected Type:
					 *     Boolean
					 * 
					 * Whether the provider is accepting new patients.
					 */

					$schema['isAcceptingNewPatients'] = $isAcceptingNewPatients;

				// medicalSpecialty

					/* 
					 * Expected Type:
					 *     Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty
					 * 
					 * A medical specialty of the provider.
					 */

					$schema['medicalSpecialty'] = $medicalSpecialty;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// Dentist
	include_once __DIR__ . '/MedicalOrganization/Dentist.php';

	// DiagnosticLab
	include_once __DIR__ . '/MedicalOrganization/DiagnosticLab.php';

	// Hospital
	include_once __DIR__ . '/MedicalOrganization/Hospital.php';

	// MedicalClinic
	include_once __DIR__ . '/MedicalOrganization/MedicalClinic.php';

	// Pharmacy
	include_once __DIR__ . '/MedicalOrganization/Pharmacy.php';

	// Physician
	include_once __DIR__ . '/MedicalOrganization/Physician.php';

	// VeterinaryCare
	include_once __DIR__ . '/MedicalOrganization/VeterinaryCare.php';