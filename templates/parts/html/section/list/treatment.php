<?php
/*
 * Template Name: Treatment List Section
 *
 * Description: A template part that displays a list of treatments associated with
 * the current page.
 *
 * Designed for UAMS Health Find-a-Doc
 *
 * Required vars:
 * 	$page_id // int // ID of the current page
 * 	$treatments_cpt // int[]
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 * 	$hide_medical_ontology // bool // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
 *
 * Optional vars:
 * 	$schema_medical_specialty // array // MedicalSpecialty Schema data
 * 	$treatment_section_show // bool
 * 	$ontology_type // bool // Query for whether item is ontology type vs. content type
 * 	$treatment_section_title // string (default: Find-a-Doc Settings value for areas of treatment section title in a general placement) // Text to use for the section title
 * 	$treatment_section_intro // string (default: Find-a-Doc Settings value for areas of treatment section intro text in a general placement) // Text to use for the section intro text
 * 	$condition_treatment_section_link_item // bool (default: false) // Query for whether to link the list items
 * 	$treatment_section_show_header // bool (default: true) // Query for whether to display the section header
 * 	$treatment_section_class // string (default: 'conditions-treatments') // Section class
 * 	$treatment_section_id // string (default: 'treatments') // Section ID
 * 	$treatment_cpt_query // WP_Post[]
 * 	$treatment_ids // int[]
 * 	$treatment_count // int
 * 	$treatment_single_name // string
 * 	$treatment_single_name_attr // string
 * 	$treatment_plural_name // string
 * 	$treatment_plural_name_attr // string
 * 	$treatment_fpage_title_general // string
 * 	$treatment_fpage_intro_general // string

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

		// Text elements

			if (
				!isset($treatment_section_title) || empty($treatment_section_title)
				||
				!isset($treatment_section_intro) || empty($treatment_section_intro)
			) {

				// Get the system settings for general placement of treatment item text elements
				include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/treatment.php' );

			}

			if (
				!isset($treatment_section_title) || empty($treatment_section_title)
			) {

				$treatment_section_title = $treatment_fpage_title_general;

			}

			if (
				!isset($treatment_section_intro) || empty($treatment_section_intro)
			) {

				$treatment_section_intro = $treatment_fpage_intro_general;

			}

		// Query for whether to display the section header
		$treatment_section_show_header = isset($treatment_section_show_header) ? $treatment_section_show_header : true;

		// Section class
		$treatment_section_class = isset($treatment_section_class) ? $treatment_section_class : 'conditions-treatments';

		// Section ID
		$treatment_section_id = isset($treatment_section_id) ? $treatment_section_id : 'treatments';

		// Get system settings for treatment labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/treatment.php' );

		// Other variables

			// Get the system settings for general placement of treatment item text elements
			include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/treatment.php' );

			// Get the ontology subsection values
			include( UAMS_FAD_PATH . '/templates/parts/vars/sys/ontology-subsection.php' );

			// Related Treatments Section Query
			include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/treatment.php' );

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
										$treatment_url = user_trailingslashit(get_the_permalink($page_id));
										$treatment_aria_label = 'Go to ' . $treatment_single_name_attr . ' page for ' . $treatment_title_attr;
									} else {
										$treatment_url = '';
										$treatment_aria_label = '';
									}

									// MedicalSpecialty Schema Data

										// Check/define the main medicalSpecialty schema array
										$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();

										// Add this location's details to the main medicalSpecialty schema array
										$schema_medical_specialty = uamswp_fad_schema_medicalSpecialty_old(
											$schema_medical_specialty, // array // Optional // Main medicalSpecialty schema array
											$treatment_title_attr, // string // Optional // The name of the item.
											$treatment_url // string // Optional // URL of the item.
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