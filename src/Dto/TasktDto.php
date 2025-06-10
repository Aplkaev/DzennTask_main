<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\Project;
use App\Entity\Task;
use App\Shared\Parser\ParseDataTrait;
use DateTime;
use DateTimeImmutable;

final class TasktDto extends BaseDto
{
    use ParseDataTrait;

    public function __construct(
        public readonly ?string $id,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $status,
        public readonly ?int $priority,
        public readonly ?DateTimeImmutable $deadline,
        public readonly ?int $storyPounts,
        public readonly ?string $parentId,
        public readonly string $assignedToId,
        public readonly string $projectId,
    ) {
    }
    public static function fromArray(array $data): static
    {
        return new static(
            id: self::parseNullableString($data['id']),
            title:self::parseNullableString($data['title']),
            description: self::parseNullableString($data['description']),
            status: self::parseNullableString($data['status']),
            priority: self::parseNullableInt($data['priority']),
            deadline: self::parseNullableDateTimeImmutable($data['deadline']),
            storyPounts: self::parseNullableString($data['story_pounts']),
            parentId: self::parseNullableString($data['parent_id']),
            assignedToId: self::parseString($data['assigned_to_id']),
            projectId: self::parseString($data['project_id'])
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
            assignedToId: $model->getAssgnedTo()->getStringId(),
            projectId: $model->getProject()->getStringId()
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
            'assigned_to_id'=> $this->assignedToId,
            'project_id'=>$this->projectId
        ];
    }

}