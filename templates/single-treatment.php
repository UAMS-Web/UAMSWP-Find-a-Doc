<?php
/*
 * Template Name: Single Treatment
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	uamswp_fad_labels_provider();

	// Get system settings for location labels
	uamswp_fad_labels_location();

	// Get system settings for location descendant item labels
	// uamswp_fad_labels_location_descendant();

	// Get system settings for area of expertise labels
	uamswp_fad_labels_expertise();

	// Get system settings for clinical resource labels
	uamswp_fad_labels_clinical_resource();

	// Get system settings for area of expertise descendant item labels
	// uamswp_fad_labels_expertise_descendant();

	// Get system settings for condition labels
	uamswp_fad_labels_condition();

	// Get system settings for treatment labels
	uamswp_fad_labels_treatment();

// Get system settings for condition archive page text
// uamswp_fad_archive_treatment();

// Get the page ID
$page_id = get_the_ID();

// Get the page title for the treatment
$page_title = get_the_title();
$page_title_attr = uamswp_attr_conversion($page_title);

// Get the page slug for the treatment
$page_slug = $post->post_name;

// Get system settings for jump links (a.k.a. anchor links)
uamswp_fad_labels_jump_links();

// Construct the meta keywords element
$keywords = get_field('treatment_procedure_alternate');
add_action('wp_head','uamswp_keyword_hook_header');

// Override theme's method of defining the meta page title
$meta_title_enhanced_addition = $treatment_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title
uamswp_fad_title_vars(); // Defines universal variables related to the setting the meta title
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

$excerpt = get_the_excerpt(); //get_field( 'treatment_procedure_short_desc' );
$content = get_the_content(); //get_field( 'treatment_procedure_content' );
$excerpt_user = true;
if (empty($excerpt)){
	$excerpt_user = false;
	if ($content){
		$excerpt = mb_strimwidth(wp_strip_all_tags($content), 0, 155, '...');
	}
}
// Override theme's method of defining the meta description
add_filter('seopress_titles_desc', 'uamswp_fad_meta_desc');

// Add page template class to body element's classes
add_filter( 'body_class', 'uamswp_page_body_class' );
$template_type = 'default';

get_header();

$clinical_trials = get_field('treatment_procedure_clinical_trials');
$video = get_field('treatment_procedure_youtube_link');
$conditions_cpt = get_field('treatment_conditions');
$medline_type = get_field('medline_code_type');
$medline_code = get_field('medline_code_id');
$embed_code = get_field('treatment_procedure_embed_codes');

$podcast_name = get_field('treatment_procedure_podcast_name');

// Hard coded breadcrumbs
// $tax = get_term_by("slug", get_query_var("term"), get_query_var("taxonomy") );

$cta_repeater = get_field('treatment_procedure_cta');

// Query for whether related providers content section should be displayed on ontology pages/subsections
$providers = get_field('treatment_procedure_physicians');
uamswp_fad_provider_query();

// Query for whether related locations content section should be displayed on a page
$locations = get_field('treatment_procedure_locations');
uamswp_fad_location_query();

// Query for whether related areas of expertise content section should be displayed on a page
$expertises = get_field('treatment_procedure_expertise');
uamswp_fad_expertise_query();

// Query for whether related clinical resources content section should be displayed on ontology pages/subsections
$clinical_resources = get_field('treatment_procedure_clinical_resources');
uamswp_fad_clinical_resource_query();

// Query for whether related conditions content section should be displayed on ontology pages/subsections
$conditions_cpt = get_field('treatment_conditions');
uamswp_fad_condition_query();

// Classes for indicating presence of content
$treatment_field_classes = '';
if ($keywords && array_filter($keywords)) { $treatment_field_classes .= ' has-keywords'; } // Alternate names
if ($clinical_trials && !empty($clinical_trials)) { $treatment_field_classes .= ' has-clinical-trials'; } // Display clinical trials block
if ($content && !empty($content)) { $treatment_field_classes .= ' has-content'; } // Body content
if ($excerpt && $excerpt_user == true ) { $treatment_field_classes .= ' has-excerpt'; } // Short Description (Excerpt)
if ($video && !empty($video)) { $treatment_field_classes .= ' has-video'; } // Video embed
if ($condition_section_show) { $treatment_field_classes .= ' has-condition'; } // Treatments
if ($expertise_section_show) { $treatment_field_classes .= ' has-expertise'; } // Areas of Expertise
if ($location_section_show) { $treatment_field_classes .= ' has-location'; } // Locations
if ($providers && array_filter($providers)) { $treatment_field_classes .= ' has-provider'; } // Providers

// Set logic for displaying jump links and sections
$jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
$jump_link_count = 0;

	// Check if UAMS Health Talk podcast section should be displayed
	$podcast_filter = 'tag';
	$podcast_subject = $page_title;
	uamswp_fad_podcast_query();
	if ( $podcast_section_show ) {
		$jump_link_count++;
	}

	// Check if Clinical Trials section should be displayed
	if ( !empty($clinical_trials) ) {
		$clinical_trials_section_show = true;
	} else {
		$clinical_trials_section_show = false;
	}

	// Check if Make an Appointment section should be displayed
	// It should always be displayed.
	$appointment_section_show = true;
	$jump_link_count++;

	// Check if Jump Links section should be displayed
	if ( $jump_link_count >= $jump_link_count_min ) {
		$jump_links_section_show = true;
	} else {
		$jump_links_section_show = false;
	}
?>
<div class="content-sidebar-wrap">
	<main id="genesis-content" class="treatment-item<?php echo $treatment_field_classes; ?>">
		<section class="archive-description bg-white">
			<header class="entry-header">
				<h1 class="entry-title"><span class="supertitle"><?php echo $treatment_single_name; ?></span><span class="sr-only">: </span><?php echo $page_title; ?></h1>
			</header>
			<div class="entry-content clearfix" itemprop="text">
				<?php
					if( $keywords ): 
						$i = 1;
						$keyword_text = '';
						foreach( $keywords as $keyword ) { 
							if ( 1 < $i ) {
								$keyword_text .= '; ';
							}
							$keyword_text .= $keyword['alternate_text'];
							$i++;
						}
						echo '<p class="text-callout text-callout-info">Also called: '. $keyword_text .'</p>';
					endif;
				?>
				<?php the_content(); ?>
				<?php 
					if ( !empty($medline_type) && 'none' != $medline_type && !empty($medline_code) ) {
						echo display_medline_api_data( trim($medline_code), $medline_type );
					}
				?>
				<?php 
					if ( $embed_code ) {
						echo $embed_code;
					}
				?>
				<?php if( $video ) { ?>
					<?php if(function_exists('lyte_preparse')) {
						echo '<div class="alignwide">';
						echo lyte_parse( str_replace( ['https:', 'http:'], 'httpv:', $video ) );
						echo '</div>';
					} else {
						echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
						echo wp_oembed_get( $video );
						echo '</div>';
					} ?>
				<?php } ?>
			</div>
		</section>
		<?php
		// Begin CTA Bar(s)
			if( $cta_repeater ) {
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
							$cta_button_target = $button_url['target'];
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
					if ( $cta_cta_size == 'small' ) {
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
			} // endif;
			// End CTA Bar(s)

			// Begin Jump Links Section
		if ( $jump_links_section_show ) { ?>
			<nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
				<h2><?php echo $fad_jump_links_title; ?></h2>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse inner-container" id="jump-link-nav">
					<ul class="nav navbar-nav">
						<?php if ( $podcast_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#podcast" title="Jump to the section of this page about UAMS Health Talk Podcast">Podcast</a>
							</li>
						<?php } ?>
						<?php if ( $clinical_resource_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#related-resources" title="Jump to the section of this page about <?php echo $clinical_resource_plural_name_attr; ?>"><?php echo $clinical_resource_plural_name; ?></a>
							</li>
						<?php } ?>
						<?php if ( $clinical_trials_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#clinical-trials" title="Jump to the section of this page about Clinical Trials">Clinical Trials</a>
							</li>
						<?php } ?>
						<?php if ( $condition_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#conditions" title="Jump to the section of this page about <?php echo $condition_plural_name_attr; ?>"><?php echo $condition_plural_name; ?></a>
							</li>
						<?php } ?>
						<?php if ( $provider_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#providers" title="Jump to the section of this page about <?php echo $provider_plural_name_attr; ?>"><?php echo $provider_plural_name; ?></a>
							</li>
						<?php } ?>
						<?php if ($location_section_show) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#locations" title="Jump to the section of this page about <?php echo $location_plural_name_attr; ?>"><?php echo $location_plural_name; ?></a>
							</li>
						<?php } ?>
						<?php if ( $expertise_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#expertise" title="Jump to the section of this page about <?php echo $expertise_plural_name_attr; ?>"><?php echo $expertise_plural_name; ?></a>
							</li>
						<?php } ?>
						<?php if ( $appointment_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#appointment-info" title="Jump to the section of this page about making an appointment">Make an Appointment</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			</nav>
		<?php } // endif
		// End Jump Links Section

		// Construct UAMS Health Talk podcast section
		uamswp_fad_podcast();

		// Begin Clinical Resources Section
		$clinical_resource_section_more_link_key = '_resource_treatments';
		$clinical_resource_section_more_link_value = $page_slug;
		$clinical_resource_section_title = $clinical_resource_plural_name . ' Related to ' . $page_title; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of clinical_resource section title in a general placement)
		$clinical_resource_section_intro = $clinical_resource_fpage_intro_general; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of clinical_resource section intro text in a general placement)
		$clinical_resource_section_more_text = 'Want to find more related ' . strtolower($clinical_resource_plural_name) . ' related to ' . $page_title . '?';
		$clinical_resource_section_more_link_text = $clinical_resource_fpage_more_link_text_general;
		$clinical_resource_section_more_link_descr = 'View the full list of ' . strtolower($clinical_resource_plural_name) . ' related to ' . $page_title;
		uamswp_fad_section_clinical_resource();
		// End Clinical Resources Section

		// Begin Clinical Trials Section
		if ( $clinical_trials_section_show ) {
			$clinical_trial_title = $page_title;
			include( UAMS_FAD_PATH . '/templates/blocks/clinical-trials.php' );
		} // endif
		// End Clinical Trials Section

		// Begin Conditions Section
		$condition_section_title = $condition_plural_name . ' Related to ' . $page_title; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
		$condition_section_intro = $condition_fpage_intro_general; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
		uamswp_fad_section_condition();
		// End Conditions Section	

		// Begin Providers Section
		$provider_section_title = $provider_plural_name . ' Performing or Prescribing ' . $page_title;// Text to use for the section title
		$provider_section_intro = 'Note that every ' . strtolower($provider_single_name) . ' listed below may not perform or prescribe ' . $page_title . ' for all ' . strtolower($condition_plural_name) . ' related to it. Review each ' . strtolower($provider_single_name) . ' for&nbsp;availability.'; // Text to use for the section intro text
		include( UAMS_FAD_PATH . '/templates/parts/section-list-provider.php' );
		// End Providers Section

		// Begin Locations Section
		$location_section_title = $location_plural_name . ' Providing ' . $page_title; // Text to use for the section title
		$location_section_intro = 'Note that ' . $page_title . ' may not be <em>performed</em> at every ' . strtolower($location_single_name) . ' listed below. The list may include ' . strtolower($location_plural_name) . ' where the treatment plan is developed during and after a patient visit.'; // Text to use for the section intro text
		include( UAMS_FAD_PATH . '/templates/parts/section-list-location.php' );
		// End Locations Section

		// Begin Areas of Expertise Section
		$expertise_section_title = $expertise_plural_name . ' Related to ' . $page_title;
		$expertise_section_intro = '';
		uamswp_fad_section_expertise();
		// End Areas of Expertise Section

		// Begin Appointment Information Section
		if ( $appointment_section_show ) {
			include( UAMS_FAD_PATH . '/templates/blocks/appointment.php' );
		}
		// End Appointment Information Section
		?>
	</main>
</div>
<?php get_footer(); ?>