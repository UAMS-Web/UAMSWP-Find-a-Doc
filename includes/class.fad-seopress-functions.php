<?php
/*
 * SEOPress Functions
 */

// Override the theme's method of defining the meta title

	// Variable definition function
	// Call before setting $meta_title on template
	function uamswp_fad_meta_title_vars(
		$page_title, // string
		$page_title_attr = '', // string (optional)
		$meta_title_base_addition = '', // string (optional) // Word or phrase to use to form base meta title // Defaults to $page_title_attr
		$meta_title_base_order = '', // array (optional) // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number
		$meta_title_enhanced_addition = '', // string (optional) // Word or phrase to inject into base meta title to form enhanced meta title level 1
		$meta_title_enhanced_order = '', // array (optional) // Pre-defined array for name order of enhanced meta title level 1 // Expects two values but will accommodate any number
		$meta_title_enhanced_x2_addition = '', // string (optional) // Second word or phrase to inject into base meta title to form enhanced meta title level 2
		$meta_title_enhanced_x2_order = '', // array (optional) // Pre-defined array for name order of enhanced meta title level 2 // Expects three values but will accommodate any number
		$meta_title_enhanced_x3_addition = '', // string (optional) // Third word or phrase to inject into base meta title to form enhanced meta title level 3
		$meta_title_enhanced_x3_order = '' // array (optional) // Pre-defined array for name order of enhanced meta title level 3 // Expects four values but will accommodate any number
	) {

		// Check/define variables

			// Page title
			$page_title = ( isset($page_title) && !empty($page_title) ) ? $page_title : get_the_title();
			$page_title_attr = ( isset($page_title_attr) && !empty($page_title_attr) ) ? $page_title_attr : ( function_exists('uamswp_attr_conversion') ? uamswp_attr_conversion($page_title) : $page_title);

			// Array for page titles and section titles
			$page_titles = array(
				'page_title'	=> $page_title
			);

			// Meta title
			$meta_title_base_addition = ( isset($meta_title_base_addition) && !empty($meta_title_base_addition) ) ? $meta_title_base_addition : $page_title_attr;
			$meta_title_enhanced_addition = ( isset($meta_title_enhanced_addition) && !empty($meta_title_enhanced_addition) ) ? $meta_title_enhanced_addition : '';
			$meta_title_enhanced_x2_addition = ( isset($meta_title_enhanced_x2_addition) && !empty($meta_title_enhanced_x2_addition) ) ? $meta_title_enhanced_x2_addition : '';
			$meta_title_enhanced_x3_addition = ( isset($meta_title_enhanced_x3_addition) && !empty($meta_title_enhanced_x3_addition) ) ? $meta_title_enhanced_x3_addition : '';
			$meta_title_base_order = ( isset($meta_title_base_order) && !empty($meta_title_base_order) ) ? $meta_title_base_order : array( $meta_title_base_addition );
			$meta_title_enhanced_order = ( isset($meta_title_enhanced_order) && !empty($meta_title_enhanced_order) ) ? $meta_title_enhanced_order : array( $meta_title_base_addition, $meta_title_enhanced_addition );
			$meta_title_enhanced_x2_order = ( isset($meta_title_enhanced_x2_order) && !empty($meta_title_enhanced_x2_order) ) ? $meta_title_enhanced_x2_order : array( $meta_title_base_addition, $meta_title_enhanced_addition, $meta_title_enhanced_x2_addition );
			$meta_title_enhanced_x3_order = ( isset($meta_title_enhanced_x3_order) && !empty($meta_title_enhanced_x3_order) ) ? $meta_title_enhanced_x3_order : array( $meta_title_base_addition, $meta_title_enhanced_addition, $meta_title_enhanced_x2_addition, $meta_title_enhanced_x3_addition );

			// Get the site title
			// "UAMS Health"
			$meta_title_site_name = get_bloginfo( "name" );

			// Set the maximum number of characters for the meta title
			// The recommended length for meta titles is 50-60 characters.
			$meta_title_chars_max = 60;

			// Set the string that separates components of the meta title
			$meta_title_separator = ' | ';

		// Construct base meta title value
		// Example: "Leonard H. McCoy, M.D. | UAMS Health"
		// Example: "Medical Oncology Clinic | UAMS Health"
		// Example: "Cancer Care | UAMS Health"
		// Example: "Breastfeeding Law and Policy | UAMS Health"
		if (
			$meta_title_base_addition
			&&
			$meta_title_base_order
		) {
			$meta_title_base_order_count = count($meta_title_base_order);
			$meta_title_base = '';
			$i = 1;
			foreach( $meta_title_base_order as $name ) {
				$meta_title_base .= $name . $meta_title_separator;
				if ( $i == $meta_title_base_order_count ) {
					$meta_title_base .= $meta_title_site_name;
				} else {
					$i++;
				}
			}

			// Set the base meta title value as the meta title
			$meta_title = $meta_title_base;
		} else {
			$meta_title_base = $meta_title_base_addition . $meta_title_separator . $meta_title_site_name;
		}

		// Set the base meta title as the meta title
		$meta_title = $meta_title_base;

		// Construct enhanced meta title level 1 value
		// Example: "Leonard H. McCoy, M.D. | Internist | UAMS Health"
		// Example: "Medical Oncology Clinic | Little Rock | UAMS Health"
		// Example: "Cancer Care | Area of Expertise | UAMS Health"
		// Example: "Breastfeeding Law and Policy | Clinical Resource | UAMS Health"
		if (
			$meta_title_enhanced_addition
			&&
			$meta_title_enhanced_order
		) {
			$meta_title_enhanced_order_count = count($meta_title_enhanced_order);
			$meta_title_enhanced = '';
			$i = 1;
			foreach( $meta_title_enhanced_order as $name ) {
				$meta_title_enhanced .= $name . $meta_title_separator;
				if ( $i == $meta_title_enhanced_order_count ) {
					$meta_title_enhanced .= $meta_title_site_name;
				} else {
					$i++;
				}
			}

			// If the enhanced meta title level 1 value is within the character limit, set it as the meta title
			// Otherwise, use the previously-defined value
			$meta_title = ( strlen($meta_title_enhanced) <= $meta_title_chars_max ) ? $meta_title_enhanced : $meta_title;
		}

		// Construct enhanced meta title level 2 value
		// Example: "Leonard H. McCoy, M.D. | Internist | Little Rock | UAMS Health"
		// Example: "Medical Oncology Clinic | Cancer Care | Little Rock | UAMS Health"
		// Example: "Breastfeeding Law and Policy | Video | Clinical Resource | UAMS Health"
		if (
			$meta_title_enhanced_addition
			&&
			$meta_title_enhanced_x2_addition
			&&
			$meta_title_enhanced_x2_order
		) {
			$meta_title_enhanced_x2_order_count = count($meta_title_enhanced_x2_order);
			$meta_title_enhanced_x2 = '';
			$i = 1;
			foreach( $meta_title_enhanced_x2_order as $name ) {
				$meta_title_enhanced_x2 .= $name . $meta_title_separator;
				if ( $i == $meta_title_enhanced_x2_order_count ) {
					$meta_title_enhanced_x2 .= $meta_title_site_name;
				} else {
					$i++;
				}
			}

			// If the enhanced meta title level 2 value is within the character limit, set it as the meta title
			// Otherwise, use the previously-defined value
			$meta_title = ( strlen($meta_title_enhanced_x2) <= $meta_title_chars_max ) ? $meta_title_enhanced_x2 : $meta_title;
		}

		// Construct enhanced meta title level 3 value
		// Example: "Leonard H. McCoy, M.D. | Internist | Primary Care | Little Rock | UAMS Health"
		if (
			$meta_title_enhanced_addition
			&&
			$meta_title_enhanced_x2_addition
			&&
			$meta_title_enhanced_x3_addition
			&&
			$meta_title_enhanced_x3_order
		) {
			$meta_title_enhanced_x3_order_count = count($meta_title_enhanced_x3_order);
			$meta_title_enhanced_x3 = '';
			$i = 1;
			foreach( $meta_title_enhanced_x3_order as $name ) {
				$meta_title_enhanced_x3 .= $name . $meta_title_separator;
				if ( $i == $meta_title_enhanced_x3_order_count ) {
					$meta_title_enhanced_x3 .= $meta_title_site_name;
				} else {
					$i++;
				}
			}

			// If the enhanced meta title level 3 value is within the character limit, set it as the meta title
			// Otherwise, use the previously-defined value
			$meta_title = ( strlen($meta_title_enhanced_x3) <= $meta_title_chars_max ) ? $meta_title_enhanced_x3 : $meta_title;
		}

		// Create and return an array to be used on the templates and template parts

			$meta_title_vars = array(
				'meta_title'	=> $meta_title, // string
			);
			return $meta_title_vars;

	}

	// Filter meta title
	// add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);
	function uamswp_fad_title($html) {
		// Bring in variables from outside of the function
		global $meta_title; // Defined in uamswp_fad_labels_clinical_resource()

		// Do stuff
		$html = $meta_title;

		return $html;
	}

// Override the theme's method of defining the meta tag values for Open Graph

	// Filter Open Graph type (og:type)
		// The type of your object (e.g., "video.movie"). Depending on the type you specify, other properties may also be required.
		// https://ogp.me/#types
	// Filter hook function for 'seopress_social_og_type'
	add_filter('seopress_social_og_type','uamswp_sp_social_og_type');
	function uamswp_sp_social_og_type($html) {
		// Bring in variables from outside of the function
		global $meta_og_type;
			// music.song
			global $meta_music_duration; // integer >=1 // The song's length in seconds.
			global $meta_music_album; // music.album array // The album this song is from.
			global $meta_music_album_disc; // integer >=1 // Which disc of the album this song is on.
			global $meta_music_album_track; // integer >=1 // Which track this song is.
			global $meta_music_musician; // profile array // The musician that made this song.
			// music.album
			global $meta_music_song; // music.song // The song on this album.
			global $meta_music_song_disc; // integer >=1 // The same as music:album:disc but in reverse.
			global $meta_music_song_track; // integer >=1 // The same as music:album:track but in reverse.
			global $meta_music_musician; // profile // The musician that made this song.
			global $meta_music_release_date; // datetime (ISO 8601) // The date the album was released.
			// music.playlist
			global $meta_music_song; // Identical to the ones on music.album
			global $meta_music_song_disc;
			global $meta_music_song_track;
			global $meta_music_creator; // profile // The creator of this playlist.
			// music.radio_station
			global $meta_music_creator; // profile // The creator of this station.
			// video.movie or video.episode
			global $meta_video_actor; // profile array // Actors in the movie or episode.
			global $meta_video_actor_role; // string // The role they played.
			global $meta_video_director; // profile array // Directors of the movie or episode.
			global $meta_video_writer; // profile array // Writers of the movie or episode.
			global $meta_video_duration; // integer >=1 // The movie or episode's length in seconds.
			global $meta_video_release_date; // datetime (ISO 8601) // The date the movie or episode was released.
			global $meta_video_tag; // string array // Tag words associated with this movie or episode.
			// video.episode
			global $meta_video_series; // video.tv_show // Which series this episode belongs to.
			// article
			global $meta_article_published_time; // datetime (ISO 8601) // When the article was first published.
			global $meta_article_modified_time; // datetime (ISO 8601) // When the article was last changed.
			global $meta_article_expiration_time; // datetime (ISO 8601) // When the article is out of date after.
			global $meta_article_author; // profile array // Writers of the article.
			global $meta_article_section; // string // A high-level section name. E.g. Technology
			global $meta_article_tag; // string array // Tag words associated with this article.
			// book
			global $meta_book_author; // profile array // Who wrote this book.
			global $meta_book_isbn; // string // The ISBN
			global $meta_book_release_date; // datetime (ISO 8601) // The date the book was released.
			global $meta_book_tag; // string array // Tag words associated with this book.
			// profile
			global $meta_profile_first_name; // string // A name normally given to an individual by a parent or self-chosen.
			global $meta_profile_last_name; // string // A name inherited from a family or marriage and by which the individual is commonly known.
			global $meta_profile_username; // string // A short unique string to identify them.
			global $meta_profile_gender; // enum(male, female) // Their gender.

		// Check/define variables
		$meta_og_type = ( isset($meta_og_type) && !empty($meta_og_type) ) ? $meta_og_type : 'website';

		// Do stuff
		if ( $meta_og_type ) {
			$html = '<meta name="og:type" content="' . $meta_og_type . '" />';

				if ( $meta_og_type == 'music:song' ) {
					$html .= $meta_music_duration ? '<meta name="music:duration" content="' . $meta_music_duration . '" />' : '';
					$html .= $meta_music_album ? '<meta name="music:album" content="' . $meta_music_album . '" />' : '';
					$html .= $meta_music_album_disc ? '<meta name="music:album:disc" content="' . $meta_music_album_disc . '" />' : '';
					$html .= $meta_music_album_track ? '<meta name="music:album:track" content="' . $meta_music_album_track . '" />' : '';
					$html .= $meta_music_musician ? '<meta name="music:musician" content="' . $meta_music_musician . '" />' : '';
				}
				if ( $meta_og_type == 'music:album' ) {
					$html .= $meta_music_song ? '<meta name="music:song" content="' . $meta_music_song . '" />' : '';
					$html .= $meta_music_song_disc ? '<meta name="music:song:disc" content="' . $meta_music_song_disc . '" />' : '';
					$html .= $meta_music_song_track ? '<meta name="music:song:track" content="' . $meta_music_song_track . '" />' : '';
					$html .= $meta_music_musician ? '<meta name="music:musician" content="' . $meta_music_musician . '" />' : '';
					$html .= $meta_music_release_date ? '<meta name="music:release_date" content="' . $meta_music_release_date . '" />' : '';
				}
				if ( $meta_og_type == 'music:playlist' ) {
					$html .= $meta_music_song ? '<meta name="music:song" content="' . $meta_music_song . '" />' : '';
					$html .= $meta_music_song_disc ? '<meta name="music:song:disc" content="' . $meta_music_song_disc . '" />' : '';
					$html .= $meta_music_song_track ? '<meta name="music:song:track" content="' . $meta_music_song_track . '" />' : '';
					$html .= $meta_music_creator ? '<meta name="music:creator" content="' . $meta_music_creator . '" />' : '';
				}
				if ( $meta_og_type == 'music_radio:station' ) {
					$html .= $meta_music_creator ? '<meta name="music:creator" content="' . $meta_music_creator . '" />' : '';
				}
				if ( $meta_og_type == 'video:movie' ) {
					$html .= $meta_video_actor ? '<meta name="video:actor" content="' . $meta_video_actor . '" />' : '';
					$html .= $meta_video_actor_role ? '<meta name="video:actor:role" content="' . $meta_video_actor_role . '" />' : '';
					$html .= $meta_video_director ? '<meta name="video:director" content="' . $meta_video_director . '" />' : '';
					$html .= $meta_video_writer ? '<meta name="video:writer" content="' . $meta_video_writer . '" />' : '';
					$html .= $meta_video_duration ? '<meta name="video:duration" content="' . $meta_video_duration . '" />' : '';
					$html .= $meta_video_release_date ? '<meta name="video:release_date" content="' . $meta_video_release_date . '" />' : '';
					$html .= $meta_video_tag ? '<meta name="video:tag" content="' . $meta_video_tag . '" />' : '';
				}
				if ( $meta_og_type == 'video:episode' ) {
					$html .= $meta_video_actor ? '<meta name="video:actor" content="' . $meta_video_actor . '" />' : '';
					$html .= $meta_video_actor_role ? '<meta name="video:actor:role" content="' . $meta_video_actor_role . '" />' : '';
					$html .= $meta_video_director ? '<meta name="video:director" content="' . $meta_video_director . '" />' : '';
					$html .= $meta_video_writer ? '<meta name="video:writer" content="' . $meta_video_writer . '" />' : '';
					$html .= $meta_video_duration ? '<meta name="video:duration" content="' . $meta_video_duration . '" />' : '';
					$html .= $meta_video_release_date ? '<meta name="video:release_date" content="' . $meta_video_release_date . '" />' : '';
					$html .= $meta_video_tag ? '<meta name="video:tag" content="' . $meta_video_tag . '" />' : '';
					$html .= $meta_video_series ? '<meta name="video:series" content="' . $meta_video_series . '" />' : '';
				}
				if ( $meta_og_type == 'article' ) {
					$html .= $meta_article_published_time ? '<meta name="article:published_time" content="' . $meta_article_published_time . '" />' : '';
					$html .= $meta_article_modified_time ? '<meta name="article:modified_time" content="' . $meta_article_modified_time . '" />' : '';
					$html .= $meta_article_expiration_time ? '<meta name="article:expiration_time" content="' . $meta_article_expiration_time . '" />' : '';
					$html .= $meta_article_author ? '<meta name="article:author" content="' . $meta_article_author . '" />' : '';
					$html .= $meta_article_section ? '<meta name="article:section" content="' . $meta_article_section . '" />' : '';
					$html .= $meta_article_tag ? '<meta name="article:tag" content="' . $meta_article_tag . '" />' : '';
				}
				if ( $meta_og_type == 'book' ) {
					$html .= $meta_book_author ? '<meta name="book:author" content="' . $meta_book_author . '" />' : '';
					$html .= $meta_book_isbn ? '<meta name="book:isbn" content="' . $meta_book_isbn . '" />' : '';
					$html .= $meta_book_release_date ? '<meta name="book:release_date" content="' . $meta_book_release_date . '" />' : '';
					$html .= $meta_book_tag ? '<meta name="book:tag" content="' . $meta_book_tag . '" />' : '';
				}
				if ( $meta_og_type == 'profile' ) {
					$html .= $meta_profile_first_name ? '<meta name="profile:first:name" content="' . $meta_profile_first_name . '" />' : '';
					$html .= $meta_profile_last_name ? '<meta name="profile:last:name" content="' . $meta_profile_last_name . '" />' : '';
					$html .= $meta_profile_username ? '<meta name="profile:username" content="' . $meta_profile_username . '" />' : '';
					$html .= $meta_profile_gender ? '<meta name="profile:gender" content="' . $meta_profile_gender . '" />' : '';
				}
		}

		return $html;
	}

	// Filter Open Graph updated time meta (og:updated_time)
	// Filter hook function for 'seopress_titles_og_updated_time'
	// add_filter('seopress_titles_og_updated_time', 'uamswp_sp_titles_og_updated_time');
	function uamswp_sp_titles_og_updated_time($html) { 
		// Bring in variables from outside of the function
		global $meta_og_updated_time;

		// Do stuff
		// Example of content value formatting: "2019-05-06T19:18:27+00:00"
		if ( $meta_og_update_time ) {
			$html = '<meta name="og:updated_time" content="' . $meta_og_updated_time . '" />';
		}

		return $html;
	}

	// Filter Open Graph Author (article:author) / Publisher (article:publisher)
		// Covered in the filter hook function for 'seopress_social_og_type'
	// Filter hook function for 'seopress_social_og_author'
	// add_filter('seopress_social_og_author', 'uamswp_sp_social_og_author');
	function uamswp_sp_social_og_author($html) { 
		// Bring in variables from outside of the function
		global $meta_article_author; // profile array // Writers of the article.
		global $meta_article_publisher; // profile array // Publishers of the article.

		// Do stuff
		if ( $meta_article_author ) {
			$html_meta_article_author = '<meta name="article:author" content="' . $meta_article_author . '" />';
		}
		if ( $meta_article_publisher ) {
			$html_meta_article_publisher = '<meta name="article:publisher" content="' . $meta_article_publisher . '" />';
		}
		$html = $html_meta_article_author . $html_meta_article_publisher;

		return $html;
	}

	// Filter locale Open Graph (og:locale)
		// The locale these tags are marked up in.
		// Of the format language_TERRITORY.
		// Default is en_US.
	// Filter hook function for 'seopress_social_og_locale'
	// add_filter('seopress_social_og_locale', 'uamswp_sp_social_og_locale');
	function uamswp_sp_social_og_locale($html) { 
		// Bring in variables from outside of the function
		global $meta_og_locale;

		// Check/define variables
		$meta_og_locale = ( isset($meta_og_locale) && !empty($meta_og_locale) ) ? $meta_og_locale : 'en_US';

		// Do stuff
		if ( $meta_og_locale ) {
			$html = '<meta name="og:locale" content="' . $meta_og_locale . '" />';
		}

		return $html;
	}

	// Filter Open Graph URL (og:url)
		// The canonical URL of your object that will be used as its permanent ID in the graph (e.g., "https://www.imdb.com/title/tt0117500/").
	// Filter hook function for 'seopress_social_og_url'
	// add_filter('seopress_social_og_url', 'uamswp_sp_social_og_url');
	function uamswp_sp_social_og_url($html) {
		// Bring in variables from outside of the function
		global $meta_og_url;

		// Do stuff
		if ( $meta_og_url ) {
			$html = '<meta name="og:url" content="' . $meta_og_url . '" />';
		}

		return $html;
	}

	// Filter Open Graph title (og:title)
		// The title of your object as it should appear within the graph (e.g., "The Rock").
	// Filter hook function for 'seopress_social_og_title'
	// add_filter('seopress_social_og_title', 'uamswp_sp_social_og_title');
	function uamswp_sp_social_og_title($html) { 
		// Bring in variables from outside of the function
		global $meta_og_title;

		// Do stuff
		if ( $meta_og_title ) {
			$html = '<meta name="og:title" content="' . $meta_og_title . '" />';
		}

		return $html;
	}

	// Filter Open Graph thumbnail (og:image)
		// An image URL which should represent your object within the graph.
	// Also "og:image:width" and "og:image:height"
	// Filter hook function for 'seopress_social_og_thumb'
	// add_filter('seopress_social_og_thumb', 'uamswp_sp_social_og_thumb');
	function uamswp_sp_social_og_thumb($html) { 
		// Bring in variables from outside of the function
		global $meta_og_image;
		global $meta_og_image_width;
		global $meta_og_image_height;

		// Do stuff
		if ( $meta_og_image ) {
			$html = '<meta property="og:image" content="' . $meta_og_image . '" />';

			if ( $meta_og_image_width && $meta_og_image_height) {
				$html .= '<meta property="og:image:width" content="' . $meta_og_image_width . '" />';
				$html .= '<meta property="og:image:height" content="' . $meta_og_image_height . '" />';
			}
		}

		return $html;
	}

	// Filter Open Graph sitename (og:site_name)
		// If your object is part of a larger web site, the name which should be displayed for the overall site (e.g., "IMDb").
	// Filter hook function for 'seopress_social_og_site_name'
	// add_filter('seopress_social_og_site_name', 'uamswp_sp_social_og_site_name');
	function uamswp_sp_social_og_site_name($html) { 
		// Bring in variables from outside of the function
		global $meta_og_site_name;

		// Do stuff
		if ( $meta_og_site_name ) {
			$html = '<meta name="og:site_name" content="' . $meta_og_site_name . '" />';
		}

		return $html;
	}

	// Filter Open Graph description (og:description)
		// A one to two sentence description of your object.
	// Filter hook function for 'seopress_social_og_desc'
	// add_filter('seopress_social_og_desc', 'uamswp_sp_social_og_desc');
	function uamswp_sp_social_og_desc($html) { 
		// Bring in variables from outside of the function
		global $meta_og_description;

		// Do stuff
		if ( $meta_og_description ) {
			$html = '<meta name="og:description" content="' . $meta_og_description . '" />';
		}

		return $html;
	}

// Override the theme's method of defining the meta tag values for Oembed

	// Filter title used in Oembed
	// Filter hook function for 'seopress_oembed_title'
	// add_filter('seopress_oembed_title','uamswp_sp_oembed_title');
	function uamswp_sp_oembed_title($title) {
		// Bring in variables from outside of the function
		global $meta_oembed_title;

		// Do stuff
		// default title: Custom Open Graph Title set from SEOPress metabox, else post title
		if ( $meta_oembed_title ) {
			$title = $meta_oembed_title;
		}

		return $title;
	}

	// Filter post thumbnail size used in Oembed
	// Filter hook function for 'seopress_oembed_thumbnail_size'
	// add_filter('seopress_oembed_thumbnail_size','uamswp_sp_oembed_thumbnail_size');
	function uamswp_sp_oembed_thumbnail_size($size) {
		// Bring in variables from outside of the function
		global $meta_oembed_thumbnail_size;

		// Check/define variables
		$meta_oembed_thumbnail_size = ( isset($meta_oembed_thumbnail_size) && !empty($meta_oembed_thumbnail_size) ) ? $meta_oembed_thumbnail_size : 'full';

		// Do stuff
		if ( $meta_oembed_thumbnail_size ) {
			$size = $meta_oembed_thumbnail_size;
		}

		return $size;
	}

	// Filter post thumbnail in Oembed
	// Filter hook function for 'seopress_oembed_thumbnail'
	// add_filter('seopress_oembed_thumbnail','uamswp_sp_oembed_thumbnail');
	function uamswp_sp_oembed_thumbnail($thumbnail) {
		// Bring in variables from outside of the function
		global $meta_oembed_thumbnail;
		global $meta_oembed_thumbnail_width;
		global $meta_oembed_thumbnail_height;

		// Do stuff
		// Example of value formatting: ['url' => 'https://example.com/my-post-thumbnail-url', 'width' => '1920', 'height' => '1080']
		if ( $meta_oembed_thumbnail ) {
			$thumbnail = array( 'url' => $meta_oembed_thumbnail );

			if ( $meta_oembed_thumbnail_width && $meta_oembed_thumbnail_height ) {
				$thumbnail['width'] = $meta_oembed_thumbnail_width;
				$thumbnail['height'] = $meta_oembed_thumbnail_height;
			}
		}

		return $thumbnail;
	}

// Override the theme's method of defining the Twitter Card summary (twitter:card)

	// Filter Twitter Card summary (twitter:card)
		// The card type
		// Used with all cards
	// Filter hook function for 'seopress_social_twitter_card_summary'
	add_filter('seopress_social_twitter_card_summary', 'uamswp_sp_social_twitter_card_summary');
	function uamswp_sp_social_twitter_card_summary($html) { 
		// Bring in variables from outside of the function
		global $meta_twitter_card_content;

		// Check/define variables
		$meta_twitter_card_content = ( isset($meta_twitter_card_content) && !empty($meta_twitter_card_content) ) ? $meta_twitter_card_content : 'summary_large_image';

		// Do stuff
		// Options for content value: "summary", "summary_large_image", "app", or "player".
		if ( $meta_twitter_card_content ) {
			$html = '<meta name="twitter:card" content="' . $meta_twitter_card_content . '" />';
		}

		return $html;
	}

	// Filter Twitter Card site (twitter:site)
		// @username of website. Either twitter:site or twitter:site:id is required.
		// Used with summary, summary_large_image, app, player cards (twitter:card)
	// Filter hook function for 'seopress_social_twitter_card_site'
	// add_filter('seopress_social_twitter_card_site', 'uamswp_sp_social_twitter_card_site');
	function uamswp_sp_social_twitter_card_site($html) { 
		// Bring in variables from outside of the function
		global $meta_twitter_site;

		// Do stuff
		if ( $meta_twitter_site ) {
			$html = '<meta name="twitter:site" content="' . $meta_twitter_site . '" />';
		}

		return $html;
	}

	// Filter Twitter Card creator (twitter:creator)
		// @username of content creator
		// Used with summary_large_image cards (twitter:card)
	// Filter hook function for 'seopress_social_twitter_card_creator'
	// add_filter('seopress_social_twitter_card_creator', 'uamswp_sp_social_twitter_card_creator');
	function uamswp_sp_social_twitter_card_creator($html) { 
		// Bring in variables from outside of the function
		global $meta_twitter_creator;

		// Do stuff
		if ( $meta_twitter_creator ) {
			$html = '<meta name="twitter:creator" content="' . $meta_twitter_creator . '" />';
		}

		return $html;
	}

	// Filter Twitter Card description (twitter:description)
		// Description of content (maximum 200 characters)
		// Used with summary, summary_large_image, player cards (twitter:card)
	// Filter hook function for 'seopress_social_twitter_card_desc'
	// add_filter('seopress_social_twitter_card_desc', 'uamswp_sp_social_twitter_card_desc');
	function uamswp_sp_social_twitter_card_desc($html) { 
		// Bring in variables from outside of the function
		global $meta_twitter_description;

		// Do stuff
		if ( $meta_twitter_description ) {
			$html = '<meta name="twitter:description" content="' . $meta_twitter_description . '" />'; 
		}

		return $html;
	}

	// Filter Twitter Card title (twitter:title)
		// Title of content (max 70 characters)
		// Used with summary, summary_large_image, player cards (twitter:card)
	// Filter hook function for 'seopress_social_twitter_card_title'
	// add_filter('seopress_social_twitter_card_title', 'uamswp_sp_social_twitter_card_title');
	function uamswp_sp_social_twitter_card_title($html) { 
		// Bring in variables from outside of the function
		global $meta_twitter_title;

		// Do stuff
		if ( $meta_twitter_title ) {
			$html = '<meta name="twitter:title" content="' . $meta_twitter_title . '" />'; 
		}

		return $html;
	}

	// Filter Twitter Card thumbnail (twitter:image)
		// URL of image to use in the card.
		// Images must be less than 5MB in size.
		// JPG, PNG, WEBP and GIF formats are supported. Only the first frame of an animated GIF will be used. SVG is not supported.
		// Used with summary, summary_large_image, player cards (twitter:card)
		// Formerly "twitter:image:src" (pre-2016)
	// Also Twitter Card thumbnail alternative text (twitter:image:alt)
		// A text description of the image conveying the essential nature of an image to users who are visually impaired.
		// Maximum 420 characters.
		// Used with summary, summary_large_image, player cards
	// Filter hook function for 'seopress_social_twitter_card_thumb'
	// add_filter('seopress_social_twitter_card_thumb', 'uamswp_sp_social_twitter_card_thumb');
	function uamswp_sp_social_twitter_card_thumb($html) { 
		// Bring in variables from outside of the function
		global $meta_twitter_image;
		global $meta_twitter_image_alt;

		// Do stuff
		if ( $meta_twitter_image ) {
			$html = '<meta name="twitter:image" content="' . $meta_twitter_image . '" />'; 

			if ($meta_twitter_image_alt) {
				$html .= '<meta name="twitter:image:alt" content="' . $meta_twitter_image_alt . '" />'; 
			}
		}

		return $html;
	}
