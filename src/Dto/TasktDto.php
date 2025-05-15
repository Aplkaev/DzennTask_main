<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\Project;
use App\Entity\Task;
use DateTime;

final class TasktDto extends BaseDto
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $status,
        public readonly ?int $priority,
        public readonly ?DateTime $deadline,
        public readonly ?int $storyPounts,
        public readonly ?string $parentId,
        public readonly string $assignedToId,
    ) {
    }
    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'],
            title: $data['title'],
            description: $data['description'],
            status: $data['status'],
            priority: $data['priority'],
            deadline: $data['deadline'],
            storyPounts: $data['storyPounts'],
            parentId: $data['parent_id'],
            assignedToId: $data['assigned_to_id']
        );
    }

    public static function fromModel(BaseEntity|Task $model): static
    {
        return new static(
            id: $model->getStringId(),
            title: $model->getTitle(),
            description: $model->getDescrition(),
            status: $model->getStatus(),
            priority: $model->getPriority(),
            deadline: $model->getDeadline(),
            storyPounts: $model->getStoryPounts(),
            parentId: $model->getParent()?->getStringId(),
            assignedToId: $model->getAssgnedTo()->getStringId()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id'=> $this->id,
            'title'=> $this->title,
            'description'=> $this->description,
            'status'=> $this->status,
            'priority'=> $this->priority,
            'deadline'=> $this->deadline,
            'story_pounts'=> $this->storyPounts,
            'parent_id'=> $this->parentId,
            'assigned_to_id'=> $this->assignedToId
        ];
    }

}