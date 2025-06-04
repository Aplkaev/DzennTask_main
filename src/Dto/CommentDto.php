<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\Comment;
use App\Entity\Notification;
use App\Entity\Project;
use App\Entity\Task;
use App\Enum\AllEntityTypeEnum;
use App\Shared\Parser\ParseDataTrait;
use DateTime;

final class CommentDto extends BaseDto
{
    use ParseDataTrait;

    public function __construct(
        public readonly ?string $id,
        public readonly ?AllEntityTypeEnum $entityType,
        public readonly ?string $entityId,
        public readonly ?string $userId,
        public readonly ?string $text,
        public readonly ?string $parentId,
    ) {
    }
    public static function fromArray(array $data): static
    {
        $entityType = null;
        if($type = self::parseNullableString($data['entity_type'])) { 
            $entityType = AllEntityTypeEnum::from($type);
        }

        return new static(
            id: self::parseNullableString($data['id']),
            entityType: $entityType,
            entityId: self::parseNullableString($data['entity_id']),
            userId: self::parseNullableString($data['user_id']),
            text: self::parseString($data['text']),
            parentId: self::parseNullableString($data['parent_id']),
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
            parentId: $model->getParent()->getStringId()
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
            'parent_id'=>$this->parentId
        ];
    }

}