<?php

declare(strict_types=1);

namespace App\UseCase\Project;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use App\UseCase\User\UserAuthUseCase;

class CheckUserAccessToProjectUseCase
{
    public function __construct(
        private readonly UserAuthUseCase $userAuthUseCase,
        private readonly ProjectRepository $projectRepository,
    ) {
    }


    public function execute(string $projectId, ?User $user = null): bool
    {
        if($user === null) { 
            $user = $this->userAuthUseCase->execute()->getUser();
        }

        $project = $this->projectRepository->createQueryBuilder('p')
            ->leftJoin('p.projectUsers', 'pu')
            ->andWhere('p.id = :projectId')
            ->andWhere('pu.user = :user')
            ->setParameter('projectId', $projectId)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();

        return $project !== null;
    }
}
