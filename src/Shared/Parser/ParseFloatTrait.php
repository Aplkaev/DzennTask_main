<?php

declare(strict_types=1);

namespace App\Shared\Parser;

trait ParseFloatTrait
{
    use PrepareParseExceptionTrait;

    protected static function parseNullableFloat(mixed &$value): ?float
    {
        if ('0' === (string) $value) {
            return 0;
        }

        return empty($value) ? null : (float) $value;
    }

    protected static function parseFloat(mixed &$value, ?float $defaultValue = null): float
    {
        $castedValue = self::parseNullableFloat($value);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }
}
