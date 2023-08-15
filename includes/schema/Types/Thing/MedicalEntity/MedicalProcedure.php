<?php

// MedicalProcedure

	/*
	 * Thing > MedicalEntity > MedicalProcedure
	 * 
	 * A process of care used in either a diagnostic, therapeutic, preventive or 
	 * palliative capacity that relies on invasive (surgical), non-invasive, or other 
	 * techniques.
	 */

	function uamswp_fad_schema_medicalprocedure(
		$schema, // array // Main schema array
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

			// Properties from MedicalProcedure (Thing > MedicalEntity > MedicalProcedure)

				$bodyLocation = ( isset($bodyLocation) && !empty($bodyLocation) ) ? $bodyLocation: '';
				$followup = ( isset($followup) && !empty($followup) ) ? $followup: '';
				$howPerformed = ( isset($howPerformed) && !empty($howPerformed) ) ? $howPerformed: '';
				$preparation = ( isset($preparation) && !empty($preparation) ) ? $preparation: '';
				$procedureType = ( isset($procedureType) && !empty($procedureType) ) ? $procedureType: '';
				$status = ( isset($status) && !empty($status) ) ? $status: '';

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_medicalentity(
					$schema, // array // Main schema array
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

			// Properties from MedicalProcedure

				// bodyLocation

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * Location in the body of the anatomical structure.
					 */

					$schema['bodyLocation'] = $bodyLocation;

				// followup

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * Typical or recommended followup care after the procedure is performed.
					 */

					$schema['followup'] = $followup;

				// howPerformed

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * How the procedure is performed.
					 */

					$schema['howPerformed'] = $howPerformed;

				// preparation

					/* 
					 * Expected Type:
					 *     MedicalEntity
					 *     DataType > Text
					 * 
					 * Typical preparation that a patient must undergo before having the procedure 
					 * performed.
					 */

					$schema['preparation'] = $preparation;

				// procedureType

					/* 
					 * Expected Type:
					 *     MedicalProcedureType
					 * 
					 * The type of procedure, for example Surgical, Noninvasive, or Percutaneous.
					 */

					$schema['procedureType'] = $procedureType;

				// status

					/* 
					 * Expected Type:
					 *     EventStatusType
					 *     MedicalStudyStatus
					 *     DataType > Text
					 * 
					 * The status of the study (enumerated).
					 */

					$schema['status'] = $status;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// DiagnosticProcedure
	include_once __DIR__ . '/MedicalProcedure/DiagnosticProcedure.php';

	// PalliativeProcedure
	include_once __DIR__ . '/MedicalProcedure/PalliativeProcedure.php';

	// PhysicalExam
	include_once __DIR__ . '/MedicalProcedure/PhysicalExam.php';

	// SurgicalProcedure
	include_once __DIR__ . '/MedicalProcedure/SurgicalProcedure.php';

	// TherapeuticProcedure
	include_once __DIR__ . '/MedicalProcedure/TherapeuticProcedure.php';