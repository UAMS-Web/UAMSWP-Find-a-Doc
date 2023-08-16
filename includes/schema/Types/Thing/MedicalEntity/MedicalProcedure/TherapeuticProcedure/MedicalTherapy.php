<?php

// MedicalTherapy

	/*
	 * Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy
	 * 
	 * 
	 */

	function uamswp_fad_schema_medicaltherapy(
		$schema, // array // Main schema array
		// MedicalTherapy
			$contraindication = '', // contraindication
			$duplicateTherapy = '', // duplicateTherapy
			$seriousAdverseOutcome = '', // seriousAdverseOutcome
		// TherapeuticProcedure
			$adverseOutcome = '', // adverseOutcome
			$doseSchedule = '', // doseSchedule
			$drug = '', // drug
		// MedicalProcedure
			$bodyLocation = '', // bodyLocation
			$followup = '', // followup
			$howPerformed = '', // howPerformed
			$preparation = '', // preparation
			$procedureType = '', // procedureType
			$status = '', // status
		// MedicalEntity
			$code = '', // code
			$funding = '', // funding
			$guideline = '', // guideline
			$legalStatus = '', // legalStatus
			$medicineSystem = '', // medicineSystem
			$recognizingAuthority = '', // recognizingAuthority
			$relevantSpecialty = '', // relevantSpecialty
			$study = '', // study
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

			// Inherited properties from MedicalEntity (Thing > MedicalEntity)

				$code = ( isset($code) && !empty($code) ) ? $code : '';
				$funding = ( isset($funding) && !empty($funding) ) ? $funding : '';
				$guideline = ( isset($guideline) && !empty($guideline) ) ? $guideline : '';
				$legalStatus = ( isset($legalStatus) && !empty($legalStatus) ) ? $legalStatus : '';
				$medicineSystem = ( isset($medicineSystem) && !empty($medicineSystem) ) ? $medicineSystem : '';
				$recognizingAuthority = ( isset($recognizingAuthority) && !empty($recognizingAuthority) ) ? $recognizingAuthority : '';
				$relevantSpecialty = ( isset($relevantSpecialty) && !empty($relevantSpecialty) ) ? $relevantSpecialty : '';
				$study = ( isset($study) && !empty($study) ) ? $study : '';

			// Inherited properties from MedicalProcedure (Thing > MedicalEntity > MedicalProcedure)

				$bodyLocation = ( isset($bodyLocation) && !empty($bodyLocation) ) ? $bodyLocation : '';
				$followup = ( isset($followup) && !empty($followup) ) ? $followup : '';
				$howPerformed = ( isset($howPerformed) && !empty($howPerformed) ) ? $howPerformed : '';
				$preparation = ( isset($preparation) && !empty($preparation) ) ? $preparation : '';
				$procedureType = ( isset($procedureType) && !empty($procedureType) ) ? $procedureType : '';
				$status = ( isset($status) && !empty($status) ) ? $status : '';

			// Inherited properties from TherapeuticProcedure (Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure)

				$adverseOutcome = ( isset($adverseOutcome) && !empty($adverseOutcome) ) ? $adverseOutcome : '';
				$doseSchedule = ( isset($doseSchedule) && !empty($doseSchedule) ) ? $doseSchedule : '';
				$drug = ( isset($drug) && !empty($drug) ) ? $drug : '';

			// Properties from MedicalTherapy (Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy)

				$contraindication = ( isset($contraindication) && !empty($contraindication) ) ? $contraindication : '';
				$duplicateTherapy = ( isset($duplicateTherapy) && !empty($duplicateTherapy) ) ? $duplicateTherapy : '';
				$seriousAdverseOutcome = ( isset($seriousAdverseOutcome) && !empty($seriousAdverseOutcome) ) ? $seriousAdverseOutcome : '';

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_therapeuticprocedure(
					$schema, // array // Main schema array
					// TherapeuticProcedure
						$adverseOutcome, // adverseOutcome
						$doseSchedule, // doseSchedule
						$drug, // drug
					// MedicalProcedure
						$bodyLocation, // bodyLocation
						$followup, // followup
						$howPerformed, // howPerformed
						$preparation, // preparation
						$procedureType, // procedureType
						$status, // status
					// MedicalEntity
						$code, // code
						$funding, // funding
						$guideline, // guideline
						$legalStatus, // legalStatus
						$medicineSystem, // medicineSystem
						$recognizingAuthority, // recognizingAuthority
						$relevantSpecialty, // relevantSpecialty
						$study, // study
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

			// Properties from MedicalTherapy (Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy)

				// contraindication

					/* 
					 * Expected Type:
					 *     Thing > MedicalEntity > MedicalContraindication
					 *     DataType > Text
					 * 
					 * A contraindication for this therapy.
					 */

					 $schema['contraindication'] = ( isset($contraindication) && !empty($contraindication) ) ? uamswp_fad_schema_type_selector($contraindication) : '';

				// duplicateTherapy

					/* 
					 * Expected Type:
					 *     Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy
					 * 
					 * A therapy that duplicates or overlaps this one.
					 */

					 $schema['duplicateTherapy'] = ( isset($duplicateTherapy) && !empty($duplicateTherapy) ) ? uamswp_fad_schema_type_selector($duplicateTherapy) : '';

				// seriousAdverseOutcome

					/* 
					 * Expected Type:
					 *     Thing > MedicalEntity
					 * 
					 * A possible serious complication and/or serious side effect of this therapy. 
					 * Serious adverse outcomes include those that are life-threatening; result in 
					 * death, disability, or permanent damage; require hospitalization or prolong 
					 * existing hospitalization; cause congenital anomalies or birth defects; or 
					 * jeopardize the patient and may require medical or surgical intervention to 
					 * prevent one of the outcomes in this definition.
					 */

					 $schema['seriousAdverseOutcome'] = ( isset($seriousAdverseOutcome) && !empty($seriousAdverseOutcome) ) ? uamswp_fad_schema_type_selector($seriousAdverseOutcome) : '';

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}

	// OccupationalTherapy
	include_once __DIR__ . '/MedicalTherapy/OccupationalTherapy.php';

	// PalliativeProcedure
	include_once __DIR__ . '/MedicalTherapy/PalliativeProcedure.php';

	// PhysicalTherapy
	include_once __DIR__ . '/MedicalTherapy/PhysicalTherapy.php';

	// RadiationTherapy
	include_once __DIR__ . '/MedicalTherapy/RadiationTherapy.php';

	// RespiratoryTherapy
	include_once __DIR__ . '/MedicalTherapy/RespiratoryTherapy.php';