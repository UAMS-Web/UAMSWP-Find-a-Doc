<?php

/* Ajax Search Pro Functions */
// Sort by last name, first name, middle name
add_action( 'pre_get_posts', 'cd_sort_physicians' );
function cd_sort_physicians( $query ) {
    if ( $query->is_main_query() && !is_admin() ) {
        if ( $query->is_tax() || $query->is_post_type_archive('physicians') ) {
	        $query->set('meta_query', array(
                'physician_last_name' => array(
                    'key' => get_field('physician_last_name'),
                ),
                'physician_first_name' => array(
                    'key' => get_field('physician_first_name'),
                ),
                'physician_middle_name' => array(
                    'key' => get_field('physician_middle_name'),
                )
            ));
            $query->set('orderby',array(
                'physician_last_name' => 'ASC',
                'physician_first_name' => 'ASC',
                'physician_middle_name' => 'ASC'
            ));
        }
    }
}

// Ajax Search Pro modifications
add_filter( 'asp_results', 'asp_custom_link_meta_results', 1, 2 );
function asp_custom_link_meta_results( $results, $id ) {

  // Change this variable to whatever meta key you are using
  //$key = 'profile_type';

  // Parse through each result item
    if ($id == '1') {
	  // Parse through each result item
	  $new_url = '';
	  $full_name = '';
	  $new_desc = '';
	  foreach ($results as $k=>$v) {
		  if (($v->content_type == "pagepost") && (get_post_type($v->id) == "physicians")) {
		  		// $new_url = '/directory/physician/' . get_post_field( 'post_name', $v->id ) .'/';
		  		$full_name = get_field('physician_first_name', '', $v->id) .' ' .(get_field('physician_middle_name', '', $v->id) ? get_field('physician_middle_name', '', $v->id) . ' ' : '') . get_field('physician_last_name', '', $v->id) . (get_field('physician_degree', '', $v->id) ? ', ' . get_field('physician_degree', '', $v->id) : '');
		  		$new_desc = get_field('physician_short_clinical_bio', '', $v->id );
		  }
		  // Change only, if the meta is specified
		  // if ( !empty($new_url) )
	   //    		$results[$k]->link  = $new_url;
	      if ( !empty($full_name) )
      			$results[$k]->title  = $full_name;
		  if ( !empty($new_desc) )
	      		$results[$k]->content  = $new_desc;

	      //$new_url = ''; //Reset
	      $full_name = ''; //Reset
	      $new_desc = ''; //Reset
	  }
	  return $results;
	}
}

add_filter( 'asp_result_image_after_prostproc', 'asp_get_post_type_image', 1, 2 );

function asp_get_post_type_image( $image, $id ) {

   if ( empty($image) ) {
       $type = get_post_type( $id );

       switch ($type) {
           case "physicians":
               $image = "/wp-content/uploads/2018/12/image01299.png";
               break;
           case "locations":
               $image = "/wp-content/uploads/2019/01/pin.png";
               break;
           default:
               $image = get_stylesheet_directory_uri() ."/assets/admin-icons/services-icon.png";
               break;
       }
   }

    return $image;
}


// pubmed finder
new pubmed_field_on_change();

class pubmed_field_on_change {

	public function __construct() {
		// enqueue js extension for acf
		// do this when ACF in enqueuing scripts
		//add_action('rwmb_enqueue_scripts', array($this, 'enqueue_script'));
		// ajax action for loading values
		add_action('wp_ajax_load_content_from_pubmed', array($this, 'load_content_from_pubmed'));
	} // end public function __construct

	public function load_content_from_pubmed() {
		// this is the ajax function that gets the related values and returns them

		// check for our other required values
		if (!isset($_POST['pmid'])) {
			echo json_encode(false);
			exit;
		}

		$idstr = intval($_POST['pmid']); //'22703441';

		// make json api call
		$host = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&retmode=json&id={IDS}';
		// insert the pmid
		$url = str_replace( '{IDS}', $idstr, $host );
		// get json data
		$request = wp_remote_get( $url );

		$json = wp_remote_retrieve_body( $request );
		if( is_wp_error( $request ) ) {
			return false; // Bail early
		}

		// make the data readable
		$obj = json_decode($json);

			// Get values
			$result = $obj -> result -> $idstr;
			$title = $result->title;
			// create authors array, just in case
			$authors = [];
			$authorlist = '';
			$last_author = end(array_keys($result->authors));
			foreach ($result->authors as $author) {
				$name = $author->name;
				array_push($authors, $name);
				$authorlist .= $name;
				if (next($result->authors)===FALSE) {
					$authorlist .= '.';
				} else {
					$authorlist .= ', ';
				}
			}
			$journal = $result->fulljournalname;
			$source = $result->source;
			$volume = $result->volume;
			$issue = $result->issue;
			$pages = $result->pages;
			$date = $result->pubdate;
			$doi = $result->elocationid;

			// create full reference
			if ($title != '') {
				$full = $authorlist . ' ' . $title . ' ' . $source . '. ' . $date . '; ';
				$full .= $volume . '('. $issue .'):' . $pages . '. ' . $doi . ' PMID: ' . $idstr . '. <br/> View in Pubmed: <a href="https://www.ncbi.nlm.nih.gov/pubmed/' . $idstr . '" target="_blank">' . $idstr . '</a>';
			} else {
				$full = '';
			}

			// put all the values into an array and return it as json
			$array = array(
			  'full' => $full,
			  'title' => $title,
			  'authors' => $authors,
			  'url' => $url,
			  'id' => $result->uid,
			  );
			echo json_encode($array);
			exit;
		//}


	} // end public function load_content_from_relationship

} // end class my_dynmamic_field_on_relationship

// Pubmed API shortcode
// Example: [pubmed terms="Chernoff%20R%5BAuthor%5D" count="10"]
function pubmed_register() {
    global $post_type;
	if ( !is_admin() ) {
		wp_register_script( 'pubmed-api', UAMS_FAD_ROOT_URL . 'assets/js/pubmed-api-async.js', array('jquery'), null, true );
    }
    if ( (is_single() && ('locations' == $post_type)) || is_singular( 'physicians' ) ) {
        wp_enqueue_style( 'leaflet-css', UAMS_FAD_ROOT_URL . 'assets/leaflet/leaflet.css', array(), '1.1', 'all');
        wp_enqueue_script( 'leaflet-js', UAMS_FAD_ROOT_URL . 'assets/leaflet/leaflet-bing.js', array(), null, false );
	}
	wp_enqueue_style( 'fad-css', UAMS_FAD_ROOT_URL . 'assets/css/style.css', array(), '1.0', 'all');
}
add_action( 'wp_enqueue_scripts', 'pubmed_register' );
function uams_pubmed_shortcode( $atts ) {

	/* call the javascript to support the api */
	wp_enqueue_script( 'pubmed-api' );

	$atts = shortcode_atts( array(
		'terms' => '',
		'count' => '20',
	), $atts, 'pubmed' );
	return "<ul class=\"pubmed-list\" data-terms=\"{$atts['terms']}\" data-count=\"{$atts['count']}\"></ul>";
}
add_shortcode( 'pubmed', 'uams_pubmed_shortcode' );

add_action( 'rwmb_enqueue_scripts', 'prefix_enqueue_custom_style' );
function prefix_enqueue_custom_style() {
    wp_enqueue_style( 'admin-mb-style', get_stylesheet_directory_uri() . '/admin.css' );
}

// add_action( 'mb_relationships_init', function() {
//     MB_Relationships_API::register( array(
//         'id'   => 'physicians_to_locations',
//         'from' => array(
//             'object_type' => 'post',
//             'post_type'   => 'physicians',
//             'admin_column' => 'after title',
//             'meta_box'    => array(
//                 'title'       => 'Location(s)',
//                 'field_title' => 'Select Location(s)<br/><span style="font-weight:normal; font-style:italic;">Set Primary Location First</span>',
//                 'context' => 'normal',
//                 'priority' => 'high',
//             ),
//         ),
//         'to'   => array(
//             'object_type' => 'post',
//             'post_type'   => 'locations',
//             'admin_column' => true,
//             'meta_box'    => array(
//                 'hidden'	=> true,
//                 'title'       => 'Physician(s)',
//             ),
//         ),
//     ),
//     array (
// 		'id'	=> 'services_to_locations',
// 		'from'	=> array(
//             'object_type' => 'post',
//             'post_type'   => 'services',
//             'meta_box'    => array(
//                 'title'       => 'Location(s)',
//                 'field_title' => 'Select Location',
//                 'context' => 'normal',
//                 'priority' => 'high',
//             ),
//         ),
//         'to'   => array(
//             'object_type' => 'post',
//             'post_type'   => 'locations',
//             'meta_box'    => array(
//                 'hidden'	=> true,
//                 'title'       => 'Service(s)',
//             ),
// 		),
//     ) );
// } );

/* FacetWP functions */
// factwp Main Query fix
// add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
//     if ( '' !== $query->get( 'facetwp' ) ) {
//         $is_main_query = (bool) $query->get( 'facetwp' );
//     }
//     return $is_main_query;
// }, 10, 2 );

// Filter to fix facetwp hash error
add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
    if ( 'physicians' == $query->get( 'post_type' ) ) {
		$is_main_query = false;
    }
    return $is_main_query;
}, 10, 2 );

add_filter( 'facetwp_shortcode_html', function( $output, $atts) {
	if ( $atts['template'] = 'physician' ) { // replace 'example' with name of your template
    /** modify replacement as needed, make sure you keep the facetwp-template class **/
    $output = str_replace( 'facetwp-template', 'facetwp-template row list', $output );
	}
	return $output; 
}, 10, 2 );

function fwp_disable_auto_refresh() {
	?>
	<script>
	(function($) {
		$(function() {
			if ('undefined' !== typeof FWP) {
				FWP.auto_refresh = false;
			}
		});
	})(jQuery);
	</script>
	<?php
	}
	add_action( 'wp_footer', 'fwp_disable_auto_refresh', 100 );

// FacetWP scripts
function fwp_facet_scripts() {
	if ( is_post_type_archive( 'physicians' ) || is_post_type_archive( 'locations' ) ) {
?>
<script>
(function($) {
    $(document).on('facetwp-loaded', function() {
        $('.facetwp-facet').each(function() {
            var facet_name = $(this).attr('data-name');
            var facet_label = FWP.settings.labels[facet_name];
            if ($('.facet-label[data-for="' + facet_name + '"]').length < 1) {
                $(this).before('<h4 class="facet-label" data-for="' + facet_name + '">' + facet_label + '</h4>');
            }
        });
    });
    $(document).on('facetwp-loaded', function() {
        $.each(FWP.settings.num_choices, function(key, val) {
            var $parent = $('.facetwp-facet-' + key).closest('.fwp-filter');
            (0 === val) ? $parent.hide() : $parent.show();
        });
        if ($('body').hasClass('tax-specialty')) {
        	if (! FWP.loaded) {
	        	$('.facetwp-facet-specialty_checkbox .facetwp-checkbox[data-value="<?php echo get_queried_object()->slug; ?>"]').click();
				$('.specialty-filter').hide();
			}
        }
        if ($('body').hasClass('tax-medical_terms')) {
        	if (! FWP.loaded) {
	        	$('.facetwp-facet-terms_checkbox .facetwp-checkbox[data-value="<?php echo get_queried_object()->slug; ?>"]').click();
				$('.terms-filter').hide();
			}
        }
        if ($('body').hasClass('tax-medical_procedures')) {
        	if (! FWP.loaded) {
	        	$('.facetwp-facet-procedures_checkbox .facetwp-checkbox[data-value="<?php echo get_queried_object()->slug; ?>"]').click();
				$('.procedures-filter').hide();
			}
        }
        if ($('body').hasClass('tax-condition')) {
        	if (! FWP.loaded) {
	        	$('.facetwp-facet-condition_checkbox .facetwp-checkbox[data-value="<?php echo get_queried_object()->slug; ?>"]').click();
				$('.condition-filter').hide();
			}
        }
    });
    $(document).on('facetwp-refresh', function() {
        if (! FWP.loaded) {
            //FWP.set_hash = function() { /* empty function */ }
            if ($('body').hasClass('tax-specialty')) {
            	FWP.set_hash = function() { /* empty function */ } // Exclude hash function
            	$('.specialty-filter').hide();
            }
            if ($('body').hasClass('tax-medical_terms')) {
            	FWP.set_hash = function() { /* empty function */ } // Exclude hash function
            	$('.terms-filter').hide();
            }
            if ($('body').hasClass('tax-medical_procedures')) {
            	FWP.set_hash = function() { /* empty function */ } // Exclude hash function
            	$('.procedures-filter').hide();
            }
            if ($('body').hasClass('tax-condition')) {
            	FWP.set_hash = function() { /* empty function */ } // Exclude hash function
            	$('.condition-filter').hide();
            }
        }
    });
	$(function() {
        FWP.hooks.addAction('facetwp/refresh/alpha', function($this, facet_name) {
            FWP.facets[facet_name] = $this.find('.facetwp-alpha.selected').attr('data-id') || '';
        });
    });

    $(document).on('click', '.facetwp-alpha.available', function() {
        $parent = $(this).closest('.facetwp-facet');
        $parent.find('.facetwp-alpha').removeClass('selected');
        var facet_name = $parent.attr('data-name');
        $(this).addClass('selected');

        if ('' !== $(this).attr('data-id')) {
            FWP.frozen_facets[facet_name] = 'soft';
        }
        // FWP.refresh();
    });
})(jQuery);
</script>
<?php
	}
}
add_action( 'wp_footer', 'fwp_facet_scripts', 100 );

// Adapted from https://gist.github.com/mgibbs189/f2469009a7039159e229efe5a01dab23
// Add Load more and Load All buttons
function fwp_load_more() {
?>
<script>
(function($) {
    $(function() {
        if ('object' != typeof FWP) {
            return;
        }
        FWP.hooks.addFilter('facetwp/template_html', function(resp, params) {
            if (FWP.is_load_more) {
                FWP.is_load_more = false;
                $('.facetwp-template').append(params.html);
                return true;
            }
            return resp;
        });
        $(document).on('click', '.fwp-load-more', function() {
            $('.fwp-load-more').html('Loading more');
            $('.fwp-load-more').after('<span class="fwp-loader"></span>');
            FWP.is_load_more = true;
            FWP.paged = parseInt(FWP.settings.pager.page) + 1;
            FWP.soft_refresh = true;
            FWP.refresh();
        });
        $(document).on('click', '.fwp-load-all', function() {
            $('.fwp-load-all').html('Loading all');
            $('.fwp-load-all').after('<span class="fwp-loader"></span>');
            FWP.soft_refresh = true;
            FWP.extras.per_page = 500
            FWP.refresh();
		});
		$(document).on('click', '.fwp_paged', function() {
			FWP.fetch_data();
		});
        // $(document).on('facetwp-loaded', function() {
        //     $('.fwp-loader').hide();
        //     if (FWP.settings.pager.page < FWP.settings.pager.total_pages) {
        //         if (! FWP.loaded && 1 > $('.fwp-load-more').length) {
        //             $('.facetwp-template').after('<div class="facetwp__loader"><button class="fwp-load-more btn btn-primary">Show more</button><button class="fwp-load-all btn btn-primary">Show all</button></div>');
        //         }
        //         else {
        //             $('.fwp-load-more').html('Show more').show();
        //         }
        //     }
        //     else {
        //         $('.fwp-load-more').hide();
        //         $('.fwp-load-all').hide();
        //     }
        // });
    });
})(jQuery);
</script>
<?php
}
// add_action( 'wp_head', 'fwp_load_more', 99 );

// FacetWP Sort
add_filter( 'facetwp_sort_options', function( $options, $params ) {
	if ( is_post_type_archive( 'physicians' ) || is_singular( 'physicians' ) ) {
		$params = array(
		    'template_name' => 'physicians',
		);
	    $options['name_asc'] = array(
	        'label' => __( 'Name (A-Z)', 'fwp' ),
	        'query_args' => array(
	            'orderby' => 'meta_value',
				'meta_key' => 'physician_full_name',
				'order' => 'ASC',
	        )
	    );
	    $options['name_desc'] = array(
	        'label' => __( 'Name (Z-A)', 'fwp' ),
	        'query_args' => array(
	            'orderby' => 'meta_value',
				'meta_key' => 'physician_full_name',
	            'order' => 'DESC',
	        )
	    );
	    unset( $options['title_asc'] );
     	unset( $options['title_desc'] );
	 } elseif ( is_post_type_archive( 'locations' ) || is_singular( 'locations' ) ) {
	 	$params = array(
		    'template_name' => 'locations',
		);
	 }
    //);
     unset( $options['date_desc'] );
     unset( $options['date_asc'] );
    return $options;
}, 10, 2 );

//FacetWP Count
// add_filter( 'facetwp_result_count', function( $output, $params ) {
// 	if ( is_post_type_archive( 'physicians' ) || is_singular( 'physicians' ) ) {
//     	$output = $params['total'] . ( $params['total'] > 1 ? ' Doctors' : ' Doctor' );
//     } elseif ( in_array( get_post_type(), array( 'locations' )) ) {//is_post_type_archive( 'locations' ) || is_singular( 'locations' ) ) {
//     	$output = $params['total'] . ( $params['total'] > 1 ? ' Locations' : ' Location' );
//     } else {
//     	$output = $params['total'];
//     }
//     return $output;
// }, 10, 2 );

add_filter( 'facetwp_pager_html', function( $output, $params ) {
    $output = '';
    $page = $params['page'];
    $total_pages = $params['total_pages'];

    if ( 1 < $total_pages ) {

		$output .= '<nav aria-label="list pagination"><ul class="pagination">';

        // Previous page (NEW)
        if ( $page > 1 ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page - 1) . '"><span class="fas fa-fast-backward" aria-hidden="true"></span></a></li>';
        }
        
        if ( 3 < $page ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link first-page" data-page="1"><span class="fas fa-angle-left" aria-hidden="true"></span></a></li>';
        }
        if ( 1 < ( $page - 10 ) ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page - 10) . '">' . ($page - 10) . '</a></li>';
        }
        for ( $i = 2; $i > 0; $i-- ) {
            if ( 0 < ( $page - $i ) ) {
                $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page - $i) . '">' . ($page - $i) . '</a></li>';
            }
        }

        // Current page
        $output .= '<li class="page-item"><a class="facetwp-page page-link active" data-page="' . $page . '">' . $page . '</a></li>';

        for ( $i = 1; $i <= 2; $i++ ) {
            if ( $total_pages >= ( $page + $i ) ) {
                $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page + $i) . '">' . ($page + $i) . '</a></li>';
            }
        }
        if ( $total_pages > ( $page + 10 ) ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page + 10) . '">' . ($page + 10) . '</a></li>';
        }
        if ( $total_pages > ( $page + 2 ) ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link last-page" data-page="' . $total_pages . '"><span class="fas fa-angle-left aria-hidden="true"></span></a></li>';
        }

        // Next page (NEW)
        if ( $page < $total_pages ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page + 1) . '"><span class="fas fa-fast-forward" aria-hidden="true"></span></a>';
		}
		
		$output .= '</ul></nav>';

    }

    return $output;
}, 10, 2 );

add_filter( 'facetwp_per_page_options', function( $options ) {
    return array( 1, 2, 25, 50, 100, 250 );
});

//FacetWP Active
// add_filter( 'facetwp_preload_url_vars', function( $url_vars ) {
//     if ( 'physicians' == FWP()->helper->get_uri() ) {
//         if ( empty( $url_vars['active'] ) ) {
//             $url_vars['active'] = array( '1' );
//         }
//     }
//     return $url_vars;
// } );

// add_filter( 'rwmb_outside_conditions', function( $conditions ){
//     $conditions['#services_to_locations_relationships_to'] = array(
// 		'hidden' => array(
// 			array( 'post_type', 'services' ),
// 			array( 'parent_id', '!=', '' )
// 		)
//     );
//     return $conditions;
// } );

// Admin Columns
add_filter('manage_physicians_posts_columns', 'posts_physicians_columns', 10);
add_action('manage_physicians_posts_custom_column', 'posts_physicians_custom_columns', 10, 2);

function posts_physicians_columns($columns){
    $custom_columns = array();
    $title = 'title';
    foreach($columns as $key => $value) {
        if ($key==$title){
            $custom_columns['physician_post_thumbs'] = __('Headshot');   // Move before title column
        }
          $custom_columns[$key] = $value;
      }

    return $custom_columns;
}

function posts_physicians_custom_columns($column_name, $id){
    if($column_name === 'physician_post_thumbs'){
        echo get_the_post_thumbnail( $id, array( 80, 80) );
    }
}

// NRC JSON API Call
function wp_nrc_cached_api( $npi ) {
	// Namespace in case of collision, since transients don't support groups like object caching.
	$url = 'https://transparency.nrchealth.com/widget/api/org-profile/uams/npi/' . $npi . '/6';
	$cache_key = 'nrc_' . $npi;
	$request = get_transient( $cache_key );

	if ( false === $request ) {
		$request = wp_remote_retrieve_body( wp_remote_get( $url ) );

		if ( is_wp_error( $request ) ) {
			// Cache failures for a short time, will speed up page rendering in the event of remote failure.
			set_transient( $cache_key, $request, MINUTE_IN_SECONDS * 15 );
		} else {
			// Success, cache for a longer time.
			set_transient( $cache_key, $request, DAY_IN_SECONDS );
		}
	}
	return $request;
}

//$request = wp_nrc_cached_api(  );

/*
if ( is_wp_error( $request ) ) {
	return false;
}

$body = wp_remote_retrieve_body( $request );
$data = json_decode( $body );

if ( ! empty( $data ) ) {
	foreach ( $data as $object ) {
		echo '<p>' . wp_kses_post( $object->introduction ) . '</p>';
	}
}

$request = wp_remote_get( 'https://transparency.nrchealth.com/widget/api/org-profile/uams/npi/' . get_field( 'physician_npi' ) . '/0' );

					if( is_wp_error( $request ) ) {
						return false; // Bail early
					}

					$body = wp_remote_retrieve_body( $request );

					$data = json_decode( $body );
*/

function my_acf_init() {
	acf_update_setting('google_api_key', '****Key goes here****');
}
add_action('acf/init', 'my_acf_init');