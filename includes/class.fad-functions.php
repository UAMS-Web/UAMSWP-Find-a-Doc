<?php
// Ajax Search Pro modifications
add_filter( 'asp_results', 'asp_custom_link_meta_results', 1, 2 );
function asp_custom_link_meta_results( $results ) {

  // Change this variable to whatever meta key you are using
  //$key = 'profile_type';

  // Parse through each result item
    // if ($id == '1') {
	  // Parse through each result item
	//   $new_url = '';
	  $full_name = '';
	  $new_desc = '';
	  foreach ($results as $k=>$v) {
		  if (($v->content_type == "pagepost") && (get_post_type($v->id) == "physicians")) {
                $degrees = get_field('physician_degree', $v->id);
                $degree_list = '';
                $i = 1;
                if ($degrees){
                    foreach( $degrees as $degree ):
                        $degree_name = get_term( $degree, 'degree');
                        $degree_list .= $degree_name->name;
                        if( count($degrees) > $i ) {
                            $degree_list .= ", ";
                        }
                        $i++;
                    endforeach;
                }
		  		// $new_url = '/directory/physician/' . get_post_field( 'post_name', $v->id ) .'/';
		  		$full_name = get_field('physician_first_name', $v->id) .' ' .(get_field('physician_middle_name', $v->id) ? get_field('physician_middle_name', $v->id) . ' ' : '') . get_field('physician_last_name', $v->id) . ( $degree_list ? ', ' . $degree_list : '' );
		  		$new_desc = get_field('physician_short_clinical_bio', $v->id );
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
	// }
}

// add_filter( 'asp_result_image_after_prostproc', 'asp_get_post_type_image', 1, 2 );

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
    if ( (is_single() && ('locations' == $post_type)) ) {
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
    // if ( 'physicians' == $query->get( 'post_type' ) ) {
		$is_main_query = false;
    // }
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
    if ( is_post_type_archive( 'physicians' ) ) {
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
}
add_action( 'wp_footer', 'fwp_disable_auto_refresh', 100 );

add_action( 'wp_footer', function() {
    if ( !is_post_type_archive( 'physicians' ) || !is_post_type_archive( 'locations' ) ) {
    ?>
        <script>
        (function($) {
            $(document).on('facetwp-refresh', function() {
            if (FWP.loaded) {
                FWP.set_hash();
                window.location.reload();
                return false;
            }
            });
        })(jQuery);
        </script>
    <?php
    }
}, 10 );

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

        // First Page
        if ( 3 < $page ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link first-page" data-page="1"><span class="fas fa-fast-backward" aria-hidden="true"></span></a></li>';
        }
        
        // Previous page (NEW)
        if ( $page > 1 ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page - 1) . '"><span class="fas fa-angle-left" aria-hidden="true"></span></a></li>';
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

        // Next page (NEW)
        if ( $page < $total_pages ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page + 1) . '"><span class="fas fa-angle-right" aria-hidden="true"></span></a>';
        }
        
        // Last Page
        if ( $total_pages > ( $page + 2 ) ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link last-page" data-page="' . $total_pages . '"><span class="fas fa-fast-forward aria-hidden="true"></span></a></li>';
        }
		
		$output .= '</ul></nav>';

    }

    return $output;
}, 10, 2 );

add_filter( 'facetwp_per_page_options', function( $options ) {
    return array( 1, 2, 25, 50, 100, 250 );
});

add_filter( 'facetwp_shortcode_html', function( $output, $atts ) {
    if ( $atts['template'] = 'locations' ) {
        $output = str_replace( 'facetwp-template row', 'facetwp-template row card-list', $output );
    }
    return $output;
}, 10, 2 );

// Show only Yes values
add_filter( 'facetwp_index_row', function( $params, $class ) {
    if ( 'primary_care' == $params['facet_name'] ) {
        $included_terms = array( 'Yes' );
        if ( ! in_array( $params['facet_display_value'], $included_terms ) ) {
            return false;
        }
    }
    // if ( 'searchable' == $params['facet_name'] ) {
    //     $included_terms = array( 'Yes' );
    //     if ( ! in_array( $params['facet_display_value'], $included_terms ) ) {
    //         return false;
    //     }
    // }
    return $params;
}, 10, 2 );

add_filter( 'facetwp_preload_url_vars', function( $url_vars ) {
    if ( 'physicians' == FWP()->helper->get_uri() ) {
        if ( empty( $url_vars['searchable'] ) ) {
            $url_vars['searchable'] = array( '1' );
        }
    }
    return $url_vars;
} );

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

add_filter('acf/load_value/key=field_physician_languages', 'set_default_language', 20, 3);
function set_default_language($value, $post_id, $field) {
	$term = get_term_by('slug', 'english', 'languages');
	$id = $term->term_id;
    $value = array($id);
  	return $value;
}

// ACF Custom Tables
/*
 * Changes the ACF Custom Database Tables JSON directory.
 * This needs to run before the 'plugins_loaded' action hook, so 
 * you need to put this in a plugin or in your wp-config.php file.
 */
define( 'ACFCDT_JSON_DIR', WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) . '/assets/json/acf-tables' );
/*
 * Disables storing of meta data values in core meta tables where a custom 
 * database table has been defined for fields. Any fields that aren't mapped
 * to a custom database table will still be stored in the core meta tables. 
 */
add_filter( 'acfcdt/settings/store_acf_values_in_core_meta', '__return_false' );

/*
 * Disables storing of ACF field key references in core meta tables where a custom 
 * database table has been defined for fields. Any fields that aren't mapped to a 
 * custom database table will still have their key references stored in the core 
 * meta tables. 
 */
// add_filter( 'acfcdt/settings/store_acf_keys_in_core_meta', '__return_false' );

add_filter('acf/settings/load_json', 'uamswp_fad_json_load_point');

function uamswp_fad_json_load_point( $paths ) {
    
    // remove original path (optional)
    // unset($paths[0]);
    
    
    // append path
    $paths[] = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) . '/assets/json/acf-json';
    
    
    // return
    return $paths;
    
}
/* 
 * Remove for production 
 */
// // Convert php to json acf
// // get all the local field groups 
// $field_groups = acf_get_local_field_groups();

// // loop over each of the gield gruops 
// foreach( $field_groups as $field_group ) {

// 	// get the field group key 
// 	$key = $field_group['key'];

// 	// if this field group has fields 
// 	if( acf_have_local_fields( $key ) ) {
	
//       	// append the fields 
// 		$field_group['fields'] = acf_get_local_fields( $key );

// 	}

// 	// save the acf-json file to the acf-json dir by default 
// 	acf_write_json_field_group( $field_group );

// }
add_action( 'admin_init', 'uamswp_remove_genesis_term_meta', 11 ); // hook in after genesis adds the tax meta
function uamswp_remove_genesis_term_meta() {
 $taxonomies = array( 'condition', 'treatment_procedure' );
 foreach( $taxonomies as $taxonomy ) {
 remove_action( "{$taxonomy}_edit_form", 'genesis_taxonomy_archive_options', 10 );
 remove_action( "{$taxonomy}_edit_form", 'genesis_taxonomy_seo_options', 10 );
 remove_action( "{$taxonomy}_edit_form", 'genesis_taxonomy_layout_options', 10 );
//  add_action( "{$taxonomy}_edit_form", 'genesis_taxonomy_archive_options', 20, 2 );
//  add_action( "{$taxonomy}_edit_form", 'genesis_taxonomy_seo_options', 20, 2 );
//  add_action( "{$taxonomy}_edit_form", 'genesis_taxonomy_layout_options', 20, 2 );
 add_action( "{$taxonomy}_edit_form", 'remove_description_form');
 add_action( "{$taxonomy}_add_form", 'remove_description_form');
 }
}
function remove_description_form() {
    echo "<style> .term-description-wrap { display:none; } </style>";
}

add_filter('manage_edit-condition_columns', function ( $columns ) 
{
    if( isset( $columns['description'] ) )
        unset( $columns['description'] );   

    return $columns;
} );
add_filter('manage_edit-treatment_procedure_columns', function ( $columns ) 
{
    if( isset( $columns['description'] ) )
        unset( $columns['description'] );   

    return $columns;
} );