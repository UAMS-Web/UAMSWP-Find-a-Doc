<?php 
    /**
     *  Template Name: Treatments Loop
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var: $treatments
     */
?>
<section class="uams-module conditions-treatments bg-auto" id="treatments">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="module-title"><span class="title">Medical Treatments and Procedures Performed<?php //echo $title_append; ?></span></h2>
                <p class="note">UAMS Health providers perform and prescribe a broad range of treatments and procedures, some of which may not be listed below.</p>
                <div class="list-container list-container-rows">
                    <ul class="list">
                    <?php foreach( $treatments_query->get_terms() as $treatment ): ?>
                        <li>
                            <a href="<?php echo get_term_link( $treatment->term_id ); ?>" aria-label="Go to Treatment page for <?php echo $treatment->name; ?>" class="btn btn-outline-primary"><?php echo $treatment->name; ?></a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>