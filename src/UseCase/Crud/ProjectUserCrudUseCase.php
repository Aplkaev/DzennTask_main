<?php

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use App\Dto\ProjectUserDto;
use App\Entity\ProjectUser;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectUserCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly ProjectUserRepository $repository,
        protected readonly ProjectRepository $projectRepository,
        protected readonly UserRepository $userRepository,
    ) {
        parent::__construct($em);
    }

    public function createEntityFromArray(BaseDto|ProjectUserDto $dto): mixed
    {
        $project = null;
        $user = null;
        $role = null;

        $project = $this->projectRepository->find($dto->projectId);
        if (null === $project) {
            // TODO вынести в свой exception
            throw new NotFoundHttpException("Not found project: {$dto->projectId}");
        }

        $user = $this->userRepository->find($dto->userId);

        if (null === $user) {
            // TODO вынести в свой exception
            throw new NotFoundHttpException("Not found user: {$dto->userId}");
        }

        $projectUser = new ProjectUser();
        $projectUser->setProject($project);
        $projectUser->setRole($dto->role);
        $projectUser->setUser($user);

        $this->repository->save($projectUser);

        return $projectUser;
    }

    public function updateEntityFromArray(mixed $projectUser, BaseDto|ProjectUserDto $dto): mixed
    {
        if (false === $projectUser instanceof ProjectUser) {
            throw new BadRequestException('Is not project item');
        }

        $projectUser->setRole($dto->role);

        $this->repository->save($projectUser);

        return null;
    }
}
