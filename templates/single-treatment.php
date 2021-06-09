<?php
	// ACF Fields - get_fields
	$keywords = get_field('treatment_procedure_alternate');

	function uamswp_keyword_hook_header() {
		global $keywords;
		$keyword_text = '';
		if( $keywords ): 
			$i = 1;
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
	add_action('wp_head','uamswp_keyword_hook_header');

	function uamswp_fad_title($html) { 
		// global $treatment_title;
		//you can add here all your conditions as if is_page(), is_category() etc.. 
		if ( strlen(get_the_title()) < 21 ) {
			$html = get_the_title() . ' | Treatments & Procedures | ' . get_bloginfo( "name" );
		} else {
			$html = get_the_title() . ' | ' . get_bloginfo( "name" );
		}
		return $html;
	}
	add_filter('pre_get_document_title', 'uamswp_fad_title', 15, 2);

	$excerpt = get_the_excerpt(); //get_field( 'treatment_procedure_short_desc' );
	$content = get_the_content(); //get_field( 'treatment_procedure_content' );
	$excerpt_user = true;
	if (empty($excerpt)){
		$excerpt_user = false;
		if ($content){
			$excerpt = mb_strimwidth(wp_strip_all_tags($content), 0, 155, '...');
		}
	}
	// Use SeoPress hook for meta description
	function sp_titles_desc($html) {
		global $excerpt;
		$html = $excerpt; 
		return $html;
	}
	add_filter('seopress_titles_desc', 'sp_titles_desc');

	function uams_default_page_body_class( $classes ) {

		$classes[] = 'page-template-default';
		return $classes;
	}
	add_filter( 'body_class', 'uams_default_page_body_class' );
	
	get_header();

	$clinical_trials = get_field('treatment_procedure_clinical_trials');
	$video = get_field('treatment_procedure_youtube_link');
	$conditions_cpt = get_field('treatment_conditions');
	$expertise = get_field('treatment_procedure_expertise');
	$locations = get_field('treatment_procedure_locations');
	$physicians = get_field('treatment_procedure_physicians');
	$medline_type = get_field('medline_code_type');
	$medline_code = get_field('medline_code_id');
	$embed_code = get_field('treatment_procedure_embed_codes');

	$treatment_title = get_field('treatments_archive_headline', 'option');
	$treatment_text = get_field('treatments_archive_intro_text', 'option');
	
    $podcast_name = get_field('treatment_procedure_podcast_name');

	// Hard coded breadcrumbs
	// $tax = get_term_by("slug", get_query_var("term"), get_query_var("taxonomy") );

    $cta_repeater = get_field('treatment_procedure_cta');

	// Clinical Resources
	$resources =  get_field('treatment_procedure_clinical_resources');
	$resource_postsPerPage = 4; // Set this value to preferred value (-1, 4, 6, 8, 10, 12)
	$resource_more = false;
	$args = (array(
		'post_type' => "clinical-resource",
		'order' => 'DESC',
        'orderby' => 'post_date',
		'posts_per_page' => $resource_postsPerPage,
		'post_status' => 'publish',
		'post__in'	=> $resources
	));
	$resource_query = new WP_Query( $args );

	// Locations Content
	$location_content = '';
	$args = (array(
		'post_type' => "location",
		"post_status" => "publish",
		'order' => 'ASC',
		'orderby' => 'title',
		'posts_per_page' => -1,
		'no_found_rows' => true, // counts posts, remove if pagination required
		'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
		'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
		'post__in'	=> $locations
	));
	$location_query = new WP_Query( $args );

	// Check for valid locations
	$location_valid = false;
	if ( $locations && $location_query->have_posts() ) {
		foreach( $locations as $location ) {
			if ( get_post_status ( $location ) == 'publish' ) {
				$location_valid = true;
				$break;
			}
		}
	}

	if ( $location_valid ) {
		$location_content .= '<section class="uams-module bg-auto" id="locations">';
		$location_content .= '<div class="container-fluid">';
		$location_content .= '<div class="row">';
		$location_content .= '<div class="col-12">';
		$location_content .= '<h2 class="module-title"><span class="title">Locations Providing ' . get_the_title() . '</span></h2>';
		$location_content .= '<p class="note">Note that ' . get_the_title() . ' may not be <em>performed</em> at every location listed below. The list may include locations where the treatment plan is developed during and after a patient visit.</p>';
		$location_content .= '<div class="card-list-container location-card-list-container">';
		$location_content .= '<div class="card-list">';
		ob_start();
		ob_clean();
		while ( $location_query->have_posts() ) : $location_query->the_post();
			$id = get_the_ID();
			include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
		endwhile;
		wp_reset_postdata();
		$location_content .= ob_get_clean();
		$location_content .= '</div>';
		$location_content .= '</div>';
		$location_content .= '</div>';
		$location_content .= '</div>';
		$location_content .= '</div>';
		$location_content .= '</section>';
	}

	// Classes for indicating presence of content
    $treatment_field_classes = '';	
	if ($keywords && array_filter($keywords)) { $treatment_field_classes .= ' has-keywords'; } // Alternate names
    if ($clinical_trials && !empty($clinical_trials)) { $treatment_field_classes .= ' has-clinical-trials'; } // Display clinical trials block
    if ($content && !empty($content)) { $treatment_field_classes .= ' has-content'; } // Body content
    if ($excerpt && $excerpt_user == true ) { $treatment_field_classes .= ' has-excerpt'; } // Short Description (Excerpt)
    if ($video && !empty($video)) { $treatment_field_classes .= ' has-video'; } // Video embed
	if ($conditions_cpt && array_filter($conditions_cpt)) { $treatment_field_classes .= ' has-condition'; } // Treatments
    if ($expertise && array_filter($expertise)) { $treatment_field_classes .= ' has-expertise'; } // Areas of Expertise
    if ($locations && $location_valid) { $treatment_field_classes .= ' has-location'; } // Locations
    if ($physicians && array_filter($physicians)) { $treatment_field_classes .= ' has-provider'; } // Providers
	
    // Set logic for displaying jump links and sections
    $jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
    $jump_link_count = 0;

        // Check if Podcast section should be displayed
        if ( $podcast_name ) {
            $show_podcast_section = true;
            $jump_link_count++;
        } else {
            $show_podcast_section = false;
        }
	
		// Check if Clinical Resources section should be displayed
		if( $resources && $resource_query->have_posts() ) {
			$show_related_resource_section = true;
			$jump_link_count++;
		} else {
			$show_related_resource_section = false;
		}

        // Check if Clinical Trials section should be displayed
        if ( !empty($clinical_trials) ) {
            $show_clinical_trials_section = true;
        } else {
            $show_clinical_trials_section = false;
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
            $show_conditions_section = true;
            $jump_link_count++;
        } else {
            $show_conditions_section = false;
        }

        // Check if Providers section should be displayed	
		if ( $physicians ) {
			$physiciansCount = count($physicians);
			$postsPerPage = 12; // Set this value to preferred value (4, 6, 8, 10, 12)
			$postsCutoff = 18; // Set cutoff value
			$postsCountClass = $postsPerPage;
			if($physiciansCount <= $postsCutoff ) {
				$postsPerPage = -1;
			}
			$args = (array(
				'post_type' => "provider",
				"post_status" => "publish",
				'order' => 'ASC',
				'orderby' => 'title',
				'posts_per_page' => $postsPerPage,
				// 'no_found_rows' => true, // counts posts, remove if pagination required
				'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
				'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
				'post__in'	=> $physicians
			));
			$physicians_query = new WP_Query( $args );
		}
		if( $physicians && $physicians_query->have_posts() ) {
			$show_providers_section = true;
			$jump_link_count++;
		} else {
			$show_providers_section = false;
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
            $show_aoe_section = true;
            $jump_link_count++;
        } else {
            $show_aoe_section = false;
        }

        // Check if Locations section should be displayed
        if ( !empty($location_content) ) {
            $show_locations_section = true;
            $jump_link_count++;
        } else {
            $show_locations_section = false;
        }

        // Check if Make an Appointment section should be displayed
		// It should always be displayed.
		$show_appointment_section = true;
		$jump_link_count++;

        // Check if Jump Links section should be displayed
        if ( $jump_link_count >= $jump_link_count_min ) {
            $show_jump_links_section = true;
        } else {
            $show_jump_links_section = false;
        }
 ?>
<div class="content-sidebar-wrap">
	<main id="genesis-content" class="treatment-item<?php echo $treatment_field_classes; ?>">
		<section class="archive-description bg-white">
			<header class="entry-header">
				<h1 class="entry-title"><span class="supertitle"><?php echo ( $treatment_title ? $treatment_title : 'Treatment & Procedure' ); ?></span><span class="sr-only">:</span> <?php echo get_the_title(); ?></h1>
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
							$keyword_text .= $keyword['text'];
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
						echo lyte_parse( str_replace( 'https', 'httpv', $video ) ); 
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
        if ( $show_jump_links_section ) { ?>
            <nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
                <h2>Contents</h2>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse inner-container" id="jump-link-nav">
                    <ul class="nav navbar-nav">
                        <?php if ( $show_podcast_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#podcast" title="Jump to the section of this page about UAMS Health Talk Podcast">Podcast</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_related_resource_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#resources" title="Jump to the section of this page about Resources">Resources</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_clinical_trials_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#clinical-trials" title="Jump to the section of this page about Clinical Trials">Clinical Trials</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_conditions_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#conditions" title="Jump to the section of this page about Conditions">Conditions</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_providers_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#providers" title="Jump to the section of this page about Providers">Providers</a>
                            </li>
                        <?php } ?>
                        <?php if ($show_locations_section) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#locations" title="Jump to the section of this page about Locations">Locations</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_aoe_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#expertise" title="Jump to the section of this page about Areas of Expertise">Areas of Expertise</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_appointment_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#appointment-info" title="Jump to the section of this page about making an appointment">Make an Appointment</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
        <?php } // endif
        // End Jump Links Section

		// Begin UAMS Health Talk Podcast Section
		if ( $show_podcast_section ) { ?>
            <section class="uams-module podcast-list bg-auto" id="podcast">
                <script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">
                </script>
                <script type="text/javascript">
					radiomd_embedded_filtered_tag("uams","radiomd-embedded-filtered-tag",303,"<?php echo $podcast_name; ?>");
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
                                <p class="lead">In the UAMS Health Talk podcast, experts from UAMS talk about a variety of health topics, providing tips and guidelines to help people lead healthier lives. Listen to the episode(s) featuring the topic of <?php echo get_the_title(); ?>.</p>
                            </div>
                            <div class="content-width mt-8" id="radiomd-embedded-filtered-tag"></div>
                        </div>
                        <div class="col-12 more">
                            <p class="lead">Find other great episodes on other topics and from other UAMS providers.</p>
                            <div class="cta-container">
                                <a href="/podcast/" class="btn btn-primary" aria-label="More UAMS Health Talk podcast episodes">Listen to More Episodes</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php }
		// End UAMS Health Talk Podcast Section

		// Begin Clinical Resources Section
		$resource_heading_related_pre = false; // "Related Resources"
		$resource_heading_related_post = true; // "Resources Related to __"
		$resource_heading_related_name = get_the_title(); // To what is it related?
		$resource_more_suppress = false; // Force div.more to not display
		if( $show_related_resource_section ) {
			include( UAMS_FAD_PATH . '/templates/blocks/clinical-resources.php' );
		}
		// End Clinical Resources Section
		
		// Begin Clinical Trials Section
		if ( $show_clinical_trials_section ) {
			$clinical_trial_title = get_the_title();
			include( UAMS_FAD_PATH . '/templates/blocks/clinical-trials.php' );
		} // endif
		// End Clinical Trials Section
		
		// Begin Conditions Section
		if ( $show_conditions_section ) { ?>
			<section class="uams-module conditions-treatments bg-auto" id="conditions">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="module-title"><span class="title">Conditions Related to <?php echo get_the_title(); ?></span></h2>
							<p class="note">UAMS providers care for a broad range of conditions, some of which may not be listed below.</p>
							<div class="list-container list-container-rows">
								<ul class="list">
								<?php while ($conditions_query_cpt->have_posts()) : $conditions_query_cpt->the_post(); 
									$condition_id = get_the_ID();
									$condition_permalink = get_permalink( $condition_id );
									$condition_title = get_the_title();
									$condition_title_attr = str_replace('"', '\'', $condition_title);
									$condition_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($condition_title_attr, null, 'utf-8')));
								?>
									<li>
										<a href="<?php echo $condition_permalink; ?>" aria-label="Go to Condition page for <?php echo $condition_title_attr; ?>" class="btn btn-outline-primary"><?php echo $condition_title; ?></a>
									</li>
								<?php endwhile;
									  wp_reset_postdata(); ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php } // endif
		// End Conditions Section

		// Begin Providers Section
		if( $show_providers_section ) { ?>
			<section class="uams-module bg-auto" id="providers">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
						<h2 class="module-title"><span class="title">Providers Performing <?php echo get_the_title(); ?></span></h2>
						<p class="note">Note that every provider listed below may not perform or prescribe <?php echo get_the_title(); ?> for all conditions related to it. Review each provider for availability.</p>   
							<div class="card-list-container">
								<div class="card-list card-list-doctors card-list-doctors-count-<?php echo $postsCountClass; ?>">
									<?php
										while ($physicians_query->have_posts()) : $physicians_query->the_post();
											$id = get_the_ID();
											include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
										endwhile;
									?>
								</div>
							</div>
							<?php if ($postsPerPage !== -1) { ?>
							<div class="more">
								<button class="loadmore btn btn-primary" data-postids="<?php echo(implode(',', $physicians)); ?>" data-ppp="<?php echo $postsPerPage; ?>" data-postcount="<?php echo $physicians_query->found_posts; ?>" aria-label="Load more providers">Load More</button>								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</section>
		<?php
		} // $physicians_query loop
		wp_reset_postdata();
		// End Providers Section
		
		// Begin Locations Section
		if ( $show_locations_section ) {
			echo $location_content; 
		}
		// End Locations Section
		
		// Begin Areas of Expertise Section
		if ( $show_aoe_section ) { ?>
		<section class="uams-module bg-auto" id="expertise">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title"><span class="title">Areas of Expertise for <?php echo get_the_title(); ?></span></h2>
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
		if ( $show_appointment_section ) {
			include( UAMS_FAD_PATH . '/templates/blocks/appointment.php' );
		}
		// End Appointment Information Section
		?>
	</main>
</div>

<?php get_footer(); ?>