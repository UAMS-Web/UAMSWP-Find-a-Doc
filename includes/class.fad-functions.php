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
		$page_id = get_the_ID();
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
		$page_id = get_the_ID();
		$provider_region_IDs = array_merge($provider_region_IDs, get_field('physician_region', $page_id));
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
		$page_id = get_the_ID();
		$title_list[] = get_field('physician_title', $page_id);
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
			$page_id = get_the_ID();
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
			$page_id = get_the_ID();
			$title_list[] = get_field('physician_title', $page_id);
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

	// Bring in variables from outside of the function

		$labels_location_vars = isset($labels_location_vars) ? $labels_location_vars : uamswp_fad_labels_location();
			$location_single_name = $labels_location_vars['location_single_name']; // string
			$location_single_name_attr = $labels_location_vars['location_single_name_attr']; // string
			$location_plural_name = $labels_location_vars['location_plural_name']; // string

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
		$page_id = get_the_ID();
		$location_region_IDs[] = get_field('location_region', $page_id);
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
			$page_id = get_the_ID();
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

// Conditionally suppress ontology sections based on Find-a-Doc Settings configuration
function uamswp_fad_ontology_hide(
	$regions = '', // string|array // Region(s) associated with the item
	$service_lines = '' // string|array // Service line(s) associated with the item
) {

	// Check/define variables
	$regions = isset($regions) ? $regions : array();
	$service_lines = isset($service_lines) ? $service_lines : array();

	// If variables are strings, convert them to arrays
	$regions = is_array($regions) ? $regions : array( $regions );
	$service_lines = is_array($service_lines) ? $service_lines : array( $service_lines );

	$hide_medical_ontology = false;
	if ( have_rows('remove_ontology_criteria', 'option') ) {
		while( have_rows('remove_ontology_criteria', 'option') ) {
			the_row();
			$remove_region = get_sub_field('remove_regions', 'option');
			$remove_service_line = get_sub_field('remove_service_lines', 'option');

			if (
				(
					!empty($remove_region)
					&&
					empty( array_diff( $regions, $remove_region ) )
				)
				&&
				empty($remove_service_line)
			) { 
				// If the remove region array is not empty
				// and if all the item's regions are in the remove region array
				// and if the remove service line array is empty
				$hide_medical_ontology = true;
				break;
			} elseif (
				// If the remove region array is empty
				// and if the remove service line array is not empty
				// and if all the item's service lines are in the remove service line array
				empty($remove_region)
				&&
				(
					!empty($remove_service_line)
					&&
					empty( array_diff( $service_lines, $remove_service_line ) )
				)
			) {
				$hide_medical_ontology = true;
				break;
			} elseif(
				// If the remove region array is not empty
				// and if all the item's regions are in the remove region array
				// and if the remove service line array is not empty
				// and if all the item's service lines are in the remove service line array
				(
					!empty($remove_region)
					&&
					empty( array_diff( $regions, $remove_region ) )
				)
				&&
				(
					!empty($remove_service_line)
					&&
					empty( array_diff( $service_lines, $remove_service_line ) )
				)
			) {
				$hide_medical_ontology = true;
				break;
			}
		} // endwhile
	} // endif

	// Create and return an array to be used on the templates and template parts

		$ontology_hide_vars = array(
			'hide_medical_ontology'	=> $hide_medical_ontology // bool
		);
		return $ontology_hide_vars;

}

// Convert text string to HTML attribute-friendly text string
function uamswp_attr_conversion($input) {
	$input_attr = isset($input) ? $input : '';
	if ( empty($input_attr) ) {
		return '';
	}
	$input_attr = str_replace('"', '\'', $input_attr); // Replace double quotes with single quote
	$input_attr = htmlentities($input_attr, false, 'UTF-8'); // Convert all applicable characters to HTML entities
	$input_attr = str_replace('&nbsp;', ' ', $input_attr); // Convert non-breaking space with normal space
	$input_attr = html_entity_decode($input_attr); // Convert HTML entities to their corresponding characters
	return $input_attr;
}

// Get site header and site nav values for ontology subsections
function uamswp_fad_ontology_site_values(
	$page_id, // int // ID of the post
	$ontology_type = true, // bool (optional) // Ontology type of the post (true is ontology type, false is content type)
	$page_title = '', // string (optional) // Title of the post
	$page_url = '' // string (optional) // Permalink of the post
) {

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
		$page_top_level_query = empty($ancestors_ontology_farthest); // Get whether this fake subpage's parent item is the top-level item // bool

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
			$navbar_subbrand_title = isset($page_title) ? $page_title : get_the_title();
			$navbar_subbrand_title_attr = uamswp_attr_conversion($navbar_subbrand_title);
			$navbar_subbrand_title_url = isset($page_url) ? $page_url : get_permalink();
			if ( $ancestors_ontology_farthest ) {
				// If a farthest ancestor with the ontology type exists
				// Set the navbar-subbrand parent element using the that ancestor's values 
				$navbar_subbrand_parent = $ancestors_ontology_farthest_title;
				$navbar_subbrand_parent_attr = '';
				$navbar_subbrand_parent_url = $ancestors_ontology_farthest_url;
			} else {
				// Otherwise, do not define the navbar-subbrand parent element
				$navbar_subbrand_parent = '';
				$navbar_subbrand_parent_attr = '';
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
				$navbar_subbrand_parent_attr = '';
				$navbar_subbrand_parent_url = '';
			}
		}

	// Get related ontology items

		$providers = get_field('physician_expertise', $site_nav_id);
		$locations = get_field('location_expertise', $site_nav_id);
		$expertises = get_field('expertise_associated', $site_nav_id);
		$expertise_descendants = get_pages(
			array(
				'child_of'	=> $site_nav_id, // int (default: 0)
				'post_type'	=> 'expertise', // string (default: 'page')
			));
		$clinical_resources = get_field('expertise_clinical_resources', $site_nav_id);
		$conditions_cpt = get_field('expertise_conditions_cpt', $site_nav_id);
		$treatments_cpt = get_field('expertise_treatments_cpt', $site_nav_id);

	// Create and return an array to be used on the templates and template parts

		$ontology_site_values_vars = array(
			'site_nav_id'					=> $site_nav_id, // int
			'navbar_subbrand'				=> array (
				'title'		=> array(
					'name'	=> $navbar_subbrand_title, // string
					'attr'	=> $navbar_subbrand_title_attr, // string
					'url'	=> $navbar_subbrand_title_url, // string
				),
				'parent'	=> array(
					'name'	=> $navbar_subbrand_parent, // string
					'attr'	=> $navbar_subbrand_parent_attr, // string
					'url'	=> $navbar_subbrand_parent_url, // string
				)
			),
			'providers'						=> $providers, // int[]
			'locations'						=> $locations, // int[]
			'expertises'					=> $expertises, // int[]
			'expertise_descendants'			=> $expertise_descendants,
			'clinical_resources'			=> $clinical_resources, // int[]
			'conditions_cpt'				=> $conditions_cpt, // int[]
			'treatments_cpt'				=> $treatments_cpt, // int[]
			'ancestors_ontology_farthest'	=> $ancestors_ontology_farthest,
			'page_top_level_query'			=> $page_top_level_query // bool
		);
		return $ontology_site_values_vars;

}

// Construct non-standard entry title
function uamswp_fad_post_title(
	$entry_title_text, // string // Entry title text
	$entry_header_style, // string // Entry header style
	$entry_title_text_supertitle = '', // string (optional) // Entry supertitle text
	$entry_title_text_subtitle = '', // string (optional) // Entry subtitle text
	$entry_title_text_body = '', // string (optional) // Entry header lead paragraph text
	$entry_title_image_desktop = '', // int (optional) // Entry header background image for desktop breakpoints
	$entry_title_image_mobile = '' // int (optional) // Entry header background image for mobile breakpoints
) {
	// Add the following (without the commenting) to the relevant template to remove Genesis-standard post title and markup
	// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	// remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

	// Add the following (without the commenting) to the relevant template to add this header style
	// add_action( 'genesis_before_content', 'uamswp_fad_post_title' );

	// Add one of the following variable definitions to the relevant template to indicate which entry header style to use

		// Normal style
		// $entry_header_style = 'normal';

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

	// Check/define variables

	$entry_title_text = ( isset($entry_title_text) && !empty($entry_title_text) ) ? $entry_title_text : get_the_title();
	$entry_header_style = ( isset($entry_header_style) && !empty($entry_header_style) ) ? $entry_header_style : 'graphic';
	$entry_title_text_supertitle = isset($entry_title_text_supertitle) ? $entry_title_text_supertitle : '';
	$entry_title_text_subtitle = isset($entry_title_text_subtitle) ? $entry_title_text_subtitle : '';
	$entry_title_text_body = isset($entry_title_text_body) ? $entry_title_text_body : '';
	$entry_title_image_desktop = isset($entry_title_image_desktop) ? $entry_title_image_desktop : '';
	$entry_title_image_mobile = isset($entry_title_image_mobile) ? $entry_title_image_mobile : '';

	include( UAMS_FAD_PATH . '/templates/parts/entry-title-' . $entry_header_style . '.php');
}

// Queries for whether each of the related ontology content sections should be displayed on ontology pages/subsections

	// Query for whether related providers content section should be displayed on ontology pages/subsections
	function uamswp_fad_provider_query(
		$providers, // int[]
		$jump_link_count = 0 // int
	) {

		if ( $providers ) {
			$args = array(
				'post__in' => $providers,
				'post_type' => 'provider',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'order' => 'ASC',
				'orderby' => 'title',
				// 'no_found_rows' => true, // counts posts, remove if pagination required
				'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
				'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
				'fields' => 'ids',
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
		} else {
			$provider_query = '';
			$provider_section_show = false;
			$provider_ids = '';
			$provider_count = 0;
		}

		// Create and return an array to be used on the templates and template parts

			$provider_query_vars = array(
				'provider_query'		=> $provider_query, // WP_Post[]
				'provider_section_show'	=> $provider_section_show, // bool
				'provider_ids'			=> $provider_ids, // int[]
				'provider_count'		=> $provider_count, // int
				'jump_link_count'		=> $jump_link_count // int
			);
			return $provider_query_vars;

	}

	// Query for whether related locations content section should be displayed on a page
	function uamswp_fad_location_query(
		$locations, // int[]
		$jump_link_count = 0 // int
	) {

		$location_valid = false;

		if ( $locations ) {
			$args = array(
				'post__in' => $locations,
				'post_type' => 'location',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'order' => 'ASC',
				'orderby' => 'title',
				'no_found_rows' => true, // counts posts, remove if pagination required
				'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
				'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
				'fields' => 'ids',
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
		} else {
			$location_query = '';
			$location_section_show = false;
			$location_ids = '';
			$location_count = 0;
		}

		// Create and return an array to be used on the templates and template parts

			$location_query_vars = array(
				'location_query'		=> $location_query, // WP_Post[]
				'location_section_show'	=> $location_section_show, // bool
				'location_ids'			=> $location_ids, // int[]
				'location_count'		=> $location_count, // int
				'location_valid'		=> $location_valid, // bool
				'jump_link_count'		=> $jump_link_count // int
			);
			return $location_query_vars;

	}

	// Query for whether descendant locations content section should be displayed on a page
	function uamswp_fad_location_descendant_query(
		$current_id, // int
		$location_descendants, // int[]
		$jump_link_count = 0 // int
	) {

		$location_descendant_valid = false;

		if ( $location_descendants && 0 != count($location_descendants) ) {
			$args = array(
				'post_type' => 'location',
				'post_status' => 'publish',
				'post_parent' => $current_id,
				'order' => 'ASC',
				'orderby' => 'title',
				'posts_per_page' => -1,
				'fields' => 'ids',
				'meta_query' => array(
					array(
						'key' => 'location_hidden',
						'value' => '1',
						'compare' => '!=',
					)
				),
			);
			$location_descendant_query = new WP_Query( $args );
			if( ( $location_descendants && $location_descendant_query->have_posts() ) ) {
				$location_descendant_section_show = true;
				$location_descendant_ids = $location_descendant_query->posts;
				$location_descendant_count = count($location_descendant_query->posts);
				$jump_link_count++;
			} else {
				$location_descendant_section_show = false;
			}

			// Check for valid descendant locations
			if ( $location_descendants && $location_descendant_query->have_posts() ) {
				foreach( $location_descendants as $location_descendant ) {
					if ( get_post_status ( $location_descendant ) == 'publish' ) {
						$location_descendant_valid = true;
						$break;
					}
				}
			}
		} else {
			$location_descendant_query = '';
			$location_descendant_section_show = false;
			$location_descendant_ids = '';
			$location_descendant_count = '';
			$location_descendant_valid = false;
		}

		// Create and return an array to be used on the templates and template parts

			$location_descendant_query_vars = array(
				'location_descendant_query'			=> $location_descendant_query, // WP_Post[]
				'location_descendant_section_show'	=> $location_descendant_section_show, // bool
				'location_descendant_ids'			=> $location_descendant_ids, // int[]
				'location_descendant_count'			=> $location_descendant_count, // int
				'location_descendant_valid'			=> $location_descendant_valid, // bool
				'jump_link_count'					=> $jump_link_count // int
			);
			return $location_descendant_query_vars;

	}

	// Query for whether descendant areas of expertise content section should be displayed on ontology pages/subsections
	function uamswp_fad_expertise_descendant_query(
		$expertise_descendants, // int[]
		$content_placement = 'profile', // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
		$site_nav_id = '', // int (optional)
		$jump_link_count = 0 // int (optional)
	) {

		// Check/define variables

		$content_placement = ( isset($content_placement) && !empty($content_placement) ) ? $content_placement : 'profile';

		if ( !isset($site_nav_id) || empty($site_nav_id) ) {
			$page_id = isset($page_id) ? $page_id : get_the_id();

			$ontology_site_values_vars = isset($ontology_site_values_vars) ? $ontology_site_values_vars : uamswp_fad_ontology_site_values(
				$page_id // int // ID of the post
			);
				$site_nav_id = $ontology_site_values_vars['site_nav_id']; // int
		}

		if ( $expertise_descendants ) {

			$expertise_descendant_args = array(
				'post_parent' => $site_nav_id,
				'post_type' => 'expertise',
				'post_status' => 'publish',
				'posts_per_page' => -1, // We do not want to limit the post count
				'order' => 'ASC',
				'orderby' => 'title',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'hide_from_sub_menu',
						'value' => '1',
						'compare' => '!=',
					),
					array(
						'relation' => 'OR',
						array(
							'key' => 'expertise_type',
							'value' => '0',
							'compare' => '!=',
						),
						array(
							'key' => 'expertise_type',
							'compare' => 'NOT EXISTS' // If the item has not been updated since 'expertise_type' was added
						),
					),
				),
			);
			$expertise_descendant_query = new WP_Query( $expertise_descendant_args );
			if( ( $expertise_descendants && $expertise_descendant_query->have_posts() ) ) {
				$expertise_descendant_section_show = true;
				$expertise_descendant_ids = $expertise_descendant_query->posts;
				$expertise_descendant_count = count($expertise_descendant_query->posts);
				$jump_link_count = $jump_link_count + 1;
			} else {
				$expertise_descendant_section_show = false;
				$expertise_descendant_ids = '';
				$expertise_descendant_count = 0;
			}

			if ( $content_placement == 'subsection' ) {
				$expertise_content_args = array(
					'post_parent' => $site_nav_id,
					'post_type' => 'expertise',
					'post_status' => 'publish',
					'posts_per_page' => -1, // We do not want to limit the post count
					'order' => 'ASC',
					'orderby' => 'title',
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'hide_from_sub_menu',
							'value' => '1',
							'compare' => '!=',
						),
						array(
							'key' => 'expertise_type',
							'value' => '0',
							'compare' => '=',
						),
					),
				);
				$expertise_content_query = new WP_Query( $expertise_content_args );
				$expertise_content_nav = '';
				if( ( $expertise_descendants && $expertise_content_query->have_posts() ) ) {
					$expertise_content_nav_show = true;
					$expertise_content_ids = $expertise_content_query->posts;
					$expertise_content_count = count($expertise_content_query->posts);
					while ( $expertise_content_query->have_posts() ) {
						$expertise_content_query->the_post();
						$page_id = get_the_ID();
						$page_title = get_the_title();
						$page_title_attr = uamswp_attr_conversion($page_title);
						$page_url = get_permalink();
						$expertise_content_nav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item menu-item-'. $page_id .' nav-item active"><a title="'. $page_title_attr .'" href="'. $page_url .'" class="nav-link"><span itemprop="name">'. $page_title .'</span></a></li>';
					} // endwhile
					wp_reset_postdata();
				} else {
					$expertise_content_nav_show = false;
					$expertise_content_ids = '';
					$expertise_content_count = 0;
				}
			} else {
				$expertise_content_query = '';
				$expertise_content_nav_show = false;
				$expertise_content_ids = '';
				$expertise_content_count = 0;
				$expertise_content_nav = '';
			}
		} else {
			$expertise_descendant_query = '';
			$expertise_descendant_section_show = false;
			$expertise_descendant_ids = '';
			$expertise_descendant_count = 0;
			$expertise_content_query = '';
			$expertise_content_nav_show = false;
			$expertise_content_ids = '';
			$expertise_content_count = 0;
			$expertise_content_nav = '';
		}

		// Create and return an array to be used on the templates and template parts

			$expertise_descendant_query_vars = array(
				'expertise_descendant_query'		=> $expertise_descendant_query, // WP_Post[]
				'expertise_descendant_section_show'	=> $expertise_descendant_section_show, // bool
				'expertise_descendant_ids'			=> $expertise_descendant_ids, // int[]
				'expertise_descendant_count'		=> $expertise_descendant_count, // int
				'expertise_content_query'			=> $expertise_content_query, // WP_Post[]
				'expertise_content_nav_show'		=> $expertise_content_nav_show, // bool
				'expertise_content_ids'				=> $expertise_content_ids, // int[]
				'expertise_content_count'			=> $expertise_content_count, // int
				'expertise_content_nav'				=> $expertise_content_nav, // string
				'jump_link_count'					=> $jump_link_count // int
			);
			return $expertise_descendant_query_vars;

	}

	// Query for whether related areas of expertise content section should be displayed on ontology pages/subsections
	function uamswp_fad_expertise_query(
		$expertises, // int[]
		$jump_link_count = 0 // int
	) {

		if ( $expertises ) {
			$args = array(
				'post__in'	=> $expertises,
				'post_type' => 'expertise',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'order' => 'ASC',
				'orderby' => 'title',
			);
			$expertise_query = new WP_Query( $args );
			if ( ( $expertises && $expertise_query->have_posts() ) ) {
				$expertise_section_show = true;
				$expertise_ids = $expertise_query->posts;
				$expertise_count = count($expertise_query->posts);
				$jump_link_count = $jump_link_count + 1;
			} else {
				$expertise_section_show = false;
			}
		} else {
			$expertise_query = '';
			$expertise_section_show = false;
			$expertise_ids = '';
			$expertise_count = 0;
		}

		// Create and return an array to be used on the templates and template parts

			$expertise_query_vars = array(
				'expertise_query'			=> $expertise_query, // WP_Post[]
				'expertise_section_show'	=> $expertise_section_show, // bool
				'expertise_ids'				=> $expertise_ids, // int[]
				'expertise_count'			=> $expertise_count, // int
				'jump_link_count'			=> $jump_link_count // int
			);
			return $expertise_query_vars;

	}

	// Query for whether related clinical resources content section should be displayed on ontology pages/subsections
	function uamswp_fad_clinical_resource_query(
		$clinical_resources, // int[] // Value of the related clinical resources input
		$clinical_resource_posts_per_page = '', // int (optional)
		$jump_link_count = 0 // int
	) {

		// Bring in variables from outside of the function

			if ( !isset($clinical_resource_posts_per_page) ) {
				if ( !isset($clinical_resource_posts_per_page_section) ) {
					$posts_per_page_clinical_resource_general_vars = isset($posts_per_page_clinical_resource_general_vars) ? $posts_per_page_clinical_resource_general_vars : uamswp_fad_posts_per_page_clinical_resource_general();
						$clinical_resource_posts_per_page_section = $posts_per_page_clinical_resource_general_vars['clinical_resource_posts_per_page_section']; // int
				}
				$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_section;
			}

		if ( $clinical_resources ) {
			$args = array(
				'post__in' => $clinical_resources,
				'post_type' => 'clinical-resource',
				'post_status' => 'publish',
				'posts_per_page' => $clinical_resource_posts_per_page,
				'order' => 'DESC',
				'orderby' => 'post_date',
			);
			$clinical_resource_query = new WP_Query( $args );

			// Check if Clinical Resources section should be displayed
			if( ( $clinical_resources && $clinical_resource_query->have_posts() ) ) {

				$clinical_resource_section_show = true;
				$clinical_resource_ids = $clinical_resource_query->posts;
				$clinical_resource_count = count($clinical_resource_query->posts);
				$jump_link_count = $jump_link_count + 1;

				// Count valid clinical resource items

					$clinical_resource_count = 0;
					foreach( $clinical_resources as $resource ) {
						if ( get_post_status ( $resource ) == 'publish' ) {
							$clinical_resource_count++;
						}
					}

			} else {

				$clinical_resource_section_show = false;

			}
		} else {
			$clinical_resource_query = '';
			$clinical_resource_section_show = false;
			$clinical_resource_ids = '';
			$clinical_resource_count = 0;
		}

		// Create and return an array to be used on the templates and template parts

			$clinical_resource_query_vars = array(
				'clinical_resource_query'			=> $clinical_resource_query, // WP_Post[]
				'clinical_resource_section_show'	=> $clinical_resource_section_show, // bool
				'clinical_resource_ids'				=> $clinical_resource_ids, // int[]
				'clinical_resource_count'			=> $clinical_resource_count, // int
				'clinical_resource_posts_per_page'	=> $clinical_resource_posts_per_page, // int
				'jump_link_count'					=> $jump_link_count // int
			);
			return $clinical_resource_query_vars;

	}

	// Query for whether related conditions content section should be displayed on ontology pages/subsections
	function uamswp_fad_condition_query(
		$conditions_cpt, // int[]
		$condition_treatment_section_show = false, // bool (optional)
		$ontology_type = true, // bool (optional)
		$jump_link_count = 0 // int (optional)
	) {

		if ( $conditions_cpt ) {
			$args = array(
				'post_type' => 'condition',
				'post_status' => 'publish',
				'orderby' => 'title',
				'order' => 'ASC',
				'posts_per_page' => -1,
				'post__in' => $conditions_cpt
			);
			$condition_cpt_query = new WP_Query( $args );
			if( ( $conditions_cpt && $condition_cpt_query->posts ) && ("1" == $ontology_type || !isset($ontology_type) ) ) {
				$condition_section_show = true;
				$condition_treatment_section_show = true;
				$condition_ids = $condition_cpt_query->posts;
				$condition_count = count($condition_cpt_query->posts);
				$jump_link_count = $jump_link_count + 1;
			} else {
				$condition_section_show = false;
				$condition_ids = '';
				$condition_count = 0;
			}
		} else {
			$condition_cpt_query = '';
			$condition_section_show = false;
			$condition_ids = '';
			$condition_count = 0;
		}

		$condition_treatment_section_show = isset($condition_treatment_section_show) ? $condition_treatment_section_show : false;
		$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();

		// Create and return an array to be used on the templates and template parts

			$condition_query_vars = array(
				'condition_cpt_query'				=> $condition_cpt_query, // WP_Post[]
				'condition_section_show'			=> $condition_section_show, // bool
				'condition_treatment_section_show'	=> $condition_treatment_section_show, // bool
				'condition_ids'						=> $condition_ids, // int[]
				'condition_count'					=> $condition_count, // int
				'schema_medical_specialty'			=> $schema_medical_specialty, // array
				'jump_link_count'					=> $jump_link_count // int
			);
			return $condition_query_vars;

	}

	// Query for whether related treatments content section should be displayed on ontology pages/subsections
	function uamswp_fad_treatment_query(
		$treatments_cpt, // int[]
		$condition_treatment_section_show = false, // bool (optional)
		$ontology_type = true, // bool (optional)
		$jump_link_count = 0 // int (optional)
	) {

		if ( $treatments_cpt ) {
			$args = array(
				'post_type' => 'treatment',
				'post_status' => 'publish',
				'orderby' => 'title',
				'order' => 'ASC',
				'posts_per_page' => -1,
				'post__in' => $treatments_cpt
			);
			$treatment_cpt_query = new WP_Query( $args );
			if( ( $treatments_cpt && $treatment_cpt_query->posts ) && ("1" == $ontology_type || !isset($ontology_type) ) ) {
				$treatment_section_show = true;
				$condition_treatment_section_show = true;
				$treatment_ids = $treatment_cpt_query->posts;
				$treatment_count = count($treatment_cpt_query->posts);
				$jump_link_count = $jump_link_count + 1;
			} else {
				$treatment_section_show = false;
				$treatment_ids = '';
				$treatment_count = 0;
			}
		} else {
			$treatment_cpt_query = '';
			$treatment_section_show = false;
			$treatment_ids = '';
			$treatment_count = 0;
		}

		$condition_treatment_section_show = isset($condition_treatment_section_show) ? $condition_treatment_section_show : false;
		$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();

		// Create and return an array to be used on the templates and template parts

			$treatment_query_vars = array(
				'treatment_cpt_query'				=> $treatment_cpt_query, // WP_Post[]
				'treatment_section_show'			=> $treatment_section_show, // bool
				'condition_treatment_section_show'	=> $condition_treatment_section_show, // bool
				'treatment_ids'						=> $treatment_ids, // int[]
				'treatment_count'					=> $treatment_count, // int
				'schema_medical_specialty'			=> $schema_medical_specialty, // array
				'jump_link_count'					=> $jump_link_count // int
			);
			return $treatment_query_vars;

	}

// Construct ontology subsection primary navigation
function uamswp_fad_ontology_nav_menu(
	$page_id, // int // ID of the post
	$ontology_type, // bool (optional) // Ontology type of the post (true is ontology type, false is content type)
	$page_title, // string (optional) // Title of the post
	$page_url // string (optional) // Permalink of the post
) {

	// Bring in variables from outside of the function

		$ontology_site_values_vars = isset($ontology_site_values_vars) ? $ontology_site_values_vars : uamswp_fad_ontology_site_values(
			$page_id, // int // ID of the post
			$ontology_type, // bool (optional) // Ontology type of the post (true is ontology type, false is content type)
			$page_title, // string (optional) // Title of the post
			$page_url // string (optional) // Permalink of the post
		);
			$site_nav_id = $ontology_site_values_vars['site_nav_id']; // int
			$providers = $ontology_site_values_vars['providers']; // int[]
			$locations = $ontology_site_values_vars['locations']; // int[]
			$expertises = $ontology_site_values_vars['expertises']; // int[]
			$expertise_descendants = $ontology_site_values_vars['expertise_descendants'];
			$clinical_resources = $ontology_site_values_vars['clinical_resources']; // int[]

		$provider_query_vars = isset($provider_query_vars) ? $provider_query_vars : uamswp_fad_provider_query(
			$providers // int[]
		);
			$provider_section_show = $provider_query_vars['provider_section_show']; // bool

		$location_query_vars = isset($location_query_vars) ? $location_query_vars : uamswp_fad_location_query(
			$locations // int[]
		);
			$location_section_show = $location_query_vars['location_section_show']; // bool

		$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query(
			$expertises // int[]
		);
			$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool

		$clinical_resource_query_vars = isset($clinical_resource_query_vars) ? $clinical_resource_query_vars : uamswp_fad_clinical_resource_query(
			$clinical_resources // int[]
		);
			$clinical_resource_section_show = $clinical_resource_query_vars['clinical_resource_section_show']; // bool

		$expertise_descendant_query_vars = isset($expertise_descendant_query_vars) ? $expertise_descendant_query_vars : uamswp_fad_expertise_descendant_query(
			$expertise_descendants, // int[]
			'subsection', // string (optional) // Expected values: 'subsection' or 'profile'
			$site_nav_id // int (optional)
		);
			$expertise_descendant_section_show = $expertise_descendant_query_vars['expertise_descendant_section_show']; // bool
			$expertise_content_nav_show = $expertise_descendant_query_vars['expertise_content_nav_show']; // bool
			$expertise_content_nav = $expertise_descendant_query_vars['expertise_content_nav']; // string

		$labels_provider_vars = isset($labels_provider_vars) ? $labels_provider_vars : uamswp_fad_labels_provider();
			$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string
			$provider_plural_name_attr = $labels_provider_vars['provider_plural_name_attr']; // string

		$labels_location_vars = isset($labels_location_vars) ? $labels_location_vars : uamswp_fad_labels_location();
			$location_plural_name = $labels_location_vars['location_plural_name']; // string
			$location_plural_name_attr = $labels_location_vars['location_plural_name_attr']; // string

		$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
			$expertise_plural_name = $labels_expertise_vars['expertise_plural_name']; // string
			$expertise_plural_name_attr = $labels_expertise_vars['expertise_plural_name_attr']; // string

		$labels_expertise_descendant_vars = isset($labels_expertise_descendant_vars) ? $labels_expertise_descendant_vars : uamswp_fad_labels_expertise_descendant();
			$expertise_descendant_plural_name = $labels_expertise_descendant_vars['expertise_descendant_plural_name']; // string
			$expertise_descendant_plural_name_attr = $labels_expertise_descendant_vars['expertise_descendant_plural_name_attr']; // string

		$labels_clinical_resource_vars = isset($labels_clinical_resource_vars) ? $labels_clinical_resource_vars : uamswp_fad_labels_clinical_resource();
			$clinical_resource_plural_name = $labels_clinical_resource_vars['clinical_resource_plural_name']; // string
			$clinical_resource_plural_name_attr = $labels_clinical_resource_vars['clinical_resource_plural_name_attr']; // string

	include( UAMS_FAD_PATH . '/templates/parts/single-expertise-nav.php');
}

// Construct ontology subsection site header
function uamswp_fad_ontology_header(
	$page_id, // int // ID of the post
	$ontology_type, // bool (optional) // Ontology type of the post (true is ontology type, false is content type)
	$page_title, // string (optional) // Title of the post
	$page_url // string (optional) // Permalink of the post
) {

	// Bring in variables from outside of the function

		$ontology_site_values_vars = isset($ontology_site_values_vars) ? $ontology_site_values_vars : uamswp_fad_ontology_site_values(
			$page_id, // int // ID of the post
			$ontology_type, // bool (optional) // Ontology type of the post (true is ontology type, false is content type)
			$page_title, // string (optional) // Title of the post
			$page_url // string (optional) // Permalink of the post
		);
			$navbar_subbrand_title = $ontology_site_values_vars['navbar_subbrand']['title']['name']; // string
			$navbar_subbrand_title_url = $ontology_site_values_vars['navbar_subbrand']['title']['url']; // string
			$navbar_subbrand_parent = $ontology_site_values_vars['navbar_subbrand']['parent']['name']; // string
			$navbar_subbrand_parent_url = $ontology_site_values_vars['navbar_subbrand']['parent']['url']; // string

	include( UAMS_FAD_PATH . '/templates/parts/single-expertise-header.php');

}

// Construct the meta keywords element
function uamswp_keyword_hook_header() { 

	// Bring in variables from outside of the function

		// Typically defined on the template

			global $keywords;

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
function uamswp_fad_ontology_appointment(
	$appointment_section_show // bool
) {

	// Bring in variables from outside of the function

		$labels_location_vars = isset($labels_location_vars) ? $labels_location_vars : uamswp_fad_labels_location();
			$location_single_name = $labels_location_vars['location_single_name']; // string

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

	// $crumbs is a multidimensional array.
	//     First array: key=position,
	//     second array: 0=>page title, 1=>URL, 2=>ID (since version 6.1)

	// Bring in variables from outside of the function

		// Typically defined on the template

			global $fpage_name;

	$crumbs[] = array($fpage_name, '');
	return $crumbs;

}

// Add page template class to body element's classes
function uamswp_page_body_class( $classes ) {

	// Bring in variables from outside of the function

		// Typically defined on the template

			global $template_type; // Expected values: 'default', 'page_landing' or 'marketing'

	$classes[] = 'page-template-' . $template_type;
	return $classes;

}

// Add bg-white class to article.entry element
function uamswp_add_entry_class( $attributes ) {
	$attributes['class'] = $attributes['class']. ' bg-white';
	return $attributes;
}

// Query for whether UAMS Health Talk podcast section should be displayed on ontology pages/subsections
function uamswp_fad_podcast_query(
	$podcast_name, // string
	$jump_link_count = 0 // int (optional)
) {

	// Check if podcast section should be displayed
	if ( $podcast_name ) {
		$podcast_section_show = true;
		$jump_link_count = $jump_link_count + 1;
	} else {
		$podcast_section_show = false;
	}

	// Create and return an array to be used on the templates and template parts

		$podcast_query_vars = array(
			'podcast_section_show'	=> $podcast_section_show, // bool
			'jump_link_count'		=> $jump_link_count // int
		);
		return $podcast_query_vars;

}

// Construct UAMS Health Talk podcast section
function uamswp_fad_podcast(
	$podcast_name, // string
	$podcast_section_show, // bool
	$podcast_filter, // string // Expected values: 'tag' or 'doctor'
	$podcast_subject // string
) {

	// Bring in variables from outside of the function

		$labels_provider_vars = isset($labels_provider_vars) ? $labels_provider_vars : uamswp_fad_labels_provider();
			$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string

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

// Create substitutions for use regarding ontology text elements
function uamswp_fad_fpage_text_replace(
	$string, // string
	$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
) {

	// Check/define variables

		if ( !isset($page_title) ) {
			$page_title = isset($page_titles['page_title']) ? $page_titles['page_title'] : '';
		}
		if ( !isset($page_title_phrase) ) {
			$page_title_phrase = isset($page_titles['page_title_phrase']) ? $page_titles['page_title_phrase'] : '';
		}
		if ( !isset($short_name) ) {
			$short_name = isset($page_titles['short_name']) ? $page_titles['short_name'] : '';
		}
		if ( !isset($short_name_possessive) ) {
			$short_name_possessive = isset($page_titles['short_name_possessive']) ? $page_titles['short_name_possessive'] : '';
		}

	// Bring in variables from outside of the function

		$labels_provider_vars = isset($labels_provider_vars) ? $labels_provider_vars : uamswp_fad_labels_provider();
			$provider_single_name = $labels_provider_vars['provider_single_name']; // string
			$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string
			$placeholder_provider_single_name = $labels_provider_vars['placeholder_provider_single_name']; // string
			$placeholder_provider_plural_name = $labels_provider_vars['placeholder_provider_plural_name']; // string
			$placeholder_provider_short_name = $labels_provider_vars['placeholder_provider_short_name']; // string
			$placeholder_provider_short_name_possessive = $labels_provider_vars['placeholder_provider_short_name_possessive']; // string

		$archive_text_provider_vars = isset($archive_text_provider_vars) ? $archive_text_provider_vars : uamswp_fad_archive_text_provider();
			$provider_archive_headline = $archive_text_provider_vars['provider_archive_headline']; // string
			$placeholder_provider_archive_headline = $archive_text_provider_vars['placeholder_provider_archive_headline']; // string

		$labels_location_vars = isset($labels_location_vars) ? $labels_location_vars : uamswp_fad_labels_location();
			$location_single_name = $labels_location_vars['location_single_name']; // string
			$location_plural_name = $labels_location_vars['location_plural_name']; // string
			$placeholder_location_single_name = $labels_location_vars['placeholder_location_single_name']; // string
			$placeholder_location_plural_name = $labels_location_vars['placeholder_location_plural_name']; // string
			$placeholder_location_page_title = $labels_location_vars['placeholder_location_page_title']; // string
			$placeholder_location_page_title_phrase = $labels_location_vars['placeholder_location_page_title_phrase']; // string

		$labels_location_descendant_vars = isset($labels_location_descendant_vars) ? $labels_location_descendant_vars : uamswp_fad_labels_location_descendant();
			$location_descendant_single_name = $labels_location_descendant_vars['location_descendant_single_name']; // string
			$location_descendant_plural_name = $labels_location_descendant_vars['location_descendant_plural_name']; // string
			$placeholder_location_descendant_single_name = $labels_location_descendant_vars['placeholder_location_descendant_single_name']; // string
			$placeholder_location_descendant_plural_name = $labels_location_descendant_vars['placeholder_location_descendant_plural_name']; // string

		$archive_text_location_vars = isset($archive_text_location_vars) ? $archive_text_location_vars : uamswp_fad_archive_text_location();
			$location_archive_headline = $archive_text_location_vars['location_archive_headline']; // string
			$placeholder_location_archive_headline = $archive_text_location_vars['placeholder_location_archive_headline']; // string

		$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
			$expertise_single_name = $labels_expertise_vars['expertise_single_name']; // string
			$expertise_plural_name = $labels_expertise_vars['expertise_plural_name']; // string
			$placeholder_expertise_single_name = $labels_expertise_vars['placeholder_expertise_single_name']; // string
			$placeholder_expertise_plural_name = $labels_expertise_vars['placeholder_expertise_plural_name']; // string
			$placeholder_expertise_page_title = $labels_expertise_vars['placeholder_expertise_page_title']; // string

		$labels_expertise_descendant_vars = isset($labels_expertise_descendant_vars) ? $labels_expertise_descendant_vars : uamswp_fad_labels_expertise_descendant();
			$expertise_descendant_single_name = $labels_expertise_descendant_vars['expertise_descendant_single_name']; // string
			$expertise_descendant_plural_name = $labels_expertise_descendant_vars['expertise_descendant_plural_name']; // string
			$placeholder_expertise_descendant_single_name = $labels_expertise_descendant_vars['placeholder_expertise_descendant_single_name']; // string
			$placeholder_expertise_descendant_plural_name = $labels_expertise_descendant_vars['placeholder_expertise_descendant_plural_name']; // string

		$archive_text_expertise_vars = isset($archive_text_expertise_vars) ? $archive_text_expertise_vars : uamswp_fad_archive_text_expertise();
			$expertise_archive_headline = $archive_text_expertise_vars['expertise_archive_headline']; // string
			$expertise_archive_intro_text = $archive_text_expertise_vars['expertise_archive_intro_text']; // string
			$placeholder_expertise_archive_headline = $archive_text_expertise_vars['placeholder_expertise_archive_headline']; // string
			$placeholder_expertise_archive_intro_text = $archive_text_expertise_vars['placeholder_expertise_archive_intro_text']; // string

		$labels_clinical_resource_vars = isset($labels_clinical_resource_vars) ? $labels_clinical_resource_vars : uamswp_fad_labels_clinical_resource();
			$clinical_resource_single_name = $labels_clinical_resource_vars['clinical_resource_single_name']; // string
			$clinical_resource_plural_name = $labels_clinical_resource_vars['clinical_resource_plural_name']; // string
			$placeholder_clinical_resource_single_name = $labels_clinical_resource_vars['placeholder_clinical_resource_single_name']; // string
			$placeholder_clinical_resource_plural_name = $labels_clinical_resource_vars['placeholder_clinical_resource_plural_name']; // string

		$archive_text_clinical_resource_vars = isset($archive_text_clinical_resource_vars) ? $archive_text_clinical_resource_vars : uamswp_fad_archive_text_clinical_resource();
			$clinical_resource_archive_headline = $archive_text_clinical_resource_vars['clinical_resource_archive_headline']; // string
			$placeholder_clinical_resource_archive_headline = $archive_text_clinical_resource_vars['placeholder_clinical_resource_archive_headline']; // string

		$labels_clinical_resource_facet_vars = isset($labels_clinical_resource_facet_vars) ? $labels_clinical_resource_facet_vars : uamswp_fad_labels_clinical_resource_facet();
			$clinical_resource_type_single_name = $labels_clinical_resource_facet_vars['clinical_resource_type_single_name']; // string
			$clinical_resource_type_plural_name = $labels_clinical_resource_facet_vars['clinical_resource_type_plural_name']; // string
			$placeholder_clinical_resource_type_single_name = $labels_clinical_resource_facet_vars['placeholder_clinical_resource_type_single_name']; // string
			$placeholder_clinical_resource_type_plural_name = $labels_clinical_resource_facet_vars['placeholder_clinical_resource_type_plural_name']; // string

		$labels_condition_treatment_vars = isset($labels_condition_treatment_vars) ? $labels_condition_treatment_vars : uamswp_fad_labels_condition_treatment();
			$condition_treatment_single_name = $labels_condition_treatment_vars['condition_treatment_single_name']; // string
			$condition_treatment_plural_name = $labels_condition_treatment_vars['condition_treatment_plural_name']; // string
			$placeholder_condition_treatment_single_name = $labels_condition_treatment_vars['placeholder_condition_treatment_single_name']; // string
			$placeholder_condition_treatment_plural_name = $labels_condition_treatment_vars['placeholder_condition_treatment_plural_name']; // string

		$labels_condition_vars = isset($labels_condition_vars) ? $labels_condition_vars : uamswp_fad_labels_condition();
			$condition_single_name = $labels_condition_vars['condition_single_name']; // string
			$condition_plural_name = $labels_condition_vars['condition_plural_name']; // string
			$placeholder_condition_single_name = $labels_condition_vars['placeholder_condition_single_name']; // string
			$placeholder_condition_plural_name = $labels_condition_vars['placeholder_condition_plural_name']; // string

		$archive_text_condition_vars = isset($archive_text_condition_vars) ? $archive_text_condition_vars : uamswp_fad_archive_text_condition();
			$condition_archive_headline = $archive_text_condition_vars['condition_archive_headline']; // string
			$condition_archive_intro_text = $archive_text_condition_vars['condition_archive_intro_text']; // string
			$placeholder_condition_archive_headline = $archive_text_condition_vars['placeholder_condition_archive_headline']; // string
			$placeholder_condition_archive_intro_text = $archive_text_condition_vars['placeholder_condition_archive_intro_text']; // string

		$labels_treatment_vars = isset($labels_treatment_vars) ? $labels_treatment_vars : uamswp_fad_labels_treatment();
			$treatment_single_name = $labels_treatment_vars['treatment_single_name']; // string
			$treatment_plural_name = $labels_treatment_vars['treatment_plural_name']; // string
			$placeholder_treatment_single_name = $labels_treatment_vars['placeholder_treatment_single_name']; // string
			$placeholder_treatment_plural_name = $labels_treatment_vars['placeholder_treatment_plural_name']; // string

		$archive_text_treatment_vars = isset($archive_text_treatment_vars) ? $archive_text_treatment_vars : uamswp_fad_archive_text_treatment();
			$treatment_archive_headline = $archive_text_treatment_vars['treatment_archive_headline']; // string
			$treatment_archive_intro_text = $archive_text_treatment_vars['treatment_archive_intro_text']; // string
			$placeholder_treatment_archive_headline = $archive_text_treatment_vars['placeholder_treatment_archive_headline']; // string
			$placeholder_treatment_archive_intro_text = $archive_text_treatment_vars['placeholder_treatment_archive_intro_text']; // string

	// Check variables
	$page_title = ( isset($page_title) && !empty($page_title) ) ? $page_title : '';
	$page_title_phrase = ( isset($page_title_phrase) && !empty($page_title_phrase) ) ? $page_title_phrase : '';
	$short_name = ( isset($short_name) && !empty($short_name) ) ? $short_name : '';
	$short_name_possessive = ( isset($short_name_possessive) && !empty($short_name_possessive) ) ? $short_name_possessive : '';

	// Create array for defining text substitutions
	// Key = old
	// Value = new
	$fpage_text_replace = array();

		// Find-a-Doc Settings values for ontology item labels

			// Find-a-Doc Settings values for provider labels
			$fpage_text_replacements[$placeholder_provider_single_name] = $provider_single_name;
			$fpage_text_replacements[strtolower($placeholder_provider_single_name ?? '')] = strtolower($provider_single_name ?? '');
			$fpage_text_replacements[$placeholder_provider_plural_name] = $provider_plural_name;
			$fpage_text_replacements[strtolower($placeholder_provider_plural_name ?? '')] = strtolower($provider_plural_name ?? '');

			// Find-a-Doc Settings values for provider archive page text
			$fpage_text_replacements[$placeholder_provider_archive_headline] = $provider_archive_headline;

			// Find-a-Doc Settings values for location labels
			$fpage_text_replacements[$placeholder_location_single_name] = $location_single_name;
			$fpage_text_replacements[strtolower($placeholder_location_single_name ?? '')] = strtolower($location_single_name ?? '');
			$fpage_text_replacements[$placeholder_location_plural_name] = $location_plural_name;
			$fpage_text_replacements[strtolower($placeholder_location_plural_name ?? '')] = strtolower($location_plural_name ?? '');

			// Find-a-Doc Settings values for location descendant item labels
			$fpage_text_replacements[$placeholder_location_descendant_single_name] = $location_descendant_single_name;
			$fpage_text_replacements[strtolower($placeholder_location_descendant_single_name ?? '')] = strtolower($location_descendant_single_name ?? '');
			$fpage_text_replacements[$placeholder_location_descendant_plural_name] = $location_descendant_plural_name;
			$fpage_text_replacements[strtolower($placeholder_location_descendant_plural_name ?? '')] = strtolower($location_descendant_plural_name ?? '');

			// Find-a-Doc Settings values for location archive page text
			$fpage_text_replacements[$placeholder_location_archive_headline] = $location_archive_headline;

			// Find-a-Doc Settings values for area of expertise labels
			$fpage_text_replacements[$placeholder_expertise_single_name] = $expertise_single_name;
			$fpage_text_replacements[strtolower($placeholder_expertise_single_name ?? '')] = strtolower($expertise_single_name ?? '');
			$fpage_text_replacements[$placeholder_expertise_plural_name] = $expertise_plural_name;
			$fpage_text_replacements[strtolower($placeholder_expertise_plural_name ?? '')] = strtolower($expertise_plural_name ?? '');

			// Find-a-Doc Settings values for area of expertise descendant item labels
			$fpage_text_replacements[$placeholder_expertise_descendant_single_name] = $expertise_descendant_single_name;
			$fpage_text_replacements[strtolower($placeholder_expertise_descendant_single_name ?? '')] = strtolower($expertise_descendant_single_name ?? '');
			$fpage_text_replacements[$placeholder_expertise_descendant_plural_name] = $expertise_descendant_plural_name;
			$fpage_text_replacements[strtolower($placeholder_expertise_descendant_plural_name ?? '')] = strtolower($expertise_descendant_plural_name ?? '');

			// Find-a-Doc Settings values for area of expertise archive page text
			$fpage_text_replacements[$placeholder_expertise_archive_headline] = $expertise_archive_headline;
			$fpage_text_replacements[$placeholder_expertise_archive_intro_text] = $expertise_archive_intro_text;

			// Find-a-Doc Settings values for clinical resource labels
			$fpage_text_replacements[$placeholder_clinical_resource_single_name] = $clinical_resource_single_name;
			$fpage_text_replacements[strtolower($placeholder_clinical_resource_single_name ?? '')] = strtolower($clinical_resource_single_name ?? '');
			$fpage_text_replacements[$placeholder_clinical_resource_plural_name] = $clinical_resource_plural_name;
			$fpage_text_replacements[strtolower($placeholder_clinical_resource_plural_name ?? '')] = strtolower($clinical_resource_plural_name ?? '');

			// Find-a-Doc Settings values for clinical resource archive page text
			$fpage_text_replacements[$placeholder_clinical_resource_archive_headline] = $clinical_resource_archive_headline;

			// Find-a-Doc Settings values for clinical resource facet labels
			$fpage_text_replacements[$placeholder_clinical_resource_type_single_name] = $clinical_resource_type_single_name;
			$fpage_text_replacements[strtolower($placeholder_clinical_resource_type_single_name ?? '')] = strtolower($clinical_resource_type_single_name ?? '');
			$fpage_text_replacements[$placeholder_clinical_resource_type_plural_name] = $clinical_resource_type_plural_name;
			$fpage_text_replacements[strtolower($placeholder_clinical_resource_type_plural_name ?? '')] = strtolower($clinical_resource_type_plural_name ?? '');

			// Find-a-Doc Settings values for combined conditions and treatments labels
			$fpage_text_replacements[$placeholder_condition_treatment_single_name] = $condition_treatment_single_name;
			$fpage_text_replacements[strtolower($placeholder_condition_treatment_single_name ?? '')] = strtolower($condition_treatment_single_name ?? '');
			$fpage_text_replacements[$placeholder_condition_treatment_plural_name] = $condition_treatment_plural_name;
			$fpage_text_replacements[strtolower($placeholder_condition_treatment_plural_name ?? '')] = strtolower($condition_treatment_plural_name ?? '');

			// Find-a-Doc Settings values for condition labels
			$fpage_text_replacements[$placeholder_condition_single_name] = $condition_single_name;
			$fpage_text_replacements[strtolower($placeholder_condition_single_name ?? '')] = strtolower($condition_single_name ?? '');
			$fpage_text_replacements[$placeholder_condition_plural_name] = $condition_plural_name;
			$fpage_text_replacements[strtolower($placeholder_condition_plural_name ?? '')] = strtolower($condition_plural_name ?? '');

			// Find-a-Doc Settings values for condition archive page text
			$fpage_text_replacements[$placeholder_condition_archive_headline] = $condition_archive_headline;
			$fpage_text_replacements[$placeholder_condition_archive_intro_text] = $condition_archive_intro_text;

			// Find-a-Doc Settings values for treatment labels
			$fpage_text_replacements[$placeholder_treatment_single_name] = $treatment_single_name;
			$fpage_text_replacements[strtolower($placeholder_treatment_single_name ?? '')] = strtolower($treatment_single_name ?? '');
			$fpage_text_replacements[$placeholder_treatment_plural_name] = $treatment_plural_name;
			$fpage_text_replacements[strtolower($placeholder_treatment_plural_name ?? '')] = strtolower($treatment_plural_name ?? '');

			// Find-a-Doc Settings values for location labels
			$fpage_text_replacements[$placeholder_treatment_archive_headline] = $treatment_archive_headline;
			$fpage_text_replacements[$placeholder_treatment_archive_intro_text] = $treatment_archive_intro_text;

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

// Define variables for Find-a-Doc Settings values regarding ontology item labels

	// Get the Find-a-Doc Settings values for provider labels
	function uamswp_fad_labels_provider() {

		$provider_single_name = get_field('provider_single_name', 'option') ?: 'Provider';
		$provider_single_name_attr = uamswp_attr_conversion($provider_single_name);
		$provider_plural_name = get_field('provider_plural_name', 'option') ?: 'Providers';
		$provider_plural_name_attr = uamswp_attr_conversion($provider_plural_name);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_provider_single_name = '[Provider]';
		$placeholder_provider_plural_name = '[Providers]';
		$placeholder_provider_short_name = '[Provider Short Name]';
		$placeholder_provider_short_name_possessive = '[Provider Short Name\'s]';

		// Create array for pairing FacetWP name with label if none exists
		$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

		// Add item to FacetWP labels array for Providers facet on Clinical Resources archive/list
		$facet_labels['resource_provider'] = $provider_plural_name;
		$facet_labels['resource_provider_attr'] = $provider_plural_name_attr;

		// Create and return an array to be used on the templates and template parts

			$labels_provider_vars = array(
				'provider_single_name'							=> $provider_single_name, // string
				'provider_single_name_attr'						=> $provider_single_name_attr, // string
				'provider_plural_name'							=> $provider_plural_name, // string
				'provider_plural_name_attr'						=> $provider_plural_name_attr, // string
				'placeholder_provider_single_name'				=> $placeholder_provider_single_name, // string
				'placeholder_provider_plural_name'				=> $placeholder_provider_plural_name, // string
				'placeholder_provider_short_name'				=> $placeholder_provider_short_name, // string
				'placeholder_provider_short_name_possessive'	=> $placeholder_provider_short_name_possessive, // string
				'facet_labels'									=> $facet_labels // array
			);
			return $labels_provider_vars;

	}

	// Get the Find-a-Doc Settings values for location labels
	function uamswp_fad_labels_location() {

		$location_single_name = get_field('location_single_name', 'option') ?: 'Location';
		$location_single_name_attr = uamswp_attr_conversion($location_single_name);
		$location_plural_name = get_field('location_plural_name', 'option') ?: 'Locations';
		$location_plural_name_attr = uamswp_attr_conversion($location_plural_name);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_location_single_name = '[Location]';
		$placeholder_location_plural_name = '[Locations]';
		$placeholder_location_page_title = '[Location Title]';
		$placeholder_location_page_title_phrase = '[the Location Title]';

		// Create array for pairing FacetWP name with label if none exists
		$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

		// Add item to FacetWP labels array for Locations facet on Providers archive/list
		$facet_labels['locations'] = $location_plural_name;
		$facet_labels['locations_attr'] = $location_plural_name_attr;

		// Add item to FacetWP labels array for Locations facet on Clinical Resources archive/list
		$facet_labels['resource_locations'] = $location_plural_name;
		$facet_labels['resource_locations_attr'] = $location_plural_name_attr;

		// Create and return an array to be used on the templates and template parts

			$labels_location_vars = array(
				'location_single_name'						=> $location_single_name, // string
				'location_single_name_attr'					=> $location_single_name_attr, // string
				'location_plural_name'						=> $location_plural_name, // string
				'location_plural_name_attr'					=> $location_plural_name_attr, // string
				'placeholder_location_single_name'			=> $placeholder_location_single_name, // string
				'placeholder_location_plural_name'			=> $placeholder_location_plural_name, // string
				'placeholder_location_page_title'			=> $placeholder_location_page_title, // string
				'placeholder_location_page_title_phrase'	=> $placeholder_location_page_title_phrase, // string
				'facet_labels'								=> $facet_labels // array
			);
			return $labels_location_vars;

	}

	// Get the Find-a-Doc Settings values for location descendant item labels
	function uamswp_fad_labels_location_descendant() {

		$location_descendant_single_name = get_field('location_descendant_single_name', 'option') ?: 'Additional Location';
		$location_descendant_single_name_attr = uamswp_attr_conversion($location_descendant_single_name);
		$location_descendant_plural_name = get_field('location_descendant_plural_name', 'option') ?: 'Additional Locations';
		$location_descendant_plural_name_attr = uamswp_attr_conversion($location_descendant_plural_name);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_location_descendant_single_name = '[Descendant Location]';
		$placeholder_location_descendant_plural_name = '[Descendant Locations]';

		// Create and return an array to be used on the templates and template parts

			$labels_location_descendant_vars = array(
				'location_descendant_single_name' 				=> $location_descendant_single_name, // string
				'location_descendant_single_name_attr' 			=> $location_descendant_single_name_attr, // string
				'location_descendant_plural_name' 				=> $location_descendant_plural_name, // string
				'location_descendant_plural_name_attr'			=> $location_descendant_plural_name_attr, // string
				'placeholder_location_descendant_single_name'	=> $placeholder_location_descendant_single_name, // string
				'placeholder_location_descendant_plural_name'	=> $placeholder_location_descendant_plural_name // string
			);
			return $labels_location_descendant_vars;

	}

	// Get the Find-a-Doc Settings values for area of expertise labels
	function uamswp_fad_labels_expertise() {

		$expertise_single_name = get_field('expertise_single_name', 'option') ?: 'Area of Expertise';
		$expertise_single_name_attr = uamswp_attr_conversion($expertise_single_name);
		$expertise_plural_name = get_field('expertise_plural_name', 'option') ?: 'Areas of Expertise';
		$expertise_plural_name_attr = uamswp_attr_conversion($expertise_plural_name);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_expertise_single_name = '[Area of Expertise]';
		$placeholder_expertise_plural_name = '[Areas of Expertise]';
		$placeholder_expertise_page_title = '[Area of Expertise Title]';

		// Create array for pairing FacetWP name with label if none exists
		$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

		// Add item to FacetWP labels array for Areas of Expertise facet on Providers archive/list
		$facet_labels['physician_areas_of_expertise'] = $expertise_plural_name;
		$facet_labels['physician_areas_of_expertise_attr'] = $expertise_plural_name_attr;

		// Add item to FacetWP labels array for Areas of Expertise facet on Locations archive/list
		$facet_labels['location_aoe'] = $expertise_plural_name;
		$facet_labels['location_aoe_attr'] = $expertise_plural_name_attr;

		// Add item to FacetWP labels array for Areas of Expertise facet on Clinical Resources archive/list
		$facet_labels['resource_aoe'] = $expertise_plural_name;
		$facet_labels['resource_aoe_attr'] = $expertise_plural_name_attr;

		// Create and return an array to be used on the templates and template parts

			$labels_expertise_vars = array(
				'expertise_single_name'				=> $expertise_single_name, // string
				'expertise_single_name_attr'		=> $expertise_single_name_attr, // string
				'expertise_plural_name'				=> $expertise_plural_name, // string
				'expertise_plural_name_attr'		=> $expertise_plural_name_attr, // string
				'placeholder_expertise_single_name'	=> $placeholder_expertise_single_name, // string
				'placeholder_expertise_plural_name'	=> $placeholder_expertise_plural_name, // string
				'placeholder_expertise_page_title'	=> $placeholder_expertise_page_title, // string
				'facet_labels' 						=> $facet_labels // array
			);
			return $labels_expertise_vars;

	}

	// Get the Find-a-Doc Settings values for area of expertise descendant item labels
	function uamswp_fad_labels_expertise_descendant() {

		$expertise_descendant_single_name = get_field('expertise_descendant_single_name', 'option') ?: 'Specialty';
		$expertise_descendant_single_name_attr = uamswp_attr_conversion($expertise_descendant_single_name);
		$expertise_descendant_plural_name = get_field('expertise_descendant_plural_name', 'option') ?: 'Specialties';
		$expertise_descendant_plural_name_attr = uamswp_attr_conversion($expertise_descendant_plural_name);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_expertise_descendant_single_name = '[Descendant Area of Expertise]';
		$placeholder_expertise_descendant_plural_name = '[Descendant Areas of Expertise]';

		// Create and return an array to be used on the templates and template parts

			$labels_expertise_descendant_vars = array(
				'expertise_descendant_single_name'				=> $expertise_descendant_single_name, // string
				'expertise_descendant_single_name_attr'			=> $expertise_descendant_single_name_attr, // string
				'expertise_descendant_plural_name'				=> $expertise_descendant_plural_name, // string
				'expertise_descendant_plural_name_attr'			=> $expertise_descendant_plural_name_attr, // string
				'placeholder_expertise_descendant_single_name'	=> $placeholder_expertise_descendant_single_name, // string
				'placeholder_expertise_descendant_plural_name'	=> $placeholder_expertise_descendant_plural_name // string
			);
			return $labels_expertise_descendant_vars;

	}

	// Get the Find-a-Doc Settings values for clinical resource labels
	function uamswp_fad_labels_clinical_resource() {

		$clinical_resource_single_name = get_field('clinical_resource_single_name', 'option') ?: 'Clinical Resource';
		$clinical_resource_single_name_attr = uamswp_attr_conversion($clinical_resource_single_name);
		$clinical_resource_plural_name = get_field('clinical_resource_plural_name', 'option') ?: 'Clinical Resources';
		$clinical_resource_plural_name_attr = uamswp_attr_conversion($clinical_resource_plural_name);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_clinical_resource_single_name = '[Clinical Resource]';
		$placeholder_clinical_resource_plural_name = '[Clinical Resources]';

		// Create and return an array to be used on the templates and template parts

			$labels_clinical_resource_vars = array(
				'clinical_resource_single_name'				=> $clinical_resource_single_name, // string
				'clinical_resource_single_name_attr'		=> $clinical_resource_single_name_attr, // string
				'clinical_resource_plural_name'				=> $clinical_resource_plural_name, // string
				'clinical_resource_plural_name_attr'		=> $clinical_resource_plural_name_attr, // string
				'placeholder_clinical_resource_single_name'	=> $placeholder_clinical_resource_single_name, // string
				'placeholder_clinical_resource_plural_name'	=> $placeholder_clinical_resource_plural_name // string
			);
			return $labels_clinical_resource_vars;

	}

	// Get the Find-a-Doc Settings values for clinical resource facet labels
	function uamswp_fad_labels_clinical_resource_facet() {

		$clinical_resource_type_single_name = get_field('clinical_resource_type_single_name', 'option') ?: 'Resource Type';
		$clinical_resource_type_single_name_attr = uamswp_attr_conversion($clinical_resource_type_single_name);
		$clinical_resource_type_plural_name = get_field('clinical_resource_type_plural_name', 'option') ?: 'Resource Types';
		$clinical_resource_type_plural_name_attr = uamswp_attr_conversion($clinical_resource_type_plural_name);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_clinical_resource_type_single_name = '[Resource Type]';
		$placeholder_clinical_resource_type_plural_name = '[Resource Types]';

		// Create array for pairing FacetWP name with label if none exists
		$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

		// Add item to FacetWP labels array for Areas of Expertise facet on Providers archive/list
		$facet_labels['resource_type'] = $clinical_resource_type_plural_name;
		$facet_labels['resource_type_attr'] = $clinical_resource_type_plural_name_attr;

		// Create and return an array to be used on the templates and template parts

			$labels_clinical_resource_facet_vars = array(
				'clinical_resource_type_single_name'				=> $clinical_resource_type_single_name, // string
				'clinical_resource_type_single_name_attr'			=> $clinical_resource_type_single_name_attr, // string
				'clinical_resource_type_plural_name'				=> $clinical_resource_type_plural_name, // string
				'clinical_resource_type_plural_name_attr'			=> $clinical_resource_type_plural_name_attr, // string
				'placeholder_clinical_resource_type_single_name'	=> $placeholder_clinical_resource_type_single_name, // string
				'placeholder_clinical_resource_type_plural_name'	=> $placeholder_clinical_resource_type_plural_name, // string
				'facet_labels'										=> $facet_labels // string
			);
			return $labels_clinical_resource_facet_vars;

	}

	// Get the Find-a-Doc Settings values for combined conditions and treatments labels
	function uamswp_fad_labels_condition_treatment() {

		$condition_treatment_single_name = get_field('condition_treatment_single_name', 'option') ?: 'Condition or Treatment';
		$condition_treatment_single_name_attr = uamswp_attr_conversion($condition_treatment_single_name);
		$condition_treatment_plural_name = get_field('condition_treatment_plural_name', 'option') ?: 'Conditions and Treatments';
		$condition_treatment_plural_name_attr = uamswp_attr_conversion($condition_treatment_plural_name);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_condition_treatment_single_name = '[Condition or Treatment]';
		$placeholder_condition_treatment_plural_name = '[Conditions and Treatments]';

		// Create and return an array to be used on the templates and template parts

			$labels_condition_treatment_vars = array(
				'condition_treatment_single_name'				=> $condition_treatment_single_name, // string
				'condition_treatment_single_name_attr'			=> $condition_treatment_single_name_attr, // string
				'condition_treatment_plural_name'				=> $condition_treatment_plural_name, // string
				'condition_treatment_plural_name_attr'			=> $condition_treatment_plural_name_attr, // string
				'placeholder_condition_treatment_single_name'	=> $placeholder_condition_treatment_single_name, // string
				'placeholder_condition_treatment_plural_name'	=> $placeholder_condition_treatment_plural_name // string
			);
			return $labels_condition_treatment_vars;

	}

	// Get the Find-a-Doc Settings values for condition labels
	function uamswp_fad_labels_condition() {

		$condition_single_name = get_field('conditions_single_name', 'option') ?: 'Condition';
		$condition_single_name_attr = uamswp_attr_conversion($condition_single_name);
		$condition_plural_name = get_field('conditions_plural_name', 'option') ?: 'Conditions';
		$condition_plural_name_attr = uamswp_attr_conversion($condition_plural_name);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_condition_single_name = '[Condition]';
		$placeholder_condition_plural_name = '[Conditions]';

		// Create array for pairing FacetWP name with label if none exists
		$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

		// Add item to FacetWP labels array for Conditions facet on Providers archive/list
		$facet_labels['conditions'] = $condition_plural_name;
		$facet_labels['conditions_attr'] = $condition_plural_name_attr;

		// Add item to FacetWP labels array for Conditions facet on Clinical Resources archive/list
		$facet_labels['resource_conditions'] = $condition_plural_name;
		$facet_labels['resource_conditions_attr'] = $condition_plural_name_attr;

		// Create and return an array to be used on the templates and template parts

			$labels_condition_vars = array(
				'condition_single_name'				=> $condition_single_name, // string
				'condition_single_name_attr'		=> $condition_single_name_attr, // string
				'condition_plural_name'				=> $condition_plural_name, // string
				'condition_plural_name_attr'		=> $condition_plural_name_attr, // string
				'placeholder_condition_single_name'	=> $placeholder_condition_single_name, // string
				'placeholder_condition_plural_name'	=> $placeholder_condition_plural_name, // string
				'facet_labels'						=> $facet_labels // array
			);
			return $labels_condition_vars;

	}

	// Get the Find-a-Doc Settings values for treatment labels
	function uamswp_fad_labels_treatment() {

		$treatment_single_name = get_field('treatments_single_name', 'option') ?: 'Treatment/Procedure';
		$treatment_single_name_attr = uamswp_attr_conversion($treatment_single_name);
		$treatment_plural_name = get_field('treatments_plural_name', 'option') ?: 'Treatments and Procedures';
		$treatment_plural_name_attr = uamswp_attr_conversion($treatment_plural_name);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_treatment_single_name = '[Treatment]';
		$placeholder_treatment_plural_name = '[Treatments]';

		// Create array for pairing FacetWP name with label if none exists
		$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

		// Add item to FacetWP labels array for Treatments facet on Providers archive/list
		$facet_labels['treatments_procedures'] = $treatment_plural_name;
		$facet_labels['treatments_procedures_attr'] = $treatment_plural_name_attr;

		// Add item to FacetWP labels array for Treatments facet on Clinical Resources archive/list
		$facet_labels['resource_treatments'] = $treatment_plural_name;
		$facet_labels['resource_treatments_attr'] = $treatment_plural_name_attr;

		// Create and return an array to be used on the templates and template parts

			$labels_treatment_vars = array(
				'treatment_single_name'				=> $treatment_single_name, // string
				'treatment_single_name_attr'		=> $treatment_single_name_attr, // string
				'treatment_plural_name'				=> $treatment_plural_name, // string
				'treatment_plural_name_attr'		=> $treatment_plural_name_attr, // string
				'placeholder_treatment_single_name'	=> $placeholder_treatment_single_name, // string
				'placeholder_treatment_plural_name'	=> $placeholder_treatment_plural_name, // string
				'facet_labels'						=> $facet_labels // array
			);
			return $labels_treatment_vars;

	}

// Define variables for Find-a-Doc Settings values regarding ontology archive page text

	// Get the Find-a-Doc Settings values for provider archive page text
	function uamswp_fad_archive_text_provider() {

		$provider_archive_headline = get_field('provider_archive_headline', 'option') ?: 'UAMS Health Providers';
		$provider_archive_headline_attr = uamswp_attr_conversion($provider_archive_headline);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_provider_archive_headline = '[Provider Archive Title]';

		// Create and return an array to be used on the templates and template parts

			$archive_text_provider_vars = array(
				'provider_archive_headline'				=> $provider_archive_headline, // string
				'provider_archive_headline_attr'		=> $provider_archive_headline_attr, // string
				'placeholder_provider_archive_headline'	=> $placeholder_provider_archive_headline // string
			);
			return $archive_text_provider_vars;

	}

	// Get the Find-a-Doc Settings values for location archive page text
	function uamswp_fad_archive_text_location() {

		$location_archive_headline = get_field('location_archive_headline', 'option') ?: 'Locations';
		$location_archive_headline_attr = uamswp_attr_conversion($location_archive_headline);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_location_archive_headline = '[Location Archive Title]';

		// Create and return an array to be used on the templates and template parts

			$archive_text_location_vars = array(
				'location_archive_headline' 			=> $location_archive_headline, // string
				'location_archive_headline_attr'		=> $location_archive_headline_attr, // string
				'placeholder_location_archive_headline'	=> $placeholder_location_archive_headline // string
			);
			return $archive_text_location_vars;

	}

	// Get the Find-a-Doc Settings values for area of expertise archive page text
	function uamswp_fad_archive_text_expertise() {

		$expertise_archive_headline = get_field('expertise_archive_headline', 'option') ?: 'Areas of Expertise';
		$expertise_archive_headline_attr = uamswp_attr_conversion($expertise_archive_headline);
		$expertise_archive_intro_text = get_field('expertise_archive_intro_text', 'option');

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_expertise_archive_headline = '[Area of Expertise Archive Title]';
		$placeholder_expertise_archive_intro_text = '[Area of Expertise Archive Intro Text]';

		// Create and return an array to be used on the templates and template parts

			$archive_text_expertise_vars = array(
				'expertise_archive_headline'				=> $expertise_archive_headline, // string
				'expertise_archive_headline_attr'			=> $expertise_archive_headline_attr, // string
				'expertise_archive_intro_text'				=> $expertise_archive_intro_text, // string
				'placeholder_expertise_archive_headline'	=> $placeholder_expertise_archive_headline, // string
				'placeholder_expertise_archive_intro_text'	=> $placeholder_expertise_archive_intro_text // string
			);
			return $archive_text_expertise_vars;

	}

	// Get the Find-a-Doc Settings values for clinical resource archive page text
	function uamswp_fad_archive_text_clinical_resource() {

		$clinical_resource_archive_headline = get_field('clinical_resource_archive_headline', 'option') ?: 'Clinical Resources';
		$clinical_resource_archive_headline_attr = uamswp_attr_conversion($clinical_resource_archive_headline);

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_clinical_resource_archive_headline = '[Clinical Resource Archive Title]';

		// Create and return an array to be used on the templates and template parts

			$archive_text_clinical_resource_vars = array(
				'clinical_resource_archive_headline'				=> $clinical_resource_archive_headline, // string
				'clinical_resource_archive_headline_attr'			=> $clinical_resource_archive_headline_attr, // string
				'placeholder_clinical_resource_archive_headline'	=> $placeholder_clinical_resource_archive_headline // string
			);
			return $archive_text_clinical_resource_vars;

	}

	// Get the Find-a-Doc Settings values for condition archive page text
	function uamswp_fad_archive_text_condition() {

		$condition_archive_headline = get_field('conditions_archive_headline', 'option') ?: 'Conditions';
		$condition_archive_headline_attr = uamswp_attr_conversion($condition_archive_headline);
		$condition_archive_intro_text = get_field('conditions_archive_intro_text', 'option');

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_condition_archive_headline = '[Condition Archive Title]';
		$placeholder_condition_archive_intro_text = '[Condition Archive Intro Text]';

		// Create and return an array to be used on the templates and template parts

			$archive_text_condition_vars = array(
				'condition_archive_headline'				=> $condition_archive_headline, // string
				'condition_archive_headline_attr'			=> $condition_archive_headline_attr, // string
				'condition_archive_intro_text'				=> $condition_archive_intro_text, // string
				'placeholder_condition_archive_headline'	=> $placeholder_condition_archive_headline, // string
				'placeholder_condition_archive_intro_text'	=> $placeholder_condition_archive_intro_text // string
			);
			return $archive_text_condition_vars;

	}

	// Get the Find-a-Doc Settings values for treatment archive page text
	function uamswp_fad_archive_text_treatment() {

		$treatment_archive_headline = get_field('treatments_archive_headline', 'option') ?: 'Treatments and Procedures';
		$treatment_archive_headline_attr = uamswp_attr_conversion($treatment_archive_headline);
		$treatment_archive_intro_text = get_field('treatments_archive_intro_text', 'option');

		// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
		$placeholder_treatment_archive_headline = '[Treatment Archive Title]';
		$placeholder_treatment_archive_intro_text = '[Treatment Archive Intro Text]';

		// Create and return an array to be used on the templates and template parts

			$archive_text_treatment_vars = array(
				'treatment_archive_headline'				=> $treatment_archive_headline, // string
				'treatment_archive_headline_attr'			=> $treatment_archive_headline_attr, // string
				'treatment_archive_intro_text'				=> $treatment_archive_intro_text, // string
				'placeholder_treatment_archive_headline'	=> $placeholder_treatment_archive_headline, // string
				'placeholder_treatment_archive_intro_text'	=> $placeholder_treatment_archive_intro_text // string
			);
			return $archive_text_treatment_vars;

	}

// Define variables for Find-a-Doc Settings values regarding ontology text elements on fake subpages and single profiles

	// Get the Find-a-Doc Settings values for ontology text elements in general placements

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Providers
		function uamswp_fad_fpage_text_provider_general(
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Get the Find-a-Doc Settings values for the text elements in general placements
			$provider_fpage_title_general = get_field('provider_fpage_title_general', 'option'); // Fake subpage (or section), title
			$provider_fpage_intro_general = get_field('provider_fpage_intro_general', 'option'); // Fake subpage (or section), intro text
			$provider_fpage_ref_main_title_general = get_field('provider_fpage_ref_main_title_general', 'option'); // Reference to the main provider archive, title
			$provider_fpage_ref_main_intro_general = get_field('provider_fpage_ref_main_intro_general', 'option'); // Reference to the main provider archive, body text
			$provider_fpage_ref_main_link_general = get_field('provider_fpage_ref_main_link_general', 'option'); // Reference to the main provider archive, link text
			$provider_fpage_ref_top_title_general = get_field('provider_fpage_ref_top_title_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Providers, title
			$provider_fpage_ref_top_intro_general = get_field('provider_fpage_ref_top_intro_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Providers, body text
			$provider_fpage_ref_top_link_general = get_field('provider_fpage_ref_top_link_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Providers, link text

			// If the variable is not set or is empty...
			// Set a hardcoded fallback value
			$provider_fpage_title_general = ( isset($provider_fpage_title_general) && !empty($provider_fpage_title_general) ) ? $provider_fpage_title_general : 'Related [Providers]'; // Fake subpage (or section), title
			$provider_fpage_intro_general = ( isset($provider_fpage_intro_general) && !empty($provider_fpage_intro_general) ) ? $provider_fpage_intro_general : ''; // Fake subpage (or section), intro text
			$provider_fpage_ref_main_title_general = ( isset($provider_fpage_ref_main_title_general) && !empty($provider_fpage_ref_main_title_general) ) ? $provider_fpage_ref_main_title_general : 'Full List of [Providers]'; // Reference to the main provider archive, title
			$provider_fpage_ref_main_intro_general = ( isset($provider_fpage_ref_main_intro_general) && !empty($provider_fpage_ref_main_intro_general) ) ? $provider_fpage_ref_main_intro_general : 'Discover our comprehensive list of [providers], spanning diverse specialties, who are dedicated to delivering exceptional care at UAMS Health.'; // Reference to the main provider archive, body text
			$provider_fpage_ref_main_link_general = ( isset($provider_fpage_ref_main_link_general) && !empty($provider_fpage_ref_main_link_general) ) ? $provider_fpage_ref_main_link_general : 'View All [Providers]'; // Reference to the main provider archive, link text
			$provider_fpage_ref_top_title_general = ( isset($provider_fpage_ref_top_title_general) && !empty($provider_fpage_ref_top_title_general) ) ? $provider_fpage_ref_top_title_general : $provider_fpage_ref_main_title_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Providers, title
			$provider_fpage_ref_top_intro_general = ( isset($provider_fpage_ref_top_intro_general) && !empty($provider_fpage_ref_top_intro_general) ) ? $provider_fpage_ref_top_intro_general : $provider_fpage_ref_main_intro_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Providers, body text
			$provider_fpage_ref_top_link_general = ( isset($provider_fpage_ref_top_link_general) && !empty($provider_fpage_ref_top_link_general) ) ? $provider_fpage_ref_top_link_general : $provider_fpage_ref_main_link_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Providers, link text

			// Substitute placeholder text for relevant Find-a-Doc Settings value
			$provider_fpage_title_general = $provider_fpage_title_general ? uamswp_fad_fpage_text_replace($provider_fpage_title_general, $page_titles) : ''; // Fake subpage (or section), title
			$provider_fpage_intro_general = $provider_fpage_intro_general ? uamswp_fad_fpage_text_replace($provider_fpage_intro_general, $page_titles) : ''; // Fake subpage (or section), intro text
			$provider_fpage_ref_main_title_general = $provider_fpage_ref_main_title_general ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_title_general, $page_titles) : ''; // Reference to the main provider archive, title
			$provider_fpage_ref_main_intro_general = $provider_fpage_ref_main_intro_general ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_intro_general, $page_titles) : ''; // Reference to the main provider archive, body text
			$provider_fpage_ref_main_link_general = $provider_fpage_ref_main_link_general ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_link_general, $page_titles) : ''; // Reference to the main provider archive, link text
			$provider_fpage_ref_top_title_general = $provider_fpage_ref_top_title_general ? uamswp_fad_fpage_text_replace($provider_fpage_ref_top_title_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Providers, title
			$provider_fpage_ref_top_intro_general = $provider_fpage_ref_top_intro_general ? uamswp_fad_fpage_text_replace($provider_fpage_ref_top_intro_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Providers, body text
			$provider_fpage_ref_top_link_general = $provider_fpage_ref_top_link_general ? uamswp_fad_fpage_text_replace($provider_fpage_ref_top_link_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Providers, link text

			// Create and return an array to be used on the templates and template parts

				$fpage_text_provider_general_vars = array(
					'provider_fpage_title_general'			=> $provider_fpage_title_general, // string
					'provider_fpage_intro_general'			=> $provider_fpage_intro_general, // string
					'provider_fpage_ref_main_title_general'	=> $provider_fpage_ref_main_title_general, // string
					'provider_fpage_ref_main_intro_general'	=> $provider_fpage_ref_main_intro_general, // string
					'provider_fpage_ref_main_link_general'	=> $provider_fpage_ref_main_link_general, // string
					'provider_fpage_ref_top_title_general'	=> $provider_fpage_ref_top_title_general, // string
					'provider_fpage_ref_top_intro_general'	=> $provider_fpage_ref_top_intro_general, // string
					'provider_fpage_ref_top_link_general'	=> $provider_fpage_ref_top_link_general // string
				);
				return $fpage_text_provider_general_vars;

		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Locations
		function uamswp_fad_fpage_text_location_general(
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Locations

				// Get the Find-a-Doc Settings values for the text elements in general placements
				$location_fpage_title_general = get_field('location_fpage_title_general', 'option'); // Fake subpage (or section), title
				$location_fpage_intro_general = get_field('location_fpage_intro_general', 'option'); // Fake subpage (or section), intro text
				$location_fpage_ref_main_title_general = get_field('location_fpage_ref_main_title_general', 'option'); // Reference to the main location archive, title
				$location_fpage_ref_main_intro_general = get_field('location_fpage_ref_main_intro_general', 'option'); // Reference to the main location archive, body text
				$location_fpage_ref_main_link_general = get_field('location_fpage_ref_main_link_general', 'option'); // Reference to the main location archive, link text
				$location_fpage_ref_top_title_general = get_field('location_fpage_ref_top_title_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Locations, title
				$location_fpage_ref_top_intro_general = get_field('location_fpage_ref_top_intro_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Locations, body text
				$location_fpage_ref_top_link_general = get_field('location_fpage_ref_top_link_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Locations, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value
				$location_fpage_title_general = ( isset($location_fpage_title_general) && !empty($location_fpage_title_general) ) ? $location_fpage_title_general : 'Related [Locations]'; // Fake subpage (or section), title
				$location_fpage_intro_general = ( isset($location_fpage_intro_general) && !empty($location_fpage_intro_general) ) ? $location_fpage_intro_general : ''; // Fake subpage (or section), intro text
				$location_fpage_ref_main_title_general = ( isset($location_fpage_ref_main_title_general) && !empty($location_fpage_ref_main_title_general) ) ? $location_fpage_ref_main_title_general : 'Full List of [Locations]'; // Reference to the main location archive, title
				$location_fpage_ref_main_intro_general = ( isset($location_fpage_ref_main_intro_general) && !empty($location_fpage_ref_main_intro_general) ) ? $location_fpage_ref_main_intro_general : 'Discover our extensive network of [locations], offering exceptional care across specialties within UAMS Health. Explore our diverse [locations] and find the one closest to you.'; // Reference to the main location archive, body text
				$location_fpage_ref_main_link_general = ( isset($location_fpage_ref_main_link_general) && !empty($location_fpage_ref_main_link_general) ) ? $location_fpage_ref_main_link_general : 'View All [Locations]'; // Reference to the main location archive, link text
				$location_fpage_ref_top_title_general = ( isset($location_fpage_ref_top_title_general) && !empty($location_fpage_ref_top_title_general) ) ? $location_fpage_ref_top_title_general : $location_fpage_ref_main_title_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Locations, title
				$location_fpage_ref_top_intro_general = ( isset($location_fpage_ref_top_intro_general) && !empty($location_fpage_ref_top_intro_general) ) ? $location_fpage_ref_top_intro_general : $location_fpage_ref_main_intro_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Locations, body text
				$location_fpage_ref_top_link_general = ( isset($location_fpage_ref_top_link_general) && !empty($location_fpage_ref_top_link_general) ) ? $location_fpage_ref_top_link_general : $location_fpage_ref_main_link_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Locations, link text

				// Substitute placeholder text for relevant Find-a-Doc Settings value
				$location_fpage_title_general = $location_fpage_title_general ? uamswp_fad_fpage_text_replace($location_fpage_title_general, $page_titles) : ''; // Fake subpage (or section), title
				$location_fpage_intro_general = $location_fpage_intro_general ? uamswp_fad_fpage_text_replace($location_fpage_intro_general, $page_titles) : ''; // Fake subpage (or section), intro text
				$location_fpage_ref_main_title_general = $location_fpage_ref_main_title_general ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_title_general, $page_titles) : ''; // Reference to the main location archive, title
				$location_fpage_ref_main_intro_general = $location_fpage_ref_main_intro_general ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_intro_general, $page_titles) : ''; // Reference to the main location archive, body text
				$location_fpage_ref_main_link_general = $location_fpage_ref_main_link_general ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_link_general, $page_titles) : ''; // Reference to the main location archive, link text
				$location_fpage_ref_top_title_general = $location_fpage_ref_top_title_general ? uamswp_fad_fpage_text_replace($location_fpage_ref_top_title_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Locations, title
				$location_fpage_ref_top_intro_general = $location_fpage_ref_top_intro_general ? uamswp_fad_fpage_text_replace($location_fpage_ref_top_intro_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Locations, body text
				$location_fpage_ref_top_link_general = $location_fpage_ref_top_link_general ? uamswp_fad_fpage_text_replace($location_fpage_ref_top_link_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Locations, link text

			// Descendant Locations

				// Get the Find-a-Doc Settings values for the text elements in general placements
				$location_descendant_fpage_title_general = get_field('location_descendant_fpage_title_general', 'option'); // Fake subpage (or section), title
				$location_descendant_fpage_intro_general = get_field('location_descendant_fpage_intro_general', 'option'); // Fake subpage (or section), intro text
				$location_descendant_fpage_ref_main_title_general = get_field('location_descendant_fpage_ref_main_title_general', 'option'); // Reference to the main location archive, title
				$location_descendant_fpage_ref_main_intro_general = get_field('location_descendant_fpage_ref_main_intro_general', 'option'); // Reference to the main location archive, body text
				$location_descendant_fpage_ref_main_link_general = get_field('location_descendant_fpage_ref_main_link_general', 'option'); // Reference to the main location archive, link text
				$location_descendant_fpage_ref_top_title_general = get_field('location_descendant_fpage_ref_top_title_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Descendant Locations, title
				$location_descendant_fpage_ref_top_intro_general = get_field('location_descendant_fpage_ref_top_intro_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Descendant Locations, body text
				$location_descendant_fpage_ref_top_link_general = get_field('location_descendant_fpage_ref_top_link_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Descendant Locations, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value
				$location_descendant_fpage_title_general = ( isset($location_descendant_fpage_title_general) && !empty($location_descendant_fpage_title_general) ) ? $location_descendant_fpage_title_general : 'Related [Descendant Locations]'; // Fake subpage (or section), title
				$location_descendant_fpage_intro_general = ( isset($location_descendant_fpage_intro_general) && !empty($location_descendant_fpage_intro_general) ) ? $location_descendant_fpage_intro_general : ''; // Fake subpage (or section), intro text
				$location_descendant_fpage_ref_main_title_general = ( isset($location_descendant_fpage_ref_main_title_general) && !empty($location_descendant_fpage_ref_main_title_general) ) ? $location_descendant_fpage_ref_main_title_general : $location_fpage_ref_main_title_general; // Reference to the main location archive, title
				$location_descendant_fpage_ref_main_intro_general = ( isset($location_descendant_fpage_ref_main_intro_general) && !empty($location_descendant_fpage_ref_main_intro_general) ) ? $location_descendant_fpage_ref_main_intro_general : $location_fpage_ref_main_intro_general; // Reference to the main location archive, body text
				$location_descendant_fpage_ref_main_link_general = ( isset($location_descendant_fpage_ref_main_link_general) && !empty($location_descendant_fpage_ref_main_link_general) ) ? $location_descendant_fpage_ref_main_link_general : $location_fpage_ref_main_link_general; // Reference to the main location archive, link text
				$location_descendant_fpage_ref_top_title_general = ( isset($location_descendant_fpage_ref_top_title_general) && !empty($location_descendant_fpage_ref_top_title_general) ) ? $location_descendant_fpage_ref_top_title_general : $location_descendant_fpage_ref_main_title_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Descendant Locations, title
				$location_descendant_fpage_ref_top_intro_general = ( isset($location_descendant_fpage_ref_top_intro_general) && !empty($location_descendant_fpage_ref_top_intro_general) ) ? $location_descendant_fpage_ref_top_intro_general : $location_descendant_fpage_ref_main_intro_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Descendant Locations, body text
				$location_descendant_fpage_ref_top_link_general = ( isset($location_descendant_fpage_ref_top_link_general) && !empty($location_descendant_fpage_ref_top_link_general) ) ? $location_descendant_fpage_ref_top_link_general : $location_descendant_fpage_ref_main_link_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Descendant Locations, link text

				// Substitute placeholder text for relevant Find-a-Doc Settings value
				$location_descendant_fpage_title_general = $location_descendant_fpage_title_general ? uamswp_fad_fpage_text_replace($location_descendant_fpage_title_general, $page_titles) : ''; // Fake subpage (or section), title
				$location_descendant_fpage_intro_general = $location_descendant_fpage_intro_general ? uamswp_fad_fpage_text_replace($location_descendant_fpage_intro_general, $page_titles) : ''; // Fake subpage (or section), intro text
				$location_descendant_fpage_ref_main_title_general = $location_descendant_fpage_ref_main_title_general ? uamswp_fad_fpage_text_replace($location_descendant_fpage_ref_main_title_general, $page_titles) : ''; // Reference to the main location archive, title
				$location_descendant_fpage_ref_main_intro_general = $location_descendant_fpage_ref_main_intro_general ? uamswp_fad_fpage_text_replace($location_descendant_fpage_ref_main_intro_general, $page_titles) : ''; // Reference to the main location archive, body text
				$location_descendant_fpage_ref_main_link_general = $location_descendant_fpage_ref_main_link_general ? uamswp_fad_fpage_text_replace($location_descendant_fpage_ref_main_link_general, $page_titles) : ''; // Reference to the main location archive, link text
				$location_descendant_fpage_ref_top_title_general = $location_descendant_fpage_ref_top_title_general ? uamswp_fad_fpage_text_replace($location_descendant_fpage_ref_top_title_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Descendant Locations, title
				$location_descendant_fpage_ref_top_intro_general = $location_descendant_fpage_ref_top_intro_general ? uamswp_fad_fpage_text_replace($location_descendant_fpage_ref_top_intro_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Descendant Locations, body text
				$location_descendant_fpage_ref_top_link_general = $location_descendant_fpage_ref_top_link_general ? uamswp_fad_fpage_text_replace($location_descendant_fpage_ref_top_link_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Descendant Locations, link text

			// Create and return an array to be used on the templates and template parts

				$fpage_text_location_general_vars = array(
					'location_fpage_title_general'						=> $location_fpage_title_general, // string
					'location_fpage_intro_general'						=> $location_fpage_intro_general, // string
					'location_fpage_ref_main_title_general'				=> $location_fpage_ref_main_title_general, // string
					'location_fpage_ref_main_intro_general'				=> $location_fpage_ref_main_intro_general, // string
					'location_fpage_ref_main_link_general'				=> $location_fpage_ref_main_link_general, // string
					'location_fpage_ref_top_title_general'				=> $location_fpage_ref_top_title_general, // string
					'location_fpage_ref_top_intro_general'				=> $location_fpage_ref_top_intro_general, // string
					'location_fpage_ref_top_link_general'				=> $location_fpage_ref_top_link_general, // string
					'location_descendant_fpage_title_general'			=> $location_descendant_fpage_title_general, // string
					'location_descendant_fpage_intro_general'			=> $location_descendant_fpage_intro_general, // string
					'location_descendant_fpage_ref_main_title_general'	=> $location_descendant_fpage_ref_main_title_general, // string
					'location_descendant_fpage_ref_main_intro_general'	=> $location_descendant_fpage_ref_main_intro_general, // string
					'location_descendant_fpage_ref_main_link_general'	=> $location_descendant_fpage_ref_main_link_general, // string
					'location_descendant_fpage_ref_top_title_general'	=> $location_descendant_fpage_ref_top_title_general, // string
					'location_descendant_fpage_ref_top_intro_general'	=> $location_descendant_fpage_ref_top_intro_general, // string
					'location_descendant_fpage_ref_top_link_general'	=> $location_descendant_fpage_ref_top_link_general // string
				);
				return $fpage_text_location_general_vars;

		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Areas of Expertise
		function uamswp_fad_fpage_text_expertise_general(
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Areas of Expertise

				// Get the Find-a-Doc Settings values for the text elements in general placements
				$expertise_fpage_title_general = get_field('expertise_fpage_title_general', 'option'); // Fake subpage (or section), title
				$expertise_fpage_intro_general = get_field('expertise_fpage_intro_general', 'option'); // Fake subpage (or section), intro text
				$expertise_fpage_ref_main_title_general = get_field('expertise_fpage_ref_main_title_general', 'option'); // Reference to the main area of expertise archive, title
				$expertise_fpage_ref_main_intro_general = get_field('expertise_fpage_ref_main_intro_general', 'option'); // Reference to the main area of expertise archive, body text
				$expertise_fpage_ref_main_link_general = get_field('expertise_fpage_ref_main_link_general', 'option'); // Reference to the main area of expertise archive, link text
				$expertise_fpage_ref_top_title_general = get_field('expertise_fpage_ref_top_title_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, title
				$expertise_fpage_ref_top_intro_general = get_field('expertise_fpage_ref_top_intro_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, body text
				$expertise_fpage_ref_top_link_general = get_field('expertise_fpage_ref_top_link_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value
				$expertise_fpage_title_general = ( isset($expertise_fpage_title_general) && !empty($expertise_fpage_title_general) ) ? $expertise_fpage_title_general : 'Related [Areas of Expertise]'; // Fake subpage (or section), title
				$expertise_fpage_intro_general = ( isset($expertise_fpage_intro_general) && !empty($expertise_fpage_intro_general) ) ? $expertise_fpage_intro_general : ''; // Fake subpage (or section), intro text
				$expertise_fpage_ref_main_title_general = ( isset($expertise_fpage_ref_main_title_general) && !empty($expertise_fpage_ref_main_title_general) ) ? $expertise_fpage_ref_main_title_general : 'Full List of [Areas of Expertise]'; // Reference to the main area of expertise archive, title
				$expertise_fpage_ref_main_intro_general = ( isset($expertise_fpage_ref_main_intro_general) && !empty($expertise_fpage_ref_main_intro_general) ) ? $expertise_fpage_ref_main_intro_general : 'Explore our extensive range of [areas of expertise], encompassing diverse specialties and cutting-edge medical advancements. Discover the breadth of knowledge and skills within UAMS Health.'; // Reference to the main area of expertise archive, body text
				$expertise_fpage_ref_main_link_general = ( isset($expertise_fpage_ref_main_link_general) && !empty($expertise_fpage_ref_main_link_general) ) ? $expertise_fpage_ref_main_link_general : 'View All [Areas of Expertise]'; // Reference to the main area of expertise archive, link text
				$expertise_fpage_ref_top_title_general = ( isset($expertise_fpage_ref_top_title_general) && !empty($expertise_fpage_ref_top_title_general) ) ? $expertise_fpage_ref_top_title_general : $expertise_fpage_ref_main_title_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, title
				$expertise_fpage_ref_top_intro_general = ( isset($expertise_fpage_ref_top_intro_general) && !empty($expertise_fpage_ref_top_intro_general) ) ? $expertise_fpage_ref_top_intro_general : $expertise_fpage_ref_main_intro_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, body text
				$expertise_fpage_ref_top_link_general = ( isset($expertise_fpage_ref_top_link_general) && !empty($expertise_fpage_ref_top_link_general) ) ? $expertise_fpage_ref_top_link_general : $expertise_fpage_ref_main_link_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, link text

				// Substitute placeholder text for relevant Find-a-Doc Settings value
				$expertise_fpage_title_general = uamswp_fad_fpage_text_replace($expertise_fpage_title_general, $page_titles); // Fake subpage (or section), title
				$expertise_fpage_intro_general = uamswp_fad_fpage_text_replace($expertise_fpage_intro_general, $page_titles); // Fake subpage (or section), intro text
				$expertise_fpage_ref_main_title_general = uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_title_general, $page_titles); // Reference to the main area of expertise archive, title
				$expertise_fpage_ref_main_intro_general = uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_intro_general, $page_titles); // Reference to the main area of expertise archive, body text
				$expertise_fpage_ref_main_link_general = uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_link_general, $page_titles); // Reference to the main area of expertise archive, link text
				$expertise_fpage_ref_top_title_general = uamswp_fad_fpage_text_replace($expertise_fpage_ref_top_title_general, $page_titles); // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, title
				$expertise_fpage_ref_top_intro_general = uamswp_fad_fpage_text_replace($expertise_fpage_ref_top_intro_general, $page_titles); // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, body text
				$expertise_fpage_ref_top_link_general = uamswp_fad_fpage_text_replace($expertise_fpage_ref_top_link_general, $page_titles); // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, link text

			// Descendant Areas of Expertise

				// Get the Find-a-Doc Settings values for the text elements in general placements
				$expertise_descendant_fpage_title_general = get_field('expertise_descendant_fpage_title_general', 'option'); // Fake subpage (or section), title
				$expertise_descendant_fpage_intro_general = get_field('expertise_descendant_fpage_intro_general', 'option'); // Fake subpage (or section), intro text
				$expertise_descendant_fpage_ref_main_title_general = get_field('expertise_descendant_fpage_ref_main_title_general', 'option'); // Reference to the main area of expertise archive, title
				$expertise_descendant_fpage_ref_main_intro_general = get_field('expertise_descendant_fpage_ref_main_intro_general', 'option'); // Reference to the main area of expertise archive, body text
				$expertise_descendant_fpage_ref_main_link_general = get_field('expertise_descendant_fpage_ref_main_link_general', 'option'); // Reference to the main area of expertise archive, link text
				$expertise_descendant_fpage_ref_top_title_general = get_field('expertise_descendant_fpage_ref_top_title_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, title
				$expertise_descendant_fpage_ref_top_intro_general = get_field('expertise_descendant_fpage_ref_top_intro_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, body text
				$expertise_descendant_fpage_ref_top_link_general = get_field('expertise_descendant_fpage_ref_top_link_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value
				$expertise_descendant_fpage_title_general = ( isset($expertise_descendant_fpage_title_general) && !empty($expertise_descendant_fpage_title_general) ) ? $expertise_descendant_fpage_title_general : 'Related [Areas of Expertise]'; // Fake subpage (or section), title
				$expertise_descendant_fpage_intro_general = ( isset($expertise_descendant_fpage_intro_general) && !empty($expertise_descendant_fpage_intro_general) ) ? $expertise_descendant_fpage_intro_general : ''; // Fake subpage (or section), intro text
				$expertise_descendant_fpage_ref_main_title_general = ( isset($expertise_descendant_fpage_ref_main_title_general) && !empty($expertise_descendant_fpage_ref_main_title_general) ) ? $expertise_descendant_fpage_ref_main_title_general : $expertise_fpage_ref_main_title_general; // Reference to the main area of expertise archive, title
				$expertise_descendant_fpage_ref_main_intro_general = ( isset($expertise_descendant_fpage_ref_main_intro_general) && !empty($expertise_descendant_fpage_ref_main_intro_general) ) ? $expertise_descendant_fpage_ref_main_intro_general : $expertise_fpage_ref_main_intro_general; // Reference to the main area of expertise archive, body text
				$expertise_descendant_fpage_ref_main_link_general = ( isset($expertise_descendant_fpage_ref_main_link_general) && !empty($expertise_descendant_fpage_ref_main_link_general) ) ? $expertise_descendant_fpage_ref_main_link_general : $expertise_fpage_ref_main_link_general; // Reference to the main area of expertise archive, link text
				$expertise_descendant_fpage_ref_top_title_general = ( isset($expertise_descendant_fpage_ref_top_title_general) && !empty($expertise_descendant_fpage_ref_top_title_general) ) ? $expertise_descendant_fpage_ref_top_title_general : $expertise_descendant_fpage_ref_main_title_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, title
				$expertise_descendant_fpage_ref_top_intro_general = ( isset($expertise_descendant_fpage_ref_top_intro_general) && !empty($expertise_descendant_fpage_ref_top_intro_general) ) ? $expertise_descendant_fpage_ref_top_intro_general : $expertise_descendant_fpage_ref_main_intro_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, body text
				$expertise_descendant_fpage_ref_top_link_general = ( isset($expertise_descendant_fpage_ref_top_link_general) && !empty($expertise_descendant_fpage_ref_top_link_general) ) ? $expertise_descendant_fpage_ref_top_link_general : $expertise_descendant_fpage_ref_main_link_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, link text

				// Substitute placeholder text for relevant Find-a-Doc Settings value
				$expertise_descendant_fpage_title_general = $expertise_descendant_fpage_title_general ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_title_general, $page_titles) : ''; // Fake subpage (or section), title
				$expertise_descendant_fpage_intro_general = $expertise_descendant_fpage_intro_general ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_intro_general, $page_titles) : ''; // Fake subpage (or section), intro text
				$expertise_descendant_fpage_ref_main_title_general = $expertise_descendant_fpage_ref_main_title_general ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_ref_main_title_general, $page_titles) : ''; // Reference to the main area of expertise archive, title
				$expertise_descendant_fpage_ref_main_intro_general = $expertise_descendant_fpage_ref_main_intro_general ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_ref_main_intro_general, $page_titles) : ''; // Reference to the main area of expertise archive, body text
				$expertise_descendant_fpage_ref_main_link_general = $expertise_descendant_fpage_ref_main_link_general ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_ref_main_link_general, $page_titles) : ''; // Reference to the main area of expertise archive, link text
				$expertise_descendant_fpage_ref_top_title_general = $expertise_descendant_fpage_ref_top_title_general ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_ref_top_title_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, title
				$expertise_descendant_fpage_ref_top_intro_general = $expertise_descendant_fpage_ref_top_intro_general ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_ref_top_intro_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, body text
				$expertise_descendant_fpage_ref_top_link_general = $expertise_descendant_fpage_ref_top_link_general ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_ref_top_link_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Areas of Expertise, link text

			// Create and return an array to be used on the templates and template parts

				$fpage_text_expertise_general_vars = array(
					'expertise_fpage_title_general'						=> $expertise_fpage_title_general, // string
					'expertise_fpage_intro_general'						=> $expertise_fpage_intro_general, // string
					'expertise_fpage_ref_main_title_general'			=> $expertise_fpage_ref_main_title_general, // string
					'expertise_fpage_ref_main_intro_general'			=> $expertise_fpage_ref_main_intro_general, // string
					'expertise_fpage_ref_main_link_general'				=> $expertise_fpage_ref_main_link_general, // string
					'expertise_fpage_ref_top_title_general'				=> $expertise_fpage_ref_top_title_general, // string
					'expertise_fpage_ref_top_intro_general'				=> $expertise_fpage_ref_top_intro_general, // string
					'expertise_fpage_ref_top_link_general'				=> $expertise_fpage_ref_top_link_general, // string
					'expertise_descendant_fpage_title_general'			=> $expertise_descendant_fpage_title_general, // string
					'expertise_descendant_fpage_intro_general'			=> $expertise_descendant_fpage_intro_general, // string
					'expertise_descendant_fpage_ref_main_title_general'	=> $expertise_descendant_fpage_ref_main_title_general, // string
					'expertise_descendant_fpage_ref_main_intro_general'	=> $expertise_descendant_fpage_ref_main_intro_general, // string
					'expertise_descendant_fpage_ref_main_link_general'	=> $expertise_descendant_fpage_ref_main_link_general, // string
					'expertise_descendant_fpage_ref_top_title_general'	=> $expertise_descendant_fpage_ref_top_title_general, // string
					'expertise_descendant_fpage_ref_top_intro_general'	=> $expertise_descendant_fpage_ref_top_intro_general, // string
					'expertise_descendant_fpage_ref_top_link_general'	=> $expertise_descendant_fpage_ref_top_link_general // string
				);
				return $fpage_text_expertise_general_vars;

		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Clinical Resources
		function uamswp_fad_fpage_text_clinical_resource_general(
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Get the Find-a-Doc Settings values for the text elements in general placements
			$clinical_resource_fpage_title_general = get_field('clinical_resource_fpage_title_general', 'option'); // Fake subpage (or section), title
			$clinical_resource_fpage_intro_general = get_field('clinical_resource_fpage_intro_general', 'option'); // Fake subpage (or section), intro text
			$clinical_resource_fpage_ref_main_title_general = get_field('clinical_resource_fpage_ref_main_title_general', 'option'); // Reference to the main clinical resource archive, title
			$clinical_resource_fpage_ref_main_intro_general = get_field('clinical_resource_fpage_ref_main_intro_general', 'option'); // Reference to the main clinical resource archive, body text
			$clinical_resource_fpage_ref_main_link_general = get_field('clinical_resource_fpage_ref_main_link_general', 'option'); // Reference to the main clinical resource archive, link text
			$clinical_resource_fpage_ref_top_title_general = get_field('clinical_resource_fpage_ref_top_title_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Clinical Resources, title
			$clinical_resource_fpage_ref_top_intro_general = get_field('clinical_resource_fpage_ref_top_intro_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Clinical Resources, body text
			$clinical_resource_fpage_ref_top_link_general = get_field('clinical_resource_fpage_ref_top_link_general', 'option'); // Reference to a Top-Level Ontology Item's Fake Subpage for Clinical Resources, link text
			$clinical_resource_fpage_more_text_general = get_field('clinical_resource_fpage_more_text_general', 'option'); // Fake subpage (or section), "More", intro text
			$clinical_resource_fpage_more_link_text_general = get_field('clinical_resource_fpage_more_link_text_general', 'option'); // Fake subpage (or section), "More", link text
			$clinical_resource_fpage_more_link_descr_general = get_field('clinical_resource_fpage_more_link_descr_general', 'option'); // Fake subpage (or section), "More", link description

			// If the variable is not set or is empty...
			// Set a hardcoded fallback value
			$clinical_resource_fpage_title_general = ( isset($clinical_resource_fpage_title_general) && !empty($clinical_resource_fpage_title_general) ) ? $clinical_resource_fpage_title_general : 'Related [Clinical Resources]'; // Fake subpage (or section), title
			$clinical_resource_fpage_intro_general = ( isset($clinical_resource_fpage_intro_general) && !empty($clinical_resource_fpage_intro_general) ) ? $clinical_resource_fpage_intro_general : ''; // Fake subpage (or section), intro text
			$clinical_resource_fpage_ref_main_title_general = ( isset($clinical_resource_fpage_ref_main_title_general) && !empty($clinical_resource_fpage_ref_main_title_general) ) ? $clinical_resource_fpage_ref_main_title_general : 'Full List of [Clinical Resources]'; // Reference to the main clinical resource archive, title
			$clinical_resource_fpage_ref_main_intro_general = ( isset($clinical_resource_fpage_ref_main_intro_general) && !empty($clinical_resource_fpage_ref_main_intro_general) ) ? $clinical_resource_fpage_ref_main_intro_general : 'Access a wealth of [clinical resources], including articles, videos, infographics, and documents, covering various specialties within UAMS Health. Expand your knowledge and stay informed.'; // Reference to the main clinical resource archive, body text
			$clinical_resource_fpage_ref_main_link_general = ( isset($clinical_resource_fpage_ref_main_link_general) && !empty($clinical_resource_fpage_ref_main_link_general) ) ? $clinical_resource_fpage_ref_main_link_general : 'View All [Clinical Resources]'; // Reference to the main clinical resource archive, link text
			$clinical_resource_fpage_ref_top_title_general = ( isset($clinical_resource_fpage_ref_top_title_general) && !empty($clinical_resource_fpage_ref_top_title_general) ) ? $clinical_resource_fpage_ref_top_title_general : $clinical_resource_fpage_ref_main_title_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Clinical Resources, title
			$clinical_resource_fpage_ref_top_intro_general = ( isset($clinical_resource_fpage_ref_top_intro_general) && !empty($clinical_resource_fpage_ref_top_intro_general) ) ? $clinical_resource_fpage_ref_top_intro_general : $clinical_resource_fpage_ref_main_intro_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Clinical Resources, body text
			$clinical_resource_fpage_ref_top_link_general = ( isset($clinical_resource_fpage_ref_top_link_general) && !empty($clinical_resource_fpage_ref_top_link_general) ) ? $clinical_resource_fpage_ref_top_link_general : $clinical_resource_fpage_ref_main_link_general; // Reference to a Top-Level Ontology Item's Fake Subpage for Clinical Resources, link text
			$clinical_resource_fpage_more_text_general = ( isset($clinical_resource_fpage_more_text_general) && !empty($clinical_resource_fpage_more_text_general) ) ? $clinical_resource_fpage_more_text_general : 'Want to find more related [clinical resources]?'; // Fake subpage (or section), "More", intro text
			$clinical_resource_fpage_more_link_text_general = ( isset($clinical_resource_fpage_more_link_text_general) && !empty($clinical_resource_fpage_more_link_text_general) ) ? $clinical_resource_fpage_more_link_text_general : 'View the Full List';// Fake subpage (or section), "More", link text
			$clinical_resource_fpage_more_link_descr_general = ( isset($clinical_resource_fpage_more_link_descr_general) && !empty($clinical_resource_fpage_more_link_descr_general) ) ? $clinical_resource_fpage_more_link_descr_general : 'View the full list of related [clinical resources]'; // Fake subpage (or section), "More", link description

			// Substitute placeholder text for relevant Find-a-Doc Settings value
			$clinical_resource_fpage_title_general = $clinical_resource_fpage_title_general ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_title_general, $page_titles) : ''; // Fake subpage (or section), title
			$clinical_resource_fpage_intro_general = $clinical_resource_fpage_intro_general ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_intro_general, $page_titles) : ''; // Fake subpage (or section), intro text
			$clinical_resource_fpage_ref_main_title_general = $clinical_resource_fpage_ref_main_title_general ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_title_general, $page_titles) : ''; // Reference to the main clinical resource archive, title
			$clinical_resource_fpage_ref_main_intro_general = $clinical_resource_fpage_ref_main_intro_general ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_intro_general, $page_titles) : ''; // Reference to the main clinical resource archive, body text
			$clinical_resource_fpage_ref_main_link_general = $clinical_resource_fpage_ref_main_link_general ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_link_general, $page_titles) : ''; // Reference to the main clinical resource archive, link text
			$clinical_resource_fpage_ref_top_title_general = $clinical_resource_fpage_ref_top_title_general ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_top_title_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Clinical Resources, title
			$clinical_resource_fpage_ref_top_intro_general = $clinical_resource_fpage_ref_top_intro_general ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_top_intro_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Clinical Resources, body text
			$clinical_resource_fpage_ref_top_link_general = $clinical_resource_fpage_ref_top_link_general ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_top_link_general, $page_titles) : ''; // Reference to a Top-Level Ontology Item's Fake Subpage for Clinical Resources, link text
			$clinical_resource_fpage_more_text_general = $clinical_resource_fpage_more_text_general ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_text_general, $page_titles) : ''; // Fake subpage (or section), "More", intro text
			$clinical_resource_fpage_more_link_text_general = $clinical_resource_fpage_more_link_text_general ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_link_text_general, $page_titles) : ''; // Fake subpage (or section), "More", link text
			$clinical_resource_fpage_more_link_descr_general = $clinical_resource_fpage_more_link_descr_general ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_link_descr_general, $page_titles) : ''; // Fake subpage (or section), "More", link description

			// Create and return an array to be used on the templates and template parts

				$fpage_text_clinical_resource_general_vars = array(
					'clinical_resource_fpage_title_general'				=> $clinical_resource_fpage_title_general, // string
					'clinical_resource_fpage_intro_general'				=> $clinical_resource_fpage_intro_general, // string
					'clinical_resource_fpage_ref_main_title_general'	=> $clinical_resource_fpage_ref_main_title_general, // string
					'clinical_resource_fpage_ref_main_intro_general'	=> $clinical_resource_fpage_ref_main_intro_general, // string
					'clinical_resource_fpage_ref_main_link_general'		=> $clinical_resource_fpage_ref_main_link_general, // string
					'clinical_resource_fpage_ref_top_title_general'		=> $clinical_resource_fpage_ref_top_title_general, // string
					'clinical_resource_fpage_ref_top_intro_general'		=> $clinical_resource_fpage_ref_top_intro_general, // string
					'clinical_resource_fpage_ref_top_link_general'		=> $clinical_resource_fpage_ref_top_link_general, // string
					'clinical_resource_fpage_more_text_general'			=> $clinical_resource_fpage_more_text_general, // string
					'clinical_resource_fpage_more_link_text_general'	=> $clinical_resource_fpage_more_link_text_general, // string
					'clinical_resource_fpage_more_link_descr_general'	=> $clinical_resource_fpage_more_link_descr_general // string
				);
				return $fpage_text_clinical_resource_general_vars;

		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Conditions
		function uamswp_fad_fpage_text_condition_general(
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Get the Find-a-Doc Settings values for the text elements in general placements
			$condition_fpage_title_general = get_field('conditions_fpage_title_general', 'option'); // Fake subpage (or section), title
			$condition_fpage_intro_general = get_field('conditions_fpage_intro_general', 'option'); // Fake subpage (or section), intro text

			// If the variable is not set or is empty...
			// Set a hardcoded fallback value
			$condition_fpage_title_general = ( isset($condition_fpage_title_general) && !empty($condition_fpage_title_general) ) ? $condition_fpage_title_general : 'Related [Conditions]'; // Fake subpage (or section), title
			$condition_fpage_intro_general = ( isset($condition_fpage_intro_general) && !empty($condition_fpage_intro_general) ) ? $condition_fpage_intro_general : 'UAMS Health [providers] care for a broad range of [conditions], some of which may not be listed below.'; // Fake subpage (or section), intro text

			// Substitute placeholder text for relevant Find-a-Doc Settings value
			$condition_fpage_title_general = uamswp_fad_fpage_text_replace($condition_fpage_title_general, $page_titles); // Fake subpage (or section), title
			$condition_fpage_intro_general = uamswp_fad_fpage_text_replace($condition_fpage_intro_general, $page_titles); // Fake subpage (or section), intro text

			// Create and return an array to be used on the templates and template parts

				$fpage_text_condition_general_vars = array(
					'condition_fpage_title_general'	=> $condition_fpage_title_general, // string
					'condition_fpage_intro_general'	=> $condition_fpage_intro_general // string
				);
				return $fpage_text_condition_general_vars;

		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Treatments
		function uamswp_fad_fpage_text_treatment_general(
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Get the Find-a-Doc Settings values for the text elements in general placements
			$treatment_fpage_title_general = get_field('treatments_fpage_title_general', 'option'); // Fake subpage (or section), title
			$treatment_fpage_intro_general = get_field('treatments_fpage_intro_general', 'option'); // Fake subpage (or section), intro text

			// If the variable is not set or is empty...
			// Set a hardcoded fallback value
			$treatment_fpage_title_general = ( isset($treatment_fpage_title_general) && !empty($treatment_fpage_title_general) ) ? $treatment_fpage_title_general : 'Related [Treatments]'; // Fake subpage (or section), title
			$treatment_fpage_intro_general = ( isset($treatment_fpage_intro_general) && !empty($treatment_fpage_intro_general) ) ? $treatment_fpage_intro_general : 'UAMS Health [providers] perform and prescribe a broad range of [treatments], some of which may not be listed below.'; // Fake subpage (or section), intro text

			// Substitute placeholder text for relevant Find-a-Doc Settings value
			$treatment_fpage_title_general = uamswp_fad_fpage_text_replace($treatment_fpage_title_general, $page_titles); // Fake subpage (or section), title
			$treatment_fpage_intro_general = uamswp_fad_fpage_text_replace($treatment_fpage_intro_general, $page_titles); // Fake subpage (or section), intro text

			// Create and return an array to be used on the templates and template parts

				$fpage_text_treatment_general_vars = array(
					'treatment_fpage_title_general'	=> $treatment_fpage_title_general, // string
					'treatment_fpage_intro_general'	=> $treatment_fpage_intro_general // string
				);
				return $fpage_text_treatment_general_vars;

		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Conditions and Treatments combined
		function uamswp_fad_fpage_text_condition_treatment_general(
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Get the Find-a-Doc Settings values for the text elements in general placements
			$condition_treatment_fpage_title_general = get_field('condition_treatment_fpage_title_general', 'option'); // Fake subpage (or section), title
			$condition_treatment_fpage_intro_general = get_field('condition_treatment_fpage_intro_general', 'option'); // Fake subpage (or section), intro text

			// If the variable is not set or is empty...
			// Set a hardcoded fallback value
			$condition_treatment_fpage_title_general = ( isset($condition_treatment_fpage_title_general) && !empty($condition_treatment_fpage_title_general) ) ? $condition_treatment_fpage_title_general : 'Related [Conditions and Treatments]'; // Fake subpage (or section), title
			$condition_treatment_fpage_intro_general = ( isset($condition_treatment_fpage_intro_general) && !empty($condition_treatment_fpage_intro_general) ) ? $condition_treatment_fpage_intro_general : 'UAMS Health [providers] perform and prescribe a broad range of [treatments] for a broad range of [conditions], some of which may not be listed below.'; // Fake subpage (or section), intro text

			// Substitute placeholder text for relevant Find-a-Doc Settings value
			$condition_treatment_fpage_title_general = uamswp_fad_fpage_text_replace($condition_treatment_fpage_title_general, $page_titles); // Fake subpage (or section), title
			$condition_treatment_fpage_intro_general = uamswp_fad_fpage_text_replace($condition_treatment_fpage_intro_general, $page_titles); // Fake subpage (or section), intro text

			// Create and return an array to be used on the templates and template parts

				$fpage_text_condition_treatment_general_vars = array(
					'condition_treatment_fpage_title_general'	=> $condition_treatment_fpage_title_general, // string
					'condition_treatment_fpage_intro_general'	=> $condition_treatment_fpage_intro_general // string
				);
				return $fpage_text_condition_treatment_general_vars;

		}

	// Get Find-a-Doc Settings values and page-level values for ontology text elements in specific subsections and single profiles

		// Get field values for fake subpage text elements in an Provider subsection (or profile)
		function uamswp_fad_fpage_text_provider(
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Locations Fake Subpage (or Section)

				$location_fpage_title_provider = get_field('location_fpage_title_provider', 'option'); // Title of a Fake Subpage (or Section) for Locations in a Provider Subsection (or Profile)
				$location_fpage_intro_provider = get_field('location_fpage_intro_provider', 'option'); // Intro Text of a Fake Subpage (or Section) for Locations in a Provider Subsection (or Profile)
				$location_fpage_ref_main_title_provider = get_field('location_fpage_ref_main_title_provider', 'option'); // Reference to the main location archive, title
				$location_fpage_ref_main_intro_provider = get_field('location_fpage_ref_main_intro_provider', 'option'); // Reference to the main location archive, body text
				$location_fpage_ref_main_link_provider = get_field('location_fpage_ref_main_link_provider', 'option'); // Reference to the main location archive, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($location_fpage_title_provider) || empty($location_fpage_title_provider) ) {
						$location_fpage_title_provider = '[Locations] Where [Provider Short Name] Practices'; // Title
					}
					if ( !isset($location_fpage_intro_provider) || empty($location_fpage_intro_provider) ) {
						$location_fpage_intro_provider = ''; // Intro text
					}
					if ( !isset($location_fpage_ref_main_title_provider) || empty($location_fpage_ref_main_title_provider) ) {
						$location_fpage_ref_main_title_provider = ''; // Reference to the main location archive, title
					}
					if ( !isset($location_fpage_ref_main_intro_provider) || empty($location_fpage_ref_main_intro_provider) ) {
						$location_fpage_ref_main_intro_provider = ''; // Reference to the main location archive, body text
					}
					if ( !isset($location_fpage_ref_main_link_provider) || empty($location_fpage_ref_main_link_provider) ) {
						$location_fpage_ref_main_link_provider = ''; // Reference to the main location archive, link text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if (
						!isset($location_fpage_title_provider) || empty($location_fpage_title_provider)
						||
						!isset($location_fpage_intro_provider) || empty($location_fpage_intro_provider)
						||
						!isset($location_fpage_ref_main_title_provider) || empty($location_fpage_ref_main_title_provider)
						||
						!isset($location_fpage_ref_main_intro_provider) || empty($location_fpage_ref_main_intro_provider)
						||
						!isset($location_fpage_ref_main_link_provider) || empty($location_fpage_ref_main_link_provider)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($location_fpage_title_general) || empty($location_fpage_title_general)
							||
							!isset($location_fpage_intro_general) || empty($location_fpage_intro_general)
							||
							!isset($location_fpage_ref_main_title_general) || empty($location_fpage_ref_main_title_general)
							||
							!isset($location_fpage_ref_main_intro_general) || empty($location_fpage_ref_main_intro_general)
							||
							!isset($location_fpage_ref_main_link_general) || empty($location_fpage_ref_main_link_general)
							) {
							$fpage_text_location_general_vars = isset($fpage_text_location_general_vars) ? $fpage_text_location_general_vars : uamswp_fad_fpage_text_location_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$location_fpage_title_general = $fpage_text_location_general_vars['location_fpage_title_general']; // string
								$location_fpage_intro_general = $fpage_text_location_general_vars['location_fpage_intro_general']; // string
								$location_fpage_ref_main_title_general = $fpage_text_location_general_vars['location_fpage_ref_main_title_general']; // string
								$location_fpage_ref_main_intro_general = $fpage_text_location_general_vars['location_fpage_ref_main_intro_general']; // string
								$location_fpage_ref_main_link_general = $fpage_text_location_general_vars['location_fpage_ref_main_link_general']; // string
						}
					}
					if ( !isset($location_fpage_title_provider) || empty($location_fpage_title_provider) ) {
						$location_fpage_title_provider = $location_fpage_title_general; // Title
					}
					if ( !isset($location_fpage_intro_provider) || empty($location_fpage_intro_provider) ) {
						$location_fpage_intro_provider = $location_fpage_intro_general; // Intro text
					}
					if ( !isset($location_fpage_ref_main_title_provider) || empty($location_fpage_ref_main_title_provider) ) {
						$location_fpage_ref_main_title_provider = $location_fpage_ref_main_title_general; // Reference to the main location archive, title
					}
					if ( !isset($location_fpage_ref_main_intro_provider) || empty($location_fpage_ref_main_intro_provider) ) {
						$location_fpage_ref_main_intro_provider = $location_fpage_ref_main_intro_general; // Reference to the main location archive, body text
					}
					if ( !isset($location_fpage_ref_main_link_provider) || empty($location_fpage_ref_main_link_provider) ) {
						$location_fpage_ref_main_link_provider = $location_fpage_ref_main_link_general; // Reference to the main location archive, link text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$location_fpage_title_provider = $location_fpage_title_provider ? uamswp_fad_fpage_text_replace($location_fpage_title_provider, $page_titles) : ''; // Title
					$location_fpage_intro_provider = $location_fpage_intro_provider ? uamswp_fad_fpage_text_replace($location_fpage_intro_provider, $page_titles) : ''; // Intro text
					$location_fpage_ref_main_title_provider = $location_fpage_ref_main_title_provider ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_title_provider, $page_titles) : ''; // Reference to the main location archive, title
					$location_fpage_ref_main_intro_provider = $location_fpage_ref_main_intro_provider ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_intro_provider, $page_titles) : ''; // Reference to the main location archive, body text
					$location_fpage_ref_main_link_provider = $location_fpage_ref_main_link_provider ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_link_provider, $page_titles) : ''; // Reference to the main location archive, link text

			// Areas of Expertise Fake Subpage (or Section)

				$expertise_fpage_title_provider = get_field('expertise_fpage_title_provider', 'option'); // Title of a Fake Subpage (or Section) for Areas of Expertise in a Provider Subsection (or Profile)
				$expertise_fpage_intro_provider = get_field('expertise_fpage_intro_provider', 'option'); // Intro Text of a Fake Subpage (or Section) for Areas of Expertise in a Provider Subsection (or Profile)
				$expertise_fpage_ref_main_title_provider = get_field('expertise_fpage_ref_main_title_provider', 'option'); // Reference to the main area of expertise archive, title
				$expertise_fpage_ref_main_intro_provider = get_field('expertise_fpage_ref_main_intro_provider', 'option'); // Reference to the main area of expertise archive, body text
				$expertise_fpage_ref_main_link_provider = get_field('expertise_fpage_ref_main_link_provider', 'option'); // Reference to the main area of expertise archive, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($expertise_fpage_title_provider) || empty($expertise_fpage_title_provider) ) {
						$expertise_fpage_title_provider = '[Provider Short Name\'s] [Areas of Expertise]'; // Title
					}
					if ( !isset($expertise_fpage_intro_provider) || empty($expertise_fpage_intro_provider) ) {
						$expertise_fpage_intro_provider = ''; // Intro text
					}
					if ( !isset($expertise_fpage_ref_main_title_provider) || empty($expertise_fpage_ref_main_title_provider) ) {
						$expertise_fpage_ref_main_title_provider = ''; // Reference to the main area of expertise archive, title
					}
					if ( !isset($expertise_fpage_ref_main_intro_provider) || empty($expertise_fpage_ref_main_intro_provider) ) {
						$expertise_fpage_ref_main_intro_provider = ''; // Reference to the main area of expertise archive, body text
					}
					if ( !isset($expertise_fpage_ref_main_link_provider) || empty($expertise_fpage_ref_main_link_provider) ) {
						$expertise_fpage_ref_main_link_provider = ''; // Reference to the main area of expertise archive, link text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if (
						!isset($expertise_fpage_title_provider) || empty($expertise_fpage_title_provider)
						||
						!isset($expertise_fpage_intro_provider) || empty($expertise_fpage_intro_provider)
						||
						!isset($expertise_fpage_ref_main_title_provider) || empty($expertise_fpage_ref_main_title_provider)
						||
						!isset($expertise_fpage_ref_main_intro_provider) || empty($expertise_fpage_ref_main_intro_provider)
						||
						!isset($expertise_fpage_ref_main_link_provider) || empty($expertise_fpage_ref_main_link_provider)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($expertise_fpage_title_general) || empty($expertise_fpage_title_general)
							||
							!isset($expertise_fpage_intro_general) || empty($expertise_fpage_intro_general)
							||
							!isset($expertise_fpage_ref_main_title_general) || empty($expertise_fpage_ref_main_title_general)
							||
							!isset($expertise_fpage_ref_main_intro_general) || empty($expertise_fpage_ref_main_intro_general)
							||
							!isset($expertise_fpage_ref_main_link_general) || empty($expertise_fpage_ref_main_link_general)
							) {
							$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$expertise_fpage_title_general = $fpage_text_expertise_general_vars['expertise_fpage_title_general']; // string
								$expertise_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_intro_general']; // string
								$expertise_fpage_ref_main_title_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_title_general']; // string
								$expertise_fpage_ref_main_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_intro_general']; // string
								$expertise_fpage_ref_main_link_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_link_general']; // string
						}
					}
					if ( !isset($expertise_fpage_title_provider) || empty($expertise_fpage_title_provider) ) {
						$expertise_fpage_title_provider = $expertise_fpage_title_general; // Title
					}
					if ( !isset($expertise_fpage_intro_provider) || empty($expertise_fpage_intro_provider) ) {
						$expertise_fpage_intro_provider = $expertise_fpage_intro_general; // Intro text
					}
					if ( !isset($expertise_fpage_ref_main_title_provider) || empty($expertise_fpage_ref_main_title_provider) ) {
						$expertise_fpage_ref_main_title_provider = $expertise_fpage_ref_main_title_general; // Reference to the main area of expertise archive, title
					}
					if ( !isset($expertise_fpage_ref_main_intro_provider) || empty($expertise_fpage_ref_main_intro_provider) ) {
						$expertise_fpage_ref_main_intro_provider = $expertise_fpage_ref_main_intro_general; // Reference to the main area of expertise archive, body text
					}
					if ( !isset($expertise_fpage_ref_main_link_provider) || empty($expertise_fpage_ref_main_link_provider) ) {
						$expertise_fpage_ref_main_link_provider = $expertise_fpage_ref_main_link_general; // Reference to the main area of expertise archive, link text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$expertise_fpage_title_provider = $expertise_fpage_title_provider ? uamswp_fad_fpage_text_replace($expertise_fpage_title_provider, $page_titles) : ''; // Title
					$expertise_fpage_intro_provider = $expertise_fpage_intro_provider ? uamswp_fad_fpage_text_replace($expertise_fpage_intro_provider, $page_titles) : ''; // Intro text
					$expertise_fpage_ref_main_title_provider = $expertise_fpage_ref_main_title_provider ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_title_provider, $page_titles) : ''; // Reference to the main area of expertise archive, title
					$expertise_fpage_ref_main_intro_provider = $expertise_fpage_ref_main_intro_provider ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_intro_provider, $page_titles) : ''; // Reference to the main area of expertise archive, body text
					$expertise_fpage_ref_main_link_provider = $expertise_fpage_ref_main_link_provider ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_link_provider, $page_titles) : ''; // Reference to the main area of expertise archive, link text

			// Clinical Resources Fake Subpage (or Section)

				$clinical_resource_fpage_title_provider = get_field('clinical_resource_fpage_title_provider', 'option'); // Title of Section for Clinical Resources on Clinical Resource Profile
				$clinical_resource_fpage_intro_provider = get_field('clinical_resource_fpage_intro_provider', 'option'); // Intro Text of Section for Clinical Resources on Clinical Resource Profile
				$clinical_resource_fpage_ref_main_title_provider = get_field('clinical_resource_fpage_ref_main_title_provider', 'option'); // Reference to the main clinical resource archive, title
				$clinical_resource_fpage_ref_main_intro_provider = get_field('clinical_resource_fpage_ref_main_intro_provider', 'option'); // Reference to the main clinical resource archive, body text
				$clinical_resource_fpage_ref_main_link_provider = get_field('clinical_resource_fpage_ref_main_link_provider', 'option'); // Reference to the main clinical resource archive, link text
				$clinical_resource_fpage_more_text_provider = get_field('clinical_resource_fpage_more_text_provider', 'option'); // "More" Intro Text of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)
				$clinical_resource_fpage_more_link_text_provider = get_field('clinical_resource_fpage_more_link_text_provider', 'option'); // "More" Link Text of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)
				$clinical_resource_fpage_more_link_descr_provider = get_field('clinical_resource_fpage_more_link_descr_provider', 'option'); // "More" Link Description of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($clinical_resource_fpage_title_provider) || empty($clinical_resource_fpage_title_provider) ) {
						$clinical_resource_fpage_title_provider = '[Clinical Resources] Related to [Provider Short Name]'; // Title
					}
					if ( !isset($clinical_resource_fpage_intro_provider) || empty($clinical_resource_fpage_intro_provider) ) {
						$clinical_resource_fpage_intro_provider = ''; // Intro text
					}
					if ( !isset($clinical_resource_fpage_ref_main_title_provider) || empty($clinical_resource_fpage_ref_main_title_provider) ) {
						$clinical_resource_fpage_ref_main_title_provider = ''; // Reference to the main clinical resource archive, title
					}
					if ( !isset($clinical_resource_fpage_ref_main_intro_provider) || empty($clinical_resource_fpage_ref_main_intro_provider) ) {
						$clinical_resource_fpage_ref_main_intro_provider = ''; // Reference to the main clinical resource archive, body text
					}
					if ( !isset($clinical_resource_fpage_ref_main_link_provider) || empty($clinical_resource_fpage_ref_main_link_provider) ) {
						$clinical_resource_fpage_ref_main_link_provider = ''; // Reference to the main clinical resource archive, link text
					}
					if ( !isset($clinical_resource_fpage_more_text_provider) || empty($clinical_resource_fpage_more_text_provider) ) {
						$clinical_resource_fpage_more_text_provider = 'Want to find more [clinical resources] related to [Provider Short Name]?'; // "More" intro text
					}
					if ( !isset($clinical_resource_fpage_more_link_text_provider) || empty($clinical_resource_fpage_more_link_text_provider) ) {
						$clinical_resource_fpage_more_link_text_provider = 'View the Full List'; // "More" link text
					}
					if ( !isset($clinical_resource_fpage_more_link_descr_provider) || empty($clinical_resource_fpage_more_link_descr_provider) ) {
						$clinical_resource_fpage_more_link_descr_provider = 'View the full list of [clinical resources] related to [Provider Short Name]'; // "More" link description
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if (
						!isset($clinical_resource_fpage_title_provider) || empty($clinical_resource_fpage_title_provider)
						||
						!isset($clinical_resource_fpage_intro_provider) || empty($clinical_resource_fpage_intro_provider)
						||
						!isset($clinical_resource_fpage_ref_main_title_provider) || empty($clinical_resource_fpage_ref_main_title_provider)
						||
						!isset($clinical_resource_fpage_ref_main_intro_provider) || empty($clinical_resource_fpage_ref_main_intro_provider)
						||
						!isset($clinical_resource_fpage_ref_main_link_provider) || empty($clinical_resource_fpage_ref_main_link_provider)
						||
						!isset($clinical_resource_fpage_more_text_provider) || empty($clinical_resource_fpage_more_text_provider)
						||
						!isset($clinical_resource_fpage_more_link_text_provider) || empty($clinical_resource_fpage_more_link_text_provider)
						||
						!isset($clinical_resource_fpage_more_link_descr_provider) || empty($clinical_resource_fpage_more_link_descr_provider)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($clinical_resource_fpage_title_general) || empty($clinical_resource_fpage_title_general)
							||
							!isset($clinical_resource_fpage_intro_general) || empty($clinical_resource_fpage_intro_general)
							||
							!isset($clinical_resource_fpage_ref_main_title_general) || empty($clinical_resource_fpage_ref_main_title_general)
							||
							!isset($clinical_resource_fpage_ref_main_intro_general) || empty($clinical_resource_fpage_ref_main_intro_general)
							||
							!isset($clinical_resource_fpage_ref_main_link_general) || empty($clinical_resource_fpage_ref_main_link_general)
							||
							!isset($clinical_resource_fpage_more_text_general) || empty($clinical_resource_fpage_more_text_general)
							||
							!isset($clinical_resource_fpage_more_link_text_general) || empty($clinical_resource_fpage_more_link_text_general)
							||
							!isset($clinical_resource_fpage_more_link_descr_general) || empty($clinical_resource_fpage_more_link_descr_general)
							) {
							$fpage_text_clinical_resource_general_vars = isset($fpage_text_clinical_resource_general_vars) ? $fpage_text_clinical_resource_general_vars : uamswp_fad_fpage_text_clinical_resource_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$clinical_resource_fpage_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_title_general']; // string
								$clinical_resource_fpage_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_intro_general']; // string
								$clinical_resource_fpage_ref_main_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_title_general']; // string
								$clinical_resource_fpage_ref_main_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_intro_general']; // string
								$clinical_resource_fpage_ref_main_link_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_link_general']; // string
								$clinical_resource_fpage_more_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_text_general']; // string
								$clinical_resource_fpage_more_link_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_text_general']; // string
								$clinical_resource_fpage_more_link_descr_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_descr_general']; // string
						}
					}
					if ( !isset($clinical_resource_fpage_title_provider) || empty($clinical_resource_fpage_title_provider) ) {
						$clinical_resource_fpage_title_provider = $clinical_resource_fpage_title_general; // Title
					}
					if ( !isset($clinical_resource_fpage_intro_provider) || empty($clinical_resource_fpage_intro_provider) ) {
						$clinical_resource_fpage_intro_provider = $clinical_resource_fpage_intro_general; // Intro text
					}
					if ( !isset($clinical_resource_fpage_ref_main_title_provider) || empty($clinical_resource_fpage_ref_main_title_provider) ) {
						$clinical_resource_fpage_ref_main_title_provider = $clinical_resource_fpage_ref_main_title_general; // Reference to the main clinical resource archive, title
					}
					if ( !isset($clinical_resource_fpage_ref_main_intro_provider) || empty($clinical_resource_fpage_ref_main_intro_provider) ) {
						$clinical_resource_fpage_ref_main_intro_provider = $clinical_resource_fpage_ref_main_intro_general; // Reference to the main clinical resource archive, body text
					}
					if ( !isset($clinical_resource_fpage_ref_main_link_provider) || empty($clinical_resource_fpage_ref_main_link_provider) ) {
						$clinical_resource_fpage_ref_main_link_provider = $clinical_resource_fpage_ref_main_link_general; // Reference to the main clinical resource archive, link text
					}
					if ( !isset($clinical_resource_fpage_more_text_provider) || empty($clinical_resource_fpage_more_text_provider) ) {
						$clinical_resource_fpage_more_text_provider = $clinical_resource_fpage_more_text_general; // "More" intro text
					}
					if ( !isset($clinical_resource_fpage_more_link_text_provider) || empty($clinical_resource_fpage_more_link_text_provider) ) {
						$clinical_resource_fpage_more_link_text_provider = $clinical_resource_fpage_more_link_text_general; // "More" link text
					}
					if ( !isset($clinical_resource_fpage_more_link_descr_provider) || empty($clinical_resource_fpage_more_link_descr_provider) ) {
						$clinical_resource_fpage_more_link_descr_provider = $clinical_resource_fpage_more_link_descr_general; // "More" link description
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$clinical_resource_fpage_title_provider = $clinical_resource_fpage_title_provider ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_title_provider, $page_titles) : ''; // Title
					$clinical_resource_fpage_intro_provider = $clinical_resource_fpage_intro_provider ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_intro_provider, $page_titles) : ''; // Intro text
					$clinical_resource_fpage_ref_main_title_provider = $clinical_resource_fpage_ref_main_title_provider ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_title_provider, $page_titles) : ''; // Reference to the main clinical resource archive, title
					$clinical_resource_fpage_ref_main_intro_provider = $clinical_resource_fpage_ref_main_intro_provider ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_intro_provider, $page_titles) : ''; // Reference to the main clinical resource archive, body text
					$clinical_resource_fpage_ref_main_link_provider = $clinical_resource_fpage_ref_main_link_provider ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_link_provider, $page_titles) : ''; // Reference to the main clinical resource archive, link text
					$clinical_resource_fpage_more_text_provider = $clinical_resource_fpage_more_text_provider ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_text_provider, $page_titles) : ''; // "More" intro text
					$clinical_resource_fpage_more_link_text_provider = $clinical_resource_fpage_more_link_text_provider ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_link_text_provider, $page_titles) : ''; // "More" link text
					$clinical_resource_fpage_more_link_descr_provider = $clinical_resource_fpage_more_link_descr_provider ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_link_descr_provider, $page_titles) : ''; // "More" link description

			// Conditions Fake Subpage (or Section)

				$condition_fpage_title_provider = get_field('conditions_fpage_title_provider', 'option'); // Title of a Fake Subpage (or Section) for Conditions in a Provider Subsection (or Profile)
				$condition_fpage_intro_provider = get_field('conditions_fpage_intro_provider', 'option'); // Intro Text of a Fake Subpage (or Section) for Conditions in a Provider Subsection (or Profile)

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($condition_fpage_title_provider) || empty($condition_fpage_title_provider) ) {
						$condition_fpage_title_provider = '[Conditions] Diagnosed or Treated by [Provider Short Name]'; // Title
					}
					if ( !isset($condition_fpage_intro_provider) || empty($condition_fpage_intro_provider) ) {
						$condition_fpage_intro_provider = ''; // Intro text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if ( !isset($condition_fpage_title_provider) || empty($condition_fpage_title_provider) ) {
						if ( !isset($condition_fpage_title_general) || empty($condition_fpage_title_general) ) {
							$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_fpage_title_general = $fpage_text_condition_general_vars['condition_fpage_title_general']; // string
						}
						$condition_fpage_title_provider = $condition_fpage_title_general; // Title
					}
					if ( !isset($condition_fpage_intro_provider) || empty($condition_fpage_intro_provider) ) {
						if ( !isset($condition_fpage_intro_general) || empty($condition_fpage_intro_general) ) {
							$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_fpage_intro_general = $fpage_text_condition_general_vars['condition_fpage_intro_general']; // string
						}
						$condition_fpage_intro_provider = $condition_fpage_intro_general; // Intro text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$condition_fpage_title_provider = $condition_fpage_title_provider ? uamswp_fad_fpage_text_replace($condition_fpage_title_provider, $page_titles) : ''; // Title
					$condition_fpage_intro_provider = $condition_fpage_intro_provider ? uamswp_fad_fpage_text_replace($condition_fpage_intro_provider, $page_titles) : ''; // Intro text

			// Treatments Fake Subpage (or Section)

				$treatment_fpage_title_provider = get_field('treatments_fpage_title_provider', 'option'); // Title of a Fake Subpage (or Section) for Treatments in a Provider Subsection (or Profile)
				$treatment_fpage_intro_provider = get_field('treatments_fpage_intro_provider', 'option'); // Intro Text of a Fake Subpage (or Section) for Treatments in a Provider Subsection (or Profile)

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($treatment_fpage_title_provider) || empty($treatment_fpage_title_provider) ) {
						$treatment_fpage_title_provider = '[Treatments] Performed or Prescribed by [Provider Short Name]'; // Title
					}
					if ( !isset($treatment_fpage_intro_provider) || empty($treatment_fpage_intro_provider) ) {
						$treatment_fpage_intro_provider = ''; // Intro text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if ( !isset($treatment_fpage_title_provider) || empty($treatment_fpage_title_provider) ) {
						if ( !isset($treatment_fpage_title_general) || empty($treatment_fpage_title_general) ) {
							$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$treatment_fpage_title_general = $fpage_text_treatment_general_vars['treatment_fpage_title_general']; // string
						}
						$treatment_fpage_title_provider = $treatment_fpage_title_general; // Title
					}
					if ( !isset($treatment_fpage_intro_provider) || empty($treatment_fpage_intro_provider) ) {
						if ( !isset($treatment_fpage_intro_general) || empty($treatment_fpage_intro_general) ) {
							$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$treatment_fpage_intro_general = $fpage_text_treatment_general_vars['treatment_fpage_intro_general']; // string
						}
						$treatment_fpage_intro_provider = $treatment_fpage_intro_general; // Intro text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$treatment_fpage_title_provider = $treatment_fpage_title_provider ? uamswp_fad_fpage_text_replace($treatment_fpage_title_provider, $page_titles) : ''; // Title
					$treatment_fpage_intro_provider = $treatment_fpage_intro_provider ? uamswp_fad_fpage_text_replace($treatment_fpage_intro_provider, $page_titles) : ''; // Intro text


			// Combined Conditions and Treatments Fake Subpage (or Section)

				$condition_treatment_fpage_title_provider = get_field('condition_treatment_fpage_title_provider', 'option'); // Title of a Fake Subpage (or Section) for Conditions and Treatments Combined in a Location Subsection (or Profile)
				$condition_treatment_fpage_intro_provider = get_field('condition_treatment_fpage_intro_provider', 'option'); // Intro Text of a Fake Subpage (or Section) for Conditions and Treatments Combined in a Location Subsection (or Profile)

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($condition_treatment_fpage_title_provider) || empty($condition_treatment_fpage_title_provider) ) {
						$condition_treatment_fpage_title_provider = '[Conditions and Treatments] Related to [Provider Short Name]'; // Title
					}
					if ( !isset($condition_treatment_fpage_intro_provider) || empty($condition_treatment_fpage_intro_provider) ) {
						$condition_treatment_fpage_intro_provider = ''; // Intro text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if ( !isset($condition_treatment_fpage_title_provider) || empty($condition_treatment_fpage_title_provider) ) {
						if ( !isset($condition_treatment_fpage_title_general) || empty($condition_treatment_fpage_title_general) ) {
							$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_treatment_fpage_title_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_title_general']; // string
						}
						$condition_treatment_fpage_title_provider = $condition_treatment_fpage_title_general; // Title
					}
					if ( !isset($condition_treatment_fpage_intro_provider) || empty($condition_treatment_fpage_intro_provider) ) {
						if ( !isset($condition_treatment_fpage_intro_general) || empty($condition_treatment_fpage_intro_general) ) {
							$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_treatment_fpage_intro_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_intro_general']; // string
						}
						$condition_treatment_fpage_intro_provider = $condition_treatment_fpage_intro_general; // Intro text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$condition_treatment_fpage_title_provider = $condition_treatment_fpage_title_provider ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_title_provider, $page_titles) : ''; // Title
					$condition_treatment_fpage_intro_provider = $condition_treatment_fpage_intro_provider ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_intro_provider, $page_titles) : ''; // Intro text

			// Create and return an array to be used on the templates and template parts

				$fpage_text_provider_vars = array(
					'location_fpage_title_provider'						=> $location_fpage_title_provider, // string
					'location_fpage_intro_provider'						=> $location_fpage_intro_provider, // string
					'location_fpage_ref_main_title_provider'			=> $location_fpage_ref_main_title_provider, // string
					'location_fpage_ref_main_intro_provider'			=> $location_fpage_ref_main_intro_provider, // string
					'location_fpage_ref_main_link_provider'				=> $location_fpage_ref_main_link_provider, // string
					'expertise_fpage_title_provider'					=> $expertise_fpage_title_provider, // string
					'expertise_fpage_intro_provider'					=> $expertise_fpage_intro_provider, // string
					'expertise_fpage_ref_main_title_provider'			=> $expertise_fpage_ref_main_title_provider, // string
					'expertise_fpage_ref_main_intro_provider'			=> $expertise_fpage_ref_main_intro_provider, // string
					'expertise_fpage_ref_main_link_provider'			=> $expertise_fpage_ref_main_link_provider, // string
					'clinical_resource_fpage_title_provider'			=> $clinical_resource_fpage_title_provider, // string
					'clinical_resource_fpage_intro_provider'			=> $clinical_resource_fpage_intro_provider, // string
					'clinical_resource_fpage_ref_main_title_provider'	=> $clinical_resource_fpage_ref_main_title_provider, // string
					'clinical_resource_fpage_ref_main_intro_provider'	=> $clinical_resource_fpage_ref_main_intro_provider, // string
					'clinical_resource_fpage_ref_main_link_provider'	=> $clinical_resource_fpage_ref_main_link_provider, // string
					'clinical_resource_fpage_more_text_provider'		=> $clinical_resource_fpage_more_text_provider, // string
					'clinical_resource_fpage_more_link_text_provider'	=> $clinical_resource_fpage_more_link_text_provider, // string
					'clinical_resource_fpage_more_link_descr_provider'	=> $clinical_resource_fpage_more_link_descr_provider, // string
					'condition_fpage_title_provider'					=> $condition_fpage_title_provider, // string
					'condition_fpage_intro_provider'					=> $condition_fpage_intro_provider, // string
					'treatment_fpage_title_provider'					=> $treatment_fpage_title_provider, // string
					'treatment_fpage_intro_provider'					=> $treatment_fpage_intro_provider, // string
					'condition_treatment_fpage_title_provider'			=> $condition_treatment_fpage_title_provider, // string
					'condition_treatment_fpage_intro_provider'			=> $condition_treatment_fpage_intro_provider, // string
				);
				return $fpage_text_provider_vars;

		}

		// Get field values for fake subpage text elements in an Location subsection (or profile)
		function uamswp_fad_fpage_text_location(
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Providers Fake Subpage (or Section)

				$provider_fpage_title_location = get_field('provider_fpage_title_location', 'option'); // Title of a Fake Subpage (or Section) for Providers in Location Subsection
				$provider_fpage_intro_location = get_field('provider_fpage_intro_location', 'option'); // Intro Text of a Fake Subpage (or Section) for Providers in a Location Subsection (or Profile)
				$provider_fpage_ref_main_title_location = get_field('provider_fpage_ref_main_title_location', 'option'); // Reference to the main provider archive, title
				$provider_fpage_ref_main_intro_location = get_field('provider_fpage_ref_main_intro_location', 'option'); // Reference to the main provider archive, body text
				$provider_fpage_ref_main_link_location = get_field('provider_fpage_ref_main_link_location', 'option'); // Reference to the main provider archive, link text
				$provider_fpage_ref_top_title_location = get_field('provider_fpage_ref_top_title_location', 'option'); // Reference to a Top-Level Location's Fake Subpage for Providers, title
				$provider_fpage_ref_top_intro_location = get_field('provider_fpage_ref_top_intro_location', 'option'); // Reference to a Top-Level Location's Fake Subpage for Providers, body text
				$provider_fpage_ref_top_link_location = get_field('provider_fpage_ref_top_link_location', 'option'); // Reference to a Top-Level Location's Fake Subpage for Providers, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					$provider_fpage_title_location = ( !isset($provider_fpage_title_location) || empty($provider_fpage_title_location) ) ? $provider_fpage_title_location : '[Providers] at [Location Title]'; // Title
					$provider_fpage_intro_location = ( !isset($provider_fpage_intro_location) || empty($provider_fpage_intro_location) ) ? $provider_fpage_intro_location : ''; // Intro text
					$provider_fpage_ref_main_title_location = ( !isset($provider_fpage_ref_main_title_location) || empty($provider_fpage_ref_main_title_location) ) ? $provider_fpage_ref_main_title_location : ''; // Reference to the main provider archive, title
					$provider_fpage_ref_main_intro_location = ( !isset($provider_fpage_ref_main_intro_location) || empty($provider_fpage_ref_main_intro_location) ) ? $provider_fpage_ref_main_intro_location : ''; // Reference to the main provider archive, body text
					$provider_fpage_ref_main_link_location = ( !isset($provider_fpage_ref_main_link_location) || empty($provider_fpage_ref_main_link_location) ) ? $provider_fpage_ref_main_link_location : ''; // Reference to the main provider archive, link text
					$provider_fpage_ref_top_title_location = ( !isset($provider_fpage_ref_top_title_location) || empty($provider_fpage_ref_top_title_location) ) ? $provider_fpage_ref_top_title_location : ''; // Reference to a Top-Level Location's Fake Subpage for Providers, title
					$provider_fpage_ref_top_intro_location = ( !isset($provider_fpage_ref_top_intro_location) || empty($provider_fpage_ref_top_intro_location) ) ? $provider_fpage_ref_top_intro_location : ''; // Reference to a Top-Level Location's Fake Subpage for Providers, body text
					$provider_fpage_ref_top_link_location = ( !isset($provider_fpage_ref_top_link_location) || empty($provider_fpage_ref_top_link_location) ) ? $provider_fpage_ref_top_link_location : ''; // Reference to a Top-Level Location's Fake Subpage for Providers, link text

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if (
						!isset($provider_fpage_title_location) || empty($provider_fpage_title_location)
						||
						!isset($provider_fpage_intro_location) || empty($provider_fpage_intro_location)
						||
						!isset($provider_fpage_ref_main_title_location) || empty($provider_fpage_ref_main_title_location)
						||
						!isset($provider_fpage_ref_main_intro_location) || empty($provider_fpage_ref_main_intro_location)
						||
						!isset($provider_fpage_ref_main_link_location) || empty($provider_fpage_ref_main_link_location)
						||
						!isset($provider_fpage_ref_top_title_location) || empty($provider_fpage_ref_top_title_location)
						||
						!isset($provider_fpage_ref_top_intro_location) || empty($provider_fpage_ref_top_intro_location)
						||
						!isset($provider_fpage_ref_top_link_location) || empty($provider_fpage_ref_top_link_location)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($provider_fpage_title_general) || empty($provider_fpage_title_general)
							||
							!isset($provider_fpage_intro_general) || empty($provider_fpage_intro_general)
							||
							!isset($provider_fpage_ref_main_title_general) || empty($provider_fpage_ref_main_title_general)
							||
							!isset($provider_fpage_ref_main_intro_general) || empty($provider_fpage_ref_main_intro_general)
							||
							!isset($provider_fpage_ref_main_link_general) || empty($provider_fpage_ref_main_link_general)
							||
							!isset($provider_fpage_ref_top_title_general) || empty($provider_fpage_ref_top_title_general)
							||
							!isset($provider_fpage_ref_top_intro_general) || empty($provider_fpage_ref_top_intro_general)
							||
							!isset($provider_fpage_ref_top_link_general) || empty($provider_fpage_ref_top_link_general)
							) {
							$fpage_text_provider_general_vars = isset($fpage_text_provider_general_vars) ? $fpage_text_provider_general_vars : uamswp_fad_fpage_text_provider_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$provider_fpage_title_general = $fpage_text_provider_general_vars['provider_fpage_title_general']; // string
								$provider_fpage_intro_general = $fpage_text_provider_general_vars['provider_fpage_intro_general']; // string
								$provider_fpage_ref_main_title_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_title_general']; // string
								$provider_fpage_ref_main_intro_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_intro_general']; // string
								$provider_fpage_ref_main_link_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_link_general']; // string
								$provider_fpage_ref_top_title_general = $fpage_text_provider_general_vars['provider_fpage_ref_top_title_general']; // string
								$provider_fpage_ref_top_intro_general = $fpage_text_provider_general_vars['provider_fpage_ref_top_intro_general']; // string
								$provider_fpage_ref_top_link_general = $fpage_text_provider_general_vars['provider_fpage_ref_top_link_general']; // string
						}
					}
					$provider_fpage_title_location = ( !isset($provider_fpage_title_location) || empty($provider_fpage_title_location) ) ? $provider_fpage_title_general : ''; // Title
					$provider_fpage_intro_location = ( !isset($provider_fpage_intro_location) || empty($provider_fpage_intro_location) ) ? $provider_fpage_intro_general : ''; // Intro text
					$provider_fpage_ref_main_title_location = ( !isset($provider_fpage_ref_main_title_location) || empty($provider_fpage_ref_main_title_location) ) ? $provider_fpage_ref_main_title_general : ''; // Reference to the main provider archive, title
					$provider_fpage_ref_main_intro_location = ( !isset($provider_fpage_ref_main_intro_location) || empty($provider_fpage_ref_main_intro_location) ) ? $provider_fpage_ref_main_intro_general : ''; // Reference to the main provider archive, body text
					$provider_fpage_ref_main_link_location = ( !isset($provider_fpage_ref_main_link_location) || empty($provider_fpage_ref_main_link_location) ) ? $provider_fpage_ref_main_link_general : ''; // Reference to the main provider archive, link text
					$provider_fpage_ref_top_title_location = ( !isset($provider_fpage_ref_top_title_location) || empty($provider_fpage_ref_top_title_location) ) ? $provider_fpage_ref_top_title_general : ''; // Reference to a Top-Level Location's Fake Subpage for Providers, title
					$provider_fpage_ref_top_intro_location = ( !isset($provider_fpage_ref_top_intro_location) || empty($provider_fpage_ref_top_intro_location) ) ? $provider_fpage_ref_top_intro_general : ''; // Reference to a Top-Level Location's Fake Subpage for Providers, body text
					$provider_fpage_ref_top_link_location = ( !isset($provider_fpage_ref_top_link_location) || empty($provider_fpage_ref_top_link_location) ) ? $provider_fpage_ref_top_link_general : ''; // Reference to a Top-Level Location's Fake Subpage for Providers, link text

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$provider_fpage_title_location = $provider_fpage_title_location ? uamswp_fad_fpage_text_replace($provider_fpage_title_location, $page_titles) : ''; // Title
					$provider_fpage_intro_location = $provider_fpage_intro_location ? uamswp_fad_fpage_text_replace($provider_fpage_intro_location, $page_titles) : ''; // Intro text
					$provider_fpage_ref_main_title_location = $provider_fpage_ref_main_title_location ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_title_location, $page_titles) : ''; // Reference to the main provider archive, title
					$provider_fpage_ref_main_intro_location = $provider_fpage_ref_main_intro_location ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_intro_location, $page_titles) : ''; // Reference to the main provider archive, body text
					$provider_fpage_ref_main_link_location = $provider_fpage_ref_main_link_location ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_link_location, $page_titles) : ''; // Reference to the main provider archive, link text
					$provider_fpage_ref_top_title_location = $provider_fpage_ref_top_title_location ? uamswp_fad_fpage_text_replace($provider_fpage_ref_top_title_location, $page_titles) : ''; // Reference to a Top-Level Location's Fake Subpage for Providers, title
					$provider_fpage_ref_top_intro_location = $provider_fpage_ref_top_intro_location ? uamswp_fad_fpage_text_replace($provider_fpage_ref_top_intro_location, $page_titles) : ''; // Reference to a Top-Level Location's Fake Subpage for Providers, body text
					$provider_fpage_ref_top_link_location = $provider_fpage_ref_top_link_location ? uamswp_fad_fpage_text_replace($provider_fpage_ref_top_link_location, $page_titles) : ''; // Reference to a Top-Level Location's Fake Subpage for Providers, link text

			// Descendant Locations Fake Subpage (or Section)

				$location_descendant_fpage_title_location = get_field('location_descendant_fpage_title_location', 'option'); // Title of a Fake Subpage (or Section) for Descendant Locations in Location Subsection
				$location_descendant_fpage_intro_location = get_field('location_descendant_fpage_intro_location', 'option'); // Intro Text of a Fake Subpage (or Section) for Descendant Locations in a Location Subsection (or Profile)
				$location_descendant_fpage_ref_main_title_location = get_field('location_descendant_fpage_ref_main_title_location', 'option'); // Reference to the main location archive, title
				$location_descendant_fpage_ref_main_intro_location = get_field('location_descendant_fpage_ref_main_intro_location', 'option'); // Reference to the main location archive, body text
				$location_descendant_fpage_ref_main_link_location = get_field('location_descendant_fpage_ref_main_link_location', 'option'); // Reference to the main location archive, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					$location_descendant_fpage_title_location = ( !isset($location_descendant_fpage_title_location) || empty($location_descendant_fpage_title_location) ) ? $location_descendant_fpage_title_location : '[Descendant Locations] Within [the Location Title]'; // Title
					$location_descendant_fpage_intro_location = ( !isset($location_descendant_fpage_intro_location) || empty($location_descendant_fpage_intro_location) ) ? $location_descendant_fpage_intro_location : ''; // Intro text
					$location_descendant_fpage_ref_main_title_location = ( !isset($location_descendant_fpage_ref_main_title_location) || empty($location_descendant_fpage_ref_main_title_location) ) ? $location_descendant_fpage_ref_main_title_location : ''; // Reference to the main location archive, title
					$location_descendant_fpage_ref_main_intro_location = ( !isset($location_descendant_fpage_ref_main_intro_location) || empty($location_descendant_fpage_ref_main_intro_location) ) ? $location_descendant_fpage_ref_main_intro_location : ''; // Reference to the main location archive, body text
					$location_descendant_fpage_ref_main_link_location = ( !isset($location_descendant_fpage_ref_main_link_location) || empty($location_descendant_fpage_ref_main_link_location) ) ? $location_descendant_fpage_ref_main_link_location : ''; // Reference to the main location archive, link text

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if (
						!isset($location_descendant_fpage_title_location) || empty($location_descendant_fpage_title_location)
						||
						!isset($location_descendant_fpage_intro_location) || empty($location_descendant_fpage_intro_location)
						||
						!isset($location_descendant_fpage_ref_main_title_location) || empty($location_descendant_fpage_ref_main_title_location)
						||
						!isset($location_descendant_fpage_ref_main_intro_location) || empty($location_descendant_fpage_ref_main_intro_location)
						||
						!isset($location_descendant_fpage_ref_main_link_location) || empty($location_descendant_fpage_ref_main_link_location)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($location_descendant_fpage_title_general) || empty($location_descendant_fpage_title_general)
							||
							!isset($location_descendant_fpage_intro_general) || empty($location_descendant_fpage_intro_general)
							||
							!isset($location_descendant_fpage_ref_main_title_general) || empty($location_descendant_fpage_ref_main_title_general)
							||
							!isset($location_descendant_fpage_ref_main_intro_general) || empty($location_descendant_fpage_ref_main_intro_general)
							||
							!isset($location_descendant_fpage_ref_main_link_general) || empty($location_descendant_fpage_ref_main_link_general)
							) {
							$fpage_text_location_general_vars = isset($fpage_text_location_general_vars) ? $fpage_text_location_general_vars : uamswp_fad_fpage_text_location_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$location_descendant_fpage_title_general = $fpage_text_location_general_vars['location_descendant_fpage_title_general']; // string
								$location_descendant_fpage_intro_general = $fpage_text_location_general_vars['location_descendant_fpage_intro_general']; // string
								$location_descendant_fpage_ref_main_title_general = $fpage_text_location_general_vars['location_descendant_fpage_ref_main_title_general']; // string
								$location_descendant_fpage_ref_main_intro_general = $fpage_text_location_general_vars['location_descendant_fpage_ref_main_intro_general']; // string
								$location_descendant_fpage_ref_main_link_general = $fpage_text_location_general_vars['location_descendant_fpage_ref_main_link_general']; // string
						}
					}
					if ( !isset($location_descendant_fpage_title_location) || empty($location_descendant_fpage_title_location) ) {
						$location_descendant_fpage_title_location = $location_descendant_fpage_title_general; // Title
					}
					if ( !isset($location_descendant_fpage_intro_location) || empty($location_descendant_fpage_intro_location) ) {
						$location_descendant_fpage_intro_location = $location_descendant_fpage_intro_general; // Intro text
					}
					if ( !isset($location_descendant_fpage_ref_main_title_location) || empty($location_descendant_fpage_ref_main_title_location) ) {
						$location_descendant_fpage_ref_main_title_location = $location_descendant_fpage_ref_main_title_general; // Reference to the main location archive, title
					}
					if ( !isset($location_descendant_fpage_ref_main_intro_location) || empty($location_descendant_fpage_ref_main_intro_location) ) {
						$location_descendant_fpage_ref_main_intro_location = $location_descendant_fpage_ref_main_intro_general; // Reference to the main location archive, body text
					}
					if ( !isset($location_descendant_fpage_ref_main_link_location) || empty($location_descendant_fpage_ref_main_link_location) ) {
						$location_descendant_fpage_ref_main_link_location = $location_descendant_fpage_ref_main_link_general; // Reference to the main location archive, link text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$location_descendant_fpage_title_location = $location_descendant_fpage_title_location ? uamswp_fad_fpage_text_replace($location_descendant_fpage_title_location, $page_titles) : ''; // Title
					$location_descendant_fpage_intro_location = $location_descendant_fpage_intro_location ? uamswp_fad_fpage_text_replace($location_descendant_fpage_intro_location, $page_titles) : ''; // Intro text
					$location_descendant_fpage_ref_main_title_location = $location_descendant_fpage_ref_main_title_location ? uamswp_fad_fpage_text_replace($location_descendant_fpage_ref_main_title_location, $page_titles) : ''; // Reference to the main location archive, title
					$location_descendant_fpage_ref_main_intro_location = $location_descendant_fpage_ref_main_intro_location ? uamswp_fad_fpage_text_replace($location_descendant_fpage_ref_main_intro_location, $page_titles) : ''; // Reference to the main location archive, body text
					$location_descendant_fpage_ref_main_link_location = $location_descendant_fpage_ref_main_link_location ? uamswp_fad_fpage_text_replace($location_descendant_fpage_ref_main_link_location, $page_titles) : ''; // Reference to the main location archive, link text

			// Areas of Expertise Fake Subpage (or Section)

				$expertise_fpage_title_location = get_field('expertise_fpage_title_location', 'option'); // Title of a Fake Subpage (or Section) for Areas of Expertise in a Location Subsection (or Profile)
				$expertise_fpage_intro_location = get_field('expertise_fpage_intro_location', 'option'); // Intro Text of a Fake Subpage (or Section) for Areas of Expertise in a Location Subsection (or Profile)
				$expertise_fpage_ref_main_title_location = get_field('expertise_fpage_ref_main_title_location', 'option'); // Reference to the main area of expertise archive, title
				$expertise_fpage_ref_main_intro_location = get_field('expertise_fpage_ref_main_intro_location', 'option'); // Reference to the main area of expertise archive, body text
				$expertise_fpage_ref_main_link_location = get_field('expertise_fpage_ref_main_link_location', 'option'); // Reference to the main area of expertise archive, link text
				$expertise_fpage_ref_top_title_location = get_field('expertise_fpage_ref_top_title_location', 'option'); // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, title
				$expertise_fpage_ref_top_intro_location = get_field('expertise_fpage_ref_top_intro_location', 'option'); // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, body text
				$expertise_fpage_ref_top_link_location = get_field('expertise_fpage_ref_top_link_location', 'option'); // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($expertise_fpage_title_location) || empty($expertise_fpage_title_location) ) {
						$expertise_fpage_title_location = '[Areas of Expertise] Represented at [the Location Title]'; // Title
					}
					if ( !isset($expertise_fpage_intro_location) || empty($expertise_fpage_intro_location) ) {
						$expertise_fpage_intro_location = ''; // Intro text
					}
					if ( !isset($expertise_fpage_ref_main_title_location) || empty($expertise_fpage_ref_main_title_location) ) {
						$expertise_fpage_ref_main_title_location = ''; // Reference to the main area of expertise archive, title
					}
					if ( !isset($expertise_fpage_ref_main_intro_location) || empty($expertise_fpage_ref_main_intro_location) ) {
						$expertise_fpage_ref_main_intro_location = ''; // Reference to the main area of expertise archive, body text
					}
					if ( !isset($expertise_fpage_ref_main_link_location) || empty($expertise_fpage_ref_main_link_location) ) {
						$expertise_fpage_ref_main_link_location = ''; // Reference to the main area of expertise archive, link text
					}
					if ( !isset($expertise_fpage_ref_top_title_location) || empty($expertise_fpage_ref_top_title_location) ) {
						$expertise_fpage_ref_top_title_location = ''; // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, title
					}
					if ( !isset($expertise_fpage_ref_top_intro_location) || empty($expertise_fpage_ref_top_intro_location) ) {
						$expertise_fpage_ref_top_intro_location = ''; // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, body text
					}
					if ( !isset($expertise_fpage_ref_top_link_location) || empty($expertise_fpage_ref_top_link_location) ) {
						$expertise_fpage_ref_top_link_location = ''; // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, link text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if (
						!isset($expertise_fpage_title_location) || empty($expertise_fpage_title_location)
						||
						!isset($expertise_fpage_intro_location) || empty($expertise_fpage_intro_location)
						||
						!isset($expertise_fpage_ref_main_title_location) || empty($expertise_fpage_ref_main_title_location)
						||
						!isset($expertise_fpage_ref_main_intro_location) || empty($expertise_fpage_ref_main_intro_location)
						||
						!isset($expertise_fpage_ref_main_link_location) || empty($expertise_fpage_ref_main_link_location)
						||
						!isset($expertise_fpage_ref_top_title_location) || empty($expertise_fpage_ref_top_title_location)
						||
						!isset($expertise_fpage_ref_top_intro_location) || empty($expertise_fpage_ref_top_intro_location)
						||
						!isset($expertise_fpage_ref_top_link_location) || empty($expertise_fpage_ref_top_link_location)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($expertise_fpage_title_general) || empty($expertise_fpage_title_general)
							||
							!isset($expertise_fpage_intro_general) || empty($expertise_fpage_intro_general)
							||
							!isset($expertise_fpage_ref_main_title_general) || empty($expertise_fpage_ref_main_title_general)
							||
							!isset($expertise_fpage_ref_main_intro_general) || empty($expertise_fpage_ref_main_intro_general)
							||
							!isset($expertise_fpage_ref_main_link_general) || empty($expertise_fpage_ref_main_link_general)
							||
							!isset($expertise_fpage_ref_top_title_general) || empty($expertise_fpage_ref_top_title_general)
							||
							!isset($expertise_fpage_ref_top_intro_general) || empty($expertise_fpage_ref_top_intro_general)
							||
							!isset($expertise_fpage_ref_top_link_general) || empty($expertise_fpage_ref_top_link_general)
							) {
							$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$expertise_fpage_title_general = $fpage_text_expertise_general_vars['expertise_fpage_title_general']; // string
								$expertise_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_intro_general']; // string
								$expertise_fpage_ref_main_title_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_title_general']; // string
								$expertise_fpage_ref_main_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_intro_general']; // string
								$expertise_fpage_ref_main_link_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_link_general']; // string
								$expertise_fpage_ref_top_title_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_top_title_general']; // string
								$expertise_fpage_ref_top_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_top_intro_general']; // string
								$expertise_fpage_ref_top_link_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_top_link_general']; // string
						}
					}
					if ( !isset($expertise_fpage_title_location) || empty($expertise_fpage_title_location) ) {
						$expertise_fpage_title_location = $expertise_fpage_title_general; // Title
					}
					if ( !isset($expertise_fpage_intro_location) || empty($expertise_fpage_intro_location) ) {
						$expertise_fpage_intro_location = $expertise_fpage_intro_general; // Intro text
					}
					if ( !isset($expertise_fpage_ref_main_title_location) || empty($expertise_fpage_ref_main_title_location) ) {
						$expertise_fpage_ref_main_title_location = $expertise_fpage_ref_main_title_general; // Reference to the main area of expertise archive, title
					}
					if ( !isset($expertise_fpage_ref_main_intro_location) || empty($expertise_fpage_ref_main_intro_location) ) {
						$expertise_fpage_ref_main_intro_location = $expertise_fpage_ref_main_intro_general; // Reference to the main area of expertise archive, body text
					}
					if ( !isset($expertise_fpage_ref_main_link_location) || empty($expertise_fpage_ref_main_link_location) ) {
						$expertise_fpage_ref_main_link_location = $expertise_fpage_ref_main_link_general; // Reference to the main area of expertise archive, link text
					}
					if ( !isset($expertise_fpage_ref_top_title_location) || empty($expertise_fpage_ref_top_title_location) ) {
						$expertise_fpage_ref_top_title_location = $expertise_fpage_ref_top_title_general; // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, title
					}
					if ( !isset($expertise_fpage_ref_top_intro_location) || empty($expertise_fpage_ref_top_intro_location) ) {
						$expertise_fpage_ref_top_intro_location = $expertise_fpage_ref_top_intro_general; // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, body text
					}
					if ( !isset($expertise_fpage_ref_top_link_location) || empty($expertise_fpage_ref_top_link_location) ) {
						$expertise_fpage_ref_top_link_location = $expertise_fpage_ref_top_link_general; // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, link text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$expertise_fpage_title_location = $expertise_fpage_title_location ? uamswp_fad_fpage_text_replace($expertise_fpage_title_location, $page_titles) : ''; // Title
					$expertise_fpage_intro_location = $expertise_fpage_intro_location ? uamswp_fad_fpage_text_replace($expertise_fpage_intro_location, $page_titles) : ''; // Intro text
					$expertise_fpage_ref_main_title_location = $expertise_fpage_ref_main_title_location ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_title_location, $page_titles) : ''; // Reference to the main area of expertise archive, title
					$expertise_fpage_ref_main_intro_location = $expertise_fpage_ref_main_intro_location ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_intro_location, $page_titles) : ''; // Reference to the main area of expertise archive, body text
					$expertise_fpage_ref_main_link_location = $expertise_fpage_ref_main_link_location ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_link_location, $page_titles) : ''; // Reference to the main area of expertise archive, link text
					$expertise_fpage_ref_top_title_location = $expertise_fpage_ref_top_title_location ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_top_title_location, $page_titles) : ''; // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, title
					$expertise_fpage_ref_top_intro_location = $expertise_fpage_ref_top_intro_location ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_top_intro_location, $page_titles) : ''; // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, body text
					$expertise_fpage_ref_top_link_location = $expertise_fpage_ref_top_link_location ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_top_link_location, $page_titles) : ''; // Reference to a Top-Level Location's Fake Subpage for Areas of Expertise, link text

			// Clinical Resources Fake Subpage (or Section)

				$clinical_resource_fpage_title_location = get_field('clinical_resource_fpage_title_location', 'option'); // Title of Section for Clinical Resources on Clinical Resource Profile
				$clinical_resource_fpage_intro_location = get_field('clinical_resource_fpage_intro_location', 'option'); // Intro Text of Section for Clinical Resources on Clinical Resource Profile
				$clinical_resource_fpage_ref_main_title_location = get_field('clinical_resource_fpage_ref_main_title_location', 'option'); // Reference to the main clinical resource archive, title
				$clinical_resource_fpage_ref_main_intro_location = get_field('clinical_resource_fpage_ref_main_intro_location', 'option'); // Reference to the main clinical resource archive, body text
				$clinical_resource_fpage_ref_main_link_location = get_field('clinical_resource_fpage_ref_main_link_location', 'option'); // Reference to the main clinical resource archive, link text
				$clinical_resource_fpage_ref_top_title_location = get_field('clinical_resource_fpage_ref_top_title_location', 'option'); // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, title
				$clinical_resource_fpage_ref_top_intro_location = get_field('clinical_resource_fpage_ref_top_intro_location', 'option'); // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, body text
				$clinical_resource_fpage_ref_top_link_location = get_field('clinical_resource_fpage_ref_top_link_location', 'option'); // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, link text
				$clinical_resource_fpage_more_text_location = get_field('clinical_resource_fpage_more_text_location', 'option'); // "More" Intro Text of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)
				$clinical_resource_fpage_more_link_text_location = get_field('clinical_resource_fpage_more_link_text_location', 'option'); // "More" Link Text of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)
				$clinical_resource_fpage_more_link_descr_location = get_field('clinical_resource_fpage_more_link_descr_location', 'option'); // "More" Link Description of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($clinical_resource_fpage_title_location) || empty($clinical_resource_fpage_title_location) ) {
						$clinical_resource_fpage_title_location = '[Clinical Resources] Related to [the Location Title]'; // Title
					}
					if ( !isset($clinical_resource_fpage_intro_location) || empty($clinical_resource_fpage_intro_location) ) {
						$clinical_resource_fpage_intro_location = ''; // Intro text
					}
					if ( !isset($clinical_resource_fpage_ref_main_title_location) || empty($clinical_resource_fpage_ref_main_title_location) ) {
						$clinical_resource_fpage_ref_main_title_location = ''; // Reference to the main clinical resource archive, title
					}
					if ( !isset($clinical_resource_fpage_ref_main_intro_location) || empty($clinical_resource_fpage_ref_main_intro_location) ) {
						$clinical_resource_fpage_ref_main_intro_location = ''; // Reference to the main clinical resource archive, body text
					}
					if ( !isset($clinical_resource_fpage_ref_main_link_location) || empty($clinical_resource_fpage_ref_main_link_location) ) {
						$clinical_resource_fpage_ref_main_link_location = ''; // Reference to the main clinical resource archive, link text
					}
					if ( !isset($clinical_resource_fpage_ref_top_title_location) || empty($clinical_resource_fpage_ref_top_title_location) ) {
						$clinical_resource_fpage_ref_top_title_location = ''; // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, title
					}
					if ( !isset($clinical_resource_fpage_ref_top_intro_location) || empty($clinical_resource_fpage_ref_top_intro_location) ) {
						$clinical_resource_fpage_ref_top_intro_location = ''; // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, body text
					}
					if ( !isset($clinical_resource_fpage_ref_top_link_location) || empty($clinical_resource_fpage_ref_top_link_location) ) {
						$clinical_resource_fpage_ref_top_link_location = ''; // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, link text
					}
					if ( !isset($clinical_resource_fpage_more_text_location) || empty($clinical_resource_fpage_more_text_location) ) {
						$clinical_resource_fpage_more_text_location = 'Want to find more [clinical resources] related to [the Location Title]?'; // "More" intro text
					}
					if ( !isset($clinical_resource_fpage_more_link_text_location) || empty($clinical_resource_fpage_more_link_text_location) ) {
						$clinical_resource_fpage_more_link_text_location = 'View the Full List'; // "More" link text
					}
					if ( !isset($clinical_resource_fpage_more_link_descr_location) || empty($clinical_resource_fpage_more_link_descr_location) ) {
						$clinical_resource_fpage_more_link_descr_location = 'View the full list of [clinical resources] related to [the Location Title]'; // "More" link description
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if (
						!isset($clinical_resource_fpage_title_location) || empty($clinical_resource_fpage_title_location)
						||
						!isset($clinical_resource_fpage_intro_location) || empty($clinical_resource_fpage_intro_location)
						||
						!isset($clinical_resource_fpage_ref_main_title_location) || empty($clinical_resource_fpage_ref_main_title_location)
						||
						!isset($clinical_resource_fpage_ref_main_intro_location) || empty($clinical_resource_fpage_ref_main_intro_location)
						||
						!isset($clinical_resource_fpage_ref_main_link_location) || empty($clinical_resource_fpage_ref_main_link_location)
						||
						!isset($clinical_resource_fpage_ref_top_title_location) || empty($clinical_resource_fpage_ref_top_title_location)
						||
						!isset($clinical_resource_fpage_ref_top_intro_location) || empty($clinical_resource_fpage_ref_top_intro_location)
						||
						!isset($clinical_resource_fpage_ref_top_link_location) || empty($clinical_resource_fpage_ref_top_link_location)
						||
						!isset($clinical_resource_fpage_more_text_location) || empty($clinical_resource_fpage_more_text_location)
						||
						!isset($clinical_resource_fpage_more_link_text_location) || empty($clinical_resource_fpage_more_link_text_location)
						||
						!isset($clinical_resource_fpage_more_link_descr_location) || empty($clinical_resource_fpage_more_link_descr_location)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($clinical_resource_fpage_title_general) || empty($clinical_resource_fpage_title_general)
							||
							!isset($clinical_resource_fpage_intro_general) || empty($clinical_resource_fpage_intro_general)
							||
							!isset($clinical_resource_fpage_ref_main_title_general) || empty($clinical_resource_fpage_ref_main_title_general)
							||
							!isset($clinical_resource_fpage_ref_main_intro_general) || empty($clinical_resource_fpage_ref_main_intro_general)
							||
							!isset($clinical_resource_fpage_ref_main_link_general) || empty($clinical_resource_fpage_ref_main_link_general)
							||
							!isset($clinical_resource_fpage_ref_top_title_general) || empty($clinical_resource_fpage_ref_top_title_general)
							||
							!isset($clinical_resource_fpage_ref_top_intro_general) || empty($clinical_resource_fpage_ref_top_intro_general)
							||
							!isset($clinical_resource_fpage_ref_top_link_general) || empty($clinical_resource_fpage_ref_top_link_general)
							||
							!isset($clinical_resource_fpage_more_text_general) || empty($clinical_resource_fpage_more_text_general)
							||
							!isset($clinical_resource_fpage_more_link_text_general) || empty($clinical_resource_fpage_more_link_text_general)
							||
							!isset($clinical_resource_fpage_more_link_descr_general) || empty($clinical_resource_fpage_more_link_descr_general)
							) {
							$fpage_text_clinical_resource_general_vars = isset($fpage_text_clinical_resource_general_vars) ? $fpage_text_clinical_resource_general_vars : uamswp_fad_fpage_text_clinical_resource_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$clinical_resource_fpage_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_title_general']; // string
								$clinical_resource_fpage_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_intro_general']; // string
								$clinical_resource_fpage_ref_main_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_title_general']; // string
								$clinical_resource_fpage_ref_main_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_intro_general']; // string
								$clinical_resource_fpage_ref_main_link_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_link_general']; // string
								$clinical_resource_fpage_ref_top_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_top_title_general']; // string
								$clinical_resource_fpage_ref_top_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_top_intro_general']; // string
								$clinical_resource_fpage_ref_top_link_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_top_link_general']; // string
								$clinical_resource_fpage_more_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_text_general']; // string
								$clinical_resource_fpage_more_link_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_text_general']; // string
								$clinical_resource_fpage_more_link_descr_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_descr_general']; // string
						}
					}
					if ( !isset($clinical_resource_fpage_title_location) || empty($clinical_resource_fpage_title_location) ) {
						$clinical_resource_fpage_title_location = $clinical_resource_fpage_title_general; // Title
					}
					if ( !isset($clinical_resource_fpage_intro_location) || empty($clinical_resource_fpage_intro_location) ) {
						$clinical_resource_fpage_intro_location = $clinical_resource_fpage_intro_general; // Intro text
					}
					if ( !isset($clinical_resource_fpage_ref_main_title_location) || empty($clinical_resource_fpage_ref_main_title_location) ) {
						$clinical_resource_fpage_ref_main_title_location = $clinical_resource_fpage_ref_main_title_general; // Reference to the main clinical resource archive, title
					}
					if ( !isset($clinical_resource_fpage_ref_main_intro_location) || empty($clinical_resource_fpage_ref_main_intro_location) ) {
						$clinical_resource_fpage_ref_main_intro_location = $clinical_resource_fpage_ref_main_intro_general; // Reference to the main clinical resource archive, body text
					}
					if ( !isset($clinical_resource_fpage_ref_main_link_location) || empty($clinical_resource_fpage_ref_main_link_location) ) {
						$clinical_resource_fpage_ref_main_link_location = $clinical_resource_fpage_ref_main_link_general; // Reference to the main clinical resource archive, link text
					}
					if ( !isset($clinical_resource_fpage_ref_top_title_location) || empty($clinical_resource_fpage_ref_top_title_location) ) {
						$clinical_resource_fpage_ref_top_title_location = $clinical_resource_fpage_ref_top_title_general; // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, title
					}
					if ( !isset($clinical_resource_fpage_ref_top_intro_location) || empty($clinical_resource_fpage_ref_top_intro_location) ) {
						$clinical_resource_fpage_ref_top_intro_location = $clinical_resource_fpage_ref_top_intro_general; // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, body text
					}
					if ( !isset($clinical_resource_fpage_ref_top_link_location) || empty($clinical_resource_fpage_ref_top_link_location) ) {
						$clinical_resource_fpage_ref_top_link_location = $clinical_resource_fpage_ref_top_link_general; // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, link text
					}
					if ( !isset($clinical_resource_fpage_more_text_location) || empty($clinical_resource_fpage_more_text_location) ) {
						$clinical_resource_fpage_more_text_location = $clinical_resource_fpage_more_text_general; // "More" intro text
					}
					if ( !isset($clinical_resource_fpage_more_link_text_location) || empty($clinical_resource_fpage_more_link_text_location) ) {
						$clinical_resource_fpage_more_link_text_location = $clinical_resource_fpage_more_link_text_general; // "More" link text
					}
					if ( !isset($clinical_resource_fpage_more_link_descr_location) || empty($clinical_resource_fpage_more_link_descr_location) ) {
						$clinical_resource_fpage_more_link_descr_location = $clinical_resource_fpage_more_link_descr_general; // "More" link description
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$clinical_resource_fpage_title_location = $clinical_resource_fpage_title_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_title_location, $page_titles) : ''; // Title
					$clinical_resource_fpage_intro_location = $clinical_resource_fpage_intro_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_intro_location, $page_titles) : ''; // Intro text
					$clinical_resource_fpage_ref_main_title_location = $clinical_resource_fpage_ref_main_title_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_title_location, $page_titles) : ''; // Reference to the main clinical resource archive, title
					$clinical_resource_fpage_ref_main_intro_location = $clinical_resource_fpage_ref_main_intro_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_intro_location, $page_titles) : ''; // Reference to the main clinical resource archive, body text
					$clinical_resource_fpage_ref_main_link_location = $clinical_resource_fpage_ref_main_link_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_link_location, $page_titles) : ''; // Reference to the main clinical resource archive, link text
					$clinical_resource_fpage_ref_top_title_location = $clinical_resource_fpage_ref_top_title_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_top_title_location, $page_titles) : ''; // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, title
					$clinical_resource_fpage_ref_top_intro_location = $clinical_resource_fpage_ref_top_intro_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_top_intro_location, $page_titles) : ''; // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, body text
					$clinical_resource_fpage_ref_top_link_location = $clinical_resource_fpage_ref_top_link_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_top_link_location, $page_titles) : ''; // Reference to a Top-Level Location's Fake Subpage for Clinical Resources, link text
					$clinical_resource_fpage_more_text_location = $clinical_resource_fpage_more_text_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_text_location, $page_titles) : ''; // "More" intro text
					$clinical_resource_fpage_more_link_text_location = $clinical_resource_fpage_more_link_text_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_link_text_location, $page_titles) : ''; // "More" link text
					$clinical_resource_fpage_more_link_descr_location = $clinical_resource_fpage_more_link_descr_location ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_link_descr_location, $page_titles) : ''; // "More" link description

			// Conditions Fake Subpage (or Section)

				$condition_fpage_title_location = get_field('conditions_fpage_title_location', 'option'); // Title of a Fake Subpage (or Section) for Conditions in a Location Subsection (or Profile)
				$condition_fpage_intro_location = get_field('conditions_fpage_intro_location', 'option'); // Intro Text of a Fake Subpage (or Section) for Conditions in a Location Subsection (or Profile)

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($condition_fpage_title_location) || empty($condition_fpage_title_location) ) {
						$condition_fpage_title_location = '[Conditions] Diagnosed or Treated at [the Location Title]'; // Title
					}
					if ( !isset($condition_fpage_intro_location) || empty($condition_fpage_intro_location) ) {
						$condition_fpage_intro_location = ''; // Intro text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if ( !isset($condition_fpage_title_location) || empty($condition_fpage_title_location) ) {
						if ( !isset($condition_fpage_title_general) || empty($condition_fpage_title_general) ) {
							$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_fpage_title_general = $fpage_text_condition_general_vars['condition_fpage_title_general']; // string
						}
						$condition_fpage_title_location = $condition_fpage_title_general; // Title
					}
					if ( !isset($condition_fpage_intro_location) || empty($condition_fpage_intro_location) ) {
						if ( !isset($condition_fpage_intro_general) || empty($condition_fpage_intro_general) ) {
							$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_fpage_intro_general = $fpage_text_condition_general_vars['condition_fpage_intro_general']; // string
						}
						$condition_fpage_intro_location = $condition_fpage_intro_general; // Intro text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$condition_fpage_title_location = $condition_fpage_title_location ? uamswp_fad_fpage_text_replace($condition_fpage_title_location, $page_titles) : ''; // Title
					$condition_fpage_intro_location = $condition_fpage_intro_location ? uamswp_fad_fpage_text_replace($condition_fpage_intro_location, $page_titles) : ''; // Intro text

			// Treatments Fake Subpage (or Section)

				$treatment_fpage_title_location = get_field('treatments_fpage_title_location', 'option'); // Title of a Fake Subpage (or Section) for Treatments in a Location Subsection (or Profile)
				$treatment_fpage_intro_location = get_field('treatments_fpage_intro_location', 'option'); // Intro Text of a Fake Subpage (or Section) for Treatments in a Location Subsection (or Profile)

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($treatment_fpage_title_location) || empty($treatment_fpage_title_location) ) {
						$treatment_fpage_title_location = '[Treatments] Performed or Prescribed at [the Location Title]'; // Title
					}
					if ( !isset($treatment_fpage_intro_location) || empty($treatment_fpage_intro_location) ) {
						$treatment_fpage_intro_location = ''; // Intro text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if ( !isset($treatment_fpage_title_location) || empty($treatment_fpage_title_location) ) {
						if ( !isset($treatment_fpage_title_general) || empty($treatment_fpage_title_general) ) {
							$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$treatment_fpage_title_general = $fpage_text_treatment_general_vars['treatment_fpage_title_general']; // string
						}
						$treatment_fpage_title_location = $treatment_fpage_title_general; // Title
					}
					if ( !isset($treatment_fpage_intro_location) || empty($treatment_fpage_intro_location) ) {
						if ( !isset($treatment_fpage_intro_general) || empty($treatment_fpage_intro_general) ) {
							$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$treatment_fpage_intro_general = $fpage_text_treatment_general_vars['treatment_fpage_intro_general']; // string
						}
						$treatment_fpage_intro_location = $treatment_fpage_intro_general; // Intro text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$treatment_fpage_title_location = $treatment_fpage_title_location ? uamswp_fad_fpage_text_replace($treatment_fpage_title_location, $page_titles) : ''; // Title
					$treatment_fpage_intro_location = $treatment_fpage_intro_location ? uamswp_fad_fpage_text_replace($treatment_fpage_intro_location, $page_titles) : ''; // Intro text


			// Combined Conditions and Treatments Fake Subpage (or Section)

				$condition_treatment_fpage_title_location = get_field('condition_treatment_fpage_title_location', 'option'); // Title of a Fake Subpage (or Section) for Conditions and Treatments Combined in a Location Subsection (or Profile)
				$condition_treatment_fpage_intro_location = get_field('condition_treatment_fpage_intro_location', 'option'); // Intro Text of a Fake Subpage (or Section) for Conditions and Treatments Combined in a Location Subsection (or Profile)

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($condition_treatment_fpage_title_location) || empty($condition_treatment_fpage_title_location) ) {
						$condition_treatment_fpage_title_location = '[Conditions and Treatments] Related to [the Location Title]'; // Title
					}
					if ( !isset($condition_treatment_fpage_intro_location) || empty($condition_treatment_fpage_intro_location) ) {
						$condition_treatment_fpage_intro_location = ''; // Intro text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if ( !isset($condition_treatment_fpage_title_location) || empty($condition_treatment_fpage_title_location) ) {
						if ( !isset($condition_treatment_fpage_title_general) || empty($condition_treatment_fpage_title_general) ) {
							$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_treatment_fpage_title_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_title_general']; // string
						}
						$condition_treatment_fpage_title_location = $condition_treatment_fpage_title_general; // Title
					}
					if ( !isset($condition_treatment_fpage_intro_location) || empty($condition_treatment_fpage_intro_location) ) {
						if ( !isset($condition_treatment_fpage_intro_general) || empty($condition_treatment_fpage_intro_general) ) {
							$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_treatment_fpage_intro_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_intro_general']; // string
						}
						$condition_treatment_fpage_intro_location = $condition_treatment_fpage_intro_general; // Intro text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$condition_treatment_fpage_title_location = $condition_treatment_fpage_title_location ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_title_location, $page_titles) : ''; // Title
					$condition_treatment_fpage_intro_location = $condition_treatment_fpage_intro_location ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_intro_location, $page_titles) : ''; // Intro text

			// Create and return an array to be used on the templates and template parts

				$fpage_text_location_vars = array(
					'provider_fpage_title_location'						=> $provider_fpage_title_location, // string
					'provider_fpage_intro_location'						=> $provider_fpage_intro_location, // string
					'provider_fpage_ref_main_title_location'			=> $provider_fpage_ref_main_title_location, // string
					'provider_fpage_ref_main_intro_location'			=> $provider_fpage_ref_main_intro_location, // string
					'provider_fpage_ref_main_link_location'				=> $provider_fpage_ref_main_link_location, // string
					'provider_fpage_ref_top_title_location'				=> $provider_fpage_ref_top_title_location, // string
					'provider_fpage_ref_top_intro_location'				=> $provider_fpage_ref_top_intro_location, // string
					'provider_fpage_ref_top_link_location'				=> $provider_fpage_ref_top_link_location, // string
					'location_descendant_fpage_title_location'			=> $location_descendant_fpage_title_location, // string
					'location_descendant_fpage_intro_location'			=> $location_descendant_fpage_intro_location, // string
					'location_descendant_fpage_ref_main_title_location'	=> $location_descendant_fpage_ref_main_title_location, // string
					'location_descendant_fpage_ref_main_intro_location'	=> $location_descendant_fpage_ref_main_intro_location, // string
					'location_descendant_fpage_ref_main_link_location'	=> $location_descendant_fpage_ref_main_link_location, // string
					'expertise_fpage_title_location'					=> $expertise_fpage_title_location, // string
					'expertise_fpage_intro_location'					=> $expertise_fpage_intro_location, // string
					'expertise_fpage_ref_main_title_location'			=> $expertise_fpage_ref_main_title_location, // string
					'expertise_fpage_ref_main_intro_location'			=> $expertise_fpage_ref_main_intro_location, // string
					'expertise_fpage_ref_main_link_location'			=> $expertise_fpage_ref_main_link_location, // string
					'expertise_fpage_ref_top_title_location'			=> $expertise_fpage_ref_top_title_location, // string
					'expertise_fpage_ref_top_intro_location'			=> $expertise_fpage_ref_top_intro_location, // string
					'expertise_fpage_ref_top_link_location'				=> $expertise_fpage_ref_top_link_location, // string
					'clinical_resource_fpage_title_location'			=> $clinical_resource_fpage_title_location, // string
					'clinical_resource_fpage_intro_location'			=> $clinical_resource_fpage_intro_location, // string
					'clinical_resource_fpage_ref_main_title_location'	=> $clinical_resource_fpage_ref_main_title_location, // string
					'clinical_resource_fpage_ref_main_intro_location'	=> $clinical_resource_fpage_ref_main_intro_location, // string
					'clinical_resource_fpage_ref_main_link_location'	=> $clinical_resource_fpage_ref_main_link_location, // string
					'clinical_resource_fpage_ref_top_title_location'	=> $clinical_resource_fpage_ref_top_title_location, // string
					'clinical_resource_fpage_ref_top_intro_location'	=> $clinical_resource_fpage_ref_top_intro_location, // string
					'clinical_resource_fpage_ref_top_link_location'		=> $clinical_resource_fpage_ref_top_link_location, // string
					'clinical_resource_fpage_more_text_location'		=> $clinical_resource_fpage_more_text_location, // string
					'clinical_resource_fpage_more_link_text_location'	=> $clinical_resource_fpage_more_link_text_location, // string
					'clinical_resource_fpage_more_link_descr_location'	=> $clinical_resource_fpage_more_link_descr_location, // string
					'condition_fpage_title_location'					=> $condition_fpage_title_location, // string
					'condition_fpage_intro_location'					=> $condition_fpage_intro_location, // string
					'treatment_fpage_title_location'					=> $treatment_fpage_title_location, // string
					'treatment_fpage_intro_location'					=> $treatment_fpage_intro_location, // string
					'condition_treatment_fpage_title_location'			=> $condition_treatment_fpage_title_location, // string
					'condition_treatment_fpage_intro_location'			=> $condition_treatment_fpage_intro_location // string
				);
				return $fpage_text_location_vars;

		}

		// Get field values for fake subpage text elements in an Area of Expertise subsection (or profile)
		function uamswp_fad_fpage_text_expertise(
			$page_id, // int
			$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
			$ontology_type // bool
		) {

			if ( !isset($page_title) ) {
				$page_title = isset($page_titles['page_title']) ? $page_titles['page_title'] : get_the_title();
			}

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

						// Substitute placeholder text for the relevant Find-a-Doc Settings value
						$expertise_short_desc = ( isset($expertise_short_desc) && !empty($expertise_short_desc) ) ? uamswp_fad_fpage_text_replace($expertise_short_desc, $page_titles) : $expertise_short_desc;

						// If the short description is not set or is empty, use the intro text as a fallback value
						$expertise_short_desc = ( isset($expertise_short_desc) && !empty($expertise_short_desc) ) ? $expertise_short_desc : $expertise_page_intro;

					}

			// Providers Fake Subpage (or Section)

				// Get the text values

					// Get the page-level input values

						// $provider_fpage_title_expertise = get_field('expertise_providers_fpage_title'); // Title
						$provider_fpage_intro_expertise = get_field('expertise_providers_fpage_intro'); // Intro text

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for a provider subpage/section in an area of expertise subsection

						if ( !isset($provider_fpage_title_expertise) || empty($provider_fpage_title_expertise) ) {
							$provider_fpage_title_expertise = get_field('provider_fpage_title_expertise', 'option'); // Title
						}
						if ( !isset($provider_fpage_intro_expertise) || empty($provider_fpage_intro_expertise) ) {
							$provider_fpage_intro_expertise = get_field('provider_fpage_intro_expertise', 'option'); // Intro text
						}
						$provider_fpage_ref_main_title_expertise = get_field('provider_fpage_ref_main_title_expertise', 'option'); // Reference to the main provider archive, title
						$provider_fpage_ref_main_intro_expertise = get_field('provider_fpage_ref_main_intro_expertise', 'option'); // Reference to the main provider archive, body text
						$provider_fpage_ref_main_link_expertise = get_field('provider_fpage_ref_main_link_expertise', 'option'); // Reference to the main provider archive, link text
						$provider_fpage_ref_top_title_expertise = get_field('provider_fpage_ref_top_title_expertise', 'option'); // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, title
						$provider_fpage_ref_top_intro_expertise = get_field('provider_fpage_ref_top_intro_expertise', 'option'); // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, body text
						$provider_fpage_ref_top_link_expertise = get_field('provider_fpage_ref_top_link_expertise', 'option'); // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, link text

					// If the variable is not set or is empty...
					// Set a hardcoded fallback value

						if ( !isset($provider_fpage_title_expertise) || empty($provider_fpage_title_expertise) ) {
							$provider_fpage_title_expertise = '[Area of Expertise Title] [Providers]'; // Title
						}
						if ( !isset($provider_fpage_intro_expertise) || empty($provider_fpage_intro_expertise) ) {
							$provider_fpage_intro_expertise = ''; // Intro text
						}
						if ( !isset($provider_fpage_ref_main_title_expertise) || empty($provider_fpage_ref_main_title_expertise) ) {
							$provider_fpage_ref_main_title_expertise = ''; // Reference to the main provider archive, title
						}
						if ( !isset($provider_fpage_ref_main_intro_expertise) || empty($provider_fpage_ref_main_intro_expertise) ) {
							$provider_fpage_ref_main_intro_expertise = ''; // Reference to the main provider archive, body text
						}
						if ( !isset($provider_fpage_ref_main_link_expertise) || empty($provider_fpage_ref_main_link_expertise) ) {
							$provider_fpage_ref_main_link_expertise = ''; // Reference to the main provider archive, link text
						}
						if ( !isset($provider_fpage_ref_top_title_expertise) || empty($provider_fpage_ref_top_title_expertise) ) {
							$provider_fpage_ref_top_title_expertise = $provider_fpage_title_expertise; // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, title
						}
						if ( !isset($provider_fpage_ref_top_intro_expertise) || empty($provider_fpage_ref_top_intro_expertise) ) {
							$provider_fpage_ref_top_intro_expertise = 'Discover our esteemed team of [providers] within the vast field of [Area of Expertise Title], delivering comprehensive care for UAMS Health patients. Explore our diverse roster of experts of various [descendant areas of expertise] within [Area of Expertise Title].'; // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, body text
						}
						if ( !isset($provider_fpage_ref_top_link_expertise) || empty($provider_fpage_ref_top_link_expertise) ) {
							$provider_fpage_ref_top_link_expertise = 'View [Providers]'; // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, link text
						}

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for general placement of a provider subpage/section

						if (
							!isset($provider_fpage_title_expertise) || empty($provider_fpage_title_expertise)
							||
							!isset($provider_fpage_intro_expertise) || empty($provider_fpage_intro_expertise)
							||
							!isset($provider_fpage_ref_main_title_expertise) || empty($provider_fpage_ref_main_title_expertise)
							||
							!isset($provider_fpage_ref_main_intro_expertise) || empty($provider_fpage_ref_main_intro_expertise)
							||
							!isset($provider_fpage_ref_main_link_expertise) || empty($provider_fpage_ref_main_link_expertise)
							||
							!isset($provider_fpage_ref_top_title_expertise) || empty($provider_fpage_ref_top_title_expertise)
							||
							!isset($provider_fpage_ref_top_intro_expertise) || empty($provider_fpage_ref_top_intro_expertise)
							||
							!isset($provider_fpage_ref_top_link_expertise) || empty($provider_fpage_ref_top_link_expertise)
							) {
							// If any of the variables are not set or are empty...

							// Check for the general placement variables.
							// If any aren't set or are empty, call the function and set the global variables.
							if (
								!isset($provider_fpage_title_general) || empty($provider_fpage_title_general)
								||
								!isset($provider_fpage_intro_general) || empty($provider_fpage_intro_general)
								||
								!isset($provider_fpage_ref_main_title_general) || empty($provider_fpage_ref_main_title_general)
								||
								!isset($provider_fpage_ref_main_intro_general) || empty($provider_fpage_ref_main_intro_general)
								||
								!isset($provider_fpage_ref_main_link_general) || empty($provider_fpage_ref_main_link_general)
								||
								!isset($provider_fpage_ref_top_title_general) || empty($provider_fpage_ref_top_title_general)
								||
								!isset($provider_fpage_ref_top_intro_general) || empty($provider_fpage_ref_top_intro_general)
								||
								!isset($provider_fpage_ref_top_link_general) || empty($provider_fpage_ref_top_link_general)
								) {
								$fpage_text_provider_general_vars = isset($fpage_text_provider_general_vars) ? $fpage_text_provider_general_vars : uamswp_fad_fpage_text_provider_general(
									$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
								);
									$provider_fpage_title_general = $fpage_text_provider_general_vars['provider_fpage_title_general']; // string
									$provider_fpage_intro_general = $fpage_text_provider_general_vars['provider_fpage_intro_general']; // string
									$provider_fpage_ref_main_title_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_title_general']; // string
									$provider_fpage_ref_main_intro_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_intro_general']; // string
									$provider_fpage_ref_main_link_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_link_general']; // string
									$provider_fpage_ref_top_title_general = $fpage_text_provider_general_vars['provider_fpage_ref_top_title_general']; // string
									$provider_fpage_ref_top_intro_general = $fpage_text_provider_general_vars['provider_fpage_ref_top_intro_general']; // string
									$provider_fpage_ref_top_link_general = $fpage_text_provider_general_vars['provider_fpage_ref_top_link_general']; // string
							}
						}
						if ( !isset($provider_fpage_title_expertise) || empty($provider_fpage_title_expertise) ) {
							$provider_fpage_title_expertise = $provider_fpage_title_general; // Title
						}
						if ( !isset($provider_fpage_intro_expertise) || empty($provider_fpage_intro_expertise) ) {
							$provider_fpage_intro_expertise = $provider_fpage_intro_general; // Intro text
						}
						if ( !isset($provider_fpage_ref_main_title_expertise) || empty($provider_fpage_ref_main_title_expertise) ) {
							$provider_fpage_ref_main_title_expertise = $provider_fpage_ref_main_title_general; // Reference to the main provider archive, title
						}
						if ( !isset($provider_fpage_ref_main_intro_expertise) || empty($provider_fpage_ref_main_intro_expertise) ) {
							$provider_fpage_ref_main_intro_expertise = $provider_fpage_ref_main_intro_general; // Reference to the main provider archive, body text
						}
						if ( !isset($provider_fpage_ref_main_link_expertise) || empty($provider_fpage_ref_main_link_expertise) ) {
							$provider_fpage_ref_main_link_expertise = $provider_fpage_ref_main_link_general; // Reference to the main provider archive, link text
						}
						if ( !isset($provider_fpage_ref_top_title_expertise) || empty($provider_fpage_ref_top_title_expertise) ) {
							$provider_fpage_ref_top_title_expertise = $provider_fpage_ref_top_title_general; // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, title
						}
						if ( !isset($provider_fpage_ref_top_intro_expertise) || empty($provider_fpage_ref_top_intro_expertise) ) {
							$provider_fpage_ref_top_intro_expertise = $provider_fpage_ref_top_intro_general; // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, body text
						}
						if ( !isset($provider_fpage_ref_top_link_expertise) || empty($provider_fpage_ref_top_link_expertise) ) {
							$provider_fpage_ref_top_link_expertise = $provider_fpage_ref_top_link_general; // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, link text
						}

					// Substitute placeholder text for relevant Find-a-Doc Settings value

						$provider_fpage_title_expertise = $provider_fpage_title_expertise ? uamswp_fad_fpage_text_replace($provider_fpage_title_expertise, $page_titles) : ''; // Title
						$provider_fpage_intro_expertise = $provider_fpage_intro_expertise ? uamswp_fad_fpage_text_replace($provider_fpage_intro_expertise, $page_titles) : ''; // Intro text
						$provider_fpage_ref_main_title_expertise = $provider_fpage_ref_main_title_expertise ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_title_expertise, $page_titles) : ''; // Reference to the main provider archive, title
						$provider_fpage_ref_main_intro_expertise = $provider_fpage_ref_main_intro_expertise ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_intro_expertise, $page_titles) : ''; // Reference to the main provider archive, body text
						$provider_fpage_ref_main_link_expertise = $provider_fpage_ref_main_link_expertise ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_link_expertise, $page_titles) : ''; // Reference to the main provider archive, link text
						$provider_fpage_ref_top_title_expertise = $provider_fpage_ref_top_title_expertise ? uamswp_fad_fpage_text_replace($provider_fpage_ref_top_title_expertise, $page_titles) : ''; // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, title
						$provider_fpage_ref_top_intro_expertise = $provider_fpage_ref_top_intro_expertise ? uamswp_fad_fpage_text_replace($provider_fpage_ref_top_intro_expertise, $page_titles) : ''; // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, body text
						$provider_fpage_ref_top_link_expertise = $provider_fpage_ref_top_link_expertise ? uamswp_fad_fpage_text_replace($provider_fpage_ref_top_link_expertise, $page_titles) : ''; // Reference to a Top-Level Area of Expertise's Fake Subpage for Providers, link text

				// Get value for meta description

					$provider_fpage_short_desc_query_expertise = get_field('expertise_providers_fpage_short_desc_query'); // If true, use intro text. If false, use specific short description.
					$provider_fpage_short_desc_query_expertise = isset($provider_fpage_short_desc_query_expertise) ? $provider_fpage_short_desc_query_expertise : true; // Define a value if the item has not been updated since 'expertise_providers_fpage_short_desc_query' was added
					if ( $provider_fpage_short_desc_query_expertise ) {
						$provider_fpage_short_desc_expertise = $provider_fpage_intro_expertise;
					} else {
						$provider_fpage_short_desc_expertise = get_field('expertise_providers_fpage_short_desc');
						$provider_fpage_short_desc_expertise = ( isset($provider_fpage_short_desc_expertise) && !empty($provider_fpage_short_desc_expertise) ) ? uamswp_fad_fpage_text_replace($provider_fpage_short_desc_expertise, $page_titles) : $provider_fpage_short_desc_expertise; // Substitute placeholder text for relevant Find-a-Doc Settings value
						$provider_fpage_short_desc_expertise = ( isset($provider_fpage_short_desc_expertise) && !empty($provider_fpage_short_desc_expertise) ) ? $provider_fpage_short_desc_expertise : $provider_fpage_intro_expertise; // If there is no value, use intro text as a fallback value
					}

			// Locations Fake Subpage (or Section)

				// Get the text values

					// Get the page-level input values

						// $location_fpage_title_expertise = get_field('expertise_locations_fpage_title'); // Title
						$location_fpage_intro_expertise = get_field('expertise_locations_fpage_intro'); // Intro text

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for a location subpage/section in an area of expertise subsection

						if ( !isset($location_fpage_title_expertise) || empty($location_fpage_title_expertise) ) {
							$location_fpage_title_expertise = get_field('location_fpage_title_expertise', 'option'); // Title
						}
						if ( !isset($location_fpage_intro_expertise) || empty($location_fpage_intro_expertise) ) {
							$location_fpage_intro_expertise = get_field('location_fpage_intro_expertise', 'option'); // Intro text
						}
						$location_fpage_ref_main_title_expertise = get_field('location_fpage_ref_main_title_expertise', 'option'); // Reference to the main location archive, title
						$location_fpage_ref_main_intro_expertise = get_field('location_fpage_ref_main_intro_expertise', 'option'); // Reference to the main location archive, body text
						$location_fpage_ref_main_link_expertise = get_field('location_fpage_ref_main_link_expertise', 'option'); // Reference to the main location archive, link text
						$location_fpage_ref_top_title_expertise = get_field('location_fpage_ref_top_title_expertise', 'option'); // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, title
						$location_fpage_ref_top_intro_expertise = get_field('location_fpage_ref_top_intro_expertise', 'option'); // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, body text
						$location_fpage_ref_top_link_expertise = get_field('location_fpage_ref_top_link_expertise', 'option'); // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, link text

					// If the variable is not set or is empty...
					// Set a hardcoded fallback value

						if ( !isset($location_fpage_title_expertise) || empty($location_fpage_title_expertise) ) {
							$location_fpage_title_expertise = '[Area of Expertise Title] [Locations]'; // Title
						}
						if ( !isset($location_fpage_intro_expertise) || empty($location_fpage_intro_expertise) ) {
							$location_fpage_intro_expertise = ''; // Intro text
						}
						if ( !isset($location_fpage_ref_main_title_expertise) || empty($location_fpage_ref_main_title_expertise) ) {
							$location_fpage_ref_main_title_expertise = ''; // Reference to the main location archive, title
						}
						if ( !isset($location_fpage_ref_main_intro_expertise) || empty($location_fpage_ref_main_intro_expertise) ) {
							$location_fpage_ref_main_intro_expertise = ''; // Reference to the main location archive, body text
						}
						if ( !isset($location_fpage_ref_main_link_expertise) || empty($location_fpage_ref_main_link_expertise) ) {
							$location_fpage_ref_main_link_expertise = ''; // Reference to the main location archive, link text
						}
						if ( !isset($location_fpage_ref_top_title_expertise) || empty($location_fpage_ref_top_title_expertise) ) {
							$location_fpage_ref_top_title_expertise = $location_fpage_title_expertise; // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, title
						}
						if ( !isset($location_fpage_ref_top_intro_expertise) || empty($location_fpage_ref_top_intro_expertise) ) {
							$location_fpage_ref_top_intro_expertise = 'Explore our extensive network of [locations] dedicated to providing exceptional care within the realm of [Area of Expertise Title]. Discover a range of specialized services and comprehensive care options across multiple [descendant areas of expertise].'; // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, body text
						}
						if ( !isset($location_fpage_ref_top_link_expertise) || empty($location_fpage_ref_top_link_expertise) ) {
							$location_fpage_ref_top_link_expertise = 'View [Locations]'; // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, link text
						}

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for general placement

						if (
							!isset($location_fpage_title_expertise) || empty($location_fpage_title_expertise)
							||
							!isset($location_fpage_intro_expertise) || empty($location_fpage_intro_expertise)
							||
							!isset($location_fpage_ref_main_title_expertise) || empty($location_fpage_ref_main_title_expertise)
							||
							!isset($location_fpage_ref_main_intro_expertise) || empty($location_fpage_ref_main_intro_expertise)
							||
							!isset($location_fpage_ref_main_link_expertise) || empty($location_fpage_ref_main_link_expertise)
							||
							!isset($location_fpage_ref_top_title_expertise) || empty($location_fpage_ref_top_title_expertise)
							||
							!isset($location_fpage_ref_top_intro_expertise) || empty($location_fpage_ref_top_intro_expertise)
							||
							!isset($location_fpage_ref_top_link_expertise) || empty($location_fpage_ref_top_link_expertise)
							) {
							// If any of the variables are not set or are empty...

							// Check for the general placement variables.
							// If any aren't set or are empty, call the function and set the global variables.
							if (
								!isset($location_fpage_title_general) || empty($location_fpage_title_general)
								||
								!isset($location_fpage_intro_general) || empty($location_fpage_intro_general)
								||
								!isset($location_fpage_ref_main_title_general) || empty($location_fpage_ref_main_title_general)
								||
								!isset($location_fpage_ref_main_intro_general) || empty($location_fpage_ref_main_intro_general)
								||
								!isset($location_fpage_ref_main_link_general) || empty($location_fpage_ref_main_link_general)
								||
								!isset($location_fpage_ref_top_title_general) || empty($location_fpage_ref_top_title_general)
								||
								!isset($location_fpage_ref_top_intro_general) || empty($location_fpage_ref_top_intro_general)
								||
								!isset($location_fpage_ref_top_link_general) || empty($location_fpage_ref_top_link_general)
								) {
								$fpage_text_location_general_vars = isset($fpage_text_location_general_vars) ? $fpage_text_location_general_vars : uamswp_fad_fpage_text_location_general(
									$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
								);
									$location_fpage_title_general = $fpage_text_location_general_vars['location_fpage_title_general']; // string
									$location_fpage_intro_general = $fpage_text_location_general_vars['location_fpage_intro_general']; // string
									$location_fpage_ref_main_title_general = $fpage_text_location_general_vars['location_fpage_ref_main_title_general']; // string
									$location_fpage_ref_main_intro_general = $fpage_text_location_general_vars['location_fpage_ref_main_intro_general']; // string
									$location_fpage_ref_main_link_general = $fpage_text_location_general_vars['location_fpage_ref_main_link_general']; // string
									$location_fpage_ref_top_title_general = $fpage_text_location_general_vars['location_fpage_ref_top_title_general']; // string
									$location_fpage_ref_top_intro_general = $fpage_text_location_general_vars['location_fpage_ref_top_intro_general']; // string
									$location_fpage_ref_top_link_general = $fpage_text_location_general_vars['location_fpage_ref_top_link_general']; // string
							}
						}
						if ( !isset($location_fpage_title_expertise) || empty($location_fpage_title_expertise) ) {
							$location_fpage_title_expertise = $location_fpage_title_general; // Title
						}
						if ( !isset($location_fpage_intro_expertise) || empty($location_fpage_intro_expertise) ) {
							$location_fpage_intro_expertise = $location_fpage_intro_general; // Intro text
						}
						if ( !isset($location_fpage_ref_main_title_expertise) || empty($location_fpage_ref_main_title_expertise) ) {
							$location_fpage_ref_main_title_expertise = $location_fpage_ref_main_title_general; // Reference to the main location archive, title
						}
						if ( !isset($location_fpage_ref_main_intro_expertise) || empty($location_fpage_ref_main_intro_expertise) ) {
							$location_fpage_ref_main_intro_expertise = $location_fpage_ref_main_intro_general; // Reference to the main location archive, body text
						}
						if ( !isset($location_fpage_ref_main_link_expertise) || empty($location_fpage_ref_main_link_expertise) ) {
							$location_fpage_ref_main_link_expertise = $location_fpage_ref_main_link_general; // Reference to the main location archive, link text
						}
						if ( !isset($location_fpage_ref_top_title_expertise) || empty($location_fpage_ref_top_title_expertise) ) {
							$location_fpage_ref_top_title_expertise = $location_fpage_ref_top_title_general; // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, title
						}
						if ( !isset($location_fpage_ref_top_intro_expertise) || empty($location_fpage_ref_top_intro_expertise) ) {
							$location_fpage_ref_top_intro_expertise = $location_fpage_ref_top_intro_general; // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, body text
						}
						if ( !isset($location_fpage_ref_top_link_expertise) || empty($location_fpage_ref_top_link_expertise) ) {
							$location_fpage_ref_top_link_expertise = $location_fpage_ref_top_link_general; // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, link text
						}

					// Substitute placeholder text for relevant Find-a-Doc Settings value

						$location_fpage_title_expertise = $location_fpage_title_expertise ? uamswp_fad_fpage_text_replace($location_fpage_title_expertise, $page_titles) : ''; // Title
						$location_fpage_intro_expertise = $location_fpage_intro_expertise ? uamswp_fad_fpage_text_replace($location_fpage_intro_expertise, $page_titles) : ''; // Intro text
						$location_fpage_ref_main_title_expertise = $location_fpage_ref_main_title_expertise ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_title_expertise, $page_titles) : ''; // Reference to the main location archive, title
						$location_fpage_ref_main_intro_expertise = $location_fpage_ref_main_intro_expertise ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_intro_expertise, $page_titles) : ''; // Reference to the main location archive, body text
						$location_fpage_ref_main_link_expertise = $location_fpage_ref_main_link_expertise ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_link_expertise, $page_titles) : ''; // Reference to the main location archive, link text
						$location_fpage_ref_top_title_expertise = $location_fpage_ref_top_title_expertise ? uamswp_fad_fpage_text_replace($location_fpage_ref_top_title_expertise, $page_titles) : ''; // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, title
						$location_fpage_ref_top_intro_expertise = $location_fpage_ref_top_intro_expertise ? uamswp_fad_fpage_text_replace($location_fpage_ref_top_intro_expertise, $page_titles) : ''; // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, body text
						$location_fpage_ref_top_link_expertise = $location_fpage_ref_top_link_expertise ? uamswp_fad_fpage_text_replace($location_fpage_ref_top_link_expertise, $page_titles) : ''; // Reference to a Top-Level Area of Expertise's Fake Subpage for Locations, link text

				// Get value for meta description

					$location_fpage_short_desc_query_expertise = get_field('expertise_locations_fpage_short_desc_query'); // If true, use intro text. If false, use specific short description.
					$location_fpage_short_desc_query_expertise = isset($location_fpage_short_desc_query_expertise) ? $location_fpage_short_desc_query_expertise : true; // Define a value if the item has not been updated since 'expertise_locations_fpage_short_desc_query' was added
					if ( $location_fpage_short_desc_query_expertise ) {
						$location_fpage_short_desc_expertise = $location_fpage_intro_expertise;
					} else {
						$location_fpage_short_desc_expertise = get_field('expertise_locations_fpage_short_desc');
						$location_fpage_short_desc_expertise = ( isset($location_fpage_short_desc_expertise) && !empty($location_fpage_short_desc_expertise) ) ? uamswp_fad_fpage_text_replace($location_fpage_short_desc_expertise, $page_titles) : $location_fpage_short_desc_expertise; // Substitute placeholder text for relevant Find-a-Doc Settings value
						$location_fpage_short_desc_expertise = ( isset($location_fpage_short_desc_expertise) && !empty($location_fpage_short_desc_expertise) ) ? $location_fpage_short_desc_expertise : $location_fpage_intro_expertise; // If there is no value, use intro text as a fallback value
					}

			// Descendant Areas of Expertise Fake Subpage (or Section)

				// Get the text values

					// Get the page-level input values

						// $expertise_descendant_fpage_title_expertise = get_field('expertise_descendant_fpage_title'); // Title
						$expertise_descendant_fpage_intro_expertise = get_field('expertise_descendant_fpage_intro'); // Intro text

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for a descendant areas of expertise subpage/section in an area of expertise subsection

						if ( !isset($expertise_descendant_fpage_title_expertise) || empty($expertise_descendant_fpage_title_expertise) ) {
							$expertise_descendant_fpage_title_expertise = get_field('expertise_descendant_fpage_title_expertise', 'option'); // Title
						}
						if ( !isset($expertise_descendant_fpage_intro_expertise) || empty($expertise_descendant_fpage_intro_expertise) ) {
							$expertise_descendant_fpage_intro_expertise = get_field('expertise_descendant_fpage_intro_expertise', 'option'); // Intro text
						}
						$expertise_descendant_fpage_ref_main_title_expertise = get_field('expertise_descendant_fpage_ref_main_title_expertise', 'option'); // Reference to the main area of expertise archive, title
						$expertise_descendant_fpage_ref_main_intro_expertise = get_field('expertise_descendant_fpage_ref_main_intro_expertise', 'option'); // Reference to the main area of expertise archive, body text
						$expertise_descendant_fpage_ref_main_link_expertise = get_field('expertise_descendant_fpage_ref_main_link_expertise', 'option'); // Reference to the main area of expertise archive, link text

					// If the variable is not set or is empty...
					// Set a hardcoded fallback value

						if ( !isset($expertise_descendant_fpage_title_expertise) || empty($expertise_descendant_fpage_title_expertise) ) {
							$expertise_descendant_fpage_title_expertise = '[Descendant Areas of Expertise] Within [Area of Expertise Title]'; // Title
						}
						if ( !isset($expertise_descendant_fpage_intro_expertise) || empty($expertise_descendant_fpage_intro_expertise) ) {
							$expertise_descendant_fpage_intro_expertise = ''; // Intro text
						}
						if ( !isset($expertise_descendant_fpage_ref_main_title_expertise) || empty($expertise_descendant_fpage_ref_main_title_expertise) ) {
							$expertise_descendant_fpage_ref_main_title_expertise = ''; // Reference to the main area of expertise archive, title
						}
						if ( !isset($expertise_descendant_fpage_ref_main_intro_expertise) || empty($expertise_descendant_fpage_ref_main_intro_expertise) ) {
							$expertise_descendant_fpage_ref_main_intro_expertise = ''; // Reference to the main area of expertise archive, body text
						}
						if ( !isset($expertise_descendant_fpage_ref_main_link_expertise) || empty($expertise_descendant_fpage_ref_main_link_expertise) ) {
							$expertise_descendant_fpage_ref_main_link_expertise = ''; // Reference to the main area of expertise archive, link text
						}

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for general placement

						if (
							!isset($expertise_descendant_fpage_title_expertise) || empty($expertise_descendant_fpage_title_expertise)
							||
							!isset($expertise_descendant_fpage_intro_expertise) || empty($expertise_descendant_fpage_intro_expertise)
							||
							!isset($expertise_descendant_fpage_ref_main_title_expertise) || empty($expertise_descendant_fpage_ref_main_title_expertise)
							||
							!isset($expertise_descendant_fpage_ref_main_intro_expertise) || empty($expertise_descendant_fpage_ref_main_intro_expertise)
							||
							!isset($expertise_descendant_fpage_ref_main_link_expertise) || empty($expertise_descendant_fpage_ref_main_link_expertise)
							) {
							// If any of the variables are not set or are empty...

							// Check for the general placement variables.
							// If any aren't set or are empty, call the function and set the global variables.
							if (
								!isset($expertise_descendant_fpage_title_general) || empty($expertise_descendant_fpage_title_general)
								||
								!isset($expertise_descendant_fpage_intro_general) || empty($expertise_descendant_fpage_intro_general)
								||
								!isset($expertise_descendant_fpage_ref_main_title_general) || empty($expertise_descendant_fpage_ref_main_title_general)
								||
								!isset($expertise_descendant_fpage_ref_main_intro_general) || empty($expertise_descendant_fpage_ref_main_intro_general)
								||
								!isset($expertise_descendant_fpage_ref_main_link_general) || empty($expertise_descendant_fpage_ref_main_link_general)
								) {
								$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
									$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
								);
									$expertise_descendant_fpage_title_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_title_general']; // string
									$expertise_descendant_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_intro_general']; // string
									$expertise_descendant_fpage_ref_main_title_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_ref_main_title_general']; // string
									$expertise_descendant_fpage_ref_main_intro_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_ref_main_intro_general']; // string
									$expertise_descendant_fpage_ref_main_link_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_ref_main_link_general']; // string
							}
						}
						if ( !isset($expertise_descendant_fpage_title_expertise) || empty($expertise_descendant_fpage_title_expertise) ) {
							$expertise_descendant_fpage_title_expertise = $expertise_descendant_fpage_title_general; // Title
						}
						if ( !isset($expertise_descendant_fpage_intro_expertise) || empty($expertise_descendant_fpage_intro_expertise) ) {
							$expertise_descendant_fpage_intro_expertise = $expertise_descendant_fpage_intro_general; // Intro text
						}
						if ( !isset($expertise_descendant_fpage_ref_main_title_expertise) || empty($expertise_descendant_fpage_ref_main_title_expertise) ) {
							$expertise_descendant_fpage_ref_main_title_expertise = $expertise_descendant_fpage_ref_main_title_general; // Reference to the main area of expertise archive, title
						}
						if ( !isset($expertise_descendant_fpage_ref_main_intro_expertise) || empty($expertise_descendant_fpage_ref_main_intro_expertise) ) {
							$expertise_descendant_fpage_ref_main_intro_expertise = $expertise_descendant_fpage_ref_main_intro_general; // Reference to the main area of expertise archive, body text
						}
						if ( !isset($expertise_descendant_fpage_ref_main_link_expertise) || empty($expertise_descendant_fpage_ref_main_link_expertise) ) {
							$expertise_descendant_fpage_ref_main_link_expertise = $expertise_descendant_fpage_ref_main_link_general; // Reference to the main area of expertise archive, link text
						}

					// Substitute placeholder text for relevant Find-a-Doc Settings value

						$expertise_descendant_fpage_title_expertise = $expertise_descendant_fpage_title_expertise ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_title_expertise, $page_titles) : ''; // Title
						$expertise_descendant_fpage_intro_expertise = $expertise_descendant_fpage_intro_expertise ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_intro_expertise, $page_titles) : ''; // Intro text
						$expertise_descendant_fpage_ref_main_title_expertise = $expertise_descendant_fpage_ref_main_title_expertise ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_ref_main_title_expertise, $page_titles) : ''; // Reference to the main area of expertise archive, title
						$expertise_descendant_fpage_ref_main_intro_expertise = $expertise_descendant_fpage_ref_main_intro_expertise ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_ref_main_intro_expertise, $page_titles) : ''; // Reference to the main area of expertise archive, body text
						$expertise_descendant_fpage_ref_main_link_expertise = $expertise_descendant_fpage_ref_main_link_expertise ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_ref_main_link_expertise, $page_titles) : ''; // Reference to the main area of expertise archive, link text

				// Get value for meta description

					$expertise_descendant_fpage_short_desc_query_expertise = get_field('expertise_descendant_fpage_short_desc_query'); // If true, use intro text. If false, use specific short description.
					$expertise_descendant_fpage_short_desc_query_expertise = isset($expertise_descendant_fpage_short_desc_query_expertise) ? $expertise_descendant_fpage_short_desc_query_expertise : true; // Define a value if the item has not been updated since 'expertise_descendant_fpage_short_desc_query' was added
					if ( $expertise_descendant_fpage_short_desc_query_expertise ) {
						$expertise_descendant_fpage_short_desc_expertise = $expertise_descendant_fpage_intro_expertise;
					} else {
						$expertise_descendant_fpage_short_desc_expertise = get_field('expertise_descendant_fpage_short_desc');
						$expertise_descendant_fpage_short_desc_expertise = ( isset($expertise_descendant_fpage_short_desc_expertise) && !empty($expertise_descendant_fpage_short_desc_expertise) ) ? uamswp_fad_fpage_text_replace($expertise_descendant_fpage_short_desc_expertise, $page_titles) : $expertise_descendant_fpage_short_desc_expertise; // Substitute placeholder text for relevant Find-a-Doc Settings value
						$expertise_descendant_fpage_short_desc_expertise = ( isset($expertise_descendant_fpage_short_desc_expertise) && !empty($expertise_descendant_fpage_short_desc_expertise) ) ? $expertise_descendant_fpage_short_desc_expertise : $expertise_descendant_fpage_intro_expertise; // If there is no value, use intro text as a fallback value
					}

			// Related Areas of Expertise Fake Subpage (or Section)

				// Get the text values

					// Get the page-level input values

						// $expertise_fpage_title_expertise = get_field('expertise_associated_fpage_title'); // Title
						$expertise_fpage_intro_expertise = get_field('expertise_associated_fpage_intro'); // Intro text

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for a related areas of expertise subpage/section in an area of expertise subsection

						if ( !isset($expertise_fpage_title_expertise) || empty($expertise_fpage_title_expertise) ) {
							$expertise_fpage_title_expertise = get_field('expertise_fpage_title_expertise', 'option'); // Title
						}
						if ( !isset($expertise_fpage_intro_expertise) || empty($expertise_fpage_intro_expertise) ) {
							$expertise_fpage_intro_expertise = get_field('expertise_fpage_intro_expertise', 'option'); // Intro text
						}
						$expertise_fpage_ref_main_title_expertise = get_field('expertise_fpage_ref_main_title_expertise', 'option'); // Reference to the main area of expertise archive, title
						$expertise_fpage_ref_main_intro_expertise = get_field('expertise_fpage_ref_main_intro_expertise', 'option'); // Reference to the main area of expertise archive, body text
						$expertise_fpage_ref_main_link_expertise = get_field('expertise_fpage_ref_main_link_expertise', 'option'); // Reference to the main area of expertise archive, link text

					// If the variable is not set or is empty...
					// Set a hardcoded fallback value

						if ( !isset($expertise_fpage_title_expertise) || empty($expertise_fpage_title_expertise) ) {
							$expertise_fpage_title_expertise = '[Areas of Expertise] Related to [Area of Expertise Title]'; // Title
						}
						if ( !isset($expertise_fpage_intro_expertise) || empty($expertise_fpage_intro_expertise) ) {
							$expertise_fpage_intro_expertise = ''; // Intro text
						}
						if ( !isset($expertise_fpage_ref_main_title_expertise) || empty($expertise_fpage_ref_main_title_expertise) ) {
							$expertise_fpage_ref_main_title_expertise = ''; // Reference to the main area of expertise archive, title
						}
						if ( !isset($expertise_fpage_ref_main_intro_expertise) || empty($expertise_fpage_ref_main_intro_expertise) ) {
							$expertise_fpage_ref_main_intro_expertise = ''; // Reference to the main area of expertise archive, body text
						}
						if ( !isset($expertise_fpage_ref_main_link_expertise) || empty($expertise_fpage_ref_main_link_expertise) ) {
							$expertise_fpage_ref_main_link_expertise = ''; // Reference to the main area of expertise archive, link text
						}

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for general placement

						if (
							!isset($expertise_fpage_title_expertise) || empty($expertise_fpage_title_expertise)
							||
							!isset($expertise_fpage_intro_expertise) || empty($expertise_fpage_intro_expertise)
							||
							!isset($expertise_fpage_ref_main_title_expertise) || empty($expertise_fpage_ref_main_title_expertise)
							||
							!isset($expertise_fpage_ref_main_intro_expertise) || empty($expertise_fpage_ref_main_intro_expertise)
							||
							!isset($expertise_fpage_ref_main_link_expertise) || empty($expertise_fpage_ref_main_link_expertise)
							) {
							// If any of the variables are not set or are empty...

							// Check for the general placement variables.
							// If any aren't set or are empty, call the function and set the global variables.
							if (
								!isset($expertise_fpage_title_general) || empty($expertise_fpage_title_general)
								||
								!isset($expertise_fpage_intro_general) || empty($expertise_fpage_intro_general)
								||
								!isset($expertise_fpage_ref_main_title_general) || empty($expertise_fpage_ref_main_title_general)
								||
								!isset($expertise_fpage_ref_main_intro_general) || empty($expertise_fpage_ref_main_intro_general)
								||
								!isset($expertise_fpage_ref_main_link_general) || empty($expertise_fpage_ref_main_link_general)
								) {
								$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
									$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
								);
									$expertise_fpage_title_general = $fpage_text_expertise_general_vars['expertise_fpage_title_general']; // string
									$expertise_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_intro_general']; // string
									$expertise_fpage_ref_main_title_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_title_general']; // string
									$expertise_fpage_ref_main_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_intro_general']; // string
									$expertise_fpage_ref_main_link_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_link_general']; // string
							}
						}
						if ( !isset($expertise_fpage_title_expertise) || empty($expertise_fpage_title_expertise) ) {
							$expertise_fpage_title_expertise = $expertise_fpage_title_general; // Title
						}
						if ( !isset($expertise_fpage_intro_expertise) || empty($expertise_fpage_intro_expertise) ) {
							$expertise_fpage_intro_expertise = $expertise_fpage_intro_general; // Intro text
						}
						if ( !isset($expertise_fpage_ref_main_title_expertise) || empty($expertise_fpage_ref_main_title_expertise) ) {
							$expertise_fpage_ref_main_title_expertise = $expertise_fpage_ref_main_title_general; // Reference to the main area of expertise archive, title
						}
						if ( !isset($expertise_fpage_ref_main_intro_expertise) || empty($expertise_fpage_ref_main_intro_expertise) ) {
							$expertise_fpage_ref_main_intro_expertise = $expertise_fpage_ref_main_intro_general; // Reference to the main area of expertise archive, body text
						}
						if ( !isset($expertise_fpage_ref_main_link_expertise) || empty($expertise_fpage_ref_main_link_expertise) ) {
							$expertise_fpage_ref_main_link_expertise = $expertise_fpage_ref_main_link_general; // Reference to the main area of expertise archive, link text
						}

					// Substitute placeholder text for relevant Find-a-Doc Settings value

						$expertise_fpage_title_expertise = $expertise_fpage_title_expertise ? uamswp_fad_fpage_text_replace($expertise_fpage_title_expertise, $page_titles) : ''; // Title
						$expertise_fpage_intro_expertise = $expertise_fpage_intro_expertise ? uamswp_fad_fpage_text_replace($expertise_fpage_intro_expertise, $page_titles) : ''; // Intro text
						$expertise_fpage_ref_main_title_expertise = $expertise_fpage_ref_main_title_expertise ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_title_expertise, $page_titles) : ''; // Reference to the main area of expertise archive, title
						$expertise_fpage_ref_main_intro_expertise = $expertise_fpage_ref_main_intro_expertise ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_intro_expertise, $page_titles) : ''; // Reference to the main area of expertise archive, body text
						$expertise_fpage_ref_main_link_expertise = $expertise_fpage_ref_main_link_expertise ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_link_expertise, $page_titles) : ''; // Reference to the main area of expertise archive, link text

				// Get value for meta description

					$expertise_fpage_short_desc_query_expertise = get_field('expertise_associated_fpage_short_desc_query'); // If true, use intro text. If false, use specific short description.
					$expertise_fpage_short_desc_query_expertise = isset($expertise_fpage_short_desc_query_expertise) ? $expertise_fpage_short_desc_query_expertise : true; // Define a value if the item has not been updated since 'expertise_associated_fpage_short_desc_query' was added
					if ( $expertise_fpage_short_desc_query_expertise ) {
						$expertise_fpage_short_desc_expertise = $expertise_fpage_intro_expertise;
					} else {
						$expertise_fpage_short_desc_expertise = get_field('expertise_associated_fpage_short_desc');
						$expertise_fpage_short_desc_expertise = ( isset($expertise_fpage_short_desc_expertise) && !empty($expertise_fpage_short_desc_expertise) ) ? uamswp_fad_fpage_text_replace($expertise_fpage_short_desc_expertise, $page_titles) : $expertise_fpage_short_desc_expertise; // Substitute placeholder text for relevant Find-a-Doc Settings value
						$expertise_fpage_short_desc_expertise = ( isset($expertise_fpage_short_desc_expertise) && !empty($expertise_fpage_short_desc_expertise) ) ? $expertise_fpage_short_desc_expertise : $expertise_fpage_intro_expertise; // If there is no value, use intro text as a fallback value
					}

			// Clinical Resources Fake Subpage (or Section)

				// Get the text values

					// Get the page-level input values

						// $clinical_resource_fpage_title_expertise = get_field('expertise_clinical_resources_fpage_title'); // Title
						$clinical_resource_fpage_intro_expertise = get_field('expertise_clinical_resources_fpage_intro'); // Intro text

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for a related areas of expertise subpage/section in an area of expertise subsection

						if ( !isset($clinical_resource_fpage_title_expertise) || empty($clinical_resource_fpage_title_expertise) ) {
							$clinical_resource_fpage_title_expertise = get_field('clinical_resource_fpage_title_expertise', 'option'); // Title
						}
						if ( !isset($clinical_resource_fpage_intro_expertise) || empty($clinical_resource_fpage_intro_expertise) ) {
							$clinical_resource_fpage_intro_expertise = get_field('clinical_resource_fpage_intro_expertise', 'option'); // Intro text
						}
						$clinical_resource_fpage_ref_main_title_expertise = get_field('clinical_resource_fpage_ref_main_title_expertise', 'option'); // Reference to the main clinical resource archive, title
						$clinical_resource_fpage_ref_main_intro_expertise = get_field('clinical_resource_fpage_ref_main_intro_expertise', 'option'); // Reference to the main clinical resource archive, body text
						$clinical_resource_fpage_ref_main_link_expertise = get_field('clinical_resource_fpage_ref_main_link_expertise', 'option'); // Reference to the main clinical resource archive, link text
						$clinical_resource_fpage_ref_top_title_expertise = get_field('clinical_resource_fpage_ref_top_title_expertise', 'option'); // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, title
						$clinical_resource_fpage_ref_top_intro_expertise = get_field('clinical_resource_fpage_ref_top_intro_expertise', 'option'); // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, body text
						$clinical_resource_fpage_ref_top_link_expertise = get_field('clinical_resource_fpage_ref_top_link_expertise', 'option'); // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, link text
						$clinical_resource_fpage_more_text_expertise = get_field('clinical_resource_fpage_more_text_expertise', 'option'); // "More" Intro Text of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)
						$clinical_resource_fpage_more_link_text_expertise = get_field('clinical_resource_fpage_more_link_text_expertise', 'option'); // "More" Link Text of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)
						$clinical_resource_fpage_more_link_descr_expertise = get_field('clinical_resource_fpage_more_link_descr_expertise', 'option'); // "More" Link Description of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)

					// If the variable is not set or is empty...
					// Set a hardcoded fallback value

						if ( !isset($clinical_resource_fpage_title_expertise) || empty($clinical_resource_fpage_title_expertise) ) {
							$clinical_resource_fpage_title_expertise = '[Clinical Resources] Related to [Area of Expertise Title]'; // Title
						}
						if ( !isset($clinical_resource_fpage_intro_expertise) || empty($clinical_resource_fpage_intro_expertise) ) {
							$clinical_resource_fpage_intro_expertise = ''; // Intro text
						}
						if ( !isset($clinical_resource_fpage_ref_main_title_expertise) || empty($clinical_resource_fpage_ref_main_title_expertise) ) {
							$clinical_resource_fpage_ref_main_title_expertise = ''; // Reference to the main clinical resource archive, title
						}
						if ( !isset($clinical_resource_fpage_ref_main_intro_expertise) || empty($clinical_resource_fpage_ref_main_intro_expertise) ) {
							$clinical_resource_fpage_ref_main_intro_expertise = ''; // Reference to the main clinical resource archive, body text
						}
						if ( !isset($clinical_resource_fpage_ref_main_link_expertise) || empty($clinical_resource_fpage_ref_main_link_expertise) ) {
							$clinical_resource_fpage_ref_main_link_expertise = ''; // Reference to the main clinical resource archive, link text
						}
						if ( !isset($clinical_resource_fpage_ref_top_title_expertise) || empty($clinical_resource_fpage_ref_top_title_expertise) ) {
							$clinical_resource_fpage_ref_top_title_expertise = $clinical_resource_fpage_title_expertise; // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, title
						}
						if ( !isset($clinical_resource_fpage_ref_top_intro_expertise) || empty($clinical_resource_fpage_ref_top_intro_expertise) ) {
							$clinical_resource_fpage_ref_top_intro_expertise = 'Unlock a treasure trove of [clinical resources] covering diverse specialties within [Area of Expertise Title]. Access a wealth of articles, videos, infographics, and documents to enhance your knowledge and understanding.'; // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, body text
						}
						if ( !isset($clinical_resource_fpage_ref_top_link_expertise) || empty($clinical_resource_fpage_ref_top_link_expertise) ) {
							$clinical_resource_fpage_ref_top_link_expertise = 'View [Clinical Resources]'; // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, link text
						}
						if ( !isset($clinical_resource_fpage_more_text_expertise) || empty($clinical_resource_fpage_more_text_expertise) ) {
							$clinical_resource_fpage_more_text_expertise = 'Want to find more [clinical resources] related to [Area of Expertise Title]?'; // "More" intro text
						}
						if ( !isset($clinical_resource_fpage_more_link_text_expertise) || empty($clinical_resource_fpage_more_link_text_expertise) ) {
							$clinical_resource_fpage_more_link_text_expertise = 'View the Full List'; // "More" link text
						}
						if ( !isset($clinical_resource_fpage_more_link_descr_expertise) || empty($clinical_resource_fpage_more_link_descr_expertise) ) {
							$clinical_resource_fpage_more_link_descr_expertise = 'View the full list of [clinical resources] related to [Area of Expertise Title]'; // "More" link description
						}

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for general placement

					if (
						!isset($clinical_resource_fpage_title_expertise) || empty($clinical_resource_fpage_title_expertise)
						||
						!isset($clinical_resource_fpage_intro_expertise) || empty($clinical_resource_fpage_intro_expertise)
						||
						!isset($clinical_resource_fpage_ref_main_title_expertise) || empty($clinical_resource_fpage_ref_main_title_expertise)
						||
						!isset($clinical_resource_fpage_ref_main_intro_expertise) || empty($clinical_resource_fpage_ref_main_intro_expertise)
						||
						!isset($clinical_resource_fpage_ref_main_link_expertise) || empty($clinical_resource_fpage_ref_main_link_expertise)
						||
						!isset($clinical_resource_fpage_ref_top_title_expertise) || empty($clinical_resource_fpage_ref_top_title_expertise)
						||
						!isset($clinical_resource_fpage_ref_top_intro_expertise) || empty($clinical_resource_fpage_ref_top_intro_expertise)
						||
						!isset($clinical_resource_fpage_ref_top_link_expertise) || empty($clinical_resource_fpage_ref_top_link_expertise)
						||
						!isset($clinical_resource_fpage_more_text_expertise) || empty($clinical_resource_fpage_more_text_expertise)
						||
						!isset($clinical_resource_fpage_more_link_text_expertise) || empty($clinical_resource_fpage_more_link_text_expertise)
						||
						!isset($clinical_resource_fpage_more_link_descr_expertise) || empty($clinical_resource_fpage_more_link_descr_expertise)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($clinical_resource_fpage_title_general) || empty($clinical_resource_fpage_title_general)
							||
							!isset($clinical_resource_fpage_intro_general) || empty($clinical_resource_fpage_intro_general)
							||
							!isset($clinical_resource_fpage_ref_main_title_general) || empty($clinical_resource_fpage_ref_main_title_general)
							||
							!isset($clinical_resource_fpage_ref_main_intro_general) || empty($clinical_resource_fpage_ref_main_intro_general)
							||
							!isset($clinical_resource_fpage_ref_main_link_general) || empty($clinical_resource_fpage_ref_main_link_general)
							||
							!isset($clinical_resource_fpage_ref_top_title_general) || empty($clinical_resource_fpage_ref_top_title_general)
							||
							!isset($clinical_resource_fpage_ref_top_intro_general) || empty($clinical_resource_fpage_ref_top_intro_general)
							||
							!isset($clinical_resource_fpage_ref_top_link_general) || empty($clinical_resource_fpage_ref_top_link_general)
							||
							!isset($clinical_resource_fpage_more_text_general) || empty($clinical_resource_fpage_more_text_general)
							||
							!isset($clinical_resource_fpage_more_link_text_general) || empty($clinical_resource_fpage_more_link_text_general)
							||
							!isset($clinical_resource_fpage_more_link_descr_general) || empty($clinical_resource_fpage_more_link_descr_general)
							) {
							$fpage_text_clinical_resource_general_vars = isset($fpage_text_clinical_resource_general_vars) ? $fpage_text_clinical_resource_general_vars : uamswp_fad_fpage_text_clinical_resource_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$clinical_resource_fpage_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_title_general']; // string
								$clinical_resource_fpage_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_intro_general']; // string
								$clinical_resource_fpage_ref_main_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_title_general']; // string
								$clinical_resource_fpage_ref_main_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_intro_general']; // string
								$clinical_resource_fpage_ref_main_link_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_link_general']; // string
								$clinical_resource_fpage_ref_top_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_top_title_general']; // string
								$clinical_resource_fpage_ref_top_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_top_intro_general']; // string
								$clinical_resource_fpage_ref_top_link_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_top_link_general']; // string
								$clinical_resource_fpage_more_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_text_general']; // string
								$clinical_resource_fpage_more_link_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_text_general']; // string
								$clinical_resource_fpage_more_link_descr_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_descr_general']; // string
						}
					}
					if ( !isset($clinical_resource_fpage_title_expertise) || empty($clinical_resource_fpage_title_expertise) ) {
						$clinical_resource_fpage_title_expertise = $clinical_resource_fpage_title_general; // Title
					}
					if ( !isset($clinical_resource_fpage_intro_expertise) || empty($clinical_resource_fpage_intro_expertise) ) {
						$clinical_resource_fpage_intro_expertise = $clinical_resource_fpage_intro_general; // Intro text
					}
					if ( !isset($clinical_resource_fpage_ref_main_title_expertise) || empty($clinical_resource_fpage_ref_main_title_expertise) ) {
						$clinical_resource_fpage_ref_main_title_expertise = $clinical_resource_fpage_ref_main_title_general; // Reference to the main clinical resource archive, title
					}
					if ( !isset($clinical_resource_fpage_ref_main_intro_expertise) || empty($clinical_resource_fpage_ref_main_intro_expertise) ) {
						$clinical_resource_fpage_ref_main_intro_expertise = $clinical_resource_fpage_ref_main_intro_general; // Reference to the main clinical resource archive, body text
					}
					if ( !isset($clinical_resource_fpage_ref_main_link_expertise) || empty($clinical_resource_fpage_ref_main_link_expertise) ) {
						$clinical_resource_fpage_ref_main_link_expertise = $clinical_resource_fpage_ref_main_link_general; // Reference to the main clinical resource archive, link text
					}
					if ( !isset($clinical_resource_fpage_ref_top_title_expertise) || empty($clinical_resource_fpage_ref_top_title_expertise) ) {
						$clinical_resource_fpage_ref_top_title_expertise = $clinical_resource_fpage_ref_top_title_general; // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, title
					}
					if ( !isset($clinical_resource_fpage_ref_top_intro_expertise) || empty($clinical_resource_fpage_ref_top_intro_expertise) ) {
						$clinical_resource_fpage_ref_top_intro_expertise = $clinical_resource_fpage_ref_top_intro_general; // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, body text
					}
					if ( !isset($clinical_resource_fpage_ref_top_link_expertise) || empty($clinical_resource_fpage_ref_top_link_expertise) ) {
						$clinical_resource_fpage_ref_top_link_expertise = $clinical_resource_fpage_ref_top_link_general; // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, link text
					}
					if ( !isset($clinical_resource_fpage_more_text_expertise) || empty($clinical_resource_fpage_more_text_expertise) ) {
						$clinical_resource_fpage_more_text_expertise = $clinical_resource_fpage_more_text_general; // "More" intro text
					}
					if ( !isset($clinical_resource_fpage_more_link_text_expertise) || empty($clinical_resource_fpage_more_link_text_expertise) ) {
						$clinical_resource_fpage_more_link_text_expertise = $clinical_resource_fpage_more_link_text_general; // "More" link text
					}
					if ( !isset($clinical_resource_fpage_more_link_descr_expertise) || empty($clinical_resource_fpage_more_link_descr_expertise) ) {
						$clinical_resource_fpage_more_link_descr_expertise = $clinical_resource_fpage_more_link_descr_general; // "More" link description
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$clinical_resource_fpage_title_expertise = $clinical_resource_fpage_title_expertise ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_title_expertise, $page_titles) : ''; // Title
					$clinical_resource_fpage_intro_expertise = $clinical_resource_fpage_intro_expertise ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_intro_expertise, $page_titles) : ''; // Intro text
					$clinical_resource_fpage_ref_main_title_expertise = $clinical_resource_fpage_ref_main_title_expertise ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_title_expertise, $page_titles) : ''; // Reference to the main clinical resource archive, title
					$clinical_resource_fpage_ref_main_intro_expertise = $clinical_resource_fpage_ref_main_intro_expertise ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_intro_expertise, $page_titles) : ''; // Reference to the main clinical resource archive, body text
					$clinical_resource_fpage_ref_main_link_expertise = $clinical_resource_fpage_ref_main_link_expertise ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_link_expertise, $page_titles) : ''; // Reference to the main clinical resource archive, link text
					$clinical_resource_fpage_ref_top_title_expertise = $clinical_resource_fpage_ref_top_title_expertise ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_top_title_expertise, $page_titles) : ''; // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, title
					$clinical_resource_fpage_ref_top_intro_expertise = $clinical_resource_fpage_ref_top_intro_expertise ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_top_intro_expertise, $page_titles) : ''; // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, body text
					$clinical_resource_fpage_ref_top_link_expertise = $clinical_resource_fpage_ref_top_link_expertise ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_top_link_expertise, $page_titles) : ''; // Reference to a Top-Level Area of Expertise's Fake Subpage for Clinical Resources, link text
					$clinical_resource_fpage_more_text_expertise = $clinical_resource_fpage_more_text_expertise ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_text_expertise, $page_titles) : ''; // "More" intro text
					$clinical_resource_fpage_more_link_text_expertise = $clinical_resource_fpage_more_link_text_expertise ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_link_text_expertise, $page_titles) : ''; // "More" link text
					$clinical_resource_fpage_more_link_descr_expertise = $clinical_resource_fpage_more_link_descr_expertise ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_link_descr_expertise, $page_titles) : ''; // "More" link description

				// Get value for meta description

					$clinical_resource_fpage_short_desc_query_expertise = get_field('expertise_clinical_resources_fpage_short_desc_query'); // If true, use intro text. If false, use specific short description.
					$clinical_resource_fpage_short_desc_query_expertise = isset($clinical_resource_fpage_short_desc_query_expertise) ? $clinical_resource_fpage_short_desc_query_expertise : true; // Define a value if the item has not been updated since 'expertise_clinical_resources_fpage_short_desc_query' was added
					if ( $clinical_resource_fpage_short_desc_query_expertise ) {
						$clinical_resource_fpage_short_desc_expertise = $clinical_resource_fpage_intro_expertise;
					} else {
						$clinical_resource_fpage_short_desc_expertise = get_field('expertise_clinical_resources_fpage_short_desc');
						$clinical_resource_fpage_short_desc_expertise = ( isset($clinical_resource_fpage_short_desc_expertise) && !empty($clinical_resource_fpage_short_desc_expertise) ) ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_short_desc_expertise, $page_titles) : $clinical_resource_fpage_short_desc_expertise; // Substitute placeholder text for relevant Find-a-Doc Settings value
						$clinical_resource_fpage_short_desc_expertise = ( isset($clinical_resource_fpage_short_desc_expertise) && !empty($clinical_resource_fpage_short_desc_expertise) ) ? $clinical_resource_fpage_short_desc_expertise : $clinical_resource_fpage_intro_expertise; // If there is no value, use intro text as a fallback value
					}

			// Conditions Fake Subpage (or Section)

				// Get the text values

					// Get the page-level input values

						// $condition_fpage_title_expertise = get_field('expertise_conditions_fpage_title'); // Title
						$condition_fpage_intro_expertise = get_field('expertise_conditions_fpage_intro'); // Intro text

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for a conditions subpage/section in an area of expertise subsection

						if ( !isset($condition_fpage_title_expertise) || empty($condition_fpage_title_expertise) ) {
							$condition_fpage_title_expertise = get_field('conditions_fpage_title_expertise', 'option'); // Title
						}
						if ( !isset($condition_fpage_intro_expertise) || empty($condition_fpage_intro_expertise) ) {
							$condition_fpage_intro_expertise = get_field('conditions_fpage_intro_expertise', 'option'); // Intro text
						}

					// If the variable is not set or is empty...
					// Set a hardcoded fallback value

						if ( !isset($condition_fpage_title_expertise) || empty($condition_fpage_title_expertise) ) {
							$condition_fpage_title_expertise = '[Conditions] Related to [Area of Expertise Title]'; // Title
						}
						if ( !isset($condition_fpage_intro_expertise) || empty($condition_fpage_intro_expertise) ) {
							$condition_fpage_intro_expertise = ''; // Intro text
						}

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for general placement of a conditions subpage/section

						if ( !isset($condition_fpage_title_expertise) || empty($condition_fpage_title_expertise) ) {
							if ( !isset($condition_fpage_title_general) || empty($condition_fpage_title_general) ) {
								$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
									$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
								);
									$condition_fpage_title_general = $fpage_text_condition_general_vars['condition_fpage_title_general']; // string
							}
							$condition_fpage_title_expertise = $condition_fpage_title_general; // Title
						}
						if ( !isset($condition_fpage_intro_expertise) || empty($condition_fpage_intro_expertise) ) {
							if ( !isset($condition_fpage_intro_general) || empty($condition_fpage_intro_general) ) {
								$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
									$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
								);
									$condition_fpage_intro_general = $fpage_text_condition_general_vars['condition_fpage_intro_general']; // string
							}
							$condition_fpage_intro_expertise = $condition_fpage_intro_general; // Intro text
						}

					// Substitute placeholder text for relevant Find-a-Doc Settings value

						$condition_fpage_title_expertise = $condition_fpage_title_expertise ? uamswp_fad_fpage_text_replace($condition_fpage_title_expertise, $page_titles) : ''; // Title
						$condition_fpage_intro_expertise = $condition_fpage_intro_expertise ? uamswp_fad_fpage_text_replace($condition_fpage_intro_expertise, $page_titles) : ''; // Intro text

			// Treatments Fake Subpage (or Section)

				// Get the text values

					// Get the page-level input values

						// $treatment_fpage_title_expertise = get_field('expertise_treatments_fpage_title'); // Title
						$treatment_fpage_intro_expertise = get_field('expertise_treatments_fpage_intro'); // Intro text

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for a treatments subpage/section in an area of expertise subsection

						if ( !isset($treatment_fpage_title_expertise) || empty($treatment_fpage_title_expertise) ) {
							$treatment_fpage_title_expertise = get_field('treatments_fpage_title_expertise', 'option'); // Title
						}
						if ( !isset($treatment_fpage_intro_expertise) || empty($treatment_fpage_intro_expertise) ) {
							$treatment_fpage_intro_expertise = get_field('treatments_fpage_intro_expertise', 'option'); // Intro text
						}

					// If the variable is not set or is empty...
					// Set a hardcoded fallback value

						if ( !isset($treatment_fpage_title_expertise) || empty($treatment_fpage_title_expertise) ) {
							$treatment_fpage_title_expertise = '[Treatments] Related to [Area of Expertise Title]'; // Title
						}
						if ( !isset($treatment_fpage_intro_expertise) || empty($treatment_fpage_intro_expertise) ) {
							$treatment_fpage_intro_expertise = ''; // Intro text
						}

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for general placement of a treatments subpage/section

						if ( !isset($treatment_fpage_title_expertise) || empty($treatment_fpage_title_expertise) ) {
							if ( !isset($treatment_fpage_title_general) || empty($treatment_fpage_title_general) ) {
								$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
									$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
								);
									$treatment_fpage_title_general = $fpage_text_treatment_general_vars['treatment_fpage_title_general']; // string
							}
							$treatment_fpage_title_expertise = $treatment_fpage_title_general; // Title
						}
						if ( !isset($treatment_fpage_intro_expertise) || empty($treatment_fpage_intro_expertise) ) {
							if ( !isset($treatment_fpage_intro_general) || empty($treatment_fpage_intro_general) ) {
								$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
									$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
								);
									$treatment_fpage_intro_general = $fpage_text_treatment_general_vars['treatment_fpage_intro_general']; // string
							}
							$treatment_fpage_intro_expertise = $treatment_fpage_intro_general; // Intro text
						}

					// Substitute placeholder text for relevant Find-a-Doc Settings value

						$treatment_fpage_title_expertise = $treatment_fpage_title_expertise ? uamswp_fad_fpage_text_replace($treatment_fpage_title_expertise, $page_titles) : ''; // Title
						$treatment_fpage_intro_expertise = $treatment_fpage_intro_expertise ? uamswp_fad_fpage_text_replace($treatment_fpage_intro_expertise, $page_titles) : ''; // Intro text

			// Combined Conditions and Treatments Fake Subpage (or Section)

				// Get the text values

					// Get the page-level input values

						// $condition_treatment_fpage_title_expertise = get_field('expertise_condition_treatment_fpage_title'); // Title
						$condition_treatment_fpage_intro_expertise = get_field('expertise_condition_treatment_fpage_intro'); // Intro text

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for a combined conditions and treatments subpage/section in an area of expertise subsection

					if ( !isset($condition_treatment_fpage_title_expertise) || empty($condition_treatment_fpage_title_expertise) ) {
						$condition_treatment_fpage_title_expertise = get_field('condition_treatment_fpage_title_expertise', 'option'); // Title
					}
					if ( !isset($condition_treatment_fpage_intro_expertise) || empty($condition_treatment_fpage_intro_expertise) ) {
						$condition_treatment_fpage_intro_expertise = get_field('condition_treatment_fpage_intro_expertise', 'option'); // Intro text
					}

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($condition_treatment_fpage_title_expertise) || empty($condition_treatment_fpage_title_expertise) ) {
						$condition_treatment_fpage_title_expertise = '[Conditions and Treatments] Related to [Area of Expertise Title]'; // Title
					}
					if ( !isset($condition_treatment_fpage_intro_expertise) || empty($condition_treatment_fpage_intro_expertise) ) {
						$condition_treatment_fpage_intro_expertise = ''; // Intro text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement of a combined conditions and treatments subpage/section

					if ( !isset($condition_treatment_fpage_title_expertise) || empty($condition_treatment_fpage_title_expertise) ) {
						if ( !isset($condition_treatment_fpage_title_general) || empty($condition_treatment_fpage_title_general) ) {
							$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_treatment_fpage_title_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_title_general']; // string
						}
						$condition_treatment_fpage_title_expertise = $condition_treatment_fpage_title_general; // Title
					}
					if ( !isset($condition_treatment_fpage_intro_expertise) || empty($condition_treatment_fpage_intro_expertise) ) {
						if ( !isset($condition_treatment_fpage_intro_general) || empty($condition_treatment_fpage_intro_general) ) {
							$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_treatment_fpage_intro_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_intro_general']; // string
						}
						$condition_treatment_fpage_intro_expertise = $condition_treatment_fpage_intro_general; // Intro text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$condition_treatment_fpage_title_expertise = $condition_treatment_fpage_title_expertise ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_title_expertise, $page_titles) : ''; // Title
					$condition_treatment_fpage_intro_expertise = $condition_treatment_fpage_intro_expertise ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_intro_expertise, $page_titles) : ''; // Intro text

			// Create and return an array to be used on the templates and template parts

				$fpage_text_expertise_vars = array(
					'expertise_page_title_options'							=> $expertise_page_title_options, // string
					'expertise_page_title'									=> $expertise_page_title, // string
					'expertise_page_intro'									=> $expertise_page_intro, // string
					'expertise_page_image'									=> $expertise_page_image, // string
					'expertise_page_image_mobile'							=> $expertise_page_image_mobile, // string
					'expertise_short_desc'									=> $expertise_short_desc, // string
					'provider_fpage_title_expertise'						=> $provider_fpage_title_expertise, // string
					'provider_fpage_intro_expertise'						=> $provider_fpage_intro_expertise, // string
					'provider_fpage_ref_main_title_expertise'				=> $provider_fpage_ref_main_title_expertise, // string
					'provider_fpage_ref_main_intro_expertise'				=> $provider_fpage_ref_main_intro_expertise, // string
					'provider_fpage_ref_main_link_expertise'				=> $provider_fpage_ref_main_link_expertise, // string
					'provider_fpage_ref_top_title_expertise'				=> $provider_fpage_ref_top_title_expertise, // string
					'provider_fpage_ref_top_intro_expertise'				=> $provider_fpage_ref_top_intro_expertise, // string
					'provider_fpage_ref_top_link_expertise'					=> $provider_fpage_ref_top_link_expertise, // string
					'provider_fpage_short_desc_expertise'					=> $provider_fpage_short_desc_expertise, // string
					'location_fpage_title_expertise'						=> $location_fpage_title_expertise, // string
					'location_fpage_intro_expertise'						=> $location_fpage_intro_expertise, // string
					'location_fpage_short_desc_expertise'					=> $location_fpage_short_desc_expertise, // string
					'location_fpage_ref_main_title_expertise'				=> $location_fpage_ref_main_title_expertise, // string
					'location_fpage_ref_main_intro_expertise'				=> $location_fpage_ref_main_intro_expertise, // string
					'location_fpage_ref_main_link_expertise'				=> $location_fpage_ref_main_link_expertise, // string
					'location_fpage_ref_top_title_expertise'				=> $location_fpage_ref_top_title_expertise, // string
					'location_fpage_ref_top_intro_expertise'				=> $location_fpage_ref_top_intro_expertise, // string
					'location_fpage_ref_top_link_expertise'					=> $location_fpage_ref_top_link_expertise, // string
					'expertise_descendant_fpage_title_expertise'			=> $expertise_descendant_fpage_title_expertise, // string
					'expertise_descendant_fpage_intro_expertise'			=> $expertise_descendant_fpage_intro_expertise, // string
					'expertise_descendant_fpage_short_desc_expertise'		=> $expertise_descendant_fpage_short_desc_expertise, // string
					'expertise_descendant_fpage_ref_main_title_expertise'	=> $expertise_descendant_fpage_ref_main_title_expertise, // string
					'expertise_descendant_fpage_ref_main_intro_expertise'	=> $expertise_descendant_fpage_ref_main_intro_expertise, // string
					'expertise_descendant_fpage_ref_main_link_expertise'	=> $expertise_descendant_fpage_ref_main_link_expertise, // string
					'expertise_fpage_title_expertise'						=> $expertise_fpage_title_expertise, // string
					'expertise_fpage_intro_expertise'						=> $expertise_fpage_intro_expertise, // string
					'expertise_fpage_short_desc_expertise'					=> $expertise_fpage_short_desc_expertise, // string
					'expertise_fpage_ref_main_title_expertise'				=> $expertise_fpage_ref_main_title_expertise, // string
					'expertise_fpage_ref_main_intro_expertise'				=> $expertise_fpage_ref_main_intro_expertise, // string
					'expertise_fpage_ref_main_link_expertise'				=> $expertise_fpage_ref_main_link_expertise, // string
					'clinical_resource_fpage_title_expertise'				=> $clinical_resource_fpage_title_expertise, // string
					'clinical_resource_fpage_intro_expertise'				=> $clinical_resource_fpage_intro_expertise, // string
					'clinical_resource_fpage_ref_main_title_expertise'		=> $clinical_resource_fpage_ref_main_title_expertise, // string
					'clinical_resource_fpage_ref_main_intro_expertise'		=> $clinical_resource_fpage_ref_main_intro_expertise, // string
					'clinical_resource_fpage_ref_main_link_expertise'		=> $clinical_resource_fpage_ref_main_link_expertise, // string
					'clinical_resource_fpage_ref_top_title_expertise'		=> $clinical_resource_fpage_ref_top_title_expertise, // string
					'clinical_resource_fpage_ref_top_intro_expertise'		=> $clinical_resource_fpage_ref_top_intro_expertise, // string
					'clinical_resource_fpage_ref_top_link_expertise'		=> $clinical_resource_fpage_ref_top_link_expertise, // string
					'clinical_resource_fpage_more_text_expertise'			=> $clinical_resource_fpage_more_text_expertise, // string
					'clinical_resource_fpage_more_link_text_expertise'		=> $clinical_resource_fpage_more_link_text_expertise, // string
					'clinical_resource_fpage_more_link_descr_expertise'		=> $clinical_resource_fpage_more_link_descr_expertise, // string
					'clinical_resource_fpage_short_desc_expertise'			=> $clinical_resource_fpage_short_desc_expertise, // string
					'condition_fpage_title_expertise'						=> $condition_fpage_title_expertise, // string
					'condition_fpage_intro_expertise'						=> $condition_fpage_intro_expertise, // string
					'treatment_fpage_title_expertise'						=> $treatment_fpage_title_expertise, // string
					'treatment_fpage_intro_expertise'						=> $treatment_fpage_intro_expertise, // string
					'condition_treatment_fpage_title_expertise'				=> $condition_treatment_fpage_title_expertise, // string
					'condition_treatment_fpage_intro_expertise'				=> $condition_treatment_fpage_intro_expertise // string
				);
				return $fpage_text_expertise_vars;

		}

		// Get field values for text elements for related ontology sections in a Clinical Resource subsection (or profile)
		function uamswp_fad_fpage_text_clinical_resource(
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Providers Fake Subpage (or Section)

				$provider_fpage_title_clinical_resource = get_field('provider_fpage_title_clinical_resource', 'option'); // Title of Section for Providers on Clinical Resource Profile
				$provider_fpage_intro_clinical_resource = get_field('provider_fpage_intro_clinical_resource', 'option'); // Intro Text of Section for Providers on Clinical Resource Profile
				$provider_fpage_ref_main_title_clinical_resource = get_field('provider_fpage_ref_main_title_clinical_resource', 'option'); // Reference to the main provider archive, title
				$provider_fpage_ref_main_intro_clinical_resource = get_field('provider_fpage_ref_main_intro_clinical_resource', 'option'); // Reference to the main provider archive, body text
				$provider_fpage_ref_main_link_clinical_resource = get_field('provider_fpage_ref_main_link_clinical_resource', 'option'); // Reference to the main provider archive, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($provider_fpage_title_clinical_resource) || empty($provider_fpage_title_clinical_resource) ) {
						$provider_fpage_title_clinical_resource = ''; // Title
					}
					if ( !isset($provider_fpage_intro_clinical_resource) || empty($provider_fpage_intro_clinical_resource) ) {
						$provider_fpage_intro_clinical_resource = ''; // Intro text
					}
					if ( !isset($provider_fpage_ref_main_title_clinical_resource) || empty($provider_fpage_ref_main_title_clinical_resource) ) {
						$provider_fpage_ref_main_title_clinical_resource = ''; // Reference to the main provider archive, title
					}
					if ( !isset($provider_fpage_ref_main_intro_clinical_resource) || empty($provider_fpage_ref_main_intro_clinical_resource) ) {
						$provider_fpage_ref_main_intro_clinical_resource = ''; // Reference to the main provider archive, body text
					}
					if ( !isset($provider_fpage_ref_main_link_clinical_resource) || empty($provider_fpage_ref_main_link_clinical_resource) ) {
						$provider_fpage_ref_main_link_clinical_resource = ''; // Reference to the main provider archive, link text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement of a provider subpage/section

					if (
						!isset($provider_fpage_title_clinical_resource) || empty($provider_fpage_title_clinical_resource)
						||
						!isset($provider_fpage_intro_clinical_resource) || empty($provider_fpage_intro_clinical_resource)
						||
						!isset($provider_fpage_ref_main_title_clinical_resource) || empty($provider_fpage_ref_main_title_clinical_resource)
						||
						!isset($provider_fpage_ref_main_intro_clinical_resource) || empty($provider_fpage_ref_main_intro_clinical_resource)
						||
						!isset($provider_fpage_ref_main_link_clinical_resource) || empty($provider_fpage_ref_main_link_clinical_resource)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($provider_fpage_title_general) || empty($provider_fpage_title_general)
							||
							!isset($provider_fpage_intro_general) || empty($provider_fpage_intro_general)
							||
							!isset($provider_fpage_ref_main_title_general) || empty($provider_fpage_ref_main_title_general)
							||
							!isset($provider_fpage_ref_main_intro_general) || empty($provider_fpage_ref_main_intro_general)
							||
							!isset($provider_fpage_ref_main_link_general) || empty($provider_fpage_ref_main_link_general)
							) {
							$fpage_text_provider_general_vars = isset($fpage_text_provider_general_vars) ? $fpage_text_provider_general_vars : uamswp_fad_fpage_text_provider_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$provider_fpage_title_general = $fpage_text_provider_general_vars['provider_fpage_title_general']; // string
								$provider_fpage_intro_general = $fpage_text_provider_general_vars['provider_fpage_intro_general']; // string
								$provider_fpage_ref_main_title_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_title_general']; // string
								$provider_fpage_ref_main_intro_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_intro_general']; // string
								$provider_fpage_ref_main_link_general = $fpage_text_provider_general_vars['provider_fpage_ref_main_link_general']; // string
						}
					}
					if ( !isset($provider_fpage_title_clinical_resource) || empty($provider_fpage_title_clinical_resource) ) {
						$provider_fpage_title_clinical_resource = $provider_fpage_title_general; // Title
					}
					if ( !isset($provider_fpage_intro_clinical_resource) || empty($provider_fpage_intro_clinical_resource) ) {
						$provider_fpage_intro_clinical_resource = $provider_fpage_intro_general; // Intro text
					}
					if ( !isset($provider_fpage_ref_main_title_clinical_resource) || empty($provider_fpage_ref_main_title_clinical_resource) ) {
						$provider_fpage_ref_main_title_clinical_resource = $provider_fpage_ref_main_title_general; // Reference to the main provider archive, title
					}
					if ( !isset($provider_fpage_ref_main_intro_clinical_resource) || empty($provider_fpage_ref_main_intro_clinical_resource) ) {
						$provider_fpage_ref_main_intro_clinical_resource = $provider_fpage_ref_main_intro_general; // Reference to the main provider archive, body text
					}
					if ( !isset($provider_fpage_ref_main_link_clinical_resource) || empty($provider_fpage_ref_main_link_clinical_resource) ) {
						$provider_fpage_ref_main_link_clinical_resource = $provider_fpage_ref_main_link_general; // Reference to the main provider archive, link text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$provider_fpage_title_clinical_resource = $provider_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($provider_fpage_title_clinical_resource, $page_titles) : ''; // Title
					$provider_fpage_intro_clinical_resource = $provider_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($provider_fpage_intro_clinical_resource, $page_titles) : ''; // Intro text
					$provider_fpage_ref_main_title_clinical_resource = $provider_fpage_ref_main_title_clinical_resource ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_title_clinical_resource, $page_titles) : ''; // Reference to the main provider archive, title
					$provider_fpage_ref_main_intro_clinical_resource = $provider_fpage_ref_main_intro_clinical_resource ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_intro_clinical_resource, $page_titles) : ''; // Reference to the main provider archive, body text
					$provider_fpage_ref_main_link_clinical_resource = $provider_fpage_ref_main_link_clinical_resource ? uamswp_fad_fpage_text_replace($provider_fpage_ref_main_link_clinical_resource, $page_titles) : ''; // Reference to the main provider archive, link text

			// Locations Fake Subpage (or Section)

				$location_fpage_title_clinical_resource = get_field('location_fpage_title_clinical_resource', 'option'); // Title of Section for Locations on Clinical Resource Profile
				$location_fpage_intro_clinical_resource = get_field('location_fpage_intro_clinical_resource', 'option'); // Intro Text of Section for Locations on Clinical Resource Profile
				$location_fpage_ref_main_title_clinical_resource = get_field('location_fpage_ref_main_title_clinical_resource', 'option'); // Reference to the main location archive, title
				$location_fpage_ref_main_intro_clinical_resource = get_field('location_fpage_ref_main_intro_clinical_resource', 'option'); // Reference to the main location archive, body text
				$location_fpage_ref_main_link_clinical_resource = get_field('location_fpage_ref_main_link_clinical_resource', 'option'); // Reference to the main location archive, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($location_fpage_title_clinical_resource) || empty($location_fpage_title_clinical_resource) ) {
						$location_fpage_title_clinical_resource = ''; // Title
					}
					if ( !isset($location_fpage_intro_clinical_resource) || empty($location_fpage_intro_clinical_resource) ) {
						$location_fpage_intro_clinical_resource = ''; // Intro text
					}
					if ( !isset($location_fpage_ref_main_title_clinical_resource) || empty($location_fpage_ref_main_title_clinical_resource) ) {
						$location_fpage_ref_main_title_clinical_resource = ''; // Reference to the main location archive, title
					}
					if ( !isset($location_fpage_ref_main_intro_clinical_resource) || empty($location_fpage_ref_main_intro_clinical_resource) ) {
						$location_fpage_ref_main_intro_clinical_resource = ''; // Reference to the main location archive, body text
					}
					if ( !isset($location_fpage_ref_main_link_clinical_resource) || empty($location_fpage_ref_main_link_clinical_resource) ) {
						$location_fpage_ref_main_link_clinical_resource = ''; // Reference to the main location archive, link text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if (
						!isset($location_fpage_title_clinical_resource) || empty($location_fpage_title_clinical_resource)
						||
						!isset($location_fpage_intro_clinical_resource) || empty($location_fpage_intro_clinical_resource)
						||
						!isset($location_fpage_ref_main_title_clinical_resource) || empty($location_fpage_ref_main_title_clinical_resource)
						||
						!isset($location_fpage_ref_main_intro_clinical_resource) || empty($location_fpage_ref_main_intro_clinical_resource)
						||
						!isset($location_fpage_ref_main_link_clinical_resource) || empty($location_fpage_ref_main_link_clinical_resource)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($location_fpage_title_general) || empty($location_fpage_title_general)
							||
							!isset($location_fpage_intro_general) || empty($location_fpage_intro_general)
							||
							!isset($location_fpage_ref_main_title_general) || empty($location_fpage_ref_main_title_general)
							||
							!isset($location_fpage_ref_main_intro_general) || empty($location_fpage_ref_main_intro_general)
							||
							!isset($location_fpage_ref_main_link_general) || empty($location_fpage_ref_main_link_general)
							) {
							$fpage_text_location_general_vars = isset($fpage_text_location_general_vars) ? $fpage_text_location_general_vars : uamswp_fad_fpage_text_location_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$location_fpage_title_general = $fpage_text_location_general_vars['location_fpage_title_general']; // string
								$location_fpage_intro_general = $fpage_text_location_general_vars['location_fpage_intro_general']; // string
								$location_fpage_ref_main_title_general = $fpage_text_location_general_vars['location_fpage_ref_main_title_general']; // string
								$location_fpage_ref_main_intro_general = $fpage_text_location_general_vars['location_fpage_ref_main_intro_general']; // string
								$location_fpage_ref_main_link_general = $fpage_text_location_general_vars['location_fpage_ref_main_link_general']; // string
						}
					}
					if ( !isset($location_fpage_title_clinical_resource) || empty($location_fpage_title_clinical_resource) ) {
						$location_fpage_title_clinical_resource = $location_fpage_title_general; // Title
					}
					if ( !isset($location_fpage_intro_clinical_resource) || empty($location_fpage_intro_clinical_resource) ) {
						$location_fpage_intro_clinical_resource = $location_fpage_intro_general; // Intro text
					}
					if ( !isset($location_fpage_ref_main_title_clinical_resource) || empty($location_fpage_ref_main_title_clinical_resource) ) {
						$location_fpage_ref_main_title_clinical_resource = $location_fpage_ref_main_title_general; // Reference to the main location archive, title
					}
					if ( !isset($location_fpage_ref_main_intro_clinical_resource) || empty($location_fpage_ref_main_intro_clinical_resource) ) {
						$location_fpage_ref_main_intro_clinical_resource = $location_fpage_ref_main_intro_general; // Reference to the main location archive, body text
					}
					if ( !isset($location_fpage_ref_main_link_clinical_resource) || empty($location_fpage_ref_main_link_clinical_resource) ) {
						$location_fpage_ref_main_link_clinical_resource = $location_fpage_ref_main_link_general; // Reference to the main location archive, link text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$location_fpage_title_clinical_resource = $location_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($location_fpage_title_clinical_resource, $page_titles) : ''; // Title
					$location_fpage_intro_clinical_resource = $location_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($location_fpage_intro_clinical_resource, $page_titles) : ''; // Intro text
					$location_fpage_ref_main_title_clinical_resource = $location_fpage_ref_main_title_clinical_resource ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_title_clinical_resource, $page_titles) : ''; // Reference to the main location archive, title
					$location_fpage_ref_main_intro_clinical_resource = $location_fpage_ref_main_intro_clinical_resource ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_intro_clinical_resource, $page_titles) : ''; // Reference to the main location archive, body text
					$location_fpage_ref_main_link_clinical_resource = $location_fpage_ref_main_link_clinical_resource ? uamswp_fad_fpage_text_replace($location_fpage_ref_main_link_clinical_resource, $page_titles) : ''; // Reference to the main location archive, link text

			// Areas of Expertise Fake Subpage (or Section)

				$expertise_fpage_title_clinical_resource = get_field('expertise_fpage_title_clinical_resource', 'option'); // Title of Section for Descendant Areas of Expertise Items on Clinical Resource Profile
				$expertise_fpage_intro_clinical_resource = get_field('expertise_fpage_intro_clinical_resource', 'option'); // Intro Text of Section for Areas of Expertise Items on Clinical Resource Profile
				$expertise_fpage_ref_main_title_clinical_resource = get_field('expertise_fpage_ref_main_title_clinical_resource', 'option'); // Reference to the main area of expertise archive, title
				$expertise_fpage_ref_main_intro_clinical_resource = get_field('expertise_fpage_ref_main_intro_clinical_resource', 'option'); // Reference to the main area of expertise archive, body text
				$expertise_fpage_ref_main_link_clinical_resource = get_field('expertise_fpage_ref_main_link_clinical_resource', 'option'); // Reference to the main area of expertise archive, link text

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($expertise_fpage_title_clinical_resource) || empty($expertise_fpage_title_clinical_resource) ) {
						$expertise_fpage_title_clinical_resource = ''; // Title
					}
					if ( !isset($expertise_fpage_intro_clinical_resource) || empty($expertise_fpage_intro_clinical_resource) ) {
						$expertise_fpage_intro_clinical_resource = ''; // Intro text
					}
					if ( !isset($expertise_fpage_ref_main_title_clinical_resource) || empty($expertise_fpage_ref_main_title_clinical_resource) ) {
						$expertise_fpage_ref_main_title_clinical_resource = ''; // Reference to the main area of expertise archive, title
					}
					if ( !isset($expertise_fpage_ref_main_intro_clinical_resource) || empty($expertise_fpage_ref_main_intro_clinical_resource) ) {
						$expertise_fpage_ref_main_intro_clinical_resource = ''; // Reference to the main area of expertise archive, body text
					}
					if ( !isset($expertise_fpage_ref_main_link_clinical_resource) || empty($expertise_fpage_ref_main_link_clinical_resource) ) {
						$expertise_fpage_ref_main_link_clinical_resource = ''; // Reference to the main area of expertise archive, link text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if (
						!isset($expertise_fpage_title_clinical_resource) || empty($expertise_fpage_title_clinical_resource)
						||
						!isset($expertise_fpage_intro_clinical_resource) || empty($expertise_fpage_intro_clinical_resource)
						||
						!isset($expertise_fpage_ref_main_title_clinical_resource) || empty($expertise_fpage_ref_main_title_clinical_resource)
						||
						!isset($expertise_fpage_ref_main_intro_clinical_resource) || empty($expertise_fpage_ref_main_intro_clinical_resource)
						||
						!isset($expertise_fpage_ref_main_link_clinical_resource) || empty($expertise_fpage_ref_main_link_clinical_resource)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($expertise_fpage_title_general) || empty($expertise_fpage_title_general)
							||
							!isset($expertise_fpage_intro_general) || empty($expertise_fpage_intro_general)
							||
							!isset($expertise_fpage_ref_main_title_general) || empty($expertise_fpage_ref_main_title_general)
							||
							!isset($expertise_fpage_ref_main_intro_general) || empty($expertise_fpage_ref_main_intro_general)
							||
							!isset($expertise_fpage_ref_main_link_general) || empty($expertise_fpage_ref_main_link_general)
							) {
							$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$expertise_fpage_title_general = $fpage_text_expertise_general_vars['expertise_fpage_title_general']; // string
								$expertise_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_intro_general']; // string
								$expertise_fpage_ref_main_title_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_title_general']; // string
								$expertise_fpage_ref_main_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_intro_general']; // string
								$expertise_fpage_ref_main_link_general = $fpage_text_expertise_general_vars['expertise_fpage_ref_main_link_general']; // string
						}
					}
					if ( !isset($expertise_fpage_title_clinical_resource) || empty($expertise_fpage_title_clinical_resource) ) {
						$expertise_fpage_title_clinical_resource = $expertise_fpage_title_general; // Title
					}
					if ( !isset($expertise_fpage_intro_clinical_resource) || empty($expertise_fpage_intro_clinical_resource) ) {
						$expertise_fpage_intro_clinical_resource = $expertise_fpage_intro_general; // Intro text
					}
					if ( !isset($expertise_fpage_ref_main_title_clinical_resource) || empty($expertise_fpage_ref_main_title_clinical_resource) ) {
						$expertise_fpage_ref_main_title_clinical_resource = $expertise_fpage_ref_main_title_general; // Reference to the main area of expertise archive, title
					}
					if ( !isset($expertise_fpage_ref_main_intro_clinical_resource) || empty($expertise_fpage_ref_main_intro_clinical_resource) ) {
						$expertise_fpage_ref_main_intro_clinical_resource = $expertise_fpage_ref_main_intro_general; // Reference to the main area of expertise archive, body text
					}
					if ( !isset($expertise_fpage_ref_main_link_clinical_resource) || empty($expertise_fpage_ref_main_link_clinical_resource) ) {
						$expertise_fpage_ref_main_link_clinical_resource = $expertise_fpage_ref_main_link_general; // Reference to the main area of expertise archive, link text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$expertise_fpage_title_clinical_resource = $expertise_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($expertise_fpage_title_clinical_resource, $page_titles) : ''; // Title
					$expertise_fpage_intro_clinical_resource = $expertise_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($expertise_fpage_intro_clinical_resource, $page_titles) : ''; // Intro text
					$expertise_fpage_ref_main_title_clinical_resource = $expertise_fpage_ref_main_title_clinical_resource ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_title_clinical_resource, $page_titles) : ''; // Reference to the main area of expertise archive, title
					$expertise_fpage_ref_main_intro_clinical_resource = $expertise_fpage_ref_main_intro_clinical_resource ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_intro_clinical_resource, $page_titles) : ''; // Reference to the main area of expertise archive, body text
					$expertise_fpage_ref_main_link_clinical_resource = $expertise_fpage_ref_main_link_clinical_resource ? uamswp_fad_fpage_text_replace($expertise_fpage_ref_main_link_clinical_resource, $page_titles) : ''; // Reference to the main area of expertise archive, link text

			// Clinical Resources Fake Subpage (or Section)

				$clinical_resource_fpage_title_clinical_resource = get_field('clinical_resource_fpage_title_clinical_resource', 'option'); // Title of Section for Clinical Resources on Clinical Resource Profile
				$clinical_resource_fpage_intro_clinical_resource = get_field('clinical_resource_fpage_intro_clinical_resource', 'option'); // Intro Text of Section for Clinical Resources on Clinical Resource Profile
				$clinical_resource_fpage_ref_main_title_clinical_resource = get_field('clinical_resource_fpage_ref_main_title_clinical_resource', 'option'); // Reference to the main clinical resource archive, title
				$clinical_resource_fpage_ref_main_intro_clinical_resource = get_field('clinical_resource_fpage_ref_main_intro_clinical_resource', 'option'); // Reference to the main clinical resource archive, body text
				$clinical_resource_fpage_ref_main_link_clinical_resource = get_field('clinical_resource_fpage_ref_main_link_clinical_resource', 'option'); // Reference to the main clinical resource archive, link text
				$clinical_resource_fpage_more_text_clinical_resource = get_field('clinical_resource_fpage_more_text_clinical_resource', 'option'); // "More" Intro Text of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)
				$clinical_resource_fpage_more_link_text_clinical_resource = get_field('clinical_resource_fpage_more_link_text_clinical_resource', 'option'); // "More" Link Text of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)
				$clinical_resource_fpage_more_link_descr_clinical_resource = get_field('clinical_resource_fpage_more_link_descr_clinical_resource', 'option'); // "More" Link Description of a Fake Subpage (or Section) for Related Clinical Resources in a Clinical Resources Subsection (or Profile)

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($clinical_resource_fpage_title_clinical_resource) || empty($clinical_resource_fpage_title_clinical_resource) ) {
						$clinical_resource_fpage_title_clinical_resource = ''; // Title
					}
					if ( !isset($clinical_resource_fpage_intro_clinical_resource) || empty($clinical_resource_fpage_intro_clinical_resource) ) {
						$clinical_resource_fpage_intro_clinical_resource = ''; // Intro text
					}
					if ( !isset($clinical_resource_fpage_ref_main_title_clinical_resource) || empty($clinical_resource_fpage_ref_main_title_clinical_resource) ) {
						$clinical_resource_fpage_ref_main_title_clinical_resource = ''; // Reference to the main clinical resource archive, title
					}
					if ( !isset($clinical_resource_fpage_ref_main_intro_clinical_resource) || empty($clinical_resource_fpage_ref_main_intro_clinical_resource) ) {
						$clinical_resource_fpage_ref_main_intro_clinical_resource = ''; // Reference to the main clinical resource archive, body text
					}
					if ( !isset($clinical_resource_fpage_ref_main_link_clinical_resource) || empty($clinical_resource_fpage_ref_main_link_clinical_resource) ) {
						$clinical_resource_fpage_ref_main_link_clinical_resource = ''; // Reference to the main clinical resource archive, link text
					}
					if ( !isset($clinical_resource_fpage_more_text_clinical_resource) || empty($clinical_resource_fpage_more_text_clinical_resource) ) {
						$clinical_resource_fpage_more_text_clinical_resource = ''; // "More" intro text
					}
					if ( !isset($clinical_resource_fpage_more_link_text_clinical_resource) || empty($clinical_resource_fpage_more_link_text_clinical_resource) ) {
						$clinical_resource_fpage_more_link_text_clinical_resource = ''; // "More" link text
					}
					if ( !isset($clinical_resource_fpage_more_link_descr_clinical_resource) || empty($clinical_resource_fpage_more_link_descr_clinical_resource) ) {
						$clinical_resource_fpage_more_link_descr_clinical_resource = ''; // "More" link description
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if (
						!isset($clinical_resource_fpage_title_clinical_resource) || empty($clinical_resource_fpage_title_clinical_resource)
						||
						!isset($clinical_resource_fpage_intro_clinical_resource) || empty($clinical_resource_fpage_intro_clinical_resource)
						||
						!isset($clinical_resource_fpage_ref_main_title_clinical_resource) || empty($clinical_resource_fpage_ref_main_title_clinical_resource)
						||
						!isset($clinical_resource_fpage_ref_main_intro_clinical_resource) || empty($clinical_resource_fpage_ref_main_intro_clinical_resource)
						||
						!isset($clinical_resource_fpage_ref_main_link_clinical_resource) || empty($clinical_resource_fpage_ref_main_link_clinical_resource)
						||
						!isset($clinical_resource_fpage_more_text_clinical_resource) || empty($clinical_resource_fpage_more_text_clinical_resource)
						||
						!isset($clinical_resource_fpage_more_link_text_clinical_resource) || empty($clinical_resource_fpage_more_link_text_clinical_resource)
						||
						!isset($clinical_resource_fpage_more_link_descr_clinical_resource) || empty($clinical_resource_fpage_more_link_descr_clinical_resource)
						) {
						// If any of the variables are not set or are empty...

						// Check for the general placement variables.
						// If any aren't set or are empty, call the function and set the global variables.
						if (
							!isset($clinical_resource_fpage_title_general) || empty($clinical_resource_fpage_title_general)
							||
							!isset($clinical_resource_fpage_intro_general) || empty($clinical_resource_fpage_intro_general)
							||
							!isset($clinical_resource_fpage_ref_main_title_general) || empty($clinical_resource_fpage_ref_main_title_general)
							||
							!isset($clinical_resource_fpage_ref_main_intro_general) || empty($clinical_resource_fpage_ref_main_intro_general)
							||
							!isset($clinical_resource_fpage_ref_main_link_general) || empty($clinical_resource_fpage_ref_main_link_general)
							||
							!isset($clinical_resource_fpage_more_text_general) || empty($clinical_resource_fpage_more_text_general)
							||
							!isset($clinical_resource_fpage_more_link_text_general) || empty($clinical_resource_fpage_more_link_text_general)
							||
							!isset($clinical_resource_fpage_more_link_descr_general) || empty($clinical_resource_fpage_more_link_descr_general)
							) {
							$fpage_text_clinical_resource_general_vars = isset($fpage_text_clinical_resource_general_vars) ? $fpage_text_clinical_resource_general_vars : uamswp_fad_fpage_text_clinical_resource_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$clinical_resource_fpage_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_title_general']; // string
								$clinical_resource_fpage_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_intro_general']; // string
								$clinical_resource_fpage_ref_main_title_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_title_general']; // string
								$clinical_resource_fpage_ref_main_intro_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_intro_general']; // string
								$clinical_resource_fpage_ref_main_link_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_ref_main_link_general']; // string
								$clinical_resource_fpage_more_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_text_general']; // string
								$clinical_resource_fpage_more_link_text_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_text_general']; // string
								$clinical_resource_fpage_more_link_descr_general = $fpage_text_clinical_resource_general_vars['clinical_resource_fpage_more_link_descr_general']; // string
						}
					}
					if ( !isset($clinical_resource_fpage_title_clinical_resource) || empty($clinical_resource_fpage_title_clinical_resource) ) {
						$clinical_resource_fpage_title_clinical_resource = $clinical_resource_fpage_title_general; // Title
					}
					if ( !isset($clinical_resource_fpage_intro_clinical_resource) || empty($clinical_resource_fpage_intro_clinical_resource) ) {
						$clinical_resource_fpage_intro_clinical_resource = $clinical_resource_fpage_intro_general; // Intro text
					}
					if ( !isset($clinical_resource_fpage_ref_main_title_clinical_resource) || empty($clinical_resource_fpage_ref_main_title_clinical_resource) ) {
						$clinical_resource_fpage_ref_main_title_clinical_resource = $clinical_resource_fpage_ref_main_title_general; // Reference to the main clinical resource archive, title
					}
					if ( !isset($clinical_resource_fpage_ref_main_intro_clinical_resource) || empty($clinical_resource_fpage_ref_main_intro_clinical_resource) ) {
						$clinical_resource_fpage_ref_main_intro_clinical_resource = $clinical_resource_fpage_ref_main_intro_general; // Reference to the main clinical resource archive, body text
					}
					if ( !isset($clinical_resource_fpage_ref_main_link_clinical_resource) || empty($clinical_resource_fpage_ref_main_link_clinical_resource) ) {
						$clinical_resource_fpage_ref_main_link_clinical_resource = $clinical_resource_fpage_ref_main_link_general; // Reference to the main clinical resource archive, link text
					}
					if ( !isset($clinical_resource_fpage_more_text_clinical_resource) || empty($clinical_resource_fpage_more_text_clinical_resource) ) {
						$clinical_resource_fpage_more_text_clinical_resource = $clinical_resource_fpage_more_text_general; // "More" intro text
					}
					if ( !isset($clinical_resource_fpage_more_link_text_clinical_resource) || empty($clinical_resource_fpage_more_link_text_clinical_resource) ) {
						$clinical_resource_fpage_more_link_text_clinical_resource = $clinical_resource_fpage_more_link_text_general; // "More" link text
					}
					if ( !isset($clinical_resource_fpage_more_link_descr_clinical_resource) || empty($clinical_resource_fpage_more_link_descr_clinical_resource) ) {
						$clinical_resource_fpage_more_link_descr_clinical_resource = $clinical_resource_fpage_more_link_descr_general; // "More" link description
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$clinical_resource_fpage_title_clinical_resource = $clinical_resource_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_title_clinical_resource, $page_titles) : ''; // Title
					$clinical_resource_fpage_intro_clinical_resource = $clinical_resource_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_intro_clinical_resource, $page_titles) : ''; // Intro text
					$clinical_resource_fpage_ref_main_title_clinical_resource = $clinical_resource_fpage_ref_main_title_clinical_resource ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_title_clinical_resource, $page_titles) : ''; // Reference to the main clinical resource archive, title
					$clinical_resource_fpage_ref_main_intro_clinical_resource = $clinical_resource_fpage_ref_main_intro_clinical_resource ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_intro_clinical_resource, $page_titles) : ''; // Reference to the main clinical resource archive, body text
					$clinical_resource_fpage_ref_main_link_clinical_resource = $clinical_resource_fpage_ref_main_link_clinical_resource ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_ref_main_link_clinical_resource, $page_titles) : ''; // Reference to the main clinical resource archive, link text
					$clinical_resource_fpage_more_text_clinical_resource = $clinical_resource_fpage_more_text_clinical_resource ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_text_clinical_resource, $page_titles) : ''; // "More" intro text
					$clinical_resource_fpage_more_link_text_clinical_resource = $clinical_resource_fpage_more_link_text_clinical_resource ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_link_text_clinical_resource, $page_titles) : ''; // "More" link text
					$clinical_resource_fpage_more_link_descr_clinical_resource = $clinical_resource_fpage_more_link_descr_clinical_resource ? uamswp_fad_fpage_text_replace($clinical_resource_fpage_more_link_descr_clinical_resource, $page_titles) : ''; // "More" link description

			// Conditions Fake Subpage (or Section)

				$condition_fpage_title_clinical_resource = get_field('conditions_fpage_title_clinical_resource', 'option'); // Title of Section for Conditions on Clinical Resource Profile
				$condition_fpage_intro_clinical_resource = get_field('conditions_fpage_intro_clinical_resource', 'option'); // Intro Text of Section for Conditions on Clinical Resource Profile

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($condition_fpage_title_clinical_resource) || empty($condition_fpage_title_clinical_resource) ) {
						$condition_fpage_title_clinical_resource = ''; // Title
					}
					if ( !isset($condition_fpage_intro_clinical_resource) || empty($condition_fpage_intro_clinical_resource) ) {
						$condition_fpage_intro_clinical_resource = ''; // Intro text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if ( !isset($condition_fpage_title_clinical_resource) || empty($condition_fpage_title_clinical_resource) ) {
						if ( !isset($condition_fpage_title_general) || empty($condition_fpage_title_general) ) {
							$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_fpage_title_general = $fpage_text_condition_general_vars['condition_fpage_title_general']; // string
						}
						$condition_fpage_title_clinical_resource = $condition_fpage_title_general; // Title
					}
					if ( !isset($condition_fpage_intro_clinical_resource) || empty($condition_fpage_intro_clinical_resource) ) {
						if ( !isset($condition_fpage_intro_general) || empty($condition_fpage_intro_general) ) {
							$fpage_text_condition_general_vars = isset($fpage_text_condition_general_vars) ? $fpage_text_condition_general_vars : uamswp_fad_fpage_text_condition_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_fpage_intro_general = $fpage_text_condition_general_vars['condition_fpage_intro_general']; // string
						}
						$condition_fpage_intro_clinical_resource = $condition_fpage_intro_general; // Intro text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$condition_fpage_title_clinical_resource = $condition_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($condition_fpage_title_clinical_resource, $page_titles) : ''; // Title
					$condition_fpage_intro_clinical_resource = $condition_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($condition_fpage_intro_clinical_resource, $page_titles) : ''; // Intro text

			// Treatments Fake Subpage (or Section)

				$treatment_fpage_title_clinical_resource = get_field('treatments_fpage_title_clinical_resource', 'option'); // Title of Section for Treatments on Clinical Resource Profile
				$treatment_fpage_intro_clinical_resource = get_field('treatments_fpage_intro_clinical_resource', 'option'); // Intro Text of Section for Treatments on Clinical Resource Profile

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($treatment_fpage_title_clinical_resource) || empty($treatment_fpage_title_clinical_resource) ) {
						$treatment_fpage_title_clinical_resource = ''; // Title
					}
					if ( !isset($treatment_fpage_intro_clinical_resource) || empty($treatment_fpage_intro_clinical_resource) ) {
						$treatment_fpage_intro_clinical_resource = ''; // Intro text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if ( !isset($treatment_fpage_title_clinical_resource) || empty($treatment_fpage_title_clinical_resource) ) {
						if ( !isset($treatment_fpage_title_general) || empty($treatment_fpage_title_general) ) {
							$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$treatment_fpage_title_general = $fpage_text_treatment_general_vars['treatment_fpage_title_general']; // string
						}
						$treatment_fpage_title_clinical_resource = $treatment_fpage_title_general; // Title
					}
					if ( !isset($treatment_fpage_intro_clinical_resource) || empty($treatment_fpage_intro_clinical_resource) ) {
						if ( !isset($treatment_fpage_intro_general) || empty($treatment_fpage_intro_general) ) {
							$fpage_text_treatment_general_vars = isset($fpage_text_treatment_general_vars) ? $fpage_text_treatment_general_vars : uamswp_fad_fpage_text_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$treatment_fpage_intro_general = $fpage_text_treatment_general_vars['treatment_fpage_intro_general']; // string
						}
						$treatment_fpage_intro_clinical_resource = $treatment_fpage_intro_general; // Intro text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$treatment_fpage_title_clinical_resource = $treatment_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($treatment_fpage_title_clinical_resource, $page_titles) : ''; // Title
					$treatment_fpage_intro_clinical_resource = $treatment_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($treatment_fpage_intro_clinical_resource, $page_titles) : ''; // Intro text

			// Combined Conditions and Treatments Fake Subpage (or Section)

				$condition_treatment_fpage_title_clinical_resource = get_field('condition_treatment_fpage_title_clinical_resource', 'option'); // Title of Section for Conditions and Treatments Combined on Clinical Resource Profile
				$condition_treatment_fpage_intro_clinical_resource = get_field('condition_treatment_fpage_intro_clinical_resource', 'option'); // Intro Text of Section for Conditions and Treatments Combined on Clinical Resource Profile

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value

					if ( !isset($condition_treatment_fpage_title_clinical_resource) || empty($condition_treatment_fpage_title_clinical_resource) ) {
						$condition_treatment_fpage_title_clinical_resource = ''; // Title
					}
					if ( !isset($condition_treatment_fpage_intro_clinical_resource) || empty($condition_treatment_fpage_intro_clinical_resource) ) {
						$condition_treatment_fpage_intro_clinical_resource = ''; // Intro text
					}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for general placement

					if ( !isset($condition_treatment_fpage_title_clinical_resource) || empty($condition_treatment_fpage_title_clinical_resource) ) {
						if ( !isset($condition_treatment_fpage_title_general) || empty($condition_treatment_fpage_title_general) ) {
							$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_treatment_fpage_title_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_title_general']; // string
						}
						$condition_treatment_fpage_title_clinical_resource = $condition_treatment_fpage_title_general; // Title
					}
					if ( !isset($condition_treatment_fpage_intro_clinical_resource) || empty($condition_treatment_fpage_intro_clinical_resource) ) {
						if ( !isset($condition_treatment_fpage_intro_general) || empty($condition_treatment_fpage_intro_general) ) {
							$fpage_text_condition_treatment_general_vars = isset($fpage_text_condition_treatment_general_vars) ? $fpage_text_condition_treatment_general_vars : uamswp_fad_fpage_text_condition_treatment_general(
								$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
							);
								$condition_treatment_fpage_intro_general = $fpage_text_condition_treatment_general_vars['condition_treatment_fpage_intro_general']; // string
						}
						$condition_treatment_fpage_intro_clinical_resource = $condition_treatment_fpage_intro_general; // Intro text
					}

				// Substitute placeholder text for relevant Find-a-Doc Settings value

					$condition_treatment_fpage_title_clinical_resource = $condition_treatment_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_title_clinical_resource, $page_titles) : ''; // Title
					$condition_treatment_fpage_intro_clinical_resource = $condition_treatment_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_intro_clinical_resource, $page_titles) : ''; // Intro text

			// Create and return an array to be used on the templates and template parts

				$fpage_text_clinical_resource_vars = array(
					'provider_fpage_title_clinical_resource'					=> $provider_fpage_title_clinical_resource, // string
					'provider_fpage_intro_clinical_resource'					=> $provider_fpage_intro_clinical_resource, // string
					'provider_fpage_ref_main_title_clinical_resource'			=> $provider_fpage_ref_main_title_clinical_resource, // string
					'provider_fpage_ref_main_intro_clinical_resource'			=> $provider_fpage_ref_main_intro_clinical_resource, // string
					'provider_fpage_ref_main_link_clinical_resource'			=> $provider_fpage_ref_main_link_clinical_resource, // string
					'provider_fpage_ref_main_title_clinical_resource'			=> $provider_fpage_ref_main_title_clinical_resource, // string
					'provider_fpage_ref_main_intro_clinical_resource'			=> $provider_fpage_ref_main_intro_clinical_resource, // string
					'provider_fpage_ref_main_link_clinical_resource'			=> $provider_fpage_ref_main_link_clinical_resource, // string
					'location_fpage_title_clinical_resource'					=> $location_fpage_title_clinical_resource, // string
					'location_fpage_intro_clinical_resource'					=> $location_fpage_intro_clinical_resource, // string
					'location_fpage_ref_main_title_clinical_resource'			=> $location_fpage_ref_main_title_clinical_resource, // string
					'location_fpage_ref_main_intro_clinical_resource'			=> $location_fpage_ref_main_intro_clinical_resource, // string
					'location_fpage_ref_main_link_clinical_resource'			=> $location_fpage_ref_main_link_clinical_resource, // string
					'expertise_fpage_title_clinical_resource'					=> $expertise_fpage_title_clinical_resource, // string
					'expertise_fpage_intro_clinical_resource'					=> $expertise_fpage_intro_clinical_resource, // string
					'expertise_fpage_ref_main_title_clinical_resource'			=> $expertise_fpage_ref_main_title_clinical_resource, // string
					'expertise_fpage_ref_main_intro_clinical_resource'			=> $expertise_fpage_ref_main_intro_clinical_resource, // string
					'expertise_fpage_ref_main_link_clinical_resource'			=> $expertise_fpage_ref_main_link_clinical_resource, // string
					'clinical_resource_fpage_title_clinical_resource'			=> $clinical_resource_fpage_title_clinical_resource, // string
					'clinical_resource_fpage_intro_clinical_resource'			=> $clinical_resource_fpage_intro_clinical_resource, // string
					'clinical_resource_fpage_ref_main_title_clinical_resource'	=> $clinical_resource_fpage_ref_main_title_clinical_resource, // string
					'clinical_resource_fpage_ref_main_intro_clinical_resource'	=> $clinical_resource_fpage_ref_main_intro_clinical_resource, // string
					'clinical_resource_fpage_ref_main_link_clinical_resource'	=> $clinical_resource_fpage_ref_main_link_clinical_resource, // string
					'clinical_resource_fpage_more_text_clinical_resource'		=> $clinical_resource_fpage_more_text_clinical_resource, // string
					'clinical_resource_fpage_more_link_text_clinical_resource'	=> $clinical_resource_fpage_more_link_text_clinical_resource, // string
					'clinical_resource_fpage_more_link_descr_clinical_resource'	=> $clinical_resource_fpage_more_link_descr_clinical_resource, // string
					'condition_fpage_title_clinical_resource'					=> $condition_fpage_title_clinical_resource, // string
					'condition_fpage_intro_clinical_resource'					=> $condition_fpage_intro_clinical_resource, // string
					'treatment_fpage_title_clinical_resource'					=> $treatment_fpage_title_clinical_resource, // string
					'treatment_fpage_intro_clinical_resource'					=> $treatment_fpage_intro_clinical_resource, // string
					'condition_treatment_fpage_title_clinical_resource'			=> $condition_treatment_fpage_title_clinical_resource, // string
					'condition_treatment_fpage_intro_clinical_resource'			=> $condition_treatment_fpage_intro_clinical_resource // string
				);
				return $fpage_text_clinical_resource_vars;

		}

// Define variables for Find-a-Doc Settings values regarding featured images of archive pages

	// Get the Find-a-Doc Settings value for provider archive page featured image
	function uamswp_fad_archive_image_provider() {

		// Get the Find-a-Doc Settings value for the featured image of the provider archive
		$provider_archive_image = get_field('provider_archive_featured_image', 'option'); // Featured image ID

		// If the variable is not set or is empty...
		// Set a hardcoded fallback value
		$provider_archive_image = ( isset($provider_archive_image) && !empty($provider_archive_image) ) ? $provider_archive_image : ''; // Featured image

		// Create and return an array to be used on the templates and template parts

			$archive_image_provider_vars = array(
				'provider_archive_image'	=> $provider_archive_image // int
			);
			return $archive_image_provider_vars;

	}

	// Get the Find-a-Doc Settings value for location archive page featured image
	function uamswp_fad_archive_image_location() {

		// Get the Find-a-Doc Settings value for the featured image of the location archive
		$location_archive_image = get_field('location_archive_featured_image', 'option'); // Featured image ID

		// If the variable is not set or is empty...
		// Set a hardcoded fallback value
		$location_archive_image = ( isset($location_archive_image) && !empty($location_archive_image) ) ? $location_archive_image : ''; // Featured image

		// Create and return an array to be used on the templates and template parts

			$archive_image_location_vars = array(
				'location_archive_image'	=> $location_archive_image // int
			);
			return $archive_image_location_vars;

	}

	// Get the Find-a-Doc Settings value for area of expertise archive page featured image
	function uamswp_fad_archive_image_expertise() {

		// Get the Find-a-Doc Settings value for the featured image of the expertise archive
		$expertise_archive_image = get_field('expertise_archive_featured_image', 'option'); // Featured image ID

		// If the variable is not set or is empty...
		// Set a hardcoded fallback value
		$expertise_archive_image = ( isset($expertise_archive_image) && !empty($expertise_archive_image) ) ? $expertise_archive_image : ''; // Featured image

		// Create and return an array to be used on the templates and template parts

			$archive_image_expertise_vars = array(
				'expertise_archive_image'	=> $expertise_archive_image // int
			);
			return $archive_image_expertise_vars;

	}

	// Get the Find-a-Doc Settings value for clinical resource archive page featured image
	function uamswp_fad_archive_image_clinical_resource() {

		// Get the Find-a-Doc Settings value for the featured image of the clinical resource archive
		$clinical_resource_archive_image = get_field('clinical_resource_archive_featured_image', 'option'); // Featured image ID

		// If the variable is not set or is empty...
		// Set a hardcoded fallback value
		$clinical_resource_archive_image = ( isset($clinical_resource_archive_image) && !empty($clinical_resource_archive_image) ) ? $clinical_resource_archive_image : ''; // Featured image

		// Create and return an array to be used on the templates and template parts

			$archive_image_clinical_resource_vars = array(
				'clinical_resource_archive_image'	=> $clinical_resource_archive_image // int
			);
			return $archive_image_clinical_resource_vars;

	}

// Define variables for Find-a-Doc Settings values regarding the featured images of fake subpages and single profiles

	// Get the Find-a-Doc Settings values for the featured images of fake subpages (or sections) in general placements

		// Get the Find-a-Doc Settings value for the featured image of a fake subpage (or section) for Providers in general placements
		function uamswp_fad_fpage_image_provider_general() {

			// Get the Find-a-Doc Settings value
			$provider_fpage_image_general = get_field('provider_fpage_featured_image_general', 'option'); // Featured image ID

			// If the variable is not set or is empty...
			// Set a hardcoded fallback value
			$provider_fpage_image_general = ( isset($provider_fpage_image_general) && !empty($provider_fpage_image_general) ) ? $provider_fpage_image_general : ''; // Featured image

			// Create and return an array to be used on the templates and template parts

				$fpage_image_provider_general_vars = array(
					'provider_fpage_image_general'	=> $provider_fpage_image_general // int
				);
				return $fpage_image_provider_general_vars;

		}

		// Get the Find-a-Doc Settings values for the featured image of a fake subpage (or section) for Locations in general placements
		function uamswp_fad_fpage_image_location_general() {

			// Locations

				// Get the Find-a-Doc Settings value
				$location_fpage_image_general = get_field('location_fpage_featured_image_general', 'option'); // Featured image ID

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value
				$location_fpage_image_general = ( isset($location_fpage_image_general) && !empty($location_fpage_image_general) ) ? $location_fpage_image_general : ''; // Featured image ID

			// Descendant Locations

				// Get the Find-a-Doc Settings value
				$location_descendant_fpage_image_general = get_field('location_descendant_fpage_featured_image_general', 'option'); // Featured image ID

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value
				$location_descendant_fpage_image_general = ( isset($location_descendant_fpage_image_general) && !empty($location_descendant_fpage_image_general) ) ? $location_descendant_fpage_image_general : ''; // Featured image ID

			// Create and return an array to be used on the templates and template parts

				$fpage_image_location_general_vars = array(
					'location_fpage_image_general'				=> $location_fpage_image_general, // int
					'location_descendant_fpage_image_general'	=> $location_descendant_fpage_image_general // int
				);
				return $fpage_image_location_general_vars;

		}

		// Get the Find-a-Doc Settings values for the featured image of a fake subpage (or section) for Areas of Expertise in general placements
		function uamswp_fad_fpage_image_expertise_general() {

			// Areas of Expertise

				// Get the Find-a-Doc Settings value
				$expertise_fpage_image_general = get_field('expertise_fpage_featured_image_general', 'option'); // Featured image ID

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value
				$expertise_fpage_image_general = ( isset($expertise_fpage_image_general) && !empty($expertise_fpage_image_general) ) ? $expertise_fpage_image_general : ''; // Featured image ID

			// Descendant Areas of Expertise

				// Get the Find-a-Doc Settings value
				$expertise_descendant_fpage_image_general = get_field('expertise_descendant_fpage_featured_image_general', 'option'); // Featured image ID

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value
				$expertise_descendant_fpage_image_general = ( isset($expertise_descendant_fpage_image_general) && !empty($expertise_descendant_fpage_image_general) ) ? $expertise_descendant_fpage_image_general : ''; // Featured image ID

			// Create and return an array to be used on the templates and template parts

				$fpage_image_expertise_general_vars = array(
					'expertise_fpage_image_general'				=> $expertise_fpage_image_general, // int
					'expertise_descendant_fpage_image_general'	=> $expertise_descendant_fpage_image_general // int
				);
				return $fpage_image_expertise_general_vars;

		}

		// Get the Find-a-Doc Settings value for the featured image of a fake subpage (or section) for Clinical Resources in general placements
		function uamswp_fad_fpage_image_clinical_resource_general() {

			// Get the Find-a-Doc Settings value
			$clinical_resource_fpage_image_general = get_field('clinical_resource_fpage_featured_image_general', 'option'); // Featured image ID

			// If the variable is not set or is empty...
			// Set a hardcoded fallback value
			$clinical_resource_fpage_image_general = ( isset($clinical_resource_fpage_image_general) && !empty($clinical_resource_fpage_image_general) ) ? $clinical_resource_fpage_image_general : ''; // Featured image ID

			// Create and return an array to be used on the templates and template parts

				$fpage_image_clinical_resource_general_vars = array(
					'clinical_resource_fpage_image_general'	=> $clinical_resource_fpage_image_general // int
				);
				return $fpage_image_clinical_resource_general_vars;

		}

	// Get Find-a-Doc Settings values and page-level values for the featured images of specific subsections (or profiles)

		// Get field values for the featured image of a fake subpage (or section) in an Provider subsection (or profile)
		function uamswp_fad_fpage_image_provider() {

			// Locations

				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Provider subsection (or profile)
				$location_fpage_image_provider = get_field('location_fpage_featured_image_provider', 'option');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($location_fpage_image_provider) || empty($location_fpage_image_provider) ) {
					if ( !isset($location_fpage_image_general) || empty($location_fpage_image_general) ) {
						$fpage_image_location_general_vars = isset($fpage_image_location_general_vars) ? $fpage_image_location_general_vars : uamswp_fad_fpage_image_location_general();
							$location_fpage_image_general = $fpage_image_location_general_vars['location_fpage_image_general']; // int
					}
					$location_fpage_image_provider = $location_fpage_image_general; // Featured image
				}

			// Areas of Expertise

				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Provider subsection (or profile)
				$expertise_fpage_image_provider = get_field('expertise_fpage_featured_image_provider', 'option');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($expertise_fpage_image_provider) || empty($expertise_fpage_image_provider) ) {
					if ( !isset($expertise_fpage_image_general) || empty($expertise_fpage_image_general) ) {
						$fpage_image_expertise_general_vars = isset($fpage_image_expertise_general_vars) ? $fpage_image_expertise_general_vars : uamswp_fad_fpage_image_expertise_general();
							$expertise_fpage_image_general = $fpage_image_expertise_general_vars['expertise_fpage_image_general']; // int
					}
					$expertise_fpage_image_provider = $expertise_fpage_image_general; // Featured image
				}

			// Clinical Resources

				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Provider subsection (or profile)
				$clinical_resource_fpage_image_provider = get_field('clinical_resource_fpage_featured_image_provider', 'option');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($clinical_resource_fpage_image_provider) || empty($clinical_resource_fpage_image_provider) ) {
					if ( !isset($clinical_resource_fpage_image_general) || empty($clinical_resource_fpage_image_general) ) {
						$fpage_image_clinical_resource_general_vars = isset($fpage_image_clinical_resource_general_vars) ? $fpage_image_clinical_resource_general_vars : uamswp_fad_fpage_image_clinical_resource_general();
							$clinical_resource_fpage_image_general = $fpage_image_clinical_resource_general_vars['clinical_resource_fpage_image_general']; // int
					}
					$clinical_resource_fpage_image_provider = $clinical_resource_fpage_image_general; // Featured image
				}

			// Create and return an array to be used on the templates and template parts

				$fpage_image_provider_vars = array(
					'location_fpage_image_provider'				=> $location_fpage_image_provider, // int
					'expertise_fpage_image_provider'			=> $expertise_fpage_image_provider, // int
					'clinical_resource_fpage_image_provider'	=> $clinical_resource_fpage_image_provider // int
				);
				return $fpage_image_provider_vars;

		}

		// Get field values for the featured image of a fake subpage (or section) in an Location subsection (or profile)
		function uamswp_fad_fpage_image_location() {

			// Providers

				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Location subsection (or profile)
				$provider_fpage_image_location = get_field('provider_fpage_featured_image_location', 'option');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($provider_fpage_image_location) || empty($provider_fpage_image_location) ) {
					if ( !isset($provider_fpage_image_general) || empty($provider_fpage_image_general) ) {
						$fpage_image_provider_general_vars = isset($fpage_image_provider_general_vars) ? $fpage_image_provider_general_vars : uamswp_fad_fpage_image_provider_general();
							$provider_fpage_image_general = $fpage_image_provider_general_vars['provider_fpage_image_general']; // int
					}
					$provider_fpage_image_location = $provider_fpage_image_general; // Featured image
				}

			// Descendant Locations

				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Location subsection (or profile)
				$location_descendant_fpage_image_location = get_field('location_descendant_fpage_featured_image_location', 'option');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($location_descendant_fpage_image_location) || empty($location_descendant_fpage_image_location) ) {
					if ( !isset($location_descendant_fpage_image_general) || empty($location_descendant_fpage_image_general) ) {
						$fpage_image_location_general_vars = isset($fpage_image_location_general_vars) ? $fpage_image_location_general_vars : uamswp_fad_fpage_image_location_general();
							$location_descendant_fpage_image_general = $fpage_image_location_general_vars['location_descendant_fpage_image_general']; // int
					}
					$location_descendant_fpage_image_location = $location_descendant_fpage_image_general; // Featured image
				}

			// Areas of Expertise

				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Location subsection (or profile)
				$expertise_fpage_image_location = get_field('expertise_fpage_featured_image_location', 'option');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($expertise_fpage_image_location) || empty($expertise_fpage_image_location) ) {
					if ( !isset($expertise_fpage_image_general) || empty($expertise_fpage_image_general) ) {
						$fpage_image_expertise_general_vars = isset($fpage_image_expertise_general_vars) ? $fpage_image_expertise_general_vars : uamswp_fad_fpage_image_expertise_general();
							$expertise_fpage_image_general = $fpage_image_expertise_general_vars['expertise_fpage_image_general']; // int
					}
					$expertise_fpage_image_location = $expertise_fpage_image_general; // Featured image
				}

			// Clinical Resources

				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Location subsection (or profile)
				$clinical_resource_fpage_image_location = get_field('clinical_resource_fpage_featured_image_location', 'option');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($clinical_resource_fpage_image_location) || empty($clinical_resource_fpage_image_location) ) {
					if ( !isset($clinical_resource_fpage_image_general) || empty($clinical_resource_fpage_image_general) ) {
						$fpage_image_clinical_resource_general_vars = isset($fpage_image_clinical_resource_general_vars) ? $fpage_image_clinical_resource_general_vars : uamswp_fad_fpage_image_clinical_resource_general();
							$clinical_resource_fpage_image_general = $fpage_image_clinical_resource_general_vars['clinical_resource_fpage_image_general']; // int
					}
					$clinical_resource_fpage_image_location = $clinical_resource_fpage_image_general; // Featured image
				}

			// Create and return an array to be used on the templates and template parts

				$fpage_image_location_vars = array(
					'provider_fpage_image_location'				=> $provider_fpage_image_location, // int
					'location_descendant_fpage_image_location'	=> $location_descendant_fpage_image_location, // int
					'expertise_fpage_image_location'			=> $expertise_fpage_image_location, // int
					'clinical_resource_fpage_image_location'	=> $clinical_resource_fpage_image_location // int
				);
				return $fpage_image_location_vars;

		}

		// Get field values for the featured image of a fake subpage (or section) in an Area of Expertise subsection (or profile)
		function uamswp_fad_fpage_image_expertise() {

			// Overview

				// Get the page-level featured image value
				$expertise_featured_image = get_field('_thumbnail_id');

				// Crop/resize the image
				if ( $expertise_featured_image && function_exists( 'fly_add_image_size' ) ) {
					$expertise_featured_image_url = image_sizer($expertise_featured_image, 1600, 900, 'center', 'center');
				} elseif ( $expertise_featured_image_url ) {
					$expertise_featured_image_url = wp_get_attachment_url( $expertise_featured_image, 'aspect-16-9' );
				} else {
					$expertise_featured_image_url = '';
				}

			// Providers

				// Get the page-level featured image value for this type of fake subpage (or profile)
				$provider_fpage_featured_image_expertise = get_field('expertise_providers_fpage_featured_image');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile in an Area of Expertise subsection (or profile)
				if ( !isset($provider_fpage_featured_image_expertise) || empty($provider_fpage_featured_image_expertise) ) {
					$provider_fpage_featured_image_expertise = get_field('provider_fpage_featured_image_expertise', 'option');
				}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($provider_fpage_featured_image_expertise) || empty($provider_fpage_featured_image_expertise) ) {
					if ( !isset($provider_fpage_image_general) || empty($provider_fpage_image_general) ) {
						$fpage_image_provider_general_vars = isset($fpage_image_provider_general_vars) ? $fpage_image_provider_general_vars : uamswp_fad_fpage_image_provider_general();
							$provider_fpage_image_general = $fpage_image_provider_general_vars['provider_fpage_image_general']; // int
					}
					$provider_fpage_featured_image_expertise = $provider_fpage_image_general; // Featured image
				}

				// Crop/resize the image
				if ( $provider_fpage_featured_image_expertise && function_exists( 'fly_add_image_size' ) ) {
					$provider_fpage_featured_image_expertise_url = image_sizer($provider_fpage_featured_image_expertise, 1600, 900, 'center', 'center');
				} elseif ( $provider_fpage_featured_image_expertise_url ) {
					$provider_fpage_featured_image_expertise_url = wp_get_attachment_url( $provider_fpage_featured_image_expertise, 'aspect-16-9' );
				} else {
					$provider_fpage_featured_image_expertise_url = $expertise_featured_image_url;
				}

			// Locations

				// Get the page-level featured image value for this type of fake subpage (or profile)
				$location_fpage_featured_image_expertise = get_field('expertise_locations_fpage_featured_image');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile in an Area of Expertise subsection (or profile)
				if ( !isset($location_fpage_featured_image_expertise) || empty($location_fpage_featured_image_expertise) ) {
					$location_fpage_featured_image_expertise = get_field('location_fpage_featured_image_expertise', 'option');
				}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($location_fpage_featured_image_expertise) || empty($location_fpage_featured_image_expertise) ) {
					if ( !isset($location_descendant_fpage_image_general) || empty($location_descendant_fpage_image_general) ) {
						$fpage_image_location_general_vars = isset($fpage_image_location_general_vars) ? $fpage_image_location_general_vars : uamswp_fad_fpage_image_location_general();
							$location_descendant_fpage_image_general = $fpage_image_location_general_vars['location_descendant_fpage_image_general']; // int
					}
					$location_fpage_featured_image_expertise = $location_descendant_fpage_image_general; // Featured image
				}

				// Crop/resize the image
				if ( $location_fpage_featured_image_expertise && function_exists( 'fly_add_image_size' ) ) {
					$location_fpage_featured_image_expertise_url = image_sizer($location_fpage_featured_image_expertise, 1600, 900, 'center', 'center');
				} elseif ( $location_fpage_featured_image_expertise_url ) {
					$location_fpage_featured_image_expertise_url = wp_get_attachment_url( $location_fpage_featured_image_expertise, 'aspect-16-9' );
				} else {
					$location_fpage_featured_image_expertise_url = $expertise_featured_image_url;
				}

			// Related Areas of Expertise

				// Get the page-level featured image value for this type of fake subpage (or profile)
				$expertise_fpage_featured_image_expertise = get_field('expertise_associated_fpage_featured_image');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Area of Expertise subsection (or profile)
				if ( !isset($expertise_fpage_featured_image_expertise) || empty($expertise_fpage_featured_image_expertise) ) {
					$expertise_fpage_featured_image_expertise = get_field('expertise_fpage_featured_image_expertise', 'option');
				}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($expertise_fpage_featured_image_expertise) || empty($expertise_fpage_featured_image_expertise) ) {
					if ( !isset($expertise_fpage_image_general) || empty($expertise_fpage_image_general) ) {
						$fpage_image_expertise_general_vars = isset($fpage_image_expertise_general_vars) ? $fpage_image_expertise_general_vars : uamswp_fad_fpage_image_expertise_general();
							$expertise_fpage_image_general = $fpage_image_expertise_general_vars['expertise_fpage_image_general']; // int
					}
					$expertise_fpage_featured_image_expertise = $expertise_fpage_image_general; // Featured image
				}

				// Crop/resize the image
				if ( $expertise_fpage_featured_image_expertise && function_exists( 'fly_add_image_size' ) ) {
					$expertise_fpage_featured_image_expertise_url = image_sizer($expertise_fpage_featured_image_expertise, 1600, 900, 'center', 'center');
				} elseif ( $expertise_fpage_featured_image_expertise_url ) {
					$expertise_fpage_featured_image_expertise_url = wp_get_attachment_url( $expertise_fpage_featured_image_expertise, 'aspect-16-9' );
				} else {
					$expertise_fpage_featured_image_expertise_url = $expertise_featured_image_url;
				}

			// Descendant Areas of Expertise

				// Get the page-level featured image value for this type of fake subpage (or profile)
				$expertise_descendant_fpage_featured_image_expertise = get_field('expertise_descendant_fpage_featured_image');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Area of Expertise subsection (or profile)
				if ( !isset($expertise_descendant_fpage_featured_image_expertise) || empty($expertise_descendant_fpage_featured_image_expertise) ) {
					$expertise_descendant_fpage_featured_image_expertise = get_field('expertise_descendant_fpage_featured_image_expertise', 'option');
				}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($expertise_descendant_fpage_featured_image_expertise) || empty($expertise_descendant_fpage_featured_image_expertise) ) {
					if ( !isset($expertise_descendant_fpage_image_general) || empty($expertise_descendant_fpage_image_general) ) {
						$fpage_image_expertise_general_vars = isset($fpage_image_expertise_general_vars) ? $fpage_image_expertise_general_vars : uamswp_fad_fpage_image_expertise_general();
							$expertise_descendant_fpage_image_general = $fpage_image_expertise_general_vars['expertise_descendant_fpage_image_general']; // int
					}
					$expertise_descendant_fpage_featured_image_expertise = $expertise_descendant_fpage_image_general; // Featured image
				}

				// Crop/resize the image
				if ( $expertise_descendant_fpage_featured_image_expertise && function_exists( 'fly_add_image_size' ) ) {
					$expertise_descendant_fpage_featured_image_expertise_url = image_sizer($expertise_descendant_fpage_featured_image_expertise, 1600, 900, 'center', 'center');
				} elseif ( $expertise_descendant_fpage_featured_image_expertise ) {
					$expertise_descendant_fpage_featured_image_expertise_url = wp_get_attachment_url( $expertise_descendant_fpage_featured_image_expertise, 'aspect-16-9' );
				} else {
					$expertise_descendant_fpage_featured_image_expertise_url = $expertise_featured_image_url;
				}

			// Clinical Resources

				// Get the page-level featured image value for this type of fake subpage (or profile)
				$clinical_resource_fpage_featured_image_expertise = get_field('expertise_clinical_resources_fpage_featured_image');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Area of Expertise subsection (or profile)
				if ( !isset($clinical_resource_fpage_featured_image_expertise) || empty($clinical_resource_fpage_featured_image_expertise) ) {
					$expertise_descendant_fpage_featured_image_expertise = get_field('clinical_resource_fpage_featured_image_expertise', 'option');
				}

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($clinical_resource_fpage_featured_image_expertise) || empty($clinical_resource_fpage_featured_image_expertise) ) {
					if ( !isset($clinical_resource_fpage_image_general) || empty($clinical_resource_fpage_image_general) ) {
						$fpage_image_clinical_resource_general_vars = isset($fpage_image_clinical_resource_general_vars) ? $fpage_image_clinical_resource_general_vars : uamswp_fad_fpage_image_clinical_resource_general();
							$clinical_resource_fpage_image_general = $fpage_image_clinical_resource_general_vars['clinical_resource_fpage_image_general']; // int
					}
					$clinical_resource_fpage_featured_image_expertise = $clinical_resource_fpage_image_general; // Featured image
				}

				// Crop/resize the image
				if ( $clinical_resource_fpage_featured_image_expertise && function_exists( 'fly_add_image_size' ) ) {
					$clinical_resource_fpage_featured_image_expertise_url = image_sizer($clinical_resource_fpage_featured_image_expertise, 1600, 900, 'center', 'center');
				} elseif ( $clinical_resource_fpage_featured_image_expertise ) {
					$clinical_resource_fpage_featured_image_expertise_url = wp_get_attachment_url( $clinical_resource_fpage_featured_image_expertise, 'aspect-16-9' );
				} else {
					$clinical_resource_fpage_featured_image_expertise_url = $expertise_featured_image_url;
				}

			// Create and return an array to be used on the templates and template parts

				$fpage_image_expertise_vars = array(
					'expertise_featured_image'									=> $expertise_featured_image, // int
					'expertise_featured_image_url'								=> $expertise_featured_image_url, // string
					'provider_fpage_featured_image_expertise'					=> $provider_fpage_featured_image_expertise, // int
					'provider_fpage_featured_image_expertise_url'				=> $provider_fpage_featured_image_expertise_url, // string
					'location_fpage_featured_image_expertise'					=> $location_fpage_featured_image_expertise, // int
					'location_fpage_featured_image_expertise_url'				=> $location_fpage_featured_image_expertise_url, // string
					'expertise_fpage_featured_image_expertise'					=> $expertise_fpage_featured_image_expertise, // int
					'expertise_fpage_featured_image_expertise_url'				=> $expertise_fpage_featured_image_expertise_url, // string
					'expertise_descendant_fpage_featured_image_expertise'		=> $expertise_descendant_fpage_featured_image_expertise, // int
					'expertise_descendant_fpage_featured_image_expertise_url'	=> $expertise_descendant_fpage_featured_image_expertise_url, // string
					'clinical_resource_fpage_featured_image_expertise'			=> $clinical_resource_fpage_featured_image_expertise, // int
					'clinical_resource_fpage_featured_image_expertise_url'		=> $clinical_resource_fpage_featured_image_expertise_url, // string
				);
				return $fpage_image_expertise_vars;

		}

		// Get field values for the featured image of a fake subpage (or section) in a Clinical Resource subsection (or profile)
		function uamswp_fad_fpage_image_clinical_resource() {

			// Providers

				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in a Clinical Resource subsection (or profile)
				$provider_fpage_image_clinical_resource = get_field('provider_fpage_featured_image_clinical_resource', 'option');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($provider_fpage_image_clinical_resource) || empty($provider_fpage_image_clinical_resource) ) {
					if ( !isset($provider_fpage_image_general) || empty($provider_fpage_image_general) ) {
						$fpage_image_provider_general_vars = isset($fpage_image_provider_general_vars) ? $fpage_image_provider_general_vars : uamswp_fad_fpage_image_provider_general();
							$provider_fpage_image_general = $fpage_image_provider_general_vars['provider_fpage_image_general']; // int
					}
					$provider_fpage_image_clinical_resource = $provider_fpage_image_general; // Featured image
				}

			// Locations

				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in a Clinical Resource subsection (or profile)
				$location_fpage_image_clinical_resource = get_field('location_fpage_featured_image_clinical_resource', 'option');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($location_fpage_image_clinical_resource) || empty($location_fpage_image_clinical_resource) ) {
					if ( !isset($location_fpage_image_general) || empty($location_fpage_image_general) ) {
						$fpage_image_location_general_vars = isset($fpage_image_location_general_vars) ? $fpage_image_location_general_vars : uamswp_fad_fpage_image_location_general();
							$location_fpage_image_general = $fpage_image_location_general_vars['location_fpage_image_general']; // int
					}
					$location_fpage_image_clinical_resource = $location_fpage_image_general; // Featured image
				}

			// Areas of Expertise

				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in a Clinical Resource subsection (or profile)
				$expertise_fpage_image_clinical_resource = get_field('expertise_fpage_featured_image_clinical_resource', 'option');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($expertise_fpage_image_clinical_resource) || empty($expertise_fpage_image_clinical_resource) ) {
					if ( !isset($expertise_fpage_image_general) || empty($expertise_fpage_image_general) ) {
						$fpage_image_expertise_general_vars = isset($fpage_image_expertise_general_vars) ? $fpage_image_expertise_general_vars : uamswp_fad_fpage_image_expertise_general();
							$expertise_fpage_image_general = $fpage_image_expertise_general_vars['expertise_fpage_image_general']; // int
					}
					$expertise_fpage_image_clinical_resource = $expertise_fpage_image_general; // Featured image
				}

			// Related Clinical Resources

				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in a Clinical Resource subsection (or profile)
				$clinical_resource_fpage_image_clinical_resource = get_field('clinical_resource_fpage_featured_image_clinical_resource', 'option');

				// If the variable is not set or is empty...
				// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
				if ( !isset($clinical_resource_fpage_image_clinical_resource) || empty($clinical_resource_fpage_image_clinical_resource) ) {
					if ( !isset($clinical_resource_fpage_image_general) || empty($clinical_resource_fpage_image_general) ) {
						$fpage_image_clinical_resource_general_vars = isset($fpage_image_clinical_resource_general_vars) ? $fpage_image_clinical_resource_general_vars : uamswp_fad_fpage_image_clinical_resource_general();
							$clinical_resource_fpage_image_general = $fpage_image_clinical_resource_general_vars['clinical_resource_fpage_image_general']; // int
					}
					$clinical_resource_fpage_image_clinical_resource = $clinical_resource_fpage_image_general; // Featured image
				}

			// Create and return an array to be used on the templates and template parts

				$fpage_image_clinical_resource_vars = array(
					'provider_fpage_image_clinical_resource'			=> $provider_fpage_image_clinical_resource, // int
					'location_fpage_image_clinical_resource'			=> $location_fpage_image_clinical_resource, // int
					'expertise_fpage_image_clinical_resource'			=> $expertise_fpage_image_clinical_resource, // int
					'clinical_resource_fpage_image_clinical_resource'	=> $clinical_resource_fpage_image_clinical_resource // int
				);
				return $fpage_image_clinical_resource_vars;

		}

// Define the general maximum number of ontology items to display in a fake subpage (or section)

	// Define the general maximum number of items to display on a fake subpage (or section) for Clinical Resource
	function uamswp_fad_posts_per_page_clinical_resource_general() {

		// General maximum number of clinical resources to display on a fake subpage (-1, 4, 6, 8, 10 or 12)
		$clinical_resource_posts_per_page_fpage = -1;

		// General maximum number of clinical resources to display in a section on a single profile (-1, 4, 6, 8, 10 or 12)
		$clinical_resource_posts_per_page_section = 4;

		// Create and return an array to be used on the templates and template parts

			$posts_per_page_clinical_resource_general_vars = array(
				'clinical_resource_posts_per_page_fpage'	=> $clinical_resource_posts_per_page_fpage, // int
				'clinical_resource_posts_per_page_section'	=> $clinical_resource_posts_per_page_section // int
			);
			return $posts_per_page_clinical_resource_general_vars;

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

		// Defined on the template

			global $excerpt;

	$html = $excerpt;

	return $html;

}

// Get the Find-a-Doc Settings values for general patient appointment information
function uamswp_fad_appointment_patients() {

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

	// Create and return an array to be used on the templates and template parts

		$appointment_patients_vars = array(
			'appointment_patients_phone_number_new'				=> $appointment_patients_phone_number_new, // string
			'appointment_patients_phone_label_new'				=> $appointment_patients_phone_label_new, // string
			'appointment_patients_phone_label_new_attr'			=> $appointment_patients_phone_label_new_attr, // string
			'appointment_patients_phone_info_new'				=> $appointment_patients_phone_info_new, // string
			'appointment_patients_phone_number_existing'		=> $appointment_patients_phone_number_existing, // string
			'appointment_patients_phone_label_existing'			=> $appointment_patients_phone_label_existing, // string
			'appointment_patients_phone_label_existing_attr'	=> $appointment_patients_phone_label_existing_attr, // string
			'appointment_patients_phone_info_existing'			=> $appointment_patients_phone_info_existing, // string
			'appointment_patients_phone_number_both'			=> $appointment_patients_phone_number_both, // string
			'appointment_patients_phone_label_both'				=> $appointment_patients_phone_label_both, // string
			'appointment_patients_phone_label_both_attr'		=> $appointment_patients_phone_label_both_attr, // string
			'appointment_patients_phone_info_both'				=> $appointment_patients_phone_info_both, // string
			'appointment_patients_web_url_new'					=> $appointment_patients_web_url_new, // string
			'appointment_patients_web_label_new'				=> $appointment_patients_web_label_new, // string
			'appointment_patients_web_label_new_attr'			=> $appointment_patients_web_label_new_attr, // string
			'appointment_patients_web_info_new'					=> $appointment_patients_web_info_new, // string
			'appointment_patients_web_url_existing'				=> $appointment_patients_web_url_existing, // string
			'appointment_patients_web_label_existing'			=> $appointment_patients_web_label_existing, // string
			'appointment_patients_web_label_existing_attr'		=> $appointment_patients_web_label_existing_attr, // string
			'appointment_patients_web_info_existing'			=> $appointment_patients_web_info_existing, // string
			'appointment_patients_web_url_both'					=> $appointment_patients_web_url_both, // string
			'appointment_patients_web_label_both'				=> $appointment_patients_web_label_both, // string
			'appointment_patients_web_label_both_attr'			=> $appointment_patients_web_label_both_attr, // string
			'appointment_patients_web_info_both'				=> $appointment_patients_web_info_both // string
		);
		return $appointment_patients_vars;

}

// Get the Find-a-Doc Settings values for general patient referral information
function uamswp_fad_appointment_refer() {

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

	// Create and return an array to be used on the templates and template parts

		$appointment_refer_vars = array(
			'appointment_refer_phone_number'		=> $appointment_refer_phone_number, // string
			'appointment_refer_phone_label'			=> $appointment_refer_phone_label, // string
			'appointment_refer_phone_label_attr'	=> $appointment_refer_phone_label_attr, // string
			'appointment_refer_phone_info'			=> $appointment_refer_phone_info, // string
			'appointment_refer_fax_number'			=> $appointment_refer_fax_number, // string
			'appointment_refer_fax_label'			=> $appointment_refer_fax_label, // string
			'appointment_refer_fax_label_attr'		=> $appointment_refer_fax_label_attr, // string
			'appointment_refer_fax_info'			=> $appointment_refer_fax_info, // string
			'appointment_refer_web_url'				=> $appointment_refer_web_url, // string
			'appointment_refer_web_label'			=> $appointment_refer_web_label, // string
			'appointment_refer_web_label_attr'		=> $appointment_refer_web_label_attr, // string
			'appointment_refer_web_info'			=> $appointment_refer_web_info, // string
		);
		return $appointment_refer_vars;

}

// Get the Find-a-Doc Settings value for jump links (a.k.a. anchor links)
function uamswp_fad_labels_jump_links() {

	// Jump Links Section Title
	$fad_jump_links_title = get_field('fad_jump_links_title', 'option') ?: 'Content';

	// Create and return an array to be used on the templates and template parts

		$labels_jump_links_vars = array(
			'fad_jump_links_title'	=> $fad_jump_links_title // string
		);
		return $labels_jump_links_vars;

}

// Crop and resize images for Open Graph and Twitter
function uamswp_meta_image_resize( $page_image_id ) {

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
		$meta_twitter_image_width = $image_size['twitter']['width'];
		$meta_twitter_image_height = $image_size['twitter']['height'];
	} elseif ( $page_image_id ) {
		$meta_og_image = wp_get_attachment_url( $page_image_id, 'aspect-16-9' );
		$meta_og_image_width = image_get_intermediate_size( $page_image_id, 'aspect-16-9' )['width'];
		$meta_og_image_height = image_get_intermediate_size( $page_image_id, 'aspect-16-9' )['height'];
		$meta_twitter_image = $meta_og_image;
		$meta_twitter_image_width = $meta_og_image_width;
		$meta_twitter_image_height = $meta_og_image_height;
	} else {
		$meta_og_image = '';
		$meta_og_image_width = '';
		$meta_og_image_height = '';
		$meta_twitter_image = '';
		$meta_twitter_image_width = '';
		$meta_twitter_image_height = '';
	}

	// Create and return an array to be used on the templates and template parts

		$meta_image_resize_vars = array(
			'meta_og_image'				=> $meta_og_image, // string
			'meta_og_image_width'		=> $meta_og_image_width, // int
			'meta_og_image_height'		=> $meta_og_image_height, // int
			'meta_twitter_image'		=> $meta_twitter_image, // string
			'meta_twitter_image_width'	=> $meta_twitter_image_width, // int
			'meta_twitter_image_height'	=> $meta_twitter_image_height // int
		);
		return $meta_image_resize_vars;

}

// Construct Provider List Section
//     The template part included in this function can stand on its own. If the 
//     relevant page template is not built using hooks/functions, the include() 
//     is all that is necessary.
function uamswp_fad_section_provider(
	$providers, // int[] // Value of the related providers input (or $provider_descendants, List of this provider item's descendant items)
	$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	// $provider_section_more_link_key, // string
	// $provider_section_more_link_value, // string
	// $provider_descendants = '', // int[] (optional) // List of this provider item's descendant items
	// $hide_medical_ontology = false, // bool (optional) // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
	// $provider_schema = '', // string (optional) // Schema data
	// $provider_section_schema_query = false, // bool (optional) // Query for whether to add locations to schema
	$provider_section_show = false, // bool (optional) // Query for whether to show the provider section (or $provider_desecendant_section_show, Query for whether to show the descendant provider section)
	$ontology_type = true, // bool (optional) // Query for whether item is ontology type vs. content type
	// $provider_descendant_list = false, // bool (optional) // Query for whether this is a list of child provider items within a provider item
	// $content_placement = 'profile', // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
	// $site_nav_id = '', // int (optional) // ID of post that defines the subsection
	$provider_section_title = '', // string (optional) // Text to use for the section title
	$provider_section_intro = '', // string (optional) // Text to use for the section intro text
	// $provider_section_more_show = true, // bool (optional) // Query for whether to show the section that links to more items
	// $provider_section_more_text = '', // string (optional) // Text to use for the "more" intro text
	// $provider_section_more_link_text = '', // string (optional) // Text to use for the "more" link text
	// $provider_section_more_link_descr = '', // string (optional) // Text to use for the "more" link description
	// $provider_section_link_item = false, // bool (optional) // Query for whether to link the list items
	$provider_section_show_header = true, // bool (optional) // Query for whether to display the section header
	$provider_section_filter = true, // bool (optional) // Query for whether to add filter(s)
	$provider_section_filter_region = true, // bool (optional) // Query for whether to add region filter
	$provider_section_filter_title = true, // bool (optional) // Query for whether to add title filter
	$provider_section_collapse_list = true // bool (optional) // Query for whether to collapse the list of providers in the providers section
	// $provider_section_class = '', // string (optional) // Section class
	// $provider_section_id = 'providers' // string (optional) // Section ID
) {

	include( UAMS_FAD_PATH . '/templates/parts/section-list-provider.php' );

}

// Construct Location List Section
//     The template part included in this function can stand on its own. If the 
//     relevant page template is not built using hooks/functions, the include() 
//     is all that is necessary.
function uamswp_fad_section_location(
	$locations, // int[] // Value of the related locations input (or $location_descendants, List of this location item's descendant items)
	$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	// $location_section_more_link_key, // string
	// $location_section_more_link_value, // string
	// $location_descendants = '', // int[] (optional) // List of this location item's descendant items
	// $hide_medical_ontology = false, // bool (optional) // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
	// $schema_address = '', // string (optional) // Address schema data
	// $schema_telephone = '', // string (optional) // Telephone schema data
	$location_section_schema_query = false, // bool (optional) // Query for whether to add locations to schema
	$location_section_show = false, // bool (optional) // Query for whether to show the location section (or $location_desecendant_section_show, Query for whether to show the descendant location section)
	$ontology_type = true, // bool (optional) // Query for whether item is ontology type vs. content type
	$location_descendant_list = false, // bool (optional) // Query for whether this is a list of child locations within a location
	// $content_placement = 'profile', // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
	// $site_nav_id = '', // int (optional) // ID of post that defines the subsection
	$location_section_title = '', // string (optional) // Text to use for the section title
	$location_section_intro = '', // string (optional) // Text to use for the section intro text
	// $location_section_more_show = true, // bool (optional) // Query for whether to show the section that links to more items
	// $location_section_more_text = '', // string (optional) // Text to use for the "more" intro text
	// $location_section_more_link_text = '', // string (optional) // Text to use for the "more" link text
	// $location_section_more_link_descr = '', // string (optional) // Text to use for the "more" link description
	// $location_section_link_item = false, // bool (optional) // Query for whether to link the list items
	$location_section_show_header = true, // bool (optional) // Query for whether to display the section header
	$location_section_filter = true, // bool (optional) // Query for whether to add filter(s)
	$location_section_filter_region = true, // bool (optional) // Query for whether to add region filter
	$location_section_filter_title = true, // bool (optional) // Query for whether to add title filter
	$location_section_collapse_list = true // bool (optional) // Query for whether to collapse the list of locations in the locations section
	// $location_section_class = '', // string (optional) // Section class
	// $location_section_id = 'locations' // string (optional) // Section ID
) {

	include( UAMS_FAD_PATH . '/templates/parts/section-list-location.php' );

}

// Construct Area of Expertise List Section
//     The template part included in this function can stand on its own. If the 
//     relevant page template is not built using hooks/functions, the include() 
//     is all that is necessary.
function uamswp_fad_section_expertise(
	$expertises, // int[] // Value of the related areas of expertise input (or $expertise_descendants, List of this area of expertise item's descendant items)
	$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	// $expertise_section_more_link_key, // string
	// $expertise_section_more_link_value, // string
	// $expertise_descendants = '', // int[] (optional) // List of this area of expertise item's descendant items
	$hide_medical_ontology = false, // bool (optional) // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
	// $expertise_schema = '', // string (optional) // Schema data
	// $expertise_section_schema_query = false, // bool (optional) // Query for whether to add locations to schema
	$expertise_section_show = false, // bool (optional) // Query for whether to show the area of expertise section (or $expertise_desecendant_section_show, Query for whether to show the descendant area of expertise section)
	$ontology_type = true, // bool (optional) // Query for whether item is ontology type vs. content type
	$expertise_descendant_list = false, // bool (optional) // Query for whether this is a list of child areas of expertise within an area of expertise
	$content_placement = 'profile', // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
	$site_nav_id = '', // int (optional) // ID of post that defines the subsection
	$expertise_section_title = '', // string (optional) // Text to use for the section title
	$expertise_section_intro = '', // string (optional) // Text to use for the section intro text
	// $expertise_section_more_show = true, // bool (optional) // Query for whether to show the section that links to more items
	// $expertise_section_more_text = '', // string (optional) // Text to use for the "more" intro text
	// $expertise_section_more_link_text = '', // string (optional) // Text to use for the "more" link text
	// $expertise_section_more_link_descr = '', // string (optional) // Text to use for the "more" link description
	// $expertise_section_link_item = false, // bool (optional) // Query for whether to link the list items
	$expertise_section_show_header = true, // bool (optional) // Query whether to display the section header
	// $expertise_section_filter = true, // bool (optional) // Query for whether to add filter(s)
	// $expertise_section_filter_region = true, // bool (optional) // Query for whether to add region filter
	// $expertise_section_filter_title = true, // bool (optional) // Query for whether to add title filter
	$expertise_section_collapse_list = false, // bool (optional) // Query for whether to collapse the list of locations in the locations section
	$expertise_section_id = 'expertise', // string (optional) // Section ID
	$expertise_section_class = 'expertise-list' // string (optional) // Section class
) {

	include( UAMS_FAD_PATH . '/templates/parts/section-list-expertise.php' );

}

// Construct Clinical Resource List Section
//     The template part included in this function can stand on its own. If the 
//     relevant page template is not built using hooks/functions, the include() 
//     is all that is necessary.
function uamswp_fad_section_clinical_resource(
	$clinical_resources, // int[] // Value of the related clinical resources input (or $clinical_resource_descendants, List of this clinical resource item's descendant items)
	$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	$clinical_resource_section_more_link_key = '', // string (optional)
	$clinical_resource_section_more_link_value = '', // string (optional)
	// $clinical_resource_descendants = '', // int[] (optional) // List of this clinical resource item's descendant items
	// $hide_medical_ontology = false, // bool (optional) // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
	// $clinical_resource_schema = '', // string (optional) // Schema data
	// $clinical_resource_section_schema_query = false, // bool (optional) // Query for whether to add locations to schema
	$clinical_resource_section_show = false, // bool (optional) // Query for whether to show the clinical resource section (or $clinical_resource_descendant_section_show, Query for whether to show the descendant clinical resource section)
	$ontology_type = true, // bool (optional) // Query for whether item is ontology type vs. content type
	// $clinical_resource_descendant_list = false, // bool (optional) // Query for whether this is a list of child location items within a location item
	// $content_placement = 'profile', // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
	// $site_nav_id = '', // int (optional) // ID of post that defines the subsection
	$clinical_resource_section_title = '', // string (optional) // Text to use for the section title
	$clinical_resource_section_intro = '', // string (optional) // Text to use for the section intro text
	$clinical_resource_posts_per_page = '', // int (optional) // Maximum number of clinical resources to display (-1, 4, 6, 8, 10 or 12)
	$clinical_resource_section_more_show = true, // bool (optional) // Query for whether to show the section that links to more items
	$clinical_resource_section_more_text = '', // string (optional) // Text to use for the "more" intro text
	$clinical_resource_section_more_link_text = '', // string (optional) // Text to use for the "more" link text
	$clinical_resource_section_more_link_descr = '', // string (optional) // Text to use for the "more" link description
	// $clinical_resource_section_link_item = false, // bool (optional) // Query for whether to link the list items
	$clinical_resource_section_show_header = true, // bool (optional) // Query for whether to display the section header
	// $clinical_resource_section_filter = true, // bool (optional) // Query for whether to add filter(s)
	// $clinical_resource_section_filter_region = true, // bool (optional) // Query for whether to add region filter
	// $clinical_resource_section_filter_title = true, // bool (optional) // Query for whether to add title filter
	$clinical_resource_section_collapse_list = false, // bool (optional) // Query for whether to collapse the list of locations in the locations section
	$clinical_resource_section_class = '', // string (optional) // Section class
	$clinical_resource_section_id = 'related-resources' // string (optional) // Section ID
) {

	include( UAMS_FAD_PATH . '/templates/parts/section-list-clinical-resource.php' );

}

// Construct Condition List Section
//     The template part included in this function can stand on its own. If the 
//     relevant page template is not built using hooks/functions, the include() 
//     is all that is necessary.
function uamswp_fad_section_condition(
	$conditions_cpt, // int[] // Value of the related conditions input (or $condition_descendants, List of this condition item's descendant items)
	$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	// $condition_section_more_link_key, // string
	// $condition_section_more_link_value, // string
	// $condition_descendants = '', // int[] (optional) // List of this condition item's descendant items
	$hide_medical_ontology = false, // bool (optional) // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
	$schema_medical_specialty = '', // array (optional) // MedicalSpecialty Schema data
	// $condition_section_schema_query = false, // bool (optional) // Query for whether to add locations to schema
	$condition_section_show = false, // bool (optional) // Query for whether to show the conditions section (or $condition_desecendant_section_show, Query for whether to show the descendant condition section)
	$ontology_type = true, // bool (optional) // Query for whether item is ontology type vs. content type
	// $condition_descendant_list = false, // bool (optional) // Query for whether this is a list of child location items within a location item
	// $content_placement = 'profile', // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
	// $site_nav_id = '', // int (optional) // ID of post that defines the subsection
	$condition_section_title = '', // string (optional) // Text to use for the section title
	$condition_section_intro = '', // string (optional) // Text to use for the section intro text
	// $condition_section_more_show = true, // bool (optional) // Query for whether to show the section that links to more items
	// $condition_section_more_text = '', // string (optional) // Text to use for the "more" intro text
	// $condition_section_more_link_text = '', // string (optional) // Text to use for the "more" link text
	// $condition_section_more_link_descr = '', // string (optional) // Text to use for the "more" link description
	$condition_treatment_section_link_item = false, // bool (optional) // Query for whether to link the list items
	$condition_section_show_header = true, // bool (optional) // Query for whether to display the section header
	// $condition_section_filter = true, // bool (optional) // Query for whether to add filter(s)
	// $condition_section_filter_region = true, // bool (optional) // Query for whether to add region filter
	// $condition_section_filter_title = true, // bool (optional) // Query for whether to add title filter
	// $condition_section_collapse_list = true, // bool (optional) // Query for whether to collapse the list of providers in the providers section
	$condition_section_class = 'conditions-treatments', // string (optional) // Section class
	$condition_section_id = 'conditions' // string (optional) // Section ID
) {

	include( UAMS_FAD_PATH . '/templates/parts/section-list-condition.php' );

	$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();

	// Create and return an array to be used on the templates and template parts

		$section_condition_vars = array(
			'schema_medical_specialty'	=> $schema_medical_specialty // array
		);
		return $section_condition_vars;

}

// Construct Treatment List Section
//     The template part included in this function can stand on its own. If the 
//     relevant page template is not built using hooks/functions, the include() 
//     is all that is necessary.
function uamswp_fad_section_treatment(
	$treatments_cpt, // int[] // Value of the related treatments input (or $treatment_descendants, List of this treatment item's descendant items)
	$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	// $treatment_section_more_link_key, // string
	// $treatment_section_more_link_value, // string
	// $treatment_descendants = '', // int[] (optional) // List of this treatment item's descendant items
	$hide_medical_ontology = false, // bool (optional) // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
	$schema_medical_specialty = '', // array (optional) // MedicalSpecialty Schema data
	// $treatment_section_schema_query = false, // bool (optional) // Query for whether to add locations to schema
	$treatment_section_show = false, // bool (optional) // Query for whether to show the treatment section (or $treatment_desecendant_section_show, Query for whether to show the descendant treatment section)
	$ontology_type = true, // bool (optional) // Query for whether item is ontology type vs. content type
	// $treatment_descendant_list = false, // bool (optional) // Query for whether this is a list of child location items within a location item
	// $content_placement = 'profile', // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
	// $site_nav_id = '', // int (optional) // ID of post that defines the subsection
	$treatment_section_title = '', // string (optional) // Text to use for the section title
	$treatment_section_intro = '', // string (optional) // Text to use for the section intro text
	// $treatment_section_more_show = true, // bool (optional) // Query for whether to show the section that links to more items
	// $treatment_section_more_text = '', // string (optional) // Text to use for the "more" intro text
	// $treatment_section_more_link_text = '', // string (optional) // Text to use for the "more" link text
	// $treatment_section_more_link_descr = '', // string (optional) // Text to use for the "more" link description
	$condition_treatment_section_link_item = false, // bool (optional) // Query for whether to link the list items
	$treatment_section_show_header = true, // bool (optional) // Query for whether to display the section header
	// $treatment_section_filter = true, // bool (optional) // Query for whether to add filter(s)
	// $treatment_section_filter_region = true, // bool (optional) // Query for whether to add region filter
	// $treatment_section_filter_title = true, // bool (optional) // Query for whether to add title filter
	// $treatment_section_collapse_list = true, // bool (optional) // Query for whether to collapse the list of providers in the providers section
	$treatment_section_class = 'conditions-treatments', // string (optional) // Section class
	$treatment_section_id = 'treatments' // string (optional) // Section ID
) {

	include( UAMS_FAD_PATH . '/templates/parts/section-list-treatment.php' );

	$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();

	// Create and return an array to be used on the templates and template parts

		$section_treatment_vars = array(
			'schema_medical_specialty'	=> $schema_medical_specialty // array
		);
		return $section_treatment_vars;

}

// Construct Combined Condition and Treatment List Section List Section
//     The template part included in this function can stand on its own. If the 
//     relevant page template is not built using hooks/functions, the include() 
//     is all that is necessary.
function uamswp_fad_section_condition_treatment(
	$conditions_cpt, // int[] // Value of the related conditions input (or $condition_descendants, List of this condition item's descendant items)
	$treatments_cpt, // int[] // Value of the related treatments input (or $treatment_descendants, List of this treatment item's descendant items)
	$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	// $condition_treatment_section_more_link_key, // string
	// $condition_treatment_section_more_link_value, // string
	// $condition_descendants = '', // int[] (optional) // List of this condition item's descendant items
	// $treatment_descendants = '', // int[] (optional) // List of this treatment item's descendant items
	$hide_medical_ontology = false, // bool (optional) // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
	// $schema_medical_specialty = '', // array (optional) // MedicalSpecialty Schema data
	// $condition_treatment_section_schema_query = false, // bool (optional) // Query for whether to add locations to schema
	$condition_treatment_section_show = false, // bool (optional) // Query for whether to show the combined conditions and treatments section
	$condition_section_show = false, // bool (optional) // Query for whether to show the condition section (or $condition_desecendant_section_show, Query for whether to show the descendant condition section)
	$treatment_section_show = false, // bool (optional) // Query for whether to show the treatment section (or $treatment_desecendant_section_show, Query for whether to show the descendant treatment section)
	$ontology_type = true, // bool (optional) // Query for whether item is ontology type vs. content type
	// $condition_treatment_descendant_list = false, // bool (optional) // Query for whether this is a list of child location items within a location item
	// $content_placement = 'profile', // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
	// $site_nav_id = '', // int (optional) // ID of post that defines the subsection
	$condition_treatment_section_title = '', // string (optional) // Text to use for the section title
	$condition_treatment_section_intro = '', // string (optional) // Text to use for the section intro text
	$condition_section_title = '', // string (optional) // Text to use for the conditions subsection title
	$condition_section_intro = '', // string (optional) // Text to use for the conditions subsection intro text
	$treatment_section_title = '', // string (optional) // Text to use for the treatments subsection title
	$treatment_section_intro = '', // string (optional) // Text to use for the treatments subsection intro text
	// $condition_treatment_section_more_show = true, // bool (optional) // Query for whether to show the section that links to more items
	// $condition_treatment_section_more_text = '', // string (optional) // Text to use for the "more" intro text
	// $condition_treatment_section_more_link_text = '', // string (optional) // Text to use for the "more" link text
	// $condition_treatment_section_more_link_descr = '', // string (optional) // Text to use for the "more" link description
	$condition_treatment_section_link_item = false, // bool (optional) // Query for whether to link the list items
	$condition_treatment_section_show_header = true, // bool (optional) // Query for whether to display the section header
	$condition_section_show_header = true, // bool (optional) // Query for whether to display the conditions subsection header
	$treatment_section_show_header = true, // bool (optional) // Query for whether to display the treatments subsection header
	// $condition_treatment_section_filter = true, // bool (optional) // Query for whether to add filter(s)
	// $condition_treatment_section_filter_region = true, // bool (optional) // Query for whether to add region filter
	// $condition_treatment_section_filter_title = true, // bool (optional) // Query for whether to add title filter
	$condition_treatment_section_collapse_list = false, // bool (optional) // Query for whether to collapse the list of conditions and treatments
	$condition_treatment_section_class = 'conditions-treatments', // string (optional) // Section class
	$condition_treatment_section_id = 'conditions-treatments', // string (optional) // Section ID
	$condition_section_class = 'conditions', // string (optional) // Conditions subsection class
	$condition_section_id = 'conditions', // string (optional) // Conditions subsection ID
	$treatment_section_class = 'treatments', // string (optional) // Treatments subsection class
	$treatment_section_id = 'treatments' // string (optional) // Treatments subsection ID
) {

	include( UAMS_FAD_PATH . '/templates/parts/section-list-condition-treatment.php' );

	$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();

	// Create and return an array to be used on the templates and template parts

		$section_condition_treatment_vars = array(
			'schema_medical_specialty'	=> $schema_medical_specialty // array
		);
		return $section_condition_treatment_vars;

}

// Collect Values For Schema Data Properties

	// Add data to an array defining schema data for address or location
	function uamswp_schema_address(
		$schema_address = '', // array (optional) // Main address or location schema array
		$street_address = '', // string (optional) // The street address. For example, 1600 Amphitheatre Pkwy.
		$post_office_box_number = '', // string (optional) // The post office box number for PO box addresses.
		$address_locality = '', // string (optional) // The locality in which the street address is, and which is in the region. For example, Mountain View.
		$address_region = '', // string (optional) // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
		$postal_code = '', // string (optional) // The postal code. For example, 94043.
		$address_country = '', // string (optional) // The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.
		$name = '', // string (optional) // The name of the item.
		$telephone = '', // string (optional) // The telephone number.
		$fax_number = '' // string (optional) // The fax number.
	) {

		/* Example use:
		* 
		* 	// Address Schema Data
		* 
		* 		// Check/define the main address or location schema array
		* 		$schema_address = ( isset($schema_address) && is_array($schema_address) ) ? $schema_address : array();
		* 
		* 		// Add this location's details to the main address or location schema array
		* 		$schema_address = uamswp_schema_address(
		* 			$schema_address, // array (optional) // Main address or location schema array
		* 			'PostalAddress', // string (optional) // Schema type
		* 			$location_address_1 . ( $location_address_2_schema ? ' ' . $location_address_2_schema : '' ), // string (optional) // The street address. For example, 1600 Amphitheatre Pkwy.
		* 			'', // string (optional) // The post office box number for PO box addresses.
		* 			$location_city, // string (optional) // The locality in which the street address is, and which is in the region. For example, Mountain View.
		* 			$location_state, // string (optional) // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
		* 			$location_zip, // string (optional) // The postal code. For example, 94043.
		* 			'', // string (optional) // The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.
		* 			$location_title, // string (optional) // The name of the item.
		* 			$location_phone_format_dash, // string (optional) // The telephone number.
		* 			$location_fax_format_dash // string (optional) // The fax number.
		* 		);
		*/

		// Check/define variables
			
			$schema_address = ( isset($schema_address) && is_array($schema_address) ) ? $schema_address : array();
			$street_address = isset($street_address) ? $street_address : '';
			$post_office_box_number = isset($post_office_box_number) ? $post_office_box_number : '';
			$address_locality = isset($address_locality) ? $address_locality : '';
			$address_region = isset($address_region) ? $address_region : '';
			$postal_code = isset($postal_code) ? $postal_code : '';
			$address_country = ( isset($address_country) && !empty($address_country) ) ? $address_country : 'USA';
			$name = isset($name) ? $name : '';
			$telephone = isset($telephone) ? $telephone : '';
			$fax_number = isset($fax_number) ? $fax_number : '';

		// Create an array for this item

			$schema = array();

		// Add values to the array

			if ( $name ) {
				$schema['name'] = $name;
			}

			if ( $street_address ) {
				$schema['streetAddress'] = $street_address;
			}
			
			if ( $post_office_box_number ) {
				$schema['postOfficeBoxNumber'] = $post_office_box_number;
			}
			
			if ( $address_locality ) {
				$schema['addressLocality'] = $address_locality;
			}
			
			if ( $address_region ) {
				$schema['addressRegion'] = $address_region;
			}
			
			if ( $postal_code ) {
				$schema['postalCode'] = $postal_code;
			}
			
			if ( $address_country ) {
				$schema['addressCountry'] = $address_country;
			}
			
			if ( $telephone ) {
				$schema['telephone'] = $telephone;
			}
			
			if ( $fax_number ) {
				$schema['faxNumber'] = $fax_number;
			}

			if ( !empty($schema) ) {
				$schema = array('@type' => 'PostalAddress') + $schema;
			}

		// Add this item's array to the main address or location schema array

			if ( !empty($schema) ) {
				$schema_address[] = $schema;
			}

		// Return the main address or location schema array

			return $schema_address;

	}

	// Add data to an array defining schema data for medicalSpecialty
	function uamswp_schema_medical_specialty(
		$schema_medical_specialty = '', // array (optional) // Main medicalSpecialty schema array
		$name = '', // string (optional) // The name of the item.
		$url = '', // string (optional) // URL of the item.
		$alternate_name = '' // string (optional) // An alias for the item.
	) {

		/* Example use:
		* 
		* 	// MedicalSpecialty Schema Data
		* 
		* 		// Check/define the main medicalSpecialty schema array
		* 		$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();
		* 
		* 		// Add this location's details to the main medicalSpecialty schema array
		* 		$schema_medical_specialty = uamswp_schema_medical_specialty(
		* 			$schema_medical_specialty, // array (optional) // Main medicalSpecialty schema array
		* 			$condition_title_attr, // string (optional) // The name of the item.
		* 			$condition_url // string (optional) // URL of the item.
		* 		);
		*/

		// Check/define variables

			$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();
			$name = isset($name) ? $name : '';
			$url = isset($url) ? $url : '';
			$alternate_name = isset($alternate_name) ? $alternate_name : '';

		// Create an array for this item

			$schema = array();

		// Add values to the array

			if ( $name ) {
				$schema['name'] = $name;
			}
			
			if ( $url ) {
				$schema['url'] = $url;
			}
			
			if ( $alternate_name ) {
				$schema['alternateName'] = $alternate_name;
			}

			if ( !empty($schema) ) {
				$schema = array('@type' => 'MedicalSpecialty') + $schema;
			}

		// Add this item's array to the main address schema array

			if ( !empty($schema) ) {
				$schema_medical_specialty[] = $schema;
			}

		// Return the main address schema array

			return $schema_medical_specialty;

	}

	// Add data to an array defining schema data for faxNumber
	function uamswp_schema_fax_number(
		$schema_fax_number = '', // array (optional) // Main faxNumber schema array
		$fax_number = '' // string (optional) // The fax number.
	) {

		/* Example use:
		* 
		* 	// FaxNumber Schema Data
		* 
		* 		// Check/define the main faxNumber schema array
		* 		$schema_fax_number = ( isset($schema_fax_number) && is_array($schema_fax_number) && !empty($schema_fax_number) ) ? $schema_fax_number : array();
		* 
		* 		// Add this location's details to the main faxNumber schema array
		* 		$schema_fax_number = uamswp_schema_fax_number(
		* 			$schema_fax_number, // array (optional) // Main faxNumber schema array
		* 			$location_fax_format_dash // string (optional) // The fax number.
		* 		);
		*/

		// Check/define variables

			$schema_fax_number = ( isset($schema_fax_number) && is_array($schema_fax_number) && !empty($schema_fax_number) ) ? $schema_fax_number : array();
			$fax_number = isset($fax_number) ? $fax_number : '';

		// Add values to the main faxNumber schema array

			if ( $fax_number ) {
				$schema_fax_number[] = $fax_number;
			}

		// Return the main faxNumber schema array

			return $schema_fax_number;

	}

	// Add data to an array defining schema data for telephone
	function uamswp_schema_telephone(
		$schema_telephone = '', // array (optional) // Main telephone schema array
		$telephone_number = '' // string (optional) // The telephone number.
	) {

		/* Example use:
		* 
		* 	// Telephone Schema Data
		* 
		* 		// Check/define the main telephone schema array
		* 		$schema_telephone = ( isset($schema_telephone) && is_array($schema_telephone) && !empty($schema_telephone) ) ? $schema_telephone : array();
		* 
		* 		// Add this location's details to the main telephone schema array
		* 		$schema_telephone = uamswp_schema_telephone(
		* 			$schema_telephone, // array (optional) // Main telephone schema array
		* 			$telephone_number // string (optional) // The telephone number.
		* 		);
		*/

		// Check/define variables

			$schema_telephone = ( isset($schema_telephone) && is_array($schema_telephone) && !empty($schema_telephone) ) ? $schema_telephone : array();
			$telephone_number = isset($telephone_number) ? $telephone_number : '';

		// Add values to the main telephone schema array

			if ( $telephone_number ) {
				$schema_telephone[] = $telephone_number;
			}

		// Return the main telephone schema array

			return $schema_telephone;

	}

	// Add data to an array defining schema data for OpeningHoursSpecification
	function uamswp_schema_opening_hours_specification(
		$schema_opening_hours_specification = '', // array (optional) // Main OpeningHoursSpecification schema array
		$day_of_week = '', // array|string (optional) // The day of the week for which these opening hours are valid.
		$opens = '', // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		$closes = '', // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		$valid_from = '', // string (optional) // The date when the item becomes valid.
		$valid_through = '' // string (optional) // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
	) {

		/* Example use:
		* 
		* 	// OpeningHoursSpecification Schema Data
		* 
		* 		// Check/define the main OpeningHoursSpecification schema array
		* 		$schema_opening_hours_specification = ( isset($schema_opening_hours_specification) && is_array($schema_opening_hours_specification) && !empty($schema_opening_hours_specification) ) ? $schema_opening_hours_specification : array();
		* 
		* 		// Add this location's details to the main OpeningHoursSpecification schema array
		* 
		* 			// // Schema.org method: Add all days as an array under the dayOfWeek property
		* 			// // as documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
		* 
		* 			// 	$schema_opening_hours_specification = uamswp_schema_opening_hours_specification(
		* 			// 		$schema_opening_hours_specification, // array (optional) // Main OpeningHoursSpecification schema array
		* 			// 		$schema_day_of_week, // array|string (optional) // The day of the week for which these opening hours are valid.
		* 			// 		$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 			// 		$schema_closes, // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 			// 		$schema_valid_from, // string (optional) // The date when the item becomes valid.
		* 			// 		$schema_valid_through // string (optional) // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
		* 			// 	);
		* 
		* 			// Google method: Loop through all the days defined in the current Hours repeater row separately
		* 			// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
		* 
		* 				foreach ( $schema_day_of_week as $day) {
		* 					$schema_opening_hours_specification = uamswp_schema_opening_hours_specification(
		* 						$schema_opening_hours_specification, // array (optional) // Main OpeningHoursSpecification schema array
		* 						$day, // array|string (optional) // The day of the week for which these opening hours are valid.
		* 						$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 						$schema_closes, // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 						$schema_valid_from, // string (optional) // The date when the item becomes valid.
		* 						$schema_valid_through // string (optional) // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
		* 					);
		* 				}
		*/

		// Check/define variables

			$schema_opening_hours_specification = ( isset($schema_opening_hours_specification) && is_array($schema_opening_hours_specification) && !empty($schema_opening_hours_specification) ) ? $schema_opening_hours_specification : array();
			$day_of_week = isset($day_of_week) ? $day_of_week : array();
			$opens = isset($opens) ? $opens : '';
			$closes = isset($closes) ? $closes : '';
			$valid_from = isset($valid_from) ? $valid_from : '';
			$valid_through = isset($valid_through) ? $valid_through : '';

		// Create an array for this item

			$schema = array();

		// Add values to the array

			if ( $day_of_week ) {
				$schema['dayOfWeek'] = $day_of_week;
			}
			
			if ( $opens ) {
				$schema['opens'] = $opens;
			}
			
			if ( $closes ) {
				$schema['closes'] = $closes;
			}
			
			if ( $valid_from ) {
				$schema['validFrom'] = $valid_from;
			}
			
			if ( $valid_through ) {
				$schema['validThrough'] = $valid_through;
			}

			if ( !empty($schema) ) {
				$schema = array('@type' => 'OpeningHoursSpecification') + $schema;
			}

		// Add this item's array to the main openingHoursSpecification schema array

			if ( !empty($schema) ) {
				$schema_opening_hours_specification[] = $schema;
			}

		// Return the main address schema array

			return $schema_opening_hours_specification;

	}

	// Add data to an array defining schema data for OpeningHours
	function uamswp_schema_opening_hours(
		$schema_opening_hours = '', // array (optional) // Main OpeningHours schema array
		$day_of_week = '', // string (optional) // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
		$opens = '', // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		$closes = '' // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
	) {

		/* Example use:
		* 
		* 	// OpeningHours Schema Data
		* 
		* 		// Check/define the main OpeningHours schema array
		* 		$schema_opening_hours = ( isset($schema_opening_hours) && is_array($schema_opening_hours) && !empty($schema_opening_hours) ) ? $schema_opening_hours : array();
		* 
		* 		// Add this location's details to the main OpeningHours schema array
		* 
		* 			$schema_opening_hours = uamswp_schema_opening_hours(
		* 				$schema_opening_hours, // array (optional) // Main OpeningHours schema array
		* 				$schema_day_of_week, // string (optional) // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
		* 				$schema_opens, // string (optional) // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 				$schema_closes // string (optional) // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
		* 			);
		*/

		// Check/define variables

			$schema_opening_hours = ( isset($schema_opening_hours) && is_array($schema_opening_hours) && !empty($schema_opening_hours) ) ? $schema_opening_hours : array();
			$day_of_week = isset($day_of_week) ? $day_of_week : '';
			$opens = isset($opens) ? $opens : '';
			$closes = isset($closes) ? $closes : '';

		// Add values to the array

			if (
				$day_of_week
				&&
				$opens
				&&
				$closes
			) {
				$schema_opening_hours[] = $day_of_week . ' ' . $opens . '-' . $closes;
			}

		// Return the main address schema array

			return $schema_opening_hours;

	}

	// Add data to an array defining schema data for geo
	function uamswp_schema_geo(
		$schema_geo = '', // array (optional) // Main geo schema array
		$latitude = '', // string (optional) // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
		$longitude = '', // string (optional) // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
		$elevation = '' // string (optional) // The elevation of a location (WGS 84). Values may be of the form 'NUMBER UNIT_OF_MEASUREMENT' (e.g., '1,000 m', '3,200 ft') while numbers alone should be assumed to be a value in meters.
	) {

		/* Example use:
		 * 
		 * 	// Geo Schema Data
		 * 
		 * 		// Check/define the main geo schema array
		 * 		$schema_geo = ( isset($schema_geo) && is_array($schema_geo) && !empty($schema_geo) ) ? $schema_geo : array();
		 * 
		 * 		// Add this location's details to the main geo schema array
		 * 
		 * 			$schema_geo = uamswp_schema_geo(
		 * 				$schema_geo, // array (optional) // Main geo schema array
		 * 				$schema_latitude, // string (optional) // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
		 * 				$schema_longitude, // string (optional) // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
		 * 				$schema_elevation // string (optional) // The elevation of a location (WGS 84). Values may be of the form 'NUMBER UNIT_OF_MEASUREMENT' (e.g., '1,000 m', '3,200 ft') while numbers alone should be assumed to be a value in meters.
		 * 			);
		 */

		// Check/define variables

			$schema_geo = ( isset($schema_geo) && is_array($schema_geo) && !empty($schema_geo) ) ? $schema_geo : array();
			$latitude = isset($latitude) ? $latitude : '';
			$longitude = isset($longitude) ? $longitude : '';
			$elevation = isset($elevation) ? $elevation : '';

		// Create an array for this item

		$schema = array();

		// Add values to the array

			if ( $latitude ) {
				$schema['latitude'] = $latitude;
			}
			
			if ( $longitude ) {
				$schema['longitude'] = $longitude;
			}
			
			if ( $elevation ) {
				$schema['elevation'] = $elevation;
			}

			if ( !empty($schema) ) {
				$schema = array('@type' => 'GeoCoordinates') + $schema;
			}

		// Add this item's array to the main geo schema array

			if ( !empty($schema) ) {
				$schema_geo[] = $schema;
			}

		// Return the main geo schema array

			return $schema_geo;

	}

// Create array_is_list function that is available in PHP 8
if ( !function_exists('array_is_list') ) {
	function array_is_list(array $array): bool {
		if (empty($array)) {
			return true;
		}

		$current_key = 0;
		foreach ($array as $key => $noop) {
			if ($key !== $current_key) {
				return false;
			}
			++$current_key;
		}

		return true;
	}
}

// Construct UAMS Text & Image Overlay Block
function uamswp_section_text_image_overlay(
	$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	$text_image_overlay_id = '', // string (optional) // Section ID attribute value
	$text_image_overlay_row_0 = '', // array (optional) // Values for the first item
	$text_image_overlay_row_1 = '' // array (optional) // Values for the second item
) {

	include( UAMS_FAD_PATH . '/templates/parts/section_text-image-overlay.php' );

}

// Construct UAMS Text & Image Overlay Block on Ontology Fake Subpages
function uamswp_fad_fpage_text_image_overlay(
	$page_id, // int
	$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	$current_fpage = '', // string (optional) // Fake subpage slug
	$ontology_type = '', // bool (optional)
	$text_image_overlay_id = 'archives' // string (optional) // Section ID attribute value
) {

	// Check/define variables

		// Get IDs for Site Header

			if (
				!isset($page_top_level_query) || empty($page_top_level_query)
				||
				!isset($ancestors_ontology_farthest) || empty($ancestors_ontology_farthest)
			) {
				$ontology_site_values_vars = isset($ontology_site_values_vars) ? $ontology_site_values_vars : uamswp_fad_ontology_site_values(
					$page_id // int // ID of the post
				);
					$page_top_level_query = $ontology_site_values_vars['page_top_level_query']; // Get whether this fake subpage's parent item is the top-level item // bool
					$ancestors_ontology_farthest = $ontology_site_values_vars['ancestors_ontology_farthest']; // ID of the top-level ontology item ancestor of the current item // int
			}

		// Page Title

			if ( !isset($page_title) || empty($page_title) ) {
				$page_title = isset($page_titles['page_title']) ? $page_titles['page_title'] : get_the_title();
			}

		// Ontology Type

			if ( !isset($ontology_type) || empty($ontology_type) ) {
				$ontology_type = isset($ontology_type) ? $ontology_type : true;
			}

		// Values Specific to the Fake Subpage

			// Define whether to display the item linking to the parent archive in the overlay block
			$show_parent_archive = false;

			if ( $current_fpage == 'providers' ) {

				if ( !isset($provider_archive_image) || empty($provider_archive_image) ) {
					$archive_image_provider_vars = isset($archive_image_provider_vars) ? $archive_image_provider_vars : uamswp_fad_archive_image_provider();
						$provider_archive_image = $archive_image_provider_vars['provider_archive_image']; // int
				}

				if (
					!isset($provider_fpage_title_expertise) || empty($provider_fpage_title_expertise)
					||
					!isset($provider_fpage_ref_main_title_expertise) || empty($provider_fpage_ref_main_title_expertise)
					||
					!isset($provider_fpage_ref_main_intro_expertise) || empty($provider_fpage_ref_main_intro_expertise)
					||
					!isset($provider_fpage_ref_main_link_expertise) || empty($provider_fpage_ref_main_link_expertise)
					||
					!isset($provider_fpage_ref_top_title_expertise) || empty($provider_fpage_ref_top_title_expertise)
					||
					!isset($provider_fpage_ref_top_intro_expertise) || empty($provider_fpage_ref_top_intro_expertise)
					||
					!isset($provider_fpage_ref_top_link_expertise) || empty($provider_fpage_ref_top_link_expertise)
				) {
					$fpage_text_expertise_vars = isset($fpage_text_expertise_vars) ? $fpage_text_expertise_vars : uamswp_fad_fpage_text_expertise(
						$page_id, // int
						$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
						$ontology_type // bool
					);
						$provider_fpage_title_expertise = $fpage_text_expertise_vars['provider_fpage_title_expertise']; // string
						$provider_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['provider_fpage_ref_main_title_expertise']; // string
						$provider_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['provider_fpage_ref_main_intro_expertise']; // string
						$provider_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['provider_fpage_ref_main_link_expertise']; // string
						$provider_fpage_ref_top_title_expertise = $fpage_text_expertise_vars['provider_fpage_ref_top_title_expertise']; // string
						$provider_fpage_ref_top_intro_expertise = $fpage_text_expertise_vars['provider_fpage_ref_top_intro_expertise']; // string
						$provider_fpage_ref_top_link_expertise = $fpage_text_expertise_vars['provider_fpage_ref_top_link_expertise']; // string
				}
				
				// Create array of main archive attributes
				$text_image_overlay_main_archive = array(
					'heading'			=> $provider_fpage_ref_main_title_expertise, // Heading text, limited to 65 characters // str
					'body'				=> $provider_fpage_ref_main_intro_expertise, // Body text, limited to 280 characters // str
					'button_text'		=> $provider_fpage_ref_main_link_expertise, // Link text, limited to 27 characters // str
					'button_url'		=> get_post_type_archive_link('provider'), // Full URL // str
					'image'				=> $provider_archive_image // Background image ID // int
				);

				// Create array of top-level ontology ancestor fake subpage attributes
				$text_image_overlay_parent_archive = array(
					'heading'			=> $provider_fpage_ref_top_title_expertise, // Heading text, limited to 65 characters // str
					'body'				=> $provider_fpage_ref_top_intro_expertise, // Body text, limited to 280 characters // str
					'button_text'		=> $provider_fpage_ref_top_link_expertise, // Link text, limited to 27 characters // str
					'button_url'		=> trailingslashit(get_permalink($ancestors_ontology_farthest)) . user_trailingslashit('providers'), // Full URL // str
					'image'				=> get_post_thumbnail_id($ancestors_ontology_farthest) // Background image ID // int
				);

			} elseif ( $current_fpage == 'locations' ) {

				if ( !isset($location_archive_image) || empty($location_archive_image) ) {
					$archive_image_location_vars = isset($archive_image_location_vars) ? $archive_image_location_vars : uamswp_fad_archive_image_location();
						$location_archive_image = $archive_image_location_vars['location_archive_image']; // int
				}

				if (
					!isset($location_fpage_title_expertise) || empty($location_fpage_title_expertise)
					||
					!isset($location_fpage_ref_main_title_expertise) || empty($location_fpage_ref_main_title_expertise)
					||
					!isset($location_fpage_ref_main_intro_expertise) || empty($location_fpage_ref_main_intro_expertise)
					||
					!isset($location_fpage_ref_main_link_expertise) || empty($location_fpage_ref_main_link_expertise)
					||
					!isset($location_fpage_ref_top_title_expertise) || empty($location_fpage_ref_top_title_expertise)
					||
					!isset($location_fpage_ref_top_intro_expertise) || empty($location_fpage_ref_top_intro_expertise)
					||
					!isset($location_fpage_ref_top_link_expertise) || empty($location_fpage_ref_top_link_expertise)
				) {
					$fpage_text_expertise_vars = isset($fpage_text_expertise_vars) ? $fpage_text_expertise_vars : uamswp_fad_fpage_text_expertise(
						$page_id, // int
						$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
						$ontology_type // bool
					);
						$location_fpage_title_expertise = $fpage_text_expertise_vars['location_fpage_title_expertise']; // string
						$location_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['location_fpage_ref_main_title_expertise']; // string
						$location_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['location_fpage_ref_main_intro_expertise']; // string
						$location_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['location_fpage_ref_main_link_expertise']; // string
						$location_fpage_ref_top_title_expertise = $fpage_text_expertise_vars['location_fpage_ref_top_title_expertise']; // string
						$location_fpage_ref_top_intro_expertise = $fpage_text_expertise_vars['location_fpage_ref_top_intro_expertise']; // string
						$location_fpage_ref_top_link_expertise = $fpage_text_expertise_vars['location_fpage_ref_top_link_expertise']; // string
				}

				// Create array of main archive attributes
				$text_image_overlay_main_archive = array(
					'heading'			=> $location_fpage_ref_main_title_expertise, // Heading text, limited to 65 characters // str
					'body'				=> $location_fpage_ref_main_intro_expertise, // Body text, limited to 280 characters // str
					'button_text'		=> $location_fpage_ref_main_link_expertise, // Link text, limited to 27 characters // str
					'button_url'		=> get_post_type_archive_link('location'), // Full URL // str
					'image'				=> $location_archive_image // Background image ID // int
				);

				// Create array of top-level ontology ancestor fake subpage attributes
				$text_image_overlay_parent_archive = array(
					'heading'			=> $location_fpage_ref_top_title_expertise, // Heading text, limited to 65 characters // str
					'body'				=> $location_fpage_ref_top_intro_expertise, // Body text, limited to 280 characters // str
					'button_text'		=> $location_fpage_ref_top_link_expertise, // Link text, limited to 27 characters // str
					'button_url'		=> trailingslashit(get_permalink($ancestors_ontology_farthest)) . user_trailingslashit('locations'), // Full URL // str
					'image'				=> get_post_thumbnail_id($ancestors_ontology_farthest) // Background image ID // int
				);

			} elseif ( $current_fpage == 'specialties' ) {

				if ( !isset($expertise_archive_image) || empty($expertise_archive_image) ) {
					$archive_image_expertise_vars = isset($archive_image_expertise_vars) ? $archive_image_expertise_vars : uamswp_fad_archive_image_expertise();
						$expertise_archive_image = $archive_image_expertise_vars['expertise_archive_image']; // int
				}

				if (
					!isset($expertise_descendant_fpage_title_expertise) || empty($expertise_descendant_fpage_title_expertise)
					||
					!isset($expertise_descendant_fpage_ref_main_title_expertise) || empty($expertise_descendant_fpage_ref_main_title_expertise)
					||
					!isset($expertise_descendant_fpage_ref_main_intro_expertise) || empty($expertise_descendant_fpage_ref_main_intro_expertise)
					||
					!isset($expertise_descendant_fpage_ref_main_link_expertise) || empty($expertise_descendant_fpage_ref_main_link_expertise)
					) {
					$fpage_text_expertise_vars = isset($fpage_text_expertise_vars) ? $fpage_text_expertise_vars : uamswp_fad_fpage_text_expertise(
						$page_id, // int
						$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
						$ontology_type // bool
					);
						$expertise_descendant_fpage_title_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_title_expertise']; // string
						$expertise_descendant_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_ref_main_title_expertise']; // string
						$expertise_descendant_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_ref_main_intro_expertise']; // string
						$expertise_descendant_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['expertise_descendant_fpage_ref_main_link_expertise']; // string
				}

				// Create array of main archive attributes
				$text_image_overlay_main_archive = array(
					'heading'			=> $expertise_descendant_fpage_ref_main_title_expertise, // Heading text, limited to 65 characters // str
					'body'				=> $expertise_descendant_fpage_ref_main_intro_expertise, // Body text, limited to 280 characters // str
					'button_text'		=> $expertise_descendant_fpage_ref_main_link_expertise, // Link text, limited to 27 characters // str
					'button_url'		=> get_post_type_archive_link('expertise'), // Full URL // str
					'image'				=> $expertise_archive_image // Background image ID // int
				);

			} elseif ( $current_fpage == 'related' ) {

				if ( !isset($expertise_archive_image) || empty($expertise_archive_image) ) {
					$archive_image_expertise_vars = isset($archive_image_expertise_vars) ? $archive_image_expertise_vars : uamswp_fad_archive_image_expertise();
						$expertise_archive_image = $archive_image_expertise_vars['expertise_archive_image']; // int
				}

				if (
					!isset($expertise_fpage_title_expertise) || empty($expertise_fpage_title_expertise)
					||
					!isset($expertise_fpage_ref_main_title_expertise) || empty($expertise_fpage_ref_main_title_expertise)
					||
					!isset($expertise_fpage_ref_main_intro_expertise) || empty($expertise_fpage_ref_main_intro_expertise)
					||
					!isset($expertise_fpage_ref_main_link_expertise) || empty($expertise_fpage_ref_main_link_expertise)
					) {
					$fpage_text_expertise_vars = isset($fpage_text_expertise_vars) ? $fpage_text_expertise_vars : uamswp_fad_fpage_text_expertise(
						$page_id, // int
						$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
						$ontology_type // bool
					);
						$expertise_fpage_title_expertise = $fpage_text_expertise_vars['expertise_fpage_title_expertise']; // string
						$expertise_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['expertise_fpage_ref_main_title_expertise']; // string
						$expertise_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['expertise_fpage_ref_main_intro_expertise']; // string
						$expertise_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['expertise_fpage_ref_main_link_expertise']; // string
				}

				// Create array of main archive attributes
				$text_image_overlay_main_archive = array(
					'heading'			=> $expertise_fpage_ref_main_title_expertise, // Heading text, limited to 65 characters // str
					'body'				=> $expertise_fpage_ref_main_intro_expertise, // Body text, limited to 280 characters // str
					'button_text'		=> $expertise_fpage_ref_main_link_expertise, // Link text, limited to 27 characters // str
					'button_url'		=> get_post_type_archive_link('expertise'), // Full URL // str
					'image'				=> $expertise_archive_image // Background image ID // int
				);

			} elseif ( $current_fpage == 'resources' ) {

				// Define whether to display the item linking to the parent archive in the overlay block
				$show_parent_archive = true;

				if ( !isset($clinical_resource_archive_image) || empty($clinical_resource_archive_image) ) {
					$archive_image_clinical_resource_vars = isset($archive_image_clinical_resource_vars) ? $archive_image_clinical_resource_vars : uamswp_fad_archive_image_clinical_resource();
						$clinical_resource_archive_image = $archive_image_clinical_resource_vars['clinical_resource_archive_image']; // int
				}

				if (
					!isset($clinical_resource_fpage_title_expertise) || empty($clinical_resource_fpage_title_expertise)
					||
					!isset($clinical_resource_fpage_ref_main_title_expertise) || empty($clinical_resource_fpage_ref_main_title_expertise)
					||
					!isset($clinical_resource_fpage_ref_main_intro_expertise) || empty($clinical_resource_fpage_ref_main_intro_expertise)
					||
					!isset($clinical_resource_fpage_ref_main_link_expertise) || empty($clinical_resource_fpage_ref_main_link_expertise)
					||
					!isset($clinical_resource_fpage_ref_top_title_expertise) || empty($clinical_resource_fpage_ref_top_title_expertise)
					||
					!isset($clinical_resource_fpage_ref_top_intro_expertise) || empty($clinical_resource_fpage_ref_top_intro_expertise)
					||
					!isset($clinical_resource_fpage_ref_top_link_expertise) || empty($clinical_resource_fpage_ref_top_link_expertise)
					) {
					$fpage_text_expertise_vars = isset($fpage_text_expertise_vars) ? $fpage_text_expertise_vars : uamswp_fad_fpage_text_expertise(
						$page_id, // int
						$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
						$ontology_type // bool
					);
						$clinical_resource_fpage_title_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_title_expertise']; // string
						$clinical_resource_fpage_ref_main_title_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_main_title_expertise']; // string
						$clinical_resource_fpage_ref_main_intro_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_main_intro_expertise']; // string
						$clinical_resource_fpage_ref_main_link_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_main_link_expertise']; // string
						$clinical_resource_fpage_ref_top_title_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_top_title_expertise']; // string
						$clinical_resource_fpage_ref_top_intro_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_top_intro_expertise']; // string
						$clinical_resource_fpage_ref_top_link_expertise = $fpage_text_expertise_vars['clinical_resource_fpage_ref_top_link_expertise']; // string
				}

				// Create array of main archive attributes
				$text_image_overlay_main_archive = array(
					'heading'			=> $clinical_resource_fpage_ref_main_title_expertise, // Heading text, limited to 65 characters // str
					'body'				=> $clinical_resource_fpage_ref_main_intro_expertise, // Body text, limited to 280 characters // str
					'button_text'		=> $clinical_resource_fpage_ref_main_link_expertise, // Link text, limited to 27 characters // str
					'button_url'		=> get_post_type_archive_link('clinical-resource'), // Full URL // str
					'image'				=> $clinical_resource_archive_image // Background image ID // int
				);

				// Create array of top-level ontology ancestor fake subpage attributes
				$text_image_overlay_parent_archive = array(
					'heading'			=> $clinical_resource_fpage_ref_top_title_expertise, // Heading text, limited to 65 characters // str
					'body'				=> $clinical_resource_fpage_ref_top_intro_expertise, // Body text, limited to 280 characters // str
					'button_text'		=> $clinical_resource_fpage_ref_top_link_expertise, // Link text, limited to 27 characters // str
					'button_url'		=> trailingslashit(get_permalink($ancestors_ontology_farthest)) . user_trailingslashit('resources'), // Full URL // str
					'image'				=> get_post_thumbnail_id($ancestors_ontology_farthest) // Background image ID // int
				);

			}

		// Create the sequence of background colors
		$text_image_overlay_color_auto = array( 'bg-blue', 'bg-green' );

		// Create an array for storing the UAMS Text & Image Overlay Block item configuration arrays


			// Iteration count
			$i = 0;

			// Create the array for the UAMS Text & Image Overlay Block item linking to the parent archive
			// ... and add it to the main array

			if (
				!$page_top_level_query // If the fake subpage's parent is not the top-level ontology item
				&&
				isset($text_image_overlay_parent_archive)
				) {

				// Create the array

					$parent_archive = array();
					$parent_archive['heading'] = $text_image_overlay_parent_archive['heading']; // Heading text, limited to 65 characters // str
					$parent_archive['body'] = $text_image_overlay_parent_archive['body']; // Body text, limited to 280 characters // str
					$parent_archive['button_text'] = $text_image_overlay_parent_archive['button_text']; // Link text, limited to 27 characters // str
					$parent_archive['button_url'] = $text_image_overlay_parent_archive['button_url']; // Full URL // str
					$parent_archive['button_target'] = true; // Query on whether to open the link in a new window/tab // bool
					$parent_archive['button_desc'] = $parent_archive['button_text'] . ', ' . $parent_archive['heading']; // Link ARIA label text // str
					$parent_archive['image'] = $text_image_overlay_parent_archive['image']; // Background image ID // int
					$parent_archive['background_color'] = $text_image_overlay_color_auto[$i]; // Background color value // str (default: 'blue')

				// Add it to the main array
				$fpage_text_image_overlay[] = $parent_archive;

				// Advance the iteration count
				$i++;

			}

			// Create the array for the UAMS Text & Image Overlay Block item linking to the main archive
			// ... and add it to the main array

				// Create the array

					$main_archive = array();
					$main_archive['heading'] = $text_image_overlay_main_archive['heading']; // Heading text, limited to 65 characters // str
					$main_archive['body'] = $text_image_overlay_main_archive['body']; // Body text, limited to 280 characters // str
					$main_archive['button_text'] = $text_image_overlay_main_archive['button_text']; // Link text, limited to 27 characters // str
					$main_archive['button_url'] = $text_image_overlay_main_archive['button_url']; // Full URL // str
					$main_archive['button_target'] = true; // Query on whether to open the link in a new window/tab // bool
					$main_archive['button_desc'] = $main_archive['button_text'] . ', ' . $main_archive['heading']; // Link ARIA label text // str
					$main_archive['image'] = $text_image_overlay_main_archive['image']; // Background image ID // int
					$main_archive['background_color'] = $text_image_overlay_color_auto[$i]; // Background color value // str (default: 'blue')

				// Add it to the main array
				$fpage_text_image_overlay[] = $main_archive;

	// Call the main function to construct UAMS Text & Image Overlay Block

		if (
			isset($parent_archive) && !empty($parent_archive)
			&&
			isset($main_archive) && !empty($main_archive)
		) {

			uamswp_section_text_image_overlay(
				$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				$text_image_overlay_id, // Section ID attribute value // string
				$parent_archive, // Values for the first item // arr
				$main_archive // Values for the second item // arr
			);

		} elseif (
			isset($main_archive) && !empty($main_archive)
		) {

			uamswp_section_text_image_overlay(
				$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				$text_image_overlay_id, // Section ID attribute value // string
				$main_archive // Values for the first item // arr
			);

		}

}

// Add non-breaking space to prevent orphaned short words
function uamswp_prevent_orphan($string) {

	// Strip whitespace from the beginning and end of the string
	$string = trim($string);

	// If the final word is at most five characters...
	// Replace the preceding space with a non-breaking space
	$string = preg_replace('/\s(\S{1,5})$/', '&nbsp;$1', $string); 

	// Replace the space in "UAMS Health" with a non-breaking space
	$string = preg_replace('/(UAMS)\s(Health)/', '$1&nbsp;$2', $string); 

	return $string;

}