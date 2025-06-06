<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ProjectDto;
use App\Entity\Project;
use App\Shared\Response\ApiResponse;
use App\UseCase\Crud\ProjectCrudUseCase;
use Doctrine\ORM\EntityManagerInterface;
use App\UseCase\Crud\AbstractCrudUseCase;
use App\Controller\AbstractCrudController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/project')]
class ProjectController extends AbstractCrudController {     
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly ProjectCrudUseCase $projectCrudUseCase
    )
    {
        parent::__construct($em, $projectCrudUseCase);
    }

    public function entityClass(): string
    {
        return Project::class;
    }

    public function getDto(): string
    {
        return ProjectDto::class;
    }
}