<?php
	function uamswp_keyword_hook_header() {
		$keywords = get_field('condition_alternate', get_queried_object());
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
	add_action('wp_head','uamswp_keyword_hook_header');
	   
	   get_header();
	   
	$condition_title = get_field('conditions_archive_headline', 'option');
	$condition_text = get_field('conditions_archive_intro_text', 'option');

	$term = get_queried_object();

	// Hard coded breadcrumbs
	$tax = get_term_by("slug", get_query_var("term"), get_query_var("taxonomy") );
 ?>
<div class="content-sidebar-wrap">
	<main id="genesis-content">
		<section class="archive-description bg-white">
			<header class="entry-header">
				<h1 class="entry-title" itemprop="headline"><?php echo ( $condition_title ? $condition_title : 'Condition' ); ?>: <?php echo single_cat_title( '', false ); ?></h1>
			</header>
			<div class="entry-content clearfix" itemprop="text">
				<?php 
					$keywords = get_field('condition_alternate', $term);
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
				<?php echo ( get_field('condition_content', $term) ? ''. get_field('condition_content', $term) . '' : '' ); ?>
				<?php if( get_field('condition_youtube_link', $term) ) { ?>
					<div class="embed-responsive embed-responsive-16by9">
					<?php echo wp_oembed_get( get_field('condition_youtube_link', $term) ); ?>
					</div>
				<?php } ?>
			</div>
		</section>
		<?php
		$clinical_trials = get_field('condition_clinical_trials', $term);
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
			$treatments = get_field('condition_treatments', $term);
			$args = (array(
				'taxonomy' => "treatment_procedure",
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
									<a href="<?php echo get_term_link( $treatment->term_id ); ?>">
									<?php
										echo $treatment->name;
									?>
									</a>
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
			$doctorQuery = new WP_Query([
					"post_type" => "physicians",
					"post_status" => "publish",
					"posts_per_page" => 1,
					"tax_query" => array(
					array(
						"taxonomy" => "condition",
						"field" => "slug",
						"terms" => get_queried_object()->slug,
						"operator" => "IN"
					)
					)
			]);
			if($doctorQuery->have_posts()) : 
				$postsPerPage = 6; // Set this value to preferred value
				$postsCutoff = 12; // Set cutoff value
				if($doctorQuery->found_posts <= $postsCutoff ) { 
					$postsPerPage = -1;
				}
				$args = array(
					"post_type" => "physicians",
					"post_status" => "publish",
					"posts_per_page" => $postsPerPage,
					"orderby" => "title",
					"order" => "ASC",
					"tax_query" => array(
						array(
						"taxonomy" => "condition",
						"field" => "slug",
						"terms" => get_queried_object()->slug,
						"operator" => "IN"
						)
					)
				);
				$physicians_query = New WP_Query( $args );
				if( $physicians_query->have_posts() ) { 
				?>
					<section class="uams-module bg-auto" id="doctors">
						<div class="container-fluid">
							<div class="row">
								<div class="col-12">
									<h2 class="module-title">Doctors Treating <?php echo single_cat_title( '', false ); ?></h2>
									<p class="note">Note that every treatment or procedure listed above may not be provided by each doctor listed below. Review each doctor for availability.</p>
									<div class="card-list-container">
										<div class="card-list card-list-doctors">
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
										<button class="loadmore btn btn-primary stretched-link" data-type="taxonomy" data-tax="condition" data-slug="<?php echo get_queried_object()->slug; ?>" data-ppp="<?php echo $postsPerPage; ?>" data-postcount="<?php echo $doctorQuery->found_posts; ?>">Load More</button>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</section>
				<?php
				} // $physicians_query loop
			endif; ?>
		<?php 
			$locations = get_field('condition_locations', $term);
			$args = (array(
				'post_type' => "locations",
				"post_status" => "publish",
				'order' => 'ASC',
				'orderby' => 'title',
				'posts_per_page' => -1,
				'post__in'	=> $locations
			));
			$location_query = new WP_Query( $args );

			if ( $locations && $location_query->have_posts() ) : ?>
		<section class="container-fluid p-8 p-sm-10 bg-auto" id="locations">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title">Locations Where <?php echo single_cat_title( '', false ); ?> Is Treated</h2>
					<div class="card-list-container location-card-list-container">
						<div class="card-list">
						<?php 
							while ( $location_query->have_posts() ) : $location_query->the_post();
								$id = get_the_ID();
								include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
							endwhile; 
						?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php wp_reset_postdata();
		 endif; 
		  
			$expertise = get_field('condition_expertise', $term);
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
			<section class="container-fluid p-8 p-sm-10 bg-auto" id="expertise">
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
			</section>
			<?php endif; ?>	
		<?php
			include( UAMS_FAD_PATH . '/templates/blocks/appointment.php' );
		?>
	</main>
</div>


<?php get_footer(); ?>