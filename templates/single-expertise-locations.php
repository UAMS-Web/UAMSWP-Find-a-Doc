<?php
/*
 * Template Name: Fake Area of Expertise Locations Subpage
 */

// Set general variables
$page_id = get_the_ID();
$page_title = get_the_title(); // Title of Area of Expertise
$page_title_attr = uamswp_attr_conversion($page_title);
$fpage_name = 'Locations'; // Fake subpage name
$fpage_name_attr = uamswp_attr_conversion($fpage_name);
$fpage_title = $page_title . ' ' . $fpage_name; // Fake subpage page title
$fpage_title_attr = uamswp_attr_conversion($fpage_title);
$page_url = get_permalink();
$expertise_archive_title = get_field('expertise_archive_headline', 'option') ?: 'Areas of Expertise';
$expertise_archive_title_attr = uamswp_attr_conversion($expertise_archive_title);
$expertise_single_name = get_field('expertise_archive_headline', 'option') ?: 'Area of Expertise';
$expertise_single_name_attr = uamswp_attr_conversion($expertise_single_name);

// Area of Expertise Content Type
$ontology_type = get_field('expertise_type'); // True is ontology type, false is content type

// Get site header and site nav values for ontology subsections
uamswp_fad_ontology_site_values();

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
add_filter( 'body_class', 'uams_default_page_body_class' );
function uams_default_page_body_class( $classes ) {
	$classes[] = 'page-template-default';
	return $classes;
}

// Override theme's method of defining the breadcrumbs
add_filter('seopress_pro_breadcrumbs_crumbs', 'uamswp_breadcrumbs_expertise');
function uamswp_breadcrumbs_expertise($crumbs) {
	$crumbs[] = array('Locations', '');
	return $crumbs;
}

// Add extra class to entry
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );
function uamswp_add_entry_class( $attributes ) {
	$attributes['class'] = $attributes['class']. ' bg-white';
	return $attributes;
}

// Modify Entry Title

	// Remove Genesis-standard post title
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

	// Add post title for ontology subsection fake subpages
	add_action( 'genesis_entry_header', 'uamswp_fad_fpage_post_title' );

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
	add_action( 'genesis_after_entry', 'uamswp_expertise_locations', 22 );
	function uamswp_expertise_locations() {
		global $show_locations_section;
		global $location_query;
		global $locations;
	
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
							<h2 class="module-title"><span class="title">Locations</span></h2>
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
									echo '<span class="no-results">Sorry, there are no locations matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
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
	add_action( 'genesis_after_entry', 'uamswp_expertise_appointment', 26 );
	// Check if Make an Appointment section should be displayed
	$show_appointment_section = true; // It should always be displayed.
	function uamswp_expertise_appointment() {
		global $show_appointment_section;
		if ( $show_appointment_section ) {
			if ( get_field('location_expertise') ) {
				$appointment_location_url = '#locations';
				//$appointment_location_label = 'Go to the list of relevant locations';
			} else {
				$appointment_location_url = '/location/';
				//$appointment_location_label = 'View a list of UAMS Health locations';
			} ?>
			<section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<h2>Make an Appointment</h2>
							<p>Request an appointment by <a href="<?php echo $appointment_location_url; ?>" data-itemtitle="Contact a clinic directly">contacting a clinic directly</a> or by calling the UAMS&nbsp;Health appointment line at <a href="tel:501-686-8000" class="no-break" data-itemtitle="Call the UAMS Health appointment line">(501) 686-8000</a>.</p>
						</div>
					</div>
				</div>
			</section>
		<?php }
	}

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

genesis();