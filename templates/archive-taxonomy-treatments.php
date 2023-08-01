<?php
/*
 * Template Name: Treatments Archive
 */

// Add page template class to body element's classes

	$template_type = 'default';
	add_filter( 'body_class', function( $classes ) use ( $template_type ) {

		// Add page template class to body class array
		$classes[] = 'page-template-' . $template_type;

		return $classes;

	} );

// Filter posts_where

	// Do nothing

// Filter terms_clauses

	add_filter( 'terms_clauses', 'uamswp_terms_clauses', 10, 3 );
	function uamswp_terms_clauses( $clauses, $taxonomies, $args ){
		// Search for terms with the first letter

		// Bring in variables from outside of the function
		global $wpdb; // WordPress-specific global variable

		if( !isset( $args['__first_letter'] ) ){
			return $clauses;
		}

		if ( is_numeric($args['__first_letter']) ) {
			$clauses['where'] .= ' AND ' . $wpdb->prepare( "t.name REGEXP %s", '^[0-9]' );
		} else {
			$clauses['where'] .= ' AND ' . $wpdb->prepare( "t.name LIKE %s", $wpdb->esc_like( $args['__first_letter'] ) . '%' );
		}
		return $clauses;

	}

// Get system settings for ontology item labels

	// Get system settings for Treatments Labels

		$labels_treatment_vars = isset($labels_treatment_vars) ? $labels_treatment_vars : uamswp_fad_labels_treatment();
			$treatment_single_name = $labels_treatment_vars['treatment_single_name']; // string
			$treatment_single_name_attr = $labels_treatment_vars['treatment_single_name_attr']; // string
			$treatment_plural_name = $labels_treatment_vars['treatment_plural_name']; // string
			$treatment_plural_name_attr = $labels_treatment_vars['treatment_plural_name_attr']; // string
			$placeholder_treatment_single_name = $labels_treatment_vars['placeholder_treatment_single_name']; // string
			$placeholder_treatment_plural_name = $labels_treatment_vars['placeholder_treatment_plural_name']; // string

	// Get system settings for Treatments Archive Page

		$archive_text_treatment_vars = isset($archive_text_treatment_vars) ? $archive_text_treatment_vars : uamswp_fad_archive_text_treatment();
			$treatment_archive_headline = $archive_text_treatment_vars['treatment_archive_headline']; // string
			$treatment_archive_headline_attr = $archive_text_treatment_vars['treatment_archive_headline_attr']; // string
			$treatment_archive_intro_text = $archive_text_treatment_vars['treatment_archive_intro_text']; // string
			$placeholder_treatment_archive_headline = $archive_text_treatment_vars['placeholder_treatment_archive_headline']; // string
			$placeholder_treatment_archive_intro_text = $archive_text_treatment_vars['placeholder_treatment_archive_intro_text']; // string
		// $treatment_archive_link = get_post_type_archive_link( get_query_var('post_type') );

// Get system settings for this archive page's text

	// Do nothing

// Get the page ID

	// $page_id = get_the_ID(); // int

// Get the page title

	$page_title = $treatment_archive_headline; // string
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

// Get system settings for this archive page's featured image

	// Do nothing

// Get the featured image

	// $featured_image = ''; // Image ID // int

// Override theme's method of defining the meta page title

	// Construct the meta title

		$meta_title_base_addition = $treatment_plural_name_attr; // Word or phrase to use to form base meta title
		$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(
			$page_title, // string
			$page_title_attr, // string (optional)
			$meta_title_base_addition // string (optional) // Word or phrase to use to form base meta title // Defaults to $page_title_attr
		);
			$meta_title = $meta_title_vars['meta_title']; // string

	// Modify SEOPress's standard meta title settings

		add_filter( 'seopress_titles_title', function( $html ) use ( $meta_title ) {

			$html = $meta_title;

			return $html;

		}, 15, 2 );

// Set the schema description and the meta description

	// // Get excerpt
	// 
	// 	$excerpt = get_the_excerpt();
	// 	$excerpt_user = true;
	// 
	// 	if ( empty( $excerpt ) ) {
	// 
	// 		$excerpt_user = false;
	// 
	// 	}
	// 
	// // Set schema description
	// 
	// 	$schema_description = $excerpt; // Used for Schema Data. Should ALWAYS have a value
	// 
	// // Override theme's method of defining the meta description
	// 
	// 	add_filter('seopress_titles_desc', function( $html ) use ( $excerpt ) {
	// 
	// 		$html = $excerpt;
	// 
	// 		return $html;
	// 
	// 	} );

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
	include( UAMS_FAD_PATH . '/templates/parts/meta-social.php' );

get_header();

?>
<div class="content-sidebar-wrap">
	<main id="genesis-content">
		<article class="entry">
			<header class="entry-header">
				<h1 class="entry-title" itemprop="headline"><?php echo $treatment_archive_headline; ?></h1>
			</header>
			<?php echo ($treatment_archive_intro_text ? '<div class="entry-content clearfix" itemprop="text">' . $treatment_archive_intro_text . '</div>' : '' ); ?>
		</article>
		<section class="uams-module conditions-treatments">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="module-title sr-only">List of <?php echo $treatment_plural_name; ?></h2>
							<?php
							// if show all is set
							$taxonomy = 'treatment';
							if( isset($_GET['showall']) ):

								$args = array( 'taxonomy' => $taxonomy, 'hide_empty' => 0 );

							else:
							// else show paged

								$paged = ( get_query_var('paged') ) ? get_query_var( 'paged' ) : 1;
								// number of tags to show per page
								$per_page = 40;
								$offset = ( $paged-1 ) * $per_page;
								$args = array( 'taxonomy' => $taxonomy, 'number' => $per_page, 'offset' => $offset, 'hide_empty' => 0 );
								if ( isset($_GET['alpha']) ) {
									$alpha = substr($_GET['alpha'], 0, 1);
									$args['__first_letter'] = $alpha;
								}

							endif;

							$tax_terms = new WP_Term_Query( $args );

							// Insert Alpha links
							?>
							<div class="list-legend">
								<div class="az-filter">
									<!-- All Filters:
										Disable any checkbox input that does not represent any Treatments. Add "disabled" to input.

										A-Z Filter:
										If we can legally do this, default to the "Featured" set of Treatments, either defined manually or by analytics.
										Start with "Featured" checkbox input checked. Add "checked" attribute to input.
										If user checks any other checkbox input, remove "checked" attribute from "Featured" checkbox input.

										If user checks "All"/"Any" checkbox input, remove "checked" attribute from checkbox input of other characters.
										After that, if user checks any other checkbox input, remove "checked" attribute from "Any"/"All" checkbox input.
									-->
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>" <?php echo ($_GET['alpha']) ? 'aria-selected="false"' : 'aria-selected="true"'; ?> aria-label="Remove any filter"><span aria-hidden="true">All</span></a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=0" <?php echo ('0' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with a number"><span aria-hidden="true">#</span></a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=a" <?php echo ('a' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter A">A</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=b" <?php echo ('b' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter B">B</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=c" <?php echo ('c' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter C">C</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=d" <?php echo ('d' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter D">D</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=e" <?php echo ('e' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter E">E</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=f" <?php echo ('f' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter F">F</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=g" <?php echo ('g' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter G">G</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=h" <?php echo ('h' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter H">H</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=i" <?php echo ('i' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter I">I</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=j" <?php echo ('j' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter J">J</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=k" <?php echo ('k' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter K">K</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=l" <?php echo ('l' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter L">L</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=m" <?php echo ('m' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter M">M</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=n" <?php echo ('n' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter N">N</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=o" <?php echo ('o' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter O">O</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=p" <?php echo ('p' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter P">P</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=q" <?php echo ('q' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter Q">Q</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=r" <?php echo ('r' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter R">R</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=s" <?php echo ('s' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter S">S</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=t" <?php echo ('t' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter T">T</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=u" <?php echo ('u' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter U">U</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=v" <?php echo ('v' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter V">V</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=w" <?php echo ('w' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter W">W</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=x" <?php echo ('x' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter X">X</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=y" <?php echo ('y' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter Y">Y</a>
									</div>
									<div class="custom-control">
										<a class="az-filter-label" href="<?php echo get_permalink(); ?>?alpha=z" <?php echo ('z' == $_GET['alpha']) ? 'aria-selected="true"' : 'aria-selected="false"'; ?> aria-label="Show only the <?php echo strtolower($treatment_plural_name_attr); ?> that begin with the letter Z">Z</a>
									</div>
								</div>
							</div>
							<div class="list-container list-container-rows">
							<?php

							if ( ! empty($tax_terms->terms) ) {

								echo '<ul class="list">';

								foreach ($tax_terms->terms as $tax_term) {
									echo '<li>' . '<a href="' . esc_attr(get_term_link($tax_term, $taxonomy)) . '" aria-label="' . sprintf( __( "Learn about %s" ), $tax_term->name ) . '" ' . ' class="btn btn-outline-primary">' . $tax_term->name.'</a></li>';
								}

								echo '</ul>';

							} else {

								echo '<p class="text-center content-width">No <?php echo strtolower($treatment_plural_name); ?> meet your criteria.</p>';

							}

							// pagination
							// if showall isn't set
							if( !isset($_GET['showall']) ):

								$args = array( 'taxonomy' => $taxonomy, 'hide_empty' => 0, 'count' => true );
								if ( isset($_GET['alpha']) ) {
									$alpha = substr($_GET['alpha'], 0, 1);
									$args['__first_letter'] = $alpha;
								}

								// $total_terms = $terms->count;
								$total_terms = wp_count_terms( $taxonomy, $args );
								$pages = ceil($total_terms/$per_page);

								if(empty($paged)) $paged = 1;
								$range = 1; // Number of items +/- current page
								$showitems = ($range * 2)+1;

								if($pages == '')
								{
									// $total_terms = wp_count_terms( $taxonomy );
									$pages = ceil($total_terms/$per_page);
									if(!$pages)
									{
										$pages = 1;
									}
								}

								// if there's more than one page
								if( 1 != $pages ): ?>
									<div class="list-pagination">
									<nav aria-label="Condtion list pagination">
										<ul class="pagination">
										<?php
										$alpha_url = isset($alpha) ? '?alpha='. $alpha : '';
										if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="First" href="'.get_permalink().'page/1/'. $alpha_url .'"><span aria-hidden="true">&laquo;</span></a></li>';
										if($paged > 1 && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="Previous" href="'.get_permalink().'page/'.($paged - 1).'/'. $alpha_url .'"><span aria-hidden="true">&lsaquo;</span></a></li>';

										for ($pagecount=1; $pagecount <= $pages; $pagecount++):
											if (1 != $pages &&( !($pagecount >= $paged+$range+1 || $pagecount <= $paged-$range-1) || $pages <= $showitems )) {
												echo ($paged == $pagecount)? '<li class="page-item current"><a class="page-link">'.$pagecount.'</a></li>':'<li class="page-item"><a class="page-link" href="'.get_permalink().'page/'.$pagecount.'/'. $alpha_url .'" class="inactive" >'.$pagecount.'</a></li>';
											}
										endfor;

										if ($paged < $pages && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="Next" href="'.get_permalink().'page/'.($paged+1).'/'. $alpha_url .'"><span aria-hidden="true">&rsaquo;</span></a></li>';
										if ($paged < $pages-1 && $paged+$range-1 < $pages && $showitems < $pages) echo '<li class="page-item"><a class="page-link" aria-label="Last" href="'.get_permalink().'page/'.$pages.'/'. $alpha_url .'"><span aria-hidden="true">&raquo;</span></a></li>';
										?>
										</ul>
									</nav>
								</div>
									<?php
									// link to show all
									echo '<div class="show-all"><p><a href="'.get_permalink().'?showall=true">Show all items</a></p></div>';
								endif;

							else:
							// showall is set, show link to get back to paged mode

							echo '<div class="show-paged"><p><a href="'.get_permalink().'">Show paged</a></p></div>';

							endif;
							?>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
</div>
<?php get_footer(); ?>
