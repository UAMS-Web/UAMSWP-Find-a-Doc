<?php
/*
 * Template Name: Areas of Expertise Archive
 */

// Add page template class to body element's classes

	// Do nothing

// Filter posts_where

	// Do nothing

// Filter terms_clauses

	// Do nothing

// Get system settings for ontology item labels

	// Get system settings for area of expertise labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

// Get system settings for area of expertise archive text
include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/expertise.php' );

// Get the page ID

	// $page_id = get_the_ID(); // int

// Get the page title

	$page_title = $expertise_archive_headline; // string
	$page_title_attr = uamswp_attr_conversion($page_title);

	// Array for page titles and section titles

		$page_titles = array(
			'page_title'		=> $page_title,
			'page_title_attr'	=> $page_title_attr
		);

// Get the page URL

	// $page_url = user_trailingslashit(get_permalink());

// alpha

	// Do nothing

// Get the system settings for the image elements of the area of expertise archive
include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/archive/expertise.php' );

// Get the featured image

	$featured_image = $expertise_archive_image; // Image ID // int

// Override theme's method of defining the meta page title

	// Construct the meta title

		$meta_title_base_addition = $expertise_plural_name_attr; // Word or phrase to use to form base meta title
		include( UAMS_FAD_PATH . '/templates/parts/html/meta/title.php' );

	// Modify SEOPress's standard meta title settings

		add_filter( 'seopress_titles_title', function( $html ) use ( $meta_title ) {

			$html = $meta_title;

			return $html;

		}, 15, 2 );

// Set the schema description and the meta description

	// Get excerpt

		$excerpt = $expertise_archive_intro_text;
		$excerpt_user = true;

		if ( empty( $excerpt ) ) {

			$excerpt_user = false;

		}

	// Set schema description

		$schema_description = $excerpt; // Used for Schema Data. Should ALWAYS have a value

	// Override theme's method of defining the meta description

		add_filter('seopress_titles_desc', function( $html ) use ( $excerpt ) {

			$html = $excerpt;

			return $html;

		} );

// Construct the meta keywords element

	// $keywords = '';
	// 
	// add_action( 'wp_head', function() use ($keywords) {
	// 	uamswp_keyword_hook_header(
	// 		$keywords // array
	// 	);
	// } );

// Override the theme's method of defining the social media meta tags

	// Filter hooks
	include( UAMS_FAD_PATH . '/templates/parts/html/meta/social.php' );

get_header();

add_filter( 'facetwp_template_use_archive', '__return_true' );

?>
<div class="content-sidebar-wrap">
	<main id="genesis-content">
		<section class="archive-description">
			<header class="entry-header">
				<h1 class="entry-title" itemprop="headline"><?php echo $page_title; ?></h1>
			</header>
			<?php echo ($expertise_archive_intro_text ? '<div class="entry-content clearfix" itemprop="text"><div class="archive-intro">' . $expertise_archive_intro_text . '</div></div>' : '' ); ?>
		</section>
		<section class="uams-module" id="expertise">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="module-title sr-only">List of <?php echo $expertise_plural_name; ?></h2>
						<div class="card-list-container">
							<div class="card-list card-list-expertise">
								<?php echo facetwp_display( 'template', 'expertise' ); ?>
								<?php //get_template_part( 'templates/physician-loop' ); ?>
							</div>
						</div>
						<div class="row list-pagination">
							<div class="col">
								<?php echo facetwp_display( 'pager' ); ?>
							</div>
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
						if (3 >= FWP.settings.pager.total_rows ) {
							$('.list-pagination').hide()
						}
						if (FWP.loaded) {
							$('html, body').animate({
								scrollTop: $('main').offset().top
							}, 500);
						}
					});
				})(jQuery);
			</script>
		</section>
	</main>
</div>
<?php get_footer(); ?>