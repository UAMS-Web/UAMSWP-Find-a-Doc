<?php 
/*
 *  Get ACF fields to use for meta data
 *  Add description from physician short description or full description * 
 */
$degrees = get_field('physician_degree',$post->ID);
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
$languages = get_field('physician_languages',$post->ID);
$language_list = '';
$i = 1;
if ( $languages ) {
    foreach( $languages as $language ):
        $language_name = get_term( $language, 'languages');
        $language_list .= $language_name->name;
        if( count($languages) > $i ) {
            $language_list .= ", ";
        }
        $i++;
    endforeach;
}
$full_name = get_field('physician_first_name',$post->ID) .' ' .(get_field('physician_middle_name',$post->ID) ? get_field('physician_middle_name',$post->ID) . ' ' : '') . get_field('physician_last_name',$post->ID) . (get_field('physician_pedigree',$post->ID) ? '&nbsp;' . get_field('physician_pedigree',$post->ID) : '') .  ( $degree_list ? ', ' . $degree_list : '' );
$short_name = get_field('physician_prefix',$post->ID) ? get_field('physician_prefix',$post->ID) .' ' .get_field('physician_last_name',$post->ID) : get_field('physician_first_name',$post->ID) .' ' .(get_field('physician_middle_name',$post->ID) ? get_field('physician_middle_name',$post->ID) . ' ' : '') . get_field('physician_last_name',$post->ID) . (get_field('physician_pedigree',$post->ID) ? '&nbsp;' . get_field('physician_pedigree',$post->ID) : '');
$excerpt = get_field('physician_short_clinical_bio',$post->ID);
$bio = get_field('physician_clinical_bio',$post->ID);
$eligible_appt = get_field('physician_eligible_appointments',$post->ID);
if (empty($excerpt)){
    if ($bio){
        $excerpt = mb_strimwidth(wp_strip_all_tags($bio), 0, 155, '...');
    }
}
function sp_titles_desc($html) {
    global $excerpt;
	$html = $excerpt; 
	return $html;
}
add_filter('seopress_titles_desc', 'sp_titles_desc');
function sp_titles_title($html) { 
    global $full_name;
	//you can add here all your conditions as if is_page(), is_category() etc.. 
	$html = $full_name . ' | ' . get_bloginfo( "name" );
	return $html;
}
add_filter('seopress_titles_title', 'sp_titles_title');

get_header();

while ( have_posts() ) : the_post(); ?>

<main class="doctor-item">
        <section class="container-fluid p-0 p-xs-8 p-sm-10 doctor-info bg-white">
            <div class="row mx-0 mx-xs-n4 mx-sm-n8">
                <div class="col-12 col-xs p-4 py-xs-0 px-xs-4 px-sm-8 order-2 text">
                    <h1 class="page-title">
                        <span class="name"><?php echo $full_name; ?></span>
                        <?php 
                        $phys_title = get_field('physician_title');
                        if ($phys_title && !empty($phys_title)) { ?>
                            <span class="subtitle"><?php echo (get_field('physician_title') ? get_term( get_field('physician_title'), 'clinical_title' )->name : ''); ?></span>
                        <?php } ?>
                    </h1>
                    <h2 class="sr-only">Overview</h2>
                    <dl>
                    <?php // Display service line
                    $service_line = get_field('physician_service_line');
                    if ($service_line && !empty($service_line)) { ?>
                        <dt>Service Line</dt>
                        <dd><?php echo ($service_line ? get_term( $service_line, 'service_line' )->name : ''); ?></dd>                 
                    <?php } ?>
                    <?php  // Display if they will provide second opinions
                        $second_opinion = get_field('physician_second_opinion');
                        if ($second_opinion) { ?>
                        <dt>Provides Second Opinion</dt>
                        <dd>Yes</dd>
                    <?php } ?>
                    <?php // Display all patient types
                        $patients = get_field('physician_patient_types');
                        if( $patients ): 
                        ?>
                        <dt>Patient Type<?php echo( count($patients) > 1 ? 's' : '' );?></dt>
                            <?php foreach( $patients as $patient ): ?>
                                <?php $patient_name = get_term( $patient, 'patient_type');
                                    echo '<dd>' . $patient_name->name . '</dd>';
                                ?>
                            <?php endforeach; ?>
                    <?php endif; ?>
                    <?php // Display all languages
                        if( $languages && $language_list == 'English') { 
                        ?>
                        <dt class="sr-only">Language</dt>
                        <?php echo '<dd class="sr-only">' . $language_list . '</dd>';?>
                    <?php } else { ?>
                        <dt>Language<?php echo( count($languages) > 1 ? 's' : '' );?></dt>
                        <?php echo '<dd>' . $language_list . '</dd>';?>
                    <?php } //endif ?>
                    </dl>
                    <?php
                        if(get_field('physician_npi')) {

                            $npi =  get_field( 'physician_npi' );
                            $request = wp_nrc_cached_api( $npi );

                            $data = json_decode( $request );

                            if( ! empty( $data ) ) {

                                $rating_valid = $data->valid;

                                if ( $rating_valid ){
                                    echo '<div class="rating" itemprop="aggregateRating" itemscope="" itemtype="https://schema.org/AggregateRating" aria-label="Patient Rating">';
                                        echo '<div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: '. floatval($data->profile->averageStarRatingStr)/5 * 100 .'%;"></div></div>';
                                        echo '<div class="ratings-score">'. $data->profile->averageRatingStr .'<span class="sr-only"> out of 5</span></div>';
                                        echo '<div class="w-100"></div>';
                                        echo '<a href="#ratings" aria-label="Jump to Patient Ratings & Reviews">';
                                        echo '<div class="ratings-count-lg">'. $data->profile->reviewcount .' Patient Satisfaction Ratings</div>';
                                        echo '<div class="ratings-comments-lg">'. $data->profile->bodycount .' comments</div>';
                                        echo '</a>';
                                    echo '</div>';
                                } else { ?>
                                    <div class="rating" aria-label="Patient Rating">
                                    <div class="ratings-count">No ratings - <a data-toggle="modal" data-target="#why_not_modal">Why Not?</a></div>
                                    <!-- <div><a href="#" class="js-modal" data-modal-close-text="Close" data-modal-close-title="Close this window" data-modal-content-id="why_not_modal" data-modal-title="Why Not?">Why Not?</a></div> -->
                                    </div>
                                <?php
                                }
                            }
                        } else { ?>
                            <div class="rating" aria-label="Patient Rating">
                                <div class="ratings-count">No ratings - <a data-toggle="modal" data-target="#why_not_modal">Why Not?</a></div>
                                <!-- <div><a href="#" class="js-modal" data-modal-close-text="Close" data-modal-close-title="Close this window" data-modal-content-id="why_not_modal" data-modal-title="Why Not?">Why Not?</a></div> -->
                            </div>
                    <?php } ?>
                    <!-- <div class="rating" aria-label="Patient Rating">
                        <div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 94%;"></div></div>
                        <div class="ratings-score">4.7<span class="sr-only"> out of 5</span></div>
                        <div class="w-100"></div>
                        <a href="#ratings" aria-label="Jump to Patient Ratings & Reviews">
                            <div class="ratings-count-lg">595 Patient Satisfaction Ratings</div>
                            <div class="ratings-comments-lg">100 comments</div>
                        </a>
                    </div> -->
                    <div id="why_not_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="why_not_modal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="WhyNotTitle">Why Not?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>There is no publicly available rating for this medical professional for one of two reasons: 1) he or she does not see patients or 2) he or she sees patients but has not yet received the minimum number of Patient Satisfaction Reviews. To be eligible for display, we require a minimum of 30 surveys. This ensures that the rating is statistically reliable and a true reflection of patient satisfaction.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        $l = 1;
                        $locations = get_field('physician_locations');
                        if( $locations ): ?>
                            <?php if ($eligible_appt) { ?>
                                <h2>Primary Appointment Location</h2>
                            <?php } else { ?>
                                <h2>Primary Location</h2>
                            <?php } // endif ?>
                            <?php foreach( $locations as $location ):
                                    if ( 2 > $l ){ ?>
                                <p><strong><?php echo get_the_title( $location ); ?></strong><br />
                                <?php echo get_field( 'location_address_1', $location ); ?><br/>
                                <?php echo ( get_field( 'location_address_2', $location ) ? get_field( 'location_address_2', $location ) . '<br/>' : ''); ?>
                                <?php echo get_field( 'location_city', $location ); ?>, <?php echo get_field(' location_state', $location ); ?> <?php echo get_field( 'location_zip', $location ); ?>
                                <?php $map = get_field( 'location_map', $location ); ?>
                                <!-- <br /><a class="uams-btn btn-red btn-sm btn-external" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank">Directions</a> -->
                                </p>
                                <div class="btn-container">
                                    <a class="btn btn-primary" href="<?php the_permalink( $location ); ?>">
                                        View Location
                                    </a>
                                    <a class="btn btn-outline-primary" href="#locations" aria-label="Jump to list of locations for this doctor">
                                        View All Locations
                                    </a>
                                </div>
                                <?php $l++;
                                } ?>
							<?php endforeach;
								// wp_reset_postdata(); ?>
						<?php endif; ?> 
                </div>
                <?php if ( has_post_thumbnail() ) { ?>
                <div class="col-12 col-xs px-0 px-xs-4 px-sm-8 order-1 image">
                    <picture>
                    <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 778, 1038, 'center', 'center'); ?>"
                            media="(min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2), 
                            (min-width: 1200px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 389, 519, 'center', 'center'); ?>"
                            media="(min-width: 1200px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 612, 816, 'center', 'center'); ?>"
                            media="(min-width: 992px) and (-webkit-min-device-pixel-ratio: 2), 
                            (min-width: 992px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 306, 408, 'center', 'center'); ?>"
                            media="(min-width: 992px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 364, 486, 'center', 'center'); ?>"
                            media="(min-width: 768px) and (-webkit-min-device-pixel-ratio: 2), 
                            (min-width: 768px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 182, 243, 'center', 'center'); ?>"
                            media="(min-width: 768px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 172, 230, 'center', 'center'); ?>"
                            media="(min-width: 576px) and (-webkit-min-device-pixel-ratio: 2), 
                            (min-width: 576px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 86, 115, 'center', 'center'); ?>"
                            media="(min-width: 576px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 760, 1013, 'center', 'center'); ?>"
                            media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), 
                            (min-width: 1px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 380, 507, 'center', 'center'); ?>"
                            media="(min-width: 1px)">
                        <img src="<?php echo image_sizer(get_post_thumbnail_id(), 778, 1038, 'center', 'center'); ?>" alt="<?php echo $full_name; ?>" />
                        <?php } else { ?>
                            <?php the_post_thumbnail( 'large',  array( 'itemprop' => 'image' ) ); ?>
                        <?php } //endif ?>
                    </picture>
                </div>
                <?php } //endif ?>
            </div>
        </section>
        <?php if ($eligible_appt): ?>
        <?php 
            $refer_req = get_field('physician_referral_required');
            $accept_new = get_field('physician_accepting_patients');
            $appointment_phone_name = 'the main UAMS appointment line'; // default (UAMS)
            $appointment_phone = '5016868000'; // default (UAMS)
        
            // Portal
            if ( get_field('physician_portal')) {
                $portal = get_term(get_field('physician_portal'), "portal");
                $portal_slug = $portal->slug;
                $portal_name = $portal->name;
                $portal_content = get_field('portal_content', $portal);
                $portal_link = get_field('portal_url', $portal);
                if ($portal_link) {
                    $portal_url = $portal_link['url'];
                    $portal_link_title = $portal_link['title'];
                }

                if ($portal && $portal_slug !== "_none") {
                    $show_portal = true;
                }
                if ($portal_slug == "ach-mychart") {
                    $appointment_phone_name = 'the main Arkansas Children\'s Hospital appointment line';
                    $appointment_phone = '5013641202';
                } elseif ($portal_slug == "my-healthevet") {
                    $appointment_phone_name = 'the main Central Arkansas Veterans Healthcare System appointment line';
                    $appointment_phone = '5012573999';
                }
            }
            
            $appointment_phone_tel = preg_replace('/^(\+?\d)?(\d{3})(\d{3})(\d{4})$/', '$2-$3-$4', $appointment_phone);
            $appointment_phone_text = preg_replace('/^(\+?\d)?(\d{3})(\d{3})(\d{4})$/', '($2) $3-$4', $appointment_phone);
        ?>
        <section class="uams-module cta-bar cta-bar-1 bg-auto">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>Make an Appointment With <?php echo $short_name; ?></h2>
                        <?php if ($refer_req && $accept_new && $show_portal) { ?>
                            <p>Appointments for new patients are by referral only. Please contact your primary care doctor or visit one of our <a href="/physicians/?fwp_primary_care=1&fwp_searchable=1">Center for Primary Care doctors</a>.</p>
                            <p>Existing patients can either <a href="<?php echo $portal_link; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>, <a href="#locations" aria-label="Jump to list of locations for this doctor">contact the clinic directly</a> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } elseif ($refer_req && $accept_new) { ?>
                            <p>Appointments for new patients are by referral only. Please contact your primary care doctor or visit one of our <a href="/physicians/?fwp_primary_care=1&fwp_searchable=1">Center for Primary Care doctors</a>.</p>
                            <p>Existing patients can make an appointment by <a href="#locations" aria-label="Jump to list of locations for this doctor">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } elseif ($accept_new && $show_portal) { ?>
                            <p>New patients can make an appointment by <a href="#locations" aria-label="Jump to list of locations for this doctor">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <p>Existing patients also have the option to <a href="<?php echo $portal_link; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>.</p>
                        <?php } elseif ($accept_new) { ?>
                            <p>New and existing patients can make an appointment by <a href="#locations" aria-label="Jump to list of locations for this doctor">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } elseif ($show_portal) { ?>
                            <p>This physician is not currently accepting new patients.</p>
                            <p>Existing patients can either <a href="<?php echo $portal_link; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>, <a href="#locations" aria-label="Jump to list of locations for this doctor">contact the clinic directly</a> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } else { ?>
                            <p>This physician is not currently accepting new patients.</p>
                            <p>Existing patients can make an appointment by <a href="#locations" aria-label="Jump to list of locations for this doctor">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <?php if(get_field('physician_clinical_bio')|| !empty (get_field('physician_youtube_link')) || !empty (get_field('physician_awards')) || get_field('physician_additional_info')): ?>
        <section class="uams-module bg-auto">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="module-title">About <?php echo $short_name; ?>
                        </h2>
                        <div class="module-body">
                            <?php echo get_field('physician_clinical_bio'); ?>
                            <?php if(get_field('physician_youtube_link')) { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <?php echo wp_oembed_get( get_field( 'physician_youtube_link' ) ); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <?php // load all 'conditions' terms for the post
	        $title_append = ' by ' . $short_name;
            $conditions = get_field('physician_conditions');

            // we will use the first term to load ACF data from
            if( $conditions ):
                include( UAMS_FAD_PATH . '/templates/loops/conditions-loop.php' );
            endif; 
             // load all 'treatments' terms for the post
            $treatments = get_field('physician_treatments');

            // we will use the first term to load ACF data from
        if( $treatments ):
            include( UAMS_FAD_PATH . '/templates/loops/treatments-loop.php' );
        endif;
        $expertises =  get_field('physician_expertise');
        if( $expertises ): ?>
            <section class="container-fluid p-8 p-sm-10 expertise-list bg-auto" id="expertise">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><?php echo $short_name; ?>'s Areas of Expertise</h2>
                        <div class="card-list-container">
                            <div class="card-list">
                            <?php foreach( $expertises as $expertise ) {
                                $id = $expertise; 
                                include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
                            } ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php 
        endif;
        ?>
        <?php if(get_field('physician_academic_bio') || get_field('physician_academic_appointment') || get_field('physician_education') || get_field('physician_boards')): ?>
        <section class="container-fluid p-8 p-sm-10 bg-auto">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="module-title"><?php echo $short_name; ?>'s Academic Background</h2>
                    <div class="module-body">
                        <?php echo get_field('physician_academic_bio'); ?>
                        <?php
                            // $academic_appointments = get_field('physician_academic_appointment');
                            if( have_rows('physician_academic_appointment') ): ?>
                                <h3>Academic Appointments</h3>
                                <dl>
                                <?php while( have_rows('physician_academic_appointment') ): the_row(); ?>
                                <?php $department = get_term( get_sub_field('department'), 'academic_department' ); ?>
                                    <dt><?php echo $department->name; ?></dt>
                                    <dd><?php the_sub_field('academic_title'); ?></dd>
                                <?php endwhile; ?>
                                </dl>
                        <?php endif; ?>
                        <?php
                            if( have_rows('physician_education') ): ?>
                                <h3>Education</h3>
                                <dl>
                                <?php while( have_rows('physician_education') ): the_row();
                                    $school_name = get_term( get_sub_field('school'), 'schools');
                                    $education_type = get_term( get_sub_field('education_type'), 'educationtype');
                                ?>
                                    <dt><?php echo $education_type->name; ?></dt>
                                    <dd><?php echo $school_name->name; ?><?php echo (get_sub_field('description') ? '<br /><span class="subtitle">' . get_sub_field('description') .'</span>' : ''); ?></dd>
                                <?php endwhile; ?>
                                </dl>
                        <?php endif;
                            $boards = get_field( 'physician_boards' );
                            if( ! empty( $boards ) ): ?>
                        <h3>Professional Certifications</h3>
                        <ul>
                        <?php foreach ( $boards as $board ) :
                            $board_name = get_term( $board, 'boards'); ?>
                            <li><?php echo $board_name->name; ?></li>
                            <?php // }; ?>
                        <?php endforeach; ?>
                        </ul>
                        <?php endif;
                            $associations = get_field( 'physician_associations' );
                            if( ! empty( $associations ) ): ?>
                        <h3>Associations</h3>
                        <ul>
                        <?php foreach ( $associations as $association ) :
                            $association_name = get_term( $association, 'associations'); ?>
                            <li><?php echo $association_name->name; ?></li>
                            <?php // }; ?>
                        <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <?php 
        $publications = get_field('physician_select_publications');

        if( !empty(get_field('physician_research_bio')) || !empty(get_field('physician_research_interests')) || !empty ( $publications ) || get_field('physician_pubmed_author_id') || get_field('physician_research_profiles_link') ): ?>
        <section class="container-fluid p-8 p-sm-10 bg-auto">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="module-title"><?php echo $short_name; ?>'s Research</h2>
                    <div class="module-body">
                        <?php
                            if(get_field('physician_research_bio'))
                            {
                                echo get_field('physician_research_bio');
                            }
                        ?>
                        <?php
                            if(get_field('physician_research_interests'))
                            { ?>
                            <h3>Research Interests</h3>
                        <?php
                                echo get_field('physician_research_interests');
                            }
                        ?>
                        <?php
                            if( !empty ( $publications ) ): ?>
                        <h3>Selected Publications</h3>
                        <ul>
                        <?php foreach( $publications as $publication ): ?>
                            <li><?php echo $publication['pubmed_information']; ?></li>
                        <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        <?php if( get_field('physician_pubmed_author_id') ): ?>
                            <?php
                                $pubmedid = trim(get_field('physician_pubmed_author_id'));
                                $pubmedcount = (get_field('pubmed_author_number') ? get_field('pubmed_author_number') : '3');
                            ?>
                            <h3>Latest Publications</h3>
                            <p>Publications listed below are automatically derived from MEDLINE/PubMed and other sources, which might result in incorrect or missing publications.</p>
                            <?php echo do_shortcode( '[pubmed terms="' . urlencode($pubmedid) .'%5BAuthor%5D" count="' . $pubmedcount .'"]' ); ?>
                        <?php endif; ?>
                        <?php if( get_field('physician_research_profiles_link') ): ?>
                            <h3>UAMS Research Profile</h3>
                            <p>Each UAMS faculty member has a research profile page that includes biographical and contact information, a list of their most recent grant activity and a list of their PubMed publications.</p>
                            <p><a class="btn btn-outline-primary" href="<?php echo get_field('physician_research_profiles_link'); ?>">View <?php echo $short_name; ?>'s research profile</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <?php if( $locations ): ?>
        <section class="container-fluid p-8 p-sm-10 location-list bg-auto" id="locations">
            <div class="row">
                <div class="col-12">
                    <h2 class="module-title">Locations Where <?php echo $short_name; ?> Practices</h2>
                    <div class="card-list-container">
                        <div class="card-list">
                        <?php $l = 1; ?>
                        <?php foreach( $locations as $location ): ?>
                            <div class="card">
                                <?php if ( has_post_thumbnail($location) ) { ?>
                                <?php echo get_the_post_thumbnail($location, 'aspect-16-9-small', ['class' => 'card-img-top']); ?>
                                <?php } else { ?>
                                <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" alt="<?php echo get_the_title( $location ); ?>" class="card-image-top" />
                                <?php } ?>
                                <div class="card-body">
                                        <h3 class="card-title">
                                            <span class="name"><?php echo get_the_title( $location ); ?></span>
                                            <?php  if ( 1 == $l ) { ?>
                                                <span class="subtitle">Primary Location</span>
                                            <?php } ?>
                                        </h3>
                                    <p class="card-text"><?php echo get_field('location_address_1', $location ); ?><br/>
                                    <?php echo ( get_field('location_address_2', $location ) ? get_field('location_address_2', $location ) . '<br/>' : ''); ?>
                                    <?php echo get_field('location_city', $location ); ?>, <?php echo get_field('location_state', $location ); ?> <?php echo get_field('location_zip', $location); ?></p>
                                    <a href="<?php the_permalink(  $location ); ?>" class="btn btn-primary stretched-link" aria-label="Go to location page for <?php echo get_the_title( $location ); ?>">View Location</a>
                                </div>
                            </div>
                            <?php $l++; ?>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?> 
        <?php if ( $rating_valid ) : ?>
        <section class="container-fluid p-8 p-sm-10 ratings-and-reviews bg-auto" id="ratings">
            <div class="row">
                <div class="col-12">
                    <h2 class="module-title">Patient Ratings &amp; Reviews</h2>
                    <div class="card overall-ratings text-center">
                        <div class="card-body">
                            <h3 class="sr-only">Average Ratings</h3>
                            <dl>
                                <?php
                                $questionRatings = $data->profile->questionRatings;
                                foreach( $questionRatings as $questionRating ): ?>
                                <dt><?php echo $questionRating->question; ?></dt>
                                <dd>
                                    <div class="rating" aria-label="Patient Rating">
                                        <div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: <?php echo floatval($questionRating->averageRatingStr)/5 * 100; ?>%;"></div></div>
                                        <div class="ratings-score-lg"><?php echo $questionRating->averageRatingStr; ?><span class="sr-only"> out of 5</span></div>
                                    </div>
                                </dd>
                                <?php endforeach; ?>
                            </dl>
                        </div>
                        <div class="card-footer bg-transparent text-muted small">
                            <p>Overall: <?php echo $data->profile->averageRatingStr; ?> out of 5</p>
                            <p>(<?php echo $data->profile->reviewBodyCountStr; ?>)</p>
                        </div>
                    </div>
                    <?php 
                    $reviews = $data->reviews;
                    // if ( $reviews ) : ?>
                    <?php //print_r($data); ?>
                    <h3 class="sr-only">Individual Reviews</h3>
                    <div class="card-list-container">
                        <div class="card-list">
                            <?php foreach( $reviews as $review ): ?>
                            <div class="card">
                                <div class="card-header bg-transparent">
                                    <div class="rating rating-center" aria-label="Average Rating">
                                        <div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: <?php echo floatval($review->rating)/5 * 100; ?>%;"></div></div>
                                        <div class="ratings-score-lg" itemprop="ratingValue"><?php echo $review->rating; ?><span class="sr-only"> out of 5</span></div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h4 class="sr-only">Comment</h4>
                                    <p class="card-text"><?php echo $review->bodyForDisplay; ?></p>
                                </div>
                                <div class="card-footer bg-transparent text-muted small">
                                    <h4 class="sr-only">Date</h4>
                                    <?php echo $review->formattedReviewDate; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="view-more text-center mt-8 mt-sm-10">
                        <button class="btn btn-secondary" data-toggle="modal" data-target="#MoreReviews" aria-label="Load more individual reviews">View More</a>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="MoreReviews" tabindex="-1" role="dialog" aria-labelledby="More_Reviews_Modal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="MoreReviews">More Reviews</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="ds-comments" data-ds-pagesize="10"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <script src="https://transparency.nrchealth.com/widget/v2/uams/npi/1841276169/lotw.js" async></script>                           
                    <?php // endif; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <!-- <section class="container-fluid p-8 p-sm-10 news-list bg-auto">
            <div class="row">
                <div class="col-12">
                    <h2 class="module-title">Latest News for C. Lowry Barnes, M.D.</h2>
                    <div class="card-list-container">
                        <div class="card-list">
                            <div class="card">
                                <img srcset="https://picsum.photos/434/244?image=1066 1x, https://picsum.photos/868/488?image=1066 2x" src="https://picsum.photos/434/244?image=1066" class="card-img-top" alt="Image description or Story title">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        <span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
                                    </h3>
                                    <p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
                                    <a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
                                </div>
                            </div>
                            <div class="card">
                                <img srcset="https://picsum.photos/434/244?image=348 1x, https://picsum.photos/868/488?image=348 2x" src="https://picsum.photos/434/244?image=348" class="card-img-top" alt="Image description or Story title">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        <span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
                                    </h3>
                                    <p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
                                    <a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
                                </div>
                            </div>
                            <div class="card">
                                <img srcset="https://picsum.photos/434/244?image=823 1x, https://picsum.photos/868/488?image=823 2x" src="https://picsum.photos/434/244?image=823" class="card-img-top" alt="Image description or Story title">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        <span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
                                    </h3>
                                    <p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
                                    <a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <?php if ($eligible_appt): ?>
            <section class="uams-module cta-bar cta-bar-1 bg-auto">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2>Make an Appointment With <?php echo $short_name; ?></h2>
                            <?php if ($refer_req && $accept_new && $show_portal) { ?>
                                <p>Appointments for new patients are by referral only. Please contact your primary care doctor or visit one of our <a href="/physicians/?fwp_primary_care=1&fwp_searchable=1">Center for Primary Care doctors</a>.</p>
                                <p>Existing patients can either <a href="<?php echo $portal_link; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>, <a href="#locations" aria-label="Jump to list of locations for this doctor">contact the clinic directly</a> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } elseif ($refer_req && $accept_new) { ?>
                                <p>Appointments for new patients are by referral only. Please contact your primary care doctor or visit one of our <a href="/physicians/?fwp_primary_care=1&fwp_searchable=1">Center for Primary Care doctors</a>.</p>
                                <p>Existing patients can make an appointment by <a href="#locations" aria-label="Jump to list of locations for this doctor">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } elseif ($accept_new && $show_portal) { ?>
                                <p>New patients can make an appointment by <a href="#locations" aria-label="Jump to list of locations for this doctor">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                                <p>Existing patients also have the option to <a href="<?php echo $portal_link; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>.</p>
                            <?php } elseif ($accept_new) { ?>
                                <p>New and existing patients can make an appointment by <a href="#locations" aria-label="Jump to list of locations for this doctor">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } elseif ($show_portal) { ?>
                                <p>This physician is not currently accepting new patients.</p>
                                <p>Existing patients can either <a href="<?php echo $portal_link; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>, <a href="#locations" aria-label="Jump to list of locations for this doctor">contact the clinic directly</a> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } else { ?>
                                <p>This physician is not currently accepting new patients.</p>
                                <p>Existing patients can make an appointment by <a href="#locations" aria-label="Jump to list of locations for this doctor">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>



<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>