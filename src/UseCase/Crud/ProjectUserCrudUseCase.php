<?php

namespace App\UseCase\Crud;

use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Enum\RoleEnum;
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
        protected readonly UserRepository $userRepository
    )
    {
        parent::__construct($em);
    }

    public function createEntityFromArray(array $data): mixed
    {
        $project = null;
        $user = null;
        $role = null;

        if($data['project_id']) { 
            $project = $this->projectRepository->find($data['project_id']);
            if($project === null) { 
                // TODO вынести в свой exception
                throw new NotFoundHttpException('Not found project:'.$data['project_id']);
            }
        }
        if($data['user_id']) { 
            $user = $this->userRepository->find($data['user_id']);

            if($user === null) { 
                // TODO вынести в свой exception
                throw new NotFoundHttpException('Not found user:'.$data['user_id']);
            }
        }
        if($data['project_id']) { 
            $role = RoleEnum::from($data['role']);
        }

        $projectUser = new ProjectUser();
        $projectUser->setProject($project);
        $projectUser->setRole($role);
        $projectUser->setUser($user);

        $this->repository->save($projectUser);

        return $projectUser;
    }
    public function updateEntityFromArray(mixed $project, array $data): mixed
    {
        if($project instanceof Project === false) { 
            throw new BadRequestException('Is not project item');
        }
        
        $project->setDescription($data['description']);
        $project->setTag($data['tag']);
        
        $this->repository->save($project);

        return null;
    }
}