<?php
/*
 * Template Name: Social Media Meta Tags
 * 
 * Description: A template part that displays the Open Graph, twitter and Oembed 
 * meta tags in the head element
 * 
 * Optional vars:
 * 	$meta_og_type // string // Open Graph type
 * 	$meta_og_type_property // array // Open Graph type properties
 * 	$meta_og_updated_time // string // PHP date format DATE_ATOM
 * 	$meta_article_author // array // profile array - Writers of the article.
 * 	$meta_article_publisher // array // profile array - Publishers of the article.
 * 	$meta_og_locale // string // Locale // Of the format language_TERRITORY (en_US)
 * 	$meta_og_url // string // The canonical URL of your object
 * 	$meta_og_title // string // The title of your object as it should appear within the graph
 * 	$meta_og_image // string // An image URL which should represent your object within the graph
 * 	$meta_og_image_width // int // Width of the image in $meta_og_image
 * 	$meta_og_image_height // int // Height of the image in $meta_og_image
 * 	$meta_og_site_name // string // If your object is part of a larger web site, the name which should be displayed for the overall site
 * 	$meta_og_description // string // A one- to two-sentence description of your object.
 * 	$meta_oembed_title // string // The title of your object as it should appear within Oembed
 * 	$meta_oembed_thumbnail_size // string // Thumbnail size used in Oembed
 * 	$meta_oembed_thumbnail // string // An image URL which should represent your object within Oembed
 * 	$meta_oembed_thumbnail_width // int // Width of the image in $meta_oembed_thumbnail
 * 	$meta_oembed_thumbnail_height // int // Width of the image in $meta_oembed_thumbnail
 * 	$meta_twitter_card_type // string // Twitter card type
 * 	$meta_twitter_site // string // Twitter @username for the website used in the card footer.
 * 	$meta_twitter_creator // string // Twitter @username for the content creator / author.
 * 	$meta_twitter_description // string // Twitter card description
 * 	$meta_twitter_title // string // Twitter card title
 * 	$meta_twitter_image // string // An image URL which should represent your object within the graph
 * 	$meta_twitter_image_alt // string // Alt text of the image in $meta_twitter_image
 */

// Check/define variables

	// Get the featured image ID

		$featured_image = ( isset($featured_image) && !empty($featured_image) ) ? $featured_image : get_post_thumbnail_id(); // int // Featured image ID
		$featured_image = $featured_image ? $featured_image : '';

	// Set image values for Open Graph, Twitter and Oembed
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/image-elements/common/meta-image-values.php' );

// Filter Open Graph type (og:type) and properties for that type

	// Check/define variables

		$meta_og_type = isset($meta_og_type) ? $meta_og_type : '';
		$meta_og_type_property = isset($meta_og_type_property) && is_array($meta_og_type_property) ? $meta_og_type_property : array();
		$meta_og_namespace = isset($meta_og_namespace) && !empty($meta_og_namespace) ? $meta_og_namespace : '';

	add_filter( 'seopress_social_og_type', function( $html ) use (
		$meta_og_type,
		$meta_og_type_property,
		&$meta_og_namespace
	) {

		/* 
		 * Open Graph documentation: https://ogp.me/#types
		 * 
		 * Expected array format for vars:
		 * 
		 * 	$meta_og_type = 'profile';
		 * 	$meta_og_type_property = array(
		 * 		'profile:first_name' => 'Leonard',
		 * 		'profile:last_name' => 'McCoy',
		 * 		'profile:gender' => 'male',
		 * 	);
		 */

		if ( $meta_og_type ) {

			$html = '<meta property="og:type" content="' . $meta_og_type . '" />';

			if ( $meta_og_type_property ) {

				foreach ( $meta_og_type_property as $key => $value ) {

					if ( $key && $value ) {
						$html .= "\n" . '<meta property="og:' . $key . '" content="' . $value . '" />';
					}

				}

			}

		}

		return $html;

	} );

	// Add Open Graph namespace prefix to body element

		add_filter( 'genesis_attr_head', function( $attributes ) use ( $meta_og_type ) {

			if ( $meta_og_type ) {

				$meta_og_type_namespace = array(
					'music.song'			=> 'https://ogp.me/ns/music#',
					'music.album'			=> 'https://ogp.me/ns/music#',
					'music.playlist'		=> 'https://ogp.me/ns/music#',
					'music.radio_station'	=> 'https://ogp.me/ns/music#',
					'video.movie'			=> 'https://ogp.me/ns/video#',
					'video.episode'			=> 'https://ogp.me/ns/video#',
					'video.tv_show'			=> 'https://ogp.me/ns/video#',
					'video.other'			=> 'https://ogp.me/ns/video#',
					'article'				=> 'https://ogp.me/ns/article#',
					'book'					=> 'https://ogp.me/ns/book#',
					'profile'				=> 'https://ogp.me/ns/profile#',
					'website'				=> 'https://ogp.me/ns/website#'
				);

				$meta_og_namespace = 'og: http://ogp.me/ns# ' . $meta_og_type . ': ' . $meta_og_type_namespace[$meta_og_type];

				$attributes['prefix'] = isset($attributes['prefix']) ? $attributes['prefix'] : '';
				$attributes['prefix'] .= ( empty($attributes['prefix']) ? '' : ' ' ) . $meta_og_namespace;

			}

			return $attributes;

		} );

// Filter Open Graph updated time meta (og:updated_time)

	// Check/define variables
	$meta_og_updated_time = isset($meta_og_updated_time) ? $meta_og_updated_time : get_post_modified_time(DATE_ATOM);

	add_filter( 'seopress_titles_og_updated_time', function( $html ) use ( $meta_og_updated_time ) {

		/* 
		 * Open Graph documentation: https://ogp.me/#types
		 * 
		 * SEOPress hook documentation: https://www.seopress.org/support/hooks/filter-article-modified-time-meta/
		 * 
		 * Expected array format for var:
		 * 
		 * 	$meta_og_updated_time = '2019-05-06T19:18:27+00:00'; // PHP date format DATE_ATOM
		 */


		if ( $meta_og_updated_time ) {
			$html = '<meta property="og:updated_time" content="' . $meta_og_updated_time . '" />';
		}

		return $html;

	} );


// Filter Open Graph Author (article:author) / Publisher (article:publisher)

	// Check/define variables

		// Open Graph type // string
		$meta_og_type = ( isset($meta_og_type) ) ? $meta_og_type : '';

		// Writers of the article. // profile array

			$meta_article_author = ( isset($meta_article_author) && is_array($meta_article_author) ) ? $meta_article_author : array();
			$meta_article_author_content = '';
			$meta_article_author_count = 0;

			if ( $meta_article_author ) {

				$meta_article_author_count = count($meta_article_author);

				$i = 0;

				foreach ( $meta_article_author_content as $item ) {

					if (
						( isset($item['profile:first_name']) && !empty($item['profile:first_name']) )
						&&
						( isset($item['profile:last_name']) && !empty($item['profile:last_name']) )
					) {

						$meta_article_author_content .= $item['profile:first_name'] . ' ' . $item['profile:last_name'];
						$i++;
						$meta_article_author_content .= ( $i < $meta_article_author_count ) ? ', ' : '';

					}

				}

			}


		// Publishers of the article. // profile array

			$meta_article_publisher = ( isset($meta_article_publisher) && is_array($meta_article_publisher) ) ? $meta_article_publisher : array();
			$meta_article_publisher_content = '';
			$meta_article_publisher_count = 0;

			if ( $meta_article_publisher ) {

				$meta_article_publisher_count = count($meta_article_publisher);

				$i = 0;

				foreach ( $meta_article_publisher as $item ) {

					if (
						( isset($item['profile:first_name']) && !empty($item['profile:first_name']) )
						&&
						( isset($item['profile:last_name']) && !empty($item['profile:last_name']) )
					) {

						$meta_article_publisher_content .= $item['profile:first_name'] . ' ' . $item['profile:last_name'];
						$i++;
						$meta_article_publisher_content .= ( $i < $meta_article_publisher_count ) ? ', ' : '';

					}

				}

			}

	add_filter( 'seopress_social_og_author', function( $html ) use (
		$meta_og_type,
		$meta_article_author,
		$meta_article_publisher 
	) {

		/* 
		 * Open Graph documentation: https://ogp.me/#type_article
		 * 
		 * Expected array format for vars:
		 * 
		 * 	$meta_og_type = 'article';
		 * 	$meta_article_author = array(
		 * 		array(
		 * 			'profile:first_name' => 'Leonard',
		 * 			'profile:last_name' => 'McCoy',
		 * 		),
		 * 		array(
		 * 			'profile:first_name' => 'Christine',
		 * 			'profile:last_name' => 'Chapel',
		 * 		),
		 * 	);
		 */

			if (
				'article' == $meta_og_type
				&&
				(
					$meta_article_author_content
					||
					$meta_article_publisher_content
				)
			) {

				$html_meta_article_author = $meta_article_author_content ? '<meta property="og:article:author" content="' . $meta_article_author_content . '" />' : '';
				$html_meta_article_publisher = $meta_article_publisher_content ? '<meta property="og:article:publisher" content="' . $meta_article_publisher_content . '" />' : '';
				$html = $html_meta_article_author . $html_meta_article_publisher;

			}

			return $html;

	} );


// Filter Open Graph (og:locale)

	// Check/define variables
	$meta_og_locale = ( isset($meta_og_locale) && !empty($meta_og_locale) ) ? $meta_og_locale : 'en_US';

	add_filter( 'seopress_social_og_locale', function( $html ) use ( $meta_og_locale ) {

		/* 
		 * The locale these tags are marked up in.
		 * Of the format language_TERRITORY.
		 * Default is en_US.
		 */

		if ( $meta_og_locale ) {

			$html = '<meta property="og:locale" content="' . $meta_og_locale . '" />';

		}

		return $html;

	} );

// Filter Open Graph URL (og:url)

	// Check/define variables
	$meta_og_url = ( isset($meta_og_url) && !empty($meta_og_url) ) ? user_trailingslashit($meta_og_url) : '';

	add_filter( 'seopress_social_og_url', function( $html ) use ( $meta_og_url ) {

		/* 
		 * The canonical URL of your object that will be used as its permanent ID in the 
		 * graph (e.g., "https://www.imdb.com/title/tt0117500/").
		 */

		if ( $meta_og_url ) {

			$html = '<meta property="og:url" content="' . $meta_og_url . '" />';

		}

		return $html;

	} );

// Filter Open Graph title (og:title)

	// Check/define variables
	$meta_og_title = ( isset($meta_og_title) && !empty($meta_og_title) ) ? $meta_og_title : '';

	add_filter( 'seopress_social_og_title', function( $html ) use ( $meta_og_title ) {

		/* 
		 * The title of your object as it should appear within the graph (e.g., "The 
		 * Rock").
		 */

		if ( $meta_og_title ) {
			$html = '<meta property="og:title" content="' . $meta_og_title . '" />';
		}

		return $html;

	} );

// Filter Open Graph thumbnail (og:image), thumbnail width (og:image:width) and thumbnail height (og:image:height)

	// Check/define variables

		$meta_og_image = ( isset($meta_og_image) && !empty($meta_og_image) ) ? $meta_og_image : '';
		$meta_og_image_width = ( isset($meta_og_image_width) && !empty($meta_og_image_width) ) ? $meta_og_image_width : '';
		$meta_og_image_height = ( isset($meta_og_image_height) && !empty($meta_og_image_height) ) ? $meta_og_image_height : '';

	add_filter( 'seopress_social_og_thumb', function( $html ) use (
		$meta_og_image,
		$meta_og_image_width,
		$meta_og_image_height
	) {

		/* 
		 * An image URL which should represent your object within the graph.
		 * 
		 * Example of value formatting:
		 * 
		 * 	$meta_og_image = 'https://example.com/my-post-thumbnail-url';
		 * 	$meta_og_image_width = '1920';
		 * 	$meta_og_image_height = '1080';
		 */

		if ( $meta_og_image ) {
			$html = '<meta property="og:image" content="' . $meta_og_image . '" />';

			if (
				$meta_og_image_width
				&&
				$meta_og_image_height
			) {

				$html .= '<meta property="og:image:width" content="' . $meta_og_image_width . '" />';
				$html .= '<meta property="og:image:height" content="' . $meta_og_image_height . '" />';

			}
		}

		return $html;

	} );

// Filter Open Graph sitename (og:site_name)

	// Check/define variables
	$meta_og_site_name = ( isset($meta_og_site_name) && !empty($meta_og_site_name) ) ? $meta_og_site_name : '';

	add_filter( 'seopress_social_og_site_name', function( $html ) use ( $meta_og_site_name ) {

		/* 
		 * If your object is part of a larger web site, the name which should be displayed 
		 * for the overall site (e.g., "IMDb").
		 */

		if ( $meta_og_site_name ) {

			$html = '<meta property="og:site_name" content="' . $meta_og_site_name . '" />';

		}

		return $html;

	} );

// Filter Open Graph description (og:description)

	// Check/define variables
	$meta_og_description = ( isset($meta_og_description) && !empty($meta_og_description) ) ? $meta_og_description : '';

	add_filter( 'seopress_social_og_desc', function( $html ) use ( $meta_og_description ) {

		/* 
		 * A one- to two-sentence description of your object.
		 */

		if ( $meta_og_description ) {

			$html = '<meta property="og:description" content="' . $meta_og_description . '" />';

		}

		return $html;

	} );

// Filter title used in Oembed

	// Check/define variables
	$meta_oembed_title = ( isset($meta_oembed_title) && !empty($meta_oembed_title) ) ? $meta_oembed_title : '';

	add_filter( 'seopress_oembed_title', function( $title ) use ( $meta_oembed_title ) {

		/* 
		 * Override the theme's method of defining the meta tag values for Oembed
		 * 
		 * Default title: Custom Open Graph Title set from SEOPress metabox, else post title
		 */

		if ( $meta_oembed_title ) {

			$title = $meta_oembed_title;

		}

		return $title;

	} );

// Filter post thumbnail size used in Oembed

	// Check/define variables
	$meta_oembed_thumbnail_size = ( isset($meta_oembed_thumbnail_size) && !empty($meta_oembed_thumbnail_size) ) ? $meta_oembed_thumbnail_size : 'full';

	add_filter( 'seopress_oembed_thumbnail_size', function( $size ) use ( $meta_oembed_thumbnail_size ) {

		/*
		 * default size: full
		 */

		if ( $meta_oembed_thumbnail_size ) {

			$size = $meta_oembed_thumbnail_size;

		}

		return $size;

	} );

// Filter post thumbnail in Oembed

	// Check/define variables

		$meta_oembed_thumbnail = ( isset($meta_oembed_thumbnail) && !empty($meta_oembed_thumbnail) ) ? $meta_oembed_thumbnail : '';
		$meta_oembed_thumbnail_width = ( isset($meta_oembed_thumbnail_width) && !empty($meta_oembed_thumbnail_width) ) ? $meta_oembed_thumbnail_width : '';
		$meta_oembed_thumbnail_height = ( isset($meta_oembed_thumbnail_height) && !empty($meta_oembed_thumbnail_height) ) ? $meta_oembed_thumbnail_height : '';

	add_filter( 'seopress_oembed_thumbnail', function( $thumbnail ) use (
		$meta_oembed_thumbnail,
		$meta_oembed_thumbnail_width,
		$meta_oembed_thumbnail_height
	) {

		/* 
		 * Example of value formatting:
		 * 
		 * 	$meta_oembed_thumbnail = 'https://example.com/my-post-thumbnail-url';
		 * 	$meta_oembed_thumbnail_width = '1920';
		 * 	$meta_oembed_thumbnail_height = '1080';
		 */

		if ( $meta_oembed_thumbnail ) {
			$thumbnail = array( 'url' => $meta_oembed_thumbnail );

			if ( $meta_oembed_thumbnail_width && $meta_oembed_thumbnail_height ) {
				$thumbnail['width'] = $meta_oembed_thumbnail_width;
				$thumbnail['height'] = $meta_oembed_thumbnail_height;
			}
		}

		return $thumbnail;

	} );

// Filter Twitter Card type (twitter:card)

	// Check/define variables
	$meta_twitter_card_type = ( isset($meta_twitter_card_type) && !empty($meta_twitter_card_type) ) ? $meta_twitter_card_type : 'summary_large_image';

	add_filter( 'seopress_social_twitter_card_summary', function( $html ) use ( $meta_twitter_card_type ) {

		/* 
		 * Override the theme's method of defining the Twitter Card type (twitter:card)
		 * 
		 * The card type, which will be one of:
		 * 	- summary
		 * 	- summary_large_image
		 * 	- app
		 * 	- player
		 * 
		 * Used with all cards
		 */

		if ( $meta_twitter_card_type ) {

			$html = '<meta name="twitter:card" content="' . $meta_twitter_card_type . '" />';

		}

		return $html;

	} );

// Filter Twitter Card site (twitter:site)

	// Check/define variables
	$meta_twitter_site = ( isset($meta_twitter_site) && !empty($meta_twitter_site) ) ? $meta_twitter_site : '';

	add_filter( 'seopress_social_twitter_card_site', function( $html ) use ( $meta_twitter_site ) {

		/* 
		 * @username of website. Either twitter:site or twitter:site:id is required.
		 * Used with summary, summary_large_image, app, player cards (twitter:card)
		 */

		if ( $meta_twitter_site ) {
			$html = '<meta name="twitter:site" content="' . $meta_twitter_site . '" />';
		}

		return $html;

	} );

// Filter Twitter Card creator (twitter:creator)

	// Check/define variables
	$meta_twitter_creator = ( isset($meta_twitter_creator) && !empty($meta_twitter_creator) ) ? $meta_twitter_creator : '';

	add_filter( 'seopress_social_twitter_card_creator', function( $html ) use ( $meta_twitter_creator ) {

		/* 
		 * @username of content creator
		 * Used with summary_large_image cards (twitter:card)
		 */

		if ( $meta_twitter_creator ) {
			$html = '<meta name="twitter:creator" content="' . $meta_twitter_creator . '" />';
		}

		return $html;

	} );

// Filter Twitter Card description (twitter:description)

	// Check/define variables
	$meta_twitter_description = ( isset($meta_twitter_description) && !empty($meta_twitter_description) ) ? $meta_twitter_description : '';

	add_filter( 'seopress_social_twitter_card_desc', function( $html ) use ( $meta_twitter_description ) {

		/* 
		 * Description of content (maximum 200 characters)
		 * Used with summary, summary_large_image, player cards (twitter:card)
		 */

		if ( $meta_twitter_description ) {

			$html = '<meta name="twitter:description" content="' . $meta_twitter_description . '" />';

		}

		return $html;

	} );

// Filter Twitter Card title (twitter:title)

	// Check/define variables
	$meta_twitter_title = ( isset($meta_twitter_title) && !empty($meta_twitter_title) ) ? $meta_twitter_title : '';

	add_filter( 'seopress_social_twitter_card_title', function( $html ) use ( $meta_twitter_title ) {

		/* 
		 * Title of content (max 70 characters)
		 * Used with summary, summary_large_image, player cards (twitter:card)
		 */

		if ( $meta_twitter_title ) {

			$html = '<meta name="twitter:title" content="' . $meta_twitter_title . '" />';

		}

		return $html;

	} );

// Filter Twitter Card thumbnail (twitter:image) and Twitter Card thumbnail alternative text (twitter:image:alt)

	// Check/define variables

		$meta_twitter_image = ( isset($meta_twitter_image) && !empty($meta_twitter_image) ) ? $meta_twitter_image : '';
		$meta_twitter_image_alt = ( isset($meta_twitter_image_alt) && !empty($meta_twitter_image_alt) ) ? $meta_twitter_image_alt : '';

	add_filter( 'seopress_social_twitter_card_thumb', function( $html ) use (
		$meta_twitter_image,
		$meta_twitter_image_alt
	) {

		/* 
		 * Twitter Card thumbnail (twitter:image)
		 * 	- URL of image to use in the card.
		 * 	- Images must be less than 5MB in size.
		 * 	- JPG, PNG, WEBP and GIF formats are supported. Only the first frame of an animated GIF will be used. SVG is not supported.
		 * 	- Used with summary, summary_large_image, player cards (twitter:card)
		 * 	- Formerly "twitter:image:src" (pre-2016)
		 * 
		 * Twitter Card thumbnail alternative text (twitter:image:alt)
		 * 	- A text description of the image conveying the essential nature of an image to users who are visually impaired.
		 * 	- Maximum 420 characters.
		 * 	- Used with summary, summary_large_image, player cards
		 */

		if ( $meta_twitter_image ) {

			$html = '<meta name="twitter:image" content="' . $meta_twitter_image . '" />';

			if ($meta_twitter_image_alt) {

				$html .= '<meta name="twitter:image:alt" content="' . $meta_twitter_image_alt . '" />';

			}

		}

		return $html;

	} );