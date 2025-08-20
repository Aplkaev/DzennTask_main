<?php

declare(strict_types=1);

namespace App\UseCase\Task;

use App\Entity\User;
use App\Exception\AccessDenied\AccessDeniedException;

class VerifyUserAccessToTaskUseCase
{
    public function __construct(
        private readonly CheckUserAccessToTaskUseCase $checkUserAccessToTaskUseCase,
    ) {
    }

    public function execute(string $taskId, bool $exception = true, ?User $user = null): bool
    {
        $result = $this->checkUserAccessToTaskUseCase->execute($taskId, $user);
        if ($exception && $result === false) {
            throw new AccessDeniedException('User has no access to this task');
        }

        return $result;
    }
}
