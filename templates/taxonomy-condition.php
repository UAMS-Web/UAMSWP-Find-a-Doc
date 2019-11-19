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
<div class="content-sidebar-wrap row">
	<main class="content col-sm-12" id="genesis-content">
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
						echo '<p>Possible alternate names: '. $keyword_text .'</p>';
					endif;
				?>
				<?php echo (get_field('condition_content', $term) ? ''. get_field('condition_content', $term) . '' : '' ); ?>
			</div>
		</section>
		<?php
		$clinical_trials = get_field('condition_clinical_trials', $term);
		if (!empty($clinical_trials)): ?>
		<section class="container-fluid p-8 p-sm-10 cta-bar cta-bar-1 bg-auto">
			<div class="row">
				<div class="col-xs-12">
					<h2>Clinical Trials</h2>
					<p><a href="https://uams.trialstoday.org/" aria-label="Search UAMS Clinical Trials">Search our clinical trials</a> for those related to <?php echo single_cat_title( '', false ); ?>.</p>
				</div>
			</div>
		</section>
		<?php endif; ?>
		<?php
		if(get_field('condition_youtube_link', $term)) { ?>
			<section class="container-fluid p-8 p-sm-10 bg-auto">
				<div class="row">
					<div class="col-12">
						<div class="embed-responsive embed-responsive-16by9">
							<?php echo wp_oembed_get( get_field( 'condition_youtube_link', $term ) ); ?>
						</div>
					</div>
				</div>
			</section>
		<?php 
		} 
			$treatments = get_field('condition_treatments', $term);
			//echo count($treatments);

			// print_r($treatments);
			
			if (!empty($treatments) && 0 < count($treatments)) {
				
		?>
		<section class="container-fluid p-8 p-sm-10 conditions-treatments bg-auto">
			<div class="row">
				<div class="col-xs-12">
					<h2 class="module-title">Treatments and Procedures Related to <?php echo single_cat_title( '', false ); ?></h2>
					<div class="list-container list-container-rows">
						<ul class="list">
						<?php foreach( $treatments as $treatment ) { ?> 
						<li><a href="<?php echo get_term_link($treatment, 'treatment_procedure'); ?>"><?php echo( get_term( $treatment, 'treatment_procedure' )->name ); ?></a></li>
						<?php } ?>
						</ul>
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
			if($doctorQuery->have_posts()) : ?>
		<section class="container-fluid p-8 p-sm-10 bg-auto" id="doctors">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title">Doctors Treating <?php echo single_cat_title( '', false ); ?></h2>
					<p class="note">Note that every treatment or procedure listed above may not be provided by each doctor listed below. Review each doctor for availability.</p>
					<div class="card-list-container">
						<div class="card-list card-list-doctors facetwp-template">
							<?php echo facetwp_display( 'template', 'condition_physicians' ); ?>
						</div>
					</div>
					<div class="row list-pagination">
						<div class="col">
							<?php echo facetwp_display( 'pager' ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php // FacetWP Hide elements
					// Set # value depending on element
					?>
			<script>
				(function($) {
					$(document).on('facetwp-loaded', function() {
						if( 0 === FWP.settings.pager.total_rows ) {
							$('#doctors').hide()
						}
						if (3 >= FWP.settings.pager.total_rows ) {
							$('.list-pagination').hide()
						}
					});
				})(jQuery);
			</script>
		</section>
		<?php endif; ?>
		<?php 
			$args = (array(
				'post_type' => "locations",
				'order' => 'ASC',
				'orderby' => 'title',
				'posts_per_page' => -1,
				'tax_query' => array(
					array(
					"taxonomy" => "condition",
					"field" => "slug",
					"terms" => get_queried_object()->slug,
					"operator" => "IN"
					)
				)
			));
			$location_query = new WP_Query( $args );

			if ( $location_query->have_posts() ) : ?>
		<section class="container-fluid p-8 p-sm-10 bg-auto" id="locations">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title">Locations Where <?php echo single_cat_title( '', false ); ?> Is Treated</h2>
					<div class="card-list-container">
						<div class="card-list card-list-locations">
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
		<?php endif; ?>
		<?php 
			$args = (array(
				'post_type' => "expertise",
				'order' => 'ASC',
				'orderby' => 'title',
				'posts_per_page' => -1,
				'tax_query' => array(
					array(
					"taxonomy" => "condition",
					"field" => "slug",
					"terms" => get_queried_object()->slug,
					"operator" => "IN"
					)
				)
			));
			$expertise_query = new WP_Query( $args );

			if ( $expertise_query->have_posts() ) : ?>
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