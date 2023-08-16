<?php

// Person

	/*
	 * Thing > Person
	 * 
	 * A person (alive, dead, undead, or fictional).
	 */

	function uamswp_fad_schema_person(
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
					'additionalName', // additionalName
					'address', // address
					'affiliation', // affiliation
					'alumniOf', // alumniOf
					'award', // award
					'birthDate', // birthDate
					'birthPlace', // birthPlace
					'brand', // brand
					'callSign', // callSign
					'children', // children
					'colleague', // colleague
					'contactPoint', // contactPoint
					'deathDate', // deathDate
					'deathPlace', // deathPlace
					'duns', // duns
					'email', // email
					'familyName', // familyName
					'faxNumber', // faxNumber
					'follows', // follows
					'funder', // funder
					'funding', // funding
					'gender', // gender
					'givenName', // givenName
					'globalLocationNumber', // globalLocationNumber
					'hasCredential', // hasCredential
					'hasOccupation', // hasOccupation
					'hasOfferCatalog', // hasOfferCatalog
					'hasPOS', // hasPOS
					'height', // height
					'homeLocation', // homeLocation
					'honorificPrefix', // honorificPrefix
					'honorificSuffix', // honorificSuffix
					'interactionStatistic', // interactionStatistic
					'isicV4', // isicV4
					'jobTitle', // jobTitle
					'knows', // knows
					'knowsAbout', // knowsAbout
					'knowsLanguage', // knowsLanguage
					'makesOffer', // makesOffer
					'memberOf', // memberOf
					'naics', // naics
					'nationality', // nationality
					'netWorth', // netWorth
					'owns', // owns
					'parent', // parent
					'performerIn', // performerIn
					'publishingPrinciples', // publishingPrinciples
					'relatedTo', // relatedTo
					'seeks', // seeks
					'sibling', // sibling
					'sponsor', // sponsor
					'spouse', // spouse
					'taxID', // taxID
					'telephone', // telephone
					'vatID', // vatID
					'weight', // weight
					'workLocation', // workLocation
					'worksFor' // worksFor
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

	// Patient
	include_once __DIR__ . '/Person/Patient.php';