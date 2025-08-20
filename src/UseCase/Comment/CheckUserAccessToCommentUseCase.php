<?php

declare(strict_types=1);

namespace App\UseCase\Comment;

use App\Entity\User;
use App\Repository\CommentRepository;
use App\UseCase\User\UserAuthUseCase;

class CheckUserAccessToCommentUseCase
{
    public function __construct(
        private readonly UserAuthUseCase $userAuthUseCase,
        private readonly CommentRepository $commentRepository,
    ) {
    }

    public function execute(string $commentId, ?User $user = null): bool
    {
        if ($user === null) {
            $user = $this->userAuthUseCase->execute()->getUser();
        }

        $comment = $this->commentRepository->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->andWhere('c.author = :user')
            ->setParameter('id', $commentId)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();

        return $comment !== null;
    }
}
