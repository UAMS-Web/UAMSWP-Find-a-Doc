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

		/*
		 * Thing > Organization > LocalBusiness
		 * 
		 *     Also: Thing > Place > LocalBusiness
		 * 
		 * A particular physical business or branch of an organization. Examples of 
		 * LocalBusiness include a restaurant, a particular branch of a restaurant chain, 
		 * a branch of a bank, a medical practice, a club, a bowling alley, etc.
		 */

		 function uamswp_fad_schema_localbusiness(
			$schema, // array // Main schema array
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

				// Properties from LocalBusiness (Thing > Place > LocalBusiness)

					$currenciesAccepted = ( isset($currenciesAccepted) && !empty($currenciesAccepted) ) ? $currenciesAccepted : '';
					$openingHours = ( isset($openingHours) && !empty($openingHours) ) ? $openingHours : '';
					$paymentAccepted = ( isset($paymentAccepted) && !empty($paymentAccepted) ) ? $paymentAccepted : '';
					$priceRange = ( isset($priceRange) && !empty($priceRange) ) ? $priceRange : '';

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

					$schema = uamswp_fad_schema_place(
						$schema, // array // Main schema array
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

				// Properties from LocalBusiness (Thing > Place > LocalBusiness)

					// currenciesAccepted

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * The currency accepted.
						 * 
						 * Use standard formats: ISO 4217 currency format (e.g., "USD"; Ticker symbol for 
						 * cryptocurrencies, e.g., "BTC"; well known names for Local Exchange Trading 
						 * Systems (LETS) and other currency types, e.g., "Ithaca HOUR").
						 */

						$schema['currenciesAccepted'] = $currenciesAccepted;

					// openingHours

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * The general opening hours for a business. Opening hours can be specified as a 
						 * weekly time range, starting with days, then times per day. Multiple days can be 
						 * listed with commas ',' separating each day. Day or time ranges are specified 
						 * using a hyphen '-'.
						 * 
						 * Days are specified using the following two-letter combinations: Mo, Tu, We, Th, 
						 * Fr, Sa, Su.
						 * 
						 * Times are specified using 24:00 format. For example, 3pm is specified as 15:00, 
						 * 10am as 10:00.
						 * 
						 * Here is an example: 
						 * <time itemprop="openingHours" datetime="Tu,Th 16:00-20:00">Tuesdays and Thursdays 4-8pm</time>.
						 * 
						 * If a business is open 7 days a week, then it can be specified as 
						 * <time itemprop="openingHours" datetime="Mo-Su">Monday through Sunday, all day</time>.
						 */

						$schema['openingHours'] = $openingHours;

					// paymentAccepted

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * Cash, Credit Card, Cryptocurrency, Local Exchange Tradings System, etc.
						 */

						$schema['paymentAccepted'] = $paymentAccepted;

					// priceRange

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * The price range of the business, for example $$$.
						 */

						$schema['priceRange'] = $priceRange;

			// Remove any empty values from the schema array

				$schema = array_filter($schema);

			return $schema;

		}

		// AnimalShelter

			/*
			 * Thing > Organization > LocalBusiness > AnimalShelter
			 * 
			 * 
			 */

			function uamswp_fad_schema_animalshelter(
				
			) {
				
			}

		// ArchiveOrganization

			/*
			 * Thing > Organization > LocalBusiness > ArchiveOrganization
			 * 
			 * 
			 */

			function uamswp_fad_schema_archiveorganization(
				
			) {
				
			}

		// AutomotiveBusiness

			/*
			 * Thing > Organization > LocalBusiness > AutomotiveBusiness
			 * 
			 * 
			 */

			function uamswp_fad_schema_automotivebusiness(
				
			) {
				
			}

			// AutoBodyShop

				/*
				 * Thing > Organization > LocalBusiness > AutomotiveBusiness > AutoBodyShop
				 * 
				 * 
				 */

				function uamswp_fad_schema_autobodyshop(
					
				) {
					
				}

			// AutoDealer

				/*
				 * Thing > Organization > LocalBusiness > AutomotiveBusiness > AutoDealer
				 * 
				 * 
				 */

				function uamswp_fad_schema_autodealer(
					
				) {
					
				}

			// AutoPartsStore

				/*
				 * Thing > Organization > LocalBusiness > AutomotiveBusiness > AutoPartsStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_autopartsstore(
					
				) {
					
				}

			// AutoRental

				/*
				 * Thing > Organization > LocalBusiness > AutomotiveBusiness > AutoRental
				 * 
				 * 
				 */

				function uamswp_fad_schema_autorental(
					
				) {
					
				}

			// AutoRepair

				/*
				 * Thing > Organization > LocalBusiness > AutomotiveBusiness > AutoRepair
				 * 
				 * 
				 */

				function uamswp_fad_schema_autorepair(
					
				) {
					
				}

			// AutoWash

				/*
				 * Thing > Organization > LocalBusiness > AutomotiveBusiness > AutoWash
				 * 
				 * 
				 */

				function uamswp_fad_schema_autowash(
					
				) {
					
				}

			// GasStation

				/*
				 * Thing > Organization > LocalBusiness > AutomotiveBusiness > GasStation
				 * 
				 * 
				 */

				function uamswp_fad_schema_gasstation(
					
				) {
					
				}

			// MotorcycleDealer

				/*
				 * Thing > Organization > LocalBusiness > AutomotiveBusiness > MotorcycleDealer
				 * 
				 * 
				 */

				function uamswp_fad_schema_motorcycledealer(
					
				) {
					
				}

			// MotorcycleRepair

				/*
				 * Thing > Organization > LocalBusiness > AutomotiveBusiness > MotorcycleRepair
				 * 
				 * 
				 */

				function uamswp_fad_schema_motorcyclerepair(
					
				) {
					
				}

		// ChildCare

			/*
			 * Thing > Organization > LocalBusiness > ChildCare
			 * 
			 * 
			 */

			function uamswp_fad_schema_childcare(
				
			) {
				
			}

		// Dentist

			/*
			 * Thing > Organization > LocalBusiness > Dentist
			 * 
			 * 
			 */

			function uamswp_fad_schema_dentist(
				
			) {
				
			}

		// DryCleaningOrLaundry

			/*
			 * Thing > Organization > LocalBusiness > DryCleaningOrLaundry
			 * 
			 * 
			 */

			function uamswp_fad_schema_drycleaningorlaundry(
				
			) {
				
			}

		// EmergencyService

			/*
			 * See: Thing > Organization > LocalBusiness > EmergencyService
			 * 
			 *     Also: Thing > Place > LocalBusiness > EmergencyService
			 * 
			 * An emergency service, such as a fire station or ER.
			 */

			 function uamswp_fad_schema_emergencyservice() {

			 }
 
			// FireStation

				/*
				 * Thing > Organization > LocalBusiness > EmergencyService > FireStation
				 * 
				 * 
				 */

				function uamswp_fad_schema_firestation(
					
				) {
					
				}

			// Hospital

				/*
				 * Thing > Organization > LocalBusiness > EmergencyService > Hospital
				 * 
				 * See: Thing > Place > CivicStructure > Hospital
				 */

			// PoliceStation

				/*
				 * Thing > Organization > LocalBusiness > EmergencyService > PoliceStation
				 * 
				 * 
				 */

				function uamswp_fad_schema_policestation(
					
				) {
					
				}

		// EmploymentAgency

			/*
			 * Thing > Organization > LocalBusiness > EmploymentAgency
			 * 
			 * 
			 */

			function uamswp_fad_schema_employmentagency(
				
			) {
				
			}

		// EntertainmentBusiness

			/*
			 * Thing > Organization > LocalBusiness > EntertainmentBusiness
			 * 
			 * 
			 */

			function uamswp_fad_schema_entertainmentbusiness(
				
			) {
				
			}

			// AdultEntertainment

				/*
				 * Thing > Organization > LocalBusiness > EntertainmentBusiness > AdultEntertainment
				 * 
				 * 
				 */

				function uamswp_fad_schema_adultentertainment(
					
				) {
					
				}

			// AmusementPark

				/*
				 * Thing > Organization > LocalBusiness > EntertainmentBusiness > AmusementPark
				 * 
				 * 
				 */

				function uamswp_fad_schema_amusementpark(
					
				) {
					
				}

			// ArtGallery

				/*
				 * Thing > Organization > LocalBusiness > EntertainmentBusiness > ArtGallery
				 * 
				 * 
				 */

				function uamswp_fad_schema_artgallery(
					
				) {
					
				}

			// Casino

				/*
				 * Thing > Organization > LocalBusiness > EntertainmentBusiness > Casino
				 * 
				 * 
				 */

				function uamswp_fad_schema_casino(
					
				) {
					
				}

			// ComedyClub

				/*
				 * Thing > Organization > LocalBusiness > EntertainmentBusiness > ComedyClub
				 * 
				 * 
				 */

				function uamswp_fad_schema_comedyclub(
					
				) {
					
				}

			// MovieTheater

				/*
				 * Thing > Organization > LocalBusiness > EntertainmentBusiness > MovieTheater
				 * 
				 * 
				 */

				function uamswp_fad_schema_movietheater(
					
				) {
					
				}

			// NightClub

				/*
				 * Thing > Organization > LocalBusiness > EntertainmentBusiness > NightClub
				 * 
				 * 
				 */

				function uamswp_fad_schema_nightclub(
					
				) {
					
				}

		// FinancialService

			/*
			 * Thing > Organization > LocalBusiness > FinancialService
			 * 
			 * 
			 */

			function uamswp_fad_schema_financialservice(
				
			) {
				
			}

			// AccountingService

				/*
				 * Thing > Organization > LocalBusiness > FinancialService > AccountingService
				 * 
				 * 
				 */

				function uamswp_fad_schema_accountingservice(
					
				) {
					
				}

			// AutomatedTeller

				/*
				 * Thing > Organization > LocalBusiness > FinancialService > AutomatedTeller
				 * 
				 * 
				 */

				function uamswp_fad_schema_automatedteller(
					
				) {
					
				}

			// BankOrCreditUnion

				/*
				 * Thing > Organization > LocalBusiness > FinancialService > BankOrCreditUnion
				 * 
				 * 
				 */

				function uamswp_fad_schema_bankorcreditunion(
					
				) {
					
				}

			// InsuranceAgency

				/*
				 * Thing > Organization > LocalBusiness > FinancialService > InsuranceAgency
				 * 
				 * 
				 */

				function uamswp_fad_schema_insuranceagency(
					
				) {
					
				}

		// FoodEstablishment

			/*
			 * Thing > Organization > LocalBusiness > FoodEstablishment
			 * 
			 * 
			 */

			function uamswp_fad_schema_foodestablishment(
				
			) {
				
			}

			// Bakery

				/*
				 * Thing > Organization > LocalBusiness > FoodEstablishment > Bakery
				 * 
				 * 
				 */

				function uamswp_fad_schema_bakery(
					
				) {
					
				}

			// BarOrPub

				/*
				 * Thing > Organization > LocalBusiness > FoodEstablishment > BarOrPub
				 * 
				 * 
				 */

				function uamswp_fad_schema_barorpub(
					
				) {
					
				}

			// Brewery

				/*
				 * Thing > Organization > LocalBusiness > FoodEstablishment > Brewery
				 * 
				 * 
				 */

				function uamswp_fad_schema_brewery(
					
				) {
					
				}

			// CafeOrCoffeeShop

				/*
				 * Thing > Organization > LocalBusiness > FoodEstablishment > CafeOrCoffeeShop
				 * 
				 * 
				 */

				function uamswp_fad_schema_cafeorcoffeeshop(
					
				) {
					
				}

			// Distillery

				/*
				 * Thing > Organization > LocalBusiness > FoodEstablishment > Distillery
				 * 
				 * 
				 */

				function uamswp_fad_schema_distillery(
					
				) {
					
				}

			// FastFoodRestaurant

				/*
				 * Thing > Organization > LocalBusiness > FoodEstablishment > FastFoodRestaurant
				 * 
				 * 
				 */

				function uamswp_fad_schema_fastfoodrestaurant(
					
				) {
					
				}

			// IceCreamShop

				/*
				 * Thing > Organization > LocalBusiness > FoodEstablishment > IceCreamShop
				 * 
				 * 
				 */

				function uamswp_fad_schema_icecreamshop(
					
				) {
					
				}

			// Restaurant

				/*
				 * Thing > Organization > LocalBusiness > FoodEstablishment > Restaurant
				 * 
				 * 
				 */

				function uamswp_fad_schema_restaurant(
					
				) {
					
				}

			// Winery

				/*
				 * Thing > Organization > LocalBusiness > FoodEstablishment > Winery
				 * 
				 * 
				 */

				function uamswp_fad_schema_winery(
					
				) {
					
				}

		// GovernmentOffice

			/*
			 * Thing > Organization > LocalBusiness > GovernmentOffice
			 * 
			 * 
			 */

			function uamswp_fad_schema_governmentoffice(
				
			) {
				
			}

			// PostOffice

				/*
				 * Thing > Organization > LocalBusiness > GovernmentOffice > PostOffice
				 * 
				 * 
				 */

				function uamswp_fad_schema_postoffice(
					
				) {
					
				}

		// HealthAndBeautyBusiness

			/*
			 * Thing > Organization > LocalBusiness > HealthAndBeautyBusiness
			 * 
			 * 
			 */

			function uamswp_fad_schema_healthandbeautybusiness(
				
			) {
				
			}

			// BeautySalon

				/*
				 * Thing > Organization > LocalBusiness > HealthAndBeautyBusiness > BeautySalon
				 * 
				 * 
				 */

				function uamswp_fad_schema_beautysalon(
					
				) {
					
				}

			// DaySpa

				/*
				 * Thing > Organization > LocalBusiness > HealthAndBeautyBusiness > DaySpa
				 * 
				 * 
				 */

				function uamswp_fad_schema_dayspa(
					
				) {
					
				}

			// HairSalon

				/*
				 * Thing > Organization > LocalBusiness > HealthAndBeautyBusiness > HairSalon
				 * 
				 * 
				 */

				function uamswp_fad_schema_hairsalon(
					
				) {
					
				}

			// HealthClub

				/*
				 * Thing > Organization > LocalBusiness > HealthAndBeautyBusiness > HealthClub
				 * 
				 * 
				 */

				function uamswp_fad_schema_healthclub(
					
				) {
					
				}

			// NailSalon

				/*
				 * Thing > Organization > LocalBusiness > HealthAndBeautyBusiness > NailSalon
				 * 
				 * 
				 */

				function uamswp_fad_schema_nailsalon(
					
				) {
					
				}

			// TattooParlor

				/*
				 * Thing > Organization > LocalBusiness > HealthAndBeautyBusiness > TattooParlor
				 * 
				 * 
				 */

				function uamswp_fad_schema_tattooparlor(
					
				) {
					
				}

		// HomeAndConstructionBusiness

			/*
			 * Thing > Organization > LocalBusiness > HomeAndConstructionBusiness
			 * 
			 * 
			 */

			function uamswp_fad_schema_homeandconstructionbusiness(
				
			) {
				
			}

			// Electrician

				/*
				 * Thing > Organization > LocalBusiness > HomeAndConstructionBusiness > Electrician
				 * 
				 * 
				 */

				function uamswp_fad_schema_electrician(
					
				) {
					
				}

			// GeneralContractor

				/*
				 * Thing > Organization > LocalBusiness > HomeAndConstructionBusiness > GeneralContractor
				 * 
				 * 
				 */

				function uamswp_fad_schema_generalcontractor(
					
				) {
					
				}

			// HVACBusiness

				/*
				 * Thing > Organization > LocalBusiness > HomeAndConstructionBusiness > HVACBusiness
				 * 
				 * 
				 */

				function uamswp_fad_schema_hvacbusiness(
					
				) {
					
				}

			// HousePainter

				/*
				 * Thing > Organization > LocalBusiness > HomeAndConstructionBusiness > HousePainter
				 * 
				 * 
				 */

				function uamswp_fad_schema_housepainter(
					
				) {
					
				}

			// Locksmith

				/*
				 * Thing > Organization > LocalBusiness > HomeAndConstructionBusiness > Locksmith
				 * 
				 * 
				 */

				function uamswp_fad_schema_locksmith(
					
				) {
					
				}

			// MovingCompany

				/*
				 * Thing > Organization > LocalBusiness > HomeAndConstructionBusiness > MovingCompany
				 * 
				 * 
				 */

				function uamswp_fad_schema_movingcompany(
					
				) {
					
				}

			// Plumber

				/*
				 * Thing > Organization > LocalBusiness > HomeAndConstructionBusiness > Plumber
				 * 
				 * 
				 */

				function uamswp_fad_schema_plumber(
					
				) {
					
				}

			// RoofingContractor

				/*
				 * Thing > Organization > LocalBusiness > HomeAndConstructionBusiness > RoofingContractor
				 * 
				 * 
				 */

				function uamswp_fad_schema_roofingcontractor(
					
				) {
					
				}

		// InternetCafe

			/*
			 * Thing > Organization > LocalBusiness > InternetCafe
			 * 
			 * 
			 */

			function uamswp_fad_schema_internetcafe(
				
			) {
				
			}

		// LegalService

			/*
			 * Thing > Organization > LocalBusiness > LegalService
			 * 
			 * 
			 */

			function uamswp_fad_schema_legalservice(
				
			) {
				
			}

			// Attorney

				/*
				 * Thing > Organization > LocalBusiness > LegalService > Attorney
				 * 
				 * 
				 */

				function uamswp_fad_schema_attorney(
					
				) {
					
				}

			// Notary

				/*
				 * Thing > Organization > LocalBusiness > LegalService > Notary
				 * 
				 * 
				 */

				function uamswp_fad_schema_notary(
					
				) {
					
				}

		// Library

			/*
			 * Thing > Organization > LocalBusiness > Library
			 * 
			 * 
			 */

			function uamswp_fad_schema_library(
				
			) {
				
			}

		// LodgingBusiness

			/*
			 * Thing > Organization > LocalBusiness > LodgingBusiness
			 * 
			 * 
			 */

			function uamswp_fad_schema_lodgingbusiness(
				
			) {
				
			}

			// BedAndBreakfast

				/*
				 * Thing > Organization > LocalBusiness > LodgingBusiness > BedAndBreakfast
				 * 
				 * 
				 */

				function uamswp_fad_schema_bedandbreakfast(
					
				) {
					
				}

			// Campground

				/*
				 * Thing > Organization > LocalBusiness > LodgingBusiness > Campground
				 * 
				 * 
				 */

				function uamswp_fad_schema_campground(
					
				) {
					
				}

			// Hostel

				/*
				 * Thing > Organization > LocalBusiness > LodgingBusiness > Hostel
				 * 
				 * 
				 */

				function uamswp_fad_schema_hostel(
					
				) {
					
				}

			// Hotel

				/*
				 * Thing > Organization > LocalBusiness > LodgingBusiness > Hotel
				 * 
				 * 
				 */

				function uamswp_fad_schema_hotel(
					
				) {
					
				}

			// Motel

				/*
				 * Thing > Organization > LocalBusiness > LodgingBusiness > Motel
				 * 
				 * 
				 */

				function uamswp_fad_schema_motel(
					
				) {
					
				}

			// Resort

				/*
				 * Thing > Organization > LocalBusiness > LodgingBusiness > Resort
				 * 
				 * 
				 */

				function uamswp_fad_schema_resort(
					
				) {
					
				}

				// SkiResort

					/*
					 * Thing > Organization > LocalBusiness > qux > quux > SkiResort
					 * 
					 * 
					 */

					function uamswp_fad_schema_skiresort(
						
					) {
						
					}

			// VacationRental

				/*
				 * Thing > Organization > LocalBusiness > LodgingBusiness > VacationRental
				 * 
				 * 
				 */

				function uamswp_fad_schema_vacationrental(
					
				) {
					
				}

		// MedicalBusiness

			/*
			 * Thing > Organization > LocalBusiness > MedicalBusiness
			 * 
			 *     Also: Thing > Place > LocalBusiness > MedicalBusiness
			 * 
			 * A particular physical or virtual business of an organization for medical 
			 * purposes. Examples of MedicalBusiness include different businesses run by 
			 * health professionals.
			 */

			function uamswp_fad_schema_medicalbusiness(
				$schema, // array // Main schema array,
				// MedicalBusiness (no property vars)
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

					// Inherited properties from Place

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

					// Inherited properties from LocalBusiness

						$currenciesAccepted = ( isset($currenciesAccepted) && !empty($currenciesAccepted) ) ? $currenciesAccepted : '';
						$openingHours = ( isset($openingHours) && !empty($openingHours) ) ? $openingHours : '';
						$paymentAccepted = ( isset($paymentAccepted) && !empty($paymentAccepted) ) ? $paymentAccepted : '';
						$priceRange = ( isset($priceRange) && !empty($priceRange) ) ? $priceRange : '';

					// Properties from MedicalBusiness

						// Do nothing

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

					// Properties from MedicalBusiness

						// Do nothing

				// Remove any empty values from the schema array

					$schema = array_filter($schema);

				return $schema;

			}

			// CommunityHealth

				/*
				 * Thing > Organization > LocalBusiness > MedicalBusiness > CommunityHealth
				 * 
				 * 
				 */

				function uamswp_fad_schema_communityhealth(
					
				) {
					
				}

			// Dentist

				/*
				 * Thing > Organization > LocalBusiness > MedicalBusiness > Dentist
				 * 
				 * 
				 */

				function uamswp_fad_schema_dentist(
					
				) {
					
				}

			// Dermatology

				/*
				 * Thing > Organization > LocalBusiness > MedicalBusiness > Dermatology
				 * 
				 * 
				 */

				function uamswp_fad_schema_dermatology(
					
				) {
					
				}

			// DietNutrition

				/*
				 * Thing > Organization > LocalBusiness > MedicalBusiness > DietNutrition
				 * 
				 * 
				 */

				function uamswp_fad_schema_dietnutrition(
					
				) {
					
				}

			// Emergency

				/*
				 * Thing > Organization > LocalBusiness > MedicalBusiness > Emergency
				 * 
				 * 
				 */

				function uamswp_fad_schema_emergency(
					
				) {
					
				}

			// Geriatric

				/*
				 * Thing > Organization > LocalBusiness > MedicalBusiness > Geriatric
				 * 
				 * 
				 */

				function uamswp_fad_schema_geriatric(
					
				) {
					
				}

			// Gynecologic

				/*
				 * Thing > Organization > LocalBusiness > MedicalBusiness > Gynecologic
				 * 
				 * 
				 */

				function uamswp_fad_schema_gynecologic(
					
				) {
					
				}

			// MedicalClinic

				/*
				 * Thing > Organization > LocalBusiness > MedicalBusiness > MedicalClinic
				 * 
				 *     Also: Thing > Organization > MedicalOrganization > MedicalClinic
				 *     Also: Thing > Place > LocalBusiness > MedicalBusiness > MedicalClinic
				 * 
				 * A facility, often associated with a hospital or medical school, that is devoted 
				 * to the specific diagnosis and/or healthcare. Previously limited to outpatients 
				 * but with evolution it may be open to inpatients as well.
				 */

				 function uamswp_fad_schema_medicalclinic(
					$schema, // array // Main schema array
					// MedicalClinic
						$availableService = '', // availableService
						$medicalSpecialty = '', // medicalSpecialty
					// MedicalBusiness (no property vars)
					// LocalBusiness
						$currenciesAccepted = '', // currenciesAccepted
						$openingHours = '', // openingHours
						$paymentAccepted = '', // paymentAccepted
						$priceRange = '', // priceRange
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

						// Inherited properties from Place

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

						// Inherited properties from LocalBusiness

							$currenciesAccepted = ( isset($currenciesAccepted) && !empty($currenciesAccepted) ) ? $currenciesAccepted : '';
							$openingHours = ( isset($openingHours) && !empty($openingHours) ) ? $openingHours : '';
							$paymentAccepted = ( isset($paymentAccepted) && !empty($paymentAccepted) ) ? $paymentAccepted : '';
							$priceRange = ( isset($priceRange) && !empty($priceRange) ) ? $priceRange : '';

						// Inherited properties from MedicalBusiness

							// Do nothing

						// Properties from MedicalClinic

							$availableService = ( isset($availableService) && !empty($availableService) ) ? $availableService : '';
							$medicalSpecialty = ( isset($medicalSpecialty) && !empty($medicalSpecialty) ) ? $medicalSpecialty : '';

					// Add values to the schema array

						// Inherited properties

							$schema = uamswp_fad_schema_medicalbusiness(
								$schema, // array // Main schema array
								// MedicalBusiness (no property vars)
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

							$schema = uamswp_fad_schema_medicalorganization(
								$schema, // array // Main schema array
								// MedicalOrganization
									$healthPlanNetworkId, // healthPlanNetworkId
									$isAcceptingNewPatients, // isAcceptingNewPatients
									$medicalSpecialty, // medicalSpecialty
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

						// Properties from MedicalClinic

							// availableService

								/* 
								 * Expected Type:
								 *     MedicalProcedure
								 *     MedicalTest
								 *     MedicalTherapy
								 * 
								 * A medical service available from this provider.
								 */

								$schema['availableService'] = $availableService;

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

				// CovidTestingFacility

					/*
					 * Thing > Organization > LocalBusiness > qux > quux > CovidTestingFacility
					 * 
					 * 
					 */

					function uamswp_fad_schema_covidtestingfacility(
						
					) {
						
					}

			// Midwifery

				/*
				 * Thing > Organization > LocalBusiness > qux > Midwifery
				 * 
				 * 
				 */

				function uamswp_fad_schema_midwifery(
					
				) {
					
				}

			// Nursing

				/*
				 * Thing > Organization > LocalBusiness > qux > Nursing
				 * 
				 * 
				 */

				function uamswp_fad_schema_nursing(
					
				) {
					
				}

			// Obstetric

				/*
				 * Thing > Organization > LocalBusiness > qux > Obstetric
				 * 
				 * 
				 */

				function uamswp_fad_schema_obstetric(
					
				) {
					
				}

			// Oncologic

				/*
				 * Thing > Organization > LocalBusiness > qux > Oncologic
				 * 
				 * 
				 */

				function uamswp_fad_schema_oncologic(
					
				) {
					
				}

			// Optician

				/*
				 * Thing > Organization > LocalBusiness > qux > Optician
				 * 
				 * 
				 */

				function uamswp_fad_schema_optician(
					
				) {
					
				}

			// Optometric

				/*
				 * Thing > Organization > LocalBusiness > qux > Optometric
				 * 
				 * 
				 */

				function uamswp_fad_schema_optometric(
					
				) {
					
				}

			// Otolaryngologic

				/*
				 * Thing > Organization > LocalBusiness > qux > Otolaryngologic
				 * 
				 * 
				 */

				function uamswp_fad_schema_otolaryngologic(
					
				) {
					
				}

			// Pediatric

				/*
				 * Thing > Organization > LocalBusiness > qux > Pediatric
				 * 
				 * 
				 */

				function uamswp_fad_schema_pediatric(
					
				) {
					
				}

			// Pharmacy

				/*
				 * Thing > Organization > LocalBusiness > qux > Pharmacy
				 * 
				 * 
				 */

				function uamswp_fad_schema_pharmacy(
					
				) {
					
				}

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
					$schema, // array // Main schema array
					// Physician
						$availableService = '', // availableService
						$hospitalAffiliation = '', // hospitalAffiliation
						$medicalSpecialty = '', // medicalSpecialty
					// MedicalBusiness (no property vars)
					// LocalBusiness
						$currenciesAccepted = '', // currenciesAccepted
						$openingHours = '', // openingHours
						$paymentAccepted = '', // paymentAccepted
						$priceRange = '', // priceRange
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

						// Inherited properties from Place

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

						// Inherited properties from LocalBusiness

							$currenciesAccepted = ( isset($currenciesAccepted) && !empty($currenciesAccepted) ) ? $currenciesAccepted : '';
							$openingHours = ( isset($openingHours) && !empty($openingHours) ) ? $openingHours : '';
							$paymentAccepted = ( isset($paymentAccepted) && !empty($paymentAccepted) ) ? $paymentAccepted : '';
							$priceRange = ( isset($priceRange) && !empty($priceRange) ) ? $priceRange : '';

						// Inherited properties from MedicalBusiness

							// Do nothing

						// Properties from Physician

							$availableService = ( isset($availableService) && !empty($availableService) ) ? $availableService : '';
							$hospitalAffiliation = ( isset($hospitalAffiliation) && !empty($hospitalAffiliation) ) ? $hospitalAffiliation : '';
							$medicalSpecialty = ( isset($medicalSpecialty) && !empty($medicalSpecialty) ) ? $medicalSpecialty : '';

					// Add values to the schema array

						// Inherited properties

							$schema = uamswp_fad_schema_medicalbusiness(
								$schema, // array // Main schema array
								// MedicalBusiness (no property vars)
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

							$schema = uamswp_fad_schema_medicalorganization(
								$schema, // array // Main schema array
								// MedicalOrganization
									$healthPlanNetworkId, // healthPlanNetworkId
									$isAcceptingNewPatients, // isAcceptingNewPatients
									$medicalSpecialty, // medicalSpecialty
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

						// Properties from Physician

							// availableService

								/* 
								 * Expected Type:
								 *     MedicalProcedure
								 *     MedicalTest
								 *     MedicalTherapy
								 * 
								 * A medical service available from this provider.
								 */

								$schema['availableService'] = $availableService;

							// hospitalAffiliation

								/* 
								 * Expected Type:
								 *     Hospital
								 * 
								 * A hospital with which the physician or office is affiliated.
								 */

								$schema['hospitalAffiliation'] = $hospitalAffiliation;

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

			// Physiotherapy

				/*
				 * Thing > Organization > LocalBusiness > qux > Physiotherapy
				 * 
				 * 
				 */

				function uamswp_fad_schema_physiotherapy(
					
				) {
					
				}

			// PlasticSurgery

				/*
				 * Thing > Organization > LocalBusiness > qux > PlasticSurgery
				 * 
				 * 
				 */

				function uamswp_fad_schema_plasticsurgery(
					
				) {
					
				}

			// Podiatric

				/*
				 * Thing > Organization > LocalBusiness > qux > Podiatric
				 * 
				 * 
				 */

				function uamswp_fad_schema_podiatric(
					
				) {
					
				}

			// PrimaryCare

				/*
				 * Thing > Organization > LocalBusiness > qux > PrimaryCare
				 * 
				 * 
				 */

				function uamswp_fad_schema_primarycare(
					
				) {
					
				}

			// Psychiatric

				/*
				 * Thing > Organization > LocalBusiness > qux > Psychiatric
				 * 
				 * 
				 */

				function uamswp_fad_schema_psychiatric(
					
				) {
					
				}

			// PublicHealth

				/*
				 * Thing > Organization > LocalBusiness > MedicalBusiness > PublicHealth
				 * 
				 * 
				 */

				function uamswp_fad_schema_publichealth(
					
				) {
					
				}

		// ProfessionalService

			/*
			 * Thing > Organization > LocalBusiness > ProfessionalService
			 * 
			 * 
			 */

			function uamswp_fad_schema_professionalservice(
				
			) {
				
			}

		// RadioStation

			/*
			 * Thing > Organization > LocalBusiness > RadioStation
			 * 
			 * 
			 */

			function uamswp_fad_schema_radiostation(
				
			) {
				
			}

		// RealEstateAgent

			/*
			 * Thing > Organization > LocalBusiness > RealEstateAgent
			 * 
			 * 
			 */

			function uamswp_fad_schema_realestateagent(
				
			) {
				
			}

		// RecyclingCenter

			/*
			 * Thing > Organization > LocalBusiness > RecyclingCenter
			 * 
			 * 
			 */

			function uamswp_fad_schema_recyclingcenter(
				
			) {
				
			}

		// SelfStorage

			/*
			 * Thing > Organization > LocalBusiness > SelfStorage
			 * 
			 * 
			 */

			function uamswp_fad_schema_selfstorage(
				
			) {
				
			}

		// ShoppingCenter

			/*
			 * Thing > Organization > LocalBusiness > ShoppingCenter
			 * 
			 * 
			 */

			function uamswp_fad_schema_shoppingcenter(
				
			) {
				
			}

		// SportsActivityLocation

			/*
			 * Thing > Organization > LocalBusiness > SportsActivityLocation
			 * 
			 * 
			 */

			function uamswp_fad_schema_sportsactivitylocation(
				
			) {
				
			}

			// BowlingAlley

				/*
				 * Thing > Organization > LocalBusiness > SportsActivityLocation > BowlingAlley
				 * 
				 * 
				 */

				function uamswp_fad_schema_bowlingalley(
					
				) {
					
				}

			// ExerciseGym

				/*
				 * Thing > Organization > LocalBusiness > SportsActivityLocation > ExerciseGym
				 * 
				 * 
				 */

				function uamswp_fad_schema_exercisegym(
					
				) {
					
				}

			// GolfCourse

				/*
				 * Thing > Organization > LocalBusiness > SportsActivityLocation > GolfCourse
				 * 
				 * 
				 */

				function uamswp_fad_schema_golfcourse(
					
				) {
					
				}

			// HealthClub

				/*
				 * Thing > Organization > LocalBusiness > SportsActivityLocation > HealthClub
				 * 
				 * 
				 */

				function uamswp_fad_schema_healthclub(
					
				) {
					
				}

			// PublicSwimmingPool

				/*
				 * Thing > Organization > LocalBusiness > SportsActivityLocation > PublicSwimmingPool
				 * 
				 * 
				 */

				function uamswp_fad_schema_publicswimmingpool(
					
				) {
					
				}

			// SkiResort

				/*
				 * Thing > Organization > LocalBusiness > SportsActivityLocation > SkiResort
				 * 
				 * 
				 */

				function uamswp_fad_schema_skiresort(
					
				) {
					
				}

			// SportsClub

				/*
				 * Thing > Organization > LocalBusiness > SportsActivityLocation > SportsClub
				 * 
				 * 
				 */

				function uamswp_fad_schema_sportsclub(
					
				) {
					
				}

			// StadiumOrArena

				/*
				 * Thing > Organization > LocalBusiness > SportsActivityLocation > StadiumOrArena
				 * 
				 * 
				 */

				function uamswp_fad_schema_stadiumorarena(
					
				) {
					
				}

			// TennisComplex

				/*
				 * Thing > Organization > LocalBusiness > SportsActivityLocation > TennisComplex
				 * 
				 * 
				 */

				function uamswp_fad_schema_tenniscomplex(
					
				) {
					
				}

		// Store

			/*
			 * Thing > Organization > LocalBusiness > Store
			 * 
			 * 
			 */

			function uamswp_fad_schema_store(
				
			) {
				
			}

			// AutoPartsStore

				/*
				 * Thing > Organization > LocalBusiness > Store > AutoPartsStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_autopartsstore(
					
				) {
					
				}

			// BikeStore

				/*
				 * Thing > Organization > LocalBusiness > Store > BikeStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_bikestore(
					
				) {
					
				}

			// BookStore

				/*
				 * Thing > Organization > LocalBusiness > Store > BookStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_bookstore(
					
				) {
					
				}

			// ClothingStore

				/*
				 * Thing > Organization > LocalBusiness > Store > ClothingStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_clothingstore(
					
				) {
					
				}

			// ComputerStore

				/*
				 * Thing > Organization > LocalBusiness > Store > ComputerStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_computerstore(
					
				) {
					
				}

			// ConvenienceStore

				/*
				 * Thing > Organization > LocalBusiness > Store > ConvenienceStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_conveniencestore(
					
				) {
					
				}

			// DepartmentStore

				/*
				 * Thing > Organization > LocalBusiness > Store > DepartmentStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_departmentstore(
					
				) {
					
				}

			// ElectronicsStore

				/*
				 * Thing > Organization > LocalBusiness > Store > ElectronicsStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_electronicsstore(
					
				) {
					
				}

			// Florist

				/*
				 * Thing > Organization > LocalBusiness > Store > Florist
				 * 
				 * 
				 */

				function uamswp_fad_schema_florist(
					
				) {
					
				}

			// FurnitureStore

				/*
				 * Thing > Organization > LocalBusiness > Store > FurnitureStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_furniturestore(
					
				) {
					
				}

			// GardenStore

				/*
				 * Thing > Organization > LocalBusiness > Store > GardenStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_gardenstore(
					
				) {
					
				}

			// GroceryStore

				/*
				 * Thing > Organization > LocalBusiness > Store > GroceryStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_grocerystore(
					
				) {
					
				}

			// HardwareStore

				/*
				 * Thing > Organization > LocalBusiness > Store > HardwareStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_hardwarestore(
					
				) {
					
				}

			// HobbyShop

				/*
				 * Thing > Organization > LocalBusiness > Store > HobbyShop
				 * 
				 * 
				 */

				function uamswp_fad_schema_hobbyshop(
					
				) {
					
				}

			// HomeGoodsStore

				/*
				 * Thing > Organization > LocalBusiness > Store > HomeGoodsStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_homegoodsstore(
					
				) {
					
				}

			// JewelryStore

				/*
				 * Thing > Organization > LocalBusiness > Store > JewelryStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_jewelrystore(
					
				) {
					
				}

			// LiquorStore

				/*
				 * Thing > Organization > LocalBusiness > Store > LiquorStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_liquorstore(
					
				) {
					
				}

			// MensClothingStore

				/*
				 * Thing > Organization > LocalBusiness > Store > MensClothingStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_mensclothingstore(
					
				) {
					
				}

			// MobilePhoneStore

				/*
				 * Thing > Organization > LocalBusiness > Store > MobilePhoneStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_mobilephonestore(
					
				) {
					
				}

			// MovieRentalStore

				/*
				 * Thing > Organization > LocalBusiness > Store > MovieRentalStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_movierentalstore(
					
				) {
					
				}

			// MusicStore

				/*
				 * Thing > Organization > LocalBusiness > Store > MusicStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_musicstore(
					
				) {
					
				}

			// OfficeEquipmentStore

				/*
				 * Thing > Organization > LocalBusiness > Store > OfficeEquipmentStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_officeequipmentstore(
					
				) {
					
				}

			// OutletStore

				/*
				 * Thing > Organization > LocalBusiness > Store > OutletStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_outletstore(
					
				) {
					
				}

			// PawnShop

				/*
				 * Thing > Organization > LocalBusiness > Store > PawnShop
				 * 
				 * 
				 */

				function uamswp_fad_schema_pawnshop(
					
				) {
					
				}

			// PetStore

				/*
				 * Thing > Organization > LocalBusiness > Store > PetStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_petstore(
					
				) {
					
				}

			// ShoeStore

				/*
				 * Thing > Organization > LocalBusiness > Store > ShoeStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_shoestore(
					
				) {
					
				}

			// SportingGoodsStore

				/*
				 * Thing > Organization > LocalBusiness > Store > SportingGoodsStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_sportinggoodsstore(
					
				) {
					
				}

			// TireShop

				/*
				 * Thing > Organization > LocalBusiness > Store > TireShop
				 * 
				 * 
				 */

				function uamswp_fad_schema_tireshop(
					
				) {
					
				}

			// ToyStore

				/*
				 * Thing > Organization > LocalBusiness > Store > ToyStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_toystore(
					
				) {
					
				}

			// WholesaleStore

				/*
				 * Thing > Organization > LocalBusiness > Store > WholesaleStore
				 * 
				 * 
				 */

				function uamswp_fad_schema_wholesalestore(
					
				) {
					
				}

		// TelevisionStation

			/*
			 * Thing > Organization > LocalBusiness > TelevisionStation
			 * 
			 * 
			 */

			function uamswp_fad_schema_televisionstation(
				
			) {
				
			}

		// TouristInformationCenter

			/*
			 * Thing > Organization > LocalBusiness > TouristInformationCenter
			 * 
			 * 
			 */

			function uamswp_fad_schema_touristinformationcenter(
				
			) {
				
			}

		// TravelAgency

			/*
			 * Thing > Organization > LocalBusiness > TravelAgency
			 * 
			 * 
			 */

			function uamswp_fad_schema_travelagency(
				
			) {
				
			}

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