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
 * 	// Vars defined in uamswp_fad_condition_fpage_text_general()
 * 		$condition_fpage_title_general // string
 * 		$condition_fpage_intro_general // string
 * 	// Vars defined in uamswp_fad_condition_query()
 * 		$condition_section_show // bool
 * 		$condition_cpt_query // WP_Post[]
 * 		$conditions_cpt // int[]
 * 		$condition_ids // int[]
 * 		$condition_count // int
 * 	// Vars defined in uamswp_fad_ontology_hide()
 * 		$hide_medical_ontology // bool
 * 
 * Optional vars:
 * 	// Vars defined on the template
 * 		$condition_section_class // Section class // string (default: 'conditions-treatments')
 * 		$condition_section_id // Section ID // string (default: 'conditions')
 * 		$condition_section_show_header // Query for whether to display the section header // bool (default: true)
 * 		$condition_section_title // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of condition section title in a general placement)
 * 		$condition_section_intro // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of condition section intro text in a general placement)
 * 		$condition_section_link_item // Query for whether to link the list items // bool (default: false)
 * 
 * Return:
 * 	$condition_schema; // string
 * 	html <section />
 */

// Do something
if ( $condition_section_show && !$hide_medical_ontology ) {

	// Check/define variables

		$condition_section_class = isset($condition_section_class) ? $condition_section_class : 'conditions-treatments';
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
							if ( $condition_count > 0 ) {
								$i = 0;

								while ( $condition_cpt_query->have_posts() ) {
									$condition_cpt_query->the_post();
									$id = get_the_ID();
									$condition_title = get_the_title($id);
									$condition_title_attr = uamswp_attr_conversion($condition_title);
									if ( $condition_treatment_section_link_item ) {
										$condition_url = get_the_permalink($id);
										$condition_aria_label = 'Go to ' . $condition_single_name_attr . ' page for ' . $condition_title_attr;
									}

									// Define the attribute-value pairs
									$schema_construct_arr = array();
									$schema_construct_arr['@type'] = 'MedicalSpecialty';
									$schema_construct_arr['name'] = $condition_title_attr;
									if ( $condition_treatment_section_link_item ) {
										$schema_construct_arr['url'] = $condition_url;
									}

									// Define number of tabs at start of schema block being created here
									$chr_tab_base_count = 2;

									// Construct the schema
									$condition_schema .= uamswp_schema_construct($schema_construct_arr);

									$i++;
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
								} // endwhile
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