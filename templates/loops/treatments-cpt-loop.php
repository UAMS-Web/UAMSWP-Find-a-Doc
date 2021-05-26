<?php 
    /**
     *  Template Name: Treatments Loop
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var: $treatments
     *      $treatments
     *      $treatment_heading_related_resource
     *      $treatment_heading_related_condition
     *      $treatment_heading_performed
     *      $treatment_disclaimer
     */

    $treatment_heading = 'Medical Treatments and Procedures';

    if ( $treatment_heading_related_resource ) {
        $treatment_heading = 'Related ' . $treatment_heading;
    }

    if ( $treatment_heading_related_treatment ) {
        $treatment_heading = $treatment_heading . ' Related to ' . get_the_title();
    }

    if ( $treatment_heading_performed ) {
        $treatment_heading = $treatment_heading . ' Performed';
    }
?>
<section class="uams-module conditions-treatments bg-auto" id="treatments">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="module-title"><?php echo $treatment_heading; ?></h2>
                <?php if ( $treatment_disclaimer ) { ?>
                    <p class="note">UAMS providers perform and prescribe a broad range of treatments and procedures, some of which may not be listed below.</p>
                <?php } ?>
                <div class="list-container list-container-rows">
                    <ul class="list">
                    <?php foreach( $treatments_cpt_query->posts as $treatment ): ?>
                        <li>
                            <a href="<?php echo get_the_permalink( $treatment->ID ); ?>" aria-label="Go to Treatment page for <?php echo $treatment->post_title; ?>" class="btn btn-outline-primary"><?php echo $treatment->post_title; ?></a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>