<?php
/*
 *
 *  Expertise Nav
 *
 */

$args = array(
    'theme_location' => 'primary',
    'container'      => '',
    'menu'           => 'expertise-navigation', // !important! you need to give the name/slug of your menu
    // 'menu_class'     => $class,
    'echo'           => 0,
);

$nav = wp_nav_menu( $args );

$pagenav = '';

$child_pages = get_pages( array('child_of' => $page_id, 'post_type' => 'expertise' ) );

// $pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item menu-item-'. $page_id .' nav-item active"><a title="'. get_the_title( $page_id ) .'" href="'. get_permalink( $page_id ) .'" class="nav-link"><span itemprop="name">'. get_the_title( $page_id ) .'</span></a></li>';
if ($show_providers_section) {
    $pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $page_id .'-providers nav-item"><a title="Providers for '. get_the_title( $page_id ) .'" href="'. get_permalink( $page_id ) .'providers/" class="nav-link"><span itemprop="name">Providers</span></a></li>';
}
if ($show_locations_section) {
    $pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $page_id .'-locations nav-item"><a title="Locations for '. get_the_title( $page_id ) .'" href="'. get_permalink( $page_id ) .'locations/" class="nav-link"><span itemprop="name">Locations</span></a></li>';
}
if ($show_related_aoe_section) {
    $pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $page_id .'-related nav-item"><a title="Related Expertise for '. get_the_title( $page_id ) .'" href="'. get_permalink( $page_id ) .'related/" class="nav-link"><span itemprop="name">Related</span></a></li>';
}
if ($show_related_resource_section) {
    $pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $page_id .'-resources nav-item"><a title="Clinical Resources for '. get_the_title( $page_id ) .'" href="'. get_permalink( $page_id ) .'resources/" class="nav-link"><span itemprop="name">Clinical Resources</span></a></li>';
}
if ($child_pages) {
    $childnav = '';
    $children = false;
    foreach ( $child_pages as $child_page ) {
        $hide = get_post_meta($child_page->ID, 'page_hide_from_menu');
        $type = get_field('expertise_type', $child_page->ID);
        if ( isset($hide[0]) && '1' == $hide[0] ) {        
            //* Do nothing if there is nothing to show
        } elseif( !isset($type) || '1' == $type ) {
            $children = true;
        } else {
            $childnav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item menu-item-'. $child_page->ID .' nav-item active"><a title="'. $child_page->post_title .'" href="'. get_permalink( $child_page->ID ) .'" class="nav-link"><span itemprop="name">'. $child_page->post_title .'</span></a></li>';            
        }
    }
    if ($children) {
        $pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $page_id .'-children menu-item-has-children nav-item dropdown"><a href="'. get_permalink( $page_id ) .'#sub-expertise-title" title="Areas Within '. get_the_title( $page_id ) .'" class="nav-link"><span itemprop="name">Specialties</span></a></li>';
    }
    if (!empty($childnav)) {
        $pagenav .= $childnav;
    }
}
// $pagenav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item nav-item"><span itemprop="name">'. explode($child_pages, ',') .'</span></li>';

// Add the appropriate navbar coding
$wrapper_open  = '<nav class="site-nav navbar navbar-expand-sm">';
//$wrapper_open .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#genesis-nav-primary" aria-controls="genesis-nav-primary" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">Test</span></button>';
$wrapper_open .= '<div class="collapse navbar-collapse inner-container" id="genesis-nav-primary">';
$wrapper_open .= '<ul id="menu-dropdowns" class="nav navbar-nav align-self-end mr-auto">';

$wrapper_close  = '</ul>'; // ul
$wrapper_close .= '</div>'; // wrap
$wrapper_close .= '</nav>'; // navbar

// Wrap the list items in an unordered list and navbar
$pagenav = $wrapper_open . $pagenav . $wrapper_close;

$pagenav_markup_open = genesis_markup( array(
    'html5'   => '<nav %s>',
    'xhtml'   => '<div id="pagenav">',
    'context' => 'genesis-nav-primary',
    'echo'    => false,
) );

echo $pagenav;