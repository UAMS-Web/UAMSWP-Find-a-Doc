<?php
/*
 * Template Name: Areas of Expertise Archive
 */

// Get system settings for Areas of Expertise Labels
// $expertise_single_name = get_field('expertise_single_name', 'option') ?: 'Area of Expertise';
// $expertise_single_name_attr = uamswp_attr_conversion($expertise_single_name);
$expertise_plural_name = get_field('expertise_plural_name', 'option') ?: 'Areas of Expertise';
$expertise_plural_name_attr = uamswp_attr_conversion($expertise_plural_name);

// Get system settings for Areas of Expertise Archive Page
$expertise_archive_headline = get_field('expertise_archive_headline', 'option') ?: 'Areas of Expertise';
// $expertise_archive_headline_attr = uamswp_attr_conversion($expertise_archive_headline);
$expertise_archive_intro_text = get_field('expertise_archive_intro_text', 'option');
// $expertise_archive_link = get_post_type_archive_link( get_query_var('post_type') );

// Override theme's method of defining the meta page title
function uamswp_fad_title($html) {
	global $expertise_plural_name_attr;

	//you can add here all your conditions as if is_page(), is_category() etc.. 
	$html = $expertise_plural_name_attr . ' | ' . get_bloginfo( "name" );
	return $html;
}
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
			<?php echo ($expertise_archive_intro_text ? '<div class="entry-content clearfix" itemprop="text">' . $expertise_archive_intro_text . '</div>' : '' ); ?>
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