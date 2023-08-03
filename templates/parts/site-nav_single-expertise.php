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

	if (
		!isset($site_nav_id) || empty($site_nav_id)
		||
		!isset($providers) || empty($providers)
		||
		!isset($locations) || empty($locations)
		||
		!isset($expertises) || empty($expertises)
		||
		!isset($expertise_descendants) || empty($expertise_descendants)
		||
		!isset($clinical_resources) || empty($clinical_resources)
	) {

		$ontology_site_values_vars = isset($ontology_site_values_vars) ? $ontology_site_values_vars : uamswp_fad_ontology_site_values(
			$page_id, // int // ID of the post
			$ontology_type, // bool (optional) // Ontology type of the post (true is ontology type, false is content type)
			$page_title, // string (optional) // Title of the post
			$page_url // string (optional) // Permalink of the post
		);
			$site_nav_id = $ontology_site_values_vars['site_nav_id']; // int
			$providers = $ontology_site_values_vars['providers']; // int[]
			$locations = $ontology_site_values_vars['locations']; // int[]
			$expertises = $ontology_site_values_vars['expertises']; // int[]
			$expertise_descendants = $ontology_site_values_vars['expertise_descendants'];
			$clinical_resources = $ontology_site_values_vars['clinical_resources']; // int[]

	}

	if ( !isset($provider_section_show) || empty($provider_section_show) ) {

		$provider_query_vars = isset($provider_query_vars) ? $provider_query_vars : uamswp_fad_provider_query(
			$page_id, // int
			$providers // int[]
		);
			$provider_section_show = $provider_query_vars['provider_section_show']; // bool

	}

	if ( !isset($location_section_show) || empty($location_section_show) ) {

		$location_query_vars = isset($location_query_vars) ? $location_query_vars : uamswp_fad_location_query(
			$page_id, // int
			$locations // int[]
		);
			$location_section_show = $location_query_vars['location_section_show']; // bool

	}

	if ( !isset($expertise_section_show) || empty($expertise_section_show) ) {

		$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query(
			$page_id, // int
			$expertises // int[]
		);
			$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool

	}

	if ( !isset($clinical_resource_section_show) || empty($clinical_resource_section_show) ) {

		$clinical_resource_query_vars = isset($clinical_resource_query_vars) ? $clinical_resource_query_vars : uamswp_fad_clinical_resource_query(
			$page_id, // int
			$clinical_resources // int[]
		);
			$clinical_resource_section_show = $clinical_resource_query_vars['clinical_resource_section_show']; // bool

	}

	if (
		!isset($expertise_descendant_section_show) || empty($expertise_descendant_section_show)
		||
		!isset($expertise_content_nav_show) || empty($expertise_content_nav_show)
		||
		!isset($expertise_content_nav) || empty($expertise_content_nav)
	) {

		$expertise_descendant_query_vars = isset($expertise_descendant_query_vars) ? $expertise_descendant_query_vars : uamswp_fad_expertise_descendant_query(
			$page_id, // int
			$expertise_descendants, // int[]
			'subsection', // string (optional) // Expected values: 'subsection' or 'profile'
			$site_nav_id // int (optional)
		);
			$expertise_descendant_section_show = $expertise_descendant_query_vars['expertise_descendant_section_show']; // bool
			$expertise_content_nav_show = $expertise_descendant_query_vars['expertise_content_nav_show']; // bool
			$expertise_content_nav = $expertise_descendant_query_vars['expertise_content_nav']; // string

	}

	if (
		!isset($provider_plural_name) || empty($provider_plural_name)
		||
		!isset($provider_plural_name_attr) || empty($provider_plural_name_attr)
	) {

		$labels_provider_vars = isset($labels_provider_vars) ? $labels_provider_vars : uamswp_fad_labels_provider();
			$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string
			$provider_plural_name_attr = $labels_provider_vars['provider_plural_name_attr']; // string

	}

	if (
		!isset($location_plural_name) || empty($location_plural_name)
		||
		!isset($location_plural_name_attr) || empty($location_plural_name_attr)
	) {

		$labels_location_vars = isset($labels_location_vars) ? $labels_location_vars : uamswp_fad_labels_location();
			$location_plural_name = $labels_location_vars['location_plural_name']; // string
			$location_plural_name_attr = $labels_location_vars['location_plural_name_attr']; // string

	}

	if (
		!isset($expertise_plural_name) || empty($expertise_plural_name)
		||
		!isset($expertise_plural_name_attr) || empty($expertise_plural_name_attr)
	) {

		$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
			$expertise_plural_name = $labels_expertise_vars['expertise_plural_name']; // string
			$expertise_plural_name_attr = $labels_expertise_vars['expertise_plural_name_attr']; // string

	}

	if (
		!isset($expertise_descendant_plural_name) || empty($expertise_descendant_plural_name)
		||
		!isset($expertise_descendant_plural_name_attr) || empty($expertise_descendant_plural_name_attr)
	) {

		$labels_expertise_descendant_vars = isset($labels_expertise_descendant_vars) ? $labels_expertise_descendant_vars : uamswp_fad_labels_expertise_descendant();
			$expertise_descendant_plural_name = $labels_expertise_descendant_vars['expertise_descendant_plural_name']; // string
			$expertise_descendant_plural_name_attr = $labels_expertise_descendant_vars['expertise_descendant_plural_name_attr']; // string

	}


	if (
		!isset($clinical_resource_plural_name) || empty($clinical_resource_plural_name)
		||
		!isset($clinical_resource_plural_name_attr) || empty($clinical_resource_plural_name_attr)
	) {

		$labels_clinical_resource_vars = isset($labels_clinical_resource_vars) ? $labels_clinical_resource_vars : uamswp_fad_labels_clinical_resource();
			$clinical_resource_plural_name = $labels_clinical_resource_vars['clinical_resource_plural_name']; // string
			$clinical_resource_plural_name_attr = $labels_clinical_resource_vars['clinical_resource_plural_name_attr']; // string

	}


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