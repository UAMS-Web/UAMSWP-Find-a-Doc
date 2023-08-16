<?php

// PhysicalExam

	/*
	 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam
	 * 
	 *     Also: Thing > MedicalEntity > MedicalProcedure > PhysicalExam
	 * 
	 * A type of physical examination of a patient performed by a physician.
	 */

	function uamswp_fad_schema_physicalexam(
		$schema, // array // Main schema array
		// PhysicalExam (no property vars)
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
		// Enumeration
			$supersededBy = '', // supersededBy
		// Intangible (no property vars)
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

			// Inherited properties from Intangible (Thing > Intangible)

				// Do nothing (no property vars)

			// Inherited properties from Enumeration (Thing > Intangible > Enumeration)

				$supersededBy = ( isset($supersededBy) && !empty($supersededBy) ) ? $supersededBy : '';

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

			// Properties from PhysicalExam (Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam)
			// Properties from PhysicalExam (Thing > MedicalEntity > MedicalProcedure > PhysicalExam)

				// Do nothing (no property vars)

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_enumeration(
					$schema, // array // Main schema array
					// Enumeration
						$supersededBy, // supersededBy
					// Intangible
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

			// Properties from PhysicalExam

				// Do nothing (no property vars)

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}

	// Abdomen
	include_once __DIR__ . '/PhysicalExam/Abdomen.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Abdomen
		 * 
		 * 
		 */

		function uamswp_fad_schema_abdomen(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// Appearance
	include_once __DIR__ . '/PhysicalExam/Appearance.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Appearance
		 * 
		 * 
		 */

		function uamswp_fad_schema_appearance(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// CardiovascularExam
	include_once __DIR__ . '/PhysicalExam/CardiovascularExam.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > CardiovascularExam
		 * 
		 * 
		 */

		function uamswp_fad_schema_cardiovascularexam(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// Ear
	include_once __DIR__ . '/PhysicalExam/Ear.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Ear
		 * 
		 * 
		 */

		function uamswp_fad_schema_ear(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// Eye
	include_once __DIR__ . '/PhysicalExam/Eye.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Eye
		 * 
		 * 
		 */

		function uamswp_fad_schema_eye(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// Genitourinary
	include_once __DIR__ . '/PhysicalExam/Genitourinary.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Genitourinary
		 * 
		 * 
		 */

		function uamswp_fad_schema_genitourinary(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// Head
	include_once __DIR__ . '/PhysicalExam/Head.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Head
		 * 
		 * 
		 */

		function uamswp_fad_schema_head(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// Lung
	include_once __DIR__ . '/PhysicalExam/Lung.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Lung
		 * 
		 * 
		 */

		function uamswp_fad_schema_lung(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// MusculoskeletalExam
	include_once __DIR__ . '/PhysicalExam/MusculoskeletalExam.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > MusculoskeletalExam
		 * 
		 * 
		 */

		function uamswp_fad_schema_musculoskeletalexam(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// Neck
	include_once __DIR__ . '/PhysicalExam/Neck.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Neck
		 * 
		 * 
		 */

		function uamswp_fad_schema_neck(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// Neuro
	include_once __DIR__ . '/PhysicalExam/Neuro.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Neuro
		 * 
		 * 
		 */

		function uamswp_fad_schema_neuro(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// Nose
	include_once __DIR__ . '/PhysicalExam/Nose.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Nose
		 * 
		 * 
		 */

		function uamswp_fad_schema_nose(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// Skin
	include_once __DIR__ . '/PhysicalExam/Skin.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Skin
		 * 
		 * 
		 */

		function uamswp_fad_schema_skin(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// Throat
	include_once __DIR__ . '/PhysicalExam/Throat.php';

		/*
		 * Thing > Intangible > Enumeration > MedicalEnumeration > PhysicalExam > Throat
		 * 
		 * 
		 */

		function uamswp_fad_schema_throat(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'baz'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}