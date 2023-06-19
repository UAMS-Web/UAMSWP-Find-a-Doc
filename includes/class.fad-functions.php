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
	// 			$results[$k]->link = $new_url;
		if ( !empty($full_name) )
				$results[$k]->title = $full_name;
		if ( !empty($new_desc) )
				$results[$k]->content = $new_desc;

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
			//echo json_encode(false);
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
	wp_enqueue_style( 'fad-app-css', UAMS_FAD_ROOT_URL . 'assets/css/app.css', array(), UAMS_FAD_VERSION, 'all');
	wp_enqueue_style( 'fad-css', UAMS_FAD_ROOT_URL . 'assets/css/style.css', array(), UAMS_FAD_VERSION, 'all');
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
			$custom_columns['provider_post_thumbs'] = __('Headshot'); // Move before title column
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
		// add_action( "{$taxonomy}_edit_form", 'genesis_taxonomy_archive_options', 20, 2 );
		// add_action( "{$taxonomy}_edit_form", 'genesis_taxonomy_seo_options', 20, 2 );
		// add_action( "{$taxonomy}_edit_form", 'genesis_taxonomy_layout_options', 20, 2 );
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
	if (!isset($term->term_id)) return $content;	// not a taxonomy term, skip
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
	if ( is_singular( 'expertise' ) || is_singular( 'condition' ) || is_singular( 'treatment' ) || is_singular( 'clinical-resource' ) ) { // Only run on these template pages
		// Register the script
		wp_register_script( 'uamswp-region-filter', UAMS_FAD_ROOT_URL . 'assets/js/uamswp-region-filter.js', array('jquery'), false, true );

		// Localize the script with new data
		$script_data_array = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'load_more_posts' )
		);
		wp_localize_script( 'uamswp-region-filter', 'uamswp_region_filter', $script_data_array );

		// Enqueued script with localized data.
		wp_enqueue_script( 'uamswp-region-filter' );
	}
	if ( is_singular( 'location' ) ) {
		wp_register_script( 'uamswp-title-filter', UAMS_FAD_ROOT_URL . 'assets/js/uamswp-title-filter.js', array('jquery'), false, true );

		// Register the script
		wp_register_script( 'uamswp-schedule-filter', UAMS_FAD_ROOT_URL . 'assets/js/uamswp-schedule.js', array('jquery'), false, true );

		// Localize the script with new data
		$script_data_array = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'load_more_posts' )
		);
		wp_localize_script( 'uamswp-title-filter', 'uamswp_ajax_scripts', $script_data_array );
		wp_localize_script( 'uamswp-schedule-filter', 'uamswp_ajax_scripts', $script_data_array );

		// Enqueued script with localized data.
		wp_enqueue_script( 'uamswp-title-filter' );
		wp_enqueue_script( 'uamswp-schedule-filter' );
	}
}
add_action( 'wp_enqueue_scripts', 'uamswp_ajax_scripts' );

add_action('wp_ajax_load_posts_by_ajax', 'uamswp_load_by_ajax_callback');
add_action('wp_ajax_nopriv_load_posts_by_ajax', 'uamswp_load_by_ajax_callback');
function uamswp_load_by_ajax_callback(){
	// $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 6; // Set this default value
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
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $ids_array,
		);
	// } else { // Taxonomy
	// 	$tax = (isset($_POST["tax"])) ? $_POST["tax"] : '';
	// 	$slug = (isset($_POST["slug"])) ? $_POST["slug"] : '';
	// 	$args = array(
	// 		'post_type' => 'provider',
	// 		'post_status' => 'publish',
	// 		'posts_per_page' => $ppp,
	// 		'orderby' => 'title',
	// 		'order' => 'ASC',
	// 		'paged'	=> $page,
	// 		'tax_query' => array(
	// 			array(
	// 			'taxonomy' => $tax,
	// 			'field' => 'slug',
	// 			'terms' => $slug,
	// 			'operator' => 'IN'
	// 			)
	// 		)
	// 	);
	// }
	$loop = new WP_Query($args);
	$out = '';
	if ($loop -> have_posts()) : while ($loop -> have_posts()) : $loop -> the_post();
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
					'field'	=> 'slug',
					'terms'	=> $slug,
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
				$full_name = get_field('physician_first_name') .' ' .(get_field('physician_middle_name') ? get_field('physician_middle_name') . ' ' : '') . get_field('physician_last_name') . (get_field('physician_pedigree') ? '&nbsp;' . get_field('physician_pedigree') : '') . ( $degree_list ? ', ' . $degree_list : '' );
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
	// 	//
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

// Provider AJAX
function uamswp_provider_ajax_filter_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'providers' => '',
		// 'ppp' => '',
		'region' => ''
	), $atts);
	$providers = explode(",", $a['providers']);
	// $ppp = $a['ppp'];
	$display_region = $a['region'];
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
			foreach($provider_region as $region){
				$provider_regions[] = $region;
			}
			//$provider_regions[] = $provider_region;
		}
	}
	$provider_titles_list = array_unique($provider_titles);
	asort($provider_titles_list);
	$provider_regions_ids = array_unique($provider_regions);
	sort($provider_regions_ids);

	$provider_region = '';
	if( isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region']) ) {
		$provider_region = isset($_GET['_filter_region']) ? $_GET['_filter_region'] : $_COOKIE['wp_filter_region'];
	}
	$provider_title = '';
	if( isset($_COOKIE['_provider_title']) ) {
		$provider_title = $_COOKIE['_provider_title'] ;
	}
	//provider_ajax_filter_scripts();

	ob_start(); ?>

	<div class="ajax-filter" id="provider-ajax-filter">
		<h3 class="sr-only">Filter the Providers</h3>
		<form action="" method="get">
			<!-- <input type="text" name="search" id="search" value="" placeholder="Search Here.."> -->
			<div class="form-row align-items-center justify-content-center">
				<div class="col-12 mb-4 col-sm-auto mb-sm-0">
					<label class="sr-only" for="provider_title">Clinical Title</label>
					<select name="provider_title" id="provider_title" class="form-control">
						<option value="">Any Clinical Title</option>
						<?php foreach($provider_titles_list as $key => $title) : ?>
							<option value="<?= $key; ?>"<?php echo ($key == $provider_title) ? ' selected' : ''; ?>><?= $title; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php if ($display_region !== 'hide') { ?> 
				<div class="col-12 mb-4 col-sm-auto mb-sm-0">
					<label class="sr-only" for="provider_region">Region</label>
					<select name="provider_region" id="provider_region" class="form-control">
						<option value="">Any Region</option>
						<?php $regions = get_terms('region', 'orderby=name&hide_empty=0');
						foreach($regions as $region) : ?>
							<option value="<?php echo $region->slug; ?>"<?php echo in_array($region->term_id, $provider_regions_ids) ? '' : ' disabled'; ?><?php echo ($region->slug === $provider_region) ? ' selected' : ''; ?>><?php echo $region->name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php } ?>
				<div class="col-auto">
					<input type="hidden" id="providers-ids" name="providers-ids" value="<?php echo implode(",", $providers); ?>">
				</div>
				<div class="col-auto">
					<input type="button" id="provider_clear" name="provider_clear" value="Reset" class="btn btn-outline-primary">
				</div>
			</div>
		</form>
		<?php if( isset($_COOKIE['wp_filter_region']) && $display_region !== 'hide' ) { ?>
		<p class="ajax-filter-message" id="provider-ajax-filter-message">The results below are filtered by region based on your previous selections. Use the "Reset" button above to view UAMS&nbsp;Health providers in all areas of&nbsp;the&nbsp;state.</p>
		<?php } ?>
	</div>
	<hr />
	<?php
	return ob_get_clean();
}
add_shortcode ('uamswp_provider_ajax_filter', 'uamswp_provider_ajax_filter_shortcode');

// Ajax Callback
add_action('wp_ajax_nopriv_provider_ajax_filter', 'provider_ajax_filter_callback');
add_action('wp_ajax_provider_ajax_filter', 'provider_ajax_filter_callback');

function provider_ajax_filter_callback() {
	$tax_query = array('relation' => 'AND');
	$tax_query_title = array();
	$tax_query_region = array();

	// Get data variables
	$provider_title = '';
	if( isset($_COOKIE['_provider_title']) ) {
		$provider_title = $_COOKIE['_provider_title'] ;
	} elseif(isset($_POST['provider_title'])){
		$provider_title = sanitize_text_field( $_POST['provider_title'] );
	}

	$provider_region = '';
	if( isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region']) ) {
		if ( isset($_GET['_filter_region']) ) {
			setcookie("wp_filter_region", htmlspecialchars($_GET['_filter_region']), "", "/", $_SERVER['HTTP_HOST'] );
		}
		$provider_region = $_COOKIE['wp_filter_region'];
	} elseif(isset($_POST['provider_region'])){
		$provider_region = sanitize_text_field( $_POST['provider_region'] );
	}

	if(isset($_POST['providers'])) {
		$providers = sanitize_text_field( $_POST['providers'] );
		$providers = explode(",", $providers);
	}

	// Build query for regions, based on titles
	if(!empty($provider_title) ) {
		$clinical_title = $provider_title ;
		$tax_query_title[] = array(
			'taxonomy' => 'clinical_title',
			'field' => 'term_id',
			'terms' => $clinical_title,
		);
		// Merge into full tax query 
		$tax_query = array_merge($tax_query, $tax_query_title);

	}

	$args = array(
		'post_type' => 'provider',
		'post_status' => 'publish',
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => -1,
		'fields' => 'ids',
		'post__in' => $providers,
		'tax_query' => $tax_query_title
	);

	$region_prov_ids = new WP_Query( $args );



	$provider_region_IDs = array();
	while ($region_prov_ids->have_posts()) : $region_prov_ids->the_post();
		$id = get_the_ID();
		$provider_region_IDs = array_merge($provider_region_IDs, get_field('physician_region', $id));
	endwhile;
	$provider_region_IDs = array_unique($provider_region_IDs);
	$provider_region_list = array();
	foreach ($provider_region_IDs as $provider_region_ID){
		$provider_region_list[] = get_term_by( 'ID', $provider_region_ID, 'region' )->slug;
	}

	// Build query for titles, based on regions
	if(!empty($provider_region)) {
		$region = $provider_region;
		$tax_query_region[] = array(
			'taxonomy' => 'region',
			'field' => 'slug',
			'terms' => $region
		);
		// Merge into full tax query
		$tax_query = array_merge($tax_query, $tax_query_region);

	}

	// Query providers based full tax query
	$args = array(
		'post_type' => 'provider',
		'post_status' => 'publish',
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => -1,
		'fields' => 'ids',
		'post__in' => $providers,
		'tax_query' => $tax_query_region
	);

	$title_prov_ids = new WP_Query( $args );



	$title_list = array();
	while ($title_prov_ids->have_posts()) : $title_prov_ids->the_post();
		$id = get_the_ID();
		$title_list[] = get_field('physician_title', $id);
	endwhile;


	// if(isset($_POST['providers'])) {
	// 	$providers = sanitize_text_field( $_POST['providers'] );
	// 	$providers = explode(",", $providers);
	// }

	// if(isset($_POST['ppp'])) {
	// 	$ppp = sanitize_text_field( $_POST['ppp'] );
	// }

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
		// $z=0;
		while ( $search_query->have_posts() ) : $search_query->the_post();
			$id = get_the_ID();
			include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
			// $z++;
		endwhile;
		echo '<data id="provider_ids" data-postids="'. implode(',', $provider_ids) .'," data-regions="'. implode(',', $provider_region_list) .'," data-titles="'. implode(',', array_unique($title_list)) .',"></data>';
		// var_dump($tax_query_title);
		// var_dump($tax_query_region);
		// var_dump($tax_query);
	} else {
		//var_dump($args);
		echo '<span class="no-results">Sorry, there are no providers matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
	}
	wp_die();
}
// provider filter with title only
function uamswp_provider_title_ajax_filter_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'providers' => ''
	), $atts);
	$providers = explode(",", $a['providers']);
	$provider_titles = array();
	$provider_titles_list = array();
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
		}
	}
	$provider_titles_list = array_unique($provider_titles);
	asort($provider_titles_list);

	$provider_title = '';
	if( isset($_COOKIE['_provider_title']) ) {
		$provider_title = $_COOKIE['_provider_title'] ;
	}

	ob_start(); ?>

	<div class="ajax-filter" id="provider-title-ajax-filter">
		<h3 class="sr-only">Filter the Providers</h3>
		<form action="" method="get">
			<div class="form-row align-items-center justify-content-center">
				<div class="col-12 mb-4 col-sm-auto mb-sm-0">
					<label class="sr-only" for="provider_title">Clinical Title</label>
					<select name="provider_title" id="provider_title" class="form-control">
						<option value="">Any Clinical Title</option>
						<?php foreach($provider_titles_list as $key => $title) : ?>
							<option value="<?= $key; ?>"<?php echo ($key == $provider_title) ? ' selected' : ''; ?>><?= $title; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-auto">
					<input type="hidden" id="providers-ids" name="providers-ids" value="<?php echo implode(",", $providers); ?>">
				</div>
				<div class="col-auto">
					<input type="button" id="provider_clear" name="provider_clear" value="Reset" class="btn btn-outline-primary">
				</div>
			</div>
		</form>
	</div>
	<hr />
	<?php
	return ob_get_clean();
}
add_shortcode ('uamswp_provider_title_ajax_filter', 'uamswp_provider_title_ajax_filter_shortcode');

// Ajax Callback for Provider Titles
add_action('wp_ajax_nopriv_provider_title_ajax_filter', 'provider_title_ajax_filter_callback');
add_action('wp_ajax_provider_title_ajax_filter', 'provider_title_ajax_filter_callback');
function provider_title_ajax_filter_callback() {
	$tax_query = array('relation' => 'AND');
	$tax_query_title = array();

	// Get data variables
	$provider_title = '';
	if(isset($_POST['provider_title'])){
		$provider_title = sanitize_text_field( $_POST['provider_title'] );
	}

	if(isset($_POST['providers'])) {
		$providers = sanitize_text_field( $_POST['providers'] );
		$providers = explode(",", $providers);
	}

	// Build query for regions, based on titles
	if(!empty($provider_title) ) {
		$clinical_title = $provider_title ;
		$tax_query_title[] = array(
			'taxonomy' => 'clinical_title',
			'field' => 'term_id',
			'terms' => $clinical_title,
		);
		// Merge into full tax query 
		$tax_query = array_merge($tax_query, $tax_query_title);

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
		$title_list = array();
		while ( $search_query->have_posts() ) : $search_query->the_post();
			$id = get_the_ID();
			$title_list[] = get_field('physician_title', $id);
			include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
		endwhile;
		echo '<data id="provider_ids" data-postids="'. implode(',', $provider_ids) .'," data-titles="'. implode(',', array_unique($title_list)) .',"></data>';
	} else {
		//var_dump($args);
		echo '<span class="no-results">Sorry, there are no providers matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
	}
	wp_die();
}

// Location AJAX functions
function uamswp_location_ajax_filter_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'locations' => '',
		'region' => ''
	), $atts);
	$locations = explode(",", $a['locations']);
	$display_region = $a['region'];
	$location_titles = array();
	$location_titles_list = array();
	$regions = array();
	foreach($locations as $location) {
		if ( get_post_status ( $location ) == 'publish' ) {
			// Region
			$location_region[] = get_field('location_region', $location);
			foreach($location_region as $region){
				$location_regions[] = $region;
			}
		}
	}
	$location_regions_ids = array_unique($location_regions);
	sort($location_regions_ids);

	$location_region = '';
	if( isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region']) ) {
		$location_region = isset($_GET['_filter_region']) ? $_GET['_filter_region'] : $_COOKIE['wp_filter_region'];
	}
	//location_ajax_filter_scripts();

	ob_start(); ?>

	<div class="ajax-filter" id="location-ajax-filter">
		<h3 class="sr-only">Filter the Locations</h3>
		<form action="" method="get">
			<!-- <?php print_r($locations); ?> -->
			<div class="form-row align-items-center justify-content-center">
				<div class="col-12 mb-4 col-sm-auto mb-sm-0<?php echo $display_region == 'hide' ? ' d-none' : '' ?>">
					<label class="sr-only" for="location_region">Region</label>
					<select name="location_region" id="location_region" class="form-control">
						<option value="">Any Region</option>
						<?php $regions = get_terms('region', 'orderby=name&hide_empty=0');
						foreach($regions as $region) : ?>
							<option value="<?php echo $region->slug; ?>"<?php echo in_array($region->term_id, $location_regions_ids) ? '' : ' disabled'; ?><?php echo ($region->slug === $location_region) ? ' selected' : ''; ?>><?php echo $region->name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-auto">
					<input type="hidden" id="locations" name="locations" value="<?php echo implode(",", $locations); ?>">
					<!-- <input type="submit" id="submit" name="submit" value="Search" class="btn btn-primary"> -->
				</div>
				<div class="col-auto">
					<input type="button" id="location_clear" name="location_clear" value="Reset" class="btn btn-outline-primary">
				</div>
			</div>
		</form>
		<?php if( isset($_COOKIE['wp_filter_region']) ) { ?>
		<p class="ajax-filter-message" id="location-ajax-filter-message">The results below are filtered by region based on your previous selections. Use the "Reset" button above to view UAMS&nbsp;Health locations in all areas of&nbsp;the&nbsp;state.</p>
		<?php } ?>
	</div>
	<hr />

	<?php
	return ob_get_clean();
}
add_shortcode ('uamswp_location_ajax_filter', 'uamswp_location_ajax_filter_shortcode');
// Ajax Callback
add_action('wp_ajax_nopriv_location_ajax_filter', 'location_ajax_filter_callback');
add_action('wp_ajax_location_ajax_filter', 'location_ajax_filter_callback');

function location_ajax_filter_callback() {
	global $location_single_name; // Typically defined in uamswp_fad_labels_location()
	global $location_single_name_attr; // Typically defined in uamswp_fad_labels_location()
	global $location_plural_name; // Typically defined in uamswp_fad_labels_location()

	$tax_query = array();

	// Get data variables
	$location_region = '';
	if( isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region']) ) {
		if ( isset($_GET['_filter_region']) ) {
			setcookie("wp_filter_region", htmlspecialchars($_GET['_filter_region']), "", "/", $_SERVER['HTTP_HOST'] );
		}
		$location_region = $_COOKIE['wp_filter_region'];
	} elseif(isset($_POST['location_region'])){
		$location_region = sanitize_text_field( $_POST['location_region'] );
	}

	if(isset($_POST['locations'])) {
		$locations = sanitize_text_field( $_POST['locations'] );
		$locations = explode(",", $locations);
	}

	// Build query for regions
	$args = array(
		'post_type' => 'location',
		'post_status' => 'publish',
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => -1,
		'fields' => 'ids',
		'post__in' => $locations,
	);
	$region_loc_ids = new WP_Query( $args );

	$location_region_IDs = array();
	while ($region_loc_ids->have_posts()) : $region_loc_ids->the_post();
		$id = get_the_ID();
		$location_region_IDs[] = get_field('location_region', $id);
	endwhile;
	$location_region_IDs = array_unique($location_region_IDs);
	$location_region_list = array();
	foreach ($location_region_IDs as $location_region_ID){
		$location_region_list[] = get_term_by( 'ID', $location_region_ID, 'region' )->slug;
	}

	// Build query for titles, based on regions
	if(!empty($location_region)) {
		$region = $location_region;
		$tax_query[] = array(
			'taxonomy' => 'region',
			'field' => 'slug',
			'terms' => $region
		);
		// Merge into full tax query
		// $tax_query = array_merge($tax_query, $tax_query_region);
	}

	$args = array(
		'post_type' => 'location',
		'post_status' => 'publish',
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => -1,
		'fields' => 'ids',
		'post__in' => $locations,
		'tax_query' => $tax_query
	);

	$search_query = new WP_Query( $args );

	if ( $search_query->have_posts() && !empty($locations) ) {
		$location_ids = $search_query->posts;
		while ( $search_query->have_posts() ) : $search_query->the_post();
			$id = get_the_ID();
			include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
		endwhile;
		echo '<data id="location_ids" data-postids="'. implode(',', $location_ids) .'," data-regions="'. implode(',', $location_region_list) .',"></data>';
	} else {
		echo '<span class="no-results">Sorry, there are no locations matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
	}
	wp_die();
}

function uamswp_add_trench(){
	if(is_page( )) {
		$trench = get_field('page_filter_region');
		$trenchQS = '';
		if ( isset($_GET['_filter_region']) ) {
			$trenchQS = $_GET['_filter_region'];
		}
		if ((isset($trench) && $trench) || $trenchQS){
			$region = $trench->slug ? $trench->slug : htmlspecialchars($trenchQS);
			?>
			<script type="text/javascript">
				// Set cookie to expire at end of session
				document.cookie = "wp_filter_region=<?php echo $region; ?>; path=/; domain="+window.location.hostname;
			</script>
		<?php
		}
	}
}
add_action('wp_footer', 'uamswp_add_trench');

// Ajax Callback
add_action('wp_ajax_nopriv_schedule_ajax_filter', 'schedule_ajax_filter_callback');
add_action('wp_ajax_schedule_ajax_filter', 'schedule_ajax_filter_callback');
function schedule_ajax_filter_callback() {
	if (!isset($_POST['pid']) || !isset($_POST['schedule_options'])) {
		// echo json_encode(false);
		exit;
	}

	$pid = $_POST['pid'];
	$schedule_key = $_POST['schedule_options'];

	$schedules = get_field('location_scheduling_options', $pid);
	$row = $schedules[$schedule_key];
	$mychart_scheduling_domain = get_field('mychart_scheduling_domain', 'option');
	$mychart_scheduling_instance = get_field('mychart_scheduling_instance', 'option');
	$mychart_scheduling_linksource = get_field('mychart_scheduling_linksource', 'option');
	$mychart_scheduling_linksource = ( isset($mychart_scheduling_linksource) && !empty($mychart_scheduling_linksource) ) ? $mychart_scheduling_linksource : 'uamshealth.com';
	$location_scheduling_options = get_field('location_scheduling_options', $pid);

	$location_scheduling_ser = $row['location_scheduling_ser'];
	$location_scheduling_dep = $row['location_scheduling_dep'];
	$location_scheduling_vt = $row['location_scheduling_vt'];
	$location_scheduling_item_title_nested = $row['location_scheduling_item_title_nested'];
	$location_scheduling_item_title_nested = ( isset($location_scheduling_item_title_nested) && !empty($location_scheduling_item_title_nested) ) ? $location_scheduling_item_title_nested : 'Schedule an Appointment Online';
	$location_scheduling_item_intro_nested = $row['location_scheduling_item_intro_nested'];
	$location_scheduling_fallback = $row['location_scheduling_fallback'];
	?>
	<h3 class="sr-only module-inner-title"><?php echo $location_scheduling_item_title_nested; ?></h3>
	<?php if ( $location_scheduling_item_intro_nested && !empty($location_scheduling_item_intro_nested) ) { ?>
		<p class="note">
			<?php echo $location_scheduling_item_intro_nested; ?>
		</p>
	<?php } ?>
	<div id="scheduleContainer">
		<iframe id="openSchedulingFrame" class="widgetframe" scrolling="no" src="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/SignupAndSchedule/EmbeddedSchedule?id=<?php echo $location_scheduling_ser; ?>&dept=<?php echo $location_scheduling_dep; ?>&vt=<?php echo $location_scheduling_vt; ?>&linksource=<?php echo $mychart_scheduling_linksource; ?>"></iframe>
	</div>

	<!-- <link href="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/Content/EmbeddedWidget.css" rel="stylesheet" type="text/css"> -->

	<script src="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/Content/EmbeddedWidgetController.js" type="text/javascript"></script>

	<script type="text/javascript">
	var EWC = new EmbeddedWidgetController({

		// Replace with the hostname of your Open Scheduling site
		'hostname':'https://<?php echo $mychart_scheduling_domain; ?>',

		// Must equal media query in EpicWP.css + any left/right margin of the host page. Should also change in EmbeddedWidget.css
		'matchMediaString':'(max-width: 991.98px)',

		//Show a button on top of the widget that lets the user see the slots in fullscreen.
		'showToggleBtn':true,

		//The toggle button’s help text for screen reader.
		'toggleBtnExpandHelpText': 'Expand to see the slots in fullscreen',
		'toggleBtnCollapseHelpText': 'Exit fullscreen',
	});
	</script>
	<?php if ( $location_scheduling_fallback && !empty($location_scheduling_fallback) ) { ?>
		<div class="more">
			<?php echo $location_scheduling_fallback; ?>
		</div>
	<?php } ?>
	<?php

	wp_die();
}

// Remove unused / overly agressive scripts
function uamswp_fad_disable_scripts() {
	// Add pages Ajax Search is used
	if ( !is_post_type_archive( 'location' ) && !is_post_type_archive( 'provider' ) && !is_post_type_archive( 'clinical-resource' ) ) {
		wp_dequeue_script('wd-asp-async-loader');
		wp_dequeue_script( 'wd-asp-ajaxsearchpro' );
	}
}

add_action('wp_enqueue_scripts', 'uamswp_fad_disable_scripts', 100);

// Convert text string to HTML attribute-friendly text string
function uamswp_attr_conversion($input)
{
	$input_attr = $input;
	$input_attr = str_replace('"', '\'', $input_attr); // Replace double quotes with single quote
	$input_attr = htmlentities($input_attr, null, 'UTF-8'); // Convert all applicable characters to HTML entities
	$input_attr = str_replace('&nbsp;', ' ', $input_attr); // Convert non-breaking space with normal space
	$input_attr = html_entity_decode($input_attr); // Convert HTML entities to their corresponding characters
	return $input_attr;
}

// Get site header and site nav values for ontology subsections
function uamswp_fad_ontology_site_values() {
	// Bring in variables from outside of the function
	global $page_id; // Typically defined on the template
	global $page_title; // Typically defined on the template
	global $page_url; // Typically defined on the template
	global $ontology_type; // Typically defined on the template

	// Make variables available outside of the function
	global $site_nav_id;
	global $navbar_subbrand_title;
	global $navbar_subbrand_title_attr;
	global $navbar_subbrand_title_url;
	global $navbar_subbrand_parent;
	global $navbar_subbrand_parent_attr;
	global $navbar_subbrand_parent_url;
	global $providers;
	global $locations;
	global $expertises;
	global $child_pages;
	global $clinical_resources;
	global $conditions_cpt;
	global $treatments_cpt;

	// Ancestors
	$ancestors = get_post_ancestors($page_id); // Get all ancestors

	// Get only the ancestors with the ontology type
	$ancestors_ontology = array();
	if ( $ancestors ) {
		foreach( $ancestors as $ancestor ) {
			$ancestor_content_type = get_field('expertise_type', $ancestor); // True is ontology type, false is content type
			$ancestor_content_type = isset($ancestor_content_type) ? $ancestor_content_type : 1; // Check if 'expertise_type' is not null, and if so, set value to true
			if ( $ancestor_content_type ) {
				$ancestors_ontology[] = $ancestor;
			}
		}
	}

	// Count the ancestors with ontology type
	$ancestors_ontology_count = count($ancestors_ontology);
	$has_ancestors_ontology = $ancestors_ontology_count ? true : false;

	// Get the farthest ancestor with ontology type
	$ancestors_ontology_farthest = end($ancestors_ontology);

		// Get the values for farthest ancestor
		$ancestors_ontology_farthest_obj = '';
		$ancestors_ontology_farthest_title = '';
		$ancestors_ontology_farthest_title_attr = '';
		$ancestors_ontology_farthest_url = '';
		if ( $has_ancestors_ontology && $ancestors_ontology_farthest ) {
			$ancestors_ontology_farthest_obj = get_post( $ancestors_ontology_farthest );
		}
		if ( $ancestors_ontology_farthest_obj ) {
			$ancestors_ontology_farthest_title = $ancestors_ontology_farthest_obj->post_title;
			$ancestors_ontology_farthest_title_attr = uamswp_attr_conversion($ancestors_ontology_farthest_title);
			$ancestors_ontology_farthest_url = get_permalink( $ancestors_ontology_farthest );
		}

	// Get the closest ancestor with the ontology type
	$ancestors_ontology_closest = reset($ancestors_ontology);

		// Get the values for closest ancestor
		$ancestors_ontology_closest_obj = '';
		$ancestors_ontology_closest_title = '';
		$ancestors_ontology_closest_title_attr = '';
		$ancestors_ontology_closest_url = '';
		if ( $has_ancestors_ontology && $ancestors_ontology_closest ) {
			$ancestors_ontology_closest_obj = get_post( $ancestors_ontology_closest );
		}
		if ( $ancestors_ontology_closest_obj ) {
			$ancestors_ontology_closest_title = $ancestors_ontology_closest_obj->post_title;
			$ancestors_ontology_closest_title_attr = uamswp_attr_conversion($ancestors_ontology_closest_title);
			$ancestors_ontology_closest_url = get_permalink( $ancestors_ontology_closest );
		}

	// Set the values of the navbar-subbrand elements
	if ( $ontology_type ) {
		// If the page has the ontology type...
		// Set the navbar-subbrand title element using the page's values 
		$site_nav_id = $page_id;
		$navbar_subbrand_title = $page_title;
		$navbar_subbrand_title_attr = uamswp_attr_conversion($navbar_subbrand_title);
		$navbar_subbrand_title_url = $page_url;
		if ( $ancestors_ontology_farthest ) {
			// If a farthest ancestor with the ontology type exists
			// Set the navbar-subbrand parent element using the that ancestor's values 
			$navbar_subbrand_parent = $ancestors_ontology_farthest_title;
			$navbar_subbrand_parent_url = $ancestors_ontology_farthest_url;
		} else {
			// Otherwise, do not define the navbar-subbrand parent element
			$navbar_subbrand_parent = '';
			$navbar_subbrand_parent_url = '';
		}
	} else {
		// If the page  does not have the ontology type...
		// Set the navbar-subbrand title element using the values of the closest ancestor with the ontology type
		$site_nav_id = $ancestors_ontology_closest;
		$navbar_subbrand_title = $ancestors_ontology_closest_title;
		$navbar_subbrand_title_attr = uamswp_attr_conversion($navbar_subbrand_title);
		$navbar_subbrand_title_url = $ancestors_ontology_closest_url;
		if ( $ancestors_ontology_farthest && ( $ancestors_ontology_closest !== $ancestors_ontology_farthest ) ) {
			// If a farthest ancestor with the ontology type exists...
			// And if closest and farthest ancestors with the ontology type are not the same...
			// Set the navbar-subbrand parent element using the values of the farthest ancestor with the ontology type
			$navbar_subbrand_parent = $ancestors_ontology_farthest_title;
			$navbar_subbrand_parent_attr = $ancestors_ontology_farthest_title_attr;
			$navbar_subbrand_parent_url = $ancestors_ontology_farthest_url;
		} else {
			// Otherwise, do not define the navbar-subbrand parent element
			$navbar_subbrand_parent = '';
			$navbar_subbrand_parent_url = '';
		}
	}

	// Get related ontology items
	$providers = get_field( "physician_expertise", $site_nav_id );
	$locations = get_field( 'location_expertise', $site_nav_id );
	$expertises = get_field('expertise_associated', $site_nav_id);
	$child_pages = get_pages( array('child_of' => $site_nav_id, 'post_type' => 'expertise' ) );
	$clinical_resources = get_field('expertise_clinical_resources', $site_nav_id);
	$conditions_cpt = get_field('expertise_conditions_cpt', $site_nav_id);
	$treatments_cpt = get_field('expertise_treatments_cpt', $site_nav_id);
}

// Construct non-standard entry title
function uamswp_fad_post_title() {
	// Add the following (without the commenting) to the relevant template to remove Genesis-standard post title and markup
	// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	// remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

	// Add the following (without the commenting) to the relevant template to add this header style
	// add_action( 'genesis_before_content', 'uamswp_fad_post_title' );

	// Add one of the following variable definitions to the relevant template to indicate which entry header style to use

		// Graphic style
		// $entry_header_style = 'graphic';

		// Marketing Landing Page style
		// $entry_header_style = 'landingpage';

	// Define the following variable to indicate the text elements of the entry title

		// Regular title
		// Graphic style has no character limit on this value
		// Marketing Landing Page style limits this value to 62 characters
		// $entry_title_text = '';

		// Optional supertitle, placed above the regular title
		// This element is only supported by Graphic style
		// $entry_title_text_supertitle = '';

		// Optional subtitle, placed below the regular title
		// This element is supported by Graphic style only
		// $entry_title_text_subtitle = '';

		// Optional lead paragraph, placed below the entry title
		// Graphic style limits this value to 500 characters
		// Marketing Landing Page style limits this value to 117 characters
		// $entry_title_text_body = ''; 

	// Define the following variables to indicate the IDs of the images used in the entry title background

		// Desktop breakpoint image ID
		// This element is optional for Graphic style
		// The minimum dimensions for Graphic style are 1920x720
		// This element is required for Marketing Landing Page style
		// The minimum dimensions for Graphic style are 1920x600
		// $entry_title_image_desktop = '';

		// Optional mobile breakpoint image ID
		// This element is only supported by Marketing Landing Page style
		// This element is optional for Marketing Landing Page style
		// The minimum dimensions for Graphic style are 992x806
		// $entry_title_image_mobile = '';

	// Bring in variables from outside of the function
	global $entry_header_style; // Typically defined on the template
	global $entry_title_text; // Typically defined on the template
	global $entry_title_text_supertitle; // Typically defined on the template
	global $entry_title_text_subtitle; // Typically defined on the template
	global $entry_title_text_body; // Typically defined on the template
	global $entry_title_image_desktop; // Typically defined on the template
	global $entry_title_image_mobile; // Typically defined on the template

	include( UAMS_FAD_PATH . '/templates/entry-title-' . $entry_header_style . '.php');
}

// Queries for whether each of the related ontology content sections should be displayed on ontology pages/subsections

	// Query for whether related providers content section should be displayed on ontology pages/subsections
	function uamswp_fad_provider_query() {
		// Bring in variables from outside of the function

			// Typically defined on the template
			global $jump_link_count;

			// Typically defined on the template or in a function such as uamswp_fad_ontology_site_values()
			global $providers; // Value of the related providers input
			// global $site_nav_id; // Typically defined in uamswp_fad_ontology_site_values()

		// Make variables available outside of the function
		global $provider_query;
		global $provider_section_show;
		global $provider_ids;
		global $provider_count; // integer

		if($providers) {
			$args = array(
				'post_type' => 'provider',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
				'fields' => 'ids',
				// 'no_found_rows' => true, // counts posts, remove if pagination required
				'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
				'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
				'post__in' => $providers
			);
			$provider_query = New WP_Query( $args );
			if( ( $provider_query && $provider_query->have_posts() ) ) {
				$provider_section_show = true;
				$provider_ids = $provider_query->posts;
				$provider_count = count($provider_query->posts);
				$jump_link_count = $jump_link_count + 1;
			} else {
				// wp_redirect( get_the_permalink($site_nav_id), 301 );
				$provider_section_show = false;
			}
		}
	}

	// Query for whether related locations content section should be displayed on a page
	function uamswp_fad_location_query() {
		// Bring in variables from outside of the function

			// Typically defined on the template
			global $jump_link_count;

			// Typically defined on the template or in a function such as uamswp_fad_ontology_site_values()
			global $locations; // Value of the related locations input

		// Make variables available outside of the function
		global $location_query;
		global $location_section_show;
		global $location_ids;
		global $location_count; // integer
		global $location_valid;

		$location_valid = false;

		if ( $locations) {
			$args = array(
				'post_type' => 'location',
				'post_status' => 'publish',
				'order' => 'ASC',
				'orderby' => 'title',
				'posts_per_page' => -1,
				'fields' => 'ids',
				'no_found_rows' => true, // counts posts, remove if pagination required
				'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
				'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
				'post__in' => $locations
			);
			$location_query = new WP_Query( $args );
			if( ( $locations && $location_query->have_posts() ) ) {
				$location_section_show = true;
				$location_ids = $location_query->posts;
				$location_count = count($location_query->posts);
				$jump_link_count = $jump_link_count + 1;
			} else {
				$location_section_show = false;
			}

			// Check for valid locations
			if ( $locations && $location_query->have_posts() ) {
				foreach( $locations as $location ) {
					if ( get_post_status ( $location ) == 'publish' ) {
						$location_valid = true;
						$break;
					}
				}
			}
		}
	}

	// Query for whether descendant areas of expertise content section should be displayed on ontology pages/subsections
	function uamswp_fad_expertise_descendant_query() {
		// Bring in variables from outside of the function

			// Typically defined on the template
			global $jump_link_count;

			// Typically defined on the template or in a function such as uamswp_fad_ontology_site_values()
			global $child_pages;

		// Make variables available outside of the function
		global $childnav;
		global $children;
		global $expertise_descendant_section_show;
		global $child_content_nav_show;

		if ($child_pages) {
			$childnav = '';
			$children = false;
			foreach ( $child_pages as $child_page ) {
				$hide = get_post_meta($child_page->ID, 'page_hide_from_menu');
				$type = get_field('expertise_type', $child_page->ID);
				$type = isset($type) ? $type : 1; // Check if 'expertise_type' is not null, and if so, set value to true
				if ( isset($hide[0]) && '1' == $hide[0] ) {
					//* Do nothing if there is nothing to show
				} elseif( !isset($type) || '1' == $type ) {
					$children = true;
				} else {
					$childnav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item menu-item-'. $child_page->ID .' nav-item active"><a title="'. $child_page->post_title .'" href="'. get_permalink( $child_page->ID ) .'" class="nav-link"><span itemprop="name">'. $child_page->post_title .'</span></a></li>';
				}
			}
			$expertise_descendant_section_show = $children ? true : false;
			$child_content_nav_show = !empty($childnav) ? true : false;
		}
	}

	// Query for whether related areas of expertise content section should be displayed on ontology pages/subsections
	function uamswp_fad_expertise_query() {
		// Bring in variables from outside of the function

			// Typically defined on the template
			global $jump_link_count;

			// Typically defined on the template or in a function such as uamswp_fad_ontology_site_values()
			global $expertises;

		// Make variables available outside of the function
		global $expertise_query;
		global $expertise_section_show;
		global $expertise_ids;
		global $expertise_count; // integer
		global $expertise_primary_name; // string
		global $expertise_primary_name_attr; // string

		$args = array(
			'post_type' => 'expertise',
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post__in'	=> $expertises
		);
		$expertise_query = new WP_Query( $args );
		if ( ( $expertises && $expertise_query->have_posts() ) ) {
			$expertise_section_show = true;
			$expertise_ids = $expertise_query->posts;
			$expertise_count = count($expertise_query->posts);
			$jump_link_count = $jump_link_count + 1;
			foreach ( $expertises as $expertise ) {
				if ( get_post_status ( $expertise ) == 'publish' ) {
					$expertise_primary_name = get_the_title($expertise);
					$expertise_primary_name_attr = uamswp_attr_conversion($expertise_primary_name);
					break;
				}
			}
		} else {
			$expertise_section_show = false;
		}
	}

	// Query for whether related clinical resources content section should be displayed on ontology pages/subsections
	function uamswp_fad_clinical_resource_query() {
		// Bring in variables from outside of the function

			// Typically defined on the template
			global $jump_link_count;

			// Typically defined on the template or in a function such as uamswp_fad_ontology_site_values()
			global $clinical_resources; // Value of the related locations input

		// Make variables available outside of the function
		global $clinical_resource_query;
		global $clinical_resource_section_show;
		global $clinical_resource_ids;
		global $resource_postsPerPage;
		global $resource_more;

		$resource_postsPerPage = 4; // Set this value to preferred value (-1, 4, 6, 8, 10, 12)
		$resource_more = false;
		$args = array(
			'post_type' => 'clinical-resource',
			'order' => 'DESC',
			'orderby' => 'post_date',
			'posts_per_page' => $resource_postsPerPage,
			'post_status' => 'publish',
			'post__in'	=> $clinical_resources
		);
		$clinical_resource_query = new WP_Query( $args );

		// Check if Clinical Resources section should be displayed
		if( ( $clinical_resources && $clinical_resource_query->have_posts() ) ) {
			$clinical_resource_section_show = true;
			$clinical_resource_ids = $clinical_resource_query->posts;
			$clinical_resource_count = count($clinical_resource_query->posts);
			$jump_link_count = $jump_link_count + 1;
		} else {
			$clinical_resource_section_show = false;
		}
	}

	// Query for whether related conditions content section should be displayed on ontology pages/subsections
	function uamswp_fad_condition_query() {
		// Bring in variables from outside of the function

			// Typically defined on the template
			global $jump_link_count;
			global $ontology_type;

			// Typically defined on the template or in a function such as uamswp_fad_ontology_site_values()
			global $conditions_cpt;

		// Make variables available outside of the function
		global $conditions_cpt_query;
		global $condition_section_show;
		global $condition_ids;
		global $condition_count; // integer

		// Conditions CPT
		$args = array(
			'post_type' => 'condition',
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $conditions_cpt
		);
		$conditions_cpt_query = new WP_Query( $args );
		if( ( $conditions_cpt && $conditions_cpt_query->posts ) && ("1" == $ontology_type || !isset($ontology_type) ) ) {
			$condition_section_show = true;
			$condition_ids = $conditions_cpt_query->posts;
			$condition_count = count($conditions_cpt_query->posts);
			$jump_link_count = $jump_link_count + 1;
		} else {
			$condition_section_show = false;
		}
	}

	// Query for whether related treatments content section should be displayed on ontology pages/subsections
	function uamswp_fad_treatment_query() {
		// Bring in variables from outside of the function

			// Typically defined on the template
			global $jump_link_count;
			global $ontology_type; // Typically defined on the template

			// Typically defined on the template or in a function such as uamswp_fad_ontology_site_values()
			global $treatments_cpt;

		// Make variables available outside of the function
		global $treatments_cpt_query;
		global $treatments_section_show;
		global $treatment_ids;
		global $treatment_count; // integer

		// Treatments CPT
		$args = array(
			'post_type' => 'treatment',
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $treatments_cpt
		);
		$treatments_cpt_query = new WP_Query( $args );
		if( ( $treatments_cpt && $treatments_cpt_query->posts ) && ("1" == $ontology_type || !isset($ontology_type) ) ) {
			$treatments_section_show = true;
			$treatment_ids = $treatments_cpt_query->posts;
			$treatment_count = count($treatments_cpt_query->posts);
			$jump_link_count = $jump_link_count + 1;
		} else {
			$treatments_section_show = false;
		}
	}

// Construct ontology subsection primary navigation
function uamswp_fad_ontology_nav_menu() {
	// Bring in variables from outside of the function
	global $site_nav_id; // Typically defined in uamswp_fad_ontology_site_values()
	global $provider_section_show; // Typically defined in uamswp_fad_provider_query()
	global $location_section_show; // Typically defined in uamswp_fad_location_query()
	global $expertise_section_show; // Typically defined in uamswp_fad_expertise_query()
	global $clinical_resource_section_show; // Typically defined in uamswp_fad_clinical_resource_query()
	global $expertise_descendant_section_show; // Typically defined in uamswp_fad_expertise_descendant_query()
	global $child_pages; // Typically defined in uamswp_fad_expertise_descendant_query()
	global $child_content_nav_show; // Typically defined in uamswp_fad_expertise_descendant_query()
	global $childnav; // Typically defined in uamswp_fad_expertise_descendant_query()
	global $provider_plural_name; // Typically defined in uamswp_fad_labels_provider()
	global $provider_plural_name_attr; // Typically defined in uamswp_fad_labels_provider()
	global $location_plural_name; // Typically defined in uamswp_fad_labels_location()
	global $location_plural_name_attr; // Typically defined in uamswp_fad_labels_location()
	global $expertise_plural_name; // Typically defined in uamswp_fad_labels_expertise()
	global $expertise_plural_name_attr; // Typically defined in uamswp_fad_labels_expertise()
	global $expertise_descendant_plural_name; // Typically defined in uamswp_fad_labels_expertise_descendant()
	global $expertise_descendant_plural_name_attr; // Typically defined in uamswp_fad_labels_expertise_descendant()
	global $clinical_resource_plural_name; // Typically defined in uamswp_fad_labels_clinical_resource()
	global $clinical_resource_plural_name_attr; // Typically defined in uamswp_fad_labels_clinical_resource()

	include( UAMS_FAD_PATH . '/templates/single-expertise-nav.php');
}

// Construct ontology subsection site header
function uamswp_fad_ontology_header() {
	// Bring in variables from outside of the function
	global $navbar_subbrand_title; // Typically defined in uamswp_fad_ontology_site_values()
	global $navbar_subbrand_title_url; // Typically defined in uamswp_fad_ontology_site_values()
	global $navbar_subbrand_parent; // Typically defined in uamswp_fad_ontology_site_values()
	global $navbar_subbrand_parent_url; // Typically defined in uamswp_fad_ontology_site_values()

	include( UAMS_FAD_PATH . '/templates/single-expertise-header.php');
}

// Construct the meta keywords element
function uamswp_keyword_hook_header() { 
	// Bring in variables from outside of the function
	global $keywords; // Typically defined on the template

	if( $keywords ): 
		$i = 1;
		$keyword_text = '';
		foreach( $keywords as $keyword ) { 
			if ( 1 < $i ) {
				$keyword_text .= ', ';
			}
			$keyword_text .= str_replace(",", "", $keyword['alternate_text']);
			$i++;
		}
		echo '<meta name="keywords" content="'. $keyword_text .'" />';
	endif;
}

// Construct ontology subsection appointment information section
function uamswp_fad_ontology_appointment() {
	// Bring in variables from outside of the function
	global $location_single_name; // Typically defined in uamswp_fad_labels_location()
	global $appointment_section_show; // Typically defined on the template

	if ( $appointment_section_show ) {
		if ( get_field('location_expertise') ) {
			$appointment_location_url = '#locations';
			//$appointment_location_label = 'Go to the list of relevant locations';
		} else {
			$appointment_location_url = '/location/';
			//$appointment_location_label = 'View a list of UAMS Health locations';
		} ?>
		<section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2>Make an Appointment</h2>
						<p>Request an appointment by <a href="<?php echo $appointment_location_url; ?>" data-itemtitle="Contact a clinic directly">contacting a <?php echo strtolower($location_single_name); ?> directly</a> or by calling the UAMS&nbsp;Health appointment line at <a href="tel:501-686-8000" class="no-break" data-itemtitle="Call the UAMS Health appointment line">(501) 686-8000</a>.</p>
					</div>
				</div>
			</div>
		</section>
	<?php }
}

// Add fake subpages to breadcrumbs
function uamswp_fad_fpage_breadcrumbs($crumbs) {
	// Bring in variables from outside of the function
	global $fpage_name; // Typically defined on the template

	$crumbs[] = array($fpage_name, '');
	return $crumbs;
}

// Add page template class to body element's classes
function uamswp_page_body_class( $classes ) {
	// Bring in variables from outside of the function
	global $template_type; // Typically defined on the template // Expected values: 'default', 'page_landing' or 'marketing'

	$classes[] = 'page-template-' . $template_type;
	return $classes;
}

// Add bg-white class to article.entry element
function uamswp_add_entry_class( $attributes ) {
	$attributes['class'] = $attributes['class']. ' bg-white';
	return $attributes;
}

// Check if UAMS Health Talk podcast section should be displayed
function uamswp_fad_podcast_query() {
	// Bring in variables from outside of the function
	global $podcast_name; // Typically defined on the template

	// Make variables available outside of the function
	global $podcast_section_show;

	// Check if podcast section should be displayed
	if ($podcast_name) {
		$podcast_section_show = true;
	} else {
		$podcast_section_show = false;
	}
}

// Construct UAMS Health Talk podcast section
function uamswp_fad_podcast() {
	// Bring in variables from outside of the function
	global $podcast_name; // Typically defined on the template
	global $podcast_subject; // Typically defined on the template
	global $podcast_section_show; // Typically defined in uamswp_fad_podcast_query()
	global $podcast_filter; // Typically defined on the template // Expected values: 'tag' or 'doctor'
	global $provider_plural_name; // Typically defined in uamswp_fad_labels_provider()

	if ( $podcast_section_show ) {
		if ( $podcast_filter == 'tag' ) {
			$podcast_filter_id = '303';
		} elseif ( $podcast_filter == 'doctor' ) {
			$podcast_filter_id = '303,1837';
		} else {
			$podcast_filter_id = '';
		}
		?>
			<section class="uams-module podcast-list bg-auto" id="podcast">
				<script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">
				</script>
				<script type="text/javascript">
					radiomd_embedded_filtered_<?php echo $podcast_filter; ?>("uams","radiomd-embedded-filtered-<?php echo $podcast_filter; ?>",<?php echo $podcast_filter_id; ?>,"<?php echo $podcast_name; ?>");
				</script>
				<style type="text/css">
					#radiomd-embedded-filtered-<?php echo $podcast_filter; ?> iframe {
						width: 100%;
						border: none;
					}
				</style>
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<h2 class="module-title"><span class="title">UAMS Health Talk Podcast</span></h2>
							<div class="module-body text-center">
								<p class="lead">In the UAMS Health Talk podcast, experts from UAMS talk about a variety of health topics, providing tips and guidelines to help people lead healthier lives. Listen to the episode(s) featuring <?php echo ( $podcast_filter == 'tag' ) ? 'the topic of ' : ''; ?><?php echo $podcast_subject; ?>.</p>
							</div>
							<div class="content-width mt-8" id="radiomd-embedded-filtered-<?php echo $podcast_filter; ?>"></div>
						</div>
						<div class="col-12 more">
							<p class="lead">Find other great episodes on other topics and from other UAMS Health <?php echo strtolower($provider_plural_name); ?>.</p>
							<div class="cta-container">
								<a href="/podcast/" class="btn btn-primary" aria-label="Listen to more episodes of the UAMS Health Talk podcast">Listen to More Episodes</a>
							</div>
						</div>
					</div>
				</div>
			</section>
	<?php } // endif ( $podcast_section_show )
}

// Get system settings for other ontology item labels and archive page text

	// Get system settings for provider labels
	function uamswp_fad_labels_provider() {
		// Make variables available outside of the function
		global $provider_single_name;
		global $provider_single_name_attr;
		global $provider_plural_name;
		global $provider_plural_name_attr;
		global $placeholder_provider_single_name;
		global $placeholder_provider_plural_name;
		global $placeholder_provider_short_name;
		global $placeholder_provider_short_name_possessive;

		global $facet_labels;

		$provider_single_name = get_field('provider_single_name', 'option') ?: 'Provider';
		$provider_single_name_attr = uamswp_attr_conversion($provider_single_name);
		$provider_plural_name = get_field('provider_plural_name', 'option') ?: 'Providers';
		$provider_plural_name_attr = uamswp_attr_conversion($provider_plural_name);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_provider_single_name = '[Provider]';
		$placeholder_provider_plural_name = '[Providers]';
		$placeholder_provider_short_name = '[Provider Short Name]';
		$placeholder_provider_short_name_possessive = '[Provider Short Name\'s]';


		// Create array for pairing FacetWP name with label if none exists
		$facet_labels = isset($facet_labels) ?: array();

		// Add item to FacetWP labels array for Providers facet on Clinical Resources archive/list
		$facet_labels['resource_provider'] = $provider_plural_name;
		$facet_labels['resource_provider_attr'] = $provider_plural_name_attr;
	}

	// Get system settings for provider archive page text
	function uamswp_fad_archive_provider() {
		// Make variables available outside of the function
		global $provider_archive_headline;
		global $provider_archive_headline_attr;
		global $placeholder_provider_archive_headline;

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_provider_archive_headline = '[Provider Archive Title]';

		$provider_archive_headline = get_field('provider_archive_headline', 'option') ?: 'UAMS Health Providers';
		$provider_archive_headline_attr = uamswp_attr_conversion($provider_archive_headline);
	}

	// Get system settings for location labels
	function uamswp_fad_labels_location() {
		// Make variables available outside of the function
		global $location_single_name;
		global $location_single_name_attr;
		global $location_plural_name;
		global $location_plural_name_attr;
		global $placeholder_location_single_name;
		global $placeholder_location_plural_name;
		global $placeholder_location_page_title;
		global $placeholder_location_page_title_phrase;
		global $facet_labels;

		$location_single_name = get_field('location_single_name', 'option') ?: 'Location';
		$location_single_name_attr = uamswp_attr_conversion($location_single_name);
		$location_plural_name = get_field('location_plural_name', 'option') ?: 'Locations';
		$location_plural_name_attr = uamswp_attr_conversion($location_plural_name);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_location_single_name = '[Location]';
		$placeholder_location_plural_name = '[Locations]';
		$placeholder_location_page_title = '[Location Title]';
		$placeholder_location_page_title_phrase = '[the Location Title]';

		// Create array for pairing FacetWP name with label if none exists
		if ( !isset($facet_labels) ) {
			$facet_labels = [];
		}

		// Add item to FacetWP labels array for Locations facet on Providers archive/list
		$facet_labels['locations'] = $location_plural_name;
		$facet_labels['locations_attr'] = $location_plural_name_attr;

		// Add item to FacetWP labels array for Locations facet on Clinical Resources archive/list
		$facet_labels['resource_locations'] = $location_plural_name;
		$facet_labels['resource_locations_attr'] = $location_plural_name_attr;
	}

	// Get system settings for location descendant item labels
	function uamswp_fad_labels_location_descendant() {
		// Make variables available outside of the function
		global $location_descendant_single_name;
		global $location_descendant_single_name_attr;
		global $location_descendant_plural_name;
		global $location_descendant_plural_name_attr;
		global $placeholder_location_descendant_single_name;
		global $placeholder_location_descendant_plural_name;

		$location_descendant_single_name = get_field('location_descendant_single_name', 'option') ?: 'Additional Location';
		$location_descendant_single_name_attr = uamswp_attr_conversion($location_descendant_single_name);
		$location_descendant_plural_name = get_field('location_descendant_plural_name', 'option') ?: 'Additional Locations';
		$location_descendant_plural_name_attr = uamswp_attr_conversion($location_descendant_plural_name);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_location_descendant_single_name = '[Descendant Location]';
		$placeholder_location_descendant_plural_name = '[Descendant Locations]';
	}

	// Get system settings for location archive page text
	function uamswp_fad_archive_location() {
		// Make variables available outside of the function
		global $location_archive_headline;
		global $location_archive_headline_attr;
		global $placeholder_location_archive_headline;

		$location_archive_headline = get_field('location_archive_headline', 'option') ?: 'Locations';
		$location_archive_headline_attr = uamswp_attr_conversion($location_archive_headline);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_location_archive_headline = '[Location Archive Title]';
	}

	// Get system settings for area of expertise labels
	function uamswp_fad_labels_expertise() {
		// Make variables available outside of the function
		global $expertise_single_name;
		global $expertise_single_name_attr;
		global $expertise_plural_name;
		global $expertise_plural_name_attr;
		global $placeholder_expertise_single_name;
		global $placeholder_expertise_plural_name;
		global $placeholder_expertise_page_title;
		global $facet_labels;

		$expertise_single_name = get_field('expertise_single_name', 'option') ?: 'Area of Expertise';
		$expertise_single_name_attr = uamswp_attr_conversion($expertise_single_name);
		$expertise_plural_name = get_field('expertise_plural_name', 'option') ?: 'Areas of Expertise';
		$expertise_plural_name_attr = uamswp_attr_conversion($expertise_plural_name);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_expertise_single_name = '[Area of Expertise]';
		$placeholder_expertise_plural_name = '[Areas of Expertise]';
		$placeholder_expertise_page_title = '[Area of Expertise Title]';

		// Create array for pairing FacetWP name with label if none exists
		if ( !isset($facet_labels) ) {
			$facet_labels = [];
		}

		// Add item to FacetWP labels array for Areas of Expertise facet on Providers archive/list
		$facet_labels['physician_areas_of_expertise'] = $expertise_plural_name;
		$facet_labels['physician_areas_of_expertise_attr'] = $expertise_plural_name_attr;

		// Add item to FacetWP labels array for Areas of Expertise facet on Locations archive/list
		$facet_labels['location_aoe'] = $expertise_plural_name;
		$facet_labels['location_aoe_attr'] = $expertise_plural_name_attr;

		// Add item to FacetWP labels array for Areas of Expertise facet on Clinical Resources archive/list
		$facet_labels['resource_aoe'] = $expertise_plural_name;
		$facet_labels['resource_aoe_attr'] = $expertise_plural_name_attr;
	}

	// Get system settings for area of expertise descendant item labels
	function uamswp_fad_labels_expertise_descendant() {
		// Make variables available outside of the function
		global $expertise_descendant_single_name;
		global $expertise_descendant_single_name_attr;
		global $expertise_descendant_plural_name;
		global $expertise_descendant_plural_name_attr;
		global $placeholder_expertise_descendant_single_name;
		global $placeholder_expertise_descendant_plural_name;

		$expertise_descendant_single_name = get_field('expertise_descendant_single_name', 'option') ?: 'Specialty';
		$expertise_descendant_single_name_attr = uamswp_attr_conversion($expertise_descendant_single_name);
		$expertise_descendant_plural_name = get_field('expertise_descendant_plural_name', 'option') ?: 'Specialties';
		$expertise_descendant_plural_name_attr = uamswp_attr_conversion($expertise_descendant_plural_name);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_expertise_descendant_single_name = '[Descendant Area of Expertise]';
		$placeholder_expertise_descendant_plural_name = '[Descendant Areas of Expertise]';
	}

	// Get system settings for area of expertise archive page text
	function uamswp_fad_archive_expertise() {
		// Make variables available outside of the function
		global $expertise_archive_headline;
		global $expertise_archive_headline_attr;
		global $expertise_archive_intro_text;
		global $placeholder_expertise_archive_headline;
		global $placeholder_expertise_archive_intro_text;

		$expertise_archive_headline = get_field('expertise_archive_headline', 'option') ?: 'Areas of Expertise';
		$expertise_archive_headline_attr = uamswp_attr_conversion($expertise_archive_headline);
		$expertise_archive_intro_text = get_field('expertise_archive_intro_text', 'option');

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_expertise_archive_headline = '[Area of Expertise Archive Title]';
		$placeholder_expertise_archive_intro_text = '[Area of Expertise Archive Intro Text]';
	}

	// Get system settings for clinical resource labels
	function uamswp_fad_labels_clinical_resource() {
		// Make variables available outside of the function
		global $clinical_resource_single_name;
		global $clinical_resource_single_name_attr;
		global $clinical_resource_plural_name;
		global $clinical_resource_plural_name_attr;
		global $placeholder_clinical_resource_single_name;
		global $placeholder_clinical_resource_plural_name;

		$clinical_resource_single_name = get_field('clinical_resource_single_name', 'option') ?: 'Clinical Resource';
		$clinical_resource_single_name_attr = uamswp_attr_conversion($clinical_resource_single_name);
		$clinical_resource_plural_name = get_field('clinical_resource_plural_name', 'option') ?: 'Clinical Resources';
		$clinical_resource_plural_name_attr = uamswp_attr_conversion($clinical_resource_plural_name);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_clinical_resource_single_name = '[Clinical Resource]';
		$placeholder_clinical_resource_plural_name = '[Clinical Resources]';
	}

	// Get system settings for clinical resource archive page text
	function uamswp_fad_archive_clinical_resource() {
		// Make variables available outside of the function
		global $clinical_resource_archive_headline;
		global $clinical_resource_archive_headline_attr;
		global $placeholder_clinical_resource_archive_headline;

		$clinical_resource_archive_headline = get_field('clinical_resource_archive_headline', 'option') ?: 'Clinical Resources';
		$clinical_resource_archive_headline_attr = uamswp_attr_conversion($clinical_resource_archive_headline);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_clinical_resource_archive_headline = '[Clinical Resource Archive Title]';
	}

	// Get system settings for clinical resource facet labels
	function uamswp_fad_labels_clinical_resource_facet() {
		// Make variables available outside of the function
		global $clinical_resource_type_single_name;
		global $clinical_resource_type_single_name_attr;
		global $clinical_resource_type_plural_name;
		global $clinical_resource_type_plural_name_attr;
		global $placeholder_clinical_resource_type_single_name;
		global $placeholder_clinical_resource_type_plural_name;
		global $facet_labels;

		$clinical_resource_type_single_name = get_field('clinical_resource_type_single_name', 'option') ?: 'Resource Type';
		$clinical_resource_type_single_name_attr = uamswp_attr_conversion($clinical_resource_type_single_name);
		$clinical_resource_type_plural_name = get_field('clinical_resource_type_plural_name', 'option') ?: 'Resource Types';
		$clinical_resource_type_plural_name_attr = uamswp_attr_conversion($clinical_resource_type_plural_name);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_clinical_resource_type_single_name = '[Resource Type]';
		$placeholder_clinical_resource_type_plural_name = '[Resource Types]';

		// Add item to FacetWP labels array for Areas of Expertise facet on Providers archive/list
		$facet_labels['resource_type'] = $clinical_resource_type_plural_name;
		$facet_labels['resource_type_attr'] = $clinical_resource_type_plural_name_attr;
	}

	// Get system settings for combined conditions and treatments labels
	function uamswp_fad_labels_condition_treatment() {
		// Make variables available outside of the function
		global $conditions_treatments_single_name;
		global $conditions_treatments_single_name_attr;
		global $conditions_treatments_plural_name;
		global $conditions_treatments_plural_name_attr;
		global $placeholder_conditions_treatments_single_name;
		global $placeholder_conditions_treatments_plural_name;

		$conditions_treatments_single_name = get_field('conditions_treatments_single_name', 'option') ?: 'Condition or Treatment';
		$conditions_treatments_single_name_attr = uamswp_attr_conversion($conditions_treatments_single_name);
		$conditions_treatments_plural_name = get_field('conditions_treatments_plural_name', 'option') ?: 'Conditions and Treatments';
		$conditions_treatments_plural_name_attr = uamswp_attr_conversion($conditions_treatments_plural_name);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_conditions_treatments_single_name = '[Condition or Treatment]';
		$placeholder_conditions_treatments_plural_name = '[Conditions and Treatments]';
	}

	// Get system settings for condition labels
	function uamswp_fad_labels_condition() {
		// Make variables available outside of the function
		global $conditions_single_name;
		global $conditions_single_name_attr;
		global $conditions_plural_name;
		global $conditions_plural_name_attr;
		global $placeholder_conditions_single_name;
		global $placeholder_conditions_plural_name;
		global $facet_labels;

		$conditions_single_name = get_field('conditions_single_name', 'option') ?: 'Condition';
		$conditions_single_name_attr = uamswp_attr_conversion($conditions_single_name);
		$conditions_plural_name = get_field('conditions_plural_name', 'option') ?: 'Conditions';
		$conditions_plural_name_attr = uamswp_attr_conversion($conditions_plural_name);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_conditions_single_name = '[Condition]';
		$placeholder_conditions_plural_name = '[Conditions]';

		// Create array for pairing FacetWP name with label if none exists
		if ( !isset($facet_labels) ) {
			$facet_labels = [];
		}

		// Add item to FacetWP labels array for Conditions facet on Providers archive/list
		$facet_labels['conditions'] = $conditions_plural_name;
		$facet_labels['conditions_attr'] = $conditions_plural_name_attr;

		// Add item to FacetWP labels array for Conditions facet on Clinical Resources archive/list
		$facet_labels['resource_conditions'] = $conditions_plural_name;
		$facet_labels['resource_conditions_attr'] = $conditions_plural_name_attr;
	}

	// Get system settings for condition archive page text
	function uamswp_fad_archive_condition() {
		// Make variables available outside of the function
		global $conditions_archive_headline;
		global $conditions_archive_headline_attr;
		global $conditions_archive_intro_text;
		global $placeholder_conditions_archive_headline;
		global $placeholder_conditions_archive_intro_text;

		$conditions_archive_headline = get_field('conditions_archive_headline', 'option') ?: 'Conditions';
		$conditions_archive_headline_attr = uamswp_attr_conversion($conditions_archive_headline);
		$conditions_archive_intro_text = get_field('conditions_archive_intro_text', 'option');

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_conditions_archive_headline = '[Condition Archive Title]';
		$placeholder_conditions_archive_intro_text = '[Condition Archive Intro Text]';
	}

	// Get system settings for treatment labels
	function uamswp_fad_labels_treatment() {
		// Make variables available outside of the function
		global $treatments_single_name;
		global $treatments_single_name_attr;
		global $treatments_plural_name;
		global $treatments_plural_name_attr;
		global $placeholder_treatments_single_name;
		global $placeholder_treatments_plural_name;
		global $facet_labels;

		$treatments_single_name = get_field('treatments_single_name', 'option') ?: 'Treatment/Procedure';
		$treatments_single_name_attr = uamswp_attr_conversion($treatments_single_name);
		$treatments_plural_name = get_field('treatments_plural_name', 'option') ?: 'Treatments and Procedures';
		$treatments_plural_name_attr = uamswp_attr_conversion($treatments_plural_name);

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_treatments_single_name = '[Treatment]';
		$placeholder_treatments_plural_name = '[Treatments]';

		// Create array for pairing FacetWP name with label if none exists
		if ( !isset($facet_labels) ) {
			$facet_labels = [];
		}

		// Add item to FacetWP labels array for Treatments facet on Providers archive/list
		$facet_labels['treatments_procedures'] = $treatments_plural_name;
		$facet_labels['treatments_procedures_attr'] = $treatments_plural_name_attr;

		// Add item to FacetWP labels array for Treatments facet on Clinical Resources archive/list
		$facet_labels['resource_treatments'] = $treatments_plural_name;
		$facet_labels['resource_treatments_attr'] = $treatments_plural_name_attr;
	}

	// Get system settings for treatment archive page text
	function uamswp_fad_archive_treatment() {
		// Make variables available outside of the function
		global $treatments_archive_headline;
		global $treatments_archive_headline_attr;
		global $treatments_archive_intro_text;
		global $placeholder_treatments_archive_headline;
		global $placeholder_treatments_archive_intro_text;

		$treatments_archive_headline = get_field('treatments_archive_headline', 'option') ?: 'Treatments and Procedures';
		$treatments_archive_headline_attr = uamswp_attr_conversion($treatments_archive_headline);
		$treatments_archive_intro_text = get_field('treatments_archive_intro_text', 'option');

		// Define string used to find and replace with values from Find-a-Doc Settings
		$placeholder_treatments_archive_headline = '[Treatment Archive Title]';
		$placeholder_treatments_archive_intro_text = '[Treatment Archive Intro Text]';
	}

// Define variables for ontology text elements on fake subpages and single profiles

	// Create substitutions for use in fake subpage text elements
	function uamswp_fad_fpage_text_replace($string) {
		// Be sure to only call this function AFTER the following external global variables have been defined on the page

		// Bring in variables from outside of the function

			// Typically defined on the template: single location, single area of expertise
			global $page_title;

			// Typically defined on the template: single location
			global $page_title_phrase;

			// Typically defined on the template: single provider
			global $short_name;
			global $short_name_possessive;

			// Defined in uamswp_fad_labels_provider()
			global $placeholder_provider_single_name;
			global $provider_single_name;
			global $placeholder_provider_plural_name;
			global $provider_plural_name;
			global $placeholder_provider_short_name;
			global $placeholder_provider_short_name_possessive;

			// Defined in uamswp_fad_archive_provider()
			global $placeholder_provider_archive_headline;
			global $provider_archive_headline;

			// Defined in uamswp_fad_labels_location()
			global $placeholder_location_single_name;
			global $location_single_name;
			global $placeholder_location_plural_name;
			global $location_plural_name;
			global $placeholder_location_page_title;
			global $placeholder_location_page_title_phrase;

			// Defined in uamswp_fad_labels_location_descendant()
			global $placeholder_location_descendant_single_name;
			global $location_descendant_single_name;
			global $placeholder_location_descendant_plural_name;
			global $location_descendant_plural_name;

			// Defined in uamswp_fad_archive_location()
			global $placeholder_location_archive_headline;
			global $location_archive_headline;

			// Defined in uamswp_fad_labels_expertise()
			global $placeholder_expertise_single_name;
			global $expertise_single_name;
			global $placeholder_expertise_plural_name;
			global $expertise_plural_name;
			global $placeholder_expertise_page_title;

			// Defined in uamswp_fad_labels_expertise_descendant()
			global $placeholder_expertise_descendant_single_name;
			global $expertise_descendant_single_name;
			global $placeholder_expertise_descendant_plural_name;
			global $expertise_descendant_plural_name;

			// Defined in uamswp_fad_archive_expertise()
			global $placeholder_expertise_archive_headline;
			global $expertise_archive_headline;
			global $placeholder_expertise_archive_intro_text;
			global $expertise_archive_intro_text;

			// Defined in uamswp_fad_labels_clinical_resource()
			global $placeholder_clinical_resource_single_name;
			global $clinical_resource_single_name;
			global $placeholder_clinical_resource_plural_name;
			global $clinical_resource_plural_name;

			// Defined in uamswp_fad_archive_clinical_resource()
			global $placeholder_clinical_resource_archive_headline;
			global $clinical_resource_archive_headline;

			// Defined in uamswp_fad_labels_clinical_resource_facet()
			global $placeholder_clinical_resource_type_single_name;
			global $clinical_resource_type_single_name;
			global $placeholder_clinical_resource_type_plural_name;
			global $clinical_resource_type_plural_name;

			// Defined in uamswp_fad_labels_condition_treatment()
			global $placeholder_conditions_treatments_single_name;
			global $conditions_treatments_single_name;
			global $placeholder_conditions_treatments_plural_name;
			global $conditions_treatments_plural_name;

			// Defined in uamswp_fad_labels_condition()
			global $placeholder_conditions_single_name;
			global $conditions_single_name;
			global $placeholder_conditions_plural_name;
			global $conditions_plural_name;

			// Defined in uamswp_fad_archive_condition()
			global $placeholder_conditions_archive_headline;
			global $conditions_archive_headline;
			global $placeholder_conditions_archive_intro_text;
			global $conditions_archive_intro_text;

			// Defined in uamswp_fad_labels_treatment()
			global $placeholder_treatments_single_name;
			global $treatments_single_name;
			global $placeholder_treatments_plural_name;
			global $treatments_plural_name;

			// Defined in uamswp_fad_archive_treatment()
			global $placeholder_treatments_archive_headline;
			global $treatments_archive_headline;
			global $placeholder_treatments_archive_intro_text;
			global $treatments_archive_intro_text;

		// Check variables
		$page_title = ( isset($page_title) && !empty($page_title) ) ? $page_title : '';
		$page_title_phrase = ( isset($page_title_phrase) && !empty($page_title_phrase) ) ? $page_title_phrase : '';
		$short_name = ( isset($short_name) && !empty($short_name) ) ? $short_name : '';
		$short_name_possessive = ( isset($short_name_possessive) && !empty($short_name_possessive) ) ? $short_name_possessive : '';

		// Make variables available outside of the function
		// global $fpage_text_replace;

		// Create array for defining text substitutions
		// Key = old
		// Value = new
		$fpage_text_replace = array();

			// System settings for ontology item labels

				// System settings for provider labels
				$fpage_text_replacements[$placeholder_provider_single_name] = $provider_single_name;
				$fpage_text_replacements[strtolower($placeholder_provider_single_name)] = strtolower($provider_single_name);
				$fpage_text_replacements[$placeholder_provider_plural_name] = $provider_plural_name;
				$fpage_text_replacements[strtolower($placeholder_provider_plural_name)] = strtolower($provider_plural_name);

				// System settings for provider archive page text
				$fpage_text_replacements[$placeholder_provider_archive_headline] = $provider_archive_headline;

				// System settings for location labels
				$fpage_text_replacements[$placeholder_location_single_name] = $location_single_name;
				$fpage_text_replacements[strtolower($placeholder_location_single_name)] = strtolower($location_single_name);
				$fpage_text_replacements[$placeholder_location_plural_name] = $location_plural_name;
				$fpage_text_replacements[strtolower($placeholder_location_plural_name)] = strtolower($location_plural_name);

				// System settings for location descendant item labels
				$fpage_text_replacements[$placeholder_location_descendant_single_name] = $location_descendant_single_name;
				$fpage_text_replacements[strtolower($placeholder_location_descendant_single_name)] = strtolower($location_descendant_single_name);
				$fpage_text_replacements[$placeholder_location_descendant_plural_name] = $location_descendant_plural_name;
				$fpage_text_replacements[strtolower($placeholder_location_descendant_plural_name)] = strtolower($location_descendant_plural_name);

				// System settings for location archive page text
				$fpage_text_replacements[$placeholder_location_archive_headline] = $location_archive_headline;

				// System settings for area of expertise labels
				$fpage_text_replacements[$placeholder_expertise_single_name] = $expertise_single_name;
				$fpage_text_replacements[strtolower($placeholder_expertise_single_name)] = strtolower($expertise_single_name);
				$fpage_text_replacements[$placeholder_expertise_plural_name] = $expertise_plural_name;
				$fpage_text_replacements[strtolower($placeholder_expertise_plural_name)] = strtolower($expertise_plural_name);

				// System settings for area of expertise descendant item labels
				$fpage_text_replacements[$placeholder_expertise_descendant_single_name] = $expertise_descendant_single_name;
				$fpage_text_replacements[strtolower($placeholder_expertise_descendant_single_name)] = strtolower($expertise_descendant_single_name);
				$fpage_text_replacements[$placeholder_expertise_descendant_plural_name] = $expertise_descendant_plural_name;
				$fpage_text_replacements[strtolower($placeholder_expertise_descendant_plural_name)] = strtolower($expertise_descendant_plural_name);

				// System settings for area of expertise archive page text
				$fpage_text_replacements[$placeholder_expertise_archive_headline] = $expertise_archive_headline;
				$fpage_text_replacements[$placeholder_expertise_archive_intro_text] = $expertise_archive_intro_text;

				// System settings for clinical resource labels
				$fpage_text_replacements[$placeholder_clinical_resource_single_name] = $clinical_resource_single_name;
				$fpage_text_replacements[strtolower($placeholder_clinical_resource_single_name)] = strtolower($clinical_resource_single_name);
				$fpage_text_replacements[$placeholder_clinical_resource_plural_name] = $clinical_resource_plural_name;
				$fpage_text_replacements[strtolower($placeholder_clinical_resource_plural_name)] = strtolower($clinical_resource_plural_name);

				// System settings for clinical resource archive page text
				$fpage_text_replacements[$placeholder_clinical_resource_archive_headline] = $clinical_resource_archive_headline;

				// System settings for clinical resource facet labels
				$fpage_text_replacements[$placeholder_clinical_resource_type_single_name] = $clinical_resource_type_single_name;
				$fpage_text_replacements[strtolower($placeholder_clinical_resource_type_single_name)] = strtolower($clinical_resource_type_single_name);
				$fpage_text_replacements[$placeholder_clinical_resource_type_plural_name] = $clinical_resource_type_plural_name;
				$fpage_text_replacements[strtolower($placeholder_clinical_resource_type_plural_name)] = strtolower($clinical_resource_type_plural_name);

				// System settings for combined conditions and treatments labels
				$fpage_text_replacements[$placeholder_conditions_treatments_single_name] = $conditions_treatments_single_name;
				$fpage_text_replacements[strtolower($placeholder_conditions_treatments_single_name)] = strtolower($conditions_treatments_single_name);
				$fpage_text_replacements[$placeholder_conditions_treatments_plural_name] = $conditions_treatments_plural_name;
				$fpage_text_replacements[strtolower($placeholder_conditions_treatments_plural_name)] = strtolower($conditions_treatments_plural_name);

				// System settings for condition labels
				$fpage_text_replacements[$placeholder_conditions_single_name] = $conditions_single_name;
				$fpage_text_replacements[strtolower($placeholder_conditions_single_name)] = strtolower($conditions_single_name);
				$fpage_text_replacements[$placeholder_conditions_plural_name] = $conditions_plural_name;
				$fpage_text_replacements[strtolower($placeholder_conditions_plural_name)] = strtolower($conditions_plural_name);

				// System settings for condition archive page text
				$fpage_text_replacements[$placeholder_conditions_archive_headline] = $conditions_archive_headline;
				$fpage_text_replacements[$placeholder_conditions_archive_intro_text] = $conditions_archive_intro_text;

				// System settings for treatment labels
				$fpage_text_replacements[$placeholder_treatments_single_name] = $treatments_single_name;
				$fpage_text_replacements[strtolower($placeholder_treatments_single_name)] = strtolower($treatments_single_name);
				$fpage_text_replacements[$placeholder_treatments_plural_name] = $treatments_plural_name;
				$fpage_text_replacements[strtolower($placeholder_treatments_plural_name)] = strtolower($treatments_plural_name);

				// System settings for location labels
				$fpage_text_replacements[$placeholder_treatments_archive_headline] = $treatments_archive_headline;
				$fpage_text_replacements[$placeholder_treatments_archive_intro_text] = $treatments_archive_intro_text;

			// Ontology item titles

				// Provider titles
				if ( $short_name ) {
					$fpage_text_replacements[$placeholder_provider_short_name] = $short_name;
				}
				if ( $short_name_possessive ) {
					$fpage_text_replacements[$placeholder_provider_short_name_possessive] = $short_name_possessive;
				}

				// Location titles
				if ( $page_title ) {
					$fpage_text_replacements[$placeholder_location_page_title] = $page_title;
				}
				if ( $page_title_phrase ) {
					$fpage_text_replacements[$placeholder_location_page_title_phrase] = $page_title_phrase;
				}

				// Area of expertise titles
				if ( $page_title ) {
					$fpage_text_replacements[$placeholder_expertise_page_title] = $page_title;
				}

		return str_replace(array_keys($fpage_text_replacements), array_values($fpage_text_replacements), $string); 
	}

	// Get field values from Find-a-Doc Settings for ontology text elements in general placements

		// Get system settings for general values of ontology text elements on a fake subpage or section for Providers
		function uamswp_fad_provider_fpage_text_general() {
			// Make variables available outside of the function
			global $provider_fpage_title_general;
			global $provider_fpage_intro_general;

			// Get the system settings for the text elements in a general placement
			$provider_fpage_title_general = get_field('provider_fpage_title_general', 'option'); // Title
			$provider_fpage_intro_general = get_field('provider_fpage_intro_general', 'option'); // Intro text

			// If the variable is not set or is empty, set a hardcoded fallback value
			$provider_fpage_title_general = ( isset($provider_fpage_title_general) && !empty($provider_fpage_title_general) ) ? $provider_fpage_title_general : 'Related [Providers]'; // Title
			$provider_fpage_intro_general = ( isset($provider_fpage_intro_general) && !empty($provider_fpage_intro_general) ) ? $provider_fpage_intro_general : ''; // Intro text

			// Substitute placeholder text for relevant system settings value
			$provider_fpage_title_general = uamswp_fad_fpage_text_replace($provider_fpage_title_general); // Title
			$provider_fpage_intro_general = uamswp_fad_fpage_text_replace($provider_fpage_intro_general); // Intro text
		}

		// Get system settings for general values of ontology text elements on a fake subpage or section for Locations
		function uamswp_fad_location_fpage_text_general() {
			// Make variables available outside of the function
			global $location_fpage_title_general;
			global $location_fpage_intro_general;

			// Get the system settings for the text elements in a general placement
			$location_fpage_title_general = get_field('location_fpage_title_general', 'option'); // Title
			$location_fpage_intro_general = get_field('location_fpage_intro_general', 'option'); // Intro text

			// If the variable is not set or is empty, set a hardcoded fallback value
			$location_fpage_title_general = ( isset($location_fpage_title_general) && !empty($location_fpage_title_general) ) ? $location_fpage_title_general : 'Related [Locations]'; // Title
			$location_fpage_intro_general = ( isset($location_fpage_intro_general) && !empty($location_fpage_intro_general) ) ? $location_fpage_intro_general : ''; // Intro text

			// Substitute placeholder text for relevant system settings value
			$location_fpage_title_general = uamswp_fad_fpage_text_replace($location_fpage_title_general); // Title
			$location_fpage_intro_general = uamswp_fad_fpage_text_replace($location_fpage_intro_general); // Intro text
		}

		// Get system settings for general values of ontology text elements on a fake subpage or section for Areas of Expertise
		function uamswp_fad_expertise_fpage_text_general() {
			// Make variables available outside of the function
			global $expertise_fpage_title_general;
			global $expertise_fpage_intro_general;

			// Get the system settings for the text elements in a general placement
			$expertise_fpage_title_general = get_field('expertise_fpage_title_general', 'option'); // Title
			$expertise_fpage_intro_general = get_field('expertise_fpage_intro_general', 'option'); // Intro text

			// If the variable is not set or is empty, set a hardcoded fallback value
			$expertise_fpage_title_general = ( isset($expertise_fpage_title_general) && !empty($expertise_fpage_title_general) ) ? $expertise_fpage_title_general : 'Related [Areas of Expertise]'; // Title
			$expertise_fpage_intro_general = ( isset($expertise_fpage_intro_general) && !empty($expertise_fpage_intro_general) ) ? $expertise_fpage_intro_general : ''; // Intro text

			// Substitute placeholder text for relevant system settings value
			$expertise_fpage_title_general = uamswp_fad_fpage_text_replace($expertise_fpage_title_general); // Title
			$expertise_fpage_intro_general = uamswp_fad_fpage_text_replace($expertise_fpage_intro_general); // Intro text
		}

		// Get system settings for general values of ontology text elements on a fake subpage or section for Clinical Resources
		function uamswp_fad_clinical_resource_fpage_text_general() {
			// Make variables available outside of the function
			global $clinical_resource_fpage_title_general;
			global $clinical_resource_fpage_intro_general;

			// Get the system settings for the text elements in a general placement
			$clinical_resource_fpage_title_general = get_field('clinical_resource_fpage_title_general', 'option'); // Title
			$clinical_resource_fpage_intro_general = get_field('clinical_resource_fpage_intro_general', 'option'); // Intro text

			// If the variable is not set or is empty, set a hardcoded fallback value
			$clinical_resource_fpage_title_general = ( isset($clinical_resource_fpage_title_general) && !empty($clinical_resource_fpage_title_general) ) ? $clinical_resource_fpage_title_general : 'Related [Clinical Resources]'; // Title
			$clinical_resource_fpage_intro_general = ( isset($clinical_resource_fpage_intro_general) && !empty($clinical_resource_fpage_intro_general) ) ? $clinical_resource_fpage_intro_general : ''; // Intro text

			// Substitute placeholder text for relevant system settings value
			$clinical_resource_fpage_title_general = uamswp_fad_fpage_text_replace($clinical_resource_fpage_title_general); // Title
			$clinical_resource_fpage_intro_general = uamswp_fad_fpage_text_replace($clinical_resource_fpage_intro_general); // Intro text
		}

		// Get system settings for general values of ontology text elements on a fake subpage or section for Conditions
		function uamswp_fad_condition_fpage_text_general() {
			// Make variables available outside of the function
			global $condition_fpage_title_general;
			global $condition_fpage_intro_general;

			// Get the system settings for the text elements in a general placement
			$condition_fpage_title_general = get_field('conditions_fpage_title_general', 'option'); // Title
			$condition_fpage_intro_general = get_field('conditions_fpage_intro_general', 'option'); // Intro text

			// If the variable is not set or is empty, set a hardcoded fallback value
			$condition_fpage_title_general = ( isset($condition_fpage_title_general) && !empty($condition_fpage_title_general) ) ? $condition_fpage_title_general : 'Related [Conditions]'; // Title
			$condition_fpage_intro_general = ( isset($condition_fpage_intro_general) && !empty($condition_fpage_intro_general) ) ? $condition_fpage_intro_general : 'UAMS Health [providers] care for a broad range of [conditions], some of which may not be listed below.'; // Intro text

			// Substitute placeholder text for relevant system settings value
			$condition_fpage_title_general = uamswp_fad_fpage_text_replace($condition_fpage_title_general); // Title
			$condition_fpage_intro_general = uamswp_fad_fpage_text_replace($condition_fpage_intro_general); // Intro text
		}

		// Get system settings for general values of ontology text elements on a fake subpage or section for Treatments
		function uamswp_fad_treatment_fpage_text_general() {
			// Make variables available outside of the function
			global $treatment_fpage_title_general;
			global $treatment_fpage_intro_general;

			// Get the system settings for the text elements in a general placement
			$treatment_fpage_title_general = get_field('treatments_fpage_title_general', 'option'); // Title
			$treatment_fpage_intro_general = get_field('treatments_fpage_intro_general', 'option'); // Intro text

			// If the variable is not set or is empty, set a hardcoded fallback value
			$treatment_fpage_title_general = ( isset($treatment_fpage_title_general) && !empty($treatment_fpage_title_general) ) ? $treatment_fpage_title_general : 'Related [Treatments]'; // Title
			$treatment_fpage_intro_general = ( isset($treatment_fpage_intro_general) && !empty($treatment_fpage_intro_general) ) ? $treatment_fpage_intro_general : 'UAMS Health [providers] perform and prescribe a broad range of [treatments], some of which may not be listed below.'; // Intro text

			// Substitute placeholder text for relevant system settings value
			$treatment_fpage_title_general = uamswp_fad_fpage_text_replace($treatment_fpage_title_general); // Title
			$treatment_fpage_intro_general = uamswp_fad_fpage_text_replace($treatment_fpage_intro_general); // Intro text
		}

	// Get field values from Find-a-Doc Settings and from ontology items for ontology text elements in specific subsections and single profiles

		// Get field values for fake subpage text elements on Provider subsection
		function uamswp_fad_fpage_text_provider() {
			// Make variables available outside of the function
			global $location_fpage_title_provider;
			global $location_fpage_intro_provider;
			global $expertise_fpage_title_provider;
			global $expertise_fpage_intro_provider;
			global $clinical_resource_fpage_title_provider;
			global $clinical_resource_fpage_intro_provider;
			global $condition_fpage_title_provider;
			global $condition_fpage_intro_provider;
			global $treatment_fpage_title_provider;
			global $treatment_fpage_intro_provider;
			global $placeholder_short_name;
			global $placeholder_short_name_possessive;

			// Locations
			$location_fpage_title_provider = get_field('location_fpage_title_provider', 'option') ?: '[Locations] Where [Provider Short Name] Practices'; // Title of Fake Subpage for Locations in Provider Subsection
				$location_fpage_title_provider = $location_fpage_title_provider ? uamswp_fad_fpage_text_replace($location_fpage_title_provider) : ''; // Substitute placeholder text for relevant system settings value
			$location_fpage_intro_provider = get_field('location_fpage_intro_provider', 'option') ?: ( get_field('location_fpage_intro_general', 'option') ?: '' ); // Intro Text of Fake Subpage for Locations in Provider Subsection
				$location_fpage_intro_provider = $location_fpage_intro_provider ? uamswp_fad_fpage_text_replace($location_fpage_intro_provider) : ''; // Substitute placeholder text for relevant system settings value

			// Areas of Expertise
			$expertise_fpage_title_provider = get_field('expertise_fpage_title_provider', 'option') ?: '[Provider Short Name\'s] [Areas of Expertise]'; // Title of Fake Subpage for Areas of Expertise in Provider Subsection
				$expertise_fpage_title_provider = $expertise_fpage_title_provider ? uamswp_fad_fpage_text_replace($expertise_fpage_title_provider) : ''; // Substitute placeholder text for relevant system settings value
			$expertise_fpage_intro_provider = get_field('expertise_fpage_intro_provider', 'option') ?: ( get_field('expertise_fpage_intro_general', 'option') ?: '' ); // Intro Text of Fake Subpage for Areas of Expertise in Provider Subsection
				$expertise_fpage_intro_provider = $expertise_fpage_intro_provider ? uamswp_fad_fpage_text_replace($expertise_fpage_intro_provider) : ''; // Substitute placeholder text for relevant system settings value

			// Clinical Resources
			$clinical_resource_fpage_title_provider = get_field('clinical_resource_fpage_title_provider', 'option') ?: '[Clinical Resources] Related to [Provider Short Name]'; // Title of Fake Subpage for Clinical Resources in Provider Subsection
				$clinical_resource_fpage_title_provider = $clinical_resource_fpage_title_provider ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_title_provider) : ''; // Substitute placeholder text for relevant system settings value
			$clinical_resource_fpage_intro_provider = get_field('clinical_resource_fpage_intro_provider', 'option') ?: ( get_field('clinical_resource_fpage_intro_general', 'option') ?: '' ); // Intro Text of Fake Subpage for Clinical Resources in Provider Subsection
				$clinical_resource_fpage_intro_provider = $clinical_resource_fpage_intro_provider ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_intro_provider) : ''; // Substitute placeholder text for relevant system settings value

			// Conditions
			$condition_fpage_title_provider = get_field('conditions_fpage_title_provider', 'option') ?: '[Conditions] Diagnosed or Treated by [Provider Short Name]'; // Title of Fake Subpage for Conditions in Provider Subsection
				$condition_fpage_title_provider = $condition_fpage_title_provider ? uamswp_fad_fpage_text_replace($condition_fpage_title_provider) : ''; // Substitute placeholder text for relevant system settings value
			$condition_fpage_intro_provider = get_field('conditions_fpage_intro_provider', 'option') ?: ( get_field('conditions_fpage_intro_general', 'option') ?: '' ); // Intro Text of Fake Subpage for Conditions in Provider Subsection
				$condition_fpage_intro_provider = $condition_fpage_intro_provider ? uamswp_fad_fpage_text_replace($condition_fpage_intro_provider) : ''; // Substitute placeholder text for relevant system settings value

			// Treatments
			$treatment_fpage_title_provider = get_field('treatments_fpage_title_provider', 'option') ?: '[Treatments] Performed or Prescribed by [Provider Short Name]'; // Title of Fake Subpage for Treatments in Provider Subsection
				$treatment_fpage_title_provider = $treatment_fpage_title_provider ? uamswp_fad_fpage_text_replace($treatment_fpage_title_provider) : ''; // Substitute placeholder text for relevant system settings value
			$treatment_fpage_intro_provider = get_field('treatments_fpage_intro_provider', 'option') ?: ( get_field('treatments_fpage_intro_general', 'option') ?: '' ); // Intro Text of Fake Subpage for Treatments in Provider Subsection
				$treatment_fpage_intro_provider = $treatment_fpage_intro_provider ? uamswp_fad_fpage_text_replace($treatment_fpage_intro_provider) : ''; // Substitute placeholder text for relevant system settings value
		}

		// Get field values for fake subpage text elements on Location subsection
		function uamswp_fad_fpage_text_location() {
			// Make variables available outside of the function
			global $provider_fpage_title_location;
			global $provider_fpage_intro_location;
			global $expertise_fpage_title_location;
			global $expertise_fpage_intro_location;
			global $clinical_resource_fpage_title_location;
			global $clinical_resource_fpage_intro_location;
			global $condition_fpage_title_location;
			global $condition_fpage_intro_location;
			global $treatment_fpage_title_location;
			global $treatment_fpage_intro_location;

			// Providers
			$provider_fpage_title_location = get_field('provider_fpage_title_location', 'option') ?: '[Providers] at [Location Title]'; // Title of Fake Subpage for Providers in Locations Subsection
				$provider_fpage_title_location = $provider_fpage_title_location ? uamswp_fad_fpage_text_replace($provider_fpage_title_location) : ''; // Substitute placeholder text for relevant system settings value
			$provider_fpage_intro_location = get_field('provider_fpage_intro_location', 'option') ?: ( get_field('provider_fpage_intro_general', 'option') ?: '' ); // Intro Text of Fake Subpage for Providers in Location Subsection
				$provider_fpage_intro_location = $provider_fpage_intro_location ? uamswp_fad_fpage_text_replace($provider_fpage_intro_location) : ''; // Substitute placeholder text for relevant system settings value

			// Areas of Expertise
			$expertise_fpage_title_location = get_field('expertise_fpage_title_location', 'option') ?: '[Areas of Expertise] Represented at [the Location Title]'; // Title of Fake Subpage for Areas of Expertise in Location Subsection
				$expertise_fpage_title_location = $expertise_fpage_title_location ? uamswp_fad_fpage_text_replace($expertise_fpage_title_location) : ''; // Substitute placeholder text for relevant system settings value
			$expertise_fpage_intro_location = get_field('expertise_fpage_intro_location', 'option') ?: ( get_field('expertise_fpage_intro_general', 'option') ?: '' ); // Intro Text of Fake Subpage for Areas of Expertise in Location Subsection
				$expertise_fpage_intro_location = $expertise_fpage_intro_location ? uamswp_fad_fpage_text_replace($expertise_fpage_intro_location) : ''; // Substitute placeholder text for relevant system settings value

			// Clinical Resources
			$clinical_resource_fpage_title_location = get_field('clinical_resource_fpage_title_location', 'option') ?: '[Clinical Resources] Related to [the Location Title]'; // Title of Fake Subpage for Clinical Resources in Location Subsection
				$clinical_resource_fpage_title_location = $clinical_resource_fpage_title_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_title_location) : ''; // Substitute placeholder text for relevant system settings value
			$clinical_resource_fpage_intro_location = get_field('clinical_resource_fpage_intro_location', 'option') ?: ( get_field('clinical_resource_fpage_intro_general', 'option') ?: '' ); // Intro Text of Fake Subpage for Clinical Resources in Location Subsection
				$clinical_resource_fpage_intro_location = $clinical_resource_fpage_intro_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_intro_location) : ''; // Substitute placeholder text for relevant system settings value

			// Conditions
			$condition_fpage_title_location = get_field('conditions_fpage_title_location', 'option') ?: '[Conditions] Diagnosed or Treated at [the Location Title]'; // Title of Fake Subpage for Conditions in Location Subsection
				$condition_fpage_title_location = $condition_fpage_title_location ? uamswp_fad_fpage_text_replace($condition_fpage_title_location) : ''; // Substitute placeholder text for relevant system settings value
			$condition_fpage_intro_location = get_field('conditions_fpage_intro_location', 'option') ?: ( get_field('conditions_fpage_intro_general', 'option') ?: '' ); // Intro Text of Fake Subpage for Conditions in Location Subsection
				$condition_fpage_intro_location = $condition_fpage_intro_location ? uamswp_fad_fpage_text_replace($condition_fpage_intro_location) : ''; // Substitute placeholder text for relevant system settings value

			// Treatments
			$treatment_fpage_title_location = get_field('treatments_fpage_title_location', 'option') ?: '[Treatments] Performed or Prescribed at [the Location Title]'; // Title of Fake Subpage for Treatments in Location Subsection
				$treatment_fpage_title_location = $treatment_fpage_title_location ? uamswp_fad_fpage_text_replace($treatment_fpage_title_location) : ''; // Substitute placeholder text for relevant system settings value
			$treatment_fpage_intro_location = get_field('treatments_fpage_intro_location', 'option') ?: ( get_field('treatments_fpage_intro_general', 'option') ?: '' ); // Intro Text of Fake Subpage for Treatments in Location Subsection
				$treatment_fpage_intro_location = $treatment_fpage_intro_location ? uamswp_fad_fpage_text_replace($treatment_fpage_intro_location) : ''; // Substitute placeholder text for relevant system settings value
		}

		// Get field values for fake subpage text elements on Area of Expertise subsection
		function uamswp_fad_fpage_text_expertise() {
			// Bring in variables from outside of the function
			global $page_id;
			global $page_title;
			global $ontology_type;

			// Make variables available outside of the function
			global $expertise_page_title_options;
			global $expertise_page_title;
			global $expertise_page_intro;
			global $expertise_page_image;
			global $expertise_page_image_mobile;
			global $expertise_short_desc;
			global $expertise_featured_image;
			global $expertise_featured_image_id;
			global $provider_fpage_title_expertise;
			global $provider_fpage_intro_expertise;
			global $providers_fpage_short_desc_expertise;
			global $provider_fpage_featured_image_expertise;
			global $provider_fpage_featured_image_expertise_id;
			global $location_fpage_title_expertise;
			global $location_fpage_intro_expertise;
			global $locations_fpage_short_desc_expertise;
			global $location_fpage_featured_image_expertise;
			global $location_fpage_featured_image_expertise_id;
			global $expertise_descendant_fpage_title_expertise;
			global $expertise_descendant_fpage_intro_expertise;
			global $expertise_descendant_fpage_short_desc_expertise;
			global $expertise_descendant_fpage_featured_image_expertise;
			global $expertise_descendant_fpage_featured_image_expertise_id;
			global $expertise_fpage_title_expertise;
			global $expertise_fpage_intro_expertise;
			global $expertise_fpage_short_desc_expertise;
			global $expertise_fpage_featured_image_expertise;
			global $expertise_fpage_featured_image_expertise_id;
			global $clinical_resource_fpage_title_expertise;
			global $clinical_resource_fpage_intro_expertise;
			global $clinical_resources_fpage_short_desc_expertise;
			global $clinical_resource_fpage_featured_image_expertise;
			global $clinical_resource_fpage_featured_image_expertise_id;
			global $condition_fpage_title_expertise;
			global $condition_fpage_intro_expertise;
			global $treatment_fpage_title_expertise;
			global $treatment_fpage_intro_expertise;

			// Overview

				// Get the field values from the current Area of Expertise text elements on the homepage of that Area of Expertise's subsection

					// Get the page header style
					if ( $ontology_type ) {

						// If the page is an ontology item, set the page header style as the Marketing Landing Page Header Style 
						$expertise_page_title_options = 'landingpage'; // Page Header Style

					} else {

						// Otherwise, get the page header style selector value
						$expertise_page_title_options = get_field('expertise_page_title_options');

					}

					// Create the variables used for setting the page header text element values
					$expertise_page_header_graphic = ''; // Graphic Header Style Options
					$expertise_page_header_landingpage = ''; // Marketing Landing Page Header Style Options
					$expertise_page_header_hero = ''; // Hero Header Style Options

					// Set the page header text element values
					if ( $expertise_page_title_options == 'graphic' ) {

						// Get Graphic Header Style Options
						$expertise_page_header_graphic = get_field('expertise_page_title_graphic')['page_header_graphic'];

						// Set the standard page title as the title value
						$expertise_page_title = $page_title;

						// Get the intro text value
						$expertise_page_intro = $expertise_page_header_graphic['page_header_graphic_intro']; // Intro text

						// Get the background image value
						$expertise_page_image = $expertise_page_header_graphic['page_header_graphic_image']; // Background image (mobile)

					} elseif ( $expertise_page_title_options == 'landingpage' ) {

						// Get Marketing Landing Page Header Style Options
						$expertise_page_header_landingpage = get_field('expertise_page_title_landingpage')['page_header_landingpage'];

						// Get the title value
						$expertise_page_title = $expertise_page_header_landingpage['page_header_landingpage_title']; 

						// If the title is not set or is empty, use the standard page title as the fallback value
						$expertise_page_title = ( isset($expertise_page_title) && !empty($expertise_page_title) ) ? $expertise_page_title : $page_title;

						// Get the intro text value
						$expertise_page_intro = $expertise_page_header_landingpage['page_header_landingpage_intro'];

						// Get the background image values
						$expertise_page_image = $expertise_page_header_landingpage['page_header_landingpage_image']; // Background image (desktop)
						$expertise_page_image_mobile = $expertise_page_header_landingpage['page_header_landingpage_image_mobile']; // Background image (mobile)

						// If the ontology item's desktop background image is not set or is empty, use the featured image as the fallback value
						if ( ( !isset($expertise_page_image) || empty($expertise_page_image) ) && $ontology_type ) {
							$expertise_page_image = get_post_thumbnail_id($page_id); // Background image (desktop)
							$expertise_page_image_mobile = ''; // Background image (mobile)
						}
						
					} elseif ( $expertise_page_title_options == 'hero' ) {

						// Get Hero Header Style Options
						$expertise_page_header_hero = get_field('expertise_page_title_hero')['page_header_hero']; // Hero Header Style Options

						// Set the standard page title as the title value
						$expertise_page_title = $page_title;

						// Set the intro text value
						$expertise_page_intro = '';

					} else {

						// Set the standard page title as the title value
						$expertise_page_title = $page_title;

						// Set the intro text value
						$expertise_page_intro = '';

					}

				// Set the short description / meta description value

					// Check whether to use this area of expertise's intro text as the short description for main page
					$expertise_short_desc_query = get_field('expertise_short_desc_query'); // If true, use intro text. If false, use specific short description.

						// Define a value if the item has not been updated since 'expertise_short_desc_query' was added
						$expertise_short_desc_query = isset($expertise_short_desc_query) ? $expertise_short_desc_query : true; // Define a value if the item has not been updated since 'expertise_short_desc_query' was added

					if ( $expertise_short_desc_query ) {

						// If the query is true, use the intro text to set the short description
						$expertise_short_desc = $expertise_page_intro;

					} else {

						// Otherwise, get the short description input value
						$expertise_short_desc = get_field('expertise_short_desc');

						// Substitute placeholder text for the relevant system settings value
						$expertise_short_desc = ( isset($expertise_short_desc) && !empty($expertise_short_desc) ) ? uamswp_fad_fpage_text_replace($expertise_short_desc) : $expertise_short_desc;

						// If the short description is not set or is empty, use the intro text as a fallback value
						$expertise_short_desc = ( isset($expertise_short_desc) && !empty($expertise_short_desc) ) ? $expertise_short_desc : $expertise_page_intro;

					}

				// Get the featured image value
				$expertise_featured_image_id = get_field('_thumbnail_id');
				if ( $expertise_featured_image && function_exists( 'fly_add_image_size' ) ) {
					$expertise_featured_image = image_sizer($expertise_featured_image_id, 1600, 900, 'center', 'center');
				} elseif ( $expertise_featured_image ) {
					$expertise_featured_image = wp_get_attachment_url( $expertise_featured_image_id, 'aspect-16-9' );
				} else {
					$expertise_featured_image = '';
				}

			// Providers

				// Page-level settings specific to an individual Area of Expertise

					// Get the field values from the current Area of Expertise ontology item for ontology text elements related to Providers in that Area of Expertise's subsection
					// $provider_fpage_title_expertise = get_field('expertise_providers_fpage_title'); // Title
					$provider_fpage_intro_expertise = get_field('expertise_providers_fpage_intro'); // Intro text

				// Find-a-Doc Settings general to all Area of Expertise subsections

					// If the Providers title variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($provider_fpage_title_expertise) || empty($provider_fpage_title_expertise) ) {
						$provider_fpage_title_expertise = get_field('provider_fpage_title_expertise', 'option'); // Title
					}

					// If the Providers intro text variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($provider_fpage_intro_expertise) || empty($provider_fpage_intro_expertise) ) {
						$provider_fpage_intro_expertise = get_field('provider_fpage_intro_expertise', 'option'); // Intro text
					}

				// Hardcoded fallbacks specific to Area of Expertise subsections

					// If the Providers title text variable still is not set or is empty...
					// set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					if ( !isset($provider_fpage_title_expertise) || empty($provider_fpage_title_expertise) ) {
						$provider_fpage_title_expertise = '[Area of Expertise Title] [Providers]';
					}

					// // If the Providers intro text variable still is not set or is empty...
					// // set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					// if ( !isset($provider_fpage_intro_expertise) || empty($provider_fpage_intro_expertise) ) {
					// 	$provider_fpage_intro_expertise = '';
					// }

				// Conditionally substitute any placeholder text for relevant system settings value
				$provider_fpage_title_expertise = ( isset($provider_fpage_title_expertise) && !empty($provider_fpage_title_expertise) ) ? uamswp_fad_fpage_text_replace($provider_fpage_title_expertise) : $provider_fpage_title_expertise; // Title
				$provider_fpage_intro_expertise = ( isset($provider_fpage_intro_expertise) && !empty($provider_fpage_intro_expertise) ) ? uamswp_fad_fpage_text_replace($provider_fpage_intro_expertise) : $provider_fpage_intro_expertise; // Intro text

				// Find-a-Doc Settings for general placements

					// If the Providers title variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element in general placements
					if ( !isset($provider_fpage_title_expertise) || empty($provider_fpage_title_expertise) ) {
						uamswp_fad_provider_fpage_text_general();
						global $provider_fpage_title_general;
						$provider_fpage_title_expertise = $provider_fpage_title_general;
					}

					// If the Providers intro text variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element in general placements
					if ( !isset($provider_fpage_intro_expertise) || empty($provider_fpage_intro_expertise) ) {
						uamswp_fad_provider_fpage_text_general();
						global $provider_fpage_intro_general;
						$provider_fpage_intro_expertise = $provider_fpage_intro_general;
					}

				// Get value for meta description
				$providers_fpage_short_desc_query_expertise = get_field('expertise_providers_fpage_short_desc_query'); // If true, use intro text. If false, use specific short description.
				$providers_fpage_short_desc_query_expertise = isset($providers_fpage_short_desc_query_expertise) ? $providers_fpage_short_desc_query_expertise : true; // Define a value if the item has not been updated since 'expertise_providers_fpage_short_desc_query' was added
				if ( $providers_fpage_short_desc_query_expertise ) {
					$providers_fpage_short_desc_expertise = $provider_fpage_intro_expertise;
				} else {
					$providers_fpage_short_desc_expertise = get_field('expertise_providers_fpage_short_desc');
					$providers_fpage_short_desc_expertise = ( isset($providers_fpage_short_desc_expertise) && !empty($providers_fpage_short_desc_expertise) ) ? uamswp_fad_fpage_text_replace($providers_fpage_short_desc_expertise) : $providers_fpage_short_desc_expertise; // Substitute placeholder text for relevant system settings value
					$providers_fpage_short_desc_expertise = ( isset($providers_fpage_short_desc_expertise) && !empty($providers_fpage_short_desc_expertise) ) ? $providers_fpage_short_desc_expertise : $provider_fpage_intro_expertise; // If there is no value, use intro text as a fallback value
				}

				// Get the featured image value
				$provider_fpage_featured_image_expertise_id = get_field('expertise_providers_fpage_featured_image');
				if ( $provider_fpage_featured_image_expertise && function_exists( 'fly_add_image_size' ) ) {
					$provider_fpage_featured_image_expertise = image_sizer($provider_fpage_featured_image_expertise_id, 1600, 900, 'center', 'center');
				} elseif ( $provider_fpage_featured_image_expertise ) {
					$provider_fpage_featured_image_expertise = wp_get_attachment_url( $provider_fpage_featured_image_expertise_id, 'aspect-16-9' );
				} else {
					$provider_fpage_featured_image_expertise = $expertise_featured_image;
				}

			// Locations

				// Page-level settings specific to an individual Area of Expertise

					// Get the field values from the current Area of Expertise ontology item for ontology text elements related to Locations in that Area of Expertise's subsection
					// $location_fpage_title_expertise = get_field('expertise_locations_fpage_title'); // Title
					$location_fpage_intro_expertise = get_field('expertise_locations_fpage_intro'); // Intro text

				// Find-a-Doc Settings general to all Area of Expertise subsections

					// If the Locations title variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($location_fpage_title_expertise) || empty($location_fpage_title_expertise) ) {
						$location_fpage_title_expertise = get_field('location_fpage_title_expertise', 'option'); // Title
					}

					// If the Locations intro text variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($location_fpage_intro_expertise) || empty($location_fpage_intro_expertise) ) {
						$location_fpage_intro_expertise = get_field('location_fpage_intro_expertise', 'option'); // Intro text
					}

				// Hardcoded fallbacks specific to Area of Expertise subsections

					// If the Locations title variable is not set or is empty...
					// set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					if ( !isset($location_fpage_title_expertise) || empty($location_fpage_title_expertise) ) {
						$location_fpage_title_expertise = '[Area of Expertise Title] [Locations]';
					}

					// // If the Locations intro text variable is not set or is empty...
					// // set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					// if ( !isset($location_fpage_intro_expertise) || empty($location_fpage_intro_expertise) ) {
					// 	$location_fpage_intro_expertise = '';
					// }

				// Conditionally substitute any placeholder text for relevant system settings value
				$location_fpage_title_expertise = ( isset($location_fpage_title_expertise) && !empty($location_fpage_title_expertise) ) ? uamswp_fad_fpage_text_replace($location_fpage_title_expertise) : $location_fpage_title_expertise; // Title
				$location_fpage_intro_expertise = ( isset($location_fpage_intro_expertise) && !empty($location_fpage_intro_expertise) ) ? uamswp_fad_fpage_text_replace($location_fpage_intro_expertise) : $location_fpage_intro_expertise; // Intro text

				// Find-a-Doc Settings for general placements

					// If the Locations title variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element in general placements
					if ( !isset($location_fpage_title_expertise) || empty($location_fpage_title_expertise) ) {
						uamswp_fad_location_fpage_text_general();
						global $location_fpage_title_general;
						$location_fpage_title_expertise = $location_fpage_title_general;
					}

					// If the Locations intro text variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element in general placements
					if ( !isset($location_fpage_intro_expertise) || empty($location_fpage_intro_expertise) ) {
						uamswp_fad_location_fpage_text_general();
						global $location_fpage_intro_general;
						$location_fpage_intro_expertise = $location_fpage_intro_general;
					}

				// Get value for meta description
				$locations_fpage_short_desc_query_expertise = get_field('expertise_locations_fpage_short_desc_query'); // If true, use intro text. If false, use specific short description.
				$locations_fpage_short_desc_query_expertise = isset($locations_fpage_short_desc_query_expertise) ? $locations_fpage_short_desc_query_expertise : true; // Define a value if the item has not been updated since 'expertise_locations_fpage_short_desc_query' was added
				if ( $locations_fpage_short_desc_query_expertise ) {
					$locations_fpage_short_desc_expertise = $location_fpage_intro_expertise;
				} else {
					$locations_fpage_short_desc_expertise = get_field('expertise_locations_fpage_short_desc');
					$locations_fpage_short_desc_expertise = ( isset($locations_fpage_short_desc_expertise) && !empty($locations_fpage_short_desc_expertise) ) ? uamswp_fad_fpage_text_replace($locations_fpage_short_desc_expertise) : $locations_fpage_short_desc_expertise; // Substitute placeholder text for relevant system settings value
					$locations_fpage_short_desc_expertise = ( isset($locations_fpage_short_desc_expertise) && !empty($locations_fpage_short_desc_expertise) ) ? $locations_fpage_short_desc_expertise : $location_fpage_intro_expertise; // If there is no value, use intro text as a fallback value
				}

				// Get the featured image value
				$location_fpage_featured_image_expertise_id = get_field('expertise_locations_fpage_featured_image');
				if ( $location_fpage_featured_image_expertise && function_exists( 'fly_add_image_size' ) ) {
					$location_fpage_featured_image_expertise = image_sizer($location_fpage_featured_image_expertise_id, 1600, 900, 'center', 'center');
				} elseif ( $location_fpage_featured_image_expertise ) {
					$location_fpage_featured_image_expertise = wp_get_attachment_url( $location_fpage_featured_image_expertise_id, 'aspect-16-9' );
				} else {
					$location_fpage_featured_image_expertise = $expertise_featured_image;
				}

			// Descendant Areas of Expertise

				// Page-level settings specific to an individual Area of Expertise

					// Get the field values from the current Area of Expertise ontology item for ontology text elements related to Descendant Areas of Expertise in that Area of Expertise's subsection
					// $expertise_descendant_fpage_title_expertise = get_field('expertise_descendant_fpage_title'); // Title
					$expertise_descendant_fpage_intro_expertise = get_field('expertise_descendant_fpage_intro'); // Intro text

				// Find-a-Doc Settings general to all Area of Expertise subsections

					// If the Descendant Areas of Expertise title variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($expertise_descendant_fpage_title_expertise) || empty($expertise_descendant_fpage_title_expertise) ) {
						$expertise_descendant_fpage_title_expertise = get_field('expertise_descendant_fpage_title_expertise', 'option'); // Title
					}

					// If the Descendant Areas of Expertise intro text variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($expertise_descendant_fpage_intro_expertise) || empty($expertise_descendant_fpage_intro_expertise) ) {
						$expertise_descendant_fpage_intro_expertise = get_field('expertise_descendant_fpage_intro_expertise', 'option'); // Intro text
					}

				// Hardcoded fallbacks specific to Area of Expertise subsections

					// If the Descendant Areas of Expertise title variable is not set or is empty...
					// set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					if ( !isset($expertise_descendant_fpage_title_expertise) || empty($expertise_descendant_fpage_title_expertise) ) {
						$expertise_descendant_fpage_title_expertise = '[Descendant Areas of Expertise] Within [Area of Expertise Title]';
					}

					// // If the Descendant Areas of Expertise intro text variable is not set or is empty...
					// // set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					// if ( !isset($expertise_descendant_fpage_intro_expertise) || empty($expertise_descendant_fpage_intro_expertise) ) {
					// 	$expertise_descendant_fpage_intro_expertise = '';
					// }

				// Conditionally substitute any placeholder text for relevant system settings value
				$expertise_descendant_fpage_title_expertise = ( isset($expertise_descendant_fpage_title_expertise) && !empty($expertise_descendant_fpage_title_expertise) ) ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_title_expertise) : $expertise_descendant_fpage_title_expertise; // Title
				$expertise_descendant_fpage_intro_expertise = ( isset($expertise_descendant_fpage_intro_expertise) && !empty($expertise_descendant_fpage_intro_expertise) ) ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_intro_expertise) : $expertise_descendant_fpage_intro_expertise; // Intro text

				// Find-a-Doc Settings for general placements

					// If the Descendant Areas of Expertise title variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element in general placements
					if ( !isset($expertise_descendant_fpage_title_expertise) || empty($expertise_descendant_fpage_title_expertise) ) {
						uamswp_fad_expertise_fpage_text_general();
						global $expertise_fpage_title_general;
						$expertise_descendant_fpage_title_expertise = $expertise_fpage_title_general;
					}

					// If the Descendant Areas of Expertise intro text variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element in general placements
					if ( !isset($expertise_descendant_fpage_intro_expertise) || empty($expertise_descendant_fpage_intro_expertise) ) {
						uamswp_fad_expertise_fpage_text_general();
						global $expertise_fpage_intro_general;
						$expertise_descendant_fpage_intro_expertise = $expertise_fpage_intro_general;
					}

				// Get value for meta description
				$expertise_descendant_fpage_short_desc_query_expertise = get_field('expertise_descendant_fpage_short_desc_query'); // If true, use intro text. If false, use specific short description.
				$expertise_descendant_fpage_short_desc_query_expertise = isset($expertise_descendant_fpage_short_desc_query_expertise) ? $expertise_descendant_fpage_short_desc_query_expertise : true; // Define a value if the item has not been updated since 'expertise_descendant_fpage_short_desc_query' was added
				if ( $expertise_descendant_fpage_short_desc_query_expertise ) {
					$expertise_descendant_fpage_short_desc_expertise = $expertise_descendant_fpage_intro_expertise;
				} else {
					$expertise_descendant_fpage_short_desc_expertise = get_field('expertise_descendant_fpage_short_desc');
					$expertise_descendant_fpage_short_desc_expertise = ( isset($expertise_descendant_fpage_short_desc_expertise) && !empty($expertise_descendant_fpage_short_desc_expertise) ) ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_short_desc_expertise) : $expertise_descendant_fpage_short_desc_expertise; // Substitute placeholder text for relevant system settings value
					$expertise_descendant_fpage_short_desc_expertise = ( isset($expertise_descendant_fpage_short_desc_expertise) && !empty($expertise_descendant_fpage_short_desc_expertise) ) ? $expertise_descendant_fpage_short_desc_expertise : $expertise_descendant_fpage_intro_expertise; // If there is no value, use intro text as a fallback value
				}

				// Get the featured image value
				$expertise_descendant_fpage_featured_image_expertise_id = get_field('expertise_descendant_fpage_featured_image');
				if ( $expertise_descendant_fpage_featured_image_expertise && function_exists( 'fly_add_image_size' ) ) {
					$expertise_descendant_fpage_featured_image_expertise = image_sizer($expertise_descendant_fpage_featured_image_expertise_id, 1600, 900, 'center', 'center');
				} elseif ( $expertise_descendant_fpage_featured_image_expertise ) {
					$expertise_descendant_fpage_featured_image_expertise = wp_get_attachment_url( $expertise_descendant_fpage_featured_image_expertise_id, 'aspect-16-9' );
				} else {
					$expertise_descendant_fpage_featured_image_expertise = $expertise_featured_image;
				}

			// Related Areas of Expertise

				// Page-level settings specific to an individual Area of Expertise

					// Get the field values from the current Area of Expertise ontology item for ontology text elements related to Related Areas of Expertise in that Area of Expertise's subsection
					// $expertise_fpage_title_expertise = get_field('expertise_associated_fpage_title'); // Title
					$expertise_fpage_intro_expertise = get_field('expertise_associated_fpage_intro'); // Intro text

				// Find-a-Doc Settings general to all Area of Expertise subsections

					// If the Related Areas of Expertise title variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($expertise_fpage_title_expertise) || empty($expertise_fpage_title_expertise) ) {
						$expertise_fpage_title_expertise = get_field('expertise_fpage_title_expertise', 'option'); // Title
					}

					// If the Related Areas of Expertise intro text variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($expertise_fpage_intro_expertise) || empty($expertise_fpage_intro_expertise) ) {
						$expertise_fpage_intro_expertise = get_field('expertise_fpage_intro_expertise', 'option'); // Intro text
					}

				// Hardcoded fallbacks specific to Area of Expertise subsections

					// If the Related Areas of Expertise title variable is not set or is empty...
					// set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					if ( !isset($expertise_fpage_title_expertise) || empty($expertise_fpage_title_expertise) ) {
						$expertise_fpage_title_expertise = '[Areas of Expertise] Related to [Area of Expertise Title]';
					}

					// // If the Related Areas of Expertise intro text variable is not set or is empty...
					// // set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					// if ( !isset($expertise_fpage_intro_expertise) || empty($expertise_fpage_intro_expertise) ) {
					// 	$expertise_fpage_intro_expertise = '';
					// }

				// Conditionally substitute any placeholder text for relevant system settings value
				$expertise_fpage_title_expertise = ( isset($expertise_fpage_title_expertise) && !empty($expertise_fpage_title_expertise) ) ? uamswp_fad_fpage_text_replace($expertise_fpage_title_expertise) : $expertise_fpage_title_expertise; // Title
				$expertise_fpage_intro_expertise = ( isset($expertise_fpage_intro_expertise) && !empty($expertise_fpage_intro_expertise) ) ? uamswp_fad_fpage_text_replace($expertise_fpage_intro_expertise) : $expertise_fpage_intro_expertise; // Intro text

				// Find-a-Doc Settings for general placements

					// If the Related Areas of Expertise title variable is not set or is empty, set the fallback value by getting the system settings for general placement
					if ( !isset($expertise_fpage_title_expertise) || empty($expertise_fpage_title_expertise) ) {
						uamswp_fad_expertise_fpage_text_general();
						global $expertise_fpage_title_general;
						$expertise_fpage_title_expertise = $expertise_fpage_title_general;
					}

					// If the Related Areas of Expertise intro text variable is not set or is empty, set the fallback value by getting the system settings for general placement
					if ( !isset($expertise_fpage_intro_expertise) || empty($expertise_fpage_intro_expertise) ) {
						uamswp_fad_expertise_fpage_text_general();
						global $expertise_fpage_intro_general;
						$expertise_fpage_intro_expertise = $expertise_fpage_intro_general;
					}

				// Get value for meta description
				$expertise_fpage_short_desc_query_expertise = get_field('expertise_associated_fpage_short_desc_query'); // If true, use intro text. If false, use specific short description.
				$expertise_fpage_short_desc_query_expertise = isset($expertise_fpage_short_desc_query_expertise) ? $expertise_fpage_short_desc_query_expertise : true; // Define a value if the item has not been updated since 'expertise_associated_fpage_short_desc_query' was added
				if ( $expertise_fpage_short_desc_query_expertise ) {
					$expertise_fpage_short_desc_expertise = $expertise_fpage_intro_expertise;
				} else {
					$expertise_fpage_short_desc_expertise = get_field('expertise_associated_fpage_short_desc');
					$expertise_fpage_short_desc_expertise = ( isset($expertise_fpage_short_desc_expertise) && !empty($expertise_fpage_short_desc_expertise) ) ? uamswp_fad_fpage_text_replace($expertise_fpage_short_desc_expertise) : $expertise_fpage_short_desc_expertise; // Substitute placeholder text for relevant system settings value
					$expertise_fpage_short_desc_expertise = ( isset($expertise_fpage_short_desc_expertise) && !empty($expertise_fpage_short_desc_expertise) ) ? $expertise_fpage_short_desc_expertise : $expertise_fpage_intro_expertise; // If there is no value, use intro text as a fallback value
				}

				// Get the featured image value
				$expertise_fpage_featured_image_expertise_id = get_field('expertise_associated_fpage_featured_image');
				if ( $expertise_fpage_featured_image_expertise && function_exists( 'fly_add_image_size' ) ) {
					$expertise_fpage_featured_image_expertise = image_sizer($expertise_fpage_featured_image_expertise_id, 1600, 900, 'center', 'center');
				} elseif ( $expertise_fpage_featured_image_expertise ) {
					$expertise_fpage_featured_image_expertise = wp_get_attachment_url( $expertise_fpage_featured_image_expertise_id, 'aspect-16-9' );
				} else {
					$expertise_fpage_featured_image_expertise = $expertise_featured_image;
				}

			// Clinical Resources

				// Page-level settings specific to an individual Area of Expertise

					// Get the field values from the current Area of Expertise ontology item for ontology text elements related to Clinical Resources in that Area of Expertise's subsection
					// $clinical_resource_fpage_title_expertise = get_field('expertise_clinical_resources_fpage_title'); // Title
					$clinical_resource_fpage_intro_expertise = get_field('expertise_clinical_resources_fpage_intro'); // Intro text

				// Find-a-Doc Settings general to all Area of Expertise subsections

					// If the Clinical Resources title variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($clinical_resource_fpage_title_expertise) || empty($clinical_resource_fpage_title_expertise) ) {
						$clinical_resource_fpage_title_expertise = get_field('clinical_resource_fpage_title_expertise', 'option'); // Title
					}

					// If the Clinical Resources intro text variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($clinical_resource_fpage_intro_expertise) || empty($clinical_resource_fpage_intro_expertise) ) {
						$clinical_resource_fpage_intro_expertise = get_field('clinical_resource_fpage_intro_expertise', 'option'); // Intro text
					}

				// Hardcoded fallbacks specific to Area of Expertise subsections

					// // If the Clinical Resources title variable is not set or is empty...
					// // set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					// if ( !isset($clinical_resource_fpage_title_expertise) || empty($clinical_resource_fpage_title_expertise) ) {
					// 	$clinical_resource_fpage_title_expertise = '';
					// }

					// // If the Clinical Resources intro text variable is not set or is empty...
					// // set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					// if ( !isset($clinical_resource_fpage_intro_expertise) || empty($clinical_resource_fpage_intro_expertise) ) {
					// 	$clinical_resource_fpage_intro_expertise = '';
					// }

				// Conditionally substitute any placeholder text for relevant system settings value
				$clinical_resource_fpage_title_expertise = ( isset($clinical_resource_fpage_title_expertise) && !empty($clinical_resource_fpage_title_expertise) ) ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_title_expertise) : $clinical_resource_fpage_title_expertise; // Title
				$clinical_resource_fpage_intro_expertise = ( isset($clinical_resource_fpage_intro_expertise) && !empty($clinical_resource_fpage_intro_expertise) ) ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_intro_expertise) : $clinical_resource_fpage_intro_expertise; // Intro text

				// Find-a-Doc Settings for general placements

					// If the Clinical Resources title variable is not set or is empty, set the fallback value by getting the system settings for general placement
					if ( !isset($clinical_resource_fpage_title_expertise) || empty($clinical_resource_fpage_title_expertise) ) {
						uamswp_fad_clinical_resource_fpage_text_general();
						global $clinical_resource_fpage_title_general;
						$clinical_resource_fpage_title_expertise = $clinical_resource_fpage_title_general;
					}

					// If the Clinical Resources intro text variable is not set or is empty, set the fallback value by getting the system settings for general placement
					if ( !isset($clinical_resource_fpage_intro_expertise) || empty($clinical_resource_fpage_intro_expertise) ) {
						uamswp_fad_clinical_resource_fpage_text_general();
						global $clinical_resource_fpage_intro_general;
						$clinical_resource_fpage_intro_expertise = $clinical_resource_fpage_intro_general;
					}

				// Get value for meta description
				$clinical_resources_fpage_short_desc_query_expertise = get_field('expertise_clinical_resources_fpage_short_desc_query'); // If true, use intro text. If false, use specific short description.
				$clinical_resources_fpage_short_desc_query_expertise = isset($clinical_resources_fpage_short_desc_query_expertise) ? $clinical_resources_fpage_short_desc_query_expertise : true; // Define a value if the item has not been updated since 'expertise_clinical_resources_fpage_short_desc_query' was added
				if ( $clinical_resources_fpage_short_desc_query_expertise ) {
					$clinical_resources_fpage_short_desc_expertise = $clinical_resource_fpage_intro_expertise;
				} else {
					$clinical_resources_fpage_short_desc_expertise = get_field('expertise_clinical_resources_fpage_short_desc');
					$clinical_resources_fpage_short_desc_expertise = ( isset($clinical_resources_fpage_short_desc_expertise) && !empty($clinical_resources_fpage_short_desc_expertise) ) ? uamswp_fad_fpage_text_replace($clinical_resources_fpage_short_desc_expertise) : $clinical_resources_fpage_short_desc_expertise; // Substitute placeholder text for relevant system settings value
					$clinical_resources_fpage_short_desc_expertise = ( isset($clinical_resources_fpage_short_desc_expertise) && !empty($clinical_resources_fpage_short_desc_expertise) ) ? $clinical_resources_fpage_short_desc_expertise : $clinical_resource_fpage_intro_expertise; // If there is no value, use intro text as a fallback value
				}

				// Get the featured image value
				$clinical_resource_fpage_featured_image_expertise_id = get_field('expertise_clinical_resources_fpage_featured_image');
				if ( $clinical_resource_fpage_featured_image_expertise && function_exists( 'fly_add_image_size' ) ) {
					$clinical_resource_fpage_featured_image_expertise = image_sizer($clinical_resource_fpage_featured_image_expertise_id, 1600, 900, 'center', 'center');
				} elseif ( $clinical_resource_fpage_featured_image_expertise ) {
					$clinical_resource_fpage_featured_image_expertise = wp_get_attachment_url( $clinical_resource_fpage_featured_image_expertise_id, 'aspect-16-9' );
				} else {
					$clinical_resource_fpage_featured_image_expertise = $expertise_featured_image;
				}

			// Conditions

				// Page-level settings specific to an individual Area of Expertise

					// Get the field values from the current Area of Expertise ontology item for ontology text elements related to Conditions in that Area of Expertise's subsection
					// $condition_fpage_title_expertise = get_field('expertise_conditions_fpage_title'); // Title
					$condition_fpage_intro_expertise = get_field('expertise_conditions_fpage_intro'); // Intro text

				// Find-a-Doc Settings general to all Area of Expertise subsections

					// If the Related Conditions title variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($condition_fpage_title_expertise) || empty($condition_fpage_title_expertise) ) {
						$condition_fpage_title_expertise = get_field('conditions_fpage_title_expertise', 'option'); // Title
					}

					// If the Related Conditions intro text variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($condition_fpage_intro_expertise) || empty($condition_fpage_intro_expertise) ) {
						$condition_fpage_intro_expertise = get_field('conditions_fpage_intro_expertise', 'option'); // Intro text
					}

				// Hardcoded fallbacks specific to Area of Expertise subsections

					// // If the Conditions title variable is not set or is empty...
					// // set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					// if ( !isset($condition_fpage_title_expertise) || empty($condition_fpage_title_expertise) ) {
					// 	$condition_fpage_title_expertise = '';
					// }

					// // If the Conditions intro text variable is not set or is empty...
					// // set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					// if ( !isset($condition_fpage_intro_expertise) || empty($condition_fpage_intro_expertise) ) {
					// 	$condition_fpage_intro_expertise = '';
					// }

				// Conditionally substitute any placeholder text for relevant system settings value
				$condition_fpage_title_expertise = ( isset($condition_fpage_title_expertise) && !empty($condition_fpage_title_expertise) ) ? uamswp_fad_fpage_text_replace($condition_fpage_title_expertise) : $condition_fpage_title_expertise; // Title
				$condition_fpage_intro_expertise = ( isset($condition_fpage_intro_expertise) && !empty($condition_fpage_intro_expertise) ) ? uamswp_fad_fpage_text_replace($condition_fpage_intro_expertise) : $condition_fpage_intro_expertise; // Intro text

				// Find-a-Doc Settings for general placements

					// If the Conditions title variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element in general placements
					if ( !isset($condition_fpage_title_expertise) || empty($condition_fpage_title_expertise) ) {
						uamswp_fad_condition_fpage_text_general();
						global $condition_fpage_title_general;
						$condition_fpage_title_expertise = $condition_fpage_title_general;
					}

					// If the Conditions intro text variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element in general placements
					if ( !isset($condition_fpage_intro_expertise) || empty($condition_fpage_intro_expertise) ) {
						uamswp_fad_condition_fpage_text_general();
						global $condition_fpage_intro_general;
						$condition_fpage_intro_expertise = $condition_fpage_intro_general;
					}

			// Treatments

				// Page-level settings specific to an individual Area of Expertise

					// Get the field values from the current Area of Expertise ontology item for ontology text elements related to Treatments in that Area of Expertise's subsection
					// $provider_fpage_title_expertise = get_field('expertise_treatments_fpage_title'); // Title
					$treatment_fpage_intro_expertise = get_field('expertise_treatments_fpage_intro'); // Intro text

				// Find-a-Doc Settings general to all Area of Expertise subsections

					// If the Treatments title variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($treatment_fpage_title_expertise) || empty($treatment_fpage_title_expertise) ) {
						$treatment_fpage_title_expertise = get_field('treatments_fpage_title_expertise', 'option'); // Title
					}

					// If the Treatments intro text variable is not set or is empty...
					// get the field values from Find-a-Doc Settings for that ontology text element general to all Area of Expertise subsections
					if ( !isset($treatment_fpage_intro_expertise) || empty($treatment_fpage_intro_expertise) ) {
						$treatment_fpage_intro_expertise = get_field('treatments_fpage_intro_expertise', 'option'); // Intro text
					}

				// Hardcoded fallbacks specific to Area of Expertise subsections

					// // If the Treatments title variable is not set or is empty...
					// // set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					// if ( !isset($treatment_fpage_title_expertise) || empty($treatment_fpage_title_expertise) ) {
					// 	$treatment_fpage_title_expertise = '';
					// }

					// // If the Treatments intro text variable is not set or is empty...
					// // set a hardcoded value for that ontology text element general to all Area of Expertise subsections
					// if ( !isset($treatment_fpage_intro_expertise) || empty($treatment_fpage_intro_expertise) ) {
					// 	$treatment_fpage_intro_expertise = '';
					// }

				// Conditionally substitute any placeholder text for relevant system settings value
				$treatment_fpage_title_expertise = ( isset($treatment_fpage_title_expertise) && !empty($treatment_fpage_title_expertise) ) ? uamswp_fad_fpage_text_replace($treatment_fpage_title_expertise) : $treatment_fpage_title_expertise; // Title
				$treatment_fpage_intro_expertise = ( isset($treatment_fpage_intro_expertise) && !empty($treatment_fpage_intro_expertise) ) ? uamswp_fad_fpage_text_replace($treatment_fpage_intro_expertise) : $treatment_fpage_intro_expertise; // Intro text

				// Find-a-Doc Settings for general placements

					// If the Treatments title variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element in general placements
					if ( !isset($treatment_fpage_title_expertise) || empty($treatment_fpage_title_expertise) ) {
						uamswp_fad_treatment_fpage_text_general();
						global $treatment_fpage_title_general;
						$treatment_fpage_title_expertise = $treatment_fpage_title_general;
					}

					// If the Treatments intro text variable is not set or is empty...
					// get the field value from Find-a-Doc Settings for that ontology text element in general placements
					if ( !isset($treatment_fpage_intro_expertise) || empty($treatment_fpage_intro_expertise) ) {
						uamswp_fad_treatment_fpage_text_general();
						global $treatment_fpage_intro_general;
						$treatment_fpage_intro_expertise = $treatment_fpage_intro_general;
					}
		}

		// Get field values for text elements for related ontology sections on Clinical Resource profile
		function uamswp_fad_fpage_text_clinical_resource() {
			// Make variables available outside of the function
			global $provider_fpage_title_clinical_resource;
			global $provider_fpage_intro_clinical_resource;
			global $location_fpage_title_clinical_resource;
			global $location_fpage_intro_clinical_resource;
			global $expertise_fpage_title_clinical_resource;
			global $expertise_fpage_intro_clinical_resource;
			global $clinical_resource_fpage_title_clinical_resource;
			global $clinical_resource_fpage_intro_clinical_resource;
			global $condition_fpage_title_clinical_resource;
			global $condition_fpage_intro_clinical_resource;
			global $treatment_fpage_title_clinical_resource;
			global $treatment_fpage_intro_clinical_resource;

			// Providers
			$provider_fpage_title_clinical_resource = get_field('provider_fpage_title_clinical_resource', 'option') ?: ( get_field('provider_fpage_title_general', 'option') ?: 'Related [Providers]' ); // Title of Section for Providers on Clinical Resource Profile
				$provider_fpage_title_clinical_resource = $provider_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($provider_fpage_title_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value
			$provider_fpage_intro_clinical_resource = get_field('provider_fpage_intro_clinical_resource', 'option') ?: ( get_field('provider_fpage_intro_general', 'option') ?: '' ); // Intro Text of Section for Providers on Clinical Resource Profile
				$provider_fpage_intro_clinical_resource = $provider_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($provider_fpage_intro_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value

			// Locations
			$location_fpage_title_clinical_resource = get_field('location_fpage_title_clinical_resource', 'option') ?: ( get_field('location_fpage_title_general', 'option') ?: 'Related [Locations]' ); // Title of Section for Locations on Clinical Resource Profile
				$location_fpage_title_clinical_resource = $location_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($location_fpage_title_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value
			$location_fpage_intro_clinical_resource = get_field('location_fpage_intro_clinical_resource', 'option') ?: ( get_field('location_fpage_intro_general', 'option') ?: '' ); // Intro Text of Section for Locations on Clinical Resource Profile
				$location_fpage_intro_clinical_resource = $location_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($location_fpage_intro_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value

			// Areas of Expertise
			$expertise_fpage_title_clinical_resource = get_field('expertise_fpage_title_clinical_resource', 'option') ?: ( get_field('expertise_fpage_title_general', 'option') ?: 'Related [Areas of Expertise]' ); // Title of Section for Descendant Areas of Expertise Items on Clinical Resource Profile
				$expertise_fpage_title_clinical_resource = $expertise_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($expertise_fpage_title_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value
			$expertise_fpage_intro_clinical_resource = get_field('expertise_fpage_intro_clinical_resource', 'option') ?: ( get_field('expertise_fpage_intro_general', 'option') ?: '' ); // Intro Text of Section for Areas of Expertise Items on Clinical Resource Profile
				$expertise_fpage_intro_clinical_resource = $expertise_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($expertise_fpage_intro_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value

			// Clinical Resources
			$clinical_resource_fpage_title_clinical_resource = get_field('clinical_resource_fpage_title_clinical_resource', 'option') ?: ( get_field('clinical_resource_fpage_title_general', 'option') ?: 'Related [Clinical Resources]' ); // Title of Section for Clinical Resources on Clinical Resource Profile
				$clinical_resource_fpage_title_clinical_resource = $clinical_resource_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_title_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value
			$clinical_resource_fpage_intro_clinical_resource = get_field('clinical_resource_fpage_intro_clinical_resource', 'option') ?: ( get_field('clinical_resource_fpage_intro_general', 'option') ?: '' ); // Intro Text of Section for Clinical Resources on Clinical Resource Profile
				$clinical_resource_fpage_intro_clinical_resource = $clinical_resource_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_intro_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value

			// Conditions
			$condition_fpage_title_clinical_resource = get_field('conditions_fpage_title_clinical_resource', 'option') ?: ( get_field('conditions_fpage_title_general', 'option') ?: 'Related [Conditions]' ); // Title of Section for Conditions on Clinical Resource Profile
				$condition_fpage_title_clinical_resource = $condition_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($condition_fpage_title_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value
			$condition_fpage_intro_clinical_resource = get_field('conditions_fpage_intro_clinical_resource', 'option') ?: ( get_field('conditions_fpage_intro_general', 'option') ?: '' ); // Intro Text of Section for Conditions on Clinical Resource Profile
				$condition_fpage_intro_clinical_resource = $condition_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($condition_fpage_intro_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value

			// Treatments
			$treatment_fpage_title_clinical_resource = get_field('treatments_fpage_title_clinical_resource', 'option') ?: ( get_field('treatments_fpage_title_general', 'option') ?: 'Related [Treatments]' ); // Title of Section for Treatments on Clinical Resource Profile
				$treatment_fpage_title_clinical_resource = $treatment_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($treatment_fpage_title_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value
			$treatment_fpage_intro_clinical_resource = get_field('treatments_fpage_intro_clinical_resource', 'option') ?: ( get_field('treatments_fpage_intro_general', 'option') ?: '' ); // Intro Text of Section for Treatments on Clinical Resource Profile
				$treatment_fpage_intro_clinical_resource = $treatment_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($treatment_fpage_intro_clinical_resource) : ''; // Substitute placeholder text for relevant system settings value
		}

// Filter the allowed block types for all editor types
function uamswp_fad_allowed_block_types ( $block_editor_context, $editor_context ) {
	// Define the list of blocks associated with landing pages
	$allowed_marketing = [
		'acf/action-bar', 			// Block: UAMS Action Bar
		'acf/call-out', 			// Block: UAMS Call-Out
		'acf/counter-list', 		// Block: UAMS Counter List
		'acf/cta', 					// Block: UAMS CTA Bar
		'acf/fad-locations', 		// Block: UAMS Find-a-Doc Locations
		'acf/fad-providers', 		// Block: UAMS Find-a-Doc Providers
		// 'acf/hero', 				// Block: UAMS Hero
		'acf/image-side', 			// Block: UAMS Side-by-Side Image & Text
		'acf/link-list', 			// Block: UAMS Link List
		'acf/livewhale-calendar', 	// Block: UAMS LiveWhale Calendar
		'acf/logo-list', 			// Block: UAMS Logo List
		'acf/text-overlay', 		// Block: UAMS Text & Image Overlay
		'acf/text-stacked', 		// Block: UAMS Stacked Image & Text
		// 'acf/uams-content', 		// Block: UAMS Content
		'acf/uams-gallery', 		// Block: UAMS Gallery
		'acf/uams-section', 		// Block: UAMS Section
		'acf/uams-news' 			// Block: UAMS News
	];

	// Include the slugs of any post types that should be restricted to only UAMS blocks
	if ( 'expertise' === $editor_context->post->post_type ) {
		return $allowed_marketing;
	}

	return $block_editor_context;
}
add_filter( 'allowed_block_types_all', 'uamswp_fad_allowed_block_types', 10, 2 );

// Override theme's method of defining the meta description on fake subpages
function uamswp_fad_meta_desc($html) {
	// Bring in variables from outside of the function
	global $excerpt; // Defined on the template

	$html = $excerpt;
	
	return $html;
}

// Get system settings for general patient appointment information
function uamswp_fad_appointment_patients() {
	// Make variables available outside of the function
	global $appointment_patients_phone_number_new;
	global $appointment_patients_phone_label_new;
	global $appointment_patients_phone_label_new_attr;
	global $appointment_patients_phone_info_new;
	global $appointment_patients_phone_number_existing;
	global $appointment_patients_phone_label_existing;
	global $appointment_patients_phone_label_existing_attr;
	global $appointment_patients_phone_info_existing;
	global $appointment_patients_phone_number_both;
	global $appointment_patients_phone_label_both;
	global $appointment_patients_phone_label_both_attr;
	global $appointment_patients_phone_info_both;
	global $appointment_patients_web_url_new;
	global $appointment_patients_web_label_new;
	global $appointment_patients_web_label_new_attr;
	global $appointment_patients_web_info_new;
	global $appointment_patients_web_url_existing;
	global $appointment_patients_web_label_existing;
	global $appointment_patients_web_label_existing_attr;
	global $appointment_patients_web_info_existing;
	global $appointment_patients_web_url_both;
	global $appointment_patients_web_label_both;
	global $appointment_patients_web_label_both_attr;
	global $appointment_patients_web_info_both;

	// Phone Number Information

		// New Patients Only
		$appointment_patients_phone_number_new = get_field('appointment_patients_phone_number_new', 'option') ?: '';
		$appointment_patients_phone_label_new = get_field('appointment_patients_phone_label_new', 'option') ?: '';
		$appointment_patients_phone_label_new_attr = uamswp_attr_conversion($appointment_patients_phone_label_new);
		$appointment_patients_phone_info_new = get_field('appointment_patients_phone_info_new', 'option') ?: '';

		// Existing Patients Only
		$appointment_patients_phone_number_existing = get_field('appointment_patients_phone_number_existing', 'option') ?: '';
		$appointment_patients_phone_label_existing = get_field('appointment_patients_phone_label_existing', 'option') ?: '';
		$appointment_patients_phone_label_existing_attr = uamswp_attr_conversion($appointment_patients_phone_label_existing);
		$appointment_patients_phone_info_existing = get_field('appointment_patients_phone_info_existing', 'option') ?: '';

		// Both New and Existing Patients
		$appointment_patients_phone_number_both = get_field('appointment_patients_phone_number_both', 'option') ?: '';
		$appointment_patients_phone_label_both = get_field('appointment_patients_phone_label_both', 'option') ?: '';
		$appointment_patients_phone_label_both_attr = uamswp_attr_conversion($appointment_patients_phone_label_both);
		$appointment_patients_phone_info_both = get_field('appointment_patients_phone_info_both', 'option') ?: '';

	// Webpage Information

		// New Patients Only
		$appointment_patients_web_url_new = get_field('appointment_patients_web_url_new', 'option') ?: '';
		$appointment_patients_web_label_new = get_field('appointment_patients_web_label_new', 'option') ?: '';
		$appointment_patients_web_label_new_attr = uamswp_attr_conversion($appointment_patients_web_label_new);
		$appointment_patients_web_info_new = get_field('appointment_patients_web_info_new', 'option') ?: '';

		// Existing Patients Only
		$appointment_patients_web_url_existing = get_field('appointment_patients_web_url_existing', 'option') ?: '';
		$appointment_patients_web_label_existing = get_field('appointment_patients_web_label_existing', 'option') ?: '';
		$appointment_patients_web_label_existing_attr = uamswp_attr_conversion($appointment_patients_web_label_existing);
		$appointment_patients_web_info_existing = get_field('appointment_patients_web_info_existing', 'option') ?: '';

		// Both New and Existing Patients
		$appointment_patients_web_url_both = get_field('appointment_patients_web_url_both', 'option') ?: '';
		$appointment_patients_web_label_both = get_field('appointment_patients_web_label_both', 'option') ?: '';
		$appointment_patients_web_label_both_attr = uamswp_attr_conversion($appointment_patients_web_label_both);
		$appointment_patients_web_info_both = get_field('appointment_patients_web_info_both', 'option') ?: '';
}

// Get system settings for general patient referral information
function uamswp_fad_appointment_refer() {
	// Make variables available outside of the function
	global $appointment_refer_phone_number;
	global $appointment_refer_phone_label;
	global $appointment_refer_phone_label_attr;
	global $appointment_refer_phone_info;
	global $appointment_refer_fax_number;
	global $appointment_refer_fax_label;
	global $appointment_refer_fax_label_attr;
	global $appointment_refer_fax_info;
	global $appointment_refer_web_url;
	global $appointment_refer_web_label;
	global $appointment_refer_web_label_attr;
	global $appointment_refer_web_info;

	// Phone Number Information
	$appointment_refer_phone_number = get_field('appointment_refer_phone_number', 'option') ?: '';
	$appointment_refer_phone_label = get_field('appointment_refer_phone_label', 'option') ?: '';
	$appointment_refer_phone_label_attr = uamswp_attr_conversion($appointment_refer_phone_label);
	$appointment_refer_phone_info = get_field('appointment_refer_phone_info', 'option') ?: '';

	// Fax Information
	$appointment_refer_fax_number = get_field('appointment_refer_fax_number', 'option') ?: '';
	$appointment_refer_fax_label = get_field('appointment_refer_fax_label', 'option') ?: '';
	$appointment_refer_fax_label_attr = uamswp_attr_conversion($appointment_refer_fax_label);
	$appointment_refer_fax_info = get_field('appointment_refer_fax_info', 'option') ?: '';

	// Webpage Information
	$appointment_refer_web_url = get_field('appointment_refer_web_url', 'option') ?: '';
	$appointment_refer_web_label = get_field('appointment_refer_web_label', 'option') ?: '';
	$appointment_refer_web_label_attr = uamswp_attr_conversion($appointment_refer_web_label);
	$appointment_refer_web_info = get_field('appointment_refer_web_info', 'option') ?: '';
}

// Get system settings for jump links (a.k.a. anchor links)
function uamswp_fad_labels_jump_links() {
	// Make variables available outside of the function
	global $fad_jump_links_title;

	// Jump Links Section Title
	$fad_jump_links_title = get_field('fad_jump_links_title', 'option') ?: 'Content';
}

// Crop and resize images for Open Graph and Twitter
function uamswp_meta_image_resize() {
	// Bring in variables from outside of the function
	global $page_image_id;

	// Make variables available outside of the function
	global $meta_og_image;
	global $meta_og_image_width;
	global $meta_og_image_height;
	global $meta_twitter_image;
	global $page_image_twitter_width;
	global $page_image_twitter_height;

	// Create multidimensional associative array for defining width and height for each social meta image
	$image_size = array();

	// Open Graph / Facebook
	// Timeline photo and post
	// Aspect ratio: 1.91:1
	// Minimum dimensions: 600 x 315 pixels
	// Recommended minimum dimensions: 1200 x 630 pixels
	// Maximum file size: 8 MB
	// https://developers.facebook.com/docs/sharing/webmasters/images/
	$image_size['opengraph'] = array( 'width' => 1600, 'height' => 838 );

	// Twitter
	// Summary Card with Large Image
	// Aspect ratio: Supports 2:1 (most articles reference 1.91:1)
	// Minimum dimensions: 300 x 157 pixels
	// Maximum dimensions: 4096 x 4096 pixels
	// Maximum file size: 5 MB
	// https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/summary-card-with-large-image
	$image_size['twitter'] = array( 'width' => 1600, 'height' => 838 );

	if ( $page_image_id && function_exists( 'fly_add_image_size' ) ) {
		$meta_og_image = image_sizer($page_image_id, $image_size['opengraph']['width'], $image_size['opengraph']['height'], 'center', 'center');
		$meta_og_image_width = $image_size['opengraph']['width'];
		$meta_og_image_height = $image_size['opengraph']['height'];
		$meta_twitter_image = image_sizer($page_image_id, $image_size['twitter']['width'], $image_size['twitter']['height'], 'center', 'center');
		$page_image_twitter_width = $image_size['twitter']['width'];
		$page_image_twitter_height = $image_size['twitter']['height'];
	} elseif ( $page_image_id ) {
		$meta_og_image = wp_get_attachment_url( $page_image_id, 'aspect-16-9' );
		$meta_og_image_width = image_get_intermediate_size( $page_image_id, 'aspect-16-9' )['width'];
		$meta_og_image_height = image_get_intermediate_size( $page_image_id, 'aspect-16-9' )['height'];
		$meta_twitter_image = $meta_og_image;
		$page_image_twitter_width = $meta_og_image_width;
		$page_image_twitter_height = $meta_og_image_height;
	} else {
		$meta_og_image = '';
		$meta_og_image_width = '';
		$meta_og_image_height = '';
		$meta_twitter_image = '';
		$page_image_twitter_width = '';
		$page_image_twitter_height = '';
	}
}

// Construct providers section for display on a page
function uamswp_fad_section_provider() {
	// Bring in variables from outside of the function

		// Optional variables defined on the template
		global $provider_section_show_header; // Query whether to display the section header // bool (default: true)
		global $provider_section_title; // Text to use for the section title // string (default: Find-a-Doc Settings value for providers section title in a general placement)
		global $provider_section_intro; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for providers section intro text in a general placement)
		global $provider_section_filter; // Query whether to add filter(s) // bool (default: true)
		global $provider_section_filter_region; // Query whether to add region filter // bool (default: true)
		global $provider_section_filter_title; // Query whether to add title filter // bool (default: true)
		global $provider_section_collapse_list; // Query whether to collapse the list of providers in the providers section // bool (default: true)

		// Defined in uamswp_fad_labels_provider()
		global $provider_plural_name; // string
		global $provider_plural_name_attr; // string

		// Defined in uamswp_fad_provider_fpage_text_general()
		global $provider_fpage_title_general; // string
		global $provider_fpage_intro_general; // string

		// Defined on the template or in a function such as uamswp_fad_provider_query()
		global $provider_section_show; // bool
		global $provider_query; // array
		global $providers; // array
		global $provider_ids; // array
		global $provider_count; // integer

	// Do something
	if ( $provider_section_show ) {

		// Check/define variables

			$provider_section_show_header = isset($provider_section_show_header) ? $provider_section_show_header : true;
			if ( !isset($provider_section_title) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($provider_fpage_title_general) ) {
					uamswp_fad_provider_fpage_text_general();
					global $provider_fpage_title_general;
				}
				$provider_section_title = $provider_fpage_title_general;
			}
			if ( !isset($provider_section_intro) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($provider_fpage_intro_general) ) {
					uamswp_fad_provider_fpage_text_general();
					global $provider_fpage_intro_general;
				}
				$provider_section_intro = $provider_fpage_intro_general;
			}
			$provider_section_filter = isset($provider_section_filter) ? $provider_section_filter : true;
			if ( $provider_section_filter ) {
				$provider_section_filter_region = isset($provider_section_filter_region) ? $provider_section_filter_region : true;
				$provider_section_filter_title = isset($provider_section_filter_title) ? $provider_section_filter_title : true;	
			} else {
				$provider_section_filter_region = false;
				$provider_section_filter_title = false;	
			}
			$provider_section_filter = ( $provider_section_filter && ( $provider_section_filter_region || $provider_section_filter_title ) ) ? $provider_section_filter : false; // Set as false if neither of the filter types is true
			$provider_section_collapse_list = isset($provider_section_collapse_list) ? $provider_section_collapse_list : true;

		// Filter details
		if ( $provider_section_filter ) {

			// Set the AJAX filter shortcode name

				if (
					$provider_section_filter_region // Filter by region
					&&
					$provider_section_filter_title // Filter by title
				) {

					$provider_section_filter_ajax = 'uamswp_provider_ajax_filter';

				} elseif (
					$provider_section_filter_title // Filter by title
				) {

					$provider_section_filter_ajax = 'uamswp_provider_title_ajax_filter';

				} elseif (
					$provider_section_filter_region // Filter by region
				) {

					$provider_section_filter_ajax = '';

				} else {
					$provider_section_filter_ajax = '';
				}

				$provider_section_filter = $provider_section_filter_ajax ? $provider_section_filter : false; // If no AJAX filter shortcode name, then set disable filtering

			// Region filter details
			if ( $provider_section_filter_region ) {
	
				// Get all available regions (all available, since no titles set on initial load)
		
					// Get the list of region IDs from the providers
					$provider_region_IDs = array();
					while ( $provider_query->have_posts() ) {
						$provider_query->the_post();
						$id = get_the_ID();
						$provider_region_return = get_field('physician_region', $id);
						if ( is_array( $provider_region_IDs ) ) {
							$provider_region_IDs = array_merge($provider_region_IDs, $provider_region_return);
						} else {
							$provider_region_IDs[] = $provider_region_return;
						}
					} // endwhile
					$provider_region_IDs = array_unique($provider_region_IDs); // Remove duplicate values from an array
		
					// Get the list of region slugs from the region IDs
					$provider_region_list = array();
					foreach ( $provider_region_IDs as $provider_region_ID ) {
						$provider_region_list[] = get_term_by( 'ID', $provider_region_ID, 'region' )->slug;
					}
		
				// If region cookie is set, run a modified query for providers
				if (
					isset($_COOKIE['wp_filter_region'])
					||
					isset($_GET['_filter_region'])
				) {
		
					$provider_region = isset($_GET['_filter_region']) ? $_GET['_filter_region'] : $_COOKIE['wp_filter_region'];
		
					// Construct the tax_query array
					$tax_query = array();
					if ( !empty($provider_region) ) {
						$tax_query[] = array(
							'taxonomy' => 'region',
							'field' => 'slug',
							'terms' => $provider_region
						);
					}
		
					// Construct the query arguments
					$args = array(
						'post_type' => 'provider',
						'post_status' => 'publish',
						'posts_per_page' => -1,
						'orderby' => 'title',
						'order' => 'ASC',
						'fields' => 'ids',
						'post__in' => $providers,
						'tax_query' => $tax_query
					);
		
					// The Query
					$provider_query = New WP_Query( $args );

					// Get a new list of provider IDs
					$provider_ids = $provider_query->posts;

				} // endif isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region'])

			} // endif ( $provider_section_filter_region )

			// Count the number of providers in the query
			$provider_count = count($provider_query->posts);

		} // endif ( $provider_section_filter )

		?>
		<section class="uams-module bg-auto<?php echo $provider_section_collapse_list ? ' collapse-list' : ''; ?>" id="providers">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title<?php echo !$provider_section_show_header ? ' sr-only' : ''; ?>"><span class="title"><?php echo $provider_section_title; ?></span></h2>
						<?php if ( $provider_section_intro ) { ?>
							<p class="note<?php echo !$provider_section_show_header ? ' sr-only' : ''; ?>"><?php echo $provider_section_intro; ?></p>
						<?php } // endif ( $provider_section_intro )
						if ( $provider_section_filter ) {
							echo do_shortcode( '[' . $provider_section_filter_ajax . ' providers="'. implode(",", $provider_ids) .'"]' );
						} // endif ( $provider_section_filter ) ?>
						<div class="card-list-container">
							<div class="card-list card-list-doctors">
								<?php
									if ( $provider_count > 0 ) {
										$title_list = $provider_section_filter_title ? array() : '';
										while ( $provider_query->have_posts() ) {
											$provider_query->the_post();
											$id = get_the_ID();
											include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
											if ( $provider_section_filter_title ) {
												$title_list[] = get_field('physician_title', $id);
											}
										} // endwhile
										if ( $provider_section_filter ) {
											echo '<data id="provider_ids" data-postids="' . implode(',', $provider_query->posts) . ',"' . ( $provider_section_filter_region ? ' data-regions="' . implode(',', $provider_region_list) . ',"' : '' ) . ( $provider_section_filter_title ? ' data-titles="' . implode(',', array_unique($title_list)) . ',"' : '' ) . '></data>';
										}
									} else {
										if ( $provider_section_filter ) {
											echo '<span class="no-results">Sorry, there are no ' . strtolower($provider_plural_name) . ' matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
										}
									} // endif ( $provider_count > 0 )
									wp_reset_postdata();
								?>
							</div>
						</div>
						<?php
						if ( $provider_section_collapse_list ) { ?>
							<div class="ajax-filter-load-more">
								<button class="btn btn-lg btn-primary" aria-label="Load all <?php echo strtolower($provider_plural_name_attr); ?>">Load All</button>
							</div>
						<?php } // endif ( $provider_section_collapse_list ) ?>
					</div>
				</div>
			</div>
			<?php if ( $provider_section_filter_region && isset($_GET['_filter_region']) ) { ?>
				<script type="text/javascript">
					// Set cookie to expire at end of session
					document.cookie = "wp_filter_region=<?php echo htmlspecialchars($_GET['_filter_region']); ?>; path=/; domain="+window.location.hostname;
				</script>
			<?php } ?>
		</section>
	<?php
	} // endif ( $provider_section_show )
}

// Construct locations section for display on a page
function uamswp_fad_section_location() {
	// Bring in variables from outside of the function

		// Optional variables defined on the template
		global $location_section_show_header; // Query whether to display the section header // bool (default: true)
		global $location_section_title; // Text to use for the section title // string (default: Find-a-Doc Settings value for locations section title in a general placement)
		global $location_section_intro; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for locations section intro text in a general placement)
		global $location_section_filter; // Query whether to add filter(s) // bool (default: true)
		global $location_section_filter_region; // Query whether to add region filter // bool (default: true)
		global $location_section_filter_title; // Query whether to add title filter // bool (default: false)
		global $location_section_collapse_list; // Query whether to collapse the list of locations in the locations section // bool (default: false)
		global $location_section_schema_query; // Query for whether to add locations to schema // bool (default: false)

		// Defined in uamswp_fad_labels_location()
		global $location_single_name; // string
		global $location_single_name_attr; // string
		global $location_plural_name; // string
		global $location_plural_name_attr; // string

		// Defined in uamswp_fad_location_fpage_text_general()
		global $location_fpage_title_general; // string
		global $location_fpage_intro_general; // string

		// Defined on the template or in a function such as uamswp_fad_location_query()
		global $location_section_show; // bool
		global $location_query; // array
		global $locations; // array
		global $location_ids; // array
		global $location_count; // integer

	// Do something
	if ( $location_section_show ) {

		// Check/define variables

			$location_section_show_header = isset($location_section_show_header) ? $location_section_show_header : true;
			if ( !isset($location_section_title) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($location_fpage_title_general) ) {
					uamswp_fad_location_fpage_text_general();
					global $location_fpage_title_general;
				}
				$location_section_title = $location_fpage_title_general;
			}
			if ( !isset($location_section_intro) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($location_fpage_intro_general) ) {
					uamswp_fad_location_fpage_text_general();
					global $location_fpage_intro_general;
				}
				$location_section_intro = $location_fpage_intro_general;
			}
			$location_section_filter = isset($location_section_filter) ? $location_section_filter : true;
			if ( $location_section_filter ) {
				$location_section_filter_region = isset($location_section_filter_region) ? $location_section_filter_region : true;
				$location_section_filter_title = isset($location_section_filter_title) ? $location_section_filter_title : false;	
			} else {
				$location_section_filter_region = false;
				$location_section_filter_title = false;	
			}
			$location_section_filter = ( $location_section_filter && ( $location_section_filter_region || $location_section_filter_title ) ) ? $location_section_filter : false; // Set as false if neither of the filter types is true
			$location_section_collapse_list = isset($location_section_collapse_list) ? $location_section_collapse_list : false;
			$location_section_schema_query = isset($location_section_schema_query) ? $location_section_schema_query : false;

		// Filter details
		if ( $location_section_filter ) {

			// Set the AJAX filter shortcode name

				if (
					$location_section_filter_region // Filter by region
					&&
					$location_section_filter_title // Filter by title
				) {
	
					$location_section_filter_ajax = '';
					$location_section_filter = false;
	
				} elseif (
					$location_section_filter_region // Filter by region
					&&
					!$location_section_filter_title // Do not filter by title
				) {
	
					$location_section_filter_ajax = 'uamswp_location_ajax_filter';
	
				} else {
					$location_section_filter_ajax = '';
					$location_section_filter = false;
				}

				$location_section_filter = $location_section_filter_ajax ? $location_section_filter : false; // If no AJAX filter shortcode name, then set disable filtering

			// Region filter details
			if ( $location_section_filter_region ) {

				// Get all available regions (all available, since no titles set on initial load)

					// Get the list of region IDs from the locations
					$location_region_IDs = array();
					while ( $location_query->have_posts() ) {
						$location_query->the_post();
						$id = get_the_ID();
						$location_region_return = get_field('location_region', $id);
						if ( is_array( $location_region_return ) ) {
							$location_region_IDs = array_merge($location_region_IDs, $location_region_return);
						} else {
							$location_region_IDs[] = $location_region_return;
						}
					} // endwhile
					$location_region_IDs = array_unique($location_region_IDs); // Remove duplicate values from an array
	
					// Get the list of region slugs from the region IDs
					$location_region_list = array();
					foreach ( $location_region_IDs as $location_region_ID ) {
						$location_region_list[] = get_term_by( 'ID', $location_region_ID, 'region' )->slug;
					}
	
				// If region cookie is set, run a modified query for locations
				if (
					isset($_COOKIE['wp_filter_region'])
					||
					isset($_GET['_filter_region'])
				) {

					$location_region = isset($_GET['_filter_region']) ? $_GET['_filter_region'] : $_COOKIE['wp_filter_region'];

					// Construct the tax_query array
					$tax_query = array();
					if ( !empty($location_region) ) {
						$tax_query[] = array(
							'taxonomy' => 'region',
							'field' => 'slug',
							'terms' => $location_region
						);
					}
	
					// Construct the query arguments
					$args = array(
						'post_type' => 'location',
						'post_status' => 'publish',
						'posts_per_page' => -1,
						'orderby' => 'title',
						'order' => 'ASC',
						'fields' => 'ids',
						'no_found_rows' => true, // counts posts, remove if pagination required
						'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
						'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
						'post__in' => $locations,
						'tax_query' => $tax_query
					);
	
					// The Query
					$location_query = New WP_Query( $args );

					// Get a new list of location IDs
					$location_ids = $location_query->posts;

				} // endif isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region'])

			} // endif ( $location_section_filter_region )
			
			// Count the number of locations in the query
			$location_count = count($location_query->posts);

		} // endif ( $location_section_filter )

		?>
		<section class="uams-module location-list bg-auto<?php echo $location_section_collapse_list ? ' collapse-list' : ''; ?>" id="locations">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title<?php echo !$location_section_show_header ? ' sr-only' : ''; ?>"><span class="title"><?php echo $location_section_title; ?></span></h2>
						<?php if ( $location_section_intro ) { ?>
							<p class="note<?php echo !$location_section_show_header ? ' sr-only' : ''; ?>"><?php echo $location_section_intro; ?></p>
						<?php } // endif ( $location_section_intro )
						if ( $location_section_filter ) {
							echo do_shortcode( '[' . $location_section_filter_ajax . ' locations="'. implode(",", $location_ids) .'"]' );
						} // endif ( $location_section_filter ) ?>
						<div class="card-list-container location-card-list-container">
							<div class="card-list card-list-locations">
								<?php
									if ( $location_section_schema_query ) {
										$l = 1;
										$location_schema = ',
	"address": [';
									}

									if ( $location_count > 0 ) {
										$title_list = $location_section_filter_title ? array() : '';
										while ( $location_query->have_posts() ) {
											$location_query->the_post();
											$id = get_the_ID();
											include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
											if ( $location_section_filter_title ) {
												$title_list[] = get_field('location_title', $id);
											}
											// Schema data
											if ( $location_section_schema_query ) {
												if ($l > 1){
													$location_schema .= ',';
												}
												$location_schema .= '
	{
		"@type": "PostalAddress",
		"streetAddress": "'. $location_address_1 . ' '. $location_address_2_schema .'",
		"addressLocality": "'. $location_city .'",
		"addressRegion": "'. $location_state .'",
		"postalCode": "'. $location_zip .'",
		"telephone": "'. format_phone_dash( $location_phone ) .'"
	}
	';
												$l++;
											}
										} // endwhile
										if ( $location_section_filter ) {
											echo '<data id="location_ids" data-postids="' . implode(',', $location_query->posts) . ',"' . ( $location_section_filter_region ? ' data-regions="' . implode(',', $location_region_list) . ',"' : '' ) . ( $location_section_filter_title ? ' data-titles="' . implode(',', array_unique($title_list)) . ',"' : '' ) . '></data>';
										}
									} else {
										if ( $location_section_filter ) {
											echo '<span class="no-results">Sorry, there are no ' . strtolower($location_plural_name) . ' matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
										}
									} // endif ( $location_count > 0 )
									wp_reset_postdata();

									if ( $location_section_schema_query ) {
										$location_schema .= ']
	';
									}
								?>
							</div>
							<?php
							if ( $location_section_collapse_list ) { ?>
								<div class="ajax-filter-load-more">
									<button class="btn btn-lg btn-primary" aria-label="Load all <?php echo strtolower($location_plural_name_attr); ?>">Load All</button>
								</div>
							<?php } // endif ( $location_section_collapse_list ) ?>
						</div>
					</div>
				</div>
			</div>
			<?php if ( $location_section_filter_region && isset($_GET['_filter_region']) ) { ?>
				<script type="text/javascript">
					// Set cookie to expire at end of session
					document.cookie = "wp_filter_region=<?php echo htmlspecialchars($_GET['_filter_region']); ?>; path=/; domain="+window.location.hostname;
				</script>
			<?php } ?>
		</section>
	<?php 
	} // endif ( $location_section_show )
} // end function uamswp_fad_section_location()

// Construct areas of expertise section for display on a page
function uamswp_fad_section_expertise() {
	// Bring in variables from outside of the function

		// Optional variables defined on the template
		global $expertise_section_class; // Section class // string (default: expertise-list)
		global $expertise_section_id; // Section ID // string (default: expertise)
		global $expertise_section_show_header; // Query whether to display the section header // bool (default: true)
		global $expertise_section_title; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of expertise section title in a general placement)
		global $expertise_section_intro; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of expertise section intro text in a general placement)
		global $expertise_section_collapse_list; // Query whether to collapse the list of locations in the locations section // bool (default: false)

		// Defined in uamswp_fad_labels_expertise()
		global $expertise_single_name; // string
		global $expertise_single_name_attr; // string
		global $expertise_plural_name; // string
		global $expertise_plural_name_attr; // string

		// Defined in uamswp_fad_expertise_fpage_text_general()
		global $expertise_fpage_title_general; // string
		global $expertise_fpage_intro_general; // string

		// Defined on the template or in a function such as uamswp_fad_expertise_query()
		global $expertise_section_show; // bool
		global $expertise_query; // array
		global $expertises; // array
		global $expertise_ids; // array
		global $expertise_count; // integer

		// Defined on the template or in a function such as uamswp_fad_ontology_hide()
		global $hide_medical_ontology;

	// Do something
	if ( $expertise_section_show && !$hide_medical_ontology ) {

		// Check/define variables

			$expertise_section_class = isset($expertise_section_class) ? $expertise_section_class : 'expertise-list';
			$expertise_section_id = isset($expertise_section_id) ? $expertise_section_id : 'expertise';
			$expertise_section_show_header = isset($expertise_section_show_header) ? $expertise_section_show_header : true;
			if ( !isset($expertise_section_title) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($expertise_fpage_title_general) ) {
					uamswp_fad_expertise_fpage_text_general();
					global $expertise_fpage_title_general;
				}
				$expertise_section_title = $expertise_fpage_title_general;
			}
			if ( !isset($expertise_section_intro) ) {
				// Set the section title using the system settings for the section title in a general placement
				if ( !isset($expertise_fpage_intro_general) ) {
					uamswp_fad_expertise_fpage_text_general();
					global $expertise_fpage_intro_general;
				}
				$expertise_section_intro = $expertise_fpage_intro_general;
			}
			$expertise_section_collapse_list = isset($expertise_section_collapse_list) ? $expertise_section_collapse_list : false;

		?>
		<section class="uams-module<?php echo $expertise_section_class ? ' ' . $expertise_section_class : ''; ?> bg-auto<?php echo $expertise_section_collapse_list ? ' collapse-list' : ''; ?>"<?php echo $expertise_section_id ? ' id="' . $expertise_section_id . '" aria-labelledby="' . $expertise_section_id . '-title"' : ''; ?>>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title<?php echo !$expertise_section_show_header ? ' sr-only' : ''; ?>"<?php echo $expertise_section_id ? ' id="' . $expertise_section_id . '-title"' : ''; ?>><span class="title"><?php echo $expertise_section_title; ?></span></h2>
						<?php if ( $expertise_section_intro ) { ?>
							<p class="note<?php echo !$expertise_section_show_header ? ' sr-only' : ''; ?>"><?php echo $expertise_section_intro; ?></p>
						<?php } // endif ( $expertise_section_intro ) ?>
						<div class="card-list-container">
							<div class="card-list card-list-expertise">
								<?php
									if ( $expertise_count > 0 ) {
										while ( $expertise_query->have_posts() ) {
											$expertise_query->the_post();
											$id = get_the_ID();
											include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
										} // endwhile
									} // endif ( $expertise_count > 0 )
									wp_reset_postdata();
								?>
							</div>
							<?php
							if ( $expertise_section_collapse_list ) { ?>
								<div class="ajax-filter-load-more">
									<button class="btn btn-lg btn-primary" aria-label="Load all <?php echo strtolower($expertise_plural_name_attr); ?>">Load All</button>
								</div>
							<?php } // endif ( $expertise_section_collapse_list ) ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php 
	} // endif ( $expertise_section_show )
} // end function uamswp_fad_section_expertise()

// Conditionally suppress ontology sections based on Find-a-Doc Settings configuration
function uamswp_fad_ontology_hide() {
	// Bring in variables from outside of the function

		// Defined on the template
		global $region;
		global $service_line;

	// Make variables available outside of the function
	global $hide_medical_ontology;

	$hide_medical_ontology = false;
	if ( have_rows('remove_ontology_criteria', 'option') ) {
		while ( have_rows('remove_ontology_criteria', 'option') ) {
			the_row();
			$remove_region = get_sub_field('remove_regions', 'option');
			$remove_service_line = get_sub_field('remove_service_lines', 'option');
			if ( (!empty($remove_region) && in_array(implode('',$region), $remove_region)) && empty($remove_service_line) ) { 
				$hide_medical_ontology = true;
				break;
			} elseif ( empty($remove_region) && (!empty($remove_service_line) && in_array($service_line, $remove_service_line) ) ) {
				$hide_medical_ontology = true;
				break;
			} elseif( (!empty($remove_region) && in_array(implode('',$region), $remove_region)) && (!empty($remove_service_line) && in_array($service_line, $remove_service_line) ) ) {
				$hide_medical_ontology = true;
				break;
			}
		} // endwhile ( have_rows('remove_ontology_criteria', 'option') )
	} // endif ( have_rows('remove_ontology_criteria', 'option') )
}