<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for plugin Uamswp
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Plugin:
 * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
 */
require_once dirname( __FILE__ ) . '/tgm/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'uamswp_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function uamswp_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// This is an example of how to include a plugin from an arbitrary external source in your theme.
		// array(
		// 	'name'         => 'TGM New Media Plugin', // The plugin name.
		// 	'slug'         => 'tgm-new-media-plugin', // The plugin slug (typically the folder name).
		// 	'source'       => 'https://s3.amazonaws.com/tgm/tgm-new-media-plugin.zip', // The plugin source.
		// 	'required'     => true, // If false, the plugin is only 'recommended' instead of required.
		// 	'external_url' => 'https://github.com/thomasgriffin/New-Media-Image-Uploader', // If set, overrides default API URL and points to an external URL.
		// ),

		// This is an example of how to include a plugin from a GitHub repository in your theme.
		// This presumes that the plugin code is based in the root of the GitHub repository
		// and not in a subdirectory ('/src') of the repository.
		// array(
		// 	'name'      => 'Adminbar Link Comments to Pending',
		// 	'slug'      => 'adminbar-link-comments-to-pending',
		// 	'source'    => 'https://github.com/jrfnl/WP-adminbar-comments-to-pending/archive/master.zip',
		// ),

		// This is an example of how to include a plugin from the WordPress Plugin Repository.
		// array(
		// 	'name'      => 'Meta Box',
		// 	'slug'      => 'meta-box',
		// 	'required'  => true,
		// ),

		// array(
		// 	'name'      => 'MB Relationsships',
		// 	'slug'      => 'mb-relationships',
		// 	'required'  => true,
		// ),

		// array(
		// 	'name'      => 'Meta Box - FacetWP Integrator',
		// 	'slug'      => 'meta-box-facetwp-integrator',
		// 	'required'  => true,
		// ),

		// array(
		// 	'name'      => 'Meta Box Text Limiter',
		// 	'slug'      => 'meta-box-text-limiter',
		// 	'required'  => true,
		// ),

		// Begin Meta Box extensions.
		// array(
		// 	'name'               => 'Meta Box Tabs', // The plugin name.
		// 	'slug'               => 'meta-box-tabs', // The plugin slug (typically the folder name).
		// 	'source'             => dirname( __FILE__ ) . '/plugins/meta-box-tabs.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => 'MB Admin Columns', // The plugin name.
		// 	'slug'               => 'mb-admin-columns', // The plugin slug (typically the folder name).
		// 	'source'             => dirname( __FILE__ ) . '/plugins/mb-admin-columns.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => 'MB Term Meta', // The plugin name.
		// 	'slug'               => 'mb-term-meta', // The plugin slug (typically the folder name).
		// 	'source'             => dirname( __FILE__ ) . '/plugins/mb-term-meta.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => 'MB Custom Table', // The plugin name.
		// 	'slug'               => 'mb-custom-table', // The plugin slug (typically the folder name).
		// 	'source'             => dirname( __FILE__ ) . '/plugins/mb-custom-table.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => 'Meta Box Group', // The plugin name.
		// 	'slug'               => 'meta-box-group', // The plugin slug (typically the folder name).
		// 	'source'             => dirname( __FILE__ ) . '/plugins/meta-box-group.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => 'Meta Box Columns', // The plugin name.
		// 	'slug'               => 'meta-box-columns', // The plugin slug (typically the folder name).
		// 	'source'             => dirname( __FILE__ ) . '/plugins/meta-box-columns.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => 'Meta Box Updater', // The plugin name.
		// 	'slug'               => 'meta-box-updater', // The plugin slug (typically the folder name).
		// 	'source'             => dirname( __FILE__ ) . '/plugins/meta-box-updater.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => 'MB Revision', // The plugin name.
		// 	'slug'               => 'mb-revision', // The plugin slug (typically the folder name).
		// 	'source'             => dirname( __FILE__ ) . '/plugins/mb-revision.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => 'Meta Box Conditional Logic', // The plugin name.
		// 	'slug'               => 'meta-box-conditional-logic', // The plugin slug (typically the folder name).
		// 	'source'             => dirname( __FILE__ ) . '/plugins/meta-box-conditional-logic.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => 'Meta Box Include Exclude', // The plugin name.
		// 	'slug'               => 'meta-box-include-exclude', // The plugin slug (typically the folder name).
		// 	'source'             => dirname( __FILE__ ) . '/plugins/meta-box-includeexclude.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// Begin FacetWP & extensions.
		array(
			'name'               => 'FacetWP', // The plugin name.
			'slug'               => 'factwp', // The plugin slug (typically the folder name).
			'source'             => dirname( __FILE__ ) . '/plugins/facetwp.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
		),

		array(
			'name'               => 'FacetWP - Alpha', // The plugin name.
			'slug'               => 'factwp-alpha', // The plugin slug (typically the folder name).
			'source'             => dirname( __FILE__ ) . '/plugins/factwp-alpha.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
		),

		array(
			'name'               => 'FacetWP - Load More', // The plugin name.
			'slug'               => 'factwp-load-more', // The plugin slug (typically the folder name).
			'source'             => dirname( __FILE__ ) . '/plugins/factwp-load-more.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
		),

		array(
			'name'               => 'FacetWP - Cache', // The plugin name.
			'slug'               => 'facetwp-cache', // The plugin slug (typically the folder name).
			'source'             => dirname( __FILE__ ) . '/plugins/facetwp-cache.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
		),

		// array(
		// 	'name'               => 'FacetWP - Conditional Logic', // The plugin name.
		// 	'slug'               => 'facetwp-conditional-logic', // The plugin slug (typically the folder name).
		// 	'source'             => dirname( __FILE__ ) . '/plugins/facetwp-conditional-logic.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// Begin Ajax Search Pro.
		array(
			'name'               => 'Ajax Search Pro', // The plugin name.
			'slug'               => 'ajax-search-pro', // The plugin slug (typically the folder name).
			'source'             => dirname( __FILE__ ) . '/plugins/ajax-search-pro.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
		),

	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'uamswp',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

		/*
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'uamswp' ),
			'menu_title'                      => __( 'Install Plugins', 'uamswp' ),
			/* translators: %s: plugin name. * /
			'installing'                      => __( 'Installing Plugin: %s', 'uamswp' ),
			/* translators: %s: plugin name. * /
			'updating'                        => __( 'Updating Plugin: %s', 'uamswp' ),
			'oops'                            => __( 'Something went wrong with the plugin API.', 'uamswp' ),
			'notice_can_install_required'     => _n_noop(
				/* translators: 1: plugin name(s). * /
				'This theme requires the following plugin: %1$s.',
				'This theme requires the following plugins: %1$s.',
				'uamswp'
			),
			'notice_can_install_recommended'  => _n_noop(
				/* translators: 1: plugin name(s). * /
				'This theme recommends the following plugin: %1$s.',
				'This theme recommends the following plugins: %1$s.',
				'uamswp'
			),
			'notice_ask_to_update'            => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'uamswp'
			),
			'notice_ask_to_update_maybe'      => _n_noop(
				/* translators: 1: plugin name(s). * /
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'uamswp'
			),
			'notice_can_activate_required'    => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'uamswp'
			),
			'notice_can_activate_recommended' => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'uamswp'
			),
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'uamswp'
			),
			'update_link' 					  => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'uamswp'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'uamswp'
			),
			'return'                          => __( 'Return to Required Plugins Installer', 'uamswp' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'uamswp' ),
			'activated_successfully'          => __( 'The following plugin was activated successfully:', 'uamswp' ),
			/* translators: 1: plugin name. * /
			'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'uamswp' ),
			/* translators: 1: plugin name. * /
			'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'uamswp' ),
			/* translators: 1: dashboard link. * /
			'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'uamswp' ),
			'dismiss'                         => __( 'Dismiss this notice', 'uamswp' ),
			'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'uamswp' ),
			'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'uamswp' ),

			'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
		),
		*/
	);

	tgmpa( $plugins, $config );
}
