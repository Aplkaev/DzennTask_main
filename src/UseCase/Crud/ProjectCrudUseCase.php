<?php

declare(strict_types=1);

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use App\Dto\ProjectDto;
use App\Dto\ProjectUserDto;
use App\Entity\Project;
use App\Entity\User;
use App\Enum\RoleEnum;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\UseCase\Crud\ProjectUserCrudUseCase;
use App\UseCase\Project\GetProjectsUserUseCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ProjectCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly ProjectRepository $projectRepository,
        protected readonly GetProjectsUserUseCase $getProjectsUserUseCase,
        protected readonly Security $security,
        protected readonly ProjectUserCrudUseCase $projectUserCrudUseCase
    )
    {
        parent::__construct($em);
    }

    public function getAll(string $entityClass): array
    {
        return $this->getProjectsUserUseCase->execute();
    }

    public function createEntityFromArray(BaseDto|ProjectDto $dto): mixed
    {
        $hasProject = $this->projectRepository->findOneBy(['name'=>$dto->name]);
        if($hasProject) { 
            throw new BadRequestException("Project name: {$dto->name} already exists");
        }

        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        $project = new Project();
        $project->setName($dto->name);
        $project->setDescription($dto->description);
        $project->setTag($dto->tag);

        $this->projectRepository->save($project);

        $this->projectUserCrudUseCase->createEntityFromArray(new ProjectUserDto(
            userId: $user->getStringId(),
            projectId: $project->getStringId(),
            role: RoleEnum::ROLE_OWNER,
            id: null
        ));

        return $project;
    }
    public function updateEntityFromArray(mixed $project, BaseDto|ProjectDto $dto): mixed
    {
        if($project instanceof Project === false) { 
            throw new BadRequestException('Is not project item');
        }
        
        $project->setDescription($dto->description);
        $project->setTag($dto->tag);
        
        $this->projectRepository->save($project);

        return $project;
    }
}