<?php

/**
 * Pins the provider helpers in class.fad-helper-functions.php: the name variants, the occupation title, the
 * pronouns and the generated Provider Spotlight excerpt, introduction and questions.
 *
 * The name variants run through uamswp_attr_conversion(), which lives in class.fad-functions.php, so that
 * file is loaded through the shared loader first.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

require_once __DIR__.'/../Support/acf-functions.php';
require_once __DIR__.'/../Support/functions-file.php';
require_once __DIR__.'/../Support/wp-classes.php';

final class ProviderHelpersTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // See AcfBlocksTest: the ACF functions file loads first, under Brain Monkey's own hook functions.
        \uamswp_test_acf_functions();
        \uamswp_test_functions_file();

        Functions\when('esc_html')->alias(static fn (string $text): string => htmlspecialchars($text, ENT_QUOTES));
        Functions\when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
        Functions\when('sanitize_title_with_dashes')->alias(static fn (string $title): string => trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($title)), '-'));
    }

    /**
     * Stub the fields of provider 5, the clinical titles and the degree terms.
     *
     * @param  array<string, mixed>  $fields  Field values for provider 5, by field name.
     * @param  array<int, object|null>  $terms  Terms by id, for both the degree and clinical title taxonomies.
     * @param  array<string, mixed>  $termFields  Field values read from a term, by field name.
     */
    private function provider(array $fields, array $terms = [], array $termFields = []): void
    {
        Functions\when('get_field')->alias(static fn (string $field, mixed $id = null): mixed => is_object($id) ? ($termFields[$field] ?? '') : ($fields[$field] ?? ''));
        Functions\when('get_term')->alias(static fn (int $id, string $taxonomy): ?object => $terms[$id] ?? null);
    }

    /**
     * @return array<string, mixed>
     */
    private function mccoy(): array
    {
        return [
            'physician_first_name' => 'Leonard',
            'physician_middle_name' => 'H.',
            'physician_last_name' => 'McCoy',
            'physician_pedigree' => '',
            'physician_degree' => [3, 4],
        ];
    }

    /**
     * @return array<int, object>
     */
    private function degreeTerms(): array
    {
        return [3 => (object) ['name' => 'M.D.'], 4 => (object) ['name' => 'Ph.D.'], 5 => (object) ['name' => 'D.O.'], 6 => (object) ['name' => 'M.D.']];
    }

    // The name variants.

    public function test_no_provider_gives_every_variant_empty(): void
    {
        $this->assertSame(
            array_fill_keys(
                ['full', 'full_attr', 'prefix', 'medium', 'medium_attr', 'medium_no_prefix', 'medium_no_prefix_attr', 'short', 'short_attr', 'short_possessive', 'short_possessive_attr', 'sort', 'sort_param'],
                ''
            ),
            \uamswp_provider_names(0)
        );
    }

    public function test_a_physician_gets_the_doctor_prefix_and_each_variant(): void
    {
        $this->provider($this->mccoy(), $this->degreeTerms());

        $this->assertSame([
            'full' => 'Leonard H. McCoy, M.D., Ph.D.',
            'full_attr' => 'Leonard H. McCoy, M.D., Ph.D.',
            'prefix' => 'Dr.',
            'medium' => 'Dr. Leonard H. McCoy',
            'medium_attr' => 'Dr. Leonard H. McCoy',
            'medium_no_prefix' => 'Leonard H. McCoy',
            'medium_no_prefix_attr' => 'Leonard H. McCoy',
            'short' => 'Dr.&nbsp;McCoy',
            'short_attr' => 'Dr. McCoy',
            'short_possessive' => "Dr.&nbsp;McCoy's",
            'short_possessive_attr' => "Dr. McCoy's",
            'sort' => 'McCoy, Leonard H.',
            'sort_param' => 'mccoy-leonard-h',
        ], \uamswp_provider_names(5));
    }

    public function test_a_doctor_of_osteopathy_also_gets_the_prefix(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee', 'physician_degree' => [5]], $this->degreeTerms());

        $this->assertSame('Dr.', \uamswp_provider_names(5)['prefix']);
    }

    public function test_other_degrees_get_no_prefix_and_the_short_name_is_the_full_plain_name(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee', 'physician_degree' => [4]], $this->degreeTerms());

        $names = \uamswp_provider_names(5);

        $this->assertSame(
            ['full' => 'Ann Lee, Ph.D.', 'prefix' => '', 'medium' => 'Ann Lee', 'short' => 'Ann Lee', 'short_possessive' => "Ann Lee's"],
            array_intersect_key($names, array_flip(['prefix', 'medium', 'short', 'short_possessive', 'full']))
        );
    }

    public function test_a_generational_suffix_follows_the_surname_with_a_non_breaking_space(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_middle_name' => 'B.', 'physician_last_name' => 'Lee', 'physician_pedigree' => 'Jr.']);

        $names = \uamswp_provider_names(5);

        $this->assertSame('Ann B. Lee&nbsp;Jr.', $names['full']);
        $this->assertSame('Ann B. Lee&nbsp;Jr.', $names['short']);
        $this->assertSame('Ann B. Lee Jr.', $names['short_attr']);
        $this->assertSame('Ann B. Lee', $names['medium'], 'the suffix is not part of the medium name');
    }

    public function test_a_prefixed_short_name_leaves_out_the_suffix(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee', 'physician_pedigree' => 'Jr.', 'physician_degree' => [3]], $this->degreeTerms());

        $this->assertSame('Dr.&nbsp;Lee', \uamswp_provider_names(5)['short']);
    }

    public function test_a_surname_ending_in_s_takes_an_apostrophe_without_another_s(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Jones', 'physician_degree' => [3]], $this->degreeTerms());

        $names = \uamswp_provider_names(5);

        $this->assertSame("Dr.&nbsp;Jones'", $names['short_possessive']);
        $this->assertSame("Dr. Jones'", $names['short_possessive_attr']);
    }

    public function test_quotation_marks_in_a_name_become_single_quotes_in_the_attribute_variants(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'O"Neil']);

        $names = \uamswp_provider_names(5);

        $this->assertSame('Ann O"Neil', $names['medium']);
        $this->assertSame("Ann O'Neil", $names['medium_attr']);
        $this->assertSame("Ann O'Neil", $names['full_attr']);
    }

    public function test_degrees_are_cleaned_and_a_missing_term_is_skipped(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee', 'physician_degree' => [3, 3, 0, '', 9]], $this->degreeTerms());

        $this->assertSame('Ann Lee, M.D., ', \uamswp_provider_names(5)['full'], 'the unknown degree 9 leaves its separator behind');
    }

    public function test_two_degree_terms_with_the_same_name_list_twice_but_count_once_for_the_prefix(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee', 'physician_degree' => [3, 6]], $this->degreeTerms());

        $names = \uamswp_provider_names(5);

        $this->assertSame('Ann Lee, M.D., M.D.', $names['full']);
        $this->assertSame('Dr.', $names['prefix']);
    }

    public function test_a_degree_value_that_is_not_a_list_adds_no_degrees(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee', 'physician_degree' => '3'], $this->degreeTerms());

        $names = \uamswp_provider_names(5);

        $this->assertSame('Ann Lee', $names['full']);
        $this->assertSame('', $names['prefix']);
    }

    public function test_a_provider_with_no_name_has_blank_variants(): void
    {
        $this->provider([]);

        $names = \uamswp_provider_names(5);

        $this->assertSame([' ', '', '', ',  ', ''], [$names['medium'], $names['medium_no_prefix'], $names['medium_no_prefix_attr'], $names['sort'], $names['sort_param']]);
    }

    // The occupation title.

    public function test_no_provider_has_no_occupation_title(): void
    {
        Functions\expect('get_field')->never();

        $this->assertSame('', \uamswp_provider_occupation_title(0));
    }

    public function test_a_resident_gets_the_fixed_title(): void
    {
        $this->provider(['physician_resident' => true, 'physician_title' => 7], [7 => (object) ['name' => 'Cardiology']]);

        $this->assertSame('Resident Physician', \uamswp_provider_occupation_title(5));
    }

    public function test_the_specialization_title_on_the_term_wins_over_its_name(): void
    {
        $this->provider(['physician_title' => '7'], [7 => (object) ['name' => 'Cardiology']], ['clinical_specialization_title' => 'Interventional Cardiologist']);

        $this->assertSame('Interventional Cardiologist', \uamswp_provider_occupation_title(5));
    }

    public function test_the_term_name_is_the_fallback_title(): void
    {
        $this->provider(['physician_title' => 7], [7 => (object) ['name' => 'Cardiology']]);

        $this->assertSame('Cardiology', \uamswp_provider_occupation_title(5));
    }

    /**
     * @return array<string, array{0: array<string, mixed>, 1: array<int, object|null>}>
     */
    public static function unresolved_titles(): array
    {
        return [
            'no specialty' => [[], [7 => (object) ['name' => 'Cardiology']]],
            'a specialty with no term' => [['physician_title' => 8], [7 => (object) ['name' => 'Cardiology']]],
            'a term with no name' => [['physician_title' => 7], [7 => new \stdClass]],
        ];
    }

    /**
     * @param  array<string, mixed>  $fields
     * @param  array<int, object|null>  $terms
     */
    #[DataProvider('unresolved_titles')]
    public function test_a_title_that_cannot_be_resolved_is_empty(array $fields, array $terms): void
    {
        $this->provider($fields, $terms);

        $this->assertSame('', \uamswp_provider_occupation_title(5));
    }

    // The pronouns.

    /**
     * @return array<string, array{0: mixed, 1: array<string, mixed>}>
     */
    public static function pronouns(): array
    {
        $they = ['subject' => 'they', 'object' => 'them', 'possessive' => 'their', 'possessive_pronoun' => 'theirs', 'reflexive' => 'themselves', 'plural' => true];

        return [
            'female' => ['female', ['subject' => 'she', 'object' => 'her', 'possessive' => 'her', 'possessive_pronoun' => 'hers', 'reflexive' => 'herself', 'plural' => false]],
            'male' => ['male', ['subject' => 'he', 'object' => 'him', 'possessive' => 'his', 'possessive_pronoun' => 'his', 'reflexive' => 'himself', 'plural' => false]],
            'other' => ['other', $they],
            'empty' => ['', $they],
            'unexpected' => ['unknown', $they],
            'not set' => [null, $they],
        ];
    }

    /**
     * @param  array<string, mixed>  $expected
     */
    #[DataProvider('pronouns')]
    public function test_the_pronouns_follow_the_recorded_gender(mixed $gender, array $expected): void
    {
        $this->provider(['physician_gender' => $gender]);

        $this->assertSame($expected, \uamswp_provider_pronouns(5));
    }

    public function test_no_provider_gets_the_singular_they_without_reading_a_field(): void
    {
        Functions\expect('get_field')->never();

        $this->assertSame('they', \uamswp_provider_pronouns(0)['subject']);
    }

    // The spotlight excerpt.

    public function test_the_excerpt_names_the_provider_and_the_lowercased_title(): void
    {
        $this->provider($this->mccoy() + ['physician_title' => 7], $this->degreeTerms() + [7 => (object) ['name' => 'Optometrist']]);

        $this->assertSame(
            'Meet Dr. Leonard H. McCoy, optometrist at UAMS Health, in this provider spotlight Q&A.',
            \uamswp_spotlight_default_excerpt(5)
        );
    }

    public function test_the_excerpt_without_a_title_leaves_it_out(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee']);

        $this->assertSame('Meet Ann Lee of UAMS Health in this provider spotlight Q&A.', \uamswp_spotlight_default_excerpt(5));
    }

    public function test_the_excerpt_uses_the_attribute_safe_name(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'O"Neil']);

        $this->assertSame("Meet Ann O'Neil of UAMS Health in this provider spotlight Q&A.", \uamswp_spotlight_default_excerpt(5));
    }

    public function test_the_excerpt_is_empty_for_no_provider_or_a_provider_with_no_name(): void
    {
        $this->provider([]);

        $this->assertSame('', \uamswp_spotlight_default_excerpt(0));
        $this->assertSame('', \uamswp_spotlight_default_excerpt(5));
    }

    public function test_the_excerpt_filter_receives_the_parts_and_its_markup_is_stripped(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee', 'physician_title' => 7], [7 => (object) ['name' => 'Optometrist']]);
        Filters\expectApplied('uamswp_spotlight_default_excerpt')
            ->once()
            ->with('Meet Ann Lee, optometrist at UAMS Health, in this provider spotlight Q&A.', 5, Mockery::type('array'), 'optometrist')
            ->andReturn('<b>Custom</b> excerpt');

        $this->assertSame('Custom excerpt', \uamswp_spotlight_default_excerpt(5));
    }

    public function test_an_excerpt_over_160_characters_is_cut_to_156_ending_in_an_ellipsis(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee']);
        Filters\expectApplied('uamswp_spotlight_default_excerpt')->andReturn(str_repeat('a', 161));

        $this->assertSame(str_repeat('a', 153).'...', \uamswp_spotlight_default_excerpt(5));
    }

    public function test_an_excerpt_of_exactly_160_characters_is_kept(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee']);
        Filters\expectApplied('uamswp_spotlight_default_excerpt')->andReturn(str_repeat('a', 160));

        $this->assertSame(str_repeat('a', 160), \uamswp_spotlight_default_excerpt(5));
    }

    // The spotlight introduction.

    public function test_the_introduction_opens_with_the_name_and_an_article_for_the_title(): void
    {
        $this->provider($this->mccoy() + ['physician_title' => 7, 'physician_gender' => 'male'], $this->degreeTerms() + [7 => (object) ['name' => 'Optometrist']]);

        $this->assertSame(
            '<p>Get to know Dr. Leonard H. McCoy, an optometrist at UAMS Health. In the Q&amp;A below, Dr.&nbsp;McCoy shares what inspired his work in healthcare, his life outside of medicine, and more.</p>',
            \uamswp_spotlight_default_intro(5)
        );
    }

    public function test_the_introduction_without_a_title_and_with_other_pronouns(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee', 'physician_gender' => 'other']);

        $this->assertSame(
            '<p>Get to know Ann Lee of UAMS Health. In the Q&amp;A below, Ann Lee shares what inspired their work in healthcare, their life outside of medicine, and more.</p>',
            \uamswp_spotlight_default_intro(5)
        );
    }

    public function test_the_introduction_escapes_the_name_and_the_title(): void
    {
        $this->provider(
            ['physician_first_name' => 'Ann', 'physician_last_name' => '<i>Lee</i>', 'physician_title' => 7, 'physician_gender' => 'female'],
            [7 => (object) ['name' => 'Ear & Eye']]
        );

        $html = \uamswp_spotlight_default_intro(5);

        $this->assertStringContainsString('Get to know Ann &lt;i&gt;Lee&lt;/i&gt;, an ear &amp; eye at UAMS Health.', $html);
        $this->assertStringContainsString('inspired her work', $html);
    }

    public function test_the_introduction_is_empty_for_no_provider_or_a_provider_with_no_name(): void
    {
        $this->provider([]);

        $this->assertSame('', \uamswp_spotlight_default_intro(0));
        $this->assertSame('', \uamswp_spotlight_default_intro(5));
    }

    public function test_the_introduction_filter_receives_the_parts_and_its_result_is_used(): void
    {
        $this->provider(['physician_first_name' => 'Ann', 'physician_last_name' => 'Lee', 'physician_title' => 7, 'physician_gender' => 'female'], [7 => (object) ['name' => 'Optometrist']]);
        Filters\expectApplied('uamswp_spotlight_intro')
            ->once()
            ->with(Mockery::type('string'), 5, Mockery::type('array'), 'optometrist', Mockery::on(static fn (mixed $pronouns): bool => is_array($pronouns) && $pronouns['subject'] === 'she'))
            ->andReturn('<p>Custom</p>');

        $this->assertSame('<p>Custom</p>', \uamswp_spotlight_default_intro(5));
    }

    // The default questions.

    public function test_the_default_questions_are_the_six_standard_ones(): void
    {
        $this->assertSame([
            'What inspired you to work in healthcare?',
            'What is the best part of your job?',
            'What do you like about working at UAMS?',
            'Who has had the biggest influence on your life, and why?',
            'What book, podcast, TV show, or activity are you enjoying right now, and why?',
            'What is one thing on your bucket list?',
        ], \uamswp_spotlight_default_questions());
    }

    public function test_the_questions_can_be_filtered_and_a_non_list_result_is_empty(): void
    {
        Filters\expectApplied('uamswp_spotlight_default_questions')->once()->andReturn(['Only one?']);
        $this->assertSame(['Only one?'], \uamswp_spotlight_default_questions());

        Filters\expectApplied('uamswp_spotlight_default_questions')->once()->andReturn('not a list');
        $this->assertSame([], \uamswp_spotlight_default_questions());
    }
}
