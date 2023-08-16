<?php

// Product

	/*
	 * Thing > Product
	 * 
	 * 
	 */

	function uamswp_fad_schema_product(
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

	// DietarySupplement

		/*
		 * Thing > Product > DietarySupplement
		 * 
		 * 
		 */

		function uamswp_fad_schema_dietarysupplement(
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

	// Drug

		/*
		 * Thing > Product > Drug
		 * 
		 * 
		 */

		function uamswp_fad_schema_drug(
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

	// IndividualProduct

		/*
		 * Thing > Product > IndividualProduct
		 * 
		 * 
		 */

		function uamswp_fad_schema_individualproduct(
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

	// ProductCollection

		/*
		 * Thing > Product > ProductCollection
		 * 
		 * 
		 */

		function uamswp_fad_schema_productcollection(
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

	// ProductGroup

		/*
		 * Thing > Product > ProductGroup
		 * 
		 * 
		 */

		function uamswp_fad_schema_productgroup(
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

	// ProductModel

		/*
		 * Thing > Product > ProductModel
		 * 
		 * 
		 */

		function uamswp_fad_schema_productmodel(
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

	// SomeProducts

		/*
		 * Thing > Product > SomeProducts
		 * 
		 * 
		 */

		function uamswp_fad_schema_someproducts(
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

	// Vehicle

		/*
		 * Thing > Product > Vehicle
		 * 
		 * 
		 */

		function uamswp_fad_schema_vehicle(
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

		// BusOrCoach

			/*
			 * Thing > Product > Vehicle > BusOrCoach
			 * 
			 * 
			 */

			function uamswp_fad_schema_busorcoach(
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

		// Car

			/*
			 * Thing > Product > Vehicle > Car
			 * 
			 * 
			 */

			function uamswp_fad_schema_car(
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

		// Motorcycle

			/*
			 * Thing > Product > Vehicle > Motorcycle
			 * 
			 * 
			 */

			function uamswp_fad_schema_motorcycle(
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

		// MotorizedBicycle

			/*
			 * Thing > Product > Vehicle > MotorizedBicycle
			 * 
			 * 
			 */

			function uamswp_fad_schema_motorizedbicycle(
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