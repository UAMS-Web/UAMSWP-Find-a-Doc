<?php

/**
 * Stand-ins for the WordPress classes the helper functions test with `instanceof`.
 *
 * WordPress is not loaded in the unit process, so each class is declared here with only the properties the
 * code under test reads. A class is declared only when it does not exist yet.
 *
 * The properties carry no native type, as in WordPress, and their types live in the docblocks. Rector reads
 * these classes for every file it analyzes, `includes/` and `templates/` too, so a native type here would be
 * read as WordPress' own and could rewrite production code from it.
 */

declare(strict_types=1);

if (! class_exists('WP_Post')) {
    class WP_Post
    {
        /** @var int */
        public $ID = 0;
    }
}

if (! class_exists('WP_Term')) {
    class WP_Term
    {
        /** @var int */
        public $term_id = 0;

        /** @var string */
        public $slug = '';
    }
}

if (! class_exists('WP_Taxonomy')) {
    class WP_Taxonomy
    {
        /** @var array<string, mixed>|false */
        public $rewrite = false;

        /** @var object */
        public $labels;
    }
}

if (! class_exists('WP_Query')) {
    class WP_Query
    {
        /** @var array<int, mixed> */
        public $posts = [];

        /** @var int The number of posts the query matched over every page; the count of `$posts` unless queued. */
        public $found_posts = 0;

        /** @var list<array<int, mixed>> The post ids each query built with arguments will find, in order. */
        public static array $queue = [];

        /** @var list<int> The `found_posts` of each query built with arguments, in order. */
        public static array $found = [];

        /** @var list<array<string, mixed>> The arguments of every query built with arguments. */
        public static array $queries = [];

        /** The id of the post `the_post()` last set up. */
        public static int $current = 0;

        private int $index = 0;

        private int $checks = 0;

        /**
         * @param  array<string, mixed>  $args
         */
        public function __construct(array $args = [])
        {
            if ($args !== []) {
                self::$queries[] = $args;
                $this->posts = array_shift(self::$queue) ?? [];
                $this->found_posts = array_shift(self::$found) ?? count($this->posts);
            }
        }

        public function have_posts(): bool
        {
            // Like WordPress, a loop that has run to its end rewinds, so the next loop over the query starts again.
            if ($this->index > 0 && $this->index >= count($this->posts)) {
                $this->index = 0;

                return false;
            }

            // A loop whose the_post() call is gone would never advance; cap the checks so a mutant of it ends and fails a test.
            return $this->index < count($this->posts) && $this->checks++ < 100;
        }

        public function the_post(): void
        {
            $post = $this->posts[$this->index];
            self::$current = is_object($post) ? (int) $post->ID : (int) $post;
            $this->index++;
        }
    }
}

if (! class_exists('WP_Error')) {
    class WP_Error
    {
        public function __construct(private string $code = '', private string $message = '', private mixed $data = '') {}

        public function get_error_code(): string
        {
            return $this->code;
        }

        public function get_error_message(): string
        {
            return $this->message;
        }

        public function get_error_data(): mixed
        {
            return $this->data;
        }
    }
}
