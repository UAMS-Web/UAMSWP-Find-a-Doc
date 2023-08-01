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

		$labels_provider_vars = isset($labels_provider_vars) ? $labels_provider_vars : uamswp_fad_labels_provider();
			$provider_single_name = $labels_provider_vars['provider_single_name']; // string
			$provider_single_name_attr = $labels_provider_vars['provider_single_name_attr']; // string
			$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string
			$provider_plural_name_attr = $labels_provider_vars['provider_plural_name_attr']; // string
			$placeholder_provider_single_name = $labels_provider_vars['placeholder_provider_single_name']; // string
			$placeholder_provider_plural_name = $labels_provider_vars['placeholder_provider_plural_name']; // string
			$placeholder_provider_short_name = $labels_provider_vars['placeholder_provider_short_name']; // string
			$placeholder_provider_short_name_possessive = $labels_provider_vars['placeholder_provider_short_name_possessive']; // string

	// Get system settings for location labels

		$labels_location_vars = isset($labels_location_vars) ? $labels_location_vars : uamswp_fad_labels_location();
			$location_single_name = $labels_location_vars['location_single_name']; // string
			$location_single_name_attr = $labels_location_vars['location_single_name_attr']; // string
			$location_plural_name = $labels_location_vars['location_plural_name']; // string
			$location_plural_name_attr = $labels_location_vars['location_plural_name_attr']; // string
			$placeholder_location_single_name = $labels_location_vars['placeholder_location_single_name']; // string
			$placeholder_location_plural_name = $labels_location_vars['placeholder_location_plural_name']; // string
			$placeholder_location_page_title = $labels_location_vars['placeholder_location_page_title']; // string
			$placeholder_location_page_title_phrase = $labels_location_vars['placeholder_location_page_title_phrase']; // string

	// Get system settings for area of expertise labels

		$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
			$expertise_single_name = $labels_expertise_vars['expertise_single_name']; // string
			$expertise_single_name_attr = $labels_expertise_vars['expertise_single_name_attr']; // string
			$expertise_plural_name = $labels_expertise_vars['expertise_plural_name']; // string
			$expertise_plural_name_attr = $labels_expertise_vars['expertise_plural_name_attr']; // string
			$placeholder_expertise_single_name = $labels_expertise_vars['placeholder_expertise_single_name']; // string
			$placeholder_expertise_plural_name = $labels_expertise_vars['placeholder_expertise_plural_name']; // string
			$placeholder_expertise_page_title = $labels_expertise_vars['placeholder_expertise_page_title']; // string

	// Get system settings for clinical resource labels

		$labels_clinical_resource_vars = isset($labels_clinical_resource_vars) ? $labels_clinical_resource_vars : uamswp_fad_labels_clinical_resource();
			$clinical_resource_single_name = $labels_clinical_resource_vars['clinical_resource_single_name']; // string
			$clinical_resource_single_name_attr = $labels_clinical_resource_vars['clinical_resource_single_name_attr']; // string
			$clinical_resource_plural_name = $labels_clinical_resource_vars['clinical_resource_plural_name']; // string
			$clinical_resource_plural_name_attr = $labels_clinical_resource_vars['clinical_resource_plural_name_attr']; // string
			$placeholder_clinical_resource_single_name = $labels_clinical_resource_vars['placeholder_clinical_resource_single_name']; // string
			$placeholder_clinical_resource_plural_name = $labels_clinical_resource_vars['placeholder_clinical_resource_plural_name']; // string

	// Get system settings for combined condition and treatment labels

		$labels_condition_treatment_vars = isset($labels_condition_treatment_vars) ? $labels_condition_treatment_vars : uamswp_fad_labels_condition_treatment();
			$condition_treatment_single_name = $labels_condition_treatment_vars['condition_treatment_single_name']; // string
			$condition_treatment_single_name_attr = $labels_condition_treatment_vars['condition_treatment_single_name_attr']; // string
			$condition_treatment_plural_name = $labels_condition_treatment_vars['condition_treatment_plural_name']; // string
			$condition_treatment_plural_name_attr = $labels_condition_treatment_vars['condition_treatment_plural_name_attr']; // string
			$placeholder_condition_treatment_single_name = $labels_condition_treatment_vars['placeholder_condition_treatment_single_name']; // string
			$placeholder_condition_treatment_plural_name = $labels_condition_treatment_vars['placeholder_condition_treatment_plural_name']; // string

	// Get system settings for condition labels

		$labels_condition_vars = isset($labels_condition_vars) ? $labels_condition_vars : uamswp_fad_labels_condition();
			$condition_single_name = $labels_condition_vars['condition_single_name']; // string
			$condition_single_name_attr = $labels_condition_vars['condition_single_name_attr']; // string
			$condition_plural_name = $labels_condition_vars['condition_plural_name']; // string
			$condition_plural_name_attr = $labels_condition_vars['condition_plural_name_attr']; // string
			$placeholder_condition_single_name = $labels_condition_vars['placeholder_condition_single_name']; // string
			$placeholder_condition_plural_name = $labels_condition_vars['placeholder_condition_plural_name']; // string

	// Get system settings for treatment labels

		$labels_treatment_vars = isset($labels_treatment_vars) ? $labels_treatment_vars : uamswp_fad_labels_treatment();
			$treatment_single_name = $labels_treatment_vars['treatment_single_name']; // string
			$treatment_single_name_attr = $labels_treatment_vars['treatment_single_name_attr']; // string
			$treatment_plural_name = $labels_treatment_vars['treatment_plural_name']; // string
			$treatment_plural_name_attr = $labels_treatment_vars['treatment_plural_name_attr']; // string
			$placeholder_treatment_single_name = $labels_treatment_vars['placeholder_treatment_single_name']; // string
			$placeholder_treatment_plural_name = $labels_treatment_vars['placeholder_treatment_plural_name']; // string

// Get system settings for this post type's archive page text

	$archive_text_condition_vars = isset($archive_text_condition_vars) ? $archive_text_condition_vars : uamswp_fad_archive_text_condition();
		$condition_archive_headline = $archive_text_condition_vars['condition_archive_headline']; // string
		$condition_archive_headline_attr = $archive_text_condition_vars['condition_archive_headline_attr']; // string
		$condition_archive_intro_text = $archive_text_condition_vars['condition_archive_intro_text']; // string
		$placeholder_condition_archive_headline = $archive_text_condition_vars['placeholder_condition_archive_headline']; // string
		$placeholder_condition_archive_intro_text = $archive_text_condition_vars['placeholder_condition_archive_intro_text']; // string

// Override theme's method of defining the meta page title

	// Construct the meta title

		$meta_title_enhanced_addition = $condition_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title
		$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(
			$page_title, // string
			$page_title_attr, // string (optional)
			'', // string (optional) // Word or phrase to use to form base meta title // Defaults to $page_title_attr
			'', // array (optional) // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number
			$meta_title_enhanced_addition // string (optional) // Word or phrase to inject into base meta title to form enhanced meta title level 1
		);
			$meta_title = $meta_title_vars['meta_title']; // string

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
		$location_content .= '<h2 class="module-title"><span class="title">' . $location_plural_name . ' Where Providers Treat ' . $page_title . '</span></h2>';
		$location_content .= '<p class="note">Note that the treatment of ' . $page_title . ' may not be <em>performed</em> at every ' . strtolower($location_single_name) . ' listed below. The list may include ' . strtolower($location_plural_name) . ' where the treatment plan is developed during and after a patient visit.</p>';
		$location_content .= '<div class="card-list-container location-card-list-container">';
		$location_content .= '<div class="card-list">';
		ob_start();
		ob_clean();
		while ( $location_query->have_posts() ) : $location_query->the_post();
			$page_id = get_the_ID();
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
													include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
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