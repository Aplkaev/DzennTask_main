<?php

declare(strict_types=1);

namespace App\Shared\Parser;

trait ParseArrayTrait
{
    use PrepareParseExceptionTrait;

    /**
     * @return array<mixed>
     */
    protected static function parseNullableArray(mixed &$value): ?array
    {
        if (!isset($value)) {
            return null;
        }

        if (empty($value)) {
            return [];
        }

        return (array) $value;
    }

    /**
     * @return array<mixed>
     */
    protected static function parseArray(mixed &$value, ?array $defaultValue = null): array
    {
        $castedValue = self::parseNullableArray($value);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }
}
