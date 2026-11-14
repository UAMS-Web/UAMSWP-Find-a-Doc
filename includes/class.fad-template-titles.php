<?php

/**
 * The page title filters of the page templates.
 *
 * Each template names its own function and registers it on `seopress_titles_title` (a few keep the
 * registration as a comment). The functions live here, and not in the templates, so a template can be run
 * more than once in a process.
 */

declare(strict_types=1);

/**
 * The page title for the archive-clinical-resource template.
 *
 * @param  string  $html  The title the theme built; replaced.
 */
function uamswp_fad_title_archive_clinical_resource($html): string
{
    // you can add here all your conditions as if is_page(), is_category() etc..
    $html = 'Clinical Resources | '.get_bloginfo('name');

    return $html;
}

/**
 * The page title for the archive-condition template.
 *
 * @param  string  $html  The title the theme built; replaced.
 */
function uamswp_fad_title_archive_condition($html): string
{
    /** @var string $condition_title */
    global $condition_title;
    // you can add here all your conditions as if is_page(), is_category() etc..
    $html = $condition_title.' | '.get_bloginfo('name');

    return $html;
}

/**
 * The page title for the archive-expertise template.
 *
 * @param  string  $html  The title the theme built; replaced.
 */
function uamswp_fad_title_archive_expertise($html): string
{
    // you can add here all your conditions as if is_page(), is_category() etc..
    $html = 'Areas of Expertise | '.get_bloginfo('name');

    return $html;
}

/**
 * The page title for the archive-locations template.
 *
 * @param  string  $html  The title the theme built; replaced.
 */
function uamswp_fad_title_archive_locations($html): string
{
    // you can add here all your conditions as if is_page(), is_category() etc..
    $html = 'Locations | '.get_bloginfo('name');

    return $html;
}

/**
 * The page title for the archive-physicians template.
 *
 * @param  string  $html  The title the theme built; replaced.
 */
function uamswp_fad_title_archive_physicians($html): string
{
    // you can add here all your conditions as if is_page(), is_category() etc..
    $html = 'Providers | '.get_bloginfo('name');

    return $html;
}

/**
 * The page title for the archive-taxonomy-conditions template.
 *
 * @param  string  $html  The title the theme built; replaced.
 */
function uamswp_fad_title_archive_taxonomy_conditions($html): string
{
    /** @var string $condition_title */
    global $condition_title;
    // you can add here all your conditions as if is_page(), is_category() etc..
    $html = ($condition_title ?: 'Conditions').' | '.get_bloginfo('name');

    return $html;
}

/**
 * The page title for the archive-taxonomy-treatments template.
 *
 * @param  string  $html  The title the theme built; replaced.
 */
function uamswp_fad_title_archive_taxonomy_treatments($html): string
{
    /** @var string $treatment_title */
    global $treatment_title;
    // you can add here all your conditions as if is_page(), is_category() etc..
    $html = ($treatment_title ?: 'Treatments &amp; Procedures').' | '.get_bloginfo('name');

    return $html;
}

/**
 * The page title for the archive-treatments template.
 *
 * @param  string  $html  The title the theme built; replaced.
 */
function uamswp_fad_title_archive_treatments($html): string
{
    /** @var string $treatment_title */
    global $treatment_title;
    // you can add here all your conditions as if is_page(), is_category() etc..
    $html = $treatment_title.' | '.get_bloginfo('name');

    return $html;
}

/**
 * The page title for the single-clinical-resource template.
 *
 * @param  string  $html  The title the theme built; replaced.
 * @param  mixed  $context  The second argument the title filter passes; unused.
 */
function uamswp_fad_title_single_clinical_resource($html, $context = null): string
{
    /** @var string $page_title_attr */
    global $page_title_attr;
    /** @var string $resource_archive_title_attr */
    global $resource_archive_title_attr;
    // you can add here all your conditions as if is_page(), is_category() etc..
    $meta_title_chars_max = 60;
    $meta_title_base = $page_title_attr.' | '.get_bloginfo('name');
    $meta_title_enhanced_addition = ' | '.$resource_archive_title_attr;
    $meta_title_enhanced = $page_title_attr.$meta_title_enhanced_addition.' | '.get_bloginfo('name');
    $meta_title_enhanced_chars = strlen($meta_title_enhanced);
    if ($meta_title_enhanced_chars <= $meta_title_chars_max) {
        return $meta_title_enhanced;
    }

    return $meta_title_base;
}

/**
 * The page title for the single-condition template.
 *
 * @param  string  $html  The title the theme built; replaced.
 * @param  mixed  $context  The second argument the title filter passes; unused.
 */
function uamswp_fad_title_single_condition($html, $context = null): string
{
    /** @var string $page_title_attr */
    global $page_title_attr;
    /** @var string $condition_title_attr */
    global $condition_title_attr;
    // you can add here all your conditions as if is_page(), is_category() etc..
    $meta_title_chars_max = 60;
    $meta_title_base = $page_title_attr.' | '.get_bloginfo('name');
    $meta_title_enhanced_addition = ' | '.$condition_title_attr;
    $meta_title_enhanced = $page_title_attr.$meta_title_enhanced_addition.' | '.get_bloginfo('name');
    $meta_title_enhanced_chars = strlen($meta_title_enhanced);
    if ($meta_title_enhanced_chars <= $meta_title_chars_max) {
        return $meta_title_enhanced;
    }

    return $meta_title_base;
}

/**
 * The page title for the single-expertise template.
 *
 * @param  string  $html  The title the theme built; replaced.
 * @param  mixed  $context  The second argument the title filter passes; unused.
 */
function uamswp_fad_title_single_expertise($html, $context = null): string
{
    /** @var string $page_title_attr */
    global $page_title_attr;
    /** @var string $expertise_single_name_attr */
    global $expertise_single_name_attr;
    // you can add here all your conditions as if is_page(), is_category() etc..
    $meta_title_chars_max = 60;
    $meta_title_base = $page_title_attr.' | '.get_bloginfo('name');
    $meta_title_enhanced_addition = ' | '.$expertise_single_name_attr;
    $meta_title_enhanced = $page_title_attr.$meta_title_enhanced_addition.' | '.get_bloginfo('name');
    $meta_title_enhanced_chars = strlen($meta_title_enhanced);
    if ($meta_title_enhanced_chars <= $meta_title_chars_max) {
        return $meta_title_enhanced;
    }

    return $meta_title_base;
}

/**
 * The page title for the single-locations template.
 *
 * @param  string  $html  The title the theme built; replaced.
 */
function uamswp_fad_title_single_locations($html): string
{
    /** @var string $page_title_attr */
    global $page_title_attr;
    /** @var string $location_city */
    global $location_city;
    // you can add here all your conditions as if is_page(), is_category() etc..
    $meta_title_chars_max = 60; // Set the maximum number of characters to be used in the <title /> value.
    $meta_title_base = $page_title_attr.' | '.get_bloginfo('name'); // Define the base <title /> value: "[location name] | [site name]"
    $meta_title_enhanced_addition = ' | '.$location_city; // Define a string with the location's city that may be injected into the <title /> value
    $meta_title_enhanced = $page_title_attr.$meta_title_enhanced_addition.' | '.get_bloginfo('name'); // Define the enhanced <title /> value: "[location name] | [city] | [site name]"
    $meta_title_enhanced_chars = strlen($meta_title_enhanced); // Count the number of characters in the enhanced <title /> value
    if ($meta_title_enhanced_chars <= $meta_title_chars_max) { // If the enhanced <title /> value is less than or equal to the maximum number of characters to be used in the <title /> value...
        $html = $meta_title_enhanced; // ... use the enhanced <title /> value
    } else { // Otherwise...
        $html = $meta_title_base; // ... use the base <title /> value
    }

    return $html;
}

/**
 * The page title for the single-physicians template.
 *
 * @param  string  $html  The title the theme built; replaced.
 * @param  mixed  $context  The second argument the title filter passes; unused.
 */
function uamswp_fad_title_single_physicians($html, $context = null): string
{
    /** @var string $full_name_attr */
    global $full_name_attr;
    /** @var string $provider_occupation_title_attr */
    global $provider_occupation_title_attr;
    /** @var string $primary_appointment_city_attr */
    global $primary_appointment_city_attr;
    /** @var string $expertise_primary_name */
    global $expertise_primary_name;

    $meta_title_chars_max = 60; // The recommended length for meta titles is 50-60 characters. Sets the max to 60.
    $meta_title_separator = ' | '; // Characters separating components of the meta title

    // Base meta title ("{Full display name} | UAMS Health")
    $meta_title_base = $full_name_attr.$meta_title_separator.get_bloginfo('name'); // Construct the meta title

    // Base meta title ("{Full display name} | {Clinical title} | UAMS Health")
    $meta_title_enhanced = $full_name_attr.$meta_title_separator.$provider_occupation_title_attr.$meta_title_separator.get_bloginfo('name'); // Construct the meta title
    $meta_title_enhanced_chars = strlen($meta_title_enhanced); // Count the characters in the meta title

    // Enhanced meta title level 1 ("{Full display name} | {Clinical title} | {City of primary location} | UAMS Health")
    $meta_title_enhanced_x2 = $full_name_attr.$meta_title_separator.$provider_occupation_title_attr.$meta_title_separator.$primary_appointment_city_attr.$meta_title_separator.get_bloginfo('name'); // Construct the meta title
    $meta_title_enhanced_x2_chars = strlen($meta_title_enhanced_x2); // Count the characters in the meta title

    // Enhanced meta title level 2 ("{Full display name} | {Clinical title} | {Primary area of expertise} | {City of primary location} | UAMS Health")
    $meta_title_enhanced_x3 = $full_name_attr.$meta_title_separator.$provider_occupation_title_attr.$meta_title_separator.$expertise_primary_name.$meta_title_separator.$primary_appointment_city_attr.$meta_title_separator.get_bloginfo('name'); // Construct the meta title
    $meta_title_enhanced_x3_chars = strlen($meta_title_enhanced_x3); // Count the characters in the meta title

    if ($expertise_primary_name && ($meta_title_enhanced_x3_chars <= $meta_title_chars_max)) {
        $html = $meta_title_enhanced_x3;
    } elseif ($primary_appointment_city_attr && ($meta_title_enhanced_x2_chars <= $meta_title_chars_max)) {
        $html = $meta_title_enhanced_x2;
    } elseif ($meta_title_enhanced_chars <= $meta_title_chars_max) {
        $html = $meta_title_enhanced;
    } else {
        $html = $meta_title_base;
    }

    return $html;
}

/**
 * The page title for the single-treatment template.
 *
 * @param  string  $html  The title the theme built; replaced.
 * @param  mixed  $context  The second argument the title filter passes; unused.
 */
function uamswp_fad_title_single_treatment($html, $context = null): string
{
    /** @var string $page_title_attr */
    global $page_title_attr;
    /** @var string $treatment_title_attr */
    global $treatment_title_attr;
    // you can add here all your conditions as if is_page(), is_category() etc..
    $meta_title_chars_max = 60;
    $meta_title_base = $page_title_attr.' | '.get_bloginfo('name');
    $meta_title_enhanced_addition = ' | '.$treatment_title_attr;
    $meta_title_enhanced = $page_title_attr.$meta_title_enhanced_addition.' | '.get_bloginfo('name');
    $meta_title_enhanced_chars = strlen($meta_title_enhanced);
    if ($meta_title_enhanced_chars <= $meta_title_chars_max) {
        return $meta_title_enhanced;
    }

    return $meta_title_base;
}
