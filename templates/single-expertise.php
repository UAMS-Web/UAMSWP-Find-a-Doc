<?php
/*
 *
 *  Template Name: Services
 *  Designed for services single
 *
 */
// Set general variables
$page_id = get_the_ID();
$page_title = get_the_title();
$expertise_archive_title_system = get_field('expertise_archive_headline', 'option');
$expertise_archive_title = $expertise_archive_title_system ? $expertise_archive_title_system : 'Area of Expertise';

function uamswp_fad_title($html) { 
    global $page_title;
	//you can add here all your conditions as if is_page(), is_category() etc.. 
	$html = $page_title . ' | ' . get_bloginfo( "name" );
	return $html;
}
add_filter('pre_get_document_title', 'uamswp_fad_title', 15, 2);
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
    add_action( 'genesis_entry_header', 'uamswp_expertise_post_title' );

    function uamswp_expertise_post_title() {
        global $expertise_archive_title;
        echo '<h1 class="entry-title" itemprop="headline">';
        echo '<span class="supertitle">'. $expertise_archive_title . '</span><span class="sr-only">:</span> ';
        echo get_the_title();
        echo '</h1>';
    }

add_filter( 'genesis_entry_content', 'uamswp_expertise_keywords', 8 );
add_action( 'genesis_entry_content', 'uamswp_expertise_youtube', 12 );
add_action( 'genesis_after_entry', 'uamswp_expertise_cta', 6 );
add_action( 'genesis_after_entry', 'uamswp_expertise_jump_links', 8 );
add_action( 'genesis_after_entry', 'uamswp_expertise_podcast', 10 );
add_action( 'genesis_after_entry', 'uamswp_list_child_expertise', 12 );
add_action( 'genesis_after_entry', 'uamswp_expertise_resource', 14 );
add_action( 'genesis_after_entry', 'uamswp_expertise_conditions_cpt', 16 );
add_action( 'genesis_after_entry', 'uamswp_expertise_treatments_cpt', 18 );
add_action( 'genesis_after_entry', 'uamswp_expertise_physicians', 20 );
add_action( 'genesis_after_entry', 'uamswp_expertise_locations', 22 );
add_action( 'genesis_after_entry', 'uamswp_expertise_associated', 24 );
add_action( 'genesis_after_entry', 'uamswp_expertise_appointment', 26 );
add_action( 'wp_head', 'uamswp_expertise_header_metadata' );

// Set logic for displaying jump links and sections
$jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
$jump_link_count = 0;

// Check if Podcast section should be displayed
$podcast_name = get_field('expertise_podcast_name');
if ($podcast_name) {
    $show_podcast_section = true;
    $jump_link_count++;
} else {
    $show_podcast_section = false;
}

// Clinical Resources
$resources =  get_field('expertise_clinical_resources');
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
if( $resources && $resource_query->have_posts() ) {
    $show_related_resource_section = true;
    $jump_link_count++;
} else {
    $show_related_resource_section = false;
}

// Check if Child Areas of Expertise section should be displayed
if (
    !( get_post_meta( $page_id, 'hide_sub_areas_of_expertise', true) ) 
    && ( 0 != count( get_pages( array( 'child_of' => $page_id, 'post_type' => 'expertise' ) ) ) ) 
) {
    $show_child_aoe_section = true;
    $jump_link_count++;
} else {
    $show_child_aoe_section = false; // If it's suppressed or none available, set to false
}

// Check if Conditions section should be displayed
// load all 'conditions' terms for the post
$conditions_cpt = get_field('expertise_conditions_cpt');
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
$treatments_cpt = get_field('expertise_treatments_cpt');
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
$physicians = get_field( "physician_expertise" );
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
    if($physicians_query && $physicians_query->have_posts()) {
        $show_providers_section = true;
        $jump_link_count++;
        $provider_ids = $physicians_query->posts;
        wp_reset_postdata();
    } else {
        $show_providers_section = false;
    }
}

// Check if Locations section should be displayed
$locations = get_field('location_expertise');
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

// Check if Locations section should be displayed
$expertises =  get_field('expertise_associated');
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
    $show_related_aoe_section = true;
    $jump_link_count++;
} else {
    $show_related_aoe_section = false;
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

function uamswp_expertise_cta() {
    $cta_repeater = get_field('expertise_cta');
    if( $cta_repeater ): 
        $i = 1;
        foreach( $cta_repeater as $cta ) { 
            $cta_heading = $cta['cta_bar_heading'];
            $cta_body = $cta['cta_bar_body'];
            $cta_action_type = $cta['cta_bar_action_type'];

            $cta_button_text = '';
            $cta_button_url = '';
            $cta_button_target = '';
            $cta_button_desc = '';
            if ( $cta_action_type == 'url' ) {
                $cta_button_text = $cta['cta_bar_button_text'];
                $cta_button_url = $cta['cta_bar_button_url'];
                if ( $cta_button_url ) {
                    $cta_button_target = $cta_button_url['target'];
                }
                $cta_button_desc = $cta['cta_bar_button_description'];
            }

            $cta_phone_prepend = '';
            $cta_phone = '';
            $cta_phone_link = '';
            if ( $cta_action_type == 'phone' ) {
                $cta_phone_prepend = $cta['cta_bar_phone_prepend'] ? $cta['cta_bar_phone_prepend'] : 'Call';
                $cta_phone = $cta['cta_bar_phone'];
                $cta_phone_link = '<a href="tel:' . format_phone_dash( $cta_phone ) . '">' . format_phone_us( $cta_phone ) . '</a>';
            }
            
            $cta_layout = 'cta-bar-centered';
            $cta_size = 'normal';
            $cta_use_image = false;
            $cta_image = '';
            $cta_background_color = 'bg-auto';
            $cta_btn_color = 'primary';

            $cta_className = '';  
            $cta_className .= ' ' . $cta_layout;
            $cta_className .= ' ' . $cta_background_color;
            $cta_className .= $cta_use_image ? ' bg-image' : '';
            if ( $cta_size == 'small' ) {
                $cta_className .= ' cta-bar-sm';
            } elseif ( $cta_size == 'large' ) {
                $cta_className .= ' extra-padding cta-bar-lg';
            }
            if ( $cta_action_type == 'none' ) {
                $cta_className .= ' no-link';
            }

            echo '<section class="uams-module cta-bar' . $cta_className . '" id="cta-bar-' . $i . '" aria-label="' . $cta_heading . '">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="inner-container">
                                <div class="cta-heading">
                                    <h2>' . $cta_heading . '</h2>
                                </div>
                                <div class="cta-body">
                                    <div class="text-container">
                                        ' . $cta_body . '
                                    </div>';
                                    echo $cta_action_type == 'url' ?
                                    '<div class="btn-container">
                                        <a href="' . $cta_button_url['url'] . '" aria-label="' . $cta_button_desc . '" class=" btn btn-' . $cta_btn_color . ( $cta_size == 'large' ? ' btn-lg' : '' ) . '"' . ( $cta_button_target ? ' target="'. $cta_button_target . '"' : '' ) . ' data-moduletitle="' . $cta_heading . '">' . $cta_button_text . '</a>
                                    </div>'
                                    : '';
                                    echo $cta_action_type == 'phone' ?
                                    '<div class="btn-container">
                                        <a href="tel:' . $cta_phone . '" data-moduletitle="' . $cta_heading . '">' . $cta_phone_prepend . ' <span class="no-break">' . $cta_phone . '</span></a>
                                    </div>'
                                    : '';
                                echo '</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>';
            $i++;
        } 
    endif;
}

function uamswp_expertise_physicians() {
    global $show_providers_section;
    //global $postsCountClass;
    global $physicians_query;
    //global $postsPerPage;
    global $physicians;
    global $provider_ids;

    // if cookies are set, run modified physician query
	if ( isset($_COOKIE['_filter_region']) || isset($_GET['_filter_region']) ) {		
		
        $provider_region = '';
        if( isset($_COOKIE['_filter_region']) || isset($_GET['_filter_region']) ) {
            $provider_region = isset($_GET['_filter_region']) ? $_GET['_filter_region'] : $_COOKIE['_filter_region'];
        }

        $tax_query = array();
        if(!empty($provider_region)) {
            $region =  $provider_region;
            $tax_query[] = array(
                'taxonomy' => 'region',
                'field' => 'slug',
                'terms' => $region
            );
        }
        $postsPerPage = -1;
        $args = array(
            "post_type" => "provider",
            "post_status" => "publish",
            "posts_per_page" => $postsPerPage,
            "orderby" => "title",
            "order" => "ASC",
            "fields" => "ids",
            "post__in" => $physicians,
            'tax_query' => $tax_query
        );
        $physicians_query = New WP_Query( $args );
    }

    if($show_providers_section) {   
        $provider_count = count($physicians_query->posts);
        $postsPerPage = 6; // Set this value to preferred value (4, 6, 8, 10, 12). If you change the value, update the instruction text in the editor's JSON file.
        $postsCutoff = 9; // Set cutoff value. If you change the value, update the instruction text in the editor's JSON file.
        if($provider_count <= $postsCutoff) {
            $postsPerPage = $postsCutoff;
        }
        $postsCountClass = $postsPerPage;

        ?>
        <section class="uams-module bg-auto" id="providers">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title">Providers</span></h2>
                        <?php echo do_shortcode( '[uamswp_provider_ajax_filter providers="'. implode(",", $provider_ids) .'" ppp="'. $postsPerPage .'"]' ); ?>
                        <div class="card-list-container">
                            <div class="card-list card-list-doctors card-list-doctors-count-<?php echo $postsCountClass; ?>">
                                <?php 
                                    echo '<data id="provider_ids" data-postids="'. implode(',', $physicians_query->posts) .'"></data>';
                                    $p=0;
                                    if($provider_count > 0){
                                        while ($p < $postsPerPage && $physicians_query->have_posts()) : $physicians_query->the_post();
                                            $id = get_the_ID();
                                            include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
                                            $p++;
                                        endwhile;
                                    } else {
                                        echo 'No matching providers found. Try a different filter or resetting filters';
                                    }
                                    wp_reset_postdata();
                                ?>
                            </div>
                        </div>
                        <div class="more" style="<?php echo ($postsPerPage < $provider_count) ? '' : 'display:none;' ; ?>">
                            <button class="loadmore btn btn-primary <?php echo $provider_count; ?>" data-ppp="<?php echo $postsPerPage; ?>" aria-label="Load more providers">Load More</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php 
			if ( isset($_GET['_filter_region']) ) { ?>
			<script type="text/javascript">
				var days = 1; // Expiration value
				var date = new Date();
				date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    			document.cookie = "_filter_region=<?php echo htmlspecialchars($_GET['_filter_region']); ?>; expires="+date.toGMTString()+"; path=/; domain="+window.location.hostname;
			</script>
    <?php   }
    }
}
function uamswp_expertise_youtube() {
    $video = get_field('expertise_youtube_link');
    if( $video ) { ?>
        <?php if(function_exists('lyte_preparse')) {
            echo '<div class="alignwide">';
            echo lyte_parse( str_replace( 'https', 'httpv', $video ) ); 
            echo '</div>';
        } else {
            echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
            echo wp_oembed_get( $video ); 
            echo '</div>';
        } ?>
    <?php }
}
function uamswp_expertise_keywords() {
    $keywords = get_field('expertise_alternate_names');
    $keyword_text = '';
    if( $keywords ): 
        $i = 1;
        foreach( $keywords as $keyword ) { 
            if ( 1 < $i ) {
                $keyword_text .= '; ';
            }
            $keyword_text .= $keyword['text'];
            $i++;
        } 
        echo '<p class="text-callout text-callout-info">Also called: '. $keyword_text .'</p>';
    endif;
}
function uamswp_expertise_conditions_cpt() {
    global $show_conditions_section;
    global $conditions_cpt_query;
    $condition_heading_related_resource = false;
    $condition_heading_related_treatment = false;
    $condition_heading_treated = true;
    $condition_disclaimer = true;

    if( $show_conditions_section ) {
        include( UAMS_FAD_PATH . '/templates/loops/conditions-cpt-loop.php' );
    }
}
function uamswp_expertise_treatments_cpt() {
    global $show_treatments_section;
    global $treatments_cpt_query;
    $treatment_heading_related_resource = false;
    $treatment_heading_related_condition = false;
    $treatment_heading_performed = true;
    $treatment_disclaimer = true;

    if( $show_treatments_section ) {
        include( UAMS_FAD_PATH . '/templates/loops/treatments-cpt-loop.php' );
    }
}
function uamswp_expertise_locations() {
    global $show_locations_section;
    global $location_query;

    if ( $show_locations_section ) { ?>
        <section class="uams-module location-list bg-auto" id="locations">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title">Locations</span></h2>
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
function uamswp_expertise_associated() {
    global $show_related_aoe_section;
    global $expertise_query;

    if( $show_related_aoe_section ) { ?>
        <section class="uams-module link-list link-list-layout-split bg-auto" id="related-expertise" aria-labelledby="related-expertise-title">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6 heading">
						<div class="text-container">
							<h2 class="module-title" id="related-expertise-title"><span class="title">Related Areas of Expertise</span></h2>
						</div>
            		</div>
            		<div class="col-12 col-md-6 list">
						<ul>
						<?php
						while ( $expertise_query->have_posts() ) : $expertise_query->the_post();
							echo '<li class="item"><div class="text-container"><h3 class="h5"><a href="'.get_permalink().'" aria-label="Go to Area of Expertise page for ' . get_the_title() . '">';
							echo get_the_title();
                            echo '</a></h3>';
                            echo ( has_excerpt() ? '<p>' . wp_trim_words( get_the_excerpt(), 30, '&nbsp;&hellip;' ) . '</p>' : '' );
                            echo '</div></li>';
						endwhile;
						wp_reset_postdata(); ?>
						</ul>
					</div>
				</div>
			</div>
		</section>
	<?php 
    } // endif
}
function uamswp_expertise_header_metadata() { 
    $keywords = get_field('expertise_alternate_names');
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
function uamswp_expertise_podcast() {
    global $page_title;
    global $show_podcast_section;
    global $podcast_name;

    if ( $show_podcast_section ) {
        echo '<section class="uams-module podcast-list bg-auto" id="podcast">
        <script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">
        </script>
        <script type="text/javascript">
            radiomd_embedded_filtered_tag("uams","radiomd-embedded-filtered-tag",303,"' . $podcast_name . '");
        </script>
        <style type="text/css">
            #radiomd-embedded-filtered-tag iframe {
            width: 100%;
            border: none;
        }
        </style>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h2 class="module-title"><span class="title">UAMS Health Talk Podcast</span></h2>
                    <div class="module-body text-center">
                        <p class="lead">In the UAMS Health Talk podcast, experts from UAMS talk about a variety of health topics, providing tips and guidelines to help people lead healthier lives. Listen to the episode(s) featuring the topic of '. $page_title . '.</p>
                    </div>
                    <div class="content-width mt-8" id="radiomd-embedded-filtered-tag"></div>
                </div>
                <div class="col-12 more">
                    <p class="lead">Find other great episodes on other topics and from other UAMS providers.</p>
                    <div class="cta-container">
                        <a href="/podcast/" class="btn btn-primary" aria-label="More UAMS Health Talk podcast episodes">Listen to More Episodes</a>
                    </div>
                </div>
            </div>
        </div>
    </section>';
    }
}
function uamswp_expertise_resource() {
    global $page_title;
    global $show_related_resource_section;
    global $resources;
    global $resource_query;
    global $resource_postsPerPage;
    $resource_heading_related_pre = true; // "Related Resources"
    $resource_heading_related_post = false; // "Resources Related to __"
    $resource_heading_related_name = $page_title; // To what is it related?
    $resource_more_suppress = false; // Force div.more to not display
    $resource_more_key = '_resource_aoe';
    $resource_more_value = $post->post_name;
    if( $show_related_resource_section ) {
        include( UAMS_FAD_PATH . '/templates/blocks/clinical-resources.php' );
    }
}
function uamswp_list_child_expertise() {
    global $page_id;
    global $page_title;
    global $show_child_aoe_section;
    
    if ( $show_child_aoe_section ) { // If it's suppressed or none available, set to false
        $args =  array(
            "post_type" => "expertise",
            "post_status" => "publish",
            "post_parent" => $page_id,
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1, // We do not want to limit the post count
            'meta_query' => array(
                "relation" => "OR",
                array(
                    "key" => "hide_from_sub_menu",
                    "value" => "1",
                    "compare" => "!=",
                ),
            ),
        );
        $pages = New WP_Query ( $args );
        if ( $pages->have_posts() ) { ?>
            <section class="uams-module expertise-list bg-auto" id="sub-expertise" aria-labelledby="sub-expertise-title" >
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="module-title" id="sub-expertise-title"><span class="title">Areas Within <?php echo $page_title; ?></span></h2>
                            <div class="card-list-container">
                                <div class="card-list card-list-expertise">
                            <?php
                                while ( $pages->have_posts() ) : $pages->the_post();
                                    $id = get_the_ID(); 
                                    include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
                                endwhile;
                                wp_reset_postdata(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php
        }
    }
}
function uamswp_expertise_appointment() {
    global $show_appointment_section;
    
    if ( $show_appointment_section ) {
        if ( get_field('location_expertise') ) {
            $appointment_location_url = '#locations';
            $appointment_location_label = 'Go to the list of relevant locations';
        } else {
            $appointment_location_url = '/location/';
            $appointment_location_label = 'View a list of UAMS Health locations';
        } ?>
        <section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>Make an Appointment</h2>
                        <p>Request an appointment by <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_label; ?>" data-itemtitle="Contact a clinic directly">contacting a clinic directly</a> or by calling the UAMS&nbsp;Health appointment line at <a href="tel:501-686-8000" class="no-break" data-itemtitle="Call the UAMS Health appointment line">(501) 686-8000</a>.</p>
                    </div>
                </div>
            </div>
        </section>
    <?php }
}
function uamswp_expertise_jump_links() {
    global $page_title;
    global $jump_link_count_min;
    global $jump_link_count;
    global $show_appointment_section;
    global $show_podcast_section;
    global $show_child_aoe_section;
    global $show_related_resource_section;
    global $show_conditions_section;
    global $show_treatments_section;
    global $show_providers_section;
    global $show_locations_section;
    global $show_related_aoe_section;
    global $show_jump_links_section;
    
    // Begin Jump Links Section
    if ( $show_jump_links_section ) { ?>
        <nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
            <h2>Contents</h2>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse inner-container" id="jump-link-nav">
                <ul class="nav navbar-nav">
                    <?php if ( $show_podcast_section ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#podcast" title="Jump to the section of this page about UAMS Health Talk Podcast">Podcast</a>
                        </li>
                    <?php } ?>
                    <?php if ($show_child_aoe_section) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#sub-expertise" title="Jump to the section of this page about Areas Within Cancer Care">Areas Within <?php echo $page_title; ?></a>
                        </li>
                    <?php } ?>
                    <?php if ( $show_related_resource_section ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#related-resources" title="Jump to the section of this page about Resources">Resources</a>
                        </li>
                    <?php } ?>
                    <?php if ( $show_conditions_section ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#conditions" title="Jump to the section of this page about Conditions Treated">Conditions</a>
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
                    <?php if ( $show_related_aoe_section ) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#related-expertise" title="Jump to the section of this page about Related Areas of Expertise">Related Areas</a>
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
genesis();