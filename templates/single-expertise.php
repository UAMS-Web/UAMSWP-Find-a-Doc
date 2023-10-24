<?php
/*
 * Template Name: Single Area of Expertise
 */

// Define variables common to all area of expertise pages and fake subpages
include( UAMS_FAD_PATH . '/templates/parts/construction/single-expertise/common/vars.php' );

// Construct HEAD elements common to all area of expertise overview pages and all fake area of expertise subpages
include( UAMS_FAD_PATH . '/templates/parts/construction/single-expertise/common/construct-head.php' );

// BODY

	// Construct BODY elements common to all area of expertise overview pages and all fake area of expertise subpages
	include( UAMS_FAD_PATH . '/templates/parts/construction/single-expertise/common/construct-body.php' );

	// MAIN / ARTICLE

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
							$keyword_text .= $keyword['alternate_text'];
							$i++;
						}
						echo '<p class="text-callout text-callout-info">Also called: '. $keyword_text .'</p>';
					endif;
				}

			// Display featured video

				add_action( 'genesis_entry_content', 'uamswp_expertise_youtube', 12 );

				function uamswp_expertise_youtube() {

					$video = get_field('expertise_youtube_link');

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

			// Construct UAMS Health Talk podcast section

				$podcast_name = get_field('expertise_podcast_name');
				$podcast_filter = 'tag'; // string // Expected values: 'tag' or 'doctor'
				$podcast_subject = $page_title; // string

				// Check if podcast section should be displayed
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/podcast.php' );

				add_action( 'genesis_after_entry', function() use (
					$podcast_name,
					$podcast_section_show,
					$podcast_filter,
					$podcast_subject
				) {

					include( UAMS_FAD_PATH . '/templates/parts/html/section/podcast.php' );

				}, 10 );

			// Construct Combined Conditions and Treatments Section

				$ontology_type = isset($ontology_type) ? $ontology_type : true; // bool
				$condition_treatment_section_title = $condition_treatment_fpage_title_expertise; // Text to use for the section title // string (default: Find-a-Doc Settings value for combined condition/treatment section title in a general placement)
				$condition_treatment_section_intro = $condition_treatment_fpage_intro_expertise; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for combined condition/treatment section intro text in a general placement)
				$condition_section_title = $condition_fpage_title_expertise; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
				$condition_section_intro = $condition_fpage_intro_expertise; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
				$treatment_section_title = $treatment_fpage_title_expertise; // Text to use for the section title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
				$treatment_section_intro = $treatment_fpage_intro_expertise; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)

				add_action( 'genesis_after_entry', function() use (
					$page_id,
					$conditions_cpt,
					$treatments_cpt,
					$page_titles,
					$hide_medical_ontology,
					&$schema_medical_specialty,
					$condition_treatment_section_show,
					$condition_section_show,
					$treatment_section_show,
					$ontology_type,
					$condition_treatment_section_title,
					$condition_treatment_section_intro,
					$condition_section_title,
					$condition_section_intro,
					$treatment_section_title,
					$treatment_section_intro
				) {
					include( UAMS_FAD_PATH . '/templates/parts/html/section/list/condition-treatment.php' );
				}, 16 );

		// Construct the Schema Data Script Tag

			add_action( 'genesis_after_entry', function() use (
				$page_id,
				$page_url,
				$hide_medical_ontology,
				$ontology_type,
				$current_fpage
			) {
				include( UAMS_FAD_PATH . '/templates/parts/html/script/schema_expertise.php' );
			}, 18 );

genesis();