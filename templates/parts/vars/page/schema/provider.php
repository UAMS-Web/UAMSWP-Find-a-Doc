<?php

/*
 * Required vars:
 * 
 * 	$schema_common_base
 * 	$page_id
 */

include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base.php' );

$schema_provider = $schema_common_base;

// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.
$page_id = get_the_ID();

/*

TODO List

 * Provider
	 * Create means of defining organization schema for third-party institutions (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
	 * Create means of associating third-party institutions with provider
	 * Define schema for affiliated hospital(s)
	 * Add fields to Education and Training Organization taxonomy, integrate them into this schema
		 * Required — Query for whether the organization is a College/University
		 * Optional — Alternate Name (repeater)
		 * Required — URL
		 * Optional — Street Address
		 * Required — City / Locality (required)
		 * Required — State / Appropriate first-level Administrative division — https://en.wikipedia.org/wiki/List_of_administrative_divisions_by_country
		 * Required — Country (required) — two-letter ISO 3166-1 alpha-2 country code — https://en.wikipedia.org/wiki/ISO_3166-1#Officially_assigned_code_elements
		 * Optional — Postal Code
	 * Add labels and definitions to Credential Transparency Description Language values map array ($ctdl_values)
	 * Define Provider-as-Dentist type array
	 * Define Provider-as-Optician type array
 * Related ontology items
	 * Related locations
		 * Apply the location schema function to the single location template
		 * Add values for remaining properties:
			 * Properties with pending questions @ https://uamsweb.atlassian.net/browse/FD20-3482
				 * aggregateRating
				 * areaServed
				 * contactPoint
					 * email
				 * currenciesAccepted
				 * diversityPolicy
				 * diversityStaffingReport
				 * ethicsPolicy
				 * funding
				 * identifier
					 * duns
					 * globalLocationNumber
					 * isicV4
					 * iso6523Code
					 * leiCode
					 * naics
					 * taxID
					 * vatID
				 * isAcceptingNewPatients
				 * isAccessibleForFree
				 * knowsLanguage
				 * legalName
				 * logo
				 * maximumAttendeeCapacity
				 * memberOf
				 * nonprofitStatus
				 * numberOfEmployees
				 * paymentAccepted
			 * Needing new data inputs
				 * award
					 * Create taxonomy similar to provider's 'Recognition List', but for locations
						 * Add a section to the location profile / subsection to display said information
				 * brand
				 * event
					 * Find means of populating values from relevant LiveWhale calendar events
				 * foundingDate
					 * Add input for date the location first opened (regardless of physical location)
				 * hasCredential
				 * hasDriveThroughService
				 * potentialAction
				 * publicAccess
				 * review
			 * Other properties
				 * contactPoint
					 * faxNumber
					 * telephone
				 * containedInPlace
					 * For descendant locations, add parent location as the 'Place'
					 * For top-level locations with a building value, add info on its building
						 * Add additional inputs to 'Building' taxonomy for 'Place' schema
							 * Include Google CID
				 * containsPlace
					 * Include descendant locations
					 * Include provider as 'Physician' type 
					 * Include provider as 'Dentist' type 
					 * Include provider as 'Optician' type 
				 * employee
					 * Include each provider associated with the location
				 * keywords
				 * knowsAbout
				 * mainEntityOfPage
				 * makesOffer
				 * openingHours
				 * openingHoursSpecification
				 * parentOrganization
					 * For descendant locations, add the parent location as the 'Organization'
					 * For top-level locations, add UAMS Health as the 'Organization'
				 * photo
					 * Amend values from ImageObject to accommodate properties particular to 'Photograph' type
				 * specialOpeningHoursSpecification
	 * Related areas of expertise
		 * Apply the areas of expertise schema function to the single area of expertise template
		 * Adjust the areas of expertise schema function (or create new one) to support the information on area of expertise fake subpages.
			 * Apply the areas of expertise schema function to the area of expertise fake subpages templates
		 * Add system fallback images to schema if relevant featured image is blank
		 * Add system fallback text to schema if relevant text is blank
	 * Related clinical resources
		 * Apply the clinical resources schema function to the single clinical resource template
		 * Separate resource types (e.g., ImageObject) into separate functions, calling them in the clinical resource schema function
		 * Get more info from YouTube API through YouTube Lyte plugin
			 * videoQuality
			 * videoFrameSize
		 * Get info from Vimeo API
			 * duration
			 * thumbnail
			 * videoQuality
			 * videoFrameSize
	 * Provider
		 * Make function for provider schema
		 * Make adjustments to convert 'Physician' type to 'MedicalBusiness' or a subtype of 'MedicalBusiness' relevant to the particular provider
			 * A dentist -> 'Dentist'
			 * An optician -> 'Optician'
			 * A women's health nurse practitioner -> 'Gynecologic'
		 * Provider as 'MedicalBusiness' type and its subtypes
			 * Find a way to validate whether a provider is an optician so the Optician type can be used in place of MedicalBusiness
			 * 'employee' property
				 * Add Provider as 'Person' type
		 * Consider adding 'ProfilePage' as an additional type on one of the items in @graph
		 * Add new set of inputs for name
			 * Message instructing editors to fully populate all fields and to not use initials
			 * Required inputs:
				 * Full first name
				 * Button group to confirm presence/absence of middle name (default: null)
				 * Full middle name
				 * Button group to confirm presence/absence of nickname (default: null)
				 * Nickname
				 * Full last name
				 * Button group to confirm presence/absence of generational suffix (default: null)
				 * Generational suffix
				 * Name display format selector with a message instructing editors to mirror the external name value defined in Epic (default: 'First Middle Last')
					 * First Middle Last
					 * First Middle "Nickname" Last
					 * First M. Last
					 * First M. "Nickname" Last
					 * F. Middle Last
					 * F. Middle "Nickname" Last
					 * F. M. Last
					 * F. M. "Nickname" Last
			 * Optional inputs
				 * Alternate name repeater (e.g., former names, variant names)
			 * Populate a hidden field in the provider data that is populated by all the name variants and is queried by provider search and site search
		 * Add values for remaining properties:
			 * Properties with pending questions @ https://uamsweb.atlassian.net/browse/FD20-3482
				 * areaServed
				 * currenciesAccepted
				 * identifiers
					 * duns
					 * globalLocationNumber
					 * isicV4
					 * leiCode
					 * naics
					 * taxID
					 * vatID
					 * iso6523Code
				 * isAccessibleForFree
				 * paymentAccepted
			 * Needing new data inputs
				 * alternateName
				 * award
				 * brand
				 * makesOffer
				 * potentialAction
				 * review
				 * sameAs
				 * worksFor
			 * Other properties
				 * additionalName
				 * affiliation
				 * containedInPlace
				 * description
				 * familyName
				 * givenName
				 * hasCredential
				 * hasOccupation
				 * hasMap
				 * honorificPrefix
				 * honorificSuffix
				 * hospitalAffiliation
				 * identifier
					 * the NPI value
					 * the taxonomy code value(s)?
				 * image
				 * isAcceptingNewPatients
				 * jobTitle
				 * keywords
				 * knowsAbout
				 * knowsLanguage
				 * legalName
				 * location
				 * mainEntityOfPage
				 * memberOf
				 * name
				 * parentOrganization
				 * photo
				 * smokingAllowed
				 * subjectOf
				 * workLocation
 * General
	 * Remove irrelevant metaboxes from taxonomy items (e.g., SEO; __ Archive Settings; Layout Settings)
	 * Replace common schema fields with clone fields referencing field in 'assets\json\acf-json\group_uamswp_schema.json'
	 * Resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.
	 * Consider shifting to the use of the 'Thing > CreativeWork > WebContent > HealthTopicContent' type in place of 'MedicalWebPage' and/or the 'CreativeWork' subtypes used for clinical resources.
	 * 'Organization' values (for properties like 'brand', 'parentOrganization' and 'worksFor')
		 * Create method of defining 'Organization' property values for UAMS Health and UAMS
		 * Create method of defining 'Organization' property values for third-party organizations (e.g., Arkansas Children's)
		 * Create method of associating additional 'Organization' options with each ontology item type (e.g., location, provider)
 * Filter ACF fields
	 * Fields referencing MedicalTest type
		 * Just treatments with MedicalTest type or its subtypes
		 * Exclude current item
	 * Fields referencing MedicalTherapy type
		 * Just treatments with MedicalTherapy type or its subtypes
		 * Exclude current item

*/

// Get Values

	// Common property values

		include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

	// Provider archive URL

		$schema_provider_archive_url = user_trailingslashit( get_post_type_archive_link('provider') );

	// Significant links (significantLink)

		// Base array

			$schema_provider_significantLink = array();

	// Related ontology items as schema arrays

		// Related Locations (MedicalWebPage[mentions], Physician[location], Person[workLocation])

			// Base array

				$provider_related_location = array();

			// Define values array for each associated location // Repeat for all associated locations

				// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.

					$locations = get_field( 'physician_locations', $page_id );

				if (
					isset($LocalBusiness_list)
					&&
					!empty($LocalBusiness_list)
					&&
					is_array($LocalBusiness_list)
				) {

					$provider_related_location = $LocalBusiness_list;

				} else {

					$provider_related_location = uamswp_fad_schema_location(
						$locations, // List of IDs of the location items
						$page_url, // Page URL
					);

				}

			// Define reference to each value/row in this property

				$schema_provider_location_ref = uamswp_fad_schema_node_references( $provider_related_location );

			// Get URLs for significantLink property

				$schema_provider_significantLink = uamswp_fad_schema_property_urls(
					$provider_related_location, // Property values from which to extract URLs
					$schema_provider_significantLink // Existing list of URLs
				);

			// If there is only one item, flatten the multi-dimensional array by one step

				uamswp_fad_flatten_multidimensional_array($provider_related_location);

		// Related Areas of Expertise

			// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.

				$expertises = get_field( 'physician_expertise', $page_id );

			// Define the schema for nesting in 'MedicalWebPage'['mentions']

				/*
					Nesting level 0 = 'MedicalWebPage'
					Nesting level 1 = 'MedicalWebPage'['mentions']
				*/

				$provider_related_expertise = uamswp_fad_schema_expertise(
					$expertises, // List of IDs of the clinical resource items
					$page_url, // Page URL
					1, // Nesting level within the main schema
					'MedicalEntity', // Base fragment identifier
				);

			// Define reference to each value/row in this property

				$schema_provider_expertise_ref = uamswp_fad_schema_node_references( $provider_related_expertise );

			// Get URLs for significantLink property

				$schema_provider_significantLink = uamswp_fad_schema_property_urls(
					$provider_related_expertise, // Property values from which to extract URLs
					$schema_provider_significantLink // Existing list of URLs
				);

		// Related Clinical Resources

			// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.

				$clinical_resources = get_field( 'physician_clinical_resources', $page_id );

			// Define the schema for nesting in 'MedicalWebPage'['mentions']

				/*
					Nesting level 0 = 'MedicalWebPage'
					Nesting level 1 = 'MedicalWebPage'['mentions']
				*/

				$provider_related_clinical_resource = uamswp_fad_schema_creativework(
					$clinical_resources, // List of IDs of the clinical resource items
					$page_url, // Page URL
					1, // Nesting level within the main schema
					'CreativeWork' // Base fragment identifier
				);

			// Define reference to each value/row in this property

				$schema_provider_clinical_resource_ref = uamswp_fad_schema_node_references( $provider_related_clinical_resource );

			// Get URLs for significantLink property

				$schema_provider_significantLink = uamswp_fad_schema_property_urls(
					$provider_related_clinical_resource, // Property values from which to extract URLs
					$schema_provider_significantLink // Existing list of URLs
				);

	// Provider Clinical Specialization and Associated Values (medicalSpecialty, et al.)

		// Eliminate PHP errors

			$schema_provider_medicalSpecialty = '';

		if ( $provider_specialty ) {

			// Schema.org MedicalSpecialty Enumeration Member

				$schema_provider_medicalSpecialty = uamswp_fad_schema_medicalSpecialty_specialization(
					$provider_specialty // mixed // Required // Clinical Specialization value(s)
				);

		}

		if ( $provider_specialty_term ) {

			// // Schema.org MedicalSpecialty Enumeration Member
			// 
			// 	$schema_provider_medicalSpecialty = get_field('schema_medicalspecialty_single', $provider_specialty_term);
			// 	$schema_provider_medicalSpecialty = uamswp_attr_conversion($schema_provider_medicalSpecialty);

			// Health Care Provider Taxonomy Code Set (https://taxonomy.nucc.org/)

				// Specialization Taxonomy Code

					$schema_provider_nucc_code = get_field('clinical_specialization_code', $provider_specialty_term);
					$schema_provider_nucc_code = uamswp_attr_conversion($schema_provider_nucc_code);

				// Specialization Name

					$schema_provider_nucc_name = get_field('clinical_specialty_name', $provider_specialty_term);
					$schema_provider_nucc_name = uamswp_attr_conversion($schema_provider_nucc_name);

				// Specialization Display Name

					$schema_provider_nucc_name_display = get_field('clinical_specialization_name_display', $provider_specialty_term);
					$schema_provider_nucc_name_display = uamswp_attr_conversion($schema_provider_nucc_name_display);

			// Centers for Medicare & Medicaid Services (CMS) Specialty Code

				// CMS Specialty Code

					$schema_provider_cms_code = get_field('clinical_specialization_cms_code', $provider_specialty_term);
					$schema_provider_cms_code = uamswp_attr_conversion($schema_provider_cms_code);

				// CMS Provider/Supplier Type

					$schema_provider_cms_code_type = get_field('clinical_specialization_cms_code_type', $provider_specialty_term);
					$schema_provider_cms_code_type = uamswp_attr_conversion($schema_provider_cms_code_type);

			// occupationalCategory

				// O*Net-SOC

					// Occupation Code

						$schema_provider_onetsoc_code = get_field('clinical_specialization_onetsoc_code', $provider_specialty_term);
						$schema_provider_onetsoc_code = uamswp_attr_conversion($schema_provider_onetsoc_code);

					// Occupation Name

						$schema_provider_onetsoc_code_name = get_field('clinical_specialization_onetsoc_code_name', $provider_specialty_term);
						$schema_provider_onetsoc_code_name = uamswp_attr_conversion($schema_provider_onetsoc_code_name);

					// Array

						$schema_provider_onetsoc = array();

						if ( $schema_provider_onetsoc_code ) {

							$schema_provider_onetsoc = array(
								'codeValue' => $schema_provider_onetsoc_code, // O*Net-SOC code value from Specialty item
								'@type' => 'CategoryCode',
								'inCodeSet' => array(
									'@type' => 'CategoryCodeSet',
									'name' => 'O*Net-SOC',
									'dateModified' => '2019',
									'url' => 'https://www.onetonline.org/'
								),
								'name' => isset($schema_provider_onetsoc_code_name) ? $schema_provider_onetsoc_code_name : '', // O*Net-SOC name from Specialty item
								'url' => 'https://www.onetonline.org/link/summary/' . $schema_provider_onetsoc_code // O*Net-SOC URL from Specialty item
							);

							// Remove empty properties

								if ( is_array($schema_provider_onetsoc) ) {

									ksort($schema_provider_onetsoc);

								}

							// Sort the array

								if ( is_array($schema_provider_onetsoc) ) {

									ksort($schema_provider_onetsoc);

								}

						}

				// ISCO-08 Code

					$schema_provider_isco08_code = get_field('clinical_specialization_isco08_code', $provider_specialty_term);

					// Make value attribute-friendly, add it to an array if it is not already

						if ( is_array($schema_provider_isco08_code)) {

							foreach ($schema_provider_isco08_code as $code ) {

								$code = uamswp_attr_conversion($code);

							}

						} else {

							$schema_provider_isco08_code = array(uamswp_attr_conversion($schema_provider_isco08_code));

						}

					// Values map

						$isco08_values = array(
							'2' => array(
								'name' => 'Professionals',
								'description' => 'Professionals increase the existing stock of knowledge; apply scientific or artistic concepts and theories; teach about the foregoing in a systematic manner; or engage in any combination of these activities. Competent performance in most occupations in this major group requires skills at the fourth ISCO skill level.',
								'sameAs' => array()
							),
							'22' => array(
								'name' => 'Health Professionals',
								'description' => 'Health professionals conduct research; improve or develop concepts, theories and operational methods; and apply scientific knowledge relating to medicine, nursing, dentistry, veterinary medicine, pharmacy, and promotion of health.  Competent performance in most occupations in this sub-major group requires skills at the fourth ISCO skill level.',
								'sameAs' => array(
									'2'
								)
							),
							'221' => array(
								'name' => 'Medical Doctors',
								'description' => 'Medical doctors (physicians) study, diagnose, treat and prevent illness, disease, injury and other physical and mental impairments in humans through the application of the principles and procedures of modern medicine. They plan, supervise and evaluate the implementation of care and treatment plans by other health care providers, and conduct medical education and research activities.',
								'sameAs' => array(
									'2',
									'22'
								)
							),
							'2211' => array(
								'name' => 'Generalist Medical Practitioners',
								'description' => 'Generalist medical practitioners (including family and primary care doctors) diagnose, treat and prevent illness, disease, injury and other physical and mental impairments and maintain general health in humans through application of the principles and procedures of modern medicine.  They do not limit their practice to certain disease categories or methods of treatment, and may assume responsibility for the provision of continuing and comprehensive medical care to individuals, families and communities.',
								'sameAs' => array(
									'2',
									'22',
									'221'
								)
							),
							'2212' => array(
								'name' => 'Specialist Medical Practitioners',
								'description' => 'Specialist medical practitioners (medical doctors) diagnose, treat and prevent illness, disease, injury and other physical and mental impairments in humans, using specialized testing, diagnostic, medical, surgical, physical and psychiatric techniques through application of the principles and procedures of modern medicine. They specialize in certain disease categories, types of patient or methods of treatment and may conduct medical education and research in their chosen areas of specialization.',
								'sameAs' => array(
									'2',
									'22',
									'221'
								)
							),
							'222' => array(
								'name' => 'Nursing and Midwifery Professionals',
								'description' => 'Nursing and midwifery professionals provide treatment and care services for people who are physically or mentally ill, disabled or infirm, and others in need of care due to potential risks to health including before, during and after childbirth. They assume responsibility for the planning, management and evaluation of the care of patients, including the supervision of other health care workers, working autonomously or in teams with medical doctors and others in the practical application of preventive and curative measures. ',
								'sameAs' => array(
									'2',
									'22'
								)
							),
							'2221' => array(
								'name' => 'Nursing Professionals',
								'description' => 'Nursing professionals provide treatment, support and care services for people who are in need of nursing care due to the effects of ageing, injury, illness or other physical or mental impairment, or potential risks to health. They assume responsibility for the planning and management of the care of patients, including the supervision of other health care workers, working autonomously or in teams with medical doctors and others in the practical application of preventive and curative measures.',
								'sameAs' => array(
									'2',
									'22',
									'222'
								)
							),
							'2222' => array(
								'name' => 'Midwifery Professionals',
								'description' => 'Midwifery professionals plan, manage, provide and evaluate midwifery care services before, during and after pregnancy and childbirth. They provide delivery care for reducing health risks to women and newborn children, working autonomously or in teams with other health care providers.',
								'sameAs' => array(
									'2',
									'22',
									'222'
								)
							),
							'223' => array(
								'name' => 'Traditional and Complementary Medicine Professionals',
								'description' => 'Traditional and complementary medicine professionals examine patients; prevent and treat illness, disease, injury and other physical and mental impairments; and maintain general health in humans by applying knowledge, skills and practices acquired through extensive study of  the theories, beliefs and experiences originating in specific cultures.',
								'sameAs' => array(
									'2',
									'22'
								)
							),
							'2230' => array(
								'name' => 'Traditional and Complementary Medicine Professionals',
								'description' => 'Traditional and complementary medicine professionals examine patients, prevent and treat illness, disease, injury and other physical and mental impairments and maintain general health in humans by applying knowledge, skills and practices acquired through extensive study of  the theories, beliefs and experiences, originating in specific cultures.',
								'sameAs' => array(
									'2',
									'22',
									'223'
								)
							),
							'224' => array(
								'name' => 'Paramedical Practitioners',
								'description' => 'Paramedical practitioners provide advisory, diagnostic, curative and preventive medical services more limited in scope and complexity than those carried out by medical doctors. They work autonomously or with limited supervision of medical doctors, and apply advanced clinical procedures for treating and preventing diseases, injuries and other physical or mental impairments common to specific communities.',
								'sameAs' => array(
									'2',
									'22'
								)
							),
							'2240' => array(
								'name' => 'Paramedical Practitioners',
								'description' => 'Paramedical practitioners provide advisory, diagnostic, curative and preventive medical services more limited in scope and complexity than those carried out by medical doctors. They work autonomously, or with limited supervision of medical doctors, and apply advanced clinical procedures for treating and preventing diseases, injuries and other physical or mental impairments common to specific communities.',
								'sameAs' => array(
									'2',
									'22',
									'224'
								)
							),
							'225' => array(
								'name' => 'Veterinarians',
								'description' => 'Veterinarians diagnose, prevent and treat diseases, injuries and dysfunctions of animals. They may provide care to a wide range of animals; specialize in the treatment of a particular animal group or in a particular area of specialization; or provide professional services to commercial firms producing biological and pharmaceutical products.',
								'sameAs' => array(
									'2',
									'22'
								)
							),
							'2250' => array(
								'name' => 'Veterinarians',
								'description' => 'Veterinarians diagnose, prevent and treat diseases, injuries and dysfunctions of animals. They may provide care to a wide range of animals or specialize in the treatment of a particular animal group or in a particular specialty area, or provide professional services to commercial firms producing biological and pharmaceutical products.',
								'sameAs' => array(
									'2',
									'22',
									'225'
								)
							),
							'226' => array(
								'name' => 'Other Health Professionals',
								'description' => 'Other health professionals provide health services related to dentistry, pharmacy, environmental health and hygiene, occupational health and safety, physiotherapy, nutrition, hearing, speech, vision and rehabilitation therapies.  This minor group includes all human health professionals except doctors, traditional and complementary medicine practitioners, nurses, midwives and paramedical professionals.',
								'sameAs' => array(
									'2',)
							),
							'2261' => array(
								'name' => 'Dentists',
								'description' => 'Dentists diagnose, treat and prevent diseases, injuries and abnormalities of the teeth, mouth, jaws and associated tissues by applying the principles and procedures of modern dentistry. They use a broad range of specialized diagnostic, surgical and other techniques to promote and restore oral health.',
								'sameAs' => array(
									'2',
									'22',
									'226'
								)
							),
							'2262' => array(
								'name' => 'Pharmacists',
								'description' => 'Pharmacists store, preserve, compound and dispense medicinal products and counsel on the proper use and adverse effects of drugs and medicines following prescriptions issued by medical doctors and other health professionals. They contribute to researching, testing, preparing, prescribing and monitoring medicinal therapies for optimizing human health.',
								'sameAs' => array(
									'2',
									'22',
									'226'
								)
							),
							'2263' => array(
								'name' => 'Environmental and Occupational Health and Hygiene Professionals',
								'description' => 'Environmental and occupational health and hygiene professionals assess, plan and implement programmes to recognize, monitor and control environmental factors that can potentially affect human health, to ensure safe and healthy working conditions and to prevent disease or injury caused by chemical, physical, radiological and biological agents or ergonomic factors.',
								'sameAs' => array(
									'2',
									'22',
									'226'
								)
							),
							'2264' => array(
								'name' => 'Physiotherapists',
								'description' => 'Physiotherapists assess, plan and implement rehabilitative programmes that improve or restore human motor functions, maximize movement ability, relieve pain syndromes, and treat or prevent physical challenges associated with injuries, diseases and other impairments. They apply a broad range of physical therapies and techniques such as movement, ultrasound, heating, laser and other techniques. ',
								'sameAs' => array(
									'2',
									'22',
									'226'
								)
							),
							'2265' => array(
								'name' => 'Dieticians and Nutritionists',
								'description' => 'Dieticians and nutritionists assess, plan and implement programmes to enhance the impact of food and nutrition on human health.',
								'sameAs' => array(
									'2',
									'22',
									'226'
								)
							),
							'2266' => array(
								'name' => 'Audiologists and Speech Therapists',
								'description' => 'Audiologists and speech therapists evaluate, manage and treat physical disorders affecting human hearing, speech, communication and swallowing. They prescribe corrective devices or rehabilitative therapies for hearing loss, speech disorders and related sensory and neural problems, and provide counselling on hearing safety and communication performance.',
								'sameAs' => array(
									'2',
									'22',
									'226'
								)
							),
							'2267' => array(
								'name' => 'Optometrists and Ophthalmic Opticians',
								'description' => 'Optometrists and ophthalmic opticians provide diagnosis, management and treatment services for disorders of the eyes and visual system. They counsel on eye care and prescribe optical aids or other therapies for visual disturbance.',
								'sameAs' => array(
									'2',
									'22',
									'226'
								)
							),
							'2269' => array(
								'name' => 'Health Professionals Not Elsewhere Classified ',
								'description' => 'This unit group covers health professionals not classified elsewhere in Sub-major Group 22: Health Professionals. For instance, the group includes occupations such as podiatrist, occupational therapist, recreational therapist, chiropractor, osteopath and other professionals providing diagnostic, preventive, curative and rehabilitative health services.',
								'sameAs' => array(
									'2',
									'22',
									'226'
								)
							)
						);

					// // (Optional) Expand ISCO-8 code to include list of ancestors
					// 
					// 	if ( $schema_provider_isco08_code ) {
					// 
					// 		$schema_provider_isco08_code = array_merge(
					// 			$schema_provider_isco08_code,
					// 			$isco08_values[$schema_provider_isco08_code[0]]['sameAs']
					// 		);
					// 
					// 		sort($schema_provider_isco08_code);
					// 
					// 	}

					// Array

						$schema_provider_isco08 = array();

						if ( $schema_provider_isco08_code ) {

							foreach ( $schema_provider_isco08_code as $code ) {

								$schema_provider_isco08_array = array(
									'codeValue' => $code, // ISCO-08 code value from Specialty item
									'@type' => 'CategoryCode',
									'description' => isset($isco08_values[$code]['description']) ? $isco08_values[$code]['description'] : '', // ISCO-08 description from Specialty item (called "Lead Statement" in "Draft ISCO-08 Group Definitions: Occupations in Health")
									'inCodeSet' => array(
										'@type' => 'CategoryCodeSet',
										'dateModified' => '2016',
										'name' => 'ISCO-08',
										'url' => 'https://www.ilo.org/public/english/bureau/stat/isco/isco08/'
									),
									'name' => isset($isco08_values[$code]['name']) ? $isco08_values[$code]['name'] : '', // ISCO-08 name from Specialty item
									'url' => 'https://www.ilo.org/public/english/bureau/stat/isco/docs/health.pdf'
								);

								// Remove empty properties

									if ( is_array($schema_provider_isco08_array) ) {

										$schema_provider_isco08_array = array_filter($schema_provider_isco08_array);

									}

								// Sort the array

									if ( is_array($schema_provider_isco08_array) ) {

										ksort($schema_provider_isco08_array);

									}

								$schema_provider_isco08[] = $schema_provider_isco08_array;

							}

						}

				// occupationalCategory Value Array

					$schema_provider_occupationalCategory = array_merge(
						$schema_provider_isco08,
						array($schema_provider_onetsoc)
					);

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($schema_provider_occupationalCategory);

			// Wikidata

				// Wikidata Item URL for the Occupation

					$schema_provider_wikidata_occupation = get_field('clinical_specialization_wikidata_url_occupation', $provider_specialty_term);
					$schema_provider_wikidata_occupation = uamswp_attr_conversion($schema_provider_wikidata_occupation);

				// Wikidata Item URL for the Specialty / Field

					$schema_provider_wikidata_field = get_field('clinical_specialization_wikidata_url_field', $provider_specialty_term);
					$schema_provider_wikidata_field = uamswp_attr_conversion($schema_provider_wikidata_field);

		}

	// Provider Hospital Affiliation (hospitalAffiliation)

		// Get hospital affiliation multi-select field value

			$provider_hospitalAffiliation_multiselect = get_field( 'physician_affiliation', $page_id ) ?? '';

		// Add each hospital to the hospitalAffiliation property value list

			// Base array

				$provider_hospitalAffiliation = array();

			if ( $provider_hospitalAffiliation_multiselect ) {

				$provider_hospitalAffiliation = uamswp_fad_schema_hospital_affiliation(
					$provider_hospitalAffiliation_multiselect, // array // Required // Hospital affiliation ID values
					$page_url, // string // Required // Page URL
					1, // int // Optional // Nesting level within the main schema
					$provider_hospitalAffiliation // array // Optional // Pre-existing list array for hospitalAffiliation to which to add additional items
				);

			}

		// Define reference to each value/row in this property

			$schema_provider_hospitalAffiliation_ref = uamswp_fad_schema_node_references( $provider_hospitalAffiliation );

// Schema JSON Item Arrays

	// Provider as MedicalWebPage

		// Base array

			$schema_provider_MedicalWebPage = array();

		// primaryImageOfPage

			$schema_provider_MedicalWebPage['primaryImageOfPage'] = array(); // Defined later

		// significantLink

			if ( $schema_provider_significantLink ) {

				$schema_provider_MedicalWebPage['significantLink'] = $schema_provider_significantLink;

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_MedicalWebPage['significantLink']);

			}

		// sourceOrganization

			$schema_provider_MedicalWebPage['sourceOrganization'] = $schema_base_org_uams_health_ref;

		// url

			/*
			 * URL of the item.
			 * 
			 * Values expected to be one of these types:
			 * 
			 *     - URL
			 */

			$schema_provider_MedicalWebPage['url'] = array(
				'@id' => $page_url . '#URL',
				'url' => $page_url
			);

			// Define reference to this 'url' property

				$schema_provider_MedicalWebPage_url_ref = uamswp_fad_schema_node_references( $schema_provider_MedicalWebPage['url'] );

		// video

			if ( $video ) {

				$schema_provider_MedicalWebPage['video'] = $video;

			}

	// Provider as Physician and as Person

		$schema_provider_combined = uamswp_fad_schema_provider(
			array($page_id), // List of IDs of the provider items
			$page_url, // Page URL
			0, // Nesting level within the main schema
			1, // Iteration counter for provider-as-MedicalWebPage
			1, // Iteration counter for provider-as-MedicalBusiness
			1, // Iteration counter for provider-as-Person
			$provider_schema_fields // Pre-existing field values array so duplicate calls can be avoided
		);

// Add Provider Schema Arrays to Base Array

	// // Provider as MedicalWebPage (old)
	// $schema_provider['@graph'][] = $schema_provider_MedicalWebPage;

	// Provider as MedicalWebPage
	$schema_provider['@graph'][] = $schema_provider_combined['MedicalWebPage'];

	// Provider as MedicalBusiness
	$schema_provider['@graph'][] = $schema_provider_combined['MedicalBusiness'];

	// Provider as Person
	$schema_provider['@graph'][] = $schema_provider_combined['Person'];

// Construct the schema JSON script tag

	uamswp_fad_schema_construct($schema_provider);

// Display array as development testing

	echo '<pre>'; // test

	// Full
	echo print_r($schema_provider); // test

	// // UAMS
	// echo print_r($schema_provider['@graph'][0]); // test

	// // UAMS Health
	// echo print_r($schema_provider['@graph'][1]); // test

	// // UAMSHealth.com
	// echo print_r($schema_provider['@graph'][1]); // test

	// // MedicalWebPage
	// echo print_r($schema_provider['@graph'][3]); // test

		// // MedicalWebPage[mentions]
		// echo print_r($schema_provider['@graph'][3]['mentions']); // test

	// // BreadcrumbList
	// echo print_r($schema_provider['@graph'][4]); // test

	// // Physician
	// echo print_r($schema_provider['@graph'][5]); // test

		// // Physician
		// echo print_r($schema_provider['@graph'][5]['containedInPlace']); // test

		// // Physician
		// echo print_r($schema_provider['@graph'][5]['location']); // test

	// // Person
	// echo print_r($schema_provider['@graph'][6]); // test

	// // Specific variable
	// echo print_r($schema_provider_location_ref); // test

	echo '</pre>'; // test

// Reusable test display lines

	// echo '<p>$foo = ' . ( is_array($foo) ? 'Array' : ( is_object($foo) ? 'Object' : ( is_null($foo) ? 'Null' : ( $foo ) ) ) ) . '</p>'; // test
	// if ( is_array($foo) || is_object($foo) ) { echo '<pre>'; print_r($foo); echo '</pre>'; } // test
