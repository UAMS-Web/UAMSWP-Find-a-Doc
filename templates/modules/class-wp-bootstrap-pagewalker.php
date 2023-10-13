<?php
/**
 * WP Bootstrap Pagewalker
 *
 * @package WP-Bootstrap-Pagewalker
 *
 * @since 1.0
 * @author Todd McKee
 *
 * Based on: https://github.com/wp-bootstrap/wp-bootstrap-navwalker
 */

/* Check if Class Exists. */
if ( ! class_exists( 'WP_Bootstrap_Pagewalker' ) ) {
	/**
	 * WP_Bootstrap_Pagewalker class.
	 *
	 * @extends Walker_Nav_Menu
	 */
	class WP_Bootstrap_Pagewalker extends Walker_Page {

		/**
		 * Start Level.
		 *
		 * @see Walker::start_lvl()
		 * @since 3.0.0
		 *
		 * @access public
		 * @param mixed $output Passed by reference. Used to append additional content.
		 * @param int   $depth (default: 0) Depth of page. Used for padding.
		 * @param array $args (default: array()) Arguments.
		 * @return void
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			// find all links with an id in the output.
			preg_match_all( '/(<a.*?id=\"|\')(.*?)\"|\'.*?>/im', $output, $matches );
			// with pointer at end of array check if we got an ID match.
			if ( end( $matches[2] ) ) {
				// build a string to use as aria-labelledby.
				$labledby = 'aria-labelledby="' . end( $matches[2] ) . '"';
			}

			$output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\" " . $labledby . ">\n";
		}

		/**
		 * Start El.
		 *
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @access public
		 * @param mixed $output Passed by reference. Used to append additional content.
		 * @param mixed $item Menu item data object.
		 * @param int   $depth (default: 0) Depth of menu item. Used for padding.
		 * @param array $args (default: array()) Arguments.
		 * @param int   $id (default: 0) Menu item ID.
		 * @return void
		 */
		public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$css_class = array('page-item-' . $page->ID);
			// BSv4 classname - as of v4-alpha.
			$css_class[] = 'nav-item';

			if( isset( $args['pages_with_children'][ $page->ID ] ) )
				$css_class[] = 'page-item-has-children dropdown';

			if ( !empty($current_page) ) {
				$_current_page = get_post( $current_page );
			if ( in_array( $page->ID, $_current_page->ancestors ) )
				$css_class[] = 'current-page-ancestor';
			if ( $page->ID == $current_page )
				$css_class[] = 'current-page-item';
			elseif ( $_current_page && $page->ID == $_current_page->post_parent )
				$css_class[] = 'current-page-parent';
			} elseif ( $page->ID == get_option('page_for_posts') ) {
				$css_class[] = 'current-page-parent';
			}

			/**
			* Filter the list of CSS classes to include with each page item in the list.
			*
			* @since 2.8.0
			*
			* @see wp_list_pages()
			*
			* @param array $css_class An array of CSS classes to be applied
			* to each list item.
			* @param WP_Post $page Page data object.
			* @param int $depth Depth of page, used for padding.
			* @param array $args An array of arguments.
			* @param int $current_page ID of the current page.
			*/
			if ( in_array( 'current-page-item', $css_class, true ) || in_array( 'current-page-parent', $css_class, true ) ) {
				$css_class[] = ' active';
			}

			$css_class = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

			$css_class = $css_class ? ' class="' . esc_attr( $css_class ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'page-item-' . $page->ID, $page, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"' . $id . $css_class . '>';
			$atts = array();

			// Add alt name for $post_title
			$alt_name = '';
			$alt_name = get_post_meta($page->ID, 'page_nav_alt_name', true);

			if ( '' === $page->post_title )
				$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );

			if ( ! empty( $page->post_title ) ) {
				$atts['title'] = ! empty( $page->post_title )   ? strip_tags( $page->post_title ) : '';
			} else {
				$atts['title'] = sprintf( __( '#%d (no title)' ), $page->ID );
			}
			if ( ! empty( $alt_name ) ) {
				$atts['title'] = $alt_name . '; ' . $atts['title'];
			}

			// $atts['target'] = ! empty( $page->target )	? $page->target	: '';
			// $atts['rel']    = ! empty( $page->xfn )		? $page->xfn	: '';
			// If item has_children add atts to a.
			$atts['href'] 	= ! empty( get_permalink($page->ID) ) ? get_permalink($page->ID) : '#';

			if(preg_match('/dropdown/', $css_class) != FALSE){
				// $atts['href']   		= '#';
				$atts['data-toggle']	= 'dropdown';
				$atts['aria-haspopup']	= 'true';
				$atts['aria-expanded']	= 'false';
				$atts['class']			= 'dropdown-toggle nav-link';
				$atts['id']				= 'menu-item-dropdown-' . $page->ID;
			} else {
				// if we are in a dropdown then the the class .dropdown-item
				// should be used instead of .nav-link.
				if ( $depth > 0 ) {
					$atts['class']	= 'dropdown-item';
				} else {
					$atts['class']	= 'nav-link';
				}
			}

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			// $item_output = $args->before; // Orig
			$item_output = '';
			$item_output .= '<a' . $attributes . '>';

			// initiate empty icon var then if we have a string containing icon classes...
			$icon_html = '';
			$icon_class_string = '';
			if ( ! empty( $alt_name ) ) {
				$page->post_title = $alt_name;
			}
			if ( ! empty( $icon_class_string ) ) {
				// append an <i> with the icon classes to what is output before links.
				$icon_html = '<i class="' . esc_attr( $icon_class_string ) . '" aria-hidden="true"></i> ';
			}
			// $item_output .= $args->link_before . $icon_html . apply_filters( 'the_title', $page->post_title, $page->ID ) . $args->link_after; // Orig
			$item_output .= $icon_html . apply_filters( 'the_title', $page->post_title, $page->ID );
			$item_output .= '</a>';
			// $item_output .= $args->after; // Orig
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $page, $depth, $args );

			// if ( !empty($show_date) ) {
			// 	if ( 'modified' == $show_date )
			// 	$time = $page->post_modified;
			// 	else
			// 	$time = $page->post_date;

			// 	$output .= " " . mysql2date($date_format, $time);

			// }

		}

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth.
		 *
		 * This method shouldn't be called directly, use the walk() method instead.
		 *
		 * @see Walker::start_el()
		 * @since 2.5.0
		 *
		 * @access public
		 * @param mixed $element Data object.
		 * @param mixed $children_elements List of elements to continue traversing.
		 * @param mixed $max_depth Max depth to traverse.
		 * @param mixed $depth Depth of current element.
		 * @param mixed $args Arguments.
		 * @param mixed $output Passed by reference. Used to append additional content.
		 * @return null Null on failure with no changes to parameters.
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return; }
			$id_field = $this->db_fields['id'];
			// Display this element.
			if ( is_object( $args[0] ) ) {
				$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] ); }
			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

		/**
		 * Menu Fallback
		 * =============
		 * If this function is assigned to the wp_nav_menu's fallback_cb variable
		 * and a menu has not been assigned to the theme location in the WordPress
		 * menu manager the function with display nothing to a non-logged in user,
		 * and will add a link to the WordPress menu manager if logged in as an admin.
		 *
		 * @param array $args passed from the wp_nav_menu function.
		 */
		// public static function fallback( $args ) {
		// 	if ( current_user_can( 'edit_theme_options' ) ) {

		// 		/* Get Arguments. */
		// 		$container = $args['container'];
		// 		$container_id = $args['container_id'];
		// 		$container_class = $args['container_class'];
		// 		$menu_class = $args['menu_class'];
		// 		$menu_id = $args['menu_id'];

		// 		// initialize var to store fallback html.
		// 		$fallback_output = '';

		// 		if ( $container ) {
		// 			$fallback_output = '<' . esc_attr( $container );
		// 			if ( $container_id ) {
		// 				$fallback_output = ' id="' . esc_attr( $container_id ) . '"';
		// 			}
		// 			if ( $container_class ) {
		// 				$fallback_output = ' class="' . sanitize_html_class( $container_class ) . '"';
		// 			}
		// 			$fallback_output = '>';
		// 		}
		// 		$fallback_output = '<ul';
		// 		if ( $menu_id ) {
		// 			$fallback_output = ' id="' . esc_attr( $menu_id ) . '"'; }
		// 		if ( $menu_class ) {
		// 			$fallback_output = ' class="' . esc_attr( $menu_class ) . '"'; }
		// 		$fallback_output = '>';
		// 		$fallback_output = '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" title="">' . esc_attr( 'Add a menu', '' ) . '</a></li>';
		// 		$fallback_output = '</ul>';
		// 		if ( $container ) {
		// 			$fallback_output = '</' . esc_attr( $container ) . '>';
		// 		}

		// 		// if $args has 'echo' key and it's true echo, otherwise return.
		// 		if ( array_key_exists( 'echo', $args ) && $args['echo'] ) {
		// 			echo $fallback_output; // WPCS: XSS OK.
		// 		} else {
		// 			return $fallback_output;
		// 		}
		// 	} // End if().
		// }
	}
} // End if().
