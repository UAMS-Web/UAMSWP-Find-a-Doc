<?php
/*
 * Template Name: Single Treatment
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

	// Get system settings for area of expertise labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

	// Get system settings for clinical resource labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

	// Get system settings for combined condition and treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition-treatment.php' );

	// Get system settings for condition labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition.php' );

	// Get system settings for treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/treatment.php' );

// // Get system settings for this post type's archive page text
// include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/treatment.php' );

// Ontology / Content Type

	$ontology_type = true; // Ontology type of the post (true is ontology type, false is content type)

// Get the page ID

	$page_id = get_the_ID();

// Get the page title

	$page_title = get_the_title();
	$page_title_attr = uamswp_attr_conversion($page_title);

	// Array for page titles and section titles

		$page_titles = array(
			'page_title'		=> $page_title,
			'page_title_attr'	=> $page_title_attr
		);

	// Get system settings for elements of a fake subpage (or section) in a Treatment subsection (or profile)

		// Text elements

			// Do nothing

		// Image elements

			// Do nothing

	// Name of ontology item type represented by this fake subpage

		// Do nothing

	// Fake subpage page title

		// Do nothing

	// Fake subpage intro text

		// Do nothing

// Get the page URL and slug

	$page_url = user_trailingslashit(get_permalink());
	$page_slug = $post->post_name;

	// Fake subpage

		// Do nothing

// Get site header and site nav values for ontology subsections

	// Do nothing

// Image values

	// Get the featured image ID

		$featured_image = get_post_thumbnail_id(); // int // Featured image ID
		$featured_image = $featured_image ? $featured_image : '';

// Define the placement for content

	$content_placement = 'profile'; // Expected values: 'subsection' or 'profile'

// Query for whether to conditionally suppress ontology sections based on based on region and service line

	$regions = isset($regions) ? $regions : array();
	$service_lines = isset($service_lines) ? $service_lines : array();

	include( UAMS_FAD_PATH . '/templates/parts/vars/page/ontology-hide.php' );

// HEAD

	// Title tag

		// Construct the title tag value

			$meta_title_enhanced_addition = $treatment_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title
			include( UAMS_FAD_PATH . '/templates/parts/html/meta/title.php' );

		// Override SEOPress's standard title tag settings

			add_filter( 'seopress_titles_title', function( $html ) use ( $meta_title ) {

				if ( $meta_title ) {

					$html = $meta_title;

				}

				return $html;

			}, 15, 2 );

	// Meta Description and Schema Description

		// Get excerpt

			$excerpt = get_the_excerpt(); // get_field( 'treatment_procedure_short_desc' );
			$excerpt_user = true;

		// Get the content

			$content = get_the_content(); //get_field( 'treatment_procedure_content' );

		// Create excerpt if none exists

			if ( empty( $excerpt ) ) {

				$excerpt_user = false;

				if ( $content ) {

					$excerpt = mb_strimwidth(wp_strip_all_tags($content), 0, 155, '...');

				}

			}

		$excerpt_attr = uamswp_attr_conversion($excerpt);

		// Set schema description

			$schema_description = $excerpt_attr; // Used for Schema Data. Should ALWAYS have a value

		// Override theme's method of defining the meta description

			add_filter('seopress_titles_desc', function( $html ) use ( $excerpt_attr ) {

				if ( $excerpt_attr ) {

					$html = $excerpt_attr;

				}
				return $html;

			} );

	// Meta Keywords

		$keywords = get_field('treatment_procedure_alternate');

		// Override theme's standard meta keywords settings

			add_action( 'wp_head', function() use ( $keywords ) {
				uamswp_keyword_hook_header(
					$keywords // array
				);
			} );

	// Canonical URL

		// Do nothing

	// Meta Social Media Tags

		// Filter hooks
		include( UAMS_FAD_PATH . '/templates/parts/html/meta/social.php' );

// BODY

	// Add page template class to body element's classes

		$template_type = 'default';
		add_filter( 'body_class', function( $classes ) use ( $template_type ) {

			// Add page template class to body class array

				if ( $template_type ) {

					$classes[] = 'page-template-' . $template_type;

				}

			return $classes;

		} );

	// Header

		get_header();

		// Site header

			// Remove the site header set by the theme

				// remove_action( 'genesis_header', 'uamswp_site_image', 5 );

			// Add ontology subsection site header

				// add_action( 'genesis_header', function() use (
				// 	$page_id,
				// 	$ontology_type,
				// 	$page_title,
				// 	$page_url
				// ) {
				// 	include( UAMS_FAD_PATH . '/templates/parts/html/site-header/single-expertise.php');
				// }, 5 );

		// Primary navigation

			// Remove the primary navigation set by the theme

				// remove_action( 'genesis_after_header', 'genesis_do_nav' );
				// remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

			// Add ontology subsection primary navigation

				// add_action( 'genesis_after_header', function() use (
				// 	$page_id,
				// 	$ontology_type,
				// 	$page_title,
				// 	$page_url
				// ) {
				// 	include( UAMS_FAD_PATH . '/templates/parts/html/site-nav/single-expertise.php');
				// }, 5 );

	// Breadcrumbs

		// Override Genesis standard breadcrumbs settings

			// Do nothing

		// Override SEOPress standard breadcrumbs settings

			// Do nothing

	// Page Header (before entry element)

		// Remove Genesis-standard post title and markup

			// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
			// remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
			// remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
			// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

		// Construct non-standard post title

			// $entry_header_style = ''; // Entry header style
			// $entry_title_text = ''; // Regular title
			// $entry_title_text_supertitle = ''; // Optional supertitle, placed above the regular title
			// $entry_title_text_subtitle = ''; // Optional subtitle, placed below the regular title
			// $entry_title_text_body = ''; // Optional lead paragraph, placed below the entry title
			// $entry_title_image_desktop = ''; // Desktop breakpoint image ID
			// $entry_title_image_mobile = ''; // Optional mobile breakpoint image ID
			// 
			// add_action( 'genesis_before_content', function() use (
			// 	$entry_title_text,
			// 	$entry_header_style,
			// 	$entry_title_text_supertitle,
			// 	$entry_title_text_subtitle,
			// 	$entry_title_text_body,
			// 	$entry_title_image_desktop,
			// 	$entry_title_image_mobile
			// ) {
			// 
			// 	// Check/define variables
			// 	$entry_header_style = ( isset($entry_header_style) && !empty($entry_header_style) ) ? $entry_header_style : 'graphic';
			// 
			// 	include( UAMS_FAD_PATH . '/templates/parts/html/entry-title/' . $entry_header_style . '.php');
			// 
			// } );

	// MAIN / ARTICLE

		// Add bg-white class to article.entry element

			// add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

		// Start count for jump links

			$jump_link_count = 0;

		// Queries for whether each of the sections should be displayed

			// Related Providers Section Query

				$providers = get_field('treatment_procedure_physicians');
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/provider.php' );

			// Related Locations Section Query

				$locations = get_field('treatment_procedure_locations');
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/location.php' );

			// Related Areas of Expertise Section Query

				$expertises = get_field('treatment_procedure_expertise');
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/expertise.php' );

			// Related Clinical Resources Section Query

				$clinical_resources = get_field('treatment_procedure_clinical_resources');
				include( UAMS_FAD_PATH . '/templates/parts/vars/sys/posts-per-page/clinical-resource.php' ); // General maximum number of clinical resource items to display on a fake subpage (or section)
				$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_section;
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/clinical-resource.php' );

			// Related Conditions Section Query

				$conditions_cpt = get_field('treatment_conditions');
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/condition.php' );

			// Check if UAMS Health Talk podcast section should be displayed

				$podcast_name = get_field('treatment_procedure_podcast_name');
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/podcast.php' );

			// Check if Clinical Trials section should be displayed

				$clinical_trials = get_field('treatment_procedure_clinical_trials');

				if ( !empty($clinical_trials) ) {

					$clinical_trials_section_show = true;
					$jump_link_count++;

				} else {

					$clinical_trials_section_show = false;

				}

			// Query for whether Make an Appointment section should be displayed

				$appointment_section_show = true; // It should always be displayed.
				$jump_link_count++;

			// Check if Jump Links section should be displayed

				if ( $jump_link_count >= $jump_link_count_min ) {

					$jump_links_section_show = true;

				} else {

					$jump_links_section_show = false;

				}

		// Get remaining details about this item

			// Get system settings for jump links (a.k.a. anchor links)

				if ( $jump_links_section_show ) {

					include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/jump-links.php' );

				}

			// Video

				$video = get_field('treatment_procedure_youtube_link');

			// Medline / syndication

				$medline_type = get_field('medline_code_type');
				$medline_code = get_field('medline_code_id');
				$embed_code = get_field('treatment_procedure_embed_codes');

			// Call-to-action bar repeater

				$cta_repeater = get_field('treatment_procedure_cta');

		// Get remaining details content associated with this item

			// Do nothing

		// Classes for indicating presence of content

			$treatment_field_classes = '';
			$treatment_field_classes .= ( $keywords && array_filter($keywords) ) ? ' has-keywords' : ''; // Alternate names
			$treatment_field_classes .= ( $clinical_trials && !empty($clinical_trials) ) ? ' has-clinical-trials' : ''; // Display clinical trials block
			$treatment_field_classes .= ( $content && !empty($content) ) ? ' has-content' : ''; // Body content
			$treatment_field_classes .= ( $excerpt && $excerpt_user == true  ) ? ' has-excerpt' : ''; // Short Description (Excerpt)
			$treatment_field_classes .= ( $video && !empty($video) ) ? ' has-video' : ''; // Video embed
			$treatment_field_classes .= ( $condition_section_show ) ? ' has-condition' : ''; // Treatments
			$treatment_field_classes .= ( $expertise_section_show ) ? ' has-expertise' : ''; // Areas of Expertise
			$treatment_field_classes .= ( $location_section_show ) ? ' has-location' : ''; // Locations
			$treatment_field_classes .= ( $providers && array_filter($providers) ) ? ' has-provider' : ''; // Providers

		// Remove standard post content

			// remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		// Construct page content

			?>
			<div class="content-sidebar-wrap">
				<main id="genesis-content" class="treatment-item<?php echo $treatment_field_classes; ?>">
					<?php

					// Construct main info section

						?>
						<section class="archive-description bg-white">
							<header class="entry-header">
								<h1 class="entry-title"><span class="supertitle"><?php echo $treatment_single_name; ?></span><span class="sr-only">: </span><?php echo $page_title; ?></h1>
							</header>
							<div class="entry-content clearfix" itemprop="text">
								<?php

								if ( $keywords ): 

									$i = 1;
									$keyword_text = '';

									foreach ( $keywords as $keyword ) { 

										if ( 1 < $i ) {

											$keyword_text .= '; ';

										}

										$keyword_text .= $keyword['alternate_text'];
										$i++;

									}

									echo '<p class="text-callout text-callout-info">Also called: '. $keyword_text .'</p>';

								endif;

								the_content();

								if ( !empty($medline_type) && 'none' != $medline_type && !empty($medline_code) ) {

									echo display_medline_api_data( trim($medline_code), $medline_type );

								}

								if ( $embed_code ) {

									echo $embed_code;

								}

								if ( $video ) {

									// Check video source

										if (
											strpos( $video, 'youtube' ) !== false
											||
											strpos( $video, 'youtu.be' ) !== false
										) {

											$video_source = 'youtube';

										} else {

											$video_source = '';

										}

									// Display video player

										if (
											function_exists('lyte_preparse')
											&&
											$video_source == 'youtube'
										) {

											?>
											<div class="alignwide">
												<?php echo lyte_parse( str_replace( ['https:', 'http:'], 'httpv:', $video ) ); ?>
											</div>
											<?php

										} else {

											?>
											<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">
												<?php echo wp_oembed_get( $video ); ?>
											</div>
											<?php

										}

								}

								?>
							</div>
						</section>
						<?php

					// Construct CTA Bar(s)

						if ( $cta_repeater ) {

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

					// Construct Jump Links Section

						if ( $jump_links_section_show ) {

							?>
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
							<?php

						} // endif

					// Construct UAMS Health Talk podcast section

						$podcast_filter = 'tag';
						$podcast_subject = $page_title;
						include( UAMS_FAD_PATH . '/templates/parts/html/section/podcast.php' );

					// Construct Clinical Resources Section

						$clinical_resource_section_more_link_key = '_resource_treatments';
						$clinical_resource_section_more_link_value = $page_slug;
						$clinical_resource_section_title = $clinical_resource_plural_name . ' Related to ' . $page_title; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of clinical_resource section title in a general placement)
						$clinical_resource_section_intro = $clinical_resource_fpage_intro_general; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of clinical_resource section intro text in a general placement)
						$clinical_resource_section_more_text = 'Want to find more related ' . strtolower($clinical_resource_plural_name) . ' related to ' . $page_title . '?';
						$clinical_resource_section_more_link_text = $clinical_resource_fpage_more_link_text_general;
						$clinical_resource_section_more_link_descr = 'View the full list of ' . strtolower($clinical_resource_plural_name) . ' related to ' . $page_title;
						include( UAMS_FAD_PATH . '/templates/parts/html/section/list/clinical-resource.php' );

					// Construct Clinical Trials Section

						if ( $clinical_trials_section_show ) {

							$clinical_trial_title = $page_title;
							include( UAMS_FAD_PATH . '/templates/parts/html/section/clinical-trials.php' );

						} // endif

					// Construct Conditions Section

						$condition_section_title = $condition_plural_name . ' Related to ' . $page_title; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
						$condition_section_intro = $condition_fpage_intro_general; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
						include( UAMS_FAD_PATH . '/templates/parts/html/section/list/condition.php' );

					// Construct Providers Section

						$provider_section_title = $provider_plural_name . ' Performing or Prescribing ' . $page_title;// Text to use for the section title
						$provider_section_intro = 'Note that every ' . strtolower($provider_single_name) . ' listed below may not perform or prescribe ' . $page_title . ' for all ' . strtolower($condition_plural_name) . ' related to it. Review each ' . strtolower($provider_single_name) . ' for&nbsp;availability.'; // Text to use for the section intro text
						include( UAMS_FAD_PATH . '/templates/parts/html/section/list/provider.php' );

					// Construct Locations Section

						$location_section_title = $location_plural_name . ' Providing ' . $page_title; // Text to use for the section title
						$location_section_intro = 'Note that ' . $page_title . ' may not be <em>performed</em> at every ' . strtolower($location_single_name) . ' listed below. The list may include ' . strtolower($location_plural_name) . ' where the treatment plan is developed during and after a patient visit.'; // Text to use for the section intro text
						include( UAMS_FAD_PATH . '/templates/parts/html/section/list/location.php' );

					// Construct Areas of Expertise Section

						$expertise_section_title = $expertise_plural_name . ' Related to ' . $page_title;
						$expertise_section_intro = '';
						include( UAMS_FAD_PATH . '/templates/parts/html/section/list/expertise.php' );

					// Construct Appointment Information Section

						if ( $appointment_section_show ) {

							include( UAMS_FAD_PATH . '/templates/parts/html/section/appointment.php' );

						}
					?>
				</main>
			</div>
			<?php

	// FOOTER

		// Remove the post-related content and markup from the entry footer

			// remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
			// remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
			// remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
			// remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

		get_footer();

?>