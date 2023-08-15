<?php

// TherapeuticProcedure

	/*
	 * Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure
	 * 
	 * A medical procedure intended primarily for therapeutic purposes, aimed at 
	 * improving a health condition.
	 */

	function uamswp_fad_schema_therapeuticprocedure(
		$schema, // array // Main schema array
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

			// Properties from TherapeuticProcedure (Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure)

				$adverseOutcome = ( isset($adverseOutcome) && !empty($adverseOutcome) ) ? $adverseOutcome : '';
				$doseSchedule = ( isset($doseSchedule) && !empty($doseSchedule) ) ? $doseSchedule : '';
				$drug = ( isset($drug) && !empty($drug) ) ? $drug : '';

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_medicalprocedure(
					$schema, // array // Main schema array
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

			// Properties from TherapeuticProcedure

				// adverseOutcome

					/* 
					 * Expected Type:
					 *     Thing > MedicalEntity
					 * 
					 * A possible complication and/or side effect of this therapy. If it is known that an adverse outcome is serious (resulting in death, disability, or permanent damage; requiring hospitalization; or otherwise life-threatening or requiring immediate medical attention), tag it as a seriousAdverseOutcome instead.
					 */

					 $schema['adverseOutcome'] = $adverseOutcome;

				// doseSchedule
 
					/* 
					 * Expected Type:
					 *     Thing > MedicalEntity > MedicalIntangible > DoseSchedule
					 * 
					 * A dosing schedule for the drug for a given population, either observed, recommended, or maximum dose based on the type used.
					 */
 
					 $schema['doseSchedule'] = $doseSchedule;
 
				// drug
 
					/* 
					 * Expected Type:
					 *     Thing > Product > Drug
					 * 
					 * Specifying a drug or medicine used in a medication procedure.
					 */
 
					 $schema['drug'] = $drug;
 
		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// MedicalTherapy
	include_once __DIR__ . '/TherapeuticProcedure/MedicalTherapy.php';

	// PsychologicalTreatment
	include_once __DIR__ . '/TherapeuticProcedure/PsychologicalTreatment.php';