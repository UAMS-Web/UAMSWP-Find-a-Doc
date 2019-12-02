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
                <h2 class="module-title">Medical Treatments and Procedures Performed<?php echo $title_append; ?></h2>
                <p class="note">UAMS doctors perform a broad range of treatments and procedures, some of which may not be listed below.</p>
                <div class="list-container list-container-rows">
                    <ul class="list">
                    <?php foreach( $treatments as $treatment ): ?>
                        <li>
                            <a href="<?php echo get_term_link( $treatment ); ?>">
                            <?php $treatment_name = get_term( $treatment, 'treatment_procedure');
                                echo $treatment_name->name;
                            ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>