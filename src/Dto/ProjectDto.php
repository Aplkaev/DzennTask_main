<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\Project;

final class ProjectDto extends BaseDto
{
    private ?string $id{
        get => $this->id;
    }
    private string $name{
        get => $this->name;
    }
    private string $tag{
        get => $this->tag;
    }
    private string $description{
        get => $this->description;
    }
    public function __construct(
        ?string $id,
        string $name,
        string $tag,
        string $description,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->tag = $tag;
        $this->description = $description; 
    }
    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'],
            name: $data['name'],
            tag: $data['tag'],
            description: $data['description']
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