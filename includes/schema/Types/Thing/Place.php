<?php

// Place

	/*
	 * Thing > Place
	 * 
	 * 
	 */

	function uamswp_fad_schema_place(
		array $schema, // Main schema array
		array $input // Array of properties and values for a type and its parent types
	) {

		/* 

			Expected format for the array of properties and values for a type and its 
			parent types:

				$input = array(
					'type'			=> 'Foo',
					'properties'	=> array(
						// Foo
							'baz'	=> '', // baz
							'qux'	=> '' // qux
						// Bar
							$quux	=> '', // quux
							$corge	=> '' // corge
					)
				);

		 */

		// Check/define variables

			// Main schema array

			$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

			// Immediate parent(s) of this type

				/*

					The type name and capitalization must match what is found on Schema.org.

				*/

				$type_parent = array(
					'Thing'
				);

			// Properties from this type (and not from the parent[s] of this type)

				/*

					The type name and capitalization must match what is found on Schema.org.

					Find the expected types of their values on Schema.org page for this type
					(e.g., https://schema.org/Thing).

				*/

				$type_properties = array(
					'additionalProperty',
					'address',
					'aggregateRating',
					'amenityFeature',
					'branchCode',
					'containedInPlace',
					'containsPlace',
					'event',
					'faxNumber',
					'geo',
					'geoContains',
					'geoCoveredBy',
					'geoCovers',
					'geoCrosses',
					'geoDisjoint',
					'geoEquals',
					'geoIntersects',
					'geoOverlaps',
					'geoTouches',
					'geoWithin',
					'globalLocationNumber',
					'hasDriveThroughService',
					'hasMap',
					'isAccessibleForFree',
					'isicV4',
					'keywords',
					'latitude',
					'logo',
					'longitude',
					'maximumAttendeeCapacity',
					'openingHoursSpecification',
					'photo',
					'publicAccess',
					'review',
					'slogan',
					'smokingAllowed',
					'specialOpeningHoursSpecification',
					'telephone',
					'tourBookingPage'
				);

		// Construct schema array

			$schema = uamswp_fad_schema_construct_array(
				$schema, // Main schema array
				$input, // Array of properties and values for the type and its parent types
				$type_properties, // Array of properties available to the type
				$type_parent // Array of the immediate parent(s) of this type
			);

		return $schema;

	}

	// Accommodation
	include_once __DIR__ . '/Place/Accommodation.php';

	// AdministrativeArea
	include_once __DIR__ . '/Place/AdministrativeArea.php';

	// CivicStructure
	include_once __DIR__ . '/Place/CivicStructure.php';

	// Landform
	include_once __DIR__ . '/Place/Landform.php';

	// LandmarksOrHistoricalBuildings
	include_once __DIR__ . '/Place/LandmarksOrHistoricalBuildings.php';

	// LocalBusiness
	include_once __DIR__ . '/Place/LocalBusiness.php';

	// Residence
	include_once __DIR__ . '/Place/Residence.php';

	// TouristAttraction
	include_once __DIR__ . '/Place/TouristAttraction.php';

	// TouristDestination
	include_once __DIR__ . '/Place/TouristDestination.php';