<?php

// CivicStructure

	/*
	 * Thing > Place > CivicStructure
	 * 
	 * 
	 */

	function uamswp_fad_schema_civicstructure(
		$schema, // array // Main schema array
		// CivicStructure
			$openingHours = '', // openingHours
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

			// Properties from CivicStructure (Thing > Place > CivicStructure)

				$openingHours = ( isset($openingHours) && !empty($openingHours) ) ? $openingHours : '';

		// Add values to the schema array

			// Inherited properties

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

			// Properties from CivicStructure (Thing > Place > CivicStructure)

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

					$schema['openingHours'] = ( isset($openingHours) && !empty($openingHours) ) ? uamswp_fad_schema_type_selector($openingHours) : '';

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}

	// Airport
	include_once __DIR__ . '/CivicStructure/Airport.php';

	// Aquarium
	include_once __DIR__ . '/CivicStructure/Aquarium.php';

	// Beach
	include_once __DIR__ . '/CivicStructure/Beach.php';

	// BoatTerminal
	include_once __DIR__ . '/CivicStructure/BoatTerminal.php';

	// Bridge
	include_once __DIR__ . '/CivicStructure/Bridge.php';

	// BusStation
	include_once __DIR__ . '/CivicStructure/BusStation.php';

	// BusStop
	include_once __DIR__ . '/CivicStructure/BusStop.php';

	// Campground
	include_once __DIR__ . '/CivicStructure/Campground.php';

	// Cemetery
	include_once __DIR__ . '/CivicStructure/Cemetery.php';

	// Crematorium
	include_once __DIR__ . '/CivicStructure/Crematorium.php';

	// EducationalOrganization
	include_once __DIR__ . '/CivicStructure/EducationalOrganization.php';

	// EventVenue
	include_once __DIR__ . '/CivicStructure/EventVenue.php';

	// FireStation
	include_once __DIR__ . '/CivicStructure/FireStation.php';

	// GovernmentBuilding
	include_once __DIR__ . '/CivicStructure/GovernmentBuilding.php';

	// Hospital
	include_once __DIR__ . '/CivicStructure/Hospital.php';

	// MovieTheater
	include_once __DIR__ . '/CivicStructure/MovieTheater.php';

	// Museum
	include_once __DIR__ . '/CivicStructure/Museum.php';

	// MusicVenue
	include_once __DIR__ . '/CivicStructure/MusicVenue.php';

	// Park
	include_once __DIR__ . '/CivicStructure/Park.php';

	// ParkingFacility
	include_once __DIR__ . '/CivicStructure/ParkingFacility.php';

	// PerformingArtsTheater
	include_once __DIR__ . '/CivicStructure/PerformingArtsTheater.php';

	// PlaceOfWorship
	include_once __DIR__ . '/CivicStructure/PlaceOfWorship.php';

	// Playground
	include_once __DIR__ . '/CivicStructure/Playground.php';

	// PoliceStation
	include_once __DIR__ . '/CivicStructure/PoliceStation.php';

	// PublicToilet
	include_once __DIR__ . '/CivicStructure/PublicToilet.php';

	// RVPark
	include_once __DIR__ . '/CivicStructure/RVPark.php';

	// StadiumOrArena
	include_once __DIR__ . '/CivicStructure/StadiumOrArena.php';

	// SubwayStation
	include_once __DIR__ . '/CivicStructure/SubwayStation.php';

	// TaxiStand
	include_once __DIR__ . '/CivicStructure/TaxiStand.php';

	// TrainStation
	include_once __DIR__ . '/CivicStructure/TrainStation.php';

	// Zoo
	include_once __DIR__ . '/CivicStructure/Zoo.php';