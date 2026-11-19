<?php

declare(strict_types=1);
/**
 * Admin notice: the FacetWP - Alpha for UAMS plugin is missing or older than this plugin needs.
 *
 * The alphabet facet on the provider and location lists comes from the standalone
 * UAMS-Web/facetwp-alpha-uams plugin, which is installed through UAMSWP-Pusher rather than from the
 * required-plugins prompt (#1437), so that prompt never names it. From 1.3.5.2 the plugin refreshes
 * the results itself when a letter is clicked (UAMS-Web/facetwp-alpha-uams#80). Older copies do
 * not, and rely on a click handler that #1433 removes from this plugin, so the floor is 1.3.5.2
 * (#1438).
 *
 * The plugin defines `FACETWP_ALPHA_VERSION` from 1.3.5 on; 1.3.1 and earlier define nothing. An
 * undefined constant therefore means either the plugin is missing or it is older than 1.3.5, and
 * the notice says both.
 */
if (! defined('WPINC')) {
    exit;
}

/**
 * The lowest FacetWP - Alpha for UAMS version this plugin works with.
 */
const UAMSWP_FAD_ALPHA_MIN_VERSION = '1.3.5.2';

/**
 * The installed FacetWP - Alpha for UAMS version, or null when the plugin defines none.
 *
 * @param  string  $constant  The constant the plugin defines its version in; a parameter only so tests can name one of their own.
 */
function uamswp_fad_alpha_installed_version(string $constant = 'FACETWP_ALPHA_VERSION'): ?string
{
    if (! defined($constant)) {
        return null;
    }

    $version = constant($constant);

    return is_string($version) && $version !== '' ? $version : null;
}

/**
 * The sentence describing what is wrong with the installed version, or null when it meets the floor.
 *
 * @param  string|null  $installed  The installed version, or null when the plugin defines none.
 */
function uamswp_fad_alpha_version_problem(?string $installed): ?string
{
    if ($installed === null) {
        return sprintf(
            /* translators: %s: the minimum version. */
            __('FacetWP - Alpha for UAMS is not active, or is older than %s.', 'uamswp-find-a-doc'),
            UAMSWP_FAD_ALPHA_MIN_VERSION
        );
    }

    if (version_compare($installed, UAMSWP_FAD_ALPHA_MIN_VERSION, '>=')) {
        return null;
    }

    return sprintf(
        /* translators: 1: the installed version, 2: the minimum version. */
        __('FacetWP - Alpha for UAMS %1$s is installed; Find-a-Doc needs %2$s or later.', 'uamswp-find-a-doc'),
        $installed,
        UAMSWP_FAD_ALPHA_MIN_VERSION
    );
}

/**
 * Print the notice when the plugin is missing or too old.
 *
 * Shown only to users who can activate plugins, so nobody sees a notice they cannot act on.
 */
function uamswp_fad_alpha_version_notice(): void
{
    if (! current_user_can('activate_plugins')) {
        return;
    }

    $problem = uamswp_fad_alpha_version_problem(uamswp_fad_alpha_installed_version());

    if ($problem === null) {
        return;
    }

    echo '<div class="notice notice-error"><p><strong>'.esc_html($problem).'</strong> ';
    echo esc_html__('The alphabet filter on the provider and location lists needs it. Install or update it through UAMSWP-Pusher from the UAMS-Web/facetwp-alpha-uams repository.', 'uamswp-find-a-doc').'</p></div>';
}

/**
 * Register the notice.
 */
function uamswp_fad_alpha_version_register(): void
{
    add_action('admin_notices', 'uamswp_fad_alpha_version_notice');
}

uamswp_fad_alpha_version_register();
