<?php

// BioChemEntity

	/*
	 * Thing > BioChemEntity
	 * 
	 * 
	 */

	function uamswp_fad_schema_biochementity(
		$schema, // array // Main schema array
		// BioChemEntity
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

			// Properties from BioChemEntity (Thing > BioChemEntity)

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

			// Properties from BioChemEntity

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

	// ChemicalSubstance

		/*
		 * Thing > BioChemEntity > ChemicalSubstance
		 * 
		 * 
		 */

		function uamswp_fad_schema_chemicalsubstance(
			
		) {
			
		}

	// Gene

		/*
		 * Thing > BioChemEntity > Gene
		 * 
		 * 
		 */

		function uamswp_fad_schema_gene(
			
		) {
			
		}

	// MolecularEntity

		/*
		 * Thing > BioChemEntity > MolecularEntity
		 * 
		 * 
		 */

		function uamswp_fad_schema_molecularentity(
			
		) {
			
		}

	// Protein

		/*
		 * Thing > BioChemEntity > Protein
		 * 
		 * 
		 */

		function uamswp_fad_schema_protein(
			
		) {
			
		}