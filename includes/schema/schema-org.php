<?php

/*
 * Schema.org Types and Properties
 */

function uamswp_fad_schema_org (
	array $schema, // Main schema array
	array $input, // Array of properties and values for a type and its parent types
	string $type // Name of Schema.org type
) {

	// Check/define variables

		// Main schema array

			$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

		// Full list of types and properties from Schema.org

			include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/schema-org.php' );

		// Get node of the Schema.org types array for the indicated type

			$type = $schema_org_types[$type];

		// // Merge relevant nodes of the Schema.org properties array into the schema type array
		//
		// 	foreach ( $schema_type['properties'] as $key => $value ) {
		//
		// 		$schema_type['properties'][$value] = $schema_org_properties[$value];
		// 		unset($schema_type['properties'][$key]);
		//
		// 	}

		// Immediate parent(s) of this type

			$type_parent = $schema_type['subTypeOf'];

		// Immediate parent(s) of this type

			$type_properties = $schema_type['properties'];

	// Construct schema array

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

		// Add values to the schema block array for the properties of the indicated type

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

