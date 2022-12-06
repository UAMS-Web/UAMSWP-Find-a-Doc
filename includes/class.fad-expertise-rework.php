<?php 
// Adding fake pages' rewrite rules
function fsp_insertrules($rules) 
{
    // Fake pages' permalinks and titles. Change these to your required sub pages.
    $my_fake_pages = array(
        'providers' => 'Providers',
        'locations' => 'Locations',
        'resources' => 'Clinical Resources',
        'related' => 'Related Areas of Expertise'
    );
 
    foreach ($my_fake_pages as $slug => $title) {
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

// Remove WordPress's default canonical handling function

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
 
    // Make sure fake pages' permalinks are canonical
    if (!empty($current_fp))
        $link .= user_trailingslashit($current_fp);
 
    echo '<link rel="canonical" href="'.$link.'" />';
}