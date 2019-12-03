<?php 
    /**
     *  Template Name: Conditions Loop
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var: $conditions
     */
?>
<section class="uams-module conditions-treatments bg-auto">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="module-title">Conditions Treated<?php //echo $title_append; ?></h2>
                <p class="note">UAMS doctors treat a broad range of conditions, some of which may not be listed below.</p>
                <div class="list-container list-container-rows">
                    <ul class="list">
                    <?php foreach( $conditions as $condition ): ?>
                        <li>
                            <a href="<?php echo get_term_link( $condition ); ?>">
                                <?php $condition_name = get_term( $condition, 'condition');
                                    echo $condition_name->name;
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