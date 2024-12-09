<?php
/*
 *
 *  Template Name: Clinical Resources
 *  Designed for clinical resources single
 *
 */
// Set general variables
$page_id = get_the_ID();
$page_title = get_the_title();
$page_title_attr = $page_title;
$page_title_attr = str_replace('"', '\'', $page_title_attr); // Replace double quotes with single quote
$page_title_attr = htmlentities($page_title_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
$page_title_attr = str_replace('&nbsp;', ' ', $page_title_attr); // Convert non-breaking space with normal space
$page_title_attr = html_entity_decode($page_title_attr); // Convert HTML entities to their corresponding characters
$resource_archive_title_system = get_field('clinical_resource_archive_headline', 'option');
$resource_archive_title = $resource_archive_title_system ? $resource_archive_title_system : 'Clinical Resource';
$resource_archive_title_attr = $resource_archive_title;
$resource_archive_title_attr = str_replace('"', '\'', $resource_archive_title_attr); // Replace double quotes with single quote
$resource_archive_title_attr = htmlentities($resource_archive_title_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
$resource_archive_title_attr = str_replace('&nbsp;', ' ', $resource_archive_title_attr); // Convert non-breaking space with normal space
$resource_archive_title_attr = html_entity_decode($resource_archive_title_attr); // Convert HTML entities to their corresponding characters

// Override theme's method of defining the page title
function uamswp_fad_title($html) {
    global $page_title_attr;
    global $resource_archive_title_attr;
    //you can add here all your conditions as if is_page(), is_category() etc..
    $meta_title_chars_max = 60;
    $meta_title_base = $page_title_attr . ' | ' . get_bloginfo( "name" );
    $meta_title_base_chars = strlen( $meta_title_base );
    $meta_title_enhanced_addition = ' | ' . $resource_archive_title_attr;
    $meta_title_enhanced = $page_title_attr . $meta_title_enhanced_addition . ' | ' . get_bloginfo( "name" );
    $meta_title_enhanced_chars = strlen( $meta_title_enhanced );
    if ( $meta_title_enhanced_chars <= $meta_title_chars_max ) {
        $html = $meta_title_enhanced;
    } else {
        $html = $meta_title_base;
    }
    return $html;
}
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

$syndicated = get_field('clinical_resource_syndicated');
$syndication_url = get_field('clinical_resource_syndication_url');
function uamswp_fad_canonical($html) {
    global $syndicated;
    global $syndication_url;
	if ( $syndicated && !empty($syndication_url) ) {
	$html = '<link rel="canonical" href="'.htmlspecialchars(urldecode($syndication_url)).'" />';
    }
	return $html;
}
add_filter('seopress_titles_canonical','uamswp_fad_canonical');
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
// Removes entry meta from entry footer incl. markup.
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

function uams_default_page_body_class( $classes ) {

    $classes[] = 'page-template-default';
    return $classes;
}
add_filter( 'body_class', 'uams_default_page_body_class' );

// Add extra class to entry
function uamswp_add_entry_class( $attributes ) {
    $attributes['class'] = $attributes['class']. ' bg-white';
    return $attributes;
}
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

// Modify Entry Title

    remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
    add_action( 'genesis_entry_header', 'uamswp_resource_post_title' );

    function uamswp_resource_post_title() {
        global $resource_archive_title;
        echo '<h1 class="entry-title" itemprop="headline">';
        echo '<span class="supertitle">'. $resource_archive_title . '</span><span class="sr-only">:</span> ';
        echo get_the_title();
        echo '</h1>';
    }

add_action( 'genesis_entry_content', 'uamswp_resource_text', 8 );
add_action( 'genesis_entry_content', 'uamswp_resource_infographic', 10 );
add_action( 'genesis_entry_content', 'uamswp_resource_video', 12 );
add_action( 'genesis_entry_content', 'uamswp_resource_document', 14 );
add_action( 'genesis_after_entry', 'uamswp_resource_jump_links', 8 );
add_action( 'genesis_after_entry', 'uamswp_resource_associated', 10 );
add_action( 'genesis_after_entry', 'uamswp_resource_conditions_cpt', 12 );
add_action( 'genesis_after_entry', 'uamswp_resource_treatments_cpt', 14 );
add_action( 'genesis_after_entry', 'uamswp_resource_physicians', 16 );
add_action( 'genesis_after_entry', 'uamswp_resource_locations', 18 );
add_action( 'genesis_after_entry', 'uamswp_resource_expertise', 20 );
add_action( 'genesis_after_entry', 'uamswp_resource_appointment', 22 );


// Set logic for displaying jump links and sections
$jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
$jump_link_count = 0;

$resource_type = get_field('clinical_resource_type');
$resource_type_value = $resource_type['value'];
$resource_type_label = $resource_type['label'];

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
    $show_conditions_section = true;
    $jump_link_count++;
} else {
    $show_conditions_section = false;
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
    $show_treatments_section = true;
    $jump_link_count++;
} else {
    $show_treatments_section = false;
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
        "post__in" => $physicians
    );
    $physicians_query = New WP_Query( $args );
    if($physicians_query && $physicians_query->have_posts()) {
        $show_providers_section = true;
        $jump_link_count++;
    } else {
        $show_providers_section = false;
    }
}

// Check if Locations section should be displayed
$locations = get_field('clinical_resource_locations');
if($locations) {
    $args = (array(
        'post_type' => "location",
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post__in'	=> $locations
    ));
    $location_query = new WP_Query( $args );
    if( $locations && $location_query->have_posts() ) {
        $show_locations_section = true;
        $jump_link_count++;
    } else {
        $show_locations_section = false;
    }
}

// Check if Expertise section should be displayed
$expertises =  get_field('clinical_resource_aoe');
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
    $show_aoe_section = true;
    $jump_link_count++;
} else {
    $show_aoe_section = false;
}

// Clinical Resources
$resources =  get_field('clinical_resource_related');
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
    $show_related_resource_section = true;
    $resource_count = count($resources);
    $jump_link_count++;
} else {
    $show_related_resource_section = false;
}

// Check if Make an Appointment section should be displayed
// It should always be displayed.
$show_appointment_section = true;
$jump_link_count++;

// Check if Jump Links section should be displayed
if ( $jump_link_count >= $jump_link_count_min ) {
    $show_jump_links_section = true;
} else {
    $show_jump_links_section = false;
}
function uamswp_resource_text() {
    global $resource_type_value;
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
    global $resource_type_value;
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
    global $resource_type_value;
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
            } elseif ( $document_url_extension == 'jpg' ||  $document_url_extension == 'jpeg' || $document_url_extension == 'gif' || $document_url_extension == 'png' || $document_url_extension == 'bmp' ) {
                $icon_file = $icon_image;
            }
        ?>
            <li><a class="attachment-link" href="<?php echo $document_url; ?>" title="<?php echo $document_title; ?>" target="_blank"><span class="<?php echo $icon_file; ?> fa-fw"></span><span class="attachment-label"><?php echo $document_title; ?></span></a></li>
        <?php endwhile;
        echo '</ul>';
    endif;
}
function uamswp_resource_physicians() {
    global $show_providers_section;
    global $postsCountClass;
    global $physicians_query;
    global $postsPerPage;
    global $physicians;

    if($show_providers_section) {
        ?>
        <section class="uams-module bg-auto" id="providers">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title">Related Providers</span></h2>
                        <div class="card-list-container">
                            <div class="card-list card-list-doctors">
                                <?php
                                    while ($physicians_query->have_posts()) : $physicians_query->the_post();
                                        $id = get_the_ID();
                                        include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
                                    endwhile;
                                    wp_reset_postdata();
                                ?>
                            </div>
                        </div>
                        <!-- <?php if ($postsPerPage !== -1) { ?>
                        <div class="more">
                            <button class="loadmore btn btn-primary" data-posttype="post" data-postids="<?php echo(implode(',', $physicians)); ?>" data-ppp="<?php echo $postsPerPage; ?>" data-postcount="<?php echo $physicians_query->found_posts; ?>" aria-label="Load more providers">Load More</button>
                        </div>
                        <?php } ?> -->
                        <div class="ajax-filter-load-more">
                            <button class="btn btn-lg btn-primary" aria-label="Load all providers">Load All</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
}
function uamswp_resource_video() {
    global $resource_type_value;
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
    global $page_title;
    global $show_conditions_section;
    global $conditions_cpt_query;
    $condition_context = 'single-resource';
    $condition_heading_related_name = $page_title; // To what is it related?

    if( $show_conditions_section ) {
        include( UAMS_FAD_PATH . '/templates/loops/conditions-cpt-loop.php' );
    }
}
function uamswp_resource_treatments_cpt() {
    global $page_title;
    global $show_treatments_section;
    global $treatments_cpt_query;
    $treatment_context = 'single-resource';
    $treatment_heading_related_name = $page_title; // To what is it related?

    if( $show_treatments_section ) {
        include( UAMS_FAD_PATH . '/templates/loops/treatments-cpt-loop.php' );
    }
}
function uamswp_resource_locations() {
    global $show_locations_section;
    global $location_query;

    if ( $show_locations_section ) { ?>
        <section class="uams-module location-list bg-auto" id="locations">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title">Related Locations</span></h2>
                        <div class="card-list-container location-card-list-container">
                            <div class="card-list">
                            <?php while ( $location_query->have_posts() ) : $location_query->the_post();
                                $id = get_the_ID();
                                include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
                            endwhile;
                            wp_reset_postdata();?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php
    } // endif
}
function uamswp_resource_associated() {
    global $page_title;
    global $show_related_resource_section;
    global $resources;
    global $resource_query;
    global $resource_postsPerPage;
    $resource_heading_related_pre = true; // "Related Resources"
    $resource_heading_related_post = false; // "Resources Related to __"
    $resource_heading_related_name = $page_title; // To what is it related?
    $resource_more_suppress = false; // Force div.more to not display
    $resource_more_key = '';
    $resource_more_value = '';
    if( $show_related_resource_section ) {
        include( UAMS_FAD_PATH . '/templates/blocks/clinical-resources.php' );
    }
}
function uamswp_resource_expertise() {
    global $show_aoe_section;
    global $expertise_query;

    if( $show_aoe_section ) { ?>
		<section class="uams-module expertise-list bg-auto" id="expertise" aria-labelledby="areas-of-expertise-title">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title" id="areas-of-expertise-title"><span class="title">Related Areas of Expertise</span></h2>
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
    global $page_title;
    global $show_related_resource_section;
    global $show_conditions_section;
    global $show_treatments_section;
    global $show_providers_section;
    global $show_locations_section;
    global $show_aoe_section;
    global $show_jump_links_section;
    global $show_appointment_section;

    // Begin Jump Links Section
    if ( $show_jump_links_section ) { ?>
        <nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
            <h2>Related Content</h2>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse inner-container" id="jump-link-nav">
                <ul class="nav navbar-nav">
                    <?php if ( $show_related_resource_section ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#related-resources" title="Jump to the section of this page about Related Resources">Resources</a>
                        </li>
                    <?php } ?>
                    <?php if ( $show_conditions_section ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#conditions" title="Jump to the section of this page about Related Conditions">Conditions</a>
                        </li>
                    <?php } ?>
                    <?php if ( $show_treatments_section ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#treatments" title="Jump to the section of this page about Medical Treatments and Procedures Performed">Treatments &amp; Procedures</a>
                        </li>
                    <?php } ?>
                    <?php if ( $show_providers_section ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#providers" title="Jump to the section of this page about Providers">Providers</a>
                        </li>
                    <?php } ?>
                    <?php if ($show_locations_section) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#locations" title="Jump to the section of this page about Locations">Locations</a>
                        </li>
                    <?php } ?>
                    <?php if ($show_aoe_section) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#expertise" title="Jump to the section of this page about Related Areas of Expertise">Areas of Expertise</a>
                        </li>
                    <?php } ?>
                    <?php if ( $show_appointment_section ) { ?>
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
    global $show_appointment_section;

    if ( $show_appointment_section ) {
        $appointment_location_url = '/location/';
        //$appointment_location_label = 'View a list of UAMS Health locations';
        ?>
        <section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>Make an Appointment</h2>
                        <p>Request an appointment by <a href="<?php echo $appointment_location_url; ?>" data-itemtitle="Contact a clinic directly">contacting a clinic directly</a> or by calling the UAMS&nbsp;Health appointment line at <a href="tel:501-686-8000" class="no-break" data-itemtitle="Call the UAMS Health appointment line">(501) 686-8000</a>.</p>
                    </div>
                </div>
            </div>
        </section>
    <?php }
}
genesis();