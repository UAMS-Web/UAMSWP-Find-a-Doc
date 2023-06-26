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
 * 	// Vars defined in uamswp_fad_labels_condition()
 * 		$condition_single_name_attr // string
 * 	// Vars defined in uamswp_fad_labels_treatment()
 * 		$treatment_single_name_attr // string
 * 	// Vars defined in uamswp_fad_condition_treatment_fpage_text_general()
 * 		$condition_treatment_fpage_title_general // string
 * 		$condition_treatment_fpage_intro_general // string
 * 	// Vars defined in uamswp_fad_condition_fpage_text_general()
 * 		$condition_fpage_title_general // string
 * 		$condition_fpage_intro_general // string
 * 	// Vars defined in uamswp_fad_treatment_fpage_text_general()
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
 * 	// Vars defined in uamswp_fad_ontology_hide()
 * 		$hide_medical_ontology // bool
 * 
 * Optional vars:
 * 	// Vars defined on the template
 * 		$condition_treatment_section_class // Section class // string (default: 'conditions-treatments')
 * 		$condition_treatment_section_id // Section ID // string (default: 'conditions-treatments')
 * 		$condition_treatment_section_show_header // Query for whether to display the section header // bool (default: true)
 * 		$condition_treatment_section_title // Text to use for the section title // string (default: Find-a-Doc Settings value for combined condition/treatment section title in a general placement)
 * 		$condition_treatment_section_intro // Text to use for the section intro text // string (default: Find-a-Doc Settings value for combined condition/treatment section intro text in a general placement)
 * 		$condition_section_link_item // Query for whether to link the list items // bool (default: false)
 * 		$condition_section_class // Conditions subsection class // string (default: 'conditions')
 * 		$condition_section_id // Conditions subsection ID // string (default: 'conditions')
 * 		$condition_section_show_header // Query for whether to display the conditions subsection header // bool (default: true)
 * 		$condition_section_title // Text to use for the conditions subsection title // string (default: Find-a-Doc Settings value for areas of condition section title in a general placement)
 * 		$condition_section_intro // Text to use for the conditions subsection intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
 * 		$treatment_section_class // Treatments subection class // string (default: 'treatments')
 * 		$treatment_section_id // Treatments subection ID // string (default: 'treatments')
 * 		$treatment_section_show_header // Query for whether to display the treatments subsection header // bool (default: true)
 * 		$treatment_section_title // Text to use for the treatments subsection title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
 * 		$treatment_section_intro // Text to use for the treatments subsection intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)
 * 
 * Return:
 * 	var $condition_treatment_schema; // string
 * 	html <section />
 */

// Do something
if ( $condition_treatment_section_show && !$hide_medical_ontology ) {

	// Check/define variables

		// Conditions

			$condition_section_class = isset($condition_section_class) ? $condition_section_class : 'conditions';
			$condition_section_id = isset($condition_section_id) ? $condition_section_id : 'conditions';
			$condition_section_show_header = isset($condition_section_show_header) ? $condition_section_show_header : true;
			if ( !isset($condition_section_title) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($condition_fpage_title_general) ) {
					uamswp_fad_condition_fpage_text_general();
					global $condition_fpage_title_general;
				}
				$condition_section_title = $condition_fpage_title_general;
			}
			if ( !isset($condition_section_intro) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($condition_fpage_intro_general) ) {
					uamswp_fad_condition_fpage_text_general();
					global $condition_fpage_intro_general;
				}
				$condition_section_intro = $condition_fpage_intro_general;
			}

		// Treatments

			$treatment_section_class = isset($treatment_section_class) ? $treatment_section_class : 'treatments';
			$treatment_section_id = isset($treatment_section_id) ? $treatment_section_id : 'treatments';
			$treatment_section_show_header = isset($treatment_section_show_header) ? $treatment_section_show_header : true;
			if ( !isset($treatment_section_title) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($treatment_fpage_title_general) ) {
					uamswp_fad_treatment_fpage_text_general();
					global $treatment_fpage_title_general;
				}
				$treatment_section_title = $treatment_fpage_title_general;
			}
			if ( !isset($treatment_section_intro) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($treatment_fpage_intro_general) ) {
					uamswp_fad_treatment_fpage_text_general();
					global $treatment_fpage_intro_general;
				}
				$treatment_section_intro = $treatment_fpage_intro_general;
			}

		// Combined Conditions and Treatments

			$condition_treatment_section_class = isset($condition_treatment_section_class) ? $condition_treatment_section_class : 'conditions-treatments';
			$condition_treatment_section_id = isset($condition_treatment_section_id) ? $condition_treatment_section_id : 'conditions-treatments';
			$condition_treatment_section_show_header = isset($condition_treatment_section_show_header) ? $condition_treatment_section_show_header : true;
			if ( $condition_section_show && $treatment_section_show ) {
				// If both the conditions section and the treatments section are shown...

				// Set the section title and section intro normally
				if ( !isset($condition_treatment_section_title) ) {

					// Set the section title using the system settings for the section title in a general placement
					if ( !isset($condition_treatment_fpage_title_general) ) {
						uamswp_fad_condition_treatment_fpage_text_general();
						global $condition_treatment_fpage_title_general;
					}
					$condition_treatment_section_title = $condition_treatment_fpage_title_general;

				}
				if ( !isset($condition_treatment_section_intro) ) {

					// Set the section title using the system settings for the section title in a general placement
					if ( !isset($condition_treatment_fpage_intro_general) ) {
						uamswp_fad_condition_treatment_fpage_text_general();
						global $condition_treatment_fpage_intro_general;
					}
					$condition_treatment_section_intro = $condition_treatment_fpage_intro_general;

				}

				// Add a split class to the item containers
				$condition_treatment_section_split_class = 'col-md-6';
				$treatment_section_class = $condition_treatment_section_split_class . ' ' . $treatment_section_class;
				$condition_section_class = $condition_treatment_section_split_class . ' ' . $condition_section_class;

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
			$condition_treatment_section_link_item = isset($condition_treatment_section_link_item) ? $condition_treatment_section_link_item : false;

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

						// Set the iteration variable for MedicalSpecialty schema data
						$i = 0;

						// Count conditions and treatments for MedicalSpecialty schema data
						$schema_construct_item_count = ( $condition_section_show ? $condition_count : 0 ) + ( $treatment_section_show ? $treatment_count : 0 );

						// Define the top-level MedicalSpecialty schema data attribute label
						$condition_treatment_schema_attr = 'medicalSpecialty';

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
											$id = get_the_ID();
											$condition_title = get_the_title($id);
											$condition_title_attr = uamswp_attr_conversion($condition_title);
											if ( $condition_treatment_section_link_item ) {
												$condition_url = get_the_permalink($id);
												$condition_aria_label = 'Go to ' . $condition_single_name_attr . ' page for ' . $condition_title_attr;
											}

											// Define the MedicalSpecialty schema data attribute-value pairs
											$schema_construct_arr = array();
											$schema_construct_arr['@type'] = 'MedicalSpecialty';
											$schema_construct_arr['name'] = $condition_title_attr;
											if ( $condition_treatment_section_link_item ) {
												$schema_construct_arr['url'] = $condition_url;
											}

											// Construct the MedicalSpecialty schema data
											global $condition_treatment_schema;
											$condition_treatment_schema .= uamswp_schema_construct($schema_construct_arr);

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

											$i++;

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
											$id = get_the_ID();
											$treatment_title = get_the_title($id);
											$treatment_title_attr = uamswp_attr_conversion($treatment_title);
											if ( $condition_treatment_section_link_item ) {
												$treatment_url = get_the_permalink($id);
												$treatment_aria_label = 'Go to ' . $treatment_single_name_attr . ' page for ' . $treatment_title_attr;
											}

											// Define the MedicalSpecialty schema data attribute-value pairs
											$schema_construct_arr = array();
											$schema_construct_arr['@type'] = 'MedicalSpecialty';
											$schema_construct_arr['name'] = $condition_title_attr;
											if ( $condition_treatment_section_link_item ) {
												$schema_construct_arr['url'] = $condition_url;
											}

											// Construct the MedicalSpecialty schema data
											global $condition_treatment_schema;
											$condition_treatment_schema .= uamswp_schema_construct($schema_construct_arr);

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

											$i++;

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