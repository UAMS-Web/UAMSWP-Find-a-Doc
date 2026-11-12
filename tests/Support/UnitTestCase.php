<?php

/**
 * Base case for every Unit test on the PHPUnit path.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Support;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

/**
 * PHPUnit TestCase with Brain Monkey around each test.
 *
 * MockeryPHPUnitIntegration counts Brain Monkey / Mockery expectations as
 * assertions, so a test whose only check is `Functions\expect(...)->once()` is
 * not reported as risky under failOnRisky.
 */
abstract class UnitTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        setUp();
    }

    protected function tearDown(): void
    {
        tearDown();
        parent::tearDown();
    }
}
