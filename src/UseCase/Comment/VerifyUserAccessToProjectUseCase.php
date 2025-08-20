<?php

declare(strict_types=1);

namespace App\UseCase\Comment;

use App\Entity\User;
use App\Exception\AccessDenied\AccessDeniedException;

class VerifyUserAccessToCommentUseCase
{
    public function __construct(
        private readonly CheckUserAccessToCommentUseCase $checkUserAccessToCommentUseCase,
    ) {
    }

    public function execute(string $commentId, bool $exception = true, ?User $user = null): bool
    {
        $result = $this->checkUserAccessToCommentUseCase->execute($commentId, $user);
        if ($exception && $result === false) {
            throw new AccessDeniedException('User has no access to this comment');
        }

        return $result;
    }
}
