<?php

/**
 * Functions for Schema.org Schema and Google Local Business structured data
 *
 * Generate schema array of Condition ontology page type (MedicalCondition)
 */

function uamswp_fad_schema_condition(
	array $repeater, // array // Required // List of IDs of the MedicalCondition items
	string $page_url, // string // Required // Page URL
	array &$node_identifier_list = array(), // array // Optional // List of node identifiers (@id) already defined in the schema
	int $nesting_level = 1, // int // Optional // Nesting level within the main schema
	int &$MedicalCondition_i = 1, // int // Optional // Iteration counter for condition-as-MedicalCondition
	int &$Service_i = 1, // int // Optional // Iteration counter for treatment-as-Service
	array $condition_fields = array(), // array // Optional // Pre-existing field values array so duplicate calls can be avoided
	array $condition_list = array() // array // Optional // Pre-existing list array for combined condition schema to which to add additional items
) {

	if ( !empty($repeater) ) {

		// Base schema function variables

			include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base_function.php' );

		// List of valid types

			/**
			 * Define the list of high-level types that are considered valid. The list may be
			 * expanded later to include the subtypes of these high-level types.
			 */

			// List of Schema.org types for which to not get the subtypes

				$condition_valid_types = array(
					'MedicalEntity'
				);

			// List of Schema.org types for which to get the subtypes

				$condition_valid_types_plus_subtypes = array(
					'MedicalCondition'
				);

			// Base array for schema.org type URLs

				$condition_valid_types_url = array();

			// Get a list of schema.org subtypes and URLs

				uamswp_fad_schema_subtypes_and_urls(
					$condition_valid_types, // array // Required // List of Schema.org types for which to not get the subtypes
					$condition_valid_types_plus_subtypes, // array // Optional // List of Schema.org types for which to get the subtypes
					$condition_valid_types_url // string|array // Optional // Pre-existing list of schema.org URLs to which to add additional items
				);

		// List of valid properties for each type

			// Base array

				$condition_properties_map = array();

			// Get list of valid properties from Schema.org type list

				foreach ( $condition_valid_types as $item ) {

					$condition_properties_map[$item]['properties'] = $schema_org_types[$item]['properties'] ?? array();
					$condition_properties_map[$item]['properties'] = is_array($condition_properties_map[$item]['properties']) ? $condition_properties_map[$item]['properties'] : array($condition_properties_map[$item]['properties']);

				}

		// Loop through each treatment to add values

			foreach ( $repeater as $entity ) {

				if ( !$entity ) {

					continue;

				}

				// Retrieve the value of the item transient

					uamswp_fad_get_transient(
						'item_' . $entity, // Required // String added to transient name for disambiguation.
						$condition_item_MedicalCondition, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

				if (
					!empty( $condition_item_MedicalCondition )
				) {

					/**
					 * The transient exists.
					 * Return the variable.
					 */

					// Add to list of conditions

						$condition_list[] = $condition_item_MedicalCondition;

				} else {

					/**
					 * The transient does not exist.
					 * Define the variable again.
					 */

					// If post is not published, skip to the next iteration

						if ( get_post_status($entity) != 'publish' ) {

							continue;

						}

					// Eliminate PHP errors / reset variables

						$condition_item = array(); // Base array
						$condition_item_MedicalCondition = in_array( 'MedicalCondition', $condition_valid_types ) ? array() : null; // Base MedicalCondition array
						$condition_additionalType_repeater = array();
						$condition_additionalType = array();
						$condition_alternateName = array();
						$condition_alternateName_repeater = array();
						$condition_code = array();
						$condition_code_repeater = array();
						$condition_id = '';
						$condition_infectiousAgent = '';
						$condition_infectiousAgentClass = '';
						$condition_name = '';
						$condition_possibleTreatment = array();
						$condition_possibleTreatment_relationship = array();
						$condition_primaryPrevention = array();
						$condition_primaryPrevention_relationship = array();
						$condition_sameAs = array();
						$condition_sameAs_repeater = array();
						$condition_secondaryPrevention = array();
						$condition_secondaryPrevention_relationship = array();
						$MedicalCondition_type = '';
						$MedicalCondition_type_parent = array();
						$condition_typicalTest = array();
						$condition_typicalTest_relationship = array();

					// Load variables from pre-existing field values array

						if (
							isset($condition_fields[$entity])
							&&
							!empty($condition_fields[$entity])
						) {

							foreach ( $condition_fields[$entity] as $key => $value ) {

								${$key} = $value; // Create a variable for each item in the array

							}

						}

					// Get ontology type

						if ( !isset($condition_ontology_type) ) {

							$condition_ontology_type = true;

						}

					// If the page is not an ontology type, skip to the next iteration

						if ( !$condition_ontology_type ) {

							continue;

						}

					// Fake subpage query and get fake subpage slug

						if (
							$condition_ontology_type
							&&
							$nesting_level == 0
						) {

							if ( !isset($condition_current_fpage) ) {

								$condition_current_fpage = get_query_var( 'fpage' ) ?? ''; // Fake subpage slug

							}

							if ( !isset($condition_fpage_query) ) {

								$condition_fpage_query = $condition_current_fpage ? true : false;

							}

						}

					// Add property values

						/**
						 * The following properties are either beyond the scope of what is being included
						 * in the facility item schema; irrelevant to the facility item schema; are
						 * superseded by another property; or are already being defined in the common
						 * schema properties (templates/parts/vars/page/schema/common/properties.php) and
						 * so they will not be included here:
						 *
						 *      * associatedAnatomy
						 *      * description
						 *      * differentialDiagnosis
						 *      * disambiguatingDescription
						 *      * drug
						 *      * epidemiology
						 *      * expectedPrognosis
						 *      * funding
						 *      * guideline
						 *      * identifyingExam
						 *      * identifyingTest
						 *      * image
						 *      * legalStatus
						 *      * mainEntityOfPage
						 *      * medicineSystem
						 *      * naturalProgression
						 *      * pathophysiology
						 *      * possibleComplication
						 *      * potentialAction
						 *      * recognizingAuthority
						 *      * relevantSpecialty
						 *      * riskFactor
						 *      * stage
						 *      * status
						 *      * study
						 *      * subjectOf
						 *      * transmissionMethod
						 */

						// url

							/**
							 * URL of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - URL
							 *
							 * The condition custom post type does not have a published URL and so this schema
							 * property will not be included.
							 */

						// @type

							// Base value

								$MedicalCondition_type = 'MedicalCondition';
								$page_fragment = $MedicalCondition_type;

							// Get the MedicalCondition subtype

								$MedicalCondition_type = get_field( 'schema_medicalcondition_subtype', $entity ) ?: $MedicalCondition_type;
								$MedicalCondition_type_parent = $MedicalCondition_type != 'MedicalCondition' ? array( 'MedicalCondition' ) : array();

							// Add to item values

								if (
									isset($condition_item_MedicalCondition)
									&&
									$MedicalCondition_type
								) {

									$condition_item_MedicalCondition['@type'] = $MedicalCondition_type;

								}

						// @id

							// Get values

								$condition_id = $page_url . '#' . $page_fragment;
								$condition_id .= $MedicalCondition_i;
								$MedicalCondition_i++;

							// Add to item values

								if (
									isset($condition_item_MedicalCondition)
									&&
									$condition_id
								) {

									$condition_item_MedicalCondition['@id'] = $condition_id;
									$node_identifier_list[] = $condition_item_MedicalCondition['@id']; // Add to the list of existing node identifiers

								}

						// Add common properties

							include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/properties.php' );

							// All types

								/*

									Loop through an associative array of properties common to all of our schema
									types, adding each row to this item's schema when the key matches a property
									valid for the type, replacing full values with only the node identifier where
									appropriate.

								*/

								if (
									isset($schema_common_properties)
									&&
									!empty($schema_common_properties)
								) {

									foreach ( $schema_common_properties as $key => $value ) {

										// Add to item values

											// MedicalCondition

												uamswp_fad_schema_add_to_item_values(
													$MedicalCondition_type, // string // Required // The @type value for the schema item
													$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								}

							// Types other than MedicalWebPage

								/*

									Loop through an associative array of properties specific to the types other
									than the MedicalWebPage type, adding each row to this item's schema when the
									key matches a property valid for the type, replacing full values with only the
									node identifier where appropriate.

								*/

								if (
									isset($schema_common_properties_exclude_MedicalWebPage)
									&&
									!empty($schema_common_properties_exclude_MedicalWebPage)
								) {

									foreach ( $schema_common_properties_exclude_MedicalWebPage as $key => $value ) {

										// Add to item values

											// MedicalCondition

												uamswp_fad_schema_add_to_item_values(
													$MedicalCondition_type, // string // Required // The @type value for the schema item
													$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								}

							// Main entity type

								/*

									Loop through an associative array of properties specific to the main entity
									type, adding each row to this item's schema when the key matches a property
									valid for the type, replacing full values with only the node identifier where
									appropriate.

								*/

								if (
									isset($schema_common_properties_main_entity)
									&&
									!empty($schema_common_properties_main_entity)
								) {

									foreach ( $schema_common_properties_main_entity as $key => $value ) {

										// Add to item values

											// MedicalCondition

												uamswp_fad_schema_add_to_item_values(
													$MedicalCondition_type, // string // Required // The @type value for the schema item
													$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								}

						// name

							/**
							 * The name of the item.
							 *
							 * Subproperty of:
							 *
							 *      - rdfs:label
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 */

							if (
								(
									isset($condition_item_MedicalCondition)
									&&
									in_array(
										'name',
										$condition_properties_map[$MedicalCondition_type]['properties']
									)
								)
							) {

								// Get values

									$condition_name = get_the_title($entity) ?? '';

								// Add to item values

									// MedicalCondition

										uamswp_fad_schema_add_to_item_values(
											$MedicalCondition_type, // string // Required // The @type value for the schema item
											$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
											'name', // string // Required // Name of schema property
											$condition_name, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// additionalType

							/**
							 * An additional type for the item, typically used for adding more specific types
							 * from external vocabularies in microdata syntax. This is a relationship between
							 * something and a class that the thing is in. Typically the value is a
							 * URI-identified RDF class, and in this case corresponds to the use of rdf:type
							 * in RDF. Text values can be used sparingly, for cases where useful information
							 * can be added without their being an appropriate schema to reference. In the
							 * case of text values, the class label should follow the schema.org style guide.
							 *
							 * Subproperty of:
							 *      - rdf:type
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *      - URL
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 */

							if (
								(
									isset($condition_item_MedicalCondition)
									&&
									in_array(
										'additionalType',
										$condition_properties_map[$MedicalCondition_type]['properties']
									)
								)
							) {

								// Get values

									// Get additionalType repeater field value

										$condition_additionalType_repeater = get_field( 'schema_additionalType', $entity ) ?? array();

									// Add each item to additionalType property values array

										if ( $condition_additionalType_repeater ) {

											$condition_additionalType = uamswp_fad_schema_additionaltype(
												$condition_additionalType_repeater, // additionalType repeater field
												'schema_additionalType_uri' // additionalType item field name
											);

										}

								// Add to item values

									// MedicalCondition

										uamswp_fad_schema_add_to_item_values(
											$MedicalCondition_type, // string // Required // The @type value for the schema item
											$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
											'additionalType', // string // Required // Name of schema property
											$condition_additionalType, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// alternateName

							/**
							 * An alias for the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 */

							if (
								(
									isset($condition_item_MedicalCondition)
									&&
									in_array(
										'alternateName',
										$condition_properties_map[$MedicalCondition_type]['properties']
									)
								)
							) {

								// Get values

									// Get alternateName repeater field value

										$condition_alternateName_repeater = get_field( 'condition_alternate', $entity ) ?? array();

									// Add each item to alternateName property values array

										if ( $condition_alternateName_repeater ) {

											$condition_alternateName = uamswp_fad_schema_alternatename(
												$condition_alternateName_repeater, // array // Required // alternateName repeater field
												'alternate_text' // string // Optional // alternateName item field name
											);

										}

								// Add to item values

									// MedicalCondition

										uamswp_fad_schema_add_to_item_values(
											$MedicalCondition_type, // string // Required // The @type value for the schema item
											$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
											'alternateName', // string // Required // Name of schema property
											$condition_alternateName, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// code

							/**
							 * A medical code for the entity, taken from a controlled vocabulary or ontology
							 * such as ICD-9, DiseasesDB, MeSH, SNOMED-CT, RxNorm, etc.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalCode
							 *
							 * Used on these types:
							 *
							 *      - MedicalEntity
							 */

							if (
								(
									isset($condition_item_MedicalCondition)
									&&
									in_array(
										'code',
										$condition_properties_map[$MedicalCondition_type]['properties']
									)
								)
							) {

								// Get values

									// Get code repeater field value

										$condition_code_repeater = get_field( 'schema_medicalcode', $entity ) ?? array();

									// Add each item to code property values array

										if ( $condition_code_repeater ) {

											$condition_code = uamswp_fad_schema_code(
												$condition_code_repeater // array // Optional // code repeater field
											);

										}

								// Add to item values

									// MedicalCondition

										uamswp_fad_schema_add_to_item_values(
											$MedicalCondition_type, // string // Required // The @type value for the schema item
											$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
											'code', // string // Required // Name of schema property
											$condition_code, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// identifier [WIP]

							/**
							 * The identifier property represents any kind of identifier for any kind of
							 * Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated
							 * properties for representing many of these, either as textual strings or as URL
							 * (URI) links. See background notes for more details.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - PropertyValue
							 *      - Text
							 *      - URL
							 */

						// identifyingExam [WIP]

							/**
							 * A physical examination that can identify this sign.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - PhysicalExam
							 */

						// identifyingTest [WIP]

							/**
							 * A diagnostic test that can identify this sign.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalTest
							 */

						// infectiousAgent

							/**
							 * The actual infectious agent, such as a specific bacterium.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 */

							if (
								(
									isset($condition_item_MedicalCondition)
									&&
									in_array(
										'infectiousAgent',
										$condition_properties_map[$MedicalCondition_type]['properties']
									)
								)
							) {

								// Get values

									$condition_infectiousAgent = get_field( 'schema_infectiousagent', $entity ) ?: '';

								// Add to item values

									// MedicalCondition

										uamswp_fad_schema_add_to_item_values(
											$MedicalCondition_type, // string // Required // The @type value for the schema item
											$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
											'infectiousAgent', // string // Required // Name of schema property
											$condition_infectiousAgent, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// infectiousAgentClass

							/**
							 * The class of infectious agent (bacteria, prion, etc.) that causes the disease.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - InfectiousAgentClass
							 */

							if (
								(
									isset($condition_item_MedicalCondition)
									&&
									in_array(
										'infectiousAgentClass',
										$condition_properties_map[$MedicalCondition_type]['properties']
									)
								)
							) {

								// Get values

									$condition_infectiousAgentClass =  get_field( 'condition_schema_infectiousagentclass_schema_infectiousagentclass', $entity ) ?: '';

								// Add to item values

									// MedicalCondition

										uamswp_fad_schema_add_to_item_values(
											$MedicalCondition_type, // string // Required // The @type value for the schema item
											$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
											'infectiousAgentClass', // string // Required // Name of schema property
											$condition_infectiousAgentClass, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// sameAs

							/**
							 * URL of a reference Web page that unambiguously indicates the item's identity
							 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
							 * website).
							 *
							 * Values expected to be one of these types:
							 *
							 *      - URL
							 */

							if (
								(
									isset($condition_item_MedicalCondition)
									&&
									in_array(
										'sameAs',
										$condition_properties_map[$MedicalCondition_type]['properties']
									)
								)
							) {

								// Get values

									// Get sameAs repeater field value

										$condition_sameAs_repeater = get_field( 'schema_sameas', $entity ) ?? array();

									// Add each item to sameAs property values array

										if ( $condition_sameAs_repeater ) {

											$condition_sameAs = uamswp_fad_schema_sameas(
												$condition_sameAs_repeater, // sameAs repeater field
												'schema_sameas_url' // sameAs item field name
											);

										}

								// Add to item values

									// MedicalCondition

										uamswp_fad_schema_add_to_item_values(
											$MedicalCondition_type, // string // Required // The @type value for the schema item
											$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
											'sameAs', // string // Required // Name of schema property
											$condition_sameAs, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// signOrSymptom [WIP]

							/**
							 * A sign or symptom of this condition. Signs are objective or physically
							 * observable manifestations of the medical condition while symptoms are the
							 * subjective experience of the medical condition.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalSignOrSymptom
							 */

						// possibleTreatment

							/**
							 * A possible treatment to address this condition, sign or symptom.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalTherapy
							 */

							if (
								(
									isset($condition_item_MedicalCondition)
									&&
									in_array(
										'possibleTreatment',
										$condition_properties_map[$MedicalCondition_type]['properties']
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Get values

									// Get possibleTreatment relationship field value

										$condition_possibleTreatment_relationship = get_field( 'condition_schema_possibletreatment', $entity ) ?? array();

									// Add each item to possibleTreatment property values array

										if ( $condition_possibleTreatment_relationship ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_treatment') ) {

												$condition_possibleTreatment = uamswp_fad_schema_treatment(
													$condition_possibleTreatment_relationship, // array // Required // List of IDs of the service items
													$page_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$Service_i, // int // Optional // Iteration counter for treatment-as-Service
													$MedicalCondition_i // int // Optional // Iteration counter for condition-as-MedicalCondition
												);

											} else {

												$condition_possibleTreatment = null;

											}

										}

								// Add to item values

									// MedicalCondition

										uamswp_fad_schema_add_to_item_values(
											$MedicalCondition_type, // string // Required // The @type value for the schema item
											$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
											'possibleTreatment', // string // Required // Name of schema property
											$condition_possibleTreatment, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// primaryPrevention

							/**
							 * A preventative therapy used to prevent an initial occurrence of the medical
							 * condition, such as vaccination.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalTherapy
							 */

							if (
								(
									isset($condition_item_MedicalCondition)
									&&
									in_array(
										'primaryPrevention',
										$condition_properties_map[$MedicalCondition_type]['properties']
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Get values

									// Get primaryPrevention relationship field value

										$condition_primaryPrevention_relationship = get_field( 'condition_schema_primaryprevention', $entity ) ?? array();

									// Add each item to primaryPrevention property values array

										if ( $condition_primaryPrevention_relationship ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_treatment') ) {

												$condition_primaryPrevention = uamswp_fad_schema_treatment(
													$condition_primaryPrevention_relationship, // array // Required // List of IDs of the service items
													$page_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$Service_i, // int // Optional // Iteration counter for treatment-as-Service
													$MedicalCondition_i // int // Optional // Iteration counter for condition-as-MedicalCondition
												);

											} else {

												$condition_primaryPrevention = null;

											}

										}

								// Add to item values

									// MedicalCondition

										uamswp_fad_schema_add_to_item_values(
											$MedicalCondition_type, // string // Required // The @type value for the schema item
											$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
											'primaryPrevention', // string // Required // Name of schema property
											$condition_primaryPrevention, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// secondaryPrevention

							/**
							 * A preventative therapy used to prevent reoccurrence of the medical condition
							 * after an initial episode of the condition.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalTherapy
							 */

							if (
								(
									isset($condition_item_MedicalCondition)
									&&
									in_array(
										'secondaryPrevention',
										$condition_properties_map[$MedicalCondition_type]['properties']
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Get values

									// Get secondaryPrevention relationship field value

										$condition_secondaryPrevention_relationship = get_field( 'condition_schema_secondaryprevention', $entity ) ?? array();

									// Add each item to secondaryPrevention property values array

										if ( $condition_secondaryPrevention_relationship ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_treatment') ) {

												$condition_secondaryPrevention = uamswp_fad_schema_treatment(
													$condition_secondaryPrevention_relationship, // array // Required // List of IDs of the service items
													$page_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$Service_i, // int // Optional // Iteration counter for treatment-as-Service
													$MedicalCondition_i // int // Optional // Iteration counter for condition-as-MedicalCondition
												);

											} else {

												$condition_secondaryPrevention = null;

											}

										}

								// Add to item values

									// MedicalCondition

										uamswp_fad_schema_add_to_item_values(
											$MedicalCondition_type, // string // Required // The @type value for the schema item
											$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
											'secondaryPrevention', // string // Required // Name of schema property
											$condition_secondaryPrevention, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// typicalTest

							/**
							 * A medical test typically performed given this condition.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalTest
							 */

							if (
								(
									isset($condition_item_MedicalCondition)
									&&
									in_array(
										'typicalTest',
										$condition_properties_map[$MedicalCondition_type]['properties']
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Get values

									// Get typicalTest relationship field value

										$condition_typicalTest_relationship = get_field( 'condition_schema_typicaltest', $entity ) ?? array();

									// Add each item to typicalTest property values array

										if ( $condition_typicalTest_relationship ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_treatment') ) {

												$condition_typicalTest = uamswp_fad_schema_treatment(
													$condition_typicalTest_relationship, // array // Required // List of IDs of the service items
													$page_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$Service_i, // int // Optional // Iteration counter for treatment-as-Service
													$MedicalCondition_i // int // Optional // Iteration counter for condition-as-MedicalCondition
												);

											} else {

												$condition_typicalTest = null;

											}

										}

								// Add to item values

									// MedicalCondition

										uamswp_fad_schema_add_to_item_values(
											$MedicalCondition_type, // string // Required // The @type value for the schema item
											$condition_item_MedicalCondition, // array // Required // The list array for the schema item to which to add the property value
											'typicalTest', // string // Required // Name of schema property
											$condition_typicalTest, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$condition_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

					// Sort and combine the arrays

						if ( isset($condition_item_MedicalCondition) ) {

							ksort( $condition_item_MedicalCondition, SORT_NATURAL | SORT_FLAG_CASE );

						}

					// Set/update the value of the item transient

						uamswp_fad_set_transient(
							'item_' . $entity, // Required // String added to transient name for disambiguation.
							$condition_item_MedicalCondition, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
							__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
						);

					// Add to list of conditions

						$condition_list[] = $condition_item_MedicalCondition;

				}

			} // endforeach ( $repeater as $entity )

		// Clean up list array

			$condition_list = array_filter($condition_list);
			$condition_list = array_values($condition_list);

			// If there is only one item, flatten the multi-dimensional array by one step

				uamswp_fad_flatten_multidimensional_array($condition_list);

	} // endif ( !empty($repeater) )

	return $condition_list;

}
