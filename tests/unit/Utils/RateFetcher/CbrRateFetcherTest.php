<?php

namespace App\Tests\unit\Utils;

use App\Utils\RateFetcher\CbrRateFetcher;
use PHPUnit\Framework\TestCase;

class CbrRateFetcherTest extends TestCase
{
    public function testGetRateFromValue_ReturnsProperRate()
    {
        $stub = $this->getMockBuilder(CbrRateFetcher::class)
            ->disableOriginalConstructor()
            ->setMethods(['normalizeValue'])
            ->getMock();

        $stub->method('normalizeValue')
            ->with('5,234')->willReturn(5.234);

        $this->assertEquals(
            0.1911,
            $stub->getRateFromValue('5,234'),
            'Ensure that CBR fetcher properly calculates rate conversion 1/Base (RUB)'
        );
    }
}
