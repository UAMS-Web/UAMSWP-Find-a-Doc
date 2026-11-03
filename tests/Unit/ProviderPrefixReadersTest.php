<?php

/**
 * How the "Dr." prefix is built in the Business Profile provider table and on the clinical resource card.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use UamswpFindADoc\Tests\Support\UnitTestCase;

/**
 * Two readers build a provider's medium name from the stored `physician_prefix` field, while the provider
 * page derives the prefix from the degrees (`uamswp_provider_names()`: "Dr." only for M.D. or D.O.). The
 * two rules disagree for some providers. These tests record each reader's output as it is, so a change to
 * the degree rule shows up as a changed result, and keep the helper's result beside them as the control.
 *
 * Each reader builds its name inline, inside a function or template that needs a full WordPress to run, so
 * each test cuts the reader's own lines out of the source and evaluates them with `get_field()` mocked. The
 * lines read only the variables the test sets and the mocked fields; nothing else is read, and no field
 * value is treated as code. The Google My Business provider listing template that once held a third copy
 * was retired with the provider CSV export, so two readers remain.
 */
final class ProviderPrefixReadersTest extends UnitTestCase
{
    /** @var array<int, array<string, mixed>> */
    private array $fields = [];

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        require_once dirname(__DIR__, 2).'/includes/class.fad-helper-functions.php';
    }

    protected function setUp(): void
    {
        parent::setUp();

        // A provider whose stored prefix is "Dr." but whose degree is not M.D. or D.O.
        $this->fields[1] = ['physician_prefix' => 'Dr.', 'physician_first_name' => 'Ann', 'physician_middle_name' => '', 'physician_last_name' => 'Lee', 'physician_pedigree' => '', 'physician_degree' => [11]];
        // A provider with an M.D. and no stored prefix.
        $this->fields[2] = ['physician_prefix' => '', 'physician_first_name' => 'Bob', 'physician_middle_name' => '', 'physician_last_name' => 'Ray', 'physician_pedigree' => '', 'physician_degree' => [12]];

        Functions\when('get_field')->alias(fn (string $name, $id = 0) => $this->fields[(int) $id][$name] ?? false);
        Functions\when('get_term')->alias(fn ($id) => (object) ['name' => [11 => 'D.D.S.', 12 => 'M.D.', 13 => 'D.O.'][(int) $id] ?? '']);
        Functions\when('uamswp_attr_conversion')->returnArg();
        Functions\when('sanitize_title_with_dashes')->returnArg();
    }

    private function source(string $path): string
    {
        return (string) file_get_contents(dirname(__DIR__, 2).'/'.$path);
    }

    /**
     * The reader's own lines: from the start marker through the end of the line holding the end marker.
     */
    private function lines(string $source, string $from, string $through): string
    {
        $start = strpos($source, $from);
        $this->assertNotFalse($start, "the source holds: {$from}");

        $mark = strpos($source, $through, $start);
        $this->assertNotFalse($mark, "the source holds: {$through}");

        $end = strpos($source, "\n", $mark);

        return substr($source, $start, ($end === false ? strlen($source) : $end) - $start);
    }

    /**
     * Run the lines with the given variables and return the named variable.
     *
     * @param  array<string, mixed>  $variables
     */
    private function evaluate(string $code, array $variables, string $result): string
    {
        $run = static function (array $variables, string $code, string $result): string {
            extract($variables, EXTR_SKIP);
            eval($code);

            return (string) $$result;
        };

        return $run($variables, $code, $result);
    }

    private function provider_table_name(int $post_id): string
    {
        $code = $this->lines($this->source('includes/class.fad-gmb-settings-page.php'), '$prefix = uamswp_provider_names($post_id)[\'prefix\'];', '$short_name = $prefix ?');

        return $this->evaluate($code, ['post_id' => $post_id, 'degree_list' => ''], 'medium_name');
    }

    private function resource_card_name(int $post_id): string
    {
        $code = $this->lines($this->source('templates/loops/resource-card.php'), '$resource_provider_prefix = uamswp_provider_names($associate)[\'prefix\'];', '$resource_provider_medium_name =');

        return $this->evaluate($code, ['associate' => $post_id], 'resource_provider_medium_name');
    }

    public function test_the_provider_table_follows_the_degrees_whatever_the_stored_prefix_is(): void
    {
        $this->assertSame('Ann Lee', $this->provider_table_name(1), 'stored "Dr." with a D.D.S.');
        $this->assertSame('Dr. Bob Ray', $this->provider_table_name(2), 'an M.D. with no stored prefix');
    }

    public function test_the_resource_card_follows_the_degrees_whatever_the_stored_prefix_is(): void
    {
        $this->assertSame('Ann Lee', $this->resource_card_name(1), 'stored "Dr." with a D.D.S.');
        $this->assertSame('Dr. Bob Ray', $this->resource_card_name(2), 'an M.D. with no stored prefix');
    }

    public function test_an_m_d_or_d_o_among_other_degrees_gets_the_prefix_and_no_degrees_gets_none_in_both_readers(): void
    {
        $this->fields[3] = ['physician_prefix' => 'Mr.', 'physician_first_name' => 'Cy', 'physician_middle_name' => '', 'physician_last_name' => 'Fox', 'physician_pedigree' => '', 'physician_degree' => [11, 13]];
        $this->fields[4] = ['physician_prefix' => 'Dr.', 'physician_first_name' => 'Di', 'physician_middle_name' => '', 'physician_last_name' => 'Hale', 'physician_pedigree' => '', 'physician_degree' => []];

        $this->assertSame('Dr. Cy Fox', $this->provider_table_name(3), 'D.D.S. and D.O.');
        $this->assertSame('Dr. Cy Fox', $this->resource_card_name(3));
        $this->assertSame('Di Hale', $this->provider_table_name(4), 'no degrees');
        $this->assertSame('Di Hale', $this->resource_card_name(4));
        $this->assertSame($this->resource_card_name(3), uamswp_provider_names(3)['medium'], 'the card matches the provider page');
    }

    public function test_the_provider_page_derives_the_prefix_from_the_degrees(): void
    {
        $this->assertSame('Ann Lee', uamswp_provider_names(1)['medium'], 'a D.D.S. gets no "Dr."');
        $this->assertSame('Dr. Bob Ray', uamswp_provider_names(2)['medium'], 'an M.D. gets "Dr."');
    }
}
