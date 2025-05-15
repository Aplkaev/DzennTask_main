<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\Task;
use App\Enum\RoleEnum;
use DateTime;

final class ProjectUserDto extends BaseDto
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $projectId,
        public readonly ?string $userId,
        public readonly ?RoleEnum $role,
    ) {
    }
    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'],
            projectId: $data['project_id'],
            userId: $data['user_id'],
            role: RoleEnum::from($data['role'])
        );
    }

    public static function fromModel(BaseEntity|ProjectUser $model): static
    {
        return new static(
            id: $model->getStringId(),
            projectId: $model->getProject()->getStringId(),
            userId: $model->getUser()->getStringId(),
            role: $model->getRole()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id'=> $this->id,
            'project_id'=> $this->projectId,
            'user_id'=> $this->userId,
            'role'=> $this->role->value
        ];
    }

}