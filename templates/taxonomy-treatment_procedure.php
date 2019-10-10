<?php
   	get_header();

	function custom_field_excerpt($title) {
		global $post;
		$text = get_field($title);
		if ( '' != $text ) {
			$text = strip_shortcodes( $text );
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]>', $text);
			$excerpt_length = 35; // 35 words
			$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
			$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
		}
		return apply_filters('the_excerpt', $text);
	}
	function wpdocs_custom_excerpt_length( $length ) {
	    return 35; // 35 words
	}
	add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

	$condition_title = get_field('treatments_archive_headline', 'option');
	$condition_text = get_field('treatments_archive_intro_text', 'option');

	// Hard coded breadcrumbs
	$tax = get_term_by("slug", get_query_var("term"), get_query_var("taxonomy") );
 ?>
 <main class="doctor-item">
	<section class="container-fluid p-8 p-sm-10 bg-auto">
		<div class="row">
			<div class="col-xs-12">
				<h1 class="page-title"><?php echo ( $condition_title ? $condition_title : 'Treatment' ); ?>: <?php echo single_cat_title( '', false ); ?></h1>
				<?php echo (get_field('treatments_content') ? '<div class="module-body">'. get_field('treatments_content') . '</div>' : '' ); ?>
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
		$conditions = get_field('treatments_conditions');
		//echo count($treatments);

		// print_r($conditions);
		
		if (0 < count($conditions)) {
			
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
	<?php } ?>
	<section class="container-fluid p-8 p-sm-10 bg-auto" id="doctors">
		<div class="row">
			<div class="col-12">
				<h2 class="module-title">Doctors Providing Treatments or Services for <?php echo single_cat_title( '', false ); ?></h2>
				<p class="note">Note that every treatment/service listed above may not be provided by each doctor listed below. Review each doctor for availability.</p>	
				<div class="card-list-container">
					<?php echo facetwp_display( 'template', 'condition' ); ?>
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