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
 * // Defined on the template
 * 		$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 * 	// Vars defined in uamswp_fad_labels_condition()
 * 		$condition_single_name_attr // string
 * 	// Vars defined in uamswp_fad_labels_treatment()
 * 		$treatment_single_name_attr // string
 * 	// Vars defined in uamswp_fad_fpage_text_condition_treatment_general()
 * 		$condition_treatment_fpage_title_general // string
 * 		$condition_treatment_fpage_intro_general // string
 * 	// Vars defined in uamswp_fad_fpage_text_condition_general()
 * 		$condition_fpage_title_general // string
 * 		$condition_fpage_intro_general // string
 * 	// Vars defined in uamswp_fad_fpage_text_treatment_general()
 * 		$treatment_fpage_title_general // string
 * 		$treatment_fpage_intro_general // string
 * 	// Vars defined in uamswp_fad_condition_query() and in uamswp_fad_treatment_query()
		$condition_treatment_section_show; // bool
 * 	// Vars defined in uamswp_fad_condition_query()
 * 		$condition_section_show // bool
 * 		$condition_cpt_query // WP_Post[]
 * 		$condition_count // int
 * 	// Vars defined in uamswp_fad_treatment_query()
 * 		$treatment_section_show // bool
 * 		$treatment_cpt_query // WP_Post[]
 * 		$treatment_count // int
 * 
 * Optional vars:
 * 	// Vars defined in uamswp_fad_ontology_hide()
 * 		$hide_medical_ontology // bool
 * 	// Vars defined on the template
 * 		$condition_treatment_section_class // Section class // string (default: 'conditions-treatments')
 * 		$condition_treatment_section_id // Section ID // string (default: 'conditions-treatments')
 * 		$condition_treatment_section_show_header // Query for whether to display the section header // bool (default: true)
 * 		$condition_treatment_section_title // Text to use for the section title // string (default: Find-a-Doc Settings value for combined condition/treatment section title in a general placement)
 * 		$condition_treatment_section_intro // Text to use for the section intro text // string (default: Find-a-Doc Settings value for combined condition/treatment section intro text in a general placement)
 * 		$condition_treatment_section_link_item // Query for whether to link the list items // bool (default: false)
 * 		$condition_section_class // Conditions subsection class // string (default: 'conditions')
 * 		$condition_section_id // Conditions subsection ID // string (default: 'conditions')
 * 		$condition_section_show_header // Query for whether to display the conditions subsection header // bool (default: true)
 * 		$condition_section_title // Text to use for the conditions subsection title // string (default: Find-a-Doc Settings value for areas of condition section title in a general placement)
 * 		$condition_section_intro // Text to use for the conditions subsection intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
 * 		$treatment_section_class // Treatments subsection class // string (default: 'treatments')
 * 		$treatment_section_id // Treatments subsection ID // string (default: 'treatments')
 * 		$treatment_section_show_header // Query for whether to display the treatments subsection header // bool (default: true)
 * 		$treatment_section_title // Text to use for the treatments subsection title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
 * 		$treatment_section_intro // Text to use for the treatments subsection intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)
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

		// Text to use for the section title and the section intro text
		if ( $condition_section_show && $treatment_section_show ) {
			// If both the conditions section and the treatments section are shown...

			// Set the section title and section intro normally
			if ( !isset($condition_treatment_section_title) ) {

				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($condition_treatment_fpage_title_general) ) {
					$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$condition_treatment_fpage_title_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_title_general']; // string
				}
				$condition_treatment_section_title = $condition_treatment_fpage_title_general;

			}
			if ( !isset($condition_treatment_section_intro) ) {

				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($condition_treatment_fpage_intro_general) ) {
					$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$condition_treatment_fpage_intro_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_intro_general']; // string
				}
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

		// Text to use for the conditions subsection title
		if ( !isset($condition_section_title) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($condition_fpage_title_general) ) {
				$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
					$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				);
					$condition_fpage_title_general = $fpage_text_condition_general_vars['condition_fpage_title_general']; // string
			}
			$condition_section_title = $condition_fpage_title_general;
		}

		// Text to use for the conditions subsection intro text
		if ( !isset($condition_section_intro) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($condition_fpage_intro_general) ) {
				$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
					$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				);
					$condition_fpage_intro_general = $fpage_text_condition_general_vars['condition_fpage_intro_general']; // string
			}
			$condition_section_intro = $condition_fpage_intro_general;
		}

		// Text to use for the treatments subsection title
		if ( !isset($treatment_section_title) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($treatment_fpage_title_general) ) {
				$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
					$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				);
					$treatment_fpage_title_general = $fpage_text_treatment_general_vars['treatment_fpage_title_general']; // string
			}
			$treatment_section_title = $treatment_fpage_title_general;
		}

		// Text to use for the treatments subsection intro text
		if ( !isset($treatment_section_intro) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($treatment_fpage_intro_general) ) {
				$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
					$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				);
					$treatment_fpage_intro_general = $fpage_text_treatment_general_vars['treatment_fpage_intro_general']; // string
			}
			$treatment_section_intro = $treatment_fpage_intro_general;
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

		// Other variables

			if ( !isset($condition_single_name_attr) ) {
				$labels_condition_vars = isset($labels_condition_vars) ? $labels_condition_vars : uamswp_fad_labels_condition();
					$condition_single_name_attr = $labels_condition_vars['condition_single_name_attr']; // string
			}

			if ( !isset($treatment_single_name_attr) ) {
				$labels_treatment_vars = isset($labels_treatment_vars) ? $labels_treatment_vars : uamswp_fad_labels_treatment();
					$treatment_single_name_attr = $labels_treatment_vars['treatment_single_name_attr']; // string
			}

			if ( !isset($condition_treatment_fpage_title_general) ) {
				$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
					$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				);
					$condition_treatment_fpage_title_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_title_general']; // string
			}

			if ( !isset($condition_treatment_fpage_intro_general) ) {
				$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
					$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				);
					$condition_treatment_fpage_intro_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_intro_general']; // string
			}

			if ( !isset($condition_fpage_title_general) ) {
				$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
					$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				);
					$condition_fpage_title_general = $fpage_text_condition_general_vars['condition_fpage_title_general']; // string
			}

			if ( !isset($condition_fpage_intro_general) ) {
				$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
					$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				);
					$condition_fpage_intro_general = $fpage_text_condition_general_vars['condition_fpage_intro_general']; // string
			}

			if ( !isset($treatment_fpage_title_general) ) {
				$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
					$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				);
					$treatment_fpage_title_general = $fpage_text_treatment_general_vars['treatment_fpage_title_general']; // string
			}

			if ( !isset($treatment_fpage_intro_general) ) {
				$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
					$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				);
					$treatment_fpage_intro_general = $fpage_text_treatment_general_vars['treatment_fpage_intro_general']; // string
			}

			if ( !isset($condition_cpt_query) ) {
				$condition_query_vars = isset($condition_query_vars) ? $condition_query_vars : uamswp_fad_condition_query(
					$conditions_cpt, // int[]
					$condition_treatment_section_show, // bool (optional)
					$ontology_type // bool (optional)
				);
					$condition_cpt_query = $condition_query_vars['condition_cpt_query']; // WP_Post[]
			}

			if ( !isset($condition_section_show) ) {
				$condition_query_vars = isset($condition_query_vars) ? $condition_query_vars : uamswp_fad_condition_query(
					$conditions_cpt, // int[]
					$condition_treatment_section_show, // bool (optional)
					$ontology_type // bool (optional)
				);
					$condition_section_show = $condition_query_vars['condition_section_show']; // bool
			}

			if ( !isset($condition_treatment_section_show) ) {
				$condition_query_vars = isset($condition_query_vars) ? $condition_query_vars : uamswp_fad_condition_query(
					$conditions_cpt, // int[]
					$condition_treatment_section_show, // bool (optional)
					$ontology_type // bool (optional)
				);
					$condition_treatment_section_show = $condition_query_vars['condition_treatment_section_show']; // bool
			}

			if ( !isset($condition_count) ) {
				$condition_query_vars = isset($condition_query_vars) ? $condition_query_vars : uamswp_fad_condition_query(
					$conditions_cpt, // int[]
					$condition_treatment_section_show, // bool (optional)
					$ontology_type // bool (optional)
				);
					$condition_count = $condition_query_vars['condition_count']; // int
			}

			if ( !isset($treatment_cpt_query) ) {
				$treatment_query_vars = isset($treatment_query_vars) ? $treatment_query_vars : uamswp_fad_treatment_query(
					$treatments_cpt, // int[]
					$condition_treatment_section_show, // bool (optional)
					$ontology_type, // bool (optional)
				);
					$treatment_cpt_query = $treatment_query_vars['treatment_cpt_query']; // WP_Post[]
			}

			if ( !isset($treatment_section_show) ) {
				$treatment_query_vars = isset($treatment_query_vars) ? $treatment_query_vars : uamswp_fad_treatment_query(
					$treatments_cpt, // int[]
					$condition_treatment_section_show, // bool (optional)
					$ontology_type, // bool (optional)
				);
					$treatment_section_show = $treatment_query_vars['treatment_section_show']; // bool
			}

			if ( !isset($condition_treatment_section_show) ) {
				$treatment_query_vars = isset($treatment_query_vars) ? $treatment_query_vars : uamswp_fad_treatment_query(
					$treatments_cpt, // int[]
					$condition_treatment_section_show, // bool (optional)
					$ontology_type, // bool (optional)
				);
					$condition_treatment_section_show = $treatment_query_vars['condition_treatment_section_show']; // bool
			}

			if ( !isset($treatment_count) ) {
				$treatment_query_vars = isset($treatment_query_vars) ? $treatment_query_vars : uamswp_fad_treatment_query(
					$treatments_cpt, // int[]
					$condition_treatment_section_show, // bool (optional)
					$ontology_type, // bool (optional)
				);
					$treatment_count = $treatment_query_vars['treatment_count']; // int
			}

			if ( !isset($hide_medical_ontology) ) {
				$ontology_hide_vars = isset($ontology_hide_vars) ? $ontology_hide_vars : uamswp_fad_ontology_hide();
					$hide_medical_ontology = $ontology_hide_vars['hide_medical_ontology']; // bool
			}

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
												$condition_url = get_the_permalink($page_id);
												$condition_aria_label = 'Go to ' . $condition_single_name_attr . ' page for ' . $condition_title_attr;
											} else {
												$condition_url = '';
												$condition_aria_label = '';
											}

											// MedicalSpecialty Schema Data
										
												// Check/define the main medicalSpecialty schema array
												$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();
										
												// Add this location's details to the main medicalSpecialty schema array
												$schema_medical_specialty = uamswp_schema_medical_specialty(
													$schema_medical_specialty, // array (optional) // Main medicalSpecialty schema array
													$condition_title_attr, // string (optional) // The name of the item.
													$condition_url // string (optional) // URL of the item.
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
												$treatment_url = get_the_permalink($page_id);
												$treatment_aria_label = 'Go to ' . $treatment_single_name_attr . ' page for ' . $treatment_title_attr;
											} else {
												$treatment_url = '';
												$treatment_aria_label = '';
											}

											// MedicalSpecialty Schema Data
										
												// Check/define the main medicalSpecialty schema array
												$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();
										
												// Add this location's details to the main medicalSpecialty schema array
												$schema_medical_specialty = uamswp_schema_medical_specialty(
													$schema_medical_specialty, // array (optional) // Main medicalSpecialty schema array
													$treatment_title_attr, // string (optional) // The name of the item.
													$treatment_url // string (optional) // URL of the item.
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