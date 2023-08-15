<?php

// Product

	/*
	 * Thing > Product
	 * 
	 * 
	 */

	function uamswp_fad_schema_product(
		$schema, // array // Main schema array
		// Product
			$foo, // foo
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

			// Properties from Product (Thing > Product)

				$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_thing(
					$schema, // array // Main schema array
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

			// Properties from Product

				// foo

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					$schema['foo'] = $foo;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// DietarySupplement

		/*
		 * Thing > Product > DietarySupplement
		 * 
		 * 
		 */

		function uamswp_fad_schema_dietarysupplement(
			
		) {
			
		}

	// Drug

		/*
		 * Thing > Product > Drug
		 * 
		 * 
		 */

		function uamswp_fad_schema_drug(
			
		) {
			
		}

	// IndividualProduct

		/*
		 * Thing > Product > IndividualProduct
		 * 
		 * 
		 */

		function uamswp_fad_schema_individualproduct(
			
		) {
			
		}

	// ProductCollection

		/*
		 * Thing > Product > ProductCollection
		 * 
		 * 
		 */

		function uamswp_fad_schema_productcollection(
			
		) {
			
		}

	// ProductGroup

		/*
		 * Thing > Product > ProductGroup
		 * 
		 * 
		 */

		function uamswp_fad_schema_productgroup(
			
		) {
			
		}

	// ProductModel

		/*
		 * Thing > Product > ProductModel
		 * 
		 * 
		 */

		function uamswp_fad_schema_productmodel(
			
		) {
			
		}

	// SomeProducts

		/*
		 * Thing > Product > SomeProducts
		 * 
		 * 
		 */

		function uamswp_fad_schema_someproducts(
			
		) {
			
		}

	// Vehicle

		/*
		 * Thing > Product > Vehicle
		 * 
		 * 
		 */

		function uamswp_fad_schema_vehicle(
			
		) {
			
		}

		// BusOrCoach

			/*
			 * Thing > Product > Vehicle > BusOrCoach
			 * 
			 * 
			 */

			function uamswp_fad_schema_busorcoach(
				
			) {
				
			}

		// Car

			/*
			 * Thing > Product > Vehicle > Car
			 * 
			 * 
			 */

			function uamswp_fad_schema_car(
				
			) {
				
			}

		// Motorcycle

			/*
			 * Thing > Product > Vehicle > Motorcycle
			 * 
			 * 
			 */

			function uamswp_fad_schema_motorcycle(
				
			) {
				
			}

		// MotorizedBicycle

			/*
			 * Thing > Product > Vehicle > MotorizedBicycle
			 * 
			 * 
			 */

			function uamswp_fad_schema_motorizedbicycle(
				
			) {
				
			}