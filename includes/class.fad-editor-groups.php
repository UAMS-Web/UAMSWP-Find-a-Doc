<?php
/**
 * Editor groups.
 *
 * Providers and locations are assigned to one or more editor groups (a service line, a
 * department section, a stand-alone team such as the dietitians). Each group lists the
 * Doc Profile Editor accounts that may edit its records, with one of them flagged as the
 * primary contact. A doc_editor can edit a provider or location when they are the post's
 * author or a member of one of its groups. Deleting and publishing stay with doc_admin
 * and administrators.
 *
 * Service lines can point at an editor group; providers and locations that have no group
 * of their own inherit it from their service line when they are saved.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'UAMSWP_FAD_EDITOR_GROUP_TAX', 'editor_group' );

/**
 * Post types that use editor groups, with the ACF field that holds their service line.
 *
 * @return array<string,string> post type => service line field name
 */
function uamswp_fad_editor_group_post_types() {
	return array(
		'provider' => 'physician_service_line',
		'location' => 'location_service_line',
	);
}

// 1. Taxonomy

function uamswp_fad_register_editor_group_taxonomy() {

	$labels = array(
		'name'              => 'Editor Groups',
		'singular_name'     => 'Editor Group',
		'menu_name'         => 'Editor Groups',
		'all_items'         => 'All Editor Groups',
		'edit_item'         => 'Edit Editor Group',
		'view_item'         => 'View Editor Group',
		'update_item'       => 'Update Editor Group',
		'add_new_item'      => 'Add New Editor Group',
		'new_item_name'     => 'New Editor Group Name',
		'parent_item'       => 'Parent Editor Group',
		'search_items'      => 'Search Editor Groups',
		'not_found'         => 'No editor groups found',
		'back_to_items'     => 'Back to Editor Groups',
		'item_link'         => 'Editor Group Link',
		'name_admin_bar'    => 'Editor Group',
	);

	register_taxonomy(
		UAMSWP_FAD_EDITOR_GROUP_TAX,
		array_keys( uamswp_fad_editor_group_post_types() ),
		array(
			'labels'             => $labels,
			'description'        => 'Which Doc Profile Editors may edit these providers and locations.',
			'public'             => false,
			'publicly_queryable' => false,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => false,
			'query_var'          => false,
			'rewrite'            => false,
			'capabilities'       => array(
				'manage_terms' => 'manage_options',
				'edit_terms'   => 'manage_options',
				'delete_terms' => 'manage_options',
				// doc_admin and administrators assign groups; doc_editor sees the box read-only.
				'assign_terms' => 'edit_others_physicians',
			),
		)
	);
}
add_action( 'init', 'uamswp_fad_register_editor_group_taxonomy', 5 );

// 2. Fields on editor group terms and on service line terms

function uamswp_fad_register_editor_group_fields() {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_uamswp_editor_group',
			'title'    => 'Editor Group Members',
			'fields'   => array(
				array(
					'key'           => 'field_uamswp_editor_group_primary_editor',
					'label'         => 'Primary editor',
					'name'          => 'editor_group_primary_editor',
					'type'          => 'user',
					'instructions'  => 'The main contact for this group. Has the same editing rights as the other editors.',
					'role'          => array( 'doc_editor', 'doc_admin' ),
					'return_format' => 'id',
					'multiple'      => 0,
					'allow_null'    => 1,
				),
				array(
					'key'           => 'field_uamswp_editor_group_editors',
					'label'         => 'Editors',
					'name'          => 'editor_group_editors',
					'type'          => 'user',
					'instructions'  => 'Everyone listed here may edit the providers and locations in this group.',
					'role'          => array( 'doc_editor', 'doc_admin' ),
					'return_format' => 'id',
					'multiple'      => 1,
					'allow_null'    => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'taxonomy',
						'operator' => '==',
						'value'    => UAMSWP_FAD_EDITOR_GROUP_TAX,
					),
				),
			),
			'position' => 'normal',
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_uamswp_service_line_editor_group',
			'title'    => 'Editor Group',
			'fields'   => array(
				array(
					'key'           => 'field_uamswp_service_line_editor_group',
					'label'         => 'Editor group',
					'name'          => 'service_line_editor_group',
					'type'          => 'taxonomy',
					'instructions'  => 'Providers and locations in this service line that have no editor group of their own are placed in this group when they are saved.',
					'taxonomy'      => UAMSWP_FAD_EDITOR_GROUP_TAX,
					'field_type'    => 'select',
					'allow_null'    => 1,
					'add_term'      => 0,
					'save_terms'    => 0,
					'load_terms'    => 0,
					'return_format' => 'id',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'taxonomy',
						'operator' => '==',
						'value'    => 'service_line',
					),
				),
			),
			'position' => 'normal',
		)
	);
}
add_action( 'acf/init', 'uamswp_fad_register_editor_group_fields' );

// 3. Membership lookups

/**
 * User IDs that may edit the records in a group: the primary editor plus the editors list.
 *
 * Reads the term meta ACF writes, so it works before ACF's API is loaded and costs one
 * cached meta lookup per term.
 *
 * @param int $term_id
 * @return int[]
 */
function uamswp_fad_editor_group_members( $term_id ) {
	static $cache = array();

	$term_id = (int) $term_id;
	if ( isset( $cache[ $term_id ] ) ) {
		return $cache[ $term_id ];
	}

	$members = array();
	foreach ( array( 'editor_group_primary_editor', 'editor_group_editors' ) as $key ) {
		$value = get_term_meta( $term_id, $key, true );
		foreach ( (array) $value as $user_id ) {
			$user_id = (int) $user_id;
			if ( $user_id > 0 ) {
				$members[] = $user_id;
			}
		}
	}

	$cache[ $term_id ] = array_values( array_unique( $members ) );
	return $cache[ $term_id ];
}

/**
 * Editor group term IDs a user belongs to.
 *
 * @param int $user_id
 * @return int[]
 */
function uamswp_fad_user_editor_groups( $user_id ) {
	static $cache = array();

	$user_id = (int) $user_id;
	if ( ! $user_id ) {
		return array();
	}
	if ( isset( $cache[ $user_id ] ) ) {
		return $cache[ $user_id ];
	}

	$groups = array();
	$terms  = get_terms(
		array(
			'taxonomy'   => UAMSWP_FAD_EDITOR_GROUP_TAX,
			'hide_empty' => false,
			'fields'     => 'ids',
		)
	);
	if ( is_array( $terms ) ) {
		foreach ( $terms as $term_id ) {
			if ( in_array( $user_id, uamswp_fad_editor_group_members( $term_id ), true ) ) {
				$groups[] = (int) $term_id;
			}
		}
	}

	$cache[ $user_id ] = $groups;
	return $groups;
}

/**
 * Whether a user is a member of one of the editor groups a post belongs to.
 *
 * @param int $user_id
 * @param int $post_id
 * @return bool
 */
function uamswp_fad_user_in_post_editor_group( $user_id, $post_id ) {
	$groups = uamswp_fad_user_editor_groups( $user_id );
	if ( empty( $groups ) ) {
		return false;
	}

	$post_groups = wp_get_object_terms( (int) $post_id, UAMSWP_FAD_EDITOR_GROUP_TAX, array( 'fields' => 'ids' ) );
	if ( is_wp_error( $post_groups ) || empty( $post_groups ) ) {
		return false;
	}

	return (bool) array_intersect( $groups, array_map( 'intval', $post_groups ) );
}

// 4. Capability mapping: a group member is treated like the post's author for editing

function uamswp_fad_editor_group_map_meta_cap( $caps, $cap, $user_id, $args ) {

	if ( 'edit_post' !== $cap || empty( $args[0] ) ) {
		return $caps;
	}

	$post = get_post( $args[0] );
	if ( ! $post || ! array_key_exists( $post->post_type, uamswp_fad_editor_group_post_types() ) ) {
		return $caps;
	}

	$type = get_post_type_object( $post->post_type );
	if ( ! $type ) {
		return $caps;
	}

	$others = $type->cap->edit_others_posts;
	$index  = array_search( $others, $caps, true );
	if ( false === $index ) {
		return $caps; // Own post, or the role already covers it.
	}

	if ( ! uamswp_fad_user_in_post_editor_group( $user_id, $post->ID ) ) {
		return $caps;
	}

	// Replace the "others" requirement with the plain edit capability; the status-based
	// requirements core added (edit_published_*, edit_private_*) stay in place.
	$caps[ $index ] = $type->cap->edit_posts;
	return array_values( array_unique( $caps ) );
}
add_filter( 'map_meta_cap', 'uamswp_fad_editor_group_map_meta_cap', 10, 4 );

// 5. Admin list: a doc_editor sees their groups' records as well as their own

function uamswp_fad_editor_group_admin_list_scope( $query ) {

	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$post_type = $query->get( 'post_type' );
	if ( ! is_string( $post_type ) || ! array_key_exists( $post_type, uamswp_fad_editor_group_post_types() ) ) {
		return;
	}

	$type = get_post_type_object( $post_type );
	if ( ! $type || current_user_can( $type->cap->edit_others_posts ) ) {
		return;
	}

	$user_id = get_current_user_id();
	$groups  = uamswp_fad_user_editor_groups( $user_id );
	if ( empty( $groups ) ) {
		return; // Core's own-posts restriction applies.
	}

	$tt_ids = get_terms(
		array(
			'taxonomy'   => UAMSWP_FAD_EDITOR_GROUP_TAX,
			'include'    => $groups,
			'hide_empty' => false,
			'fields'     => 'tt_ids',
		)
	);
	if ( is_wp_error( $tt_ids ) || empty( $tt_ids ) ) {
		return;
	}

	// Core set author = current user because the role lacks edit_others; widen it to the groups.
	$query->set( 'author', '' );
	$query->set( 'uamswp_editor_group_tt_ids', array_map( 'intval', $tt_ids ) );
	$query->set( 'uamswp_editor_group_user', $user_id );
}
add_action( 'pre_get_posts', 'uamswp_fad_editor_group_admin_list_scope' );

function uamswp_fad_editor_group_admin_list_where( $where, $query ) {
	global $wpdb;

	$tt_ids  = $query->get( 'uamswp_editor_group_tt_ids' );
	$user_id = (int) $query->get( 'uamswp_editor_group_user' );
	if ( empty( $tt_ids ) || ! $user_id ) {
		return $where;
	}

	$in = implode( ',', array_map( 'intval', (array) $tt_ids ) );

	$where .= $wpdb->prepare(
		" AND ( {$wpdb->posts}.post_author = %d OR {$wpdb->posts}.ID IN ( SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ( {$in} ) ) )",
		$user_id
	);

	return $where;
}
add_filter( 'posts_where', 'uamswp_fad_editor_group_admin_list_where', 10, 2 );

// 6. Inherit the group from the service line

/**
 * Editor group IDs a post should inherit from its service line, or an empty array.
 *
 * @param int $post_id
 * @return int[]
 */
function uamswp_fad_editor_groups_from_service_line( $post_id ) {

	$post_type = get_post_type( $post_id );
	$fields    = uamswp_fad_editor_group_post_types();
	if ( ! isset( $fields[ $post_type ] ) || ! function_exists( 'get_field' ) ) {
		return array();
	}

	$service_lines = get_field( $fields[ $post_type ], $post_id );
	$groups        = array();
	foreach ( (array) $service_lines as $service_line ) {
		$service_line_id = is_object( $service_line ) ? (int) $service_line->term_id : (int) $service_line;
		if ( ! $service_line_id ) {
			continue;
		}
		$group = get_term_meta( $service_line_id, 'service_line_editor_group', true );
		foreach ( (array) $group as $group_id ) {
			$group_id = (int) $group_id;
			if ( $group_id > 0 && term_exists( $group_id, UAMSWP_FAD_EDITOR_GROUP_TAX ) ) {
				$groups[] = $group_id;
			}
		}
	}

	return array_values( array_unique( $groups ) );
}

/**
 * Give a post its service line's editor group when it has none. Returns true when terms were set.
 *
 * @param int $post_id
 * @return bool
 */
function uamswp_fad_inherit_editor_group( $post_id ) {

	$existing = wp_get_object_terms( (int) $post_id, UAMSWP_FAD_EDITOR_GROUP_TAX, array( 'fields' => 'ids' ) );
	if ( is_wp_error( $existing ) || ! empty( $existing ) ) {
		return false;
	}

	$groups = uamswp_fad_editor_groups_from_service_line( $post_id );
	if ( empty( $groups ) ) {
		return false;
	}

	$result = wp_set_object_terms( (int) $post_id, $groups, UAMSWP_FAD_EDITOR_GROUP_TAX, false );
	return ! is_wp_error( $result );
}

function uamswp_fad_inherit_editor_group_on_save( $post_id, $post ) {

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! array_key_exists( $post->post_type, uamswp_fad_editor_group_post_types() ) ) {
		return;
	}

	uamswp_fad_inherit_editor_group( $post_id );
}
// Priority 20: after ACF has written the service line field (ACF saves on save_post at 10).
add_action( 'save_post', 'uamswp_fad_inherit_editor_group_on_save', 20, 2 );

// 7. One-click backfill for records saved before the mapping existed

function uamswp_fad_editor_group_sync_url() {
	return wp_nonce_url(
		admin_url( 'admin-post.php?action=uamswp_fad_sync_editor_groups' ),
		'uamswp_fad_sync_editor_groups'
	);
}

function uamswp_fad_editor_group_sync_notice() {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$synced = isset( $_GET['uamswp_synced'] ) ? absint( $_GET['uamswp_synced'] ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( null !== $synced ) {
		printf(
			'<div class="notice notice-success"><p>%s</p></div>',
			esc_html( sprintf( '%d providers and locations were placed in their service line\'s editor group.', $synced ) )
		);
	}

	printf(
		'<div class="notice notice-info"><p>%s <a class="button" href="%s">%s</a></p></div>',
		esc_html( 'Records that have no editor group inherit one from their service line when they are saved. To apply that mapping to everything now:' ),
		esc_url( uamswp_fad_editor_group_sync_url() ),
		esc_html( 'Apply service line mapping' )
	);
}
add_action( UAMSWP_FAD_EDITOR_GROUP_TAX . '_pre_add_form', 'uamswp_fad_editor_group_sync_notice' );

function uamswp_fad_sync_editor_groups() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You are not allowed to do that.', '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'uamswp_fad_sync_editor_groups' );

	$synced = 0;
	foreach ( array_keys( uamswp_fad_editor_group_post_types() ) as $post_type ) {
		$ids = get_posts(
			array(
				'post_type'      => $post_type,
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => UAMSWP_FAD_EDITOR_GROUP_TAX,
						'operator' => 'NOT EXISTS',
					),
				),
			)
		);
		foreach ( $ids as $post_id ) {
			if ( uamswp_fad_inherit_editor_group( $post_id ) ) {
				$synced++;
			}
		}
	}

	wp_safe_redirect(
		add_query_arg(
			array(
				'taxonomy'      => UAMSWP_FAD_EDITOR_GROUP_TAX,
				'post_type'     => 'provider',
				'uamswp_synced' => $synced,
			),
			admin_url( 'edit-tags.php' )
		)
	);
	exit;
}
add_action( 'admin_post_uamswp_fad_sync_editor_groups', 'uamswp_fad_sync_editor_groups' );
