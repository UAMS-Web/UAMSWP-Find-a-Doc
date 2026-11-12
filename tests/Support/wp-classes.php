<?php

/**
 * Stand-ins for the WordPress classes the helper functions test with `instanceof`.
 *
 * WordPress is not loaded in the unit process, so each class is declared here with only the properties the
 * code under test reads. A class is declared only when it does not exist yet.
 */

declare(strict_types=1);

if (! class_exists('WP_Post')) {
    class WP_Post
    {
        public int $ID = 0;
    }
}

if (! class_exists('WP_Term')) {
    class WP_Term
    {
        public int $term_id = 0;

        public string $slug = '';
    }
}

if (! class_exists('WP_Taxonomy')) {
    class WP_Taxonomy
    {
        /** @var array<string, mixed>|false */
        public array|false $rewrite = false;

        public object $labels;
    }
}

if (! class_exists('WP_Query')) {
    class WP_Query
    {
        /** @var array<int, mixed> */
        public array $posts = [];

        /** @var list<array<int, mixed>> The post ids each query built with arguments will find, in order. */
        public static array $queue = [];

        /** @var list<array<string, mixed>> The arguments of every query built with arguments. */
        public static array $queries = [];

        /** The id of the post `the_post()` last set up. */
        public static int $current = 0;

        private int $index = 0;

        /**
         * @param  array<string, mixed>  $args
         */
        public function __construct(array $args = [])
        {
            if ($args !== []) {
                self::$queries[] = $args;
                $this->posts = array_shift(self::$queue) ?? [];
            }
        }

        public function have_posts(): bool
        {
            return $this->index < count($this->posts);
        }

        public function the_post(): void
        {
            self::$current = (int) $this->posts[$this->index];
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
