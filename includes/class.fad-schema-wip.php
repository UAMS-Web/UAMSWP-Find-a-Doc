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

// DataTypes
include_once __DIR__ . '/Schema/DataTypes.php';

// Types
include_once __DIR__ . '/Schema/Types.php';