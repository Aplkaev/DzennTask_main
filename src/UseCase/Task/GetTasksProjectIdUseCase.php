<?php

declare(strict_types=1);

namespace App\UseCase\Task;

use App\Dto\Filter\Task\FilterTaskDto;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\UseCase\Project\VerifyUserAccessToProjectUseCase;

class GetTasksProjectIdUseCase
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly VerifyUserAccessToProjectUseCase $verifyUserAccessToProjectUseCase,
    ) {
    }

    /**
     * @return Task[]
     */
    public function execute(string $projectId, ?FilterTaskDto $filter = null): array
    {
        $this->verifyUserAccessToProjectUseCase->execute($projectId);

        return $this->taskRepository->findByFilters($filter);
    }
}
