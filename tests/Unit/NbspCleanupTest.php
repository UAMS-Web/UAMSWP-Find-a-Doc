<?php

/**
 * The attribute-label cleanup used across the templates encodes a title with htmlentities() and decodes it again,
 * so a quote or an accent survives inside an attribute. A literal `&nbsp;` in a title has its ampersand encoded to
 * `&amp;nbsp;` and comes back as `&nbsp;` after the decode, unless it is replaced before the title is encoded.
 *
 * Four templates are rendered in their own tests. The rest run too much of the page to render here, so this test
 * reads every use of the cleanup from the source.
 */

declare(strict_types=1);

/**
 * Find each htmlentities() call that does not have the `&nbsp;` replacement on the same variable before it.
 *
 * @return list<string> The offending lines, trimmed.
 */
function nbsp_cleanup_gaps(string $source): array
{
    $lines = preg_split('/\R/', $source);
    $gaps = [];

    foreach ($lines as $index => $line) {
        if (! preg_match('/htmlentities\((?:str_replace\(\'&nbsp;\', \' \', )?(\$\w+)/', $line, $match)) {
            continue;
        }

        $variable = preg_quote($match[1], '/');
        $replaced_inline = (bool) preg_match('/htmlentities\(str_replace\(\'&nbsp;\', \' \', '.$variable.'/', $line);
        $replaced_before = $index > 0 && preg_match('/^\s*'.$variable.' = str_replace\(\'&nbsp;\', \' \', '.$variable.'\);/', $lines[$index - 1]);

        if (! $replaced_inline && ! $replaced_before) {
            $gaps[] = trim($line);
        }
    }

    return $gaps;
}

/**
 * @return list<string> The templates that call htmlentities(), relative to the templates folder.
 */
function nbsp_cleanup_templates(): array
{
    $found = [];
    $directory = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(UAMS_FAD_PATH.'templates', FilesystemIterator::SKIP_DOTS));

    foreach ($directory as $file) {
        if ($file->getExtension() === 'php' && str_contains((string) file_get_contents($file->getPathname()), 'htmlentities(')) {
            $found[] = substr(str_replace('\\', '/', $file->getPathname()), strlen(str_replace('\\', '/', UAMS_FAD_PATH.'templates/')));
        }
    }

    sort($found);

    return $found;
}

test('every use of the label cleanup replaces the non-breaking space entity before it encodes', function (string $template): void {
    expect(nbsp_cleanup_gaps((string) file_get_contents(UAMS_FAD_PATH.'templates/'.$template)))->toBe([]);
})->with(array_combine(nbsp_cleanup_templates(), array_map(static fn (string $template): array => [$template], nbsp_cleanup_templates())));

test('the reader finds the templates that use the cleanup', function (): void {
    expect(nbsp_cleanup_templates())->toContain('loops/physician-card.php', 'single-locations.php', 'blocks/appointment-provider.php')
        ->and(count(nbsp_cleanup_templates()))->toBe(16);
});

test('the reader tells a cleanup that replaces first from one that does not', function (): void {
    $before = "\$a = str_replace('&nbsp;', ' ', \$a);\n\$a = htmlentities(\$a, ENT_HTML401, 'UTF-8');\n";
    $inline = "\$b = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities(str_replace('&nbsp;', ' ', \$b), ENT_HTML401, 'utf-8')));\n";
    $after = "\$c = htmlentities(\$c, ENT_HTML401, 'UTF-8');\n\$c = str_replace('&nbsp;', ' ', \$c);\n";
    $other = "\$d = str_replace('&nbsp;', ' ', \$e);\n\$d = htmlentities(\$d, ENT_HTML401, 'UTF-8');\n";
    $old = "\$f = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities(\$f, ENT_HTML401, 'utf-8')));\n";

    expect(nbsp_cleanup_gaps($before))->toBe([])
        ->and(nbsp_cleanup_gaps($inline))->toBe([])
        ->and(nbsp_cleanup_gaps($after))->toBe(["\$c = htmlentities(\$c, ENT_HTML401, 'UTF-8');"])
        ->and(nbsp_cleanup_gaps($other))->toBe(["\$d = htmlentities(\$d, ENT_HTML401, 'UTF-8');"])
        ->and(nbsp_cleanup_gaps($old))->toBe(["\$f = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities(\$f, ENT_HTML401, 'utf-8')));"]);
});
