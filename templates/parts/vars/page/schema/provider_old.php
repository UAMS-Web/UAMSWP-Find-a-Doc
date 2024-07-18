<?php

/*
 * Required vars:
 * 	$full_name_attr
 * 	$last_name_attr
 * 	$first_name_attr
 * 	$prefix_attr
 * 	$degree_array
 * 	$gender_attr
 * 	$excerpt_attr
 * 	$rating_valid
 * 	$accept_new
 * 	$review_count
 * 	$avg_rating
 * 	$comment_count
 * 	$accept_new
 * 	$video
 * 	$schema_provider_languages
 * 	$featured_image
 * 	$headshot_wide
 * 	$provider_associations_values
 * 	$provider_plural_name_attr
 * 	$conditions_cpt
 */

include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base_script.php' );

$schema_provider = $schema_common_base;

// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.
$page_id = get_the_ID();

// Get Values

	// Common property values

		include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

	// Provider URL

		$schema_provider_url = user_trailingslashit($page_url);

	// Provider archive URL

		$schema_provider_archive_url = user_trailingslashit( get_post_type_archive_link('provider') );

	// Significant links (significantLink)

		// Base array

			$schema_provider_significantLink = array();

	// Related ontology items as schema arrays

		// Related Locations (MedicalWebPage[mentions], IndividualPhysician[location], Person[workLocation])

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

					$node_identifier_list = $node_identifier_list ?? array(); // List of node identifiers (@id) already defined in the schema

					if ( function_exists('uamswp_fad_schema_location') ) {

						$provider_related_location = uamswp_fad_schema_location(
							$locations, // List of IDs of the location items
							$schema_provider_url, // Page URL
							$node_identifier_list // array // Optional // List of node identifiers (@id) already defined in the schema
						);

					} else {

						$provider_related_location = null;

					}

				}

			// Define reference to each value/row in this property

				$schema_provider_location_ref = uamswp_fad_schema_node_references( $provider_related_location );

			// Get URLs for significantLink property

				$schema_provider_significantLink = uamswp_fad_schema_property_values(
					$provider_related_location, // array // Required // Property values from which to extract specific values
					array( 'url' ), // mixed // Required // List of properties from which to collect values
					$schema_provider_significantLink // mixed // Optional // Pre-existing list to which to add additional items
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

				$node_identifier_list = $node_identifier_list ?? array(); // List of node identifiers (@id) already defined in the schema

				if ( function_exists('uamswp_fad_schema_expertise') ) {

					$provider_related_expertise = uamswp_fad_schema_expertise(
						$expertises, // List of IDs of the clinical resource items
						$page_url, // Page URL
						$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
						1 // Nesting level within the main schema
					);

				} else {

					$provider_related_expertise = null;

				}

			// Define reference to each value/row in this property

				$schema_provider_expertise_ref = uamswp_fad_schema_node_references( $provider_related_expertise );

			// Get URLs for significantLink property

				$schema_provider_significantLink = uamswp_fad_schema_property_values(
					$provider_related_expertise, // array // Required // Property values from which to extract specific values
					array( 'url' ), // mixed // Required // List of properties from which to collect values
					$schema_provider_significantLink // mixed // Optional // Pre-existing list to which to add additional items
				);

		// Related Clinical Resources

			// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.

				$clinical_resources = get_field( 'physician_clinical_resources', $page_id );

			// Define the schema for nesting in 'MedicalWebPage'['mentions']

				/*
					Nesting level 0 = 'MedicalWebPage'
					Nesting level 1 = 'MedicalWebPage'['mentions']
				*/

				$node_identifier_list = $node_identifier_list ?? array(); // List of node identifiers (@id) already defined in the schema

				$provider_related_clinical_resource = uamswp_fad_schema_clinical_resource(
					$clinical_resources, // List of IDs of the clinical resource items
					$page_url, // Page URL
					$node_identifier_list, // array // Optional // List of node identifiers (@id) already defined in the schema
					1 // Nesting level within the main schema
				);

			// Define reference to each value/row in this property

				$schema_provider_clinical_resource_ref = uamswp_fad_schema_node_references( $provider_related_clinical_resource );

			// Get URLs for significantLink property

				$schema_provider_significantLink = uamswp_fad_schema_property_values(
					$provider_related_clinical_resource, // array // Required // Property values from which to extract specific values
					array( 'url' ), // mixed // Required // List of properties from which to collect values
					$schema_provider_significantLink // mixed // Optional // Pre-existing list to which to add additional items
				);

		// Related Conditions and Treatments

			// Related Conditions

				// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.

					$conditions_cpt = get_field( 'physician_conditions_cpt', $page_id );

				// Define the schema for nesting in 'MedicalWebPage'['mentions']

					/*
						Nesting level 0 = 'MedicalWebPage'
						Nesting level 1 = 'MedicalWebPage'['mentions']
					*/

					$node_identifier_list = $node_identifier_list ?? array(); // List of node identifiers (@id) already defined in the schema

					$provider_related_condition = uamswp_fad_schema_condition(
						$conditions_cpt, // array // Required // List of IDs of the MedicalCondition items
						$schema_provider_url, // string // Required // Page URL
						$node_identifier_list // array // Optional // List of node identifiers (@id) already defined in the schema
					);

				// Define reference to each value/row in this property

					$provider_related_condition_ref = uamswp_fad_schema_node_references( $provider_related_condition );

			// Related Treatments

				// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.

					$treatments_cpt = get_field( 'physician_treatments_cpt', $page_id );

				// Define the schema for nesting in 'IndividualPhysician'['availableService']

					/*
						Nesting level 0 = 'IndividualPhysician'
						Nesting level 1 = 'IndividualPhysician'['availableService']
					*/

					$node_identifier_list = $node_identifier_list ?? array(); // List of node identifiers (@id) already defined in the schema

					if ( function_exists('uamswp_fad_schema_treatment') ) {

						$provider_related_treatment = uamswp_fad_schema_treatment(
							$treatments_cpt, // array // Required // List of IDs of the service items
							$schema_provider_url, // string // Required // Page URL
							$node_identifier_list // array // Optional // List of node identifiers (@id) already defined in the schema
						);

					} else {

						$provider_related_treatment = null;

					}

				// Define reference to each value/row in this property

					$schema_provider_treatment_ref = uamswp_fad_schema_node_references( $provider_related_treatment );

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
					1, // int // Optional // Nesting level within the main schema
					$provider_hospitalAffiliation // array // Optional // Pre-existing list array for hospitalAffiliation to which to add additional items
				);

			}

		// Define reference to each value/row in this property

			$schema_provider_hospitalAffiliation_ref = uamswp_fad_schema_node_references( $provider_hospitalAffiliation );

// Schema JSON Item Arrays

	// Provider as MedicalWebPage

		// Base array

			$schema_provider_MedicalWebPage = array(
				'@type' => 'MedicalWebPage'
			);

		// @id

			$schema_provider_MedicalWebPage['@id'] = $schema_provider_url . '#' . $schema_provider_MedicalWebPage['@type'];

			// Define reference to this 'MedicalWebPage' item

				$schema_provider_MedicalWebPage_ref['@id'] = $schema_provider_MedicalWebPage['@id'] ?: '';

		// name

			/*
			 * The name of the item.
			 *
			 * Subproperty of:
			 *
			 *     - rdfs:label
			 *
			 * Values expected to be one of these types:
			 *
			 *     - Text
			 */

			$schema_provider_MedicalWebPage['name'] = array(); // Defined later

		// headline

			$schema_provider_MedicalWebPage['headline'] = array(); // Defined later

		// about

			$schema_provider_MedicalWebPage['about'] = array(); // Defined later

		// breadcrumb

			$schema_provider_MedicalWebPage['breadcrumb'] = array(); // Defined after 'BreadcrumbList' item is defined

		// creator

			$schema_provider_MedicalWebPage['creator'] = $schema_base_org_uams_ref;

		// dateModified

			$schema_provider_MedicalWebPage['dateModified'] = get_the_modified_date( 'c', $page_id ); // ISO 8601 date format

		// datePublished

			$schema_provider_MedicalWebPage['datePublished'] = get_the_date( 'c', $page_id ); // ISO 8601 date format

		// description

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
			 *     - Thing
			 *
			 * Sub-properties:
			 *
			 *     - disambiguatingDescription
			 *     - interpretedAsClaim
			 *     - originalMediaContextDescription
			 *     - sha256
			 */

			$schema_provider_MedicalWebPage['description'] = array(); // Defined later

		// inLanguage

			$schema_provider_MedicalWebPage['inLanguage'] = $schema_base_website_uams_health_inLanguage_ref;

		// isPartOf

			$schema_provider_MedicalWebPage['isPartOf'] = $schema_base_website_uams_health_ref;

		// mainEntity

			$schema_provider_MedicalWebPage['mainEntity'] = array(); // Defined later

		// maintainer

			$schema_provider_MedicalWebPage['maintainer'] = $schema_base_org_uams_ref;

		// medicalAudience

			$schema_provider_MedicalWebPage['medicalAudience'] = $schema_common_medicalAudience;

		// mentions

			// Base array

				$schema_provider_MedicalWebPage['mentions'] = array();

			// Related Locations

				$schema_provider_MedicalWebPage['mentions'] = array_merge(
					$schema_provider_MedicalWebPage['mentions'],
					( array_is_list($schema_provider_location_ref) ? $schema_provider_location_ref : array($schema_provider_location_ref) )
				);

			// Related Areas of Expertise

				$schema_provider_MedicalWebPage['mentions'] = array_merge(
					$schema_provider_MedicalWebPage['mentions'],
					( array_is_list($provider_related_expertise) ? $provider_related_expertise : array($provider_related_expertise) )
				);

			// Related Clinical Resources

				$schema_provider_MedicalWebPage['mentions'] = array_merge(
					$schema_provider_MedicalWebPage['mentions'],
					( array_is_list($provider_related_clinical_resource) ? $provider_related_clinical_resource : array($provider_related_clinical_resource) )
				);

			// Related Conditions

				$schema_provider_MedicalWebPage['mentions'] = array_merge(
					$schema_provider_MedicalWebPage['mentions'],
					( array_is_list($provider_related_condition) ? $provider_related_condition : array($provider_related_condition) )
				);

			// Related Treatments

				$schema_provider_MedicalWebPage['mentions'] = array_merge(
					$schema_provider_MedicalWebPage['mentions'],
					( array_is_list($schema_provider_treatment_ref) ? $schema_provider_treatment_ref : array($schema_provider_treatment_ref) )
				);

			// Remove any empty items from the array

				$schema_provider_MedicalWebPage['mentions'] = array_filter($schema_provider_MedicalWebPage['mentions']);

				if ( empty($schema_provider_MedicalWebPage['mentions']) ) {

					$schema_provider_MedicalWebPage['mentions'];

				}

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
				'@id' => $schema_provider_url . '#URL',
				'url' => $schema_provider_url
			);

			// Define reference to this 'url' property

				$schema_provider_MedicalWebPage_url_ref['@id'] = $schema_provider_MedicalWebPage['url']['@id'] ?: '';

		// video

			if ( $video ) {

				$schema_provider_MedicalWebPage['video'] = $video;

			}

	// BreadcrumbList

		$schema_provider_BreadcrumbList = array(
			'@type' => 'BreadcrumbList'
		);

		$schema_provider_BreadcrumbList['@id'] = $schema_provider_url . '#' . $schema_provider_BreadcrumbList['@type'];

		$schema_provider_BreadcrumbList['itemListElement'] = array(
			array(
				'@type' => 'ListItem',
				'position' => 1,
				'item' => array(
					'@type' => 'WebPage',
					'@id' => $schema_base_org_uams_health_url . '#ListItem',
					'url' => $schema_base_org_uams_health_url,
					'name' => 'UAMS Health'
				)
			),
			array(
				'@type' => 'ListItem',
				'position' => 2,
				'item' => array(
					'@type' => 'WebPage',
					'@id' => $schema_provider_archive_url . '#ListItem',
					'url' => $schema_provider_archive_url,
					'name' => $provider_plural_name_attr
				)
				),
			array(
				'@type' => 'ListItem',
				'position' => 3,
				'item' => $schema_provider_MedicalWebPage_ref
			)
		);

		// Define 'BreadcrumbList' reference

			$schema_provider_BreadcrumbList_ref['@id'] = $schema_provider_BreadcrumbList['@id'] ?: '';

		// Set value of 'breadcrumb' property of 'MedicalWebPage' item with 'BreadcrumbList' reference

			$schema_provider_MedicalWebPage['breadcrumb'] = $schema_provider_BreadcrumbList_ref ?: '';

	// Provider as IndividualPhysician

		// Base array

			$schema_provider_IndividualPhysician = array(
				'@type' => 'IndividualPhysician'
			);

		// @id

			$schema_provider_IndividualPhysician['@id'] = $schema_provider_url . '#' . $schema_provider_IndividualPhysician['@type'];

			// Define reference to this 'IndividualPhysician' item

				$schema_provider_IndividualPhysician_ref['@id'] = $schema_provider_IndividualPhysician['@id'] ?: '';

			// Define a value of 'about' of 'MedicalWebPage' with this 'IndividualPhysician' reference

				$schema_provider_MedicalWebPage['about'][] = $schema_provider_IndividualPhysician_ref;

			// Define a value of 'mentions' of 'MedicalWebPage' with this 'IndividualPhysician' reference

				$schema_provider_MedicalWebPage['mentions'][] = $schema_provider_IndividualPhysician_ref;

			// Define the value of 'mainEntity' of 'MedicalWebPage' with this 'IndividualPhysician' reference

				$schema_provider_MedicalWebPage['mainEntity'] = $schema_provider_IndividualPhysician_ref;

		// name

			/*
			 * The name of the item.
			 *
			 * Subproperty of:
			 *
			 *     - rdfs:label
			 *
			 * Values expected to be one of these types:
			 *
			 *     - Text
			 */

			$schema_provider_IndividualPhysician['name'] = array(); // Defined later

		// aggregateRating

			if ($rating_valid) {

				$schema_provider_IndividualPhysician['aggregateRating'] = array_filter(
					array(
						'@type' => 'AggregateRating',
						'description' => '', // Get description of the rating/review concept from Patient Experience.
						'itemReviewed' => $schema_provider_IndividualPhysician_ref,
						'ratingCount' => $review_count,
						'ratingValue' => $avg_rating,
						'reviewAspect' => '', // Get info from Patient Experience about which facets of the provider is rated/reviewed.
						'reviewCount' => $comment_count
					)
				);

			}

		// availableService

			$schema_provider_IndividualPhysician['availableService'] = $provider_related_treatment;

		// brand

			// Base array

				$schema_provider_brand = array();

			// Add relevant organizations

				// Add UAMS Health

					$schema_provider_brand[] = $schema_base_org_uams_health_ref;

				// Add additional Organizations if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)

			// Add to schema

				if ( $schema_provider_brand ) {

					$schema_provider_IndividualPhysician['brand'] = $schema_provider_brand;

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($schema_provider_IndividualPhysician['brand']);

				}

		// containedInPlace

			$schema_provider_IndividualPhysician['containedInPlace'] = $provider_related_location;

		// description

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
			 *     - Thing
			 *
			 * Sub-properties:
			 *
			 *     - disambiguatingDescription
			 *     - interpretedAsClaim
			 *     - originalMediaContextDescription
			 *     - sha256
			 */

			$schema_provider_IndividualPhysician['description'] = array(); // Defined later

		// hospitalAffiliation

			$schema_provider_IndividualPhysician['hospitalAffiliation'] = $provider_hospitalAffiliation;

		// isAcceptingNewPatients

			$schema_provider_IndividualPhysician['isAcceptingNewPatients'] = $accept_new ? 'True' : 'False';

		// location

			if (
				isset($schema_provider_location_ref)
				&&
				!empty($schema_provider_location_ref)
			) {

				$schema_provider_IndividualPhysician['location'] = $schema_provider_location_ref;

			}

		// mainEntityOfPage

			/**
			 * Indicates a page (or other CreativeWork) for which this thing is the main
			 * entity being described. See background notes for details.
			 *
			 * Inverse property:
			 *
			 *      - mainEntity
			 *
			 * Values expected to be one of these types:
			 *
			 *      - CreativeWork
			 *      - URL
			 *
			 * Used on these types:
			 *
			 *      - Thing
			 */

			$schema_provider_IndividualPhysician['mainEntityOfPage'] = $schema_provider_MedicalWebPage_ref;

		// medicalSpecialty

			$schema_provider_IndividualPhysician['medicalSpecialty'] = $schema_provider_medicalSpecialty;

		// parentOrganization

			if ( $schema_provider_brand ) {

				$schema_provider_IndividualPhysician['parentOrganization'] = $schema_provider_brand;

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_IndividualPhysician['parentOrganization']);

			}

		// subjectOf

			/**
			 * A CreativeWork or Event about this Thing.
			 *
			 * Inverse-property: about
			 *
			 * Values expected to be one of these types:
			 *
			 *      - CreativeWork
			 *      - Event
			 *
			 * Used on these types:
			 *
			 *      - Thing
			 */

			$schema_provider_IndividualPhysician['subjectOf'] = $schema_provider_MedicalWebPage_ref;

		// url

			/*
			 * URL of the item.
			 *
			 * Values expected to be one of these types:
			 *
			 *     - URL
			 */

			$schema_provider_IndividualPhysician['url'] = $schema_provider_MedicalWebPage_url_ref;

	// Provider as Person

		// Base array

			$schema_provider_Person = array(
				'@type' => 'Person'
			);

		// additionalType

			/*
			 * An additional type for the item, typically used for adding more specific types
			 * from external vocabularies in microdata syntax. This is a relationship between
			 * something and a class that the thing is in. Typically the value is a
			 * URI-identified RDF class, and in this case corresponds to the use of rdf:type
			 * in RDF. Text values can be used sparingly, for cases where useful information
			 * can be added without their being an appropriate schema to reference. In the
			 * case of text values, the class label should follow the schema.org style guide.
			 *
			 * Subproperty of:
			 *     - rdf:type
			 *
			 * Values expected to be one of these types:
			 *
			 *     - Text
			 *     - URL
			 *
			 * Used on these types:
			 *
			 *     - Thing
			 */

			$schema_provider_Person_additionalType_fallback = 'https://www.wikidata.org/wiki/Q11974939'; // Wikidata item URL for health professional (Q11974939)

			if (
				isset($schema_provider_wikidata_occupation)
				&&
				!empty($schema_provider_wikidata_occupation)
			) {

				$schema_provider_Person['additionalType'] = $schema_provider_wikidata_occupation; // Get Wikidata item URL for occupation from Specialty

			} else {

				$schema_provider_Person['additionalType'] = $schema_provider_Person_additionalType_fallback;

			}

		// @id

			$schema_provider_Person['@id'] = $schema_provider_url . '#' . $schema_provider_Person['@type'];

			// Define reference to this 'Person' item

				$schema_provider_Person_ref['@id'] = $schema_provider_Person['@id'] ?: '';

			// Define a value of 'about' of 'MedicalWebPage' with this 'Person' reference

				$schema_provider_MedicalWebPage['about'][] = $schema_provider_Person_ref;

			// Define a value of 'mentions' of 'MedicalWebPage' with this 'Person' reference

				$schema_provider_MedicalWebPage['mentions'][] = $schema_provider_Person_ref;

		// name

			/*
			 * The name of the item.
			 *
			 * Subproperty of:
			 *
			 *     - rdfs:label
			 *
			 * Values expected to be one of these types:
			 *
			 *     - Text
			 */

			$schema_provider_Person['name'] = array(
				'@id' => $schema_provider_url . '#Name',
				'@type' => 'Name',
				'name' => $full_name_attr, // Replace 'foo' with long provider name (e.g., "Leonard H. McCoy Jr., M.D.")
			);

			// Define reference to this 'name' property

				$schema_provider_Person_name_ref['@id'] = $schema_provider_Person['name']['@id'] ?: '';

			// Define value of other 'name' properties with this 'name' reference

				// MedicalWebPage

					$schema_provider_MedicalWebPage['name'] = $schema_provider_Person_name_ref;

				// IndividualPhysician

					$schema_provider_IndividualPhysician['name'] = $schema_provider_Person_name_ref;

			// Define value of 'headline' of 'MedicalWebPage' with this 'name' reference

				$schema_provider_MedicalWebPage['headline'] = $schema_provider_Person_name_ref;

		// affiliation

			// Base array

				$provider_affiliation = array();

			// Brand organizations

				if ( $schema_provider_brand ) {

					$schema_provider_brand = array_is_list($schema_provider_brand) ? $schema_provider_brand : array($schema_provider_brand);

					$provider_affiliation = array_merge(
						$provider_affiliation,
						$schema_provider_brand
					);

				}

			// Related hospitals

				if ( $schema_provider_hospitalAffiliation_ref ) {

					$schema_provider_hospitalAffiliation_ref = array_is_list($schema_provider_hospitalAffiliation_ref) ? $schema_provider_hospitalAffiliation_ref : array($schema_provider_hospitalAffiliation_ref);

					$provider_affiliation = array_merge(
						$provider_affiliation,
						$schema_provider_hospitalAffiliation_ref
					);

				}

			// Clean up list array

				if ( $provider_affiliation ) {

					$provider_affiliation = array_filter($provider_affiliation);
					$provider_affiliation = array_unique( $provider_affiliation, SORT_REGULAR );

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($provider_affiliation);

				}

			// Add to schema

				if ( $provider_affiliation ) {

					$schema_provider_Person['affiliation'] = $provider_affiliation;

				}

		// alumniOf

			// Get list of education and training organizations

				$provider_education_organizations = array();
				$schema_provider_alumniOf = array();

				if (
					isset($education)
					&&
					!empty($education)
					&&
					is_array($education)
				) {

					foreach ( $education as $item ) {

						$provider_education_organizations[] = get_term( $item['school'], 'school')->name;

					}

					// Remove empty items

						if ( is_array($provider_education_organizations) ) {

							$provider_education_organizations = array_filter($provider_education_organizations);

						}

					// Remove duplicate items

						if ( is_array($provider_education_organizations) ) {

							$provider_education_organizations = array_unique($provider_education_organizations);

						}

					// Sort array

						if ( is_array($provider_education_organizations) ) {

							sort($provider_education_organizations);

						}

				}

			// Build alumniOf value

				if ( !empty($provider_education_organizations) ) {

					foreach ( $provider_education_organizations as $item ) {

						$schema_provider_alumniOf[] = array(
							'@type' => 'EducationalOrganization',
							'name' => $item
						);

					}

				}

			// Add to the schema

				if ( !empty($schema_provider_alumniOf) ) {

					$schema_provider_Person['alumniOf'] = $schema_provider_alumniOf;

					// If there is only one item, flatten the multi-dimensional array by one step

						uamswp_fad_flatten_multidimensional_array($schema_provider_Person['alumniOf']);

				}

		// brand

			if ( $schema_provider_brand ) {

				$schema_provider_Person['brand'] = $schema_provider_brand;

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_Person['brand']);

			}

		// description

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
			 *     - Thing
			 *
			 * Sub-properties:
			 *
			 *     - disambiguatingDescription
			 *     - interpretedAsClaim
			 *     - originalMediaContextDescription
			 *     - sha256
			 */

			$schema_provider_Person['description'] = array(
				'@id' => $schema_provider_url . '#Description',
				'@type' => 'Text',
				'description' => $excerpt_attr
			);

			// Define reference to this 'description' property

				$schema_provider_Person_description_ref['@id'] = $schema_provider_Person['description']['@id'] ?: '';

			// Define value of 'description' of 'IndividualPhysician' with this 'description' reference

				$schema_provider_IndividualPhysician['description'] = $schema_provider_Person_description_ref;

			// Define value of 'description' of 'MedicalWebPage' with this 'description' reference

				$schema_provider_MedicalWebPage['description'] = $schema_provider_Person_description_ref;

		// familyName

			$schema_provider_Person['familyName'] = $last_name_attr;

		// gender

			// Capitalize first letter of value

				$schema_provider_gender = ucfirst($gender_attr);

			// Use GenderType if Male/Female

				if (
					$schema_provider_gender == 'Female'
					||
					$schema_provider_gender == 'Male'
				) {

					$schema_provider_gender = array(
						'@type' => 'GenderType',
						'valueReference' => $schema_provider_gender
					);

				}

			// Add to schema

				$schema_provider_Person['gender'] = $schema_provider_gender;

		// givenName

			$schema_provider_Person['givenName'] = $first_name_attr;

		// hasCredential

			// Format values

				$provider_hasCredential = array();

				if ( $degrees ) {

					$provider_hasCredential = uamswp_fad_schema_hascredential(
						$degrees, // mixed // Required // ID values for degrees/credentials or certifications
						'degree' // string // Required // Slug of relevant taxonomy (enum: 'degree', 'board')
					);

				}

			// Add to schema

				if ( $provider_hasCredential ) {

					$schema_provider_Person['hasCredential'] = $provider_hasCredential;

				}

		// hasOccupation

			if (
				isset($provider_specialty)
				&&
				!empty($provider_specialty)
			) {

				$schema_provider_Person['hasOccupation'] = uamswp_fad_schema_hasoccupation_id(
					$provider_specialty // mixed // Required // Clinical Specialization ID values
				);

			}

		// honorificPrefix

			if ( $prefix_attr ) {

				$schema_provider_Person['honorificPrefix'] = $prefix_attr;

			}

		// honorificSuffix

			if ( $degree_attr_array ) {

				$schema_provider_Person['honorificSuffix'] = $degree_attr_array;

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_Person['honorificSuffix']);

			}

		// identifier

			/*
			 * The identifier property represents any kind of identifier for any kind of
			 * Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated
			 * properties for representing many of these, either as textual strings or as
			 * URL (URI) links. See background notes at
			 * https://schema.org/docs/datamodel.html#mainEntityBackground for details.
			 *
			 * Values expected to be one of these types:
			 *
			 *     - PropertyValue
			 *     - Text
			 *     - URL
			 */

			$schema_provider_Person['identifier'] = array();

			// National Provider Identifier (NPI)

				if ( $npi ) {

					$schema_provider_Person['identifier'][] = array(
						'@type' => 'PropertyValue',
						'name' => 'National Provider Identifier',
						'propertyID' => 'https://www.wikidata.org/wiki/Q6975101', // Wikidata item page for National Provider Identifier
						'url' => 'https://npiregistry.cms.hhs.gov/provider-view/' . $npi, // Provider information on NPPES NPI Registry
						'value' => $npi // NPI value
					);

				}

			if ( !empty($schema_provider_Person['identifier']) ) {

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_Person['identifier']);

			} else {

				// If there are no items, remove the property from the schema

					unset($schema_provider_Person['identifier']);

			}

		// image

			/*
			 * An image of the item. This can be a URL or a fully described ImageObject.
			 *
			 * Values expected to be one of these types:
			 *
			 *     - ImageObject
			 *     - URL
			 */

			// Get portrait values
				$provider_portrait = uamswp_fad_schema_imageobject_thumbnails(
					$schema_provider_url, // URL of entity with which the image is associated
					0, // Nesting level within the main schema
					'3:4', // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9')
					'Portrait', // Base fragment identifier
					$featured_image ?: 0, // ID of image to use for 1:1 aspect ratio
					$featured_image ?: 0, // ID of image to use for 3:4 aspect ratio
					$headshot_wide ?: 0, // ID of image to use for 4:3 aspect ratio
					$headshot_wide ?: 0, // ID of image to use for 16:9 aspect ratio
					0 // ID of image to use for full image
				) ?? array();

			// Add to schema

				if ( $provider_portrait ) {

					$schema_provider_Person['image'] = $provider_portrait;

					// Define reference to each value/row in this property

						$schema_provider_Person_image_ref = uamswp_fad_schema_node_references( $provider_portrait );

					// Define value of 'primaryImageOfPage' of 'MedicalWebPage' with this series of 'image' references

						$schema_provider_MedicalWebPage['primaryImageOfPage'] = $schema_provider_Person_image_ref;

				}

		// jobTitle

			if ( $provider_occupation_title_attr ) {

				$schema_provider_Person['jobTitle'] = $provider_occupation_title_attr;

			}

		// knowsLanguage

			// Base array

				$schema_provider_Person['knowsLanguage'] = array();

			// Eliminate PHP errors

				$schema_provider_language = '';
				$schema_provider_language_alternateName = '';

			if ( $schema_provider_languages ) {

				// Add each language to the schema

					foreach ( $schema_provider_languages as $key => $value ) {

						$schema_provider_language = array( // Repeat as necessary
							'@type' => 'Language',
							'name' => $key,
						);

						if ( !empty($schema_provider_languages[$key]['alternateName']) ) {

							// If there is only one item, flatten the multi-dimensional array by one step

								$schema_provider_language_alternateName = $schema_provider_languages[$key]['alternateName'];
								uamswp_fad_flatten_multidimensional_array($schema_provider_language_alternateName);

							$schema_provider_language['alternateName'] = $schema_provider_language_alternateName;

						}

						if ( $schema_provider_language ) {

							$schema_provider_Person['knowsLanguage'][] = $schema_provider_language;

						}

					}

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_Person['knowsLanguage']);

			}

			if ( !empty($schema_provider_Person['knowsLanguage']) ) {

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_Person['knowsLanguage']);

			} else {

				// If there are no items, remove the property from the schema

					unset($schema_provider_Person['knowsLanguage']);

			}

		// memberOf

			if ( $provider_schema_fields['provider_memberOf'] ) {

				$schema_provider_Person['memberOf'] = $provider_schema_fields['provider_memberOf'];

			}

		// sameAs

			/*
			 * URL of a reference Web page that unambiguously indicates the item's identity
			 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
			 * website).
			 *
			 * Values expected to be one of these types:
			 *
			 *     - URL
			 */

			// Base array

				$schema_provider_Person['sameAs'] = array();

			// NPPES NPI Registry

				if ( $npi ) {

					$schema_provider_Person['sameAs'][] = 'https://npiregistry.cms.hhs.gov/provider-view/' . $npi;

				}

			if ( !empty($schema_provider_Person['sameAs']) ) {

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_Person['sameAs']);

			} else {

				// If there are no items, remove the property from the schema

					unset($schema_provider_Person['sameAs']);

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

			if ( $schema_provider_MedicalWebPage_ref ) {

				$schema_provider_Person['subjectOf'] = $schema_provider_MedicalWebPage_ref;

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_Person['subjectOf']);

			}

		// url

			/*
			 * URL of the item.
			 *
			 * Values expected to be one of these types:
			 *
			 *     - URL
			 */

			if ( $schema_provider_MedicalWebPage_url_ref ) {

				$schema_provider_Person['url'] = $schema_provider_MedicalWebPage_url_ref;

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_Person['url']);

			}

		// workLocation

			if ( $schema_provider_location_ref ) {

				$schema_provider_Person['workLocation'] = $schema_provider_location_ref;

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_Person['workLocation']);

			}

		// worksFor

			if ( $schema_provider_brand ) {

				$schema_provider_Person['worksFor'] = $schema_provider_brand;

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($schema_provider_Person['worksFor']);

			}

// Add Provider Schema Arrays to Base Array

	// Provider as MedicalWebPage
	$schema_provider['@graph'][] = $schema_provider_MedicalWebPage;

	// BreadcrumbList
	$schema_provider['@graph'][] = $schema_provider_BreadcrumbList;

	// Provider as IndividualPhysician
	$schema_provider['@graph'][] = $schema_provider_IndividualPhysician;

	// Provider as Person
	$schema_provider['@graph'][] = $schema_provider_Person;

// Construct the schema JSON script tag

	uamswp_fad_schema_construct($schema_provider);