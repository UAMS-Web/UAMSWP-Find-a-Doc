<?php

// MedicalSpecialty

	/*
	 * Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty
	 * 
	 *     Also: Thing > Intangible > Enumeration > Specialty > MedicalSpecialty
	 * 
	 * Any specific branch of medical science or practice. Medical specialties 
	 * include clinical specialties that pertain to particular organ systems and 
	 * their respective disease states, as well as allied health specialties. 
	 * Enumerated type.
	 * 
	 * Enumeration members:
	 * 
	 *     Anesthesia
	 *     Cardiovascular
	 *     CommunityHealth
	 *     Dentistry
	 *     Dermatology
	 *     DietNutrition
	 *     Emergency
	 *     Endocrine
	 *     Gastroenterologic
	 *     Genetic
	 *     Geriatric
	 *     Gynecologic
	 *     Hematologic
	 *     Infectious
	 *     LaboratoryScience
	 *     Midwifery
	 *     Musculoskeletal
	 *     Neurologic
	 *     Nursing
	 *     Obstetric
	 *     Oncologic
	 *     Optometric
	 *     Otolaryngologic
	 *     Pathology
	 *     Pediatric
	 *     PharmacySpecialty
	 *     Physiotherapy
	 *     PlasticSurgery
	 *     Podiatric
	 *     PrimaryCare
	 *     Psychiatric
	 *     PublicHealth
	 *     Pulmonary
	 *     Radiography
	 *     Renal
	 *     RespiratoryTherapy
	 *     Rheumatologic
	 *     SpeechPathology
	 *     Surgical
	 *     Toxicologic
	 *     Urologic
	 */

	function uamswp_fad_schema_medicalspecialty(
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

			// Properties from MedicalSpecialty (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty)

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

			// Properties from MedicalSpecialty (Thing > Intangible > Enumeration > MedicalEnumeration > MedicalSpecialty)

				// Do nothing (no property vars)

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}

	// Anesthesia
	include_once __DIR__ . '/MedicalSpecialty/Anesthesia.php';

	// Cardiovascular
	include_once __DIR__ . '/MedicalSpecialty/Cardiovascular.php';

	// CommunityHealth
	include_once __DIR__ . '/MedicalSpecialty/CommunityHealth.php';

	// Dentistry
	include_once __DIR__ . '/MedicalSpecialty/Dentistry.php';

	// Dermatologic
	include_once __DIR__ . '/MedicalSpecialty/Dermatologic.php';

	// Dermatology
	include_once __DIR__ . '/MedicalSpecialty/Dermatology.php';

	// DietNutrition
	include_once __DIR__ . '/MedicalSpecialty/DietNutrition.php';

	// Emergency
	include_once __DIR__ . '/MedicalSpecialty/Emergency.php';

	// Endocrine
	include_once __DIR__ . '/MedicalSpecialty/Endocrine.php';

	// Gastroenterologic
	include_once __DIR__ . '/MedicalSpecialty/Gastroenterologic.php';

	// Genetic
	include_once __DIR__ . '/MedicalSpecialty/Genetic.php';

	// Geriatric
	include_once __DIR__ . '/MedicalSpecialty/Geriatric.php';

	// Gynecologic
	include_once __DIR__ . '/MedicalSpecialty/Gynecologic.php';

	// Hematologic
	include_once __DIR__ . '/MedicalSpecialty/Hematologic.php';

	// Infectious
	include_once __DIR__ . '/MedicalSpecialty/Infectious.php';

	// LaboratoryScience
	include_once __DIR__ . '/MedicalSpecialty/LaboratoryScience.php';

	// Midwifery
	include_once __DIR__ . '/MedicalSpecialty/Midwifery.php';

	// Musculoskeletal
	include_once __DIR__ . '/MedicalSpecialty/Musculoskeletal.php';

	// Neurologic
	include_once __DIR__ . '/MedicalSpecialty/Neurologic.php';

	// Nursing
	include_once __DIR__ . '/MedicalSpecialty/Nursing.php';

	// Obstetric
	include_once __DIR__ . '/MedicalSpecialty/Obstetric.php';

	// Oncologic
	include_once __DIR__ . '/MedicalSpecialty/Oncologic.php';

	// Optometric
	include_once __DIR__ . '/MedicalSpecialty/Optometric.php';

	// Otolaryngologic
	include_once __DIR__ . '/MedicalSpecialty/Otolaryngologic.php';

	// Pathology
	include_once __DIR__ . '/MedicalSpecialty/Pathology.php';

	// Pediatric
	include_once __DIR__ . '/MedicalSpecialty/Pediatric.php';

	// PharmacySpecialty
	include_once __DIR__ . '/MedicalSpecialty/PharmacySpecialty.php';

	// Physiotherapy
	include_once __DIR__ . '/MedicalSpecialty/Physiotherapy.php';

	// PlasticSurgery
	include_once __DIR__ . '/MedicalSpecialty/PlasticSurgery.php';

	// Podiatric
	include_once __DIR__ . '/MedicalSpecialty/Podiatric.php';

	// PrimaryCare
	include_once __DIR__ . '/MedicalSpecialty/PrimaryCare.php';

	// Psychiatric
	include_once __DIR__ . '/MedicalSpecialty/Psychiatric.php';

	// PublicHealth
	include_once __DIR__ . '/MedicalSpecialty/PublicHealth.php';

	// Pulmonary
	include_once __DIR__ . '/MedicalSpecialty/Pulmonary.php';

	// Radiography
	include_once __DIR__ . '/MedicalSpecialty/Radiography.php';

	// Renal
	include_once __DIR__ . '/MedicalSpecialty/Renal.php';

	// RespiratoryTherapy
	include_once __DIR__ . '/MedicalSpecialty/RespiratoryTherapy.php';

	// Rheumatologic
	include_once __DIR__ . '/MedicalSpecialty/Rheumatologic.php';

	// SpeechPathology
	include_once __DIR__ . '/MedicalSpecialty/SpeechPathology.php';

	// Surgical
	include_once __DIR__ . '/MedicalSpecialty/Surgical.php';

	// Toxicologic
	include_once __DIR__ . '/MedicalSpecialty/Toxicologic.php';

	// Urologic
	include_once __DIR__ . '/MedicalSpecialty/Urologic.php';