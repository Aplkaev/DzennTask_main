<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;

interface BaseFromModalDto
{
    public static function fromModel(BaseEntity $model): static;
}
