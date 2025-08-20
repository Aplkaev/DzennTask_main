<?php

declare(strict_types=1);

namespace App\Shared\Parser;

trait ParseIntTrait
{
    use PrepareParseExceptionTrait;

    protected static function parseNullableInt(mixed &$value): ?int
    {

        if ('0' === (string) $value) {
            return 0;
        }

        return empty($value) ? null : (int) $value;
    }

    protected static function parseInt(mixed &$value, ?int $defaultValue = null): int
    {
        $castedValue = self::parseNullableInt($value);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }
}
