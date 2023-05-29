<?php
/*
 * Template Name: Fake Area of Expertise Related Areas of Expertise Subpage
 */

// Set general variables
$page_id = get_the_ID();
$page_title = get_the_title(); // Title of Area of Expertise
$page_title_attr = uamswp_attr_conversion($page_title);
$fpage_name = 'Related Areas of Expertise'; // Fake subpage title
$fpage_name_attr = uamswp_attr_conversion($fpage_name);
$fpage_title = 'Areas of Expertise Related to ' . $page_title; // Fake subpage page title
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

// Override theme's method of defining the breadcrumbs
function uamswp_breadcrumbs_expertise($crumbs) {
	$crumbs[] = array('Related Areas of Expertise', '');
	return $crumbs;
}
add_filter('seopress_pro_breadcrumbs_crumbs', 'uamswp_breadcrumbs_expertise');

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
// Removes entry meta from entry footer incl. markup.
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

function uams_default_page_body_class( $classes ) {

	$classes[] = 'page-template-default';
	return $classes;
}
add_filter( 'body_class', 'uams_default_page_body_class' );

// Add extra class to entry
function uamswp_add_entry_class( $attributes ) {
	$attributes['class'] = $attributes['class']. ' bg-white';
	return $attributes;
}
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

// Modify Entry Title
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
add_action( 'genesis_entry_header', 'uamswp_fad_fpage_post_title' );

// add_action( 'genesis_after_entry', 'uamswp_list_child_expertise', 12 );
add_action( 'genesis_after_entry', 'uamswp_expertise_associated', 24 );
add_action( 'wp_head', 'uamswp_expertise_header_metadata' );

// Remove content
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

// Check if Podcast section should be displayed
$podcast_name = get_field('expertise_podcast_name');
$podcast_name_attr = uamswp_attr_conversion($podcast_name);
if ($podcast_name) {
	$show_podcast_section = true;
} else {
	$show_podcast_section = false;
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

// Check if Make an Appointment section should be displayed
// It should always be displayed.
$show_appointment_section = true;

// Remove the primary navigation set by the theme
remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

// Add ontology subsection primary navigation
add_action( 'genesis_after_header', 'uamswp_fad_ontology_nav_menu', 5 );

remove_action( 'genesis_header', 'uamswp_site_image', 5 );
add_action( 'genesis_header', 'uamswp_fad_ontology_header', 5 );

function uamswp_expertise_cta() {
	$cta_repeater = get_field('expertise_cta');
	if( $cta_repeater ): 
		$i = 1;
		foreach( $cta_repeater as $cta ) { 
			$cta_heading = $cta['cta_bar_heading'];
			$cta_body = $cta['cta_bar_body'];
			$cta_action_type = $cta['cta_bar_action_type'];

			$cta_button_text = '';
			$cta_button_url = '';
			$cta_button_target = '';
			$cta_button_desc = '';
			if ( $cta_action_type == 'url' ) {
				$cta_button_text = $cta['cta_bar_button_text'];
				$cta_button_url = $cta['cta_bar_button_url'];
				if ( $cta_button_url ) {
					$cta_button_target = $cta_button_url['target'];
				}
				$cta_button_desc = $cta['cta_bar_button_description'];
			}

			$cta_phone_prepend = '';
			$cta_phone = '';
			$cta_phone_link = '';
			if ( $cta_action_type == 'phone' ) {
				$cta_phone_prepend = $cta['cta_bar_phone_prepend'] ? $cta['cta_bar_phone_prepend'] : 'Call';
				$cta_phone = $cta['cta_bar_phone'];
				$cta_phone_link = '<a href="tel:' . format_phone_dash( $cta_phone ) . '">' . format_phone_us( $cta_phone ) . '</a>';
			}

			$cta_layout = 'cta-bar-centered';
			$cta_size = 'normal';
			$cta_use_image = false;
			$cta_image = '';
			$cta_background_color = 'bg-auto';
			$cta_btn_color = 'primary';

			$cta_className = '';
			$cta_className .= ' ' . $cta_layout;
			$cta_className .= ' ' . $cta_background_color;
			$cta_className .= $cta_use_image ? ' bg-image' : '';
			if ( $cta_size == 'small' ) {
				$cta_className .= ' cta-bar-sm';
			} elseif ( $cta_size == 'large' ) {
				$cta_className .= ' extra-padding cta-bar-lg';
			}
			if ( $cta_action_type == 'none' ) {
				$cta_className .= ' no-link';
			}

			echo '<section class="uams-module cta-bar' . $cta_className . '" id="cta-bar-' . $i . '" aria-label="' . $cta_heading . '">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<div class="inner-container">
								<div class="cta-heading">
									<h2>' . $cta_heading . '</h2>
								</div>
								<div class="cta-body">
									<div class="text-container">
										' . $cta_body . '
									</div>';
									echo $cta_action_type == 'url' ?
									'<div class="btn-container">
										<a href="' . $cta_button_url['url'] . '" aria-label="' . $cta_button_desc . '" class=" btn btn-' . $cta_btn_color . ( $cta_size == 'large' ? ' btn-lg' : '' ) . '"' . ( $cta_button_target ? ' target="'. $cta_button_target . '"' : '' ) . ' data-moduletitle="' . $cta_heading . '">' . $cta_button_text . '</a>
									</div>'
									: '';
									echo $cta_action_type == 'phone' ?
									'<div class="btn-container">
										<a href="tel:' . $cta_phone . '" data-moduletitle="' . $cta_heading . '">' . $cta_phone_prepend . ' <span class="no-break">' . $cta_phone . '</span></a>
									</div>'
									: '';
								echo '</div>
							</div>
						</div>
					</div>
				</div>
			</section>';
			$i++;
		}
	endif;
}

function uamswp_expertise_physicians() {
	global $show_providers_section;
	//global $postsCountClass;
	global $physicians_query;
	//global $postsPerPage;
	global $physicians;
	global $provider_ids;


	if($show_providers_section) {

		// Get available regions - All available, since no titles set on initial load
		$region_IDs = array();
		while ($physicians_query->have_posts()) : $physicians_query->the_post();
			$id = get_the_ID();
			$region_IDs = array_merge($region_IDs, get_field('physician_region', $id));
		endwhile;
		$region_IDs = array_unique($region_IDs);
		$region_list = array();
		foreach ($region_IDs as $region_ID){
			$region_list[] = get_term_by( 'ID', $region_ID, 'region' )->slug;
		}

		// if cookie is set, run modified physician query
		if ( isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region']) ) {		

			$provider_region = '';
			if( isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region']) ) {
				$provider_region = isset($_GET['_filter_region']) ? $_GET['_filter_region'] : $_COOKIE['wp_filter_region'];
			}

			$tax_query = array();
			if(!empty($provider_region)) {
				$tax_query[] = array(
					'taxonomy' => 'region',
					'field' => 'slug',
					'terms' => $provider_region
				);
			}
			$args = array(
				"post_type" => "provider",
				"post_status" => "publish",
				"posts_per_page" => -1,
				"orderby" => "title",
				"order" => "ASC",
				"fields" => "ids",
				"post__in" => $physicians,
				'tax_query' => $tax_query
			);
			$physicians_query = New WP_Query( $args );
		}
		$provider_count = count($physicians_query->posts);
		?>
		<section class="uams-module bg-auto" id="providers">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title"><span class="title">Providers</span></h2>
						<?php echo do_shortcode( '[uamswp_provider_ajax_filter providers="'. implode(",", $provider_ids) .'"]' ); ?>
						<div class="card-list-container">
							<div class="card-list card-list-doctors">
								<?php 
									if($provider_count > 0){
										$title_list = array();
										while ($physicians_query->have_posts()) : $physicians_query->the_post();
											$id = get_the_ID();
											include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
											$title_list[] = get_field('physician_title', $id);
										endwhile;
										echo '<data id="provider_ids" data-postids="'. implode(',', $physicians_query->posts) .'," data-regions="'. implode(',', $region_list) .'," data-titles="'. implode(',', array_unique($title_list)) .',"></data>';
									} else {
										echo '<span class="no-results">Sorry, there are no providers matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
									}
									wp_reset_postdata();
								?>
							</div>
						</div>
						<!-- <div class="more" style="<?php //echo ($postsPerPage < $provider_count) ? '' : 'display:none;' ; ?>">
							<button class="loadmore btn btn-primary <?php //echo $provider_count; ?>" data-ppp="<?php //echo $postsPerPage; ?>" aria-label="Load more providers">Load More</button>
						</div> -->
						<div class="ajax-filter-load-more">
							<button class="btn btn-lg btn-primary" aria-label="Load all providers">Load All</button>
						</div>
					</div>
				</div>
			</div>
			<?php if ( isset($_GET['_filter_region']) ) { ?>
				<script type="text/javascript">
					// Set cookie to expire at end of session
					document.cookie = "wp_filter_region=<?php echo htmlspecialchars($_GET['_filter_region']); ?>; path=/; domain="+window.location.hostname;
				</script>
			<?php } ?>
		</section>
	<?php }
}
function uamswp_expertise_youtube() {
	$video = get_field('expertise_youtube_link');
	if( $video ) { ?>
		<?php if(function_exists('lyte_preparse')) {
			echo '<div class="alignwide">';
			echo lyte_parse( str_replace( ['https:', 'http:'], 'httpv:', $video ) );
			echo '</div>';
		} else {
			echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
			echo wp_oembed_get( $video );
			echo '</div>';
		} ?>
	<?php }
}
function uamswp_expertise_keywords() {
	$keywords = get_field('expertise_alternate_names');
	$keyword_text = '';
	if( $keywords ): 
		$i = 1;
		foreach( $keywords as $keyword ) { 
			if ( 1 < $i ) {
				$keyword_text .= '; ';
			}
			$keyword_text .= $keyword['text'];
			$i++;
		}
		echo '<p class="text-callout text-callout-info">Also called: '. $keyword_text .'</p>';
	endif;
}
function uamswp_expertise_conditions_cpt() {
	global $page_title;
	global $show_conditions_section;
	global $conditions_cpt_query;
	$condition_context = 'single-expertise';
	$condition_heading_related_name = $page_title; // To what is it related?
	$condition_heading_related_name_attr = $page_title_attr;

	if( $show_conditions_section ) {
		include( UAMS_FAD_PATH . '/templates/loops/conditions-cpt-loop.php' );
	}
}
function uamswp_expertise_treatments_cpt() {
	global $page_title;
	global $show_treatments_section;
	global $treatments_cpt_query;
	$treatment_context = 'single-expertise';
	$treatment_heading_related_name = $page_title; // To what is it related?
	$treatment_heading_related_name_attr = $page_title_attr;

	if( $show_treatments_section ) {
		include( UAMS_FAD_PATH . '/templates/loops/treatments-cpt-loop.php' );
	}
}
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
function uamswp_expertise_associated() {
	global $show_related_aoe_section;
	global $expertise_query;

	if( $show_related_aoe_section ) { ?>
		<section class="uams-module link-list link-list-layout-split bg-auto" id="related-expertise" aria-labelledby="related-expertise-title">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6 heading">
						<div class="text-container">
							<h2 class="module-title" id="related-expertise-title"><span class="title">Related Areas of Expertise</span></h2>
						</div>
					</div>
					<div class="col-12 col-md-6 list">
						<ul>
						<?php
						while ( $expertise_query->have_posts() ) : $expertise_query->the_post();
							echo '<li class="item"><div class="text-container"><h3 class="h5"><a href="'.get_permalink().'" aria-label="Go to Area of Expertise page for ' . get_the_title() . '">';
							echo get_the_title();
							echo '</a></h3>';
							echo ( has_excerpt() ? '<p>' . wp_trim_words( get_the_excerpt(), 30, '&nbsp;&hellip;' ) . '</p>' : '' );
							echo '</div></li>';
						endwhile;
						wp_reset_postdata(); ?>
						</ul>
					</div>
				</div>
			</div>
		</section>
	<?php 
	} // endif
}
function uamswp_expertise_header_metadata() { 
	$keywords = get_field('expertise_alternate_names');
	if( $keywords ): 
		$i = 1;
		$keyword_text = '';
		foreach( $keywords as $keyword ) { 
			if ( 1 < $i ) {
				$keyword_text .= ', ';
			}
			$keyword_text .= str_replace(",", "", $keyword['text']);
			$i++;
		}
		echo '<meta name="keywords" content="'. $keyword_text .'" />';
	endif;
}
function uamswp_expertise_podcast() {
	global $page_title;
	global $show_podcast_section;
	global $podcast_name;

	if ( $show_podcast_section ) {
		echo '<section class="uams-module podcast-list bg-auto" id="podcast">
		<script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">
		</script>
		<script type="text/javascript">
			radiomd_embedded_filtered_tag("uams","radiomd-embedded-filtered-tag",303,"' . $podcast_name . '");
		</script>
		<style type="text/css">
			#radiomd-embedded-filtered-tag iframe {
			width: 100%;
			border: none;
		}
		</style>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title"><span class="title">UAMS Health Talk Podcast</span></h2>
					<div class="module-body text-center">
						<p class="lead">In the UAMS Health Talk podcast, experts from UAMS talk about a variety of health topics, providing tips and guidelines to help people lead healthier lives. Listen to the episode(s) featuring the topic of '. $page_title . '.</p>
					</div>
					<div class="content-width mt-8" id="radiomd-embedded-filtered-tag"></div>
				</div>
				<div class="col-12 more">
					<p class="lead">Find other great episodes on other topics and from other UAMS providers.</p>
					<div class="cta-container">
						<a href="/podcast/" class="btn btn-primary" aria-label="Listen to more episodes of the UAMS Health Talk podcast">Listen to More Episodes</a>
					</div>
				</div>
			</div>
		</div>
	</section>';
	}
}
function uamswp_expertise_resource() {
	global $post;
	global $page_title;
	global $show_related_resource_section;
	global $resources;
	global $resource_query;
	global $resource_postsPerPage;
	$resource_heading_related_pre = true; // "Related Resources"
	$resource_heading_related_post = false; // "Resources Related to __"
	$resource_heading_related_name = $page_title; // To what is it related?
	$resource_heading_related_name_attr = $page_title_attr;
	$resource_more_suppress = false; // Force div.more to not display
	$resource_more_key = '_resource_aoe';
	$resource_more_value = $post->post_name;
	if( $show_related_resource_section ) {
		include( UAMS_FAD_PATH . '/templates/blocks/clinical-resources.php' );
	}
}
function uamswp_list_child_expertise() {
	global $page_id;
	global $page_title;
	global $show_child_aoe_section;
	if ( $show_child_aoe_section ) { // If it's suppressed or none available, set to false
		$args = array(
			"post_type" => "expertise",
			"post_status" => "publish",
			"post_parent" => $page_id,
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1, // We do not want to limit the post count
			'meta_query' => array(
				"relation" => "AND",
				array(
					"key" => "hide_from_sub_menu",
					"value" => "1",
					"compare" => "!=",
				),
				array(
					"key" => "expertise_type",
					"value" => "0",
					"compare" => "!=",
				),
			),
		);
		$pages = New WP_Query ( $args );
		if ( $pages->have_posts() ) { ?>
			<section class="uams-module expertise-list bg-auto" id="sub-expertise" aria-labelledby="sub-expertise-title" >
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<h2 class="module-title" id="sub-expertise-title"><span class="title">Areas Within <?php echo $page_title; ?></span></h2>
							<div class="card-list-container">
								<div class="card-list card-list-expertise">
							<?php
								while ( $pages->have_posts() ) : $pages->the_post();
									$id = get_the_ID();
									$child_expertise_list = true; // Indicate that this is a list of child Areas of Expertise within this Area of Expertise
									include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
								endwhile;
								wp_reset_postdata(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php
		}
	}
}
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
genesis();