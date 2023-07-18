<?php
/*
 * Template Name: Single Treatment
 */

$term = get_queried_object();

// ACF Fields - get_fields
$page_title = single_cat_title( '', false );
$page_title_attr = uamswp_attr_conversion($page_title);
$keywords = get_field('treatment_procedure_alternate', $term);
$clinical_trials = get_field('treatment_procedure_clinical_trials', $term);
$content = get_field( 'treatment_procedure_content', $term );
$excerpt = get_field( 'treatment_procedure_short_desc', $term );
$excerpt_user = true;
$video = get_field('treatment_procedure_youtube_link', $term);
$conditions = get_field('treatment_procedure_conditions', $term);
$expertise = get_field('treatment_procedure_expertise', $term);
$locations = get_field('treatment_procedure_locations', $term);
$providers = get_field('treatment_procedure_physicians', $term);
$medline_type = get_field('medline_code_type', $term);
$medline_code = get_field('medline_code_id', $term);
$embed_code = get_field('treatment_procedure_embed_codes', $term); // Embed / Syndication Code

// Get system settings for ontology item labels

	// Get system settings for provider labels
	$labels_provider = uamswp_fad_labels_provider();
		$provider_single_name = $labels_provider['provider_single_name']; // string
		$provider_single_name_attr = $labels_provider['provider_single_name_attr']; // string
		$provider_plural_name = $labels_provider['provider_plural_name']; // string
		$provider_plural_name_attr = $labels_provider['provider_plural_name_attr']; // string
		$placeholder_provider_single_name = $labels_provider['placeholder_provider_single_name']; // string
		$placeholder_provider_plural_name = $labels_provider['placeholder_provider_plural_name']; // string
		$placeholder_provider_short_name = $labels_provider['placeholder_provider_short_name']; // string
		$placeholder_provider_short_name_possessive = $labels_provider['placeholder_provider_short_name_possessive']; // string

	// Get system settings for location labels
	$labels_location = uamswp_fad_labels_location();
		$location_single_name = $labels_location['location_single_name']; // string
		$location_single_name_attr = $labels_location['location_single_name_attr']; // string
		$location_plural_name = $labels_location['location_plural_name']; // string
		$location_plural_name_attr = $labels_location['location_plural_name_attr']; // string
		$placeholder_location_single_name = $labels_location['placeholder_location_single_name']; // string
		$placeholder_location_plural_name = $labels_location['placeholder_location_plural_name']; // string
		$placeholder_location_page_title = $labels_location['placeholder_location_page_title']; // string
		$placeholder_location_page_title_phrase = $labels_location['placeholder_location_page_title_phrase']; // string

	// Get system settings for area of expertise labels
	$labels_expertise = uamswp_fad_labels_expertise();
		$expertise_single_name = $labels_expertise['expertise_single_name']; // string
		$expertise_single_name_attr = $labels_expertise['expertise_single_name_attr']; // string
		$expertise_plural_name = $labels_expertise['expertise_plural_name']; // string
		$expertise_plural_name_attr = $labels_expertise['expertise_plural_name_attr']; // string
		$placeholder_expertise_single_name = $labels_expertise['placeholder_expertise_single_name']; // string
		$placeholder_expertise_plural_name = $labels_expertise['placeholder_expertise_plural_name']; // string
		$placeholder_expertise_page_title = $labels_expertise['placeholder_expertise_page_title']; // string

	// Get system settings for clinical resource labels
	$labels_clinical_resource = uamswp_fad_labels_clinical_resource();
		$clinical_resource_single_name = $labels_clinical_resource['clinical_resource_single_name']; // string
		$clinical_resource_single_name_attr = $labels_clinical_resource['clinical_resource_single_name_attr']; // string
		$clinical_resource_plural_name = $labels_clinical_resource['clinical_resource_plural_name']; // string
		$clinical_resource_plural_name_attr = $labels_clinical_resource['clinical_resource_plural_name_attr']; // string
		$placeholder_clinical_resource_single_name = $labels_clinical_resource['placeholder_clinical_resource_single_name']; // string
		$placeholder_clinical_resource_plural_name = $labels_clinical_resource['placeholder_clinical_resource_plural_name']; // string

	// Get system settings for combined condition and treatment labels
	$labels_condition_treatment = uamswp_fad_labels_condition_treatment();
		$condition_treatment_single_name = $labels_condition_treatment['condition_treatment_single_name']; // string
		$condition_treatment_single_name_attr = $labels_condition_treatment['condition_treatment_single_name_attr']; // string
		$condition_treatment_plural_name = $labels_condition_treatment['condition_treatment_plural_name']; // string
		$condition_treatment_plural_name_attr = $labels_condition_treatment['condition_treatment_plural_name_attr']; // string
		$placeholder_condition_treatment_single_name = $labels_condition_treatment['placeholder_condition_treatment_single_name']; // string
		$placeholder_condition_treatment_plural_name = $labels_condition_treatment['placeholder_condition_treatment_plural_name']; // string

	// Get system settings for condition labels
	$labels_condition = uamswp_fad_labels_condition();
		$condition_single_name = $labels_condition['condition_single_name']; // string
		$condition_single_name_attr = $labels_condition['condition_single_name_attr']; // string
		$condition_plural_name = $labels_condition['condition_plural_name']; // string
		$condition_plural_name_attr = $labels_condition['condition_plural_name_attr']; // string
		$placeholder_condition_single_name = $labels_condition['placeholder_condition_single_name']; // string
		$placeholder_condition_plural_name = $labels_condition['placeholder_condition_plural_name']; // string

	// Get system settings for treatment labels
	$labels_treatment = uamswp_fad_labels_treatment();
		$treatment_single_name = $labels_treatment['treatment_single_name']; // string
		$treatment_single_name_attr = $labels_treatment['treatment_single_name_attr']; // string
		$treatment_plural_name = $labels_treatment['treatment_plural_name']; // string
		$treatment_plural_name_attr = $labels_treatment['treatment_plural_name_attr']; // string
		$placeholder_treatment_single_name = $labels_treatment['placeholder_treatment_single_name']; // string
		$placeholder_treatment_plural_name = $labels_treatment['placeholder_treatment_plural_name']; // string

// Get system settings for condition archive page text
uamswp_fad_archive_text_treatment();

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
add_action('wp_head','uamswp_keyword_hook_header');

// Override theme's method of defining the meta page title
$meta_title_enhanced_addition = $treatment_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title
$meta_title_vars = uamswp_fad_meta_title_vars(); // Defines universal variables related to the setting the meta title
	$meta_title = $meta_title_vars['meta_title']; // string
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

if (empty($excerpt)){
	$excerpt_user = false;
	if ($content){
		$excerpt = mb_strimwidth(wp_strip_all_tags($content), 0, 155, '...');
	}
}
// Override theme's method of defining the meta description
add_filter('seopress_titles_desc', 'uamswp_fad_meta_desc');

get_header();

// Hard coded breadcrumbs
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
	$location_content .= '<h2 class="module-title"><span class="title">' . $location_plural_name . ' Providing ' . $page_title . '</span></h2>';
	$location_content .= '<p class="note">Note that ' . $page_title . ' may not be <em>performed</em> at every ' . strtolower($location_single_name) . ' listed below. The list may include ' . strtolower($location_plural_name) . ' where the treatment plan is developed during and after a patient visit.</p>';
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
if ($providers && !empty($providers)) { $treatment_field_classes .= ' has-provider'; } // Providers

?>
<div class="content-sidebar-wrap">
	<main id="genesis-content" class="treatment-item<?php echo $treatment_field_classes; ?>">
		<section class="archive-description bg-white">
			<header class="entry-header">
				<h1 class="entry-title"><span class="supertitle"><?php echo $treatment_single_name; ?></span><span class="sr-only">: </span><?php echo $page_title; ?></h1>
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
				'taxonomy' => 'condition',
				'order' => 'ASC',
				'orderby' => 'name',
				'hide_empty' => false,
				'term_taxonomy_id' => $conditions
			));
			$condition_query = new WP_Term_Query( $args );

			if ( $conditions && !empty($condition_query->terms) ) {

		?>
			<section class="uams-module conditions-treatments bg-auto">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="module-title"><span class="title"><?php echo $clinical_resource_plural_name; ?> Related to <?php echo $page_title; ?></span></h2>
							<p class="note">UAMS Health <?php echo strtolower($provider_plural_name); ?> care for a broad range of <?php echo strtolower($clinical_resource_plural_name); ?>, some of which may not be listed below.</p>
							<div class="list-container list-container-rows">
								<ul class="list">
								<?php foreach( $condition_query->get_terms() as $condition ): ?>
									<li>
										<a href="<?php echo get_term_link( $condition->term_id ); ?>" aria-label="Go to <?php echo $provider_single_name_attr; ?> page for <?php echo $condition->name; ?>" class="btn btn-outline-primary"><?php echo $condition->name; ?></a>
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
		$provider_count = 0;
		if ($providers) {
			$provider_count = count($providers);
		}
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
							<h2 class="module-title"><span class="title"><?php echo $provider_plural_name; ?> Performing or Prescribing <?php echo $page_title; ?></span></h2>
							<p class="note">Note that every <?php echo strtolower($provider_single_name); ?> listed below may not perform or prescribe <?php echo $page_title; ?> for all <?php echo strtolower($condition_plural_name); ?> related to it. Review each <?php echo strtolower($provider_single_name); ?> for availability.</p>
							<div class="card-list-container">
								<div class="card-list card-list-doctors card-list-doctors-count-<?php echo $postsCountClass; ?>">
									<?php
										while ($provider_query->have_posts()) : $provider_query->the_post();
											$id = get_the_ID();
											include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
										endwhile;
										wp_reset_postdata();
									?>
								</div>
							</div>
							<?php if ($postsPerPage !== -1) { ?>
							<div class="more">
								<button class="loadmore btn btn-primary" data-type="taxonomy" data-tax="treatment_procedure" data-slug="<?php echo $term->slug; ?>" data-ppp="<?php echo $postsPerPage; ?>" data-postcount="<?php echo $provider_count; ?>" aria-label="Load more <?php echo strtolower($provider_plural_name_attr); ?>">Load More</button>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</section>
		<?php
		} // $provider_query loop

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