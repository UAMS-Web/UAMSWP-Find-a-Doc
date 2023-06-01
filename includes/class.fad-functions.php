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
			"orderby" => "title",
			"order" => "ASC",
			'posts_per_page' => -1,
			'post__in' => $ids_array,

		);
	// } else { // Taxonomy
	// 	$tax = (isset($_POST["tax"])) ? $_POST["tax"] : '';
	// 	$slug = (isset($_POST["slug"])) ? $_POST["slug"] : '';
	// 	$args = array(
	// 		"post_type" => "provider",
	// 		"post_status" => "publish",
	// 		"posts_per_page" => $ppp,
	// 		"orderby" => "title",
	// 		"order" => "ASC",
	// 		'paged'	=> $page,
	// 		"tax_query" => array(
	// 			array(
	// 			"taxonomy" => $tax,
	// 			"field" => "slug",
	// 			"terms" => $slug,
	// 			"operator" => "IN"
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



	$region_IDs = array();
	while ($region_prov_ids->have_posts()) : $region_prov_ids->the_post();
		$id = get_the_ID();
		$region_IDs = array_merge($region_IDs, get_field('physician_region', $id));
	endwhile;
	$region_IDs = array_unique($region_IDs);
	$region_list = array();
	foreach ($region_IDs as $region_ID){
		$region_list[] = get_term_by( 'ID', $region_ID, 'region' )->slug;
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
		echo '<data id="provider_ids" data-postids="'. implode(',', $provider_ids) .'," data-regions="'. implode(',', $region_list) .'," data-titles="'. implode(',', array_unique($title_list)) .',"></data>';
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
	global $location_single_name;
	global $location_single_name_attr;
	global $location_plural_name;

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

	$region_IDs = array();
	while ($region_loc_ids->have_posts()) : $region_loc_ids->the_post();
		$id = get_the_ID();
		$region_IDs[] = get_field('location_region', $id);
	endwhile;
	$region_IDs = array_unique($region_IDs);
	$region_list = array();
	foreach ($region_IDs as $region_ID){
		$region_list[] = get_term_by( 'ID', $region_ID, 'region' )->slug;
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
		echo '<data id="location_ids" data-postids="'. implode(',', $location_ids) .'," data-regions="'. implode(',', $region_list) .',"></data>';
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
	// Bring in variables from outside the function
	global $page_id;
	global $page_title;
	global $page_url;
	global $ontology_type;

	// Send variables out from inside the function
	global $site_nav_id;
	global $navbar_subbrand_title;
	global $navbar_subbrand_title_attr;
	global $navbar_subbrand_title_url;
	global $navbar_subbrand_parent;
	global $navbar_subbrand_parent_attr;
	global $navbar_subbrand_parent_url;

	// Ancestors
	$ancestors = get_post_ancestors($page_id); // Get all ancestors

	// Get only the ancestors with the ontology type
	$ancestors_ontology = array();
	if ( $ancestors ) {
		foreach( $ancestors as $ancestor ) {
			$ancestor_content_type = get_field('expertise_type', $ancestor); // True is ontology type, false is content type
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

	global $entry_header_style;
	global $entry_title_text;
	global $entry_title_text_supertitle;
	global $entry_title_text_subtitle;
	global $entry_title_text_body;
	global $entry_title_image_desktop;
	global $entry_title_image_mobile;

	include( UAMS_FAD_PATH . '/templates/entry-title-' . $entry_header_style . '.php');
}

// Construct meta title for fake subpages
function uamswp_fad_fpage_title($html) { 
	global $page_title;
	global $fpage_name;
	//you can add here all your conditions as if is_page(), is_category() etc.. 
	$html = $fpage_name . ' | ' . $page_title . ' | ' . get_bloginfo( "name" );
	return $html;
}

// Queries for whether each of the associated ontology content sections should be displayed on ontology pages/subsections

	// Query for whether associated providers content section should be displayed on ontology pages/subsections
	function uamswp_fad_ontology_providers_query() {
		global $physicians;
		global $site_nav_id;
		global $physicians_query;
		global $ontology_type;
		global $show_providers_section;
		global $provider_ids;

		$physicians = get_field( "physician_expertise", $site_nav_id );
		if($physicians) {
			$args = array(
				"post_type" => "provider",
				"post_status" => "publish",
				"posts_per_page" => -1,
				"orderby" => "title",
				"order" => "ASC",
				"fields" => "ids",
				// 'no_found_rows' => true, // counts posts, remove if pagination required
				'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
				'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
				"post__in" => $physicians
			);
			$physicians_query = New WP_Query( $args );
			if( ( $physicians_query && $physicians_query->have_posts()) ) {
				$show_providers_section = true;
				$provider_ids = $physicians_query->posts;
			} else {
				// wp_redirect( get_the_permalink($site_nav_id), 301 );
				$show_providers_section = false;
			}
		}
	}

	// Query for whether associated locations content section should be displayed on ontology pages/subsections
	function uamswp_fad_ontology_locations_query() {
		global $locations;
		global $site_nav_id;
		global $location_query;
		global $ontology_type;
		global $show_locations_section;

		$locations = get_field('location_expertise', $site_nav_id);
		if($locations) {
			$args = (array(
				'post_type' => "location",
				"post_status" => "publish",
				'order' => 'ASC',
				'orderby' => 'title',
				'posts_per_page' => -1,
				'fields' => 'ids',
				'no_found_rows' => true, // counts posts, remove if pagination required
				'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
				'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
				'post__in'	=> $locations
			));
			$location_query = new WP_Query( $args );
			if( ( $locations && $location_query->have_posts() ) ) {
				$show_locations_section = true;
			} else {
				$show_locations_section = false;
			}
		}
	}

	// Query for whether descendant ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	function uamswp_fad_ontology_descendants_query() {
		global $child_pages;
		global $site_nav_id;
		global $childnav;
		global $children;
		global $show_child_aoe_section;
		global $show_child_content_nav;

		$child_pages = get_pages( array('child_of' => $site_nav_id, 'post_type' => 'expertise' ) );
		if ($child_pages) {
			$childnav = '';
			$children = false;
			foreach ( $child_pages as $child_page ) {
				$hide = get_post_meta($child_page->ID, 'page_hide_from_menu');
				$type = get_field('expertise_type', $child_page->ID);
				if ( isset($hide[0]) && '1' == $hide[0] ) {
					//* Do nothing if there is nothing to show
				} elseif( !isset($type) || '1' == $type ) {
					$children = true;
				} else {
					$childnav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item menu-item-'. $child_page->ID .' nav-item active"><a title="'. $child_page->post_title .'" href="'. get_permalink( $child_page->ID ) .'" class="nav-link"><span itemprop="name">'. $child_page->post_title .'</span></a></li>';			
				}
			}
			$show_child_aoe_section = $children ? true : false;
			$show_child_content_nav = !empty($childnav) ? true : false;
		}
	}

	// Query for whether related ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	function uamswp_fad_ontology_related_query() {
		global $expertises;
		global $site_nav_id;
		global $expertise_query;
		global $ontology_type;
		global $show_related_aoe_section;

		$expertises = get_field('expertise_associated', $site_nav_id);
		$args = (array(
			'post_type' => "expertise",
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post__in'	=> $expertises
		));
		$expertise_query = new WP_Query( $args );
		if( ( $expertises && $expertise_query->have_posts() ) ) {
			$show_related_aoe_section = true;
		} else {
			$show_related_aoe_section = false;
		}
	}

	// Query for whether associated clinical resources content section should be displayed on ontology pages/subsections
	function uamswp_fad_ontology_resources_query() {
		global $site_nav_id;
		global $resources;
		global $resource_postsPerPage;
		global $resource_more;
		global $resource_query;
		global $ontology_type;
		global $show_related_resource_section;

		$resources = get_field('expertise_clinical_resources', $site_nav_id);
		$resource_postsPerPage = 4; // Set this value to preferred value (-1, 4, 6, 8, 10, 12)
		$resource_more = false;
		$args = (array(
			'post_type' => "clinical-resource",
			'order' => 'DESC',
			'orderby' => 'post_date',
			'posts_per_page' => $resource_postsPerPage,
			'post_status' => 'publish',
			'post__in'	=> $resources
		));
		$resource_query = new WP_Query( $args );
		
		// Check if Clinical Resources section should be displayed
		if( ( $resources && $resource_query->have_posts() ) ) {
			$show_related_resource_section = true;
		} else {
			$show_related_resource_section = false;
		}
	}

	// Query for whether associated conditions content section should be displayed on ontology pages/subsections
	function uamswp_fad_ontology_conditions_query() {
		global $conditions_cpt;
		global $site_nav_id;
		global $conditions_cpt_query;
		global $ontology_type;
		global $show_conditions_section;

		// load all 'conditions' terms for the post
		$conditions_cpt = get_field('expertise_conditions_cpt', $site_nav_id);
		// Conditions CPT
		$args = (array(
			'post_type' => "condition",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $conditions_cpt
		));
		$conditions_cpt_query = new WP_Query( $args );
		if( ( $conditions_cpt && $conditions_cpt_query->posts ) && ("1" == $ontology_type || !isset($ontology_type) ) ) {
			$show_conditions_section = true;
		} else {
			$show_conditions_section = false;
		}
	}

	// Query for whether associated treatments content section should be displayed on ontology pages/subsections
	function uamswp_fad_ontology_treatments_query() {
		global $treatments_cpt;
		global $site_nav_id;
		global $treatments_cpt_query;
		global $ontology_type;
		global $show_treatments_section;
		
		$treatments_cpt = get_field('expertise_treatments_cpt', $site_nav_id);
		// Treatments CPT
		$args = (array(
			'post_type' => "treatment",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $treatments_cpt
		));
		$treatments_cpt_query = new WP_Query( $args );
		if( ( $treatments_cpt && $treatments_cpt_query->posts ) && ("1" == $ontology_type || !isset($ontology_type) ) ) {
			$show_treatments_section = true;
		} else {
			$show_treatments_section = false;
		}
	}

// Construct ontology subsection primary navigation
function uamswp_fad_ontology_nav_menu() {
	global $site_nav_id;
	global $show_providers_section;
	global $show_locations_section;
	global $show_child_aoe_section;
	global $show_related_aoe_section;
	global $show_related_resource_section;
	global $child_pages;
	global $show_child_content_nav;
	global $childnav;
	global $provider_plural_name;
	global $provider_plural_name_attr;
	global $location_plural_name;
	global $location_plural_name_attr;
	global $expertise_plural_name;
	global $expertise_plural_name_attr;
	global $clinical_resource_plural_name;
	global $clinical_resource_plural_name_attr;

	include( UAMS_FAD_PATH . '/templates/single-expertise-nav.php');
}

// Construct ontology subsection site header
function uamswp_fad_ontology_header() {
	global $navbar_subbrand_title;
	global $navbar_subbrand_title_url;
	global $navbar_subbrand_parent;
	global $navbar_subbrand_parent_url;

	include( UAMS_FAD_PATH . '/templates/single-expertise-header.php');
}

// Construct ontology subsection meta keywords element
function uamswp_expertise_header_metadata() { 
	global $keywords;

	if( $keywords ): 
		$i = 1;
		$keyword_text = '';
		foreach( $keywords as $keyword ) { 
			if ( 1 < $i ) {
				$keyword_text .= ', ';
			}
			$keyword_text .= str_replace(",", "", $keyword['text']);
			$i++;
		}
		echo '<meta name="keywords" content="'. $keyword_text .'" />';
	endif;
}

// Construct ontology subsection appointment information section
function uamswp_fad_ontology_appointment() {
	global $show_appointment_section;

	global $provider_single_name;
	global $provider_single_name_attr;
	global $provider_plural_name;
	global $provider_plural_name_attr;
	global $location_single_name;
	global $location_single_name_attr;
	global $location_plural_name;
	global $location_plural_name_attr;
	global $expertise_single_name;
	global $expertise_single_name_attr;
	global $expertise_plural_name;
	global $expertise_plural_name_attr;
	global $expertise_archive_headline;
	global $expertise_archive_headline_attr;
	global $expertise_archive_intro_text;
	global $clinical_resource_single_name;
	global $clinical_resource_single_name_attr;
	global $clinical_resource_plural_name;
	global $clinical_resource_plural_name_attr;
	global $conditions_single_name;
	global $conditions_single_name_attr;
	global $conditions_plural_name;
	global $conditions_plural_name_attr;
	global $treatments_single_name;
	global $treatments_single_name_attr;
	global $treatments_plural_name;
	global $treatments_plural_name_attr;

	if ( $show_appointment_section ) {
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
	global $fpage_name;

	$crumbs[] = array($fpage_name, '');
	return $crumbs;
}

// Add page template class to body element's classes
function uamswp_page_body_class( $classes ) {
	global $template_type; // Expected values: 'default', 'page_landing' or 'marketing'

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
	global $podcast_name;
	global $show_podcast_section;

	// Check if podcast section should be displayed
	if ($podcast_name) {
		$show_podcast_section = true;
	} else {
		$show_podcast_section = false;
	}
}

// Construct UAMS Health Talk podcast section
function uamswp_fad_podcast() {
	global $podcast_name;
	global $podcast_subject;
	global $show_podcast_section;
	global $podcast_filter; // Expected values: 'tag' or 'doctor'

	global $provider_single_name;
	global $provider_single_name_attr;
	global $provider_plural_name;
	global $provider_plural_name_attr;
	global $location_single_name;
	global $location_single_name_attr;
	global $location_plural_name;
	global $location_plural_name_attr;
	global $expertise_single_name;
	global $expertise_single_name_attr;
	global $expertise_plural_name;
	global $expertise_plural_name_attr;
	global $expertise_archive_headline;
	global $expertise_archive_headline_attr;
	global $expertise_archive_intro_text;
	global $clinical_resource_single_name;
	global $clinical_resource_single_name_attr;
	global $clinical_resource_plural_name;
	global $clinical_resource_plural_name_attr;
	global $conditions_single_name;
	global $conditions_single_name_attr;
	global $conditions_plural_name;
	global $conditions_plural_name_attr;
	global $treatments_single_name;
	global $treatments_single_name_attr;
	global $treatments_plural_name;
	global $treatments_plural_name_attr;

	if ( $show_podcast_section ) {
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
	<?php } // endif ( $show_podcast_section )
}

// Get system settings for other ontology item labels and archive page text

	// Get system settings for provider labels
	function uamswp_fad_labels_provider() {
		global $provider_single_name;
		global $provider_single_name_attr;
		global $provider_plural_name;
		global $provider_plural_name_attr;

		$provider_single_name = get_field('provider_single_name', 'option') ?: 'Provider';
		$provider_single_name_attr = uamswp_attr_conversion($provider_single_name);
		$provider_plural_name = get_field('provider_plural_name', 'option') ?: 'Providers';
		$provider_plural_name_attr = uamswp_attr_conversion($provider_plural_name);
	}

	// Get system settings for provider archive page text
	function uamswp_fad_archive_provider() {
		global $provider_archive_headline;
		global $provider_archive_headline_attr;

		$provider_archive_headline = get_field('provider_archive_headline', 'option') ?: 'UAMS Health Providers';
		$provider_archive_headline_attr = uamswp_attr_conversion($provider_archive_headline);
	}

	// Get system settings for location labels
	function uamswp_fad_labels_location() {
		global $location_single_name;
		global $location_single_name_attr;
		global $location_plural_name;
		global $location_plural_name_attr;

		$location_single_name = get_field('location_single_name', 'option') ?: 'Location';
		$location_single_name_attr = uamswp_attr_conversion($location_single_name);
		$location_plural_name = get_field('location_plural_name', 'option') ?: 'Locations';
		$location_plural_name_attr = uamswp_attr_conversion($location_plural_name);
	}

	// Get system settings for location archive page text
	function uamswp_fad_archive_location() {
		global $location_archive_headline;
		global $location_archive_headline_attr;

		$location_archive_headline = get_field('location_archive_headline', 'option') ?: 'Locations';
		$location_archive_headline_attr = uamswp_attr_conversion($location_archive_headline);
	}

	// Get system settings for area of expertise labels
	function uamswp_fad_labels_expertise() {
		global $expertise_single_name;
		global $expertise_single_name_attr;
		global $expertise_plural_name;
		global $expertise_plural_name_attr;

		$expertise_single_name = get_field('expertise_single_name', 'option') ?: 'Area of Expertise';
		$expertise_single_name_attr = uamswp_attr_conversion($expertise_single_name);
		$expertise_plural_name = get_field('expertise_plural_name', 'option') ?: 'Areas of Expertise';
		$expertise_plural_name_attr = uamswp_attr_conversion($expertise_plural_name);
	}

	// Get system settings for area of expertise archive page text
	function uamswp_fad_archive_expertise() {
		global $expertise_archive_headline;
		global $expertise_archive_headline_attr;
		global $expertise_archive_intro_text;

		$expertise_archive_headline = get_field('expertise_archive_headline', 'option') ?: 'Areas of Expertise';
		$expertise_archive_headline_attr = uamswp_attr_conversion($expertise_archive_headline);
		$expertise_archive_intro_text = get_field('expertise_archive_intro_text', 'option');
	}

	// Get system settings for clinical resource labels
	function uamswp_fad_labels_clinical_resource() {
		global $clinical_resource_single_name;
		global $clinical_resource_single_name_attr;
		global $clinical_resource_plural_name;
		global $clinical_resource_plural_name_attr;

		$clinical_resource_single_name = get_field('clinical_resource_single_name', 'option') ?: 'Clinical Resource';
		$clinical_resource_single_name_attr = uamswp_attr_conversion($clinical_resource_single_name);
		$clinical_resource_plural_name = get_field('clinical_resource_plural_name', 'option') ?: 'Clinical Resources';
		$clinical_resource_plural_name_attr = uamswp_attr_conversion($clinical_resource_plural_name);
	}

	// Get system settings for clinical resource archive page text
	function uamswp_fad_archive_clinical_resource() {
		global $clinical_resource_archive_headline;
		global $clinical_resource_archive_headline_attr;

		$clinical_resource_archive_headline = get_field('clinical_resource_archive_headline', 'option') ?: 'Clinical Resources';
		$clinical_resource_archive_headline_attr = uamswp_attr_conversion($clinical_resource_archive_headline);
	}

	// Get system settings for combined conditions and treatments labels
	function uamswp_fad_labels_conditions_treatments() {
		global $conditions_treatments_single_name;
		global $conditions_treatments_single_name_attr;
		global $conditions_treatments_plural_name;
		global $conditions_treatments_plural_name_attr;

		$conditions_treatments_single_name = get_field('conditions_treatments_single_name', 'option') ?: 'Condition';
		$conditions_treatments_single_name_attr = uamswp_attr_conversion($conditions_treatments_single_name);
		$conditions_treatments_plural_name = get_field('conditions_treatments_plural_name', 'option') ?: 'conditions_treatments';
		$conditions_treatments_plural_name_attr = uamswp_attr_conversion($conditions_treatments_plural_name);
	}

	// Get system settings for condition labels
	function uamswp_fad_labels_conditions() {
		global $conditions_single_name;
		global $conditions_single_name_attr;
		global $conditions_plural_name;
		global $conditions_plural_name_attr;

		$conditions_single_name = get_field('conditions_single_name', 'option') ?: 'Condition';
		$conditions_single_name_attr = uamswp_attr_conversion($conditions_single_name);
		$conditions_plural_name = get_field('conditions_plural_name', 'option') ?: 'Conditions';
		$conditions_plural_name_attr = uamswp_attr_conversion($conditions_plural_name);
	}

	// Get system settings for condition archive page text
	function uamswp_fad_archive_conditions() {
		global $conditions_archive_headline;
		global $conditions_archive_headline_attr;
		global $conditions_archive_intro_text;

		$conditions_archive_headline = get_field('conditions_archive_headline', 'option') ?: 'Conditions';
		$conditions_archive_headline_attr = uamswp_attr_conversion($conditions_archive_headline);
		$conditions_archive_intro_text = get_field('conditions_archive_intro_text', 'option');
	}

	// Get system settings for treatment labels
	function uamswp_fad_labels_treatments() {
		global $treatments_single_name;
		global $treatments_single_name_attr;
		global $treatments_plural_name;
		global $treatments_plural_name_attr;

		$treatments_single_name = get_field('treatments_single_name', 'option') ?: 'Treatment/Procedure';
		$treatments_single_name_attr = uamswp_attr_conversion($treatments_single_name);
		$treatments_plural_name = get_field('treatments_plural_name', 'option') ?: 'Treatments and Procedures';
		$treatments_plural_name_attr = uamswp_attr_conversion($treatments_plural_name);
	}

	// Get system settings for treatment archive page text
	function uamswp_fad_archive_treatments() {
		global $treatments_archive_headline;
		global $treatments_archive_headline_attr;
		global $treatments_archive_intro_text;

		$treatments_archive_headline = get_field('treatments_archive_headline', 'option') ?: 'Treatments and Procedures';
		$treatments_archive_headline_attr = uamswp_attr_conversion($treatments_archive_headline);
		$treatments_archive_intro_text = get_field('treatments_archive_intro_text', 'option');
	}
