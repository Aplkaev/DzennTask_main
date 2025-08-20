<?php

declare(strict_types=1);

namespace App\Shared\Parser;

use App\Dto\BaseDto;

trait ParseDtoTrait
{
    use PrepareParseExceptionTrait;
    use ParseArrayTrait;

    /**
     * @template T
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    protected static function parseNullableRequestDto(string $className, mixed &$value)
    {
        $parsedValue = self::parseNullableArray($value);
        if (null === $parsedValue) {
            return;
        }

        if (!is_subclass_of($className, BaseDto::class)) {
            throw self::prepareParseException($className.' is not instance of '.BaseDto::class);
        }

        return $className::fromArray($parsedValue);
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     * @param T|null          $defaultValue
     *
     * @return T
     */
    protected static function parseRequestDto(string $className, mixed &$value, $defaultValue = null)
    {
        $castedValue = self::parseNullableRequestDto($className, $value);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     *
     * @return array<T>|null
     */
    protected static function parseNullableRequestDtoList(string $className, mixed &$value): ?array
    {
        $parsedValues = self::parseNullableArray($value);
        if (null === $parsedValues) {
            return null;
        }

        $result = [];
        foreach ($parsedValues as $parsedValue) {
            $result[] = self::parseRequestDto($className, $parsedValue);
        }

        return $result;
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     * @param array<T>|null   $defaultValue
     *
     * @return array<T>
     */
    protected static function parseRequestDtoList(string $className, mixed &$value, $defaultValue = null): array
    {
        $castedValue = self::parseNullableRequestDtoList($className, $value);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    protected static function parseNullableResponseDto(string $className, mixed $value)
    {
        if (null === $value) {
            return;
        }

        if (!is_subclass_of($className, BaseDto::class)) {
            throw self::prepareParseException($className.' is not instance of '.BaseDto::class);
        }

        return $className::fromModel($value);
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     * @param T|null          $defaultValue
     *
     * @return T
     */
    protected static function parseResponseDto(string $className, mixed $value, $defaultValue = null)
    {
        $castedValue = self::parseNullableResponseDto($className, $value);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     *
     * @return array<T>|null
     */
    protected static function parseNullableResponseDtoList(string $className, mixed &$value): ?array
    {
        $parsedValues = self::parseNullableArray($value);
        if (null === $parsedValues) {
            return null;
        }

        $result = [];
        foreach ($parsedValues as $parsedValue) {
            $result[] = self::parseResponseDto($className, $parsedValue);
        }

        return $result;
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     * @param array<T>|null   $defaultValue
     *
     * @return array<T>
     */
    protected static function parseResponseDtoList(string $className, mixed &$value, $defaultValue = null): array
    {
        $castedValue = self::parseNullableResponseDtoList($className, $value);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }
}
