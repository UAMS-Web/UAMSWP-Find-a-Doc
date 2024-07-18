<?php

/**
 * Functions for Schema.org Schema and Google Local Business structured data
 *
 * Utility functions
 */

// Define @id references to each top-level node in an array

	function uamswp_fad_schema_node_references(
		array $input
	) {

		// Check or define variables

			$input = array_is_list($input) ? $input : array($input);

		// Base array

			$output = array();

		if ( $input ) {

			foreach ( $input as $item ) {

				if ( $item ) {

					// Define reference to each value/row in this property

						if (
							isset($item['@id'])
							&&
							!empty($item['@id'])
						) {

							// If @id key exists, add only that key/value pair

								$output[]['@id'] = $item['@id'];

						}

				}

			}

		}

		// Clean up array

			$output = array_filter($output);
			$output = array_unique( $output, SORT_REGULAR );
			$output = array_values($output);

			// If there is only one item, and that item is not '@id' and its value, flatten the multi-dimensional array by one step

				if ( !isset($output['@id']) ) {

					uamswp_fad_flatten_multidimensional_array($output);

				}

		return $output;

	}

// Determine whether to define schema property with full values or with @id reference

	function uamswp_fad_schema_values_or_reference(
		&$property, // Property variable
		$value, // Full value variable
		array &$node_identifier_list // array // Required // List of node identifiers (@id) already defined in the schema
	) {

		if (
			isset($value['@id'])
			&&
			(
				in_array(
					$value['@id'],
					$node_identifier_list
				)
				||
				in_array(
					array( '@id' => $value['@id'] ),
					$node_identifier_list
				)
			)
		) {

			/*

				If the value has a node identifier ...
				and if that node identifier is in the list of existing node identifiers ...
				then merge an array containing that node identifier into the property array

			*/

			if ( $property ) {

				$property = is_array($property) ? $property : array($property);
				$property = array_is_list($property) ? $property : array($property);

				$property[] = array(
					'@id' => $value['@id']
				);

			} else {

				$property = array(
					'@id' => $value['@id']
				);

			}

			// Clean up the property value array

				if (
					$property
					&&
					is_array($property)
				) {

					$property = array_filter($property);

					if ( array_is_list($property) ) {

						$property = array_unique( $property, SORT_REGULAR );
						$property = array_values($property);
						uamswp_fad_flatten_multidimensional_array($property);

					} else {

						if ( !isset($property['@id']) ) {

							uamswp_fad_flatten_multidimensional_array($property);

						}

					}

				}

		} else {

			if (
				isset($property)
				&&
				!empty($property)
			) {

				/*

					If a node identifier reference variable with a value does not exist ...
					and if the property variable already has a value ...
					then merge the new full value into the property array

				*/

				$property = array_merge(
					( ( is_array($property) && array_is_list($property) ) ? $property : array($property) ),
					( ( is_array($value) && array_is_list($value) ) ? $value : array($value) )
				);

				if ( $property ) {

					$property = array_filter($property);
					$property = array_unique( $property, SORT_REGULAR );
					$property = array_values($property);

					if ( !isset($property['@id']) ) {

						uamswp_fad_flatten_multidimensional_array($property);

					}

				}

			} else {

				/*

					If a node identifier reference variable with a value does not exist ...
					and if the property variable does not already have a value ...
					then set the property value using the new full value

				*/

				$property = $value;

			}

			// Add node identifiers (@id) to the list of node identifiers already defined in the schema

				// Get node identifiers from value

					$value_node_identifiers = uamswp_fad_schema_node_references(is_array($value) ? $value : array($value));

				// Make sure node identifiers arrays are list arrays

					$value_node_identifiers = array_is_list($value_node_identifiers) ? $value_node_identifiers : array($value_node_identifiers);
					$node_identifier_list = array_is_list($node_identifier_list) ? $node_identifier_list : array($node_identifier_list);

				// Merge value node identifiers into the list of node identifiers already defined in the schema

					$node_identifier_list = array_merge(
						$node_identifier_list,
						$value_node_identifiers
					);

				// De-duplicate the list of node identifiers already defined in the schema

					if (
						$node_identifier_list
						&&
						is_array($node_identifier_list)
						&&
						array_is_list($node_identifier_list)
					) {

						$node_identifier_list = array_unique( $node_identifier_list, SORT_REGULAR );

					}

		}

	}

// Add values to schema item property

	function uamswp_fad_schema_add_to_item_values(
		$schema_type, // string // Required // The @type value for the schema item
		&$schema_type_list, // array // Required // The list array for the schema item to which to add the property value
		string $property_name, // string // Required // Name of schema property
		$property_value, // mixed // Required // Variable to add as the property value
		array &$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
		array $property_map, // array // Required // Map array to match schema types with allowed properties
		int $nesting_level, // int // Required // Current nesting level value
		int $nesting_level_max = -1, // int // Optional // Max nesting level at which to add the property value // Default: -1 (no limit)
		string $nesting_level_operator = '==', // string // Optional // Operator used to compare nesting level with max nesting level. The possible operators are: <, lt, <=, le, >, gt, >=, ge, ==, =, eq, !=, <>, ne respectively. // Default: ==
		string $property_map_key = 'properties', // string // Optional // Key in the property map containing the list of allowed properties as its value // Default: 'properties'
		bool $property_value_overwrite = true // bool // Optional // Query for whether to overwite any existing property value of the list array with the incoming property value // Default: true
	) {

		if (
			!$schema_type
			||
			!is_string($schema_type)
			||
			!$schema_type_list
			||
			!is_array($schema_type_list)
			||
			!$property_name
			||
			(
				!$property_value
				||
				!isset($property_value)
			)
			||
			!$property_map
			||
			!$property_map_key
			||
			(
				!$property_map[$schema_type]
				||
				!isset($property_map[$schema_type])
			)
			||
			(
				!$property_map[$schema_type][$property_map_key]
				||
				!isset($property_map[$schema_type][$property_map_key])
			)
		) {

			/*

				If any of the important values are empty, not set, false, etc. ...
				then stop the function here

			*/

			return;

		}

		if (
			$nesting_level_max == -1
			||
			version_compare( $nesting_level, $nesting_level_max, $nesting_level_operator )
		) {

			if (
				in_array(
					$property_name,
					$property_map[$schema_type][$property_map_key]
				)
				&&
				$property_value
			) {

				// Check array for existing node identifiers

					/**
					 * If there is no nesting level limit or if the current nesting level is at/under the limit ...
					 * and if the specific schema property is a valid property for the schema type ...
					 * and if the property value exists ...
					 */

					if (
						is_array($property_value)
						&&
						array_is_list($property_value)
					) {

						/**
						 * ... and if the property value is a list array ...
						 * then loop through the rows in that array.
						 *
						 * Check the current row for if its node identifier is already on the list of node identifiers.
						 * If so, replace the full value of that row with only the node identifier.
						 */

						$property_value_temp = array();

						foreach ( $property_value as &$item ) {

							uamswp_fad_schema_values_or_reference(
								$property_value_temp, // Property variable
								$item, // Full value variable
								$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
							);

						}

						$property_value = $property_value_temp;

					} elseif ( is_array($property_value) ) {

						/**
						 * ... and if the property value is an associative array.
						 *
						 * Check the array for if its node identifier is already on the list of node identifiers.
						 * If so, replace the full value with only the node identifier.
						 */

						$property_value_temp = array();

						uamswp_fad_schema_values_or_reference(
							$property_value_temp, // Property variable
							$property_value, // Full value variable
							$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
						);

						$property_value = $property_value_temp;

					}

				// Add the value to the property

					if (
						isset($schema_type_list[$property_name])
						&&
						!empty($schema_type_list[$property_name])
						&&
						!$property_value_overwrite
					) {

						/**
						 * If the row value for the property already exists
						 * and the row value for the property is not empty
						 * and existing the row value for the property should not be overwritten with the incoming property value
						 */

						if (
							(
								is_array($schema_type_list[$property_name])
								&&
								!array_is_list($schema_type_list[$property_name])
							)
							||
							(
								!is_array($schema_type_list[$property_name])
							)
						) {

							/**
							 * If the row value for the property is an associative array...
							 *
							 * Or if the row value for the property is not an array...
							 */

							// Nest the row value for the property in an array to make the row value a list array

								$schema_type_list[$property_name] = array($schema_type_list[$property_name]);

						}

						// Add the property value to the row value list array for the property

							$schema_type_list[$property_name][] = $property_value;

					} else {

						$schema_type_list[$property_name] = $property_value;

					}

			}

		}

		// Clean up property value array

			if (
				isset($schema_type_list[$property_name])
				&&
				!empty($schema_type_list[$property_name])
				&&
				is_array($schema_type_list[$property_name])
			) {

				$schema_type_list[$property_name] = array_filter($schema_type_list[$property_name]);

				if ( array_is_list($schema_type_list[$property_name]) ) {

					$schema_type_list[$property_name] = array_unique( $schema_type_list[$property_name], SORT_REGULAR );
					$schema_type_list[$property_name] = array_values($schema_type_list[$property_name]);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($schema_type_list[$property_name]);

				}

			}

	}

// Merge multiple schema item property value arrays

	function uamswp_fad_schema_merge_values(
		&$base_value, // mixed // Required // Initial schema item property value
		$incoming_value // mixed // Required // Incoming schema item property value
	) {

		// Check / define variables

			$base_value = $base_value ?? array();
			$incoming_value = $incoming_value ?? array();

		// Merge the arrays

			// Base value is empty

				if (
					!$base_value
					&&
					$incoming_value
				) {

					$base_value = $incoming_value;

				}

			// Base value exists

				if (
					$base_value
					&&
					$incoming_value
				) {

					$base_value = is_array($base_value) ? $base_value : array($base_value);
					$incoming_value = is_array($incoming_value) ? $incoming_value : array($incoming_value);

					$base_value = array_is_list($base_value) ? $base_value : array($base_value);
					$incoming_value = array_is_list($incoming_value) ? $incoming_value : array($incoming_value);

					$base_value = array_merge(
						$base_value,
						$incoming_value,
					);

				}

		// Clean up array

			if (
				$base_value
				&&
				is_array($base_value)
			) {

				$base_value = array_filter($base_value);

				if ( array_is_list($base_value) ) {

					$base_value = array_unique( $base_value, SORT_REGULAR );
					$base_value = array_values($base_value);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($base_value);

				}

			}

		return $base_value;

	}

// Create list of specific values from property value items

	function uamswp_fad_schema_property_values(
		array $input, // array // Required // Property values from which to extract specific values
		$properties, // mixed // Required // List of properties from which to collect values
		&$output = array() // mixed // Optional // Pre-existing list to which to add additional items
	) {

		// Check / define variables

			if ( !$input ) {

				// If $input is empty, stop here

					return $output;

			}

			$input = array_is_list($input) ? $input : array($input);
			$properties = is_array($properties) ? $properties : array($properties);
			$output = is_array($output) && array_is_list($output) ? $output : array($output);

		// Loop through input array and get the desired property values

			if ( $input ) {

				foreach ( $input as $item ) {

					foreach ( $properties as $property_key => $property_value ) {

						if ( is_array($property_value) ) {

							// If requested property is an array

								if ( isset($item[$property_key]) ) {

									$output = uamswp_fad_schema_property_values(
										$item[$property_key], // array // Required // Property values from which to extract specific values
										$property_value, // mixed // Required // List of properties from which to collect values
										$output // mixed // Optional // Pre-existing list to which to add additional items
									);

								}

						} else {

							// If requested property is a string

								if (
									isset($item[$property_value])
									&&
									!empty($item[$property_value])
								) {

									if ( is_array($item[$property_value]) ) {

										foreach ( $item[$property_value] as $array_item ) {

											// Check if value is longer than two characters

												if (
													$array_item
													&&
													!is_array($array_item)
													&&
													strlen($array_item) > 2
												) {

													$output = is_array($output) ? $output : array($output);
													$output[] = $array_item;

												}

										}

									} else {

										// Check if value is longer than two characters

											if ( strlen($item[$property_value]) > 2 ) {

												$output = is_array($output) ? $output : array($output);
												$output[] = $item[$property_value];

											}

									} // endif

								} // endif

						}

					} // endforeach ( $properties as $property_key => $property_value )

				} // endforeach ( $input as $item )

			} else {

				// If $input is empty, stop here

					return $output;

			} // endif ( $input )

		// Clean up the output array

			if ( $output ) {

				$output = array_filter($output);
				$output = array_unique( $output, SORT_REGULAR );
				$output = array_values($output);
				uamswp_fad_flatten_multidimensional_array($output);

				if (
					is_array($output)
					&&
					array_is_list($output)
				) {

					sort( $output, SORT_NATURAL | SORT_FLAG_CASE );

				} elseif ( is_array($output) ) {

					ksort( $output, SORT_NATURAL | SORT_FLAG_CASE );

				}

			}

		return $output;

	}

// Get a list of schema.org subtypes and URLs

	function uamswp_fad_schema_subtypes_and_urls(
		array &$types_no_subtypes, // array // Required // List of Schema.org types for which to not get the subtypes
		array &$types_for_subtypes = array(), // array // Optional // List of Schema.org types for which to get the subtypes
		&$urls = array() // string|array // Optional // Pre-existing list of schema.org URLs to which to add additional items
	) {

		// Check variables

			if (
				$urls
				&&
				!is_string($urls)
				&&
				!is_array($urls)
			) {

				$urls = array();

			}

			if (
				$urls
				&&
				is_string($urls)
			) {

				$urls = array( $urls );

			}

		// Get the subtypes

			if ( $types_for_subtypes ) {

				uamswp_fad_schema_subtypes(
					$types_for_subtypes // array // Required // List of Schema.org types for which to get the subtypes
				);

				// Merge the two lists of types

					$types_no_subtypes = array_merge(
						$types_no_subtypes,
						$types_for_subtypes
					);

			}

		// Handle the remainder of the types

			// Add schema.org URLs to list

				foreach ( $types_no_subtypes as $item ) {

					if ( $item ) {

						// Add schema.org URLs to list

							$urls[] = 'https://schema.org/' . $item;

					}
				}

		// Clean up the arrays

			$types_no_subtypes = $types_no_subtypes ? array_filter($types_no_subtypes) : array();
			$types_no_subtypes = $types_no_subtypes ? array_unique( $types_no_subtypes, SORT_REGULAR ) : array();
			$types_no_subtypes = $types_no_subtypes ? array_values($types_no_subtypes) : array();

			$urls = $urls ? array_filter($urls) : array();
			$urls = $urls ? array_unique( $urls, SORT_REGULAR ) : array();
			$urls = $urls ? array_values($urls) : array();

	}

// Get list of Schema.org subtypes

	function uamswp_fad_schema_subtypes(
		array &$types // array // Required // List of Schema.org types for which to get the subtypes
	) {

		// If the input is invalid, stop here

			if ( !$types ) {

				return;

			}

		// Schema.org types and properties

			include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/schema-org.php' );

		// Get the subtypes

			foreach ( $types as $item ) {

				if ( $item ) {

					// Get the subtypes of the current item

						$subtypes = $schema_org_types[$item]['subTypes'] ?? array();
						$subtypes = !empty($subtypes) ? $subtypes : array();

					if ( $subtypes ) {

						$subtypes = is_array($subtypes) ? $subtypes : array($subtypes);

						uamswp_fad_schema_subtypes($subtypes);

						$types = array_merge(
							$types,
							$subtypes
						);

					}

				}
			}

		// Clean up the array

			$types = $types ? array_filter($types) : array();
			$types = $types ? array_unique( $types, SORT_REGULAR ) : array();
			$types = $types ? array_values($types) : array();

	}

// Construct the schema script tag

	function uamswp_fad_schema_construct($input) {

		$schema_line_break = "\n"; // the double quotes are important

		// Construct schema JSON
		// $schema_block = uamswp_fad_schema_type_selector($input);

		// Open script tag
			echo '<script type="application/ld+json">';
			echo $schema_line_break;

		// Encode JSON

			echo json_encode($input, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

		// Close script tag

			echo $schema_line_break;
			echo '</script>';
			echo $schema_line_break;

		// Display array as development testing

			echo '<pre><code>'; // test

			echo json_encode($input, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT); // test

			echo '</code></pre>'; // test

		// Reusable test display lines

			// echo '<p>$foo = ' . ( is_array($foo) ? 'Array' : ( is_object($foo) ? 'Object' : ( is_null($foo) ? 'Null' : ( $foo ) ) ) ) . '</p>'; // test
			// if ( is_array($foo) || is_object($foo) ) { echo '<pre>'; print_r($foo); echo '</pre>'; } // test

	}