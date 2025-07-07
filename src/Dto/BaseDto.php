<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;

abstract class BaseDto implements \JsonSerializable
{
    abstract public static function fromArray(array $data): static;

    abstract public static function fromModel(BaseEntity $model): static;

    abstract public function jsonSerialize(): array;
}
