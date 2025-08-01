<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\TasktDto;
use App\Entity\Task;
use App\Enum\Task\TaskStatusEnum;
use App\Shared\Response\ApiResponse;
use App\UseCase\Crud\TaskCrudUseCase;
use App\UseCase\Task\GetTasksProjectIdUseCase;
use App\UseCase\Task\TaskSetStatusUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasks')]
class TaskController extends AbstractCrudController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly TaskCrudUseCase $taskCrudUseCase,
        protected readonly GetTasksProjectIdUseCase $getTasksProjectIdUseCase,
        protected readonly TaskSetStatusUseCase $taskSetStatusUseCase,
    ) {
        parent::__construct($em, $taskCrudUseCase);
    }

    public function entityClass(): string
    {
        return Task::class;
    }

    public function getDto(): string
    {
        return TasktDto::class;
    }

    #[Route('/project/{id}', methods: ['GET'])]
    public function taskProjectId(string $id): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $items = $this->getTasksProjectIdUseCase->execute($id);

        return ApiResponse::responseList(
            self::parseResponseDtoList($this->getDto(), $items),
        );
    }

    #[Route('/{id}/done', methods: ['PUT'])]
    public function taskDone(Task $task): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->taskSetStatusUseCase->execute($task, TaskStatusEnum::DONE);

        return ApiResponse::success();
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function taskUpdate(Task $task, #[MapRequestPayload] TasktDto $taskDto): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->taskCrudUseCase->update(Task::class, $task->getStringId(), $taskDto);

        return ApiResponse::success();
    }
}
