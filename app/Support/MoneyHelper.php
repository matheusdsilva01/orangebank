<?php

namespace App\Support;

use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;
use Illuminate\Support\Arr;

class MoneyHelper
{
    public static function ofMinor(string $amount, string $currency = 'BRL'): Money
    {
        return Money::ofMinor($amount, self::getCustomCurrency($currency), null, RoundingMode::HALF_EVEN);
    }

    public static function of(string|float|int $amount, string $currency = 'BRL'): Money
    {
        return Money::of($amount, self::getCustomCurrency($currency), null, RoundingMode::HALF_EVEN);
    }

    public static function getCustomCurrency(?string $currency): string|Currency
    {
        return Arr::has(config('finance.currencies'), $currency)
            ? new Currency(...config('finance.currencies')[$currency])
            : $currency;
    }

    public static function applyTax(Money $money, float $taxPercent): Money
    {
        $taxMultiplier = 1 + $taxPercent / 100; // ex: 0.5% -> 1.005

        return $money->multipliedBy($taxMultiplier, RoundingMode::HALF_EVEN);
    }

    public static function format(Money $money): string
    {
        $formatter = new \NumberFormatter('pt_BR', \NumberFormatter::CURRENCY);
        $formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_DOWN);
        $formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 2);

        return $money->formatWith($formatter);
    }
}
