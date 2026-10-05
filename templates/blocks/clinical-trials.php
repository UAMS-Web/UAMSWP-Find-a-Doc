<?php
    /**
     *  Template Name: Clinical Trials loop / text block
     *  Designed for UAMS Find-a-Doc
     * 
     *  Required vars:
     *      $clinical_trial_title
     */
?>
<section class="uams-module cta-bar cta-bar-1 bg-auto" id="clinical-trials">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="module-title"><span class="title">Clinical Trials</span></h2>
                <p><a href="https://uams.trialstoday.org/" aria-label="Search UAMS Clinical Trials">Search our clinical trials</a> for those related to <?php echo $clinical_trial_title; ?>.</p>
            </div>
        </div>
    </div>
</section>