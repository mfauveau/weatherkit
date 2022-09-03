<?php

declare(strict_types=1);

namespace Rugaard\WeatherKit\Tests\Units\Speed;

use Rugaard\WeatherKit\Contracts\Unit;
use Rugaard\WeatherKit\Tests\AbstractTestCase;
use Rugaard\WeatherKit\Units\Speed\FootPerSecond;

/**
 * FootPerSecondTest.
 *
 * @package Rugaard\WeatherKit\Tests\Units
 */
class FootPerSecondTest extends AbstractTestCase
{
    /**
     * Unit instance.
     *
     * @var \Rugaard\WeatherKit\Contracts\Unit
     */
    protected Unit $unit;

    /**
     * Set up test case.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->unit = new FootPerSecond;
    }

    /**
     * Test unit name.
     *
     * @return void
     */
    public function testName(): void
    {
        $this->assertIsString(actual: $this->unit->getName());
        $this->assertEquals(expected: 'Foot per second', actual: $this->unit->getName());
    }

    /**
     * Test unit abbreviation.
     *
     * @return void
     */
    public function testAbbreviation(): void
    {
        $this->assertIsString(actual: $this->unit->getAbbreviation());
        $this->assertEquals(expected: 'ft/s', actual: $this->unit->getAbbreviation());
    }

    /**
     * Test unit __toString.
     *
     * @return void
     */
    public function testToString(): void
    {
        $this->assertIsString(actual: (string) $this->unit);
        $this->assertEquals(expected: 'ft/s', actual: (string) $this->unit);
    }
}
