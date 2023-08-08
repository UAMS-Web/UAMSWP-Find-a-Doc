<?php
/*
 * Template Name: Clinical Resource List Section
 * 
 * Description: A template part that displays a list of clinical resources 
 * associated with the current page.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	$page_id // int // ID of the current page
 * 	$clinical_resources // int[] // Value of the related clinical resources input (or $clinical_resource_descendants, List of this clinical resource item's descendant items)
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 * 
 * Optional vars:
 * 	$clinical_resource_section_more_link_key // string
 * 	$clinical_resource_section_more_link_value // string
 * 	$clinical_resource_section_show // bool // Query for whether to show the clinical resource section (or $clinical_resource_descendant_section_show, Query for whether to show the descendant clinical resource section)
 * 	$ontology_type // bool // Query for whether item is ontology type vs. content type
 * 	$clinical_resource_section_title // string (default: Find-a-Doc Settings value for clinical resources section title in a general placement) // Text to use for the section title
 * 	$clinical_resource_section_intro // string (default: Find-a-Doc Settings value for clinical resources section intro text in a general placement) // Text to use for the section intro text
 * 	$clinical_resource_posts_per_page // int enum(-1, 4, 6, 8, 10, 12) // Maximum number of clinical resources to display
 * 	$clinical_resource_section_more_show // bool (default: true) // Query for whether to show the section that links to more items
 * 	$clinical_resource_section_more_text // string (default: Find-a-Doc Settings value for clinical resources section "more" intro text in a general placement) // Text to use for the "more" intro text
 * 	$clinical_resource_section_more_link_text // string (default: Find-a-Doc Settings value for clinical resources section "more" link text in a general placement) // Text to use for the "more" link text
 * 	$clinical_resource_section_more_link_descr // string (default: Find-a-Doc Settings value for clinical resources section "more" link description in a general placement) // Text to use for the "more" link description
 * 	$clinical_resource_section_show_header // bool (default: true) // Query for whether to display the section header
 * 	$clinical_resource_section_collapse_list // bool (default: false) // Query for whether to collapse the list of locations in the locations section
 * 	$clinical_resource_section_class // string (default: '') // Section class
 * 	$clinical_resource_section_id // string (default: 'related-resources') // Section ID
 * 	$jump_link_count // int (default: 0)
	$clinical_resource_query // WP_Post[]
	$clinical_resource_count // int
 * 
 * Return:
 * 	html <section />
 */

// Check/define variables

	// Number of clinical resources to display

		if ( !isset($clinical_resource_posts_per_page) || empty($clinical_resource_posts_per_page) ) {

			include( UAMS_FAD_PATH . '/templates/parts/vars/sys/posts-per-page/clinical-resource.php' ); // General maximum number of clinical resource items to display on a fake subpage (or section)
			$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_section;
		}

	// Jump link count
	$jump_link_count = isset($jump_link_count) ? $jump_link_count : 0;

	// Related Clinical Resources Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/clinical-resource.php' );

// Do something

	if ( $clinical_resource_section_show ) {

		// Check/define variables

			// Related Clinical Resources Section Query
			include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/clinical-resource.php' );

			// Query for whether item is ontology type vs. content type
			$ontology_type = isset($ontology_type) ? $ontology_type : true;

			// Text to use for the section title

				if ( !isset($clinical_resource_section_title) ) {

					// Set the section title using the system settings for the section title in a general placement

						// Get the system settings for general placement of clinical resource item text elements
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/clinical-resource.php' );
	
						$clinical_resource_section_title = $clinical_resource_fpage_title_general;
				}

			// Text to use for the section intro text

				if ( !isset($clinical_resource_section_intro) ) {

					// Set the section title using the system settings for the section title in a general placement

						// Get the system settings for general placement of clinical resource item text elements
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/clinical-resource.php' );
	
						$clinical_resource_section_intro = $clinical_resource_fpage_intro_general;
				}

			// Query for whether to show the section that links to more items

				if ( !isset($clinical_resource_section_more_show) ) {

					$clinical_resource_section_more_show = ( $clinical_resource_count > $clinical_resource_posts_per_page ) ? true : false;

				}

				if ( $clinical_resource_section_more_show ) {

					$clinical_resource_section_more_link_key = isset($clinical_resource_section_more_link_key) ? $clinical_resource_section_more_link_key : '';
					$clinical_resource_section_more_link_value = isset($clinical_resource_section_more_link_value) ? $clinical_resource_section_more_link_value : '';

					$clinical_resource_section_more_link_url = user_trailingslashit('/clinical-resource/');

					if ( $clinical_resource_section_more_link_key && $clinical_resource_section_more_link_value ) {

						$clinical_resource_section_more_link_url .= '?' . $clinical_resource_section_more_link_key . '=' . $clinical_resource_section_more_link_value;

					}

					$clinical_resource_section_more_link_target = '_blank';

					// Text to use for the "more" intro text

						if ( !isset($clinical_resource_section_more_text) ) {

							// Set the section title using the system settings for the section title in a general placement

								// Get the system settings for general placement of clinical resource item text elements
								include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/clinical-resource.php' );
	
								$clinical_resource_section_more_text = $clinical_resource_fpage_more_text_general;
						}

					// Text to use for the "more" link text

						if ( !isset($clinical_resource_section_more_link_text) ) {

							// Set the section title using the system settings for the section title in a general placement

								// Get the system settings for general placement of clinical resource item text elements
								include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/clinical-resource.php' );
	
								$clinical_resource_section_more_link_text = $clinical_resource_fpage_more_link_text_general;
						}

					// Text to use for the "more" link description

						if ( !isset($clinical_resource_section_more_link_descr) ) {

							// Set the section title using the system settings for the section title in a general placement

								// Get the system settings for general placement of clinical resource item text elements
								include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/clinical-resource.php' );
		
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

			// Get system settings for clinical resource labels
			include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

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
											include( UAMS_FAD_PATH . '/templates/parts/html/cards/clinical-resource.php' );
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