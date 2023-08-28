<?php
/*
 * Template Name: UAMS Health Talk Podcast
 * 
 * Description: A template part that lists any episodes from the UAMS Health Talk 
 * podcast which are related to the content of the current page
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	$podcast_section_show // bool
 * 	$podcast_name // string
 * 	$podcast_filter // string enum('tag', 'doctor')
 * 	$podcast_subject // string
 */

// Get system settings for provider labels
include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );

if ( $podcast_section_show ) {

	$podcast_filter_id = array (
		'tag'		=> '303',
		'doctor'	=> '303,1837'
	)

	?>
	<section class="uams-module podcast-list bg-auto" id="podcast">
		<script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">
		</script>
		<script type="text/javascript">
			radiomd_embedded_filtered_<?php echo $podcast_filter; ?>("uams","radiomd-embedded-filtered-<?php echo $podcast_filter; ?>",<?php echo $podcast_filter_id[$podcast_filter]; ?>,"<?php echo $podcast_name; ?>");
		</script>
		<style type="text/css">
			#radiomd-embedded-filtered-<?php echo $podcast_filter; ?> iframe {
				width: 100%;
				border: none;
			}
		</style>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title"><span class="title">UAMS Health Talk Podcast</span></h2>
					<div class="module-body text-center">
						<p class="lead">In the UAMS Health Talk podcast, experts from UAMS talk about a variety of health topics, providing tips and guidelines to help people lead healthier lives. Listen to the episode(s) featuring <?php echo ( $podcast_filter == 'tag' ) ? 'the topic of ' : ''; ?><?php echo $podcast_subject; ?>.</p>
					</div>
					<div class="content-width mt-8" id="radiomd-embedded-filtered-<?php echo $podcast_filter; ?>"></div>
				</div>
				<div class="col-12 more">
					<p class="lead">Find other great episodes on other topics and from other UAMS Health <?php echo strtolower($provider_plural_name); ?>.</p>
					<div class="cta-container">
						<a href="/podcast/" class="btn btn-primary" aria-label="Listen to more episodes of the UAMS Health Talk podcast">Listen to More Episodes</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php

} // endif ( $podcast_section_show )
