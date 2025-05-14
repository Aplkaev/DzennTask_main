<?php

declare(strict_types=1);

namespace App\Dto;

use JsonSerializable;
use App\Entity\BaseEntity;

abstract class BaseDto implements JsonSerializable { 
    public abstract static function fromArray(array $data): static;

    public abstract static function fromModel(BaseEntity $model): static;

    public abstract function jsonSerialize(): array;
}