<?php
	$term = get_queried_object();

	// ACF Fields - get_fields
	$keywords = get_field('treatment_procedure_alternate', $term);
	$clinical_trials = get_field('treatment_procedure_clinical_trials', $term);
	$content = get_field( 'treatment_procedure_content', $term );
	$excerpt = get_field( 'treatment_procedure_short_desc', $term );
	$excerpt_user = true;
	$video = get_field('treatment_procedure_youtube_link', $term);
	$conditions = get_field('treatment_procedure_conditions', $term);
	$expertise = get_field('treatment_procedure_expertise', $term);
	$locations = get_field('treatment_procedure_locations', $term);
	$physicians = get_field('treatment_procedure_physicians', $term);
	$medline_type = get_field('medline_code_type', $term);
	$medline_code = get_field('medline_code_id', $term);
	$embed_code = get_field('treatment_procedure_embed_codes', $term); // Embed / Syndication Code
	if (
		( $medline_type && 'none' != $medline_type && $medline_code && !empty($medline_code) ) // if the medline plus syndication option is filled in
		|| ( $embed_code && !empty($embed_code) ) // or if the syndication embed field has a value
	) {
		$syndication = true;
	}
	else {
		$syndication = false;
	}

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

	// Override theme's method of defining the page title
	function uamswp_fad_title($html) { 
		// global $treatment_title;
		//you can add here all your conditions as if is_page(), is_category() etc.. 
		if ( strlen(single_cat_title( '', false )) < 21 ) {
			$html = single_cat_title( '', false ) . ' | Treatments & Procedures | ' . get_bloginfo( "name" );
		} else {
			$html = single_cat_title( '', false ) . ' | ' . get_bloginfo( "name" );
		}
		return $html;
	}
	add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

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
	
	get_header();

	$treatment_title = get_field('treatments_archive_headline', 'option');
	$treatment_text = get_field('treatments_archive_intro_text', 'option');

	// Hard coded breadcrumbs
	$tax = get_term_by("slug", get_query_var("term"), get_query_var("taxonomy") );

	// Locations Content
	$location_content = '';
	$args = (array(
		'post_type' => "location",
		"post_status" => "publish",
		'order' => 'ASC',
		'orderby' => 'title',
		'posts_per_page' => -1,
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
		$location_content .= '<h2 class="module-title"><span class="title">Locations Providing ' . single_cat_title( '', false ) . '</span></h2>';
		$location_content .= '<div class="card-list-container location-card-list-container">';
		$location_content .= '<div class="card-list">';
		ob_start();
		ob_clean();
		while ( $location_query->have_posts() ) : $location_query->the_post();
			$id = get_the_ID();
			include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
		endwhile;
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
	if ($keywords && !empty($keywords)) { $treatment_field_classes .= ' has-keywords'; } // Alternate names
    if ($clinical_trials && !empty($clinical_trials)) { $treatment_field_classes .= ' has-clinical-trials'; } // Display clinical trials block
    if ($content && !empty($content)) { $treatment_field_classes .= ' has-content'; } // Body content
    if ($syndication) { $treatment_field_classes .= ' has-syndication'; } // Syndication content
    if ($excerpt && $excerpt_user == true ) { $treatment_field_classes .= ' has-excerpt'; } // Short Description (Excerpt)
    if ($video && !empty($video)) { $treatment_field_classes .= ' has-video'; } // Video embed
    if ($conditions && !empty($conditions)) { $treatment_field_classes .= ' has-condition'; } // Treatments
    if ($expertise && !empty($expertise)) { $treatment_field_classes .= ' has-expertise'; } // Areas of Expertise
    if ($locations && $location_valid) { $treatment_field_classes .= ' has-location'; } // Locations
    if ($physicians && !empty($physicians)) { $treatment_field_classes .= ' has-provider'; } // Providers
	
 ?>
<div class="content-sidebar-wrap">
	<main id="genesis-content" class="treatment-item<?php echo $treatment_field_classes; ?>">
		<section class="archive-description bg-white">
			<header class="entry-header">
				<h1 class="entry-title"><?php echo ( $treatment_title ? $treatment_title : 'Treatment & Procedure' ); ?>: <?php echo single_cat_title( '', false ); ?></h1>
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
				<?php echo ( $content ? ''. $content . '' : '' ); ?>
				<?php 
					if ( $medline_type && 'none' != $medline_type && $medline_code ) {
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
		if (!empty($clinical_trials)): ?>
			<section class="uams-module cta-bar cta-bar-1 bg-auto">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<h2>Clinical Trials</h2>
							<p><a href="https://uams.trialstoday.org/" aria-label="Search UAMS Clinical Trials">Search our clinical trials</a> for those related to <?php echo single_cat_title( '', false ); ?>.</p>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>
		<?php 
			$args = (array(
				'taxonomy' => "condition",
				'order' => 'ASC',
				'orderby' => 'name',
				'hide_empty' => false,
				'term_taxonomy_id' => $conditions
			));
			$conditions_query = new WP_Term_Query( $args );
			
			if ( $conditions && !empty($conditions_query->terms) ) {
				
		?>
			<section class="uams-module conditions-treatments bg-auto">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="module-title"><span class="title">Conditions Related to <?php echo single_cat_title( '', false ); ?></span></h2>
							<div class="list-container list-container-rows">
								<ul class="list">
								<?php foreach( $conditions_query->get_terms() as $condition ): ?>
									<li>
										<a href="<?php echo get_term_link( $condition->term_id ); ?>" aria-label="Go to Condition page for <?php echo $condition->name; ?>" class="btn btn-outline-primary"><?php echo $condition->name; ?></a>
									</li>
								<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php } // endif ?>
		<?php // Check if any doctors are connected	
		$physiciansCount = 0;
		if ($physicians) {
			$physiciansCount = count($physicians);
		}
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
			'post__in'	=> $physicians
		));
		$physicians_query = new WP_Query( $args );

		if( $physicians && $physicians_query->have_posts() ) {
		?>
			<section class="uams-module bg-auto" id="doctors">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
						<h2 class="module-title"><span class="title">Providers Performing or Prescribing <?php echo single_cat_title( '', false ); ?></span></h2>
						<p class="note">Note that every condition listed above may not be treated by each provider listed below. Review each provider for availability.</p>	
							<div class="card-list-container">
								<div class="card-list card-list-doctors card-list-doctors-count-<?php echo $postsCountClass; ?>">
									<?php
										while ($physicians_query->have_posts()) : $physicians_query->the_post();
											$id = get_the_ID();
											include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
										endwhile;
										wp_reset_postdata();
									?>
								</div>
							</div>
							<?php if ($postsPerPage !== -1) { ?>
							<div class="more">
								<button class="loadmore btn btn-primary" data-type="taxonomy" data-tax="treatment_procedure" data-slug="<?php echo $term->slug; ?>" data-ppp="<?php echo $postsPerPage; ?>" data-postcount="<?php echo $physiciansCount; ?>" aria-label="Load more providers">Load More</button>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</section>
		<?php
		} // $physicians_query loop
		
		// Location Section
		if (!empty($location_content)) {
			echo $location_content; 
		}
		
		// Expertise Section
		$args = (array(
			'post_type' => "expertise",
			"post_status" => "publish",
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
			'post__in'	=> $expertise
		));
		$expertise_query = new WP_Query( $args );

		if ( $expertise && $expertise_query->have_posts() ): ?>
		<section class="uams-module bg-auto" id="expertise">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title"><span class="title">Areas of Expertise for <?php echo single_cat_title( '', false ); ?></span></h2>
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