<?php
/*
 * Template Name: Primary Navigation for Area of Expertise Subsection
 * 
 * Required vars:
 * 	$provider_plural_name // System setting for Providers plural item name
 * 	$provider_plural_name_attr // Attribute value friendly version of system setting for Providers plural item name
 * 	$location_plural_name // System setting for Locations plural item name
 * 	$location_plural_name_attr // Attribute value friendly version of system setting for Locations plural item name
 * 	$expertise_plural_name // System setting for Areas of Expertise plural item name
 * 	$expertise_plural_name_attr // Attribute value friendly version of system setting for Areas of Expertise plural item name
 * 	$expertise_descendant_plural_name; // System setting for Areas of Expertise plural descendant item name
 * 	$expertise_descendant_plural_name_attr; // Attribute value friendly version of system setting for Areas of Expertise plural descendant item name
 * 	$clinical_resource_plural_name // System setting for Clinical Resources plural item name
 * 	$clinical_resource_plural_name_attr // Attribute value friendly version of system setting for Clinical Resources plural item name
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
if ($provider_section_show) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-providers nav-item"><a title="' . $provider_plural_name_attr . ' for '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'providers/" class="nav-link"><span itemprop="name">' . $provider_plural_name . '</span></a></li>';
}
if ($location_section_show) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-locations nav-item"><a title="' . $location_plural_name_attr . ' for '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'locations/" class="nav-link"><span itemprop="name">' . $location_plural_name . '</span></a></li>';
}
if ($show_child_aoe_section) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-children nav-item"><a title="' . $expertise_descendant_plural_name_attr . ' Within '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'specialties/" class="nav-link"><span itemprop="name">' . $expertise_descendant_plural_name . '</span></a></li>';
}
if ($show_related_aoe_section) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-related nav-item"><a title="' . $expertise_plural_name_attr . ' Related to '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'related/" class="nav-link"><span itemprop="name">Related ' . $expertise_plural_name . '</span></a></li>';
}
if ($clinical_resource_show_section) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-resources nav-item"><a title="' . $clinical_resource_plural_name_attr . ' for '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'resources/" class="nav-link"><span itemprop="name">' . $clinical_resource_plural_name . '</span></a></li>';
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