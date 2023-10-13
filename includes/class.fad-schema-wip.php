<?php

// Schema value string vs. array

	function uamswp_fad_schema_value_string_array(
		$property,
		$value
	) {

		if ( $value ) {

			$property = isset($property) ? $property : '';

			if ( is_array($value) ) {

				${$property}[] = $value;

			} else {

				${$property} = $value;

			}

		} else {

				${$property} = '';

		}

		return ${$property};

	}

// Check if key exists in multidimensional array

	function uamswp_fad_multi_key_exists( array $arr, $key ) {

		// Is the key in the base array?

			if ( array_key_exists( $key, $arr ) ) {

				return true;

			}

		// Check arrays contained in this array for the key

			foreach ( $arr as $element ) {

				if ( is_array($element) ) {

					if ( uamswp_fad_multi_key_exists( $element, $key ) ) {

						return true;

					}

				}

			}

		return false;

	}

// Execute the schema function relevant to the array's schema type

	function uamswp_fad_schema_type_selector($input) {

		/*

		Expected formats for the array:

			// Single property value

				$var = array(
					'type'			=> 'MedicalProcedure',
					'properties'	=> array(
						'foo'	=> '',
						'bar'	=> '',
						'baz'	=> ''
					)
				);

			// Multiple property values

				$var = array(
					array(
						'type'			=> 'MedicalProcedure',
						'properties'	=> array(
							'foo'	=> '',
							'bar'	=> '',
							'baz'	=> ''
						)
					),
					array(
						'type'			=> 'MedicalTest',
						'properties'	=> array(
							'foo'	=> '',
							'bar'	=> '',
							'baz'	=> ''
						)
					)
				);

		 */

		if ( !uamswp_fad_multi_key_exists( $input, 'type' ) ) {

			return;

		}

		// Get the type
		$type = $input['type'];

		// Construct the function name
		$function = 'uamswp_fad_schema_' . strtolower($type);

		// Run the function (if it exists)

			if ( function_exists($function) ) {

				return $function($input);

			} else {

				return;

			}

	}

// Construct the schema array

	function uamswp_fad_schema_construct_array(
		array $schema, // Main schema array
		array $input, // Array of properties and values for the type and its parent types
		array $type_properties, // Array of properties available to the type
		array $type_parent // Array of the immediate parent(s) of this type
	) {

		/*

			The keys in the $input array must match the Schema.org property names exactly.

		*/

		// If either the list of properties or the list of parents are empty, stop now

			if (
				empty( array_filter($type_properties) )
				||
				empty( array_filter($type_parent) )
			) {

				return $schema;

			}

		// Extract variables from the input properties array

			foreach ( $input['properties'] as $key => $value ) {

				${$key} = $value;

			}

		// Add values to the schema block array for the properties of this type

			if ( !empty( array_filter($type_properties) ) ) {

				foreach ( $type_properties as $property ) {

					$schema[$property] = ( isset($property) && !empty($property) ) ? uamswp_fad_schema_type_selector($property) : '';

				} // endforeach ( $type_properties as $property )

			} // endif ( !empty( array_filter($type_properties) ) )

		// Add values to the schema block array for the properties of the parent(s) of this type

			if ( !empty( array_filter($type_parent) ) ) {

				// Loop through each parent that is listed in the array

					foreach ( $type_parent as $parent ) {

						// Construct the name of function relevant to the parent of this type

							$parent_function = 'uamswp_fad_schema_' . strtolower($type_parent);

						// Run the function (if it exists)

							if ( function_exists($parent_function) ) {

								$schema = $parent_function(
									$schema, // array // Main schema array
									$input // array // Properties from this type
								);

							} // endif ( function_exists($parent_function) )

					} // endforeach ( $type_parent as $parent )

			} // endif ( !empty( array_filter($type_parent) ) )

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}