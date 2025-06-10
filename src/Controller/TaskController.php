<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Dto\TasktDto;
use App\Dto\ProjectDto;
use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Shared\Response\ApiResponse;
use App\UseCase\Crud\TaskCrudUseCase;
use App\UseCase\Crud\ProjectCrudUseCase;
use Doctrine\ORM\EntityManagerInterface;
use App\UseCase\Crud\AbstractCrudUseCase;
use App\Controller\AbstractCrudController;
use App\UseCase\Task\GetTasksProjectIdUseCase;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/tasks')]
class TaskController extends AbstractCrudController {     
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly TaskCrudUseCase $taskCrudUseCase,
        protected readonly GetTasksProjectIdUseCase $getTasksProjectIdUseCase
    )
    {
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
}