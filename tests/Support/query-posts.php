<?php

/**
 * Published posts for the WP_Query stand-in, for metadata tests that export related content.
 *
 * The WP_Post stand-in declares only an ID; these posts add the title and slug the REST
 * metadata callbacks read, as declared properties rather than dynamic ones.
 */

declare(strict_types=1);

require_once __DIR__.'/wp-classes.php';

if (! function_exists('query_post')) {
    /**
     * A post with an ID, a title and a slug, as WP_Query returns it.
     */
    function query_post(int $id, string $title, string $slug): WP_Post
    {
        $post = new class extends WP_Post
        {
            /** @var string */
            public $post_title = '';

            /** @var string */
            public $post_name = '';
        };
        $post->ID = $id;
        $post->post_title = $title;
        $post->post_name = $slug;

        return $post;
    }
}

if (! function_exists('reset_query_stand_in')) {
    /**
     * Forget the results and arguments an earlier test left on the WP_Query stand-in.
     */
    function reset_query_stand_in(): void
    {
        WP_Query::$queue = [];
        WP_Query::$found = [];
        WP_Query::$queries = [];
    }
}
