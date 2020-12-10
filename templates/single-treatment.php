<?php
	// ACF Fields - get_fields
	$keywords = get_field('treatment_procedure_alternate');

	function uamswp_keyword_hook_header() {
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
	$content = get_the_content(); //get_field( 'treatment_procedure_content' );
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

	// Locations Content
	$location_content = '';
	if (!isset($filter_region)){
		$filter_region = '';
		if (isset($_GET[ '_provider_region' ])) {
			$filter_region = explode(",", $_GET[ '_provider_region' ]);
		}
	}
	$tax_query = array();
	if (!empty($filter_region))
	{
		$tax_query[] =  array(
			'taxonomy' => 'region',
			'field'    => 'slug',
			'terms' =>  $filter_region,
			'operator' => 'IN'
		);
	}
	$args = (array(
		'post_type' => "location",
		"post_status" => "publish",
		'order' => 'ASC',
		'orderby' => 'title',
		'posts_per_page' => -1,
		"tax_query" => $tax_query,
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
		$location_content .= '<h2 class="module-title">Locations Providing ' . get_the_title() . '</h2>';
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
	
 ?>
<div class="content-sidebar-wrap">
	<main id="genesis-content" class="treatment-item<?php echo $treatment_field_classes; ?>">
		<section class="archive-description bg-white">
			<header class="entry-header">
				<h1 class="entry-title"><?php echo ( $treatment_title ? $treatment_title : 'Treatment & Procedure' ); ?>: <?php echo get_the_title(); ?></h1>
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
					<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">
					<?php echo wp_oembed_get( $video ); ?>
					</div>
				<?php } ?>
			</div>
		</section>
        <?php
            // UAMS Health Talk Podcast
            if ($podcast_name) {
        ?>
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
        <?php } ?>
		<?php
		if (!empty($clinical_trials)): ?>
			<section class="uams-module cta-bar cta-bar-1 bg-auto">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<h2>Clinical Trials</h2>
							<p><a href="https://uams.trialstoday.org/" aria-label="Search UAMS Clinical Trials">Search our clinical trials</a> for those related to <?php echo get_the_title(); ?>.</p>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>
		<?php
			$tax_query = array();
			if (!empty($filter_region))
			{
				$tax_query[] =  array(
					'taxonomy' => 'region',
					'field'    => 'slug',
					'terms' =>  $filter_region,
					'operator' => 'IN'
				);
			}
			$args = (array(
				'post_type' => "condition",
				"post_status" => "publish",
				'order' => 'ASC',
				'orderby' => 'title',
				'posts_per_page' => -1,
				"tax_query" => $tax_query,
				'post__in'	=> $conditions_cpt
			));
			$conditions_query_cpt = new WP_Query( $args );
			
			if ( $conditions_cpt && !empty($conditions_query_cpt->have_posts()) ) {
				
		?>
			<section class="uams-module conditions-treatments bg-auto">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="module-title">Conditions Related to <?php echo get_the_title(); ?></h2>
							<div class="list-container list-container-rows">
								<ul class="list">
								<?php while ($conditions_query_cpt->have_posts()) : $conditions_query_cpt->the_post(); ?>
									<li>
										<a href="<?php echo return_region(get_permalink( get_the_ID() ), $filter_region); ?>" aria-label="Go to Condition page for <?php echo get_the_title(); ?>" class="btn btn-outline-primary"><?php echo get_the_title(); ?></a>
									</li>
								<?php endwhile;
									  wp_reset_postdata(); ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php } // endif ?>
		<?php // Check if any doctors are connected	
		if ($physicians) {
			$physiciansCount = count($physicians);
			$postsPerPage = 12; // Set this value to preferred value (4, 6, 8, 10, 12)
			$postsCutoff = 18; // Set cutoff value
			$postsCountClass = $postsPerPage;
			if($physiciansCount <= $postsCutoff ) {
				$postsPerPage = -1;
			}
			$tax_query = array();
			if (!empty($filter_region))
			{
				$tax_query[] =  array(
					'taxonomy' => 'region',
					'field'    => 'slug',
					'terms' =>  $filter_region,
					'operator' => 'IN'
				);
				$postsPerPage = -1;
			}
			$args = (array(
				'post_type' => "provider",
				"post_status" => "publish",
				'order' => 'ASC',
				'orderby' => 'title',
				'posts_per_page' => $postsPerPage,
				"tax_query" => $tax_query,
				'post__in'	=> $physicians
			));
			$physicians_query = new WP_Query( $args );

			if( $physicians && $physicians_query->have_posts() ) {
			?>
				<section class="uams-module bg-auto" id="doctors">
					<div class="container-fluid">
						<div class="row">
							<div class="col-12">
							<h2 class="module-title">Providers Performing <?php echo get_the_title(); ?></h2>
							<p class="note">Note that every condition listed above may not be treated by each provider listed below. Review each provider for availability.</p>	
								<div class="card-list-container">
									<div class="card-list card-list-doctors card-list-doctors-count-<?php echo $postsCountClass; ?>">
										<?php
											while ($physicians_query->have_posts()) : $physicians_query->the_post();
												$id = get_the_ID();
												$region = $filter_region;
												include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
											endwhile;
										?>
									</div>
								</div>
								<?php if ($postsPerPage !== -1) { ?>
								<div class="more">
									<button class="loadmore btn btn-primary" data-postids="<?php echo(implode(',', $physicians)); ?>" data-ppp="<?php echo $postsPerPage; ?>" data-postcount="<?php echo $physicians_query->found_posts; ?>" data-region="<?php echo $_GET['_provider_region']; ?>" aria-label="Load more providers">Load More</button>								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</section>
			<?php
			} // $physicians_query loop
			wp_reset_postdata();
		}
		
		// Location Section
		if (!empty($location_content)) {
			echo $location_content; 
		}
		
		// Expertise Section
		$tax_query = array();
		if (!empty($filter_region))
		{
			$tax_query[] =  array(
				'taxonomy' => 'region',
				'field'    => 'slug',
				'terms' =>  $filter_region,
				'operator' => 'IN'
			);
		}
		$args = (array(
			'post_type' => "expertise",
			"post_status" => "publish",
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
			"tax_query" => $tax_query,
			'post__in'	=> $expertise
		));
		$expertise_query = new WP_Query( $args );

		if ( $expertise && $expertise_query->have_posts() ): ?>
		<section class="uams-module bg-auto" id="expertise">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title">Areas of Expertise for <?php echo get_the_title(); ?></h2>
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
		<?php endif; ?>	
		<?php
		include( UAMS_FAD_PATH . '/templates/blocks/appointment.php' );
		?>
	</main>
</div>

<?php get_footer(); ?>