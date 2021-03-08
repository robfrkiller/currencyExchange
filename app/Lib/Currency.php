<?php

namespace App\Lib;

use App\Lib\CurrencyException;

class Currency
{
    /**
     * @var array
     */
    public static $exchangeRate;

    /**
     * @return void
     */
    public static function init(): void
    {
        $jsonData = file_get_contents(__DIR__ . '/exchangeRate.json');
        self::$exchangeRate = json_decode($jsonData, true);
    }

    /**
     * @param string $src
     * @param string $dst
     * @param float $amount
     * @return float
     *
     * @throws \App\Lib\CurrencyException
     */
    public static function exchange(string $src, string $dst, float $amount): float
    {
        if (!isset(self::$exchangeRate['currencies'][$src])) {
            throw new CurrencyException('Unsupported source currency.');
        }

        if (!isset(self::$exchangeRate['currencies'][$src][$dst])) {
            throw new CurrencyException('Unsupported destination currency.');
        }

        if ($src === $dst || $amount === 0.0) {
            return $amount;
        }

        return (float) bcmul((string) $amount, (string) self::$exchangeRate['currencies'][$src][$dst], 5);
    }
}
