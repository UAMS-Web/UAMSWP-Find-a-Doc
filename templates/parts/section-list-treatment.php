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
 * 
 * Return:
 * 	var $treatment_schema // string
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
								$i = 0;
								while ( $treatment_cpt_query->have_posts() ) {
									$treatment_cpt_query->the_post();
									$id = get_the_ID();
									$treatment_title = get_the_title($id);
									$treatment_title_attr = uamswp_attr_conversion($treatment_title);
									$treatment_url = get_the_permalink($id);
									$treatment_aria_label = 'Go to ' . $treatment_single_name_attr . ' page for ' . $treatment_title_attr;
									if ($i > 0) {
										$treatment_schema .= ',
';
									}
									$treatment_schema .= '
		{
			"@type": "MedicalSpecialty",
			"name": "' . $treatment_title_attr . '",
			"url":"'. $treatment_url .'"
		}';
									$i++;
									?>
									<li>
										<a href="<?php echo $treatment_url; ?>" aria-label="<?php echo $treatment_aria_label; ?>" class="btn btn-outline-primary">
											<?php echo $treatment_title; ?>
										</a>
									</li>
								<?php
								} // endwhile
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