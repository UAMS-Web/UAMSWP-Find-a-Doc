<?php

// Organization

	/*
	 * Thing > Organization
	 * 
	 * An organization such as a school, NGO, corporation, club, etc.
	 */

	function uamswp_fad_schema_organization(
		$schema, // array // Main schema array
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

			// Properties from Organization (Thing > Organization)

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

			// Properties from Organization

				// actionableFeedbackPolicy

					/* 
					 * Expected Type:
					 *     CreativeWork
					 *     URL
					 * 
					 * For a NewsMediaOrganization or other news-related Organization, a statement 
					 * about public engagement activities (for news media, the newsroom’s), including 
					 * involving the public - digitally or otherwise -- in coverage decisions, 
					 * reporting and activities after publication.
					 */

					$schema['actionableFeedbackPolicy'] = $actionableFeedbackPolicy;

				// address

					/* 
					 * Expected Type:
					 *     PostalAddress
					 *     Text
					 * 
					 * Physical address of the item.
					 */

					$schema['address'] = $address;

				// aggregateRating

					/* 
					 * Expected Type:
					 *     AggregateRating
					 * 
					 * The overall rating, based on a collection of reviews or ratings, of the item.
					 */

					$schema['aggregateRating'] = $aggregateRating;

				// alumni

					/* 
					 * Expected Type:
					 *     Person
					 * 
					 * Alumni of an organization.
					 * 
					 * Inverse property: alumniOf
					 */

					$schema['alumni'] = $alumni;

				// areaServed

					/* 
					 * Expected Type:
					 *     AdministrativeArea
					 *     GeoShape
					 *     Place
					 *     Text
					 * 
					 * The geographic area where a service or offered item is provided. Supersedes 
					 * serviceArea.
					 */

					$schema['areaServed'] = $areaServed;

				// award

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * An award won by or for this item. Supersedes awards.
					 */

					$schema['award'] = $award;

				// brand

					/* 
					 * Expected Type:
					 *     Brand
					 *     Organization
					 * 
					 * The brand(s) associated with a product or service, or the brand(s) maintained 
					 * by an organization or business person.
					 */

					$schema['brand'] = $brand;

				// contactPoint

					/* 
					 * Expected Type:
					 *     ContactPoint
					 * 
					 * A contact point for a person or organization. Supersedes contactPoints.
					 */

					$schema['contactPoint'] = $contactPoint;

				// correctionsPolicy

					/* 
					 * Expected Type:
					 *     CreativeWork
					 *     URL
					 * 
					 * For an Organization (e.g. NewsMediaOrganization), a statement describing (in 
					 * news media, the newsroom’s) disclosure and correction policy for errors.
					 */

					$schema['correctionsPolicy'] = $correctionsPolicy;

				// department

					/* 
					 * Expected Type:
					 *     Organization
					 * 
					 * A relationship between an organization and a department of that organization, 
					 * also described as an organization (allowing different urls, logos, opening 
					 * hours). For example: a store with a pharmacy, or a bakery with a cafe.
					 */

					$schema['department'] = $department;

				// dissolutionDate

					/* 
					 * Expected Type:
					 *     Date
					 * 
					 * The date that this organization was dissolved.
					 */

					$schema['dissolutionDate'] = $dissolutionDate;

				// diversityPolicy

					/* 
					 * Expected Type:
					 *     CreativeWork
					 *     URL
					 * 
					 * Statement on diversity policy by an Organization e.g. a NewsMediaOrganization. 
					 * For a NewsMediaOrganization, a statement describing the newsroom’s diversity 
					 * policy on both staffing and sources, typically providing staffing data.
					 */

					$schema['diversityPolicy'] = $diversityPolicy;

				// diversityStaffingReport

					/* 
					 * Expected Type:
					 *     Article
					 *     URL
					 * 
					 * For an Organization (often but not necessarily a NewsMediaOrganization), a 
					 * report on staffing diversity issues. In a news context this might be for 
					 * example ASNE or RTDNA (US) reports, or self-reported.
					 */

					$schema['diversityStaffingReport'] = $diversityStaffingReport;

				// duns

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * The Dun & Bradstreet DUNS number for identifying an organization or business 
					 * person.
					 */

					$schema['duns'] = $duns;

				// email

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * Email address.
					 */

					$schema['email'] = $email;

				// employee

					/* 
					 * Expected Type:
					 *     Person
					 * 
					 * Someone working for this organization. Supersedes employees.
					 */

					$schema['employee'] = $employee;

				// ethicsPolicy

					/* 
					 * Expected Type:
					 *     CreativeWork
					 *     URL
					 * 
					 * Statement about ethics policy, e.g. of a NewsMediaOrganization regarding 
					 * journalistic and publishing practices, or of a Restaurant, a page describing 
					 * food source policies. In the case of a NewsMediaOrganization, an ethicsPolicy 
					 * is typically a statement describing the personal, organizational, and corporate 
					 * standards of behavior expected by the organization.
					 */

					$schema['ethicsPolicy'] = $ethicsPolicy;

				// event

					/* 
					 * Expected Type:
					 *     Event
					 * 
					 * Upcoming or past event associated with this place, organization, or action. 
					 * Supersedes events.
					 */

					$schema['event'] = $event;

				// faxNumber

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * The fax number.
					 */

					$schema['faxNumber'] = $faxNumber;

				// founder

					/* 
					 * Expected Type:
					 *     Person
					 * 
					 * A person who founded this organization. Supersedes founders.
					 */

					$schema['founder'] = $founder;

				// foundingDate

					/* 
					 * Expected Type:
					 *     Date
					 * 
					 * The date that this organization was founded.
					 */

					$schema['foundingDate'] = $foundingDate;

				// foundingLocation

					/* 
					 * Expected Type:
					 *     Place
					 * 
					 * The place where the Organization was founded.
					 */

					$schema['foundingLocation'] = $foundingLocation;

				// funder

					/* 
					 * Expected Type:
					 *     Organization
					 *     Person
					 * 
					 * A person or organization that supports (sponsors) something through some kind 
					 * of financial contribution.
					 */

					$schema['funder'] = $funder;

				// funding

					/* 
					 * Expected Type:
					 *     Grant
					 * 
					 * A Grant that directly or indirectly provide funding or sponsorship for this 
					 * item. See also ownershipFundingInfo.
					 * 
					 * Inverse property: fundedItem
					 */

					$schema['funding'] = $funding;

				// globalLocationNumber

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * The Global Location Number (GLN, sometimes also referred to as International 
					 * Location Number or ILN) of the respective organization, person, or place. The 
					 * GLN is a 13-digit number used to identify parties and physical locations.
					 */

					$schema['globalLocationNumber'] = $globalLocationNumber;

				// hasCredential

					/* 
					 * Expected Type:
					 *     EducationalOccupationalCredential
					 * 
					 * A credential awarded to the Person or Organization.
					 */

					$schema['hasCredential'] = $hasCredential;

				// hasMerchantReturnPolicy

					/* 
					 * Expected Type:
					 *     MerchantReturnPolicy
					 * 
					 * Specifies a MerchantReturnPolicy that may be applicable. Supersedes 
					 * hasProductReturnPolicy.
					 */

					$schema['hasMerchantReturnPolicy'] = $hasMerchantReturnPolicy;

				// hasOfferCatalog

					/* 
					 * Expected Type:
					 *     OfferCatalog
					 * 
					 * Indicates an OfferCatalog listing for this Organization, Person, or Service.
					 */

					$schema['hasOfferCatalog'] = $hasOfferCatalog;

				// hasPOS

					/* 
					 * Expected Type:
					 *     Place
					 * 
					 * Points-of-Sales operated by the organization or person.
					 */

					$schema['hasPOS'] = $hasPOS;

				// interactionStatistic

					/* 
					 * Expected Type:
					 *     InteractionCounter
					 * 
					 * The number of interactions for the CreativeWork using the WebSite or 
					 * SoftwareApplication. The most specific child type of InteractionCounter should 
					 * be used. Supersedes interactionCount.
					 */

					$schema['interactionStatistic'] = $interactionStatistic;

				// isicV4

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * The International Standard of Industrial Classification of All Economic 
					 * Activities (ISIC), Revision 4 code for a particular organization, business 
					 * person, or place.
					 */

					$schema['isicV4'] = $isicV4;

				// iso6523Code

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * An organization identifier as defined in ISO 6523(-1). Note that many existing 
					 * organization identifiers such as leiCode, duns and vatID can be expressed as an 
					 * ISO 6523 identifier by setting the ICD part of the ISO 6523 identifier 
					 * accordingly.
					 */

					$schema['iso6523Code'] = $iso6523Code;

				// keywords

					/* 
					 * Expected Type:
					 *     DefinedTerm
					 *     Text
					 *     URL
					 * 
					 * Keywords or tags used to describe some item. Multiple textual entries in a 
					 * keywords list are typically delimited by commas, or by repeating the property.
					 */

					$schema['keywords'] = $keywords;

				// knowsAbout

					/* 
					 * Expected Type:
					 *     Text
					 *     Thing
					 *     URL
					 * 
					 * Of a Person, and less typically of an Organization, to indicate a topic that is 
					 * known about - suggesting possible expertise but not implying it. We do not 
					 * distinguish skill levels here, or relate this to educational content, events, 
					 * objectives or JobPosting descriptions.
					 */

					$schema['knowsAbout'] = $knowsAbout;

				// knowsLanguage

					/* 
					 * Expected Type:
					 *     Language
					 *     Text
					 * 
					 * Of a Person, and less typically of an Organization, to indicate a known 
					 * language. We do not distinguish skill levels or 
					 * reading/writing/speaking/signing here. Use language codes from the IETF BCP 47 
					 * standard.
					 */

					$schema['knowsLanguage'] = $knowsLanguage;

				// legalName

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * The official name of the organization, e.g. the registered company name.
					 */

					$schema['legalName'] = $legalName;

				// leiCode

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * An organization identifier that uniquely identifies a legal entity as defined 
					 * in ISO 17442.
					 */

					$schema['leiCode'] = $leiCode;

				// location

					/* 
					 * Expected Type:
					 *     Place
					 *     PostalAddress
					 *     Text
					 *     VirtualLocation
					 * 
					 * The location of, for example, where an event is happening, where an 
					 * organization is located, or where an action takes place.
					 */

					$schema['location'] = $location;

				// logo

					/* 
					 * Expected Type:
					 *     ImageObject
					 *     URL
					 * 
					 * An associated logo.
					 */

					$schema['logo'] = $logo;

				// makesOffer

					/* 
					 * Expected Type:
					 *     Offer
					 * 
					 * A pointer to products or services offered by the organization or person.
					 * 
					 * Inverse property: offeredBy
					 */

					$schema['makesOffer'] = $makesOffer;

				// member

					/* 
					 * Expected Type:
					 *     Organization
					 *     Person
					 * 
					 * A member of an Organization or a ProgramMembership. Organizations can be 
					 * members of organizations; ProgramMembership is typically for individuals. 
					 * Supersedes musicGroupMember, members.
					 * 
					 * Inverse property: memberOf
					 */

					$schema['member'] = $member;

				// memberOf

					/* 
					 * Expected Type:
					 *     Organization
					 *     ProgramMembership
					 * 
					 * An Organization (or ProgramMembership) to which this Person or Organization 
					 * belongs.
					 * 
					 * Inverse property: member
					 */

					$schema['memberOf'] = $memberOf;

				// naics

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * The North American Industry Classification System (NAICS) code for a particular 
					 * organization or business person.
					 */

					$schema['naics'] = $naics;

				// nonprofitStatus

					/* 
					 * Expected Type:
					 *     NonprofitType
					 * 
					 * nonprofitStatus indicates the legal status of a non-profit organization in its 
					 * primary place of business.
					 */

					$schema['nonprofitStatus'] = $nonprofitStatus;

				// numberOfEmployees

					/* 
					 * Expected Type:
					 *     QuantitativeValue
					 * 
					 * The number of employees in an organization, e.g. business.
					 */

					$schema['numberOfEmployees'] = $numberOfEmployees;

				// ownershipFundingInfo

					/* 
					 * Expected Type:
					 *     AboutPage
					 *     CreativeWork
					 *     Text
					 *     URL
					 * 
					 * For an Organization (often but not necessarily a NewsMediaOrganization), a 
					 * description of organizational ownership structure; funding and grants. In a 
					 * news/media setting, this is with particular reference to editorial 
					 * independence. Note that the funder is also available and can be used to make 
					 * basic funder information machine-readable.
					 */

					$schema['ownershipFundingInfo'] = $ownershipFundingInfo;

				// owns

					/* 
					 * Expected Type:
					 *     OwnershipInfo
					 *     Product
					 * 
					 * Products owned by the organization or person.
					 */

					$schema['owns'] = $owns;

				// parentOrganization

					/* 
					 * Expected Type:
					 *     Organization
					 * 
					
					 Supersedes branchOf.
					 * 
					 * Inverse property: subOrganization
					 */

					$schema['parentOrganization'] = $parentOrganization;

				// publishingPrinciples

					/* 
					 * Expected Type:
					 *     CreativeWork
					 *     URL
					 * 
					 * The publishingPrinciples property indicates (typically via URL) a document 
					 * describing the editorial principles of an Organization (or individual, e.g., a 
					 * Person writing a blog) that relate to their activities as a publisher, e.g., 
					 * ethics or diversity policies. When applied to a CreativeWork (e.g., 
					 * NewsArticle) the principles are those of the party primarily responsible for 
					 * the creation of the CreativeWork.
					 * 
					 * While such policies are most typically expressed in natural language, sometimes 
					 * related information (e.g. indicating a funder) can be expressed using 
					 * schema.org terminology.
					 */

					$schema['publishingPrinciples'] = $publishingPrinciples;

				// review

					/* 
					 * Expected Type:
					 *     Review
					 * 
					 * A review of the item. Supersedes reviews.
					 */

					$schema['review'] = $review;

				// seeks

					/* 
					 * Expected Type:
					 *     Demand
					 * 
					 * A pointer to products or services sought by the organization or person (demand).
					 */

					$schema['seeks'] = $seeks;

				// slogan

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * A slogan or motto associated with the item.
					 */

					$schema['slogan'] = $slogan;

				// sponsor

					/* 
					 * Expected Type:
					 *     Organization
					 *     Person
					 * 
					 * A person or organization that supports a thing through a pledge, promise, or 
					 * financial contribution (e.g., a sponsor of a Medical Study or a corporate 
					 * sponsor of an event).
					 */

					$schema['sponsor'] = $sponsor;

				// subOrganization

					/* 
					 * Expected Type:
					 *     Organization
					 * 
					 * A relationship between two organizations where the first includes the second, 
					 * e.g., as a subsidiary. See also: the more specific 'department' property.
					 * 
					 * Inverse property: parentOrganization
					 */

					$schema['subOrganization'] = $subOrganization;

				// taxID

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * The Tax / Fiscal ID of the organization or person, e.g., the TIN in the US or 
					 * the CIF/NIF in Spain.
					 */

					$schema['taxID'] = $taxID;

				// telephone

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * The telephone number.
					 */

					$schema['telephone'] = $telephone;

				// unnamedSourcesPolicy

					/* 
					 * Expected Type:
					 *     CreativeWork
					 *     URL
					 * 
					 * For an Organization (typically a NewsMediaOrganization), a statement about 
					 * policy on use of unnamed sources and the decision process required.
					 */

					$schema['unnamedSourcesPolicy'] = $unnamedSourcesPolicy;

				// vatID

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * The Value-added Tax ID of the organization or person.
					 */

					$schema['vatID'] = $vatID;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// Airline

		/*
		 * Thing > Organization > Airline
		 * 
		 * 
		 */

		function uamswp_fad_schema_airline(
			
		) {
			
		}

	// Consortium

		/*
		 * Thing > Organization > Consortium
		 * 
		 * 
		 */

		function uamswp_fad_schema_consortium(
			
		) {
			
		}

	// Corporation

		/*
		 * Thing > Organization > Corporation
		 * 
		 * 
		 */

		function uamswp_fad_schema_corporation(
			
		) {
			
		}

	// EducationalOrganization

		/*
		 * Thing > Organization > EducationalOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_educationalorganization(
			
		) {
			
		}

		// CollegeOrUniversity

			/*
			 * Thing > Organization > EducationalOrganization > CollegeOrUniversity
			 * 
			 * 
			 */

			function uamswp_fad_schema_collegeoruniversity(
				
			) {
				
			}

		// ElementarySchool

			/*
			 * Thing > Organization > EducationalOrganization > ElementarySchool
			 * 
			 * 
			 */

			function uamswp_fad_schema_elementaryschool(
				
			) {
				
			}

		// HighSchool

			/*
			 * Thing > Organization > EducationalOrganization > HighSchool
			 * 
			 * 
			 */

			function uamswp_fad_schema_highschool(
				
			) {
				
			}

		// MiddleSchool

			/*
			 * Thing > Organization > EducationalOrganization > MiddleSchool
			 * 
			 * 
			 */

			function uamswp_fad_schema_middleschool(
				
			) {
				
			}

		// Preschool

			/*
			 * Thing > Organization > EducationalOrganization > Preschool
			 * 
			 * 
			 */

			function uamswp_fad_schema_preschool(
				
			) {
				
			}

		// School

			/*
			 * Thing > Organization > EducationalOrganization > School
			 * 
			 * 
			 */

			function uamswp_fad_schema_school(
				
			) {
				
			}

	// FundingScheme

		/*
		 * Thing > Organization > FundingScheme
		 * 
		 * 
		 */

		function uamswp_fad_schema_fundingscheme(
			
		) {
			
		}

	// GovernmentOrganization

		/*
		 * Thing > Organization > GovernmentOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_governmentorganization(
			
		) {
			
		}

	// LibrarySystem

		/*
		 * Thing > Organization > LibrarySystem
		 * 
		 * 
		 */

		function uamswp_fad_schema_librarysystem(
			
		) {
			
		}

	// LocalBusiness
	include_once __DIR__ . '/Organization/LocalBusiness.php';

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

					$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';

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
						 *     Text
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
						 *     MedicalSpecialty
						 * 
						 * A medical specialty of the provider.
						 */

						$schema['medicalSpecialty'] = $medicalSpecialty;

			// Remove any empty values from the schema array

				$schema = array_filter($schema);

			return $schema;

		}

		// Dentist

			/*
			 * Thing > Organization > MedicalOrganization > Dentist
			 * 
			 * See: Thing > Organization > LocalBusiness > Dentist
			 */

		// DiagnosticLab

			/*
			 * Thing > Organization > MedicalOrganization > DiagnosticLab
			 * 
			 * A medical laboratory that offers on-site or off-site diagnostic services.
			 */

			function uamswp_fad_schema_diagnosticlab(
				
			) {
				
			}

		// Hospital

			/*
			 * Thing > Organization > MedicalOrganization > Hospital
			 * 
			 * See: Thing > Place > CivicStructure > Hospital
			 */

		// MedicalClinic

			/*
			 * Thing > Organization > MedicalOrganization > MedicalClinic
			 * 
			 * See: Thing > Organization > LocalBusiness > MedicalBusiness > MedicalClinic
			 */

		// Pharmacy

			/*
			 * Thing > Organization > MedicalOrganization > Pharmacy
			 * 
			 * See: Thing > Organization > LocalBusiness > MedicalBusiness > Pharmacy
			 */

		// Physician

			/*
			 * Thing > Organization > MedicalOrganization > Physician
			 * 
			 * See: Thing > Organization > LocalBusiness > MedicalBusiness > Physician
			 */

		// VeterinaryCare

			/*
			 * Thing > Organization > MedicalOrganization > VeterinaryCare
			 * 
			 * See: Thing > Organization > MedicalOrganization > VeterinaryCare
			 */

	// NGO

		/*
		 * Thing > Organization > NGO
		 * 
		 * 
		 */

		function uamswp_fad_schema_ngo(
			
		) {
			
		}

	// NewsMediaOrganization

		/*
		 * Thing > Organization > NewsMediaOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_newsmediaorganization(
			
		) {
			
		}

	// OnlineBusiness

		/*
		 * Thing > Organization > OnlineBusiness
		 * 
		 * 
		 */

		function uamswp_fad_schema_onlinebusiness(
			
		) {
			
		}

		// OnlineStore

			/*
			 * Thing > Organization > OnlineBusiness > OnlineStore
			 * 
			 * 
			 */

			function uamswp_fad_schema_onlinestore(
				
			) {
				
			}

	// PerformingGroup

		/*
		 * Thing > Organization > PerformingGroup
		 * 
		 * 
		 */

		function uamswp_fad_schema_performinggroup(
			
		) {
			
		}

		// DanceGroup

			/*
			 * Thing > Organization > PerformingGroup > DanceGroup
			 * 
			 * 
			 */

			function uamswp_fad_schema_dancegroup(
				
			) {
				
			}

		// MusicGroup

			/*
			 * Thing > Organization > PerformingGroup > MusicGroup
			 * 
			 * 
			 */

			function uamswp_fad_schema_musicgroup(
				
			) {
				
			}

		// TheaterGroup

			/*
			 * Thing > Organization > PerformingGroup > TheaterGroup
			 * 
			 * 
			 */

			function uamswp_fad_schema_theatergroup(
				
			) {
				
			}

	// PoliticalParty

		/*
		 * Thing > Organization > PoliticalParty
		 * 
		 * 
		 */

		function uamswp_fad_schema_politicalparty(
			
		) {
			
		}

	// Project

		/*
		 * Thing > Organization > Project
		 * 
		 * 
		 */

		function uamswp_fad_schema_project(
			
		) {
			
		}

		// FundingAgency

			/*
			 * Thing > Organization > Project > FundingAgency
			 * 
			 * 
			 */

			function uamswp_fad_schema_fundingagency(
				
			) {
				
			}

		// ResearchProject

			/*
			 * Thing > Organization > Project > ResearchProject
			 * 
			 * 
			 */

			function uamswp_fad_schema_researchproject(
				
			) {
				
			}

	// ResearchOrganization

		/*
		 * Thing > Organization > ResearchOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_researchorganization(
			
		) {
			
		}

	// SearchRescueOrganization

		/*
		 * Thing > Organization > SearchRescueOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_searchrescueorganization(
			
		) {
			
		}

	// SportsOrganization

		/*
		 * Thing > Organization > SportsOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_sportsorganization(
			
		) {
			
		}

		// SportsTeam

			/*
			 * Thing > Organization > SportsOrganization > SportsTeam
			 * 
			 * 
			 */

			function uamswp_fad_schema_sportsteam(
				
			) {
				
			}

	// WorkersUnion

		/*
		 * Thing > Organization > WorkersUnion
		 * 
		 * 
		 */

		function uamswp_fad_schema_workersunion(
			
		) {
			
		}