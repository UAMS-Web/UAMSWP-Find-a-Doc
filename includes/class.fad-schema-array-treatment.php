<?php

/**
 * Functions for Schema.org Schema and Google Local Business structured data
 *
 * Generate schema array of Treatment ontology page type (MedicalProcedure, MedicalTest)
 */

function uamswp_fad_schema_treatment(
	array $repeater, // array // Required // List of IDs of the service items
	string $page_url, // string // Required // Page URL
	array &$node_identifier_list = array(), // array // Optional // List of node identifiers (@id) already defined in the schema
	int $nesting_level = 1, // int // Optional // Nesting level within the main schema
	int &$Service_i = 1, // int // Optional // Iteration counter for treatment-as-Service
	int &$MedicalCondition_i = 1, // int // Optional // Iteration counter for condition-as-MedicalCondition
	array $treatment_fields = array(), // array // Optional // Pre-existing field values array so duplicate calls can be avoided
	array $treatment_list = array() // array // Optional // Pre-existing list array for treatment schema to which to add additional items
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

				$treatment_valid_types = array(
					'MedicalEntity'
				);

			// List of Schema.org types for which to get the subtypes

				$treatment_valid_types_plus_subtypes = array(
					'MedicalTest',
					'MedicalProcedure'
				);

			// Base array for schema.org type URLs

				$treatment_valid_types_url = array();

			// Get a list of schema.org subtypes and URLs

				uamswp_fad_schema_subtypes_and_urls(
					$treatment_valid_types, // array // Required // List of Schema.org types for which to not get the subtypes
					$treatment_valid_types_plus_subtypes, // array // Optional // List of Schema.org types for which to get the subtypes
					$treatment_valid_types_url // string|array // Optional // Pre-existing list of schema.org URLs to which to add additional items
				);

		// List of valid properties for each type

			// Base array

				$treatment_properties_map = array();

			// Get list of valid properties from Schema.org type list

				foreach ( $treatment_valid_types as $item ) {

					$treatment_properties_map[$item]['properties'] = $schema_org_types[$item]['properties'] ?? array();
					$treatment_properties_map[$item]['properties'] = is_array($treatment_properties_map[$item]['properties']) ? $treatment_properties_map[$item]['properties'] : array($treatment_properties_map[$item]['properties']);

				}

		// Loop through each treatment to add values

			foreach ( $repeater as $entity ) {

				if ( !$entity ) {

					continue;

				}

				// Retrieve the value of the item transient

					uamswp_fad_get_transient(
						'item_' . $entity, // Required // String added to transient name for disambiguation.
						$treatment_item_Service, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

				if ( !empty( $treatment_item_Service ) ) {

					/**
					 * The transient exists.
					 * Return the variable.
					 */

					// Add to list of treatments and procedures

						$treatment_list[] = $treatment_item_Service;

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

						$treatment_item = array(); // Base array
						$treatment_item_Service = in_array( 'Service', $treatment_valid_types ) ? array() : null; // Base Service array
						$treatment_additionalType = null;
						$treatment_additionalType_repeater = null;
						$treatment_alternateName = null;
						$treatment_alternateName_repeater = null;
						$treatment_code = null;
						$treatment_code_repeater = null;
						$treatment_drug = null;
						$treatment_drug_item = null;
						$treatment_drug_item_nonProprietaryName_list = null;
						$treatment_drug_item_proprietaryName_list = null;
						$treatment_drug_repeater = null;
						$treatment_duplicateTherapy = null;
						$treatment_duplicateTherapy_relationship = null;
						$treatment_id = null;
						$treatment_imagingTechnique = null;
						$treatment_name = null;
						$treatment_procedureType = null;
						$treatment_relevantSpecialty = null;
						$treatment_relevantSpecialty_multiselect = null;
						$treatment_sameAs = null;
						$treatment_sameAs_repeater = null;
						$treatment_subTest = null;
						$treatment_subTest_relationship = null;
						$treatment_tissueSample = null;
						$treatment_tissueSample_repeater = null;
						$treatment_usedToDiagnose = null;
						$treatment_usedToDiagnose_relationship = null;
						$treatment_usesDevice = null;
						$treatment_usesDevice_item = null;
						$treatment_usesDevice_item_alternateName = null;
						$treatment_usesDevice_item_alternateName_repeater = null;
						$treatment_usesDevice_item_code = null;
						$treatment_usesDevice_item_code_repeater = null;
						$treatment_usesDevice_repeater = null;
						$MedicalCondition_i = 1;
						$Service_type = null;
						$Service_type_parent = null;

					// Load variables from pre-existing field values array

						if (
							isset($treatment_fields[$entity])
							&&
							!empty($treatment_fields[$entity])
						) {

							foreach ( $treatment_fields[$entity] as $key => $value ) {

								${$key} = $value; // Create a variable for each item in the array

							}

						}

					// Get ontology type

						if ( !isset($treatment_ontology_type) ) {

							$treatment_ontology_type = true;

						}

					// If the page is not an ontology type, skip to the next iteration

						if ( !$treatment_ontology_type ) {

							continue;

						}

					// Fake subpage query and get fake subpage slug

						if (
							$treatment_ontology_type
							&&
							$nesting_level == 0
						) {

							if ( !isset($treatment_current_fpage) ) {

								$treatment_current_fpage = get_query_var( 'fpage' ) ?? ''; // Fake subpage slug

							}

							if ( !isset($treatment_fpage_query) ) {

								$treatment_fpage_query = $treatment_current_fpage ? true : false;

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
						 *      * adverseOutcome
						 *      * affectedBy
						 *      * bodyLocation
						 *      * contraindication
						 *      * description
						 *      * disambiguatingDescription
						 *      * doseSchedule
						 *      * followup
						 *      * funding
						 *      * guideline
						 *      * howPerformed
						 *      * image
						 *      * mainEntityOfPage
						 *      * normalRange
						 *      * potentialAction
						 *      * preparation
						 *      * seriousAdverseOutcome
						 *      * status
						 *      * study
						 *      * subjectOf
						 *      * url
						 */

						// @type

							// Base value

								$Service_type = 'MedicalEntity';

							// Get the MedicalEntity subtype

								$Service_type = get_field( 'schema_medicalentity_subtype_availableservice', $entity ) ?: $Service_type;
								$Service_type_parent = array();

								// Get the MedicalTest subtype

									if ( $Service_type == 'MedicalTest' ) {

										$Service_type_parent[] = 'MedicalEntity';
										$Service_type = get_field( 'schema_medicaltest_subtype', $entity ) ?: $Service_type;

										if ( $Service_type != 'MedicalTest' ) {

											$Service_type_parent[] = 'MedicalTest';

										}

									}

								// Get the MedicalProcedure subtype

									if ( $Service_type == 'MedicalProcedure' ) {

										$Service_type_parent[] = 'MedicalEntity';
										$Service_type = get_field( 'schema_medicalprocedure_subtype', $entity ) ?: $Service_type;

										if ( $Service_type != 'MedicalProcedure' ) {

											$Service_type_parent[] = 'MedicalProcedure';

										}

										// Get the TherapeuticProcedure subtype

											if ( $Service_type == 'TherapeuticProcedure' ) {

												$Service_type = get_field( 'schema_therapeuticprocedure_subtype', $entity ) ?: $Service_type;

												if ( $Service_type != 'TherapeuticProcedure' ) {

													$Service_type_parent[] = 'TherapeuticProcedure';

												}

												// Get the MedicalTherapy subtype

													if ( $Service_type == 'MedicalTherapy' ) {

														$Service_type = get_field( 'schema_medicaltherapy_subtype', $entity ) ?: $Service_type;

														if ( $Service_type != 'MedicalTherapy' ) {

															$Service_type_parent[] = 'MedicalTherapy';

														}

													}

											}

									}

							// Add to schema

								if (
									isset($treatment_item_Service)
									&&
									$Service_type
								) {

									$treatment_item_Service['@type'] = $Service_type;

								}

						// @id

							// Get values

								$page_fragment = 'Service';

								$treatment_id = $page_url . '#' . $page_fragment;
								$treatment_id .= $Service_i;
								$Service_i++;

							// Add to item values

								if (
									isset($treatment_item_Service)
									&&
									$treatment_id
								) {

									$treatment_item_Service['@id'] = $treatment_id;
									$node_identifier_list[] = $treatment_item_Service['@id']; // Add to the list of existing node identifiers

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

											// Service

												uamswp_fad_schema_add_to_item_values(
													$Service_type, // string // Required // The @type value for the schema item
													$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
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

											// Service

												uamswp_fad_schema_add_to_item_values(
													$Service_type, // string // Required // The @type value for the schema item
													$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
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

											// Service

												uamswp_fad_schema_add_to_item_values(
													$Service_type, // string // Required // The @type value for the schema item
													$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
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
									isset($treatment_item_Service)
									&&
									in_array(
										'name',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
							) {

								// Get values

									$treatment_name = get_the_title($entity) ?? '';

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'name', // string // Required // Name of schema property
											$treatment_name, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
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
									isset($treatment_item_Service)
									&&
									in_array(
										'additionalType',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
							) {

								// Get values

									// Get additionalType repeater field value

										$treatment_additionalType_repeater = get_field( 'schema_additionalType', $entity ) ?? array();

									// Add each item to additionalType property values array

										if ( $treatment_additionalType_repeater ) {

											$treatment_additionalType = uamswp_fad_schema_additionaltype(
												$treatment_additionalType_repeater, // additionalType repeater field
												'schema_additionalType_uri' // additionalType item field name
											);

										}

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'additionalType', // string // Required // Name of schema property
											$treatment_additionalType, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
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
									isset($treatment_item_Service)
									&&
									in_array(
										'alternateName',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
							) {

								// Get values

									// Get alternateName repeater field value

										$treatment_alternateName_repeater = get_field( 'treatment_procedure_alternate', $entity ) ?? array();

									// Add each item to alternateName property values array

										if ( $treatment_alternateName_repeater ) {

											$treatment_alternateName = uamswp_fad_schema_alternatename(
												$treatment_alternateName_repeater, // array // Required // alternateName repeater field
												'alternate_text' // string // Optional // alternateName item field name
											);

										}

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'alternateName', // string // Required // Name of schema property
											$treatment_alternateName, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
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
									isset($treatment_item_Service)
									&&
									in_array(
										'code',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
							) {

								// Get values

									// Get code repeater field value

										$treatment_code_repeater = get_field( 'schema_medicalcode', $entity ) ?? array();

									// Add each item to code property values array

										if ( $treatment_code_repeater ) {

											$treatment_code = uamswp_fad_schema_code(
												'code', // enum('code', 'identifier') // Required // Schema property format to output
												$treatment_code_repeater // array // Optional // code repeater field
											);

										}

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'code', // string // Required // Name of schema property
											$treatment_code, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// description [excluded; unset]

							/**
							 * A description of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *      - TextObject
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 *
							 * Sub-properties:
							 *
							 *      - disambiguatingDescription
							 *      - interpretedAsClaim
							 *      - originalMediaContextDescription
							 *      - sha256
							 *
							 * This schema property is beyond the scope of what is being included for
							 * treatment schema and so it will not be included.
							 */

							// Unset value defined in common properties

								if ( isset($treatment_item_Service['description']) ) {

									unset($treatment_item_Service['description']);

								}

						// drug

							/**
							 * Specifying a drug or medicine used in a medication procedure.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Drug
							 */

							if (
								(
									isset($treatment_item_Service)
									&&
									in_array(
										'drug',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
							) {

								// Get values

									// Base array

										$treatment_drug = array();

									// Get drug repeater field value — Drug(s) or Medicine(s) Used

										$treatment_drug_repeater = get_field( 'schema_drug', $entity ) ?? array();

										// Add each item to drug property values array

											if ( $treatment_drug_repeater ) {

												foreach ( $treatment_drug_repeater as $item ) {

													// Base drug property value item array

														$treatment_drug_item = array();

													// Define property values of drug items

														// Define proprietaryName schema property value

															// Base proprietaryName list array

																$treatment_drug_item_proprietaryName_list = array();

															// Get proprietaryName repeater value

																$treatment_drug_item['proprietaryName'] = $item['schema_drug_proprietaryname'] ?? array();

															// Loop through rows of proprietaryName repeater value

																if ( $treatment_drug_item['proprietaryName'] ) {

																	foreach ( $treatment_drug_item['proprietaryName'] as $proprietaryName ) {

																		// Add subfield value to base proprietaryName list array

																			$treatment_drug_item_proprietaryName_list[] = $proprietaryName['schema_drug_proprietaryname_text'];

																	}

																}

															// Clean up base proprietaryName list array

																if ( $treatment_drug_item_proprietaryName_list ) {

																	// If there is only one item, flatten the multi-dimensional array by one step

																		uamswp_fad_flatten_multidimensional_array($treatment_drug_item_proprietaryName_list);

																}

															// Add proprietaryName values to drug property value item array

																if ( $treatment_drug_item_proprietaryName_list ) {

																	$treatment_drug_item['proprietaryName'] = $treatment_drug_item_proprietaryName_list;

																}

														// Define nonProprietaryName schema property value

															// Base nonProprietaryName list array

																$treatment_drug_item_nonProprietaryName_list = array();

															// Get nonProprietaryName subfield value

																$treatment_drug_item['nonProprietaryName'] = $item['schema_drug_nonproprietaryname'] ?? array();

															// Loop through rows of proprietaryName repeater value

																if ( $treatment_drug_item['nonProprietaryName'] ) {

																	foreach ( $treatment_drug_item['nonProprietaryName'] as $nonProprietaryName ) {

																		// Add subfield value to base nonProprietaryName list array

																			$treatment_drug_item_nonProprietaryName_list[] = $nonProprietaryName['schema_drug_nonproprietaryname_text'];

																	}

																}

															// Clean up base nonProprietaryName list array

																if ( $treatment_drug_item_nonProprietaryName_list ) {

																	// If there is only one item, flatten the multi-dimensional array by one step

																		uamswp_fad_flatten_multidimensional_array($treatment_drug_item_nonProprietaryName_list);

																}

															// Add nonProprietaryName values to drug property value item array

																if ( $treatment_drug_item_nonProprietaryName_list ) {

																	$treatment_drug_item['nonProprietaryName'] = $treatment_drug_item_nonProprietaryName_list;

																}

														// Define prescriptionStatus schema property value, add to drug property value item array

															$treatment_drug_item['prescriptionStatus'] = $item['schema_drug_prescriptionstatus'] ?? '';

														// Define rxcui schema property value, add to drug property value item array

															$treatment_drug_item['rxcui'] = $item['schema_drug_rxcui'] ?? '';

													// Add drug property value item array to the drug property values array

														if ( $treatment_drug_item ) {

															$treatment_drug[] = $treatment_drug_item;

														}

												}

											}

									// Clean up drug property values array

										if ( $treatment_drug ) {

											$treatment_drug = array_filter($treatment_drug);
											$treatment_drug = array_values($treatment_drug);

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array($treatment_drug);

										}

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'drug', // string // Required // Name of schema property
											$treatment_drug, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// duplicateTherapy

							/**
							 * A therapy that duplicates or overlaps this one.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalTherapy
							 */

							if (
								(
									isset($treatment_item_Service)
									&&
									in_array(
										'duplicateTherapy',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Get values

									// Get duplicateTherapy relationship repeater value (clones 'field_schema_medicaltherapy')

										$treatment_duplicateTherapy_relationship = get_field( 'treatment_procedure_schema_duplicatetherapy_schema_medicaltherapy', $entity ) ?? array();

									// Add each item to duplicateTherapy property values array

										if ( $treatment_duplicateTherapy_relationship ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											$treatment_duplicateTherapy = uamswp_fad_schema_treatment(
												$treatment_duplicateTherapy_relationship, // array // Required // List of IDs of the service items
												$page_url, // string // Required // Page URL
												$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
												( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
												$Service_i, // int // Optional // Iteration counter for treatment-as-Service
												$MedicalCondition_i // int // Optional // Iteration counter for condition-as-MedicalCondition
											);

										}

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'duplicateTherapy', // string // Required // Name of schema property
											$treatment_duplicateTherapy, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
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

						// imagingTechnique

							/**
							 * Imaging technique used.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalImagingTechnique
							 */

							if (
								(
									isset($treatment_item_Service)
									&&
									in_array(
										'imagingTechnique',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
							) {

								// Get values

									$treatment_imagingTechnique = get_field( 'schema_medicalimagingtechnique', $entity ) ?: '';

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'imagingTechnique', // string // Required // Name of schema property
											$treatment_imagingTechnique, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// legalStatus [WIP]

							/**
							 * The drug or supplement's legal status, including any controlled substance
							 * schedules that apply.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - DrugLegalStatus
							 *      - MedicalEnumeration
							 *      - Text
							 */

						// mainEntityOfPage [excluded; unset]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

							// Unset value defined in common properties

								if ( isset($treatment_item_Service['mainEntityOfPage']) ) {

									unset($treatment_item_Service['mainEntityOfPage']);

								}

						// medicineSystem [WIP]

							/**
							 * The system of medicine that includes this MedicalEntity
							 * (e.g., 'evidence-based,' 'homeopathic,' 'chiropractic').
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicineSystem
							 */

						// procedureType

							/**
							 * The type of procedure, for example Surgical, Noninvasive, or Percutaneous.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalProcedureType (enumeration type)
							 *           - NoninvasiveProcedure
							 *           - PercutaneousProcedure
							 */

							if (
								(
									isset($treatment_item_Service)
									&&
									in_array(
										'procedureType',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
							) {

								// Get values

									$treatment_procedureType = get_field( 'schema_medicalproceduretype', $entity ) ?: '';

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'procedureType', // string // Required // Name of schema property
											$treatment_procedureType, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// recognizingAuthority [WIP]

							/**
							 * If applicable, the organization that officially recognizes this entity as part
							 * of its endorsed system of medicine.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Organization
							 */

						// relevantSpecialty

							/**
							 * If applicable, a medical specialty in which this entity is relevant.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalSpecialty (enumeration type)
							 */

							if (
								(
									isset($treatment_item_Service)
									&&
									in_array(
										'relevantSpecialty',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
							) {

								// Get values

									// Base array

										$treatment_relevantSpecialty = array();

									// Get relevantSpecialty multi-select field value (clone of 'field_schema_medicalspecialty_multiple')

										$treatment_relevantSpecialty_multiselect = get_field( 'treatment_procedure_schema_relevantspecialty_schema_medicalspecialty_multiple', $entity ) ?? array();

									// Add each item to relevantSpecialty property values array

										if ( $treatment_relevantSpecialty_multiselect ) {

											foreach ( $treatment_relevantSpecialty_multiselect as $item ) {

												$treatment_relevantSpecialty[] = $item ?? '';

											}

										}

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'relevantSpecialty', // string // Required // Name of schema property
											$treatment_relevantSpecialty, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
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
									isset($treatment_item_Service)
									&&
									in_array(
										'sameAs',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
							) {

								// Get values

									// Get sameAs repeater field value

										$treatment_sameAs_repeater = get_field( 'schema_sameas', $entity ) ?? array();

									// Add each item to sameAs property values array

										if ( $treatment_sameAs_repeater ) {

											$treatment_sameAs = uamswp_fad_schema_sameas(
												$treatment_sameAs_repeater, // sameAs repeater field
												'schema_sameas_url' // sameAs item field name
											);

										}

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'sameAs', // string // Required // Name of schema property
											$treatment_sameAs, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// signDetected [WIP]

							/**
							 * A sign detected by the test.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalSign
							 */

						// subTest

							/**
							 * A component test of the panel.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalTest
							 */

							if (
								(
									isset($treatment_item_Service)
									&&
									in_array(
										'subTest',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Get values

									// Get subTest relationship field value (clone field referencing 'field_schema_medicaltest')

										$treatment_subTest_relationship = get_field( 'treatment_procedure_schema_subtest_schema_medicaltest', $entity ) ?? array();

									// Add each item to subTest property values array

										if ( $treatment_subTest_relationship ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											$treatment_subTest = uamswp_fad_schema_treatment(
												$treatment_subTest_relationship, // array // Required // List of IDs of the service items
												$page_url, // string // Required // Page URL
												$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
												( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
												$Service_i, // int // Optional // Iteration counter for treatment-as-Service
												$MedicalCondition_i // int // Optional // Iteration counter for condition-as-MedicalCondition
											);

										}

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'subTest', // string // Required // Name of schema property
											$treatment_subTest, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// tissueSample

							/**
							 * The type of tissue sample required for the test.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 */

							if (
								(
									isset($treatment_item_Service)
									&&
									in_array(
										'tissueSample',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
							) {

								// Get values

									// Base tissueSample property values array

										$treatment_tissueSample = array();

									// Get tissueSample repeater field value

										$treatment_tissueSample_repeater = get_field( 'schema_tissuesample', $entity ) ?? array();

									// Add each item to tissueSample property values array

										if ( $treatment_tissueSample_repeater ) {

											foreach ( $treatment_tissueSample_repeater as $tissueSample ) {

												$treatment_tissueSample[] = $tissueSample['schema_tissuesample_text'];

											}

										}

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'tissueSample', // string // Required // Name of schema property
											$treatment_tissueSample, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// usedToDiagnose

							/**
							 * A condition the test is used to diagnose.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalCondition
							 */

							if (
								(
									isset($treatment_item_Service)
									&&
									in_array(
										'usedToDiagnose',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Get values

									// Get usedToDiagnose relationship field value (clone of 'field_schema_medicalcondition')

										$treatment_usedToDiagnose_relationship = get_field( 'treatment_procedure_schema_usedtodiagnose_schema_medicalcondition', $entity ) ?? array();

									// Add each item to usedToDiagnose property values array

										if ( $treatment_usedToDiagnose_relationship ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_condition') ) {

												$treatment_usedToDiagnose = uamswp_fad_schema_condition(
													$treatment_usedToDiagnose_relationship, // array // Required // List of IDs of the MedicalCondition items
													$page_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$MedicalCondition_i, // int // Optional // Iteration counter for condition-as-MedicalCondition
													$Service_i // int // Optional // Iteration counter for treatment-as-Service
												);

											} else {

												$treatment_usedToDiagnose = null;

											}

										}

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'usedToDiagnose', // string // Required // Name of schema property
											$treatment_usedToDiagnose, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// usesDevice

							/**
							 * Device used to perform the test.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalDevice
							 */

							if (
								(
									isset($treatment_item_Service)
									&&
									in_array(
										'usesDevice',
										$treatment_properties_map[$Service_type]['properties']
									)
								)
							) {

								// Get values

									// Base array

										$treatment_usesDevice = array();

									// Get usesDevice repeater field value

										$treatment_usesDevice_repeater = get_field( 'schema_medicaldevice', $entity ) ?? array();

									// Add each item to usesDevice property values array

										if ( $treatment_usesDevice_repeater ) {

											foreach ( $treatment_usesDevice_repeater as $item ) {

												// Base usesDevice property value item array

													$treatment_usesDevice_item = array();

												// @type

													$treatment_usesDevice_item['@type'] = 'MedicalDevice'; // Replace 'MedicalDevice' with subtype, if relevant

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

													$treatment_usesDevice_item['name'] = $item['schema_medicaldevice_name'];

												// alternateName

													/**
													 * An alias for the item.
													 *
													 * Values expected to be one of these types:
													 *
													 *      - Text
													 */

													// Get alternateName repeater field value

														$treatment_usesDevice_item_alternateName_repeater = $item['schema_medicaldevice_alternatename']['schema_alternatename'] ?: array();

														// Add each item to alternateName property value array

															if ( $treatment_alternateName_repeater ) {

																$treatment_usesDevice_item_alternateName = uamswp_fad_schema_alternatename(
																	$treatment_alternateName_repeater, // array // Required // alternateName repeater field
																	'schema_alternatename_text' // string // Optional // alternateName item field name
																);

															}

													// Add to usesDevice property value item array

														if ( $treatment_usesDevice_item_alternateName ) {

															$treatment_usesDevice_item['alternateName'] = $treatment_usesDevice_item_alternateName;

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

													// Get code repeater field value

														$treatment_usesDevice_item_code_repeater = $item['schema_medicaldevice_code']['schema_medicalcode'] ?: array();

														// Add each item to code property value array

															if ( $treatment_usesDevice_item_code_repeater ) {

																$treatment_usesDevice_item_code = uamswp_fad_schema_code(
																	'code', // enum('code', 'identifier') // Required // Schema property format to output
																	$treatment_usesDevice_item_code_repeater // array // Optional // code repeater field
																);

															}

													// Add to usesDevice property value item array

														if ( $treatment_usesDevice_item_code ) {

															$treatment_usesDevice_item['code'] = $treatment_usesDevice_item_code;

														}

												// Add item to the list array

													$treatment_usesDevice[] = $treatment_usesDevice_item;

											}

										}

								// Add to item values

									// Service

										uamswp_fad_schema_add_to_item_values(
											$Service_type, // string // Required // The @type value for the schema item
											$treatment_item_Service, // array // Required // The list array for the schema item to which to add the property value
											'usesDevice', // string // Required // Name of schema property
											$treatment_usesDevice, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$treatment_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

					// Sort and combine the arrays

						if ( isset($treatment_item_Service) ) {

							ksort( $treatment_item_Service, SORT_NATURAL | SORT_FLAG_CASE );

						}

					// Set/update the value of the item transient

						uamswp_fad_set_transient(
							'item_' . $entity, // Required // String added to transient name for disambiguation.
							$treatment_item_Service, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
							__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
						);

					// Add to list of treatments

						$treatment_list[] = $treatment_item_Service;

				}

			} // endforeach ( $repeater as $entity )

		// Clean up list array

			$treatment_list = array_filter($treatment_list);
			$treatment_list = array_values($treatment_list);

			// If there is only one item, flatten the multi-dimensional array by one step

				uamswp_fad_flatten_multidimensional_array($treatment_list);

	} // endif ( !empty($repeater) )

	return $treatment_list;

}
