<?php
// force use of templates from plugin folder
function uamswp_force_template( $template )
{	
    if( is_post_type_archive( 'physicians' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__)))  .'/templates/archive-physicians.php';
	}
	
	if( is_singular( 'physicians' ) ) {
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
	
	if( is_tax( 'medical_procedures' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/taxonomy-medical_procedures.php';
    }
    
    if( is_tax( 'medical_terms' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/taxonomy-medical_terms.php';
    }
    
    if( is_tax( 'specialty' ) ) {
        $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/taxonomy-specialty.php';
	}
	
    return $template;
}
add_filter( 'template_include', 'uamswp_force_template' );