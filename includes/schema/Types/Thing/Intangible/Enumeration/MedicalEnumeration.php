<?php

// MedicalEnumeration

	/*
	 * Thing > Intangible > Enumeration > MedicalEnumeration
	 * 
	 * Enumerations related to health and the practice of medicine: A concept that is 
	 * used to attribute a quality to another concept, as a qualifier, a collection of 
	 * items or a listing of all of the elements of a set in medicine practice.
	 */

	function uamswp_fad_schema_medicalenumeration(
		$schema, // array // Main schema array
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

			// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)

				// Do nothing (no property vars)

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_enumeration(
					$schema, // array // Main schema array
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

			// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)

				// Do nothing (no property vars)

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// DrugCostCategory
	include_once __DIR__ . '/MedicalEnumeration/DrugCostCategory.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > DrugCostCategory
		 * 
		 * 
		 */

		function uamswp_fad_schema_drugcostcategory(
			$schema, // array // Main schema array
			// DrugCostCategory (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from DrugCostCategory (Thing > Intangible > Enumeration > MedicalEnumeration > DrugCostCategory)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from DrugCostCategory (Thing > Intangible > Enumeration > MedicalEnumeration > DrugCostCategory)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// ReimbursementCap

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > ReimbursementCap
			 * 
			 * 
			 */

			function uamswp_fad_schema_reimbursementcap(
				
			) {
				
			}

		// Retail

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Retail
			 * 
			 * 
			 */

			function uamswp_fad_schema_retail(
				
			) {
				
			}

		// Wholesale

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Wholesale
			 * 
			 * 
			 */

			function uamswp_fad_schema_wholesale(
				
			) {
				
			}

	// DrugPregnancyCategory
	include_once __DIR__ . '/MedicalEnumeration/DrugPregnancyCategory.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > DrugPregnancyCategory
		 * 
		 * 
		 */

		function uamswp_fad_schema_drugpregnancycategory(
			$schema, // array // Main schema array
			// DrugPregnancyCategory (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from DrugPregnancyCategory (Thing > Intangible > Enumeration > MedicalEnumeration > DrugPregnancyCategory)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from DrugPregnancyCategory (Thing > Intangible > Enumeration > MedicalEnumeration > DrugPregnancyCategory)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// FDAcategoryA

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAcategoryA
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdacategorya(
				
			) {
				
			}

		// FDAcategoryB

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAcategoryB
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdacategoryb(
				
			) {
				
			}

		// FDAcategoryC

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAcategoryC
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdacategoryc(
				
			) {
				
			}

		// FDAcategoryD

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAcategoryD
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdacategoryd(
				
			) {
				
			}

		// FDAcategoryX

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAcategoryX
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdacategoryx(
				
			) {
				
			}

		// FDAnotEvaluated

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > FDAnotEvaluated
			 * 
			 * 
			 */

			function uamswp_fad_schema_fdanotevaluated(
				
			) {
				
			}

	// DrugPrescriptionStatus
	include_once __DIR__ . '/MedicalEnumeration/DrugPrescriptionStatus.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > DrugPrescriptionStatus
		 * 
		 * 
		 */

		function uamswp_fad_schema_drugprescriptionstatus(
			$schema, // array // Main schema array
			// DrugPrescriptionStatus (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from DrugPrescriptionStatus (Thing > Intangible > Enumeration > MedicalEnumeration > DrugPrescriptionStatus)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from DrugPrescriptionStatus (Thing > Intangible > Enumeration > MedicalEnumeration > DrugPrescriptionStatus)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// OTC

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > OTC
			 * 
			 * 
			 */

			function uamswp_fad_schema_otc(
				
			) {
				
			}

		// PrescriptionOnly

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > PrescriptionOnly
			 * 
			 * 
			 */

			function uamswp_fad_schema_prescriptiononly(
				
			) {
				
			}

	// InfectiousAgentClass
	include_once __DIR__ . '/MedicalEnumeration/InfectiousAgentClass.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > InfectiousAgentClass
		 * 
		 * 
		 */

		function uamswp_fad_schema_infectiousagentclass(
			$schema, // array // Main schema array
			// InfectiousAgentClass (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from InfectiousAgentClass (Thing > Intangible > Enumeration > MedicalEnumeration > InfectiousAgentClass)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from InfectiousAgentClass (Thing > Intangible > Enumeration > MedicalEnumeration > InfectiousAgentClass)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// Bacteria

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Bacteria
			 * 
			 * 
			 */

			function uamswp_fad_schema_bacteria(
				
			) {
				
			}

		// Fungus

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Fungus
			 * 
			 * 
			 */

			function uamswp_fad_schema_fungus(
				
			) {
				
			}

		// MulticellularParasite

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > MulticellularParasite
			 * 
			 * 
			 */

			function uamswp_fad_schema_multicellularparasite(
				
			) {
				
			}

		// Prion

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Prion
			 * 
			 * 
			 */

			function uamswp_fad_schema_prion(
				
			) {
				
			}

		// Protozoa

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Protozoa
			 * 
			 * 
			 */

			function uamswp_fad_schema_protozoa(
				
			) {
				
			}

		// Virus

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Virus
			 * 
			 * 
			 */

			function uamswp_fad_schema_virus(
				
			) {
				
			}

	// MedicalAudienceType
	include_once __DIR__ . '/MedicalEnumeration/MedicalAudienceType.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalAudienceType
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalaudiencetype(
			$schema, // array // Main schema array
			// MedicalAudienceType (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalAudienceType (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalAudienceType)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from MedicalAudienceType (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalAudienceType)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// Clinician

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Clinician
			 * 
			 * 
			 */

			function uamswp_fad_schema_clinician(
				
			) {
				
			}

		// MedicalResearcher

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > MedicalResearcher
			 * 
			 * 
			 */

			function uamswp_fad_schema_medicalresearcher(
				
			) {
				
			}

	// MedicalDevicePurpose
	include_once __DIR__ . '/MedicalEnumeration/MedicalDevicePurpose.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalDevicePurpose
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicaldevicepurpose(
			$schema, // array // Main schema array
			// MedicalDevicePurpose (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalDevicePurpose (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalDevicePurpose)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from MedicalDevicePurpose (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalDevicePurpose)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// Diagnostic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Diagnostic
			 * 
			 * 
			 */

			function uamswp_fad_schema_diagnostic(
				
			) {
				
			}

		// Therapeutic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Therapeutic
			 * 
			 * 
			 */

			function uamswp_fad_schema_therapeutic(
				
			) {
				
			}

	// MedicalEvidenceLevel
	include_once __DIR__ . '/MedicalEnumeration/MedicalEvidenceLevel.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalEvidenceLevel
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalevidencelevel(
			$schema, // array // Main schema array
			// MedicalEvidenceLevel (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalEvidenceLevel (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalEvidenceLevel)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from MedicalEvidenceLevel (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalEvidenceLevel)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// EvidenceLevelA

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > EvidenceLevelA
			 * 
			 * 
			 */

			function uamswp_fad_schema_evidencelevela(
				
			) {
				
			}

		// EvidenceLevelB

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > EvidenceLevelB
			 * 
			 * 
			 */

			function uamswp_fad_schema_evidencelevelb(
				
			) {
				
			}

		// EvidenceLevelC

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > EvidenceLevelC
			 * 
			 * 
			 */

			function uamswp_fad_schema_evidencelevelc(
				
			) {
				
			}

	// MedicalImagingTechnique
	include_once __DIR__ . '/MedicalEnumeration/MedicalImagingTechnique.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalImagingTechnique
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalimagingtechnique(
			$schema, // array // Main schema array
			// MedicalImagingTechnique (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalImagingTechnique (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalImagingTechnique)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from MedicalImagingTechnique (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalImagingTechnique)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// CT

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > CT
			 * 
			 * 
			 */

			function uamswp_fad_schema_ct(
				
			) {
				
			}

		// MRI

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > MRI
			 * 
			 * 
			 */

			function uamswp_fad_schema_mri(
				
			) {
				
			}

		// PET

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > PET
			 * 
			 * 
			 */

			function uamswp_fad_schema_pet(
				
			) {
				
			}

		// Radiography

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Radiography
			 * 
			 * 
			 */

			function uamswp_fad_schema_radiography(
				
			) {
				
			}

		// Ultrasound

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Ultrasound
			 * 
			 * 
			 */

			function uamswp_fad_schema_ultrasound(
				
			) {
				
			}

		// XRay

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > XRay
			 * 
			 * 
			 */

			function uamswp_fad_schema_xray(
				
			) {
				
			}

	// MedicalObservationalStudyDesign
	include_once __DIR__ . '/MedicalEnumeration/MedicalObservationalStudyDesign.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalObservationalStudyDesign
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalobservationalstudydesign(
			$schema, // array // Main schema array
			// MedicalObservationalStudyDesign (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalObservationalStudyDesign (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalObservationalStudyDesign)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from MedicalObservationalStudyDesign (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalObservationalStudyDesign)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// CaseSeries

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > CaseSeries
			 * 
			 * 
			 */

			function uamswp_fad_schema_caseseries(
				
			) {
				
			}

		// CohortStudy

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > CohortStudy
			 * 
			 * 
			 */

			function uamswp_fad_schema_cohortstudy(
				
			) {
				
			}

		// CrossSectional

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > CrossSectional
			 * 
			 * 
			 */

			function uamswp_fad_schema_crosssectional(
				
			) {
				
			}

		// Longitudinal

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Longitudinal
			 * 
			 * 
			 */

			function uamswp_fad_schema_longitudinal(
				
			) {
				
			}

		// Observational

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Observational
			 * 
			 * 
			 */

			function uamswp_fad_schema_observational(
				
			) {
				
			}

		// Registry

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Registry
			 * 
			 * 
			 */

			function uamswp_fad_schema_registry(
				
			) {
				
			}

	// MedicalProcedureType
	include_once __DIR__ . '/MedicalEnumeration/MedicalProcedureType.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalProcedureType
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalproceduretype(
			$schema, // array // Main schema array
			// MedicalProcedureType (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalProcedureType (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalProcedureType)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from MedicalProcedureType (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalProcedureType)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// NoninvasiveProcedure

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > NoninvasiveProcedure
			 * 
			 * 
			 */

			function uamswp_fad_schema_noninvasiveprocedure(
				
			) {
				
			}

		// PercutaneousProcedure

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > PercutaneousProcedure
			 * 
			 * 
			 */

			function uamswp_fad_schema_percutaneousprocedure(
				
			) {
				
			}

	// MedicalSpecialty
	include_once __DIR__ . '/MedicalEnumeration/MedicalSpecialty.php';

	// MedicalStudyStatus
	include_once __DIR__ . '/MedicalEnumeration/MedicalStudyStatus.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalStudyStatus
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicalstudystatus(
			$schema, // array // Main schema array
			// MedicalStudyStatus (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalStudyStatus (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalStudyStatus)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from MedicalStudyStatus (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalStudyStatus)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// ActiveNotRecruiting

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > ActiveNotRecruiting
			 * 
			 * 
			 */

			function uamswp_fad_schema_activenotrecruiting(
				
			) {
				
			}

		// Completed

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Completed
			 * 
			 * 
			 */

			function uamswp_fad_schema_completed(
				
			) {
				
			}

		// EnrollingByInvitation

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > EnrollingByInvitation
			 * 
			 * 
			 */

			function uamswp_fad_schema_enrollingbyinvitation(
				
			) {
				
			}

		// NotYetRecruiting

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > NotYetRecruiting
			 * 
			 * 
			 */

			function uamswp_fad_schema_notyetrecruiting(
				
			) {
				
			}

		// Recruiting

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Recruiting
			 * 
			 * 
			 */

			function uamswp_fad_schema_recruiting(
				
			) {
				
			}

		// ResultsAvailable

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > ResultsAvailable
			 * 
			 * 
			 */

			function uamswp_fad_schema_resultsavailable(
				
			) {
				
			}

		// ResultsNotAvailable

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > ResultsNotAvailable
			 * 
			 * 
			 */

			function uamswp_fad_schema_resultsnotavailable(
				
			) {
				
			}

		// Suspended

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Suspended
			 * 
			 * 
			 */

			function uamswp_fad_schema_suspended(
				
			) {
				
			}

		// Terminated

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Terminated
			 * 
			 * 
			 */

			function uamswp_fad_schema_terminated(
				
			) {
				
			}

		// Withdrawn

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Withdrawn
			 * 
			 * 
			 */

			function uamswp_fad_schema_withdrawn(
				
			) {
				
			}

	// MedicalTrialDesign
	include_once __DIR__ . '/MedicalEnumeration/MedicalTrialDesign.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalTrialDesign
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicaltrialdesign(
			$schema, // array // Main schema array
			// MedicalTrialDesign (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicalTrialDesign (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalTrialDesign)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from MedicalTrialDesign (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalTrialDesign)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// DoubleBlindedTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > DoubleBlindedTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_doubleblindedtrial(
				
			) {
				
			}

		// InternationalTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > InternationalTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_internationaltrial(
				
			) {
				
			}

		// MultiCenterTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > MultiCenterTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_multicentertrial(
				
			) {
				
			}

		// OpenTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > OpenTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_opentrial(
				
			) {
				
			}

		// PlaceboControlledTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > PlaceboControlledTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_placebocontrolledtrial(
				
			) {
				
			}

		// RandomizedTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > RandomizedTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_randomizedtrial(
				
			) {
				
			}

		// SingleBlindedTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > SingleBlindedTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_singleblindedtrial(
				
			) {
				
			}

		// SingleCenterTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > SingleCenterTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_singlecentertrial(
				
			) {
				
			}

		// TripleBlindedTrial

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > TripleBlindedTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_tripleblindedtrial(
				
			) {
				
			}

	// MedicineSystem
	include_once __DIR__ . '/MedicalEnumeration/MedicineSystem.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicineSystem
		 * 
		 * 
		 */

		function uamswp_fad_schema_medicinesystem(
			$schema, // array // Main schema array
			// MedicineSystem (no property vars)
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
	
				// Properties from MedicalEnumeration (Thing > Intangible > Enumeration > MedicalEnumeration)
	
					// Do nothing (no property vars)
	
				// Properties from MedicineSystem (Thing > Intangible > Enumeration > MedicalEnumeration > MedicineSystem)
	
					// Do nothing (no property vars)
	
			// Add values to the schema array
	
				// Inherited properties
	
					$schema = uamswp_fad_schema_medicalenumeration(
						$schema, // array // Main schema array
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
	
				// Properties from MedicineSystem (Thing > Intangible > Enumeration > MedicalEnumeration > MedicineSystem)
	
					// Do nothing (no property vars)
	
			// Remove any empty values from the schema array
	
				$schema = array_filter($schema);
	
			return $schema;
	
		}

		// Ayurvedic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Ayurvedic
			 * 
			 * 
			 */

			function uamswp_fad_schema_ayurvedic(
				
			) {
				
			}

		// Chiropractic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Chiropractic
			 * 
			 * 
			 */

			function uamswp_fad_schema_chiropractic(
				
			) {
				
			}

		// Homeopathic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Homeopathic
			 * 
			 * 
			 */

			function uamswp_fad_schema_homeopathic(
				
			) {
				
			}

		// Osteopathic

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > Osteopathic
			 * 
			 * 
			 */

			function uamswp_fad_schema_osteopathic(
				
			) {
				
			}

		// TraditionalChinese

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > TraditionalChinese
			 * 
			 * 
			 */

			function uamswp_fad_schema_traditionalchinese(
				
			) {
				
			}

		// WesternConventional

			/*
			 * Thing > Intangible > Enumeration > MedicalEnumeration > quux > WesternConventional
			 * 
			 * 
			 */

			function uamswp_fad_schema_westernconventional(
				
			) {
				
			}

	// PhysicalExam
	include_once __DIR__ . '/MedicalEnumeration/PhysicalExam.php';