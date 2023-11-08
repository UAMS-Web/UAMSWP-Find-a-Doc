<?php
/*
 * Template Name: Schema Data Script Tag
 *
 * Description: A template part that displays the schema data script tag
 *
 * Required vars:
 * 	$schema_type // string
 * 	$schema_name // string
 * 	$schema_address // array
 *
 * Optional vars:
 * 	$schema_aggregate_rating; // bool
 * 	$schema_aggregate_rating_value; // string
 * 	$schema_aggregate_rating_count; // int
 * 	$schema_aggregate_rating_review_count; // int
 * 	$schema_geo_coordinates; // array
 * 	$schema_hospital_affiliation; // array
 * 	$schema_OpeningHoursSpecification // array
 * 	$schema_telephone // array
 * 	$schema_url // string
 * 	$schema_fax_number // string
 * 	$schema_description // string
 * 	$schema_image // string|array
 * 	$schema_medical_specialty // array
 * 	$schema_opening_hours // array
 */

// Check/define variables

	$schema_line_break = "\n"; // the double quotes are important

	// Schema Type
	$schema_type = isset($schema_type) ? $schema_type : '';

	// Required for Google Structured Data
	// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

		// Property: name
			// Expected Type: Text
			// Description: The name of the business.

			$schema_name = isset($schema_name) ? $schema_name : '';

		// Property: address
			// 	Expected Type: PostalAddress or Text
			// 	Description: The physical location of the business.
			//
			// Google Structured Data Documentation:
			// 	- Include as many properties as possible. The more properties you provide, the higher quality the result is to users.

			$schema_address = ( isset($schema_address) && is_array($schema_address) && !empty($schema_address) ) ? $schema_address : array();

	// Recommended by Google Structured Data
	// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

		// Property: aggregateRating
			// 	Expected Type: AggregateRating
			// 	Description: The overall rating, based on a collection of reviews or ratings, of the item.
			//
			// Google Structured Data Documentation:
			// 	- For sites that capture reviews about other local businesses: The average rating of the local business based on multiple ratings or reviews. Follow the Review snippet guidelines and the list of required and recommended aggregate rating properties.

			$schema_aggregate_rating_value = isset($schema_aggregate_rating_value) ? $schema_aggregate_rating_value : '';
			$schema_aggregate_rating_count = isset($schema_aggregate_rating_count) ? $schema_aggregate_rating_count : '';
			$schema_aggregate_rating_review_count = isset($schema_aggregate_rating_review_count) ? $schema_aggregate_rating_review_count : '';
			$schema_aggregate_rating_review_count = ( 0 != $schema_aggregate_rating_review_count || '0' != $schema_aggregate_rating_review_count ) ? $schema_aggregate_rating_review_count : '';
			$schema_aggregate_rating = ( isset($schema_aggregate_rating) && ( $schema_aggregate_rating_value || $schema_aggregate_rating_count || $schema_aggregate_rating_review_count) ) ? $schema_aggregate_rating : false;

		// Property: department
			// 	Expected Type: Organization
			// 	Description: A relationship between an organization and a department of that organization, also described as an organization (allowing different urls, logos, opening hours). For example: a store with a pharmacy, or a bakery with a cafe.
			//
			// Google Structured Data Documentation:
			// 	- A nested item for a single department. You can define any of the properties in this table for a department.
			// 	- Include the store name with the department name in the following format: {store name} {department name}. For example, gMart and gMart Pharmacy.
			// 	- If the department name is explicitly branded, specify a department name by itself. For example: Best Buy and Geek Squad.

		// Property: geo
			// 	Expected Type: GeoCoordinates or GeoShape
			// 	Description: The geo coordinates of the place.
			//
			// Google Structured Data Documentation:
			// 	- Geographic coordinates of the business
			// 	- latitude and longitude: The precision must be at least 5 decimal places.
			$schema_geo_coordinates = isset($schema_geo_coordinates) ? $schema_geo_coordinates : '';

		// Property: openingHoursSpecification
			// 	Expected Type: OpeningHoursSpecification
			// 	Description: The opening hours of a certain place.
			//
			// Google Structured Data Documentation:
			// 	- opens and closes: hh:mm:ss format.
			// 	- validFrom and validThrough: YYYY-MM-DD format

			$schema_OpeningHoursSpecification = isset($schema_OpeningHoursSpecification) ? $schema_OpeningHoursSpecification : '';

		// Property: priceRange
			// 	Expected Type: Text
			// 	Description: The price range of the business, for example $$$.
			//
			// Google Structured Data Documentation:
			// 	- The relative price range of a business, commonly specified by either a numerical range (for example, "$10-15") or a normalized number of currency signs (for example, "$$$").
			// 	- This field must be shorter than 100 characters. If it's longer than 100 characters, Google won't show a price range for the business.

		// Property: review
			// 	Expected Type: Review
			// 	Description: A review of the item. Supersedes reviews.
			//
			// Google Structured Data Documentation:
			// 	- For sites that capture reviews about other local businesses: A review of the local business. Follow the Review snippet guidelines and the list of required and recommended review properties.

		// Property: telephone
			// 	Expected Type: Text
			// 	Description: The telephone number.
			//
			// Google Structured Data Documentation:
			// 	- A business phone number meant to be the primary contact method for customers.
			// 	- Be sure to include the country code and area code in the phone number.

			$schema_telephone = ( isset($schema_telephone) && is_array($schema_telephone) && !empty($schema_telephone) ) ? $schema_telephone : array();

		// Property: url
			// 	Expected Type: URL
			// 	Description: URL of the item.
			//
			// Google Structured Data Documentation:
			// 	- The fully-qualified URL of the specific business location.
			// 	- The URL must be a working link.

			$schema_url = isset($schema_url) ? $schema_url : '';

	// Additional Selected Properties

		// Property: availableService
			// Expected Type: MedicalProcedure or MedicalTest or MedicalTherapy
			// Description: A medical service available from this provider.

			$schema_available_service = ( isset($schema_available_service) && is_array($schema_available_service) && !empty($schema_available_service) ) ? $schema_available_service : array(); // array

		// Property: faxNumber
			// Expected Type: Text
			// Description: The fax number.

			$schema_fax_number = ( isset($schema_fax_number) && is_array($schema_fax_number) && !empty($schema_fax_number) ) ? $schema_fax_number : array(); // array

		// Property: description
			// 	Expected Type: Text or TextObject
			// 	Description: A description of the item.

			$schema_description = isset($schema_description) ? $schema_description : '';

		// Property: hospitalAffiliation
			// Expected Type: Hospital
			// Description: A hospital with which the physician or office is affiliated.

			$schema_hospital_affiliation = isset($schema_hospital_affiliation) ? $schema_hospital_affiliation : '';

		// Property: image
			// 	Expected Type: ImageObject or URL
			// 	Description: One or more images of the MedicalClinic.
			//
			// Google Structured Data Documentation:
			// 	- Image URLs must be crawlable and indexable. To check if Google can access your URLs, use the URL Inspection tool.
			// 	- Images must be in a file format that's supported by Google Images.
			// 	- For best results, Google recommends providing multiple high-resolution images (minimum of 50K pixels when multiplying width and height) with the following aspect ratios: 16x9, 4x3, and 1x1.

			$schema_image = isset($schema_image) ? $schema_image : '';

		// Property: logo
			// 	Expected Type: ImageObject or URL
			// 	Description: An associated logo.
			//
			// Google Structured Data Documentation:
			// 	- A logo that is representative of the organization.
			// 	- The image must be 112x112px, at minimum.
			// 	- Make sure the image looks how you intend it to look on a purely white background (for example, if the logo is mostly white or gray, it may not look how you want it to look when displayed on a white background).
			// 	- If you use the ImageObject type, make sure that it has a valid contentUrl property or url property that follows the same guidelines as a URL type.

			$schema_logo = get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_horizontal_dark_386x50.png';

		// Property: medicalSpecialty
			// 	Expected Type: MedicalSpecialty
			// 	Description: A medical specialty of the provider.

			$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();

		// Property: openingHours
			// 	Expected Type: Text
			// 	Description: The general opening hours for a business. Opening hours can be specified as a weekly time range, starting with days, then times per day. Multiple days can be listed with commas ',' separating each day. Day or time ranges are specified using a hyphen '-'.

			$schema_opening_hours = ( isset($schema_opening_hours) && is_array($schema_opening_hours) && !empty($schema_opening_hours) ) ? $schema_opening_hours : array();

	// Other Available Properties
	// according to Schema.org
	// https://schema.org/MedicalClinic (https://archive.is/ZS6nb)

			// Properties for Term: MedicalClinic

				// Property: availableService
					// Expected Type: MedicalProcedure or MedicalTest or MedicalTherapy
					// Description: A medical service available from this provider.

			// Properties for Term: LocalBusiness

				// Property: currenciesAccepted
					// Expected Type: Text
					// Description: The currency accepted. Use standard formats: ISO 4217 currency format, e.g. "USD"; Ticker symbol for cryptocurrencies, e.g. "BTC"; well known names for Local Exchange Trading Systems (LETS) and other currency types, e.g. "Ithaca HOUR".

				// Property: paymentAccepted
					// Expected Type: Text
					// Description: Cash, Credit Card, Cryptocurrency, Local Exchange Tradings System, etc.

			// Properties for Term: Place

				// Property: additionalProperty
					// Expected Type: PropertyValue
					// Description: A property-value pair representing an additional characteristic of the entity, e.g. a product feature or another characteristic for which there is no matching property in schema.org. Note: Publishers should be aware that applications designed to use specific schema.org properties (e.g. https://schema.org/width, https://schema.org/color, https://schema.org/gtin13, ...) will typically expect such data to be provided using those properties, rather than using the generic property/value mechanism.

				// Property: amenityFeature
					// Expected Type: LocationFeatureSpecification
					// Description: An amenity feature (e.g. a characteristic or service) of the Accommodation. This generic property does not make a statement about whether the feature is included in an offer for the main accommodation or available at extra costs.

				// Property: branchCode
					// Expected Type: Text
					// Description: A short textual code (also called "store code") that uniquely identifies a place of business. The code is typically assigned by the parentOrganization and used in structured URLs. For example, in the URL http://www.starbucks.co.uk/store-locator/etc/detail/3047 the code "3047" is a branchCode for a particular branch.

				// Property: containedInPlace
					// Expected Type: Place
					// Description: The basic containment relation between a place and one that contains it. Supersedes containedIn. Inverse property: containsPlace

				// Property: containsPlace
					// Expected Type: Place
					// Description: The basic containment relation between a place and another that it contains. Inverse property: containedInPlace

				// Property: event
					// Expected Type: Event
					// Description: Upcoming or past event associated with this place, organization, or action. Supersedes events.

				// Property: geoContains
					// Expected Type: GeospatialGeometry or Place
					// Description: Represents a relationship between two geometries (or the places they represent), relating a containing geometry to a contained geometry. "a contains b iff no points of b lie in the exterior of a, and at least one point of the interior of b lies in the interior of a". As defined in DE-9IM.

				// Property: geoCoveredBy
					// Expected Type: GeospatialGeometry or Place
					// Description: Represents a relationship between two geometries (or the places they represent), relating a geometry to another that covers it. As defined in DE-9IM.

				// Property: geoCovers
					// Expected Type: GeospatialGeometry or Place
					// Description: Represents a relationship between two geometries (or the places they represent), relating a covering geometry to a covered geometry. "Every point of b is a point of (the interior or boundary of) a". As defined in DE-9IM.

				// Property: geoCrosses
					// Expected Type: GeospatialGeometry or Place
					// Description: Represents a relationship between two geometries (or the places they represent), relating a geometry to another that crosses it: "a crosses b: they have some but not all interior points in common, and the dimension of the intersection is less than that of at least one of them". As defined in DE-9IM.

				// Property: geoDisjoint
					// Expected Type: GeospatialGeometry or Place
					// Description: Represents spatial relations in which two geometries (or the places they represent) are topologically disjoint: "they have no point in common. They form a set of disconnected geometries." (A symmetric relationship, as defined in DE-9IM.)

				// Property: geoEquals
					// Expected Type: GeospatialGeometry or Place
					// Description: Represents spatial relations in which two geometries (or the places they represent) are topologically equal, as defined in DE-9IM. "Two geometries are topologically equal if their interiors intersect and no part of the interior or boundary of one geometry intersects the exterior of the other" (a symmetric relationship).

				// Property: geoIntersects
					// Expected Type: GeospatialGeometry or Place
					// Description: Represents spatial relations in which two geometries (or the places they represent) have at least one point in common. As defined in DE-9IM.

				// Property: geoOverlaps
					// Expected Type: GeospatialGeometry or Place
					// Description: Represents a relationship between two geometries (or the places they represent), relating a geometry to another that geospatially overlaps it, i.e. they have some but not all points in common. As defined in DE-9IM.

				// Property: geoTouches
					// Expected Type: GeospatialGeometry or Place
					// Description: Represents spatial relations in which two geometries (or the places they represent) touch: "they have at least one boundary point in common, but no interior points." (A symmetric relationship, as defined in DE-9IM.)

				// Property: geoWithin
					// Expected Type: GeospatialGeometry or Place
					// Description: Represents a relationship between two geometries (or the places they represent), relating a geometry to one that contains it, i.e. it is inside (i.e. within) its interior. As defined in DE-9IM.

				// Property: globalLocationNumber
					// Expected Type: Text
					// Description: The Global Location Number (GLN, sometimes also referred to as International Location Number or ILN) of the respective organization, person, or place. The GLN is a 13-digit number used to identify parties and physical locations.

				// Property: hasDriveThroughService
					// Expected Type: Boolean
					// Description: Indicates whether some facility (e.g. FoodEstablishment, CovidTestingFacility) offers a service that can be used by driving through in a car. In the case of CovidTestingFacility such facilities could potentially help with social distancing from other potentially-infected users.

				// Property: hasMap
					// Expected Type: Map or URL
					// Description: A URL to a map of the place. Supersedes maps, map.

				// Property: isAccessibleForFree
					// Expected Type: Boolean
					// Description: A flag to signal that the item, event, or place is accessible for free. Supersedes free.

				// Property: isicV4
					// Expected Type: Text
					// Description: The International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code for a particular organization, business person, or place.

				// Property: keywords
					// Expected Type: DefinedTerm or Text or URL
					// Description: Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.

				// Property: maximumAttendeeCapacity
					// Expected Type: Integer
					// Description: The total number of individuals that may attend an event or venue.

				// Property: photo
					// Expected Type: ImageObject or Photograph
					// Description: A photograph of this place. Supersedes photos.

				// Property: publicAccess
					// Expected Type: Boolean
					// Description: A flag to signal that the Place is open to public visitors. If this property is omitted there is no assumed default boolean value

				// Property: slogan
					// Expected Type: Text
					// Description: A slogan or motto associated with the item.

				// Property: smokingAllowed
					// Expected Type: Boolean
					// Description: Indicates whether it is allowed to smoke in the place, e.g. in the restaurant, hotel or hotel room.

				// Property: specialOpeningHoursSpecification
					// Expected Type: OpeningHoursSpecification
					// Description: The special opening hours of a certain place. Use this to explicitly override general opening hours brought in scope by openingHoursSpecification or openingHours.

				// Property: tourBookingPage
					// Expected Type: URL
					// Description: A page providing information on how to book a tour of some Place, such as an Accommodation or ApartmentComplex in a real estate setting, as well as other kinds of tours as appropriate.

			// Properties for Term: Organization

				// Property: actionableFeedbackPolicy
					// Expected Type: CreativeWork or URL
					// Description: For a NewsMediaOrganization or other news-related Organization, a statement about public engagement activities (for news media, the newsroom's), including involving the public - digitally or otherwise -- in coverage decisions, reporting and activities after publication.

				// Property: aggregateRating
					// Expected Type: AggregateRating
					// Description: The overall rating, based on a collection of reviews or ratings, of the item.

				// Property: alumni
					// Expected Type: Person
					// Description: Alumni of an organization. Inverse property: alumniOf

				// Property: areaServed
					// Expected Type: AdministrativeArea or GeoShape or Place or Text
					// Description: The geographic area where a service or offered item is provided. Supersedes serviceArea.

				// Property: award
					// Expected Type: Text
					// Description: An award won by or for this item. Supersedes awards.

				// Property: brand
					// Expected Type: Brand or Organization
					// Description: The brand(s) associated with a product or service, or the brand(s) maintained by an organization or business person.

				// Property: contactPoint
					// Expected Type: ContactPoint
					// Description: A contact point for a person or organization. Supersedes contactPoints.

				// Property: correctionsPolicy
					// Expected Type: CreativeWork or URL
					// Description: For an Organization (e.g. NewsMediaOrganization), a statement describing (in news media, the newsroom's) disclosure and correction policy for errors.

				// Property: dissolutionDate
					// Expected Type: Date
					// Description: The date that this organization was dissolved.

				// Property: diversityPolicy
					// Expected Type: CreativeWork or URL
					// Description: Statement on diversity policy by an Organization e.g. a NewsMediaOrganization. For a NewsMediaOrganization, a statement describing the newsroom's diversity policy on both staffing and sources, typically providing staffing data.

				// Property: diversityStaffingReport
					// Expected Type: Article or URL
					// Description: For an Organization (often but not necessarily a NewsMediaOrganization), a report on staffing diversity issues. In a news context this might be for example ASNE or RTDNA (US) reports, or self-reported.

				// Property: duns
					// Expected Type: Text
					// Description: The Dun & Bradstreet DUNS number for identifying an organization or business person.

				// Property: email
					// Expected Type: Text
					// Description: Email address.

				// Property: employee
					// Expected Type: Person
					// Description: Someone working for this organization. Supersedes employees.

				// Property: ethicsPolicy
					// Expected Type: CreativeWork or URL
					// Description: Statement about ethics policy, e.g. of a NewsMediaOrganization regarding journalistic and publishing practices, or of a Restaurant, a page describing food source policies. In the case of a NewsMediaOrganization, an ethicsPolicy is typically a statement describing the personal, organizational, and corporate standards of behavior expected by the organization.

				// Property: event
					// Expected Type: Event
					// Description: Upcoming or past event associated with this place, organization, or action. Supersedes events.

				// Property: founder
					// Expected Type: Person
					// Description: A person who founded this organization. Supersedes founders.

				// Property: foundingDate
					// Expected Type: Date
					// Description: The date that this organization was founded.

				// Property: foundingLocation
					// Expected Type: Place
					// Description: The place where the Organization was founded.

				// Property: funder
					// Expected Type: Organization or Person
					// Description: A person or organization that supports (sponsors) something through some kind of financial contribution.

				// Property: funding
					// Expected Type: Grant
					// Description: A Grant that directly or indirectly provide funding or sponsorship for this item. See also ownershipFundingInfo. Inverse property: fundedItem

				// Property: globalLocationNumber
					// Expected Type: Text
					// Description: The Global Location Number (GLN, sometimes also referred to as International Location Number or ILN) of the respective organization, person, or place. The GLN is a 13-digit number used to identify parties and physical locations.

				// Property: hasCredential
					// Expected Type: EducationalOccupationalCredential
					// Description: A credential awarded to the Person or Organization.

				// Property: hasMerchantReturnPolicy
					// Expected Type: MerchantReturnPolicy
					// Description: Specifies a MerchantReturnPolicy that may be applicable. Supersedes hasProductReturnPolicy.

				// Property: hasOfferCatalog
					// Expected Type: OfferCatalog
					// Description: Indicates an OfferCatalog listing for this Organization, Person, or Service.

				// Property: hasPOS
					// Expected Type: Place
					// Description: Points-of-Sales operated by the organization or person.

				// Property: interactionStatistic
					// Expected Type: InteractionCounter
					// Description: The number of interactions for the CreativeWork using the WebSite or SoftwareApplication. The most specific child type of InteractionCounter should be used. Supersedes interactionCount.

				// Property: isicV4
					// Expected Type: Text
					// Description: The International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code for a particular organization, business person, or place.

				// Property: iso6523Code
					// Expected Type: Text
					// Description: An organization identifier as defined in ISO 6523(-1). Note that many existing organization identifiers such as leiCode, duns and vatID can be expressed as an ISO 6523 identifier by setting the ICD part of the ISO 6523 identifier accordingly.

				// Property: keywords
					// Expected Type: DefinedTerm or Text or URL
					// Description: Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.

				// Property: knowsAbout
					// Expected Type: Text or Thing or URL
					// Description: Of a Person, and less typically of an Organization, to indicate a topic that is known about - suggesting possible expertise but not implying it. We do not distinguish skill levels here, or relate this to educational content, events, objectives or JobPosting descriptions.

				// Property: knowsLanguage
					// Expected Type: Language or Text
					// Description: Of a Person, and less typically of an Organization, to indicate a known language. We do not distinguish skill levels or reading/writing/speaking/signing here. Use language codes from the IETF BCP 47 standard.

				// Property: legalName
					// Expected Type: Text
					// Description: The official name of the organization, e.g. the registered company name.

				// Property: leiCode
					// Expected Type: Text
					// Description: An organization identifier that uniquely identifies a legal entity as defined in ISO 17442.

				// Property: location
					// Expected Type: Place or PostalAddress or Text or VirtualLocation
					// Description: The location of, for example, where an event is happening, where an organization is located, or where an action takes place.

				// Property: makesOffer
					// Expected Type: Offer
					// Description: A pointer to products or services offered by the organization or person. Inverse property: offeredBy

				// Property: member
					// Expected Type: Organization or Person
					// Description: A member of an Organization or a ProgramMembership. Organizations can be members of organizations; ProgramMembership is typically for individuals. Supersedes musicGroupMember, members. Inverse property: memberOf

				// Property: memberOf
					// Expected Type: Organization or ProgramMembership
					// Description: An Organization (or ProgramMembership) to which this Person or Organization belongs. Inverse property: member

				// Property: naics
					// Expected Type: Text
					// Description: The North American Industry Classification System (NAICS) code for a particular organization or business person.

				// Property: nonprofitStatus
					// Expected Type: NonprofitType
					// Description: nonprofitStatus indicates the legal status of a non-profit organization in its primary place of business.

				// Property: numberOfEmployees
					// Expected Type: QuantitativeValue
					// Description: The number of employees in an organization, e.g. business.

				// Property: ownershipFundingInfo
					// Expected Type: AboutPage or CreativeWork or Text or URL
					// Description: For an Organization (often but not necessarily a NewsMediaOrganization), a description of organizational ownership structure; funding and grants. In a news/media setting, this is with particular reference to editorial independence. Note that the funder is also available and can be used to make basic funder information machine-readable.

				// Property: owns
					// Expected Type: OwnershipInfo or Product
					// Description: Products owned by the organization or person.

				// Property: parentOrganization
					// Expected Type: Organization
					// Description: The larger organization that this organization is a subOrganization of, if any. Supersedes branchOf. Inverse property: subOrganization

				// Property: publishingPrinciples
					// Expected Type: CreativeWork or URL
					// Description: The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual, e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.

				// Property: seeks
					// Expected Type: Demand
					// Description: A pointer to products or services sought by the organization or person (demand).

				// Property: slogan
					// Expected Type: Text
					// Description: A slogan or motto associated with the item.

				// Property: sponsor
					// Expected Type: Organization or Person
					// Description: A person or organization that supports a thing through a pledge, promise, or financial contribution. E.g. a sponsor of a Medical Study or a corporate sponsor of an event.

				// Property: subOrganization
					// Expected Type: Organization
					// Description: A relationship between two organizations where the first includes the second, e.g., as a subsidiary. See also: the more specific 'department' property. Inverse property: parentOrganization

				// Property: taxID
					// Expected Type: Text
					// Description: The Tax / Fiscal ID of the organization or person, e.g. the TIN in the US or the CIF/NIF in Spain.

				// Property: unnamedSourcesPolicy
					// Expected Type: CreativeWork or URL
					// Description: For an Organization (typically a NewsMediaOrganization), a statement about policy on use of unnamed sources and the decision process required.

				// Property: vatID
					// Expected Type: Text
					// Description: The Value-added Tax ID of the organization or person.

			// Properties for Term: Thing

				// Property: additionalType
					// Expected Type: Text or URL
					// Description: An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide

				// Property: alternateName
					// Expected Type: Text
					// Description: An alias for the item.

				// Property: disambiguatingDescription
					// Expected Type: Text
					// Description: A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.

				// Property: identifier
					// Expected Type: PropertyValue or Text or URL
					// Description: The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See background notes for more details.

				// Property: mainEntityOfPage
					// Expected Type: CreativeWork or URL
					// Description: Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See background notes for details. Inverse property: mainEntity

				// Property: potentialAction
					// Expected Type: Action
					// Description: Indicates a potential Action, which describes an idealized action in which this thing would play an 'object' role.

				// Property: sameAs
					// Expected Type: URL
					// Description: URL of a reference Web page that unambiguously indicates the item's identity. E.g. the URL of the item's Wikipedia page, Wikidata entry, or official website.

				// Property: subjectOf
					// Expected Type: CreativeWork or Event
					// Description: A CreativeWork or Event about this Thing. Inverse property: about

// Create main array

	$schema_block = array(
		'@context'	=> 'http://www.schema.org'
	);

// Add @type

	if ( $schema_type ) {
		$schema_block['@type'] = $schema_type; // Add the relevant '@type' value
	}

// Add name

	if ( $schema_name ) {
		$schema_block['name'] = $schema_name; // Add the relevant 'name' value
	}

// Add url

	if ( $schema_url ) {
		$schema_block['url'] = $schema_url; // Add the relevant 'url' value
	}

// Add logo

	if ( $schema_logo ) {
		$schema_block['logo'] = $schema_logo; // Add the relevant 'logo ' value
	}

// Add image

	if ( $schema_image ) {

		// If the image schema array only contains one top-level item/array, flatten the image schema array
		if ( is_array($schema_image) ) {

			if ( 1 == count($schema_image) && is_array($schema_image[0]) ) {

				$schema_image = array_reduce( $schema_image, 'array_merge', array() );

			} elseif ( 1 == count($schema_image) && !is_array($schema_image[0]) ) {

				$schema_image = $schema_image[0];

			}

		}

		$schema_block['image'] = $schema_image; // Add the relevant 'image' value

	}

// Add description

	if ( $schema_description ) {
		$schema_block['description'] = $schema_description; // Add the relevant 'description' value
	}

// Add hospitalAffiliation

	if ( $schema_hospital_affiliation ) {
		$schema_block['hospitalAffiliation'] = $schema_hospital_affiliation; // Add the relevant 'hospitalAffiliation' value
	}

// Add availableService

	if ( $schema_available_service ) {
		$schema_block['availableService'] = $schema_available_service; // Add the relevant 'availableService' value
	}

// Add medicalSpecialty

	if ( $schema_medical_specialty ) {

		// If the medicalSpecialty schema array only contains one top-level item/array, flatten the medicalSpecialty schema array
		if ( is_array($schema_medical_specialty) ) {

			if ( 1 == count($schema_medical_specialty) && is_array($schema_medical_specialty[0]) ) {

				$schema_medical_specialty = array_reduce( $schema_medical_specialty, 'array_merge', array() );

			} elseif ( 1 == count($schema_medical_specialty) && !is_array($schema_medical_specialty[0]) ) {

				$schema_medical_specialty = $schema_medical_specialty[0];

			}

		}

		$schema_block['medicalSpecialty'] = $schema_medical_specialty; // Add the relevant 'medicalSpecialty' value

	}

// Add address

	if ( $schema_address ) {

		// If the address schema array only contains one top-level item/array, flatten the address schema array
		if ( is_array($schema_address) ) {

			if ( 1 == count($schema_address) && is_array($schema_address[0]) ) {

				$schema_address = array_reduce( $schema_address, 'array_merge', array() );

			} elseif ( 1 == count($schema_address) && !is_array($schema_address[0]) ) {

				$schema_address = $schema_address[0];

			}

		}

		$schema_block['address'] = $schema_address; // Add the relevant 'address' value

	}

// Add geo

if ( $schema_geo_coordinates ) {

	// If the geo schema array only contains one top-level item/array, flatten the geo schema array
	if ( is_array($schema_geo_coordinates) ) {

		if ( 1 == count($schema_geo_coordinates) && is_array($schema_geo_coordinates[0]) ) {

			$schema_geo_coordinates = array_reduce( $schema_geo_coordinates, 'array_merge', array() );

		} elseif ( 1 == count($schema_geo_coordinates) && !is_array($schema_geo_coordinates[0]) ) {

			$schema_geo_coordinates = $schema_geo_coordinates[0];

		}

	}

	$schema_block['geo'] = $schema_geo_coordinates; // Add the relevant 'geo' value

}

// Add openingHoursSpecification

	if ( $schema_OpeningHoursSpecification ) {

		// If the openingHoursSpecification schema array only contains one top-level item/array, flatten the openingHoursSpecification schema array
		if ( is_array($schema_OpeningHoursSpecification) ) {

			if ( 1 == count($schema_OpeningHoursSpecification) && is_array($schema_OpeningHoursSpecification[0]) ) {

				$schema_OpeningHoursSpecification = array_reduce( $schema_OpeningHoursSpecification, 'array_merge', array() );

			} elseif ( 1 == count($schema_OpeningHoursSpecification) && !is_array($schema_OpeningHoursSpecification[0]) ) {

				$schema_OpeningHoursSpecification = $schema_OpeningHoursSpecification[0];

			}

		}

		$schema_block['openingHoursSpecification'] = $schema_OpeningHoursSpecification; // Add the relevant 'openingHoursSpecification' value

	}

// Add openingHours

	if ( $schema_opening_hours ) {

		// If the openingHours schema array only contains one top-level item/array, flatten the openingHours schema array
		if ( is_array($schema_opening_hours) ) {

			if ( 1 == count($schema_opening_hours) && is_array($schema_opening_hours[0]) ) {

				$schema_opening_hours = array_reduce( $schema_opening_hours, 'array_merge', array() );

			} elseif ( 1 == count($schema_opening_hours) && !is_array($schema_opening_hours[0]) ) {

				$schema_opening_hours = $schema_opening_hours[0];

			}

		}

		$schema_block['openingHours'] = $schema_opening_hours; // Add the relevant 'openingHours' value

	}

// Add telephone

	if ( $schema_telephone ) {

		// If the telephone schema array only contains one top-level item/array, flatten the telephone schema array
		if ( is_array($schema_telephone) ) {

			if ( 1 == count($schema_telephone) && is_array($schema_telephone[0]) ) {

				$schema_telephone = array_reduce( $schema_telephone, 'array_merge', array() );

			} elseif ( 1 == count($schema_telephone) && !is_array($schema_telephone[0]) ) {

				$schema_telephone = $schema_telephone[0];

			}

		}

		$schema_block['telephone'] = $schema_telephone; // Add the relevant 'telephone' value

	}

// Add faxNumber

if ( $schema_fax_number ) {

	// If the faxNumber schema array only contains one top-level item/array, flatten the faxNumber schema array
	if ( is_array($schema_fax_number) ) {

		if ( 1 == count($schema_fax_number) && is_array($schema_fax_number[0]) ) {

			$schema_fax_number = array_reduce( $schema_fax_number, 'array_merge', array() );

		} elseif ( 1 == count($schema_fax_number) && !is_array($schema_fax_number[0]) ) {

			$schema_fax_number = $schema_fax_number[0];

		}

	}

	$schema_block['faxNumber'] = $schema_fax_number; // Add the relevant 'faxNumber' value

}

// Add aggregateRating

	if ( $schema_aggregate_rating ) {

		// Create the 'aggregateRating' array

			$schema_block['aggregateRating'] = array(
				'@type'	=> 'AggregateRating'
			);

		// Add ratingValue

			if ( isset($schema_aggregate_rating_value) && !empty($schema_aggregate_rating_value) ) {
				$schema_block['aggregateRating']['ratingValue'] = $schema_aggregate_rating_value; // Add the relevant 'ratingValue' value
			}

		// Add ratingCount

			if ( isset($schema_aggregate_rating_count) && !empty($schema_aggregate_rating_count) ) {
				$schema_block['aggregateRating']['ratingCount'] = $schema_aggregate_rating_count; // Add the relevant 'ratingCount' value
			}

		// Add reviewCount

			if ( isset($schema_aggregate_rating_review_count) && !empty($schema_aggregate_rating_review_count) ) {
				$schema_block['aggregateRating']['reviewCount'] = $schema_aggregate_rating_review_count; // Add the relevant 'reviewCount' value
			}

	}

// Construct the schema JSON script tag

	uamswp_fad_schema_construct($schema_block);