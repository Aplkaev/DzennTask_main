<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\Task;
use App\Enum\Task\TaskStatusEnum;
use App\Shared\Parser\ParseDataTrait;

final class TasktDto extends BaseDto
{
    use ParseDataTrait;

    public function __construct(
        public readonly ?string $id,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?TaskStatusEnum $status,
        public readonly ?int $priority,
        public readonly ?\DateTimeImmutable $deadline,
        public readonly ?int $storyPoints,
        public readonly ?string $parentId,
        public readonly string $assignedToId,
        public readonly string $projectId,
    ) {
    }

    public static function fromArray(array $data): static
    {
        $status = null;
        if ($statusString = self::parseNullableString($data['status'])) {
            $status = TaskStatusEnum::tryFrom($statusString);
        }

        return new static(
            id: self::parseNullableString($data['id']),
            title: self::parseNullableString($data['title']),
            description: self::parseNullableString($data['description']),
            status: $status,
            priority: self::parseNullableInt($data['priority']),
            deadline: self::parseNullableDateTimeImmutable($data['deadline']),
            storyPoints: self::parseNullableString($data['story_points']),
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
            storyPoints: $model->getStoryPoints(),
            parentId: $model->getParent()?->getStringId(),
            assignedToId: $model->getAssgnedTo()->getStringId(),
            projectId: $model->getProject()->getStringId()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'deadline' => $this->deadline,
            'story_pounts' => $this->storyPoints,
            'parent_id' => $this->parentId,
            'assigned_to_id' => $this->assignedToId,
            'project_id' => $this->projectId,
        ];
    }
}
