<?php

/*
 * 
 * Required vars:
 * $entity ($id of current post)
 * $schema_base_org_uams
 * $schema_base_org_uams_health
 * $schema_base_org_uams_health_name
 * $schema_common_state
 * $schema_common_usa
 * $schema_common_item_MedicalWebPage
 * 
 */

// Common schema properties

	// Base arrays

		// Common schema properties for all types
		
			$schema_common_properties = array();

		// Common schema properties for MedicalWebPage only
		
			$schema_common_properties_MedicalWebPage = array();

		// Common schema properties for the main entity type
		
			$schema_common_properties_main_entity = array();

		// Common schema properties for types other than MedicalWebPage
		
			$schema_common_properties_exclude_MedicalWebPage = array();

	// Common values used in these properties

		// Common organization credited

			// Base array

				$schema_common_credit = array();

			// Merge in UAMS organization values

				$schema_common_credit = uamswp_fad_schema_merge_values(
					$schema_common_credit, // mixed // Required // Initial schema item property value
					$schema_base_org_uams // mixed // Required // Incoming schema item property value
				);

			// Merge in UAMS Health organization values

				$schema_common_credit = uamswp_fad_schema_merge_values(
					$schema_common_credit, // mixed // Required // Initial schema item property value
					$schema_base_org_uams_health // mixed // Required // Incoming schema item property value
				);

		// Common affiliation organizations

			// Base array

				$schema_common_affiliation_organization = array();

			// UAMS

				$schema_common_affiliation_organization = uamswp_fad_schema_merge_values(
					$schema_common_affiliation_organization, // mixed // Required // Initial schema item property value
					$schema_base_org_uams // mixed // Required // Incoming schema item property value
				);

			// UAMS Health

				$schema_common_affiliation_organization = uamswp_fad_schema_merge_values(
					$schema_common_affiliation_organization, // mixed // Required // Initial schema item property value
					$schema_base_org_uams_health // mixed // Required // Incoming schema item property value
				);

		// Common clinical organizations

			// Base array

				$schema_common_clinical_organization = array();

			// UAMS Health

				$schema_common_clinical_organization = uamswp_fad_schema_merge_values(
					$schema_common_clinical_organization, // mixed // Required // Initial schema item property value
					$schema_base_org_uams_health // mixed // Required // Incoming schema item property value
				);

	// accessMode [WIP]

		/* 
		 * The human sensory perceptual system or cognitive faculty through which a person 
		 * may process or perceive information. Values should be drawn from the approved 
		 * vocabulary.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_accessMode = array();

			// Add to common schema properties array

				if ( $schema_common_accessMode ) {

					$schema_common_properties['accessMode'] = $schema_common_accessMode;

				}

		}

	// accessModeSufficient [WIP]

		/* 
		 * A list of single or combined accessModes that are sufficient to understand all 
		 * the intellectual content of a resource. Values should be drawn from the 
		 * approved vocabulary.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - ItemList
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_accessModeSufficient = array();

			// Add to common schema properties array

				if ( $schema_common_accessModeSufficient ) {

					$schema_common_properties['accessModeSufficient'] = $schema_common_accessModeSufficient;

				}

		}

	// accessibilityAPI [WIP]

		/* 
		 * Indicates that the resource is compatible with the referenced accessibility 
		 * API. Values should be drawn from the approved vocabulary 
		 * (https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityAPI-vocabulary).
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_accessibilityAPI = array();

			// Add to common schema properties array

				if ( $schema_common_accessibilityAPI ) {

					$schema_common_properties['accessibilityAPI'] = $schema_common_accessibilityAPI;

				}

		}

	// accessibilityControl [WIP]

		/* 
		 * Identifies input methods that are sufficient to fully control the described 
		 * resource. Values should be drawn from the approved vocabulary 
		 * (https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityControl-vocabulary).
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_accessibilityControl = array();

			// Add to common schema properties array

				if ( $schema_common_accessibilityControl ) {

					$schema_common_properties['accessibilityControl'] = $schema_common_accessibilityControl;

				}

		}

	// accessibilityFeature [WIP]

		/* 
		 * Content features of the resource, such as accessible media, alternatives and 
		 * supported enhancements for accessibility. Values should be drawn from the 
		 * approved vocabulary 
		 * (https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityFeature-vocabulary).
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_accessibilityFeature = array();

			// Add to common schema properties array

				if ( $schema_common_accessibilityFeature ) {

					$schema_common_properties['accessibilityFeature'] = $schema_common_accessibilityFeature;

				}

		}

	// accessibilityHazard [WIP]

		/* 
		 * A characteristic of the described resource that is physiologically dangerous to 
		 * some users. Related to WCAG 2.0 guideline 2.3. Values should be drawn from the 
		 * approved vocabulary.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_accessibilityHazard = array();

			// Add to common schema properties array

				if ( $schema_common_accessibilityHazard ) {

					$schema_common_properties['accessibilityHazard'] = $schema_common_accessibilityHazard;

				}

		}

	// accessibilitySummary [WIP]

		/* 
		 * A human-readable summary of specific accessibility features or deficiencies, 
		 * consistent with the other accessibility metadata but expressing subtleties such 
		 * as "short descriptions are present but long descriptions will be needed for 
		 * non-visual users" or "short descriptions are present and no long descriptions 
		 * are needed."
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_accessibilitySummary = array();

			// Add to common schema properties array

				if ( $schema_common_accessibilitySummary ) {

					$schema_common_properties['accessibilitySummary'] = $schema_common_accessibilitySummary;

				}

		}

	// affiliation

		/* 
		 * An organization that this person is affiliated with. For example, a 
		 * school/university, a club, or a team.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 */

		// Get values

			// Base array

				$schema_common_affiliation = array();

			// Merge in common affiliation organizations values

				$schema_common_affiliation = uamswp_fad_schema_merge_values(
					$schema_common_affiliation, // mixed // Required // Initial schema item property value
					$schema_common_affiliation_organization // mixed // Required // Incoming schema item property value
				);

		// Add to common schema properties array

			if ( $schema_common_affiliation ) {

				$schema_common_properties['affiliation'] = $schema_common_affiliation;

			}

	// areaServed

		/* 
		 * The geographic area where a service or offered item is provided.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - AdministrativeArea
		 *     - GeoShape
		 *     - Place
		 *     - Text
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_areaServed = $schema_common_arkansas;

			// Add to common schema properties array

				if ( $schema_common_areaServed ) {

					$schema_common_properties['areaServed'] = $schema_common_areaServed;

				}

		}

	// audience

		/* 
		 * An intended audience, i.e. a group for whom something was created.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Audience
		 */

		if ( $nesting_level == 0 ) {

			// Add to common schema properties array

				if ( $schema_common_audience ) {

					$schema_common_properties['audience'] = $schema_common_audience;

				}

		}

	// author

		/* 
		 * The author of this content or rating. Please note that author is special in 
		 * that HTML 5 provides a special mechanism for indicating authorship via the rel 
		 * tag. That is equivalent to this and may be used interchangeably.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

					$schema_common_author = array();

				// Merge in common organization credited values

					$schema_common_author = uamswp_fad_schema_merge_values(
						$schema_common_author, // mixed // Required // Initial schema item property value
						$schema_common_credit // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array

				if ( $schema_common_author ) {

					$schema_common_properties['author'] = $schema_common_author;

				}

		}

	// brand

		/* 
		 * The brand(s) associated with a product or service, or the brand(s) maintained 
		 * by an organization or business person.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Brand
		 *     - Organization
		 */

		// Get values

			// Base array

				$schema_common_brand = array();

			// Merge in common clinical organizations values

				$schema_common_brand = uamswp_fad_schema_merge_values(
					$schema_common_brand, // mixed // Required // Initial schema item property value
					$schema_common_clinical_organization // mixed // Required // Incoming schema item property value
				);

		// Add to common schema properties array

			if ( $schema_common_brand ) {

				$schema_common_properties['brand'] = $schema_common_brand;

			}

	// contributor

		/* 
		 * A secondary contributor to the CreativeWork or Event.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

					$schema_common_contributor = array();

				// Merge in common organization credited values

					$schema_common_contributor = uamswp_fad_schema_merge_values(
						$schema_common_contributor, // mixed // Required // Initial schema item property value
						$schema_common_credit // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array

				if ( $schema_common_contributor ) {

					$schema_common_properties['contributor'] = $schema_common_contributor;

				}

		}

	// copyrightHolder

		/* 
		 * The party holding the legal copyright to the CreativeWork.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

					$schema_common_copyrightHolder = array();

				// Merge in UAMS organization values

					$schema_common_copyrightHolder = uamswp_fad_schema_merge_values(
						$schema_common_copyrightHolder, // mixed // Required // Initial schema item property value
						$schema_base_org_uams // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array

				if ( $schema_common_copyrightHolder ) {

					$schema_common_properties['copyrightHolder'] = $schema_common_copyrightHolder;

				}

		}

	// copyrightNotice [WIP]

		/* 
		 * Text of a notice appropriate for describing the copyright aspects of this 
		 * Creative Work, ideally indicating the owner of the copyright for the Work.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_copyrightNotice = array();

			// Add to common schema properties array

				if ( $schema_common_copyrightNotice ) {

					$schema_common_properties['copyrightHolder'] = $schema_common_copyrightNotice;

				}

		}

	// copyrightYear [WIP]

		/* 
		 * The year during which the claimed copyright for the CreativeWork was first 
		 * asserted.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Number
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_copyrightYear = array();

			// Add to common schema properties array

				if ( $schema_common_copyrightYear ) {

					$schema_common_properties['copyrightYear'] = $schema_common_copyrightYear;

				}

		}

	// countryOfOrigin

		/* 
		 * The country of origin of something, including products as well as creative 
		 * works such as movie and TV content.
		 * 
		 * In the case of TV and movie, this would be the country of the principle offices 
		 * of the production company or individual responsible for the movie. For other 
		 * kinds of CreativeWork it is difficult to provide fully general guidance, and 
		 * properties such as contentLocation and locationCreated may be more applicable.
		 * 
		 * In the case of products, the country of origin of the product. The exact 
		 * interpretation of this may vary by context and product type, and cannot be 
		 * fully enumerated here.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Country
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_countryOfOrigin = $schema_common_usa;

			// Add to common schema properties array

				if ( $schema_common_countryOfOrigin ) {

					$schema_common_properties['countryOfOrigin'] = $schema_common_countryOfOrigin;

				}

		}

	// creativeWorkStatus

		/* 
		 * The status of a creative work in terms of its stage in a lifecycle. Example 
		 * terms include Incomplete, Draft, Published, Obsolete. Some organizations define 
		 * a set of terms for the stages of their publication lifecycle.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - DefinedTerm
		 *     - Text
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_creativeWorkStatus = 'Published';

			// Add to common schema properties array

				if ( $schema_common_creativeWorkStatus ) {

					$schema_common_properties['creativeWorkStatus'] = $schema_common_creativeWorkStatus;

				}

		}

	// creator

		/* 
		 * The creator/author of this CreativeWork. This is the same as the Author 
		 * property for CreativeWork.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

				$schema_common_creator = array();

				// Merge in common organization credited values

					$schema_common_creator = uamswp_fad_schema_merge_values(
						$schema_common_creator, // mixed // Required // Initial schema item property value
						$schema_common_credit // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array

				if ( $schema_common_creator ) {

					$schema_common_properties['creator'] = $schema_common_creator;

				}

		}

	// creditText

		/* 
		 * Text that can be used to credit person(s) and/or organization(s) associated 
		 * with a published Creative Work.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_creditText = $schema_base_org_uams_health_name ?? '';

			// Add to common schema properties array

				if ( $schema_common_creator ) {

					$schema_common_properties['creator'] = $schema_common_creator;

				}

		}

	// dateModified

		/* 
		 * The date on which the CreativeWork was most recently modified or when the 
		 * item's entry was modified within a DataFeed.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Date
		 *     - DateTime
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				if ( !isset($schema_common_dateModified) ) {

					$schema_common_dateModified = get_the_modified_date( 'c', $entity ) ?? '';

				}

			// Add to common schema properties array

				if ( $schema_common_dateModified ) {

					$schema_common_properties['dateModified'] = $schema_common_dateModified;

				}

		}

	// datePublished

		/* 
		 * Date of first broadcast/publication.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Date
		 *     - DateTime
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_datePublished = get_the_date( 'c', $entity ) ?? '';

			// Add to common schema properties array

				if ( $schema_common_datePublished ) {

					$schema_common_properties['datePublished'] = $schema_common_datePublished;

				}

		}

	// inLanguage

		/* 
		 * The language of the content or performance or used in an action. Please use one 
		 * of the language codes from the IETF BCP 47 standard. See also availableLanguage.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Language
		 *     - Text
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_inLanguage = $schema_base_website_uams_health_inLanguage;

			// Add to common schema properties array

				if ( $schema_common_inLanguage ) {

					$schema_common_properties['inLanguage'] = $schema_common_inLanguage;

				}

		}

	// isAccessibleForFree (MedicalWebPage only)

		/* 
		 * A flag to signal that the item, event, or place is accessible for free.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Boolean
		 */

		// Get values

			$schema_common_isAccessibleForFree_MedicalWebPage = 'True';

		// Add to common schema properties array for MedicalWebPage only

			if ( $schema_common_isAccessibleForFree_MedicalWebPage ) {

				$schema_common_properties_MedicalWebPage['inLanguage'] = $schema_common_isAccessibleForFree_MedicalWebPage;

			}

	// isFamilyFriendly

		/* 
		 * Indicates whether this content is family friendly.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Boolean
		 */

		// Get values

			$schema_common_isFamilyFriendly = 'True';

		// Add to common schema properties array

			if ( $schema_common_isFamilyFriendly ) {

				$schema_common_properties['isFamilyFriendly'] = $schema_common_isFamilyFriendly;

			}

	// isPartOf (MedicalWebPage only)

		/* 
		 * Indicates an item or CreativeWork that this item, or CreativeWork (in some 
		 * sense), is part of.
		 * 
		 * Inverse-property: hasPart
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 *     - URL
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

					$schema_common_isPartOf_MedicalWebPage = array();

				// Merge in UAMSHealth.com WebSite values

					$schema_common_isPartOf_MedicalWebPage = uamswp_fad_schema_merge_values(
						$schema_common_isPartOf, // mixed // Required // Initial schema item property value
						$schema_base_website_uams_health // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array for MedicalWebPage only

				if ( $schema_common_isPartOf_MedicalWebPage ) {

					$schema_common_properties_MedicalWebPage['isPartOf'] = $schema_common_isPartOf_MedicalWebPage;

				}

		}

	// lastReviewed

		/* 
		 * Date on which the content on this web page was last reviewed for accuracy 
		 * and/or completeness.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Date
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				if ( !isset($schema_common_dateModified) ) {

					$schema_common_dateModified = get_the_modified_date( 'c', $entity ) ?? '';

				}

			// Add to common schema properties array

				if ( $schema_common_dateModified ) {

					$schema_common_properties['lastReviewed'] = $schema_common_dateModified;

				}

		}

	// mainContentOfPage (MedicalWebPage only)

		/* 
		 * Indicates if this web page element is the main subject of the page.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - WebPageElement
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_mainContentOfPage = array(
					'@type' => 'WebPageElement',
					'cssSelector' => 'main'
				);

			// Add to common schema properties array for MedicalWebPage only

				if ( $schema_common_mainContentOfPage ) {

					$schema_common_properties_MedicalWebPage['mainContentOfPage'] = $schema_common_mainContentOfPage;

				}

		}

	// mainEntityOfPage

		/* 
		 * Indicates a page (or other CreativeWork) for which this thing is the main 
		 * entity being described. See background notes at 
		 * https://schema.org/docs/datamodel.html#mainEntityBackground for details.
		 * 
		 * Inverse-property: mainEntity
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 *     - URL
		 */

		// Get values

			// Base array

			$schema_common_mainEntityOfPage = array();

			// Merge in current entity's MedicalWebPage type item

				$schema_common_mainEntityOfPage = uamswp_fad_schema_merge_values(
					$schema_common_mainEntityOfPage, // mixed // Required // Initial schema item property value
					$schema_common_item_MedicalWebPage // mixed // Required // Incoming schema item property value
				);

		// Add to common schema properties for types other than MedicalWebPage array

			if ( $schema_common_mainEntityOfPage ) {

				$schema_common_properties_exclude_MedicalWebPage['mainEntityOfPage'] = $schema_common_mainEntityOfPage;

			}

	// maintainer

		/* 
		 * A maintainer of a Dataset, software package (SoftwareApplication), or other 
		 * Project.
		 * 
		 * A maintainer is a Person or Organization that manages contributions to, and/or 
		 * publication of, some (typically complex) artifact.
		 * 
		 * It is common for distributions of software and data to be based on "upstream" 
		 * sources.
		 * 
		 * When maintainer is applied to a specific version of something (e.g., a 
		 * particular version or packaging of a Dataset), it is always possible that the 
		 * upstream source has a different maintainer.
		 * 
		 * The isBasedOn property can be used to indicate such relationships between 
		 * datasets to make the different maintenance roles clear.
		 * 
		 * Similarly in the case of software, a package may have dedicated maintainers 
		 * working on integration into software distributions such as Ubuntu, as well as 
		 * upstream maintainers of the underlying work.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

				$schema_common_maintainer = array();

				// Merge in common organization credited values

					$schema_common_maintainer = uamswp_fad_schema_merge_values(
						$schema_common_maintainer, // mixed // Required // Initial schema item property value
						$schema_common_credit // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array

				if ( $schema_common_maintainer ) {

					$schema_common_properties['maintainer'] = $schema_common_maintainer;

				}

		}

	// medicalAudience

		/* 
		 * Medical audience for page.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - MedicalAudience (Type)
		 *     - MedicalAudienceType (Enumeration Type)
		 */

		// Add to common schema properties array

			if ( $schema_common_medicalAudience ) {

				$schema_common_properties['medicalAudience'] = $schema_common_medicalAudience;

			}

	// memberOf

		/* 
		 * An Organization (or ProgramMembership) to which this Person or Organization 
		 * belongs.
		 * 
		 * Inverse-property: member
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - ProgramMembership
		 */

		// Get values

			// Base array

				$schema_common_memberOf = array();

			// Merge in common affiliation organizations values

				$schema_common_memberOf = uamswp_fad_schema_merge_values(
					$schema_common_memberOf, // mixed // Required // Initial schema item property value
					$schema_common_affiliation_organization // mixed // Required // Incoming schema item property value
				);

		// Add to common schema properties array

			if ( $schema_common_memberOf ) {

				$schema_common_properties['memberOf'] = $schema_common_memberOf;

			}

	// parentOrganization

		/* 
		 * The larger organization that this organization is a subOrganization of, if any.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 */

		// Get values

			// Base array

				$schema_common_parentOrganization = array();

			// Merge in common affiliation organizations values

				$schema_common_parentOrganization = uamswp_fad_schema_merge_values(
					$schema_common_parentOrganization, // mixed // Required // Initial schema item property value
					$schema_common_affiliation_organization // mixed // Required // Incoming schema item property value
				);

		// Add to common schema properties array

			if ( $schema_common_parentOrganization ) {

				$schema_common_properties['parentOrganization'] = $schema_common_parentOrganization;

			}

	// producer

		/* 
		 * The person or organization who produced the work (e.g., music album, movie, 
		 * TV/radio series).
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

				$schema_common_producer = array();

				// Merge in common organization credited values

					$schema_common_producer = uamswp_fad_schema_merge_values(
						$schema_common_producer, // mixed // Required // Initial schema item property value
						$schema_common_credit // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array

				if ( $schema_common_producer ) {

					$schema_common_properties['producer'] = $schema_common_producer;

				}

		}

	// publisher

		/* 
		 * The publisher of the creative work.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

				$schema_common_publisher = array();

				// Merge in common organization credited values

					$schema_common_publisher = uamswp_fad_schema_merge_values(
						$schema_common_publisher, // mixed // Required // Initial schema item property value
						$schema_common_credit // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array

				if ( $schema_common_publisher ) {

					$schema_common_properties['publisher'] = $schema_common_publisher;

				}

		}

	// reviewedBy

		/* 
		 * People or organizations that have reviewed the content on this web page for 
		 * accuracy and/or completeness.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

				$schema_common_reviewedBy = array();

				// Merge in common organization credited values

					$schema_common_reviewedBy = uamswp_fad_schema_merge_values(
						$schema_common_reviewedBy, // mixed // Required // Initial schema item property value
						$schema_common_credit // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array

				if ( $schema_common_reviewedBy ) {

					$schema_common_properties['reviewedBy'] = $schema_common_reviewedBy;

				}

		}

	// smokingAllowed

		/* 
		 * Indicates whether it is allowed to smoke in the place (e.g., in the restaurant, 
		 * hotel or hotel room).
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Boolean
		 */

		if ( $nesting_level == 0 ) {

			// Define value

				$schema_common_smokingAllowed = 'False';

			// Add to common schema properties array

				if ( $schema_common_smokingAllowed ) {

					$schema_common_properties['smokingAllowed'] = $schema_common_smokingAllowed;

				}

		}

	// sourceOrganization

		/* 
		 * The Organization on whose behalf the creator was working.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

				$schema_common_sourceOrganization = array();

				// Merge in common organization credited values

					$schema_common_sourceOrganization = uamswp_fad_schema_merge_values(
						$schema_common_sourceOrganization, // mixed // Required // Initial schema item property value
						$schema_common_credit // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array

				if ( $schema_common_sourceOrganization ) {

					$schema_common_properties['sourceOrganization'] = $schema_common_sourceOrganization;

				}

		}

	// subjectOf

		/* 
		 * A CreativeWork or Event about this Thing.
		 * 
		 * Inverse-property: about
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 *     - Event
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

					$schema_common_subjectOf = array();

				// Merge in current entity's MedicalWebPage type item

					$schema_common_subjectOf = uamswp_fad_schema_merge_values(
						$schema_common_subjectOf, // mixed // Required // Initial schema item property value
						$schema_common_item_MedicalWebPage // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties for types other than MedicalWebPage array

				if ( $schema_common_subjectOf ) {

					$schema_common_properties_exclude_MedicalWebPage['subjectOf'] = $schema_common_subjectOf;

				}

		}

	// worksFor

		/* 
		 * Organizations that the person works for.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 */

		// Get values

			// Base array

				$schema_common_worksFor = array();

			// Merge in common affiliation organizations values

				$schema_common_worksFor = uamswp_fad_schema_merge_values(
					$schema_common_worksFor, // mixed // Required // Initial schema item property value
					$schema_common_affiliation_organization // mixed // Required // Incoming schema item property value
				);

		// Add to common schema properties array

			if ( $schema_common_worksFor ) {

				$schema_common_properties['worksFor'] = $schema_common_worksFor;

			}
