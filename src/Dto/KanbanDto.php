<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\KanbanColumn;
use App\Enum\Task\TaskStatusEnum;
use App\Shared\Parser\ParseDataTrait;

final class KanbanDto extends BaseDto
{
    use ParseDataTrait;

    public function __construct(
        public readonly ?string $id,
        public readonly ?string $name,
        public readonly ?string $color,
        public readonly int $position,
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
            name: self::parseNullableString($data['name']),
            color: self::parseNullableString($data['color']),
            position: self::parseInt($data['position']),
            projectId: self::parseString($data['project_id'])
        );
    }

    /**
     * Undocumented function.
     *
     * @param KanbanColumn $model
     */
    public static function fromModel(BaseEntity|KanbanColumn $model): static
    {
        return new static(
            id: $model->getStringId(),
            name: $model->getName(),
            color: $model->getColor(),
            position: $model->getPosition(),
            projectId: $model->getProject()->getStringId()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'position' => $this->position,
            'project_id' => $this->projectId,
        ];
    }
}
