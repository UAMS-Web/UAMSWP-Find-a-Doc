<?php
/*
 * Template Name: Single Condition
 */

$term = get_queried_object();

// ACF Fields - get_fields

	$clinical_trials = get_field('condition_clinical_trials', $term);
	$video = get_field('condition_youtube_link', $term);
	$treatments = get_field('condition_treatments', $term);
	$expertise = get_field('condition_expertise', $term);
	$locations = get_field('condition_locations', $term);
	$providers = get_field('condition_physicians', $term);

// Medline / Syndication

	$medline_type = get_field('medline_code_type', $term);
	$medline_code = get_field('medline_code_id', $term);
	$embed_code = get_field('condition_embed_codes', $term); // Embed / Syndication Code
	if (
		( $medline_type && 'none' != $medline_type && $medline_code && !empty($medline_code) ) // if the medline plus syndication option is filled in
		|| ( $embed_code && !empty($embed_code) ) // or if the syndication embed field has a value
	) {
		$syndication = true;
	}
	else {
		$syndication = false;
	}

// Construct the meta keywords element

	$keywords = get_field('condition_alternate', $term);

	add_action( 'wp_head', function() use ($keywords) {
		uamswp_keyword_hook_header(
			$keywords // array
		);
	} );


// Page title

	$page_title = single_cat_title( '', false );
	$page_title_attr = uamswp_attr_conversion($page_title);

	// Array for page titles and section titles

		$page_titles = array(
			'page_title'		=> $page_title,
			'page_title_attr'	=> $page_title_attr
		);

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

// Get system settings for this post type's archive page text
include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/condition.php' );

// Override theme's method of defining the meta page title

	// Construct the meta title

		$meta_title_enhanced_addition = $condition_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title
		include( UAMS_FAD_PATH . '/templates/parts/html/meta/title.php' );

	// Modify SEOPress's standard meta title settings

		add_filter( 'seopress_titles_title', function( $html ) use ( $meta_title ) {

			$html = $meta_title;

			return $html;

		}, 15, 2 );

// Set the schema description and the meta description

	// Get excerpt

		$excerpt = get_field( 'treatment_procedure_short_desc', $term );
		$excerpt_user = true;

	// Get the content

		$content = get_field( 'treatment_procedure_content', $term );

	// Create excerpt if none exists

		if ( empty( $excerpt ) ) {

			$excerpt_user = false;

			if ( $content ) {

				$excerpt = mb_strimwidth(wp_strip_all_tags($content), 0, 155, '...');

			}

		}

	// Set schema description

		$schema_description = $excerpt; // Used for Schema Data. Should ALWAYS have a value

	// Override theme's method of defining the meta description

		add_filter('seopress_titles_desc', function( $html ) use ( $excerpt ) {

			$html = $excerpt;

			return $html;

		} );

get_header();

// Hard-coded breadcrumbs
	$tax = get_term_by("slug", get_query_var("term"), get_query_var("taxonomy") );

// Locations Content

	$location_content = '';
	$args = (array(
		'post_type' => 'location',
		'post_status' => 'publish',
		'order' => 'ASC',
		'orderby' => 'title',
		'posts_per_page' => -1,
		'post__in'	=> $locations
	));
	$location_query = new WP_Query( $args );

	// Check for valid locations

		$location_valid = false;
		$location_section_show = false;
		if ( $locations && $location_query->have_posts() ) {
			foreach( $locations as $location ) {
				if ( get_post_status ( $location ) == 'publish' ) {
					$location_valid = true;
					$location_section_show = true;
					$break;
				}
			}
		}

	if ( $location_valid ) {
		$location_content .= '<section class="uams-module bg-auto" id="locations">';
		$location_content .= '<div class="container-fluid">';
		$location_content .= '<div class="row">';
		$location_content .= '<div class="col-12">';
		$location_content .= '<h2 class="module-title"><span class="title">' . $location_plural_name . ' Where Providers Treat ' . $page_title . '</span></h2>';
		$location_content .= '<p class="note">Note that the treatment of ' . $page_title . ' may not be <em>performed</em> at every ' . strtolower($location_single_name) . ' listed below. The list may include ' . strtolower($location_plural_name) . ' where the treatment plan is developed during and after a patient visit.</p>';
		$location_content .= '<div class="card-list-container location-card-list-container">';
		$location_content .= '<div class="card-list">';
		ob_start();
		ob_clean();
		while ( $location_query->have_posts() ) : $location_query->the_post();
			$page_id = get_the_ID();
			include( UAMS_FAD_PATH . '/templates/parts/html/cards/location.php' );
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
	if ($syndication) { $condition_field_classes .= ' has-syndication'; } // Syndication content
	if ($excerpt && $excerpt_user == true ) { $condition_field_classes .= ' has-excerpt'; } // Short Description (Excerpt)
	if ($video && !empty($video)) { $condition_field_classes .= ' has-video'; } // Video embed
	if ($treatments && !empty($treatments)) { $condition_field_classes .= ' has-treatment'; } // Treatments
	if ($expertise && !empty($expertise)) { $condition_field_classes .= ' has-expertise'; } // Areas of Expertise
	if ($locations && $location_valid) { $condition_field_classes .= ' has-location'; } // Locations
	if ($providers && !empty($providers)) { $condition_field_classes .= ' has-provider'; } // Providers

?>
<div class="content-sidebar-wrap">
	<main id="genesis-content" class="condition-item<?php echo $condition_field_classes; ?>">
		<section class="archive-description bg-white">
			<header class="entry-header">
				<h1 class="entry-title" itemprop="headline"><?php echo $condition_archive_headline; ?><span class="sr-only">: </span><?php echo $page_title; ?></h1>
			</header>
			<div class="entry-content clearfix" itemprop="text">
				<?php

				$keyword_text = '';

				if( $keywords ):

					$i = 1;

					foreach ( $keywords as $keyword ) {

						if ( 1 < $i ) {

							$keyword_text .= '; ';

						}

						$keyword_text .= $keyword['alternate_text'];
						$i++;

					}

					echo '<p class="text-callout text-callout-info">Also called: '. $keyword_text .'</p>';

				endif;

				echo ( $content ? ''. $content . '' : '' );

				if ( $medline_type && 'none' != $medline_type && $medline_code ) {

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
		if (!empty($clinical_trials)): ?>
		<section class="uams-module cta-bar cta-bar-1 bg-auto">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2>Clinical Trials</h2>
						<p><a href="https://uams.trialstoday.org/" aria-label="Search UAMS Clinical Trials">Search our clinical trials</a> for those related to <?php echo $page_title; ?>.</p>
					</div>
				</div>
			</div>
		</section>
		<?php endif; ?>
		<?php
			$args = (array(
				'taxonomy' => 'treatment',
				'order' => 'ASC',
				'orderby' => 'name',
				'hide_empty' => false,
				'term_taxonomy_id' => $treatments
			));
			$treatment_query = new WP_Term_Query( $args );
			if ( $treatments && !empty($treatment_query->terms) ) {

		?>
		<section class="uams-module conditions-treatments bg-auto">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="module-title"><span class="title"><?php echo $treatment_plural_name; ?> Related to <?php echo $page_title; ?></span></h2>
						<p class="note">UAMS Health <?php echo strtolower($provider_plural_name); ?> perform and prescribe a broad range of <?php echo strtolower($treatment_plural_name); ?>, some of which may not be listed below.</p>
						<div class="list-container list-container-rows">
							<ul class="list">
							<?php foreach( $treatment_query->get_terms() as $treatment ): ?>
								<li>
									<a href="<?php echo get_term_link( $treatment->term_id ); ?>" aria-label="Go to <?php echo $treatment_single_name_attr; ?> page for <?php echo $treatment->name; ?>" class="btn btn-outline-primary"><?php echo $treatment->name; ?></a>
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
			if ($providers) {
				$provider_count = count($providers);
				$postsPerPage = 12; // Set this value to preferred value (4, 6, 8, 10, 12)
				$postsCutoff = 18; // Set cutoff value
				$postsCountClass = $postsPerPage;
				if($provider_count <= $postsCutoff ) {
						$postsPerPage = -1;
					}
				$args = (array(
					'post_type' => 'provider',
					'post_status' => 'publish',
					'order' => 'ASC',
					'orderby' => 'title',
					'posts_per_page' => $postsPerPage,
					'post__in'	=> $providers
				));
				$provider_query = new WP_Query( $args );

				if( $providers && $provider_query->have_posts() ) {
				?>
					<section class="uams-module bg-auto" id="doctors">
						<div class="container-fluid">
							<div class="row">
								<div class="col-12">
									<h2 class="module-title"><span class="title"><?php echo $provider_plural_name; ?> Diagnosing or Treating <?php echo $page_title; ?></span></h2>
									<p class="note">Note that every <?php echo strtolower($provider_single_name); ?> listed below may not perform or prescribe all <?php echo strtolower($provider_plural_name); ?> related to <?php echo $page_title; ?>. Review each <?php echo strtolower($provider_single_name); ?> for availability.</p>
									<div class="card-list-container">
										<div class="card-list card-list-doctors card-list-doctors-count-<?php echo $postsCountClass; ?>">
											<?php
												while ($provider_query->have_posts()) : $provider_query->the_post();
													$page_id = get_the_ID();
													include( UAMS_FAD_PATH . '/templates/parts/html/cards/provider.php' );
												endwhile;
												wp_reset_postdata();
											?>
										</div>
									</div>
									<?php if ($postsPerPage !== -1) { ?>
									<div class="more">
										<button class="loadmore btn btn-primary" data-type="taxonomy" data-tax="condition" data-slug="<?php echo $term->slug; ?>" data-ppp="<?php echo $postsPerPage; ?>" data-postcount="<?php echo $provider_count; ?>" aria-label="Load more <?php echo strtolower($provider_plural_name_attr); ?>">Load More</button>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</section>
				<?php
				} // $provider_query loop
			}

			// Location Section
			if (!empty($location_content)) {
				echo $location_content;
			}

			// Expertise Section
			$args = (array(
				'post_type' => 'expertise',
				'post_status' => 'publish',
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
							<h2 class="module-title"><span class="title"><?php echo $expertise_plural_name; ?> for <?php echo $page_title; ?></span></h2>
							<div class="card-list-container">
								<div class="card-list card-list-expertise">
								<?php
									while ( $expertise_query->have_posts() ) : $expertise_query->the_post();
										$page_id = get_the_ID();
										include( UAMS_FAD_PATH . '/templates/parts/html/cards/expertise.php' );
									endwhile;
									wp_reset_postdata();
								?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<?php endif;

			$appointment_section_show = true; // It should always be displayed.
			include( UAMS_FAD_PATH . '/templates/parts/html/section/appointment.php' );

		?>
	</main>
</div>
<?php get_footer(); ?>