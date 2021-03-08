<?php

declare(strict_types=1);

use App\Lib\Currency;
use App\Lib\CurrencyException;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    protected function setUp(): void
    {
        Currency::init();
    }

    public function testRate(): void
    {
        $currencies = ['TWD', 'JPY', 'USD'];

        foreach ($currencies as $srcCurrency) {
            $this->assertArrayHasKey($srcCurrency, Currency::$exchangeRate['currencies']);

            foreach ($currencies as $dstCurrency) {
                $this->assertArrayHasKey($dstCurrency, Currency::$exchangeRate['currencies'][$srcCurrency]);
            }
        }
    }

    public function testUnsupported(): void
    {
        $this->expectException(CurrencyException::class);
        Currency::exchange('AAA', 'TWD', 3);
        Currency::exchange('TWD', 'AAA', 3);
    }

    public function testExchange(): void
    {
        $this->assertEquals(
            4.03563,
            Currency::exchange('TWD','USD', 123)
        );

        $this->assertEquals(
            2.84085,
            Currency::exchange('JPY','USD', 321)
        );

        $this->assertEquals(
            3386.04256,
            Currency::exchange('USD','TWD', 111.222)
        );

        $this->assertEquals(
            -3386.04256,
            Currency::exchange('USD','TWD', -111.222)
        );

        $this->assertEquals(
            0.0,
            Currency::exchange('USD','TWD', 0)
        );

        $this->assertEquals(
            43.21,
            Currency::exchange('USD','USD', 43.21)
        );
    }
}
