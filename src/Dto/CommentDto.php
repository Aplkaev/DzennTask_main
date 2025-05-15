<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\Comment;
use App\Entity\Notification;
use App\Entity\Project;
use App\Entity\Task;
use App\Enum\AllEntityTypeEnum;
use DateTime;

final class CommentDto extends BaseDto
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?AllEntityTypeEnum $entityType,
        public readonly ?string $entityId,
        public readonly ?string $userId,
        public readonly ?string $text,
    ) {
    }
    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'],
            entityType: $data['entity_type'],
            entityId: $data['entity_id'],
            userId: $data['user_id'],
            text: $data['text'],
        );
    }

    public static function fromModel(BaseEntity|Comment $model): static
    {
        return new static(
            id: $model->getStringId(),
            entityType: $model->getEntityType(),
            entityId: $model->getEntityId(),
            userId: $model->getAuthor()->getStringId(),
            text: $model->getText(),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id'=> $this->id,
            'user_id'=> $this->userId,
            'text'=> $this->text,
            'entity_type'=> $this->entityType,
            'entity_id'=> $this->entityId,
        ];
    }

}