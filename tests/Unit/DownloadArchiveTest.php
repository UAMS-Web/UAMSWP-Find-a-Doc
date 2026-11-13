<?php

/**
 * Pins what the GitHub download of the plugin contains, so the keep-list in #902 can be checked against what the plugin runs.
 *
 * The archive is built the way GitHub builds it: git archive of HEAD, which honors the export-ignore attributes.
 * The attributes are read from the working tree (--worktree-attributes), so a changed .gitattributes is tested
 * before it is committed. Every file the plugin requires, followed through its require and include statements,
 * must be in the archive.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class DownloadArchiveTest extends TestCase
{
    private const ENTRY_FILES = ['uamswp-find-a-doc.php', 'includes/find-a-doc.php'];

    /** @var string[]|null Paths of the regular files in the archive, cached across tests */
    private static ?array $archive = null;

    private static function root(): string
    {
        return dirname(__DIR__, 2);
    }

    /**
     * Build the archive of HEAD in a checkout the way GitHub does and list its regular files.
     *
     * @return string[]
     */
    private function archiveOf(string $checkout): array
    {
        $tar = tempnam(sys_get_temp_dir(), 'fad-archive-');
        $this->assertNotFalse($tar, 'a temporary file for the archive');

        $output = [];
        $status = 0;
        exec('git -C '.escapeshellarg($checkout).' archive --worktree-attributes --format=tar -o '.escapeshellarg($tar).' HEAD 2>&1', $output, $status);
        $this->assertSame(0, $status, 'git archive exits 0, got:
'.implode('
', $output));

        $files = self::tarFileNames((string) file_get_contents($tar));
        unlink($tar);

        return $files;
    }

    /**
     * @return string[] Paths of the regular files git archive puts in the download of this checkout
     */
    private function archivedFiles(): array
    {
        if (self::$archive !== null) {
            return self::$archive;
        }

        if (! is_dir(self::root().'/.git') && ! is_file(self::root().'/.git')) {
            $this->markTestSkipped('The download archive is built from a git checkout; this tree has no .git.');
        }

        $files = $this->archiveOf(self::root());
        $this->assertNotEmpty($files, 'the archive holds files, so a clean result below is not an empty read');

        return self::$archive = $files;
    }

    /**
     * Read the regular-file names out of a tar stream, following the pax extended headers git writes for long paths.
     *
     * @return string[]
     */
    private static function tarFileNames(string $tar): array
    {
        $names = [];
        $longName = null;
        $offset = 0;
        $length = strlen($tar);

        while ($offset + 512 <= $length) {
            $header = substr($tar, $offset, 512);
            if (trim($header, "\0") === '') {
                break;
            }

            $name = rtrim(substr($header, 0, 100), "\0");
            $prefix = rtrim(substr($header, 345, 155), "\0");
            $size = (int) octdec(trim(substr($header, 124, 12), "\0 "));
            $type = $header[156];
            $offset += 512;
            $body = substr($tar, $offset, $size);
            $offset += (int) (ceil($size / 512) * 512);

            if ($type === 'x') {
                if (preg_match('/\d+ path=([^\n]*)\n/', $body, $m) === 1) {
                    $longName = $m[1];
                }

                continue;
            }

            if ($type === 'g') {
                continue;
            }

            $path = $longName ?? ($prefix !== '' ? $prefix.'/'.$name : $name);
            $longName = null;
            if ($type === '0' || $type === "\0") {
                $names[] = $path;
            }
        }

        return $names;
    }

    /**
     * Follow the require and include statements from the entry files.
     *
     * Recognized: a string literal appended to __DIR__, dirname( __FILE__ ) or UAMS_FAD_PATH. Commented lines are skipped.
     *
     * @return array<string, string> Required path (relative to the plugin root) => the file that requires it
     */
    private function requiredFiles(): array
    {
        $required = [];
        $queue = self::ENTRY_FILES;
        $seen = [];

        while ($queue !== []) {
            $file = array_shift($queue);
            if (isset($seen[$file])) {
                continue;
            }

            $seen[$file] = true;

            $source = file_get_contents(self::root().'/'.$file);
            $this->assertNotFalse($source, "{$file} is readable");

            preg_match_all(
                '~^[ \t]*(?:require|include)(?:_once)?\s*\(?\s*(__DIR__|dirname\(\s*__FILE__\s*\)|UAMS_FAD_PATH)\s*\.\s*[\'"]([^\'"]+)[\'"]~m',
                $source,
                $matches,
                PREG_SET_ORDER
            );

            foreach ($matches as $match) {
                $base = $match[1] === 'UAMS_FAD_PATH' ? '' : dirname($file);
                $path = ltrim(($base === '.' || $base === '' ? '' : $base.'/').ltrim($match[2], '/'), '/');
                $required[$path] ??= $file;
                $queue[] = $path;
            }
        }

        return $required;
    }

    public function test_the_requires_are_followed_from_both_entry_files(): void
    {
        $required = $this->requiredFiles();

        // The instrument must see requires it is known to contain, or an empty result would read as a clean one.
        $this->assertSame('uamswp-find-a-doc.php', $required['includes/find-a-doc.php']);
        $this->assertSame('uamswp-find-a-doc.php', $required['required-plugins.php']);
        $this->assertSame('includes/find-a-doc.php', $required['includes/class.fad-functions.php']);
        $this->assertSame('required-plugins.php', $required['tgm/class-tgm-plugin-activation.php']);
        $this->assertSame('includes/class.fad-functions.php', $required['templates/loops/location-card.php']);
        $this->assertGreaterThan(15, count($required));
    }

    public function test_every_file_the_plugin_requires_is_in_the_download(): void
    {
        $archive = $this->archivedFiles();

        $missing = [];
        foreach (array_merge(array_fill_keys(self::ENTRY_FILES, 'the plugin'), $this->requiredFiles()) as $path => $requiredBy) {
            if (! in_array($path, $archive, true)) {
                $missing[] = "{$path} (required by {$requiredBy})";
            }
        }

        $this->assertSame([], $missing, "the download is missing files the plugin requires:\n".implode("\n", $missing));
    }

    public function test_every_required_file_exists_in_the_checkout(): void
    {
        $absent = [];
        foreach ($this->requiredFiles() as $path => $requiredBy) {
            if (! is_file(self::root().'/'.$path)) {
                $absent[] = "{$path} (required by {$requiredBy})";
            }
        }

        $this->assertSame([], $absent, "the plugin requires files the repository does not have:\n".implode("\n", $absent));
    }

    public function test_the_archive_reader_sees_export_ignore(): void
    {
        // Control, independent of this repository's attributes: a scratch repository with one kept and one ignored
        // file. An archive reader that ignored the attributes, or read no names, would fail this.
        $dir = sys_get_temp_dir().'/fad-archive-control-'.bin2hex(random_bytes(4));
        mkdir($dir.'/sub/deeper', 0777, true);
        file_put_contents($dir.'/kept.php', '<?php
');
        file_put_contents($dir.'/dropped.php', '<?php
');
        file_put_contents($dir.'/sub/deeper/'.str_repeat('long-name-', 12).'.php', '<?php
');
        file_put_contents($dir.'/.gitattributes', '/dropped.php export-ignore
');

        $git = 'git -c user.name=control -c user.email=control@example.test -C '.escapeshellarg($dir);
        exec('git init -q '.escapeshellarg($dir).' 2>&1 && '.$git.' add -A 2>&1 && '.$git.' commit -q -m control 2>&1', $output, $status);
        $this->assertSame(0, $status, 'the control repository is built, got:
'.implode('
', $output));

        $files = $this->archiveOf($dir);

        // Remove the scratch tree (read-only objects on Windows need a chmod first).
        $items = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($items as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : (chmod($item->getPathname(), 0666) && unlink($item->getPathname()));
        }

        rmdir($dir);

        $this->assertContains('kept.php', $files);
        $this->assertNotContains('dropped.php', $files, 'export-ignore keeps a file out of the archive');
        $this->assertContains('sub/deeper/'.str_repeat('long-name-', 12).'.php', $files, 'a path longer than 100 bytes is read whole');
        $this->assertContains('.gitattributes', $files);
    }
}
