<?php
/*
 * Template Name: Fake Area of Expertise Locations Subpage
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	uamswp_fad_labels_provider();

	// Get system settings for location labels
	uamswp_fad_labels_location();

	// Get system settings for area of expertise labels
	uamswp_fad_labels_expertise();

	// Get system settings for area of expertise descendant item labels
	uamswp_fad_labels_expertise_descendant();

	// Get system settings for clinical resource labels
	uamswp_fad_labels_clinical_resource();

	// Get system settings for condition labels
	uamswp_fad_labels_conditions();

	// Get system settings for treatment labels
	uamswp_fad_labels_treatments();

// Get system settings for fake subpage text elements on Area of Expertise subsection
uamswp_fad_fpage_text_expertise();

// Set general variables
$page_id = get_the_ID();
$page_title = get_the_title(); // Title of Area of Expertise
$page_title_attr = uamswp_attr_conversion($page_title);
$page_url = get_permalink();
$fpage_name = $location_plural_name; // Name of ontology item type represented by this fake subpage
$fpage_name_attr = uamswp_attr_conversion($fpage_name);
$fpage_title = $location_fpage_title_expertise; // Fake subpage page title pre-substitution
$fpage_title = uamswp_fad_fpage_text_replace($fpage_title); // Fake subpage page title post-substitution
$fpage_title_attr = uamswp_attr_conversion($fpage_title);
$current_fpage = get_query_var('fpage'); // Fake subpage slug
$fpage_url = !empty($current_fpage) ? $page_url . user_trailingslashit($current_fpage) : $page_url; // Fake subpage URL

// Area of Expertise Content Type
$ontology_type = get_field('expertise_type'); // True is ontology type, false is content type
$ontology_type = isset($ontology_type) ? $ontology_type : 1; // Check if 'expertise_type' is not null, and if so, set value to true

// Get site header and site nav values for ontology subsections
uamswp_fad_ontology_site_values();

// Queries for whether each of the associated ontology content sections should be displayed on ontology pages/subsections

	// Query for whether associated providers content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_providers_query();

	// Query for whether associated locations content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_locations_query();

	// Query for whether descendant ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_descendants_query();

	// Query for whether related ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_related_query();

	// Query for whether associated clinical resources content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_resources_query();

	// Query for whether associated conditions content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_conditions_query();

	// Query for whether associated treatments content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_treatments_query();

// Override theme's method of defining the meta page title
add_filter('seopress_titles_title', 'uamswp_fad_fpage_title', 15, 2);

// Add meta keywords
add_action( 'wp_head', 'uamswp_expertise_header_metadata' );
$keywords = get_field('expertise_alternate_names');

// Modify site header

	// Remove the site header set by the theme
	remove_action( 'genesis_header', 'uamswp_site_image', 5 );

	// Add ontology subsection site header
	add_action( 'genesis_header', 'uamswp_fad_ontology_header', 5 );

// Modify primary navigation

	// Remove the primary navigation set by the theme
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

	// Add ontology subsection primary navigation
	add_action( 'genesis_after_header', 'uamswp_fad_ontology_nav_menu', 5 );

// Add page template class to body element's classes
add_filter( 'body_class', 'uamswp_page_body_class' );
$template_type = 'page_landing';

// Add fake subpage to breadcrumbs
add_filter('seopress_pro_breadcrumbs_crumbs', 'uamswp_fad_fpage_breadcrumbs');

// Add bg-white class to article.entry element
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

// Modify Entry Title

	// Remove Genesis-standard post title and markup
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

	// Construct non-standard post title
	add_action( 'genesis_before_content', 'uamswp_fad_post_title' );
	$entry_header_style = 'graphic'; // Entry header style
	$entry_title_text = $fpage_title; // Regular title
	$entry_title_text_supertitle = ''; // Optional supertitle
	$entry_title_text_subtitle = ''; // Optional subtitle
	$entry_title_text_body = ''; // Optional lead paragraph
	$entry_title_image_desktop = ''; // Desktop breakpoint image ID
	$entry_title_image_mobile = ''; // Optional mobile breakpoint image ID

// Remove the post info (byline) from the entry header and the entry footer
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php

// Remove the entry meta (tags) from the entry footer, including markup
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

// Construct page content

	// Remove content
	remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

	// Display ontology page content
	add_action( 'genesis_entry_content', 'uamswp_expertise_locations', 22 );
	function uamswp_expertise_locations() {
		// Bring in variables from outside of the function
		global $show_locations_section; // Defined in uamswp_fad_ontology_locations_query()
		global $location_query; // Defined in uamswp_fad_ontology_locations_query()
		global $locations; // Defined in uamswp_fad_ontology_locations_query()
		global $location_single_name; // Defined in uamswp_fad_labels_location()
		global $location_single_name_attr; // Defined in uamswp_fad_labels_location()
		global $location_plural_name; // Defined in uamswp_fad_labels_location()

		// Do something
		if ( $show_locations_section ) { 
			$location_ids = $location_query->posts;

			$location_region_IDs = array();
			foreach($location_ids as $location_id) {
				$location_region_IDs[] = get_field('location_region', $location_id);
			}
			// endwhile;
			$location_region_IDs = array_unique($location_region_IDs);
			$location_region_list = array();
			foreach ($location_region_IDs as $location_region_ID){
				$location_region_list[] = get_term_by( 'ID', $location_region_ID, 'region' )->slug;
			}

			// if cookie is set, run modified physician query
			if ( isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region']) ) {

				$location_region = '';
				if( isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region']) ) {
					$location_region = isset($_GET['_filter_region']) ? $_GET['_filter_region'] : $_COOKIE['wp_filter_region'];
				}

				$tax_query = array();
				if(!empty($location_region)) {
					$tax_query[] = array(
						'taxonomy' => 'region',
						'field' => 'slug',
						'terms' => $location_region
					);
				}
				$args = array(
					'post_type' => "location",
					'post_status' => 'publish',
					'order' => 'ASC',
					'orderby' => 'title',
					'posts_per_page' => -1,
					'fields' => 'ids',
					'no_found_rows' => true, // counts posts, remove if pagination required
					'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
					'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
					'post__in'	=> $locations,
					'tax_query' => $tax_query
				);
				$location_query = New WP_Query( $args );
			}

			?>
			<section class="uams-module location-list bg-auto" id="locations">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<h2 class="module-title"><span class="title"><?php echo $location_plural_name; ?></span></h2>
							<?php echo do_shortcode( '[uamswp_location_ajax_filter locations="'. implode(",", $location_ids) .'"]' ); ?>
							<div class="card-list-container location-card-list-container">
								<div class="card-list card-list-locations">
								<?php
								if ($location_query->have_posts()){
									while ( $location_query->have_posts() ) : $location_query->the_post();
										$id = get_the_ID();
										include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
									endwhile;
									echo '<data id="location_ids" data-postids="'. implode(',', $location_query->posts) .'," data-regions="'. implode(',', $location_region_list) .',"></data>';
								} else {
									echo '<span class="no-results">Sorry, there are no ' . strtolower($location_plural_name) . ' matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
								}
								wp_reset_postdata();?>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php 
		} // endif
	}

	// Display appointment information
	add_action( 'genesis_entry_content', 'uamswp_fad_ontology_appointment', 26 );
	// Check if Make an Appointment section should be displayed
	$show_appointment_section = true; // It should always be displayed.

genesis();