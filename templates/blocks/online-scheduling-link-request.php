<?php
    /**
     *  Template Name: Online Scheduling, Links to Appointment Request Forms
     *  Designed for UAMS Find-a-Doc
     * 
     *  Required vars:
     *      $appointment_request_forms
     *      
     */

    $appointment_request_form_count = count($appointment_request_forms);
    $appointment_request_button_text = 'Request an Appointment';
?>
<div class="btn-container">
    <div class="inner-container">
        <?php
        if ( $appointment_request_form_count > 1 ) { ?>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="appt_request_form_dropdown" data-toggle="dropdown" aria-expanded="false"><?php echo $appointment_request_button_text; ?></button>
                <div class="dropdown-menu" aria-labelledby="appt_request_form_dropdown">
                    <?php foreach( $appointment_request_forms as $form ) {
                        $form_object = get_term_by( 'id', $form, 'appointment_request');
                        if ( $form_object ) {
                            $form_object_name = $form_object->name;
                            $form_object_name_attr = str_replace('"', '\'', $form_object_name);
                            $form_object_name_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($form_object_name_attr, null, 'utf-8')));
                            $form_url = get_field('appointment_request_url', $form_object);
                            ?>
                            <a class="dropdown-item" href="<?php echo $form_url; ?>" target="_blank"><?php echo $form_object_name; ?></a>
                        <?php }
                    } ?>
                </div>
            </div>
        <?php } else {
            foreach( $appointment_request_forms as $form ) {
                $form_object = get_term_by( 'id', $form, 'appointment_request');
                if ( $form_object ) {
                    $form_object_name = $form_object->name;
                    $form_object_name_attr = str_replace('"', '\'', $form_object_name);
                    $form_object_name_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($form_object_name_attr, null, 'utf-8')));
                    $form_url = get_field('appointment_request_url', $form_object);
                    ?>
                    <a class="btn btn-outline-primary" href="<?php echo $form_url; ?>" target="_blank" aria-label="<?php echo $appointment_request_button_text . ', ' . $form_object_name_attr; ?>"><?php echo $appointment_request_button_text; ?></a>
                <?php }
            }
        } ?>
    </div>
</div>