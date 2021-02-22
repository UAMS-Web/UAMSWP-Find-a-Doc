<?php 
    /**
     *  Template Name: Conditions Loop
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var: $conditions
     */
?>
<section class="uams-module conditions-treatments bg-auto" id="conditions">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="module-title">Conditions Treated<?php //echo $title_append; ?></h2>
                <p class="note">UAMS providers care for a broad range of conditions, some of which may not be listed below.</p>
                <div class="list-container list-container-rows">
                    <ul class="list">
                    <?php foreach( $conditions_cpt_query->posts as $condition ): ?>
                        <li>
                            <a href="<?php echo get_the_permalink( $condition->ID ); ?>" aria-label="Go to Condition page for <?php echo $condition->post_title; ?>" class="btn btn-outline-primary">
                                <?php 
                                    echo $condition->post_title;
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