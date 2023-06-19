<?php
/*
 * Template Name: Single Clinical Resource
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	uamswp_fad_labels_provider();

	// Get system settings for location labels
	uamswp_fad_labels_location();

	// Get system settings for location descendant item labels
	// uamswp_fad_labels_location_descendant();

	// Get system settings for area of expertise labels
	uamswp_fad_labels_expertise();

	// Get system settings for area of expertise descendant item labels
	// uamswp_fad_labels_expertise_descendant();

	// Get system settings for clinical resource labels
	uamswp_fad_labels_clinical_resource();

	// Get system settings for condition labels
	uamswp_fad_labels_condition();

	// Get system settings for treatment labels
	uamswp_fad_labels_treatments();

// Get system settings for clinical resource archive page text
// uamswp_fad_archive_clinical_resource();

// Get the page ID
$page_id = get_the_ID();

// Get the page title for the clinical resource
$page_title = get_the_title();
$page_title_attr = uamswp_attr_conversion($page_title);

// Get system settings for text elements on Clinical Resource profile
uamswp_fad_fpage_text_clinical_resource();

// Get system settings for jump links (a.k.a. anchor links)
// uamswp_fad_labels_jump_links();

// Get system settings for jump links (a.k.a. anchor links)
uamswp_fad_labels_jump_links();

// Get resource type
$resource_type = get_field('clinical_resource_type');
$resource_type_value = $resource_type['value'];
$resource_type_label = $resource_type['label'];
$resource_type_label_attr = uamswp_attr_conversion($resource_type_label);

// Override theme's method of defining the meta page title
$meta_title_base_addition = $page_title_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
$meta_title_enhanced_addition = $clinical_resource_single_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
$meta_title_enhanced_x2_addition = $resource_type_label_attr; // Second word or phrase to inject into base meta title to form enhanced meta title level 2
$meta_title_enhanced_x2_order = array( $meta_title_base_addition, $meta_title_enhanced_x2_addition, $meta_title_enhanced_addition ); // Optional pre-defined array for name order of enhanced meta title level 2 // Expects three values but will accommodate any number
uamswp_fad_title_vars(); // Defines universal variables related to the setting the meta title
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

// Modify SEOPress's standard canonical URL settings
$syndicated = get_field('clinical_resource_syndicated');
$syndication_url = get_field('clinical_resource_syndication_url');
function uamswp_fad_canonical($html) {
	// Bring in variables from outside of the function
	global $syndicated; // Defined on the template
	global $syndication_url; // Defined on the template

	if ( $syndicated && !empty($syndication_url) ) {
		$html = '<link rel="canonical" href="' . htmlspecialchars(urldecode($syndication_url)) . '" />';
	}

	return $html;
}
add_filter('seopress_titles_canonical','uamswp_fad_canonical');

// Add page template class to body element's classes
add_filter( 'body_class', 'uamswp_page_body_class' );
$template_type = 'default';

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
// Removes entry meta from entry footer incl. markup.
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

// Add bg-white class to article.entry element
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

// Modify Entry Title

	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	add_action( 'genesis_entry_header', 'uamswp_resource_post_title' );

	function uamswp_resource_post_title() {
		// Bring in variables from outside of the function
		global $clinical_resource_single_name; // Defined in uamswp_fad_labels_clinical_resource()

		echo '<h1 class="entry-title" itemprop="headline">';
		echo '<span class="supertitle">'. $clinical_resource_single_name . '</span><span class="sr-only">: </span>';
		echo get_the_title();
		echo '</h1>';
	}

// Construct page content

	// Construct main clinical resource content section

		// Resource type: article
		add_action( 'genesis_entry_content', 'uamswp_resource_text', 8 );

		// Resource type: infographic
		add_action( 'genesis_entry_content', 'uamswp_resource_infographic', 10 );

		// Resource type: video
		add_action( 'genesis_entry_content', 'uamswp_resource_video', 12 );

		// Resource type: document
		add_action( 'genesis_entry_content', 'uamswp_resource_document', 14 );

	// Construct jump links section
	add_action( 'genesis_after_entry', 'uamswp_resource_jump_links', 8 );

	// Construct related clinical resources section
	add_action( 'genesis_after_entry', 'uamswp_resource_associated', 10 );

	// Construct conditions section
	add_action( 'genesis_after_entry', 'uamswp_resource_conditions_cpt', 12 );

	// Construct treatments section
	add_action( 'genesis_after_entry', 'uamswp_resource_treatments_cpt', 14 );

	// Construct providers section
	$provider_section_title = $provider_fpage_title_clinical_resource; // Text to use for the section title
	$provider_section_intro = $provider_fpage_intro_clinical_resource; // Text to use for the section intro text
	add_action( 'genesis_after_entry', 'uamswp_fad_section_provider', 16 );

	// Construct locations section
	$location_section_title = $location_fpage_title_clinical_resource; // Text to use for the section title
	$location_section_intro = $location_fpage_intro_clinical_resource; // Text to use for the section intro text
	add_action( 'genesis_after_entry', 'uamswp_fad_section_location', 18 );

	// Construct areas of expertise section
	add_action( 'genesis_after_entry', 'uamswp_resource_expertise', 20 );

	// Construct appointment information section
	add_action( 'genesis_after_entry', 'uamswp_resource_appointment', 22 );


// Set logic for displaying jump links and sections
$jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
$jump_link_count = 0;

// Check if Conditions section should be displayed
// load all 'conditions' terms for the post
$conditions_cpt = get_field('clinical_resource_conditions');
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
if( $conditions_cpt && $conditions_cpt_query->posts ) {
	$condition_section_show = true;
	$jump_link_count++;
} else {
	$condition_section_show = false;
}

// Check if Treatments and Procedures section should be displayed
$treatments_cpt = get_field('clinical_resource_treatments');
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
if( $treatments_cpt && $treatments_cpt_query->posts ) {
	$treatments_section_show = true;
	$jump_link_count++;
} else {
	$treatments_section_show = false;
}

// Check if Providers section should be displayed
$physicians = get_field( "clinical_resource_providers" );
if($physicians) {
	$postsPerPage = 12; // Set this value to preferred value (4, 6, 8, 10, 12). If you change the value, update the instruction text in the editor's JSON file.
	$postsCutoff = 18; // Set cutoff value. If you change the value, update the instruction text in the editor's JSON file.
	$postsCountClass = $postsPerPage;
	if(count($physicians) <= $postsCutoff ) {
		$postsPerPage = -1;
	}
	$args = array(
		"post_type" => "provider",
		"post_status" => "publish",
		"posts_per_page" => $postsPerPage,
		"orderby" => "title",
		"order" => "ASC",
		"fields" => "ids",
		"post__in" => $physicians
	);
	$provider_query = New WP_Query( $args );
	if($provider_query && $provider_query->have_posts()) {
		$provider_section_show = true;
		$jump_link_count++;
		$provider_ids = $provider_query->posts;
	} else {
		$provider_section_show = false;
	}
}

// Query for whether associated locations content section should be displayed on a page
$locations = get_field('clinical_resource_locations');
uamswp_fad_location_query();

// Check if Expertise section should be displayed
$expertises = get_field('clinical_resource_aoe');
$args = (array(
	'post_type' => "expertise",
	'order' => 'ASC',
	'orderby' => 'title',
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'post__in'	=> $expertises
));
$expertise_query = new WP_Query( $args );
if( $expertises && $expertise_query->have_posts() ) {
	$expertise_section_show = true;
	$jump_link_count++;
} else {
	$expertise_section_show = false;
}

// Clinical Resources
$resources = get_field('clinical_resource_related');
$resource_postsPerPage = -1; // Set this value to preferred value (-1, 4, 6, 8, 10, 12)
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
if( $resources && $resource_query->have_posts() ) {
	$clinical_resource_show_section = true;
	$resource_count = count($resources);
	$jump_link_count++;
} else {
	$clinical_resource_show_section = false;
}

// Check if Make an Appointment section should be displayed
// It should always be displayed.
$appointment_section_show = true;
$jump_link_count++;

// Check if Jump Links section should be displayed
if ( $jump_link_count >= $jump_link_count_min ) {
	$jump_links_section_show = true;
} else {
	$jump_links_section_show = false;
}
function uamswp_resource_text() {
	// Bring in variables from outside of the function
	global $resource_type_value; // Defined on the template

	$text = get_field('clinical_resource_text');
	$nci_query = get_field('clinical_resource_text_nci_query');
	$nci_embed = get_field('clinical_resource_nci_embed');

	if( 'text' == $resource_type_value && $text && !$nci_query ) { // $show_text_section ) {
		echo $text;
	} elseif ( 'text' == $resource_type_value && $nci_query && $nci_embed ) {
		echo $nci_embed;
	}
}
function uamswp_resource_infographic() {
	// Bring in variables from outside of the function
	global $resource_type_value; // Defined on the template

	$infographic = get_field('clinical_resource_infographic');
	$infographic_descr = get_field('clinical_resource_infographic_descr');
	$infographic_transcript = get_field('clinical_resource_infographic_transcript');
	$size = 'content-image-wide';

	if( 'infographic' == $resource_type_value && $infographic ) {
		if ( $infographic_descr ) {
			echo '<h2 class="sr-only">Description</h2>';
			echo $infographic_descr;
		}

		echo '<h2 class="sr-only">Infographic</h2>';
		echo '<div class="alignwide">';
		echo wp_get_attachment_image( $infographic, $size );
		echo '</div>';

		if ( $infographic_transcript ) {
			echo '<h2>Transcript</h2>';
			echo $infographic_transcript;
		}
	}
}
function uamswp_resource_document() {
	// Bring in variables from outside of the function
	global $resource_type_value; // Defined on the template

	$document_descr = get_field('clinical_resource_document_descr');
	$document = get_field('clinical_resource_document');

	$icon_file = 'far fa-file';
	$icon_pdf = 'far fa-file-pdf';
	$icon_word = 'far fa-file-word';
	$icon_powerpoint = 'far fa-file-powerpoint';
	$icon_excel = 'far fa-file-excel';
	$icon_image = 'far fa-file-image';

	if( 'doc' == $resource_type_value && have_rows('clinical_resource_document') ):
		echo $document_descr;
		echo '<hr />';
		echo '<h2>Attachments</h2>';
		echo '<ul class="attachments">';
		while( have_rows('clinical_resource_document') ): the_row();
			$document_title = get_sub_field('document_title');
			$document_file = get_sub_field('document_file');
			$document_url = $document_file['url'];
			$document_url_path = pathinfo($document_url);
			$document_url_extension = $document_url_path['extension'];

			if ( $document_url_extension == 'pdf' ) {
				$icon_file = $icon_pdf;
			} elseif ( $document_url_extension == 'doc' || $document_url_extension == 'docx' ) {
				$icon_file = $icon_word;
			} elseif ( $document_url_extension == 'ppt' || $document_url_extension == 'pptx' ) {
				$icon_file = $icon_powerpoint;
			} elseif ( $document_url_extension == 'xls' || $document_url_extension == 'xlsx' ) {
				$icon_file = $icon_excel;
			} elseif ( $document_url_extension == 'jpg' || $document_url_extension == 'jpeg' || $document_url_extension == 'gif' || $document_url_extension == 'png' || $document_url_extension == 'bmp' ) {
				$icon_file = $icon_image;
			}
		?>
			<li><a class="attachment-link" href="<?php echo $document_url; ?>" title="<?php echo $document_title; ?>" target="_blank"><span class="<?php echo $icon_file; ?> fa-fw"></span><span class="attachment-label"><?php echo $document_title; ?></span></a></li>
		<?php endwhile;
		echo '</ul>';
	endif;
}
function uamswp_resource_video() {
	// Bring in variables from outside of the function
	global $resource_type_value; // Defined on the template

	$video = get_field('clinical_resource_video');
	$video_descr = get_field('clinical_resource_video_descr');
	$video_transcript = get_field('clinical_resource_video_transcript');

	$video_source = '';
	if ( (strpos($video, 'youtube') !== false) || (strpos($video, 'youtu.be') !== false) ) {
		$video_source = 'youtube';
	}

	if( 'video' == $resource_type_value && $video ) { ?>
		<?php if ( $video_descr ) {
			echo '<h2 class="sr-only">Description</h2>';
			echo $video_descr;
		}
		echo '<h2 class="sr-only">Video Player</h2>';
		if( function_exists('lyte_preparse') && $video_source == 'youtube' ) {
			echo '<div class="alignwide">';
			echo lyte_parse( str_replace( ['https:', 'http:'], 'httpv:', $video ) );
			echo '</div>';
		} else {
			echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
			echo wp_oembed_get( $video );
			echo '</div>';
		}
		if ( $video_transcript ) {
			echo '<h2>Transcript</h2>';
			echo $video_transcript;
		}
	}
}
function uamswp_resource_conditions_cpt() {
	// Bring in variables from outside of the function
	global $page_title; // Defined on the template
	global $page_title_attr; // Defined on the template
	global $condition_section_show; // Defined on the template
	global $conditions_cpt_query; // Defined on the template
	global $provider_plural_name; // Defined in uamswp_fad_labels_provider()
	global $conditions_single_name_attr; // Defined in uamswp_fad_labels_condition()
	global $conditions_plural_name; // Defined in uamswp_fad_labels_condition()

	$condition_context = 'single-resource';
	$condition_heading_related_name = $page_title; // To what is it related?
	$condition_heading_related_name_attr = $page_title_attr;

	if( $condition_section_show ) {
		include( UAMS_FAD_PATH . '/templates/loops/conditions-cpt-loop.php' );
	}
}
function uamswp_resource_treatments_cpt() {
	// Bring in variables from outside of the function
	global $page_title; // Defined on the template
	global $page_title_attr; // Defined on the template
	global $treatments_section_show; // Defined on the template
	global $treatments_cpt_query; // Defined on the template
	global $provider_plural_name; // Defined in uamswp_fad_labels_provider()
	global $treatments_single_name; // Defined in uamswp_fad_labels_treatments()
	global $treatments_plural_name; // Defined in uamswp_fad_labels_treatments()

	$treatment_context = 'single-resource';
	$treatment_heading_related_name = $page_title; // To what is it related?
	$treatment_heading_related_name_attr = $page_title_attr;

	if( $treatments_section_show ) {
		include( UAMS_FAD_PATH . '/templates/loops/treatments-cpt-loop.php' );
	}
}
function uamswp_resource_associated() {
	// Bring in variables from outside of the function
	global $page_title; // Defined on the template
	global $page_title_attr; // Defined on the template
	global $clinical_resource_show_section; // Defined on the template
	global $resources; // Defined on the template
	global $resource_query; // Defined on the template
	global $resource_postsPerPage; // Defined on the template
	global $provider_single_name; // Defined in uamswp_fad_labels_provider()
	global $provider_plural_name; // Defined in uamswp_fad_labels_provider()
	global $location_single_name; // Defined in uamswp_fad_labels_location()
	global $location_plural_name; // Defined in uamswp_fad_labels_location()
	global $expertise_single_name; // Defined in uamswp_fad_labels_expertise()
	global $expertise_plural_name; // Defined in uamswp_fad_labels_expertise()
	global $clinical_resource_single_name; // Defined in uamswp_fad_labels_clinical_resource()
	global $clinical_resource_plural_name; // Defined in uamswp_fad_labels_clinical_resource()
	global $conditions_single_name; // Defined in uamswp_fad_labels_condition()
	global $conditions_plural_name; // Defined in uamswp_fad_labels_condition()
	global $treatments_single_name; // Defined in uamswp_fad_labels_treatments()
	global $treatments_plural_name; // Defined in uamswp_fad_labels_treatments()
	global $clinical_resource_fpage_title_clinical_resource; // Defined in uamswp_fad_fpage_text_clinical_resource()
	global $clinical_resource_fpage_intro_clinical_resource; // Defined in uamswp_fad_fpage_text_clinical_resource()

	$resource_heading = $clinical_resource_fpage_title_clinical_resource;
	$resource_heading_related_name = $page_title; // To what is it related? Leave empty to 
	$resource_heading_related_name_attr = $page_title_attr;
	$resource_intro = $clinical_resource_fpage_intro_clinical_resource;
	$resource_more_suppress = false; // Force div.more to not display
	$resource_more_key = '';
	$resource_more_value = '';
	if( $clinical_resource_show_section ) {
		include( UAMS_FAD_PATH . '/templates/blocks/clinical-resources.php' );
	}
}
function uamswp_resource_expertise() {
	// Bring in variables from outside of the function
	global $expertise_fpage_title_clinical_resource; // Defined in uamswp_fad_fpage_text_clinical_resource()
	global $expertise_fpage_intro_clinical_resource; // Defined in uamswp_fad_fpage_text_clinical_resource()
	global $expertise_section_show; // Defined on the template
	global $expertise_query; // Defined on the template
	global $expertise_single_name; // Defined in uamswp_fad_labels_expertise()
	global $expertise_single_name_attr; // Defined in uamswp_fad_labels_expertise()
	global $expertise_plural_name; // Defined in uamswp_fad_labels_expertise()

	if( $expertise_section_show ) { ?>
		<section class="uams-module expertise-list bg-auto" id="expertise" aria-labelledby="areas-of-expertise-title">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title" id="areas-of-expertise-title"><span class="title"><?php echo $expertise_fpage_title_clinical_resource; ?></span></h2>
						<?php echo $expertise_fpage_intro_clinical_resource ? '<p class="note">' . $expertise_fpage_intro_clinical_resource . '</p>' : ''; ?>
						<div class="card-list-container">
							<div class="card-list card-list-expertise">
							<?php 
							while ($expertise_query->have_posts()) : $expertise_query->the_post();
								$id = get_the_ID();
								include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php 
	} // endif
}
function uamswp_resource_jump_links() {
	// Bring in variables from outside of the function
	global $provider_plural_name; // Defined in uamswp_fad_labels_provider()
	global $provider_plural_name_attr; // Defined in uamswp_fad_labels_provider()
	global $location_plural_name; // Defined in uamswp_fad_labels_location()
	global $location_plural_name_attr; // Defined in uamswp_fad_labels_location()
	global $expertise_plural_name; // Defined in uamswp_fad_labels_expertise()
	global $expertise_plural_name_attr; // Defined in uamswp_fad_labels_expertise()
	global $clinical_resource_plural_name; // Defined in uamswp_fad_labels_clinical_resource()
	global $clinical_resource_plural_name_attr; // Defined in uamswp_fad_labels_clinical_resource()
	global $conditions_plural_name; // Defined in uamswp_fad_labels_condition()
	global $conditions_plural_name_attr; // Defined in uamswp_fad_labels_condition()
	global $treatments_plural_name; // Defined in uamswp_fad_labels_treatments()
	global $treatments_plural_name_attr; // Defined in uamswp_fad_labels_treatments()
	global $fad_jump_links_title; // Defined in uamswp_fad_labels_jump_links()
	global $page_title; // Defined on the template
	global $clinical_resource_show_section; // Defined on the template
	global $condition_section_show; // Defined on the template
	global $treatments_section_show; // Defined on the template
	global $provider_section_show; // Defined on the template
	global $location_section_show; // Defined on the template
	global $expertise_section_show; // Defined on the template
	global $jump_links_section_show; // Defined on the template
	global $appointment_section_show; // Defined on the template

	// Begin Jump Links Section
	if ( $jump_links_section_show ) { ?>
		<nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
			<h2><?php echo $fad_jump_links_title; ?></h2>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse inner-container" id="jump-link-nav">
				<ul class="nav navbar-nav">
					<?php if ( $clinical_resource_show_section ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#related-resources" title="Jump to the section of this page about related <?php echo $clinical_resource_plural_name_attr; ?>">Related <?php echo $clinical_resource_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $condition_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#conditions" title="Jump to the section of this page about related <?php echo $conditions_plural_name_attr; ?>"><?php echo $conditions_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $treatments_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#treatments" title="Jump to the section of this page about related <?php echo $treatments_plural_name_attr; ?>"><?php echo $treatments_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $provider_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#providers" title="Jump to the section of this page about related <?php echo $provider_plural_name_attr; ?>"><?php echo $provider_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ($location_section_show) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#locations" title="Jump to the section of this page about related <?php echo $location_plural_name_attr; ?>"><?php echo $location_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ($expertise_section_show) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#expertise" title="Jump to the section of this page about related <?php echo $expertise_plural_name_attr; ?>"><?php echo $expertise_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $appointment_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#appointment-info" title="Jump to the section of this page about making an appointment">Make an Appointment</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</nav>
	<?php }
}
function uamswp_resource_appointment() {
	// Bring in variables from outside of the function
	global $location_single_name; // Defined in uamswp_fad_labels_location()
	global $location_single_name_attr; // Defined in uamswp_fad_labels_location()
	global $appointment_section_show; // Defined on the template

	if ( $appointment_section_show ) {
		$appointment_location_url = '/location/';
		//$appointment_location_label = 'View a list of UAMS Health locations';
		?>
		<section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2>Make an Appointment</h2>
						<p>Request an appointment by <a href="<?php echo $appointment_location_url; ?>" data-itemtitle="Contact a <?php echo strtolower($location_single_name_attr); ?> directly">contacting a <?php echo strtolower($location_single_name); ?> directly</a> or by calling the UAMS&nbsp;Health appointment line at <a href="tel:501-686-8000" class="no-break" data-itemtitle="Call the UAMS Health appointment line">(501) 686-8000</a>.</p>
					</div>
				</div>
			</div>
		</section>
	<?php }
}
genesis();