<?php

// Landform

	/*
	 * Thing > Place > Landform
	 * 
	 * 
	 */

	function uamswp_fad_schema_landform(
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

	// BodyOfWater
	include_once __DIR__ . '/Landform/BodyOfWater.php';

		/*
		 * Thing > Place > Landform > BodyOfWater
		 * 
		 * 
		 */

		function uamswp_fad_schema_bodyofwater(
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

		// Canal

			/*
			 * Thing > Place > Landform > BodyOfWater > Canal
			 * 
			 * 
			 */

			function uamswp_fad_schema_canal(
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

		// LakeBodyOfWater

			/*
			 * Thing > Place > Landform > BodyOfWater > LakeBodyOfWater
			 * 
			 * 
			 */

			function uamswp_fad_schema_lakebodyofwater(
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

		// OceanBodyOfWater

			/*
			 * Thing > Place > Landform > BodyOfWater > OceanBodyOfWater
			 * 
			 * 
			 */

			function uamswp_fad_schema_oceanbodyofwater(
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

		// Pond

			/*
			 * Thing > Place > Landform > BodyOfWater > Pond
			 * 
			 * 
			 */

			function uamswp_fad_schema_pond(
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

		// Reservoir

			/*
			 * Thing > Place > Landform > BodyOfWater > Reservoir
			 * 
			 * 
			 */

			function uamswp_fad_schema_reservoir(
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

		// RiverBodyOfWater

			/*
			 * Thing > Place > Landform > BodyOfWater > RiverBodyOfWater
			 * 
			 * 
			 */

			function uamswp_fad_schema_riverbodyofwater(
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

		// SeaBodyOfWater

			/*
			 * Thing > Place > Landform > BodyOfWater > SeaBodyOfWater
			 * 
			 * 
			 */

			function uamswp_fad_schema_seabodyofwater(
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

		// Waterfall

			/*
			 * Thing > Place > Landform > BodyOfWater > Waterfall
			 * 
			 * 
			 */

			function uamswp_fad_schema_waterfall(
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

	// Continent
	include_once __DIR__ . '/Landform/Continent.php';

		/*
		 * Thing > Place > Landform > Continent
		 * 
		 * 
		 */

		function uamswp_fad_schema_continent(
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

	// Mountain
	include_once __DIR__ . '/Landform/Mountain.php';

		/*
		 * Thing > Place > Landform > Mountain
		 * 
		 * 
		 */

		function uamswp_fad_schema_mountain(
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

	// Volcano
	include_once __DIR__ . '/Landform/Volcano.php';

		/*
		 * Thing > Place > Landform > Volcano
		 * 
		 * 
		 */

		function uamswp_fad_schema_volcano(
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

