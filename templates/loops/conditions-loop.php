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
                <h2 class="module-title"><span class="title">Conditions Treated<?php //echo $title_append; ?></span></h2>
                <p class="note">UAMS Health providers care for a broad range of conditions, some of which may not be listed below.</p>
                <div class="list-container list-container-rows">
                    <ul class="list">
                    <?php foreach( $conditions_query->get_terms() as $condition ): ?>
                        <li>
                            <a href="<?php echo get_term_link( $condition->term_id ); ?>" aria-label="Go to Condition page for <?php echo $condition->name; ?>" class="btn btn-outline-primary">
                                <?php 
                                    echo $condition->name;
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