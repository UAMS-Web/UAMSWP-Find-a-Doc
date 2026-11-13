<?php
/**
 *  Template Name: Treatments Loop
 *  Designed for UAMS Find-a-Doc
 *
 *  Must be used inside a loop
 *  Required var: $treatments
 */

/**
 * Set by the file that includes this partial, which runs in that file's scope (#1232).
 *
 * @var WP_Term_Query $treatments_query
 */
?>
<section class="uams-module conditions-treatments bg-auto" id="treatments">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="module-title"><span class="title">Medical Treatments and Procedures Performed<?php // echo $title_append;?></span></h2>
                <p class="note">UAMS Health providers perform and prescribe a broad range of treatments and procedures, some of which may not be listed below.</p>
                <div class="list-container list-container-rows">
                    <ul class="list">
                    <?php /** @var list<WP_Term> $treatment_terms */
                    $treatment_terms = $treatments_query->get_terms();
foreach ($treatment_terms as $treatment) {
    /** @var string $treatment_link */
    $treatment_link = get_term_link($treatment->term_id); ?>
                        <li>
                            <a href="<?php echo $treatment_link; ?>" aria-label="Go to Treatment page for <?php echo $treatment->name; ?>" class="btn btn-outline-primary"><?php echo $treatment->name; ?></a>
                        </li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>