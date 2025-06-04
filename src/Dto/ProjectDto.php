<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\Project;
use App\Shared\Parser\ParseDataTrait;

final class ProjectDto extends BaseDto
{
    use ParseDataTrait;

    public function __construct(
        public readonly ?string $id,
        public readonly string $name,
        public readonly ?string $tag,
        public readonly ?string $description,
    ) {
    }
    
    public static function fromArray(array $data): static
    {
        return new static(
            id: self::parseNullableString($data['id']),
            name: self::parseString($data['name']),
            tag: self::parseNullableString($data['tag']),
            description: self::parseNullableString($data['description'])
        );
    }

    public static function fromModel(BaseEntity|Project $model): static
    {
        return new static(
            id: $model->getStringId(),
            name: $model->getName(),
            tag: $model->getTag(),
            description: $model->getDescription()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'tag'=>$this->tag,
            'description'=>$this->description
        ];
    }

}