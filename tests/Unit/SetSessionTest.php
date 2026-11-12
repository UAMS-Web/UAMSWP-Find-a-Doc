<?php

/**
 * Pins the session setter (class.fad-set-session.php).
 *
 * The file starts a PHP session as it loads and reads `$_REQUEST`. Each case loads it in this process with the
 * session kept off cookies and headers (a command-line run has already sent output) and saved under the system
 * temporary directory.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

final class SetSessionTest extends UnitTestCase
{
    /** @var array<string, mixed> */
    private array $request_before = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->request_before = $_REQUEST;
        ini_set('session.use_cookies', '0');
        ini_set('session.cache_limiter', '');
        ini_set('session.save_path', sys_get_temp_dir());
        session_id('fadsetsessiontest'.bin2hex(random_bytes(4)));
    }

    protected function tearDown(): void
    {
        $_REQUEST = $this->request_before;
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        parent::tearDown();
    }

    private function load(): void
    {
        include dirname(__DIR__, 2).'/includes/class.fad-set-session.php';
    }

    public function test_a_variable_and_value_are_stored_in_the_session_and_echoed(): void
    {
        $_REQUEST = ['variable' => 'region', 'value' => 'north'];

        $this->expectOutputString('Session: region; Value: north;');

        $this->load();

        $this->assertSame(['region' => 'north'], $_SESSION);
    }

    public function test_both_are_escaped_before_they_are_stored_or_echoed(): void
    {
        $_REQUEST = ['variable' => 'a"b', 'value' => '<b>x</b>'];

        $this->expectOutputString('Session: a&quot;b; Value: &lt;b&gt;x&lt;/b&gt;;');

        $this->load();

        $this->assertSame(['a&quot;b' => '&lt;b&gt;x&lt;/b&gt;'], $_SESSION);
    }

    public function test_a_request_missing_the_value_stores_and_echoes_nothing(): void
    {
        $_REQUEST = ['variable' => 'region'];

        $this->expectOutputString('');

        $this->load();

        $this->assertSame([], $_SESSION);
    }

    public function test_a_request_missing_the_variable_stores_and_echoes_nothing(): void
    {
        $_REQUEST = ['value' => 'north'];

        $this->expectOutputString('');

        $this->load();

        $this->assertSame([], $_SESSION);
    }
}
