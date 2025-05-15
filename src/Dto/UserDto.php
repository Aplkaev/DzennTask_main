<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\Project;
use App\Entity\User;

final class UserDto extends BaseDto
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $email,
        public readonly string $avatarUrl,
        public readonly string $timezone,
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'],
            email: $data['email'],
            avatarUrl: $data['avatar_url'],
            timezone: $data['timezone']
        );
    }

    public static function fromModel(BaseEntity|User $model): static
    {
        return new static(
            id: $model->getStringId(),
            email: $model->getEmail(),
            avatarUrl: $model->getAvatarUrl(),
            timezone: $model->getTimezone()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id'=>$this->id,
            'email'=> $this->email,
            'avatarUrl'=> $this->avatarUrl,
            'timezone'=> $this->timezone
        ];
    }

}