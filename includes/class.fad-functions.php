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

// Pubmed finder

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

// Remove Genesis term meta

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

// Relevanssi Functions

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

		if (
			is_singular( 'expertise' )
			||
			is_singular( 'condition' )
			||
			is_singular( 'treatment' )
			||
			is_singular( 'clinical-resource' )
		) { // Only run on these template pages

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
			$out .= include( UAMS_FAD_PATH . '/templates/parts/html/cards/provider.php' );
		endwhile;
		endif;
		wp_die();

	}

// Provider Recognition Lists

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

					$provider_specialty = get_field('physician_title');
					$provider_specialty_term = get_term($provider_specialty, 'clinical_title');
					$provider_specialty_name = $provider_specialty_term->name;
					$provider_occupation_title = get_field('clinical_specialization_title', $provider_specialty_term);
					$provider_occupation_title = $provider_occupation_title ?: $provider_specialty_name;

					if (
						$provider_occupation_title
						&&
						!empty($provider_occupation_title)
					) {

						$recognition_list .= '<td>'. ($provider_occupation_title ?: '&nbsp;') .'</td>';

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

// Medline API

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

// Ajax Filters

	// Provider Ajax Filter

		// Provider Ajax Filter Shortcode

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
						// Clinical Occupation Title
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
								<label class="sr-only" for="provider_title">Clinical Occupation Title</label>
								<select name="provider_title" id="provider_title" class="form-control">
									<option value="">Any Clinical Occupation Title</option>
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

		// Provider Ajax Filter Callback

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
						include( UAMS_FAD_PATH . '/templates/parts/html/cards/provider.php' );
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

	// Provider Title-Only Ajax Filter

		// Provider Title-Only Ajax Filter Shortcode

			function uamswp_provider_title_ajax_filter_shortcode( $atts ) {

				$a = shortcode_atts( array(
					'providers' => ''
				), $atts);
				$providers = explode(",", $a['providers']);
				$provider_titles = array();
				$provider_titles_list = array();
				foreach($providers as $provider) {
					if ( get_post_status ( $provider ) == 'publish' ) {
						// Clinical Occupation Title
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
								<label class="sr-only" for="provider_title">Clinical Occupation Title</label>
								<select name="provider_title" id="provider_title" class="form-control">
									<option value="">Any Clinical Occupation Title</option>
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

		// Provider Title-Only Ajax Filter Callback

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
						include( UAMS_FAD_PATH . '/templates/parts/html/cards/provider.php' );
					endwhile;
					echo '<data id="provider_ids" data-postids="'. implode(',', $provider_ids) .'," data-titles="'. implode(',', array_unique($title_list)) .',"></data>';
				} else {
					//var_dump($args);
					echo '<span class="no-results">Sorry, there are no providers matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
				}
				wp_die();
			}

	// Location Ajax Filter

		// Location Ajax Filter Shortcode

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

		// Location Ajax Filter Callback

			add_action('wp_ajax_nopriv_location_ajax_filter', 'location_ajax_filter_callback');
			add_action('wp_ajax_location_ajax_filter', 'location_ajax_filter_callback');

			function location_ajax_filter_callback() {

				// Get system settings for location labels
				include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

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
						include( UAMS_FAD_PATH . '/templates/parts/html/cards/location.php' );
					endwhile;
					echo '<data id="location_ids" data-postids="'. implode(',', $location_ids) .'," data-regions="'. implode(',', $location_region_list) .',"></data>';
				} else {
					echo '<span class="no-results">Sorry, there are no locations matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
				}
				wp_die();
			}

// The Trench (Pre-filtering provider and location lists based on user geolocation and/or user intent)

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

// MyChart Open Scheduling Ajax Callback

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
	$page_id, // int // ID of the post
	$regions = '', // string|array // Region(s) associated with the item
	$service_lines = '' // string|array // Service line(s) associated with the item
) {

	// Retrieve the value of the transient
	uamswp_fad_get_transient(
		'vars_' . $page_id, // Required // String added to transient name for disambiguation.
		$ontology_hide_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
		__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
	);

	if ( !empty( $ontology_hide_vars ) ) {

		/* 
		 * The transient exists.
		 * Return the variable.
		 */

		return $ontology_hide_vars;

	} else {

		/* 
		 * The transient does not exist.
		 * Define the variable again.
		 */

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
					empty($remove_region) // If the remove region array is empty
					&&
					!empty($remove_service_line) // and if the remove service line array is not empty
					&&
					empty( array_diff( $service_lines, $remove_service_line ) ) // and if all the item's service lines are in the remove service line array
				) {

					$hide_medical_ontology = true;

					break;

				} elseif(
					!empty($remove_region) // If the remove region array is not empty
					&&
					empty( array_diff( $regions, $remove_region ) ) // and if all the item's regions are in the remove region array
					&&
					!empty($remove_service_line) // and if the remove service line array is not empty
					&&
					empty( array_diff( $service_lines, $remove_service_line ) ) // and if all the item's service lines are in the remove service line array
				) {

					$hide_medical_ontology = true;

					break;

				}

			} // endwhile

		} // endif

		// Create an array to be used on the templates and template parts

			$ontology_hide_vars = array(
				'hide_medical_ontology'	=> $hide_medical_ontology // bool
			);

		// Set/update the value of the transient
		uamswp_fad_set_transient(
			'vars_' . $page_id, // Required // String added to transient name for disambiguation.
			$ontology_hide_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		// Return the variable
		return $ontology_hide_vars;

	}

}

// Convert text string to HTML attribute-friendly text string
function uamswp_attr_conversion($input) {

	$input_attr = isset($input) ? $input : '';

	if ( empty($input_attr) ) {
		return '';
	}

	$input_attr = str_replace('&nbsp;', ' ', $input_attr); // Replace non-breaking space with normal space
	$input_attr = str_replace('&#8220;', '\'', $input_attr); // Replace left double quotation mark with normal space
	$input_attr = str_replace('&#8221;', '\'', $input_attr); // Replace right double quotation mark with normal space
	$input_attr = str_replace('&#8216;', '\'', $input_attr); // Replace left single quotation mark with normal space
	$input_attr = str_replace('&#8217;', '\'', $input_attr); // Replace right single quotation mark with normal space
	$input_attr = str_replace('"', '\'', $input_attr); // Replace double quotes with single quote
	$input_attr = htmlentities($input_attr, false, 'UTF-8'); // Convert all applicable characters to HTML entities
	$input_attr = str_replace('&nbsp;', ' ', $input_attr); // Replace non-breaking space with normal space
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

	// Retrieve the value of the transient
	uamswp_fad_get_transient(
		'vars_' . $page_id, // Required // String added to transient name for disambiguation.
		$ontology_site_values_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
		__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
	);

	if ( !empty( $ontology_site_values_vars ) ) {

		/* 
		 * The transient exists.
		 * Return the variable.
		 */

		return $ontology_site_values_vars;

	} else {

		/* 
		 * The transient does not exist.
		 * Define the variable again.
		 */

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
					$ancestors_ontology_farthest_url = user_trailingslashit(get_permalink( $ancestors_ontology_farthest ));
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
					$ancestors_ontology_closest_url = user_trailingslashit(get_permalink( $ancestors_ontology_closest ));
				}

		// Set the values of the navbar-subbrand elements

			if ( $ontology_type ) {
				// If the page has the ontology type...
				// Set the navbar-subbrand title element using the page's values 
				$site_nav_id = $page_id;
				$site_nav_title = get_the_title($site_nav_id);
				$site_nav_title_attr = uamswp_attr_conversion($site_nav_title);
				$site_nav_url = user_trailingslashit(get_permalink($site_nav_id));
				$navbar_subbrand_title = $site_nav_title;
				$navbar_subbrand_title_attr = $site_nav_title_attr;
				$navbar_subbrand_title_url = $site_nav_url;
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
				$site_nav_title = $ancestors_ontology_closest_title;
				$site_nav_title_attr = uamswp_attr_conversion($site_nav_title);
				$site_nav_url = $ancestors_ontology_closest_url;
				$navbar_subbrand_title = $site_nav_title;
				$navbar_subbrand_title_attr = $site_nav_title_attr;
				$navbar_subbrand_title_url = $site_nav_url;
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

		// Create an array to be used on the templates and template parts

			$ontology_site_values_vars = array(
				'site_nav_id'					=> $site_nav_id, // int
				'site_nav_title'				=> $site_nav_title, // string
				'site_nav_title_attr'			=> $site_nav_title_attr, // string
				'site_nav_url'					=> $site_nav_url, // string
				'navbar_subbrand_title'			=> $navbar_subbrand_title, // string
				'navbar_subbrand_title_attr'	=> $navbar_subbrand_title_attr, // string
				'navbar_subbrand_title_url'		=> $navbar_subbrand_title_url, // string
				'navbar_subbrand_parent'		=> $navbar_subbrand_parent, // string
				'navbar_subbrand_parent_attr'	=> $navbar_subbrand_parent_attr, // string
				'navbar_subbrand_parent_url'	=> $navbar_subbrand_parent_url, // string
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

		// Set/update the value of the transient
		uamswp_fad_set_transient(
			'vars_' . $page_id, // Required // String added to transient name for disambiguation.
			$ontology_site_values_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		// Return the variable
		return $ontology_site_values_vars;

	}

}

// Queries for whether each of the related ontology content sections should be displayed on ontology pages/subsections

	// Loop through array of IDs and remove the IDs of unpublished posts

		function uamswp_fad_simple_publish_loop(
			$array, // int[] // Array of post items,
			$max = 0 // int // Maximum number of posts to return (0 = all)
		) {

			// Check/define variables
				$array = ( isset($array) && is_array($array) && !empty($array) ) ? $array : array();
				$max = ( isset($max) && !empty($max) ) ? $max : 0;

			// If the array is empty, stop here

				if ( !$array ) {

					return $array;

				}

			// Check for published items

				$i = 0;

				foreach ( $array as $key => $value ) {

					if ( get_post_status ( $value ) != 'publish' ) {

						unset($array[$key]);

					} else {

						$i++;

						if (
							$max != 0
							&&
							$i == $max
						) {

							$array = array_values($array);

							return $array;

						}

					}

				}

				$array = array_values($array);

			return $array;

		}

	// Create a simple linked text list of related ontology items

		function uamswp_fad_related_list(
			$page_id, // int // ID of the current ontology item
			$page_title_attr, // string // Attribute-friendly title of the current ontology item
			&$related_array, // int[] // List of related ontology item IDs
			$post_type = '', // string (optional) // Post type of the related ontology items
			$data_category_title = '', // string (optional) // 'data-categorytitle' attribute value of links
			$max = 3 // int (optional) // Maximum number of related ontology items to display
		) {

			// Check/define variables

				$related_array = ( isset($related_array) && is_array($related_array) && !empty($related_array) ) ? $related_array : array();
				$related_array_list = '';

			// If the array is empty, stop here

				if ( !$related_array ) {

					return $related_array_list;
				}

			// Get only published IDs
			$related_array = $related_array ? uamswp_fad_simple_publish_loop($related_array) : array();

			// Count remaining providers
			$related_array_count = count($related_array);

			// Display reference to more items
			$more = ( $related_array_count > $max ) ? true : false; // bool

			// Limit number of providers
			$related_array = array_slice( $related_array, 0, $max );

			// Get field values for provider links
			$related_array_vals = uamswp_fad_related_list_link_values($related_array);

			// Check/define the post type
			$post_type = ( isset($post_type) && !empty($post_type) ) ? $post_type : get_post_type( $related_array[0] );

			// Check/define the '$data_category_title' attribute value

				if (
					!isset($data_category_title)
					||
					empty($data_category_title)
				) {

					$data_category_title_map = array(
						'provider'			=> 'Related Provider',
						'location'			=> 'Related Location',
						'expertise'			=> 'Related Area of Expertise',
						'clinical-resource'	=> 'Related Clinical Resource',
						'condition'			=> 'Related Condition',
						'treatment'			=> 'Related Treatment'
					);

					$data_category_title = $data_category_title_map[$post_type];

				}

			// Create the comma-separated list of linked provider items

				$related_array_list = uamswp_fad_related_list_html(
					$related_array_vals, // Multidimensional array where second-level arrays are associative arrays (keys: 'title', 'title_attr', 'url')
					$data_category_title, // string // 'data-categorytitle' attribute value
					$page_title_attr, // string // 'data-itemtitle' attribute value
					$more // bool // Query for whether to include reference to more items
				);

			return $related_array_list;

		}

		// Get published field values from a post used to construct a linked text item in a list

			function uamswp_fad_related_item_link_values(
				$page_id // int // Post ID
			) {

				// Retrieve the value of the transient
				uamswp_fad_get_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$output, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				if ( !empty( $output ) ) {

					/* 
					* The transient exists.
					* Return the variable.
					*/

					return $output;

				} else {

					$output = array();

					if ( get_post_status ( $page_id ) == 'publish' ) {

						// Titles and sort name

							if ( get_post_type($page_id) == 'provider' ) {

								// If provider, get medium name for title

									$output['title'] = get_field( 'physician_medium_name', $page_id ) ?: '';

									if (
										!isset( $output['title'] )
										||
										empty( $output['title'] )
									) {

										$output['title'] = implode(
											' ',
											array_filter(
												array(
													( get_field( 'physician_prefix', $page_id ) ?: '' ),
													( get_field( 'physician_first_name', $page_id ) ?: '' ),
													( get_field( 'physician_middle_name', $page_id ) ?: '' ),
													( get_field( 'physician_last_name', $page_id ) ?: '' ),
													( get_field( 'physician_pedigree', $page_id ) ?: '' )
												)
											)
										) ?: '';

									}

								// Attribute-friendly title
								$output['title_attr'] = uamswp_attr_conversion($output['title']);

								// Sort name
								$output['sort_name'] = get_the_title($page_id);

							} else {

								// Otherwise, get the post title
								$output['title'] = get_the_title($page_id);

								// Attribute-friendly title
								$output['title_attr'] = uamswp_attr_conversion($output['title']);

								// Sort name
								$output['sort_name'] = $output['title'];

							}

						// URL
						$output['url'] = get_permalink($page_id);

					}

					// Set/update the value of the transient
					uamswp_fad_set_transient(
						$page_id, // Required // String added to transient name for disambiguation.
						$output, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

					// Return the array
					return $output;

				}

			}

		// Loop through array of IDs and get published field values used to construct the linked text items in a list

			function uamswp_fad_related_list_link_values(
				$array // int[] // Array of post IDs
			) {

				// Check/define values

					$array = ( isset($array) && is_array($array) && !empty($array) ) ? $array : array();
					$output = array();

				// If the array is empty, stop here

					if ( !$array ) {

						return $output;

					}

				// Check for published items

					foreach ( $array as $item ) {

						$output[$item] = uamswp_fad_related_item_link_values($item);

					}

				return $output;

			}

		// Create HTML for a comma-separated list of linked ontology items

			function uamswp_fad_related_list_html(
				$array, // Multidimensional array where second-level arrays are associative arrays (keys: 'title', 'title_attr', 'url')
				$data_category_title = '', // string // 'data-categorytitle' attribute value
				$data_item_title = '', // string // 'data-itemtitle' attribute value
				$more = false // bool // Query for whether to include reference to more items
			) {

				// Construct link elements

					foreach ( $array as $item ) {

						if ( $item['url'] ) {

							// Open anchor element

								$item_link_open = '<a';
								$item_link_open .= ' href="' . $item['url'] . '"';
								$item_link_open .= $data_category_title ? ( ' data-categorytitle="' . $data_category_title .'"' ) : '';
								$item_link_open .= $item['title_attr'] ? ( ' data-typetitle="' . $item['title_attr'] . '"' ) : '';
								$item_link_open .= $data_item_title ? ' data-categorytitle="' . $data_item_title . '"' : '';
								$item_link_open .= '>';

							// Close anchor element

								$item_link_close = '</a>';

						}

						$output_array[] = $item_link_open . $item['title'] . $item_link_close;

					}

				// Add reference to more items
				$output_array[] = $more ? 'more' : '';

				// Remove any empty items from the array
				$output_array = array_filter($output_array);

				// Split lists for serial grammar

					// Final two items in array

						$output_array_split_1 = array_slice(
							$output_array,
							-2, // start two items from the end
							2 // include two items
						);

					// Remainder of the array (the items at the beginning)

						$output_array_split_0 = array_slice(
							$output_array,
							0, // start at the beginning
							( count($output_array) - count($output_array_split_1) ) // include the remainder
						);

				// Construct the list as a string

					// Join the final two items with "and"

						$output_string_split_1 = implode(
							(
								' and'
								. ( $more ? '&nbsp;' : ' ' ) // Use non-breaking space if the last item is 'more'
							),
							$output_array_split_1
						);

					// Merge the combined final two items string with the beginning array

						$output_array_merge = array_merge(
							$output_array_split_0,
							array($output_string_split_1)
						); // reset variable

					// Join the items in the array with commas

						$output = implode(
							', ',
							$output_array_merge
						);

				return $output;

			}

	// Query for whether related providers content section should be displayed on ontology pages/subsections
	function uamswp_fad_provider_query(
		$page_id, // int
		$providers, // int[]
		&$jump_link_count = 0, // int
		$hide_medical_ontology = false // bool
	) {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars_' . $page_id, // Required // String added to transient name for disambiguation.
			$provider_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $provider_query_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $provider_query_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Eliminate PHP errors

				$provider_query = '';
				$provider_section_show = false;
				$provider_ids = array();
				$provider_count = 0;

			if (
				!$hide_medical_ontology
				&&
				$providers
			) {

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

				if ( $provider_query->have_posts() ) {

					$provider_section_show = true;
					$provider_ids = $provider_query->posts;
					$provider_count = count($provider_query->posts);
					$jump_link_count++;

				}

			}

			// Create an array to be used on the templates and template parts

				$provider_query_vars = array(
					'provider_query'		=> $provider_query, // WP_Post[]
					'provider_section_show'	=> $provider_section_show, // bool
					'provider_ids'			=> $provider_ids, // int[]
					'provider_count'		=> $provider_count, // int
					'jump_link_count'		=> $jump_link_count // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$provider_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $provider_query_vars;

		}

	}

	// Query for whether related locations content section should be displayed on a page
	function uamswp_fad_location_query(
		$page_id, // int
		$locations, // int[]
		&$jump_link_count = 0, // int
		$hide_medical_ontology = false // bool
	) {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars_' . $page_id, // Required // String added to transient name for disambiguation.
			$location_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $location_query_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $location_query_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Eliminate PHP errors

				$location_query = '';
				$location_section_show = false;
				$location_valid = false;
				$location_ids = array();
				$location_count = 0;
				$location_primary_query = '';

			if (
				!$hide_medical_ontology
				&&
				$locations
			) {

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

				if ( $location_query->have_posts() ) {

					$location_section_show = true;
					$location_valid = true;
					$location_ids = $location_query->posts;
					$location_count = count($location_query->posts);
					$jump_link_count++;

					if ( 'provider' == get_post_type($page_id) ) {

						$args = array(
							'post__in' => $locations,
							'post_type' => 'location',
							'post_status' => 'publish',
							'posts_per_page' => 1,
							'order' => 'ASC',
							'orderby' => 'post__in',
							'fields' => 'ids',
						);

						$location_primary_query = new WP_Query( $args );

					}

				}

			}

			// Create an array to be used on the templates and template parts

				$location_query_vars = array(
					'location_query'			=> $location_query, // WP_Post[]
					'location_section_show'		=> $location_section_show, // bool
					'location_ids'				=> $location_ids, // int[]
					'location_count'			=> $location_count, // int
					'location_valid'			=> $location_valid, // bool
					'location_primary_query'	=> $location_primary_query, // WP_Post[]
					'jump_link_count'			=> $jump_link_count // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$location_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $location_query_vars;

		}

	}

	// Query for whether descendant locations content section should be displayed on a page
	function uamswp_fad_location_descendant_query(
		$page_id, // int
		$location_descendants, // int[]
		&$jump_link_count = 0, // int
		$hide_medical_ontology = false // bool
	) {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars_' . $page_id, // Required // String added to transient name for disambiguation.
			$location_descendant_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $location_descendant_query_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $location_descendant_query_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Eliminate PHP errors

				$location_descendant_query = '';
				$location_descendant_section_show = false;
				$location_descendant_valid = false;
				$location_descendant_ids = array();
				$location_descendant_count = '';
				$location_descendant_valid = false;

			if (
				!$hide_medical_ontology
				&&
				$location_descendants
				&&
				0 != count($location_descendants)
			) {

				$location_descendant_args = array(
					'post_type' => 'location',
					'post_status' => 'publish',
					'post_parent' => $page_id,
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

				$location_descendant_query = new WP_Query( $location_descendant_args );

				if ( $location_descendant_query->have_posts() ) {

					$location_descendant_section_show = true;
					$location_descendant_valid = true;
					$location_descendant_ids = $location_descendant_query->posts;
					$location_descendant_count = count($location_descendant_query->posts);
					$jump_link_count++;

				}

			}

			// Create an array to be used on the templates and template parts

				$location_descendant_query_vars = array(
					'location_descendant_query'			=> $location_descendant_query, // WP_Post[]
					'location_descendant_section_show'	=> $location_descendant_section_show, // bool
					'location_descendant_ids'			=> $location_descendant_ids, // int[]
					'location_descendant_count'			=> $location_descendant_count, // int
					'location_descendant_valid'			=> $location_descendant_valid, // bool
					'jump_link_count'					=> $jump_link_count // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$location_descendant_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $location_descendant_query_vars;

		}

	}

	// Query for whether descendant areas of expertise content section should be displayed on ontology pages/subsections
	function uamswp_fad_expertise_descendant_query(
		$page_id, // int
		$expertise_descendants, // int[]
		$content_placement = 'profile', // string // Placement of this content // Expected values: 'subsection' or 'profile'
		$site_nav_id = '', // int 
		&$jump_link_count = 0, // int
		$hide_medical_ontology = false // bool
	) {

		// Get site header and site nav values for this ontology subsection
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/ontology-subsection.php' );

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars_' . $site_nav_id, // Required // String added to transient name for disambiguation.
			$expertise_descendant_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $expertise_descendant_query_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $expertise_descendant_query_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Check/define variables

				$content_placement = ( isset($content_placement) && !empty($content_placement) ) ? $content_placement : 'profile';

			// Eliminate PHP errors

				$expertise_descendant_args = '';
				$expertise_descendant_query = '';
				$expertise_descendant_section_show = false;
				$expertise_descendant_ids = array();
				$expertise_descendant_count = 0;
				$expertise_content_args = '';
				$expertise_content_query = '';
				$expertise_content_nav_show = false;
				$expertise_content_ids = array();
				$expertise_content_count = 0;
				$expertise_content_nav = '';

			if (
				!$hide_medical_ontology
				&&
				$expertise_descendants
			) {

				// Create the query for ontology type

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
								'relation' => 'OR',
								array(
									'key' => 'hide_from_sub_menu',
									'value' => '1',
									'compare' => '!=',
								),
								array(
									'key' => 'hide_from_sub_menu',
									'compare' => 'NOT EXISTS'
								),
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

					if (
						$expertise_descendant_query->have_posts()
					) {

						$expertise_descendant_section_show = true;
						$expertise_descendant_ids = $expertise_descendant_query->posts;
						$expertise_descendant_count = count($expertise_descendant_query->posts);
						$jump_link_count++;

					}

				// Create the query for content type

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
									'relation' => 'OR',
									array(
										'key' => 'hide_from_sub_menu',
										'value' => '1',
										'compare' => '!=',
									),
									array(
										'key' => 'hide_from_sub_menu',
										'compare' => 'NOT EXISTS'
									),
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

						if ( $expertise_content_query->have_posts() ) {

							$expertise_content_nav_show = true;
							$expertise_content_ids = $expertise_content_query->posts;
							$expertise_content_count = count($expertise_content_query->posts);

							while ( $expertise_content_query->have_posts() ) {

								$expertise_content_query->the_post();
								$page_id = get_the_ID();
								$page_title = get_the_title();
								$page_title_attr = uamswp_attr_conversion($page_title);
								$page_url = user_trailingslashit(get_permalink());
								$expertise_content_nav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item menu-item-'. $page_id .' nav-item active"><a title="'. $page_title_attr .'" href="'. $page_url .'" class="nav-link"><span itemprop="name">'. $page_title .'</span></a></li>';

							} // endwhile

							wp_reset_postdata();

						}

					}

			}

			// Create an array to be used on the templates and template parts

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

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars_' . $site_nav_id, // Required // String added to transient name for disambiguation.
				$expertise_descendant_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $expertise_descendant_query_vars;

		}

	}

	// Query for whether related areas of expertise content section should be displayed on ontology pages/subsections
	function uamswp_fad_expertise_query(
		$page_id, // int
		$expertises, // int[]
		&$jump_link_count = 0, // int
		$hide_medical_ontology = false // bool
	) {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars_' . $page_id, // Required // String added to transient name for disambiguation.
			$expertise_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $expertise_query_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $expertise_query_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Eliminate PHP errors

				$expertise_query = '';
				$expertise_section_show = false;
				$expertise_ids = array();
				$expertise_count = 0;

			if (
				!$hide_medical_ontology
				&&
				$expertises
			) {

				$args = array(
					'post__in'	=> $expertises,
					'post_type' => 'expertise',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'order' => 'ASC',
					'orderby' => 'title',
				);

				$expertise_query = new WP_Query( $args );

				if ( $expertise_query->have_posts() ) {

					$expertise_section_show = true;
					$expertise_ids = $expertise_query->posts;
					$expertise_count = count($expertise_query->posts);
					$jump_link_count++;

				}

			}

			// Create an array to be used on the templates and template parts

				$expertise_query_vars = array(
					'expertise_query'			=> $expertise_query, // WP_Post[]
					'expertise_section_show'	=> $expertise_section_show, // bool
					'expertise_ids'				=> $expertise_ids, // int[]
					'expertise_count'			=> $expertise_count, // int
					'jump_link_count'			=> $jump_link_count // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$expertise_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $expertise_query_vars;

		}

	}

	// Query for whether related clinical resources content section should be displayed on ontology pages/subsections
	function uamswp_fad_clinical_resource_query(
		$page_id, // int
		$clinical_resources, // int[] // Value of the related clinical resources input
		$clinical_resource_posts_per_page = '', // int (optional)
		&$jump_link_count = 0, // int
		$hide_medical_ontology = false // bool
	) {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars_' . $page_id, // Required // String added to transient name for disambiguation.
			$clinical_resource_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $clinical_resource_query_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $clinical_resource_query_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Bring in variables from outside of the function

				if ( !isset($clinical_resource_posts_per_page) ) {

					include( UAMS_FAD_PATH . '/templates/parts/vars/sys/posts-per-page/clinical-resource.php' ); // General maximum number of clinical resource items to display on a fake subpage (or section)
					$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_section;

				}

			// Eliminate PHP errors

				$clinical_resource_query = '';
				$clinical_resource_section_show = false;
				$clinical_resource_ids = array();
				$clinical_resource_count = 0;

			if (
				!$hide_medical_ontology
				&&
				$clinical_resources
			) {

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

					if ( $clinical_resource_query->have_posts() ) {

						$clinical_resource_section_show = true;
						$clinical_resource_ids = $clinical_resource_query->posts;
						$clinical_resource_count = count($clinical_resource_query->posts);
						$jump_link_count++;

						// Count valid clinical resource items

							$clinical_resource_count = 0;

							foreach ( $clinical_resources as $resource ) {

								if ( get_post_status ( $resource ) == 'publish' ) {

									$clinical_resource_count++;

								}

							}

					}

			}

			// Create an array to be used on the templates and template parts

				$clinical_resource_query_vars = array(
					'clinical_resource_query'			=> $clinical_resource_query, // WP_Post[]
					'clinical_resource_section_show'	=> $clinical_resource_section_show, // bool
					'clinical_resource_ids'				=> $clinical_resource_ids, // int[]
					'clinical_resource_count'			=> $clinical_resource_count, // int
					'clinical_resource_posts_per_page'	=> $clinical_resource_posts_per_page, // int
					'jump_link_count'					=> $jump_link_count // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$clinical_resource_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $clinical_resource_query_vars;

		}

	}

	// Query for whether related conditions content section should be displayed on ontology pages/subsections
	function uamswp_fad_condition_query(
		$page_id, // int
		$conditions_cpt, // int[]
		&$condition_treatment_section_show = false, // bool
		$ontology_type = true, // bool
		&$jump_link_count = 0, // int
		$hide_medical_ontology = false // bool
	) {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars_' . $page_id, // Required // String added to transient name for disambiguation.
			$condition_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $condition_query_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $condition_query_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Eliminate PHP errors

				$condition_cpt_query = '';
				$condition_section_show = isset($condition_section_show) ? $condition_section_show : false;
				$condition_treatment_section_show = isset($condition_treatment_section_show) ? $condition_treatment_section_show : false;
				$condition_ids = array();
				$condition_count = 0;

			if (
				!$hide_medical_ontology
				&&
				$conditions_cpt
			) {

				$args = array(
					'post_type' => 'condition',
					'post_status' => 'publish',
					'orderby' => 'title',
					'order' => 'ASC',
					'posts_per_page' => -1,
					'post__in' => $conditions_cpt
				);

				$condition_cpt_query = new WP_Query( $args );

				if (
					$condition_cpt_query->posts
					&&
					(
						"1" == $ontology_type
						||
						!isset($ontology_type)
					)
				) {

					$condition_section_show = true;
					$condition_treatment_section_show = true;
					$condition_ids = $condition_cpt_query->posts;
					$condition_count = count($condition_cpt_query->posts);
					$jump_link_count++;

				}

			}

			$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();

			// Create an array to be used on the templates and template parts

				$condition_query_vars = array(
					'condition_cpt_query'				=> $condition_cpt_query, // WP_Post[]
					'condition_section_show'			=> $condition_section_show, // bool
					'condition_ids'						=> $condition_ids, // int[]
					'condition_count'					=> $condition_count, // int
					'schema_medical_specialty'			=> $schema_medical_specialty, // array
					'jump_link_count'					=> $jump_link_count // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$condition_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $condition_query_vars;

		}

	}

	// Query for whether related treatments content section should be displayed on ontology pages/subsections
	function uamswp_fad_treatment_query(
		$page_id, // int
		$treatments_cpt, // int[]
		&$condition_treatment_section_show = false, // bool
		$ontology_type = true, // bool
		&$jump_link_count = 0, // int
		$hide_medical_ontology = false // bool
	) {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars_' . $page_id, // Required // String added to transient name for disambiguation.
			$treatment_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $treatment_query_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $treatment_query_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Eliminate PHP errors

				$treatment_cpt_query = '';
				$treatment_section_show = false;
				$condition_treatment_section_show = isset($condition_treatment_section_show) ? $condition_treatment_section_show : false;
				$treatment_ids = array();
				$treatment_count = 0;
				$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array();

			if (
				!$hide_medical_ontology
				&&
				$treatments_cpt
			) {

				$args = array(
					'post_type' => 'treatment',
					'post_status' => 'publish',
					'orderby' => 'title',
					'order' => 'ASC',
					'posts_per_page' => -1,
					'post__in' => $treatments_cpt
				);

				$treatment_cpt_query = new WP_Query( $args );

				if (
					$treatment_cpt_query->posts
					&&
					(
						"1" == $ontology_type
						||
						!isset($ontology_type)
					)
				) {

					$treatment_section_show = true;
					$condition_treatment_section_show = true;
					$treatment_ids = $treatment_cpt_query->posts;
					$treatment_count = count($treatment_cpt_query->posts);
					$jump_link_count++;

				}

			}

			// Create an array to be used on the templates and template parts

				$treatment_query_vars = array(
					'treatment_cpt_query'				=> $treatment_cpt_query, // WP_Post[]
					'treatment_section_show'			=> $treatment_section_show, // bool
					'condition_treatment_section_show'	=> $condition_treatment_section_show, // bool
					'treatment_ids'						=> $treatment_ids, // int[]
					'treatment_count'					=> $treatment_count, // int
					'schema_medical_specialty'			=> $schema_medical_specialty, // array
					'jump_link_count'					=> $jump_link_count // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$treatment_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $treatment_query_vars;

		}

	}

// Construct the meta keywords element
function uamswp_keyword_hook_header( $keywords ) { 

	if ( $keywords ) {

		$i = 1;
		$keyword_text = '';

		foreach( $keywords as $keyword ) { 

			if ( 1 < $i ) {
				$keyword_text .= ', ';
			}

			$keyword_text .= str_replace( ",", "", $keyword['alternate_text'] );
			$i++;

		}

		echo '<meta name="keywords" content="' . $keyword_text . '" />';

	} // endif ( $keywords )

}

// Add bg-white class to article.entry element
function uamswp_add_entry_class( $attributes ) {

	$attributes['class'] = $attributes['class']. ' bg-white';

	return $attributes;

}

// Query for whether UAMS Health Talk podcast section should be displayed on ontology pages/subsections
function uamswp_fad_podcast_query(
	$page_id, // int
	$podcast_name, // string
	&$jump_link_count = 0 // int (optional)
) {

	// Retrieve the value of the transient
	uamswp_fad_get_transient(
		'vars_' . $page_id, // Required // String added to transient name for disambiguation.
		$podcast_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
		__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
	);

	if ( !empty( $podcast_query_vars ) ) {

		/* 
		 * The transient exists.
		 * Return the variable.
		 */

		return $podcast_query_vars;

	} else {

		/* 
		 * The transient does not exist.
		 * Define the variable again.
		 */

		// Check if podcast section should be displayed

			if ( $podcast_name ) {

				$podcast_section_show = true;
				$jump_link_count++;

			} else {

				$podcast_section_show = false;

			}

		// Create an array to be used on the templates and template parts

			$podcast_query_vars = array(
				'podcast_section_show'	=> $podcast_section_show, // bool
				'jump_link_count'		=> $jump_link_count // int
			);

		// Set/update the value of the transient
		uamswp_fad_set_transient(
			'vars_' . $page_id, // Required // String added to transient name for disambiguation.
			$podcast_query_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		// Return the variable
		return $podcast_query_vars;

	}

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

		// Get system settings for provider labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

		// Get system settings for provider archive text
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/provider.php' );

		// Get system settings for location labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

		// Get system settings for descendant location labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location-descendant.php' );

		// Get system settings for location archive text
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/location.php' );

		// Get system settings for area of expertise labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

		// Get system settings for descendant area of expertise item labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise-descendant.php' );

		// Get system settings for area of expertise archive text
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/expertise.php' );

		// Get system settings for clinical resource labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

		// Get system settings for clinical resource archive text
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/clinical-resource.php' );

		// Get system settings for Clinical Resource facet labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/facets/clinical-resource.php' );

		// Get system settings for combined condition and treatment labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition-treatment.php' );

		// Get system settings for condition labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition.php' );

		// Get system settings for condition archive text
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/condition.php' );

		// Get system settings for treatment labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/treatment.php' );

		// Get system settings for treatment archive text
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/treatment.php' );

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

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$labels_provider_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $labels_provider_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $labels_provider_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$provider_single_name = get_field('provider_single_name', 'option') ?: 'Provider';
			$provider_single_name_attr = uamswp_attr_conversion($provider_single_name);
			$provider_plural_name = get_field('provider_plural_name', 'option') ?: 'Providers';
			$provider_plural_name_attr = uamswp_attr_conversion($provider_plural_name);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()

				$placeholder_provider_single_name = '[Provider]';
				$placeholder_provider_plural_name = '[Providers]';
				$placeholder_provider_short_name = '[Provider Short Name]';
				$placeholder_provider_short_name_possessive = '[Provider Short Name\'s]';

			// FacetWP labels

				// Create array for pairing FacetWP name with label if none exists

					$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

				// Add item to FacetWP labels array for Providers facet on Clinical Resources archive/list

					$facet_labels['clinical_resource']['resource_provider'] = $provider_plural_name;
					$facet_labels['clinical_resource']['resource_provider_attr'] = $provider_plural_name_attr;

			// Create an array to be used on the templates and template parts

				$labels_provider_vars = array(
					'provider_single_name'							=> $provider_single_name, // string
					'provider_single_name_attr'						=> $provider_single_name_attr, // string
					'provider_plural_name'							=> $provider_plural_name, // string
					'provider_plural_name_attr'						=> $provider_plural_name_attr, // string
					'placeholder_provider_single_name'				=> $placeholder_provider_single_name, // string
					'placeholder_provider_plural_name'				=> $placeholder_provider_plural_name, // string
					'placeholder_provider_short_name'				=> $placeholder_provider_short_name, // string
					'placeholder_provider_short_name_possessive'	=> $placeholder_provider_short_name_possessive, // string
					'facet_labels_clinical_resource'				=> $facet_labels['clinical_resource'] // array
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$labels_provider_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $labels_provider_vars;

		}

	}

	// Get the Find-a-Doc Settings values for location labels
	function uamswp_fad_labels_location() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$labels_location_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $labels_location_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $labels_location_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$location_single_name = get_field('location_single_name', 'option') ?: 'Location';
			$location_single_name_attr = uamswp_attr_conversion($location_single_name);
			$location_plural_name = get_field('location_plural_name', 'option') ?: 'Locations';
			$location_plural_name_attr = uamswp_attr_conversion($location_plural_name);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()

				$placeholder_location_single_name = '[Location]';
				$placeholder_location_plural_name = '[Locations]';
				$placeholder_location_page_title = '[Location Title]';
				$placeholder_location_page_title_phrase = '[the Location Title]';

			// FacetWP labels

				// Create array for pairing FacetWP name with label if none exists

					$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

				// Add item to FacetWP labels array for Locations facet on Providers archive/list

					$facet_labels['provider']['locations'] = $location_plural_name;
					$facet_labels['provider']['locations_attr'] = $location_plural_name_attr;

				// Add item to FacetWP labels array for Locations facet on Clinical Resources archive/list

					$facet_labels['clinical_resource']['resource_locations'] = $location_plural_name;
					$facet_labels['clinical_resource']['resource_locations_attr'] = $location_plural_name_attr;

			// Create an array to be used on the templates and template parts

				$labels_location_vars = array(
					'location_single_name'						=> $location_single_name, // string
					'location_single_name_attr'					=> $location_single_name_attr, // string
					'location_plural_name'						=> $location_plural_name, // string
					'location_plural_name_attr'					=> $location_plural_name_attr, // string
					'placeholder_location_single_name'			=> $placeholder_location_single_name, // string
					'placeholder_location_plural_name'			=> $placeholder_location_plural_name, // string
					'placeholder_location_page_title'			=> $placeholder_location_page_title, // string
					'placeholder_location_page_title_phrase'	=> $placeholder_location_page_title_phrase, // string
					'facet_labels_provider'						=> $facet_labels['provider'], // array
					'facet_labels_clinical_resource'			=> $facet_labels['clinical_resource'] // array
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$labels_location_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $labels_location_vars;

		}

	}

	// Get the Find-a-Doc Settings values for location descendant item labels
	function uamswp_fad_labels_location_descendant() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$labels_location_descendant_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $labels_location_descendant_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $labels_location_descendant_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$location_descendant_single_name = get_field('location_descendant_single_name', 'option') ?: 'Additional Location';
			$location_descendant_single_name_attr = uamswp_attr_conversion($location_descendant_single_name);
			$location_descendant_plural_name = get_field('location_descendant_plural_name', 'option') ?: 'Additional Locations';
			$location_descendant_plural_name_attr = uamswp_attr_conversion($location_descendant_plural_name);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
			$placeholder_location_descendant_single_name = '[Descendant Location]';
			$placeholder_location_descendant_plural_name = '[Descendant Locations]';

			// Create an array to be used on the templates and template parts

				$labels_location_descendant_vars = array(
					'location_descendant_single_name' 				=> $location_descendant_single_name, // string
					'location_descendant_single_name_attr' 			=> $location_descendant_single_name_attr, // string
					'location_descendant_plural_name' 				=> $location_descendant_plural_name, // string
					'location_descendant_plural_name_attr'			=> $location_descendant_plural_name_attr, // string
					'placeholder_location_descendant_single_name'	=> $placeholder_location_descendant_single_name, // string
					'placeholder_location_descendant_plural_name'	=> $placeholder_location_descendant_plural_name // string
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$labels_location_descendant_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $labels_location_descendant_vars;

		}

	}

	// Get the Find-a-Doc Settings values for area of expertise labels
	function uamswp_fad_labels_expertise() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$labels_expertise_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $labels_expertise_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $labels_expertise_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$expertise_single_name = get_field('expertise_single_name', 'option') ?: 'Area of Expertise';
			$expertise_single_name_attr = uamswp_attr_conversion($expertise_single_name);
			$expertise_plural_name = get_field('expertise_plural_name', 'option') ?: 'Areas of Expertise';
			$expertise_plural_name_attr = uamswp_attr_conversion($expertise_plural_name);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()

				$placeholder_expertise_single_name = '[Area of Expertise]';
				$placeholder_expertise_plural_name = '[Areas of Expertise]';
				$placeholder_expertise_page_title = '[Area of Expertise Title]';

			// FacetWP labels

				// Create array for pairing FacetWP name with label if none exists

					$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

				// Add item to FacetWP labels array for Areas of Expertise facet on Providers archive/list

					$facet_labels['provider']['physician_areas_of_expertise'] = $expertise_plural_name;
					$facet_labels['provider']['physician_areas_of_expertise_attr'] = $expertise_plural_name_attr;

				// Add item to FacetWP labels array for Areas of Expertise facet on Locations archive/list

					$facet_labels['location']['location_aoe'] = $expertise_plural_name;
					$facet_labels['location']['location_aoe_attr'] = $expertise_plural_name_attr;

				// Add item to FacetWP labels array for Areas of Expertise facet on Clinical Resources archive/list

					$facet_labels['clinical_resource']['resource_aoe'] = $expertise_plural_name;
					$facet_labels['clinical_resource']['resource_aoe_attr'] = $expertise_plural_name_attr;

			// Create an array to be used on the templates and template parts

				$labels_expertise_vars = array(
					'expertise_single_name'				=> $expertise_single_name, // string
					'expertise_single_name_attr'		=> $expertise_single_name_attr, // string
					'expertise_plural_name'				=> $expertise_plural_name, // string
					'expertise_plural_name_attr'		=> $expertise_plural_name_attr, // string
					'placeholder_expertise_single_name'	=> $placeholder_expertise_single_name, // string
					'placeholder_expertise_plural_name'	=> $placeholder_expertise_plural_name, // string
					'placeholder_expertise_page_title'	=> $placeholder_expertise_page_title, // string
					'facet_labels_provider' 			=> $facet_labels['provider'], // array
					'facet_labels_location' 			=> $facet_labels['location'], // array
					'facet_labels_clinical_resource' 	=> $facet_labels['clinical_resource'] // array
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$labels_expertise_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $labels_expertise_vars;

		}

	}

	// Get the Find-a-Doc Settings values for area of expertise descendant item labels
	function uamswp_fad_labels_expertise_descendant() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$labels_expertise_descendant_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $labels_expertise_descendant_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $labels_expertise_descendant_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$expertise_descendant_single_name = get_field('expertise_descendant_single_name', 'option') ?: 'Specialty';
			$expertise_descendant_single_name_attr = uamswp_attr_conversion($expertise_descendant_single_name);
			$expertise_descendant_plural_name = get_field('expertise_descendant_plural_name', 'option') ?: 'Specialties';
			$expertise_descendant_plural_name_attr = uamswp_attr_conversion($expertise_descendant_plural_name);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
			$placeholder_expertise_descendant_single_name = '[Descendant Area of Expertise]';
			$placeholder_expertise_descendant_plural_name = '[Descendant Areas of Expertise]';

			// Create an array to be used on the templates and template parts

				$labels_expertise_descendant_vars = array(
					'expertise_descendant_single_name'				=> $expertise_descendant_single_name, // string
					'expertise_descendant_single_name_attr'			=> $expertise_descendant_single_name_attr, // string
					'expertise_descendant_plural_name'				=> $expertise_descendant_plural_name, // string
					'expertise_descendant_plural_name_attr'			=> $expertise_descendant_plural_name_attr, // string
					'placeholder_expertise_descendant_single_name'	=> $placeholder_expertise_descendant_single_name, // string
					'placeholder_expertise_descendant_plural_name'	=> $placeholder_expertise_descendant_plural_name // string
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$labels_expertise_descendant_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $labels_expertise_descendant_vars;

		}

	}

	// Get the Find-a-Doc Settings values for clinical resource labels
	function uamswp_fad_labels_clinical_resource() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$labels_clinical_resource_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $labels_clinical_resource_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $labels_clinical_resource_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$clinical_resource_single_name = get_field('clinical_resource_single_name', 'option') ?: 'Clinical Resource';
			$clinical_resource_single_name_attr = uamswp_attr_conversion($clinical_resource_single_name);
			$clinical_resource_plural_name = get_field('clinical_resource_plural_name', 'option') ?: 'Clinical Resources';
			$clinical_resource_plural_name_attr = uamswp_attr_conversion($clinical_resource_plural_name);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
			$placeholder_clinical_resource_single_name = '[Clinical Resource]';
			$placeholder_clinical_resource_plural_name = '[Clinical Resources]';

			// Create an array to be used on the templates and template parts

				$labels_clinical_resource_vars = array(
					'clinical_resource_single_name'				=> $clinical_resource_single_name, // string
					'clinical_resource_single_name_attr'		=> $clinical_resource_single_name_attr, // string
					'clinical_resource_plural_name'				=> $clinical_resource_plural_name, // string
					'clinical_resource_plural_name_attr'		=> $clinical_resource_plural_name_attr, // string
					'placeholder_clinical_resource_single_name'	=> $placeholder_clinical_resource_single_name, // string
					'placeholder_clinical_resource_plural_name'	=> $placeholder_clinical_resource_plural_name // string
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$labels_clinical_resource_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $labels_clinical_resource_vars;

		}

	}

	// Get the Find-a-Doc Settings values for clinical resource facet labels
	function uamswp_fad_labels_clinical_resource_facet() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$labels_clinical_resource_facet_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $labels_clinical_resource_facet_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $labels_clinical_resource_facet_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$clinical_resource_type_single_name = get_field('clinical_resource_type_single_name', 'option') ?: 'Resource Type';
			$clinical_resource_type_single_name_attr = uamswp_attr_conversion($clinical_resource_type_single_name);
			$clinical_resource_type_plural_name = get_field('clinical_resource_type_plural_name', 'option') ?: 'Resource Types';
			$clinical_resource_type_plural_name_attr = uamswp_attr_conversion($clinical_resource_type_plural_name);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()

				$placeholder_clinical_resource_type_single_name = '[Resource Type]';
				$placeholder_clinical_resource_type_plural_name = '[Resource Types]';

			// FacetWP labels

				// Create array for pairing FacetWP name with label if none exists

					$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

				// Add item to FacetWP labels array for Clinical Resource Type facet on Clinical Resource archive/list

					$facet_labels['clinical_resource']['resource_type'] = $clinical_resource_type_plural_name;
					$facet_labels['clinical_resource']['resource_type_attr'] = $clinical_resource_type_plural_name_attr;

			// Create an array to be used on the templates and template parts

				$labels_clinical_resource_facet_vars = array(
					'clinical_resource_type_single_name'				=> $clinical_resource_type_single_name, // string
					'clinical_resource_type_single_name_attr'			=> $clinical_resource_type_single_name_attr, // string
					'clinical_resource_type_plural_name'				=> $clinical_resource_type_plural_name, // string
					'clinical_resource_type_plural_name_attr'			=> $clinical_resource_type_plural_name_attr, // string
					'placeholder_clinical_resource_type_single_name'	=> $placeholder_clinical_resource_type_single_name, // string
					'placeholder_clinical_resource_type_plural_name'	=> $placeholder_clinical_resource_type_plural_name, // string
					'facet_labels_clinical_resource'					=> $facet_labels['clinical_resource'] // string
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$labels_clinical_resource_facet_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $labels_clinical_resource_facet_vars;

		}

	}

	// Get the Find-a-Doc Settings values for combined conditions and treatments labels
	function uamswp_fad_labels_condition_treatment() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$labels_condition_treatment_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $labels_condition_treatment_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $labels_condition_treatment_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$condition_treatment_single_name = get_field('condition_treatment_single_name', 'option') ?: 'Condition or Treatment';
			$condition_treatment_single_name_attr = uamswp_attr_conversion($condition_treatment_single_name);
			$condition_treatment_plural_name = get_field('condition_treatment_plural_name', 'option') ?: 'Conditions and Treatments';
			$condition_treatment_plural_name_attr = uamswp_attr_conversion($condition_treatment_plural_name);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
			$placeholder_condition_treatment_single_name = '[Condition or Treatment]';
			$placeholder_condition_treatment_plural_name = '[Conditions and Treatments]';

			// Create an array to be used on the templates and template parts

				$labels_condition_treatment_vars = array(
					'condition_treatment_single_name'				=> $condition_treatment_single_name, // string
					'condition_treatment_single_name_attr'			=> $condition_treatment_single_name_attr, // string
					'condition_treatment_plural_name'				=> $condition_treatment_plural_name, // string
					'condition_treatment_plural_name_attr'			=> $condition_treatment_plural_name_attr, // string
					'placeholder_condition_treatment_single_name'	=> $placeholder_condition_treatment_single_name, // string
					'placeholder_condition_treatment_plural_name'	=> $placeholder_condition_treatment_plural_name // string
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$labels_condition_treatment_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $labels_condition_treatment_vars;

		}

	}

	// Get the Find-a-Doc Settings values for condition labels
	function uamswp_fad_labels_condition() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$labels_condition_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $labels_condition_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $labels_condition_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$condition_single_name = get_field('conditions_single_name', 'option') ?: 'Condition';
			$condition_single_name_attr = uamswp_attr_conversion($condition_single_name);
			$condition_plural_name = get_field('conditions_plural_name', 'option') ?: 'Conditions';
			$condition_plural_name_attr = uamswp_attr_conversion($condition_plural_name);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
			$placeholder_condition_single_name = '[Condition]';
			$placeholder_condition_plural_name = '[Conditions]';

			// FacetWP labels

				// Create array for pairing FacetWP name with label if none exists

					$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

				// Add item to FacetWP labels array for Conditions facet on Providers archive/list

					$facet_labels['provider']['conditions'] = $condition_plural_name;
					$facet_labels['provider']['conditions_attr'] = $condition_plural_name_attr;

				// Add item to FacetWP labels array for Conditions facet on Clinical Resources archive/list

					$facet_labels['clinical_resource']['resource_conditions'] = $condition_plural_name;
					$facet_labels['clinical_resource']['resource_conditions_attr'] = $condition_plural_name_attr;

			// Create an array to be used on the templates and template parts

				$labels_condition_vars = array(
					'condition_single_name'				=> $condition_single_name, // string
					'condition_single_name_attr'		=> $condition_single_name_attr, // string
					'condition_plural_name'				=> $condition_plural_name, // string
					'condition_plural_name_attr'		=> $condition_plural_name_attr, // string
					'placeholder_condition_single_name'	=> $placeholder_condition_single_name, // string
					'placeholder_condition_plural_name'	=> $placeholder_condition_plural_name, // string
					'facet_labels_provider'				=> $facet_labels['provider'], // array
					'facet_labels_clinical_resource'	=> $facet_labels['clinical_resource'] // array
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$labels_condition_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $labels_condition_vars;

		}

	}

	// Get the Find-a-Doc Settings values for treatment labels
	function uamswp_fad_labels_treatment() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$labels_treatment_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $labels_treatment_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $labels_treatment_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$treatment_single_name = get_field('treatments_single_name', 'option') ?: 'Treatment/Procedure';
			$treatment_single_name_attr = uamswp_attr_conversion($treatment_single_name);
			$treatment_plural_name = get_field('treatments_plural_name', 'option') ?: 'Treatments and Procedures';
			$treatment_plural_name_attr = uamswp_attr_conversion($treatment_plural_name);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()

				$placeholder_treatment_single_name = '[Treatment]';
				$placeholder_treatment_plural_name = '[Treatments]';

			// FacetWP labels

				// Create array for pairing FacetWP name with label if none exists

					$facet_labels = ( isset($facet_labels) && is_array($facet_labels) ) ? $facet_labels : array();

				// Add item to FacetWP labels array for Treatments facet on Providers archive/list

					$facet_labels['provider']['treatments_procedures'] = $treatment_plural_name;
					$facet_labels['provider']['treatments_procedures_attr'] = $treatment_plural_name_attr;

				// Add item to FacetWP labels array for Treatments facet on Clinical Resources archive/list

					$facet_labels['clinical_resource']['resource_treatments'] = $treatment_plural_name;
					$facet_labels['clinical_resource']['resource_treatments_attr'] = $treatment_plural_name_attr;

			// Create an array to be used on the templates and template parts

				$labels_treatment_vars = array(
					'treatment_single_name'				=> $treatment_single_name, // string
					'treatment_single_name_attr'		=> $treatment_single_name_attr, // string
					'treatment_plural_name'				=> $treatment_plural_name, // string
					'treatment_plural_name_attr'		=> $treatment_plural_name_attr, // string
					'placeholder_treatment_single_name'	=> $placeholder_treatment_single_name, // string
					'placeholder_treatment_plural_name'	=> $placeholder_treatment_plural_name, // string
					'facet_labels_provider'				=> $facet_labels['provider'], // array
					'facet_labels_clinical_resource'	=> $facet_labels['clinical_resource'] // array
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$labels_treatment_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $labels_treatment_vars;

		}

	}

// Define variables for Find-a-Doc Settings values regarding ontology archive page text

	// Get the Find-a-Doc Settings values for provider archive page text
	function uamswp_fad_archive_text_provider() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$archive_text_provider_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $archive_text_provider_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $archive_text_provider_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$provider_archive_headline = get_field('provider_archive_headline', 'option') ?: 'UAMS Health Providers';
			$provider_archive_headline_attr = uamswp_attr_conversion($provider_archive_headline);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
			$placeholder_provider_archive_headline = '[Provider Archive Title]';

			// Create an array to be used on the templates and template parts

				$archive_text_provider_vars = array(
					'provider_archive_headline'				=> $provider_archive_headline, // string
					'provider_archive_headline_attr'		=> $provider_archive_headline_attr, // string
					'placeholder_provider_archive_headline'	=> $placeholder_provider_archive_headline // string
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$archive_text_provider_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $archive_text_provider_vars;

		}

	}

	// Get the Find-a-Doc Settings values for location archive page text
	function uamswp_fad_archive_text_location() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$archive_text_location_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $archive_text_location_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $archive_text_location_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$location_archive_headline = get_field('location_archive_headline', 'option') ?: 'Locations';
			$location_archive_headline_attr = uamswp_attr_conversion($location_archive_headline);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
			$placeholder_location_archive_headline = '[Location Archive Title]';

			// Create an array to be used on the templates and template parts

				$archive_text_location_vars = array(
					'location_archive_headline' 			=> $location_archive_headline, // string
					'location_archive_headline_attr'		=> $location_archive_headline_attr, // string
					'placeholder_location_archive_headline'	=> $placeholder_location_archive_headline // string
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$archive_text_location_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $archive_text_location_vars;

		}

	}

	// Get the Find-a-Doc Settings values for area of expertise archive page text
	function uamswp_fad_archive_text_expertise() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$archive_text_expertise_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $archive_text_expertise_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $archive_text_expertise_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$expertise_archive_headline = get_field('expertise_archive_headline', 'option') ?: 'Areas of Expertise';
			$expertise_archive_headline_attr = uamswp_attr_conversion($expertise_archive_headline);
			$expertise_archive_intro_text = get_field('expertise_archive_intro_text', 'option');

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
			$placeholder_expertise_archive_headline = '[Area of Expertise Archive Title]';
			$placeholder_expertise_archive_intro_text = '[Area of Expertise Archive Intro Text]';

			// Create an array to be used on the templates and template parts

				$archive_text_expertise_vars = array(
					'expertise_archive_headline'				=> $expertise_archive_headline, // string
					'expertise_archive_headline_attr'			=> $expertise_archive_headline_attr, // string
					'expertise_archive_intro_text'				=> $expertise_archive_intro_text, // string
					'placeholder_expertise_archive_headline'	=> $placeholder_expertise_archive_headline, // string
					'placeholder_expertise_archive_intro_text'	=> $placeholder_expertise_archive_intro_text // string
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$archive_text_expertise_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $archive_text_expertise_vars;

		}

	}

	// Get the Find-a-Doc Settings values for clinical resource archive page text
	function uamswp_fad_archive_text_clinical_resource() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$archive_text_clinical_resource_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $archive_text_clinical_resource_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $archive_text_clinical_resource_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$clinical_resource_archive_headline = get_field('clinical_resource_archive_headline', 'option') ?: 'Clinical Resources';
			$clinical_resource_archive_headline_attr = uamswp_attr_conversion($clinical_resource_archive_headline);

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
			$placeholder_clinical_resource_archive_headline = '[Clinical Resource Archive Title]';

			// Create an array to be used on the templates and template parts

				$archive_text_clinical_resource_vars = array(
					'clinical_resource_archive_headline'				=> $clinical_resource_archive_headline, // string
					'clinical_resource_archive_headline_attr'			=> $clinical_resource_archive_headline_attr, // string
					'placeholder_clinical_resource_archive_headline'	=> $placeholder_clinical_resource_archive_headline // string
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$archive_text_clinical_resource_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $archive_text_clinical_resource_vars;

		}

	}

	// Get the Find-a-Doc Settings values for condition archive page text
	function uamswp_fad_archive_text_condition() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$archive_text_condition_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $archive_text_condition_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $archive_text_condition_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$condition_archive_headline = get_field('conditions_archive_headline', 'option') ?: 'Conditions';
			$condition_archive_headline_attr = uamswp_attr_conversion($condition_archive_headline);
			$condition_archive_intro_text = get_field('conditions_archive_intro_text', 'option');

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
			$placeholder_condition_archive_headline = '[Condition Archive Title]';
			$placeholder_condition_archive_intro_text = '[Condition Archive Intro Text]';

			// Create an array to be used on the templates and template parts

				$archive_text_condition_vars = array(
					'condition_archive_headline'				=> $condition_archive_headline, // string
					'condition_archive_headline_attr'			=> $condition_archive_headline_attr, // string
					'condition_archive_intro_text'				=> $condition_archive_intro_text, // string
					'placeholder_condition_archive_headline'	=> $placeholder_condition_archive_headline, // string
					'placeholder_condition_archive_intro_text'	=> $placeholder_condition_archive_intro_text // string
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$archive_text_condition_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $archive_text_condition_vars;

		}

	}

	// Get the Find-a-Doc Settings values for treatment archive page text
	function uamswp_fad_archive_text_treatment() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$archive_text_treatment_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $archive_text_treatment_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $archive_text_treatment_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			$treatment_archive_headline = get_field('treatments_archive_headline', 'option') ?: 'Treatments and Procedures';
			$treatment_archive_headline_attr = uamswp_attr_conversion($treatment_archive_headline);
			$treatment_archive_intro_text = get_field('treatments_archive_intro_text', 'option');

			// Define string used to find and replace with Find-a-Doc Settings values in uamswp_fad_fpage_text_replace()
			$placeholder_treatment_archive_headline = '[Treatment Archive Title]';
			$placeholder_treatment_archive_intro_text = '[Treatment Archive Intro Text]';

			// Create an array to be used on the templates and template parts

				$archive_text_treatment_vars = array(
					'treatment_archive_headline'				=> $treatment_archive_headline, // string
					'treatment_archive_headline_attr'			=> $treatment_archive_headline_attr, // string
					'treatment_archive_intro_text'				=> $treatment_archive_intro_text, // string
					'placeholder_treatment_archive_headline'	=> $placeholder_treatment_archive_headline, // string
					'placeholder_treatment_archive_intro_text'	=> $placeholder_treatment_archive_intro_text // string
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$archive_text_treatment_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $archive_text_treatment_vars;

		}

	}

// Define variables for Find-a-Doc Settings values regarding ontology text elements on fake subpages and single profiles

	// Get the Find-a-Doc Settings values for ontology text elements in general placements

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Providers
		function uamswp_fad_fpage_text_provider_general(
			$page_id, // int
			$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_text_provider_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_text_provider_general_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_text_provider_general_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

				// Create an array to be used on the templates and template parts

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

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_text_provider_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_text_provider_general_vars;

			}


		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Locations
		function uamswp_fad_fpage_text_location_general(
			$page_id, // int
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_text_location_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_text_location_general_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_text_location_general_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

				// Create an array to be used on the templates and template parts

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

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_text_location_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_text_location_general_vars;

			}

		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Areas of Expertise
		function uamswp_fad_fpage_text_expertise_general(
			$page_id, // int
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_text_expertise_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_text_expertise_general_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_text_expertise_general_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

				// Create an array to be used on the templates and template parts

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

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_text_expertise_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_text_expertise_general_vars;

			}

		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Clinical Resources
		function uamswp_fad_fpage_text_clinical_resource_general(
			$page_id, // int
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_text_clinical_resource_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_text_clinical_resource_general_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_text_clinical_resource_general_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

				// Create an array to be used on the templates and template parts

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

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_text_clinical_resource_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_text_clinical_resource_general_vars;

			}

		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Conditions
		function uamswp_fad_fpage_text_condition_general(
			$page_id, // int
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_text_condition_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_text_condition_general_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_text_condition_general_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

				// Create an array to be used on the templates and template parts

					$fpage_text_condition_general_vars = array(
						'condition_fpage_title_general'	=> $condition_fpage_title_general, // string
						'condition_fpage_intro_general'	=> $condition_fpage_intro_general // string
					);

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_text_condition_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_text_condition_general_vars;

			}

		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Treatments
		function uamswp_fad_fpage_text_treatment_general(
			$page_id, // int
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_text_treatment_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_text_treatment_general_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_text_treatment_general_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

				// Create an array to be used on the templates and template parts

					$fpage_text_treatment_general_vars = array(
						'treatment_fpage_title_general'	=> $treatment_fpage_title_general, // string
						'treatment_fpage_intro_general'	=> $treatment_fpage_intro_general // string
					);

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_text_treatment_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_text_treatment_general_vars;

			}
		}

		// Get the Find-a-Doc Settings values for general values of ontology text elements on a fake subpage (or section) for Conditions and Treatments combined
		function uamswp_fad_fpage_text_condition_treatment_general(
			$page_id, // int
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_text_condition_treatment_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_text_condition_treatment_general_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_text_condition_treatment_general_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

				// Create an array to be used on the templates and template parts

					$fpage_text_condition_treatment_general_vars = array(
						'condition_treatment_fpage_title_general'	=> $condition_treatment_fpage_title_general, // string
						'condition_treatment_fpage_intro_general'	=> $condition_treatment_fpage_intro_general // string
					);

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_text_condition_treatment_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_text_condition_treatment_general_vars;

			}

		}

	// Get Find-a-Doc Settings values and page-level values for ontology text elements in specific subsections and single profiles

		// Get field values for fake subpage text elements in an Provider subsection (or profile)
		function uamswp_fad_fpage_text_provider(
			$page_id, // int
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_text_provider_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_text_provider_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_text_provider_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

							// Get the system settings for general placement of location item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/location.php' );

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

							// Get the system settings for general placement of area of expertise item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/expertise.php' );

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

							// Get the system settings for general placement of clinical resource item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/clinical-resource.php' );

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

						if (
							!isset($condition_fpage_title_provider) || empty($condition_fpage_title_provider)
							||
							!isset($condition_fpage_intro_provider) || empty($condition_fpage_intro_provider)
						) {

							// Get the system settings for general placement of condition item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition.php' );

						}

						if ( !isset($condition_fpage_title_provider) || empty($condition_fpage_title_provider) ) {

							$condition_fpage_title_provider = $condition_fpage_title_general; // Title

						}

						if ( !isset($condition_fpage_intro_provider) || empty($condition_fpage_intro_provider) ) {

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

						if (
							!isset($treatment_fpage_title_provider) || empty($treatment_fpage_title_provider)
							||
							!isset($treatment_fpage_intro_provider) || empty($treatment_fpage_intro_provider)
						) {

							// Get the system settings for general placement of treatment item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/treatment.php' );

						}

						if ( !isset($treatment_fpage_title_provider) || empty($treatment_fpage_title_provider) ) {

							$treatment_fpage_title_provider = $treatment_fpage_title_general; // Title

						}

						if ( !isset($treatment_fpage_intro_provider) || empty($treatment_fpage_intro_provider) ) {

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

						if (
							!isset($condition_treatment_fpage_title_provider) || empty($condition_treatment_fpage_title_provider)
							||
							!isset($condition_treatment_fpage_intro_provider) || empty($condition_treatment_fpage_intro_provider)
						) {

							// Get the system settings for general placement of combined condition and treatment item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition-treatment.php' );

						}

						if (
							!isset($condition_treatment_fpage_title_provider) || empty($condition_treatment_fpage_title_provider)
						) {

							$condition_treatment_fpage_title_provider = $condition_treatment_fpage_title_general; // Title

						}

						if (
							!isset($condition_treatment_fpage_intro_provider) || empty($condition_treatment_fpage_intro_provider)
						) {

							$condition_treatment_fpage_intro_provider = $condition_treatment_fpage_intro_general; // Intro text
						}

					// Substitute placeholder text for relevant Find-a-Doc Settings value

						$condition_treatment_fpage_title_provider = $condition_treatment_fpage_title_provider ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_title_provider, $page_titles) : ''; // Title
						$condition_treatment_fpage_intro_provider = $condition_treatment_fpage_intro_provider ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_intro_provider, $page_titles) : ''; // Intro text

				// Create an array to be used on the templates and template parts

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

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_text_provider_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_text_provider_vars;

			}

		}

		// Get field values for fake subpage text elements in an Location subsection (or profile)
		function uamswp_fad_fpage_text_location(
			$page_id, // int
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_text_location_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_text_location_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_text_location_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

							// Get the system settings for general placement of provider item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/provider.php' );

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

							// Get the system settings for general placement of location item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/location.php' );

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

							// Get the system settings for general placement of area of expertise item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/expertise.php' );

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

								// Get the system settings for general placement of clinical resource item text elements
								include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/clinical-resource.php' );

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

						if (
							!isset($condition_fpage_title_location) || empty($condition_fpage_title_location)
							||
							!isset($condition_fpage_intro_location) || empty($condition_fpage_intro_location)
						) {

							// Get the system settings for general placement of condition item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition.php' );

						}

						if ( !isset($condition_fpage_title_location) || empty($condition_fpage_title_location) ) {

							$condition_fpage_title_location = $condition_fpage_title_general; // Title

						}

						if ( !isset($condition_fpage_intro_location) || empty($condition_fpage_intro_location) ) {

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

						if (
							!isset($treatment_fpage_title_location) || empty($treatment_fpage_title_location)
							||
							!isset($treatment_fpage_intro_location) || empty($treatment_fpage_intro_location)
						) {

							// Get the system settings for general placement of treatment item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/treatment.php' );

						}

						if ( !isset($treatment_fpage_title_location) || empty($treatment_fpage_title_location) ) {

							$treatment_fpage_title_location = $treatment_fpage_title_general; // Title

						}

						if ( !isset($treatment_fpage_intro_location) || empty($treatment_fpage_intro_location) ) {

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

						if (
							!isset($condition_treatment_fpage_title_location) || empty($condition_treatment_fpage_title_location)
							||
							!isset($condition_treatment_fpage_intro_location) || empty($condition_treatment_fpage_intro_location)
						) {

							// Get the system settings for general placement of combined condition and treatment item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition-treatment.php' );

						}

						if (
							!isset($condition_treatment_fpage_title_location) || empty($condition_treatment_fpage_title_location)
						) {

							$condition_treatment_fpage_title_location = $condition_treatment_fpage_title_general; // Title

						}

						if (
							!isset($condition_treatment_fpage_intro_location) || empty($condition_treatment_fpage_intro_location)
						) {

							$condition_treatment_fpage_intro_location = $condition_treatment_fpage_intro_general; // Intro text
						}

					// Substitute placeholder text for relevant Find-a-Doc Settings value

						$condition_treatment_fpage_title_location = $condition_treatment_fpage_title_location ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_title_location, $page_titles) : ''; // Title
						$condition_treatment_fpage_intro_location = $condition_treatment_fpage_intro_location ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_intro_location, $page_titles) : ''; // Intro text

				// Create an array to be used on the templates and template parts

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

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_text_location_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_text_location_vars;

			}

		}

		// Get field values for fake subpage text elements in an Area of Expertise subsection (or profile)
		function uamswp_fad_fpage_text_expertise(
			$page_id, // int
			$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
			$ontology_type // bool
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_text_expertise_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_text_expertise_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_text_expertise_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

							// Define empty variables to prevent PHP errors

								$expertise_page_header_graphic_field = '';
								$expertise_page_header_graphic = '';
								$expertise_page_header_landingpage_field = '';
								$expertise_page_header_landingpage = '';
								$expertise_page_title = '';
								$expertise_page_intro = '';
								$expertise_page_image = '';
								$expertise_page_image_mobile = '';
								$expertise_page_header_hero = '';

							if ( $expertise_page_title_options == 'graphic' ) {

								// Get Graphic Header Style Options

									$expertise_page_header_graphic_field = get_field('expertise_page_title_graphic');
									$expertise_page_header_graphic = ( isset($expertise_page_header_graphic_field) && is_array($expertise_page_header_graphic_field) ) ? $expertise_page_header_graphic_field['page_header_graphic'] : '';

								// Set the standard page title as the title value
								$expertise_page_title = $page_title;

								if ( $expertise_page_header_graphic ) {

									// Get the intro text value
									$expertise_page_intro = $expertise_page_header_graphic['page_header_graphic_intro']; // Intro text

									// Get the background image value
									$expertise_page_image = $expertise_page_header_graphic['page_header_graphic_image']; // Background image (mobile)

								}

							} elseif ( $expertise_page_title_options == 'landingpage' ) {

								// Get Marketing Landing Page Header Style Options

									$expertise_page_header_landingpage_field = get_field('expertise_page_title_landingpage');
									$expertise_page_header_landingpage = ( isset($expertise_page_header_landingpage_field) && is_array($expertise_page_header_landingpage_field) ) ? $expertise_page_header_landingpage_field['page_header_landingpage'] : '';

								if ( $expertise_page_header_landingpage ) {

									// Get the title value
									$expertise_page_title = $expertise_page_header_landingpage['page_header_landingpage_title']; 

									// If the title is not set or is empty, use the standard page title as the fallback value
									$expertise_page_title = ( isset($expertise_page_title) && !empty($expertise_page_title) ) ? $expertise_page_title : $page_title;

									// Get the intro text value
									$expertise_page_intro = $expertise_page_header_landingpage['page_header_landingpage_intro'];

									// Get the background image values
									$expertise_page_image = $expertise_page_header_landingpage['page_header_landingpage_image']; // Background image (desktop)
									$expertise_page_image_mobile = $expertise_page_header_landingpage['page_header_landingpage_image_mobile']; // Background image (mobile)

								}

								// If the ontology item's desktop background image is not set or is empty, use the featured image as the fallback value
								if (
									(
										!isset($expertise_page_image)
										||
										empty($expertise_page_image)
									)
									&& $ontology_type
								) {
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

								// Get the system settings for general placement of provider item text elements
								include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/provider.php' );

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

								// Get the system settings for general placement of location item text elements
								include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/location.php' );

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

								// Get the system settings for general placement of area of expertise item text elements
								include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/expertise.php' );

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

								// Get the system settings for general placement of area of expertise item text elements
								include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/expertise.php' );

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

							// Get the system settings for general placement of clinical resource item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/clinical-resource.php' );

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

							if (
								!isset($condition_fpage_title_expertise) || empty($condition_fpage_title_expertise)
								||
								!isset($condition_fpage_intro_expertise) || empty($condition_fpage_intro_expertise)
							) {

								// Get the system settings for general placement of condition item text elements
								include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition.php' );

							}

							if ( !isset($condition_fpage_title_expertise) || empty($condition_fpage_title_expertise) ) {

								$condition_fpage_title_expertise = $condition_fpage_title_general; // Title

							}
							if ( !isset($condition_fpage_intro_expertise) || empty($condition_fpage_intro_expertise) ) {

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

							if (
								!isset($treatment_fpage_title_expertise) || empty($treatment_fpage_title_expertise)
								||
								!isset($treatment_fpage_intro_expertise) || empty($treatment_fpage_intro_expertise)
							) {

								// Get the system settings for general placement of treatment item text elements
								include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/treatment.php' );

							}

							if ( !isset($treatment_fpage_title_expertise) || empty($treatment_fpage_title_expertise) ) {

								$treatment_fpage_title_expertise = $treatment_fpage_title_general; // Title

							}

							if ( !isset($treatment_fpage_intro_expertise) || empty($treatment_fpage_intro_expertise) ) {

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

						if (
							!isset($condition_treatment_fpage_title_expertise) || empty($condition_treatment_fpage_title_expertise)
							||
							!isset($condition_treatment_fpage_intro_location) || empty($condition_treatment_fpage_intro_location)
						) {

							// Get the system settings for general placement of combined condition and treatment item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition-treatment.php' );

						}

						if (
							!isset($condition_treatment_fpage_title_expertise) || empty($condition_treatment_fpage_title_expertise)
						) {

							$condition_treatment_fpage_title_expertise = $condition_treatment_fpage_title_general; // Title

						}

						if (
							!isset($condition_treatment_fpage_intro_expertise) || empty($condition_treatment_fpage_intro_expertise)
						) {

							$condition_treatment_fpage_intro_expertise = $condition_treatment_fpage_intro_general; // Intro text
						}

					// Substitute placeholder text for relevant Find-a-Doc Settings value

						$condition_treatment_fpage_title_expertise = $condition_treatment_fpage_title_expertise ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_title_expertise, $page_titles) : ''; // Title
						$condition_treatment_fpage_intro_expertise = $condition_treatment_fpage_intro_expertise ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_intro_expertise, $page_titles) : ''; // Intro text

				// Create an array to be used on the templates and template parts

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

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_text_expertise_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_text_expertise_vars;

			}

		}

		// Get field values for text elements for related ontology sections in a Clinical Resource subsection (or profile)
		function uamswp_fad_fpage_text_clinical_resource(
			$page_id, // int
			$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_text_clinical_resource_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_text_clinical_resource_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_text_clinical_resource_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

							// Get the system settings for general placement of provider item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/provider.php' );

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

							// Get the system settings for general placement of location item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/location.php' );

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

							// Get the system settings for general placement of area of expertise item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/expertise.php' );

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

							// Get the system settings for general placement of clinical resource item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/clinical-resource.php' );

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

						if (
							!isset($condition_fpage_title_clinical_resource) || empty($condition_fpage_title_clinical_resource)
							||
							!isset($condition_fpage_intro_clinical_resource) || empty($condition_fpage_intro_clinical_resource)
						) {

							// Get the system settings for general placement of condition item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition.php' );

						}

						if ( !isset($condition_fpage_title_clinical_resource) || empty($condition_fpage_title_clinical_resource) ) {

							$condition_fpage_title_clinical_resource = $condition_fpage_title_general; // Title

						}
						if ( !isset($condition_fpage_intro_clinical_resource) || empty($condition_fpage_intro_clinical_resource) ) {

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

						if (
							!isset($treatment_fpage_title_clinical_resource) || empty($treatment_fpage_title_clinical_resource)
							||
							!isset($treatment_fpage_intro_clinical_resource) || empty($treatment_fpage_intro_clinical_resource)
						) {

							// Get the system settings for general placement of treatment item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/treatment.php' );

						}

						if ( !isset($treatment_fpage_title_clinical_resource) || empty($treatment_fpage_title_clinical_resource) ) {

							$treatment_fpage_title_clinical_resource = $treatment_fpage_title_general; // Title

						}

						if ( !isset($treatment_fpage_intro_clinical_resource) || empty($treatment_fpage_intro_clinical_resource) ) {

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

						if (
							!isset($condition_treatment_fpage_title_clinical_resource) || empty($condition_treatment_fpage_title_clinical_resource)
							||
							!isset($condition_treatment_fpage_intro_clinical_resource) || empty($condition_treatment_fpage_intro_clinical_resource)
						) {

							// Get the system settings for general placement of combined condition and treatment item text elements
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/condition-treatment.php' );

						}

						if (
							!isset($condition_treatment_fpage_title_clinical_resource) || empty($condition_treatment_fpage_title_clinical_resource)
						) {

							$condition_treatment_fpage_title_clinical_resource = $condition_treatment_fpage_title_general; // Title

						}

						if (
							!isset($condition_treatment_fpage_intro_clinical_resource) || empty($condition_treatment_fpage_intro_clinical_resource)
						) {

							$condition_treatment_fpage_intro_clinical_resource = $condition_treatment_fpage_intro_general; // Intro text
						}

					// Substitute placeholder text for relevant Find-a-Doc Settings value

						$condition_treatment_fpage_title_clinical_resource = $condition_treatment_fpage_title_clinical_resource ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_title_clinical_resource, $page_titles) : ''; // Title
						$condition_treatment_fpage_intro_clinical_resource = $condition_treatment_fpage_intro_clinical_resource ? uamswp_fad_fpage_text_replace($condition_treatment_fpage_intro_clinical_resource, $page_titles) : ''; // Intro text

				// Create an array to be used on the templates and template parts

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

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_text_clinical_resource_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_text_clinical_resource_vars;

			}

		}

// Define variables for Find-a-Doc Settings values regarding featured images of archive pages

	// Get the Find-a-Doc Settings value for provider archive page featured image
	function uamswp_fad_archive_image_provider() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$archive_image_provider_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $archive_image_provider_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $archive_image_provider_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Get the Find-a-Doc Settings value for the featured image of the provider archive
			$provider_archive_image = get_field('provider_archive_featured_image', 'option'); // Featured image ID

			// If the variable is not set or is empty...
			// Set a hardcoded fallback value
			$provider_archive_image = ( isset($provider_archive_image) && !empty($provider_archive_image) ) ? $provider_archive_image : ''; // Featured image

			// Create an array to be used on the templates and template parts

				$archive_image_provider_vars = array(
					'provider_archive_image'	=> $provider_archive_image // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$archive_image_provider_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $archive_image_provider_vars;

		}

	}

	// Get the Find-a-Doc Settings value for location archive page featured image
	function uamswp_fad_archive_image_location() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$archive_image_location_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $archive_image_location_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $archive_image_location_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Get the Find-a-Doc Settings value for the featured image of the location archive
			$location_archive_image = get_field('location_archive_featured_image', 'option'); // Featured image ID

			// If the variable is not set or is empty...
			// Set a hardcoded fallback value
			$location_archive_image = ( isset($location_archive_image) && !empty($location_archive_image) ) ? $location_archive_image : ''; // Featured image

			// Create an array to be used on the templates and template parts

				$archive_image_location_vars = array(
					'location_archive_image'	=> $location_archive_image // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$archive_image_location_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $archive_image_location_vars;

		}

	}

	// Get the Find-a-Doc Settings value for area of expertise archive page featured image
	function uamswp_fad_archive_image_expertise() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$archive_image_expertise_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $archive_image_expertise_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $archive_image_expertise_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Get the Find-a-Doc Settings value for the featured image of the expertise archive
			$expertise_archive_image = get_field('expertise_archive_featured_image', 'option'); // Featured image ID

			// If the variable is not set or is empty...
			// Set a hardcoded fallback value
			$expertise_archive_image = ( isset($expertise_archive_image) && !empty($expertise_archive_image) ) ? $expertise_archive_image : ''; // Featured image

			// Create an array to be used on the templates and template parts

				$archive_image_expertise_vars = array(
					'expertise_archive_image'	=> $expertise_archive_image // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$archive_image_expertise_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $archive_image_expertise_vars;

		}

	}

	// Get the Find-a-Doc Settings value for clinical resource archive page featured image
	function uamswp_fad_archive_image_clinical_resource() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$archive_image_clinical_resource_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $archive_image_clinical_resource_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $archive_image_clinical_resource_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// Get the Find-a-Doc Settings value for the featured image of the clinical resource archive
			$clinical_resource_archive_image = get_field('clinical_resource_archive_featured_image', 'option'); // Featured image ID

			// If the variable is not set or is empty...
			// Set a hardcoded fallback value
			$clinical_resource_archive_image = ( isset($clinical_resource_archive_image) && !empty($clinical_resource_archive_image) ) ? $clinical_resource_archive_image : ''; // Featured image

			// Create an array to be used on the templates and template parts

				$archive_image_clinical_resource_vars = array(
					'clinical_resource_archive_image'	=> $clinical_resource_archive_image // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$archive_image_clinical_resource_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $archive_image_clinical_resource_vars;

		}

	}

// Define variables for Find-a-Doc Settings values regarding the featured images of fake subpages and single profiles

	// Get the Find-a-Doc Settings values for the featured images of fake subpages (or sections) in general placements

		// Get the Find-a-Doc Settings value for the featured image of a fake subpage (or section) for Providers in general placements
		function uamswp_fad_fpage_image_provider_general() {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$fpage_image_provider_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_image_provider_general_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_image_provider_general_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Get the Find-a-Doc Settings value
				$provider_fpage_image_general = get_field('provider_fpage_featured_image_general', 'option'); // Featured image ID

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value
				$provider_fpage_image_general = ( isset($provider_fpage_image_general) && !empty($provider_fpage_image_general) ) ? $provider_fpage_image_general : ''; // Featured image

				// Create an array to be used on the templates and template parts

					$fpage_image_provider_general_vars = array(
						'provider_fpage_image_general'	=> $provider_fpage_image_general // int
					);

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars', // Required // String added to transient name for disambiguation.
					$fpage_image_provider_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_image_provider_general_vars;

			}

		}

		// Get the Find-a-Doc Settings values for the featured image of a fake subpage (or section) for Locations in general placements
		function uamswp_fad_fpage_image_location_general() {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$fpage_image_location_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_image_location_general_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_image_location_general_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

				// Create an array to be used on the templates and template parts

					$fpage_image_location_general_vars = array(
						'location_fpage_image_general'				=> $location_fpage_image_general, // int
						'location_descendant_fpage_image_general'	=> $location_descendant_fpage_image_general // int
					);

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars', // Required // String added to transient name for disambiguation.
					$fpage_image_location_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_image_location_general_vars;

			}

		}

		// Get the Find-a-Doc Settings values for the featured image of a fake subpage (or section) for Areas of Expertise in general placements
		function uamswp_fad_fpage_image_expertise_general() {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$fpage_image_expertise_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_image_expertise_general_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_image_expertise_general_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

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

				// Create an array to be used on the templates and template parts

					$fpage_image_expertise_general_vars = array(
						'expertise_fpage_image_general'				=> $expertise_fpage_image_general, // int
						'expertise_descendant_fpage_image_general'	=> $expertise_descendant_fpage_image_general // int
					);

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars', // Required // String added to transient name for disambiguation.
					$fpage_image_expertise_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_image_expertise_general_vars;

			}

		}

		// Get the Find-a-Doc Settings value for the featured image of a fake subpage (or section) for Clinical Resources in general placements
		function uamswp_fad_fpage_image_clinical_resource_general() {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$fpage_image_clinical_resource_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_image_clinical_resource_general_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_image_clinical_resource_general_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Get the Find-a-Doc Settings value
				$clinical_resource_fpage_image_general = get_field('clinical_resource_fpage_featured_image_general', 'option'); // Featured image ID

				// If the variable is not set or is empty...
				// Set a hardcoded fallback value
				$clinical_resource_fpage_image_general = ( isset($clinical_resource_fpage_image_general) && !empty($clinical_resource_fpage_image_general) ) ? $clinical_resource_fpage_image_general : ''; // Featured image ID

				// Create an array to be used on the templates and template parts

					$fpage_image_clinical_resource_general_vars = array(
						'clinical_resource_fpage_image_general'	=> $clinical_resource_fpage_image_general // int
					);

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars', // Required // String added to transient name for disambiguation.
					$fpage_image_clinical_resource_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_image_clinical_resource_general_vars;

			}

		}

	// Get Find-a-Doc Settings values and page-level values for the featured images of specific subsections (or profiles)

		// Get field values for the featured image of a fake subpage (or section) in an Provider subsection (or profile)
		function uamswp_fad_fpage_image_provider( $page_id ) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_image_provider_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_image_provider_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_image_provider_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Locations

					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Provider subsection (or profile)
					$location_fpage_image_provider = get_field('location_fpage_featured_image_provider', 'option');

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
					if ( !isset($location_fpage_image_provider) || empty($location_fpage_image_provider) ) {

						// System settings for image elements in general placements of location fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/location.php' );

						$location_fpage_image_provider = $location_fpage_image_general; // Featured image

					}

				// Areas of Expertise

					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Provider subsection (or profile)
					$expertise_fpage_image_provider = get_field('expertise_fpage_featured_image_provider', 'option');

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
					if ( !isset($expertise_fpage_image_provider) || empty($expertise_fpage_image_provider) ) {

						// System settings for image elements in general placements of area of expertise fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/expertise.php' );

						$expertise_fpage_image_provider = $expertise_fpage_image_general; // Featured image

					}

				// Clinical Resources

					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Provider subsection (or profile)
					$clinical_resource_fpage_image_provider = get_field('clinical_resource_fpage_featured_image_provider', 'option');

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
					if ( !isset($clinical_resource_fpage_image_provider) || empty($clinical_resource_fpage_image_provider) ) {

						// System settings for image elements in general placements of clinical resource fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/clinical-resource.php' );

						$clinical_resource_fpage_image_provider = $clinical_resource_fpage_image_general; // Featured image

					}

				// Create an array to be used on the templates and template parts

					$fpage_image_provider_vars = array(
						'location_fpage_image_provider'				=> $location_fpage_image_provider, // int
						'expertise_fpage_image_provider'			=> $expertise_fpage_image_provider, // int
						'clinical_resource_fpage_image_provider'	=> $clinical_resource_fpage_image_provider // int
					);

				// Set/update the value of the transient
				uamswp_fad_get_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_image_provider_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_image_provider_vars;

			}

		}

		// Get field values for the featured image of a fake subpage (or section) in an Location subsection (or profile)
		function uamswp_fad_fpage_image_location( $page_id ) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_image_location_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_image_location_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_image_location_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Providers

					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Location subsection (or profile)
					$provider_fpage_image_location = get_field('provider_fpage_featured_image_location', 'option');

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
					if ( !isset($provider_fpage_image_location) || empty($provider_fpage_image_location) ) {

						// System settings for image elements in general placements of provider fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/provider.php' );

						$provider_fpage_image_location = $provider_fpage_image_general; // Featured image
					}

				// Descendant Locations

					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Location subsection (or profile)
					$location_descendant_fpage_image_location = get_field('location_descendant_fpage_featured_image_location', 'option');

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
					if ( !isset($location_descendant_fpage_image_location) || empty($location_descendant_fpage_image_location) ) {

						// System settings for image elements in general placements of location fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/location.php' );

						$location_descendant_fpage_image_location = $location_descendant_fpage_image_general; // Featured image

					}

				// Areas of Expertise

					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Location subsection (or profile)
					$expertise_fpage_image_location = get_field('expertise_fpage_featured_image_location', 'option');

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
					if ( !isset($expertise_fpage_image_location) || empty($expertise_fpage_image_location) ) {

						// System settings for image elements in general placements of area of expertise fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/expertise.php' );

						$expertise_fpage_image_location = $expertise_fpage_image_general; // Featured image

					}

				// Clinical Resources

					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in an Location subsection (or profile)
					$clinical_resource_fpage_image_location = get_field('clinical_resource_fpage_featured_image_location', 'option');

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
					if ( !isset($clinical_resource_fpage_image_location) || empty($clinical_resource_fpage_image_location) ) {

						// System settings for image elements in general placements of clinical resource fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/clinical-resource.php' );

						$clinical_resource_fpage_image_location = $clinical_resource_fpage_image_general; // Featured image

					}

				// Create an array to be used on the templates and template parts

					$fpage_image_location_vars = array(
						'provider_fpage_image_location'				=> $provider_fpage_image_location, // int
						'location_descendant_fpage_image_location'	=> $location_descendant_fpage_image_location, // int
						'expertise_fpage_image_location'			=> $expertise_fpage_image_location, // int
						'clinical_resource_fpage_image_location'	=> $clinical_resource_fpage_image_location // int
					);

				// Set/update the value of the transient
				uamswp_fad_get_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_image_location_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_image_location_vars;

			}

		}

		// Get field values for the featured image of a fake subpage (or section) in an Area of Expertise subsection (or profile)
		function uamswp_fad_fpage_image_expertise( $page_id ) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_image_expertise_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_image_expertise_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_image_expertise_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Overview

					// Get the page-level featured image value
					$expertise_featured_image = get_field('_thumbnail_id');

					// Crop/resize the image
					if ( $expertise_featured_image && function_exists( 'fly_add_image_size' ) ) {
						$expertise_featured_image_url = image_sizer($expertise_featured_image, 1600, 900, 'center', 'center');
					} elseif ( $expertise_featured_image ) {
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

						// System settings for image elements in general placements of provider fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/provider.php' );

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

						// System settings for image elements in general placements of location fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/location.php' );

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

						// System settings for image elements in general placements of area of expertise fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/expertise.php' );

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

						// System settings for image elements in general placements of area of expertise fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/expertise.php' );

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

						// System settings for image elements in general placements of clinical resource fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/clinical-resource.php' );

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

				// Create an array to be used on the templates and template parts

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

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_image_expertise_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_image_expertise_vars;

			}

		}

		// Get field values for the featured image of a fake subpage (or section) in a Clinical Resource subsection (or profile)
		function uamswp_fad_fpage_image_clinical_resource( $page_id ) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$fpage_image_clinical_resource_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $fpage_image_clinical_resource_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $fpage_image_clinical_resource_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Providers

					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in a Clinical Resource subsection (or profile)
					$provider_fpage_image_clinical_resource = get_field('provider_fpage_featured_image_clinical_resource', 'option');

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
					if ( !isset($provider_fpage_image_clinical_resource) || empty($provider_fpage_image_clinical_resource) ) {

						// System settings for image elements in general placements of provider fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/provider.php' );

						$provider_fpage_image_clinical_resource = $provider_fpage_image_general; // Featured image

					}

				// Locations

					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in a Clinical Resource subsection (or profile)
					$location_fpage_image_clinical_resource = get_field('location_fpage_featured_image_clinical_resource', 'option');

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
					if ( !isset($location_fpage_image_clinical_resource) || empty($location_fpage_image_clinical_resource) ) {

						// System settings for image elements in general placements of location fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/location.php' );

						$location_fpage_image_clinical_resource = $location_fpage_image_general; // Featured image

					}

				// Areas of Expertise

					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in a Clinical Resource subsection (or profile)
					$expertise_fpage_image_clinical_resource = get_field('expertise_fpage_featured_image_clinical_resource', 'option');

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
					if ( !isset($expertise_fpage_image_clinical_resource) || empty($expertise_fpage_image_clinical_resource) ) {

						// System settings for image elements in general placements of area of expertise fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/expertise.php' );

						$expertise_fpage_image_clinical_resource = $expertise_fpage_image_general; // Featured image

					}

				// Related Clinical Resources

					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in a Clinical Resource subsection (or profile)
					$clinical_resource_fpage_image_clinical_resource = get_field('clinical_resource_fpage_featured_image_clinical_resource', 'option');

					// If the variable is not set or is empty...
					// Get the Find-a-Doc Settings value for the featured image of this type of fake subpage (or profile) in general placements
					if ( !isset($clinical_resource_fpage_image_clinical_resource) || empty($clinical_resource_fpage_image_clinical_resource) ) {

						// System settings for image elements in general placements of clinical resource fake subpages (or sections)
						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/general-placement/clinical-resource.php' );

						$clinical_resource_fpage_image_clinical_resource = $clinical_resource_fpage_image_general; // Featured image

					}

				// Create an array to be used on the templates and template parts

					$fpage_image_clinical_resource_vars = array(
						'provider_fpage_image_clinical_resource'			=> $provider_fpage_image_clinical_resource, // int
						'location_fpage_image_clinical_resource'			=> $location_fpage_image_clinical_resource, // int
						'expertise_fpage_image_clinical_resource'			=> $expertise_fpage_image_clinical_resource, // int
						'clinical_resource_fpage_image_clinical_resource'	=> $clinical_resource_fpage_image_clinical_resource // int
					);

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$fpage_image_clinical_resource_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $fpage_image_clinical_resource_vars;

			}

		}

// Define the general maximum number of ontology items to display in a fake subpage (or section)

	// Define the general maximum number of items to display on a fake subpage (or section) for Clinical Resource
	function uamswp_fad_posts_per_page_clinical_resource_general() {

		// Retrieve the value of the transient
		uamswp_fad_get_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$posts_per_page_clinical_resource_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		if ( !empty( $posts_per_page_clinical_resource_general_vars ) ) {

			/* 
			 * The transient exists.
			 * Return the variable.
			 */

			return $posts_per_page_clinical_resource_general_vars;

		} else {

			/* 
			 * The transient does not exist.
			 * Define the variable again.
			 */

			// General maximum number of clinical resources to display on a fake subpage (-1, 4, 6, 8, 10 or 12)
			$clinical_resource_posts_per_page_fpage = -1;

			// General maximum number of clinical resources to display in a section on a single profile (-1, 4, 6, 8, 10 or 12)
			$clinical_resource_posts_per_page_section = 4;

			// Create an array to be used on the templates and template parts

				$posts_per_page_clinical_resource_general_vars = array(
					'clinical_resource_posts_per_page_fpage'	=> $clinical_resource_posts_per_page_fpage, // int
					'clinical_resource_posts_per_page_section'	=> $clinical_resource_posts_per_page_section // int
				);

			// Set/update the value of the transient
			uamswp_fad_set_transient(
				'vars', // Required // String added to transient name for disambiguation.
				$posts_per_page_clinical_resource_general_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			// Return the variable
			return $posts_per_page_clinical_resource_general_vars;

		}

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

// Get the Find-a-Doc Settings values for general patient appointment information
function uamswp_fad_appointment_patients() {

	// Retrieve the value of the transient
	uamswp_fad_get_transient(
		'vars', // Required // String added to transient name for disambiguation.
		$appointment_patients_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
		__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
	);

	if ( !empty( $appointment_patients_vars ) ) {

		/* 
		 * The transient exists.
		 * Return the variable.
		 */

		return $appointment_patients_vars;

	} else {

		/* 
		 * The transient does not exist.
		 * Define the variable again.
		 */

		// Phone Number Information

			$appointment_patients_phone_number_both_fallback = '(501) 686-8800';

			// New Patients Only
			$appointment_patients_phone_number_new = format_phone_dash(get_field('appointment_patients_phone_number_new', 'option')) ?: $appointment_patients_phone_number_both_fallback;
			$appointment_patients_phone_label_new = get_field('appointment_patients_phone_label_new', 'option') ?: '';
			$appointment_patients_phone_label_new_attr = uamswp_attr_conversion($appointment_patients_phone_label_new);
			$appointment_patients_phone_info_new = get_field('appointment_patients_phone_info_new', 'option') ?: '';

			// Existing Patients Only
			$appointment_patients_phone_number_existing = format_phone_dash(get_field('appointment_patients_phone_number_existing', 'option')) ?: $appointment_patients_phone_number_both_fallback;
			$appointment_patients_phone_label_existing = get_field('appointment_patients_phone_label_existing', 'option') ?: '';
			$appointment_patients_phone_label_existing_attr = uamswp_attr_conversion($appointment_patients_phone_label_existing);
			$appointment_patients_phone_info_existing = get_field('appointment_patients_phone_info_existing', 'option') ?: '';

			// Both New and Existing Patients
			$appointment_patients_phone_number_both = format_phone_dash(get_field('appointment_patients_phone_number_both', 'option')) ?: $appointment_patients_phone_number_both_fallback;
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

		// Create an array to be used on the templates and template parts

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

		// Set/update the value of the transient
		uamswp_fad_set_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$appointment_patients_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		// Return the variable
		return $appointment_patients_vars;

	}

}

// Get the Find-a-Doc Settings values for general patient referral information
function uamswp_fad_appointment_refer() {

	// Retrieve the value of the transient
	uamswp_fad_get_transient(
		'vars', // Required // String added to transient name for disambiguation.
		$appointment_refer_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
		__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
	);

	if ( !empty( $appointment_refer_vars ) ) {

		/* 
		 * The transient exists.
		 * Return the variable.
		 */

		return $appointment_refer_vars;

	} else {

		/* 
		 * The transient does not exist.
		 * Define the variable again.
		 */

		// Phone Number Information

			$appointment_refer_phone_number = format_phone_dash(get_field('appointment_refer_phone_number', 'option')) ?: '';
			$appointment_refer_phone_label = get_field('appointment_refer_phone_label', 'option') ?: '';
			$appointment_refer_phone_label_attr = uamswp_attr_conversion($appointment_refer_phone_label);
			$appointment_refer_phone_info = get_field('appointment_refer_phone_info', 'option') ?: '';

		// Fax Information

			$appointment_refer_fax_number = format_phone_dash(get_field('appointment_refer_fax_number', 'option')) ?: '';
			$appointment_refer_fax_label = get_field('appointment_refer_fax_label', 'option') ?: '';
			$appointment_refer_fax_label_attr = uamswp_attr_conversion($appointment_refer_fax_label);
			$appointment_refer_fax_info = get_field('appointment_refer_fax_info', 'option') ?: '';

		// Webpage Information

			$appointment_refer_web_url = get_field('appointment_refer_web_url', 'option');
			$appointment_refer_web_url = $appointment_refer_web_url ? user_trailingslashit($appointment_refer_web_url): '';
			$appointment_refer_web_label = get_field('appointment_refer_web_label', 'option') ?: '';
			$appointment_refer_web_label_attr = uamswp_attr_conversion($appointment_refer_web_label);
			$appointment_refer_web_info = get_field('appointment_refer_web_info', 'option') ?: '';

		// Create an array to be used on the templates and template parts

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

		// Set/update the value of the transient
		uamswp_fad_set_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$appointment_refer_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		// Return the variable
		return $appointment_refer_vars;

	}

}

// Get the Find-a-Doc Settings value for jump links (a.k.a. anchor links)
function uamswp_fad_labels_jump_links() {

	// Retrieve the value of the transient
	uamswp_fad_get_transient(
		'vars', // Required // String added to transient name for disambiguation.
		$labels_jump_links_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
		__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
	);

	if ( !empty( $labels_jump_links_vars ) ) {

		/* 
		 * The transient exists.
		 * Return the variable.
		 */

		return $labels_jump_links_vars;

	} else {

		/* 
		 * The transient does not exist.
		 * Define the variable again.
		 */

		// Jump Links Section Title
		$fad_jump_links_title = get_field('fad_jump_links_title', 'option') ?: 'Content';

		// Create an array to be used on the templates and template parts

			$labels_jump_links_vars = array(
				'fad_jump_links_title'	=> $fad_jump_links_title // string
			);
			return $labels_jump_links_vars;

		// Set/update the value of the transient
		uamswp_fad_set_transient(
			'vars', // Required // String added to transient name for disambiguation.
			$labels_jump_links_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		// Return the variable
		return $labels_jump_links_vars;

	}

}

// Set image values for Open Graph, Twitter and Oembed
function uamswp_meta_image_values( $featured_image ) {

	// Retrieve the value of the transient
	uamswp_fad_get_transient(
		'vars_' . $featured_image, // Required // String added to transient name for disambiguation.
		$meta_image_values_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
		__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
	);

	if ( !empty( $meta_image_values_vars ) ) {

		/* 
		 * The transient exists.
		 * Return the variable.
		 */

		return $meta_image_values_vars;

	} else {

		/* 
		 * The transient does not exist.
		 * Define the variable again.
		 */

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

		if ( $featured_image && function_exists( 'fly_add_image_size' ) ) {
			$meta_og_image = image_sizer($featured_image, $image_size['opengraph']['width'], $image_size['opengraph']['height'], 'center', 'center');
			$meta_og_image_width = $image_size['opengraph']['width'];
			$meta_og_image_height = $image_size['opengraph']['height'];
			$meta_twitter_image = image_sizer($featured_image, $image_size['twitter']['width'], $image_size['twitter']['height'], 'center', 'center');
			$meta_twitter_image_width = $image_size['twitter']['width'];
			$meta_twitter_image_height = $image_size['twitter']['height'];
		} elseif ( $featured_image ) {
			$meta_og_image = wp_get_attachment_url( $featured_image, 'aspect-16-9' );
			$meta_og_image_width = image_get_intermediate_size( $featured_image, 'aspect-16-9' )['width'];
			$meta_og_image_height = image_get_intermediate_size( $featured_image, 'aspect-16-9' )['height'];
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

		$meta_twitter_image_alt = $featured_image ? get_post_meta( $featured_image, '_wp_attachment_image_alt', true ) : ''; // string // Alt text of the image in $meta_twitter_image

		// Create an array to be used on the templates and template parts

			$meta_image_values_vars = array(
				'meta_og_image'				=> $meta_og_image, // string
				'meta_og_image_width'		=> $meta_og_image_width, // int
				'meta_og_image_height'		=> $meta_og_image_height, // int
				'meta_twitter_image'		=> $meta_twitter_image, // string
				'meta_twitter_image_width'	=> $meta_twitter_image_width, // int
				'meta_twitter_image_height'	=> $meta_twitter_image_height, // int
				'meta_twitter_image_alt'	=> $meta_twitter_image_alt // string // Alt text of the image in $meta_twitter_image
			);

		// Set/update the value of the transient
		uamswp_fad_set_transient(
			'vars_' . $featured_image, // Required // String added to transient name for disambiguation.
			$meta_image_values_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		// Return the variable
		return $meta_image_values_vars;

	}

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

// Create str_contains function that is available in PHP 8

	if ( !function_exists('str_contains') ) {

		function str_contains(
			string $haystack, // string // Required // The string to search in.
			string $needle // string // Required // The substring to search for in the haystack.
		) {

			/*

				Performs a case-sensitive check indicating if needle is contained in haystack.

				Returns true if needle is in haystack, false otherwise.

			*/

			return $needle !== '' || mb_strpos($haystack, $needle) !== false;

		}

	}

// Construct UAMS Text & Image Overlay Block on Ontology Fake Subpages
function uamswp_fad_fpage_text_image_overlay(
	$page_id, // int
	$page_titles, // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
	$current_fpage = '', // string (optional) // Fake subpage slug
	$ontology_type = true, // bool (optional)
	$text_image_overlay_id = 'archives' // string (optional) // Section ID attribute value
) {

	// Retrieve the value of the transients
	uamswp_fad_get_transient(
		'row_0_' . $page_id, // Required // String added to transient name for disambiguation.
		$text_image_overlay_row_0, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
		__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
	);
	uamswp_fad_get_transient(
		'row_1_' . $page_id, // Required // String added to transient name for disambiguation.
		$text_image_overlay_row_1, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
		__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
	);

	if (
		!empty( $text_image_overlay_row_0 )
		&&
		!empty( $text_image_overlay_row_1 )
	) {

		/* 
		 * The transients exists.
		 * Load the template part.
		 */

		include( UAMS_FAD_PATH . '/templates/parts/html/section/text-image-overlay.php' );

	} else {

		/* 
		 * The transient does not exist.
		 * Define the variables again.
		 */

		// Check/define variables

			// Get the ontology subsection values
			include( UAMS_FAD_PATH . '/templates/parts/vars/sys/ontology-subsection.php' );

			// Page Titles Array
			$page_titles = is_array($page_titles) ? $page_titles : array( $page_titles );
			$page_titles['page_title'] = isset($page_titles['page_title']) ? $page_titles['page_title'] : get_the_title();

			// Ontology Type
			$ontology_type = !empty($ontology_type) ? $ontology_type : true;

			// Get system settings for text elements in an area of expertise subsection (or profile)
			include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/specific-placement/expertise.php' );

			// Values Specific to the Fake Subpage

				// Define whether to display the item linking to the parent archive in the overlay block
				$show_parent_archive = false;

				if ( $current_fpage == 'providers' ) {

					// Get the system settings for the image elements of the provider archive
					include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/archive/provider.php' );

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

					// Get the system settings for the image elements of the location archive
					include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/archive/location.php' );

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

					// Get the system settings for the image elements of the area of expertise archive
					include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/archive/expertise.php' );

					// Create array of main archive attributes
					$text_image_overlay_main_archive = array(
						'heading'			=> $expertise_descendant_fpage_ref_main_title_expertise, // Heading text, limited to 65 characters // str
						'body'				=> $expertise_descendant_fpage_ref_main_intro_expertise, // Body text, limited to 280 characters // str
						'button_text'		=> $expertise_descendant_fpage_ref_main_link_expertise, // Link text, limited to 27 characters // str
						'button_url'		=> get_post_type_archive_link('expertise'), // Full URL // str
						'image'				=> $expertise_archive_image // Background image ID // int
					);

				} elseif ( $current_fpage == 'related' ) {

					// Get the system settings for the image elements of the area of expertise archive
					include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/archive/expertise.php' );

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

					// Get the system settings for the image elements of the clinical resource archive
					include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/archive/clinical-resource.php' );

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

		// Pass the values to the UAMS Text & Image Overlay Block template part

			if (
				isset($parent_archive) && !empty($parent_archive)
				&&
				isset($main_archive) && !empty($main_archive)
			) {

				$text_image_overlay_row_0 = $parent_archive; // Values for the first item
				$text_image_overlay_row_1 = $main_archive; // Values for the second item

			} elseif (
				isset($main_archive) && !empty($main_archive)
			) {

				$text_image_overlay_row_0 = $main_archive; // Values for the first item
				$text_image_overlay_row_1 = ''; // Values for the second item

			} else {

				$text_image_overlay_row_0 = ''; // Values for the first item
				$text_image_overlay_row_1 = ''; // Values for the second item

			}

		// Set/update the value of the transients
		uamswp_fad_set_transient(
			'row_0_' . $page_id, // Required // String added to transient name for disambiguation.
			$text_image_overlay_row_0, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);
		uamswp_fad_set_transient(
			'row_1_' . $page_id, // Required // String added to transient name for disambiguation.
			$text_image_overlay_row_1, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
		);

		include( UAMS_FAD_PATH . '/templates/parts/html/section/text-image-overlay.php' );

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

// Transients

	// Transient name

		function uamswp_fad_transient_name(
			string $string, // String added to transient name for disambiguation.
			string $function = '' // Function name added to transient name for disambiguation. // Optional
		) {

			/* 
			 * Combines various elements to avoid transient name collisions and to make the 
			 * transients more easily found in a list.
			 * 
			 * Include __FUNCTION__ in the $function argument to include the name of the 
			 * current function in the transient name.
			 */

			$transient_name = isset($string) ? $string : '';

			if ( empty($transient_name) ) {

				return '';

			}

			// Remove UAMS_FAD_TRANSIENT_PREFIX from beginning of $function value if present

				$function = isset($function) ? $function : '';
				if (substr($function, 0, strlen(UAMS_FAD_TRANSIENT_PREFIX)) == UAMS_FAD_TRANSIENT_PREFIX) {

					$function = substr( $function, strlen(UAMS_FAD_TRANSIENT_PREFIX) );

				}

			$transient_name = UAMS_FAD_TRANSIENT_PREFIX . ( $function ? $function . '_' : '' ) . $transient_name . UAMS_FAD_TRANSIENT_SUFFIX;

			return $transient_name;

		}

	// Get transient

		function uamswp_fad_get_transient(
			string $string, // Required // String added to transient name for disambiguation.
			&$var, // Required // Input the variable that should be populated with the value stored in the transient
			$function = '' // Optional // Function name added to transient name for disambiguation.
		) {

			/* 
			 * Combines with the uamswp_fad_transient_name() function to streamline the code.
			 * 
			 * Arguments will be identical to those used in the uamswp_fad_set_transient() 
			 * function.
			 * 
			 * Include __FUNCTION__ in the $function argument to include the name of the 
			 * current function in the transient name.
			 */

			$var = get_transient( // WordPress function that retrieves the value of a transient
				uamswp_fad_transient_name(
					$string, // string // Required // String added to transient name for disambiguation.
					$function // string // Optional // Function name added to transient name for disambiguation.
				)
			);

			return;
		}

	// Set transient

		function uamswp_fad_set_transient(
			string $string, // Required // String added to transient name for disambiguation.
			$value, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
			string $function = '' // Optional // Function name added to transient name for disambiguation.
		) {

			/* 
			 * Combines with the uamswp_fad_transient_name() function to streamline the code.
			 * 
			 * Arguments will be identical to those used in the uamswp_fad_get_transient() 
			 * function.
			 * 
			 * Include __FUNCTION__ in the $function argument to include the name of the 
			 * current function in the transient name.
			 */

			set_transient( // WordPress function that sets/updates the value of a transient
				uamswp_fad_transient_name(
					$string, // Required // String added to transient name for disambiguation.
					$function // Optional // Function name added to transient name for disambiguation.
				), // string // Required // Transient name. Expected to not be SQL-escaped. Must be 172 characters or fewer in length.
				$value, // mixed // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				DAY_IN_SECONDS // int // Optional // Time until expiration in seconds // Default 0 (no expiration)
			);

			return;
		}

// Profile and card field values

	// Provider profile field values

		function uamswp_fad_provider_profile_fields(
			$page_id // int // ID of the profile
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id,
				$provider_profile_fields_vars,
				__FUNCTION__
			);

			if ( !empty( $provider_profile_fields_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $provider_profile_fields_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Create a variables array to be used on the templates and template parts
				$provider_profile_fields_vars = array();

				// Get the field values

					// First Name

						$provider_first_name = get_field( 'physician_first_name', $page_id ); // string

						$provider_profile_fields_vars['provider_first_name'] = isset($provider_first_name) ? $provider_first_name : ''; // Add to the variables array

					// Middle Name

						$provider_middle_name = get_field( 'physician_middle_name', $page_id ); // string

						$provider_profile_fields_vars['provider_middle_name'] = isset($provider_middle_name) ? $provider_middle_name : ''; // Add to the variables array

					// Last Name

						$provider_last_name = get_field( 'physician_last_name', $page_id ); // string

						$provider_profile_fields_vars['provider_last_name'] = isset($provider_last_name) ? $provider_last_name : ''; // Add to the variables array

					// Generational Suffix

						$provider_pedigree = get_field( 'physician_pedigree', $page_id ); // string 

						$provider_profile_fields_vars['provider_pedigree'] = isset($provider_pedigree) ? $provider_pedigree : ''; // Add to the variables array

					// Degree and/or Credential (taxonomy multi-select)

						$provider_degree = get_field( 'physician_degree', $page_id ); // int[]

						foreach ( $provider_degree as $item ) {

							$provider_degree_array[$item] = array(
								'name'	=> get_term( $item, 'degree')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_degree'] = isset($provider_degree) ? $provider_degree : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_degree_array'] = isset($provider_degree_array) ? $provider_degree_array : ''; // Add to the variables array

					// Prefix

						$provider_prefix = get_field( 'physician_prefix', $page_id ); // string 

						$provider_profile_fields_vars['provider_prefix'] = isset($provider_prefix) ? $provider_prefix : ''; // Add to the variables array

					// Gender

						$provider_gender = get_field( 'physician_gender', $page_id ); // string 

						$provider_profile_fields_vars['provider_gender'] = isset($provider_gender) ? $provider_gender : ''; // Add to the variables array

					// Searchable

						$provider_searchable = get_field( 'physician_searchable', $page_id ); // bool 

						$provider_profile_fields_vars['provider_searchable'] = isset($provider_searchable) ? $provider_searchable : ''; // Add to the variables array

					// Full Name

						$provider_full_name = get_field( 'physician_full_name', $page_id ); // string 

						$provider_profile_fields_vars['provider_full_name'] = isset($provider_full_name) ? $provider_full_name : ''; // Add to the variables array

					// Headshot

						$_thumbnail_id = get_field( '_thumbnail_id', $page_id ); // int

						$provider_profile_fields_vars['_thumbnail_id'] = isset($_thumbnail_id) ? $_thumbnail_id : ''; // Add to the variables array 

					// Wide Image

						$provider_image_wide = get_field( 'physician_image_wide', $page_id ); // int 

						$provider_profile_fields_vars['provider_image_wide'] = isset($provider_image_wide) ? $provider_image_wide : ''; // Add to the variables array

					// Is the Provider a Resident?

						$provider_resident = get_field( 'physician_resident', $page_id ); // bool 

						$provider_profile_fields_vars['provider_resident'] = isset($provider_resident) ? $provider_resident : ''; // Add to the variables array

					// Does the Provider See Patients Via Appointments?

						$provider_eligible_appointments = get_field( 'physician_eligible_appointments', $page_id ); // bool 

						$provider_profile_fields_vars['provider_eligible_appointments'] = isset($provider_eligible_appointments) ? $provider_eligible_appointments : ''; // Add to the variables array

					// Is the Provider a Primary Care Provider?

						$provider_primary_care = get_field( 'physician_primary_care', $page_id ); // bool 

						$provider_profile_fields_vars['provider_primary_care'] = isset($provider_primary_care) ? $provider_primary_care : ''; // Add to the variables array

					// Is the Provider Accepting New Patients?

						$provider_accepting_patients = get_field( 'physician_accepting_patients', $page_id ); // bool 

						$provider_profile_fields_vars['provider_accepting_patients'] = isset($provider_accepting_patients) ? $provider_accepting_patients : ''; // Add to the variables array

					// Does the Provider Require a Referral for New Patients?

						$provider_referral_required = get_field( 'physician_referral_required', $page_id ); // bool 

						$provider_profile_fields_vars['provider_referral_required'] = isset($provider_referral_required) ? $provider_referral_required : ''; // Add to the variables array

					// Does the Provider Offer Second Opinions?

						$provider_second_opinion = get_field( 'physician_second_opinion', $page_id ); // bool 

						$provider_profile_fields_vars['provider_second_opinion'] = isset($provider_second_opinion) ? $provider_second_opinion : ''; // Add to the variables array

					// Appointment Link

						$provider_appointment_link = get_field( 'physician_appointment_link', $page_id ); // string

						$provider_profile_fields_vars['provider_appointment_link'] = isset($provider_appointment_link) ? $provider_appointment_link : ''; // Add to the variables array 

					// Clinical Job Title (taxonomy select)

						$provider_title = get_field( 'physician_title', $page_id ); // string|int[] // Term ID(s)
						$provider_title = is_array($provider_title) ? $provider_title : array($provider_title); // int[] // Term ID(s)

						foreach ( $provider_title as $item ) {

							$provider_title_array[$item] = array(
								'name'	=> get_term( $item, 'clinical_title')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_title'] = isset($provider_title) ? $provider_title : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_title_array'] = isset($provider_title_array) ? $provider_title_array : ''; // Add to the variables array

					// UAMS Health Service Line (taxonomy select)

						$provider_service_line = get_field( 'physician_service_line', $page_id ); // string|int[] // Term ID(s)
						$provider_service_line = is_array($provider_service_line) ? $provider_service_line : array($provider_service_line); // int[] // Term ID(s)

						foreach ( $provider_service_line as $item ) {

							$provider_service_line_array[$item] = array(
								'name'	=> get_term( $item, 'service_line')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_service_line'] = isset($provider_service_line) ? $provider_service_line : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_service_line_array'] = isset($provider_service_line_array) ? $provider_service_line_array : ''; // Add to the variables array

					// Clinical Administrative Title (repeater)

						$provider_clinical_admin_title = get_field( 'physician_clinical_admin_title', $page_id ); // array

						$provider_profile_fields_vars['provider_clinical_admin_title'] = isset($provider_clinical_admin_title) ? $provider_clinical_admin_title : ''; // Add to the variables array

					// National Provider Identifier (NPI)

						$provider_npi = get_field( 'physician_npi', $page_id ); // string
						$provider_npi = str_pad($provider_npi, 10, '0', STR_PAD_LEFT); // Add enough leading zeroes to reach 10 digits

						$provider_profile_fields_vars['provider_npi'] = isset($provider_npi) ? $provider_npi : ''; // Add to the variables array

					// UAMS Health Epic SER ID

						$provider_pid = get_field( 'physician_pid', $page_id ); // string

						$provider_profile_fields_vars['provider_pid'] = isset($provider_pid) ? $provider_pid : ''; // Add to the variables array

					// Patient Type(s) (taxonomy checkbox)

						$provider_patient_types = get_field( 'physician_patient_types', $page_id ); // string|int[] // Term ID(s)
						$provider_patient_types = is_array($provider_patient_types) ? $provider_patient_types : array($provider_patient_types); // int[] // Term ID(s)

						foreach ( $provider_patient_types as $item ) {

							$provider_patient_types_array[$item] = array(
								'name'	=> get_term( $item, 'patient_type')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_patient_types'] = isset($provider_patient_types) ? $provider_patient_types : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_patient_types_array'] = isset($provider_patient_types_array) ? $provider_patient_types_array : ''; // Add to the variables array

					// Language(s) (taxonomy multi-select)

						$provider_languages = get_field( 'physician_languages', $page_id ); // int[]

						foreach ( $provider_languages as $item ) {

							$provider_languages_array[$item] = array(
								'name'	=> get_term( $item, 'language')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_languages'] = isset($provider_languages) ? $provider_languages : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_languages_array'] = isset($provider_languages_array) ? $provider_languages_array : ''; // Add to the variables array

					// Patient-focused Clinical Biography

						$provider_clinical_bio = get_field( 'physician_clinical_bio', $page_id ); // string

						$provider_profile_fields_vars['provider_clinical_bio'] = isset($provider_clinical_bio) ? $provider_clinical_bio : ''; // Add to the variables array

					// Short Patient-focused Clinical Biography

						$provider_short_clinical_bio = get_field( 'physician_short_clinical_bio', $page_id ); // string 

						$provider_profile_fields_vars['provider_short_clinical_bio'] = isset($provider_short_clinical_bio) ? $provider_short_clinical_bio : ''; // Add to the variables array

					// Featured Video

						$provider_youtube_link = get_field( 'physician_youtube_link', $page_id ); // string

						$provider_profile_fields_vars['provider_youtube_link'] = isset($provider_youtube_link) ? $provider_youtube_link : ''; // Add to the variables array

					// Clinical Focus

						$provider_clinical_focus = get_field( 'physician_clinical_focus', $page_id ); // string

						$provider_profile_fields_vars['provider_clinical_focus'] = isset($provider_clinical_focus) ? $provider_clinical_focus : ''; // Add to the variables array

					// Medical Specialties (taxonomy multi-select)

						$provider_medical_specialties = get_field( 'physician_medical_specialties', $page_id ); // int[]

						foreach ( $provider_medical_specialties as $item ) {

							$provider_medical_specialties_array[$item] = array(
								'name'	=> get_term( $item, 'specialty')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_medical_specialties'] = isset($provider_medical_specialties) ? $provider_medical_specialties : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_medical_specialties_array'] = isset($provider_medical_specialties_array) ? $provider_medical_specialties_array : ''; // Add to the variables array

					// Conditions Treated (post_object multi-select)

						$provider_conditions_cpt = get_field( 'physician_conditions_cpt', $page_id ); // int[]

						$provider_profile_fields_vars['provider_conditions_cpt'] = isset($provider_conditions_cpt) ? $provider_conditions_cpt : ''; // Add to the variables array

					// Treatments & Procedures (post_object multi-select)

						$provider_treatments_cpt = get_field( 'physician_treatments_cpt', $page_id ); // int[] 

						$provider_profile_fields_vars['provider_treatments_cpt'] = isset($provider_treatments_cpt) ? $provider_treatments_cpt : ''; // Add to the variables array

					// Medical Terms (Tags) (taxonomy multi-select)

						$provider_medical_terms = get_field( 'physician_medical_terms', $page_id ); // int[] 

						foreach ( $provider_medical_terms as $item ) {

							$provider_medical_terms_array[$item] = array(
								'name'	=> get_term( $item, 'medical_term')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_medical_terms'] = isset($provider_medical_terms) ? $provider_medical_terms : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_medical_terms_array'] = isset($provider_medical_terms_array) ? $provider_medical_terms_array : ''; // Add to the variables array

					// Locations (relationship)

						$provider_locations = get_field( 'physician_locations', $page_id ); // int[] 

						$provider_profile_fields_vars['provider_locations'] = isset($provider_locations) ? $provider_locations : ''; // Add to the variables array

					// Region (taxonomy multi-select)

						$provider_region = get_field( 'physician_region', $page_id ); // int[] 

						foreach ( $provider_region as $item ) {

							$provider_region_array[$item] = array(
								'name'	=> get_term( $item, 'region')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_region'] = isset($provider_region) ? $provider_region : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_region_array'] = isset($provider_region_array) ? $provider_region_array : ''; // Add to the variables array

					// Hospital Affiliations (taxonomy checkbox)

						$provider_affiliation = get_field( 'physician_affiliation', $page_id ); // string|int[] // Term ID(s)
						$provider_affiliation = is_array($provider_affiliation) ? $provider_affiliation : array($provider_affiliation); // int[] // Term ID(s)

						foreach ( $provider_affiliation as $item ) {

							$provider_affiliation_array[$item] = array(
								'name'	=> get_term( $item, 'affiliation')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_affiliation'] = isset($provider_affiliation) ? $provider_affiliation : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_affiliation_array'] = isset($provider_affiliation_array) ? $provider_affiliation_array : ''; // Add to the variables array

					// Institute Affiliations (taxonomy checkbox)

						$provider_institute_affiliation = get_field( 'physician_institute_affiliation', $page_id ); // string|int[] // Term ID(s)
						$provider_institute_affiliation = is_array($provider_institute_affiliation) ? $provider_institute_affiliation : array($provider_institute_affiliation); // int[] // Term ID(s)

						foreach ( $provider_institute_affiliation as $item ) {

							$provider_institute_affiliation_array[$item] = array(
								'name'	=> get_term( $item, 'institute_affiliation')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_institute_affiliation'] = isset($provider_institute_affiliation) ? $provider_institute_affiliation : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_institute_affiliation_array'] = isset($provider_institute_affiliation_array) ? $provider_institute_affiliation_array : ''; // Add to the variables array

					// Portal (taxonomy radio)

						$provider_portal = get_field( 'physician_portal', $page_id ); // string|int[] // Term ID(s)
						$provider_portal = is_array($provider_portal) ? $provider_portal : array($provider_portal); // int[] // Term ID(s)

						foreach ( $provider_portal as $item ) {

							$provider_portal_array[$item] = array(
								'name'	=> get_term( $item, 'portal')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_portal'] = isset($provider_portal) ? $provider_portal : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_portal_array'] = isset($provider_portal_array) ? $provider_portal_array : ''; // Add to the variables array

					// Areas of Expertise (relationship)

						$provider_expertise = get_field( 'physician_expertise', $page_id ); // int[] 

						$provider_profile_fields_vars['provider_expertise'] = isset($provider_expertise) ? $provider_expertise : ''; // Add to the variables array

					// Clinical Resources (relationship)

						$provider_clinical_resources = get_field( 'physician_clinical_resources', $page_id ); // int[] 

						$provider_profile_fields_vars['provider_clinical_resources'] = isset($provider_clinical_resources) ? $provider_clinical_resources : ''; // Add to the variables array

					// Recognition Lists (taxonomy multi-select)

						$provider_recognitions = get_field( 'physician_recognitions', $page_id ); // int[] 

						foreach ( $provider_recognitions as $item ) {

							$provider_recognitions_array[$item] = array(
								'name'	=> get_term( $item, 'recognition')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_recognitions'] = isset($provider_recognitions) ? $provider_recognitions : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_recognitions_array'] = isset($provider_recognitions_array) ? $provider_recognitions_array : ''; // Add to the variables array

					// Hide from Provider List

						$provider_hidden = get_field( 'physician_hidden', $page_id ); // bool 

						$provider_profile_fields_vars['provider_hidden'] = isset($provider_hidden) ? $provider_hidden : ''; // Add to the variables array

					// UAMS Health Talk Podcast Guest Name

						$provider_podcast_name = get_field( 'physician_podcast_name', $page_id ); // string 

						$provider_profile_fields_vars['provider_podcast_name'] = isset($provider_podcast_name) ? $provider_podcast_name : ''; // Add to the variables array

					// Ajax Search Filter

						$provider_asp_filter = get_field( 'physician_asp_filter', $page_id ); // string 

						$provider_profile_fields_vars['provider_asp_filter'] = isset($provider_asp_filter) ? $provider_asp_filter : ''; // Add to the variables array

					// Resident profile group

						$provider_resident_profile_group = get_field( 'physician_resident_profile_group', $page_id ); // group

						$provider_profile_fields_vars['provider_resident_profile_group'] = isset($provider_resident_profile_group) ? $provider_resident_profile_group : ''; // Add to the variables array

						// Is the hometown outside the U.S.?

							$provider_resident_hometown_international = $provider_resident_profile_group['physician_resident_hometown_international']; // bool 

							$provider_profile_fields_vars['provider_resident_hometown_international'] = isset($provider_resident_hometown_international) ? $provider_resident_hometown_international : ''; // Add to the variables array

						// Hometown City

							$provider_resident_hometown_city = $provider_resident_profile_group['physician_resident_hometown_city']; // string 

							$provider_profile_fields_vars['provider_resident_hometown_city'] = isset($provider_resident_hometown_city) ? $provider_resident_hometown_city : ''; // Add to the variables array

						// Hometown State / District / Territory

							$provider_resident_hometown_state = $provider_resident_profile_group['physician_resident_hometown_state']; // string 

							$provider_profile_fields_vars['provider_resident_hometown_state'] = isset($provider_resident_hometown_state) ? $provider_resident_hometown_state : ''; // Add to the variables array

						// Hometown Country

							$provider_resident_hometown_country = $provider_resident_profile_group['physician_resident_hometown_country']; // string 

							$provider_profile_fields_vars['provider_resident_hometown_country'] = isset($provider_resident_hometown_country) ? $provider_resident_hometown_country : ''; // Add to the variables array

						// Medical School (taxonomy select)

							$provider_resident_school = get_field( 'physician_resident_school', $page_id ); // string|int[] // Term ID(s)
							$provider_resident_school = is_array($provider_resident_school) ? $provider_resident_school : array($provider_resident_school); // int[] // Term ID(s)

							foreach ( $provider_resident_school as $item ) {

								$provider_resident_school_array[$item] = array(
									'name'	=> get_term( $item, 'school')->name // string // Term name
								);

							}

							$provider_profile_fields_vars['provider_resident_school'] = isset($provider_resident_school) ? $provider_resident_school : ''; // Add to the variables array
							$provider_profile_fields_vars['provider_resident_school_array'] = isset($provider_resident_school_array) ? $provider_resident_school_array : ''; // Add to the variables array

						// Academic Department (taxonomy select)

							$provider_resident_academic_department = get_field( 'physician_resident_academic_department', $page_id ); // string|int[] // Term ID(s)
							$provider_resident_academic_department = is_array($provider_resident_academic_department) ? $provider_resident_academic_department : array($provider_resident_academic_department); // int[] // Term ID(s)

							foreach ( $provider_resident_academic_department as $item ) {

								$provider_resident_academic_department_array[$item] = array(
									'name'	=> get_term( $item, 'academic_department')->name // string // Term name
								);

							}

							$provider_profile_fields_vars['provider_fields_vars'] = isset($provider_profile_fields_vars) ? $provider_profile_fields_vars : ''; // Add to the variables array
							$provider_profile_fields_vars['provider_resident_academic_department_array'] = isset($provider_resident_academic_department_array) ? $provider_resident_academic_department_array : ''; // Add to the variables array

						// Chief Resident?

							$provider_resident_academic_chief = $provider_resident_profile_group['physician_resident_academic_chief']; // bool

							$provider_profile_fields_vars['provider_resident_academic_chief'] = isset($provider_resident_academic_chief) ? $provider_resident_academic_chief : ''; // Add to the variables array

						// Residency Year (taxonomy select)

							$provider_resident_academic_year = get_field( 'physician_resident_academic_year', $page_id ); // string|int[] // Term ID(s)
							$provider_resident_academic_year = is_array($provider_resident_academic_year) ? $provider_resident_academic_year : array($provider_resident_academic_year); // int[] // Term ID(s)

							foreach ( $provider_resident_academic_year as $item ) {

								$provider_resident_academic_year_array[$item] = array(
									'name'	=> get_term( $item, 'residency_year')->name // string // Term name
								);

							}

							$provider_profile_fields_vars['provider_resident_academic_year'] = isset($provider_resident_academic_year) ? $provider_resident_academic_year : ''; // Add to the variables array
							$provider_profile_fields_vars['provider_resident_academic_year_array'] = isset($provider_resident_academic_year_array) ? $provider_resident_academic_year_array : ''; // Add to the variables array

					// Academic Title

						$provider_academic_title = get_field( 'physician_academic_title', $page_id ); // string 

						$provider_profile_fields_vars['provider_academic_title'] = isset($provider_academic_title) ? $provider_academic_title : ''; // Add to the variables array

					// College Affiliation (taxonomy checkbox)

						$provider_academic_college = get_field( 'physician_academic_college', $page_id ); // string|int[] // Term ID(s)
						$provider_academic_college = is_array($provider_academic_college) ? $provider_academic_college : array($provider_academic_college); // int[] // Term ID(s)

						foreach ( $provider_academic_college as $item ) {

							$provider_academic_college_array[$item] = array(
								'name'	=> get_term( $item, 'academic_college')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_academic_college'] = isset($provider_academic_college) ? $provider_academic_college : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_academic_college_array'] = isset($provider_academic_college_array) ? $provider_academic_college_array : ''; // Add to the variables array

					// Academic Position Type (taxonomy checkbox)

						$provider_academic_position = get_field( 'physician_academic_position', $page_id ); // string|int[] // Term ID(s)
						$provider_academic_position = is_array($provider_academic_position) ? $provider_academic_position : array($provider_academic_position); // int[] // Term ID(s)

						foreach ( $provider_academic_position as $item ) {

							$provider_academic_position_array[$item] = array(
								'name'	=> get_term( $item, 'academic_position')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_academic_position'] = isset($provider_academic_position) ? $provider_academic_position : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_academic_position_array'] = isset($provider_academic_position_array) ? $provider_academic_position_array : ''; // Add to the variables array

					// Faculty Title (repeater)

						$provider_academic_appointment = get_field( 'physician_academic_appointment', $page_id ); // array

						$provider_profile_fields_vars['provider_academic_appointment'] = isset($provider_academic_appointment) ? $provider_academic_appointment : ''; // Add to the variables array

					// Academic Administrative Title (repeater)

						$provider_academic_admin_title = get_field( 'physician_academic_admin_title', $page_id ); // array

						$provider_profile_fields_vars['provider_academic_admin_title'] = isset($provider_academic_admin_title) ? $provider_academic_admin_title : ''; // Add to the variables array

					// Academic Biography

						$provider_academic_bio = get_field( 'physician_academic_bio', $page_id ); // string 

						$provider_profile_fields_vars['provider_academic_bio'] = isset($provider_academic_bio) ? $provider_academic_bio : ''; // Add to the variables array

					// Short Academic Biography

						$provider_academic_short_bio = get_field( 'physician_academic_short_bio', $page_id ); // string 

						$provider_profile_fields_vars['provider_academic_short_bio'] = isset($provider_academic_short_bio) ? $provider_academic_short_bio : ''; // Add to the variables array

					// Office Location

						$provider_academic_office = get_field( 'physician_academic_office', $page_id ); // string 

						$provider_profile_fields_vars['provider_academic_office'] = isset($provider_academic_office) ? $provider_academic_office : ''; // Add to the variables array

					// Building / Map

						$provider_academic_map = get_field( 'physician_academic_map', $page_id ); // string 

						$provider_profile_fields_vars['provider_academic_map'] = isset($provider_academic_map) ? $provider_academic_map : ''; // Add to the variables array

					// Contact Information (repeater)

						$provider_contact_information = get_field( 'physician_contact_information', $page_id ); // array

						$provider_profile_fields_vars['provider_contact_information'] = isset($provider_contact_information) ? $provider_contact_information : ''; // Add to the variables array

					// Education and Training (repeater)

						$provider_education = get_field( 'physician_education', $page_id ); // array

						$provider_profile_fields_vars['provider_education'] = isset($provider_education) ? $provider_education : ''; // Add to the variables array

					// Boards and Certifications (taxonomy multi-select)

						$provider_boards = get_field( 'physician_boards', $page_id ); // int[]

						foreach ( $provider_boards as $item ) {

							$provider_boards_array[$item] = array(
								'name'	=> get_term( $item, 'board')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_boards'] = isset($provider_boards) ? $provider_boards : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_boards_array'] = isset($provider_boards_array) ? $provider_boards_array : ''; // Add to the variables array

					// Associations (taxonomy multi-select)

						$provider_associations = get_field( 'physician_associations', $page_id ); // int[]

						foreach ( $provider_associations as $item ) {

							$provider_associations_array[$item] = array(
								'name'	=> get_term( $item, 'association')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_associations'] = isset($provider_associations) ? $provider_associations : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_associations_array'] = isset($provider_associations_array) ? $provider_associations_array : ''; // Add to the variables array

					// Research Bio

						$provider_research_bio = get_field( 'physician_research_bio', $page_id ); // string 

						$provider_profile_fields_vars['provider_research_bio'] = isset($provider_research_bio) ? $provider_research_bio : ''; // Add to the variables array

					// Research Interests

						$provider_research_interests = get_field( 'physician_research_interests', $page_id ); // string 

						$provider_profile_fields_vars['provider_research_interests'] = isset($provider_research_interests) ? $provider_research_interests : ''; // Add to the variables array

					// UAMS Profiles Link

						$provider_research_profiles_link = get_field( 'physician_research_profiles_link', $page_id ); // string 

						$provider_profile_fields_vars['provider_research_profiles_link'] = isset($provider_research_profiles_link) ? $provider_research_profiles_link : ''; // Add to the variables array

					// Pubmed Author ID / Name

						$provider_pubmed_author_id = get_field( 'physician_pubmed_author_id', $page_id ); // string 

						$provider_profile_fields_vars['provider_pubmed_author_id'] = isset($provider_pubmed_author_id) ? $provider_pubmed_author_id : ''; // Add to the variables array

					// Number of Latest Articles to Display

						$provider_author_number = get_field( 'physician_author_number', $page_id ); // string 

						$provider_profile_fields_vars['provider_author_number'] = isset($provider_author_number) ? $provider_author_number : ''; // Add to the variables array

					// Selected Publications (repeater)

						$provider_select_publications = get_field( 'physician_select_publications', $page_id ); // array

						$provider_profile_fields_vars['provider_select_publications'] = isset($provider_select_publications) ? $provider_select_publications : ''; // Add to the variables array

					// Exclude Listing in Google My Business

						$provider_gmb_exclude = get_field( 'physician_gmb_exclude', $page_id ); // bool

						$provider_profile_fields_vars['provider_gmb_exclude'] = isset($provider_gmb_exclude) ? $provider_gmb_exclude : ''; // Add to the variables array

					// Google My Business Category (taxonomy multi-select)

						$provider_gmb_cat = get_field( 'physician_gmb_cat', $page_id ); // int[]

						foreach ( $provider_gmb_cat as $item ) {

							$provider_gmb_cat_array[$item] = array(
								'name'	=> get_term( $item, 'gmb_cat_provider')->name // string // Term name
							);

						}

						$provider_profile_fields_vars['provider_gmb_cat'] = isset($provider_gmb_cat) ? $provider_gmb_cat : ''; // Add to the variables array
						$provider_profile_fields_vars['provider_gmb_cat_array'] = isset($provider_gmb_cat_array) ? $provider_gmb_cat_array : ''; // Add to the variables array

					// Award(s) (repeater)

						$provider_awards = get_field( 'physician_awards', $page_id ); // array

						$provider_profile_fields_vars['provider_awards'] = isset($provider_awards) ? $provider_awards : ''; // Add to the variables array

					// Additional Information

						$provider_additional_info = get_field( 'physician_additional_info', $page_id ); // string

						$provider_profile_fields_vars['provider_additional_info'] = isset($provider_additional_info) ? $provider_additional_info : ''; // Add to the variables array

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$provider_profile_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $provider_profile_fields_vars;

			}

		}

	// Provider card field values

		function uamswp_fad_provider_card_fields(
			$page_id, // int // ID of the profile
			$provider_card_style = 'basic' // string enum('basic', 'detailed') // Provider card style
		) {

			// Check optional variables
			$provider_card_style = ( 'basic' == $provider_card_style || 'detailed' == $provider_card_style ) ? $provider_card_style : 'basic';

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $provider_card_style . '_' . $page_id, // Required // String added to transient name for disambiguation.
				$provider_card_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $provider_card_fields_vars ) ) {

				/* 
				 * The transient exists.
				  Return the variable.
				 */

				return $provider_card_fields_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Create a variables array to be used on the templates and template parts
				$provider_card_fields_vars = array();

				// Get the field values

					// Common

						// First Name

							$provider_first_name = get_field( 'physician_first_name', $page_id ); // string

						// Middle Name

							$provider_middle_name = get_field( 'physician_middle_name', $page_id ); // string

						// Last Name

							$provider_last_name = get_field( 'physician_last_name', $page_id ); // string

						// Generational Suffix

							$provider_pedigree = get_field( 'physician_pedigree', $page_id ); // string 

						// Degree and/or Credential (taxonomy multi-select)

							$provider_degree = get_field( 'physician_degree', $page_id ); // int[]

							// List degree term names

								if ( $provider_degree ) {

									foreach ( $provider_degree as $key => $value ) {

										$provider_degree_term = get_term( $value, 'degree' );

										if ( is_object( $provider_degree_term ) ) {

											$provider_degree[$key] = $provider_degree_term->name;

										} else {

											unset($provider_degree[$key]);
											$provider_degree = array_values($provider_degree);

										}

									} // endforeach

									$provider_degree_list = $provider_degree ? implode(", ", $provider_degree) : ''; // string

								} else {

									// Eliminate PHP errors
									$provider_degree_list = '';

								} // endif

							// Add to the variables array

								$provider_card_fields_vars['provider_degree_list'] = isset($provider_degree_list) ? $provider_degree_list : '';

						// Full name

							$provider_full_name[] = $provider_first_name ?: '';
							$provider_full_name[] = $provider_middle_name ?: '';
							$provider_full_name[] = $provider_last_name ?: '';
							$provider_full_name = array(
								implode( ' ', array_filter($provider_full_name) )
							);
							$provider_full_name[] = $provider_pedigree ?: '';
							$provider_full_name = array(
								implode( '&nbsp;', array_filter($provider_full_name) )
							);
							$provider_full_name[] = $provider_degree_list ?: '';
							$provider_full_name = implode( ', ', array_filter($provider_full_name) );
							$provider_full_name_attr = uamswp_attr_conversion( $provider_full_name );

							// Add to the variables array

								$provider_card_fields_vars['provider_full_name'] = isset($provider_full_name) ? $provider_full_name : '';
								$provider_card_fields_vars['provider_full_name_attr'] = isset($provider_full_name_attr) ? $provider_full_name_attr : '';

						// Clinical Job Title (taxonomy select)

							// Is the Provider a Resident?
							$provider_resident = get_field( 'physician_resident', $page_id ) ?: false; // bool 

							if ( $provider_resident ) {

								$provider_title = '';
								$provider_title_list = 'Resident Physician';

							} else {

								$provider_title = get_field( 'physician_title', $page_id ); // string|int[] // Term ID(s)
								$provider_title = is_array($provider_title) ? $provider_title : array($provider_title); // int[] // Term ID(s)

								// List Clinical Job Title term names

								if ( $provider_title ) {

									foreach ( $provider_title as $key => $value ) {

										$provider_title_term = get_term( $value, 'clinical_title' );

										if ( is_object( $provider_title_term ) ) {

											$provider_title[$key] = $provider_title_term->name;

										} else {

											unset($provider_title[$key]);
											$provider_title = array_values($provider_title);

										}

									} // endforeach

									$provider_title_list = $provider_title ? implode(", ", $provider_title) : ''; // string

								} else {

									// Eliminate PHP errors
									$provider_title_list = '';

								} // endif

							} // endif ( $provider_resident )

							// Add to the variables array

								$provider_card_fields_vars['provider_title'] = isset($provider_title) ? $provider_title : '';
								$provider_card_fields_vars['provider_title_list'] = isset($provider_title_list) ? $provider_title_list : '';

						// Profile URL

							$provider_url = user_trailingslashit(get_permalink( $page_id ));

							// Add to the variables array
							$provider_card_fields_vars['provider_url'] = isset($provider_url) ? $provider_url : '';

					// Provider Card Styles

						if ( 'basic' == $provider_card_style ) {

							// Headshot

								$provider_headshot = get_field( '_thumbnail_id', $page_id ); // int

								if (
									$provider_headshot
									&&
									function_exists( 'fly_add_image_size' )
								) {

									$provider_headshot_url = image_sizer( $provider_headshot, 253, 337, 'center', 'center' );

								} elseif ( $provider_headshot ) {

									$provider_headshot_url = wp_get_attachment_image_url( $provider_headshot, 'medium' );

								} else {

									$provider_headshot_url = '';

								}

								// Add to the variables array
								$provider_card_fields_vars['provider_headshot_url'] = isset($provider_headshot_url) ? $provider_headshot_url : ''; 

						} elseif ( 'detailed' == $provider_card_style ) {

							// Headshot

								$provider_headshot = get_field( '_thumbnail_id', $page_id ); // int

								if (
									$provider_headshot
									&&
									function_exists( 'fly_add_image_size' )
								) {

									$provider_headshot_srcset[] = array(
										'url'				=> image_sizer( $provider_headshot, 243, 324, 'center', 'center' ),
										'media-min-width'	=> '2054px'
									);

									$provider_headshot_srcset[] = array(
										'url'				=> image_sizer( $provider_headshot, 184, 245, 'center', 'center' ),
										'media-min-width'	=> '1784px'
									);

									$provider_headshot_srcset[] = array(
										'url'				=> image_sizer( $provider_headshot, 243, 324, 'center', 'center' ),
										'media-min-width'	=> '1200px'
									);

									$provider_headshot_srcset[] = array(
										'url'				=> image_sizer( $provider_headshot, 184, 245, 'center', 'center' ),
										'media-min-width'	=> '768px'
									);

									$provider_headshot_srcset[] = array(
										'url'				=> image_sizer( $provider_headshot, 95, 127, 'center', 'center' ),
										'media-min-width'	=> '576px'
									);

									$provider_headshot_srcset[] = array(
										'url'				=> image_sizer( $provider_headshot, 184, 245, 'center', 'center' ),
										'media-min-width'	=> '1px'
									);

									$provider_headshot_base_url = image_sizer( $provider_headshot, 184, 245, 'center', 'center' );

								} elseif ( $provider_headshot ) {

									$provider_headshot_base_url = wp_get_attachment_image_url( $provider_headshot, 'medium' );

								} else {

									$provider_headshot_base_url = '';

								}

								// Add to the variables array

									$provider_card_fields_vars['provider_headshot_srcset'] = isset($provider_headshot_srcset) ? $provider_headshot_srcset : array(); 
									$provider_card_fields_vars['provider_headshot_base_url'] = isset($provider_headshot_base_url) ? $provider_headshot_base_url : ''; 

							// National Provider Identifier (NPI)

								$provider_npi = get_field( 'physician_npi', $page_id ); // string
								$provider_npi = str_pad($provider_npi, 10, '0', STR_PAD_LEFT); // Add enough leading zeroes to reach 10 digits

								// Add to the variables array
								$provider_card_fields_vars['provider_npi'] = isset($provider_npi) ? $provider_npi : '';

							// Post Excerpt

								$provider_excerpt = get_field( 'physician_short_clinical_bio', $page_id ); // string
								$provider_excerpt = $provider_excerpt ?: wp_strip_all_tags( get_field( 'physician_clinical_bio', $page_id ) ); // string
								$provider_excerpt = $provider_excerpt ?: ''; // string

								// Truncate the excerpt if it is greater than 160 characters

									if ( strlen($provider_excerpt) > 160 ) {

										$provider_excerpt = wp_trim_words( $provider_excerpt, 23, ' &hellip;' );

									}

								// Add to the variables array
								$provider_card_fields_vars['provider_excerpt'] = isset($provider_excerpt) ? $provider_excerpt : '';

							// Locations (relationship)

								$provider_locations = get_field( 'physician_locations', $page_id ); // int[] 

								// Check for valid locations

									foreach ( $provider_locations as $key => $value ) {

										if ( get_post_status ( $value ) == 'publish' ) {

											$provider_locations_array[$value]['title'] = get_the_title( $value );
											$provider_locations_array[$value]['title_attr'] = uamswp_attr_conversion($provider_locations_array[$value]['title']);
											$provider_locations_array[$value]['url'] = get_permalink( $value );

										} else {

											unset($provider_locations[$key]);

										}
									}

									$provider_locations = array_values($provider_locations);

								// Add to the variables array
								$provider_card_fields_vars['provider_locations_array'] = isset($provider_locations_array) ? $provider_locations_array : '';

						} // endif

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $provider_card_style . '_' . $page_id, // Required // String added to transient name for disambiguation.
					$provider_card_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $provider_card_fields_vars;

			}

		}

	// Location profile field values

		function uamswp_fad_location_profile_fields(
			$page_id // int // ID of the profile
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$location_profile_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $location_profile_fields_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $location_profile_fields_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Create a variables array to be used on the templates and template parts
				$location_profile_fields_vars = array();

				// Get the field values

					// Foo

						$foo = get_field( 'foo', $page_id ); // string

						$location_profile_fields_vars['foo'] = isset($foo) ? $foo : ''; // Add to the variables array

					// Bar (taxonomy multi-select)

						$bar = get_field( 'bar', $page_id ); // int[]

						foreach ( $bar as $item ) {

							$bar_array[$item] = array(
								'name'	=> get_term( $item, 'bar_term')->name // string // Term name
							);

						}

						$location_profile_fields_vars['bar'] = isset($bar) ? $bar : ''; // Add to the variables array
						$location_profile_fields_vars['bar_array'] = isset($bar_array) ? $bar_array : ''; // Add to the variables array

					// Baz (taxonomy select/radio/checkbox)

						$baz = get_field( 'baz', $page_id ); // string|int[] // Term ID(s)
						$baz = is_array($baz) ? $baz : array($baz); // int[] // Term ID(s)

						foreach ( $baz as $item ) {

							$baz_array[$item] = array(
								'name'	=> get_term( $item, 'baz_term')->name // string // Term name
							);

						}

						$location_profile_fields_vars['baz'] = isset($baz) ? $baz : ''; // Add to the variables array
						$location_profile_fields_vars['baz_array'] = isset($baz_array) ? $baz_array : ''; // Add to the variables array

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$location_profile_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $location_profile_fields_vars;

			}
		}

	// Location card field values

		function uamswp_fad_location_card_fields(
			$page_id, // int // ID of the profile
			$location_card_style, // string enum('basic', 'detailed', 'primary-location') // Location card style
			$schema_address = array(), // array // Schema address data
			$schema_telephone = array(), // array // Schema telephone data
			$schema_fax_number = array(), // array // Schema fax number data
			$schema_geo_coordinates = array(), // array // Schema geo data
			$location_section_schema_query = false, // bool // Query for whether to add locations to schema
			$location_descendant_list = false // bool // Query on whether this card is in a list of descendant locations
		) {

			// Check optional variables
			$location_card_style = ( 'basic' == $location_card_style || 'detailed' == $location_card_style || 'primary-location' == $location_card_style ) ? $location_card_style : 'basic';

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $location_card_style . '_' . $page_id, // Required // String added to transient name for disambiguation.
				$location_card_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $location_card_fields_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $location_card_fields_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Create a variables array to be used on the templates and template parts
				$location_card_fields_vars = array();

				// Get the field values

					// Common

						// Query on whether this card is in a list of descendant locations

							$location_descendant_list = isset($location_descendant_list) ? $location_descendant_list : false;

						// Location Title

							$location_title = get_the_title($page_id);
							$location_title_attr = uamswp_attr_conversion($location_title);

							// Add to the variables array

								$location_card_fields_vars['location_title'] = isset($location_title) ? $location_title : '';
								$location_card_fields_vars['location_title_attr'] = isset($location_title_attr) ? $location_title_attr : '';

						// Location URL
							$location_url = user_trailingslashit(get_permalink($page_id));

							// Add to the variables array
							$location_card_fields_vars['location_url'] = isset($location_url) ? $location_url : '';

						// Query on whether the current location has a parent

							$location_has_parent = get_field('location_parent', $page_id) ?: '';

						// Parent location

							// Parent ID
							$location_parent_id = $location_has_parent ? ( get_field('location_parent_id', $page_id) ?: '' ) : '';
							$location_has_parent = $location_parent_id ? true : false;

							// Get the parent post object
							$location_parent_object = $location_parent_id ? get_post($location_parent_id) : '';
							$location_parent_display = ( $location_parent_object && !$location_descendant_list ) ? true : false;

							if ( $location_parent_object ) {

								// If the parent post object exists...

								// Parent title

									$location_parent_title = $location_parent_object->post_title;
									$location_parent_title_attr = uamswp_attr_conversion($location_parent_title);

								// Parent URL
								$location_parent_url = get_permalink($location_parent_id);

								// Set address ID using the parent ID
								$location_address_id = $location_parent_id;

								// Query on whether to override the photos using the parent's photos
								$location_override_parent_photo = get_field('location_image_override_parent', $page_id) ?: false;

								// Query on whether to override the featured photo using the parent's featured photo
								$location_override_parent_photo_featured = $location_override_parent_photo ? get_field('location_image_override_parent_featured', $page_id) : false;

							} else {

								// Eliminate PHP errors

								$location_parent_title = '';
								$location_parent_title_attr = '';
								$location_parent_url = '';
								$location_address_id = $page_id;
								$location_override_parent_photo = '';
								$location_override_parent_photo_featured = '';

							}

							// Add to the variables array
							$location_card_fields_vars['location_override_parent_photo'] = isset($location_override_parent_photo) ? $location_override_parent_photo : '';
							$location_card_fields_vars['location_override_parent_photo_featured'] = isset($location_override_parent_photo_featured) ? $location_override_parent_photo_featured : '';
							$location_card_fields_vars['location_has_parent'] = isset($location_has_parent) ? $location_has_parent : '';
							$location_card_fields_vars['location_parent_id'] = isset($location_parent_id) ? $location_parent_id : '';
							$location_card_fields_vars['location_parent_object'] = isset($location_parent_object) ? $location_parent_object : '';
							$location_card_fields_vars['location_parent_display'] = isset($location_parent_display) ? $location_parent_display : '';
							$location_card_fields_vars['location_parent_title'] = isset($location_parent_title) ? $location_parent_title : '';
							$location_card_fields_vars['location_parent_title_attr'] = isset($location_parent_title_attr) ? $location_parent_title_attr : '';
							$location_card_fields_vars['location_parent_url'] = isset($location_parent_url) ? $location_parent_url : '';

						// Featured image

							// Featured image ID

								if (
									$location_parent_object
									&&
									!$location_override_parent_photo_featured
								) {

									$location_featured_image = get_field( '_thumbnail_id', $location_parent_id ) ?: ''; // int

								} else {

									$location_featured_image = get_field( '_thumbnail_id', $page_id ) ?: ''; // int

								}

								$location_card_fields_vars['location_featured_image'] = isset($location_featured_image) ? $location_featured_image : '';

							// Featured image URL
							$location_featured_image_url = $location_featured_image ? wp_get_attachment_image_url( $location_featured_image, 'aspect-16-9-small' ) : ''; // string

							// Add to the variables array
							$location_card_fields_vars['location_featured_image_url'] = isset($location_featured_image_url) ? $location_featured_image_url : '';

						// Get system settings for location labels

							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

						// Get the address attributes of the relevant item

							// Street address (address line 1)
							$location_address_1 = get_field('location_address_1', $location_address_id ) ?: '';

								// Add to the variables array
								$location_card_fields_vars['location_address_1'] = isset($location_address_1) ? $location_address_1 : '';

							// Building, Floor and Suite

								// Building

									$location_building = get_field('location_building', $location_address_id ) ?: '';
									$location_building_term = $location_building ? get_term( $location_building, 'building' ) : '';
									$location_building_slug = $location_building_term ? $location_building_term->slug : '';
									$location_building_name = $location_building_term ? $location_building_term->name : '';

									// If building slug is set to '_none' (standalone building), reset the values

										if ( $location_building_slug == '_none' ) {

											$location_building = '';
											$location_building_term = '';
											$location_building_slug = '';
											$location_building_name = '';

										}

									// Add to the variables array

										$location_card_fields_vars['location_building'] = isset($location_building) ? $location_building : '';
										$location_card_fields_vars['location_building_slug'] = isset($location_building_slug) ? $location_building_slug : '';
										$location_card_fields_vars['location_building_name'] = isset($location_building_name) ? $location_building_name : '';

								// Building floor

									$location_floor = get_field_object('location_building_floor', $location_address_id ) ?: '';
									$location_floor_value = $location_floor ? $location_floor['value'] : '';
									$location_floor_label = $location_floor ? $location_floor['choices'][$location_floor_value] : '';

									// If floor value is set to '0' (single-story building), reset the values

										if ( $location_floor_value == '0' ) {

											$location_floor = '';
											$location_floor_value = '';
											$location_floor_label = '';

										}

									// Add to the variables array

										$location_card_fields_vars['location_floor_value'] = isset($location_floor_value) ? $location_floor_value : '';
										$location_card_fields_vars['location_floor_label'] = isset($location_floor_label) ? $location_floor_label : '';

								// Suite/unit number

									$location_suite = get_field('location_suite', $location_address_id ) ?: '';

									// Add to the variables array

										$location_card_fields_vars['location_suite'] = isset($location_suite) ? $location_suite : '';

								// Create address detail line(s) string

									$location_address_detail = implode(
										'<br />',
										array_filter(
											array(
												$location_building_name,
												implode(
													', ',
													array_filter(
														array(
															$location_floor_label,
															$location_suite
														)
													)
												)
											)
										)
									);

								// Construct the schema for address line 2

									$location_address_2_schema = implode(
										' ',
										array_filter(
											array(
												$location_building_name,
												$location_floor_label,
												$location_suite
											)
										)
									);

								// Fall back to the deprecated address line 2 field

									if (
										!$location_address_detail
										||
										!$location_address_2_schema
									) {

										// Get deprecated address line 2 value
										$location_address_2_deprecated = get_field('location_address_2', $location_address_id ) ?: '';

										$location_address_detail = $location_address_detail ?: $location_address_2_deprecated;
										$location_address_2_schema = $location_address_2_schema ?: $location_address_2_deprecated;

									}

								// Construct the full schema address line

									$location_address_schema = implode(
										' ',
										array_filter(
											array(
												$location_address_1,
												$location_address_2_schema
											)
										)
									);

							// City, State and ZIP

								// City
								$location_city = get_field('location_city', $location_address_id) ?: '';

								// State
								$location_state = get_field('location_state', $location_address_id) ?: '';

								// ZIP
								$location_zip = get_field('location_zip', $location_address_id) ?: '';

								// Construct final address line string

									$location_address_final_line = implode(
										', ',
										array_filter(
											array(
												$location_city,
												implode(
													' ',
													array_filter(
														array(
															$location_state,
															$location_zip
														)
													)
												)
											)
										)
									);

								// Add to the variables array

									$location_card_fields_vars['location_city'] = isset($location_city) ? $location_city : '';
									$location_card_fields_vars['location_state'] = isset($location_state) ? $location_state : '';
									$location_card_fields_vars['location_zip'] = isset($location_zip) ? $location_zip : '';

						// GPS / Map / Directions

							$location_map = get_field('location_map', $location_address_id) ?: '';

							// Construct directions URL (Google Maps)
							$location_directions_url = $location_map ? 'https://www.google.com/maps/dir/Current+Location/' . $location_map['lat'] . ',' . $location_map['lng'] : '';

							// Add to the variables array

								$location_card_fields_vars['location_map'] = isset($location_map) ? $location_map : '';
								$location_card_fields_vars['location_directions_url'] = isset($location_directions_url) ? $location_directions_url : '';

						// Location Alerts

							// Closure Alert

								// Query for whether the location is closing

									$location_closing = get_field('location_closing', $page_id) ?: false; // bool

								// Eliminate PHP errors

									$location_closing_date = '';
									$location_closing_date_past = '';
									$location_closing_length = '';
									$location_closing_reopen_known = '';
									$location_closing_reopen_date = '';

								if ( $location_closing ) {

									// Closing Date
									$location_closing_date = get_field('location_closing_date', $page_id) ?: ''; // Date Format: "F j, Y"

									// Query for whether the closing date is in the past

										if ( new DateTime() >= new DateTime($location_closing_date) ) {

											$location_closing_date_past = true;

										} else {

											$location_closing_date_past = false;

										} // endif

									// Temporary or permanent?
									$location_closing_length = get_field('location_closing_length', $page_id) ?: ''; // string enum('temporary', 'permanent')

									if ( $location_closing_length == 'temporary' ) {

										// Reopening date known?
										$location_closing_reopen_known = get_field('location_reopen_known', $page_id) ?: ''; // string enum('tbd', 'date')

										if ( $location_closing_reopen_known == 'date' ) {

											// Reopening date
											$location_closing_reopen_date = get_field('location_reopen_date', $page_id) ?: ''; // Date Format: "F j, Y"

										} // endif ( $location_closing_reopen_known == 'date' )

									} // endif ( $location_closing_length == 'temporary' )

								} // endif ( $location_closing )

								// Query for whether to display the location closure alert

									if (
										(
											$location_closing
											&&
											$location_closing_length == 'permanent'
										)
										||
										(
											$location_closing
											&&
											$location_closing_length == 'temporary'
											&&
											!$location_closing_reopen_date_past
										)
									) {

										$location_closing_display = true;

									} else {

										$location_closing_display = false;

									}

							// Modified Hours Alert

								$location_modified_hours_group = get_field('location_hours_group', $page_id);

								// Datetime references (Unix timestamp)

									$today = strtotime("today");
									$today_30 = strtotime("+30 days");

								// Modified Clinic Hours

									// Query for whether there are upcoming modified clinic hours
									$location_modified_clinic_hours = $location_modified_hours_group['location_modified_hours'] ?: false; // bool

									// Eliminate PHP errors

										$location_modified_clinic_hours_start_date = '';
										$location_modified_clinic_hours_start_date_unix = '';
										$location_modified_clinic_hours_end = '';
										$location_modified_clinic_hours_end_date = '';
										$location_modified_clinic_hours_end_date_unix = '';

									if ( $location_modified_clinic_hours ) {

										// Modified Clinic Hours Start Date

											$location_modified_clinic_hours_start_date = $location_modified_hours_group['location_modified_hours_start_date'] ?: ''; // Date Format: "F j, Y"
											$location_modified_clinic_hours_start_date_unix = $location_modified_clinic_hours_start_date ? strtotime($location_modified_clinic_hours_start_date) : ''; // (Unix timestamp)

										// Query for whether there is an end date to the modified clinic hours
										$location_modified_clinic_hours_end = $location_modified_hours_group['location_modified_hours_end'] ?: false; // bool

										if ( $location_modified_clinic_hours_end ) {

											// Modified Clinic Hours End Date

												$location_modified_clinic_hours_end_date = $location_modified_hours_group['location_modified_hours_end_date'] ?: ''; // Date Format: "F j, Y"
												$location_modified_clinic_hours_end_date_unix = $location_modified_clinic_hours_end_date ? strtotime($location_modified_clinic_hours_end_date) : ''; // (Unix timestamp)

										}

									}

								// Modified Telemedicine Hours

									// Query for whether there are upcoming modified telemedicine hours
									$location_modified_telemed_hours = $location_modified_hours_group['location_telemed_modified_hours_query'] ?: false; // bool

									// Eliminate PHP errors

										$location_modified_telemed_hours_start_date = '';
										$location_modified_telemed_hours_start_date_unix = '';
										$location_modified_telemed_hours_end = '';
										$location_modified_telemed_hours_end_date = '';
										$location_modified_telemed_hours_end_date_unix = '';

									if ( $location_modified_telemed_hours ) {

										// Modified Telemedicine Hours Start Date

											$location_modified_telemed_hours_start_date = $location_modified_hours_group['location_telemed_modified_hours_start_date'] ?: ''; // Date Format: "F j, Y"
											$location_modified_telemed_hours_start_date_unix = $location_modified_telemed_hours_start_date ? strtotime($location_modified_telemed_hours_start_date) : ''; // (Unix timestamp)

										// Query for whether there is an end date to the modified telemedicine hours
										$location_modified_telemed_hours_end = $location_modified_hours_group['location_telemed_modified_hours_end'] ?: false; // bool

										if ( $location_modified_telemed_hours_end ) {

											// Modified Telemedicine Hours End Date

												$location_modified_telemed_hours_end_date = $location_modified_hours_group['location_telemed_modified_hours_end_date'] ?: ''; // Date Format: "F j, Y"
												$location_modified_telemed_hours_end_date_unix = $location_modified_telemed_hours_end_date ? strtotime($location_modified_telemed_hours_end_date) : ''; // (Unix timestamp)

										}

									}

								// Query for whether to display the location closure alert

									if (
										(
											$location_modified_clinic_hours
											&&
											$location_modified_clinic_hours_start_date_unix <= $today_30
											&&
											$location_modified_clinic_hours_end_date_unix >= $today
										)
										||
										(
											$location_modified_clinic_hours
											&&
											$location_modified_clinic_hours_start_date_unix <= $today_30
											&&
											!$location_modified_clinic_hours_end
										)
										||
										(
											$location_modified_telemed_hours
											&&
											$location_modified_telemed_hours_start_date_unix <= $today_30
											&&
											$location_modified_telemed_hours_end_date_unix >= $today
										)
										||
										(
											$location_modified_telemed_hours
											&&
											$location_modified_telemed_hours_start_date_unix <= $today_30
											&&
											!$location_modified_telemed_hours_end
										)
									) {

										$location_modified_hours_display = true;

									} else {

										$location_modified_hours_display = false;

									}

								// Set start of modified hours based on the earliest of the two (clinic and telemedicine)

									if (
										$location_modified_clinic_hours_start_date_unix
										||
										$location_modified_telemed_hours_start_date_unix
									) {

										$location_modified_hours_start_date_unix_array = array_filter(
											array(
												$location_modified_clinic_hours_start_date_unix,
												$location_modified_telemed_hours_start_date_unix
											)
										);

										$location_modified_hours_start_date_unix = ( count($location_modified_hours_start_date_unix_array) > 1 ) ? min($location_modified_hours_start_date_unix_array) : $location_modified_hours_start_date_unix_array[0];

									} else {

										$location_modified_hours_start_date_unix = '';

									}

								// Format modified hours start date

									if ( $location_modified_hours_start_date_unix ) {

										$location_modified_hours_start_date = date( 'F j, Y', $location_modified_hours_start_date_unix );

									} else {

										$location_modified_hours_start_date = '';

									}

								// Determine if earliest modified hours start date has past

									if ( $location_modified_hours_start_date_unix ) {

										$location_modified_hours_date_past = ( $location_modified_hours_start_date_unix <= $today ) ? true : false;

									} else {

										$location_modified_hours_date_past = '';

									}

							// Construct the location alert elements

								// Link accessible label

									if ( $location_closing_display ) {

										if ( $location_closing_date_past ) {

											$alert_message = 'This ' . strtolower($location_single_name) . ' is ' . ( $location_closing_length == 'temporary' ? 'temporarily' : 'permanently' ) . ' closed.';

										} else {

											$alert_message = 'This ' . strtolower($location_single_name) . ' will be closing ' . ( $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently' ) . ' on ' .  $location_closing_date . '.';

										} // endif

										$alert_label_attr = 'Learn more about the closure of ' . $location_title_attr . '.';

									} elseif ( $location_modified_hours_display ) {

										if ( $location_closing_date_past ) {

											$alert_message = 'This ' . strtolower($location_single_name) . '\'s hours have been temporarily modified.';

										} else {

											$alert_message = 'This ' . strtolower($location_single_name) . '\'s hours will be temporarily modified beginning on ' . $location_modified_clinic_hours_start_date . '.';

										} // endif

										$alert_label_attr = 'Learn more about the modified hours.';

									} else {

										$alert_message = '';
										$alert_label_attr = '';

									} // endif

							// Add to the variables array

								$location_card_fields_vars['location_closing_display'] = isset($location_closing_display) ? $location_closing_display : '';
								$location_card_fields_vars['location_modified_hours_display'] = isset($location_modified_hours_display) ? $location_modified_hours_display : '';
								$location_card_fields_vars['alert_message'] = isset($alert_message) ? $alert_message : '';
								$location_card_fields_vars['alert_label_attr'] = isset($alert_label_attr) ? $alert_label_attr : '';

						// Phone numbers

							// Query for whether this is an Arkansas Children's location

								$location_ac_query = get_field( 'location_ac_query', $page_id ) ?: '';

							// Query for whether a patient can schedule an appointment for services rendered at this location

								$location_appointments_query = get_field( 'location_appointments_query', $page_id ) ?: ''; // Get the input

							// Data attributes

								// 'data-categorytitle' attribute value
								$location_phone_data_categorytitle = 'Telephone Number';

								// 'data-itemtitle' attribute value
								$location_phone_data_itemtitle = $location_title_attr;

								// 'data-typetitle' attribute value

									if ( $location_appointments_query ) {

										$location_phone_link_data_typetitle = 'Appointment Phone Number for New and Returning Patients';

									} else {

										$location_phone_link_data_typetitle = 'Location Phone Number';
									}

							if ( !$location_appointments_query ) {

								// Add general information phone number to location phone numbers array

									// Get the general information phone number
									$location_phone = get_field( 'location_phone', $page_id ) ?: '';

									// Add the general information phone number (as the appointment phone number) to the location phone numbers array

										if ( $location_phone ) {

											$location_phone_numbers['General Information']['general'] = array(
												'link'		=> uamswp_fad_create_telephone_link(
													$location_phone, // string // phone number
													'icon-phone', // string // class attribute value
													$location_phone_data_categorytitle, // string // data-categorytitle attribute value
													$location_phone_data_itemtitle, // string // data-itemtitle attribute value
													'General Information Phone Number' // string // data-typetitle attribute value
												),
												'subtitle'	=> ''
											);

										}

							} else {

								// Query for whether there are main appointment phone numbers other than the general information phone number

									if (
										!$location_ac_query
									) {

										$location_phone_appointment_query = get_field( 'location_clinic_phone_query', $page_id ) ?: false;

									} else {

										$location_phone_appointment_query = false;

									}

								// General information phone number

									// Get the general information phone number

										$location_phone = get_field( 'location_phone', $page_id ) ?: '';

									if (
										!$location_phone_appointment_query // If there are no main appointment phone numbers other than the general information phone number
										&&
										!$location_ac_query // If this is not an Arkansas Children's location
									) {

										// Add the general information phone number (as the appointment phone number) to the location phone numbers array

											if ( $location_phone ) {

												$location_phone_numbers['Appointment Phone Numbers']['new'] = array(
													'link'		=> uamswp_fad_create_telephone_link(
														$location_phone, // string // phone number
														'icon-phone', // string // class attribute value
														$location_phone_data_categorytitle, // string // data-categorytitle attribute value
														$location_phone_data_itemtitle, // string // data-itemtitle attribute value
														'Appointment Phone Number for New and Returning Patients' // string // data-typetitle attribute value
													),
													'subtitle'	=> 'New and Returning Patients'
												);

											}

									}

								// Query for whether this Arkansas Children's location have separate phone numbers for primary care appointments and specialty care appointments

									if (
										$location_ac_query // If this is an Arkansas Children's location
									) {

										// Get field value of query for whether this Arkansas Children's location have separate phone numbers for primary care appointments and specialty care appointments
										$location_phone_appointment_ac_query = get_field('location_ac_appointments_query', $page_id) ?: false;

										// Arkansas Children's appointment phone number for primary care and for specialty care

											if ( $location_phone_appointment_ac_query ) {

												// Arkansas Children's appointment phone number for primary care

													$location_phone_appointment_ac_primary = get_field('location_ac_appointments_primary', $page_id) ?: '';

													// Add the phone number to the location phone numbers array

														if ( $location_phone_appointment_ac_primary ) {

															$location_phone_numbers['Appointment Phone Numbers']['ac-primary'] = array(
																'link'		=> uamswp_fad_create_telephone_link(
																	$location_phone_appointment_ac_primary, // string // phone number
																	'icon-phone', // string // class attribute value
																	$location_phone_data_categorytitle, // string // data-categorytitle attribute value
																	$location_phone_data_itemtitle, // string // data-itemtitle attribute value
																	'Arkansas Children\'s Primary Care Appointments Phone Number' // string // data-typetitle attribute value
																),
																'subtitle'	=> 'Primary Care'
															);

														}

												// Arkansas Children's appointment phone number for specialty care

													$location_phone_appointment_ac_specialty = get_field('location_ac_appointments_specialty', $page_id) ?: '';

													// Add the phone number to the location phone numbers array

														if ( $location_phone_appointment_ac_specialty ) {

															$location_phone_numbers['Appointment Phone Numbers']['ac-specialty'] = array(
																'link'		=> uamswp_fad_create_telephone_link(
																	$location_phone_appointment_ac_specialty, // string // phone number
																	'icon-phone', // string // class attribute value
																	$location_phone_data_categorytitle, // string // data-categorytitle attribute value
																	$location_phone_data_itemtitle, // string // data-itemtitle attribute value
																	'Arkansas Children\'s Specialty Care Appointments Phone Number' // string // data-typetitle attribute value
																),
																'subtitle'	=> 'Specialty Care'
															);

														}

											}

									} else {

										$location_phone_appointment_ac_query = false;

									}

								// Appointment phone numbers for new and returning patients

									if (
										$location_phone_appointment_query
										||
										(
											$location_ac_query
											&&
											!$location_phone_appointment_ac_query
										)
									) {

										// Get appointment phone number for (new) patients
										$location_phone_appointment_new = get_field('location_new_appointments_phone', $page_id) ?: ''; 

										// Query for whether there is a separate appointment phone number for returning patients

											$location_phone_appointment_returning_query = get_field('location_appointment_phone_query', $page_id) ?: false; 

											// Appointments Phone Number for Returning Patients

												if ( $location_phone_appointment_returning_query ) {

													$location_phone_appointment_returning = get_field('location_return_appointments_phone', $page_id) ?: '';

												} else {

													$location_phone_appointment_returning = '';

												}

									} else {

										$location_phone_appointment_new = '';
										$location_phone_appointment_returning_query = false;
										$location_phone_appointment_returning = '';

									}

									// Add the phone numbers to the location phone numbers array

										if ( $location_phone_appointment_new ) {

											if ( $location_phone_appointment_returning_query ) {

												$location_phone_numbers['Appointment Phone Numbers']['new'] = array(
													'link'		=> uamswp_fad_create_telephone_link(
														$location_phone_appointment_new, // string // phone number
														'icon-phone', // string // class attribute value
														$location_phone_data_categorytitle, // string // data-categorytitle attribute value
														$location_phone_data_itemtitle, // string // data-itemtitle attribute value
														'Appointment Phone Number for New Patients' // string // data-typetitle attribute value
													),
													'subtitle'	=> 'New Patients'
												);

											} else {

												$location_phone_numbers['Appointment Phone Numbers']['new'] = array(
													'link'		=> uamswp_fad_create_telephone_link(
														$location_phone_appointment_new, // string // phone number
														'icon-phone', // string // class attribute value
														$location_phone_data_categorytitle, // string // data-categorytitle attribute value
														$location_phone_data_itemtitle, // string // data-itemtitle attribute value
														'Appointment Phone Number for New and Returning Patients' // string // data-typetitle attribute value
													),
													'subtitle'	=> 'New and Returning Patients'
												);

											} // endif

										} // endif

										if ( $location_phone_appointment_returning ) {

											$location_phone_numbers['Appointment Phone Numbers']['returning'] = array(
												'link'		=> uamswp_fad_create_telephone_link(
													$location_phone_appointment_returning, // string // phone number
													'icon-phone', // string // class attribute value
													$location_phone_data_categorytitle, // string // data-categorytitle attribute value
													$location_phone_data_itemtitle, // string // data-itemtitle attribute value
													'Appointment Phone Number for Returning Patients' // string // data-typetitle attribute value
												),
												'subtitle'	=> 'Returning Patients'
											);

										} // endif

							} // endif

							// Make adjustments to the location phone numbers array

								// Remove general phone number item if there is no phone number value

									if (
										isset($location_phone_numbers['General Information'])
										&&
										(
											!isset($location_phone_numbers['General Information']['general']['link'])
											||
											empty($location_phone_numbers['General Information']['general']['link'])
										)
									) {

										// unset($location_phone_numbers['General Information']);

									} // endif

								// Remove appointment phone number item if there is no phone number value

									if ( isset($location_phone_numbers['Appointment Phone Numbers']) ) {

										foreach ( $location_phone_numbers['Appointment Phone Numbers'] as $item ) {

											if (
												!isset($item['link'])
												||
												empty($item['link'])
											) {

												// unset($item);

											} // endif

										} // endforeach

										// If the appointment phone numbers item is now empty, remove it

											if ( empty($location_phone_numbers['Appointment Phone Numbers']) ) {

												// unset($location_phone_numbers['Appointment Phone Numbers']);

											}

									} // endif

								// Change key of appointment phone numbers item to singular if there is only one phone number sub-item

									if (
										isset($location_phone_numbers['Appointment Phone Numbers'])
										&&
										count($location_phone_numbers['Appointment Phone Numbers']) == 1
									) {

										$location_phone_numbers['Appointment Phone Number'] = $location_phone_numbers['Appointment Phone Numbers'];
										unset($location_phone_numbers['Appointment Phone Numbers']);

									}

							// Fax number

								if (
									!$location_ac_query // If this is not an Arkansas Children's location...
								) {

									$location_fax = get_field('location_fax', $page_id) ?: ''; 

									// Build the anchor element for the fax number

										$location_fax_link = uamswp_fad_create_telephone_link(
											$location_fax, // string // phone number
											'icon-phone', // string // class attribute value
											$location_phone_data_categorytitle, // string // data-categorytitle attribute value
											$location_phone_data_itemtitle, // string // data-itemtitle attribute value
											'Clinic Fax Number' // string // data-typetitle attribute value
										);

								}

							// Add to the variables array

								$location_card_fields_vars['location_phone_numbers'] = isset($location_phone_numbers) ? $location_phone_numbers : '';
								$location_card_fields_vars['location_phone_data_categorytitle'] = isset($location_phone_data_categorytitle) ? $location_phone_data_categorytitle : '';
								$location_card_fields_vars['location_appointments_query'] = isset($location_appointments_query) ? $location_appointments_query : '';
								$location_card_fields_vars['location_fax_link'] = isset($location_fax_link) ? $location_fax_link : '';

							// Add location details to schema data

								// Query for whether to add locations to schema

									$location_section_schema_query = isset($location_section_schema_query) ? $location_section_schema_query : false;

								if ( $location_section_schema_query ) {

									// Address Schema Data

										// Check/define the main address schema array

											$schema_address = ( isset($schema_address) && is_array($schema_address) ) ? $schema_address : array();

										// Add this location's details to the main address schema array

											$schema_address = uamswp_fad_schema_postaladdress(
												( isset($location_address_schema) ? $location_address_schema : '' ), // string // Required // The street address or the post office box number for PO box addresses.
												true, // bool // Required // Query for whether the address is a street address (as opposed to a post office box number)
												( isset($location_city) ? $location_city : '' ), // string // Required // The locality in which the street address is, and which is in the region. For example, Mountain View.
												( isset($location_state) ? $location_state : '' ), // string // Required // The region in which the locality is, and which is in the country. For example, California or another appropriate first-level Administrative division.
												( isset($location_zip) ? $location_zip : '' ), // string // Required // The postal code (e.g., 94043).
												'', // string // Optional // The country's ISO 3166-1 alpha-2 country code. // Default: 'US'
												( isset($location_title) ? $location_title : '' ), // string // Optional // The name of the item.
												( isset($location_phone) ? $location_phone : '' ), // string // Optional // The telephone number.
												( isset($location_fax) ? $location_fax : '' ), // string // Optional // The fax number.
												$schema_address // array // Optional // Main PostalAddress schema array
											);

									// Telephone Schema Data

										// Check/define the main telephone schema array

											$schema_telephone =  (( isset($schema_telephone) && is_array($schema_telephone) && !empty($schema_telephone) ) ? $schema_telephone : array() );

										// Add this location's details to the main telephone schema array

											$schema_telephone = uamswp_fad_schema_telephone(
												$schema_telephone, // array (optional) // Main telephone schema array
												( isset($location_phone) ? $location_phone : '' ) // string (optional) // The telephone number.
											);

									// Fax Schema Data

										// Check/define the main fax number schema array

											$schema_fax_number = ( isset($schema_fax_number) && is_array($schema_fax_number) && !empty($schema_telephone) ) ? $schema_fax_number : array();

										// Add this location's details to the main fax number schema array

											$schema_fax_number = uamswp_fad_schema_fax_number(
												$schema_fax_number, // array (optional) // Main faxNumber schema array
												( isset($location_fax) ? $location_fax : '' ) // string (optional) // The fax number.
											);

									// GeoCoordinates Schema Data

										// Check/define the main GeoCoordinates schema array

											$schema_geo_coordinates = ( isset($schema_geo_coordinates) && is_array($schema_geo_coordinates) && !empty($schema_geo_coordinates) ) ? $schema_geo_coordinates : array();

										// Add this location's details to the main GeoCoordinates schema array

											$schema_geo_coordinates = uamswp_schema_geo_coordinates(
												$location_map['lat'], // string // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
												$location_map['lng'], // string // Required // The longitude of a location. For example -122.08585 (WGS 84). // The precision must be at least 5 decimal places.
												'', // string // Optional // The elevation of a location (WGS 84). Values may be of the form 'NUMBER UNIT_OF_MEASUREMENT' (e.g., '1,000 m', '3,200 ft') while numbers alone should be assumed to be a value in meters.
												$schema_geo_coordinates // array // Optional // Existing main GeoCoordinates schema array
											);

								} // endif ( $location_section_schema_query )

								// Add to the variables array

									$location_card_fields_vars['schema_address'] = isset($schema_address) ? $schema_address : '';
									$location_card_fields_vars['schema_telephone'] = isset($schema_telephone) ? $schema_telephone : '';
									$location_card_fields_vars['schema_fax_number'] = isset($schema_fax_number) ? $schema_fax_number : '';
									$location_card_fields_vars['schema_geo'] = isset($schema_geo_coordinates) ? $schema_geo_coordinates : '';

					// Location Card Styles

						if ( 'basic' == $location_card_style ) {

							// Construct address paragraph text

								$location_address_text = implode(
									'<br />',
									array_filter(
										array(
											$location_address_1,
											$location_address_detail,
											$location_address_final_line
										)
									)
								);

							// Add to the variables array
							$location_card_fields_vars['location_address_text'] = isset($location_address_text) ? $location_address_text : '';

						} elseif ( 'detailed' == $location_card_style ) {

						} elseif ( 'primary-location' == $location_card_style ) {

							// Reference to parent location

								if ( $location_parent_display ) {

									$location_parent_reference = '(Part of <a href="' . $location_parent_url . '" data-categorytitle="Parent Name">' . $location_parent_title . '</a>)';

								} else {

									$location_parent_reference = '';

								}

							// Construct address paragraph text

								$location_address_text = implode(
									'<br />',
									array_filter(
										array(
											'<strong>' . $location_title . '</strong>',
											$location_parent_reference,
											$location_address_1,
											$location_address_detail,
											$location_address_final_line
										)
									)
								);

							// Add to the variables array
							$location_card_fields_vars['location_address_text'] = isset($location_address_text) ? $location_address_text : '';

						} // endif

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $location_card_style . '_' . $page_id, // Required // String added to transient name for disambiguation.
					$location_card_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $location_card_fields_vars;

			}

		}

	// Area of expertise profile field values

		function uamswp_fad_expertise_profile_fields(
			$page_id, // int // ID of the profile
			$current_fpage = '' // int // Current fake subpage slug
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id . ( $current_fpage ? '_' . $current_fpage : ''), // Required // String added to transient name for disambiguation.
				$expertise_profile_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $expertise_profile_fields_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $expertise_profile_fields_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Create a variables array to be used on the templates and template parts
				$expertise_profile_fields_vars = array();

				// Get the field values

					// Common

						global $post;

						// Post Title (if current item is a fake subpage, title of the parent item)

							$page_title = get_the_title();
							$page_title_attr = uamswp_attr_conversion($page_title);

							// Array for page titles and section titles

								$page_titles = array(
									'page_title'		=> $page_title,
									'page_title_attr'	=> $page_title_attr
								);

							// Add to the variables array

								$expertise_profile_fields_vars['page_title'] = isset($page_title) ? $page_title : '';
								$expertise_profile_fields_vars['page_titles'] = isset($page_titles) ? $page_titles : '';

						// Post URL (if current item is a fake subpage, URL of the parent item)

							$page_url = user_trailingslashit(get_permalink());

							// Add to the variables array
							$expertise_profile_fields_vars['page_url'] = isset($page_url) ? $page_url : '';

						// Post Slug (if current item is a fake subpage, slug of the parent item)

							$page_slug = $post->post_name;

							// Add to the variables array
							$expertise_profile_fields_vars['page_slug'] = isset($page_slug) ? $page_slug : '';

						// Ontology / Content Type

							$ontology_type = get_field('expertise_type'); // Ontology type of the post (true is ontology type, false is content type)
							$ontology_type = isset($ontology_type) ? $ontology_type : true; // Check if 'expertise_type' is not null, and if so, set value to true

							// Add to the variables array
							$expertise_profile_fields_vars['ontology_type'] = isset($ontology_type) ? $ontology_type : '';

						// Get system settings for text elements in an area of expertise subsection (or profile)

							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/specific-placement/expertise.php' );

							// Add to the variables array

								$expertise_profile_fields_vars['expertise_page_title_options'] = isset($expertise_page_title_options) ? $expertise_page_title_options: '';
								$expertise_profile_fields_vars['expertise_page_title'] = isset($expertise_page_title) ? $expertise_page_title: '';
								$expertise_profile_fields_vars['expertise_page_intro'] = isset($expertise_page_intro) ? $expertise_page_intro: '';
								$expertise_profile_fields_vars['expertise_page_image'] = isset($expertise_page_image) ? $expertise_page_image: '';
								$expertise_profile_fields_vars['expertise_page_image_mobile'] = isset($expertise_page_image_mobile) ? $expertise_page_image_mobile: '';
								$expertise_profile_fields_vars['expertise_short_desc'] = isset($expertise_short_desc) ? $expertise_short_desc: '';
								$expertise_profile_fields_vars['provider_fpage_title_expertise'] = isset($provider_fpage_title_expertise) ? $provider_fpage_title_expertise: '';
								$expertise_profile_fields_vars['provider_fpage_intro_expertise'] = isset($provider_fpage_intro_expertise) ? $provider_fpage_intro_expertise: '';
								$expertise_profile_fields_vars['provider_fpage_ref_main_title_expertise'] = isset($provider_fpage_ref_main_title_expertise) ? $provider_fpage_ref_main_title_expertise: '';
								$expertise_profile_fields_vars['provider_fpage_ref_main_intro_expertise'] = isset($provider_fpage_ref_main_intro_expertise) ? $provider_fpage_ref_main_intro_expertise: '';
								$expertise_profile_fields_vars['provider_fpage_ref_main_link_expertise'] = isset($provider_fpage_ref_main_link_expertise) ? $provider_fpage_ref_main_link_expertise: '';
								$expertise_profile_fields_vars['provider_fpage_ref_top_title_expertise'] = isset($provider_fpage_ref_top_title_expertise) ? $provider_fpage_ref_top_title_expertise: '';
								$expertise_profile_fields_vars['provider_fpage_ref_top_intro_expertise'] = isset($provider_fpage_ref_top_intro_expertise) ? $provider_fpage_ref_top_intro_expertise: '';
								$expertise_profile_fields_vars['provider_fpage_ref_top_link_expertise'] = isset($provider_fpage_ref_top_link_expertise) ? $provider_fpage_ref_top_link_expertise: '';
								$expertise_profile_fields_vars['provider_fpage_short_desc_expertise'] = isset($provider_fpage_short_desc_expertise) ? $provider_fpage_short_desc_expertise: '';
								$expertise_profile_fields_vars['location_fpage_title_expertise'] = isset($location_fpage_title_expertise) ? $location_fpage_title_expertise: '';
								$expertise_profile_fields_vars['location_fpage_intro_expertise'] = isset($location_fpage_intro_expertise) ? $location_fpage_intro_expertise: '';
								$expertise_profile_fields_vars['location_fpage_short_desc_expertise'] = isset($location_fpage_short_desc_expertise) ? $location_fpage_short_desc_expertise: '';
								$expertise_profile_fields_vars['location_fpage_ref_main_title_expertise'] = isset($location_fpage_ref_main_title_expertise) ? $location_fpage_ref_main_title_expertise: '';
								$expertise_profile_fields_vars['location_fpage_ref_main_intro_expertise'] = isset($location_fpage_ref_main_intro_expertise) ? $location_fpage_ref_main_intro_expertise: '';
								$expertise_profile_fields_vars['location_fpage_ref_main_link_expertise'] = isset($location_fpage_ref_main_link_expertise) ? $location_fpage_ref_main_link_expertise: '';
								$expertise_profile_fields_vars['location_fpage_ref_top_title_expertise'] = isset($location_fpage_ref_top_title_expertise) ? $location_fpage_ref_top_title_expertise: '';
								$expertise_profile_fields_vars['location_fpage_ref_top_intro_expertise'] = isset($location_fpage_ref_top_intro_expertise) ? $location_fpage_ref_top_intro_expertise: '';
								$expertise_profile_fields_vars['location_fpage_ref_top_link_expertise'] = isset($location_fpage_ref_top_link_expertise) ? $location_fpage_ref_top_link_expertise: '';
								$expertise_profile_fields_vars['expertise_descendant_fpage_title_expertise'] = isset($expertise_descendant_fpage_title_expertise) ? $expertise_descendant_fpage_title_expertise: '';
								$expertise_profile_fields_vars['expertise_descendant_fpage_intro_expertise'] = isset($expertise_descendant_fpage_intro_expertise) ? $expertise_descendant_fpage_intro_expertise: '';
								$expertise_profile_fields_vars['expertise_descendant_fpage_short_desc_expertise'] = isset($expertise_descendant_fpage_short_desc_expertise) ? $expertise_descendant_fpage_short_desc_expertise: '';
								$expertise_profile_fields_vars['expertise_descendant_fpage_ref_main_title_expertise'] = isset($expertise_descendant_fpage_ref_main_title_expertise) ? $expertise_descendant_fpage_ref_main_title_expertise: '';
								$expertise_profile_fields_vars['expertise_descendant_fpage_ref_main_intro_expertise'] = isset($expertise_descendant_fpage_ref_main_intro_expertise) ? $expertise_descendant_fpage_ref_main_intro_expertise: '';
								$expertise_profile_fields_vars['expertise_descendant_fpage_ref_main_link_expertise'] = isset($expertise_descendant_fpage_ref_main_link_expertise) ? $expertise_descendant_fpage_ref_main_link_expertise: '';
								$expertise_profile_fields_vars['expertise_fpage_title_expertise'] = isset($expertise_fpage_title_expertise) ? $expertise_fpage_title_expertise: '';
								$expertise_profile_fields_vars['expertise_fpage_intro_expertise'] = isset($expertise_fpage_intro_expertise) ? $expertise_fpage_intro_expertise: '';
								$expertise_profile_fields_vars['expertise_fpage_short_desc_expertise'] = isset($expertise_fpage_short_desc_expertise) ? $expertise_fpage_short_desc_expertise: '';
								$expertise_profile_fields_vars['expertise_fpage_ref_main_title_expertise'] = isset($expertise_fpage_ref_main_title_expertise) ? $expertise_fpage_ref_main_title_expertise: '';
								$expertise_profile_fields_vars['expertise_fpage_ref_main_intro_expertise'] = isset($expertise_fpage_ref_main_intro_expertise) ? $expertise_fpage_ref_main_intro_expertise: '';
								$expertise_profile_fields_vars['expertise_fpage_ref_main_link_expertise'] = isset($expertise_fpage_ref_main_link_expertise) ? $expertise_fpage_ref_main_link_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_title_expertise'] = isset($clinical_resource_fpage_title_expertise) ? $clinical_resource_fpage_title_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_intro_expertise'] = isset($clinical_resource_fpage_intro_expertise) ? $clinical_resource_fpage_intro_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_ref_main_title_expertise'] = isset($clinical_resource_fpage_ref_main_title_expertise) ? $clinical_resource_fpage_ref_main_title_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_ref_main_intro_expertise'] = isset($clinical_resource_fpage_ref_main_intro_expertise) ? $clinical_resource_fpage_ref_main_intro_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_ref_main_link_expertise'] = isset($clinical_resource_fpage_ref_main_link_expertise) ? $clinical_resource_fpage_ref_main_link_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_ref_top_title_expertise'] = isset($clinical_resource_fpage_ref_top_title_expertise) ? $clinical_resource_fpage_ref_top_title_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_ref_top_intro_expertise'] = isset($clinical_resource_fpage_ref_top_intro_expertise) ? $clinical_resource_fpage_ref_top_intro_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_ref_top_link_expertise'] = isset($clinical_resource_fpage_ref_top_link_expertise) ? $clinical_resource_fpage_ref_top_link_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_more_text_expertise'] = isset($clinical_resource_fpage_more_text_expertise) ? $clinical_resource_fpage_more_text_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_more_link_text_expertise'] = isset($clinical_resource_fpage_more_link_text_expertise) ? $clinical_resource_fpage_more_link_text_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_more_link_descr_expertise'] = isset($clinical_resource_fpage_more_link_descr_expertise) ? $clinical_resource_fpage_more_link_descr_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_short_desc_expertise'] = isset($clinical_resource_fpage_short_desc_expertise) ? $clinical_resource_fpage_short_desc_expertise: '';
								$expertise_profile_fields_vars['condition_fpage_title_expertise'] = isset($condition_fpage_title_expertise) ? $condition_fpage_title_expertise: '';
								$expertise_profile_fields_vars['condition_fpage_intro_expertise'] = isset($condition_fpage_intro_expertise) ? $condition_fpage_intro_expertise: '';
								$expertise_profile_fields_vars['treatment_fpage_title_expertise'] = isset($treatment_fpage_title_expertise) ? $treatment_fpage_title_expertise: '';
								$expertise_profile_fields_vars['treatment_fpage_intro_expertise'] = isset($treatment_fpage_intro_expertise) ? $treatment_fpage_intro_expertise: '';
								$expertise_profile_fields_vars['condition_treatment_fpage_title_expertise'] = isset($condition_treatment_fpage_title_expertise) ? $condition_treatment_fpage_title_expertise: '';
								$expertise_profile_fields_vars['condition_treatment_fpage_intro_expertise'] = isset($condition_treatment_fpage_intro_expertise) ? $condition_treatment_fpage_intro_expertise: '';

						// Get system settings for area of expertise profile image elements

							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/single/specific-placement/expertise.php' );

							// Add to the variables array

								$expertise_profile_fields_vars['expertise_featured_image'] = isset($expertise_featured_image) ? $expertise_featured_image: '';
								$expertise_profile_fields_vars['expertise_featured_image_url'] = isset($expertise_featured_image_url) ? $expertise_featured_image_url: '';
								$expertise_profile_fields_vars['provider_fpage_featured_image_expertise'] = isset($provider_fpage_featured_image_expertise) ? $provider_fpage_featured_image_expertise: '';
								$expertise_profile_fields_vars['provider_fpage_featured_image_expertise_url'] = isset($provider_fpage_featured_image_expertise_url) ? $provider_fpage_featured_image_expertise_url: '';
								$expertise_profile_fields_vars['location_fpage_featured_image_expertise'] = isset($location_fpage_featured_image_expertise) ? $location_fpage_featured_image_expertise: '';
								$expertise_profile_fields_vars['location_fpage_featured_image_expertise_url'] = isset($location_fpage_featured_image_expertise_url) ? $location_fpage_featured_image_expertise_url: '';
								$expertise_profile_fields_vars['expertise_fpage_featured_image_expertise'] = isset($expertise_fpage_featured_image_expertise) ? $expertise_fpage_featured_image_expertise: '';
								$expertise_profile_fields_vars['expertise_fpage_featured_image_expertise_url'] = isset($expertise_fpage_featured_image_expertise_url) ? $expertise_fpage_featured_image_expertise_url: '';
								$expertise_profile_fields_vars['expertise_descendant_fpage_featured_image_expertise'] = isset($expertise_descendant_fpage_featured_image_expertise) ? $expertise_descendant_fpage_featured_image_expertise: '';
								$expertise_profile_fields_vars['expertise_descendant_fpage_featured_image_expertise_url'] = isset($expertise_descendant_fpage_featured_image_expertise_url) ? $expertise_descendant_fpage_featured_image_expertise_url: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_featured_image_expertise'] = isset($clinical_resource_fpage_featured_image_expertise) ? $clinical_resource_fpage_featured_image_expertise: '';
								$expertise_profile_fields_vars['clinical_resource_fpage_featured_image_expertise_url'] = isset($clinical_resource_fpage_featured_image_expertise_url) ? $clinical_resource_fpage_featured_image_expertise_url: '';

						// Get the ontology subsection values

							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/ontology-subsection.php' );

							// Add to the variables array

								$expertise_profile_fields_vars['site_nav_id'] = isset($site_nav_id) ? $site_nav_id: '';
								$expertise_profile_fields_vars['site_nav_title'] = isset($site_nav_title) ? $site_nav_title: '';
								$expertise_profile_fields_vars['site_nav_title_attr'] = isset($site_nav_title_attr) ? $site_nav_title_attr: '';
								$expertise_profile_fields_vars['site_nav_url'] = isset($site_nav_url) ? $site_nav_url: '';
								$expertise_profile_fields_vars['navbar_subbrand_title'] = isset($navbar_subbrand_title) ? $navbar_subbrand_title: '';
								$expertise_profile_fields_vars['navbar_subbrand_title_attr'] = isset($navbar_subbrand_title_attr) ? $navbar_subbrand_title_attr: '';
								$expertise_profile_fields_vars['navbar_subbrand_title_url'] = isset($navbar_subbrand_title_url) ? $navbar_subbrand_title_url: '';
								$expertise_profile_fields_vars['navbar_subbrand_parent'] = isset($navbar_subbrand_parent) ? $navbar_subbrand_parent: '';
								$expertise_profile_fields_vars['navbar_subbrand_parent_attr'] = isset($navbar_subbrand_parent_attr) ? $navbar_subbrand_parent_attr: '';
								$expertise_profile_fields_vars['navbar_subbrand_parent_url'] = isset($navbar_subbrand_parent_url) ? $navbar_subbrand_parent_url: '';
								$expertise_profile_fields_vars['providers'] = isset($providers) ? $providers: '';
								$expertise_profile_fields_vars['locations'] = isset($locations) ? $locations: '';
								$expertise_profile_fields_vars['expertises'] = isset($expertises) ? $expertises: '';
								$expertise_profile_fields_vars['expertise_descendants'] = isset($expertise_descendants) ? $expertise_descendants: '';
								$expertise_profile_fields_vars['clinical_resources'] = isset($clinical_resources) ? $clinical_resources: '';
								$expertise_profile_fields_vars['conditions_cpt'] = isset($conditions_cpt) ? $conditions_cpt: '';
								$expertise_profile_fields_vars['treatments_cpt'] = isset($treatments_cpt) ? $treatments_cpt: '';
								$expertise_profile_fields_vars['ancestors_ontology_farthest'] = isset($ancestors_ontology_farthest) ? $ancestors_ontology_farthest: '';
								$expertise_profile_fields_vars['page_top_level_query'] = isset($page_top_level_query) ? $page_top_level_query: '';

						// Post Featured Image (if current item is a fake subpage, featured image of the parent item)

							$featured_image = $expertise_featured_image; // Image ID
							$featured_image = $featured_image ? $featured_image : '';

							// Add to the variables array
							$expertise_profile_fields_vars['featured_image'] = isset($featured_image) ? $featured_image : '';

						// Page template class

							$template_type = 'default';

							// Add to the variables array
							$expertise_profile_fields_vars['template_type'] = isset($template_type) ? $template_type : '';

						// Meta title

							// Get system settings for area of expertise labels
							include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

							$meta_title_enhanced_addition = $expertise_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
							include( UAMS_FAD_PATH . '/templates/parts/html/meta/title.php' );

							// Add to the variables array
							$expertise_profile_fields_vars['meta_title'] = isset($meta_title) ? $meta_title : '';

						// Meta Description and Schema Description

							// Get excerpt

								$excerpt = get_the_excerpt(); // 'expertise_selected_post_excerpt'
								$excerpt_attr = uamswp_attr_conversion($excerpt);
								$excerpt_user = true;

								if ( empty( $excerpt ) ) {

									$excerpt_user = false;

								}

							// Set schema description

								$schema_description = $excerpt_attr; // Used for Schema Data. Should ALWAYS have a value

							// Add to the variables array

								$expertise_profile_fields_vars['excerpt'] = isset($excerpt) ? $excerpt : '';
								$expertise_profile_fields_vars['excerpt_attr'] = isset($excerpt_attr) ? $excerpt_attr : '';
								$expertise_profile_fields_vars['excerpt_user'] = isset($excerpt_user) ? $excerpt_user : '';
								$expertise_profile_fields_vars['schema_description'] = isset($schema_description) ? $schema_description : '';

							// Meta Keywords

								$keywords = get_field('expertise_alternate_names');

								// Add to the variables array
								$expertise_profile_fields_vars['keywords'] = isset($keywords) ? $keywords : '';

							// Meta Social Media Tags

								// Filter hooks
								include( UAMS_FAD_PATH . '/templates/parts/html/meta/social.php' );

								// Add to the variables array

									$expertise_profile_fields_vars['meta_og_type'] = isset($meta_og_type) ? $meta_og_type : '';
									$expertise_profile_fields_vars['meta_og_type_property'] = isset($meta_og_type_property) ? $meta_og_type_property : '';
									$expertise_profile_fields_vars['meta_og_namespace'] = isset($meta_og_namespace) ? $meta_og_namespace : '';
									$expertise_profile_fields_vars['meta_og_updated_time'] = isset($meta_og_updated_time) ? $meta_og_updated_time : '';
									$expertise_profile_fields_vars['meta_article_author'] = isset($meta_article_author) ? $meta_article_author : '';
									$expertise_profile_fields_vars['meta_article_author_content'] = isset($meta_article_author_content) ? $meta_article_author_content : '';
									$expertise_profile_fields_vars['meta_article_author_count'] = isset($meta_article_author_count) ? $meta_article_author_count : '';
									$expertise_profile_fields_vars['meta_article_publisher'] = isset($meta_article_publisher) ? $meta_article_publisher : '';
									$expertise_profile_fields_vars['meta_article_publisher_content'] = isset($meta_article_publisher_content) ? $meta_article_publisher_content : '';
									$expertise_profile_fields_vars['meta_article_publisher_count'] = isset($meta_article_publisher_count) ? $meta_article_publisher_count : '';
									$expertise_profile_fields_vars['meta_og_locale'] = isset($meta_og_locale) ? $meta_og_locale : '';
									$expertise_profile_fields_vars['meta_og_url'] = isset($meta_og_url) ? $meta_og_url : '';
									$expertise_profile_fields_vars['meta_og_title'] = isset($meta_og_title) ? $meta_og_title : '';
									$expertise_profile_fields_vars['meta_og_image'] = isset($meta_og_image) ? $meta_og_image : '';
									$expertise_profile_fields_vars['meta_og_image_width'] = isset($meta_og_image_width) ? $meta_og_image_width : '';
									$expertise_profile_fields_vars['meta_og_image_height'] = isset($meta_og_image_height) ? $meta_og_image_height : '';
									$expertise_profile_fields_vars['meta_og_site_name'] = isset($meta_og_site_name) ? $meta_og_site_name : '';
									$expertise_profile_fields_vars['meta_og_description'] = isset($meta_og_description) ? $meta_og_description : '';
									$expertise_profile_fields_vars['meta_oembed_title'] = isset($meta_oembed_title) ? $meta_oembed_title : '';
									$expertise_profile_fields_vars['meta_oembed_thumbnail_size'] = isset($meta_oembed_thumbnail_size) ? $meta_oembed_thumbnail_size : '';
									$expertise_profile_fields_vars['meta_oembed_thumbnail'] = isset($meta_oembed_thumbnail) ? $meta_oembed_thumbnail : '';
									$expertise_profile_fields_vars['meta_oembed_thumbnail_width'] = isset($meta_oembed_thumbnail_width) ? $meta_oembed_thumbnail_width : '';
									$expertise_profile_fields_vars['meta_oembed_thumbnail_height'] = isset($meta_oembed_thumbnail_height) ? $meta_oembed_thumbnail_height : '';
									$expertise_profile_fields_vars['meta_twitter_card_type'] = isset($meta_twitter_card_type) ? $meta_twitter_card_type : '';
									$expertise_profile_fields_vars['meta_twitter_site'] = isset($meta_twitter_site) ? $meta_twitter_site : '';
									$expertise_profile_fields_vars['meta_twitter_creator'] = isset($meta_twitter_creator) ? $meta_twitter_creator : '';
									$expertise_profile_fields_vars['meta_twitter_description'] = isset($meta_twitter_description) ? $meta_twitter_description : '';
									$expertise_profile_fields_vars['meta_twitter_title'] = isset($meta_twitter_title) ? $meta_twitter_title : '';
									$expertise_profile_fields_vars['meta_twitter_image'] = isset($meta_twitter_image) ? $meta_twitter_image : '';
									$expertise_profile_fields_vars['meta_twitter_image_alt'] = isset($meta_twitter_image_alt) ? $meta_twitter_image_alt : '';

							// Define the placement for content

								$content_placement = 'subsection'; // Expected values: 'subsection' or 'profile'

								// Add to the variables array
								$expertise_profile_fields_vars['content_placement'] = isset($content_placement) ? $content_placement : '';

							// Page title configuration

								$entry_header_style = $expertise_page_title_options ?: 'graphic'; // Entry header style
								$entry_title_text = $expertise_page_title; // Regular title
								$entry_title_text_supertitle = ''; // Optional supertitle, placed above the regular title
								$entry_title_text_subtitle = ''; // Optional subtitle, placed below the regular title
								$entry_title_text_body = $expertise_page_intro; // Optional lead paragraph, placed below the entry title
								$entry_title_image_desktop = $expertise_page_image; // Desktop breakpoint image ID
								$entry_title_image_mobile = $expertise_page_image_mobile; // Optional mobile breakpoint image ID

								// Add to the variables array

									$expertise_profile_fields_vars['entry_header_style'] = isset($entry_header_style) ? $entry_header_style : '';
									$expertise_profile_fields_vars['entry_title_text'] = isset($entry_title_text) ? $entry_title_text : '';
									$expertise_profile_fields_vars['entry_title_text_supertitle'] = isset($entry_title_text_supertitle) ? $entry_title_text_supertitle : '';
									$expertise_profile_fields_vars['entry_title_text_subtitle'] = isset($entry_title_text_subtitle) ? $entry_title_text_subtitle : '';
									$expertise_profile_fields_vars['entry_title_text_body'] = isset($entry_title_text_body) ? $entry_title_text_body : '';
									$expertise_profile_fields_vars['entry_title_image_desktop'] = isset($entry_title_image_desktop) ? $entry_title_image_desktop : '';
									$expertise_profile_fields_vars['entry_title_image_mobile'] = isset($entry_title_image_mobile) ? $entry_title_image_mobile : '';

							// Query for whether to conditionally suppress ontology sections based on based on region and service line

								$regions = isset($regions) ? $regions : array();
								$service_lines = isset($service_lines) ? $service_lines : array();

								include( UAMS_FAD_PATH . '/templates/parts/vars/page/ontology-hide.php' );

								// Add to the variables array
								$expertise_profile_fields_vars['hide_medical_ontology'] = isset($hide_medical_ontology) ? $hide_medical_ontology : '';


							// Queries for whether each of the sections should be displayed

								// Related Providers Section Query

									include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/provider.php' );

									// Add to the variables array

										$expertise_profile_fields_vars['provider_query'] = isset($provider_query) ? $provider_query : '';
										$expertise_profile_fields_vars['provider_section_show'] = isset($provider_section_show) ? $provider_section_show : '';
										$expertise_profile_fields_vars['provider_ids'] = isset($provider_ids) ? $provider_ids : '';
										$expertise_profile_fields_vars['provider_count'] = isset($provider_count) ? $provider_count : '';

								// Related Locations Section Query

									include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/location.php' );

									// Add to the variables array

										$expertise_profile_fields_vars['location_query'] = isset($location_query) ? $location_query : '';
										$expertise_profile_fields_vars['location_section_show'] = isset($location_section_show) ? $location_section_show : '';
										$expertise_profile_fields_vars['location_ids'] = isset($location_ids) ? $location_ids : '';
										$expertise_profile_fields_vars['location_count'] = isset($location_count) ? $location_count : '';
										$expertise_profile_fields_vars['location_valid'] = isset($location_valid) ? $location_valid : '';

								// Descendant Areas of Expertise Section Query

									include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/expertise-descendant.php' );

									// Add to the variables array

										$expertise_profile_fields_vars['expertise_descendant_query'] = isset($expertise_descendant_query) ? $expertise_descendant_query : '';
										$expertise_profile_fields_vars['expertise_descendant_section_show'] = isset($expertise_descendant_section_show) ? $expertise_descendant_section_show : '';
										$expertise_profile_fields_vars['expertise_descendant_ids'] = isset($expertise_descendant_ids) ? $expertise_descendant_ids : '';
										$expertise_profile_fields_vars['expertise_descendant_count'] = isset($expertise_descendant_count) ? $expertise_descendant_count : '';
										$expertise_profile_fields_vars['expertise_content_query'] = isset($expertise_content_query) ? $expertise_content_query : '';
										$expertise_profile_fields_vars['expertise_content_nav_show'] = isset($expertise_content_nav_show) ? $expertise_content_nav_show : '';
										$expertise_profile_fields_vars['expertise_content_ids'] = isset($expertise_content_ids) ? $expertise_content_ids : '';
										$expertise_profile_fields_vars['expertise_content_count'] = isset($expertise_content_count) ? $expertise_content_count : '';
										$expertise_profile_fields_vars['expertise_content_nav'] = isset($expertise_content_nav) ? $expertise_content_nav : '';

								// Related Areas of Expertise Section Query

									include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/expertise.php' );

										// Add to the variables array

											$expertise_profile_fields_vars['expertise_query'] = isset($expertise_query) ? $expertise_query : '';
											$expertise_profile_fields_vars['expertise_section_show'] = isset($expertise_section_show) ? $expertise_section_show : '';
											$expertise_profile_fields_vars['expertise_ids'] = isset($expertise_ids) ? $expertise_ids : '';
											$expertise_profile_fields_vars['expertise_count'] = isset($expertise_count) ? $expertise_count : '';

								// Related Clinical Resources Section Query

									include( UAMS_FAD_PATH . '/templates/parts/vars/sys/posts-per-page/clinical-resource.php' ); // General maximum number of clinical resource items to display on a fake subpage (or section)
									$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_fpage;
									include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/clinical-resource.php' );

									// Add to the variables array

										$expertise_profile_fields_vars['clinical_resource_query'] = isset($clinical_resource_query) ? $clinical_resource_query : '';
										$expertise_profile_fields_vars['clinical_resource_section_show'] = isset($clinical_resource_section_show) ? $clinical_resource_section_show : '';
										$expertise_profile_fields_vars['clinical_resource_ids'] = isset($clinical_resource_ids) ? $clinical_resource_ids : '';
										$expertise_profile_fields_vars['clinical_resource_count'] = isset($clinical_resource_count) ? $clinical_resource_count : '';

								// Related Conditions Section Query

									include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/condition.php' );

									// Add to the variables array

										$expertise_profile_fields_vars['condition_cpt_query'] = isset($condition_cpt_query) ? $condition_cpt_query : '';
										$expertise_profile_fields_vars['condition_section_show'] = isset($condition_section_show) ? $condition_section_show : '';
										$expertise_profile_fields_vars['condition_ids'] = isset($condition_ids) ? $condition_ids : '';
										$expertise_profile_fields_vars['condition_count'] = isset($condition_count) ? $condition_count : '';
										$expertise_profile_fields_vars['schema_medical_specialty'] = isset($schema_medical_specialty) ? $schema_medical_specialty : '';

								// Related Treatments Section Query

									include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/treatment.php' );

									// Add to the variables array

										$expertise_profile_fields_vars['treatment_cpt_query'] = isset($treatment_cpt_query) ? $treatment_cpt_query : '';
										$expertise_profile_fields_vars['treatment_section_show'] = isset($treatment_section_show) ? $treatment_section_show : '';
										$expertise_profile_fields_vars['treatment_ids'] = isset($treatment_ids) ? $treatment_ids : '';
										$expertise_profile_fields_vars['treatment_count'] = isset($treatment_count) ? $treatment_count : '';
										$expertise_profile_fields_vars['schema_medical_specialty'] = isset($schema_medical_specialty) ? $schema_medical_specialty : '';

								// Combined Conditions and Treatments Section Query

										$expertise_profile_fields_vars['condition_treatment_section_show'] = isset($condition_treatment_section_show) ? $condition_treatment_section_show : '';

								// Query for whether Make an Appointment section should be displayed

									$appointment_section_show = true; // It should always be displayed.

									// Add to the variables array

										$expertise_profile_fields_vars['appointment_section_show'] = isset($appointment_section_show) ? $appointment_section_show : '';

								// Jump links

									$jump_link_count = isset($jump_link_count) ? $jump_link_count : '';

									// Add to the variables array

										$expertise_profile_fields_vars['jump_link_count'] = isset($jump_link_count) ? $jump_link_count : '';

						// Ontology subsection site header
						include( UAMS_FAD_PATH . '/templates/parts/html/site-header/single-expertise.php');

						// Ontology subsection primary navigation
						include( UAMS_FAD_PATH . '/templates/parts/html/site-nav/single-expertise.php');

						// Construct non-standard post title
						include( UAMS_FAD_PATH . '/templates/parts/html/entry-title/' . $entry_header_style . '.php');

					// Overview / Content Pages

						if ( !$current_fpage ) {

						}

					// Fake Subpage: Related Providers

						if ( 'providers' == $current_fpage ) {

						}

					// Fake Subpage: Related Locations

						if ( 'locations' == $current_fpage ) {

						}

					// Fake Subpage: Descendant Areas of Expertise

						if ( 'specialties' == $current_fpage ) {

						}

					// Fake Subpage: Related Areas of Expertise

						if ( 'related' == $current_fpage ) {

						}

					// Fake Subpage: Related Clinical Resources

						if ( 'resources' == $current_fpage ) {

						}

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id . ( $current_fpage ? '_' . $current_fpage : ''), // Required // String added to transient name for disambiguation.
					$expertise_profile_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $expertise_profile_fields_vars;

			}

		}

	// Area of expertise card field values

		function uamswp_fad_expertise_card_fields(
			$page_id, // int // ID of the profile
			$expertise_card_style = 'basic' // string enum('basic', 'detailed') // Area of expertise card style
		) {

			// Check optional variables
			$expertise_card_style = ( 'basic' == $expertise_card_style || 'detailed' == $expertise_card_style ) ? $expertise_card_style : 'basic';

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $expertise_card_style . '_' . $page_id, // Required // String added to transient name for disambiguation.
				$expertise_card_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $expertise_card_fields_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $expertise_card_fields_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Create a variables array to be used on the templates and template parts
				$expertise_card_fields_vars = array();

				// Get the field values

					// Post Title

						$expertise_title = get_the_title($page_id); // string
						$expertise_title_attr = uamswp_attr_conversion($expertise_title); // string

						// Add to the variables array

							$expertise_card_fields_vars['expertise_title'] = isset($expertise_title) ? $expertise_title : '';
							$expertise_card_fields_vars['expertise_title_attr'] = isset($expertise_title_attr) ? $expertise_title_attr : '';

					// Post Excerpt

						$expertise_excerpt = get_field( 'expertise_selected_post_excerpt', $page_id ) ?: ''; // string
						$expertise_excerpt = $expertise_excerpt ?: get_the_excerpt($page_id); // string
						$expertise_excerpt = $expertise_excerpt ?: wp_strip_all_tags( get_the_content($page_id) ); // string
						$expertise_excerpt = $expertise_excerpt ?: ''; // string

						if ( $expertise_excerpt ) {

							// Truncate the excerpt if it is greater than 160 characters

								if ( strlen($expertise_excerpt) > 160 ) {

									$expertise_excerpt = wp_trim_words( $expertise_excerpt, 23, ' &hellip;' );

								}

							$expertise_excerpt_attr = uamswp_attr_conversion($expertise_excerpt); // string

						}

						// Add to the variables array

							$expertise_card_fields_vars['expertise_excerpt'] = isset($expertise_excerpt) ? $expertise_excerpt : '';
							$expertise_card_fields_vars['expertise_excerpt_attr'] = isset($expertise_excerpt_attr) ? $expertise_excerpt_attr : '';

					// Post URL

						$expertise_url = get_permalink($page_id); // string

						// Add to the variables array
						$expertise_card_fields_vars['expertise_url'] = isset($expertise_url) ? $expertise_url : '';

					// Post Featured Image

						// Featured image ID

							$expertise_featured_image = get_post_thumbnail_id($page_id) ?: ''; // int

							if ( $expertise_featured_image ) {

								// Featured image URL
								$expertise_featured_image_url = wp_get_attachment_image_url( $expertise_featured_image, 'aspect-16-9-small' ) ?: ''; // string

							}

						// Add to the variables array

							$expertise_card_fields_vars['expertise_featured_image'] = isset($expertise_featured_image) ? $expertise_featured_image : '';
							$expertise_card_fields_vars['expertise_featured_image_url'] = isset($expertise_featured_image_url) ? $expertise_featured_image_url : '';

					// Parent Area of Expertise

						// Parent ID

							$expertise_parent_id = wp_get_post_parent_id($page_id) ?: ''; // int

							if ( $expertise_parent_id ) {

								// Parent post object

									$expertise_parent_object = get_post($expertise_parent_id) ?: ''; // object

									if ( $expertise_parent_object ) {

										// Parent title

											$expertise_parent_title = $expertise_parent_object->post_title ?: ''; // string
											$expertise_parent_title_attr = uamswp_attr_conversion($expertise_parent_title); // string

										// Parent URL

											$expertise_parent_url = get_permalink($expertise_parent_id) ?: ''; // string

									}

							}

						// Add to the variables array

							$expertise_card_fields_vars['expertise_parent_id'] = isset($expertise_parent_id) ? $expertise_parent_id : '';
							$expertise_card_fields_vars['expertise_parent_object'] = isset($expertise_parent_object) ? $expertise_parent_object : '';
							$expertise_card_fields_vars['expertise_parent_title'] = isset($expertise_parent_title) ? $expertise_parent_title : '';
							$expertise_card_fields_vars['expertise_parent_title_attr'] = isset($expertise_parent_title_attr) ? $expertise_parent_title_attr : '';
							$expertise_card_fields_vars['expertise_parent_url'] = isset($expertise_parent_url) ? $expertise_parent_url : '';

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $expertise_card_style . '_' . $page_id, // Required // String added to transient name for disambiguation.
					$expertise_card_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $expertise_card_fields_vars;

			}

		}

	// Clinical resource profile field values

		function uamswp_fad_clinical_resource_profile_fields(
			$page_id // int // ID of the profile
		) {

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $page_id, // Required // String added to transient name for disambiguation.
				$clinical_resource_profile_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $clinical_resource_profile_fields_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $clinical_resource_profile_fields_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Create a variables array to be used on the templates and template parts
				$clinical_resource_profile_fields_vars = array();

				// Get the field values

					// Foo

						$foo = get_field( 'foo', $page_id ); // string

						$clinical_resource_profile_fields_vars['foo'] = isset($foo) ? $foo : ''; // Add to the variables array

					// Bar (taxonomy multi-select)

						$bar = get_field( 'bar', $page_id ); // int[]

						foreach ( $bar as $item ) {

							$bar_array[$item] = array(
								'name'	=> get_term( $item, 'bar_term')->name // string // Term name
							);

						}

						$clinical_resource_profile_fields_vars['bar'] = isset($bar) ? $bar : ''; // Add to the variables array
						$clinical_resource_profile_fields_vars['bar_array'] = isset($bar_array) ? $bar_array : ''; // Add to the variables array

					// Baz (taxonomy select/radio/checkbox)

						$baz = get_field( 'baz', $page_id ); // string|int[] // Term ID(s)
						$baz = is_array($baz) ? $baz : array($baz); // int[] // Term ID(s)

						foreach ( $baz as $item ) {

							$baz_array[$item] = array(
								'name'	=> get_term( $item, 'baz_term')->name // string // Term name
							);

						}

						$clinical_resource_profile_fields_vars['baz'] = isset($baz) ? $baz : ''; // Add to the variables array
						$clinical_resource_profile_fields_vars['baz_array'] = isset($baz_array) ? $baz_array : ''; // Add to the variables array

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $page_id, // Required // String added to transient name for disambiguation.
					$clinical_resource_profile_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $clinical_resource_profile_fields_vars;

			}

		}

	// Clinical resource card field values

		function uamswp_fad_clinical_resource_card_fields(
			$page_id, // int // ID of the profile
			$clinical_resource_card_style = 'basic' // string enum('basic', 'detailed') // Clinical resource card style
		) {

			// Check optional variables
			$clinical_resource_card_style = ( 'basic' == $clinical_resource_card_style || 'detailed' == $clinical_resource_card_style ) ? $clinical_resource_card_style : 'basic';

			// Retrieve the value of the transient
			uamswp_fad_get_transient(
				'vars_' . $clinical_resource_card_style . '_' . $page_id, // Required // String added to transient name for disambiguation.
				$clinical_resource_card_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
				__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
			);

			if ( !empty( $clinical_resource_card_fields_vars ) ) {

				/* 
				 * The transient exists.
				 * Return the variable.
				 */

				return $clinical_resource_card_fields_vars;

			} else {

				/* 
				 * The transient does not exist.
				 * Define the variable again.
				 */

				// Create a variables array to be used on the templates and template parts
				$clinical_resource_card_fields_vars = array();

				// Get the field values

					// Common

						// Title

							$clinical_resource_title = get_the_title($page_id);
							$clinical_resource_title_attr = uamswp_attr_conversion($clinical_resource_title);

							// Add to the variables array

								$clinical_resource_card_fields_vars['clinical_resource_title'] = isset($clinical_resource_title) ? $clinical_resource_title : '';
								$clinical_resource_card_fields_vars['clinical_resource_title_attr'] = isset($clinical_resource_title_attr) ? $clinical_resource_title_attr : '';

						// Type

							$clinical_resource_type = get_field('clinical_resource_type', $page_id);
							$clinical_resource_type_value = $clinical_resource_type['value']; // value
							$clinical_resource_type_label = $clinical_resource_type['label']; // label

							// Add to the variables array
							$clinical_resource_card_fields_vars['clinical_resource_type_label'] = isset($clinical_resource_type_label) ? $clinical_resource_type_label : '';

						// Link Element

							// Build an array of resource type values (keys) with the corresponding link text (values)

								$clinical_resource_link_text_map = array(
									'text' => 'Read the Article',
									'infographic' => 'View the Infographic',
									'video' => 'Watch the Video',
									'doc' => 'Read the Document'
								);

							// Link text

								// Get system settings for clinical resource labels

									if ( !$clinical_resource_type_value ) {

										include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

									}

								$clinical_resource_link_text = ( $clinical_resource_type_value && $clinical_resource_link_text_map[$clinical_resource_type_value] ) ? $clinical_resource_link_text_map[$clinical_resource_type_value] : 'View the ' . $clinical_resource_single_name;

							// Link accessible label

								$clinical_resource_link_label = implode(
									', ',
									array(
										$clinical_resource_link_text,
										$clinical_resource_title
									)
								);
								$clinical_resource_link_label = uamswp_attr_conversion($clinical_resource_link_label);

							// Add to the variables array

								$clinical_resource_card_fields_vars['clinical_resource_link_text'] = isset($clinical_resource_link_text) ? $clinical_resource_link_text : '';
								$clinical_resource_card_fields_vars['clinical_resource_link_label'] = isset($clinical_resource_link_label) ? $clinical_resource_link_label : '';

						// Excerpt

							$clinical_resource_excerpt = get_field( 'clinical_resource_excerpt', $page_id ); // string
							$clinical_resource_excerpt = $clinical_resource_excerpt ?: get_the_excerpt($page_id); // string

							if ( !$clinical_resource_excerpt ) {

								$clinical_resource_content_map = array(
									'text' => get_field( 'clinical_resource_text', $page_id ),
									'infographic' => get_field( 'clinical_resource_infographic_descr', $page_id ),
									'video' => get_field( 'clinical_resource_video_descr', $page_id ),
									'doc' => get_field( 'clinical_resource_document_descr', $page_id )
								);

								$clinical_resource_content = ( $clinical_resource_type_value && $clinical_resource_content_map[$clinical_resource_type_value] ) ? $clinical_resource_content_map[$clinical_resource_type_value] : '';

							}
							$clinical_resource_excerpt = $clinical_resource_excerpt ?: wp_strip_all_tags( $clinical_resource_content ); // string
							$clinical_resource_excerpt = $clinical_resource_excerpt ?: ''; // string

							// Truncate the excerpt if it is greater than 160 characters

								if ( strlen($clinical_resource_excerpt) > 160 ) {

									$clinical_resource_excerpt = wp_trim_words( $clinical_resource_excerpt, 23, ' &hellip;' );

								}

							// Add to the variables array
							$clinical_resource_card_fields_vars['clinical_resource_excerpt'] = isset($clinical_resource_excerpt) ? $clinical_resource_excerpt : '';

						// Clinical Resource URL

							$clinical_resource_url = get_permalink($page_id);

							// Add to the variables array
							$clinical_resource_card_fields_vars['clinical_resource_url'] = isset($clinical_resource_url) ? $clinical_resource_url : array(); 

					// Clinical Resource Card Styles

						if ( 'basic' == $clinical_resource_card_style ) {

							// Featured image (wide)

								$clinical_resource_featured_image = get_field( '_thumbnail_id', $page_id ); // int

								if (
									$clinical_resource_featured_image
									&&
									function_exists( 'fly_add_image_size' )
								) {

									$clinical_resource_featured_image_srcset[] = array(
										'url'				=> image_sizer( $clinical_resource_featured_image, 455, 256, 'center', 'center' ),
										'media-min-width'	=> '1921px'
									);

									$clinical_resource_featured_image_srcset[] = array(
										'url'				=> image_sizer( $clinical_resource_featured_image, 433, 244, 'center', 'center' ),
										'media-min-width'	=> '1500px'
									);

									$clinical_resource_featured_image_srcset[] = array(
										'url'				=> image_sizer( $clinical_resource_featured_image, 455, 256, 'center', 'center' ),
										'media-min-width'	=> '992px'
									);

									$clinical_resource_featured_image_srcset[] = array(
										'url'				=> image_sizer( $clinical_resource_featured_image, 433, 244, 'center', 'center' ),
										'media-min-width'	=> '768px'
									);

									$clinical_resource_featured_image_srcset[] = array(
										'url'				=> image_sizer( $clinical_resource_featured_image, 455, 256, 'center', 'center' ),
										'media-min-width'	=> '1px'
									);

									$clinical_resource_featured_image_base_url = image_sizer( $clinical_resource_featured_image, 455, 256, 'center', 'center' );

								} elseif ( $clinical_resource_featured_image ) {

									$clinical_resource_featured_image_srcset = array();
									$clinical_resource_featured_image_base_url = wp_get_attachment_image_url( $clinical_resource_featured_image, 'aspect-16-9-small' );

								} else {

									$clinical_resource_featured_image_srcset = array();
									$clinical_resource_featured_image_base_url = '';

								}

								// Add to the variables array

									$clinical_resource_card_fields_vars['clinical_resource_featured_image_srcset'] = isset($clinical_resource_featured_image_srcset) ? $clinical_resource_featured_image_srcset : array(); 
									$clinical_resource_card_fields_vars['clinical_resource_featured_image_base_url'] = isset($clinical_resource_featured_image_base_url) ? $clinical_resource_featured_image_base_url : ''; 

						} elseif ( 'detailed' == $clinical_resource_card_style ) {

							// Featured image (wide and square)

								$clinical_resource_featured_image = get_field( '_thumbnail_id', $page_id ); // int
								$clinical_resource_featured_image_square = get_field( 'clinical_resource_image_square', $page_id ); // int

								if (
									$clinical_resource_featured_image
									&&
									function_exists( 'fly_add_image_size' )
								) {

									// srcset

										$clinical_resource_featured_image_srcset[] = array(
											'url'				=> image_sizer(
												( $clinical_resource_featured_image_square ?: $clinical_resource_featured_image ),
												243, 243,
												'bar', 'center'
											),
											'media-min-width'	=> '2054px'
										);

										$clinical_resource_featured_image_srcset[] = array(
											'url'				=> image_sizer(
												( $clinical_resource_featured_image_square ?: $clinical_resource_featured_image ),
												184, 184,
												'center', 'center'
											),
											'media-min-width'	=> '1784px'
										);

										$clinical_resource_featured_image_srcset[] = array(
											'url'				=> image_sizer(
												( $clinical_resource_featured_image_square ?: $clinical_resource_featured_image ),
												243, 243,
												'center', 'center'
											),
											'media-min-width'	=> '1200px'
										);

										$clinical_resource_featured_image_srcset[] = array(
											'url'				=> image_sizer(
												( $clinical_resource_featured_image_square ?: $clinical_resource_featured_image ),
												184, 184,
												'center', 'center'
											),
											'media-min-width'	=> '930px'
										);

										$clinical_resource_featured_image_srcset[] = array(
											'url'				=> image_sizer(
												$clinical_resource_featured_image,
												580, 326,
												'center', 'center'
											),
											'media-min-width'	=> '768px'
										);

										$clinical_resource_featured_image_srcset[] = array(
											'url'				=> image_sizer(
												( $clinical_resource_featured_image_square ?: $clinical_resource_featured_image ),
												95, 95,
												'center', 'center'
											),
											'media-min-width'	=> '576px'
										);

										$clinical_resource_featured_image_srcset[] = array(
											'url'				=> image_sizer(
												$clinical_resource_featured_image,
												510, 286,
												'center', 'center'
											),
											'media-min-width'	=> '1px'
										);

									// Base image

										$clinical_resource_featured_image_base_url = image_sizer(
											$clinical_resource_featured_image,
											510, 286,
											'center', 'center'
										);

								} elseif ( $clinical_resource_featured_image ) {

									$clinical_resource_featured_image_srcset = array();
									$clinical_resource_featured_image_base_url = wp_get_attachment_image_url( $clinical_resource_featured_image, 'aspect-16-9-small' );

								} else {

									$clinical_resource_featured_image_srcset = array();
									$clinical_resource_featured_image_base_url = '';

								}

								// Add to the variables array

									$clinical_resource_card_fields_vars['clinical_resource_featured_image_srcset'] = isset($clinical_resource_featured_image_srcset) ? $clinical_resource_featured_image_srcset : array(); 
									$clinical_resource_card_fields_vars['clinical_resource_featured_image_base_url'] = isset($clinical_resource_featured_image_base_url) ? $clinical_resource_featured_image_base_url : ''; 

							// Related content

								// Providers

									// Get IDs of related providers
									$clinical_resource_providers = get_field( 'clinical_resource_providers', $page_id ) ?: array();

									// Construct the list of related providers

										$clinical_resource_provider_list = uamswp_fad_related_list(
											$page_id, // int // ID of the current ontology item
											$clinical_resource_title, // string // Attribute-friendly title of the current ontology item
											$clinical_resource_providers, // int[] // List of related ontology item IDs
											'provider' // string (optional) // Post type of the related ontology items
										);

									// Define the list label

										if ( $clinical_resource_provider_list ) {

											// Get system settings for provider labels
											include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

											$clinical_resource_provider_label = count($clinical_resource_providers) > 1 ? $provider_plural_name : $provider_single_name;

										}

									// Add to the variables array

										$clinical_resource_card_fields_vars['clinical_resource_provider_label'] = isset($clinical_resource_provider_label) ? $clinical_resource_provider_label : array(); 
										$clinical_resource_card_fields_vars['clinical_resource_provider_list'] = isset($clinical_resource_provider_list) ? $clinical_resource_provider_list : array(); 

								// Locations

									// Get IDs of related locations
									$clinical_resource_locations = get_field( 'clinical_resource_locations', $page_id ) ?: array();

									// Construct the list of related locations

										$clinical_resource_location_list = uamswp_fad_related_list(
											$page_id, // int // ID of the current ontology item
											$clinical_resource_title, // string // Attribute-friendly title of the current ontology item
											$clinical_resource_locations, // int[] // List of related ontology item IDs
											'location' // string (optional) // Post type of the related ontology items
										);

									// Define the list label

										if ( $clinical_resource_location_list ) {

											// Get system settings for location labels
											include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

											$clinical_resource_location_label = count($clinical_resource_locations) > 1 ? $location_plural_name : $location_single_name;

										}

									// Add to the variables array

										$clinical_resource_card_fields_vars['clinical_resource_location_label'] = isset($clinical_resource_location_label) ? $clinical_resource_location_label : array(); 
										$clinical_resource_card_fields_vars['clinical_resource_location_list'] = isset($clinical_resource_location_list) ? $clinical_resource_location_list : array(); 

								// Areas of Expertise

									// Get IDs of related areas of expertise
									$clinical_resource_expertises = get_field( 'clinical_resource_aoe', $page_id ) ?: array();

									// Construct the list of related expertises

										$clinical_resource_expertise_list = uamswp_fad_related_list(
											$page_id, // int // ID of the current ontology item
											$clinical_resource_title, // string // Attribute-friendly title of the current ontology item
											$clinical_resource_expertises, // int[] // List of related ontology item IDs
											'expertise' // string (optional) // Post type of the related ontology items
										);

									// Define the list label

										if ( $clinical_resource_expertise_list ) {

											// Get system settings for expertise labels
											include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

											$clinical_resource_expertise_label = count($clinical_resource_expertises) > 1 ? $expertise_plural_name : $expertise_single_name;

										}

									// Add to the variables array

										$clinical_resource_card_fields_vars['clinical_resource_expertise_label'] = isset($clinical_resource_expertise_label) ? $clinical_resource_expertise_label : array(); 
										$clinical_resource_card_fields_vars['clinical_resource_expertise_list'] = isset($clinical_resource_expertise_list) ? $clinical_resource_expertise_list : array(); 

								// Conditions

									// Get IDs of related conditions
									$clinical_resource_conditions = get_field( 'clinical_resource_conditions', $page_id ) ?: array();

									// Construct the list of related conditions

										$clinical_resource_condition_list = uamswp_fad_related_list(
											$page_id, // int // ID of the current ontology item
											$clinical_resource_title, // string // Attribute-friendly title of the current ontology item
											$clinical_resource_conditions, // int[] // List of related ontology item IDs
											'condition' // string (optional) // Post type of the related ontology items
										);

									// Define the list label

										if ( $clinical_resource_condition_list ) {

											// Get system settings for condition labels
											include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition.php' );

											$clinical_resource_condition_label = count($clinical_resource_conditions) > 1 ? $condition_plural_name : $condition_single_name;

										}

									// Add to the variables array

										$clinical_resource_card_fields_vars['clinical_resource_condition_label'] = isset($clinical_resource_condition_label) ? $clinical_resource_condition_label : array(); 
										$clinical_resource_card_fields_vars['clinical_resource_condition_list'] = isset($clinical_resource_condition_list) ? $clinical_resource_condition_list : array(); 

								// Treatments

									// Get IDs of related treatments
									$clinical_resource_treatments = get_field( 'clinical_resource_treatments', $page_id ) ?: array();

									// Construct the list of related treatments

										$clinical_resource_treatment_list = uamswp_fad_related_list(
											$page_id, // int // ID of the current ontology item
											$clinical_resource_title, // string // Attribute-friendly title of the current ontology item
											$clinical_resource_treatments, // int[] // List of related ontology item IDs
											'treatment' // string (optional) // Post type of the related ontology items
										);

									// Define the list label

										if ( $clinical_resource_treatment_list ) {

											// Get system settings for treatment labels
											include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/treatment.php' );

											$clinical_resource_treatment_label = count($clinical_resource_treatments) > 1 ? $treatment_plural_name : $treatment_single_name;

										}

									// Add to the variables array

										$clinical_resource_card_fields_vars['clinical_resource_treatment_label'] = isset($clinical_resource_treatment_label) ? $clinical_resource_treatment_label : array(); 
										$clinical_resource_card_fields_vars['clinical_resource_treatment_list'] = isset($clinical_resource_treatment_list) ? $clinical_resource_treatment_list : array(); 

						}

				// Set/update the value of the transient
				uamswp_fad_set_transient(
					'vars_' . $clinical_resource_card_style . '_' . $page_id, // Required // String added to transient name for disambiguation.
					$clinical_resource_card_fields_vars, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
					__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
				);

				// Return the variable
				return $clinical_resource_card_fields_vars;

			}

		}

// Create telephone link element

	function uamswp_fad_create_telephone_link(
		$phone_number, // string
		$class = '', // string
		$data_category_title = '', // string // data-categorytitle attribute value
		$data_item_title = '', // string // data-itemtitle attribute value
		$data_type_title = '' // string // data-typetitle attribute value
	) {

		// Check/define optional variables

			$phone_number =  ( isset($phone_number) && !empty($phone_number) ) ? format_phone_dash( $phone_number ) : '';
			$class = $class ?: '';
			$data_category_title = $data_category_title ?: '';
			$data_item_title = $data_item_title ?: '';
			$data_type_title = $data_type_title ?: '';
			$output = '';

		if ( !$phone_number ) {

			return $output;

		}

		$output = '<a href="tel:' . $phone_number . '"';
		$output .= $class ? ' class="' . $class . '"' : '';
		$output .= $data_category_title ? ' data-categorytitle="' . $data_category_title . '"' : '';
		$output .= $data_item_title ? ' data-itemtitle="' . $data_item_title . '"' : '';
		$output .= $data_type_title ? ' data-typetitle="' . $data_type_title . '"' : '';
		$output .= '>';
		$output .= $phone_number;
		$output .= '</a>';

		return $output;

	}


// Flatten single-row multi-dimensional array by one step

	function uamswp_fad_flatten_multidimensional_array(&$input) {

		if (
			$input
			&&
			is_array($input)
			&&
			array_is_list($input)
			&&
			count($input) == 1
		) {

			$input = reset($input);

		}

	}

// Get the info from a YouTube video

	function uamswp_fad_youtube_info(
		string $url // YouTube video URL
	){

		/* 
		 * Requires YouTube Lyte plugin
		 * 
		 * Source: https://github.com/futtta/wp-youtube-lyte/blob/main/wp-youtube-lyte.php
		 * 
		 * Additional information: https://developers.google.com/youtube/v3/docs/videos
		 */

		if ( function_exists('lyte_get_YT_resp') ) {

			// Get video ID

				parse_str( // Parses the string into variables
					parse_url( // Parse a URL and return its components
						$url,
						PHP_URL_QUERY // Outputs the query string of the URL parsed
					),
					$arr
				);

				$video_id = $arr['v'];

			// Get video info from cache or get it from YouTube and set it

				$data = lyte_get_YT_resp( $video_id, false, '_lyte_' . $video_id ); // Get video info from cache or get it from YouTube and set it

			if (
				!isset($data)
				||
				empty($data)
			) {

				return false;

			}

			// Add ID to data

				$data['id'] = $video_id;

			return $data;

		} else {

			return false;

		}

	}

// Convert seconds to ISO 8601 duration format

	function uamswp_fad_iso8601_duration( int $seconds ) {

		/* Source: https://stackoverflow.com/a/13301472 */

		$units = array(
			'Y' => 365*24*3600,
			'D' =>     24*3600,
			'H' =>        3600,
			'M' =>          60,
			'S' =>           1,
		);

		$str = 'P';
		$istime = false;

		foreach ( $units as $unitName => &$unit ) {

			$quot = intval($seconds / $unit);
			$seconds -= $quot * $unit;
			$unit = $quot;

			if ( $unit > 0 ) {

				if (
					!$istime
					&&
					in_array( $unitName, array('H', 'M', 'S') )
				) { // There may be a better way to do this

					$str .= 'T';
					$istime = true;

				}

				$str .= strval($unit) . $unitName;

			}

		}

		return $str;

	}

// Generate all combinations from a multi-dimensional array

	function uamswp_fad_combinations(
		array $input, // array // Required // Multi-dimensional array with choice lists nested in arrays
		int $i = 0 // int // Option // Iteration counter
	) {

		/*

			Source: https://stackoverflow.com/a/8567199

			Expected format of $input:

				$input = array(
					array(
						'Leonard',
						'L.',
						''
					),
					array(
						'Horatio',
						'H.',
						''
					),
					array(
						'\'Bones\'',
						''
					)
				);

		*/

		// Check if the indicated sub-array exists before continuing

			if ( !isset($input[$i]) ) {

				return array();

			}

		// If there is only one sub-array, return that sub-array

			if ( $i == count($input) - 1 ) {

				return $input[$i];

			}

		// Get combinations from subsequent arrays

			// Create a temporary array that equals the next sub-array

				$next_array = uamswp_fad_combinations($input, $i + 1);

			// Base array // Used in first iteration

				$result = array();

			// concat each array from $temporary_array with each element from $arrays[$i]

				foreach (
					$input[$i] // Current sub-array
					as
					$current_array_value // Value in current sub-array
				) {

					foreach (
						$next_array // Next sub-array
						as
						$next_array_value // Value in next sub-array
					) {

						if (
							is_array($next_array_value)
						) {

							$result_value = array_merge(
								array($current_array_value),
								$next_array_value
							);

						} else {

							$result_value = array(
								$current_array_value,
								$next_array_value
							);

						}

						// Clean up value

							$result_value = array_filter($result_value);
							$result_value = array_values($result_value);

						$result[] = $result_value;

					}

				}

		// Clean up result

			$result = array_filter($result);
			$result = array_values($result);

		return $result;

	}
