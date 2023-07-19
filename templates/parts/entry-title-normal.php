<?php
/*
 * Template Name: Entry Title in the Normal Style
 * 
 * Description: A template part that displays a page header using the Normal style.
 * 
 * The page header displays a page title.
 * 
 * Optional elements include the following:
 * 	* A supertitle prepended to the page title
 * 	* A subtitle appended to the page title
 */

?>
<header class="entry-header">
	<div class="text-container">
		<h1 class="entry-title" itemprop="headline"><?php 

			if ( $entry_title_text_supertitle ) {
				?><span class="supertitle"><?php echo $entry_title_text_supertitle; ?></span><span class="sr-only">: </span><?php 
			}

			echo $entry_title_text;

			if ( $entry_title_text_subtitle ) {
				?><span class="subtitle"><?php echo $entry_title_text_subtitle; ?></span><?php 
			}

		?></h1>
	</div>
</header>
