<?php
/*
 * Template Name: Combined Condition and Treatment List Section
 *
 * Description: A template part that displays a list of conditions and treatments
 * associated with the current page.
 *
 * When this template part is needed for a hook, use the
 * uamswp_fad_section_condition_treatment() function.
 *
 * Required vars:
 * 	$page_id // int // ID of the current page
 * 	$conditions_cpt // int[] // Value of the related conditions input (or $condition_descendants, List of this condition item's descendant items)
 * 	$treatments_cpt // int[] // Value of the related treatments input (or $treatment_descendants, List of this treatment item's descendant items)
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 * 	$hide_medical_ontology // bool // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
 *
 * Optional vars:
 * 	$schema_medical_specialty // array // MedicalSpecialty Schema data
 * 	$condition_treatment_section_show // bool
 * 	$condition_section_show // bool
 * 	$treatment_section_show // bool
 * 	$ontology_type // bool (default: true) // Query for whether item is ontology type vs. content type
 * 	$condition_treatment_section_title // string (default: Find-a-Doc Settings value for combined condition/treatment section title in a general placement) // Text to use for the section title
 * 	$condition_treatment_section_intro // string (default: Find-a-Doc Settings value for combined condition/treatment section intro text in a general placement) // Text to use for the section intro text
 * 	$condition_section_title // string (default: Find-a-Doc Settings value for areas of condition section title in a general placement) // Text to use for the conditions subsection title
 * 	$condition_section_intro // string (default: Find-a-Doc Settings value for condition section intro text in a general placement) // Text to use for the conditions subsection intro text
 * 	$treatment_section_title // string (default: Find-a-Doc Settings value for treatment section title in a general placement) // Text to use for the treatments subsection title
 * 	$treatment_section_intro // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement) // Text to use for the treatments subsection intro text
 * 	$condition_treatment_section_link_item // bool (default: false) // Query for whether to link the list items
 * 	$condition_treatment_section_show_header // bool (default: true) // Query for whether to display the section header
 * 	$condition_section_show_header // bool (default: true) // Query for whether to display the conditions subsection header
 * 	$treatment_section_show_header // bool (default: true) // Query for whether to display the treatments subsection header
 * 	$condition_treatment_section_collapse_list // bool (default: false) // Query for whether to collapse the list of conditions and treatments
 * 	$condition_treatment_section_class // string (default: 'conditions-treatments') // Section class
 * 	$condition_treatment_section_id // string (default: 'conditions-treatments') // Section ID
 * 	$condition_section_class // string (default: 'conditions') // Conditions subsection class
 * 	$condition_section_id // string (default: 'conditions') // Conditions subsection ID
 * 	$treatment_section_class // string (default: 'treatments') // Treatments subsection class
 * 	$treatment_section_id // string (default: 'treatments') // Treatments subsection ID
 * 	$condition_single_name_attr // string
 * 	$treatment_single_name_attr // string
 * 	$condition_treatment_fpage_title_general // string
 * 	$condition_treatment_fpage_intro_general // string
 * 	$condition_fpage_title_general // string
 * 	$condition_fpage_intro_general // string
 * 	$treatment_fpage_title_general // string
 * 	$treatment_fpage_intro_general // string
 * 	$condition_cpt_query // WP_Post[]
 * 	$condition_count // int
 * 	$treatment_cpt_query // WP_Post[]
 * 	$treatment_count // int
 *
 * Return:
 * 	var $schema_medical_specialty; // array
 * 	html <section />
 */

// Check/define variables

	// Query for whether to show the combined conditions and treatments section
	$condition_treatment_section_show = isset($condition_treatment_section_show) ? $condition_treatment_section_show : false;

	// Query for whether to show the condition section
	$condition_section_show = isset($condition_section_show) ? $condition_section_show : false;

	// Query for whether to show the treatment section
	$treatment_section_show = isset($treatment_section_show) ? $treatment_section_show : false;

	// Query for whether to link the list items
	$condition_treatment_section_link_item = isset($condition_treatment_section_link_item) ? $condition_treatment_section_link_item : false;

	// Other variables
	$hide_medical_ontology = isset($hide_medical_ontology) ? $hide_medical_ontology : false;

	// Revisit query for whether to show the combined conditions and treatments section
	$condition_treatment_section_show = ( $condition_treatment_section_show && ( $condition_section_show || $treatment_section_show ) ) ? $condition_treatment_section_show : false;
	if (
		$condition_treatment_section_show
		&&
		$condition_treatment_section_link_item
		&&
		$hide_medical_ontology
		) {

		// If the combined conditions and treatments section should be shown...
		// and if the condition items should be linked...
		// and if certain ontology items should be hidden on this page...

		// Set the combined conditions and treatments section to not be shown on this page
		$condition_treatment_section_show = false;

	}

// Do something
if ( $condition_treatment_section_show ) {

	// Check/define variables

		// Query for whether item is ontology type vs. content type
		$ontology_type = isset($ontology_type) ? $ontology_type : true;

		// Conditions subsection class
		$condition_section_class = isset($condition_section_class) ? $condition_section_class : 'conditions';

		// Treatments subsection class
		$treatment_section_class = isset($treatment_section_class) ? $treatment_section_class : 'treatments';

		// Conditions subsection

			if (
				!isset($condition_section_title) || empty($condition_section_title)
				||
				!isset($condition_section_intro) || empty($condition_section_intro)
			) {

				// Get the system settings for general placement of condition item text elements
				include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition.php' );

			}

			if ( !isset($condition_section_title) || empty($condition_section_title) ) {

				$condition_section_title = $condition_fpage_title_general;

			}

			if ( !isset($condition_section_intro) || empty($condition_section_intro) ) {

				$condition_section_intro = $condition_fpage_intro_general;

			}

		// Treatments subsection

			if (
				!isset($treatment_section_title) || empty($treatment_section_title)
				||
				!isset($treatment_section_intro) || empty($treatment_section_intro)
			) {

				// Get the system settings for general placement of treatment item text elements
				include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/treatment.php' );

			}

			if ( !isset($treatment_section_title) || empty($treatment_section_title) ) {

				$treatment_section_title = $treatment_fpage_title_general;

			}

			if ( !isset($treatment_section_intro) || empty($treatment_section_intro) ) {

				$treatment_section_intro = $treatment_fpage_intro_general;

			}

		// Text to use for the section title and the section intro text

			if ( $condition_section_show && $treatment_section_show ) {

				// If both the conditions section and the treatments section are shown...
				// Set the section title and section intro normally

					if (
						!isset($condition_treatment_section_title) || empty($condition_treatment_section_title)
						||
						!isset($condition_treatment_section_intro) || empty($condition_treatment_section_intro)
					) {

						// Get the system settings for general placement of combined condition and treatment item text elements
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition-treatment.php' );

					}

					if ( !isset($condition_treatment_section_title) || empty($condition_treatment_section_title) ) {

						$condition_treatment_section_title = $condition_treatment_fpage_title_general;

					}

					if ( !isset($condition_treatment_section_intro) || empty($condition_treatment_section_intro) ) {

						$condition_treatment_section_intro = $condition_treatment_fpage_intro_general;

					}


				// Add a split class to the item containers
				$condition_treatment_section_split_class = 'col-md-6';

				// Conditions subsection class
				$condition_section_class = $condition_treatment_section_split_class . ' ' . $condition_section_class;

				// Treatments subsection class
				$treatment_section_class = $condition_treatment_section_split_class . ' ' . $treatment_section_class;

			} elseif ( $condition_section_show && !$treatment_section_show ) {

				// If only the conditions section is shown...

				// Set the section title and section intro using the conditions values
				$condition_treatment_section_title = $condition_section_title;
				$condition_treatment_section_intro = $condition_section_intro;

			} elseif ( !$condition_section_show && $treatment_section_show ) {

				// If only the treatments section is shown...

				// Set the section title and section intro using the treatments values
				$condition_treatment_section_title = $treatment_section_title;
				$condition_treatment_section_intro = $treatment_section_intro;

			}

		// Query for whether to display the section header
		$condition_treatment_section_show_header = isset($condition_treatment_section_show_header) ? $condition_treatment_section_show_header : true;

		// Query for whether to display the conditions subsection header
		$condition_section_show_header = isset($condition_section_show_header) ? $condition_section_show_header : true;

		// Query for whether to display the treatments subsection header
		$treatment_section_show_header = isset($treatment_section_show_header) ? $treatment_section_show_header : true;

		// Query for whether to collapse the list of conditions and treatments
		$condition_treatment_section_collapse_list = isset($condition_treatment_section_collapse_list) ? $condition_treatment_section_collapse_list : false;

		// Section class
		$condition_treatment_section_class = isset($condition_treatment_section_class) ? $condition_treatment_section_class : 'conditions-treatments';

		// Section ID
		$condition_treatment_section_id = isset($condition_treatment_section_id) ? $condition_treatment_section_id : 'conditions-treatments';

		// Conditions subsection ID
		$condition_section_id = isset($condition_section_id) ? $condition_section_id : 'conditions';

		// Treatments subsection ID
		$treatment_section_id = isset($treatment_section_id) ? $treatment_section_id : 'treatments';

		// Get system settings for condition labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition.php' );

		// Get system settings for treatment labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/treatment.php' );

		// Other variables

			// Get the system settings for general placement of condition item text elements
			include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition.php' );

			// Related Conditions Section Query
			include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/condition.php' );

			// Related Treatments Section Query
			include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/treatment.php' );

	?>
	<section class="uams-module<?php echo $condition_treatment_section_class ? ' ' . $condition_treatment_section_class : ''; ?> bg-auto<?php echo $condition_treatment_section_collapse_list ? ' collapse-list' : ''; ?>"<?php echo $condition_treatment_section_id ? ' id="' . $condition_treatment_section_id . '" aria-labelledby="' . $condition_treatment_section_id . '-title"' : ''; ?>>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title<?php echo !$condition_treatment_section_show_header ? ' sr-only' : ''; ?>"<?php echo $condition_treatment_section_id ? ' id="' . $condition_treatment_section_id . '-title"' : ''; ?>><span class="title"><?php echo $condition_treatment_section_title; ?></span></h2>
					<?php if ( $condition_treatment_section_intro ) { ?>
						<p class="note<?php echo !$condition_treatment_section_show_header ? ' sr-only' : ''; ?>"><?php echo $condition_treatment_section_intro; ?></p>
					<?php } // endif ( $condition_treatment_section_intro ) ?>
					<div class="row">
						<?php

						// Begin Conditions Section
						if ( $condition_section_show ) { ?>
							<div class="col-12<?php echo $condition_section_class ? ' ' . $condition_section_class : '' ?> list-container list-container-rows"<?php echo $condition_section_id ? ' id="' . $condition_section_id . '"' : '' ?>>
								<?php

								// Begin Conditions Section Heading
								// Add condition section heading only if treatment section is also shown
								if ( $treatment_section_show ) { ?>
									<h3 class="module-inner-title<?php echo !$condition_section_show_header ? ' sr-only' : ''; ?>"<?php echo $condition_section_id ? ' id="' . $condition_section_id . '-title"' : ''; ?>><span class="title"><?php echo $condition_section_title; ?></span></h3>
									<?php if ( $condition_section_intro ) { ?>
										<p class="note<?php echo !$condition_section_show_header ? ' sr-only' : ''; ?>"><?php echo $condition_section_intro; ?></p>
									<?php } // endif ( $condition_section_intro )
								} // endif ( $treatment_section_show )
								// End Conditions Section Heading

								// Begin Conditions Section Content ?>
								<ul class="list">
									<?php

									if ( $condition_count > 0 ) {

										while ( $condition_cpt_query->have_posts() ) {
											$condition_cpt_query->the_post();
											$page_id = get_the_ID();
											$condition_title = get_the_title($page_id);
											$condition_title_attr = uamswp_attr_conversion($condition_title);
											if ( $condition_treatment_section_link_item ) {
												$condition_url = user_trailingslashit(get_the_permalink($page_id));
												$condition_aria_label = 'Go to ' . $condition_single_name_attr . ' page for ' . $condition_title_attr;
											} else {
												$condition_url = '';
												$condition_aria_label = '';
											}

											// MedicalSpecialty Schema Data

												// Check/define the main medicalSpecialty schema array
												$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();

												// Add this location's details to the main medicalSpecialty schema array
												$schema_medical_specialty = uamswp_fad_schema_medicalSpecialty_old(
													$schema_medical_specialty, // array // Optional // Main medicalSpecialty schema array
													$condition_title_attr, // string // Optional // The name of the item.
													$condition_url // string // Optional // URL of the item.
												);

											?>
											<li>
												<?php
												if ( $condition_treatment_section_link_item ) { ?>
													<a href="<?php echo $condition_url; ?>" aria-label="<?php echo $condition_aria_label; ?>" class="btn btn-outline-primary">
														<?php echo $condition_title; ?>
													</a>
												<?php
												} else {
													echo '<span class="item">' . $condition_title . '</span>';
												} // endif ( $condition_treatment_section_link_item )
												?>
											</li>
											<?php

										} // endwhile ( $condition_cpt_query->have_posts() )

									} // endif ( $condition_count > 0 )

									wp_reset_postdata();

									?>
								</ul>
								<?php
								// End Conditions Section Content

								?>
							</div>
						<?php
						} // endif ( $condition_section_show )
						// End Conditions Section

						// Begin Treatments Section
						if ( $treatment_section_show ) { ?>
							<div class="col-12<?php echo $treatment_section_class ? ' ' . $treatment_section_class : '' ?> list-container list-container-rows"<?php echo $treatment_section_id ? ' id="' . $treatment_section_id . '"' : '' ?>>
								<?php

								// Begin Treatments Section Heading
								// Add treatments section heading only if condition section is also shown
								if ( $condition_section_show ) { ?>
									<h3 class="module-inner-title<?php echo !$treatment_section_show_header ? ' sr-only' : ''; ?>"<?php echo $treatment_section_id ? ' id="' . $treatment_section_id . '-title"' : ''; ?>><span class="title"><?php echo $treatment_section_title; ?></span></h3>
									<?php if ( $treatment_section_intro ) { ?>
										<p class="note<?php echo !$treatment_section_show_header ? ' sr-only' : ''; ?>"><?php echo $treatment_section_intro; ?></p>
									<?php } // endif ( $treatment_section_intro )
								} // endif ( $condition_section_show )
								// End Treatments Section Heading

								// Begin Treatments Section Content ?>
								<ul class="list">
									<?php

									if ( $treatment_count > 0 ) {

										while ( $treatment_cpt_query->have_posts() ) {
											$treatment_cpt_query->the_post();
											$page_id = get_the_ID();
											$treatment_title = get_the_title($page_id);
											$treatment_title_attr = uamswp_attr_conversion($treatment_title);
											if ( $condition_treatment_section_link_item ) {
												$treatment_url = user_trailingslashit(get_the_permalink($page_id));
												$treatment_aria_label = 'Go to ' . $treatment_single_name_attr . ' page for ' . $treatment_title_attr;
											} else {
												$treatment_url = '';
												$treatment_aria_label = '';
											}

											// AvailableService Schema Data

												// Check/define the main AvailableService schema array
												$schema_available_service = ( isset($schema_available_service) && is_array($schema_available_service) && !empty($schema_available_service) ) ? $schema_available_service : array();

												// Add this treatment's details to the main AvailableService schema array
												$schema_available_service = uamswp_fad_schema_available_service(
													$schema_available_service, // array // Main availableService schema array
													$page_id // int // ID of the medical entity (a.k.a., Treatment and Procedure post)
												);

											?>
											<li>
												<?php
												if ( $condition_treatment_section_link_item ) { ?>
													<a href="<?php echo $treatment_url; ?>" aria-label="<?php echo $treatment_aria_label; ?>" class="btn btn-outline-primary">
														<?php echo $treatment_title; ?>
													</a>
												<?php
												} else {
													echo '<span class="item">' . $treatment_title . '</span>';
												} // endif ( $condition_treatment_section_link_item )
												?>
											</li>
											<?php

										} // endwhile ( $treatment_cpt_query->have_posts() )

									} // endif ( $treatment_count > 0 )

									wp_reset_postdata();

									?>
								</ul>
								<?php
								// End Treatments Section Content

								?>
							</div>
						<?php
						} // endif ( $treatment_section_show )
						// End Treatments Section

						?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php
} // endif ( $treatment_section_show )

?>