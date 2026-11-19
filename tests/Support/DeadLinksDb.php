<?php

/**
 * An in-memory database for the dead link cleanup's Unit tests.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Support;

use LogicException;

/**
 * Stands in for `$wpdb` with the posts, post meta, options and custom tables the dead link cleanup reads.
 *
 * It answers only the exact SQL statements `includes/class.fad-dead-link-cleanup.php` sends, and throws on any
 * other, so a change to a statement fails the test that sent it instead of quietly reading nothing. Values come
 * back as strings, the way `wpdb` returns them.
 */
final class DeadLinksDb
{
    public string $prefix = 'wp_';

    public string $posts = 'wp_posts';

    public string $postmeta = 'wp_postmeta';

    public string $options = 'wp_options';

    /** @var array<int, string> Post ID to post type. */
    public array $post_types = [];

    /** @var array<int, array{id: int, post: int, key: string, value: string}> */
    public array $meta = [];

    /** @var array<string, array<int, array<string, string|null>>> Custom table (without the prefix) to its rows. */
    public array $tables = [];

    /** @var array<string, string> Option name to its stored (serialized) value. */
    public array $option_values = [];

    /** @var array<int, string> Every statement run, in order. */
    public array $queries = [];

    /** @var array<int, array{0: string, 1: string, 2: mixed}> Every write: [verb, table, detail]. */
    public array $writes = [];

    /** @var array<int, array<int, mixed>> The WordPress and ACF calls the tests stub, each as [function, ...arguments]. */
    public array $calls = [];

    /** @var array<string, true> Options whose writes are lost while they are listed, as a failed database write loses them. */
    public array $lose = [];

    /** @var array<string, int> Options whose writes are lost after this many have been stored. */
    public array $keep_writes = [];

    /** @var array<string, true> Custom tables whose deletes and inserts fail. */
    public array $refuse = [];

    /** @var bool Whether get_results() fails, returning null as wpdb does on a query error. */
    public bool $fail_results = false;

    private int $next_meta = 1;

    /**
     * Add a post of a type.
     */
    public function post(int $id, string $type): void
    {
        $this->post_types[$id] = $type;
    }

    /**
     * Add a post meta row.
     */
    public function add_meta(int $post, string $key, string $value): void
    {
        $this->meta[] = ['id' => $this->next_meta++, 'post' => $post, 'key' => $key, 'value' => $value];
    }

    /**
     * The post meta rows of a post, as [key, value] in the order they were added.
     *
     * @return array<int, array{0: string, 1: string}>
     */
    public function meta_of(int $post): array
    {
        $rows = [];

        foreach ($this->meta as $row) {
            if ($row['post'] === $post) {
                $rows[] = [$row['key'], $row['value']];
            }
        }

        return $rows;
    }

    public function esc_like(string $text): string
    {
        return addcslashes($text, '_%\\');
    }

    public function prepare(string $sql, mixed ...$args): string
    {
        if (count($args) === 1 && is_array($args[0])) {
            $args = $args[0];
        }

        return preg_replace_callback('/%[sd]/', function (array $match) use (&$args): string {
            $arg = array_shift($args);

            if ($arg === null) {
                throw new LogicException('prepare() has more placeholders than arguments');
            }

            return $match[0] === '%d' ? (string) (int) $arg : "'".addslashes((string) $arg)."'";
        }, $sql).($args === [] ? '' : throw new LogicException('prepare() has more arguments than placeholders'));
    }

    public function get_var(string $sql): ?string
    {
        $this->queries[] = $sql;

        if (preg_match("/^SHOW TABLES LIKE '(.+)'$/", $sql, $m)) {
            // Undo prepare()'s quoting, then esc_like()'s escapes.
            $name = (string) preg_replace('/\\\\(.)/', '$1', stripslashes($m[1]));

            return str_starts_with($name, $this->prefix) && isset($this->tables[substr($name, strlen($this->prefix))]) ? $name : null;
        }

        if (preg_match("/^SELECT MD5\\(option_value\\) FROM wp_options WHERE option_name = '([^']+)'$/", $sql, $m)) {
            return isset($this->option_values[$m[1]]) ? md5($this->option_values[$m[1]]) : null;
        }

        if (preg_match('/^SELECT COUNT\\(\\*\\) FROM wp_(\\w+) WHERE post_id = (\\d+)$/', $sql, $m)) {
            return (string) count($this->table_rows($m[1], (int) $m[2]));
        }

        throw new LogicException('Unexpected get_var(): '.$sql);
    }

    /**
     * @return array<int, string>
     */
    public function get_col(string $sql): array
    {
        $this->queries[] = $sql;

        if (preg_match('/^SELECT ID FROM wp_posts WHERE ID IN \\(([\\d,]+)\\)$/', $sql, $m)) {
            $found = [];

            foreach (explode(',', $m[1]) as $id) {
                if (isset($this->post_types[(int) $id])) {
                    $found[] = $id;
                }
            }

            return $found;
        }

        throw new LogicException('Unexpected get_col(): '.$sql);
    }

    /**
     * @return array<int, array<string, string|null>>|null
     */
    public function get_results(string $sql, string $output = 'OBJECT'): ?array
    {
        $this->queries[] = $sql;
        $this->array_a($output);

        if ($this->fail_results) {
            return null;
        }

        if (preg_match("/^SELECT m\\.post_id, m\\.meta_value FROM wp_postmeta m INNER JOIN wp_posts p ON p\\.ID = m\\.post_id WHERE p\\.post_type = '([^']+)' AND m\\.meta_key = '([^']+)' AND m\\.meta_value <> ''$/", $sql, $m)) {
            $rows = [];

            foreach ($this->meta as $row) {
                if ($row['key'] === $m[2] && $row['value'] !== '' && ($this->post_types[$row['post']] ?? null) === $m[1]) {
                    $rows[] = ['post_id' => (string) $row['post'], 'meta_value' => $row['value']];
                }
            }

            return $rows;
        }

        if (preg_match("/^SELECT t\\.post_id, t\\.`(\\w+)` AS links FROM wp_(\\w+) t INNER JOIN wp_posts p ON p\\.ID = t\\.post_id WHERE t\\.`\\1` <> ''$/", $sql, $m)) {
            $rows = [];

            foreach ($this->table_rows($m[2]) as $row) {
                $value = $row[$m[1]] ?? null;

                if (isset($this->post_types[(int) $row['post_id']]) && $value !== null && $value !== '') {
                    $rows[] = ['post_id' => (string) $row['post_id'], 'links' => $value];
                }
            }

            return $rows;
        }

        if (preg_match('/^SELECT t\\.\\* FROM wp_(\\w+) t LEFT JOIN wp_posts p ON p\\.ID = t\\.post_id WHERE p\\.ID IS NULL$/', $sql, $m)) {
            return array_values(array_filter($this->table_rows($m[1]), fn (array $row): bool => ! isset($this->post_types[(int) $row['post_id']])));
        }

        if (preg_match("/^SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = (\\d+) AND meta_key IN \\('([^']+)', '([^']+)'\\) ORDER BY meta_id$/", $sql, $m)) {
            $rows = [];

            foreach ($this->meta as $row) {
                if ($row['post'] === (int) $m[1] && in_array($row['key'], [$m[2], $m[3]], true)) {
                    $rows[] = ['meta_key' => $row['key'], 'meta_value' => $row['value']];
                }
            }

            return $rows;
        }

        throw new LogicException('Unexpected get_results(): '.$sql);
    }

    /**
     * @return array<string, string|null>|null
     */
    public function get_row(string $sql, string $output = 'OBJECT'): ?array
    {
        $this->queries[] = $sql;
        $this->array_a($output);

        if (preg_match('/^SELECT `(\\w+)` AS cell FROM wp_(\\w+) WHERE post_id = (\\d+)$/', $sql, $m)) {
            $row = $this->table_rows($m[2], (int) $m[3])[0] ?? null;

            return $row === null ? null : ['cell' => $row[$m[1]] ?? null];
        }

        if (preg_match('/^SELECT \\* FROM wp_(\\w+) WHERE post_id = (\\d+)$/', $sql, $m)) {
            return $this->table_rows($m[1], (int) $m[2])[0] ?? null;
        }

        throw new LogicException('Unexpected get_row(): '.$sql);
    }

    /**
     * @param  array<string, int|string>  $where
     * @param  array<int, string>  $format
     */
    public function delete(string $table, array $where, array $format = []): int|false
    {
        $this->writes[] = ['delete', $table, [$where, $format]];

        if ($table === $this->postmeta) {
            $this->format_is($format, ['%d', '%s']);
            $before = count($this->meta);
            $this->meta = array_values(array_filter($this->meta, fn (array $row): bool => $row['post'] !== (int) $where['post_id'] || $row['key'] !== $where['meta_key']));

            return $before - count($this->meta);
        }

        $name = $this->custom($table);
        $this->format_is($format, ['%d']);

        if (isset($this->refuse[$name])) {
            return false;
        }

        $before = count($this->tables[$name]);
        $this->tables[$name] = array_values(array_filter($this->tables[$name], fn (array $row): bool => (int) $row['post_id'] !== (int) $where['post_id']));

        return $before - count($this->tables[$name]);
    }

    /**
     * @param  array<string, int|string|null>  $data
     */
    public function insert(string $table, array $data): int|false
    {
        $this->writes[] = ['insert', $table, $data];

        if ($table === $this->postmeta) {
            $this->add_meta((int) $data['post_id'], (string) $data['meta_key'], (string) $data['meta_value']);

            return 1;
        }

        $name = $this->custom($table);

        if (isset($this->refuse[$name])) {
            return false;
        }

        $this->tables[$name][] = array_map(fn (mixed $value): ?string => $value === null ? null : (string) $value, $data);

        return 1;
    }

    /**
     * @param  array<string, string|null>  $data
     * @param  array<string, int>  $where
     */
    public function update(string $table, array $data, array $where): int
    {
        $this->writes[] = ['update', $table, [$data, $where]];
        $name = $this->custom($table);
        $changed = 0;

        foreach ($this->tables[$name] as $i => $row) {
            if ((int) $row['post_id'] === (int) $where['post_id']) {
                $this->tables[$name][$i] = array_replace($row, $data);
                $changed++;
            }
        }

        return $changed;
    }

    /**
     * Store an option as `update_option()` would, unless its writes are being lost.
     */
    public function store_option(string $name, string $serialized): void
    {
        if (isset($this->keep_writes[$name])) {
            if ($this->keep_writes[$name] === 0) {
                return;
            }

            $this->keep_writes[$name]--;
        }

        if (! isset($this->lose[$name])) {
            $this->option_values[$name] = $serialized;
        }
    }

    /**
     * The rows of a custom table, optionally only those of one post.
     *
     * @return array<int, array<string, string|null>>
     */
    private function table_rows(string $table, ?int $post = null): array
    {
        if (! isset($this->tables[$table])) {
            throw new LogicException('No custom table '.$table);
        }

        return array_values(array_filter($this->tables[$table], fn (array $row): bool => $post === null || (int) $row['post_id'] === $post));
    }

    /**
     * The custom table a prefixed name names.
     */
    private function custom(string $table): string
    {
        $name = str_starts_with($table, $this->prefix) ? substr($table, strlen($this->prefix)) : '';

        if (! isset($this->tables[$name])) {
            throw new LogicException('No custom table '.$table);
        }

        return $name;
    }

    private function array_a(string $output): void
    {
        if ($output !== 'ARRAY_A') {
            throw new LogicException('Rows must be read as ARRAY_A, not '.$output);
        }
    }

    /**
     * @param  array<int, string>  $format
     * @param  array<int, string>  $expected
     */
    private function format_is(array $format, array $expected): void
    {
        if ($format !== $expected) {
            throw new LogicException('Unexpected delete format '.json_encode($format));
        }
    }
}
