<?php
	$term = get_queried_object();

	// ACF Fields - get_fields
	$keywords = get_field('condition_alternate', $term);
	$clinical_trials = get_field('condition_clinical_trials', $term);
	$content = get_field( 'condition_content', $term );
	$excerpt = get_field( 'condition_short_desc', $term );
	$excerpt_user = true;
	$video = get_field('condition_youtube_link', $term);
	$treatments = get_field('condition_treatments', $term);
	$expertise = get_field('condition_expertise', $term);
	$locations = get_field('condition_locations', $term);
	$physicians = get_field('condition_physicians', $term);

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
		// global $condition_title;
		//you can add here all your conditions as if is_page(), is_category() etc.. 
		$html = single_cat_title( '', false ) . ' | ' . get_bloginfo( "name" );
		return $html;
	}
	add_filter('pre_get_document_title', 'uamswp_fad_title', 15, 2);

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
	   
	$condition_title = get_field('conditions_archive_headline', 'option');
	$condition_text = get_field('conditions_archive_intro_text', 'option');

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
		$location_content .= '<h2 class="module-title">Locations Providing ' . single_cat_title( '', false ) . '</h2>';
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
    $condition_field_classes = '';	
    if ($keywords && !empty($keywords)) { $condition_field_classes .= ' has-keywords'; } // Alternate names
    if ($clinical_trials && !empty($clinical_trials)) { $condition_field_classes .= ' has-clinical-trials'; } // Display clinical trials block
    if ($content && !empty($content)) { $condition_field_classes .= ' has-content'; } // Body content
    if ($excerpt && $excerpt_user == true ) { $condition_field_classes .= ' has-excerpt'; } // Short Description (Excerpt)
    if ($video && !empty($video)) { $condition_field_classes .= ' has-video'; } // Video embed
    if ($treatments && !empty($treatments)) { $condition_field_classes .= ' has-treatment'; } // Treatments
    if ($expertise && !empty($expertise)) { $condition_field_classes .= ' has-expertise'; } // Areas of Expertise
    if ($locations && $location_valid) { $condition_field_classes .= ' has-location'; } // Locations
    if ($physicians && !empty($physicians)) { $condition_field_classes .= ' has-provider'; } // Providers

 ?>
<div class="content-sidebar-wrap">
	<main id="genesis-content" class="condition-item<?php echo $condition_field_classes; ?>">
		<section class="archive-description bg-white">
			<header class="entry-header">
				<h1 class="entry-title" itemprop="headline"><?php echo ( $condition_title ? $condition_title : 'Condition' ); ?>: <?php echo single_cat_title( '', false ); ?></h1>
			</header>
			<div class="entry-content clearfix" itemprop="text">
				<?php 
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
				?>
				<?php echo ( $content ? ''. $content . '' : '' ); ?>
				<?php if( $video ) { ?>
					<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">
					<?php echo wp_oembed_get( $video ); ?>
					</div>
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
				'taxonomy' => "treatment",
				'order' => 'ASC',
				'orderby' => 'name',
				'hide_empty' => false,
				'term_taxonomy_id' => $treatments
			));
			$treatments_query = new WP_Term_Query( $args );
			if ( $treatments && !empty($treatments_query->terms) ) {
				
		?>
		<section class="uams-module conditions-treatments bg-auto">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="module-title">Treatments and Procedures Related to <?php echo single_cat_title( '', false ); ?></h2>
						<div class="list-container list-container-rows">
							<ul class="list">
							<?php foreach( $treatments_query->get_terms() as $treatment ): ?>
								<li>
									<a href="<?php echo get_term_link( $treatment->term_id ); ?>" aria-label="Go to Treatment page for <?php echo $treatment->name; ?>"><?php echo $treatment->name; ?></a>
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
				'post__in'	=> $physicians
			));
			$physicians_query = new WP_Query( $args );

			if( $physicians && $physicians_query->have_posts() ) {
			?>
				<section class="uams-module bg-auto" id="doctors">
					<div class="container-fluid">
						<div class="row">
							<div class="col-12">
								<h2 class="module-title">Providers Treating <?php echo single_cat_title( '', false ); ?></h2>
								<p class="note">Note that every treatment or procedure listed above may not be provided by each provider listed below. Review each provider for availability.</p>
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
									<button class="loadmore btn btn-primary" data-type="taxonomy" data-tax="condition" data-slug="<?php echo $term->slug; ?>" data-ppp="<?php echo $postsPerPage; ?>" data-postcount="<?php echo $physiciansCount; ?>" aria-label="Load more providers">Load More</button>
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
							<h2 class="module-title">Areas of Expertise for <?php echo single_cat_title( '', false ); ?></h2>
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