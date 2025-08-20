<?php

declare(strict_types=1);

namespace App\UseCase\Task;

use App\Entity\User;
use App\Repository\TaskRepository;
use App\UseCase\User\UserAuthUseCase;

class CheckUserAccessToTaskUseCase
{
    public function __construct(
        private readonly UserAuthUseCase $userAuthUseCase,
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function execute(string $taskId, ?User $user = null): bool
    {
        if ($user === null) {
            $user = $this->userAuthUseCase->execute()->getUser();
        }

        $task = $this->taskRepository->createQueryBuilder('t')
            ->leftJoin('t.project', 'p')
            ->leftJoin('p.projectUsers', 'pu')
            ->andWhere('t.id = :taskId')
            ->andWhere('pu.user = :user')
            ->setParameter('taskId', $taskId)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();

        return $task !== null;
    }
}
