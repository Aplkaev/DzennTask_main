<?php

declare(strict_types=1);

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use App\Entity\Task;
use App\Entity\User;
use App\Dto\TasktDto;
use App\Enum\RoleEnum;
use App\Dto\ProjectDto;
use App\Entity\Project;
use App\Dto\ProjectUserDto;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\UseCase\Crud\ProjectUserCrudUseCase;
use App\UseCase\Project\GetProjectsUserUseCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TaskCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly TaskRepository $taskRepository,
        protected readonly Security $security,
        protected readonly ProjectRepository $projectRepository,
        protected readonly UserRepository $userRepository
    )
    {
        parent::__construct($em);
    }

    public function createEntityFromArray(BaseDto|TasktDto $dto): mixed
    {
        $project = $this->projectRepository->find($dto->projectId);
        $user = $this->userRepository->find($dto->assignedToId);


        $task = new Task();
        $task->setAssgnedTo($user);
        $task->setProject($project);
        $task->setDeadline($dto->deadline);
        $task->setPriority($dto->priority);
        $task->setDescrition($dto->description);
        $task->setTitle($dto->title);
        $task->setStatus($dto->status);
        $task->setStoryPoints($dto->storyPouits);
        // $task->setKanbanColumn($dto->k)


        $this->taskRepository->save($task);

        return $task;
    }
    public function updateEntityFromArray(mixed $task, BaseDto|TasktDto $dto): mixed
    {
        if($task instanceof Task === false) { 
            throw new BadRequestException('Is not project item');
        }
        
        $task->setDeadline($dto->deadline);
        $task->setPriority($dto->priority);
        $task->setDescrition($dto->description);
        $task->setTitle($dto->title);
        $task->setStatus($dto->status);
        $task->setStoryPoints($dto->storyPoints);
        // $task->setKanbanColumn($dto->k)

        $this->taskRepository->save($task);

        return $task;
    }
}