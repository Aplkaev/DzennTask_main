<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\BaseEntity;
use App\Entity\ProjectUser;
use App\Enum\RoleEnum;
use App\Shared\Parser\ParseDataTrait;

final class ProjectUserDto extends BaseDto
{
    use ParseDataTrait;

    public function __construct(
        public readonly ?string $id,
        public readonly ?string $projectId,
        public readonly ?string $userId,
        public readonly ?RoleEnum $role,
    ) {
    }

    public static function fromArray(array $data): static
    {
        $roleEnum = null;
        if ($role = self::parseNullableString($data['role'])) {
            $roleEnum = RoleEnum::from($role);
        }

        return new static(
            id: self::parseNullableString($data['id']),
            projectId: self::parseString($data['project_id']),
            userId: self::parseString($data['user_id']),
            role: $roleEnum
        );
    }

    /**
     * Undocumented function.
     *
     * @param ProjectUser $model
     */
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
            'id' => $this->id,
            'project_id' => $this->projectId,
            'user_id' => $this->userId,
            'role' => $this->role->value,
        ];
    }
}
