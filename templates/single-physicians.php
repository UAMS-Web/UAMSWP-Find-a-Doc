<?php 
/*
 *  Get ACF fields to use for meta data
 *  Add description from provider short description or full description * 
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
$language_count = 0;
if ($languages) {
    $language_count = count($languages);
}
$language_list = '';
$i = 1;
if ( $languages ) {
    foreach( $languages as $language ):
        $language_name = get_term( $language, 'language');
        $language_list .= $language_name->name;
        if( $language_count > $i ) {
            $language_list .= ", ";
        }
        $i++;
    endforeach;
}

$prefix = get_field('physician_prefix',$post->ID);
$full_name = get_field('physician_first_name',$post->ID) .' ' .(get_field('physician_middle_name',$post->ID) ? get_field('physician_middle_name',$post->ID) . ' ' : '') . get_field('physician_last_name',$post->ID) . (get_field('physician_pedigree',$post->ID) ? '&nbsp;' . get_field('physician_pedigree',$post->ID) : '') .  ( $degree_list ? ', ' . $degree_list : '' );
$medium_name = ($prefix ? $prefix .' ' : '') . get_field('physician_first_name',$post->ID) .' ' .(get_field('physician_middle_name',$post->ID) ? get_field('physician_middle_name',$post->ID) . ' ' : '') . get_field('physician_last_name',$post->ID);
$short_name = $prefix ? $prefix .' ' .get_field('physician_last_name',$post->ID) : get_field('physician_first_name',$post->ID) .' ' .(get_field('physician_middle_name',$post->ID) ? get_field('physician_middle_name',$post->ID) . ' ' : '') . get_field('physician_last_name',$post->ID) . (get_field('physician_pedigree',$post->ID) ? '&nbsp;' . get_field('physician_pedigree',$post->ID) : '');
$excerpt = get_field('physician_short_clinical_bio',$post->ID);
$phys_title = get_field('physician_title',$post->ID);
$phys_title_name = get_term( $phys_title, 'clinical_title' )->name;
$vowels = array('a','e','i','o','u');
if (in_array(strtolower($phys_title_name)[0], $vowels)) { // Defines a or an, based on whether clinical title starts with vowel
    $phys_title_indef_article = 'an';
} else {
    $phys_title_indef_article = 'a';
}
if ( substr($short_name, -1) == 's' ) { // Defines a or an, based on whether clinical title starts with vowel
    $short_name_possessive = $short_name . '\'';
} else {
    $short_name_possessive = $short_name . '\'s';
}
$bio = get_field('physician_clinical_bio',$post->ID);
$eligible_appt = get_field('physician_eligible_appointments',$post->ID);
// Check for valid locations
$locations = get_field('physician_locations',$post->ID);
$location_valid = false;
foreach( $locations as $location ) {
    if ( get_post_status ( $location ) == 'publish' ) {
        $location_valid = true;
        $break;
    }
}
// Get number of valid locations
$location_count = 0;
if( $locations && $location_valid ) {
    foreach( $locations as $location ) {
        if ( get_post_status ( $location ) == 'publish' ) {
            $location_count++;
        }
    } // endforeach
}
// Get primary appointment location name
$l = 1;
if( $locations && $location_valid ) {
    foreach( $locations as $location ) {
        if ( 2 > $l ){
            if ( get_post_status ( $location ) == 'publish' ) {
                $primary_appointment_title = get_the_title( $location );
                $primary_appointment_url = get_the_permalink( $location );
                $l++;
            }
        }
    } // endforeach
}

// Set meta description
if (empty($excerpt)){
    if ($bio){
        $excerpt = mb_strimwidth(wp_strip_all_tags($bio), 0, 155, '...');
    } else {
        $fallback_desc = $medium_name . ' is ' . ($phys_title ? $phys_title_indef_article . ' ' . strtolower($phys_title_name) : 'a health care provider' ) . ($primary_appointment_title ? ' at ' . $primary_appointment_title : '') .  ' employed by UAMS Health.';
        $excerpt = mb_strimwidth(wp_strip_all_tags($fallback_desc), 0, 155, '...');
    }
}
function sp_titles_desc($html) {
    global $excerpt;
	$html = $excerpt; 
	return $html;
}
add_filter('seopress_titles_desc', 'sp_titles_desc');

// Set meta title
function sp_titles_title($html) { 
    global $full_name;
	//you can add here all your conditions as if is_page(), is_category() etc.. 
	$html = $full_name . ' | ' . get_bloginfo( "name" );
	return $html;
}
add_filter('seopress_titles_title', 'sp_titles_title', 20, 2);

function be_remove_title_from_single_crumb( $crumb, $args ) { // Because BE is the man
    global $full_name;
    return substr( $crumb, 0, strrpos( $crumb, $args['sep'] ) ) . $args['sep'] . $full_name;
}
add_filter( 'genesis_single_crumb', 'be_remove_title_from_single_crumb', 10, 2 );

function sp_change_title_from_provider_crumb( $crumbs ) { // SEOPress
    global $full_name;
    $crumb = array_pop($crumbs);
    $provider_name = array($full_name, get_permalink());
    array_push($crumbs, $provider_name);
    return $crumbs;
}
add_filter('seopress_pro_breadcrumbs_crumbs', 'sp_change_title_from_provider_crumb', 20);

get_header();

while ( have_posts() ) : the_post(); 
    // ACF Fields - get_fields
    $service_line = get_field('physician_service_line');
    $npi =  get_field('physician_npi');
    $bio_short = get_field('physician_short_clinical_bio');
    $video = get_field('physician_youtube_link');
    $affiliation = get_field('physician_affiliation');
    $hidden = get_field('physician_hidden');
    $college_affiliation = get_field('physician_academic_college');
    $position = get_field('physician_academic_position');
    $bio_academic = get_field('physician_academic_bio');
    $bio_academic_short = get_field('physician_academic_short_bio');
    $office_location = get_field('physician_academic_office');
    $office_building = get_field('physician_academic_map');
    $bio_research = get_field('physician_research_bio');
    $research_interests = get_field('physician_research_interests');
    $research_profile = get_field('physician_research_profiles_link');
    $additional_info = get_field('physician_additional_info');
    $boards = get_field( 'physician_boards' );
    $conditions = get_field('physician_conditions');
    $treatments = get_field('physician_treatments');
    $expertises =  get_field('physician_expertise');
    $second_opinion = get_field('physician_second_opinion');
    $patients = get_field('physician_patient_types');
    $refer_req = get_field('physician_referral_required');
    $accept_new = get_field('physician_accepting_patients');
    $physician_portal = get_field('physician_portal');
    $physician_clinical_bio = get_field('physician_clinical_bio');
    $physician_youtube_link = get_field('physician_youtube_link');
    $physician_clinical_admin_title = get_field('physician_clinical_admin_title');
    $physician_clinical_focus = get_field('physician_clinical_focus');
    $physician_awards = get_field('physician_awards');
    $physician_additional_info = get_field('physician_additional_info');
    $expertises =  get_field('physician_expertise');
    $associations = get_field( 'physician_associations' );
    $publications = get_field('physician_select_publications');
    $pubmed_author_id = get_field('physician_pubmed_author_id');
    $pubmed_author_number = get_field('physician_author_number');
    $education = get_field('physician_education');
    $academic_bio = get_field('physician_academic_bio');
    $academic_appointment = get_field('physician_academic_appointment');
    $academic_admin_title = get_field('physician_academic_admin_title');
    $research_bio = get_field('physician_research_bio');
    $research_interests = get_field('physician_research_interests');
    $research_profiles_link = get_field('physician_research_profiles_link');


    // Classes for indicating presence of content
    $provider_field_classes = '';
    if ($degrees && !empty($degrees)) { $provider_field_classes = $provider_field_classes . ' has-degrees'; }
    if ($prefix && !empty($prefix)) { $provider_field_classes = $provider_field_classes . ' has-prefix'; }
    if (has_post_thumbnail()) {
        $provider_field_classes = $provider_field_classes . ' has-image';
        $image_age = date('Y') - get_the_date( 'Y', get_post_thumbnail_id() ); // How old the provider image is in years
        $image_age_threshold = 10; // Set the threshold for how old a provider image can be before a new photo is needed
        if ( $image_age >= $image_age_threshold ) {
            $image_age = $image_age_threshold . '+'; // Cap attribute value at the threshold
        }
    }
    if ($service_line && !empty($service_line)) { $provider_field_classes = $provider_field_classes . ' has-service-line'; }
    if ($npi && !empty($npi)) { $provider_field_classes = $provider_field_classes . ' has-npi'; }
    if ($bio && !empty($bio)) { $provider_field_classes = $provider_field_classes . ' has-clinical-bio'; }
    if ($bio_short && !empty($bio_short)) { $provider_field_classes = $provider_field_classes . ' has-short-clinical-bio'; }
    if ($video && !empty($video)) { $provider_field_classes = $provider_field_classes . ' has-video'; }
    if ($conditions && !empty($conditions)) { $provider_field_classes = $provider_field_classes . ' has-condition'; }
    if ($treatments && !empty($treatments)) { $provider_field_classes = $provider_field_classes . ' has-treatment'; }
    if ($locations && $location_valid) { $provider_field_classes = $provider_field_classes . ' has-location'; }
    if ($affiliation && !empty($affiliation)) { $provider_field_classes = $provider_field_classes . ' has-affiliation'; }
    if ($expertises && !empty($expertises)) { $provider_field_classes = $provider_field_classes . ' has-expertise'; }
    if ($hidden && !empty($hidden)) { $provider_field_classes = $provider_field_classes . ' has-hidden'; }
    // Add one instance of a class (' has-academic-appt') if there is a physician_academic_appointment row with a value in either/both of the fields.
    // Add one instance of a class (' has-empty-academic-title') if there is an empty academic title field in any of the physician_academic_appointment rows.
    // Add one instance of a class (' has-empty-academic-dept') if there is an empty academic department field in any of the physician_academic_appointment rows.
    if ($college_affiliation && !empty($college_affiliation)) { $provider_field_classes = $provider_field_classes . ' has-college-affiliation'; }
    if ($position && !empty($position)) { $provider_field_classes = $provider_field_classes . ' has-position'; }
    if ($bio_academic && !empty($bio_academic)) { $provider_field_classes = $provider_field_classes . ' has-academic-bio'; }
    if ($bio_academic_short && !empty($bio_academic_short)) { $provider_field_classes = $provider_field_classes . ' has-short-academic-bio'; }
    if ($office_location && !empty($office_location)) { $provider_field_classes = $provider_field_classes . ' has-office-location'; }
    if ($office_building && !empty($office_building)) { $provider_field_classes = $provider_field_classes . ' has-office-building'; }
    // Add one instance of a class (' has-contact-info') if there is a physician_contact_information row with a value in both of the fields.
    // Add one instance of a class (' has-empty-contact-info') if there is an empty information field in any of the physician_contact_information rows.
    // Add one instance of a class (' has-education') if there is a physician_education row with a value in either education_type or school.
    // Add one instance of a class (' has-empty-education-type') if there is an empty education_type field in any of the physician_education rows.
    if ($education && !empty($education)) { $provider_field_classes = $provider_field_classes . ' has-education'; }
    // Add one instance of a class (' has-empty-education-school') if there is an empty school field in any of the physician_education rows.
    if ($boards && !empty($boards)) { $provider_field_classes = $provider_field_classes . ' has-boards'; }
    if ($associations && !empty($associations)) { $provider_field_classes = $provider_field_classes . ' has-associations'; }
    if ($bio_research && !empty($bio_research)) { $provider_field_classes = $provider_field_classes . ' has-research-bio'; }
    if ($research_interests && !empty($research_interests)) { $provider_field_classes = $provider_field_classes . ' has-research-interests'; }
    if ($research_profile && !empty($research_profile)) { $provider_field_classes = $provider_field_classes . ' has-research-profile'; }
    if ($pubmed_author_id && !empty($pubmed_author_id)) { $provider_field_classes = $provider_field_classes . ' has-pubmed-id'; }
    // Add one instance of a class (' has-selected-pubs') if there is a physician_select_publications row with a value in either/both of the pubmed_id_pmid and pubmed_information fields.
    // Add one instance of a class (' has-empty-selected-pub-id') if there is an empty pubmed_id_pmid field in any of the physician_select_publications rows.
    // Add one instance of a class (' has-empty-selected-pub-info') if there is an empty pubmed_information field in any of the physician_select_publications rows.
    // Add one instance of a class (' has-awards') if there is a physician_awards row with a value in either/both of the year and title fields.
    // Add one instance of a class (' has-empty-selected-pub-id') if there is an empty year field in any of the physician_awards rows.
    // Add one instance of a class (' has-empty-selected-pub-info') if there is an empty title field in any of the physician_awards rows.
    if ($additional_info && !empty($additional_info)) { $provider_field_classes = $provider_field_classes . ' has-additional-info'; }
?>

<div class="content-sidebar-wrap">
    <main class="doctor-item<?php echo $provider_field_classes; ?>" id="genesis-content"<?php echo (has_post_thumbnail() ? ' data-image-age="' . $image_age . '"' : ''); ?><?php echo ($service_line ? ' data-service-line="' . get_term( $service_line, 'service_line' )->name . '"' : ''); ?>>
        <section class="container-fluid p-0 p-xs-8 p-sm-10 doctor-info bg-white">
            <div class="row mx-0 mx-xs-n4 mx-sm-n8">
                <div class="col-12 col-xs p-4 py-xs-0 px-xs-4 px-sm-8 order-2 text">
                    <h1 class="page-title">
                        <span class="name"><?php echo $full_name; ?></span>
                        <?php 
                        
                        if ($phys_title && !empty($phys_title)) { ?>
                            <span class="subtitle"><?php echo ($phys_title ? get_term( $phys_title, 'clinical_title' )->name : ''); ?></span>
                        <?php } ?>
                    </h1>
                    <h2 class="sr-only">Overview</h2>
                    <dl>
                    <?php // Display area(s) of expertise
                    $expertise_valid = false;
                    if ($expertises && !empty($expertises)) { 
                        foreach( $expertises as $expertise ) {
                            if ( get_post_status ( $expertise ) == 'publish' ) {
                               $expertise_valid = true;
                               $break;
                            }
                        }
                        if ( $expertise_valid ) {
                        ?>
                        <dt>Area<?php echo( count($expertises) > 1 ? 's' : '' );?> of Expertise</dt>
                        <?php foreach( $expertises as $expertise ) {
                            $id = $expertise; 
                            if ( get_post_status ( $expertise ) == 'publish' ) {
                                echo '<dd><a href="' . get_permalink($id) . '" target="_self">' . get_the_title($id) . '</a></dd>';
                            }
                        } ?>
                        <?php }
                    } ?>
                    <?php  // Display if they will provide second opinions    
                    if ($second_opinion) { ?>
                        <dt>Provides Second Opinion</dt>
                        <dd>Yes</dd>
                    <?php } ?>
                    <?php // Display all patient types
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
                        <dt>Language<?php echo( $language_count > 1 ? 's' : '' );?></dt>
                        <?php echo '<dd>' . $language_list . '</dd>';?>
                    <?php } //endif ?>
                    </dl>
                    <?php
                        if($npi) {
                            
                            $request = wp_nrc_cached_api( $npi );

                            $data = json_decode( $request );

                            if( ! empty( $data ) ) {

                                $rating_valid = $data->valid;

                                if ( $rating_valid ){
                                    $avg_rating = $data->profile->averageRatingStr;
	                                $avg_rating_dec = $data->profile->averageRating;
	                                $review_count = $data->profile->reviewcount;
	                                $comment_count = $data->profile->bodycount;
                                    echo '<div class="rating" aria-label="Patient Rating">';
                                        echo '<div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: '. $avg_rating_dec/5 * 100 .'%;"></div></div>';
                                        echo '<div class="ratings-score">'. $avg_rating .'<span class="sr-only"> out of 5</span></div>';
                                        echo '<div class="w-100"></div>';
                                        echo '<a href="#ratings" aria-label="Jump to Patient Ratings & Reviews">';
                                        echo '<div class="ratings-count-lg">'. $review_count .' Patient Satisfaction Ratings</div>';
                                        echo '<div class="ratings-comments-lg">'.  $comment_count .' comments</div>';
                                        echo '</a>';
                                    echo '</div>';
                                } else { ?>
                                    <p class="small"><em>Patient ratings are not available for this provider. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#">Why not?</a></em></p> 
                                <?php
                                }
                            }
                        } else { ?>
                            <p class="small"><em>Patient ratings are not available for this provider. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#">Why not?</a></em></p>
                    <?php
                        }
                    ?>
                    <?php if( (!$npi) || ( !empty($data) && !$rating_valid ) ) { ?>
                        <div id="why_not_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="why_not_modal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="WhyNotTitle">Why are there no ratings?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>There is no publicly available rating for this medical professional for one of two reasons:</p>
                                        <ul>
                                            <li>He or she does not see patients</li>
                                            <li>He or she sees patients but has not yet received the minimum number of Patient Satisfaction Reviews. To be eligible for display, we require a minimum of 30 surveys. This ensures that the rating is statistically reliable and a true reflection of patient satisfaction.</li>
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php 
                        $l = 1;
                        if( $locations && $location_valid ): ?>
                            <?php if ($eligible_appt) { ?>
                                <h2>Primary Appointment Location</h2>
                            <?php } else { ?>
                                <h2>Primary Location</h2>
                            <?php } // endif ?>
                            <?php foreach( $locations as $location ):
                                    if ( 2 > $l ){
	                                    if ( get_post_status ( $location ) == 'publish' ) {

                                            // Reset variables
                                            $address_id = $location;
                                        
                                            // Parent Location 
                                            $location_has_parent = get_field('location_parent', $location);
                                            $location_parent_id = get_field('location_parent_id', $location);
                                            $parent_location = '';
                                            $parent_id = '';
                                            $parent_title = '';
                                            $parent_url = '';
                                        
                                            if ($location_has_parent && $location_parent_id) { 
                                                $parent_location = get_post( $location_parent_id );
                                            }
                                            // Get Post ID for Address & Image fields
                                            if ($parent_location) {
                                                $parent_id = $parent_location->ID;
                                                $parent_title = $parent_location->post_title;
                                                $parent_url = get_permalink( $parent_id );
                                                $address_id = $parent_id;
                                            }
                                    ?>
                                <p><strong><?php echo get_the_title( $location ); ?></strong><br />
                                <?php if ( $parent_location ) { ?>
                                    (Part of <a href="<?php echo $parent_url; ?>"><?php echo $parent_title; ?></a>)<br />
                                <?php } // endif ?>
                                <?php echo get_field( 'location_address_1', $address_id ); ?><br/>
                                <?php echo ( get_field( 'location_address_2', $address_id ) ? get_field( 'location_address_2', $address_id ) . '<br/>' : ''); ?>
                                <?php echo get_field( 'location_city', $address_id ); ?>, <?php echo get_field('location_state', $address_id ); ?> <?php echo get_field( 'location_zip', $address_id ); ?>
                                <?php $map = get_field( 'location_map', $address_id ); ?>
                                <!-- <br /><a class="uams-btn btn-red btn-sm btn-external" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank">Directions</a> -->
                                </p>
                                <?php if (get_field('location_phone', $location)) { ?>
                                    <dl>
                                        <dt>Appointment Phone Number<?php echo get_field('field_location_appointment_phone_query', $location) ? 's' : ''; ?></dt>
                                        <?php if (get_field('location_new_appointments_phone', $location) && get_field('location_clinic_phone_query', $location)) { ?>
                                            <dd><a href="tel:<?php echo format_phone_dash( get_field('location_new_appointments_phone', $location) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_new_appointments_phone', $location) ); ?></a><?php echo get_field('field_location_appointment_phone_query', $location) ? '<br/><span class="subtitle">New Patients</span>' : '<br/><span class="subtitle">New and Returning Patients</span>'; ?></dd>
                                            <?php if (get_field('location_return_appointments_phone', $location) && get_field('field_location_appointment_phone_query', $location)) { ?>
                                                <dd><a href="tel:<?php echo format_phone_dash( get_field('location_return_appointments_phone', $location) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_return_appointments_phone', $location) ); ?></a><br/><span class="subtitle">Returning Patients</span></dd>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <dd><a href="tel:<?php echo format_phone_dash( get_field('location_phone', $location) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_phone', $location) ); ?></a></dd>
                                        <?php } ?>
                                    </dl>
                                <?php } ?>
                                <div class="btn-container">
                                    <a class="btn btn-primary" href="<?php echo get_the_permalink( $location ); ?>">
                                        View Location
                                    </a>
                                    <?php if (1 < $location_count) { ?>
                                        <a class="btn btn-outline-primary" href="#locations" aria-label="Jump to list of locations for this provider">
                                            View All Locations
                                        </a>
                                    <?php } ?>
                                </div>
                                <?php $l++;
	                                }
                                } ?>
							<?php endforeach;
								// wp_reset_postdata(); ?>
						<?php endif; ?> 
                </div>
                <?php 
                $docphoto = '/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg';
                if ( has_post_thumbnail() ) { ?>
                <div class="col-12 col-xs px-0 px-xs-4 px-sm-8 order-1 image">
                    <picture>
                    <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 389, 519, 'center', 'center'); ?> 1x, <?php echo image_sizer(get_post_thumbnail_id(), 778, 1038, 'center', 'center'); ?> 2x"
                            media="(min-width: 1200px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 306, 408, 'center', 'center'); ?> 1x, <?php echo image_sizer(get_post_thumbnail_id(), 612, 816, 'center', 'center'); ?> 2x"
                            media="(min-width: 992px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 182, 243, 'center', 'center'); ?> 1x, <?php echo image_sizer(get_post_thumbnail_id(), 364, 486, 'center', 'center'); ?> 2x"
                            media="(min-width: 768px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 86, 115, 'center', 'center'); ?> 1x, <?php echo image_sizer(get_post_thumbnail_id(), 172, 230, 'center', 'center'); ?> 2x"
                            media="(min-width: 576px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 380, 507, 'center', 'center'); ?> 1x, <?php echo image_sizer(get_post_thumbnail_id(), 760, 1013, 'center', 'center'); ?> 2x"
                            media="(min-width: 1px)">
                        <img src="<?php echo image_sizer(get_post_thumbnail_id(), 778, 1038, 'center', 'center'); ?>" alt="<?php echo $full_name; ?>" />
                        <?php $docphoto = image_sizer(get_post_thumbnail_id(), 778, 1038, 'center', 'center');
                             } else {
                                the_post_thumbnail( 'large',  array( 'itemprop' => 'image' ) );
                                $docphoto = get_the_post_thumbnail( 'large');
                             } //endif ?>
                    </picture>
                </div>
                <?php } //endif ?>
            </div>
        </section>
        <?php if ($eligible_appt): ?>
        <?php 
            $appointment_phone_name = 'the main UAMS appointment line'; // default (UAMS)
            $appointment_phone = '5016868000'; // default (UAMS)
            $show_portal = false;
            // Portal
            if ( $physician_portal ) {
                $portal = get_term($physician_portal, "portal");
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

            if (1 == $location_count) {
                $appointment_location_url = $primary_appointment_url;
                $appointment_location_title = $primary_appointment_title;
            } else {
                $appointment_location_url = '#locations';
                $appointment_location_title = 'Jump to list of locations for this provider';
            }
            
        ?>
        <section class="uams-module cta-bar cta-bar-1 bg-auto">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>Make an Appointment With <?php echo $short_name; ?></h2>
                        <?php if (!$location_valid && $refer_req && $accept_new && $show_portal) { ?>
                            <p>Appointments for new patients are by referral only.</p>
                            <p>Existing patients can either <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } elseif ($refer_req && $accept_new && $show_portal) { ?>
                            <p>Appointments for new patients are by referral only.</p>
                            <p>Existing patients can either <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>, <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contact the clinic directly</a> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } elseif (!$location_valid && $refer_req && $accept_new) { ?>
                            <p>Appointments for new patients are by referral only.</p>
                            <p>Existing patients can make an appointment by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } elseif ($refer_req && $accept_new) { ?>
                            <p>Appointments for new patients are by referral only.</p>
                            <p>Existing patients can make an appointment by <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } elseif (!$location_valid && $accept_new && $show_portal) { ?>
                            <p>New patients can make an appointment by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <p>Existing patients also have the option to <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>.</p>
                        <?php } elseif ($accept_new && $show_portal) { ?>
                            <p>New patients can make an appointment by <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <p>Existing patients also have the option to <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>.</p>
                        <?php } elseif (!$location_valid && $accept_new) { ?>
                            <p>New and existing patients can make an appointment by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } elseif ($accept_new) { ?>
                            <p>New and existing patients can make an appointment by <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } elseif (!$location_valid && $show_portal) { ?>
                            <p>This provider is not currently accepting new patients.</p>
                            <p>Existing patients can either <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } elseif ($show_portal) { ?>
                            <p>This provider is not currently accepting new patients.</p>
                            <p>Existing patients can either <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>, <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contact the clinic directly</a> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } elseif (!$location_valid) { ?>
                            <p>This provider is not currently accepting new patients.</p>
                            <p>Existing patients can make an appointment by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } else { ?>
                            <p>This provider is not currently accepting new patients.</p>
                            <p>Existing patients can make an appointment by <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
        
        <?php 
            $physician_clinical_split = false;
            if (
                ( $physician_clinical_bio || !empty ($physician_youtube_link) ) // column A stuff
                && ( $physician_clinical_focus ) // column B stuff
                // && ( $physician_clinical_admin_title || $physician_clinical_focus ) // Alternate column B stuff if we decide to display clinical admin title
                ) {
                $physician_clinical_split = true; // If there is stuff for column A and column B, split the section into two columns
            }
        
            // Display section for Clinical Bio, Clinical Video, Clinical Administrative Title(s), Clinical Focus ... only if there is a bio or video.
            if ( $physician_clinical_bio || !empty ($physician_youtube_link) ) { ?>
            <section class="uams-module clinical-info bg-auto">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="module-title">About <?php echo $short_name; ?></h2>


                            <?php if ( $physician_clinical_split ) {
                                // If there is a bio or video AND at least one of the other clinical things, visually split the layout ?>
                                <div class="row content-split-lg">
                                <div class="col-xs-12 col-lg-7">
                                <div class="content-width">
                            <?php } else { ?>
                                <div class="module-body">
                            <?php } // endif
                            if ( $physician_clinical_bio ) { ?>
                                <h3 class="sr-only">Clinical Biography</h3>
                                <?php echo $physician_clinical_bio; ?>
                            <?php } // endif
                            if($physician_youtube_link) { ?>
                                <div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">
                                    <?php echo wp_oembed_get( $physician_youtube_link ); ?>
                                </div>
                            <?php } // endif
                            if ( $physician_clinical_split ) { ?>
                                </div>
                                </div>
                                <div class="col-xs-12 col-lg-5">
                                <div class="content-width">
                            <?php } // endif ?>
                            <?php // Section for displaying clinical admin title
                            if (true == false) { // Remove this if statement if we decide to display clinical admin title later.
                                if( have_rows('physician_clinical_admin_title') ): ?>
                                    <h3 class="h4">Administrative Roles</h3>
                                    <dl>
                                    <?php while( have_rows('physician_clinical_admin_title') ): the_row();
                                        $department = get_term( get_sub_field('physician_clinical_admin_area'), 'service_line' ); 
                                        $clinical_admin_title_tax = get_term( get_sub_field('clinical_admin_title_tax'), 'clinical_admin_title' );
                                    ?>
                                        <dt><?php echo $department->name; ?></dt>
                                        <dd><?php echo $clinical_admin_title_tax->name; ?></dd>
                                    <?php endwhile; ?>
                                    </dl>
                                <?php endif; 
                            } // endif ?>
                            <?php if($physician_clinical_focus) { ?>
                                <h3 class="h4">Clinical Focus</h3>
                                <?php echo $physician_clinical_focus; ?>
                            <?php } // endif
                            if ( $physician_clinical_split ) { ?>
                                </div>
                                </div>
                                </div>
                            <?php } else { ?>
                                </div>
                            <?php } // endif ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php } // endif ?>
        <?php // load all 'conditions' terms for the post
	        $title_append = ' by ' . $short_name;
            $args = (array(
                'taxonomy' => "condition",
                'hide_empty' => false,
                'term_taxonomy_id' => $conditions
            ));
            $conditions_query = new WP_Term_Query( $args );
            $condition_schema = '';
            // we will use the first term to load ACF data from
            if( $conditions ):
                include( UAMS_FAD_PATH . '/templates/loops/conditions-loop.php' );
                $condition_schema .= ',"medicalSpecialty": [';
                foreach( $conditions as $condition ):
                    $condition_schema .= '{
                    "@type": "MedicalSpecialty",
                    "name": "'. get_term( $condition, 'condition' )->name .'",
                    "url":"'. get_term_link($condition) .'"
                    },';
                endforeach;
                $condition_schema .= '"" ]';
            endif; 
             // load all 'treatments' terms for the post
            $args = (array(
                'taxonomy' => "treatment",
                'hide_empty' => false,
                'term_taxonomy_id' => $treatments
            ));
            $treatments_query = new WP_Term_Query( $args );

            // we will use the first term to load ACF data from
        if( $treatments ):
            include( UAMS_FAD_PATH . '/templates/loops/treatments-loop.php' );
        endif;
        
        $expertise_valid = false;
        if( $expertises ):
	        foreach( $expertises as $expertise ) {
	            if ( get_post_status ( $expertise ) == 'publish' ) {
	                $expertise_valid = true;
                    break;
	            }
	        }
	        if ( $expertise_valid ) {
	        ?>
	            <section class="uams-module expertise-list bg-auto" id="expertise">
	                <div class="container-fluid">
	                    <div class="row">
	                        <div class="col-12">
	                            <h2 class="module-title"><?php echo $short_name_possessive; ?> Areas of Expertise</h2>
	                            <div class="card-list-container">
	                                <div class="card-list">
                                        <?php foreach( $expertises as $expertise ) {
                                            $id = $expertise;
                                            if ( get_post_status ( $expertise ) == 'publish' ) {
                                                include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
                                            }
                                        } ?>
                                    </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </section>
	        <?php
	        }
        endif;
        ?>
        <?php 
            $physician_academic_split = false;
            if ( $academic_bio && ( $academic_appointment || $academic_admin_title || $education || $boards ) ) {
                $physician_academic_split = true;
            }
        
            if($academic_bio || $academic_appointment || $academic_admin_title || $education || $boards): ?>
        <section class="uams-module academic-info bg-auto">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="module-title"><?php echo $short_name_possessive; ?> Academic Background</h2>
                        <?php if ( $physician_academic_split ) {
                            // If there is a bio AND at least one of the other academic things, visually split the layout ?>
                            <div class="row content-split-lg">
                            <div class="col-xs-12 col-lg-7">
                            <div class="content-width">
                        <?php } else { ?>
                            <div class="module-body">
                        <?php } // endif
                        if ( $academic_bio ) { ?>
                            <h3 class="sr-only">Academic Biography</h3>
                            <?php echo $academic_bio; ?>
                        <?php } // endif?>
                        <?php if ( $physician_academic_split ) { ?>
                            </div>
                            </div>
                            <div class="col-xs-12 col-lg-5">
                            <div class="content-width">
                        <?php } // endif ?>
                            <?php
                                if( have_rows('physician_academic_admin_title') ): ?>
                                    <h3 class="h4">Administrative Roles</h3>
                                    <dl>
                                    <?php while( have_rows('physician_academic_admin_title') ): the_row(); ?>
                                    <?php 
                                        $department = get_term( get_sub_field('department'), 'academic_department' ); 
                                        $academic_admin_title_tax = get_term( get_sub_field('academic_admin_title_tax'), 'academic_admin_title' );
                                    ?>
                                        <dt><?php echo $department->name; ?></dt>
                                        <dd><?php echo $academic_admin_title_tax->name; ?></dd>
                                    <?php endwhile; ?>
                                    </dl>
                            <?php endif; ?>
                            <?php
                                // $academic_appointments = get_field('physician_academic_appointment');
                                if( have_rows('physician_academic_appointment') ): ?>
                                    <h3 class="h4">Faculty Appointments</h3>
                                    <dl>
                                    <?php while( have_rows('physician_academic_appointment') ): the_row(); ?>
                                    <?php 
                                        $department = get_term( get_sub_field('department'), 'academic_department' ); 
                                        $academic_title_tax = get_term( get_sub_field('academic_title_tax'), 'academic_title' );
                                        if ($academic_title_tax->name) {
                                            $academic_title = $academic_title_tax->name;
                                        } else {
                                            $academic_title = get_sub_field('academic_title');
                                        }
                                    ?>
                                        <dt><?php echo $department->name; ?></dt>
                                        <dd><?php echo $academic_title; ?></dd>
                                    <?php endwhile; ?>
                                    </dl>
                            <?php endif; ?>
                            <?php
                                if( have_rows('physician_education') ): ?>
                                    <h3 class="h4">Education</h3>
                                    <dl>
                                    <?php while( have_rows('physician_education') ): the_row();
                                        $school_name = get_term( get_sub_field('school'), 'school');
                                        $education_type = get_term( get_sub_field('education_type'), 'educationtype');
                                    ?>
                                        <dt><?php echo $education_type->name; ?></dt>
                                        <dd><?php echo $school_name->name; ?><?php echo (get_sub_field('description') ? '<br /><span class="subtitle">' . get_sub_field('description') .'</span>' : ''); ?></dd>
                                    <?php endwhile; ?>
                                    </dl>
                            <?php endif;
                                
                                if( ! empty( $boards ) ): ?>
                            <h3 class="h4">Professional Certifications</h3>
                            <ul>
                            <?php foreach ( $boards as $board ) :
                                $board_name = get_term( $board, 'board'); ?>
                                <li><?php echo $board_name->name; ?></li>
                                <?php // }; ?>
                            <?php endforeach; ?>
                            </ul>
                            <?php endif;
                                
                                if( ! empty( $associations ) ): ?>
                            <h3 class="h4">Associations</h3>
                            <ul>
                            <?php foreach ( $associations as $association ) :
                                $association_name = get_term( $association, 'association'); ?>
                                <li><?php echo $association_name->name; ?></li>
                                <?php // }; ?>
                            <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        <?php if ( $physician_academic_split ) { ?>
                            </div>
                            </div>
                            </div>
                        <?php } else { ?>
                            </div>
                        <?php } // endif ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <?php 
        if( !empty($research_bio) || !empty($esearch_interests) || !empty ( $publications ) || $pubmed_author_id || $research_profiles_link ): ?>
        <section class="uams-module research-info bg-auto">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="module-title"><?php echo $short_name_possessive; ?> Research</h2>
                        <div class="module-body">
                            <?php
                                if($research_bio)
                                {
                                    echo $research_bio;
                                }
                            ?>
                            <?php
                                if($research_interests)
                                { ?>
                                <h3>Research Interests</h3>
                            <?php
                                    echo $research_interests;
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
                            <?php if( $pubmed_author_id ): ?>
                                <?php
                                    $pubmedid = trim($pubmed_author_id);
                                    $pubmedcount = ($pubmed_author_number ? $pubmed_author_number : '3');
                                ?>
                                <h3>Latest Publications</h3>
                                <p>Publications listed below are automatically derived from MEDLINE/PubMed and other sources, which might result in incorrect or missing publications.</p>
                                <?php echo do_shortcode( '[pubmed terms="' . urlencode($pubmedid) .'%5BAuthor%5D" count="' . $pubmedcount .'"]' ); ?>
                            <?php endif; ?>
                            <?php if( $research_profiles_link ): ?>
                                <h3>UAMS Research Profile</h3>
                                <p>Each UAMS faculty member has a research profile page that includes biographical and contact information, a list of their most recent grant activity and a list of their PubMed publications.</p>
                                <p><a class="btn btn-outline-primary" href="<?php echo $research_profiles_link; ?>">View <?php echo $short_name_possessive; ?> research profile</a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <?php 
        if( $locations && $location_valid ): ?>
        <section class="uams-module location-list bg-auto" id="locations">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title">Locations Where <?php echo $short_name; ?> Practices</h2>
                        <div class="card-list-container location-card-list-container">
                            <div class="card-list">
                            <?php $l = 1;
                                $location_schema = ',"address": [';
                            ?>
                            <?php foreach( $locations as $location ): 
                                if ( get_post_status ( $location ) == 'publish' ) { 

                                    $id = $location; 
                                    include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
                                        // Schema data
                                        if ($l > 1){
                                            $location_schema .= ',';
                                        }
                                        $location_schema .= '
                                        {
                                        "@type": "PostalAddress",
                                        "streetAddress": "'. get_field('location_address_1', $location ) . ' '. get_field('location_address_2', $location ) .'",
                                        "addressLocality": "'. get_field('location_city', $location ) .'",
                                        "addressRegion": "'. get_field('location_state', $location ) .'",
                                        "postalCode": "'. get_field('location_zip', $location) .'",
                                        "telephone": "'. format_phone_dash( get_field('location_phone', $location) ) .'"
                                        }
                                        ';
                                    ?>

                                    <?php $l++; ?>
                                <?php } ?>
                            <?php endforeach; 
                                $location_schema .= ']
                                ';
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?> 
        <?php if ( $rating_valid ) : ?>
        <section class="uams-module ratings-and-reviews bg-auto" id="ratings">
            <div class="container-fluid">
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
                                <p class="h5">Overall: <?php echo $data->profile->averageRatingStr; ?> out of 5</p>
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
                            <button class="btn btn-secondary" data-toggle="modal" data-target="#MoreReviews" aria-label="Load more individual reviews">View More</button>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="MoreReviews" tabindex="-1" role="dialog" aria-labelledby="more-reviews-title" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="more-reviews-title">More Reviews</h5>
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
                        <script src="https://transparency.nrchealth.com/widget/v2/uams/npi/<?php echo $npi; ?>/lotw.js" async></script>                           
                        <?php // endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <!-- <section class="uams-module news-list bg-auto">
            <div class="container-fluid"
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title">Latest News for {Name}</h2>
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
            </div>
        </section> -->
        <?php if ($eligible_appt): ?>
            <section class="uams-module cta-bar cta-bar-1 bg-auto">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2>Make an Appointment With <?php echo $short_name; ?></h2>
                            <?php if (!$location_valid && $refer_req && $accept_new && $show_portal) { ?>
                                <p>Appointments for new patients are by referral only.</p>
                                <p>Existing patients can either <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } elseif ($refer_req && $accept_new && $show_portal) { ?>
                                <p>Appointments for new patients are by referral only.</p>
                                <p>Existing patients can either <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>, <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contact the clinic directly</a> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } elseif (!$location_valid && $refer_req && $accept_new) { ?>
                                <p>Appointments for new patients are by referral only.</p>
                                <p>Existing patients can make an appointment by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } elseif ($refer_req && $accept_new) { ?>
                                <p>Appointments for new patients are by referral only.</p>
                                <p>Existing patients can make an appointment by <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } elseif (!$location_valid && $accept_new && $show_portal) { ?>
                                <p>New patients can make an appointment by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                                <p>Existing patients also have the option to <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>.</p>
                            <?php } elseif ($accept_new && $show_portal) { ?>
                                <p>New patients can make an appointment by <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                                <p>Existing patients also have the option to <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>.</p>
                            <?php } elseif (!$location_valid && $accept_new) { ?>
                                <p>New and existing patients can make an appointment by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } elseif ($accept_new) { ?>
                                <p>New and existing patients can make an appointment by <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } elseif (!$location_valid && $show_portal) { ?>
                                <p>This provider is not currently accepting new patients.</p>
                                <p>Existing patients can either <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } elseif ($show_portal) { ?>
                                <p>This provider is not currently accepting new patients.</p>
                                <p>Existing patients can either <a href="<?php echo $portal_url; ?>" aria-label="<?php echo $portal_name; ?>" target="_blank">request an appointment online</a> through <?php echo $portal_name; ?>, <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contact the clinic directly</a> or call <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } elseif (!$location_valid) { ?>
                                <p>This provider is not currently accepting new patients.</p>
                                <p>Existing patients can make an appointment by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } else { ?>
                                <p>This provider is not currently accepting new patients.</p>
                                <p>Existing patients can make an appointment by <a href="<?php echo $appointment_location_url; ?>" aria-label="<?php echo $appointment_location_title; ?>">contacting the clinic directly</a> or by calling <?php echo $appointment_phone_name; ?> at <a href="tel:<?php echo $appointment_phone_tel; ?>" class="no-break"><?php echo $appointment_phone_text; ?></a>.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
</div>

<?php // Schema Data ?>
<script type='application/ld+json'>
{
  "@context": "http://www.schema.org",
  "@type": "Physician",
  "name": "<?php echo $full_name; ?>",
  "url": "<?php echo get_permalink(); ?>",
  "logo": "<?php echo get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_horizontal_dark_386x50.png'; ?>",
  "image": "<?php echo $docphoto; ?>"
  <?php if ($excerpt) { ?>
  ,"description": "<?php echo $excerpt; ?>"
  <?php } ?>
  <?php echo $condition_schema; ?>
  <?php echo $location_schema; ?>
  <?php if ( $rating_valid ){ ?>
  ,"aggregateRating": {
  		"@type": "AggregateRating",
        "ratingValue": "<?php echo $avg_rating; ?>",
        "ratingCount": "<?php echo $review_count; ?>",
        "reviewCount": "<?php echo $comment_count; ?>"
   }
  <?php } ?>
}
 </script>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>