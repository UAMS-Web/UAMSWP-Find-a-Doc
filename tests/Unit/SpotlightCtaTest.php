<?php

/**
 * Pins the Provider Spotlight call-to-action band as it renders today, before #903 rewrites the "Learn More" line.
 *
 * The function runs for real, with the name and occupation helpers it calls. Only the WordPress and theme
 * functions it needs are stubbed.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class SpotlightCtaTest extends UnitTestCase
{
    private const URL = 'https://example.test/provider/jane-doe/';

    /** @var array<string, mixed> ACF field values by name, for get_field() */
    private array $fields = [];

    /** @var array<int, string> Names of the clinical_title terms by id */
    private array $titles = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->fields = [
            'physician_first_name' => 'Jane',
            'physician_last_name' => 'Doe',
            'physician_degree' => [3],
            'physician_resident' => '0',
            'physician_eligible_appointments' => '1',
            'physician_accepting_patients' => '1',
            'physician_referral_required' => '0',
            'physician_title' => '',
            'clinical_resource_spotlight_phone' => '5016868000',
        ];
        $this->titles = [7 => 'Cardiologist'];

        Functions\when('get_field')->alias(function ($name, $id = null) {
            if ($name === 'clinical_specialization_title') {
                return is_object($id) ? ($id->override ?? null) : null;
            }

            return $this->fields[$name] ?? null;
        });
        Functions\when('get_term')->alias(function ($id, $taxonomy) {
            if ($taxonomy === 'degree') {
                return (object) ['name' => 'M.D.'];
            }

            return isset($this->titles[$id]) ? (object) ['name' => $this->titles[$id], 'override' => $this->fields['title_override'] ?? null] : null;
        });
        Functions\when('get_permalink')->justReturn(self::URL);
        Functions\when('esc_url')->returnArg();
        Functions\when('esc_attr')->alias(fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES));
        Functions\when('esc_html')->alias(fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES));
        Functions\when('uamswp_attr_conversion')->returnArg();
        Functions\when('sanitize_title_with_dashes')->alias(fn ($v) => strtolower(str_replace(' ', '-', (string) $v)));
        Functions\when('format_phone_dash')->alias(fn ($p) => preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '$1-$2-$3', (string) $p));

        require_once dirname(__DIR__, 2).'/includes/class.fad-helper-functions.php';
    }

    private function name_link(): string
    {
        return '<a href="'.self::URL.'" data-itemtitle="Learn more about Dr. Jane Doe">Dr. Jane Doe</a>';
    }

    private function profile_link(): string
    {
        return '<a href="'.self::URL.'" data-itemtitle="Learn more about Dr. Jane Doe">visit Dr.&nbsp;Doe&#039;s provider profile</a>';
    }

    private function tel_link(): string
    {
        return '<a href="tel:501-686-8000" class="no-break" data-itemtitle="Call to schedule with Dr. Jane Doe">501-686-8000</a>';
    }

    /**
     * @return array{0: string, 1: string} The heading and the paragraph of the rendered band
     */
    private function render(int $provider_id = 5): array
    {
        $html = \uamswp_spotlight_appointment_cta($provider_id);

        $this->assertSame(1, preg_match('#<h2 id="spotlight-cta-title">(.*?)</h2>\s*<p>(.*?)</p>#s', $html, $m), 'the band has a heading and a paragraph');

        return [$m[1], $m[2]];
    }

    public function test_a_bookable_provider_with_a_phone_gets_the_call_to_schedule_line(): void
    {
        [$heading, $body] = $this->render();

        $this->assertSame('Make an Appointment', $heading);
        $this->assertSame(
            'Ready to schedule an appointment with '.$this->name_link().'? Call&nbsp;'.$this->tel_link().' to request an appointment, or '.$this->profile_link().' to learn more.',
            $body
        );
    }

    public function test_a_referral_note_leads_the_scheduling_line(): void
    {
        $this->fields['physician_referral_required'] = '1';

        [$heading, $body] = $this->render();

        $this->assertSame('Make an Appointment', $heading);
        $this->assertSame(
            'Appointments for new patients are by referral only. Ready to schedule an appointment with '.$this->name_link().'? Call&nbsp;'.$this->tel_link().' to request an appointment, or '.$this->profile_link().' to learn more.',
            $body
        );
    }

    public function test_a_bookable_provider_with_no_phone_gets_the_profile_line(): void
    {
        $this->fields['clinical_resource_spotlight_phone'] = '';

        [$heading, $body] = $this->render();

        $this->assertSame('Make an Appointment', $heading);
        $this->assertSame('To schedule an appointment with '.$this->name_link().', '.$this->profile_link().'.', $body);
    }

    public function test_a_phone_that_cannot_be_formatted_is_treated_as_no_phone(): void
    {
        Functions\when('format_phone_dash')->justReturn('');

        [, $body] = $this->render();

        $this->assertSame('To schedule an appointment with '.$this->name_link().', '.$this->profile_link().'.', $body);
    }

    public function test_a_referral_note_leads_the_profile_line_too(): void
    {
        $this->fields['physician_referral_required'] = '1';
        $this->fields['clinical_resource_spotlight_phone'] = '';

        [, $body] = $this->render();

        $this->assertSame('Appointments for new patients are by referral only. To schedule an appointment with '.$this->name_link().', '.$this->profile_link().'.', $body);
    }

    /**
     * @return array<string, array{0: array<string, mixed>}>
     */
    public static function not_bookable_cases(): array
    {
        return [
            'not eligible for appointments' => [['physician_eligible_appointments' => '0']],
            'not accepting new patients' => [['physician_accepting_patients' => '0']],
            'neither' => [['physician_eligible_appointments' => '0', 'physician_accepting_patients' => '0']],
            'eligible field never saved' => [['physician_eligible_appointments' => '']],
            'accepting field never saved' => [['physician_accepting_patients' => null]],
            'a resident who is otherwise bookable' => [['physician_resident' => '1']],
        ];
    }

    /**
     * @param  array<string, mixed>  $changes
     */
    #[DataProvider('not_bookable_cases')]
    public function test_a_provider_who_cannot_be_scheduled_gets_the_learn_more_band(array $changes): void
    {
        $this->fields = $changes + $this->fields;

        [$heading, $body] = $this->render();

        $this->assertSame('Learn More', $heading);
        $this->assertStringStartsWith('Get to know Dr. Jane Doe', $body);
        $this->assertStringNotContainsString('schedule', $body);
        $this->assertStringNotContainsString('tel:', $body);
    }

    public function test_the_learn_more_line_with_an_occupation_title(): void
    {
        $this->fields['physician_eligible_appointments'] = '0';
        $this->fields['physician_title'] = 7;

        [$heading, $body] = $this->render();

        $this->assertSame('Learn More', $heading);
        $this->assertSame('Get to know Dr. Jane Doe, a Cardiologist at UAMS Health, and '.$this->profile_link().' to learn more.', $body);
    }

    public function test_the_learn_more_line_uses_an_before_a_vowel_title(): void
    {
        $this->fields['physician_eligible_appointments'] = '0';
        $this->fields['physician_title'] = 7;
        $this->titles[7] = 'Anesthesiologist';

        [, $body] = $this->render();

        $this->assertSame('Get to know Dr. Jane Doe, an Anesthesiologist at UAMS Health, and '.$this->profile_link().' to learn more.', $body);
    }

    public function test_the_learn_more_line_follows_the_article_exception_list(): void
    {
        $this->fields['physician_eligible_appointments'] = '0';
        $this->fields['physician_title'] = 7;
        $this->titles[7] = 'Urologist';

        [, $body] = $this->render();

        $this->assertSame('Get to know Dr. Jane Doe, a Urologist at UAMS Health, and '.$this->profile_link().' to learn more.', $body);
    }

    public function test_the_term_override_title_wins_over_the_term_name(): void
    {
        $this->fields['physician_eligible_appointments'] = '0';
        $this->fields['physician_title'] = 7;
        $this->fields['title_override'] = 'Heart Specialist';

        [, $body] = $this->render();

        $this->assertSame('Get to know Dr. Jane Doe, a Heart Specialist at UAMS Health, and '.$this->profile_link().' to learn more.', $body);
    }

    public function test_the_learn_more_line_escapes_the_occupation_title(): void
    {
        $this->fields['physician_eligible_appointments'] = '0';
        $this->fields['physician_title'] = 7;
        $this->titles[7] = 'Ear, Nose & Throat Specialist';

        [, $body] = $this->render();

        $this->assertStringContainsString('an Ear, Nose &amp; Throat Specialist at UAMS Health', $body);
    }

    public function test_the_learn_more_line_with_no_occupation_title(): void
    {
        $this->fields['physician_eligible_appointments'] = '0';

        [$heading, $body] = $this->render();

        $this->assertSame('Learn More', $heading);
        $this->assertSame('Get to know Dr. Jane Doe and '.$this->profile_link().' to learn more.', $body);
    }

    public function test_a_resident_gets_the_fixed_resident_title(): void
    {
        $this->fields['physician_resident'] = '1';
        $this->fields['physician_title'] = 7;

        [$heading, $body] = $this->render();

        $this->assertSame('Learn More', $heading);
        $this->assertSame('Get to know Dr. Jane Doe, a Resident Physician at UAMS Health, and '.$this->profile_link().' to learn more.', $body);
    }

    public function test_the_provider_name_is_escaped_in_the_line_and_in_the_link_title(): void
    {
        $this->fields['physician_first_name'] = 'Jo & Co';
        $this->fields['physician_eligible_appointments'] = '0';

        [, $body] = $this->render();

        $this->assertStringStartsWith('Get to know Dr. Jo &amp; Co Doe and ', $body);
        $this->assertStringContainsString('data-itemtitle="Learn more about Dr. Jo &amp; Co Doe">visit ', $body);
    }

    public function test_the_band_markup(): void
    {
        $html = \uamswp_spotlight_appointment_cta(5);

        $this->assertStringContainsString('<section class="uams-module cta-bar cta-bar-1 bg-auto extra-padding cta-bar-lg" id="appointment-info" aria-labelledby="spotlight-cta-title">', $html);
        $this->assertStringContainsString('<div class="container-fluid">', $html);
        $this->assertStringContainsString('<div class="col-xs-12">', $html);
        $this->assertStringContainsString('<h2 id="spotlight-cta-title">', $html);
        $this->assertStringEndsWith('</section>', $html);
    }

    public function test_the_filter_receives_the_band_the_id_the_phone_and_the_names(): void
    {
        Filters\expectApplied('uamswp_spotlight_cta')->once()->with(
            \Mockery::on(fn ($cta) => is_string($cta) && strpos($cta, 'Make an Appointment') !== false),
            5,
            '5016868000',
            \Mockery::on(fn ($names) => is_array($names) && $names['medium'] === 'Dr. Jane Doe' && $names['short_possessive'] === 'Dr.&nbsp;Doe\'s')
        );

        \uamswp_spotlight_appointment_cta(5);
    }

    public function test_the_filter_gets_no_phone_when_the_provider_cannot_be_scheduled(): void
    {
        $this->fields['physician_accepting_patients'] = '0';

        Filters\expectApplied('uamswp_spotlight_cta')->once()->with(
            \Mockery::on(fn ($cta) => is_string($cta) && strpos($cta, 'Learn More') !== false),
            5,
            '',
            \Mockery::type('array')
        );

        \uamswp_spotlight_appointment_cta(5);
    }

    public function test_the_filter_can_replace_the_band(): void
    {
        Filters\expectApplied('uamswp_spotlight_cta')->once()->andReturn('<section>replaced</section>');

        $this->assertSame('<section>replaced</section>', \uamswp_spotlight_appointment_cta(5));
    }
}
