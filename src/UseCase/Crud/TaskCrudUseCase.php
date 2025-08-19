<?php

declare(strict_types=1);

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use App\Dto\TaskDto;
use App\Entity\Task;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\UseCase\Project\VerifyUserAccessToProjectUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class TaskCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly TaskRepository $taskRepository,
        protected readonly Security $security,
        protected readonly ProjectRepository $projectRepository,
        protected readonly UserRepository $userRepository,
        protected readonly VerifyUserAccessToProjectUseCase $verifyUserAccessToProjectUseCase,
    ) {
        parent::__construct($em);
    }

    /**
     * @param TaskDto $dto
     */
    public function createEntityFromArray(BaseDto|TaskDto $dto): Task
    {
        // \assert($dto instanceof TaskDto);
        $this->verifyUserAccessToProjectUseCase->execute($dto->projectId);

        $project = $this->projectRepository->find($dto->projectId);
        $user = $this->userRepository->find($dto->assignedToId);

        $task = new Task();
        $task->setAssgnedTo($user);
        $task->setProject($project);
        $task->setPriority($dto->priority);
        $task->setDescrition($dto->description);
        $task->setTitle($dto->title);
        $task->setStatus($dto->status);
        $task->setStoryPoints($dto->storyPoints);

        $this->taskRepository->save($task);

        return $task;
    }

    /**
     * @param Task    $task
     * @param TaskDto $dto
     */
    public function updateEntityFromArray(mixed $task, BaseDto|TaskDto $dto): Task
    {
        // \assert($dto instanceof TaskDto);
        // \assert($task instanceof Task);

        $this->verifyUserAccessToProjectUseCase->execute($dto->projectId);

        $task->setPriority($dto->priority);
        $task->setDescrition($dto->description);
        $task->setTitle($dto->title);
        $task->setStatus($dto->status);
        $task->setStoryPoints($dto->storyPoints);

        $this->taskRepository->save($task);

        return $task;
    }
}
