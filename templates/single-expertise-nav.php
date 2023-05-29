<?php
/*
 * Expertise Nav
 */

require_once( 'modules/class-wp-bootstrap-pagewalker.php' );

$args = array(
	'theme_location' => 'primary',
	'container' => '',
	'menu' => 'expertise-navigation', // !important! you need to give the name/slug of your menu
	// 'menu_class' => $class,
	'echo' => 0,
);

$nav = wp_nav_menu( $args );

$pagenav = '';

$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item menu-item-'. $site_nav_id .' nav-item active"><a title="'. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'" class="nav-link"><span itemprop="name">Overview</span></a></li>';
if ($show_providers_section) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-providers nav-item"><a title="Providers for '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'providers/" class="nav-link"><span itemprop="name">Providers</span></a></li>';
}
if ($show_locations_section) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-locations nav-item"><a title="Locations for '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'locations/" class="nav-link"><span itemprop="name">Locations</span></a></li>';
}
if ($show_child_aoe_section) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-children nav-item"><a title="Areas within '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'specialties/" class="nav-link"><span itemprop="name">Specialties</span></a></li>';
}
if ($show_related_aoe_section) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-related nav-item"><a title="Related Expertise for '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'related/" class="nav-link"><span itemprop="name">Related</span></a></li>';
}
if ($show_related_resource_section) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-resources nav-item"><a title="Clinical Resources for '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'resources/" class="nav-link"><span itemprop="name">Clinical Resources</span></a></li>';
}
if ($child_pages) {
	$args = array(
		'child_of' => $site_nav_id,
		'title_li' => '',
		'echo' => false,
		'walker' => new WP_Bootstrap_Pagewalker(), // !important! create Bootstrap style navigation
		// 'exclude' => implode(',',$excluded_pages),
	);
}
if ($show_child_content_nav) {
	$pagenav .= $childnav;
}
// $pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item nav-item"><span itemprop="name">'. explode($child_pages, ',') .'</span></li>';

// Add the appropriate navbar coding
$wrapper_open = '<nav class="site-nav navbar navbar-expand-sm">';
//$wrapper_open .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#genesis-nav-primary" aria-controls="genesis-nav-primary" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">Test</span></button>';
$wrapper_open .= '<div class="collapse navbar-collapse inner-container" id="genesis-nav-primary">';
$wrapper_open .= '<ul id="menu-dropdowns" class="nav navbar-nav align-self-end mr-auto">';

$wrapper_close = '</ul>'; // ul
$wrapper_close .= '</div>'; // wrap
$wrapper_close .= '</nav>'; // navbar

// Wrap the list items in an unordered list and navbar
$pagenav = $wrapper_open . $pagenav . $wrapper_close;

$pagenav_markup_open = genesis_markup( array(
	'html5' => '<nav %s>',
	'xhtml' => '<div id="pagenav">',
	'context' => 'genesis-nav-primary',
	'echo' => false,
) );

echo $pagenav;