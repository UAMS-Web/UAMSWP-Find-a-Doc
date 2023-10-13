<?php
/**
 * Template Name: MyChart Provider List
 */

// Remove the primary navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' );

// Remove header
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );

// Remove primary nav
remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

// Remove Footer Widgets
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );

// Remove Footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

// Force full-width-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

// Remove Breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_after_header', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_after_header', 'sp_breadcrumb_after_header' );

// Remove Skip Links from a template
remove_action ( 'genesis_before_header', 'genesis_skip_links', 5 );

// UAMS modifications
remove_action ( 'genesis_header', 'uamswp_site_image', 5 );
remove_action ( 'genesis_after_header', 'genesis_do_breadcrumbs' );
remove_action ( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action ( 'genesis_before_header', 'uams_toggle_search', 12);
remove_action ( 'genesis_before_header', 'uamswp_skip_links', 5 );

// Dequeue Skip Links Script
add_action( 'wp_enqueue_scripts','child_dequeue_skip_links' );
function child_dequeue_skip_links() {
	wp_dequeue_script( 'skip-links' );
}

// Remove GTM container
remove_action( 'wp_head', 'uamswp_gtm_1' );
remove_action( 'genesis_before', 'uamswp_gtm_2' );

add_filter ( 'wp_nav_menu', '__return_false' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'display_provider_image' );
function display_provider_image() {
	// Custom WP_Query args
	$args = array(
		'post_type' => 'provider',
		'post_status' => 'publish',
		'posts_per_page' => '-1', // Set for all
		'orderby' => 'title',
		'order' => 'ASC',
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) : ?>
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="no-break">Provider ID</th>
						<th class="no-break">Provider Name</th>
						<th class="no-break">Provider Profile URL</th>
					</tr>
				</thead>
				<tbody>
				<?php
				while( $query->have_posts() ) : $query->the_post();
					$post_id = get_the_ID();

					// Create the Name variables
					$pid = get_field('physician_pid',$post_id);
					$pid = ( $pid == 0 ) ? '' : $pid;
					$sort_name = get_the_title($post_id);
					$profile_url = user_trailingslashit(get_the_permalink($post_id));

					// Get slug
					$profile_slug = get_post_field( 'post_name', $post_id );

					$resident = get_field('physician_resident',$post_id);

					// Create the table
					if ( !$resident ) {

						// Start table row
						echo '<tr>';

						// PID
							echo '<td class="no-break">' . $pid . '</td>';

						// Provider Name
							echo '<td class="no-break">' . $sort_name . '</td>';

						// Provider Profile URL
							echo '<td class="no-break">' . $profile_url . '?utm_source=mychart&utm_medium=link&utm_campaign=clinical_service&utm_term=provider&utm_content=' . $profile_slug . '</td>';

						echo '</tr>';

						$l++;

					} // endif !$resident

				endwhile;
				?>
				</tbody>
			</table>
		</div>
	<?php
		else :
		echo 'No providers found';
	endif;

}

genesis();