<?php

namespace App\Casts;

use App\Support\MoneyHelper;
use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<Money, string>
 */
class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): Money
    {
        return MoneyHelper::ofMinor($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Money|string $value
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $value instanceof Money ? (string) $value->getUnscaledAmount() : $value;
    }
}
