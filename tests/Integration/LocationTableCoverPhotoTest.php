<?php

/**
 * The Business Profile location table reads a location's cover and other photos from that location alone.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

require_once __DIR__.'/../Support/acf-post-meta.php';

/**
 * `uamswp_fad_gmb_location_table()` builds every location it is given in one call, so a photo variable
 * assigned only for a location that has images leaked into the next location, and into a first location
 * that had none (an undefined variable).
 */
final class LocationTableCoverPhotoTest extends IntegrationTestCase
{
    private int $region;

    public function set_up(): void
    {
        parent::set_up();

        $this->region = (int) self::factory()->term->create(['taxonomy' => 'region', 'name' => 'Central']);
    }

    /**
     * A published location in the region, with the given images as its featured image.
     */
    private function location(string $title, ?int $image): int
    {
        $id = (int) self::factory()->post->create(['post_type' => 'location', 'post_status' => 'publish', 'post_title' => $title]);

        update_post_meta($id, 'location_region', (string) $this->region);
        update_post_meta($id, 'location_hours_group', ['location_telemed_query' => '']);

        if ($image !== null) {
            set_post_thumbnail($id, $image);
        }

        return $id;
    }

    private function image(string $name): int
    {
        return (int) self::factory()->attachment->create_object(['file' => $name.'.jpg', 'post_mime_type' => 'image/jpeg', 'post_title' => $name]);
    }

    /**
     * @param  array<int, int>  $ids
     * @return array<int, array<int, string>> The table rows by location post ID order of the call.
     */
    private function rows(array $ids): array
    {
        $table = uamswp_fad_gmb_location_table($ids);

        return array_values($table['body'] ?? $table[1] ?? []);
    }

    public function test_a_location_with_no_images_has_an_empty_cover_and_other_photos_cell(): void
    {
        $rows = $this->rows([$this->location('Alpha clinic', null)]);

        $this->assertCount(1, $rows);
        $this->assertSame('', $rows[0][19]);
        $this->assertSame('', $rows[0][20]);
    }

    public function test_a_location_after_one_with_images_does_not_inherit_its_photos(): void
    {
        $first = $this->location('Alpha clinic', $this->image('alpha'));
        $second = $this->location('Beta clinic', null);

        $rows = $this->rows([$first, $second]);

        $this->assertCount(2, $rows);
        $this->assertStringContainsString('alpha', $rows[0][19], 'the first location has its own cover photo');
        $this->assertSame('', $rows[1][19], 'the second has none, so its cell is empty');
        $this->assertSame('', $rows[1][20]);
    }
}

// cspell:ignore uamswp
