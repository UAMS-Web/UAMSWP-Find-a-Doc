<?php 
/* 
 * 	Creates fake subpages for a Area of Expertise custom post type
 * 
 * 	Based on: https://web.archive.org/web/20120724003555/https://www.placementedge.com/blog/create-post-sub-pages/
 * 	Based on: https://web.archive.org/web/20120304064323/https://betterwp.net/98-wordpress-create-fake-pages/
 * 
*/

// Adding rewrite rules for the fake subpages under Area of Expertise pages
function fsp_insertrules($rules) 
{
	// Define an array of the permalinks and titles for the fake subpages.
	// Change these to your required subpages.
	// Key = subpage permalink (slug).
	// Value = subpage title.
	$my_fake_pages = array(
		'providers' => 'Providers',
		'locations' => 'Locations',
		'specialties' => 'Specialties',
		'resources' => 'Clinical Resources',
		'related' => 'Related Areas of Expertise'
	);
	// Loop through each of the fake subpages
	foreach ($my_fake_pages as $slug => $title) {
		// Add a rewrite rule that transforms a URL structure to a set of query vars
		add_rewrite_rule('expertise/([^/]+)/' . $slug . '/?$', 'index.php?expertise=$matches[1]&fpage=' . $slug, 'top');
	}
}
add_action( 'init', 'fsp_insertrules' );

// Tell WordPress to accept our custom query variable
function fsp_insertqv($vars)
{
	$vars[] = 'fpage';
	return $vars;
}
add_filter('query_vars', 'fsp_insertqv');

// Replace WordPress's default canonical handling function
remove_filter('wp_head', 'rel_canonical');
add_filter('wp_head', 'fsp_rel_canonical');
function fsp_rel_canonical()
{
	global $current_fp, $wp_the_query;

	if (!is_singular())
		return;

	if (!$id = $wp_the_query->get_queried_object_id())
		return;

	$link = trailingslashit(get_permalink($id));

	// Make sure permalinks for fake subpages are canonical
	if (!empty($current_fp))
		$link .= user_trailingslashit($current_fp);

	echo '<link rel="canonical" href="'.$link.'" />';
}

// Do not forget to flush your permalinks.
// To flush, go to Settings > Permalinks > Save