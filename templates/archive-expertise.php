<?php
/*
 * Template Name: Areas of Expertise Archive
 */

// Get system settings for Areas of Expertise Labels
uamswp_fad_labels_expertise();

// Get system settings for Areas of Expertise Archive Page
uamswp_fad_archive_expertise();
// $expertise_archive_link = get_post_type_archive_link( get_query_var('post_type') );

// Override theme's method of defining the meta page title
$meta_title_base_addition = $expertise_plural_name_attr; // Word or phrase to use to form base meta title
uamswp_fad_title_vars(); // Defines universal variables related to the setting the meta title
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

get_header();

add_filter( 'facetwp_template_use_archive', '__return_true' );

?>

<div class="content-sidebar-wrap">
	<main id="genesis-content">
		<section class="archive-description">
			<header class="entry-header">
				<h1 class="entry-title" itemprop="headline"><?php echo $expertise_archive_headline; ?></h1>
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