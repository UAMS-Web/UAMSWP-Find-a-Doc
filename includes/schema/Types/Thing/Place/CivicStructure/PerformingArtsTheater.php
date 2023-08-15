<?php

// PerformingArtsTheater

	/*
	 * Thing > Place > CivicStructure > PerformingArtsTheater
	 * 
	 * 
	 */

	function uamswp_fad_schema_performingartstheater(
		$schema, // array // Main schema array
		// PerformingArtsTheater
			$foo = '', // foo
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

			// Inherited properties from CivicStructure (Thing > Place > CivicStructure)

				$openingHours = ( isset($openingHours) && !empty($openingHours) ) ? $openingHours : '';

			// Properties from PerformingArtsTheater (Thing > Place > CivicStructure > PerformingArtsTheater)

				$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_civicstructure(
					$schema, // array // Main schema array
					// CivicStructure
						$openingHours, // openingHours
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

			// Properties from PerformingArtsTheater (Thing > Place > CivicStructure > PerformingArtsTheater)

				// foo

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					$schema['foo'] = $foo;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

