<?php

/**
 * Callbacks of the single-clinical-resource template.
 *
 * The template registers each one on its own hook; the functions live here, and not in the template, so the
 * template can be run more than once in a process.
 */

/**
 * The `uamswp_fad_canonical` callback of the single-clinical-resource template.
 */
function uamswp_fad_canonical(string $html): string
{
    global $syndicated;
    global $syndication_url;
    if ($syndicated && ! empty($syndication_url)) {
        $html = '<link rel="canonical" href="'.htmlspecialchars(urldecode(uamswp_fad_str($syndication_url))).'" />';
    }

    return $html;
}

/**
 * The `uamswp_resource_post_title` callback of the single-clinical-resource template.
 */
function uamswp_resource_post_title(): void
{
    /** @var string $resource_archive_title */
    global $resource_archive_title;
    /** @var string|null $resource_type_label */
    global $resource_type_label;
    global $is_provider_spotlight;

    // A Provider Spotlight names itself in the supertitle
    $supertitle = $is_provider_spotlight ? $resource_type_label : $resource_archive_title;

    echo '<h1 class="entry-title" itemprop="headline">';
    echo '<span class="supertitle">'.$supertitle.'</span><span class="sr-only">:</span> ';
    // Neutralize any markup that may have been stored in the title before the
    // save-time sanitizer existed. wp_strip_all_tags() removes tags while
    // preserving the wptexturize entities the_title filter already produced
    // (e.g. &#8217; for an apostrophe), so esc_html() is avoided here to
    // prevent double-encoding legitimate titles.
    echo wp_strip_all_tags(get_the_title());
    echo '</h1>';
}

/**
 * The `uamswp_resource_text` callback of the single-clinical-resource template.
 */
function uamswp_resource_text(): void
{
    global $resource_type_value;
    $text = get_field('clinical_resource_text');
    $nci_query = get_field('clinical_resource_text_nci_query');
    $nci_embed = get_field('clinical_resource_nci_embed');

    if ($resource_type_value == 'text' && $text && ! $nci_query) { // $show_text_section ) {
        echo $text;
    } elseif ($resource_type_value == 'text' && $nci_query && $nci_embed) {
        echo $nci_embed;
    }
}

/**
 * The `uamswp_resource_infographic` callback of the single-clinical-resource template.
 */
function uamswp_resource_infographic(): void
{
    global $resource_type_value;
    $infographic = get_field('clinical_resource_infographic');
    $infographic_descr = get_field('clinical_resource_infographic_descr');
    $infographic_transcript = get_field('clinical_resource_infographic_transcript');
    $size = 'content-image-wide';

    if ($resource_type_value == 'infographic' && $infographic) {
        if ($infographic_descr) {
            echo '<h2 class="sr-only">Description</h2>';
            echo $infographic_descr;
        }

        echo '<h2 class="sr-only">Infographic</h2>';
        echo '<div class="alignwide">';
        echo wp_get_attachment_image(uamswp_fad_int($infographic), $size);
        echo '</div>';

        if ($infographic_transcript) {
            echo '<h2>Transcript</h2>';
            echo $infographic_transcript;
        }
    }
}

/**
 * The `uamswp_resource_document` callback of the single-clinical-resource template.
 */
function uamswp_resource_document(): void
{
    global $resource_type_value;
    $document_descr = get_field('clinical_resource_document_descr');
    get_field('clinical_resource_document');

    $icon_file = 'far fa-file';
    $icon_pdf = 'far fa-file-pdf';
    $icon_word = 'far fa-file-word';
    $icon_powerpoint = 'far fa-file-powerpoint';
    $icon_excel = 'far fa-file-excel';
    $icon_image = 'far fa-file-image';

    if ($resource_type_value == 'doc' && have_rows('clinical_resource_document')) {
        echo $document_descr;
        echo '<hr />';
        echo '<h2>Attachments</h2>';
        echo '<ul class="attachments">';
        while (have_rows('clinical_resource_document')) {
            the_row();
            $document_title = uamswp_fad_str(get_sub_field('document_title'));
            $document_file = uamswp_fad_assoc(get_sub_field('document_file'));
            $document_url = uamswp_fad_str($document_file['url'] ?? '');
            $document_url_path = pathinfo($document_url);
            $document_url_extension = $document_url_path['extension'] ?? '';

            if ($document_url_extension == 'pdf') {
                $icon_file = $icon_pdf;
            } elseif ($document_url_extension == 'doc' || $document_url_extension == 'docx') {
                $icon_file = $icon_word;
            } elseif ($document_url_extension == 'ppt' || $document_url_extension == 'pptx') {
                $icon_file = $icon_powerpoint;
            } elseif ($document_url_extension == 'xls' || $document_url_extension == 'xlsx') {
                $icon_file = $icon_excel;
            } elseif (in_array($document_url_extension, ['jpg', 'jpeg', 'gif', 'png', 'bmp'])) {
                $icon_file = $icon_image;
            }
            ?>
            <li><a class="attachment-link" href="<?php echo esc_url($document_url); ?>" title="<?php echo esc_attr($document_title); ?>" target="_blank"><span class="<?php echo $icon_file; ?> fa-fw"></span><span class="attachment-label"><?php echo esc_html($document_title); ?></span></a></li>
        <?php }
        echo '</ul>';
    }
}

/**
 * The `uamswp_resource_physicians` callback of the single-clinical-resource template.
 */
function uamswp_resource_physicians(): void
{
    global $show_providers_section;
    global $postsCountClass;
    /** @var WP_Query $physicians_query */
    global $physicians_query;
    /** @var int $postsPerPage */
    global $postsPerPage;
    /** @var array<int, int|string> $physicians */
    global $physicians;

    if ($show_providers_section) {
        ?>
        <section class="uams-module bg-auto" id="providers">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title">Related Providers</span></h2>
                        <div class="card-list-container">
                            <div class="card-list card-list-doctors">
                                <?php
                                    while ($physicians_query->have_posts()) {
                                        $physicians_query->the_post();
                                        $id = get_the_ID();
                                        include UAMS_FAD_PATH.'/templates/loops/physician-card.php';
                                    }
        wp_reset_postdata();
        ?>
                            </div>
                        </div>
                        <!-- <?php if ($postsPerPage !== -1) { ?>
                        <div class="more">
                            <button class="loadmore btn btn-primary" data-posttype="post" data-postids="<?php echo implode(',', $physicians); ?>" data-ppp="<?php echo $postsPerPage; ?>" data-postcount="<?php echo $physicians_query->found_posts; ?>" aria-label="Load more providers">Load More</button>
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

/**
 * The `uamswp_resource_video` callback of the single-clinical-resource template.
 */
function uamswp_resource_video(): void
{
    global $resource_type_value;
    $video = uamswp_fad_str(get_field('clinical_resource_video'));
    $video_descr = get_field('clinical_resource_video_descr');
    $video_transcript = get_field('clinical_resource_video_transcript');

    $video_source = '';
    if ((str_contains($video, 'youtube')) || (str_contains($video, 'youtu.be'))) {
        $video_source = 'youtube';
    }

    if ($resource_type_value == 'video' && $video) { ?>
        <?php if ($video_descr) {
            echo '<h2 class="sr-only">Description</h2>';
            echo $video_descr;
        }
        echo '<h2 class="sr-only">Video Player</h2>';
        if (function_exists('lyte_preparse') && $video_source === 'youtube') {
            echo '<div class="alignwide">';
            echo lyte_parse(str_replace(['https:', 'http:'], 'httpv:', $video));
            echo '</div>';
        } else {
            echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
            echo wp_oembed_get($video);
            echo '</div>';
        }
        if ($video_transcript) {
            echo '<h2>Transcript</h2>';
            echo $video_transcript;
        }
    }
}

/**
 * The `uamswp_resource_conditions_cpt` callback of the single-clinical-resource template.
 */
function uamswp_resource_conditions_cpt(): void
{
    global $page_title;
    global $show_conditions_section;
    global $conditions_cpt_query;
    $condition_context = 'single-resource';
    $condition_heading_related_name = $page_title; // To what is it related?

    if ($show_conditions_section) {
        include UAMS_FAD_PATH.'/templates/loops/conditions-cpt-loop.php';
    }
}

/**
 * The `uamswp_resource_treatments_cpt` callback of the single-clinical-resource template.
 */
function uamswp_resource_treatments_cpt(): void
{
    global $page_title;
    global $show_treatments_section;
    global $treatments_cpt_query;
    $treatment_context = 'single-resource';
    $treatment_heading_related_name = $page_title; // To what is it related?

    if ($show_treatments_section) {
        include UAMS_FAD_PATH.'/templates/loops/treatments-cpt-loop.php';
    }
}

/**
 * The `uamswp_resource_locations` callback of the single-clinical-resource template.
 */
function uamswp_resource_locations(): void
{
    global $show_locations_section;
    /** @var WP_Query $location_query */
    global $location_query;

    if ($show_locations_section) { ?>
        <section class="uams-module location-list bg-auto" id="locations">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title">Related Locations</span></h2>
                        <div class="card-list-container location-card-list-container">
                            <div class="card-list">
                            <?php while ($location_query->have_posts()) {
                                $location_query->the_post();
                                $id = get_the_ID();
                                include UAMS_FAD_PATH.'/templates/loops/location-card.php';
                            }
        wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php
    } // endif
}

/**
 * The `uamswp_resource_associated` callback of the single-clinical-resource template.
 */
function uamswp_resource_associated(): void
{
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
    if ($show_related_resource_section) {
        include UAMS_FAD_PATH.'/templates/blocks/clinical-resources.php';
    }
}

/**
 * The `uamswp_resource_expertise` callback of the single-clinical-resource template.
 */
function uamswp_resource_expertise(): void
{
    global $show_aoe_section;
    /** @var WP_Query $expertise_query */
    global $expertise_query;

    if ($show_aoe_section) { ?>
		<section class="uams-module expertise-list bg-auto" id="expertise" aria-labelledby="areas-of-expertise-title">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title" id="areas-of-expertise-title"><span class="title">Related Areas of Expertise</span></h2>
						<div class="card-list-container">
							<div class="card-list card-list-expertise">
							<?php
                            while ($expertise_query->have_posts()) {
                                $expertise_query->the_post();
                                $id = get_the_ID();
                                include UAMS_FAD_PATH.'/templates/loops/expertise-card.php';
                            }
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

/**
 * The `uamswp_resource_jump_links` callback of the single-clinical-resource template.
 */
function uamswp_resource_jump_links(): void
{
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
    if ($show_jump_links_section) { ?>
        <nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
            <h2>Related Content</h2>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse inner-container" id="jump-link-nav">
                <ul class="nav navbar-nav">
                    <?php if ($show_related_resource_section) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#related-resources" title="Jump to the section of this page about Related Resources">Resources</a>
                        </li>
                    <?php } ?>
                    <?php if ($show_conditions_section) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#conditions" title="Jump to the section of this page about Related Conditions">Conditions</a>
                        </li>
                    <?php } ?>
                    <?php if ($show_treatments_section) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#treatments" title="Jump to the section of this page about Medical Treatments and Procedures Performed">Treatments &amp; Procedures</a>
                        </li>
                    <?php } ?>
                    <?php if ($show_providers_section) { ?>
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
                    <?php if ($show_appointment_section) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#appointment-info" title="Jump to the section of this page about making an appointment">Make an Appointment</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
    <?php }
    }

/**
 * The `uamswp_resource_appointment` callback of the single-clinical-resource template.
 */
function uamswp_resource_appointment(): void
{
    global $show_appointment_section;
    global $is_provider_spotlight;
    /** @var int|false|null $spotlight_provider */
    global $spotlight_provider;

    if ($show_appointment_section) {

        // On a Provider Spotlight, the provider-specific CTA replaces the
        // generic "Make an Appointment" band in this same slot.
        if ($is_provider_spotlight && $spotlight_provider) {
            echo uamswp_spotlight_appointment_cta($spotlight_provider);

            return;
        }

        $appointment_location_url = '/location/';
        // $appointment_location_label = 'View a list of UAMS Health locations';
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

/**
 * The `uamswp_resource_provider_spotlight` callback of the single-clinical-resource template.
 */
function uamswp_resource_provider_spotlight(): void
{
    global $is_provider_spotlight;
    /** @var int|false|null $spotlight_provider */
    global $spotlight_provider;

    if (! $is_provider_spotlight || ! $spotlight_provider) {
        return;
    }

    $provider_id = $spotlight_provider;

    // Provider-derived strings, resolved once
    $names = uamswp_provider_names($provider_id);

    $medium_name_attr = uamswp_fad_str($names['medium_attr']);

    // Introduction: always generated from the provider. The manual field was
    // removed, so any value left in postmeta from before that is ignored.
    $intro = uamswp_spotlight_default_intro($provider_id);

    if ($intro) {
        echo '<h2 class="sr-only">Description</h2>';
        echo $intro;
    }

    // Questions and answers, wrapped so the portrait float is contained and the
    // block carries a divider above it (echoing an <hr> after the intro). The
    // "Getting to Know" heading is screen-reader only; the portrait follows it
    // and floats via the theme's .alignright.
    if (have_rows('clinical_resource_spotlight_qa')) {
        ?>
        <h2 class="sr-only">Questions and Answers</h2>
        <div class="rule-top-lg d-flow-root">
            <?php
            // Portrait: the provider's standard 3:4 headshot, sized for a
            // right-aligned content image
            $portrait_id = get_post_thumbnail_id($provider_id);

        if ($portrait_id) {
            // The stub types the attributes as a closed shape without itemprop,
            // so itemprop is added through the attributes filter for this call
            $add_itemprop = static function ($attr): array {
                $attr = uamswp_fad_assoc($attr);
                $attr['itemprop'] = 'image';

                return $attr;
            };
            add_filter('wp_get_attachment_image_attributes', $add_itemprop);
            ?>
                <figure class="alignright">
                    <?php echo wp_get_attachment_image($portrait_id, 'content-image-side', false, ['alt' => $medium_name_attr]); ?>
                </figure>
                <?php
            remove_filter('wp_get_attachment_image_attributes', $add_itemprop);
            ?>
                <?php
        }

        while (have_rows('clinical_resource_spotlight_qa')) {
            the_row();
            $question = uamswp_fad_str(get_sub_field('spotlight_qa_question'));
            $answer = uamswp_fad_str(get_sub_field('spotlight_qa_answer'));

            if (! $question) {
                continue;
            }
            ?>
                <h3><?php echo esc_html($question); ?></h3>
                <div class="answer"><?php echo $answer; ?></div>
                <?php
        }
        ?>
        </div>
        <?php
    }

    // The provider-specific call to action is not rendered here. It renders in
    // the site appointment slot (#appointment-info) via
    // uamswp_resource_appointment(), replacing the generic "Make an Appointment"
    // band for spotlights. See uamswp_spotlight_appointment_cta().
}
