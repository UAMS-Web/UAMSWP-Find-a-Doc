<?php
/*
 * Template Name: Condition List Section
 * 
 * Description: A template part that displays a list of conditions associated with 
 * the current page.
 * 
 * When this template part is needed for a hook, use the 
 * uamswp_fad_section_condition() function.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	// Vars defined in uamswp_fad_labels_condition()
 * 		$condition_single_name // string
 * 		$condition_single_name_attr // string
 * 		$condition_plural_name // string
 * 		$condition_plural_name_attr // string
 * 	// Vars defined in uamswp_fad_fpage_text_condition_general()
 * 		$condition_fpage_title_general // string
 * 		$condition_fpage_intro_general // string
 * 	// Vars defined in uamswp_fad_condition_query()
 * 		$condition_section_show // bool
 * 		$condition_cpt_query // WP_Post[]
 * 		$conditions_cpt // int[]
 * 		$condition_ids // int[]
 * 		$condition_count // int
 * 
 * Optional vars:
 * 	// Vars defined in uamswp_fad_ontology_hide()
 * 		$hide_medical_ontology // bool
 * 	// Vars defined on the template
 * 		$condition_section_class // Section class // string (default: 'conditions-treatments')
 * 		$condition_section_id // Section ID // string (default: 'conditions')
 * 		$condition_section_show_header // Query for whether to display the section header // bool (default: true)
 * 		$condition_section_title // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of condition section title in a general placement)
 * 		$condition_section_intro // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of condition section intro text in a general placement)
 * 		$condition_treatment_section_link_item // Query for whether to link the list items // bool (default: false)
 * 
 * Return:
 * 	var $condition_treatment_schema; // string
 * 	var $condition_treatment_schema_i // int
 * 	var $condition_treatment_schema_count // int
 * 	html <section />
 */

// Check/define variables
$condition_section_show = isset($condition_section_show) ? $condition_section_show : false;
$hide_medical_ontology = isset($hide_medical_ontology) ? $hide_medical_ontology : false;
$condition_treatment_section_link_item = isset($condition_treatment_section_link_item) ? $condition_treatment_section_link_item : false;
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

		$condition_section_class = isset($condition_section_class) ? $condition_section_class : 'conditions-treatments';
		$condition_section_id = isset($condition_section_id) ? $condition_section_id : 'conditions';
		$condition_section_show_header = isset($condition_section_show_header) ? $condition_section_show_header : true;
		if ( !isset($condition_section_title) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($condition_fpage_title_general) ) {
				$fpage_text_condition_general_vars = uamswp_fad_fpage_text_condition_general();
					$condition_fpage_title_general = $fpage_text_condition_general_vars['condition_fpage_title_general']; // string
			}
			$condition_section_title = $condition_fpage_title_general;
		}
		if ( !isset($condition_section_intro) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($condition_fpage_intro_general) ) {
				$fpage_text_condition_general_vars = uamswp_fad_fpage_text_condition_general();
					$condition_fpage_intro_general = $fpage_text_condition_general_vars['condition_fpage_intro_general']; // string
			}
			$condition_section_intro = $condition_fpage_intro_general;
		}
		$condition_treatment_section_link_item = isset($condition_treatment_section_link_item) ? $condition_treatment_section_link_item : false;

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

							// Set the iteration variable for MedicalSpecialty schema data
							// Reuse iteration from treatments list if it comes before this one
							global $condition_treatment_schema_i;
							$condition_treatment_schema_i = isset($condition_treatment_schema_i) ? $condition_treatment_schema_i : 0;
							$i = $condition_treatment_schema_i;

							// Count conditions and treatments for MedicalSpecialty schema data
							// Reuse count from treatments list if it comes before this one
							global $condition_treatment_schema_count;
							if ( !isset($condition_treatment_schema_count) ) {
								global $treatment_section_show;
								global $treatment_count;
								$condition_treatment_schema_count = ( $condition_section_show ? $condition_count : 0 ) + ( $treatment_section_show ? $treatment_count : 0 );
								$schema_construct_item_count = $condition_treatment_schema_count;
							}

							// Define the top-level MedicalSpecialty schema data attribute label
							global $condition_treatment_schema_attr;
							$condition_treatment_schema_attr = isset($condition_treatment_schema_attr) ? $condition_treatment_schema_attr : 'medicalSpecialty';
							$schema_construct_attr = $condition_treatment_schema_attr;

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

							$condition_treatment_schema_i = $i; // Make iteration available to treatments list MedicalSpecialty schema data if it comes later

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