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

// Get system settings for jump links (a.k.a. anchor links)
uamswp_fad_labels_jump_links();

// Construct the meta keywords element
$keywords = get_field('treatment_procedure_alternate');
add_action('wp_head','uamswp_keyword_hook_header');

// Override theme's method of defining the meta page title
$meta_title_enhanced_addition = $treatments_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title
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
$expertise = get_field('treatment_procedure_expertise');
$providers = get_field('treatment_procedure_physicians');
$medline_type = get_field('medline_code_type');
$medline_code = get_field('medline_code_id');
$embed_code = get_field('treatment_procedure_embed_codes');

$podcast_name = get_field('treatment_procedure_podcast_name');

// Hard coded breadcrumbs
// $tax = get_term_by("slug", get_query_var("term"), get_query_var("taxonomy") );

$cta_repeater = get_field('treatment_procedure_cta');

// Query for whether related clinical resources content section should be displayed on a page
$clinical_resources = get_field('treatment_procedure_clinical_resources');
$resource_postsPerPage = 4; // Set this value to preferred value (-1, 4, 6, 8, 10, 12)
$resource_more = false;
$args = (array(
	'post_type' => "clinical-resource",
	'order' => 'DESC',
	'orderby' => 'post_date',
	'posts_per_page' => $resource_postsPerPage,
	'post_status' => 'publish',
	'post__in'	=> $clinical_resources
));
$clinical_resource_query = new WP_Query( $args );

// Query for whether related locations content section should be displayed on a page
$locations = get_field('treatment_procedure_locations');
uamswp_fad_location_query();

// Classes for indicating presence of content
$treatment_field_classes = '';
if ($keywords && array_filter($keywords)) { $treatment_field_classes .= ' has-keywords'; } // Alternate names
if ($clinical_trials && !empty($clinical_trials)) { $treatment_field_classes .= ' has-clinical-trials'; } // Display clinical trials block
if ($content && !empty($content)) { $treatment_field_classes .= ' has-content'; } // Body content
if ($excerpt && $excerpt_user == true ) { $treatment_field_classes .= ' has-excerpt'; } // Short Description (Excerpt)
if ($video && !empty($video)) { $treatment_field_classes .= ' has-video'; } // Video embed
if ($conditions_cpt && array_filter($conditions_cpt)) { $treatment_field_classes .= ' has-condition'; } // Treatments
if ($expertise && array_filter($expertise)) { $treatment_field_classes .= ' has-expertise'; } // Areas of Expertise
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

	// Check if Clinical Resources section should be displayed
	if( $clinical_resources && $clinical_resource_query->have_posts() ) {
		$clinical_resource_section_show = true;
		$jump_link_count++;
	} else {
		$clinical_resource_section_show = false;
	}

	// Check if Clinical Trials section should be displayed
	if ( !empty($clinical_trials) ) {
		$clinical_trials_section_show = true;
	} else {
		$clinical_trials_section_show = false;
	}

	// Check if Conditions section should be displayed
	$args = (array(
		'post_type' => "condition",
		"post_status" => "publish",
		'order' => 'ASC',
		'orderby' => 'title',
		'posts_per_page' => -1,
		'no_found_rows' => true, // counts posts, remove if pagination required
		'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
		'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
		'post__in'	=> $conditions_cpt
	));
	$conditions_query_cpt = new WP_Query( $args );

	if ( $conditions_cpt && !empty($conditions_query_cpt->have_posts()) ) {
		$condition_section_show = true;
		$jump_link_count++;
	} else {
		$condition_section_show = false;
	}

	// Check if Providers section should be displayed
	if ( $providers ) {
		$args = (array(
			'post_type' => "provider",
			"post_status" => "publish",
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
			'fields' => 'ids',
			// 'no_found_rows' => true, // counts posts, remove if pagination required
			'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
			'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
			'post__in'	=> $providers
		));
		$provider_query = new WP_Query( $args );
	}
	if( $providers && $provider_query->have_posts() ) {
		$provider_section_show = true;
		$jump_link_count++;
		$provider_ids = $provider_query->posts;
		wp_reset_postdata();
	} else {
		$provider_section_show = false;
	}

	// Check if Areas of Expertise section should be displayed
	$args = (array(
		'post_type' => "expertise",
		"post_status" => "publish",
		'order' => 'ASC',
		'orderby' => 'title',
		'posts_per_page' => -1,
		'no_found_rows' => true, // counts posts, remove if pagination required
		'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
		'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
		'post__in'	=> $expertise
	));
	$expertise_query = new WP_Query( $args );

	if ( $expertise && $expertise_query->have_posts() ) {
		$expertise_section_show = true;
		$jump_link_count++;
	} else {
		$expertise_section_show = false;
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
				<h1 class="entry-title"><span class="supertitle"><?php echo $treatments_single_name; ?></span><span class="sr-only">: </span><?php echo $page_title; ?></h1>
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
								<a class="nav-link" href="#conditions" title="Jump to the section of this page about <?php echo $conditions_plural_name_attr; ?>"><?php echo $conditions_plural_name; ?></a>
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
		$resource_heading_related_pre = false; // "Related Resources"
		$resource_heading_related_post = true; // "Resources Related to __"
		$resource_heading_related_name = $page_title; // To what is it related?
		$resource_heading_related_name_attr = $page_title_attr;
		$resource_more_suppress = false; // Force div.more to not display
		$resource_more_key = '_resource_treatments';
		$resource_more_value = $post->post_name;
		if( $clinical_resource_section_show ) {
			include( UAMS_FAD_PATH . '/templates/blocks/clinical-resources.php' );
		}
		// End Clinical Resources Section

		// Begin Clinical Trials Section
		if ( $clinical_trials_section_show ) {
			$clinical_trial_title = $page_title;
			include( UAMS_FAD_PATH . '/templates/blocks/clinical-trials.php' );
		} // endif
		// End Clinical Trials Section

		// Begin Conditions Section
		if ( $condition_section_show ) { ?>
			<section class="uams-module conditions-treatments bg-auto" id="conditions">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="module-title"><span class="title"><?php echo $clinical_resource_plural_name; ?> Related to <?php echo $page_title; ?></span></h2>
							<p class="note">UAMS Health <?php echo strtolower($provider_plural_name); ?> care for a broad range of <?php echo strtolower($clinical_resource_plural_name); ?>, some of which may not be listed below.</p>
							<div class="list-container list-container-rows">
								<ul class="list">
								<?php while ($conditions_query_cpt->have_posts()) : $conditions_query_cpt->the_post();
									$condition_id = get_the_ID();
									$condition_permalink = get_permalink( $condition_id );
									$condition_title = get_the_title();
									$condition_title_attr = uamswp_attr_conversion($condition_title);
									?>
									<li>
										<a href="<?php echo $condition_permalink; ?>" aria-label="Go to <?php echo $provider_single_name_attr; ?> page for <?php echo $condition_title_attr; ?>" class="btn btn-outline-primary"><?php echo $condition_title; ?></a>
									</li>
								<?php
									endwhile;
									wp_reset_postdata();
								?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php } // endif
		// End Conditions Section

		// Begin Providers Section
		$provider_section_title = $provider_plural_name . ' Performing or Prescribing ' . $page_title;// Text to use for the section title
		$provider_section_intro = 'Note that every ' . strtolower($provider_single_name) . ' listed below may not perform or prescribe ' . $page_title . ' for all ' . strtolower($conditions_plural_name) . ' related to it. Review each ' . strtolower($provider_single_name) . ' for&nbsp;availability.'; // Text to use for the section intro text
		uamswp_fad_section_provider();
		// End Providers Section

		// Begin Locations Section
		$location_section_title = $location_plural_name . ' Providing ' . $page_title; // Text to use for the section title
		$location_section_intro = 'Note that ' . $page_title . ' may not be <em>performed</em> at every ' . strtolower($location_single_name) . ' listed below. The list may include ' . strtolower($location_plural_name) . ' where the treatment plan is developed during and after a patient visit.'; // Text to use for the section intro text
		uamswp_fad_section_location();
		// End Locations Section

		// Begin Areas of Expertise Section
		if ( $expertise_section_show ) { ?>
		<section class="uams-module bg-auto" id="expertise">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title"><span class="title"><?php echo $expertise_plural_name; ?> for <?php echo $page_title; ?></span></h2>
						<div class="card-list-container">
							<div class="card-list card-list-expertise">
							<?php 
								while ( $expertise_query->have_posts() ) : $expertise_query->the_post();
									$id = get_the_ID();
									include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
								endwhile;
								wp_reset_postdata();
							?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php } // endif
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