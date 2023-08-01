<?php
/*
 * Template Name: Treatment List Section
 * 
 * Description: A template part that displays a list of treatments associated with 
 * the current page.
 * 
 * When this template part is needed for a hook, use the 
 * uamswp_fad_section_treatment() function.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	// Vars defined on the template
 * 		$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 * 	// Vars defined in uamswp_fad_labels_treatment()
 * 		$treatment_single_name // string
 * 		$treatment_single_name_attr // string
 * 		$treatment_plural_name // string
 * 		$treatment_plural_name_attr // string
 * 	// Vars defined in uamswp_fad_fpage_text_treatment_general()
 * 		$treatment_fpage_title_general // string
 * 		$treatment_fpage_intro_general // string
 * 	// Vars defined in uamswp_fad_treatment_query()
 * 		$treatment_section_show // bool
 * 		$treatment_cpt_query // WP_Post[]
 * 		$treatments_cpt // int[]
 * 		$treatment_ids // int[]
 * 		$treatment_count // int
 * 
 * Optional vars:
 * 	// Vars defined in uamswp_fad_ontology_hide()
 * 		$hide_medical_ontology // bool
 * 	// Vars defined on the template
 * 		$treatment_section_class // Section class // string (default: 'conditions-treatments')
 * 		$treatment_section_id // Section ID // string (default: 'treatments')
 * 		$treatment_section_show_header // Query for whether to display the section header // bool (default: true)
 * 		$treatment_section_title // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of treatment section title in a general placement)
 * 		$treatment_section_intro // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of treatment section intro text in a general placement)
 * 		$condition_treatment_section_link_item // Query for whether to link the list items // bool (default: false)
 * 
 * Return:
 * 	var $schema_medical_specialty; // array
 * 	html <section />
 */

// Check/define variables

	// Query for whether to show the treatment section
	$treatment_section_show = isset($treatment_section_show) ? $treatment_section_show : false;

	// Query for whether to link the list items
	$condition_treatment_section_link_item = isset($condition_treatment_section_link_item) ? $condition_treatment_section_link_item : false;

	// Other variables
	$hide_medical_ontology = isset($hide_medical_ontology) ? $hide_medical_ontology : false;

	// Revisit query for whether to show the treatment section
	if (
		$treatment_section_show
		&&
		$condition_treatment_section_link_item
		&&
		$hide_medical_ontology
		) {

		// If the treatments section should be shown...
		// and if the condition items should be linked...
		// and if certain ontology items should be hidden on this page...

		// Set the treatments section to not be shown on this page
		$treatment_section_show = false;

	}

// Do something
if ( $treatment_section_show ) {

	// Check/define variables

		// Query for whether item is ontology type vs. content type
		$ontology_type = isset($ontology_type) ? $ontology_type : true;

		// Text to use for the section title
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

		// Text to use for the section intro text
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
		$treatment_section_show_header = isset($treatment_section_show_header) ? $treatment_section_show_header : true;

		// Section class
		$treatment_section_class = isset($treatment_section_class) ? $treatment_section_class : 'conditions-treatments';

		// Section ID
		$treatment_section_id = isset($treatment_section_id) ? $treatment_section_id : 'treatments';

		// Other variables

			if ( !isset($treatment_single_name) ) {
				$labels_treatment_vars = isset($labels_treatment_vars) ? $labels_treatment_vars : uamswp_fad_labels_treatment();
					$treatment_single_name = $labels_treatment_vars['treatment_single_name']; // string
			}

			if ( !isset($treatment_single_name_attr) ) {
				$labels_treatment_vars = isset($labels_treatment_vars) ? $labels_treatment_vars : uamswp_fad_labels_treatment();
					$treatment_single_name_attr = $labels_treatment_vars['treatment_single_name_attr']; // string
			}

			if ( !isset($treatment_plural_name) ) {
				$labels_treatment_vars = isset($labels_treatment_vars) ? $labels_treatment_vars : uamswp_fad_labels_treatment();
					$treatment_plural_name = $labels_treatment_vars['treatment_plural_name']; // string
			}

			if ( !isset($treatment_plural_name_attr) ) {
				$labels_treatment_vars = isset($labels_treatment_vars) ? $labels_treatment_vars : uamswp_fad_labels_treatment();
					$treatment_plural_name_attr = $labels_treatment_vars['treatment_plural_name_attr']; // string
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

			if ( !isset($treatments_cpt) ) {
				$ontology_site_values_vars = isset($ontology_site_values_vars) ? $ontology_site_values_vars : uamswp_fad_ontology_site_values(
					$page_id // int // ID of the post
				);
					$treatments_cpt = $ontology_site_values_vars['treatments_cpt'];
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

			if ( !isset($treatment_ids) ) {
				$treatment_query_vars = isset($treatment_query_vars) ? $treatment_query_vars : uamswp_fad_treatment_query(
					$treatments_cpt, // int[]
					$condition_treatment_section_show, // bool (optional)
					$ontology_type, // bool (optional)
				);
					$treatment_ids = $treatment_query_vars['treatment_ids']; // int[]
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
	<section class="uams-module<?php echo $treatment_section_class ? ' ' . $treatment_section_class : ''; ?> bg-auto<?php echo $treatment_section_collapse_list ? ' collapse-list' : ''; ?>"<?php echo $treatment_section_id ? ' id="' . $treatment_section_id . '" aria-labelledby="' . $treatment_section_id . '-title"' : ''; ?>>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title<?php echo !$treatment_section_show_header ? ' sr-only' : ''; ?>"<?php echo $treatment_section_id ? ' id="' . $treatment_section_id . '-title"' : ''; ?>><span class="title"><?php echo $treatment_section_title; ?></span></h2>
					<?php if ( $treatment_section_intro ) { ?>
						<p class="note<?php echo !$treatment_section_show_header ? ' sr-only' : ''; ?>"><?php echo $treatment_section_intro; ?></p>
					<?php } // endif ( $treatment_section_intro ) ?>
					<div class="list-container list-container-rows">
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

									$i++;

								} // endwhile ( $treatment_cpt_query->have_posts() )

							} // endif ( $treatment_count > 0 )

							wp_reset_postdata();

							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php 
} // endif ( $treatment_section_show )
?>