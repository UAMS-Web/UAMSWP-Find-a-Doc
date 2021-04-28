<?php
    /**
     *  Template Name: Appointments loop / text block on provider profiles
     *  Designed for UAMS Find-a-Doc
     */

    $appointment_show_main_line_sys = get_field('provider_mainline', 'option');
    
    $appointment_phone_name = 'the UAMS&nbsp;Health appointment line'; // default (UAMS)
    $appointment_phone = '5016868000'; // default (UAMS)
    $show_portal = false;
    // Portal
    if ( $physician_portal ) {
        $portal = get_term($physician_portal, "portal");
        $portal_slug = $portal->slug;
        $portal_name = $portal->name;
        $portal_name_attr = str_replace('"', '\'', $portal_name);
        $portal_name_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($portal_name_attr, null, 'utf-8')));
        $portal_content = get_field('portal_content', $portal);
        $portal_link = get_field('portal_url', $portal);
        if ($portal_link) {
            $portal_url = $portal_link['url'];
            $portal_link_title = $portal_link['title'];
        }

        if ($location_valid && $portal && $portal_slug !== "_none") {
            $show_portal = true;
        }
        if ($portal_slug == "ach-mychart") {
            $appointment_phone_name = 'the main Arkansas Children\'s Hospital appointment line';
            $appointment_phone = '5013644000';
        } elseif ($portal_slug == "my-healthevet") {
            $appointment_phone_name = 'the main Central Arkansas Veterans Healthcare System appointment line';
            $appointment_phone = '5012573999';
        }
        $appointment_phone_name_attr = str_replace('"', '\'', $appointment_phone_name);
        $appointment_phone_name_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($appointment_phone_name_attr, null, 'utf-8')));
    }
    
    $appointment_phone_tel = preg_replace('/^(\+?\d)?(\d{3})(\d{3})(\d{4})$/', '$2-$3-$4', $appointment_phone);
    $appointment_phone_text = preg_replace('/^(\+?\d)?(\d{3})(\d{3})(\d{4})$/', '($2) $3-$4', $appointment_phone);

    if (1 == $location_count) {
        $appointment_location_url = $primary_appointment_url;
        $appointment_location_title = $primary_appointment_title;
        $appointment_location_data = 'Contact the Clinic Directly | Direct Link | ' . $primary_appointment_title;
    } else {
        $appointment_location_url = '#locations';
        $appointment_location_title = 'Jump to list of locations for this provider';
        $appointment_location_data = 'Contact the Clinic Directly | Anchor Link';
    }
    $appointment_reference_referral = 'Appointments for new patients are by referral only.';
    $appointment_reference_portal = '<a href="' . $portal_url . '" aria-label="' . $portal_name . '" target="_blank" data-categorytitle="Make an Appointment | Block ' . $appointment_block_instance . '" data-typetitle="Request an Appointment Online | ' . $portal_name_attr . '">requesting an appointment online</a> through ' . $portal_name;
    $appointment_reference_direct = 'by <a href="' . $appointment_location_url . '" aria-label="' . $appointment_location_title . '" data-categorytitle="Make an Appointment | Block ' . $appointment_block_instance . '" data-typetitle="' . $appointment_location_data . '">contacting the clinic directly</a>';
    $appointment_reference_main = 'by calling ' . $appointment_phone_name . ' at <a href="tel:' . $appointment_phone_tel . '" class="no-break" data-categorytitle="Make an Appointment | Block ' . $appointment_block_instance . '" data-typetitle="Main Appointment Line | ' . $appointment_phone_name_attr . '">' . $appointment_phone_text . '</a>';
?>
<section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info-<? echo $appointment_block_instance; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h2>Make an Appointment With <?php echo $short_name; ?></h2>
                <?php if ($refer_req && $accept_new && $show_portal) { ?>
                    <p><?php echo $appointment_reference_referral; ?></p>
                    <p>Existing patients can make an appointment by <?php if ( $appointment_show_main_line_sys ) { ?>
                        <?php echo $appointment_reference_portal; ?>, <?php echo $appointment_reference_direct; ?> or <?php echo $appointment_reference_main; ?>.
                    <?php } else { ?>
                        <?php echo $appointment_reference_portal; ?> or <?php echo $appointment_reference_direct; ?>.
                    <?php } ?></p>
                <?php } elseif (!$location_valid && $refer_req && $accept_new) {
                    // Showing main appointment line reference here regardless of 
                    // decision on main appointment line, as there is no valid location 
                    // in this scenario. There would otherwise be no manner to make an 
                    // appointment with this provider.
                ?>
                    <p><?php echo $appointment_reference_referral; ?></p>
                    <p>Existing patients can make an appointment <?php echo $appointment_reference_main; ?>.</p>
                <?php } elseif ($refer_req && $accept_new) { ?>
                    <p><?php echo $appointment_reference_referral; ?></p>
                    <p>Existing patients can make an appointment <?php if ( $appointment_show_main_line_sys ) { ?>
                        <?php echo $appointment_reference_direct; ?> or <?php echo $appointment_reference_main; ?>.
                    <? } else { ?>
                        <?php echo $appointment_reference_direct; ?>.
                    <?php } ?></p>
                <?php } elseif ($accept_new && $show_portal) { ?>
                    <p>New patients can make an appointment <?php if ( $appointment_show_main_line_sys ) { ?>
                        <?php echo $appointment_reference_direct; ?> or <?php echo $appointment_reference_main; ?>.
                    <?php } else { ?>
                        <?php echo $appointment_reference_direct; ?>.
                    <?php } ?></p>
                    <p>Existing patients also have the option of <?php echo $appointment_reference_portal; ?>.</p>
                <?php } elseif (!$location_valid && $accept_new) {
                    // Showing main appointment line reference here regardless of 
                    // decision on main appointment line, as there is no valid location 
                    // in this scenario. There would otherwise be no manner to make an 
                    // appointment with this provider.
                ?>
                    <p>New and existing patients can make an appointment <?php echo $appointment_reference_main; ?>.</p>
                <?php } elseif ($accept_new) { ?>
                    <p>New and existing patients can make an appointment <?php if ( $appointment_show_main_line_sys ) { ?>
                        <?php echo $appointment_reference_direct; ?> or <?php echo $appointment_reference_main; ?>.
                    <?php } else { ?>
                        <?php echo $appointment_reference_direct; ?>.
                    <?php } ?></p>
                <?php } elseif ($show_portal) { ?>
                    <p>This provider is not currently accepting new patients.</p>
                    <p>Existing patients can make an appointment by <?php if ( $appointment_show_main_line_sys ) { ?>
                        <?php echo $appointment_reference_portal; ?>, <?php echo $appointment_reference_direct; ?> or <?php echo $appointment_reference_main; ?>.</p>
                    <?php } else { ?>
                        <?php echo $appointment_reference_portal; ?> or <?php echo $appointment_reference_direct; ?>.</p>
                    <?php } ?>
                <?php } elseif (!$location_valid) {
                    // Showing main appointment line reference here regardless of 
                    // decision on main appointment line, as there is no valid location 
                    // in this scenario. There would otherwise be no manner to make an 
                    // appointment with this provider.
                ?>
                    <p>This provider is not currently accepting new patients.</p>
                    <p>Existing patients can make an appointment <?php echo $appointment_reference_main; ?>.</p>
                <?php } else { ?>
                    <p>This provider is not currently accepting new patients.</p>
                    <p>Existing patients can make an appointment <?php if ( $appointment_show_main_line_sys ) { ?>
                        <?php echo $appointment_reference_direct; ?> or <?php echo $appointment_reference_main; ?>.
                    <?php } else { ?>
                        <?php echo $appointment_reference_direct; ?>.
                    <?php } ?></p>
                <?php } ?>
            </div>
        </div>
    </div>
</section>