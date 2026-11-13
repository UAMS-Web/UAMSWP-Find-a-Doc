<?php

declare(strict_types=1);

/**
 * The path of a template file in this plugin's templates folder.
 *
 * @param  string  $file  The file name inside the templates folder.
 */
function uamswp_fad_plugin_template(string $file): string
{
    // The plugin folder is the checkout's own name wherever it is installed, and a checkout that repeats its parent's name (as a CI checkout does) cannot tell this level from the next one up.
    // @pest-mutate-ignore: IncrementInteger
    return WP_PLUGIN_DIR.'/'.basename(dirname(__FILE__, 2)).'/templates/'.$file;
}

// force use of templates from plugin folder
/**
 * @param  mixed  $template
 * @return mixed
 */
function uamswp_force_template($template)
{
    if (is_post_type_archive('provider')) {
        $template = uamswp_fad_plugin_template('archive-physicians.php');
    }

    if (is_singular('provider')) {
        $template = uamswp_fad_plugin_template('single-physicians.php');
    }

    if (is_post_type_archive('location')) {
        $template = uamswp_fad_plugin_template('archive-locations.php');
    }

    if (is_singular('location')) {
        $template = uamswp_fad_plugin_template('single-locations.php');
    }

    if (is_post_type_archive('expertise')) {
        $template = uamswp_fad_plugin_template('archive-expertise.php');
    }

    if (is_singular('expertise')) {
        $template = uamswp_fad_plugin_template('single-expertise.php');
    }

    if (is_singular('condition')) {
        $template = uamswp_fad_plugin_template('single-condition.php');
    }

    if (is_post_type_archive('condition')) {
        $template = uamswp_fad_plugin_template('archive-condition.php');
    }

    if (is_singular('treatment')) {
        $template = uamswp_fad_plugin_template('single-treatment.php');
    }

    if (is_post_type_archive('treatment')) {
        $template = uamswp_fad_plugin_template('archive-treatments.php');
    }

    if (is_singular('clinical-resource')) {
        $template = uamswp_fad_plugin_template('single-clinical-resource.php');
    }

    if (is_post_type_archive('clinical-resource')) {
        return uamswp_fad_plugin_template('archive-clinical-resource.php');
    }

    return $template;
}

add_filter('template_include', 'uamswp_force_template');

/**
 * @param  array<string, string>  $templates
 * @return array<string, string>
 */
function uamswp_taxonomy_archive_page_template($templates)
{
    $templates['archive-taxonomy-conditions.php'] = 'Conditions Archive';
    $templates['archive-taxonomy-treatments.php'] = 'Treatments Archive';
    $templates['provider-image.php'] = 'Provider Image';

    // $templates['doximity-list.php'] = 'Doximity List';
    // $templates['mychart-list-provider.php'] = 'MyChart Provider List';
    return $templates;
}

add_filter('theme_page_templates', 'uamswp_taxonomy_archive_page_template');

/**
 * @param  mixed  $template
 * @return mixed
 */
function uamswp_redirect_page_template($template)
{
    $post = get_post();
    $page_template = get_post_meta($post->ID ?? 0, '_wp_page_template', true);
    if (basename(uamswp_fad_str($page_template)) === 'archive-taxonomy-conditions.php') {
        $template = uamswp_fad_plugin_template('archive-taxonomy-conditions.php');
    }

    if (basename(uamswp_fad_str($page_template)) === 'archive-taxonomy-treatments.php') {
        $template = uamswp_fad_plugin_template('archive-taxonomy-treatments.php');
    }

    if (basename(uamswp_fad_str($page_template)) === 'provider-image.php') {
        return uamswp_fad_plugin_template('provider-image.php');
    }

    // if ('doximity-list.php' == basename ($page_template ))
    //     $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/doximity-list.php';
    // if ('mychart-list-provider.php' == basename ($page_template ))
    //     $template = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) .'/templates/mychart-list-provider.php';
    return $template;
}

add_filter('page_template', 'uamswp_redirect_page_template');

// functions.php
// prioritetize pagination over displaying custom post type content
add_action('init', function (): void {
    add_rewrite_rule('(.?.+?)/page/?([0-9]{1,})/?$', 'index.php?pagename=$matches[1]&paged=$matches[2]', 'top');
});

function provider_image_rewrite_rule(): void
{
    add_rewrite_rule('provider/([^/]+)/image', 'index.php?provid=$matches[1]&pagename=image&image=yes', 'top');
}

add_action('init', 'provider_image_rewrite_rule');

/**
 * @param  array<int, string>  $vars
 * @return array<int, string>
 */
function provider_image_query_var($vars)
{
    $vars[] = 'image';
    $vars[] = 'provid';

    return $vars;
}

add_filter('query_vars', 'provider_image_query_var');

function provider_image_rewrite_templates(): void
{
    if (get_query_var('image')) {
        add_filter('template_include', fn (): string => uamswp_fad_plugin_template('provider-image.php'));
    }
}

add_action('template_redirect', 'provider_image_rewrite_templates');

add_filter('body_class', 'uamswp_fad_body_class');
/**
 * @param  array<int, string>  $classes
 * @return array<int, string>
 */
function uamswp_fad_body_class($classes)
{

    if (is_tax('condition') || is_tax('treatment')) {
        $classes[] = 'page-template-default';
    }

    return $classes;

}

// Custom redirect to archive page for providers & locations
add_action('template_redirect', function (): void {
    /** @var WP_Query $wp_query */
    global $wp_query;
    $post_type = $wp_query->get('post_type');
    if (in_array($post_type, ['provider', 'location'], true) && is_404()) {
        $redirectLink = get_post_type_archive_link(uamswp_fad_str($post_type));
        wp_redirect((string) $redirectLink, 301);
        exit;
    }
});
