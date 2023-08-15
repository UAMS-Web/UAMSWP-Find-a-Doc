<?php

// OccupationalTherapy

	/*
	 * Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy > OccupationalTherapy
	 * 
	 * A treatment of people with physical, emotional, or social problems, using 
	 * purposeful activity to help them overcome or learn to deal with their problems.
	 */

	function uamswp_fad_schema_occupationaltherapy(
		$schema, // array // Main schema array
		// OccupationalTherapy (no property vars)
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

			// Inherited properties from MedicalTherapy (Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy)

				$contraindication = ( isset($contraindication) && !empty($contraindication) ) ? $contraindication : '';
				$duplicateTherapy = ( isset($duplicateTherapy) && !empty($duplicateTherapy) ) ? $duplicateTherapy : '';
				$seriousAdverseOutcome = ( isset($seriousAdverseOutcome) && !empty($seriousAdverseOutcome) ) ? $seriousAdverseOutcome : '';

			// Properties from OccupationalTherapy (Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy > OccupationalTherapy)

				// Do nothing (no property vars)

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_medicaltherapy(
					$schema, // array // Main schema array
					// MedicalTherapy
						$contraindication, // contraindication
						$duplicateTherapy, // duplicateTherapy
						$seriousAdverseOutcome, // seriousAdverseOutcome
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

			// Properties from OccupationalTherapy (Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy > OccupationalTherapy)

				// Do nothing (no property vars)

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}