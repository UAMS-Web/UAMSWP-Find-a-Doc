<?php

/*
 * Required vars:
 *     $entity ($id of current post)
 *     $schema_base_org_uams
 *     $schema_base_org_uams_health
 *     $schema_base_org_uams_health_name
 *     $schema_common_state
 *     $schema_common_usa
 *     $schema_common_item_MedicalWebPage
 *     $schema_common_specific_clinical_organization // Clinical organization(s) specific to the current entity
 *     $schema_common_url // URL of the current entity
 *     $schema_common_excerpt
 * 
 */

// Check/define variables

	$entity = $entity ?? null;
	$schema_base_org_uams = $schema_base_org_uams ?? null;
	$schema_base_org_uams_health = $schema_base_org_uams_health ?? null;
	$schema_base_org_uams_health_name = $schema_base_org_uams_health_name ?? null;
	$schema_common_state = $schema_common_state ?? null;
	$schema_common_usa = $schema_common_usa ?? null;
	$schema_common_item_MedicalWebPage = $schema_common_item_MedicalWebPage ?? null;
	$schema_common_specific_clinical_organization = $schema_common_specific_clinical_organization ?? null;
	$schema_common_url = $schema_common_url ?? null;
	$schema_common_excerpt = $schema_common_excerpt ?? null;

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

		// Excerpt

			if ( !isset($schema_common_excerpt) ) {

				$schema_common_excerpt = get_the_excerpt( $entity ) ?? null;

				// Clean up excerpt value

					if ( $schema_common_excerpt ) {

						$schema_common_excerpt = wp_strip_all_tags($schema_common_excerpt);
						$schema_common_excerpt = str_replace("\n", ' ', $schema_common_excerpt); // Strip line breaks
						$schema_common_excerpt = strlen($schema_common_excerpt) > 160 ? mb_strimwidth($schema_common_excerpt, 0, 156, '...') : $schema_common_excerpt; // Limit to 160 characters
						$schema_common_excerpt = uamswp_attr_conversion($schema_common_excerpt);

					}

				// Create TextObject version

					$schema_common_excerpt_TextObject = null;

					if ( $schema_common_excerpt ) {

						$schema_common_excerpt_TextObject = array(
							'@id' => $schema_common_url ? $schema_common_url . '#Description' : '',
							'@type' => 'TextObject',
							'text' => $schema_common_excerpt,
						);

						$provider_description = array_filter($provider_description);

					}

			}

	// abstract [WIP]

		/* 
		 * An abstract is a short description that summarizes a CreativeWork.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * Get the excerpt. Let the specific entity's schema function handle the fallback 
		 * value.
		 */

		// Add to common schema properties array

			if ( $schema_common_excerpt ) {

				$schema_common_properties['abstract'] = $schema_common_excerpt;

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

	// accountablePerson [excluded]

		/* 
		 * Specifies the Person that is legally accountable for the CreativeWork.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Person
		 * 
		 * We will not be identifying the person that is legally accountable for 
		 * UAMSHealth.com webpages of their content and so this schema property will not 
		 * be included.
		 */

	// acquireLicensePage [excluded]

		/* 
		 * Indicates a page documenting how licenses can be purchased or otherwise 
		 * acquired, for the current item.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 *     - URL
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// actor [excluded]

		/* 
		 * An actor (e.g., in TV, radio, movie, video games, an event). Actors can be 
		 * associated with individual items or with a series, episode, clip.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Person
		 */

	// actors [superseded]

		/* 
		 * SupersededBy: actor
		 */

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

	// alternativeHeadline [excluded]

		/* 
		 * A secondary title of the CreativeWork.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// archivedAt [excluded for MedicalWebPage]

		/* 
		 * Indicates a page or other link involved in archival of a CreativeWork. In the 
		 * case of MediaReview, the items in a MediaReviewItem may often become 
		 * inaccessible, but be archived by archival, journalistic, activist, or law 
		 * enforcement organizations. In such cases, the referenced page may not directly 
		 * publish the content.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - URL
		 *     - WebPage
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * UAMSHealth.com webpages are not being procedurally archived and so this schema 
		 * property will not be included.
		 */

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

	// articleSection [excluded]

		/* 
		 * Articles may belong to one or more 'sections' in a magazine or newspaper, such 
		 * as Sports, Lifestyle, etc.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// aspect [superseded]

		/* 
		 * SupersededBy: mainContentOfPage
		 */

	// assesses [excluded]

		/* 
		 * The item being described is intended to assess the competency or learning 
		 * outcome defined by the referenced term.
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

	// associatedArticle [excluded]

		/* 
		 * A NewsArticle associated with the Media Object.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - NewsArticle
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// associatedMedia [excluded]

		/* 
		 * A media object that encodes this CreativeWork. This property is a synonym for 
		 * encoding.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - MediaObject
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

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

	// audio [excluded for MedicalWebPage]

		/* 
		 * An embedded audio object.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - AudioObject
		 *     - Clip
		 *     - MusicRecording
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages and will not be 
		 * included for the MedicalWebPage schema type.
		 */

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

	// award [excluded for MedicalWebPage]

		/* 
		 * An award won by or for this item.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages and will not be 
		 * included for the MedicalWebPage schema type.
		 */

	// awards [superseded]

		/* 
		 * SupersededBy: award
		 */

	// backstory [excluded]

		/* 
		 * For an Article, typically a NewsArticle, the backstory property provides a 
		 * textual summary giving a brief explanation of why and how an article was 
		 * created. In a journalistic setting this could include information about 
		 * reporting process, methods, interviews, data sources, etc.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 *     - Text
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

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

	// character [excluded]

		/* 
		 * Fictional person connected with a creative work.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Person
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// comment [excluded]

		/* 
		 * Comments, typically from users.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Comment
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// commentCount [excluded]

		/* 
		 * The number of comments this CreativeWork (e.g. Article, Question or Answer) has 
		 * received. This is most applicable to works published in Web sites with 
		 * commenting system; additional comments may exist elsewhere.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - [insert type(s) here]
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// conditionsOfAccess [excluded]

		/* 
		 * Conditions that affect the availability of, or method(s) of access to, an item. 
		 * Typically used for real world items such as an ArchiveComponent held by an 
		 * ArchiveOrganization. This property is not suitable for use as a general Web 
		 * access control mechanism. It is expressed only in natural language.
		 * 
		 * For example "Available by appointment from the Reading Room" or "Accessible 
		 * only from logged-in accounts."
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// contentRating [excluded]

		/* 
		 * Official rating of a piece of content (e.g., 'MPAA PG-13').
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Rating
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// contentReferenceTime [excluded]

		/* 
		 * The specific time described by a creative work, for works (e.g., articles, 
		 * video objects) that emphasize a particular moment within an Event.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - DateTime
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

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

	// correction [excluded]

		/* 
		 * Indicates a correction to a CreativeWork, either via a CorrectionComment, 
		 * textually or in another document.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CorrectionComment
		 *     - Text
		 *     - URL
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

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

	// dateCreated [excluded]

		/* 
		 * The date on which the CreativeWork was created or the item was added to a 
		 * DataFeed.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Date
		 *     - DateTime
		 * 
		 * The DataFeed schema type is defined as "a single feed providing structured 
		 * information about one or more entities or topics." This schema property is not 
		 * relevant to UAMSHealth.com webpages or their content and will not be included.
		 */

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

	// description

		/* 
		 * A description of the item.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 *     - TextObject
		 * 
		 * Get the excerpt. Let the specific entity's schema function handle the fallback 
		 * value.
		 */

		// Add to common schema properties array

			if ( $schema_common_excerpt_TextObject ) {

				$schema_common_properties['description'] = $schema_common_excerpt_TextObject;

			}

	// director [excluded]

		/* 
		 * A director of e.g. TV, radio, movie, video gaming etc. content, or of an event. 
		 * Directors can be associated with individual items or with a series, episode, 
		 * clip.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Person
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// directors [superseded]

		/* 
		 * SupersededBy: director
		 */

	// discussionUrl [excluded]

		/* 
		 * A link to the page containing the comments of the CreativeWork.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - URL
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// editEIDR [excluded]

		/* 
		 * An EIDR (Entertainment Identifier Registry) identifier representing a specific 
		 * edit / edition for a work of film or television.
		 * 
		 * For example, the motion picture known as "Ghostbusters" whose titleEIDR is 
		 * "10.5240/7EC7-228A-510A-053E-CBB8-J" has several edits 
		 * (e.g., "10.5240/1F2A-E1C5-680A-14C6-E76B-I", 
		 * "10.5240/8A35-3BEE-6497-5D12-9E4F-3").
		 * 
		 * Since schema.org types like Movie and TVEpisode can be used for both works and 
		 * their multiple expressions, it is possible to use titleEIDR alone (for a 
		 * general description), or alongside editEIDR for a more edit-specific 
		 * description.
		 * 
		 * EIDR: https://eidr.org/
		 * identifier: https://schema.org/identifier
		 * titleEIDR: https://schema.org/titleEIDR
		 * Movie: https://schema.org/Movie
		 * TVEpisode: https://schema.org/TVEpisode
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 *     - URL
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// editor [excluded]

		/* 
		 * Specifies the Person who edited the CreativeWork.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Person
		 * 
		 * We will not be identifying the person that edited UAMSHealth.com webpages or 
		 * their content and so this schema property will not be included.
		 */

	// educationalAlignment [excluded]

		/* 
		 * An alignment to an established educational framework.
		 * 
		 * This property should not be used where the nature of the alignment can be 
		 * described using a simple property, for example to express that a resource 
		 * teaches or assesses a competency.
		 * 
		 * teaches: https://schema.org/teaches
		 * assesses: https://schema.org/assesses
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - AlignmentObject
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// educationalLevel [excluded]

		/* 
		 * The level in terms of progression through an educational or training context. 
		 * Examples of educational levels include 'beginner', 'intermediate' or 
		 * 'advanced', and formal sets of level indicators.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - DefinedTerm
		 *     - Text
		 *     - URL
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// educationalUse [excluded]

		/* 
		 * The purpose of a work in the context of education; for example, 'assignment', 
		 * 'group work'.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - DefinedTerm
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// endTime [excluded]

		/* 
		 * The endTime of something. For a reserved event or service 
		 * (e.g., FoodEstablishmentReservation), the time that it is expected to end. 
		 * For actions that span a period of time, when the action was performed 
		 * (e.g., John wrote a book from January to December). For media, including audio 
		 * and video, it's the time offset of the end of a clip within a larger file.
		 * 
		 * Note that Event uses startDate/endDate instead of startTime/endTime, even when 
		 * describing dates with times. This situation may be clarified in future 
		 * revisions.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - DateTime
		 *     - Time
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// encodesCreativeWork [excluded]

		/* 
		 * The CreativeWork encoded by this media object.
		 * 
		 * Inverse-property: encoding
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// encoding [excluded]

		/* 
		 * A media object that encodes this CreativeWork. This property is a synonym for 
		 * associatedMedia.
		 * 
		 * Inverse-property: encodesCreativeWork
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - MediaObject
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// encodings [superseded]

		/* 
		 * SupersededBy: encoding
		 */

	// exampleOfWork [excluded]

		/* 
		 * A creative work that this work is an example/instance/realization/derivation of.
		 * 
		 * Inverse-property: workExample
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// exifData [excluded]

		/* 
		 * exif data for this object.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - PropertyValue
		 *     - Text
		 * 
		 * This schema property is not currently relevant to UAMSHealth.com webpages or 
		 * their content and will not be included.
		 */

	// expires [excluded]

		/* 
		 * Date the content expires and is no longer useful or available. For example a 
		 * VideoObject or NewsArticle whose availability or relevance is time-limited, or 
		 * a ClaimReview fact check whose publisher wants to indicate that it may no 
		 * longer be relevant (or helpful to highlight) after some date.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Date
		 *     - DateTime
		 * 
		 * This schema property is not currently relevant to UAMSHealth.com webpages or 
		 * their content and will not be included.
		 */

	// fileFormat [superseded]

		/* 
		 * SupersededBy: encodingFormat
		 */

	// funder [excluded]

		/* 
		 * A person or organization that supports (sponsors) something through some kind 
		 * of financial contribution.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 * 
		 * This schema property is not currently relevant to UAMSHealth.com webpages or 
		 * their content and will not be included.
		 */

	// funding [excluded]

		/* 
		 * A Grant that directly or indirectly provide funding or sponsorship for this 
		 * item. See also ownershipFundingInfo.
		 * 
		 * Inverse-property: fundedItem
		 * 
		 * Grant: https://schema.org/Grant
		 * ownershipFundingInfo: https://schema.org/ownershipFundingInfo
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Grant
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not currently relevant to UAMSHealth.com webpages or 
		 * their content and will not be included.
		 */

	// genre [excluded]

		/* 
		 * Genre of the creative work, broadcast channel or group.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 *     - URL
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// headline [excluded for MedicalWebPage]

		/* 
		 * Headline of the article.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages and will not be 
		 * included for the MedicalWebPage schema type.
		 */

	// ineligibleRegion [excluded]

		/* 
		 * The ISO 3166-1 (ISO 3166-1 alpha-2) or ISO 3166-2 code, the place, or the 
		 * GeoShape for the geo-political region(s) for which the offer or delivery charge 
		 * specification is not valid (e.g., a region where the transaction is not 
		 * allowed).
		 * 
		 * See also eligibleRegion.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - GeoShape
		 *     - Place
		 *     - Text
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

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

	// interactionStatistic [excluded]

		/* 
		 * The number of interactions for the CreativeWork using the WebSite or 
		 * SoftwareApplication. The most specific child type of InteractionCounter should 
		 * be used.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - InteractionCounter
		 * 
		 * We will not be identifying the number of any form of interaction with 
		 * UAMSHealth.com webpages or their content, and so this schema property will not 
		 * be included.
		 */

	// interactivityType [excluded]

		/* 
		 * The predominant mode of learning supported by the learning resource. Acceptable 
		 * values are 'active', 'expositive', or 'mixed'.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// interpretedAsClaim [excluded]

		/* 
		 * Used to indicate a specific claim contained, implied, translated or refined 
		 * from the content of a MediaObject or other CreativeWork. The interpreting party 
		 * can be indicated using claimInterpreter.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Claim
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

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

	// isBasedOn [excluded for MedicalWebPage]

		/* 
		 * A resource from which this work is derived or from which it is a modification 
		 * or adaptation.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 *     - Product
		 *     - URL
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages and will not be 
		 * included for the MedicalWebPage schema type.
		 */

	// isBasedOnUrl [superseded]

		/* 
		 * SupersededBy: isBasedOn
		 */

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

				if ( !isset($schema_common_locationCreated) ) {

					$schema_common_locationCreated = get_the_modified_date( 'c', $entity ) ?? '';

				}

			// Add to common schema properties array

				if ( $schema_common_locationCreated ) {

					$schema_common_properties['lastReviewed'] = $schema_common_locationCreated;

				}

		}

	// learningResourceType [excluded for MedicalWebPage]

		/* 
		 * The predominant type or kind characterizing the learning resource. For example, 
		 * 'presentation', 'handout'.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - DefinedTerm
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// license [excluded]

		/* 
		 * A license document that applies to this content, typically indicated by URL.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 *     - URL
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// locationCreated

		/* 
		 * The location where the CreativeWork was created, which may not be the same as 
		 * the location depicted in the CreativeWork.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Place
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

					$schema_common_locationCreated = array();

				// Merge in UAMS organization values

					$schema_common_locationCreated = uamswp_fad_schema_merge_values(
						$schema_common_locationCreated, // mixed // Required // Initial schema item property value
						$schema_base_org_uams // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array

				if ( $schema_common_locationCreated ) {

					$schema_common_properties['locationCreated'] = $schema_common_locationCreated;

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

	// mainEntityOfPage (excluding MedicalWebPage)

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

	// material [excluded]

		/* 
		 * A material that something is made from, e.g. leather, wool, cotton, paper.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Product
		 *     - Text
		 *     - URL
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// materialExtent [excluded]

		/* 
		 * The quantity of the materials being described or an expression of the physical 
		 * space they occupy.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - QuantitativeValue
		 *     - Text
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

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

	// musicBy [excluded]

		/* 
		 * The composer of the soundtrack.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - MusicGroup
		 *     - Person
		 * 
		 * This schema property is not currently relevant to UAMSHealth.com webpages or 
		 * their content and will not be included.
		 */

	// pageEnd [excluded]

		/* 
		 * The page on which the work ends; for example "138" or "xvi".
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Integer
		 *     - Text
		 * 
		 * This schema property is not currently relevant to UAMSHealth.com webpages or 
		 * their content and will not be included.
		 */

	// pageStart [excluded]

		/* 
		 * The page on which the work starts; for example "135" or "xiii".
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Integer
		 *     - Text
		 * 
		 * This schema property is not currently relevant to UAMSHealth.com webpages or 
		 * their content and will not be included.
		 */

	// pagination [excluded]

		/* 
		 * Any description of pages that is not separated into pageStart and pageEnd; for 
		 * example, "1-6, 9, 55" or "10-12, 46-49".
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * This schema property is not currently relevant to UAMSHealth.com webpages or 
		 * their content and will not be included.
		 */

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

	// pattern [excluded]

		/* 
		 * A pattern that something has, for example 'polka dot', 'striped', 
		 * 'Canadian flag'. Values are typically expressed as text, although links to 
		 * controlled value schemes are also supported.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - DefinedTerm
		 *     - Text
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// playerType [excluded]

		/* 
		 * Player type required—for example, Flash or Silverlight.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// position [excluded]

		/* 
		 * The position of an item in a series or sequence of items.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Integer
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

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

	// productionCompany [excluded]

		/* 
		 * The production company or studio responsible for the item (e.g., series, video 
		 * game, episode).
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// provider

		/* 
		 * The service provider, service operator, or service performer; the goods 
		 * producer.
		 * 
		 * Another party (a seller) may offer those services or goods on behalf of the 
		 * provider.
		 * 
		 * A provider may also serve as the seller.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

					$schema_common_provider = array();

				// Merge in relevant clinical 'Organization' value

					if (
						isset($schema_common_specific_clinical_organization)
						&&
						!empty($schema_common_specific_clinical_organization)
					) {

						// Merge in specific clinical 'Organization' value

							$schema_common_provider = uamswp_fad_schema_merge_values(
								$schema_common_provider, // mixed // Required // Initial schema item property value
								$schema_common_specific_clinical_organization // mixed // Required // Incoming schema item property value
							);

					} elseif (
						isset($schema_common_common_clinical_organization)
						&&
						!empty($schema_common_common_clinical_organization)
					) {

						// Merge in common clinical 'Organization' value

							$schema_common_provider = uamswp_fad_schema_merge_values(
								$schema_common_provider, // mixed // Required // Initial schema item property value
								$schema_common_common_clinical_organization // mixed // Required // Incoming schema item property value
							);

					}

			// Add to common schema properties array

				if ( $schema_common_provider ) {

					$schema_common_properties['provider'] = $schema_common_provider;

				}

		}

	// publication [excluded]

		/* 
		 * A publication event associated with the item.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - PublicationEvent
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

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

	// publisherImprint [excluded]

		/* 
		 * The publishing division which published the comic.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// publishingPrinciples [WIP]

		/* 
		 * The publishingPrinciples property indicates (typically via URL) a document 
		 * describing the editorial principles of an Organization or individual 
		 * (e.g., a Person writing a blog) that relate to their activities as a publisher 
		 * (e.g., ethics or diversity policies). When applied to a CreativeWork 
		 * (e.g., NewsArticle) the principles are those of the party primarily responsible 
		 * for the creation of the CreativeWork.
		 * 
		 * While such policies are most typically expressed in natural language, sometimes 
		 * related information (e.g., indicating a funder) can be expressed using 
		 * schema.org terminology.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 *     - URL
		 */

	// recordedAt [excluded]

		/* 
		 * The Event where the CreativeWork was recorded. The CreativeWork may capture all 
		 * or part of the event.
		 * 
		 * Inverse-property: recordedIn
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Event
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// regionsAllowed [excluded]

		/* 
		 * The regions where the media is allowed. If not specified, then it's assumed to 
		 * be allowed everywhere. Specify the countries in ISO 3166 format.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Place
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// releasedEvent [excluded]

		/* 
		 * The place and time the release was issued, expressed as a PublicationEvent.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - PublicationEvent
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// requiresSubscription

		/* 
		 * Indicates if use of the media require a subscription (either paid or free). 
		 * Allowed values are true or false (note that an earlier version had 'yes', 'no').
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Boolean
		 *     - MediaSubscription
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_requiresSubscription = 'False';

			// Add to common schema properties array

				if ( $schema_common_requiresSubscription ) {

					$schema_common_properties['requiresSubscription'] = $schema_common_requiresSubscription;

				}

		}

	// review [excluded for MedicalWebPage]

		/* 
		 * A review of the item.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Review
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages and will not be 
		 * included for the MedicalWebPage schema type.
		 */

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

	// reviews [superseded]

		/* 
		 * SupersededBy: review
		 */

	// schemaVersion

		/* 
		 * Indicates (by URL or string) a particular version of a schema used in some 
		 * CreativeWork. This property was created primarily to indicate the use of a 
		 * specific schema.org release (e.g., 10.0 as a simple string, or more explicitly 
		 * via URL, https://schema.org/docs/releases.html#v10.0). There may be situations 
		 * in which other schemas might usefully be referenced this way 
		 * (e.g. http://dublincore.org/specifications/dublin-core/dces/1999-07-02/) but 
		 * this has not been carefully explored in the community.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 *     - URL
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				$schema_common_schemaVersion = 'https://schema.org/docs/releases.html#v22.0';

			// Add to common schema properties array

				if ( $schema_common_schemaVersion ) {

					$schema_common_properties['schemaVersion'] = $schema_common_schemaVersion;

				}

		}

	// sdDatePublished

		/* 
		 * Indicates the date on which the current structured data was generated / 
		 * published. Typically used alongside sdPublisher.
		 * 
		 * sdPublisher: https://schema.org/sdPublisher
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Date
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 */

		if ( $nesting_level == 0 ) {

			// Get current date

				$schema_common_sdDatePublished = date( 'c' );

			// Add to common schema properties array

				if ( $schema_common_sdDatePublished ) {

					$schema_common_properties['sdDatePublished'] = $schema_common_sdDatePublished;

				}

		}

	// sdLicense [excluded for MedicalWebPage]

		/* 
		 * A license document that applies to this structured data, typically indicated 
		 * by URL.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 *     - URL
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages and will not be 
		 * included for the MedicalWebPage schema type.
		 */

	// sdPublisher

		/* 
		 * Indicates the party responsible for generating and publishing the current 
		 * structured data markup, typically in cases where the structured data is derived 
		 * automatically from existing published content but published on a different 
		 * site. For example, student projects and open data initiatives often re-publish 
		 * existing content with more explicitly structured metadata. The sdPublisher 
		 * property helps make such practices more explicit.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 */

		if ( $nesting_level == 0 ) {

			// Get values

				// Base array

					$schema_common_sdPublisher = array();

				// Merge in UAMS organization values

					$schema_common_sdPublisher = uamswp_fad_schema_merge_values(
						$schema_common_sdPublisher, // mixed // Required // Initial schema item property value
						$schema_base_org_uams // mixed // Required // Incoming schema item property value
					);

			// Add to common schema properties array

				if ( $schema_common_sdPublisher ) {

					$schema_common_properties['sdPublisher'] = $schema_common_sdPublisher;

				}

		}

	// sha256 [excluded]

		/* 
		 * The SHA-2 SHA256 hash of the content of the item. For example, a zero-length 
		 * input has value 
		 * 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855'
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// significantLinks [superseded]

		/* 
		 * SupersededBy: significantLink
		 */

	// size [excluded for MedicalWebPage]

		/* 
		 * A standardized size of a product or creative work, specified either through a 
		 * simple textual string (for example 'XL', '32Wx34L'), a QuantitativeValue with a 
		 * unitCode, or a comprehensive and structured SizeSpecification; in other cases, 
		 * the width, height, depth and weight properties may be more applicable.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - [insert type(s) here]
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages and will not be 
		 * included for the MedicalWebPage schema type.
		 */

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

	// spatial [excluded]

		/* 
		 * The "spatial" property can be used in cases when more specific properties 
		 * (e.g., locationCreated, spatialCoverage, contentLocation) are not known to be 
		 * appropriate.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Place
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// spatialCoverage [excluded]

		/* 
		 * The spatialCoverage of a CreativeWork indicates the place(s) which are the 
		 * focus of the content. It is a subproperty of contentLocation intended primarily 
		 * for more technical and detailed materials. For example with a Dataset, it 
		 * indicates areas that the dataset describes: a dataset of New York weather would 
		 * have spatialCoverage which was the place: the state of New York.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Place
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// sponsor [excluded]

		/* 
		 * A person or organization that supports a thing through a pledge, promise, or 
		 * financial contribution (e.g., a sponsor of a Medical Study or a corporate 
		 * sponsor of an event).
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// startTime [excluded]

		/* 
		 * The startTime of something. For a reserved event or service 
		 * (e.g., FoodEstablishmentReservation), the time that it is expected to start. 
		 * For actions that span a period of time, when the action was performed 
		 * (e.g., John wrote a book from January to December). For media, including audio 
		 * and video, it's the time offset of the start of a clip within a larger file.
		 * 
		 * Note that Event uses startDate/endDate instead of startTime/endTime, even when 
		 * describing dates with times. This situation may be clarified in future 
		 * revisions.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - DateTime
		 *     - Time
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

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

	// teaches [excluded]

		/* 
		 * The item being described is intended to help a person learn the competency or 
		 * learning outcome defined by the referenced term.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - DefinedTerm
		 *     - Text
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// temporal [excluded]

		/* 
		 * The "temporal" property can be used in cases where more specific properties 
		 * (e.g., temporalCoverage, dateCreated, dateModified, datePublished) are not 
		 * known to be appropriate.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - DateTime
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// temporalCoverage [excluded]

		/* 
		 * The temporalCoverage of a CreativeWork indicates the period that the content 
		 * applies to (i.e., that it describes), either as a DateTime or as a textual 
		 * string indicating a time period in ISO 8601 time interval format. In the case 
		 * of a Dataset it will typically indicate the relevant time period in a precise 
		 * notation (e.g., for a 2011 census dataset, the year 2011 would be written 
		 * "2011/2012"). Other forms of content (e.g., ScholarlyArticle, Book, TVSeries, 
		 * TVEpisode), may indicate their temporalCoverage in broader terms — textually or 
		 * via well-known URL. Written works such as books may sometimes have precise 
		 * temporal coverage too (e.g., a work set in 1939 - 1945 can be indicated in 
		 * ISO 8601 interval format format via "1939/1945").
		 * 
		 * Open-ended date ranges can be written with ".." in place of the end date. For 
		 * example, "2015-11/.." indicates a range beginning in November 2015 and with no 
		 * specified final date. This is tentative and might be updated in future when 
		 * ISO 8601 is officially updated.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - DateTime
		 *     - Text
		 *     - URL
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// thumbnail [excluded for MedicalWebPage]

		/* 
		 * Thumbnail image for an image or video.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - ImageObject
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages and will not be 
		 * included for the MedicalWebPage schema type.
		 */

	// thumbnailUrl

		/* 
		 * A thumbnail image relevant to the Thing.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - URL
		 */

		// Get values

			// Base array

				$schema_common_thumbnailUrl = array();

			// Get post thumbnail ID

				$schema_common_thumbnailUrl_id = get_post_thumbnail_id( $entity );

		// Create ImageObject

			$schema_common_thumbnailUrl = uamswp_fad_schema_imageobject_thumbnails(
				$schema_common_url, // URL of entity with which the image is associated
				($nesting_level + 1), // Nesting level within the main schema
				'16:9', // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9', 'full')
				'thumbnailUrl', // Base fragment identifier
				$schema_common_thumbnailUrl_id, // ID of image to use for 1:1 aspect ratio
				0, // ID of image to use for 3:4 aspect ratio
				$schema_common_thumbnailUrl_id, // ID of image to use for 4:3 aspect ratio
				$schema_common_thumbnailUrl_id // ID of image to use for 16:9 aspect ratio
			);

		// Add to common schema properties array

			if ( $schema_common_thumbnailUrl ) {

				$schema_common_properties['thumbnailUrl'] = $schema_common_thumbnailUrl;

			}

	// translationOfWork [excluded]

		/* 
		 * The work that this work has been translated from (e.g., 物种起源 is a 
		 * translationOf "On the Origin of Species".
		 * 
		 * Inverse-property: workTranslation
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// translator [excluded for MedicalWebPage]

		/* 
		 * Organization or person who adapts a creative work to different languages, 
		 * regional differences and technical requirements of a target market, or that 
		 * translates during some event.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Organization
		 *     - Person
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages and will not be 
		 * included for the MedicalWebPage schema type.
		 */

	// typicalAgeRange [excluded]

		/* 
		 * The typical expected age range (e.g. '7-9', '11-').
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// usageInfo [WIP]

		/* 
		 * The schema.org usageInfo property indicates further information about a 
		 * CreativeWork. This property is applicable both to works that are freely 
		 * available and to those that require payment or other transactions. It can 
		 * reference additional information (e.g., community expectations on preferred 
		 * linking and citation conventions), as well as purchasing details. For something 
		 * that can be commercially licensed, usageInfo can provide detailed, 
		 * resource-specific information about licensing options.
		 * 
		 * This property can be used alongside the license property which indicates 
		 * license(s) applicable to some piece of content. The usageInfo property can 
		 * provide information about other licensing options (e.g., acquiring commercial 
		 * usage rights for an image that is also available under non-commercial creative 
		 * commons licenses).
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 *     - URL
		 * 
		 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation 
		 * feedback and adoption from applications and websites can help improve their 
		 * definitions.
		 */

	// version [excluded]

		/* 
		 * The version of the CreativeWork embodied by a specified resource.
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - Number
		 *     - Text
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

	// workExample [excluded]

		/* 
		 * Example/instance/realization/derivation of the concept of this creative work 
		 * (e.g., paperback edition, first edition, e-book).
		 * 
		 * Inverse-property: exampleOfWork
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */

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

	// workTranslation [excluded]

		/* 
		 * A work that is a translation of the content of this work (e.g., 西遊記 has an 
		 * English workTranslation "Journey to the West", a German workTranslation 
		 * "Monkeys Pilgerfahrt" and a Vietnamese translation Tây du ký bình khảo).
		 * 
		 * Inverse-property: translationOfWork
		 * 
		 * Values expected to be one of these types:
		 * 
		 *     - CreativeWork
		 * 
		 * This schema property is not relevant to UAMSHealth.com webpages or their 
		 * content and will not be included.
		 */
