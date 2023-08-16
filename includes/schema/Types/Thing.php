<?php

// Thing

	/*
	 * The most generic type of item.
	 */

	 if ( true == false ) {
		
		$uamswp_fad_schema_physician_vars = array(
			'type'			=> 'Thing',
			'properties'	=> array(
				'additionalType'			=> '',
				'alternateName'				=> '',
				'description'				=> '',
				'disambiguatingDescription'	=> '',
				'identifier'				=> '',
				'image'						=> '',
				'mainEntityOfPage'			=> '',
				'name'						=> '',
				'potentialAction'			=> '',
				'sameAs'					=> '',
				'subjectOf'					=> '',
				'url'						=> ''
			)
		);

	}

	function uamswp_fad_schema_thing(
		array $schema, // Main schema array
		array $input // Array of properties and values for a type and its parent types
	) {

		/* 

			Expected format for the array of properties and values for a type and its 
			parent types:

				$input = array(
					'type'			=> 'Thing',
					'properties'	=> array(
						// Thing
							$additionalType						=> '', // additionalType
							$alternateName						=> '', // alternateName
							$description						=> '', // description
							$disambiguatingDescription			=> '', // disambiguatingDescription
							$identifier							=> '', // identifier
							$image								=> '', // image
							$mainEntityOfPage					=> '', // mainEntityOfPage
							$name								=> '', // name
							$potentialAction					=> '', // potentialAction
							$sameAs								=> '', // sameAs
							$subjectOf							=> '', // subjectOf
							$url								=> '' // url
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

				$type_parent = array();

			// Properties from this type

				/*

					The type name and capitalization must match what is found on Schema.org.

					Find the expected types of their values on Schema.org page for this type
					(e.g., https://schema.org/Thing).

				*/

				$type_properties = array(
					'additionalType',
					'alternateName',
					'description',
					'disambiguatingDescription',
					'identifier',
					'image',
					'mainEntityOfPage',
					'name',
					'potentialAction',
					'sameAs',
					'subjectOf',
					'url'
				);

		// Construct schema array

			$schema = uamswp_fad_schema_construct_array( $schema, $input, $type_properties, $type_parent );

		return $schema;

	}

	// Action
	include_once __DIR__ . '/Thing/Action.php';

	// BioChemEntity
	include_once __DIR__ . '/Thing/BioChemEntity.php';

	// // CreativeWork
	include_once __DIR__ . '/Thing/CreativeWork.php';

	// Event
	include_once __DIR__ . '/Thing/Event.php';

	// // Intangible
	include_once __DIR__ . '/Thing/Intangible.php';

	// // MedicalEntity
	include_once __DIR__ . '/Thing/MedicalEntity.php';

	// Organization
	include_once __DIR__ . '/Thing/Organization.php';

	// Person
	include_once __DIR__ . '/Thing/Person.php';

	// // Place
	include_once __DIR__ . '/Thing/Place.php';

	// Product
	include_once __DIR__ . '/Thing/Product.php';

	// Taxon
	include_once __DIR__ . '/Thing/Taxon.php';