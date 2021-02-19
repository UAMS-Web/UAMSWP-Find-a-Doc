<?php
/*
 *
 *  Template Name: Services
 *  Designed for services single
 *
 */
function uamswp_fad_title($html) { 

	//you can add here all your conditions as if is_page(), is_category() etc.. 
	$html = get_the_title() . ' | ' . get_bloginfo( "name" );
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

add_filter( 'genesis_entry_content', 'uamswp_expertise_keywords', 8 );
add_action( 'genesis_entry_content', 'uamswp_expertise_youtube', 12 );
add_action( 'genesis_after_entry', 'uamswp_expertise_podcast', 6 );
add_action( 'genesis_after_entry', 'uamswp_list_child_expertise', 8 );
add_action( 'genesis_after_entry', 'uamswp_expertise_conditions_cpt', 10 );
add_action( 'genesis_after_entry', 'uamswp_expertise_treatments_cpt', 12 );
add_action( 'genesis_after_entry', 'uamswp_expertise_physicians', 14 );
add_action( 'genesis_after_entry', 'uamswp_expertise_locations', 16 );
add_action( 'genesis_after_entry', 'uamswp_expertise_associated', 20 );
add_action( 'genesis_after_entry', 'uamswp_expertise_appointment', 22 );
add_action( 'wp_head', 'uamswp_expertise_header_metadata' );

function uamswp_expertise_physicians() {
    $physicians = get_field( "physician_expertise" );
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
        if($physicians_query->have_posts()) {   
        ?>
        <section class="uams-module bg-auto" id="doctors">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title">Providers</h2>
                        <div class="card-list-container">
                            <div class="card-list card-list-doctors card-list-doctors-count-<?php echo $postsCountClass; ?>">
                                <?php 
                                    while ($physicians_query->have_posts()) : $physicians_query->the_post();
                                        $id = get_the_ID();
                                        include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
                                    endwhile;
                                    wp_reset_postdata();
                                ?>
                            </div>
                        </div>
                        <?php if ($postsPerPage !== -1) { ?>
                        <div class="more">
                            <button class="loadmore btn btn-primary" data-posttype="post" data-postids="<?php echo(implode(',', $physicians)); ?>" data-ppp="<?php echo $postsPerPage; ?>" data-postcount="<?php echo $physicians_query->found_posts; ?>" aria-label="Load more providers">Load More</button>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php }
    }
}
function uamswp_expertise_youtube() {
    if(get_field('expertise_youtube_link')) { ?>
        <div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">
            <?php echo wp_oembed_get( get_field( 'expertise_youtube_link' ) ); ?>
        </div>
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
    if( $conditions_cpt && $conditions_cpt_query->posts ):
        include( UAMS_FAD_PATH . '/templates/loops/conditions-cpt-loop.php' );
    endif;
}
function uamswp_expertise_treatments_cpt() {
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
    if( $treatments_cpt && $treatments_cpt_query->posts ):
        include( UAMS_FAD_PATH . '/templates/loops/treatments-cpt-loop.php' );
    endif; 
}
function uamswp_expertise_locations() {
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
        if( $locations && $location_query->have_posts() ): ?>
            <section class="uams-module location-list bg-auto" id="locations">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="module-title">Locations</h2>
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
        endif;
    }
}
function uamswp_expertise_associated() {
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
    if( $expertises && $expertise_query->have_posts() ): ?>
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
    endif;
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
    $podcast_name = get_field('expertise_podcast_name');
    if ($podcast_name) {
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
                        <p class="lead">In the UAMS Health Talk podcast, experts from UAMS talk about a variety of health topics, providing tips and guidelines to help people lead healthier lives. Listen to the episode(s) featuring the topic of '. get_the_title() . '.</p>
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
function uamswp_list_child_expertise() {
    $page_id = get_the_ID();
    if ( !( get_post_meta( $page_id, 'hide_sub_areas_of_expertise', true) ) && ( 0 != count( get_pages( array( 'child_of' => $page_id, 'post_type' => 'expertise' ) ) ) ) ) { // If it's suppressed or none available, set to false
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
                            <h2 class="module-title" id="sub-expertise-title">Areas Within <?php echo get_the_title(); ?> <?php echo $hide_menu; ?></h2>
                            <div class="card-list-container">
                                <div class="card-list">
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
    if ( get_field('expertise_locations') ) {
        $appointment_location_url = '#locations';
    } else {
        $appointment_location_url = '/location/';
    } ?>
    <section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h2>Make an Appointment</h2>
                    <p>Request an appointment by <a href="<?php echo $appointment_location_url; ?>">contacting a clinic directly</a> or by calling the UAMS&nbsp;Health appointment line at <a href="tel:501-686-8000" class="no-break">(501) 686-8000</a>.</p>
                </div>
            </div>
        </div>
    </section>
    <?php
}
genesis();