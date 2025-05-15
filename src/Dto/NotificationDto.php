<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\Notification;
use App\Entity\Project;
use App\Entity\Task;
use DateTime;

final class NotificationDto extends BaseDto
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $type,
        public readonly ?string $userId,
        public readonly ?string $text,
        public readonly ?bool $isRead,
    ) {
    }
    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'],
            type: $data['type'],
            userId: $data['user_id'],
            text: $data['text'],
            isRead: $data['is_read']
        );
    }

    public static function fromModel(BaseEntity|Notification $model): static
    {
        return new static(
            id: $model->getStringId(),
            type: $model->getType(),
            userId: $model->getUser()->getStringId(),
            text: $model->getText(),
            isRead: $model->isRead()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id'=> $this->id,
            'type'=> $this->type,
            'user_id'=> $this->userId,
            'text'=> $this->text,
            'is_read'=> $this->isRead
        ];
    }

}