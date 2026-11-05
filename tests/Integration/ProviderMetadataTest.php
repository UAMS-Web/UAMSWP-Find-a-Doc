<?php

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

require_once __DIR__.'/../Support/acf-post-meta.php';
require_once __DIR__.'/../Support/theme-images.php';

final class ProviderMetadataTest extends IntegrationTestCase
{
    /** @return array<string, array{int}> */
    public static function expertiseCounts(): array
    {
        return ['without expertise' => [0], 'with two expertise posts' => [2]];
    }

    /** @dataProvider expertiseCounts */
    public function test_publications_keep_all_rows_with_independent_one_based_keys(int $expertise_count): void
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
}
