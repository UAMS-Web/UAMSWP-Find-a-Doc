<?php

$schema_physician = array(
	'@context' => 'https://schema.org/',
	'@type' => 'Physician',
	// Properties from Physician
		'availableService' => array(
			array(
				'@type' => 'MedicalProcedure',
				'foo' => 'bar'
			),
			array(
				'@type' => 'MedicalTest',
				'foo' => 'bar'
			)
		),
		'hospitalAffiliation' => array(
			array(
				'@type' => 'Hospital',
				'foo' => 'bar'
			)
		),
		'medicalSpecialty' => array(
			'foo', // MedicalSpecialty (Enumeration Type)
			'bar' // MedicalSpecialty (Enumeration Type)
		),
	// Properties from MedicalBusiness (no properties)
	// Properties from LocalBusiness
		'currenciesAccepted' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'openingHours' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'paymentAccepted' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'priceRange' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
	// Properties from Place
		'additionalProperty' => array(
			array(
				'@type' => 'PropertyValue',
				'foo' => 'bar'
			)
		),
		'address' => array(
			array(
				'@type' => 'PostalAddress',
				'foo' => 'bar'
			)
			'foo' // Text (Data Type)
		),
		'aggregateRating' => array(
			array(
				'@type' => 'AggregateRating',
				'foo' => 'bar'
			)
		),
		'amenityFeature' => array(
			array(
				'@type' => 'LocationFeatureSpecification',
				'foo' => 'bar'
			)
		),
		'branchCode' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'containedInPlace' => array(
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'containsPlace' => array(
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'event' => array(
			array(
				'@type' => 'Event',
				'foo' => 'bar'
			)
		),
		'faxNumber' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'geo' => array(
			array(
				'@type' => 'GeoCoordinates',
				'foo' => 'bar'
			),
			array(
				'@type' => 'GeoShape',
				'foo' => 'bar'
			)
		),
		'geoContains' => array(
			array(
				'@type' => 'GeospatialGeometry',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'geoCoveredBy' => array(
			array(
				'@type' => 'GeospatialGeometry',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'geoCovers' => array(
			array(
				'@type' => 'GeospatialGeometry',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'geoCrosses' => array(
			array(
				'@type' => 'GeospatialGeometry',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'geoDisjoint' => array(
			array(
				'@type' => 'GeospatialGeometry',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'geoEquals' => array(
			array(
				'@type' => 'GeospatialGeometry',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'geoIntersects' => array(
			array(
				'@type' => 'GeospatialGeometry',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'geoOverlaps' => array(
			array(
				'@type' => 'GeospatialGeometry',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'geoTouches' => array(
			array(
				'@type' => 'GeospatialGeometry',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'geoWithin' => array(
			array(
				'@type' => 'GeospatialGeometry',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'globalLocationNumber' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'hasDriveThroughService' => 'foo', // Boolean (Data Type)
		'hasMap' => array(
			array(
				'@type' => 'Map',
				'foo' => 'bar'
			),
			'foo' // URL (Data Type)
		),
		'isAccessibleForFree' => 'foo', // Boolean (Data Type)
		'isicV4' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'keywords' => array(
			array(
				'@type' => 'DefinedTerm',
				'foo' => 'bar'
			),
			'foo', // Text (Data Type)
			'bar' // URL (Data Type)
		),
		'latitude' => 'foo', // Number (Data Type) or Text (Data Type)
		'logo' => array(
			array(
				'@type' => 'ImageObject',
				'foo' => 'bar'
			),
			'foo', // URL (Data Type)
		),
		'longitude' => 'foo', // Number (Data Type) or Text (Data Type)
		'maximumAttendeeCapacity' => 'foo', // Integer (Data Type)
		'openingHoursSpecification' => array(
			array(
				'@type' => 'OpeningHoursSpecification',
				'foo' => 'bar'
			)
		),
		'photo' => array(
			array(
				'@type' => 'ImageObject',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Photograph',
				'foo' => 'bar'
			)
		),
		'publicAccess' => 'foo', // Boolean (Data Type)
		'review' => array(
			array(
				'@type' => 'Review',
				'foo' => 'bar'
			)
		),
		'slogan' => 'foo', // Text (Data Type)
		'smokingAllowed' => 'foo', // Boolean (Data Type)
		'specialOpeningHoursSpecification' => array(
			array(
				'@type' => 'OpeningHoursSpecification',
				'foo' => 'bar'
			)
		),
		'telephone' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'tourBookingPage' => array(
			'foo', // URL (Data Type)
			'bar' // URL (Data Type)
		),
	// Properties from MedicalOrganization
		'healthPlanNetworkId' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'isAcceptingNewPatients' => 'foo', // Boolean (Data Type)
	// Properties from Organization
		'actionableFeedbackPolicy' => array(
			array(
				'@type' => 'CreativeWork',
				'foo' => 'bar'
			),
			'foo' // URL (Data Type)
		),
		'alumni' => array(
			array(
				'@type' => 'Person',
				'foo' => 'bar'
			)
		),
		'areaServed' => array(
			array(
				'@type' => 'AdministrativeArea',
				'foo' => 'bar'
			),
			array(
				'@type' => 'GeoShape',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			),
			'foo' // Text (Data Type)
		),
		'award' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'brand' => array(
			array(
				'@type' => 'Brand',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Organization',
				'foo' => 'bar'
			)
		),
		'contactPoint' => array(
			array(
				'@type' => 'ContactPoint',
				'foo' => 'bar'
			)
		),
		'correctionsPolicy' => array(
			array(
				'@type' => '	CreativeWork',
				'foo' => 'bar'
			),
			'foo' // URL (Data Type)
		),
		'department' => array(
			array(
				'@type' => 'Organization',
				'foo' => 'bar'
			)
		),
		'dissolutionDate' => array(
			'@type' => 'Date',
			'foo' => 'bar'
		),
		'diversityPolicy' => array(
			array(
				'@type' => 'CreativeWork',
				'foo' => 'bar'
			),
			'foo' // URL (Data Type)
		),
		'diversityStaffingReport' => array(
			array(
				'@type' => 'Article',
				'foo' => 'bar'
			),
			'foo' // URL (Data Type)
		),
		'duns' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'email' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'employee' => array(
			array(
				'@type' => 'Person',
				'foo' => 'bar'
			)
		),
		'ethicsPolicy' => array(
			array(
				'@type' => 'CreativeWork',
				'foo' => 'bar'
			),
			'foo' // URL (Data Type)
		),
		'founder' => array(
			array(
				'@type' => 'Person',
				'foo' => 'bar'
			)
		),
		'foundingDate' => array(
			'@type' => 'Date',
			'foo' => 'bar'
		),
		'foundingLocation' => array(
			'@type' => 'Place',
			'foo' => 'bar'
		),
		'funder' => array(
			array(
				'@type' => 'Organization',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Person',
				'foo' => 'bar'
			)
		),
		'funding' => array(
			array(
				'@type' => 'Grant',
				'foo' => 'bar'
			)
		),
		'hasCredential' => array(
			array(
				'@type' => 'EducationalOccupationalCredential',
				'foo' => 'bar'
			)
		),
		'hasMerchantReturnPolicy' => array(
			array(
				'@type' => 'MerchantReturnPolicy',
				'foo' => 'bar'
			)
		),
		'hasOfferCatalog' => array(
			array(
				'@type' => 'OfferCatalog',
				'foo' => 'bar'
			)
		),
		'hasPOS' => array(
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			)
		),
		'interactionStatistic' => array(
			array(
				'@type' => 'InteractionCounter',
				'foo' => 'bar'
			)
		),
		'iso6523Code' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'knowsAbout' => array(
			array(
				'@type' => 'Thing',
				'foo' => 'bar'
			),
			'foo', // Text (Data Type)
			'bar' // URL (Data Type)
		),
		'knowsLanguage' => array(
			array(
				'@type' => 'Language',
				'foo' => 'bar'
			),
			'foo' // Text (Data Type)
		),
		'legalName' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'leiCode' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'location' => array(
			array(
				'@type' => 'Place',
				'foo' => 'bar'
			),
			array(
				'@type' => 'PostalAddress',
				'foo' => 'bar'
			),
			array(
				'@type' => 'VirtualLocation',
				'foo' => 'bar'
			),
			'foo' // Text (Data Type)
		),
		'makesOffer' => array(
			array(
				'@type' => 'Offer',
				'foo' => 'bar'
			)
		),
		'member' => array(
			array(
				'@type' => 'Organization',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Person',
				'foo' => 'bar'
			)
		),
		'memberOf' => array(
			array(
				'@type' => 'Organization',
				'foo' => 'bar'
			),
			array(
				'@type' => 'ProgramMembership',
				'foo' => 'bar'
			)
		),
		'naics' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'nonprofitStatus' => array(
			array(
				'@type' => 'NonprofitType',
				'foo' => 'bar'
			)
		),
		'numberOfEmployees' => array(
			array(
				'@type' => 'QuantitativeValue',
				'foo' => 'bar'
			)
		),
		'ownershipFundingInfo' => array(
			array(
				'@type' => 'AboutPage',
				'foo' => 'bar'
			),
			array(
				'@type' => 'CreativeWork',
				'foo' => 'bar'
			),
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'owns' => array(
			array(
				'@type' => 'OwnershipInfo',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Product',
				'foo' => 'bar'
			)
		),
		'parentOrganization' => array(
			array(
				'@type' => 'Organization',
				'foo' => 'bar'
			)
		),
		'publishingPrinciples' => array(
			array(
				'@type' => 'CreativeWork',
				'foo' => 'bar'
			),
			'foo' // URL (Data Type)
		),
		'seeks' => array(
			array(
				'@type' => 'Demand',
				'foo' => 'bar'
			)
		),
		'slogan' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'sponsor' => array(
			array(
				'@type' => 'Organization',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Person',
				'foo' => 'bar'
			)
		),
		'subOrganization' => array(
			array(
				'@type' => 'Organization',
				'foo' => 'bar'
			)
		),
		'taxID' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'unnamedSourcesPolicy' => array(
			array(
				'@type' => 'CreativeWork',
				'foo' => 'bar'
			),
			'foo' // URL (Data Type)
		),
		'vatID' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
	// Properties from Thing
		'additionalType' => array(
			'foo', // Text (Data Type)
			'bar' // URL (Data Type)
		),
		'alternateName' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'description' => array(
			array(
				'@type' => 'TextObject',
				'foo' => 'bar'
			),
			'foo' // Text (Data Type)
		),
		'disambiguatingDescription' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'identifier' => array(
			array(
				'@type' => 'PropertyValue',
				'foo' => 'bar'
			),
			'foo', // Text (Data Type)
			'bar' // URL (Data Type)
		),
		'image' => array(
			array(
				'@type' => 'ImageObject',
				'foo' => 'bar'
			),
			'foo' // URL (Data Type)
		),
		'mainEntityOfPage' => array(
			array(
				'@type' => 'CreativeWork',
				'foo' => 'bar'
			),
			'foo' // URL (Data Type)
		),
		'name' => array(
			'foo', // Text (Data Type)
			'bar' // Text (Data Type)
		),
		'potentialAction' => array(
			array(
				'@type' => 'Action',
				'foo' => 'bar'
			)
		),
		'sameAs' => array(
			'foo', // URL (Data Type)
			'bar' // URL (Data Type)
		),
		'subjectOf' => array(
			array(
				'@type' => 'CreativeWork',
				'foo' => 'bar'
			),
			array(
				'@type' => 'Event',
				'foo' => 'bar'
			)
		),
		'url' => 'foo' // URL (Data Type)
);