<?php
/*
 * Template Name: Primary Navigation for Area of Expertise Subsection
 * 
 * Description: A template part that displays the primary navigation specific to 
 * an area of expertise subsection — a group of pages with its own primary 
 * navigation.
 * 
 * This template part replicates the primary navigation used in the UAMS 2020 
 * theme, placing a list of fixed link items at the beginning of the list followed 
 * by a hierarchical list of descendants with a certain meta value.
 * 
 * Required vars:
 * 	$page_id // int // ID of the area of expertise item
 * 	$ontology_type // bool // Ontology type of the area of expertise item (true is ontology type, false is content type)
 * 	$page_title // string // Title of the area of expertise item
 * 	$page_url // string // Permalink of the area of expertise item
 * 
 * Optional vars:
 * 	$site_nav_id // int
 * 	$providers // int[]
 * 	$locations // int[]
 * 	$expertises // int[]
 * 	$expertise_descendants // int[]
 * 	$clinical_resources // int[]
 * 	$provider_section_show // bool
 * 	$location_section_show // bool
 * 	$expertise_section_show // bool
 * 	$clinical_resource_section_show // bool
 * 	$expertise_descendant_section_show // bool
 * 	$expertise_content_nav_show // bool
 * 	$expertise_content_nav // string
 * 	$provider_plural_name // string // System setting for Providers plural item name
 * 	$provider_plural_name_attr // string // Attribute value friendly version of system setting for Providers plural item name
 * 	$location_plural_name // string // System setting for Locations plural item name
 * 	$location_plural_name_attr // string // Attribute value friendly version of system setting for Locations plural item name
 * 	$expertise_plural_name // string // System setting for Areas of Expertise plural item name
 * 	$expertise_plural_name_attr // string // Attribute value friendly version of system setting for Areas of Expertise plural item name
 * 	$expertise_descendant_plural_name // string // System setting for Areas of Expertise plural descendant item name
 * 	$expertise_descendant_plural_name_attr // string // Attribute value friendly version of system setting for Areas of Expertise plural descendant item name
 * 	$clinical_resource_plural_name // string // System setting for Clinical Resources plural item name
 * 	$clinical_resource_plural_name_attr // string // Attribute value friendly version of system setting for Clinical Resources plural item name
 */

// Check/define variables

	// Get the ontology subsection values
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/ontology-subsection.php' );

	// Related Providers Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/provider.php' );

	// Related Locations Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/location.php' );

	// Related Areas of Expertise Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/expertise.php' );

	// Related Clinical Resources Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/clinical-resource.php' );

	// Descendant Areas of Expertise Section Query

		$content_placement = 'subsection';
		include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/expertise-descendant.php' );

	// Get system settings for provider labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

	// Get system settings for area of expertise labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

	// Get system settings for descendant area of expertise item labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise-descendant.php' );

	// Get system settings for clinical resource labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

require_once( UAMS_FAD_PATH . '/templates/modules/class-wp-bootstrap-pagewalker.php' );

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
if ($expertise_descendant_section_show) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-children nav-item"><a title="' . $expertise_descendant_plural_name_attr . ' Within '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'specialties/" class="nav-link"><span itemprop="name">' . $expertise_descendant_plural_name . '</span></a></li>';
}
if ($expertise_section_show) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-related nav-item"><a title="' . $expertise_plural_name_attr . ' Related to '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'related/" class="nav-link"><span itemprop="name">Related ' . $expertise_plural_name . '</span></a></li>';
}
if ($clinical_resource_section_show) {
	$pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $site_nav_id .'-resources nav-item"><a title="' . $clinical_resource_plural_name_attr . ' for '. get_the_title( $site_nav_id ) .'" href="'. get_permalink( $site_nav_id ) .'resources/" class="nav-link"><span itemprop="name">' . $clinical_resource_plural_name . '</span></a></li>';
}
if ($expertise_descendants) {
	$args = array(
		'child_of' => $site_nav_id,
		'title_li' => '',
		'echo' => false,
		'walker' => new WP_Bootstrap_Pagewalker(), // !important! create Bootstrap style navigation
		// 'exclude' => implode(',',$excluded_pages),
	);
}
if ($expertise_content_nav_show) {
	$pagenav .= $expertise_content_nav;
}
// $pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item nav-item"><span itemprop="name">'. explode($expertise_descendants, ',') .'</span></li>';

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