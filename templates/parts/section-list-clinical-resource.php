<?php
/*
 * Template Name: Clinical Resource List Section
 * 
 * Description: A template part that displays a list of clinical resources 
 * associated with the current page.
 * 
 * When this template part is needed for a hook, use the 
 * uamswp_fad_section_clinical_resource() function.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	// Vars defined on the template
 * 		$clinical_resources // int[]
 * 		$clinical_resource_posts_per_page // int
 * 
 * Optional vars:
 * 	// Vars defined on the template
 * 		$clinical_resource_section_class // Section class // string (default: '')
 * 		$clinical_resource_section_id // Section ID // string (default: 'related-resources')
 * 		$clinical_resource_section_show_header // Query for whether to display the section header // bool (default: true)
 * 		$clinical_resource_section_title // Text to use for the section title // string (default: Find-a-Doc Settings value for clinical resources section title in a general placement)
 * 		$clinical_resource_section_intro // Text to use for the section intro text // string (default: Find-a-Doc Settings value for clinical resources section intro text in a general placement)
 * 		$clinical_resource_section_more_show // Query for whether to show the section that links to more items // bool (default: true)
 * 		$clinical_resource_section_more_link_key // string
 * 		$clinical_resource_section_more_link_value // string
 * 		$clinical_resource_section_more_text // Text to use for the "more" intro text // string (default: Find-a-Doc Settings value for clinical resources section "more" intro text in a general placement)
 * 		$clinical_resource_section_more_link_text // Text to use for the "more" link text // string (default: Find-a-Doc Settings value for clinical resources section "more" link text in a general placement)
 * 		$clinical_resource_section_more_link_descr // Text to use for the "more" link description // string (default: Find-a-Doc Settings value for clinical resources section "more" link description in a general placement)
 * 		$clinical_resource_section_collapse_list // Query for whether to collapse the list of locations in the locations section // bool (default: false)
 * 
 * Return:
 * 	html <section />
 */

// Check/define variables

	// Number of clinical resources to display

	if ( !isset($clinical_resource_posts_per_page) ) {
		if ( !isset($clinical_resource_posts_per_page_section) ) {
			$posts_per_page_clinical_resource_general_vars = isset($posts_per_page_clinical_resource_general_vars) ? $posts_per_page_clinical_resource_general_vars : uamswp_fad_posts_per_page_clinical_resource_general();
				$clinical_resource_posts_per_page_section = $posts_per_page_clinical_resource_general_vars['clinical_resource_section_posts_per_page']; // int
		}
		$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_section;
	}

	$jump_link_count = isset($jump_link_count) ? $jump_link_count : 0;
	$clinical_resource_query_vars = isset($clinical_resource_query_vars) ? $clinical_resource_query_vars : uamswp_fad_clinical_resource_query(
		$clinical_resources,
		$clinical_resource_posts_per_page,
		$jump_link_count
	);
		$jump_link_count = $clinical_resource_query_vars['jump_link_count']; // int

	if ( !isset($clinical_resource_section_show) ) {
		$clinical_resource_query_vars = isset($clinical_resource_query_vars) ? $clinical_resource_query_vars : uamswp_fad_clinical_resource_query(
			$clinical_resources,
			$clinical_resource_posts_per_page,
			$jump_link_count
		);
			$clinical_resource_section_show = $clinical_resource_query_vars['clinical_resource_section_show']; // bool
	}

// Do something

if ( $clinical_resource_section_show ) {

	// Check/define variables

		// List of Clinical Resourcse

			if (
				!isset($clinical_resource_query)
				||
				!isset($clinical_resource_count)
			) {
				$clinical_resource_query_vars = isset($clinical_resource_query_vars) ? $clinical_resource_query_vars : uamswp_fad_clinical_resource_query(
					$clinical_resources,
					$clinical_resource_posts_per_page,
					$jump_link_count
				);
					$clinical_resource_query = $clinical_resource_query_vars['clinical_resource_query']; // WP_Post[]
					$clinical_resource_count = $clinical_resource_query_vars['clinical_resource_count']; // int
			}

		// Query for whether item is ontology type vs. content type
		$ontology_type = isset($ontology_type) ? $ontology_type : true;

		// Text to use for the section title
		if ( !isset($clinical_resource_section_title) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($clinical_resource_fpage_title_general) ) {
				$fpage_text_clinical_resource_general_vars = isset($fpage_text_clinical_resource_general_vars) ? $fpage_text_clinical_resource_general_vars : uamswp_fad_fpage_text_clinical_resource_general();
					$clinical_resource_fpage_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_title_general']; // string
			}
			$clinical_resource_section_title = $clinical_resource_fpage_title_general;
		}

		// Text to use for the section intro text
		if ( !isset($clinical_resource_section_intro) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($clinical_resource_fpage_intro_general) ) {
				$fpage_text_clinical_resource_general_vars = isset($fpage_text_clinical_resource_general_vars) ? $fpage_text_clinical_resource_general_vars : uamswp_fad_fpage_text_clinical_resource_general();
					$clinical_resource_fpage_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_intro_general']; // string
			}
			$clinical_resource_section_intro = $clinical_resource_fpage_intro_general;
		}

		// Query for whether to show the section that links to more items
		if ( !isset($clinical_resource_section_more_show) ) {
			$clinical_resource_section_more_show = ( $clinical_resource_count > $clinical_resource_posts_per_page ) ? true : false;
		}

		if ( $clinical_resource_section_more_show ) {

			$clinical_resource_section_more_link_key = isset($clinical_resource_section_more_link_key) ? $clinical_resource_section_more_link_key : '';
			$clinical_resource_section_more_link_value = isset($clinical_resource_section_more_link_value) ? $clinical_resource_section_more_link_value : '';

			$clinical_resource_section_more_link_url = '/clinical-resource/';
			if ( $clinical_resource_section_more_link_key && $clinical_resource_section_more_link_value ) {
				$clinical_resource_section_more_link_url .= '?' . $clinical_resource_section_more_link_key . '=' . $clinical_resource_section_more_link_value;
			}
			$clinical_resource_section_more_link_target = '_blank';

			// Text to use for the "more" intro text
			if ( !isset($clinical_resource_section_more_text) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($clinical_resource_fpage_more_text_general) ) {
					$fpage_text_clinical_resource_general_vars = isset($fpage_text_clinical_resource_general_vars) ? $fpage_text_clinical_resource_general_vars : uamswp_fad_fpage_text_clinical_resource_general();
						$clinical_resource_fpage_more_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_text_general']; // string
				}
				$clinical_resource_section_more_text = $clinical_resource_fpage_more_text_general;
			}

			// Text to use for the "more" link text
			if ( !isset($clinical_resource_section_more_link_text) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($clinical_resource_fpage_more_link_text_general) ) {
					$fpage_text_clinical_resource_general_vars = isset($fpage_text_clinical_resource_general_vars) ? $fpage_text_clinical_resource_general_vars : uamswp_fad_fpage_text_clinical_resource_general();
						$clinical_resource_fpage_more_link_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_text_general']; // string
				}
				$clinical_resource_section_more_link_text = $clinical_resource_fpage_more_link_text_general;
			}

			// Text to use for the "more" link description
			if ( !isset($clinical_resource_section_more_link_descr) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($clinical_resource_fpage_more_link_descr_general) ) {
					$fpage_text_clinical_resource_general_vars = isset($fpage_text_clinical_resource_general_vars) ? $fpage_text_clinical_resource_general_vars : uamswp_fad_fpage_text_clinical_resource_general();
						$clinical_resource_fpage_more_link_descr_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_descr_general']; // string
				}
				$clinical_resource_section_more_link_descr = $clinical_resource_fpage_more_link_descr_general;
			}
			$clinical_resource_section_more_link_descr_attr = uamswp_attr_conversion($clinical_resource_section_more_link_descr);

		} else {

				// Text to use for the "more" intro text
				$clinical_resource_section_more_text = '';

				// Text to use for the "more" link text
				$clinical_resource_section_more_link_text = '';

				// Text to use for the "more" link description
				$clinical_resource_section_more_link_descr = '';
				$clinical_resource_section_more_link_descr_attr = '';

		}

		// Query for whether to display the section header
		$clinical_resource_section_show_header = isset($clinical_resource_section_show_header) ? $clinical_resource_section_show_header : true;

		// Query for whether to collapse the list of locations in the locations section
		$clinical_resource_section_collapse_list = isset($clinical_resource_section_collapse_list) ? $clinical_resource_section_collapse_list : false;

		// Section class
		$clinical_resource_section_class = isset($clinical_resource_section_class) ? $clinical_resource_section_class : '';

		// Section ID
		$clinical_resource_section_id = isset($clinical_resource_section_id) ? $clinical_resource_section_id : 'related-resources';

		// Labels

			if ( !isset($clinical_resource_plural_name_attr) ) {
				$labels_clinical_resource_vars = isset($labels_clinical_resource_vars) ? $labels_clinical_resource_vars : uamswp_fad_labels_clinical_resource();
					$clinical_resource_plural_name_attr = $labels_clinical_resource_vars['clinical_resource_plural_name_attr']; // string
			}

	?>
	<section class="uams-module<?php echo $clinical_resource_section_class ? ' ' . $clinical_resource_section_class : ''; ?> bg-auto<?php echo $clinical_resource_section_collapse_list ? ' collapse-list' : ''; ?>"<?php echo $clinical_resource_section_id ? ' id="' . $clinical_resource_section_id . '" aria-labelledby="' . $clinical_resource_section_id . '-title"' : ''; ?>>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title<?php echo !$clinical_resource_section_show_header ? ' sr-only' : ''; ?>"<?php echo $clinical_resource_section_id ? ' id="' . $clinical_resource_section_id . '-title"' : ''; ?>><span class="title"><?php echo $clinical_resource_section_title; ?></span></h2>
					<?php if ( $clinical_resource_section_intro ) { ?>
						<p class="note<?php echo !$clinical_resource_section_show_header ? ' sr-only' : ''; ?>"><?php echo $clinical_resource_section_intro; ?></p>
					<?php } // endif ( $clinical_resource_section_intro ) ?>
					<div class="card-list-container">
						<div class="card-list card-list-clinical-resource">
							<?php
								if ( $clinical_resource_count > 0 ) {
									while ( $clinical_resource_query->have_posts() ) {
										$clinical_resource_query->the_post();
										include( UAMS_FAD_PATH . '/templates/loops/resource-card-single.php' );
									} // endwhile
								} // endif ( $clinical_resource_count > 0 )
								wp_reset_postdata();
							?>
						</div>
						<?php
						if ( $clinical_resource_section_collapse_list ) { ?>
							<div class="ajax-filter-load-more">
								<button class="btn btn-lg btn-primary" aria-label="Load all <?php echo strtolower($clinical_resource_plural_name_attr); ?>">Load All</button>
							</div>
						<?php } // endif ( $clinical_resource_section_collapse_list ) ?>
					</div>
					<?php if ( $clinical_resource_section_more_show ) { ?>
						<div class="col-12 more">
							<p class="lead"><?php echo $clinical_resource_section_more_text; ?></p>
							<div class="cta-container">
								<a href="<?php echo $clinical_resource_section_more_link_url; ?>" class="btn btn-outline-primary" aria-label="<?php echo $clinical_resource_section_more_link_descr_attr; ?>"<?php $clinical_resource_section_more_link_target ? ' target="'. $clinical_resource_section_more_link_target . '"' : '' ?>><?php echo $clinical_resource_section_more_link_text; ?></a>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</section>
<?php 
} // endif ( $clinical_resource_section_show )
?>