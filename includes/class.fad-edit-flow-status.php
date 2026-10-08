<?php
/**
 * Edit Flow status control on published providers and locations.
 *
 * Edit Flow hides the Status dropdown on a published post from anyone who lacks the publish
 * capability, so a Doc Profile Editor or Doc Profile Admin can only move a published profile
 * to Inactive (or another custom status) through Quick Edit. WordPress itself allows the
 * change: moving a published post to an unpublished status needs only edit_published_*,
 * which both roles have. This brings the dropdown back on the edit screen for those users.
 *
 * Publishing is unaffected. Once the profile leaves Published, Edit Flow's normal rules
 * apply again and the dropdown no longer offers Published.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function uamswp_fad_edit_flow_published_status_control( $hook ) {

	if ( 'post.php' !== $hook || ! wp_script_is( 'edit_flow-custom_status', 'enqueued' ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, array( 'provider', 'location' ), true ) ) {
		return;
	}

	// Runs after Edit Flow's own ready handler, which this script is attached to.
	wp_add_inline_script(
		'edit_flow-custom_status',
		"jQuery( function ( $ ) {
			if (
				'publish' !== current_status
				|| current_user_can_publish_posts
				|| ! current_user_can_edit_published_posts
			) {
				return;
			}

			var \$select = $( 'select[name=\"post_status\"]' );
			if ( ! \$select.length ) {
				return;
			}

			// Edit Flow appends a second, selected Published option when it locks the dropdown.
			\$select.find( 'option[value=\"publish\"]' ).slice( 1 ).remove();
			\$select.val( 'publish' );

			$( '#post-status-select' ).hide();
			$( '.edit-post-status' ).show();

			// Core's label update bails early when the date picker is absent, which it is for
			// users who can't publish, so keep the Status label in step with the choice here.
			$( '#post-status-select' ).on( 'click', '.save-post-status, .cancel-post-status', function () {
				$( '#post-status-display' ).text( \$select.find( 'option:selected' ).text() );
			} );

			// Taking a profile out of Published shouldn't require every field to be complete,
			// matching ACF's own Save Draft button and Quick Edit, neither of which validates.
			$( '#publish' ).on( 'click', function () {
				if ( window.acf && acf.validation ) {
					acf.validation.set( 'ignore', 'publish' !== \$select.val() );
				}
			} );
		} );",
		'after'
	);

}
add_action( 'admin_enqueue_scripts', 'uamswp_fad_edit_flow_published_status_control', 20 );
