<?php

declare(strict_types=1);

namespace App\UseCase\Project;

use App\Entity\User;
use App\Exception\AccessDenied\AccessDeniedException;

class VerifyUserAccessToProjectUseCase
{
    public function __construct(
        private readonly CheckUserAccessToProjectUseCase $checkUserAccessToProjectUseCase,
    ) {
    }

    public function execute(string $projectId, bool $exception = true, ?User $user = null): bool
    {
        $result = $this->checkUserAccessToProjectUseCase->execute($projectId, $user);

        if ($exception && $result === false) {
            throw new AccessDeniedException('User has no access to this project');
        }

        return $result;
    }
}
