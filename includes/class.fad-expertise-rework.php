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

// Modify SEOPress's standard canonical URL settings
add_filter('seopress_titles_canonical','uamswp_fad_ontology_canonical');
function uamswp_fad_ontology_canonical($html) {
	// Bring in variables from outside of the function
	global $page_id; // Defined on the template
	global $current_fpage; // Defined on the template
	global $wp_the_query; // WordPress-specific global variable
	
	// Make sure permalinks for fake subpages are canonical
	if (
		!empty($current_fpage) // Is a fake subpage
		&&
		is_singular() // Is an existing single post of any post type
		&&
		$page_id = $wp_the_query->get_queried_object_id()
	) {
		$html = '<link rel="canonical" href="' . trailingslashit(get_permalink($page_id)) . user_trailingslashit($current_fpage) . '" />';
	}
	return $html;
}

// Do not forget to flush your permalinks.
// To flush, go to Settings > Permalinks > Save