<?php
   	get_header();

	$treatment_title = get_field('treatments_archive_headline', 'option');
	$treatment_text = get_field('treatments_archive_intro_text', 'option');

	$term = get_queried_object();

	// Hard coded breadcrumbs
	$tax = get_term_by("slug", get_query_var("term"), get_query_var("taxonomy") );
 ?>
 <main class="doctor-item">
	<section class="container-fluid p-8 p-sm-10 bg-auto">
		<div class="row">
			<div class="col-xs-12">
				<h1 class="page-title"><?php echo ( $treatment_title ? $treatment_title : 'Treatment & Procedure' ); ?>: <?php echo single_cat_title( '', false ); ?></h1>
				<?php echo (get_field('treatment_procedure_alternate', $term) ? '<p>Possible alternate names: '. get_field('treatment_procedure_alternate', $term) . '</p>' : '' ); ?>
				<?php echo (get_field('treatment_procedure_content', $term) ? '<div class="module-body">'. get_field('treatment_procedure_content', $term) . '</div>' : '' ); ?>
			</div>
		</div>
	</section>
	<section class="container-fluid p-8 p-sm-10 cta-bar cta-bar-1 bg-auto">
		<div class="row">
			<div class="col-xs-12">
				<h2>Clinical Trials</h2>
				<p><a href="javascript:void(0)">Search our clinical trials</a> for those related to Shoulder Impingement Syndrome.</p>
			</div>
		</div>
	</section>
	<?php 
		$conditions = get_field('treatment_procedure_conditions', $term);
		
		if (!empty($conditions) && 0 < count($conditions)) {
			
	?>
	<section class="container-fluid p-8 p-sm-10 conditions-treatments bg-auto">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="module-title">Conditions</h2>
				<div class="list-container list-container-rows">
					<ul class="list">
					<?php foreach( $conditions as $condition ) { ?> 
					<li><a href="<?php echo get_term_link($condition, 'condition'); ?>"><?php echo( get_term( $condition, 'condition' )->name ); ?></a></li>
					<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<?php } // endif ?>
	<section class="container-fluid p-8 p-sm-10 bg-auto" id="doctors">
		<div class="row">
			<div class="col-12">
				<h2 class="module-title">Doctors Providing Treatments or Services for <?php echo single_cat_title( '', false ); ?></h2>
				<p class="note">Note that every treatment/service listed above may not be provided by each doctor listed below. Review each doctor for availability.</p>	
				<div class="card-list-container">
					<div class="card-list card-list-doctors facetwp-template">
						<?php echo facetwp_display( 'template', 'treatment_physicians' ); ?>
					</div>
				</div>
				<div class="row list-pagination">
					<div class="col">
						<?php echo facetwp_display( 'pager' ); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="container-fluid p-8 p-sm-10 bg-auto" id="locations">
		<div class="row">
			<div class="col-12">
				<h2 class="module-title">Locations Providing Treatments or Services for <?php echo single_cat_title( '', false ); ?></h2>
				<div class="card-list-container">
					<div class="card-list card-list-locations">
					<?php 
						$args = (array(
							'post_type' => "locations",
							'order' => 'ASC',
							'orderby' => 'title',
							'posts_per_page' => -1,
							'tax_query' => array(
								array(
								  "taxonomy" => "treatment_procedure",
								  "field" => "slug",
								  "terms" => get_queried_object()->slug,
								  "operator" => "IN"
								)
							)
						));
						$location_query = new WP_Query( $args );

						if ( $location_query->have_posts() ) : while ( $location_query->have_posts() ) : $location_query->the_post();
							$id = get_the_ID();
							include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
						endwhile; else : 
							echo '<p>'. _e( 'Sorry, no locations matched your criteria.' ) .'</p>';
				 		endif; 
					?>
					</div>
				</div>
			</div>
		</div>
	</section>	
    <section class="container-fluid p-8 p-sm-10 cta-bar cta-bar-1 bg-auto">
		<div class="row">
			<div class="col-xs-12">
				<h2>Make an Appointment</h2>
				<p>Request an appointment directly with <a href="javascript:void(0)">your clinic</a>, <a href="javascript:void(0)">your doctor</a>, <span class="no-break">or call <a href="javascript:void(0)">501-555-5555</a>.</span></p>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>