<?php

declare(strict_types=1);

namespace App\UseCase\Project;

use App\Entity\User;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use App\UseCase\User\UserAuthUseCase;

class CheckUserAccessToProjectUseCase
{
    public function __construct(
        private readonly UserAuthUseCase $userAuthUseCase,
        // private readonly ProjectRepository $projectRepository,
        private readonly ProjectUserRepository $projectUserRepository
    ) {
    }

    public function execute(string $projectId, ?User $user = null): bool
    {
        if ($user === null) {
            $user = $this->userAuthUseCase->execute()->getUser();
        }
        $project = $this->projectUserRepository->createQueryBuilder('pu')
            ->andWhere('pu.project = :projectId')
            ->andWhere('pu.user = :userId')
            ->setParameter('projectId', $projectId)
            ->setParameter('userId', $user->getStringId())
            ->getQuery()
            ->getOneOrNullResult();

            return $project !== null;
    }
}
