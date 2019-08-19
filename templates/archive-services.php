<?php get_header();

  add_filter( 'facetwp_template_use_archive', '__return_true' );

 ?>

<main class="container-fluid p-8 p-sm-10 area-of-expertise-list bg-auto">
    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Services / Areas of Expertise</h1>
            <div class="module-body">
                <p class="lead"><strong><a href="https://www.w3.org/TR/UNDERSTANDING-WCAG20/visual-audio-contrast-visual-presentation.html" target="_blank">WCAG 2.0 SC 1.4.8</a> Visual Presentation:</strong> For the visual presentation of blocks of text, a mechanism is available to achieve the following: (Level AAA)</p>
                <ul>
                    <li>Foreground and background colors can be selected by the user.</li>
                    <li><strong>Width is no more than 80 characters or glyphs</strong> (40 if <abbr title="CJK is a collective term for the Chinese, Japanese, and Korean languages">CJK</abbr>).</li>
                    <li>Text is not justified (aligned to both the left and the right margins).</li>
                    <li>Line spacing (leading) is at least space-and-a-half within paragraphs, and paragraph spacing is at least 1.5 times larger than the line spacing.</li>
                    <li>Text can be resized without assistive technology up to 200 percent in a way that does not require the user to scroll horizontally to read a line of text on a full-screen window.</li>
                </ul>
            </div>
            <h2 class="sr-only">List of Areas of Expertise</h2>
            <div class="card-list-container">
                <div class="card-list">
                    <?php echo facetwp_display( 'template', 'services' ); ?>
                    <?php //get_template_part( 'templates/physician-loop' ); ?>
                    <?php //echo facetwp_display( 'pager' ); ?>
                    <?php //echo do_shortcode('[facetwp load_more="true" label="Load more"]'); ?>
                </div>
            </div>
        </div>
    </div>
</main>



<?php get_footer(); ?>