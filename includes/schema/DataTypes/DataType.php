<?php

// DataType

	/*
	 * 
	 */

	function uamswp_fad_schema_datatype(
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

	// Format Values for DataType

		function uamswp_fad_schema_datatype_format(
			$type, // string
			$value // string
		) {

			$datatype_map = array(
				'Time'				=> $value,
				'Number'			=> $value,
				'Float'				=> $value,
				'Integer'			=> $value,
				'Text'				=> uamswp_attr_conversion($value),
				'CssSelectorType'	=> $value,
				'PronounceableText'	=> uamswp_attr_conversion($value),
				'URL'				=> user_trailingslashit($value),
				'XPathType'			=> $value,
				'Date'				=> $value,
				'Boolean'			=> $value,
				'True'				=> $value,
				'False'				=> $value,
				'DateTime'			=> $value
			);

			if ( is_array($value) ) {

				foreach ( $value as $item ) {

					$item = $datatype_map[$type] ?: $item;

				}

			} else {

				$value = $datatype_map[$type] ?: $value;

			}

			return $value;

		}


	// Boolean
	include_once __DIR__ . '/DataType/Boolean.php';

	// Date
	include_once __DIR__ . '/DataType/Date.php';

	// DateTime
	include_once __DIR__ . '/DataType/DateTime.php';

	// Number
	include_once __DIR__ . '/DataType/Number.php';

	// Text
	include_once __DIR__ . '/DataType/Text.php';

	// Time
	include_once __DIR__ . '/DataType/Time.php';