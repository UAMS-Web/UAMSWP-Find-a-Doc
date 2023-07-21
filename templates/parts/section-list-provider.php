<?php
/*
 * Template Name: Provider List Section
 * 
 * Description: A template part that displays a list of providers associated with 
 * the current page.
 * 
 * When this template part is needed for a hook, use the 
 * uamswp_fad_section_provider() function.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	// Defined in uamswp_fad_labels_provider()
 * 		$provider_plural_name // string
 * 		$provider_plural_name_attr // string
 * 	// Defined in uamswp_fad_fpage_text_provider_general()
 * 		$provider_fpage_title_general // string
 * 		$provider_fpage_intro_general // string
 * 	// Defined in uamswp_fad_provider_query()
 * 		$provider_section_show // bool
 * 		$provider_query // WP_Post[]
 * 		$providers // int[]
 * 		$provider_ids // int[]
 * 		$provider_count // int
 * 
 * Optional vars:
 * 	// Vars defined on the template
 * 		$provider_section_show_header // Query for whether to display the section header // bool (default: true)
 * 		$provider_section_title // Text to use for the section title // string (default: Find-a-Doc Settings value for providers section title in a general placement)
 * 		$provider_section_intro // Text to use for the section intro text // string (default: Find-a-Doc Settings value for providers section intro text in a general placement)
 * 		$provider_section_filter // Query for whether to add filter(s) // bool (default: true)
 * 		$provider_section_filter_region // Query for whether to add region filter // bool (default: true)
 * 		$provider_section_filter_title // Query for whether to add title filter // bool (default: true)
 * 		$provider_section_collapse_list // Query for whether to collapse the list of providers in the providers section // bool (default: true)
 * 
 * Return:
 * 	html <section />
 */

// Do something

if ( $provider_section_show ) {

	// Check/define variables

		// Query for whether item is ontology type vs. content type
		$ontology_type = isset($ontology_type) ? $ontology_type : true;

		// Text to use for the section title
		if ( !isset($provider_section_title) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($provider_fpage_title_general) ) {
				$fpage_text_provider_general_vars = isset($fpage_text_provider_general_vars) ? $fpage_text_provider_general_vars : uamswp_fad_fpage_text_provider_general();
					$provider_fpage_title_general = $fpage_text_provider_general_vars['provider_fpage_title_general']; // string
			}
			$provider_section_title = $provider_fpage_title_general;
		}

		// Text to use for the section intro text
		if ( !isset($provider_section_intro) ) {
			// Set the section title using the system settings for the section title in a general placement
			if ( !isset($provider_fpage_intro_general) ) {
				$fpage_text_provider_general_vars = isset($fpage_text_provider_general_vars) ? $fpage_text_provider_general_vars : uamswp_fad_fpage_text_provider_general();
					$provider_fpage_intro_general = $fpage_text_provider_general_vars['provider_fpage_intro_general']; // string
			}
			$provider_section_intro = $provider_fpage_intro_general;
		}

		// Query for whether to display the section header
		$provider_section_show_header = isset($provider_section_show_header) ? $provider_section_show_header : true;

		// Query for whether to add filter(s)
		$provider_section_filter = isset($provider_section_filter) ? $provider_section_filter : true;

		// Query for whether to add region filter
		$provider_section_filter_region = isset($provider_section_filter_region) ? $provider_section_filter_region : true;

		// Query for whether to add title filter
		$provider_section_filter_title = isset($provider_section_filter_title) ? $provider_section_filter_title : true;
		
		// Revisit filter queries
		$provider_section_filter_region = $provider_section_filter ? $provider_section_filter_region : false;
		$provider_section_filter_title = $provider_section_filter ? $provider_section_filter_title : false;
		$provider_section_filter = ( $provider_section_filter && ( $provider_section_filter_region || $provider_section_filter_title ) ) ? $provider_section_filter : false; // Set as false if neither of the filter types is true

		// Query for whether to collapse the list of providers in the providers section
		$provider_section_collapse_list = isset($provider_section_collapse_list) ? $provider_section_collapse_list : true;

		// Other variables

			if ( !isset($provider_plural_name) ) {
				$labels_provider_vars = isset($labels_provider_vars) ? $labels_provider_vars : uamswp_fad_labels_provider();
					$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string
			}

			if ( !isset($provider_plural_name_attr) ) {
				$labels_provider_vars = isset($labels_provider_vars) ? $labels_provider_vars : uamswp_fad_labels_provider();
					$provider_plural_name_attr = $labels_provider_vars['provider_plural_name_attr']; // string
			}

			if ( !isset($provider_fpage_title_general) ) {
				$fpage_text_provider_general_vars = isset($fpage_text_provider_general_vars) ? $fpage_text_provider_general_vars : uamswp_fad_fpage_text_provider_general();
					$provider_fpage_title_general = $fpage_text_provider_general_vars['provider_fpage_title_general']; // string
			}

			if ( !isset($provider_fpage_intro_general) ) {
				$fpage_text_provider_general_vars = isset($fpage_text_provider_general_vars) ? $fpage_text_provider_general_vars : uamswp_fad_fpage_text_provider_general();
					$provider_fpage_intro_general = $fpage_text_provider_general_vars['provider_fpage_intro_general']; // string
			}

			if ( !isset($providers) ) {
				$ontology_site_values_vars = isset($ontology_site_values_vars) ? $ontology_site_values_vars : uamswp_fad_ontology_site_values();
					$providers = $ontology_site_values_vars['providers']; // int[]
			}

			if ( !isset($provider_query) ) {
				$provider_query_vars = isset($provider_query_vars) ? $provider_query_vars : uamswp_fad_provider_query( $providers );
					$provider_query = $provider_query_vars['provider_query']; // WP_Post[]
			}

			if ( !isset($provider_section_show) ) {
				$provider_query_vars = isset($provider_query_vars) ? $provider_query_vars : uamswp_fad_provider_query( $providers );
					$provider_section_show = $provider_query_vars['provider_section_show']; // bool
			}

			if ( !isset($provider_ids) ) {
				$provider_query_vars = isset($provider_query_vars) ? $provider_query_vars : uamswp_fad_provider_query( $providers );
					$provider_ids = $provider_query_vars['provider_ids']; // int[]
			}

			if ( !isset($provider_count) ) {
				$provider_query_vars = isset($provider_query_vars) ? $provider_query_vars : uamswp_fad_provider_query( $providers );
					$provider_count = $provider_query_vars['provider_count']; // int
			}

	// Filter details
	if ( $provider_section_filter ) {

		// Set the AJAX filter shortcode name

			if (
				$provider_section_filter_region // Filter by region
				&&
				$provider_section_filter_title // Filter by title
			) {

				$provider_section_filter_ajax = 'uamswp_provider_ajax_filter';

			} elseif (
				$provider_section_filter_title // Filter by title
			) {

				$provider_section_filter_ajax = 'uamswp_provider_title_ajax_filter';

			} elseif (
				$provider_section_filter_region // Filter by region
			) {

				$provider_section_filter_ajax = '';

			} else {
				$provider_section_filter_ajax = '';
			}

			$provider_section_filter = $provider_section_filter_ajax ? $provider_section_filter : false; // If no AJAX filter shortcode name, then set disable filtering

		// Region filter details
		if ( $provider_section_filter_region ) {

			// Get all available regions (all available, since no titles set on initial load)

				// Get the list of region IDs from the providers
				$provider_region_IDs = array();
				while ( $provider_query->have_posts() ) {
					$provider_query->the_post();
					$id = get_the_ID();
					$provider_region_return = get_field('physician_region', $id);
					if ( is_array( $provider_region_IDs ) ) {
						$provider_region_IDs = array_merge($provider_region_IDs, $provider_region_return);
					} else {
						$provider_region_IDs[] = $provider_region_return;
					}
				} // endwhile
				$provider_region_IDs = array_unique($provider_region_IDs); // Remove duplicate values from an array

				// Get the list of region slugs from the region IDs
				$provider_region_list = array();
				foreach ( $provider_region_IDs as $provider_region_ID ) {
					$provider_region_list[] = get_term_by( 'ID', $provider_region_ID, 'region' )->slug;
				}

			// If region cookie is set, run a modified query for providers
			if (
				isset($_COOKIE['wp_filter_region'])
				||
				isset($_GET['_filter_region'])
			) {

				$provider_region = isset($_GET['_filter_region']) ? $_GET['_filter_region'] : $_COOKIE['wp_filter_region'];

				// Construct the tax_query array
				$tax_query = array();
				if ( !empty($provider_region) ) {
					$tax_query[] = array(
						'taxonomy' => 'region',
						'field' => 'slug',
						'terms' => $provider_region
					);
				}

				// Construct the query arguments
				$args = array(
					'post_type' => 'provider',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'orderby' => 'title',
					'order' => 'ASC',
					'fields' => 'ids',
					'post__in' => $providers,
					'tax_query' => $tax_query
				);

				// The Query
				$provider_query = New WP_Query( $args );

				// Get a new list of provider IDs
				$provider_ids = $provider_query->posts;

			} // endif isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region'])

		} // endif ( $provider_section_filter_region )

		// Count the number of providers in the query
		$provider_count = count($provider_query->posts);

	} // endif ( $provider_section_filter )

	?>
	<section class="uams-module bg-auto<?php echo $provider_section_collapse_list ? ' collapse-list' : ''; ?>" id="providers">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title<?php echo !$provider_section_show_header ? ' sr-only' : ''; ?>"><span class="title"><?php echo $provider_section_title; ?></span></h2>
					<?php if ( $provider_section_intro ) { ?>
						<p class="note<?php echo !$provider_section_show_header ? ' sr-only' : ''; ?>"><?php echo $provider_section_intro; ?></p>
					<?php } // endif ( $provider_section_intro )
					if ( $provider_section_filter ) {
						echo do_shortcode( '[' . $provider_section_filter_ajax . ' providers="'. implode(",", $provider_ids) .'"]' );
					} // endif ( $provider_section_filter ) ?>
					<div class="card-list-container">
						<div class="card-list card-list-doctors">
							<?php
								if ( $provider_count > 0 ) {
									$title_list = $provider_section_filter_title ? array() : '';
									while ( $provider_query->have_posts() ) {
										$provider_query->the_post();
										$id = get_the_ID();
										include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
										if ( $provider_section_filter_title ) {
											$title_list[] = get_field('physician_title', $id);
										}
									} // endwhile
									if ( $provider_section_filter ) {
										echo '<data id="provider_ids" data-postids="' . implode(',', $provider_query->posts) . ',"' . ( $provider_section_filter_region ? ' data-regions="' . implode(',', $provider_region_list) . ',"' : '' ) . ( $provider_section_filter_title ? ' data-titles="' . implode(',', array_unique($title_list)) . ',"' : '' ) . '></data>';
									}
								} else {
									if ( $provider_section_filter ) {
										echo '<span class="no-results">Sorry, there are no ' . strtolower($provider_plural_name) . ' matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
									}
								} // endif ( $provider_count > 0 )
								wp_reset_postdata();
							?>
						</div>
					</div>
					<?php
					if ( $provider_section_collapse_list ) { ?>
						<div class="ajax-filter-load-more">
							<button class="btn btn-lg btn-primary" aria-label="Load all <?php echo strtolower($provider_plural_name_attr); ?>">Load All</button>
						</div>
					<?php } // endif ( $provider_section_collapse_list ) ?>
				</div>
			</div>
		</div>
		<?php if ( $provider_section_filter_region && isset($_GET['_filter_region']) ) { ?>
			<script type="text/javascript">
				// Set cookie to expire at end of session
				document.cookie = "wp_filter_region=<?php echo htmlspecialchars($_GET['_filter_region']); ?>; path=/; domain="+window.location.hostname;
			</script>
		<?php } ?>
	</section>
<?php
} // endif ( $provider_section_show )
?>