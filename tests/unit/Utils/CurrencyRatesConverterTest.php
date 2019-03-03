<?php

namespace App\Tests\unit\Utils;

use App\Utils\CurrencyRatesConverter;
use PHPUnit\Framework\TestCase;

class CurrencyRatesConverterTest extends TestCase
{
    public function testConvert_ReturnsProperValue()
    {
        $stub = $this->getMockBuilder(CurrencyRatesConverter::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRateByCode'])
            ->getMock();

        $stub->method('getRateByCode')
            ->withConsecutive(['USD'], ['RUB'])->willReturnOnConsecutiveCalls(0.1234, 1.2345);

        $this->assertEquals(
            456.9741,
            $stub->convert('USD', 'RUB', 45.6789),
            'Check main rate conversion function to return proper value'
        );
    }
}
