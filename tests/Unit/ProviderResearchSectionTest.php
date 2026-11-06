<?php

/**
 * When the provider page shows its Research section, its jump link and its anchor.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

/**
 * The decision is a block inside the provider template, which needs a full WordPress and theme to
 * render, so these tests cut that block out of the template and run it against each input on its own.
 * The block reads only plain variables, so running it executes the template's own condition, not a copy.
 * The "Research Interests only" case records today's behavior: the condition reads a misspelled variable,
 * so that field alone shows no section.
 */
final class ProviderResearchSectionTest extends UnitTestCase
{
    private function template(): string
    {
        return (string) file_get_contents(dirname(__DIR__, 2).'/templates/single-physicians.php');
    }

    /**
     * The template's own "Check if Research section should be displayed" block, from its comment to its closing brace.
     */
    private function decision_block(): string
    {
        $source = $this->template();
        $start = strpos($source, '// Check if Research section should be displayed');
        $this->assertNotFalse($start, 'the Research decision block exists');

        $end = strpos($source, '// Check if Conditions section should be displayed', $start);
        $this->assertNotFalse($end);

        return substr($source, $start, $end - $start);
    }

    /**
     * Run the template's block with the given variables set and nothing else.
     *
     * @param  array<string, mixed>  $inputs
     * @return array{shown: bool, jump_links: int}
     */
    private function decide(array $inputs): array
    {
        $block = $this->decision_block();

        // The page has these five set before the block runs (empty when the field is empty). The misspelled
        // `$esearch_interests` the condition reads is never set anywhere, so it is not set here either.
        $inputs += ['research_bio' => '', 'research_interests' => '', 'publications' => [], 'pubmed_author_id' => '', 'research_profiles_link' => ''];

        // The block is the template's own text and reads only the variables passed in, so evaluating it
        // runs the real condition. No file other than the template is read, and no input is a string of code.
        $run = static function (array $inputs, string $block): array {
            extract($inputs, EXTR_SKIP);
            $jump_link_count = 0;
            eval($block);

            return ['shown' => $show_research_section, 'jump_links' => $jump_link_count];
        };

        return $run($inputs, $block);
    }

    /**
     * @return array<string, array{array<string, mixed>}>
     */
    public static function fieldsThatShowTheSection(): array
    {
        return [
            'Research Bio' => [['research_bio' => 'Studies the heart.']],
            'Publications' => [['publications' => ['One paper']]],
            'PubMed Author ID' => [['pubmed_author_id' => '12345']],
            'Research Profiles link' => [['research_profiles_link' => 'https://profiles.uams.edu/x']],
        ];
    }

    /**
     * @param  array<string, mixed>  $inputs
     */
    #[DataProvider('fieldsThatShowTheSection')]
    public function test_each_of_these_fields_alone_shows_the_section_and_counts_one_jump_link(array $inputs): void
    {
        $this->assertSame(['shown' => true, 'jump_links' => 1], $this->decide($inputs));
    }

    public function test_nothing_filled_in_shows_no_section_and_no_jump_link(): void
    {
        $this->assertSame(['shown' => false, 'jump_links' => 0], $this->decide([]));
        $this->assertSame(['shown' => false, 'jump_links' => 0], $this->decide(['research_bio' => '', 'research_interests' => '', 'publications' => [], 'pubmed_author_id' => '', 'research_profiles_link' => '']));
    }

    public function test_research_interests_alone_shows_no_section_today_because_the_condition_reads_a_misspelled_variable(): void
    {
        $this->assertSame(['shown' => false, 'jump_links' => 0], $this->decide(['research_interests' => 'Heart failure']));
        $this->assertStringContainsString('$esearch_interests', $this->decision_block());
    }

    public function test_the_jump_link_and_the_anchor_both_follow_the_decision(): void
    {
        $source = $this->template();

        $this->assertMatchesRegularExpression('/<\?php if \(\$show_research_section\) \{ \?>\s*<li class="nav-item">\s*<a class="nav-link" href="#research-info">Research<\/a>/', $source);
        $this->assertMatchesRegularExpression('/if\( \$show_research_section \): \?>\s*<section class="uams-module research-info bg-auto" id="research-info">/', $source);
    }
}
