<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\KanbanDto;
use App\Entity\KanbanColumn;
use App\Entity\Task;
use App\Enum\Task\TaskStatusEnum;
use App\Shared\Response\ApiResponse;
use App\UseCase\Crud\KanbanCrudUseCase;
use App\UseCase\Task\GetTasksProjectIdUseCase;
use App\UseCase\Task\TaskSetStatusUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/kanban')]
class Kanban extends AbstractCrudController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly KanbanCrudUseCase $kanbanCrudUseCase,
        protected readonly GetTasksProjectIdUseCase $getTasksProjectIdUseCase,
        protected readonly TaskSetStatusUseCase $taskSetStatusUseCase,
    ) {
        parent::__construct($em, $kanbanCrudUseCase);
    }

    public function entityClass(): string
    {
        return Kanban::class;
    }

    public function getDto(): string
    {
        return KanbanDto::class;
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
    public function taskUpdate(KanbanColumn $kanban, #[MapRequestPayload] KanbanDto $kanbanDto): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // TODO можно улучшить, убрать передача $kanban->getStringId() и KanbanColumn::class
        $this->kanbanCrudUseCase->update(KanbanColumn::class, $kanban->getStringId(), $kanbanDto);

        return ApiResponse::success();
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return ApiResponse::error(status: Response::HTTP_NOT_FOUND);

    }
}
