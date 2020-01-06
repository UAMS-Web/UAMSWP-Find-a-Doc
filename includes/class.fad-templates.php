<?php
// force use of templates from plugin folder
function uamswp_force_template( $template )
{	
    if( is_post_type_archive( 'providers' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__)))  .'/templates/archive-physicians.php';
	}
	
	if( is_singular( 'providers' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/single-physicians.php';
    }
    
    if( is_post_type_archive( 'locations' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/archive-locations.php';
	}
	
	if( is_singular( 'locations' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/single-locations.php';
    }
    
    if( is_post_type_archive( 'expertise' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/archive-expertise.php';
	}
	
	if( is_singular( 'expertise' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/single-expertise.php';
    }
    
    if( is_tax( 'condition' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/taxonomy-condition.php';
    }
    
    if( is_tax( 'treatment_procedure' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/taxonomy-treatment_procedure.php';
    }
	
    return $template;
}
add_filter( 'template_include', 'uamswp_force_template' );

function uamswp_taxonomy_archive_page_template ($templates) {
    $templates['archive-taxonomy-conditions.php'] = 'Conditions Archive';
    $templates['archive-taxonomy-treatments.php'] = 'Treatments Archive';
    return $templates;
    }
add_filter ('theme_page_templates', 'uamswp_taxonomy_archive_page_template');

function uamswp_redirect_page_template ($template) {
    $post = get_post();
    $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ('archive-taxonomy-conditions.php' == basename ($page_template ))
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/archive-taxonomy-conditions.php';
    if ('archive-taxonomy-treatments.php' == basename ($page_template ))
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/archive-taxonomy-treatments.php';
    return $template;
    }
add_filter ('page_template', 'uamswp_redirect_page_template');

// functions.php
// prioritetize pagination over displaying custom post type content
add_action('init', function() {
    add_rewrite_rule('(.?.+?)/page/?([0-9]{1,})/?$', 'index.php?pagename=$matches[1]&paged=$matches[2]', 'top');
  });

add_filter( 'body_class', 'uamswp_fad_body_class' );
function uamswp_fad_body_class( $classes ) {
 
	if ( is_tax( 'condition' ) || is_tax( 'treatment_procedure' ) ) {
		$classes[] = 'page-template-default';
	}
 
	return $classes;
 
}
// Custom redirect to archive page for providers & locations
add_action( 'template_redirect', function() {
	global $wp_query;
    if ( ('providers' == $wp_query->get('post_type') || 'locations' == $wp_query->get('post_type')) && is_404( ) ) {
        $redirectLink = get_post_type_archive_link( $wp_query->get('post_type') );
        wp_redirect( $redirectLink, 301 );
        exit;
    }
});