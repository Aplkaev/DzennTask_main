<?php

declare(strict_types=1);

namespace App\UseCase\Task;

use App\Entity\Task;
use App\Repository\TaskRepository;

class GetTasksProjectIdUseCase
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {
    }

    /**
     * @return Task[]
     */
    public function execute(string $projectId): array
    {
        return $this->taskRepository->findBy(
            ['project' => $projectId],
            ['id' => 'DESC']
        ) ?? [];
    }
}
