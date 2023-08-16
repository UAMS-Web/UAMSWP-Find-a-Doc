<?php

// Organization

	/*
	 * Thing > Organization
	 * 
	 * An organization such as a school, NGO, corporation, club, etc.
	 */

	function uamswp_fad_schema_organization(
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
					'actionableFeedbackPolicy',
					'address',
					'aggregateRating',
					'alumni',
					'areaServed',
					'award',
					'brand',
					'contactPoint',
					'correctionsPolicy',
					'department',
					'dissolutionDate',
					'diversityPolicy',
					'diversityStaffingReport',
					'duns',
					'email',
					'employee',
					'ethicsPolicy',
					'event',
					'faxNumber',
					'founder',
					'foundingDate',
					'foundingLocation',
					'funder',
					'funding',
					'globalLocationNumber',
					'hasCredential',
					'hasMerchantReturnPolicy',
					'hasOfferCatalog',
					'hasPOS',
					'interactionStatistic',
					'isicV4',
					'iso6523Code',
					'keywords',
					'knowsAbout',
					'knowsLanguage',
					'legalName',
					'leiCode',
					'location',
					'logo',
					'makesOffer',
					'member',
					'memberOf',
					'naics',
					'nonprofitStatus',
					'numberOfEmployees',
					'ownershipFundingInfo',
					'owns',
					'parentOrganization',
					'publishingPrinciples',
					'review',
					'seeks',
					'slogan',
					'sponsor',
					'subOrganization',
					'taxID',
					'telephone',
					'unnamedSourcesPolicy',
					'vatID'
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

	// Airline
	include_once __DIR__ . '/Organization/Airline.php';

		/*
		 * Thing > Organization > Airline
		 * 
		 * 
		 */

		function uamswp_fad_schema_airline(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

	// Consortium
	include_once __DIR__ . '/Organization/Consortium.php';

		/*
		 * Thing > Organization > Consortium
		 * 
		 * 
		 */

		function uamswp_fad_schema_consortium(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

	// Corporation
	include_once __DIR__ . '/Organization/Corporation.php';

		/*
		 * Thing > Organization > Corporation
		 * 
		 * 
		 */

		function uamswp_fad_schema_corporation(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

	// EducationalOrganization
	include_once __DIR__ . '/Organization/EducationalOrganization.php';

		/*
		 * Thing > Organization > EducationalOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_educationalorganization(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

		// CollegeOrUniversity

			/*
			 * Thing > Organization > EducationalOrganization > CollegeOrUniversity
			 * 
			 * 
			 */

			function uamswp_fad_schema_collegeoruniversity(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

		// ElementarySchool

			/*
			 * Thing > Organization > EducationalOrganization > ElementarySchool
			 * 
			 * 
			 */

			function uamswp_fad_schema_elementaryschool(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

		// HighSchool

			/*
			 * Thing > Organization > EducationalOrganization > HighSchool
			 * 
			 * 
			 */

			function uamswp_fad_schema_highschool(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

		// MiddleSchool

			/*
			 * Thing > Organization > EducationalOrganization > MiddleSchool
			 * 
			 * 
			 */

			function uamswp_fad_schema_middleschool(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

		// Preschool

			/*
			 * Thing > Organization > EducationalOrganization > Preschool
			 * 
			 * 
			 */

			function uamswp_fad_schema_preschool(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

		// School

			/*
			 * Thing > Organization > EducationalOrganization > School
			 * 
			 * 
			 */

			function uamswp_fad_schema_school(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

	// FundingScheme
	include_once __DIR__ . '/Organization/FundingScheme.php';

		/*
		 * Thing > Organization > FundingScheme
		 * 
		 * 
		 */

		function uamswp_fad_schema_fundingscheme(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

	// GovernmentOrganization
	include_once __DIR__ . '/Organization/GovernmentOrganization.php';

		/*
		 * Thing > Organization > GovernmentOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_governmentorganization(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

	// LibrarySystem
	include_once __DIR__ . '/Organization/LibrarySystem.php';

		/*
		 * Thing > Organization > LibrarySystem
		 * 
		 * 
		 */

		function uamswp_fad_schema_librarysystem(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

	// LocalBusiness
	include_once __DIR__ . '/Organization/LocalBusiness.php';

	// MedicalOrganization
	include_once __DIR__ . '/Organization/MedicalOrganization.php';

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
						 *     DataType > Text
						 * 
						 * Name or unique ID of network. (Networks are often reused across different 
						 * insurance plans.)
						 */

						$schema['healthPlanNetworkId'] = ( isset($healthPlanNetworkId) && !empty($healthPlanNetworkId) ) ? uamswp_fad_schema_type_selector($healthPlanNetworkId) : '';

					// isAcceptingNewPatients

						/* 
						 * Expected Type:
						 *     Boolean
						 * 
						 * Whether the provider is accepting new patients.
						 */

						$schema['isAcceptingNewPatients'] = ( isset($isAcceptingNewPatients) && !empty($isAcceptingNewPatients) ) ? uamswp_fad_schema_type_selector($isAcceptingNewPatients) : '';

					// medicalSpecialty

						/* 
						 * Expected Type:
						 *     Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty
						 * 
						 * A medical specialty of the provider.
						 */

						$schema['medicalSpecialty'] = ( isset($medicalSpecialty) && !empty($medicalSpecialty) ) ? uamswp_fad_schema_type_selector($medicalSpecialty) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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
	include_once __DIR__ . '/Organization/NGO.php';

		/*
		 * Thing > Organization > NGO
		 * 
		 * 
		 */

		function uamswp_fad_schema_ngo(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

	// NewsMediaOrganization
	include_once __DIR__ . '/Organization/NewsMediaOrganization.php';

		/*
		 * Thing > Organization > NewsMediaOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_newsmediaorganization(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

	// OnlineBusiness
	include_once __DIR__ . '/Organization/OnlineBusiness.php';

		/*
		 * Thing > Organization > OnlineBusiness
		 * 
		 * 
		 */

		function uamswp_fad_schema_onlinebusiness(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

		// OnlineStore

			/*
			 * Thing > Organization > OnlineBusiness > OnlineStore
			 * 
			 * 
			 */

			function uamswp_fad_schema_onlinestore(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

	// PerformingGroup
	include_once __DIR__ . '/Organization/PerformingGroup.php';

		/*
		 * Thing > Organization > PerformingGroup
		 * 
		 * 
		 */

		function uamswp_fad_schema_performinggroup(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

		// DanceGroup

			/*
			 * Thing > Organization > PerformingGroup > DanceGroup
			 * 
			 * 
			 */

			function uamswp_fad_schema_dancegroup(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

		// MusicGroup

			/*
			 * Thing > Organization > PerformingGroup > MusicGroup
			 * 
			 * 
			 */

			function uamswp_fad_schema_musicgroup(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

		// TheaterGroup

			/*
			 * Thing > Organization > PerformingGroup > TheaterGroup
			 * 
			 * 
			 */

			function uamswp_fad_schema_theatergroup(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

	// PoliticalParty
	include_once __DIR__ . '/Organization/PoliticalParty.php';

		/*
		 * Thing > Organization > PoliticalParty
		 * 
		 * 
		 */

		function uamswp_fad_schema_politicalparty(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

	// Project
	include_once __DIR__ . '/Organization/Project.php';

		/*
		 * Thing > Organization > Project
		 * 
		 * 
		 */

		function uamswp_fad_schema_project(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

		// FundingAgency

			/*
			 * Thing > Organization > Project > FundingAgency
			 * 
			 * 
			 */

			function uamswp_fad_schema_fundingagency(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

		// ResearchProject

			/*
			 * Thing > Organization > Project > ResearchProject
			 * 
			 * 
			 */

			function uamswp_fad_schema_researchproject(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

	// ResearchOrganization
	include_once __DIR__ . '/Organization/ResearchOrganization.php';

		/*
		 * Thing > Organization > ResearchOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_researchorganization(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

	// SearchRescueOrganization
	include_once __DIR__ . '/Organization/SearchRescueOrganization.php';

		/*
		 * Thing > Organization > SearchRescueOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_searchrescueorganization(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

	// SportsOrganization
	include_once __DIR__ . '/Organization/SportsOrganization.php';

		/*
		 * Thing > Organization > SportsOrganization
		 * 
		 * 
		 */

		function uamswp_fad_schema_sportsorganization(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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

		// SportsTeam

			/*
			 * Thing > Organization > SportsOrganization > SportsTeam
			 * 
			 * 
			 */

			function uamswp_fad_schema_sportsteam(
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
							'baz'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
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

	// WorkersUnion
	include_once __DIR__ . '/Organization/WorkersUnion.php';

		/*
		 * Thing > Organization > WorkersUnion
		 * 
		 * 
		 */

		function uamswp_fad_schema_workersunion(
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
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
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