<?php
/*
 * Template Name: Area of Expertise List Section
 * 
 * Description: A template part that displays a list of areas of expertise 
 * associated with the current page.
 * 
 * When this template part is needed for a hook, use the 
 * uamswp_fad_section_expertise( $expertises ) function.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	// Vars defined in uamswp_fad_labels_expertise()
 * 		$expertise_single_name // string
 * 		$expertise_single_name_attr // string
 * 		$expertise_plural_name // string
 * 		$expertise_plural_name_attr // string
 * 	// Vars defined in uamswp_fad_fpage_text_expertise_general()
 * 		$expertise_fpage_title_general // string
 * 		$expertise_fpage_intro_general // string
 * 	// Vars defined in uamswp_fad_expertise_query()
 * 		$expertise_section_show // bool
 * 		$expertise_query // WP_Post[]
 * 		$expertise_count // int
 * 	// Vars defined in uamswp_fad_expertise_descendant_query()
 * 		$expertise_descendant_section_show // bool
 * 		$expertise_descendant_query // WP_Post[]
 * 		$expertise_descendant_count // int
 * 
 * Optional vars:
 * 	// Vars defined in uamswp_fad_ontology_hide()
 * 		$hide_medical_ontology // bool
 * 	// Vars defined on the template
 * 		$expertise_section_class // Section class // string (default: 'expertise-list')
 * 		$expertise_section_id // Section ID // string (default: 'expertise')
 * 		$expertise_section_show_header // Query for whether to display the section header // bool (default: true)
 * 		$expertise_section_title // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of expertise section title in a general placement)
 * 		$expertise_section_intro // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of expertise section intro text in a general placement)
 * 		$expertise_section_collapse_list // Query for whether to collapse the list of locations in the locations section // bool (default: false)
 * 		$expertise_descendant_list // Query for whether this is a list of child areas of expertise within an area of expertise // bool (default: false)
 * 
 * Return:
 * 	html <section />
 */

// Check/define variables

	$expertise_descendant_list = isset($expertise_descendant_list) ? $expertise_descendant_list : false;
	if ( $expertise_descendant_list ) {
		$expertise_section_show = $expertise_descendant_section_show; // bool
		$expertise_query = $expertise_descendant_query; // WP_Post[]
		$expertise_count = $expertise_descendant_count; // int
	}


if ( $expertise_section_show && !$hide_medical_ontology ) {

	// Check/define variables

		$expertise_section_class = isset($expertise_section_class) ? $expertise_section_class : 'expertise-list';
		$expertise_section_id = isset($expertise_section_id) ? $expertise_section_id : 'expertise';
		$expertise_section_show_header = isset($expertise_section_show_header) ? $expertise_section_show_header : true;
		if ( !isset($expertise_section_title) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($expertise_fpage_title_general) ) {
				$fpage_text_expertise_general_vars = uamswp_fad_fpage_text_expertise_general();
					$expertise_fpage_title_general = $fpage_text_expertise_general_vars['expertise_fpage_title_general']; // string
			}
			$expertise_section_title = $expertise_fpage_title_general;
		}
		if ( !isset($expertise_section_intro) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($expertise_fpage_intro_general) ) {
				$fpage_text_expertise_general_vars = uamswp_fad_fpage_text_expertise_general();
					$expertise_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_intro_general']; // string
			}
			$expertise_section_intro = $expertise_fpage_intro_general;
		}
		$expertise_section_collapse_list = isset($expertise_section_collapse_list) ? $expertise_section_collapse_list : false;

	?>
	<section class="uams-module<?php echo $expertise_section_class ? ' ' . $expertise_section_class : ''; ?> bg-auto<?php echo $expertise_section_collapse_list ? ' collapse-list' : ''; ?>"<?php echo $expertise_section_id ? ' id="' . $expertise_section_id . '" aria-labelledby="' . $expertise_section_id . '-title"' : ''; ?>>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title<?php echo !$expertise_section_show_header ? ' sr-only' : ''; ?>"<?php echo $expertise_section_id ? ' id="' . $expertise_section_id . '-title"' : ''; ?>><span class="title"><?php echo $expertise_section_title; ?></span></h2>
					<?php if ( $expertise_section_intro ) { ?>
						<p class="note<?php echo !$expertise_section_show_header ? ' sr-only' : ''; ?>"><?php echo $expertise_section_intro; ?></p>
					<?php } // endif ( $expertise_section_intro ) ?>
					<div class="card-list-container">
						<div class="card-list card-list-expertise">
							<?php
								if ( $expertise_count > 0 ) {
									while ( $expertise_query->have_posts() ) {
										$expertise_query->the_post();
										$id = get_the_ID();
										include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
									} // endwhile
								} // endif ( $expertise_count > 0 )
								wp_reset_postdata();
							?>
						</div>
						<?php
						if ( $expertise_section_collapse_list ) { ?>
							<div class="ajax-filter-load-more">
								<button class="btn btn-lg btn-primary" aria-label="Load all <?php echo strtolower($expertise_plural_name_attr); ?>">Load All</button>
							</div>
						<?php } // endif ( $expertise_section_collapse_list ) ?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php 
} // endif ( $expertise_section_show )
?>