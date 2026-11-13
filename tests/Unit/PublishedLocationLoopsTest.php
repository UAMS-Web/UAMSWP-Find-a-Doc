<?php

/**
 * The loops that look for one published related post and set a flag (`$location_valid = true;` and the like).
 * They must leave the loop at the first match with `break;`. A bare `$break;` once stood there, which reads a
 * variable and leaves the loop running to the end of the list.
 *
 * The provider loop and the resource card are rendered in their own tests, which count the status reads. The
 * other templates run too much of the page to render here, so their loops are read from the source.
 */

declare(strict_types=1);

/**
 * Find each flag assignment inside a published-status check and say whether `break;` follows it.
 *
 * @return list<array{string, bool}> The flag variable and whether the next statement is `break;`.
 */
function published_loop_flags(string $source): array
{
    preg_match_all('/get_post_status\((?:uamswp_fad_int\(\$\w+\)|\$\w+)\)\s*==\s*\'publish\'\)\s*\{\s*(\$\w+_valid)\s*=\s*true;\s*(break;)?/', $source, $matches, PREG_SET_ORDER);

    return array_map(static fn (array $match): array => [$match[1], isset($match[2])], $matches);
}

test('each loop that looks for a published post leaves at the first match', function (string $template, array $flags): void {
    $found = published_loop_flags((string) file_get_contents(UAMS_FAD_PATH.'templates/'.$template));

    expect(array_column($found, 0))->toBe($flags)
        ->and(array_column($found, 1))->each->toBeTrue();
})->with([
    'the Doximity list' => ['doximity-list.php', ['$location_valid']],
    'the provider loop' => ['fwp/physician-loop.php', ['$location_valid']],
    'the resource card' => ['loops/resource-card.php', ['$resource_provider_valid', '$resource_location_valid', '$resource_condition_valid', '$resource_treatment_valid', '$resource_expertise_valid']],
    'the condition page' => ['single-condition.php', ['$location_valid']],
    'the provider page' => ['single-physicians.php', ['$location_valid', '$expertise_valid', '$expertise_valid']],
    'the treatment page' => ['single-treatment.php', ['$location_valid']],
]);

test('the reader tells a loop that leaves from one that does not', function (): void {
    $open = "if (get_post_status(\$location) == 'publish') {\n    \$location_valid = true;\n\n}\n";
    $closed = "if (get_post_status(\$location) == 'publish') {\n    \$location_valid = true;\n    break;\n}\n";

    expect(published_loop_flags($open))->toBe([['$location_valid', false]])
        ->and(published_loop_flags($closed))->toBe([['$location_valid', true]]);
});
