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
        $_filter = [
            'project' => $projectId,
        ];

        if ($filter->getStatus() && $filter->getStatus() !== 'all') {
            $_filter['status'] = $filter->getStatus();
        }

        $this->verifyUserAccessToProjectUseCase->execute($projectId);

        return $this->taskRepository->findBy(
            $_filter,
            [
                'priority' => 'DESC',
                'id' => 'DESC',
            ]
        ) ?? [];
    }
}
