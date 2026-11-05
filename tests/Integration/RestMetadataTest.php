<?php

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

require_once __DIR__.'/../Support/acf-post-meta.php';

final class RestMetadataTest extends IntegrationTestCase
{
    /**
     * @return array<string, array{string, array<int, array{string, string, string, string}>}>
     */
    public static function relationships(): array
    {
        return [
            'condition' => ['get_condition_meta', [
                ['expertise', 'condition_expertise', 'condition_expertise', 'condition_expertise_list'],
                ['treatment', 'condition_treatments', 'condition_treatments', 'condition_treatments_list'],
                ['provider', 'condition_physicians', 'condition_provider', 'condition_provider_list'],
                ['location', 'condition_locations', 'condition_location', 'condition_location_list'],
            ]],
            'treatment' => ['get_treatment_meta', [
                ['expertise', 'treatment_procedure_expertise', 'treatment_expertise', 'treatment_expertise_list'],
                ['condition', 'treatment_conditions', 'treatment_conditions', 'treatment_conditions_list'],
                ['provider', 'treatment_procedure_physicians', 'treatment_provider', 'treatment_provider_list'],
                ['location', 'treatment_procedure_locations', 'treatment_location', 'treatment_location_list'],
            ]],
        ];
    }

    /**
     * @dataProvider relationships
     *
     * @param  array<int, array{string, string, string, string}>  $relationships
     */
    public function test_absent_relationships_return_empty_lists_without_item_collections(string $callback, array $relationships): void
    {
        $post_id = (int) self::factory()->post->create();
        $expected = [];
        foreach ($relationships as [$type, $field, $items, $list]) {
            $expected[$list] = '';
        }

        $this->assertSame($expected, $callback(['id' => $post_id]));
    }

    /**
     * @dataProvider relationships
     *
     * @param  array<int, array{string, string, string, string}>  $relationships
     */
    public function test_selected_published_relationships_are_alphabetized_and_keep_their_public_fields(string $callback, array $relationships): void
    {
        $post_id = (int) self::factory()->post->create();
        $expected = [];
        foreach ($relationships as [$type, $field, $items, $list]) {
            $zulu = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'publish', 'post_title' => 'Zulu', 'post_name' => 'zulu']);
            $alpha = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'publish', 'post_title' => 'Alpha', 'post_name' => 'alpha']);
            self::factory()->post->create(['post_type' => $type, 'post_status' => 'publish', 'post_title' => 'Not selected']);
            update_post_meta($post_id, $field, [$zulu, $alpha]);
            update_post_meta($alpha, 'physician_full_name', 'Doctor First');
            update_post_meta($zulu, 'physician_full_name', 'Doctor Last');

            $expected[$items] = [
                $alpha => ['link' => get_permalink($alpha), 'title' => $type === 'provider' ? 'Doctor First' : 'Alpha', 'slug' => 'alpha'],
                $zulu => ['link' => get_permalink($zulu), 'title' => $type === 'provider' ? 'Doctor Last' : 'Zulu', 'slug' => 'zulu'],
            ];
            $expected[$list] = $type === 'provider' ? 'Doctor First | Doctor Last' : 'Alpha, Zulu';
        }

        $this->assertSame($expected, $callback(['id' => $post_id]));
    }

    /**
     * @dataProvider relationships
     *
     * @param  array<int, array{string, string, string, string}>  $relationships
     */
    public function test_unpublished_and_wrong_type_relationships_do_not_leak_into_public_metadata(string $callback, array $relationships): void
    {
        $post_id = (int) self::factory()->post->create();
        $expected = [];
        foreach ($relationships as [$type, $field, $items, $list]) {
            $draft = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'draft']);
            $private = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'private']);
            $page = (int) self::factory()->post->create(['post_type' => 'page', 'post_status' => 'publish']);
            update_post_meta($post_id, $field, [$draft, $private, $page]);
            $expected[$list] = '';
        }

        $this->assertSame($expected, $callback(['id' => $post_id]));
    }
}
