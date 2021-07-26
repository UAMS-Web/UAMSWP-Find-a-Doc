<?php
// Ajax Search Pro modifications
add_filter( 'asp_results', 'asp_custom_link_meta_results', 1, 2 );
function asp_custom_link_meta_results( $results ) {

  // Parse through each result item
	  $full_name = '';
	  $new_desc = '';
	  foreach ($results as $k=>$v) {
		  if (($v->content_type == "pagepost") && (get_post_type($v->id) == "provider")) {
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
// Enqueue for Admin
function uamswp_admin_scripts ( $hook ) {
     
    if( $hook == 'post.php' ) {
		wp_enqueue_script( 'acf-admin-js', UAMS_FAD_ROOT_URL . 'admin/js/acf-admin.js', array('jquery'), null, true );
		wp_enqueue_script( 'medline-acf-js', UAMS_FAD_ROOT_URL . 'admin/js/acf-medline.js', array('jquery'), null, true );
        // wp_enqueue_stylesheet( 'plugin-main-style', plugins_url( 'css/plugin-main.css', dirname( __FILE__) ) ); 
	}
	// if( $hook == 'term.php' || $hook == 'edit-tags.php') {
	// 	wp_enqueue_script( 'medline-acf-js', UAMS_FAD_ROOT_URL . 'admin/js/acf-medline.js', array('jquery'), null, true );
	// }
}   
add_action('admin_enqueue_scripts', 'uamswp_admin_scripts');

// pubmed finder
new pubmed_field_on_change();

class pubmed_field_on_change {

	public function __construct() {
		// enqueue js extension for acf
		// do this when ACF in enqueuing scripts
		add_action('acf/input/admin_enqueue_scripts', array($this, 'enqueue_script'));
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
			// $last_author = end(array_keys($result->authors)); // unused
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
			  'full' => htmlentities($full),
			  'title' => $title,
			  'authors' => $authors,
			  'url' => $url,
			  'id' => $result->uid,
			  );
			echo json_encode($array);
			exit;
		//}


    } // end public function load_content_from_relationship
    
    public function enqueue_script() {
		// enqueue acf extenstion

		// only enqueue the script on the post page where it needs to run
		/* *** THIS IS IMPORTANT
		       ACF uses the same scripts as well as the same field identification
		       markup (the data-key attribute) if the ACF field group editor
		       because of this, if you load and run your custom javascript on
		       the field group editor page it can have unintended side effects
		       on this page. It is important to always make sure you're only
		       loading scripts where you need them.
		*/


		// the handle should be changed to your own unique handle
		$handle = 'pubmed_field_on_change';

		// I'm using this method to set the src because
		// I don't know where this file will be located
		// you should alter this to use the correct functions
		// to get the theme, template or plugin path
		// to set the src value to point to the javascript file
		$src = UAMS_FAD_ROOT_URL . '/assets/js/acf-pubmed.js';
		// make this script dependent on acf-input
		$depends = array('acf-input');

		wp_register_script($handle, $src, $depends);

		wp_enqueue_script($handle);
	} // end public function enqueue_script

} // end class my_dynmamic_field_on_relationship

// Pubmed API shortcode
// Example: [pubmed terms="Chernoff%20R%5BAuthor%5D" count="10"]
function fad_script_register() {
    global $post_type;
	if ( !is_admin() ) {
		wp_register_script( 'pubmed-api', UAMS_FAD_ROOT_URL . 'assets/js/pubmed-api-async.js', array('jquery'), null, true );
    }
    if ( (is_single() && ('location' == $post_type)) ) {
        wp_enqueue_style( 'leaflet-css', UAMS_FAD_ROOT_URL . 'assets/leaflet/leaflet.css', array(), '1.1', 'all');
        wp_enqueue_script( 'leaflet-js', UAMS_FAD_ROOT_URL . 'assets/leaflet/leaflet-bing.js', array(), null, false );
    }
    if ( (is_archive() && (('provider' == $post_type) || ('location' == $post_type))) ) {
        wp_enqueue_script( 'mobile-filter-toggle', UAMS_FAD_ROOT_URL . 'assets/js/mobile-filter-toggle.js', array('jquery'), null, false );
    }
	wp_enqueue_style( 'fad-app-css', UAMS_FAD_ROOT_URL . 'assets/css/app.css', array(), '1.0', 'all');
	wp_enqueue_style( 'fad-css', UAMS_FAD_ROOT_URL . 'assets/css/style.css', array(), '1.0', 'all');
}
add_action( 'wp_enqueue_scripts', 'fad_script_register' );
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



// Admin Columns
add_filter('manage_provider_posts_columns', 'posts_provider_columns', 10);
add_action('manage_provider_posts_custom_column', 'posts_provider_custom_columns', 10, 2);

function posts_provider_columns($columns){
    $custom_columns = array();
    $title = 'title';
    foreach($columns as $key => $value) {
        if ($key==$title){
            $custom_columns['provider_post_thumbs'] = __('Headshot');   // Move before title column
        }
          $custom_columns[$key] = $value;
      }

    return $custom_columns;
}

function posts_provider_custom_columns($column_name, $id){
    if($column_name === 'provider_post_thumbs'){
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

function limit_to_post_parent( $args, $field, $post ) {

    $args['post_parent'] = 0;
    // $args['post_status'] = 'publish';
    $args['post__not_in'] = array( $post );

    return $args;
}


add_action( 'admin_init', 'uamswp_remove_genesis_term_meta', 11 ); // hook in after genesis adds the tax meta
function uamswp_remove_genesis_term_meta() {
 $taxonomies = array( 'condition', 'treatment', 'portal' );
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
add_filter('manage_edit-treatment_columns', function ( $columns ) 
{
    if( isset( $columns['description'] ) )
        unset( $columns['description'] );   

    return $columns;
} );

/* Relevanssi Functions */
add_filter('relevanssi_tax_term_additional_content', 'rlv_tax_excerpt_term_fields', 10, 2);
add_filter('relevanssi_pre_excerpt_content', 'rlv_tax_excerpt_term_fields', 10, 2);
function rlv_tax_excerpt_term_fields($content, $term) {
    if (!isset($term->term_id)) return $content;    // not a taxonomy term, skip
    if (isset($term->term_id) && !isset($term->taxonomy)) {
        // this is excerpt-building, where the taxonomy is in $term->post_type
        $term->taxonomy = $term->post_type;
    }
    $post_id = $term->taxonomy . "_" . $term->term_id;
    if( get_field('condition_alternate', $post_id) || get_field('condition_content', $post_id) ) {
        $content .= get_field('condition_alternate', $post_id);
        $content .= ' ' . get_field('condition_content', $post_id);
    } 
    if ( get_field('treatment_procedure_alternate', $post_id) || get_field('treatment_procedure_content', $post_id) ) {
        $content .= get_field('treatment_procedure_alternate', $post_id);
        $content .= ' ' . get_field('treatment_procedure_content', $post_id);
    }
    return $content;
}
// AJAX
function uamswp_ajax_scripts() { 
    if ( is_singular( 'location' ) || is_singular( 'expertise' ) || is_singular( 'condition' ) || is_singular( 'treatment' ) ) { // Only run on these template pages
        // Register the script
        wp_register_script( 'uamswp-loadmore', UAMS_FAD_ROOT_URL . 'assets/js/uamswp-loadmore.js', array('jquery'), false, true );
    
        // Localize the script with new data
        $script_data_array = array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'security' => wp_create_nonce( 'load_more_posts' )
        );
        wp_localize_script( 'uamswp-loadmore', 'uamswp_loadmore', $script_data_array );
    
        // Enqueued script with localized data.
        wp_enqueue_script( 'uamswp-loadmore' );
    }
}
add_action( 'wp_enqueue_scripts', 'uamswp_ajax_scripts' );

add_action('wp_ajax_load_posts_by_ajax', 'uamswp_load_by_ajax_callback');
add_action('wp_ajax_nopriv_load_posts_by_ajax', 'uamswp_load_by_ajax_callback');
function uamswp_load_by_ajax_callback(){
    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 6; // Set this default value
    $page = $_POST['page'];
    // $type = (isset($_POST["posttype"])) ? $_POST["posttype"] : 'post'; // Assume its post if not set
        
    header("Content-Type: text/html");
    // if ('post' == $type) {
        $ids = (isset($_POST["postid"])) ? $_POST["postid"] : '';
        $ids_array = explode(',', $ids);
        $args = array(
            // 'suppress_filters' => true,
            'post_type' => 'provider',
            'post_status' => 'publish',
            "orderby" => "title",
            "order" => "ASC",
            'posts_per_page' => $ppp,
            'paged'    => $page,
            'post__in' => $ids_array,
            
        );
    // } else { // Taxonomy
    //     $tax = (isset($_POST["tax"])) ? $_POST["tax"] : '';
    //     $slug = (isset($_POST["slug"])) ? $_POST["slug"] : '';
    //     $args = array(
    //         "post_type" => "provider",
    //         "post_status" => "publish",
    //         "posts_per_page" => $ppp,
    //         "orderby" => "title",
    //         "order" => "ASC",
    //         'paged'    => $page,
    //         "tax_query" => array(
    //             array(
    //             "taxonomy" => $tax,
    //             "field" => "slug",
    //             "terms" => $slug,
    //             "operator" => "IN"
    //             )
    //         )
    //     );
    // }
    $loop = new WP_Query($args);
    $out = '';
    if ($loop -> have_posts()) :  while ($loop -> have_posts()) : $loop -> the_post();
        $id = get_the_ID();
        $out .= include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' ); 
    endwhile;
    endif;
    wp_die();
}
function provider_recognition_function( $atts ) {
	extract(shortcode_atts(array(
		'slug' => '',
		'layout' => 'table',
	 ), $atts));

	query_posts(
		array(
			'post_type' => 'provider', // We only want pages
			'post_status' => 'publish', // We only want children of a defined post ID
			'posts_per_page' => -1, // We do not want to limit the post count
			'order' => 'ASC',
			'orderby' => 'title',
			'tax_query' => array(
				array(
					'taxonomy' => 'recognition',
					'field'    => 'slug',
					'terms'    => $slug,
				),
			),
		// We can define any additional arguments that we need - see Codex for the full list
		)
	);
	$recognition_list = '';
	if (have_posts()):
		if ('table' == $layout || empty($layout)) {
			$recognition_list .= '<div class="table-responsive">
			<table class="table table-striped">
			<thead>
			<tr>
				<th scope="col" class="col-6">Name</th>
				<th scope="col" class="col-6">Title</th>
			</tr>
			</thead>
			<tbody>';

			while( have_posts() ) : the_post();
				$degrees = get_field('physician_degree');
				$degree_list = '';
				$i = 1;
				if ( $degrees ) {
					foreach( $degrees as $degree ):
						$degree_name = get_term( $degree, 'degree');
						$degree_list .= $degree_name->name;
						if( count($degrees) > $i ) {
							$degree_list .= ", ";
						}
						$i++;
					endforeach;
				}
				$full_name = get_field('physician_first_name') .' ' .(get_field('physician_middle_name') ? get_field('physician_middle_name') . ' ' : '') . get_field('physician_last_name') . (get_field('physician_pedigree') ? '&nbsp;' . get_field('physician_pedigree') : '') .  ( $degree_list ? ', ' . $degree_list : '' );
				$recognition_list .= '<tr>';
				$recognition_list .= '<td><a href="'.get_permalink().'" title="'. $full_name .'">'. $full_name .'</a></td>';
				$phys_title = get_field('physician_title');
				if ($phys_title && !empty($phys_title)) {
					$recognition_list .= '<td>'. ($phys_title ? get_term( $phys_title, 'clinical_title' )->name : '&nbsp;') .'</td>';
				}
				$recognition_list .= '</tr>';

			endwhile;
			$recognition_list .= '</tbody>';
			$recognition_list .= '</table>';
			$recognition_list .= '</div>'; // responsive table
		} // table layout
		// Additional layouts
	endif;
	wp_reset_query();
	return $recognition_list;

}
function register_recognition_shortcodes(){
	add_shortcode('recognition-list', 'provider_recognition_function');
}
add_action( 'init', 'register_recognition_shortcodes');

function get_medline_api_data( $code, $type ) {
	// if ( 'none' == $type ) {
	// 	 //
	// }
	// Build the $id
	$id = 'medline-api-' . $type . '-' . $code; 

	// Get API data
	$transient = get_transient( $id );

	if (!empty($transient)) {

		return $transient;

	} else {

		$url = 'https://connect.medlineplus.gov/service?';

		if ('icd' == $type) {
			$arguments = array(
				'mainSearchCriteria.v.cs' => '2.16.840.1.113883.6.90',
				'knowledgeResponseType' => 'application/javascript',
				'mainSearchCriteria.v.c' => $code
			);
		} elseif ('ndc' == $type) {
			$arguments = array(
				'mainSearchCriteria.v.cs' => '2.16.840.1.113883.6.69',
				'knowledgeResponseType' => 'application%2Fjavascript',
				'mainSearchCriteria.v.c' => $code
			);
		} elseif ('lonic' == $type) {
			$arguments = array(
				'mainSearchCriteria.v.cs' => '2.16.840.1.113883.6.1',
				'knowledgeResponseType' => 'application%2Fjavascript',
				'mainSearchCriteria.v.c' => $code
			);
		}

		$url_parameters = array();
		foreach ($arguments as $key => $value){
			$url_parameters[] = $key.'='.$value;
		}
		$url = $url.implode('&', $url_parameters);

		// echo $url .'<br/>';

		$response = wp_remote_get( $url );


		$response = $response['body'];

		$response = str_replace('None(', '', $response);
		$response = str_replace('});', '}', $response);

		// var_dump( $response );
		try {
 
			// Note that we decode the body's response since it's the actual JSON feed
			$json = json_decode( $response );
	 
		} catch ( Exception $ex ) {
			$json = null;
		} // end try/catch
	 
		set_transient( $id, $json, DAY_IN_SECONDS );
		
		return $json;

	}
}
function display_medline_api_data( $code, $type ) {
	// Get data for api
	$json = get_medline_api_data( $code, $type );

	$feed = $json->feed;
	$entry = $feed->entry;
	//echo (count($entry->title));
	if (isset($entry->title)) {
		// echo ('<h2>'. $entry->title->_value .'</h2>');
		// echo '<br>';
		echo '<p>'. ($entry->summary->_value) .'</p>';
		echo '<div class="cite">';
		echo '<p><em>Courtesy of <a href="https://medlineplus.gov/">MedlinePlus</a> from the <a href="https://www.nlm.nih.gov/">National Library of Medicine</a>.</em></p>';
		echo '<p><strong>Syndicated Content Details:</strong><br />';
		echo 'Source URL: <a href="'. $entry->link->href .'">'. $entry->link->href .'</a><br />';
		echo 'Source Agency: <a href="https://www.nlm.nih.gov/">National Library of Medicine</a></p>';
		echo '</div>';
	} else {
		for($a=0;$a<count($entry);$a++) {
			if (strpos($entry[$a]->link->href, 'medlineplus.gov') !== false) {
				if ($a != 0) {
					echo ('<h2>'. $entry[$a]->title->_value .'</h2>'); // Add heading if there is more than one
				}
				echo '<p>'. ($entry[$a]->summary->_value) .'</p>';
				echo '<div class="cite">';
				echo '<p><em>Courtesy of <a href="https://medlineplus.gov/">MedlinePlus</a> from the <a href="https://www.nlm.nih.gov/">National Library of Medicine</a>.</em></p>';
				echo '<p><strong>Syndicated Content Details:</strong><br />';
				echo 'Source URL: <a href="'. $entry[$a]->link->href .'">'. $entry[$a]->link->href .'</a><br />';
				echo 'Source Agency: <a href="https://www.nlm.nih.gov/">National Library of Medicine</a></p>';
				echo '</div>';
			}
		}
	}
}

function provider_ajax_filter_scripts() {
	wp_enqueue_script( 'provider_ajax_filter', UAMS_FAD_ROOT_URL . 'assets/js/ajax-script.js', array(), '1.0', true );
    wp_localize_script( 'provider_ajax_filter', 'ajax_url', admin_url('admin-ajax.php') );
}

// Provider AJAX
function uamswp_provider_ajax_filter_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'providers' => '',
		'ppp' => ''
	), $atts);
	$providers = explode(",", $a['providers']);
	$ppp = $a['ppp'];
	$provider_titles = array();
	$provider_titles_list = array();
	$regions = array();
	foreach($providers as $provider) {
		if ( get_post_status ( $provider ) == 'publish' ) {
			// Clinical Title
			$provider_resident = get_field('physician_resident',$provider);
			$provider_resident_title_name = 'Resident Physician';
			$provider_phys_title = get_field('physician_title',$provider);
			if(!empty($provider_phys_title) || $provider_resident){
				$provider_phys_title_name = $provider_resident ? $provider_resident_title_name : get_term( $provider_phys_title, 'clinical_title' )->name;
				$provider_titles[$provider_phys_title] = $provider_phys_title_name;
			}
			// Region
			$provider_region = get_field('physician_region', $provider);
			$provider_regions[] = $provider_region;
		}
	}
	$provider_titles_list = array_unique($provider_titles);
	asort($provider_titles_list);
	$provider_regions_ids = array_unique($provider_regions);
	sort($provider_regions_ids);

	$provider_region = '';
	if( isset($_COOKIE['_filter_region']) ) {
		$provider_region = $_COOKIE['_filter_region'];
	}
	$provider_title = '';
	if( isset($_COOKIE['_provider_title']) ) {
		$provider_title = $_COOKIE['_provider_title'] ;
	}
	//provider_ajax_filter_scripts();

	ob_start(); ?>

	<div id="provider-ajax-filter">
        <form action="" method="get">
            <!-- <input type="text" name="search" id="search" value="" placeholder="Search Here.."> -->
            <div class="row">
                <div class="col-6">
                    <label for="region">Region</label>
                    <select name="region" id="region">
						<option value="">Any Region</option>
					<?php $regions = get_terms('region', 'orderby=name&hide_empty=0');
					foreach($regions as $region) : ?>
                        <option value="<?php echo $region->slug; ?>"<?php echo in_array($region->term_id, $provider_regions_ids) ? '' : ' disabled'; ?><?php echo ($region->slug === $provider_region) ? ' selected' : ''; ?>><?php echo $region->name; ?></option>
					<?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6">
                    <label for="title">Clinical Title</label>
                    <select name="title" id="title">
                        <option value="">Any Title</option>
						<?php foreach($provider_titles_list as $key => $title) : ?>
                        <option value="<?= $key; ?>"<?php echo ($key == $provider_title) ? ' selected' : ''; ?>><?= $title; ?></option>
					<?php endforeach; ?>
                    </select>
                </div>
            </div>
			<input type="hidden" id="providers" name="providers" value="<?php echo implode(",", $providers); ?>">
			<input type="hidden" id="ppp" name="ppp" value="<?php echo $ppp; ?>">
            <input type="submit" id="submit" name="submit" value="Search">
			<input type="button" id="clear" name="clear" value="Reset">
        </form>
    </div>

	<?php
	return ob_get_clean();
}
add_shortcode ('uamswp_provider_ajax_filter', 'uamswp_provider_ajax_filter_shortcode');

// Ajax Callback
add_action('wp_ajax_nopriv_provider_ajax_filter', 'provider_ajax_filter_callback'); 
add_action('wp_ajax_provider_ajax_filter', 'provider_ajax_filter_callback');
 
function provider_ajax_filter_callback() {
  
    $tax_query = array('relation' => 'AND');

	$provider_title = '';
	if( isset($_COOKIE['_provider_title']) ) {
		$provider_title = $_COOKIE['_provider_title'] ;
	} elseif(isset($_POST['title'])){
		$provider_title = sanitize_text_field( $_POST['title'] );
	}

	$provider_region = '';
	if( isset($_COOKIE['_filter_region']) ) {
		$provider_region = $_COOKIE['_filter_region'];
	} elseif(isset($_POST['region'])){
		$provider_region = sanitize_text_field( $_POST['region'] );
	}
 
    if(!empty($provider_title) ) {
        $clinical_title = $provider_title ;
        $tax_query[] = array(
            'taxonomy' => 'clinical_title',
			'field' => 'term_id',
            'terms' => $clinical_title,
        );
    }

	if(!empty($provider_region)) {
        $region =  $provider_region;
        $tax_query[] = array(
            'taxonomy' => 'region',
			'field' => 'slug',
            'terms' => $region
        );
    }

	if(isset($_POST['providers'])) {
        $providers = sanitize_text_field( $_POST['providers'] );
		$providers = explode(",", $providers);
    }

	if(isset($_POST['ppp'])) {
        $ppp = sanitize_text_field( $_POST['ppp'] );
    }
 
    $args = array(
        'post_type' => 'provider',
		'post_status' => 'publish',
		'orderby' => 'title',
		'order' => 'ASC',
        'posts_per_page' => -1,
		'fields' => 'ids',
		'post__in' => $providers,
        'tax_query' => $tax_query
    );
 
    $search_query = new WP_Query( $args );
 
    if ( $search_query->have_posts() && !empty($providers) ) {
		$provider_ids = $search_query->posts;
		//echo $_POST['ppp'];
		echo '<data id="provider_ids" data-postids="'. implode(',', $provider_ids) .'"></data>';
		$z=0;
        while ( $z < $ppp && $search_query->have_posts() ) : $search_query->the_post();
            $id = get_the_ID();
			include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
			$z++;
        endwhile;
		
    } else {
		//var_dump($args);
        echo 'No matching providers found. Try a different filter or resetting filters';
    }
    wp_die();
}
function uamswp_add_trench(){
	if(is_page( )) {
		$trench = get_field('page_filter_region');
		if (isset($trench) && $trench){	
			?>
			<script type="text/javascript">
				var days = 1; // Expiration value
				var date = new Date();
				date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
				// expires = "; expires=" + date.toUTCString();
				// var domain = "; domain=" + window.location.hostname;
    			document.cookie = "_filter_region=<?php echo $trench->slug; ?> expires="+date.toGMTString()+"; path=/; domain="+window.location.hostname;
			</script>
		<?php
		}
	}
}
add_action('wp_footer', 'uamswp_add_trench');