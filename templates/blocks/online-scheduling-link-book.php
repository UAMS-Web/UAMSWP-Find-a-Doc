<?php
    /**
     *  Template Name: Online Scheduling, MyChart Open Scheduling Widget Modal Toggles
     *  Designed for UAMS Find-a-Doc
     * 
     *  Required vars:
     * 
     *  Optional vars:
     *      $mychart_scheduling_options_rows
     */
?>
<div class="btn-container">
    <div class="inner-container">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="mychart_scheduling_dropdown" data-toggle="dropdown" aria-expanded="false">Book an Appointment</button>
            <div class="dropdown-menu" aria-labelledby="mychart_scheduling_dropdown">
                <?php 
                $i = 0;
                // Loop through rows.
                while( $mychart_scheduling_options_rows ) {
                    the_row();
                    // Load sub field value.
                    $visit_type = get_sub_field('location_scheduling_vt');
                    $visit_type_object = get_term_by( 'id', $visit_type, 'mychart_visit_type');

                    // Do something...
                    if ( $visit_type_object ) {
                        $visit_type_link_text = get_field('mychart_visit_type_link_text', $visit_type_object);
                        ?>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#mychart-scheduling_<?php echo $i; ?>"><?php echo $visit_type_link_text; ?></a>
                    <?php }
                    $i++;

                } // endwhile (have_rows) ?>
            </div>
        </div>
    </div>
</div>