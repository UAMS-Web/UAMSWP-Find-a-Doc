<?php
    /**
     *  Template Name: Online Scheduling Display Logic
     *  Designed for UAMS Find-a-Doc
     * 
     *  Required vars:
     *      $show_scheduling_mychart_section
     *      $scheduling_mychart_book_options
     * 
     *  Optional vars from single location template:
     *      $scheduling_mychart_intro_override
     *      $scheduling_mychart_dep
     * 
     *  Optional vars from single provider template:
     *      $scheduling_mychart_ser
     */

// Check optional vars
$scheduling_mychart_book_options = isset($scheduling_mychart_book_options) ? $scheduling_mychart_book_options : '';

// Check optional vars from single location template
$scheduling_mychart_intro_override = isset($scheduling_mychart_intro_override) ? $scheduling_mychart_intro_override : '';
$scheduling_mychart_dep = isset($scheduling_mychart_dep) ? $scheduling_mychart_dep : '';

// Check optional vars from single provider template
$scheduling_mychart_ser = isset($scheduling_mychart_ser) ? $scheduling_mychart_ser : '';

if( $show_scheduling_mychart_section ) {
    // Get system settings for constructing common parts of MyChart Open Scheduling iframe src value
    $scheduling_mychart_widget_configuration_group = get_field('mychart_scheduling_widget_configuration_group', 'option'); // ACF field containing the inputs relevant to MyChart open scheduling widget configuration
    $scheduling_mychart_domain = $scheduling_mychart_widget_configuration_group['mychart_scheduling_domain'];
    $scheduling_mychart_instance = $scheduling_mychart_widget_configuration_group['mychart_scheduling_instance'];
    $scheduling_mychart_linksource = $scheduling_mychart_widget_configuration_group['mychart_scheduling_linksource'];
    $scheduling_mychart_linksource = ( isset($scheduling_mychart_linksource) && !empty($scheduling_mychart_linksource) ) ? $scheduling_mychart_linksource : 'uamshealth.com';

    $scheduling_mychart_book_group_sys = get_field('mychart_scheduling_book_group', 'option'); // ACF field containing the inputs relevant to Appointment Booking
    $scheduling_mychart_intro = $scheduling_mychart_book_group_sys['mychart_scheduling_book_intro_location_system']; // Default value for appointment section intro
    $scheduling_mychart_intro = ( isset($scheduling_mychart_intro_override) && !empty($scheduling_mychart_intro_override) ) ? $scheduling_mychart_intro_override : $scheduling_mychart_intro;
    $i = 0;
    // Loop through repeater rows.
    if ( $scheduling_mychart_book_options ) {
        $options = $scheduling_mychart_book_options;
        foreach( $options as $option ) {
            // Load sub field value.
            $visit_type = $option['location_scheduling_vt'] ?: $option;
            $visit_type_object = get_term_by( 'id', $visit_type, 'mychart_visit_type');
            $scheduling_mychart_ser = $option['location_scheduling_ser'] ?: $scheduling_mychart_ser;
            $scheduling_mychart_dep_override = $option['location_scheduling_dep'] ?: ''; // Override at the repeater level
            $scheduling_mychart_fallback_override = $option['location_scheduling_fallback'] ?: ''; // Override at the repeater level

            // Do something...
            if ( $visit_type_object ) {
                $scheduling_mychart_title = get_field('mychart_visit_type_heading', $visit_type_object);
                $visit_type_intro = get_field('mychart_visit_type_intro', $visit_type_object);
                $scheduling_mychart_intro = ( isset($visit_type_intro) && !empty($visit_type_intro) ) ? $visit_type_intro : $scheduling_mychart_intro;
                // $visit_type_link_text = get_field('mychart_visit_type_link_text', $visit_type_object);
                $scheduling_mychart_book_options_id = get_field('mychart_visit_type_id', $visit_type_object);
                $scheduling_mychart_dep = ( isset($scheduling_mychart_dep_override) && !empty($scheduling_mychart_dep_override) ) ? $scheduling_mychart_dep_override : $scheduling_mychart_dep;
                $visit_type_fallback = get_field('mychart_visit_type_fallback', $visit_type_object);
                $scheduling_mychart_fallback = ( isset($scheduling_mychart_fallback_override) && !empty($scheduling_mychart_fallback_override) ) ? $scheduling_mychart_fallback_override : $visit_type_fallback;
                ?>
                <div id="mychart-scheduling_<?php echo $i; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mychart-scheduling_<?php echo $i; ?>_label" aria-modal="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-mychart-scheduling" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title" id="mychart-scheduling_<?php echo $i; ?>_label"><?php echo $scheduling_mychart_title; ?></h2>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?php if ( isset($scheduling_mychart_intro) && !empty($scheduling_mychart_intro)) {
                                    echo '<p>' . $scheduling_mychart_intro . '</p>';
                                } ?>
                                <div id="scheduleContainer">
                                    <iframe id="openSchedulingFrame" class="widgetframe" scrolling="no" src="https://<?php echo $scheduling_mychart_domain; ?>/<?php echo $scheduling_mychart_instance; ?>/SignupAndSchedule/EmbeddedSchedule?id=<?php echo $scheduling_mychart_ser; ?>&dept=<?php echo $scheduling_mychart_dep; ?>&vt=<?php echo $scheduling_mychart_book_options_id; ?>&linksource=<?php echo $scheduling_mychart_linksource; ?>"></iframe>
                                </div>
                                <?php echo $scheduling_mychart_fallback; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            $i++;
        } // end foreach
    } // endif ( $scheduling_mychart_book_options )
    ?>
    <!-- <link href="https://<?php echo $scheduling_mychart_domain; ?>/<?php echo $scheduling_mychart_instance; ?>/Content/EmbeddedWidget.css" rel="stylesheet" type="text/css"> -->

    <script src="https://<?php echo $scheduling_mychart_domain; ?>/<?php echo $scheduling_mychart_instance; ?>/Content/EmbeddedWidgetController.js" type="text/javascript"></script>

    <script type="text/javascript">
        var EWC = new EmbeddedWidgetController({

            // Replace with the hostname of your Open Scheduling site
            'hostname':'https://<?php echo $scheduling_mychart_domain; ?>',

            // Must equal media query in EpicWP.css + any left/right margin of the host page. Should also change in EmbeddedWidget.css
            'matchMediaString':'(max-width: 991.98px)',

            //Show a button on top of the widget that lets the user see the slots in fullscreen.
            'showToggleBtn':true,

            //The toggle button’s help text for screen reader.
            'toggleBtnExpandHelpText': 'Expand to see the slots in fullscreen',
            'toggleBtnCollapseHelpText': 'Exit fullscreen',
        });
    </script>
<?php } // endif $show_scheduling_mychart_section
?>