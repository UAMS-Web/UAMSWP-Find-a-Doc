<?php

// Place

	/*
	 * Thing > Place
	 * 
	 * 
	 */

	 function uamswp_fad_schema_place(
		$schema, // array // Main schema array
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

			// Properties from Place

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

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_thing(
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

			// Properties from Place

				// additionalProperty

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > PropertyValue
					 * 
					 * A property-value pair representing an additional characteristic of the entity 
					 * (e.g., a product feature or another characteristic for which there is no 
					 * matching property in schema.org).
					 * 
					 * Note: Publishers should be aware that applications designed to use specific 
					 * schema.org properties (e.g., https://schema.org/width, 
					 * https://schema.org/color, https://schema.org/gtin13, ...) will typically expect 
					 * such data to be provided using those properties, rather than using the generic 
					 * property/value mechanism.
					 */

					$schema['additionalProperty'] = $additionalProperty;

				// address

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > ContactPoint > PostalAddress
					 *     DataType > Text
					 * 
					 * Physical address of the item.
					 */

					$schema['address'] = $address;

				// aggregateRating

					/* 
					 * Expected Type:
					 *     Thing > Intangible > Rating > AggregateRating
					 * 
					 * The overall rating, based on a collection of reviews or ratings, of the 
					 * item.
					 */

					$schema['aggregateRating'] = $aggregateRating;

				// amenityFeature

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > PropertyValue > LocationFeatureSpecification
					 * 
					 * An amenity feature (e.g., a characteristic or service) of the Accommodation. 
					 * This generic property does not make a statement about whether the feature is 
					 * included in an offer for the main accommodation or available at extra costs.
					 */

					$schema['amenityFeature'] = $amenityFeature;

				// branchCode

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * A short textual code (also called "store code") that uniquely identifies a 
					 * place of business. The code is typically assigned by the parentOrganization and 
					 * used in structured URLs.
					 * 
					 * For example, in the URL 
					 * http://www.starbucks.co.uk/store-locator/etc/detail/3047 the code "3047" is a 
					 * branchCode for a particular branch.
					 */

					$schema['branchCode'] = $branchCode;

				// containedInPlace

					/* 
					 * Expected Type:
					 *     Thing > Place
					 * 
					 * The basic containment relation between a place and one that contains it. 
					 * Supersedes containedIn.
					 * 
					 * Inverse property: containsPlace
					 */

					$schema['containedInPlace'] = $containedInPlace;

				// containsPlace

					/* 
					 * Expected Type:
					 *     Thing > Place
					 * 
					 * The basic containment relation between a place and another that it contains.
					 * 
					 * Inverse property: containedInPlace
					 */

					$schema['containsPlace'] = $containsPlace;

				// event

					/* 
					 * Expected Type:
					 *     Thing > Event
					 * 
					 * Upcoming or past event associated with this place, organization, or action. 
					 * Supersedes events.
					 */

					$schema['event'] = $event;

				// faxNumber

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The fax number.
					 */

					$schema['faxNumber'] = $faxNumber;

				// geo

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > GeoCoordinates
					 *     Thing > Intangible > StructuredValue > GeoShape
					 * 
					 * The geo coordinates of the place.
					 */

					$schema['geo'] = $geo;

				// geoContains

					/* 
					 * Expected Type:
					 *     Thing > Intangible > GeospatialGeometry
					 *     Thing > Place
					 * 
					 * Represents a relationship between two geometries (or the places they 
					 * represent), relating a containing geometry to a contained geometry. "a contains 
					 * b iff no points of b lie in the exterior of a, and at least one point of the 
					 * interior of b lies in the interior of a". As defined in DE-9IM.
					 */

					$schema['geoContains'] = $geoContains;

				// geoCoveredBy

					/* 
					 * Expected Type:
					 *     Thing > Intangible > GeospatialGeometry
					 *     Thing > Place
					 * 
					 * Represents a relationship between two geometries (or the places they 
					 * represent), relating a geometry to another that covers it. As defined in DE-9IM.
					 */

					$schema['geoCoveredBy'] = $geoCoveredBy;

				// geoCovers

					/* 
					 * Expected Type:
					 *     Thing > Intangible > GeospatialGeometry
					 *     Thing > Place
					 * 
					 * Represents a relationship between two geometries (or the places they 
					 * represent), relating a covering geometry to a covered geometry. "Every point of 
					 * b is a point of (the interior or boundary of) a". As defined in DE-9IM.
					 */

					$schema['geoCovers'] = $geoCovers;

				// geoCrosses

					/* 
					 * Expected Type:
					 *     Thing > Intangible > GeospatialGeometry
					 *     Thing > Place
					 * 
					 * Represents a relationship between two geometries (or the places they 
					 * represent), relating a geometry to another that crosses it: "a crosses b: they 
					 * have some but not all interior points in common, and the dimension of the 
					 * intersection is less than that of at least one of them". As defined in DE-9IM.
					 */

					$schema['geoCrosses'] = $geoCrosses;

				// geoDisjoint

					/* 
					 * Expected Type:
					 *     Thing > Intangible > GeospatialGeometry
					 *     Thing > Place
					 * 
					 * Represents spatial relations in which two geometries (or the places they 
					 * represent) are topologically disjoint: "they have no point in common. They form 
					 * a set of disconnected geometries." (A symmetric relationship, as defined in 
					 * DE-9IM.)
					 */

					$schema['geoDisjoint'] = $geoDisjoint;

				// geoEquals

					/* 
					 * Expected Type:
					 *     Thing > Intangible > GeospatialGeometry
					 *     Thing > Place
					 * 
					 * Represents spatial relations in which two geometries (or the places they 
					 * represent) are topologically equal, as defined in DE-9IM. "Two geometries are 
					 * topologically equal if their interiors intersect and no part of the interior or 
					 * boundary of one geometry intersects the exterior of the other" (a symmetric 
					 * relationship).
					 */

					$schema['geoEquals'] = $geoEquals;

				// geoIntersects

					/* 
					 * Expected Type:
					 *     Thing > Intangible > GeospatialGeometry
					 *     Thing > Place
					 * 
					 * Represents spatial relations in which two geometries (or the places they 
					 * represent) have at least one point in common. As defined in DE-9IM.
					 */

					$schema['geoIntersects'] = $geoIntersects;

				// geoOverlaps

					/* 
					 * Expected Type:
					 *     Thing > Intangible > GeospatialGeometry
					 *     Thing > Place
					 * 
					 * Represents a relationship between two geometries (or the places they 
					 * represent), relating a geometry to another that geospatially overlaps it 
					 * (i.e., they have some but not all points in common. As defined in 
					 * DE-9IM).
					 */

					$schema['geoOverlaps'] = $geoOverlaps;

				// geoTouches

					/* 
					 * Expected Type:
					 *     Thing > Intangible > GeospatialGeometry
					 *     Thing > Place
					 * 
					 * Represents spatial relations in which two geometries (or the places they 
					 * represent) touch: "they have at least one boundary point in common, but no 
					 * interior points." (A symmetric relationship, as defined in DE-9IM.)
					 */

					$schema['geoTouches'] = $geoTouches;

				// geoWithin

					/* 
					 * Expected Type:
					 *     Thing > Intangible > GeospatialGeometry
					 *     Thing > Place
					 * 
					 * Represents a relationship between two geometries (or the places they 
					 * represent), relating a geometry to one that contains it (i.e., it is inside 
					 * (within) its interior. As defined in DE-9IM.
					 */

					$schema['geoWithin'] = $geoWithin;

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

				// hasDriveThroughService

					/* 
					 * Expected Type:
					 *     DataType > Boolean
					 * 
					 * Indicates whether some facility (e.g., FoodEstablishment, CovidTestingFacility) 
					 * offers a service that can be used by driving through in a car. In the case of 
					 * CovidTestingFacility such facilities could potentially help with social 
					 * distancing from other potentially-infected users.
					 */

					$schema['hasDriveThroughService'] = $hasDriveThroughService;

				// hasMap

					/* 
					 * Expected Type:
					 *     Thing > CreativeWork > Map
					 *     DataType > Text > URL
					 * 
					 * A URL to a map of the place. Supersedes maps, map.
					 */

					$schema['hasMap'] = $hasMap;

				// isAccessibleForFree

					/* 
					 * Expected Type:
					 *     DataType > Boolean
					 * 
					 * A flag to signal that the item, event, or place is accessible for free. 
					 * Supersedes free.
					 */

					$schema['isAccessibleForFree'] = $isAccessibleForFree;

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

				// keywords

					/* 
					 * Expected Type:
					 *     Thing > Intangible > DefinedTerm
					 *     DataType > Text
					 *     DataType > Text > URL
					 * 
					 * Keywords or tags used to describe some item. Multiple textual entries in a 
					 * keywords list are typically delimited by commas, or by repeating the property.
					 */

					$schema['keywords'] = $keywords;

				// latitude

					/* 
					 * Expected Type:
					 *     DataType > Number
					 *     DataType > Text
					 * 
					 * The latitude of a location. For example 37.42242 (WGS 84).
					 */

					$schema['latitude'] = $latitude;

				// logo

					/* 
					 * Expected Type:
					 *     Thing > CreativeWork > MediaObject > ImageObject
					 *     DataType > Text > URL
					 * 
					 * An associated logo.
					 */

					$schema['logo'] = $logo;

				// longitude

					/* 
					 * Expected Type:
					 *     DataType > Number
					 *     DataType > Text
					 * 
					 * The longitude of a location. For example -122.08585 (WGS 84).
					 */

					$schema['longitude'] = $longitude;

				// maximumAttendeeCapacity

					/* 
					 * Expected Type:
					 *     DataType > Number > Integer
					 * 
					 * The total number of individuals that may attend an event or venue.
					 */

					$schema['maximumAttendeeCapacity'] = $maximumAttendeeCapacity;

				// openingHoursSpecification

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > OpeningHoursSpecification
					 * 
					 * The opening hours of a certain place.
					 */

					$schema['openingHoursSpecification'] = $openingHoursSpecification;

				// photo

					/* 
					 * Expected Type:
					 *     Thing > CreativeWork > MediaObject > ImageObject
					 *     Thing > CreativeWork > Photograph
					 * 
					 * A photograph of this place. Supersedes photos.
					 */

					$schema['photo'] = $photo;

				// publicAccess

					/* 
					 * Expected Type:
					 *     DataType > Boolean
					 * 
					 * A flag to signal that the Place is open to public visitors. If this property is 
					 * omitted there is no assumed default boolean value
					 */

					$schema['publicAccess'] = $publicAccess;

				// review

					/* 
					 * Expected Type:
					 *     Thing > CreativeWork > Review
					 * 
					 * A review of the item. Supersedes reviews.
					 */

					$schema['review'] = $review;

				// slogan

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * A slogan or motto associated with the item.
					 */

					$schema['slogan'] = $slogan;

				// smokingAllowed

					/* 
					 * Expected Type:
					 *     DataType > Boolean
					 * 
					 * Indicates whether it is allowed to smoke in the place (e.g., in the restaurant, 
					 * hotel or hotel room).
					 */

					$schema['smokingAllowed'] = $smokingAllowed;

				// specialOpeningHoursSpecification

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > OpeningHoursSpecification
					 * 
					 * The special opening hours of a certain place.
					 * 
					 * Use this to explicitly override general opening hours brought in scope by 
					 * openingHoursSpecification or openingHours.
					 */

					$schema['specialOpeningHoursSpecification'] = $specialOpeningHoursSpecification;

				// telephone

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The telephone number.
					 */

					$schema['telephone'] = $telephone;

				// tourBookingPage

					/* 
					 * Expected Type:
					 *     DataType > Text > URL
					 * 
					 * A page providing information on how to book a tour of some Place, such as an 
					 * Accommodation or ApartmentComplex in a real estate setting, as well as other 
					 * kinds of tours as appropriate.
					 */

					$schema['tourBookingPage'] = $tourBookingPage;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// Accommodation

		/*
		 * Thing > Place > Accommodation
		 * 
		 * 
		 */

		function uamswp_fad_schema_accommodation(
			
		) {
			
		}

		// Apartment

			/*
			 * Thing > Place > Accommodation > Apartment
			 * 
			 * 
			 */

			function uamswp_fad_schema_apartment(
				
			) {
				
			}

		// CampingPitch

			/*
			 * Thing > Place > Accommodation > CampingPitch
			 * 
			 * 
			 */

			function uamswp_fad_schema_campingpitch(
				
			) {
				
			}

		// House

			/*
			 * Thing > Place > Accommodation > House
			 * 
			 * 
			 */

			function uamswp_fad_schema_house(
				
			) {
				
			}

			// SingleFamilyResidence

				/*
				 * Thing > Place > Accommodation > House > SingleFamilyResidence
				 * 
				 * 
				 */

				function uamswp_fad_schema_singlefamilyresidence(
					
				) {
					
				}

		// Room

			/*
			 * Thing > Place > Accommodation > Room
			 * 
			 * 
			 */

			function uamswp_fad_schema_room(
				
			) {
				
			}

			// HotelRoom

				/*
				 * Thing > Place > Accommodation > Room > HotelRoom
				 * 
				 * 
				 */

				function uamswp_fad_schema_hotelroom(
					
				) {
					
				}

			// MeetingRoom

				/*
				 * Thing > Place > Accommodation > Room > MeetingRoom
				 * 
				 * 
				 */

				function uamswp_fad_schema_meetingroom(
					
				) {
					
				}

		// Suite

			/*
			 * Thing > Place > Accommodation > Suite
			 * 
			 * 
			 */

			function uamswp_fad_schema_suite(
				
			) {
				
			}

	// AdministrativeArea

		/*
		 * Thing > Place > AdministrativeArea
		 * 
		 * 
		 */

		function uamswp_fad_schema_administrativearea(
			
		) {
			
		}

		// City

			/*
			 * Thing > Place > AdministrativeArea > City
			 * 
			 * 
			 */

			function uamswp_fad_schema_city(
				
			) {
				
			}

		// Country

			/*
			 * Thing > Place > AdministrativeArea > Country
			 * 
			 * 
			 */

			function uamswp_fad_schema_country(
				
			) {
				
			}

		// SchoolDistrict

			/*
			 * Thing > Place > AdministrativeArea > SchoolDistrict
			 * 
			 * 
			 */

			function uamswp_fad_schema_schooldistrict(
				
			) {
				
			}

		// State

			/*
			 * Thing > Place > AdministrativeArea > State
			 * 
			 * 
			 */

			function uamswp_fad_schema_state(
				
			) {
				
			}

	// CivicStructure

		/*
		 * Thing > Place > CivicStructure
		 * 
		 * 
		 */

		function uamswp_fad_schema_civicstructure(
			
		) {
			
		}

		// Airport

			/*
			 * Thing > Place > CivicStructure > Airport
			 * 
			 * 
			 */

			function uamswp_fad_schema_airport(
				
			) {
				
			}

		// Aquarium

			/*
			 * Thing > Place > CivicStructure > Aquarium
			 * 
			 * 
			 */

			function uamswp_fad_schema_aquarium(
				
			) {
				
			}

		// Beach

			/*
			 * Thing > Place > CivicStructure > Beach
			 * 
			 * 
			 */

			function uamswp_fad_schema_beach(
				
			) {
				
			}

		// BoatTerminal

			/*
			 * Thing > Place > CivicStructure > BoatTerminal
			 * 
			 * 
			 */

			function uamswp_fad_schema_boatterminal(
				
			) {
				
			}

		// Bridge

			/*
			 * Thing > Place > CivicStructure > Bridge
			 * 
			 * 
			 */

			function uamswp_fad_schema_bridge(
				
			) {
				
			}

		// BusStation

			/*
			 * Thing > Place > CivicStructure > BusStation
			 * 
			 * 
			 */

			function uamswp_fad_schema_busstation(
				
			) {
				
			}

		// BusStop

			/*
			 * Thing > Place > CivicStructure > BusStop
			 * 
			 * 
			 */

			function uamswp_fad_schema_busstop(
				
			) {
				
			}

		// Campground

			/*
			 * Thing > Place > CivicStructure > Campground
			 * 
			 * 
			 */

			function uamswp_fad_schema_campground(
				
			) {
				
			}

		// Cemetery

			/*
			 * Thing > Place > CivicStructure > Cemetery
			 * 
			 * 
			 */

			function uamswp_fad_schema_cemetery(
				
			) {
				
			}

		// Crematorium

			/*
			 * Thing > Place > CivicStructure > Crematorium
			 * 
			 * 
			 */

			function uamswp_fad_schema_crematorium(
				
			) {
				
			}

		// EducationalOrganization

			/*
			 * Thing > Place > CivicStructure > EducationalOrganization
			 * 
			 * 
			 */

			function uamswp_fad_schema_educationalorganization(
				
			) {
				
			}

		// EventVenue

			/*
			 * Thing > Place > CivicStructure > EventVenue
			 * 
			 * 
			 */

			function uamswp_fad_schema_eventvenue(
				
			) {
				
			}

		// FireStation

			/*
			 * Thing > Place > CivicStructure > FireStation
			 * 
			 * 
			 */

			function uamswp_fad_schema_firestation(
				
			) {
				
			}

		// GovernmentBuilding

			/*
			 * Thing > Place > CivicStructure > GovernmentBuilding
			 * 
			 * 
			 */

			function uamswp_fad_schema_governmentbuilding(
				
			) {
				
			}

			// CityHall

				/*
				 * Thing > Place > CivicStructure > GovernmentBuilding > CityHall
				 * 
				 * 
				 */

				function uamswp_fad_schema_cityhall(
					
				) {
					
				}

			// Courthouse

				/*
				 * Thing > Place > CivicStructure > GovernmentBuilding > Courthouse
				 * 
				 * 
				 */

				function uamswp_fad_schema_courthouse(
					
				) {
					
				}

			// DefenceEstablishment

				/*
				 * Thing > Place > CivicStructure > GovernmentBuilding > DefenceEstablishment
				 * 
				 * 
				 */

				function uamswp_fad_schema_defenceestablishment(
					
				) {
					
				}

			// Embassy

				/*
				 * Thing > Place > CivicStructure > GovernmentBuilding > Embassy
				 * 
				 * 
				 */

				function uamswp_fad_schema_embassy(
					
				) {
					
				}

			// LegislativeBuilding

				/*
				 * Thing > Place > CivicStructure > GovernmentBuilding > LegislativeBuilding
				 * 
				 * 
				 */

				function uamswp_fad_schema_legislativebuilding(
					
				) {
					
				}

		// Hospital

			/*
			 * Thing > Place > CivicStructure > Hospital
			 * 
			 *     Also: Thing > Organization > LocalBusiness > EmergencyService > Hospital
			 *     Also: Thing > Organization > MedicalOrganization > Hospital
			 *     Also: Thing > Place > LocalBusiness > EmergencyService > Hospital
			 * 
			 * A hospital.
			 */

			function uamswp_fad_schema_hospital(
				
			) {
				
			}

		// MovieTheater

			/*
			 * Thing > Place > CivicStructure > MovieTheater
			 * 
			 * 
			 */

			function uamswp_fad_schema_movietheater(
				
			) {
				
			}

		// Museum

			/*
			 * Thing > Place > CivicStructure > Museum
			 * 
			 * 
			 */

			function uamswp_fad_schema_museum(
				
			) {
				
			}

		// MusicVenue

			/*
			 * Thing > Place > CivicStructure > MusicVenue
			 * 
			 * 
			 */

			function uamswp_fad_schema_musicvenue(
				
			) {
				
			}

		// Park

			/*
			 * Thing > Place > CivicStructure > Park
			 * 
			 * 
			 */

			function uamswp_fad_schema_park(
				
			) {
				
			}

		// ParkingFacility

			/*
			 * Thing > Place > CivicStructure > ParkingFacility
			 * 
			 * 
			 */

			function uamswp_fad_schema_parkingfacility(
				
			) {
				
			}

		// PerformingArtsTheater

			/*
			 * Thing > Place > CivicStructure > PerformingArtsTheater
			 * 
			 * 
			 */

			function uamswp_fad_schema_performingartstheater(
				
			) {
				
			}

		// PlaceOfWorship

			/*
			 * Thing > Place > CivicStructure > PlaceOfWorship
			 * 
			 * 
			 */

			function uamswp_fad_schema_placeofworship(
				
			) {
				
			}

			// BuddhistTemple

				/*
				 * Thing > Place > CivicStructure > PlaceOfWorship > BuddhistTemple
				 * 
				 * 
				 */

				function uamswp_fad_schema_buddhisttemple(
					
				) {
					
				}

			// Church

				/*
				 * Thing > Place > CivicStructure > PlaceOfWorship > Church
				 * 
				 * 
				 */

				function uamswp_fad_schema_church(
					
				) {
					
				}

				// CatholicChurch

					/*
					 * Thing > Place > CivicStructure > qux > quux > CatholicChurch
					 * 
					 * 
					 */

					function uamswp_fad_schema_catholicchurch(
						
					) {
						
					}

			// HinduTemple

				/*
				 * Thing > Place > CivicStructure > qux > HinduTemple
				 * 
				 * 
				 */

				function uamswp_fad_schema_hindutemple(
					
				) {
					
				}

			// Mosque

				/*
				 * Thing > Place > CivicStructure > PlaceOfWorship > Mosque
				 * 
				 * 
				 */

				function uamswp_fad_schema_mosque(
					
				) {
					
				}

			// Synagogue

				/*
				 * Thing > Place > CivicStructure > PlaceOfWorship > Synagogue
				 * 
				 * 
				 */

				function uamswp_fad_schema_synagogue(
					
				) {
					
				}

		// Playground

			/*
			 * Thing > Place > CivicStructure > Playground
			 * 
			 * 
			 */

			function uamswp_fad_schema_playground(
				
			) {
				
			}

		// PoliceStation

			/*
			 * Thing > Place > CivicStructure > PoliceStation
			 * 
			 * 
			 */

			function uamswp_fad_schema_policestation(
				
			) {
				
			}

		// PublicToilet

			/*
			 * Thing > Place > CivicStructure > PublicToilet
			 * 
			 * 
			 */

			function uamswp_fad_schema_publictoilet(
				
			) {
				
			}

		// RVPark

			/*
			 * Thing > Place > CivicStructure > RVPark
			 * 
			 * 
			 */

			function uamswp_fad_schema_rvpark(
				
			) {
				
			}

		// StadiumOrArena

			/*
			 * Thing > Place > CivicStructure > StadiumOrArena
			 * 
			 * 
			 */

			function uamswp_fad_schema_stadiumorarena(
				
			) {
				
			}

		// SubwayStation

			/*
			 * Thing > Place > CivicStructure > SubwayStation
			 * 
			 * 
			 */

			function uamswp_fad_schema_subwaystation(
				
			) {
				
			}

		// TaxiStand

			/*
			 * Thing > Place > CivicStructure > TaxiStand
			 * 
			 * 
			 */

			function uamswp_fad_schema_taxistand(
				
			) {
				
			}

		// TrainStation

			/*
			 * Thing > Place > CivicStructure > TrainStation
			 * 
			 * 
			 */

			function uamswp_fad_schema_trainstation(
				
			) {
				
			}

		// Zoo

			/*
			 * Thing > Place > CivicStructure > Zoo
			 * 
			 * 
			 */

			function uamswp_fad_schema_zoo(
				
			) {
				
			}

	// Landform

		/*
		 * Thing > Place > Landform
		 * 
		 * 
		 */

		function uamswp_fad_schema_landform(
			
		) {
			
		}

		// BodyOfWater

			/*
			 * Thing > Place > Landform > BodyOfWater
			 * 
			 * 
			 */

			function uamswp_fad_schema_bodyofwater(
				
			) {
				
			}

			// Canal

				/*
				 * Thing > Place > Landform > BodyOfWater > Canal
				 * 
				 * 
				 */

				function uamswp_fad_schema_canal(
					
				) {
					
				}

			// LakeBodyOfWater

				/*
				 * Thing > Place > Landform > BodyOfWater > LakeBodyOfWater
				 * 
				 * 
				 */

				function uamswp_fad_schema_lakebodyofwater(
					
				) {
					
				}

			// OceanBodyOfWater

				/*
				 * Thing > Place > Landform > BodyOfWater > OceanBodyOfWater
				 * 
				 * 
				 */

				function uamswp_fad_schema_oceanbodyofwater(
					
				) {
					
				}

			// Pond

				/*
				 * Thing > Place > Landform > BodyOfWater > Pond
				 * 
				 * 
				 */

				function uamswp_fad_schema_pond(
					
				) {
					
				}

			// Reservoir

				/*
				 * Thing > Place > Landform > BodyOfWater > Reservoir
				 * 
				 * 
				 */

				function uamswp_fad_schema_reservoir(
					
				) {
					
				}

			// RiverBodyOfWater

				/*
				 * Thing > Place > Landform > BodyOfWater > RiverBodyOfWater
				 * 
				 * 
				 */

				function uamswp_fad_schema_riverbodyofwater(
					
				) {
					
				}

			// SeaBodyOfWater

				/*
				 * Thing > Place > Landform > BodyOfWater > SeaBodyOfWater
				 * 
				 * 
				 */

				function uamswp_fad_schema_seabodyofwater(
					
				) {
					
				}

			// Waterfall

				/*
				 * Thing > Place > Landform > BodyOfWater > Waterfall
				 * 
				 * 
				 */

				function uamswp_fad_schema_waterfall(
					
				) {
					
				}

		// Continent

			/*
			 * Thing > Place > Landform > Continent
			 * 
			 * 
			 */

			function uamswp_fad_schema_continent(
				
			) {
				
			}

		// Mountain

			/*
			 * Thing > Place > Landform > Mountain
			 * 
			 * 
			 */

			function uamswp_fad_schema_mountain(
				
			) {
				
			}

		// Volcano

			/*
			 * Thing > Place > Landform > Volcano
			 * 
			 * 
			 */

			function uamswp_fad_schema_volcano(
				
			) {
				
			}

	// LandmarksOrHistoricalBuildings

		/*
		 * Thing > Place > LandmarksOrHistoricalBuildings
		 * 
		 * 
		 */

		function uamswp_fad_schema_landmarksorhistoricalbuildings(
			
		) {
			
		}

	// LocalBusiness

		/*
		 * Thing > Place > LocalBusiness
		 * 
		 * See: Thing > Organization > LocalBusiness
		 */

	// Residence

		/*
		 * Thing > Place > Residence
		 * 
		 * 
		 */

		function uamswp_fad_schema_residence(
			
		) {
			
		}

		// ApartmentComplex

			/*
			 * Thing > Place > Residence > ApartmentComplex
			 * 
			 * 
			 */

			function uamswp_fad_schema_apartmentcomplex(
				
			) {
				
			}

		// GatedResidenceCommunity

			/*
			 * Thing > Place > Residence > GatedResidenceCommunity
			 * 
			 * 
			 */

			function uamswp_fad_schema_gatedresidencecommunity(
				
			) {
				
			}

	// TouristAttraction

		/*
		 * Thing > Place > TouristAttraction
		 * 
		 * 
		 */

		function uamswp_fad_schema_touristattraction(
			
		) {
			
		}

	// TouristDestination

		/*
		 * Thing > Place > TouristDestination
		 * 
		 * 
		 */

		function uamswp_fad_schema_touristdestination(
			
		) {
			
		}