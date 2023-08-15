<?php

// MedicalTestPanel

	/*
	 * Thing > MedicalEntity > MedicalTest > MedicalTestPanel
	 * 
	 * Any collection of tests commonly ordered together.
	 */

	function uamswp_fad_schema_medicaltestpanel(
		$schema, // array // Main schema array
		// MedicalTestPanel
			$subTest = '', // subTest
		// MedicalTest
			$affectedBy = '', // affectedBy
			$normalRange = '', // normalRange
			$signDetected = '', // signDetected
			$usedToDiagnose = '', // usedToDiagnose
			$usesDevice = '', // usesDevice
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

			// Inherited properties from MedicalTest (Thing > MedicalEntity > MedicalTest)

				$affectedBy = ( isset($affectedBy) && !empty($affectedBy) ) ? $affectedBy : '';
				$normalRange = ( isset($normalRange) && !empty($normalRange) ) ? $normalRange : '';
				$signDetected = ( isset($signDetected) && !empty($signDetected) ) ? $signDetected : '';
				$usedToDiagnose = ( isset($usedToDiagnose) && !empty($usedToDiagnose) ) ? $usedToDiagnose : '';
				$usesDevice = ( isset($usesDevice) && !empty($usesDevice) ) ? $usesDevice : '';

			// Properties from MedicalTestPanel (Thing > MedicalEntity > MedicalTest > MedicalTestPanel)

				$subTest = ( isset($subTest) && !empty($subTest) ) ? $subTest : '';

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_medicaltest(
					$schema, // array // Main schema array
					// MedicalTest
						$affectedBy, // affectedBy
						$normalRange, // normalRange
						$signDetected, // signDetected
						$usedToDiagnose, // usedToDiagnose
						$usesDevice, // usesDevice
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

			// Properties from MedicalTestPanel (Thing > MedicalEntity > MedicalTest > MedicalTestPanel)

				// subTest

					/* 
					 * Expected Type:
					 *     Thing > MedicalEntity > MedicalTest
					 * 
					 * A component test of the panel.
					 */

					$schema['subTest'] = $subTest;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}