<?php
	/**
	 *  Template Name: Services
	 *  Designed for services single
	 */

	get_header();
   	$sidebar = get_post_meta($post->ID, "sidebar");
   	$breadcrumbs = get_post_meta($post->ID, "breadcrumb"); 

	$images = rwmb_meta( 'service_header_image', array( 'limit' => 1 ) );
	$image = reset( $images );
	$mobileimgs = rwmb_meta( 'service_header_mobile_image', array( 'limit' => 1 ) );
	$mobileimg = reset( $mobileimgs );

	$url = $image['full_url'];
	if(!$url){
		$url = get_site_url() . "/wp-content/themes/uams-2016/assets/headers/uams-pattern-grey.png";
	}
	$darktext = rwmb_meta("header_dark_text");
	$hasdarktext = ( $darktext ? ' hero-text-dark' : '');
	$hasmobileimage = '';
	$mobileimage = $mobileimg['full_url'];
	$hasmobileimage = ( !empty($mobileimage) ? ' hero-mobile-image' : '' );
?>

<div class="uams-hero-image hero-height<?php echo $hasmobileimage; ?>" style="background-image: url(<?php echo $url; ?>);">
    <?php if( get_field('home_image_mobile') ) { ?>
    <div class="mobile-image" style="background-image: url(<?php echo $mobileimage; ?>);"></div>
    <?php } ?>
    <div id="hero-bg">
      <div id="hero-container" class="container">
        <h1 class="uams-site-title<?php echo $hasdarktext; ?>"><?php echo (get_field('home_image_title') ? get_field('home_image_title') : get_the_title() ); ?></h1>
        <span class="udub-slant"><span></span></span>
      </div>
    </div>
</div>

<?php 
	if( rwmb_meta('action_bar_active') && !empty( rwmb_meta('action_menu') ) ) { //:
	?>

<div class="full-bar">
	<nav aria-label="popular links" class="container action-bar">
		<ul class="center-block">
		<?php
		// Get count for class
		$rows = rwmb_meta('action_menu');
		$row_count = count($rows);
		// loop through the rows of data
		foreach ( $rows as $row ) {
			// vars
			$linktitle = $row['action_link_title'];
			$icon = $row['action_link_icon'];
			$actionurl = $row['action_link_url'];
		?>
			<li class="ab-1_<?php echo $row_count; ?>"><a href="<?php echo ($actionurl); ?>" title="<?php echo $linktitle; ?>"><i class="icon <?php echo $icon; ?>"></i><span><?php echo $linktitle; ?></span></a></li>
		<?php
		} ?>
		</ul>
	</nav>
</div>
<?
	} //endif;
?>

<div class="container uams-body">

  <div class="row">

	<?php 
		//if(have_posts()): 
			//while(have_posts()): the_post();
	?>

    <div class="hero-content col-md-<?php echo (($sidebar[0]!="on") ? "8" : "12" ); ?>  uams-content" role='main'>

      <?php
	      	get_template_part( 'breadcrumbs' );
	  ?>

      <div id='main_content' class="uams-body-copy" tabindex="-1">

		<h1><?php the_title(); ?></h1>

		<div id="mobile-sidebar">

			<button id="mobile-sidebar-menu" aria-hidden="true" tabindex="1">

				<div aria-hidden="true" id="ham">
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
				<div id="mobile-sidebar-title" class="page_item">

					<?php
						//limitation of the characters
						$text = get_the_title();
						echo text_cut($text, 27, true);
						function text_cut($text, $length, $dots) {
							//$text =get_the_title();
							$text = trim(preg_replace('#[\s\n\r\t]{2,}#', ' ', $text));
							$text_temp = $text;
							while (substr($text, $length, 1) != " ") {
								$length--;
								if ($length > strlen($text)) {
									break;
								}
							}
							$text = substr($text, 0, $length);
							$text .= ( ( ($dots == true) && ($text != '') && (strlen($text_temp) > $length) ) ? '...' : '' );
							return $text;
						}
					?>

				</div>
			</button>
			<div id="mobile-sidebar-links" aria-hidden="true">  <?php uams_services_menu_mobile(); ?></div>
		</div>
		<?php
			// Start the loop.
			while ( have_posts() ) : the_post();
				the_content();
            // End of the loop.
        	endwhile;
        ?>
      </div>

    </div>

    <div id="sidebar">
	<?php
	  if(!isset($sidebar[0]) || ($sidebar[0]!="on")) { 
		?>
			
			<div class="col-md-4 uams-sidebar">
			<?php uams_services_menu(); ?>
			<?php dynamic_sidebar( UAMS_Sidebar::ID ); ?>
			</div>
		<?php
      }
    ?>
		
	</div>
	<?php //endwhile; ?>
	<?php //endif;?>
  </div>

</div>
	
<?php get_footer(); ?>