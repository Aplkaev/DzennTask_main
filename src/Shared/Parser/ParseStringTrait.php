<?php

declare(strict_types=1);

namespace App\Shared\Parser;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

trait ParseStringTrait
{
    use PrepareParseExceptionTrait;

    // @description Reference(&) needed for passing Undefined array keys
    protected static function parseNullableString(mixed &$value, ?int $maxLength = 200): ?string
    {
        try {
            if (null === $value) {
                return null;
            }

            if (null !== $maxLength && mb_strlen((string) $value) > $maxLength) {
                return throw new BadRequestException("String length cannot exceed {$maxLength} characters");
            }

            return (string) $value;
        } catch (\Exception $error) {
            throw self::prepareParseException($error->getMessage());
        }
    }

    // @description Reference(&) needed for passing Undefined array keys
    protected static function parseString(mixed &$value, ?int $maxLength = 200, ?string $defaultValue = null): string
    {
        $castedValue = self::parseNullableString($value, $maxLength);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }
}
