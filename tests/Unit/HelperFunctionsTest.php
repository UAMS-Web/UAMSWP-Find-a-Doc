<?php

/**
 * Pins the date and time helpers, the list splitter, the taxonomy archive link and breadcrumbs, the custom
 * table query, the name escaper, the indefinite article and the typed readers (class.fad-helper-functions.php).
 *
 * The provider name, occupation, pronoun and spotlight helpers in the same file have their own tests.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Filters\expectApplied;
use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/wp-classes.php';

final class HelperFunctionsTest extends UnitTestCase
{
    private string $timezone_before = '';

    /** @var array<string, mixed> */
    private array $post_before = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->timezone_before = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $this->post_before = $_POST;
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->timezone_before);
        $_POST = $this->post_before;

        parent::tearDown();
    }

    private function at(int $year, int $month, int $day, int $hour = 12, int $minute = 0): int
    {
        return gmmktime($hour, $minute, 0, $month, $day, $year);
    }

    /**
     * A timestamp in the current year that is not today.
     */
    private function this_year_not_today(): int
    {
        $today = new \DateTimeImmutable('today 12:00');
        $other = ($today->format('m-d') === '12-31') ? $today->modify('-1 day') : $today->modify('+1 day');

        return $other->getTimestamp();
    }

    /**
     * @return array<string, array{0: int, 1: string}>
     */
    public static function past_dates(): array
    {
        return [
            'January' => [1578225600, 'Sunday, Jan.  5, 2020'],
            'February' => [1581854400, 'Sunday, Feb.  16, 2020'],
            'March' => [1584446400, 'Tuesday, March 17, 2020'],
            'April' => [1587124800, 'Friday, April 17, 2020'],
            'May' => [1589716800, 'Sunday, May 17, 2020'],
            'June' => [1592395200, 'Wednesday, June 17, 2020'],
            'July' => [1594987200, 'Friday, July 17, 2020'],
            'August' => [1597665600, 'Monday, Aug.  17, 2020'],
            'September' => [1600344000, 'Thursday, Sept.  17, 2020'],
            'October' => [1602936000, 'Saturday, Oct.  17, 2020'],
            'November' => [1605614400, 'Tuesday, Nov.  17, 2020'],
            'December' => [1608206400, 'Thursday, Dec.  17, 2020'],
        ];
    }

    #[DataProvider('past_dates')]
    public function test_a_past_date_prints_the_weekday_the_ap_month_the_day_and_the_year(int $timestamp, string $expected): void
    {
        $this->assertSame($expected, \ap_date($timestamp));
    }

    public function test_the_weekday_and_the_year_can_be_left_off_for_a_past_date(): void
    {
        $this->assertSame(' Jan.  5, 2020', \ap_date(1578225600, true, true, true, false));
        $this->assertSame('Sunday, Jan.  5, 2020', \ap_date('1578225600', false, false, false, true), 'a past year keeps its year');
    }

    public function test_a_date_this_year_can_leave_the_year_off(): void
    {
        $timestamp = $this->this_year_not_today();
        $with = \ap_date($timestamp, true, true, true, false);
        $without = \ap_date($timestamp, true, true, false, false);

        $this->assertSame($with, $without.', '.date('Y', $timestamp));
        $this->assertStringNotContainsString(date('Y', $timestamp), $without);
    }

    public function test_today_is_written_as_today_in_either_case(): void
    {
        $now = time();

        $this->assertSame('Today', \ap_date($now));
        $this->assertSame('today', \ap_date($now, true, false));
    }

    public function test_today_is_written_as_a_date_when_asked_not_to_say_today(): void
    {
        $now = time();
        $date = \ap_date($now, false);

        $this->assertStringStartsWith(date('l,', $now), $date);
        $this->assertStringContainsString(date('Y', $now), $date);
        $this->assertStringNotContainsString('oday', $date);
    }

    /**
     * @return array<string, array{0: int, 1: bool, 2: string}>
     */
    public static function times(): array
    {
        return [
            'midnight' => [0, true, 'Midnight'],
            'midnight lower case' => [0, false, 'midnight'],
            'noon' => [43200, true, 'Noon'],
            'noon lower case' => [43200, false, 'noon'],
            'the top of the morning hour' => [32400, true, '9 a.m.'],
            'a morning time' => [32700, true, '9:05 a.m.'],
            'the top of an afternoon hour' => [46800, true, '1 p.m.'],
            'an afternoon time' => [55800, true, '3:30 p.m.'],
            'just after midnight' => [60, true, '12:01 a.m.'],
            'just after noon' => [43260, true, '12:01 p.m.'],
            'late evening' => [84600, true, '11:30 p.m.'],
        ];
    }

    #[DataProvider('times')]
    public function test_a_time_is_written_in_ap_style(int $seconds, bool $capitalize, string $expected): void
    {
        $this->assertSame($expected, \ap_time($seconds, $capitalize));
        $this->assertSame($expected, \apStyleTime(gmdate('H:i', $seconds), $capitalize));
    }

    public function test_a_time_span_in_one_half_of_the_day_names_the_meridian_once(): void
    {
        $this->assertSame('9 &ndash; 11:30 a.m.', \ap_time_span($this->at(2020, 1, 5, 9), $this->at(2020, 1, 5, 11, 30)));
        $this->assertSame('1 &ndash; 4:30 p.m.', \ap_time_span($this->at(2020, 1, 5, 13), $this->at(2020, 1, 5, 16, 30)));
    }

    public function test_a_time_span_across_noon_names_both_meridians(): void
    {
        $this->assertSame('9 a.m. &ndash; 1 p.m.', \ap_time_span($this->at(2020, 1, 5, 9), $this->at(2020, 1, 5, 13)));
        $this->assertSame('Noon &ndash; 3 p.m.', \ap_time_span($this->at(2020, 1, 5, 12), $this->at(2020, 1, 5, 15)));
    }

    /**
     * @return array<string, array{0: mixed, 1: bool}>
     */
    public static function dates(): array
    {
        return [
            'a date' => ['2020-02-29', true],
            'a date with a time' => ['2020-02-29 10:30', true],
            'a leap day in a common year' => ['2021-02-29', false],
            'a month that does not exist' => ['2020-13-01', false],
            'a day that does not exist' => ['2020-02-30', false],
            'text' => ['not a date', false],
            'empty' => ['', false],
            'zero' => ['0', false],
            'null' => [null, false],
            'false' => [false, false],
        ];
    }

    #[DataProvider('dates')]
    public function test_a_value_is_a_date_only_when_it_parses_without_warnings_and_exists(mixed $value, bool $expected): void
    {
        $this->assertSame($expected, \isDate($value));
    }

    /**
     * @return array<string, array{0: list<int>, 1: int, 2: list<list<int>>}>
     */
    public static function partitions(): array
    {
        return [
            'an uneven split gives the extras to the first parts' => [[1, 2, 3, 4, 5, 6, 7], 3, [[1, 2, 3], [4, 5], [6, 7]]],
            'an even split' => [[1, 2, 3, 4], 2, [[1, 2], [3, 4]]],
            'fewer items than parts' => [[1, 2], 3, [[1], [2], []]],
            'one part holds everything' => [[1, 2, 3], 1, [[1, 2, 3]]],
            'an empty list' => [[], 2, [[], []]],
        ];
    }

    /**
     * @param  list<int>  $list
     * @param  list<list<int>>  $expected
     */
    #[DataProvider('partitions')]
    public function test_a_list_is_split_into_parts_that_differ_by_at_most_one_item(array $list, int $parts, array $expected): void
    {
        $this->assertSame($expected, \partition($list, $parts));
    }

    public function test_a_taxonomy_archive_link_is_the_rewrite_slug_under_the_root(): void
    {
        $taxonomy = new \WP_Taxonomy;
        $taxonomy->rewrite = ['slug' => 'conditions'];
        expect('get_taxonomy')->once()->with('condition')->andReturn($taxonomy);

        $this->assertSame('/conditions', \get_taxonomy_archive_link('condition'));
    }

    public function test_a_taxonomy_without_a_rewrite_slug_or_that_does_not_exist_links_to_the_root(): void
    {
        $no_slug = new \WP_Taxonomy;
        $no_slug->rewrite = ['with_front' => true];

        $no_rewrite = new \WP_Taxonomy;
        expect('get_taxonomy')->times(4)->andReturn($no_slug, $no_rewrite, false, null);

        $this->assertSame('/', \get_taxonomy_archive_link('a'));
        $this->assertSame('/', \get_taxonomy_archive_link('b'));
        $this->assertSame('/', \get_taxonomy_archive_link('c'));
        $this->assertSame('/', \get_taxonomy_archive_link(12));
    }

    /**
     * @param  list<string>  $true  Conditions that answer true, as `tag:type`.
     */
    private function conditions(array $true): void
    {
        foreach (['is_singular', 'is_tax'] as $tag) {
            when($tag)->alias(static fn (string $type): bool => in_array($tag.':'.$type, $true, true));
        }
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function taxonomy_views(): array
    {
        return [
            'a condition page' => ['is_singular:condition'],
            'a treatment page' => ['is_singular:treatment'],
            'a condition archive' => ['is_tax:condition'],
            'a treatment archive' => ['is_tax:treatment'],
        ];
    }

    #[DataProvider('taxonomy_views')]
    public function test_the_taxonomy_crumb_is_added_before_the_last_crumb(string $view): void
    {
        $this->conditions([$view]);
        when('get_queried_object')->justReturn((object) ['taxonomy' => 'condition']);
        $taxonomy = new \WP_Taxonomy;
        $taxonomy->labels = (object) ['name' => 'Conditions'];
        $taxonomy->rewrite = ['slug' => 'conditions'];
        expect('get_taxonomy')->twice()->with('condition')->andReturn($taxonomy);

        $this->assertSame(
            [['Home', '/'], ['Conditions', '/conditions'], ['Asthma', '/asthma']],
            \uamswp_fad_taxonomy_breadcrumbs_crumbs([['Home', '/'], ['Asthma', '/asthma']])
        );
    }

    public function test_other_views_keep_their_crumbs_and_never_read_a_taxonomy(): void
    {
        $crumbs = [['Home', '/'], ['Page', '/page']];

        $this->conditions([]);
        expect('get_taxonomy')->never();

        $this->assertSame($crumbs, \uamswp_fad_taxonomy_breadcrumbs_crumbs($crumbs));
    }

    public function test_an_unknown_taxonomy_keeps_the_crumbs(): void
    {
        $crumbs = [['Home', '/'], ['Page', '/page']];

        $this->conditions(['is_tax:condition']);
        when('get_queried_object')->justReturn();
        expect('get_taxonomy')->once()->with('')->andReturn(false);

        $this->assertSame($crumbs, \uamswp_fad_taxonomy_breadcrumbs_crumbs($crumbs));
    }

    private function database(): object
    {
        return new class
        {
            public string $prefix = 'wp_';

            public string $sql = '';

            public function get_col(string $sql): array
            {
                $this->sql = (string) preg_replace('/\s+/', ' ', trim($sql));

                return ['4', '9'];
            }
        };
    }

    public function test_the_custom_table_query_matches_any_of_the_values_in_post_order(): void
    {
        global $wpdb;
        $previous = $wpdb;
        $wpdb = $this->database();

        try {
            $ids = \uamswp_custom_table_query('fad_providers', 'specialty', ['heart', 7, ['x'], null]);
            $sql = $wpdb->sql;
        } finally {
            $wpdb = $previous;
        }

        $this->assertSame(['4', '9'], $ids);
        $this->assertSame(
            "SELECT `post_id` FROM `wp_fad_providers` WHERE `specialty` LIKE '%heart%' OR `specialty` LIKE '%7%' OR `specialty` LIKE '%%' OR `specialty` LIKE '%%' ORDER BY `post_id` ASC;",
            $sql
        );
    }

    public function test_one_value_adds_no_or_and_none_leaves_the_condition_empty(): void
    {
        global $wpdb;
        $previous = $wpdb;
        $wpdb = $this->database();

        try {
            \uamswp_custom_table_query('t', 'f', ['a']);
            $one = $wpdb->sql;
            \uamswp_custom_table_query('t', 'f', []);
            $none = $wpdb->sql;
        } finally {
            $wpdb = $previous;
        }

        $this->assertSame("SELECT `post_id` FROM `wp_t` WHERE `f` LIKE '%a%' ORDER BY `post_id` ASC;", $one);
        $this->assertSame('SELECT `post_id` FROM `wp_t` WHERE ORDER BY `post_id` ASC;', $none);
    }

    public function test_a_provider_name_is_escaped_but_keeps_its_non_breaking_space(): void
    {
        when('esc_html')->alias(static fn (string $text): string => htmlspecialchars($text, ENT_QUOTES));

        $this->assertSame('Dr.&nbsp;McCoy &lt;b&gt;', \uamswp_provider_name_html('Dr.&nbsp;McCoy <b>'));
        $this->assertSame('&lt;script&gt;', \uamswp_provider_name_html('<script>'));
        $this->assertSame('42', \uamswp_provider_name_html(42));
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function phrases(): array
    {
        return [
            'empty' => ['', 'a'],
            'zero' => ['0', 'a'],
            'a vowel' => ['Audiology', 'an'],
            'e' => ['Endocrinology', 'an'],
            'i' => ['Internal medicine', 'an'],
            'o' => ['Oncology', 'an'],
            'u' => ['Uterine care', 'an'],
            'lower case vowel' => ['oncology', 'an'],
            'a consonant' => ['Cardiology', 'a'],
            'an exception for a consonant start' => ['SNF unit', 'an'],
            'an exception for a vowel start' => ['Urology', 'a'],
            'an exception in lower case' => ['urology clinic', 'a'],
            'another exception' => ['Uveitis clinic', 'a'],
            'a phrase that only starts like an exception' => ['Uro', 'an'],
        ];
    }

    #[DataProvider('phrases')]
    public function test_the_indefinite_article_follows_the_first_letter_and_the_exception_list(string $phrase, string $expected): void
    {
        $this->assertSame($expected, \uamswp_indefinite_article($phrase));
    }

    public function test_the_exception_list_can_be_filtered_and_the_last_matching_key_wins(): void
    {
        expectApplied('uamswp_indefinite_article_exceptions')->times(4)->andReturn(
            ['Hon' => 'an'],
            ['Ab' => 'a', 'Abc' => 'an'],
            [],
            'not a list'
        );

        $this->assertSame('an', \uamswp_indefinite_article('Honor'));
        $this->assertSame('an', \uamswp_indefinite_article('Abcd'));
        $this->assertSame('a', \uamswp_indefinite_article('Banana'));
        $this->assertSame('an', \uamswp_indefinite_article('Apple'));
    }

    /**
     * @return array<string, array{0: mixed, 1: string, 2: int, 3: float}>
     */
    public static function readings(): array
    {
        return [
            'a string' => ['12.5', '12.5', 12, 12.5],
            'an integer' => [7, '7', 7, 7.0],
            'a float' => [2.5, '2.5', 2, 2.5],
            'true' => [true, '1', 1, 1.0],
            'false' => [false, '', 0, 0.0],
            'null' => [null, '', 0, 0.0],
            'text' => ['abc', 'abc', 0, 0.0],
            'an array' => [[1], '', 0, 0.0],
            'an object' => [new \stdClass, '', 0, 0.0],
        ];
    }

    #[DataProvider('readings')]
    public function test_a_value_is_read_as_a_string_an_integer_or_a_float(mixed $value, string $string, int $int, float $float): void
    {
        $this->assertSame($string, \uamswp_fad_str($value));
        $this->assertSame($int, \uamswp_fad_int($value));
        $this->assertSame($float, \uamswp_fad_float($value));
    }

    public function test_an_array_is_returned_as_it_is_and_anything_else_is_empty(): void
    {
        $this->assertSame(['a' => 1, 2 => 'b'], \uamswp_fad_arr(['a' => 1, 2 => 'b']));
        $this->assertSame([], \uamswp_fad_arr('text'));
        $this->assertSame([], \uamswp_fad_arr(null));
    }

    public function test_an_associative_reading_keeps_the_values_and_reads_each_key_as_a_string(): void
    {
        $this->assertSame(['a' => 1, 'b' => [2]], \uamswp_fad_assoc(['a' => 1, 'b' => [2]]));
        $this->assertSame([5 => 'x'], \uamswp_fad_assoc([5 => 'x']));
        $this->assertSame([], \uamswp_fad_assoc('text'));
    }

    public function test_ids_are_read_from_posts_terms_and_plain_values(): void
    {
        $post = new \WP_Post;
        $post->ID = 11;

        $term = new \WP_Term;
        $term->term_id = 22;

        $this->assertSame([11, 22, 33, 44, 5, 0], \uamswp_fad_ids([$post, $term, '33', 44, 5.9, 'x']));
        $this->assertSame([], \uamswp_fad_ids([]));
    }

    public function test_a_querys_posts_keep_only_post_objects_in_order(): void
    {
        $first = new \WP_Post;
        $first->ID = 1;

        $second = new \WP_Post;
        $second->ID = 2;

        $query = new \WP_Query;
        $query->posts = [3 => $first, 5, new \stdClass, 9 => $second];

        $this->assertSame([$first, $second], \uamswp_fad_query_posts($query));
    }

    public function test_the_post_type_of_a_numeric_id_is_read_and_a_non_id_is_not(): void
    {
        expect('get_post_type')->once()->with(12)->andReturn('provider');
        expect('get_post_type')->once()->with(0)->andReturn(false);

        $this->assertSame('provider', \uamswp_fad_post_type_of('12'));
        $this->assertFalse(\uamswp_fad_post_type_of(0));
        $this->assertFalse(\uamswp_fad_post_type_of('options'));
        $this->assertFalse(\uamswp_fad_post_type_of('term_5'));
        $this->assertFalse(\uamswp_fad_post_type_of(null));
    }

    public function test_a_submitted_acf_value_is_read_by_field_key(): void
    {
        $_POST['acf'] = ['field_a' => 'x', 'field_b' => null, 'field_c' => ['y']];

        $this->assertSame('x', \uamswp_fad_posted('field_a'));
        $this->assertSame(['y'], \uamswp_fad_posted('field_c'));
        $this->assertNull(\uamswp_fad_posted('field_missing'));
        $this->assertNull(\uamswp_fad_posted('field_b'));
        $this->assertTrue(\uamswp_fad_posted_has('field_b'));
        $this->assertFalse(\uamswp_fad_posted_has('field_missing'));
    }

    public function test_no_submitted_acf_values_read_as_absent(): void
    {
        unset($_POST['acf']);
        $this->assertNull(\uamswp_fad_posted('field_a'));
        $this->assertFalse(\uamswp_fad_posted_has('field_a'));

        $_POST['acf'] = 'not an array';
        $this->assertNull(\uamswp_fad_posted('field_a'));
        $this->assertFalse(\uamswp_fad_posted_has('field_a'));
    }

    public function test_setting_a_submitted_acf_value_keeps_the_others_and_starts_a_missing_set(): void
    {
        $_POST['acf'] = ['field_a' => 'x'];
        \uamswp_fad_post_set('field_b', 'y');
        $this->assertSame(['field_a' => 'x', 'field_b' => 'y'], $_POST['acf']);

        \uamswp_fad_post_set('field_a', null);
        $this->assertSame(['field_a' => null, 'field_b' => 'y'], $_POST['acf']);

        unset($_POST['acf']);
        \uamswp_fad_post_set('field_c', 'z');
        $this->assertSame(['field_c' => 'z'], $_POST['acf']);
    }
}
