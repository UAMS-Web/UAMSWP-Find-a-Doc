<?php

// MedicalEntity

	/*
	 * Thing > MedicalEntity
	 * 
	 * The most generic type of entity related to health and the practice of medicine.
	 */

	function uamswp_fad_schema_medicalentity(
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
					'Thing'
				);

			// Properties from this type (and not from the parent[s] of this type)

				/*

					The type name and capitalization must match what is found on Schema.org.

					Find the expected types of their values on Schema.org page for this type
					(e.g., https://schema.org/Thing).

				*/

				$type_properties = array(
					'code', // code
					'funding', // funding
					'guideline', // guideline
					'legalStatus', // legalStatus
					'medicineSystem', // medicineSystem
					'recognizingAuthority', // recognizingAuthority
					'relevantSpecialty', // relevantSpecialty
					'study' // study
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

	// AnatomicalStructure
	include_once __DIR__ . '/MedicalEntity/AnatomicalStructure.php';

		/*
		 * Thing > MedicalEntity > AnatomicalStructure
		 * 
		 * Any part of the human body, typically a component of an anatomical system.
		 * Organs, tissues, and cells are all anatomical structures.
		 */

		function uamswp_fad_schema_anatomicalstructure(
			$schema, // array // Main schema array
			// AnatomicalStructure
				$associatedPathophysiology = '', // associatedPathophysiology
				$bodyLocation = '', // bodyLocation
				$connectedTo = '', // connectedTo
				$diagram = '', // diagram
				$partOfSystem = '', // partOfSystem
				$relatedCondition = '', // relatedCondition
				$relatedTherapy = '', // relatedTherapy
				$subStructure = '', // subStructure
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

				// Properties from AnatomicalStructure (Thing > MedicalEntity > AnatomicalStructure)

					$associatedPathophysiology = ( isset($associatedPathophysiology) && !empty($associatedPathophysiology) ) ? $associatedPathophysiology : '';
					$bodyLocation = ( isset($bodyLocation) && !empty($bodyLocation) ) ? $bodyLocation : '';
					$connectedTo = ( isset($connectedTo) && !empty($connectedTo) ) ? $connectedTo : '';
					$diagram = ( isset($diagram) && !empty($diagram) ) ? $diagram : '';
					$partOfSystem = ( isset($partOfSystem) && !empty($partOfSystem) ) ? $partOfSystem : '';
					$relatedCondition = ( isset($relatedCondition) && !empty($relatedCondition) ) ? $relatedCondition : '';
					$relatedTherapy = ( isset($relatedTherapy) && !empty($relatedTherapy) ) ? $relatedTherapy : '';
					$subStructure = ( isset($subStructure) && !empty($subStructure) ) ? $subStructure : '';

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

				// Properties from MedicalEntity

					// associatedPathophysiology

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * If applicable, a description of the pathophysiology associated with the 
						 * anatomical system, including potential abnormal changes in the mechanical, 
						 * physical, and biochemicalfunctions of the system.
						 */

						$schema['associatedPathophysiology'] = ( isset($associatedPathophysiology) && !empty($associatedPathophysiology) ) ? uamswp_fad_schema_type_selector($associatedPathophysiology) : '';

					// bodyLocation

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * Location in the body of the anatomical structure.
						 */

						$schema['bodyLocation'] = ( isset($bodyLocation) && !empty($bodyLocation) ) ? uamswp_fad_schema_type_selector($bodyLocation) : '';

					// connectedTo

						/* 
						 * Expected Type:
						 *     AnatomicalStructure
						 * 
						 * Other anatomical structures to which this structure is connected.
						 */

						$schema['connectedTo'] = ( isset($connectedTo) && !empty($connectedTo) ) ? uamswp_fad_schema_type_selector($connectedTo) : '';

					// diagram

						/* 
						 * Expected Type:
						 *     Thing > CreativeWork > MediaObject > ImageObject
						 * 
						 * An image containing a diagram that illustrates the structure and/or its 
						 * component substructures and/or connections with other structures.
						 */

						$schema['diagram'] = ( isset($diagram) && !empty($diagram) ) ? uamswp_fad_schema_type_selector($diagram) : '';

					// partOfSystem

						/* 
						 * Expected Type:
						 *     AnatomicalSystem
						 * 
						 * The anatomical or organ system that this structure is part of.
						 */

						$schema['partOfSystem'] = ( isset($partOfSystem) && !empty($partOfSystem) ) ? uamswp_fad_schema_type_selector($partOfSystem) : '';

					// relatedCondition

						/* 
						 * Expected Type:
						 *     MedicalCondition
						 * 
						 * A medical condition associated with this anatomy.
						 */

						$schema['relatedCondition'] = ( isset($relatedCondition) && !empty($relatedCondition) ) ? uamswp_fad_schema_type_selector($relatedCondition) : '';

					// relatedTherapy

						/* 
						 * Expected Type:
						 *     Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy
						 * 
						 * A medical therapy related to this anatomy.
						 */

						$schema['relatedTherapy'] = ( isset($relatedTherapy) && !empty($relatedTherapy) ) ? uamswp_fad_schema_type_selector($relatedTherapy) : '';

					// subStructure

						/* 
						 * Expected Type:
						 *     AnatomicalStructure
						 * 
						 * Component (sub-)structure(s) that comprise this anatomical structure.
						 */

						$schema['subStructure'] = ( isset($subStructure) && !empty($subStructure) ) ? uamswp_fad_schema_type_selector($subStructure) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

		// Bone

			/*
			 * Thing > MedicalEntity > AnatomicalStructure > Bone
			 * 
			 * 
			 */

			function uamswp_fad_schema_bone(
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

		// BrainStructure

			/*
			 * Thing > MedicalEntity > AnatomicalStructure > BrainStructure
			 * 
			 * 
			 */

			function uamswp_fad_schema_brainstructure(
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

		// Joint

			/*
			 * Thing > MedicalEntity > AnatomicalStructure > Joint
			 * 
			 * 
			 */

			function uamswp_fad_schema_joint(
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

		// Ligament

			/*
			 * Thing > MedicalEntity > AnatomicalStructure > Ligament
			 * 
			 * 
			 */

			function uamswp_fad_schema_ligament(
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

		// Muscle

			/*
			 * Thing > MedicalEntity > AnatomicalStructure > Muscle
			 * 
			 * 
			 */

			function uamswp_fad_schema_muscle(
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

		// Nerve

			/*
			 * Thing > MedicalEntity > AnatomicalStructure > Nerve
			 * 
			 * 
			 */

			function uamswp_fad_schema_nerve(
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

		// Vessel

			/*
			 * Thing > MedicalEntity > AnatomicalStructure > Vessel
			 * 
			 * 
			 */

			function uamswp_fad_schema_vessel(
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

			// Artery

				/*
				 * Thing > MedicalEntity > AnatomicalStructure > Vessel > Artery
				 * 
				 * 
				 */

				function uamswp_fad_schema_artery(
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

			// LymphaticVessel

				/*
				 * Thing > MedicalEntity > AnatomicalStructure > Vessel > LymphaticVessel
				 * 
				 * 
				 */

				function uamswp_fad_schema_lymphaticvessel(
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

			// Vein

				/*
				 * Thing > MedicalEntity > AnatomicalStructure > Vessel > Vein
				 * 
				 * 
				 */

				function uamswp_fad_schema_vein(
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


	// AnatomicalSystem
	include_once __DIR__ . '/MedicalEntity/AnatomicalSystem.php';

		/*
		 * Thing > MedicalEntity > AnatomicalSystem
		 * 
		 * An anatomical system is a group of anatomical structures that work together to 
		 * perform a certain task. Anatomical systems, such as organ systems, are one 
		 * organizing principle of anatomy, and can include circulatory, digestive, 
		 * endocrine, integumentary, immune, lymphatic, muscular, nervous, reproductive, 
		 * respiratory, skeletal, urinary, vestibular, and other systems.
		 */

		function uamswp_fad_schema_anatomicalsystem(
			$schema, // array // Main schema array
			// AnatomicalSystem
				$associatedPathophysiology = '', // associatedPathophysiology
				$comprisedOf = '', // comprisedOf
				$relatedCondition = '', // relatedCondition
				$relatedStructure = '', // relatedStructure
				$relatedTherapy = '', // relatedTherapy
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

				// Properties from AnatomicalSystem (Thing > MedicalEntity > AnatomicalSystem)

					$associatedPathophysiology = ( isset($associatedPathophysiology) && !empty($associatedPathophysiology) ) ? $associatedPathophysiology : '';
					$comprisedOf = ( isset($comprisedOf) && !empty($comprisedOf) ) ? $comprisedOf : '';
					$relatedCondition = ( isset($relatedCondition) && !empty($relatedCondition) ) ? $relatedCondition : '';
					$relatedStructure = ( isset($relatedStructure) && !empty($relatedStructure) ) ? $relatedStructure : '';
					$relatedTherapy = ( isset($relatedTherapy) && !empty($relatedTherapy) ) ? $relatedTherapy : '';

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

				// Properties from AnatomicalSystem

					// associatedPathophysiology

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * If applicable, a description of the pathophysiology associated with the 
						 * anatomical system, including potential abnormal changes in the mechanical, 
						 * physical, and biochemicalfunctions of the system.
						 */

						$schema['associatedPathophysiology'] = ( isset($associatedPathophysiology) && !empty($associatedPathophysiology) ) ? uamswp_fad_schema_type_selector($associatedPathophysiology) : '';

					// comprisedOf

						/* 
						 * Expected Type:
						 *     AnatomicalStructure
						 *     AnatomicalSystem
						 * 
						 * Specifying something physically contained by something else. Typically used 
						 * here for the underlying anatomical structures, such as organs, that comprise 
						 * the anatomical system.
						 */

						$schema['comprisedOf'] = ( isset($comprisedOf) && !empty($comprisedOf) ) ? uamswp_fad_schema_type_selector($comprisedOf) : '';

					// relatedCondition

						/* 
						 * Expected Type:
						 *     MedicalCondition
						 * 
						 * A medical condition associated with this anatomy.
						 */

						$schema['relatedCondition'] = ( isset($relatedCondition) && !empty($relatedCondition) ) ? uamswp_fad_schema_type_selector($relatedCondition) : '';

					// relatedStructure

						/* 
						 * Expected Type:
						 *     AnatomicalStructure
						 * 
						 * Related anatomical structure(s) that are not part of the system but relate or 
						 * connect to it, such as vascular bundles associated with an organ system.
						 */

						$schema['relatedStructure'] = ( isset($relatedStructure) && !empty($relatedStructure) ) ? uamswp_fad_schema_type_selector($relatedStructure) : '';

					// relatedTherapy

						/* 
						 * Expected Type:
						 *     Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy
						 * 
						 * A medical therapy related to this anatomy.
						 */

						$schema['relatedTherapy'] = ( isset($relatedTherapy) && !empty($relatedTherapy) ) ? uamswp_fad_schema_type_selector($relatedTherapy) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

	// DrugClass
	include_once __DIR__ . '/MedicalEntity/DrugClass.php';

		/*
		 * Thing > MedicalEntity > DrugClass
		 * 
		 * A class of medical drugs (e.g., statins). Classes can represent general 
		 * pharmacological class, common mechanisms of action, common physiological 
		 * effects, etc.
		 */

		function uamswp_fad_schema_drugclass(
			$schema, // array // Main schema array
			// DrugClass
				$drug = '', // drug
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

				// Properties from DrugClass (Thing > MedicalEntity > DrugClass)

					$drug = ( isset($drug) && !empty($drug) ) ? $drug : '';

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

				// Properties from DrugClass

					// drug

						/* 
						 * Expected Type:
						 *     Drug
						 * 
						 * Specifying a drug or medicine used in a medication procedure.
						 */

						 $schema['drug'] = ( isset($drug) && !empty($drug) ) ? uamswp_fad_schema_type_selector($drug) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

	// DrugCost
	include_once __DIR__ . '/MedicalEntity/DrugCost.php';

		/*
		 * Thing > MedicalEntity > DrugCost
		 * 
		 * The cost per unit of a medical drug. Note that this type is not meant to 
		 * represent the price in an offer of a drug for sale; see the Offer type for 
		 * that. This type will typically be used to tag wholesale or average retail cost 
		 * of a drug, or maximum reimbursable cost. Costs of medical drugs vary widely 
		 * depending on how and where they are paid for, so while this type captures some 
		 * of the variables, costs should be used with caution by consumers of this 
		 * schema's markup.
		 */

		function uamswp_fad_schema_drugcost(
			$schema, // array // Main schema array
			// DrugCost
				$applicableLocation = '', // applicableLocation
				$costCategory = '', // costCategory
				$costCurrency = '', // costCurrency
				$costOrigin = '', // costOrigin
				$costPerUnit = '', // costPerUnit
				$drugUnit = '', // drugUnit
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

				// Properties from DrugCost (Thing > MedicalEntity > DrugCost)

					$applicableLocation = ( isset($applicableLocation) && !empty($applicableLocation) ) ? $applicableLocation : '';
					$costCategory = ( isset($costCategory) && !empty($costCategory) ) ? $costCategory : '';
					$costCurrency = ( isset($costCurrency) && !empty($costCurrency) ) ? $costCurrency : '';
					$costOrigin = ( isset($costOrigin) && !empty($costOrigin) ) ? $costOrigin : '';
					$costPerUnit = ( isset($costPerUnit) && !empty($costPerUnit) ) ? $costPerUnit : '';
					$drugUnit = ( isset($drugUnit) && !empty($drugUnit) ) ? $drugUnit : '';

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

				// Properties from DrugCost

					// applicableLocation

						/* 
						 * Expected Type:
						 *     Thing > Place > AdministrativeArea
						 * 
						 * The location in which the status applies.
						 */

						$schema['applicableLocation'] = ( isset($applicableLocation) && !empty($applicableLocation) ) ? uamswp_fad_schema_type_selector($applicableLocation) : '';

					// costCategory

						/* 
						 * Expected Type:
						 *     DrugCostCategory
						 * 
						 * The category of cost, such as wholesale, retail, reimbursement cap, etc.
						 */

						$schema['costCategory'] = ( isset($costCategory) && !empty($costCategory) ) ? uamswp_fad_schema_type_selector($costCategory) : '';

					// costCurrency

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * The currency (in 3-letter) of the drug cost. 
						 * See: http://en.wikipedia.org/wiki/ISO_4217.
						 */

						$schema['costCurrency'] = ( isset($costCurrency) && !empty($costCurrency) ) ? uamswp_fad_schema_type_selector($costCurrency) : '';

					// costOrigin

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * Additional details to capture the origin of the cost data. For example, 
						 * 'Medicare Part B'.
						 */

						$schema['costOrigin'] = ( isset($costOrigin) && !empty($costOrigin) ) ? uamswp_fad_schema_type_selector($costOrigin) : '';

					// costPerUnit

						/* 
						 * Expected Type:
						 *     Number
						 *     QualitativeValue
						 *     DataType > Text
						 * 
						 * The cost per unit of the drug.
						 */

						$schema['costPerUnit'] = ( isset($costPerUnit) && !empty($costPerUnit) ) ? uamswp_fad_schema_type_selector($costPerUnit) : '';

					// drugUnit

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * The unit in which the drug is measured (e.g., '5 mg tablet').
						 */

						$schema['drugUnit'] = ( isset($drugUnit) && !empty($drugUnit) ) ? uamswp_fad_schema_type_selector($drugUnit) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

	// LifestyleModification
	include_once __DIR__ . '/MedicalEntity/LifestyleModification.php';

		/*
		 * Thing > MedicalEntity > LifestyleModification
		 * 
		 * A process of care involving exercise, changes to diet, fitness routines, and 
		 * other lifestyle changes aimed at improving a health condition.
		 */

		function uamswp_fad_schema_lifestylemodification(
			$schema, // array // Main schema array
			// LifestyleModification (no property vars)
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

				// Properties from LifestyleModification (Thing > MedicalEntity > LifestyleModification)

					// Do nothing (no property vars)

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

				// Properties from LifestyleModification

					// Do nothing (no property vars)

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

		// Diet

			/*
			 * Thing > MedicalEntity > LifestyleModification > Diet
			 * 
			 * 
			 */

			function uamswp_fad_schema_diet(
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

		// PhysicalActivity

			/*
			 * Thing > MedicalEntity > LifestyleModification > PhysicalActivity
			 * 
			 * 
			 */

			function uamswp_fad_schema_physicalactivity(
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

			// ExercisePlan

				/*
				 * Thing > MedicalEntity > LifestyleModification > PhysicalActivity > ExercisePlan
				 * 
				 * 
				 */

				function uamswp_fad_schema_exerciseplan(
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


	// MedicalCause
	include_once __DIR__ . '/MedicalEntity/MedicalCause.php';

		/*
		 * Thing > MedicalEntity > MedicalCause
		 * 
		 * The causative agent(s) that are responsible for the pathophysiologic process 
		 * that eventually results in a medical condition, symptom or sign. In this 
		 * schema, unless otherwise specified this is meant to be the proximate cause of 
		 * the medical condition, symptom or sign. The proximate cause is defined as the 
		 * causative agent that most directly results in the medical condition, symptom or 
		 * sign. For example, the HIV virus could be considered a cause of AIDS. Or in a 
		 * diagnostic context, if a patient fell and sustained a hip fracture and two days 
		 * later sustained a pulmonary embolism which eventuated in a cardiac arrest, the 
		 * cause of the cardiac arrest (the proximate cause) would be the pulmonary 
		 * embolism and not the fall. Medical causes can include cardiovascular, chemical, 
		 * dermatologic, endocrine, environmental, gastroenterologic, genetic, 
		 * hematologic, gynecologic, iatrogenic, infectious, musculoskeletal, neurologic, 
		 * nutritional, obstetric, oncologic, otolaryngologic, pharmacologic, psychiatric, 
		 * pulmonary, renal, rheumatologic, toxic, traumatic, or urologic causes; medical 
		 * conditions can be causes as well.
		 */

		function uamswp_fad_schema_medicalcause(
			$schema, // array // Main schema array
			// MedicalCause
				$causeOf = '', // causeOf
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

				// Properties from MedicalCause (Thing > MedicalEntity > MedicalCause)

					$causeOf = ( isset($causeOf) && !empty($causeOf) ) ? $causeOf : '';

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

				// Properties from MedicalCause

					// causeOf

						/* 
						 * Expected Type:
						 *     MedicalEntity
						 * 
						 * The condition, complication, symptom, sign, etc. caused.
						 */

						$schema['causeOf'] = ( isset($causeOf) && !empty($causeOf) ) ? uamswp_fad_schema_type_selector($causeOf) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

	// MedicalCondition
	include_once __DIR__ . '/MedicalEntity/MedicalCondition.php';

		/*
		 * Thing > MedicalEntity > MedicalCondition
		 * 
		 * Any condition of the human body that affects the normalfunctioning of a 
		 * person, whether physically or mentally. Includes diseases, injuries, 
		 * disabilities, disorders, syndromes, etc.
		 */

		function uamswp_fad_schema_medicalcondition(
			$schema, // array // Main schema array
			// MedicalCondition
				$associatedAnatomy = '', // associatedAnatomy
				$differentialDiagnosis = '', // differentialDiagnosis
				$drug = '', // drug
				$epidemiology = '', // epidemiology
				$expectedPrognosis = '', // expectedPrognosis
				$naturalProgression = '', // naturalProgression
				$pathophysiology = '', // pathophysiology
				$possibleComplication = '', // possibleComplication
				$possibleTreatment = '', // possibleTreatment
				$primaryPrevention = '', // primaryPrevention
				$riskFactor = '', // riskFactor
				$secondaryPrevention = '', // secondaryPrevention
				$signOrSymptom = '', // signOrSymptom
				$stage = '', // stage
				$status = '', // status
				$typicalTest = '', // typicalTest
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

				// Properties from MedicalCondition (Thing > MedicalEntity > MedicalCondition)

					$associatedAnatomy = ( isset($associatedAnatomy) && !empty($associatedAnatomy) ) ? $associatedAnatomy : '';
					$differentialDiagnosis = ( isset($differentialDiagnosis) && !empty($differentialDiagnosis) ) ? $differentialDiagnosis : '';
					$drug = ( isset($drug) && !empty($drug) ) ? $drug : '';
					$epidemiology = ( isset($epidemiology) && !empty($epidemiology) ) ? $epidemiology : '';
					$expectedPrognosis = ( isset($expectedPrognosis) && !empty($expectedPrognosis) ) ? $expectedPrognosis : '';
					$naturalProgression = ( isset($naturalProgression) && !empty($naturalProgression) ) ? $naturalProgression : '';
					$pathophysiology = ( isset($pathophysiology) && !empty($pathophysiology) ) ? $pathophysiology : '';
					$possibleComplication = ( isset($possibleComplication) && !empty($possibleComplication) ) ? $possibleComplication : '';
					$possibleTreatment = ( isset($possibleTreatment) && !empty($possibleTreatment) ) ? $possibleTreatment : '';
					$primaryPrevention = ( isset($primaryPrevention) && !empty($primaryPrevention) ) ? $primaryPrevention : '';
					$riskFactor = ( isset($riskFactor) && !empty($riskFactor) ) ? $riskFactor : '';
					$secondaryPrevention = ( isset($secondaryPrevention) && !empty($secondaryPrevention) ) ? $secondaryPrevention : '';
					$signOrSymptom = ( isset($signOrSymptom) && !empty($signOrSymptom) ) ? $signOrSymptom : '';
					$stage = ( isset($stage) && !empty($stage) ) ? $stage : '';
					$status = ( isset($status) && !empty($status) ) ? $status : '';
					$typicalTest = ( isset($typicalTest) && !empty($typicalTest) ) ? $typicalTest : '';

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

				// Properties from MedicalCondition

					// associatedAnatomy

						/* 
						 * Expected Type:
						 *     AnatomicalStructure
						 *     AnatomicalSystem
						 *     SuperficialAnatomy
						 * 
						 * The anatomy of the underlying organ system or structures associated with this 
						 * entity.
						 */

						$schema['associatedAnatomy'] = ( isset($associatedAnatomy) && !empty($associatedAnatomy) ) ? uamswp_fad_schema_type_selector($associatedAnatomy) : '';

					// differentialDiagnosis

						/* 
						 * Expected Type:
						 *     DDxElement
						 * 
						 * One of a set of differential diagnoses for the condition. Specifically, a 
						 * closely-related or competing diagnosis typically considered later in the 
						 * cognitive process whereby this medical condition is distinguished from others 
						 * most likely responsible for a similar collection of signs and symptoms to reach 
						 * the most parsimonious diagnosis or diagnoses in a patient.
						 */

						$schema['differentialDiagnosis'] = ( isset($differentialDiagnosis) && !empty($differentialDiagnosis) ) ? uamswp_fad_schema_type_selector($differentialDiagnosis) : '';

					// drug

						/* 
						 * Expected Type:
						 *     Drug
						 * 
						 * Specifying a drug or medicine used in a medication procedure.
						 */

						$schema['drug'] = ( isset($drug) && !empty($drug) ) ? uamswp_fad_schema_type_selector($drug) : '';

					// epidemiology

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * The characteristics of associated patients, such as age, gender, race etc.
						 */

						$schema['epidemiology'] = ( isset($epidemiology) && !empty($epidemiology) ) ? uamswp_fad_schema_type_selector($epidemiology) : '';

					// expectedPrognosis

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * The likely outcome in either the short term or long term of the medical 
						 * condition.
						 */

						$schema['expectedPrognosis'] = ( isset($expectedPrognosis) && !empty($expectedPrognosis) ) ? uamswp_fad_schema_type_selector($expectedPrognosis) : '';

					// naturalProgression

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * The expected progression of the condition if it is not treated and allowed to 
						 * progress naturally.
						 */

						$schema['naturalProgression'] = ( isset($naturalProgression) && !empty($naturalProgression) ) ? uamswp_fad_schema_type_selector($naturalProgression) : '';

					// pathophysiology

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * Changes in the normal mechanical, physical, and biochemicalfunctions that are 
						 * associated with this activity or condition.
						 */

						$schema['pathophysiology'] = ( isset($pathophysiology) && !empty($pathophysiology) ) ? uamswp_fad_schema_type_selector($pathophysiology) : '';

					// possibleComplication

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * A possible unexpected and unfavorable evolution of a medical condition. 
						 * Complications may include worsening of the signs or symptoms of the disease, 
						 * extension of the condition to other organ systems, etc.
						 */

						$schema['possibleComplication'] = ( isset($possibleComplication) && !empty($possibleComplication) ) ? uamswp_fad_schema_type_selector($possibleComplication) : '';

					// possibleTreatment

						/* 
						 * Expected Type:
						 *     Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy
						 * 
						 * A possible treatment to address this condition, sign or symptom.
						 */

						$schema['possibleTreatment'] = ( isset($possibleTreatment) && !empty($possibleTreatment) ) ? uamswp_fad_schema_type_selector($possibleTreatment) : '';

					// primaryPrevention

						/* 
						 * Expected Type:
						 *     Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy
						 * 
						 * A preventative therapy used to prevent an initial occurrence of the medical 
						 * condition, such as vaccination.
						 */

						$schema['primaryPrevention'] = ( isset($primaryPrevention) && !empty($primaryPrevention) ) ? uamswp_fad_schema_type_selector($primaryPrevention) : '';

					// riskFactor

						/* 
						 * Expected Type:
						 *     MedicalRiskFactor
						 * 
						 * A preventative therapy used to prevent reoccurrence of the medical condition 
						 * after an initial episode of the condition.
						 */

						$schema['riskFactor'] = ( isset($riskFactor) && !empty($riskFactor) ) ? uamswp_fad_schema_type_selector($riskFactor) : '';

					// secondaryPrevention

						/* 
						 * Expected Type:
						 *     Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy
						 * 
						 * A preventative therapy used to prevent reoccurrence of the medical condition 
						 * after an initial episode of the condition.
						 */

						$schema['secondaryPrevention'] = ( isset($secondaryPrevention) && !empty($secondaryPrevention) ) ? uamswp_fad_schema_type_selector($secondaryPrevention) : '';

					// signOrSymptom

						/* 
						 * Expected Type:
						 *     MedicalSignOrSymptom
						 * 
						 * A sign or symptom of this condition. Signs are objective or physically 
						 * observable manifestations of the medical condition while symptoms are the 
						 * subjective experience of the medical condition.
						 */

						$schema['signOrSymptom'] = ( isset($signOrSymptom) && !empty($signOrSymptom) ) ? uamswp_fad_schema_type_selector($signOrSymptom) : '';

					// stage

						/* 
						 * Expected Type:
						 *     MedicalConditionStage
						 * 
						 * The stage of the condition, if applicable.
						 */

						$schema['stage'] = ( isset($stage) && !empty($stage) ) ? uamswp_fad_schema_type_selector($stage) : '';

					// status

						/* 
						 * Expected Type:
						 *     EventStatusType
						 *     MedicalStudyStatus
						 *     DataType > Text
						 * 
						 * The status of the study (enumerated).
						 */

						$schema['status'] = ( isset($status) && !empty($status) ) ? uamswp_fad_schema_type_selector($status) : '';

					// typicalTest

						/* 
						 * Expected Type:
						 *     Thing > MedicalEntity > MedicalTest
						 * 
						 * A medical test typically performed given this condition.
						 */

						$schema['typicalTest'] = ( isset($typicalTest) && !empty($typicalTest) ) ? uamswp_fad_schema_type_selector($typicalTest) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

		// InfectiousDisease

			/*
			 * Thing > MedicalEntity > MedicalCondition > InfectiousDisease
			 * 
			 * 
			 */

			function uamswp_fad_schema_infectiousdisease(
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

		// MedicalSignOrSymptom

			/*
			 * Thing > MedicalEntity > MedicalCondition > MedicalSignOrSymptom
			 * 
			 * 
			 */

			function uamswp_fad_schema_medicalsignorsymptom(
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

			// MedicalSign

				/*
				 * Thing > MedicalEntity > MedicalCondition > MedicalSignOrSymptom > MedicalSign
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicalsign(
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

				// VitalSign

					/*
					 * Thing > MedicalEntity > MedicalCondition > qux > quux > VitalSign
					 * 
					 * 
					 */

					function uamswp_fad_schema_vitalsign(
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

			// MedicalSymptom

				/*
				 * Thing > MedicalEntity > MedicalCondition > MedicalSignOrSymptom > MedicalSymptom
				 * 
				 * 
				 */

				function uamswp_fad_schema_medicalsymptom(
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


	// MedicalContraindication
	include_once __DIR__ . '/MedicalEntity/MedicalContraindication.php';

		/*
		 * Thing > MedicalEntity > MedicalContraindication
		 * 
		 * A condition or factor that serves as a reason to withhold a certain medical 
		 * therapy. Contraindications can be absolute (there are no reasonable 
		 * circumstances for undertaking a course of action) or relative (the patient is 
		 * at higher risk of complications, but these risks may be outweighed by other 
		 * considerations or mitigated by other measures).
		 */

		function uamswp_fad_schema_medicalcontraindication(
			$schema, // array // Main schema array
			// MedicalContraindication (no property vars)
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

				// Properties from MedicalContraindication (Thing > MedicalEntity > MedicalContraindication)

					// Do nothing (no property vars)

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

				// Properties from MedicalContraindication

					// Do nothing (no property vars)

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

	// MedicalDevice
	include_once __DIR__ . '/MedicalEntity/MedicalDevice.php';

		/*
		 * Thing > MedicalEntity > MedicalDevice
		 * 
		 * Any object used in a medical capacity, such as to diagnose or treat a patient.
		 */

		function uamswp_fad_schema_medicaldevice(
			$schema, // array // Main schema array
			// MedicalDevice
				$adverseOutcome = '', // adverseOutcome
				$contraindication = '', // contraindication
				$postOp = '', // postOp
				$preOp = '', // preOp
				$procedure = '', // procedure
				$seriousAdverseOutcome = '', // seriousAdverseOutcome
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

				// Properties from MedicalDevice (Thing > MedicalEntity > MedicalDevice)

					$adverseOutcome = ( isset($adverseOutcome) && !empty($adverseOutcome) ) ? $adverseOutcome : '';
					$contraindication = ( isset($contraindication) && !empty($contraindication) ) ? $contraindication : '';
					$postOp = ( isset($postOp) && !empty($postOp) ) ? $postOp : '';
					$preOp = ( isset($preOp) && !empty($preOp) ) ? $preOp : '';
					$procedure = ( isset($procedure) && !empty($procedure) ) ? $procedure : '';
					$seriousAdverseOutcome = ( isset($seriousAdverseOutcome) && !empty($seriousAdverseOutcome) ) ? $seriousAdverseOutcome : '';

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

				// Properties from MedicalDevice

					// adverseOutcome

						/* 
						 * Expected Type:
						 *     MedicalEntity
						 * 
						 * A possible complication and/or side effect of this therapy. If it is known that 
						 * an adverse outcome is serious (resulting in death, disability, or permanent 
						 * damage; requiring hospitalization; or otherwise life-threatening or requiring 
						 * immediate medical attention), tag it as a seriousAdverseOutcome instead.
						 */

						$schema['adverseOutcome'] = ( isset($adverseOutcome) && !empty($adverseOutcome) ) ? uamswp_fad_schema_type_selector($adverseOutcome) : '';

					// contraindication

						/* 
						 * Expected Type:
						 *     MedicalContraindication
						 *     DataType > Text
						 * 
						 * A contraindication for this therapy.
						 */

						$schema['contraindication'] = ( isset($contraindication) && !empty($contraindication) ) ? uamswp_fad_schema_type_selector($contraindication) : '';

					// postOp

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * A description of the postoperative procedures, care, and/or followups for this 
						 * device.
						 */

						$schema['postOp'] = ( isset($postOp) && !empty($postOp) ) ? uamswp_fad_schema_type_selector($postOp) : '';

					// preOp

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * A description of the workup, testing, and other preparations required before 
						 * implanting this device.
						 */

						$schema['preOp'] = ( isset($preOp) && !empty($preOp) ) ? uamswp_fad_schema_type_selector($preOp) : '';

					// procedure

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * A description of the procedure involved in setting up, using, and/or installing 
						 * the device.
						 */

						$schema['procedure'] = ( isset($procedure) && !empty($procedure) ) ? uamswp_fad_schema_type_selector($procedure) : '';

					// seriousAdverseOutcome

						/* 
						 * Expected Type:
						 *     MedicalEntity
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

	// MedicalGuideline
	include_once __DIR__ . '/MedicalEntity/MedicalGuideline.php';

		/*
		 * Thing > MedicalEntity > MedicalGuideline
		 * 
		 * Any recommendation made by a standard society (e.g., ACC/AHA) or consensus 
		 * statement that denotes how to diagnose and treat a particular condition. 
		 * Note: this type should be used to tag the actual guideline recommendation; if 
		 * the guideline recommendation occurs in a larger scholarly article, use 
		 * MedicalScholarlyArticle to tag the overall article, not this type. Note 
		 * also: the organization making the recommendation should be captured in the 
		 * recognizingAuthority base property of MedicalEntity.
		 */

		function uamswp_fad_schema_medicalguideline(
			$schema, // array // Main schema array
			// MedicalGuideline
				$evidenceLevel = '', // evidenceLevel
				$evidenceOrigin = '', // evidenceOrigin
				$guidelineDate = '', // guidelineDate
				$guidelineSubject = '', // guidelineSubject
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

				// Properties from MedicalGuideline (Thing > MedicalEntity > MedicalGuideline)

					$evidenceLevel = ( isset($evidenceLevel) && !empty($evidenceLevel) ) ? $evidenceLevel : '';
					$evidenceOrigin = ( isset($evidenceOrigin) && !empty($evidenceOrigin) ) ? $evidenceOrigin : '';
					$guidelineDate = ( isset($guidelineDate) && !empty($guidelineDate) ) ? $guidelineDate : '';
					$guidelineSubject = ( isset($guidelineSubject) && !empty($guidelineSubject) ) ? $guidelineSubject : '';

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

				// Properties from MedicalGuideline

					// evidenceLevel

						/* 
						 * Expected Type:
						 *     MedicalEvidenceLevel
						 * 
						 * Strength of evidence of the data used to formulate the guideline (enumerated).
						 */

						$schema['evidenceLevel'] = ( isset($evidenceLevel) && !empty($evidenceLevel) ) ? uamswp_fad_schema_type_selector($evidenceLevel) : '';

					// evidenceOrigin

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * Source of the data used to formulate the guidance (e.g., RCT, consensus 
						 * opinion).
						 */

						$schema['evidenceOrigin'] = ( isset($evidenceOrigin) && !empty($evidenceOrigin) ) ? uamswp_fad_schema_type_selector($evidenceOrigin) : '';

					// guidelineDate

						/* 
						 * Expected Type:
						 *     DataType > Date
						 * 
						 * Date on which this guideline's recommendation was made.
						 */

						$schema['guidelineDate'] = ( isset($guidelineDate) && !empty($guidelineDate) ) ? uamswp_fad_schema_type_selector($guidelineDate) : '';

					// guidelineSubject

						/* 
						 * Expected Type:
						 *     MedicalEntity
						 * 
						 * The medical conditions, treatments, etc. that are the subject of the guideline.
						 */

						$schema['guidelineSubject'] = ( isset($guidelineSubject) && !empty($guidelineSubject) ) ? uamswp_fad_schema_type_selector($guidelineSubject) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

		// MedicalGuidelineContraindication

			/*
			 * Thing > MedicalEntity > MedicalGuideline > MedicalGuidelineContraindication
			 * 
			 * 
			 */

			function uamswp_fad_schema_medicalguidelinecontraindication(
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

		// MedicalGuidelineRecommendation

			/*
			 * Thing > MedicalEntity > MedicalGuideline > MedicalGuidelineRecommendation
			 * 
			 * 
			 */

			function uamswp_fad_schema_medicalguidelinerecommendation(
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

	// MedicalIndication
	include_once __DIR__ . '/MedicalEntity/MedicalIndication.php';

		/*
		 * Thing > MedicalEntity > MedicalIndication
		 * 
		 * A condition or factor that indicates use of a medical therapy, including signs, 
		 * symptoms, risk factors, anatomical states, etc.
		 */

		function uamswp_fad_schema_medicalindication(
			$schema, // array // Main schema array
			// MedicalIndication (no property vars)
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

				// Properties from MedicalIndication (Thing > MedicalEntity > MedicalIndication)

					// Do nothing (no property vars)

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

				// Properties from MedicalIndication

					// Do nothing (no property vars)

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

		// ApprovedIndication

			/*
			 * Thing > MedicalEntity > MedicalIndication > ApprovedIndication
			 * 
			 * 
			 */

			function uamswp_fad_schema_approvedindication(
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

		// PreventionIndication

			/*
			 * Thing > MedicalEntity > MedicalIndication > PreventionIndication
			 * 
			 * 
			 */

			function uamswp_fad_schema_preventionindication(
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

		// TreatmentIndication

			/*
			 * Thing > MedicalEntity > MedicalIndication > TreatmentIndication
			 * 
			 * 
			 */

			function uamswp_fad_schema_treatmentindication(
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

	// MedicalIntangible
	include_once __DIR__ . '/MedicalEntity/MedicalIntangible.php';

		/*
		 * Thing > MedicalEntity > MedicalIntangible
		 * 
		 * A utility class that serves as the umbrella for a number of 'intangible' things 
		 * in the medical space.
		 */

		function uamswp_fad_schema_medicalintangible(
			$schema, // array // Main schema array
			// MedicalIntangible (no property vars)
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

				// Properties from MedicalIntangible (Thing > MedicalEntity > MedicalIntangible)

					// Do nothing (no property vars)

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

				// Properties from MedicalIntangible

					// Do nothing (no property vars)

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

		// DDxElement

			/*
			 * Thing > MedicalEntity > MedicalIntangible > DDxElement
			 * 
			 * 
			 */

			function uamswp_fad_schema_ddxelement(
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

		// DoseSchedule

			/*
			 * Thing > MedicalEntity > MedicalIntangible > DoseSchedule
			 * 
			 * 
			 */

			function uamswp_fad_schema_doseschedule(
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

			// MaximumDoseSchedule

				/*
				 * Thing > MedicalEntity > MedicalIntangible > DoseSchedule > MaximumDoseSchedule
				 * 
				 * 
				 */

				function uamswp_fad_schema_maximumdoseschedule(
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

			// RecommendedDoseSchedule

				/*
				 * Thing > MedicalEntity > MedicalIntangible > DoseSchedule > RecommendedDoseSchedule
				 * 
				 * 
				 */

				function uamswp_fad_schema_recommendeddoseschedule(
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

			// ReportedDoseSchedule

				/*
				 * Thing > MedicalEntity > MedicalIntangible > DoseSchedule > ReportedDoseSchedule
				 * 
				 * 
				 */

				function uamswp_fad_schema_reporteddoseschedule(
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

		// DrugLegalStatus

			/*
			 * Thing > MedicalEntity > MedicalIntangible > DrugLegalStatus
			 * 
			 * 
			 */

			function uamswp_fad_schema_druglegalstatus(
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

		// DrugStrength

			/*
			 * Thing > MedicalEntity > MedicalIntangible > DrugStrength
			 * 
			 * 
			 */

			function uamswp_fad_schema_drugstrength(
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

		// MedicalCode

			/*
			 * Thing > MedicalEntity > MedicalIntangible > MedicalCode
			 * 
			 * 
			 */

			function uamswp_fad_schema_medicalcode(
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

		// MedicalConditionStage

			/*
			 * Thing > MedicalEntity > MedicalIntangible > MedicalConditionStage
			 * 
			 * 
			 */

			function uamswp_fad_schema_medicalconditionstage(
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

	// MedicalProcedure
	include_once __DIR__ . '/MedicalEntity/MedicalProcedure.php';

	// MedicalRiskEstimator
	include_once __DIR__ . '/MedicalEntity/MedicalRiskEstimator.php';

		/*
		 * Thing > MedicalEntity > MedicalRiskEstimator
		 * 
		 * Any rule set or interactive tool for estimating the risk of developing a 
		 * complication or condition.
		 */

		function uamswp_fad_schema_medicalriskestimator(
			$schema, // array // Main schema array
			// MedicalRiskEstimator
				$estimatesRiskOf = '', // estimatesRiskOf
				$includedRiskFactor = '', // includedRiskFactor
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

				// Properties from MedicalRiskEstimator (Thing > MedicalEntity > MedicalRiskEstimator)

					$estimatesRiskOf = ( isset($estimatesRiskOf) && !empty($estimatesRiskOf) ) ? $estimatesRiskOf : '';
					$includedRiskFactor = ( isset($includedRiskFactor) && !empty($includedRiskFactor) ) ? $includedRiskFactor : '';

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

				// Properties from MedicalRiskEstimator

					// estimatesRiskOf

						/* 
						 * Expected Type:
						 *     MedicalEntity
						 * 
						 * The condition, complication, or symptom whose risk is being estimated.
						 */

						$schema['estimatesRiskOf'] = ( isset($estimatesRiskOf) && !empty($estimatesRiskOf) ) ? uamswp_fad_schema_type_selector($estimatesRiskOf) : '';

					// includedRiskFactor

						/* 
						 * Expected Type:
						 *     MedicalRiskFactor
						 * 
						 * A modifiable or non-modifiable risk factor included in the calculation (e.g., 
						 * age, coexisting condition).
						 */

						$schema['includedRiskFactor'] = ( isset($includedRiskFactor) && !empty($includedRiskFactor) ) ? uamswp_fad_schema_type_selector($includedRiskFactor) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

		// MedicalRiskCalculator

			/*
			 * Thing > MedicalEntity > MedicalRiskEstimator > MedicalRiskCalculator
			 * 
			 * 
			 */

			function uamswp_fad_schema_medicalriskcalculator(
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

		// MedicalRiskScore

			/*
			 * Thing > MedicalEntity > MedicalRiskEstimator > MedicalRiskScore
			 * 
			 * 
			 */

			function uamswp_fad_schema_medicalriskscore(
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

	// MedicalRiskFactor
	include_once __DIR__ . '/MedicalEntity/MedicalRiskFactor.php';

		/*
		 * Thing > MedicalEntity > MedicalRiskFactor
		 * 
		 * A risk factor is anything that increases a person's likelihood of developing or 
		 * contracting a disease, medical condition, or complication.
		 */

		function uamswp_fad_schema_medicalriskfactor(
			$schema, // array // Main schema array
			// MedicalRiskFactor
				$increasesRiskOf = '', // increasesRiskOf
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

				// Properties from MedicalRiskFactor (Thing > MedicalEntity > MedicalRiskFactor)

					$increasesRiskOf = ( isset($increasesRiskOf) && !empty($increasesRiskOf) ) ? $increasesRiskOf : '';

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

				// Properties from MedicalRiskFactor

					// increasesRiskOf

						/* 
						 * Expected Type:
						 *     Thing > MedicalEntity
						 * 
						 * The condition, complication, etc. influenced by this factor.
						 */

						$schema['increasesRiskOf'] = ( isset($increasesRiskOf) && !empty($increasesRiskOf) ) ? uamswp_fad_schema_type_selector($increasesRiskOf) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

	// MedicalStudy
	include_once __DIR__ . '/MedicalEntity/MedicalStudy.php';

		/*
		 * Thing > MedicalEntity > MedicalStudy
		 * 
		 * A medical study is an umbrella type covering all kinds of research studies 
		 * relating to human medicine or health, including observational studies and 
		 * interventional trials and registries, randomized, controlled or not. When the 
		 * specific type of study is known, use one of the extensions of this type, such 
		 * as MedicalTrial or MedicalObservationalStudy. Also, note that this type should 
		 * be used to mark up data that describes the study itself; to tag an article that 
		 * publishes the results of a study, use MedicalScholarlyArticle. Note: use the 
		 * code property of MedicalEntity to store study IDs (e.g., clinicaltrials.gov ID).
		 */

		function uamswp_fad_schema_medicalstudy(
			$schema, // array // Main schema array
			// MedicalStudy
				$healthCondition = '', // healthCondition
				$sponsor = '', // sponsor
				$status = '', // status
				$studyLocation = '', // studyLocation
				$studySubject = '', // studySubject
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

				// Properties from MedicalStudy (Thing > MedicalEntity > MedicalStudy)

					$healthCondition = ( isset($healthCondition) && !empty($healthCondition) ) ? $healthCondition : '';
					$sponsor = ( isset($sponsor) && !empty($sponsor) ) ? $sponsor : '';
					$status = ( isset($status) && !empty($status) ) ? $status : '';
					$studyLocation = ( isset($studyLocation) && !empty($studyLocation) ) ? $studyLocation : '';
					$studySubject = ( isset($studySubject) && !empty($studySubject) ) ? $studySubject : '';

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

				// Properties from MedicalStudy

					// healthCondition

						/* 
						 * Expected Type:
						 *     MedicalCondition
						 * 
						 * Specifying the health condition(s) of a patient, medical study, or other target 
						 * audience.
						 */

						$schema['healthCondition'] = ( isset($healthCondition) && !empty($healthCondition) ) ? uamswp_fad_schema_type_selector($healthCondition) : '';

					// sponsor

						/* 
						 * Expected Type:
						 *     Thing > Organization
						 *     Thing > Person
						 * 
						 * A person or organization that supports a thing through a pledge, promise, or 
						 * financial contribution (e.g.,  a sponsor of a Medical Study or a corporate 
						 * sponsor of an event).
						 */

						$schema['sponsor'] = ( isset($sponsor) && !empty($sponsor) ) ? uamswp_fad_schema_type_selector($sponsor) : '';

					// status

						/* 
						 * Expected Type:
						 *     EventStatusType
						 *     MedicalStudyStatus
						 *     DataType > Text
						 * 
						 * The status of the study (enumerated).
						 */

						$schema['status'] = ( isset($status) && !empty($status) ) ? uamswp_fad_schema_type_selector($status) : '';

					// studyLocation

						/* 
						 * Expected Type:
						 *     Thing > Place > AdministrativeArea
						 * 
						 * The location in which the study is taking/took place.
						 */

						$schema['studyLocation'] = ( isset($studyLocation) && !empty($studyLocation) ) ? uamswp_fad_schema_type_selector($studyLocation) : '';

					// studySubject

						/* 
						 * Expected Type:
						 *     MedicalEntity
						 * 
						 * A subject of the study (i.e., one of the medical conditions, therapies, 
						 * devices, drugs, etc. investigated by the study).
						 */

						$schema['studySubject'] = ( isset($studySubject) && !empty($studySubject) ) ? uamswp_fad_schema_type_selector($studySubject) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

		// MedicalObservationalStudy

			/*
			 * Thing > MedicalEntity > MedicalStudy > MedicalObservationalStudy
			 * 
			 * 
			 */

			function uamswp_fad_schema_medicalobservationalstudy(
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

		// MedicalTrial

			/*
			 * Thing > MedicalEntity > MedicalStudy > MedicalTrial
			 * 
			 * 
			 */

			function uamswp_fad_schema_medicaltrial(
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

	// MedicalTest
	include_once __DIR__ . '/MedicalEntity/MedicalTest.php';

	// Substance
	include_once __DIR__ . '/MedicalEntity/Substance.php';

		/*
		 * Thing > MedicalEntity > Substance
		 * 
		 * Any matter of defined composition that has discrete existence, whose origin may 
		 * be biological, mineral or chemical.
		 */

		function uamswp_fad_schema_substance(
			$schema, // array // Main schema array
			// Substance
				$activeIngredient = '', // activeIngredient
				$maximumIntake = '', // maximumIntake
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

				// Properties from Substance (Thing > MedicalEntity > Substance)

					$activeIngredient = ( isset($activeIngredient) && !empty($activeIngredient) ) ? $activeIngredient : '';
					$maximumIntake = ( isset($maximumIntake) && !empty($maximumIntake) ) ? $maximumIntake : '';

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

				// Properties from Substance

					// activeIngredient

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * An active ingredient, typically chemical compounds and/or biologic substances.
						 */

						$schema['activeIngredient'] = ( isset($activeIngredient) && !empty($activeIngredient) ) ? uamswp_fad_schema_type_selector($activeIngredient) : '';

					// maximumIntake

						/* 
						 * Expected Type:
						 *     Thing > MedicalEntity > MedicalIntangible > DoseSchedule > MaximumDoseSchedule
						 * 
						 * Recommended intake of this supplement for a given population as defined by a 
						 * specific recommending authority.
						 */

						$schema['maximumIntake'] = ( isset($maximumIntake) && !empty($maximumIntake) ) ? uamswp_fad_schema_type_selector($maximumIntake) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}

		// DietarySupplement

			/*
			 * Thing > MedicalEntity > Substance > DietarySupplement
			 * 
			 * 
			 */

			function uamswp_fad_schema_dietarysupplement(
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

		// Drug

			/*
			 * Thing > MedicalEntity > Substance > Drug
			 * 
			 * 
			 */

			function uamswp_fad_schema_drug(
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

	// SuperficialAnatomy
	include_once __DIR__ . '/MedicalEntity/SuperficialAnatomy.php';

		/*
		 * Thing > MedicalEntity > SuperficialAnatomy
		 * 
		 * Anatomical features that can be observed by sight (without dissection), 
		 * including the form and proportions of the human body as well as surface 
		 * landmarks that correspond to deeper subcutaneous structures. Superficial 
		 * anatomy plays an important role in sports medicine, phlebotomy, and other 
		 * medical specialties as underlying anatomical structures can be identified 
		 * through surface palpation. For example, during back surgery, superficial 
		 * anatomy can be used to palpate and count vertebrae to find the site of 
		 * incision. Or in phlebotomy, superficial anatomy can be used to locate an 
		 * underlying vein; for example, the median cubital vein can be located by 
		 * palpating the borders of the cubital fossa (such as the epicondyles of the 
		 * humerus) and then looking for the superficial signs of the vein, such as 
		 * size, prominence, ability to refill after depression, and feel of surrounding 
		 * tissue support. As another example, in a subluxation (dislocation) of the 
		 * glenohumeral joint, the bony structure becomes pronounced with the deltoid 
		 * muscle failing to cover the glenohumeral joint allowing the edges of the 
		 * scapula to be superficially visible. Here, the superficial anatomy is the 
		 * visible edges of the scapula, implying the underlying dislocation of the joint 
		 * (the related anatomical structure).
		 */

		function uamswp_fad_schema_superficialanatomy(
			$schema, // array // Main schema array
			// SuperficialAnatomy
				$associatedPathophysiology = '', // associatedPathophysiology
				$relatedAnatomy = '', // relatedAnatomy
				$relatedCondition = '', // relatedCondition
				$relatedTherapy = '', // relatedTherapy
				$significance = '', // significance
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

				// Properties from SuperficialAnatomy (Thing > MedicalEntity > SuperficialAnatomy)

					$associatedPathophysiology = ( isset($associatedPathophysiology) && !empty($associatedPathophysiology) ) ? $associatedPathophysiology : '';
					$relatedAnatomy = ( isset($relatedAnatomy) && !empty($relatedAnatomy) ) ? $relatedAnatomy : '';
					$relatedCondition = ( isset($relatedCondition) && !empty($relatedCondition) ) ? $relatedCondition : '';
					$relatedTherapy = ( isset($relatedTherapy) && !empty($relatedTherapy) ) ? $relatedTherapy : '';
					$significance = ( isset($significance) && !empty($significance) ) ? $significance : '';

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

				// Properties from SuperficialAnatomy

					// associatedPathophysiology

						/* 
						 * Expected Type:
						 *     DataType > Text
						 * 
						 * If applicable, a description of the pathophysiology associated with the 
						 * anatomical system, including potential abnormal changes in the mechanical, 
						 * physical, and biochemicalfunctions of the system.
						 */

						$schema['associatedPathophysiology'] = ( isset($associatedPathophysiology) && !empty($associatedPathophysiology) ) ? uamswp_fad_schema_type_selector($associatedPathophysiology) : '';

					// relatedAnatomy

						/* 
						 * Expected Type:
						 *     AnatomicalStructure
						 *     AnatomicalSystem
						 * 
						 * Anatomical systems or structures that relate to the superficial anatomy.
						 */

						$schema['relatedAnatomy'] = ( isset($relatedAnatomy) && !empty($relatedAnatomy) ) ? uamswp_fad_schema_type_selector($relatedAnatomy) : '';

					// relatedCondition

						/* 
						 * Expected Type:
						 *     MedicalCondition
						 * 
						 * A medical condition associated with this anatomy.
						 */

						$schema['relatedCondition'] = ( isset($relatedCondition) && !empty($relatedCondition) ) ? uamswp_fad_schema_type_selector($relatedCondition) : '';

					// relatedTherapy

						/* 
						 * Expected Type:
						 *     Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy
						 * 
						 * A medical therapy related to this anatomy.
						 */

						$schema['relatedTherapy'] = ( isset($relatedTherapy) && !empty($relatedTherapy) ) ? uamswp_fad_schema_type_selector($relatedTherapy) : '';

					// significance

						/* 
						 * Expected Type:
						 *     Thing > MedicalEntity > MedicalProcedure > TherapeuticProcedure > MedicalTherapy
						 * 
						 * A medical therapy related to this anatomy.
						 */

						$schema['significance'] = ( isset($significance) && !empty($significance) ) ? uamswp_fad_schema_type_selector($significance) : '';

			// Remove any empty values from the schema array

				$schema = array_filter($schema);
				$schema = array_unique($schema, SORT_REGULAR);

			return $schema;

		}