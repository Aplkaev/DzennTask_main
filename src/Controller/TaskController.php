<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Filter\Task\FilterTaskDto;
use App\Dto\TaskDto;
use App\Entity\Task;
use App\Enum\Task\TaskStatusEnum;
use App\Shared\Response\ApiResponse;
use App\UseCase\Crud\TaskCrudUseCase;
use App\UseCase\Task\GetTasksProjectIdUseCase;
use App\UseCase\Task\TaskSetStatusUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        return TaskDto::class;
    }

    #[Route('/project/{id}', methods: ['GET'])]
    public function taskProjectId(Request $request, string $id): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $filter = FilterTaskDto::fromArray([
            'project_id' => $id,
            ...$request->query->all(),
        ]);

        try {
            $items = $this->getTasksProjectIdUseCase->execute($id, $filter);

            return ApiResponse::responseList(
                self::parseResponseDtoList($this->getDto(), $items),
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    #[Route('/{id}/done', methods: ['PUT'])]
    public function taskDone(Task $task): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->taskSetStatusUseCase->execute($task, TaskStatusEnum::DONE);

        return ApiResponse::success();
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function taskUpdate(Task $task, #[MapRequestPayload] TaskDto $taskDto): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->taskCrudUseCase->update(Task::class, $task->getStringId(), $taskDto);

        return ApiResponse::success();
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return ApiResponse::error(status: Response::HTTP_NOT_FOUND);

    }
}
