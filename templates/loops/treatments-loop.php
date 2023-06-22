<?php 
/**
 * Template Name: Treatments Loop
 * Designed for UAMS Find-a-Doc
 * 
 * Must be used inside a loop
 * 
 * Required vars:
 * 	$provider_plural_name // System setting for Providers plural item name
 * 	$treatment_single_name_attr // Attribute value friendly version of system setting for Treatments single item name
 * 	$treatment_plural_name // System setting for Treatments plural item name
 * 	$treatments
 */
?>
<section class="uams-module conditions-treatments bg-auto" id="treatments">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="module-title"><span class="title"><?php echo $treatment_plural_name; ?> Performed</span></h2>
				<p class="note">UAMS Health <?php echo strtolower($provider_plural_name); ?> perform and prescribe a broad range of <?php echo strtolower($treatment_plural_name); ?>, some of which may not be listed below.</p>
				<div class="list-container list-container-rows">
					<ul class="list">
					<?php foreach( $treatment_query->get_terms() as $treatment ): ?>
						<li>
							<a href="<?php echo get_term_link( $treatment->term_id ); ?>" aria-label="Go to <?php echo $treatment_single_name_attr; ?> page for <?php echo $treatment->name; ?>" class="btn btn-outline-primary"><?php echo $treatment->name; ?></a>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>