<?php 
    /**
     *  Template Name: Treatments Loop
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var: $treatments
     */
?>
<section class="uams-module conditions-treatments bg-auto">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="module-title">Medical Treatments and Procedures Performed<?php //echo $title_append; ?></h2>
                <p class="note">UAMS providers perform and prescribe a broad range of treatments and procedures, some of which may not be listed below.</p>
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