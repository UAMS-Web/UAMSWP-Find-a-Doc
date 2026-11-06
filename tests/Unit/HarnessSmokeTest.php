<?php

/**
 * First unit test pinning harness load for UAMSWP Find-a-Doc.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

final class HarnessSmokeTest extends UnitTestCase
{
    /**
     * @return void
     */
    public function test_harness_loads_plugin_surface(): void
    {
        $this->assertTrue(\defined('UAMS_FAD_PATH'));
    }
}
