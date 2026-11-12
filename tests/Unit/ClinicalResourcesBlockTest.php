<?php

/**
 * The clinical resources block (templates/blocks/clinical-resources.php): a list of links for more than four
 * resources shown without a limit, cards otherwise, and the link to the full list.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';
require_once __DIR__.'/../Support/acf-blocks.php';

/**
 * A stand-in for a posts query over the given post ids that moves on with the_post().
 *
 * @param  list<int>  $ids
 */
function resources_block_query(array $ids): object
{
    $current = new stdClass;
    $current->id = 0;
    when('get_the_ID')->alias(static fn (): int => $current->id);

    return new class($ids, $current)
    {
        private int $position = 0;

        /** @param list<int> $ids */
        public function __construct(private readonly array $ids, private readonly stdClass $current) {}

        public function have_posts(): bool
        {
            return $this->position < count($this->ids);
        }

        public function the_post(): void
        {
            $this->current->id = $this->ids[$this->position];
            $this->position++;
        }
    };
}

/**
 * Render the clinical resources block with the variables the page template sets.
 *
 * @param  list<int>  $ids  The ids of the resources the query returns, all of them published unless named in `drafts`.
 * @param  array<string, mixed>  $variables  Overrides of the other variables.
 * @param  list<int>  $drafts  The ids of the resources that are not published.
 */
function resources_block(array $ids, array $variables = [], array $drafts = []): string
{
    uamswp_test_acf_blocks_file();

    when('wp_reset_postdata')->justReturn(null);
    when('get_post_status')->alias(static fn (int $id): string => in_array($id, $drafts, true) ? 'draft' : 'publish');
    when('esc_url')->returnArg();
    when('esc_attr')->alias(static fn (string $text): string => htmlspecialchars($text));
    when('esc_html')->alias(static fn (string $text): string => htmlspecialchars($text));
    when('get_field')->alias(static fn (string $name): mixed => $name === 'clinical_resource_type' ? ['value' => 'text', 'label' => 'Article'] : null);
    when('get_the_title')->alias(static fn (int $id): string => 'Resource '.$id);
    when('get_the_excerpt')->justReturn('An excerpt.');
    when('get_the_content')->justReturn('');
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('wp_trim_words')->returnArg();
    when('get_permalink')->alias(static fn (int $id): string => 'https://example.test/resource/'.$id.'/');
    when('get_post_thumbnail_id')->justReturn(0);
    when('get_post_meta')->justReturn('');
    when('has_post_thumbnail')->justReturn(false);

    return uamswp_test_include_partial('blocks/clinical-resources', $variables + [
        'resources' => $ids,
        'resource_query' => resources_block_query($ids),
        'resource_postsPerPage' => -1,
        'resource_more_suppress' => false,
        'resource_more_key' => 'condition',
        'resource_more_value' => 'asthma',
        'resource_heading_related_pre' => false,
        'resource_heading_related_post' => false,
        'resource_heading_related_name' => 'Asthma',
    ]);
}

test('the clinical resources block lists more than four resources with no limit as links', function (): void {
    $output = resources_block([1, 2, 3, 4, 5]);

    expect($output)->toContain('class="uams-module link-list link-list-layout-split bg-auto" id="related-resources"')
        ->and($output)->toContain('<span class="title">Resources</span>')
        ->and(substr_count($output, '<li'))->toBe(5)
        ->and($output)->toContain('>Resource 3</a>')
        ->and($output)->not->toContain('card-list');
});

test('the clinical resources block shows cards for four or fewer resources with no limit', function (): void {
    $output = resources_block([1, 2, 3, 4]);

    expect($output)->toContain('class="uams-module stacked-image-text bg-auto" id="related-resources" aria-labelledby="related-resources-title"')
        ->and(substr_count($output, '<div class="card">'))->toBe(4)
        ->and($output)->toContain('<h3 class="card-title h5">Resource 2 ')
        ->and($output)->not->toContain('link-list');
});

test('the clinical resources block shows cards when a limit is set, however many there are', function (): void {
    $output = resources_block([1, 2, 3, 4, 5], ['resource_postsPerPage' => 3]);

    expect($output)->toContain('card-list card-list-left')
        ->and($output)->not->toContain('link-list');
});

test('the clinical resources block does not count a resource that is not published', function (): void {
    // Four published resources are not enough for the list layout, whatever the query holds.
    $output = resources_block([1, 2, 3, 4, 5], [], [5]);

    expect($output)->toContain('card-list card-list-left');
});

test('the clinical resources block counts nothing without resources or without posts in the query', function (): void {
    $output = resources_block([], ['resources' => [1, 2, 3, 4, 5]]);

    expect($output)->toContain('card-list card-list-left')
        ->and($output)->not->toContain('<div class="card">');

    $noResources = resources_block([1, 2, 3, 4, 5], ['resources' => []]);

    expect($noResources)->toContain('card-list card-list-left');
});

test('the clinical resources block builds its heading from the related name', function (bool $pre, bool $post, string $expected): void {
    $output = resources_block([1, 2, 3, 4, 5], ['resource_heading_related_pre' => $pre, 'resource_heading_related_post' => $post]);

    expect($output)->toContain('<span class="title">'.$expected.'</span>');
})->with([
    'plain' => [false, false, 'Resources'],
    'related' => [true, false, 'Related Resources'],
    'related to the subject' => [false, true, 'Resources Related to Asthma'],
    'both' => [true, true, 'Related Resources Related to Asthma'],
]);

test('the clinical resources block offers the full list when the cards leave some out', function (): void {
    $output = resources_block([1, 2, 3, 4], ['resource_postsPerPage' => 3]);

    expect($output)->toContain('<p class="lead">Want to find more resources related to Asthma?</p>')
        ->and($output)->toContain('<a href="/clinical-resource/?condition=asthma" class="btn btn-outline-primary" aria-label="View the full list of clinical resources related to Asthma" target="_blank" rel="noopener">View the Full List</a>');
});

test('the clinical resources block leaves out the full list link without a count over the limit, a key and value, or when suppressed', function (array $variables, array $ids): void {
    $output = resources_block($ids, $variables + ['resource_postsPerPage' => 3]);

    expect($output)->not->toContain('Want to find more resources');
})->with([
    'within the limit' => [[], [1, 2, 3]],
    'no key' => [['resource_more_key' => ''], [1, 2, 3, 4]],
    'no value' => [['resource_more_value' => ''], [1, 2, 3, 4]],
    'suppressed' => [['resource_more_suppress' => true], [1, 2, 3, 4]],
]);

test('the clinical resources block describes the link with a label that has no quotes or entities', function (): void {
    $output = resources_block([1, 2, 3, 4], ['resource_postsPerPage' => 3, 'resource_heading_related_name' => 'Mom&#8217;s "Heart"']);

    expect($output)->toContain('aria-label="View the full list of clinical resources related to Mom&#039;s &#039;Heart&#039;"');
});
