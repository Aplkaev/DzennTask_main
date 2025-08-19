<?php

declare(strict_types=1);

namespace App\UseCase\Crud;

use App\Dto\BaseDto;
use App\Dto\TasktDto;
use App\Entity\Task;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\UseCase\Project\VerifyUserAccessToProjectUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TaskCrudUseCase extends AbstractCrudUseCase
{
    public function __construct(
        EntityManagerInterface $em,
        protected readonly TaskRepository $taskRepository,
        protected readonly Security $security,
        protected readonly ProjectRepository $projectRepository,
        protected readonly UserRepository $userRepository,
        protected readonly VerifyUserAccessToProjectUseCase $verifyUserAccessToProjectUseCase
    ) {
        parent::__construct($em);
    }

    /**
     * @param TasktDto $dto
     */
    public function createEntityFromArray(BaseDto|TasktDto $dto): mixed
    {
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
     * @param Task $task
     * @param TasktDto $dto
     */
    public function updateEntityFromArray(mixed $task, BaseDto|TasktDto $dto): mixed
    {
        if ($task instanceof Task === false) {
            throw new BadRequestException('Is not project item');
        }

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
