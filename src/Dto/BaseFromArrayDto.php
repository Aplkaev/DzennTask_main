<?php

declare(strict_types=1);

namespace App\Dto;

interface BaseFromArrayDto
{
    public static function fromArray(array $data): static;
}
