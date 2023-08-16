<?php

// Gastroenterologic

	/*
	 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty > Gastroenterologic
	 * 
	 * 
	 */

	function uamswp_fad_schema_gastroenterologic(
		$schema, // array // Main schema array
		// Gastroenterologic (no property vars)
		// MedicalSpecialty (no property vars)
		// MedicalEnumeration (no property vars)
		// Enumeration
			$supersededBy, // supersededBy
		// Intangible (no property vars)
		// Thing
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

			// Inherited properties from Intangible (Thing > Intangible)

				// Do nothing (no property vars)

			// Inherited properties from Enumeration (Thing > Intangible > Enumeration)

				$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';

			// Inherited properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)

				// Do nothing (no property vars)

			// Inherited properties from MedicalSpecialty (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty)

				// Do nothing (no property vars)

			// Properties from Gastroenterologic (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty > Gastroenterologic)

				// Do nothing (no property vars)

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_medicalspecialty(
					$schema, // array // Main schema array
					// MedicalSpecialty (no property vars)
					// MedicalEnumeration (no property vars)
					// Enumeration
						$supersededBy, // supersededBy
					// Intangible (no property vars)
					// Thing
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

			// Properties from Gastroenterologic (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty > Gastroenterologic)

				// Do nothing (no property vars)

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}