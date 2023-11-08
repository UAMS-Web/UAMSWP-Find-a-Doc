<?php
/*
 * Template Name: Condition List Section
 *
 * Description: A template part that displays a list of conditions associated with
 * the current page.
 *
 * Designed for UAMS Health Find-a-Doc
 *
 * Required vars:
 * 	$page_id // int // ID of the current page
 * 	$conditions_cpt // int[] // Value of the related conditions input (or $condition_descendants, List of this condition item's descendant items)
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 * 	$hide_medical_ontology // bool // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
 *
 * Optional vars:
 * 	$schema_medical_specialty // array // MedicalSpecialty Schema data
 * 	$condition_section_show // bool // Query for whether to show the conditions section (or $condition_descendant_section_show, Query for whether to show the descendant condition section)
 * 	$condition_section_title // string (default: Find-a-Doc Settings value for areas of condition section title in a general placement) // Text to use for the section title
 * 	$condition_section_intro // string (default: Find-a-Doc Settings value for areas of condition section intro text in a general placement) // Text to use for the section intro text
 * 	$condition_treatment_section_link_item // bool (default: false) // Query for whether to link the list items
 * 	$condition_section_show_header // bool (default: true) // Query for whether to display the section header
 * 	$condition_section_class // string (default: 'conditions-treatments') // Section class
 * 	$condition_section_id // string (default: 'conditions') // Section ID
 * 	$condition_single_name // string
 * 	$condition_single_name_attr // string
 * 	$condition_plural_name // string
 * 	$condition_plural_name_attr // string
 * 	$condition_fpage_title_general // string
 * 	$condition_fpage_intro_general // string
 * 	$condition_cpt_query // WP_Post[]
 * 	$condition_ids // int[]
 * 	$condition_count // int
 *
 * Return:
 * 	var $schema_medical_specialty // array
 * 	html <section />
 */

// Check/define variables

	// Query for whether to show the conditions section
	$condition_section_show = isset($condition_section_show) ? $condition_section_show : false;

	// Query for whether to link the list items
	$condition_treatment_section_link_item = isset($condition_treatment_section_link_item) ? $condition_treatment_section_link_item : false;

	// Other variables
	$hide_medical_ontology = isset($hide_medical_ontology) ? $hide_medical_ontology : false;

	// Revisit query for whether to show the conditions section
	if (
		$condition_section_show
		&&
		$condition_treatment_section_link_item
		&&
		$hide_medical_ontology
		) {

		// If the conditions section should be shown...
		// and if the condition items should be linked...
		// and if certain ontology items should be hidden on this page...

		// Set the conditions section to not be shown on this page
		$condition_section_show = false;

	}

// Do something
if ( $condition_section_show ) {

	// Check/define variables

		// Query for whether item is ontology type vs. content type
		$ontology_type = isset($ontology_type) ? $ontology_type : true;

		// Text elements

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

		// Query for whether to display the section header
		$condition_section_show_header = isset($condition_section_show_header) ? $condition_section_show_header : true;

		// Section class
		$condition_section_class = isset($condition_section_class) ? $condition_section_class : 'conditions-treatments';

		// Section ID
		$condition_section_id = isset($condition_section_id) ? $condition_section_id : 'conditions';

		// Get system settings for condition labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition.php' );

		// Get the system settings for general placement of condition item text elements
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition.php' );

		// Get the ontology subsection values
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/ontology-subsection.php' );

		// Related Conditions Section Query
		include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/condition.php' );

	?>
	<section class="uams-module<?php echo $condition_section_class ? ' ' . $condition_section_class : ''; ?> bg-auto<?php echo $condition_section_collapse_list ? ' collapse-list' : ''; ?>"<?php echo $condition_section_id ? ' id="' . $condition_section_id . '" aria-labelledby="' . $condition_section_id . '-title"' : ''; ?>>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title<?php echo !$condition_section_show_header ? ' sr-only' : ''; ?>"<?php echo $condition_section_id ? ' id="' . $condition_section_id . '-title"' : ''; ?>><span class="title"><?php echo $condition_section_title; ?></span></h2>
					<?php if ( $condition_section_intro ) { ?>
						<p class="note<?php echo !$condition_section_show_header ? ' sr-only' : ''; ?>"><?php echo $condition_section_intro; ?></p>
					<?php } // endif ( $condition_section_intro ) ?>
					<div class="list-container list-container-rows">
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

									$i++;

								} // endwhile ( $condition_cpt_query->have_posts() )

							} // endif ( $condition_count > 0 )

							wp_reset_postdata();

							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php
} // endif ( $condition_section_show )
?>