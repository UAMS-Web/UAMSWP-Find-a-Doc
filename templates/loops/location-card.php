<?php
/**
 *  Template Name: Location Loop - Card layout
 *  Designed for UAMS Find-a-Doc
 *
 *  Must be used inside a loop
 *  Required var: $id
 */

/**
 * Set by the file that includes this partial, which runs in that file's scope (#1232).
 *
 * @var mixed $id From `get_the_ID()`, or one item of a `get_field()` list.
 */

// Reset variables
$featured_image = '';

/**
 * Which location supplies each address component. A child location may
 * override any one of them, so this replaces the flat swap to the parent
 * ID that the address fields used to read through.
 */
$card_id = uamswp_fad_int($id);
$location_source_ids = uamswp_fad_location_source_ids($card_id);

// $child_location_list indicates whether this is a list of child locations within this location
// Check if $child_location_list is set. Otherwise create the variable and set its value to false.
$child_location_list ??= false;

$location_title = get_the_title($card_id);
$location_title_attr = str_replace('"', "'", $location_title);
$location_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities(str_replace('&nbsp;', ' ', $location_title_attr), ENT_HTML401, 'utf-8')));

// Parent Location
$location_has_parent = get_field('location_parent', $id);
$location_parent_id = get_field('location_parent_id', $id);
$parent_location = ''; // Eliminate PHP errors
$parent_id = ''; // Eliminate PHP errors
$parent_title = ''; // Eliminate PHP errors
$parent_url = ''; // Eliminate PHP errors
$parent_location_prepend_the = ''; // Eliminate PHP errors
$parent_title_prepend = ''; // Eliminate PHP errors
$parent_title_phrase = ''; // Eliminate PHP errors
$override_parent_photo = ''; // Eliminate PHP errors
$override_parent_photo_featured = ''; // Eliminate PHP errors

if ($location_has_parent && $location_parent_id) {
    $parent_location = get_post(uamswp_fad_int($location_parent_id));
}
// Get Post ID for Address & Image fields
if ($parent_location) {
    $parent_id = $parent_location->ID;
    $parent_title = $parent_location->post_title;
    $parent_title_attr = $parent_title;
    $parent_title_attr = str_replace('"', "'", $parent_title_attr); // Replace double quotes with single quote
    $parent_title_attr = str_replace('&#8217;', "'", $parent_title_attr); // Replace right single quote with single quote
    $parent_title_attr = str_replace('&nbsp;', ' ', $parent_title_attr); // Convert non-breaking space entity text to a normal space before it is encoded
    $parent_title_attr = htmlentities($parent_title_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
    $parent_title_attr = str_replace('&nbsp;', ' ', $parent_title_attr); // Convert non-breaking space with normal space
    $parent_title_attr = html_entity_decode($parent_title_attr); // Convert HTML entities to their corresponding characters
    $parent_url = get_permalink($parent_id);
    $featured_image = get_the_post_thumbnail($parent_id, 'aspect-16-9-small', ['class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr, 'loading' => 'lazy']);
    $parent_location_prepend_the = get_field('location_prepend_the', $parent_id);
    $parent_title_prepend = $parent_location_prepend_the ? 'the ' : '';
    $parent_title_phrase = $parent_title_prepend.$parent_title;

    $override_parent_photo = get_field('location_image_override_parent', $id);
    $override_parent_photo_featured = get_field('location_image_override_parent_featured', $id);

    // Set featured image
    if ($override_parent_photo && $override_parent_photo_featured) {
        $featured_image = get_the_post_thumbnail($card_id, 'aspect-16-9-small', ['class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr, 'loading' => 'lazy']);
    }
} else {
    // Set featured image
    if (has_post_thumbnail($card_id)) {
        $featured_image = get_the_post_thumbnail($card_id, 'aspect-16-9-small', ['class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr, 'loading' => 'lazy']);
    }
}

$location_address_1 = uamswp_fad_str(get_field('location_address_1', $location_source_ids['street']));
$location_building = get_field('location_building', $location_source_ids['facility']);
$building_slug = '';
$building_name = '';
if ($location_building) {
    $building = get_term(uamswp_fad_int($location_building), 'building');
    $building_found = is_object($building) && ! is_wp_error($building);
    $building_slug = $building_found ? uamswp_fad_str($building->slug) : '';
    $building_name = $building_found ? uamswp_fad_str($building->name) : '';
}
/** @var array<string, mixed>|false $location_floor */
$location_floor = get_field_object('location_building_floor', $location_source_ids['unit']);
$location_floor_value = '';
$location_floor_label = '';
if ($location_floor) {
    $location_floor_value = uamswp_fad_str($location_floor['value'] ?? null);
    $location_floor_label = uamswp_fad_str(uamswp_fad_assoc($location_floor['choices'] ?? null)[$location_floor_value] ?? null);
}
$location_suite = uamswp_fad_str(get_field('location_suite', $location_source_ids['unit'])) ?: false;
$location_address_2 = uamswp_location_address_2(($location_building && $building_slug != '_none'), $building_name, $location_floor, $location_floor_value, $location_floor_label, $location_suite, '<br />', ', ');
$location_address_2_schema = uamswp_location_address_2(($location_building && $building_slug != '_none'), $building_name, $location_floor, $location_floor_value, $location_floor_label, $location_suite, ' ', ' ');

$location_address_2_deprecated = uamswp_fad_str(get_field('location_address_2', $location_source_ids['street']));
if (! $location_address_2) {
    $location_address_2 = $location_address_2_deprecated;
    $location_address_2_schema = $location_address_2_deprecated;
}

$location_city = uamswp_fad_str(get_field('location_city', $location_source_ids['street']));
$location_state = uamswp_fad_str(get_field('location_state', $location_source_ids['street']));
$location_zip = uamswp_fad_str(get_field('location_zip', $location_source_ids['street']));

?>
<div class="card">
    <?php if ($featured_image) {
        echo $featured_image;
    } else { ?>
    <picture>
        <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
        <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" data-categorytitle="Photo" data-itemtitle="<?php echo $location_title_attr; ?>" loading="lazy" />
    </picture>
    <?php } ?>
    <div class="card-body">
        <h3 class="card-title h5">
            <span class="name"><a href="<?php echo get_permalink($card_id); ?>" target="_self" data-categorytitle="Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $location_title; ?></a></span>
            <?php if ($parent_location && ! $child_location_list) { ?>
                <span class="subtitle"><span class="sr-only">(</span>Part of <?php echo $parent_title_prepend; ?><a href="<?php echo esc_url(uamswp_fad_str($parent_url)); ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
            <?php } // endif?>
            <?php if (isset($l) && $l == 1) { ?>
                <span class="subtitle"><span class="sr-only">, </span>Primary Location</span>
            <?php } ?>
        </h3>
        <?php
        // Check for if we should display a closure alert

        $location_closing = get_field('location_closing', $id); // true or false
$location_closing_date = uamswp_fad_str(get_field('location_closing_date', $id)); // F j, Y
$location_closing_date_past = false;
if (new DateTime >= new DateTime($location_closing_date)) {
    $location_closing_date_past = true;
}
$location_closing_length = get_field('location_closing_length', $id);
$location_reopen_known = get_field('location_reopen_known', $id);
$location_reopen_date = uamswp_fad_str(get_field('location_reopen_date', $id)); // F j, Y
$location_reopen_date_past = false;
if (new DateTime >= new DateTime($location_reopen_date)) {
    $location_reopen_date_past = true;
}
$location_closing_info = get_field('location_closing_info', $id);
$location_closing_display = false;
if (
    $location_closing && (
        $location_closing_length == 'permanent'
        || ($location_closing_length == 'temporary' && ! $location_reopen_date_past)
    )
) {
    $location_closing_display = true;
}

// Check for if we should display a modified hours alert

$location_hours_group = uamswp_fad_assoc(get_field('location_hours_group', $id));

$modified = $location_hours_group['location_modified_hours'] ?? null;
$modified_start = uamswp_fad_str($location_hours_group['location_modified_hours_start_date'] ?? null);
$modified_end = $location_hours_group['location_modified_hours_end'] ?? null;
$modified_end_date = uamswp_fad_str($location_hours_group['location_modified_hours_end_date'] ?? null);

$today = strtotime('today');
$today_30 = strtotime('+30 days');

$telemed_modified = $location_hours_group['location_telemed_modified_hours_query'] ?? null; // Are there modified hours for telemedicine?
$telemed_modified_start = uamswp_fad_str($location_hours_group['location_telemed_modified_hours_start_date'] ?? null); // When do the modified telemedicine hours start?
$telemed_modified_end = $location_hours_group['location_telemed_modified_hours_end'] ?? null; // Do we know when the modified telemedicine hours end?
$telemed_modified_end_date = uamswp_fad_str($location_hours_group['location_telemed_modified_hours_end_date'] ?? null); // When do the modified telemedicine hours end?

$telemed_today = $today;
$telemed_today_30 = $today_30;

// A flag counts only with its start date: `strtotime('')` is false, which compares as on or before any day (#1302).
$modified = $modified && $modified_start;
$telemed_modified = $telemed_modified && $telemed_modified_start;

// Reset, so a card in a loop never prints the date of the card before it.
$location_modified_hours_start = '';
$location_modified_hours_date_past = false;

if (
    ($modified && strtotime($modified_start) <= $today_30 && (strtotime($modified_end_date) >= $today || ! $modified_end)) ||
    ($telemed_modified && strtotime($telemed_modified_start) <= $telemed_today_30 && (strtotime($telemed_modified_end_date) >= $telemed_today || ! $telemed_modified_end))
) {
    $location_modified_hours_display = true;
} else {
    $location_modified_hours_display = false;
}

// // Set start of modified hours based on the earliest of the two (clinic and telemedicine)

if ($location_modified_hours_display) {
    if ($modified && $telemed_modified) {
        if (strtotime($modified_start) <= strtotime($telemed_modified_start)) {
            $location_modified_hours_start = $modified_start;
        } else {
            $location_modified_hours_start = $telemed_modified_start;
        }
    } elseif ($modified) {
        $location_modified_hours_start = $modified_start;
    } else {
        $location_modified_hours_start = $telemed_modified_start;
    }

    if (strtotime($location_modified_hours_start) <= $today) {
        $location_modified_hours_date_past = true;
    } else {
        $location_modified_hours_date_past = false;
    }
}

// Create the alert

if ($location_closing_display || $location_modified_hours_display) {
    $alert_label = '';
    if ($location_closing_display) {
        $alert_label = 'Learn more about the closure of '.$location_title_attr.'.';
    } elseif ($location_modified_hours_display) {
        $alert_label = 'Learn more about the modified hours.';
    }
    $alert_label_attr = $alert_label;
    $alert_label_attr = str_replace('"', "'", $alert_label_attr); // Replace double quotes with single quote
    $alert_label_attr = str_replace('&#8217;', "'", $alert_label_attr); // Replace right single quote with single quote
    $alert_label_attr = str_replace('&nbsp;', ' ', $alert_label_attr); // Convert non-breaking space entity text to a normal space before it is encoded
    $alert_label_attr = htmlentities($alert_label_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
    $alert_label_attr = str_replace('&nbsp;', ' ', $alert_label_attr); // Convert non-breaking space with normal space
    $alert_label_attr = html_entity_decode($alert_label_attr); // Convert HTML entities to their corresponding characters
    ?>
            <div class="alert alert-warning" role="alert">
                <?php if ($location_closing_display) {
                    if ($location_closing_date_past) { ?>
                        This location is <?php echo $location_closing_length == 'temporary' ? 'temporarily' : 'permanently'; ?> closed.
                    <?php } else { ?>
                        This location will be closing <?php echo $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently'; ?> on <?php echo $location_closing_date; ?>.
                    <?php } // endif
                } elseif ($location_modified_hours_display) {
                    if ($location_modified_hours_date_past) { ?>
                        This location's hours have been temporarily modified.
                    <?php } else { ?>
                        This location's hours will be temporarily modified beginning on <?php echo $location_modified_hours_start; ?>.
                    <?php } // endif
                } // endif?>
                <p><a href="<?php echo get_permalink($card_id); ?>" aria-label="<?php echo $alert_label_attr; ?>" class="alert-link" data-categorytitle="Alert" data-itemtitle="<?php echo $location_title_attr; ?>">Learn more</a></p>
            </div>
        <?php } // endif?>
        <?php $map = uamswp_fad_assoc(get_field('location_map', $location_source_ids['map'])); ?>
        <p class="card-text"><?php echo $location_address_1; ?><br/>
            <?php echo $location_address_2 ? $location_address_2.'<br/>' : ''; ?>
            <?php echo $location_city.', '.$location_state.' '.$location_zip; ?>
        </p>
        <?php
        // Phone values
        $phone_output_id = $id;
$phone_output = 'associated_locations';
include UAMS_FAD_PATH.'/templates/blocks/locations-phone.php';
?>
    </div><!-- .card-body -->
    <div class="btn-container">
        <div class="inner-container">
            <a href="<?php echo get_permalink($card_id); ?>" class="btn btn-primary" aria-label="View location page for <?php echo $location_title_attr; ?>" data-categorytitle="View Location" data-itemtitle="<?php echo $location_title_attr; ?>">View Location</a>
            <?php if ($map !== []) { ?>
            <a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo uamswp_fad_str($map['lat'] ?? null); ?>,<?php echo uamswp_fad_str($map['lng'] ?? null); ?>" target="_blank" aria-label="Get Directions to <?php echo $location_title; ?>" data-categorytitle="Get Directions" data-itemtitle="<?php echo $location_title_attr; ?>">Get Directions</a>
            <?php } ?>
        </div>
    </div>
</div><!-- .card -->