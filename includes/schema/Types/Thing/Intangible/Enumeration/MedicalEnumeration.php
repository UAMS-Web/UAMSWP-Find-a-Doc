<?php

// MedicalEnumeration

	/*
	 * Thing > Intangible > Enumeration > MedicalEnumeration
	 * 
	 * Enumerations related to health and the practice of medicine: A concept that is 
	 * used to attribute a quality to another concept, as a qualifier, a collection of 
	 * items or a listing of all of the elements of a set in medicine practice.
	 */

	function uamswp_fad_schema_medicalenumeration(
		$schema, // array // Main schema array
		// MedicalEnumeration (no property vars)
		// Enumeration
			$supersededBy, // supersededBy
		// Intangible (no property vars)
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

			// Inherited properties from Intangible (Thing > Intangible)

				// Do nothing (no property vars)

			// Inherited properties from Enumeration (Thing > Intangible > Enumeration)

				$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';

			// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)

				// Do nothing (no property vars)

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_enumeration(
					$schema, // array // Main schema array
					// Enumeration
						$supersededBy, // supersededBy
					// Intangible (no property vars)
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

			// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)

				// Do nothing (no property vars)

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}

	// DrugCostCategory
	include_once __DIR__ . '/MedicalEnumeration/DrugCostCategory.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > DrugCostCategory
		 * 
		 * 
		 */

		function uamswp_fad_schema_drugcostcategory(
			$schema, // array // Main schema array
			// DrugCostCategory (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from DrugCostCategory (Thing > Intangible > Enumeration > MedicalEnumeration > DrugCostCategory)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from DrugCostCategory (Thing > Intangible > Enumeration > MedicalEnumeration > DrugCostCategory)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// ReimbursementCap

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > ReimbursementCap
			 * 
			 * 
			 */

			function uamswp_fad_schema_reimbursementcap(
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

		// Retail

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Retail
			 * 
			 * 
			 */

			function uamswp_fad_schema_retail(
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

		// Wholesale

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Wholesale
			 * 
			 * 
			 */

			function uamswp_fad_schema_wholesale(
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

	// DrugPregnancyCategory
	include_once __DIR__ . '/MedicalEnumeration/DrugPregnancyCategory.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > DrugPregnancyCategory
		 * 
		 * 
		 */

		function uamswp_fad_schema_drugpregnancycategory(
			$schema, // array // Main schema array
			// DrugPregnancyCategory (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from DrugPregnancyCategory (Thing > Intangible > Enumeration > MedicalEnumeration > DrugPregnancyCategory)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from DrugPregnancyCategory (Thing > Intangible > Enumeration > MedicalEnumeration > DrugPregnancyCategory)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// FDAcategoryA

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAcategoryA
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdacategorya(
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

		// FDAcategoryB

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAcategoryB
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdacategoryb(
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

		// FDAcategoryC

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAcategoryC
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdacategoryc(
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

		// FDAcategoryD

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAcategoryD
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdacategoryd(
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

		// FDAcategoryX

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAcategoryX
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdacategoryx(
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

		// FDAnotEvaluated

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAnotEvaluated
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdanotevaluated(
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

	// DrugPrescriptionStatus
	include_once __DIR__ . '/MedicalEnumeration/DrugPrescriptionStatus.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > DrugPrescriptionStatus
		 * 
		 * 
		 */

		function uamswp_fad_schema_drugprescriptionstatus(
			$schema, // array // Main schema array
			// DrugPrescriptionStatus (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from DrugPrescriptionStatus (Thing > Intangible > Enumeration > MedicalEnumeration > DrugPrescriptionStatus)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from DrugPrescriptionStatus (Thing > Intangible > Enumeration > MedicalEnumeration > DrugPrescriptionStatus)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// OTC

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > OTC
			 * 
			 * 
			 */

			function uamswp_fad_schema_otc(
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

		// PrescriptionOnly

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > PrescriptionOnly
			 * 
			 * 
			 */

			function uamswp_fad_schema_prescriptiononly(
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

	// InfectiousAgentClass
	include_once __DIR__ . '/MedicalEnumeration/InfectiousAgentClass.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > InfectiousAgentClass
		 * 
		 * 
		 */

		function uamswp_fad_schema_infectiousagentclass(
			$schema, // array // Main schema array
			// InfectiousAgentClass (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from InfectiousAgentClass (Thing > Intangible > Enumeration > MedicalEnumeration > InfectiousAgentClass)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from InfectiousAgentClass (Thing > Intangible > Enumeration > MedicalEnumeration > InfectiousAgentClass)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// Bacteria

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Bacteria
			 * 
			 * 
			 */

			function uamswp_fad_schema_bacteria(
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

		// Fungus

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Fungus
			 * 
			 * 
			 */

			function uamswp_fad_schema_fungus(
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

		// MulticellularParasite

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > MulticellularParasite
			 * 
			 * 
			 */

			function uamswp_fad_schema_multicellularparasite(
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

		// Prion

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Prion
			 * 
			 * 
			 */

			function uamswp_fad_schema_prion(
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

		// Protozoa

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Protozoa
			 * 
			 * 
			 */

			function uamswp_fad_schema_protozoa(
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

		// Virus

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Virus
			 * 
			 * 
			 */

			function uamswp_fad_schema_virus(
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

	// MedicalAudienceType
	include_once __DIR__ . '/MedicalEnumeration/MedicalAudienceType.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalAudienceType
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalaudiencetype(
			$schema, // array // Main schema array
			// MedicalAudienceType (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalAudienceType (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalAudienceType)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from MedicalAudienceType (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalAudienceType)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// Clinician

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Clinician
			 * 
			 * 
			 */

			function uamswp_fad_schema_clinician(
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

		// MedicalResearcher

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > MedicalResearcher
			 * 
			 * 
			 */

			function uamswp_fad_schema_medicalresearcher(
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

	// MedicalDevicePurpose
	include_once __DIR__ . '/MedicalEnumeration/MedicalDevicePurpose.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalDevicePurpose
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicaldevicepurpose(
			$schema, // array // Main schema array
			// MedicalDevicePurpose (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalDevicePurpose (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalDevicePurpose)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from MedicalDevicePurpose (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalDevicePurpose)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// Diagnostic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Diagnostic
			 * 
			 * 
			 */

			function uamswp_fad_schema_diagnostic(
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

		// Therapeutic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Therapeutic
			 * 
			 * 
			 */

			function uamswp_fad_schema_therapeutic(
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

	// MedicalEvidenceLevel
	include_once __DIR__ . '/MedicalEnumeration/MedicalEvidenceLevel.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalEvidenceLevel
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalevidencelevel(
			$schema, // array // Main schema array
			// MedicalEvidenceLevel (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalEvidenceLevel (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalEvidenceLevel)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from MedicalEvidenceLevel (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalEvidenceLevel)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// EvidenceLevelA

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > EvidenceLevelA
			 * 
			 * 
			 */

			function uamswp_fad_schema_evidencelevela(
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

		// EvidenceLevelB

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > EvidenceLevelB
			 * 
			 * 
			 */

			function uamswp_fad_schema_evidencelevelb(
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

		// EvidenceLevelC

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > EvidenceLevelC
			 * 
			 * 
			 */

			function uamswp_fad_schema_evidencelevelc(
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

	// MedicalImagingTechnique
	include_once __DIR__ . '/MedicalEnumeration/MedicalImagingTechnique.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalImagingTechnique
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalimagingtechnique(
			$schema, // array // Main schema array
			// MedicalImagingTechnique (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalImagingTechnique (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalImagingTechnique)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from MedicalImagingTechnique (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalImagingTechnique)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// CT

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > CT
			 * 
			 * 
			 */

			function uamswp_fad_schema_ct(
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

		// MRI

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > MRI
			 * 
			 * 
			 */

			function uamswp_fad_schema_mri(
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

		// PET

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > PET
			 * 
			 * 
			 */

			function uamswp_fad_schema_pet(
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

		// Radiography

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Radiography
			 * 
			 * 
			 */

			function uamswp_fad_schema_radiography(
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

		// Ultrasound

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Ultrasound
			 * 
			 * 
			 */

			function uamswp_fad_schema_ultrasound(
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

		// XRay

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > XRay
			 * 
			 * 
			 */

			function uamswp_fad_schema_xray(
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

	// MedicalObservationalStudyDesign
	include_once __DIR__ . '/MedicalEnumeration/MedicalObservationalStudyDesign.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalObservationalStudyDesign
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalobservationalstudydesign(
			$schema, // array // Main schema array
			// MedicalObservationalStudyDesign (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalObservationalStudyDesign (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalObservationalStudyDesign)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from MedicalObservationalStudyDesign (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalObservationalStudyDesign)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// CaseSeries

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > CaseSeries
			 * 
			 * 
			 */

			function uamswp_fad_schema_caseseries(
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

		// CohortStudy

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > CohortStudy
			 * 
			 * 
			 */

			function uamswp_fad_schema_cohortstudy(
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

		// CrossSectional

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > CrossSectional
			 * 
			 * 
			 */

			function uamswp_fad_schema_crosssectional(
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

		// Longitudinal

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Longitudinal
			 * 
			 * 
			 */

			function uamswp_fad_schema_longitudinal(
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

		// Observational

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Observational
			 * 
			 * 
			 */

			function uamswp_fad_schema_observational(
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

		// Registry

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Registry
			 * 
			 * 
			 */

			function uamswp_fad_schema_registry(
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

	// MedicalProcedureType
	include_once __DIR__ . '/MedicalEnumeration/MedicalProcedureType.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalProcedureType
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalproceduretype(
			$schema, // array // Main schema array
			// MedicalProcedureType (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalProcedureType (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalProcedureType)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from MedicalProcedureType (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalProcedureType)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// NoninvasiveProcedure

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > NoninvasiveProcedure
			 * 
			 * 
			 */

			function uamswp_fad_schema_noninvasiveprocedure(
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

		// PercutaneousProcedure

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > PercutaneousProcedure
			 * 
			 * 
			 */

			function uamswp_fad_schema_percutaneousprocedure(
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

	// MedicalSpecialty
	include_once __DIR__ . '/MedicalEnumeration/MedicalSpecialty.php';

	// MedicalStudyStatus
	include_once __DIR__ . '/MedicalEnumeration/MedicalStudyStatus.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalStudyStatus
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalstudystatus(
			$schema, // array // Main schema array
			// MedicalStudyStatus (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalStudyStatus (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalStudyStatus)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from MedicalStudyStatus (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalStudyStatus)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// ActiveNotRecruiting

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > ActiveNotRecruiting
			 * 
			 * 
			 */

			function uamswp_fad_schema_activenotrecruiting(
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

		// Completed

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Completed
			 * 
			 * 
			 */

			function uamswp_fad_schema_completed(
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

		// EnrollingByInvitation

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > EnrollingByInvitation
			 * 
			 * 
			 */

			function uamswp_fad_schema_enrollingbyinvitation(
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

		// NotYetRecruiting

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > NotYetRecruiting
			 * 
			 * 
			 */

			function uamswp_fad_schema_notyetrecruiting(
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

		// Recruiting

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Recruiting
			 * 
			 * 
			 */

			function uamswp_fad_schema_recruiting(
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

		// ResultsAvailable

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > ResultsAvailable
			 * 
			 * 
			 */

			function uamswp_fad_schema_resultsavailable(
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

		// ResultsNotAvailable

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > ResultsNotAvailable
			 * 
			 * 
			 */

			function uamswp_fad_schema_resultsnotavailable(
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

		// Suspended

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Suspended
			 * 
			 * 
			 */

			function uamswp_fad_schema_suspended(
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

		// Terminated

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Terminated
			 * 
			 * 
			 */

			function uamswp_fad_schema_terminated(
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

		// Withdrawn

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Withdrawn
			 * 
			 * 
			 */

			function uamswp_fad_schema_withdrawn(
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

	// MedicalTrialDesign
	include_once __DIR__ . '/MedicalEnumeration/MedicalTrialDesign.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalTrialDesign
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicaltrialdesign(
			$schema, // array // Main schema array
			// MedicalTrialDesign (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalTrialDesign (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalTrialDesign)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from MedicalTrialDesign (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalTrialDesign)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// DoubleBlindedTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > DoubleBlindedTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_doubleblindedtrial(
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

		// InternationalTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > InternationalTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_internationaltrial(
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

		// MultiCenterTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > MultiCenterTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_multicentertrial(
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

		// OpenTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > OpenTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_opentrial(
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

		// PlaceboControlledTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > PlaceboControlledTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_placebocontrolledtrial(
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

		// RandomizedTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > RandomizedTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_randomizedtrial(
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

		// SingleBlindedTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > SingleBlindedTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_singleblindedtrial(
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

		// SingleCenterTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > SingleCenterTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_singlecentertrial(
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

		// TripleBlindedTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > TripleBlindedTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_tripleblindedtrial(
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

	// MedicineSystem
	include_once __DIR__ . '/MedicalEnumeration/MedicineSystem.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicineSystem
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicinesystem(
			$schema, // array // Main schema array
			// MedicineSystem (no property vars)
			// MedicalEnumeration (no property vars)
			// Enumeration
				$supersededBy, // supersededBy
			// Intangible (no property vars)
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
	
				// Inherited properties from Intangible (Thing > Intangible)
	
					// Do nothing (no property vars)
	
				// Inherited properties from Enumeration (Thing > Intangible > Enumeration)
	
					$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicineSystem (Thing > Intangible > Enumeration > MedicalEnumeration > MedicineSystem)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
						// MedicalEnumeration (no property vars)
						// Enumeration
							$supersededBy, // supersededBy
						// Intangible (no property vars)
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
	
				// Properties from MedicineSystem (Thing > Intangible > Enumeration > MedicalEnumeration > MedicineSystem)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);
	
			return $schema;
	
		}

		// Ayurvedic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Ayurvedic
			 * 
			 * 
			 */

			function uamswp_fad_schema_ayurvedic(
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

		// Chiropractic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Chiropractic
			 * 
			 * 
			 */

			function uamswp_fad_schema_chiropractic(
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

		// Homeopathic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Homeopathic
			 * 
			 * 
			 */

			function uamswp_fad_schema_homeopathic(
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

		// Osteopathic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Osteopathic
			 * 
			 * 
			 */

			function uamswp_fad_schema_osteopathic(
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

		// TraditionalChinese

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > TraditionalChinese
			 * 
			 * 
			 */

			function uamswp_fad_schema_traditionalchinese(
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

		// WesternConventional

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > WesternConventional
			 * 
			 * 
			 */

			function uamswp_fad_schema_westernconventional(
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

	// PhysicalExam
	include_once __DIR__ . '/MedicalEnumeration/PhysicalExam.php';