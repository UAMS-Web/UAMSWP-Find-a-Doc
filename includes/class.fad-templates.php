<?php
// force use of templates from plugin folder
function uamswp_force_template( $template )
{	
    if( is_post_type_archive( 'provider' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__)))  .'/templates/archive-physicians.php';
	}
	
	if( is_singular( 'provider' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/single-physicians.php';
    }
    
    if( is_post_type_archive( 'location' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/archive-locations.php';
	}
	
	if( is_singular( 'location' ) ) {
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
    
    if( is_tax( 'treatment' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/taxonomy-treatment_procedure.php';
    }
	
    return $template;
}
add_filter( 'template_include', 'uamswp_force_template' );

function uamswp_taxonomy_archive_page_template ($templates) {
    $templates['archive-taxonomy-conditions.php'] = 'Conditions Archive';
    $templates['archive-taxonomy-treatments.php'] = 'Treatments Archive';
    $templates['provider-image.php'] = 'Provider Image';
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
    if ('provider-image.php' == basename ($page_template ))
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/provider-image.php';
    return $template;
    }
add_filter ('page_template', 'uamswp_redirect_page_template');

// functions.php
// prioritetize pagination over displaying custom post type content
add_action('init', function() {
    add_rewrite_rule('(.?.+?)/page/?([0-9]{1,})/?$', 'index.php?pagename=$matches[1]&paged=$matches[2]', 'top');
});

function provider_image_rewrite_rule() {
    add_rewrite_rule( 'provider/([^/]+)/image', 'index.php?provid=$matches[1]&pagename=image&image=yes', 'top' );
}
add_action( 'init', 'provider_image_rewrite_rule' );

function provider_image_query_var( $vars ) {
    $vars[] = 'image';
    $vars[] = 'provid';
    return $vars;
}
add_filter( 'query_vars', 'provider_image_query_var' );

function provider_image_rewrite_templates() {
    if ( get_query_var( 'image' )) {
        add_filter( 'template_include', function() {
            return WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/provider-image.php';
        });
    }
}
add_action( 'template_redirect', 'provider_image_rewrite_templates' );

add_filter( 'body_class', 'uamswp_fad_body_class' );
function uamswp_fad_body_class( $classes ) {
 
	if ( is_tax( 'condition' ) || is_tax( 'treatment' ) ) {
		$classes[] = 'page-template-default';
	}
 
	return $classes;
 
}
// Custom redirect to archive page for providers & locations
// add_action( 'template_redirect', function() {
// 	global $wp_query;
//     if ( ('provider' == $wp_query->get('post_type') || 'location' == $wp_query->get('post_type')) && is_404( ) ) {
//         $redirectLink = get_post_type_archive_link( $wp_query->get('post_type') );
//         wp_redirect( $redirectLink, 301 );
//         exit;
//     }
// });