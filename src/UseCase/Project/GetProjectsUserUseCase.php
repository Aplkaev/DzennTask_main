<?php

declare(strict_types=1);

namespace App\UseCase\Project;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use App\UseCase\User\UserAuthUseCase;

class GetProjectsUserUseCase
{
    public function __construct(
        private readonly UserAuthUseCase $userAuthUseCase,
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    /**
     * @return Project[]
     */
    public function execute(?User $user = null): array
    {
        if ($user === null) {
            $user = $this->userAuthUseCase->execute()->getUser();
        }

        return $this->projectRepository->createQueryBuilder('p')
        ->leftJoin('p.projectUsers', 'pu')
        ->andWhere('pu.user = :user')
        ->setParameter('user', $user)
        ->orderBy('p.id', 'DESC')
        ->getQuery()
        ->getResult();
    }
}
