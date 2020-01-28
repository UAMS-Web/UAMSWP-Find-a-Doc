<?php

// AP Style for Dates
if ( !function_exists('apStyleDate') ) {
	function apStyleDate($date){

		$date = strftime("%l:%M %P", strtotime($date));
	
		$date = str_replace(":00", "", $date);
		$date = str_replace("m", ".m.", $date);
	
		return $date;
	
	}
}

// Partition / Split Col function
if ( !function_exists('partition') ) {
    function partition( $list, $p ) {
        $listlen = count( $list );
        $partlen = floor( $listlen / $p );
        $partrem = $listlen % $p;
        $partition = array();
        $mark = 0;
        for ($px = 0; $px < $p; $px++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice( $list, $mark, $incr );
            $mark += $incr;
        }
        return $partition;
    }
}

/**
 * Return anchor link for a single breadcrumb.
 *
 * @param string      $url     URL for href attribute.
 * @param string      $content Linked content.
 * @param bool|string $sep     Optional. Separator. Default is empty string.
 *
 * @return string HTML markup for anchor link and optional separator.
 */
function uamswp_get_breadcrumb_link( $url, $content, $sep = '' ) {

  $itemprop_item = genesis_html5() ? ' itemprop="item"' : '';
  $itemprop_name = genesis_html5() ? ' itemprop="name"' : '';

  $link = sprintf(
    '<a href="%s"%s><span%s>%s</span></a>',
    esc_attr( $url ),
    $itemprop_item,
    $itemprop_name,
    $content
  );

  if ( genesis_html5() ) {
    $link = sprintf(
      '<span %s>',
      genesis_attr( 'breadcrumb-link-wrap' )
    ) . $link . '</span>';
  }

  if ( $sep ) {
    $link .= $sep;
  }

  return $link;

}

/**
 * Filter the Genesis CPT breadcrumb.
 *
 * @since 2.5.0
 *
 * @param string $crumb HTML markup for the CPT breadcrumb.
 * @param array  $args  Arguments used to generate the breadcrumbs. Documented in Genesis_Breadcrumbs::get_output().
 */
function uamswp_cpt_breadcrumb( $crumb, $args ) {
    global $wp_query;
  
    if ( !is_singular( 'expertise' ) ) {
      return $crumb;
    }
  
    $post = $wp_query->get_queried_object();

    $crumb = $crumb = '<a href="'. get_post_type_archive_link( 'expertise' ) .'">'. get_post_type_object( 'expertise' )->labels->name .'</a>' . $args['sep'];
  
    // If this is a top level Page, it's simple to output the breadcrumb.
    if ( ! $post->post_parent ) {
      $crumb .= get_the_title();
    } else {
      // Get the IDs of all parents and ancestors in an array.
      $ancestors = get_post_ancestors( $post->ID );
  
      // Add ancestor breacrumb links to $crumbs array
      // get_breadcrumb_link() https://gist.github.com/natenault/af861546ab8a3468d12a09e89437ed54
      $crumbs = array();
      foreach ( $ancestors as $ancestor ) {
        array_unshift(
          $crumbs,
          uamswp_get_breadcrumb_link( get_permalink( $ancestor ), get_the_title( $ancestor ) )
        );
      }
  
      // Add the current page title.
      $crumbs[] = get_the_title( $post->ID );
        
      $crumb .= implode( $args['sep'], $crumbs );
    }
  
    return $crumb;
  }
  add_filter( 'genesis_cpt_crumb', 'uamswp_cpt_breadcrumb', 10, 2 );

  /**
 * Pass in a taxonomy value that is supported by WP's `get_taxonomy`
 * and you will get back the url to the archive view.
 * @param $taxonomy string|int
 * @return string
 */
function get_taxonomy_archive_link( $taxonomy ) {
  $tax = get_taxonomy( $taxonomy ) ;
  return '/' . $tax->rewrite['slug'];
}

  function uamswp_add_blog_crumb( $crumb, $args ) {
    if ( is_singular( 'condition' ) || is_singular( 'treatment' ) || is_tax( 'condition' ) || is_tax( 'treatment' ) )
      return '<a href="' . get_taxonomy_archive_link( get_queried_object()->taxonomy ) . '/">' . get_taxonomy( get_queried_object()->taxonomy )->labels->name .'</a> ' . $args['sep'] . ' ' . $crumb;
    else
      return $crumb;
  }
  add_filter( 'genesis_single_crumb', 'uamswp_add_blog_crumb', 10, 2 );
  add_filter( 'genesis_archive_crumb', 'uamswp_add_blog_crumb', 10, 2 );