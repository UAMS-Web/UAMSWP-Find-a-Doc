<?php

/**
 * Functions for Schema.org Schema and Google Local Business structured data
 *
 * Generate schema array of Location ontology page type (MedicalWebPage; LocalBusiness)
 */

function uamswp_fad_schema_location(
	array $repeater, // List of IDs of the location items
	string $page_url, // Page URL
	array &$node_identifier_list = array(), // array // Optional // List of node identifiers (@id) already defined in the schema
	int $nesting_level = 1, // Nesting level within the main schema
	int $MedicalWebPage_i = 1, // Iteration counter for location-as-MedicalWebPage
	int $LocalBusiness_i = 1, // Iteration counter for location-as-LocalBusiness
	array $location_fields = array(), // Pre-existing field values array so duplicate calls can be avoided
	array $MedicalWebPage_list = array(), // Pre-existing list array for location-as-MedicalWebPage to which to add additional items
	array $LocalBusiness_list = array(), // Pre-existing list array for location-as-LocalBusiness to which to add additional items
	array $location_list = array() // Pre-existing list array for combined location schema to which to add additional items
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

				$location_valid_types = array();

			// List of Schema.org types for which to get the subtypes

				$location_valid_types_plus_subtypes = array(
					'MedicalBusiness',
					'MedicalWebPage',
					'Hospital'
				);

			// Base array for schema.org type URLs

				$location_valid_types_url = array();

			// Get a list of schema.org subtypes and URLs

				uamswp_fad_schema_subtypes_and_urls(
					$location_valid_types, // array // Required // List of Schema.org types for which to not get the subtypes
					$location_valid_types_plus_subtypes, // array // Optional // List of Schema.org types for which to get the subtypes
					$location_valid_types_url // string|array // Optional // Pre-existing list of schema.org URLs to which to add additional items
				);

		// List of valid properties for each type

			// Base array

				$location_properties_map = array();

			// Get list of valid properties from Schema.org type list

				foreach ( $location_valid_types as $item ) {

					$location_properties_map[$item]['properties'] = $schema_org_types[$item]['properties'] ?? array();
					$location_properties_map[$item]['properties'] = is_array($location_properties_map[$item]['properties']) ? $location_properties_map[$item]['properties'] : array($location_properties_map[$item]['properties']);

				}

		// Loop through each location to add values

			foreach ( $repeater as $entity ) {

				if ( !$entity ) {

					continue;

				}

				// Retrieve the value of the item transient

					uamswp_fad_get_transient(
						'item_' . $entity . ( $nesting_level ? '_nested-level-' . $nesting_level : '_root' ), // Required // String added to transient name for disambiguation.
						$location_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

				if (
					!empty( $location_item )
					&&
					(
						(
							isset($location_item['MedicalWebPage'])
							&&
							!empty($location_item['MedicalWebPage'])
						)
						||
						(
							isset($location_item['LocalBusiness'])
							&&
							!empty($location_item['LocalBusiness'])
						)
					)
				) {

					/**
					 * The transient exists.
					 * Return the variable.
					 */

					// Add to lists of locations

						// Add to list of MedicalWebPage items

							if (
								isset($location_item['MedicalWebPage'])
								&&
								!empty($location_item['MedicalWebPage'])
							) {

								$MedicalWebPage_list[] = $location_item['MedicalWebPage'];

							}

						// Add to list of LocalBusiness items

							if (
								isset($location_item['LocalBusiness'])
								&&
								!empty($location_item['LocalBusiness'])
							) {

								$LocalBusiness_list[] = $location_item['LocalBusiness'];

							}

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

						$location_item = array(); // Base array
						$location_item_MedicalWebPage = in_array( 'MedicalWebPage', $location_valid_types ) ? array() : null; // Base MedicalWebPage array
						$location_item_LocalBusiness = in_array( 'LocalBusiness', $location_valid_types ) ? array() : null; // Base LocalBusiness array
						$item_contactType = null;
						$item_description = null;
						$item_telephone = null;
						$item_value = null;
						$LocalBusiness_id = null;
						$LocalBusiness_type = null;
						$location_hours_24_7_query = null;
						$location_about = null;
						$location_abstract = null;
						$location_accessibilityAPI = null;
						$location_accessibilityControl = null;
						$location_accessibilityFeature = null;
						$location_accessibilityHazard = null;
						$location_accessibilitySummary = null;
						$location_accessMode = null;
						$location_accessModeSufficient = null;
						$location_accountablePerson = null;
						$location_additionalType_field = null;
						$location_additionalType_LocalBusiness = null;
						$location_additionalType_medicalSpecialty = null;
						$location_additionalType_MedicalWebPage = null;
						$location_additionalType_repeater = null;
						$location_address = null;
						$location_address_1 = null;
						$location_address_2_array = null;
						$location_address_id = null;
						$location_address_keywords = null;
						$location_addressLocality = null;
						$location_addressRegion = null;
						$location_after_hours = null;
						$location_alternateName = null;
						$location_alternateName_repeater = null;
						$location_appointments_query = null;
						$location_areaServed = null;
						$location_audience = null;
						$location_author = null;
						$location_availableService = null;
						$location_brand = null;
						$location_clinical_resource = null;
						$location_clinical_resource_CreativeWork = null;
						$location_clinical_resource_CreativeWork_keywords = null;
						$location_clinical_resource_MedicalWebPage = null;
						$location_clinical_resource_MedicalWebPage_significantLink = null;
						$location_contactPoint = null;
						$location_containedInPlace = null;
						$location_containsPlace = null;
						$location_contributor = null;
						$location_copyrightHolder = null;
						$location_copyrightNotice = null;
						$location_copyrightYear = null;
						$location_countryOfOrigin = null;
						$location_creativeWorkStatus = null;
						$location_creator = null;
						$location_creditText = null;
						$location_current_fpage = null;
						$location_dateModified = null;
						$location_datePublished = null;
						$location_department = null;
						$location_descendant_location = null;
						$location_descendant_location_ids = null;
						$location_descendant_location_LocalBusiness = null;
						$location_descendant_location_LocalBusiness_keywords = null;
						$location_descendant_location_MedicalWebPage = null;
						$location_descendant_location_MedicalWebPage_significantLink = null;
						$location_description = null;
						$location_description_TextObject = null;
						$location_editor = null;
						$location_employee = null;
						$location_expertise = null;
						$location_expertise_MedicalEntity = null;
						$location_expertise_MedicalEntity_keywords = null;
						$location_expertise_MedicalWebPage_significantLink = null;
						$location_facility_additionalType = null;
						$location_facility_additionalType_repeater = null;
						$location_facility_address = null;
						$location_facility_alternateName = null;
						$location_facility_alternateName_repeater = null;
						$location_facility_containedIn = null;
						$location_facility_containedInPlace = null;
						$location_facility_geo = null;
						$location_facility_hasMap = null;
						$location_facility_id = null;
						$location_facility_image = null;
						$location_facility_latitude = null;
						$location_facility_longitude = null;
						$location_facility_name = null;
						$location_facility_photo = null;
						$location_facility_Place = null;
						$location_facility_query = null;
						$location_facility_sameAs = null;
						$location_facility_sameAs_repeater = null;
						$location_facility_slug = null;
						$location_facility_term = null;
						$location_facility_type = null;
						$location_faxNumber = null;
						$location_faxNumber_Text_array = null;
						$location_featured_image_id = null;
						$location_floor = null;
						$location_floor_label = null;
						$location_floor_value = null;
						$location_fpage_query = null;
						$location_funding = null;
						$location_gallery_image_id = null;
						$location_geo = null;
						$location_geo_value = null;
						$location_has_parent = null;
						$location_hasMap = null;
						$location_hours_repeater = null;
						$location_hours_group = null;
						$location_hours_openingHours = null;
						$location_hours_openingHoursSpecification = null;
						$location_hours_loop_input = null;
						$location_hours_specialOpeningHoursSpecification = null;
						$location_hours_specialOpeningHoursSpecification_input = null;
						$location_hours_variable_query = null;
						$location_hours_variable_info = null;
						$location_identifier = null;
						$location_image = null;
						$location_image_general = null;
						$location_image_id = null;
						$location_inLanguage = null;
						$location_isAcceptingNewPatients = null;
						$location_isFamilyFriendly = null;
						$location_isPartOf = null;
						$location_keywords = null;
						$location_lastReviewed = null;
						$location_mainContentOfPage = null;
						$location_mainEntity = null;
						$location_mainEntityOfPage = null;
						$location_maintainer = null;
						$location_medicalAudience = null;
						$location_medicalSpecialty = null;
						$location_medicalSpecialty_multiselect = null;
						$location_memberOf = null;
						$location_mentions = null;
						$location_hours_modified_query = null;
						$location_hours_modified_end_query = null;
						$location_hours_modified_end_date = null;
						$location_hours_modified = null;
						$location_hours_modified_reason = null;
						$location_hours_modified_start_date = null;
						$location_name = null;
						$location_ontology_type = null;
						$location_override_parent_photo = null;
						$location_override_parent_photo_featured = null;
						$location_override_parent_photo_gallery = null;
						$location_override_parent_photo_wayfinding = null;
						$location_parent_id = null;
						$location_parent_LocalBusiness = null;
						$location_parentOrganization = null;
						$location_photo = null;
						$location_postalCode = null;
						$location_producer = null;
						$location_provider = null;
						$location_provider_ids = null;
						$location_provider_MedicalBusiness = null;
						$location_provider_Person = null;
						$location_publisher = null;
						$location_relatedLink = null;
						$location_reviewedBy = null;
						$location_sameAs = null;
						$location_sameAs_repeater = null;
						$location_significantLink = null;
						$location_smokingAllowed = null;
						$location_sourceOrganization = null;
						$location_speakable = null;
						$location_specialty_common = null;
						$location_specific_clinical_organization_slug = null;
						$location_streetAddress = null;
						$location_streetAddress_array = null;
						$location_subjectOf = null;
						$location_subOrganization = null;
						$location_suite = null;
						$location_telemed_24_7 = null;
						$location_telemed_hours = null;
						$location_telemed_modified_hours_24_7 = null;
						$location_telemed_modified_hours_end = null;
						$location_telemed_modified_hours_end_date = null;
						$location_telemed_modified_hours_query = null;
						$location_telemed_modified_hours_reason = null;
						$location_telemed_modified_hours_start_date = null;
						$location_telemed_modified_hours_times = null;
						$location_telemed_patients = null;
						$location_telemed_query = null;
						$location_telephone_appointment_ac_primary = null;
						$location_telephone_appointment_ac_query = null;
						$location_telephone_appointment_ac_specialty = null;
						$location_telephone_appointment_existing = null;
						$location_telephone_appointment_new = null;
						$location_telephone_appointment_query = null;
						$location_telephone_general = null;
						$location_telephone_Text_array = null;
						$location_thumbnailUrl = null;
						$location_treatments = null;
						$location_url = null;
						$location_vatID = null;
						$location_wayfinding_image_id = null;
						$MedicalCondition_i = 1;
						$MedicalWebPage_id = null;
						$MedicalWebPage_type = null;
						$Service_i = 1;

						// Reused variables

							$location_smokingAllowed = 'False';

					// Load variables from pre-existing field values array

						if (
							isset($location_fields[$entity])
							&&
							!empty($location_fields[$entity])
						) {

							foreach ( $location_fields[$entity] as $key => $value ) {

								${$key} = $value; // Create a variable for each item in the array

							}

						}

					// Get ontology type

						if ( !isset($location_ontology_type) ) {

							$location_ontology_type = true;

						}

					// If the page is not an ontology type, skip to the next iteration

						if ( !$location_ontology_type ) {

							continue;

						}

					// Fake subpage query and get fake subpage slug

						if (
							$location_ontology_type
							&&
							$nesting_level == 0
						) {

							if ( !isset($location_current_fpage) ) {

								$location_current_fpage = get_query_var( 'fpage' ) ?? ''; // Fake subpage slug

							}

							if ( !isset($location_fpage_query) ) {

								$location_fpage_query = $location_current_fpage ? true : false;

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
						 *      * about
						 *      * abstract
						 *      * acceptedPaymentMethod
						 *      * accessibilityAPI
						 *      * accessibilityControl
						 *      * accessibilityFeature
						 *      * accessibilityHazard
						 *      * accessibilitySummary
						 *      * accessMode
						 *      * accessModeSufficient
						 *      * accountablePerson
						 *      * acquireLicensePage
						 *      * actionableFeedbackPolicy
						 *      * additionalProperty
						 *      * agentInteractionStatistic
						 *      * alternativeHeadline
						 *      * alumni
						 *      * amenityFeature
						 *      * archivedAt
						 *      * areaServed
						 *      * aspect
						 *      * assesses
						 *      * associatedMedia
						 *      * audience
						 *      * audio
						 *      * author
						 *      * awards
						 *      * branchCode
						 *      * branchOf
						 *      * breadcrumb
						 *      * character
						 *      * citation
						 *      * comment
						 *      * commentCount
						 *      * conditionsOfAccess
						 *      * contactPoints
						 *      * containedIn
						 *      * contentLocation
						 *      * contentRating
						 *      * contentReferenceTime
						 *      * contributor
						 *      * copyrightHolder
						 *      * copyrightNotice
						 *      * copyrightYear
						 *      * correction
						 *      * correctionsPolicy
						 *      * countryOfOrigin
						 *      * creativeWorkStatus
						 *      * creator
						 *      * creditText
						 *      * dateCreated
						 *      * dateModified
						 *      * datePublished
						 *      * description
						 *      * digitalSourceType
						 *      * disambiguatingDescription
						 *      * discussionUrl
						 *      * dissolutionDate
						 *      * editEIDR
						 *      * editor
						 *      * educationalAlignment
						 *      * educationalLevel
						 *      * educationalUse
						 *      * email
						 *      * employees
						 *      * encoding
						 *      * encodingFormat
						 *      * encodings
						 *      * events
						 *      * exampleOfWork
						 *      * expires
						 *      * fileFormat
						 *      * founder
						 *      * founders
						 *      * foundingLocation
						 *      * funder
						 *      * funding
						 *      * genre
						 *      * geoContains
						 *      * geoCoveredBy
						 *      * geoCovers
						 *      * geoCrosses
						 *      * geoDisjoint
						 *      * geoEquals
						 *      * geoIntersects
						 *      * geoOverlaps
						 *      * geoTouches
						 *      * geoWithin
						 *      * hasCertification
						 *      * hasGS1DigitalLink
						 *      * hasMerchantReturnPolicy
						 *      * hasOfferCatalog
						 *      * hasPart
						 *      * hasPOS
						 *      * hasProductReturnPolicy
						 *      * headline
						 *      * healthcareReportingData
						 *      * healthPlanNetworkId
						 *      * inLanguage
						 *      * interactionStatistic
						 *      * interactivityType
						 *      * interpretedAsClaim
						 *      * isBasedOn
						 *      * isBasedOnUrl
						 *      * isFamilyFriendly
						 *      * isPartOf
						 *      * lastReviewed
						 *      * learningResourceType
						 *      * license
						 *      * location
						 *      * locationCreated
						 *      * mainEntity
						 *      * mainEntityOfPage
						 *      * maintainer
						 *      * map
						 *      * maps
						 *      * material
						 *      * materialExtent
						 *      * medicalAudience
						 *      * member
						 *      * members
						 *      * ownershipFundingInfo
						 *      * owns
						 *      * pattern
						 *      * photos
						 *      * position
						 *      * priceRange
						 *      * producer
						 *      * provider
						 *      * publication
						 *      * publisher
						 *      * publisherImprint
						 *      * publishingPrinciples
						 *      * recordedAt
						 *      * releasedEvent
						 *      * reviewedBy
						 *      * reviews
						 *      * schemaVersion
						 *      * sdDatePublished
						 *      * sdLicense
						 *      * sdPublisher
						 *      * seeks
						 *      * serviceArea
						 *      * significantLinks
						 *      * size
						 *      * slogan
						 *      * smokingAllowed
						 *      * sourceOrganization
						 *      * spatial
						 *      * spatialCoverage
						 *      * sponsor
						 *      * subjectOf
						 *      * teaches
						 *      * temporal
						 *      * temporalCoverage
						 *      * text
						 *      * thumbnail
						 *      * thumbnailUrl
						 *      * tourBookingPage
						 *      * translationOfWork
						 *      * translator
						 *      * typicalAgeRange
						 *      * unnamedSourcesPolicy
						 *      * usageInfo
						 *      * version
						 *      * video
						 *      * workExample
						 *      * workTranslation
						 */

						// url

							/**
							 * URL of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - URL
							 */

							// Get values

								if ( !isset($location_url) ) {

									$location_url = get_permalink($entity);
									$location_url = $location_url ? user_trailingslashit( $location_url ) : '';

								}

							// Pass the values to common schema properties template part

								$schema_common_url = $location_url;

							// Add to schema

								// MedicalWebPage

									if (
										isset($location_item_MedicalWebPage)
										&&
										$location_url
									) {

										$location_item_MedicalWebPage['url'] = $location_url;

									}

								// LocalBusiness

									if (
										isset($provider_item_LocalBusiness)
										&&
										$location_url
									) {

										$provider_item_LocalBusiness['url'] = $location_url;

									}

						// @type

							// MedicalWebPage type

								if ( isset($location_item_MedicalWebPage) ) {

									// Get values

										$MedicalWebPage_type = 'MedicalWebPage';

										// Add to item values

											if ( $MedicalWebPage_type ) {

												$location_item_MedicalWebPage['@type'] = $MedicalWebPage_type;

											}

								}

							// LocalBusiness Subtype

								if ( isset($location_item_LocalBusiness) ) {

									// Get values

										// LocalBusiness Subtype

											if ( !isset($LocalBusiness_type) ) {

												$LocalBusiness_type = get_field( 'schema_localbusiness_single', $entity ) ?? '';

											}

											$LocalBusiness_type = is_array($LocalBusiness_type) ? reset($LocalBusiness_type) : $LocalBusiness_type;

											// Fallback value

												if (
													!$LocalBusiness_type
													||
													!array_key_exists( $LocalBusiness_type, $location_properties_map )
												) {

													$LocalBusiness_type = 'MedicalBusiness';

												}

									// Add to item values

										$location_item_LocalBusiness['@type'] = $LocalBusiness_type;

								}

						// @id

							// MedicalWebPage

								if ( isset($location_item_MedicalWebPage) ) {

									// Get values

										$MedicalWebPage_id = $location_url . '#' . $MedicalWebPage_type;
										// $MedicalWebPage_id .= $MedicalWebPage_i;
										// $MedicalWebPage_i++;

									// Add to item values

										if ( $MedicalWebPage_id ) {

											$location_item_MedicalWebPage['@id'] = $MedicalWebPage_id;
											$node_identifier_list[] = $location_item_MedicalWebPage['@id']; // Add to the list of existing node identifiers

										}

								}

							// LocalBusiness

								if ( isset($location_item_LocalBusiness) ) {

									// Get values

										$LocalBusiness_id = $location_url . '#' . $LocalBusiness_type;
										// $LocalBusiness_id .= $LocalBusiness_i;
										// $LocalBusiness_i++;

									// Add to item values

										if ( $LocalBusiness_id ) {

											$location_item_LocalBusiness['@id'] = $LocalBusiness_id;
											$node_identifier_list[] = $location_item_LocalBusiness['@id']; // Add to the list of existing node identifiers

										}

								}

						// Specific Clinical Organizations (common use) [WIP]

							/*

								e.g., Arkansas Children's, Baptist Health, Central Arkansas Veterans Healthcare System

							*/

							// List of properties that reference organizations (i.e., 'Organization')

								$location_organization_common = array(
									'affiliation',
									'brand',
									'contactPoint',
									'hospitalAffiliation',
									'memberOf',
									'parentOrganization',
									'worksFor'
								);

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									array_intersect(
										$location_properties_map[$MedicalWebPage_type]['properties'],
										$location_organization_common
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									array_intersect(
										$location_properties_map[$LocalBusiness_type]['properties'],
										$location_organization_common
									)
								)
							) {

								// Get values

									// Query: Whether to override the default clinical brand organization for this entity

										$location_specific_clinical_organization_override = get_field( 'schema_brandorg_query', $entity ) ?? null;

									// Get list of Third-Party Brand Organizations

										// Base array

											$location_specific_clinical_organization = array();
											$location_specific_clinical_organization_slug = array();

										if ( $location_specific_clinical_organization_override ) {

											$location_specific_clinical_organization = uamswp_fad_schema_brand_organization_list(
												$entity, // int|WP_Term // Required // Post ID or term object
												$location_specific_clinical_organization, // array // Optional // Pre-existing list array for brand organizations to which to add additional items
												$location_specific_clinical_organization_slug // array // Optional // Pre-existing list array for brand organizations slugs to which to add additional slugs
											);

										}

									// Fallback query (deprecated): Is this an Arkansas Children's location?

										/**
										 * If the brand organization fields do not yet have a value (i.e., the location
										 * profile hasn't been updated since this functionality was added), then use the
										 * value from the deprecated query for whether this location is an
										 * Arkansas Children's location.
										 */

										if ( !isset($location_specific_clinical_organization_override) ) {

											if ( !isset($location_ac_query) ) {

												$location_ac_query = get_field( 'location_ac_query', $entity ) ?? null;

											}

											$brand_organization_slug_arkansas_childrens = $brand_organization_slug_arkansas_childrens ?? null;

											if (
												$location_ac_query
												&&
												$brand_organization_slug_arkansas_childrens
											) {

												$location_specific_clinical_organization = uamswp_fad_schema_brand_organization(
													$brand_organization_slug_arkansas_childrens // string // Required // Brand Organization term slug
												);

												$location_specific_clinical_organization_slug[] = $brand_organization_slug_arkansas_childrens;

											}

										}

								// Pass the values to common schema properties template part

									$schema_common_specific_brand_organization_override = $location_specific_clinical_organization_override; // Query for whether to override the default clinical brand organization for this entity
									$schema_common_specific_brand_organization = $location_specific_clinical_organization; // Clinical organization(s) specific to the current entity

							}

						// Add common properties

							// Pass variables to template part

								$schema_common_item_MedicalWebPage = $location_item_MedicalWebPage; // MedicalWebPage item array
								$schema_common_item_mainEntity = $location_item_LocalBusiness ?? null; // item array for the main entity of the MedicalWebPage
								$schema_common_item_about = $location_item_LocalBusiness ?? null; // all major entities of the MedicalWebPage

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

											// MedicalWebPage

												uamswp_fad_schema_add_to_item_values(
													$MedicalWebPage_type, // string // Required // The @type value for the schema item
													$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$location_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// LocalBusiness

												uamswp_fad_schema_add_to_item_values(
													$LocalBusiness_type, // string // Required // The @type value for the schema item
													$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$location_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								}

							// MedicalWebPage only

								/*

									Loop through an associative array of properties specific to the MedicalWebPage
									type, adding each row to this item's schema when the key matches a property
									valid for the type, replacing full values with only the node identifier where
									appropriate.

								*/

								if (
									isset($schema_common_properties_MedicalWebPage)
									&&
									!empty($schema_common_properties_MedicalWebPage)
								) {

									foreach ( $schema_common_properties_MedicalWebPage as $key => $value ) {

										// Add to item values

											// MedicalWebPage

												uamswp_fad_schema_add_to_item_values(
													$MedicalWebPage_type, // string // Required // The @type value for the schema item
													$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$location_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

									// Merge node identifier lists

										if (
											isset($node_identifier_list_MedicalWebPage)
											&&
											!empty($node_identifier_list_MedicalWebPage)
											&&
											is_array($node_identifier_list_MedicalWebPage)
										) {

											$node_identifier_list = array_merge(
												$node_identifier_list,
												$node_identifier_list_MedicalWebPage
											);

										}

									// De-duplicate the node identifier list

										if (
											$node_identifier_list
											&&
											is_array($node_identifier_list)
										) {

											$node_identifier_list = array_unique( $node_identifier_list, SORT_REGULAR );

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

											// LocalBusiness

												uamswp_fad_schema_add_to_item_values(
													$LocalBusiness_type, // string // Required // The @type value for the schema item
													$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$location_properties_map, // array // Required // Map array to match schema types with allowed properties
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

											// LocalBusiness

												uamswp_fad_schema_add_to_item_values(
													$LocalBusiness_type, // string // Required // The @type value for the schema item
													$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$location_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								}

						// Associated ontology items (e.g., providers, areas of expertise, clinical resources, conditions, treatments)

							// Associated providers

								// List of properties that reference associated providers

									$location_provider_common = array(
										'containsPlace',
										'department',
										'employee',
										'mentions',
										'relatedLink',
										'significantLink',
										'subOrganization'
									);

								if (
									(
										(
											isset($location_item_MedicalWebPage)
											&&
											array_intersect(
												$location_properties_map[$MedicalWebPage_type]['properties'],
												$location_provider_common
											)
										)
										||
										(
											isset($location_item_LocalBusiness)
											&&
											array_intersect(
												$location_properties_map[$LocalBusiness_type]['properties'],
												$location_provider_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get values

										// Get list of associated providers

											if ( !isset($location_provider_ids) ) {

												$location_provider_ids = array();

												$providers = get_field( 'physician_locations', $entity );
												include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/provider.php' );
												$location_provider_ids = $provider_ids;

												// Reset variables from Related Providers Section Query template part

													$providers = null;
													$provider_query = null;
													$provider_section_show = null;
													$provider_ids = null;
													$provider_count = null;
													$jump_link_count = null;

											}

										// Format values

											if ( $location_provider_ids ) {

												$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

												if ( function_exists('uamswp_fad_schema_provider') ) {

													$location_provider = uamswp_fad_schema_provider(
														$location_provider_ids, // array // Required // List of IDs of the provider items
														$location_url, // string // Required // Page URL
														$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
														($nesting_level + 1), // int // Optional // Nesting level within the main schema
														array( 'MedicalBusiness', 'MedicalWebPage', 'Person' ) // array // Optional // List of the schema types to output
													) ?? null;

												} else {

													$location_provider = null;

												}

												if ( isset($location_provider) ) {

													$location_provider_about = array(); // all major entities of the ontology type's MedicalWebPages

													// MedicalWebPage

														$location_provider_MedicalWebPage = $location_provider['MedicalWebPage'] ?? null;

														// Get URLs for significantLink property

															if ( $location_provider_MedicalWebPage ) {

																$location_provider_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																	$location_provider_MedicalWebPage, // array // Required // Property values from which to extract specific values
																	array( 'url' ) // mixed // Required // List of properties from which to collect values
																);

															}

													// MedicalBusiness and subtypes

														$location_provider_MedicalBusiness = $location_provider['MedicalBusiness'] ?? null;
														if ( isset($location_provider_MedicalBusiness) ) { $location_provider_about[] = $location_provider_MedicalBusiness; } // Add to the list of all major entities of the ontology type's MedicalWebPages

														if ( $location_provider_MedicalBusiness ) {

															// Get URLs for significantLink property

																$location_provider_MedicalBusiness_significantLink = uamswp_fad_schema_property_values(
																	$location_provider_MedicalBusiness, // array // Required // Property values from which to extract specific values
																	array( 'url' ) // mixed // Required // List of properties from which to collect values
																);

															// Get names for keywords property

																$location_provider_MedicalBusiness_keywords = uamswp_fad_schema_property_values(
																	$location_provider_MedicalBusiness, // array // Required // Property values from which to extract specific values
																	array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
																);

														}

													// Person

														$location_provider_Person = $location_provider['Person'] ?? null;
														$location_provider_mainEntity = $location_provider_Person; // item array for the main entity of the ontology type's MedicalWebPages
														if ( isset($location_provider_Person) ) { $location_provider_about[] = $location_provider_Person; } // Add to the list of all major entities of the ontology type's MedicalWebPages

														if ( $location_provider_Person ) {

															// Get URLs for significantLink property

																$location_provider_Person_significantLink = uamswp_fad_schema_property_values(
																	$location_provider_Person, // array // Required // Property values from which to extract specific values
																	array( 'url' ) // mixed // Required // List of properties from which to collect values
																);

															// Get names for keywords property

																$location_provider_Person_keywords = uamswp_fad_schema_property_values(
																	$location_provider_Person, // array // Required // Property values from which to extract specific values
																	array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
																);

														}

												}

											}

								}

							// Descendant locations

								// List of properties that reference descendant locations

									$location_descendant_location_common = array(
										'containsPlace',
										'department',
										'employee',
										'mentions',
										'relatedLink',
										'significantLink',
										'subOrganization'
									);

								if (
									(
										(
											isset($location_item_MedicalWebPage)
											&&
											array_intersect(
												$location_properties_map[$MedicalWebPage_type]['properties'],
												$location_descendant_location_common
											)
										)
										||
										(
											isset($location_item_LocalBusiness)
											&&
											array_intersect(
												$location_properties_map[$LocalBusiness_type]['properties'],
												$location_descendant_location_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get values

										// Get list of descendant locations

											if ( !isset($location_descendant_location_ids) ) {

												$location_descendant_locations_query_vars = uamswp_fad_location_descendant_query(
													$entity, // int
													get_pages(
														array(
															'child_of' => $entity,
															'post_status' => 'publish',
															'post_type' => 'location',
														)
													) // WP_Post
												);

												$location_descendant_location_ids = $location_descendant_locations_query_vars['location_descendant_ids'];

											}

										// Format values

											if ( $location_descendant_location_ids ) {

												$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

												$location_descendant_location = uamswp_fad_schema_location(
													$location_descendant_location_ids, // List of IDs of the location items
													$location_url, // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													($nesting_level + 1) // Nesting level within the main schema
												) ?? null;

												if ( isset($location_descendant_location) ) {

													$location_descendant_location_about = array(); // Base array for all major entities of the ontology type's MedicalWebPages

													// MedicalWebPage

														$location_descendant_location_MedicalWebPage = $location_descendant_location['MedicalWebPage'] ?? null;

														// Get URLs for significantLink property

															if ( $location_descendant_location_MedicalWebPage ) {

																$location_descendant_location_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																	$location_descendant_location_MedicalWebPage, // array // Required // Property values from which to extract specific values
																	array( 'url' ) // mixed // Required // List of properties from which to collect values
																);

															}

													// LocalBusiness and subtypes

														$location_descendant_location_LocalBusiness = $location_descendant_location['LocalBusiness'] ?? null;
														$location_descendant_location_mainEntity = $location_descendant_location_LocalBusiness; // item array for the main entity of the ontology type's MedicalWebPages
														if ( isset($location_descendant_location_LocalBusiness) ) { $location_descendant_location_about[] = $location_descendant_location_LocalBusiness; } // Add to the list of all major entities of the ontology type's MedicalWebPages

														if ( $location_descendant_location_LocalBusiness ) {

															// Get URLs for significantLink property

																$location_descendant_location_LocalBusiness_significantLink = uamswp_fad_schema_property_values(
																	$location_descendant_location_LocalBusiness, // array // Required // Property values from which to extract specific values
																	array( 'url' ) // mixed // Required // List of properties from which to collect values
																);

															// Get names for keywords property

																$location_descendant_location_LocalBusiness_keywords = uamswp_fad_schema_property_values(
																	$location_descendant_location_LocalBusiness, // array // Required // Property values from which to extract specific values
																	array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
																);

														}

												}

											}

								}

							// Associated areas of expertise

								// List of properties that reference areas of expertise

									$location_expertise_common = array(
										'knowsAbout',
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($location_item_MedicalWebPage)
											&&
											array_intersect(
												$location_properties_map[$MedicalWebPage_type]['properties'],
												$location_expertise_common
											)
										)
										||
										(
											isset($location_item_MedicalWebPage)
											&&
											array_intersect(
												$location_properties_map[$MedicalWebPage_type]['properties'],
												$location_expertise_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related areas of expertise

										if ( !isset($location_expertise_list) ) {

											$location_expertise_list = get_field( 'location_expertise', $entity ) ?? array();

										}

									// Format values

										if ( $location_expertise_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_expertise') ) {

												$location_expertise = uamswp_fad_schema_expertise(
													$location_expertise_list, // List of IDs of the area of expertise items
													'', // string // Required // Page or fake subpage URL
													true, // bool // Required // Query for the ontology type of the post (true is ontology type, false is content type)
													'', // string // Required // Fake subpage slug
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ) // Nesting level within the main schema
												) ?? null;

											} else {

												$location_expertise = null;

											}

											if ( isset($location_expertise) ) {

												$location_expertise_about = array(); // Base array for all major entities of the ontology type's MedicalWebPages

												// MedicalWebPage

													$location_expertise_MedicalWebPage = $location_expertise['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $location_expertise_MedicalWebPage ) {

															$location_expertise_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$location_expertise_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// MedicalEntity and subtypes

													$location_expertise_MedicalEntity = $location_expertise['MedicalEntity'] ?? null;
													$location_expertise_mainEntity = $location_expertise_MedicalEntity; // item array for the main entity of the ontology type's MedicalWebPages
													if ( isset($location_expertise_MedicalEntity) ) { $location_expertise_about[] = $location_expertise_MedicalEntity; } // Add to the list of all major entities of the ontology type's MedicalWebPages

													if ( $location_expertise_MedicalEntity ) {

														// Get URLs for significantLink property

															$location_expertise_MedicalEntity_significantLink = uamswp_fad_schema_property_values(
																$location_expertise_MedicalEntity, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$location_expertise_MedicalEntity_keywords = uamswp_fad_schema_property_values(
																$location_expertise_MedicalEntity, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Associated clinical resources

								// List of properties that reference clinical resources

									$location_clinical_resource_common = array(
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($location_item_MedicalWebPage)
											&&
											array_intersect(
												$location_properties_map[$MedicalWebPage_type]['properties'],
												$location_clinical_resource_common
											)
										)
										||
										(
											isset($location_item_MedicalWebPage)
											&&
											array_intersect(
												$location_properties_map[$MedicalWebPage_type]['properties'],
												$location_clinical_resource_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related clinical resources

										if ( !isset($location_clinical_resource_list) ) {

											$location_clinical_resource_list = get_field( 'location_clinical_resources', $entity ) ?? array();

										}

										if ( !isset($location_clinical_resource_list_max) ) {

											include( UAMS_FAD_PATH . '/templates/parts/vars/sys/posts-per-page/clinical-resource.php' ); // General maximum number of clinical resource items to display on a fake subpage (or section)
											$location_clinical_resource_list_max = $clinical_resource_posts_per_page_section;

										}

									// Format values

										if ( $location_clinical_resource_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_clinical_resource') ) {

												$location_clinical_resource = uamswp_fad_schema_clinical_resource(
													$location_clinical_resource_list, // List of IDs of the clinical resource items
													$location_url, // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ) // Nesting level within the main schema
												) ?? null;

											} else {

												$location_clinical_resource = null;

											}

											if ( isset($location_clinical_resource) ) {

												$location_clinical_resource_about = array(); // Base array for all major entities of the ontology type's MedicalWebPages

												// MedicalWebPage

													$location_clinical_resource_MedicalWebPage = $location_clinical_resource['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $location_clinical_resource_MedicalWebPage ) {

															$location_clinical_resource_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$location_clinical_resource_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// CreativeWork and subtypes

													$location_clinical_resource_CreativeWork = $location_clinical_resource['CreativeWork'] ?? null;
													$location_clinical_resource_mainEntity = $location_clinical_resource_CreativeWork; // item array for the main entity of the ontology type's MedicalWebPages
													if ( isset($location_clinical_resource_CreativeWork) ) { $location_clinical_resource_about[] = $location_clinical_resource_CreativeWork; } // Add to the list of all major entities of the ontology type's MedicalWebPages

													if ( $location_clinical_resource_CreativeWork ) {

														// Get URLs for significantLink property

															$location_clinical_resource_CreativeWork_significantLink = uamswp_fad_schema_property_values(
																$location_clinical_resource_CreativeWork, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$location_clinical_resource_CreativeWork_keywords = uamswp_fad_schema_property_values(
																$location_clinical_resource_CreativeWork, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Associated conditions

								// List of properties that reference conditions

									$location_condition_common = array(
										'knowsAbout',
										'mentions'
									);

								if (
									(
										(
											isset($location_item_MedicalWebPage)
											&&
											array_intersect(
												$location_properties_map[$MedicalWebPage_type]['properties'],
												$location_condition_common
											)
										)
										||
										(
											isset($location_item_MedicalWebPage)
											&&
											array_intersect(
												$location_properties_map[$MedicalWebPage_type]['properties'],
												$location_condition_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related conditions

										if ( !isset($location_condition_list) ) {

											$location_condition_list = get_field( 'location_conditions_cpt', $entity ) ?? array();

										}

									// Format values

										$location_condition = null;

										if ( $location_condition_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_condition') ) {

												$location_condition = uamswp_fad_schema_condition(
													$location_condition_list, // array // Required // List of IDs of the MedicalCondition items
													$location_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$MedicalCondition_i, // int // Optional // Iteration counter for condition-as-MedicalCondition
													$Service_i // int // Optional // Iteration counter for treatment-as-Service
												) ?? null;

											} else {

												$location_condition = null;

											}

											if (
												isset($location_condition)
												&&
												$location_condition
											) {

												// Get names for keywords property

													$location_condition_keywords = uamswp_fad_schema_property_values(
														$location_condition, // array // Required // Property values from which to extract specific values
														array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
													);

											}

										}

								}

							// Associated treatments and procedures

								// List of properties that reference treatments and procedures

									$location_treatment_common = array(
										'availableService',
										'mentions'
									);

								if (
									(
										(
											isset($location_item_MedicalWebPage)
											&&
											array_intersect(
												$location_properties_map[$MedicalWebPage_type]['properties'],
												$location_treatment_common
											)
										)
										||
										(
											isset($location_item_MedicalWebPage)
											&&
											array_intersect(
												$location_properties_map[$MedicalWebPage_type]['properties'],
												$location_treatment_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related treatments

										if ( !isset($location_treatments) ) {

											$location_treatments = get_field( 'location_treatments_cpt', $entity ) ?? array();

										}

									// Format values

										if ( $location_treatments ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_treatment') ) {

												$location_availableService = uamswp_fad_schema_treatment(
													$location_treatments, // array // Required // List of IDs of the service items
													$location_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$Service_i, // int // Optional // Iteration counter for treatment-as-Service
													$MedicalCondition_i // int // Optional // Iteration counter for condition-as-MedicalCondition
												) ?? null;

											} else {

												$location_availableService = null;

											}

											if (
												isset($location_availableService)
												&&
												$location_availableService
											) {

												// Get names for keywords property

													$location_availableService_keywords = uamswp_fad_schema_property_values(
														$location_availableService, // array // Required // Property values from which to extract specific values
														array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
													);

											}

										}

								}

						// Parent location attributes (common use)

							// List of properties that reference parent locations

								$location_parent_attributes_common = array(
									'address',
									'containedInPlace',
									'image',
									'photo'
								);

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									array_intersect(
										$location_properties_map[$MedicalWebPage_type]['properties'],
										$location_parent_attributes_common
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									array_intersect(
										$location_properties_map[$LocalBusiness_type]['properties'],
										$location_parent_attributes_common
									)
								)
							) {

								// Parent location query and ID

									if (
										!isset($location_has_parent)
										||
										(
											$location_has_parent
											&&
											!isset($location_parent_id)
										)
									) {

										$location_has_parent = get_field( 'location_parent', $entity );
										$location_parent_id = $location_has_parent ? get_field( 'location_parent_id', $entity ) : 0;
										$location_has_parent = $location_parent_id ? true : false;

									}

							}

						// Parent entities (common use)

							// List of properties that reference parent Place/Organization

								$location_parent_entity_common = array(
									'containedInPlace'
								);

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										array_intersect(
											$location_properties_map[$MedicalWebPage_type]['properties'],
											$location_parent_entity_common
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										array_intersect(
											$location_properties_map[$LocalBusiness_type]['properties'],
											$location_parent_entity_common
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get parent location

									// Format values (LocalBusiness and subtypes)

										if ( $location_has_parent ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											$location_parent_LocalBusiness = uamswp_fad_schema_location(
												array($location_parent_id), // List of IDs of the location items
												$location_url, // Page URL
												$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
												($nesting_level + 1) // Nesting level within the main schema
											)['LocalBusiness'] ?? array();

										}

								// Facility

									// Query: Is this location contained within a larger facility rather than being its own standalone facility?

										if ( !isset($location_facility_query) ) {

											$location_facility_query = get_field( 'location_building_query', $entity ) ?? null;

										}

									// Get selected Facility term ID

										if ( !isset($location_facility_id) ) {

											if ( $location_facility_query ) {

												$location_facility_id = get_field( 'location_building', $entity ) ?? null;

											}

										}

									// Format values as Place if the location has no parent

										if (
											$location_facility_id
											&&
											isset($location_has_parent)
											&&
											$location_has_parent == false
										) {

											$location_facility_Place = uamswp_fad_schema_facility(
												$location_facility_id, // int // Required // Term ID for the facility
												$nesting_level // Nesting level within the main schema
											);

										}

							}

						// MedicalSpecialty (common use)

							// List of properties that reference MedicalSpecialty

								$location_MedicalSpecialty_common = array(
									'additionalType',
									'medicalSpecialty',
									'specialty'
								);

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									array_intersect(
										$location_properties_map[$MedicalWebPage_type]['properties'],
										$location_MedicalSpecialty_common
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									array_intersect(
										$location_properties_map[$LocalBusiness_type]['properties'],
										$location_MedicalSpecialty_common
									)
								)
							) {

								// Get values

									// Get medicalSpecialty multiselect field value

										if ( !isset($location_medicalSpecialty_multiselect) ) {

											$location_medicalSpecialty_multiselect = get_field( 'schema_medicalspecialty_multiple', $entity ) ?? array();

										}

									// Format value

										// Simple list of MedicalSpecialty values

											$location_medicalSpecialty_list = array();

										// Schema property values

											if ( $location_medicalSpecialty_multiselect ) {

												$location_medicalSpecialty = uamswp_fad_schema_medicalSpecialty_select(
													$location_medicalSpecialty_multiselect, // mixed // Required // MedicalSpecialty select or multi-select field value
													$location_medicalSpecialty_list // Optional // Array to populate with the list of MedicalSpecialty values
												);

											}

							}

						// geo (common use)

							// List of properties that reference geo

								$location_geo_common = array(
									'geo',
									'latitude',
									'longitude'
								);

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									array_intersect(
										$location_properties_map[$MedicalWebPage_type]['properties'],
										$location_geo_common
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									array_intersect(
										$location_properties_map[$LocalBusiness_type]['properties'],
										$location_geo_common
									)
								)
							) {

								// Get values

									if ( !isset($location_geo_value) ) {

										$location_geo_value = get_field( 'location_map', $entity ) ?? array();

										// Check values

											if ( $location_geo_value ) {

												$location_geo_value = ( array_key_exists( 'lat', $location_geo_value ) && array_key_exists( 'lng', $location_geo_value ) ) ? $location_geo_value : array();

											}

									}

							}

						// image (common use)

							// List of properties that reference geo

								$location_image_common = array(
									'image',
									'photo'
								);

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									array_intersect(
										$location_properties_map[$MedicalWebPage_type]['properties'],
										$location_image_common
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									array_intersect(
										$location_properties_map[$LocalBusiness_type]['properties'],
										$location_image_common
									)
								)
							) {

								if ( !isset($location_image_id) ) {

									// Get the various images

										// Base list array

											$location_image_id = array();

										// Parent location overrides

											if ( $location_has_parent ) {

												// Query on whether to override any of the parent location's photos

													if ( !isset($location_override_parent_photo) ) {

														$location_override_parent_photo = get_field('location_image_override_parent') ?? false;

													}

												if ( $location_override_parent_photo ) {

													// Query on whether to override the parent location's featured image

														if ( !isset($location_override_parent_photo_featured) ) {

															$location_override_parent_photo_featured = get_field( 'location_image_override_parent_featured', $entity ) ?? false;

														}

													// Query on whether to override the parent location's wayfinding photo

														if ( !isset($location_override_parent_photo_wayfinding) ) {

															$location_override_parent_photo_wayfinding = get_field( 'location_image_override_parent_wayfinding', $entity ) ?? false;

														}

													// Query on whether to override the parent location's gallery photos

														if ( !isset($location_override_parent_photo_gallery) ) {

															$location_override_parent_photo_gallery = get_field( 'location_image_override_parent_gallery', $entity ) ?? false;

														}

												}

											}

										// Get featured image ID

											if ( !isset($location_featured_image_id) ) {

												if ( !$location_fpage_query ) {

													/* Overview page */

													$location_featured_image_id = $location_override_parent_photo_featured ? get_field( '_thumbnail_id', $location_parent_id ) : get_field( '_thumbnail_id', $entity ); // int

												} elseif ( $location_current_fpage == 'providers' ) {

													/* Fake subpage for related providers */

													$location_featured_image_id = '';

												} elseif ( $location_current_fpage == 'clinics' ) {

													/* Fake subpage for descendant locations */

													$location_featured_image_id = '';

												} elseif ( $location_current_fpage == 'related' ) {

													/* Fake subpage for related locations */

													$location_featured_image_id = '';

												} elseif ( $location_current_fpage == 'expertises' ) {

													/* Fake subpage for related areas of expertise */

													$location_featured_image_id = '';

												} elseif ( $location_current_fpage == 'resources' ) {

													/* Fake subpage for related clinical resources */

													$location_featured_image_id = '';

												}

											}

											// Add to the list of image IDs

												if ( $location_featured_image_id ) {

													$location_image_id[] = $location_featured_image_id;

												}

										// Get wayfinding photo ID

											if ( !isset($location_wayfinding_image_id) ) {

												if ( $nesting_level == 0 ) {

													$location_wayfinding_image_id = $location_override_parent_photo_wayfinding ? get_field( 'location_wayfinding_photo', $location_parent_id ) : get_field('location_wayfinding_photo', $entity ); // int

												}

											}

											// Add to the list of image IDs

												if ( $location_wayfinding_image_id ) {

													$location_image_id[] = $location_wayfinding_image_id;

												}

										// Get gallery photo IDs

											if ( !isset($location_gallery_image_id) ) {

												if ( $nesting_level == 0 ) {

													$location_gallery_image_id = $location_override_parent_photo_wayfinding ? get_field( 'location_photo_gallery', $location_parent_id ) : get_field('location_photo_gallery', $entity ); // array

												}

											}

											// Add to the list of image IDs

												if ( $location_gallery_image_id ) {

													$location_image_id = array_merge(
														$location_image_id,
														$location_gallery_image_id
													);

												}

										// Clean up the list

											if (
												$location_image_id
												&&
												is_array($location_image_id)
											) {

												$location_image_id = array_filter($location_image_id);
												$location_image_id = array_unique( $location_image_id, SORT_REGULAR );
												$location_image_id = array_values($location_image_id);

											}

								}

								// Create ImageObject values array for each image

									// Base array

										$location_image_general = array();

									if ( $location_image_id ) {

										foreach ( $location_image_id as $item ) {

											// Reset variables

												$item_thumbnails = array();

											// Check variables

												if ( $location_image_general ) {

													$location_image_general = is_array($location_image_general) ? $location_image_general : array($location_image_general);
													$location_image_general = array_is_list($location_image_general) ? $location_image_general : array($location_image_general);

												}

											// If item value is empty or 0, skip this iteration

												if ( !$item ) {

													continue;

												}

											// Get values

												$item_thumbnails = uamswp_fad_schema_imageobject_thumbnails(
													$location_url, // URL of entity with which the image is associated
													( $nesting_level + 1 ), // Nesting level within the main schema
													'16:9', // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9')
													'Image', // Base fragment identifier
													$item, // ID of image to use for 1:1 aspect ratio
													0, // ID of image to use for 3:4 aspect ratio
													$item, // ID of image to use for 4:3 aspect ratio
													$item, // ID of image to use for 16:9 aspect ratio
													0 // ID of image to use for full image
												);

											// Add values to list array

												if ( $item_thumbnails ) {

													$item_thumbnails = is_array($item_thumbnails) ? $item_thumbnails : array($item_thumbnails);
													$item_thumbnails = array_is_list($item_thumbnails) ? $item_thumbnails : array($item_thumbnails);

													$location_image_general = array_merge(
														$location_image_general,
														$item_thumbnails
													);

												}

											// Reset variables

												$item_thumbnails = array();

										}

									}

								// Clean up the array

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($location_image_general);

							}

						// Identifiers (common use)

							// Google customer ID (CID)

								// List of properties that reference Google customer ID

									$location_google_cid_common = array(
										'identifier',
										'hasMap'
									);

								if (
									(
										isset($location_item_MedicalWebPage)
										&&
										array_intersect(
											$location_properties_map[$MedicalWebPage_type]['properties'],
											$location_google_cid_common
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										array_intersect(
											$location_properties_map[$LocalBusiness_type]['properties'],
											$location_google_cid_common
										)
									)
								) {

									// Get Google customer ID

										if ( !isset($location_google_cid) ) {

											$location_google_cid = get_field( 'schema_google_cid', $entity ) ?? array();

										}

								}

							// National Provider Identifier (NPI)

								// List of properties that reference National Provider Identifier (NPI)

									$location_npi_common = array(
										'identifier'
									);

								if (
									(
										isset($location_item_MedicalWebPage)
										&&
										array_intersect(
											$location_properties_map[$MedicalWebPage_type]['properties'],
											$location_npi_common
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										array_intersect(
											$location_properties_map[$LocalBusiness_type]['properties'],
											$location_npi_common
										)
									)
								) {

									// Get values

										if ( !isset($location_npi) ) {

											// Base array

												$location_npi = array();

											// Get the NPI repeater value

												$location_npi_repeater = get_field( 'location_npi', $entity ) ?? array();
												$location_npi_repeater = is_array($location_npi_repeater) ? $location_npi_repeater : array();

											// Add each repeater row to the list array

												if (
													$location_npi_repeater
													&&
													is_array($location_npi_repeater)
												) {

													foreach ( $location_npi_repeater as $item ) {

														$location_npi_item = null;

														if (
															$item
															&&
															isset($item['location_npi_item'])
															&&
															!empty($item['location_npi_item'])
														) {

															$location_npi_item = $item['location_npi_item'];

														}

														// Format value

															if ( $location_npi_item ) {

																$location_npi_item = $location_npi_item ? str_pad($location_npi_item, 10, '0', STR_PAD_LEFT) : ''; // Add enough leading zeroes to reach 10 digits

															}

														// Add the value to the list array

															if ( $location_npi_item ) {

																$location_npi[] = $item['location_npi_item'];

															}

													} // endforeach

												} // endif

											// Clean up the list array

												if ( $location_npi ) {

													$location_npi = array_filter($location_npi);
													$location_npi = array_unique( $location_npi, SORT_REGULAR );
												}

												if ( $location_npi ) {

													$location_npi = array_values($location_npi);

													// If there is only one item, flatten the multi-dimensional array by one step

														uamswp_fad_flatten_multidimensional_array($location_npi);

												}

										}

								}

							// United States Department of Veterans Affairs Station ID

								// List of properties that reference National Provider Identifier (NPI)

									$location_va_station_id_common = array(
										'identifier'
									);

								if (
									(
										isset($location_item_MedicalWebPage)
										&&
										array_intersect(
											$location_properties_map[$MedicalWebPage_type]['properties'],
											$location_va_station_id_common
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										array_intersect(
											$location_properties_map[$LocalBusiness_type]['properties'],
											$location_va_station_id_common
										)
									)
								) {

									// Get values

										if ( !isset($location_va_station_id) ) {

											$location_va_station_id = get_field( 'location_va_station_id', $entity ) ?? '';

										}

								}

							// American Hospital Association Hospital Identifier (AHAID)

								// List of properties that reference American Hospital Association Hospital Identifier (AHAID)

									$location_ahaid_common = array(
										'identifier'
									);

								if (
									(
										isset($location_item_MedicalWebPage)
										&&
										array_intersect(
											$location_properties_map[$MedicalWebPage_type]['properties'],
											$location_ahaid_common
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										array_intersect(
											$location_properties_map[$LocalBusiness_type]['properties'],
											$location_ahaid_common
										)
									)
								) {

									// Get values

										if ( !isset($location_ahaid) ) {

											$location_ahaid = get_field( 'location_hospital_aha_id', $entity ) ?? '';

										}

								}

							// Centers for Medicare & Medicaid Services Certification Number (CCN)

								// List of properties that reference Centers for Medicare & Medicaid Services Certification Number (CCN)

									$location_cms_ccn_common = array(
										'identifier'
									);

								if (
									(
										isset($location_item_MedicalWebPage)
										&&
										array_intersect(
											$location_properties_map[$MedicalWebPage_type]['properties'],
											$location_cms_ccn_common
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										array_intersect(
											$location_properties_map[$LocalBusiness_type]['properties'],
											$location_cms_ccn_common
										)
									)
								) {

									// Get values

										if ( !isset($location_cms_ccn) ) {

											$location_cms_ccn = get_field( 'location_hospital_ccn_id', $entity ) ?? '';

										}

								}

						// address (common use)

							// List of properties that reference address

								$location_address_common = array(
									'address',
									'contactPoint'
								);

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									array_intersect(
										$location_properties_map[$MedicalWebPage_type]['properties'],
										$location_address_common
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									array_intersect(
										$location_properties_map[$LocalBusiness_type]['properties'],
										$location_address_common
									)
								)
							) {

								// Base array

									$location_streetAddress_array = array();

								// Base address keywords array

									$location_address_keywords = array();

								// Get values

									// Conditionally get parent location ID

										if ( $location_has_parent ) {

											$location_address_id = $location_parent_id;

										} else {

											$location_address_id = $entity;

										}

									// Address line 1

										if ( !isset($location_address_1) ) {

											$location_address_1 = get_field( 'location_address_1', $location_address_id ) ?? '';

										}

										if ( $location_address_1 ) {

											$location_streetAddress_array[] = $location_address_1;

											// Add values to location keywords

												$location_address_keywords[] = $location_address_1;

										}

									// Address line 2

										// Base array

											$location_address_2_array = array();

										// Facility values

											if ( !isset($location_facility_name) ) {

												// Query: Is this location contained within a larger facility rather than being its own standalone facility?

													if ( !isset($location_facility_query) ) {

														$location_facility_query = get_field('location_building_query', $location_address_id ) ?? null;

													}

												// Get selected Facility term ID

													if ( !isset($location_facility_id) ) {

														if ( $location_facility_query ) {

															$location_facility_id = get_field( 'location_building', $location_address_id ) ?? null;

														}

													}

												// Get building term and its values

													if ( $location_facility_id ) {

														if ( !isset($location_facility_term) ) {

															$location_facility_term = get_term( $location_facility_id, 'building' ) ?? null;

														}

														if (
															$location_facility_term
															&&
															is_object($location_facility_term)
														) {

															$location_facility_type = get_field( 'facility_place_subtype', $location_facility_term ) ?? null;
															$location_facility_slug = $location_facility_term->slug;
															$location_facility_name = $location_facility_term->name;

														}

													}

												// Reset values if building is set to 'None'

													if (
														$location_facility_slug
														&&
														$location_facility_slug == '_none'
													) {

														$location_facility_id = null;
														$location_facility_term = null;
														$location_facility_type = null;
														$location_facility_slug = null;
														$location_facility_name = null;

													}

											}

											// Add to the address 2 array

												if (
													$location_facility_name
													&&
													(
														!isset($location_facility_type)
														||
														$location_facility_type == 'building'
													)
												) {

													$location_address_2_array[] = $location_facility_name;

													// Add values to location keywords

														$location_address_keywords[] = $location_facility_name;

												}

										// Floor values

											// Get building floor selection

												if ( !isset($location_floor_label) ) {

													if ( !isset($location_floor) ) {

														if (
															!isset($location_building_query)
															||
															$location_building_query
														) {

															$location_floor = get_field_object('location_building_floor', $location_address_id );

														}

													}

													// Get building floor values

														if ( $location_floor ) {

															$location_floor_value = $location_floor['value'] ?? null;
															$location_floor_label = $location_floor['choices'][ $location_floor_value ] ?? null;

														}

														// Reset values if building is set to 'Single-Story Building'

															if (
																isset($location_floor_value)
																&&
																!$location_floor_value
															) {

																$location_floor = null;
																$location_floor_value = null;
																$location_floor_label = null;

															}

												}

											// Add to the address 2 array

												if ( $location_floor_label ) {

													$location_address_2_array[] = $location_floor_label;

												}

										// Suite value

											if ( !isset($location_suite) ) {

												$location_suite = get_field(' location_suite', $location_address_id ) ?? '';

											}

											// Add to the address 2 array

												if ( $location_suite ) {

													$location_address_2_array[] = $location_suite;

												}

										// Explode the array and add to streetAddress values array

											if ( $location_address_2_array ) {

												$location_streetAddress_array[] = implode(
													' ',
													$location_address_2_array
												);

											}

									// Combine the lines

										if ( $location_streetAddress_array ) {

											$location_streetAddress = implode(
												' ',
												$location_streetAddress_array
											);

										}

									// City

										if ( !isset($location_addressLocality) ) {

											$location_addressLocality = get_field( 'location_city', $location_address_id ) ?? null;

											// Add values to location keywords

												if ( $location_addressLocality ) {

													$location_address_keywords[] = $location_addressLocality;

												}

										}

									// State

										if ( !isset($location_addressRegion) ) {

											$location_addressRegion = get_field( 'location_state', $location_address_id ) ?? null;

											// Add values to location keywords

												if ( $location_addressRegion ) {

													$location_address_keywords[] = $location_addressRegion;

												}

										}

									// ZIP

										if ( !isset($location_postalCode) ) {

											$location_postalCode = get_field( 'location_zip', $location_address_id ) ?? null;

											// Add values to location keywords

												if ( $location_postalCode ) {

													$location_address_keywords[] = $location_postalCode;

												}

										}

								// Format values as PostalAddress

									if ( !isset($location_address) ) {

										if (
											$location_streetAddress
											&&
											$location_addressLocality
											&&
											$location_addressRegion
											&&
											$location_postalCode
										) {

											$location_address = uamswp_fad_schema_postaladdress(
												'physical address', // string // Required // A person or organization can have different contact points, for different purposes. For example, a sales contact point, a PR contact point and so on. This property is used to specify the kind of contact point.
												$location_streetAddress, // string // Required // The street address or the post office box number for PO box addresses.
												true, // bool // Required // Query for whether the address is a street address (as opposed to a post office box number)
												$location_addressLocality, // string // Required // The locality in which the street address is, and which is in the region. For example, Mountain View.
												$location_addressRegion, // string // Required // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
												$location_postalCode // string // Required // The postal code (e.g., 94043).
											);

										}

									}

								// Add PostalAddress to contactPoint property

									$location_contactPoint = $location_contactPoint ?? array();

									if ( $location_address ) {

										$location_contactPoint[] = $location_address;

									}

								// Merge address keywords value into keywords

									$location_address_keywords = $location_address_keywords ?? null;

									if ( $location_address_keywords ) {

										$location_keywords = uamswp_fad_schema_merge_values(
											$location_keywords, // mixed // Required // Initial schema item property value
											$location_address_keywords // mixed // Required // Incoming schema item property value
										);

									}

							}

						// Query: Can a patient schedule an appointment for services rendered at this location? (common use)

							// List of properties that reference address

								$location_appointments_query_common = array(
									'contactPoint',
									'telephone'
								);

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									array_intersect(
										$location_properties_map[$MedicalWebPage_type]['properties'],
										$location_appointments_query_common
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									array_intersect(
										$location_properties_map[$LocalBusiness_type]['properties'],
										$location_appointments_query_common
									)
								)
							) {

								if ( !isset($location_appointments_query) ) {

									$location_appointments_query = get_field( 'location_appointments_query', $entity ) ?? null;

								}

							}

						// telephone number (common use)

							// List of properties that reference telephone number

								$location_telephone_common = array(
									'contactPoint',
									'telephone'
								);

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									array_intersect(
										$location_properties_map[$MedicalWebPage_type]['properties'],
										$location_telephone_common
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									array_intersect(
										$location_properties_map[$LocalBusiness_type]['properties'],
										$location_telephone_common
									)
								)
							) {

								// Base arrays

									// ContactPoint type

										$location_contactPoint = $location_contactPoint ?? array();

									// Text type

										$location_telephone_Text_array = array();

								// Get values

									// General Information Telephone Number

										$location_telephone_general = $location_telephone_general ?? null;

										if ( !isset($location_telephone_general) ) {

											$location_telephone_general = get_field( 'location_phone', $entity ) ?? null;
											$location_telephone_general = $location_telephone_general ? format_phone_dash($location_telephone_general) : null;

										}

										// Add value to the list arrays

											if ( $location_telephone_general ) {

												// ContactPoint type

													$location_contactPoint = uamswp_fad_schema_contactpoint(
														'https://www.wikidata.org/wiki/Q214995', // string|array // Optional // additionalType // An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. // Allowed schema types: 'Text', 'URL'
														null, // string|array // Optional // 'areaServed' // The geographic area where a service or offered item is provided. // Allowed schema types: 'AdministrativeArea', 'GeoShape', 'Place', 'Text'
														null, // string|array // Optional // 'availableLanguage' // A language someone may use with or at the item, service or place. Must use one of the language codes from the IETF BCP 47 standard. // Allowed schema types: 'Language', 'Text'
														null, // string|array enum('HearingImpairedSupported', 'TollFree') // Optional // 'contactOption' // An option available on this contact point. // Allowed schema types: 'ContactPointOption'
														'general information', // string // Optional // 'contactType' // A person or organization can have different contact points, for different purposes (e.g., sales, PR, bill payment, customer service, technical support). This property is used to specify the kind of contact point. // Allowed schema types: 'Text'
														null, // string // Optional // 'description' // A description of the item. // Allowed schema types: 'Text'
														null, // string // Optional // 'disambiguatingDescription' // A short description of the item used to disambiguate from other, similar items. // Allowed schema types: 'Text'
														null, // string // Optional // 'email' // Email address. // Allowed schema types: 'Text'
														null, // string // Optional // 'faxNumber' // The fax number. // Allowed schema types: 'Text'
														null, // array // Optional // 'hoursAvailable' // The hours during which this service or contact is available. // Allowed schema types: 'OpeningHoursSpecification'
														null, // string|array // Optional // 'mainEntityOfPage' // Indicates a page (or other CreativeWork) for which this thing is the main entity being described. // Allowed schema types: 'CreativeWork', 'URL'
														null, // array // Optional // 'potentialAction' // Indicates a potential Action, which describes an idealized action in which this thing would play an 'object' role. // Allowed schema types: 'Action'
														null, // string|array // Optional // 'productSupported' // The product or service this support contact point is related to (such as product support for a particular product line). // Allowed schema types: 'Product  or Text'
														null, // string|array // Optional // 'sameAs' // URL of a reference Web page that unambiguously indicates the item's identity (e.g., the item's Wikipedia page, the item's Wikidata entry, the item's official website). // Allowed schema types: 'URL'
														null, // array // Optional // 'subjectOf' // A CreativeWork or Event about this Thing. // Allowed schema types: 'CreativeWork', 'Event'
														$location_telephone_general, // string // Optional // 'telephone' // The telephone number. // Allowed schema types: 'Text'
														null, // string // Optional // 'url' // URL of the item. // Allowed schema types: 'URL'
														$location_contactPoint // array // Optional // Pre-existing list array of ContactPoint items to which to add additional items
													);

												// Text type

													$location_telephone_Text_array[] = $location_telephone_general;

											}

									// Appointments Telephone Number

										if ( $location_appointments_query ) {

											$location_specific_clinical_organization_slug = $location_specific_clinical_organization_slug ?? array();

											// Query: Does this Arkansas Children's location have separate telephone numbers for primary care appointments and specialty care appointments?

												$location_telephone_appointment_ac_query = $location_telephone_appointment_ac_query ?? null;

												if (
													in_array(
														$brand_organization_slug_arkansas_childrens,
														$location_specific_clinical_organization_slug
													)
												) {

													if ( !isset($location_telephone_appointment_ac_query) ) {

														$location_telephone_appointment_ac_query = get_field( 'location_ac_appointments_query', $entity ) ?? null;

													}

												}

											if ( $location_telephone_appointment_ac_query ) {

												// Arkansas Children's primary care and specialty care appointments telephone numbers

													// Primary care appointments telephone number

														$location_telephone_appointment_ac_primary = $location_telephone_appointment_ac_primary ?? null;

														if ( !isset($location_telephone_appointment_ac_primary) ) {

															$location_telephone_appointment_ac_primary = get_field( 'location_ac_appointments_primary', $entity ) ?? null;
															$location_telephone_appointment_ac_primary = $location_telephone_appointment_ac_primary ? format_phone_dash($location_telephone_appointment_ac_primary) : null;

														}

														// Add value to the list arrays

															if ( $location_telephone_appointment_ac_primary ) {

																// ContactPoint type

																	$location_contactPoint = uamswp_fad_schema_contactpoint(
																		'https://www.wikidata.org/wiki/Q214995', // string|array // Optional // additionalType // An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. // Allowed schema types: 'Text', 'URL'
																		null, // string|array // Optional // 'areaServed' // The geographic area where a service or offered item is provided. // Allowed schema types: 'AdministrativeArea', 'GeoShape', 'Place', 'Text'
																		null, // string|array // Optional // 'availableLanguage' // A language someone may use with or at the item, service or place. Must use one of the language codes from the IETF BCP 47 standard. // Allowed schema types: 'Language', 'Text'
																		null, // string|array enum('HearingImpairedSupported', 'TollFree') // Optional // 'contactOption' // An option available on this contact point. // Allowed schema types: 'ContactPointOption'
																		'appointment scheduling for primary care', // string // Optional // 'contactType' // A person or organization can have different contact points, for different purposes (e.g., sales, PR, bill payment, customer service, technical support). This property is used to specify the kind of contact point. // Allowed schema types: 'Text'
																		null, // string // Optional // 'description' // A description of the item. // Allowed schema types: 'Text'
																		null, // string // Optional // 'disambiguatingDescription' // A short description of the item used to disambiguate from other, similar items. // Allowed schema types: 'Text'
																		null, // string // Optional // 'email' // Email address. // Allowed schema types: 'Text'
																		null, // string // Optional // 'faxNumber' // The fax number. // Allowed schema types: 'Text'
																		null, // array // Optional // 'hoursAvailable' // The hours during which this service or contact is available. // Allowed schema types: 'OpeningHoursSpecification'
																		null, // string|array // Optional // 'mainEntityOfPage' // Indicates a page (or other CreativeWork) for which this thing is the main entity being described. // Allowed schema types: 'CreativeWork', 'URL'
																		null, // array // Optional // 'potentialAction' // Indicates a potential Action, which describes an idealized action in which this thing would play an 'object' role. // Allowed schema types: 'Action'
																		null, // string|array // Optional // 'productSupported' // The product or service this support contact point is related to (such as product support for a particular product line). // Allowed schema types: 'Product  or Text'
																		null, // string|array // Optional // 'sameAs' // URL of a reference Web page that unambiguously indicates the item's identity (e.g., the item's Wikipedia page, the item's Wikidata entry, the item's official website). // Allowed schema types: 'URL'
																		null, // array // Optional // 'subjectOf' // A CreativeWork or Event about this Thing. // Allowed schema types: 'CreativeWork', 'Event'
																		$location_telephone_appointment_ac_primary, // string // Optional // 'telephone' // The telephone number. // Allowed schema types: 'Text'
																		null, // string // Optional // 'url' // URL of the item. // Allowed schema types: 'URL'
																		$location_contactPoint // array // Optional // Pre-existing list array of ContactPoint items to which to add additional items
																	);

																// Text type

																	$location_telephone_Text_array[] = $location_telephone_appointment_ac_primary;

															}

													// Specialty care appointments telephone number

														$location_telephone_appointment_ac_specialty = $location_telephone_appointment_ac_specialty ?? null;

														if ( !isset($location_telephone_appointment_ac_specialty) ) {

															$location_telephone_appointment_ac_specialty = get_field( 'location_ac_appointments_specialty', $entity ) ?? null;
															$location_telephone_appointment_ac_specialty = $location_telephone_appointment_ac_specialty ? format_phone_dash($location_telephone_appointment_ac_specialty) : null;

														}

														// Add value to the list arrays

															if ( $location_telephone_appointment_ac_specialty ) {

																// ContactPoint type

																	$location_contactPoint = uamswp_fad_schema_contactpoint(
																		'https://www.wikidata.org/wiki/Q214995', // string|array // Optional // additionalType // An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. // Allowed schema types: 'Text', 'URL'
																		null, // string|array // Optional // 'areaServed' // The geographic area where a service or offered item is provided. // Allowed schema types: 'AdministrativeArea', 'GeoShape', 'Place', 'Text'
																		null, // string|array // Optional // 'availableLanguage' // A language someone may use with or at the item, service or place. Must use one of the language codes from the IETF BCP 47 standard. // Allowed schema types: 'Language', 'Text'
																		null, // string|array enum('HearingImpairedSupported', 'TollFree') // Optional // 'contactOption' // An option available on this contact point. // Allowed schema types: 'ContactPointOption'
																		'appointment scheduling for specialty care', // string // Optional // 'contactType' // A person or organization can have different contact points, for different purposes (e.g., sales, PR, bill payment, customer service, technical support). This property is used to specify the kind of contact point. // Allowed schema types: 'Text'
																		null, // string // Optional // 'description' // A description of the item. // Allowed schema types: 'Text'
																		null, // string // Optional // 'disambiguatingDescription' // A short description of the item used to disambiguate from other, similar items. // Allowed schema types: 'Text'
																		null, // string // Optional // 'email' // Email address. // Allowed schema types: 'Text'
																		null, // string // Optional // 'faxNumber' // The fax number. // Allowed schema types: 'Text'
																		null, // array // Optional // 'hoursAvailable' // The hours during which this service or contact is available. // Allowed schema types: 'OpeningHoursSpecification'
																		null, // string|array // Optional // 'mainEntityOfPage' // Indicates a page (or other CreativeWork) for which this thing is the main entity being described. // Allowed schema types: 'CreativeWork', 'URL'
																		null, // array // Optional // 'potentialAction' // Indicates a potential Action, which describes an idealized action in which this thing would play an 'object' role. // Allowed schema types: 'Action'
																		null, // string|array // Optional // 'productSupported' // The product or service this support contact point is related to (such as product support for a particular product line). // Allowed schema types: 'Product  or Text'
																		null, // string|array // Optional // 'sameAs' // URL of a reference Web page that unambiguously indicates the item's identity (e.g., the item's Wikipedia page, the item's Wikidata entry, the item's official website). // Allowed schema types: 'URL'
																		null, // array // Optional // 'subjectOf' // A CreativeWork or Event about this Thing. // Allowed schema types: 'CreativeWork', 'Event'
																		$location_telephone_appointment_ac_specialty, // string // Optional // 'telephone' // The telephone number. // Allowed schema types: 'Text'
																		null, // string // Optional // 'url' // URL of the item. // Allowed schema types: 'URL'
																		$location_contactPoint // array // Optional // Pre-existing list array of ContactPoint items to which to add additional items
																	);

																// Text type

																	$location_telephone_Text_array[] = $location_telephone_appointment_ac_specialty;

															}

											} else {

												// All other appointment telephone numbers

													// Query: Is there a separate appointment telephone number for patients?

														$location_telephone_appointment_query = $location_telephone_appointment_query ?? null;

														if ( !isset($location_telephone_appointment_query) ) {

															$location_telephone_appointment_query = get_field( 'location_clinic_phone_query', $entity ) ?? false;

														}

													// Query: Is there a separate appointment telephone number for patients?

														$location_telephone_appointment_existing_query = $location_telephone_appointment_existing_query ?? null;

														if ( $location_telephone_appointment_query ) {

															if ( !isset($location_telephone_appointment_existing_query) ) {

																$location_telephone_appointment_existing_query = get_field( 'location_appointment_phone_query', $entity ) ?? false;

															}

														}

													// New Patients

														$location_telephone_appointment_new = $location_telephone_appointment_new ?? null;

														if ( $location_telephone_appointment_query ) {

															if ( !isset($location_telephone_appointment_new) ) {

																$location_telephone_appointment_new = get_field( 'location_new_appointments_phone', $entity ) ?? null;
																$location_telephone_appointment_new = $location_telephone_appointment_new ? format_phone_dash($location_telephone_appointment_new) : null;

															}

														} else {

															$location_telephone_appointment_new = $location_telephone_general;

														}

														// Add value to the list arrays

															if ( $location_telephone_appointment_new ) {

																// ContactPoint type

																	$location_contactPoint = uamswp_fad_schema_contactpoint(
																		'https://www.wikidata.org/wiki/Q214995', // string|array // Optional // additionalType // An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. // Allowed schema types: 'Text', 'URL'
																		null, // string|array // Optional // 'areaServed' // The geographic area where a service or offered item is provided. // Allowed schema types: 'AdministrativeArea', 'GeoShape', 'Place', 'Text'
																		null, // string|array // Optional // 'availableLanguage' // A language someone may use with or at the item, service or place. Must use one of the language codes from the IETF BCP 47 standard. // Allowed schema types: 'Language', 'Text'
																		null, // string|array enum('HearingImpairedSupported', 'TollFree') // Optional // 'contactOption' // An option available on this contact point. // Allowed schema types: 'ContactPointOption'
																		'appointment scheduling for new patients', // string // Optional // 'contactType' // A person or organization can have different contact points, for different purposes (e.g., sales, PR, bill payment, customer service, technical support). This property is used to specify the kind of contact point. // Allowed schema types: 'Text'
																		null, // string // Optional // 'description' // A description of the item. // Allowed schema types: 'Text'
																		null, // string // Optional // 'disambiguatingDescription' // A short description of the item used to disambiguate from other, similar items. // Allowed schema types: 'Text'
																		null, // string // Optional // 'email' // Email address. // Allowed schema types: 'Text'
																		null, // string // Optional // 'faxNumber' // The fax number. // Allowed schema types: 'Text'
																		null, // array // Optional // 'hoursAvailable' // The hours during which this service or contact is available. // Allowed schema types: 'OpeningHoursSpecification'
																		null, // string|array // Optional // 'mainEntityOfPage' // Indicates a page (or other CreativeWork) for which this thing is the main entity being described. // Allowed schema types: 'CreativeWork', 'URL'
																		null, // array // Optional // 'potentialAction' // Indicates a potential Action, which describes an idealized action in which this thing would play an 'object' role. // Allowed schema types: 'Action'
																		null, // string|array // Optional // 'productSupported' // The product or service this support contact point is related to (such as product support for a particular product line). // Allowed schema types: 'Product  or Text'
																		null, // string|array // Optional // 'sameAs' // URL of a reference Web page that unambiguously indicates the item's identity (e.g., the item's Wikipedia page, the item's Wikidata entry, the item's official website). // Allowed schema types: 'URL'
																		null, // array // Optional // 'subjectOf' // A CreativeWork or Event about this Thing. // Allowed schema types: 'CreativeWork', 'Event'
																		$location_telephone_appointment_new, // string // Optional // 'telephone' // The telephone number. // Allowed schema types: 'Text'
																		null, // string // Optional // 'url' // URL of the item. // Allowed schema types: 'URL'
																		$location_contactPoint // array // Optional // Pre-existing list array of ContactPoint items to which to add additional items
																	);

																// Text type

																	$location_telephone_Text_array[] = $location_telephone_appointment_new;

															}

													// Existing Patients

														$location_telephone_appointment_existing = $location_telephone_appointment_existing ?? null;

														if (
															$location_telephone_appointment_query
															&&
															$location_telephone_appointment_existing_query
														) {

															if ( !isset($location_telephone_appointment_existing) ) {

																$location_telephone_appointment_existing = get_field( 'location_return_appointments_phone', $entity ) ?? null;
																$location_telephone_appointment_existing = $location_telephone_appointment_existing ? format_phone_dash($location_telephone_appointment_existing) : null;

															}

														} else {

															$location_telephone_appointment_existing = $location_telephone_appointment_new;

														}

														// Add value to the list arrays

															if ( $location_telephone_appointment_existing ) {

																// ContactPoint type

																	$location_contactPoint = uamswp_fad_schema_contactpoint(
																		'https://www.wikidata.org/wiki/Q214995', // string|array // Optional // additionalType // An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. // Allowed schema types: 'Text', 'URL'
																		null, // string|array // Optional // 'areaServed' // The geographic area where a service or offered item is provided. // Allowed schema types: 'AdministrativeArea', 'GeoShape', 'Place', 'Text'
																		null, // string|array // Optional // 'availableLanguage' // A language someone may use with or at the item, service or place. Must use one of the language codes from the IETF BCP 47 standard. // Allowed schema types: 'Language', 'Text'
																		null, // string|array enum('HearingImpairedSupported', 'TollFree') // Optional // 'contactOption' // An option available on this contact point. // Allowed schema types: 'ContactPointOption'
																		'appointment scheduling for returning patients', // string // Optional // 'contactType' // A person or organization can have different contact points, for different purposes (e.g., sales, PR, bill payment, customer service, technical support). This property is used to specify the kind of contact point. // Allowed schema types: 'Text'
																		null, // string // Optional // 'description' // A description of the item. // Allowed schema types: 'Text'
																		null, // string // Optional // 'disambiguatingDescription' // A short description of the item used to disambiguate from other, similar items. // Allowed schema types: 'Text'
																		null, // string // Optional // 'email' // Email address. // Allowed schema types: 'Text'
																		null, // string // Optional // 'faxNumber' // The fax number. // Allowed schema types: 'Text'
																		null, // array // Optional // 'hoursAvailable' // The hours during which this service or contact is available. // Allowed schema types: 'OpeningHoursSpecification'
																		null, // string|array // Optional // 'mainEntityOfPage' // Indicates a page (or other CreativeWork) for which this thing is the main entity being described. // Allowed schema types: 'CreativeWork', 'URL'
																		null, // array // Optional // 'potentialAction' // Indicates a potential Action, which describes an idealized action in which this thing would play an 'object' role. // Allowed schema types: 'Action'
																		null, // string|array // Optional // 'productSupported' // The product or service this support contact point is related to (such as product support for a particular product line). // Allowed schema types: 'Product  or Text'
																		null, // string|array // Optional // 'sameAs' // URL of a reference Web page that unambiguously indicates the item's identity (e.g., the item's Wikipedia page, the item's Wikidata entry, the item's official website). // Allowed schema types: 'URL'
																		null, // array // Optional // 'subjectOf' // A CreativeWork or Event about this Thing. // Allowed schema types: 'CreativeWork', 'Event'
																		$location_telephone_appointment_existing, // string // Optional // 'telephone' // The telephone number. // Allowed schema types: 'Text'
																		null, // string // Optional // 'url' // URL of the item. // Allowed schema types: 'URL'
																		$location_contactPoint // array // Optional // Pre-existing list array of ContactPoint items to which to add additional items
																	);

																// Text type

																	$location_telephone_Text_array[] = $location_telephone_appointment_existing;

															}

												}

										}

									// Additional Phone Numbers

										// Get the Additional Phone Numbers repeater

											if ( !isset($location_telephone_additional_repeater) ) {

												$location_telephone_additional_repeater = get_field( 'location_phone_numbers', $entity ) ?? null;

											}

										// Add each item to an array

											if ( $location_telephone_additional_repeater ) {

												foreach ( $location_telephone_additional_repeater as $item ) {

													// Eliminate PHP errors

														$item_value = null;
														$item_contactType = null;
														$item_telephone = null;
														$item_description = null;

													// Get the values of the subfields

														if ( $item ) {

															$item_contactType = $item['location_appointments_text'] ?? null;
															$item_description = $item['location_appointments_additional_text'] ?? null;
															$item_telephone = $item['location_appointments_phone'] ?? null;
															$item_telephone = $item_telephone ? format_phone_dash($item_telephone) : null;

														}

													// Add the item to the list arrays

														if ( $item_telephone ) {

															// ContactPoint type

																$location_contactPoint = uamswp_fad_schema_contactpoint(
																	'https://www.wikidata.org/wiki/Q214995', // string|array // Optional // additionalType // An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. // Allowed schema types: 'Text', 'URL'
																	null, // string|array // Optional // 'areaServed' // The geographic area where a service or offered item is provided. // Allowed schema types: 'AdministrativeArea', 'GeoShape', 'Place', 'Text'
																	null, // string|array // Optional // 'availableLanguage' // A language someone may use with or at the item, service or place. Must use one of the language codes from the IETF BCP 47 standard. // Allowed schema types: 'Language', 'Text'
																	null, // string|array enum('HearingImpairedSupported', 'TollFree') // Optional // 'contactOption' // An option available on this contact point. // Allowed schema types: 'ContactPointOption'
																	$item_contactType, // string // Optional // 'contactType' // A person or organization can have different contact points, for different purposes (e.g., sales, PR, bill payment, customer service, technical support). This property is used to specify the kind of contact point. // Allowed schema types: 'Text'
																	$item_description, // string // Optional // 'description' // A description of the item. // Allowed schema types: 'Text'
																	null, // string // Optional // 'disambiguatingDescription' // A short description of the item used to disambiguate from other, similar items. // Allowed schema types: 'Text'
																	null, // string // Optional // 'email' // Email address. // Allowed schema types: 'Text'
																	null, // string // Optional // 'faxNumber' // The fax number. // Allowed schema types: 'Text'
																	null, // array // Optional // 'hoursAvailable' // The hours during which this service or contact is available. // Allowed schema types: 'OpeningHoursSpecification'
																	null, // string|array // Optional // 'mainEntityOfPage' // Indicates a page (or other CreativeWork) for which this thing is the main entity being described. // Allowed schema types: 'CreativeWork', 'URL'
																	null, // array // Optional // 'potentialAction' // Indicates a potential Action, which describes an idealized action in which this thing would play an 'object' role. // Allowed schema types: 'Action'
																	null, // string|array // Optional // 'productSupported' // The product or service this support contact point is related to (such as product support for a particular product line). // Allowed schema types: 'Product  or Text'
																	null, // string|array // Optional // 'sameAs' // URL of a reference Web page that unambiguously indicates the item's identity (e.g., the item's Wikipedia page, the item's Wikidata entry, the item's official website). // Allowed schema types: 'URL'
																	null, // array // Optional // 'subjectOf' // A CreativeWork or Event about this Thing. // Allowed schema types: 'CreativeWork', 'Event'
																	$item_telephone, // string // Optional // 'telephone' // The telephone number. // Allowed schema types: 'Text'
																	null, // string // Optional // 'url' // URL of the item. // Allowed schema types: 'URL'
																	$location_contactPoint // array // Optional // Pre-existing list array of ContactPoint items to which to add additional items
																);

															// Text type

																$location_telephone_Text_array[] = $item_telephone;

														}

												}

											}

							}

						// fax number (common use)

							// List of properties that reference fax number

								$location_faxNumber_common = array(
									'faxNumber',
									'contactPoint'
								);

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									array_intersect(
										$location_properties_map[$MedicalWebPage_type]['properties'],
										$location_faxNumber_common
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									array_intersect(
										$location_properties_map[$LocalBusiness_type]['properties'],
										$location_faxNumber_common
									)
								)
							) {

								// Base arrays

									// ContactPoint type

										$location_contactPoint = $location_contactPoint ?? array();

									// Text type

										$location_faxNumber_Text_array = array();

								// Get values

									// Clinic Fax Number

										if ( !isset($location_faxNumber) ) {

											$location_faxNumber = get_field( 'location_fax', $entity ) ?? null;
											$location_faxNumber = $location_faxNumber ? format_phone_dash($location_faxNumber) : null;

										}

								// Add the item to the list arrays

									if ( $location_faxNumber ) {

										// ContactPoint type

											$location_contactPoint = uamswp_fad_schema_contactpoint(
												'https://www.wikidata.org/wiki/Q214995', // string|array // Optional // additionalType // An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. // Allowed schema types: 'Text', 'URL'
												null, // string|array // Optional // 'areaServed' // The geographic area where a service or offered item is provided. // Allowed schema types: 'AdministrativeArea', 'GeoShape', 'Place', 'Text'
												null, // string|array // Optional // 'availableLanguage' // A language someone may use with or at the item, service or place. Must use one of the language codes from the IETF BCP 47 standard. // Allowed schema types: 'Language', 'Text'
												null, // string|array enum('HearingImpairedSupported', 'TollFree') // Optional // 'contactOption' // An option available on this contact point. // Allowed schema types: 'ContactPointOption'
												'fax', // string // Optional // 'contactType' // A person or organization can have different contact points, for different purposes (e.g., sales, PR, bill payment, customer service, technical support). This property is used to specify the kind of contact point. // Allowed schema types: 'Text'
												null, // string // Optional // 'description' // A description of the item. // Allowed schema types: 'Text'
												null, // string // Optional // 'disambiguatingDescription' // A short description of the item used to disambiguate from other, similar items. // Allowed schema types: 'Text'
												null, // string // Optional // 'email' // Email address. // Allowed schema types: 'Text'
												$location_faxNumber, // string // Optional // 'faxNumber' // The fax number. // Allowed schema types: 'Text'
												null, // array // Optional // 'hoursAvailable' // The hours during which this service or contact is available. // Allowed schema types: 'OpeningHoursSpecification'
												null, // string|array // Optional // 'mainEntityOfPage' // Indicates a page (or other CreativeWork) for which this thing is the main entity being described. // Allowed schema types: 'CreativeWork', 'URL'
												null, // array // Optional // 'potentialAction' // Indicates a potential Action, which describes an idealized action in which this thing would play an 'object' role. // Allowed schema types: 'Action'
												null, // string|array // Optional // 'productSupported' // The product or service this support contact point is related to (such as product support for a particular product line). // Allowed schema types: 'Product  or Text'
												null, // string|array // Optional // 'sameAs' // URL of a reference Web page that unambiguously indicates the item's identity (e.g., the item's Wikipedia page, the item's Wikidata entry, the item's official website). // Allowed schema types: 'URL'
												null, // array // Optional // 'subjectOf' // A CreativeWork or Event about this Thing. // Allowed schema types: 'CreativeWork', 'Event'
												null, // string // Optional // 'telephone' // The telephone number. // Allowed schema types: 'Text'
												null, // string // Optional // 'url' // URL of the item. // Allowed schema types: 'URL'
												$location_contactPoint // array // Optional // Pre-existing list array of ContactPoint items to which to add additional items
											);

										// Text type

											$location_faxNumber_Text_array[] = $location_faxNumber;

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
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'name',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'name',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Get values

									if ( !isset($location_name) ) {

										$location_name = uamswp_attr_conversion(get_the_title($entity)) ?? '';

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'name', // string // Required // Name of schema property
											$location_name, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'name', // string // Required // Name of schema property
											$location_name, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
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

							// additionalType (MedicalWebPage)

								if (
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'additionalType',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
								) {

									// Get values

										$location_additionalType_MedicalWebPage = 'https://schema.org/ProfilePage';

									// Clean up additionalType property values array

										if (
											$location_additionalType_MedicalWebPage
											&&
											is_array($location_additionalType_MedicalWebPage)
										) {

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array($location_additionalType_MedicalWebPage);

										}

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'additionalType', // string // Required // Name of schema property
												$location_additionalType_MedicalWebPage, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$location_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								}

							// additionalType (LocalBusiness)

								if (
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'additionalType',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								) {

									// Get values

										// Base property values array

											$location_additionalType_LocalBusiness = array();

										// Get medicalSpecialty values that match MedicalBusiness subtypes and add to property values

											// Cross-reference the lists

												if ( $location_medicalSpecialty_list ) {

													$location_additionalType_medicalSpecialty = array_intersect(
														$location_valid_types_url,
														$location_medicalSpecialty_list
													);

													$location_additionalType_medicalSpecialty = array_values($location_additionalType_medicalSpecialty);

												}

											// Merge value into the additionalType property values array

												$location_additionalType_medicalSpecialty = $location_additionalType_medicalSpecialty ?? null;

												if ( $location_additionalType_medicalSpecialty ) {

													$location_additionalType_LocalBusiness = uamswp_fad_schema_merge_values(
														$location_additionalType_LocalBusiness, // mixed // Required // Initial schema item property value
														$location_additionalType_medicalSpecialty // mixed // Required // Incoming schema item property value
													);

												}

										// Get additionalType field list

											// Get additionalType repeater field value

												if ( !isset($location_additionalType_repeater) ) {

													$location_additionalType_repeater = get_field( 'schema_additionalType', $entity ) ?? array();

												}

											// Add each item to an array

												if ( $location_additionalType_repeater ) {

													$location_additionalType_field = uamswp_fad_schema_additionaltype(
														$location_additionalType_repeater, // additionalType repeater field
														'schema_additionalType_uri' // additionalType item field name
													);

												}

											// Merge value into the additionalType property values array

												$location_additionalType_field = $location_additionalType_field ?? null;

												if ( $location_additionalType_field ) {

													$location_additionalType_LocalBusiness = uamswp_fad_schema_merge_values(
														$location_additionalType_LocalBusiness, // mixed // Required // Initial schema item property value
														$location_additionalType_field // mixed // Required // Incoming schema item property value
													);

												}

									// Add to item values

										// LocalBusiness

											uamswp_fad_schema_add_to_item_values(
												$LocalBusiness_type, // string // Required // The @type value for the schema item
												$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'additionalType', // string // Required // Name of schema property
												$location_additionalType_LocalBusiness, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$location_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								}

						// address

							/**
								* Physical address of the item.
								*
								* Values expected to be one of these types:
								*
								*      - PostalAddress
								*      - Text
								*/

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'address',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'address',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'address', // string // Required // Name of schema property
											$location_address, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'address', // string // Required // Name of schema property
											$location_address, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// aggregateRating [WIP]

							/**
								* The overall rating, based on a collection of reviews or ratings, of the item.
								*
								* Values expected to be one of these types:
								*
								*      - AggregateRating
								*/

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
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'alternateName',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'alternateName',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get alternateName values

									// Base array

										$location_alternateName = $location_alternateName ?? array();
										$location_alternateName = is_array($location_alternateName) ? $location_alternateName : array($location_alternateName);

									// alternateName repeater

										// Get alternateName repeater field value

											if ( !isset($location_alternateName_repeater) ) {

												$location_alternateName_repeater = get_field( 'schema_alternatename', $entity ) ?? array();

											}

										// Add each item to alternateName property values array

											if ( $location_alternateName_repeater ) {

												$location_alternateName = uamswp_fad_schema_alternatename(
													$location_alternateName_repeater, // array // Required // alternateName repeater field
													'schema_alternatename_text', // string // Optional // alternateName item field name
													$location_alternateName // mixed // Optional // Pre-existing schema array for alternateName to which to add alternateName items
												);

											}

									// American Hospital Association hospital identifier (AHAID) record name

										if ( !isset($location_ahaid_name) ) {

											$location_ahaid_name = get_field( 'location_hospital_aha_name', $entity ) ?? '';

										}

										$location_ahaid_name = $location_ahaid_name ?? null;

										if ( $location_ahaid_name ) {

											$location_alternateName = uamswp_fad_schema_merge_values(
												$location_alternateName, // mixed // Required // Initial schema item property value
												$location_ahaid_name // mixed // Required // Incoming schema item property value
											);

										}

									// Centers for Medicare & Medicaid Services Certification Number (CCN) record name

										if ( !isset($location_cms_ccn_name) ) {

											$location_cms_ccn_name = get_field( 'location_hospital_ccn_name', $entity ) ?? '';

										}

										$location_cms_ccn_name = $location_cms_ccn_name ?? null;

										if ( $location_cms_ccn_name ) {

											$location_alternateName = uamswp_fad_schema_merge_values(
												$location_alternateName, // mixed // Required // Initial schema item property value
												$location_cms_ccn_name // mixed // Required // Incoming schema item property value
											);

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'alternateName', // string // Required // Name of schema property
											$location_alternateName, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'alternateName', // string // Required // Name of schema property
											$location_alternateName, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// availableService

							/**
								* A medical service available from this provider.
								*
								* Values expected to be one of these types:
								*
								*      - MedicalProcedure
								*      - MedicalTest
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'availableService',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'availableService',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'availableService', // string // Required // Name of schema property
											$location_availableService, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'availableService', // string // Required // Name of schema property
											$location_availableService, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Merge availableService significantLink value into significantLink

									$location_availableService_significantLink = $location_availableService_significantLink ?? null;

									if ( $location_availableService_significantLink ) {

										$location_significantLink = uamswp_fad_schema_merge_values(
											$location_significantLink, // mixed // Required // Initial schema item property value
											$location_availableService_significantLink // mixed // Required // Incoming schema item property value
										);

									}

								// Merge availableService keywords value into keywords

									$location_availableService_keywords = $location_availableService_keywords ?? null;

									if ( $location_availableService_keywords ) {

										$location_keywords = uamswp_fad_schema_merge_values(
											$location_keywords, // mixed // Required // Initial schema item property value
											$location_availableService_keywords // mixed // Required // Incoming schema item property value
										);

									}

							}

						// award [WIP]

							/**
								* An award won by or for this item.
								*
								* Values expected to be one of these types:
								*
								*      - Text
								*/

						// brand

							/**
								* The brand(s) associated with a product or service, or the brand(s) maintained
								* by an organization or business person.
								*
								* Values expected to be one of these types:
								*
								*      - Brand
								*      - Organization
								*/

							// Get names for keywords property

								// Base array

									$location_brand_keywords = array();

								// WebSite and MedicalWebPage only

									if (
										$schema_common_brand_MedicalWebPage
										&&
										(
											isset($location_item_MedicalWebPage)
											&&
											in_array(
												'brand',
												$location_properties_map[$MedicalWebPage_type]['properties']
											)
										)
									) {

										$location_brand_keywords = uamswp_fad_schema_property_values(
											$schema_common_brand_MedicalWebPage, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ), // mixed // Required // List of properties from which to collect values
											$location_brand_keywords // mixed // Optional // Pre-existing list to which to add additional items
										);

									}

								// Excluding WebSite and MedicalWebPage

									if (
										$schema_common_brand_exclude_MedicalWebPage
										&&
										(
											isset($location_item_LocalBusiness)
											&&
											in_array(
												'brand',
												$location_properties_map[$LocalBusiness_type]['properties']
											)
										)
									) {

										$location_brand_keywords = uamswp_fad_schema_property_values(
											$schema_common_brand_exclude_MedicalWebPage, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ), // mixed // Required // List of properties from which to collect values
											$location_brand_keywords // mixed // Optional // Pre-existing list to which to add additional items
										);

									}

								// Merge brand keywords value into keywords

									$location_brand_keywords = $location_brand_keywords ?? null;

									if ( $location_brand_keywords ) {

										$location_keywords = uamswp_fad_schema_merge_values(
											$location_keywords, // mixed // Required // Initial schema item property value
											$location_brand_keywords // mixed // Required // Incoming schema item property value
										);

									}

						// contactPoint

							/**
								* A contact point for a person or organization.
								*
								*      - ContactPoint
								*/

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'contactPoint',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'contactPoint',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'contactPoint', // string // Required // Name of schema property
											$location_contactPoint, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'contactPoint', // string // Required // Name of schema property
											$location_contactPoint, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// containedInPlace

							/**
								* The basic containment relation between a place and one that contains it.
								* expected to be one of these types:
								*
								*      - Place
								*/

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'containedInPlace',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'containedInPlace',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Get values

									// Base array

										$location_containedInPlace = array();

									// Merge in parent location (LocalBusiness) value

										$location_parent_LocalBusiness = $location_parent_LocalBusiness ?? null;

										if ( $location_parent_LocalBusiness ) {

											$location_containedInPlace = uamswp_fad_schema_merge_values(
												$location_containedInPlace, // mixed // Required // Initial schema item property value
												$location_parent_LocalBusiness // mixed // Required // Incoming schema item property value
											);

										}

									// Merge in facility (Place) value

										$location_facility_Place = $location_facility_Place ?? null;

										if ( $location_facility_Place ) {

											$location_containedInPlace = uamswp_fad_schema_merge_values(
												$location_containedInPlace, // mixed // Required // Initial schema item property value
												$location_facility_Place // mixed // Required // Incoming schema item property value
											);

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'containedInPlace', // string // Required // Name of schema property
											$location_containedInPlace, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'containedInPlace', // string // Required // Name of schema property
											$location_containedInPlace, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// containsPlace

							/**
								* The basic containment relation between a place and another that it contains.
								*
								* Inverse property: 'containedInPlace'
								*
								* Values expected to be one of these types:
								*
								*      - Place
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'containsPlace',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'containsPlace',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$location_containsPlace = array();

									// Merge in descendant location (LocalBusiness) value

										$location_descendant_location_LocalBusiness = $location_descendant_location_LocalBusiness ?? null;

										if ( $location_descendant_location_LocalBusiness ) {

											$location_containsPlace = uamswp_fad_schema_merge_values(
												$location_containsPlace, // mixed // Required // Initial schema item property value
												$location_descendant_location_LocalBusiness // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related descendant location (LocalBusiness) significantLink value into significantLink

											$location_descendant_location_MedicalWebPage_significantLink = $location_descendant_location_MedicalWebPage_significantLink ?? null;

											if ( $location_descendant_location_MedicalWebPage_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_descendant_location_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related descendant location (LocalBusiness) keywords value into keywords

											$location_descendant_location_LocalBusiness_keywords = $location_descendant_location_LocalBusiness_keywords ?? null;

											if ( $location_descendant_location_LocalBusiness_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_descendant_location_LocalBusiness_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related provider (MedicalBusiness) value

										$location_provider_MedicalBusiness = $location_provider_MedicalBusiness ?? null;

										if ( $location_provider_MedicalBusiness ) {

											$location_containsPlace = uamswp_fad_schema_merge_values(
												$location_containsPlace, // mixed // Required // Initial schema item property value
												$location_provider_MedicalBusiness // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related provider (MedicalBusiness) significantLink value into significantLink

											$location_provider_MedicalBusiness_significantLink = $location_provider_MedicalBusiness_significantLink ?? null;

											if ( $location_provider_MedicalBusiness_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_provider_MedicalBusiness_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related provider (MedicalBusiness) keywords value into keywords

											$location_provider_MedicalBusiness_keywords = $location_provider_MedicalBusiness_keywords ?? null;

											if ( $location_provider_MedicalBusiness_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_provider_MedicalBusiness_keywords // mixed // Required // Incoming schema item property value
												);

											}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'containsPlace', // string // Required // Name of schema property
											$location_containsPlace, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'containsPlace', // string // Required // Name of schema property
											$location_containsPlace, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// currenciesAccepted [WIP]

							/**
								* The currency accepted.
								*
								* Use standard formats:
								*      - ISO 4217 currency format (e.g., "USD")
								*      - Ticker symbol for cryptocurrencies (e.g., "BTC")
								*      - Well-known names for Local Exchange Trading Systems (LETS) and other
								*       currency types (e.g., "Ithaca HOUR")
								*
								* Values expected to be one of these types:
								*
								*      - Text
								*/

						// department

							/**
								* A relationship between an organization and a department of that organization,
								* also described as an organization (allowing different urls, logos, opening
								* hours). For example: a store with a pharmacy, or a bakery with a cafe.
								*
								* Values expected to be one of these types:
								*
								*      - Organization
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'department',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'department',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$location_department = array();

									// Merge in location (LocalBusiness) value

										$location_descendant_location_LocalBusiness = $location_descendant_location_LocalBusiness ?? null;

										if ( $location_descendant_location_LocalBusiness ) {

											$location_department = uamswp_fad_schema_merge_values(
												$location_department, // mixed // Required // Initial schema item property value
												$location_descendant_location_LocalBusiness // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related descendant location (LocalBusiness) significantLink value into significantLink

											$location_descendant_location_MedicalWebPage_significantLink = $location_descendant_location_MedicalWebPage_significantLink ?? null;

											if ( $location_descendant_location_MedicalWebPage_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_descendant_location_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related descendant location (LocalBusiness) keywords value into keywords

											$location_descendant_location_LocalBusiness_keywords = $location_descendant_location_LocalBusiness_keywords ?? null;

											if ( $location_descendant_location_LocalBusiness_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_descendant_location_LocalBusiness_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in provider (MedicalBusiness) value

										$location_provider_MedicalBusiness = $location_provider_MedicalBusiness ?? null;

										if ( $location_provider_MedicalBusiness ) {

											$location_department = uamswp_fad_schema_merge_values(
												$location_department, // mixed // Required // Initial schema item property value
												$location_provider_MedicalBusiness // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related provider (MedicalBusiness) significantLink value into significantLink

											$location_provider_MedicalBusiness_significantLink = $location_provider_MedicalBusiness_significantLink ?? null;

											if ( $location_provider_MedicalBusiness_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_provider_MedicalBusiness_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related provider (MedicalBusiness) keywords value into keywords

											$location_provider_MedicalBusiness_keywords = $location_provider_MedicalBusiness_keywords ?? null;

											if ( $location_provider_MedicalBusiness_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_provider_MedicalBusiness_keywords // mixed // Required // Incoming schema item property value
												);

											}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'department', // string // Required // Name of schema property
											$location_department, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'department', // string // Required // Name of schema property
											$location_department, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// diversityPolicy [WIP]

							/**
								* Statement on diversity policy by an Organization
								* (e.g., a NewsMediaOrganization).
								*
								* For a NewsMediaOrganization, a statement  describing the newsroom’s diversity
								* policy on both staffing and sources, typically providing staffing data.
								*
								* Values expected to be one of these types:
								*
								*      - CreativeWork
								*      - URL
								*
								* As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
								* feedback and adoption from applications and websites can help improve their
								* definitions.
								*/

						// diversityStaffingReport [WIP]

							/**
								* For an Organization (often but not necessarily a NewsMediaOrganization), a
								* report on staffing diversity issues. In a news context this might be for
								* example ASNE or RTDNA (US) reports, or self-reported.
								*
								* Values expected to be one of these types:
								*
								*      - Article
								*      - URL
								*
								* As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
								* feedback and adoption from applications and websites can help improve their
								* definitions.
								*/

						// ethicsPolicy [WIP]

							/**
								* Statement about ethics policy, (e.g., journalistic and publishing practices of
								* a NewsMediaOrganization; food source policies of a Restaurant).
								*
								* In the case of a NewsMediaOrganization, an ethicsPolicy is typically a
								* statement describing the personal, organizational, and corporate standards of
								* behavior expected by the organization.
								*
								* Values expected to be one of these types:
								*
								*      - CreativeWork
								*      - URL
								*
								* As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
								* feedback and adoption from applications and websites can help improve their
								* definitions.
								*/

						// employee

							/**
								* Someone working for this organization.
								*
								* Values expected to be one of these types:
								*
								*      - Person
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'employee',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'employee',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$location_employee = array();

									// Merge in provider Person value

										$location_provider_Person = $location_provider_Person ?? null;

										if ( $location_provider_Person ) {

											$location_employee = uamswp_fad_schema_merge_values(
												$location_employee, // mixed // Required // Initial schema item property value
												$location_provider_Person // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related provider (Person) significantLink value into significantLink

											$location_provider_Person_significantLink = $location_provider_Person_significantLink ?? null;

											if ( $location_provider_Person_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_provider_Person_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related provider (Person) keywords value into keywords

											$location_provider_Person_keywords = $location_provider_Person_keywords ?? null;

											if ( $location_provider_Person_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_provider_Person_keywords // mixed // Required // Incoming schema item property value
												);

											}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'employee', // string // Required // Name of schema property
											$location_employee, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'employee', // string // Required // Name of schema property
											$location_employee, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// event [WIP]

							/**
								* Upcoming or past event associated with this place, organization, or action.
								*
								* Values expected to be one of these types:
								*
								*      - Event
								*/

						// faxNumber

							/**
								* The fax number.
								*
								* Values expected to be one of these types:
								*
								*      - Text
								*/

								if (
								(
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'faxNumber',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'faxNumber',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'faxNumber', // string // Required // Name of schema property
											$location_faxNumber_Text_array, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'faxNumber', // string // Required // Name of schema property
											$location_faxNumber_Text_array, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// foundingDate [WIP]

							/**
								* The date that this organization was founded.
								*
								* Values expected to be one of these types:
								*
								*      - Date
								*/

						// geo (specific property)

							/**
								* The geo coordinates of the place.
								*
								* The precision must be at least 5 decimal places.
								*
								* Values expected to be one of these types:
								*
								*      - GeoCoordinates
								*      - GeoShape
								*/

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'geo',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'geo',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Format values

									if ( $location_geo_value ) {

										$location_geo = uamswp_schema_geo_coordinates(
											$location_geo_value['lat'], // string|int // Required // The latitude of a location. For example 37.42242 (WGS 84). // The precision must be at least 5 decimal places.
											$location_geo_value['lng'] // string|int // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
										);

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'geo', // string // Required // Name of schema property
											$location_geo, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'geo', // string // Required // Name of schema property
											$location_geo, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// hasCredential [WIP]

							/**
								* A credential awarded to the Person or Organization.
								*
								* Values expected to be one of these types:
								*
								*      - EducationalOccupationalCredential
								*
								* As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
								* feedback and adoption from applications and websites can help improve their
								* definitions.
								*/

						// hasDriveThroughService [WIP]

							/**
								* Indicates whether some facility (e.g., FoodEstablishment, CovidTestingFacility)
								* offers a service that can be used by driving through in a car.
								*
								* In the case of CovidTestingFacility such facilities could potentially help with
								* social distancing from other potentially-infected users.
								*
								* Values expected to be one of these types:
								*
								*      - Boolean
								*
								* As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
								* feedback and adoption from applications and websites can help improve their
								* definitions.
								*/

						// hasMap

							/**
								* A URL to a map of the place.
								*
								* Values expected to be one of these types:
								*
								*      - Map
								*      - URL
								*
								* The examples on Schema.org indicate that a URL to the location on Google Maps
								* is acceptable.
								*/

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'hasMap',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'hasMap',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Get values

									if ( $location_google_cid ) {

										$location_hasMap = 'https://www.google.com/maps?cid=' . $location_google_cid;

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'hasMap', // string // Required // Name of schema property
											$location_hasMap, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'hasMap', // string // Required // Name of schema property
											$location_hasMap, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// identifiers (multiple properties) [WIP]

							if ( $nesting_level == 0 ) {

								// 'duns' property [WIP]

									/**
										* The Dun & Bradstreet DUNS number for identifying an organization or business
										* person.
										*
										* Values expected to be one of these types:
										*
										*      - Text
										*/

								// globalLocationNumber [WIP]

									/**
										* The Global Location Number (GLN, sometimes also referred to as International
										* Location Number or ILN) of the respective organization, person, or place. The
										* GLN is a 13-digit number used to identify parties and physical locations.
										*
										* Values expected to be one of these types:
										*
										*      - Text
										*/

								// isicV4 [WIP]

									/**
										* The International Standard of Industrial Classification of All Economic
										* Activities (ISIC), Revision 4 code for a particular organization, business
										* person, or place.
										*
										* Values expected to be one of these types:
										*
										*      - Text
										*/

								// leiCode [WIP]

									/**
										* An organization identifier that uniquely identifies a legal entity as defined
										* in ISO 17442.
										*
										* Values expected to be one of these types:
										*
										*      - Text
										*/

								// naics [WIP]

									/**
										* The North American Industry Classification System (NAICS) code for a particular
										* organization or business person.
										*
										* Values expected to be one of these types:
										*
										*      - Text
										*/

								// taxID [WIP]

									/**
										* The Tax / Fiscal ID of the organization or person (e.g., the TIN in the US;
										* the CIF/NIF in Spain).
										*
										* Values expected to be one of these types:
										*
										*      - Text
										*/

								// vatID [WIP]

									/**
										* The Value-added Tax ID of the organization or person.
										*
										*      - Text
										*/

								// iso6523Code [WIP]

									/**
										* An organization identifier as defined in ISO 6523(-1). Note that many existing
										* organization identifiers such as leiCode, duns and vatID can be expressed as an
										* ISO 6523 identifier by setting the ICD part of the ISO 6523 identifier
										* accordingly.
										*
										* Values expected to be one of these types:
										*
										*      - Text
										*
										* As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
										* feedback and adoption from applications and websites can help improve their
										* definitions.
										*/

								// 'identifier' property [WIP]

									if (
										(
											isset($location_item_MedicalWebPage)
											&&
											in_array(
												'identifier',
												$location_properties_map[$MedicalWebPage_type]['properties']
											)
										)
										||
										(
											isset($location_item_LocalBusiness)
											&&
											in_array(
												'identifier',
												$location_properties_map[$LocalBusiness_type]['properties']
											)
										)
									) {

										/**
											* The identifier property represents any kind of identifier for any kind of
											* Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated
											* properties for representing many of these, either as textual strings or as
											* URL (URI) links.
											*
											* See https://schema.org/docs/datamodel.html#identifierBg for more details.
											*
											* Values expected to be one of these types:
											*
											*      - PropertyValue
											*      - Text
											*      - URL
											*/

										if ( !isset($location_identifier) ) {

											// Base 'identifier' property value array

												$location_identifier = array();

											// Get values [WIP]

												// Dun & Bradstreet DUNS number [WIP]

													$location_duns = $location_duns ?? null;

													if ( $location_duns ) {

														$location_identifier = uamswp_fad_schema_propertyvalue(
															array(
																'Data Universal Numbering System (DUNS) number',
																'DUNS number',
																'D-U-N-S number'
															), // mixed // Optional // alternateName property value
															null, // string // Optional // description property value
															null, // int // Optional // maxValue property value
															null, // mixed // Optional // measurementMethod property value
															null, // mixed // Optional // measurementTechnique property value
															null, // int // Optional // minValue property value
															'Data Universal Numbering System number', // string // Optional // name property value
															'https://www.wikidata.org/wiki/Q246386', // string|array // Optional // propertyID property value
															null, // string // Optional // unitCode property value
															null, // string // Optional // unitText property value
															null, // string // Optional // url property value
															$location_duns, // string|array // Optional // value property value
															null, // mixed // Optional // valueReference property value
															$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

												// Global Location Number [WIP]

													$location_globalLocationNumber = $location_globalLocationNumber ?? null;

													if ( $location_globalLocationNumber ) {

														$location_identifier = uamswp_fad_schema_propertyvalue(
															'GLN', // mixed // Optional // alternateName property value
															null, // string // Optional // description property value
															null, // int // Optional // maxValue property value
															null, // mixed // Optional // measurementMethod property value
															null, // mixed // Optional // measurementTechnique property value
															null, // int // Optional // minValue property value
															'Global Location Number', // string // Optional // name property value
															'https://www.wikidata.org/wiki/Q1258830', // string|array // Optional // propertyID property value
															null, // string // Optional // unitCode property value
															null, // string // Optional // unitText property value
															null, // string // Optional // url property value
															$location_globalLocationNumber, // string|array // Optional // value property value
															null, // mixed // Optional // valueReference property value
															$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

												// International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code [WIP]

													$location_isicV4 = $location_isicV4 ?? null;

													if ( $location_isicV4 ) {

														$location_identifier = uamswp_fad_schema_propertyvalue(
															array(
																'ISIC 2008',
																'ISIC Rev 4'
															), // mixed // Optional // alternateName property value
															null, // string // Optional // description property value
															null, // int // Optional // maxValue property value
															null, // mixed // Optional // measurementMethod property value
															null, // mixed // Optional // measurementTechnique property value
															null, // int // Optional // minValue property value
															'International Standard Industrial Classification Rev. 4', // string // Optional // name property value
															'https://www.wikidata.org/wiki/Q112111674', // string|array // Optional // propertyID property value
															null, // string // Optional // unitCode property value
															null, // string // Optional // unitText property value
															null, // string // Optional // url property value
															$location_isicV4, // string|array // Optional // value property value
															null, // mixed // Optional // valueReference property value
															$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

												// Legal Entity Identifier (LEI) [WIP]

													$location_leiCode = $location_leiCode ?? null;

													if ( $location_leiCode ) {

														$location_identifier = uamswp_fad_schema_propertyvalue(
															array(
																'Global Legal Entity Identifier',
																'LEI',
																'LEI code',
																'LEI number'
															), // mixed // Optional // alternateName property value
															null, // string // Optional // description property value
															null, // int // Optional // maxValue property value
															null, // mixed // Optional // measurementMethod property value
															null, // mixed // Optional // measurementTechnique property value
															null, // int // Optional // minValue property value
															'Legal Entity Identifier', // string // Optional // name property value
															'https://www.wikidata.org/wiki/Q6517388', // string|array // Optional // propertyID property value
															null, // string // Optional // unitCode property value
															null, // string // Optional // unitText property value
															null, // string // Optional // url property value
															$location_leiCode, // string|array // Optional // value property value
															null, // mixed // Optional // valueReference property value
															$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

												// North American Industry Classification System (NAICS) code [WIP]

													$location_naics = $location_naics ?? null;

													if ( $location_naics ) {

														$location_identifier = uamswp_fad_schema_propertyvalue(
															array(
																'North American Industry Classification System',
																'NAICS code',
																'NAICS',
																'NAICS-SCIAN code',
																'NAICS-SCIAN'
															), // mixed // Optional // alternateName property value
															null, // string // Optional // description property value
															null, // int // Optional // maxValue property value
															null, // mixed // Optional // measurementMethod property value
															null, // mixed // Optional // measurementTechnique property value
															null, // int // Optional // minValue property value
															'North American Industry Classification System code', // string // Optional // name property value
															'https://www.wikidata.org/wiki/Q3509282', // string|array // Optional // propertyID property value
															null, // string // Optional // unitCode property value
															null, // string // Optional // unitText property value
															null, // string // Optional // url property value
															$location_naics, // string|array // Optional // value property value
															null, // mixed // Optional // valueReference property value
															$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

												// Tax / Fiscal ID [WIP]

													// Taxpayer Identification Number [WIP]

														$location_taxID_taxpayer = $location_taxID_taxpayer ?? null;

														if ( $location_taxID_taxpayer ) {

															$location_identifier = uamswp_fad_schema_propertyvalue(
																array(
																	'TIN',
																	'IRS TIN',
																	'TIN IRS'
																), // mixed // Optional // alternateName property value
																null, // string // Optional // description property value
																null, // int // Optional // maxValue property value
																null, // mixed // Optional // measurementMethod property value
																null, // mixed // Optional // measurementTechnique property value
																null, // int // Optional // minValue property value
																'Taxpayer Identification Number', // string // Optional // name property value
																'https://www.wikidata.org/wiki/Q1444804', // string|array // Optional // propertyID property value
																null, // string // Optional // unitCode property value
																null, // string // Optional // unitText property value
																null, // string // Optional // url property value
																$location_taxID_taxpayer, // string|array // Optional // value property value
																null, // mixed // Optional // valueReference property value
																$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
															);

														}

													// Employer Identification Number [WIP]

														$location_taxID_employer = $location_taxID_employer ?? null;

														if ( $location_taxID_employer ) {

															$location_identifier = uamswp_fad_schema_propertyvalue(
																array(
																	'Federal Employer Identification Number',
																	'EIN'
																), // mixed // Optional // alternateName property value
																null, // string // Optional // description property value
																null, // int // Optional // maxValue property value
																null, // mixed // Optional // measurementMethod property value
																null, // mixed // Optional // measurementTechnique property value
																null, // int // Optional // minValue property value
																'Employer Identification Number', // string // Optional // name property value
																'https://www.wikidata.org/wiki/Q2397748', // string|array // Optional // propertyID property value
																null, // string // Optional // unitCode property value
																null, // string // Optional // unitText property value
																null, // string // Optional // url property value
																$location_taxID_employer, // string|array // Optional // value property value
																null, // mixed // Optional // valueReference property value
																$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
															);

														}

												// Value-added tax (VAT) identification number [WIP]

													$location_vatID = $location_vatID ?? null;

													if ( $location_vatID ) {

														$location_identifier = uamswp_fad_schema_propertyvalue(
															array(
																'value-added tax identification number',
																'VAT ID',
																'VATIN'
															), // mixed // Optional // alternateName property value
															null, // string // Optional // description property value
															null, // int // Optional // maxValue property value
															null, // mixed // Optional // measurementMethod property value
															null, // mixed // Optional // measurementTechnique property value
															null, // int // Optional // minValue property value
															'VAT identification number', // string // Optional // name property value
															'https://www.wikidata.org/wiki/Q2319042', // string|array // Optional // propertyID property value
															null, // string // Optional // unitCode property value
															null, // string // Optional // unitText property value
															null, // string // Optional // url property value
															$location_vatID, // string|array // Optional // value property value
															null, // mixed // Optional // valueReference property value
															$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

												// Google customer ID (CID)

													if ( $location_google_cid ) {

														$location_identifier = uamswp_fad_schema_propertyvalue_google_cid(
															$location_google_cid, // string|array // Required // Google customer ID
															$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

												// National Provider Identifier (NPI)

													if ( $location_npi ) {

														$location_identifier = uamswp_fad_schema_propertyvalue_npi(
															$location_npi, // string|array // Required // National Provider Identifier
															$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

												// United States Department of Veterans Affairs Station ID

													if ( $location_va_station_id ) {

														$location_identifier = uamswp_fad_schema_propertyvalue(
															array(
																'Department of Veterans Affairs facility #',
																'Department of Veterans Affairs facility ID',
																'Department of Veterans Affairs facilityId',
																'Department of Veterans Affairs institutionCode',
																'Department of Veterans Affairs location ID',
																'Department of Veterans Affairs locationid',
																'Department of Veterans Affairs parent station with suffix',
																'Department of Veterans Affairs STA6A',
																'Department of Veterans Affairs sta6aid code',
																'Department of Veterans Affairs sta6aid',
																'Department of Veterans Affairs station code',
																'Department of Veterans Affairs station ID',
																'Department of Veterans Affairs station number',
																'Department of Veterans Affairs stationID',
																'Department of Veterans Affairs substation identifier',
																'facility #',
																'facility ID',
																'facilityId',
																'institutionCode',
																'location ID',
																'locationid',
																'parent station with suffix',
																'sta6aid code',
																'sta6aid',
																'station code',
																'station ID',
																'station number',
																'stationID',
																'substation identifier',
																'U.S. Department of Veterans Affairs facility #',
																'U.S. Department of Veterans Affairs facility ID',
																'U.S. Department of Veterans Affairs facilityId',
																'U.S. Department of Veterans Affairs institutionCode',
																'U.S. Department of Veterans Affairs location ID',
																'U.S. Department of Veterans Affairs locationid',
																'U.S. Department of Veterans Affairs parent station with suffix',
																'U.S. Department of Veterans Affairs STA6A',
																'U.S. Department of Veterans Affairs sta6aid code',
																'U.S. Department of Veterans Affairs sta6aid',
																'U.S. Department of Veterans Affairs station code',
																'U.S. Department of Veterans Affairs station ID',
																'U.S. Department of Veterans Affairs station number',
																'U.S. Department of Veterans Affairs stationID',
																'U.S. Department of Veterans Affairs substation identifier',
																'United States Department of Veterans Affairs facility #',
																'United States Department of Veterans Affairs facility ID',
																'United States Department of Veterans Affairs facilityId',
																'United States Department of Veterans Affairs institutionCode',
																'United States Department of Veterans Affairs location ID',
																'United States Department of Veterans Affairs locationid',
																'United States Department of Veterans Affairs parent station with suffix',
																'United States Department of Veterans Affairs STA6A',
																'United States Department of Veterans Affairs sta6aid code',
																'United States Department of Veterans Affairs sta6aid',
																'United States Department of Veterans Affairs station code',
																'United States Department of Veterans Affairs station ID',
																'United States Department of Veterans Affairs station number',
																'United States Department of Veterans Affairs stationID',
																'United States Department of Veterans Affairs substation identifier',
																'VA facility #',
																'VA facility ID',
																'VA facilityId',
																'VA institutionCode',
																'VA location ID',
																'VA locationid',
																'VA parent station with suffix',
																'VA STA6A',
																'VA sta6aid code',
																'VA sta6aid',
																'VA station code',
																'VA station ID',
																'VA station number',
																'VA stationID',
																'VA substation identifier',
																'Veterans Affairs station facility #',
																'Veterans Affairs station facility ID',
																'Veterans Affairs station facilityId',
																'Veterans Affairs station institutionCode',
																'Veterans Affairs station location ID',
																'Veterans Affairs station locationid',
																'Veterans Affairs station parent station with suffix',
																'Veterans Affairs station STA6A',
																'Veterans Affairs station sta6aid code',
																'Veterans Affairs station sta6aid',
																'Veterans Affairs station station code',
																'Veterans Affairs station station ID',
																'Veterans Affairs station station number',
																'Veterans Affairs station stationID',
																'Veterans Affairs station substation identifier'
															), // mixed // Optional // alternateName property value
															'STA6A is a six-character length variable that identifies the United States Department of Veterans Affairs substation where the patient\'s health care encounter occurred. The first three characters are digits that typically indicate the parent station (e.g., VA hospital, VA Medical Center [STA3N]). An appended three alphabetic characters may also be present as a suffix to specify a care unit. This variable has 3,503 distinct values as of February 2015, and is frequently updated.', // string // Optional // description property value
															null, // int // Optional // maxValue property value
															null, // mixed // Optional // measurementMethod property value
															null, // mixed // Optional // measurementTechnique property value
															null, // int // Optional // minValue property value
															'STA6A', // string // Optional // name property value
															'https://www.wikidata.org/wiki/Property:P8755', // string|array // Optional // propertyID property value
															null, // string // Optional // unitCode property value
															null, // string // Optional // unitText property value
															null, // string // Optional // url property value
															$location_va_station_id, // string|array // Optional // value property value
															null, // mixed // Optional // valueReference property value
															$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

												// American Hospital Association Hospital Identifier (AHAID)

													if ( $location_ahaid ) {

														$location_identifier = uamswp_fad_schema_propertyvalue(
															array(
																'AHAID',
																'AHA hospital identifier',
																'AHA identification number',
																'AHA identifier'
															), // mixed // Optional // alternateName property value
															null, // string // Optional // description property value
															null, // int // Optional // maxValue property value
															null, // mixed // Optional // measurementMethod property value
															null, // mixed // Optional // measurementTechnique property value
															null, // int // Optional // minValue property value
															'American Hospital Association Hospital Identifier', // string // Optional // name property value
															null, // string|array // Optional // propertyID property value
															null, // string // Optional // unitCode property value
															null, // string // Optional // unitText property value
															null, // string // Optional // url property value
															$location_ahaid, // string|array // Optional // value property value
															null, // mixed // Optional // valueReference property value
															$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

												// Centers for Medicare & Medicaid Services Certification Number (CCN)

													if ( $location_cms_ccn ) {

														$location_identifier = uamswp_fad_schema_propertyvalue(
															array(
																'CCN',
																'Centers for Medicare & Medicaid Services CCN',
																'CMS CCN',
																'CMS Certification Number',
																'CMS ID',
																'HCFA ID',
																'Medicare Identification Number',
																'Medicare Provider ID',
																'Medicare Provider Number',
																'Medicare/Medicaid Provider Number',
																'OSCAR Number',
																'OSCAR Provider Number',
																'Provider Number'
															), // mixed // Optional // alternateName property value
															null, // string // Optional // description property value
															null, // int // Optional // maxValue property value
															null, // mixed // Optional // measurementMethod property value
															null, // mixed // Optional // measurementTechnique property value
															null, // int // Optional // minValue property value
															'Centers for Medicare & Medicaid Services Certification Number', // string // Optional // name property value
															null, // string|array // Optional // propertyID property value
															null, // string // Optional // unitCode property value
															null, // string // Optional // unitText property value
															null, // string // Optional // url property value
															$location_cms_ccn, // string|array // Optional // value property value
															null, // mixed // Optional // valueReference property value
															$location_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

										}

										// Add to item values

											// MedicalWebPage

												uamswp_fad_schema_add_to_item_values(
													$MedicalWebPage_type, // string // Required // The @type value for the schema item
													$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
													'identifier', // string // Required // Name of schema property
													$location_identifier, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$location_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// LocalBusiness

												uamswp_fad_schema_add_to_item_values(
													$LocalBusiness_type, // string // Required // The @type value for the schema item
													$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'identifier', // string // Required // Name of schema property
													$location_identifier, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$location_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

							}

						// image (specific property)

							/**
								* An image of the item. This can be a URL or a fully described ImageObject.
								*
								* Values expected to be one of these types:
								*
								*      - ImageObject
								*      - URL
								*/

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'image',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'image',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'image', // string // Required // Name of schema property
											$location_image_general, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'image', // string // Required // Name of schema property
											$location_image_general, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// isAcceptingNewPatients [WIP]

							/**
								* Whether the provider is accepting new patients.
								*
								* Values expected to be one of these types:
								*
								*      - Boolean
								*
								* As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
								* feedback and adoption from applications and websites can help improve their
								* definitions.
								*/

						// isAccessibleForFree (LocalBusiness) [WIP]

							/**
								* A flag to signal that the item, event, or place is accessible for free.
								*
								* Values expected to be one of these types:
								*
								*      - Boolean
								*/

							/*

								The 'isAccessibleForFree' property for MedicalWebPage has been addressed by the
								common properties.

								Regarding the LocalBusiness item, more information is needed on whether or not
								any clinical location is ever accessible for free.

							*/

						// knowsLanguage [WIP]

							/**
								* Of a Person, and less typically of an Organization, to indicate a known
								* language. We do not distinguish skill levels or reading / writing / speaking /
								* signing here. Use language codes from the IETF BCP 47 standard.
								*
								* Values expected to be one of these types:
								*
								*      - Language
								*      - Text
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'knowsLanguage',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'knowsLanguage',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									$location_knowsLanguage = array();

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'knowsLanguage', // string // Required // Name of schema property
											$location_knowsLanguage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'knowsLanguage', // string // Required // Name of schema property
											$location_knowsLanguage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// legalName [WIP]

							/**
								* The official name of the organization (e.g., the registered company name).
								*
								*      - Text
								*/

						// logo [WIP]

							/**
								* An associated logo.
								*
								* Values expected to be one of these types:
								*
								*      - ImageObject
								*      - URL
								*/

						// mainContentOfPage

							/**
							* Indicates if this web page element is the main subject of the page.
							*
							* Values expected to be one of these types:
							*
							*     - WebPageElement
							*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'mainContentOfPage',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'mainContentOfPage',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Get values

									$location_mainContentOfPage = array(
										'@type' => 'WebPageElement',
										'cssSelector' => '.location-item'
									);

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'mainContentOfPage', // string // Required // Name of schema property
											$location_mainContentOfPage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'mainContentOfPage', // string // Required // Name of schema property
											$location_mainContentOfPage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// makesOffer [WIP]

							/**
								* A pointer to products or services offered by the organization or person.
								*
								* Inverse-property: offeredBy
								*
								* Values expected to be one of these types:
								*
								*      - Offer
								*/

						// maximumAttendeeCapacity [WIP]

							/**
								* The total number of individuals that may attend an event or venue.
								*
								* Values expected to be one of these types:
								*
								*      - Integer
								*/

						// medicalSpecialty (specific property)

							/**
								* A medical specialty of the provider.
								*
								* Values expected to be one of these types:
								*
								*      - MedicalSpecialty
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'medicalSpecialty',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'medicalSpecialty',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'medicalSpecialty', // string // Required // Name of schema property
											$location_medicalSpecialty, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'medicalSpecialty', // string // Required // Name of schema property
											$location_medicalSpecialty, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// memberOf [WIP]

							/**
								* An Organization (or ProgramMembership) to which this Person or Organization
								* belongs.
								*
								* Inverse-property: member
								*
								* Subproperty of:
								*
								*      - foo
								*
								* Values expected to be one of these types:
								*
								*      - Organization
								*      - ProgramMembership
								*/

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'memberOf',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'memberOf',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Get values [WIP]

									// Base array

										$location_memberOf = array();

									// Get health care professional associations [WIP]

										// Get health care professional associations input value [WIP]

											/*

												More information is needed on whether or not clinical locations are ever
												members of health care professional associations.

											*/

											if ( !isset($location_associations) ) {

												$location_associations = array();

											}

										// Format values

											if ( $location_associations ) {

												$location_association_names = array();
												$location_memberOf = uamswp_fad_schema_associations(
													$location_associations, // mixed // Required // Health care professional association ID values
													$location_association_names, // array // Optional // Pre-existing array variable to populate with a list of association names
													$location_memberOf // array // Optional // Pre-existing schema array for Language to which to add association items
												);

											}

									// Merge in common 'memberOf' value

										if (
											isset($schema_common_memberOf)
											&&
											!empty($schema_common_memberOf)
										) {

											$location_memberOf = uamswp_fad_schema_merge_values(
												$location_memberOf, // mixed // Required // Initial schema item property value
												$schema_common_memberOf // mixed // Required // Incoming schema item property value
											);

										}

									// Merge in specific clinical 'Organization' value

										$location_specific_clinical_organization = $location_specific_clinical_organization ?? null;

										if ( $location_specific_clinical_organization ) {

											$location_memberOf = uamswp_fad_schema_merge_values(
												$location_memberOf, // mixed // Required // Initial schema item property value
												$location_specific_clinical_organization // mixed // Required // Incoming schema item property value
											);

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'memberOf', // string // Required // Name of schema property
											$location_memberOf, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'memberOf', // string // Required // Name of schema property
											$location_memberOf, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// nonprofitStatus [WIP]

							/**
								* nonprofitStatus indicates the legal status of a non-profit organization in its
								* primary place of business.
								*
								* Values expected to be one of these types:
								*
								*      - NonprofitType
								*
								* As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
								* feedback and adoption from applications and websites can help improve their
								* definitions.
								*/

						// numberOfEmployees [WIP]

							/**
								* The number of employees in an organization (e.g., business).
								*
								* Values expected to be one of these types:
								*
								*      - QuantitativeValue
								*/

						// offers [WIP]

							/**
								* An offer to provide this item—for example, an offer to sell a product, rent the
								* DVD of a movie, perform a service, or give away tickets to an event.
								*
								* Use businessFunction to indicate the kind of transaction offered
								* (i.e., sell, lease).
								*
								* This property can also be used to describe a Demand.
								*
								* While this property is listed as expected on a number of common types, it can
								* be used in others. In that case, using a second type, such as Product or a
								* subtype of Product, can clarify the nature of the offer.
								*
								* Inverse-property: itemOffered
								*
								* Values expected to be one of these types:
								*
								*      - Demand
								*      - Offer
								*/

						// openingHours

							/**
								* The general opening hours for a business. Opening hours can be specified as a
								* weekly time range, starting with days, then times per day. Multiple days can be
								* listed with commas ',' separating each day. Day or time ranges are specified
								* using a hyphen '-'.
								*
								* Days are specified using the following two-letter combinations:
								* Mo, Tu, We, Th, Fr, Sa, Su.
								*
								* Times are specified using 24:00 format. For example, 3 p.m. is specified as
								* 15:00, 10 a.m. as 10:00.
								*
								* Here is an example:
								* <time itemprop="openingHours" datetime="Tu,Th 16:00-20:00">Tuesdays and Thursdays 4-8pm</time>.
								*
								* If a business is open 7 days a week, then it can be specified as
								* <time itemprop="openingHours" datetime="Mo-Su">Monday through Sunday, all day</time>.
								*
								* Values expected to be one of these types:
								*
								*      - Text
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'openingHours',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'openingHours',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'openingHours', // string // Required // Name of schema property
											$location_hours_openingHours, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'openingHours', // string // Required // Name of schema property
											$location_hours_openingHours, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// openingHoursSpecification

							/**
								* The opening hours of a certain place.
								*
								* Values expected to be one of these types:
								*
								*      - OpeningHoursSpecification
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'openingHoursSpecification',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'openingHoursSpecification',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'openingHoursSpecification', // string // Required // Name of schema property
											$location_hours_openingHoursSpecification, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'openingHoursSpecification', // string // Required // Name of schema property
											$location_hours_openingHoursSpecification, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// parentOrganization

							/**
								* The larger organization that this organization is a subOrganization of, if any.
								*
								* Values expected to be one of these types:
								*
								*      - Organization
								*/

							// WebSite and MedicalWebPage only

								if (
									$schema_common_parentOrganization_MedicalWebPage
									&&
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'parentOrganization',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
								) {

									// Get names for keywords property

										// Base array

											$location_parentOrganization_keywords = $location_parentOrganization_keywords ?? array();

										$location_parentOrganization_keywords = uamswp_fad_schema_property_values(
											$schema_common_parentOrganization_MedicalWebPage, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ), // mixed // Required // List of properties from which to collect values
											$location_parentOrganization_keywords // mixed // Optional // Pre-existing list to which to add additional items
										);

										// Merge brand keywords value into keywords

											$location_parentOrganization_keywords = $location_parentOrganization_keywords ?? array();

											if ( $location_parentOrganization_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_parentOrganization_keywords // mixed // Required // Incoming schema item property value
												);

											}

								}

							// Excluding WebSite and MedicalWebPage

								if (
									(
										$schema_common_parentOrganization_exclude_MedicalWebPage
										||
										$location_parent_LocalBusiness
									)
									&&
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'parentOrganization',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								) {

									// Get parent location value

										$location_parentOrganization_exclude_MedicalWebPage = $location_parent_LocalBusiness ?? array();

									// Add to item values

										// LocalBusiness

											uamswp_fad_schema_add_to_item_values(
												$LocalBusiness_type, // string // Required // The @type value for the schema item
												$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'parentOrganization', // string // Required // Name of schema property
												$location_parentOrganization_exclude_MedicalWebPage, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$location_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1), // int // Required // Current nesting level value
												-1, // int // Optional // Max nesting level at which to add the property value // Default: -1 (no limit)
												'==', // string // Optional // Operator used to compare nesting level with max nesting level. The possible operators are: <, lt, <=, le, >, gt, >=, ge, ==, =, eq, !=, <>, ne respectively. // Default: ==
												'properties', // string // Optional // Key in the property map containing the list of allowed properties as its value // Default: 'properties'
												false // bool // Optional // Query for whether to overwite any existing property value of the list array with the incoming property value // Default: true
											);

									// Get names for keywords property

										// Base array

											$location_parentOrganization_keywords = $location_parentOrganization_keywords ?? array();

										// Common parentOrganization keywords

											$location_parentOrganization_keywords = uamswp_fad_schema_property_values(
												$schema_common_parentOrganization_exclude_MedicalWebPage, // array // Required // Property values from which to extract specific values
												array( 'name', 'alternateName' ), // mixed // Required // List of properties from which to collect values
												$location_parentOrganization_keywords // mixed // Optional // Pre-existing list to which to add additional items
											);

										// Parent location keywords

											$location_parentOrganization_keywords = uamswp_fad_schema_property_values(
												$location_parentOrganization_exclude_MedicalWebPage, // array // Required // Property values from which to extract specific values
												array( 'name', 'alternateName' ), // mixed // Required // List of properties from which to collect values
												$location_parentOrganization_keywords // mixed // Optional // Pre-existing list to which to add additional items
											);

										// Merge brand keywords value into keywords

											$location_parentOrganization_keywords = $location_parentOrganization_keywords ?? array();

											if ( $location_parentOrganization_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_parentOrganization_keywords // mixed // Required // Incoming schema item property value
												);

											}

								}

						// payment (common) [WIP]

							// acceptedPaymentMethod [WIP]

								/**
								 * The payment method(s) that are accepted in general by an organization, or for
								 * some specific demand or offer.
								 *
								 * Values expected to be one of these types:
								 *
								 *      - LoanOrCredit
								 *      - PaymentMethod
								 *
								 * Used on these types:
								 *
								 *      - Demand
								 *      - Offer
								 *      - Organization
								 */

							// paymentAccepted [WIP]

								/**
								 * Cash, Credit Card, Cryptocurrency, Local Exchange Tradings System, etc.
								 *
								 * Values expected to be one of these types:
								 *
								 *      - Text
								 */

						// photo

							/**
								* A photograph of this place.
								*
								* Values expected to be one of these types:
								*
								*      - ImageObject
								*      - Photograph
								*/

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'photo',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'photo',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'photo', // string // Required // Name of schema property
											$location_image_general, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'photo', // string // Required // Name of schema property
											$location_image_general, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// potentialAction [WIP]

							/**
								* Indicates a potential Action, which describes an idealized action in which this
								* thing would play an 'object' role.
								*
								* Values expected to be one of these types:
								*
								*      - Action
								*/

							/*

								Create one or more Action arrays, likely 'CreateAction' type

										* Make an appointment, new or existing patient, by phone
										* Make an appointment, new patient, by phone
										* Make an appointment, existing patient, by phone
										* Make an appointment, new or existing patient, online
										* Make an appointment, new patient, online
										* Make an appointment, existing patient, online
										* Refer a patient, by phone
										* Refer a patient, by fax
										* Refer a patient, through Epic thing

								Property descriptions:

										* 'actionStatus'
											* Indicates the current disposition of the Action
										* 'agent'
											* The direct performer or driver of the action — animate or inanimate (e.g., John
											wrote a book)
										* 'endTime'
											* The endTime of something. For a reserved event or service
											(e.g., FoodEstablishmentReservation), the time that it is expected to end. For
											actions that span a period of time, when the action was performed (e.g., John
											wrote a book from January to December). For media, including audio and video,
											it's the time offset of the end of a clip within a larger file. Note that Event
											uses startDate/endDate instead of startTime/endTime, even when describing dates
											with times. This situation may be clarified in future revisions.
										* 'error'
											* For failed actions, more information on the cause of the failure.
										* 'instrument'
											* The object that helped the agent perform the action (e.g., John wrote a book
										with a pen).
										* 'location'
											* The location of, for example, where an event is happening, where an
											organization is located, or where an action takes place.
										* 'object'
											* The object upon which the action is carried out, whose state is kept intact or
											changed. Also known as the semantic roles patient, affected or undergoer —
											which change their state — or theme — which doesn't (e.g., John read a book).
										* 'participant'
											* Other co-agents that participated in the action indirectly (e.g., John wrote a
										book with Steve).
										* 'provider'
											* The service provider, service operator, or service performer; the goods
											producer. Another party (a seller) may offer those services or goods on behalf
											of the provider. A provider may also serve as the seller. Supersedes carrier.
										* 'result'
											* The result produced in the action (e.g., John wrote a book).
										* 'startTime'
											* The startTime of something. For a reserved event or service
											(e.g., FoodEstablishmentReservation), the time that it is expected to start.
											For actions that span a period of time, when the action was performed
											(e.g., John wrote a book from January to December). For media, including audio
											and video, it's the time offset of the start of a clip within a larger file.
											Note that Event uses startDate/endDate instead of startTime/endTime, even when
											describing dates with times. This situation may be clarified in future
											revisions.
										* 'target'
											* Indicates a target EntryPoint, or url, for an Action.

							*/

						// primaryImageOfPage [WIP]

							/**
								* Indicates the main image on the page.
								*
								* Values expected to be one of these types:
								*
								*      - ImageObject
								*/

						// publicAccess [WIP]

							/**
								* A flag to signal that the Place is open to public visitors. If this property is
								* omitted there is no assumed default boolean value.
								*
								* Values expected to be one of these types:
								*
								*      - Boolean
								*/

						// review [WIP]

							/**
								* A review of the item.
								*
								* Values expected to be one of these types:
								*
								*      - Review
								*/

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
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'sameAs',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'sameAs',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Get sameAs values

									if ( !isset($location_sameAs) ) {

										// Get sameAs repeater field value

											if ( !isset($location_sameAs_repeater) ) {

												$location_sameAs_repeater = get_field( 'schema_sameas', $entity ) ?? array();

											}

											// Add each item to sameAs property values array

												if ( $location_sameAs_repeater ) {

													$location_sameAs = uamswp_fad_schema_sameas(
														$location_sameAs_repeater, // sameAs repeater field
														'schema_sameas_url' // sameAs item field name
													);

												}

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'sameAs', // string // Required // Name of schema property
											$location_sameAs, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'sameAs', // string // Required // Name of schema property
											$location_sameAs, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// speakable [WIP]

							/**
								* Indicates sections of a Web page that are particularly 'speakable' in the sense
								* of being highlighted as being especially appropriate for text-to-speech
								* conversion. Other sections of a page may also be usefully spoken in particular
								* circumstances; the 'speakable' property serves to indicate the parts most
								* likely to be generally useful for speech.
								*
								* The speakable property can be repeated an arbitrary number of times, with three
								* kinds of possible 'content-locator' values:
								*
								*     1.) id-value URL references - uses id-value of an element in the page being
								* annotated. The simplest use of speakable has (potentially relative) URL values,
								* referencing identified sections of the document concerned.
								*
								*     2.) CSS Selectors - addresses content in the annotated page (e.g., via
								* class attribute). Use the cssSelector property.
								*
								*     3.) XPaths - addresses content via XPaths (assuming an XML view of the
								* content). Use the xpath property.
								*
								* For more sophisticated markup of speakable sections beyond simple ID
								* references, either CSS selectors or XPath expressions to pick out document
								* section(s) as speakable. For this we define a supporting type,
								* SpeakableSpecification which is defined to be a possible value of the speakable
								* property.
								*
								* Values expected to be one of these types:
								*
								*      - SpeakableSpecification
								*      - URL
								*/

						// specialOpeningHoursSpecification

							/**
								* The special opening hours of a certain place.
								*
								* Use this to explicitly override general opening hours brought in scope by
								* openingHoursSpecification or openingHours.
								*
								* Values expected to be one of these types:
								*
								*      - OpeningHoursSpecification
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'specialOpeningHoursSpecification',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'specialOpeningHoursSpecification',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'specialOpeningHoursSpecification', // string // Required // Name of schema property
											$location_hours_specialOpeningHoursSpecification, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'specialOpeningHoursSpecification', // string // Required // Name of schema property
											$location_hours_specialOpeningHoursSpecification, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// specialty

							/**
								* One of the domain specialties to which this web page's content applies.
								*
								* Values expected to be one of these types:
								*
								*      - Specialty
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'specialty',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'specialty',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'specialty', // string // Required // Name of schema property
											$location_medicalSpecialty, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'specialty', // string // Required // Name of schema property
											$location_medicalSpecialty, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// subOrganization

							/**
								* A relationship between two organizations where the first includes the second
								* (e.g., as a subsidiary).
								*
								* See also: the more specific 'department' property.
								*
								* Inverse-property: parentOrganization
								*
								* Values expected to be one of these types:
								*
								*      - Organization
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'subOrganization',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'subOrganization',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$location_subOrganization = array();

									// Merge in location (LocalBusiness) value

										$location_descendant_location_LocalBusiness = $location_descendant_location_LocalBusiness ?? null;

										if ( $location_descendant_location_LocalBusiness ) {

											$location_subOrganization = uamswp_fad_schema_merge_values(
												$location_subOrganization, // mixed // Required // Initial schema item property value
												$location_descendant_location_LocalBusiness // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related descendant location (LocalBusiness) significantLink value into significantLink

											$location_descendant_location_MedicalWebPage_significantLink = $location_descendant_location_MedicalWebPage_significantLink ?? null;

											if ( $location_descendant_location_MedicalWebPage_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_descendant_location_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related descendant location (LocalBusiness) keywords value into keywords

											$location_descendant_location_LocalBusiness_keywords = $location_descendant_location_LocalBusiness_keywords ?? null;

											if ( $location_descendant_location_LocalBusiness_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_descendant_location_LocalBusiness_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in provider (MedicalBusiness) value

										$location_provider_MedicalBusiness = $location_provider_MedicalBusiness ?? null;

										if ( $location_provider_MedicalBusiness ) {

											$location_subOrganization = uamswp_fad_schema_merge_values(
												$location_subOrganization, // mixed // Required // Initial schema item property value
												$location_provider_MedicalBusiness // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related provider (MedicalBusiness) significantLink value into significantLink

											$location_provider_MedicalBusiness_significantLink = $location_provider_MedicalBusiness_significantLink ?? null;

											if ( $location_provider_MedicalBusiness_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_provider_MedicalBusiness_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related provider (MedicalBusiness) keywords value into keywords

											$location_provider_MedicalBusiness_keywords = $location_provider_MedicalBusiness_keywords ?? null;

											if ( $location_provider_MedicalBusiness_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_provider_MedicalBusiness_keywords // mixed // Required // Incoming schema item property value
												);

											}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'subOrganization', // string // Required // Name of schema property
											$location_subOrganization, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'subOrganization', // string // Required // Name of schema property
											$location_subOrganization, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// telephone

							/**
								* The telephone number.
								*
								* Values expected to be one of these types:
								*
								*      - Text
								*/

							if (
								(
									isset($location_item_MedicalWebPage)
									&&
									in_array(
										'telephone',
										$location_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($location_item_LocalBusiness)
									&&
									in_array(
										'telephone',
										$location_properties_map[$LocalBusiness_type]['properties']
									)
								)
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'telephone', // string // Required // Name of schema property
											$location_telephone_Text_array, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'telephone', // string // Required // Name of schema property
											$location_telephone_Text_array, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// timeRequired [WIP]

							/**
								* Approximate or typical time it usually takes to work with or through the
								* content of this work for the typical or target audience.
								*
								* Values expected to be one of these types:
								*
								*      - Duration (use ISO 8601 duration format).
								*/

						// knowsAbout

							/**
								* Of a Person, and less typically of an Organization, to indicate a topic that is
								* known about — suggesting possible expertise but not implying it. We do not
								* distinguish skill levels here, or relate this to educational content, events,
								* objectives or JobPosting descriptions.
								*
								* Values expected to be one of these types:
								*
								*      - Text
								*      - Thing
								*      - URL
								*
								* As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
								* feedback and adoption from applications and websites can help improve their
								* definitions.
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'knowsAbout',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'knowsAbout',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$location_knowsAbout = array();

									// Merge in related area of expertise (MedicalEntity) value

										$location_expertise_MedicalEntity = $location_expertise_MedicalEntity ?? null;

										if ( $location_expertise_MedicalEntity ) {

											$location_knowsAbout = uamswp_fad_schema_merge_values(
												$location_knowsAbout, // mixed // Required // Initial schema item property value
												$location_expertise_MedicalEntity // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related area of expertise (MedicalEntity) significantLink value into significantLink

											$location_expertise_MedicalWebPage_significantLink = $location_expertise_MedicalWebPage_significantLink ?? null;

											if ( $location_expertise_MedicalWebPage_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_expertise_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related area of expertise (MedicalEntity) keywords value into keywords

											$location_expertise_MedicalEntity_keywords = $location_expertise_MedicalEntity_keywords ?? null;

											if ( $location_expertise_MedicalEntity_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_expertise_MedicalEntity_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related condition (MedicalCondition) value

										$location_condition = $location_condition ?? null;

										if ( $location_condition ) {

											$location_knowsAbout = uamswp_fad_schema_merge_values(
												$location_knowsAbout, // mixed // Required // Initial schema item property value
												$location_condition // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related condition (MedicalCondition) significantLink value into significantLink

											$location_condition_significantLink = $location_condition_significantLink ?? null;

											if ( $location_condition_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_condition_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related condition (MedicalCondition) keywords value into keywords

											$location_condition_keywords = $location_condition_keywords ?? null;

											if ( $location_condition_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_condition_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related treatment (availableService) value

										$location_availableService = $location_availableService ?? null;

										if ( $location_availableService ) {

											$location_knowsAbout = uamswp_fad_schema_merge_values(
												$location_knowsAbout, // mixed // Required // Initial schema item property value
												$location_availableService // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related treatment (availableService) significantLink value into significantLink

											$location_availableService_significantLink = $location_availableService_significantLink ?? null;

											if ( $location_availableService_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_availableService_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related treatment (availableService) keywords value into keywords

											$location_availableService_keywords = $location_availableService_keywords ?? null;

											if ( $location_availableService_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_availableService_keywords // mixed // Required // Incoming schema item property value
												);

											}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'knowsAbout', // string // Required // Name of schema property
											$location_knowsAbout, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'knowsAbout', // string // Required // Name of schema property
											$location_knowsAbout, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// mentions

							/**
								* Indicates that the CreativeWork contains a reference to, but is not necessarily
								* about a concept.
								*
								* Values expected to be one of these types:
								*
								*      - Thing
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'mentions',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'mentions',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$location_mentions = array();

									// Merge in related providers (MedicalBusiness) value

										$location_provider_MedicalBusiness = $location_provider_MedicalBusiness ?? null;

										if ( $location_provider_MedicalBusiness ) {

											$location_mentions = uamswp_fad_schema_merge_values(
												$location_mentions, // mixed // Required // Initial schema item property value
												$location_provider_MedicalBusiness // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related providers (MedicalBusiness) significantLink value into significantLink

											$location_provider_MedicalBusiness_significantLink = $location_provider_MedicalBusiness_significantLink ?? null;

											if ( $location_provider_MedicalBusiness_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_provider_MedicalBusiness_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related providers (MedicalBusiness) keywords value into keywords

											$location_provider_MedicalBusiness_keywords = $location_provider_MedicalBusiness_keywords ?? null;

											if ( $location_provider_MedicalBusiness_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_provider_MedicalBusiness_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related providers (Person) value

										$location_provider_Person = $location_provider_Person ?? null;

										if ( $location_provider_Person ) {

											$location_mentions = uamswp_fad_schema_merge_values(
												$location_mentions, // mixed // Required // Initial schema item property value
												$location_provider_Person // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related providers (Person) significantLink value into significantLink

											$location_provider_Person_significantLink = $location_provider_Person_significantLink ?? null;

											if ( $location_provider_Person_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_provider_Person_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related providers (Person) keywords value into keywords

											$location_provider_Person_keywords = $location_provider_Person_keywords ?? null;

											if ( $location_provider_Person_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_provider_Person_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in descendant locations value

										$location_descendant_location_LocalBusiness = $location_descendant_location_LocalBusiness ?? null;

										if ( $location_descendant_location_LocalBusiness ) {

											$location_mentions = uamswp_fad_schema_merge_values(
												$location_mentions, // mixed // Required // Initial schema item property value
												$location_descendant_location_LocalBusiness // mixed // Required // Incoming schema item property value
											);

										}

										// Merge descendant locations significantLink value into significantLink

											$location_descendant_location_MedicalWebPage_significantLink = $location_descendant_location_MedicalWebPage_significantLink ?? null;

											if ( $location_descendant_location_MedicalWebPage_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_descendant_location_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge descendant locations keywords value into keywords

											$location_descendant_location_LocalBusiness_keywords = $location_descendant_location_LocalBusiness_keywords ?? null;

											if ( $location_descendant_location_LocalBusiness_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_descendant_location_LocalBusiness_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related areas of expertise (MedicalEntity) value

										$location_expertise_MedicalEntity = $location_expertise_MedicalEntity ?? null;

										if ( $location_expertise_MedicalEntity ) {

											$location_mentions = uamswp_fad_schema_merge_values(
												$location_mentions, // mixed // Required // Initial schema item property value
												$location_expertise_MedicalEntity // mixed // Required // Incoming schema item property value
											);

										}

										// Merge areas of expertise (MedicalEntity) significantLink value into significantLink

											$location_expertise_MedicalWebPage_significantLink = $location_expertise_MedicalWebPage_significantLink ?? null;

											if ( $location_expertise_MedicalWebPage_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_expertise_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge areas of expertise (MedicalEntity) keywords value into keywords

											$location_expertise_MedicalEntity_keywords = $location_expertise_MedicalEntity_keywords ?? null;

											if ( $location_expertise_MedicalEntity_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_expertise_MedicalEntity_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related clinical resources value

										$location_clinical_resource_CreativeWork = $location_clinical_resource_CreativeWork ?? null;

										if ( $location_clinical_resource_CreativeWork ) {

											$location_mentions = uamswp_fad_schema_merge_values(
												$location_mentions, // mixed // Required // Initial schema item property value
												$location_clinical_resource_CreativeWork // mixed // Required // Incoming schema item property value
											);

										}

										// Merge clinical resources significantLink value into significantLink

											$location_clinical_resource_MedicalWebPage_significantLink = $location_clinical_resource_MedicalWebPage_significantLink ?? null;

											if ( $location_clinical_resource_MedicalWebPage_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_clinical_resource_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related conditions value

										$location_condition = $location_condition ?? null;

										if ( $location_condition ) {

											$location_mentions = uamswp_fad_schema_merge_values(
												$location_mentions, // mixed // Required // Initial schema item property value
												$location_condition // mixed // Required // Incoming schema item property value
											);

										}

										// Merge conditions significantLink value into significantLink

											$location_condition_significantLink = $location_condition_significantLink ?? null;

											if ( $location_condition_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_condition_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge conditions keywords value into keywords

											$location_condition_keywords = $location_condition_keywords ?? null;

											if ( $location_condition_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_condition_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related treatments value

										$location_availableService = $location_availableService ?? null;

										if ( $location_availableService ) {

											$location_mentions = uamswp_fad_schema_merge_values(
												$location_mentions, // mixed // Required // Initial schema item property value
												$location_availableService // mixed // Required // Incoming schema item property value
											);

										}

										// Merge availableService significantLink value into significantLink

											$location_availableService_significantLink = $location_availableService_significantLink ?? null;

											if ( $location_availableService_significantLink ) {

												$location_significantLink = uamswp_fad_schema_merge_values(
													$location_significantLink, // mixed // Required // Initial schema item property value
													$location_availableService_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge availableService keywords value into keywords

											$location_availableService_keywords = $location_availableService_keywords ?? null;

											if ( $location_availableService_keywords ) {

												$location_keywords = uamswp_fad_schema_merge_values(
													$location_keywords, // mixed // Required // Initial schema item property value
													$location_availableService_keywords // mixed // Required // Incoming schema item property value
												);

											}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'mentions', // string // Required // Name of schema property
											$location_mentions, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'mentions', // string // Required // Name of schema property
											$location_mentions, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// relatedLink

							/**
								* A link related to this web page, for example to other related web pages.
								*
								* Values expected to be one of these types:
								*
								*      - URL
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'relatedLink',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'relatedLink',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'significantLink', // string // Required // Name of schema property
											$location_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'significantLink', // string // Required // Name of schema property
											$location_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// significantLink

							/**
								* One of the more significant URLs on the page. Typically, these are the
								* non-navigation links that are clicked on the most.
								*
								* Values expected to be one of these types:
								*
								*      - URL
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'significantLink',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'significantLink',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'significantLink', // string // Required // Name of schema property
											$location_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'significantLink', // string // Required // Name of schema property
											$location_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// keywords

							/**
								* Keywords or tags used to describe some item. Multiple textual entries in a
								* keywords list are typically delimited by commas, or by repeating the property.
								*
								* Values expected to be one of these types:
								*
								*      - DefinedTerm
								*      - Text
								*      - URL
								*/

							if (
								(
									(
										isset($location_item_MedicalWebPage)
										&&
										in_array(
											'keywords',
											$location_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($location_item_LocalBusiness)
										&&
										in_array(
											'keywords',
											$location_properties_map[$LocalBusiness_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$location_keywords = $location_keywords ?? array();

									// Common values

										$location_keywords[] = 'location';

									// Address values

										if ( $location_address ) {

											if (
												isset($location_address['addressLocality'])
												&&
												!empty($location_address['addressLocality'])
											) {

												$location_keywords[] = $location_address['addressLocality'];

											}

											if (
												isset($location_address['addressRegion'])
												&&
												!empty($location_address['addressRegion'])
											) {

												$location_keywords[] = $location_address['addressRegion'];

											}

										}

									// Clean up list array

										if ( $location_keywords ) {

											$location_keywords = array_filter($location_keywords);
											$location_keywords = array_unique( $location_keywords, SORT_REGULAR );
											$location_keywords = array_values($location_keywords);
											uamswp_fad_flatten_multidimensional_array($location_keywords);

											if ( is_array($location_keywords) ) {

												sort( $location_keywords, SORT_NATURAL | SORT_FLAG_CASE );

											}

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$location_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'keywords', // string // Required // Name of schema property
											$location_keywords, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// LocalBusiness

										uamswp_fad_schema_add_to_item_values(
											$LocalBusiness_type, // string // Required // The @type value for the schema item
											$location_item_LocalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'keywords', // string // Required // Name of schema property
											$location_keywords, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$location_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

					// Sort and combine the arrays

						if ( isset($location_item_MedicalWebPage) ) {

							ksort( $location_item_MedicalWebPage, SORT_NATURAL | SORT_FLAG_CASE );
							$location_item['MedicalWebPage'] = $location_item_MedicalWebPage;

						}

						if ( isset($location_item_LocalBusiness) ) {

							ksort( $location_item_LocalBusiness, SORT_NATURAL | SORT_FLAG_CASE );
							$location_item['LocalBusiness'] = $location_item_LocalBusiness;

						}

					// Set/update the value of the item transient

						uamswp_fad_set_transient(
							'item_' . $entity . ( $nesting_level ? '_nested-level-' . $nesting_level : '_root' ), // Required // String added to transient name for disambiguation.
							$location_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
							__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
						);

					// Add to lists of providers

						// Add to list of MedicalWebPage items

							if (
								isset($location_item['MedicalWebPage'])
								&&
								!empty($location_item['MedicalWebPage'])
							) {

								$MedicalWebPage_list[] = $location_item['MedicalWebPage'];

							}

						// Add to list of LocalBusiness items

							if (
								isset($location_item['LocalBusiness'])
								&&
								!empty($location_item['LocalBusiness'])
							) {

								$LocalBusiness_list[] = $location_item['LocalBusiness'];

							}

				}

			} // endforeach ( $repeater as $entity )

		// Clean up list arrays

			// MedicalWebPage

				$MedicalWebPage_list = array_filter($MedicalWebPage_list);
				$MedicalWebPage_list = array_values($MedicalWebPage_list);

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($MedicalWebPage_list);

			// LocalBusiness

				$LocalBusiness_list = array_filter($LocalBusiness_list);
				$LocalBusiness_list = array_values($LocalBusiness_list);

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($LocalBusiness_list);

		// Combine lists for return

			// MedicalWebPage

				if ( $MedicalWebPage_list ) {

					// Check if pre-existing list is an indexed array

						if (
							isset($location_list['MedicalWebPage'])
							&&
							!empty($location_list['MedicalWebPage'])
						) {

							$location_list['MedicalWebPage'] = array_is_list($location_list['MedicalWebPage']) ? $location_list['MedicalWebPage'] : array($location_list['MedicalWebPage']);

						}

					$location_list['MedicalWebPage'] = $MedicalWebPage_list;

				}

			// LocalBusiness

				if ( $LocalBusiness_list ) {

					// Check if pre-existing list is an indexed array

						if (
							isset($location_list['LocalBusiness'])
							&&
							!empty($location_list['LocalBusiness'])
						) {

							$location_list['LocalBusiness'] = array_is_list($location_list['LocalBusiness']) ? $location_list['LocalBusiness'] : array($location_list['LocalBusiness']);

						}

					$location_list['LocalBusiness'] = $LocalBusiness_list;

				}

	} // endif ( !empty($repeater) )

	return $location_list;

}