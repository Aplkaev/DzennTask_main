<?php

declare(strict_types=1);

namespace App\Dto;

interface BaseJsonSerializeDto
{
    public function jsonSerialize(): array;
}
