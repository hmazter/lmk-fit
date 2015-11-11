<?php

use LMK\ValueObjects\TimespanNanos;

class TimespanNanosTest extends TestCase
{

    public function testConstruct()
    {
        new TimespanNanos(1400000000000000000, 1400000000000000000);
    }

    /**
     * @expectedException LMK\Exceptions\InvalidNanoTimestamp
     */
    public function testConstruct_InvalidStart()
    {
        new TimespanNanos('invalid', 1400000000000000000);
    }

    /**
     * @expectedException LMK\Exceptions\InvalidNanoTimestamp
     */
    public function testConstruct_InvalidEnd()
    {
        new TimespanNanos(1400000000000000000, 'invalid');
    }

    public function testCreateFromString_Week()
    {
        $expectedStart = (new \Carbon\Carbon())->subDays(8);
        $expectedEnd = (new \Carbon\Carbon())->subDays(1);
        $timespan = TimespanNanos::createFromStartString('week');

        $date = \Carbon\Carbon::createFromTimestamp($timespan->getStart() / (1000 * 1000 * 1000), 'UTC');
        $this->assertEquals($expectedStart->format('Y-m-d'), $date->format('Y-m-d'));

        $date = \Carbon\Carbon::createFromTimestamp($timespan->getEnd() / (1000 * 1000 * 1000), 'UTC');
        $this->assertEquals($expectedEnd->format('Y-m-d'), $date->format('Y-m-d'));

        $this->assertTrue($timespan->hasDescription());
        $this->assertContains('week', $timespan->getDescription());
    }

    public function testCreateFromString_Yesterday()
    {
        $expectedStart = (new \Carbon\Carbon())->subDays(1);
        $expectedEnd = (new \Carbon\Carbon())->subDays(1);
        $timespan = TimespanNanos::createFromStartString('yesterday');

        $date = \Carbon\Carbon::createFromTimestamp($timespan->getStart() / (1000 * 1000 * 1000), 'UTC');
        $this->assertEquals($expectedStart->format('Y-m-d'), $date->format('Y-m-d'));

        $date = \Carbon\Carbon::createFromTimestamp($timespan->getEnd() / (1000 * 1000 * 1000), 'UTC');
        $this->assertEquals($expectedEnd->format('Y-m-d'), $date->format('Y-m-d'));

        $this->assertTrue($timespan->hasDescription());
        $this->assertContains('yesterday', $timespan->getDescription());
    }

    public function testCreateFromString_Today()
    {
        $expectedStart = (new \Carbon\Carbon());
        $expectedEnd = (new \Carbon\Carbon());
        $timespan = TimespanNanos::createFromStartString('today');

        $date = \Carbon\Carbon::createFromTimestamp($timespan->getStart() / (1000 * 1000 * 1000), 'UTC');
        $this->assertEquals($expectedStart->format('Y-m-d'), $date->format('Y-m-d'));

        $date = \Carbon\Carbon::createFromTimestamp($timespan->getEnd() / (1000 * 1000 * 1000), 'UTC');
        $this->assertEquals($expectedEnd->format('Y-m-d'), $date->format('Y-m-d'));

        $this->assertTrue($timespan->hasDescription());
        $this->assertContains('today', $timespan->getDescription());
    }

    public function testCreateFromString_Date()
    {
        $testDate = (new \Carbon\Carbon())->subDays(1);
        $timespan = TimespanNanos::createFromStartString($testDate->format('Y-m-d'));

        $date = \Carbon\Carbon::createFromTimestamp($timespan->getStart() / (1000 * 1000 * 1000), 'UTC');
        $this->assertEquals($testDate->format('Y-m-d'), $date->format('Y-m-d'));

        $date = \Carbon\Carbon::createFromTimestamp($timespan->getEnd() / (1000 * 1000 * 1000), 'UTC');
        $this->assertEquals($testDate->format('Y-m-d'), $date->format('Y-m-d'));

        $this->assertFalse($timespan->hasDescription());
    }
}