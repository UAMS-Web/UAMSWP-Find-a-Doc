<?php

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

require_once __DIR__.'/../Support/acf-post-meta.php';
require_once __DIR__.'/../Support/theme-images.php';

/**
 * Provider metadata contracts using real WordPress posts and terms.
 *
 * @command  composer test:integration -- --filter=ProviderMetadataTest
 */
final class ProviderMetadataTest extends IntegrationTestCase
{
    private function createProvider(): int
    {
        $provider = (int) self::factory()->post->create(['post_type' => 'provider']);
        $portal = (int) self::factory()->term->create(['taxonomy' => 'portal', 'name' => 'Patient Portal']);
        update_post_meta($provider, 'physician_portal', $portal);
        update_term_meta($portal, 'portal_content', 'Portal instructions');
        update_post_meta($provider, 'physician_locations', []);
        update_post_meta($provider, 'physician_resident_profile_group', [
            'physician_resident_academic_department' => 0,
            'physician_resident_academic_chief' => false,
            'physician_resident_academic_year' => 0,
        ]);

        return $provider;
    }

    /** @return array<string, array{int}> */
    public static function expertiseCounts(): array
    {
        return ['without expertise' => [0], 'with two expertise posts' => [2]];
    }

    /** @dataProvider expertiseCounts */
    public function test_publications_keep_all_rows_with_independent_one_based_keys(int $expertise_count): void
    {
        $provider = $this->createProvider();
        $expertise = self::factory()->post->create_many($expertise_count, ['post_type' => 'expertise', 'post_status' => 'publish']);
        update_post_meta($provider, 'physician_expertise', $expertise);
        update_post_meta($provider, 'physician_select_publications', [
            ['pubmed_id_pmid' => '10000001', 'pubmed_information' => 'Zulu publication'],
            ['pubmed_id_pmid' => '10000002', 'pubmed_information' => 'Alpha publication'],
        ]);

        $data = get_provider_meta(['id' => $provider]);

        $this->assertSame('Patient Portal', $data['physician_portal']['name']);
        $this->assertSame('Portal instructions', $data['physician_portal']['content']);
        $this->assertCount($expertise_count, $data['physician_expertise'] ?? []);
        $this->assertSame([
            1 => ['information' => 'Zulu publication'],
            2 => ['information' => 'Alpha publication'],
        ], $data['physician_pubmed']);
        $this->assertSame('{"1":{"information":"Zulu publication"},"2":{"information":"Alpha publication"}}', wp_json_encode($data['physician_pubmed']));
    }

    public function test_names_term_lists_and_occupation_titles_use_the_selected_terms(): void
    {
        $provider = $this->createProvider();
        foreach (['physician_first_name' => 'Ada', 'physician_middle_name' => 'M', 'physician_last_name' => 'Lovelace', 'physician_pedigree' => 'Jr', 'physician_eligible_appointments' => 'yes'] as $field => $value) {
            update_post_meta($provider, $field, $value);
        }

        $lists = [
            'physician_degree' => ['degree', ['MD', 'PhD'], 'physician_full_name', 'Ada M Lovelace&nbsp;Jr, MD, PhD'],
            'physician_patient_types' => ['patient_type', ['Children', 'Adults'], 'physician_patient_types', 'Children, Adults'],
            'physician_languages' => ['language', ['Spanish', 'English'], 'physician_languages', 'Spanish, English'],
            'physician_boards' => ['board', ['Board B', 'Board A'], 'physician_boards', 'Board B,Board A'],
            'physician_associations' => ['association', ['Association Z', 'Association A'], 'physician_associations', 'Association Z,Association A'],
        ];
        foreach ($lists as $field => [$taxonomy, $names]) {
            $ids = [];
            self::factory()->term->create(['taxonomy' => $taxonomy, 'name' => 'Not selected']);
            foreach (array_reverse($names) as $name) {
                $ids[] = self::factory()->term->create(['taxonomy' => $taxonomy, 'name' => $name]);
            }

            update_post_meta($provider, $field, array_reverse($ids));
        }

        $specialty = (int) self::factory()->term->create(['taxonomy' => 'clinical_title', 'name' => 'Cardiology']);
        update_post_meta($provider, 'physician_title', $specialty);
        update_term_meta($specialty, 'clinical_specialization_title', 'Cardiologist');

        $data = get_provider_meta(['id' => $provider]);

        foreach ($lists as [$taxonomy, $names, $key, $expected]) {
            $this->assertSame($expected, $data[$key]);
        }

        $this->assertSame('Cardiologist', $data['physician_title']);
        $this->assertSame('yes', $data['physician_eligible_appointments']);

        delete_term_meta($specialty, 'clinical_specialization_title');
        delete_post_meta($provider, 'physician_middle_name');
        delete_post_meta($provider, 'physician_pedigree');
        $fallback = get_provider_meta(['id' => $provider]);
        $this->assertSame('Cardiology', $fallback['physician_title']);
        $this->assertSame('Ada Lovelace, MD, PhD', $fallback['physician_full_name']);

        update_post_meta($provider, 'physician_resident', '1');
        $resident = get_provider_meta(['id' => $provider]);
        $this->assertSame('Resident Physician', $resident['physician_title']);
        $this->assertSame(0, $resident['physician_eligible_appointments']);
    }

    public function test_education_and_academic_rows_preserve_order_and_content(): void
    {
        $provider = $this->createProvider();
        $terms = [];
        foreach ([
            'residency' => ['educationtype', 'Residency'], 'fellowship' => ['educationtype', 'Fellowship'],
            'school_uams' => ['school', 'UAMS'], 'school_other' => ['school', 'Other School'],
            'medicine' => ['academic_department', 'Medicine'], 'surgery' => ['academic_department', 'Surgery'],
            'assistant' => ['academic_title', 'Assistant Professor'], 'professor' => ['academic_title', 'Professor'],
            'director' => ['academic_admin_title', 'Director'], 'chair' => ['academic_admin_title', 'Chair'],
        ] as $key => [$taxonomy, $name]) {
            $terms[$key] = self::factory()->term->create(['taxonomy' => $taxonomy, 'name' => $name]);
        }

        update_post_meta($provider, 'physician_education', [
            ['education_type' => $terms['fellowship'], 'school' => $terms['school_other'], 'description' => 'Advanced training'],
            ['education_type' => $terms['residency'], 'school' => $terms['school_uams'], 'description' => 'Foundation'],
        ]);
        update_post_meta($provider, 'physician_academic_appointment', [
            ['department' => $terms['surgery'], 'academic_title_tax' => $terms['assistant']],
            ['department' => $terms['medicine'], 'academic_title_tax' => $terms['professor']],
        ]);
        update_post_meta($provider, 'physician_academic_admin_title', [
            ['department' => $terms['medicine'], 'academic_admin_title_tax' => $terms['chair']],
            ['department' => $terms['surgery'], 'academic_admin_title_tax' => $terms['director']],
        ]);

        $data = get_provider_meta(['id' => $provider]);

        $this->assertSame([
            ['type' => 'Fellowship', 'school' => 'Other School', 'description' => 'Advanced training'],
            ['type' => 'Residency', 'school' => 'UAMS', 'description' => 'Foundation'],
        ], $data['physician_education']);
        $this->assertSame([
            ['department' => 'Surgery', 'title' => 'Assistant Professor'],
            ['department' => 'Medicine', 'title' => 'Professor'],
        ], $data['physician_faculty_appointment']);
        $this->assertSame([
            ['department' => 'Medicine', 'title' => 'Chair'],
            ['department' => 'Surgery', 'title' => 'Director'],
        ], $data['physician_academic_admin_role']);
    }

    /** @return array<string, array{int, string}> */
    public static function biographies(): array
    {
        return ['at limit' => [30, ''], 'over limit' => [31, ''], 'explicit summary' => [31, 'Custom summary']];
    }

    /** @dataProvider biographies */
    public function test_short_biography_uses_the_explicit_summary_or_thirty_word_fallback(int $words, string $summary): void
    {
        $provider = $this->createProvider();
        $bio = implode(' ', array_map(fn (int $i): string => 'word'.$i, range(1, $words)));
        update_post_meta($provider, 'physician_clinical_bio', $bio);
        update_post_meta($provider, 'physician_short_clinical_bio', $summary);

        $data = get_provider_meta(['id' => $provider]);

        $expected = implode(' ', array_map(fn (int $i): string => 'word'.$i, range(1, 30)));
        $this->assertSame($bio, $data['physician_clinical_bio']);
        $this->assertSame($summary !== '' ? $summary : $expected.($words > 30 ? ' &hellip;' : ''), $data['physician_short_clinical_bio']);
    }

    /**
     * Keep selected IDs, order, and each location's own phones and stored map.
     */
    public function test_selected_locations_keep_independent_phone_and_map_payloads(): void
    {
        // Create posts in an order different from both selection and title order.
        $provider = $this->createProvider();
        $alpha = (int) self::factory()->post->create(['post_type' => 'location', 'post_status' => 'publish', 'post_title' => 'Alpha Clinic', 'post_name' => 'alpha-clinic']);
        $zulu = (int) self::factory()->post->create(['post_type' => 'location', 'post_status' => 'publish', 'post_title' => 'Zulu Clinic', 'post_name' => 'zulu-clinic']);
        $middle = (int) self::factory()->post->create(['post_type' => 'location', 'post_status' => 'publish', 'post_title' => 'Middle Clinic', 'post_name' => 'middle-clinic']);
        $delta = (int) self::factory()->post->create(['post_type' => 'location', 'post_status' => 'publish', 'post_title' => 'Delta Clinic', 'post_name' => 'delta-clinic']);
        self::factory()->post->create(['post_type' => 'location', 'post_status' => 'publish', 'post_title' => 'Not selected']);
        $this->assertArrayNotHasKey('physician_locations', get_provider_meta(['id' => $provider]));
        update_post_meta($provider, 'physician_locations', [$zulu, $alpha, $middle, $delta]);

        // Populate disabled fields as controls and use distinct phones per location.
        foreach ([
            $zulu => ['location_phone' => '(501) 555-0100 ext 9', 'location_clinic_phone_query' => '1', 'field_location_appointment_phone_query' => '1', 'location_new_appointments_phone' => '501.555.0101', 'location_return_appointments_phone' => '+1 501 555 0102', 'location_map' => ['lat' => 34.71, 'lng' => -92.32]],
            $alpha => ['location_phone' => '501-555-0200', 'location_clinic_phone_query' => '0', 'field_location_appointment_phone_query' => '1', 'location_new_appointments_phone' => 'ignored new', 'location_return_appointments_phone' => 'ignored returning', 'location_map' => 'not a map'],
            $middle => ['location_phone' => '501-555-0300', 'location_clinic_phone_query' => '1', 'field_location_appointment_phone_query' => '0', 'location_new_appointments_phone' => '501-555-0301', 'location_return_appointments_phone' => 'ignored returning'],
            $delta => ['location_phone' => '501-555-0400', 'location_clinic_phone_query' => '1', 'field_location_appointment_phone_query' => '1', 'location_new_appointments_phone' => '501-555-0401', 'location_return_appointments_phone' => '501-555-0402', 'location_map' => ['lat' => 35.1, 'lng' => -93.2]],
        ] as $id => $fields) {
            foreach ($fields as $field => $value) {
                update_post_meta($id, $field, $value);
            }
        }

        // Compare full rows to catch stale values leaking from a previous iteration.
        $data = get_provider_meta(['id' => $provider]);
        $this->assertSame([
            $zulu => [
                'link' => get_permalink($zulu), 'title' => 'Zulu Clinic', 'slug' => 'zulu-clinic',
                'locations_phone_title' => 'Clinic Phone Number', 'location_clinic_phone' => '(501) 555-0100 ext 9',
                'location_phone_text' => 'Appointment Phone Numbers',
                'location_new_appointments_phone' => '501.555.0101', 'location_new_appointments_phonetext' => 'New Patients',
                'location_return_appointments_phone' => '+1 501 555 0102', 'location_return_appointments_phone_text' => 'Returning Patients',
                'location_lat' => 34.71, 'location_lng' => -92.32,
                'location_directions' => '<a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/34.71,-92.32" target="_blank" aria-label="Get Directions to Zulu Clinic">Get Directions</a>',
            ],
            $alpha => [
                'link' => get_permalink($alpha), 'title' => 'Alpha Clinic', 'slug' => 'alpha-clinic',
                'locations_phone_title' => 'Clinic Phone Number', 'location_clinic_phone' => '501-555-0200',
                'location_phone_text' => 'Appointment Phone Number',
                'location_new_appointments_phone' => '', 'location_new_appointments_phonetext' => 'New and Returning Patients',
                'location_return_appointments_phone' => '', 'location_return_appointments_phone_text' => '',
            ],
            $middle => [
                'link' => get_permalink($middle), 'title' => 'Middle Clinic', 'slug' => 'middle-clinic',
                'locations_phone_title' => 'Clinic Phone Number', 'location_clinic_phone' => '501-555-0300',
                'location_phone_text' => 'Appointment Phone Numbers',
                'location_new_appointments_phone' => '501-555-0301', 'location_new_appointments_phonetext' => 'New Patients',
                'location_return_appointments_phone' => '', 'location_return_appointments_phone_text' => '',
            ],
            $delta => [
                'link' => get_permalink($delta), 'title' => 'Delta Clinic', 'slug' => 'delta-clinic',
                'locations_phone_title' => 'Clinic Phone Number', 'location_clinic_phone' => '501-555-0400',
                'location_phone_text' => 'Appointment Phone Numbers',
                'location_new_appointments_phone' => '501-555-0401', 'location_new_appointments_phonetext' => 'New Patients',
                'location_return_appointments_phone' => '501-555-0402', 'location_return_appointments_phone_text' => 'Returning Patients',
                'location_lat' => 35.1, 'location_lng' => -93.2,
                'location_directions' => '<a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/35.1,-93.2" target="_blank" aria-label="Get Directions to Delta Clinic">Get Directions</a>',
            ],
        ], $data['physician_locations']);
    }
}
