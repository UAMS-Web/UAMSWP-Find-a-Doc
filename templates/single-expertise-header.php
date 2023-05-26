<?php
/*
 * Expertise Header
 */

?>
<!-- /* Begin Title / Logo */ -->
<div class="global-title">
	<a href="<?php echo network_site_url(); ?>" class="navbar-brand <?php if ( !$expertise_has_parent && !$content_type && !get_query_var('fpage') ) { echo 'no-subbrand'; } ?>">
		<picture>
			<source srcset="<?php echo get_stylesheet_directory_uri(); ?>/assets/svg/uams-logo_health_horizontal_dark.svg" media="(min-width: 576px)">
			<source srcset="<?php echo get_stylesheet_directory_uri(); ?>/assets/svg/uams-logo_health_vertical_dark.svg" media="(min-width: 1px)">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/svg/uams-logo_health_horizontal_dark_386x50.png" alt="UAMS Health Logo" />
		</picture>
		<span class="sr-only">UAMS Health</span>
	</a>
	<?php if ( $expertise_has_parent || $content_type || get_query_var('fpage') ) { 
		?>
	<div class="navbar-subbrand">
		<!-- <a class="parent" href="/expertise/">Areas of Expertise</a><span class="sr-only">: </span> -->
		<a class="title" href="<?php echo get_permalink( $page_id ); ?>"><?php echo get_the_title( $page_id ); ?></a>
	</div>
	<?php } ?>
</div>
<!-- /* End Title / Logo */ -->

<!-- /* Begin Right Navbar */ -->
<nav class="header-nav" aria-label="Resource Navigation">
	<div class="collapse navbar-collapse" id="nav-secondary">
		<ul class="nav">
			<!-- Options - uamshealth -->
			<li class="nav-item">
				<a class="nav-link" href="https://www.uams.edu/">UAMS.edu</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="https://mychart.uamshealth.com/">MyChart</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="http://giving.uams.edu/">Giving</a>
			</li>
			<!-- End right nav -->
		</ul>
	</div>
	<ul class="nav resource-nav" id="nav-resource">
		<li class="nav-item">
			<a class="nav-link emergency-link" href="https://uamshealth.com/location/uams-emergency-room/" aria-label="Emergency Room"><svg class="" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ambulance" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg=""><path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48v320c0 26.5 21.5 48 48 48h16c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm144-248c0 4.4-3.6 8-8 8h-56v56c0 4.4-3.6 8-8 8h-48c-4.4 0-8-3.6-8-8v-56h-56c-4.4 0-8-3.6-8-8v-48c0-4.4 3.6-8 8-8h56v-56c0-4.4 3.6-8 8-8h48c4.4 0 8 3.6 8 8v56h56c4.4 0 8 3.6 8 8v48zm176 248c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path></svg><span class="sr-only">Emergency Room</span></a>
		</li>
		<li class="nav-item">
			<button class="search-toggler" type="button" id="toggle-search" aria-controls="header-search" aria-expanded="false" title="Toggle Search">
				<span class="sr-only label">Toggle Search</span>
				<svg class="fa-search fa-fw" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg>
				<svg class="fa-times fa-fw" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512" data-fa-i2svg=""><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>
			</button>
		</li>
	</ul>

	<style>
		.emergency-link svg {
			display: inline-block;
			height: 1em;
			overflow: visible;
			font-size: 1.3333333333em;
			line-height: .75em;
			vertical-align: -.0667em;
		}
		.search-toggler svg {
			display: inline-block;
			height: 1em;
			overflow: visible;
			font-size: 1.3333333333em;
			line-height: .75em;
			vertical-align: -.0667em;
		}
		.mobile-menu-toggler svg {
			display: inline-block;
			/* font-size: inherit; */
			height: 1em;
			overflow: visible;
			/* vertical-align: -.125em; */
			font-size: 1.3333333333em;
			line-height: .75em;
			vertical-align: -.0667em;
		}
	</style>

</nav>
