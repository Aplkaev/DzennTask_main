<?php

declare(strict_types=1);

namespace App\UseCase\Task;

use App\Entity\Task;
use App\Enum\Task\TaskStatusEnum;
use App\Repository\TaskRepository;

class TaskSetStatusUseCase
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly VerifyUserAccessToTasktUseCase $verifyUserAccessToTasktUseCase,
    ) {
    }

    public function execute(Task $task, TaskStatusEnum $status): void
    {
        $this->verifyUserAccessToTasktUseCase->execute($task->getStringId());

        $task->setStatus($status);
        // отправим нотификацию на почту, отправим инфу в ws для обновления у всех
        $this->taskRepository->save($task);
    }
}
