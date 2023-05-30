<?php
/*
 * Template Name: Single Area of Expertise
 */

// Set general variables
$page_id = get_the_ID();
$page_title = get_the_title();
$page_title_attr = uamswp_attr_conversion($page_title);
$page_url = get_permalink();
$expertise_archive_title = get_field('expertise_archive_headline', 'option') ?: 'Areas of Expertise';
$expertise_archive_title_attr = uamswp_attr_conversion($expertise_archive_title);
$expertise_single_name = get_field('expertise_archive_headline', 'option') ?: 'Area of Expertise';
$expertise_single_name_attr = uamswp_attr_conversion($expertise_single_name);

// Area of Expertise Content Type
$ontology_type = get_field('expertise_type'); // True is ontology type, false is content type

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
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);
function uamswp_fad_title($html) { 
	global $page_title_attr;
	global $expertise_single_name_attr;
	//you can add here all your conditions as if is_page(), is_category() etc.. 
	$meta_title_chars_max = 60;
	$meta_title_base = $page_title_attr . ' | ' . get_bloginfo( "name" );
	$meta_title_base_chars = strlen( $meta_title_base );
	$meta_title_enhanced_addition = ' | ' . $expertise_single_name_attr;
	$meta_title_enhanced = $page_title_attr . $meta_title_enhanced_addition . ' | ' . get_bloginfo( "name" );
	$meta_title_enhanced_chars = strlen( $meta_title_enhanced );
	if ( $meta_title_enhanced_chars <= $meta_title_chars_max ) {
		$html = $meta_title_enhanced;
	} else {
		$html = $meta_title_base;
	}
	return $html;
}

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

// Add bg-white class to article.entry element
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

// Modify Entry Title

	// Remove Genesis-standard post title
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

	// Add post title for ontology subsection main page
	add_action( 'genesis_entry_header', 'uamswp_expertise_post_title' );
	function uamswp_expertise_post_title() {
		global $page_title;
		global $expertise_single_name;
		global $parent_expertise;
		global $parent_title;
		global $parent_title_attr;
		global $parent_url;
		global $has_ancestors_ontology;
		global $ontology_type;

		echo '<h1 class="entry-title" itemprop="headline">';
		echo '<span class="supertitle">'. $expertise_single_name . '</span><span class="sr-only">:</span> ';
		echo $page_title;
		if ( $parent_expertise ) {
		   echo '<span class="subtitle"><span class="sr-only">(</span>Part of <a href="' . $parent_url . '" aria-label="Go to Area of Expertise page for ' . $parent_title_attr . '" data-categorytitle="Parent Name">' . $parent_title . '</a><span class="sr-only">)</span></span>';
		} // endif
		echo '</h1>';
	}

// Remove the post info (byline) from the entry header and the entry footer
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php

// Remove the entry meta (tags) from the entry footer, including markup
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

// Construct page content

	// Display alternate names
	add_filter( 'genesis_entry_content', 'uamswp_expertise_keywords', 8 );
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

	// Display featured video
	add_action( 'genesis_entry_content', 'uamswp_expertise_youtube', 12 );
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

	// Display call-to-action bars
	add_action( 'genesis_after_entry', 'uamswp_expertise_cta', 6 );
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

	// Display podcast
	add_action( 'genesis_after_entry', 'uamswp_expertise_podcast', 10 );
	// Check if Podcast section should be displayed
	$podcast_name = get_field('expertise_podcast_name');
	$podcast_name_attr = uamswp_attr_conversion($podcast_name);
	if ($podcast_name) {
		$show_podcast_section = true;
	} else {
		$show_podcast_section = false;
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
						<p class="lead">Find other great episodes on other topics and from other UAMS Health providers.</p>
						<div class="cta-container">
							<a href="/podcast/" class="btn btn-primary" aria-label="Listen to more episodes of the UAMS Health Talk podcast">Listen to More Episodes</a>
						</div>
					</div>
				</div>
			</div>
		</section>';
		}
	}

	// Display conditions
	add_action( 'genesis_after_entry', 'uamswp_expertise_conditions_cpt', 16 );
	function uamswp_expertise_conditions_cpt() {
		global $page_title;
		global $show_conditions_section;
		global $conditions_cpt_query;
		$condition_context = 'single-expertise';
		$condition_heading_related_name = $page_title; // To what is it related?
		$condition_heading_related_name_attr = $page_title_attr;
	
		if( $show_conditions_section ) {
			include( UAMS_FAD_PATH . '/templates/loops/conditions-cpt-loop-list.php' );
		}
	}

	// Display treatments
	add_action( 'genesis_after_entry', 'uamswp_expertise_treatments_cpt', 18 );
	function uamswp_expertise_treatments_cpt() {
		global $page_title;
		global $show_treatments_section;
		global $treatments_cpt_query;
		$treatment_context = 'single-expertise';
		$treatment_heading_related_name = $page_title; // To what is it related?
		$treatment_heading_related_name_attr = $page_title_attr;
	
		if( $show_treatments_section ) {
			include( UAMS_FAD_PATH . '/templates/loops/treatments-cpt-loop-list.php' );
		}
	}

	// Display appointment information
	add_action( 'genesis_after_entry', 'uamswp_fad_ontology_appointment', 26 );
	// Check if Make an Appointment section should be displayed
	$show_appointment_section = true; // It should always be displayed.

genesis();