<?php
    /**
     *  Template Name: Online Scheduling Display Logic
     *  Designed for UAMS Find-a-Doc
     * 
     *  Required vars:
     *      $show_mychart_scheduling_section
     * 
     *  Optional vars from single location template:
     *      $mychart_scheduling_options
     *      $mychart_scheduling_intro_override
     *      $mychart_scheduling_dep
     * 
     *  Optional vars from single provider template:
     *      
     */

if( $show_mychart_scheduling_section ) {
    // Get system settings for constructing common parts of MyChart Open Scheduling iframe src value
    $mychart_scheduling_domain = get_field('mychart_scheduling_domain', 'option');
    $mychart_scheduling_instance = get_field('mychart_scheduling_instance', 'option');
    $mychart_scheduling_linksource = get_field('mychart_scheduling_linksource', 'option');
    $mychart_scheduling_linksource = ( isset($mychart_scheduling_linksource) && !empty($mychart_scheduling_linksource) ) ? $mychart_scheduling_linksource : 'uamshealth.com';

    $mychart_scheduling_intro = 'Use your UAMS Health MyChart account to schedule an appointment at this clinic. If you are not a MyChart user, you can continue as a guest.'; // Default value for appointment section intro
    $mychart_scheduling_intro = ( isset($mychart_scheduling_intro_override) && !empty($mychart_scheduling_intro_override) ) ? $mychart_scheduling_intro_override : $mychart_scheduling_intro;
    $i = 0;
    // Loop through repeater rows.
    if ( $mychart_scheduling_options ) {
        foreach( $mychart_scheduling_options as $option ) {
            // Load sub field value.
            $visit_type = $option['location_scheduling_vt'];
            $visit_type_object = get_term_by( 'id', $visit_type, 'mychart_visit_type');
            $mychart_scheduling_ser = $option['location_scheduling_ser'];
            $mychart_scheduling_dep_override = $option['location_scheduling_dep']; // Override at the repeater level
            $mychart_scheduling_fallback_override = $option['location_scheduling_fallback']; // Override at the repeater level

            // Do something...
            if ( $visit_type_object ) {
                $mychart_scheduling_title = get_field('mychart_visit_type_heading', $visit_type_object);
                $visit_type_intro = get_field('mychart_visit_type_intro', $visit_type_object);
                $mychart_scheduling_intro = ( isset($visit_type_intro) && !empty($visit_type_intro) ) ? $visit_type_intro : $mychart_scheduling_intro;
                // $visit_type_link_text = get_field('mychart_visit_type_link_text', $visit_type_object);
                $mychart_scheduling_visit_type_id = get_field('mychart_visit_type_id', $visit_type_object);
                $mychart_scheduling_dep = ( isset($mychart_scheduling_dep_override) && !empty($mychart_scheduling_dep_override) ) ? $mychart_scheduling_dep_override : $mychart_scheduling_dep;
                $visit_type_fallback = get_field('mychart_visit_type_fallback', $visit_type_object);
                $mychart_scheduling_fallback = ( isset($mychart_scheduling_fallback_override) && !empty($mychart_scheduling_fallback_override) ) ? $mychart_scheduling_fallback_override : $visit_type_fallback;
                ?>
                <div id="mychart-scheduling_<?php echo $i; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mychart-scheduling_<?php echo $i; ?>_label" aria-modal="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-mychart-scheduling" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title" id="mychart-scheduling_<?php echo $i; ?>_label"><?php echo $mychart_scheduling_title; ?></h2>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?php if ( isset($mychart_scheduling_intro) && !empty($mychart_scheduling_intro)) {
                                    echo '<p>' . $mychart_scheduling_intro . '</p>';
                                } ?>
                                <div id="scheduleContainer">
                                    <iframe id="openSchedulingFrame" class="widgetframe" scrolling="no" src="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/SignupAndSchedule/EmbeddedSchedule?id=<?php echo $mychart_scheduling_ser; ?>&dept=<?php echo $mychart_scheduling_dep; ?>&vt=<?php echo $mychart_scheduling_visit_type_id; ?>&linksource=<?php echo $mychart_scheduling_linksource; ?>"></iframe>
                                </div>
                                <?php echo $mychart_scheduling_fallback; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            $i++;
        } // end foreach
    } // endif $mychart_scheduling_options
    ?>
    <!-- <link href="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/Content/EmbeddedWidget.css" rel="stylesheet" type="text/css"> -->

    <script src="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/Content/EmbeddedWidgetController.js" type="text/javascript"></script>

    <script type="text/javascript">
        var EWC = new EmbeddedWidgetController({

            // Replace with the hostname of your Open Scheduling site
            'hostname':'https://<?php echo $mychart_scheduling_domain; ?>',

            // Must equal media query in EpicWP.css + any left/right margin of the host page. Should also change in EmbeddedWidget.css
            'matchMediaString':'(max-width: 991.98px)',

            //Show a button on top of the widget that lets the user see the slots in fullscreen.
            'showToggleBtn':true,

            //The toggle button’s help text for screen reader.
            'toggleBtnExpandHelpText': 'Expand to see the slots in fullscreen',
            'toggleBtnCollapseHelpText': 'Exit fullscreen',
        });
    </script>
<?php } // endif $show_mychart_scheduling_section
?>