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
 * 	// Vars defined in uamswp_fad_labels_treatment()
 * 		$treatment_single_name // string
 * 		$treatment_single_name_attr // string
 * 		$treatment_plural_name // string
 * 		$treatment_plural_name_attr // string
 * 	// Vars defined in uamswp_fad_treatment_fpage_text_general()
 * 		$treatment_fpage_title_general // string
 * 		$treatment_fpage_intro_general // string
 * 	// Vars defined in uamswp_fad_treatment_query()
 * 		$treatment_section_show // bool
 * 		$treatment_cpt_query // WP_Post[]
 * 		$treatments_cpt // int[]
 * 		$treatment_ids // int[]
 * 		$treatment_count // int
 * 	// Vars defined in uamswp_fad_ontology_hide()
 * 		$hide_medical_ontology // bool
 * 
 * Optional vars:
 * 	// Vars defined on the template
 * 		$treatment_section_class // Section class // string (default: 'conditions-treatments')
 * 		$treatment_section_id // Section ID // string (default: 'treatments')
 * 		$treatment_section_show_header // Query for whether to display the section header // bool (default: true)
 * 		$treatment_section_title // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of treatment section title in a general placement)
 * 		$treatment_section_intro // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of treatment section intro text in a general placement)
 * 		$condition_treatment_section_link_item // Query for whether to link the list items // bool (default: false)
 * 
 * Return:
 * 	var $condition_treatment_schema; // string
 * 	var $condition_treatment_schema_i // int
 * 	var $condition_treatment_schema_count // int
 * 	html <section />
 */

// Do something
if ( $treatment_section_show && !$hide_medical_ontology ) {

	// Check/define variables

		$treatment_section_class = isset($treatment_section_class) ? $treatment_section_class : 'conditions-treatments';
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
		$condition_treatment_section_link_item = isset($condition_treatment_section_link_item) ? $condition_treatment_section_link_item : false;

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

							// Set the iteration variable for MedicalSpecialty schema data
							// Reuse iteration from conditions list if it comes before this one
							global $condition_treatment_schema_i;
							$condition_treatment_schema_i = isset($condition_treatment_schema_i) ? $condition_treatment_schema_i : 0;
							$i = $condition_treatment_schema_i;

							// Count conditions and treatments for MedicalSpecialty schema data
							// Reuse count from conditions list if it comes before this one
							global $condition_treatment_schema_count;
							if ( !isset($condition_treatment_schema_count) ) {
								global $condition_section_show;
								global $condition_count;
								$condition_treatment_schema_count = ( $condition_section_show ? $condition_count : 0 ) + ( $treatment_section_show ? $treatment_count : 0 );
								$schema_construct_item_count = $condition_treatment_schema_count;
							}

							// Define the top-level MedicalSpecialty schema data attribute label
							global $condition_treatment_schema_attr;
							$condition_treatment_schema_attr = isset($condition_treatment_schema_attr) ? $condition_treatment_schema_attr : 'medicalSpecialty';
							$schema_construct_attr = $condition_treatment_schema_attr;

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
									$schema_construct_arr['name'] = $treatment_title_attr;
									if ( $condition_treatment_section_link_item ) {
										$schema_construct_arr['url'] = $treatment_url;
									}

									// Construct the MedicalSpecialty schema data
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

							$condition_treatment_schema_i = $i; // Make iteration available to conditions list MedicalSpecialty schema data if it comes later

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