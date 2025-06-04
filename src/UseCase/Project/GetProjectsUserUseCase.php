<?php

declare(strict_types=1);

namespace App\UseCase\Project;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\SecurityBundle\Security;

class GetProjectsUserUseCase{
    public function __construct(
        private readonly Security $security,
        private readonly ProjectRepository $projectRepository
    ){}

    /**
     * @return Project[]
     */
    public function execute(): array
    {
        $user = $this->security->getUser();

        return $this->projectRepository->createQueryBuilder('p')
        ->leftJoin('p.projectUsers','pu')
        ->andWhere('pu.user = :user')
        ->setParameter('user', $user)
        ->orderBy('p.id','DESC')
        ->getQuery()
        ->getResult();
    }
}