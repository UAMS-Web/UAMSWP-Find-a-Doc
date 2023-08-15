<?php

// DataType

	/*
	 * 
	 */

	function uamswp_fad_schema_datatype(
	
	) {
		
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