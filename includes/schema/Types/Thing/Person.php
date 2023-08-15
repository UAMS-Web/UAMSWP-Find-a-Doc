<?php

// Person

	/*
	 * Thing > Person
	 * 
	 * A person (alive, dead, undead, or fictional).
	 */

	function uamswp_fad_schema_person(
		$schema, // array // Main schema array
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

			// Properties from Person (Thing > Person)

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

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_thing(
					// Thing
						$schema, // array // Main schema array
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

			// Properties from Person

				// additionalName

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * An additional name for a Person, can be used for a middle name.
					 */

					$schema['additionalName'] = $additionalName;

				// address

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > ContactPoint > PostalAddress
					 *     DataType > Text
					 * 
					 * Physical address of the item.
					 */

					$schema['address'] = $address;

				// affiliation

					/* 
					 * Expected Type:
					 *     Thing > Organization
					 * 
					 * An organization that this person is affiliated with. For example, a 
					 * school/university, a club, or a team.
					 */

					$schema['affiliation'] = $affiliation;

				// alumniOf

					/* 
					 * Expected Type:
					 *     EducationalOrganization
					 *     Thing > Organization
					 * 
					 * An organization that the person is an alumni of.
					 * 
					 * Inverse property: alumni
					 */

					$schema['alumniOf'] = $alumniOf;

				// award

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * An award won by or for this item. Supersedes awards.
					 */

					$schema['award'] = $award;

				// birthDate

					/* 
					 * Expected Type:
					 *     DataType > Date
					 * 
					 * Date of birth.
					 */

					$schema['birthDate'] = $birthDate;

				// birthPlace

					/* 
					 * Expected Type:
					 *     Thing > Place
					 * 
					 * The place where the person was born.
					 */

					$schema['birthPlace'] = $birthPlace;

				// brand

					/* 
					 * Expected Type:
					 *     Thing > Intangible > Brand
					 *     Thing > Organization
					 * 
					 * The brand(s) associated with a product or service, or the brand(s) maintained 
					 * by an organization or business person.
					 */

					$schema['brand'] = $brand;

				// callSign

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * A callsign, as used in broadcasting and radio communications to identify 
					 * people, radio and TV stations, or vehicles.
					 */

					$schema['callSign'] = $callSign;

				// children

					/* 
					 * Expected Type:
					 *     Thing > Person
					 * 
					 * A child of the person.
					 */

					$schema['children'] = $children;

				// colleague

					/* 
					 * Expected Type:
					 *     Thing > Person
					 *     DataType > Text > URL
					 * 
					 * A colleague of the person. Supersedes colleagues.
					 */

					$schema['colleague'] = $colleague;

				// contactPoint

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > ContactPoint
					 * 
					 * A contact point for a person or organization. Supersedes contactPoints.
					 */

					$schema['contactPoint'] = $contactPoint;

				// deathDate

					/* 
					 * Expected Type:
					 *     DataType > Date
					 * 
					 * Date of death.
					 */

					$schema['deathDate'] = $deathDate;

				// deathPlace

					/* 
					 * Expected Type:
					 *     Thing > Place
					 * 
					 * The place where the person died.
					 */

					$schema['deathPlace'] = $deathPlace;

				// duns

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The Dun & Bradstreet DUNS number for identifying an organization or business 
					 * person.
					 */

					$schema['duns'] = $duns;

				// email

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * Email address.
					 */

					$schema['email'] = $email;

				// familyName

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * Family name. In the U.S., the last name of a Person.
					 */

					$schema['familyName'] = $familyName;

				// faxNumber

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The fax number.
					 */

					$schema['faxNumber'] = $faxNumber;

				// follows

					/* 
					 * Expected Type:
					 *     Thing > Person
					 * 
					 * The most generic uni-directional social relation.
					 */

					$schema['follows'] = $follows;

				// funder

					/* 
					 * Expected Type:
					 *     Thing > Organization
					 *     Thing > Person
					 * 
					 * A person or organization that supports (sponsors) something through some kind 
					 * of financial contribution.
					 */

					$schema['funder'] = $funder;

				// funding

					/* 
					 * Expected Type:
					 *     Thing > Intangible > Grant
					 * 
					 * A Grant that directly or indirectly provide funding or sponsorship for this 
					 * item. See also ownershipFundingInfo.
					 * 
					 * Inverse property: fundedItem
					 */

					$schema['funding'] = $funding;

				// gender

					/* 
					 * Expected Type:
					 *     GenderType
					 *     DataType > Text
					 * 
					 * Gender of something, typically a Person, but possibly also fictional 
					 * characters, animals, etc. While https://schema.org/Male and 
					 * https://schema.org/Female may be used, text strings are also acceptable for 
					 * people who do not identify as a binary gender. The gender property can also be 
					 * used in an extended sense to cover (e.g., the gender of sports teams). As with 
					 * the gender of individuals, we do not try to enumerate all possibilities. A 
					 * mixed-gender SportsTeam can be indicated with a text value of "Mixed".
					 */

					$schema['gender'] = $gender;

				// givenName

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * Given name. In the U.S., the first name of a Person.
					 */

					$schema['givenName'] = $givenName;

				// globalLocationNumber

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The Global Location Number (GLN, sometimes also referred to as International 
					 * Location Number or ILN) of the respective organization, person, or place. The 
					 * GLN is a 13-digit number used to identify parties and physical locations.
					 */

					$schema['globalLocationNumber'] = $globalLocationNumber;

				// hasCredential

					/* 
					 * Expected Type:
					 *     Thing > CreativeWork > EducationalOccupationalCredential
					 * 
					 * A credential awarded to the Person or Organization.
					 */

					$schema['hasCredential'] = $hasCredential;

				// hasOccupation

					/* 
					 * Expected Type:
					 *     Occupation
					 * 
					 * The Person's occupation. For past professions, use Role for expressing dates.
					 */

					$schema['hasOccupation'] = $hasOccupation;

				// hasOfferCatalog

					/* 
					 * Expected Type:
					 *     Thing > Intangible > ItemList > OfferCatalog
					 * 
					 * Indicates an OfferCatalog listing for this Organization, Person, or Service.
					 */

					$schema['hasOfferCatalog'] = $hasOfferCatalog;

				// hasPOS

					/* 
					 * Expected Type:
					 *     Thing > Place
					 * 
					 * Points-of-Sales operated by the organization or person.
					 */

					$schema['hasPOS'] = $hasPOS;

				// height

					/* 
					 * Expected Type:
					 *     Distance
					 *     Thing > Intangible > StructuredValue > QuantitativeValue
					 * 
					 * The height of the item.
					 */

					$schema['height'] = $height;

				// homeLocation

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > ContactPoint
					 *     Thing > Place
					 * 
					 * A contact location for a person's residence.
					 */

					$schema['homeLocation'] = $homeLocation;

				// honorificPrefix

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * An honorific prefix preceding a Person's name such as Dr/Mrs/Mr.
					 */

					$schema['honorificPrefix'] = $honorificPrefix;

				// honorificSuffix

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * An honorific suffix following a Person's name such as M.D./PhD/MSCSW.
					 */

					$schema['honorificSuffix'] = $honorificSuffix;

				// interactionStatistic

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > InteractionCounter
					 * 
					 * The number of interactions for the CreativeWork using the WebSite or 
					 * SoftwareApplication. The most specific child type of InteractionCounter should 
					 * be used. Supersedes interactionCount.
					 */

					$schema['interactionStatistic'] = $interactionStatistic;

				// isicV4

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The International Standard of Industrial Classification of All Economic 
					 * Activities (ISIC), Revision 4 code for a particular organization, business 
					 * person, or place.
					 */

					$schema['isicV4'] = $isicV4;

				// jobTitle

					/* 
					 * Expected Type:
					 *     Thing > Intangible > DefinedTerm
					 *     DataType > Text
					 * 
					 * The job title of the person (for example, Financial Manager).
					 */

					$schema['jobTitle'] = $jobTitle;

				// knows

					/* 
					 * Expected Type:
					 *     Thing > Person
					 * 
					 * The most generic bi-directional social/work relation.
					 */

					$schema['knows'] = $knows;

				// knowsAbout

					/* 
					 * Expected Type:
					 *     DataType > Text
					 *     Thing
					 *     DataType > Text > URL
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
					 *     Thing > Intangible > Language
					 *     DataType > Text
					 * 
					 * Of a Person, and less typically of an Organization, to indicate a known 
					 * language. We do not distinguish skill levels or 
					 * reading/writing/speaking/signing here. Use language codes from the IETF BCP 47 
					 * standard.
					 */

					$schema['knowsLanguage'] = $knowsLanguage;

				// makesOffer

					/* 
					 * Expected Type:
					 *     Thing > Intangible > Offer
					 * 
					 * A pointer to products or services offered by the organization or person.
					 * 
					 * Inverse property: offeredBy
					 */

					$schema['makesOffer'] = $makesOffer;

				// memberOf

					/* 
					 * Expected Type:
					 *     Thing > Organization
					 *     Thing > Intangible > ProgramMembership
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
					 *     DataType > Text
					 * 
					 * The North American Industry Classification System (NAICS) code for a particular 
					 * organization or business person.
					 */

					$schema['naics'] = $naics;

				// nationality

					/* 
					 * Expected Type:
					 *     Country
					 * 
					 * Nationality of the person.
					 */

					$schema['nationality'] = $nationality;

				// netWorth

					/* 
					 * Expected Type:
					 *     MonetaryAmount
					 *     PriceSpecification
					 * 
					 * The total financial value of the person as calculated by subtracting assets 
					 * from liabilities.
					 */

					$schema['netWorth'] = $netWorth;

				// owns

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > OwnershipInfo
					 *     Thing > Product
					 * 
					 * Products owned by the organization or person.
					 */

					$schema['owns'] = $owns;

				// parent

					/* 
					 * Expected Type:
					 *     Thing > Person
					 * 
					 * A parent of this person. Supersedes parents.
					 */

					$schema['parent'] = $parent;

				// performerIn

					/* 
					 * Expected Type:
					 *     Thing > Event
					 * 
					 * Event that this person is a performer or participant in.
					 */

					$schema['performerIn'] = $performerIn;

				// publishingPrinciples

					/* 
					 * Expected Type:
					 *     Thing > CreativeWork
					 *     DataType > Text > URL
					 * 
					 * The publishingPrinciples property indicates (typically via URL) a document 
					 * describing the editorial principles of an Organization (or individual, e.g., a 
					 * Person writing a blog) that relate to their activities as a publisher (e.g., 
					 * ethics or diversity policies). When applied to a CreativeWork (e.g., 
					 * NewsArticle) the principles are those of the party primarily responsible for 
					 * the creation of the CreativeWork.
					 */

					$schema['publishingPrinciples'] = $publishingPrinciples;

				// relatedTo

					/* 
					 * Expected Type:
					 *     Thing > Person
					 * 
					 * The most generic familial relation.
					 */

					$schema['relatedTo'] = $relatedTo;

				// seeks

					/* 
					 * Expected Type:
					 *     Thing > Intangible > Demand
					 * 
					 * A pointer to products or services sought by the organization or person (demand).
					 */

					$schema['seeks'] = $seeks;

				// sibling

					/* 
					 * Expected Type:
					 *     Thing > Person
					 * 
					 * A sibling of the person. Supersedes siblings.
					 */

					$schema['sibling'] = $sibling;

				// sponsor

					/* 
					 * Expected Type:
					 *     Thing > Organization
					 *     Thing > Person
					 * 
					 * A person or organization that supports a thing through a pledge, promise, or 
					 * financial contribution (e.g., a sponsor of a Medical Study or a corporate 
					 * sponsor of an event).
					 */

					$schema['sponsor'] = $sponsor;

				// spouse

					/* 
					 * Expected Type:
					 *     Thing > Person
					 * 
					 * The person's spouse.
					 */

					$schema['spouse'] = $spouse;

				// taxID

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The Tax / Fiscal ID of the organization or person (e.g.,  the TIN in the US or 
					 * the CIF/NIF in Spain).
					 */

					$schema['taxID'] = $taxID;

				// telephone

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The telephone number.
					 */

					$schema['telephone'] = $telephone;

				// vatID

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The Value-added Tax ID of the organization or person.
					 */

					$schema['vatID'] = $vatID;

				// weight

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > QuantitativeValue
					 * 
					 * The weight of the product or person.
					 */

					$schema['weight'] = $weight;

				// workLocation

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > ContactPoint
					 *     Thing > Place
					 * 
					 * A contact location for a person's place of work.
					 */

					$schema['workLocation'] = $workLocation;

				// worksFor

					/* 
					 * Expected Type:
					 *     Thing > Organization
					 * 
					 * Organizations that the person works for.
					 */

					$schema['worksFor'] = $worksFor;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// Patient
	include_once __DIR__ . '/Person/Patient.php';